<?php
$mXML ="";
$card_token=0; 

$mUserName = $objRestaurant->authoriseLoginID;
$mPassword = $objRestaurant->transKey;

$mCustomerKey = "";
$mSQL = "SELECT IFNULL(CustomerKey, '') AS  CustomerKey FROM  customer_registration WHERE id=".$loggedinuser->id;
$mRes = mysql_query($mSQL);
$mRow = mysql_fetch_object($mRes);
$mCustomerKey = $mRow->CustomerKey;
if (trim($mCustomerKey)=="")
{
	$mURL = $SureGateURL."paygate/ws/recurring.asmx/ManageCustomer"; //$SureGateURL is defined in includes/config.php
	$mTransType = "ADD";
	$mVendorID = $objRestaurant->VendorID;
	$mCustomerID = $loggedinuser->id;
	$mCustomerKey = "";
	$mFirstName = $loggedinuser->cust_your_name;
	$mLastName = $loggedinuser->LastName;
	$mCustomerName = $mFirstName." ".$mLastName;
	$mTitle = $loggedinuser->LastName;
	$mDepartment = "";
	$mStreet1 = "";
	$mStreet2 = "";
	$mStreet3 = "";
	$mCity = "";
	$mStateID = "";
	$mProvince = "";
	$mZip = "";
	$mCountryID = "";
	$mDayPhone = "";
	$mNightPhone = "";
	$mFax = "";
	$mEmail = "";
	$mMobile = "";
	$mStatus = "";
	$mExtData = "";
	
	$mPostString = "UserName=".$mUserName."&Password=".$mPassword."&TransType=".$mTransType."&Vendor=".$mVendorID."&CustomerID=".$mCustomerID."&CustomerName=".$mCustomerName."&CustomerKey=".$mCustomerKey."&FirstName=".$mFirstName."&LastName=".$mLastName."&Title=".$mTitle."&Department=".$mDepartment."&Street1=".$mStreet1."&Street2=".$mStreet2."&Street3=".$mStreet3."&City=".$mCity."&StateID=".$mStateID."&Province=".$mProvince."&Zip=".$mZip."&CountryID=".$mCountryID."&DayPhone=".$mDayPhone."&NightPhone=".$mNightPhone."&Fax=".$mFax."&Email=".$mEmail."&Mobile=".$mMobile."&Status=".$mStatus."&ExtData=".$mExtData;
	Log::write('SureGate Post Variables - Save Customer -Tokenization', $mPostString, 'suregate');
	
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
		$mTransaction = simplexml_load_string($result); 
		/*$tmp= new SimpleXMLElement($result);
		echo($tmp->asXML());*/

		if (isset($mTransaction->CustomerKey))
		{
			$mCustomerKey = $mTransaction->CustomerKey;
			$mSQL = "UPDATE customer_registration SET CustomerKey='".$mCustomerKey."' WHERE id=".$loggedinuser->id;
			mysql_query($mSQL);
		}
	}
}

if (trim($mCustomerKey)!="")
{
	$mURL = $SureGateURL."ws/cardsafe.asmx/StoreCard"; //$SureGateURL is defined in includes/config.php
	$mTokenMode = "DEFAULT";
	$mCardNum = $x_card_num;
	$mExpDate = $x_exp_date;
	$mNameOnCard = "";
	$mStreet = "";
	$mZip = "";
	$mExtData = "";
	
	$mPostString = "UserName=".$mUserName."&Password=".$mPassword."&CustomerKey=".$mCustomerKey."&CardNum=".$mCardNum."&ExpDate=".$mExpDate."&TokenMode=".$mTokenMode."&NameOnCard=".$mNameOnCard."&Street=".$mStreet."&Zip=".$mZip."&ExtData=".$mExtData;
	Log::write('SureGate Post Variables - Save Card - Tokenization', $mPostString, 'suregate');
	
	$ch = curl_init(); 
	curl_setopt ($ch, CURLOPT_URL, $mURL);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $mPostString);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 120);			
	curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	$result = curl_exec($ch);
	Log::write('SureGate Response: Save Card(Home) ', print_r($result,true), 'suregate');					
	unset($ch);

	if ($result) 
	{
		$mTransaction = simplexml_load_string($result); 
		if ($mTransaction)
		{
			if ($mTransaction->Result=="0")
			{
				$mExtDataOp = new SimpleXMLElement($mTransaction->ExtData);
				$gateway_token = $mExtDataOp;
				$CarkTokenOrderTbl = $mExtDataOp;
				$success=1;
			}
		}
	}	
}
?>