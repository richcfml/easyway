<?
require_once("../includes/config.php");
include("../includes/class.phpmailer.php");

$result = dbAbstract::Execute("SELECT COUNT(*) AS orders_count, UserID FROM ordertbl WHERE payment_approv=1 GROUP BY UserID",1);
while($user = dbAbstract::returnAssoc($result,1)) {
	dbAbstract::Update("UPDATE customer_registration SET orders_count=". $user["orders_count"] . " WHERE id=" . $user["UserID"],1);
}

?>
<?php mysqli_close($mysqli);?>