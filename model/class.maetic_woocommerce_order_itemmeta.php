<?php
define('LOG_USE', 'USE');

class MaeTick_Woocommerce_Order_Itemmeta {
	const QUANTITY_META_NAME = 'maetic_used_ticket_quantity';

	public static function item_object( $order_item_id ) {
		return new WC_Order_Item_Product($order_item_id);
	}

	public static function get_order_id_from_order_item_id($order_item_id){
		$item = self::item_object($order_item_id);
		return $item->get_order_id();
	}

	public static function get_product_id_from_order_item_id($order_item_id){
		$item = self::item_object($order_item_id);
		return $item->get_product_id();
	}

	public static function get_order_item_meta($order_item_id,$meta_key){
		return wc_get_order_item_meta($order_item_id,$meta_key,true);
	}

	public static function get_quantity($order_item_id){
		return intval(self::get_order_item_meta($order_item_id,'_qty'));
	}

	public static function get_used_ticket_quantity($order_item_id){
		$used_ticket_quantity = wc_get_order_item_meta($order_item_id, self::QUANTITY_META_NAME, true);

		if( empty( $used_ticket_quantity ) ){
			self::update_used_ticket_quantity( $order_item_id, 0 );
			return 0;
		}
		return intval( $used_ticket_quantity );
	}

	public static function has_ticket_qty_left($order_item_id){
		$quantity = self::get_quantity( $order_item_id );
		$used_ticket_quantity = self::get_used_ticket_quantity( $order_item_id );
		return $quantity - $used_ticket_quantity;
	}

	public static function update_used_ticket_quantity($order_item_id,$count){
		return wc_update_order_item_meta( $order_item_id,  self::QUANTITY_META_NAME, $count );
	}

	public static function use( $order_item_id, $count ) {
		$sum = self::get_used_ticket_quantity( $order_item_id ) + intval( $count );

		if ( $sum > self::get_quantity( $order_item_id ) ) {
			throw new WP_Error( 'invalid quantity' );
		}

		$payload = array(
			'count' => $count
		);
		self::log( $order_item_id, LOG_USE, $payload );
		self::update_used_ticket_quantity( $order_item_id, $sum );
	}

	public static function log( $order_item_id, $type, $values=array() ){
		$values['type'] = $type;
		$values['time'] = time();
		$values['user'] = get_current_user_id();
		return wc_add_order_item_meta( $order_item_id, 'maetic_log', $values, false );
	}

	public static function logs( $order_item_id ) {
		return wc_get_order_item_meta( $order_item_id, 'maetic_log', false );
	}
}
