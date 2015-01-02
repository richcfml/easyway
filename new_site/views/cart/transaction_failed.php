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
    if(isset($_GET['tpe'])) 
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
        else if ($_GET['tpe']=="suregate")
        {
            if ($response=="suregate1") 
            {
                $error_msg = "SureGate: Transaction failed.";
            }
            else if ($response=="suregate2") 
            {
                $error_msg = "SureGate: Error occurred.";
            }
            else if ($response=="-100") 
            {
                $error_msg = "SureGate: Transaction NOT Processed; Generic Host Error.";
            }
            else if ($response=="1") 
            {
                $error_msg = "SureGate: User Authentication Failed.";
            }
            else if ($response=="2") 
            {
                $error_msg = "SureGate: Invalid Transaction.";
            }
            else if ($response=="3") 
            {
                $error_msg = "SureGate: Invalid Transaction Type.";
            }
            else if ($response=="4") 
            {
                $error_msg = "SureGate: Invalid Amount.";
            }
            else if ($response=="5") 
            {
                $error_msg = "SureGate: Invalid Merchant Information.";
            }
            else if ($response=="7") 
            {
                $error_msg = "SureGate: Field Format Error.";
            }
            else if ($response=="8") 
            {
                $error_msg = "SureGate: Not a Transaction Server.";
            }
            else if ($response=="9") 
            {
                $error_msg = "SureGate: Invalid Parameter Stream.";
            }
            else if ($response=="10") 
            {
                $error_msg = "SureGate: Too Many Line Items.";
            }
            else if ($response=="11") 
            {
                $error_msg = "SureGate: Client Timeout Waiting for Response.";
            }
            else if ($response=="12") 
            {
                $error_msg = "SureGate: Decline.";
            }
            else if ($response=="13") 
            {
                $error_msg = "SureGate: Referral.";
            }
            else if ($response=="14") 
            {
                $error_msg = "SureGate: Transaction Type Not Supported In This Version.";
            }
            else if ($response=="19") 
            {
                $error_msg = "SureGate: Original Transaction ID Not Found.";
            }
            else if ($response=="20") 
            {
                $error_msg = "SureGate: Customer Reference Number Not Found.";
            }
            else if ($response=="22") 
            {
                $error_msg = "SureGate: Invalid ABA Number.";
            }
            else if ($response=="23") 
            {
                $error_msg = "SureGate: Invalid Account Number.";
            }
            else if ($response=="24") 
            {
                $error_msg = "SureGate: Invalid Expiration Date.";
            }
            else if ($response=="25") 
            {
                $error_msg = "SureGate: Transaction Type Not Supported by Host.";
            }
            else if ($response=="26") 
            {
                $error_msg = "SureGate: Invalid Reference Number.";
            }
            else if ($response=="27") 
            {
                $error_msg = "SureGate: Invalid Receipt Information.";
            }
            else if ($response=="28") 
            {
                $error_msg = "SureGate: Invalid Check Holder Name.";
            }
            else if ($response=="29") 
            {
                $error_msg = "SureGate: Invalid Check Number.";
            }
            else if ($response=="30") 
            {
                $error_msg = "SureGate: Check DL Verification Requires DL State.";
            }
            else if ($response=="40") 
            {
                $error_msg = "SureGate: Transaction did not connect (to NCN because SecureNCIS is not running on the web server).";
            }
            else if ($response=="50") 
            {
                $error_msg = "SureGate: Insufficient Funds Available.";
            }
            else if ($response=="99") 
            {
                $error_msg = "SureGate: General Error.";
            }
            else if ($response=="100") 
            {
                $error_msg = "SureGate: Invalid Transaction Returned from Host.";
            }
            else if ($response=="101") 
            {
                $error_msg = "SureGate: Timeout Value too Small or Invalid Time Out Value.";
            }
            else if ($response=="102") 
            {
                $error_msg = "SureGate: Processor Not Available.";
            }
            else if ($response=="103") 
            {
                $error_msg = "SureGate: Error Reading Response from Host.";
            }
            else if ($response=="104") 
            {
                $error_msg = "SureGate: Timeout waiting for Processor Response.";
            }
            else if ($response=="105") 
            {
                $error_msg = "SureGate: Credit Error.";
            }
            else if ($response=="106") 
            {
                $error_msg = "SureGate: Host Not Available.";
            }
            else if ($response=="107") 
            {
                $error_msg = "SureGate: Duplicate Suppression Timeout.";
            }
            else if ($response=="108") 
            {
                $error_msg = "SureGate: Void Error.";
            }
            else if ($response=="109") 
            {
                $error_msg = "SureGate: Timeout Waiting for Host Response.";
            }
            else if ($response=="110") 
            {
                $error_msg = "SureGate: Duplicate Transaction.";
            }
            else if ($response=="111") 
            {
                $error_msg = "SureGate: Capture Error.";
            }
            else if ($response=="112") 
            {
                $error_msg = "SureGate: Failed AVS Check.";
            }
            else if ($response=="113") 
            {
                $error_msg = "SureGate: Cannot Exceed Sales Cap.";
            }
            else if ($response=="1000") 
            {
                $error_msg = "SureGate: Generic Host Error.";
            }
            else if ($response=="1001") 
            {
                $error_msg = "SureGate: Invalid Login.";
            }
            else if ($response=="1002") 
            {
                $error_msg = "SureGate: Insufficient Privilege or Invalid Amount.";
            }
            else if ($response=="1003") 
            {
                $error_msg = "SureGate: Invalid Login Blocked.";
            }
            else if ($response=="1004") 
            {
                $error_msg = "SureGate: Invalid Login Deactivated.";
            }
            else if ($response=="1005") 
            {
                $error_msg = "SureGate: Transaction Type Not Allowed.";
            }
            else if ($response=="1006") 
            {
                $error_msg = "SureGate: Unsupported Processor.";
            }
            else if ($response=="1007") 
            {
                $error_msg = "SureGate: Invalid Request Message.";
            }
            else if ($response=="1008") 
            {
                $error_msg = "SureGate: Invalid Version.";
            }
            else if ($response=="1010") 
            {
                $error_msg = "SureGate: Payment Type Not Supported.";
            }
            else if ($response=="1011") 
            {
                $error_msg = "SureGate: Error Starting Transaction.";
            }
            else if ($response=="1012") 
            {
                $error_msg = "SureGate: Error Finishing Transaction.";
            }
            else if ($response=="1013") 
            {
                $error_msg = "SureGate: Error Checking Duplicate.";
            }
            else if ($response=="1014") 
            {
                $error_msg = "SureGate: No Records To Settle (in the current batch).";
            }
            else if ($response=="1015") 
            {
                $error_msg = "SureGate: No Records To Process (in the current batch).";
            }
            else 
            {
                $error_msg = "SureGate: Transaction failed. Error code: ".$response;
            }
        }
    }
    else if($response == 2 || $response == 3 || $response == 4 ) 
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
    else if($response == 14 ) 
    {
        $error_msg = "The Referrer or Relay Response URL is invalid.";
    }
    else if($response == 15 ) 
    {
        $error_msg = "The transaction ID is invalid.";
    }
    else if($response == 16 ) 
    {
        $error_msg = "The transaction was not found.";
    }
    else if($response == 17 ) 
    {
        $error_msg = "The merchant does not accept this type of credit card.";
    }
    else if($response == 18 ) 
    {
        $error_msg = "ACH transactions are not accepted by this merchant.";
    }
    else if($response == 19 || $response == 20 || $response == 21 || $response == 12 || $response == 23 ) 
    {
        $error_msg = "An error occurred during processing. Please try again in 5 minutes.";
    }
    else if($response == 24 ) 
    {
        $error_msg = "The Nova Bank Number or Terminal ID is incorrect. Call Merchant Service Provider.";
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