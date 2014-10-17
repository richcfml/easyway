<?php
if (isset($_POST['btncheckout'])) 
{
	if ($_POST['btncheckout'] == 'Pickup') 
	{
        $cart->setdelivery_type(cart::Pickup);
    } 
	else 
	{
    	if ($cart->sub_total < $objRestaurant->order_minimum) 
		{
        	redirect($SiteUrl . $objRestaurant->url . "/?item=cart");
            exit;
        }
        $cart->setdelivery_type(cart::Delivery);
    }
}
if (is_numeric($loggedinuser->id) && isset($_POST['delivery_address'])) 
{
    $loggedinuser->delivery_address_choice = $_POST['delivery_address'];
}
$MAX_REWARD = 0.0;


if ($cart->isempty()) 
{
    redirect($SiteUrl . $objRestaurant->url . "/");
    exit;
}

if ($cart->delivery_type == cart::None) 
{
    redirect($SiteUrl . $objRestaurant->url . "/?item=menu");
    exit;
}
include "checkout_cart.php";
?>
