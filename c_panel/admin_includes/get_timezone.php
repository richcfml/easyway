<? ini_set('display_errors',0);
session_start();
if(!$_SESSION['admin_session_user_name'] && !$_SESSION['admin_session_pass']){ header("location:login.php");}
include("../includes/config.php");


 ?>

<?php   $drop_qry_exec = mysql_query("SELECT * FROM times_zones where time_zone='Europe/London'");
        while($drop_qry_rs = mysql_fetch_object($drop_qry_exec)){

            print_r($drop_qry_rs);
	 }
?>