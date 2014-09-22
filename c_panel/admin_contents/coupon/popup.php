<? 
	include("../../../includes/config.php");
	
?>
<script type="text/javascript">
function saveSelectedItems(couponItem){
	var count = 0;
	var selectedItems=""
	var selectedItemTitles=""
	var selectedItemIds=""
	for(var i=0; i < document.frmpopup.itemcheck.length; i++){
	if(document.frmpopup.itemcheck[i].checked) {
	  count+= 1;
	  //selectedItems +=document.frmpopup.itemcheck[i].value + " ,"
		var valArr = document.frmpopup.itemcheck[i].value
		var itemArr = valArr.split("~");
/*		if(count == 1) {
		  selectedItemTitles += itemArr[1] ;
		  selectedItemIds += itemArr[0] ;
		} else {
		  selectedItemTitles += "," + itemArr[1] ; 
		  selectedItemIds += "," + itemArr[0] ; 
		}
*/	

		selectedItemTitles += itemArr[1]+","; 
		selectedItemIds += itemArr[0]+","; 
		
	}

	}
	if( couponItem == 1 ) {
		document.getElementById('selitems1').innerHTML = selectedItemTitles;
		document.getElementById('coupon_items1').value = selectedItemIds;
		
	} else if( couponItem == 2 ) {
		document.getElementById('selitems2').innerHTML = selectedItemTitles;
		document.getElementById('coupon_items2').value = selectedItemIds;
	} else if( couponItem == 3 ) {
		document.getElementById('selitems3').innerHTML = selectedItemTitles;
		document.getElementById('coupon_items3').value = selectedItemIds;
	}
}
</script>
<script language="javascript">	
	$(document).ready(function() {
		$("#close").click(function() {
			$(document).trigger('close.facebox');
		});
		$("#add").click(function() {
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
<form action="admin_contents/coupon/add_to_db.php" method="get" name="frmpopup" >
<div style="width:1000px;">
  	<? 
  	if( $_GET['coupon_item'] == "1" || $_GET['coupon_item'] == "2" ) { 		
  	?>
    <div class="heading">Check Items To Add To Coupon</div>
   	<? }  else if( $_GET['coupon_item'] == "3") {?>
    <div class="heading">Check Categories To Add To Coupon</div>
    <? }?>
    <?
	$counter = 0;
	if ($_GET['coupon_item'] == "3") {
		$item_query = mysql_query("SELECT * FROM categories WHERE parent_id='".$_GET['catid']."' AND status = '1' ");
		//echo "SELECT * FROM categories WHERE parent_id='".$_GET['catid']."' ";
		}
		
	if ($_GET['coupon_item'] == "1" || $_GET['coupon_item'] == "2") {
		$item_query = mysql_query("SELECT * FROM product WHERE cat_id='".$_GET['catid']."' AND status = '1' ");
	}
		if($_REQUEST['couponid'] != "") {
		 $coupontbl_cats_selc_arr = mysql_fetch_array(mysql_query("SELECT * FROM coupontbl WHERE coupon_id=".$_REQUEST['couponid']));
		}
			if ($_GET['coupon_item'] == 1){
	
					$coponids_split = split(",",$coupontbl_cats_selc_arr['coupon_items1']);
				
			} else if ($_GET['coupon_item'] == 2){
				
					$coponids_split = split(",",$coupontbl_cats_selc_arr['coupon_items2']);
				
			}else if ($_GET['coupon_item'] == 3){
					$coponids_split = split(",",$coupontbl_cats_selc_arr['coupon_items1']);
				
			}
	
	$selec_i = 0;
	 while($itemRs	=	mysql_fetch_object($item_query)){
		 if ($_GET['coupon_item'] == 3) {
			$popselect_ = $itemRs->cat_id;
		 } else {
			$popselect_ = $itemRs->prd_id;	 
		 }
		 
		 if ($popselect_==$coponids_split[$selec_i]) {
			$checked = "checked";
			$selec_i++;
		 } else {
			$checked = "";  
		 }
		//if product is already associated then chech box will be checed else check box will unchecked.
		 //$assoc_query = mysql_query("SELECT  id FROM product_association WHERE product_id='".$_GET['pid']."' AND association_id ='".$itemRs->prd_id."' ");
		 //$assoc_rows = mysql_fetch_array($assoc_query);
		 if( $_GET['coupon_item'] == "1" || $_GET['coupon_item'] == "2" ) { 			
	?>
   <div class="check_box_dynamic_div"><input  name="itemcheck[]" id="itemcheck" type="checkbox"  value="<?=$itemRs->prd_id ?>~<?=$itemRs->item_title ?>" <?=$checked?>/><?=stripslashes($itemRs->item_title);  ?>
   </div>
   		<?
		 } else if( $_GET['coupon_item'] == "3") {
		?>
        <div class="check_box_dynamic_div"><input  name="itemcheck[]" id="itemcheck" type="checkbox"  value="<?=$itemRs->cat_id ?>~<?=$itemRs->cat_name ?>" <?=$checked?>/><?=stripslashes($itemRs->cat_name);  ?>
   </div>
   		<?
		 }
		?>
   <? $counter++; }?>
  <input type="hidden" id="couponid" name="couponid" value="<?=$_GET['couponid'] ?>"  />
  <input type="hidden" id="coupon_item" name="coupon_item" value="<?=$_GET['coupon_item'] ?>"  />

   <div style="clear:both"></div>
   <div class="line_540px">&nbsp;</div>
   <div class="check_box_dynamic_div_bold"></div>
    <input type="button" name="add" id="add" value="Add Selected" onclick="saveSelectedItems('<?=$_GET['coupon_item']?>');" style="width:100px; height:25px; font-size:14px; padding-bottom:2px; margin-top:10px;"/>
</div>   
</form>
<br />
<br />

<div style="float:right"><input id="close" type="image" src="../images/closelabel.gif" /></div>