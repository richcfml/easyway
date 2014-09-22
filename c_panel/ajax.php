<? ini_set('display_errors',0);
session_start();
if(!$_SESSION['admin_session_user_name'] && !$_SESSION['admin_session_pass']){ header("location:login.php");}	
include("../includes/config.php");
$ajax=1;
include "includes/main_nav.php";
 include $admin_include_content; 
  @mysql_close($mysql_conn); 
   
 ?>