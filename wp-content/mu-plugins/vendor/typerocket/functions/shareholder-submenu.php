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
      add_action("load-$hook", 'shareholder_table_add_options');

      function shareholder_table_add_options() {

            $args_page = array(
                  'label' => 'تعداد موردها در هر برگه:',
                  'default' => 20,
                  'option' => 'employees_per_page'
            );

            // $args_columns = array(
            //       'label' => 'ستون‌ها',
            //       'max' => 10,
            //       'default' => 2,
            //       // 'option' => 'employees_per_page'
            // );
            add_screen_option('per_page', $args_page);
            // add_screen_option('layout_columns', $args_columns);

            // $empTable = new Employees_List_Table();

      }
      
}
add_action('admin_menu', 'shareholder_wc_register_shareholder_submenu_page');

function shareholder_table_submenu_page_callback() {

      include plugin_dir_path(__FILE__) . 'shareholder-table/shareholder-table.php';
      shareholder_employees_list_init();

      $user = wp_get_current_user();
      switch ( $user->roles[0] ) {
            case 'administrator':
                  $user_amount = 'product_shareholder_admin_amount';
                  break;
                  
            case 'photographer':
                  $user_amount = 'product_shareholder_photographer_amount';
                  break;

            case 'graphicer':
                  $user_amount = 'product_shareholder_graphicer_amount';
                  break;

            default:
                  $user_amount = '';
                  break;
      }

      $line_items = tr_query()->table('se7en_wc_order_product_lookup');
      $line_items = $line_items->findAll()->orderBy('order_item_id', 'DESC')->groupBy(['variation_id','product_id','order_id'])->get();
      foreach( $line_items as $key => $value ) {
            echo 'شناسه: ' . $value->order_item_id . ' | ';

            echo 'شناسه محصول: ' . $value->product_id . ' | ';
            if( $value->variation_id == 0 ) {
                  $product = tr_query()->table('se7en_posts')->findById($value->product_id)->select('ID', 'post_title')->get();
            } else {
                  $product = tr_query()->table('se7en_posts')->findById($value->variation_id)->select('ID', 'post_title')->get();
            }
            echo 'نام محصول: ' . $product['post_title'] . ' | ';
            echo 'شناسه متغیر: ' . $value->variation_id . ' | ';

            echo 'شناسه خریدار: ' . $value->customer_id . ' | ';
            $customer = tr_query()->table('se7en_users')->findById($value->customer_id)->select('display_name')->get();

            $where = [
                  [
                        'column' => 'meta_key',
                        'operator' => '=',
                        'value' => $user_amount
                  ],
                  // 'AND',
                  // [
                  //       'column' => 'meta_value',
                  //       'operator' => '=',
                  //       'value' => get_current_user_id()
                  // ]
            ];
            $shareholder = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($value->product_id)->where($where)->select('meta_value')->get();
            if( $shareholder['meta_value'] ) {
                  echo 'درصد کاربر: ' . $shareholder['meta_value'] . ' | ';
                  echo 'سهم کاربر: ' . ($value->product_gross_revenue * $shareholder['meta_value']) / 100 . ' | ';
            }
            echo 'شناسه کاربر: ' . $user->ID . ' | ';
            echo 'نام کاربر: ' . $user->display_name . ' | ' . $user->user_login . ' | ';

            echo 'شناسه سفارش: ' . $value->order_id . ' | ';
            echo 'مبلغ ناخالص: ' . $value->product_net_revenue . ' | ';
            echo 'مبلغ خالص ' . $value->product_gross_revenue . ' | ';
            echo 'تاریخ سفارش ' . $value->date_created . ' | ';

            echo "<hr>";
      }

}