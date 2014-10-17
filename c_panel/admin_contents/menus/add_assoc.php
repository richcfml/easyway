<?php
include("../../../includes/config.php");
	
	if($_REQUEST['apply_subcat']) {
		
		$scat_id = $_REQUEST['sub_cat_id'];
		$prodQry = mysql_query("select prd_id from product where sub_cat_id= $scat_id");
		while($prodRs = mysql_fetch_array($prodQry)){
                        Log::write("Delete product association - add_assoc.php", "QUERY -- DELETE FROM product_association where product_id = '".$prodRs['prd_id']."'", 'menu', 1 , 'cpanel');
			mysql_query("DELETE FROM product_association where product_id = '".$prodRs['prd_id']."'");

		}
		
		$prodQry = mysql_query("select prd_id from product where sub_cat_id= $scat_id");
		while($prodRs = mysql_fetch_array($prodQry)){
			$association_id = $_GET['itemcheck'];
			for($i=0; $i<count($_GET['itemcheck']); $i++) {
				if($prodRs['prd_id'] != $association_id[$i]) {
				$query = "INSERT INTO product_association SET product_id = '".$prodRs['prd_id']."', association_id = '".$association_id[$i]."'";
                                Log::write("Add new product association - add_assoc.php", "QUERY -- ".$query, 'menu', 1 , 'cpanel');
				mysql_query($query);
                                Log::write("Set product HasAssociates=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $prodRs['prd_id'] . "", 'menu', 1, 'cpanel');
                                mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id = " . $prodRs['prd_id'] . "");
            }
			}// end for
		}// end while
	} else {
		Log::write("Delete product association - add_assoc.php", "QUERY -- DELETE FROM product_association where product_id = '".$_GET['product_id']."' ", 'menu', 1 , 'cpanel');
		mysql_query("DELETE FROM product_association where product_id = '".$_GET['product_id']."' ");
		
		$association_id = $_GET['itemcheck'];
	  for($i=0; $i<count($_GET['itemcheck']); $i++) {
		  $query = "INSERT INTO product_association SET product_id = '".$_GET['product_id']."', association_id = '".$association_id[$i]."'";
                  Log::write("Add new product association - add_assoc.php", "QUERY -- ".$query, 'menu', 1 , 'cpanel');
		  mysql_query($query);
                Log::write("Set product HasAssociates=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $_GET['product_id'] . "", 'menu', 1, 'cpanel');
                mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id = " . $_GET['product_id'] . "");
    }
	}// end if
	
	
	
	
	
	
	
	
	

	
?>
	
<script type="text/javascript" language="javascript">http://
//window.location ="http://localhost/onlineorderingsystem/c_panel/admin_contents/resturants/tab_resturant_menus.php?catid=$_GET['product_id']";

window.location.href = "../../?mod=menus&catid=<?=$_GET['category_id']?>";
</script>