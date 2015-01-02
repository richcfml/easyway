<?php

$mURL = $SureGateURL."ws/cardsafe.asmx/ProcessCreditCard"; //$SureGateURL is defined in includes/config.php
$mUserName = $Objrestaurant->authoriseLoginID;
$mPassword = $Objrestaurant->transKey;
$mTransType = "Return";
$mCardToken = $gCardToken;
$mTokenMode = "DEFAULT";
$mInvNum = "";
$mPNRef = $transactionid;
$mExtData = "";
$mAmount = 0;
if ($SureGateTestFlag) // $SureGateTestFlag is defined in Config
{
	$mAmount = round($amount); //Because for test credentials only round figure amount is accepted by Gateway
}
else
{
	$mAmount = $amount;
}

$mPostString = "UserName=".$mUserName."&Password=".$mPassword."&TransType=".$mTransType."&CardToken=".$mCardToken."&TokenMode=".$mTokenMode."&Amount=".$mAmount."&InvNum=".$mInvNum."&PNRef=".$mPNRef."&ExtData=".$mExtData;
Log::write('SureGate Post Variables - Refund Payment', $mPostString, 'suregate',1 );

$ch = curl_init(); 
curl_setopt ($ch, CURLOPT_URL, $mURL);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $mPostString);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_TIMEOUT, 120);			
curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
unset($ch);

if ($result) 
{
	Log::write("SureGate Response - Refund ", print_r($result,true), 'suregate' ,1 );	
	$mTransaction = simplexml_load_string($result); 

	if ($mTransaction)
	{
		if ($mTransaction->Result=="0")
		{
			$success=1;
			$message = $mTransaction->Message;
			$transaction_id=$mTransaction->Transaction_Tag;
		}	 
		else 
		{
			$message = $mTransaction->Bank_Message;
		}
	}
	else
	{
		$message = $result;
	}
} 
else 
{
	$message = "First Data GGe4: Connection failure.";
}
?>