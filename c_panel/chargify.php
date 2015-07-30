<?php
 require_once("../includes/config.php");
 require_once 'classes/chargifyApi.php';
 
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
    global $ChargifyURL, $ChargifyResellerProduct, $ChargifyPremiumProduct, $ChargifyStandardProduct, $ChargifyPremiumComponent, $ChargifyStandardComponent;	   
    if(!empty($_GET['ids']))
    {
        $sql = dbAbstract::Execute("Select id,firstname,lastname,email,company_name,address,country,state,city,zip from users where type ='store owner' and id in(".$_GET['ids'].")",1);

        while($getids = dbAbstract::returnAssoc($sql,1))
        {
             $chargify_customer_id = createCustomer($getids['firstname'],$getids['lastname'],$getids['email'],$getids['company_name'],$getids['city'],$getids['state'],$getids['zip'],$getids['country'],$getids['address']);
             if(!empty($chargify_customer_id))
             {
                 dbAbstract::Update("Update users set chargify_customer_id =" .$chargify_customer_id ." where id = ".$getids['id'],1);
             }
         }
    }

}

 //for reseller subscrption
function reseller()
{
    global $ChargifyURL, $ChargifyResellerProduct, $ChargifyPremiumProduct, $ChargifyStandardProduct, $ChargifyPremiumComponent, $ChargifyStandardComponent;	   
if(!empty($_GET['ids']))
{
	Log::write(__LINE__.'In Reseller: id:'.$_GET['ids'], $_GET['ids'], 'chargify', 1);
     $sql = dbAbstract::Execute("Select id,firstname,lastname,email,company_name,address,country,state,city,zip from users where type ='reseller' and chargify_customer_id is null and id in(".$_GET['ids'].")",1);
     while($getids = dbAbstract::returnAssoc($sql,1))
     {
		Log::write(__LINE__.'In Reseller: id:'.$_GET['ids'], print_r($getids,true), 'chargify', 1);
         $chargify_customer_id = createCustomer($getids['firstname'],$getids['lastname'],$getids['email'],$getids['company_name'],$getids['city'],$getids['state'],$getids['zip'],$getids['country'],$getids['address']);
         if(!empty($chargify_customer_id))
         {
             dbAbstract::Update("Update users set chargify_customer_id =" .$chargify_customer_id ." where id = ".$getids['id'],1);

             $reseller_subscription = createResellerSubcription($chargify_customer_id);
             dbAbstract::Update("Update users set chargify_subcription_id =" .$reseller_subscription ." where id = ".$getids['id'],1);
             
			$query="INSERT INTO chargify_products
                                        SET user_id= '".addslashes($getids['id'])."'
                                                ,product_id	= '".$ChargifyStandardProduct."'
                                                ,api_access_key='2aRl08rsgL3H3WiWl5ar'
                                                ,site_shared_key='Lh2aYDxDHC5oBUkADFF'
                                                ,return_url= $ChargifyURL.'self-signup-wizard/index.php'
                                                ,update_return_url= $ChargifyURL.'self-signup-wizard/handle_update_payment.php'
                                                ,hosted_page_url='".$ChargifyURL."h/".$ChargifyStandardProduct."/subscriptions/new '
                                                ,premium_account=0
                                                ,status=1
                                ";
             dbAbstract::Insert($query,1);
			Log::write($query,"",'chargify', 1);
			$query="INSERT INTO chargify_products
                                        SET user_id= '".addslashes($getids['id'])."'
                                                ,product_id	= '".$ChargifyPremiumProduct."'
                                                ,api_access_key='2aRl08rsgL3H3WiWl5ar'
                                                ,site_shared_key='Lh2aYDxDHC5oBUkADFF'
                                                ,return_url= $ChargifyURL.'self-signup-wizard/index.php'
                                                ,update_return_url= $ChargifyURL.'self-signup-wizard/handle_update_payment.php'
                                                ,hosted_page_url='".$ChargifyURL."h/".$ChargifyPremiumProduct."/subscriptions/new '
                                                ,premium_account=1
                                                ,status=1
                                ";
             dbAbstract::Insert($query,1);
								Log::write($query,"",'chargify', 1);
         }

     }
}
}


 function createCustomer($first_name,$last_name,$email,$companyName ,$city,$state ,$zip ,$country,$address )
   {
     global $ChargifyURL, $ChargifyResellerProduct, $ChargifyPremiumProduct, $ChargifyStandardProduct, $ChargifyPremiumComponent, $ChargifyStandardComponent;	   
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

        $url = $ChargifyURL."customers.json";
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
        return $mResult->customer->id;
   }

   function createResellerSubcription($customer_id )
   {
        
        global $ChargifyURL, $ChargifyResellerProduct, $ChargifyPremiumProduct, $ChargifyStandardProduct, $ChargifyPremiumComponent, $ChargifyStandardComponent;	   
        $username = '2aRl08rsgL3H3WiWl5ar';
        $password = 'x';
        $parameters = array();
        $url = $ChargifyURL."subscriptions.json";
        
        $parameters ='{"subscription":{
        "product_id":"'.$ChargifyResellerProduct.'",
        "customer_id":'.$customer_id.',
        "components": [{
        "component_id":  '.$ChargifyStandardComponent.',
        "allocated_quantity": 0
        },{
        "component_id": '.$ChargifyPremiumComponent.',
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

<?php mysqli_close($mysqli);?>