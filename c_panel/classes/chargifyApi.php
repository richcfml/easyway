<?php
/**
 * Description of chargifyApi
 *
 * @author Asher
 * @created at 3 june 2013
 */


class chargifyApi
{
   function createCustomer($first_name,$last_name,$email,$companyName ,$city,$state ,$zip ,$country,$address,$phone )
   {
        $paramater = '{"customer":{
        "first_name":"'.$first_name.'",
        "last_name":"'.$last_name.'",
        "email":"'.$email.'",
        "address":"'.$address.'",
        "city":"'.$city.'",
        "state":"'.$state.'",
        "zip":"'.$zip.'",
        "country":"'.$country.'",
	"phone":"'.$phone.'"
        }}';
        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Create Customer', print_r($post_array,true), 'chargify', 1);

        $url = "https://easyway-ordering.chargify.com/customers.json";
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramater);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
        Log::write('CHARGIFY Response Array - Create Customer', print_r($mResult,true), 'chargify', 1);
        
        $mResult = (object) $mResult;
	$mResult->customer = (object) $mResult->customer;
        //echo "<pre>";print_r($mResult);exit;
        return $mResult->customer->id;
   }

   function updateCustomer($customer_id,$first_name,$last_name,$email,$companyName ,$city,$state ,$zip ,$country,$address,$phone )
   {
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        
        $paramater = '{"customer":{
        "first_name":"'.$first_name.'",
        "last_name":"'.$last_name.'",
        "email":"'.$email.'",
	"address":"'.$address.'",
        "city":"'.$city.'",
        "state":"'.$state.'",
        "zip":"'.$zip.'",
        "country":"'.$country.'",
	"phone":"'.$phone.'"
        }}';
        
        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Update Customer', print_r($post_array,true), 'chargify', 1);

        $url = "https://easyway-ordering.chargify.com/customers/".$customer_id.".json";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramater);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
        Log::write('CHARGIFY Response Array - Update Customer', print_r($mResult,true), 'chargify', 1);
        
        $mResult = (object) $mResult;
        //echo "<pre>";print_r($mResult);exit;
        
   }


   function getSubcription($customer_id)
   {
        $url = "https://easyway-ordering.chargify.com/subscriptions/".$customer_id.".json";

        Log::write('CHARGIFY Post Array - Get Subscription Url', $url, 'chargify', 1);
        
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
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
        else
        {
            return $mResult;
        }
        //echo "<pre>";print_r($mResult->subscription);exit;
   }
   
   function createSubcription($product_id ,$customer_id,$payment_collection_method,$payment_profile_id,$card_number,$exp_month,$exp_year )
   {
        if($card_number=='')
        {
           $exp_month = '';
           $exp_year = '';
        }
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $parameters = array();
        $url = "https://easyway-ordering.chargify.com/subscriptions.json";
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
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
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

   function createResellerSubcription($product_id ,$customer_id,$payment_collection_method,$payment_profile_id,$card_number,$exp_month,$exp_year,$license_quantity )
   {
        //$premium = mysql_fetch_object(mysql_query("Select premium_account from chargify_products where"));
        if($card_number=='')
        {
           $exp_month = '';
           $exp_year = '';
        }
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $parameters = array();
        $url = "https://easyway-ordering.chargify.com/subscriptions.json";
        if($payment_profile_id !="-1" && !empty($payment_profile_id))
        {
            $parameters ='{"subscription":{
            "product_id":'.$product_id.',
            "customer_id":'.$customer_id.',
            "components": [{
            "component_id": 39219,
            "allocated_quantity": 0
            },{
            "component_id": 35267,
            "allocated_quantity": 0
            }],
            "payment_profile_id":'.$payment_profile_id.',
            "payment_collection_method":"'.$payment_collection_method.'"
          }}';
        }
        else
        {
            $parameters ='{"subscription":{
            "product_id":'.$product_id.',
            "customer_id":'.$customer_id.',
            "components": [{
            "component_id": 39219,
            "allocated_quantity": 0
            },{
            "component_id": 35267,
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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
        Log::write('CHARGIFY Response Array - Get Reseller Subscription', print_r($mResult,true), 'chargify', 1);
        
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

   function updatePaymentProfile($first_name,$last_name,$billingAddress1,$billingAddress2,$city,$state ,$zip ,$country,$payment_profile_id,$exp_month,$exp_year )
   {
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $parameters = array();
        $url = "https://easyway-ordering.chargify.com/payment_profiles/".$payment_profile_id.".json";
        
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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
        Log::write('CHARGIFY Response Array - Update Payment Profile', print_r($mResult,true), 'chargify', 1);
        
        $mResult = (object) $mResult;
        $mResult->payment_profile = (object) $mResult->payment_profile;

        //echo "<pre>";print_r($mResult);exit;
        return $mResult;
   }

   function getProduct($product_id)
   {
        $url = "https://easyway-ordering.chargify.com/products/" . $product_id . ".json";
	
        Log::write('CHARGIFY Post Array - Get Product Url', $url, 'chargify', 1);
	
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
	
        $mResult = json_decode($mResult);
        
        Log::write('CHARGIFY Response Array - Get Product', print_r($mResult,true), 'chargify', 1);
        
        if(!empty($mResult))
        {
            $mResult = (object) $mResult; 
            $mResult->product = (object) $mResult->product;
            return $mResult->product;
        }
        else
        {
            return $mResult;
        }
        //echo "<pre>";print_r($mResult->subscription);exit;
   }

   function cancelSubcriptionByAdmin($chargify_subcription_id)
   {

        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $parameters = array();
        $url = "https://easyway-ordering.chargify.com/subscriptions/" . $chargify_subcription_id . ".json";

        Log::write('CHARGIFY Post Array - Cancel Subscription By Admin URL', $url, 'chargify', 1);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);

        curl_close($ch);
        unset($ch);
	
        $mResult = json_decode($mResult);
        Log::write('CHARGIFY Response Array - Cancel Subscription By Admin', print_r($mResult,true), 'chargify', 1);
	
        $mResult = (object) $mResult;
        $mResult->subscription = (object) $mResult->subscription;
        $mResult->subscription->product = (object) $mResult->subscription->product;
        $mResult->subscription->customer = (object) $mResult->subscription->customer;
        $mResult->subscription->credit_card = (object) $mResult->subscription->credit_card; 
        //echo "<pre>";print_r($mResult);exit;
        return $mResult;
   }

   function cancelSubcriptionByRestowner($chargify_subcription_id)
   {
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $parameters = array();
        $url = "https://easyway-ordering.chargify.com/subscriptions/".$chargify_subcription_id.".json";
        $parameters ='{"subscription":{
            "cancel_at_end_of_period":"true"
          }}';

        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Cancel Subscription By Restaurant Owner URL: \\n'.$url."\n", print_r($post_array, true), 'chargify', 1);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
        Log::write('CHARGIFY Response Array - Cancel Subscription By Restaurant Owner', print_r($mResult,true), 'chargify', 1);
        
        $mResult = (object) $mResult;
        $mResult->subscription = (object) $mResult->subscription;
        $mResult->subscription->product = (object) $mResult->subscription->product;
        $mResult->subscription->customer = (object) $mResult->subscription->customer;
        $mResult->subscription->credit_card = (object) $mResult->subscription->credit_card; 
        //echo "<pre>";print_r($mResult);
        return $mResult;
   }

   function reactivateSubcription($chargify_subcription_id,$payment_collection_method,$payment_profile_id,$card_number,$exp_month,$exp_year)
   {    //echo $chargify_subcription_id;
        if($card_number=='')
        {
           $exp_month = '';
           $exp_year = '';
        }

        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $parameters = array();
       
        $url = "https://easyway-ordering.chargify.com/subscriptions/".$chargify_subcription_id."/reactivate.json";

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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
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
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        Log::write('Vendasta Response Array - Cancel Vendasta Account', print_r($mResult,true), 'vendasta',1);
        
        //echo "<pre>";print_r($mResult);exit;
        //return $mResult;
   }

   function createVendestaPremium($catname,$country,$rest_address,$rest_city,$rest_state,$rest_zip,$demoAccountFlag,$phone,$Email)
   {

        $mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/account/create/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1";
        $parameters = "address=".$rest_address."&city=".$rest_city."&companyName=".$catname."&country=".$country."&state=".$rest_state."&zip=".$rest_zip."&workNumber=".$phone."&email=".$Email;
        $parameters = $parameters."&demoAccountFlag=".$demoAccountFlag."&salesPersonEmail=cwilliams@easywayordering.com";
        
        parse_str($parameters,$post_array);
        Log::write('VENDASTA Post Array - Create Vendasta Premium Account', print_r($post_array, true), 'vendasta',1);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $mURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        
        $mResult= json_decode($mResult);
        
        Log::write('Vendasta Response Array - Create Vendasta Premium Account', print_r($mResult,true), 'vendasta',1);
        
        if(isset($mResult->data))
        {
           $mResult = (object) $mResult;
           $mResult->data = (object) $mResult->data;
           $data = $mResult->data;
           $getSrid = $data->srid;
           return $getSrid;
        }
        else
        {
           return "";
        }
   }

   function createMigration($subcription_id,$product_id )
   {
        $paramater = '{"migration":{
         "product_id": "'.$product_id.'",
         "include_coupons": 0
       }}';


        $url = "https://easywayordering.chargify.com/subscriptions/".$subcription_id."/migrations.json";
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';

        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Create Migration URL: \\n'.$url, print_r($post_array, true), 'chargify', 1);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramater);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
        Log::write('CHARGIFY Response Array - Create Migration', print_r($mResult,true), 'chargify', 1);
        //echo "<pre>";print_r($mResult);exit;
        //return $mResult->customer->id;
   }

   function allocationQuantity($subcription_id,$quantity,$premium,$status )
   {
       if($premium ==1)
       {
            $component_id = '39219';
       }
       else
       {
            $component_id = '35267';
       }

       if($quantity<=0)
       {
           $quantity = 0;
       }
        if($status == 'suspend')
       {
            $paramater = '{
              "allocation":{
                "proration_downgrade_scheme": "no-prorate",
                "quantity":'.$quantity.'
              }
            }';
       }
       else
       {
           $paramater = '{
              "allocation":{
                "proration_upgrade_scheme": "prorate-delay-capture",
                "quantity":'.$quantity.'
              }
            }';
       }
        //echo $paramater;
        $url = "https://easyway-ordering.chargify.com/subscriptions/".$subcription_id."/components/".$component_id."/allocations.json";
	//echo $url;
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';

        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Allocation Quantity URL: \\n'.$url, print_r($post_array, true), 'chargify', 1);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramater);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
        Log::write('CHARGIFY Response Array - Allocation Quantity', print_r($mResult,true), 'chargify', 1);
        
        //echo "<pre>";print_r($mResult);exit;
        //return $mResult->customer->id;
   }

   function getallocationQuantity($subcription_id,$premium )
   {
	
       if($premium ==1)
       {
            $component_id = '39219';
       }
       else
       {
            $component_id = '35267';
       }

        $url = "https://easyway-ordering.chargify.com/subscriptions/".$subcription_id."/components/".$component_id."/allocations.json";
       	
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';

        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Get Allocation Quantity URL', $url, 'chargify', 1);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
	
        $mResult = json_decode($mResult);
        Log::write('CHARGIFY Response Array - Get Allocation Quantity', print_r($mResult,true), 'chargify', 1);
	//int_r($mResult);
	$mResult = current($mResult);
        if(!empty($mResult->allocation))
        {    
	    $mResult = (object) $mResult;
            $mResult = (object) $mResult->allocation;
            $mResult = $mResult->quantity;
            //echo "<pre>";print_r($mResult);exit;
            return $mResult;       
	 }
        else
        {
            return 0;
        }
        //echo "<pre>";print_r($mResult->quantity);exit;
        
   }
   function multipleAllocation($subcription_id,$quantityPremium,$quantityStandard,$premium )
   {
      
       if($premium ==1)
       {
         $quantityPremium = $quantityPremium+1;
         if($quantityStandard>0)
         {
            $quantityStandard = $quantityStandard-1;
         }
         $paramater = '{
        "allocations": [
          { "component_id": 39219, "quantity": '.$quantityPremium.' },
          { "component_id": 35267, "quantity": '.$quantityStandard.' }
        ]}';
       }
       else
       {
         if($quantityPremium>0)
         {
            $quantityPremium = $quantityPremium-1;
         }
         $quantityStandard = $quantityStandard+1;
         $paramater = '{
        "allocations": [
          { "component_id": 39219, "quantity": '.$quantityPremium.' },
          { "component_id": 35267, "quantity": '.$quantityStandard.'}
        ]
      }';
       }

        $url = "https://easyway-ordering.chargify.com/subscriptions/" . $subcription_id . "/allocations.json";

        $post_array = json_decode($paramater);
        Log::write('CHARGIFY Post Array - Multiple Allocation URL: \\n'.$url, print_r($post_array, true), 'chargify', 1);

        //echo $url;
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramater);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $mResult = json_decode($mResult);
        
        Log::write('CHARGIFY Response Array - Multiple Allocation', print_r($mResult,true), 'chargify', 1);
        //echo "<pre>";print_r($mResult);exit;
        //return $mResult->customer->id;
   }

   function createVendestaAccount($catname,$rest_address,$rest_city,$rest_state,$rest_zip,$demoAccountFlag)
   {
        $mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/account/create/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1";
        $parameters = "address=".$rest_address."&city=".$rest_city."&companyName=".$catname."&country=US&state=".$rest_state."&zip=".$rest_zip;
        $parameters = $parameters . "&demoAccountFlag=" . $demoAccountFlag . "&salesPersonEmail=cwilliams@easywayordering.com";

        parse_str($parameters,$post_array);
        Log::write('VENDASTA Post Array - Create Vendasta Account', print_r($post_array, true), 'vendasta',1);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $mURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);

        $mResult = curl_exec($ch);
        curl_close($ch);
        unset($ch);

        $mResult= json_decode($mResult);
        Log::write('VENDASTA Response Array - Create Vendasta Account', print_r($mResult,true), 'vendasta',1);
        
        $mResult = (object) $mResult;
        return $mResult;
   }
}
?>
