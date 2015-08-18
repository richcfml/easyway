<?php
require_once("../includes/config.php");
require("includes/SimpleImage.php");
require("includes/snapshot.class.php");
include("admin_includes/function.php");
require("classes/Country.php");
require("classes/chargifyApi.php");
include("../includes/class.phpmailer.php"); 
include("../includes/class.smtp.php");
require_once('../classes/abandoned_carts.php');
$jsParameter = "?v3";
$function_obj = new clsFunctions();
$chargify = new chargifyApi();

$ajax=0;

//echo 'user'.$_SESSION['admin_session_user_name'];
//echo 'pass'.$_SESSION['admin_session_pass'];
if(!$_SESSION['admin_session_user_name'] && !$_SESSION['admin_session_pass'])
{ 
        header("location:login.php");	
    //echo 'index if';
} 
?>

<?php
if($_SESSION['admin_type'] == 'admin') 
{
	$resturantQuery = dbAbstract::Execute("SELECT * FROM resturants",1);
} 
else if($_SESSION['admin_type'] == 'reseller') 
{
    $resellerId = $_SESSION['owner_id'];
    $client_ids = resellers_client( $resellerId );

	$resturantQuery = dbAbstract::Execute("SELECT * FROM resturants WHERE owner_id IN ( $client_ids ) ",1);
	$licenseQry		=	dbAbstract::Execute("select * from licenses WHERE reseller_id = '".$resellerId."'",1);
	$totalLicenses  = 	dbAbstract::returnRowsCount($licenseQry,1);

} else if($_SESSION['admin_type'] == 'store owner') {
	$resturantQuery = dbAbstract::Execute("SELECT * FROM resturants WHERE owner_id = '".$_SESSION['owner_id']."'");
}
else if($_SESSION['admin_type'] == 'bh') 
{
    $resturantQuery = dbAbstract::Execute("SELECT * FROM resturants WHERE bh_restaurant = 1",1);
}
@$totalResturants = dbAbstract::returnRowsCount($resturantQuery,1);

$resellerQuery = dbAbstract::Execute("SELECT * FROM users WHERE  type = 'reseller'",1);
$totalresellers= dbAbstract::returnRowsCount($resellerQuery,1);
if($_SESSION['admin_type'] == 'admin') {

$clientQuery = dbAbstract::Execute("SELECT * FROM users WHERE  type = 'store owner'",1);

} else if($_SESSION['admin_type'] == 'reseller') {

$clientQuery = dbAbstract::Execute("SELECT * FROM users WHERE  id IN ( $client_ids )",1);

}
@$totalClients = dbAbstract::returnRowsCount($clientQuery,1);

 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>easywayordering</title>

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.22.custom.min.js"></script>
<script type="text/javascript" src="js/Togle.js"></script>
<link href="css/adminMain.css" rel="stylesheet" type="text/css" />
<link href="css/south-street/jquery-ui-1.8.22.custom.css" rel="stylesheet" type="text/css" />


<!-- GOOGLE API KEY FOR DREAM HOST:  AIzaSyCkRkSd4hQornJOYjYMoHqi3-Wv4hVOOgg-->
<!-- GOOGLE API KEY FOR LOCAL PROJECT:  ABQIAAAAPpaOjFQ_miNP74G3g3O7oBTTwBGlz0OqYPu6tmNrU0ToxRrT5hQhlPr8PLUNIxb0D5FrOa5lJ1tp6w-->
<script src="//maps.googleapis.com/maps/api/js?key=<?=$google_api_key?>&sensor=false" type="text/javascript"></script>
<!--<script src="http://maps.google.com/maps?file=api&v=2&key=<?=$google_api_key?>" type="text/javascript"></script> -->
</script>
<script type="text/javascript" language="javascript">
	function initialize() {
		geocoder = new google.maps.Geocoder();
	}
</script>

</head>
 <?if(strpos($_SERVER['REQUEST_URI'],"new_menu")==false ){?>
<body  onLoad="initialize();">
<div id="maincontainer">
	<div id="header">
            <div id="top">
                <div id="top_left"></div>
                <div id="top_right"></div>
            </div><!--End top Div-->
    </div><!--End header Div-->        
            <div id="text_holder">
                
                <? include "includes/header.php"; ?>
                <!--End logo_area Div-->
            
            <div id="page_content">
            
   			<div id="navigation_links">
                  <?  include "includes/main_nav.php"; ?>
                  <!--End navigation Div-->
            </div><!--End navigation_links Div-->
            <? include $admin_include_content;?>	
         </div>   
            
        </div><!--End text_holder Div-->	
        <div id="footer">
            <div id="bottom">
                <div id="bottom_left"></div>
                <div id="bottom_right"></div>
            </div><!--End bottom Div--><br style="clear:both" />
        </div><!--End footer Div-->
    </div><!--End header Div-->
</div><!--End maincontainer Div-->
</body>
<?}else{?>
<body style="background-image:none">
    <div id="pagewrap" style="width: 1248px; margin:0 auto">
<div class="userNameLink">
<span style="color:#A7A7A7;">Welcome,</span> <span>Username</span>
</div>
        <div style="position: relative;">
            <div id="toggle_menu_nav">
                <i class="fa fa-caret-up" style="position: absolute;top: -20px;right: 6%;font-size: 30px;color: #777777;"></i>
                <div class="divlirest"><span class="imgSpanClass" ><img src="img/restaurantsTop.png" id="imgRest" style="width: 20px;"/></span><a href="<?php echo($AdminSiteUrl); ?>?mod=resturant" class="headingSpan">Restaurants</a></div>
                <div class="divliSetting"><span class="imgSpanClass" ><img src="img/settings.png" style="width: 20px;" id="imgSetting"/></span><a href="#" class="headingSpan">Account Settings</a></div>
                <div class="divliHelp"><span class="imgSpanClass" ><img src="img/help.png" style="width: 20px;" id ="imgHelp"/></span><a href="#" class="headingSpan">Help</a></div>
                <div class="divliLogout"><span class="imgSpanClass" ><img src="img/logout.png" style="width: 20px;" id ="imglogout"/></span><a href="logout.php" class="headingSpan">Log Out</a></div>
            </div>
        </div>
    <div style="background-color: #E9F3F7">
 
        <span id="showLeft"></span>
    <span style="margin-left: 11px;"><img src="img/ew_weblogo.png" style="width: 93px; "/></span>
    </div>
<? include "admin_contents/menus/new_menu.php";?>
<?}?>
    </div>
</body>
</html>
