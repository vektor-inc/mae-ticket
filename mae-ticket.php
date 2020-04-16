<?php
/*
* Plugin Name: maeticket
* Version: 0.0.1
* Author: Vektor, Inc.
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once( 'class.maetic_ticket_stats_management.php' );

class MaeTick_Front_Controller {
    const CODE_VAR = 'ticket_code';

    public static function init() {
        add_filter( 'query_vars', array( __CLASS__, 'query_vars'), 10, 1 );
        add_action( 'init', array( __CLASS__, 'add_routes' ) );
        add_action( 'template_redirect', array( __CLASS__, 'front_controller' ), 10, 1 );
        // add_action( 'parse_query', array( ))
        add_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ), 9, 1 );
    }

    public static function set_rewrite_rules() {
        // add_action( 'init', array( __CLASS__, 'add_routes' ) );
        // $this->manage_user_routes();
        flush_rewrite_rules();
    }

    public static function pre_get_posts( $query ) {
        $code = $query->get(self::CODE_VAR);
        if( !empty($code) ) {
            // $query->set( 'post_type', );
        }
    }

    public static function add_routes() {
        add_rewrite_rule( '^qr/([^/]+)/?', 'index.php?'. self::CODE_VAR .'=$matches[1]', 'top' );
    }

    public static function query_vars( $vars ) {
        $vars[] = self::CODE_VAR;
        return $vars;
    }

    public static function front_controller() {
        global $wp_query;
        $code_var = $wp_query->get( self::CODE_VAR );
        var_dump($code_var);
        error_log($code_var);

        get_template_part( 'qr-code' );
    }

}

/**
 * Check if WooCommerce is active
 **/
if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    return;
}

add_action('wp_head', function(){
    // echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
});

MaeTick_Front_Controller::init();
register_activation_hook( __FILE__, array( 'MaeTick_Front_Controller', 'set_rewrite_rules') );