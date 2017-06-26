<?php

add_action('wp_enqueue_scripts', 'porto_child_css', 1001);
 
// Load CSS
function porto_child_css() {
    // porto child theme styles
    wp_deregister_style( 'styles-child' );
    wp_register_style( 'styles-child', get_stylesheet_directory_uri() . '/style.css' );
    wp_enqueue_style( 'styles-child' );

    if (is_rtl()) {
        wp_deregister_style( 'styles-child-rtl' );
        wp_register_style( 'styles-child-rtl', get_stylesheet_directory_uri() . '/style_rtl.css' );
        wp_enqueue_style( 'styles-child-rtl' );
    }
}


//Display Fields
add_action( 'woocommerce_product_after_variable_attributes', 'variable_fields', 10, 3 );
//JS to add fields for new variations
add_action( 'woocommerce_product_after_variable_attributes_js', 'variable_fields_js' );
//Save variation fields
add_action( 'woocommerce_save_product_variation', 'save_variable_fields', 10, 1 );

/**
 * Create new fields for variations
 *
*/
function variable_fields( $loop, $variation_data, $variation ) {
?>
	<tr>
		<td>
			<?php
			// Text Field
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_ean['.$loop.']', 
					'label'       => __( 'EAN: ', 'woocommerce' ), 
					'placeholder' => 'Enter the EAN here',
					'desc_tip'    => 'false',
					'description' => __( 'Enter the EAN here', 'woocommerce' ),
					'value'       => get_post_meta($variation->ID, '_ean', true)
				)
			);
			?>
		</td>
	</tr>
        <tr>
		<td>
			<?php
			// Text Field
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_vaterartikel['.$loop.']', 
					'label'       => __( 'Vater Artikel: ', 'woocommerce' ), 
					'placeholder' => 'Enter the Vater Artikel here',
					'desc_tip'    => 'false',
					'description' => __( 'Enter the Vater Artikel here', 'woocommerce' ),
					'value'       => get_post_meta($variation->ID, '_vaterartikel', true)
				)
			);
			?>
		</td>
	</tr>
        <tr>
		<td>
			<?php
			// Text Field
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_parentSKU['.$loop.']', 
					'label'       => __( 'Parent SKU: ', 'woocommerce' ), 
					'placeholder' => 'Enter the Parent SKU here',
					'desc_tip'    => 'false',
					'description' => __( 'Enter the Parent SKU here', 'woocommerce' ),
					'value'       => get_post_meta($variation->ID, '_parentSKU', true)
				)
			);
			?>
		</td>
	</tr>
	
	       
	
<?php
}
/**
 * Create new fields for new variations
 *
*/
function variable_fields_js() {
?>
	<tr>
		<td>
			<?php
			// Text Field
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_ean[ + loop + ]', 
					'label'       => __( 'EAN: ', 'woocommerce' ), 
					'placeholder' => 'Enter the EAN here',
					'desc_tip'    => 'false',
					'description' => __( 'Enter the value here.', 'woocommerce' ),
					'value'       => ''
				)
			);
			?>
		</td>
	</tr>
        <tr>
		<td>
			<?php
			// Text Field
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_vaterartikel[ + loop + ]', 
					'label'       => __( 'Vater Artikel: ', 'woocommerce' ), 
					'placeholder' => 'Enter the Vater Artikel here',
					'desc_tip'    => 'false',
					'description' => __( 'Enter the value here.', 'woocommerce' ),
					'value'       => ''
				)
			);
			?>
		</td>
	</tr>
        <tr>
		<td>
			<?php
			// Text Field
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_parentSKU[ + loop + ]', 
					'label'       => __( 'Parent SKU: ', 'woocommerce' ), 
					'placeholder' => 'Enter the Parent SKU here',
					'desc_tip'    => 'false',
					'description' => __( 'Enter the value here.', 'woocommerce' ),
					'value'       => ''
				)
			);
			?>
		</td>
	</tr>
		
	
<?php
}
/**
 * Save new fields for variations
 *
*/

function save_variable_fields( $post_id ) {
	if (isset( $_POST['variable_sku'] ) ) :
		$variable_sku          = $_POST['variable_sku'];
		$variable_post_id      = $_POST['variable_post_id'];
		
		// Text Field
		$_ean = $_POST['_ean'];
		for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :
			$variation_id = (int) $variable_post_id[$i];
			if ( isset( $_ean[$i] ) ) {
				update_post_meta( $variation_id, '_ean', stripslashes( $_ean[$i] ) );
			}
		endfor;

                // Text Field
		$_vaterartikel = $_POST['_vaterartikel'];
		for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :
			$variation_id = (int) $variable_post_id[$i];
			if ( isset( $_vaterartikel[$i] ) ) {
				update_post_meta( $variation_id, '_vaterartikel', stripslashes( $_vaterartikel[$i] ) );
			}
		endfor;

                // Text Field
		$_parentSKU = $_POST['_parentSKU'];
		for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :
			$variation_id = (int) $variable_post_id[$i];
			if ( isset( $_parentSKU[$i] ) ) {
				update_post_meta( $variation_id, '_parentSKU', stripslashes( $_parentSKU[$i] ) );
			}
		endfor;
				
	endif;
}