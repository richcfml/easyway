<? 
include("../../../includes/config.php");
	
?>
<script language="javascript">	
	$(document).ready(function() {
		$("#close").click(function() {
			$(document).trigger('close.facebox');
		});
	});
</script>
<style>
.check_box_dynamic_div{width:250px; float:left; font-size:12px;}
.heading{ text-align:center; font-size:14px; font-weight:bold; background-color:#EFEFEF; padding:5px 0px 5px 0px; margin-bottom:10px;}
.line_540px{ background-color:#CCC; height:1px; margin-top:10px;}
.check_box_dynamic_div_bold{ padding-top:10px;}
</style>
<form action="add_assoc.php" method="get" name="frm1" >
<div style="width:1000px;">
   <div class="heading">Check Items To Associate</div>
   
    <?
	$counter = 0; 
    $item_query = mysql_query("SELECT * FROM product WHERE cat_id='".$_GET['catid']."' AND prd_id!='".$_GET['pid']."'");
	 while($itemRs	=	mysql_fetch_object($item_query)){
		//if product is already associated then chech box will be checed else check box will unchecked.
		 $assoc_query = mysql_query("SELECT  id FROM product_association WHERE product_id='".$_GET['pid']."' AND association_id ='".$itemRs->prd_id."' ");
		 $assoc_rows = mysql_fetch_array($assoc_query); 			
	?>
   <div class="check_box_dynamic_div"><input  name="itemcheck[]" id="itemcheck" type="checkbox" <? if($assoc_rows['id']) { ?>  checked="checked" <? }?> value="<?=$itemRs->prd_id ?>"   /><?=stripslashes($itemRs->item_title);  ?>
   </div>
   <? $counter++; }?>
  <input type="hidden" id="product_id" name="product_id" value="<?=$_GET['pid'] ?>"  />
  <input type="hidden" id="category_id" name="category_id" value="<?=$_GET['catid'] ?>"  />
  <input type="hidden" id="sub_cat_id" name="sub_cat_id" value="<?=$_GET['sub_cat'] ?>"  />

   
   
   
   <div style="clear:both"></div>
   <div class="line_540px">&nbsp;</div>
   <div class="check_box_dynamic_div_bold"><input type="checkbox" id="apply_subcat" name="apply_subcat" value="applytosubcat" /><strong>Apply This Association To All Menu Items In the Same Sub-Category</strong></div>
    <input type="submit" name="closebtn" id="closebtn" value="Submit" style="width:80px; height:25px; font-size:14px; padding-bottom:2px; margin-top:10px;"/>
</div>   
</form>
<br />
<br />

<div style="float:right"><input id="close" type="image" src="../images/closelabel.gif" /></div>







