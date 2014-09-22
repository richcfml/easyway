<nav class="nav_wrapper">
			<div align="center" class="nav_menu">
				<ul>
                
					<?php
					  if(isset($loggedinuser->arrFavorites) && count($loggedinuser->arrFavorites)>0) {
						 ?>
                         <a class="<?=$class?>" href="<?=$client_path?><?= $objRestaurant->url ."/"?>?menuid=0&mobile=1">Favorites</a>
                         
                         <?
						    }
					$objMenu->restaurant_id = $objRestaurant->id;
						
						$menuid = (isset($_GET['menuid']) ? $_GET['menuid']:"");
						 
						$menus = $objMenu->getmenu(1);
						for($i = 0; $i < count($menus); $i++) {
							$class='';
							$menu=$menus[$i];
						
						 	if($i == 0 && $menuid == "") {
								$menuid = $menu->id;
								$class = 'current-menu';
								
							} elseif($menuid == $menu->id) {
								$class = 'current-menu';
							}
					?>
							<li>
								<a class="<?=$class?>" href="<?=$client_path?><?= $objRestaurant->url ."/"?>?menuid=<?=$menu->id ?>"><?= stripslashes($menu->menu_name) ?></a>
							</li>
							
					<?	} ?>
				</ul>

			</div>
			<!-- login and cart-->
			<div align="center" class="check_out_container">
 				<a href="<?=$client_path?><?= $objRestaurant->url ."/"?>?item=cart" class="shopping-cart-basket">
					<span class="shopping-cart-header-count"><?= $cart->totalItems(); ?></span>
					<span class="arrow-down-basket">arrow-down</span>
				</a>
                <? 
				if(is_numeric($loggedinuser->id)) {
				?>
                <a href="<?=$client_path?><?= $objRestaurant->url ."/"?>?item=account" class="login-logout-header">
					<span>Welcome <?= $loggedinuser->cust_your_name ?></span>
				</a>
                 
                
                <? } else { ?>
				<a href="<?=$client_path?><?= $objRestaurant->url ."/"?>?item=login" class="login-logout-header">
					<span>Login</span>
				</a>
                <? } ?>
			</div>
		</nav>
	  
      <? include $mobile_root_path. "views/restaurant/sub_menu.php" ?>