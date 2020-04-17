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
    error_log($plugin_template_path);
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
