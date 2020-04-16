<?php

class MaeTick_Front_Controller {
    const CODE_VAR = 'ticket_code';
    const QR_INPUT_VAR = 'qr_input';

    public static function init() {
        add_filter( 'query_vars', array( __CLASS__, 'query_vars'), 10, 1 );
        add_action( 'init', array( __CLASS__, 'add_routes' ) );
        add_action( 'template_redirect', array( __CLASS__, 'front_controller' ), 10, 1 );
        // add_action( 'parse_query', array( ))
        add_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ), 9, 1 );
    }

    public static function set_rewrite_rules() {
        // add_action( 'init', array( __CLASS__, 'add_routes' ) );
        $this->manage_user_routes();
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
        add_rewrite_rule( '^qr/?$', 'index.php?'. self::QR_INPUT_VAR .'=1', 'top' );
    }

    public static function query_vars( $vars ) {
        $vars[] = self::CODE_VAR;
        $vars[] = self::QR_INPUT_VAR;

        return $vars;
    }

    public static function front_controller() {
        global $wp_query;
        $code_var = $wp_query->get( self::CODE_VAR );
        $qr_input = $wp_query->get( 'qr_input' );

        if ( $qr_input == '1' ) {
            $wp_query->init_query_flags();
            include( plugin_dir_path( __FILE__ ) . 'templates/input-page.php' );
            die();
        }
    }
}

function maetic_get_template_part( $part ) {
    include( plugin_dir_path( __FILE__ ) . 'templates/' . $part . '.php' );
}
