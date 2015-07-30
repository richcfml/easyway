<?php
error_reporting(1);

//require_once "includes/config.php";
$mysqli = new mysqli("localhost", "easywayordering", "Yh56**ew!d", "upgraded_easywayordering");

$mSQL = "SELECT * FROM bh_items";

$mRes = mysqli_query($mysqli, $mSQL);


echo("<pre>");
while ($ar = dbAbstract::returnArray($mRes))
{
    print_r($ar);
}
echo("</pre>");
?>