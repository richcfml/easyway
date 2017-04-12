<?php
if (isset($_POST['btncheckout'])) 
{
    if ($_POST['btncheckout'] == 'Pickup') 
    {
        $cart->setdelivery_type(cart::Pickup);
    } 
    else 
    {
        $cart->setdelivery_type(cart::Delivery);
    }
}

$user_email = '';
if (isset($_COOKIE["user"])) 
{
    $user_email = $_COOKIE["user"];
}

$remeber_me = '';
if (isset($_POST['rememberme'])) 
{
    $remeber_me = $_POST['rememberme'];
}
$result = -1;
if (isset($_POST['login'])) 
{
    $mTmpEcommerceID = 0;
    if (isset($_SESSION["EcommerceID"]))
    {
        if ($_SESSION["EcommerceID"] > 0)
        {
            $mTmpEcommerceID = $_SESSION["EcommerceID"];
        }
    }
    
    $user_email = $_POST['email'];
	
    if($_POST['login']=='sso')
    {
        Log::write("---->login.php[ssoUserLogin]<-----");
        $user = $loggedinuser->ssoUserLogin($_POST['email'], $objRestaurant->id, $_POST['ssoUserId']);
    }
    else
    {
        Log::write("---->login.php[loginUser]<-----");
        $user = $loggedinuser->loginUser($_POST['email'], $_POST['password'], $objRestaurant->id);
    }

    if (is_null($user)) 
    {   
        Log::write("---->login.php[user null]<-----");
        $result = false;
    } 
    else 
    {
        Log::write("---->login.php[user not null]<-----");
        $loggedinuser->destroyUserSession();
        $loggedinuser = $user;
        require($mobile_root_path . "../new_site/includes/abandoned_cart_config.php");

        if ($objRestaurant->useValutec == 1) 
        {
            if ($loggedinuser->valuetec_card_number > 0) 
            {
                $Balance = valutecCardBalance($loggedinuser->valuetec_card_number);
                $loggedinuser->valuetec_points = $Balance['PointBalance'];
                $loggedinuser->valuetec_reward = $Balance['Balance'];
            }
        } 
        else 
        {
            $loggedinuser->valuetec_card_number = 0;
        }
        $address1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));

        $loggedinuser->street1 = $address1[0];
        $loggedinuser->street2 = '';
        if (count($address1) >= 1)
        {
            if(isset($address1[1]))
            {
                $loggedinuser->street2 = $address1[1];
            }
        }

        $address1 = explode('~', trim($loggedinuser->delivery_address1, '~'));

        $loggedinuser->delivery_street1 = $address1[0];
        $loggedinuser->delivery_street2 = '';
        if (count($address1) >= 1)
        {
            if(isset($address1[1]))
            {
                $loggedinuser->delivery_street2 = $address1[1];
            }
        }
		
		if($_POST['login']=='sso'){
			$loggedinuser->ssoUserId = $_POST['ssoUserId'];
		}
		
        $loggedinuser->saveToSession();
        $result = true;
        $itemcount = $cart->totalItems();
		
		if(isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"]))
		{
			redirect($SiteUrl .$objRestaurant->url ."/?item=checkout&grp_userid=".$_GET["grp_userid"]."&grpid=".$_GET["grpid"]."&uid=".$_GET["uid"]."&grp_keyvalue=".$_GET["grp_keyvalue"]);
		}
		elseif ($itemcount > 0) 
        {
            redirect($SiteUrl . $objRestaurant->url . "/?item=checkout");
            exit;
        }
        redirect($SiteUrl .$objRestaurant->url );
        exit;
    }
    if ($mTmpEcommerceID>0)
    { 
        $_SESSION["EcommerceID"] = $mTmpEcommerceID;
    }
}
 
if ($result === false) 
{
	redirect($SiteUrl.$objRestaurant->url ."/?reponse=-1#login");
	exit;
} 
?>
