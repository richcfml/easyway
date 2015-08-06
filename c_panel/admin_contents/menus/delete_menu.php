<? include("../../../includes/config.php");?>
<?php
  $ID = $_GET['scid'];
  $catid	=	$_REQUEST['cid'];
  Log::write("Delete product - delete_menu.php", "QUERY -- delete from product where sub_cat_id =".$ID, 'menu', 1 , 'cpanel');
  dbAbstract::Delete("delete from product where sub_cat_id =".$ID, 1);      
  Log::write("Delete category - delete_menu.php", "QUERY -- delete from categories WHERE cat_id = ".$ID, 'menu', 1 , 'cpanel');
  dbAbstract::Delete("delete from categories WHERE cat_id = ".$ID, 1);
?>
<script language="javascript">
window.location="../../?mod=menus&catid=<?=$catid?>";
</script>