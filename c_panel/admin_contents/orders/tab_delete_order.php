<?php 
$OrderID=$_REQUEST['OrderID'];
Log::write("Delete order details - tab_delete_order.php", "QUERY -- DELETE FROM orderdetails WHERE orderid=$OrderID", 'order', 1 , 'cpanel');
dbAbstract::Delete("DELETE FROM orderdetails WHERE orderid=$OrderID", 1);
Log::write("Delete order details from ordertbl - tab_delete_order.php", "QUERY -- DELETE FROM ordertbl WHERE OrderID=$OrderID", 'order', 1 , 'cpanel');
dbAbstract::Delete("DELETE FROM ordertbl WHERE OrderID=$OrderID", 1);
?>
<script language="javascript">
		window.location="./?mod=order&item=main&cid=<?=$mRestaurantIDCP?>";
 </script>