<?
require_once("../../../includes/config.php");
$resturantId=$_POST['rest_id'];
$Status=$_POST['status'];
$admin_type=$_POST['admin_type'];

dbAbstract::Update("UPDATE resturants SET  status=".$Status .",status_changed_by='".$admin_type."' WHERE id=$resturantId",1);
dbAbstract::Update("UPDATE analytics SET  status=".$Status ." WHERE resturant_id = $resturantId",1);

if($Status==0) {
	$newStatus=1;
	$newStatusMsg="Activate";
	
	}
	else
	{
		$newStatus=0;
		$newStatusMsg="Deactivate";	
	}
	$result=array();
	$result["status"]=$newStatus;
	$result["message"]=$newStatusMsg;
		$result["error"]=$err;
	 	 
	  echo $newStatus."-".$newStatusMsg."-".$err;
?>
<?php mysqli_close($mysqli);?>