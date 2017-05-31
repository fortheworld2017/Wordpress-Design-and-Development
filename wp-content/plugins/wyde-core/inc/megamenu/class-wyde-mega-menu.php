<?php

/** Wyde Mega Menu Edit class **/
require_once( WYDE_PLUGIN_DIR .'inc/megamenu/class-wyde-walker-mega-menu-edit.php' );

if ( ! class_exists( 'Wyde_Mega_Menu' ) ){

    class Wyde_Mega_Menu{
     
        function __construct() {
            add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_nav_fields' ) );
            add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields'), 10, 3 );
            add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker'), 10, 2 );
            add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts') );
        }

        function Wyde_Mega_Menu(){
            $this->__construct();
        }

        function load_admin_scripts(){
            // Icon Picker scripts
            wp_enqueue_style('wyde-font-awesome', WYDE_PLUGIN_URI. 'assets/css/font-awesome.min.css', null, '4.6.3');
            wp_enqueue_style('wyde-megamenu-style', WYDE_PLUGIN_URI. 'assets/css/wyde-megamenu.css', null, WYDE_VERSION);
            wp_enqueue_script('wyde-iconpicker-script', WYDE_PLUGIN_URI. 'assets/js/wyde-menu.js', array('jquery'), WYDE_VERSION, true);
        }

        function add_custom_nav_fields( $menu_item ) {
            $menu_item->icon = get_post_meta( $menu_item->ID, '_w_menu_icon', true );
            $menu_item->megamenu = get_post_meta( $menu_item->ID, '_w_megamenu', true );
            return $menu_item;
        }

        function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

            if ( isset( $_REQUEST['menu-item-megamenu'] ) && is_array( $_REQUEST['menu-item-megamenu'] ) ) {
                if( isset( $_REQUEST['menu-item-megamenu'][$menu_item_db_id] ) ){
                    $megamenu = $_REQUEST['menu-item-megamenu'][$menu_item_db_id];
                    update_post_meta( $menu_item_db_id, '_w_megamenu', $megamenu );
                }
            }
            if ( isset( $_REQUEST['menu-item-icon'] ) && is_array( $_REQUEST['menu-item-icon'] ) ) {
                if( isset( $_REQUEST['menu-item-icon'][$menu_item_db_id] ) ){
                    $icon = $_REQUEST['menu-item-icon'][$menu_item_db_id];
                    update_post_meta( $menu_item_db_id, '_w_menu_icon', $icon );
                }
            }

        }

        function edit_walker($walker,$menu_id) {            
	        return 'Wyde_Walker_MegaMenu_Edit';
	    }
    }

    new Wyde_Mega_Menu();

}