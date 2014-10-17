 <form id="checkoutform" name="checkoutform" action="<?php echo $SiteUrl.$objRestaurant->url; ?>/?item=confirmpayments&session_id=<?= session_id() ?>&ifrm=confirmpayments" method="post">
	<? include "if-cart_products.php" ?> 
    <div id="second_body">
        <div id="second_body_col" style="width:650px; float:left;">
      	<? include "if-customer.php" ?>
            <div class="second_body_heading">Special Requests/Notes</div>
            <div class="text_area">
                <textarea name="special_notes" id="special_notes"></textarea>
            </div>
        <? include "if-valutec.php" ?>
            <!-- RESTAURANT USE VIP CARD --> 
        </div>
        <div class="clear"> </div>
    </div>
         <? include "if-paymentmethods.php" ?>


</form>
<script src="//maps.googleapis.com/maps/api/js?key=<?=$google_api_key?>&sensor=false" type="text/javascript"></script>
<!--<script src="http://maps.google.com/maps?file=api&v=2&key=<?=$google_api_key?>" type="text/javascript"></script> -->
<!--<script src="../js/deliveryzones.js" type="text/javascript"></script>-->
<script src="../js/checkdeliveryzones.js" type="text/javascript"></script>
<script type="text/javascript">

var geocoder, location1, location2, radius_verified;
geocoder = new google.maps.Geocoder();
radius_verified = - 1;
var Zone1, Zone1_enabled;
var Zone2, Zone2_enabled;
var Zone3, Zone3_enabled;
document.getElementById("delivery_tip").disabled = true
document.getElementById("btn_delivery_tip").disabled = true;
function formatCurrency(num) {

    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = 0;
    
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    var l = new String(cents)

    if (l.length == 3) {
        cents = '0' + cents;
    }else if (l.length == 1) {
        cents = '0' + cents;
    }
    
    if (l.length == 0) {
        cents = '00' + cents;
    }

    num = Math.floor(num / 100).toString();
    //if(cents<10)
    //cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
    num = num.substring(0, num.length - (4 * i + 3)) + ',' +
    num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + '<?=$java_currency?>' + num + '.' + cents);
}



window.verifylocation = function() { 
    
    if (!$('input:radio[name=payment_method]:checked').val()){
        alert("please select payment method");
        return false;
    }
    if (!$("#checkoutform").valid()) {
        return false;
    }
    if (<?= $cart->delivery_type ?> == <?= cart::Pickup ?>) {
        submitme();
        return false;
    }

    var restaurant_location = '<?= $objRestaurant->rest_address . ", " . $objRestaurant->rest_city . ", " . $objRestaurant->rest_state; ?>';
<? if (is_numeric($loggedinuser->id)) { ?>
        var customer_location = '<?= $loggedinuser->get_delivery_address(0); ?>';
<? } else { ?>
        var customer_location = $("#customer_address").val() + " , " + $("#customer_city").val() + " , " + $("#customer_state").val();
<? } ?>
    var radius = '<?= $objRestaurant->delivery_radius ?>';
    geocoder.geocode({'address': restaurant_location}, function(results, status) {

        if (status != google.maps.GeocoderStatus.OK){
            alert("Sorry, we were unable to recognize the resturant address");
            return false;
        } else {
            var position = results[0].geometry.location;
            location1 = position;
            var restaurantlocation = new google.maps.LatLng(parseFloat(position.lat()), parseFloat(position.lng()));

            geocoder.geocode({'address': customer_location}, function(results, status) {                    
                if (status != google.maps.GeocoderStatus.OK){
                    alert("Sorry, we were unable to recognize the customer address");
                    return false;
                } else {

                    var position1 = results[0].geometry.location;
		      location2 = position1;
                    var customerlatlang = new google.maps.LatLng(parseFloat(position1.lat()), parseFloat(position1.lng()));

                    if ('<?= $objRestaurant->delivery_option ?>' == 'delivery_zones') {

                        Zone1_enabled = <?= $objRestaurant->zone1 ?> ;
                        Zone2_enabled = <?= $objRestaurant->zone2 ?> ;
                        Zone3_enabled = <?= $objRestaurant->zone3 ?> ;

                        drawzones(restaurantlocation);

                        if (Zone1_enabled && Zone1.containaddress(customerlatlang)) {
                            submitme();
                        } else if (Zone2_enabled && Zone2.containaddress(customerlatlang)) {
                            showConfirmation(2, customer_location)
                        } else if (Zone3_enabled && Zone3.containaddress(customerlatlang)) {
                            showConfirmation(3, customer_location)
                        } else {
                            showConfirmation(4, customer_location);
                        }

                    } else {
                        if (calculateDistance(radius)) {

                            submitme();
                        }
                    }
                }
            });
        }
    });
};

function showConfirmation(zone, location) {
 
			$(".display_radius_msg_area").removeClass('alert-error');
			 $(".display_radius_msg_area").addClass('alert-error');
			 
			var cartSubTotal=<?=$cart->sub_total ?>;
			var restMinTotal,charges;
				if(zone==4){
		        var msgg = 'You are out side of our delivery zones';
		        $(".display_radius_msg_area").html("<strong>" + msgg + "</strong>");
		        report_abandoned_cart_error({"type": "out_of_area", "msg": msgg});
					$.facebox(  {div:  "#delivery_msg_area"});
					return false;
					}
			if(zone==2){
				restMinTotal ='<?= $objRestaurant->zone2_min_total?>'
				charges='<?= $objRestaurant->zone2_delivery_charges?>';
			}else{
				restMinTotal ='<?= $objRestaurant->zone3_min_total?>';
				charges='<?= $objRestaurant->zone3_delivery_charges?>';
			}
			var msg=''
			if(cartSubTotal<restMinTotal)
			{
				msg='<br/><br/><b><?=$java_currency?>'+ restMinTotal +'</b> of food required to checkout. Please add more items <br/><br/> ';
			}
			else {
				msg='<strong>"<a href="javascript:iagree('+charges+');">I Agree</a>"</strong>' ;
			}
			
			$(".display_radius_msg_area").html('<strong>You are in extended delivery zone. <?=$java_currency?>' + charges  + ' delivery charges will be charged.</strong> '+ msg);
			$.facebox(  {div:  "#delivery_msg_area"});
		}
		function iagree(charges) {
				$("#cart_delivery_charges").val(charges);
				 submitme();
			
			}
function drawzones(restaurantlocation) {        
    var Zone1Coordinates, Zone2Coordinates, Zone3Coordinates;

    Zone1Coordinates = '<?= $objRestaurant->zone1_coordinates ?>';
    Zone2Coordinates = '<?= $objRestaurant->zone2_coordinates ?>';
    Zone3Coordinates = '<?= $objRestaurant->zone3_coordinates ?>';

    Zone1 = new DeliveryZone(Zone1Coordinates, '#00CC00', 3, 1, '#00A333', 0.2, 0.02, restaurantlocation);
    Zone2 = new DeliveryZone(Zone2Coordinates, '#003595', 3, 1, '#000088', 0.2, 0.025, restaurantlocation);
    Zone3 = new DeliveryZone(Zone3Coordinates, '#f33f00', 3, 1, '#ffaa00', 0.2, 0.03, restaurantlocation);

    var defaultZone1;
    var defaultZone2;
    var defaultZone3;
    if (Zone1Coordinates == '')
        defaultZone1 = true;
    else
        defaultZone1 = false;

    if (Zone2Coordinates == '')
        defaultZone2 = true;
    else
        defaultZone2 = false;

    if (Zone3Coordinates == '')
        defaultZone3 = true;
    else
        defaultZone3 = false;

    Zone1.drawZone(false, defaultZone1);
    Zone2.drawZone(false, defaultZone2);
    Zone3.drawZone(false, defaultZone3);
}
 
 	function submitme() {
    if ($('input:radio[name=payment_method]:checked').val() == 1){
        if(<?= $cart->delivery_type ?> != <?= cart::Pickup ?>)
        {
            if($("#li_tip").text()=="" ){

            if ($("#li_tip").text()=="" && $('input[name="rbd_tip"]:checked').length ==0 ) {
                $("#tip_section").show();
                return false;
            }

            else if ($("#li_tip").text()=="" && $('input[name="rbd_tip"]:checked').val() ==2 ) {
                $("#tip_section").show();
                return false;
            }
        }
    }   
			$("#checkoutform").attr('action', '<?php echo $checkoutProtocol.str_replace("https://", "", str_replace("http://", "", $SiteUrl)).$objRestaurant->url;?>/?item=confirmpayments&session_id=<?= session_id() ?>&ifrm=confirmpayments');
		 }
		 else
		 {
			  $("#checkoutform").attr('action','?item=submitorder&ifrm=submitorder');
		 }
			$("#checkoutform").submit();
			$("#btncheckout").attr('disabled','disabled');
 }
	
	
	
	function calculateDistance(radius)
	{
		try
		{
        var glatlng1 = new google.maps.LatLng(parseFloat(location1.lat()), parseFloat(location1.lng()));
        var glatlng2 = new google.maps.LatLng(parseFloat(location2.lat()), parseFloat(location2.lng()));
			var miledistance = parseFloat(glatlng1.distanceFrom(glatlng2, 3959).toFixed(1));
			var kmdistance = (miledistance * 1.609344).toFixed(1);
			radius = parseFloat(radius);
		 	if(radius < miledistance ) {
			 $(".display_radius_msg_area").removeClass('alert-error');
			  $(".display_radius_msg_area").addClass('alert-error');
			  $(".display_radius_msg_area").html('<strong>Sorry! We only deliver within '+radius+' miles radius. You are '+ miledistance +' miles away from the resturant.</strong>');
				 jQuery.facebox({div:  "#delivery_msg_area"});
				return  false;
				 
				 
			} else {
				return true;
			}
			
		}
		catch (error)
		{ 
			alert(error);
			return false;
		}
	}
	function showhideCCoption(opt) {
		if(opt==1)  
		$("#cardOpt").fadeIn('500');
	else
	{
		$("#cardOpt").fadeOut('500');
	 
	}
		
		}

$(function() {
	
	 $("#checkoutform").validate({
					   rules: {
							customer_name: {required: true  },
							customer_last_name: {required: true},
							customer_phone: {required: true,minlength: 5},
							customer_email: {required: true,email:1},
							customer_address: {required: true,minlength: 3},
							customer_city: {required: true,minlength: 3},
							customer_state: {required: true,minlength: 2},
							customer_zip: {required: true,minlength: 2},
							payment_method :{required: true}
							 
					   },
					   messages: {
							 customer_name: {
										   required: "please enter your first name"
									   },
						 customer_last_name: {
										   required: "please enter your last name"
									 },
							customer_phone: {
								   required: "please enter your phone number",
								   minlength: "please enter a valid phone number"
									
							   },
							   customer_email: {
										   required: "please enter your email address",
										   email: "please enter a valid email address"
										   },
							 customer_address: {
								   required: "please enter your street address",
								   minlength: "please enter a valid address"
									
							   },
							 customer_city: {
								   required: "please enter your city",
								   minlength: "please enter a valid city"
									
							   },
							 customer_state: {
								   required: "please enter your state",
								   minlength: "please enter a valid state"
									
							   },
							customer_zip: {
								   required: "please enter your zip",
								   minlength: "please enter a valid zip"
									
							   },
							   payment_method :{ required: "please select payment method"}
							
								   
					   },
					   errorElement: "span",
				   
					  errorClass: "alert-error"
					 
			});
	 
		 
     $('#driver_tip').blur(function() {
			$('#driver_tip').val(formatCurrency($('#driver_tip').val()));
		 });
	 
	 $('#vipredeemreward').blur(function() {
 		$('#vipredeemreward').val(formatCurrency($('#vipredeemreward').val()));
 	 });
	 
	 	
	  $('#serving_date').change(function(){
		  var selectindex =$(this).get(0).selectedIndex;
	
		  if(selectindex==0) 
		   {
			   if( $("#serving_time option[value='0']").length ==0)
				 $('#serving_time').append( new Option('As Soon as Possible','0',true,true) );
			}
		  else
		   {
			   if( $("#serving_time option[value='0']").length > 0)
			  	 $("#serving_time option[value='0']").remove();
		 	}
		  });
		  
		  $("#driver_tip").keypress(function(e) {
				if(e.which == 13) {
				 addtip();
				 e.preventDefault();
				}
				
			});

	$("#btntip").click(function(){
			addtip();
		});
		
     $('input:radio[name="rbd_tip"]').change(function(){
                
                if ($('input:radio[name=rbd_tip]:checked').val() == 0){
                    document.getElementById("delivery_tip").disabled = true
                    document.getElementById("btn_delivery_tip").disabled = true;
                    add_delivery_tip('0');
                }
                else if ($('input:radio[name=rbd_tip]:checked').val() == 1){
                    document.getElementById("delivery_tip").disabled = true
                    document.getElementById("btn_delivery_tip").disabled = true;
                    add_delivery_tip('<?=number_format((20/100)*$cart->grand_total(),2)?>');
                }
                else if($('input:radio[name=rbd_tip]:checked').val() == 2){
                    document.getElementById("delivery_tip").disabled = false
                    document.getElementById("btn_delivery_tip").disabled = false;
                    add_delivery_tip('0');
                }
    });

    $("#delivery_tip").keypress(function(e) {
        if (e.which == 13) {
             add_delivery_tip($("#delivery_tip").val());
            e.preventDefault();
        }
    });

    $("#btn_delivery_tip").click(function() {
        add_delivery_tip($("#delivery_tip").val());
    });

    function add_delivery_tip(tip) {
        //var tip = $("#delivery_tip").val();
      
        $.post('?item=addtip&ajax=1&ifrm=addtip', {tip: tip, action: "updatetip"}, function(data) {
            $(".cart_statsarea").html(data);
        });
    }
	 function addtip(){
			var tip=$("#driver_tip").val();
				$.post('?item=addtip&ajax=1&ifrm=addtip',{tip:tip,action:"updatetip"},function(data) {
				$(".cart_statsarea").html(data);
			});
	}
	
	
			$("#btncoupon").click(function(){
					redeemCoupon();
				});
			$("#coupon_code").keypress(function(e) {
			 		 if(e.which == 13) {
						 redeemCoupon();
						 e.preventDefault();
					 }
				 
				 });
					
			function redeemCoupon(){
					var coupon_code=$("#coupon_code").val();
					$.post('?item=redeemcoupon&ajax=1&ifrm=redeemcoupon',{coupon_code:coupon_code,action:"redeemcoupon"},function(data) {
						$(".cart_statsarea").html(data);
					});				
			 }			 
			 $("#applyvipreward").click(function(){
					vipredeemreward();
				});
				
			 $("#applymaxvipreward").click(function(){
				  $("#vipredeemreward").val(<?=$MAX_REWARD?>);
					vipredeemreward();
				});
				
			$("#vipredeemreward").keypress(function(e) {
			 
					 if(e.which == 13) {
						 e.preventDefault();
						 vipredeemreward();
						}
				 
				 });
					
			function vipredeemreward(){
					var vipreward=$("#vipredeemreward").val();
				 
					$.post('?item=redeemcoupon&ajax=1&ifrm=redeemcoupon',{vipreward:vipreward,action:"vipreward"},function(data) {
						$(".cart_statsarea").html(data);
						$('#vipredeemreward').val(formatCurrency($('#vipdiscount').html()));
					});
					
					
			 }

	});

</script>