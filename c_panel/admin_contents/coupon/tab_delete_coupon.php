<?php
		$coupon_id	=	$_REQUEST['cid'];
		dbAbstract::Delete("DELETE FROM coupontbl where coupon_id = $coupon_id", 1);
?>
		<script language="javascript">
				window.location="./?mod=coupon";
		</script>