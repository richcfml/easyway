<?php 
	require_once("../../../includes/config.php");
?>
<script language="javascript">	
	$(document).ready(function() {
		$("#closebtn").click(function() {
			$(document).trigger('close.facebox');
			$("#assoc").load("add_assoc.php?item=" + escape($('#itemcheck').val()) );
		});
		$("#close").click(function() {
			$(document).trigger('close.facebox');
		});
	});
</script>
<form action="add_assoc.php" method="get" name="frm1" >
   <table width="540px" align="center">
    <tr>
        <td colspan="2" align="center"><strong>Check Items to associate</strong>	</td>
    </tr>
    <tr height="15">
    </tr>
    <tr >
    	<td width="20%"></td>
        <td>
		  <?
          	$item_query = dbAbstract::Execute("SELECT * FROM product WHERE cat_id='".$_GET['catid']."' AND prd_id!='".$_GET['pid']."'",1);
			 while($itemRs	=	dbAbstract::returnObject($item_query,1)){
				//if product is already associated then chech box will be checed else check box will unchecked.
				 $assoc_query = dbAbstract::Execute("SELECT  id FROM product_association WHERE product_id='".$_GET['pid']."' AND association_id ='".$itemRs->prd_id."' ",1);
				 $assoc_rows = dbAbstract::returnArray($assoc_query,1);
				 			 
          ?>
          <input  name="itemcheck[]" id="itemcheck" type="checkbox" <? if($assoc_rows['id']) { ?>  checked="checked" <? }?> value="<?=$itemRs->prd_id ?>"   /><?=stripslashes($itemRs->item_title) ?><br />
          <? }?>
          <input type="hidden" id="product_id" name="product_id" value="<?=$_GET['pid'] ?>"  />
          <input type="hidden" id="category_id" name="category_id" value="<?=$_GET['catid'] ?>"  />
     </td>
    </tr>
    <tr height="15">
    </tr>
    <tr>
    	<td></td>
        <td> <input type="submit" name="closebtn" id="closebtn" value="Submit" /></td>
    </tr>
   </table>
    <br /><br /><br /><br />
</form>

<div style="float:right"><input id="close" type="image" src="../../../images/closelabel.gif" /></div>
