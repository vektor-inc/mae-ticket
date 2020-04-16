<?php

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

