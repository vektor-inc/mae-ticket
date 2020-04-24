<?php

if ( !defined('ABSPATH') ) {
	die();
}

class MaeticCore {

	public static function init() {
		add_action( 'woocommerce_payment_complete', array( __CLASS__, 'payment_complete'), 10, 1 );
		add_action('woocommerce_email_order_details', array( __CLASS__, 'add_qr_code'), 10, 4);
	}

	public static function payment_complete( $order_id ) {
		if ( MaeTick_Order::is_maetic_product( $order_id ) ) {
			$ticket_order = new MaeTick_Order( $order_id, $order );
			if ( !$ticket_order->is_completed() ) {
				return;
			}
			$ticket_code = $ticket_order->get_ticket_code();
		}
	}

	public static function add_qr_code( $order, $sent_to_admin, $plain_text, $email ){

		if ($order->get_status() != 'completed' ) {
			return;
		}

		if ( MaeTick_Order::has_ticket( $order->get_id() ) ) {
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
}