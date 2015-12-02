<?php
require_once("../includes/config.php");
include("../includes/class.phpmailer.php");

// delete all abandoned carts whose orders were placed
$abandoned_carts = dbAbstract::Execute("SELECT * FROM abandoned_carts WHERE status=0",1);
while($cart = dbAbstract::returnAssoc($abandoned_carts,1)) {
	$user_clause = "";
	if($cart["user_id"] > 0) {
		$user_clause = " AND UserID=" . $cart["user_id"];
	}
	$orders = dbAbstract::Execute("
		SELECT COUNT(*) as count, totel 
		FROM ordertbl 
		WHERE cat_id=" . $cart["resturant_id"] . $user_clause . " 
			AND submit_time  BETWEEN SUBDATE('" . $cart["date_added"] . "', INTERVAL 60 MINUTE) AND ADDDATE('" . $cart["date_added"] . "', INTERVAL 60 MINUTE) 
			AND totel=" . $cart["cart_total_amount"],1
	);
	$count = dbAbstract::returnArray($orders,1);
	if($count["count"] > 0) {
                Log::write("Delete from abandoned carts - get_abandoned_orders_analytics.php", "QUERY -- DELETE FROM abandoned_carts WHERE id=" . $cart["id"], 'order', 1 , 'cpanel');
		dbAbstract::Delete("DELETE FROM abandoned_carts WHERE id=" . $cart["id"]);
	}
}
Log::write("Delete all duplicate abandoned carts - get_abandoned_orders_analytics.php", "QUERY --
	CREATE TEMPORARY TABLE temp (id INT);
	INSERT temp (id) 
		SELECT id 
		FROM abandoned_carts ac 
		WHERE 
			EXISTS (
				SELECT * 
				FROM abandoned_carts ac2 
				WHERE ac2.user_id=ac.user_id 
					AND ac.resturant_id=ac2.resturant_id 
					AND ac2.id>ac.id 
					AND ac2.cart_total_amount=ac.cart_total_amount 
					AND ac2.date_added BETWEEN SUBDATE(ac2.date_added, INTERVAL 60 MINUTE) AND ADDDATE(ac2.date_added, INTERVAL 60 MINUTE)
			);
	DELETE FROM abandoned_carts WHERE id IN (SELECT id FROM temp);
	DROP TEMPORARY TABLE IF EXISTS temp;
	", 'order', 1 , 'cpanel');
// delete all duplicate abandoned carts
dbAbstract::Execute("
	CREATE TEMPORARY TABLE temp (id INT);
	INSERT temp (id) 
		SELECT id 
		FROM abandoned_carts ac 
		WHERE 
			EXISTS (
				SELECT * 
				FROM abandoned_carts ac2 
				WHERE ac2.user_id=ac.user_id 
					AND ac.resturant_id=ac2.resturant_id 
					AND ac2.id>ac.id 
					AND ac2.cart_total_amount=ac.cart_total_amount 
					AND ac2.date_added BETWEEN SUBDATE(ac2.date_added, INTERVAL 60 MINUTE) AND ADDDATE(ac2.date_added, INTERVAL 60 MINUTE)
			);
	DELETE FROM abandoned_carts WHERE id IN (SELECT id FROM temp);
	DROP TEMPORARY TABLE IF EXISTS temp;
	",1
);

Log::write("Delete all abandoned carts - get_abandoned_orders_analytics.php", "QUERY -- 
	DELETE FROM abandoned_carts 
	WHERE DATE(dated) < DATE(NOW()) 
		AND ( 
			cart_total_amount<0.01 
			OR (dated NOT BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE())
			OR session_duration_in_seconds=0;
		)
", 'order', 1 , 'cpanel');
// delete all empty abandoned carts
dbAbstract::Delete("
	DELETE FROM abandoned_carts 
	WHERE DATE(dated) < DATE(NOW()) 
		AND ( 
			cart_total_amount<0.01 
			OR (dated NOT BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE())
			OR session_duration_in_seconds=0;
		)
",1);

Log::write("Update abandoned carts status - get_abandoned_orders_analytics.php", "Make yesterdays carts abandoned -- QUERY -- UPDATE abandoned_carts SET status=1 WHERE DATE(dated) < DATE(NOW()) AND status=0;", 'order', 1 , 'cpanel');
// make yesterdays carts abandoned
dbAbstract::Update("UPDATE abandoned_carts SET status=1 WHERE DATE(dated) < DATE(NOW()) AND status=0;",1);

// set exisisting abandoned cart to 0 in analytics table
Log::write("Update analytics - get_abandoned_orders_analytics.php", "Set exisisting abandoned cart to 0 in analytics table -- QUERY -- UPDATE analytics SET abandoned_carts_count_last_month=0, abandoned_carts_count_second_last_month=0;", 'order', 1 , 'cpanel');
dbAbstract::Update("UPDATE analytics SET abandoned_carts_count_last_month=0, abandoned_carts_count_second_last_month=0;",1);

// get abandoned carts of last 30 days
$result = dbAbstract::Execute(
	"SELECT COUNT(*) AS `count`, `resturant_id`
	FROM `abandoned_carts`
	WHERE (date_added BETWEEN CURDATE( ) - INTERVAL 30 DAY AND CURDATE( )) AND status=1
	GROUP BY `resturant_id`
	ORDER BY `resturant_id`
	",1
);
while($data = dbAbstract::returnAssoc($result,1)) {
	$resturant_id = $data["resturant_id"];
	$analytics = dbAbstract::Execute("SELECT id FROM analytics WHERE resturant_id=$resturant_id",1);
	if(!empty($analytics) && (dbAbstract::returnRowsCount($analytics,1) > 0)) {
		$analytics = dbAbstract::returnAssoc($analytics,1);
		echo $data["count"] . " " . $analytics["id"] . " updated<br>";
		dbAbstract::Update(
			"UPDATE `analytics` 
			SET abandoned_carts_count_last_month=". $data["count"] . "
			WHERE id=".  $analytics["id"],1
		);
	} else {
		echo $data["count"] . " " . $analytics["id"] . " inserted<br>";
		dbAbstract::Insert(
			"INSERT INTO `analytics` (resturant_id, abandoned_carts_count_last_month) VALUES ($resturant_id, " . $data["count"] . ")",1
		);
	}
}

// get abandoned carts of last 60 days
$result1 = dbAbstract::Execute(
	"SELECT COUNT(*) AS `count`, `resturant_id`
	FROM `abandoned_carts`
	WHERE date_added BETWEEN CURDATE( ) - INTERVAL 60 DAY AND CURDATE( ) - INTERVAL 30 DAY
	GROUP BY `resturant_id`
	ORDER BY `resturant_id`
	",1
);
while($data = dbAbstract::returnAssoc($result,1)) {
	$resturant_id = $data["resturant_id"];
	$analytics = dbAbstract::Execute("SELECT id FROM analytics WHERE resturant_id=$resturant_id",1);
	if(!empty($analytics) && (dbAbstract::returnRowsCount($analytics,1) > 0)) {
		$analytics = dbAbstract::returnAssoc($analytics,1);
		dbAbstract::Update(
			"UPDATE `analytics` 
			SET abandoned_carts_count_second_last_month=". $data["count"] . "
			WHERE id=".  $analytics["id"],1
		);
	} else {
		dbAbstract::Insert(
			"INSERT INTO `analytics` (resturant_id, abandoned_carts_count_second_last_month) VALUES ($resturant_id, " . $data["count"] . ")",1
		);
	}
}
echo "Abandoned Carts Analytics extracted.";

// send email to author about the cron job execution
$to      = 'gulfam@qualityclix.com';
$subject = 'LIVE - EasyWay - Abandoned Carts Analytics extracted';
$message = 'EasyWay - Abandoned Carts Analytics extracted. @ ' . date("F j, Y, g:i a");

$testmail=new testmail();
$testmail->sendTo($message, $subject, $to, true);

?>
