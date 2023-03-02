<?php

namespace DevOwl\RealPhysicalMedia\listener;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\configuration\Options;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * This singleton class listens to changes to folders and the folder structure.
 */
class FolderListener {
    use UtilsProvider;
    private static $me;
    /**
     * A new folder is created. Check the options and create a physical folder.
     *
     * @param int $parent
     * @param string $name
     * @param int $type
     * @param int $id
     */
    public function created($parent, $name, $type, $id) {
        if (\DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->isCleanupCreateEnabled()) {
            $folder = wp_rml_get_object_by_id($id);
            \DevOwl\RealPhysicalMedia\listener\Lockfile::getInstance()->createForObject($folder);
        }
    }
    /**
     * A folder is deleted. Check the options and delete the physical folder.
     *
     * @param IFolder $folder
     */
    public function preDeletion($folder) {
        if (\DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->isCleanupDeleteEnabled()) {
            \DevOwl\RealPhysicalMedia\listener\Lockfile::getInstance()->removeForObject($folder);
        }
    }
    /**
     * Get singleton class.
     *
     * @return FolderListener
     */
    public static function getInstance() {
        return !isset(self::$me) ? (self::$me = new \DevOwl\RealPhysicalMedia\listener\FolderListener()) : self::$me;
    }
}
