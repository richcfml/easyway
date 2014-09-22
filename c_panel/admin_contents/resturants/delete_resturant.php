<?
include("../../../includes/config.php");
$cat_id = $_REQUEST['cid'];
//mysql_query("delete from categories where parent_id=$cat_id");
//mysql_query("delete from product where cat_id=$cat_id");
//mysql_query("delete from cat_product_cosine where Cat_ID=$cat_id");
//mysql_query("delete from categories where cat_id=$cat_id");

?>
 <script language="javascript">
		window.location="../../?mode=adminindex";
 </script>