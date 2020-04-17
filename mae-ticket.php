<?php
/**
 * Plugin Name:     maeticket
 * Plugin URI:      https://github.com/vektor-inc/mae-ticket
 * Description:
 * Author:          Vektor, Inc.
 * Text Domain:     mae-ticket
 * Domain Path:     /languages
 * Version:         0.0.1
 */

if ( !defined('ABSPATH') ) {
    die();
}

require_once( dirname( __FILE__ ) . '/class.maetic_front_controller.php' );
require_once( dirname( __FILE__ ) . '/class.maetic_qrcode.php' );
require_once( dirname( __FILE__ ) . '/class.maetic_base64qrcode.php' );
require_once( dirname( __FILE__ ) . '/model/class.maetic_postmeta.php' );
require_once( dirname( __FILE__ ) . '/model/class.maetic_woocommerce_order_itemmeta.php' );
require_once( dirname( __FILE__ ) . '/model/class.maetic_woocommerce_order_items.php' );


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	MaeTick_Woocommerce_Order_Itemmeta::init();

	add_action( 'woocommerce_after_register_post_type',function (){


//		$expired_period = MaeTick_Postmeta::get_expired_period(18);
//		echo $expired_period;


		$order_items_id = MaeTick_Woocommerce_Order_Items::get_order_items_id(20);
		foreach ($order_items_id as $order_item_id){
			$expired_date = MaeTick_Woocommerce_Order_Itemmeta::get_expired_date($order_item_id);
//			echo $expired_date . "|";
			echo date('Y/m/d H:i:s', intval($expired_date)) . '|';
		}
	});
}



//$is_valid = MaeTick_Postmeta::is_orderId_valid(20);
//$expired_date = MaeTick_Postmeta::get_expired_date(18);

//echo date('Y/m/d H:i:s', $expired_date);
//var_dump($is_valid);


//MaeTick_Front_Controller::init();
//register_activation_hook( __FILE__, array( 'MaeTick_Front_Controller', 'set_rewrite_rules') );
