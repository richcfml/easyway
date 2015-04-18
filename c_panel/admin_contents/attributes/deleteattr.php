<?php
    require_once("../../../includes/config.php");	
    $id 		= $_GET['id'];
    $catid		= $_REQUEST['cid'];
    $name 		= $_GET['name'];
    
    $mQuery = "DELETE FROM attribute WHERE ProductID=$id and option_name='$name'";
    mysql_query($mQuery);
    Log::write("Delete Attribute - deleteattr.php", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');	
?>
<script language="javascript">
    window.location="../../?mod=menus&catid=<?=$catid?>";
</script>	