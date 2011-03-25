function ViperBar_hide() {
	jQuery('#ViperBar_inner').hide();
	jQuery('#ViperBar_show').show();
	
	var height = jQuery('#ViperBar_main').css("height");
	var background = jQuery('body').css("background-position");
	
	height = parseInt(height.replace("px",""));
	background = parseInt(background.replace("px",""));
	var position = background - height;
	
	jQuery('body').css("background-position","0px " + position.toString() + "px");
}

function ViperBar_show() {
	jQuery('#ViperBar_inner').show();
	jQuery('#ViperBar_show').hide();
	
	var height = jQuery('#ViperBar_main').css("height");
	var background = jQuery('body').css("background-position");
	
	height = parseInt(height.replace("px",""));
	background = parseInt(background.replace("%",""));
	var position = background + height;
	
	jQuery('body').css("background-position","0px " + position.toString() + "px");
}

jQuery('#ViperBar_main').css(
	"margin-top",
	"-" + jQuery('body').css("padding-top")
);

jQuery('#ViperBar_main').css(
	"margin-right",
	"-" + jQuery('body').css("padding-right")
);

jQuery('#ViperBar_main').css(
	"margin-left",
	"-" + jQuery('body').css("padding-left")
);

var width = jQuery('#ViperBar_main').css("width");
width = parseInt(width.replace("px",""));

var marginLeft = jQuery('#ViperBar_main').css("margin-left");
marginLeft = parseInt(marginLeft.replace("px","")) * -1;

var marginRight = jQuery('#ViperBar_main').css("margin-right");
marginRight = parseInt(marginRight.replace("px","")) * -1;

jQuery('#ViperBar_main').css("width",(width + marginLeft + marginRight));

jQuery('#ViperBar_hide').click(function() {
	ViperBar_hide();
});

jQuery('#ViperBar_show').click(function() {
	ViperBar_show();
});

jQuery('#ViperBar_inner').hide();
jQuery('#ViperBar_show').hide();

ViperBar_show();

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