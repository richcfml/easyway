<?php
error_reporting(E_ALL);
set_time_limit(1000);
ini_set('max_execution_time', 1000);
require_once "includes/config.php";
$mSQL = "SELECT prd_id, item_des FROM product WHERE LENGTH(item_des) > 0";

$mResBH = dbAbstract::Execute($mSQL);
$mRecordCount = 0;
while ($mRowBH = dbAbstract::returnObject($mResBH))
{
    $mItemDes = strip_tags($mRowBH->item_des, "<br>");
    $mSQL = "UPDATE product SET item_des='".$mItemDes."' WHERE prd_id=".$mRowBH->prd_id;
    dbAbstract::Update($mSQL);    
    $mRecordCount = $mRecordCount + 1;
}

echo($mRecordCount." records updated.");
?>