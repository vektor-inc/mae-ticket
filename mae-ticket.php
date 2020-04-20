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

require_once( 'functions.php' );
require_once( 'class.maetic_front_controller.php' );
require_once( 'class.maetic_qrcode.php' );
require_once( 'class.maetic_base64qrcode.php' );
require_once( dirname( __FILE__ ) . '/model/class.maetic_postmeta.php' );
require_once( dirname( __FILE__ ) . '/model/class.maetic_woocommerce_order_itemmeta.php' );
require_once( dirname( __FILE__ ) . '/model/class.maetic_woocommerce_order_items.php' );

//モデルのサンプルコード
//if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
//	MaeTick_Woocommerce_Order_Itemmeta::init();
//
//	add_action( 'woocommerce_after_register_post_type',function (){
//
//		$expired_period = MaeTick_Postmeta::get_expired_period(18);
//		echo $expired_period;
//
//		$maetic_product = MaeTick_Postmeta::is_maetic_product(18);
//		echo "isMaetic : " . $maetic_product;
//		echo "<br>";
//
//		$order_items_id = MaeTick_Woocommerce_Order_Items::get_order_items_id(30);
//
//		foreach ($order_items_id as $order_item_id){
//
//			$valid = MaeTick_Postmeta::is_orderItemId_valid($order_item_id);
//			echo "valid : " . var_dump($valid);
//
//			$update = MaeTick_Woocommerce_Order_Itemmeta::update_used_ticket_quantity($order_item_id,0);
//
//			$expired_date = MaeTick_Woocommerce_Order_Itemmeta::get_expired_date($order_item_id);
//			$quantity = MaeTick_Woocommerce_Order_Itemmeta::get_quantity($order_item_id);
//			$used_ticket_quantity = MaeTick_Woocommerce_Order_Itemmeta::get_used_ticket_quantity($order_item_id);
//			echo "<br>";
//			echo "date : " . date('Y/m/d H:i:s', intval($expired_date)) . '|';
//			echo "<br>";
//			echo "quantity : " . $quantity;
//			echo "<br>";
//			echo "used_ticket_quantity : " . $used_ticket_quantity;
//		}
//	});
//}

MaeTick_Front_Controller::init();
register_activation_hook( __FILE__, array( 'MaeTick_Front_Controller', 'set_rewrite_rules') );
