<?php 

/**
	* if new user logged show welcome notification
	* insert into database
	* @since   0.1
	* @return void
*/
	
// if new user logged show welcome notification
function create_wlfn_data_n(){
	global $wpdb, $current_user;
	
	/**
	* if new user logged show welcome notification
	* insert into database
	* @since   0.1
	* @return void
	*/
	
	// welcome notification for new user
	if ( is_user_logged_in() ){
		global $wpdb, $current_user;
		
		$current_user = wp_get_current_user();
		$c_r_id = $current_user->ID;
		$c_r_dn = $current_user->display_name;
		$total_nfication = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'livenotifications WHERE userid = "'.$c_r_id.'"' );
		
		if( $total_nfication < 1 ){
			$wpdb->query("INSERT INTO " . $wpdb->prefix . "livenotifications (id,userid,userid_subj,content_type,content_id,parent_id,content_text,is_read,time,additional_subj,username) 
			VALUES (NULL, '".$c_r_id."','1','notice','20','0', 'Welcome to ".get_bloginfo('name')."','0','".time()."','-1', '".$c_r_dn."')");
		}
	}
}
add_action('wp_head', 'create_wlfn_data_n');

?>