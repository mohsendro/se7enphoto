<?php
	/*
	Plugin Name: Digital Checkout for WooCommerce
	Plugin URI: https://wordpress.org/plugins/wc-digital-checkout/
	Description: WooCommerce Fast checkout on digital products
	Version: 3
	Author: MihanWP
	Author URI: https://mihanwp.com
	License: GPLv2
	*/



	add_filter( 'woocommerce_checkout_fields' , 'mihanwp_custom_override_checkout_fields' );
	function mihanwp_custom_override_checkout_fields( $fields ) {
	 global $woocommerce;
		$hasPhysicalProduct = false;
		if ( ! empty( $woocommerce->cart->cart_contents ) ) {
			$cart = $woocommerce->cart->get_cart();
			foreach ( $cart as $key => $values ) {

			  $_product = get_product( $values['variation_id'] ? $values['variation_id'] : $values['product_id'] );

			  if ( ! empty( $_product ) && $_product->exists() && $values['quantity'] > 0 ) {
				  if ($_product->virtual == 'no') {
					$hasPhysicalProduct = true;
				  }
			  }
			}
		}
		if ($hasPhysicalProduct == false)
		{

			unset($fields['billing']['billing_address_1']);
			unset($fields['billing']['billing_address_2']);
			unset($fields['billing']['billing_company']);
			unset($fields['billing']['billing_city']);
			unset($fields['billing']['billing_postcode']);
			unset($fields['billing']['billing_country']);
			unset($fields['billing']['billing_state']);
			unset($fields['billing']['billing_phone']);
			wp_enqueue_style( 'myCSS', plugins_url( 'style.css', __FILE__ ) );
		}
		return $fields;
	}
	function mihanwp_custom_override_billing_fields( $fields ) {
		unset($fields['billing_state']);
		unset($fields['billing_country']);
		unset($fields['billing_address_1']);
		unset($fields['billing_address_2']);
		unset($fields['billing_postcode']);
		unset($fields['billing_city']);
		$fields['billing_phone']['required'] = false;
		return $fields;
	}
	function mihanwp_custom_override_shipping_fields( $fields ) {
		unset($fields['shipping_state']);
		unset($fields['shipping_country']);
		unset($fields['shipping_company']);
		unset($fields['shipping_address_1']);
		unset($fields['shipping_address_2']);
		unset($fields['shipping_postcode']);
		unset($fields['shipping_city']);
		return $fields;
	}
	function mihanwp_custom_override_account_page_addresses($fields) {
		unset($fields['address_1']);
		unset($fields['address_2']);
		unset($fields['city']);
		unset($fields['state']);
		unset($fields['postcode']);
		unset($fields['country']);
		return $fields;
	}
	function mihanwp_custom_override_account_page_address_title() {
		return 'My Informations';
	}
	function mihanwp_custom_override_account_page_address_description() {
		return 'Its your profile informations.';
	}
