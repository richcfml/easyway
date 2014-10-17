<?
require_once("../includes/config.php");
include("../includes/class.phpmailer.php");

$result = mysql_query("SELECT COUNT(*) AS orders_count, UserID FROM ordertbl WHERE payment_approv=1 GROUP BY UserID") or die(mysql_error());
while($user = mysql_fetch_assoc($result)) {
	mysql_query("UPDATE customer_registration SET orders_count=". $user["orders_count"] . " WHERE id=" . $user["UserID"]) or die(mysql_error());
}

?>