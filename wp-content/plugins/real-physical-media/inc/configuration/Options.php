<?php

namespace DevOwl\RealPhysicalMedia\configuration;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
use DevOwl\RealPhysicalMedia\handler\Handler;
use DevOwl\RealPhysicalMedia\queue\Queue;
use DevOwl\RealPhysicalMedia\listener\Lockfile as ListenerLockfile;
use DevOwl\RealPhysicalMedia\misc\WpPosts;
use MatthiasWeb\RealMediaLibrary\view\Options as ViewOptions;
use DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Service;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Singleton options class.
 */
class Options {
    use UtilsProvider;
    const META_BOX_GROUP = 'physical';
    const UPLOADS_USE_YEARMONTH_FOLDERS = 'uploads_use_yearmonth_folders';
    const OPTION_NAME_PREFIX = '_prefix';
    const OPTION_NAME_TO_LOWER = '_to_lower';
    const OPTION_DEFAULT_TO_LOWER = 1;
    const OPTION_NAME_SPECIAL_CHARS = '_special_chars';
    const OPTION_DEFAULT_SPECIAL_CHARS = 1;
    const OPTION_NAME_MAX_EXEC_TIME = '_max_exec_time';
    const OPTION_DEFAULT_MAX_EXEC_TIME = 5;
    const OPTION_NAME_COUNTDOWN_PROCESSING = '_countdown_processing';
    const OPTION_DEFAULT_COUNTDOWN_PROCESSING = 2;
    const OPTION_NAME_COUNTDOWN_PAUSE = '_countdown_pause';
    const OPTION_DEFAULT_COUNTDOWN_PAUSE = 20;
    const OPTION_NAME_CLEAN_CREATE = '_cleanup_create';
    const OPTION_DEFAULT_CLEAN_CREATE = '1';
    const OPTION_NAME_CLEAN_DELETE = '_cleanup_delete';
    const OPTION_DEFAULT_CLEAN_DELETE = '1';
    const OPTION_NAME_CLEAN_MOVE = '_cleanup_move';
    const OPTION_DEFAULT_CLEAN_MOVE = '1';
    const OPTION_NAME_CLEAN_VIRTUAL_MOVE = '_cleanupv_move';
    const OPTION_DEFAULT_CLEAN_VIRTUAL_MOVE = '0';
    const OPTION_NAME_SEO_301 = '_seo_301';
    const OPTION_DEFAULT_SEO_301 = 72;
    private static $me;
    /**
     * Disable the year/month options totally.
     */
    public function uploads_use_yearmonth_folders() {
        if (get_option(self::UPLOADS_USE_YEARMONTH_FOLDERS)) {
            update_option(self::UPLOADS_USE_YEARMONTH_FOLDERS, \false);
        }
    }
    /**
     * Register tab and fields in Real Media Library options.
     */
    public function register_fields() {
        // Register tab
        add_settings_section(
            'rml_options_rpm',
            'RealMediaLibrary:Real Physical Media',
            [\MatthiasWeb\RealMediaLibrary\view\Options::getInstance(), 'empty_callback'],
            'media'
        );
        $field_name = 'rpm_option_buttons';
        add_settings_field(
            $field_name,
            '<label>&nbsp;</label>',
            [$this, 'html_' . $field_name],
            'media',
            'rml_options_rpm'
        );
        $field_name = 'rpm_option_handler';
        add_settings_field(
            $field_name,
            '<label>' . __('File moving handler', RPM_TD) . '</label>',
            [$this, 'html_' . $field_name],
            'media',
            'rml_options_rpm'
        );
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_PREFIX;
        if (!self::isDemoEnv()) {
            add_option($field_name, '');
            register_setting('media', $field_name, 'esc_attr');
        }
        add_settings_field(
            $field_name,
            '<label for="' . $field_name . '">' . __('Uploads prefix', RPM_TD) . '</label>',
            [$this, 'html_' . $field_name],
            'media',
            'rml_options_rpm'
        );
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_TO_LOWER;
        add_option($field_name, self::OPTION_DEFAULT_TO_LOWER);
        register_setting('media', $field_name, 'esc_attr');
        add_settings_field(
            $field_name,
            '<label for="' . $field_name . '">' . __('Lowercase', RPM_TD) . '</label>',
            [$this, 'html_' . $field_name],
            'media',
            'rml_options_rpm'
        );
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_SPECIAL_CHARS;
        add_option($field_name, self::OPTION_DEFAULT_SPECIAL_CHARS);
        register_setting('media', $field_name, 'esc_attr');
        add_settings_field(
            $field_name,
            '<label for="' . $field_name . '">' . __('Special characters', RPM_TD) . '</label>',
            [$this, 'html_' . $field_name],
            'media',
            'rml_options_rpm'
        );
        add_option(RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_MOVE, self::OPTION_DEFAULT_CLEAN_MOVE);
        add_option(RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_CREATE, self::OPTION_DEFAULT_CLEAN_CREATE);
        add_option(RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_DELETE, self::OPTION_DEFAULT_CLEAN_DELETE);
        add_option(RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_VIRTUAL_MOVE, self::OPTION_DEFAULT_CLEAN_VIRTUAL_MOVE);
        register_setting('media', RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_MOVE, 'esc_attr');
        register_setting('media', RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_CREATE, 'esc_attr');
        register_setting('media', RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_DELETE, 'esc_attr');
        register_setting('media', RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_VIRTUAL_MOVE, 'esc_attr');
        add_settings_field(
            RPM_OPT_PREFIX . '_directories',
            '<label id="rpm_head_directories">' . __('Folders', RPM_TD) . '</label>',
            [$this, 'html_rpm_directories'],
            'media',
            'rml_options_rpm'
        );
        $field_name = 'rpm_options_cronjob';
        add_settings_field(
            $field_name,
            '<label id="rpm_cronjob">' . __('Cronjob service', RPM_TD) . '</label>',
            [$this, 'html_' . $field_name],
            'media',
            'rml_options_rpm'
        );
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_SEO_301;
        add_option($field_name, self::OPTION_DEFAULT_SEO_301);
        register_setting('media', $field_name, 'esc_attr');
        add_settings_field(
            $field_name,
            '<label for="' . $field_name . '">' . __('301 redirect delay (SEO)', RPM_TD) . '</label>',
            [$this, 'html_' . $field_name],
            'media',
            'rml_options_rpm'
        );
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_MAX_EXEC_TIME;
        if (!self::isDemoEnv()) {
            add_option($field_name, self::OPTION_DEFAULT_MAX_EXEC_TIME);
            register_setting('media', $field_name, 'esc_attr');
        }
        add_settings_field(
            $field_name,
            '<label for="' . $field_name . '">' . __('Queue maximum execution time', RPM_TD) . '</label>',
            [$this, 'html_' . $field_name],
            'media',
            'rml_options_rpm'
        );
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_COUNTDOWN_PROCESSING;
        if (!self::isDemoEnv()) {
            add_option($field_name, self::OPTION_DEFAULT_COUNTDOWN_PROCESSING);
            register_setting('media', $field_name, 'esc_attr');
        }
        add_settings_field(
            $field_name,
            '<label for="' . $field_name . '">' . __('Queue interval', RPM_TD) . '</label>',
            [$this, 'html_' . $field_name],
            'media',
            'rml_options_rpm'
        );
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_COUNTDOWN_PAUSE;
        add_option($field_name, self::OPTION_DEFAULT_COUNTDOWN_PAUSE);
        register_setting('media', $field_name, 'esc_attr');
        add_settings_field(
            $field_name,
            '<label for="' . $field_name . '">' . __('Queue pause interval', RPM_TD) . '</label>',
            [$this, 'html_' . $field_name],
            'media',
            'rml_options_rpm'
        );
        add_settings_field(
            RPM_OPT_PREFIX . '_advanced',
            '<label>' . __('Post Name/URL max. length', RPM_TD) . '</label>',
            [$this, 'html_url_max_length'],
            'media',
            'rml_options_rpm'
        );
    }
    // self-explaining
    public function html_rpm_option_buttons() {
        $instance = \DevOwl\RealPhysicalMedia\handler\Handler::getInstance()->getCurrentInstance();
        if ($instance === null) {
            _e(
                'You have not currently activated a file handler. Please activate a file handler in the settings below.',
                RPM_TD
            );
        } else {
            $count = \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->getPotentialFilesCount();
            echo '<div class="notice notice-info inline notice-alt">
                <p>' .
                __(
                    'You can automatically sort all files you have already sorted with Real Media Library in a physical way.',
                    RPM_TD
                ) .
                (!\DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->isAutomaticQueueing()
                    ? __(
                        '<br /><br />Before you reorder all files, move one file in your media library and go to the "Attachment Details" or "Edit Media" dialog of the file you last moved and click the "Move physically" button. Then check that the file is available at the new URL as expected.<br /><br />We recommend that you perform this manual check before you reorder all files, because special WordPress configurations rarely result in errors with the Media File Renamer handler.',
                        RPM_TD
                    )
                    : '') .
                '</p>
                <p><a class="rml-rest-button button" ' .
                self::getDemoDisabledAttr() .
                ' data-url="queue/item/potential" data-urlnamespace="real-physical-media/v1" data-method="POST">' .
                \sprintf(__('Add %d files to queue for moving', RPM_TD), $count) .
                '</a></p>
                ' .
                $this->getDemoRestrictionText(\true) .
                '
            </div>';
        }
    }
    // self-explaining
    public function html_rpm_option_handler() {
        // See admin.tsx
        echo '<ul id="rpm-handlers"></ul>';
    }
    // self-explaining
    public function html_rpm_prefix() {
        $upload_dir = wp_upload_dir();
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_PREFIX;
        echo '<input ' .
            self::getDemoDisabledAttr() .
            ' type="text" value="' .
            esc_attr($this->getPrefix()) .
            '" id="' .
            $field_name .
            '" name="' .
            $field_name .
            '"/>
            <p class="description">' .
            \sprintf(
                // translators:
                __('Your new uploads will be stored in: <code>%s</code>', RPM_TD),
                self::isDemoEnv() ? 'wp-content/uploads' : $upload_dir['path']
            ) .
            '<br/>
            ' .
            __('If you set an upload prefix, this prefix is appended to the above path.', RPM_TD) .
            '</p>' .
            $this->getDemoRestrictionText(\true);
        if (\DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->canAutomaticQueueing()) {
            echo '<div class="notice notice-warning inline notice-alt hidden rpm-option-change"><p>' .
                __(
                    'Note: You have activated automatic change detection. If you change the prefix, all files will be moved, which may take a few minutes..',
                    RPM_TD
                ) .
                '</p></div>';
        }
    }
    // self-explaining
    public function html_rpm_to_lower() {
        echo '<label>
        <input type="checkbox" name="' .
            RPM_OPT_PREFIX .
            self::OPTION_NAME_TO_LOWER .
            '" ' .
            checked($this->isToLowerCase(), \true, \false) .
            ' value="1" />' .
            __('Automatically transform the complete file and folder path to lowercase', RPM_TD) .
            '</label>';
        if (\DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->canAutomaticQueueing()) {
            echo '<div class="notice notice-warning inline notice-alt hidden rpm-option-change"><p>' .
                __(
                    'Note: You have activated automatic change detection. If you change this option, all files will be moved, which may take a few minutes.',
                    RPM_TD
                ) .
                '</p></div>';
        }
    }
    // self-explaining
    public function html_rpm_max_exec_time() {
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_MAX_EXEC_TIME;
        $server_max = (int) \ini_get('max_execution_time');
        $server_max = $server_max === 0 ? 'unlimited' : $server_max;
        echo '<input ' .
            self::getDemoDisabledAttr() .
            ' type="number" value="' .
            esc_attr($this->getMaxExecTime()) .
            '" id="' .
            $field_name .
            '" name="' .
            $field_name .
            '"/> s
            <p class="description">' .
            __(
                'Moving files is processed in a queue and in several requests to the server. The individual requests must be limited in time based on the configuration of PHP on your server.',
                RPM_TD
            ) .
            '<br />
            ' .
            \sprintf(
                // translators:
                __(
                    'The minimum allowed value is 1 second. Your server configuration allows you to run the request for max. %s seconds.',
                    RPM_TD
                ),
                $server_max
            ) .
            '</p>' .
            $this->getDemoRestrictionText(\true);
    }
    // self-explaining
    public function html_rpm_countdown_processing() {
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_COUNTDOWN_PROCESSING;
        echo '<input' .
            self::getDemoDisabledAttr() .
            ' type="number" value="' .
            esc_attr($this->getCountdownProcessing()) .
            '" id="' .
            $field_name .
            '" name="' .
            $field_name .
            '"/> s
            <p class="description">' .
            __(
                'When processing the queue, not all requests are sent immediately, but one after the other. This value defines how long to wait between two requests.',
                RPM_TD
            ) .
            '<br />
            ' .
            __(
                'Set up a delay so as not to overload your server and wait a certain time of seconds. The minimum allowed value is 1 second.',
                RPM_TD
            ) .
            '</p>' .
            $this->getDemoRestrictionText(\true);
    }
    // self-explaining
    public function html_rpm_countdown_pause() {
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_COUNTDOWN_PAUSE;
        echo '<input' .
            self::getDemoDisabledAttr() .
            ' type="number" value="' .
            esc_attr($this->getCountdownPause()) .
            '" id="' .
            $field_name .
            '" name="' .
            $field_name .
            '"/> s
            <p class="description">' .
            __(
                'If the queue is empty, the Browser must regularly check if there is a new task in the queue. This value defines after how much seconds the browser will check again if a new task exists. The minimum allowed value is 1 second.',
                RPM_TD
            ) .
            '<br />
            ' .
            __(
                'Manual changes are automatically detected and a request to move the file physically will be sent immediately. For example, if you move a file by drag & drop, the file will be moved immediately.',
                RPM_TD
            ) .
            '</p>' .
            $this->getDemoRestrictionText(\true);
    }
    // self-explaining
    public function html_rpm_directories() {
        echo '<label>
            <input type="checkbox" name="' .
            RPM_OPT_PREFIX .
            self::OPTION_NAME_CLEAN_CREATE .
            '" ' .
            checked($this->isCleanupCreateEnabled(), \true, \false) .
            ' value="1" />
            ' .
            \sprintf(
                // translators:
                __(
                    'Create a physical folder after you create a virtual folder, even if the folder is still empty (creates a lock file %s to keep the physical folder).',
                    RPM_TD
                ),
                '<code>' . \DevOwl\RealPhysicalMedia\listener\Lockfile::NAME . '</code>'
            ) .
            '
        </label><br/>
        <label>
            <input type="checkbox" name="' .
            RPM_OPT_PREFIX .
            self::OPTION_NAME_CLEAN_DELETE .
            '" ' .
            checked($this->isCleanupDeleteEnabled(), \true, \false) .
            ' value="1" />
            ' .
            \sprintf(
                // translators:
                __(
                    'Delete physical folders after deleting a virtual folder, but only if the physical folder and subfolders were empty.',
                    RPM_TD
                )
            ) .
            '
        </label><br/>
        <label>
            <input type="checkbox" name="' .
            RPM_OPT_PREFIX .
            self::OPTION_NAME_CLEAN_MOVE .
            '" ' .
            checked($this->isCleanupMoveEnabled(), \true, \false) .
            ' value="1" />
            ' .
            __(
                'After moving the last file from a virtual folder, the physical folder should be deleted, but only if the subfolders are also empty and there is no lock file.',
                RPM_TD
            ) .
            '
        </label><br />
        <label style="margin-left:25px;">
            <input type="checkbox" name="' .
            RPM_OPT_PREFIX .
            self::OPTION_NAME_CLEAN_VIRTUAL_MOVE .
            '" ' .
            checked($this->isCleanupVirtualMoveEnabled(), \true, \false) .
            ' value="1" />
            ' .
            __(
                'After the last file or folder is moved from a virtual folder, both the virtual folder and the physical folder should get deleted.',
                RPM_TD
            ) .
            '
        </label>
        <hr/>
        <p>
            ' .
            \sprintf(
                // translators:
                __(
                    'Reflect the already virtually created folder structure (not the file locations) to your physical folders in %s:',
                    RPM_TD
                ),
                '<code>wp-content/uploads/</code>'
            ) .
            '</p><p>
            <a class="rml-rest-button button" data-url="lockfiles/reflect" data-urlnamespace="real-physical-media/v1" data-method="POST" data-withlockfile="true">' .
            __('Physical folders with lockfile (all folders)', RPM_TD) .
            '</a>&nbsp;
            <a class="rml-rest-button button" data-url="lockfiles/reflect" data-urlnamespace="real-physical-media/v1" data-method="POST">' .
            __('Physical folders without lockfile (only folders with uploads)', RPM_TD) .
            '</a>&nbsp;
            <a class="rml-rest-button button" data-url="lockfiles" data-urlnamespace="real-physical-media/v1" data-method="DELETE">' .
            __('Delete all lockfiles', RPM_TD) .
            '</a></p><p>
            ' .
            __(
                'If you have changed and saved the folder settings above, you must reflect the structure again, otherwise the new settings will only apply to new folder structures.',
                RPM_TD
            ) .
            '
        </p>';
    }
    // self-explaining
    public function html_rpm_special_chars() {
        echo '<label>
            <input type="checkbox" name="' .
            RPM_OPT_PREFIX .
            self::OPTION_NAME_SPECIAL_CHARS .
            '" ' .
            checked($this->isSpecialCharacters(), \true, \false) .
            ' value="1" />' .
            __('Automatically transform special characters to latin characters', RPM_TD) .
            '</label>
            <p class="description">' .
            __(
                'WordPress itself cannot handle special characters in file and folder names without problems. Enable this option to convert special characters to latin characters.',
                RPM_TD
            ) .
            '</p>';
        if (\DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->canAutomaticQueueing()) {
            echo '<div class="notice notice-warning inline notice-alt hidden rpm-option-change"><p>' .
                __(
                    'Note: You have activated automatic change detection. If you change this option, all files will be moved, which may take a few minutes.',
                    RPM_TD
                ) .
                '</p></div>';
        }
    }
    // self-explaining
    public function html_rpm_options_cronjob() {
        echo '<p class="description">' .
            __(
                'The queue to physically move files can only be processed as long as a logged in user has the WordPress backend open in his browser.',
                RPM_TD
            ) .
            ' ' .
            __(
                'This limitation exists because WordPress is developed in PHP and PHP scripts must be called to be run, but cannot run as a background process. You can solve this problem by setting up a cronjob that calls the given cronjob URL regularly (e.g. every 30 seconds).',
                RPM_TD
            ) .
            ' ' .
            __(
                'You have to ask your hoster if they offer such functionality or use an external service like easycron.com.',
                RPM_TD
            ) .
            ' ' .
            '<a href="https://wikipedia.org/wiki/Cron" target="_blank">' .
            __('Learn more about Cronjobs', RPM_TD) .
            '</a></p>' .
            '<p>' .
            __('All cronjob URLs for all websites hosted in this WordPress instance:', RPM_TD) .
            '</p>';
        // WordPress 4.6
        if (\function_exists('get_sites') && \class_exists('WP_Site_Query')) {
            $sites = get_sites();
        }
        // WordPress < 4.6
        if (\function_exists('wp_get_sites')) {
            $sites = wp_get_sites();
        }
        echo '<ul style="list-style:initial!important;">';
        if (isset($sites) && !self::isDemoEnv()) {
            foreach ($sites as $site) {
                $os = (object) $site;
                $this->cronjobLi($os->blog_id);
            }
        } else {
            // No site, use current blog id
            $this->cronjobLi(get_current_blog_id());
        }
        echo '</ul>';
    }
    /**
     * Create meta group in folder settings.
     *
     * @param array $groups
     */
    public function folder_meta_groups($groups) {
        $groups[\DevOwl\RealPhysicalMedia\configuration\Options::META_BOX_GROUP] = __('Real Physical Media', RPM_TD);
        return $groups;
    }
    /**
     * Create a <li> list element and output the current blog cronjob URL.
     *
     * @param int $blogId
     */
    private function cronjobLi($blogId) {
        $multisite = \function_exists('switch_to_blog');
        if ($multisite) {
            switch_to_blog($blogId);
        }
        $url = \DevOwl\RealPhysicalMedia\Vendor\MatthiasWeb\Utils\Service::getUrl(
            $this,
            null,
            'queue/cron/' . $this->getCronJobToken($blogId)
        );
        echo '<li>' .
            get_bloginfo('name') .
            ': <input readonly="readonly" type="text" class="rpm-cronjob-url regular-text" value="' .
            esc_url($url) .
            '" /></li>';
        if ($multisite) {
            restore_current_blog();
        }
    }
    // self-explaining
    public function html_rpm_seo_301() {
        $field_name = RPM_OPT_PREFIX . self::OPTION_NAME_SEO_301;
        echo '<input type="number" value="' .
            esc_attr($this->getSeo301()) .
            '" id="' .
            $field_name .
            '" name="' .
            $field_name .
            '"/> hours
            <p class="description">' .
            __(
                'After you physically move a file, a 302 redirect (temporary forwarding) is set up. After a specified time (in hours) after the last update of the file location, a 301 redirect (permanent forwarding) is set up. This two-step process is necessary to avoid malfunctioning of caching plugins or CDN services.',
                RPM_TD
            ) .
            '<br />
            ' .
            __('We recommend a time between the two redirects of at least 48 hours to avoid conflicts.', RPM_TD) .
            '</p>';
    }
    // self-explaining
    public function html_url_max_length() {
        if (current_user_can(\DevOwl\RealPhysicalMedia\misc\WpPosts::NEEDED_PERMISSION)) {
            $lengths = \DevOwl\RealPhysicalMedia\misc\WpPosts::getInstance()->getLengths();
            echo '<p><code>post_name</code> <input ' .
                self::getDemoDisabledAttr() .
                ' type="text" value="' .
                $lengths['post_name'] .
                '" id="rpm_advanced_post_name" /> (' .
                __('WordPress default', RPM_TD) .
                ': <code>200</code>)</p>
                <p><code>guid</code> <input ' .
                self::getDemoDisabledAttr() .
                ' type="text" value="' .
                $lengths['guid'] .
                '" id="rpm_advanced_guid" /> (' .
                __('WordPress default', RPM_TD) .
                ': <code>255</code>)</p>
                <p><a ' .
                self::getDemoDisabledAttr() .
                ' class="rml-rest-button button" data-url="schema/filelength" data-urlnamespace="real-physical-media/v1" data-method="POST">' .
                __('Apply new length', RPM_TD) .
                '</a></p>
                <p class="description">' .
                __(
                    'This method allows you to modify the standard WordPress database table wp_posts to allow more than 255 characters as the full URL path (guid) and the length of the file name (post_name). This is an advanced option and you should create a backup before you apply the change. These changes are WordPress core update safe. Please only use them if you are aware of the technical implications of such a change and possible side effects for other WordPress plugins and themes.',
                    RPM_TD
                ) .
                '</p>
                <p class="description">' .
                __(
                    'When do I need this? This is necessary, for example, if you receive the error message <strong>Could not add post to database.</strong> when uploading a new file.',
                    RPM_TD
                ) .
                '</p>' .
                $this->getDemoRestrictionText(\true);
        } else {
            echo __('This option is only allowed for administrators..', RPM_TD);
        }
    }
    /**
     * Get the cronjob token for a specific blog or current blog.
     *
     * @param int $blogId
     */
    public function getCronJobToken($blogId = null) {
        $multisite = \function_exists('switch_to_blog');
        if ($multisite && $blogId !== null) {
            switch_to_blog($blogId);
        }
        $optionName = RPM_OPT_PREFIX . '_cjt';
        $token = get_option($optionName);
        if ($token === \false) {
            $token = \md5(\uniqid());
            update_option($optionName, $token);
        }
        if ($multisite && $blogId !== null) {
            restore_current_blog();
        }
        return $token;
    }
    // self-explaining
    public function getPrefix() {
        return get_option(RPM_OPT_PREFIX . self::OPTION_NAME_PREFIX, '');
    }
    // self-explaining
    public function isToLowerCase() {
        return \boolval(get_option(RPM_OPT_PREFIX . self::OPTION_NAME_TO_LOWER, self::OPTION_DEFAULT_TO_LOWER));
    }
    // self-explaining
    public function isSpecialCharacters() {
        return \boolval(
            get_option(RPM_OPT_PREFIX . self::OPTION_NAME_SPECIAL_CHARS, self::OPTION_DEFAULT_SPECIAL_CHARS)
        );
    }
    // self-explaining
    public function getMaxExecTime() {
        $server_max = (int) \ini_get('max_execution_time');
        $max = \intval(
            get_option(RPM_OPT_PREFIX . self::OPTION_NAME_MAX_EXEC_TIME, self::OPTION_DEFAULT_MAX_EXEC_TIME)
        );
        if ($max > 0) {
            if ($server_max === 0) {
                return $max;
            } else {
                return $max <= $server_max ? $max : self::OPTION_DEFAULT_MAX_EXEC_TIME;
            }
        } else {
            return self::OPTION_DEFAULT_MAX_EXEC_TIME;
        }
    }
    // self-explaining
    public function getCountdownProcessing() {
        $countdown = (int) get_option(
            RPM_OPT_PREFIX . self::OPTION_NAME_COUNTDOWN_PROCESSING,
            self::OPTION_DEFAULT_COUNTDOWN_PROCESSING
        );
        return $countdown > 0 ? $countdown : self::OPTION_DEFAULT_COUNTDOWN_PROCESSING;
    }
    // self-explaining
    public function getCountdownPause() {
        $countdown = (int) get_option(
            RPM_OPT_PREFIX . self::OPTION_NAME_COUNTDOWN_PAUSE,
            self::OPTION_DEFAULT_COUNTDOWN_PAUSE
        );
        return $countdown > 0 ? $countdown : self::OPTION_DEFAULT_COUNTDOWN_PAUSE;
    }
    // self-explaining
    public function getSeo301() {
        $countdown = (int) get_option(RPM_OPT_PREFIX . self::OPTION_NAME_SEO_301, self::OPTION_DEFAULT_SEO_301);
        return $countdown > 0 ? $countdown : self::OPTION_DEFAULT_SEO_301;
    }
    // self-explaining
    public function isCleanupCreateEnabled() {
        return get_option(RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_CREATE, self::OPTION_DEFAULT_CLEAN_CREATE) > 0;
    }
    // self-explaining
    public function isCleanupDeleteEnabled() {
        return get_option(RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_DELETE, self::OPTION_DEFAULT_CLEAN_DELETE) > 0;
    }
    // self-explaining
    public function isCleanupMoveEnabled() {
        return get_option(RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_MOVE, self::OPTION_DEFAULT_CLEAN_MOVE) > 0;
    }
    // self-explaining
    public function isCleanupVirtualMoveEnabled() {
        return get_option(
            RPM_OPT_PREFIX . self::OPTION_NAME_CLEAN_VIRTUAL_MOVE,
            self::OPTION_DEFAULT_CLEAN_VIRTUAL_MOVE
        ) > 0;
    }
    /**
     * The prefix / strtolower option is updated. If automatic queueing is active then add all
     * files to the queue.
     */
    public function automatic_queueing_add() {
        if (\DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->canAutomaticQueueing()) {
            \DevOwl\RealPhysicalMedia\queue\Queue::getInstance()->addPotentialFiles();
        }
    }
    /**
     * Get singleton core class.
     *
     * @return Options
     */
    public static function getInstance() {
        return !isset(self::$me) ? (self::$me = new \DevOwl\RealPhysicalMedia\configuration\Options()) : self::$me;
    }
    /**
     * Checks if the current running WordPress instance is configured as sandbox
     * because then some configurations are not allowed to change.
     */
    public static function isDemoEnv() {
        return \defined('MATTHIASWEB_DEMO') && \constant('MATTHIASWEB_DEMO');
    }
    // self-explaining
    public static function getDemoRestrictionText($p = \false) {
        $text = '<i style="color:darkred;">' . __('This option cannot be changed in the test drive.', RPM_TD) . '</i>';
        return self::isDemoEnv() ? ($p ? '<p class="description">' . $text . '</p>' : $text) : '';
    }
    // self-explaining
    public static function getDemoDisabledAttr() {
        return self::isDemoEnv() ? ' disabled="disabled" ' : '';
    }
}
