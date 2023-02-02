<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

if( ! class_exists( 'Bootstrap' ) ) {

	class Bootstrap {

		public function __construct() {

			add_action( 'wp_enqueue_scripts', array($this, 'wpplus_enqueuing_bootstrap_styles') );
			add_action( 'wp_enqueue_scripts', array($this, 'wpplus_enqueuing_bootstrap_scripts') );

		}

		public function wpplus_enqueuing_bootstrap_styles() {

			wp_enqueue_style( 'bootstrap-reboot.min', plugin_dir_url(__FILE__) . 'css/bootstrap-reboot.min.css', false, '5.1.3' );
			wp_enqueue_style( 'bootstrap.rtl.min', plugin_dir_url(__FILE__) . 'css/bootstrap.rtl.min.css', false, '5.1.3' );
			wp_enqueue_style( 'bootstrap-reboot.min' );
			wp_enqueue_style( 'bootstrap.rtl.min' );

		}

		public function wpplus_enqueuing_bootstrap_scripts() {

			wp_enqueue_script( 'bootstrap.bundle.min', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', false, '5.1.3', true );
			wp_enqueue_script( 'bootstrap.bundle.min' );

		}

	}

}
$Bootstrap = new Bootstrap;