
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