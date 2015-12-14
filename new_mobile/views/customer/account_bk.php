<?php

echo "<pre>"; print_r($_POST); echo "</pre>";

if (!is_numeric($loggedinuser->id)) {
    redirect($SiteUrl . $objRestaurant->url . "/?item=login");
    exit;
}
if (isset($_POST['btnsave'])) 
{
    extract($_POST);
    
    $mSalt = $loggedinuser->salt;    
    $ePassword = hash('sha256', $password.$mSalt);
    
    $loggedinuser->cust_email = trim($email) == '' ? $loggedinuser->cust_email : $email;
    $loggedinuser->epassword = trim($password) == '' ? $loggedinuser->epassword : $ePassword;
    $loggedinuser->cust_your_name = trim($first_name) == '' ? $loggedinuser->cust_your_name : $first_name;
    $loggedinuser->LastName = trim($last_name) == '' ? $loggedinuser->LastName : $last_name;
    $loggedinuser->street1 = trim($address1) == '' ? $loggedinuser->street1 : $address1;
    $loggedinuser->street2 = trim($address2) == '' ? $loggedinuser->street2 : $address2;
    $loggedinuser->cust_ord_city = trim($city) == '' ? $loggedinuser->cust_ord_city : $city;
    $loggedinuser->cust_ord_state = trim($state) == '' ? $loggedinuser->cust_ord_state : $state;
    $loggedinuser->cust_ord_zip = trim($zip) == '' ? $loggedinuser->cust_ord_zip : $zip;
    $loggedinuser->cust_phone1 = trim($phone1) == '' ? $loggedinuser->cust_phone1 : $phone1;
    $loggedinuser->delivery_street1 = trim($saddress1) == '' ? $loggedinuser->delivery_street1 : $saddress1;
    $loggedinuser->delivery_street2 = trim($saddress2) == '' ? $loggedinuser->delivery_street2 : $saddress2;
    $loggedinuser->delivery_city1 = trim($scity) == '' ? $loggedinuser->delivery_city1 : $scity;
    $loggedinuser->delivery_state1 = trim($cstate) == '' ? $loggedinuser->delivery_state1 : $cstate;
    $loggedinuser->deivery1_zip = trim($czip) == '' ? $loggedinuser->deivery1_zip : $czip;
    $loggedinuser->update();
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
            <h3 class=section__article-title>Delivery Address:</h3>
            <a data-step=delivery_addresses class="section__article-action js-goto" href="#">Change</a> </header>
          <div class=section__article-content>
            <div class=contact itemscope='' itemtype='http://schema.org/ContactPoint'>
              <p class=contact__address itemscope='' itemtype='http://schema.org/PostalAddress'> 
                <span class=contact__address-street itemprop=streetAddress><?=str_replace('~', ' ', $loggedinuser->delivery_address1)?></span> 
                <span class=contact__address-locality itemprop=addressLocality><?=$loggedinuser->delivery_city1?></span> 
                <abbr class=contact__address-region itemprop=addressRegion title='New York'><?=$loggedinuser->delivery_state1?></abbr> 
                <span class=contact__address-zip itemprop=postalCode><?=$loggedinuser->deivery1_zip?></span> 
              </p>
              <span class=contact__phone content=1317508344 itemprop=telephone><?=$loggedinuser->cust_phone1?></span> </div>
          </div>
        </div>
        
        <div class=section__article>
          <header class=section__article-header>
            <h3 class=section__article-title>Payment Method:</h3>
            <a data-step=payment_methods class="section__article-action js-goto" href="#">Change</a> </header>
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
                  <p class=credit-card> 
                    <span class=credit-card__type><?=$card_type?></span> 
                    <span class=credit-card__name>Jack Nicholson</span> 
                    <span class=credit-card__last-four>**** **** **** <?=$token->data_1?></span> 
                  </p>
                </div>
			<? } ?>
        </div>
        
        <div class=section__article>
          <header class=section__article-header>
            <h3 class=section__article-title>Account Information:</h3>
            <a data-step=change_information class="section__article-action js-goto" href="#">Change</a> </header>
          <div class=section__article-content>
            <p class=person itemscope='' itemtype='http://schema.org/Person'> 
              <span class=person__name itemprop=name><?=$loggedinuser->cust_your_name.' '.$loggedinuser->LastName?></span> 
              <span class=person__email itemprop=email><?=$loggedinuser->cust_email?></span> 
            </p>
          </div>
        </div>
      </section>
      
      
      <section class=section id=delivery_addresses>
        <header class=section__header>
          <h2 class=section__title>Delivery Addresses</h2>
          <a data-step=account class="section__action js-goto" href="#">Back</a> </header>
        <div class=section__article>
          <header class=section__article-header>
            <div class=section__article-title>
              <label>
                <input type=radio name=default_address checked/>
                Default </label>
            </div>
            <i class='section__article-action address-edit'></i> </header>
          <div class=section__article-content>
            <div class=contact itemscope='' itemtype='http://schema.org/ContactPoint'>
              <p class=contact__address itemscope='' itemtype='http://schema.org/PostalAddress'> <span class=contact__address-street itemprop=streetAddress>3742 Groove Street</span> <span class=contact__address-locality itemprop=addressLocality>Nethpage</span> <abbr class=contact__address-region itemprop=addressRegion title='New York'>NY</abbr> <span class=contact__address-zip itemprop=postalCode>94043</span> </p>
              <span class=contact__phone content=1317508344 itemprop=telephone>631-750-8344</span> </div>
          </div>
        </div>
        <div class='section__article account__hide'>
          <div class=section__article-content>
            <p class=account__empty-message>You haven't added any address</p>
          </div>
        </div>
        <input type=button value="Add New Address" data-step=new_delivery_address class="account__save js-goto"/>
      </section>
      
      <section class=section id=new_delivery_address>
        <header class=section__header>
          <h2 class=section__title>New Delivery Address</h2>
          <a data-step=delivery_addresses class="section__action js-goto" href="#">Back</a> </header>
        <div class=section__article>
          <div class=section__article-content>
            <form>
              <fieldset>
                <label for=street_address>Street address: </label>
                <input name=street id=street_address autocomplete=street-address />
                <label for=apt>Apt: </label>
                <input name=apt id=apt />
                <label for=zip>Zip: </label>
                <input name=zip id=zip autocomplete=postal-code />
                <label for=city>City: </label>
                <input name=city id=city autocomplete=address-level2 />
                <label for=state>State: </label>
                <input name=state id=state autocomplete=address-level1 />
                <label for=phone_number>Phone number: </label>
                <input type=tel name=phone id=phone_number autocomplete=tel />
              </fieldset>
            </form>
          </div>
        </div>
        <input type=button value="Save Address" data-step=delivery_addresses class="account__save js-save-address"/>
      </section>
      
      <section class=section id=payment_methods>
        <header class=section__header>
          <h2 class=section__title>Payment Methods</h2>
          <a data-step=account class="section__action js-goto" href="#">Back</a> </header>
        <div class=section__article>
          <header class=section__article-header>
            <div class=section__article-title>
              <label>
                <input type=radio name=default_payment_method checked class=section__article-title />
                Default </label>
            </div>
            <i class='section__article-action address-edit'></i> </header>
          <div class=section__article-content>
            <p class=credit-card> <span class=credit-card__type>VISA</span> <span class=credit-card__name>Jack Nicholson</span> <span class=credit-card__last-four>**** **** **** 5654</span> </p>
          </div>
        </div>
        <div class='section__article account__hide'>
          <div class=section__article-content>
            <p class=account__empty-message>Yoy haven't added any payment method</p>
          </div>
        </div>
        <input type=button value="Add New Payment Method" data-step=new_payment_method class="account__save js-goto"/>
      </section>
      
      <section class=section id=new_payment_method>
        <header class=section__header>
          <h2 class=section__title>New Payment Method</h2>
          <a data-step=payment_methods class="section__action js-goto" href="#">Back</a> </header>
        <div class=section__article>
          <div class=section__article-content>
            <form>
              <fieldset>
                <div class=checkout-card>
                  <div class=checkout-card__stripe></div>
                  <div class=checkout-card__content>
                    <div class=checkout-card__name>
                      <label for=card_name>Card name: </label>
                      <input name=cc_name id=card_name placeholder="Card Name" autocomplete=cc-name />
                    </div>
                    <div class=checkout-card__number>
                      <label for=card_number>Card number: </label>
                      <input name=cc_number id=card_number placeholder="Card Number" autocomplete=cc-number />
                    </div>
                    <div class=checkout-card__exp-month>
                      <label for=expiration_month>Expiration month: </label>
                      <input name=cc_exp_month id=expiration_month placeholder=MM autocomplete=cc-exp-month />
                    </div>
                    <div class=checkout-card__exp-year>
                      <label for=expiration_year>Expiration year: </label>
                      <input name=cc_exp_year id=expiration_year placeholder=YY autocomplete=cc-exp-year />
                    </div>
                    <div class=checkout-card__cvv>
                      <label for=cvv>Cvv: </label>
                      <input name=cvv id=cvv placeholder=CVV autocomplete=cc-csc />
                    </div>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
        <input type=button value="Save Payment Method" data-step=payment_methods class="account__save js-save-payment-method"/>
      </section>
      
      <section class=section id=change_information>
        <header class=section__header>
          <h2 class=section__title>Account Information</h2>
          <a data-step=account class="section__action js-goto" href="#">Back</a> </header>
        <div class=section__article>
          <div class=section__article-content>
            <form>
              <fieldset>
                <label for=first_name>First name: </label>
                <input name=first_name id=first_name value=Jack autocomplete=given-name />
                <label for=last_name>Last name: </label>
                <input name=last_name id=last_name value=Nicholson autocomplete=family-name />
                <label for=email>Email: </label>
                <input type=email name=email id=email value="jack.nicholson@gmail.com" autocomplete=email />
              </fieldset>
            </form>
          </div>
        </div>
        <input type=button value="Save Changes" class="account__save js-goto" data-step=account />
        <a class=js-goto data-step=change_password href="#">Change password?</a> </section>
      
      <!--	Change Password	-->
      <section class=section id=change_password>
        <header class=section__header>
          <h2 class=section__title>Change Password</h2>
          <a data-step=change_information class="section__action js-goto" href="#">Back</a> 
        </header>
        <div class=section__article>
          <div class=section__article-content>
            <form action="" method="post" id="changepasswordfrm">
              <fieldset>
                <label for="previous_password">Previous password: </label>
                <input type="password" name="previous_password" id="previous_password" value="123qwe123" />
                
                <label for="new_password">New password: </label>
                <input type="password" name="new_password" id="new_password" value="qwe123qwe" />
                
                <label for="password_confirmation">Password confirmation: </label>
                <input type="password" name="password_confirmation" id="password_confirmation" value="qwe123qwe" />
              </fieldset>
            </form>
          </div>
        </div>
        <input type="submit" name="btnChangepassword" id="btnChangepassword" value="Change" class="account__save js-update-password js-goto" data-step="account" />
      </section>
    </div>
  </div>
</main>

<script>
  $(document).ready(function() {
    EasyWay.Account.setup();
	
	$("#btnChangepassword").click(function(){
		$("#changepasswordfrm").submit();
	});
  });
</script>
