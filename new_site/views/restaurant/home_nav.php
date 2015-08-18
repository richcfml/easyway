<?php
	$mZipPostal = "Zip Code:";
	
	if ($objRestaurant->region == "2") //Canada
	{
		$mZipPostal = "Postal Code:";
	}
?>
<div id="business_hours_area" style="position:relative;">
	<div id="business_hours">
		<img src="../images/<? if ($objRestaurant->isOpenHour) echo "store_open.png"; else echo "store_closed.png"; ?>" />
	</div>
	<div id="horizontal_line"></div>
	<div style="padding-left:0px; float:left;">
    	<div id="weeks" style="font-size:15px; font-weight:bold;">
			<!--Monday:-->
		</div>
		<div id="weeks" style="font-size:15px;font-weight:bold;"> 
    		<!-- From 7:00 am To 7:00 pm,--> 
			<a href="?item=businesshours&ajax=1" rel="facebox" style="text-decoration:none; float:left; color:#CC0000;">
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
        <?php
        $mMarginTopbanner = "";
        $mVipDisplay = "Join our VIP list and get discounts sent to your phone or email";
        if (trim($objRestaurant->VIP_List_Image)!= "")
        {
            $mMarginTopbanner = " style='margin-top: 5px !important;' ";
            $mVipDisplay = "<img src='../images/resturant_vip_headers/$objRestaurant->VIP_List_Image' alt='Join our VIP list and get discounts sent to your phone or email' title='Join our VIP list and get discounts sent to your phone or email' >";
        }
        ?>
	<div id="banner" <?=$mMarginTopbanner?>>
		<a style="text-decoration:none" rel="facebox" href="?item=mailinglist&rest_id=<?=$objRestaurant->id?>&ajax=1">
                    <?=$mVipDisplay?>
		</a>
	</div>
	<!-- VIP MAIL LIST/ DEFAULT BANNER ENDS HERE -->  
<?php 
}  
elseif ($loggedinuser->valuetec_card_number==0)  
{
?>
	<div id="rewardwrap">
    	<div class="left"> 
			<img src="../images/vip.png" /> 
		</div>
		<div class="right1">
    		<div class="register">
				<a style="text-decoration:none" rel="facebox" href="?item=valutec&ajax=1">
					Register your V.I.P card and earn rewards with every order
				</a>
			</div>
			<div class="membernotice">
				Not a member? you can request a free card during checkout
			</div>
		</div>
	</div>
<? 
} 
elseif ($loggedinuser->valuetec_card_number>0)  
{
?>  
	<div id="rewardwrap">
    	<div class="left">
        	<img src="../images/vip.png" />
        </div>
        <div class="right">
        	<div class="reward">
				YOUR VIP REWARDS
			</div>
            <div class="bar">
            	<div class="barOuter">
            		<div class="barInner" <? if(number_format((($loggedinuser->valuetec_points/$objRestaurant->rewardLevel) *100) ,0)>=5)  {?>  style="width:<?= number_format((($loggedinuser->valuetec_points/$objRestaurant->rewardLevel) *100) ,0); ?>%;max-width:100%;" <? } ?>></div>
                </div>    
                <div class="points"><?=$loggedinuser->valuetec_points?> pts</div>
            </div>
           	<div class="clear"></div>
            <div class="nextreward">
				Next reward: <?=$currency?><?= $objRestaurant->rewardAmount ?> @ <?= $objRestaurant->rewardLevel ?> pts
			</div>
		</div>
        <div class="right_threeColumn">
        	<div class="youhave">YOU HAVE</div>
            <div class="rewardamount"><?=$currency?><?= $loggedinuser->valuetec_reward ?></div>
        </div>
    </div>
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
<?php 
if( isset($_REQUEST['addtolist']) && $_REQUEST['addtolist'] == "yes" )  
{ 
	if ($loggedinuser->valuetec_card_number>0)  
	{
?> 
<div id="mailinglist_msg" class="success">
	Thank you for registering your card, you now have <u style='font-size:16px;'><?= $loggedinuser->valuetec_points ?></u> Point(s)
</div>        
        
<?php 
	} 
  	else 
	{ 
?>
<div id="mailinglist_msg" class="success" style="font-size:18px; font-weight:bold;">
	You are successfully added to our mailing list.
</div>
<?php 
	} 
}
?>
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
	$menus = $objMenu->getEnableMenu();
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
			//$iscurrentMenuAvaible=1;
		} 
		else if($isOpen==1 && $menuid == "") 
		{
			$menuid = $menu->id;
			$class = 'selected_red';
			//$iscurrentMenuAvaible=1;
		} 
		else if($isOpen==0) 
		{
			$menuname=$menu->menu_name;
			//$iscurrentMenuAvaible=0;
			$currentMenuTimings="[". $menu->openTime ." to ". $menu->closeTime ."]";
			$class = 'menu_disabled';
		} 
		elseif($menuid == $menu->id) 
		{
			$class = 'selected_red';
			//$iscurrentMenuAvaible=1;
		}
		
		if($menuid == $menu->id && $isOpen==0)
		{
			$iscurrentMenuAvaible=0;
		}	 
?>
		&nbsp;&nbsp;<a class="<?=$class?>"  title="<?= stripslashes($menu->menu_name) ?>"  menuid="<?=$menu->id ?>" timings='<?= $menu->openTime ." to ". $menu->closeTime  ?>' href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?menuid=<?=$menu->id ?>"><?= stripslashes($menu->menu_name) .
		($isOpen==0 ?"[". $menu->openTime ." to ". $menu->closeTime ."]":"") ?></a>
		&nbsp;&nbsp;
<?php  
		if($i < count($menus)-1) 
			echo "|&nbsp;&nbsp;"; 
	}
?>    
  </div>
  <iframe src= "<?= isset($_SERVER['HTTPS'])?"https":"http" ?>://www.facebook.com/plugins/like.php?href=<? if( $objRestaurant->facebook_link != "") { echo $objRestaurant->facebook_link;} else { echo (isset($_SERVER['HTTPS'])?"https":"http")."%3A%2F%2Fwww.easywayordering.com%2F".$objRestaurant->url."%2F";} ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" allowTransparency="true" style="float:right; width:85px; height:20px; margin-left:15px;"></iframe>
<?php 
if (isset($loggedinuser->id))
{ 
?>
	<span style="float:right; padding-right:10;"><a href="?item=account" <?  if(isset($item) && $item == 'accountdetail') echo "class='selected_red'" ?>>My Account</a>&nbsp;&nbsp;Welcome <? echo $loggedinuser->cust_your_name?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?item=logout">Logout</a></span>
<?php 
} 
else 
{
?>
	<span style="float:right; padding-right:10;"><a href="?item=login">Login/Register</a></span>
<?php 
}
?>
</div>