<?php
error_reporting(1);
echo("1");
require_once "includes/config.php";
echo("2");
$mysqli = new mysqli("localhost", "easywayordering", "Yh56**ew!d", "upgraded_easywayordering");
echo("3");
/*
$mSQL = "SELECT * FROM bh_items";
$mRes = dbAbstract::Execute($mSQL);
echo("<pre>");
while ($ar = dbAbstract::returnArray($mRes))
{
    print_r($ar);
}
echo("</pre>");*/
?>