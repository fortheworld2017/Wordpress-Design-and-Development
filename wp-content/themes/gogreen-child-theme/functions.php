<?php

function gogreen_child_after_setup_theme() {
    load_child_theme_textdomain( 'gogreen', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'gogreen_child_after_setup_theme' );

function gogreen_child_theme_enqueue_styles() {
    $theme_info = wp_get_theme('gogreen');
    $version = $theme_info->get( 'Version' );
    wp_enqueue_style( 'gogreen-child', get_stylesheet_directory_uri() . '/style.css', array( 'gogreen', 'gogreen-main', 'gogreen-icons' ), $version );
}
add_action( 'wp_enqueue_scripts', 'gogreen_child_theme_enqueue_styles' );

/* 
 *Add your custom icons to Icon Picker, this method will append your icons into Simple Line icon set.
 *Please don't forget to upload your font files to the host and add your icon CSS class to style.css.
 */
/*
function gogreen_add_simple_line_icons( $icons ){

    $custom_icons =  array(
		array( "custom-1" => "Custom 1" ),
		array( "custom-2" => "Custom 2" ),
		array( "custom-2" => "Custom 3" ), 
    );        
      
    return array_merge_recursive( $icons, $custom_icons );

}
add_filter( 'vc_iconpicker-type-simple_line', 'gogreen_add_simple_line_icons' );
*/


/* 
 *Add social icons to Social Media section in Theme Options.
 */
/*
function gogreen_child_get_social_icons( $icons ){

    $icons['fa fa-delicious'] =  'Delicious';
    $icons['fa fa-foursquare'] =  'Foursquare';
      
    return $icons;

}
add_filter( 'gogreen_get_social_icons', 'gogreen_child_get_social_icons' );
*/


/* 
 *Add social share buttons in blog posts.
 */
/*
function gogreen_get_blog_share_links( $links ){

    $links['fa fa-linkedin-square'] =  'https://www.linkedin.com/shareArticle?mini=true&url='.urlencode( get_permalink() ).'&title='.urlencode( get_the_title() );    
      
    return $links;

}
add_filter( 'gogreen_blog_share_links', 'gogreen_get_blog_share_links' );
*/

/* 
 *Add social share buttons in portfolios.
 */
/*
function gogreen_get_portfolio_share_links( $links ){

    $links['fa fa-linkedin-square'] =  'https://www.linkedin.com/shareArticle?mini=true&url='.urlencode( get_permalink() ).'&title='.urlencode( get_the_title() );    
      
    return $links;

}
add_filter( 'gogreen_portfolio_share_links', 'gogreen_get_portfolio_share_links' );
*/