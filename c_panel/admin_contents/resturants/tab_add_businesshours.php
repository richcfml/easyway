<? 
require_once("../../../includes/config.php");

	if( $_REQUEST['deleteid'] != '') {
		$catid = $_REQUEST['catid'];
		$deleteid = $_REQUEST['deleteid'];
		dbAbstract::Delete("DELETE  FROM  business_hours WHERE id = $deleteid",1);
	} else {
	$dayId = $_REQUEST['dayid'];
	$catid = $_REQUEST['catid'];
	$buninessHrQry = dbAbstract::Execute("SELECT * FROM  business_hours WHERE id = $dayId",1);
	$buninessHrRs = dbAbstract::returnArray($buninessHrQry,1);
	
	Log::write("Add restaurant business hours - tab_add_businesshours.php", "QUERY --INSERT INTO business_hours SET rest_id= '".$buninessHrRs['rest_id']."' , day= '".$buninessHrRs['day']."', open='".$buninessHrRs['open']."', close='".$buninessHrRs['close']."'", 'menu', 1 , 'cpanel');
	dbAbstract::Insert("INSERT INTO business_hours SET rest_id= '".$buninessHrRs['rest_id']."' , day= '".$buninessHrRs['day']."', open='".$buninessHrRs['open']."', close='".$buninessHrRs['close']."'",1);
	}
        <?php mysqli_close($mysqli);?>
?>

<script language="javascript">
	window.location="tab_resturant_businesshours.php?catid=<?=$catid?>&<?=time()?>";
</script>
