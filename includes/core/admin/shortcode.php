<?php

/**
    * notificcation shortcode hook in admin
	* files
	* @since   0.1
	* @return none
*/


function wfln_4backend_shotic(){ ?>

<h2>Shortcodes</h2>
<p>Below you can find couple shortcodes you can implement in your website. Don't forget to replace <code>{user_id}</code> or, <code>{post_url}</code> with the USER ID and POST URL you are displaying their information. You can request more shortcodes, just on <a href="http://facebook.com/cryptic.pazzle" target="_blank">Facebook</a>.</p>
<table class="wp-list-table widefat striped scd wpc-table">
	<th>Code</th><th>Return</th>
	<tr><td><code>[notification_all]</code></td><td> Show all Notification. Use <code><?php echo esc_attr("<?php echo do_shortcode('[notification_all]'); ?>"); ?></code> to place it in a PHP template.</td></tr>
	<tr><td><code>[reports_all]</code></td><td> Show all Reported Message. Use <code><?php echo esc_attr("<?php echo do_shortcode('[reports_all]'); ?>"); ?></code> to place it in a PHP template.</td></tr>
	<tr><td><code>[send_notice]</code></td><td> Send Administrator Notices. Use <code><?php echo esc_attr("<?php echo do_shortcode('[send_notice]'); ?>"); ?></code> to place it in a PHP template.</td></tr>
	<tr><td><code>[report_action]</code></td><td> Send Report. Use <code><?php echo esc_attr("<?php echo do_shortcode('[report_action]'); ?>"); ?></code> or, Full Code: <code><?php echo esc_attr("<?php \$post_link = get_permalink(); echo do_shortcode('[report_pg var=\"action\" sub=\"'.\$post_link.'\"]'); ?>"); ?></code>to place it in a PHP template.</td></tr>
	<tr><td><code>[report_pg var="action" sub="{post_url}"]</code></td><td> Show report Url. Use <code><?php echo esc_attr("<?php echo do_shortcode('[report_pg var=\"action\" sub=\"{post_url}\"]'); ?>"); ?></code> to place it in a PHP template.</td></tr>
	<tr><td><code>[f_notification var="user" out="unseen"]</code></td><td> This will output user unread notifications . Use <code><?php echo esc_attr("<?php echo do_shortcode('[f_notification var=\"user\" out=\"unseen\"]'); ?>"); ?></code> to place it in a PHP template.</td></tr>
	<tr><td><code>[f_notification var="user" out="unseen" id="{user_id}"]</code></td><td> This will output specific user unread notifications . Use <code><?php echo esc_attr("<?php echo do_shortcode('[f_notification var=\"user\" out=\"unseen\" id=\"user_id\"]'); ?>"); ?></code> to place it in a PHP template.</td></tr>
	<tr><td><code>[f_notification var="user" out="ntc_url"]</code></td><td> Returns Notification Page Url. Use <code><?php echo esc_attr("<?php echo do_shortcode('[f_notification var=\"user\" out=\"ntc_url\"]'); ?>"); ?></code> to place it in a PHP template.</td></tr>
	<tr><td><code>[f_notification var="user" out="ntc_link"]</code></td><td> Returns Notification Page Link. Use <code><?php echo esc_attr("<?php echo do_shortcode('[f_notification var=\"user\" out=\"ntc_link\"]'); ?>"); ?></code> to place it in a PHP template.</td></tr>
	<tr><td><code>[f_notification var="user" out="ntc_l_count"]</code></td><td> Returns Notification Page Link with Counter. Use <code><?php echo esc_attr("<?php echo do_shortcode('[f_notification var=\"user\" out=\"ntc_l_count\"]'); ?>"); ?></code> to place it in a PHP template.</td></tr>
	<tr><td><code>[f_notification var="user" out="ntc_l_count" id="{user_id}"]</code></td><td> Returns Notification Page Link with Counter for specific user. Use <code><?php echo esc_attr("<?php echo do_shortcode('[f_notification var=\"user\" out=\"ntc_l_count\" id=\"user_id\"]'); ?>"); ?></code> to place it in a PHP template.</td></tr>
</table>
<?php 


}
?>