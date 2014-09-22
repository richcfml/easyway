<?
session_start();

include("../includes/config.php");
include("../includes/class.phpmailer.php");
include("../includes/function.php");
require_once ("../classes/Log.php");

//ini_set('display_errors', 1);

ini_set('max_execution_time', 0);

// delete all abandoned carts whose orders were placed
$abandoned_carts = mysql_query("SELECT * FROM abandoned_carts WHERE status=0");
while($cart = mysql_fetch_assoc($abandoned_carts)) {
	$user_clause = "";
	if($cart["user_id"] > 0) {
		$user_clause = " AND UserID=" . $cart["user_id"];
	}
	$orders = mysql_query("
		SELECT COUNT(*) as count, totel 
		FROM ordertbl 
		WHERE cat_id=" . $cart["resturant_id"] . $user_clause . " 
			AND submit_time  BETWEEN SUBDATE('" . $cart["date_added"] . "', INTERVAL 60 MINUTE) AND ADDDATE('" . $cart["date_added"] . "', INTERVAL 60 MINUTE) 
			AND totel=" . $cart["cart_total_amount"]
	) or die(mysql_error());
	$count = mysql_fetch_assoc($orders);
	if($count["count"] > 0) {
                Log::write("Delete from abandoned carts - get_abandoned_orders_analytics.php", "QUERY -- DELETE FROM abandoned_carts WHERE id=" . $cart["id"], 'order', 1 , 'cpanel');
		mysql_query("DELETE FROM abandoned_carts WHERE id=" . $cart["id"]);
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
	DROP TEMPORARY TABLE IF EXISTS tmep;
	", 'order', 1 , 'cpanel');
// delete all duplicate abandoned carts
mysql_query("
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
	DROP TEMPORARY TABLE IF EXISTS tmep;
	"
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
mysql_query("
	DELETE FROM abandoned_carts 
	WHERE DATE(dated) < DATE(NOW()) 
		AND ( 
			cart_total_amount<0.01 
			OR (dated NOT BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE())
			OR session_duration_in_seconds=0;
		)
");

Log::write("Update abandoned carts status - get_abandoned_orders_analytics.php", "Make yesterdays carts abandoned -- QUERY -- UPDATE abandoned_carts SET status=1 WHERE DATE(dated) < DATE(NOW()) AND status=0;", 'order', 1 , 'cpanel');
// make yesterdays carts abandoned
mysql_query("UPDATE abandoned_carts SET status=1 WHERE DATE(dated) < DATE(NOW()) AND status=0;");

// set exisisting abandoned cart to 0 in analytics table
Log::write("Update analytics - get_abandoned_orders_analytics.php", "Set exisisting abandoned cart to 0 in analytics table -- QUERY -- UPDATE analytics SET abandoned_carts_count_last_month=0, abandoned_carts_count_second_last_month=0;", 'order', 1 , 'cpanel');
mysql_query("UPDATE analytics SET abandoned_carts_count_last_month=0, abandoned_carts_count_second_last_month=0;") or die(mysql_error());

// get abandoned carts of last 30 days
$result = mysql_query(
	"SELECT COUNT(*) AS `count`, `resturant_id`
	FROM `abandoned_carts`
	WHERE (date_added BETWEEN CURDATE( ) - INTERVAL 30 DAY AND CURDATE( )) AND status=1
	GROUP BY `resturant_id`
	ORDER BY `resturant_id`
	"
) or die(mysql_error());
while($data = mysql_fetch_assoc($result)) {
	$resturant_id = $data["resturant_id"];
	$analytics = mysql_query("SELECT id FROM analytics WHERE resturant_id=$resturant_id");
	if(!empty($analytics) && (mysql_num_rows($analytics) > 0)) {
		$analytics = mysql_fetch_assoc($analytics);
		echo $data["count"] . " " . $analytics["id"] . " updated<br>";
		mysql_query(
			"UPDATE `analytics` 
			SET abandoned_carts_count_last_month=". $data["count"] . "
			WHERE id=".  $analytics["id"]
		);
	} else {
		echo $data["count"] . " " . $analytics["id"] . " inserted<br>";
		mysql_query(
			"INSERT INTO `analytics` (resturant_id, abandoned_carts_count_last_month) VALUES ($resturant_id, " . $data["count"] . ")"
		) or die(mysql_error());
	}
}

// get abandoned carts of last 60 days
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
@mysql_close($mysql_conn);
echo "Abandoned Carts Analytics extracted.";

// send email to author about the cron job execution
$to      = 'aliraza@qualityclix.com';
$subject = 'EasyWay - Abandoned Carts Analytics extracted';
$message = 'EasyWay - Abandoned Carts Analytics extracted. @ ' . date("F j, Y, g:i a");

$testmail=new testmail();
$testmail->sendTo($message, $subject, $to, true);

?>