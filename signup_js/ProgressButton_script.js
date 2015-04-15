$(document).ready(function(){

	// Convert all the links with the progress-button class to
	// actual buttons with progress meters.
	// You need to call this function once the page is loaded.
	// If you add buttons later, you will need to call the function only for them.

	$('.progress-button').progressInitialize();
        
        var dataFile = new FormData();
        var j=0;
        var dataObject = {};
        $("#uploadme").change(function(event)
        {
                jQuery.each($('#uploadme')[0].files, function(i, file) {
                var ext = file.name.split('.').pop().toLowerCase();
                if($.inArray(ext, ['jpg','jpeg','docx','doc','pdf']) == -1)
                {
                    $("#RestaurantInputBasicError").show().text('Please upload only jpeg, pdf or word file.');
                    return false;
                }
                else
                {
                    var fileSize = file.size;
                    if(fileSize > 5120000)
                    {
                        $("#RestaurantInputBasicError").show().text('File size must not be greator than 5MB');
                    }
                    else
                    {
                        dataObject['file-'+j] = file;
                        $("#RestaurantInputBasicError").hide().text('Please correct the errors highlighted in red.');
                        $("#menu_name").append("<span>"+file.name+"<i class='fa fa-trash removeMenu' key="+j+" style='  margin-left: 20px;font-size: 18px;cursor:pointer'></i><br /></span>");
                        $(".addNewMenu").show();
                        $("#noMenuAttached").hide();
                        j++;
                        
                    }
                }
            });
            $("#uploadme").val('');
            
        });

        $('#menu_name').on('click', '.removeMenu', function()
        {
               var key = $(this).attr('key')
               key = 'file-'+key;
               delete dataObject[key];
               $(this).parent('span').remove();
               if (jQuery.isEmptyObject(dataObject) )
               {
                    $(".addNewMenu").hide();
                    $("#noMenuAttached").show();
               }
        });

	// Listen for clicks on the first three buttons, and start
	// the progress animations

	$("#submitButton").unbind("click").click(function(e){
                for (var key in dataObject) {
                  dataFile.append(key, dataObject[key]);
                }
                
		e.preventDefault();
                $("#submitButton").attr('disabled',true);
		$("#txtFullName").css("border", "");
		$("#txtEmailAddress").css("border", "");
		$("#txtPassword").css("border", "");
		$("#txtCPassword").css("border", "");
		$("#txtUserName").css("border", "");
		$("#RestaurantInputAccountError").text("Please correct the errors highlighted in red.");
		$("#RestaurantInputAccountError").hide();
                var rbProducts = $("input[name='rbProducts']:checked").val();
                if (!rbProducts)
		{
			$("#RestaurantInputFinalError").show();
			$("#RestaurantInputFinalError").text('Please select plan');
                        $("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#RestaurantInputFinalError").text('Please correct the errors highlighted in red.');
		}
		if ($.trim($("#txtFullName").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#txtFullName").focus();
			$("#txtFullName").css("border", "2px solid #FF0000");
                        $("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#txtFullName").css("border", "");
		}

		if ($.trim($("#txtEmailAddress").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#txtEmailAddress").focus();
			$("#txtEmailAddress").css("border", "2px solid #FF0000");
                        $("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#txtEmailAddress").css("border", "");
		}

		var mFlag = validateEmail($.trim($("#txtEmailAddress").val()));
		if(mFlag)
		{
			$("#RestaurantInputFinalError").hide();
			$("#txtEmailAddress").css("border", "");
		}
		else
		{
			$("#RestaurantInputFinalError").text("Invalid Email address.");
			$("#RestaurantInputAccountError").show();
			$("#txtEmailAddress").focus();
			$("#txtEmailAddress").css("border", "2px solid #FF0000");
                        $("#submitButton").removeAttr('disabled');
			return false;
		}

		if ($.trim($("#txtPassword").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#txtPassword").focus();
			$("#txtPassword").css("border", "2px solid #FF0000");
                        $("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#txtPassword").css("border", "");
		}

		if ($.trim($("#txtUserName").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#txtUserName").focus();
			$("#txtUserName").css("border", "2px solid #FF0000");
                        $("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#txtUserName").css("border", "");
		}
                if ($.trim($("#txtCreditCardNumber").val())=="")
		{
			$("#RestaurantInputFinalError").show();
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
                if ($.trim($("#ddlExpMonth").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#ddlExpMonth").focus();
			$("#ddlExpMonth").css("border", "2px solid #FF0000");
                        $("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#ddlExpMonth").css("border", "");
		}
                if ($.trim($("#ddlExpYear").val())=="")
		{
			$("#RestaurantInputFinalError").show();
			$("#ddlExpYear").focus();
			$("#ddlExpYear").css("border", "2px solid #FF0000");
                        $("#submitButton").removeAttr('disabled');
			return false;
		}
		else
		{
			$("#RestaurantInputFinalError").hide();
			$("#ddlExpYear").css("border", "");
		}

                $("#RestaurantInputFinalError").show();
                $("#RestaurantInputFinalError").css("text-decoration", "blink");
                $("#RestaurantInputFinalError").text("Please wait...");

		$.ajax({
			url:"signup_ajax.php?call=saveuser&username="+$.trim($("#txtUserName").val()),
			type:"POST",
                        data: $("#msform").serialize(),
			success:function(data)
			{
				console.log(data);
				if ($.trim(data).toLowerCase()=="duplicate")
				{
					$("#RestaurantInputFinalError").text("Username already exists.");
					$("#RestaurantInputFinalError").show();
					$("#txtUserName").focus();
					$("#txtUserName").css("border", "2px solid #FF0000");
                                        $("#submitButton").removeAttr('disabled');
                                        $("#submitButton").removeClass("Save progress-button finished").addClass("Save progress-button");
					return false;
				}
				else if ($.trim(data).toLowerCase()=="error")
				{
					$("#txtUserName").focus();
					$("#RestaurantInputFinalError").text("Error occurred. Request cannot be completed.");
					$("#RestaurantInputFinalError").show();
                                        $("#submitButton").removeClass("Save progress-button finished").addClass("Save progress-button");
					return false;
				}
                                else if(data.indexOf("ERROR") > -1)
                                {
                                    $("#RestaurantInputFinalError").show();
                                    $("#RestaurantInputFinalError").text(data);
                                    $("#submitButton").removeClass("Save progress-button finished").addClass("Save progress-button");
                                }
				else if(data > 0 &&!isNaN(data))
				{
                                    if (jQuery.isEmptyObject(dataObject) )
                                    {
                                        window.location = "thank_you.php"
                                        
                                    }
                                    else
                                    {
                                         var resrId = data;
                                         $.ajax({
                                            type:"post",
                                            url: "signup_ajax.php?call=uploadMenu&resrId="+resrId,
                                            data: dataFile,
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            success: function(data)
                                            {
                                                if(data!='Error')
                                                {
                                                    window.location = "thank_you.php"
                                                }

                                            }
                                        });
                                    }
				}
			},
			error:function(data)
			{
				$("#txtUserName").focus();
				$("#RestaurantInputAccountError").text("Error occurred. Request cannot be completed.");
				$("#RestaurantInputAccountError").show();
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

    function performClick(elemId) {
               var elem = document.getElementById(elemId);
               if(elem && document.createEvent) {
                  var evt = document.createEvent("MouseEvents");
                  evt.initEvent("click", true, false);
                  elem.dispatchEvent(evt);
               }
    }
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
			last_progress = new Date().getTime()
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

		}, seconds*3000);
	};
})(jQuery);
