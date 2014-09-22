<? include("../../../includes/config.php");?>
<?
  $ID = $_GET['scid'];
  $catid	=	$_REQUEST['cid'];
  Log::write("Delete product - delete_menu.php", "QUERY -- delete from product where sub_cat_id =".$ID, 'menu', 1 , 'cpanel');
  mysql_query("delete from product where sub_cat_id =".$ID);      
  Log::write("Delete category - delete_menu.php", "QUERY -- delete from categories WHERE cat_id = ".$ID, 'menu', 1 , 'cpanel');
  mysql_query("delete from categories WHERE cat_id = ".$ID);
?>
<script language="javascript">
window.location="../../?mod=menus&catid=<?=$catid?>";
</script>