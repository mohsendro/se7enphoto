<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Column: order - ستون جدول سفارش

// Add Columns
function columns_shop_order_shareholder($columns) {

    $columns['shareholder_shop_order_data'] = 'اطلاعات مالی';
    return $columns;

}
// add_filter('manage_edit-shop_order_columns', 'columns_shop_order_shareholder');

// Add Data To Columns
function columns_shop_order_shareholder_data($column, $post_id) {

    // if ($column == 'shareholder_shop_order_data') {
    if ($column == 'order_total') {

        $order = wc_get_order( $post_id );

        // Get and Loop Over Order Items
        // $order_data = [];
        foreach ( $order->get_items() as $item_id => $item ) {
    
            $order_data[$item->get_product_id()] += $item->get_total();

        }
    
        foreach( $order_data as $key => $value) {
    
            $status = get_post_meta( $key, 'product_shareholder_status' );
            if( $status[0] == 'true' ) {
    
                $admin_user         = get_post_meta( $key, 'product_shareholder_admin_user' );
                $photographer_user  = get_post_meta( $key, 'product_shareholder_photographer_user' );
                $graphicer_user     = get_post_meta( $key, 'product_shareholder_graphicer_user' );
                $admin_amount         = get_post_meta( $key, 'product_shareholder_admin_amount' );
                $photographer_amount  = get_post_meta( $key, 'product_shareholder_photographer_amount' );
                $graphicer_amount     = get_post_meta( $key, 'product_shareholder_graphicer_amount' );
    
                $order_shareholder_admin[$admin_user[0]] += ($value * $admin_amount[0]) / 100;
                $order_shareholder_photographer[$photographer_user[0]] += ($value * $photographer_amount[0]) / 100;
                $order_shareholder_graphicer[$graphicer_user[0]] += ($value * $graphicer_amount[0]) / 100;
                
            }
            
        }
    
        $sums_first = array();
        foreach (array_keys($order_shareholder_admin + $order_shareholder_photographer ) as $currency) {
            $sums_first[$currency] = (isset($order_shareholder_admin[$currency]) ? $order_shareholder_admin[$currency] : 0) + (isset($order_shareholder_photographer) ? $order_shareholder_photographer[$currency] : 0);
        }
    
        $sums_end = array();
        foreach (array_keys($sums_first + $order_shareholder_graphicer ) as $currency) {
            $sums_end[$currency] = (isset($sums_first[$currency]) ? $sums_first[$currency] : 0) + (isset($order_shareholder_graphicer) ? $order_shareholder_graphicer[$currency] : 0);
        }
    
        foreach( $sums_end as $user => $amount ) {
    
            $user_info = get_user_by('id', $user);
            echo "<a href='" . admin_url() . 'user-edit.php?user_id=' . $user_info->ID . "' tergrt='_blank'>" . $user_info->user_login . "</a> " . $amount;
            echo "<br>";
    
        }
        echo "<hr>";

	}

}
add_action('manage_shop_order_posts_custom_column' , 'columns_shop_order_shareholder_data', 10, 2);