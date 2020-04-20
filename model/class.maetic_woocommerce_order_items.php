<?php
class MaeTick_Woocommerce_Order_Items {
	public $ID;

	public function __construct( $post ) {
		foreach ( get_object_vars( $post ) as $key => $value ) {
			$this->$key = $value;
		}
	}

	public static function get_order_from_ticket_id( $ticket_id ) {
		$r = MaeTick_Woocommerce_Order_Itemmeta::get_ticket_id( $ticket_id );
		if ( !empty($r) ) {
			return new MaeTick_Woocommerce_Order_Items( $r );
		}
	}

	public static function generate_ticket_id( $order_id ) {
		$ticket_id = maetic_get_random_value();
		wc_update_order_item_meta( $order_id, 'maetic_ticket_id', $ticket_id );
		return $ticket_id;
	}

	public static function get_order_items_id( $order_id ){
		$order = wc_get_order($order_id);

		if ( empty($order) ) {
			return array();
		}

		$order_items = $order->get_items();
		$order_items_id =[];

		foreach ($order_items as $order_item_id => $order_item) {
			array_push($order_items_id,$order_item_id);
		}

		return $order_items_id;
	}
}