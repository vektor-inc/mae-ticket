<?php

class MaeticAdmin {
	const WC_ORDER_POST_TYPE = 'shop_order';

	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'metabox_init' ) );
		add_filter( 'woocommerce_product_data_tabs', array( __CLASS__, 'product_tab'), 10, 1 );
		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'product_tab_content'), 10, 0 );
		add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_product_options_field'),10, 1 );
	}

	public static function product_tab( $tabs ) {
		// Key should be exactly the same as in the class product_type
		$tabs['simple'] = array(
			'label'	 => __( 'Maetic', 'mae-ticket' ),
			'target' => 'maetic_product_options',
			'class'  => array( 'show_if_maetic_product' ),
			);
		return $tabs;
	}

	public static function product_tab_content() {
		// Dont forget to change the id in the div with your target of your product tab
		?>
<div id='maetic_product_options' class='panel woocommerce_options_panel'>
	<div class='options_group'>
		<?php
			woocommerce_wp_checkbox(
				array(
					'id' 	=> 'enable_maetic_product',
					'label' => __( 'Enable Maetic Product', 'mae-ticket' ),
				)
			);

			woocommerce_wp_text_input(
				array(
					'id'				=> 'maetic_expired_period',
					'label'				=> __( 'Expired Period (Day)', 'mae-ticket' ),
					'desc_tip'			=> 'true',
					'description'		=> __( 'Enter the number of days the virtual ticket is valid for.', 'mae-ticket' ),
					'type' 				=> 'number',
					'custom_attributes'	=> array(
						'min'	=> '1',
						'step'	=> '1',
					),
				)
			);
		?>
	</div>
</div>
		<?php
	}

	public static function save_product_options_field( $post_id ) {
		$enable_maetic_product = isset( $_POST['enable_maetic_product'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, 'enable_maetic_product', $enable_maetic_product );

		if ( isset( $_POST['maetic_expired_period'] ) ) {
			update_post_meta( $post_id, 'maetic_expired_period', intval( $_POST['maetic_expired_period'] ) );
		}
	}

	public static function metabox_init() {
		add_meta_box(
			'maetic_meta',
			__( 'maetic', 'mae-ticket' ),
			array( __CLASS__, 'meta_box' ),
			array( self::WC_ORDER_POST_TYPE ),
			'normal',
			'high',
		);
	}

	public static function meta_box() {
		if ( ! MaeTick_Order::has_ticket( get_the_ID() ) ) {
			echo __( "this isn't ticket order.", 'mae-ticket' );
			return;
		}

		$ticket_order = new MaeTick_Order( get_the_ID() );
		$ticket_order->get_order();

		echo '<span class="maetic_code">'. maetic_get_separated_code( $ticket_order->get_ticket_code() ) . '</span>';
		echo '<ul>';
		foreach ( $ticket_order->tickets() as $id => $ticket ) {
			echo '<li>';
			echo $ticket->get_title();
			echo ' - ';
			echo $ticket->get_rest_quantity(). ' rest';
			echo '</li>';
		}
		echo '</ul>';
	}
}