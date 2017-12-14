<?php 

/**
	* get all content
	* @since   0.1
	* @return void
*/
	
	
	
// get all content
function flnf_33_getall_content( $userid, $userid_subj, $content_id, $right_width, $request_status_class, $phrase1, $phrase2, $time1, $scrollpane_height, $full ){
	global $wpdb;
	
	$sql_rec = $wpdb->get_results("select * from " . $wpdb->prefix . "livenotifications where content_type='reports' and ((userid='".$userid."' and userid_subj='".$userid_subj."') or (userid='".$userid_subj."' and userid_subj='".$userid."'))");
	$numrows1 = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'livenotifications WHERE content_type="reports" AND ((userid="'.$userid.'" AND userid_subj="'.$userid_subj.'") or (userid="'.$userid_subj.'" AND userid_subj="'.$userid.'"))');
	
	$myoutput = '<div onclick="ln_back_to_messages('.$content_id.','.$scrollpane_height.');" class="ln_link" id="ln_reports_back_'.$content_id.'">Back to Messages</div>';
	
	if( $numrows1 > 0 ){
		$scrollpane_height = 230;
		$myoutput .= '<ul id="ulScroll" class="ln_scrollpane" style="height: '.$scrollpane_height.'px !important;">';
		
		$var = 1;
		foreach ( $sql_rec as $numrecord ){
			if( $var == '1' ){
				$myoutput.='<li class="lnpmbit">';
				$myoutput.= wlf_fatech_65_dat( $numrecord->userid_subj )
		         		. '<div style="float:left;width:'.$right_width.'px;"><div class="'.$request_status_class.'" ><p class="ln_sender_name">'
						. $numrecord->username
						. '</p>'
						. '<p class="ln_content">'
						. $numrecord->content_text
						. '</p>'
						. '<p class="ln_time">'.wlf_timezone_22( $numrecord->time ).'</p>'
						. '</div></div><div style="clear:both;"></div></li>';
			}else{
				$myoutput.='<li class="lnpmbit">';
				$myoutput.= wlf_fatech_65_dat( $numrecord->userid_subj )
		        		. '<div style="float:left;width:'.$right_width.'px;">
						<div class="'.$request_status_class.'" ><p class="ln_sender_name">'
						. $numrecord->username
						. '</p>'
						. '<p class="ln_content">'
						. $numrecord->content_text
						. '</p>'
						. '<p class="ln_time">'.wlf_timezone_22( $numrecord->time ).'</p>'
						. '</div></div><div style="border-top: 1px solid #DDD; margin-top: 4px;padding-top: 4px;"></div><div style="clear:both;"></div></li>';
			}
			$var++;
		}
		$myoutput.='</ul>';
	}
	return $myoutput;
}

?>