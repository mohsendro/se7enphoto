<?php

namespace DevOwl\RealPhysicalMedia\configuration;

use DevOwl\RealPhysicalMedia\Assets;
use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\listener\Lockfile as ListenerLockfile;
use MatthiasWeb\RealMediaLibrary\api\IFolder;
use MatthiasWeb\RealMediaLibrary\api\IMetadata;
use WP_REST_Request;
use WP_REST_Response;
\defined('ABSPATH') or die('No script kiddies please!');
/**
 * Allow to create or delete the lockfile for a folder.
 */
class Lockfile implements \MatthiasWeb\RealMediaLibrary\api\IMetadata {
    use UtilsProvider;
    const UNIQUE_NAME = 'rpmLockfile';
    const FIELD_NAME = 'lockfile';
    /**
     * Enqueue scripts.
     *
     * @param Assets $assets
     */
    public function scripts($assets) {
        // Silence is golden.
    }
    /**
     * Output options.
     *
     * @param string $content
     * @param IFolder $folder
     */
    public function content($content, $folder) {
        $link = admin_url('options-media.php#rml-rpm_head_directories');
        $content .=
            '<label><input name="' .
            self::FIELD_NAME .
            '" type="checkbox" value="1" ' .
            checked(1, self::isLocked($folder), \false) .
            ' /> ' .
            __('Lockfile ', RPM_TD) .
            '(<code>' .
            \DevOwl\RealPhysicalMedia\listener\Lockfile::NAME .
            '</code>)</label>
            <p class="description"><a href="' .
            $link .
            '" target="_blank">' .
            __('Learn more about lockfiles', RPM_TD) .
            '</a></p>';
        return $content;
    }
    /**
     * Save options.
     *
     * @param WP_REST_Response $response
     * @param IFolder $folder
     * @param WP_REST_Request $request
     */
    public function save($response, $folder, $request) {
        $param = $request->get_param(self::FIELD_NAME);
        $this->isLocked($folder, $param === '1');
        return $response;
    }
    /**
     * Check if given folder is locked.
     *
     * @param IFolder $folder
     * @param boolean $persist
     */
    public static function isLocked($folder, $persist = null) {
        $instance = \DevOwl\RealPhysicalMedia\listener\Lockfile::getInstance();
        if ($persist !== null && $instance->isLockedObject($folder) !== $persist) {
            if ($persist) {
                $instance->createForObject($folder);
            } else {
                $instance->removeForObject($folder, \false);
            }
        }
        return $instance->isLockedObject($folder);
    }
}
