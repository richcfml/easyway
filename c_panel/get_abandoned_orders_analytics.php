<?php
require_once("../includes/config.php");
include("../includes/class.phpmailer.php");

//Revamped by Gulfam - 7th December 2015

//Step 1: Delete all abandoned carts whose orders were placed
$mLogID = mt_rand(1, mt_getrandmax());
$mSQL = "SELECT * FROM abandoned_carts WHERE status=0";
Log::write("Get Unhandled Abandoned Carts, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
$abandoned_carts = dbAbstract::Execute($mSQL, 1);
while($cart = dbAbstract::returnAssoc($abandoned_carts, 1)) 
{
    $user_clause = "";
    if($cart["user_id"] > 0) 
    {
        $user_clause = " AND UserID = ".$cart["user_id"];
    }
    
    //Following query fetches those orders of the restaurant of abandoned cart query which were 
    //made in couple of hour duration of abandoned cart entry and having same amount as of
    //abandoned cart and if there is a user then that is matched too.
    $mSQL = "SELECT COUNT(*) as count, totel FROM ordertbl WHERE cat_id=".$cart["resturant_id"].$user_clause." AND submit_time  BETWEEN SUBDATE('".$cart["date_added"]."', INTERVAL 60 MINUTE) AND ADDDATE('".$cart["date_added"]."', INTERVAL 60 MINUTE) AND totel=".$cart["cart_total_amount"];
    Log::write("Get Orders, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
    $orders = dbAbstract::Execute($mSQL, 1);
    $count = dbAbstract::returnArray($orders, 1);
    if($count["count"] > 0) 
    {
        $mSQL = "DELETE FROM abandoned_carts WHERE id=".$cart["id"];
        Log::write("Deleted Completed Carts, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
        dbAbstract::Delete($mSQL, 1);
    }
}

//Step 2: Delete all duplicate abandoned carts
$mSQL = "SELECT DISTINCT AC2.id AS ID FROM abandoned_carts AC1 INNER JOIN abandoned_carts AC2 ON AC2.resturant_id = AC1.resturant_id AND AC2.cart_total_amount = AC1.cart_total_amount AND AC2.date_added BETWEEN SUBDATE(AC1.date_added, INTERVAL 60 MINUTE) AND ADDDATE(AC1.date_added, INTERVAL 60 MINUTE) AND AC2.id > AC1.id";
Log::write("Get Duplicate Abandoned Carts, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
$mReturn = dbAbstract::Execute($mSQL, 1);

while($mRow = dbAbstract::returnObject($mReturn, 1)) 
{
    $mID = $mRow->ID;
    $mSQL = "DELETE FROM abandoned_carts WHERE id = ".$mID;
    Log::write("DELETE Duplicate Abandoned Carts, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
    dbAbstract::Delete($mSQL, 1);
}

//Step 3: delete all empty abandoned carts
$mSQL = "DELETE FROM abandoned_carts WHERE DATE(dated) < DATE(NOW()) AND (cart_total_amount<0.01 OR (dated NOT BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()) OR session_duration_in_seconds=0)";
Log::write("DELETE Empty Abandoned Carts, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
dbAbstract::Delete($mSQL, 1);

//Step 4: Set all other abandoned cart status to 1 (Ideally here we will have only last day's abandoned carts)
$mSQL = "UPDATE abandoned_carts SET status=1 WHERE DATE(dated) < DATE(NOW()) AND status=0";
Log::write("Update Abandoned Carts status, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
dbAbstract::Update($mSQL, 1);

//Step 5: Exisisting abandoned cart to 0 in analytics table
$mSQL = "UPDATE analytics SET abandoned_carts_count_last_month=0, abandoned_carts_count_second_last_month=0";
Log::write("Update Analytics, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
dbAbstract::Update($mSQL, 1);

//Step 6: get abandoned carts of last 30 days
$mSQL = "SELECT COUNT(*) AS `count`, `resturant_id` FROM `abandoned_carts` WHERE (date_added BETWEEN CURDATE( ) - INTERVAL 30 DAY AND CURDATE( )) AND status=1 GROUP BY `resturant_id` ORDER BY `resturant_id`";
Log::write("Get abandoned carts of last 30 days, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
$result = dbAbstract::Execute($mSQL, 1);

while($data = dbAbstract::returnAssoc($result,1)) 
{
    $resturant_id = $data["resturant_id"];
    $mSQL = "SELECT id FROM analytics WHERE resturant_id = ".$resturant_id;
    Log::write("Get IDs from analytics, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
    $analytics = dbAbstract::Execute($mSQL, 1);
    if(!empty($analytics) && (dbAbstract::returnRowsCount($analytics,1) > 0)) 
    {
        $analytics = dbAbstract::returnAssoc($analytics, 1);
        $mSQL = "UPDATE `analytics` SET abandoned_carts_count_last_month=".$data["count"]." WHERE id=".$analytics["id"];
        Log::write("Update analytics, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
        dbAbstract::Update($mSQL, 1);
    } 
    else 
    {
        $mSQL = "INSERT INTO `analytics` (resturant_id, abandoned_carts_count_last_month) VALUES ($resturant_id, ".$data["count"].")";        
        Log::write("Insert analytics, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
        dbAbstract::Insert($mSQL,1);
    }
}

//Step 7: get abandoned carts of last 30 days
$mSQL = "SELECT COUNT(*) AS `count`, `resturant_id` FROM `abandoned_carts` WHERE date_added BETWEEN CURDATE( ) - INTERVAL 60 DAY AND CURDATE( ) - INTERVAL 30 DAY GROUP BY `resturant_id` ORDER BY `resturant_id`";
Log::write("Get abandoned carts of last 60 days, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
$result1 = dbAbstract::Execute($mSQL, 1);

while($data = dbAbstract::returnAssoc($result,1)) 
{
    $resturant_id = $data["resturant_id"];
    $mSQL = "SELECT id FROM analytics WHERE resturant_id = ".$resturant_id;
    Log::write("Get IDs from analytics, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
    $analytics = dbAbstract::Execute($mSQL, 1);
    if(!empty($analytics) && (dbAbstract::returnRowsCount($analytics, 1) > 0)) 
    {
        $analytics = dbAbstract::returnAssoc($analytics,1);
        $mSQL = "UPDATE `analytics` SET abandoned_carts_count_second_last_month=".$data["count"]." WHERE id=".$analytics["id"];
        Log::write("Update analytics, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
        dbAbstract::Update($mSQL, 1);
    } 
    else 
    {
        $mSQL = "INSERT INTO `analytics` (resturant_id, abandoned_carts_count_second_last_month) VALUES ($resturant_id, ".$data["count"].")";
        Log::write("Insert analytics, get_abandoned_orders_analytics.php, Line Number: ".__LINE__.", Time: ".time().", Log ID: ".$mLogID, "QUERY -- ".$mSQL, 'crons', 1, 'c_panel');
        dbAbstract::Insert($mSQL, 1);
    }
}

//Step 8: Send email to developer about the cron job execution
$to      = 'gulfam@qualityclix.com';
$subject = 'LIVE - EasyWay - Abandoned Carts Analytics extracted';
$message = 'EasyWay - Abandoned Carts Analytics extracted. @ ' . date("F j, Y, g:i a");

$testmail=new testmail();
$testmail->sendTo($message, $subject, $to, true);
?>
