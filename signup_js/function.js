var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches
function next(index){
    if(animating) return false;
    animating = true;

    current_fs = $(index).parent();
    next_fs = $(index).parent().next();

    //activate next step on progressbar using the index of next_fs
    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active").css("background-color","#e4eff3");

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
        step: function(now, mx) {
            //as the opacity of current_fs reduces to 0 - stored in "now"
            //1. scale current_fs down to 80%
            scale = 1 - (1 - now) * 0.2;
            //2. bring next_fs from the right(50%)
            left = (now * 50)+"%";
            //3. increase opacity of next_fs to 1 as it moves in
            opacity = 1 - now;
            current_fs.css({'transform': 'scale('+scale+')'});
            next_fs.css({'left': left, 'opacity': opacity});
        },
        duration: 800,
        complete: function(){
            current_fs.hide();
            animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
    });
}
$(function() {

    //jQuery time
    $(".previous").click(function(){
        if(animating) return false;
        animating = true;

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
        $("#progressbar li").eq($("fieldset").index(previous_fs)).css("background-color","").addClass("active");;

        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
            },
            duration: 800,
            complete: function(){
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
        $(window).scrollTop(125);
    });

    $(".submit").click(function(){
        return false;
    });

});


$(document).ready(function() {

	/* MAIN MENU */
	$('#main-menu > li:has(ul.sub-menu)').addClass('parent');
	$('ul.sub-menu > li:has(ul.sub-menu) > a').addClass('parent');

	$('#menu-toggle').click(function() {
		$('#main-menu').slideToggle(300);
		return false;
	});

	$(window).resize(function() {
		if ($(window).width() > 768) {
			$('#main-menu').removeAttr('style');
		}
	});
	
	$("#firstTab").click(function()
        {
            next(this);
            $(window).scrollTop(125);
        });

	/*Next Button */
	$('#Second_Tab').click(function(e)
	{
		e.preventDefault();
		$("#txtRestaurantName").css("border", "");
		$("#txtStreetAddress").css("border", "");
		$("#txtCity").css("border", "");
		$("#txtZip").css("border", "");			
		$("#txtPhone").css("border", "");
		$("#txtFax").css("border", "");
		$("#ddlTimeZone").css("border", "");
                $("#txtState").css("border", "");
		$("#RestaurantInputBasicError").hide();
		$("#RestaurantInputBasicError").text("Please correct the errors highlighted in red.");
		
		if ($.trim($("#txtRestaurantName").val())=="")
		{
			$("#RestaurantInputBasicError").show();
			$("#txtRestaurantName").focus();
			$("#txtRestaurantName").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#txtRestaurantName").css("border", "");
		}

		if ($.trim($("#txtStreetAddress").val())=="")
		{
			$("#RestaurantInputBasicError").show();
			$("#txtStreetAddress").focus();
			$("#txtStreetAddress").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#txtStreetAddress").css("border", "");
		}

		if ($.trim($("#txtCity").val())=="")
		{
			$("#RestaurantInputBasicError").show();
			$("#txtCity").focus();
			$("#txtCity").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#txtCity").css("border", "");
		}
		
		if ($.trim($("#txtZip").val())=="")
		{
			$("#RestaurantInputBasicError").show();
			$("#txtZip").focus();
			$("#txtZip").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#txtZip").css("border", "");
		}

                if ($.trim($("#txtState").val())=="-1")
		{
			$("#RestaurantInputBasicError").show();
			$("#txtState").focus();
			$("#txtState").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#txtState").css("border", "");
		}
		
		if ($.trim($("#txtPhone").val())=="")
		{
			$("#RestaurantInputBasicError").show();
			$("#txtPhone").focus();
			$("#txtPhone").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#txtPhone").css("border", "");
		}
		
		if ($.trim($("#ddlTimeZone").val())=="-1")
		{
			$("#RestaurantInputBasicError").show();
			$("#ddlTimeZone").focus();
			$("#ddlTimeZone").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#ddlTimeZone").css("border", "");
		}
                
		next(this);
                $(window).scrollTop(125);
	});

        $('#Third_Tab').click(function(e){
		e.preventDefault();
                var radioValue = $("input[name='rbOrders']:checked").val();
                var radioValue1 = $("input[name='rbOrdersTab']:checked").val();
                if (!radioValue && !radioValue1)
		{
			$("#orderPreferencesError").show();
			$("#orderPreferencesError").text('Please select receive order method');

			return false;
		}
		else
		{
			$("#orderPreferencesError").hide();
			$("#orderPreferencesError").text('Please correct the errors highlighted in red.');
		}

                if ($.trim($("#txtTax").val())=="")
		{
			$("#orderPreferencesError").show();
			$("#txtTax").focus();
			$("#txtTax").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#orderPreferencesError").hide();
			$("#txtTax").css("border", "");
		}


		next(this);
                $(window).scrollTop(125);
	});

	$('#Fourth_Tab').click(function(e){
		e.preventDefault();
                var rbCreditCard = $("input[name='rbCreditCard']:checked").val();
                var rbCash = $("input[name='rbCash']:checked").val();
                var rbOrdering = $("input[name='rbMenuUse']:checked").val();

                if (rbCreditCard || rbCash)
		{
                        $("#paymentsMethodError").hide();
			$("#paymentsMethodError").text('Please correct the errors highlighted in red.');
		}
		else
		{
			$("#paymentsMethodError").show();
			$("#paymentsMethodError").text('Please select at least one payment method');
                        return false;
		}

                if (rbOrdering)
		{
                        $("#paymentsMethodError").hide();
			$("#paymentsMethodError").text('Please correct the errors highlighted in red.');
                        $("#onlineOrdering").css("border","none");
		}
		else
		{
			$("#paymentsMethodError").show();
			$("#paymentsMethodError").text('Please choose how you would like to use your online ordering.');
                        $("#onlineOrdering").css("border","1px solid red");
                        return false;
		}

                if ($("#rbMenuUse1").is(":checked"))
		{
                    if(! $("#noFTP").is(":checked"))
                    {
                        if ($.trim($("#webName").val())=="")
                        {
                                $("#paymentsMethodError").show();
                                $("#webName").focus();
                                $("#webName").css("border", "2px solid #FF0000");
                                return false;
                        }
                        else
                        {
                                $("#paymentsMethodError").hide();
                                $("#webName").css("border", "");
                        }

                        if ($.trim($("#webHost").val())=="")
                        {
                                $("#paymentsMethodError").show();
                                $("#webHost").focus();
                                $("#webHost").css("border", "2px solid #FF0000");
                                return false;
                        }
                        else
                        {
                                $("#paymentsMethodError").hide();
                                $("#webHost").css("border", "");
                        }

                        if ($.trim($("#webUserName").val())=="")
                        {
                                $("#paymentsMethodError").show();
                                $("#webUserName").focus();
                                $("#webUserName").css("border", "2px solid #FF0000");
                                return false;
                        }
                        else
                        {
                                $("#paymentsMethodError").hide();
                                $("#webUserName").css("border", "");
                        }

                        if ($.trim($("#webPassword").val())=="")
                        {
                                $("#paymentsMethodError").show();
                                $("#webPassword").focus();
                                $("#webPassword").css("border", "2px solid #FF0000");
                                return false;
                        }
                        else
                        {
                                $("#paymentsMethodError").hide();
                                $("#webPassword").css("border", "");
                        }
			
                    }
                    else
                    {
                        $("#webPassword").css("border", "");
                        $("#webUserName").css("border", "");
                        $("#webHost").css("border", "");
                        $("#webName").css("border", "");
                        $("#paymentsMethodError").hide();
                    }
		}
                else if ($("#rbMenuUse2").is(":checked"))
		{
                    if ($.trim($("#txtDomainName").val())=="")
                    {
                            $("#paymentsMethodError").show();
                            $("#txtDomainName").focus();
                            $("#txtDomainName").css("border", "2px solid #FF0000");
                            return false;
                    }
                    else
                    {
                            $("#paymentsMethodError").hide();
                            $("#txtDomainName").css("border", "");
                    }
                }

                else if ($("#rbMenuUse3").is(":checked"))
		{
                    var radioValue = $("input[name='orderingPlan']:checked").val();
                    if (!radioValue)
                    {
                        $("#paymentsMethodError").show();
                        $("#paymentsMethodError").text('Please choose your online ordering.');
                        return false;
                    }
                    else
                    {
                        $("#paymentsMethodError").hide();
                        $("#paymentsMethodError").text('Please correct the errors highlighted in red.');
                    }
                }
                next(this);
                $(window).scrollTop(125);

        });
	
	function validateEmail(email)
	{
		var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		var valid = emailReg.test(email);
	
		if(!valid) 
		{
			return false;
		} 
		else 
		{
			return true;
		}
	}
        $('#webName,#webHost,#webUserName,#webPassword,#txtDomainName').keyup(function(){
            $("#paymentsMethodError").hide();
            $(this).css("border", "");
        });

        $("#step1").click(function()
        {
            $("#createAccount").css("display","none");
            $("#payment").css("display","none");
            $("#orderPrefrence").css("display","none");
            $("#BuisnessInfo").css("display","none");
            $("#locateBuisness").css({"opacity":"1","left":"0","display":"block","transform":"scale(1)"});
            $(this).addClass('active');
            $("#step2,#step3,#step4,#step5").removeClass('active');
            $("#step1,#step2,#step3,#step4,#step5").css("background-color","");
        });

        $("#step2").click(function()
        {
            $("#createAccount").css("display","none");
            $("#payment").css("display","none");
            $("#orderPrefrence").css("display","none");
            $("#locateBuisness").css("display","none");
            $("#BuisnessInfo").css({"opacity":"1","left":"0","display":"block","transform":"scale(1)"});
            $(this).addClass('active');
            $("#step1").css("background-color","#e4eff3");
            $("#step1,#step3,#step4,#step5").removeClass('active');
            $("#step2,#step3,#step4,#step5").css("background-color","");
        });
        
        $("#step3").click(function()
        {
            if(checkBuisnessValidation())
            {
                $("#createAccount").css("display","none");
                $("#payment").css("display","none");
                $("#BuisnessInfo").css("display","none");
                $("#locateBuisness").css("display","none");
                $("#orderPrefrence").css({"opacity":"1","left":"0","display":"block","transform":"scale(1)"});
                $(this).addClass('active');
                $("#step1,#step2").css("background-color","#e4eff3");
                $("#step2,#step1,#step4,#step5").removeClass('active');
                $("#step3,#step4,#step5").css("background-color","");
            }
        });
        $("#step4").click(function()
        {
            if(checkBuisnessValidation() && orderPrefrenceValidation())
            {
                $("#createAccount").css("display","none");
                $("#orderPrefrence").css("display","none");
                $("#BuisnessInfo").css("display","none");
                $("#locateBuisness").css("display","none");
                $("#payment").css({"opacity":"1","left":"0","display":"block","transform":"scale(1)"});
                $(this).addClass('active');
                $("#step1,#step2,#step3").css("background-color","#e4eff3");
                $("#step2,#step3,#step1,#step5").removeClass('active');
                $("#step4,#step5").css("background-color","");
            }
        });

        $("#step5").click(function()
        {
            if(checkBuisnessValidation() && orderPrefrenceValidation() && paymentDetailsValidation())
            {
                $("#payment").css("display","none");
                $("#orderPrefrence").css("display","none");
                $("#BuisnessInfo").css("display","none");
                $("#locateBuisness").css("display","none");
                $("#createAccount").css({"opacity":"1","left":"0","display":"block","transform":"scale(1)"});
                $(this).addClass('active').css("background-color","");
                $("#step1,#step2,#step3,#step4").css("background-color","#e4eff3");
                $("#step2,#step3,#step4,#step1").removeClass('active');
            }
        });

});
        function checkBuisnessValidation()
        {
            if($("#txtRestaurantName").val()!="" && $("#txtStreetAddress").val()!="" && $("#txtCity").val()!="" && $("#txtZip").val()!="" && $("#txtState").val()!="1" && $("#ddlCountry1").val()!="" && $("#txtPhone").val()!="" && $("#ddlTimeZone").val()!="-1")
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function orderPrefrenceValidation()
        {
            var rbOrders = $("input[name='rbOrders']:checked").val();
            if (rbOrders && $("#txtTax").val()!="")
            {
                return true;
            }
            else
            {
                return false;
            }
            
        }
        function paymentDetailsValidation()
        {
            var rbcash = $("input[name='rbCash']:checked").val();
            var rbcredit = $("input[name='rbCreditCard']:checked").val();
            var rbMenuUse = $("input[name='rbMenuUse']:checked").val();
            if ((rbcash || rbcredit) && rbMenuUse)
            {
                return true;
            }
            else
            {
                return false;
            }

        }
        