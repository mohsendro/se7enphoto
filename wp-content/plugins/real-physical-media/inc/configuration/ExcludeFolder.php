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
 * Allow to exclude a folder for the physical path.
 */
class ExcludeFolder implements \MatthiasWeb\RealMediaLibrary\api\IMetadata {
    use UtilsProvider;
    use CommonFolderTrait;
    const UNIQUE_NAME = 'rpmPhysicalExcludeFolder';
    const FIELD_NAME = 'physicalExcludeFolder';
    const META_NAME = self::UNIQUE_NAME;
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
            checked(1, self::isExcluded($folder), \false) .
            ' /> ' .
            __('Exclude folder name from file path ', RPM_TD) .
            ' ' .
            (\DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->isAutomaticQueueing() ? '<sup>1</sup>' : '') .
            '</label>
            <p class="description">' .
            __('The name of the folder will not be visible in the URLs of the files it contains.', RPM_TD) .
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
        if (self::isExcluded($folder, $param === '1')) {
            \DevOwl\RealPhysicalMedia\listener\Listener::getInstance()->addToQueueRecursively($folder->getId());
        }
        return $response;
    }
    /**
     * Check if given folder is excluded.
     *
     * @param IFolder $folder
     * @param boolean $persist
     */
    public static function isExcluded($folder, $persist = null) {
        return self::is(self::META_NAME, $folder, $persist);
    }
}
