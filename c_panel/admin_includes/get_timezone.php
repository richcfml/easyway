<?
require_once("../includes/config.php");
if(!$_SESSION['admin_session_user_name'] && !$_SESSION['admin_session_pass']){ header("location:login.php");}
 ?>

<?php   $drop_qry_exec = dbAbstract::Execute("SELECT * FROM times_zones where time_zone='Europe/London'",1);
        while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){

            print_r($drop_qry_rs);
	 }
?>
<?php mysqli_close($mysqli);?>