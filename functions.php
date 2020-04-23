<?php

function maetic_get_template( $type_origin, $templates=array() ) {
     $type_origin = preg_replace( '|[^a-z0-9-]+|', '', $type_origin );
     $type = 'maetic/' . $type_origin;

    if ( empty( $templates ) ) {
         $templates = array( "{$type}.php" );
    }

    $templates = apply_filters( "{$type}_template_hierarchy", $templates );
    $template = locate_template( $templates );

    // addition
    $plugin_template_path = plugin_dir_path( __FILE__ ) . "templates/{$type_origin}.php";
    if ( empty( $template ) && file_exists( $plugin_template_path ) ) {
        $template = $plugin_template_path;
    }

    return apply_filters( "{$type}_template", $template, $type, $templates );
}

function maetic_get_template_part( $slug ) {
    $template = maetic_get_template( $slug );

    if ( $template ) {
        include( $template );
    }
}

function maetic_code_strip( $code ){
     $code = preg_replace( '|[^0-9]+|', '', $code );

    return $code;
}

function maetic_add_pad( $code ) {
    while( strlen($code) < 16 ) {
        $code = '0' . $code;
    }
    return $code;
}

function maetic_get_separated_code( $code, $separator='-' ) {
    $block = ceil( strlen($code) / 4 );
    $buf = array();
    for($i=0;$i<$block;$i++){
        $buf[] = substr( $code, $i * 4, 4 );
    }
    return implode( $separator, $buf );
}

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

function maetic_get_random_value() {
    return random_int( 0, pow(10, 16) - 1 );
}

add_action( 'woocommerce_payment_complete', 'maetic_payment_complete', 10, 1 );
function maetic_payment_complete( $order_id ) {
    if ( $in_ticket ) {
        $ticket_order = new MaeTick_Order( $order_id, $order );
        $ticket_code = $ticket_order->get_ticket_code();
    }
}

// add_action('wp_head', function(){
//     // maetic_payment_complete(1166);
//     $order = new WC_Order(1181);
//     if ( MaeTick_Order::has_ticket( $order->get_id() ) ) {
//         error_log("message");
//         var_dump($order);
//     //     var_dump($ticket_order)
//         $ticket_order = new MaeTick_Order( $order->get_id(), $order );

//         $size = 128;
//         $attributes = array(
//             'alt' => "QR Code",
//             'width' => $size . "px",
//             'height' => $size . "px"
//         );

//         $qr = MaeTick_QrCode::getImgTag( $ticket_order->ticket_url(), $size, 'M', $attributes );
//         var_dump($qr);
//     }

//     $r=MaeTick_Order::get_order_from_ticket_id('2903910494195477');
//     var_dump($r);
// });

add_action('woocommerce_email_order_details', 'maetic_add_qr_code', 10, 4);
function maetic_add_qr_code( $order, $sent_to_admin, $plain_text, $email ){

    if ($order->get_status() != 'completed' ) {
        return;
    }

    if ( MaeTick_Order::has_ticket( $order->get_id() ) ) {
        error_log("famas-----");
        $ticket_order = new MaeTick_Order( $order->get_id(), $order );
        $size = 128;
        $s = $size . "px";

        echo <<<EOL
<style>
#code{font-size:2em;font-weight:bold;font-family:sans-serif;}
#c_w{border-bottom:solid 1px #333;padding:0.3em;text-align:center;width:100%;}
#qr{text-align: center;}
#qr_w{padding:2em;background-color:#fff;}
</style>
EOL;

        echo "<p>チケットの詳細です。お越しの際にこちらのQRコードを提示するか、<br/>下記の番号をお伝えください。</p>";
        $code = maetic_get_separated_code( $ticket_order->get_ticket_code() );
        echo '<div id="c_w"><span id="code">'. $code .'</span></div>';

        $attributes = array(
            'alt' => "QR Code",
        );

        $qr = MaeTick_QrCode::getImgTag( $ticket_order->ticket_url(), $size, 'M', $attributes );

        echo '<div id="qr"><span id="qr_w">'. $qr. '</span></div>';

    }
}
