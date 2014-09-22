<? 
$OrderID=$_REQUEST['OrderID'];
Log::write("Delete order details - tab_delete_order.php", "QUERY -- DELETE FROM orderdetails WHERE orderid=$OrderID", 'order', 1 , 'cpanel');
mysql_query("DELETE FROM orderdetails WHERE orderid=$OrderID");
Log::write("Delete order details from ordertbl - tab_delete_order.php", "QUERY -- DELETE FROM ordertbl WHERE OrderID=$OrderID", 'order', 1 , 'cpanel');
mysql_query("DELETE FROM ordertbl WHERE OrderID=$OrderID");
//echo "DELETE FROM ordertbl WHERE OrderID=$OrderID"."<br>";
//echo "DELETE FROM orderdetails WHERE orderid=$OrderID";

?>
<script language="javascript">
		window.location="./?mod=order&item=main&cid=<?=$mRestaurantIDCP?>";
 </script>