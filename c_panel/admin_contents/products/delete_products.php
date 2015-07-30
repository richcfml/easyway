<?php
require_once("../../../includes/config.php");
$product_id	=	$_REQUEST['pid'];
$catid		=	$_REQUEST['cid'];


dbAbstract::Delete("delete from attribute where ProductID =".$product_id,1);
Log::write("Delete attribute - delete_products.php - LINE 8", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');

Log::write("Delete product - delete_products.php", "QUERY -- delete from product WHERE prd_id = ".$product_id, 'menu', 1 , 'cpanel');
dbAbstract::Delete("delete from product WHERE prd_id = ".$product_id,1);
mysqli_close($mysqli);
?>
 <script language="javascript">
    window.location="../../?mod=menus&catid=<?=$catid?>";
 </script>
 
 