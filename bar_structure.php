var html =
	'<div id="ViperBar_main">' +
		'<div id="ViperBar_inner">' +
			'<?php
				echo
					"<div id=\"ViperBar_credit_logo\">".
						"<a href=\"http://www.ViperChill.com/viperbar/\" target=\"_blank\">".
							"<img src=\"".WP_PLUGIN_URL."/".$this->plugin_dir."/provided_by_ViperChill.png\">".
						"</a>".
					"</div>";
			?>' +
			'<?php
				if($_GET['Viper_thanks'] == "y") {
					echo "<div style=\"margin: 5px;\">".$options['text_thanks']."</div>";
				} else {
					echo $options['text_before'];
					
					if($options['form_id_feedburner'] != "") require("form_feedburner.php");
					if($options['form_id_aweber'] != "") require("form_aweber.php");
					if($options['form_id_mailchimp'] != "") require("form_mailchimp.php");
					
					echo $options['text_after'];
					echo $options['text_custom'];
				}
			?>' +
			'<div id="ViperBar_hide"><a href="#"><img src="<?php echo WP_PLUGIN_URL."/".$this->plugin_dir; ?>/icon-up.png"></a></div>' +
		'</div>' +
		'<div id="ViperBar_show"><a href="#"><img src="<?php echo WP_PLUGIN_URL."/".$this->plugin_dir; ?>/icon-down.png"></a></div>' +
	'</div>';