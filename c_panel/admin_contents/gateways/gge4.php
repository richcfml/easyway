<?php
$mSQLQuery=dbAbstract::Execute("SELECT Authorization_Num FROM gge4_authorization_num WHERE UserID=".$gUID." AND transaction_id='".$transactionid."'", 1);
if(dbAbstract::returnRowsCount($mSQLQuery, 1) > 0) 
{
	$mRow=dbAbstract::returnObject($mSQLQuery, 1);
	$mAuthorization_Num = $mRow->Authorization_Num;
        
	$mXML = '<?xml version="1.0" encoding="utf-8" ?>
				<Transaction>
					<ExactID>'.$Objrestaurant->authoriseLoginID.'</ExactID>
					<Password>'.$Objrestaurant->transKey.'</Password>
					<Transaction_Type>34</Transaction_Type>
					<Transaction_Tag>'.$transactionid.'</Transaction_Tag>
					<Authorization_Num>'.$mAuthorization_Num.'</Authorization_Num>
					<DollarAmount>'.$amount.'</DollarAmount>
				</Transaction>';
        Log::write("GGE4 Post XML - Refund ", $mXML, 'gge4', 1);

	$ch = curl_init($GGe4URL); //$GGe4URL is defined in includes/config.php
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $mXML);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=UTF-8;','Accept: text/xml' ));
	
	$result = curl_exec($ch);
	
	if ($result) 
	{
//		$result = json_decode($result);
        Log::write("GGE4 Response - Refund ", print_r($result,true), 'gge4' ,1 );
		
		$mTransaction = simplexml_load_string($result); 
		
		/*$tmp= new SimpleXMLElement($result);
		echo($tmp->asXML());*/
		
		if ($mTransaction->Transaction_Approved!='false')
		{
			if ($mTransaction->Bank_Resp_Code == '100') 
			{
				$success=1;
				$message = $mTransaction->Bank_Message;
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
}
else
{
	$message = "First Data GGe4: Authorization_Num not found.";
}
?>