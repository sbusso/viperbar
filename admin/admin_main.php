<?php
add_action( 'admin_menu', 'viperbar_admin_actions' );
add_action( 'admin_init', 'viperbar_init');

function viperbar_init() {
	viperbar_admin_script();
	viperbar_admin_style();
}
/*
	Admin Script - Enqueue's the scripts for the admin section.
*/
function viperbar_admin_script() {
	wp_enqueue_script( 'jquery' );
	$script_url = WP_PLUGIN_URL . '/viperbar/admin/js/admin.js';
	$script_file = WP_PLUGIN_DIR . '/viperbar/admin/js/admin.js';
	if ( file_exists($script_file) ) {
		wp_register_script( 'viperbar_admin_script', $script_url );
		wp_enqueue_script( 'viperbar_admin_script' );
	}
	if ($_GET['tab'] == 'preview') {
		if ($sticky == 'true') {
			$script_url = WP_PLUGIN_URL . '/viperbar/client/js/viperbar_sticky.js';
			$script_file = WP_PLUGIN_DIR . '/viperbar/client/js/viperbar_sticky.js';
			if ( file_exists($script_file) ) {
				wp_register_script( 'viperbar_sticky_script', $script_url );
				wp_enqueue_script( 'viperbar_sticky_script' );
			}
			// Uses Tiny MCE for custom text.
			wp_enqueue_script('tiny_mce');
		}
	}
}
/*
	Admin Style - Enqueue's the style for the admin section.
*/
function viperbar_admin_style() {
	$style_url = WP_PLUGIN_URL . '/viperbar/admin/css/admin_style.css';
	$style_file = WP_PLUGIN_DIR . '/viperbar/admin/css/admin_style.css';
	if ( file_exists( $style_file ) ) {
		wp_register_style( 'viperbar_admin_style', $style_url );
		wp_enqueue_style( 'viperbar_admin_style' );
	}
	
	if ($_GET['tab'] == 'preview') {
		$style_url = WP_PLUGIN_URL . '/viperbar/client/css/front_style.css';
		$style_file = WP_PLUGIN_DIR . '/viperbar/client/css/front_style.css';
		if ( file_exists( $style_file ) ) {
			wp_register_style( 'viperbar_front_style', $style_url );
			wp_enqueue_style( 'viperbar_front_style' );
		}
	}
}
/*
	Activation Function - Adds the default options for the plugin, and sets the stats to 0.
*/
function viperbar_activate() {
	// These are all of the options that will be saved by this plugin:
	$default_options = array(
		'enabled' => 'false',
		'first_time_use' => 'true',
		'show_to_admin' => 'true',
		'stats_enabled' => 'true',
		'split_testing_enabled' => 'off',
		'split_test_favor' => '',
		'name_field_disable' => 'on',
		'sticky_enabled' => 'false',
		'cookie_hide' => 'true',
		'cookie_days' => '30',
		'opacity_value' => 10,
		'show_credit' => 'true',
		'aweber_thank_you' => '',
		'show_hide' => 'true',
		'bar_type' => 'single',
		'image_url' => '', // If the background of the bar is an image
		'bar_single_color' => '999999',
		'bar__color' => '999999',
		'use_color_picker' => 'false',
		'text_button' => 'Submit',
		'manual_text_color' => 'false',
		'buttons_color' => 'FF6600',
		'text_thanks' => 'Thank you! Please check your inbox to confirm your subscription.',
		'before_text' => '',
		'after_text' => '',
		'placeholder_name' => 'Name',
		'placeholder_email' => 'Email Address',
		'before_text_b' => '',
		'after_text_b' => '',
		'form_id_feedburner' => '',
		'form_id_mailchimp' => '',
		'form_id_aweber' => '',
		'form_id_mailchimp_list' => '',
		'text_custom' => ''
	);
	
	add_option( 'viperbar_options_general', $default_options );
	
	// Split-test A
	add_option( 'viperbar_stats_submits', '0' );
	add_option( 'viperbar_stats_impressions', '0' );
	
	//Split-Test B
	add_option( 'viperbar_stats_submits_b', '0' );
	add_option( 'viperbar_stats_impressions_b', '0' );
	
	//Submits, extended data.
	add_option ( 'viperbar_submit_data', '' );
}

/*
	Deactivation - Runs when the plugin is deactivated; removes all options specific to ViperBar.
*/
function viperbar_deactivate() {
	delete_option( 'viperbar_options_general' );
	delete_option( 'viperbar_stats_submits' );
	delete_option( 'viperbar_stats_impressions' );
	delete_option( 'viperbar_stats_submits_b' );
	delete_option( 'viperbar_stats_impressions_b' );
}

/*
	Admin Actions - Adds the Viperbar settings page, which utilizes the function: viperbar_settings.
*/
function viperbar_admin_actions() {
	add_options_page( 'Viperbar', 'Viperbar', 'manage_options', 'viperbar-settings', 'viperbar_settings', '' );
}

function viperbar_admin_header() {
	$base_uri_list = explode('&', $_SERVER['REQUEST_URI']);
	$base_uri = $base_uri_list[0];
	// The header for the plugin settings is loaded from Viperchill.com for user benefit.
	echo '<div id="Viper_main_container">' . file_get_contents( 'http://www.viperchill.com/rss/plugin_header.php?plugin=viperbar' );
	?>
	<div id="Viper_sections">
		<a href="<?php echo $base_uri; ?>&tab=general" <?php echo ($_GET['tab'] == "general") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?>>General</a>
		<a href="<?php echo $base_uri; ?>&tab=content&section=preset" <?php echo ($_GET['tab'] == "content") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?>>Content</a>
		<a href="<?php echo $base_uri; ?>&tab=appearance&section=bar" <?php echo ($_GET['tab'] == "appearance") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?>>Appearance</a>
		<a href="<?php echo $base_uri; ?>&tab=stats&section=overview" <?php echo ($_GET['tab'] == "stats") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?>>Stats</a>
		<a href="<?php echo $base_uri; ?>&tab=preview" <?php echo ($_GET['tab'] == "preview") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?>>Preview</a>
	</div>
<?php }

/*
	Update Options - Whenever options are updated, this function is utilized to check the
	nonce value, and then update the plugin's options.
*/
function viperbar_update_options() {

	$current_options = get_option( 'viperbar_options_general' );
	if (isset($_POST['enabled']) && check_admin_referer( 'viperbar_save', 'general' )) {
		// Settings to update: General
		$current_options['enabled'] = $_POST['enabled'];
		if ($current_options['enabled'] == 'true') {
			$current_options['first_time_use'] = 'false';
		}
		$current_options['show_to_admin'] = $_POST['show_admin'];
		$current_options['stats_enabled'] = $_POST['enable_stats'];
		$current_options['show_credit'] = $_POST['info_link'];
		$current_options['show_hide'] = $_POST['enable_hide'];
		$current_options['sticky_enabled'] = $_POST['add_sticky'];
		$current_options['cookie_hide'] = $_POST['add_cookie_hide'];
		$current_options['cookie_days'] = $_POST['set_cookie_days'];
		update_option('viperbar_options_general', $current_options);
		$update_success = 'Your general settings have been updated.';
	} else if (isset($_POST['before_form']) && check_admin_referer( 'viperbar_save', 'content' )) {
		// Settings to update: Content
		// Copy Settings
		$current_options['split_test_favor'] = $_POST['favor_split'];
		$current_options['split_testing_enabled'] = $_POST['split_testing'];
		$current_options['text_before'] = $_POST['before_form'];
		$current_options['text_after'] = $_POST['after_form'];
		$current_options['text_before_b'] = $_POST['before_form_b'];
		$current_options['text_after_b'] = $_POST['after_form_b'];
		$current_options['placeholder_name'] = $_POST['placeholder_name'];
		$current_options['placeholder_email'] = $_POST['placeholder_email'];
		
		// RSS Subscriptions
		$current_options['form_id_feedburner'] = $_POST['feedburner_id'];
		
		// Mailing List Subscriptions
		$current_options['name_field_disable'] = $_POST['disable_namefield'];
		$current_options['form_id_aweber'] = $_POST['aweber_id'];
		$current_options['aweber_thank_you'] = $_POST['aweber_thanks'];
		$current_options['form_id_mailchimp'] = $_POST['mailchimp_key'];
		$current_options['form_id_mailchimp_list'] = $_POST['mailchimp_list_id'];
		$current_options['text_thanks'] = $_POST['thankyou_text'];
		update_option('viperbar_options_general', $current_options);
		$update_success = 'The content of your Viperbar has been changed.';
	} else if (isset($_POST['custom_text']) && check_admin_referer( 'viperbar_save', 'content_custom' )) {
		// Settings to update: Custom Content
		$current_options['text_custom'] = $_POST['custom_text'];
		update_option('viperbar_options_general', $current_options);
		$update_success = 'The content of your Viperbar has been changed - custom content has been added.';
	} else if (isset($_POST['bar_type']) && check_admin_referer( 'viperbar_save', 'appearance_bar' )) {		
		foreach ($_POST as $key => $value) {
			// Settings to update: Bar Color Options
			// Save all options generically, except those from the nonce.
			if ($key != '_wp_http_referer' 
				&& $key != 'appearance_bar' 
				&& $key != 'custom_image'
				&& trim($value) != '') {
				$current_options[$key] = $value;
			}
		}
		foreach($_FILES as $key => $file) {
			if ($file['error'] != 0) {
				// There has been an error uploading the file.
			} else if ($key == 'custom_image') {
				// Upload this file
				include_once(ABSPATH . 'wp-admin/includes/media.php');
				include_once(ABSPATH . 'wp-admin/includes/file.php');
				$overrides = array( 'test_form' => false);
				$file_id = wp_handle_upload( $file, $overrides );  
				$img = $file_id['url'];
				$current_options['image_url'] = $img;
			}
		}
		update_option('viperbar_options_general', $current_options);
		$update_success = 'The appearance of your ViperBar has been changed.';
	} else if (isset($_POST['text_button']) && check_admin_referer( 'viperbar_save', 'appearance_button' )) {
		foreach ($_POST as $key => $value) {
			// Settings to update: Button Color Options
			if ($key != '_wp_http_referer' && $key != 'appearance_button')	{
				$current_options[$key] = $value;
			}
		}
		$update_success = 'Your button options have been updated.';
		update_option('viperbar_options_general', $current_options);
	} else if (isset($_POST['reset']) && check_admin_referer( 'viperbar_save', 'reset_stats')) {
		if ($_POST['reset'] == 'stats') {
			// User wants to reset statistics.
			update_option( 'viperbar_stats_submits', '0' );
			update_option( 'viperbar_stats_impressions', '0' );
			update_option( 'viperbar_stats_submits_b', '0' );
			update_option( 'viperbar_stats_impressions_b', '0' );
			update_option( 'viperbar_submit_data', '');
			
			$update_success = 'Your stats have just been reset.';
		}
	}
	if ($update_success) {
		echo '<div class="Viper_success" >' . $update_success . '</div>';
	}
}

/*
	General Settings - This admin tab includes general settings, like activation.
*/
function viperbar_general_settings() { 
	$general_options = get_option( 'viperbar_options_general' );
	if ($general_options['enabled'] == 'false') {
		echo '<div class="viperbar_disabled" >The Viperbar is not currently enabled.</div>';
		if ($general_options['first_time_use'] == 'true') {
			echo '<div class="viperbar_note">Wordpress caching plugins are great, but disable them while activating ViperBar for the first time.</div>';
		}
	}
?>
	<h3>Basic Options</h3>
	<div id="Viper_panel_general">	
		<form method="post" action="<?php echo $_POST['REQUEST_URI']; ?>">
			<table id="viperbar-general-settings" class="viperbar_settings_table">
				<tr>
					<td>
						<div class="Viper_label">Enable ViperBar</div>
						<div class="Viper_input">
							<p>
								<input type="radio" name="enabled" value="true" <?php echo ($general_options['enabled'] == "true") ? 'checked="checked"' : ''; ?>>Yes <input type="radio" name="enabled" value="false" <?php echo ($general_options['enabled'] == "false") ? 'checked="checked"' : ''; ?>>No</p>
						</div>
					</td>
					<td>
						<div class="Viper_label">Show the ViperBar to Administrators</div>
						<div class="Viper_input">
							<p>
								<input type="radio" name="show_admin" value="true" <?php echo ($general_options['show_to_admin'] == "true") ? 'checked="checked"' : ''; ?>>Yes <input type="radio" name="show_admin" value="false" <?php echo ($general_options['show_to_admin'] == "false") ? 'checked="checked"' : ''; ?>>No</p>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="Viper_label">Enable Statistics</div>
						<div class="Viper_input">
							<p>
								<input type="radio" name="enable_stats" value="true" <?php echo ($general_options['stats_enabled'] == "true") ? 'checked="checked"' : ''; ?>>Yes <input type="radio" name="enable_stats" value="false" <?php echo ($general_options['stats_enabled'] == "false") ? 'checked="checked"' : ''; ?>>No</p>
						</div>
					</td>
					<td>
						<div class="Viper_label">Show Link to ViperBar Information <span style="font-weight: normal">(Recommended)</span>
						</div>
						<div class="Viper_input">
							<p>
								<input type="radio" name="info_link" value="true" <?php echo ($general_options['show_credit'] == "true") ? 'checked="checked"' : ''; ?>>Yes
								<input type="radio" name="info_link" value="false" <?php echo ($general_options['show_credit'] == "false") ? 'checked="checked"' : ''; ?>>No</p>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="Viper_label">Enable Hide Button <span style="font-weight: normal">(Recommended)</span>
						</div>
						<div class="Viper_input">
							<p>
								<input type="radio" name="enable_hide" value="true" <?php echo ($general_options['show_hide'] == "true") ? 'checked="checked"' : ''; ?>>Yes <input type="radio" name="enable_hide" value="false" <?php echo ($general_options['show_hide'] == "false") ? 'checked="checked"' : ''; ?>>No</p>
						</div>
					</td>
					<td>
						<div class="Viper_label">Make ViperBar Sticky</div>
						<p>(Make the bar stay at the top of the page as the user scrolls down.)</p>
						<div class="Viper_input">
							<p>
								<input type="radio" name="add_sticky" value="true" <?php echo ($general_options['sticky_enabled'] == "true") ? 'checked="checked"' : ''; ?>>Yes <input type="radio" name="add_sticky" value="false" <?php echo ($general_options['sticky_enabled'] == "false") ? 'checked="checked"' : ''; ?>>No</p>
						</div>
					</td>
				</tr>
				</table>
				<h3>Advanced Options</h3>
					<div class="Viper_label">Once hidden, keep the Viperbar hidden for that user until they click 'show' again.</div>
					<p>(Creates a cookie on the user's computer so we know they've hidden the bar.)</p>
					<div class="Viper_input">
						<p>
							<input type="radio" name="add_cookie_hide" value="true" <?php echo ($general_options['cookie_hide'] == "true") ? 'checked="checked"' : ''; ?>>Yes <input type="radio" name="add_cookie_hide" value="false" <?php echo ($general_options['cookie_hide'] == "false") ? 'checked="checked"' : ''; ?>>No</p>
					</div>
					<p><strong>If enabled: </strong>Hide the bar for <input type="text" name="set_cookie_days" value="<?php echo $general_options['cookie_days'];?>" style="width:50px;" /> days after hide button is clicked.</p>
			<?php wp_nonce_field( 'viperbar_save', 'general' ); ?>
			<input type="submit" class="button-secondary" value="Save Settings" />
		</form>
	</div>
<?php
}

/*
	Content Settings - Includes settings like RSS and mailing-list form options.
*/
function viperbar_content_settings() { 
	$options = get_option( 'viperbar_options_general' );
	$base_uri_list = explode('&', $_SERVER['REQUEST_URI']);
	$base_uri = $base_uri_list[0] . '&' . $base_uri_list[1];
?>
<div id="Viper_panel_content">
	<div class="Viper_subsections" id="Viper_subsections_content">
		<a href="<?php echo $base_uri; ?>&section=preset" <?php echo ($_GET['section'] == "preset") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?>>Preset Forms</a>
		<a href="<?php echo $base_uri; ?>&section=custom" <?php echo ($_GET['section'] == "custom") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?>>Custom Text</a>
	</div>
	<div id="Viper_subpanel_content_main">
		<div id="Viper_subpanel_content_presets" <?php echo ($_GET['section'] == 'preset') ? '' : 'style="display:none;"'; ?>>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" >
			<style>
				h4.split_header, p.split_favor_option { <?php echo ($options['split_testing_enabled'] == 'on') ? '' : 'display:none;'; ?> margin: 10px 0 0 0;}
			</style>
			<h2>Form Copy</h2>
			<p><input type="checkbox" name="split_testing" id="enable_split_testing" <?php echo ($options['split_testing_enabled'] == 'on') ? 'checked="checked"' : ''; ?> /> Enable Split-Testing</p>
			<h4 class="split_header">Split Test A</h4>
			<table id="viperbar_content_table" class="viperbar_settings_table">
				<tr>
					<td style="width:50%;">
						<span class="Viper_label">Text Before the Form:</span> 
						<div class="Viper_input">
							<input type="text" name="before_form" value="<?php echo stripslashes($options['text_before']); ?>" />
							<p>E.g. "Join thousands and get updates for free."</p>
						</div>
						<p class="split_favor_option">
							<input type="checkbox" name="favor_split" id="favor_a" value="a" <?php echo ($options['split_test_favor'] == 'a') ? 'checked="checked"' : ''; ?>  /> Favor Test A (increases the likelihood of A by 16%)
						</p>
					</td>
					<td>
						<span class="Viper_label">Text After the Form:</span> 
						<div class="Viper_input">
							<input type="text" name="after_form" value="<?php echo stripslashes($options['text_after']); ?>" />
							<p>E.g. "No-Spam Guarantee."</p>
						</div>
					</td>
				</tr>
				<tr>
				<?php
				if ($options['name_field_disable'] != 'on') {
				?>
					<td style="width:50%;">
						<span class="Viper_label">Placeholder Text for Name:</span> 
						<div class="Viper_input">
							<input type="text" name="placeholder_name" value="<?php echo stripslashes($options['placeholder_name']); ?>" />
							<p>E.g. "Your Name", "First Name".</p>
						</div>
					</td>
				<?php } ?>
					<td>
						<span class="Viper_label">Placeholder Text for Email Address:</span> 
						<div class="Viper_input">
							<input type="text" name="placeholder_email" value="<?php echo stripslashes($options['placeholder_email']); ?>" />
							<p>E.g. "Email Address", "Your Best Email".</p>
						</div>
					</td>
				</tr>
			</table>
			<h4 class="split_header">Split Test B</h4>
			<table>
				<tr <?php echo ($options['split_testing_enabled'] == 'on') ? '' : 'style="display:none;"'; ?> id="split_form_b">
					<td style="width:50%;">
						<span class="Viper_label">Text Before the Form:</span> 
						<div class="Viper_input">
							<input type="text" name="before_form_b" value="<?php echo $options['text_before_b']; ?>" />
						</div>
						<p class="split_favor_option">
							<input type="checkbox" name="favor_split" value="b" <?php echo ($options['split_test_favor'] == 'b') ? 'checked="checked"' : ''; ?> id="favor_b" /> Favor Test B (increases the likelihood of B by 16%)
						</p>
					</td>
					<td>
						<span class="Viper_label">Text After the Form:</span> 
						<div class="Viper_input">
							<input type="text" name="after_form_b" value="<?php echo $options['text_after_b']; ?>" />
						</div>
					</td>
					</tr>
				</tr>
				<tr>
					<td>
						<h2>Opt-in Form Settings</h2>
						<p>What form do you want to put on your Viperbar?</p>
						
						<h3>RSS Subscriptions</h3>
						<span class="Viper_label">Feedburner ID:</span> 
						<div class="Viper_input">
							<p>Leave this blank to remove the form.</p>
							<input type="text" name="feedburner_id" value="<?php echo $options['form_id_feedburner']; ?>">
							<p>This is the list name. (e.g. "ViperChill").</p><p>Please make sure that you have <strong>enabled email subscriptions</strong> in your <a href="https://feedburner.google.com/fb/a/myfeeds">Feedburner settings</a> under <em>Publicize->Email Sunbscriptions</em>.</p>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<h3>Mailing List Subscriptions</h3>
						<p><input type="checkbox" name="disable_namefield" <?php echo ($options['name_field_disable'] == 'on') ? 'checked="checked"' : ''; ?> /> Disable the 'Name' and 'Last Name' Fields (create a form with email only).</p>
						<span class="Viper_label">Aweber List ID:</span> 
						<div class="Viper_input">
							<p>Leave this blank to remove the form.</p>
							<input type="text" name="aweber_id" value="<?php echo $options['form_id_aweber']; ?>" />
							<p>This is the feed name. (i.e. "ViperChill")</p>
							<strong>Thank you page: <input type="text" name="aweber_thanks" value="<?php echo $options['aweber_thank_you']; ?>" /></strong>
							<p>Example: http://www.viperchill.com/thank-you</p>
							<p>You can use this page to thank your new subscriber, and possible up-sell something else.</p>
						</div>
					</td>
				</tr>
				<tr>
					<td style="width:50%;">
						<span class="Viper_label">MailChimp API Key:</span> 
						<div class="Viper_input">
							<input type="text" name="mailchimp_key" value="<?php echo $options['form_id_mailchimp']; ?>" />
							<p>You get this from the account drop-down, near the bottom.</p>
							<p>If no form appears in your Viperbar, check that you have the correct API key - we don't want to output errors on your Viperbar.</p>
						</div>
					</td>
					<td style="vertical-align: top;">
						<span class="Viper_label">MailChimp Unique List ID:</span> 
						<div class="Viper_input">
							<input type="text" name="mailchimp_list_id" value="<?php echo $options['form_id_mailchimp_list']; ?>" />
							<p>Go to your list settings. It's at the bottom.</p>
						</div>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td>
						<span class="Viper_label">Thank you text:</span> 
						<div class="Viper_input">
							<p>This is the text the user sees when they submit the form.</p>
							<input type="text" name="thankyou_text" style="width: 500px;" value="<?php echo $options['text_thanks']; ?>" />
						</div>
					</td>
				</tr>
			</table>
			<?php wp_nonce_field( 'viperbar_save', 'content' ); ?>
			<input type="submit" value="Save Content Settings" class="button-secondary" />
		</form>
		</div>
		<div id="Viper_subpanel_content_custom" <?php echo ($_GET['section'] == 'custom') ? '' : 'style="display:none;"'; ?>>
			<div class="viperbar_note">You can use any html elements here to create <u>links</u>, <strong>bold text</strong>, <em>italicized print</em> etc., and Viperbar will display them appropriately. You can also use the editor to help you with formatting. We are aware that some Google Chrome users have a problem adding links with the editor, so if this is you, then use &lt;a&gt;Link&lt;/a&gt; tags instead.</div>
			<div class="Viper_label">Custom Text:</div>
			<div class="Viper_input">
					<?php // Instantiate a tiny_mce, to be used for custom output on the bar. 
					$viperbar_tinymce_options = array( 
					'editor_selector' => 'Viper_editor', 
					'convert_urls' => true, 
					'theme' => 'advanced',
					'skin' => 'wp_theme',
					);
						wp_tiny_mce( false , $viperbar_tinymce_options ); ?>
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<textarea name="custom_text" class="Viper_editor" id="Viper_editor"><?php echo stripslashes($options['text_custom']); ?></textarea>
					<?php wp_nonce_field( 'viperbar_save', 'content_custom' ); ?>
					<p><input type="submit" value="Save Custom Text" class="button-secondary" /></p>
				</form>
			</div>
		</div>
	</div>
</div>
<?php }

function viperbar_color_options ($options) {

	if (isset($_GET['vcu'])) {
		if ($_GET['vcu'] == 'hex') {
			$use_color_picker = 'false';
		} else {
			$use_color_picker = 'true';
		}
	} else {
		$use_color_picker = $options['use_color_picker'];
	}
	
	$app_base_uri = explode('&vcu=', $_SERVER['REQUEST_URI']);
	$app_base_uri = $app_base_uri[0];
?>

<div id="Viper_subpanel_appearance_bar">
<div id="Viper_bar_type_panel_main">
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" >
	
	<div id="viperbar_picker_options">
	<a href="<?php echo $app_base_uri; ?>&vcu=hex"  class="viperbar_vcu_link <?php echo ($use_color_picker == 'false') ? 'Viper_active' : 'Viper_inactive'; ?>"  >Use a Hex Code</a>
	<a href="<?php echo $app_base_uri; ?>&vcu=picker" class="viperbar_vcu_link <?php echo ($use_color_picker == 'true') ? 'Viper_active' : 'Viper_inactive'; ?>" >Use the Color Picker</a>
	</div>
		
	<div class="Viper_label">Select bar style:</div>
	<div class="Viper_input">
		<input type="radio" name="bar_type" class="viperbar_bar_type" value="solid"
			<?php echo ($options['bar_type'] == "solid") ? 'checked="checked"' : ''; ?> onClick="toggle_bar_type_panel('solid')" />
				Solid Color
		<input type="radio" name="bar_type" class="viperbar_bar_type" value="single" <?php echo ($options['bar_type'] == "single") ? 'checked="checked"' : ''; ?> onClick="toggle_bar_type_panel('single')" />
				Single Color
		<input type="radio" name="bar_type" class="viperbar_bar_type" value="two" <?php echo ($options['bar_type'] == "two") ? 'checked="checked"' : ''; ?> onClick="toggle_bar_type_panel('two')" />
				Two Color
		<input type="radio" name="bar_type" class="viperbar_bar_type" value="image" <?php echo ($options['bar_type'] == "image") ? 'checked="checked"' : ''; ?> onClick="toggle_bar_type_panel('image')" />
				Image Upload
		<input type="radio" name="bar_type" class="viperbar_bar_type" value="theme" <?php echo ($options['bar_type'] == "theme") ? 'checked="checked"' : ''; ?> onClick="toggle_bar_type_panel('theme')" />
				Theme
	</div>		
	
	<div id="Viper_bar_type_panel_solid" class="Viper_bar_type_panel">
		<div class="Viper_label">Choose your bar's solid color:</div>
		<div class="Viper_input">
		<?php
		if ($use_color_picker == 'true') {
			echo Viper_color_picker("bar_solid",$options,$plugin_dir,"solid");
		} else { ?>
			
		Hex Value: #
			<input type="text" name="bar_solid_color" style="width: 70px;" maxlength="6" value="<?php echo $options['bar_solid_color']; ?>" />
	<?php } ?>
			
		</div>
	</div>
	<div id="Viper_bar_type_panel_single" class="Viper_bar_type_panel">
		<div class="Viper_label">Choose your bar's gradient color:</div>
		<div class="Viper_input">
			<?php 
			if ($use_color_picker == 'true') {
				echo Viper_color_picker("bar_single",$options,$plugin_dir,"single");
			} else { ?>
			Hex Value: #
			<input type="text" name="bar_single_color" style="width: 70px;" maxlength="6" value="<?php echo $options['bar_single_color']; ?>" />
			<?php } ?>
		</div>
	</div>
	<div id="Viper_bar_type_panel_two" class="Viper_bar_type_panel">
		<div class="Viper_label">Choose your bar's two colors:</div>
		<div class="Viper_input">
			<?php
			if ($use_color_picker == 'true') { 
				echo Viper_color_picker("bar_two",$options,$plugin_dir,"two"); 
			} else { ?>
			Hex Value for Top Color: #
			<input type="text" name="bar_color_top" style="width: 70px;" maxlength="6" value="<?php echo $options['bar_color_top']; ?>" /><br/>
			Hex Value for Bottom Color: #
			<input type="text" name="bar_color_bottom" style="width: 70px;" maxlength="6" value="<?php echo $options['bar_color_bottom']; ?>" />
			<?php } ?>
		</div>
	</div>
	<div id="Viper_bar_type_panel_image" class="Viper_bar_type_panel">
		<div class="Viper_label">Upload your own image:</div>
		<div class="Viper_input">
			<p>This image will be repeated horizontally, but not vertically.</p>
			<input type="file" name="custom_image" id="file" />
			<?php							
				if (trim($options[ 'image_url' ]) != '') {
					echo '<p><b>Current Image:</b></p>' .
						'<p ><img src="' . $options[ 'image_url' ] . '" style="max-height: 200px; max-width:600px; border: 1px solid #AAAAAA;"></p><p>Upload a new image, using the form above, to replace this image.</p>';
				}
			?>
		</div>
	</div>
	<div id="Viper_bar_type_panel_theme" class="Viper_bar_type_panel">
		<div class="Viper_label">Pre-designed Themes</div>
		<div class="Viper_input">
			<p>Choose a <em>pre-designed theme</em>.</p>
			<?php
			$v_theme_url = WP_PLUGIN_URL . '/viperbar/images/themes/';
			$num_themes = 9;
			$theme_names = array ( 'Dark Wood', 'Cloud', 'Light Doodle', 'Paper', 'Light Wood', 'Purple', 'Silver Gradient', 'Intense Effect', 'Fancy' );
			$theme_sizes = array ( 29, 20, 20, 12, 12, 4, 4, 4, 16);
			for ($i = 1; $i <= $num_themes; $i++) {
				echo '<p>';
				echo '<input type="radio" name="theme_url" value="v_' . $i . '"';
				// Check if is current.
				echo ($options['theme_url'] == ('v_' . $i)) ? 'checked="checked"' : '';
				
				echo  '/> ' . $theme_names[$i - 1] . ' (Size: ' . $theme_sizes[$i - 1] . 'kb)<br/>';
				echo '<div class="viperbar_theme_preview" style="background-image: url(\'' . $v_theme_url . 'v_' . $i . '.png' . '\');"></div>';
				echo '</p>';
			}
			?>
			
			
		</div>
	</div>
</div>
		
<input type="hidden" name="use_color_picker" value="<?php echo $color_picker; ?>" />
<?php
}

function viperbar_bar_appearance($options) {

	viperbar_color_options($options);
	?>

	<h3>Advanced Appearance Options</h3>
	<div id="viperbar_advanced_appearance_settings">
		<h4 style="font-size: 1.1em;">Opacity/Transparency</h4>
		<div class="Viper_label">Opacity Value</div>
		<p>The lower the percentage, the more transparent the bar will be.</p>
		<div class="Viper_input">
			<p id="viperbar_opacity_settings">
				<?php
				for ($i = 1; $i <= 10; $i++) {
					if ($options['opacity_value'] == $i) {
						echo '<input type="radio" name="opacity_value" checked="checked" value="' . $i . '"  /><span>' . $i . '0%</span>';
					} else {
						echo '<input type="radio" name="opacity_value" value="' . $i . '"  /><span>' . $i . '0%</span>';
					}
				}
				?>
			</p>
		</div>
	</div>
	<h4 style="font-size: 1.1em;">Override Text Color</h4>
	<p>Viperbar automatically works out whether your text should be light or dark, except in the case of a custom image background. You can use this setting to override the system and choose your own text color.</p>	
	<div class="Viper_label">Enable Manual Text Color Setting</div>
	<div class="Viper_input">
		<p>
			<input type="radio" name="manual_text_color" value="true" <?php echo ($options['manual_text_color'] == "true") ? 'checked="checked"' : ''; ?>>Yes <input type="radio" name="manual_text_color" value="false" <?php echo ($options['manual_text_color'] == "false") ? 'checked="checked"' : ''; ?>>No</p>
	</div>
	<div class="Viper_label">Hex Color Value</div>
	<div class="Viper_input">
		#<input type="text" maxlength="6" name="custom_text_color" value="<?php echo $options['custom_text_color']; ?>" style="width: 70px;" />
	</div>
	<?php wp_nonce_field( 'viperbar_save', 'appearance_bar' ); ?>
	<p><input type="submit" value="Save Appearance Settings" class="button-secondary" /></p>
</form>
</div>
</div>
<?php
}

function viperbar_rewrite_style ($style_file, $new_style) {
	// Write to the file.
	$fopen = fopen( $style_file, 'w' );
	fwrite( $fopen, $new_style );
	fclose($fopen);
}

function viperbar_advanced_appearance () {

	$style_file = WP_PLUGIN_DIR . '/viperbar/client/css/front_style.css';
	$style_file_original = WP_PLUGIN_DIR . '/viperbar/client/css/front_style_original.css';
	
	if ( isset( $_POST['rewrite_css'] ) && check_admin_referer( 'viperbar_rewrite_style', 'rewrite' ) ) {
		$new_style = $_POST['rewrite_css'];
		viperbar_rewrite_style( $style_file, $new_style );
		
	} else if ( isset($_POST['revert_original'] ) && check_admin_referer( 'viperbar_rewrite_style', 'revert' ) ) {
		$old_style = file_get_contents($style_file_original);
		viperbar_rewrite_style( $style_file, $old_style );
	}

?>

<h3>Customize Your Viperbar CSS</h3>
	
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<textarea name="rewrite_css" class="viperbar_edit_css"><?php echo file_get_contents($style_file); ?></textarea>
		<?php wp_nonce_field( 'viperbar_rewrite_style', 'rewrite' ); ?>
		<p><input type="submit" class="button-secondary" value="Update Viperbar Style" /></p>
	</form>
	
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<input type="hidden" name="revert_original" value="yes" />
		<?php wp_nonce_field( 'viperbar_rewrite_style', 'revert' ); ?>
		<p><input type="submit" class="button-secondary" value="Revert to Original Style" /></p>
	</form>

<?php
}

function viperbar_button_appearance($options) {
?>
<div id="Viper_subpanel_appearance_buttons" <?php echo ($_GET['section'] == 'button') ? '' : 'style="display:none;"'; ?>>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" >
		<div class="Viper_label">Button Text:</div>
		<div class="Viper_input">
			<input type="text" name="text_button" style="width:200px;" value="<?php echo $options['text_button']; ?>">
		</div>
		<br style="clear: both;">
		<div class="Viper_label">Choose your button color:</div>
		<div class="Viper_input">
			<p>This will only affect the buttons in the preset forms (see the content tab).</p>
		</div>
		<?php echo Viper_color_picker("buttons",$options,$plugin_dir); ?>
		<?php wp_nonce_field( 'viperbar_save', 'appearance_button' ); ?>
		<p><input type="submit" value="Save Appearance Settings" class="button-secondary" /></p>
	</form>		
</div>

<?php
}

function viperbar_appearance_settings() {
	$options = get_option( 'viperbar_options_general' );
	$base_uri_list = explode('&', $_SERVER['REQUEST_URI']);
	$base_uri = $base_uri_list[0] . '&' . $base_uri_list[1];
?>
<div id="Viper_panel_appearance">
<div class="Viper_subsections viper_third" id="Viper_subsections_appearance">
	<a href="<?php echo $base_uri; ?>&section=bar" <?php echo ($_GET['section'] == "bar") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?>>Bar</a>
	<a href="<?php echo $base_uri; ?>&section=button" <?php echo ($_GET['section'] == "button") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?> >Buttons</a>
	<a href="<?php echo $base_uri; ?>&section=advanced" <?php echo ($_GET['section'] == "advanced") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?> >Advanced</a>
</div>
<div id="Viper_subpanel_appearance_main">
	<?php
	if ($_GET['section'] == 'bar') {
		viperbar_bar_appearance($options);
	} else if ($_GET['section'] == 'button') {
		viperbar_button_appearance($options);
	} else if ($_GET['section'] == 'advanced') {
		viperbar_advanced_appearance();
	}
	?>
	<script type="text/javascript">	
	toggle_bar_type_panel('<?php echo $options['bar_type']; ?>');
	
	function set_new_color(hex,element,query_type) {
	document.getElementById('new_color_' + element).src = '<?php echo WP_PLUGIN_URL . '/viperbar/includes/gradient.php?width=530&color='; ?>' + hex;

	if(query_type == 'solid') {
		document.getElementById('new_color_' + element).src = '<?php echo WP_PLUGIN_URL . '/viperbar/includes/gradient.php?width=530&top='; ?>' + hex + '&bottom=' + hex;
		document.getElementById('bar_color_input_' + element).value = hex;
	}
	
	if(query_type == 'single') {
		document.getElementById('new_color_' + element).src = '<?php echo WP_PLUGIN_URL . '/viperbar/includes/gradient.php?width=530&color='; ?>' + hex;
		document.getElementById('bar_color_input_' + element).value = hex;
	}
		
	if(query_type == 'two') {
		var top_color;
		var bottom_color;
	
		if(document.getElementById('Viper_color_selector_store').value == 'top') {
			top_color = hex;
			bottom_color = document.getElementById('Viper_color_' + element + '_bottom_store').value;
			document.getElementById('Viper_color_' + element + '_top_store').value = hex;
		} else {
			top_color = document.getElementById('Viper_color_' + element + '_top_store').value;
			document.getElementById('Viper_color_' + element + '_bottom_store').value = hex;
			bottom_color = hex;
		}
		
		document.getElementById('new_color_' + element).src = '<?php echo WP_PLUGIN_URL . '/viperbar/includes/gradient.php?width=530&top='; ?>' + top_color + '&bottom=' + bottom_color;
		
	}
}
</script>
<?php			
}

function viperbar_create_data_table ($title, $th_1, $th_2, $data) {

	$data_table = '<h3>' . $title . '</h3>';
	$data_table .= '<table class="widefat">';
	$data_table .= '<thead><th>' . $th_1 . '</th><th style="text-align:right;">' . $th_2 . '</th></thead>';
	foreach ($data as $key => $frequency) {
		if ($th_1 == 'Page Title') {
			// We are doing a page data table.
			if ($key == 'homepage') {
				$page_title = 'Homepage';
			} else {
				$page_title = get_the_title($page);
			}
			$row_val = $page_title;
		} else if ($title == 'Dates') {
			// We are doing, for instance, a time data table.
			$time = explode('-', $key);
			$row_val = date( 'l, F jS', mktime( 0, 0, 0, intval( $time[1] ), intval( $time[0] ), intval( $time[2] ) ) );
		}
		if ($row_val){
			$data_table .= '<tr>';
			
			$data_table .= '<td>' . $row_val . '</td>';
			$data_table .= '<td style="text-align:right;">' . $frequency . '</td>';
			
			$data_table .= '</tr>';
		}
	}

	$data_table .= '</table>';
	
	return $data_table;
}

function viperbar_stats_submits ( $options ) {
	
	echo '<div id="viperbar_submit_data">';
	
	$submit_data = get_option ( 'viperbar_submit_data' );
	
	if (trim($submit_data) != '') {
		$submit_data = explode ( ';', $submit_data );
		$submit_pages = array();
		$submit_timestamp = array();
			
		foreach ( $submit_data as $data ) {
			$data_arr = explode ( ',', $data );
			array_push( $submit_pages, intval( $data_arr[ 0 ] ) );
			array_push( $submit_timestamp,  strval($data_arr[ 1 ] ));
		}
		
		
	
		if ( is_array( $submit_pages ) && ( count ( $submit_pages ) >= 1 ) ) {
			$data = array_count_values($submit_pages);
			arsort($data);
			echo viperbar_create_data_table ('Top Posts and Pages', 'Page Title', 'Number of Submits', $data);
		}
		if ( is_array( $submit_timestamp ) && ( count ( $submit_timestamp ) >= 1 ) ) {
			$data = array_count_values( $submit_timestamp );
			arsort($data);
			echo viperbar_create_data_table ('Dates', 'Day', 'Number of Submits', $data);
		}
	} else {
		echo '<p>No submission data is available, yet.</p>';
	}
	echo '</div>';
}

function viperbar_stats_overview ( $options ) {

	$impressions = get_option( 'viperbar_stats_impressions' );
	$submits = get_option( 'viperbar_stats_submits' );
	if ( $submits > 0 ) {
		$conversion_rate = round( $submits/$impressions * 100, 2 ) . '%';
	} else {
		$conversion_rate = 'No submits, yet!';
	}
	?>
	<style type="text/css">
		h2.split_header { <?php echo ( $options['split_testing_enabled'] == 'on' ) ? '' : 'display:none;'; ?> margin: 10px 0 0 0; font-size: 18px; text-decoration: underline;}
	</style>	
	<h2 class="split_header">Split A Results</h2>
		<table id="Viper_stats_table">
			<tr>
				<td><h3>Impressions</h3></td>
				<td id="ViperBar_stats_impressions"><?php echo $impressions; ?></td>
			</tr>
			<tr>
				<td><h3>Submissions</h3></td>
				<td id="ViperBar_stats_submits"><?php echo $submits; ?></td>
			</tr>
			<tr>
				<td><h3>Conversion Rate</h3></td>
				<td id="ViperBar_stats_conversion"><?php echo $conversion_rate; ?></td>
			</tr>
		</table>
		<?php
		if ($options['split_testing_enabled'] == 'on') { 
			$impressions_b = get_option( 'viperbar_stats_impressions_b' );
			$submits_b = get_option( 'viperbar_stats_submits_b' );
			if (!$submits_b || trim($submits_b) == '') {
				$submits_b = 0;
			}
			if ($submits_b > 0){
				$conversion_rate_b = round($submits_b/$impressions_b * 100, 2) . '%';
			} else {
				$conversion_rate_b = 'No submits, yet!';
			}
		?>
			<h2 class="split_header">Split B Results</h2>
			<table id="Viper_stats_table">
			<tr>
				<td><h3>Impressions</h3></td>
				<td id="ViperBar_stats_impressions"><?php echo $impressions_b; ?></td>
			</tr>
			<tr>
				<td><h3>Submissions</h3></td>
				<td id="ViperBar_stats_submits"><?php echo $submits_b; ?></td>
			</tr>
			<tr>
				<td><h3>Conversion Rate</h3></td>
				<td id="ViperBar_stats_conversion"><?php echo $conversion_rate_b; ?></td>
			</tr>
			</table>
<?php }
}

function viperbar_view_stats() {
	$base_uri_list = explode('&', $_SERVER['REQUEST_URI']);
	$base_uri = $base_uri_list[0] . '&' . $base_uri_list[1];
	
	$options = get_option ( 'viperbar_options_general' );
	if ( $options['stats_enabled'] == 'false' ) {
		// Stats is not enabled, do not show stats.
		echo '<h4>You do not currently have stats enabled.</h4>';
	} else { ?>
		<div id="Viper_panel_stats">
			<div class="Viper_subsections" id="Viper_subsections_content">
				<a href="<?php echo $base_uri; ?>&section=overview" <?php echo ($_GET['section'] == "overview") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?>>Stats Overview</a>
				<a href="<?php echo $base_uri; ?>&section=submits" <?php echo ($_GET['section'] == "submits") ? 'class="Viper_active"' : 'class="Viper_inactive"'; ?>>Submits Info</a>
			</div>
		<?php 
		
		if ($_GET['section'] == 'submits') {
			viperbar_stats_submits ( $options );
		} else {
			viperbar_stats_overview ( $options ); 
		}
		
		?>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="resetStatsForm">
			<input type="hidden" name="reset" value="stats" />
			<?php wp_nonce_field( 'viperbar_save', 'reset_stats' ); ?>
			<input type="submit" value="Reset All Stats" class="button-secondary" />
		</form>
		<?php 
		if ($_GET['section'] != 'submits') {
			echo '<p style="font-weight: bold;">* If you do not include opt-in forms on your bar, then conversion rate statistics will not apply.</p>';
		} ?>
		<script type="text/javascript" >
			jQuery(document).ready(function ($) {
				$('#resetStatsForm').submit(function () {
					if (confirm('Are you sure that you want to reset your stat counter?')) {
						return true;
					} else {
						return false;
					}
				});
			});
		</script>
	</div>
	<?php
	}
}

function viperbar_preview() {
	include_once( WP_PLUGIN_DIR . '/viperbar/client/front_main.php' );
	echo '<style type="text/css" > #ViperBar_main { display: block; } </style>';
	$viperbar = viperbar_get_bar($preview = 'yes');
?>
<div id="Viper_panel_preview">
	<div id="Viper_preview_pane">
	<?php echo $viperbar; ?>	
	
		<p style="text-align: center;">
			[This is where the rest of your website would be.]
		</p>
		<div style="padding: 10px;">
			<p>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras in enim augue, eget rhoncus eros. In vitae adipiscing ipsum. Fusce hendrerit mauris in lectus ornare consectetur. Nulla facilisi. Cras id sem libero. Cras scelerisque, odio a sollicitudin varius, risus ante aliquam ligula, ac luctus libero nulla nec arcu. Pellentesque turpis diam, pharetra vitae sodales vehicula, pharetra vel diam. Nunc pretium, mi non ultrices aliquam, est arcu venenatis urna, at fringilla felis diam sit amet turpis. Sed at ligula lectus, at pulvinar mi. Praesent commodo posuere eros eget tempor. Sed nunc turpis, semper in malesuada in, elementum nec erat. Etiam consequat elementum volutpat. Aliquam erat volutpat.
			</p>
		</div>
	</div>
</div>
<?php
}

function viperbar_settings() {
	$general_options = get_option( 'viperbar_options_general' );
	// Load the plugin's header, including the menu.
	viperbar_admin_header();
	// Check for any form posts, update settings if necessary.
	viperbar_update_options();
	echo '<div id="Viper_panel_main">';
	if ($_GET['tab'] == 'content') {
	// Load the content settings, which includes the RSS and mailing-list forms.
		viperbar_content_settings();
	} else if ($_GET['tab'] == 'stats') {
		viperbar_view_stats();
	} else if ($_GET['tab'] == 'appearance') {
		viperbar_appearance_settings();
	} else if ($_GET['tab'] == 'preview') {
		viperbar_preview();
	} else {
	// Load the general settings tab, which includes the activation option.
		viperbar_general_settings();
	}
	?>
	</div>
</div>
<?php
}

/* 
	Color Picker - Adds color picker functionality to the admin section, for the bar background and button.
*/
function Viper_color_picker($slug,$options,$plugin_dir,$type = "single") {
		$resolution = array(
			"00","33","66","99","CC","FF"
		);
		
		if($type == "solid") {
			$query = "top=".$options[$slug.'_color']."&bottom=".$options[$slug.'_color'];
		}
		
		if($type == "single") {
			$query = "color=" . $options[$slug.'_color'];
		}
		
		if($type == "two") {
			$query = "top=".$options['bar_color_top']."&bottom=".$options['bar_color_bottom'];
			
			$color_selector = '<div style="float: right;">
					<input type="radio" name="color_number" value="top" onClick="set_color_store(\'top\');"> Top
					<input type="radio" name="color_number" checked="checked" value="bottom" onClick="set_color_store(\'bottom\');"> Bottom
					<input type="hidden" name="color_selector_store" id="Viper_color_selector_store" value="">
				</div>';				
			$color_store =
				"<input type=\"hidden\" id=\"Viper_color_".$slug."_top_store\" name=\"bar_color_top\" value=\"".$options[$slug.'_color']."\">
				 <input type=\"hidden\" id=\"Viper_color_".$slug."_bottom_store\" name=\"bar_color_bottom\" value=\"".$options[$slug.'_color']."\">";
		}

		$output .=
			$color_selector.
			"<p>
				Hover to see it, click to commit.
			</p>
			<div class=\"Viper_color_picker\" style=\"border: 1px solid #000000;\">";
			
		for($r = 0; $r < sizeof($resolution); $r++) {
			$output .= "<div style=\"float: left; width: 102px;\">";
			
			for($g = 0; $g < sizeof($resolution); $g++) {
			
				for($b = 0; $b < sizeof($resolution); $b++) {
					$hex = $resolution[$r].$resolution[$g].$resolution[$b];
				
					$output .= '<div class="Viper_swatch" style="background:#' . $hex . '" onMouseOver="see_new_color(\'' . $hex . '\',\'' . $slug . '\')" onClick="set_new_color(\'' . $hex . '\',\'' . $slug . '\',\'' . $type . '\')" ></div>';
				}
				
			}
			
			$output .= "</div>";
		}
		
		$output .= '<div class="Viper_swatch_lg" id="preview_color_' . $slug . '" style="background: #' . $options[$slug.'_color'] . '; float: left; margin: 0px; border: 0px; width: 42px;"></div>
				<br style="clear: both;">
			</div>
			<div style="margin: 10px;">
				<table class="Viper_table">
					<tr>
						<td style="text-align: right;"><b>Current Bar:</b></td>
						<td>
						<img id="current_color_"' . $slug . '" src="' . WP_PLUGIN_URL . '/viperbar/includes/gradient.php?width=530&' . $query . '" alt="bar"></td>
					</tr>
					<tr>
						<td style="text-align: right;"><b>New Bar:</b></td>
						<td><img id="new_color_' . $slug . '" src="' . WP_PLUGIN_URL . '/viperbar/includes/gradient.php?width=530&' . $query . '" alt="bar"></td>
					</tr>
				</table>
				<input type="hidden" id="bar_color_input_' . $slug . '" name="' . $slug . '_color" value="' . $options[$slug . '_color'] . '" />' . $color_store . '<br style="clear: both;"></div>';
			
		return $output;
	}

 ?>