<?php
function cartProductExist($title){
	global $cart;
	foreach ($cart->products as $product)
	{
		if($product->item_title == $title) return true;
	}
	return false;
}

if (isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"]))
{
    $mSQL = "SELECT SerializedCart FROM grouporder WHERE GroupID=".$_GET["grpid"]." AND CustomerID=".$_GET["grp_userid"]." AND UserID<>".$_GET["uid"]." AND FoodOrdered=1";
    $mRes = dbAbstract::Execute($mSQL);
    if (dbAbstract::returnRowsCount($mRes)>0)
    {
        while ($mRow=dbAbstract::returnObject($mRes))
        {
            $cartLoop = new $cart();
            $cartLoop = unserialize($mRow->SerializedCart);

            foreach ($cartLoop->products as $prodG)
            {
			  if(!cartProductExist($prodG->item_title)){
                $cart->addProduct($prodG);
			  }
            }
        }
		
    }
}

if($_POST['btnCheckout']==1){
	$_POST['payment_method'] = ($_POST['method']=='credit_card')? 1:2;
	
	if ($_POST['delivery'] == 'Pickup') {
        $cart->setdelivery_type(cart::Pickup);
    } else {
		$cart->setdelivery_type(cart::Delivery);
    }
	
	extract($_POST);
	
	if($address_option > 0){
		$loggedinuser->setUserDeliveryAddress($_POST['address_option']);
	}
	else{
		$loggedinuser->cust_your_name = trim($customer_name);
		$loggedinuser->LastName = trim($customer_last_name);
		$loggedinuser->cust_email = trim($customer_email);
	
		if(isset($altDeliverAddr) && $altDeliverAddr==1){
			$loggedinuser->delivery_address_choice = 2;
			$loggedinuser->cust_phone2 = trim($customer_phone);
			$loggedinuser->delivery_street1 = trim($customer_address);
			$loggedinuser->delivery_street2 = trim($apt);
			$loggedinuser->delivery_city1 = trim($customer_city);
			$loggedinuser->delivery_state1 = trim($customer_state);
			$loggedinuser->deivery1_zip = trim($customer_zip);
			$loggedinuser->delivery_address = $loggedinuser->delivery_street1 . ", " . $loggedinuser->delivery_city1 . ", " . $loggedinuser->delivery_state1;
			
		}else{
			$loggedinuser->cust_phone1 = trim($customer_phone);
			$loggedinuser->street1 = trim($customer_address);
			$loggedinuser->street2 = trim($apt);
			$loggedinuser->cust_ord_city = trim($customer_city);
			$loggedinuser->cust_ord_state = trim($customer_state);
			$loggedinuser->cust_ord_zip = trim($customer_zip);
			$loggedinuser->delivery_address = $loggedinuser->street1 . ", " . $loggedinuser->cust_ord_city . ", " . $loggedinuser->cust_ord_state;
		}

		$loggedinuser->updateCustomerRegistration();
		$loggedinuser->saveToSession();
	}

	// Order Form Code
	if($_POST['payment_method']==1){
		$mZipPostal = "Zip Code";
		$mStateProvince = "State";
			
		if ($objRestaurant->region == "2") //Canada
		{
			$mZipPostal = "Postal Code";
			$mStateProvince = "Province";
		}
		
		$success = 0;
		$CardTokenOrderTbl = "";
		
		$_POST['x_exp_date'] = $pCardExpiry = $cc_exp_month.$cc_exp_year;
		$_POST['cart_total'] = $cart->grand_total();
		
		$nameArr = explode(' ',$customer_name);
		$_POST['x_first_name'] = $nameArr[0];
		$_POST['x_last_name'] = $nameArr[1];
		$_POST['x_address'] = $customer_address;
		$_POST['x_city'] = $customer_city;
		$_POST['x_state'] = $customer_state;
		$_POST['x_zip'] = $customer_zip;
		$_POST['x_email'] = $customer_email;
		$_POST['x_email'] = $customer_email;
		
		$x_card_num=$secure_data;
		$x_exp_date=$pCardExpiry;
		$token='0';
		
		$gateway_token=0;
		$tokenization=1;
		
		if ($objRestaurant->payment_gateway == "authoriseDotNet")
			$objRestaurant->payment_gateway = "AuthorizeNet";
		
		require_once 'classes/gateways/' . $objRestaurant->payment_gateway . '.php';
		
		if ($success == 1) {
			if ($objRestaurant->tokenization == 1) {
				$secure_data = $_POST['x_card_num'];
				$type = substr($secure_data, 0, 1);
			}
			// Added by Saad 22-Sept-2014 -- if user do not wish to save card info.. i.e tokenization checkbox not selected
			$creditCardType = 0;
			if(!isset($gateway_token))
			{
				$creditCardType = substr($secure_data, 0,1);
			}
			//Modified01082013
			$cc = substr($secure_data, -4, 4);
			
			if($card_token == 0){
				if(isset($_POST['cc_name']) && $_POST['cc_name'] != ''){
					$cardName=$_POST['cc_name'];
				}else{
				  $creditCardType = substr($_POST['x_card_num'], 0,1);
				  if($creditCardType==AMEX)
				  {
					  $cardName="American Express";
				  }
				  else if($creditCardType==VISA)
				  {
					  $cardName="VISA";
				  }
				  else  if($creditCardType==MASTER)
				  {
					  $cardName="MasterCard";
				  }
				  else  if($creditCardType==DISCOVER)
				  {
					  $cardName="Discover";
				  }
				}
				if(isset($_POST['save_cc']) && $_POST['save_cc']==1){
				  $resp = $loggedinuser->saveCCTokenForMobile($_POST['x_card_num'], $gateway_token, 0, $pCardExpiry,$cardName);
				  $loggedinuser->getUserCCTokens();
				  $loggedinuser->saveToSession();
				}
			}
			
			$_POST['x_card_num'] = '';
		}
	}
	
	include "submit_order.php";
	
}

$cart_total = $cart->grand_total();
if (!is_numeric($cart_total) || $cart_total <= 0) {
    redirect($SiteUrl . $objRestaurant->url . "/");
    header("location: " . $SiteUrl . $objRestaurant->url . "/");
    exit;
}

if ($cart->delivery_type == cart::None) {
	$cart->delivery_type = cart::Pickup;
    //redirect($SiteUrl . $objRestaurant->url . "/?item=cart");
    //header("location: " . $SiteUrl . $objRestaurant->url . "/?item=cart");
    //exit;
}

isset($_POST['step']) ? $step = $_POST['step'] : $step = 1;

include "checkout_cart.php";
/*
if (!is_numeric($loggedinuser->id) && $step == 1) {
	include "resgister_customer.php";
} else {
	include "checkout_cart.php";
} ///ELSE*/
?>