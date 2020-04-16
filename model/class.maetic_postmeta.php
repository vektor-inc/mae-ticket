<?php
class MaeTick_Postmeta {
    public static function init() {

    }

//	public static function is_orderId_valid($orderId){
//    	$order = get_post($orderId);
//		if(is_null($order)){
//			return false;
//		};
//
//		$is_expired = MaeTick_Postmeta::is_orderId_expired($orderId);
//		$is_maeTick = MaeTick_Postmeta::is_orderId_maetic_product($orderId);
//
//		return !$is_expired && $is_maeTick;
//	}

	public static function is_orderId_expired($orderId){

		$_date_completed = get_post_meta($orderId, '_date_completed', true);
		if($_date_completed === ''){
			return false;
		}
		//unixtime
		$expired_period =get_option( 'maetic_expired_period', false );
		return time() <= $_date_completed + intval($expired_period);
	}

//	public static function is_orderId_maetic_product($orderId){
//		$is_maetic_product = get_post_meta($orderId, 'maetic_is_maetic_product', true);
//		return $is_maetic_product !== '';
//	}
}
