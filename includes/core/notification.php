<?php 

/**
    * notificcation hooks
	* files
	* @since   0.1
	* @return none
*/
// notification from here
function fln_09_livenotifications(){
	global $wpdb;
	// settng for notifications
	$options = get_option('fln_options');
	echo '<script type="text/javascript">
		var ln_timer;
		var update_interval = '.(max(20,$options['update_interval']) * 1000).'
		var base_url = "'.get_option("siteurl").'"	;
	</script>';
		
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$user_name = $current_user->user_login; 
	
	$ln_notificationcount = array();
	$ln_notificationcount['comment'] = count_9_user_livenotification($user_id,'comment');
	$ln_notificationcount['reports'] = count_9_user_livenotification($user_id,'reports');
	$ln_notificationcount['moderation'] = count_9_user_livenotification($user_id,'moderation');
	?>
<div id="fln_09_livenotifications" class="run_once">
    <div class="ln_topsec">
	
        <?php if($options['logo_url']){ ?>
            <a href="<?php echo get_site_url(); ?>"><img class="ln_logo" src="<?php echo $options['logo_url'];?>" height="40" /></a>
		<?php }
		$selectevento_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'eventodropdown order by order1 ASC');
		if( $selectevento_count > 0 ){
			echo '<a href="javascript:void(0);" id="eventodropdown" onclick="eventodropdown();" ><img src="' . plugins_url( 'images/drop_down.png' , __FILE__ ) . '"  style="width:17px;position:relative;float:left;top:12px;margin-left:-18px;" > </a>';
		} ?>
		
		<div id="menuOrder" style="display:none"><ul>
	    	<?php $selectevento_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'eventodropdown order by order1 ASC');
			$selectevento = $wpdb->get_results("select * from " . $wpdb->prefix . "eventodropdown order by order1 ASC");	
			if( $selectevento_count > 0 ){ 
	    		foreach ( $selectevento as $evantorec ){
					echo '<li>';
					echo '<a href="'.$evantorec->link.'">';
					echo '<div class="topProperties"><div class="top_image">
			     	     	<img src="'.$evantorec->logourl.'">
			     		</div>
						<div class="top_title">'.$evantorec->linkname.'</div>
					</div></a></li>';
				}
			} ?>
		</ul></div>
    <?php if (is_user_logged_in()){ ?>
        <?php if($options['ln_enable_userdropdown']){ ?>
            <script type="text/javascript">
	    		jQuery(document).ready(function(){
	     			ln_create_userpane();
				});
			</script>
		<?php } ?>
		
		<div class="welcomelink">
			<?php  global $wpdb;
			$options1 = get_option('fln_options1'); 
			$url2 = $options1["plink_award"]; 
			$currentuserid = get_current_user_id();
			
			// count points
			$countpoints = $wpdb->get_results("select SUM(cp_points) as rows_value from " . $wpdb->prefix . "countpoints where cp_uid='".$currentuserid."'");	
			foreach ( $countpoints as $row1243 ){
				$cp = $row1243->rows_value;
			} ?> 
			
			<a href="<?php echo $url2; ?>" class="popupctrl">
                <div class="pointmain">
					<div class="pointsub">
			     		<span>
				        	<?php if($cp!=0){
								echo $cp;
							}else{
								echo '0';
							} ?>
						</span>
						
			        	<span>
					    	<?php if($cp!=0){
						     	echo 'Points';
							}else{
								echo 'Points';
							} ?>
						</span>
			    	</div>
			    </div>
			</a>

            <?php if($options['ln_enable_userdropdown']){ ?>
	    		<div id="user-dropdown" class="popupbody popuphover"></div>
			  	<a id="userName1" onclick="ln_clickuser(event); return false;" class="popupctrl userdropdownlink" href="profile.php">
		    		<span><?php echo wlf_display_65_dat($current_user->ID); ?></span>
				</a>
				<a onclick="ln_clickuser(event); return false;" class="popupctrl userdropdownlink" href="profile.php" id="userName">
			    	<span style="position:absolute;margin-top:7px;color:#fff; text-transform: uppercase;" ><?php echo $user_name; ?></span>
				</a>
			<?php }else{ 
		     	echo $user_name; 
			} ?>
			
			<div class="socialdropdown2">
	    		<div id="socialIcons" class="">
		    		<a class="popupctrl" href="javascript:void(0);" id="SearchTop" onclick="customeSearch();"><span>Search</span></a>
				</div>
				
				<div id="mysearch1">
				<div>
				
				<?php if($options['ln_email'] == 'yes'){ ?>
			    <div class="emails" id="emails"><span>Email</span></div>
				<div id="email_form" style="display:none; border:1px solid #333; border-radius:2px; margin-top:10px;z-index;9999;right:0px;float:left;width:100%;max-width:295px;position:absolute;background:white;">
					<?php if(isset($_POST['send_email'])){
						$to = $_POST['email'];
						$subject = $_POST['subject'];
						$message = $_POST['message'];
						$headers = "From: rtraselbd@gmail" . "\r\n" ;
						mail( $to, $subject, $message, $headers );
					} ?>
					
					<table border="1" style="margin-left:10px;">
					<form method="post">
			    		<tr>
                         	<td>Email:</td>
							<td><input type="text" name="email" placeholder="Enter Email Address for send info" /></td>
						</tr>
						<tr>
				    		<td>Subject:</td>
							<td><input type="text" name="subject" value="Page Link" /></td>
						</tr>
						<tr>
			              	<td>Message:</td>
							<td><textarea name="message" style="width: 179px !important; height: 79px !important;"><?php echo get_permalink($ID); ?></textarea></td>
						</tr>
						<tr>
			     			<td colspan="2" align="center" style="text-align:center !important;"><input type="submit" class="btn lgrgButtons" name="send_email" value="Send Mail"  /></td>
						</tr>
					</form>
					</table>
				</div>
			<?php } ?>
        </div></div>
    </div>
		
		<div class="socialdropdownMenu">
    		<a class="popupctrl" href="javascript:void(0);" id="SearchTop" onclick="customeSearchmenu();"><span>Search</span></a>
	 		<?php global $wpdb;
		$options1 = get_option('fln_options1'); 
		$url2 = $options1["plink_award"]; 
		$currentuserid = get_current_user_id();
		
		// count points
		$countpoints = $wpdb->get_results("select SUM(cp_points) as rows_value from " . $wpdb->prefix . "countpoints where cp_uid='".$currentuserid."'");	
		foreach ( $countpoints as $row1243 ){
			$cp = $row1243->rows_value;
		} ?>
		<a href="<?php echo $url2; ?>" class="popupctrl">
	    	<div class="pointmain">
                <div class="pointsub">
		    		<span>
			    		<?php if($cp!=0){
							echo $cp;
						}else{
							echo '0';
						} ?>
					</span>
					
			     	<span>
				    	<?php if($cp!=0){
							echo 'points';
						}else{
							echo 'point';
						} ?>
					</span>
				</div>
            </div>
		</a>
		
		<div id="mysearchMenu" style="display:none" >
        <?php if($options['ln_swich_search']=='google'){
			if($options['ln_gocode']!=' '){
				echo stripslashes($options['ln_gocode']);
			}
		}else{
			echo '<div class="test" style="float:right; margin-right:10%; padding:5px 10px 9px 10px;background:#FFFFFF; width:305px; border: 1px solid #c5c5c5; margin-top:-13px;-webkit-box-shadow: 0 3px 8px rgba(0, 0, 0, .25);border-radius: 0px 0px 3px 3px; ">';
		     	get_search_form();
			echo '</div>';
		} ?>
        </div>
		 
		<div id="mysearch1Menu" style="display:none;">
            <div class="gbvbd">
				
				<?php if($options['ln_email']=='yes'){ ?>
			    		<div id="email_form" style="display:none;">
						<?php if(isset($_POST['send_email'])){
							$to = $_POST['email'];
							$subject = $_POST['subject'];
							$message = $_POST['message'];
							$headers = "From: rtraselbd@gmail.com" . "\r\n" ;
							mail($to,$subject,$message,$headers);
						} ?>
						
					<table border="1" class="table tdg">
						<form method="post" class="form s_form">
			     			<tr>
				    			<td>Email:</td>
								<td><input type="text" name="email" placeholder="Enter Email Address for send info" /></td>
                            </tr>
							
							<tr>
				    			<td>Subject:</td>
								<td><input type="text" name="subject" value="Page Link" /></td>
							</tr>
							
							<tr>
			    				<td>Message:</td>
								<td><textarea name="message"><?php echo get_permalink($ID); ?></textarea></td>
							</tr>
							
							<tr>
			    				<td colspan="2"><input type="submit" class="btn lgrgButtons" name="send_email" value="Send Mail"  /></td>
							</tr>
						</form>
					</table></div>
				<?php } ?>
			</div>
        </div></div>
	</div>
	
	
    <div id="toplinks" class="toplinks"><ul class="isuser">
        <input type="hidden" value="" id="pluginURL"/>
	    	<li class="ln_popupmenu " id="livenotifications">
		    	<a onclick="ln_fetchnotifications('comment',event); return false;" class="popupctrl " href="#">
			    	<img src="<?php echo get_template_directory_uri(); ?>/images/world.png" height="23" width="23" />
				<span class="livenotifications_num" style="<?php if ($ln_notificationcount['comment']) {?>visibility: visible;<?php }else{?>visibility: hidden;<?php }?>" id="livenotifications_num">
					<?php echo $ln_notificationcount['comment']; ?>
				</span>
				</a>
				<ul class="popupbody popuphover" id="livenotifications_list"></ul>
			</li>
			
			<li class="ln_popupmenu " id="livenotifications_reports" style="<?php if($options['enable_reports']){?>visibility: visible;<?php }else{?>visibility: hidden;width:0;<?php }?>">
		    	<a onclick="ln_fetchnotifications('reports',event); return false;" class="popupctrl" href="#">
			    	<img src="<?php echo get_template_directory_uri(); ?>/images/message_notification.png" height="23" width="23" />
					<span class="livenotifications_num_reports" style="<?php if($ln_notificationcount['reports']){?>visibility: visible;<?php }else{?>visibility: hidden;<?php }?>" id="livenotifications_num_reports"><?php echo $ln_notificationcount['reports']; ?></span>
				</a>
               <ul class="popupbody popuphover" id="livenotifications_list_reports"></ul>
			</li>
			
			<li class="ln_popupmenu " id="livenotifications_moderation" style="<?php if($options['enable_moderation'] &&  current_user_can('manage_options')){?>visibility: visible;<?php }else{?>visibility: hidden;width:0;<?php }?>">
		    	<a onclick="ln_fetchnotifications('moderation',event); return false;" class="popupctrl" href="#">
			    	<img src="<?php echo get_template_directory_uri(); ?>/images/moderation_notification.png" height="23" width="23" />
					<span class="livenotifications_num_moderation" style="<?php if($ln_notificationcount['moderation']){?>visibility: visible;<?php }else{?>visibility: hidden;<?php }?>" id="livenotifications_num_moderation"><?php echo $ln_notificationcount['moderation'];?></span>
				</a>
				<ul class="popupbody popuphover" id="livenotifications_list_moderation"></ul>
			</li>
		</ul>
    </div>
	
	
    <?php }else{ ?>
   
	<?php } ?>
  
<a href="#"class="ln_close"><i class="icon-double-angle-up"></i></a>
</div>
<a href="JavaScript:void(0);"class="ln_botsec"></a>
</div>
<?php
}




// count user notifications
$ln_usersettings_cache = array();
function count_9_user_livenotification( $userid, $type ){
	global $wpdb;

	if( $type == 'reports' ){
		$cond = " AND content_type = 'reports' ";
	}else if( $type == 'moderation' ){
		$cond = " AND substring(content_type,1,4) = 'mod_'";
	}else{
		$cond = " AND content_type <> 'reports' AND substring(content_type,1,4) <> 'mod_'";
	}
	$sql = "SELECT COUNT(id) AS num FROM " . $wpdb->prefix . "livenotifications WHERE userid = " . (int)$userid . " AND is_read = 0 ".$cond;
	$res = $wpdb->get_row($sql);
	
	return (!$res || empty($res->num)) ? 0 : (int)$res->num;
}

// add user notifications
function add_notification_table_wlfn( $userid_cause, $userid_target, $content_type, $content_id, $content_text, $parent_id = 0, $updatetime = 0, $username_cause = "", $status = "" ) {
	global $wpdb, $ln_usersettings_cache;
	$prefix = "";
	if( strlen($content_type) > 4 ){
		$prefix = substr($content_type,0,4);
	} 
	
	if (!isset($ln_usersettings_cache[$userid_target])) $ln_usersettings_cache[$userid_target] = wlf_7_fetech_drop($userid_target);


	$sql = "SELECT id,userid,userid_subj,additional_subj AS n, content_text FROM ". $wpdb->prefix ."livenotifications WHERE userid = ".(int)$userid_target." AND content_type = '".$content_type."' AND content_id = ".(int)$content_id." AND parent_id = ".(int)$parent_id."";

	$check = $wpdb->get_row($sql);
	if( $updatetime == 0 ){
		$updatetime = time();
	} 
	
	if ( $check && !empty($check) && $check->id > 0 ){
		// user already has a notification about this, lets add ours
		// if awaiting moderation count is 0 then remove this notification
		// else if old count is different with new count, then update
		if($content_type == "mod_comment" ){
			if((string)$content_text == "" || (string)$content_text == "0"){
				delete_11_livenotification($check->id);
			}else if($content_text != $check->content_text){
				$sql = "UPDATE " . $wpdb->prefix . "livenotifications
					SET time = '" . time() . "',
					content_text = '" . htmlspecialchars(($content_text)) . "',
					is_read = 0
					WHERE id = " . (int)$check->id;
				$wpdb->query($sql);
			}
		}
		
		// if status is spam, trash or delete then remove this notification
		// else update table
		elseif($content_type == "comment" || $content_type == "reply" || $content_type == "mention"){
			if ((string)$status == "1" || (string) $status == "approve"){
				$sql = "UPDATE " . $wpdb->prefix . "livenotifications
					SET time = '" . $updatetime . ",
					content_text = '" . htmlspecialchars(($content_text)) . "'
					WHERE id = " . (int)$check->id;
				$wpdb->query($sql);
			}else{
				delete_11_livenotification($check->id);
			}
		}
	}else{
		// Create new notification
		if( $content_type == "mod_comment" && (string)$status != "0" && (string)$status != "hold" ) return;
		if( $content_type == "comment" || $content_type == "reply" || $content_type == "mention" ){
			if((string)$status != "1" && (string)$status != "approve") {
				return;
			}
		}
		
		$is_red = 0;
		
		if( $content_type == "reports" ){
			$content_text = wlf_get_shorten($content_text,0);
		}
		
		$sql = "INSERT INTO " . $wpdb->prefix . "livenotifications
			(`userid`, `userid_subj`, `content_type`, `content_id`, `parent_id`, `content_text`, `is_read`, `time`, `additional_subj`, `username`) VALUES
			(" . (int)$userid_target
			. ", " . (int)$userid_cause
			. ", '" . $content_type
			. "', " . (int)$content_id
			. ", " .  (int)$parent_id
			. ", '" . htmlspecialchars(($content_text))
			. "', " . $is_red
			. ", '" . $updatetime
			. "', " . "0,'".$username_cause . "');";

		$wpdb->query($sql);
	}
	return true;
}


// mark read seen notifications
function wlf_upadte_notiifcation_1($userid,  $start=0, $count=-1, $full=false,$type) {
	global $wpdb;

	$update_ids = array(); 

	if($type == 'all'){
		return;
	}elseif($type == 'comment'){
		$cond = " AND  l.content_type <> 'reports' AND substring(l.content_type,1,4) <> 'frie' AND substring(l.content_type,1,4) <> 'mod_' ";
	}elseif($type == 'reports'){
		$cond = " AND l.content_type = 'reports'  ";
	}
	
	if($type == 'moderation'){
		$cond = " AND substring(l.content_type,1,4) = 'mod_'  ";
	}
	
	$sql = "SELECT l.* FROM " . $wpdb->prefix . "livenotifications AS l WHERE l.userid = " . (int)$userid . " ".$cond." ORDER BY l.is_read, l.id DESC";
	
	if ($start >= 0 && $count > 0) $sql .= " LIMIT ".(int)$start.", ".(int)$count;

	$res = $wpdb->get_results($sql);

	if (!$res || empty($res)) return ;
	
	foreach ($res as $notification) {
		if (!$notification->is_read) $update_ids[] = (int)$notification->id; // Set pulled notifications as red
	}
	
	if (!empty($update_ids)){
		$newids = implode(",",$update_ids);
		$sql = "UPDATE " . $wpdb->prefix . "livenotifications SET is_read = 1 WHERE id IN (" . $newids . ")";
		$wpdb->query($sql);
	}
}



// remove notifications
function wlf_remove_ntc_032($content_type, $userid, $userid_subj){
	global $wpdb;
	$sql = "DELETE FROM " . $wpdb->prefix . "livenotifications WHERE content_type = '".$content_type."' AND userid = ".$userid." AND userid_subj = ".$userid_subj ;
	return $wpdb->query($sql);
}



// delete reported messages
function wlf_pm_delte_ntc_032($pm_id){
	global $wpdb;
	
	// check if the sender has deleted this message
	$sender_deleted = $wpdb->get_var( 'SELECT `deleted` FROM ' . $wpdb->prefix . 'report WHERE `id` = "' . $pm_id . '" LIMIT 1' );

	// create corresponding query for deleting message
	if ( $sender_deleted == 1 ) {
		$query = 'DELETE from ' . $wpdb->prefix . 'report WHERE `id` = "' . $pm_id . '"';
		
	}else{
		$query = 'UPDATE ' . $wpdb->prefix . 'report SET `deleted` = "2" WHERE `id` = "' . $pm_id . '"';
	}
	$sql = "DELETE FROM " . $wpdb->prefix . "livenotifications WHERE content_type = 'reports' AND content_id = ".$pm_id ;
	if( $wpdb->query( $query ) ){
		$wpdb->query( $sql);
	}
}


// report reply actions
function wlf_pm_action_ntc_032($pm_id,$pm_text){
	global $wpdb, $current_user;

	$pm_parent = $wpdb->get_row("SELECT report.subject, report.sender, report.recipient FROM " . $wpdb->prefix . "report AS report WHERE report.id = " . $pm_id . "");
	$title = $pm_parent->subject;
	if(substr($title,0,3) != "Re:") $title = "Re:".$title;

	$userid_subj = $wpdb->get_var("SELECT ID FROM " . $wpdb->prefix . "users WHERE user_login = '".$pm_parent->sender."'");

	$new_message = array(
		'id' => NULL,
		'subject' => $title,
		'content' => $pm_text,
		'sender' => $pm_parent->recipient,
		'recipient' => $pm_parent->sender,
		'date' => current_time( 'mysql' ),
		'read' => 0,
		'deleted' => 0
	);
	// insert into database
	if ($wpdb->insert( $wpdb->prefix . 'reports', $new_message, array( '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d' ) ) ){
		add_notification_table_wlfn($current_user->ID, $userid_subj, 'reports', $wpdb->insert_id, $pm_text, 0, 0, $pm_parent->recipient);
	}
}



// userpoints fetech
function wlf_7_fetech_drop($userid){
	global $wpdb;
	$options = get_option('fln_options');
	$q = "SELECT enable_comment, enable_reply, enable_award,enable_reports, enable_friend,enable_moderation, enable_taguser FROM " . $wpdb->prefix . "livenotifications_usersettings WHERE userid = " . $userid;
	$res = $wpdb->get_row($q);
	
	if (!$res) return array(
		'enable_comment' => $options['enable_comment'],
		'enable_award' => $options['enable_award'],
		'enable_reply' => $options['enable_reply'],
		'enable_taguser' => $options['enable_taguser'],
		'enable_moderation' => $options['enable_moderation'],
		'enable_reports' => $options['enable_reports']
	);
	else return array(
		'enable_comment' => $res->enable_comment,
		'enable_award' => $res->enable_award,
		'enable_reply' => $res->enable_reply,
		'enable_taguser' => $res->enable_taguser,
		'enable_moderation' => $res->enable_moderation,
		'enable_reports' => $res->enable_reports
	);
}



?>