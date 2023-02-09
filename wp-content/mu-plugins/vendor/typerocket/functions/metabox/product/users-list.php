<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// require plugin_dir_path(__FILE__) . '/product-handle.php';

function users_list( $role = 'administrator' ) {
    
    // WP_User_Query arguments
    $args = array(
        'role'    => $role,
        'fields'  => array( 'id', 'user_login' ),
        'number'         => '100',
    );

    // The User Query
    $user_query = new WP_User_Query( $args ); //var_dump($user_query);

    if ( ! empty( $user_query->results ) ) {
        
        foreach ( $user_query->results as $user ) {
            
            if( get_user_meta( $user->id, 'user_shareholder_stock_amount' ) ) {
                $useramount = get_user_meta( $user->id, 'user_shareholder_stock_amount' );
                $useramount = ' (پیشفرض: ' . $useramount[0] . '%)';
            }
            // else {
            //     $useramount = ' (تنظیم نشده است) ';
            // }

            $users[$user->id] .= $user->user_login . ' - آیدی: ' . $user->id . $useramount;
            
            // return array(
            //     $user->id => $user->user_login . ' - آیدی: ' . $user->id . $useramount,
            // );
        }
        return $users;

    }
}

$product_meta_amount_id = $_GET['post'];
$product_shareholder_status             = get_post_meta( $product_meta_amount_id, 'product_shareholder_status' );
$product_shareholder_admin_user         = get_post_meta( $product_meta_amount_id, 'product_shareholder_admin_user' );
$product_shareholder_photographer_user  = get_post_meta( $product_meta_amount_id, 'product_shareholder_photographer_user' );
$product_shareholder_graphicer_user     = get_post_meta( $product_meta_amount_id, 'product_shareholder_graphicer_user' );
$product_shareholder_admin_amount              = get_post_meta( $product_meta_amount_id, 'product_shareholder_admin_amount' );
$product_shareholder_photographer_amount       = get_post_meta( $product_meta_amount_id, 'product_shareholder_photographer_amount' );
$product_shareholder_graphicer_amount          = get_post_meta( $product_meta_amount_id, 'product_shareholder_graphicer_amount' );

if( $product_shareholder_status[0] == 'true' ) {
    $product_shareholder_status = true;
} else {
    $product_shareholder_status = false;
}

if( $product_shareholder_photographer_amount ) {
    $product_shareholder_photographer_amount = $product_shareholder_photographer_amount[0];
} else {
    $product_shareholder_photographer_amount = 0;
}

if( $product_shareholder_graphicer_amount ) {
    $product_shareholder_graphicer_amount = $product_shareholder_graphicer_amount[0];
} else {
    $product_shareholder_graphicer_amount = 0;
}

if( $product_shareholder_admin_amount ) {
    $product_shareholder_admin_amount = $product_shareholder_admin_amount[0];
} else {
    $product_shareholder_admin_amount = 100 - ( $product_shareholder_photographer_amount + $product_shareholder_graphicer_amount );
}