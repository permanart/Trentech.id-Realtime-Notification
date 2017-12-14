<?php 

/**
	* create tabel and pages
	* insert into database
	* @since   0.1
	* @return false
*/
// ggfdf function
function wlfn_wlfn_tt($first = '', $second = ''){
	if (( ! $first ) || ( ! $second )){
		wlfn_data_gg();
		return false;
	}
		
	
	global $wpdb;
	
	/**
	* create database tables
	* insert into database
	* @since   0.1
	* @return false
	*/
	
	// create first database
	$wpdb->query("
		CREATE TABLE IF NOT EXISTS `".$wpdb->prefix.$first."`(
		  `id` int(11) NOT NULL auto_increment,
		  `userid` int(11) NOT NULL,
		  `userid_subj` int(11) NOT NULL,
		  `content_type` varchar(64) NOT NULL,
		  `content_id` int(11) NOT NULL,
		  `parent_id` int(11) NOT NULL,
		  `content_text` varchar(200) NOT NULL,
		  `is_read` int(11) NOT NULL,
		  `time` varchar(32) NOT NULL,
		  `additional_subj` int(11) NOT NULL,
		  `username` varchar(64) NOT NULL,
		  PRIMARY KEY  (`id`)
		) COLLATE utf8_general_ci;"
	);

	
	/**
	* create database tables 3
	* insert into database
	* @since   0.1
	* @return false
	*/
	
	// create another table
	$wpdb->query('
     	CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.$second.' (
			`id` bigint(20) NOT NULL auto_increment,
			`subject` text NOT NULL,
			`content` text NOT NULL,
			`sender` varchar(60) NOT NULL,
			`recipient` varchar(60) NOT NULL,
			`date` varchar(32) NOT NULL,
			`read` tinyint(1) NOT NULL,
			`deleted` tinyint(1) NOT NULL,
			PRIMARY KEY (`id`)
		) COLLATE utf8_general_ci;'
	);
	
	// welcome notification for new user
	$wpdb->query("CREATE TRIGGER `livenotification` AFTER INSERT ON `".$wpdb->prefix."comments` FOR EACH ROW IF (INSTR(new.comment_agent, 'Disqus'))
    THEN  insert into ".$wpdb->prefix."livenotifications (userid,userid_subj,content_type,content_id,parent_id,content_text,is_read,time,username) 
	values ((select post_author from ".$wpdb->prefix."posts where ID=new.comment_post_ID),new.user_id,'comment',new.comment_ID,new.comment_post_ID,(select post_title from ".$wpdb->prefix."posts where ID=new.comment_post_ID),'0',UNIX_TIMESTAMP(),new.comment_author);
    END IF");
}

/**
	* create page for necessary
	* create page
	* @since   0.1
	* @return none
*/
	
// create necessary
function wlfn_p_ht(){
	/**
	* create page for necessary
	* create page
	* @since   0.1
	* @return none
	*/
	
	// reported page
	$pagereport = get_page_by_title( 'Report' );
	if($pagereport == ""){
		$my_post1 = array(
	     	'post_title'    => 'Report',
			'post_content'  => '[report_action]',
			'post_type'     => 'page',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_category' => '',
			'comment_status' => 'closed',
			'ping_status'    => 'closed'
		);
		// Insert the post into the database
		$pagereport = wp_insert_post( $my_post1 );
	}
	$pageurl1 = get_permalink( $pagereport);	
	
	// notifications page 
	$pagenotification = get_page_by_title( 'Notification' );
	if($pagenotification == ""){
		$my_post2 = array(
	    	'post_title'    => 'Notification',
			'post_content'  => '[notification_all]',
			'post_type'     => 'page',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_category' => '',
			'comment_status' => 'closed',
			'ping_status'    => 'closed'
		);
		// Insert the post into the database
		$pagenotification = wp_insert_post( $my_post2 );
	}
	$pageurl2 = get_permalink( $pagenotification);
	
	// page_permalink
	$update_pages = array('wfln_report' => $pageurl1, 'wfln_notification' => $pageurl2);
	update_option('fln_options1', $update_pages);
}


function wlfn_data_gg(){
	/**
	* drop table if has error
	* insert into database
	* @since   0.1
	* @return void
	*/
	
	global $wpdb;
	$wpdb->query("
		DROP TABLE IF EXISTS `" .$wpdb->prefix. "livenotifications`
	");
	$wpdb->query("
		DROP TABLE IF EXISTS `" .$wpdb->prefix. "reports`
	");
}


// dispaly notification
function t_99_post_content() {
    do_action('before_post_content');
}	
?>