<? if( isset($_REQUEST['addtolist']) && $_REQUEST['addtolist'] == "yes" )  { 
 		if ($loggedinuser->valuetec_card_number>0)  {?> 
        <div id="mailinglist_msg" class="success">Thank you for registering your card, you now have <u style='font-size:16px;'><?= $loggedinuser->valuetec_points ?></u> Point(s)</div>        
        
         <? } else { ?>
<div id="mailinglist_msg" class="success" style="font-size:18px; font-weight:bold;">You are successfully added to our mailing list.</div>
<? } }?>
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
		$menus = $objMenu->getmenu(1);
	 	$isOpen=true;
		$iscurrentMenuAvaible=1;
		$currentMenuTimings="";
		for($i = 0; $i < count($menus); $i++) {	
			$class='';
			$menu=$menus[$i];
	   	    $isOpen=$menu->isAvailable();
			
			if($isOpen==1 && $i == 0 && $menuid == "") {
				$menuid = $menu->id;
				$class = 'selected_red';
				 //$iscurrentMenuAvaible=1;
			} else 	if($isOpen==1 && $menuid == "") {
				
				$menuid = $menu->id;
			 	$class = 'selected_red';
				//$iscurrentMenuAvaible=1;
				
		 	}	else if($isOpen==0){
				
				 $menuname=$menu->menu_name;
				 //$iscurrentMenuAvaible=0;
				 $currentMenuTimings="[". $menu->openTime ." to ". $menu->closeTime ."]";
				 $class = 'menu_disabled';
				 
			 }elseif($menuid == $menu->id) {
				$class = 'selected_red';
				//$iscurrentMenuAvaible=1;
			
			 }
                         if($menuid == $menu->id && $isOpen==0){
                                $iscurrentMenuAvaible=0;
                         }
	 
?>&nbsp;&nbsp;<a class="<?=$class?>"  title="<?= stripslashes($menu->menu_name) ?>"  menuid="<?=$menu->id ?>" timings='<?= $menu->openTime ." to ". $menu->closeTime  ?>' href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?menuid=<?=$menu->id ?>&ifrm=load_resturant"><?= stripslashes($menu->menu_name) .
 ($isOpen==0 ?"[". $menu->openTime ." to ". $menu->closeTime ."]":"") ?></a>
 &nbsp;&nbsp;<?  if($i < count($menus)-1) echo "|&nbsp;&nbsp;"; }?>    
  </div>
  <? if (isset($loggedinuser->id)){ ?>
  <span style="float:right; padding-right:10;" class="generaltext2"><a  href="?item=account&ifrm=account" <?  if(isset($item) && $item == 'accountdetail') echo "class='selected_red'" ?>>My Account</a>&nbsp;&nbsp;Welcome <? echo $loggedinuser->cust_your_name?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?item=logout&ifrm=logout">Logout</a></span>
  <? } else {?>
  <span style="float:right; padding-right:10;"><a href="<?=$SiteUrl?><?=$objRestaurant->url?>/?item=login&ifrm=login">Login/Register</a></span>
  <? }?>
</div>