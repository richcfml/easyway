<nav class="nav_wrapper">
			<div align="center" class="nav_menu">
				<ul>
                
					<?php
					  if(isset($loggedinuser->arrFavorites) && count($loggedinuser->arrFavorites)>0) {
						 ?>
                         <a class="<?=$class?>" href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?menuid=0&mobile=1">Favorites</a>
                         
                         <?
						    }
					$objMenu->restaurant_id = $objRestaurant->id;
						
						$menuid = (isset($_GET['menuid']) ? $_GET['menuid']:"");
						 $iscurrentMenuAvaible=1;
						$menus = $objMenu->getEnableMenu();
						for($i = 0; $i < count($menus); $i++) {
							$class='';
							$menu=$menus[$i];
                                                        $isOpen=$menu->isMenuOpen();
                                                        //    echo $isOpen;exit;
						 	if($isOpen==1 && $i == 0 && $menuid == "") {
                                    $menuid = $menu->id;
                                    $class = 'current-menu';
                                     //$iscurrentMenuAvaible=1;
                            } else 	if($isOpen==1 && $menuid == "") {

                                    $menuid = $menu->id;
                                    $class = 'current-menu';
                                    //$iscurrentMenuAvaible=1;

                            } else if($isOpen==0) {

                                     $menuname=$menu->menu_name;
                                     //$iscurrentMenuAvaible=0;
                                     $currentMenuTimings="[". $menu->openTime ." to ". $menu->closeTime ."]";
                                    

                                     $class = 'menu_disabled';

                            } elseif($menuid == $menu->id) {
                                    $class = 'current-menu';
                                    //$iscurrentMenuAvaible=1;

                            }
                            if($menuid == $menu->id && $isOpen==0){

                                $iscurrentMenuAvaible=0;
                            }
					?>
			
                         <li>
								<a class="<?=$class?>"  title="<?= stripslashes($menu->menu_name) ?>"  menuid="<?=$menu->id ?>" timings='<?= $menu->openTime ." to ". $menu->closeTime  ?>' href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?menuid=<?=$menu->id ?>"><?= stripslashes($menu->menu_name) .
 ($isOpen==0 ?"[". $menu->openTime ." to ". $menu->closeTime ."]":"") ?></a>
                                                            
 
							</li>
							
					<?

                            } ?>
				</ul>

			</div>
			<!-- login and cart-->
			<div align="center" class="check_out_container">
 				<a href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?item=cart" class="shopping-cart-basket">
					<span class="shopping-cart-header-count"><?= $cart->totalItems(); ?></span>
					<span class="arrow-down-basket">arrow-down</span>
				</a>
                <? 
				if(is_numeric($loggedinuser->id)) {
				?>
                <a href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?item=account" class="login-logout-header">
					<span>Welcome <?= $loggedinuser->cust_your_name ?></span>
				</a>
                 
                
                <? } else { ?>
				<a href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?item=login" class="login-logout-header">
					<span>Login</span>
				</a>
                <? } ?>
			</div>
		</nav>
<? include $mobile_root_path. "views/valutec/tab_register_card_popup.php"; ?>
	   <?

if($objRestaurant->announcement!= "" && $objRestaurant->announce_status == '1') {?>
<div style="background-color:#EAEAEC;  margin-bottom:10px; border:1px solid #A4A4A4; color:#F00; font-size:12px;">
<div style="float:left; padding:5px 5px;"><img src="<?=$SiteUrl?>images/dialog_warning.png" width="30"  /></div>
<div style="padding:15px 5px 0px 5px;"><?=$objRestaurant->announcement?></div>

<br style="clear:both"  />
</div>
<? } ?>
      <? include $mobile_root_path. "views/restaurant/sub_menu.php" ?>