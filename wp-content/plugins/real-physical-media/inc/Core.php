<?php

namespace DevOwl\RealPhysicalMedia;

use DevOwl\RealPhysicalMedia\base\Core as BaseCore;
use DevOwl\RealPhysicalMedia\configuration\ExcludeFolder;
use DevOwl\RealPhysicalMedia\configuration\Lockfile;
use DevOwl\RealPhysicalMedia\configuration\MetaSupText;
use DevOwl\RealPhysicalMedia\configuration\Options;
use DevOwl\RealPhysicalMedia\configuration\SkipToFirstShortcut;
use DevOwl\RealPhysicalMedia\handler\Handler as HandlerHandler;
use DevOwl\RealPhysicalMedia\listener\FolderListener;
use DevOwl\RealPhysicalMedia\listener\Listener;
use DevOwl\RealPhysicalMedia\misc\Seo as MiscSeo;
use DevOwl\RealPhysicalMedia\misc\SpecialCharacters;
use DevOwl\RealPhysicalMedia\misc\UploadDir;
use DevOwl\RealPhysicalMedia\misc\WpPosts;
use DevOwl\RealPhysicalMedia\queue\Queue as QueueQueue;
use DevOwl\RealPhysicalMedia\rest\Handler;
use DevOwl\RealPhysicalMedia\rest\Queue;
use DevOwl\RealPhysicalMedia\rest\Seo;
use DevOwl\RealPhysicalMedia\rest\Service;
use DevOwl\RealPhysicalMedia\view\AdminBar;
use DevOwl\RealPhysicalMedia\view\CustomField;
use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\ExpireOption;
use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Service as UtilsService;
use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\ServiceNoStore;
use MatthiasWeb\WPU\V4\WPLSController;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Singleton core class which handles the main system for plugin. It includes
 * registering of the autoload, all hooks (actions & filters) (see BaseCore class).
 */
class Core extends \DevOwl\RealPhysicalMedia\base\Core {
    /**
     * Singleton instance.
     */
    private static $me;
    private $updater;
    /**
     * Application core constructor.
     */
    protected function __construct() {
        parent::__construct();
        // Enable `no-store` for our relevant WP REST API endpoints
        \DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\ServiceNoStore::hook(
            '/' . \DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Service::getNamespace($this)
        );
        // Register all your before init hooks here.
        // Note: At this point isn't sure if RML is installed and the min version is reached.
        // It is not recommend to use UtilsProvider::isRMLVersionReached() here, you should use it in
        // all your hook implementations.
        add_action('RML/Activate', [$this->getActivator(), 'rmlActivate']);
        \DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->uploads_use_yearmonth_folders();
        $this->getUpdater();
        // Initially load the updater
        (new \DevOwl\RealPhysicalMedia\AdInitiator())->start();
    }
    /**
     * The init function is fired even the init hook of WordPress. If possible
     * it should register all hooks to have them in one place.
     */
    public function init() {
        // Check if min Real Media Library version is reached...
        if (!$this->isRMLVersionReached()) {
            require_once RPM_INC . 'base/others/fallback-rml.php';
            return;
        }
        // Check if free add-on is used
        if (\class_exists('upload_dir_real_media_library')) {
            require_once RPM_INC . 'base/others/fallback-freeaddon.php';
            return;
        }
        $folderListener = \DevOwl\RealPhysicalMedia\listener\FolderListener::getInstance();
        $listener = \DevOwl\RealPhysicalMedia\listener\Listener::getInstance();
        $handler = \DevOwl\RealPhysicalMedia\handler\Handler::getInstance();
        // Register all your hooks here
        add_action('rest_api_init', [\DevOwl\RealPhysicalMedia\rest\Seo::instance(), 'rest_api_init']);
        add_action('rest_api_init', [\DevOwl\RealPhysicalMedia\rest\Handler::instance(), 'rest_api_init']);
        add_action('rest_api_init', [\DevOwl\RealPhysicalMedia\rest\Queue::instance(), 'rest_api_init']);
        add_action('rest_api_init', [\DevOwl\RealPhysicalMedia\rest\Service::instance(), 'rest_api_init']);
        add_action('admin_bar_menu', [\DevOwl\RealPhysicalMedia\view\AdminBar::getInstance(), 'admin_bar_menu'], 50);
        add_action(
            'update_option_' . RPM_OPT_PREFIX . \DevOwl\RealPhysicalMedia\configuration\Options::OPTION_NAME_PREFIX,
            [\DevOwl\RealPhysicalMedia\configuration\Options::getInstance(), 'automatic_queueing_add']
        );
        add_action(
            'update_option_' . RPM_OPT_PREFIX . \DevOwl\RealPhysicalMedia\configuration\Options::OPTION_NAME_TO_LOWER,
            [\DevOwl\RealPhysicalMedia\configuration\Options::getInstance(), 'automatic_queueing_add']
        );
        add_action(
            'update_option_' .
                RPM_OPT_PREFIX .
                \DevOwl\RealPhysicalMedia\configuration\Options::OPTION_NAME_SPECIAL_CHARS,
            [\DevOwl\RealPhysicalMedia\configuration\Options::getInstance(), 'automatic_queueing_add']
        );
        add_action('delete_attachment', [\DevOwl\RealPhysicalMedia\queue\Queue::getInstance(), 'deleteAttachment']);
        add_action('delete_attachment', [\DevOwl\RealPhysicalMedia\misc\Seo::getInstance(), 'deleteAttachment']);
        add_action('template_redirect', [\DevOwl\RealPhysicalMedia\misc\Seo::getInstance(), 'template_redirect'], 11);
        add_action('RPM/Queue/Added', [\DevOwl\RealPhysicalMedia\queue\Queue::getInstance(), 'initialProcess']);
        add_action('RML/Options/Register', [
            \DevOwl\RealPhysicalMedia\configuration\Options::getInstance(),
            'register_fields'
        ]);
        add_action(
            'RML/CustomField',
            [\DevOwl\RealPhysicalMedia\view\CustomField::getInstance(), 'customField'],
            10,
            2
        );
        add_action('RML/Folder/Created', [$folderListener, 'created'], 10, 4);
        add_action('RML/Folder/Predeletion', [$folderListener, 'preDeletion']);
        add_action('RML/Folder/Delete', [$listener, 'folder_delete']);
        add_action('RML/Item/MoveFinished', [$listener, 'item_move_finished'], 10, 5);
        add_action('RML/Reset/Relations', [$listener, 'wipe']);
        add_action('RML/Reset', [$listener, 'wipe']);
        add_action('RML/Folder/Rename', [$listener, 'folder_rename'], 10, 2);
        add_action('RML/Folder/Renamed', [$listener, 'folder_renamed'], 10, 2);
        add_action('RML/Folder/Move', [$listener, 'folder_move'], 10);
        add_action('RML/Folder/Moved', [$listener, 'folder_moved'], 10);
        add_action('RML/Scripts', [$this->getAssets(), 'admin_enqueue_scripts']);
        add_filter('wp_handle_upload_prefilter', [
            \DevOwl\RealPhysicalMedia\misc\UploadDir::getInstance(),
            'handle_pre_upload'
        ]);
        add_filter('wp_handle_sideload_prefilter', [
            \DevOwl\RealPhysicalMedia\misc\UploadDir::getInstance(),
            'handle_pre_upload'
        ]);
        add_filter('wp_handle_upload', [\DevOwl\RealPhysicalMedia\misc\UploadDir::getInstance(), 'handle_upload']);
        add_filter('dbdelta_create_queries', [
            \DevOwl\RealPhysicalMedia\misc\WpPosts::getInstance(),
            'dbdelta_create_queries'
        ]);
        add_filter('RML/Folder/Meta/Groups', [
            \DevOwl\RealPhysicalMedia\configuration\Options::getInstance(),
            'folder_meta_groups'
        ]);
        add_rml_meta_box(
            \DevOwl\RealPhysicalMedia\configuration\ExcludeFolder::UNIQUE_NAME,
            new \DevOwl\RealPhysicalMedia\configuration\ExcludeFolder(),
            \false,
            10,
            \DevOwl\RealPhysicalMedia\configuration\Options::META_BOX_GROUP
        );
        add_rml_meta_box(
            \DevOwl\RealPhysicalMedia\configuration\SkipToFirstShortcut::UNIQUE_NAME,
            new \DevOwl\RealPhysicalMedia\configuration\SkipToFirstShortcut(),
            \false,
            10,
            \DevOwl\RealPhysicalMedia\configuration\Options::META_BOX_GROUP
        );
        if (current_user_can('manage_options')) {
            add_rml_meta_box(
                \DevOwl\RealPhysicalMedia\configuration\Lockfile::UNIQUE_NAME,
                new \DevOwl\RealPhysicalMedia\configuration\Lockfile(),
                \false,
                10,
                \DevOwl\RealPhysicalMedia\configuration\Options::META_BOX_GROUP
            );
        }
        add_rml_meta_box(
            \DevOwl\RealPhysicalMedia\configuration\MetaSupText::UNIQUE_NAME,
            new \DevOwl\RealPhysicalMedia\configuration\MetaSupText(),
            \false,
            999,
            \DevOwl\RealPhysicalMedia\configuration\Options::META_BOX_GROUP
        );
        // Show plugin notice
        if (!$this->getUpdater()->isActivated()) {
            add_action('after_plugin_row_' . plugin_basename(RPM_FILE), [$this, 'after_plugin_row'], 10, 2);
        }
        // Show notice when no handler is active, or activate automatically
        if ($handler->getCurrent() === null && current_user_can('install_plugins')) {
            add_action('admin_notices', [$this, 'admin_notices_no_handler']);
            add_action('activated_plugin', [$handler, 'activated_plugin'], 10, 3);
        }
        // Lowercase path
        if (\DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->isToLowerCase()) {
            add_filter('sanitize_file_name', \function_exists('mb_strtolower') ? 'mb_strtolower' : 'strtolower');
            // strtolower does not support special characters
        }
        // Special characters
        if (\DevOwl\RealPhysicalMedia\configuration\Options::getInstance()->isSpecialCharacters()) {
            // add_filter('RPM/Attachment/Folder/Path', [SpecialCharacters::class, 'sanitize'], 9);
            add_filter('sanitize_file_name', [\DevOwl\RealPhysicalMedia\misc\SpecialCharacters::class, 'sanitize'], 9);
        }
    }
    /**
     * Set and/or get the value if the license notice is dismissed.
     *
     * @param boolean $set
     */
    public function isLicenseNoticeDismissed($set = null) {
        $value = '1';
        $expireOption = new \DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\ExpireOption(
            RPM_OPT_PREFIX . '_licenseActivated',
            \false,
            365 * \constant('DAY_IN_SECONDS')
        );
        $expireOption->enableTransientMigration(
            \DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\ExpireOption::TRANSIENT_MIGRATION_SITE_WIDE
        );
        if ($set !== null) {
            $expireOption->set($set ? $value : 0);
        }
        return $expireOption->get() === $value;
    }
    /**
     * Show a notice in the plugins list that the plugin is not activated, yet.
     *
     * @param string $file
     * @param string $plugin
     */
    public function after_plugin_row($file, $plugin) {
        $wp_list_table = _get_list_table('WP_Plugins_List_Table');
        \printf(
            '<tr class="rpm-update-notice active">
	<th colspan="%d" class="check-column">
    	<div class="plugin-update update-message notice inline notice-warning notice-alt">
        	<div class="update-message">%s</div>
    	</div>
    </th>
</tr>',
            $wp_list_table->get_column_count(),
            wpautop(
                __(
                    '<strong>You have not yet entered the license key</strong>. To receive automatic updates, please enter the key in "Enter license".',
                    RPM_TD
                )
            )
        );
    }
    /**
     * This notice is shown when no handler is visible.
     */
    public function admin_notices_no_handler() {
        echo '<div class="notice notice-error hidden" id="rpm-no-handler-notice"></div>';
    }
    /**
     * Send an email newsletter if checked in the updater.
     *
     * @param string $email
     */
    public function handleNewsletter($email) {
        wp_remote_post('https://devowl.io/wp-json/devowl-site/v1/plugin-activation-newsletter', [
            'body' => ['email' => $email, 'referer' => home_url(), 'slug' => RPM_SLUG]
        ]);
    }
    /**
     * Get the updater instance.
     *
     * @see https://github.com/matzeeable/wordpress-plugin-updater
     */
    public function getUpdater() {
        if ($this->updater === null) {
            $this->updater = \MatthiasWeb\WPU\V4\WPLSController::initClient('https://license.matthias-web.com/', [
                'name' => 'Real Physical Media',
                'version' => RPM_VERSION,
                'path' => RPM_FILE,
                'slug' => RPM_SLUG,
                'newsletterPrivacy' => 'https://devowl.io/privacy-policy/'
            ]);
            add_action('wpls_email_' . RPM_SLUG, [$this, 'handleNewsletter']);
        }
        return $this->updater;
    }
    /**
     * Get singleton core class.
     *
     * @return Core
     */
    public static function getInstance() {
        return !isset(self::$me) ? (self::$me = new \DevOwl\RealPhysicalMedia\Core()) : self::$me;
    }
}
// Inherited from packages/utils/src/Service
/**
 * See API docs.
 *
 * @api {get} /real-physical-media/v1/plugin Get plugin information
 * @apiHeader {string} X-WP-Nonce
 * @apiName GetPlugin
 * @apiGroup Plugin
 *
 * @apiSuccessExample {json} Success-Response:
 * {
 *     Name: "My plugin",
 *     PluginURI: "https://example.com/my-plugin",
 *     Version: "0.1.0",
 *     Description: "This plugin is doing something.",
 *     Author: "<a href="https://example.com">John Smith</a>",
 *     AuthorURI: "https://example.com",
 *     TextDomain: "my-plugin",
 *     DomainPath: "/languages",
 *     Network: false,
 *     Title: "<a href="https://example.com">My plugin</a>",
 *     AuthorName: "John Smith"
 * }
 * @apiVersion 0.1.0
 */
