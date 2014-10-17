<?
		$coupon_id	=	$_REQUEST['cid'];
		mysql_query("DELETE FROM coupontbl where coupon_id = $coupon_id");
?>
		<script language="javascript">
				window.location="./?mod=coupon";
		</script>