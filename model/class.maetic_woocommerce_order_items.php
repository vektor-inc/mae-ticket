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

	// public function get_ticket_code() {
	// 	return get_post_meta( $this->ID, self::TICKET_META_NAME, true );
	// }

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
			if ( MaeTick_Postmeta::is_maetic_product( $order->get_product_id() ) == 'yes' ) {
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
}
