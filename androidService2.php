<?php
require_once("includes/config.php");
$jsonData = array();
$jsonTempData = array();
$message = array();
$desiredResult = array();
    
if (isset($_REQUEST['op'])) {
    include "classes/restaurant.php";
    
    $function_obj = new clsFunctions();
    $Objrestaurant = new restaurant();

    $op = $_REQUEST['op'];    
    if ($op == 'getItem') {
        if (isset($_REQUEST['Slug']) && !empty($_REQUEST['Slug'])) {
            $restaurant_slug = $_REQUEST['Slug'];
            $Objrestaurant = $Objrestaurant->getDetailbyUrl($restaurant_slug);
            $restuarant_id = $Objrestaurant->id;
            $categories = array();
            $cat_qry = mysql_query("SELECT * FROM categories where parent_id = $restuarant_id ");
            while ($row = mysql_fetch_assoc($cat_qry)){
                $desiredResult[] = $row;
            }
            $jsonTempData['result'] = $categories;
            
        } else {
            $jsonTempData['status'] = 'failed';
            $jsonTempData['message'] = 'All parameters are required';
        }
    }else if ($op == 'getSubItem') {
        if (isset($_REQUEST['catId']) && !empty($_REQUEST['catId'])) {
            $catId = $_REQUEST['catId'];
            $sub_categories = array();
            $cat_qry = mysql_query("select * from product where sub_cat_id = $catId");
            while ($row = mysql_fetch_assoc($cat_qry)){
                $desiredResult[] = $row;
            }
            $jsonTempData['result'] = $sub_categories;
            
        } else {
            $jsonTempData['status'] = 'failed';
            $jsonTempData['message'] = 'All parameters are required';
        }
    } else if ($op == 'updateItem') {
        if (isset($_REQUEST['catId']) && !empty($_REQUEST['catId']) &&
            isset($_REQUEST['status']) && $_REQUEST['status']!=null) {
            $catId = $_REQUEST['catId'];
            $status = $_REQUEST['status'];
            $response = mysql_query("UPDATE categories SET status = $status WHERE cat_id = $catId");
            //$desiredResult['result'] = $response;
			$desiredResult[]['result'] = $response;
            
        } else {
            $jsonTempData['status'] = 'failed';
            $jsonTempData['message'] = 'All parameters are required';
        }
    }else if ($op == 'updateSubItem') {
        if (isset($_REQUEST['productId']) && !empty($_REQUEST['productId']) &&
            isset($_REQUEST['status']) && $_REQUEST['status']!=null) {
            $product_id = $_REQUEST['productId'];
            $status = $_REQUEST['status'];
            $response = mysql_query("UPDATE product SET status=$status WHERE prd_id=$product_id");
            $desiredResult[]['result'] = $response;
            
        } else {
            $jsonTempData['status'] = 'failed';
            $jsonTempData['message'] = 'All parameters are required';
        }
    } else if ($op == 'getRestaurantInfo') {
        if (isset($_REQUEST['Slug']) && !empty($_REQUEST['Slug'])) {
            $restaurant_slug = $_REQUEST['Slug'];
			
            $response = mysql_query("SELECT * FROM resturants where url_name = '$restaurant_slug'");
			
            $desiredResult[] = mysql_fetch_assoc($response);
            
        } else {
            $jsonTempData['status'] = 'failed';
            $jsonTempData['message'] = 'All parameters are required';
        }
    }else if ($op == 'updateRestAnnouncmentStatus') {
        if (isset($_REQUEST['Slug']) && !empty($_REQUEST['Slug']) &&
            isset($_REQUEST['status']) && $_REQUEST['status']!=null) {
            $restaurant_slug = $_REQUEST['Slug'];
            $status = $_REQUEST['status'];
            $response = mysql_query("UPDATE resturants SET announce_status=$status WHERE url_name='$restaurant_slug'");
            $desiredResult[]['result'] = $response;
            
        } else {
            $jsonTempData['status'] = 'failed';
            $jsonTempData['message'] = 'All parameters are required';
        }
    }else if ($op == 'updateRestAnnouncmentText') {	
        if (isset($_REQUEST['Slug']) && !empty($_REQUEST['Slug']) &&
            isset($_REQUEST['text'])) {
            $restaurant_slug = $_REQUEST['Slug'];
            $text = $_REQUEST['text'];
            $response = mysql_query("UPDATE resturants SET announcement='$text' WHERE url_name='$restaurant_slug'");
            $desiredResult[]['result'] = $response;
            
        } else {
            $jsonTempData['status'] = 'failed';
            $jsonTempData['message'] = 'All parameters are required';
        }
    }else if ($op == 'updateRestStoreStatus') {	
        if (isset($_REQUEST['Slug']) && !empty($_REQUEST['Slug']) &&
            isset($_REQUEST['status']) && $_REQUEST['status']!=null) {
            $restaurant_slug = $_REQUEST['Slug'];
            $status = $_REQUEST['status'];
            $response = mysql_query("UPDATE resturants SET rest_open_close=$status WHERE url_name='$restaurant_slug'");
            $desiredResult[]['result'] = $response;
            
        } else {
            $jsonTempData['status'] = 'failed';
            $jsonTempData['message'] = 'All parameters are required';
        }
    }else if ($op == 'updateRestDeliveryStatus') {	
        if (isset($_REQUEST['Slug']) && !empty($_REQUEST['Slug']) &&
            isset($_REQUEST['status']) && $_REQUEST['status']!=null) {
            $restaurant_slug = $_REQUEST['Slug'];
            $status = $_REQUEST['status'];
            $response = mysql_query("UPDATE resturants SET delivery_offer=$status WHERE url_name='$restaurant_slug'");
            $desiredResult[]['result'] = $response;
            
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
$jsonData[] = $jsonTempData;

$outputArr = array();
$outputArr['Response'] = $jsonData;

print(json_encode($desiredResult));
?>