<?php

/*
 * Authorize.net API and transakction key
 * '62XAQ6temv3', '9gEgzkA2t2kT327q'
 *
 */

if (isset($api) && $api == true) {
 
    require_once '../lib/authorize_api/AuthorizeNet.php';
} else {
    require_once 'lib/authorize_api/AuthorizeNet.php';
}
require_once 'classes/gateways/tokenization/Auth_Transaction.php';

$gw = new AuthNetTokenizationModel($objRestaurant->authoriseLoginID, $objRestaurant->transKey);
extract($_POST);

if(!isset($card_token)) $card_token=0;
if($card_token>0)  $tokenization=1;
if(!isset($tokenization)) {$card_token=0;$objRestaurant->tokenization=0;}
if(!isset($tokenization))
{
//extract($_POST);
		define("AUTHORIZENET_API_LOGIN_ID",$objRestaurant->authoriseLoginID);    // Add your API LOGIN ID
		define("AUTHORIZENET_TRANSACTION_KEY",$objRestaurant->transKey); // Add your API transaction key
		define("AUTHORIZENET_SANDBOX",$AuthorizeDotNetSandBox);       // Set to false to test against production, $AuthorizeDotNetSandBox is defined in includes/config.php
		define("TEST_REQUEST", $AuthorizeDotNetTestRequest);           // You may want to set to true if testing against production, $AuthorizeDotNetTestRequest is defined in includes/config.php
		define("AUTHORIZENET_MD5_SETTING","");
		
		 extract($_POST);
		$auth_defined = true;		
		$transaction = new AuthorizeNetAIM;
		$transaction->setSandbox(AUTHORIZENET_SANDBOX);

		$transaction->setFields(
			array(
			'amount' => $cart_total, 
			'card_num' => $x_card_num, 
			'exp_date' => $x_exp_date,
			'first_name' => $x_first_name,
			'last_name' => $x_last_name,
			'address' => $x_address,
			'city' => $x_city,
			'state' => $x_state,
			'country' => "USA",
			'zip' => $x_zip,
			'email' => $x_email,
			'ship_to_first_name' => $x_first_name,
			'ship_to_last_name' => $x_last_name,
			'ship_to_address' => $x_address,
			'ship_to_city' => $x_city,
			'ship_to_state' => $x_state,
			'ship_to_zip' => $x_zip,
		    'ship_to_country' => "USA",
			'invoice_num' => date('YmdHis')			
			)
		);

		$_POST['x_exp_date']='';
		
		$response = $transaction->authorizeAndCapture();
    $response_array = array();
    foreach($response as $key => $value){
        $response_array[$key] = $value;        
    }
    
    $result = ($response->approved == 1 ? 'Approved' : 'Not approved');
    
    Log::write("AuthorizeNet Response Array - Tokenization not set \nResult: ".$result, print_r($response_array, true), 'AuthorizeNet');
		
                if ($response->approved) {
				$_POST['payment_method']=1;
				$_POST['invoice_number']=$response->invoice_number;
				$_POST['transaction_id']=$response->transaction_id;
                                $txn_id = $response->transaction_id;
								
				$success=1;

			} else {
				$_SESSION['GATEWAY_RESPONSE']=$response->error_message;
				@mysql_close($mysql_conn);

				if(isset($wp_api) && $wp_api==true) 
				{
					//echo "Jamal";
					redirect($SiteUrl . $objRestaurant->url ."/?item=failed&wp_api=failed&response_code=".$response->response_code);
					exit;
				} 
				else if(isset($ifrm) && $ifrm==true) 
				{
					redirect($SiteUrl . $objRestaurant->url ."/?item=failed&ifrm=failed&response_code=".$response->response_code);
					exit;
				}
				else 
				{
					redirect($SiteUrl . $objRestaurant->url ."/?item=failed&response_code=".$response->response_code);
					exit;
				}

		 	}
                //$invoice_number = $response->invoice_number;
              //  $transaction_id =$response->transaction_id;
               // $error_message = $response->error_message;
}
if(isset($repid_payment))
    {
	
	$cusprofile = mysql_fetch_object(mysql_query('SELECT * FROM auth_cc_tokens WHERE token = "'.mysql_escape_string($card_token).'"'));
    
	$custid=$cusprofile->customer_id;
	
    $profile=$gw->loadProfile($custid, $objRestaurant->id);
	
    $userprofile = mysql_fetch_object(mysql_query('SELECT * FROM auth_shipping_details WHERE customer_id = "'.$custid.'" ORDER BY id DESC LIMIT 1'));
    $shipping_id=$userprofile->cust_shipping_id;
    Log::write("AuthorizeDotNet Rapid Payment User Profile:", print_r($profile, true), 'AuthorizeNet');
    
	
    $transaction_id = $gw->useToken($cart_total,$card_token,$shipping_id,$profile->profile_id);
	 $qry = 'INSERT INTO cydne_log (ToPhoneNumber, MessageID, MatchedMessageID, ReferenceId,FromPhoneNumber,Message,LogTime,SMSType) VALUES ("'.$payment_profile_id.'", "'.mysql_escape_string($amount).'", "'.mysql_escape_string($profile->profile_id).'", "'.mysql_escape_string($shipping_id).'","'.mysql_escape_string($transaction_id).'","'.mysql_escape_string(1111).'","'.mysql_escape_string(1111).'","'.mysql_escape_string(3).'")';
	mysql_query($qry);
    if($transaction_id>0)
    {

        $gateway_token = $payment_profileid;
        $invoice_number = $payment_profileid;
				$_POST['payment_method']=1;
				$_POST['invoice_number']=$invoice_number;
				$_POST['transaction_id']=$transaction_id;
				$success=1;
        Log::write("AuthorizeDotNet Rapid Payment Response: \nResult: Success", 'Transaction Id: '.$transaction_id, 'AuthorizeNet');
    }
    else
    {
        $error_message = $transaction_id;
        Log::write("AuthorizeDotNet Rapid Payment Response: \nResult: Error", 'Error: '.$transaction_id, 'AuthorizeNet');
    }
    }

if(!isset($repid_payment)){

if(isset ($tokenization)){
$profile=$gw->loadProfile($loggedinuser->id, $objRestaurant->id);

        Log::write("AuthorizeDotNet User Profile - Tokenization set", print_r($profile, true), 'AuthorizeNet');
if(!empty ($profile ) &$card_token!=0){
$userprofile = mysql_fetch_object(mysql_query('SELECT * FROM auth_shipping_details WHERE customer_id = "'.$loggedinuser->id.'" AND Address = "'.mysql_escape_string($x_address).'" And City="'.mysql_escape_string($x_city).'" And state="'.mysql_escape_string($x_state).'" And zip_code="'.mysql_escape_string($x_zip).'"'));

$shipping_id=$userprofile->cust_shipping_id;

 if(empty ($shipping_id ) || $shipping_id==0){
    $shipping_id = $gw->getshippingid($x_first_name,$x_last_name,$x_address,$x_city,$x_state,$x_zip,$x_phone,$objRestaurant->id,$payment_profileid,$x_email,$loggedinuser->id);
    }
$transaction_id = $gw->useToken($cart_total,$card_token,$shipping_id,$profile->profile_id);

if($transaction_id>0)
    {
    
        $gateway_token = $payment_profileid;
        $invoice_number = $payment_profileid;
        $response =1;
                Log::write("AuthorizeDotNet Response - Tokenization set, Card Token not Set, Profile Not empty \nResult: Success", 'Transaction Id: '.$transaction_id, 'AuthorizeNet');
  
    }
    else
    {
        $error_message = $transaction_id;
                Log::write("AuthorizeDotNet Response - Tokenization set, Card Token not Set, Profile Not empty \nResult: Error", 'Error: '.$transaction_id, 'AuthorizeNet');
    }
}

if(!empty ($profile ) & $card_token ==  0)
{   
    $payment_profileid = $gw->saveCCToken($objRestaurant->name,$x_card_num,substr($x_exp_date,2,4),substr($x_exp_date,0,2),$x_first_name,$x_last_name,$x_address,$x_city,$x_state,$x_zip,"USA",$x_phone,"1123",$loggedinuser->id,$objRestaurant->id);
    if(strpos($payment_profileid,'duplicate customer payment profile already exists')!==false)
    {
             redirect($SiteUrl .$objRestaurant->url ."/?item=checkout" );exit;
    }
    if($payment_profileid>0 ){
    $userprofile = mysql_fetch_object(mysql_query('SELECT * FROM auth_shipping_details WHERE customer_id = "'.$loggedinuser->id.'" AND Address = "'.mysql_escape_string($x_address).'" And City="'.mysql_escape_string($x_city).'" And state="'.mysql_escape_string($x_state).'" And zip_code="'.mysql_escape_string($x_zip).'"'));
    $shipping_id=$userprofile->cust_shipping_id;
    if(empty ($shipping_id ) || $shipping_id==0){
    $shipping_id = $gw->getshippingid($x_first_name,$x_last_name,$x_address,$x_city,$x_state,$x_zip,$x_phone,$objRestaurant->id,$payment_profileid,$x_email,$loggedinuser->id);
    }
    $transaction_id = $gw->useToken($cart_total,$payment_profileid,$shipping_id,$profile->profile_id);
   }else{
       $transaction_id=$payment_profileid;
   }
    if($transaction_id>0)
    {
        $response =1;
        $gateway_token = $payment_profileid;
        $invoice_number = $gateway_token;
                Log::write("AuthorizeDotNet Response - Tokenization set, Card Token Set, Profile Not empty \nResult: Success", 'Transaction Id: '.$transaction_id, 'AuthorizeNet');
        
    }
    else
    {
        $error_message = $transaction_id;
                Log::write("AuthorizeDotNet Response - Tokenization set, Card Token Set, Profile Not empty \nResult: Error", 'Error: '.$transaction_id, 'AuthorizeNet');
    }
   
}
if(empty ($profile )){

  $profile=  $gw->createProfile($loggedinuser->cust_email, $objRestaurant->id);
   if($profile>0){
   $payment_profileid = $gw->saveCCToken($objRestaurant->name,$x_card_num,substr($x_exp_date,2,4),substr($x_exp_date,0,2),$x_first_name,$x_last_name,$x_address,$x_city,$x_state,$x_zip,"USA",$x_phone,"1123",$loggedinuser->id,$objRestaurant->id);
    if($payment_profileid>0){
    $shipping_id = $gw->getshippingid($x_first_name,$x_last_name,$x_address,$x_city,$x_state,$x_zip,$x_phone,$objRestaurant->id,$payment_profileid,$x_email,$loggedinuser->id);
    $transaction_id = $gw->useToken($cart_total,$payment_profileid,$shipping_id,$profile);
    }else{
        $transaction_id=$payment_profileid;
    }
   }
    else
    {
         $transaction_id=$profile;
    }
    if($transaction_id>0)
    {
        $response =1;
        $gateway_token = $payment_profileid;
        $invoice_number = $gateway_token;
                Log::write("AuthorizeDotNet Response - Tokenization set, Profile empty \nResult: Success", 'Transaction Id: '.$transaction_id, 'AuthorizeNet');
    }
    else
    {
        $error_message = $transaction_id;
                Log::write("AuthorizeDotNet Response - Tokenization set, Profile empty \nResult: Error", 'Error: '.$transaction_id, 'AuthorizeNet');
    }
 }


if ( $response ==1) {
				$_POST['payment_method']=1;
				$_POST['invoice_number']=$invoice_number;
				$_POST['transaction_id']=$transaction_id;
				$success=1;

			} else {
				$_SESSION['GATEWAY_RESPONSE']=$error_message;
				@mysql_close($mysql_conn);

                                if (isset($api) && $api == true) {
                                    return;
                                }
				if(isset($wp_api) && $wp_api==true) 
				{
					redirect($SiteUrl . $objRestaurant->url ."/?item=failed&wp_api=failed&response_code=".$response->response_code);
					exit;
				}
				else if(isset($ifrm) && $ifrm==true) 
				{
					redirect($SiteUrl . $objRestaurant->url ."/?item=failed&ifrm=failed&response_code=".$response->response_code);
					exit;
				}
				else 
				{
					redirect($SiteUrl . $objRestaurant->url ."/?item=failed&response_code=".$response->response_code);
					exit;
				}
		 	}
}
}

                /*
                 * Refund the order and show message
                 */
                 if(isset($_GET['refund']) && is_numeric($_GET['refund'])){
                  
                     echo JSON_SERVER_RESPONSE_BREAKER;
                    $trans = AuthorizeNetTransactionModel::getTransactionByOrder($_GET['refund']);
    Log::write("AuthorizeDotNet Refund - Get Transaction By Order - ".$_GET['refund'], print_r($trans,true), 'AuthorizeNet');
                    $message = '';
                    if($trans){

                        $message = AuthorizeNetTransactionModel::refundTransaction($trans->txn_id, $transaction);
        Log::write("AuthorizeDotNet Refund Response \nResult: ".$message, print_r($message,true), 'AuthorizeNet');
                    }else{

                        $message = 'Cannot be refunded!';
        Log::write("AuthorizeDotNet Refund Response \nResult: Cannot be refunded", 'Message: Cannot be refunded!', 'AuthorizeNet');
                    }
                    print_r($transaction->void($trans->txn_id));
                    echo JSON_SERVER_RESPONSE_BREAKER;
                    
                    echo json_encode(
                            array(
                                'message'=>$message
                            )
                        );
                    exit;
                 }
		
?>