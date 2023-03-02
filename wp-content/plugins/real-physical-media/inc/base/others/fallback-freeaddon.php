<?php
defined('ABSPATH') or die('No script kiddies please!'); // Avoid direct file request

if (!function_exists('rpm_skip_free_addon_admin_notice')) {
    /**
     * Show an admin notice to administrators when the Physical Custom Upload Dir
     * plugin is activated.
     */
    function rpm_skip_free_addon_admin_notice() {
        if (current_user_can('install_plugins')) {
            $data = get_plugin_data(RPM_FILE, true, false);
            echo '<div class=\'notice notice-error\'>
			    <p>The plugin <strong>' .
                $data['Name'] .
                '</strong> (Add-On) could not be initialized because you have still activated the <strong>Physical Custom Upload Dir for Real Media Library</strong> plugin activated..</p>
			</div>';
        }
    }
}
add_action('admin_notices', 'rpm_skip_free_addon_admin_notice');
