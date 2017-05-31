<?php
/* Google Maps
---------------------------------------------------------- */
class WPBakeryShortCode_Wyde_GMaps extends WPBakeryShortCode {
	
}

vc_map( array(
    'name' => __('Google Maps', 'wyde-core'),
    'description' => __('Google Maps block.', 'wyde-core'),
    'base' => 'wyde_gmaps',
    'controls' => 'full',
    'icon' =>  'wyde-icon gmaps-icon', 
    'weight'    => 900,
    'category' => __('Wyde', 'wyde-core'),
    'params' => array(
		array(
			'param_name' => 'values',
			'type' => 'param_group',
			'heading' => __( 'Addresses', 'wyde-core' ),
			'description' => __( 'Enter addresses for map.', 'wyde-core' ),
			'params' => array(
				array(
					'param_name' => 'address',
					'type' => 'textarea',
					'heading' => __( 'Address', 'wyde-core' ),					
					'description' => __( 'Enter text to display in the Info Window.', 'wyde-core' ),		                    
				),
				array(
					'param_name' => 'location',
					'type' => 'textfield',
					'heading' => __( 'Coordinates (optional)', 'wyde-core' ),					
					'description' => __( 'If you have the Google Maps coordinates, put it here to set the position of the marker on the map.', 'wyde-core' ),		                    
				)
			),
			'callbacks' => array(
				'after_add' => 'wyde_gmaps_addresses_added',
				'after_delete' => 'wyde_gmaps_addresses_deleted',
			)
        ),
        array(
        	'param_name' => 'gmaps',
	        'type' => 'wyde_gmaps',
	        'heading' => 'Maps',			        
	        'description' => __('Drag & drop markers to set your locations, map type and zoom level settings will also be used.', 'wyde-core')
        ),
        array(
        	'param_name' => 'height',
	        'type' => 'textfield',
	        'heading' => __( 'Map Height', 'wyde-core' ),			        
	        'admin_label' => true,
            'value' => '300',
	        'description' => __( 'Enter map height in pixels. Example: 300.', 'wyde-core' )
        ),
        array( 
			'param_name' => 'color', 
        	'type' => 'colorpicker',
            'heading' => __('Map Color', 'wyde-core'),                    
            'description' => __('Select map background color.', 'wyde-core'),
        ),
        array(
        	'param_name' => 'icon',
	        'type' => 'attach_image',
	        'heading' => __( 'Icon', 'wyde-core' ),			        
	        'description' => __( 'To custom your own marker icon, upload or select images from media library.', 'wyde-core' )
        ),
        array(
        	'param_name' => 'el_class',
	        'type' => 'textfield',
	        'heading' => __( 'Extra CSS Class', 'wyde-core' ),			        
	        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'wyde-core' )
        ),
    )
));