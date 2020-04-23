<?php

class MaeTick_Order {
	const TICKET_META_NAME = 'maetic_ticket_id';
	public $ID;
	public $order;

	public function __construct( $order_id, $order=null ) {
		$this->ID = $order_id;

		if ( !is_null( $order ) ) {
			$this->order = $order;
		}
	}

	public function get_order() {
		if ( empty( $this->order ) ) {
			$this->order = new WC_Order( $this->ID );
		}
		return $this->order;
	}

	public function get_ticket_code() {
		$code = get_post_meta( $this->ID, self::TICKET_META_NAME, true );
		if ( empty( $code ) ) {
			$code = $this->set_ticket_code();
		}
		return $code;
	}

	public function ticket_url( ) {
		return home_url( 'qr/'. $this->get_ticket_code() );
	}

	public function set_ticket_code() {
		return self::generate_ticket_code( $this->ID );
	}

	public function get_ticket_expired_time() {
		return 1;
	}

	public function tickets() {
		$tickets = array();

		foreach( $this->order->get_items() as $item_id => $item ) {
			if ( self::is_maetic_product( $item->get_product_id() ) == 'yes' ) {
				$tickets[$item_id] = new Maetic_Ticket( $item_id, $item );
			}
		}

		return $tickets;
	}

	public function use_tickets( $count ) {
		$tickets = $this->tickets();
		foreach ( $count as $id => $c ) {
			$c = intval( $c );
			if ( $c <= 0 ) {
				continue;
			}
			$tickets[ $id ]->use($c);
		}
	}

	public static function get_order_from_ticket_code( $ticket_id ) {
		$r = MaeTick_Woocommerce_Order_Itemmeta::get_ticket_id( $ticket_id );
		if ( !empty($r) ) {
			return new MaeTick_Woocommerce_Order_Items( $r );
		}
	}

	public static function generate_ticket_code( $order_id ) {
		$ticket_code = strval( maetic_add_pad( maetic_get_random_value() ) );
		update_post_meta( $order_id, self::TICKET_META_NAME, $ticket_code );
		return $ticket_code;
	}

	public static function has_ticket( $order_id ) {
		$order = new WC_Order( $order_id );

		foreach( $order->get_items() as $item_id => $order ) {
			if ( self::is_maetic_product( $order->get_product_id() ) == 'yes' ) {
				$in_ticket = true;
				return $order;
			}
		}

		return false;
	}

	public static function get_order_from_ticket_id( $ticket_id ) {
		$r = self::search_order_from_ticket_code( $ticket_id );

		if ( !empty($r) ) {
			$order = new MaeTick_Order( $r );
			$order->get_order();
			return $order;
		}
	}

	public static function search_order_from_ticket_code( $ticket_id ) {
		global $wpdb;

		$r = $wpdb->get_results(
			$wpdb->prepare(
				"
					SELECT `post_id`
					FROM `$wpdb->postmeta`
					WHERE
						`meta_key` = %s
						AND
							`meta_value` = %s
					;
				",
				self::TICKET_META_NAME,
				$ticket_id
			),
			ARRAY_N
		);

		if ( count($r) == 0 ) {
			return null;
		}

		return $r[0][0];
	}

	/**
	 * 注文が存在するかつ、Maeticプロダクトか確認。
	 * @param $orderId
	 *
	 * @return bool
	 */
	public static function is_orderItemId_valid( $order_item_id ){
		$product_id = MaeTick_Woocommerce_Order_Itemmeta::get_product_id_from_order_item_id($order_item_id);
		$is_maetic_product = MaeTick_Postmeta::is_maetic_product($product_id);
		$expired_date = MaeTick_Woocommerce_Order_Itemmeta::get_expired_date($order_item_id);
		$left_ticket = MaeTick_Woocommerce_Order_Itemmeta::has_ticket_qty_left($orderis_maetic_product_item_id);

		return $is_maetic_product==="yes" && $expired_date >= time() && $left_ticket;
	}

	public static function get_expired_period( $product_id ){
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
	public static function is_maetic_product( $product_id ){
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
