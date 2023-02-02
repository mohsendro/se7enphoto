<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

require_once __DIR__ .'/vendor/autoload.php';
// require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
include plugin_dir_path(__FILE__) . 'vendor/plugins/query-monitor/query-monitor.php';
// include plugin_dir_path(__FILE__) . 'vendor/plugins/another-show-hooks/another-show-hooks.php';
// include plugin_dir_path(__FILE__) . 'vendor/plugins/woocommerce/woocommerce.php';
include plugin_dir_path(__FILE__) . 'vendor/plugins/multiple-roles/multiple-roles.php';

// use Acme\A as A;
// $a = new A;
