<? session_start();
session_unregister('admin_session_user_name'); 
session_unregister('admin_session_pass');
//session_unregister('admin_session_id');
//session_unregister('admin_login');
session_unregister('cart');
session_unregister('dtip');
session_unregister('coupon_code');
session_unregister('coupon_discount');
session_unregister('calculated_tip');
session_unregister('customer_name');
session_unregister('customer_room');
session_unregister('customer_cell');
session_unregister('customer_email');
session_unregister('catg_id');
session_unregister('sub_catg_id');
session_unregister('p_id');
session_unregister('hotel_adres');
session_unregister('SessionPID');
  session_unregister('payment');
   session_unregister('delveryspecialrequest');
session_unregister('selectdate');
session_unregister('selectime');
session_unregister('rest_opt');

session_unregister('Clentid');
?>
<script language="javascript">
window.location = "login.php";
</script>

							