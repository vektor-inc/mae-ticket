<?php
require_once( dirname( __FILE__ ) . '/class.maetic_postmeta.php' );
define('LOG_USE', 'USE');
class MaeTick_Woocommerce_Order_Itemmeta extends MaeTick_Postmeta{

	public static function init() {
		add_filter( 'woocommerce_product_data_tabs', array( __CLASS__, 'maetic_product_tab'), 10, 1 );
		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'maetic_product_tab_content'), 10, 0 );
		add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_maetic_product_options_field'),10, 1 );
	}

	public static function maetic_product_tab($tabs) {
		// Key should be exactly the same as in the class product_type
		$tabs['simple'] = array(
			'label'	 => __( 'Maetic', 'mae-ticket' ),
			'target' => 'maetic_product_options',
			'class'  => ('show_if_maetic_product'),
			);
		return $tabs;
	}

	public static function maetic_product_tab_content() {
		// Dont forget to change the id in the div with your target of your product tab
		?><div id='maetic_product_options' class='panel woocommerce_options_panel'><?php
		?><div class='options_group'><?php

		woocommerce_wp_checkbox( array(
			'id' 	=> 'enable_maetic_product',
			'label' => __( 'Enable Maetic Product', 'mae-ticket' ),
		) );

		woocommerce_wp_text_input( array(
			'id'				=> 'maetic_expired_period',
			'label'				=> __( 'Expired Period (Day)', 'mae-ticket' ),
			'desc_tip'			=> 'true',
			'description'		=> __( 'Enter the number of days the virtual ticket is valid for.', 'mae-ticket' ),
			'type' 				=> 'number',
			'custom_attributes'	=> array(
				'min'	=> '1',
				'step'	=> '1',
			),
		) );
		?></div>
		</div><?php
	}

	public static function save_maetic_product_options_field( $post_id ) {
		$enable_maetic_product = isset( $_POST['enable_maetic_product'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, 'enable_maetic_product', $enable_maetic_product );

		if ( isset( $_POST['maetic_expired_period'] ) ) :
			update_post_meta( $post_id, 'maetic_expired_period', intval( $_POST['maetic_expired_period'] ) );
		endif;
	}

	public static function get_expired_date( $order_item_id ){

		$order_id   = MaeTick_Woocommerce_Order_Itemmeta::get_order_id_from_order_item_id($order_item_id);
		$ordered_date = MaeTick_Postmeta::get_ordered_date($order_id);

		$product_id   = MaeTick_Woocommerce_Order_Itemmeta::get_product_id_from_order_item_id($order_item_id);
		$expired_period = MaeTick_Postmeta::get_expired_period($product_id);
		return $ordered_date + (intval($expired_period)*86400);//期限切れ期間(日)×1日(秒)
	}

	public static function get_order_id_from_order_item_id($order_item_id){
		$item = new WC_Order_Item_Product($order_item_id);
		return $item->get_order_id();
	}

	public static function get_product_id_from_order_item_id($order_item_id){
		$item = new WC_Order_Item_Product($order_item_id);
		return $item->get_product_id();
	}

	public static function get_order_item_meta($order_item_id,$meta_key){
		return wc_get_order_item_meta($order_item_id,$meta_key,true);
	}

	public static function get_quantity($order_item_id){
		return self::get_order_item_meta($order_item_id,'_qty');
	}

	public static function get_used_ticket_quantity($order_item_id){

		$used_ticket_quantity = wc_get_order_item_meta($order_item_id,'maetic_used_ticket_quantity',true);
		//フィールドが存在しない場合0を追加する
		if($used_ticket_quantity === ""){
			wc_update_order_item_meta($order_item_id,'maetic_used_ticket_quantity',0);
			$used_ticket_quantity = wc_get_order_item_meta($order_item_id,'maetic_used_ticket_quantity',true);
		}
		return $used_ticket_quantity;
	}

	public static function has_ticket_qty_left($order_item_id){
		$quantity = self::get_quantity($order_item_id);
		$used_ticket_quantity = self::get_used_ticket_quantity($order_item_id);
		return $quantity - $used_ticket_quantity > 0;
	}

	public static function update_used_ticket_quantity($order_item_id,$count){
		return wc_update_order_item_meta( $order_item_id, 'maetic_used_ticket_quantity', $count );
	}

	public static function use( $order_item_id, $count ) {
		$left = self::has_ticket_qty_left( $order_item_id ) - intval( $count );

		if ( $left < 0 ) {
			throw new WP_Error( 'invalid quantity' );
		}
		$payload = array(
			'count' => $count
		);
		self::log( $order_item_id, LOG_USE, $payload );
		self::update_used_ticket_quantity( $order_item_id, $left );
	}

	public static function log( $order_item_id, $type, $values=array() ){
		$values['type'] = $type;
		$values['time'] = time();
		$values['user'] = get_current_user_id();
		return wc_add_order_item_meta( $order_item_id, 'maetic_log', $values, false );
	}

	public static function logs( $order_item_id ) {
		return wc_get_order_item_meta( $order_item_id, 'maetic_log' );
	}

	public static function get_ticket_id( $ticket_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'woocommerce_order_itemmeta';

		$r = $wpdb->get_results(
			$wpdb->prepare(
				"
					SELECT `order_item_id`
					FROM `$table_name`
					WHERE
						`meta_key` = %s
						AND
							`meta_value` = %s
					;
				",
				'maetic_ticket_id',
				$ticket_id
			),
			ARRAY_N
		);

		if ( count($r) == 0 ) {
			return false;
		}

		return $r[0][0];
	}

}
