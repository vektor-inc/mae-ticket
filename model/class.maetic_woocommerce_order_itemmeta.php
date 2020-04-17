<?php
require_once( dirname( __FILE__ ) . '/class.maetic_postmeta.php' );

class MaeTick_Woocommerce_Order_Itemmeta extends MaeTick_Postmeta{

    public static function init() {
	    add_filter( 'woocommerce_product_data_tabs', array( __CLASS__, 'maetic_product_tab'), 10, 1 );
	    add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'maetic_product_tab_content'), 10, 0 );
	    add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_maetic_product_options_field'),10, 1 );
    }

    public static function maetic_product_tab($tabs) {
    	// Key should be exactly the same as in the class product_type
	    $tabs['simple'] = array(
	    	'label'	 => __( 'Maetic', 'mae-ticket' ),
		    'target' => 'maetic_product_options',
		    'class'  => ('show_if_maetic_product'),
		    );
	    return $tabs;
    }

	public static function maetic_product_tab_content() {
		// Dont forget to change the id in the div with your target of your product tab
		?><div id='maetic_product_options' class='panel woocommerce_options_panel'><?php
		?><div class='options_group'><?php

		woocommerce_wp_checkbox( array(
			'id' 	=> 'enable_maetic_product',
			'label' => __( 'Enable Maetic Product', 'mae-ticket' ),
		) );

		woocommerce_wp_text_input( array(
			'id'				=> 'maetic_expired_period',
			'label'				=> __( 'Expired Period (Day)', 'mae-ticket' ),
			'desc_tip'			=> 'true',
			'description'		=> __( 'Enter the number of days the virtual ticket is valid for.', 'mae-ticket' ),
			'type' 				=> 'number',
			'custom_attributes'	=> array(
				'min'	=> '1',
				'step'	=> '1',
			),
		) );
		?></div>
		</div><?php
	}

	public static function save_maetic_product_options_field( $post_id ) {
		$enable_maetic_product = isset( $_POST['enable_maetic_product'] ) ? true : false;
		update_post_meta( $post_id, 'enable_maetic_product', $enable_maetic_product );

		if ( isset( $_POST['maetic_expired_period'] ) ) :
			update_post_meta( $post_id, 'maetic_expired_period', intval( $_POST['maetic_expired_period'] ) );
		endif;
	}

	public static function get_expired_date($order_item_id){
		$item = new WC_Order_Item_Product($order_item_id);

		$order_id = $item->get_order_id();
		$ordered_date = MaeTick_Postmeta::get_ordered_date($order_id);

		$product_id   = $item->get_product_id();
		$expired_period = MaeTick_Postmeta::get_expired_period($product_id);
		return $ordered_date + (intval($expired_period)*86400);//期限切れ期間(日)×1日(秒)
	}

	public static function get_order_item_meta($item_id,$meta_key){
		return wc_get_order_item_meta($item_id,$meta_key,true);
	}

}
