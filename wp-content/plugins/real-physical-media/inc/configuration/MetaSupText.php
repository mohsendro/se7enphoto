<?php

namespace DevOwl\RealPhysicalMedia\configuration;

use DevOwl\RealPhysicalMedia\Assets;
use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\queue\Queue;
use MatthiasWeb\RealMediaLibrary\api\IFolder;
use MatthiasWeb\RealMediaLibrary\api\IMetadata;
use WP_REST_Request;
use WP_REST_Response;
\defined('ABSPATH') or die('No script kiddies please!');
/**
 * Create Meta sup text for the physical meta.
 */
class MetaSupText implements \MatthiasWeb\RealMediaLibrary\api\IMetadata {
    use UtilsProvider;
    const UNIQUE_NAME = 'rpmSupText';
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
        $content .= '<p class="description" style="font-size:10px;">';
        if (\DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->isAutomaticQueueing()) {
            $content .=
                '<sup>1</sup> ' .
                __('Only applies if automatic change detection from Real Physical Media is enabled.', RPM_TD) .
                '<br/>';
        }
        $content .= '</p>';
        return $content;
    }
    /**
     * Save options - nothing to save here.
     *
     * @param WP_REST_Response $response
     * @param IFolder $folder
     * @param WP_REST_Request $request
     */
    public function save($response, $folder, $request) {
        return $response;
    }
}
