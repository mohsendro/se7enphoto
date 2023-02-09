<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Column: line-item - ستون جدول لیست سفارش

function shareholder_order_total_price( $user_amount = 100, $item = null ) {

    $all_total = $item->get_total();
    $share_total = ($all_total * $user_amount) / 100;
    return $share_total;

}

function shareholder_order_itemmeta( $item_id, $item, $product ){

    $status = get_post_meta( $product->id, 'product_shareholder_status' );

    if( $status[0] != 'true' ) {
        echo 'وضعیت سهام: غیرفعال';
        return;
    }

    $admin_user         = get_post_meta( $product->id, 'product_shareholder_admin_user' );
    $photographer_user  = get_post_meta( $product->id, 'product_shareholder_photographer_user' );
    $graphicer_user     = get_post_meta( $product->id, 'product_shareholder_graphicer_user' );
    $admin_amount         = get_post_meta( $product->id, 'product_shareholder_admin_amount' );
    $photographer_amount  = get_post_meta( $product->id, 'product_shareholder_photographer_amount' );
    $graphicer_amount     = get_post_meta( $product->id, 'product_shareholder_graphicer_amount' );
    $admin_info = get_user_by('id', $admin_user[0]);
    $photographer_info = get_user_by('id', $photographer_user[0]);
    $graphicer_info = get_user_by('id', $graphicer_user[0]);

    echo "<hr>";
    echo 'سهم مدیر: ' . "<a href='" . admin_url() . 'user-edit.php?user_id=' . $admin_info->ID . "' tergrt='_blank'>" . $admin_info->user_login . "</a>" . ' (' . $admin_amount[0] . '%) ' . '(مجموع: ' . shareholder_order_total_price($admin_amount[0], $item) . ')';
    echo "<br>";
    echo 'سهم عکاس: ' . "<a href='" . admin_url() . 'user-edit.php?user_id=' . $photographer_info->ID . "' tergrt='_blank'>" . $photographer_info->user_login . "</a>" . ' (' . $photographer_amount[0] . '%) ' . '(مجموع: ' . shareholder_order_total_price($photographer_amount[0], $item) . ')';
    echo "<br>";
    echo 'سهم گرافیست: ' . "<a href='" . admin_url() . 'user-edit.php?user_id=' . $graphicer_info->ID . "' tergrt='_blank'>" . $graphicer_info->user_login . "</a>" . ' (' . $graphicer_amount[0] . '%) ' . '(مجموع: ' . shareholder_order_total_price($graphicer_amount[0], $item) . ')';

}
add_action( 'woocommerce_after_order_itemmeta', 'shareholder_order_itemmeta', 10, 3 );

function wpplus_woocommerce_admin_order_totals_after_total_action( $order_id ) {
    
    $order = wc_get_order( $order_id );

    // Get and Loop Over Order Items
    // $order_data = [];
    foreach ( $order->get_items() as $item_id => $item ) {

        $order_data[$item->get_product_id()] += $item->get_total();

        // echo 'نام محصول : ' . $item->get_name() . '<br>';
        // echo 'شناسه محصول : ' . $item->get_product_id() . '<br>';
        // if ($item->get_variation_id()) {
        //     echo 'نوع محصول : محصول متغیر' . '<br>';
        //     echo 'شناسه محصول متغیر : ' . $item->get_variation_id() . '<br>';
        // } else {
        //     echo 'نوع محصول : محصول ساده' . '<br>';
        // }
        
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
    // var_dump($sums_end);

    echo "<hr>";
    foreach( $sums_end as $user => $amount ) {

        $user_info = get_user_by('id', $user); //var_dump($user_info->roles); echo "<hr>";

        // if( in_array( 'administrator', (array) $user_info->roles ) ) {
        //     echo 'مجموع سهم مدیر' . ' (' . "<a href='" . admin_url() . 'user-edit.php?user_id=' . $user_info->ID . "' tergrt='_blank'>" . $user_info->user_login . "</a>" . '): ' . "<strong>" . $amount . "</strong>";
        //     echo "<br>";
        // }

        // if( in_array( 'photographer', (array) $user_info->roles ) ) {
        //     echo 'مجموع سهم عکاس' . ' (' . "<a href='" . admin_url() . 'user-edit.php?user_id=' . $user_info->ID . "' tergrt='_blank'>" . $user_info->user_login . "</a>" . '): ' . "<strong>" . $amount . "</strong>";
        //     echo "<br>";
        // }

        // if( in_array( 'graphicer', (array) $user_info->roles ) ) {
        //     echo 'مجموع سهم گرافیست' . ' (' . "<a href='" . admin_url() . 'user-edit.php?user_id=' . $user_info->ID . "' tergrt='_blank'>" . $user_info->user_login . "</a>" . '): ' . "<strong>" . $amount . "</strong>";
        //     echo "<br>";
        // }

        echo 'مجموع سهم کاربر' . ' (' . "<a href='" . admin_url() . 'user-edit.php?user_id=' . $user_info->ID . "' tergrt='_blank'>" . $user_info->user_login . "</a>" . '): ' . "<strong>" . $amount . "</strong>";
        echo "<br>";

    }

}
add_action( 'woocommerce_admin_order_totals_after_total', 'wpplus_woocommerce_admin_order_totals_after_total_action' );