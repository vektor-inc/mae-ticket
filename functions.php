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

function maetic_code_add_pad( $code ) {
    while( strlen($code) < 16 ) {
        $code = '0' . $code;
    }
    return $code;
}

function maetic_get_separated_code( $code, $separator='-' ) {
    $block = ceil( strlen($code) / 4 );
    $buf = array();
    for($i=0;$i<$block;$i++){
        $buf[] = substr( $code, $i, 4 );
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
    return random_int(0, 16);
}

add_action( 'woocommerce_payment_complete', 'maetic_payment_complete', 10, 1 );
function maetic_payment_complete( $order_id ) {
    error_log("---------------------------");
    error_log( $order_id );
    $order = new WC_Order( $order_id );
}


function send_ticket_email( $ticket, $user_email, $args=array() ) {
    array_push($args, 'Content-Type: text/html; charset=ISO-2022-JP');
    $title = get_bloginfo('name');
    $subject ="[$title] チケットの送信";

    $qr = MaeTick_QrCode::getImgTag( $ticket->ticket_url(), 256, 'M' );
    $code = maetic_get_separated_code($ticket->ticket_code());
    $site_url = site_url();
    $ticket_quantity = $ticket->all_quantity();
    $ticket_expired_date = $ticket->expired_date();
    $ticket_title = $ticket->product->post_title;

    $body = <<<EOL
<!doctype html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html;>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width">
  <title>$subject</title>
  <style type="text/css">
    body{color:#fff}
    .wrap{padding:1em;max-width:600px;margin:auto;text-align:center}
    #header{background-color:red;color:#fff;}
    #footer{border-top:1px solid #333;background-color:#aaa;}
    #code{font-size:3em;font-weight:bold;font-family:sans-serif;}
    #c_w{border-bottom:solid 1px #333;padding:0.3em;}
    #qr{padding:2em;background-color:#fff;}
  </style>
</head>
<body>
<div id="header"><div class="wrap">
<h1>Ticket Information</h1>
</div></div>
<div id="content"><div class="wrap">
    <p>チケットの詳細です。お越しの際にこちらのQRコードを提示するか、下記の番号をお伝えください。</p>
    <h2>$ticket_title</h2>
    <div id="c_w"><span id="code">$code</span></div>
    <div id="qr">$qr</div>
    <div id="description">
        チケット数: $ticket_quantity 枚<br/>
        有効期限: $ticket_expired_date
    </div>
</div></div>
<div id="footer"><div class="wrap">
<ul>
    <li><a href="$title">$site_url</a></li>
</ul>
</div></div>
</html>
EOL;

    wp_mail([$user_email], $subject, $body, $args);
}
