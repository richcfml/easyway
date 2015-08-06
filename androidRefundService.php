<?php
require_once("includes/config.php");
$jsonData = array();
$jsonTempData = array();
$message = array();
ini_set('display_errors', 1);
error_reporting(1);

if (isset($_REQUEST['op'])) {
    include "classes/restaurant.php";
    include("includes/class.phpmailer.php"); 

    $function_obj = new clsFunctions();
    $Objrestaurant = new restaurant();

    $op = $_REQUEST['op'];
    if ($op == 'refund') {
        $result = -1;
        $key = 'EASYWAY-REFUND-SECURE-KEY';

        if (isset($_REQUEST['password']) &&
                isset($_REQUEST['OrderId']) &&
                isset($_REQUEST['Slug']) &&
                !empty($_REQUEST['password']) &&
                !empty($_REQUEST['OrderId']) &&
                !empty($_REQUEST['Slug'])) {

            $mOrderID = $_REQUEST['OrderId'];
            $mPassword = $_REQUEST['password'];
            $restaurant_slug = $_REQUEST['Slug'];

            $Objrestaurant = getRestaurantDetailsBySlug($restaurant_slug, $Objrestaurant);
            $mRestaurantID = $Objrestaurant->id;
            $mResult = dbAbstract::Execute("SELECT refund_password FROM resturants WHERE id=" . $mRestaurantID);

            if (dbAbstract::returnRowsCount($mResult) > 0) {
                $mRow = dbAbstract::returnObject($mResult);
                if ($mPassword == $mRow->refund_password) {
                    $mResult = dbAbstract::Execute("SELECT R.id AS ID FROM resturants R INNER JOIN customer_registration CR ON CR.resturant_id=R.id INNER JOIN ordertbl O ON O.UserID=CR.id WHERE O.OrderID=" . $mOrderID);
                    if (dbAbstract::returnRowsCount($mResult) > 0) {
                        $mRow = dbAbstract::returnObject($mResult);
                        if ($mRestaurantID == $mRow->ID) {

                            $mResult = dbAbstract::Execute("SELECT IFNULL(transaction_id, '') AS transaction_id, IFNULL(payment_approv, 0) AS payment_approv, IFNULL(payment_method, '') AS payment_method FROM `ordertbl` WHERE OrderID=" . $mOrderID);
                            if (dbAbstract::returnRowsCount($mResult) > 0) {
                                $mRow = dbAbstract::returnObject($mResult);
                                if ((trim($mRow->transaction_id) == "") || (trim($mRow->transaction_id) == "0")) {
                                    $jsonTempData['status'] = 'failed';
                                    $jsonTempData['message'] = 'Order can not be refunded.';
                                }

                                if (trim($mRow->payment_approv) != "1") {
                                    $jsonTempData['status'] = 'failed';
                                    $jsonTempData['message'] = 'Payment not approved.';
                                }

                                if (strtolower(trim($mRow->payment_method)) != "credit card") {
                                    $jsonTempData['status'] = 'failed';
                                    $jsonTempData['message'] = 'Refund available for Credit Card orders only.';
                                }

                                $mTransactionID = $mRow->transaction_id;
                                $transactionid = $mTransactionID;

                                $mResult = dbAbstract::Execute("SELECT C.id AS UserID, C.cust_your_name AS FirstName, C.LastName AS LastName, C.cust_email AS Email, O.Totel AS Total, O.cdata AS CData, O.OrderDate AS OrderDate FROM `ordertbl` O INNER JOIN `customer_registration` C on O.UserID=C.id WHERE O.OrderID=" . $mOrderID);
                                if (dbAbstract::returnRowsCount($mResult) > 0) {
                                    $mRow = dbAbstract::returnObject($mResult);

                                    $gUID = $mRow->UserID;
                                    $amount = $mRow->Total;
                                    $cc = $mRow->CData;
                                    $mFirstName = $mRow->FirstName;
                                    $mLastName = $mRow->LastName;
                                    $mEmail = $mRow->Email;
                                    $mOrderDate = $mRow->OrderDate;
                                    $mTotal = $mRow->Total;

                                    $mResult = dbAbstract::Execute("SELECT IFNULL(payment_gateway, '') AS payment_gateway, url_name AS url, IFNULL(phone, '') AS phone, IFNULL(fax, '') AS fax, IFNULL(email, '') AS email FROM resturants WHERE id=" . $mRestaurantID);
                                    if (dbAbstract::returnRowsCount($mResult) > 0) {
                                        $mRow = dbAbstract::returnObject($mResult);
                                        if (trim($mRow->payment_gateway) != "") {
                                            $success = 0;
                                            $mURL = $mRow->url;
                                            $mPhone = $mRow->phone;
                                            $mFax = $mRow->fax;
                                            $mRestEmail = $mRow->email;
                                            if ($mRow->payment_gateway == "authoriseDotNet") {
                                                $mRow->payment_gateway = "AuthorizeNet";
                                            }

                                            $Objrestaurant = new restaurant();
                                            $Objrestaurant = $Objrestaurant->getDetail($mRestaurantID);
                                            require_once 'c_panel/admin_contents/gateways/' . $mRow->payment_gateway . '.php';
                                            if ($success == 1) {
                                                dbAbstract::Update("UPDATE ordertbl set payment_approv=0 where OrderID=" . $mOrderID);
                                                $mObjMail = new testmail();
                                                $message = "<br/> Dear Customer <br/><br/> Your Order Payment is refunded: <br/><br/> Order ID: " . $mOrderID . "<br/><br/> Order Date: " . $mOrderDate . "<br/><br/> Order Total: " . $mTotal . " <br/> ";
                                                $message .=" <a href='http://www.easywayordering.com/" . $mURL . "/'>http://www.easywayordering.com/" . $mURL . "/</a>";
                                                $message .="<br/><br/>Phone: " . $mPhone . "";
                                                $message .="<br/><br/>Fax: " . $mFax . "";
                                                $subject = "Easyway Ordering Refund";
                                                $mObjMail->sendTo($message, $subject, $mEmail);

                                                $message = "<br/> Dear Restaurant Owner <br/><br/> Order Payment is refunded: <br/><br/> Order ID: " . $mOrderID . "<br/><br/> Order Date: " . $mOrderDate . "<br/><br/> Order Total: " . $mTotal . " <br/> ";
                                                $message .=" <a href='http://www.easywayordering.com/" . $mURL . "/'>http://www.easywayordering.com/" . $mURL . "/</a>";
                                                $message .="<br/><br/>Phone: " . $mPhone . "";
                                                $message .="<br/><br/>Fax: " . $mFax . "";
                                                $mObjMail->sendTo($message, $subject, $mRestEmail);

                                                $jsonTempData['status'] = 'success';
                                                $jsonTempData['message'] = "Order Refunded successfully.||" . $transaction_id;
                                            } else {
                                                $jsonTempData['status'] = 'failed';
                                                $jsonTempData['message'] = "Order Refund failed. Gateway Message: " . $message;
                                            }
                                        } else {
                                            $jsonTempData['status'] = 'failed';
                                            $jsonTempData['message'] = "Error in fetching Payment gateway for restaurant.";
                                        }
                                    } else {
                                        $jsonTempData['status'] = 'failed';
                                        $jsonTempData['message'] = "Error occurred in fetching Payment gateway for restaurant.";
                                    }
                                } else {
                                    $jsonTempData['status'] = 'failed';
                                    $jsonTempData['message'] = "Error occurred in fetching Order details.";
                                }
                            } else {
                                $jsonTempData['status'] = 'failed';
                                $jsonTempData['message'] = "Order not found.";
                            }
                        } else {
                            $jsonTempData['status'] = 'failed';
                            $jsonTempData['message'] = 'Error occurred in restaurant and order validation.';
                        }
                    } else {
                        $jsonTempData['status'] = 'failed';
                        $jsonTempData['message'] = "Error occurred in restaurant validation.";
                    }
                } else {
                    $jsonTempData['status'] = 'failed';
                    $jsonTempData['message'] = 'Password mismatch.';
                }
            } else {
                $jsonTempData['status'] = 'failed';
                $jsonTempData['message'] = 'Order not found.';
            }
        } else {
            $jsonTempData['status'] = 'failed';
            $jsonTempData['message'] = 'All parameters are required';
        }
    } else {
        $jsonTempData['status'] = 'failed';
        $jsonTempData['message'] = 'Unknown Operation';
    }
} else {
    $jsonTempData['status'] = 'failed';
    $jsonTempData['message'] = 'Operation Not specified';
}

function getRestaurantDetailsBySlug($restaurant_slug, $Objrestaurant) {
    return $Objrestaurant->getDetailbyUrl($restaurant_slug);
}

$jsonData[] = $jsonTempData;

$outputArr = array();
$outputArr['Response'] = $jsonData;

print_r(json_encode($outputArr));
?>