<?php

namespace DevOwl\RealPhysicalMedia\configuration;

use DevOwl\RealPhysicalMedia\Assets;
use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\listener\Listener;
use DevOwl\RealPhysicalMedia\queue\Queue;
use MatthiasWeb\RealMediaLibrary\api\IFolder;
use MatthiasWeb\RealMediaLibrary\api\IMetadata;
use MatthiasWeb\RealMediaLibrary\metadata\CommonFolderTrait;
use WP_REST_Request;
use WP_REST_Response;
\defined('ABSPATH') or die('No script kiddies please!');
/**
 * Allow to skip this file in this folder and use the next shortcut file.
 */
class SkipToFirstShortcut implements \MatthiasWeb\RealMediaLibrary\api\IMetadata {
    use UtilsProvider;
    use CommonFolderTrait;
    const UNIQUE_NAME = 'rpmSkipToFirstShortcut';
    const FIELD_NAME = 'skipToFirstShortcut';
    const META_NAME = self::UNIQUE_NAME;
    /**
     * C'tor.
     */
    public function __construct() {
        add_filter('RPM/Attachment/Folder', [$this, 'attachment_folder'], 1, 3);
    }
    /**
     * Return first found shortcut folder if folder is skipped.
     *
     * @param IFolder $folder
     * @param IFolder $attachmentFolder
     * @param int $attachment
     */
    public function attachment_folder($folder, $attachmentFolder, $attachment) {
        if (self::isSkip($attachmentFolder)) {
            $shortcuts = wp_attachment_get_shortcuts($attachment);
            if (\count($shortcuts) > 0) {
                return wp_rml_get_object_by_id(wp_attachment_folder($shortcuts[0], $folder->getId()));
            }
        }
        return $folder;
    }
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
        $content .=
            '<label><input name="' .
            self::FIELD_NAME .
            '" type="checkbox" value="1" ' .
            checked(1, self::isSkip($folder), \false) .
            ' /> ' .
            __('First shortcut path as file path', RPM_TD) .
            ' ' .
            (\DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->isAutomaticQueueing() ? '<sup>1</sup>' : '') .
            '</label>
            <p class="description">' .
            __(
                'Moves the file physically to the location where the first shortcut is created. This is useful, for example, if you have a "All Images" folder and use the same images in several galleries, but do not want to have the "All Images" folder in the pathname. The WP/LR plugin works this way and you have to enable this option for the synchronized folder.',
                RPM_TD
            ) .
            '</p>';
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
        if (self::isSkip($folder, $param === '1')) {
            \DevOwl\RealPhysicalMedia\listener\Listener::getInstance()->addToQueueRecursively($folder->getId());
        }
        return $response;
    }
    /**
     * Check if given folder is skipped.
     *
     * @param IFolder $folder
     * @param boolean $persist
     */
    public static function isSkip($folder, $persist = null) {
        return self::is(self::META_NAME, $folder, $persist);
    }
}
