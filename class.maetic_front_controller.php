<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class MaeTick_Front_Controller {
	const ACTION_VAR   = 'action_code';
	const CODE_VAR     = 'ticket_code';
	const QR_INPUT_VAR = 'qr_input';

	public static function init() {
		add_filter( 'query_vars', array( __CLASS__, 'query_vars' ), 10, 1 );
		add_action( 'init', array( __CLASS__, 'add_routes' ) );
		add_action( 'template_redirect', array( __CLASS__, 'front_controller' ), 10, 1 );
		add_filter( 'woocommerce_display_item_meta', array( __CLASS__, 'item_meta_hidden' ) );
	}

	/**
	 * Delete item meta "maetic_used_ticket_quantity" in mail
	 *
	 * @param [type] $html
	 * @return void
	 */
	public static function item_meta_hidden( $html ){
		$html = '';
		return $html;
	}

	public static function set_rewrite_rules() {
		self::add_routes();
		flush_rewrite_rules();
	}

	public static function add_routes() {
		add_rewrite_rule( '^qr/([^/]+)/([a-zA-Z]+)/?', 'index.php?' . self::CODE_VAR . '=$matches[1]&' . self::ACTION_VAR . '=$matches[2]', 'top' );
		add_rewrite_rule( '^qr/([^/]+)/?', 'index.php?' . self::CODE_VAR . '=$matches[1]', 'top' );
		add_rewrite_rule( '^qr/?$', 'index.php?' . self::QR_INPUT_VAR . '=1', 'top' );
	}

	public static function query_vars( $vars ) {
		$vars[] = self::ACTION_VAR;
		$vars[] = self::CODE_VAR;
		$vars[] = self::QR_INPUT_VAR;

		return $vars;
	}

	public static function is_edit_ticket( $user = null ) {
		if ( null != $user ) {
			$can = user_can( $user, 'edit_posts' );
		} else {
			$can = current_user_can( 'edit_posts' );
		}
		return apply_filters( 'maetic_is_edit_ticket', $can );
	}

	public static function http404() {
		status_header( 404 );
		nocache_headers();

		if ( '' != get_404_template() ) {
			include get_404_template();
		} else {
			add_filter(
				'body_class',
				function( $classes ) {
					$classes[] = 'maetic';
					return $classes;
				},
				10,
				1
			);
			include maetic_get_template( '404' );
		}

		exit();
	}

	public static function front_controller() {
		global $wp_query;
		$code_var   = $wp_query->get( self::CODE_VAR );
		$qr_input   = $wp_query->get( 'qr_input' );
		$action_var = $wp_query->get( self::ACTION_VAR );

		if ( $qr_input == '1' ) {
			if ( ! self::is_edit_ticket() ) {
				self::http404();
			}

			if (
				isset( $_GET['number-1'] )
				&& isset( $_GET['number-2'] )
				&& isset( $_GET['number-3'] )
				&& isset( $_GET['number-4'] )
			) {
				$code     = $_GET['number-1'] . $_GET['number-2'] . $_GET['number-3'] . $_GET['number-4'];
				$location = maetic_get_qr_url( "/$code" );
				wp_safe_redirect( $location, 302 );
			}

			add_filter(
				'body_class',
				function( $classes ) {
					$classes[] = 'code_imput';
					$classes[] = 'maetic';
					return $classes;
				},
				10,
				1
			);

			$wp_query->init_query_flags();

			include maetic_get_template( 'input-page' );

			die();
		}

		if ( $code_var ) {
			$code_var = maetic_code_strip( $code_var );

			add_filter(
				'body_class',
				function( $classes ) {
					$classes[] = 'code_info';
					$classes[] = 'maetic';
					return $classes;
				},
				10,
				1
			);

			remove_action( 'wp_head', '_wp_render_title_tag', 1 );

			if ( ! self::is_edit_ticket() ) {
				self::http404();
			}

			$order = Maetic_Order::get_order_from_ticket_id( $code_var );

			if ( empty( $order ) ) {
				self::http404();
			}

			if ( $action_var ) {

        if ( $_SERVER["REQUEST_METHOD"] != 'POST' ) {
					self::http404();
				}

				if ( empty( $_POST['maetic_code'] ) ) {
					self::http404();
				}

				$code = $_POST['maetic_code'];
				check_admin_referer( 'maetic_qr_' . $action_var . '_' . $code );

				$url = maetic_get_qr_url( '/' . $code );
				if ( $action_var == 'use' ) {
					$order->use_tickets( $_POST['count'] );
					wp_safe_redirect( $url );
				}
				if ( $action_var == 'revert' ) {
					$order->revert_tickets( $_POST['count'] );
					wp_safe_redirect( $url );
				}
			}

			include maetic_get_template( 'code-page' );

			die();
		}
	}
}

add_action('wp_mail_failed', function($err){
	var_dump($err);
}, 10, 1);
