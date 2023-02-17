<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Tab: shareholder - سهامداران

function shareholder_page_tabs( $current = 'order' ) {

    $tabs = array(
        'order'     => 'سفارشات', 
        'log'       => 'گزارشات', 
        'checkout'  => 'پرداخت‌ها', 
    );

    $html = '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ) {
        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
        $query_args = [
            'shareholder_id' => $_GET['shareholder_id'],
            'tab'            => $tab
        ];
        $html .= '<a class="nav-tab ' . $class . '" href="' . add_query_arg($query_args) . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;

}
shareholder_page_tabs( $tab );

// Tabs
$tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'order';
if( $tab == 'log' ) {

    include plugin_dir_path(__FILE__) . 'log.php';

} elseif( $tab == 'checkout' ) {

    include TYPEROCKET_DIR_PATH . 'functions/table/shareholder-checkout.php';

} else {

    include TYPEROCKET_DIR_PATH . 'functions/table/shareholder-order.php';

}