<?php
require_once "includes/config.php";
if(isset($_GET['session_id']))
{
	$currents_session_id = $_GET['session_id'];
	session_id($currents_session_id);
}
else if(isset($_POST['x_sid']))
{
	$currents_session_id = $_POST['x_sid'];
 	session_id($currents_session_id);
}

	header('Access-Control-Allow-Origin: *.easywayordering.com'); 
        define("AMEX",3);
	define("VISA",4);
	define("MASTER",5);
	define("DISCOVER",6);
	
 	require_once('lib/nusoap.php');
	
	include "classes/Restaurant.php";
	include "classes/Users.php";
	include "classes/Validater.php";
	require_once("classes/trackers.php"); 
	require	"includes/class.phpmailer.php";
	require_once('classes/valutec.php'); 
	require_once('classes/Menu.php');
	require_once('classes/Category.php');
	require_once('classes/Product.php');
	require_once('classes/cart.php');
	require_once('classes/GO3.php');
 	require_once('lib/cdyne/cdyne.php');
	require_once('classes/AbandonedCarts.php');
	require_once('classes/chargify.php');
	
	require_once('lib/device_detection/Mobile_Detect.php');
	$function_obj = new clsFunctions();
	$validater = new Validater(); 
	$objRestaurant = new Restaurant();
	$objMail = new testmail();
	$objMenu = new Menu();
	$objCategory = new Category();
	$product = new Product();
 	$cart = new cart();
 	$objGO3 = new GO3();
	$objcdyne=new cydne();
	$loggedinuser=new Users();
	$abandoned_carts = new AbandonedCarts();

	$objRestaurant = $objRestaurant->getDetailByRestaurantUrl($_GET["name"]);

	if($loggedinuser->loadFromSession())
	{
            $loggedinuser=$loggedinuser->loadFromSession();
            if ($loggedinuser->resturant_id != $objRestaurant->id)
            {
                $loggedinuser = new users();
            }
	}
        
	$visit_tracker_html='';

	//**********************************************************//
	//**************************CYDNE****************************//
	//**********************************************************//
	$loggedinuser->resturant_id=$objRestaurant->id;
	$objcdyne->APIkey='04257110-2ae2-452f-a776-f8ae189e5f74';
	$objcdyne->did=$objRestaurant->did_number;
	$objcdyne->url=$objRestaurant->url;
	$objcdyne->restaurant_name=$objRestaurant->name;
	$objcdyne->user=$loggedinuser;
   	//**********************************************************//
   	//**************************CYDNE****************************//
   	//**********************************************************//
	
	$detect = new Mobile_Detect;
	 
	$mobileapp=false;
	
	if(isset($_GET['desktop']) && $_GET['desktop']==1) 
	{
		$_SESSION['desktopview']='true';
		$mobileapp=false;
	} 
	else 
	{
		if($detect->isMobile() || $detect->isTablet() || (!empty($_GET['mobile']) && $_GET['mobile']==1)) 
		{
			$mobileapp=true;
			if(isset($_SESSION['desktopview']) ) 
			{
				$mobileapp=false;
			}
		}
	}

	// $platform_used if 1 then website else if 2 then mobile else if 3 then rapid reorder
	$platform_used = 1;
	//$mobileapp = true;

	if (isset($_GET["mobile_qc"]) || isset($_SESSION["mobile_qc"]))
	{
		$_SESSION["mobile_qc"] = 1;
		$mobileapp = true;
	}

        if (isset($_GET["kiosk"]))
        {
            include("kiosk/index.php");
        }
        else
        {
            if ($mobileapp)
            {
		$platform_used = 2;
		include("mobile/index.php");
            }
            else 
            {
 		include("new_site/index.php");
                
            }
        }
?>

	