<?php
	$mZipPostal = "Zip Code:";
	
	if ($objRestaurant->region == "2") //Canada
	{
		$mZipPostal = "Postal Code:";
	}
?>
<div id="business_hours_area" style="position:relative;">
	<div id="business_hours">
		<?php
		if ($objRestaurant->isOpenHour)
		{
		?>
			<a rel="facebox" href="#TodayHours" style="text-decoration: none;"><img src="../images/store_open.png" /></a>
		<?php	
		}
		else
		{
		?>	
			<a rel="facebox" href="#TodayHours" style="text-decoration: none;"><img src="../images/store_closed.png" /></a>
		<?php	
		}
		?>
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
		<div id="TodayHours" name="TodayHours" style="display: none;">
			<table style="width: 100%; margin: 0px;" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
					<center><h2 style="background-color:#EFEFEF; padding:5px 0px 5px 0px; font-size:16px;">Today's Business Hours</h2></center>
					<?php
						$mDayNumber = date("w");
						$mTime = date("Hi");
						if ($mDayNumber>0)
						{
							$mDayNumber = $mDayNumber - 1; //Because in PHP 0 is for Sunday while in our system 0 is programed for Monday.
						}
						else
						{
							$mDayNumber = 6; //Because in PHP 0 is for Sunday while in our system 0 is programed for Monday.						
						}
						
						$arr_days=$objRestaurant->DayBusinessHours($mDayNumber);
						$mCount = 1;
                                                $mOpenHourCount = count($arr_days);
					  	foreach($arr_days as $day)
						{
							if ($mCount==1)
							{
					?>
					  			<div class="hour_row">
								<div class="day_name"> <?= $day->dayName ?> </div>
					<?php			
							}
                                                        if ($mOpenHourCount==1)
                                                        {
                                                            if (($day->open=="Closed") && ($day->close=="Closed"))
                                                            {
                                        ?>
                                                                <div class="bgrow"  style="text-align: center !important;">
                                                                    <div class="one-fourth bold"  style="width: 100% !important; text-align: center !important;"> Closed </div>
                                                                    <div class="clear"></div>
                                                                </div>
                                        <?php
                                                            }  
                                                            else
                                                            {
                                        ?>
                                                                <div class="bgrow" style="line-height: 40px;">
                                                                    <div class="one-fourth bold"> From: </div>
                                                                    <div class="one-fourth"> <?= $day->open ?></div>
                                                                    <div class="one-fourth bold"> To: </div>
                                                                    <div class="one-fourth column-last"><?= $day->close ?> </div>
                                                                    <div class="clear"></div>
								</div>
                                        <?php
                                                            }
                                                        }
                                                        else
                                                        {
					?>		
								<div class="bgrow" style="line-height: 40px;">
                                                                    <div class="one-fourth bold"> From: </div>
                                                                    <div class="one-fourth"> <?= $day->open ?></div>
                                                                    <div class="one-fourth bold"> To: </div>
                                                                    <div class="one-fourth column-last"><?= $day->close ?> </div>
                                                                    <div class="clear"></div>
								</div>
					<?php
                                                        }
							if ($mCount == count($arr_days))
							{
					?>							 
					  	</div>
					<?php 
							}
							$mCount++;
						}
						
						if (!($objRestaurant->isOpenHour))
						{
							$mSQL = "SELECT open AS OpenHours, close AS CloseHours FROM business_hours WHERE rest_id=". $objRestaurant->id." AND day=".$mDayNumber." AND open>".$mTime." ORDER BY open ASC LIMIT 1";
							$mRes = dbAbstract::Execute($mSQL);
							if (dbAbstract::returnRowsCount($mRes)>0)
							{
								$mRow = dbAbstract::returnObject($mRes);	
								$mRow->OpenHours = date("g:i A",strtotime($mRow->OpenHours));
								$mRow->CloseHours = date("g:i A",strtotime($mRow->CloseHours));
					?>
								<div class="hour_row">	
									<div class="day_name">Next opening hours</div>
									<div class="bgrow" style="line-height: 40px;">
										<div class="one-fourth bold"> From: </div>
										<div class="one-fourth"> <?= $mRow->OpenHours ?></div>
										<div class="one-fourth bold"> To: </div>
										<div class="one-fourth column-last"><?= $mRow->CloseHours ?> </div>
										<div class="clear"></div>
									</div>
								</div>
					<?php			
							}
							else
							{
					?>
								<span style="color: maroon;">No more open hours today.</span>
					<?php			
							}
						}
					?>
					</td>
				</tr>
				<tr>
					<td style="text-align: right;" align="right">
						<div style="float:right">
							<img id="close" src="../images/closelabel.gif" style="cursor: hand; cursor: pointer;" onclick="$.facebox.close();" />
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="horizontal_line"></div>
	<div style="padding-left:0px; float:left;">
    	<div id="weeks" style="font-size:15px; font-weight:bold;">
			<!--Monday:-->
		</div>
		<div id="weeks" style="font-size:15px;font-weight:bold;"> 
    		<!-- From 7:00 am To 7:00 pm,--> 
			<a href="?item=businesshours&kiosk=1&ajax=1" rel="facebox" style="text-decoration:none; float:left; color:#CC0000;">
				<?=  date('g:i A' , $objRestaurant->openTime)."<br />&nbsp&nbsp&nbsp&nbsp  to <br />".date('g:i A' , $objRestaurant->closeTime);?>
			</a> 
		</div>
    	<div style="clear:both"></div>
	</div>
<?php  
if($objRestaurant->useValutec==false) 
{ 
?>
	<!-- VIP MAIL LIST/ DEFAULT BANNER -->  
	<div id="banner">
		<a style="text-decoration:none" rel="facebox" href="?item=mailinglist&kiosk=1&rest_id=<?=$objRestaurant->id?>&ajax=1">
			Join our VIP list and get discounts sent to your phone or email
		</a>
	</div>
	<!-- VIP MAIL LIST/ DEFAULT BANNER ENDS HERE -->  
<?php 
}  
?>     
	<div id="order_by_area"> 
		<!--<div id="order_by">ORDER BY</div>-->
	    <div id="other">
    		<?= $objRestaurant->rest_address ?>
	    </div>
    	<div id="other">
			<?=$objRestaurant->rest_city.", ".$objRestaurant->rest_state ?>
	    </div>
		<div id="other">
			<?=$mZipPostal?>
	    	<?=$objRestaurant->rest_zip?>
    	</div>
	    <div id="other">
			Phone:
			<?=$objRestaurant->phone?>
    	</div>
	    <div style="clear:left"></div>
	</div>
	<!--End order_by_area Div-->
	<div style="clear:both"></div>
</div>
<div id="display_uername_area">
	<div id="main-menu">
<?php
	if (($objRestaurant->region == 1) || ($objRestaurant->region == 2))
	{
		$currency = "$";
	} 
	else 
	{
		$currency = "&#163;";
	}
	$objMenu->restaurant_id = $objRestaurant->id;
	$menuid = (isset($_GET['menuid']) ? $_GET['menuid']:"");
	$menuname='';
	$menus = $objMenu->getMenusByRestaurantId();
	$isOpen=true;
	$iscurrentMenuAvaible=1;
	$currentMenuTimings="";
	for($i = 0; $i < count($menus); $i++) 
	{	
		$class='';
		$menu=$menus[$i];
		$isOpen=$menu->isMenuOpen();
		
		if($isOpen==1 && $i == 0 && $menuid == "") 
		{
			$menuid = $menu->id;
			$class = 'selected_red';
		} 
		else if($isOpen==1 && $menuid == "") 
		{
			$menuid = $menu->id;
			$class = 'selected_red';
		} 
		else if($isOpen==0) 
		{
			$menuname=$menu->menu_name;
			$currentMenuTimings="[". $menu->openTime ." to ". $menu->closeTime ."]";
			$class = 'menu_disabled';
		} 
		elseif($menuid == $menu->id) 
		{
			$class = 'selected_red';
		}
		
		if($menuid == $menu->id && $isOpen==0)
		{
			$iscurrentMenuAvaible=0;
		}	 
?>
		&nbsp;&nbsp;<a class="<?=$class?>"  title="<?= stripslashes($menu->menu_name) ?>"  menuid="<?=$menu->id ?>" timings='<?= $menu->openTime ." to ". $menu->closeTime  ?>' href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?menuid=<?=$menu->id ?>&kiosk=1"><?= stripslashes($menu->menu_name) .
		($isOpen==0 ?"[". $menu->openTime ." to ". $menu->closeTime ."]":"") ?></a>
		&nbsp;&nbsp;
<?php  
		if($i < count($menus)-1) 
			echo "|&nbsp;&nbsp;"; 
	}
?>    
  </div>
 

</div>