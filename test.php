<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
require_once "includes/config.php";
$mSQL = "SELECT * FROM bh_items";
$mRes = dbAbstract::Execute($mSQL);
echo("<pre>");
while ($ar = dbAbstract::returnArray($mRes))
{
    print_r($ar);
}
echo("</pre>");
?>