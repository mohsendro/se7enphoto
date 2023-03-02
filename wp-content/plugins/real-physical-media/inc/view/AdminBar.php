<?php

namespace DevOwl\RealPhysicalMedia\view;

use DevOwl\RealPhysicalMedia\base\UtilsProvider;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Handle the admin bar.
 */
class AdminBar {
    use UtilsProvider;
    /**
     * Singleton instance.
     */
    private static $me;
    /**
     * Add the HDD icon to the admin bar.
     */
    public function admin_bar_menu() {
        global $wp_admin_bar;
        if (!is_admin_bar_showing() || !current_user_can('edit_posts')) {
            return;
        }
        $wp_admin_bar->add_menu(['id' => 'rpm-files', 'title' => '<div id="admin-bar-rpm"></div>']);
    }
    /**
     * Set and/or get the value if the first-time move notice is dismissed.
     *
     * @param boolean $set
     */
    public function isFirstTimeMoveHintDismissed($set = null) {
        $optionName = RPM_OPT_PREFIX . '_firstTimeMoveHint';
        $value = '1';
        add_option($optionName, 0);
        if ($set !== null) {
            if ($set) {
                update_option($optionName, $value);
            } else {
                delete_option($optionName);
            }
        }
        return get_option($optionName) === $value;
    }
    /**
     * Set and/or get the value if the first-time queue notice is dismissed.
     *
     * @param boolean $set
     */
    public function isFirstTimeQueueNoticeDismissed($set = null) {
        $optionName = RPM_OPT_PREFIX . '_firstTimeQueueNotice';
        $value = '1';
        add_option($optionName, 0);
        if ($set !== null) {
            if ($set) {
                update_option($optionName, $value);
            } else {
                delete_option($optionName);
            }
        }
        return get_option($optionName) === $value;
    }
    /**
     * Get singleton AdminBar class.
     *
     * @return AdminBar
     */
    public static function getInstance() {
        return !isset(self::$me) ? (self::$me = new \DevOwl\RealPhysicalMedia\view\AdminBar()) : self::$me;
    }
}
