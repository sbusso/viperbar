<?php
	echo
		"<form ".
			"id=\"Viper_form_feedburner\"".
			"class=\"ViperBar_form\"".
			"action=\"http://feedburner.google.com/fb/a/mailverify\"".
			"method=\"post\"".
			"target=\"popupwindow\"".
		">".
			"<input type=\"text\" name=\"email\" value=\"Email Address\" class=\"Viper_input_default\"> ".
			"<input type=\"hidden\" value=\"".$options['form_id_feedburner']."\" name=\"uri\">".
			"<input type=\"hidden\" name=\"Viper_form_type\" value=\"feedburner\" id=\"Viper_feedburner_id\">".
			"<input type=\"hidden\" name=\"loc\" value=\"en_US\">".
			"<input type=\"Submit\" class=\"ViperBar_submit\" value=\"".$options['text_button']."\">".
		"</form>";
?>