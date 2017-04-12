<?php 
//isset($_GET['mod']) ? $mod=$_GET['mod'] : $mod='resturants';
Log::write("---->controller.php<----");
if($_GET['item']=='menu') unset($_GET['item']);

isset($_GET['item']) ? $mod=$_GET['item'] : $mod='resturants';

	if($objRestaurant->status==0){
		$include=$mobile_root_path. "views/restaurant/notavailable.php";
	} else{
		 Log::write("---->controller.php[objRestaurant.status not zero]<----".$mobile_root_path);
	if($mod=='resturants') {
		Log::write("----->controller.php[mod=resturants]");
		$include=$mobile_root_path. "views/restaurant/sub_menu.php";
	}else if($mod=='product') {
		$include=$mobile_root_path. "views/restaurant/product.php";
	}else if($mod=='cart') {
		$include=$mobile_root_path. "views/cart/cart.php";
	}
	else if($mod=='grouporder') {
		$include=$mobile_root_path. "views/cart/grouporder.php";
	}
	else if($mod=='login') 
	{
		Log::write("---->controller.php views-customer-login.php<----");
		$include=$mobile_root_path. "views/customer/login.php";
	}
	else if($mod=='accountajax') 
	{
		Log::write("---->controller.php views-customer-ajax.php<----");
		$include=$mobile_root_path. "views/customer/ajax.php";
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
	 }else if ($mod == 'favindex') {
        $include = $mobile_root_path . "views/restaurant/ajax.php";
    }
	else if($mod=='tos') 
	{
			$include=$site_root_path. "views/main/tos.php";
	} 
	else if($mod=='privacypolicy') 
	{
			$include=$site_root_path. "views/main/privacypolicy.php";
	} 
	else if($mod=='refundpolicy') 
	{
			$include=$site_root_path. "views/main/refundpolicy.php";
    }
	 else if($mod=='favorites') 
	 {
		$include=$mobile_root_path. "views/customer/favorites.php";	
	}
	else if($mod=='checkfbid') 
	{
		$include=$mobile_root_path. "views/customer/ajax.php";
	}
        else if($mod=='checktwitter') 
	{
		$include=$mobile_root_path. "views/customer/ajax.php";
	}else if($mod=='resetpassword') 
	{
		$include=$site_root_path. "views/customer/resetpassword.php";
	}else if($mod=='cartajax') 
	{
		$include=$site_root_path. "views/cart/cart_ajax.php";
	}else if($mod=='groupordering') 
	{
		$include=$site_root_path. "views/cart/group_orders.php";
	}else if($mod=='grouporder_ajax') 
    {
        $include=$site_root_path. "views/cart/grp_aiax.php";
    }
	else if($mod=='grouporderdone') 
    {
        $include=$site_root_path. "views/cart/grouporderdone.php";
    }
	else if($mod=='grouporderthankyou') 
    {
        $include=$site_root_path. "views/cart/grouporderthankyou.php";
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
