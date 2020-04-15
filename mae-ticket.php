<?php
/*
* Plugin Name: maeticket
* Version: 0.0.1
* Author: Vektor, Inc.
*/

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

class MaeTick_QrCode {
    const API_BASE = 'https://chart.googleapis.com/chart';

    public function _construct( $code, $size="512x512", $quality='M' ) {
        $this->code = $code;
        $this->quality = $quality;
        $this->size = $size;
    }

    public function _getUrl() {
        return self::createUrl( $this->code, $this->size, $this->$quality );
    }

    public static function createUrl( $code, $size, $quality ) {
        $options = array(
            'cht=qr',
            'chld=' . $quality,
            'chs=' . $size,
            'chl=' . urlencode($code)
        );

        return self::API_BASE . '?' . implode( '&', $options );
    }

    public static function getImgTag( $code, $size, $quality, $additional_cllasses=array() ) {
        $url = self::createUrl( $code, $size, $quality );
        $classes = implode( ' ', $additional_cllasses );
        $cls = '';
        if ( !empty($classes) ) {
            $cls = ' class="'. $classes . '"';
        }
        return '<img'. $cls . ' src="'. $url .'" />';
    }
}

class MaeTick_Base64QrCode extends MaeTick_QrCode {
    public static function getImgTag( $code, $size, $quality , $additional_cllasses=array() ) {
        list( $type, $data ) = self::getBody( $code, $size, $quality );

        $classes = implode( ' ', $additional_cllasses );
        $cls = '';
        if ( !empty($classes) ) {
            $cls = ' class="'. $classes . '"';
        }
        return '<img'. $cls . ' src="data:' . $type . ';base64,' . $data . '" />';
    }

    public static function getBody( $code, $size, $quality ) {
        return self::_cache( array($code, $size, $quality), array( __CLASS__, '_getBody' ), array( $code, $size, $quality ) );
    }

    public static function _getBody( $code, $size, $quality ) {
        $url = self::createUrl( $code, $size, $quality );
        $r = wp_safe_remote_get($url);

        $type = 'image/ping';
        if ( isset($r['headers']['content-type']) ) {
            $type = $r['headers']['content-type'];
        }
        if( $r['response']['code'] == 200) {
            return array( $type, base64_encode($r['body']) );
        }

        return array( $type, '' );
    }

    public static function _cache( $keys, $fnc, $args ) {
        $key = md5( implode( $keys ) );
        $result = wp_cache_get( $key );
        if ( false == $result ) {
            $result = call_user_func_array( $fnc, $args );
            wp_cache_set($key, $result);
        }
        return $result;
    }
}


MaeTick_Front_Controller::init();
register_activation_hook( __FILE__, array( 'MaeTick_Front_Controller', 'set_rewrite_rules') );
