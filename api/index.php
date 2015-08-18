<?php
require_once "../includes/config.php";
require_once 'function.php';


include "../classes/restaurant.php";
include "../classes/users.php";
include "../classes/validater.php";
require_once("../classes/trackers.php");
require "../includes/class.phpmailer.php";
require_once('../classes/valutec.php');
require_once('../classes/Menu.php');
require_once('../classes/Category.php');
require_once('../classes/product.php');
require_once('../classes/cart.php');
require_once('../lib/cdyne/cdyne.php');
require_once('../classes/abandoned_carts.php');
require_once('../classes/chargify.php');
require('GoogleGeocode.php');

$geo = new GoogleGeocode($google_api_key);
$validater = new validate();
$objRestaurant = new restaurant();
$objMenu = new Menu();
$objCategory = new Category();
$product = new product();
$cart = new cart();
$objcdyne = new cydne();
$loggedinuser = new users();
$abandoned_carts = new abandoned_carts();
$fun = new clsFunctions();
$objMail = new testmail();

session_start();

if ((isset($_GET['op']) && ($_GET['op'] == 'getRestaurantId'))) {

    $restaurants_info = Easy_Way_Api::getRestaurants();
    echo json_encode($restaurants_info);
    exit;
} else
if ((isset($_GET['op']) && ($_GET['op'] == 'fetch')) && (isset($_GET['field']) && ($_GET['field']) == 'restaurant')) {

    $myRestId = $_POST['restaurant_id'];
    $qry = dbAbstract::Execute("select * from resturants where id = '" . $myRestId . "'");
    $rest_qry = dbAbstract::returnObject($qry);
    $objRestaurant = $objRestaurant->getDetailbyUrl($rest_qry->url_name);

    $objMenu->restaurant_id = $myRestId;
    $restaurant_info = $objRestaurant->getDetail($myRestId);
    $menuname = '';
    $menuid = (isset($_GET['menuid']) ? $_GET['menuid'] : "");
    $menus = $objMenu->getMenusByRestaurantId();
    $isOpen = true;
    $iscurrentMenuAvaible = 1;
    $currentMenuTimings = "";
    for ($i = 0; $i < count($menus); $i++) {
        $menu = $menus[$i];
        $isOpen = $menu->isMenuOpen();

        if ($isOpen == 1 && $i == 0 && $menuid == "") {
            $menuid = $menu->id;
        } else if ($isOpen == 1 && $menuid == "") {
            $menuid = $menu->id;
        } else if ($isOpen == 0) {
            $menuname = $menu->menu_name;
        }
        if ($menuid == $menu->id && $isOpen == 0) {
            $iscurrentMenuAvaible = 0;
        }
        if ($iscurrentMenuAvaible != 0) {

            $objCategory->menu_id = $menuid;
            $categories = $objCategory->getCategoryByMenuId();
            $total_cats = count($categories);
            $half = round($total_cats / 2);
            $index = 0;
            $loop_index = 0;
            $menuitems = array();
            while ($index < $total_cats) {
                $category = $categories[$index];
                $products = Easy_Way_Api::getMenuItems($category->cat_id);
                $menuitems[] = $products;
                $index++;
            }
            $restaurant_info->menuitem = $menuitems;
        }
    }

    if ($restaurant_info) {
        echo json_encode($restaurant_info);
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'Invalid Restaurant Id'));
    }
    exit;
} else

if ((isset($_GET['op']) && ($_GET['op'] == 'create')) && (isset($_GET['type']) && ($_GET['type']) == 'user')) {


    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $loggedinuser->cust_email = $_POST['email'];
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'Email address not entered!'));
        exit;
    }
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $loggedinuser->password = trim($_POST['password']);
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'Password not entered!'));
        exit;
    }
    if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
        $loggedinuser->cust_your_name = trim($_POST['first_name']);
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'First name not entered!'));
        exit;
    }
    if (isset($_POST['last_name']) && !empty($_POST['last_name'])) {
        $loggedinuser->LastName = trim($_POST['last_name']);
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'last name not entered!'));
        exit;
    }
    if (isset($_POST['address1']) && !empty($_POST['address1'])) {
        $loggedinuser->street1 = trim($_POST['address1']);
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'Street 1 not entered!'));
        exit;
    }
    if (isset($_POST['address2']) && !empty($_POST['address2'])) {
        $loggedinuser->street2 = trim($_POST['address2']);
    }
    if (isset($_POST['city']) && !empty($_POST['city'])) {
        $loggedinuser->cust_ord_city = trim($_POST['city']);
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'City not entered!'));
        exit;
    }
    if (isset($_POST['state']) && !empty($_POST['state'])) {
        $loggedinuser->cust_ord_state = trim($_POST['state']);
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'State not entered!'));
        exit;
    }
    if (isset($_POST['zip']) && !empty($_POST['zip'])) {
        $loggedinuser->cust_ord_zip = trim($_POST['zip']);
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'Zip Code not entered!'));
        exit;
    }
    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $loggedinuser->cust_phone1 = trim($_POST['phone']);
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'Phone number not entered!'));
        exit;
    }
    if (isset($_POST['saddress1']) && !empty($_POST['saddress1'])) {
        $loggedinuser->delivery_street1 = trim($_POST['saddress1']);
    } else {
        $loggedinuser->delivery_street1 = '';
    }
    if (isset($_POST['saddress2']) && !empty($_POST['saddress2'])) {
        $loggedinuser->delivery_street2 = trim($_POST['saddress2']);
    } else {
        $loggedinuser->delivery_street2 = '';
    }
    if (isset($_POST['scity']) && !empty($_POST['scity'])) {
        $loggedinuser->delivery_city1 = trim($_POST['scity']);
    } else {
        $loggedinuser->delivery_city1 = '';
    }
    if (isset($_POST['cstate']) && !empty($_POST['cstate'])) {
        $loggedinuser->delivery_state1 = trim($_POST['cstate']);
    } else {
        $loggedinuser->delivery_state1 = '';
    }
    if (isset($_POST['czip']) && !empty($_POST['czip'])) {
        $loggedinuser->deivery1_zip = trim($_POST['czip']);
    } else {
        $loggedinuser->deivery1_zip = '';
    }
    if (isset($_POST['restaurant_id']) && !empty($_POST['restaurant_id'])) {
        $loggedinuser->resturant_id = $_POST['restaurant_id'];
        $objRestaurant = $objRestaurant->getDetail($_POST['restaurant_id']);
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'Restaurant Id not entered!'));
        exit;
    }

    $register_result = $loggedinuser->register($objRestaurant, $objMail);

    if ($register_result == true) {
        echo json_encode(array('success' => '1'));
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'User not created.'));
    }
    exit;
} else

if ((isset($_GET['op']) && ($_GET['op'] == 'update')) && (isset($_GET['type']) && ($_GET['type']) == 'user')) {
    if (isset($_SESSION['token']) && isset($_POST['authToken']) && $_POST['authToken'] == $_SESSION['token']) {

        $user_info = Easy_Way_Api::getIdFromAuthToken($_POST['authToken']);
        $user = $loggedinuser->loginbyid($user_info['user_id']);

        if (isset($_GET['field']) && ($_GET['field'] == 'password')) {

            if (is_null($user)) {
                echo json_encode(array('success' => '0', 'msg' => 'User does not exist!'));
                exit;
            } else {
                $loggedinuser->destroysession();
                $loggedinuser = $user;
                $address1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));
                $loggedinuser->street1 = $address1[0];
                $loggedinuser->street2 = '';
                if (count($address1) > 1)
                    $loggedinuser->street2 = $address1[1];

                $address1 = explode('~', trim($loggedinuser->delivery_address1, '~'));

                $loggedinuser->delivery_street1 = $address1[0];
                $loggedinuser->delivery_street2 = '';
                if (count($address1) > 1) {
                    $loggedinuser->delivery_street2 = $address1[1];
                }
                if (isset($_POST['password']) && !empty($_POST['password'])) {
                    $loggedinuser->password = trim($_POST['password']) == '' ? $loggedinuser->password : $_POST['password'];
                } else {
                    echo json_encode(array('success' => '0', 'msg' => 'Password not entered!'));
                    exit;
                }

                if ($loggedinuser->update()) {
                    echo json_encode(array('success' => '1'));
                    exit;
                } else {
                    echo json_encode(array('success' => '0', 'msg' => 'Cannot change user password!'));
                    exit;
                }
            }
        } else if (isset($_GET['field']) && ($_GET['field'] == 'profile')) {

            if (is_null($user)) {
                echo json_encode(array('success' => '0', 'msg' => 'User does not exist!'));
                exit;
            } else {
                $loggedinuser->destroysession();
                $loggedinuser = $user;
                extract($_POST);
                if (isset($first_name)) {
                    if (!empty($first_name)) {
                        $loggedinuser->cust_your_name = $first_name;
                    } 
                }
                if (isset($last_name)) {
                    if (!empty($last_name)) {
                        $loggedinuser->LastName = $last_name;
                    }
                }
                if (isset($address1)) {
                    if (!empty($address1)) {
                        $loggedinuser->street1 = $address1;
                    } 
                }
                if (isset($address2)) {
                    if (!empty($address2)) {
                        $loggedinuser->street2 = $address2;
                    }
                }
                if (isset($city)) {
                    if (!empty($city)) {
                        $loggedinuser->cust_ord_city = $city;
                    } 
                }
                if (isset($state)) {
                    if (!empty($state)) {
                        $loggedinuser->cust_ord_state = $state;
                    } 
                }
                if (isset($zip)) {
                    if (!empty($zip)) {
                        $loggedinuser->cust_ord_zip = $zip;
                    } 
                }
                if (isset($phone)) {
                    if (!empty($phone)) {
                        $loggedinuser->cust_phone1 = $phone;
                    } 
                }

                if ($loggedinuser->update()) {
                    echo json_encode(array('success' => '1'));
                    exit;
                } else {
                    echo json_encode(array('success' => '0', 'msg' => 'Error updating user profile!'));
                    exit;
                }
            }
        } else {
            echo json_encode(array('success' => '0', 'msg' => 'Unable to Update!'));
            exit;
        }
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'Invalid tokken!'));
        exit;
    }
} else

if ((isset($_GET['op']) && ($_GET['op'] == 'new')) && (isset($_GET['type']) && ($_GET['type']) == 'order')) {
    if (isset($_SESSION['token']) && isset($_POST['authToken']) && $_POST['authToken'] == $_SESSION['token']) {
        
        $myAuthToken = $_POST['authToken'];
        $auth_user = Easy_Way_Api::getIdFromAuthToken($myAuthToken);
        $restaurant_id = $_POST['restaurant_id'];
        if ($auth_user['restaurant_id'] == $restaurant_id) {
            $userId = $auth_user['user_id'];
            $rest_url = Easy_Way_Api::getRestaurantUrl($restaurant_id);
            $objRestaurant = $objRestaurant->getDetailbyUrl($rest_url);
            if ($objRestaurant->isOpenHour != 1) {
                echo json_encode(array('success' => '0', 'msg' => 'Restaurant closed'));
                exit;
            }
            $cart->restaurant_id = $objRestaurant->id;
            $cart->sales_tax_ratio = $objRestaurant->tax_percent;

            if ($objRestaurant->delivery_option == 'delivery_zones') {
                $objRestaurant->delivery_charges = $objRestaurant->zone1_delivery_charges;
                $objRestaurant->order_minimum = $objRestaurant->zone1_min_total;
            }

            $user = $loggedinuser->loginbyid($userId);
            $loggedinuser->destroysession();
            $loggedinuser = $user;

            $loggedinuser->resturant_id = $objRestaurant->id;
            $delivery_type = strtoupper($_POST['delivery_type']);
            if ($objRestaurant->delivery_offer == 1 && $delivery_type == "1") {
                $cart->delivery_type = cart::Delivery;
            } else if ($delivery_type == "2") {
                $cart->delivery_type = cart::Pickup;
            } else {
                echo json_encode(array('success' => '0', 'msg' => 'Please specify delivery type'));
                exit;
            }
            
            if (empty($_POST['items'])) {
                echo json_encode(array('success' => '0', 'msg' => 'No items selected!'));
                exit;
            }
            
            $item_list = $_POST['items'];
            foreach ($item_list as $item) {
                $product_id = $item['item_id'];
                $quantity = $item['quantity'];
                if(empty($product_id)){
                    echo json_encode(array('success' => '0', 'msg' => 'Item id is required!'));
                    exit;
                }
                if(empty($quantity)){
                    echo json_encode(array('success' => '0', 'msg' => 'Quantity is required!'));
                    exit;
                }
//                $item_for = $item['item_for'];
//                $requestnote = $item['request_note'];
                $item_for = '';
                $requestnote = '';
                $product = @product::getdetail($product_id);
                $attribute_index = 1;
                $product_to_order = new product();
                if ($quantity == '' || $quantity <= 0) {
                    $quantity = 1;
                }
                $product_to_order->prd_id = $product->prd_id;
                $product_to_order->category_id = $product->sub_cat_id;

                $product_to_order->item_code = $product->item_code;
                $product_to_order->cat_name = stripslashes($product->cat_name);
                $product_to_order->quantity = $quantity;
                $product_to_order->item_title = stripslashes($product->item_title);
                $product_to_order->retail_price = $product->retail_price;
                $product_to_order->sale_price = $product->retail_price;
                $product_to_order->item_for = $item_for;
                $product_to_order->requestnote = $requestnote;

                $product_to_order->associations = array();
                $product_to_order->attributes = array();
                $product_to_order->distinct_attributes = array();

                $cart->addProduct($product_to_order);
            }
            //------------------------------------------------------------------//

            if ($cart->delivery_type == cart::Delivery) {
                $objRestaurant->delivery_charges = $cart->delivery_charges();
                $cart->rest_delivery_charges = $objRestaurant->delivery_charges;
            }
            if ($cart->sub_total < $objRestaurant->order_minimum) {
                $error_msg = "$" . $objRestaurant->order_minimum . " of food required to checkout. Please add more items";
                echo json_encode(array('success' => '0', 'msg' => $error_msg));
                exit;
            }


            $address1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));

            $loggedinuser->street1 = $address1[0];
            $loggedinuser->street2 = '';
            if (count($address1) > 1)
                $loggedinuser->street2 = $address1[1];

            $address1 = explode('~', trim($loggedinuser->delivery_address1, '~'));

            $loggedinuser->delivery_street1 = $address1[0];
            $loggedinuser->delivery_street2 = '';
            if (count($address1) > 1) {
                $loggedinuser->delivery_street2 = $address1[1];
            }
            $cart_total = $cart->grand_total();
            $is_guest = 0;

            if(empty($_POST['payment_method'])){
                echo json_encode(array('success' => '0', 'msg' => 'Payment method not entered!'));
                exit;
            }

            if ($_POST['payment_method'] == "1") {
                $success = 0;
                if ($objRestaurant->payment_gateway == "authoriseDotNet")
                    $objRestaurant->payment_gateway = "AuthorizeNet";

                
                if (isset($loggedinuser->street2)) {
                    $street2 = $loggedinuser->street2;
                } else {
                    $street2 = '';
                }
                $x_exp_date = $_POST['x_exp_date'];
                if(empty($_POST['x_exp_date'])){
                    echo json_encode(array('success' => '0', 'msg' => 'Expiry date is required!'));
                    exit;
                }
                
                $x_first_name = $loggedinuser->cust_your_name;
                $x_last_name = $loggedinuser->LastName;
                $x_address = trim($loggedinuser->street1 . " " . $street2);
                $x_city = $loggedinuser->cust_ord_city;
                $x_state = $loggedinuser->cust_ord_state;
                $x_zip = $loggedinuser->cust_ord_zip;
                $x_phone = $loggedinuser->cust_phone1;
                $x_email = $loggedinuser->cust_email;

                if(empty($_POST['x_card_num'])){
                    echo json_encode(array('success' => '0', 'msg' => "Enter Credit card number!"));
                    exit;
                }

                $_SESSION['GATEWAY_RESPONSE'] = null;
                $api = true;
                require_once '../classes/gateways/' . $objRestaurant->payment_gateway . '.php';
                
                if ($objRestaurant->payment_gateway == "AuthorizeNet" && isset($_SESSION['GATEWAY_RESPONSE']) && $_SESSION['GATEWAY_RESPONSE'] != null){
                    echo json_encode(array('success' => '0', 'msg' => $_SESSION['GATEWAY_RESPONSE']));
                    exit;
                } else{
                    if($objRestaurant->payment_gateway != "AuthorizeNet"){
                        if ($response != 1) {
                            if ($response == 2 || $response == 3 || $response == 4) {
                                $error_msg = "This transaction has been declined.";
                            } else if ($response == 5) {
                                $error_msg = "A valid amount is required.";
                            } else if ($response == 6) {
                                $error_msg = "The credit card number is invalid.";
                            } else if ($response == 7) {
                                $error_msg = "The credit card expiration date is invalid.";
                            } else if ($response == 8) {
                                $error_msg = "The credit card has expired.";
                            } else if ($response == 9) {
                                $error_msg = "The ABA code is invalid.";
                            } else if ($response == 10) {
                                $error_msg = "The account number is invalid.";
                            } else if ($response == 11) {
                                $error_msg = "A duplicate transaction has been submitted.";
                            } else if ($response == 12) {
                                $error_msg = "An authorization code is required but not present.";
                            } else if ($response == 13) {
                                $error_msg = "The merchant API Login ID is invalid or the account is inactive.";
                            } elseif ($response == 14) {
                                $error_msg = "The Referrer or Relay Response URL is invalid.";
                            } elseif ($response == 15) {
                                $error_msg = "The transaction ID is invalid.";
                            } else if ($response == 16) {
                                $error_msg = "The transaction was not found.";
                            } elseif ($response == 17) {
                                $error_msg = "The merchant does not accept this type of credit card.";
                            }

                            if ($response == 18) {
                                $error_msg = "ACH transactions are not accepted by this merchant.";
                            } elseif ($response == 19 || $response == 20 || $response == 21 || $response == 12 || $response == 23) {
                                $error_msg = "An error occurred during processing. Please try again in 5 minutes.";
                            } elseif ($response == 24) {
                                $error_msg = "The Nova Bank Number or Terminal ID is incorrect. Call Merchant Service Provider.";
                            }
                            echo json_encode(array('success' => '0', 'msg' => $error_msg));
                            exit;
                        }
                    }
                }

                if ($success == 1) {
                    $secure_data = $_POST['x_card_num'];
                    if ($objRestaurant->tokenization == 1) {
                        $type = substr($secure_data, 0, 1);
                    }
                    $cc = substr($secure_data, -4, 4);

                    $_POST['x_card_num'] = '';
                } if($success == 0 ){
                    echo json_encode(array('success' => '0', 'msg' => "Invalid Credit Card number!"));
                    exit;
                }
            }

            if (!is_numeric($loggedinuser->id) || $loggedinuser->id == 0) {
                $loggedinuser->createNewUser();
                $is_guest = 1;
            }

            if (isset($_POST['serving_time'])) {
                $serving_time = $_POST['serving_time'];
            } else {
                $serving_time = 0;
            }
            $serving_date = date("m-d-Y", time());
            $asap = 0;
            if ($serving_time == '0') {
                $asap = 1;
                $serving_time = date("H:i");
            }
            $serving_date = $serving_date . " " . $serving_time;
            $payment_method = ($_POST['payment_method'] == "1" ? "Credit Card" : "Cash");
            $address = $loggedinuser->get_delivery_address(0) . ", " . $loggedinuser->get_delivery_zip();
            $invoice_number = '';
            if (isset($_POST['invoice_number'])) {
                $invoice_number = $_POST['invoice_number'];
            }
            if (!isset($type))
                $type = '';
            if (!isset($cc))
                $cc = '';
            if (!isset($gateway_token))
                $gateway_token = '';
            if (!isset($transaction_id)) {
                $transaction_id = 0;
            }
            $del_special_notes = "";
            if (isset($_POST['special_notes'])) {
                $del_special_notes = $_POST['special_notes'];
            }

            $cart->invoice_number = $invoice_number;
            $cart->payment_menthod = $_POST['payment_method'];
            $cart->special_notes = $del_special_notes;
            $platform_used = 1;

            $cart->createNewOrder($loggedinuser->id, $address, $serving_date, $asap, $payment_method, $invoice_number, 1, $type, $cc, $gateway_token, $transaction_id, $platform_used, $is_guest, $del_special_notes);
            $cart->order_created = 1;
            $cart->save();

            if (isset($_SESSION["abandoned_cart_id"]) && $_SESSION["abandoned_cart_id"] > 0) {
                $abandoned_carts->delete_abandoned_cart($_SESSION["abandoned_cart_id"]);
            }

            $objChargify = new chargifyMeteredUsage();
            $objChargify->sendMeteredUsageToChargify($cart->restaurant_id, intval($cart->grand_total()), $payment_method);

            if ($objRestaurant->order_destination == "POS") {
                //Create a function object and call to posttoORDRSRVR function
                $fun->posttoORDRSRVR($cart->order_id);
                echo json_encode(array('success' => '1'));
                exit;
            } else {
                echo json_encode(array('success' => '0', 'msg' => "Error!"));
                exit;
            }
        } else {
            echo json_encode(array('success' => '0', 'msg' => "Restaurant id is not valid for this user!"));
            exit;
        }
    }else {
        echo json_encode(array('success' => '0', 'msg' => 'Invalid tokken!'));
        exit;
    }
    exit;
} else

if ((isset($_GET['op']) && ($_GET['op'] == 'fetch')) && (isset($_GET['field']) && ($_GET['field']) == 'order')) {

    if (isset($_POST['order_id']) && !empty($_POST['order_id'])) {
        $orderId = $_POST['order_id'];
    } else {
        echo json_encode(array('success' => '0', 'msg' => "Order id not entered!"));
        exit;
    }
    $order_qry = dbAbstract::Execute("SELECT OrderID, Totel as total,coupon_discount, driver_tip,delivery_chagres,Tax,UserID,order_receiving_method,DesiredDeliveryDate,submit_time,asap_order,payment_method,fax_sent,fax_date,DelSpecialReq,DeliveryAddress,cat_id,OrderDate,Approve,payment_approv,coupons,order_confirm,est_delivery_time,vip_discount,transaction_id,refund_request,is_guest,platform_used
                                              FROM ordertbl
                                              WHERE OrderID =" . $orderId);

    $order_rs = dbAbstract::returnAssoc($order_qry);
    if (!$order_rs) {
        echo json_encode(array('success' => '0', 'msg' => "Order not found!"));
        exit;
    }
    $order_detail_qry = dbAbstract::Execute("SELECT item_title , 0 as item_total_price,quantity as item_qty,retail_price as item_price,RequestNote as special_notes,extra, pid as item_id ,associations as associated_items, item_for,prd.pos_id
                                                     FROM orderdetails  ord Inner join product prd
                                                     on ord.pid=prd.prd_id
                                                     WHERE orderid = " . $order_rs['OrderID'] . "");
    $cust_qry = dbAbstract::Execute("SELECT cust_your_name, LastName ,cust_phone1
                                             FROM customer_registration
                                             WHERE  id = " . $order_rs['UserID'] . "	");
    $cust_rs = dbAbstract::returnAssoc($cust_qry);
    $rest_qry = dbAbstract::Execute("SELECT id as restid, name as restname, phone
                                             FROM resturants
                                             WHERE  id = " . $order_rs['cat_id'] . "	");
    $rest_rs = dbAbstract::returnAssoc($rest_qry);
    $associated_items_price = 0;
    $extra_items_price = 0;
    $index = 1;

    while ($order_detail_rs = dbAbstract::returnObject($order_detail_qry)) {
        $assoc_split_arr1 = explode('~', $order_detail_rs->associated_items);

        $order_detail_rs->item_title = $fun->replaceSpecial($order_detail_rs->item_title);
        $order_detail_rs->special_notes = $fun->replaceSpecial($order_detail_rs->special_notes);

        for ($j = 0; $j < count($assoc_split_arr1); $j++) {
            $assoc_split_arr2 = explode('|', $assoc_split_arr1[$j]);
            if (count($assoc_split_arr2) > 1) {
                $associated_items_price += $assoc_split_arr2[1];
            }
        }
        $extra_split_arr1 = explode('~', $order_detail_rs->extra);

        for ($k = 0; $k < count($extra_split_arr1); $k++) {
            $extra_split_arr2 = explode('|', $extra_split_arr1[$k]);
            if (count($extra_split_arr2) > 1) {
                $extra_items_price += $extra_split_arr2[1];
            }
        }

        $order_detail_rs->item_total_price = ($order_detail_rs->item_price + $extra_items_price + $associated_items_price) * $order_detail_rs->item_qty;
        $order_detail_rs->extra = ltrim(str_replace('~', '|', str_replace('|', '=', $order_detail_rs->extra)), "|");
        $order_detail_rs->associated_items = ltrim(str_replace('~', '|', str_replace('|', '=', $order_detail_rs->associated_items)), "|");
        $product_rs[$index] = $order_detail_rs;
        $index++;
    }
    //make associative array
    $order_rs['DesiredDeliveryDate'] = str_replace("as soon as possible", date("H:i", strtotime($order_rs['submit_time'])), strtolower($order_rs['DesiredDeliveryDate']));
    $arr_Date = explode("-", $order_rs['DesiredDeliveryDate']);
    $arr_Time = explode(" ", $arr_Date[2]);

    $order["ORDERINFO"] = array(
        "order_id" => $order_rs['OrderID'],
        "Total" => $order_rs['total'],
        "delivery_method" => $order_rs['order_receiving_method'],
        "delivery_time_reqested" => $arr_Time[1],
        "delivery_date_requested" => date("Y") . "-" . $arr_Date[0] . "-" . $arr_Date[1],
        "time_of_order" => date("H:i", strtotime($order_rs['submit_time'])),
        "date_of_order" => date("Y-m-d", strtotime($order_rs['submit_time'])),
        "delivery_address" => $fun->replaceSpecial($order_rs['DeliveryAddress']),
        "customer_name" => $fun->replaceSpecial($cust_rs['cust_your_name']),
        "customer_phone" => $cust_rs['cust_phone1'],
        "special_instructions" => $fun->replaceSpecial($order_rs['DelSpecialReq'])
    );
    
    $order["ITEMS"] = array(
        "item_title" => $product_rs['1']->item_title,
        "item_total_price" => $product_rs['1']->item_total_price,
        "item_qty" => $product_rs['1']->item_qty,
        "item_price" => $product_rs['1']->item_price,
        "item_id" => $product_rs['1']->item_id,
        );


    if ($order_rs['coupon_discount'] == null

        )$order_rs['coupon_discount'] = 0;

    $sub_total = $order_rs['total'] - ($order_rs['coupon_discount'] + $order_rs['driver_tip'] + $order_rs['delivery_chagres'] + $order_rs['Tax']);

    $order["TOTAL"] = array(
        "sub_total" => number_format($sub_total, 2),
        "driver_charge" => number_format($order_rs['driver_tip'], 2),
        "delivery_charges" => number_format($order_rs['delivery_chagres'], 2),
        "total" => number_format($order_rs['total'], 2),
        "tax" => number_format($order_rs['Tax'], 2),
        "tip_amt" => $order_rs['driver_tip'],
        "PAYMENT" => $order_rs['total']
    );
    //Post order here
    $encoded = json_encode($order);
    print_r($encoded);
    exit;
} else

if (isset($_GET['op']) && ($_GET['op'] == 'getToken')) {

     if (!isset($_REQUEST['email']) && !isset($_REQUEST['password']) && !isset($_REQUEST['restaurant_id'])) {
        echo json_encode(array('success' => '0', 'msg' => 'All parameters are required!'));
        exit;
    }

    if (isset($_REQUEST['email']) && empty($_REQUEST['email'])) {
        echo json_encode(array('success' => '0', 'msg' => 'Email not entered!'));
        exit;
    }
    if (isset($_REQUEST['password']) && empty($_REQUEST['password'])) {
        echo json_encode(array('success' => '0', 'msg' => 'Password not entered!'));
        exit;
    }
    if (isset($_REQUEST['restaurant_id']) && empty($_REQUEST['restaurant_id'])) {
        echo json_encode(array('success' => '0', 'msg' => 'Restaurant id not entered!'));
        exit;
    }
    
    $objRestaurant = $objRestaurant->getDetail($_REQUEST['restaurant_id']);
    
    if($objRestaurant == false){
        echo json_encode(array('success' => '0', 'msg' => 'Invalid Restaurant Id!'));
        exit;
    }
    $user_email = $_REQUEST['email'];
    $user = $loggedinuser->login($_REQUEST['email'], $_REQUEST['password'], $objRestaurant->id);

    if (is_null($user)) {
        echo json_encode(array('success' => '0', 'msg' => 'User does not exist!'));
        exit;
    } else {
        $token = Easy_Way_Api::getToken($_REQUEST['email'], $_REQUEST['password'], $objRestaurant->id);
    }
    if ($token) {
        $_SESSION['token'] = $token;
        echo json_encode(array('success' => '1', 'msg' => 'Authorised', 'token' => $token));
        exit;
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'Failed To authorise'));
        exit;
    }
    exit;
} else {
    echo json_encode(array('success' => '0', 'msg' => 'Invalid Request!'));
    exit;
}
exit;
?>