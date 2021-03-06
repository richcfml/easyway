<?php
//echo "<pre>"; print_r($loggedinuser); echo "</pre>"; die();
?>
<body class=checkout>
<div class=notification__overlay></div>
<?php require($mobile_root_path . "includes/header.php"); ?>
<main class=main>
  <div class=main__container>
    <section class=section__article>
      <p class="redtxt" id="googleerror"></p>
      <span class="redtxt" id="error"></span>
      <form action="" method="post" class="section__article-content" id="checkout-form">
        <input type="hidden" name="btnCheckout" id="btnCheckout">
        <input type="hidden" name="serving_date_time" id="serving_date_time" />
        <input type="hidden" name="address_option" id="address_option" id="1" />
        <input type="hidden" name="customer_name" id="customer_name" value="<?=$loggedinuser->cust_your_name?>" />
        <input type="hidden" name="customer_last_name" id="customer_last_name" value="<?=$loggedinuser->LastName?>" />
        <input type="hidden" name="customer_phone" id="customer_phone" value="<?=$loggedinuser->cust_phone1?>" />
        <input type="hidden" name="customer_email" id="customer_email" value="<?=$loggedinuser->cust_email ?>" />
        <input type="hidden" name="special_notes" id="special_notes" />
        
        <div class='checkout__step open' data-name='Pickup details'>
          <fieldset>
            <legend>Complete your order</legend>
            <div class=checkout-radio__group>
              <input type="radio" name="delivery" value="Pickup" id="pickup" <?=$cart->delivery_type==cart::Pickup? "checked":""?> class="checkout-radio" />
              <label class="checkout-radio__label" for="pickup">Pickup</label>
              <input type="radio" name="delivery" value="delivery" id="delivery" <?=$cart->delivery_type==cart::Delivery? "checked":""?> class="checkout-radio right"/>
              <label class="checkout-radio__label" for="delivery">Delivery</label>
            </div>
          </fieldset>
          
          <div id="pickup" <?=($cart->delivery_type==cart::Pickup)? "":"class='hidden'"?>>
            <fieldset>
              <div class="checkout__pickup-date">
                <label for="pickup_datetime">Pickup date/time: </label>
                <div class=input>
                  <input id="pickup_datetime" value="As soon as possible" class=js-pickup />
                  <span class=input-error>Invalid date/time</span> 
                </div>
              </div>
            </fieldset>
            
            <fieldset>
              <legend class=checkout__pickup-information>Pick up information</legend>
              <div class=contact-fields>
                <div class=contact-fields__name>
                  <label for="first_name">First name: </label>
                  <div class=input>
                    <input id="pcustomer_name" value="<?=$loggedinuser->cust_your_name?>" autocomplete="given-name" />
                    <span class=input-error>Invalid first name</span> 
                  </div>
                </div>
                
                <div class='contact-fields__name right'>
                  <label for=last_name>Last name: </label>
                  <div class=input>
                    <input id="pcustomer_last_name" value="<?=$loggedinuser->LastName?>" autocomplete="family-name" />
                    <span class=input-error>Invalid last name</span> 
                  </div>
                </div>
              </div>
              
              <label for="email">Email: </label>
              <div class=input>
                <input type="email" id="pcustomer_email" placeholder="e.g. john@doe.com" value="<?=$loggedinuser->cust_email ?>" autocomplete="email" />
                <span class=input-error>Please provide a valid email</span> 
              </div>
              
              <label for="phone_number">Phone number: </label>
              <div class=input>
                <input type="tel" id="pcustomer_phone" placeholder="e.g. 202-555-0114" value="<?=$loggedinuser->cust_phone1?>" autocomplete="tel" />
                <span class=input-error>Please provide a valid phone</span> 
              </div>
              
              <label for="pickup_instructions"> Got any pick up instructions? </label>
              <textarea id="special_notes_p" rows="" cols=""></textarea>
            </fieldset>
          </div>
          
          <div id="delivery" <?=($cart->delivery_type==cart::Delivery)? "":"class='hidden'"?>>
            <fieldset>
              <div class="checkout__pickup-date">
                <label for="delivery_datetime">Delivery date/time: </label>
                <div class=input>
                  <input id="delivery_datetime" value="As soon as possible" class=js-pickup />
                  <span class=input-error>Invalid date/time</span> 
                </div>
              </div>
            </fieldset>
            
            <fieldset>
              <legend>Delivery information</legend>
              
              <?php if(is_numeric($loggedinuser->id)){ ?>
              <label for="altDeliverAddr">
                <input type="checkbox" id="altDeliverAddr" name="altDeliverAddr" value="1">
                Alternate Delivery Address 
              </label>
              <?php } ?>
              
              <div class=contact-fields>
                <div class=contact-fields__name>
                  <label for=first_name>First name: </label>
                  <div class=input>
                    <input id="dcustomer_name" value="<?=$loggedinuser->cust_your_name ?>" autocomplete="given-name" />
                    <span class=input-error>Invalid first name</span> 
                  </div>
                </div>
                <div class='contact-fields__name right'>
                  <label for=last_name>Last name: </label>
                  <div class=input>
                    <input id="dcustomer_last_name" value="<?=$loggedinuser->LastName ?>" autocomplete="family-name" />
                    <span class=input-error>Invalid last name</span> 
                  </div>
                </div>
              </div>
              
              <label for="email">Email: </label>
              <div class=input>
                <input type="email" id="dcustomer_email" placeholder="e.g. john@doe.com" value="<?=$loggedinuser->cust_email ?>" autocomplete="email" />
                <span class=input-error>Please provide a valid email</span> 
              </div>
              
              <label for=phone_number>Phone number: </label>
              <div class=input>
                  <input type="tel" id="dcustomer_phone"  value="<?=$loggedinuser->cust_phone1?>" placeholder="e.g. 202-555-0114" autocomplete="tel" />
                <span class=input-error>Please provide a valid phone</span> 
              </div>
              
              <label for="street_address">Street address: </label>
              <div class=input>
                <input name="customer_address" id="customer_address" autocomplete="street-customer_address" value="<?=(($loggedinuser->id > 0)? $loggedinuser->street1.' '.$loggedinuser->street2:'') ?>" />
                <span class=input-error>Error</span> 
              </div>
              
              <label for="apt">Apt: </label>
              <div class=input>
                <input name="apt" id="apt" />
                <span class=input-error>Error</span> 
              </div>
              
              <label for="zip">Zip: </label>
              <div class=input>
                <input name="customer_zip" id="customer_zip" value="<?=$loggedinuser->cust_ord_zip ?>" autocomplete="postal-code" />
                <span class=input-error>Error</span> 
              </div>
              
              <label for="city">City: </label>
              <div class=input>
                <input name="customer_city" id="customer_city" value="<?=$loggedinuser->cust_ord_city ?>" autocomplete="address-level2" />
                <span class=input-error>Error</span> 
              </div>
              
              <label for="state">State: </label>
              <div class=input>
                <input name="customer_state" id="customer_state" value="<?=$loggedinuser->cust_ord_state ?>" autocomplete="address-level1" />
                <span class=input-error>Error</span> 
              </div>
              
              <label for="pickup_instructions"> Got any delivery instructions? </label>
              <textarea name="special_notes" id="special_notes_d" rows="" cols=""></textarea>
            </fieldset>
          </div>
        </div>
        
        <?php
		  $cardName=''; $cardNum=''; $cardExpM=''; $cardExpY='';  $cardCVV='';
		  $card_token=0;
		  
		  foreach($loggedinuser->arrTokens as $token){
			if($token->data_3 == 1){
			  if($token->data_type==AMEX)
			  {
				  $cardName="American Express";
			  }
			  else if($token->data_type==VISA)
			  {
				  $cardName="VISA";
			  }
			  else  if($token->data_type==MASTER)
			  {
				  $cardName="MasterCard";
			  }
			  else  if($token->data_type==DISCOVER)
			  {
				  $cardName="Discover";
			  }
			  
			  $cardNum = "Ending in ".$token->data_1;
			  $cardExpM = ($token->card_expiry != '')? substr($token->card_expiry, 0,2):'';
			  $cardExpY = ($token->card_expiry != '')? substr($token->card_expiry, 2, 3):'';
			  $card_token = $token->data_2;
			}
		  }
		?>
        <div class=checkout__step data-name=Payment>
          <fieldset>
            <legend>Payment method</legend>
            <div class=checkout-radio__group>
              <input type="radio" name="method" value="credit_card" id="credit_card" checked class="checkout-radio" />
              <label class="checkout-radio__label" for="credit_card">Credit Card</label>
              <input type="radio" name="method" value="cash" id="cash" class="checkout-radio right"/>
              <label class="checkout-radio__label" for="cash">Cash</label>
            </div>
          </fieldset>
          <div id=credit_card>
            <div class=checkout__supported-cards> <i class=master-card></i> <i class=amex></i> <i class=visa></i> </div>
            <fieldset>
              <legend>Card information</legend>
              <div class=credit-card>
                <div class=credit-card__stripe></div>
                <div class=credit-card__content>
                  <div class='credit-card__number input'>
                    <label for="card_number">Card number: </label>
                    <input name="x_card_num" id="card_number" value="<?=$cardNum?>" maxlength="16" placeholder="Card Number" autocomplete="cc-number" />
                    <input type="hidden" name="card_token" id="card_token" value="<?=$card_token?>">
                    <span class=input-error>Please provide a valid card number</span> 
                  </div>
                  
                  <div class=credit-card__validation>
                    <div class='credit-card__exp-month input'>
                      <label for="expiration_month">Expiration month: </label>
                      <input name="cc_exp_month" id="expiration_month" value="<?=$cardExpM?>" placeholder="MM" autocomplete="cc-exp-month" />
                      <span class=input-error>Invalid</span> 
                    </div>
                    
                    <div class='credit-card__exp-year input'>
                      <label for="expiration_year">Expiration year: </label>
                      <input name="cc_exp_year" id="expiration_year" value="<?=$cardExpY?>" placeholder="YY" autocomplete="cc-exp-year" />
                      <span class=input-error>Invalid</span> 
                    </div>
                    
                    <div class='credit-card__cvv input'>
                      <label for="cvv">Cvv: </label>
                      <input name="x_card_code" id="cvv" placeholder="CVV" autocomplete="cc-csc" />
                      <span class=input-error>Invalid</span> 
                    </div>
                  </div>
                  <div class=credit-card__name>
                    <label for=card_name>Card name: </label>
                    <input name="cc_name" id="card_name" value="<?=$cardName?>" placeholder="Card Name" autocomplete="cc-name" />
                  </div>
                </div>
              </div>
              
              <? 
              $objRestaurant->useValutec = true;
              include "views/valutec.php";
              ?>
            </fieldset>
          </div>
          <fieldset class='checkout__cash hidden' id=cash>
            <i class=checkout__cash-icon></i>
            <p> You will be paying with cash, <br>
              payment due on receipt. </p>
          </fieldset>
        </div>
        
        <div class='checkout__step' data-name='Review order'>
          <fieldset>
            <legend>Review Order</legend>
            <dl class=checkout__summary>
              <dt>Subtotal:</dt>
              <dd>
                <?=$currency.number_format($cart->sub_total,2)?>
              </dd>
              <dt>Sales tax:</dt>
              <dd>
                <?=$currency.number_format($cart->sales_tax(),2)?>
              </dd>
              
              <?php if($cart->delivery_type==cart::Delivery) { ?>
              <dt>Delivery:</dt>
              <dd>
                <span id="deliverchrgs">
                <?=$currency.number_format($cart->rest_delivery_charges,2)?>
                </span>
              </dd>
              <?php } ?>
                
              <dt>Discount:</dt>
              <dd>
                <?=$currency.number_format($cart->coupon_discount,2)?>
              </dd>
              
              <?php if($cart->delivery_type==cart::Delivery) { ?>
              <dt>Tip amount:</dt>
              <dd class=checkout__summary-tip>
                <input name="tip" id="tip" placeholder="<?=$currency.$cart->driver_tip?>" value="<?=$cart->driver_tip?>"/>
              </dd>
              <?php } ?>
              <input type="hidden" id="cart_total" name="cart_total" value="<?= $cart->grand_total() ?>" />
              <input type="hidden" id="cart_delivery_charges" name="cart_delivery_charges" value="<?= $cart->rest_delivery_charges ?>" />
            </dl>
            <div class=checkout__coupon>
              <label for="tip">Got a coupon?</label>
              <div class='checkout__coupon-input input'>
                <input name="coupon_code" id="coupon_code" maxlength="6" size="4" class="checkout__coupon-input" />
                <span class=input-error>Invalid coupon</span> 
              </div>
              <input type="button" value="Add" class="checkout__coupon-button" />
            </div>
            <span class=checkout__coupon-message></span>
          </fieldset>
          <h2 class=checkout__total> 
            <span>Total:</span> 
            <span id="gtotal"><?=$currency.number_format($cart->grand_total(),2)?></span> 
          </h2>
          <input type=button value="Order now!" class="checkout__order-now full-width"/>
        </div>
      </form>
    </section>
  </div>
</main>
<?php
$groupOrderUrl = '';
if(isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"])){
  $groupOrderUrl = '&grp_userid='.$_GET["grp_userid"].'&grpid='.$_GET["grpid"].'&uid='.$_GET["uid"].'&grp_keyvalue='.$_GET['grp_keyvalue'];
}
?>
<script src="../js/checkdeliveryzones.js" type="text/javascript"></script> 
<script>
  var totalerror = 0;
  var deliveryType = '<?=$cart->delivery_type?>';
  
  $(document).ready(function() {
	EasyWay.Checkout.setup();
	
	$("#pcustomer_name,#pcustomer_last_name,#pcustomer_email,#pcustomer_phone,#dcustomer_name,#dcustomer_last_name,#dcustomer_email,#dcustomer_phone,#customer_address,#customer_zip,#customer_city,#customer_state").change(function(){
		$("#address_option").val(0);
	});
	
	$("#customer_address,#customer_zip,#customer_city,#customer_state").change(function(){
		totalerror = 0;
	});
	
	$("#pcustomer_name,#dcustomer_name").blur(function(){
		$("#customer_name").val($(this).val());
	});
	
	$("#pcustomer_last_name,#dcustomer_last_name").blur(function(){
		$("#customer_last_name").val($(this).val());
	});
	
	$("#pcustomer_email,#dcustomer_email").blur(function(){
		$("#customer_email").val($(this).val());
	});
	
	if($("#pickup").is(':checked')){
		updateDeliveryType('<?=cart::Pickup?>');
	}else{
		updateDeliveryType('<?=cart::Delivery?>');
	}
	
	$("#pickup, #delivery").click(function(){
		if($("#pickup").is(':checked')){
			updateDeliveryType('<?=cart::Pickup?>');
		}else{
			updateDeliveryType('<?=cart::Delivery?>');
		}
	});
	
	$("#special_notes_p,#special_notes_d").blur(function(){
		$("#special_notes").val($(this).val());
	});
	
	$("#altDeliverAddr").click(function(){
		if($(this).is(':checked')){
			$("#dcustomer_phone").val('<?=$loggedinuser->cust_phone2?>');
			$("#customer_address").val('<?=$loggedinuser->delivery_street1.' '.$loggedinuser->delivery_street2?>');
			$("#customer_zip").val('<?=$loggedinuser->deivery1_zip?>');
			$("#customer_city").val('<?=$loggedinuser->delivery_city1?>');
			$("#customer_city").val('<?=$loggedinuser->delivery_state1?>');
			$("#address_option").val(2);
		}else{
			$("#dcustomer_phone").val('<?=$loggedinuser->cust_phone1?>');
			$("#customer_address").val('<?=$loggedinuser->street1.' '.$loggedinuser->street2?>');
			$("#customer_zip").val('<?=$loggedinuser->cust_ord_zip?>');
			$("#customer_city").val('<?=$loggedinuser->cust_ord_city?>');
			$("#customer_city").val('<?=$loggedinuser->cust_ord_state?>');
			$("#address_option").val(1);
		}
		
	});
	
	
	// Checkout Submit Form Start
	$("#checkout-form").submit(function(e){
	  var regEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	  var regName = /^[a-zA-Z ]{2,30}$/;
	  
	  // Varify Pickup Details
	  if($('#pickup').is(':checked')){
		if($("#pickup_datetime").val()!=''){
		  $("#serving_date_time").val($("#pickup_datetime").val());
		}
		
		if(regName.test($("#pcustomer_name").val())==false){
			$("#pcustomer_name").siblings('.input-error').show();
			$("#error").html('Invalid First Name.');
			return false;
		}else{
			$("#pcustomer_name").siblings('.input-error').hide();
			$("#error").html('');
		}
		
		if(regName.test($("#pcustomer_last_name").val())==false){
			$("#pcustomer_last_name").siblings('.input-error').show();
			$("#error").html('Invalid Last Name.');
			return false;
		}else{
			$("#pcustomer_last_name").siblings('.input-error').hide();
			$("#error").html('');
		}
		
		if(regEmail.test($("#pcustomer_email").val())==false){
			$("#pcustomer_email").siblings('.input-error').show();
			$("#error").html('Invalid Email Address.');
			return false;
		}else{
			$("#pcustomer_email").siblings('.input-error').hide();
			$("#error").html('');
		}
		
		if($("#pcustomer_phone").val()==''){
			$("#pcustomer_phone").siblings('.input-error').show();
			$("#error").html('Pickup customer phone number is required');
			return false;
		}else{
			$("#pcustomer_phone").siblings('.input-error').hide();
			$("#error").html('');
		}
	  }
	  
	  // Varify Delivery Details
	  else{
		  if($("#delivery_datetime").val()!=''){
			$("#serving_date_time").val($("#delivery_datetime").val());
		  }
		  
		  if(regName.test($("#dcustomer_name").val())==false){
			  $("#dcustomer_name").siblings('.input-error').show();
			  $("#error").html('Invalid First Name.');
			  return false;
		  }else{
		  	$("#dcustomer_name").siblings('.input-error').hide();
			$("#error").html('');
		  }
		  
		  if(regName.test($("#dcustomer_last_name").val())==false){
			  $("#dcustomer_last_name").siblings('.input-error').show();
			  $("#error").html('Invalid Last Name.');
			  return false;
		  }else{
		  	$("#dcustomer_last_name").siblings('.input-error').hide();
			$("#error").html('');
		  }
		  
		  if(regEmail.test($("#dcustomer_email").val())==false){
			  $("#dcustomer_email").siblings('.input-error').show();
			  $("#error").html('Customer email is required');
			  return false;
		  }else{
			  $("#dcustomer_email").siblings('.input-error').hide();
			  $("#error").html('');
		  }
		  
		  if($("#dcustomer_phone").val()==''){
			  $("#dcustomer_phone").siblings('.input-error').show();
			  $("#error").html('Customer phone number is required for Delivery');
			  return false;
		  }else{
		  	$("#dcustomer_phone").siblings('.input-error').hide();
			$("#error").html('');
		  }
		
		  if($("#customer_address").val()=='' || $("#customer_address").val().length < 3){
			  $("#customer_address").siblings('.input-error').show();
			  $("#error").html('Please enter a valid street address');
			  return false;
		  }else{
		  	$("#customer_address").siblings('.input-error').hide();
			$("#error").html('');
		  }
		  
		  if($("#customer_zip").val()=='' || $("#customer_zip").val().length < 2){
			  $("#customer_zip").siblings('.input-error').show();
			  $("#error").html('Please enter a valid zip code');
			  return false;
		  }else{
		  	$("#customer_zip").siblings('.input-error').hide();
			$("#error").html('');
		  }
		  
		  if($("#customer_city").val()=='' || $("#customer_city").val().length < 3){
			  $("#customer_city").siblings('.input-error').show();
			  $("#error").html('Please enter a valid city name');
			  return false;
		  }else{
		  	$("#customer_city").siblings('.input-error').hide();
			$("#error").html('');
		  }
		  
		  if($("#customer_state").val()=='' || $("#customer_state").val().length < 2){
			  $("#customer_state").siblings('.input-error').show();
			  $("#error").html('Please enter a valid state name');
			  return false;
		  }else{
		  	$("#customer_state").siblings('.input-error').hide();
			$("#error").html('');
		  }
		  
		  if(<?=$cart->sub_total?> < <?=$objRestaurant->order_minimum?>){
			$("#error").html('Your order sub-total must be greater than <?=$currency.$objRestaurant->order_minimum?> for delivery.');
			return false;
		  }else{
		  	$("#error").html('');
		  }
		}
	  
	  
	  if($("#card_number").val() != '<?=$cardNum?>'){
		  $("#card_token").val(0);
	  }
	  
	  if($("#expiration_month").val() != '<?=$cardExpM?>'){
		  $("#card_token").val(0);
	  }
	  
	  if($("#expiration_year").val() != '<?=$cardExpY?>'){
		  $("#card_token").val(0);
	  }
	  
	  if($('#credit_card').is(':checked')){
		if(($("#card_number").val()=='') || ($("#card_number").val().length < 15 && $("#card_number").val() != '<?=$cardNum?>')){
			$("#card_number").siblings('.input-error').show();
			$("#error").html('Invalid credit card number');
			return false;
		}else{
		  	$("#card_number").siblings('.input-error').hide();
			$("#error").html('');
		}
		
		var ccm = $("#expiration_month").val();
		if(ccm==''|| ccm.length < 2 || ccm.length > 2 || ccm > 12 || ccm < 0){
			$("#expiration_month").siblings('.input-error').show();
			$("#error").html('Invalid expiration month, Please enter 2 digit value between 00 to 12.');
			return false;
		}else{
		  	$("#expiration_month").siblings('.input-error').hide();
			$("#error").html('');
		}
		
		var ccy = $("#expiration_year").val();
		if(ccy==''|| ccy.length < 2 || ccy.length > 2 || ccy < 15){
			$("#expiration_year").siblings('.input-error').show();
			$("#error").html('Invalid expiration year, Please enter 2 digit value greater than or equal to 15.');
			return false;
		}else{
		  	$("#expiration_year").siblings('.input-error').hide();
			$("#error").html('');
		}
		
	  }
	  $("#btnCheckout").val('1');
	  
	  if($('#tip').val()!='' && ($('#tip').val() < 0 || !isNumeric($('#tip').val())) && $("#delivery").is(':checked')){
		  $("#tip").siblings('.input-error').show();
		  $("#error").html('Please enter valid tip amount.');
		  $("#tip").focus();
		  return false;
	  }else{
		  $("#tip").siblings('.input-error').hide();
		  $("#error").html('');
		  add_delivery_tip($('#tip').val());
	  }
	  if(totalerror == 0) return true;
	  else{
		return false;
	  }
	});
	// Checkout Submit Form End
	
	$("#tip").blur(function(){
		if($('#tip').val()=='' || $('#tip').val() <= 0 || !isNumeric($('#tip').val())){
			$("#tip").css('border-color','#F00');
			$("#error").html('Please enter valid tip amount.');
			return false;
		}else{
			add_delivery_tip($(this).val());
		}
	});
	
	$("#coupon_code").keypress(function(e) {
	  if(e.which == 13) {
	   redeemCoupon();
	   e.preventDefault();
	  }
	});
	
	$('.checkout__coupon-button').click(function(){
		redeemCoupon();
	});
	
	$(".footer__stepper-next").click(function(){
		var regEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	  	var regName = /^[a-zA-Z ]{2,30}$/;
	    
		// Varify Pickup or Delivery Details
		if($.trim($(".footer__stepper-next").html())=='Payment'){
			if($('#pickup').is(':checked')){
				if($("#pickup_datetime").val()!=''){
				  $("#serving_date_time").val($("#pickup_datetime").val());
				}
				
				if(regName.test($("#pcustomer_name").val())==false){
					$("#pcustomer_name").siblings('.input-error').show();
					$("#error").html('Invalid First Name.');
					return false;
				}else{
					$("#pcustomer_name").siblings('.input-error').hide();
					$("#error").html('');
				}
				
				if(regName.test($("#pcustomer_last_name").val())==false){
					$("#pcustomer_last_name").siblings('.input-error').show();
					$("#error").html('Invalid Last Name.');
					return false;
				}else{
					$("#pcustomer_last_name").siblings('.input-error').hide();
					$("#error").html('');
				}
				
				if(regEmail.test($("#pcustomer_email").val())==false){
					$("#pcustomer_email").siblings('.input-error').show();
					$("#error").html('Invalid Email Address.');
					return false;
				}else{
					$("#pcustomer_email").siblings('.input-error').hide();
					$("#error").html('');
				}
				
				if($("#pcustomer_phone").val()==''){
					$("#pcustomer_phone").siblings('.input-error').show();
					$("#error").html('Pickup customer phone number is required');
					return false;
				}else{
					$("#pcustomer_phone").siblings('.input-error').hide();
					$("#error").html('');
				}
			  }
			else{
			  if($("#delivery_datetime").val()!=''){
				$("#serving_date_time").val($("#delivery_datetime").val());
			  }
			  
			  if(regName.test($("#dcustomer_name").val())==false){
				  $("#dcustomer_name").siblings('.input-error').show();
				  $("#error").html('Invalid First Name.');
				  return false;
			  }else{
				$("#dcustomer_name").siblings('.input-error').hide();
				$("#error").html('');
			  }
			  
			  if(regName.test($("#dcustomer_last_name").val())==false){
				  $("#dcustomer_last_name").siblings('.input-error').show();
				  $("#error").html('Invalid Last Name.');
				  return false;
			  }else{
				$("#dcustomer_last_name").siblings('.input-error').hide();
				$("#error").html('');
			  }
			  
			  if(regEmail.test($("#dcustomer_email").val())==false){
				  $("#dcustomer_email").siblings('.input-error').show();
				  $("#error").html('Customer email is required');
				  return false;
			  }else{
				  $("#dcustomer_email").siblings('.input-error').hide();
				  $("#error").html('');
			  }
			  
			  if($("#dcustomer_phone").val()==''){
				  $("#dcustomer_phone").siblings('.input-error').show();
				  $("#error").html('Customer phone number is required for Delivery');
				  return false;
			  }else{
				$("#dcustomer_phone").siblings('.input-error').hide();
				$("#error").html('');
			  }
			
			  if($("#customer_address").val()=='' || $("#customer_address").val().length < 3){
				  $("#customer_address").siblings('.input-error').show();
				  $("#error").html('Please enter a valid street address');
				  return false;
			  }else{
				$("#customer_address").siblings('.input-error').hide();
				$("#error").html('');
			  }
			  
			  if($("#customer_zip").val()=='' || $("#customer_zip").val().length < 2){
				  $("#customer_zip").siblings('.input-error').show();
				  $("#error").html('Please enter a valid zip code');
				  return false;
			  }else{
				$("#customer_zip").siblings('.input-error').hide();
				$("#error").html('');
			  }
			  
			  if($("#customer_city").val()=='' || $("#customer_city").val().length < 3){
				  $("#customer_city").siblings('.input-error').show();
				  $("#error").html('Please enter a valid city name');
				  return false;
			  }else{
				$("#customer_city").siblings('.input-error').hide();
				$("#error").html('');
			  }
			  
			  if($("#customer_state").val()=='' || $("#customer_state").val().length < 2){
				  $("#customer_state").siblings('.input-error').show();
				  $("#error").html('Please enter a valid state name');
				  return false;
			  }else{
				$("#customer_state").siblings('.input-error').hide();
				$("#error").html('');
			  }
			}
		}
		
		// Varify CC Info
		if($.trim($(".footer__stepper-next").html())=='Review order'){
			if($("#card_number").val() != '<?=$cardNum?>'){
				$("#card_token").val(0);
			}
			
			if($("#expiration_month").val() != '<?=$cardExpM?>'){
				$("#card_token").val(0);
			}
			
			if($("#expiration_year").val() != '<?=$cardExpY?>'){
				$("#card_token").val(0);
			}
			
			if($('#credit_card').is(':checked')){
			  if(($("#card_number").val()=='') || ($("#card_number").val().length < 15 && $("#card_number").val() != '<?=$cardNum?>')){
				  $("#card_number").siblings('.input-error').show();
				  $("#error").html('Invalid credit card number');
				  return false;
			  }else{
				  $("#card_number").siblings('.input-error').hide();
				  $("#error").html('');
			  }
			  
			  var ccm = $("#expiration_month").val();
			  if(ccm==''|| ccm.length < 2 || ccm.length > 2 || ccm > 12 || ccm < 0){
				  $("#expiration_month").siblings('.input-error').show();
				  $("#error").html('Invalid expiration month, Please enter 2 digit value between 00 to 12.');
				  return false;
			  }else{
				  $("#expiration_month").siblings('.input-error').hide();
				  $("#error").html('');
			  }
			  
			  var ccy = $("#expiration_year").val();
			  if(ccy==''|| ccy.length < 2 || ccy.length > 2 || ccy < 15){
				  $("#expiration_year").siblings('.input-error').show();
				  $("#error").html('Invalid expiration year, Please enter 2 digit value greater than or equal to 15.');
				  return false;
			  }else{
				  $("#expiration_year").siblings('.input-error').hide();
				  $("#error").html('');
			  }
			}
		}
		
		if($('#delivery').is(':checked') && totalerror==0){
			verifylocation();
		}
	});
  });
  
  function redeemCoupon(){
	  var coupon_code=$("#coupon_code").val();
	  var delivery_charges = $("#cart_delivery_charges").val();
	  $.post('?item=redeemcoupon&ajax=1<?=$groupOrderUrl?>',{coupon_code:coupon_code,delivery_charges:delivery_charges,action:"redeemcoupon"},function(data) {
		  $(".checkout__summary").html(data);
		  $("#gtotal").html('<?=$currency?>' + $("#cart_total").val());
	  });
	}
	
  function add_delivery_tip(tip) {
	  var delivery_charges = $("#cart_delivery_charges").val();
	  $.post('?item=addtip&ajax=1', {tip: tip,delivery_charges:delivery_charges, action: "updatetip"}, 
	  function(data) {
		  $(".checkout__summary").html(data);
		  $("#gtotal").html('<?=$currency?>' + $("#cart_total").val());
	  });
  }
  
  function updateDeliveryType(type){
	  $.post('?item=addtip<?=$groupOrderUrl?>&ajax=1', {type: type, action: "updateDeliverType"}, 
	  function(data) {
		  data= data.replace(/(\r\n|\n|\r)/gm,"");
		  if(data != deliveryType){
			location.hash = "";
			location.reload();
		  }
	  });
  }
  
  function getDeliveryType(){
	  return deliveryType;
  }
  
  function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
  }
</script> 
<script src="../js/checkdeliveryzones.js" type="text/javascript"></script> 
<script language="javascript">
var geocoder, location1, location2, radius_verified;
geocoder = new google.maps.Geocoder();
radius_verified = - 1;
var Zone1, Zone1_enabled;
var Zone2, Zone2_enabled;
var Zone3, Zone3_enabled;

window.verifylocation = function() {
	var restaurant_location = '<?= $objRestaurant->rest_address . ", " . $objRestaurant->rest_city . ", " . $objRestaurant->rest_state; ?>';
	var customer_location = $("#customer_address").val() + " , " + $("#customer_city").val() + " , " + $("#customer_state").val();
	var radius = '<?= $objRestaurant->delivery_radius ?>';
	
    geocoder.geocode({'address': restaurant_location}, function(results, status) {
		if (status != google.maps.GeocoderStatus.OK){
            //$("#googleerror").html('Sorry, we were unable to recognize the resturant address.');
			$("#out-of-delivery").children('.notification__box').children('.notification__box-content').children('p').html("Sorry, we were unable to recognize the resturant address.");
			window.location.hash = "#out-of-delivery", EasyWay.Notification.open()
			totalerror = totalerror + 1;
			return false;
        }
		else {
            var position = results[0].geometry.location;
            location1 = position;
            var restaurantlocation = new google.maps.LatLng(parseFloat(position.lat()), parseFloat(position.lng()));
			
			geocoder.geocode({'address': customer_location}, function(results, status) {                    
                if (status != google.maps.GeocoderStatus.OK){
					//$("#googleerror").html('Sorry, we were unable to recognize the customer address.');
					$("#out-of-delivery").children('.notification__box').children('.notification__box-content').children('p').html("Sorry, we were unable to recognize the customer address.");
					window.location.hash = "#out-of-delivery", EasyWay.Notification.open()
					totalerror = totalerror + 1;
                    return false;
                } 
				else {
                    var position = results[0].geometry.location;
                    location2 = position;
                    var customerlatlang = new google.maps.LatLng(parseFloat(position.lat()), parseFloat(position.lng()));
                    
					if ('<?= $objRestaurant->delivery_option ?>' == 'delivery_zones') {

                        Zone1_enabled = <?= $objRestaurant->zone1 ?> ;
                        Zone2_enabled = <?= $objRestaurant->zone2 ?> ;
                        Zone3_enabled = <?= $objRestaurant->zone3 ?> ;

                        drawzones(restaurantlocation);

                        if (Zone1_enabled && Zone1.containaddress(customerlatlang)) {
                            //submitme();
                        } else if (Zone2_enabled && Zone2.containaddress(customerlatlang)) {
                            showConfirmation(2, customer_location)
                        } else if (Zone3_enabled && Zone3.containaddress(customerlatlang)) {
                            showConfirmation(3, customer_location)
                        } else {
                            showConfirmation(4, customer_location);
                        }
                    } else {
						return calculateDistance(radius);
                    }
                }
            });
        }
    });
};

function showConfirmation(zone, location) {
	var cartSubTotal = <?= $cart->sub_total ?> ;
    var restMinTotal, charges;
    
    if (zone == 4) {
        var msgg = 'You are out side of our delivery zones';
        $("#googleerror").html("<strong>" + msgg + "</strong>");
		$("#out-of-delivery").children('.notification__box').children('.notification__box-content').children('p').html(msgg);
		window.location.hash = "#out-of-delivery", EasyWay.Notification.open()
		totalerror = totalerror + 1;
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
		totalerror = totalerror + 1;
    }else {
        msg = '"<a href="javascript:iagree(' + charges + ');">I Agree</a>"';
		totalerror = totalerror + 1;
    }
    
    msg = 'You are in extended delivery zone. <?=$java_currency?>' + charges + ' delivery charges will be charged. ' + msg;
    $("#googleerror").html("<strong>" + msg + "</strong>");
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

function calculateDistance(radius){
    try{
        var glatlng1 = new google.maps.LatLng(parseFloat(location1.lat()), parseFloat(location1.lng()));
        var glatlng2 = new google.maps.LatLng(parseFloat(location2.lat()), parseFloat(location2.lng()));
        var miledistance = parseFloat(glatlng1.distanceFrom(glatlng2, 3959).toFixed(1));
        var kmdistance = (miledistance * 1.609344).toFixed(1);
        radius = parseFloat(radius);
        if (radius < miledistance) {
			var msg = '<strong>Sorry! We only deliver within ' + radius + ' miles radius. You are ' + miledistance + ' miles away from the resturant.</strong>'
			
			$("#googleerror").html(msg);
			$("#out-of-delivery").children('.notification__box').children('.notification__box-content').children('p').html(msg);
			window.location.hash = "#out-of-delivery", EasyWay.Notification.open()
			totalerror = totalerror + 1;
			return false;
        } else {
            return true;
        }

    }    
    catch (error)
    {
        $("#googleerror").html(error);
		totalerror = totalerror + 1;
        return false;
    }
}

function iagree(charges) {
	totalerror = totalerror - 1;
    $("#cart_delivery_charges").val(charges);
	$("#deliverchrgs").html('<?=$currency?>'+ charges);
	$("#gtotal").html('<?=$currency?>'+ (Number($("#cart_total").val()) + Number(charges)));
}
</script>

<?php
//echo "<pre>"; print_r($loggedinuser); echo "</pre>";
?>
