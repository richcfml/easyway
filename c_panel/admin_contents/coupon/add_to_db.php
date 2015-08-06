<?php
	include("../../../includes/config.php");
	
	 
	if($_REQUEST)  {
		$item_id = $_GET['itemcheck'];
	  	for($i=0; $i<count($_GET['itemcheck']); $i++) {
			if($i == 0  ) {
				$coupon_items_str =   $item_id[$i];
			} else {
				$coupon_items_str .= ", ".  $item_id[$i];	
			}
	 	 }
		 
		 if( $_GET['coupon_item'] == 1 ) {
			 	@$_SESSION['couponitem1'] = $coupon_items_str;  
		 } else if( $_GET['coupon_item'] == 2 )  {
				@$_SESSION['couponitem2'] = $coupon_items_str;
		 } else if( $_GET['coupon_item'] == 3 )  {
				@$_SESSION['couponitem3'] = $coupon_items_str;
		 }
	}// end if	
	
	if( $_REQUEST['couponid'] != "") {
		$couponid = $_REQUEST['couponid'];
?>	

<script type="text/javascript" language="javascript">
window.location.href = "../../?mod=coupon&item=edit&cid=<?=$couponid?>";
</script>
<?php
	}else {
?>
<script type="text/javascript" language="javascript">
window.location.href = "../../?mod=coupon&item=add&catid=<?=$_GET['category_id']?>&sh=1";
</script>
<?php
	}
?>