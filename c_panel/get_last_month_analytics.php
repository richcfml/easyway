<?php
require_once("../includes/config.php");
include("../includes/class.phpmailer.php");

//place this before any script you want to calculate time
$time_start = microtime(true); 

$rows_affected_count = 0;
// fetch all active resturants
$resturants = dbAbstract::Execute("SELECT id FROM `resturants` WHERE status=1",1);
while($resturant = dbAbstract::returnAssoc($resturants,1)) {
	// last 30 days repeat customers, new customers, deliver method, pickup method, 
	// credit card payment, cash payment orders count and total $ amount
	$resturant_id = $resturant["id"];
	//$resturant_id = 205;
	if($resturant_id > 0) {
		$data = array(
			"delivery_orders_count" => 0
			,"pickup_orders_count" => 0
			,"credit_card_orders_count" => 0
			,"cash_orders_count" => 0
			,"website_orders_count" => 0
			,"mobile_orders_count" => 0
			,"rapid_reorders_count" => 0
			,"guests_orders_count" => 0
			
			,"delivery_total_amount" => 0
			,"pickup_total_amount" => 0
			,"credit_card_total_amount" => 0
			,"cash_total_amount" => 0
			,"website_total_amount" => 0
			,"mobile_total_amount" => 0
			,"rapid_reorders_total_amount" => 0
			,"guests_total_amount" => 0
		);
		$data1 = array(
			"repeat_customers_orders_count" => 0
			,"repeat_customers_total_amount" => 0
		);
		$data2 = array(
			"new_customers_orders_count" => 0
			,"new_customers_total_amount" => 0
		);
		$result = dbAbstract::Execute(
			"SELECT 
				COALESCE(sum( if( order_receiving_method='Delivery', 1, 0 ) ), 0) AS delivery_orders_count
				,COALESCE(sum( if( order_receiving_method='Pickup', 1, 0 ) ), 0) AS pickup_orders_count
				,COALESCE(sum( if( payment_method='Credit Card', 1, 0 ) ), 0) AS credit_card_orders_count
				,COALESCE(sum( if( payment_method='Cash', 1, 0 ) ), 0) AS cash_orders_count
				,COALESCE(sum( if( platform_used=1, 1, 0 ) ), 0) AS website_orders_count
				,COALESCE(sum( if( platform_used=2, 1, 0 ) ), 0) AS mobile_orders_count
				,COALESCE(sum( if( platform_used=3, 1, 0 ) ), 0) AS rapid_reorders_count
				,COALESCE(sum( if( is_guest=1, 1, 0 ) ), 0) AS guests_orders_count
				
				,COALESCE(sum( if( order_receiving_method='Delivery', totel, 0 ) ), 0) AS delivery_total_amount
				,COALESCE(sum( if( order_receiving_method='Pickup', totel, 0 ) ), 0) AS pickup_total_amount
				,COALESCE(sum( if( payment_method='Credit Card', totel, 0 ) ), 0) AS credit_card_total_amount
				,COALESCE(sum( if( payment_method='Cash', totel, 0 ) ), 0) AS cash_total_amount
				,COALESCE(sum( if( platform_used=1, totel, 0 ) ), 0) AS website_total_amount
				,COALESCE(sum( if( platform_used=2, totel, 0 ) ), 0) AS mobile_total_amount
				,COALESCE(sum( if( platform_used=3, totel, 0 ) ), 0) AS rapid_reorders_total_amount
				,COALESCE(sum( if( is_guest =1, totel, 0 ) ), 0) AS guests_total_amount
			FROM `ordertbl`
			WHERE OrderDate BETWEEN CURDATE( ) - INTERVAL 60 DAY AND CURDATE( )
				AND cat_id=$resturant_id
				AND payment_approv=1",1
		);
		if(dbAbstract::returnRowsCount($result,1) > 0) {
			$data = dbAbstract::returnAssoc($result,1);
		}

		$result = dbAbstract::Execute(
			"SELECT sum(ot2.repeat_customers_orders_count) AS repeat_customers_orders_count
					,sum(ot2.repeat_customers_total_amount) AS repeat_customers_total_amount
			FROM (
				SELECT COUNT(UserID) AS repeat_customers_orders_count
						,SUM(totel) AS repeat_customers_total_amount
				FROM ordertbl ot, customer_registration cr
				WHERE OrderDate BETWEEN CURDATE( ) - INTERVAL 60 DAY AND CURDATE( )
					AND cat_id=$resturant_id
					AND payment_approv=1
					AND cr.orders_count > 1
					AND cr.id=ot.UserID
					AND ot.is_guest!=1
				GROUP BY `UserID`
			) ot2,1
			"
		);
		if(dbAbstract::returnRowsCount($result,1) > 0) {
			$data1 = dbAbstract::Execute($result,1);
		}

		$result = dbAbstract::Execute(
			"SELECT sum(ot2.new_customers_orders_count) AS new_customers_orders_count
					,sum(ot2.new_customers_total_amount) AS new_customers_total_amount
			FROM (
				SELECT COUNT(UserID) AS new_customers_orders_count
						,SUM(totel) AS new_customers_total_amount
				FROM ordertbl ot, customer_registration cr
				WHERE OrderDate BETWEEN CURDATE( ) - INTERVAL 60 DAY AND CURDATE( )
					AND cat_id=$resturant_id
					AND payment_approv=1
					AND cr.orders_count = 1
					AND cr.id=ot.UserID
					AND ot.is_guest!=1
				GROUP BY `UserID`
			) ot2,1
			"
		);
		if(dbAbstract::returnRowsCount($result,1) > 0) {
			$data2 = dbAbstract::returnAssoc($result,1);
		}
		$data = array_merge($data, $data1, $data2);
		//echo "<pre>"; print_r($data); echo "</pre>"; 
		//die(0);
		$analytics = dbAbstract::Execute("SELECT id FROM analytics WHERE resturant_id=$resturant_id",1);
		if(!empty($analytics) && (dbAbstract::returnRowsCount($analytics,1) > 0)) {
			$analytics = dbAbstract::returnAssoc($analytics,1);
			dbAbstract::Update(
				"UPDATE `analytics` 
				SET repeat_customers_orders_count='". $data["repeat_customers_orders_count"] ."', " .
					"new_customers_orders_count='". $data["new_customers_orders_count"] ."', " .
					"guests_orders_count='". $data["guests_orders_count"] ."', " .
					"delivery_orders_count='". $data["delivery_orders_count"] ."', " .
					"pickup_orders_count='". $data["pickup_orders_count"] ."', " .
					"credit_card_orders_count='". $data["credit_card_orders_count"] ."', " .
					"cash_orders_count='". $data["cash_orders_count"] ."', " .
					"website_orders_count='". $data["website_orders_count"] ."', " .
					"mobile_orders_count='". $data["mobile_orders_count"] ."', " .
					"rapid_reorders_count='". $data["rapid_reorders_count"] ."', " .
					
					"repeat_customers_total_values='". $data["repeat_customers_total_amount"] ."', " .
					"new_customers_total_value='". $data["new_customers_total_amount"] ."', " .
					"guests_orders_total_value='". $data["guests_total_amount"] ."', " .
					"delivery_orders_total_value='". $data["delivery_total_amount"] ."', " .
					"pickup_orders_total_value='". $data["pickup_total_amount"] ."', " .
					"credit_card_orders_total_value='". $data["credit_card_total_amount"] ."', " .
					"website_total_amount='". $data["website_total_amount"] ."', " .
					"mobile_total_amount='". $data["mobile_total_amount"] ."', " .
					"rapid_reorders_total_amount='". $data["rapid_reorders_total_amount"] ."', " .
					"cash_orders_total_value='". $data["cash_total_amount"] . "'
				WHERE id=".  $analytics["id"],1
			);
		} else {
			dbAbstract::Insert(
				"INSERT INTO `analytics` 
				(
					`resturant_id`, 
					`repeat_customers_orders_count`, 
					`new_customers_orders_count`, 
					`guests_orders_count`, 
					`delivery_orders_count`,
					`pickup_orders_count`, 
					`credit_card_orders_count`, 
					`cash_orders_count`,
					`website_orders_count`,
					`mobile_orders_count`,
					`rapid_reorders_count`,
					
					`repeat_customers_total_values`, 
					`new_customers_total_value`, 
					`guests_orders_total_value`, 
					`delivery_orders_total_value`, 
					`pickup_orders_total_value`, 
					`credit_card_orders_total_value`, 
					`website_total_amount`, 
					`mobile_total_amount`, 
					`rapid_reorders_total_amount`, 
					`cash_orders_total_value`
				) VALUES ('" . 
					$resturant_id ."', '" .
					$data["repeat_customers_orders_count"] ."', '" .
					$data["new_customers_orders_count"] ."', '" .
					$data["guests_orders_count"] ."', '" .
					$data["delivery_orders_count"] ."', '" .
					$data["pickup_orders_count"] ."', '" .
					$data["credit_card_orders_count"] ."', '" .
					$data["cash_orders_count"] ."', '" .
					$data["website_orders_count"] ."', '" .
					$data["mobile_orders_count"] ."', '" .
					$data["rapid_reorders_count"] ."', '" .
					
					$data["repeat_customers_total_amount"] ."', '" .
					$data["new_customers_total_amount"] ."', '" .
					$data["guests_total_amount"] ."', '" .
					$data["delivery_total_amount"] ."', '" .
					$data["pickup_total_amount"] ."', '" .
					$data["credit_card_total_amount"] ."', '" .
					$data["website_total_amount"] ."', '" .
					$data["mobile_total_amount"] ."', '" .
					$data["rapid_reorders_total_amount"] ."', '" .
					$data["cash_total_amount"] . "'
				)",1
			);
			
		}
		$rows_affected_count++;
	}
}

$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start)/60;

//execution time of the script
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
echo "<pre>";
echo "Analytics extracted for: " . $rows_affected_count . " resturnats in $execution_time mins";
echo "</pre>";

$to      = 'aliraza@qualityclix.com';
$subject = 'EasyWay - Analytics extracted';
$message = 'EasyWay - Analytics extracted for: ' . $rows_affected_count . ' resturnats @ ' . date("F j, Y, g:i a") . "in $execution_time mins";

$testmail=new testmail();
$testmail->sendTo($message, $subject, $to, true);
mysqli_close($mysqli);?>