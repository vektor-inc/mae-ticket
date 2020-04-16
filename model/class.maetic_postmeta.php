<?php
class MaeTick_Postmeta {
    public static function init() {

    }

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

		return MaeTick_Postmeta::is_orderId_expired($orderId);
	}

	/**
	 * 注文が有効期限が切れか確認。
	 * @param $orderId
	 *
	 * @return bool 有効な時にTrue|期限切れの時にFalse
	 */
	public static function is_orderId_expired($orderId){

		$_date_completed = get_post_meta($orderId, '_date_completed', true);
		if($_date_completed === ''){
			return false;
		}
		//unixtime
		$expired_period =get_option( 'maetic_expired_period', false );
		return time() <= $_date_completed + intval($expired_period);
	}
}
