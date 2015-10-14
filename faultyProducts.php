<?php
error_reporting(E_ALL);
set_time_limit(1000);
ini_set('max_execution_time', 1000);
require_once "includes/config.php";

$mResProducts = dbAbstract::Execute("SELECT prd_id, item_des FROM product WHERE LENGTH(item_des) > 0 AND LOWER(item_des) LIKE '%proudly featuring boar%'");
$mRecordCount = 0;

while ($mRowProducts = dbAbstract::returnObject($mResProducts))
{
    $mResBH = dbAbstract::Execute("SELECT ID, ItemCode, ItemName FROM bh_items ORDER BY LENGTH(ItemName) DESC");
    while ($mRowBH = dbAbstract::returnObject($mResBH))
    {
        $mItemName = trim(replaceBhSpecialChars($mRowBH->ItemName));
        $mDescription = trim(replaceBhSpecialChars($mRowProducts->item_des));
            
        $mRes = compareStrs($mDescription, $mItemName);
        if ($mRes !== null)
        {
            if (strlen($mRes)>8)
            {
                if (strpos($mRes, " ")!==FALSE)
                {
                    echo("<b>Product ID: </b>".$mRowProducts->prd_id);
                    echo("<br /><b>Item ID: </b>".$mRowBH->ID);
                    echo("<br /><b>Item: </b>".$mRowBH->ItemName);
                    echo("<br /><b>Description: </b>".$mRowProducts->item_des);
                    echo("<br /><br />- - - - - - - - - - - - - - - - - - - - - <br />");
                    $mRecordCount = $mRecordCount + 1;
                }
            }
        }
    }
}

echo("<br /><br /><br /><b>Total Faulty Record: </b>".$mRecordCount);


function replaceBhSpecialChars($pDescription)
{
    $pDescription = str_replace("'", "&#39;", $pDescription);
    $pDescription = str_replace("®", "&#174;", $pDescription);
    $pDescription = str_replace("ä", "&#228;", $pDescription);
    $pDescription = str_replace("è", "&#232;", $pDescription);
    $pDescription = str_replace("ñ", "&#241;", $pDescription);
    $pDescription = str_replace("™", "&#8482;", $pDescription);
    $pDescription = str_replace("&amp;", "&#38;", $pDescription);
    $pDescription = str_replace(" & ", " &#38; ", $pDescription);
    $pDescription = str_replace(" ", " ", $pDescription);
    return $pDescription;
}

function compareStrs($str1, $str2)
{
    for ($i = strlen($str1); $i != 0; $i--) {
        for ($x = 0; $x < strlen($str1) - $i; $x++) {
            if (($pos = strpos($str2, substr($str1, $x, $i + 1))) !== false) {
                return substr($str1, $x, $i + 1);
            }
        }
    }
    return null;
}
?>