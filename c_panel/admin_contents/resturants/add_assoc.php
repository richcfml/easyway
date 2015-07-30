<?php   
        require_once("../../../includes/config.php");
        Log::write("Delete product association - add_assoc.php", "QUERY -- DELETE FROM product_association where product_id = '".$_GET['product_id']."' ", 'menu', 1 , 'cpanel');
	dbAbstract::Delete("DELETE FROM product_association where product_id = '".$_GET['product_id']."' ",1);
	
	$association_id = $_GET['itemcheck'];
	for($i=0; $i<count($_GET['itemcheck']); $i++) {
		$query = "INSERT INTO product_association SET product_id = '".$_GET['product_id']."', association_id = '".$association_id[$i]."'";
                Log::write("Add new product association - restaurant/add_assoc.php", "QUERY -- ". $query, 'menu', 1 , 'cpanel');
		dbAbstract::Insert($query,1);
                Log::write("Set product HasAssociates=1 - add_assoc.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $_GET['product_id'] . "", 'menu', 1, 'cpanel');
                dbAbstract::Update("UPDATE product set HasAssociates=1 WHERE prd_id = " . $_GET['product_id'] . "",1);
	}
      mysqli_close($mysqli);
?>
	
<script type="text/javascript" language="javascript">http://
window.location.href = "tab_resturant_menus.php?subitem=menu&catid=<?=$_GET['category_id']?>";
</script>