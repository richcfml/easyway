<?php

/*
*	ServerId, TID, TUNAME and ClientKey are defined constants in restaurant.php 
*	for MerchantID (Username), TerminalID (Terminal ID) and ClientKey(Password)
*/

class GO3
{
	/*
	*	Get Card Balance
	*/
    function go3CardBalance($pCardNumber)
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
	
	/*
	*	Get Reward Points
	*/
    function go3RewardPoints($pCardNumber)
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
	
	/*
	*	Deduct amount from Card, $pOrderID =  $cart->order_id (submit_order.php -> notify_customers.php)
	*/
    function go3Sale($pCardNumber, $pAmount, $pOrderID)
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
	
	/*
	*	Add reward points
	*/
    function go3AddValue($pCardNumber, $pAmount)
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
	
	/*
	*	$pUserID for reference number, Exp Date mmyyyy(042015)
	*/
    function go3SetRegistration($pCardNumber, $pExpDate, $pCVV, $pInitialLoad, $pMobileNumber, $pUserID)
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
	
	/*
	*	$pUserID for reference number, Exp Date mmyyyy(042015)
	*/
    function go3ActivateReward($pCardNumber, $pExpDate, $pCVV, $pUserID)
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
        $mSQL = "SELECT COUNT(*) AS CardTotal FROM customer_registration WHERE valuetec_card_number=".$pCardNumber;
        $result = dbAbstract::ExecuteObject($mSQL);
        return ($result->CardTotal>0)? true:false;
     }

    function go3DoRequest($pParam, $pURL)
    {
        Log::write('GO3 CALL URL - GO3 API', 'URL: '.$pURL.', Parameter: '.$pParam, 'go3');
        $mResult = file_get_contents($pURL);

        if ($mResult)
        {
            $mXML = simplexml_load_string($mResult); 
            Log::write('GO3 Response - GO3 API', $mResult, 'go3');
			$respCode = strval($mXML->ResponseCode);
			
			switch($pParam){
				//	Login
				case 1:
					return ($respCode=="0000")? strval($mXML->ResponseMessage->SessionID):'Error occurred.';
					break;
				
				//	Card Balance
				case 2:
					return ($respCode=="0000")? strval($mXML->CardBalance):'';
					break;
				
				// Points
				case 3:
					return ($respCode=="0000")? strval($mXML->ResponseData->RewardBalance):'';
					break;
				
				/*
				*	4,5 and 7 has same response
				*	4: Sale (Redeem/Deduct amount)
				*	5: Add amount/points to card
				*	7: LogOut
				*/
				case 4:
				case 5:
				case 7:
				  return ($respCode=="0000")? 'Success':'Error occurred.';
				  break;
				  
				// Register A Card
				case 6:
					if ($respCode=="0000") return "Success";
					elseif($respCode=="1000"){
						//Because we have to use Card even its already registered with GO3
						return (trim(strtolower($mXML->ResponseMessage))=="card is already active")? 'Success':'Error occurred.';
					}
					else return "Error occurred.";
					break;
				
				case 8:
					return 'Success';
					break;	
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