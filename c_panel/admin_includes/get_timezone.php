<?
require_once("../includes/config.php");
if(!$_SESSION['admin_session_user_name'] && !$_SESSION['admin_session_pass']){ header("location:login.php");}
 ?>

<?php   $drop_qry_exec = mysql_query("SELECT * FROM times_zones where time_zone='Europe/London'");
        while($drop_qry_rs = mysql_fetch_object($drop_qry_exec)){

            print_r($drop_qry_rs);
	 }
?>