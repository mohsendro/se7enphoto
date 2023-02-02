<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * @package   Codestar Framework - WordPress Options Framework
 * @author    Codestar <info@codestarthemes.com>
 * @link      http://codestarframework.com
 * @copyright 2015-2022 Codestar
 *
 *
 * Plugin Name: Codestar Framework
 * Plugin URI: http://codestarframework.com/
 * Author: Codestar
 * Author URI: http://codestarthemes.com/
 * Version: 2.2.6
 * Description: A Simple and Lightweight WordPress Option Framework for Themes and Plugins
 * Text Domain: csf
 * Domain Path: /languages
 *
 */
require_once plugin_dir_path( __FILE__ ) .'classes/setup.class.php';


/* CSF Framework Enqueue Styles && Scripts */
function wpplus_enqueuing_csf_scripts() {
	wp_dequeue_style( 'csf-css' );
	wp_dequeue_style( 'csf-rtl-css' );
	wp_dequeue_script( 'csf-plugins-js' );
	wp_dequeue_script( 'csf-js' );
	wp_enqueue_style( 'csf-css', plugin_dir_url(__FILE__) . 'assets/css/style.min.css', false, '1.0.0' );
    wp_enqueue_style( 'csf-rtl-css', plugin_dir_url(__FILE__) . 'assets/css/style-rtl.min.css', false, '1.0.0' );
	wp_enqueue_script( 'csf-plugins-js', plugin_dir_url(__FILE__) . 'assets/js/plugins.min.js', false, '1.0.0' );
	wp_enqueue_script( 'csf-js', plugin_dir_url(__FILE__) . 'assets/js/main.min.js', false, '1.0.0' );
}
add_action('csf_enqueue', 'wpplus_enqueuing_csf_scripts');
// add_filter( 'csf_welcome_page', '__return_false' );

// include plugin_dir_path( __FILE__ ) . '/samples/admin-options.php';
// include plugin_dir_path( __FILE__ ) . '/samples/comment-options.php';
// include plugin_dir_path( __FILE__ ) . '/samples/customize-options.php';
// include plugin_dir_path( __FILE__ ) . '/samples/metabox-options.php';
// include plugin_dir_path( __FILE__ ) . '/samples/nav-menu-options.php';
// include plugin_dir_path( __FILE__ ) . '/samples/profile-options.php';
// include plugin_dir_path( __FILE__ ) . '/samples/shortcode-options.php';
// include plugin_dir_path( __FILE__ ) . '/samples/taxonomy-options.php';
// include plugin_dir_path( __FILE__ ) . '/samples/widget-options.php';

// Panels
include plugin_dir_path( __FILE__ ) . '/panels/user-profile.php';
include plugin_dir_path( __FILE__ ) . '/panels/product-table.php';

function product_table_enqueue_script() {
	wp_enqueue_script( 'product_table_script_handle', plugin_dir_url(__FILE__) . '/product-table.js', array('jquery') );
    wp_localize_script( 'product_table_script_handle', 'product_table_ajax_localize_obj', array(
                      'ajax_url' => admin_url( 'admin-ajax.php' ),
                      'the_nonce' => wp_create_nonce('product_table_form_nonce') 
	));
}
// add_action( 'admin_enqueue_scripts', 'product_table_enqueue_script' );