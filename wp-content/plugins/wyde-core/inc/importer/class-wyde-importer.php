<?php
if ( !class_exists( 'Wyde_WXR_Importer' ) ) {	
    require dirname( __FILE__ ) . '/class-wyde-wrx-importer.php';
}

if( !class_exists('Wyde_Importer') ) {

    class Wyde_Importer {


        function __construct(){

        }

        function Wyde_Importer(){
        	$this->__construct();
        }


        public function import_content( $data_file ){

            try{	
				
         
                // Begin import
        		ini_set( 'output_buffering', 'off' );
				ini_set( 'zlib.output_compression', false );
				if ( $GLOBALS['is_nginx'] ) {
					// Setting this header instructs Nginx to disable fastcgi_buffering
					// and disable gzip for this request.
					header( 'X-Accel-Buffering: no' );
					header( 'Content-Encoding: none' );
				}

				// Start the event stream.
				header( 'Content-Type: text/event-stream' );

				// Time to run the import!
				set_time_limit( 0 );

				// Ensure we're not buffered.
				wp_ob_end_flush_all();
				flush();

				$importer = new Wyde_WXR_Importer( array(
						'fetch_attachments' => true,
						'default_author'    => get_current_user_id(),
					)
				);

				$logger = new Wyde_WP_Importer_Logger_ServerSentEvents();
				$importer->set_logger( $logger );


				// Flush once more.
				flush();

				//$data_file = get_attached_file( $this->id );
				$err = $importer->import( $data_file );

                if ( is_wp_error( $err ) ) {
                	//echo $err->get_error_message();
					return false; 
				}
                return true;

            }catch(Exception $e){
                throw $e;
            }
        }

        public function import_widgets( $widget_file ){
            try{
                $data_file = $widget_file; 
                $widgets_json = wp_remote_get( $data_file );
                if($widgets_json && is_array($widgets_json)){
                	$widget_data = $widgets_json['body'];
					$import_widgets = $this->import_widget_data( $widget_data );
            	}
            }catch(Exception $e){
                throw $e;
            }
        }
        
        function import_widget_data( $widget_data ) {

        	try{

	            $json_data = $widget_data;
	            $json_data = json_decode( $json_data, true );

	            if( !is_array($json_data) ){
	            	return;
	            }

	            $sidebar_data = $json_data[0];
	            $widget_data = $json_data[1];

	            foreach ( $widget_data as $widget_data_title => $widget_data_value ) {
	                $widgets[ $widget_data_title ] = '';
	                foreach( $widget_data_value as $widget_data_key => $widget_data_array ) {
	                    if( is_int( $widget_data_key ) ) {
	                        $widgets[$widget_data_title][$widget_data_key] = 'on';
	                    }
	                }
	            }

	            unset($widgets[""]);

	            foreach ( $sidebar_data as $title => $sidebar ) {
	                $count = count( $sidebar );
	                for ( $i = 0; $i < $count; $i++ ) {
	                    $widget = array( );
	                    $widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
	                    $widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
	                    if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
	                        unset( $sidebar_data[$title][$i] );
	                    }
	                }
	                $sidebar_data[$title] = array_values( $sidebar_data[$title] );
	            }

	            foreach ( $widgets as $widget_title => $widget_value ) {
	                foreach ( $widget_value as $widget_key => $widget_value ) {
	                    $widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
	                }
	            }

	            $sidebar_data = array( array_filter( $sidebar_data ), $widgets );

	            $this->parse_import_data( $sidebar_data );

            }catch(Exception $e){
                throw $e;
            }
        }

        function parse_import_data( $import_array ) {
            global $wp_registered_sidebars;

            if( !is_array($import_array) ){
            	return;
            }

            try{

	            $sidebars_data = $import_array[0];
	            $widget_data = $import_array[1];
	            $current_sidebars = get_option( 'sidebars_widgets' );
	            $new_widgets = array();

	            foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :

	                foreach ( $import_widgets as $import_widget ){
	                    //if the sidebar exists
	                    if ( isset( $wp_registered_sidebars[$import_sidebar] ) ) {
	                        $title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
	                        $index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
	                        $current_widget_data = get_option( 'widget_' . $title );
	                        $new_widget_name = $this->get_new_widget_name( $title, $index );
	                        $new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

	                        if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
	                            while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
	                                $new_index++;
	                            }
	                        }
	                        $current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
	                        if ( array_key_exists( $title, $new_widgets ) ) {
	                            $new_widgets[$title][$new_index] = $widget_data[$title][$index];
	                            $multiwidget = $new_widgets[$title]['_multiwidget'];
	                            unset( $new_widgets[$title]['_multiwidget'] );
	                            $new_widgets[$title]['_multiwidget'] = $multiwidget;
	                        } else {
	                            $current_widget_data[$new_index] = $widget_data[$title][$index];
	                            $current_multiwidget = isset($current_widget_data['_multiwidget']) ? $current_widget_data['_multiwidget'] : false;
	                            $new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
	                            $multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
	                            unset( $current_widget_data['_multiwidget'] );
	                            $current_widget_data['_multiwidget'] = $multiwidget;
	                            $new_widgets[$title] = $current_widget_data;
	                        }

	                    }
	                }
	            endforeach;

	            if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {

	                update_option( 'sidebars_widgets', $current_sidebars );

	                foreach ( $new_widgets as $title => $content ){
	                	update_option( 'widget_' . $title, $content );
	                }                    

	                return true;
	            }

	        }catch(Exception $e){
                throw $e;
            }

            return false;
        }

        function get_new_widget_name( $widget_name, $widget_index ) {
            $current_sidebars = get_option( 'sidebars_widgets' );
            $all_widget_array = array( );
            foreach ( $current_sidebars as $sidebar => $widgets ) {
                if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
                    foreach ( $widgets as $widget ) {
                        $all_widget_array[] = $widget;
                    }
                }
            }
            while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
                $widget_index++;
            }
            $new_widget_name = $widget_name . '-' . $widget_index;
            return $new_widget_name;
        }

		public function import_revsliders( $data_dir ){

	        // Import Revslider
	        if( class_exists('RevSlider') && class_exists('UniteFunctionsRev') ) {

		        $rev_directory = $data_dir;

                if( !file_exists($rev_directory) ){
                    return;
                }

                $rev_files = array();

                $files = glob( $rev_directory . '*.zip' );

                if( is_array($files) ){

			        foreach( $files as $filename ) {
				        
	                    $filename = $rev_directory . basename($filename);

	                    if( file_exists($filename) ) {
				            $rev_files[] = $filename;
	                    }
			        }

			    }

				$slider = new RevSlider();
				foreach( $rev_files as $rev_file ) {

					$filepath = $rev_file;

					ob_start();
					$slider->importSliderFromPost(true, false, $filepath);
					ob_clean();
					ob_end_clean();
				}			


	        }

        }

        function revslider_clear_error($matches) { 
            $string = $matches[2]; 
            $length = strlen($string); 
            return 's:' . $length . ':"' . $string . '";'; 
        }

    }

}