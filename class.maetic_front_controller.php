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

    public static function is_edit_ticket( $user=null ) {
        if ( null != $user ) {
            $can = user_can( $user, 'edit_posts' );
        }else{
            $can = current_user_can( 'edit_posts' );
        }
        return apply_filters( 'maetic_is_edit_ticket', $can );
    }

    public static function front_controller() {
        global $wp_query;
        $code_var = $wp_query->get( self::CODE_VAR );
        $qr_input = $wp_query->get( 'qr_input' );

        if ( $qr_input == '1' ) {
            if ( ! self::is_edit_ticket() ) {
                status_header(404);
                nocache_headers();
                // include( get_query_template( '404' ) );
                if ( '' != get_404_template() )
                    include( get_404_template() );
                exit();
            }

            if (
                isset($_GET['number-1'])
                && isset($_GET['number-2'])
                && isset($_GET['number-3'])
                && isset($_GET['number-4'])
            ) {
                $code = $_GET['number-1'].$_GET['number-1'].$_GET['number-1'].$_GET['number-1'];
                $location = get_home_url() . "/qr/$code";
                wp_safe_redirect( $location, 302 );
            }

            var_dump(get_query_template('maetic/code_page'));
            var_dump(get_query_template('post'));
            var_dump(get_query_template('inc/template-tags.php'));

            $wp_query->init_query_flags();

            include( maetic_get_template( 'input-page' ) );

            die();
        }

        if ( $code_var ) {
            if ( ! self::is_edit_ticket() ) {
                status_header(404);
                nocache_headers();
                if ( '' != get_404_template() )
                    include( get_404_template() );
                exit();
            }

            if ( $_SERVER["REQUEST_METHOD"] == 'POST' ) {
                if ( empty( $_POST['maetic_code'] ) ) {
                    $wp_query->set_404();
                    status_header(404);
                    return;
                }
                $code = $_POST['maetic_code'];
                check_admin_referer( 'maetic_qr_'.$code );
            }

            include( maetic_get_template( 'code-page' ) );

            die();
        }
    }
}

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
    error_log($plugin_template_path);
    if ( empty( $template ) && file_exists( $plugin_template_path ) ) {
        $template = $plugin_template_path;
    }

    return apply_filters( "{$type}_template", $template, $type, $templates );
}

function maetic_get_template_part( $part ) {
    include( plugin_dir_path( __FILE__ ) . 'templates/' . $part . '.php' );
}
