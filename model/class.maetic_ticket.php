<?php

if ( !defined('ABSPATH') ) {
	die();
}

class Maetic_Ticket {
	public $ID;
	public $item;

	public function __construct( $item_id, $item=null ) {
		$this->ID = $item_id;
		if ( !empty($item) ) {
			$this->item = $item;
		}
	}

	public function get_expire_date() {
		$start = $this->item->get_order()->get_date_completed();
		if( empty( $start ) ) {
			return null;
		}
		$order_exipre = get_post_meta($this->item->get_product_id(), 'maetic_expired_period', true);
		return $start->modify('+'. $order_exipre . ' days');
	}

	public function get_title() {
		return get_the_title( $this->item->get_product_id() );
	}

	public function get_quantity() {
		return $this->item->get_quantity();
	}

	public function get_rest_quantity() {
		return intval( MaeTick_Woocommerce_Order_Itemmeta::has_ticket_qty_left( $this->ID ) );
	}

	public function use( $count ) {
		return MaeTick_Woocommerce_Order_Itemmeta::use( $this->ID, $count );
	}

	public function get_logs() {
		$l =  MaeTick_Woocommerce_Order_Itemmeta::logs( $this->ID );
		$d = new WC_DATETime();
		$d->SetTimezone( wp_timezone() );

		for( $i=0;$i<count($l);$i++ ){
			$e = clone $d;
			$e->SetTimeStamp( $l[$i]['time'] );
			$l[$i]['date'] = $e;
		}

		return $l;
	}
}
