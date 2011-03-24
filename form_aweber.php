<?php
	echo
		"<form class=\"ViperBar_form\" action=\"http://www.aweber.com/scripts/addlead.pl\" method=\"post\" id=\"ViperBar_form_aweber\">".
			"<input type=\"text\" name=\"name\" value=\"Name\" class=\"Viper_input_default\" />".
			"<input type=\"text\" name=\"email\" value=\"Email Address\" class=\"Viper_input_default\" />".
			"<input type=\"hidden\" name=\"listname\" value=\"".$options['form_id_aweber']."\" />".
			"<input type=\"hidden\" name=\"meta_message\" value=\"1\" />".
			"<input type=\"hidden\" name=\"redirect\" value=\"".site_url()."?Viper_thanks=y\" />".
			"<input type=\"Submit\" class=\"ViperBar_submit\" value=\"".$options['text_button']."\">".
		"</form>";
?>