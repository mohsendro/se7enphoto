<?php

namespace DevOwl\RealPhysicalMedia\view;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\handler\Handler;
use DevOwl\RealPhysicalMedia\misc\Seo;
use DevOwl\RealPhysicalMedia\misc\UploadDir;
use DevOwl\RealPhysicalMedia\queue\Queue;
use WP_Post;
use Exception;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Singleton custom field class.
 */
class CustomField {
    use UtilsProvider;
    private static $me;
    /**
     * Create the WP-RFC div container.
     *
     * @param string $html
     * @param WP_Post $post
     */
    public function customField($html, $post) {
        $html .= '<div class="rml-wprfc" data-wprfc="rpm/customField" data-id="' . esc_attr($post->ID) . '"></div>';
        return $html;
    }
    /**
     * Get the HTML for the WP-RFC div container via e.g. REST.
     *
     * @param WP_Post $post
     */
    public function getHtml($post) {
        $html = '';
        if ($post === null) {
            return $html;
        }
        // Skip this one with a filter
        try {
            // Documented in Row
            do_action('RPM/Queue/Skip', $post->ID);
        } catch (\Exception $e) {
            return '<p class="description">' . $e->getMessage() . '</p>';
        }
        $instance = \DevOwl\RealPhysicalMedia\handler\Handler::getInstance()->getCurrentInstance();
        if ($instance === null) {
            $html .=
                '<p class="description">' .
                __(
                    'You have not currently activated a file handler. Please activate a file handler in the settings below.',
                    RPM_TD
                ) .
                '</p>';
            return $html;
        }
        $folder = wp_rml_get_object_by_id(wp_attachment_folder($post->ID));
        if (
            is_rml_folder($folder) &&
            ($pathes = \DevOwl\RealPhysicalMedia\misc\UploadDir::getInstance()->pathes($post->ID, $folder))
        ) {
            $item = \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->item($post->ID);
            if ($item) {
                // Already queued
                $html .=
                    '<p class="description">' .
                    \sprintf(
                        // translators:
                        __('This file was already added to the queue %1$s ago and will be moved to %2$s.', RPM_TD),
                        human_time_diff(\strtotime($item->created), \time()),
                        '<code style="font-size:10px;">' . $pathes['destinationPath'] . '</code>'
                    ) .
                    '</p>';
            } else {
                if ($pathes['identical']) {
                    // Is identical to RML folder
                    $html .=
                        '<p class="description">' .
                        \sprintf(
                            // translators:
                            __('This file is synchronized with your physical file system (<code>%s</code>).', RPM_TD),
                            $pathes['destinationPath']
                        ) .
                        '</p>';
                } else {
                    $html .=
                        '<p>' .
                        \sprintf(
                            // translators:
                            __(
                                '<strong>The physical file is not synchronized with the folder mentioned above.</strong> You can now synchronize and move the file from<br/>%1$s<br/>to<br/>%2$s',
                                RPM_TD
                            ),
                            '<code style="font-size:10px;">' . $pathes['sourcePath'] . '</code>',
                            '<code style="font-size:10px;">' . $pathes['destinationPath'] . '</code>'
                        ) .
                        '</p>';
                    $html .=
                        '<input type="hidden" name="rpm-refresh" />
                        <a href="#" class="button button-primary rpm-manual-queue" style="margin-right:5px;" data-id="' .
                        $post->ID .
                        '">' .
                        __('Move physically', RPM_TD) .
                        '</a>';
                }
            }
        } else {
            // File has no physically because it is computed
            $html .=
                '<p class="description">' .
                __(
                    'This file has no physical file - it is a virtual copy (shortcut). When the original physical file gets synchronized with the file system, this copy is automatically synchronized, too.',
                    RPM_TD
                ) .
                '</p>';
        }
        // SEO dialog
        if (\DevOwl\RealPhysicalMedia\misc\Seo::getInstance()->getCount($post->ID) > 0) {
            $html .= '<span class="rpm-single-seo"></span>';
        }
        return $html;
    }
    /**
     * Get singleton core class.
     *
     * @returns Core
     */
    public static function getInstance() {
        return !isset(self::$me) ? (self::$me = new \DevOwl\RealPhysicalMedia\view\CustomField()) : self::$me;
    }
}
