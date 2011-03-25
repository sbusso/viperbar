<?php
	require("mailchimp/inc/MCAPI.class.php");
	
	$api = new MCAPI($options['form_id_mailchimp']);
	
	$merge_vars = $api->listMergeVars($options['form_id_mailchimp_list']);
	
	if(!$api->errorCode) {
		$inputs = "";
	
		if(sizeof($merge_vars) > 0) {
			foreach($merge_vars as $var) {
				if($var['field_type'] == "text" || $var['field_type'] == "email") {
					$inputs .= "<input type=\"text\" name=\"".$var['tag']."\" value=\"".$var['name']."\" class=\"Viper_input_default\">";
				}
			}
		}
	}
	
	echo
		"<form action=\"".WP_PLUGIN_URL."/".$this->plugin_dir."/mailchimp/subscribe.php\" method=\"post\" class=\"ViperBar_form\">".
			"<input type=\"hidden\" name=\"redirect\" value=\"".get_bloginfo('url')."\">".
			$inputs.
			"<input type=\"submit\" value=\"".$options['text_button']."\" name=\"subscribe\" class=\"ViperBar_submit\">".
		"</form>";
?>