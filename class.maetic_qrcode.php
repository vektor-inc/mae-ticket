<?php

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

    public static function getImgTag( $code, $size, $quality='M', $attributes=array() ) {
        $url = self::createUrl( $code, $size, $quality );

        $attr = '';
        foreach ( $attributes as $k => $v ) {
            $attr .= $k . '="' . $v .'" ';
        }

        return '<img '. $attr . 'src="'. $url .'" />';
    }
}
