<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Taxonomy: product_cat - دسته‌بندی محصولات

// general
function product_category_unregister_taxonomy() {

    unregister_taxonomy( 'product_cat');

}
add_action( 'init', 'product_category_unregister_taxonomy' );