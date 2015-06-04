<?php
isset($_GET['item']) ? $mod=$_GET['item'] : $mod='resturants';
$kiosk = true;
if($objRestaurant->status==0)
{
    $_GET['ajax']=1;
    $include=$site_root_path. "views/restaurant/notavailable.php";
} 
else
{	 
    if($mod=='resturants') 
    {
        $include=$site_root_path. "views/restaurant/menu.php";
    }
    else if($mod=='product') 
    {
        $include=$site_root_path. "views/restaurant/product.php";
    }
    else if($mod=='cart') 
    {
        $include=$site_root_path. "views/cart/cart.php";
    }
    else if($mod=='clearcart') 
    {
        $include=$site_root_path. "views/cart/clearcart.php";
    }
    else if($mod=='addtip' || $mod=='redeemcoupon') 
    {
        $include=$site_root_path. "views/cart/cart_stats.php";
    }
    else if($mod=='submitorder') 
    {
        $include=$site_root_path. "views/cart/submit_order.php";
    }
    else if($mod=='confirmpayments') 
    {
        $include=$site_root_path. "views/cart/orderform.php";
    }
    else if($mod=='thankyou') 
    {
        $include=$site_root_path. "views/restaurant/thankyou.php";
    }
    else if($mod=='failed') 
    {
        $include=$site_root_path. "views/cart/transaction_failed.php";
    }
    else if($mod=='businesshours') 
    {
        $include=$site_root_path. "views/restaurant/businesshours.php";			
    } 
    else if($mod=='report_abandoned_cart_error') 
    {
        $include = $site_root_path . "views/cart/report_abandoned_cart_error.php";
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
    else if($mod=='favindex') 
    {
        $include=$site_root_path. "views/restaurant/ajax.php";
    }
    else if($mod=='parseresponse') 
    {
        $include=$site_root_path. "views/cart/parseresponse.php";
    }
    else
    {
        $include=$site_root_path. "views/restaurant/menu.php";
        $mod='resturants';
    }
}