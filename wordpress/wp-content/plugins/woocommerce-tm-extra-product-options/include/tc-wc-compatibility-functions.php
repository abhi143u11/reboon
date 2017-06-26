<?php
// Direct access security
if (!defined('TM_EPO_PLUGIN_SECURITY')){
	die();
}

if (!function_exists('tc_get_price_excluding_tax')){
	function tc_get_price_excluding_tax($product,$args=array()){
		if (function_exists('wc_get_price_excluding_tax')){
			if (empty($args['price'])){
				$args = wp_parse_args( $args, array(
					'qty'   => '',
					'price' => '',
				) );
				$qty   = (int) $args['qty'] ? $args['qty'] : 1;
				return apply_filters( 'woocommerce_get_price_excluding_tax', 0, $qty, $product );
			}
			return wc_get_price_excluding_tax( $product, $args );
		}
		$args = wp_parse_args( $args, array(
			'qty'   => '',
			'price' => '',
		) );
		$price = $args['price'] ? $args['price'] : 0;
		$qty   = $args['qty'] ? $args['qty'] : 1;
		return $product->get_price_excluding_tax($qty,$price);
	}
}

if (!function_exists('tc_get_price_including_tax')){
	function tc_get_price_including_tax($product,$args=array()){
		if (function_exists('wc_get_price_including_tax')){
			if (empty($args['price'])){
				$args = wp_parse_args( $args, array(
					'qty'   => '',
					'price' => '',
				) );
				$qty   = (int) $args['qty'] ? $args['qty'] : 1;
				return apply_filters( 'woocommerce_get_price_including_tax', 0, $qty, $product );
			}
			return wc_get_price_including_tax( $product, $args );
		}
		$args = wp_parse_args( $args, array(
			'qty'   => '',
			'price' => '',
		) );
		$price = $args['price'] ? $args['price'] : 0;
		$qty   = $args['qty'] ? $args['qty'] : 1;
		return $product->get_price_including_tax($qty,$price);
	}
}

if (!function_exists('tc_get_id')){
	function tc_get_id($product){
		if (is_callable( array($product, 'get_id')) && is_callable( array($product, 'get_parent_id')) ){
			if ($product->get_type()=="variation" ){
				return $product->get_parent_id();
			}
			return $product->get_id();
		}
		return $product->id;
	}
}

if (!function_exists('tc_get_tax_class')){
	function tc_get_tax_class($product){
		if (is_callable( array($product, 'get_tax_class'))){
			return $product->get_tax_class();
		}
		return $product->tax_class;
	}
}

if (!function_exists('tc_get_woocommerce_currency')){
	function tc_get_woocommerce_currency(){
		$currency = get_woocommerce_currency();
		if (class_exists('WooCommerce_All_in_One_Currency_Converter_Main')){
			global $woocommerce_all_in_one_currency_converter;
			$currency = $woocommerce_all_in_one_currency_converter->settings->session_currency;
		}
		return $currency;

	}
}
if (!function_exists('tc_get_product_type')){
	function tc_get_product_type($product=NULL){
		if (is_object($product)){
			if (is_callable( array($product, 'get_type'))){
				return $product->get_type();
			}else{
				return $product->product_type;
			}			
		}
		return false;
	}
}

if (!function_exists('tc_get_post_meta')){
	function tc_get_post_meta($post_id, $meta_key = '', $single = false){
		if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
			return get_post_meta( $post_id, $meta_key, $single );
		} else {
			if ( is_numeric( $post_id ) ) {
				$product = wc_get_product( $post_id );
				if ( is_object( $product ) ) {
					return $product->get_meta( $meta_key, true );
				}else{
					return get_post_meta( $post_id, $meta_key, $single );
				}
				
			}			
		}

		return false;
	}
}

if (!function_exists('tc_update_post_meta')){
	function tc_update_post_meta( $post_id, $meta_key, $meta_value, $prev_value = '' ) {

		if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
			return update_post_meta( $post_id, $meta_key, $meta_value, $prev_value );
		} else {
			if ( is_numeric( $post_id ) ) {
				$product = wc_get_product( $post_id );
				if ( is_object( $product ) ) {
					$product->update_meta_data( $meta_key, $meta_value );
					$product->save_meta_data();
				}else{
					return update_post_meta( $post_id, $meta_key, $meta_value, $prev_value );
				}
			}
		}

		return false;
		
	}
}

if (!function_exists('tc_delete_post_meta')){
	function tc_delete_post_meta( $post_id, $meta_key, $meta_value = '' ) {

		if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
			return delete_post_meta( $post_id, $meta_key, $meta_value );
		} else {
			if ( is_numeric( $post_id ) ) {
				$product = wc_get_product( $post_id );
				if ( is_object( $product ) ) {
					$product->delete_meta_data( $meta_key );
					$product->save_meta_data();
					return true;
				}else{
					return delete_post_meta( $post_id, $meta_key, $meta_value );
				}
			}
		}

		return false;
		
	}
}

if (!function_exists('tc_add_post_meta')){
	function tc_add_post_meta( $post_id, $meta_key, $meta_value, $unique = false ) {

		if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
			return add_post_meta( $post_id, $meta_key, $meta_value, $unique );
		} else {
			if ( is_numeric( $post_id ) ) {
				$product = wc_get_product( $post_id );
				if ( is_object( $product ) ) {
					$product->add_meta_data( $meta_key, $meta_value, $unique );
					$product->save_meta_data();
					return true;
				}else{
					return add_post_meta( $post_id, $meta_key, $meta_value, $unique );
				}
			}
		}

		return false;
		
	}
}
?>