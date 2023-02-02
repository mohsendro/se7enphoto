<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

function product_table_ajax_handle_function() {

    check_ajax_referer( 'product_table_form_nonce', 'submitted_nonce' );  // This function will die if submitted_nonce is not correct.

    //$post_type = sanitize_text_field($_POST['post_type']);  

    $response = array(
        'success'   => 'درخواست با موفقیت ارسال شد',
    );

    wp_send_json_success( $response, 200 );
    
    wp_send_json_error();
    wp_die();

}
// add_action( 'wp_ajax_nopriv_product_table_ajax_handle', 'product_table_ajax_handle_function' );
add_action( 'wp_ajax_product_table_ajax_handle', 'product_table_ajax_handle_function' );  // For logged in users.