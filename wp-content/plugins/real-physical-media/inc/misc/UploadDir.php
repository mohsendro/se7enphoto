<?php

namespace DevOwl\RealPhysicalMedia\misc;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\configuration\ExcludeFolder;
use DevOwl\RealPhysicalMedia\configuration\Options;
use DevOwl\RealPhysicalMedia\Util;
use MatthiasWeb\RealMediaLibrary\api\IFolder;
use Exception;
use MatthiasWeb\RealMediaLibrary\attachment\Upload;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Singleton upload dir class.
 */
class UploadDir {
    use UtilsProvider;
    private static $me;
    private $folder = null;
    private $lastGeneratedPath = null;
    private $currentUserId = null;
    /**
     * This is called before the upload progress is started so the
     * real upload dir filter can be set.
     *
     * @param string $file
     */
    public function handle_pre_upload($file) {
        add_filter('upload_dir', [$this, 'upload_dir']);
        return $file;
    }
    /**
     * This is called just before the upload progress so the
     * real upload dir filter can be removed.
     *
     * @param array $fileInfo
     */
    public function handle_upload($fileInfo) {
        remove_filter('upload_dir', [$this, 'upload_dir']);
        return $fileInfo;
    }
    /**
     * This sets the sub dir for the given folder.
     *
     * @param array $path
     */
    public function upload_dir($path) {
        $folder = $this->getFolderFromRequest();
        if (is_rml_folder($folder)) {
            // Get path
            $prefix = \DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->getPrefix();
            $prefix = empty($prefix) ? '' : trailingslashit(_wp_rml_sanitize_filename(\trim($prefix, '/\\')));
            if ($folder->getType() === RML_TYPE_ROOT) {
                $folderPath = '';
            } else {
                $folderPath = $this->getFolderPath($folder);
            }
            /**
             * Allows to modify the complete folder path by string.
             *
             * @param {string} $folderPath
             * @param {IFolder} $folder
             * @hook RPM/Attachment/Folder/Path
             * @return {string}
             * @since 1.0.3
             */
            $folderPath = apply_filters('RPM/Attachment/Folder/Path', $folderPath, $folder);
            $destinationPathRelativeUploadDir = trailingslashit($prefix . $folderPath);
            $subDir = \trim($destinationPathRelativeUploadDir, '/\\');
            $this->debug('Use ' . $subDir . ' as subdir in upload process...', __METHOD__);
            // Modify pathes
            $path['subdir'] = '/' . $subDir;
            $path['path'] = trailingslashit($path['path']) . $subDir;
            $path['url'] = trailingslashit($path['url']) . $subDir;
            $this->lastGeneratedPath =
                $destinationPathRelativeUploadDir === '/' ? '' : $destinationPathRelativeUploadDir;
        }
        return $path;
    }
    /**
     * Get absolute path of a folder prepared for physical filesystems.
     *
     * @param IFolder $folder
     */
    public function getFolderPath($folder) {
        $callable = [$this, 'removeAllMimeTypes'];
        $result = \DevOwl\RealPhysicalMedia\Util::withoutFilters('mime_types', function () use ($folder, $callable) {
            add_filter('mime_types', $callable, \PHP_INT_MAX, 0);
            add_filter('upload_mimes', $callable, \PHP_INT_MAX, 0);
            return \trim($folder->getPath('/', [$this, 'getFolderPathMap'], [$this, 'getFolderPathFilter']), '/\\');
        });
        return $result;
    }
    /**
     * Remove all known Core (!) mime types to avoid "unnamed-file.txt" folder names.
     *
     * @see https://app.clickup.com/t/50nr7g
     */
    public function removeAllMimeTypes() {
        return [];
    }
    // See getFolderPath
    public function getFolderPathMap($name, $folder) {
        /**
         * Allows to modify a path part by string.
         *
         * @param {string} $folderPart
         * @param {IFolder} $folder
         * @hook RPM/Attachment/Folder/PathPart
         * @return {string}
         * @since 1.0.3
         */
        return apply_filters('RPM/Attachment/Folder/PathPart', _wp_rml_sanitize_filename($name), $folder);
    }
    // See getFolderPathFilter
    public function getFolderPathFilter($folder) {
        return !\DevOwl\RealPhysicalMedia\configuration\ExcludeFolder::isExcluded($folder);
    }
    /**
     * This is called before the upload progress is started so the
     * real upload dir filter can be set.
     *
     * @param IFolder $arg
     */
    public function initialize($arg) {
        add_filter('upload_dir', [$this, 'upload_dir']);
        if (is_rml_folder($arg)) {
            $this->setFolder($arg);
        }
        return $arg;
    }
    /**
     * This is called just before the upload progress so the
     * real upload dir filter can be removed.
     *
     * @param array $fileInfo
     */
    public function reset($fileInfo = null) {
        remove_filter('upload_dir', [$this, 'upload_dir']);
        $this->setFolder(null);
        return $fileInfo;
    }
    /**
     * Get pathes for a given attachment.
     *
     * @param int $attachment
     * @param IFolder $_folder
     * @return array|boolean Returns an array of pathes when the attachment has a real file otherwise false1
     */
    public function pathes($attachment, $_folder) {
        // Get current path
        $attachedFile = $this->getUnfilteredAttachedFile($attachment);
        if ($attachedFile !== \false) {
            // Set the current user
            $owner = get_post_field('post_author', $attachment);
            if (empty($owner)) {
                return \false;
            }
            $this->setCurrentUser((int) $owner);
            try {
                /**
                 * Get the folder to use for this attachment when calculating the folder path.
                 *
                 * @param {IFolder} $folder
                 * @param {IFolder} $folderOriginal
                 * @param {int} $attachment
                 * @hook RPM/Attachment/Folder
                 * @return {IFolder}
                 */
                $folder = apply_filters('RPM/Attachment/Folder', $_folder, $_folder, $attachment);
                // Create source pathes
                $result = [];
                $result['sourceAbsPath'] = \dirname($attachedFile);
                $result['sourcePath'] = trailingslashit(\substr($result['sourceAbsPath'], \strlen(ABSPATH)));
                // Generate new path
                $upload_dir = $this->path($folder);
                $result['destinationPathRelativeUploadDir'] = $this->getLastGeneratedPath();
                // Generate relative absolute path
                $destinationPath =
                    trailingslashit($upload_dir['basedir']) . $result['destinationPathRelativeUploadDir'];
                $destinationPath = \substr($destinationPath, \strlen(ABSPATH));
                $result['destinationPath'] = $destinationPath;
                $result['identical'] = $destinationPath === $result['sourcePath'];
                return $result;
            } catch (\Exception $e) {
                $result = \false;
            }
            $this->restoreCurrentUser();
            return $result;
        } else {
            // File perhaps deleted?
            return \false;
        }
    }
    /**
     * Get the path of a RML folder.
     *
     * @param IFolder $folder The folder object
     * @return array|boolean The result of upload_dir()
     */
    public function path($folder) {
        // Set owner
        $this->setCurrentUser($folder->getOwner());
        try {
            $this->initialize($folder);
            $result = wp_upload_dir();
            $this->reset();
        } catch (\Exception $e) {
            $result = \false;
        }
        $this->restoreCurrentUser();
        return $result;
    }
    /**
     * Get the unfiltered attached file path of an attachment directly from database.
     *
     * @param int $attachment
     */
    public function getUnfilteredAttachedFile($attachment) {
        global $wpdb;
        $file = $wpdb->get_var(
            $wpdb->prepare(
                'SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "_wp_attached_file" AND post_id = %d',
                $attachment
            )
        );
        if (empty($file)) {
            return \false;
        }
        // If the file is relative, prepend upload dir. (see https://developer.wordpress.org/reference/functions/get_attached_file/ source code)
        if (
            $file &&
            0 !== \strpos($file, '/') &&
            !\preg_match('|^.:\\\\|', $file) &&
            (($uploads = wp_get_upload_dir()) && \false === $uploads['error'])
        ) {
            $file = $uploads['basedir'] . "/{$file}";
        }
        return empty($file) ? \false : $file;
    }
    /**
     * Get the folder object from the upload request.
     */
    private function getFolderFromRequest() {
        if (isset($this->folder)) {
            return $this->folder;
        } elseif (\method_exists(\MatthiasWeb\RealMediaLibrary\attachment\Upload::class, 'getFolderFromRequest')) {
            $folder = \MatthiasWeb\RealMediaLibrary\attachment\Upload::getInstance()->getFolderFromRequest();
            return wp_rml_get_object_by_id($folder === null ? _wp_rml_root() : $folder);
        }
    }
    // getter
    public function getLastGeneratedPath() {
        return $this->lastGeneratedPath;
    }
    // setter
    public function setFolder($folder) {
        $this->folder = $folder;
    }
    /**
     * Set the current user for the upload path.
     *
     * @param int $id
     */
    public function setCurrentUser($id) {
        global $current_user;
        $this->currentUserId = null;
        $user = get_userdata($id);
        if ($user !== \false && isset($current_user)) {
            $this->currentUserId = $current_user->ID;
            wp_set_current_user($id);
            return \true;
        }
        return \false;
    }
    /**
     * Restore the current user to the original.
     */
    public function restoreCurrentUser() {
        if ($this->currentUserId !== null) {
            $this->setCurrentUser($this->currentUserId);
        }
        $this->currentUserId = null;
    }
    /**
     * Get singleton class.
     *
     * @return UploadDir
     */
    public static function getInstance() {
        return !isset(self::$me) ? (self::$me = new \DevOwl\RealPhysicalMedia\misc\UploadDir()) : self::$me;
    }
}
