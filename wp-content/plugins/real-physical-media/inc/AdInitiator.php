<?php

namespace DevOwl\RealPhysicalMedia;

use DevOwl\RealPhysicalMedia\Vendor\DevOwl\RealUtils\AbstractInitiator;
use DevOwl\RealPhysicalMedia\Vendor\DevOwl\RealUtils\WelcomePage;
use DevOwl\RealPhysicalMedia\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Initiate real-utils functionality.
 */
class AdInitiator extends \DevOwl\RealPhysicalMedia\Vendor\DevOwl\RealUtils\AbstractInitiator {
    use UtilsProvider;
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getPluginBase() {
        return $this;
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getPluginAssets() {
        return $this->getCore()->getAssets();
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getRateLink() {
        return 'https://devowl.io/go/codecanyon/real-physical-media/rate';
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getKeyFeatures() {
        return [
            [
                'image' => $this->getAssetsUrl('feature-queue.gif'),
                'title' => __('Reliable moving of files', RPM_TD),
                'description' => __(
                    'Moves new uploads directly to the correct physical folder, but existing files can also be moved physically.',
                    RPM_TD
                )
            ],
            [
                'image' => $this->getAssetsUrl('feature-seo.jpg'),
                'title' => __('Redirect old media URLs', RPM_TD),
                'description' => __(
                    'SEO URL redirects protect you from errors: If your or any other website refers to a file whose URL changes when the file is moved, the user\'s browser will be automatically redirected with a 301 and 302 redirect for best SEO results.',
                    RPM_TD
                )
            ],
            [
                'image' => $this->getAssetsUrl('feature-manual.jpg'),
                'title' => __('Where is your file located?', RPM_TD),
                'description' => __(
                    'Opening a single media file allows you to view physical location and manually move the file physically.',
                    RPM_TD
                )
            ]
        ];
    }
}
