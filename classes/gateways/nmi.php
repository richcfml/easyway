<?php

if(isset($api) && $api == true){
  require_once '../lib/nmi_api/gwapi.php';
} else {
  require_once 'lib/nmi_api/gwapi.php';
}
		 
$gw = new gwapi;
$gw->setLogin($objRestaurant->authoriseLoginID, $objRestaurant->transKey);
extract($_POST);

if(!isset($card_token))$card_token=0;
if($card_token>0) $tokenization=1;
if(!isset($tokenization)) {$card_token=0;$objRestaurant->tokenization=0;}
 if(!isset($repid_payment)){
        Log::write("NMI Rapid payment not set", '', 'nmi');
        
	$gw->setBilling($x_first_name,$x_last_name, $x_address, $x_city, $x_state,$x_zip,$x_phone,$x_email,$objRestaurant->tokenization);
	$gw->setShipping( $x_first_name,$x_last_name,$x_address,$x_city,$x_state,$x_zip,$x_email);
	$gw->setOrder();
 }
$response = @$gw->doSale($cart_total,$x_card_num,$x_exp_date,$card_token);

$result = ($response == 1 ? 'Approved' : 'Not approved');
Log::write("NMI Response Array \nResult: ".$result, print_r($response, true), 'nmi');

if ($response==APPROVED) {
	$_POST['payment_method']=1;
	$_POST['invoice_number']=$gw->order['orderid'];
	$_POST['transaction_id']=$gw->responses['transactionid'] ;  
	$success=1;
	if($objRestaurant->tokenization==1 && $card_token==0) {
		$gateway_token=$gw->responses['customer_vault_id'] ;
	}
} else {
	$_SESSION['GATEWAY_RESPONSE']=$gw->responses['responsetext'];
	if(!isset($repid_payment)){
			//@mysql_close($mysql_conn);
            if(isset($api) && $api == true)
			{
            	return;
            }
			
            if(isset($wp_api)&& $wp_api==true) 
			{
				//echo "Jamal";
				redirect($SiteUrl . $objRestaurant->url ."/?item=failed&wp_api=failed&response_code=".$response->response_code);
				exit;
			}
			else if(isset($ifrm)&& $ifrm==true) 
			{
				//echo "Jamal";
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
			
?>