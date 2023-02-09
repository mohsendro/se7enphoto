<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Sub Menu: wc-shareholder - زیرمنو سهامداران

function register_wc_shareholder_submenu_page() {

	$hook = add_submenu_page(
		'woocommerce',
		'سهامدران',
		'سهامداران',
		'manage_options',
		'wc-shareholder',
		'shareholder_table_submenu_page_callback',
            10
      );
      add_action("load-$hook", 'shareholder_table_add_options');
      
}
add_action('admin_menu', 'register_wc_shareholder_submenu_page');

// screen option
function shareholder_table_add_options() {

      $args_page = array(
            'label' => 'تعداد موردها در هر برگه:',
            'default' => 20,
            'option' => 'shareholder_per_page'
      );

      add_screen_option('per_page', $args_page);

}

// get saved screen meta value
function shareholder_table_set_option($status, $option, $value) {

      return $value;

}
add_filter('set-screen-option', 'shareholder_table_set_option', 10, 3);

function shareholder_table_submenu_page_callback() {

      include plugin_dir_path(__FILE__) . 'shareholder/tab.php';
      include plugin_dir_path(__FILE__) . 'shareholder/checkout.php';
      include plugin_dir_path(__FILE__) . 'shareholder/log.php';
      include TYPEROCKET_DIR_PATH . 'functions/table/shareholder.php';

}


// $settings = ['capability' => 'administrator'];
// $handler = function() {  

     //return 'hi2';  

// };

// $expert = tr_page('forms', 'expert', 'درخواست کارشناسی', $settings, $handler);
// $expert->setHandler(\App\Controllers\PostController::class);
// $expert->mapAction('GET', 'expert');
// $expert->mapAction('POST', 'create_expert');
// $expert->setView($handler);
// $expert->adminBar('forms_expert');
// $expert->setSlug('forms_expert');

// $expert->setParent($forms);
// $expert->setTitle('درخواست کارشناسی');
// $expert->setSubMenuTitle('درخواست کارشناسی'); // If is sub page