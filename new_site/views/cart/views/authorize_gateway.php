<?php
 
			///////////////////// **********************/////////////////////////
		 	/////////////////////Authorize.net payment system////////////////////
		 	///////////////////////**********************//////////////////////////
		 	// This section generates the "Submit Payment" button using PHP           -->
			// This sample code requires the mhash library for PHP versions older than
			// 5.1.2 - http://hmhash.sourceforge.net/
			// the parameters for the payment can be configured here
			// the API Login ID and Transaction Key must be replaced with valid values
			$loginID		= $objRestaurant->authoriseLoginID; 
			$transactionKey = $objRestaurant->transKey;
			
	 
			$description = "Customer Order";
			//$description 	= "Sample Transaction";
			$label 			= "Submit Payment"; // The is the label on the 'submit' button
			$testMode		= "false";
			 
			
			$url			= "https://secure.authorize.net/gateway/transact.dll";
	 	    $amount=$cart->grand_total();
			
			if (@$_POST["special_notes"])
				{ $description = $_POST["special_notes"]; }
			
			// an invoice is generated using the date and time
			$invoice	= date('YmdHis');
			// a sequence number is randomly generated
			$sequence	= rand(1, 1000);
			// a timestamp is generated
			$timeStamp	= time ();
			
			// The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
			// newer have the necessary hmac function built in.  For older versions, it
			// will try to use the mhash library.
			if( phpversion() >= '5.1.2' )
			{	$fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey); }
			else 
			{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey)); }
	
	 
		 
		
        ?>

<FORM method='post' action='<?=$url?>' name='payment_form' id="payment_form" >
  <INPUT type='hidden' name='x_login' value='<?= $loginID?>' />
  <INPUT type='hidden' name='x_gateway_payment' value='1' />
  <INPUT type='hidden' name='x_amount' value='<?= $amount?>' />
  <INPUT type='hidden' name='x_description' value='<?= $description?>' />
   <INPUT type='hidden' name='x_test_request' value='true' />
  <INPUT TYPE="HIDDEN" NAME='x_first_name' VALUE='<?= $loggedinuser->	cust_your_name?>'>
  <INPUT TYPE="HIDDEN" NAME='x_last_name' VALUE='<?= 	$loggedinuser->	LastName?>'>
  <INPUT TYPE="HIDDEN" NAME='x_address' VALUE='<?= 	$loggedinuser->	street1 ." ". $loggedinuser->	street2?>'>
  <INPUT TYPE="HIDDEN" NAME='x_city' VALUE='<?= 	$loggedinuser->	cust_ord_city?>'>
  <INPUT TYPE="HIDDEN" NAME='x_state' VALUE='<?= 	$loggedinuser->	cust_ord_state?>'>
  <INPUT TYPE="HIDDEN" NAME='x_zip' VALUE='<?= 	$loggedinuser->	cust_ord_zip?>'>
  <INPUT TYPE="HIDDEN" NAME='x_country' VALUE='USA'>
  <INPUT TYPE="HIDDEN" NAME='x_email' VALUE='<?= 	$loggedinuser->	cust_email?>'>
  <INPUT TYPE="HIDDEN" NAME='x_phone' VALUE='<?= 	$loggedinuser->	cust_phone1?>'>
  <!--			////////////customer shipping information/////////////-->
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_first_name' VALUE='<?= 	$loggedinuser->	cust_your_name?>'>
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_last_name' VALUE='<?= 	$loggedinuser->	LastName?>'>
  <?  if($loggedinuser->delivery_address_choice==1) {?>
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_address' VALUE='<?= 	$loggedinuser->	street1 ." ". $loggedinuser->	street2 ?>'>
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_city' VALUE='<?= 	$loggedinuser->	cust_ord_city?>'>
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_state' VALUE='<?= 	$loggedinuser->	cust_ord_state?>'>
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_zip' VALUE='<?= 	$loggedinuser->	cust_ord_zip?>'>
  <? } else { ?>
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_address' VALUE='<?= 	$loggedinuser->	delivery_street1 ." ". $loggedinuser->	delivery_street2 ?>'>
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_city' VALUE='<?= 	$loggedinuser->	delivery_city1?>'>
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_state' VALUE='<?= 	$loggedinuser->	delivery_state1?>'>
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_zip' VALUE='<?= 	$loggedinuser->	deivery1_zip?>'>
  <? } ?>
  <INPUT TYPE="HIDDEN" NAME='x_ship_to_country' VALUE='USA'>
  <INPUT type='hidden' name='x_invoice_num' value='<?= $invoice?>' />
  <INPUT type='hidden' name='x_fp_sequence' value='<?= $sequence?>' />
  <INPUT type='hidden' name='x_fp_timestamp' value='<?= $timeStamp?>' />
  <INPUT type='hidden' name='x_fp_hash' value='<?= $fingerprint?>' />
   <INPUT type='hidden' name='x_show_form' value='PAYMENT_FORM' />
  <INPUT TYPE="HIDDEN" NAME='x_receipt_link_method' VALUE='POST'>

  <INPUT TYPE="HIDDEN" NAME='x_merchant_name' VALUE='<?= $objRestaurant->name?>'>
   <INPUT TYPE="HIDDEN" NAME='x_sid' VALUE='<?= session_id()?>'>
     <INPUT TYPE="HIDDEN" NAME='x_order_id' VALUE='<?= $cart->order_id?>'>
      
    <INPUT TYPE="HIDDEN" NAME='confimration_email' VALUE='<?= $confimration_email ?>'>
    <INPUT TYPE="HIDDEN" NAME='restuarant_email' VALUE='<?= $restuarant_email?>'>
        <INPUT TYPE="HIDDEN" NAME='serving_date' VALUE='<?= $serving_date ?>'>
    
 
            
  <INPUT TYPE='HIDDEN' NAME='x_relay_url' VALUE='http://www.easywayordering.com/<?=$objRestaurant->url?>/?item=thankyou&ajax=1' />
</FORM>
<script type="text/javascript">$(function(){ 
 
	$("#payment_form").submit();

});</script>