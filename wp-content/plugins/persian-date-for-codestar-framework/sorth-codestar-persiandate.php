<?php
/**
* Plugin Name: Persian date for codestar framework
* Description: Adding persian(jalali o r shamsi) date field to codestar framework
* Version: 1.1
* Author: Saeed Taheri
* Author URI: https://#
* Requires at least: 5.5
* Requires PHP: 7.0
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
if(!defined("ABSPATH")) { exit(); } // no direct access
# check if codestar framework exists
if( class_exists( 'CSF' ) ) {
  require_once(plugin_dir_path(__FILE__).'persiandate.php');
}else{
  add_action('admin_notices', function(){
    echo '<div class="notice notice-warning is-dismissible">
             <p>Persian date for codestar needs "codestar" framework</p>
         </div>';
  });
}
