jQuery('#ViperBar_hide').click(function() {
	jQuery('#ViperBar_inner').slideUp("fast");
	jQuery('#ViperBar_show').slideDown("fast");
});

jQuery('#ViperBar_show').click(function() {
	jQuery('#ViperBar_inner').slideDown("fast");

	jQuery('#ViperBar_show').slideUp("fast");
});

jQuery('#ViperBar_inner').hide();

jQuery('#ViperBar_show').hide();
jQuery('#ViperBar_inner').slideDown("fast");

jQuery('.Viper_input_default').focus(function() {
	jQuery(this).val("");
});
		
jQuery('.ViperBar_form').submit(function() {
	jQuery.ajax({
		async: false,
		url: '<?php echo WP_PLUGIN_URL."/".$this->plugin_dir; ?>/stats.php?increment=true&type=submit',
		success: function() {
			if(jQuery('#Viper_feedburner_id').val() == "feedburner") {
				window.open(
					'http://feedburner.google.com/fb/a/mailverify?uri="<?php echo $options['form_id_feedburner']; ?>',
					'popupwindow',
					'scrollbars=yes,width=550,height=520'
				);
				
				return true
			}
		}
	});
	
	return true;
});

jQuery.ajax({
	url: '<?php echo WP_PLUGIN_URL."/".$this->plugin_dir; ?>/stats.php?increment=true&type=impression',
});