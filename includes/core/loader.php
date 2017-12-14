<?php

/**
    * register style cfunctions
	* fle
	* @since   0.1
	* @return none
*/

add_action( 'wp_enqueue_scripts', 'sctips_wfln' ); 
add_action( 'admin_enqueue_scripts', 'sctips_wfln' ); 
function sctips_wfln() {
	## Register css file
	wp_register_style( 'notification-wfln', WFN_PLUGIN_CSS.'notification.css' , array(), '', 'all' );
	wp_enqueue_style( 'notification-wfln' );
}


/**
    * load all functions
	* function
	* @since   0.1
	* @return error
*/

// Load function files
require_once( PLUGIN_CORE_DlR   . 'default.php'      );
require_once( PLUGIN_CORE_DIR   . 'notification.php' );
require_once( PLUGIN_MENU_DIR   . 'menues.php'       );
require_once( PLUGIN_CORE_DIR   . 'activate.php'     );
require_once( PLUGIN_CORE_DlR   . 'functions.php'    );
require_once( PLUGIN_CORE_DlR   . 'update.php'       );
require_once( PLUGIN_CORE_DIR   . 'show.php'         );
require_once( PLUGIN_CORE_DlR   . 'content.php'      );
require_once( PLUGIN_CORE_DIR   . 'shortcode.php'    );
require_once( PLUGIN_ACTION_DIR . 'report.php'       );

?>