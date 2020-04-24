<?php

if ( !defined('ABSPATH') ) {
	die();
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

function maetic_get_random_value() {
	return random_int( 0, pow(10, 16) - 1 );
}

function maetic_get_qr_url( $path ) {
	return get_home_url() .'/qr'. $path;
}
