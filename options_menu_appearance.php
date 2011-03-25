<div class="Viper_subsections" id="Viper_subsections_appearance">
	<a onClick="Viper_change_subpanel('appearance','bar',this);" class="Viper_active">Bar</a>
	<a onClick="Viper_change_subpanel('appearance','buttons',this);">Buttons</a>
</div>
<div id="Viper_subpanel_appearance_main">
	<div id="Viper_subpanel_appearance_bar">
		<div class="Viper_form_element" style="width: 95%;">
			<div class="Viper_label">Select bar style:</div>
			<div class="Viper_input">
				<input
					type="radio"
					name="bar_type"
					value="solid"
					<?php echo ($options['bar_type'] == "solid") ? 'checked="checked"' : ''; ?>
					onClick="toggle_bar_type_panel('solid')"
				>
					Solid Color
				<input type="radio" name="bar_type" value="single" <?php echo ($options['bar_type'] == "single") ? 'checked="checked"' : ''; ?> onClick="toggle_bar_type_panel('single')">
					Single Color
				<input type="radio" name="bar_type" value="two" <?php echo ($options['bar_type'] == "two") ? 'checked="checked"' : ''; ?> onClick="toggle_bar_type_panel('two')">
					Two Color
				<input type="radio" name="bar_type" value="image" <?php echo ($options['bar_type'] == "image") ? 'checked="checked"' : ''; ?> onClick="toggle_bar_type_panel('image')">
					Image Upload
			</div>
		</div>
		<br style="clear: both;">
		<div id="Viper_bar_type_panel_main">
			<div id="Viper_bar_type_panel_solid" class="Viper_bar_type_panel">
				<div class="Viper_label">Choose your bar's solid color:</div>
				<div class="Viper_input">
					<?php echo Viper_color_picker("bar_solid",$options,$plugin_dir,"solid"); ?>
				</div>
			</div>
			<div id="Viper_bar_type_panel_single" class="Viper_bar_type_panel">
				<div class="Viper_label">Choose your bar's gradient color:</div>
				<div class="Viper_input">
					<?php echo Viper_color_picker("bar_single",$options,$plugin_dir,"single"); ?>
				</div>
			</div>
			<div id="Viper_bar_type_panel_two" class="Viper_bar_type_panel">
				<div class="Viper_label">Choose your bar's two colors:</div>
				<div class="Viper_input">
					<?php echo Viper_color_picker("bar_two",$options,$plugin_dir,"two"); ?>
				</div>
			</div>
			<div id="Viper_bar_type_panel_image" class="Viper_bar_type_panel">
				<div class="Viper_label">Upload your own image (jpg only):</div>
				<div class="Viper_input">
					<p>This image will be repeated horizontally, but not vertically.</p>
					<input type="file" name="file" id="file" />
					<?php
						$upload_dir = wp_upload_dir();
						
						if(file_exists($upload_dir['basedir']."/ViperBar_custom_image.jpg")) {
							echo
								"<p><b>Current Image:</b></p>
								<p style=\"padding: 5px;\"><img src=\"".$upload_dir['baseurl']."/ViperBar_custom_image.jpg\" style=\"border: 1px solid #AAAAAA;\"></p>";
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<div id="Viper_subpanel_appearance_buttons">
		<div class="Viper_form_element">
			<div class="Viper_label">Button Text:</div>
			<div class="Viper_input">
				<input type="text" name="text_button" value="<?php echo $options['text_button']; ?>">
			</div>
		</div>
		<br style="clear: both;">
		<div class="Viper_label">Choose your button color:</div>
		<div class="Viper_input">
			<p>
				This will only affect the buttons in the preset forms (see the content tab).
			</p>
		</div>
		<?php echo Viper_color_picker("buttons",$options,$plugin_dir); ?>
	</div>
</div>
<script type="text/javascript">
	function toggle_bar_type_panel(type) {
		jQuery('.Viper_bar_type_panel').hide();
		jQuery('#Viper_bar_type_panel_' + type).show();
	}
	
	toggle_bar_type_panel('<?php echo $options['bar_type']; ?>');
</script>