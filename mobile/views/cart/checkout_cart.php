<style>
.normal{float:none;}
</style>
<h1>Shopping Cart - Confirm Order</h1>
<div class="cart">
  <div class="cart_header">
    <div class="item1">Item</div>
    <div class="item2">Description</div>
    <div class="item3">Qty</div>
    <div class="item4">Total</div>
    <div class="clear"></div>
  </div>
  <? 
$index=-1;
  
  foreach($cart->products as $prod ) {
	 $index +=1;
  ?>
  <div class="cart_row">
    <div class="item1">
      <?= $prod->item_title ?>
    </div>
    <div class="item2">
      <? $last_att='--';
	$arr_index=0;
	 foreach($prod->attributes as $attr) { 
	  if($attr->Option_name!=$last_att && $last_att!='--')  echo "</div>";
	  if($attr->Option_name!=$last_att) {$arr_index=0; $last_att=$attr->Option_name;
	  echo " <div class='bold'>".$attr->Option_name .":"
     	?>
      <? } ?>
      <span class="normal">
      <?   echo ($arr_index>0 ? ", ":"") . $attr->Title ." ".  ($attr->Price=='0' ?'' : ($attr->Price[0]=='-' ? 'Subtract': 'Add'   ). $currency . currencyToNumber($attr->Price)) ;?>
      </span>
      <? $arr_index +=1;
		  }
	  // FO RACH ATTR 
     if($arr_index>0) echo "</div>";
      
	  if(count($prod->associations)) echo "<div class='bold'>Associated Product:";
	  
	   foreach($prod->associations as $prd) { ?>
      <span class="normal">
      <?= $prd->item_title .' Add '.$currency. $prd->retail_price ?>
      </span>
      <? }
	   if(count($prod->associations)) echo "</div>";
	 ?>
      <div class="bold">Special Instruction:<span class="normal">
        <?=  $prod->requestnote ?>
        </span></div>
     <!-- <div class="bold"> This Item For:<span class="normal">
        <?  // $prod->item_for ?>
        </span></div>-->
    </div>
    <!-- ITME 2-->
    <div class="item3">
      <?= $prod->quantity?>
    </div>
    <div class="item4">
      <?= $currency.($prod->sale_price * $prod->quantity) ?>
    </div>
    <div class="clear"></div>
  </div>
  <? }// FOR EACH PRODUCT ?>
</div>

<div>
<div class="border_box left cart_stats" style="width:auto; margin-right:auto;">  
<div class="bold strip">You chose: <span class="normal red"><?= $cart->delivery_type==cart::Delivery ?"Delivery":"Pickup" ?></span>
<input type="hidden" name="customer_name" id="customer_name"  value="<?=$loggedinuser->cust_your_name ?>" />
<input type="hidden" name="customer_phone" id="customer_phone" value="<?=$loggedinuser->cust_phone1 ?>" />
<input type="hidden" name="customer_email" id="customer_email" value="<?=$loggedinuser->cust_email ?>" />
<input type="hidden" name="customer_address" id="customer_address" value="<?=$loggedinuser->street1 ?>" />
<input type="hidden" name="customer_city" id="customer_city" value="<?=$loggedinuser->cust_ord_city ?>" />
<input type="hidden" name="customer_state" id="customer_state"  value="<?=$loggedinuser->cust_ord_state ?>" />
<input type="hidden" name="customer_zip" id="customer_zip"  value="<?=$loggedinuser->cust_ord_zip ?>" />

</div>

 <? if($cart->delivery_type==cart::Delivery) { ?>
<div class="bold strip">Contact : <span class="normal" style="overflow:hidden;max-width:30px;"><?= $loggedinuser->get_delivery_address()?></span>

<span class="red"><br/><input type="submit" class="button orange" value="Change" name="btncheckout"></span></div>
<? }?>
 </div>
<div class="border_box right cart_stats cart_statsarea">  
<? include "cart_stats.php"; ?>
</div>
<div class="clear"> </div>
</div>

<div class="border_box">
  
  <div class="cart_row1 margintopsmall">
    <div class="item1">For your convenience, you can add gratuity here</div>
    <div class="item2">
      <input type="text" id="driver_tip"  name="driver_tip" maxlength="6" size="4" value="<?= ($cart->driver_tip>0 ?$cart->driver_tip:'') ?>">
    </div>
    <div class="item3">
      <input type="button" id="btntip" name="btntip" value="Add gratuity"  class="button small blue">
    </div>
    <div class="clear"></div>
  </div>
  
  <div class="cart_row1 margintopsmall">
    <div class="item1">Enter Coupon Code</div>
    <div class="item2">
      <input type="text" name="coupon_code" maxlength="6" size="4" value="" id="coupon_code">
    </div>
    <div class="item3">
      <input type="button" name="btncoupon" id="btncoupon" value="Redeem"  class="button small blue">
    </div>
    <div class="clear"></div>
  </div>
  <div class="cart_row1 margintopsmall">
    <div class="item1"> Select
      <?= ($cart->delivery_type==cart::Delivery ? "Delivery" :"Pickup"); ?>
      Date </div>
    <div class="item2">
      <select name="serving_date" id="serving_date"  style="width:120px; ">
        <?=$function_obj->get_datelist('')?>
      </select>
    </div>
    <div class="clear"></div>
  </div>
  <div class="cart_row1 margintopsmall">
    <div class="item1"> Select
      <?= ($cart->delivery_type==cart::Delivery ? "Delivery" :"Pickup"); ?>
      Time </div>
    <div class="item2">
      <select name="serving_time" id="serving_time"  >
        <option value="0">As Soon as Possible</option>
        <option value="10:00" <? if($_SESSION['selectime'] == '10:00'){?> selected="selected"<? }?>>10:00 AM</option>
        <option value="10:15" <? if($_SESSION['selectime'] == '10:15'){?> selected="selected"<? }?>>10:15 AM</option>
        <option value="10:30" <? if($_SESSION['selectime'] == '10:30'){?> selected="selected"<? }?>>10:30 AM</option>
        <option value="10:45" <? if($_SESSION['selectime'] == '10:45'){?> selected="selected"<? }?>>10:45 AM</option>
        <option value="11:00" <? if($_SESSION['selectime'] == '11:00'){?> selected="selected"<? }?>>11:00 AM</option>
        <option value="11:15" <? if($_SESSION['selectime'] == '11:15'){?> selected="selected"<? }?>>11:15 AM</option>
        <option value="11:30" <? if($_SESSION['selectime'] == '11:30'){?> selected="selected"<? }?>>11:30 AM</option>
        <option value="11:45" <? if($_SESSION['selectime'] == '11:45'){?> selected="selected"<? }?>>11:45 AM</option>
        <option value="12:00" <? if($_SESSION['selectime'] == '12:00'){?> selected="selected"<? }?>>12:00 PM</option>
        <option value="12:15" <? if($_SESSION['selectime'] == '12:15'){?> selected="selected"<? }?>>12:15 PM</option>
        <option value="12:30" <? if($_SESSION['selectime'] == '12:30'){?> selected="selected"<? }?>>12:30 PM</option>
        <option value="12:45" <? if($_SESSION['selectime'] == '12:45'){?> selected="selected"<? }?>>12:45 PM</option>
        <?
				$val = 13;
				 for($i=1; $i<=10; $i++)
					{
						for($j=0; $j<=45; $j = $j+15)
							{
								if($i < 10){ $hour = '0'.$i;}else{ $hour = $i;}
								if($j == 0){ $mints = '00';}else{	$mints = $j;}
								$option_title = $hour.':'.$mints.' PM';
								$option_val = $val.':'.$mints;?>
        <option value="<?=$option_val?>"  >
        <?=$option_title?>
        </option>
        <?		
							}$val++;
					
					}
				?>
        <option value="23:00" >11:00 PM</option>
      </select>
    </div>
    <div class="clear"></div>
  </div>
</div>
<!-- Add by asher-->
 <? include "views/valutec.php" ?>
<!-- -->
<div class="tip_section" id="tip_section" >
    <div class="tipmain">Oops!  Looks like you forgot to enter a tip</div>
    <ul style="list-style: none;"><ol><li>
            <div class="tiptext"> <input type="radio" name="rbd_tip"  value="1" class="rdbclass"/>Please add 20% (<?=$currency.number_format((20/100)*$cart->grand_total(),2)?>)</div>
        </li>
        <li>
            <div class="tiptext"><input type="radio" name="rbd_tip"  value="2" class="rdbclass"/>Enter my own Amount:
     <input type="text" id="delivery_tip" value="<?=$currency?>0" size="4" maxlength="4" name="delivery_tip" class="valid inputtiptext" style="margin-left: 21px;">
   <input type="button" name="btn_delivery_tip" id="btn_delivery_tip" value="Add gratuity" class="button small blue inputtip"></div>
    </li>
    <li>
        <div class="tiptext"><input type="radio" name="rbd_tip"  value="0" class="rdbclass"/>I will not tip on the card</div>
        </li></ol>
    </ul>
   </div>
<div class="box">
  <div class="bold margintopsmall"> Payment Method</div>
  <div class="margintopsmall normal">
    <? if ($objRestaurant->credit_card==1) { ?>
    <div class="one-half column-first">
      <input type="radio"  name="payment_method" id="payment_method" value='1'>
      Credit Card Payment</div>
    <? }  if ($objRestaurant->cash==1) { ?>
    <div class="one-half column-last">
      <input type="radio"  name="payment_method" id="payment_method" value='2'>
      Cash Payment After Delivery</div>
    <? } ?>
    <div class="clear"></div>
  </div>
</div>
 
  <div class="display_radius_msg_area"></div>
 

<div class="rightalign" style="margin-left:40%;">
  <input type="button" name="btncheckout" id="btncheckout" value="I Agree & Place Order"  class="button blue"  onclick="return verifylocation();">
</div>
<script src="//maps.googleapis.com/maps/api/js?key=<?=$google_api_key?>&sensor=false" type="text/javascript"></script>
<!--<script src="http://maps.google.com/maps?file=api&v=2&key=<?=$google_api_key?>" type="text/javascript"></script>-->
<!--<script src="../js/deliveryzones.js" type="text/javascript"></script>-->
<script src="../js/checkdeliveryzones.js" type="text/javascript"></script>
<script type="text/javascript">

var geocoder, location1, location2, radius_verified;
geocoder = new google.maps.Geocoder();
radius_verified = - 1;
var Zone1, Zone1_enabled;
var Zone2, Zone2_enabled;
var Zone3, Zone3_enabled;
document.getElementById("delivery_tip").disabled = true;
document.getElementById("btn_delivery_tip").disabled = true;
	function initialize() {
		geocoder = new GClientGeocoder();
		radius_verified=-1;
	}
	initialize();
	
	 
	
	
function verifylocation() { 
 
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
                    var position = results[0].geometry.location;
                    location2 = position;
                    var customerlatlang = new google.maps.LatLng(parseFloat(position.lat()), parseFloat(position.lng()));
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
    var cartSubTotal = <?= $cart->sub_total ?> ;
    var restMinTotal, charges;
    
    if (zone == 4) {
        var msgg = 'You are out side of our delivery zones';
        $(".display_radius_msg_area").html("<strong>" + msgg + "</strong>");
        report_abandoned_cart_error({"type": "out_of_area", "msg": msgg});
        $.facebox({div: "#delivery_msg_area"});
        return false;
    }
    
    if (zone == 2) {
        restMinTotal = '<?= $objRestaurant->zone2_min_total ?>'
        charges = '<?= $objRestaurant->zone2_delivery_charges ?>';
    } else {
        restMinTotal = '<?= $objRestaurant->zone3_min_total ?>';
        charges = '<?= $objRestaurant->zone3_delivery_charges ?>';
    }
    
    var msg = ''
    
    if (cartSubTotal < restMinTotal){
        msg = '<br/><br/><b><?=$java_currency?>' + restMinTotal + '</b> of food required to checkout. Please add more items <br/><br/> ';
    }else {
        msg = '"<a href="javascript:iagree(' + charges + ');">I Agree</a>"';
    }
    
    msg = 'You are in extended delivery zone. <?=$java_currency?>' + charges + ' delivery charges will be charged. ' + msg;
    $(".display_radius_msg_area").html("<strong>" + msg + "</strong>");
    report_abandoned_cart_error({"type": "under_delivery_minimum", "zone": location, "minTotal": restMinTotal});
    $.facebox({div: "#delivery_msg_area"});
}

function report_abandoned_cart_error(msg) {
    $.post('?item=report_abandoned_cart_error&ajax=1', msg, function(data) {
    });
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
        if($("#li_tip").text()==""){
        if ($("#li_tip").text()=="" && $('input[name="rbd_tip"]:checked').length ==0 ) {
            $("#tip_section").show();
            return false;
        }
       
        else if ($("#li_tip").text()=="" && $('input[name="rbd_tip"]:checked').val() ==2 ) {
            $("#tip_section").show();
            return false;
        }
    }
		
			$("#checkoutform").attr('action', '<?php echo $checkoutProtocol.str_replace("https://", "", str_replace("http://", "", $SiteUrl)).$objRestaurant->url;?>/?item=confirmpayments&session_id=<?= session_id() ?>');
		 }
		 else
		 {
			  $("#checkoutform").attr('action','?item=submitorder');
		 }
			$("#checkoutform").submit();
									
	
	}

	function calculateDistance(radius)
	{
		try
		{
			
        var glatlng1 = new google.maps.LatLng(parseFloat(location1.lat), parseFloat(location1.lng));
        var glatlng2 = new google.maps.LatLng(parseFloat(location2.lat), parseFloat(location2.lng));
        var miledistance = parseFloat(glatlng1.distanceFrom(glatlng2, 3959).toFixed(1));
        var kmdistance = (miledistance * 1.609344).toFixed(1);
        radius = parseFloat(radius);
			if(radius < miledistance ) {
			 $(".display_radius_msg_area").removeClass('alert-error');
			  $(".display_radius_msg_area").addClass('alert-error');
			  $(".display_radius_msg_area").html('<strong>Sorry! We only deliver within '+radius+' miles radius. You are '+ miledistance +' miles away from the resturant.</strong>');
			 
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
</script>
<script type="text/javascript">
$(function() {
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
      
        $.post('?item=addtip&ajax=1', {tip: tip, action: "updatetip"}, function(data) {
            $(".cart_statsarea").html(data);
        });
    }
	 function addtip(){
			var tip=$("#driver_tip").val();
		$.post('?item=addtip&ajax=1',{tip:tip,action:"updatetip"},function(data) {
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
	 		$.post('?item=redeemcoupon&ajax=1',{coupon_code:coupon_code,action:"redeemcoupon"},function(data) {
				$(".cart_statsarea").html(data);
			});
			
	 }
         //ADD below code by Asher

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

					$.post('?item=redeemcoupon&ajax=1',{vipreward:vipreward,action:"vipreward"},function(data) {
						$(".cart_statsarea").html(data);
						$('#vipredeemreward').val(formatCurrency($('#vipdiscount').html()));
					});


			 }
	});

</script>