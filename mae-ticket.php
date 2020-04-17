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

require_once( dirname( __FILE__ ) . '/class.maetic_front_controller.php' );
require_once( dirname( __FILE__ ) . '/class.maetic_qrcode.php' );
require_once( dirname( __FILE__ ) . '/class.maetic_base64qrcode.php' );

MaeTick_Front_Controller::init();
register_activation_hook( __FILE__, array( 'MaeTick_Front_Controller', 'set_rewrite_rules') );
