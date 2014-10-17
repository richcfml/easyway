<script src="../js/jquery.js" type="text/javascript"></script>
<link href="../css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox.js" type="text/javascript"></script>
<script language="javascript">
	jQuery(document).ready(function($) {
		$('a[rel*=facebox]').facebox();
	});
</script>

<? 
///////Menu activate/Deactivate Section////////////////////
if (isset($_REQUEST['menuActivateId'])){
	$id = $_REQUEST['menuActivateId'];
        Log::write("Update menu status - tab_resturant_menu.php", "QUERY -- UPDATE menus SET  status='1' WHERE id=$id", 'menu', 1 , 'cpanel');
	mysql_query("UPDATE menus SET  status='1' WHERE id=$id");
} else if (isset($_REQUEST['menuDeactivateId'])){
	$id = $_REQUEST['menuDeactivateId'];
        Log::write("Update menu status - tab_resturant_menu.php", "QUERY -- UPDATE menus SET  status='0' WHERE id=$id", 'menu', 1 , 'cpanel');
	mysql_query("UPDATE menus SET  status='0' WHERE id=$id");
} 
//////////////////////////////////////////////
///////Sub Menu activate/Deactivate Section////////////////////
if (isset($_REQUEST['submenuActivateId'])){
	$id = $_REQUEST['submenuActivateId'];
        Log::write("Update submenu status - tab_resturant_menu.php", "QUERY -- UPDATE categories SET  status='1' WHERE cat_id=$id", 'menu', 1 , 'cpanel');
	mysql_query("UPDATE categories SET  status='1' WHERE cat_id=$id");
	
	
} else if (isset($_REQUEST['submenuDeactivateId'])){
	$id = $_REQUEST['submenuDeactivateId'];
        Log::write("Update submenu status - tab_resturant_menu.php", "QUERY -- UPDATE categories SET  status='0' WHERE cat_id=$id", 'menu', 1 , 'cpanel');
	mysql_query("UPDATE categories SET  status='0' WHERE cat_id=$id");
} 
//////////////////////////////////////////////

///////Product activate/Deactivate Section////////////////////

if (isset($_REQUEST['ProdActivateId'])){
	$id = $_REQUEST['ProdActivateId'];
        Log::write("Update product status- tab_resturant_menu.php", "QUERY -- UPDATE product SET  status='1' WHERE prd_id=$id", 'menu', 1 , 'cpanel');
	mysql_query("UPDATE product SET  status='1' WHERE prd_id=$id");
} else if (isset($_REQUEST['ProdDeactivateId'])){
	$id = $_REQUEST['ProdDeactivateId'];
        Log::write("Update product status- tab_resturant_menu.php", "QUERY -- UPDATE product SET  status='0' WHERE prd_id=$id", 'menu', 1 , 'cpanel');
	mysql_query("UPDATE product SET  status='0' WHERE prd_id=$id");
} 
//////////////////////////////////////////////

//$_SESSION[cid];
$catid = $Objrestaurant->id;
$menuid=0;
if(isset($_REQUEST['menuid'])){
	$menuid =$_REQUEST['menuid'];
} 
?>
<div id="main_heading">
	<div id="nav_links">
		<?	
		$menu_qry	=	mysql_query("select * from menus where rest_id = ".$Objrestaurant->id."");
		$menu_i= 0;
		while($menuRs	=	mysql_fetch_array($menu_qry) ) {
		?>
		<? if ($menu_i!=0) {?>|<? }?> 
        
        
        <? if($menuRs['status']  == 1) {?>
          <a href="?mod=menus&item=menustatus&catid=<?=$catid?>&menuDeactivateId=<?=$menuRs['id']?>" onClick="return confirm('Are you sure you would like to change the status of this Menu?')"><img src="../images/enable.png" width="16" height="16" border="0" title="Enabled" /></a>
           <? }else if($menuRs['status']  == 0) { ?>
          <a href="?mod=menus&item=menustatus&catid=<?=$catid?>&menuActivateId=<?=$menuRs['id']?>" onClick="return confirm('Are you sure you would like to change the status of this Menu?')"><img src="../images/disable.png" width="16" height="16" border="0" title="Disabled" /></a>
     <? }?>
     
        <a <? if($menuRs['id'] == $menuid || ($menu_i==0 && $menuid=="")) echo "style='color:#CC0000'";  ?>    href="?mod=menus&menuid=<?=$menuRs['id']?>&catid=<?=$catid?>" > <?=$menuRs['menu_name']?> </a> <a href="?mod=menus&item=editmenu&catid=<?=$catid?>&menuid=<?=$menuRs['id']?>" ><img src="../images/page_white_edit.png" width="14" height="14" border="0" title="Edit" /></a>
        <? $menu_i++;}?>
    </div>
        </div>
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
		if($menuid) {
			$cat_qry	=	mysql_query("select * from categories where menu_id = ".$menuid." ");
		} else {
			$cat_qry	=	mysql_query("select * from categories where menu_id=(SELECT  id FROM menus where rest_id = $catid ORDER BY menu_ordering ASC limit 1)");		
		}
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
						 $sub_cat_status = mysql_result($cat_qry,$count,"status");
  ?>
    <td width="50%">
  	
     <? if($sub_cat_status  == 1) {?>
       <a href="?mod=menus&item=submenustatus&catid=<?=$catid?>&submenuDeactivateId=<?=$sub_cat?>" onClick="return confirm('Are you sure you would like to change the status of  this Sub Menu?')"><img src="../images/enable.png" width="16" height="16" border="0" title="Enabled" /></a>
       <? }else if($sub_cat_status  == 0) { ?>
       <a href="?mod=menus&item=submenustatus&catid=<?=$catid?>&submenuActivateId=<?=$sub_cat?>" onClick="return confirm('Are you sure you would like to change the status of  this Sub Menu?')"><img src="../images/disable.png" width="16" height="16" border="0" title="Disabled" /></a>
       <? }?>
     
    <a href="?mod=menus&item=edit&catid=<?=$catid?>&sub_cat=<?=$sub_cat?>" class="largelink"><strong><?=stripslashes(stripcslashes($sub_cat_name))?></strong></a>&nbsp;&nbsp;<a href="?mod=menus&item=addproduct&catid=<?=$catid?>&scid=<?=$sub_cat?>" class="smalllink">Add Items</a>
      <ul id="menus">
      <?	$subcat_id	=	mysql_result($cat_qry,$count,"cat_id");
	  		$prd_qry	=	mysql_query("select * from product where sub_cat_id = $subcat_id");
			
      				while($prd_qryRs = mysql_fetch_object($prd_qry)){	?>
                            	  <li>
		<? if($prd_qryRs->status  == 1 && $sub_cat_status == 1) {?>
        <a href="?mod=menus&item=productstatus&catid=<?=$catid?>&ProdDeactivateId=<?=$prd_qryRs->prd_id?>" onClick="return confirm('Are you sure you would like to change the status of this Product?')"><img src="../images/enable.png" width="16" height="16" border="0" title="Enabled" /></a>
         <? }else if($prd_qryRs->status == 0 || ($prd_qryRs->status == 1 && $sub_cat_status == 0)) { ?>
        <a href="?mod=menus&item=productstatus&catid=<?=$catid?>&ProdActivateId=<?=$prd_qryRs->prd_id?>" onClick="return confirm('Are you sure you would like to change the status of this Product?')"><img src="../images/disable.png" width="16" height="16" border="0" title="Disabled" /></a>
		<? }?>	
                                  
                                  
                                  
                                  <a href="?mod=menus&item=editproduct2&amp;pid=<?=$prd_qryRs->prd_id?>&amp;catid=<?=$catid?>&amp;scid=<?=$subcat_id?>" class="mediumlink"><?=stripslashes(stripcslashes($prd_qryRs->item_title))?></a>
                                  &nbsp;&nbsp;<a href="?mod=menus&item=addattribute&amp;pid=<?=$prd_qryRs->prd_id?>&amp;catid=<?=$catid?>&amp;scid=<?=$sub_cat?>" class="smalllink">Add Attribute</a> <a href="admin_contents/menus/popup.php?catid=<?=$catid?>&sub_cat=<?=$sub_cat?>&pid=<?=$prd_qryRs->prd_id ?>" rel="facebox" class="smalllink">associate item</a>
                                  <?
								  $attrib_qry = mysql_query("SELECT distinct(option_name) FROM attribute WHERE 	ProductID ='".$prd_qryRs->prd_id."'");
								  $product_assoc_qry = mysql_query("SELECT association_id FROM product_association WHERE product_id ='".$prd_qryRs->prd_id."'");						
								  ?>
                                  <ul id="items">
                                  	<? while ($attribRs = mysql_fetch_array($attrib_qry)) {?>
                                  	<li>
                                    	<a href="?mod=menus&item=editattribute&amp;pid=<?=$prd_qryRs->prd_id?>&amp;catid=<?=$catid?>&amp;scid=<?=$sub_cat?>&name=<?=$attribRs['option_name']?>"><? echo $attribRs['option_name'];?></a>
                                    	
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
