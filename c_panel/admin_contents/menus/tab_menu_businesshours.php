 <link href="css/adminMain.css" rel="stylesheet" type="text/css" />
<?
 ini_set('display_errors',0);
require_once ("../classes/menu.php");
require_once ("../classes/Log.php");
 
 $menu=new menu();
 $menu->id=$_GET['menuid'];
 $updateMessage="";
 
 include("../includes/function.php");
 
$func_obj = new clsFunctions();


if(isset($_POST['update'])) {
                Log::write("Delete menu hours", "QUERY -- delete from menu_hours  WHERE menu_id=".$_GET['menuid'], 'menu', 1 , 'cpanel');
		mysql_query("delete from menu_hours  WHERE menu_id=".$_GET['menuid']);
		
 
	$open_hr_arr = $_POST['open_hr'];
	$open_min_arr = $_POST['open_min'];
	$close_hr_arr = $_POST['close_hr'];
	$close_min_arr = $_POST['close_min'];
	
	 
	for ($index=0;$index<7;$index++) {
		
	 if( $open_hr_arr[$index]!="-1" &&  $close_hr_arr[$index]!="-1") {
		 if($open_min_arr[$index]=="-1") $open_min_arr[$index]="00";
 		 if($close_min_arr[$index]=="-1") $close_min_arr[$index]="00";
		 
		$open_time   = $open_hr_arr[$index].$open_min_arr[$index];
		$close_time  = $close_hr_arr[$index].$close_min_arr[$index];
		
                Log::write("Adding menu business hours", "QUERY -- insert into menu_hours SET open='$open_time', close='$close_time',day=$index , menu_id=".$_GET['menuid'], 'menu', 1 , 'cpanel');
		mysql_query("insert into menu_hours SET open='$open_time', close='$close_time',day=$index , menu_id=".$_GET['menuid']);
		if(mysql_affected_rows()>0)
                    {
                        $updateMessage="Menu timings updated Successfully";
                    } 
	 }
	}
}

 $menu->getBusinessHours();
 $arrhours=$menu->arr_hours_list;
 
?>
 <? if ($updateMessage != "" ) { ?>
<div style="background:#A7B6BD; border: 1px solid #1A1B1A; padding: 10px 10px 10px 10px; margin: 0px 10px 0px 10px; color: #FFFFFF; font-size: 15px; margin-bottom: 20px; margin-top: 10px;">
  <?=$updateMessage?>
</div>
<? }?>
<body style="background-color:#FFFFFF">
  <form name="business_hrs" id="business_hrs" method="post" action="">

  <? 
  $ids_arr_i = 0;
  for ($weekday=0;$weekday<7;$weekday++) {
  $b_day=$arrhours[$weekday];
 
?>
 <div style="position:relative; border:#333 1px solid; padding:5px; margin:10px;">
	 <div style="position:absolute; top:-10px; color: #333; font-size:12px;  background:#FFF; padding:0px 5px 0px 5px;">
 <?=$func_obj->daysName($weekday)?>
 </div>
  <table width="100%" border="0" cellspacing="1" class="listig_table">
  <tr bgcolor="<?=$alt_color?>" height="25px">
    <td>From:</td>
    <td><select name="open_hr[]" id="<?='open_hr_'.$weekday?>">
      <option value="-1">Hour</option>
      <? for($i=0; $i<=23; $i++) {
	  		if ($i<10) {
					$i = '0'.$i;
				}
				if(strlen($b_day->open) < 4)
					 $open_hour = substr($b_day->open,0, 1);
				else 
					 $open_hour = substr($b_day->open,0, 2);	
			?>
      <option value="<?=$i?>" <? if ($i==$open_hour) { echo "selected"; }?>><?=$i ?></option>
      <? }?>
      </select>
      &nbsp;
      <select name="open_min[]" id="<?='open_min_'.$weekday?>">
        <option value="-1">Minute</option>
        <? 
			for($k=0; $k<=59; $k++) {
				if ($k<10) {
					$k = '0'.$k;
				}
				
				if(strlen($b_day->open) < 4)
					 $open_min = substr($b_day->open,1, 3);
				else 
					 $open_min = substr($b_day->open,2, 4);	
				
			?>
        <option value="<?=$k?>" <? if ($k==$open_min) { echo "selected"; }?>><?=$k?></option>
        <? }?>
      </select></td>
    <td>To:</td>
    <td><select name="close_hr[]" id="<?='close_hr_'.$weekday?>">
    		<option value="-1" selected="selected">Hour</option>
            <? for($i=1; $i<=23; $i++) {
					if ($i<10) {
					$i = '0'.$i;
				}
				if(strlen($b_day->close) < 4)
					 $open_hour = substr($b_day->close,0, 1);
				else 
					 $open_hour = substr($b_day->close,0, 2);		
			?>
            <option value="<?=$i?>" <? if ($i==$open_hour) { echo "selected"; }?>><?=$i?></option>
            <? }?>
    	</select>
        &nbsp;
        <select name="close_min[]" id="<?='close_min_'.$weekday?>">
    		<option value="-1">Minute</option>
            <? 
			for($k=0; $k<=59; $k++) {
				if ($k<10) {
					$k = '0'.$k;
				}
				if(strlen($b_day->close) < 4)
					 $open_min = substr($b_day->close,1, 3);
				else 
					 $open_min = substr($b_day->close,2, 4);	

				
			?>
            <option value="<?=$k?>" <? if ($k==$open_min) { echo "selected"; }?>><?=$k?></option>
            <? }?>
    	</select> 
       
 </td>
 
  </tr>
 
</table>
</div>
<div>&nbsp;</div>
<?	  
 
  }
 ?>
 <div>&nbsp;&nbsp;<input type="submit" name="update" id="update" value="Update"></div>
</form>
</body>

