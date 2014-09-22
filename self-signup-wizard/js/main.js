/*
* Facebox (for jQuery)
* version: 1.2 (05/05/2008)
* @requires jQuery v1.2 or later
*
* Examples at http://famspam.com/facebox/
*
* Licensed under the MIT:
* http://www.opensource.org/licenses/mit-license.php
*
* Copyright 2007, 2008 Chris Wanstrath [ chris@ozmm.org ]
*
* Usage:
*
* jQuery(document).ready(function() {
* jQuery('a[rel*=facebox]').facebox()
* })
*
* <a href="#terms" rel="facebox">Terms</a>
* Loads the #terms div in the box
*
* <a href="terms.html" rel="facebox">Terms</a>
* Loads the terms.html page in the box
*
* <a href="terms.png" rel="facebox">Terms</a>
* Loads the terms.png image in the box
*
*
* You can also use it programmatically:
*
* jQuery.facebox('some html')
* jQuery.facebox('some html', 'my-groovy-style')
*
* The above will open a facebox with "some html" as the content.
*
* jQuery.facebox(function($) {
* $.get('blah.html', function(data) { $.facebox(data) })
* })
*
* The above will show a loading screen before the passed function is called,
* allowing for a better ajaxy experience.
*
* The facebox function can also display an ajax page, an image, or the contents of a div:
*
* jQuery.facebox({ ajax: 'remote.html' })
* jQuery.facebox({ ajax: 'remote.html' }, 'my-groovy-style')
* jQuery.facebox({ image: 'stairs.jpg' })
* jQuery.facebox({ image: 'stairs.jpg' }, 'my-groovy-style')
* jQuery.facebox({ div: '#box' })
* jQuery.facebox({ div: '#box' }, 'my-groovy-style')
*
* Want to close the facebox? Trigger the 'close.facebox' document event:
*
* jQuery(document).trigger('close.facebox')
*
* Facebox also has a bunch of other hooks:
*
* loading.facebox
* beforeReveal.facebox
* reveal.facebox (aliased as 'afterReveal.facebox')
* init.facebox
* afterClose.facebox
*
* Simply bind a function to any of these hooks:
*
* $(document).bind('reveal.facebox', function() { ...stuff to do after the facebox and contents are revealed... })
*
*/
(function($) {
  $.facebox = function(data, klass) {
    $.facebox.loading()

    if (data.ajax) fillFaceboxFromAjax(data.ajax, klass)
    else if (data.image) fillFaceboxFromImage(data.image, klass)
    else if (data.div) fillFaceboxFromHref(data.div, klass)
    else if ($.isFunction(data)) data.call($)
    else $.facebox.reveal(data, klass)
  }

  /*
* Public, $.facebox methods
*/

  $.extend($.facebox, {
    settings: {
      opacity : 0.2,
      overlay : true,
      loadingImage : '/images/loading.gif',
      closeImage : '/images/closelabel.gif',
      imageTypes : [ 'png', 'jpg', 'jpeg', 'gif' ],
      faceboxHtml : '\
<div id="facebox" style="display:none;"> \
<div class="popup"> \
<div class="content"> \
</div> \
<a href="#" class="close"><img src="/facebox/closelabel.png" title="close" class="close_image" /></a> \
</div> \
</div>'
    },

    loading: function() {
      init()
      if ($('#facebox .loading').length == 1) return true
      showOverlay()

      $('#facebox .content').empty()
      $('#facebox .body').children().hide().end().
        append('<div class="loading"><img src="'+$.facebox.settings.loadingImage+'"/></div>')

      $('#facebox').css({
        top:	(getPageScroll()[1] + (getPageHeight() / 10)) - 80,
        left:	$(window).width() / 2 - 205
      }).show()

      $(document).bind('keydown.facebox', function(e) {
        if (e.keyCode == 27) $.facebox.close()
        return true
      })
      $(document).trigger('loading.facebox')
    },

    reveal: function(data, klass) {
      $(document).trigger('beforeReveal.facebox')
      if (klass) $('#facebox .content').addClass(klass)
      $('#facebox .content').append(data)
      $('#facebox .loading').remove()
      $('#facebox .body').children().fadeIn('normal')
      $('#facebox').css('left', $(window).width() / 2 - ($('#facebox .popup').width() / 2))
      $(document).trigger('reveal.facebox').trigger('afterReveal.facebox')
    },

    close: function() {
      $(document).trigger('close.facebox')
      return false
    }
  })

  /*
* Public, $.fn methods
*/

  $.fn.facebox = function(settings) {
    if ($(this).length == 0) return

    init(settings)

    function clickHandler() {
      $.facebox.loading(true)

      // support for rel="facebox.inline_popup" syntax, to add a class
      // also supports deprecated "facebox[.inline_popup]" syntax
      var klass = this.rel.match(/facebox\[?\.(\w+)\]?/)
      if (klass) klass = klass[1]

      fillFaceboxFromHref(this.href, klass, this.rev)
      return false
    }

    return this.bind('click.facebox', clickHandler)
  }

  /*
* Private methods
*/

  // called one time to setup facebox on this page
  function init(settings) {
    if ($.facebox.settings.inited) return true
    else $.facebox.settings.inited = true

    $(document).trigger('init.facebox')
    makeCompatible()

    var imageTypes = $.facebox.settings.imageTypes.join('|')
    $.facebox.settings.imageTypesRegexp = new RegExp('\.(' + imageTypes + ')$', 'i')

    if (settings) $.extend($.facebox.settings, settings)
    $('body').append($.facebox.settings.faceboxHtml)

    var preload = [ new Image(), new Image() ]
    preload[0].src = $.facebox.settings.closeImage
    preload[1].src = $.facebox.settings.loadingImage

    $('#facebox').find('.b:first, .bl').each(function() {
      preload.push(new Image())
      preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1')
    })

    $('#facebox .close').click($.facebox.close)
    $('#facebox .close_image').attr('src', $.facebox.settings.closeImage)
  }

  // getPageScroll() by quirksmode.com
  function getPageScroll() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
      yScroll = self.pageYOffset;
      xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	// Explorer 6 Strict
      yScroll = document.documentElement.scrollTop;
      xScroll = document.documentElement.scrollLeft;
    } else if (document.body) {// all other Explorers
      yScroll = document.body.scrollTop;
      xScroll = document.body.scrollLeft;
    }
    return new Array(xScroll,yScroll)
  }

  // Adapted from getPageSize() by quirksmode.com
  function getPageHeight() {
    var windowHeight
    if (self.innerHeight) {	// all except Explorer
      windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
      windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
      windowHeight = document.body.clientHeight;
    }
    return windowHeight
  }

  // Backwards compatibility
  function makeCompatible() {
    var $s = $.facebox.settings

    $s.loadingImage = $s.loading_image || $s.loadingImage
    $s.closeImage = $s.close_image || $s.closeImage
    $s.imageTypes = $s.image_types || $s.imageTypes
    $s.faceboxHtml = $s.facebox_html || $s.faceboxHtml
  }

  // Figures out what you want to display and displays it
  // formats are:
  // div: #id
  // image: blah.extension
  // ajax: anything else
  
  function fillFaceboxFromHref(href, klass, rev) {
    // div
    if (href.match(/#/)) {
      var url = window.location.href.split('#')[0]
      var target = href.replace(url, '')
      $.facebox.reveal($(target).clone().show(), klass)
    // image
    } else if (href.match($.facebox.settings.imageTypesRegexp)) {
      fillFaceboxFromImage(href, klass)
      // iframe
    } else if (rev.split('|')[0] == 'iframe') {
      fillFaceboxFromIframe(href, klass, rev.split('|')[1])
      // ajax
    } else {
      fillFaceboxFromAjax(href, klass)
    }
  }

  function fillFaceboxFromImage(href, klass) {
    var image = new Image()
    image.onload = function() {
      $.facebox.reveal('<div class="image"><img src="' + image.src + '" /></div>', klass)
    }
    image.src = href
  }

  function fillFaceboxFromAjax(href, klass) {
    $.get(href, function(data) { $.facebox.reveal(data, klass) })
  }

  function skipOverlay() {
    return $.facebox.settings.overlay == false || $.facebox.settings.opacity === null
  }

  function showOverlay() {
    if (skipOverlay()) return

    if ($('#facebox_overlay').length == 0)
      $("body").append('<div id="facebox_overlay" class="facebox_hide"></div>')

    $('#facebox_overlay').hide().addClass("facebox_overlayBG")
      .css('opacity', $.facebox.settings.opacity)
      .click(function() { $(document).trigger('close.facebox') })
      .fadeIn(200)
    return false
  }

  function hideOverlay() {
    if (skipOverlay()) return

    $('#facebox_overlay').fadeOut(200, function(){
      $("#facebox_overlay").removeClass("facebox_overlayBG")
      $("#facebox_overlay").addClass("facebox_hide")
      $("#facebox_overlay").remove()
    })

    return false
  }
  
  function fillFaceboxFromIframe(href, klass, height) {
	$.facebox.reveal('<iframe scrolling="no" marginwidth="0" height="795px" width="930px" frameborder="0" src="' + href + '" marginheight="0"></iframe>', klass)
  }

  /*
* Bindings
*/

  $(document).bind('close.facebox', function() {
    $(document).unbind('keydown.facebox')
    $('#facebox').fadeOut(function() {
      $('#facebox .content').removeClass().addClass('content')
      $('#facebox .loading').remove()
      $(document).trigger('afterClose.facebox')
    })
    hideOverlay()
  })

})(jQuery);

(function($){
	$(function() {		
		$('a[rel*=facebox]').click(function() {
			if($("#restaurant_address").val() == "" || $("#restaurant_city").val() == "" || $("#restaurant_state").val() == "") {
				alert("Please provide restaurant address, city and state in Step 2.");
			} else {
				var link = $("#facebox_link");
				link.attr("href", "tab_restaurant_delivery_zones.php?address=" + $("#restaurant_address").val() + "&city=" + $("#restaurant_city").val() + "&state=" + $("#restaurant_state").val() + "");
				link.facebox();
				link.trigger("click");
			}
			return false;
		});

		$("#login-id, #reg-id").click(function() {
			var id = $(this).attr("id");
			if(id == "login-id") {
				$('#login-div').show();
				$('#reg-div').hide();
				$("#register_desciption").hide();
				$("#login_desciption").slideDown();
			} else if(id == "reg-id") {
				$('#login-div').hide();
				$('#reg-div').show();
				$("#login_desciption").hide();
				$("#register_desciption").slideDown();
			}
			return false;
		});	
		
		$("#fax, #email, #pos").click(function() {
			var id = $(this).attr("id");
			if(id=='fax')
				{
					$('#FaxDivId').show();
					$('#EmailDivId').hide();
					$('#POSDivId').hide();	
				}
				else if(id=='email')
				{
					$('#FaxDivId').hide();
					$('#EmailDivId').show();
					$('#POSDivId').hide();	
				}
				else if(id=='pos')
				{
					$('#FaxDivId').hide();
					$('#EmailDivId').hide();
					$('#POSDivId').show();	
				}
		});	
		
		$("#fax, #email, #pos").click(function() {
			var id = $(this).attr("id");
			if(key=='fax')
				{
					$('#FaxDivId').show();
					$('#EmailDivId').hide();
					$('#POSDivId').hide();	
				}
				else if(key=='email')
				{
					$('#FaxDivId').hide();
					$('#EmailDivId').show();
					$('#POSDivId').hide();	
				}
				else if(key=='pos')
				{
					$('#FaxDivId').hide();
					$('#EmailDivId').hide();
					$('#POSDivId').show();	
				}
		});
		
		$("#del_yes, #del_no").click(function() {
			var id = $(this).attr("id");
			if(id == "del_yes") {
				$('#del_yes_div').show();
				$('#del_no_div').hide();
			} else if(id == "del_no") {
				$('#del_yes_div').hide();
				$('#del_no_div').show();			
			}
		});
		
		$("#del_yes, #del_no").click(function() {
			var id = $(this).attr("id");
			if(id == "del_yes") {
				$('#del_yes_div').show();
				$('#del_no_div').hide();
			} else if(id == "del_no") {
				$('#del_yes_div').hide();
				$('#del_no_div').show();			
			}
		});
		
		
		$("#delRad, #cusDelZon").click(function() {
			var id = $(this).attr("id");
			if(id == "delRad") {
				$('#delRadDiv').show();
				$('#cusDelZonDiv').hide();
			} else if(id == "cusDelZon") {
				$('#delRadDiv').hide();
				$('#cusDelZonDiv').show();			
			}
		});
		
		$("#uploadMenu, #uploadMenu2, #uploadMenu3").click(function() {
			var id = $(this).attr("id");
			if(id == "uploadMenu") {
				$('#uploadMenuDiv').show();
				$('#uploadMenuDiv2').hide();
				$('#uploadMenuDiv3').hide();
			} else if(id == "uploadMenu2") {
				$('#uploadMenuDiv').hide();
				$('#uploadMenuDiv2').show();
				$('#uploadMenuDiv3').hide();			
			}else if(id == "uploadMenu3") {
				$('#uploadMenuDiv').hide();
				$('#uploadMenuDiv2').hide();
				$('#uploadMenuDiv3').show();			
			}
		});
		
		$("#credit_card_yes, #credit_card_no").click(function() {
			if($(this).attr("id") == "credit_card_yes") {
				$('#cashDivYes').show();
			} else {
				$('#cashDivYes').hide();		
			}
		});	
		
		
		$("#domain1, #domain2, #domain3").click(function() {
			var id = $(this).attr("id");
			if(id == "domain1") {
				$('#domainDiv1').show();
				$('#domainDiv2').hide();
				$('#domainDiv3').hide();
			} else if(id == "domain2") {
				$('#domainDiv1').hide();
				$('#domainDiv2').show();
				$('#domainDiv3').hide();		
			}else if(id == "domain3") {
				$('#domainDiv1').hide();
				$('#domainDiv2').hide();
				$('#domainDiv3').show();		
			}
		});	

		$("#domainName1, #domainName2").click(function() {
			var id = $(this).attr("id");
			if(id == "domainName1") {
				$("#domainDiv2").show();
				$("#domainDiv3").hide();
				$("#hosting_information_container").show();
			} else if(id == "domainName2") {
				$("#domainDiv2").hide();
				$("#domainDiv3").show();
				if(!$("#wesbite_integration1").is(":checked")) {
					$("#hosting_information_container").hide();
				}
			}
		});
		$("#wesbite_integration1, #wesbite_integration2, #wesbite_integration3").click(function() {
			var id = $(this).attr("id");
			if(id == "wesbite_integration1") {
				$("#hosting_information_container").show();
			} else {
				if(!$("#domainName1").is(":checked")) {
					$("#hosting_information_container").hide();
				}
			}
		});		

		$("#hosting_integration_type1, #hosting_integration_type2, #hosting_integration_type3").click(function() {
			var id = $(this).attr("id");
			if(id == "hosting_integration_type1") {
				$("#hosting_div1").show();
				$("#hosting_div2").hide();
			} else if(id == "hosting_integration_type2") {
				$("#hosting_div1").hide();
				$("#hosting_div2").show();			
			}
		});
		
		$("#credit_card_payment_option1,#credit_card_payment_option2,#credit_card_payment_option3").click(function() {
			if($(this).attr("id") == "credit_card_payment_option1") {
				$("#creditCardOptionDiv").show();
			} else {
				$("#creditCardOptionDiv").hide();
			}
		});
		
		var active_tab = 1;
		$("#btn_next").click(function(){
			activate_tab(active_tab, parseInt(active_tab) + 1);
		});
		$("#btn_previous").click(function(){
			activate_tab(active_tab, parseInt(active_tab) - 1);
		});
		$("#btn_skip").click(function(){
			$("#tab_" + active_tab + " .tile").removeClass("selected visited");
			$("#tab_" + active_tab + " .tab_label").removeClass("selected visited");
			$("#tab_" + active_tab + " .tile").addClass("skipped");
			$("#tab_" + active_tab + " .tab_label").addClass("skipped");
			activate_tab(active_tab, parseInt(active_tab) + 1);
		});
		$(".left-col .tile, .left-col .tab_label").click(function() {
			var new_tab = $(this).parent("div").attr("id").substr(-1);
			if(new_tab == active_tab) return;
			activate_tab(active_tab, new_tab);
		});
		
		var is_logged_in = false;
		function activate_tab(active_tab_id, new_tab_id) {
			if(active_tab_id == 1 && $('#login-div').is(":visible") && !is_logged_in) {
				$("#login_error_container").hide();
				$(".login_form_container").hide();
				$(".loading_container").show();
				$.post(
					"ajax_response.php", 
					{
						"action": "authenticate_user"
						, "username" : $("#client_login_usrname").val()
						, "password" : $("#client_login_password").val()
					}
					, function(data) {
						var num = parseInt(data);
						if(num > 0) {
							$("#client_login_id").val(num);
							$(".login_success_container").show();
							$(".loading_container").hide();
							is_logged_in = true;
							hide_show_tabs(active_tab_id, new_tab_id);
						} else {
							$(".login_form_container").show();
							$("#login_error_container").show();
							$(".loading_container").hide();
						}
					}
				);
			} else {
				if(active_tab_id > 1 && $('#login-div').is(":visible") && !is_logged_in) {
					return false;
				} else {
					hide_show_tabs(active_tab_id, new_tab_id);
				}
			}
		}
		function hide_show_tabs(active_tab_id, new_tab_id) {
                    if(premium_account){
                        var last_tab = 7;
                    } else {
                        var last_tab=6;
                    }
			if(new_tab_id == last_tab) {
				$("#btn_next").hide();
				$("#btn_skip").hide();
				$("#btn_submit").show();
				$("#btn_previous").show();
			} else if(new_tab_id == 1) {
				$("#btn_previous").hide();
				$("#btn_skip").hide();
				$("#btn_next").show();
				$("#btn_submit").hide();
			} else {
				$("#btn_previous").show();
				$("#btn_next").show();
				$("#btn_skip").show();
				$("#btn_submit").hide();
			}
			console.log(active_tab_id + " = " + new_tab_id);
			$("#tab_" + active_tab_id + " .tile").removeClass("selected");
			$("#tab_" + active_tab_id + " .tile").addClass("visited");
			$("#tab_" + active_tab_id + " .tab_label").removeClass("selected");
			$("#tab_" + active_tab_id + " .tab_label").addClass("visited");
			
			$("#tab_" + new_tab_id + " .tile").addClass("selected");
			$("#tab_" + new_tab_id + " .tile").removeClass("visited skipped");
			$("#tab_" + new_tab_id + " .tab_label").addClass("selected");
			$("#tab_" + new_tab_id + " .tab_label").removeClass("visited skipped");

			$("#tab" + active_tab_id).hide();
			$("#tab" + new_tab_id).show();
			
			$("#description" + active_tab_id).hide();
			$("#description" + new_tab_id).slideDown();
			active_tab = new_tab_id;
		}
		var errors = [];
		var msg = "";
		var submitted = false;
		//$("#self_setup_form").validate({ignore: ":hidden"});
		$("#client_username").focusout(function() {
			$.post(
				"ajax_response.php", 
				{
					"action": "is_username_available"
					, "username" : $(this).val()
				}
				, function(data) {
					var num = parseInt(data);
					if(num > 0) {
						$("#username_not_available_label").show();
					} else {
						$("#username_not_available_label").hide();
					}
				}
			);
		});
		$("#restaurant_name").focusout(function() {
			$.post(
				"ajax_response.php", 
				{
					"action": "is_restaurant_name_available"
					, "restaurant_name" : $(this).val()
				}
				, function(data) {
					var num = parseInt(data);
					if(num > 0) {
						$("#restaurant_name_not_available_label").show();
					} else {
						$("#restaurant_name_not_available_label").hide();
					}
				}
			);
		});
		
	});
})(jQuery);