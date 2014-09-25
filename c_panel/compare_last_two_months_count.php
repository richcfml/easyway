<?
require_once("../includes/config.php");
include("../includes/class.phpmailer.php");

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

$to      = 'aliraza@qualityclix.com';
$subject = 'EasyWay - Compare Last Two Months Cron Job';
$message = 'EasyWay - Compare Last Two Months Cron Job executed @ ' . date("F j, Y, g:i a");

$testmail=new testmail();
$testmail->sendTo($message, $subject, $to, true);

?>