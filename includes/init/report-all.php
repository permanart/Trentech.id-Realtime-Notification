<?php
/**
    * reported lists
	* function
	* @since   0.1
	* @return false
*/

// report lists for admin only
function wfln_report(){
	global $wpdb, $current_user;
	if ( is_user_logged_in() && current_user_can('administrator')){
		$options1 = get_option('fln_options1');
		
		// if view message
		if ( isset( $_GET['action'] ) && 'view' == $_GET['action'] && !empty( $_GET['subject'] ) ){
			$id = $_GET['subject'];
			check_admin_referer( "lnpm-view_inbox_msg_$id" );
			
			// mark message as read
			$wpdb->update( $wpdb->prefix . 'reports', array( 'read' => 1 ), array( 'subject' => $id, 'recipient' => $current_user->ID, ) );
			
			// select message information
			$msg = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'reports WHERE `subject` = "'. $id .'" AND recipient = "'.$current_user->ID.'" AND deleted != "-1" ORDER BY date DESC' );
			?>
	        <div class="block_posts view_msg"><h2>View Reports</h2>		
		    	<ul class="rpul"><li>
		         	<p><a href="?page=all_report"><?php _e( 'Back to Report List' ); ?></a></p>
					
					<table class="widefat fixed" cellspacing="0">
				    	<tr>
				    		<th class="manage-column" width="20%"><?php _e( 'User' ); ?></th>
							<th class="manage-column"><?php _e( 'Reasons' ); ?></th>
							<th class="manage-column" width="15%"><?php _e( 'Time' ); ?></th>
						</tr>
						
						<?php foreach ( $msg as $view ) { 
					    	$view->user = $wpdb->get_var( "SELECT display_name FROM $wpdb->users WHERE ID = '$view->sender'" ); ?>
							<tr>
						    	<td><a href="<?php echo get_author_posts_url($view->sender); ?>"><?php echo $view->user; ?></a></td>
								<td><?php printf( __( '<p>%s</p>', 'wfln' ), nl2br( stripcslashes( $view->content ) ) ); ?></td>
								<td>
							    	<?php $dimentions = get_option('gmt_offset'); $msgs_date = $view->date;
									$message_time = $msgs_date+(60*(60*(-($dimentions)) ));
									echo date('d-m-y', $message_time).' at '.date('h:s', $message_time); ?>
								</td>
							</tr>
						<?php } ?>
						
						<tr>
			              	<th class="manage-column" width="20%"><?php _e( 'User', 'wfln' ); ?></th>
							<th class="manage-column"><?php _e( 'Reasons', 'wfln' ); ?></th>
							<th class="manage-column" width="15%"><?php _e( 'Time', 'wfln' ); ?></th>
						</tr>
					</table>
					
					<p class="submit">
					    <a class="button button-primary" href="<?php echo wp_nonce_url( "?page=all_report&action=delete&subject=$id", 'lnpm-delete_inbox_msg_' . $id ); ?>"><?php _e( 'Delete Report', 'wfln' ); ?></a>
						<a class="button button-secondary" href="<?php echo wp_nonce_url( "?page=all_report&action=remove&subject=$id", 'lnpm-remove_inbox_msg_' . $id ); ?>" onClick="return confirm('Are you sure want to Delete the post ?')"><?php _e( 'Delete Post', 'wfln' ); ?></a>
						<a class="button button-secondary" href="<?php echo get_post_permalink($id); ?>" target="_blank"><?php _e( 'View Post', 'wfln' ); ?></a>
					</p>
				
				</li></ul>
			</div>
	<?php return; }
	    
		// if mark messages as read
		if ( isset( $_GET['action'] ) && 'mar' == $_GET['action'] && !empty( $_GET['subject'] ) ) {
			$id = $_GET['subject'];
			
			if ( !is_array( $id ) ) {
				check_admin_referer( "lnpm-mar_inbox_msg_$id" );
				$id = array( $id );
			}else{
				check_admin_referer( "lnpm-bulk-action_inbox" );
			}
			
			$error = false;
	    	foreach ( $id as $msg_id ){
		    	// update record
				if ( $wpdb->update( $wpdb->prefix. "reports", array( 'read' => 1 ), array( 'subject' => $msg_id, 'recipient' => $current_user->ID ) )){
					$status = _n( 'Report marked as read.', 'Report marked as read', $n, 'wfln' );
				}else{
					$status = __( 'Error. Please try again.', 'wfln' );
				}
			}
		}
		
		// if delete post
		if ( isset( $_GET['action'] ) && 'remove' == $_GET['action'] && !empty( $_GET['subject'] ) ){
			$id = $_GET['subject'];
			
			$all_report = $wpdb->get_results( "select id,subject,sender,recipient from " . $wpdb->prefix . "reports WHERE subject = '".$id."' AND recipient = '".$current_user->ID."' AND deleted !='-1' " ); 
			$all_reported = $wpdb->get_results( "select id,subject,sender,recipient from " . $wpdb->prefix . "reports WHERE subject = '".$id."' AND recipient = '".$current_user->ID."' AND deleted !='-1' ORDER BY date DESC LIMIT 1" ); 
		
			if ( !is_array( $id ) ) {
		    	check_admin_referer( "lnpm-remove_inbox_msg_$id" );
				$id = array( $id );
			}else{
				check_admin_referer( "lnpm-bulk-action_inbox" );
			}
			
			$error = false;
			foreach ( $all_reported as $removeer ){
		    	$author_id = author_deta55ls($removeer->subject);
				$author = $wpdb->get_var( "SELECT display_name FROM $wpdb->users WHERE ID = '$author_id'" ); 
				
				// notify posy author
				$wpdb->insert( $wpdb->prefix. "livenotifications", array( 'id' => 'NULL', 'userid' => $author_id, 'userid_subj' => $current_user->ID, 'content_type' => 'notice', 'content_id' => $removeer->id, 'content_text' => '“'.get_the_title($removeer->subject).'” Post will deleted by Admin for Problem. Be careful, otherwise you will lost you authorship.', 'is_read' => 0, 'time' => time(), 'additional_subj' => 4, 'username' => $author  ));
			
				// remove specific post
			    wp_delete_post($removeer->subject);
			}
			
			foreach ( $all_report as $remove ){
		    	// username
				$sender = $wpdb->get_var( "SELECT display_name FROM $wpdb->users WHERE ID = '$remove->sender'" ); 
				
				// insert into live notifications table
				$wpdb->insert( $wpdb->prefix. "livenotifications", array( 'id' => 'NULL', 'userid' => $remove->sender, 'userid_subj' => $current_user->ID, 'content_type' => 'notice', 'content_id' => $remove->id, 'content_text' => '“'.get_the_title($remove->subject).'” Post has been remove by Admin from your Report. Thanks for your feedback.', 'is_read' => 0, 'time' => time(), 'additional_subj' => 3, 'username' => $sender ));
				
				// check if the sender has deleted this message
				$wpdb->query("DELETE FROM " .$wpdb->prefix. "reports WHERE recipient='". $current_user->ID ."' AND subject = '".$remove->subject."'");
				
				// check if the sender has deleted notification
				$wpdb->query("DELETE FROM " . $wpdb->prefix . "livenotifications WHERE content_type = 'reports' AND content_text = ".$remove->subject." AND userid ='". $current_user->ID ."'");
		 	}
			
			if ( $error ){
	     		$status = __( 'Error. Please try again.', 'wfln' );
			}else{
				$status = _n( 'Post deleted.', 'Post deleted.', count( $id ), 'wfln' );
			}
		}
		
		// if delete message
		if ( isset( $_GET['action'] ) && 'delete' == $_GET['action'] && !empty( $_GET['subject'] ) ){
			$id = $_GET['subject'];
			
			if ( !is_array( $id ) ) {
		    	check_admin_referer( "lnpm-delete_inbox_msg_$id" );
				$id = array( $id );
			}else{
				check_admin_referer( "lnpm-bulk-action_inbox" );
			}
			
			$error = false;
	    	foreach ( $id as $msg_id ) {
		    	// check if the sender has deleted this message
				$wpdb->update( $wpdb->prefix. "reports", array( 'deleted' => '-1' ), array( 'subject' => $msg_id, 'recipient' => $current_user->ID ) );
				
				// check if the sender has deleted notification
				$wpdb->query("DELETE FROM " . $wpdb->prefix . "livenotifications WHERE content_type = 'reports' AND content_text = ".$msg_id." AND userid ='". $current_user->ID ."'");
		 	}
			
			if ( $error ) {
	     		$status = __( 'Error. Please try again.', 'wfln' );
			}else{
				$status = _n( 'Report deleted.', 'Report deleted.', count( $id ), 'wfln' );
			}
		}
		
		// show all messages which from admin
		$msgs = $wpdb->get_results( "select rpt2.id,rpt2.subject,rpt2.sender,rpt2.recipient,rpt2.read,rpt2.date from " . $wpdb->prefix . "reports as rpt2 WHERE rpt2.id IN ( select Max(rpt.id ) from " . $wpdb->prefix . "reports as rpt where rpt.recipient = '" . $current_user->ID . "' AND rpt.deleted != '-1' GROUP BY rpt.subject ) ORDER BY rpt2.date DESC" ); ?>
		<div class="block_posts view_msg"><h2>Reported Messages</h2><ul class="rpul"><li>
		<?php if ( !empty( $status ) ){
			echo '<div id="message" class="updated fade"><p>', $status, '</p></div>';
		}
		
	   	if ( empty( $msgs ) ) {
	    	echo '<p>', __( 'You don\'t have any Reports.', 'wfln' ), '</p>';
		}else{
	    	$n = count( $msgs );
			$num_unread = 0;
			
			foreach ( $msgs as $msg ) {
		    	if ( !( $msg->read ) ) {
					$num_unread++;
				}
			}
			echo '<p>', sprintf( _n( 'You have %d reported message (%d unread).', 'You have %d reported messages (%d unread).', $n, 'wfln' ), $n, $num_unread ), '</p>';?>
			<form action="" method="get">
		    	<script>
				jQuery(document).ready(function(){
					//Jquery ceck all checkbox
					jQuery(".widefat #checkAll").live('click', function(){
						if (jQuery(".widefat #checkAll").is(':checked')){
							jQuery(".widefat input[type=checkbox]").each(function (){
								jQuery(this).prop("checked", true);
							});
						}else{
							jQuery(".widefat input[type=checkbox]").each(function (){
								jQuery(this).prop("checked", false);
							});
						}
					}); 
					
					//Jquery ceck all checkbox
					jQuery(".widefat #checkAlll").live('click', function(){
						if (jQuery(".widefat #checkAlll").is(':checked')){
							jQuery(".widefat input[type=checkbox]").each(function (){
								jQuery(this).prop("checked", true);
							});
						}else{
							jQuery(".widefat input[type=checkbox]").each(function (){
								jQuery(this).prop("checked", false);
							});
						}
					}); 
				});
				</script>
				
				<div class="tablenav">
			    	<select name="action">
			     		<option value="-1" selected="selected"><?php _e( 'Bulk Action' ); ?></option>
						<option value="delete"><?php _e( 'Delete' ); ?></option>
						<option value="mar"><?php _e( 'Mark As Read' ); ?></option>
					</select>
			     	<input type="submit" class="button-secondary" value="Apply" />
				</div>
				
				<table class="widefat fixed" cellspacing="0" width="100%">
		     		<tr>
			    		<th class="manage-column check-column"><input type="checkbox" name="checkAll" id="checkAll" /></th>
						<th class="manage-column" width="10%"><?php _e( 'Sender' ); ?></th>
						<th class="manage-column"><?php _e( 'Subject' ); ?></th>
						<th class="manage-column" width="20%"><?php _e( 'Date' ); ?></th>
					</tr>
				    	<?php foreach ( $msgs as $msg ){
					       	$msg->user = $wpdb->get_var( "SELECT display_name FROM $wpdb->users WHERE ID = '$msg->sender'" );
				     	?>
					<tr>
				     	<th class="check-column"><input type="checkbox" name="id[]" value="<?php echo $msg->id; ?>"/></th>
						<td><a href="<?php echo wp_nonce_url( "?page=all_report&action=view&subject=$msg->subject", 'lnpm-view_inbox_msg_' . $msg->subject ); ?>"><?php echo $msg->user; ?></a></td>
						<td><?php if ( $msg->read == 0 ){
				           	echo '<b><a href="', wp_nonce_url( "?page=all_report&action=view&subject=$msg->subject", 'lnpm-view_inbox_msg_' . $msg->subject ), '">', get_the_title($msg->subject), '</a></b>';
			         	}else{
							echo '<a href="', wp_nonce_url( "?page=all_report&action=view&subject=$msg->subject", 'lnpm-view_inbox_msg_' . $msg->subject ), '">', get_the_title($msg->subject), '</a>';
						} ?>
				    		<div class="row-actions">
					     		<span>
							    	<a href="<?php echo wp_nonce_url( "?page=all_report&action=view&subject=$msg->subject", 'lnpm-view_inbox_msg_' . $msg->subject ); ?>"><?php _e( 'View', 'wfln' ); ?></a>
								</span>
								<?php if ( $msg->read == 0 ){ ?>
						       		<span>
									| <a href="<?php echo wp_nonce_url( "?page=all_report&action=mar&subject=$msg->subject", 'lnpm-mar_inbox_msg_' . $msg->subject ); ?>"><?php _e( 'Mark As Read', 'wfln' ); ?></a>
								    </span>
								<?php } ?>
						       	<span class="delete">
						    		| <a class="delete" href="<?php echo wp_nonce_url( "?page=all_report&action=delete&subject=$msg->subject", 'lnpm-delete_inbox_msg_' . $msg->subject ); ?>"><?php _e( 'Delete', 'wfln' ); ?></a>
						     	</span>
					    	</div>
				    	</td>
				       	<td>
			    			<?php $dimentions = get_option('gmt_offset'); $msgs_date = $msg->date;
							$message_time = $msgs_date+(60*(60*(-($dimentions)) ));
							echo date('d-m-y', $message_time).' at '.date('h:s', $message_time); ?>
						</td>
					</tr>
					<?php } ?>
					<tr>
				     	<th class="manage-column check-column"><input type="checkbox" name="checkAlll" id="checkAlll" /></th>
						<th class="manage-column"><?php _e( 'Sender', 'wfln' ); ?></th>
						<th class="manage-column"><?php _e( 'Subject', 'wfln' ); ?></th>
						<th class="manage-column"><?php _e( 'Date', 'wfln' ); ?></th>
					</tr>
				</table>
				
				<?php wp_nonce_field( 'lnpm-bulk-action_inbox' ); ?>
				<input type="hidden" name="page" value="all_report"/>
			</form></li></ul>
       	<?php } ?>
		</div>
	<?php 
	}else{
		echo 'Error - not found or not available.';
	}
}
add_shortcode( 'reports_all', 'wfln_report' );

?>