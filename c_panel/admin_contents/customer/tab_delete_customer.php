<?php $user_id = $_REQUEST['userid'];

$order_qry	=	dbAbstract::Execute("select OrderID from ordertbl where UserID=".$user_id, 1);
while($order_rs = dbAbstract::returnObject($order_qry, 1)){
	$orderid = $order_rs->OrderID;
        Log::write("Delete order details - tab_delete_customer.php", "QUERY -- DELETE from orderdetails where orderid=".$orderid, 'order', 1 , 'cpanel');
dbAbstract::Delete("DELETE from orderdetails where orderid=".$orderid, 1);


}
Log::write("Delete order details from ordertbl - tab_delete_customer.php", "QUERY -- DELETE from ordertbl where UserID=".$user_id, 'order', 1 , 'cpanel');
dbAbstract::Delete("DELETE from ordertbl where UserID=".$user_id, 1);

Log::write("Delete from customer registration - tab_delete_customer.php", "QUERY -- DELETE from customer_registration where id=".$user_id, 'order', 1 , 'cpanel');
dbAbstract::Delete("DELETE from customer_registration where id=".$user_id, 1);



?>
<script language="javascript">
window.location="?mod=customer&cid=<?=$mRestaurantIDCP?>";
</script>