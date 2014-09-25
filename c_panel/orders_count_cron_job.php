<?
require_once("../includes/config.php");

if($_REQUEST["type"] == "compare_last_two_months_count") {
	mysql_query("
		update resturants 
			set orders_last_month_count=(
					SELECT count( * )
						FROM ordertbl
						WHERE OrderDate BETWEEN CURDATE( ) - INTERVAL 30 DAY AND CURDATE( )
						AND cat_id =resturants.id
						AND payment_approv =1),
					orders_last_but_second_month_count=(
						SELECT count( * )
						FROM ordertbl
						WHERE OrderDate BETWEEN CURDATE( ) - INTERVAL 60 DAY AND CURDATE( ) - INTERVAL 30 DAY
						AND cat_id =resturants.id
						AND payment_approv =1)");

} else if($_REQUEST["type"] == "get_last_month_analytics") {
	$rows_affected_count = 0;
	// fetch all active resturants
	$resturants = mysql_query("SELECT id FROM `resturants` WHERE status=1");
	while($resturant = mysql_fetch_assoc($resturants)) {
		// last 30 days repeat customers, new customers, deliver method, pickup method, 
		// credit card payment, cash payment orders count and total $ amount
		$resturant_id = $resturant["id"];
		if($resturant_id > 0) {
			$result = mysql_query(
				"SELECT COALESCE(sum( if( ot2.Rows >1, 1, 0 ) ), 0) AS repeat_customers_orders_count, 
						COALESCE(sum( if( ot2.Rows=1, 1, 0 ) ), 0) AS new_customers_orders_count, 
						COALESCE(sum( if( ot2.order_receiving_method='Delivery', 1, 0 ) ), 0) AS delivery_orders_count, 
						COALESCE(sum( if( ot2.order_receiving_method='Pickup', 1, 0 ) ), 0) AS pickup_orders_count,
						COALESCE(sum( if( ot2.payment_method='Credit Card', 1, 0 ) ), 0) AS credit_card_orders_count,
						COALESCE(sum( if( ot2.payment_method='Cash', 1, 0 ) ), 0) AS cash_orders_count,
						COALESCE(sum( if( ot2.platform_used=1, 1, 0 ) ), 0) AS website_orders_count,
						COALESCE(sum( if( ot2.platform_used=2, 1, 0 ) ), 0) AS mobile_orders_count,
						COALESCE(sum( if( ot2.platform_used=3, 1, 0 ) ), 0) AS rapid_reorders_count,
					
						COALESCE(sum( if( ot2.Rows >1, ot2.totel, 0 ) ), 0) AS repeat_customers_total_amount, 
						COALESCE(sum( if( ot2.Rows =1, ot2.totel, 0 ) ), 0) AS new_customers_total_amount, 
						COALESCE(sum( if( ot2.order_receiving_method='Delivery', ot2.totel, 0 ) ), 0) AS delivery_total_amount, 
						COALESCE(sum( if( ot2.order_receiving_method='Pickup', ot2.totel, 0 ) ), 0) AS pickup_total_amount, 
						COALESCE(sum( if( ot2.payment_method='Credit Card', ot2.totel, 0 ) ), 0) AS credit_card_total_amount, 
						COALESCE(sum( if( ot2.payment_method='Cash', ot2.totel, 0 ) ), 0) AS cash_total_amount,
						COALESCE(sum( if( ot2.platform_used=1, ot2.totel, 0 ) ), 0) AS webisite_total_amount,
						COALESCE(sum( if( ot2.platform_used=2, ot2.totel, 0 ) ), 0) AS mobile_total_amount,
						COALESCE(sum( if( ot2.platform_used=3, ot2.totel, 0 ) ), 0) AS rapid_reorders_total_amount
				FROM (
					SELECT COUNT( * ) AS `Rows`, ot1. *
					FROM `ordertbl` ot1
					WHERE OrderDate BETWEEN CURDATE( ) - INTERVAL 30 DAY AND CURDATE( )
					AND cat_id=$resturant_id
					AND payment_approv=1
					GROUP BY `UserID`
				)ot2
				"
			);
			$data = mysql_fetch_assoc($result);
			
			$analytics = mysql_query("SELECT id FROM analytics WHERE resturant_id=$resturant_id");
			if(!empty($analytics) && (mysql_num_rows($analytics) > 0)) {
				$analytics = mysql_fetch_assoc($analytics);
				mysql_query(
					"UPDATE `analytics` 
					SET repeat_customers_orders_count=". $data["repeat_customers_orders_count"] .", " .
						"new_customers_orders_count=". $data["new_customers_orders_count"] .", " .
						"delivery_orders_count=". $data["delivery_orders_count"] .", " .
						"pickup_orders_count=". $data["pickup_orders_count"] .", " .
						"credit_card_orders_count=". $data["credit_card_orders_count"] .", " .
						"cash_orders_count=". $data["cash_orders_count"] .", " .
						"website_orders_count=". $data["website_orders_count"] .", " .
						"mobile_orders_count=". $data["mobile_orders_count"] .", " .
						"rapid_reorders_count=". $data["rapid_reorders_count"] .", " .
						
						"repeat_customers_total_values=". $data["repeat_customers_total_amount"] .", " .
						"new_customers_total_value=". $data["new_customers_total_amount"] .", " .
						"delivery_orders_total_value=". $data["delivery_total_amount"] .", " .
						"pickup_orders_total_value=". $data["pickup_total_amount"] .", " .
						"credit_card_orders_total_value=". $data["credit_card_total_amount"] .", " .
						"webisite_total_amount=". $data["webisite_total_amount"] .", " .
						"mobile_total_amount=". $data["mobile_total_amount"] .", " .
						"rapid_reorders_total_amount=". $data["rapid_reorders_total_amount"] .", " .
						"cash_orders_total_value=". $data["cash_total_amount"] . "
					WHERE id=".  $analytics["id"]
				);
			} else {
				mysql_query(
					"INSERT INTO `analytics` 
					(
						`resturant_id`, 
						`repeat_customers_orders_count`, 
						`new_customers_orders_count`, 
						`delivery_orders_count`,
						`pickup_orders_count`, 
						`credit_card_orders_count`, 
						`cash_orders_count`,
						`website_orders_count`,
						`mobile_orders_count`,
						`rapid_reorders_count`,
						
						`repeat_customers_total_values`, 
						`new_customers_total_value`, 
						`delivery_orders_total_value`, 
						`pickup_orders_total_value`, 
						`credit_card_orders_total_value`, 
						`webisite_total_amount`, 
						`mobile_total_amount`, 
						`rapid_reorders_total_amount`, 
						`cash_orders_total_value`
					) VALUES (" . 
						$resturant_id .", " .
						$data["repeat_customers_orders_count"] .", " .
						$data["new_customers_orders_count"] .", " .
						$data["delivery_orders_count"] .", " .
						$data["pickup_orders_count"] .", " .
						$data["credit_card_orders_count"] .", " .
						$data["cash_orders_count"] .", " .
						$data["website_orders_count"] .", " .
						$data["mobile_orders_count"] .", " .
						$data["rapid_reorders_count"] .", " .
						
						$data["repeat_customers_total_amount"] .", " .
						$data["new_customers_total_amount"] .", " .
						$data["delivery_total_amount"] .", " .
						$data["pickup_total_amount"] .", " .
						$data["credit_card_total_amount"] .", " .
						$data["webisite_total_amount"] .", " .
						$data["mobile_total_amount"] .", " .
						$data["rapid_reorders_total_amount"] .", " .
						$data["cash_total_amount"] . "
					)"
				) or die(mysql_error());
				
			}
			$rows_affected_count++;
		}
	}
//$result = mysql_query("SELECT * FROM analytics") or die(mysql_error());
echo "<pre>";
//while($obj = mysql_fetch_object($result)) {
//	print_r($obj);
//}
	
	echo "Analytics extracted for: " . $rows_affected_count . " resturnats";
	echo "</pre>";
} else if($_REQUEST["type"] == "get_last_month_analytics_from_google") {

	require_once 'google-api/Google_Client.php';
	require_once 'google-api/contrib/Google_AnalyticsService.php';

	$client = new Google_Client();
	$client->setApplicationName("Google Analytics PHP Starter Application");

	// Visit //code.google.com/apis/console?api=analytics to generate your
	// client id, client secret, and to register your redirect uri.
	$client->setClientId('668969395261-i6udf38kgm4chao3cm1v95b4stibgo2s.apps.googleusercontent.com');
	$client->setClientSecret('XIIgkLfbIYxLmTWKp02e9K60');
	$client->setRedirectUri('http://localhost/google-api/analytics/index.php');
	//$client->setDeveloperKey('insert_your_developer_key');
	$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

	if (isset($_GET['logout'])) {
	  unset($_SESSION['token']);
	}

	if (isset($_GET['code'])) {
	  $client->authenticate();
	  $_SESSION['token'] = $client->getAccessToken();
	  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	}

	if (isset($_SESSION['token'])) {
	  $client->setAccessToken($_SESSION['token']);
	}

	if ($client->getAccessToken()) {

		$client->setUseObjects(true);

		$service = new Google_AnalyticsService($client);
		$profile_id = urldecode("ga:72879926");
		$start_date = date('Y-m-d', strtotime('-30 days'));
		$end_date = date('Y-m-d');
		
		$analytics = mysql_query("SELECT r.id, r.url_name FROM `resturants` r, `analytics` a WHERE r.id = a.resturant_id ORDER BY a.dated LIMIT 10");
		if(!empty($analytics) && (mysql_num_rows($analytics) > 0)) {
			while($row = mysql_fetch_assoc($analytics)) {
				//get total views of a resturant
				$resturant_slug = str_replace(',', '', $row["url_name"]);
				//$resturant_slug = "slice_pizza";

				$data = array();
				
				// get total visits by a mobile and desktop and average time on site, bounce rate
				$response = $service->data_ga->get(
							$profile_id,
							$start_date,
							$end_date,
							'ga:visits, ga:avgTimeOnPage, ga:entranceBounceRate',
							array(
								'dimensions' => "ga:isMobile",
								'filters' => "ga:pagePath=@$resturant_slug"
							)
						);
				if(!empty($response->rows)) {
					$data["desktop_traffic_count"] = (isset($response->rows[0][1]) ?  $response->rows[0][1] : 0);
					$data["mobile_traffic_count"] = (isset($response->rows[1][1]) ?  $response->rows[1][1] : 0);
					$data["total_visits"] = $response->totalsForAllResults["ga:visits"];
					$data["average_time_on_site"] = intval($response->totalsForAllResults["ga:avgTimeOnPage"]);
					$data["bounce_rate"] = intval($response->totalsForAllResults["ga:entranceBounceRate"]);
				}
				
				// get total visits last but second month of a resturant
				$response = $service->data_ga->get(
							$profile_id,
							date('Y-m-d', strtotime('-60 days')),
							date('Y-m-d', strtotime('-30 days')),
							'ga:visits',
							array(
								'filters' => "ga:pagePath=@$resturant_slug"
							)
						);
				if(!empty($response->totalsForAllResults)) {
					$data["total_visits_second_last_month"] = $response->totalsForAllResults["ga:visits"];
				}
				
				// get total visits by a referrals and referrals urls
				$response = $service->data_ga->get(
							$profile_id,
							$start_date,
							$end_date,
							'ga:visits',
							array(
								'dimensions' => "ga:source",
								'filters' => "ga:pagePath=@$resturant_slug"
							)
						);
				if(!empty($response->rows)) {
					$data["referring_traffic_sources"] = json_encode($response->rows);
				}
				
				// get refering keywords 
				$response = $service->data_ga->get(
							$profile_id,
							$start_date,
							$end_date,
							'ga:visits',
							array(
								'dimensions' => "ga:keyword",
								'filters' => "ga:pagePath=@$resturant_slug"
							)
						);
				if(!empty($response->rows)) {
					$data["referring_keywords_by_search_engine"] = json_encode($response->rows);
				}
				
				// get social media referrals count and names
				$response = $service->data_ga->get(
							$profile_id,
							$start_date,
							$end_date,
							'ga:visits',
							array(
								'dimensions' => "ga:hasSocialSourceReferral, ga:socialNetwork",
								'filters' => "ga:pagePath=@$resturant_slug"
							)
						);
				if(!empty($response->rows)) {
					$data["social_media_referrals"] = json_encode($response->rows);
				}
				
				if(!empty($data)) {
					$qry = "";
					foreach($data as $key => $val) {
						$qry .= "`" . $key . "`='" . $val . "', ";
					}
					$qry .= "dated=NOW()";
					
					mysql_query(
						"UPDATE `analytics` 
						SET $qry
						WHERE resturant_id=".  $row["id"]
					) or die(mysql_error());
					
					echo $resturant_slug . "<br>";
					echo "<pre>";print_r($data);echo "</pre>";
				} else {
					mysql_query(
						"UPDATE `analytics` 
						SET dated=NOW()
						WHERE resturant_id=".  $row["id"]);
					echo "No data found for: " . $resturant_slug . "<br>";
				}
			}
		}
		$_SESSION['token'] = $client->getAccessToken();
	} else {
		$authUrl = $client->createAuthUrl();
		print "<a class='login' href='$authUrl'>Connect Me!</a>";
	}
} else if($_REQUEST["type"] == "get_abondand_cart_analytics") {
	
	$result = mysql_query(
		"SELECT COUNT(*) AS `count`, `resturant_id`
		FROM `abandoned_carts`
		WHERE date_added BETWEEN CURDATE( ) - INTERVAL 30 DAY AND CURDATE( )
		GROUP BY `resturant_id`
		ORDER BY `resturant_id`
		"
	) or die(mysql_error());
	while($data = mysql_fetch_assoc($result)) {
		$resturant_id = $data["resturant_id"];
		$analytics = mysql_query("SELECT id FROM analytics WHERE resturant_id=$resturant_id");
		if(!empty($analytics) && (mysql_num_rows($analytics) > 0)) {
			$analytics = mysql_fetch_assoc($analytics);
			mysql_query(
				"UPDATE `analytics` 
				SET abandoned_carts_count_last_month=". $data["count"] . "
				WHERE id=".  $analytics["id"]
			);
		} else {
			mysql_query(
				"INSERT INTO `analytics` (resturant_id, abandoned_carts_count_last_month) VALUES ($resturant_id, " . $data["count"] . ")"
			) or die(mysql_error());
		}
	}
	
	$result1 = mysql_query(
		"SELECT COUNT(*) AS `count`, `resturant_id`
		FROM `abandoned_carts`
		WHERE date_added BETWEEN CURDATE( ) - INTERVAL 60 DAY AND CURDATE( ) - INTERVAL 30 DAY
		GROUP BY `resturant_id`
		ORDER BY `resturant_id`
		"
	);
	while($data = mysql_fetch_assoc($result)) {
		$resturant_id = $data["resturant_id"];
		$analytics = mysql_query("SELECT id FROM analytics WHERE resturant_id=$resturant_id");
		if(!empty($analytics) && (mysql_num_rows($analytics) > 0)) {
			$analytics = mysql_fetch_assoc($analytics);
			mysql_query(
				"UPDATE `analytics` 
				SET abandoned_carts_count_second_last_month=". $data["count"] . "
				WHERE id=".  $analytics["id"]
			);
		} else {
			mysql_query(
				"INSERT INTO `analytics` (resturant_id, abandoned_carts_count_second_last_month) VALUES ($resturant_id, " . $data["count"] . ")"
			) or die(mysql_error());
		}
	}
	echo "Abandoned Carts Analytics extracted.";
}

?>