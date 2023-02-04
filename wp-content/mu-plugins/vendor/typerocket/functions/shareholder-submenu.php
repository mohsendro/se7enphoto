<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

function shareholder_wc_register_shareholder_submenu_page() {

	$hook = add_submenu_page(
		'woocommerce',
		'سهامدران',
		'سهامداران',
		'manage_options',
		'wc-shareholder',
		'shareholder_table_submenu_page_callback',
        10
      );

      // screen option
      function shareholder_table_add_options() {

            $args_page = array(
                  'label' => 'تعداد موردها در هر برگه:',
                  'default' => 20,
                  'option' => 'shareholder_per_page'
            );

            add_screen_option('per_page', $args_page);

      }
      add_action("load-$hook", 'shareholder_table_add_options');
      
}
add_action('admin_menu', 'shareholder_wc_register_shareholder_submenu_page');

// get saved screen meta value
function shareholder_table_set_option($status, $option, $value) {

      return $value;

}
add_filter('set-screen-option', 'shareholder_table_set_option', 10, 3);

function shareholder_table_submenu_page_callback() {

      include plugin_dir_path(__FILE__) . 'shareholder-table/shareholder-table.php';
      shareholder_employees_list_init();

}