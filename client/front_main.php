<?php

add_action('init', 'viperbar_load');
/*
	WP minify can sometimes cause complications with JQuery, if the user wants to load the
	script from the Google servers, while we use wp_enqueue_script( 'jquery' ). Because of this,
	I included a change to the minify location. Currently unaware of more appropriate solutions.
*/
add_action('wp_head', 'add_minify_location' , 99);

/*
	Load - Function is run at initialization of plugin; enqueues the front-end style, the front-end
	script, and sets which split-test set will be used (if split-testing is enabled).
*/
function viperbar_load() {

	// Enqueue Front-end Style
	viperbar_front_style();

	// Load plugin options.
	$options = get_option('viperbar_options_general');
	// For split testing, we need to choose randomly between options A and options B.
	$sticky = $options['sticky_enabled'];
	$split_testing = $options['split_testing_enabled'];

	$opts = array('a', 'b');
	if ($split_testing == 'true') {
		/*
			In the user options, they can set it so that one set occurs more than the other. In such
			a case, I add the favored set to the array of options again, increasing its likelihood of
			being chosen in a random selection.
		*/
		$favor_split = $options['split_test_favor'];
		if ( trim($favor_split) != '' ) {
			array_push($opts, $favor_split);
		}
	}
	global $select; // This is the selected split-test set.
	$select = $opts[array_rand($opts)]; // Randomly selected from the set of options ('a','b'; 'a','b','a'; or 'a','b','b')
	$viperbar_ajax_nonce = wp_create_nonce ('viperbar-submit-nonce');

	$cookie_hide = $options['cookie_hide'];
	$cookie_days = $options['cookie_days'];

	// Get Form Type
	$form_type = 'none';
	if ($options['form_id_feedburner'] && trim($options['form_id_feedburner']) != '') {
		$form_type = 'feedburner';
	} else if ($options['form_id_aweber'] && trim($options['form_id_aweber']) != '') {
			$form_type = 'aweber';
		} else if ($options['form_id_mailchimp'] && trim($options['form_id_mailchimp']) != '') {
			$form_type = 'mailchimp';
		} else if ($options['text_custom'] && trim($options['text_custom']) != '') {
			$form_type = 'custom';
		}

	// Enqueue Front-end Script
	viperbar_front_script($select, $split_testing, $sticky, $viperbar_ajax_nonce, $cookie_hide, $cookie_days, $form_type);

	// If not in wp-admin (in which case this is a preview, and the bar will not be prepended), call prepend function.
	if (!is_admin()) {
		add_action('wp_footer', 'viperbar_prepend');
	}
}
/*
	Front-end Script - Enqueue's the scripts for the front-end.
*/
function viperbar_front_script($select, $split_testing, $sticky, $viperbar_ajax_nonce, $cookie_hide, $cookie_days, $form_type) {
	wp_enqueue_script( 'jquery' );
	$script_url = WP_PLUGIN_URL . '/viperbar/client/js/front.js';
	$script_file = WP_PLUGIN_DIR . '/viperbar/client/js/front.js';
	if ( file_exists($script_file) ) {
		wp_register_script( 'viperbar_front_script', $script_url );
		wp_enqueue_script( 'viperbar_front_script' );

		// Create an array with the basic data for localization.
		$viperbar_data = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) , 'viperbar_submission_nonce' => $viperbar_ajax_nonce );

		// Include other needed data for localization.
		if (trim($form_type) != '') {
			$viperbar_data['form_type'] = $form_type;
		}
		if ($split_testing) {
			$viperbar_data['split_testing_option'] = $select;
		}
		if ($cookie_hide == 'true') {
			$viperbar_data['cookie_hide'] = $cookie_hide;
			$viperbar_data['cookie_days'] = $cookie_days;
		}

		// Localize data for this script.
		wp_localize_script( 'viperbar_front_script', 'viperbar', $viperbar_data );
	}
	// For the sticky feature (as the user scrolls down, the bar stays at the top of the page.)
	if ($sticky == 'true') {
		$script_url = WP_PLUGIN_URL . '/viperbar/client/js/viperbar_sticky.js';
		$script_file = WP_PLUGIN_DIR . '/viperbar/client/js/viperbar_sticky.js';
		if ( file_exists($script_file) ) {
			wp_register_script( 'viperbar_sticky_script', $script_url );
			wp_enqueue_script( 'viperbar_sticky_script' );
		}
	}
}
/*
	Front-end Style - Enqueue's the style for the front-end.
*/
function viperbar_front_style() {


	$style_url = WP_PLUGIN_URL . '/viperbar/client/css/front_style.css';
	$style_file = WP_PLUGIN_DIR . '/viperbar/client/css/front_style.css';
	if ( file_exists( $style_file ) ) {
		wp_register_style( 'viperbar_front_style', $style_url );
		wp_enqueue_style( 'viperbar_front_style' );
	}
}
/*
	Add Minify Location - As mentioned before, a fix for WP Minify + Google API + wp_enqueue conflicts.
*/
function add_minify_location() {
	if (class_exists('WPMinify')) { ?>
<!-- WP-Minify JS -->
<!-- WP-Minify CSS -->
<?php }
}
/*
	Prepend - Uses jQuery to add the Viperbar to the top of the document body.
*/
function viperbar_prepend() {
	/* I really wish that wp_body_open was included in all Wordpress installations.
		Since it isn't, the only way specifically to prepend the body is javascript. */

	if ($_GET['viperbar_thanks'] == 'y') {
		$viperbar = viperbar_get_bar('no', 'yes');
	} else {
		$viperbar = viperbar_get_bar();
	}
	echo $viperbar;
}
/*
	Show bar - This function returns a concatenation of the various ViberBar components
*/
function viperbar_get_bar($preview = 'no', $thanks = 'no') {
	global $select;
	$options = get_option( 'viperbar_options_general' );
	// The user may choose not to display the bar to administrators.
	if ($preview == 'no' && ($options['enabled'] == 'false' ||
			(current_user_can('manage_options') && $options['show_to_admin'] == 'false'))) {
		continue;
	}
	// Split-testing options, output text either for set A and for set B.
	if ($options['split_testing_enabled'] == 'on' && $select == 'b') {
		$text_before = stripslashes($options['text_before_b']);
		$text_after = stripslashes($options['text_after_b']);
	} else {
		$text_before = stripslashes($options['text_before']);
		$text_after = stripslashes($options['text_after']);
	}
	if ($preview == 'yes') {
		if (strlen($text_before) >= 15) {
			$text_before = substr($text_before, 0, 15) . '...';
		}
		if (strlen($text_after) >= 15) {
			$text_after = substr($text_after, 0, 15) . '...';
		}
	}

	// Since the ViperBar is being called, we count this as an impression unless it's a preview, or thanks.
	if ($options['stats_enabled'] == 'true' && $preview == 'no' && $thanks == 'no') {
		// This is a valid impression
		// Check if split-testing is enabled, and if the current test is 'b'
		if ($options['split_testing_enabled'] == 'on' && $select == 'b') {
			$current_impressions_b = get_option( 'viperbar_stats_impressions_b' );
			if ($current_impressions_b >= 0) {
				update_option( 'viperbar_stats_impressions_b', $current_impressions_b + 1 ); // 'x' + 1 - Got to love PHP.
			} else {
				update_option( 'viperbar_stats_impressions_b', 1 );
			}
		} else {
			$current_impressions = get_option( 'viperbar_stats_impressions' );
			if ($current_impressions >= 0) {
				update_option( 'viperbar_stats_impressions', $current_impressions + 1 );
			} else {
				update_option( 'viperbar_stats_impressions', 1 );
			}
		}
	}
	// Get the background for the Viperbar.
	$grad_url = WP_PLUGIN_URL . '/viperbar/includes/gradient.php'; // URL to gradient.php
	$viperbar_background = 'background: ';
	if ($options['bar_type'] == "solid" && $options['bar_solid_color'] != "") {
		// A solid color is going to be used.
		$viperbar_background .= '#' . $options['bar_solid_color'];
		$check_light_color = $options['bar_solid_color']; // To see whether the text should be dark.
	} elseif ($options['bar_type'] == "two" && $options['bar_color_bottom'] != "" && $options['bar_color_top'] != "") {
		// Two colors are going to be used in a gradient.
		$viperbar_background .= '#' . $options['bar_color_bottom'] .
			' url(\'' . $grad_url . '?width=10&height=45&top=' . $options['bar_color_top'] . '&bottom=' . $options['bar_color_bottom'] . '\')';
		$check_light_color = $options['bar_color_top']; // To see whether the text should be dark.
	} elseif ($options['bar_type'] == "single" && $options['bar_single_color'] !="") {
		// One color is going to be used, in a preset gradient.
		$viperbar_background .= '#' . $options['bar_single_color'] . ' url(\'' . $grad_url . '?' .
			'width=10&height=45&color=' . $options['bar_single_color'] . '\')';
		$check_light_color = $options['bar_single_color']; // To see whether the text should be dark.
	} elseif ($options['bar_type'] == "image") {
		// A user-uploaded image is going to be used for the background.
		$viperbar_background .= ' url(\'' . $options[ 'image_url' ] . '\')';
	} else if ( $options[ 'bar_type' ] == 'theme' && trim( $options['theme_url'] ) != '') {
			$viperbar_background .= ' url(\'' . WP_PLUGIN_URL . '/viperbar/images/themes/' . $options[ 'theme_url' ] . '.png\')';
		} else {
		// Bar appearance type varibale not set; use the default color in a gradient.
		$viperbar_background .= '#888888 url(\'' . $grad_url . '?width=10&height=45&color=888888\')';
		$check_light_color = $options['bar_single_color']; // To see whether the text should be dark.
	}
	$viperbar_background .= ' repeat-x;'; // repeat the generated, or custom, image across the bar.
	// Set the color of the before-form and after-form text.
	$viperbar_text_color = ' color: #';
	if ($options['manual_text_color'] == 'true') {
		// The user is overriding the automatic color detection.
		$viperbar_text_color .= $options['custom_text_color'] . ';';
	} else {
		// If the background is dark, we need to have light color, and vise-verse.
		if (is_light($check_light_color)) {
			$viperbar_text_color .= "111111;";
		} else {
			$viperbar_text_color .= "EEEEEE;";
		};
	}
	// The user may choose for the bar to have a transparency; if so, they also specify the opacity level.
	if ($options['opacity_value'] != '10') {
		$op_val = $options['opacity_value']; // The value of the opacity out of 10
		// Define a cross-browser CSS statement that provides opacity at the user-selected level.
		$viperbar_opacity .= ' -ms-filter: \'progid:DXImageTransform.Microsoft.Alpha(Opacity=' . $op_val . '0)\';  filter: alpha(opacity=' . $op_val . '0); -moz-opacity: 0.' . $op_val . '; -khtml-opacity: 0.' . $op_val . '; opacity: 0.' . $op_val . '; ';
	}
	// Concatenate the elements into a style variable for the Viperbar itself.
	$viberbar_style = $viperbar_background . $viperbar_text_color . $viperbar_opacity;
	// Now we do a similar operate for the button, getting the background style and text color.
	$button_style = 'display:inline; background: #CCCCCC '; // In case themes override our css (very common).
	$button_style .= 'url(\'' . $grad_url . '?width=10&height=30&color=' . $options['buttons_color'] . '\') repeat-x !important;';
	$button_style .= '';
	if (is_light($options['buttons_color'])) {
		$button_text_color = "000000";
	} else {
		$button_text_color = "FFFFFF";
	}

	if (is_home()) {
		$post_id = 'homepage';
	} else {
		$post_id = get_the_ID();
	}

	$button_style .= ' color: #' . $button_text_color . '!important;';
	// We need to create variables containing the Viberbar HTML structure.
	$viperbar_html = '<div id="ViperBar_main" rel="' . $post_id . '">';
	// The 'inner' div of the Viperbar will contain our specified style.
	$viperbar_inner = '<div id="ViperBar_inner" style="' . $viberbar_style . '">';
	// The user can remove the Viperchill logo and link.
	if ($options['show_credit'] == 'true') {
		$viperbar_inner .= '<div id="ViperBar_credit_logo">' .
			'<a href="http://www.ViperChill.com/viperbar/" target="_blank">' .
			'<img src="' . WP_PLUGIN_URL . '/viperbar/images/provided_by_ViperChill.png" />' .
			'</a>' .
			'</div>';
	}
	if ($thanks == 'yes') {
		$viperbar_content = '<div style="margin: 5px;" id="viperbar_thanks">' . $options['text_thanks'] . '</div></div>';
	} else {
		// Define a content variable that contains the text and form elements. Thanks will be hidden until form submission.
		$viperbar_content = '<div style="margin: 5px; display:none;" id="viperbar_thanks">' . $options['text_thanks'] . '</div>';
		$viperbar_content .= '<div id="viperbar_ajaxload"><em>Loading...</em></div>';
		$viperbar_content .= '<div id="viperbar_form_content">' . $text_before;
		// Show the Feedburner form, if the Feedburner ID in the options is not ''.
		if ($options['form_id_feedburner'] && trim($options['form_id_feedburner']) != '') {
			$viperbar_content .= viperbar_get_feedburner($options, $button_style);
		}
		// Show the Aweber form, if the Aweber ID in the options is not ''.
		if ($options['form_id_aweber'] && trim($options['form_id_aweber']) != '') {
			$viperbar_content .= viperbar_get_aweber($options, $button_style);
		}
		// Show the Main Chimp form, if the Mail Chimp ID in the options is not ''.
		if ($options['form_id_mailchimp'] && trim($options['form_id_mailchimp']) != '') {
			$viperbar_content .= viperbar_get_mailchimp($options, $button_style);
		}
		// Show custom content, if
		if ($options['text_custom'] && trim($options['text_custom']) != '') {
			$viperbar_content .= '<p>' . html_entity_decode(stripslashes($options['text_custom'])) . '</p>';
		}
		$viperbar_content .= $text_after . '</div>'; // Close the main div.
	}
	// The user can set the option of whether their readers can hide the Viperbar or not.
	if ($options['show_hide'] == 'true') {
		$viperbar_toggle_show = '<div id="ViperBar_hide">' .
			'<a href="javascript:void(0);"><img src="' . WP_PLUGIN_URL . '/viperbar/images/icon-up.png" /></a>' .
			'</div>' .
			'</div>' .
			'<div id="ViperBar_show">' .
			'<a href="javascript:void(0);"><img src="' . WP_PLUGIN_URL . '/viperbar/images/icon-down.png" /></a>' .
			'</div>' .
			'</div>';
	} else {
		$viperbar_toggle_show .= '</div>';
	}
	// Concatenate Viperbar elements.
	$viperbar = $viperbar_html . $viperbar_inner . $viperbar_content . $viperbar_toggle_show;
	return $viperbar; // Return contents.
}
/*
	Get Feedburner - Creates the form for Feedburner accounts.
*/
function viperbar_get_feedburner($options, $button_style) {
	$feed_id = trim($options['form_id_feedburner']);
	$button_text = $options['text_button'];
	$feedburner_form = '<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open(\'http://feedburner.google.com/fb/a/mailverify?uri=' . $feed_id . '\', \'popupwindow\', \'scrollbars=yes,width=550,height=520\');return true" class="ViperBar_form" style="display:inline;">' .
		'<input type="text" name="email" placeholder="' . $options['placeholder_email'] . '" class="Viper_input_default viper_email_input"/>' .
		'<input type="hidden" value="' . $feed_id . '" name="uri"/>' .
		'<input type="hidden" name="loc" value="en_US"/>' .
		'<input type="submit" value="' . $button_text . '" id="ViperBar_submit" style="' . $button_style . '" /></form>';
	return $feedburner_form;
}
/*
	Get Aweeber - Creates the form for Aweber accounts.
*/
function viperbar_get_aweber($options, $button_style) {
	if (trim($options['aweber_thank_you'] != '')) {
		$redirect_url = $options['aweber_thank_you'];
	} else {
		$redirect_url = get_option( 'home' ) . '?viperbar_thanks=y';
	}

	$aweber_form = '<form class="ViperBar_form" action="http://www.aweber.com/scripts/addlead.pl" method="post" id="ViperBar_form_aweber" style="display:inline;">';
	if ($options['name_field_disable'] != 'on') {
		$aweber_form .= '<input type="text" name="name" placeholder="' . $options['placeholder_name'] . '" class="Viper_input_default viper_name_input" />';
	}
	$aweber_form .= '<input type="text" name="email" placeholder="' . $options['placeholder_email'] . '" class="Viper_input_default viper_email_input" />' .
		'<input type="hidden" name="listname" value="' . $options['form_id_aweber'] . '" />' .
		'<input type="hidden" name="meta_message" value="1" />' .
		'<input type="hidden" name="redirect" value="' . $redirect_url . '" />' .
		'<input type="submit" id="ViperBar_submit" value="' . $options['text_button'] . '" style="' . $button_style . '" />' .
		'</form>';
	return $aweber_form;
}
/*
	Get Mailchimp - Creates the form for Mailchimp accounts.
*/
function viperbar_get_mailchimp($options, $button_style) {
	include_once WP_PLUGIN_DIR . '/viperbar/includes/mailchimp/inc/MCAPI.class.php';
	$mailchimp_id = trim($options['form_id_mailchimp']);
	$mailchimp_list = trim($options['form_id_mailchimp_list']);
	
	
	$ViperBar_api = new MCAPI( $mailchimp_id );
	$merge_vars = $ViperBar_api->listMergeVars( $mailchimp_list );

	if (!$ViperBar_api->errorCode) {
		$inputs = '';
		foreach ($merge_vars as $var) {
			if ($var['field_type'] == 'text' && $options['name_field_disable'] != 'on' && $var['tag'] != 'LNAME') {
				$inputs .= '<input type="text" name="' . $var['tag'] . '" placeholder="' . $options['placeholder_name'] . '" class="Viper_input_default viper_name_input" />';
			} else if ($var['field_type'] == 'email') {
					$inputs .= '<input type="text" name="' . $var['tag'] . '" placeholder="' . $options['placeholder_email'] . '" class="Viper_input_default viper_email_input" />';
				}
		}

		$mailchimp_form = '<form method="post" id="viperbar_mailchimp_form" class="ViperBar_form">' . $inputs. '<input type="submit" value="' . $options['text_button'] . '" name="subscribe" id="ViperBar_submit" style="' . $button_style . '" />' . '</form>';
	}

	return $mailchimp_form;
}
/*
	Is Light - Determines whether a color is light or dark. This is used to choose the text color for the bar.
*/
function is_light($hexColor) {
	$r = hexdec(substr($hexColor, 1, 2));
	$g = hexdec(substr($hexColor, 3, 2));
	$b = hexdec(substr($hexColor, 5, 2));

	$average = ($r + $g + $b) / 3;

	if ($average > 122) {
		return true;
	} else {
		return false;
	}
}

?>