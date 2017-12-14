<?php 

/**
    * include function
	* function
	* @since   0.1
	* @return error
*/

//report action
function ww4_report_action(){ ?>
	<div class="reports warp">
     	<?php if ( $_REQUEST['page'] == 'report_send' && isset( $_POST['submit'] ) ){
			global $wpdb, $current_user;
			$current_user = wp_get_current_user();    
			$error   = "";
			$sender = $current_user->id;
			$total = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'reports WHERE `sender` = "' . $sender . '" OR `recipient` = "' . $sender . '"' );
			$subject = url_to_postid( $_POST['subject'] );
			$content = $_POST['content'];
				
			if( !empty( $subject )){
			    $already = $wpdb->get_var( 'SELECT id FROM ' . $wpdb->prefix . 'reports WHERE sender = "' . $current_user->id . '" AND subject = "'.$subject.'" ORDER BY date DESC LIMIT 1' );
			}
				
			if ( empty( $subject )){
				$error .= __('<p>Please enter post url / valid url to report.</p>');
			}elseif ( $already > 0 ){
				$error .= __('<p>You have already reported on this post.</p>');
			}
			
			if ( empty( $content )){
				$error .= __('<p>Please include the reasons of report.</p>');
			}
				
			if ( $error == "" ){
				$recipient = $wpdb->get_results( 'SELECT '.$wpdb->prefix.'users.ID FROM '.$wpdb->prefix.'users WHERE (SELECT '.$wpdb->prefix.'usermeta.meta_value FROM '.$wpdb->prefix.'usermeta WHERE '.$wpdb->prefix.'usermeta.user_id = '.$wpdb->prefix.'users.ID AND '.$wpdb->prefix.'usermeta.meta_key = "'.$wpdb->prefix.'capabilities") LIKE "%administrator%" ORDER BY '.$wpdb->prefix.'users.ID DESC' );
				foreach ( $recipient as $rec ){
		           	$new_report = array(
				   		'id' => NULL,
						'subject' => $subject,
						'content' => $content,
						'sender' => $sender,
						'recipient' => $rec->ID,
						'date' => time(),
						'read' => 0,
						'deleted' => 0
					);
					
				   	if(( $wpdb->insert( $wpdb->prefix . 'reports', $new_report, array( '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d' ))) && ( $rec->ID !== $sender )){
						$wpdb->query("INSERT INTO " . $wpdb->prefix . "livenotifications (id,userid,userid_subj,content_type,content_id,parent_id,content_text,is_read,time,additional_subj,username) 
						VALUES (NULL, '".$rec->ID."', '".$sender."', 'reports', '" .$wpdb->insert_id. "', '0', '".$subject."', '0', '".time()."', '0', '".get_the_author_meta('display_name', $sender)."')");
					}
				}
				
				$options1 = get_option('fln_options1'); 
				$url2 = $options1["wfln_report"]; 
				$redirect_link = $url2.'?page=report_send&success=true';
				header('Location:'.$redirect_link);
			}else{
				echo '<div class="error notice">'.$error.'</div>';
			}
		} 
		
		if($_REQUEST['page'] == 'report_send' && isset($_GET['success'])){
			echo '<div class="updated notice"><p>Your report has been submmitted.</p></div>';
		}else{ ?>
		<form method="post" action="" id="send-form">
	     	<table class="form-table report_action"><tbody>
		      	<?php if(isset( $_GET['subject'])){
					$subject = $_GET['subject'];
					$postid = url_to_postid( $subject );
				}else{
					$subject = $_REQUEST['subject'];
				}
				
				if(isset( $_GET['content'])){
					$content = $_GET['content'];
				} 
				
				if( isset( $_GET['subject']) && ( $_GET['subject'] == $_REQUEST['subject'] )){ ?>
                	<tr id="rrr">
	                	<th><p><?php _e('You\'re going to report' );?> “<a href="<?php echo $subject; ?>"><?php echo get_the_title($postid); ?></a>”</p></th>
					</tr>
				<?php }else{ ?>	
			    	<tr id="rrr">
			     		<th><p><?php echo get_bloginfo('name'); _e(" is a open place to share knowledge. Any one who is 'Author' can create post. There might be fake or spam post. Please report those post here. Admin panel will taka action about those within 24 hours."); ?></p></th>
					</tr>
					
					<tr>
	                	<th><label for="subject"><?php _e('Post url to Report', 'wfln'); ?></label></th>
				       	<td><input type="text" id="subject" name="subject" style="width: 100%;" autocomplete="off" value="<?php echo $subject; ?>"/></td>
					</tr>
				<?php } ?>
				
				<tr class="user-description-wrap">
		    		<th><label for="content">Reasons to report</label></th>
					<td>
				     	<textarea cols="4" rows="3" id="content" name="content" style="width: 100%;" placeholder="Why do you think this post shouldn't be on <?php bloginfo('name'); ?>?"><?php echo $content; ?></textarea>
					</td>
				</tr>
			</tbody></table>
			
			<p class="submit">
		       	<?php if( isset( $_GET['subject']) && ( $_GET['subject'] == $_REQUEST['subject'] )){ ?>
                	<input type="hidden" id="subject" name="subject" autocomplete="off" value="<?php echo $subject; ?>" />
				<?php } ?>
		     	<input type="hidden" name="page" value="report_send" />
				<input type="submit" name="submit" class="button-primary" value="<?php _e( 'Report Send', 'wfln' ) ?>" />
				<?php if( isset( $_GET['subject']) && ( $_GET['subject'] == $_REQUEST['subject'] )){ ?>
					<a class="button-secondary" href="<?php echo $_GET['subject']; ?>">Back</a>
				<?php } ?>
				
			</p>
		</form>
		<?php } ?>
	</div>
<?php  
}
add_shortcode( 'report_action', 'ww4_report_action' );

/**
    * include function
	* function
	* @since   0.1
	* @return error
*/


// Load function files
require_once( PLUGIN_ACTION_DIR . 'report-all.php');
require_once( PLUGIN_ACTION_DIR . 'admin.php');

?>