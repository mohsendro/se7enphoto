<?php

namespace DevOwl\RealPhysicalMedia\listener;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\misc\UploadDir;
use DevOwl\RealPhysicalMedia\Util;
use WP_Filesystem_Direct;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Handle lock file generation.
 */
class Lockfile {
    use UtilsProvider;
    /**
     * Singleton instance.
     */
    private static $me;
    private $fs;
    const NAME = '.rpmcreated';
    const CONTAINS_OTHER_FILES = 'others';
    const PROCESS_ID_DEL = ',';
    /**
     * C'tor.
     */
    public function __construct() {
        // Require filesystem class in MU installation
        require_once path_join(ABSPATH, 'wp-admin/includes/class-wp-filesystem-base.php');
        require_once path_join(ABSPATH, 'wp-admin/includes/class-wp-filesystem-direct.php');
        $this->fs = new \WP_Filesystem_Direct([]);
    }
    /**
     * Create the lockfile in a given path.
     *
     * @param string $path
     */
    public function create($path) {
        wp_mkdir_p($path);
        $file = path_join($path, self::NAME);
        return $this->fs->touch($file);
    }
    /**
     * Create a lockfile for a folder.
     *
     * @param IFolder $folder The folder object
     */
    public function createForObject($folder) {
        return $this->create($this->rml2path($folder));
    }
    /**
     * Create lockfiles for all virtual RML folders.
     *
     * @param boolean $lockfile If false the folders are created without lockfile
     */
    public function createAll($lockfile = \true) {
        foreach (wp_rml_objects() as $obj) {
            $path = $this->rml2path($obj);
            if ($lockfile) {
                $this->create($path);
            } else {
                wp_mkdir_p($path);
            }
        }
    }
    /**
     * Remove the lockfile in a given path. The file only gets deleted when it is the
     * only file in the folder.
     *
     * @param string $path The absolute path
     * @param boolean $empty If true the path gets recursively deleted if empty
     * @return boolean|'others'
     */
    public function remove($path, $empty = \true) {
        if (!$this->containsOnlyLockfile($path)) {
            return self::CONTAINS_OTHER_FILES;
        }
        $file = path_join($path, self::NAME);
        $delete = $this->fs->delete($file, \false, 'f');
        if ($empty) {
            \DevOwl\RealPhysicalMedia\Util::removeEmptyDirs($path);
        }
        return $delete;
    }
    /**
     * Remove the lockfile in a given folder.
     *
     * @param string $folder The folder object
     * @param boolean $empty If true the path gets recursively deleted if empty
     * @returns boolean
     */
    public function removeForObject($folder, $empty = \true) {
        return $this->remove($this->rml2path($folder), $empty);
    }
    /**
     * Remove all available lockfiles in your uploads folder.
     */
    public function removeAll() {
        foreach ($this->getAll() as $file) {
            $this->fs->delete($file, \false, 'f');
        }
    }
    /**
     * Append a process id to the lockfile content. This method does
     * not ensure if the file exists, it simply writes and can throw an
     * exception if the file does not exist.
     *
     * @param string $path The absolute path
     * @param string $processId The process id
     */
    public function appendProcessId($path, $processId) {
        $file = path_join($path, self::NAME);
        $ids = $this->getProcessIds($path);
        $ids[] = $processId;
        return $this->put_contents($file, \implode(self::PROCESS_ID_DEL, $ids));
    }
    /**
     * Clear the lockfile content. This method does
     * not ensure if the file exists, it simply writes and can throw an
     * exception if the file does not exist.
     *
     * @param string $path The absolute path
     */
    public function clear($path) {
        return $this->put_contents(path_join($path, self::NAME), '');
    }
    /**
     * Put content to a file.
     *
     * @param string $file
     * @param string $content
     */
    private function put_contents($file, $content) {
        $chmod_dir = 0755 & ~\umask();
        if (\defined('FS_CHMOD_FILE')) {
            $chmod_dir = \constant('FS_CHMOD_FILE');
        }
        $this->fs->put_contents($file, $content, $chmod_dir);
    }
    /**
     * Checks if a given folder has only the lock file instead of further files.
     *
     * @param string $path The absolute path to the folder
     */
    public function containsOnlyLockfile($path) {
        // Has generelly the lock file?
        if (!$this->isLocked($path)) {
            return \false;
        }
        $handle = \opendir($path);
        $i = 0;
        while (($fileItem = \readdir($handle)) !== \false) {
            // skip '.' and '..'
            if ($fileItem === '.' || $fileItem === '..') {
                continue;
            }
            if (\is_file(path_join($path, $fileItem))) {
                $i++;
                if ($i > 1) {
                    break;
                }
            }
        }
        \closedir($handle);
        return $i === 1;
    }
    /**
     * Get the absolute path for a given RML folder.
     *
     * @param IFolder $folder The folder
     * @returns string
     */
    public function rml2path($folder) {
        $path = \DevOwl\RealPhysicalMedia\misc\UploadDir::getInstance()->path($folder);
        return $path['path'];
    }
    /**
     * Checks if a given folder has the lock file.
     *
     * @param string $path The absolute path to the folder
     * @returns boolean
     */
    public function isLocked($path) {
        return $this->fs->is_file(path_join($path, self::NAME));
    }
    /**
     * Check if a RML folder is locked.
     *
     * @param IFolder $folder The folder object
     * @returns boolean
     */
    public function isLockedObject($folder) {
        return $this->isLocked($this->rml2path($folder));
    }
    /**
     * Get all lockfile pathes.
     *
     * @returns string[]
     */
    public function getAll() {
        $path = wp_upload_dir();
        $path = $path['path'];
        return \DevOwl\RealPhysicalMedia\Util::rglob($path, self::NAME);
    }
    /**
     * Get the process ids of the lockfile content. This method does
     * not ensure if the file exists, it simply reads and can throw an
     * exception if the file does not exist.
     *
     * @param string $path The absolute path
     * @returns string[]
     */
    public function getProcessIds($path) {
        $content = \trim(\trim($this->fs->get_contents(path_join($path, self::NAME)), self::PROCESS_ID_DEL));
        if (empty($content)) {
            return [];
        }
        return \explode(self::PROCESS_ID_DEL, $content);
    }
    /**
     * Get singleton listener class.
     *
     * @returns Lockfile
     */
    public static function getInstance() {
        return !isset(self::$me) ? (self::$me = new \DevOwl\RealPhysicalMedia\listener\Lockfile()) : self::$me;
    }
}
