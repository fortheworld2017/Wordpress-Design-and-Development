<?php

if( !class_exists('Wyde_Ajax_Search') ){
 

    class Wyde_Ajax_Search{


    	private $search_types = array('post', 'page', 'wyde_portfolio');

    	private $search_order = 'post_title';

    	private $search_content = false;

    	public $search_suggestions = 5;
    
        function __construct(){
            add_action('wp_ajax_wyde_search', array($this, 'get_search_results'));
            add_action('wp_ajax_nopriv_wyde_search', array($this, 'get_search_results'));
        }

        public function set_post_types( $post_types = array() ){        	
        	$this->search_types = $post_types;
        }

        public function set_search_suggestions( $items = false ){
        	$this->search_suggestions = $items;
        }

        function get_search_results()
	    {            
	    	$post_types = sanitize_text_field( $_POST['wyde_search_post_types'] );
	    	if( !empty($post_types) ){
	    		$this->set_post_types( explode(',', $post_types ) );
	    	}	

	    	$suggestions = intval( $_POST['wyde_search_suggestions'] );
	    	if( $suggestions ) {
	    		$this->set_search_suggestions( $suggestions );
	    	}	

		    $results = array();
		    $keyword = apply_filters('wyde_search_keyword', sanitize_text_field( $_POST['wyde_search_keyword'] ) );

		    if( !empty($keyword) )
		    {
			    $search = $this->get_search_objects();
			    foreach($search as $key => $object)
			    {
				    $posts_result = $this->posts($keyword, $object['name']);
				    if(sizeof($posts_result) > 0) {
					    $results[] = array(
                                'items' => $posts_result, 
                                'title' => $object['label'],
                                'name'  => $object['name']
                        );
				    }
			    }
			    echo json_encode($results);
		    }
            exit;
	    }
        function get_search_objects()
	    {

            $types = array();

            $post_types = $this->get_post_types();    	

		    foreach($post_types as $post_type)
		    {		
				    $show = in_array($post_type->name, $this->search_types);
				    if( $show ){
					    $types[] = array(
						    'name' => $post_type->name, 
						    'label' => 	$post_type->label
					    );
				    }
				
		    }
		    return $types;
	    }
        function get_post_types()
	    {
		    $post_types = get_post_types(array('_builtin' => false, 'exclude_from_search' => false), 'objects');
		    $post_types['post'] = get_post_type_object('post');
		    $post_types['page'] = get_post_type_object('page');
		    unset($post_types['wpsc-product-file']);
		    return $post_types;
	    }

        function posts( $keyword, $post_type = 'post' )
	    {
		    global $wpdb;
		
            $posts = array();           
        		
		    $order_results = (  $this->search_order != '' ? ' ORDER BY '. $this->search_order : '');
		
            $limit = intval( $this->search_suggestions );

            $results = array();
		
		    $query = "
			    SELECT 
				    $wpdb->posts.ID 
			    FROM 
				    $wpdb->posts
			    WHERE 
				    (post_title LIKE '%%%s%%' ". ( $this->search_content ? "or post_content LIKE '%%%s%%')":")") ." 
				    AND post_status='publish' 
				    AND post_type='". $post_type ."' 
				    $order_results 
			    LIMIT 0, %d";

		    $query = ( $this->search_content ? $wpdb->prepare($query, $keyword, $keyword, $limit ) : $wpdb->prepare($query, $keyword, $limit) );

		    $results = $wpdb->get_results( $query );

		    if(sizeof($results) > 0 && is_array($results) && !is_wp_error($results))
		    {
			    foreach($results as $result)
			    {
				    $item = $this->post_object($result->ID);
				    if($item){
					    $posts[] = $item; 
				    }
			    }
		    }

		    return $posts;
	    }

        function post_object($id) {
		
			$date_format = get_option( 'date_format' );
            $item = get_post( $id );
		
            if( $item )
		    {
			    $post_object = new stdclass();
			    $post_object->ID = $item->ID;
                $post_object->post_title = $item->post_title;
            
			    $thumb_id = get_post_thumbnail_id( $item->ID );
			    if( $thumb_id )
			    {
				    $thumb = wp_get_attachment_image_src( $thumb_id );
				    $post_object->post_image = isset($thumb[0]) ? esc_url( $thumb[0] ) : '';
			    }
						
			    $post_object->post_author = get_the_author_meta( 'display_name', $item->post_author );
			    $post_object->post_link = esc_url( get_permalink( $item->ID ) );
			    $post_object->post_date = get_the_date( $date_format,  $item->ID );
			    return $post_object;
		    }
		    return false;
	    }

    }

    new Wyde_Ajax_Search();

}