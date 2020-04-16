<?php
/*
* Plugin Name: maeticket
* Version: 0.0.1
* Author: Vektor, Inc.
*/

if ( !defined('ABSPATH') ) {
    die();
}

require_once( 'class.maetic_front_controller.php' );
require_once( 'class.maetic_qrcode.php' );
require_once( 'class.maetic_base64qrcode.php' );


add_action( 'wp_enqueue_scripts', 'maet_register_scripts' , 30, 0 );
function maet_register_scripts() {
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

MaeTick_Front_Controller::init();
register_activation_hook( __FILE__, array( 'MaeTick_Front_Controller', 'set_rewrite_rules') );
