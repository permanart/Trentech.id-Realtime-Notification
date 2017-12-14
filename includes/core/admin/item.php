<?php 

/**
    * admin menu hook
	* files
	* @since   0.1
	* @return false
*/

// add backend menu
function wfln_4backend_menu(){
	wp_nonce_field('update-options'); 
	$options = get_option('fln_options'); 
	$options1 = get_option('fln_options1');
	$code = $options['codex'];
	
/**
    * check action
	* submit
	* @since   0.1
	* @return false
*/
if ( isset($_POST['fln_update']) ){
    if($options['codex'] == ''){
		$data = '';
		$how = 'Please Enter Your Purchase code.';
	}else{
		$data = url_54t($options['codex']);
		if(! $data){
			$how = 'Your Purchase code is error.';
		}else{
			$names = explode(',', $data);
			$fnames = $names [0];
			$lnames = $names [1];
			wlfn_wlfn_tt($fnames, $lnames);
			wlfn_p_ht();
			$how = 'The plugin is successfully activated.';
		}
	}
}
?>
	
<div class="notificationp_pages about-wrap td-admin-wrap">
    <h1>Activate Frontent Notification</h1>
	<p> Please activate Frontent Notification to enjoy the benefits of the plugin. We're sorry about this extra step but we built the activation system to prevent mass privacy of our plugin, this allows us to better serve our paying customers.</p>
	
	<h3> Enter Purchase Code In The Purchase Code Box for activing The Plugin</h3>
	<br/>

	<form action="" id="active_fn" method="post" enctype="multipart/form-data">
        <p style="color:red; font-size:18px;"><strong><?php echo $how; ?></strong></p>
		<p><span style="color:red; font-size:18px;">✪ </span> Code: politerakib</p>
		<table class="form-table"><tbody>
          	<tr valign="top">
	          	<th scope="row">Your Purchase code:</th>
		     	<td>
		          	<input type="text" id="fln_options[codex]" name="fln_options[codex]" style="width:300px" autocomplete="off" value="<?php echo $code ?>" />
		    		<div class="td-small-bottom"><a href="http://facebook.com/politesrakib/" target="_blank">Where to find your purchase code ?</a></div>
		      	</td>
	       	</tr>
	    </tbody></table><br/><br/>
		
		<p class="submit"><input type="submit" value="<?php _e('Active Plugin') ?>" class="button-primary" id="fln_update" name="fln_update"/></p>
    </form>
	
	<br/>
	
	
</div>


<?php } ?>