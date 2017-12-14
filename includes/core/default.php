<?php 

/**
	* default functions
	* insert into database
	* @since   0.1
	* @return void
*/
	
	

// defaults settings for notifications
function defaults_wlfn_39(){
	$default = array(
	    'update_interval' => 30,	
	    'max_age' => 7,
	    'codex' => '',
		'ln_swich_search'=> 'wordpress',
		'Notifications Settings' => 'notification'
    );
return $default;
}


?>