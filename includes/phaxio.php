<?php

class EWOphaxio
{
		
	public function SendFax($pOrderID, $pFaxNumber) 
	{
		global $PhaxioCallBackUrl;
		try
		{
			//TrackingNumber below is FaxID of Phaxio, TrackingNumber was used in previous Fax API (Metrofax)
			$query="INSERT INTO fax_log(orderid, status, fax_date, TrackingNumber, fax_message) values (".$pOrderID.",0,'". date("Y-m-d H:i:s")  ."', 0, 'Fax sending started' )";
			mysql_query($query);
			Log::write('PHAXIO Post Array - Send Fax', $query.mysql_error(), 'phaxio');
			$mLogID = mysql_insert_id();
			
			$mFileName = $pOrderID .".pdf";
			$mFilePath = realpath("pdffiles/pdf".$mFileName);	
			
			$mCallBackURL = $PhaxioCallBackUrl."crons/callback.php?orderid=".$pOrderID;

			//$PhaxioApiKey and $PhaxioApiSecret are defined in includes/config.php
			global $PhaxioApiKey, $PhaxioApiSecret;
			$mPostData = array('to' => $pFaxNumber, 'filename' => '@'.$mFilePath, 'api_key' => $PhaxioApiKey, 'api_secret' => $PhaxioApiSecret, 'callback_url' => $mCallBackURL);
			
            Log::write('PHAXIO Post Array - Send Fax', print_r($mPostData,true), 'phaxio');
                        
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.phaxio.com/v1/send");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $mPostData);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$mResponse = curl_exec($ch);
			curl_close($ch);
			unset($ch);
			if ($mResponse)
			{
				$mDecodeResponse = $this->json_decode_ewo($mResponse);
				$mSuccess = $mDecodeResponse["success"];
				$mMessage = $mDecodeResponse["message"];
				$mFaxID = $mDecodeResponse["faxId"];
				
				
				if (($mSuccess==1) || ($mSuccess=="1") || ($mSuccess==true) || (strtolower($mSuccess)=="true"))
				{
					mysql_query("UPDATE ordertbl SET fax_tracking_number='".$mFaxID."', fax_sent=1, resend_fax=0, fax_tries=fax_tries+1, json_sent=0, fax_date='".date("Y-m-d H:i:s") ."' WHERE OrderID=".$pOrderID);
					//TrackingNumber below is FaxID of Phaxio, TrackingNumber was used in previous Fax API (Metrofax)
					mysql_query("UPDATE fax_log SET fax_message='Success', status=1, TrackingNumber='".$mFaxID."' WHERE id=".$mLogID);
                                        
                                        Log::write("PHAXIO Response - Send Fax \nResult: Fax Sent", print_r($mDecodeResponse,true), 'phaxio');
                                        
				}
				else
				{
					mysql_query("UPDATE fax_log SET fax_message='".$mMessage."' WHERE id=". $mLogID);
					//TrackingNumber below is FaxID of Phaxio, TrackingNumber was used in previous Fax API (Metrofax)
					mysql_query("UPDATE ordertbl SET fax_tracking_number=1234, fax_tries=fax_tries+1, resend_fax=0 WHERE OrderID=".$pOrderID);
                                        
                                        Log::write("PHAXIO Response - Send Fax \nResult: ".$mMessage, print_r($mDecodeResponse,true), 'phaxio');
				}
			}
			else
			{
				mysql_query("UPDATE fax_log SET fax_message='Error occurred while sending fax.' WHERE id=".$mLogID);
				//TrackingNumber below is FaxID of Phaxio, TrackingNumber was used in previous Fax API (Metrofax)
				mysql_query("UPDATE ordertbl SET fax_tracking_number=1234, fax_tries=fax_tries+1, resend_fax=0 WHERE OrderID=".$pOrderID);
                                
                                Log::write("PHAXIO Response - Send Fax \nResult: Error occurred while sending fax.", '' , 'phaxio');
			}
		}
		catch (Exception $e)
		{
			mysql_query("UPDATE fax_log SET fax_message='Exception raised while sending fax.' WHERE id=".$mLogID);
			//TrackingNumber below is FaxID of Phaxio, TrackingNumber was used in previous Fax API (Metrofax)
			mysql_query("UPDATE ordertbl SET fax_tracking_number=1234, fax_tries=fax_tries+1, resend_fax=0 WHERE OrderID=".$pOrderID);
                        
                        Log::write("PHAXIO Response - Send Fax \nResult: Exception raised while sending fax.", '' , 'phaxio');
		}
		mysql_close($mysql_conn);
	}
	
	public function ResendFax()
	{
   		$mQuery = "SELECT O.OrderID AS OrderID, R.fax AS FaxNumber FROM ordertbl O INNER JOIN resturants R ON O.cat_id = R.id WHERE O.resend_fax=1 ORDER BY OrderID DESC";
					 
		$mResult = mysql_query($mQuery);
		$mCount = 0;	
	
		while($faxtoresend = mysql_fetch_object($mResult))
		{
			$this->SendFax($faxtoresend->OrderID, $faxtoresend->FaxNumber);
		    $mCount = $mCount+1;
		}
		return $mCount;
	}
	
	public function CheckFaxStatus($pFaxID) 
	{
		try
		{
			//$PhaxioApiKey and $PhaxioApiSecret are defined in includes/config.php
			global $PhaxioApiKey, $PhaxioApiSecret;
			$mPostData = array('id' => $pFaxID, 'api_key' => $PhaxioApiKey, 'api_secret' => $PhaxioApiSecret);
			
                        Log::write('PHAXIO Post Array - Check Fax Status', print_r($mPostData,true), 'phaxio');
                        
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.phaxio.com/v1/faxStatus");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $mPostData);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			$mResponse = curl_exec($ch);
			
			curl_close($ch);
			unset($ch);
			
			if ($mResponse)
			{
				$mDecodeResponse = $this->json_decode_ewo($mResponse);
				$mSuccess = $mDecodeResponse["success"];
				if (($mSuccess==1) || ($mSuccess=="1") || ($mSuccess==true) || (strtolower($mSuccess)=="true"))
				{
					$mStatus = $mDecodeResponse["data"]["status"];
					if (strtolower($mStatus)=="success")
					{
						return 1;
                                                Log::write("PHAXIO Response - Check Fax Status \nResult: Success", print_r($mDecodeResponse,true), 'phaxio');
					}
					else
					{
                                                Log::write("PHAXIO Response - Check Fax Status \nResult: ".$mStatus, print_r($mDecodeResponse,true), 'phaxio');
						return 0;
					}
				}
				else
				{
                                        Log::write("PHAXIO Response - Check Fax Status \nResult: Failure", print_r($mDecodeResponse,true), 'phaxio');
					return 0;
				}
			}
			else
			{
                                Log::write("PHAXIO Response - Check Fax Status \nResult: Failure", print_r($mDecodeResponse,true), 'phaxio');
				return 0;
			}
		}
		catch (Exception $e)
		{
			return 0;
		}
	}
	
	public function json_decode_ewo($json)
   	{
		$comment = false;
		$out = '$x=';
		for ($i=0; $i<strlen($json); $i++)
		{
			if (!$comment)
			{
				if (($json[$i] == '{') || ($json[$i] == '['))
					$out .= ' array(';
				else if (($json[$i] == '}') || ($json[$i] == ']'))
					$out .= ')';
				else if ($json[$i] == ':')
					$out .= '=>';
				else
					$out .= $json[$i];
			}
			else
				$out .= $json[$i];
			if ($json[$i] == '"' && $json[($i-1)]!="\\")
				$comment = !$comment;
		}
		eval($out . ';');
		return $x;
	}
}
?>