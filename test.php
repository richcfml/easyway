<?php
error_reporting(1);
require_once "includes/config.php";
$mysqli = new mysqli("localhost", "easywayordering", "Yh56**ew!d", "upgraded_easywayordering");

$mSQL = "SELECT * FROM bh_items";
echo("3");
$mRes = dbAbstract::Execute($mSQL);
echo("4");
echo("<pre>");
while ($ar = dbAbstract::returnArray($mRes))
{
    print_r($ar);
}
echo("</pre>");

?>