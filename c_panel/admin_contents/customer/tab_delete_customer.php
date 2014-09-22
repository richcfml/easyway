<? $user_id = $_REQUEST['userid'];

$order_qry	=	mysql_query("select OrderID from ordertbl where UserID=".$user_id);
while($order_rs = mysql_fetch_object($order_qry)){
	$orderid = $order_rs->OrderID;
        Log::write("Delete order details - tab_delete_customer.php", "QUERY -- DELETE from orderdetails where orderid=".$orderid, 'order', 1 , 'cpanel');
mysql_query("DELETE from orderdetails where orderid=".$orderid);


}
Log::write("Delete order details from ordertbl - tab_delete_customer.php", "QUERY -- DELETE from ordertbl where UserID=".$user_id, 'order', 1 , 'cpanel');
mysql_query("DELETE from ordertbl where UserID=".$user_id);

Log::write("Delete from customer registration - tab_delete_customer.php", "QUERY -- DELETE from customer_registration where id=".$user_id, 'order', 1 , 'cpanel');
mysql_query("DELETE from customer_registration where id=".$user_id);



?>
<script language="javascript">
window.location="?mod=customer&cid=<?=$mRestaurantIDCP?>";
</script>