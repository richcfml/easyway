<?php
//$url = 'https://api.demo.globalgatewaye4.firstdata.com/transaction/v11';
$url = 'https://api.globalgatewaye4.firstdata.com/transaction/v11';
$mXML ="";
	
$card_token=0; 

$mXML = '<?xml version="1.0" encoding="utf-8" ?>
			<Transaction>
				<ExactID>'.$objRestaurant->authoriseLoginID.'</ExactID>
				<Password>'.$objRestaurant->transKey.'</Password>
				<CardHoldersName>'.$loggedinuser->cust_your_name.' '.$loggedinuser->LastName.'</CardHoldersName>
				<Card_Number>'.$x_card_num.'</Card_Number>
				<Transaction_Type>01</Transaction_Type>
				<Expiry_Date>'.$x_exp_date.'</Expiry_Date>
				<DollarAmount>0</DollarAmount>
			</Transaction>';


$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $mXML);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=UTF-8;','Accept: text/xml' ));

$result = curl_exec($ch);
if ($result) 
{
	$mTransaction = simplexml_load_string($result); 
	/*$tmp= new SimpleXMLElement($result);
	echo("<br />//XML Response Start//<br />".$tmp->asXML()."<br />//XML Response End//<br />");*/
	if (!(is_null($mTransaction->TransactionResult)))
	{
		if ($mTransaction->Bank_Resp_Code == '100') 
		{
			$gateway_token=$mTransaction->TransarmorToken;		 
			$success=1;
		} 
	}
} 
?>