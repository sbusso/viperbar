<?php
	$plugin_name = "ViperBar";
	$options = get_option($plugin_name."_options");
	
	function Viper_color_picker($slug,$options,$plugin_dir,$type = "single") {
		$resolution = array(
			"00","33","66","99","CC","FF"
		);
		
		if($type == "solid") {
			$query = "top=".$options[$slug.'_color']."&bottom=".$options[$slug.'_color'];
		}
		
		if($type == "single") {
			$query = "color=".$options[$slug.'_color'];
		}
		
		if($type == "two") {
			$query = "top=".$options['bar_color_top']."&bottom=".$options['bar_color_bottom'];
			
			$color_selector =
				"<div style=\"float: right;\">
					<input type=\"radio\" name=\"color_number\" value=\"top\" onClick=\"set_color_store('top');\"> Top
					<input type=\"radio\" name=\"color_number\" value=\"bottom\" onClick=\"set_color_store('bottom');\"> Bottom
					<input type=\"hidden\" name=\"color_selector_store\" id=\"Viper_color_selector_store\" value=\"\">
				</div>";
				
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
				
					$output .=
						"<div
							class=\"Viper_swatch\"
							style=\"background:#".$hex."\"
							onMouseOver=\"see_new_color('".$hex."','".$slug."')\"
							onClick=\"set_new_color('".$hex."','".$slug."','".$type."')\"
						></div>";
				}
				
			}
			
			$output .= "</div>";
		}
		
		$output .=
				"<div
					class=\"Viper_swatch_lg\"
					id=\"preview_color_".$slug."\"
					style=\"background: #".$options[$slug.'_color']."; float: left; margin: 0px; border: 0px; width: 42px;\"></div>
				<br style=\"clear: both;\">
			</div>
			<div style=\"margin: 10px;\">
				<table class=\"Viper_table\">
					<tr>
						<td style=\"text-align: right;\"><b>Current Bar:</b></td>
						<td><img id=\"current_color_".$slug."\" src=\"".WP_PLUGIN_URL."/".$plugin_dir."/gradient.php?width=530&".$query."\" alt=\"bar\"></td>
					</tr>
					<tr>
						<td style=\"text-align: right;\"><b>New Bar:</b></td>
						<td><img id=\"new_color_".$slug."\" src=\"".WP_PLUGIN_URL."/".$plugin_dir."/gradient.php?width=530&".$query."\" alt=\"bar\"></td>
					</tr>
				</table>
				<input type=\"hidden\" id=\"bar_color_input_".$slug."\" name=\"".$slug."_color\" value=\"".$options[$slug.'_color']."\">".$color_store."
				<br style=\"clear: both;\">
			</div>";
			
		return $output;
	}
	
	$plugin_dir = str_replace("/".basename(__FILE__),"",plugin_basename(__FILE__));
?>
<script type="text/javascript">
	function Viper_change_panel(panel,element) {
		jQuery('#Viper_panel_main').children().hide();
		jQuery('#Viper_panel_' + panel).show();
		jQuery('#Viper_sections').children().removeClass('Viper_active');
		jQuery('#Viper_sections').children().addClass('Viper_inactive');
		jQuery(element).addClass('Viper_active');
		jQuery(element).removeClass('Viper_inactive');
	}
	
	function Viper_change_subpanel(panel,subpanel,element) {
		jQuery('#Viper_subpanel_' + panel + '_main').children().hide();
		jQuery('#Viper_subpanel_' + panel + '_' + subpanel).show();
		jQuery('#Viper_subsections_' + panel).children().removeClass('Viper_active');
		jQuery('#Viper_subsections_' + panel).children().addClass('Viper_inactive');
		jQuery(element).addClass('Viper_active');
		jQuery(element).removeClass('Viper_inactive');
	}
	
	function see_new_color(hex,element) {
		document.getElementById('preview_color_' + element).style.background = '#' + hex;
	}
	
	function set_color_store(position) {
		document.getElementById('Viper_color_selector_store').value = position;
	}
	
	function set_new_color(hex,element,query_type) {
		document.getElementById('new_color_' + element).src = '<?php echo WP_PLUGIN_URL."/".$plugin_dir; ?>/gradient.php?width=530&color=' + hex;
	
		if(query_type == 'solid') {
			document.getElementById('new_color_' + element).src = '<?php echo WP_PLUGIN_URL."/".$plugin_dir; ?>/gradient.php?width=530&top=' + hex + '&bottom=' + hex;
			document.getElementById('bar_color_input_' + element).value = hex;
		}
		
		if(query_type == 'single') {
			document.getElementById('new_color_' + element).src = '<?php echo WP_PLUGIN_URL."/".$plugin_dir; ?>/gradient.php?width=530&color=' + hex;
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
			
			document.getElementById('new_color_' + element).src = '<?php echo WP_PLUGIN_URL."/".$plugin_dir; ?>/gradient.php?width=530&top=' + top_color + '&bottom=' + bottom_color;
		}
	}
	
	jQuery(document).ready(function() {
		Viper_change_panel('general', jQuery('.Viper_active'));
		Viper_change_subpanel('content','presets', jQuery('.Viper_active'));
		Viper_change_subpanel('appearance','bar', jQuery('.Viper_active'));
		jQuery('#Viper_reset_stats').click(function() {
			if (confirm("Are you sure you'd like to delete your stats? You cannot undo this.")) {
				jQuery.ajax({
					url: '<?php echo WP_PLUGIN_URL."/".$this->plugin_dir; ?>/stats.php?reset=true',
					success: function(data) {
						alert('Stats have been reset.');
					}
				});
			} else {
				return false;
			}
		});
	
		Viper_poll();
		
		jQuery('#Viper_main_form').show();
		jQuery('#Viper_loading').hide();
	});
	
	setInterval('Viper_poll()',2000);
	
	function Viper_poll() {
		jQuery.ajax({
			url: '<?php echo WP_PLUGIN_URL."/".$this->plugin_dir; ?>/stats.php',
			success: function(data) {
				var stat_array = eval('(' + data + ')');
				jQuery('#ViperBar_stats_impressions').html(stat_array['impressions']);
				jQuery('#ViperBar_stats_submits').html(stat_array['submits']);
				jQuery('#ViperBar_stats_conversion').html(stat_array['conversion'] + '%');
			}
		});
	}
</script>
<?php require("css.php"); ?>
<div id="Viper_main_container">
	<?php
		echo file_get_contents("http://www.viperchill.com/rss/plugin_header.php?plugin=".$plugin_dir);
		
		//echo "<pre>".print_r($options,true)."</pre>";
	?>
	<div id="Viper_loading">
		<h1>ViperBar is Loading...</h1>
		<img src="<?php echo WP_PLUGIN_URL."/".$this->plugin_dir; ?>/loading.gif" alt="loading">
	</div>
	<form method="post" action="" enctype="multipart/form-data" id="Viper_main_form">
		<div id="Viper_sections">
			<a onClick="Viper_change_panel('general',this);" class="Viper_active">General</a>
			<a onClick="Viper_change_panel('content',this);">Content</a>
			<a onClick="Viper_change_panel('appearance',this);">Appearance</a>
			<a onClick="Viper_change_panel('stats',this);">Stats</a>
			<a onClick="Viper_change_panel('preview',this);">Preview</a>
		</div>
		<div id="Viper_panel_main">
			<div id="Viper_panel_general">
				<div class="Viper_form_element">
					<div class="Viper_label">Enable ViperBar?</div>
					<div class="Viper_input">
						<input type="radio" name="enabled" value="true" <?php echo ($options['enabled'] == "true") ? 'checked="checked"' : ''; ?>>Yes
						<input type="radio" name="enabled" value="false" <?php echo ($options['enabled'] == "false") ? 'checked="checked"' : ''; ?>>No
					</div>
				</div>
				<br style="clear: both;">
			</div>
			<div id="Viper_panel_content">
				<div class="Viper_subsections" id="Viper_subsections_content">
					<a onClick="Viper_change_subpanel('content','presets',this);" class="Viper_active">Preset Forms</a>
					<a onClick="Viper_change_subpanel('content','custom',this);">Custom Text</a>
				</div>
				<div id="Viper_subpanel_content_main">
					<div id="Viper_subpanel_content_presets">
						<p>Quick note: if you fill in both the Aweber list ID, and the Feedburner ID, both forms will show up.</p>
						<div class="Viper_form_element">
							<div class="Viper_label">Text Before the Form:</div>
							<div class="Viper_input">
								<input type="text" name="text_before" value="<?php echo $options['text_before']; ?>">
							</div>
						</div>
						<div class="Viper_form_element">
							<div class="Viper_label">Text After the Form:</div>
							<div class="Viper_input">
								<input type="text" name="text_after" value="<?php echo $options['text_after']; ?>">
							</div>
						</div>
						<div class="Viper_form_element">
							<div class="Viper_label">Feedburner ID:</div>
							<div class="Viper_input">
								<p>Leave this blank to remove the form.</p>
								<input type="text" name="form_id_feedburner" value="<?php echo $options['form_id_feedburner']; ?>">
								<p>This is the feed name. (i.e. "ViperChill")</p>
							</div>
						</div>
						<br style="clear: both;">
						<div class="Viper_form_element">
							<div class="Viper_label">Aweber List ID:</div>
							<div class="Viper_input">
								<p>Leave this blank to remove the form.</p>
								<input type="text" name="form_id_aweber" value="<?php echo $options['form_id_aweber']; ?>">
								<p>This is the list name. (i.e. "ViperChill")</p>
							</div>
						</div>
						<br style="clear: both;">
						<div class="Viper_form_element">
							<div class="Viper_label">MailChimp API Key:</div>
							<div class="Viper_input">
								<input type="text" name="form_id_mailchimp" value="<?php echo $options['form_id_mailchimp']; ?>">
							</div>
							<p>You get this from the account drop-down, near the bottom.</p>
						</div>
						<div class="Viper_form_element">
							<div class="Viper_label">MailChimp Unique List ID:</div>
							<div class="Viper_input">
								<input type="text" name="form_id_mailchimp_list" value="<?php echo $options['form_id_mailchimp_list']; ?>">
							</div>
							<p>Go to your list settings. It's at the bottom.</p>
						</div>
						<br style="clear: both;">
						<div class="Viper_form_element">
							<div class="Viper_label">Thank you text:</div>
							<div class="Viper_input">
								<p>This is the text the user sees when they submit the Aweber or MailChimp form.</p>
								<input type="text" name="text_thanks" value="<?php echo $options['text_thanks']; ?>">
							</div>
						</div>
					</div>
					<div id="Viper_subpanel_content_custom">
						<div class="Viper_form_element">
							<div class="Viper_label">Custom Text:</div>
							<div class="Viper_input">
								<?php
									wp_tiny_mce( false , // true makes the editor "teeny"
										array(
											"editor_selector" => "Viper_editor"
										)
									);
								?>
								<textarea name="text_custom" class="Viper_editor"><?php echo stripslashes($options['text_custom']); ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="Viper_panel_appearance">
				<?php require("options_menu_appearance.php"); ?>
			</div><!-- End Viper_subpanel_appearance_main -->
			<div id="Viper_panel_stats">
				<style>
					#Viper_stats_table {
						width: 95%;
					}
					
					#Viper_stats_table tr td {
						font-size: 25px;
						text-align: right;
					}
					
					#Viper_stats_table tr td h2 {
						text-align: left;
					}
				</style>
				<table id="Viper_stats_table">
					<tr>
						<td><h2>Impressions</h2></td>
						<td id="ViperBar_stats_impressions"></td>
					</tr>
					<tr>
						<td><h2>Submissions</h2></td>
						<td id="ViperBar_stats_submits"></td>
					</tr>
					<tr>
						<td><h2>Conversion Rate</h2></td>
						<td id="ViperBar_stats_conversion"></td>
					</tr>
				</table>
				<a href="#" id="Viper_reset_stats">Reset Stats</a>
			</div>
			<div id="Viper_panel_preview">
				<div id="Viper_preview_pane">
					<p style="text-align: center;">
						[This is where the rest of your website would be.]
					</p>
					<div style="padding: 10px;">
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras in enim augue, eget rhoncus eros. In vitae adipiscing ipsum. Fusce hendrerit mauris in lectus ornare consectetur. Nulla facilisi. Cras id sem libero. Cras scelerisque, odio a sollicitudin varius, risus ante aliquam ligula, ac luctus libero nulla nec arcu. Pellentesque turpis diam, pharetra vitae sodales vehicula, pharetra vel diam. Nunc pretium, mi non ultrices aliquam, est arcu venenatis urna, at fringilla felis diam sit amet turpis. Sed at ligula lectus, at pulvinar mi. Praesent commodo posuere eros eget tempor. Sed nunc turpis, semper in malesuada in, elementum nec erat. Etiam consequat elementum volutpat. Aliquam erat volutpat.
						</p>
					</div>
				</div>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						<?php require("bar_structure.php"); ?>
		
						jQuery('#Viper_preview_pane').prepend(html);
		
						<?php require("bar_actions.php"); ?>
					});
				</script>
			</div>
		</div>
		<br style="clear: both;">
		<input type="submit" name="submit" value="Save" style="float: right;">
		<br style="clear: both;">
	</form>
</div>