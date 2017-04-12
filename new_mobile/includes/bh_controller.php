<?php 
//isset($_GET['mod']) ? $mod=$_GET['mod'] : $mod='resturants';
Log::write("---->bh_controller.php<----");
if($_GET['item']=='menu') unset($_GET['item']);

isset($_GET['item']) ? $mod=$_GET['item'] : $mod='resturants';

	if($objRestaurant->status==0){
		$include=$mobile_root_path. "views/restaurant/notavailable.php";
	} else{
		Log::write("---->bh_controller.php<----");
	if($mod=='login') 
	{
		Log::write("---->bh_controller.php views/customer/login.php<----");
		$include=$mobile_root_path. "views/customer/login.php";
    }
	else{
			$include=$mobile_root_path. "views/restaurant/menu.php";
			 $mod='resturants';
		}
		
	if(!$objRestaurant->isOpenHour){
		 $mod='closed';
		$include=$mobile_root_path. "views/restaurant/closed.php";
		}
	 }//ELSE REST
	 
?>