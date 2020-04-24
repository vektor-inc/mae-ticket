<?php
/**
 * Plugin Name:     maeticket
 * Plugin URI:      https://github.com/vektor-inc/mae-ticket
 * Description:
 * Author:          Vektor, Inc.
 * Text Domain:     mae-ticket
 * Domain Path:     /languages
 * Version:         0.0.1
 */

if ( !defined('ABSPATH') ) {
	die();
}

// common functions
require_once( 'functions.php' );
require_once( 'class.maetic_qrcode.php' );
require_once( dirname( __FILE__ ) . '/model/class.maetic_woocommerce_order_itemmeta.php' );
require_once( dirname( __FILE__ ) . '/model/class.maetic_order.php' );
require_once( dirname( __FILE__ ) . '/model/class.maetic_ticket.php' );

require_once( 'class.maetic_front_controller.php' );
require_once( 'class.maetic_core.php' );
require_once( 'class.maetic_admin.php' );

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'wp_enqueue_scripts', 'maetic_register_scripts' , 30, 0 );

	MaeTick_Front_Controller::init();
	MaeticCore::init();

	if ( is_admin() ) {
		MaeticAdmin::init();
	}
}

function maetic_register_scripts() {
	wp_register_style(
		'maetic',
		plugins_url( 'assets/css/style.css', __FILE__ ),
		array(),
		'1',
		'all'
	);
	wp_enqueue_style( 'maetic');

	wp_register_script(
		'maetic-form',
		plugins_url( 'assets/js/form.min.js', __FILE__ ),
		array(),
		'1',
		true
	);
	wp_enqueue_script( 'maetic-form' );
}


add_action(
	'plugins_loaded',
	function () {
		load_plugin_textdomain( 'mae-ticket', false, '/mae-ticket/languages/' );
	}
);
register_activation_hook( __FILE__, array( 'MaeTick_Front_Controller', 'set_rewrite_rules') );
