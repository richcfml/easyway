// JavaScript Document
<!--MenuStuck JS STARTS HERE-->
var num = 136; //number of pixels before modifying styles
  $(window).bind('scroll', function () {
	  if ($(window).scrollTop() > num) {
		  
		  $('#navigation').addClass('fixed');
	  } else {
		  $('#navigation').removeClass('fixed');
	  }
  });
  <!--MenuStuck JS ENDS HERE-->
  
  <!--ScrollToTop JS STARTS HERE-->
  $(document).ready(function(){
$("a[href='#top']").click(function() {
  $("html, body").animate({ scrollTop: 0 }, "300");
  return false;
});
	 });
  
  <!--ScrollToTop JS ENDS HERE-->