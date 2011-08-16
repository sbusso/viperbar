<?php
/*
Plugin Name: ViperBar
Plugin URI: http://www.viperchill.com/wordpress-plugins/
Description: ViperBar adds an attractive bar to your site header, which you can use to increase blog or newsletter subscribers. It includes built in styling, Aweber & Feedburner integration, and conversion rate tracking.
Version: 2.0
Author: ViperChill
Author URI: http://www.viperchill.com
License: GPL2
	Copyright 2011  Glen Allsopp  (email : hq@viperchill.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Access the wp_ajax hook to process AJAX posts on form submissions.
add_action( 'wp_ajax_nopriv_viperbar_ajax', 'viperbar_submission_ajax' );
add_action( 'wp_ajax_viperbar_ajax', 'viperbar_submission_ajax' );

/*
	When a form is submitted on the Viperbar, it sends an AJAX post to this function. This function updates the option 		'viperbar_stats_submits' or 'viperbar_stats_submits_b', depending on split-testing. The  update is then reflected 
	in the user dashboard, under 'Stats', which includes a conversion rate of impressions to submits. This function 
	uses a nonce value to authenticate the post, so that external submits will not count.
*/
function viperbar_submission_ajax () {
	// Receive nonce to verify.
	$viperbar_ajax_nonce = $_POST['viperbar_submission_nonce'];
	$options = get_option( 'viperbar_options_general' );
	
	// Verify with nonce, and check that stats is not disabled.
    if ( ! wp_verify_nonce( $viperbar_ajax_nonce, 'viperbar-submit-nonce' ) || $options['stats_enabled'] == 'false' ) {
		die ( 'nonce error' );
	}
	
	//Figure out what type of form has been submitted
	$form_type = $_POST[ 'formtype' ];
	if ( $form_type == 'mailchimp' ) {
		$mailchimp_id = $options [ 'form_id_mailchimp' ];
		$mailchimp_list = $options [ 'form_id_mailchimp_list' ];
		require_once( WP_PLUGIN_DIR . '/viperbar/includes/mailchimp/inc/MCAPI.class.php' );
		$ViperBar_api = new MCAPI( $mailchimp_id );
		$merge_vars = $ViperBar_api->listMergeVars( $mailchimp_list );
		// Add inputs, like first name and last name, to the mailchimp values to add.
		if(!$ViperBar_api->errorCode) {
			$passable_merge_vars = array();
	
			if(sizeof($merge_vars) > 0) {
				foreach($merge_vars as $var) {
					$passable_merge_vars[$var['tag']] = $_REQUEST[$var['tag']];
				}
			}
		}
		$email_to_add = sanitize_email($_POST['EMAIL']);
		$retval = $ViperBar_api->listSubscribe($options['form_id_mailchimp_list'], $email_to_add, $passable_merge_vars);
	}
	
	// Figure out which statistic to update.
	$split_test_option = $_POST['split_test_option'];
	if ($options['split_testing_enabled'] == 'on' && $split_test_option == 'b') {
		$option_to_update = 'viperbar_stats_submits_b';
	} else {
		$option_to_update = 'viperbar_stats_submits';
	}
	// Add 1 to the current statistic, and update the option.
	$cur_submits = get_option( $option_to_update );
	if ($cur_submits >= 0) {
		update_option( $option_to_update , $cur_submits + 1);
	} else {
		// In the event that the option has not been set to 0 at start.
		update_option( $option_to_update , 1);
	}
	
	// Now we do the extended submit statistics.
	$post_id = $_POST[ 'postid' ];
	// Get timestamp.
	$timestamp = date("j - n - Y");
		
	$submit_stats = get_option( 'viperbar_submit_data' );
	if ($submit_stats) {
		$submit_stats .= ';' . $post_id . ',' . $timestamp;
	} else {
		$submit_stats = $post_id;
	}
	update_option ( 'viperbar_submit_data' , $submit_stats );
	
	// Finish the AJAX operation.
	die( 'success' );
}

if (is_admin()) {
	// If the user is currently in the admin panel, load the admin content.
	include_once( WP_PLUGIN_DIR . '/viperbar/admin/admin_main.php' );
} else { // Else, load the front-end content (no need for admin content here!)

	include_once( WP_PLUGIN_DIR . '/viperbar/client/front_main.php' );

}

// When the plugin is activated, we need to create default options variables.
register_activation_hook( __FILE__,'viperbar_activate' );
// When the plugin is deactivated, we delete the options, leaving the users' Wordpress installation like we found it.
register_deactivation_hook( __FILE__, 'viperbar_deactivate' );
?>