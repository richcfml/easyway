<script>
var addressEdit = 0;
$(document).ready(function(){
  /*	Update Account Information */
  var custObj;
  $("#saveAccountInfo").click(function(event){
	var regEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	var regName = /^[a-zA-Z ]{2,30}$/;
	
	var first_name = $("#info_first_name").val();
	var last_name = $("#info_last_name").val();
	var email = $("#info_email").val();
	
	if(regName.test(first_name)==false){
		$("#info_first_name").css('border','1px solid red');
		return false;
	}else {
		$("#info_first_name").css('border','1px solid #6b8f9a')
	}

	if(regName.test(last_name)==false){
		$("#info_last_name").css('border','1px solid red');
		return false;
	}else {
		$("#info_last_name").css('border','1px solid #6b8f9a')
	}

	if(regEmail.test(email)==false){
		$("#info_email").css('border','1px solid red');
		return false;
	}else {
		$("#info_email").css('border','1px solid #6b8f9a')
	}
	event.preventDefault();
	$.ajax({
		type:"POST",
		url: "?item=accountajax&ajax=1&saveInfo=1",
		data:$("#accountInfoForm").serialize(),
		success: function(data) {
		  if(data == 1){
			  $(".person__name").html(first_name+' '+ last_name)
			  $(".person__email").html(email)
		  }
		}
	});
  });
  
  $(".rbDefault").click(function()
  {   
	  var mTokenID = $(this).attr("tokenid");
	  $.post("?mod=accountajax&item=accountajax&call=setdefault&ajax=1",{token:mTokenID, id:<?=$loggedinuser->id?>},function(data)
	  {
		  $("#spnError").html(data.message);
	  },  "json")
  });
        
  /*	Change Password Code	*/
  $("#btnChangepassword").click(function(event){
	var previous_password = $("#previous_password").val();
	var new_password = $("#new_password").val();
	var password_confirmation = $("#password_confirmation").val();
	
	event.preventDefault();
	$.ajax({
		type:"POST",
		url: "?item=accountajax&ajax=1&changepassword=1",
		data:$("#changepasswordForm").serialize(),
		success: function(data) {
		  switch(data){
			case '-1':
				$(".notification__box-title").html('Sorry!');
				$(".notification__box-content").html('Previous password, New password and Password confirmation fields are required to update password');
				break;
			
			case '-2':
				$(".notification__box-title").html('Sorry!');
				$(".notification__box-content").html('New password and Password confirmation fields do not matched.');
				break;
			
			case '-3':
				$(".notification__box-title").html('Sorry!');
				$(".notification__box-content").html('Previous password is invalid');
				break;
		  }
		}
	});
  });
  
  /*	change default address	*/
  $("input[name='default_address']").change(function(){
  	var defaultAddress = $(this).val();
	event.preventDefault();
	$.ajax({
		type:"POST",
		url: "?item=accountajax&ajax=1&changeDefaultAddress=1",
		data:{ defaultAddress:defaultAddress },
		dataType: 'json',
		success: function(data) {
			custObj = data;
		}
	});
  });
  
  
  /*	Add New Address	*/
  $("#newerAddress").click(function(){
	$("#updateAddress").val(0);
		
        
        $("#street_address").val('');
        
        $("#street_address").val('');
        
        $("#city").val('');

        
        $("#state").val('');

        
        $("#zip").val('');

        
        $("#phone_number").val('');
  });
  
});
</script>
    
<?php

if (!is_numeric($loggedinuser->id)) {
    redirect($SiteUrl . $objRestaurant->url."/#login");
    exit;
}
?>

<body class=account>
<?php require($mobile_root_path . "includes/header.php"); ?>
<main class=main>
  <div class=main__container>
    <div class=account__wrapper>
      <section class=section id=account>
        <header class=section__header>
          <h2 class=section__title>Your Account</h2>
        </header>
        <div class=section__article>
          <header class=section__article-header>
            <div class=section__article-title><h3>Delivery Address:</h3></div>
            <a data-step=delivery_addresses class="section__article-action  js-goto" href="#">Change</a> </header>
            <div class=section__article-content>
                        <div class=contact itemscope='' itemtype='http://schema.org/ContactPoint'>
                           <p class=contact__address itemscope='' itemtype='http://schema.org/PostalAddress'> 
                <span class="contact__address-street street1" itemprop=streetAddress><?=str_replace('~', ' ', $loggedinuser->cust_odr_address)?></span> 
                <span class="contact__address-locality city1" itemprop=addressLocality><?=$loggedinuser->cust_ord_city?></span> 
                <abbr class="contact__address-region state1" itemprop=addressRegion title='New York'><?=$loggedinuser->cust_ord_state?></abbr> 
                <span class="contact__address-zip zip1" itemprop=postalCode><?=$loggedinuser->cust_ord_zip?></span> 
			   </p>
              <span class="contact__phone phone1" content=1317508344 itemprop=telephone><?=$loggedinuser->cust_phone1?></span> 
                        </div>
            </div>
          <br/>
            <div class=section__article-content>
                        <div class=contact itemscope='' itemtype='http://schema.org/ContactPoint'>
                           <p class=contact__address itemscope='' itemtype='http://schema.org/PostalAddress'> 
                <span class="contact__address-street street2" itemprop=streetAddress><?=str_replace('~', ' ', $loggedinuser->delivery_address1)?></span> 
                <span class="contact__address-locality city2" itemprop=addressLocality><?=$loggedinuser->delivery_city1?></span> 
                <abbr class="contact__address-region state2" itemprop=addressRegion title='New York'><?=$loggedinuser->delivery_state1?></abbr> 
                <span class="contact__address-zip zip2" itemprop=postalCode><?=$loggedinuser->deivery1_zip?></span> 
              </p>
              <span class="contact__phone phone2" content=1317508344 itemprop=telephone><?=$loggedinuser->cust_phone1?></span> </div>
            </div>

        </div>
        
        <div class=section__article>
          <header class=section__article-header>
            <div class=section__article-title>
               <h3>Payment Method:</h3>
            </div>
            <a data-step=payment_methods class="section__article-action edit js-goto" href="#">Change</a> 
          </header>
            <?php
			foreach ($loggedinuser->arrTokens as $token) {
				$card_type = '';
				$card_num = '';
				$card_ed = '';
				$card_ccv = '';
				if ($token->data_type == AMEX)
					$card_type = "American Express";
				else if ($token->data_type == VISA)
					$card_type = "VISA";
				else if ($token->data_type == MASTER)
					$card_type = "MasterCard";
				else if ($token->data_type == DISCOVER)
					$card_type = "Discover";
				?>
		<div class=section__article-content>
                      <p class=cc> 
                          <span class=cc__type><?=$card_type?></span> <br> 
                          <span class=cc__name><?= $token->card_name?></span> <br> 
                          <span class=cc__last-four>**** **** **** <?=$token->data_1?></span> 
                      </p>
                   </div>		
                
			<?php } ?>
        </div>
        
      
        <div class=section__article>
          <header class=section__article-header>
            <div class=section__article-title>
                <h3>Account Information:</h3>
             </div>
            <a data-step=change_information class="section__article-action edit js-goto" href="#">Change</a>
            </header>
            <div class=section__article-content>
                   <p class=person itemscope='' itemtype='http://schema.org/Person'> 
                       <span class=person__name itemprop=name><?=$loggedinuser->cust_your_name.' '.$loggedinuser->LastName?></span> 
                       <span class=person__email itemprop=email><?=$loggedinuser->cust_email?></span> 
                   </p>
            </div>
        </div>
      </section>
      
<!--      <section class=section id=delivery_addresses>
                  <header class=section__header>
                     <h2 class=section__title>Delivery Addresses</h2>
                     <a data-step=account class="section__action back js-goto" href="#">Back</a> 
                  </header>
                  <div class=section__article>
                     <header class=section__article-header>
                        <div class=section__article-title> <label> <input type=radio name=default_address checked/> Default </label> </div>
                        <i class='section__article-action address-edit'></i> 
                     </header>
                     <div class=section__article-content>
                        <div class=contact itemscope='' itemtype='http://schema.org/ContactPoint'>
                           <p class=contact__address itemscope='' itemtype='http://schema.org/PostalAddress'> <span class=contact__address-street itemprop=streetAddress>3742 Groove Street</span> <span class=contact__address-locality itemprop=addressLocality>Nethpage</span> <abbr class=contact__address-region itemprop=addressRegion title='New York'>NY</abbr> <span class=contact__address-zip itemprop=postalCode>94043</span> </p>
                           <span class=contact__phone content=1317508344 itemprop=telephone>631-750-8344</span> 
                        </div>
                     </div>
                  </div>
                  <div class=section__article>
                     <div class=section__article-content>
                        <p class=account__empty-message>You haven't added any address</p>
                     </div>
                  </div>
                  <footer class=section__footer> <input type=button value="Add New Address" data-step=new_delivery_address class="full-width js-goto"/> </footer>
        </section>-->
      <section class=section id=delivery_addresses>
        <header class=section__header>
          <h2 class=section__title>Delivery Addresses</h2>
          <a data-step=account class="section__action back js-goto" href="#">Back</a> 
        </header>
        <?php if(trim($loggedinuser->cust_odr_address,'~')!='') :?>
        <div class=section__article>  
          <header class=section__article-header>
            <div class=section__article-title>
              <label><input type="radio" name="default_address" class="default_address" value="1" checked/>Default</label>
            </div>
            <i class='section__article-action fa fa-pencil js-goto editAddress edit' address=1 data-step=new_delivery_address></i> 
          </header>
          <div class=section__article-content>
            <div class=contact itemscope='' itemtype='http://schema.org/ContactPoint' id="deliveryAddress1">
              <p class=contact__address itemscope='' itemtype='http://schema.org/PostalAddress'> 
                <span id="street1" class="contact__address-street street1" itemprop=streetAddress><?= str_replace('~', ' ', $loggedinuser->cust_odr_address)?></span> 
                <span id="city1" class="contact__address-locality city1" itemprop=addressLocality><?=$loggedinuser->cust_ord_city ?></span> 
                <abbr id="state1" class="contact__address-region state1" itemprop=addressRegion title='New York'><?=$loggedinuser->cust_ord_state ?></abbr> 
                <span id="zip1" class="contact__address-zip zip1" itemprop=postalCode><?= $loggedinuser->cust_ord_zip?></span> 
              </p>
              <span id="phone1" class="contact__phone phone1" content=1317508344 itemprop=telephone><?= $loggedinuser->cust_phone1?></span>
            </div>
          </div>
        </div>
         <?php endif;?>
        <?php if(trim( $loggedinuser->delivery_address1, '~') !='') : ?>  
        <div class=section__article>  
          <header class=section__article-header>
            <div class=section__article-title>
              <label><input type="radio" name="default_address" class="default_address" value="2"/>Default</label>
            </div>
            <i class='section__article-action fa fa-pencil js-goto editAddress edit' address=2 data-step=new_delivery_address></i> 
          </header>
          <div class=section__article-content>
            <div class=contact itemscope='' itemtype='http://schema.org/ContactPoint' id="deliveryAddress2">
              <p class=contact__address itemscope='' itemtype='http://schema.org/PostalAddress'> 
                <span id="street2" class="contact__address-street street2" itemprop=streetAddress><?=str_replace('~', ' ', $loggedinuser->delivery_address1)?></span> 
                <span id="city2" class="contact__address-locality city2" itemprop=addressLocality><?=$loggedinuser->delivery_city1?></span> 
                <abbr id="state2" class="contact__address-region state2" itemprop=addressRegion title='New York'><?=$loggedinuser->delivery_state1?></abbr> 
                <span id="zip2" class="contact__address-zip zip2" itemprop=postalCode><?=$loggedinuser->deivery1_zip?></span> 
              </p>
              <span id="phone2" class="contact__phone phone2" content=1317508344 itemprop=telephone><?= $loggedinuser->cust_phone2?></span>
            </div>
          </div>
        </div>
        <?php endif;?>
        <?php
        if(trim($loggedinuser->delivery_address1, '~')=='' && trim($loggedinuser->cust_odr_address,'~')==''){
		?>
        <div class='section__article <?=(($loggedinuser->cust_odr_address == '')? 'account__hide':'')?>'>
          <div class=section__article-content>
            <p class=account__empty-message>You haven't added any address</p>
          </div>
        </div>
        <?php 
		} 
		
		if(trim($loggedinuser->delivery_address1, '~')=='' || trim($loggedinuser->cust_odr_address,'~')==''){
		?>
          <footer class="section__footer"> <input id='newerAddress' type="button" value="Add New Address" data-step="new_delivery_address" class="full-width account__save js-goto"> </footer>
        
        <?php } ?>
      </section>
      
      <section class=section id=new_delivery_address>
        <header class=section__header>
          <h2 class=section__title>New Delivery Address</h2>
          <a data-step=delivery_addresses class="section__action js-goto" id="btnBack" href="#">Back</a> </header>
        <div class=section__article>
          <div class=section__article-content>
            <form method="post" action="" id="addNewAddressForm">
              <span class="redtxt" id="addressError"></span>  
              <input type="hidden" name="updateAddress" id="updateAddress" value="0" />
              <fieldset>
                <label for="street_address">Street address: </label>
                <input name="street" id="street_address" autocomplete="street-address" />
                <label for="apt">Apt: </label>
                <input name="apt" id="apt" />
                <label for="zip">Zip: </label>
                <input name="zip" id="zip" autocomplete="postal-code" />
                <label for="city">City: </label>
                <input name="city" id="city" autocomplete="address-level2" />
                <label for="state">State: </label>
                <input name="state" id="state" autocomplete="address-level1" />
                <label for="phone_number">Phone number: </label>
                <input type="tel" name="phone" id="phone_number" autocomplete="tel" />
              </fieldset>
            </form>
          </div>
        </div>
        <footer class="section__footer"> <input type="button" id="btnAddNewAddress" value="Save Address" data-step="delivery_addresses" class="full-width account__save js-save-address"> </footer>  
        
         </section>
     
      <section class=section id=payment_methods>
        <header class=section__header>
          <h2 class=section__title>Payment Methods</h2>
          <a data-step=account class="section__action back js-goto" href="#">Back</a> 
        </header>
          <?php foreach ($loggedinuser->arrTokens as $token) {//echo "<pre>";print_r($token);
				$card_type = '';
				$card_num = '';
				$card_ed = '';
				$card_ccv = '';
				if ($token->data_type == AMEX)
					$card_type = "American Express";
				else if ($token->data_type == VISA)
					$card_type = "VISA";
				else if ($token->data_type == MASTER)
					$card_type = "MasterCard";
				else if ($token->data_type == DISCOVER)
					$card_type = "Discover";
				?>
        <div class=section__article>
          <header class=section__article-header>
            <div class=section__article-title>
              <label>
                <input type=radio name=default_payment_method class="section__article-title rbDefault" tokenid="<?= $token->id  ?>" <?php if($token->data_3=="1") echo "checked";?>/>
                Default </label>
            </div>
            <i class='section__article-action address-edit'></i>
          </header>
          <div class=section__article-content>
            <p class=cc> <span class=cc__type><?= $card_type ?></span> <br> <span class=cc__name><?= $token->card_name?></span> <br> <span class=cc__last-four>**** **** **** <?=$token->data_1?></span> </p>
          </div>
        </div>
          <?php } ?>
          <?php if(empty($loggedinuser->arrTokens)){ ?>
        <div class='section__article account__hide'>
          <div class=section__article-content>
            <p class=account__empty-message>Yoy haven't added any payment method</p>
          </div>
        </div>
          <?php } ?>
        <footer class=section__footer> <input type=button value="Add New Payment Method" data-step=new_payment_method class="full-width js-goto"/> </footer>
      </section>
      
      <section class=section id=new_payment_method>
          <form method="post" id="saveCardForm">
        <header class=section__header>
               <h2 class=section__title>New Payment Method</h2>
               <a data-step=payment_methods class="section__action back js-goto" href="#">Back</a> 
            </header>
        <div class=section__article>
          <div class=section__article-content>
              <fieldset>
                <div class=checkout-card>
                  <div class=checkout-card__stripe></div>
                  <div class=checkout-card__content>
                    <div class=checkout-card__name>
                      <label for=card_name>Card name: </label>
                      <input name=card_name id=card_name placeholder="Card Name" autocomplete=cc-name maxlength="25"/>
                    </div>
                    <div class=checkout-card__number>
                      <label for=card_number>Card number: </label>
                      <input name=card_number id=card_number placeholder="Card Number" autocomplete=cc-number maxlength="16"/>
                    </div>
                    <div class=checkout-card__exp-month>
                      <label for=expiration_month>Expiration month: </label>
                      <input name=expiration_month id=expiration_month placeholder=MM autocomplete=cc-exp-month maxlength="2"/>
                    </div>
                    <div class=checkout-card__exp-year>
                      <label for=expiration_year>Expiration year: </label>
                      <input name=expiration_year id=expiration_year placeholder=YY autocomplete=cc-exp-year maxlength="2"/>
                    </div>
                    <div class=checkout-card__cvv>
                      <label for=cvv>Cvv: </label>
                      <input name=cvv id=cvv placeholder=CVV autocomplete=cc-csc />
                    </div>
                  </div>
                  <div id="tokenmessage"></div>
                </div>
              </fieldset>
            
          </div>
        </div>
        <footer class=section__footer> <input type=button value="Save Payment Method" id="saveCreditCard" data-step=payment_methods class="full-width js-save-payment-method"/> </footer>
        </form>
      </section>
      
      <section class=section id=change_information>
            <header class=section__header>
               <h2 class=section__title>Account Information</h2>
               <a data-step=account class="section__action back js-goto" href="#">Back</a> 
            </header>
            <div class=section__article>
               <div class=section__article-content>
                  <form method="post" id="accountInfoForm">
                     <fieldset>
                        <label for="first_name">First name: </label> 
                        <input name="info_first_name" id="info_first_name" value="<?= $loggedinuser->cust_your_name?>" autocomplete="given-name" /> 
                        
                        <label for="info_last_name">Last name: </label> 
                        <input name="info_last_name" id="info_last_name" value="<?= $loggedinuser->LastName ?>" autocomplete="family-name" /> 
                        
                        <label for="email">Email: </label> 
                        <div class=input> <input type="email" name="info_email" id="info_email" value="<?=$loggedinuser->cust_email?>" autocomplete="email" />
                            <span class=input-error>Error</span> </div>
                     </fieldset>
                  </form>
               </div>
            </div>
            <footer class=section__footer> <input type=button value="Save Changes" id="saveAccountInfo" class="full-width js-goto" data-step=account /> <a class="account__change-password js-goto" data-step=change_password href="#">Change password?</a> </footer>
        </section>  
       
      <section class=section id=change_password>
        <header class=section__header>
          <h2 class=section__title>Change Password</h2>
          <a data-step=change_information class="section__action back js-goto" href="#">Back</a>
        </header>
        <div class=section__article>
          <div class=section__article-content>
            <form method="post" id="changepasswordForm">
              <fieldset>
                <label for="previous_password">Previous password: </label>
                <input type="password" name="previous_password" id="previous_password" />
                <label for="new_password">New password: </label>
                <input type="password" name="new_password" id="new_password" />
                <label for="password_confirmation">Password confirmation: </label>
                <input type="password" name="password_confirmation" id="password_confirmation" />
              </fieldset>
            </form>
          </div>
        </div>
          <footer class=section__footer> <input type=button id="btnChangepassword" value=Change class="full-width js-update-password js-goto" data-step=account /> </footer>
      </section>
    </div>
  </div>
</main>

<!--	Password change success -->
<div class=notification id=password-change>
    <div class='notification__box center-text'>
        <header class=notification__box-header> <a class=notification__box-action href="#">X</a>
            <h3 class=notification__box-title>Success!</h3>
        </header>
        <div class=notification__box-content>
            <p>Your password has been changed successfuly.</p>
        </div>
    </div>
</div>

<script>
  $(document).ready(function() {
    EasyWay.Account.setup();
	
	$("#btnBack").click(function(){
		$("#updateAddress").val(0);
		addressEdit=0;
	});
	
	$(".editAddress").click(function(){
		var defaultAdrs = $("input[name=default_address]:checked").val();
                $('#addressError').text(""); 
		addressEdit = parseInt($(this).attr('address'));
		var update = ((defaultAdrs==1 && addressEdit==1) || (defaultAdrs==2 && addressEdit==2))? 1:2;
		
		$("#updateAddress").val(update);
		
		var street_address = (addressEdit==1)? $("#street1").html() : $("#street2").html();
		$("#street_address").val(street_address);
		
		var city = (addressEdit==1)? $("#city1").html() : $("#city2").html();
		$("#city").val(city);
		
		var state = (addressEdit==1)? $("#state1").html() : $("#state2").html();
		$("#state").val(state);
		
		var zip = (addressEdit==1)? $("#zip1").html() : $("#zip2").html();
		$("#zip").val(zip);
		
		var phone = (addressEdit==1)? $("#phone1").html() : $("#phone2").html();
		$("#phone_number").val(phone);
	});
  });
</script>
<style type="text/css">
    #tokenmessage{
        width: 80%;
        padding: 9px;
        margin-top: 7px;
    }    
    .success{
        color:green;
    }
    .error{
        color:red;
    }
</style>