<?php

/**
	* unnecessary only for more update version
	* insert into database
	* @since   0.1
	* @return void
*/
// fetech user avatar
function wlf_fatech_65_dat($userid){
	global $avatar_type;
	$options = get_option("fln_options");
	
	if($options['hide_avatar']) return "";
	$size = $options['ln_avatar_height'];
	
	if($userid){
		$local = get_usermeta($userid, 'avatar');
		if(!empty($local)){
			$newsiteurl=substr(get_option('siteurl'),0,22);
			$local = $newsiteurl.$local;
			$avatar_type = TYPE_LOCAL;
			return "<img alt='' src='{$local}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
		}elseif(empty($options['ln_default_avatar'])){
			$avatar_type = TYPE_GLOBAL;
		}
	}
	return get_avatar( $userid, $size,$options['ln_default_avatar'] );
}


// fetech display user avatar
function wlf_display_65_dat($userid){
	global $avatar_type;
	$options = get_option("fln_options");
	
	if($options['hide_avatar']) return "";
	$size = $options['ln_avatar_height'];
	
	if( $userid ){
		$local = get_usermeta($userid, 'avatar');
		if( !empty($local) ){
			$local = $newsiteurl.$local;
			$avatar_type = TYPE_LOCAL;
			return "<img alt='' src='{$local}' class='avatar avatar-{$size} photo avatar-default' height='30' width='{$size}' />";
		}elseif(empty($options['ln_default_avatar'])){
			$avatar_type = TYPE_GLOBAL;
		}
	}
	return get_avatar( $userid, $size,$options['ln_default_avatar'] );
}


// notiification warp_url
function wlf_url7_warp($url, $txt, $title = ""){
	$options = get_option('fln_options');
	if($title){
		$titles = $title;
	}else{
		$titles = $txt;
	}
	$links .= '<strong><a href="'.$url.'" class="extra" id="rel" title="'.$titles.'" >'.$txt.'</a></strong>';
	return $links;
}


// prune_notifications
function wlf_url47_prune(){
	global $wpdb;
	$options = get_option('fln_options');
	$maxage = TIMENOW - (60*60*24* intval($options['max_age']));

	$sql = "DELETE FROM " . $wpdb->prefix . "livenotifications WHERE `time` < " . intval($maxage);
	return $wpdb->query($sql);
}



// avatar strip suffix
function flnf_7_suffix($file){
	$parts = pathinfo($file);
	$base = basename($file, '.' . $parts['extension']);
	
	if(substr($base, -(strlen("avatar") + 1)) == ('-' . "avatar")) {
		$base = substr($base, 0, strlen($base) - (strlen("avatar") + 1));
	}
	if(substr($base, -(strlen("cropped") + 1)) == ('-' . "cropped")) {
		$base = substr($base, 0, strlen($base) - (strlen("cropped") + 1));
	}

	$f[BASE_FILE] = $parts['dirname'] . '/' . $base . '.' . $parts['extension'];
	$f[AVTR_FILE] = $parts['dirname'] . '/' . $base . '-' . "avatar" . '.' . $parts['extension'];
	$f[CROP_FILE] = $parts['dirname'] . '/' . $base . '-' . "cropped" . '.' . $parts['extension'];

	return $f;
}


// crop uploaded avatar
function flnf_7_crop( $user, $file ){
	list($w, $h, $type, $attr) = getimagesize(f_10_root() . $file);

	$image_functions = array(
		IMAGETYPE_GIF => 'imagecreatefromgif',
		IMAGETYPE_JPEG => 'imagecreatefromjpeg',
		IMAGETYPE_PNG => 'imagecreatefrompng',
		IMAGETYPE_WBMP => 'imagecreatefromwbmp',
		IMAGETYPE_XBM => 'imagecreatefromxbm'
	);

	$src = $image_functions[$type](f_10_root() . $file);
	$options = get_option("fln_options");
	if($src){
		$dst = imagecreatetruecolor($options['ln_avatar_height'], $options['ln_avatar_height']);
		imagesavealpha($dst, true);
		$trans = imagecolorallocatealpha($dst, 0, 0, 0, 127);
		imagefill($dst, 0, 0, $trans);
		$chk = imagecopyresampled($dst, $src, 0, 0, $_POST['x1'], $_POST['y1'], $options['ln_avatar_height'], $options['ln_avatar_height'], $_POST['w'], $_POST['h']);

		if($chk) {
			$parts = pathinfo($file);
			$base = basename($parts['basename'], '.' . $parts['extension']);
			$file = $parts['dirname'] . '/' . $base . '-' . 'cropped' . '.' . $parts['extension'];

			$image_functions = array(
				IMAGETYPE_GIF => 'imagegif',
				IMAGETYPE_JPEG => 'imagejpeg',
				IMAGETYPE_PNG => 'imagepng',
				IMAGETYPE_WBMP => 'imagewbmp',
				IMAGETYPE_XBM => 'imagexbm'
			);

			$image_functions[$type]($dst, f_10_root() . $file);

			// Save the new local avatar for this user.
			update_usermeta($user->ID, 'avatar', $file);

			imagedestroy($dst);
		}
	}
}


// if error shoe error in avatar
function flnf_7_avatr_7_output($usr){
	if( $usr->avatar_error ){
		printf("<div id='message' class='error fade' style='width: 100%%;'><strong>%s</strong> %s</div>", __('Upload error:', 'avatars'), $usr->avatar_error);
	}
	delete_usermeta($usr->ID, 'avatar_error');
}


// get avatar type
function w55_avatar_type(){
	global $avatar_type;

	switch($avatar_type){
		case TYPE_GLOBAL:	return __('Global', 'ln_notifications');
		case TYPE_LOCAL:	return __('Local', 'ln_notifications');
		default:			return __('Default', 'ln_notifications');
	}
}


// avatar directory
function f_10_root(){
	return substr(ABSPATH, 0, -strlen(strrchr(substr(ABSPATH, 0, -1), '/')) - 1);
}


// avatar uploads
function f_88_upload($user_id){
	$info = '';

	// Make sure WP's media library is available.
	if(!function_exists('image_resize')) include_once(ABSPATH . '/wp-includes/media.php');

	// Make sure WP's filename sanitizer is available.
	if(!function_exists('sanitize_file_name')) include_once(ABSPATH . '/wp-includes/formatting.php');

	// Valid file types for upload.
	$valid_file_types = array(
		"image/jpeg" => true,
		"image/pjpeg" => true,
		"image/gif" => true,
		"image/png" => true,
		"image/x-png" => true
	);

	// The web-server root directory.  Used to create absolute paths.
	$root = f_10_root();
	
	// Upload a local avatar.
	if(isset($_FILES['avatar_file']) && @$_FILES['avatar_file']['name']){	
		if($_FILES['avatar_file']['error']) $error = 'Upload error.';	
		else if(@$valid_file_types[$_FILES['avatar_file']['type']]){	
			//$path = trailingslashit("/wp-content/wp_custom_avatar");
			$path = trailingslashit("/wp_custom_avatar");
			
			$file = sanitize_file_name($_FILES['avatar_file']['name']);
			// Directory exists?
			if(!file_exists($root . $path) && @!mkdir($root . $path, 0755)) $error = __("Upload directory doesn't exist.", 'ln_notifications');
			else {
				// Get a unique filename.
				// First, if already there, include the User's ID; this should be enough.
				if(file_exists($root . $path . $file)) {
					$parts = pathinfo($file);
					$file = basename($parts['basename'], '.' . $parts['extension']) . '-' . $user_id . '.' . $parts['extension'];
				}

				// Second, if required loop to create a unique file name.
				$i = 0;
				while(file_exists($root . $path . $file) && $i < UPLOAD_TRIES) {
					$i++;
					$parts = pathinfo($file);
					$file = substr(basename($parts['basename'], '.' . $parts['extension']), 0, strlen(basename($parts['basename'], '.' . $parts['extension'])) - ($i > 1 ? 2 : 0)) . '-' . $i . '.' . $parts['extension'];
				}
				if($i >= UPLOAD_TRIES) $error = __('Too many tries to find non-existent file.', 'ln_notifications');

				$file = strtolower($file);

				// Copy uploaded file.
				if(!move_uploaded_file($_FILES['avatar_file']['tmp_name'], $root . $path . $file)) $error = __('File upload failed.', 'ln_notifications');
				else chmod($root . $path . $file, 0644);

				// Remember uploaded file information.
				$info = getimagesize($root . $path . $file);
				$info[4] = $path . $file;

			}
		}else $error = __('Wrong type.', 'ln_notifications');

		// Save the new local avatar for this user.
		if(empty($error)) update_usermeta($user_id, 'avatar', $path . $file);
	}

	// If there was an an error, record the text for display.
	if(!empty($error)) update_usermeta($user_id, 'avatar_error', $error);

	return $info;
}


// check avatr switch
function f_6_switch($chk, $default, $size = SCALED_SIZE){
	switch ($chk){
		case 'custom': return $default;
		case 'mystery': return urlencode(FALLBACK . "?s=" . $size);
		case 'blank': return includes_url('images/blank.gif');
		case 'gravatar_default': return "";
		default: return urlencode($chk);
	}
}


?>