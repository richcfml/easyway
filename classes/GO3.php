<?php
//ServerId, TID, TUNAME and ClientKey are defined constants in restaurant.php for MerchantID (Username), TerminalID (Terminal ID) and ClientKey(Password)
class GO3
{
	function go3CardBalance($pCardNumber) //Get Card Balance
	{
		$mSessionID = $this->go3Login();
		if ($mSessionID == "Error occurred.")
		{
			return "";
		}
		else
		{
			$mURL = "http://go3gift.com/services/index.php/webservices/inquire/?P01=".$mSessionID."&P02=".TID."&P03=".$pCardNumber;
			$mRetVal = $this->go3DoRequest(2, $mURL);
			$this->go3LogOut($mSessionID);
			return $mRetVal;
		}
	}
	
	function go3RewardPoints($pCardNumber) //Get Reward Points
	{
		$mSessionID = $this->go3Login();
		if ($mSessionID == "Error occurred.")
		{
			return "";
		}
		else
		{
			$mURL = "http://go3gift.com/services/index.php/rewardservices/inquire_points/?P01=".$mSessionID."&P02=".TID."&P03=".$pCardNumber;
			$mRetVal = $this->go3DoRequest(3, $mURL);
			$this->go3LogOut($mSessionID);
			return $mRetVal;
		}
	}
	
	function go3Sale($pCardNumber, $pAmount, $pOrderID) //Deduct amount from Card, $pOrderID =  $cart->order_id (submit_order.php -> notify_customers.php)
	{
		$mSessionID = $this->go3Login();
		if ($mSessionID == "Error occurred.")
		{
			return "Error occurred.";
		}
		else
		{
			$mURL = "http://go3gift.com/services/index.php/webservices/redeem/?P01=".$mSessionID."&P02=".TID."&P03=".$pCardNumber."&P04=".$pAmount."&P05=".$pOrderID;
			$mRetVal = $this->go3DoRequest(4, $mURL);
			$this->go3LogOut($mSessionID);
			return $mRetVal;
		}
	}
	
	function go3AddValue($pCardNumber, $pAmount) //Add reward points
	{
		$mSessionID = $this->go3Login();
		if ($mSessionID == "Error occurred.")
		{
			return "Error occurred.";
		}
		else
		{
			$mURL = "http://go3gift.com/services/index.php/rewardservices/add_points/?P01=".$mSessionID."&P02=".TID."&P03=".$pCardNumber."&P04=".$pAmount;
			$mRetVal = $this->go3DoRequest(5, $mURL);
			$this->go3LogOut($mSessionID);
			return $mRetVal;
		}
	}
	
	function go3SetRegistration($pCardNumber, $pExpDate, $pCVV, $pInitialLoad, $pMobileNumber, $pUserID) //$pUserID for reference number, Exp Date mmyyyy(042015)
   	{
		$mSessionID = $this->go3Login();
		if ($mSessionID == "Error occurred.")
		{
			return "Error occurred.";
		}
		else
		{
			$pMobileNumber = str_replace("-", "", str_replace(")", "", str_replace("(", "", str_replace(" ", "", $pMobileNumber))));
			$mURL = "http://go3gift.com/services/index.php/webservices/activate/?P01=".$mSessionID."&P02=".TID."&P03=".$pCardNumber."&P04=".$pExpDate."&P05=".$pCVV."&P06=".$pInitialLoad."&P07=".$pMobileNumber."&P08=".$pUserID;
			$mRetVal = $this->go3DoRequest(6, $mURL);
			$this->go3LogOut($mSessionID);
			return $mRetVal;
		}
	}
	
	function go3ActivateReward($pCardNumber, $pExpDate, $pCVV, $pUserID) //$pUserID for reference number, Exp Date mmyyyy(042015)
   	{
		$mSessionID = $this->go3Login();
		if ($mSessionID == "Error occurred.")
		{
			return "Error occurred.";
		}
		else
		{
			$pMobileNumber = str_replace("-", "", str_replace(")", "", str_replace("(", "", str_replace(" ", "", $pMobileNumber))));
			$mURL = "http://go3gift.com/services/index.php/rewardservices/activate_rewards/?P01=".$mSessionID."&P02=".TID."&P03=".$pCardNumber."&P04=".$pExpDate."&P05=".$pCVV."&P06=".$pUserID;
			$mRetVal = $this->go3DoRequest(8, $mURL);
			$this->go3LogOut($mSessionID);
			return $mRetVal;
		}
	}
	
	function go3IsCardAlreadyRegistered($pCardNumber) 
	{		
		$result=mysql_fetch_object(	mysql_query("SELECT COUNT(*) AS CardTotal FROM customer_registration WHERE valuetec_card_number=".$pCardNumber));
		if($result->CardTotal>0)
		{
			return true;	
		}
		else
		{
			return false;
		}
	 }
	
	function go3DoRequest($pParam, $pURL)
	{
		Log::write('GO3 CALL URL - GO3 API', 'URL: '.$pURL.', Parameter: '.$pParam, 'go3');
		$mResult = file_get_contents($pURL);
		
		if ($mResult)
		{
			$mXML = simplexml_load_string($mResult); 
			Log::write('GO3 Response - GO3 API', $mResult, 'go3');
			if ($pParam==1) //Login
			{
				if (strval($mXML->ResponseCode)=="0000")
				{
					return strval($mXML->ResponseMessage->SessionID);
				}
				else
				{
					return "Error occurred.";
				}
			}
			else if ($pParam==2) //Card Balance
			{
				if (strval($mXML->ResponseCode)=="0000")
				{
					return strval($mXML->CardBalance);
				}
				else
				{
					return "";
				}
			}
			else if ($pParam==3) //Points
			{
				if (strval($mXML->ResponseCode)=="0000")
				{
					return strval($mXML->ResponseData->RewardBalance);
				}
				else
				{
					return "";
				}
			}
			else if ($pParam==4) //Sale (Redeem/Deduct amount)
			{
				if (strval($mXML->ResponseCode)=="0000")
				{
					return "Success";
				}
				else
				{
					return "Error occurred.";
				}
			}
			else if ($pParam==5) //Add amount/points to card
			{
				if (strval($mXML->ResponseCode)=="0000")
				{
					return "Success";
				}
				else
				{
					return "Error occurred.";
				}
			}
			else if ($pParam==6) //Register A Card
			{
				if (strval($mXML->ResponseCode)=="0000")
				{
					return "Success";
				}
				else if (strval($mXML->ResponseCode)=="1000")
				{
					if (trim(strtolower($mXML->ResponseMessage))=="card is already active") //Because we have to use Card even its already registered with GO3
					{
						return "Success";
					}
					else
					{
						return "Error occurred.";
					}
				}
				else
				{
					return "Error occurred.";
				}
			}
			else if ($pParam==7) //LogOut
			{
				if (strval($mXML->ResponseCode)=="0000")
				{
					return "Success";
				}
				else
				{
					return "Error occurred.";
				}
			}
			else if ($pParam==8) //Activate Rewards for Card
			{
				if (strval($mXML->ResponseCode)=="0000")
				{
					return "Success";
				}
				else
				{
					//return "Error occurred.";
					return "Success";
				}
			}
		}
		else
		{
			Log::write('GO3 NO Response - GO3 API', 'No Reponse', 'go3');
			return "Error occurred.";
		}
	}
	
	function go3Login()
	{		
		$mURL = "http://go3gift.com/services/index.php/webservices/login/?P01=".ServerId."&P02=".TUNAME."&P03=".md5(ClientKey);
		return $this->go3DoRequest(1, $mURL);
	}
	
	function go3LogOut($pSessionID)
	{		
		$mURL = "http://go3gift.com/services/index.php/webervices/logout/?P01=".$pSessionID;
		return $this->go3DoRequest(7, $mURL);
	}
}
?>