<?php
dbAbstract::Insert("INSERT INTO tblDebug(Step, Value1, Value2) VALUES (1, 'CdyneResponse', '1212121212");
if(isset($_POST['SMSSent']) && $_POST['SMSSent'])
{
	//outgoing message has been queued for sending
	
	$messageid = $_POST['MessageID'];
	$referenceid =	$_POST['ReferenceID'];
	$fromphonenumber = $_POST['FromPhoneNumber'];
	$tophonenumber = $_POST['ToPhoneNumber'];
	$senttime = $_POST['SentTime'];
	
	$mLogStr = "MessageID: ".$messageid.", ReferenceID: ".$referenceid.", From Phone Number: ".$fromphonenumber.", To Phone Number: ".$tophonenumber.", Sent Time: ".$senttime;
	Log::write('CDYNE SMS Sent - cdyne/index.php', $mLogStr , 'cdyne');
}
elseif (isset($_POST['SMSResponse']) && $_POST['SMSResponse']) 
{
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
	
	$mLogStr = "MessageID: ".$messageid.", Matched Messages ID: ".$matchedmessageid.", ReferenceID: ".$referenceid.", From Phone Number: ".$fromphonenumber.", To Phone Number: ".$tophonenumber.", Response Receive Date: ".$responsereceivedate.", Message: ".$message;
	Log::write('CDYNE SMS Response - cdyne/index.php', $mLogStr , 'cdyne');
	
	$objcdyne->phonenumber=$fromphonenumber;
	$objcdyne->sms=$message;
	$objcdyne->SMSReceived();
}
elseif (isset($_POST['SMSReceived']) && $_POST['SMSReceived']) 
{
	$messageid = $_POST['MessageID'];
	$matchedmessageid = $_POST['MatchedMessageID'];
	$referenceid = $_POST['ReferenceID'];
	$fromphonenumber = $_POST['FromPhoneNumber'];
	$tophonenumber = $_POST['ToPhoneNumber'];
	$responsereceivedate = $_POST['ResponseReceiveDate'];
	$message = $_POST['Message'];
   	
	$mLogStr = "MessageID: ".$messageid.", Matched Messages ID: ".$matchedmessageid.", ReferenceID: ".$referenceid.", From Phone Number: ".$fromphonenumber.", To Phone Number: ".$tophonenumber.", Response Receive Date: ".$responsereceivedate.", Message: ".$message;
	Log::write('CDYNE SMS Received - cdyne/index.php', $mLogStr , 'cdyne');
	
	$objcdyne->phonenumber=$fromphonenumber;
	$objcdyne->sms=$message;
	$objcdyne->SMSReceived();
} 
?>