jQuery(document).ready(function($) {
	var placeholderSupported = Viper_isPlaceholderSupported();
	if (placeholderSupported == false) {
		$('.Viper_input_default').each(function() {
			var intendedPlaceholder = $(this).attr('placeholder');
			$(this).val(intendedPlaceholder);
			$(this).focus(function() {
				if ($(this).val() == intendedPlaceholder) {
					$(this).val('');
					$(this).css('color', '#000');
				}
			});
			$(this).blur(function() {
				if ($(this).val() == '') {
					$(this).val(intendedPlaceholder);
					$(this).css('color', '#ccc');
				}
			});
		});
	}
	$('.Viper_input_default').focus(function() {
		$('#ViperBar_inner').fadeTo(100, 1);
		$('.Viper_input_default').fadeTo(0, 0.8);
		$(this).fadeTo(0, 1);
	});
	$('.Viper_input_default').blur(function() {
		$('.Viper_input_default').fadeTo(0, 1);
	});
	var bodyPaddingLeft = $('body').css('paddingLeft');
	if (bodyPaddingLeft != '0px') {
		$('#ViperBar_main').css('marginLeft', '-' + bodyPaddingLeft);
	}
	var bodyPaddingRight = $('body').css('paddingRight');
	if (bodyPaddingRight != '0px') {
		$('#ViperBar_main').css('marginRight', '-' + bodyPaddingRight);
	}
	var bodyPaddingTop = $('body').css('paddingTop');
	if (bodyPaddingTop != '0px') {
		$('#ViperBar_main').css('marginTop', '-' + bodyPaddingTop);
	}
	var backgroundImage = $('body').css('backgroundImage');
	if (backgroundImage != '') {
		var viperHeight = parseInt($('#ViperBar_main').height()) - 1;
		$('body').css('backgroundPosition', '0px ' + viperHeight + 'px');
	
	}
	
	$('body').prepend($('#ViperBar_main'));
	$('#ViperBar_main').show();
	if (viperbar.cookie_hide == 'true') {
		if (readCookie('viperbar_set_hide') == 'hide') {
			$('#ViperBar_inner').hide();
			$('#ViperBar_show').show();
			$('#ViperBar_main').css('height', '0px');
		}
	}
	$('.viper_email_input').focus(function() {
		if ($(this).val() == 'Email Address') {
			$(this).val('');
		}
	}).blur(function() {
		if ($(this).val() == '') {
			$(this).val('Email Address');
		}
	});
	$('.viper_name_input').focus(function() {
		if ($(this).val() == 'Name') {
			$(this).val('');
		}
	}).blur(function() {
		if ($(this).val() == '') {
			$(this).val('Name');
		}
	});
	$('#ViperBar_hide').click(function() {
		$('#ViperBar_inner').hide();
		$('#ViperBar_show').show();
		$('#ViperBar_main').css('height', '0px');
		if (viperbar.cookie_hide == 'true') {
			createCookie('viperbar_set_hide', 'hide', viperbar.cookie_days);
		}
	});
	$('#ViperBar_show').click(function() {
		$('#ViperBar_show').hide();
		$('#ViperBar_inner').show();
		$('#ViperBar_main').css('height', '40px');
		eraseCookie('viperbar_set_hide');
	});
	$('.ViperBar_form').submit(function(e) {
		e.preventDefault();
		var self = this;
		var vip_post_id = $('#ViperBar_main').attr('rel');
		var submission_data = "action=viperbar_ajax&split_test_option=" + viperbar.split_test_option + "&viperbar_submission_nonce=" + viperbar.viperbar_submission_nonce + "&formtype=" + viperbar.form_type + "&postid=" + vip_post_id;
		if (viperbar.form_type == 'mailchimp') {
			$('.Viper_input_default').each(function() {
				submission_data += "&" + $('.Viper_input_default').attr('name') + '=' + ($('.Viper_input_default').val());
			});
		}
		$('#viperbar_form_content').hide();
		$('#viperbar_ajaxload').fadeIn('normal');
		$.ajax({
			type: "POST",
			url: viperbar.ajaxurl,
			data: submission_data,
			success: function(msg) {
				if (viperbar.form_type == 'aweber') {
					self.submit();
				} else {
					$('#viperbar_form_content').hide();
					$('#viperbar_ajaxload').hide();
					$('#viperbar_thanks').show();
				}
			}
		});
	});

	function Viper_isPlaceholderSupported() {
		var input = document.createElement("input");
		return ('placeholder' in input);
	}
});

function createCookie(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = "; expires=" + date.toGMTString();
	} else var expires = "";
	document.cookie = name + "=" + value + expires + "; path=/";
}
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}
function eraseCookie(name) {
	createCookie(name, "", -1);
}
