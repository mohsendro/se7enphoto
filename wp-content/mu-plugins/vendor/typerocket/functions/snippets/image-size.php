<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Register new image sizes
add_image_size( 'gallery-cover-size', 716, 442,false);

// Make image size selectable
add_filter( 'image_size_names_choose', 'gallery_cover_sizes' );

function gallery_cover_sizes( $sizes ) {

    return array_merge( $sizes, array(

        'gallery-cover-size' => __( 'سایز کاور گالری' ),
        
    ) );

}
    
    