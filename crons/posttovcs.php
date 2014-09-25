<?php 
	require_once("../includes/config.php"); 
	include_once("../includes/phaxio.php"); 
  
	//TrackingNumber below is FaxID of Phaxio, TrackingNumber was used in previous Fax API (Metrofax)
	//Ironically cat_id in ordertbl is RestaurantID :( - Gulfam
													  
	$mQuery="SELECT OrderID, fax_tracking_number, cat_id, PhoneCallCount, submit_time FROM ordertbl WHERE Approve=0 AND TIMESTAMPDIFF(MINUTE, CreateDate, CURRENT_TIMESTAMP()) >= 10 AND PhoneCallCount<3";
	$mysql_query=mysql_query($mQuery);
	$fun=new clsFunctions();
	$mFax=new EWOphaxio();
	
	while($callstosend=mysql_fetch_object($mysql_query))
	{
		$mPhoneCallCount = $callstosend->PhoneCallCount;
		
		$mQuery = "SELECT IFNULL(order_destination, 'fax') AS order_destination, name FROM resturants WHERE id=".$callstosend->cat_id;
		$mResult = mysql_query($mQuery);
		if (mysql_num_rows($mResult)>0)
		{
			$mRow = mysql_fetch_object($mResult);
			$mDestination =  strtolower($mRow->order_destination);
			$mName = $mRow->name;
			
			$mFaxStatus = 0;
			if ($mDestination == "fax") 
			{
				$mFaxStatus = $mFax->CheckFaxStatus($callstosend->fax_tracking_number);
			}
			else if (($mDestination == "email") || ($mDestination == "pos"))
			{
				$mFaxStatus = 1;
			}
			
			if ($mPhoneCallCount<2) //Call should go out only twice
			{
				$fun->posttoVCS($callstosend->OrderID, $mFaxStatus, $callstosend->fax_tracking_number);
			}
			else if ($mPhoneCallCount==2)//If two calls have been made then an email should be sent to easywayordering
			{
				/*$mMailBody = "<div style='font: Arial; font-family: Arial; font-size: 14px;'>";
				$mMailBody .= "Hello EasywayOrdering,<br /><br />";
				$mMailBody .= "There is an unconfirmed order in system. Following are the short details of order.<br /><br />";
				$mMailBody .= "<strong>Order ID: </strong>&nbsp;&nbsp;".$callstosend->OrderID."<br />";
				$mMailBody .= "<strong>Restaurant ID: </strong>&nbsp;&nbsp;".$callstosend->cat_id."<br />";
				$mMailBody .= "<strong>Restaurant Name: </strong>&nbsp;&nbsp;".$mName."<br />";	
				$mMailBody .= "<strong>Order Submit Date-Time: </strong>&nbsp;&nbsp;".date("d M Y H:i", strtotime($callstosend->submit_time))."<br /><br /><br />";	
				$mMailBody .= "Thank You,";
				$mMailBody .= "</div>";
				
				require_once("../includes/class.phpmailer.php");
				
				$mObjMail = new testmail();
				$mObjMail->sendTo($mMailBody, "Unconfirmed Order - ".$callstosend->OrderID, "gulfam@qualityclix.com",true);
				
				unset($mObjMail);*/
			}
			$mQuery = "UPDATE ordertbl SET PhoneCallCount=PhoneCallCount+1 WHERE OrderID=".$callstosend->OrderID;
			mysql_query($mQuery);
		}
	}
	unset($mFax);
	@mysql_close($mysql_conn);
?>