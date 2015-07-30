<?
include("../../../includes/config.php");
$func_obj = new clsFunctions();


?>
<script language="javascript" type="text/javascript">
function deleteHours(numrows,deleteid,catid){
    if(numrows < 2){
        alert("At least one timing must be required");
    }else{
        fillBusinessHours('DeleteBusinessHours', catid, 0, deleteid);
    }
}
</script>
<?
$updateMessage = "";
if(isset($_REQUEST['update'])) {
    
    $ids_arr = $_REQUEST['business_time_arr'];
    $open_hr_arr = $_REQUEST['open_hr'];
    $open_min_arr = $_REQUEST['open_min'];
    $close_hr_arr = $_REQUEST['close_hr'];
    $close_min_arr = $_REQUEST['close_min'];

    $tot_rec = count($_REQUEST['business_time_arr']);
    for ($insert_i=0;$insert_i<$tot_rec;$insert_i++) {

        $update_id = $ids_arr[$insert_i];
        $open_time   = $open_hr_arr[$insert_i].$open_min_arr[$insert_i];
        $close_time  = $close_hr_arr[$insert_i].$close_min_arr[$insert_i];
        Log::write("Update restaurant business hours - tab_resturant_add.php", "QUERY --UPDATE business_hours SET open='$open_time', close='$close_time' WHERE id=".$update_id, 'menu', 1 , 'cpanel');
        dbAbstract::Update("UPDATE business_hours SET open='$open_time', close='$close_time' WHERE id=".$update_id,1);
    }
    $updateMessage = "Business hours updated successfully";
}
if(isset($_GET['action'])){
    $action = $_GET['action'];
    if($action == "DeleteBusinessHours"){
        
        if(isset($_GET['deleteid'])){
            $deleteid = $_GET['deleteid'];
            dbAbstract::Delete("DELETE  FROM  business_hours WHERE id = $deleteid",1);
            $updateMessage = "Business hours deleted successfully";
        }
        
    }else if($action == "InsertBusinessHours"){
        
        if(isset($_GET['dayid'])) {
            $dayId = $_GET['dayid'];
            $buninessHrQry = dbAbstract::Execute("SELECT * FROM  business_hours WHERE id = $dayId",1);
            $buninessHrRs = dbAbstract::returnArray($buninessHrQry,1);
            Log::write("Add restaurant business hours - tab_add_businesshours.php", "QUERY --INSERT INTO business_hours SET rest_id= '".$buninessHrRs['rest_id']."' , day= '".$buninessHrRs['day']."', open=".$buninessHrRs['open'].", close=".$buninessHrRs['close'], 'menu', 1 , 'cpanel');
            dbAbstract::Insert("INSERT INTO business_hours SET rest_id= '".$buninessHrRs['rest_id']."' , day= '".$buninessHrRs['day']."', open='".$buninessHrRs['open']."', close='".$buninessHrRs['close']."'",1);
            $updateMessage = "Business hours inserted successfully";
        }
    }
}

?> 
<? if ($updateMessage != "" ){?>
<div style="background:#EFFCF5; border: 1px solid #6F976F; padding: 10px; margin: 10px 10px 20px 10px; color: #33B657; font-size: 15px; margin-bottom: 20px; margin-top: 10px;">
  <?=$updateMessage?>
</div>
<? } ?>
<form name="business_hrs" id="business_hrs" method="post" action="" onsubmit="return updateBuisnessHours();">

  <? 
  $ids_arr_i = 0;
  for ($weekday=0;$weekday<7;$weekday++) {
 	 $cm_i = 0;
	 $businesshrsQry	=	dbAbstract::Execute("select * from business_hours where day=".$weekday." AND rest_id='".$_REQUEST['catid']."' order by day asc",1);
	$businesshrsRows	=	dbAbstract::returnRowsCount($businesshrsQry,1);
	if($businesshrsRows > 0) {
?>
 <div style="position:relative; border:#333 1px solid; padding:5px; margin:10px;">
<?
	 while($businesshrsRs	=	dbAbstract::returnObject($businesshrsQry,1)){
	  if ($cm_i%2==0) {
	  	$alt_color = "#F7F7F7";
	  } else {
	  	$alt_color = "#FFFFFF";
	  }
  ?>
 <div style="position:absolute; top:-10px; color: #333; font-size:12px;  background:#FFF; padding:0px 5px 0px 5px;">
 <?=$func_obj->daysName($businesshrsRs->day)?>
 </div>
  <table width="100%" border="0" cellspacing="1" class="listig_table">
  <tr bgcolor="<?=$alt_color?>" height="25px">
    <td>From:</td>
    <td><select name="open_hr[]" id="<?='open_hr_'.$weekday."_".$cm_i?>">
      <option value="-1">Hour</option>
      <? for($i=0; $i<=23; $i++) {
	  		if ($i<10) {
					$i = '0'.$i;
				}
				if(strlen($businesshrsRs->open) < 4)
					 $open_hour = substr($businesshrsRs->open,0, 1);
				else 
					 $open_hour = substr($businesshrsRs->open,0, 2);	
			?>
      <option value="<?=$i?>" <? if ($i==$open_hour) { echo "selected"; }?>><?=$i ?></option>
      <? }?>
      </select>
      &nbsp;
      <select name="open_min[]" id="<?='open_min_'.$weekday."_".$cm_i?>">
        <option value="-1">Minute</option>
        <? 
			for($k=0; $k<=59; $k++) {
				if ($k<10) {
					$k = '0'.$k;
				}
				
				if(strlen($businesshrsRs->open) < 4)
					 $open_min = substr($businesshrsRs->open,1, 3);
				else 
					 $open_min = substr($businesshrsRs->open,2, 4);	
				
			?>
        <option value="<?=$k?>" <? if ($k==$open_min) { echo "selected"; }?>><?=$k?></option>
        <? }?>
      </select></td>
    <td>To:</td>
    <td><select name="close_hr[]" id="<?='close_hr_'.$weekday."_".$cm_i?>">
    		<option value="-1" selected="selected">Hour</option>
            <? for($i=1; $i<=23; $i++) {
					if ($i<10) {
					$i = '0'.$i;
				}
				if(strlen($businesshrsRs->close) < 4)
					 $open_hour = substr($businesshrsRs->close,0, 1);
				else 
					 $open_hour = substr($businesshrsRs->close,0, 2);		
			?>
            <option value="<?=$i?>" <? if ($i==$open_hour) { echo "selected"; }?>><?=$i?></option>
            <? }?>
    	</select>
        &nbsp;
        <select name="close_min[]" id="<?='close_min_'.$weekday."_".$cm_i?>">
    		<option value="-1">Minute</option>
            <? 
			for($k=0; $k<=59; $k++) {
				if ($k<10) {
					$k = '0'.$k;
				}
				if(strlen($businesshrsRs->close) < 4)
					 $open_min = substr($businesshrsRs->close,1, 3);
				else 
					 $open_min = substr($businesshrsRs->close,2, 4);	

				
			?>
            <option value="<?=$k?>" <? if ($k==$open_min) { echo "selected"; }?>><?=$k?></option>
            <? }?>
    	</select></td>
        <td> 
        <a href="javascript: void(0)" onclick="deleteHours('<?=$businesshrsRows?>','<?=$businesshrsRs->id?>','<?=$businesshrsRs->rest_id?>')"><img height="22px" border="0" src="../images/removetime.png" /></a>
        </td>
        
    <td><a href="javascript: void(0)" onclick="fillBusinessHours('InsertBusinessHours', <?=$_REQUEST['catid']?>, <?=$businesshrsRs->id?>, 0);return false;" style="text-decoration:underline;"><img height="22px" border="0" src="../images/addtime.png" /></a></td>
  </tr>
  <input name="business_time_arr[]" type="hidden" value="<?=$businesshrsRs->id?>" />
  <? $ids_arr_i++;$cm_i++;}
?>
</table>
</div>
<div>&nbsp;</div>
<?	  
  }
}
 ?>
<input type="hidden" name="catid" id="update" value="<?=$_REQUEST['catid']?>">
<input type="hidden" name="update" id="update" value="Update">
 <div>&nbsp;&nbsp;<input type="submit" name="submitBH" id="submitBH" value="Update"></div>
</form>
<?php mysqli_close($mysqli);?>

