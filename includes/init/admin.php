<?php 


/**
    * admin notice send
	* function
	* @since   0.1
	* @return error
*/

// send admin notice action
function admin_notice44_p(){
	global $wpdb, $current_user;
	if ( is_user_logged_in() && current_user_can('administrator')){
	    $options1 = get_option('fln_options1'); ?>
	
	<div class="wrap"><h2>Send Notice</h2>		
	<ul class="rpul"><li>
		<?php if ( $_REQUEST['page'] == 'send_notice' && isset( $_POST['submit'] ) ){
			if(isset($_GET['success'])){
				if( $_GET['success'] == 'true'){
		    		$messge = 'Your Notice has been successfully Send.';
				}
			}
			
			$current_user = wp_get_current_user();   
			$error   = "";
			$sender = $current_user->ID;
			$content = $_POST['content'];
			
			if($_POST['recipient'] == "") {
				$recipient = array();
			}else {
				$recipient = $_POST['recipient'];
			}
			
			$recipient = array_map( 'strip_tags', $recipient );
			$recipient = array_map( 'esc_sql', $recipient );
			
			// remove duplicate and empty recipient
			$recipient = array_unique( $recipient );
			$recipient = array_filter( $recipient );
			
			if ( empty( $recipient ) ){
		    	$error .= __('<p>Please select a User.</p>');
			}
			
			if ( empty( $content ) ){
		    	$error .= __('<p>Please include the description.</p>');
			}
			
			if ( $error == "" ){
				foreach ( $recipient as $rec ){
					// Send notification to post author
					$wpdb->query("INSERT INTO " . $wpdb->prefix . "livenotifications (id,userid,userid_subj,content_type,content_id,parent_id,content_text,is_read,time,additional_subj,username) 
					VALUES (NULL, '".$rec."','1','notice','22','0', '".$content."','0','".time()."','0', '".get_the_author_meta('display_name',$rec)."')");
					
					$redirect_link2 = '?page=send_notice&success=true';
					header('Location:'.$redirect_link2);
				}
			}else{
				echo '<div class="error notice"><p>'.$error.'</p></div>';
			} 
		
		} 
		
		if($_REQUEST['page'] == 'send_notice' && isset($_GET['success'])){
			echo '<div class="updated notice"><p>You Notice has been successfully Send.</p></div>';
		}else{ ?>
	    <form method="post" action="" id="send-form">
	     	<table class="form-table">
		    	<tr>
		    		<th><label for="recipient"><?php _e( 'Recipient', 'ln_livenotifications' ); ?></label></th>
		     		<td><?php if( (isset( $_GET['recipient'])) && ($_GET['recipient'] == $_REQUEST['recipient']) ){
					    	$recipient = $_REQUEST['recipient'];
						}
						
						if( (isset( $_GET['content'])) && ($_GET['content'] == $_REQUEST['content']) ){
					    	$content = $_REQUEST['content'];
						} 
						
						// Get all users of blog
						$users = $wpdb->get_results("SELECT display_name,ID FROM $wpdb->users WHERE ID <> ".$current_user->ID." ORDER BY display_name ASC"); ?>
						<select name="recipient[]" multiple="multiple" size="5" style="width: 100%;">
		             		<?php foreach ( $users as $user ){
				              	if( (!empty( $recipient )) && ($user->ID == $recipient) ){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								echo "<option value='".$user->ID."' ".$selected.">".$user->display_name."</option>";
							} ?>
						</select>
					</td>
				</tr>
				
				<tr>
	       			<th><label for="content"><?php _e( 'Content', 'Wfln' ); ?></label></th>
					<td><textarea cols="4" rows="3" id="content" name="content" style="width: 100%;"><?php echo $content; ?></textarea></td>
				</tr>
			</table>
			
			<p class="submit">
		    	<input type="hidden" name="page" value="send_notice" class="button button-primary"/>
				<input type="submit" name="submit" class="button-primary" value="Send Notice" />
			</p>
		</form>
		<?php } ?>
		</li></ul>
	</div> <?php
	}
}
add_shortcode( 'send_notice', 'admin_notice44_p' );
?>