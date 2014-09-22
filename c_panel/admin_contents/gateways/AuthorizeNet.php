<?php 
	    require_once '../lib/authorize_api/AuthorizeNet.php';
		define("AUTHORIZENET_API_LOGIN_ID",$Objrestaurant->authoriseLoginID);    // Add your API LOGIN ID
		define("AUTHORIZENET_TRANSACTION_KEY",$Objrestaurant->transKey); // Add your API transaction key
		define("AUTHORIZENET_SANDBOX",true);       // Set to false to test against production
		define("TEST_REQUEST", "true");           // You may want to set to true if testing against production
		define("AUTHORIZENET_MD5_SETTING","");
		//$transactionid ='2204481791';
		//$cc = '1000';
		//$amount='26.86';
		
		$transaction = new AuthorizeNetAIM;
		//Modified01082013
		//$response = $transaction->void($transactionid);
		$response = $transaction->credit($transactionid,$amount,$cc);
                
		if($response->approved)
		 $success=1;
		 $i = 1;
                $response_array = array();
                foreach ($response as $key => $value) {
			
		      if($i!=1){
		        $response_array[$key] = $value;
		      }
		      $i++;
                }

                $result = $success == 1 ? 'Success' : 'Failure';
                Log::write("AuthorizeNet Response Array - Refund \nResult: ".$result, print_r($response_array, true), 'AuthorizeNet', 1);
			  
		$transaction_id=$response->transaction_id;
		$message=$response->response_reason_text;

?>