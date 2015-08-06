<?php
	require_once("../includes/config.php");
	include("../includes/class.phpmailer.php");
	$function_obj = new clsFunctions();
	$objMail = new testmail();
	Log::write("Chargify WebHook - webhook_manager.php", " Request Params:".print_r($_REQUEST,true), 'chargify', 1);
	if(!empty($_REQUEST["payload"]["subscription"]["state"])) {
		$state = $_REQUEST["payload"]["subscription"]["state"];
		$subscription_id = $_REQUEST["payload"]["subscription"]["id"];
		if($state == "canceled") {
			$restaurant = dbAbstract::Execute("SELECT id, license_id FROM resturants WHERE chargify_subscription_id=$subscription_id");
			if(dbAbstract::returnRowsCount($restaurant) > 0) {
                                $restaurant = dbAbstract::returnAssoc($restaurant);
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
                                    dbAbstract::Update("UPDATE resturants SET vendasta_account_created=0 WHERE id=$restaurant_id");
                                }else{
                                    echo 'Unable to delete vendasta account';
                                    echo $result['message'];
                                    exit;
                                }
                                
				dbAbstract::Update("UPDATE licenses SET status='suspended', chargify_subscription_canceled=1 WHERE id=$license_id");
				dbAbstract::Update("UPDATE resturants SET chargify_subscription_canceled=1, status=0 WHERE id=$restaurant_id");
				
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
