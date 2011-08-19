jQuery(document).ready(function ($) {	
	$('#Viper_main_form').show();
	$('#enable_split_testing').change(function () {
		if ($(this).is(':checked')) {
			$('.split_header').show();
			$('#split_form_b').show();
			$('.split_favor_option').show();
		} else {
			$('.split_header').hide();
			$('#split_form_b').hide();
			$('.split_favor_option').hide();
		}
	});
	$('#favor_a').change(function () {
		$('#favor_b').attr('checked', false)
	});
	$('#favor_b').change(function () {
		$('#favor_a').attr('checked', false)
	});
	$('#Viper_subpanel_content_presets').submit(function () {
		if ($('#enable_split_testing').is(':checked')) {
			alert('Remember to reset your stats before making changes to your split test.')
		}
	});
});

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
function toggle_bar_type_panel(type) {
	jQuery('.Viper_bar_type_panel').hide();
	jQuery('#Viper_bar_type_panel_' + type).show();
	if(type == 'image') {
		jQuery('#viperbar_picker_options').hide();
	} else {
		jQuery('#viperbar_picker_options').show();
	}
}