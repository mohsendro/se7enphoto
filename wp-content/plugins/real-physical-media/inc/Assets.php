<?php

namespace DevOwl\RealPhysicalMedia;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\configuration\Options;
use DevOwl\RealPhysicalMedia\handler\Handler;
use DevOwl\RealPhysicalMedia\queue\Queue;
use DevOwl\RealPhysicalMedia\view\AdminBar;
use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Assets as UtilsAssets;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Asset management for frontend scripts and styles.
 */
class Assets {
    use UtilsProvider;
    use UtilsAssets;
    /**
     * Enqueue scripts and styles depending on the type. This function is called
     * from both admin_enqueue_scripts and wp_enqueue_scripts. You can check the
     * type through the $type parameter. In this function you can include your
     * external libraries from src/public/lib, too.
     *
     * @param string $type The type (see utils Assets constants)
     * @param string $hook_suffix The current admin page
     */
    public function enqueue_scripts_and_styles($type, $hook_suffix = null) {
        // Generally check if an entrypoint should be loaded (not needed, only loaded with RML/Scripts)
        // if (!in_array($type, [self::$TYPE_ADMIN], true)) {
        //     return;
        // }
        $realUtils = RPM_ROOT_SLUG . '-real-utils-helper';
        // Your assets implementation here... See utils Assets for enqueue* methods
        // $useNonMinifiedSources = $this->useNonMinifiedSources(); // Use this variable if you need to differ between minified or non minified sources
        // Our utils package relies on jQuery, but this shouldn't be a problem as the most themes still use jQuery (might be replaced with https://github.com/github/fetch)
        // Enqueue external utils package
        $scriptDeps = $this->enqueueUtils();
        $scriptDeps = \array_merge($scriptDeps, [$realUtils]);
        // Enqueue plugin entry points
        $handle = $this->enqueueScript('admin', 'admin.js', $scriptDeps);
        $this->enqueueStyle('admin', 'admin.css', [$realUtils]);
        // Localize script with server-side variables
        wp_localize_script($handle, RPM_SLUG_CAMELCASE, $this->localizeScript($type));
    }
    /**
     * Localize the WordPress backend and frontend. If you want to provide URLs to the
     * frontend you have to consider that some JS libraries do not support umlauts
     * in their URI builder. For this you can use utils Assets#getAsciiUrl.
     *
     * Also, if you want to use the options typed in your frontend you should
     * adjust the following file too: src/public/ts/store/option.tsx
     *
     * @param string $context
     * @return array
     */
    public function overrideLocalizeScript($context) {
        $options = \DevOwl\RealPhysicalMedia\configuration\Options::getInstance();
        $core = \DevOwl\RealPhysicalMedia\Core::getInstance();
        $isLicenseActivated = $core->getUpdater()->isActivated();
        $isLicenseNoticeDismissed = $core->isLicenseNoticeDismissed();
        $canInstallPlugins = current_user_can('install_plugins');
        return [
            'installPluginNonce' => $canInstallPlugins ? wp_create_nonce('updates') : '',
            'initialQueue' => \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->prepareResponse(0, 5),
            'initialPausedError' => \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->createExceptionFromDb(\true),
            'manageOptions' => current_user_can('manage_options') ? admin_url('options-media.php') : \false,
            'seoAttachmentPage' => admin_url('post.php?action=edit&post='),
            'countDown' => [
                'processing' => $options->getCountdownProcessing(),
                'pause' => $options->getCountdownPause()
            ],
            'handlers' => \DevOwl\RealPhysicalMedia\handler\Handler::getInstance()->get(),
            'isAutomaticQueueing' => \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->isAutomaticQueueing(
                null,
                \true
            ),
            'isFirstTimeMoveHintDismissed' => \DevOwl\RealPhysicalMedia\view\AdminBar::getInstance()->isFirstTimeMoveHintDismissed(),
            'isFirstTimeQueueNoticeDismissed' => \DevOwl\RealPhysicalMedia\view\AdminBar::getInstance()->isFirstTimeQueueNoticeDismissed(),
            'supportsAllChildrenSql' => wp_rml_all_children_sql_supported(),
            'showLicenseNotice' =>
                !$isLicenseActivated && !$isLicenseNoticeDismissed && current_user_can('install_plugins'),
            'pluginsUrl' => admin_url('plugins.php')
        ];
    }
}
