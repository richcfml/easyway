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
		
		if ($.trim($("#txtFax").val())=="")
		{
			$("#RestaurantInputBasicError").show();
			$("#txtFax").focus();
			$("#txtFax").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#txtFax").css("border", "");
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
			
		$('#ac-2').prop('checked',true);									
	});
	
	$('#Third_Tab').click(function(e){
		e.preventDefault();
		
		$("#txtFullName").css("border", "");
		$("#txtEmailAddress").css("border", "");
		$("#txtPassword").css("border", "");
		$("#txtCPassword").css("border", "");
		$("#txtUserName").css("border", "");
		$("#RestaurantInputAccountError").text("Please correct the errors highlighted in red.");
		$("#RestaurantInputAccountError").hide();
		
		if ($.trim($("#txtFullName").val())=="")
		{
			$("#RestaurantInputAccountError").show();
			$("#txtFullName").focus();
			$("#txtFullName").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtFullName").css("border", "");
		}

		if ($.trim($("#txtEmailAddress").val())=="")
		{
			$("#RestaurantInputAccountError").show();
			$("#txtEmailAddress").focus();
			$("#txtEmailAddress").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtEmailAddress").css("border", "");
		}
		
		var mFlag = validateEmail($.trim($("#txtEmailAddress").val()));
		if(mFlag)
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtEmailAddress").css("border", "");			
		} 
		else 
		{
			$("#RestaurantInputAccountError").text("Invalid Email address.");
			$("#RestaurantInputAccountError").show();
			$("#txtEmailAddress").focus();
			$("#txtEmailAddress").css("border", "2px solid #FF0000");
			return false;
		}

		if ($.trim($("#txtPassword").val())=="")
		{
			$("#RestaurantInputAccountError").show();
			$("#txtPassword").focus();
			$("#txtPassword").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtPassword").css("border", "");
		}
		
		if ($.trim($("#txtCPassword").val())=="")
		{
			$("#RestaurantInputAccountError").show();
			$("#txtCPassword").focus();
			$("#txtCPassword").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtCPassword").css("border", "");
		}
		
		if ($.trim($("#txtPassword").val())!=$.trim($("#txtCPassword").val()))
		{
			$("#RestaurantInputAccountError").text("Confirm password do not match.");
			$("#RestaurantInputAccountError").show();
			$("#txtCPassword").focus();
			$("#txtCPassword").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtCPassword").css("border", "");
		}
		
		if ($.trim($("#txtUserName").val())=="")
		{
			$("#RestaurantInputAccountError").show();
			$("#txtUserName").focus();
			$("#txtUserName").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtUserName").css("border", "");
		}
		
		$.ajax({
			url:"ajax.php?call=validateuser&username="+$.trim($("#txtUserName").val()),
			type:"GET",
			success:function(data)
			{
				console.log(data);
				if ($.trim(data).toLowerCase()=="duplicate")
				{
					$("#RestaurantInputAccountError").text("Username already exists.");
					$("#RestaurantInputAccountError").show();
					$("#txtUserName").focus();
					$("#txtUserName").css("border", "2px solid #FF0000");
					return false;
				}
				else if ($.trim(data).toLowerCase()=="error")
				{
					$("#txtUserName").focus();
					$("#RestaurantInputAccountError").text("Error occurred. Request cannot be completed.");
					$("#RestaurantInputAccountError").show();
					return false;
				}
				else
				{
					$('#ac-3').prop('checked',true);									
				}
			},
			error:function(data)
			{
				$("#txtUserName").focus();
				$("#RestaurantInputAccountError").text("Error occurred. Request cannot be completed.");
				$("#RestaurantInputAccountError").show();
				return false;
			}
		});
	});
	
	$('#Fourth_Tab').click(function(e){
		e.preventDefault();
		$('#ac-4').prop('checked',true);									
	});
	
	$('#Fifth_Tab').click(function(e){
		e.preventDefault();
		$('#ac-5').prop('checked',true);									
	});
	
	/* Back Button */
		$('#Second_Back_Tab').click(function(e){
		e.preventDefault();
		$('#ac-1').prop('checked',true);									
	});
		
	$('#Third_Back_Tab').click(function(e){
		e.preventDefault();
		$('#ac-2').prop('checked',true);									
	});
	
	$('#Fourth_Back_Tab').click(function(e){
		e.preventDefault();
		$('#ac-3').prop('checked',true);									
	});
	
	$('#Fifth_Back_Tab').click(function(e){
		e.preventDefault();
		$('#ac-4').prop('checked',true);									
	});
	
	$('#btnSave1').click(function(e)
	{
		$("#txtRestaurantName").css("border", "");
		$("#txtStreetAddress").css("border", "");
		$("#txtCity").css("border", "");
		$("#txtZip").css("border", "");			
		$("#txtPhone").css("border", "");
		$("#txtFax").css("border", "");
		$("#ddlTimeZone").css("border", "");
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
		
		if ($.trim($("#txtFax").val())=="")
		{
			$("#RestaurantInputBasicError").show();
			$("#txtFax").focus();
			$("#txtFax").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#txtFax").css("border", "");
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
		
		$("#RestaurantInputBasicError").show();
		$("#RestaurantInputBasicError").text("Please wait...");
		saveRestaurant("btnSave1");
	});
	
	$('#btnSave2').click(function(e)
	{
		$("#txtRestaurantName").css("border", "");
		$("#txtStreetAddress").css("border", "");
		$("#txtCity").css("border", "");
		$("#txtZip").css("border", "");			
		$("#txtPhone").css("border", "");
		$("#txtFax").css("border", "");
		$("#ddlTimeZone").css("border", "");
		$("#RestaurantInputBasicError").hide();
		$("#RestaurantInputBasicError").text("Please correct the errors highlighted in red.");
		
		if ($.trim($("#txtRestaurantName").val())=="")
		{
			$('#ac-1').prop('checked',true);
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
			$('#ac-1').prop('checked',true);
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
			$('#ac-1').prop('checked',true);
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
			$('#ac-1').prop('checked',true);
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
		
		if ($.trim($("#txtPhone").val())=="")
		{
			$('#ac-1').prop('checked',true);
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
		
		if ($.trim($("#txtFax").val())=="")
		{
			$('#ac-1').prop('checked',true);
			$("#RestaurantInputBasicError").show();
			$("#txtFax").focus();
			$("#txtFax").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#txtFax").css("border", "");
		}
		
		if ($.trim($("#ddlTimeZone").val())=="-1")
		{
			$('#ac-1').prop('checked',true);
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
		
		
		$("#txtFullName").css("border", "");
		$("#txtEmailAddress").css("border", "");
		$("#txtPassword").css("border", "");
		$("#txtCPassword").css("border", "");
		$("#txtUserName").css("border", "");
		$("#RestaurantInputAccountError").text("Please correct the errors highlighted in red.");
		$("#RestaurantInputAccountError").hide();
		
		if ($.trim($("#txtFullName").val())=="")
		{
			$("#RestaurantInputAccountError").show();
			$("#txtFullName").focus();
			$("#txtFullName").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtFullName").css("border", "");
		}

		if ($.trim($("#txtEmailAddress").val())=="")
		{
			$("#RestaurantInputAccountError").show();
			$("#txtEmailAddress").focus();
			$("#txtEmailAddress").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtEmailAddress").css("border", "");
		}
		
		var mFlag = validateEmail($.trim($("#txtEmailAddress").val()));
		if(mFlag)
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtEmailAddress").css("border", "");			
		} 
		else 
		{
			$("#RestaurantInputAccountError").text("Invalid Email address.");
			$("#RestaurantInputAccountError").show();
			$("#txtEmailAddress").focus();
			$("#txtEmailAddress").css("border", "2px solid #FF0000");
			return false;
		}

		if ($.trim($("#txtPassword").val())=="")
		{
			$("#RestaurantInputAccountError").show();
			$("#txtPassword").focus();
			$("#txtPassword").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtPassword").css("border", "");
		}
		
		if ($.trim($("#txtCPassword").val())=="")
		{
			$("#RestaurantInputAccountError").show();
			$("#txtCPassword").focus();
			$("#txtCPassword").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtCPassword").css("border", "");
		}
		
		if ($.trim($("#txtPassword").val())!=$.trim($("#txtCPassword").val()))
		{
			$("#RestaurantInputAccountError").text("Confirm password do not match.");
			$("#RestaurantInputAccountError").show();
			$("#txtCPassword").focus();
			$("#txtCPassword").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtCPassword").css("border", "");
		}
		
		if ($.trim($("#txtUserName").val())=="")
		{
			$("#RestaurantInputAccountError").show();
			$("#txtUserName").focus();
			$("#txtUserName").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtUserName").css("border", "");
		}
		
		$.ajax({
			url:"ajax.php?call=validateuser&username="+$.trim($("#txtUserName").val()),
			type:"GET",
			success:function(data)
			{
				console.log(data);
				if ($.trim(data).toLowerCase()=="duplicate")
				{
					$("#RestaurantInputAccountError").text("Username already exists.");
					$("#RestaurantInputAccountError").show();
					$("#txtUserName").focus();
					$("#txtUserName").css("border", "2px solid #FF0000");
					return false;
				}
				else if ($.trim(data).toLowerCase()=="new")
				{
					$("#RestaurantInputAccountError").show();
					$("#RestaurantInputAccountError").text("Please wait...");
					saveRestaurant("btnSave2");
				}
				else if ($.trim(data).toLowerCase()=="error")
				{
					$("#txtUserName").focus();
					$("#RestaurantInputAccountError").text("Error occurred. Request cannot be completed.");
					$("#RestaurantInputAccountError").show();
					return false;
				}
			},
			error:function(data)
			{
				$("#txtUserName").focus();
				$("#RestaurantInputAccountError").text("Error occurred. Request cannot be completed.");
				$("#RestaurantInputAccountError").show();
				return false;
			}
		});
	});
	
	$('#btnSave3').click(function(e)
	{
		$("#txtRestaurantName").css("border", "");
		$("#txtStreetAddress").css("border", "");
		$("#txtCity").css("border", "");
		$("#txtZip").css("border", "");			
		$("#txtPhone").css("border", "");
		$("#txtFax").css("border", "");
		$("#ddlTimeZone").css("border", "");
		$("#RestaurantInputBasicError").hide();
		$("#RestaurantInputBasicError").text("Please correct the errors highlighted in red.");
		
		if ($.trim($("#txtRestaurantName").val())=="")
		{
			$('#ac-1').prop('checked',true);
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
			$('#ac-1').prop('checked',true);
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
			$('#ac-1').prop('checked',true);
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
			$('#ac-1').prop('checked',true);
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
		
		if ($.trim($("#txtPhone").val())=="")
		{
			$('#ac-1').prop('checked',true);
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
		
		if ($.trim($("#txtFax").val())=="")
		{
			$('#ac-1').prop('checked',true);
			$("#RestaurantInputBasicError").show();
			$("#txtFax").focus();
			$("#txtFax").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#txtFax").css("border", "");
		}
		
		if ($.trim($("#ddlTimeZone").val())=="-1")
		{
			$('#ac-1').prop('checked',true);
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
		
		
		$("#txtFullName").css("border", "");
		$("#txtEmailAddress").css("border", "");
		$("#txtPassword").css("border", "");
		$("#txtCPassword").css("border", "");
		$("#txtUserName").css("border", "");
		$("#RestaurantInputAccountError").text("Please correct the errors highlighted in red.");
		$("#RestaurantInputAccountError").hide();
		
		if ($.trim($("#txtFullName").val())=="")
		{
			$('#ac-2').prop('checked',true);
			$("#RestaurantInputAccountError").show();
			$("#txtFullName").focus();
			$("#txtFullName").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtFullName").css("border", "");
		}

		if ($.trim($("#txtEmailAddress").val())=="")
		{
			$('#ac-2').prop('checked',true);
			$("#RestaurantInputAccountError").show();
			$("#txtEmailAddress").focus();
			$("#txtEmailAddress").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtEmailAddress").css("border", "");
		}
		
		var mFlag = validateEmail($.trim($("#txtEmailAddress").val()));
		if(mFlag)
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtEmailAddress").css("border", "");			
		} 
		else 
		{
			$('#ac-2').prop('checked',true);
			$("#RestaurantInputAccountError").text("Invalid Email address.");
			$("#RestaurantInputAccountError").show();
			$("#txtEmailAddress").focus();
			$("#txtEmailAddress").css("border", "2px solid #FF0000");
			return false;
		}

		if ($.trim($("#txtPassword").val())=="")
		{
			$('#ac-2').prop('checked',true);
			$("#RestaurantInputAccountError").show();
			$("#txtPassword").focus();
			$("#txtPassword").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtPassword").css("border", "");
		}
		
		if ($.trim($("#txtCPassword").val())=="")
		{
			$('#ac-2').prop('checked',true);
			$("#RestaurantInputAccountError").show();
			$("#txtCPassword").focus();
			$("#txtCPassword").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtCPassword").css("border", "");
		}
		
		if ($.trim($("#txtPassword").val())!=$.trim($("#txtCPassword").val()))
		{
			$('#ac-2').prop('checked',true);
			$("#RestaurantInputAccountError").text("Confirm password do not match.");
			$("#RestaurantInputAccountError").show();
			$("#txtCPassword").focus();
			$("#txtCPassword").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtCPassword").css("border", "");
		}
		
		if ($.trim($("#txtUserName").val())=="")
		{
			$('#ac-2').prop('checked',true);
			$("#RestaurantInputAccountError").show();
			$("#txtUserName").focus();
			$("#txtUserName").css("border", "2px solid #FF0000");
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtUserName").css("border", "");
		}
		
		$.ajax({
			url:"ajax.php?call=validateuser&username="+$.trim($("#txtUserName").val()),
			type:"GET",
			success:function(data)
			{
				console.log(data);
				if ($.trim(data).toLowerCase()=="duplicate")
				{
					$('#ac-2').prop('checked',true);
					$("#RestaurantInputAccountError").text("Username already exists.");
					$("#RestaurantInputAccountError").show();
					$("#txtUserName").focus();
					$("#txtUserName").css("border", "2px solid #FF0000");
					return false;
				}
				else if ($.trim(data).toLowerCase()=="new")
				{
					$("#RestaurantInputMenuError").show();
					$("#RestaurantInputMenuError").text("Please wait...");
					saveRestaurant("btnSave3");
				}
				else if ($.trim(data).toLowerCase()=="error")
				{
					$('#ac-2').prop('checked',true);
					$("#txtUserName").focus();
					$("#RestaurantInputAccountError").text("Error occurred. Request cannot be completed.");
					$("#RestaurantInputAccountError").show();
					return false;
				}
			},
			error:function(data)
			{
				$('#ac-2').prop('checked',true);
				$("#txtUserName").focus();
				$("#RestaurantInputAccountError").text("Error occurred. Request cannot be completed.");
				$("#RestaurantInputAccountError").show();
				return false;
			}
		});	
	});
	
	function saveRestaurant(pButtonID)
	{
		var mUrl = '';
		var mRandom = Math.floor((Math.random()*1000000)+1); 
		var mMessage="";
		if ($.isNumeric($("#txtRestaurantID").val()))
		{
			if ($("#txtRestaurantID").val()>0)
			{
				mUrl="ajax.php?call=updateuser&rndm="+mRandom;				
			}
			else
			{
				mUrl="ajax.php?call=saveuser&rndm="+mRandom;
			}
		}
		else
		{
			mUrl="ajax.php?call=saveuser&rndm="+mRandom;
		}

		$.ajax
		({
			url: mUrl,
			type:'POST',
			data: $("#frmMain").serialize(), 
			success: function(data)
			{
				if ($.isNumeric(data))
				{
					if (data>0)
					{
						$("#txtRestaurantID").val(data);
						mMessage = "Changes Saved.";
					}
					else
					{
						mMessage = data;
					}
				}
				else
				{
					mMessage = data;
				}
				
				if (pButtonID=="btnSave1")
				{
					$("#RestaurantInputBasicError").show();
					$("#RestaurantInputBasicError").text(mMessage);
				}
				else if (pButtonID=="btnSave2")
				{
					$("#RestaurantInputAccountError").show();
					$("#RestaurantInputAccountError").text(mMessage);
				}
				else if (pButtonID=="btnSave3")
				{
					$("#RestaurantInputMenuError").show();
					$("#RestaurantInputMenuError").text(mMessage);
				}
			},
			error:function(data)
			{
				mMessage = data;
				
				if (pButtonID=="btnSave1")
				{
					$("#RestaurantInputBasicError").show();
					$("#RestaurantInputBasicError").text(mMessage);
				}
				else if (pButtonID=="btnSave2")
				{
					$("#RestaurantInputAccountError").show();
					$("#RestaurantInputAccountError").text(mMessage);
				}
				else if (pButtonID=="btnSave3")
				{
					$("#RestaurantInputMenuError").show();
					$("#RestaurantInputMenuError").text(mMessage);
				}
			}
		});
	}
	
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
});