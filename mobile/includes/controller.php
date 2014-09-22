<? 
//isset($_GET['mod']) ? $mod=$_GET['mod'] : $mod='resturants';
isset($_GET['item']) ? $mod=$_GET['item'] : $mod='resturants';

	if($objRestaurant->status==0){
		$include=$mobile_root_path. "views/restaurant/notavailable.php";
	} else{
		 
	if($mod=='resturants') {
		$include=$mobile_root_path. "views/restaurant/menu.php";
	}else if($mod=='product') {
		$include=$mobile_root_path. "views/restaurant/product.php";
	}else if($mod=='cart') {
		$include=$mobile_root_path. "views/cart/cart.php";
	}
	else if($mod=='login') 
	{
		$include=$mobile_root_path. "views/customer/login.php";
	}
	else if($mod=='forgotpassword') {
		$include=$mobile_root_path. "views/customer/forgotpassword.php";
	}else if($mod=='account') {
		$include=$mobile_root_path. "views/customer/account.php";
	}else if($mod=='editaccount') {
		$include=$mobile_root_path. "views/customer/editaccount.php";
 	}else if($mod=='register') {
		$include=$mobile_root_path. "views/customer/register.php";
	}else if($mod=='logout') {
		$include=$mobile_root_path. "views/customer/logout.php";
	}else if($mod=='checkout') {
		$include=$mobile_root_path. "views/cart/checkout.php";
 	}else if($mod=='addtip' || $mod=='redeemcoupon') {
		$include=$mobile_root_path. "views/cart/cart_stats.php";
	 }else if($mod=='submitorder') {
		$include=$mobile_root_path. "views/cart/submit_order.php";
 	}else if($mod=='confirmpayments') {
		$include=$mobile_root_path. "views/cart/orderform.php";
	 }else if($mod=='valutec') {
                $include=$mobile_root_path. "views/valutec/tab_register_card_popup.php";
        }else if($mod=='register_card') {
                $include=$mobile_root_path. "views/valutec/tab_register_card.php";
        }else if($mod=='thankyou') {
		$include=$mobile_root_path. "views/restaurant/thankyou.php";
 	}else if($mod=='failed') {
		$include=$mobile_root_path. "views/cart/transaction_failed.php";
	 } 
	 else if($mod=='favorites') 
	 {
		$include=$mobile_root_path. "views/customer/favorites.php";	
	}
	else if($mod=='checkfbid') 
	{
		$include=$mobile_root_path. "views/customer/ajax.php";
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