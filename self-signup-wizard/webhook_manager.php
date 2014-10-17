<?
	require_once("../includes/config.php");
	include("../includes/class.phpmailer.php");
	$function_obj = new clsFunctions();
	$objMail = new testmail();
	Log::write("Chargify WebHook - webhook_manager.php", " Request Params:".print_r($_REQUEST,true), 'chargify', 1);
	if(!empty($_REQUEST["payload"]["subscription"]["state"])) {
		$state = $_REQUEST["payload"]["subscription"]["state"];
		$subscription_id = $_REQUEST["payload"]["subscription"]["id"];
		if($state == "canceled") {
			$restaurant = mysql_query("SELECT id, license_id FROM resturants WHERE chargify_subscription_id=$subscription_id");
			if(mysql_num_rows($restaurant) > 0) {
                                $restaurant = mysql_fetch_assoc($restaurant);
				$restaurant_id = $restaurant["id"];
				$license_id = $restaurant["license_id"];
                                
                                $uri = 'https://reputation-intelligence-api.vendasta.com/api/v2/account/delete/?apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&apiUser=ESWY';
                                $delete_vendastaaccount = array();
                                $delete_vendastaaccount = array("customerIdentifier" => "$subscription_id");
                                $postString = $delete_vendastaaccount;

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $uri);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
                                $api_response = curl_exec($ch);
                                $result = json_decode($api_response);
                                if($result['statusCode'] == 200 ){
                                    mysql_query("UPDATE resturants SET vendasta_account_created=0 WHERE id=$restaurant_id");
                                }else{
                                    echo 'Unable to delete vendasta account';
                                    echo $result['message'];
                                    exit;
                                }
                                
				mysql_query("UPDATE licenses SET status='suspended', chargify_subscription_canceled=1 WHERE id=$license_id");
				mysql_query("UPDATE resturants SET chargify_subscription_canceled=1, status=0 WHERE id=$restaurant_id");
				
				$to = "cwilliams@easywayordering.com";
				$to = "aliraza@qualityclix.com";
				$from = "From:admin@easywayordering.com";
				$subject = "Payload Subscription State Change Webhook Invoked";
				$body = "restaurant_id = $restaurant_id and license_id = $license_id " . json_encode($_REQUEST);
				$objMail->sendTo($body, $subject, $to, true);
			}
		}
	}

	$to = "cwilliams@easywayordering.com";
	$to = "aliraza@qualityclix.com";
	$from = "From:admin@easywayordering.com";
	$subject = "Webhook Invoked but nothing happened";
	$body = json_encode($_REQUEST);
	$objMail->sendTo($body, $subject, $to, true);
	
	?>
	
	<script type="text/javascript">
	// var a = {
   // "id":"11756600",
   // "event":"subscription_state_change",
   // "payload":{
      // "subscription":{
         // "activated_at":"",
         // "balance_in_cents":"0",
         // "cancel_at_end_of_period":"false",
         // "canceled_at":"2013-07-16 06:43:21 -0400",
         // "cancellation_message":"no",
         // "created_at":"2013-07-16 04:54:50 -0400",
         // "current_period_ends_at":"2013-08-16 04:54:50 -0400",
         // "expires_at":"",
         // "id":"3612107",
         // "next_assessment_at":"2013-08-16 04:54:50 -0400",
         // "payment_collection_method":"automatic",
         // "state":"canceled",
         // "trial_ended_at":"2013-08-16 04:54:50 -0400",
         // "trial_started_at":"2013-07-16 04:54:50 -0400",
         // "updated_at":"2013-07-16 06:43:21 -0400",
         // "current_period_started_at":"2013-07-16 04:54:50 -0400",
         // "previous_state":"trialing",
         // "signup_payment_id":"36311829",
         // "signup_revenue":"0.00",
         // "delayed_cancel_at":"",
         // "coupon_code":"",
         // "total_revenue_in_cents":"0",
         // "customer":{
            // "address":"",
            // "address_2":"",
            // "city":"",
            // "country":"",
            // "created_at":"2013-07-16 04:54:50 -0400",
            // "email":"irfan@qualityclix.com",
            // "first_name":"irfan",
            // "id":"3459318",
            // "last_name":"khan",
            // "organization":"qualityclix",
            // "phone":"9079807",
            // "reference":"",
            // "state":"",
            // "updated_at":"2013-07-16 04:54:50 -0400",
            // "zip":""
         // },
         // "product":{
            // "accounting_code":"123456",
            // "archived_at":"",
            // "created_at":"2013-07-03 02:45:30 -0400",
            // "description":"A test product for testing",
            // "expiration_interval":"",
            // "expiration_interval_unit":"never",
            // "handle":"easyway",
            // "id":"3318535",
            // "initial_charge_in_cents":"0",
            // "interval":"1",
            // "interval_unit":"month",
            // "name":"Test1",
            // "price_in_cents":"0",
            // "request_credit_card":"true",
            // "require_credit_card":"true",
            // "return_params":"subscription_id={subscription_id}&customer_id={customer_id}&product_id={product_id}",
            // "return_url":"http://easywayordering.com/self-signup-wizard/index.php",
            // "trial_interval":"1",
            // "trial_interval_unit":"month",
            // "trial_price_in_cents":"0",
            // "update_return_url":"",
            // "updated_at":"2013-07-15 07:45:22 -0400",
            // "product_family":{
               // "accounting_code":"",
               // "description":"Monthly unlimited plan",
               // "handle":"easyway-ordering-monthly",
               // "id":"346368",
               // "name":"EasyWay Ordering Monthly"
            // }
         // },
         // "credit_card":{
            // "billing_address":"",
            // "billing_address_2":"",
            // "billing_city":"",
            // "billing_country":"",
            // "billing_state":"",
            // "billing_zip":"40100",
            // "card_type":"visa",
            // "current_vault":"bogus",
            // "customer_id":"3459318",
            // "customer_vault_token":"",
            // "expiration_month":"7",
            // "expiration_year":"2013",
            // "first_name":"irfan",
            // "id":"2170460",
            // "last_name":"siddique",
            // "masked_card_number":"XXXX-XXXX-XXXX-1111",
            // "vault_token":"4111111111111111"
         // }
      // },
      // "site":{
         // "id":"15087",
         // "subdomain":"easywayordering"
      // }
   // }
// }
	</script>