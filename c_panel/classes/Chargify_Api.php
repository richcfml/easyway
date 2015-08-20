<?php
/**
 * Description of Chargify_Api
 *
 * @author Asher
 * @created at 3 june 2013
 */


class Chargify_Api
{
	private $username = '2aRl08rsgL3H3WiWl5ar';
	private $password = 'x';
	
	/*
	*	Curl Execute
	*/
	function curlExec($url,$parameter='', $curlArr=array()){
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		
		if(!array_key_exists('NoUsernamePassword', $curlArr)){
        	curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
		}
		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if(array_key_exists('CURLOPT_POST', $curlArr)){
        	curl_setopt($ch, CURLOPT_POST, $curlArr['CURLOPT_POST']);
		}
		
		if(array_key_exists('CURLOPT_CUSTOMREQUEST', $curlArr)){
        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $curlArr['CURLOPT_CUSTOMREQUEST']);
		}
		
		if($parameter!=''){
        	curl_setopt($ch, CURLOPT_POSTFIELDS, $parameter);
		}

		if(array_key_exists('CURLOPT_HTTPHEADER', $curlArr)){
        	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		}
		
        $mResult = curl_exec($ch);
		curl_close($ch);
        unset($ch);
		return json_decode($mResult);
	}
	
	/*
	*	Create Customer
	*/
   	function createCustomer($first_name,$last_name,$email,$companyName ,$city,$state ,$zip ,$country,$address,$phone )
   	{
	   	global $ChargifyURL;
        $paramater = '{"customer": {"first_name":"'.$first_name.'", "last_name":"'.$last_name.'",
									"email":"'.$email.'","address":"'.$address.'", "city":"'.$city.'",
									"state":"'.$state.'", "zip":"'.$zip.'","country":"'.$country.'",
									"phone":"'.$phone.'"}}';
		
        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Create Customer', print_r($post_array,true), 'chargify', 1);

        $url = $ChargifyURL."customers.json";
        Log::write('CHARGIFY Post Array - Create Customer', 'URL: '.$url, 'chargify', 1);
		
		$mResult = $this->curlExec($url, $paramater, array('CURLOPT_POST'=>1, 'CURLOPT_HTTPHEADER'=>'Y'));
        Log::write('CHARGIFY Response Array - Create Customer', print_r($mResult,true), 'chargify', 1);
        
        $mResult = (object) $mResult;
		$mResult->customer = (object) $mResult->customer;
        return $mResult->customer->id;
   }
	
	/*
	*	Update Customer
	*/
   	function updateCustomer($customer_id,$first_name,$last_name,$email,$companyName ,$city,$state ,$zip ,$country,$address,$phone )
   	{
		global $ChargifyURL;
        $paramater = '{"customer": {"first_name":"'.$first_name.'", "last_name":"'.$last_name.'", "email":"'.$email.'",
									"address":"'.$address.'", "city":"'.$city.'", "state":"'.$state.'", "zip":"'.$zip.'",
									"country":"'.$country.'","phone":"'.$phone.'"}}';
									
        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Update Customer', print_r($post_array,true), 'chargify', 1);

        $url = $ChargifyURL."/customers/".$customer_id.".json";
        
        $mResult = $this->curlExec($url, $paramater, array('CURLOPT_CUSTOMREQUEST'=>'PUT', 'CURLOPT_HTTPHEADER'=>'Y'));
        Log::write('CHARGIFY Response Array - Update Customer', print_r($mResult,true), 'chargify', 1);
        $mResult = (object) $mResult;
   }

   	/*
	*	Get subscription by customer id
	*/
	function getSubscriptionByCustomerId($customer_id)
   	{
   	   	global $ChargifyURL;	   
        $url = $ChargifyURL."subscriptions/".$customer_id.".json";
        Log::write('CHARGIFY Post Array - Get Subscription Url', $url, 'chargify', 1);
        $mResult = $this->curlExec($url);
        
        Log::write('CHARGIFY Response Array - Get Subscription', print_r($mResult,true), 'chargify', 1);
        
        if(!empty($mResult))
        {
            $mResult = (object) $mResult;
            $mResult->subscription = (object) $mResult->subscription;
            $mResult->subscription->product = (object) $mResult->subscription->product;
            $mResult->subscription->customer = (object) $mResult->subscription->customer;
	    	if(empty($mResult->subscription->credit_card))
            {
                $mResult->subscription->credit_card = array('expiration_month' => '','masked_card_number' => '','expiration_year' => '');
            }
            $mResult->subscription->credit_card = (object) $mResult->subscription->credit_card;            
	    	return $mResult->subscription;
        }
        else{ return $mResult; }
    }
   
   	/*
	*	Create Subscription
	*/
	function createSubcription($product_id ,$customer_id,$payment_collection_method,$payment_profile_id,$card_number,$exp_month,$exp_year )
   	{
   	   	global $ChargifyURL;
		
        if($card_number=='')
        {
           $exp_month = '';
           $exp_year = '';
        }
		
        $parameters = array();
        $url = $ChargifyURL."subscriptions.json";
        if($payment_profile_id !="-1" && !empty($payment_profile_id))
        {
            $parameters ='{"subscription":{
            "product_id":'.$product_id.',
            "customer_id":'.$customer_id.',
            "payment_profile_id":'.$payment_profile_id.',
            "payment_collection_method":"'.$payment_collection_method.'"
          }}';
        }
        else
        {
            $parameters ='{"subscription":{
            "product_id":'.$product_id.',
            "customer_id":'.$customer_id.',
            "credit_card_attributes":{
            "full_number":"'.$card_number.'",
            "expiration_month":"'.$exp_month.'",
            "expiration_year":"'.$exp_year.'"
            },
            "payment_collection_method":"'.$payment_collection_method.'"
          }}';
        }
        
        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Create Subscription', print_r($post_array,true), 'chargify', 1);
        
		$mResult = $this->curlExec($url, $parameters, array('CURLOPT_POST'=>1, 'CURLOPT_HTTPHEADER'=>'Y'));
		
        Log::write('CHARGIFY Response Array - Create Subscription', print_r($mResult,true), 'chargify', 1);
	
        if(!empty($mResult->subscription))
        {
            $mResult = (object) $mResult;
            $mResult->subscription = (object) $mResult->subscription;
            $mResult->subscription->product = (object) $mResult->subscription->product;
            $mResult->subscription->customer = (object) $mResult->subscription->customer;
            $mResult->subscription->credit_card = (object) $mResult->subscription->credit_card;
            //echo "<pre>";print_r($mResult);exit;

        }
        return $mResult;
	}

   	/*
	*	Create Reseller Subscription
	*/
	function createResellerSubcription($product_id ,$customer_id,$payment_collection_method,$payment_profile_id,$card_number,$exp_month,$exp_year,$license_quantity )
   	{
        global $ChargifyURL, $ChargifyPremiumComponent, $ChargifyStandardComponent;	   
        
        if($card_number=='')
        {
           $exp_month = '';
           $exp_year = '';
        }
        
        $parameters = array();
        $url = $ChargifyURL."subscriptions.json";
        if($payment_profile_id !="-1" && !empty($payment_profile_id))
        {
            $parameters ='{"subscription":
							{
								"product_id":'.$product_id.', "customer_id":'.$customer_id.', 
								"components": [{
									"component_id": '.$ChargifyPremiumComponent.',
									"allocated_quantity": 0
								},{
									"component_id": '.$ChargifyStandardComponent.',
									"allocated_quantity": 0
            					}],
								"payment_profile_id":'.$payment_profile_id.',
								"payment_collection_method":"'.$payment_collection_method.'"
						  	}}';
        }
        else{
            $parameters ='{"subscription":
							{
								"product_id":'.$product_id.',
								"customer_id":'.$customer_id.',
								"components": [{
									"component_id": '.$ChargifyPremiumComponent.',
									"allocated_quantity": 0
								},{
									"component_id": '.$ChargifyStandardComponent.',
									"allocated_quantity": 0
								}],
				
								"credit_card_attributes":{
								"full_number":"'.$card_number.'",
								"expiration_month":"'.$exp_month.'",
								"expiration_year":"'.$exp_year.'"
							},
							"payment_collection_method":"'.$payment_collection_method.'"
						  }}';
        }

        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Create Reseller Subscription', print_r($post_array, true), 'chargify', 1);
		
		$mResult = $this->curlExec($url, $parameters, array('CURLOPT_POST'=>1, 'CURLOPT_HTTPHEADER'=>'Y'));
        
        Log::write('CHARGIFY Response Array - Get Reseller Subscription', print_r($mResult,true), 'chargify', 1);
        
        if(!empty($mResult->subscription))
        {
            $mResult = (object) $mResult;
            $mResult->subscription = (object) $mResult->subscription;
            $mResult->subscription->product = (object) $mResult->subscription->product;
            $mResult->subscription->customer = (object) $mResult->subscription->customer;
            $mResult->subscription->credit_card = (object) $mResult->subscription->credit_card;
        }
        return $mResult;
	}

   	function updatePaymentProfile($first_name,$last_name,$billingAddress1,$billingAddress2,$city,$state ,$zip ,$country,$payment_profile_id,$exp_month,$exp_year )
   	{
   	   	global $ChargifyURL;	   
        
        $parameters = array();
        $url = $ChargifyURL."payment_profiles/".$payment_profile_id.".json";
        
        $parameters ='{"payment_profile":{
							"billing_address":"'.$billingAddress1.'",
							"billing_address_2":"'.$billingAddress2.'",
							"billing_city":"'.$city.'",
							"billing_country":"'.$country.'",
							"billing_state":"'.$state.'",
							"billing_zip":"'.$zip.'",
							"first_name":"'.$first_name.'",
							"last_name":"'.$last_name.'",
							"expiration_month":"'.$exp_month.'",
							"expiration_year":"'.$exp_year.'"
						  }}';

        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Update Payment Profile', print_r($post_array, true), 'chargify', 1);
		
		$mResult = $this->curlExec($url, $parameters, array('CURLOPT_CUSTOMREQUEST'=>'PUT', 'CURLOPT_HTTPHEADER'=>'Y'));
        
        Log::write('CHARGIFY Response Array - Update Payment Profile', print_r($mResult,true), 'chargify', 1);
        
        $mResult = (object) $mResult;
        $mResult->payment_profile = (object) $mResult->payment_profile;
        return $mResult;
	}
	
	/*
	*	Get Product by Product Id
	*/
   	function getProductById($product_id)
   	{
   	   	global $ChargifyURL;	   
        $url = $ChargifyURL."products/" . $product_id . ".json";
	
        Log::write('CHARGIFY Post Array - Get Product Url', $url, 'chargify', 1);
		
		$mResult = $this->curlExec($url);
        
        Log::write('CHARGIFY Response Array - Get Product', print_r($mResult,true), 'chargify', 1);
        
        if(!empty($mResult))
        {
            $mResult = (object) $mResult; 
            $mResult->product = (object) $mResult->product;
            return $mResult->product;
        }
        else return $mResult;
	}
	
	/*
	*	Cancel subscription by admin using chargify subscription id
	*/
   	function cancelSubcriptionByAdmin($chargify_subcription_id)
   	{
   	   	global $ChargifyURL;
        
        $parameters = array();
        $url = $ChargifyURL."subscriptions/" . $chargify_subcription_id . ".json";

        Log::write('CHARGIFY Post Array - Cancel Subscription By Admin URL', $url, 'chargify', 1);
        
		$mResult = $this->curlExec($url, '', array('CURLOPT_CUSTOMREQUEST'=>'DELETE', 'CURLOPT_HTTPHEADER'=>'Y'));
        Log::write('CHARGIFY Response Array - Cancel Subscription By Admin', print_r($mResult,true), 'chargify', 1);
	
        $mResult = (object) $mResult;
        $mResult->subscription = (object) $mResult->subscription;
        $mResult->subscription->product = (object) $mResult->subscription->product;
        $mResult->subscription->customer = (object) $mResult->subscription->customer;
        $mResult->subscription->credit_card = (object) $mResult->subscription->credit_card; 
        return $mResult;
	}
	
	/*
	*	Cancel subscription by Restowner using chargify subscription id
	*/
   	function cancelSubcriptionByRestowner($chargify_subcription_id)
   	{
   	   	global $ChargifyURL;	   
        
        $parameters = array();
        $url = $ChargifyURL."subscriptions/".$chargify_subcription_id.".json";
        $parameters ='{"subscription":{
            "cancel_at_end_of_period":"true"
          }}';

        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Cancel Subscription By Restaurant Owner URL: \\n'.$url."\n", print_r($post_array, true), 'chargify', 1);
		
		$mResult = $this->curlExec($url, '', array('CURLOPT_CUSTOMREQUEST'=>'DELETE', 'CURLOPT_HTTPHEADER'=>'Y'));
        
        Log::write('CHARGIFY Response Array - Cancel Subscription By Restaurant Owner', print_r($mResult,true), 'chargify', 1);
        
        $mResult = (object) $mResult;
        $mResult->subscription = (object) $mResult->subscription;
        $mResult->subscription->product = (object) $mResult->subscription->product;
        $mResult->subscription->customer = (object) $mResult->subscription->customer;
        $mResult->subscription->credit_card = (object) $mResult->subscription->credit_card; 
        return $mResult;
	}
	
	/*
	*	Reactivate subscription
	*/
   	function reactivateSubcription($chargify_subcription_id,$payment_collection_method,$payment_profile_id,$card_number,$exp_month,$exp_year)
   	{    //echo $chargify_subcription_id;
   	   	global $ChargifyURL;   
        if($card_number=='')
        {
           $exp_month = '';
           $exp_year = '';
        }
        
		$url = $ChargifyURL."subscriptions/".$chargify_subcription_id."/reactivate.json";
		$parameters = array();
		
        if($payment_profile_id !="-1" && !empty($payment_profile_id))
        {
            $parameters ='{"subscription":{
            "payment_profile_id":'.$payment_profile_id.',
            "payment_collection_method":"'.$payment_collection_method.'"
          }}';
        }
        else
        {
            $parameters ='{"subscription":{
            "credit_card_attributes":{
            "full_number":"'.$card_number.'",
            "expiration_month":"'.$exp_month.'",
            "expiration_year":"'.$exp_year.'"
            },
            "payment_collection_method":"'.$payment_collection_method.'"
          }}';
        }

        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Reactivate Subscription URL: \\n'.$url, print_r($post_array, true), 'chargify', 1);
        // echo $parameters;
		
		$mResult = $this->curlExec($url, $parameters, array('CURLOPT_CUSTOMREQUEST'=>'PUT', 'CURLOPT_HTTPHEADER'=>'Y'));
        
        Log::write('CHARGIFY Response Array - Reactivate Subscription', print_r($mResult,true), 'chargify', 1);
        
        if(!empty($mResult->subscription))
        {
            $mResult = (object) $mResult;
            $mResult->subscription = (object) $mResult->subscription;
            $mResult->subscription->product = (object) $mResult->subscription->product;
            $mResult->subscription->customer = (object) $mResult->subscription->customer;
            $mResult->subscription->credit_card = (object) $mResult->subscription->credit_card;
            //echo "<pre>";print_r($mResult);exit;
        }
        else
        {
			$mResult = (object) $mResult;
        }
        return $mResult;
	}

  	//***************cancel vendesta accouny**************//
   	function cancelVendesta($srid)
   	{
   	   	$url = "https://reputation-intelligence-api.vendasta.com/api/v2/account/delete/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=" . $srid;

		Log::write('VENDASTA Post Array - Cancel Vendasta Account URL:', $url, 'vendasta',1);
        $mResult = $this->curlExec($url, '', array('CURLOPT_POST'=>1, 'NoUsernamePassword'=>'N'));
		Log::write('Vendasta Response Array - Cancel Vendasta Account', print_r($mResult,true), 'vendasta',1);
        //return $mResult;
   	}

   	function createVendestaPremium($catname,$country,$rest_address,$rest_city,$rest_state,$rest_zip,$demoAccountFlag,$phone,$Email)
   	{
   	   	$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/account/create/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1";
        
		$parameters = "address=".$rest_address."&city=".$rest_city."&companyName=".$catname."&country=".$country."&state=".$rest_state."&zip=".$rest_zip."&workNumber=".$phone."&email=".$Email."&demoAccountFlag=".$demoAccountFlag."&salesPersonEmail=cwilliams@easywayordering.com";
        
        parse_str($parameters,$post_array);
        Log::write('VENDASTA Post Array - Create Vendasta Premium Account', print_r($post_array, true), 'vendasta',1);
        
		$mResult = $this->curlExec($mURL, $parameters, array('CURLOPT_POST'=>1, 'NoUsernamePassword'=>'N'));
        
        Log::write('Vendasta Response Array - Create Vendasta Premium Account', print_r($mResult,true), 'vendasta',1);
        
        if(isset($mResult->data))
        {
           $mResult = (object) $mResult;
           $mResult->data = (object) $mResult->data;
           $data = $mResult->data;
           $getSrid = $data->srid;
           return $getSrid;
        }
        else{ return ""; }
   	}

   	function createMigration($subcription_id,$product_id )
   	{
   	   	global $ChargifyURL;	   
        
		$paramater = '{"migration":{"product_id": "'.$product_id.'", "include_coupons": 0}}';
        $url = $ChargifyURL."subscriptions/".$subcription_id."/migrations.json";
        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Create Migration URL: \\n'.$url, print_r($post_array, true), 'chargify', 1);
		
		$mResult = $this->curlExec($url, $paramater, array('CURLOPT_POST'=>1, 'CURLOPT_HTTPHEADER'=>'Y'));
        
        Log::write('CHARGIFY Response Array - Create Migration', print_r($mResult,true), 'chargify', 1);
        //return $mResult->customer->id;
   	}
	
   	function allocationQuantity($subcription_id,$quantity,$premium,$status )
   	{
		global $ChargifyURL, $ChargifyPremiumComponent, $ChargifyStandardComponent;
		
		$component_id = ($premium == 1)? $ChargifyPremiumComponent:$ChargifyStandardComponent;
		$quantity = ($quantity<=0)? 0:$quantity;

        if($status == 'suspend'){
            $paramater = '{"allocation":{"proration_downgrade_scheme": "no-prorate","quantity":'.$quantity.'}}';
		}
       	else{
           $paramater = '{"allocation":{"proration_upgrade_scheme": "prorate-delay-capture","quantity":'.$quantity.'}}';
		}
        
		$url = $ChargifyURL."subscriptions/".$subcription_id."/components/".$component_id."/allocations.json";
		$post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Allocation Quantity URL: \\n'.$url, print_r($post_array, true), 'chargify', 1);
		
		$mResult = $this->curlExec($url, $paramater, array('CURLOPT_POST'=>1, 'CURLOPT_HTTPHEADER'=>'Y'));
        Log::write('CHARGIFY Response Array - Allocation Quantity', print_r($mResult,true), 'chargify', 1);
        //return $mResult->customer->id;
	}
	
   	function getallocationQuantity($subcription_id,$premium )
	{
   	   	global $ChargifyURL, $ChargifyPremiumComponent, $ChargifyStandardComponent;
		
		$component_id = ($premium == 1)? $ChargifyPremiumComponent:$ChargifyStandardComponent;
        $url = $ChargifyURL."subscriptions/".$subcription_id."/components/".$component_id."/allocations.json";
       	$post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Get Allocation Quantity URL', $url, 'chargify', 1);
		
		$mResult = $this->curlExec($url);
        
        Log::write('CHARGIFY Response Array - Get Allocation Quantity', print_r($mResult,true), 'chargify', 1);
		//int_r($mResult);
		$mResult = current($mResult);
        if(!empty($mResult->allocation))
        {    
	    	$mResult = (object) $mResult;
            $mResult = (object) $mResult->allocation;
            $mResult = $mResult->quantity;
            return $mResult;       
		}
        else{ return 0; }
    }
   
	function multipleAllocation($subcription_id,$quantityPremium,$quantityStandard,$premium )
	{
		global $ChargifyURL, $ChargifyPremiumComponent, $ChargifyStandardComponent;
		
		if($premium ==1){
			$quantityPremium = $quantityPremium+1;
			if($quantityStandard>0){
				$quantityStandard = $quantityStandard-1;
			}
			$paramater = '{"allocations": [{"component_id": '.$ChargifyPremiumComponent.', "quantity": '.$quantityPremium.' },
						  {"component_id": '.$ChargifyStandardComponent.', "quantity": '.$quantityStandard.' }]}';
		}
		else{
			if($quantityPremium > 0){
				$quantityPremium = $quantityPremium-1;
			}
			$quantityStandard = $quantityStandard+1;
			$paramater = '{"allocations": [{ "component_id": '.$ChargifyPremiumComponent.', "quantity": '.$quantityPremium.' },
						  {"component_id": '.$ChargifyStandardComponent.', "quantity": '.$quantityStandard.'}]}';
		}
		$url = $ChargifyURL."subscriptions/" . $subcription_id . "/allocations.json";
		
		$post_array = json_decode($paramater);
		Log::write('CHARGIFY Post Array - Multiple Allocation URL: \\n'.$url, print_r($post_array, true), 'chargify', 1);
		
		$mResult = $this->curlExec($url, $paramater, array('CURLOPT_POST'=>1,'CURLOPT_HTTPHEADER'=>'Y'));
		Log::write('CHARGIFY Response Array - Multiple Allocation', print_r($mResult,true), 'chargify', 1);
	}
	
	function createVendestaAccount($catname,$rest_address,$rest_city,$rest_state,$rest_zip,$demoAccountFlag)
	{
		$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/account/create/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1";
		$parameters = "address=".$rest_address."&city=".$rest_city."&companyName=".$catname."&country=US&state=".$rest_state."&zip=".$rest_zip;
		$parameters .= "&demoAccountFlag=" . $demoAccountFlag . "&salesPersonEmail=cwilliams@easywayordering.com";
		
		parse_str($parameters,$post_array);
		Log::write('VENDASTA Post Array - Create Vendasta Account', print_r($post_array, true), 'vendasta',1);
		
		$mResult = $this->curlExec($mURL, $parameters, array('CURLOPT_POST'=>1, 'NoUsernamePassword'=>'N'));
		Log::write('VENDASTA Response Array - Create Vendasta Account', print_r($mResult,true), 'vendasta',1);
		$mResult = (object) $mResult;
		
		return $mResult;
	}
	
   	function chargeExtraAmount($subcription_id)
   	{
		global $ChargifyURL;
		$paramater = '{"charge":{"amount": "150.00", "memo": "charges for tablet"}}';
		$post_array = json_decode($paramater);
		
		$url = $ChargifyURL."subscriptions/".$subcription_id."/charges.json";
		Log::write('CHARGIFY Post Array - Create Charges URL: \\n'.$url, print_r($post_array, true), 'chargify', 1);
		
		$mResult = $this->curlExec($url, $paramater, array('CURLOPT_POST'=>1, 'CURLOPT_HTTPHEADER'=>1));
		Log::write('CHARGIFY Response Array - Create Charges for tablet', print_r($mResult,true), 'chargify', 1);
	}
}
?>