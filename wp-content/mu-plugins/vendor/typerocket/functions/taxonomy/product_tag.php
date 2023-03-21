<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Taxonomy: product_tag - دسته‌بندی محصولات

// general
function product_tag_unregister_taxonomy() {

    unregister_taxonomy( 'product_tag');

}
add_action( 'init', 'product_tag_unregister_taxonomy' );