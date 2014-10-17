<?php

class Easy_Way_Api {

    public static function getToken($email, $password, $restaurant_id) {

        //generate token.
        $token = md5($password . '' . time());
        $user_info = mysql_fetch_array(mysql_query("SELECT * from customer_registration where cust_email='$email' and password ='$password' and resturant_id= '". $restaurant_id ."'"));

        if (!$user_info) {
            echo json_encode(array('success' => '0', 'msg' => "Invalid user!"));
            exit;
        }
        $user_id = $user_info['id'];
        mysql_query("INSERT into user_authentication set auth_token     = '".$token."'
                                                        ,user_id        = '".$user_id."'
                                                        ,restaurant_id  = '".$restaurant_id."'"
                   );

        return $token;
    }

    public static function sendToDatabase($parameterNameArray, $parameterValueArray, $useIdName, $useIdValue, $oldPassword, $tableName) {

        $iterator = 0;
        $passwordFlag = true;
        $idName;
        $myId;

        if ($useIdValue == '') {
            $query = "INSERT INTO " . $tableName . " (" . $parameterNameArray[0] . ") VALUES('" . $parameterValueArray[0] . "')";
            mysql_query($query);

            $query = "SELECT MAX(" . $useIdName . ") as max FROM " . $tableName . ";";
            $result = mysql_fetch_array(mysql_query($query));

            $myId = $result[0];
            $iterator++;
        } else {
            $myId = $useIdValue;
        }


        $arrayLength = count($parameterNameArray);
        if ($oldPassword != '') {
            $query = "SELECT password FROM " . $tableName . " WHERE " . $useIdName . " = '" . $myId . "'";
            $result = mysql_fetch_array(mysql_query($query));

            $passwordFlag = ($result[0] == $oldPassword);
        }
        if ($passwordFlag) {
            for (; $iterator < $arrayLength; $iterator++) {
                $query = "UPDATE " . $tableName . " SET " . $parameterNameArray[$iterator] . "='" . $parameterValueArray[$iterator] . "' WHERE " . $useIdName . "='" . $myId . "';";
                mysql_query($query);
            }
        } else {
            return "password mismatch";
        }

        if ($tableName == 'placed_order') {
            return "success";
            //return $myId;
        }
        return "success";
    }

    public static function getIdFromAuthToken($myAuthToken) {

        $query = "SELECT * from user_authentication where auth_token='" . $myAuthToken . "'";
        $result = mysql_fetch_array(mysql_query($query));
        return $result;
    }

    public static function getTuple($requiredColumns, $columnName, $columnVal, $optionalColumnName, $optionalColumnVal, $tableName) {

        $requiredColumnStrings = Easy_Way_Api::convertToQueryString($requiredColumns);
        $queryOne = "SELECT " . $requiredColumnStrings . " FROM " . $tableName . " WHERE " . $columnName . "='" . $columnVal . "'";
        $queryTwo = "SELECT " . $requiredColumnStrings . " FROM " . $tableName . " WHERE " . $optionalColumnName . "='" . $optionalColumnVal . "'";
        $query = "";

        if ($optionalColumnVal == '') {
            $query = $queryOne;
        } else {
            $query = $queryTwo;
        }

        $result = mysql_query($query);

        $resultArrayTuple = array();

        $resultArray = array();
        $resultArrayIndex = 0;

        while ($tuple = mysql_fetch_array($result)) {
            for ($i = 0; $i < count($requiredColumns); $i++) {
                $resultArrayTuple[$requiredColumns[$i]] = $tuple[$requiredColumns[$i]];
            }
            $resultArray[$resultArrayIndex] = $resultArrayTuple;
            $resultArrayIndex++;
        }
        if ((count($resultArray)) == 0) {
            echo json_encode(array("success" => 0, "msg" => "wrong optional value"));
            return;
        } else {
            echo json_encode($resultArray);
            return;
        }
    }

    public static function convertToQueryString($columnNamesArr) {
        $convertedString = '';
        for ($i = 0; $i < count($columnNamesArr); $i++) {
            $convertedString = $convertedString . "," . $columnNamesArr[$i];
        }
        $convertedString = substr($convertedString, 1);
        return $convertedString;
    }

    public static function deleteCard($requiredId, $userName) {

        $myFlag = true;
        $query = "SELECT user_name FROM credit_card_info WHERE user_id = '" . $requiredId . "'";
        $result = mysql_fetch_array(mysql_query($query));
        $myFlag = ($result['user_name'] == $userName);

        if ($myFlag == true) {
            $query = "DELETE FROM credit_card_info WHERE user_id =" . $requiredId;
            mysql_query($query);
            return "success";
        } else {
            return "invalid user name";
        }
    }
    
    public static function getResturantDetail($rest_id) {
        $qry = mysql_query("select `id`, `name`, `delivery_charges`, `order_minimum`, `tax_percent`,`email`,`fax`,  `order_destination`, `phone`, `payment_method`, `announcement`, `payment_gateway`, `tokenization`, `time_zone_id`, `rest_open_close`, `delivery_offer`, `voice_phone`, `voice_email_service`, `phone_notification`, `rest_address`, `rest_city`, `rest_state`, `rest_zip`, `delivery_radius` from resturants where id = '" . $rest_id . "'")or die(mysql_error());
        
        $Restaurantobj = mysql_fetch_object($qry);
        if(!empty($Restaurantobj)){
            $Restaurantobj->menu_items = Easy_Way_Api::getMenuItems($rest_id);
            return $Restaurantobj;
        } else {
            return false;
        }
    }

    public static function getMenuItems($category_id) {
        
        $qry = "select * from product where sub_cat_id =".$category_id;
        $cat_qry = mysql_query($qry);
        $arr_product_list = array();
        while ($product = mysql_fetch_object($cat_qry)) {
            $arr_product_list[] = $product;
        }

        return $arr_product_list;
    }
    
    public static function getRestaurantUrl($rest_id) {

        $qry = mysql_query("select url_name as url from resturants where id = " . $rest_id);
        @$restauranturl = mysql_fetch_object($qry, "restaurant");

        return $restauranturl->url;
    }

    public static function getRestaurants() {

        $qry = mysql_query("select id,name from resturants");
        $restaurant_info = array();
        while ($rest = mysql_fetch_assoc($qry)) {
            $restaurant_info[] = $rest;
        }
        return $restaurant_info;
    }
}