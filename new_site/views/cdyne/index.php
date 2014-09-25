<?  
if(isset($_POST['SMSSent']) && $_POST['SMSSent'])
{
	//outgoing message has been queued for sending
	
	$messageid = $_POST['MessageID'];
	$referenceid =	$_POST['ReferenceID'];
	$fromphonenumber = $_POST['FromPhoneNumber'];
	$tophonenumber = $_POST['ToPhoneNumber'];
	$senttime = $_POST['SentTime'];
 
 
   
   
 
}elseif (isset($_POST['SMSResponse']) && $_POST['SMSResponse']) {
 
	$messageid = $_POST['MessageID'];
	$matchedmessageid = $_POST['MatchedMessageID'];
	$referenceid = $_POST['ReferenceID'];
	$fromphonenumber = $_POST['FromPhoneNumber'];
	$tophonenumber = $_POST['ToPhoneNumber'];
	$responsereceivedate = $_POST['ResponseReceiveDate'];
	$message = $_POST['Message'];
 
 
 
 	$result='messageid ='. $_POST['MessageID'];
 	$result .='<br/>matchedmessageid ='. $_POST['MatchedMessageID'];
	$result .='<br/>referenceid ='. $_POST['ReferenceID'];
	$result .='<br/>fromphonenumber ='. $_POST['FromPhoneNumber'];
	$result .='<br/>tophonenumber ='. $_POST['ToPhoneNumber'];
	$result .='<br/>responsereceivedate ='. $_POST['ResponseReceiveDate'];
 	$result .='<br/>message ='. $_POST['Message'];
	
	 
	 
   
		$objcdyne->phonenumber=$fromphonenumber;
		$objcdyne->sms=$message;
		$objcdyne->SMSReceived();
	
	//somedatabasecommandobject($sql);
 }elseif (isset($_POST['SMSReceived']) && $_POST['SMSReceived']) {
 
	$messageid = $_POST['MessageID'];
	$matchedmessageid = $_POST['MatchedMessageID'];
	$referenceid = $_POST['ReferenceID'];
	$fromphonenumber = $_POST['FromPhoneNumber'];
	$tophonenumber = $_POST['ToPhoneNumber'];
	$responsereceivedate = $_POST['ResponseReceiveDate'];
	$message = $_POST['Message'];
 
   
	$objcdyne->phonenumber=$fromphonenumber;
	$objcdyne->sms=$message;
	$objcdyne->SMSReceived();

   //$objcdyne->sendSMS($fromphonenumber,'I received this new message, please reply once ',$referenceid,$objcdyne->SYSTEM_RESPONSE);
   //$objcdyne->saveLog($tophonenumber,$fromphonenumber,$messageid,$matchedmessageid,$referenceid,$message,$objcdyne->CUSTOMER_SMS);
	//somedatabasecommandobject($sql);
	
} else if (isset($_GET['mod']) && $_GET['mod']=='sentsms') {
	 
	//$key = '6a06bf43-e655-40a9-8127-e69ec8a62091';
	  $key='04257110-2ae2-452f-a776-f8ae189e5f74';
	 	 $cell='9082793830';
			 if(isset($_GET['cell'])){
				  $cell=$_GET['cell'];
				 }
 
$json='{
                           "LicenseKey":"'. $key .'",
                           "SMSRequests":[{
								"AssignedDID":"13478159686 ",
								"Message":"Hello there",
								"PhoneNumbers":["'. $cell .'"],
								"ReferenceID":"test cdyne",
								"StatusPostBackURL":"http://easywayordering.com/ozzies/?item=cdyne"
                                                            }]
}';
 
 
//Method
$url='http://sms2.cdyne.com/sms.svc/AdvancedSMSsend';
 
$cURL = curl_init();
 
curl_setopt($cURL,CURLOPT_URL,$url);
curl_setopt($cURL,CURLOPT_POST,true);
curl_setopt($cURL,CURLOPT_POSTFIELDS,$json);
curl_setopt($cURL,CURLOPT_RETURNTRANSFER, true);  
curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json'));
//If you desire your results in xml format, use the following line for your httpheaders and comment out the httpheaders code line above.
//curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
$result = curl_exec($cURL);
echo $json;
 echo "<br/><br/><pre>";print_r($result);echo "</pre>";
curl_close($cURL);
 
 

	}

 
			
?>