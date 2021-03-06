<?php

if ( !defined('ABSPATH') ) {
	die();
}


class Maetic_Admin {
	const WC_ORDER_POST_TYPE = 'shop_order';

	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'metabox_init' ) );
		add_action( 'wp_dashboard_setup', array( __CLASS__, 'add_dashboard' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_page') );

		add_filter( 'woocommerce_product_data_tabs', array( __CLASS__, 'product_tab'), 10, 1 );
		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'product_tab_content'), 10, 0 );
		add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_product_options_field'),10, 1 );
	}

	public static function add_admin_page() {
		add_submenu_page(
			'woocommerce',
			__( 'MaeTicket', 'mae-ticket' ),
			__( 'MaeTicket', 'mae-ticket' ),
			'activate_plugin',
			'maetic',
			array( __CLASS__, 'admin_page' )
		);
	}

	public static function admin_page() {
		if ( isset($_GET[ 'action' ] ) ) {
			if ( $_GET[ 'action' ] == 'url' ) {
				MaeTick_Front_Controller::set_rewrite_rules();

				?>
<div id="maetic_noticebox">
	<div class="notice notice-success settings-error is-dismissible">
		<span><?php _e( 'parmalink updated.', 'mae-ticket' ); ?></span>
	</div>
</div>
				<?php
			}
		}

		include( dirname( __FILE__ ) . '/pages/admin_menu.php' );
	}

	public static function add_dashboard() {
		wp_add_dashboard_widget(
			'maetic_dashboard',
			__( 'MaeTicket', 'mae-ticket' ),
			array( __CLASS__, 'show_dashboard' )
		);
	}

	public static function show_dashboard() {
		include( dirname( __FILE__ ) . '/pages/dashboard.php' );
	}

	public static function product_tab( $tabs ) {
		// Key should be exactly the same as in the class product_type
		$tabs['simple'] = array(
			'label'  => __( 'MaeTicket', 'mae-ticket' ),
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
					'id'    => 'enable_maetic_product',
					'label' => __( 'Enable MaeTicket Product', 'mae-ticket' ),
				)
			);

			woocommerce_wp_text_input(
				array(
					'id'                => 'maetic_expired_period',
					'label'             => __( 'Expired Period (Day)', 'mae-ticket' ),
					'desc_tip'          => 'true',
					'description'       => __( 'Enter the number of days the virtual ticket is valid for.', 'mae-ticket' ),
					'type'              => 'number',
					'custom_attributes' => array(
						'min'     => 0,
						'step'    => '1',
						'default' => 60
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
			'high'
		);
	}

	public static function meta_box() {
		if ( ! Maetic_Order::has_ticket( get_the_ID() ) ) {
			echo __( "this isn't ticket order.", 'mae-ticket' );
			return;
		}

		$ticket_order = new Maetic_Order( get_the_ID() );
		$ticket_order->get_order();

		include( dirname( __FILE__ ) . '/pages/order-metabox.php' );

	}
}
