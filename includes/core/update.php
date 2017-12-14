<?php 


/**
	* if update data
	* insert into database
	* @since   0.1
	* @return void
*/
	
//add in database  
if(isset($_POST['fln_update1'])){
	for($i=0;$i<count($_POST['eventologourl']);$i++){
		$insert_record = $wpdb->query("insert into " .$wpdb->prefix. "eventodropdown (logourl,linkname,order1,link) values('".$_POST['eventologourl'][$i]."','".$_POST['linkname'][$i]."','".$_POST['order'][$i]."','".$_POST['logolink'][$i]."')");
	}
}

if(isset($_POST['fln_update4'])){
	$selectuser_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'users WHERE ID!="1"');
	$selectuser = $wpdb->get_results("select * from " . $wpdb->prefix . "users where ID!='1'");	
	
	if( $selectuser_count > 0 ){
		foreach ( $selectuser as $selectuserrec ){
			$insert_record1 = $wpdb->query("insert into " .$wpdb->prefix. "livenotifications (userid,userid_subj,content_type,content_id,parent_id,content_text,is_read,time,additional_subj,username) values('".$selectuserrec->ID."','1','adminnotification','0','0','".$_POST['notification']."','0','".time()."','".$_POST['noti_time']."','admin')");
		}
		$_SESSION['succ'] = "Notification Added Successfully";
	}	
}


// update record
if(isset($_POST['fln_update2']) && $_POST['uprecord']=='uprecord'){
	for($i=0;$i<count($_POST['eventologourl']);$i++){
		$update_record = $wpdb->query("update " .$wpdb->prefix. "eventodropdown set logourl ='".$_POST['eventologourl'][$i]."',linkname='".$_POST['linkname'][$i]."',order1='".$_POST['order'][$i]."',link='".$_POST['logolink'][$i]."' where id='".$_POST['myid']."'");
		echo '<meta http-equiv="refresh" content="0; url='.admin_url( 'admin.php?page=wfln_4backend_menu', 'http' ).'/">';
	}
}

/**
	* add gg
	* insert into database
	* @since   0.1
	* @return void
*/
	
// add gg
if(isset($_POST['fln_update6'])){	
    $selectreward_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'rewardsystem WHERE reorder="'.$_POST['reorder'].'"');
	$selectreward = $wpdb->get_results("select * from " . $wpdb->prefix . "rewardsystem where reorder='".$_POST['reorder']."'");	
	
	if( $selectreward_count == 0 ){
	    $insert_record1 = $wpdb->query("insert into " .$wpdb->prefix. "rewardsystem (rew_image,numlist,type,retitle,remsg,repoint,reorder) values('".$_POST['rew_image']."','".$_POST['numlist']."','".$_POST['type']."','".$_POST['retitle']."','".$_POST['remsg']."','".$_POST['repoint']."','".$_POST['reorder']."')");
	}else{
		$_SESSION['error'] = "please insert another order for reward system";
	}
}


/**
	* update dd detail
	* insert into database
	* @since   0.1
	* @return void
*/
	
// update dd detail
if(isset($_POST['fln_update5'])){	
	$insert_record1 = $wpdb->query("update ".$wpdb->prefix. "rewardsystem set rew_image='".$_POST['rew_image']."',numlist='".$_POST['numlist']."',type='".$_POST['type']."',retitle='".$_POST['retitle']."',remsg='".$_POST['remsg']."',repoint='".$_POST['repoint']."',reorder='".$_POST['reorder']."' where reid='".$_POST['updateid']."'");
	echo '<meta http-equiv="refresh" content="0; url='.admin_url( 'admin.php?page=wfln_4backend_menu', 'http' ).'/">';
	exit;
}

/**
	* update option
	* insert into database
	* @since   0.1
	* @return void
*/
	

// update option
if(isset($_POST['fln_update'])){
	update_option('fln_options', fln_33updates_27());
}


/**
	* update settings on setting
	* insert into database
	* @since   0.1
	* @return void
*/
	
// update settings on setting
function fln_33updates_27(){
	$options = $_POST['fln_options'];
	$update_val = array(
	    'update_interval' => $options['update_interval'],
	    'max_age' => $options['max_age'],
	    'codex' => $options['codex'],
		'ln_swich_search'=> 'wordpress',
		'Notifications Settings' => 'notification'
    );
	return $update_val;
}

?>