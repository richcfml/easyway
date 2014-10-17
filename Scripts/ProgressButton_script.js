$(document).ready(function(){

	// Convert all the links with the progress-button class to
	// actual buttons with progress meters.
	// You need to call this function once the page is loaded.
	// If you add buttons later, you will need to call the function only for them.

	$('.progress-button').progressInitialize();

	// Listen for clicks on the first three buttons, and start
	// the progress animations

	$("#submitButton").unbind("click").click(function(e){
		e.preventDefault();
		$("#submitButton").attr('disabled',true);
		
		$("#txtRestaurantName").css("border", "");
		$("#txtStreetAddress").css("border", "");
		$("#txtCity").css("border", "");
		$("#txtZip").css("border", "");			
		$("#txtPhone").css("border", "");
		$("#txtFax").css("border", "");
		$("#ddlTimeZone").css("border", "");
		$("#RestaurantInputBasicError").hide();
		$("#RestaurantInputBasicError").text("Please correct the errors highlighted in red.");
		
		$("#txtFullName").css("border", "");
		$("#txtEmailAddress").css("border", "");
		$("#txtPassword").css("border", "");
		$("#txtCPassword").css("border", "");
		$("#txtUserName").css("border", "");
		$("#RestaurantInputAccountError").text("Please correct the errors highlighted in red.");
		$("#RestaurantInputAccountError").hide();
		
		
		if ($.trim($("#txtRestaurantName").val())=="")
		{
			$('#ac-1').prop('checked',true);
			$("#RestaurantInputBasicError").show();
			$("#txtRestaurantName").focus();
			$("#txtRestaurantName").css("border", "2px solid #FF0000");
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputBasicError").hide();
			$("#ddlTimeZone").css("border", "");
		}
		
		
		
		
		
		
		if ($.trim($("#txtFullName").val())=="")
		{
			$('#ac-2').prop('checked',true);
			$("#RestaurantInputAccountError").show();
			$("#txtFullName").focus();
			$("#txtFullName").css("border", "2px solid #FF0000");
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
			return false;
		}

		if ($.trim($("#txtPassword").val())=="")
		{
			$('#ac-2').prop('checked',true);			
			$("#RestaurantInputAccountError").show();
			$("#txtPassword").focus();
			$("#txtPassword").css("border", "2px solid #FF0000");
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
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
			$("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputAccountError").hide();
			$("#txtUserName").css("border", "");
		}
		
		if ($.trim($("#txtCreditCardNumber").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#RestaurantInputFinalError").text("Please correct the errors highlighted in red.");
			$("#txtCreditCardNumber").focus();
			$("#txtCreditCardNumber").css("border", "2px solid #FF0000");
			$("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#txtCreditCardNumber").css("border", "");
		}
		
		if ($.trim($("#txtClientAddress").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#RestaurantInputFinalError").text("Please correct the errors highlighted in red.");
			$("#txtClientAddress").focus();
			$("#txtClientAddress").css("border", "2px solid #FF0000");
			$("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#txtClientAddress").css("border", "");
		}
		
		if ($.trim($("#txtClientState").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#RestaurantInputFinalError").text("Please correct the errors highlighted in red.");
			$("#txtClientState").focus();
			$("#txtClientState").css("border", "2px solid #FF0000");
			$("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#txtClientState").css("border", "");
		}
		
		if ($.trim($("#txtClientCity").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#RestaurantInputFinalError").text("Please correct the errors highlighted in red.");
			$("#txtClientCity").focus();
			$("#txtClientCity").css("border", "2px solid #FF0000");
			$("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#txtClientCity").css("border", "");
		}
		
		if ($.trim($("#txtClientZip").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#RestaurantInputFinalError").text("Please correct the errors highlighted in red.");
			$("#txtClientZip").focus();
			$("#txtClientZip").css("border", "2px solid #FF0000");
			$("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#txtClientZip").css("border", "");
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
					$("#submitButton").removeAttr('disabled');
					$("#submitButton").removeClass("Save progress-button finished").addClass("Save progress-button");
					return false;
				}
				else if ($.trim(data).toLowerCase()=="new")
				{					
					$("#RestaurantInputFinalError").show();
					$("#RestaurantInputFinalError").css("text-decoration", "blink");
					$("#RestaurantInputFinalError").text("Please wait...");
					
					var mUrl = '';
					var mRandom = Math.floor((Math.random()*1000000)+1); 
					var mMessage="";
					if ($.isNumeric($("#txtRestaurantID").val()))
					{
						if ($("#txtRestaurantID").val()>0)
						{
							mUrl="ajax.php?call=updateuser&savebtn=1&rndm="+mRandom;				
						}
						else
						{
							mUrl="ajax.php?call=saveuser&savebtn=1&rndm="+mRandom;
						}
					}
					else
					{
						mUrl="ajax.php?call=saveuser&savebtn=1&rndm="+mRandom;
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
									$("#ThankYouContainer").show();
									$("#RestaurantInputFinalError").hide();
									mMessage = "";
									$('html, body').animate({
										scrollTop: $("#ThankYouContainer").offset().top
									}, 2000);
								}
								else
								{
									mSDID = data.substring(0, data.indexOf("ERROR:")); 
									data = data.substring(data.indexOf("ERROR:")); 
									if (mSDID!="")
									{
										if ($.isNumeric(mSDID))
										{
											$("#txtRestaurantID").val(mSDID);	
										}
									}
									$("#submitButton").removeAttr('disabled');
									mMessage = data;
									$("#RestaurantInputFinalError").show();
									$("#RestaurantInputFinalError").css("text-decoration", "none");
									$("#RestaurantInputFinalError").text(mMessage);
									$("#submitButton").removeClass("Save progress-button finished").addClass("Save progress-button");
								}
							}
							else
							{
								mSDID = data.substring(0, data.indexOf("ERROR:")); 
								data = data.substring(data.indexOf("ERROR:")); 
								if (mSDID!="")
								{
									if ($.isNumeric(mSDID))
									{
										$("#txtRestaurantID").val(mSDID);	
									}
								}
								$("#submitButton").removeAttr('disabled');
								mMessage = data;
								$("#RestaurantInputFinalError").css("text-decoration", "none");
								$("#RestaurantInputFinalError").show();
								$("#RestaurantInputFinalError").text(mMessage);
								$("#submitButton").removeClass("Save progress-button finished").addClass("Save progress-button");
							}
						},
						error:function(data)
						{
							mSDID = data.substring(0, data.indexOf("ERROR:")); 
							data = data.substring(data.indexOf("ERROR:")); 
							if (mSDID!="")
							{
								if ($.isNumeric(mSDID))
								{
									$("#txtRestaurantID").val(mSDID);	
								}
							}
							$("#submitButton").removeAttr('disabled');
							mMessage = data;
							$("#RestaurantInputFinalError").css("text-decoration", "none");
							$("#RestaurantInputFinalError").show();
							$("#RestaurantInputFinalError").text(mMessage);
							$("#submitButton").removeClass("Save progress-button finished").addClass("Save progress-button");
						}
					});
					/* --------------------------------------------------------------------- */
				}
				else if ($.trim(data).toLowerCase()=="error")
				{
					$('#ac-2').prop('checked',true);
					$("#txtUserName").focus();
					$("#RestaurantInputAccountError").text("Error occurred. Request cannot be completed.");
					$("#RestaurantInputAccountError").show();
					$("#submitButton").removeAttr('disabled');
					$("#submitButton").removeClass("Save progress-button finished").addClass("Save progress-button");
					return false;
				}
			},
			error:function(data)
			{
				$('#ac-2').prop('checked',true);
				$("#txtUserName").focus();
				$("#RestaurantInputAccountError").text("Error occurred. Request cannot be completed.");
				$("#RestaurantInputAccountError").show();
				$("#submitButton").removeAttr('disabled');
				$("#submitButton").removeClass("Save progress-button finished").addClass("Save progress-button");
				return false;
			}
		});
		// This function will show a progress meter for
		// the specified amount of time
		$(this).progressTimed(2);
		/* --------------------------------------------------------------------- */
	});

	$('#actionButton').click(function(e){
		e.preventDefault();
		$(this).progressTimed(2);
	});

	$('#generateButton').one('click', function(e){
		e.preventDefault();

		// It can take a callback

		var button = $(this);
		button.progressTimed(3, function(){

			// In this callback, you can set the href attribute of the button
			// to the URL of the generated file. For the demo, we will only
			// set up a new event listener that alerts a message.

			button.click(function(){
				alert('Showing how a callback works!');
			});
		});
	});


	// Custom progress handling

	var controlButton = $('#controlButton');

	controlButton.click(function(e){
		e.preventDefault();

		// You can optionally call the progressStart function.
		// It will simulate activity every 2 seconds if the
		// progress meter has not been incremented.

		controlButton.progressStart();
	});

	$('.command.increment').click(function(){

		// Increment the progress bar with 10%. Pass a number
		// as an argument to increment with a different amount.

		controlButton.progressIncrement();
	});

	$('.command.set-to-1').click(function(){

		// Set the progress meter to the specified percentage

		controlButton.progressSet(1);
	});

	$('.command.set-to-50').click(function(){
		controlButton.progressSet(50);
	});

	$('.command.finish').click(function(){

		// Set the progress meter to 100% and show the done text.
		controlButton.progressFinish();
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

});

// The progress meter functionality is available as a series of plugins.
// You can put this code in a separate file if you want to keep things tidy.

(function($){

	// Creating a number of jQuery plugins that you can use to
	// initialize and control the progress meters.

	$.fn.progressInitialize = function(){

		// This function creates the necessary markup for the progress meter
		// and sets up a few event listeners.


		// Loop through all the buttons:

		return this.each(function(){

			var button = $(this),
				progress = 0;

			// Extract the data attributes into the options object.
			// If they are missing, they will receive default values.

			var options = $.extend({
				type:'background-horizontal',
				loading: 'Loading..',
				finished: 'Done!'
			}, button.data());

			// Add the data attributes if they are missing from the element.
			// They are used by our CSS code to show the messages
			button.attr({'data-loading': options.loading, 'data-finished': options.finished});

			// Add the needed markup for the progress bar to the button
			var bar = $('<span class="tz-bar ' + options.type + '">').appendTo(button);


			// The progress event tells the button to update the progress bar
			button.on('progress', function(e, val, absolute, finish){

				if(!button.hasClass('in-progress')){

					// This is the first progress event for the button (or the
					// first after it has finished in a previous run). Re-initialize
					// the progress and remove some classes that may be left.

					bar.show();
					progress = 0;
					button.removeClass('finished').addClass('in-progress')
				}

				// val, absolute and finish are event data passed by the progressIncrement
				// and progressSet methods that you can see near the end of this file.

				if(absolute){
					progress = val;
				}
				else{
					progress += val;
				}

				if(progress >= 100){
					progress = 100;
				}

				if(finish){

					button.removeClass('in-progress').addClass('finished');

					bar.delay(500).fadeOut(function(){

						// Trigger the custom progress-finish event
						button.trigger('progress-finish');
						setProgress(0);
					});

				}

				setProgress(progress);
			});

			function setProgress(percentage){
				bar.filter('.background-horizontal,.background-bar').width(percentage+'%');
				bar.filter('.background-vertical').height(percentage+'%');
			}

		});

	};

	// progressStart simulates activity on the progress meter. Call it first,
	// if the progress is going to take a long time to finish.

	$.fn.progressStart = function(){

		var button = this.first(),
			last_progress = new Date().getTime();

		if(button.hasClass('in-progress')){
			// Don't start it a second time!
			return this;
		}

		button.on('progress', function(){
			last_progress = new Date().getTime();
		});

		// Every half a second check whether the progress
		// has been incremented in the last two seconds

		var interval = window.setInterval(function(){

			if( new Date().getTime() > 2000+last_progress){

				// There has been no activity for two seconds. Increment the progress
				// bar a little bit to show that something is happening

				button.progressIncrement(5);
			}

		}, 500);

		button.on('progress-finish',function(){
			window.clearInterval(interval);
		});

		return button.progressIncrement(10);
	};

	$.fn.progressFinish = function(){
		return this.first().progressSet(100);
	};

	$.fn.progressIncrement = function(val){

		val = val || 10;

		var button = this.first();

		button.trigger('progress',[val])

		return this;
	};

	$.fn.progressSet = function(val){
		val = val || 10;

		var finish = false;
		if(val >= 100){
			finish = true;
		}

		return this.first().trigger('progress',[val, true, finish]);
	};

	// This function creates a progress meter that
	// finishes in a specified amount of time.

	$.fn.progressTimed = function(seconds, cb){

		var button = this.first(),
			bar = button.find('.tz-bar');

		if(button.is('.in-progress')){
			return this;
		}

		// Set a transition declaration for the duration of the meter.
		// CSS will do the job of animating the progress bar for us.

		bar.css('transition', seconds+'s linear');
		button.progressSet(99);

		window.setTimeout(function(){
			bar.css('transition','');
			button.progressFinish();

			if($.isFunction(cb)){
				cb();
			}

		}, seconds*1000);
	};
})(jQuery);
