<?php 
	$cart_total=$cart->grand_total();
	if(!is_numeric($cart_total) || $cart_total<=0) 
	{
		header("location: ". $SiteUrl .$objRestaurant->url ."/?item=account");
		exit;	
	}
	
	if(!isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "off") 
	{
		 header("location: ". $SiteUrl .$objRestaurant->url ."/");
		 exit;
 	}
	
	$response=0;
	
	if(isset($_GET['response_code']))
	{
		$response=$_GET['response_code']; 
	}
?>
<div id="thank_you_area" style="padding:5px;">
 
<?php
	if($response == 2 || $response == 3 || $response == 4 ) 
	{
		$error_msg = "This transaction has been declined.";
	}
	
	else if($response == 5  ) 
	{
		$error_msg = "A valid amount is required.";
	}
	
	else if($response == 6  ) 
	{
		$error_msg = "The credit card number is invalid.";
	}
	
	else if($response == 7 ) 
	{
		$error_msg = "The credit card expiration date is invalid.";
	}
	
	else if($response == 8 ) 
	{
		$error_msg = "The credit card has expired.";
	}
	
	else if($response == 9 ) 
	{
		$error_msg = "The ABA code is invalid.";
	}
	
	else if($response == 10 ) 
	{
		$error_msg = "The account number is invalid.";  	
	}
	
	else if($response == 11 ) 
	{
		$error_msg = "A duplicate transaction has been submitted.";  	
	}
	
	else if($response == 12 ) 
	{
		$error_msg = "An authorization code is required but not present.";
	}
	
	else if($response == 13 ) 
	{
		$error_msg = "The merchant API Login ID is invalid or the account is inactive.";
	}
	
	elseif($response == 14 ) 
	{
		$error_msg = "The Referrer or Relay Response URL is invalid.";
	}
	
	elseif($response == 15 ) 
	{
		$error_msg = "The transaction ID is invalid.";
	}
	
	else if($response == 16 ) 
	{
		$error_msg = "The transaction was not found.";
	}
	
	elseif($response == 17 ) 
	{
		$error_msg = "The merchant does not accept this type of credit card.";
	}
	
	if($response == 18 ) 
	{
		$error_msg = "ACH transactions are not accepted by this merchant.";
	}
	
	elseif($response == 19 || $response == 20 || $response == 21 || $response == 12 || $response == 23 ) 
	{
		$error_msg = "An error occurred during processing. Please try again in 5 minutes.";
	}
	
	elseif($response == 24 ) 
	{
		$error_msg = "The Nova Bank Number or Terminal ID is incorrect. Call Merchant Service Provider.";
	}
	 
	elseif(isset($_GET['tpe'])) 
	{
		if ($_GET['tpe']=="gge4")
		{
			if ($response=="gge41") 
			{
				$error_msg = "First Data GGe4: Transaction failed.";
			}
			else if ($response=="gge42") 
			{
				$error_msg = "First Data GGe4: Error occurred.";
			}
			else
			{
				$error_msg = "First Data GGe4: Transaction failed. Error code: ".$response;
			}
		}
	}
	
	$error_msg = $error_msg. "<br/>". $_SESSION['GATEWAY_RESPONSE'];
			
	$_SESSION["abandoned_cart_error"]["credit_card_declined"] = $error_msg;
	
	//$cart->destroysession();
	//$loggedinuser->destroysession();
?>
	<div id="error_img"><img src="<?=$SiteUrl?>images/dialog_warning.png" width="64" /></div>
	<div id="error_message" style="font-size:12px;">
	<?=$error_msg?>
	<br /><br />
 	<a href="<?=$SiteUrl. $objRestaurant->url ?>/"><strong>Go to Home Page</strong></a>
	</div>
	<br />
</div>
<br style="clear: both;" />