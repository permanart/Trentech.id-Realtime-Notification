<?php 
/*
    Plugin Name: Frontent Notification
	Description: This Plugin will show notifications for comments, reply, report, admin notices etc..
	Author: Polite Rakib
	Version: 1.0
	Author URI: https://www.youtube.com/channel/UC0jqqQZgdcAQR9_O4cANJrg
	Author Social URI: http://facebook.com/politesrakib
	License: license purchased
*/

/* Copyright 2016 Rasel ( email : rakibislamri999@gmail.com ) */

// define avatar
define( 'PLUGIN_FILE_PATH',  plugin_dir_path( __FILE__ ) );
define( 'WFN_PLUGIN_DIR',    plugin_dir_url ( __FILE__ ) );
define( 'WFN_PLUGIN_IMG',    plugin_dir_url ( __FILE__ ). 'assets/img/' );
define( 'WFN_PLUGIN_CSS',    plugin_dir_url ( __FILE__ ). 'assets/css/' );
define( 'PLUGIN_CORE_DIR',   plugin_dir_path( __FILE__ ). 'includes/core/class-' );
define( 'PLUGIN_CORE_DlR',   plugin_dir_path( __FILE__ ). 'includes/core/'       );
define( 'PLUGIN_MENU_DIR',   plugin_dir_path( __FILE__ ). 'includes/core/admin/' );
define( 'PLUGIN_ACTION_DIR', plugin_dir_path( __FILE__ ). 'includes/init/'       );
define( 'PLUGIN_CSS_DIR',    plugin_dir_path( __FILE__ ). '/assets/css/'         );
define( 'PLUGIN_JS_DIR',     plugin_dir_path( __FILE__ ). '/assets/js/'          );
define( 'PLUGIN_IMG_DIR',    plugin_dir_path( __FILE__ ). '/assets/img/'         );
// text domain
define('wfln', 'TEXT_DOMAIN');	


// included plugin file
require_once( PLUGIN_CORE_DlR . 'loader.php' );

// Runs when plugin is activated and creates new database field
register_activation_hook(__FILE__,'plugin_active_wfn');
register_deactivation_hook(__FILE__,'plugin_deactive_wfn');

function plugin_active_wfn(){
    add_option('fln_options', defaults_wlfn_39());
	delete_option( 'ln_options' );
	delete_option( 'ln_options1' );
}

// plugin un istall action
function plugin_deactive_wfn(){
	wlfn_data_gg();
}

?>