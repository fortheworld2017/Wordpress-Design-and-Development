<?php

class SetupFunctions{

	function install_emh(){
			
	} //install_emh Function

	function BeginConstruction(){
	
		global $pagenow;
		
		if ((is_super_admin()) && ($pagenow == 'nav-menus.php')) {
		
			wp_register_script('ehm-admin-script', plugins_url('/js/emh_functions.js', __FILE__), array('jquery'));
			wp_enqueue_script('ehm-admin-script');
			wp_localize_script('ehm-admin-script', 'currentjspath', array('pluginURL' => plugins_url( '' , __FILE__ )));
			
			
			wp_register_style('emh-admin-style', plugins_url('/css/emh_style.css', __FILE__));
			wp_enqueue_style('emh-admin-style');
			
		} //IF
	
	} 
	
} // Class

?>