<?php
/*
Plugin Name: ViperBar
Plugin URI: http://www.viperchill.com/wordpress-plugins/
Description: ViperBar adds an attractive bar to your site header, which you can use to increase blog or newsletter subscribers. It includes built in styling, Aweber & Feedburner integration, and conversion rate tracking.
Version: 1.2
Author: ViperChill
Author URI: http://www.viperchill.com
License: GPL2
	Copyright 2011  Glen Allsopp  (email : hq@viperchill.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists("ViperBar")) {
	class ViperBar {
		var $plugin_name;
		var $options_name;
		var $plugin_dir;
		var $called;
		
		function activate() {
			$default_options = array(
				'enabled' => 'true',
				'credit' => 'true',
				'bar_type' => 'single',
				'bar_single_color' => '999999',
				'bar__color' => '999999',
				'text_button' => 'Submit',
				'buttons_color' => 'FF6600',
				'text_thanks' => 'Thank you! Please check your inbox to confirm your subscription.'
			);
			
			add_option($this->options_name, $default_options);
			add_option('ViperBar_stats_submits', "0");
			add_option('ViperBar_stats_impressions', "0");
		}
		
		function ViperBar() { //constructor
			$this->plugin_name = "ViperBar";
			$this->options_name = $this->plugin_name."_options";
			$this->plugin_dir = str_replace("/".basename(__FILE__),"",plugin_basename(__FILE__));
		}
		
		function go($content = '') {
			$options = get_option($this->options_name);
			
			if($options['enabled'] == "true" && $this->called != true) {
				require("header.php");
				$this->called = true;
			}
		}
		
		function add_menu() {
			add_options_page(
				$this->plugin_name." Options",
				$this->plugin_name,
				"manage_options",
				$this->plugin_name,
				array(&$this, 'options_menu')
			);
		}
		
		function options_menu() {
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			
			if($_REQUEST['submit']) {
				if ($_FILES["file"]["error"] < 1) {
					$upload_dir = wp_upload_dir();
					
					move_uploaded_file($_FILES["file"]["tmp_name"],$upload_dir['basedir']."/ViperBar_custom_image.jpg");
				}
				
				update_option($this->options_name,$_REQUEST);
			}
			
			require("options_menu.php");
		}
	}
} //End Class ViperBar

if (class_exists("ViperBar")) {
	$ViperBar = new ViperBar();
}

//Actions and Filters
if (isset($ViperBar)) {
		wp_enqueue_script("jquery");
		register_activation_hook(__FILE__, array(&$ViperBar, 'activate'));
	
	//Actions
		add_action('admin_menu', array(&$ViperBar, 'add_menu'), 1);
		add_action('wp_footer', array(&$ViperBar, 'go'));
	
	//Filters
		//add_filter('wp_head', array(&$ViperBar, 'addContent'));
}
?>
