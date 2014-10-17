<? 
if(isset($_GET['mod'])) $mod = $_GET['mod']; else $mod	= 'resturants';
if($mod == "resturants") {$includecontent = "_contents/resturants/tab_resturant.php"; }
else if($mod == "myaccount") { $includecontent = "_contents/myaccount/tab_myaccount.php"; } 
else if($mod == "logout") { $includecontent = "_contents/tab_logout.php"; } 
else if($mod == "valutec") { $includecontent = "_contents/valutec/tab_main.php"; } 
else   { $includecontent = "_contents/resturants/tab_resturant.php"; }
 
?>
 
   