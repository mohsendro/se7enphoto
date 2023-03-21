<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Single product hooks
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta' );

function woocommerce_template_single_gallery() {

    $gallery_id = get_post_meta( get_the_ID(), 'product_gallery', true );

    if( $gallery_id ) { 
        
        $gallery = get_post( $gallery_id );
        $post_title = $gallery->post_title;
        $post_name = get_bloginfo('url') . '/gallery/' . $gallery->post_title;
        echo "
            <div class='category-gallery'>گالری: <strong><a href='" . $post_name . "' target='_blank'>" . $post_title . "</a></strong></div>
        ";
    }
}
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_gallery', 6);