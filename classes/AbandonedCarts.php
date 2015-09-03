<?php
/*
*	Class AbandonedCarts start
*/
class AbandonedCarts 
{
	/*
	*	Initiaize public variables
	*/
    public $id
            ,$user_id
            ,$resturant_id
            ,$cart
            ,$date_added
            ,$referral_source
            ,$session_duration_in_seconds
            ,$last_user_action
            ,$cart_total_amount
            ,$reason
            ,$platform_used
            ,$status;
	
	/*
	*	define class constructor
	*/
    function __construct()
    {
        $this->user_id = 0;
        $this->resturant_id = 0;
        $this->cart = null;
        $this->date_added = date("Y-m-d H:i:s");
        $this->referral_source = "";
        $this->session_duration_in_seconds = 0;
        $this->last_user_action = "";
        $this->cart_total_amount = 0.00;
        $this->reason = "";
        $this->platform_used = 1;
        $this->status = 0;
    }
	
	/*
	*	Get abandoned carts
	*/
    public function getAbandonedCarts($resturant_id) 
    {
        return dbAbstract::Execute("SELECT ac.*, cr.id as customer_id, IF(ac.user_id=0, 'guest', cr.cust_email) user_email, CONCAT(cust_odr_address, ', ', cust_ord_city, ', ', cust_ord_state, ', ', cust_ord_zip) FROM  abandoned_carts ac LEFT JOIN customer_registration cr ON ac.user_id=cr.id WHERE ac.resturant_id=$resturant_id AND ac.status=1 AND (date_added BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()) ORDER BY ac.date_added DESC");
    }
	
	/*
	*	add new abandoned cart
	*/
    public function addNewAbandonedCart($user_id,$resturant_id,$cart,$date_added,$referral_source,$session_duration_in_seconds,$last_user_action,$cart_total_amount,$reason,$platform_used,$status) 
    {
        $mSQL = "INSERT INTO `abandoned_carts`(`user_id`, `resturant_id`, `cart`, `date_added`, `referral_source`, `session_duration_in_seconds`, `last_user_action`, `cart_total_amount`, `reason`, `platform_used`, `status`) VALUES ($user_id,$resturant_id,'$cart','$date_added','$referral_source','$session_duration_in_seconds','$last_user_action','$cart_total_amount','$reason','$platform_used','$status')";
        Log::write("Insert into abandoned_carts - abandoned_carts.php", "QUERY --".$mSQL, 'order', 0 , 'user');
        return dbAbstract::Insert($mSQL, 0, 2);
    }
	
	/*
	*	update abandoned cart
	*/
    public function updateAbandonedCart($abandoned_cart_id, $user_id,$resturant_id,$cart,$date_added,$referral_source,$session_duration_in_seconds,$last_user_action,$cart_total_amount,$reason,$platform_used,$status) 
    {
        $mSQL = "UPDATE `abandoned_carts` SET `user_id`=$user_id, `resturant_id`=$resturant_id, `cart`='$cart', `date_added`='$date_added', `referral_source`='$referral_source', `session_duration_in_seconds`='$session_duration_in_seconds', `last_user_action`='$last_user_action', `cart_total_amount`='$cart_total_amount', `reason`='$reason', `platform_used`='$platform_used', `status`='$status' WHERE id=$abandoned_cart_id";
        Log::write("Update abandoned carts - abandoned_carts.php", "QUERY --".$mSQL, 'order', 0 , 'user');
        return dbAbstract::Update($mSQL);   
    }
	
	/*
	*	delete abandoned cart
	*/
    public function deleteAbandonedCart($id) 
    {
        $mSQL = "DELETE FROM `abandoned_carts` WHERE id=$id";
        dbAbstract::Delete($mSQL);
        Log::write("Update abandoned - abandoned_carts.php", "QUERY --".$mSQL, 'order', 0 , 'user');
        
		unset($_SESSION["user_session_duration"]);
        unset($_SESSION["referral_source"]);
        unset($_SESSION["abandoned_cart_error"]);
        unset($_SESSION["abandoned_cart_id"]);
    }
 }//CLASS
 
?>