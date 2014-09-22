<script src="../../../js/jquery.js" type="text/javascript"></script>
<link href="../../../css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../../../js/facebox.js" type="text/javascript"></script>
<script language="javascript">
	jQuery(document).ready(function($) {
		$('a[rel*=facebox]').facebox();
	});
</script>
<table width="100%" border="0">
<tr>
      <td class="msg_warning">*Note: Click On Menu Items Name To Edit:
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
</table>

<table id="menulisting" width="100%" border="0" cellpadding="0" cellspacing="0">
 	
    <?	
		$cat_qry	=	mysql_query("select * from categories where parent_id = ".$catid."");
		$cat_rows	=	mysql_num_rows($cat_qry);
		$i 		= 0;
		$count	= 0;
		$limit	= round($cat_rows/2);
		while($i < $limit){	
       
		?>
   
  <tr>
  <? for($j = 0; $j<2; $j++){
  						 $sub_cat	=	mysql_result($cat_qry,$count,"cat_id");
						 $sub_cat_name = mysql_result($cat_qry,$count,"cat_name");
  ?>
    <td width="50%"><a href="?subitem=edit&catid=<?=$catid?>&sub_cat=<?=$sub_cat?>" class="largelink"><strong><?=stripslashes(stripcslashes($sub_cat_name))?></strong></a>&nbsp;&nbsp;<a href="?subitem=addproduct&catid=<?=$catid?>&scid=<?=$sub_cat?>" class="smalllink">Add Items</a>
      <ul id="menus">
      <?	$subcat_id	=	mysql_result($cat_qry,$count,"cat_id");
	  		$prd_qry	=	mysql_query("select * from product where sub_cat_id = $subcat_id");
			
      				while($prd_qryRs = mysql_fetch_object($prd_qry)){	?>
                            	  <li><a href="?subitem=editproduct2&amp;pid=<?=$prd_qryRs->prd_id?>&amp;catid=<?=$catid?>&amp;scid=<?=$subcat_id?>" class="mediumlink"><?=stripslashes(stripcslashes($prd_qryRs->item_title))?></a>
                                  &nbsp;&nbsp;<a href="?subitem=addattribute2&amp;pid=<?=$prd_qryRs->prd_id?>&amp;catid=<?=$catid?>&amp;scid=<?=$sub_cat?>" class="smalllink">Add Attribute</a> <a href="popup.php?catid=<?=$catid?>&pid=<?=$prd_qryRs->prd_id ?>" rel="facebox" class="smalllink">associate item</a>
                                  <?
								  $attrib_qry = mysql_query("SELECT distinct(option_name) FROM attribute WHERE 	ProductID ='".$prd_qryRs->prd_id."'");
								  $product_assoc_qry = mysql_query("SELECT association_id FROM product_association WHERE product_id ='".$prd_qryRs->prd_id."'");						
								  ?>
                                  <ul id="items">
                                  	<? while ($attribRs = mysql_fetch_array($attrib_qry)) {?>
                                  	<li>
                                    	<a href="?subitem=editattribute3&amp;pid=<?=$prd_qryRs->prd_id?>&amp;catid=<?=$catid?>&amp;scid=<?=$sub_cat?>&name=<?=$attribRs['option_name']?>"><? echo $attribRs['option_name'];?></a>
                                    	
                                    </li>
                                    <? }?>
                                    <? while ($assocRs = mysql_fetch_array($product_assoc_qry)) {
										$product_query = mysql_query("SELECT item_title FROM product WHERE prd_id='".$assocRs['association_id']."'");
										$productRS = mysql_fetch_array($product_query);
										?>
                                  	<li>
                                        <a  href="#"><? echo stripslashes($productRS['item_title']);?></a>
                                    </li>
                                    <? }?>
                                  </ul>
                                </li>
        				<?	} // end inner while	?>
      </ul>    </td>
      <? 	$count++; 
	  		if($count == $cat_rows){	break;	}
		} // end for loop
	  ?>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  		<?	$i++;	} //end outer while	?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
