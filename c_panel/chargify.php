<?php
 include("../includes/config.php");
 include("../includes/function.php");
 require_once 'classes/chargifyApi.php';
 include("../classes/Log.php");
 
// for Customer Subscription

$type = $_GET['type'];
if(isset($type) && $type=='owner')
{
    restaurantOwner();
}
else if(isset($type) && $type=='reseller')
{
    reseller();
}
function restaurantOwner()
{
    if(!empty($_GET['ids']))
    {
        $sql = mysql_query("Select id,firstname,lastname,email,company_name,address,country,state,city,zip from users where type ='store owner' and id in(".$_GET['ids'].")");

        while($getids = mysql_fetch_assoc($sql))
        {
             $chargify_customer_id = createCustomer($getids['firstname'],$getids['lastname'],$getids['email'],$getids['company_name'],$getids['city'],$getids['state'],$getids['zip'],$getids['country'],$getids['address']);
             if(!empty($chargify_customer_id))
             {
                // echo "Update users set chargify_customer_id =" .$chargify_customer_id ." where id = ".$getids['id'];
                 mysql_query("Update users set chargify_customer_id =" .$chargify_customer_id ." where id = ".$getids['id']);
             }
         }
    }

}

 //for reseller subscrption
function reseller()
{
if(!empty($_GET['ids']))
{
	Log::write(__LINE__.'In Reseller: id:'.$_GET['ids'], $_GET['ids'], 'chargify', 1);
     $sql = mysql_query("Select id,firstname,lastname,email,company_name,address,country,state,city,zip from users where type ='reseller' and chargify_customer_id is null and id in(".$_GET['ids'].")");
     while($getids = mysql_fetch_assoc($sql))
     {
		Log::write(__LINE__.'In Reseller: id:'.$_GET['ids'], print_r($getids,true), 'chargify', 1);
         $chargify_customer_id = createCustomer($getids['firstname'],$getids['lastname'],$getids['email'],$getids['company_name'],$getids['city'],$getids['state'],$getids['zip'],$getids['country'],$getids['address']);
         if(!empty($chargify_customer_id))
         {
            // echo "Update users set chargify_customer_id =" .$chargify_customer_id ." where id = ".$getids['id'];
             mysql_query("Update users set chargify_customer_id =" .$chargify_customer_id ." where id = ".$getids['id']);

             $reseller_subscription = createResellerSubcription($chargify_customer_id);
             mysql_query("Update users set chargify_subcription_id =" .$reseller_subscription ." where id = ".$getids['id']);
             Log::write("Update users set chargify_subcription_id =" .$reseller_subscription ." where id = ".$getids['id'],"",'chargify', 1);
			$query="INSERT INTO chargify_products
                                        SET user_id= '".addslashes($getids['id'])."'
                                                ,product_id	= '3324722'
                                                ,api_access_key='2aRl08rsgL3H3WiWl5ar'
                                                ,site_shared_key='Lh2aYDxDHC5oBUkADFF'
                                                ,return_url='http://easyway-ordering.com/self-signup-wizard/index.php'
                                                ,update_return_url='http://easywayordering.com/self-signup-wizard/handle_update_payment.php'
                                                ,hosted_page_url='https://easyway-ordering.chargify.com/h/3324722/subscriptions/new '
                                                ,premium_account=0
                                                ,status=1
                                ";
             mysql_query($query);
			Log::write($query,"",'chargify', 1);
			$query="INSERT INTO chargify_products
                                        SET user_id= '".addslashes($getids['id'])."'
                                                ,product_id	= '3353278'
                                                ,api_access_key='2aRl08rsgL3H3WiWl5ar'
                                                ,site_shared_key='Lh2aYDxDHC5oBUkADFF'
                                                ,return_url='http://easyway-ordering.com/self-signup-wizard/index.php'
                                                ,update_return_url='http://easywayordering.com/self-signup-wizard/handle_update_payment.php'
                                                ,hosted_page_url='https://easyway-ordering.chargify.com/h/3353278/subscriptions/new '
                                                ,premium_account=1
                                                ,status=1
                                ";
             mysql_query($query);
								Log::write($query,"",'chargify', 1);
         }

     }
}
}


 function createCustomer($first_name,$last_name,$email,$companyName ,$city,$state ,$zip ,$country,$address )
   {
        $paramater = '{"customer":{
        "first_name":"'.$first_name.'",
        "last_name":"'.$last_name.'",
        "email":"'.$email.'",
        "address":"'.$address.'",
        "city":"'.$city.'",
        "state":"'.$state.'",
        "zip":"'.$zip.'",
        "country":"'.$country.'"
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

   function createResellerSubcription($customer_id )
   {
        //$premium = mysql_fetch_object(mysql_query("Select premium_account from chargify_products where"));
        
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $parameters = array();
        $url = "https://easyway-ordering.chargify.com/subscriptions.json";
        
        $parameters ='{"subscription":{
        "product_id":"3393209",
        "customer_id":'.$customer_id.',
        "components": [{
        "component_id":  35267,
        "allocated_quantity": 0
        },{
        "component_id": 39219,
        "allocated_quantity": 0
        }],
        "payment_profile_id":"",
        "payment_collection_method":"invoice"
      }}';

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
        $mResult = (object) $mResult;
        $mResult = (object) $mResult;print_r($mResult);
        $mResult->subscription = (object) $mResult->subscription;

        echo $mResult->subscription->id;
        return $mResult->subscription->id;
   }

?>

