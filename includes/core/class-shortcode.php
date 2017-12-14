<?php

/**
    * notificcation shortcode hook anf function
	* files
	* @since   0.1
	* @return none
*/

class wfln_noti {
	/**
	 * Ouputs notification page link
	 * @param int $user_id provided user ID
	*/
	public function notification42_count( $user_id ){
		global $wpdb;
		if ( ! $user_id ){
			return false;
		}
		$tal = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'livenotifications WHERE userid="'.$user_id.'" AND is_read = "0"');
		$options1 = get_option('fln_options1'); 
		$data = $options1["wfln_notification"].'?seen=true'; 
		$url = '<a href="'.$data.'" class="notification"><i class="fa fa-bell fa-1x"></i> </a>';
		$cnt = '<class="notification_counts">'.$tal.'</a>';
		if($tal > 0){
			$cnt = '<class="notification_counts">'.$tal.'</a>';
		}else{
			$cnt = '';
		}
		echo $url.$cnt; 
	}
	
	/**
	 * Ouputs notification page link
	 * @param int $user_id provided user ID
	*/
	public function notification42_url( ){
		global $wpdb;
		$options1 = get_option('fln_options1'); 
		echo $options1["wfln_notification"].'?seen=true'; 
	}
	
	/**
	 * Ouputs notification page url
	 * @param int $user_id provided user ID
	*/
	public function notification42_link( ){
		global $wpdb;
		$options1 = get_option('fln_options1'); 
		$data = $options1["wfln_notification"].'?seen=true'; 
		echo '<a href="'.$data.'" class="notification">Notification</a>';
	}
	
	/**
	 * Ouputs notification unread
	 * @param int $user_id provided user ID
	*/
	public function notification44_unread( $user_id ){
		global $wpdb;
		if ( ! $user_id ){
			return false;
		}
		echo $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'livenotifications WHERE userid="'.$user_id.'" AND is_read = "0"');
	}
	
}


class wfln {
	/**
	 * Ouputs good
	 * @param int $user_id provided user ID
	*/
	public function online_statusds(){
		echo 'Good';
	}
	
}


add_shortcode('f_notification', function( $atts ){
	
/**
    * notificcation shortcode hook
	* files
	* @since   0.1
	* @return error
*/

    $wfln_noti = new wfln_noti;
	$wfln = new wfln;
	global $current_user;

	$a = shortcode_atts( array(
		'var' 		=> '',
		'out' 		=> '',
        'id' 		=> '',
    ), $atts );

	$var 	= esc_attr( "{$a['var']}" );
	$out 	= esc_attr( "{$a['out']}" );
	$id 	= esc_attr( "{$a['id']}" );

	if( $var == 'user' ){
		if( empty($id) && is_user_logged_in()){
			$ids = $current_user->ID;
		}else{
			$ids = $id;
		}
		if( empty($ids) ){
			if( $out == 'ntc_url' || $out == 'ntc_l_count' || $out == 'ntc_link' ){
				global $wpdb;
				$options1 = get_option('fln_options1'); 
				return '<a href="'.$options1["wfln_notification"].'?seen=true'.'" class="ntc_url">Notification</a>';
			}else{
				return 'Error - user not found';
			}
		}else{

			if( $out == 'unseen' ){
				return $wfln_noti->notification44_unread($ids);
			}
			
			if( $out == 'ntc_url' ){
				return $wfln_noti->notification42_url();
			}
			
			if( $out == 'ntc_link' ){
				return $wfln_noti->notification42_link();
			}
			
			if( $out == 'ntc_l_count' ){
				return $wfln_noti->notification42_count($ids);
			}
		}
	}
});

/**
    * notificcation shortcode hook
	* files
	* @since   0.1
	* @return error
*/

class wfln_report{
	/**
	 * Ouputs notification page link
	 * @param int $user_id provided user ID
	*/
	public function reportgg_action( $sub ){
		global $wpdb, $current_user;
		if ( ! $sub ){
			return false;
		}
		
		$subject = url_to_postid( $sub );
		$already = $wpdb->get_var( 'SELECT id FROM ' . $wpdb->prefix . 'reports WHERE sender = "' . $current_user->id . '" AND subject = "'.$subject.'" ORDER BY date DESC LIMIT 1' );
		
		if($already > 0){
			echo '<div class="reported"><span>Reported</span></div>';
		}else{
			$options1 = get_option('fln_options1'); 
			$data = $options1["wfln_report"].'?subject='.$sub; 
			$url = '<div class="report"><a id="rel" href="'.$data.'" class="report_actions">Report</a></div>';
			echo $url; 
		}
	}	
}


add_shortcode('report_pg', function( $atts ){
	
/**
    * notificcation shortcode hook
	* files
	* @since   0.1
	* @return error
*/

    $wfln_report = new wfln_report;
	global $wpdb, $current_user;

	$a = shortcode_atts( array(
		'var' 		=> '',
		'sub' 		=> '',
    ), $atts );

	$var 	= esc_attr( "{$a['var']}" );
	$sub 	= esc_attr( "{$a['sub']}" );

	if( $var == 'action' ){
		if( empty($sub)){
			return '<p>Error - Enter subject.</p>';
		}else{
			if( is_user_logged_in()){
		    	return $wfln_report->reportgg_action($sub);
	        }else{
				return '<div class="report"><a id="rel" href="'.wp_login_url($sub).'" class="report_actions">Report</a></div>';
			}	
		}
	}
});

?>