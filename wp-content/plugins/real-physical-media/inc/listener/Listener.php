<?php

namespace DevOwl\RealPhysicalMedia\listener;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\configuration\Options;
use DevOwl\RealPhysicalMedia\handler\Handler;
use DevOwl\RealPhysicalMedia\misc\UploadDir;
use DevOwl\RealPhysicalMedia\queue\Queue;
use DevOwl\RealPhysicalMedia\Util;
use MatthiasWeb\RealMediaLibrary\api\IFolder;
use MatthiasWeb\RealMediaLibrary\Core;
use MatthiasWeb\RealMediaLibrary\rest\Attachment;
use MatthiasWeb\RealMediaLibrary\rest\Service;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * This singleton class listens to changes for physical movements and adds attachments
 * to the queue.
 */
class Listener {
    use UtilsProvider;
    /**
     * Singleton instance.
     */
    private static $me;
    private $lastCleanupPath;
    const PROCESS_ID_PLACEHOLDER = '{{processId}}';
    /**
     * Files are moved. Now check for non-shortcuts and add to queue.
     *
     * @param int|string $fid
     * @param int[] $attachments
     * @param IFolder $folder
     * @param boolean $isShortcut
     * @param int[] $sourceFolders
     */
    public function item_move_finished($fid, $attachments, $folder, $isShortcut, $sourceFolders) {
        if (!\DevOwl\RealPhysicalMedia\Util::endsWith($_SERVER['REQUEST_URI'], 'async-upload.php')) {
            global $wpdb;
            $processId = $this->addToQueueFromSubquery(
                'SELECT p.ID AS attachment, 0 AS processLoaded, %d AS processTotal, NULL AS cleanup_path, ' .
                    self::PROCESS_ID_PLACEHOLDER .
                    ' AS processId
                FROM ' .
                    $wpdb->posts .
                    ' p
                INNER JOIN ' .
                    $wpdb->postmeta .
                    ' pm ON p.ID = pm.post_id
                WHERE post_type = "attachment"
                AND p.ID IN (' .
                    \implode(',', $attachments) .
                    ')'
            );
            if ($processId !== \false) {
                $lockfile = \DevOwl\RealPhysicalMedia\listener\Lockfile::getInstance();
                foreach ($sourceFolders as $sid) {
                    $sobj = wp_rml_get_object_by_id($sid);
                    if ($sobj !== null) {
                        $deleted = \false;
                        // Delete the virtual folder when empty
                        if (
                            \DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->isCleanupVirtualMoveEnabled() &&
                            $sobj->getCnt(\true) <= 0 &&
                            ($deleted = wp_rml_delete($sid)) === \true
                        ) {
                            \MatthiasWeb\RealMediaLibrary\rest\Service::addResponseModifier(
                                \MatthiasWeb\RealMediaLibrary\rest\Attachment::MODIFIER_TYPE_BULK_MOVE,
                                ['removedFolderIds' => [$sid]]
                            );
                        }
                        // Clear lockfile process ids
                        if (!$deleted) {
                            $path = $lockfile->rml2path($sobj);
                            if ($lockfile->isLocked($path)) {
                                $lockfile->clear($path);
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * Folder gets deleted so add all the content to the queue.
     *
     * @param IFolder $folder
     */
    public function folder_delete($folder) {
        global $wpdb;
        $table_name_rml = \MatthiasWeb\RealMediaLibrary\Core::getInstance()->getTableName('posts');
        $processId = $this->addToQueueFromSubquery(
            'SELECT p.attachment, 0 AS processLoaded, %d AS processTotal, NULL AS cleanup_path, ' .
                self::PROCESS_ID_PLACEHOLDER .
                ' AS processId
            FROM ' .
                $table_name_rml .
                ' p
            INNER JOIN ' .
                $wpdb->postmeta .
                ' pm ON p.attachment = pm.post_id
            AND p.fid = ' .
                $folder->getId()
        );
        // Folders gets moved to unorganized, so add the process id to the lockfile as of rename/moved
        if (
            $processId !== \false &&
            \DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->isCleanupDeleteEnabled()
        ) {
            $lockfile = \DevOwl\RealPhysicalMedia\listener\Lockfile::getInstance();
            $toRemove = $lockfile->rml2path($folder);
            $removed = $lockfile->remove($toRemove);
            // Check if removed folder contains other files
            if ($removed === \DevOwl\RealPhysicalMedia\listener\Lockfile::CONTAINS_OTHER_FILES) {
                // Save the process id to the folder
                $lockfile->appendProcessId($toRemove, $processId);
            }
        }
    }
    /**
     * Before a folder gets renamed save the clean up path.
     *
     * @param string $name
     * @param IFolder $folder
     */
    public function folder_rename($name, $folder) {
        $this->lastCleanupPath = $this->getCleanupPath($folder);
    }
    /**
     * A folder is renamed so read all the child folders content (include self) and
     * add them to the queue.
     *
     * @param string $name
     * @param IFolder $folder
     */
    public function folder_renamed($name, $folder) {
        if ($folder->getId() > 0) {
            // only execute when folder is already persisted
            $processId = $this->addToQueueRecursively($folder->getId());
            if (\DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->isCleanupCreateEnabled()) {
                $toRemove = path_join(ABSPATH, $this->lastCleanupPath);
                $lockfile = \DevOwl\RealPhysicalMedia\listener\Lockfile::getInstance();
                $removed = $lockfile->remove($toRemove);
                // Check if removed folder contains other files
                if (
                    $removed === \DevOwl\RealPhysicalMedia\listener\Lockfile::CONTAINS_OTHER_FILES &&
                    $processId !== \false
                ) {
                    // Save the process id to the folder
                    $lockfile->appendProcessId($toRemove, $processId);
                }
                $lockfile->createForObject($folder);
            }
        }
    }
    /**
     * Before a folder gets moved read the cleanup path.
     *
     * @param IFolder $folder
     */
    public function folder_move($folder) {
        $this->lastCleanupPath = $this->getCleanupPath($folder);
    }
    /**
     * After a folder gets moved read all the child folders content (include self) and
     * add them to the queue.
     *
     * @param IFolder $folder
     */
    public function folder_moved($folder) {
        $this->folder_renamed(null, $folder);
    }
    /**
     * Reset / Full reset and add all files to the queue.
     */
    public function wipe() {
        \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->addPotentialFiles();
    }
    /**
     * Add original files instead of shortcuts.
     *
     * @param string $processId The process ID to use
     */
    private function clearShortcuts($processId) {
        if ($processId !== \false) {
            global $wpdb;
            $table_name_queue = $this->getTableName('queue');
            $table_name_rml = \MatthiasWeb\RealMediaLibrary\Core::getInstance()->getTableName('posts');
            // Add original files
            $this->addToQueueFromSubquery(
                'SELECT rmlp.isShortcut AS attachment, 0 AS processLoaded, %d AS processTotal, NULL AS cleanup_path, ' .
                    self::PROCESS_ID_PLACEHOLDER .
                    ' AS processId
                FROM ' .
                    $table_name_queue .
                    ' q
                INNER JOIN ' .
                    $table_name_rml .
                    ' rmlp ON rmlp.attachment = q.attachment AND rmlp.isShortcut > 0',
                $processId
            );
            // Remove shortcuts
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->query(
                'DELETE q.* FROM ' .
                    $table_name_queue .
                    ' q
                LEFT JOIN ' .
                    $wpdb->postmeta .
                    ' pm ON pm.post_id = q.attachment AND pm.meta_key = "_wp_attached_file"
                WHERE pm.meta_value IS NULL OR pm.meta_value = ""'
            );
            // phpcs:enable WordPress.DB.PreparedSQL
        }
    }
    /**
     * Add a folder recursively with all attachments to the queue.
     *
     * @param int $id The folder id
     * @return boolean|string|false when failed and the processId when successful
     */
    public function addToQueueRecursively($id) {
        global $wpdb;
        $table_name_rml = \MatthiasWeb\RealMediaLibrary\Core::getInstance()->getTableName('posts');
        $sql_child_ids = wp_rml_create_all_children_sql($id, \true, [
            'fields' =>
                'p.attachment, 0 AS processloaded, %d AS processtotal, ' .
                $wpdb->prepare('%s', $this->lastCleanupPath) .
                ' AS cleanup_path, ' .
                self::PROCESS_ID_PLACEHOLDER .
                ' AS processId',
            'join' =>
                'INNER JOIN ' .
                $table_name_rml .
                ' p ON rmldata.id = p.fid
                LEFT JOIN ' .
                $wpdb->postmeta .
                ' pm ON p.attachment = pm.post_id',
            'afterWhere' => ' AND (meta_key = \'_wp_attached_file\' OR isShortcut > 0)'
        ]);
        return $this->addToQueueFromSubquery($sql_child_ids);
    }
    /**
     * Add to queue from sub query.
     *
     * @param string $sql The SQL with a self::PROCESS_ID_PLACEHOLDER placeholder and one %d for processTotal value
     * @param string $useProcessId
     * @return boolean|string|false when failed and the processId when successful
     */
    private function addToQueueFromSubquery($sql, $useProcessId = null) {
        $processTotal = $this->getProcessTotal();
        if ($processTotal === \false) {
            return \false;
        }
        global $wpdb;
        $table_name = $this->getTableName('queue');
        $processId = $useProcessId !== null ? $useProcessId : \md5(\uniqid());
        $sql = \str_replace(self::PROCESS_ID_PLACEHOLDER, $wpdb->prepare('%s', $processId), $sql);
        // phpcs:disable WordPress.DB.PreparedSQL
        // phpcs:disable WordPress.DB.PreparedSQLPlaceholders
        $sql = $wpdb->prepare(
            'INSERT INTO ' .
                $table_name .
                '(attachment, processLoaded, processTotal, cleanup_path, processId)
            (' .
                $sql .
                ')
            ON DUPLICATE KEY UPDATE processLoaded=0, processTotal=VALUES(processTotal), created=CURRENT_TIMESTAMP, processId=VALUES(processId)',
            $processTotal
        );
        $wpdb->query($sql);
        // phpcs:enable WordPress.DB.PreparedSQL
        // phpcs:enable WordPress.DB.PreparedSQLPlaceholders
        if ($useProcessId === null) {
            $this->clearShortcuts($processId);
            /**
             * Added an attachment to the queue with a process id.
             *
             * @param {string} $processId An unique process id for an attachment
             * @hook RPM/Queue/Added
             */
            do_action('RPM/Queue/Added', $processId);
        }
        return $processId;
    }
    /**
     * Get the cleanup path for a given folder.
     *
     * @param IFolder $folder
     * @returns string
     */
    public function getCleanupPath($folder) {
        if (is_rml_folder($folder)) {
            $path = \DevOwl\RealPhysicalMedia\misc\UploadDir::getInstance()->path($folder);
            $path = \substr($path['path'], \strlen(ABSPATH));
            return $path;
        }
    }
    /**
     * Checks if automatic queueing is allowed and returns the processTotal value.
     */
    public function getProcessTotal() {
        if (!\DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->canAutomaticQueueing()) {
            return \false;
        }
        return \DevOwl\RealPhysicalMedia\handler\Handler::getInstance()
            ->getCurrentInstance()
            ->getProcessTotal();
    }
    /**
     * Get singleton instance.
     *
     * @return Listener
     */
    public static function getInstance() {
        return !isset(self::$me) ? (self::$me = new \DevOwl\RealPhysicalMedia\listener\Listener()) : self::$me;
    }
}
