<?php

namespace DevOwl\RealPhysicalMedia\queue;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\handler\Handler;
use DevOwl\RealPhysicalMedia\misc\Seo;
use DevOwl\RealPhysicalMedia\misc\UploadDir;
use DevOwl\RealPhysicalMedia\Util;
use ErrorException;
use Exception;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Queue item model.
 */
class Row {
    use UtilsProvider;
    public $id;
    public $processId;
    public $attachment;
    public $loaded;
    public $total;
    public $created;
    public $logId;
    public $cleanupPath;
    public $currentFolderId;
    public $filename;
    public $sourceAbsPath;
    public $sourcePath;
    public $destinationPath;
    public $destinationPathRelativeUploadDir;
    private $destroyed = \false;
    private $previousUrls;
    /**
     * C'tor.
     *
     * @param int $id
     * @param string $processId
     * @param int $attachment
     * @param int $loaded
     * @param int $total
     * @param int $created
     * @param int $logId
     * @param string $cleanupPath
     * @param int $currentFolderId
     * @param string $filename
     * @param string $previousUrls
     */
    public function __construct(
        $id,
        $processId,
        $attachment,
        $loaded,
        $total,
        $created,
        $logId,
        $cleanupPath,
        $currentFolderId,
        $filename,
        $previousUrls
    ) {
        $this->id = (int) $id;
        $this->processId = $processId;
        $this->attachment = (int) $attachment;
        $this->loaded = (int) $loaded;
        $this->total = (int) $total;
        $this->created = (int) $created;
        $this->logId = $logId === null ? null : (int) $logId;
        $this->cleanupPath = $cleanupPath;
        $this->currentFolderId = (int) $currentFolderId;
        $this->filename = $filename;
        $this->previousUrls = $previousUrls;
        // Get current path
        $pathes = \DevOwl\RealPhysicalMedia\misc\UploadDir::getInstance()->pathes($attachment, $this->getFolder());
        if ($pathes !== \false) {
            $this->sourceAbsPath = $pathes['sourceAbsPath'];
            $this->sourcePath = $pathes['sourcePath'];
            $this->destinationPath = $pathes['destinationPath'];
            $this->destinationPathRelativeUploadDir = $pathes['destinationPathRelativeUploadDir'];
        } else {
            // File perhaps deleted?
            $this->remove();
        }
    }
    /**
     * Process this single item and when done remove from queue and create log.
     *
     * @throws Exception
     */
    public function process() {
        global $wpdb;
        $instance = \DevOwl\RealPhysicalMedia\handler\Handler::getInstance()->getCurrentInstance();
        if ($instance === null) {
            return \false;
        }
        // Check if already done
        if ($this->isDone()) {
            $this->remove();
            return \true;
        }
        // Skip this one with a filter
        try {
            /**
             * Allows to skip an attachment in the queue - it will not be moved. Simply
             * add_action and throw an exception to skip it.
             *
             * @param {int} $attachment Attachment id
             * @throws Exception
             * @hook RPM/Queue/Skip
             */
            do_action('RPM/Queue/Skip', $this->attachment);
        } catch (\Exception $e) {
            $this->remove();
            return \true;
        }
        // Process the rename
        $start = \microtime(\true) * 1000;
        // Save previous urls to database
        if ($this->logId === null && $this->loaded === 0) {
            // Use base64 encoding to avoid url replace of S&R plugins
            $this->previousUrls = \base64_encode(\json_encode($this->fetchUrls(\true)));
            $wpdb->update($this->getTableName('queue'), ['previousUrls' => $this->previousUrls], ['id' => $this->id]);
        }
        // Process with given error handler
        try {
            \set_error_handler([$this, 'set_error_handler']);
            $instance->process($this);
            \restore_error_handler();
        } catch (\Exception $e) {
            \restore_error_handler();
            throw $e;
        }
        $done = $this->isDone();
        // Log and remove
        clean_post_cache($this->attachment);
        // Clean cache so the URLs get updated properly
        $newUrls = $this->fetchUrls();
        if ($done) {
            $instance->finish($this);
            $this->remove($newUrls);
        }
        // Get the duration in milliseconds
        $duration = \microtime(\true) * 1000 - $start;
        $ms = $this->logger($duration);
        // Prepare the response with further details
        $newUrls['f'] = $this->filename;
        $newUrls['d'] = $this->destinationPath;
        $newUrls['ms'] = $ms;
        return $newUrls;
    }
    /**
     * Set error handler.
     *
     * @param string $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param string $errcontext
     * @see https://stackoverflow.com/a/1241751/5506547
     * @throws ErrorException
     */
    public function set_error_handler($errno, $errstr, $errfile, $errline, $errcontext) {
        // error was suppressed with the @-operator
        if (0 === \error_reporting()) {
            return \false;
        }
        if (!\DevOwl\RealPhysicalMedia\handler\Handler::getInstance()->getCurrentInstance()) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
    }
    /**
     * Fetch result for REST response.
     *
     * @param boolean $commonPrefix If true the result array is compressed to the common prefix
     */
    public function fetchUrls($commonPrefix = \false) {
        $image_metadata = wp_get_attachment_metadata($this->attachment);
        $url = wp_get_attachment_url($this->attachment);
        $result = [
            'i' => \false,
            // Defines if image
            'fid' => $this->currentFolderId,
            // The current folder id
            'u' => [
                // All urls including full and image sizes
                'full' => $url
            ]
        ];
        if ($image_metadata !== \false) {
            $result['i'] = \true;
            if (isset($image_metadata['sizes'])) {
                foreach ($image_metadata['sizes'] as $key => $size) {
                    $sizeSrc = wp_get_attachment_image_src($this->attachment, $key);
                    if ($sizeSrc !== \false) {
                        $result['u'][$key] = $sizeSrc[0];
                    }
                }
            }
        }
        // Find common prefix
        if ($commonPrefix) {
            $prefix = \DevOwl\RealPhysicalMedia\Util::commonPrefix(\array_values($result['u']));
            $prefln = \strlen($prefix);
            foreach ($result['u'] as $key => $u) {
                $result['u'][$key] = \substr($u, $prefln);
            }
            $result['p'] = $prefix;
        }
        return $result;
    }
    /**
     * Update the processLoaded in database.
     *
     * @param int $to The processLoaded value otherwise +1
     */
    public function progress($to = null) {
        global $wpdb;
        if ($to === null) {
            $this->loaded++;
        } else {
            $this->loaded = \intval($to);
        }
        $wpdb->update(
            $this->getTableName('queue'),
            ['processLoaded' => $this->loaded],
            ['id' => $this->id],
            '%d',
            '%d'
        );
    }
    /**
     * Add queue item to logger.
     *
     * @param int $duration The duration in ms
     * @returns int The complete duration in ms
     */
    private function logger($duration) {
        global $wpdb;
        // If there is already a log increase the duration, only
        $table_name = $this->getTableName('log');
        $doneInt = $this->isDone() ? 1 : 0;
        if ($this->logId === null) {
            $wpdb->insert(
                $table_name,
                [
                    'attachment' => $this->attachment,
                    'duration' => $duration,
                    'done' => $doneInt,
                    'fromPath' => $this->sourcePath,
                    'toPath' => $this->destinationPath
                ],
                ['%d', '%d', '%d', '%s', '%s']
            );
            // Save to the queue
            $this->logId = $wpdb->insert_id;
            $wpdb->update($this->getTableName('queue'), ['log' => $this->logId], ['id' => $this->id], '%d', '%d');
            \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->clearLog($this->logId);
            return $duration;
        } else {
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->query(
                'UPDATE ' .
                    $table_name .
                    '
                SET duration = (duration + ' .
                    \intval($duration) .
                    '),
                    done = ' .
                    $doneInt .
                    '
                WHERE id = ' .
                    $this->logId
            );
            return (int) $wpdb->get_var(
                $wpdb->prepare('SELECT duration FROM ' . $table_name . ' WHERE id = %d', $this->logId)
            );
            // phpcs:enable WordPress.DB.PreparedSQL
        }
    }
    /**
     * Remove the row from queue.
     *
     * @param array $newUrls Save data to the seo database table
     * @returns int Result of $wpdb->delete()
     */
    private function remove($newUrls = \false) {
        global $wpdb;
        $previousUrls = $this->getPreviousUrls();
        \DevOwl\RealPhysicalMedia\misc\Seo::getInstance()->persistUrls($this->attachment, $previousUrls, $newUrls);
        $this->destroyed = \true;
        return $wpdb->delete($this->getTableName('queue'), ['id' => $this->id]);
    }
    /**
     * Checks if the row is done.
     *
     * @returns boolean
     */
    public function isDone() {
        return $this->loaded >= $this->total ||
            $this->sourcePath === $this->destinationPath ||
            $this->destroyed ||
            empty($this->filename);
        // No physical file in database
    }
    /**
     * Checks if the file exists.
     *
     * @returns boolean
     */
    public function exists() {
        return \file_exists(path_join($this->sourceAbsPath, $this->filename));
    }
    /**
     * Get the RML folder object for this row.
     *
     * @returns \MatthiasWeb\RealMediaLibrary\api\IFolder
     */
    public function getFolder() {
        return wp_rml_get_object_by_id($this->currentFolderId);
    }
    /**
     * Get previous urls of this row.
     *
     * @param boolean $parse
     */
    public function getPreviousUrls($parse = \true) {
        $p = $this->previousUrls;
        if ($parse && !empty($p)) {
            $p = \json_decode(\base64_decode($p), ARRAY_A);
            $prefix = $p['p'];
            unset($p['p']);
            foreach ($p['u'] as $key => $url) {
                $p['u'][$key] = $prefix . $url;
            }
        }
        return $p;
    }
}
