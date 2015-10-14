<?php
error_reporting(E_ALL);
set_time_limit(1000);
ini_set('max_execution_time', 1000);
require_once "includes/config.php";

$mResBH = dbAbstract::Execute("SELECT ID, ItemCode, ItemName FROM bh_items ORDER BY LENGTH(ItemName) DESC");
$mResProducts = dbAbstract::Execute("SELECT prd_id, item_des FROM product WHERE LENGTH(item_des) > 0 AND item_des LIKE '%Proudly Featuring Boar%'");
$mRecordCount = 0;

while ($mRowProducts = dbAbstract::returnObject($mResProducts))
{
    while ($mRowBH = dbAbstract::returnObject($mResBH))
    {
        $mItemName = trim(replaceBhSpecialChars($mRowBH->ItemName));
        $mDescription = trim(replaceBhSpecialChars($mRowProducts->item_des));
        echo($mItemName);
        echo("<br />".$mDescription."<br /><br /><br />");
        
        /*if (strpos($mDescription, $mItemName)!==FALSE)
        {
            $mDescription = str_replace($mItemName, "@".$mRowBH->ItemCode, $mDescription);
            dbAbstract::Update("UPDATE product SET item_des='".$mDescription."' WHERE prd_id=".$mRowProducts->prd_id);
            $mRecordCount = $mRecordCount + 1;
        }*/
    }
}

//echo ($mRecordCount." records updated.");

function replaceBhSpecialChars($pDescription)
{
    $pDescription = str_replace("'", "&#39;", $pDescription);
    $pDescription = str_replace("®", "&#174;", $pDescription);
    $pDescription = str_replace("ä", "&#228;", $pDescription);
    $pDescription = str_replace("è", "&#232;", $pDescription);
    $pDescription = str_replace("ñ", "&#241;", $pDescription);
    $pDescription = str_replace("™", "&#8482;", $pDescription);
    $pDescription = str_replace(" ", " ", $pDescription);
    return $pDescription;
}
?>