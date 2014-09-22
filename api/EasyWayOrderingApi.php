<?php

class Authentication {

    public static function getToken($UserName, $Password) {

        /*$query = "SELECT * from user_info where user_name = '" . $UserName . "' and password = '" . $Password . "'";
        $row = mysql_query($query);
        */
        $result = (array)json_decode(Authentication::sendData('http://dev.easywayordering.com/services.php?op=authenticate', array('user'=>$UserName, 'pass'=>md5($Password))));

        if ($result['message'] == 'authenticated') {
            //generate token.
            $loop = false;
            do {
                $token = md5(uniqid(rand(), true));
                $token_info = mysql_fetch_array(mysql_query("SELECT token from authentication where token = '" . $token . "'"));

                if ($token_info) {
                    $loop = true;
                } else {
                    $loop = false;
                }

            }while ($loop);

            $user_id = $result['user'];
            $rest_id = $result['rest'];
            mysql_query("INSERT into authentication set token = '" . $token . "'
                        ,user_id = '" . $user_id . "'
                        ,rest_id = '" . $rest_id . "'
                        ,status= 1");
        } else {
            //user not valid
            $token = false;
        }
        return $token;
    }

    public static function getRestIdFromToken($token){
        $query = "SELECT * from authentication where token  = '" . $token . "'";
        $result = mysql_fetch_array(mysql_query($query));
        if($result){
            //$restaurant = mysql_fetch_array(mysql_query('SELECT * FROM user_info WHERE user_id = '.$result['user_id']));
            return unserialize($result['rest_id']);
        }else{
            return false;
        }
    }
    public static function sendData($uri, $data){



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
    //echo $result;
        return $result;
    }

}

class OrderInfo {

    public static function getOrders($token, $rest_id = false) {
        $rest_id_ = Authentication::getRestIdFromToken($token);

        if($rest_id)
        if(!in_array($rest_id, array_keys($rest_id_))){
            return false;
        }

        $orders = array();
        if($rest_id){
            $result = mysql_query("SELECT * from orders where rest_id = '" . $rest_id_[$rest_id] . "' AND status = 0");
            while($rec = mysql_fetch_object($result)){
                $orders[$rec->order_id] = unserialize($rec->payload);
            }
        }

        return $orders;


    }

    public static function fetchOrder($order) {
        return mysql_fetch_array(mysql_query('SELECT * FROM orders WHERE order_id = '.$order));
    }


    public static function orderAuthTokenMatch($order, $token) {

        $order = OrderInfo::fetchOrder($order);
        $rest_id_ = Authentication::getRestIdFromToken($token);

        if($order && $rest_id_){

            return in_array($order['rest_id'], $rest_id_);

        }else{
            return false;
        }


    }

}

class Confirmation {

    public static function setConfirmation($OrderId) {
        return mysql_query("UPDATE orders set status = 1 where order_id = '" . $OrderId . "'");
    }

    public static function setConfirmationOnEWO($OrderId, $RestId) {
        Authentication::sendData('http://dev.easywayordering.com/services.php?op=confirmOrder', array('rest_id'=>$RestId, 'order_id' =>$OrderId));
    }

}