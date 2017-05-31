<?php
/**
 * Locate a template and return the path for inclusion.
 */
function wyde_locate_template( $slug, $name = '' ) {

	$template = '';

	if ( $name ) {
		$template = locate_template( "{$slug}-{$name}.php" );
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php
	if ( ! $template ) {
		$template = locate_template( "{$slug}.php" );
	} 

    return $template;
}

/**
 * Get content template file, passing attributes and including the file.
 */
function wyde_get_template( $slug, $name, $args = array() ) {    

    $template_file = wyde_locate_template( $slug, $name );

    if ( ! empty( $args ) && is_array( $args ) ) {
        extract( $args );
    }

    if ( $template_file && file_exists( $template_file ) ) {
    	include( $template_file );
	}
}

/* Register body styles */
function wyde_add_body_style($handle, $src){
    global $wyde_body_stylesheets;
    if( !$wyde_body_stylesheets ){
        $wyde_body_stylesheets = array();
    }

    $wyde_body_stylesheets[$handle] = $src;    
}