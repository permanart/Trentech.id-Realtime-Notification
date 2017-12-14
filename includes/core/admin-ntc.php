<?php 

/**
	* if any user role changed send admin notices
	* insert into database
	* @since   0.1
	* @return void
*/

function wfln_user_role_update( $user_id, $new_role ){
    global $wpdb;
	/**
	* if any user role changed
	* insert into database
	* @since   0.1
	* @return void
	*/
    $user_info = get_userdata( $user_id ); 
	
	// insert to ntc table
	$wpdb->query("INSERT INTO " . $wpdb->prefix . "livenotifications (id,userid,userid_subj,content_type,content_id,parent_id,content_text,is_read,time,additional_subj,username) 
	VALUES (NULL, '".$user_id."','1','notice','-2','-2', 'Your user-role has been updated to ".$new_role."','0','".time()."','-2', '".$user_info->display_name."')");	
} 
add_action( 'set_user_role', 'wfln_user_role_update', 10, 2);

?>