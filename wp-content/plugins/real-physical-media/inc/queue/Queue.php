<?php

namespace DevOwl\RealPhysicalMedia\queue;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\configuration\Options;
use DevOwl\RealPhysicalMedia\handler\Handler;
use DevOwl\RealPhysicalMedia\misc\UploadDir;
use DevOwl\RealPhysicalMedia\view\AdminBar;
use MatthiasWeb\RealMediaLibrary\Core;
use WP_Error;
use Exception;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Queue management.
 */
class Queue {
    use UtilsProvider;
    public static $me;
    /**
     * Clear the complete queue.
     */
    public function clear() {
        global $wpdb;
        $table_name = $this->getTableName('queue');
        // phpcs:disable WordPress.DB.PreparedSQL
        return $wpdb->query('DELETE FROM ' . $table_name);
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Clear the complete log.
     *
     * @param int $max The maximum id - 500 gets deleted so it is sure only 500 entries left
     */
    public function clearLog($max) {
        global $wpdb;
        $table_name = $this->getTableName('log');
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query('DELETE FROM ' . $table_name . ' WHERE id <= ' . ($max - 500));
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Process the queue with a maximum execution time in seconds and an amount
     * of items at once.
     *
     * @param int $maxExecutionSeconds
     * @param int $amount
     * @return WP_Error|array Array key (attachment id) and value (array, new urls)
     */
    public function process($maxExecutionSeconds, $amount = 100) {
        $items = $this->get(0, $amount);
        $done = [];
        $optName = RPM_OPT_PREFIX . '_ppr';
        $start = \microtime(\true);
        foreach ($items as $item) {
            // Rethrow exception if the queue is still paused
            $exc = $this->createExceptionFromDb();
            if (is_wp_error($exc)) {
                return $exc;
            }
            try {
                $itemResult = $item->process();
                if ($itemResult === \false) {
                    return new \WP_Error(
                        'rpm_queue_process',
                        __('Process failed due to an unexpected error.', RPM_TD),
                        ['status' => 500]
                    );
                }
                if (\is_array($itemResult)) {
                    $done[$item->attachment] = $itemResult;
                }
                $this->clearException();
            } catch (\Exception $e) {
                // Save stacktrace to DB and pause queue
                $stack = [
                    'id' => $item->attachment,
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'stack' => $e->getTraceAsString(),
                    'status' => 500,
                    'at' => \time()
                ];
                update_option(RPM_OPT_PREFIX . '_queuestack', \json_encode($stack));
                return $this->createException($item->attachment, $stack, \true);
            }
            if (\microtime(\true) - $start > $maxExecutionSeconds) {
                update_option($optName, \count($done));
                return $done;
            }
        }
        return $done;
    }
    /**
     * Clear the exception so the queue can automatically continue.
     *
     * @param boolean $skip
     */
    public function clearException($skip = \false) {
        $exc = $this->createExceptionFromDb(\true);
        if ($exc !== null && $skip) {
            $this->remove($exc['data']['id']);
        }
        return update_option(RPM_OPT_PREFIX . '_queuestack', \false);
    }
    /**
     * Create the paused error exception from the database.
     *
     * @param boolean $asArray
     */
    public function createExceptionFromDb($asArray = \false) {
        // Rethrow exception if the queue is still paused
        add_option(RPM_OPT_PREFIX . '_queuestack', \false);
        $stack = get_option(RPM_OPT_PREFIX . '_queuestack');
        if (!empty($stack)) {
            $stack = \json_decode($stack, ARRAY_A);
            return $this->createException($stack['id'], $stack, \false, $asArray);
        }
        return null;
    }
    /**
     * Create an exception for the process.
     *
     * @param int $attachment
     * @param array $stack
     * @param boolean $atRuntime
     * @param boolean $asArray
     */
    public function createException($attachment, $stack, $atRuntime, $asArray = \false) {
        $code = 'rpm_queue_paused';
        $message = \sprintf(
            // translators:
            __(
                'Something went wrong with the handler foe the file %s. The automatic change detection is now paused.',
                RPM_TD
            ),
            wp_date('l jS \\of F Y h:i:s A', $stack['at'])
        );
        $data = \array_merge(
            [
                'atRuntime' => $atRuntime,
                'id' => $attachment,
                'attachmentUrl' => admin_url('post.php?post=' . $attachment . '&action=edit')
            ],
            $stack
        );
        return $asArray
            ? ['code' => $code, 'message' => $message, 'data' => $data]
            : new \WP_Error($code, $message, $data);
    }
    /**
     * Return the remaining amount of entries.
     */
    public function remaining() {
        global $wpdb;
        $table_name = $this->getTableName('queue');
        // phpcs:disable WordPress.DB.PreparedSQL
        return (int) $wpdb->get_var(
            'SELECT COUNT(*) FROM ' .
                $table_name .
                ' q
            INNER JOIN ' .
                $wpdb->postmeta .
                ' wpm
            ON wpm.post_id = q.attachment
            AND wpm.meta_key = \'_wp_attached_file\''
        );
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Return the average duration left for this queue.
     *
     * @param int $amount The amount for multiplicator
     * @param boolean $formatted If true the result is a string in format 00:00:00
     * @param boolean $withppr If true the process per reqeust is respected
     */
    public function avgDuration($amount = 1, $formatted = \false, $withppr = \true) {
        global $wpdb;
        $countDownProcessing = \DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->getCountdownProcessing();
        $table_name = $this->getTableName('log');
        // phpcs:disable WordPress.DB.PreparedSQL
        $seconds = \floatval($wpdb->get_var('SELECT AVG(duration) / 1000 FROM ' . $table_name)) * $amount;
        // phpcs:enable WordPress.DB.PreparedSQL
        $seconds += \floatval($amount) * 0.25;
        // Request tolerance
        // Add process per request
        if ($withppr) {
            add_option(RPM_OPT_PREFIX . '_ppr', 0);
            $ppr = get_option(RPM_OPT_PREFIX . '_ppr', 0);
            if ($ppr > 0) {
                if ($amount > $ppr) {
                    $seconds += ($amount / $ppr) * $countDownProcessing;
                } else {
                    $seconds += $countDownProcessing;
                }
            } else {
                $seconds += $countDownProcessing;
            }
        }
        $t = \round($seconds);
        if (!$formatted) {
            return $t;
        }
        $formatted = \sprintf('%02d:%02d:%02d', $t / 3600, ($t / 60) % 60, $t % 60);
        return $formatted;
    }
    /**
     * Prepare the response for WP REST API.
     *
     * @param int $skip
     * @param int $top
     */
    public function prepareResponse($skip, $top) {
        $remaining = $this->remaining();
        return [
            'rows' => $this->get($skip, $top),
            'count' => $remaining,
            'estimate' => $this->avgDuration($remaining, \true)
        ];
    }
    /**
     * Get items from the queue.
     *
     * @param int $skip
     * @param int $top
     */
    public function get($skip = 0, $top = 5) {
        global $wpdb;
        $top = \intval($top);
        if ($top > 100) {
            $top = 100;
        } elseif ($top <= 0) {
            $top = 5;
        }
        $table_name = $this->getTableName('queue');
        $table_name_rml = \MatthiasWeb\RealMediaLibrary\Core::getInstance()->getTableName('posts');
        // phpcs:disable WordPress.DB.PreparedSQL
        $result = $wpdb->get_results(
            $wpdb->prepare(
                'SELECT q.*, IFNULL(rmlp.fid, %d) AS fid, wpm.meta_value AS attachedFile FROM ' .
                    $table_name .
                    ' q
            LEFT JOIN ' .
                    $table_name_rml .
                    ' rmlp
            ON rmlp.attachment = q.attachment
            INNER JOIN ' .
                    $wpdb->postmeta .
                    ' wpm
            ON wpm.post_id = q.attachment
            AND wpm.meta_key = \'_wp_attached_file\' LIMIT %d, %d',
                _wp_rml_root(),
                $skip,
                $top
            )
        );
        // phpcs:enable WordPress.DB.PreparedSQL
        $items = [];
        foreach ($result as $row) {
            $items[] = new \DevOwl\RealPhysicalMedia\queue\Row(
                $row->id,
                $row->processId,
                $row->attachment,
                $row->processLoaded,
                $row->processTotal,
                \strtotime($row->created),
                $row->log,
                $row->cleanup_path,
                $row->fid,
                \basename($row->attachedFile),
                $row->previousUrls
            );
        }
        return $items;
    }
    /**
     * Get a single item from the queue.
     *
     * @param int $id The attachment id
     */
    public function item($id) {
        global $wpdb;
        $table_name = $this->getTableName('queue');
        // phpcs:disable WordPress.DB.PreparedSQL
        return $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $table_name . ' WHERE attachment = %d', $id));
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Add an item to queue. Duplicate attachments are handled automatically. Avoid adding attachments in a loop
     * and implement your own query with INSERT INTO SELECT statement.
     *
     * @param int $attachment The attachment id
     */
    public function add($attachment) {
        global $wpdb;
        $instance = \DevOwl\RealPhysicalMedia\handler\Handler::getInstance()->getCurrentInstance();
        if ($instance === null) {
            return new \WP_Error('rpm_queue_add_no_handler', __('You have not activated a handler.', RPM_TD), [
                'status' => 500
            ]);
        }
        if (
            \DevOwl\RealPhysicalMedia\misc\UploadDir::getInstance()->getUnfilteredAttachedFile($attachment) === \false
        ) {
            return new \WP_Error(
                'rpm_queue_add_not_found',
                __('The attachment was not found or has no physical file.', RPM_TD),
                ['status' => 404]
            );
        }
        $table_name = $this->getTableName('queue');
        $processId = \md5(\uniqid());
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query(
            $wpdb->prepare(
                'INSERT INTO ' .
                    $table_name .
                    '(attachment, processLoaded, processTotal, processId) VALUES (%d, 0, %d, %s)
            ON DUPLICATE KEY UPDATE processLoaded=0, processTotal=VALUES(processTotal), created=CURRENT_TIMESTAMP, processId=VALUES(processId)',
                $attachment,
                $instance->getProcessTotal(),
                $processId
            )
        );
        // phpcs:enable WordPress.DB.PreparedSQL
        // Documented in Listener
        do_action('RPM/Queue/Added', $processId);
    }
    /**
     * Remove a file from the queue.
     *
     * @param int $attachment The attachment id
     */
    public function remove($attachment) {
        global $wpdb;
        return $wpdb->delete($this->getTableName('queue'), ['attachment' => $attachment], ['%d']);
    }
    /**
     * Add the potential files to the queue so they can be reinitialized.
     */
    public function addPotentialFiles() {
        global $wpdb;
        $instance = \DevOwl\RealPhysicalMedia\handler\Handler::getInstance()->getCurrentInstance();
        if ($instance === null) {
            return new \WP_Error(
                'rpm_queue_add_potential_no_handler',
                __('You have not activated a handler.', RPM_TD),
                ['status' => 500]
            );
        }
        $table_name = $this->getTableName('queue');
        $processId = \md5(\uniqid());
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query(
            $wpdb->prepare(
                'INSERT INTO ' .
                    $table_name .
                    ' (attachment, processLoaded, processTotal, processId)
            (SELECT p.ID AS attachment, 0 AS processLoaded, %d AS processTotal, %s AS processId
            FROM ' .
                    $wpdb->posts .
                    ' p
            INNER JOIN ' .
                    $wpdb->postmeta .
                    ' pm ON p.ID = pm.post_id
            WHERE post_type = "attachment"
            AND meta_key = "_wp_attached_file")
            ON DUPLICATE KEY UPDATE processLoaded=0, processTotal=VALUES(processTotal), created=CURRENT_TIMESTAMP, processId=VALUES(processId)',
                $instance->getProcessTotal(),
                $processId
            )
        );
        // phpcs:enable WordPress.DB.PreparedSQL
        // Documented in Listener
        do_action('RPM/Queue/Added', $processId);
    }
    /**
     * Delete attachment from the queue.
     *
     * @param int $postId
     */
    public function deleteAttachment($postId) {
        global $wpdb;
        return $wpdb->delete($this->getTableName('queue'), ['attachment' => $postId], ['%d']);
    }
    /**
     * Get the first item in queue.
     */
    public function getFirstItem() {
        $items = $this->get(0, 1);
        if (\count($items) > 0) {
            return $items[0];
        }
        return null;
    }
    /**
     * Get the count of potential files which can be added to the queue.
     */
    public function getPotentialFilesCount() {
        global $wpdb;
        // phpcs:disable WordPress.DB.PreparedSQL
        return (int) $wpdb->get_var(
            'SELECT COUNT(*) FROM ' .
                $wpdb->posts .
                ' p
            INNER JOIN ' .
                $wpdb->postmeta .
                ' pm ON p.ID = pm.post_id
            WHERE post_type = "attachment"
            AND meta_key = "_wp_attached_file"'
        );
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Check if the queue has automatic queueing activated.
     *
     * @param boolean $active Set the active status
     * @param boolean $forceBoolean
     */
    public function isAutomaticQueueing($active = null, $forceBoolean = \false) {
        $instance = \DevOwl\RealPhysicalMedia\handler\Handler::getInstance()->getCurrentInstance();
        if ($instance === null) {
            return $forceBoolean
                ? \false
                : new \WP_Error('rpm_queue_automatic_no_handler', __('You have not activated a handler.', RPM_TD), [
                    'status' => 500
                ]);
        }
        $option_name = RPM_OPT_PREFIX . '_automatic';
        if ($active !== null) {
            update_option($option_name, $active ? '1' : '0');
            \DevOwl\RealPhysicalMedia\view\AdminBar::getInstance()->isFirstTimeMoveHintDismissed(\true);
        }
        return get_option($option_name, '0') === '1';
    }
    /**
     * Checks if automatic queuing is enabled, a handler is active and children SQL is supported by database.
     */
    public function canAutomaticQueueing() {
        return $this->isAutomaticQueueing() &&
            \DevOwl\RealPhysicalMedia\handler\Handler::getInstance()->getCurrentInstance() !== null &&
            wp_rml_all_children_sql_supported();
    }
    /**
     * Start the initial process when a set of files got added to the queue.
     *
     * @param string $processId
     */
    public function initialProcess($processId) {
        /**
         * Allows to process files by process id directly after they are added to queue.
         *
         * @param {boolean} $process
         * @param {string} $processId
         * @hook RPM/Queue/Added/Process
         * @return {boolean}
         */
        $process = apply_filters('RPM/Queue/Added/Process', \false, $processId);
        if ($process) {
            $this->process(\DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->getMaxExecTime());
        }
    }
    /**
     * Get singleton instance.
     *
     * @return Queue
     */
    public static function getInstance() {
        return self::$me === null ? (self::$me = new \DevOwl\RealPhysicalMedia\queue\Queue()) : self::$me;
    }
}
