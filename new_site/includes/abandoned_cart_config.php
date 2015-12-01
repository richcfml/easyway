<?php
if (isset($_GET["l"]))
{
    log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
}
if($cart->order_created===0) 
{
    if (isset($_GET["l"]))
    {
        log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
    }
    if(empty($_SESSION["user_session_duration"])) 
    {
        $_SESSION["user_session_duration"] = time();
    }

    if(empty($_SESSION["referral_source"])) 
    {
        $_SESSION["referral_source"] = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/");
    }
    if (isset($_GET["l"]))
    {
        log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
    }
    $last_user_action = "";
    if($mod == "product") 
    {
        $last_user_action = "Viewed product details";
    } 
    else if($mod == "cart") 
    {
        $last_user_action = "Viewed Cart";
    } 
    else if($mod == "login") 
    {
        $last_user_action = "Logged in";
    } 
    else if($mod == "checkout") 
    {
        $last_user_action = "Clicked Checkout";
    } 
    else if($mod == "confirmpayments") 
    {
        $last_user_action = "Clicked confirm payments";
    } 
    else if($mod == "addtip") 
    {
        $last_user_action = "Added Tip";
    } 
    else if($mod == "redeemcoupon") 
    {
        $last_user_action = "Redeem Coupon";
    } 
    else if($mod == "logout") 
    {
        $last_user_action = "Logged out";
    } 
    else if($mod == "valutec") 
    {
        $last_user_action = "Register Card Details";
    } 
    else if($mod == "register_card") 
    {
        $last_user_action = "Register Card Details";
    }
    if (isset($_GET["l"]))
    {
        log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
    }
    if($last_user_action != "") 
    {
        $_SESSION["last_user_action"] = $last_user_action;
    } 
    else if(empty($_SESSION["last_user_action"])) 
    {
        $_SESSION["last_user_action"] = "";
    }
    if (isset($_GET["l"]))
    {
        log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
    }
    $user_id = (isset($loggedinuser->id) && is_numeric($loggedinuser->id) ? $loggedinuser->id : 0);
    $resturant_id = $objRestaurant->id;
    $cartt = addslashes(json_encode($cart));
    $date_added = date("Y-m-d H:i:s");
    $referral_source = $_SESSION["referral_source"];
    if(!empty($_SESSION["user_session_duration"])) 
    {
        $session_duration_in_seconds = time() - $_SESSION["user_session_duration"];
    } 
    else 
    {
        $session_duration_in_seconds = 0;
    }
    if (isset($_GET["l"]))
    {
        log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
    }
    $cart_total_amount = $cart->grand_total();
    $platform_used = $platform_used;
    $status = 0;
    if (isset($_GET["l"]))
    {
        log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
    }
    $reason = "Unknown";
    if(isset($_SESSION["abandoned_cart_error"])) 
    {
        if (isset($_GET["l"]))
        {
            log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
        }
        $error = $_SESSION["abandoned_cart_error"];
        if(isset($error["redeemcoupon"])) 
        {
            $reason = "Coupon Code: " . $error["redeemcoupon"]["coupon_code"] . ". Error: " . $error["redeemcoupon"]["msg"];
        } 
        elseif(isset($error["credit_card_declined"])) 
        {
            $reason = $error["credit_card_declined"];
        } 
        elseif(isset($error["out_of_area"])) 
        {
            $reason = $error["out_of_area"];
        } 
        elseif(isset($error["under_delivery_minimum"])) 
        {
            $error1 = $error["under_delivery_minimum"];
            $reason = "Zone: " . $error1["zone"] . ". Minimum Total required: $". $error1["minTotal"];
        }
        if (isset($_GET["l"]))
        {
            log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
        }
    }

    $last_user_action = $_SESSION["last_user_action"];
    if (isset($_GET["l"]))
    {
        log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
    }
    if(isset($_SESSION["abandoned_cart_id"])) 
    {
        if (isset($_GET["l"]))
        {
            log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
        }
        $abandoned_carts->updateAbandonedCart($_SESSION["abandoned_cart_id"], $user_id, $resturant_id, $cartt, $date_added, $referral_source, $session_duration_in_seconds,$last_user_action,$cart_total_amount,$reason,$platform_used,$status);
        if (isset($_GET["l"]))
        {
            log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
        }
    } 
    else 
    {
        if (isset($_GET["l"]))
        {
            log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
        }
        if(!$cart->isempty())
        {
            $_SESSION["abandoned_cart_id"] = $abandoned_carts->addNewAbandonedCart($user_id, $resturant_id, $cartt, $date_added, $referral_source, $session_duration_in_seconds,$last_user_action,$cart_total_amount,$reason,$platform_used,$status);		
        }
        if (isset($_GET["l"]))
        {
            log::write("abandoned_cart_config.php: Log ID = ".$_GET["l"].", Line Number = ".__LINE__, "Server Time: ".time()." || Client Time: ".$_GET["t"], "debug");
        }
    }
}
?>