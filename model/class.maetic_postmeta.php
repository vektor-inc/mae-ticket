<?php
class MaeTick_Postmeta {

//	/**
//	 * 注文が存在するかつ、Maeticプロダクトか確認。
//	 * @param $orderId
//	 *
//	 * @return bool
//	 */
//	public static function is_orderId_valid($orderId){
//    	$order = get_post($orderId);
//		if(is_null($order)){
//			return false;
//		};
//		//		&& time() <= MaeTick_Postmeta::get_expired_date($orderId)
//		return MaeTick_Postmeta::is_orderId_maetic_product($orderId);
//	}

	public static function get_expired_period($product_id){
		$expired_period = get_post_meta($product_id, 'maetic_expired_period', true);
		if($expired_period === ''){
			return false;
		}else{
			return $expired_period;
		}
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
