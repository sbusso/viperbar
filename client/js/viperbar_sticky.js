jQuery(document).ready(function ($) {
  var msie6 = $.browser == 'msie' && $.browser.version < 7;
  var v_bodypadding = $('body').css('paddingTop');
  if (!msie6) {
    var top = $('#ViperBar_main').offset().top;
    $(window).scroll(function (event) {
      var y = $(this).scrollTop();
      if (y >= top) { 
      	$('#ViperBar_main').addClass('fixed');
      	$('body').css('paddingTop', parseInt(v_bodypadding) + 38 + 'px');
      }
      else { $('#ViperBar_main').removeClass('fixed'); }
    });
  }
});