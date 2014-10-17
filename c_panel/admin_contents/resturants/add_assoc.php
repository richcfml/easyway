<?php
/*	$mysql_conn = mysql_connect("localhost","root","");
	mysql_select_db("onlineorderingsystem",$mysql_conn);*/
	
	$mysql_conn = mysql_connect("easywayordering.db.7320018.hostedresource.com","easywayordering","Way2ordering");
	mysql_select_db("easywayordering",$mysql_conn);
	
        Log::write("Delete product association - add_assoc.php", "QUERY -- DELETE FROM product_association where product_id = '".$_GET['product_id']."' ", 'menu', 1 , 'cpanel');
	mysql_query("DELETE FROM product_association where product_id = '".$_GET['product_id']."' ");
	
	$association_id = $_GET['itemcheck'];
	for($i=0; $i<count($_GET['itemcheck']); $i++) {
		$query = "INSERT INTO product_association SET product_id = '".$_GET['product_id']."', association_id = '".$association_id[$i]."'";
                Log::write("Add new product association - restaurant/add_assoc.php", "QUERY -- ". $query, 'menu', 1 , 'cpanel');
		mysql_query($query);
                Log::write("Set product HasAssociates=1 - add_assoc.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $_GET['product_id'] . "", 'menu', 1, 'cpanel');
                mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id = " . $_GET['product_id'] . "");
	}
?>
	
<script type="text/javascript" language="javascript">http://
//window.location ="http://localhost/onlineorderingsystem/c_panel/admin_contents/resturants/tab_resturant_menus.php?catid=$_GET['product_id']";

window.location.href = "tab_resturant_menus.php?subitem=menu&catid=<?=$_GET['category_id']?>";
</script>