<?php
error_reporting(E_ALL);
set_time_limit(1000);
ini_set('max_execution_time', 1000);
require_once "includes/config.php";

$mRes= dbAbstract::Execute("SELECT prd_id, item_des FROM product WHERE LENGTH(item_des) > 0 AND LOWER(item_des) LIKE '%proudly featuring boar%' ORDER BY UpdatedOn DESC LIMIT 5000");
$mCount = 0;
while ($mRow = dbAbstract::returnObject($mRes))
{
    $mCount = $mCount + 1;
    echo("<b>Product ID: </b>".$mRow->prd_id."<br /><b>Description: </b>".$mRow->item_des);
    echo("<br /><br />- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - <br /><br />");
}

echo("<b><span style='color: red'>Total BH Products:: ".$mCount."</span></b>");