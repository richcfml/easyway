<? 
session_start();

function unset_sess($sess_val){
	unset($_SESSION[$sess_val]);
}

unset_sess('admin_session_user_name'); 
unset_sess('admin_session_pass');
//unset_sess('admin_session_id');
//unset_sess('admin_login');
unset_sess('cart');
unset_sess('dtip');
unset_sess('coupon_code');
unset_sess('coupon_discount');
unset_sess('calculated_tip');
unset_sess('customer_name');
unset_sess('customer_room');
unset_sess('customer_cell');
unset_sess('customer_email');
unset_sess('catg_id');
unset_sess('sub_catg_id');
unset_sess('p_id');
unset_sess('hotel_adres');
unset_sess('SessionPID');
unset_sess('payment');
unset_sess('delveryspecialrequest');
unset_sess('selectdate');
unset_sess('selectime');
unset_sess('rest_opt');
unset_sess('Clentid');
?>
<script language="javascript">
window.location = "login.php";
</script>

							