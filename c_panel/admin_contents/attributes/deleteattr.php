<?
	include("../../../includes/config.php");
	
	 $id 		= $_GET['id'];
	 $catid		= $_REQUEST['cid'];
	 $name 		= $_GET['name'];
	mysql_query("DELETE FROM attribute WHERE ProductID=$id and option_name='$name'");
	
?>
				<script language="javascript">
						window.location="../../?mod=menus&catid=<?=$catid?>";
				</script>	