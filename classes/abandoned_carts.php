<?
class abandoned_carts {
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

	function __construct() {
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
	
	public function get_abandoned_carts($resturant_id) {
		//echo "SELECT ac.*, cr.id as customer_id, IF(ac.user_id=0, 'guest', cr.cust_email) user_email, CONCAT(cust_odr_address, ', ', cust_ord_city, ', ', cust_ord_state, ', ', cust_ord_zip) FROM  abandoned_carts ac LEFT JOIN customer_registration cr ON ac.user_id=cr.id WHERE ac.resturant_id=$resturant_id AND ac.status=1 AND (date_added BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()) ORDER BY ac.date_added DESC";
		return mysql_query("SELECT ac.*, cr.id as customer_id, IF(ac.user_id=0, 'guest', cr.cust_email) user_email, CONCAT(cust_odr_address, ', ', cust_ord_city, ', ', cust_ord_state, ', ', cust_ord_zip) FROM  abandoned_carts ac LEFT JOIN customer_registration cr ON ac.user_id=cr.id WHERE ac.resturant_id=$resturant_id AND ac.status=1 AND (date_added BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()) ORDER BY ac.date_added DESC");
	}
	
	public function add_abandoned_cart($user_id,$resturant_id,$cart,$date_added,$referral_source,$session_duration_in_seconds,$last_user_action,$cart_total_amount,$reason,$platform_used,$status) {
            Log::write("Insert into abandoned_carts - abandoned_carts.php", "QUERY -- 
			INSERT INTO `abandoned_carts`(
				`user_id`
				, `resturant_id`
				, `cart`
				, `date_added`
				, `referral_source`
				, `session_duration_in_seconds`
				, `last_user_action`
				, `cart_total_amount`
				, `reason`
				, `platform_used`
				, `status`
			) VALUES (
				$user_id
				,$resturant_id
				,'$cart'
				,'$date_added'
				,'$referral_source'
				,'$session_duration_in_seconds'
				,'$last_user_action'
				,'$cart_total_amount'
				,'$reason'
				,'$platform_used'
				,'$status'
			)
			", 'order', 0 , 'user');
            mysql_query("
			INSERT INTO `abandoned_carts`(
				`user_id`
				, `resturant_id`
				, `cart`
				, `date_added`
				, `referral_source`
				, `session_duration_in_seconds`
				, `last_user_action`
				, `cart_total_amount`
				, `reason`
				, `platform_used`
				, `status`
			) VALUES (
				$user_id
				,$resturant_id
				,'$cart'
				,'$date_added'
				,'$referral_source'
				,'$session_duration_in_seconds'
				,'$last_user_action'
				,'$cart_total_amount'
				,'$reason'
				,'$platform_used'
				,'$status'
			)
			"
		) or die(mysql_error());
		return mysql_insert_id();
	}
	
	public function update_abandoned_cart($abandoned_cart_id, $user_id,$resturant_id,$cart,$date_added,$referral_source,$session_duration_in_seconds,$last_user_action,$cart_total_amount,$reason,$platform_used,$status) {
            Log::write("Update abandoned carts - abandoned_carts.php", "QUERY -- 
                            UPDATE `abandoned_carts`
                            SET `user_id`=$user_id
                                    , `resturant_id`=$resturant_id
                                    , `cart`='$cart'
                                    , `date_added`='$date_added'
                                    , `referral_source`='$referral_source'
                                    , `session_duration_in_seconds`='$session_duration_in_seconds'
                                    , `last_user_action`='$last_user_action'
                                    , `cart_total_amount`='$cart_total_amount'
                                    , `reason`='$reason'
                                    , `platform_used`='$platform_used'
                                    , `status`='$status'
                            WHERE id=$abandoned_cart_id
                            ", 'order', 0 , 'user');
            mysql_query("
			UPDATE `abandoned_carts`
			SET `user_id`=$user_id
				, `resturant_id`=$resturant_id
				, `cart`='$cart'
				, `date_added`='$date_added'
				, `referral_source`='$referral_source'
				, `session_duration_in_seconds`='$session_duration_in_seconds'
				, `last_user_action`='$last_user_action'
				, `cart_total_amount`='$cart_total_amount'
				, `reason`='$reason'
				, `platform_used`='$platform_used'
				, `status`='$status'
			WHERE id=$abandoned_cart_id
			"
		) or die(mysql_error());
		return mysql_insert_id();
	}
	
	public function delete_abandoned_cart($id) {
                Log::write("Update abandoned - abandoned_carts.php", "QUERY -- DELETE FROM `abandoned_carts` WHERE id=$id", 'order', 0 , 'user');
		mysql_query("DELETE FROM `abandoned_carts` WHERE id=$id");
		$this->unset_sessions();
	}
	
	public function make_current_order_abandoned($id) {
            Log::write("Delete abandoned_carts - abandoned_carts.php", "QUERY -- UPDATE `abandoned_carts`
			SET `status`=1
			WHERE id=$id
			"
		, 'order', 0 , 'user');
		mysql_query("
			UPDATE `abandoned_carts`
			SET `status`=1
			WHERE id=$id
			"
		) or die(mysql_error());
		$this->unset_sessions();
	}
	
	public function unset_sessions() {
		unset($_SESSION["user_session_duration"]);
		unset($_SESSION["referral_source"]);
		unset($_SESSION["abandoned_cart_error"]);
		unset($_SESSION["abandoned_cart_id"]);
	}
	
	public function update_abandoned_carts_count($resturant_id, $orders_count) {
		mysql_query("
			UPDATE `analytics`
			SET `abandoned_carts_count_last_month`=$orders_count
			WHERE resturant_id=$resturant_id
			"
		) or die(mysql_error());
	}

 }//CLASS
 
?>