<?php
	function is_light($hexColor) {
		$r = hexdec(substr($hexColor,1,2));
		$g = hexdec(substr($hexColor,3,2));
		$b = hexdec(substr($hexColor,5,2));

		$average = ($r + $g + $b) / 3;

		if($average > 122) {
			return true;
		} else {
			return false;
		}
	}
?>
<style>
	#ViperBar_main {
		width: 100%;
		position: relative;
		z-index: 10000;
	}

	#ViperBar_inner {
		width: 100%;
		background:
			<?php
				if($options['bar_type'] == "solid" && $options['bar_solid_color'] != "") {
					echo
						"#".$options['bar_solid_color'];
					$check_color = $options['bar_solid_color'];
				} elseif($options['bar_type'] == "two" && $options['bar_color_bottom'] != "" && $options['bar_color_top'] != "") {
					echo
						"#".$options['bar_color_bottom']."
						url('".WP_PLUGIN_URL."/".$this->plugin_dir."/gradient.php?".
							"width=10&height=45&top=".$options['bar_color_top']."&bottom=".$options['bar_color_bottom']."')";
					$check_color = $options['bar_color_top'];
				} elseif($options['bar_type'] == "single" && $options['bar_single_color'] !="") {
					echo
						"#".$options['bar_single_color']."
						url('".WP_PLUGIN_URL."/".$this->plugin_dir."/gradient.php?".
							"width=10&height=45&color=".$options['bar_single_color']."')";
					$check_color = $options['bar_single_color'];
				} elseif($options['bar_type'] == "image") {
					$upload_dir = wp_upload_dir();
				
					echo
						"url('".$upload_dir['baseurl']."/ViperBar_custom_image.jpg')";
				} else {
					echo
						"#888888
						url('".WP_PLUGIN_URL."/".$this->plugin_dir."/gradient.php?".
							"width=10&height=45&color=888888')";
					$check_color = $options['bar_single_color'];
				}
			?>
			repeat-x;
		position: relative !important;
		padding: 5px 0px 0px 0px !important;
		height: 33px !important;
		overflow: hidden !important;
		color: #<?php if(is_light($check_color)) { echo "111111"; } else { echo "EEEEEE"; } ?> !important;
		font-size: 15px !important;
		font-family: arial, sans-serif !important;
		text-align: center;
	}
	
	#ViperBar_inner p {
		display: inline;
	}
	
	#ViperBar_credit_logo {
		position: absolute;
		top: 5px;
		left: 3px;
		margin: 3px 30px 0px 0px;
		display: none;
	}
	
	#ViperBar_hide, #ViperBar_show  {
		position: absolute;
		right: 9px;
		top: 9px;
		cursor: hand;
	}

	.ViperBar_form {
		padding: 0px 10px;
		display: inline;
	}
	
	.ViperBar_form input[type=text] {
		background: #FFFFFF !important;
		color: #999999 !important;
		border: 1px solid #999999 !important;
	}

	.ViperBar_form input {
		margin: 0px 3px 0px 3px !important;
		padding: 3px !important;
		display: inline !important;
		font-size: 14px !important;
		height: auto !important;
		font-weight: normal !important;
		width: auto !important;
		font-family: arial, sans-serif !important;
		text-decoration: none !important;
		text-transform: none !important;
		text-shadow: none !important;
		-moz-border-radius: 5px !important;
		-webkit-border-radius: 5px !important;
		border-radius: 5px !important;
	}
	
	.ViperBar_submit {
		background:
			#CCCCCC
			url('<?php echo WP_PLUGIN_URL."/".$this->plugin_dir; ?>/gradient.php?width=10&height=30&color=<?php echo $options['buttons_color']; ?>')
			repeat-x !important;
		color: #<?php if(is_light($options['buttons_color'])) { echo "000000"; } else { echo "FFFFFF"; } ?> !important;
		border: 0px !important;
	}
	
	#Viper_preview {
		border: 1px solid #555;
		background: #FFFFFF;
		padding: 5px;
	}
	
	.Viper_table tr td {
		padding: 3px;
	}
	
	.Viper_swatch {
		float: left;
		width: 17px;
		height: 10px;
	}
	
	.Viper_swatch_lg {
		border: 1px solid #000000;
		width: 60px;
		height: 60px;
		margin: 10px;
	}
	
	.Viper_color_picker {
		width: 654px;
		border: 1px solid #000000;
	}
	
	/* Common Viper Products */
		#Viper_main_container {
			width: 700px;
			border: 1px solid #CCCCCC;
			margin: 10px;
			padding: 10px;
		}
		
		#Viper_main_form {
			display: none;
		}
		
		#Viper_loading {
			text-align: center;
			padding: 30px;
		}
		
		#Viper_sections, .Viper_subsections {
			border-bottom: 1px solid #CCCCCC;
			margin: 0px -10px;
			overflow: hidden;
		}
		
		#Viper_sections {
			height: 50px;
		}
		
		.Viper_subsections {
			height: 30px;
		}
		
		.Viper_subsections a,#Viper_sections a  {
			display: block;
			float: left;
			border-right: 1px solid #CCCCCC;
			text-align: center;
			font-weight: bold;
		}
		
		#Viper_sections a {
			width: 143px;
			padding: 15px 0px;
			height: 50px;
		}
		
		.Viper_subsections a {
			width: 359px;
			padding: 5px 0px;
			height: 30px;
		}
		
		#Viper_sections a.Viper_inactive, .Viper_subsections a.Viper_inactive {
			background: #EEEEEE;
		}
		
		#Viper_sections a.Viper_active, .Viper_subsections a.Viper_active {
			background: #FFFFFF;
		}
		
		.Viper_input,.Viper_label {
			margin: 3px;
			display: block;
			width: 95%;
		}
		
		.Viper_form_element {
			float: left;
			width: 45%;
			margin: 5px;
		}
		
		.Viper_label {
			font-weight: bold;
			margin-top: 20px;
		}
		
		.Viper_input {
			margin-left: 20px;
		}
		
		.Viper_input input[type=text] {
			width: 95%;
		}
		
		#Viper_preview_pane {
			border: 1px solid #CCCCCC;
			height: 300px;
			margin: 20px;
		}
	/* End Common Viper Products */
</style>