<?php
/*
* Plugin Name: maeticket
* Version: 0.0.1
* Author: Vektor, Inc.
*/

if ( !defined('ABSPATH') ) {
    die();
}

require_once( dirname( __FILE__ ) . '/class.maetic_front_controller.php' );
require_once( dirname( __FILE__ ) . '/class.maetic_qrcode.php' );
require_once( dirname( __FILE__ ) . '/class.maetic_base64qrcode.php' );



MaeTick_Front_Controller::init();
register_activation_hook( __FILE__, array( 'MaeTick_Front_Controller', 'set_rewrite_rules') );
