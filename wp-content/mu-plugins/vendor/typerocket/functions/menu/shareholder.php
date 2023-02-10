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
		'shareholder_submenu_page_callback',
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

function shareholder_submenu_page_callback() {

      if( $_GET['shareholder_id'] ) {

            $users = tr_query()->table('se7en_users')->findById($_GET['shareholder_id'])->select('ID')->get();
            if( $users ) {
                  include plugin_dir_path(__FILE__) . 'shareholder/tab.php';
            } else {
                  echo 'لطفاً شناسه کاربر دارای سهام فروش مد نظر خود را به درستی انتخاب نمایید...';
            }

      } else {

            include TYPEROCKET_DIR_PATH . 'functions/table/shareholder-user.php';

      }

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