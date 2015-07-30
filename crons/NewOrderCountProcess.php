<?php
require_once("../includes/config.php"); 
$ordersCountFor1To30Days = dbAbstract::Execute("SELECT cat_id, count( * ) as order_count
    FROM ordertbl
    WHERE OrderDate BETWEEN CURDATE( ) - INTERVAL 30 DAY AND CURDATE( )
    AND payment_approv = 1
    Group by ordertbl.cat_id");

$ordersCountFor31To60Days = dbAbstract::Execute("SELECT cat_id, count( * ) as order_count
FROM ordertbl
WHERE OrderDate BETWEEN CURDATE( ) - INTERVAL 60 DAY AND CURDATE( ) - INTERVAL 30 DAY
AND payment_approv = 1
Group by ordertbl.cat_id");

$restaurantCountArray = array();
$restaurantIdArray = array();

while ($attr = dbAbstract::returnArray($ordersCountFor1To30Days)) {
    $restaurantIdArray[] = $attr['cat_id'];
    $restaurantCountArray[$attr['cat_id']]['0'] = $attr['cat_id'];
    $restaurantCountArray[$attr['cat_id']]['1'] = $attr['order_count'];
    $restaurantCountArray[$attr['cat_id']]['2'] = 0;
}

while ($attr = dbAbstract::returnArray($ordersCountFor31To60Days)) {
    if (!isset($restaurantCountArray[$attr['cat_id']]['0'])) {
        $restaurantIdArray[] = $attr['cat_id'];
        $restaurantCountArray[$attr['cat_id']]['0'] = $attr['cat_id'];
    }
    if (!isset($restaurantCountArray[$attr['cat_id']]['1'])) {
        $restaurantCountArray[$attr['cat_id']]['1'] = 0;
    }
    $restaurantCountArray[$attr['cat_id']]['2'] = $attr['order_count'];
}


foreach ($restaurantCountArray as $data)
{   
    $updateQuery = 'UPDATE resturants set orders_last_month_count = ' . $data[1] . ', orders_last_but_second_month_count = ' . $data[2] . ' Where id = ' . $data[0];
    $result = dbAbstract::Update($updateQuery);
}

/** Following code is used to update records for restaurants whose order not found for last two months **/
$implodeArray = implode(', ', $restaurantIdArray);

$otherRestaurantIds = dbAbstract::Update("UPDATE resturants set orders_last_month_count = 0, orders_last_but_second_month_count = 0 Where id NOT in ($implodeArray)");
?>
<?php mysqli_close($mysqli);?>