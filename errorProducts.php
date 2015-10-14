<?php
error_reporting(E_ALL);
set_time_limit(1000);
ini_set('max_execution_time', 1000);
require_once "includes/config.php";
$mSQL = "SELECT prd_id, item_des FROM product WHERE LENGTH(item_des) > 0 AND item_des LIKE '%Proudly Featuring Boar%'";

$mResBH = dbAbstract::Execute($mSQL);
$mRecordCount = 0;
while ($mRowBH = dbAbstract::returnObject($mResBH))
{
    echo($mRowBH->prd_id."<br />".$mRowBH->item_des."<br /><br /><br />");
    $mRecordCount = $mRecordCount + 1;
}

echo("<br /><br /><br /><br />".$mRecordCount." Total Records.");
?>