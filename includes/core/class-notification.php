<?php

/**
    * notification types
	* files
	* @since   0.1
	* @return none
*/

// posts user details
function add_33userdetail_fn($post_ID){
	global $wpdb;

	if( !$post_info = get_post($post_ID) ){
		return false;
	}
		
	if( $post_info->post_type != 'post' ){
		return;
	}  
	
	$dimentions = get_option('gmt_offset');
	$userid = $post_info->post_author;
	$poster_name = $wpdb->get_var("SELECT display_name FROM $wpdb->users WHERE ID = $userid");
	$post_time = strtotime($post_info->post_date);
	$post_date = $post_time+(60*(60*(-($dimentions)) ));
	$post_status = $post_info->post_status;
	
	// insert record in count_reading table
	if( $post_status == 'publish' ){
		$wpdb->query("insert into ".$wpdb->prefix."count_reading (userid,postid,readtime,posttype) values('".$userid."','".$post_ID."','".$post_date."','post')");
		// count posts
		$count_post = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'count_reading WHERE userid="'.$userid.'" AND posttype="post"');
		
		// get reward system data
		$getpostreward = $wpdb->get_results("select * from " . $wpdb->prefix . "rewardsystem where type='post' ORDER BY `reid` ASC");	
		foreach ( $getpostreward as $getpostrewardrec ){	
    		$numlist = $getpostrewardrec->numlist;
			$repoint = $getpostrewardrec->repoint;
			$reorder = $getpostrewardrec->reorder;
			$type = $getpostrewardrec->type;
			$retitle = $getpostrewardrec->retitle;
			$remsg = $getpostrewardrec->remsg;
			$reid = $getpostrewardrec->reid;
			
			if( $numlist == $count_post ){
	    		// insert into point table
				$countpoints = $wpdb->query("insert into ".$wpdb->prefix."countpoints (cp_uid,cp_reportsid,cp_points,cp_time,cp_tasklist) values('".$userid."','".$post_ID."','".$repoint."','".$post_date."','".$reorder."')");
				// insert into point table
				$selectorder_count = $wpdb->get_var('SELECT COUNT(cp_tasklist) FROM ' . $wpdb->prefix . 'countpoints WHERE cp_uid="'.$userid.'"');
				$selectorder = $wpdb->get_results("select cp_tasklist from " . $wpdb->prefix . "countpoints where cp_uid='".$userid."'");	
				if( $selectorder_count > 0 ){
		    		$reclist = 0;
			    	foreach ( $selectorder as $selectorderrec ){	
		        		if( $reclist == 0 ){
				         	$order .="reorder!=".$selectorderrec->cp_tasklist;
				     	}else{
				     		$order .=" and reorder!=".$selectorderrec->cp_tasklist;
				    	}
				    	$reclist++;
			    	}
				}else{
			    	$order="1=1";
				}
				
				$selectdata1 = $wpdb->get_results("select * from " . $wpdb->prefix . "rewardsystem where ".$order." ORDER BY reorder ASC");	
				foreach ( $selectdata1 as $rank ){
					$rank_next = $rank->numlist.$rank->type;
				}
				
				//insert into notification table
				$selectoption_count = $wpdb->get_var('SELECT COUNT(enable_award) FROM ' . $wpdb->prefix . 'livenotifications_usersettings WHERE userid="'.$userid.'"');
				$selectoption = $wpdb->get_results("select enable_award from " . $wpdb->prefix . "livenotifications_usersettings where userid='".$userid."'");	
				if( $selectoption_count > 0 ){
					foreach ( $selectoption as $selectoptionrec ){
			        	$award = $selectoptionrec->enable_award;
					}
				}else{
			     	// setting for award
					$options = get_option('fln_options');
					$award = $options['enable_award'];
					if( $award == 'on' ){
						$award = '1';
					}else{
				    	$award = '0';
					}
				}
				
				if( $award == '1' ){
					$livesnotificationtable = $wpdb->query("insert into ".$wpdb->prefix."livenotifications (userid,userid_subj,content_type,content_id,content_text,is_read,time,username) values('".$userid."','".$userid."','postaward','".$reid."','".$remsg."','0','".time()."','".$rank_next."')");
				}
	    	}
	    }
	}
}
add_action('wp_insert_post', 'add_33userdetail_fn',10,1);



/**
    * notification types comment post
	* files
	* @since   0.1
	* @return none
*/

// comment post
function add_33userdetail_fn_comment($comment_ID){
	global $wpdb;

	if(!$post_info = get_comment($comment_ID)){
		return false;
	}
		
	$dimentions = get_option('gmt_offset');
	$commmentpostid = $post_info->comment_post_ID;
	$commentauthor = $post_info->comment_author;
	$current_user = wp_get_current_user();
	$userid = $current_user->ID;
	$post_time = strtotime($post_info->comment_date);
	$post_date = $post_time+(60*(60*(-($dimentions)) ));
	$post_status=$post_info->post_status;
	
	// insert record in count_reading table
	$wpdb->query("insert into ".$wpdb->prefix."count_reading (userid,postid,readtime,posttype) values('".$userid."','".$commmentpostid."','".$post_date."','comment')");
	// count comments
	$count_post = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'count_reading WHERE userid="'.$userid.'" AND posttype="comment"');
		
	// get reward system data
	$getpostreward = $wpdb->get_results("select * from " . $wpdb->prefix . "rewardsystem where type='comment' ORDER BY `reid` ASC");	
	foreach ( $getpostreward as $getpostrewardrec ){
		$numlist = $getpostrewardrec->numlist;
		$repoint = $getpostrewardrec->repoint;
		$reorder = $getpostrewardrec->reorder;
		$type = $getpostrewardrec->type;
		$retitle = $getpostrewardrec->retitle;
		$remsg = $getpostrewardrec->remsg;
		$reid = $getpostrewardrec->reid;
		
		if( $count_post == $numlist ){
		//insert into point table
		$countpoints = $wpdb->query("insert into ".$wpdb->prefix."countpoints (cp_uid,cp_reportsid,cp_points,cp_time,cp_tasklist) values('".$userid."','".$commmentpostid."','".$repoint."','".$post_date."','".$reorder."')");
		
			$selectorder_count = $wpdb->get_var('SELECT COUNT(cp_tasklist) FROM ' . $wpdb->prefix . 'countpoints WHERE cp_uid="'.$userid.'"');
			$selectorder = $wpdb->get_results("select cp_tasklist from " . $wpdb->prefix . "countpoints where cp_uid='".$userid."'");	
			
			if( $selectorder_count > 0 ){
				$reclist=0;
				foreach ( $selectorder as $selectorderrec ){	
			    	if( $reclist == 0 ){
						$order .="reorder!=".$selectorderrec->cp_tasklist;
					}else{
						$order .=" and reorder!=".$selectorderrec->cp_tasklist;
					}
					$reclist++;
				}
			}else{
				$order="1=1";
			}
			
			$selectdata1 = $wpdb->get_results("select * from " . $wpdb->prefix . "rewardsystem where ".$order." ORDER BY reorder ASC");	
			foreach ( $selectdata1 as $rank ){
					$rank_next = $rank->numlist.$rank->type;
			}
				
			//insert into notification table
			$selectoption_count = $wpdb->get_var('SELECT COUNT(enable_award) FROM ' . $wpdb->prefix . 'livenotifications_usersettings WHERE userid="'.$userid.'"');
			$selectoption = $wpdb->get_results("select enable_award from " . $wpdb->prefix . "livenotifications_usersettings where userid='".$userid."'");	
			if( $selectoption_count > 0 ){
				foreach ( $selectoption as $selectoptionrec ){
			        $award = $selectoptionrec->enable_award;
				}
			}else{
				// setting for award
				$options = get_option('fln_options');
				$award = $options['enable_award'];
			    if( $award == 'on' ){
			    	$award='1';
				}else{
					$award='0';
				}
			}
			
			if( $award == '1' ){
				//insert into notification table
				$livesnotificationtable = $wpdb->query("insert into ".$wpdb->prefix."livenotifications (userid,userid_subj,content_type,content_id,content_text,is_read,time,username) values('".$userid."','".$userid."','commentaward','".$reid."','".$remsg."','0','".time()."','".$rank_next."')");
			}
		}
	}
}
add_action('comment_post', 'add_33userdetail_fn_comment',10,2);

// send new topic notifications in bbpress
function bbpress_topic_wlfn(){	
    global $wpdb;
	
	// count forum type posts
	$selectpost_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'posts WHERE post_type="forum"');
	// get forum type posts
	$selectpost = $wpdb->get_results('select * from ' . $wpdb->prefix . 'posts where post_type="forum"');
	
	if( $selectpost_count > 0 ){
		foreach ( $selectpost as $selectpostrec ){
			$fetchtopics_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'posts WHERE post_parent="'.$selectpostrec->ID.'" AND post_type!="revision"');
			$fetchtopics = $wpdb->get_results("select * from " . $wpdb->prefix . "posts where post_parent='".$selectpostrec->ID."' and post_type!='revision'");	
			
			if( $fetchtopics_count > 0 ){
				foreach ( $fetchtopics as $fetchtopicsrec ){
					$fetchusergrpwise_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'users WHERE ID!="'.$selectpostrec->post_author.'"');
					$fetchusergrpwise = $wpdb->get_results("select * from " . $wpdb->prefix . "users where ID!='".$selectpostrec->post_author."'");
					
					if( $fetchusergrpwise_count > 0 ){
						foreach ( $fetchusergrpwise as $fetchusergrpwiserec ){
							$selectlivenoti_count = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'livenotifications WHERE userid="'.$fetchusergrpwiserec->ID.'" and userid_subj="'.$selectpostrec->post_author.'" AND content_id="'.$fetchtopicsrec->ID.'"');
							$selectlivenoti = $wpdb->get_results("select id from " . $wpdb->prefix . "livenotifications where userid='".$fetchusergrpwiserec->ID."' and userid_subj='".$selectpostrec->post_author."' and content_id='".$fetchtopicsrec->ID."'");
							
							if( $selectlivenoti_count == 0 ){
								$user_info = get_userdata( $selectpostrec->post_author );
								$wpdb->query("insert into " . $wpdb->prefix . "livenotifications (`userid`,`userid_subj`,`content_type`,`content_id`,`parent_id`,`content_text`,`time`,`username`) values ('".$fetchusergrpwiserec->ID."','".$fetchtopicsrec->post_author."','bbpressnotification','".$fetchtopicsrec->ID."','".$fetchtopicsrec->post_parent."','".$fetchtopicsrec->post_title."','".time()."','".$user_info->display_name."')");
							}
						}
					}
				}
			}
		}
	}
}
add_action('bbp_new_topic', 'bbpress_topic_wlfn');


/**
    * notification types reply
	* files
	* @since   0.1
	* @return none
*/

//  reply
function bbpress_reply_wlfn(){
	global $wpdb;
	
	// count topic type posts
	$selectpost_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'posts WHERE post_type="topic"');
	// get topic type posts
	$selectpost = $wpdb->get_results('select * from ' . $wpdb->prefix . 'posts where post_type="topic"');
	
	if( $selectpost_count > 0 ){
		foreach ( $selectpost as $selectpostrec ){
			$fetchtopics_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'posts WHERE post_parent="'.$selectpostrec->ID.'" AND post_type!="revision"');
			$fetchtopics = $wpdb->get_results("select * from " . $wpdb->prefix . "posts where post_parent='".$selectpostrec->ID."' and post_type!='revision'");	
			
			if( $fetchtopics_count > 0 ){
				foreach ( $fetchtopics as $fetchtopicsrec ){
					$fetchusergrpwise_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'users WHERE ID!="'.$fetchtopicsrec->post_author.'"');
					$fetchusergrpwise = $wpdb->get_results("select * from " . $wpdb->prefix . "users where ID!='".$fetchtopicsrec->post_author."'");
					
					if( $fetchusergrpwise_count > 0 ){
						foreach ( $fetchusergrpwise as $fetchusergrpwiserec ){
							$selectlivenoti_count = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'livenotifications WHERE userid="'.$fetchusergrpwiserec->ID.'" and userid_subj="'.$fetchtopicsrec->post_author.'" AND content_id="'.$fetchtopicsrec->ID.'"');
							$selectlivenoti = $wpdb->get_results("select id from " . $wpdb->prefix . "livenotifications where userid='".$fetchusergrpwiserec->ID."' and userid_subj='".$fetchtopicsrec->post_author."' and content_id='".$fetchtopicsrec->ID."'");
							
							if( $selectlivenoti_count == 0 ){
								$user_info = get_userdata( $fetchtopicsrec->post_author );
								$wpdb->query("insert into " . $wpdb->prefix . "livenotifications (`userid`,`userid_subj`,`content_type`,`content_id`,`parent_id`,`content_text`,`time`,`username`) values('".$fetchusergrpwiserec->ID."','".$fetchtopicsrec->post_author."','bbpressnotificationreply','".$fetchtopicsrec->ID."','".$fetchtopicsrec->post_parent."','".$fetchtopicsrec->post_title."','".time()."','".$user_info->display_name."')");
							}
						}
			     	}
				}	
			}
		}
	}
}
add_action('bbp_new_reply','bbpress_reply_wlfn');

/**
    * notification types comment reply
	* files
	* @since   0.1
	* @return none
*/

require_once( PLUGIN_CORE_DIR   . 'welcome.php'      );
function add_cmt_notification_wlfn( $comment_ID, $comment_approved ){
	global $wpdb;
	
	if ( !$comment = get_comment($comment_ID) ){
		return false;
	}
	
	$dimentions = get_option('gmt_offset');
	$comments_waiting = $wpdb->get_var("SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'");
	$post_info = get_post($comment->comment_post_ID);
	$userid = $comment->user_id;
	$commenter_name = $comment->comment_author;
	$comment_time = strtotime($comment->comment_date);
	$comment_date = $comment_time+(60*(60*(-($dimentions)) ));
	$comment_approval = $comment->comment_approved;
	$comment_parent = $comment->comment_parent;
	$ln_post = $wpdb->get_row( "SELECT user_id,comment_content FROM " . $wpdb->prefix . "comments WHERE comment_ID = '".$comment_parent."'" );


	if( $comment_approval == 0 ){
		$moderator_ids = $wpdb->get_results( "SELECT $wpdb->users.ID FROM $wpdb->users WHERE (SELECT $wpdb->usermeta.meta_value FROM $wpdb->usermeta WHERE $wpdb->usermeta.user_id = wp_users.ID AND $wpdb->usermeta.meta_key = 'wp_capabilities') LIKE '%administrator%'" );
		
		$is_noadmin = true;
		foreach($moderator_ids as $m_id){
			add_notification_table_wlfn(0, $m_id->ID, 'mod_comment', 0, $comments_waiting,$comment->comment_post_ID, $comment_date , $commenter_name ,$comment_approved);
			if($m_id->ID == $post_info->post_author) $is_noadmin = false;
		}
		
		if ( user_can( $post_info->post_author, 'edit_comment', $comment_ID) && $is_noadmin )
			add_notification_table_wlfn(0, $post_info->post_author, 'mod_comment', 0, $comments_waiting, $comment->comment_post_ID, $comment_date , $commenter_name ,$comment_approved);
	}


 	if ( ($comment_parent == 0) && ($comment_approval == 1) ){
		if ($userid != $post_info->post_author){
			// get reply comment
			add_notification_table_wlfn($userid, $post_info->post_author, 'comment', $comment_ID, $post_info->post_title, $comment->comment_post_ID, $comment_date , $commenter_name ,$comment_approved);
		}
	}
	
	if ( ($comment_parent > 0) && ($comment_approval == 1) ){
		// get reply author
		$ln_post = $wpdb->get_row( "SELECT user_id,comment_content FROM " . $wpdb->prefix . "comments WHERE comment_ID = '".$comment_parent."'" );
		if (( $ln_post->user_id ) > 0 && ( $ln_post->user_id != $userid )){
			add_notification_table_wlfn($userid, $ln_post->user_id, 'reply', $comment_ID, $post_info->post_title, $comment->comment_post_ID, $comment_date , $commenter_name, $comment_approved);
		}
	}
}
add_action('comment_post', 'add_cmt_notification_wlfn',10,2);
add_action('wp_set_comment_status', 'add_cmt_notification_wlfn',11,2);

require_once( PLUGIN_CORE_DlR   . 'admin-ntc.php'    );
require_once( PLUGIN_CORE_DlR   . 'notification.php' );
// remove notification
function remove_notification_table_wlfn($post_ID){
	add_post_notification_wlfn($post_ID, 0);
}
add_action('after_delete_post', 'remove_notification_table_wlfn',10,1);
add_action('trashed_post', 'remove_notification_table_wlfn',10,1);


// post notifications
function add_post_notification_wlfn($post_ID, $status = 1){
	global $wpdb;

	if(!$post_info = get_post($post_ID))
		return false;
	if($post_info->post_type != 'post') return; 
	$userid = $post_info->post_author;
	$poster_name = $wpdb->get_var("SELECT display_name FROM $wpdb->users WHERE ID = $userid");

	$post_date = strtotime($post_info->post_date);
}
add_action('untrashed_post', 'add_post_notification_wlfn',10,1);
add_action('wp_insert_post', 'add_post_notification_wlfn',10,1);

?>