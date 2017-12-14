<?php 

/**
    * admin menu hook
	* files
	* @since   0.1
	* @return none
*/

// Hook for adding admin menus
function the_77_admin_menu(){
    global $wpdb, $current_user;
    
	/**
    	* mark all as read
		* files
		* @since   0.1
		* @return void
	*/
	
	if( isset($_GET['page']) && $_GET['page'] == 'all_notification'){
		$wpdb->update( $wpdb->prefix. "livenotifications", array( 'is_read' => 1 ), array( 'userid' => $current_user->ID ) );
	}
	
	$num_unread = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'reports WHERE `recipient` = "' . $current_user->ID . '" AND `read` = 0 AND `deleted` != "-1"' );
    /**
    	* count unread notifications
		* files
		* @since   0.1
		* @return void
	*/
	// count notifications
	$notifications = count($wpdb->get_results('select id from ' . $wpdb->prefix . 'livenotifications where userid = "'.$current_user->ID.'" AND is_read = "0"'));
	$item = ($num_unread + $notifications);
	
	if ( empty( $num_unread ) )
     	$num_unread = 0;
     
    $icon_url = WFN_PLUGIN_IMG. 'notification.png';
    // menu pages
	add_menu_page( __('Notifications','wfln'), __( 'Notification', 'wfln' )." <span class='update-plugins count-$item'><span class='plugin-count'>$item</span></span>", 'read', 'all_notification', 'notifications_wlfn_menu', $icon_url );
    	add_submenu_page( 'all_notification', __( 'All Notifications', 'wfln' ), __( 'All Notifications', 'wfln' )." <span class='update-plugins count-$notifications'><span class='plugin-count'>$notifications</span></span>", 'read', 'all_notification', 'notifications_wlfn_menu' );
    // administrator menus  
	if ( current_user_can('administrator') && is_user_logged_in() ){
		add_submenu_page( 'all_notification', __( 'Reports', 'wfln' ), __( 'Reports', 'wfln' )." <span class='update-plugins count-$num_unread'><span class='plugin-count'>$num_unread</span></span>", 'read', 'all_report', 'wfln_report' );
		add_submenu_page( 'all_notification', __( 'Send Notices', 'wfln' ), __( 'Send Notices', 'wfln' ), 'read', 'send_notice', 'admin_notice44_p' );
		add_submenu_page( 'all_notification', __( 'Shortcodes', 'wfln' ), __( 'Shortcodes', 'wfln' ), 'read', 'short_codes', 'wfln_4backend_shotic' );
		add_submenu_page( 'all_notification', __( 'Active Plugin', 'wfln' ), __( 'Active Plugin', 'wfln' ), 'read', 'active_plugin', 'wfln_4backend_menu' );
   }
}
add_action('admin_menu', 'the_77_admin_menu');


/**
    * function file loaded
	* function
	* @since   0.1
	* @return void
*/
// Load first function files
require_once( PLUGIN_MENU_DIR . 'item.php');

// Load function files
require_once( PLUGIN_MENU_DIR . 'shortcode.php');
?>