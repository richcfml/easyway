<?php	
require_once("../../../includes/config.php");	
	//echo "<pre>";print_r($_SERVER);exit;
//	if(isset($_REQUEST['apply_subcat'])) {
	if (strpos($_SERVER['HTTP_REFERER'], 'product') != true) {
		$scat_id = $_REQUEST['sub_cat_id'];
		$prodQry = mysql_query("select prd_id from product where sub_cat_id= $scat_id");
		while($prodRs = mysql_fetch_array($prodQry)){
                    Log::write("Delete product association - add_assoc_new.php", "QUERY -- DELETE FROM product_association where product_id = '".$prodRs['prd_id']."'", 'menu', 1 , 'cpanel');
			mysql_query("DELETE FROM product_association where product_id = '".$prodRs['prd_id']."'");

		}
		
		$prodQry = mysql_query("select prd_id from product where sub_cat_id= $scat_id");
		while($prodRs = mysql_fetch_array($prodQry)){
			$association_id = $_GET['itemcheck'];
			for($i=0; $i<count($_GET['itemcheck']); $i++) {
				if($prodRs['prd_id'] != $association_id[$i]) {
                                    Log::write("Add new product association - add_assoc_new.php", "QUERY -- INSERT INTO product_association SET product_id = '".$prodRs['prd_id']."', association_id = '".$association_id[$i]."'", 'menu', 1 , 'cpanel');
				$query = "INSERT INTO product_association SET product_id = '".$prodRs['prd_id']."', association_id = '".$association_id[$i]."'";
				mysql_query($query);
                                Log::write("Set product HasAssociates=1 - add_assoc_new.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $prodRs['prd_id'] . "", 'menu', 1, 'cpanel');
                                mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id = " . $prodRs['prd_id'] . "");
                            }
			}// end for
		}// end while
                  ?>
        <script type="text/javascript" src="js/new-menu.js"></script>
        <script type="text/javascript" language="javascript">
            var sPageURL = '';
            sPageURL = '<?= $_SERVER['HTTP_REFERER']?>';
             var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++)
            {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == "catid")
                {
                    catid =  sParameterName[1];
                }
                else if (sParameterName[0] == "menuid")
                {
                    menu_id =  sParameterName[1];
                }
                else if (sParameterName[0] == "menu_name")
                {
                    menu_name =  sParameterName[1];
                    
                }
            }
            window.location.href = "<?= $AdminSiteUrl ?>?mod=new_menu&catid="+catid+"&menuid="+menu_id+"&menu_name="+menu_name;
        </script>
<?
	}
        else {
		if($_REQUEST['apply_subcat']) {

//		$scat_id = $_REQUEST['sub_cat_id'];
//		$prodQry = mysql_query("select prd_id from product where sub_cat_id= $scat_id");
//		while($prodRs = mysql_fetch_array($prodQry)){
//			Log::write("Delete product association - add_assoc_new.php", "QUERY -- DELETE FROM product_association where product_id = '".$_GET['product_id']."' ", 'menu', 1 , 'cpanel');
//			mysql_query("DELETE FROM product_association where product_id = '".$prodRs['prd_id']."'");
//
//		}
//
//		$prodQry = mysql_query("select prd_id from product where sub_cat_id= $scat_id");
//		while($prodRs = mysql_fetch_array($prodQry)){
//			$association_id = $_GET['itemcheck'];
//                        $orderNo = 1;
//			for($i=0; $i<count($_GET['itemcheck']); $i++) {
//				if($prodRs['prd_id'] != $association_id[$i]) {
//				$query = "INSERT INTO product_association SET product_id = '".$prodRs['prd_id']."', association_id = '".$association_id[$i]."',sortOrder = ".$orderNo."";
//				mysql_query($query);
//                                mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id = " . $prodRs['prd_id'] . "");
//                                $orderNo++;
//				}
//			}// end for
//		}// end while
                
                // Saad Changes 24-Sept-2014 -
                $subMenuId = $_GET['sub_cat_id'];

                $selectedProductId = $_GET['product_id'];
                //Delete all associated items for selected productId
                Log::write("Delete product association - add_assoc_new.php", "QUERY -- DELETE FROM product_association where product_id = '".$_GET['product_id']."' ", 'menu', 1 , 'cpanel');
                mysql_query("DELETE FROM product_association where product_id = '".$selectedProductId."'");
                // Update Has Associates for selected productId
                Log::write("Update product association - add_assoc_new.php", "QUERY -- UPDATE product set HasAssociates=0 WHERE prd_id = " . $prodRs['prd_id'], 'menu', 1 , 'cpanel');
                mysql_query("UPDATE product set HasAssociates=0 WHERE prd_id = " . $prodRs['prd_id'] . "");
                    
		$prodQry = mysql_query("select prd_id from product where sub_cat_id= $subMenuId");

		while($prodRs = mysql_fetch_array($prodQry)){
                    $association_id = $_GET['itemcheck'];
                    $countOfItemChecked = count($association_id);
                    $isAssociationInserted = false;
                    $maxOrderNo = 0;
                    
                    $maxOrderNoQuery = mysql_fetch_object(mysql_query("Select max(sortOrder) as maxOrder from product_association where product_id = ".$prodRs['prd_id']));

                    if($maxOrderNoQuery->maxOrder != null) {
                        // MaxOrder is present -- Get it and use it for other products;
                        $maxOrderNo = $maxOrderNoQuery->maxOrder;
                        $maxOrderNo++;
                    }
                    
                    for($i=0; $i<$countOfItemChecked; $i++) {
                        if($prodRs['prd_id'] != $association_id[$i]) {
                            //echo "Select count(id) from product_association where product_id = ".$prodRs['prd_id']." && association_id = ".$association_id[$i]." <br/>";
                            $isAssocAlreadyExist = mysql_query("Select 1 from product_association where product_id = '".$prodRs['prd_id']."'&& association_id = '".$association_id[$i]."'");

                            if(mysql_num_rows($isAssocAlreadyExist)> 0) {
                               // "Already exist, do nothing just move for another association"
                            } else {
                                // no entry in association for that particular product id, now insert it and move for another association
                                $query = "INSERT INTO product_association SET product_id = ".$prodRs['prd_id'].", association_id = ".$association_id[$i].",sortOrder = ".$maxOrderNo."";
                                mysql_query($query);
                                $isAssociationInserted = true;
                                $maxOrderNo++;
                            }
                        }
                    }// end for
                    if($isAssociationInserted) {
                        //if association is inserted for product then also update their status
                        mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id = " . $prodRs['prd_id'] . "");
                    }
		}// end while
	}
        else
        {
		mysql_query("DELETE FROM product_association where product_id = '".$_GET['product_id']."' ");
		
		$association_id = $_GET['itemcheck'];
                $orderNo = 1;
	  for($i=0; $i<count($_GET['itemcheck']); $i++) {
		  $query = "INSERT INTO product_association SET product_id = '".$_GET['product_id']."', association_id = '".$association_id[$i]."',sortOrder = ".$orderNo."";
                  Log::write("Add new product association - add_assoc_new.php", "QUERY -- ".$query, 'menu', 1 , 'cpanel');
		  mysql_query($query);
                Log::write("Set product HasAssociates=1 - add_assoc_new.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $_GET['product_id'] . "", 'menu', 1, 'cpanel');
                mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id = " . $_GET['product_id'] . "");
                $orderNo++;
	  }
          
         // header('Location:tab_product_update_new.php&prd_id='.$_GET['product_id']);exit;
	}// end if
        ?>
        <script type="text/javascript" language="javascript">
            window.location.href = "<?=$AdminSiteUrl?>?mod=new_menu&item=updateproduct_new&prd_id=<?=$_GET['product_id']?>&sub_cat=<?=$_GET['sub_cat_id']?>";
        </script>
         <?
      }
	
?>
	
