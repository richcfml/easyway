<?php
error_reporting(E_ALL);
set_time_limit(1000);
ini_set('max_execution_time', 1000);
require_once "includes/config.php";

$mRes= dbAbstract::Execute("SELECT prd_id, item_des FROM product WHERE LENGTH(item_des) > 0 AND LOWER(item_des) LIKE '%proudly featuring boar%' LIMIT 2000");

while ($mRow = dbAbstract::returnObject($mRes))
{
    echo($mRow->prd_id."<br />".$mRow->item_des."<br /><br /><br />");
}