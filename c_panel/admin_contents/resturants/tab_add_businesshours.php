<? 
require_once("../../../includes/config.php");

	if( $_REQUEST['deleteid'] != '') {
		$catid = $_REQUEST['catid'];
		$deleteid = $_REQUEST['deleteid'];
		mysql_query("DELETE  FROM  business_hours WHERE id = $deleteid");
	} else {
	$dayId = $_REQUEST['dayid'];
	$catid = $_REQUEST['catid'];
	$buninessHrQry = mysql_query("SELECT * FROM  business_hours WHERE id = $dayId");
	$buninessHrRs = mysql_fetch_array($buninessHrQry);
        //echo "INSERT INTO business_hours SET rest_id= '".$buninessHrRs['rest_id']."' , day= '".$buninessHrRs['day']."', open='".$buninessHrRs['open']."', close='".$buninessHrRs['close']."'";
	Log::write("Add restaurant business hours - tab_add_businesshours.php", "QUERY --INSERT INTO business_hours SET rest_id= '".$buninessHrRs['rest_id']."' , day= '".$buninessHrRs['day']."', open='".$buninessHrRs['open']."', close='".$buninessHrRs['close']."'", 'menu', 1 , 'cpanel');
	mysql_query("INSERT INTO business_hours SET rest_id= '".$buninessHrRs['rest_id']."' , day= '".$buninessHrRs['day']."', open='".$buninessHrRs['open']."', close='".$buninessHrRs['close']."'");
	}
?>
<?
@mysql_close($mysql_conn);
?>

<script language="javascript">
	window.location="tab_resturant_businesshours.php?catid=<?=$catid?>&<?=time()?>";
</script>
