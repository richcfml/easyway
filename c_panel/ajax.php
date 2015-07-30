<?php
require_once("../includes/config.php");
if(!$_SESSION['admin_session_user_name'] && !$_SESSION['admin_session_pass']){ header("location:login.php");}	

$ajax=1;
include "includes/main_nav.php";
 include $admin_include_content; 
 ?>
<?php mysqli_close($mysqli);?>