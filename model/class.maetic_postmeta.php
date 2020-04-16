<?php
class MaeTick_Postmeta {
    public static function init() {

    }
	public static function is_orderId_valid($orderId){
    	$order = get_post($orderId);
		if(is_null($order)){
			return;
		};

		$is_expired = MaeTick_Postmeta::is_orderId_expired($orderId);
		$is_maeTick = MaeTick_Postmeta::is_orderId_maeTick($orderId);

		if(!$is_expired && $is_maeTick){
			return $order;
		}
	}

	public static function is_orderId_expired($orderId){

	}

	public static function is_orderId_maeTick($orderId){
	}
}
