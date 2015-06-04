<style>
 div.hour_row{
	 position:relative; border:#333 1px solid; 
	 padding:10px; margin:20px 10px 30px 10px;
	 font-size:13px;
	 
	 }
	 div.hour_row .day_name{ position:absolute; top:-10px; color: #333; font-size:13px;  background:#FFF; padding:0px 5px 0px 5px;}
	 
 .one-fourth, .four-columns {
	width:21%;
	position:relative;
	margin-right:4%;
	float:left;
}
.bgrow{
	background-color:#F7F7F7;
	}

</style>
<center><h2 style="background-color:#EFEFEF; padding:5px 0px 5px 0px; font-size:16px;">Full Week Business Hours</h2></center>

  <?php
    $arr_days=$objRestaurant->allBusinessHours();
    foreach($arr_days as $day)
    {
        if (($day->open=="Closed") && ($day->close=="Closed"))
        {
   ?>
    <div class="hour_row" style="text-align: center !important;">
        <div class="day_name"> <?= $day->dayName ?> </div>
        <div class="bgrow"  style="text-align: center !important;">
            <div class="one-fourth bold"  style="width: 100% !important; text-align: center !important;"> Closed </div>
            <div class="clear"></div>
        </div>
    </div>
   <?php
        }
        else
        {
  ?>
  <div class="hour_row">
    <div class="day_name"> <?= $day->dayName ?> </div>
    <div class="bgrow">
        <div class="one-fourth bold"> From: </div>
        <div class="one-fourth"> <?= $day->open ?></div>
        <div class="one-fourth bold"> To: </div>
        <div class="one-fourth column-last"><?= $day->close ?> </div>
        <div class="clear"></div>
    </div>
  </div>
  <?php 
        }
    } 
  ?>
 <br class="clearfloat" /> 
    <div style="float:right"><input id="close" type="image" src="<?=$SiteUrl?>images/closelabel.gif" /></div>
 <script language="javascript">	
	$(function() {
		$(".close").click(function() {
			$(document).trigger('close.facebox');
			location.reload();
		});
		
		$("#close").live('click',function() {
			$(document).trigger('close.facebox');
		 
		});
	});
		</script>