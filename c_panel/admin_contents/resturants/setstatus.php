<?
 
include("../../../includes/config.php");
$resturantId=$_POST['rest_id'];
$Status=$_POST['status'];
$admin_type=$_POST['admin_type'];

mysql_query("UPDATE resturants SET  status=".$Status .",status_changed_by='".$admin_type."' WHERE id=$resturantId");
mysql_query("UPDATE analytics SET  status=".$Status ." WHERE resturant_id = $resturantId");
$err=mysql_error();
@mysql_close($mysql_conn);
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