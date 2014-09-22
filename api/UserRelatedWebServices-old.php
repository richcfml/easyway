<?php

include "../includes/config.php";
require_once 'function.php';


include "../classes/restaurant.php";
include "../classes/users.php";
include "../classes/validater.php";
require_once("../classes/trackers.php");
require "../includes/class.phpmailer.php";
require_once('../classes/valutec.php');
require_once('../classes/menu.php');
require_once('../classes/category.php');
require_once('../classes/product.php');
require_once('../classes/cart.php');
require_once('../lib/cdyne/cdyne.php');
require_once('../classes/abandoned_carts.php');
require_once('../classes/chargify.php');



$validater = new validate();
$objRestaurant = new restaurant();
$objMenu = new menu();
$objCategory = new category();
$product = new product();
$cart = new cart();
$objcdyne = new cydne();
$loggedinuser = new users();
$abandoned_carts = new abandoned_carts();

session_start();

if ((isset($_GET['op']) && ($_GET['op'] == 'fetch')) && (isset($_GET['field']) && ($_GET['field']) == 'restaurant')) {

    $myRestId = $_POST['restaurantId'];
    $qry = mysql_query("select * from resturants where id = '" . $myRestId . "'");
    $rest_qry = mysql_fetch_object($qry);
    $objRestaurant = $objRestaurant->getDetailbyUrl($rest_qry->url_name);

    $objMenu->restaurant_id = $myRestId;
    $restaurant_info = $objRestaurant->getDetail($myRestId);
    $menuname = '';
    $menuid = (isset($_GET['menuid']) ? $_GET['menuid'] : "");
    $menus = $objMenu->getmenu(1);
    $isOpen = true;
    $iscurrentMenuAvaible = 1;
    $currentMenuTimings = "";
    for ($i = 0; $i < count($menus); $i++) {
        $menu = $menus[$i];
        $isOpen = $menu->isAvailable();

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
            $categories = $objCategory->getcategories();
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
}

if ((isset($_GET['op']) && ($_GET['op'] == 'create')) && (isset($_GET['type']) && ($_GET['type']) == 'user')) {
    $myUsername = $_POST['username'];
    $myFirstName = $_POST['firstName'];
    $myLastName = $_POST['lastName'];
    $myContactNo = $_POST['contactNo'];
    $myEmail = $_POST['email'];
    $myPassword = $_POST['password'];

    $myValsArr = array(0 => $myFirstName, 1 => $myLastName, 2 => $myContactNo, 3 => $myEmail, 4 => $myPassword, 5 => $myUsername);
    $myNameArr = array(0 => 'firstname', 1 => 'lastname', 2 => 'phone', 3 => 'email', 4 => 'password', 5 => 'username');
    $status = Easy_Way_Api::sendToDatabase($myNameArr, $myValsArr, 'id', '', '', 'users');
    if ($status == "success") {
        echo json_encode(array('success' => '1'));
    } else {
        echo json_encode(array('success' => '0', 'msg' => $status));
    }
}

if (isset($_GET['op']) && ($_GET['op'] == 'getToken') && isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
    $token = Easy_Way_Api::getToken($_REQUEST['username'], $_REQUEST['password']);
    if ($token) {
        $_SESSION['token'] = $token;
        echo json_encode(array('success' => '1', 'msg' => 'Authorised', 'token' => $token));
    } else {
        echo json_encode(array('success' => '0', 'msg' => 'Failed To authorise'));
    }
} else if (isset($_SESSION['token'])) {
    if (isset($_POST['authToken'])) {
        if ($_POST['authToken'] == $_SESSION['token']) {
            if ((isset($_GET['op']) && ($_GET['op'] == 'update')) && (isset($_GET['type']) && ($_GET['type']) == 'user')) {
                if (isset($_GET['field']) && ($_GET['field'] == 'password')) {
                    $myAuthToken = $_POST['authToken'];
                    $oldPassword = $_POST['oldpassword'];
                    $newPassword = $_POST['newpassword'];

                    $myValsArr = array(0 => $newPassword);
                    $myNameArr = array(0 => 'password');

                    $requiredId = Easy_Way_Api::getIdFromAuthToken($myAuthToken);
                    $status = Easy_Way_Api::sendToDatabase($myNameArr, $myValsArr, 'id', $requiredId, $oldPassword, 'users');

                    if ($status == "success") {
                        echo json_encode(array('success' => '1'));
                    } else {
                        echo json_encode(array('success' => '0', 'msg' => $status));
                    }
                } else {


                    $myAuthToken = $_POST['authToken'];
                    $myFirstName = $_POST['firstName'];
                    $myLastName = $_POST['lastName'];
                    $myContactNo = $_POST['contactNo'];

                    $myValsArr = array(0 => $myFirstName, 1 => $myLastName, 2 => $myContactNo);
                    $myNameArr = array(0 => 'firstname', 1 => 'lastname', 2 => 'phone');

                    $requiredId = Easy_Way_Api::getIdFromAuthToken($myAuthToken);
                    $status = Easy_Way_Api::sendToDatabase($myNameArr, $myValsArr, 'id', $requiredId, '', 'users');
                    if ($status == "success") {
                        echo json_encode(array('success' => '1'));
                    } else {
                        echo json_encode(array('success' => '0', 'msg' => $status));
                    }
                }
            }

            if ((isset($_GET['op']) && ($_GET['op'] == 'save')) && (isset($_GET['field']) && ($_GET['field']) == 'address')) {
                $myAuthToken = $_POST['authToken'];
                $nameOfAddress = $_POST['nameOfAddress'];
                $addressOne = $_POST['address1'];
                $addressTwo = $_POST['address2'];
                $city = $_POST['city'];
                $zipCode = $_POST['zip'];
                $state = $_POST['state'];
                $phone = $_POST['phone'];

                $requiredId = Easy_Way_Api::getIdFromAuthToken($myAuthToken);

                $myValsArr = array(0 => $requiredId, 1 => $nameOfAddress, 2 => $addressOne, 3 => $addressTwo, 4 => $city, 5 => $zipCode, 6 => $state, 7 => $phone);
                $myNameArr = array(0 => 'id', 1 => 'name_of_address', 2 => 'address1', 3 => 'address2', 4 => 'city', 5 => 'zip', 6 => 'state', 7 => 'phone');

                $status = Easy_Way_Api::sendToDatabase($myNameArr, $myValsArr, 'id_no', '', '', 'user_address');
                if ($status == "success") {
                    echo json_encode(array('success' => '1'));
                } else {
                    echo json_encode(array('success' => '0', 'msg' => $status));
                }
            }

            if ((isset($_GET['op']) && ($_GET['op'] == 'get')) && (isset($_GET['field']) && ($_GET['field']) == 'address')) {
                $myAuthToken = $_POST['authToken'];
                $nameOfAddress = $_POST['nameOfAddress'];

                $requiredColumns = array(0 => 'name_of_address', 1 => 'address1', 2 => 'address2', 3 => 'city', 4 => 'zip', 5 => 'state', 6 => 'phone');
                $requiredId = Easy_Way_Api::getIdFromAuthToken($myAuthToken);

                Easy_Way_Api::getTuple($requiredColumns, 'id', $requiredId, 'name_of_address', $nameOfAddress, 'user_address');
            }

            if ((isset($_GET['op']) && ($_GET['op'] == 'save')) && (isset($_GET['type']) && ($_GET['type']) == 'card')) {
                $myAuthToken = $_POST['authToken'];
                $myName = $_POST['name'];
                $myCcno = $_POST['ccno'];
                $myCcv = $_POST['ccv'];
                $myExpMonth = $_POST['expmonth'];
                $myExpYear = $_POST['expyear'];
                $myType = $_POST['type'];
                $myBillAddr = $_POST['bill_addr'];
                $myBillAddr2 = $_POST['bill_addr2'];
                $myBillCity = $_POST['bill_city'];
                $myBillState = $_POST['bill_state'];
                $myBillZip = $_POST['bill_zip'];
                $myBillCountry = $_POST['bill_country'];
                $myBillPhone = $_POST['bill_phone'];

                $requiredId = Easy_Way_Api::getIdFromAuthToken($myAuthToken);
                $ccnoMd5 = md5($myCcno);

                $myValsArr = array(0 => $requiredId
                    , 1 => $myName
                    , 2 => $ccnoMd5
                    , 3 => $myCcv
                    , 4 => $myExpMonth
                    , 5 => $myExpYear
                    , 6 => $myType
                    , 7 => $myBillAddr
                    , 8 => $myBillAddr2
                    , 9 => $myBillCity
                    , 10 => $myBillState
                    , 11 => $myBillZip
                    , 12 => $myBillCountry
                    , 13 => $myBillPhone
                );
                $myNameArr = array(0 => 'user_id'
                    , 1 => 'user_name'
                    , 2 => 'ccno_md5'
                    , 3 => 'ccv'
                    , 4 => 'expMonth'
                    , 5 => 'expYear'
                    , 6 => 'type'
                    , 7 => 'addr'
                    , 8 => 'addr2'
                    , 9 => 'city'
                    , 10 => 'state'
                    , 11 => 'zip'
                    , 12 => 'country'
                    , 13 => 'phone'
                );

                $status = Easy_Way_Api::sendToDatabase($myNameArr, $myValsArr, 'card_table_id', '', '', 'credit_card_info');
                if ($status == "success") {
                    echo json_encode(array('success' => '1'));
                } else {
                    echo json_encode(array('success' => '0', 'msg' => $status));
                }
            }

            if ((isset($_GET['op']) && ($_GET['op'] == 'delete')) && (isset($_GET['type']) && ($_GET['type']) == 'card')) {

                $myAuthToken = $_POST['authToken'];
                $myName = $_POST['name'];

                $requiredId = Easy_Way_Api::getIdFromAuthToken($myAuthToken);
                Easy_Way_Api::deleteCard($requiredId, $myName);
                if ($status == "success") {
                    echo json_encode(array('success' => '1'));
                } else {
                    echo json_encode(array('success' => '0', 'msg' => $status));
                }
            }

            if ((isset($_GET['op']) && ($_GET['op'] == 'fetch')) && (isset($_GET['field']) && ($_GET['field']) == 'order')) {


                $myAuthToken = $_POST['authToken'];
                $myOrderId = $_POST['order_id'];

                $requiredColumns = array(0 => 'oid', 1 => 'rest_id', 2 => 'subtotal', 3 => 'tax', 4 => 'total', 5 => 'time', 6 => 'items');
                $requiredId = Easy_Way_Api::getIdFromAuthToken($myAuthToken);

                Easy_Way_Api::getTuple($requiredColumns, 'user_id', $requiredId, 'oid', $myOrderId, 'orders');
            }
            if ((isset($_GET['op']) && ($_GET['op'] == 'new')) && (isset($_GET['type']) && ($_GET['type']) == 'order')) {

                $myAuthToken = $_POST['authToken'];

                $requiredId = Easy_Way_Api::getIdFromAuthToken($myAuthToken);
                $userId = $_POST['id'];

                $user = $loggedinuser->loginbyid($id);
                $loggedinuser->destroysession();
                $loggedinuser = $user;

                $myItemId = $_POST['itemId'];
                $myQty = $_POST['qty'];


                $myTime = time();
                $myFirstName = $_POST['firstName'];
                $myLastName = $_POST['lastName'];
                $myAddress = $_POST['address'];
                $myAddressTwo = $_POST['addressTwo'];
                $myCity = $_POST['city'];
                $myZip = $_POST['zip'];
                $myState = $_POST['state'];
                $myCCId = $_POST['creditCardId'];
                $myCCMd5 = $_POST['creditCardNo'];
                $delivery = $_POST['deliveryOption'];

                if ($myCCMd5 != '') {
                    $myCCMd5 = md5($myCCMd5);
                }

                //------------------------------------------------------------------//

                $objMenu->restaurant_id = $myRestId;


                $menu = $menus[$i];
                $isOpen = $menu->isAvailable();

                $quantity = 1;
                $id = $_POST['product_id'];
                $product = product::getdetail($id);
                $cart_item_for = "";
                $notes = "";
                $item_for = "";
                $requestnote = "";
                $attribute_index = 1;
                $product_to_order = new product();
                if ($quantity == '' || $quantity <= 0)
                    $quantity = 1;
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


                $cart->sales_tax();
                $cart->grand_total();

                if ($cart->sub_total < $objRestaurant->order_minimum) {
                    echo "$" + $objRestaurant->order_minimum + " of food required to checkout. Please add more items";
                    exit;
                }



                $cart_total = $cart->grand_total();
                if (isset($_POST['btnconfirmorder1'])) {

                    $success = 0;
                    if ($objRestaurant->payment_gateway == "authoriseDotNet")
                        $objRestaurant->payment_gateway = "AuthorizeNet";

                    require_once '../classes/gateways/' . $objRestaurant->payment_gateway . '.php';

                    if ($success == 1) {
                        $secure_data = $_POST['creditCardNo'];
                        if ($objRestaurant->tokenization == 1) {

                            $type = substr($secure_data, 0, 1);
                        }
                        //Modified01082013
                        $cc = substr($secure_data, -4, 4);

                        $_POST['creditCardNo'] = '';
                        include "submit_order.php";
                    }
                } 

                if (isset($cart_delivery_charges)) {
                    $objRestaurant->delivery_charges = $cart_delivery_charges;
                    $cart->rest_delivery_charges = $objRestaurant->delivery_charges;
                }
                
                $arrRestaurant['VIPSECTION'] = '';
                $is_guest = 1;

                $serving_time = "";
                
                $address = $loggedinuser->get_delivery_address(0) . ", " . $loggedinuser->get_delivery_zip();
                $invoice_number = '';
                if (!isset($type))
                    $type = '';
                if (!isset($cc))
                    $cc = '';

                $gateway_token = '';
                $transaction_id = 0;
                
                $del_special_notes = "";

                $cart->invoice_number = $invoice_number;
                $cart->payment_menthod = $_POST['payment_method'];
                $cart->special_notes = $del_special_notes;

                $asap = "";
                $payment_method = "Credit Card";

                $cart->createNewOrder($userId, $address, $serving_date, $asap, $payment_method, $invoice_number, 1, $type, $cc, $gateway_token, $transaction_id, $platform_used, $is_guest, $del_special_notes);
                $cart->order_created = 1;
                $cart->save();

                if (isset($_SESSION["abandoned_cart_id"]) && $_SESSION["abandoned_cart_id"] > 0) {
                    $abandoned_carts->delete_abandoned_cart($_SESSION["abandoned_cart_id"]);
                }

                $objChargify = new chargifyMeteredUsage();
                $objChargify->sendMeteredUsageToChargify($cart->restaurant_id, intval($cart->grand_total()), $payment_method);


                if ($objRestaurant->order_destination == "POS") {
                    //Create a function object and call to posttoORDRSRVR function
                    $fun = new clsFunctions();
                    $fun->posttoORDRSRVR($cart->order_id);
                }
                echo __FILE__ . $iscurrentMenuAvaible;
                exit;

                //-----------------------------------------------------------------//

                $myValsArr = array(0 => $requiredId, 1 => $myRestaurantId, 2 => $myTime, 3 => $myFirstName, 4 => $myLastName, 5 => $myAddress, 6 => $myAddressTwo, 7 => $myCity, 8 => $myZip, 9 => $myState, 10 => $myCCId, 11 => $myCCMd5);
                $myNamesArr = array(0 => 'user_id', 1 => 'rest_id', 2 => 'time', 3 => 'first_name', 4 => 'last_name', 5 => 'address', 6 => 'address_two', 7 => 'city', 8 => 'zip', 9 => 'state', 10 => 'card_id', 11 => 'card_md5');

                $status = Easy_Way_Api::sendToDatabase($myNamesArr, $myValsArr, 'order_id', '', '', 'placed_order');

                $myValsArr = array(0 => $myItemId, 1 => $myQty);
                $myNamesArr = array(0 => 'item_id', 1 => 'qty');

                Easy_Way_Api::sendToDatabase($myNamesArr, $myValsArr, 'id', '', '', 'items_ordered');

                if ($status == "success") {
                    echo json_encode(array('success' => '1'));
                } else {
                    echo json_encode(array('success' => '0', 'msg' => $status));
                }
            }
//            if ((isset($_GET['op']) && ($_GET['op'] == 'new')) && (isset($_GET['type']) && ($_GET['type']) == 'order')) {
//
//                $myAuthToken = $_POST['authToken'];
//
//                $requiredId = Easy_Way_Api::getIdFromAuthToken($myAuthToken);
//                $myRestaurantId = $_POST['restaurantId'];
//
//                $myItemId = $_POST['itemId'];
//                $myQty = $_POST['qty'];
//
//
//                $myTime = $_POST['time'];
//                $myFirstName = $_POST['firstName'];
//                $myLastName = $_POST['lastName'];
//                $myAddress = $_POST['address'];
//                $myAddressTwo = $_POST['addressTwo'];
//                $myCity = $_POST['city'];
//                $myZip = $_POST['zip'];
//                $myState = $_POST['state'];
//                $myCCId = $_POST['creditCardId'];
//                $myCCMd5 = $_POST['creditCardNo'];
//
//                if ($myCCMd5 != '') {
//                    $myCCMd5 = md5($myCCMd5);
//                }
//
//                $myValsArr = array(0 => $requiredId, 1 => $myRestaurantId, 2 => $myTime, 3 => $myFirstName, 4 => $myLastName, 5 => $myAddress, 6 => $myAddressTwo, 7 => $myCity, 8 => $myZip, 9 => $myState, 10 => $myCCId, 11 => $myCCMd5);
//                $myNamesArr = array(0 => 'user_id', 1 => 'rest_id', 2 => 'time', 3 => 'first_name', 4 => 'last_name', 5 => 'address', 6 => 'address_two', 7 => 'city', 8 => 'zip', 9 => 'state', 10 => 'card_id', 11 => 'card_md5');
//
//                $status = Easy_Way_Api::sendToDatabase($myNamesArr, $myValsArr, 'order_id', '', '', 'placed_order');
//
//                $myValsArr = array(0 => $myItemId, 1 => $myQty);
//                $myNamesArr = array(0 => 'item_id', 1 => 'qty');
//
//                Easy_Way_Api::sendToDatabase($myNamesArr, $myValsArr, 'id', '', '', 'items_ordered');
//
//                if ($status == "success") {
//                    echo json_encode(array('success' => '1'));
//                } else {
//                    echo json_encode(array('success' => '0', 'msg' => $status));
//                }
//            }
            if ((isset($_GET['op']) && ($_GET['op'] == 'fetch')) && (isset($_GET['type']) && ($_GET['type']) == 'card')) {

                $myAuthToken = $_POST['authToken'];

                $requiredColumns = array(0 => 'user_id'
                    , 1 => 'user_name'
                    , 2 => 'ccno_md5'
                    , 3 => 'ccv'
                    , 4 => 'expMonth'
                    , 5 => 'expYear'
                    , 6 => 'type'
                    , 7 => 'addr'
                    , 8 => 'addr2'
                    , 9 => 'city'
                    , 10 => 'state'
                    , 11 => 'zip'
                    , 12 => 'country'
                    , 13 => 'phone'
                );
                $requiredId = Easy_Way_Api::getIdFromAuthToken($myAuthToken);

                Easy_Way_Api::getTuple($requiredColumns, 'user_id', $requiredId, '', '', 'credit_card_info');
            }
        }
    }
}

exit;
?>