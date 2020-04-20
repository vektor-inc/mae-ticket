<?php
require_once( dirname( __FILE__ ) . '/class.maetic_woocommerce_order_itemmeta.php' );

class MaeTick_Postmeta {

	/**
	 * 注文が存在するかつ、Maeticプロダクトか確認。
	 * @param $orderId
	 *
	 * @return bool
	 */
	public static function is_orderItemId_valid($order_item_id){
		$product_id = MaeTick_Woocommerce_Order_Itemmeta::get_product_id_from_order_item_id($order_item_id);
		$is_maetic_product = MaeTick_Postmeta::is_maetic_product($product_id);
		$expired_date = MaeTick_Woocommerce_Order_Itemmeta::get_expired_date($order_item_id);
		$left_ticket = MaeTick_Woocommerce_Order_Itemmeta::has_ticket_qty_left($order_item_id);

		return $is_maetic_product==="yes" && $expired_date >= time() && $left_ticket;
	}

	public static function get_expired_period($product_id){
		$expired_period = get_post_meta($product_id, 'maetic_expired_period', true);
		if($expired_period === ''){
			return false;
		}else{
			return $expired_period;
		}
	}

	/**
	 * Maeticプロダクトか判定
	 * @param $product_id
	 *
	 * @return bool
	 */
	public static function is_maetic_product($product_id){
		return get_post_meta($product_id, 'enable_maetic_product', true);
	}

	/**
	 * 注文日時を取得 (unixtime)
	 * @param $orderId
	 *
	 * @return bool|mixed
	 */
	public static function get_ordered_date($orderId){

		$_date_completed = get_post_meta($orderId, '_date_completed', true);
		if($_date_completed === ''){
			return false;
		}else{
			return $_date_completed;
		}
	}
}
