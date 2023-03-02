<?php
defined('ABSPATH') or die('No script kiddies please!'); // Avoid direct file request

if (!function_exists('rpm_skip_rml_admin_notice')) {
    /**
     * Show an admin notice to administrators when the minimum RML version
     * could not be reached. The error message is only in english available.
     */
    function rpm_skip_rml_admin_notice() {
        if (current_user_can('install_plugins')) {
            $data = get_plugin_data(RPM_FILE, true, false);
            echo '<div class=\'notice notice-error\'>
			    <p>The plugin <strong>' .
                $data['Name'] .
                '</strong> (Add-On) could not be initialized because <a href="https://devowl.io/wordpress-real-media-library/" target="_blank"><b>Real Media Library</b></a> is not active (maybe not installed neither) or the version of Real Media Library is < ' .
                RPM_MIN_RML .
                ' (please update).</p>
			</div>';
        }
    }
}
add_action('admin_notices', 'rpm_skip_rml_admin_notice');
