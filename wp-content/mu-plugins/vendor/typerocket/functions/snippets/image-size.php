<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Register new image sizes
add_image_size( 'gallery_cover_size', 716, 442, false);
add_image_size( '1000_with_logo_size', 1000, 1000, false);
add_image_size( '1000_without_logo_size', 1000, 1000, false);
add_image_size( '2000_without_logo_size', 2000, 2000, false);


// Make image size selectable
function image_size_names_choose_callback( $sizes ) {

    return array_merge( $sizes, array(

        'gallery_cover_size'     => 'سایز کاور گالری',
        '1000_with_logo_size'    => 'سایز 1000 با آرم کوچک',
        '1000_without_logo_size' => 'سایز 1000 بدون آرم',
        '2000_without_logo_size' => 'سایز 2000 مناسب چاپ',
        
    ) );

}
add_filter( 'image_size_names_choose', 'image_size_names_choose_callback' );    