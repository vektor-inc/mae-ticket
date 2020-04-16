<?php
class MaeTick_Postmeta {

	/**
	 * 注文が存在するかつ、期限切れでないか確認。
	 * @param $orderId
	 *
	 * @return bool
	 */
	public static function is_orderId_valid($orderId){
    	$order = get_post($orderId);
		if(is_null($order)){
			return false;
		};
		return time() <= MaeTick_Postmeta::get_expired_date($orderId);
	}

	/**
	 * 有効期限を取得
	 * @param $orderId
	 *
	 * @return bool|int|mixed
	 */
	public static function get_expired_date($orderId){

		$ordered_date = MaeTick_Postmeta::get_ordered_date($orderId);
		//unixtime
		$expired_period =get_option( 'maetic_expired_period', false );
		return $ordered_date + intval($expired_period);
	}

	/**
	 * 注文日時を取得
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
