<?php
/*
Plugin Name: GoGreen Demo Content
Plugin URI: http://www.wydethemes.com
Description: Demo content for GoGreen theme
Version: 1.0.1
Author: Wyde
Author URI: http://www.wydethemes.com
*/

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Current GoGreen Demo Content plugin version
 */
if ( ! defined( 'GOGREEN_PLUGIN_VERSION' ) ) {
	define( 'GOGREEN_PLUGIN_VERSION', '1.0.1' );
}

if( !class_exists('GoGreen_Demo_Content') ) {

    class GoGreen_Demo_Content {

        /**
		 * This method adds other methods to specific hooks within WordPress.
		 *
		 * @since     1.0.0
		 */
		public function __construct() {
			$this->constants();
			$this->init();
		}

		/**
		 * Defines plugin constants
		 *
		 * @since     1.0.0
		 */
		function constants() {        	
        	define('GOGREEN_PLUGIN_DIR', plugin_dir_path(__FILE__));
        	define('GOGREEN_PLUGIN_URI', plugin_dir_url(__FILE__));
		}

		/**
		 * Initialize the plugin by setting localization and loading public scripts
		 *
		 * @since     1.0.0
		 */
		function init() {
        	
			add_action('wp_ajax_gogreen_import', array($this, 'data_import'));
            add_action('wp_ajax_nopriv_gogreen_import', array($this, 'data_import'));

            if ( is_admin() && isset($_GET['page']) && $_GET['page'] == 'theme-options' ) {
                add_action( 'admin_enqueue_scripts', array($this, 'load_scripts') );
            }

		}

        function load_scripts(){
            
            wp_enqueue_style('gogreen-theme-options-style', GOGREEN_PLUGIN_URI .'css/theme-options.css', null, GOGREEN_PLUGIN_VERSION);
    
            wp_enqueue_script('gogreen-ajax-importer-script', GOGREEN_PLUGIN_URI .'js/ajax-importer.js', null, GOGREEN_PLUGIN_VERSION, true);
            
            wp_localize_script('gogreen-ajax-importer-script', 'gogreen_ajax_importer_settings', 
            array(
                'import_url' => admin_url( 'admin-ajax.php' ),
                'data_dir' => GOGREEN_PLUGIN_URI .'data/', 
                'messages' => array( 
                        'loading' => esc_html__('Importing...', 'gogreen-demo-content'),
                        'confirm_import_demo_content' => esc_html__('Are you sure you want to import demo content?', 'gogreen-demo-content'),
                        'can_not_import_demo_content' => esc_html__('There was something wrong on importer settings. Refresh this page and try again.', 'gogreen-demo-content')
                    )
                )
            );

        }

        function load_screen(){
        	?>
        	<div class="import-wrapper">
            	<h3>Select content types</h3>
                <div class="content-options">                        
                    <p><label for="task-posts"><input type="checkbox" id="task-posts" value="posts" checked="checked" class="noUpdate"><?php echo esc_html__('Posts', 'gogreen-demo-content'); ?></label></p>
                    <p><label for="task-pages"><input type="checkbox" id="task-pages" value="pages" checked="checked" class="noUpdate"><?php echo esc_html__('Pages', 'gogreen-demo-content'); ?></label></p>
                    <p><label for="task-contact-forms"><input type="checkbox" id="task-contact-forms" value="contact-forms" checked="checked" class="noUpdate"><?php echo esc_html__('Contact Forms', 'gogreen-demo-content'); ?></label></p>
                    <p><label for="task-portfolios"><input type="checkbox" id="task-portfolios" value="portfolios" checked="checked" class="noUpdate"><?php echo esc_html__('Portfolios', 'gogreen-demo-content'); ?></label></p>
                    <p><label for="task-team-members"><input type="checkbox" id="task-team-members" value="team-members" checked="checked" class="noUpdate"><?php echo esc_html__('Team Members', 'gogreen-demo-content'); ?></label></p>
                    <p><label for="task-testimonials"><input type="checkbox" id="task-testimonials" value="testimonials" checked="checked" class="noUpdate"><?php echo esc_html__('Testimonials', 'gogreen-demo-content'); ?></label></p>                                            
                    <p><label for="task-sliders"><input type="checkbox" id="task-sliders" value="sliders" checked="checked" class="noUpdate"><?php echo esc_html__('Sliders', 'gogreen-demo-content'); ?></label></p>
                    <p><label for="task-menus"><input type="checkbox" id="task-menus" value="menus" checked="checked" class="noUpdate"><?php echo esc_html__('Menus', 'gogreen-demo-content'); ?></label></p>                
                    <p><label for="task-widgets"><input type="checkbox" id="task-widgets" value="widgets" checked="checked" class="noUpdate"><?php echo esc_html__('Widgets', 'gogreen-demo-content'); ?></label></p>
                    <p><label for="task-media"><input type="checkbox" id="task-media" value="media1,media2,media3" checked="checked" class="noUpdate"><?php echo esc_html__('Media', 'gogreen-demo-content'); ?></label></p>
                    <p><label for="task-settings"><input type="checkbox" id="task-settings" value="settings" checked="checked" class="noUpdate"><?php echo esc_html__('Settings', 'gogreen-demo-content') ?></label></p>    
                </div>
	            <h3>Choose a demo</h3>
	            <h4>Multi Pages</h4>
	            <div class="demo-content-list">
		            <a id="demo-1" href="#" class="demo-item"><img src="<?php echo esc_url( GOGREEN_PLUGIN_URI .'images/1.jpg' ); ?>" alt="<?php echo esc_html__('Main Demo', 'gogreen-demo-content'); ?>"/><strong><?php echo esc_html__('Main Demo', 'gogreen-demo-content'); ?></strong></a>
		            <a id="demo-2" href="#" class="demo-item"><img src="<?php echo esc_url( GOGREEN_PLUGIN_URI .'images/2.jpg'); ?>" alt="<?php echo esc_html__('Video Slider', 'gogreen-demo-content'); ?>"/><strong><?php echo esc_html__('Video Slider', 'gogreen-demo-content'); ?></strong></a>            
	            </div>
	            <h4>One Page</h4>
	            <div class="demo-content-list">
	           		<a id="demo-3" href="#" class="demo-item"><img src="<?php echo esc_url( GOGREEN_PLUGIN_URI .'images/3.jpg'); ?>" alt="<?php echo esc_html__('One Page', 'gogreen-demo-content'); ?>"/><strong><?php echo esc_html__('One Page', 'gogreen-demo-content'); ?></strong></a>  
	            </div>
            </div>
        	<?php
        }

        function data_import() {


            if ( class_exists('Wyde_Importer') && current_user_can( 'manage_options' ) && isset( $_GET['type'] ) && !empty($_GET['type']) ) {
               
                    $demo = isset( $_GET['demo'] )? $_GET['demo'] : '1';
                    $type = isset( $_GET['type'] )? $_GET['type'] : 'settings';                    
                    
                    $importer = new Wyde_Importer();
                   
                 try{

                    switch( strtolower($type) ){                        
                        case 'posts':
                            $importer->import_content( GOGREEN_PLUGIN_DIR .'data/'.$demo.'/posts.xml'); 
                            break;
                        case 'pages':
                            $importer->import_content( GOGREEN_PLUGIN_DIR .'data/'.$demo.'/pages.xml'); 
                            break;
                        case 'portfolios':
                            $importer->import_content( GOGREEN_PLUGIN_DIR .'data/'.$demo.'/portfolios.xml'); 
                            break;
                        case 'testimonials':
                            $importer->import_content( GOGREEN_PLUGIN_DIR .'data/'.$demo.'/testimonials.xml'); 
                            break;
                        case 'team-members':
                            $importer->import_content( GOGREEN_PLUGIN_DIR .'admin/data/'.$demo.'/team-members.xml'); 
                            break;
                        case 'contact-forms':
                            $importer->import_content( GOGREEN_PLUGIN_DIR .'data/'.$demo.'/contact-forms.xml'); 
                            break;
                        case 'widgets':
                            $importer->import_widgets( GOGREEN_PLUGIN_URI .'data/'.$demo.'/widget_data.txt');
                            break;
                        case 'sliders':
                            $importer->import_revsliders( GOGREEN_PLUGIN_DIR .'data/'.$demo.'/revsliders/');
                            break;
                        case 'menus':
                            $importer->import_content( GOGREEN_PLUGIN_DIR .'data/'.$demo.'/menus.xml');  
                            break;    
                        case 'media1':
                            $importer->import_content( GOGREEN_PLUGIN_DIR .'data/'.$demo.'/media1.xml');               
                            break;
                        case 'media2':
                            $importer->import_content( GOGREEN_PLUGIN_DIR .'data/'.$demo.'/media2.xml');               
                            break;
                        case 'media3':
                            $importer->import_content( GOGREEN_PLUGIN_DIR .'data/'.$demo.'/media3.xml');               
                            break;
                        case 'settings':                        
                            $this->default_settings($demo);
                            break;
                    }   

                    echo json_encode( array('code' => '1', 'key' => $type, 'message' => esc_html__('All done', 'gogreen-demo-content') ) );        

                } catch (Exception $e) {
                    echo json_encode( array('code' => '0', 'key' => $type, 'message' => esc_html__('There was an error.', 'gogreen-demo-content') .' '. $e ) );           
                }

            }else{
                echo json_encode( array('code' => '-1', 'message' => esc_html__('Cannot access to Administration Panel options.', 'gogreen-demo-content') ) );           
            }

            exit;
               
        }


        function default_settings( $demo = '1' ){
            

            try{

                $home_name = '';
                $primary_menu = 'Primary';

                switch ($demo) {
                    case '1':
                        $home_name = 'Home - Main Demo';
                        break;
                    case '2':
                        $home_name = 'Home - Video Slider';
                        break;   
                    case '3':
                        $home_name = 'Home';
                        break;    
                }


                $this->set_menu_locations($primary_menu);               

                // Settings -> Reading 
                $homepage = get_page_by_title( $home_name );

                if(isset( $homepage ) && $homepage->ID) {
                    update_option('show_on_front', 'page');
                    update_option('page_on_front', $homepage->ID); // Front Page
                }

                $posts_page = get_page_by_title( 'Blog' );
                if(isset( $posts_page ) && $posts_page->ID) {
                    update_option('page_for_posts', $posts_page->ID); // Blog Page
                }


                $this->set_woocommerce_pages();

                return true;

             } catch (Exception $e) {
                return false;           
            }
        }

        function set_menu_locations( $primary_menu = 'Primary'){
            // Set imported menus to registered theme locations
            $locations = get_theme_mod( 'nav_menu_locations' ); // get registered menu locations in theme
            $menus = wp_get_nav_menus(); // get registered menus

            if( $menus ) {
                foreach($menus as $menu) { // assign menus to theme locations
                    if( strpos($menu->name, $primary_menu)  !== false ) {
                        $locations['primary'] = $menu->term_id;
                    } else if( strpos($menu->name, 'Footer') !== false ) {
                        $locations['footer'] = $menu->term_id;
                    } else if( strpos($menu->name, 'Contact Info') !== false ) {
                        $locations['contact'] = $menu->term_id;
                    }
                }
            }

            set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations
        }

        function set_woocommerce_pages(){

            if( class_exists('WooCommerce') ) {
            
                // Set woocommerce pages
                $woopages = array(
                    'woocommerce_shop_page_id' => 'Shop',
                    'woocommerce_cart_page_id' => 'Cart',
                    'woocommerce_checkout_page_id' => 'Checkout',
                    'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',
                    'woocommerce_thanks_page_id' => 'Order Received',
                    'woocommerce_myaccount_page_id' => 'My Account',
                    'woocommerce_edit_address_page_id' => 'Edit My Address',
                    'woocommerce_view_order_page_id' => 'View Order',
                    'woocommerce_change_password_page_id' => 'Change Password',
                    'woocommerce_logout_page_id' => 'Logout',
                    'woocommerce_lost_password_page_id' => 'Lost Password'
                );

                foreach( $woopages as $page_name => $page_title) {
                    $woopage = get_page_by_title( $page_title );
                    if(isset( $woopage ) && $woopage->ID) {
                        update_option($page_name, $woopage->ID);
                    }
                }

                // No longer need to install woocommerce pages
                delete_option( '_wc_needs_pages' );
                delete_transient( '_wc_activation_redirect' );
            }

        }

    }

}