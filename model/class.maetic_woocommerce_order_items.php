<?php
class MaeTick_Woocommerce_Order_Items {
	public $ID;
	public $product;
	public $order;
	public $logs;

	public function __construct( $post_id ) {
		$this->ID = $post_id;
		$this->get_metas();
	}

	public function get_metas() {
		$this->product = $this->_product();
		$this->logs = $this->_logs();
		$this->order = $this->_order();
	}

	public function quantity() {
		return MaeTick_Woocommerce_Order_Itemmeta::has_ticket_qty_left( $this->ID );
	}

	public function used_quantity() {
		return MaeTick_Woocommerce_Order_Itemmeta::get_used_ticket_quantity( $this->ID );
	}

	public function all_quantity() {
		return MaeTick_Woocommerce_Order_Itemmeta::get_quantity( $this->ID );
	}

	public function expired_date() {
		return MaeTick_Woocommerce_Order_Itemmeta::get_expired_date( $this->ID );
	}

	private function _logs() {
		return MaeTick_Woocommerce_Order_Itemmeta::logs( $this->ID );
	}

	private function _product() {
		$product_id = MaeTick_Woocommerce_Order_Itemmeta::get_product_id_from_order_item_id( $this->ID );

		if ( empty( $product_id ) ) {
			return false;
		}
		return get_post( $product_id, 'OBJECT' );
	}

	private function _order() {
		return get_post( MaeTick_Woocommerce_Order_Itemmeta::get_order_id_from_order_item_id( $this->ID ), 'OBJECT');
	}

	public function use( $quantity ) {
		MaeTick_Woocommerce_Order_Itemmeta::use( $this->ID, $quantity );
	}

	public function ticket_code() {
		$code = wc_get_order_item_meta( $this->ID, 'maetic_ticket_id');
		if ( empty( $code ) ) {
			$code = self::generate_ticket_id( $this->ID );
		}
		return $code;
	}

	public function ticket_url( ) {
		return home_url( 'qr/'. $this->ticket_code() );
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