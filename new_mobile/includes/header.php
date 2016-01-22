<?php
$mIPAddress = "Unknown";
$mSessionID = session_id();
$mRestaurantID = $objRestaurant->id;
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARTDED_FOR'] != '') 
{
    $mIPAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
} 
else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') 
{
    $mIPAddress = $_SERVER['REMOTE_ADDR'];
}

if ((trim($mIPAddress)!="Unknown") && (trim($mIPAddress)!=""))
{
    $mResult = dbAbstract::Execute("SELECT COUNT(*) AS VisitCount FROM mobilevisits WHERE RestaurantID=".$mRestaurantID." AND IPAddress='".$mIPAddress."' AND SessionID='".$mSessionID."'");
    if (dbAbstract::returnRowsCount($mResult)>0)
    {
        $mRow = dbAbstract::returnObject($mResult);
        if (is_numeric($mRow->VisitCount))
        {
            if ($mRow->VisitCount<1) //No Entry for this visit, Record this visit
            {
                $mResult = dbAbstract::Insert("INSERT INTO mobilevisits (RestaurantID, IPAddress, SessionID) VALUES (".$mRestaurantID.", '".$mIPAddress."', '".$mSessionID."')");
            }
        }
    }
}
if ($objRestaurant->status == 1) {
    $objMenu->restaurant_id = $objRestaurant->id;
    $menuid = (isset($_GET['menuid']) ? $_GET['menuid'] : "");
    $menuname = '';
    $menus = $objMenu->getMenusByRestaurantId();
    $isOpen = true;
    $iscurrentMenuAvaible = 1;
    $currentMenuTimings = "";
	?>
    <div class=notification__overlay></div>
        <header class=header style="background-image:url(<?php echo $objRestaurant->bh_banner_image; ?>); background-size: 100% 100%">
         <div class=header__top-bar>
            <div class=header__distributor> <i class=header__menu-icon></i> <a class=header__store-title href='?item=menu' title='<?= $objRestaurant->name ?> Home'><?= $objRestaurant->name ?></a> <a class=header__cart href='/cart' title='Show Cart'><?=($_GET['item']=='grouporderthankyou' || $_GET['item']=='thankyou')? 0:$cart->totalItems()?></a> <a class=header__account href='?item=account' title='Login/Register'>Login/Register</a> </div>
         </div>
           
            <div  class=header__banner style="min-height: 60px"><?php if($objRestaurant->logo):?>  <img alt='Such Restaurant Logo' class=header__logo src='<?= $SiteUrl . 'images/resturant_logos/' . $objRestaurant->logo ?>'> <?php endif;?></div>
         
         <?php
         if ($objRestaurant->announcement != "" && $objRestaurant->announce_status == '1'):
         ?>
         <div class=header__messages-container>
            <p class=header__message><?php echo $objRestaurant->announcement ?></p>
         </div>
         <?php endif;?>
         <div class=header__store-info-container itemscope='' itemtype='http://schema.org/LocalBusiness'>
            <h1 class=header__store-name itemprop=name><?=$objRestaurant->name?></h1>
            <p class=header__store-info itemprop=address itemtype='http://schema.org/PostalAddress' itemscope=''> 
              <b><?php if($objRestaurant->isOpenHour){ echo 'Open</b>'; }else{echo 'Close</b>';} ?></b> <br> 
              <span itemprop=streetAddress><?= $objRestaurant->rest_address ?></span>, 
              <span itemprop=addressLocality><?= $objRestaurant->rest_city ?></span>, 
              <abbr itemprop=addressRegion title='<?php echo $objRestaurant->rest_state ?>'><?php echo $objRestaurant->rest_state ?></abbr> 
            </p>
            
            <a class=header__store-phone href='tel: <?= $objRestaurant->phone ?>' title=store-phone>
              <span itemprop=telephone><?= $objRestaurant->phone ?></span> 
            </a>
         </div>
         
      </header>
        
    <nav class="category-nav" style=''>
        <ul class="category-nav__tabs">
            <?php
            for ($i = 0; $i < count($menus); $i++) {
                $class = '';
                $menu = $menus[$i];
                $isOpen = $menu->isMenuOpen();

		   if($isOpen==1 && $i == 0 && $menuid == "") {
			  $menuid = $menu->id;
			  $class = 'active';
			  //$iscurrentMenuAvaible=1;
		   }else if($isOpen==1 && $menuid == "") {
			   $menuid = $menu->id;
			   $class = 'active';
			   //$iscurrentMenuAvaible=1;
		   }else if($isOpen==0){
			   $menuname=$menu->menu_name;
			   //$iscurrentMenuAvaible=0;
			   $currentMenuTimings="[". $menu->openTime ." to ". $menu->closeTime ."]";
			   $class = 'menu_disabled';
		   }elseif($menuid == $menu->id) {
			   $class = 'active';
			   //$iscurrentMenuAvaible=1;
		   }
		   if($menuid == $menu->id && $isOpen==0){
			   $iscurrentMenuAvaible=0;
		   }
        ?>
        <li class="category-nav__tab <?= $class;?>"> 
          <a class="category-nav__tab-link" title="<?= stripslashes($menu->menu_name) ?>" menuid="<?=$menu->id ?>" timings="<?= $menu->openTime ." to ". $menu->closeTime  ?>" href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?menuid=<?=$menu->id ?>">
		  
		  <?= stripslashes($menu->menu_name) .($isOpen==0 ?"[". $menu->openTime ." to ". $menu->closeTime ."]":"") ?>
          
          </a> 
        </li>
         <?php } ?>
      </ul>
	</nav>

<?php 
}
?>

                <div class="notification" id="edit_item"><div  class="notification__box" id="dvLM" name="dvLM">
                        <header class="notification__box-header center-text"> <a class="notification__box-action" href="#">X</a>
                            <h3 class="notification__box-title"></h3>
                        </header>
                        <div id="data">
                            <form action="" method="post" id="frmPrd" name="frmPrd">
                                
                                <div style="">
                                    
                                    <div style="float: left" id="item_detail_wrapper">
                                        <div style=""> 
                                            <strong style="padding-top:3px;" id="item_title"></strong>
                                            <br>
                                            <span style="font-size:12px;" id="item_des"></span> 
                                        </div>
                                        <div style="clear:both"></div>
                                        <div style="margin-top: 6px;"> 
                                            <strong>Item Price:</strong> <span style="font-size:12px; color:#ff0000;" id="retail_price"></span>
                                        </div>
                                    </div>
                                    <br>
                                </div>

                                <div style="clear: both;"></div>

                                <div id="updateMessage" style="margin-bottom: 10px; color:#9C0F17; margin-top:10px;display:none;font-size:14px;">Please make a selection to continue.</div>

                                <div id='attributes_wrapper'></div>
                                <div id="association_wrapper"></div>
                                <hr width="96%" size="1" class="hr">
                                <div>
                                    <label><strong>Quantity:</strong></label>
                                    <script type="text/javascript" language="javascript">
                                        $(document).ready(function ()
                                        {
                                            $(".qnty").keydown(function (e)
                                            {
                                                if ($.inArray(e.keyCode, [8, 9, 27, 13]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39))
                                                {
                                                    return;
                                                }

                                                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105))
                                                {
                                                    e.preventDefault();
                                                }
                                            });
                                        });
                                    </script>
                                    <input name="quantity" type="text" id="quantity" tabindex="1" title="Quantity" maxlength="3" value="1" size="5"  >
                                    
                                </div>
                                
                                <div class="attribute">
                                    <label>List any special requests or notes</label>
                                    <br>
                                    <textarea name="requestnote" id="requestnote" tabindex="3" cols="35" rows="4"></textarea>
                                </div>
                                <br>
                                <div class="attribute">
                                    <div style=""><input type="submit" name="addtocart" id="addtocart" value="Add to Cart" ></div>
                                    <div id="updateMessage1" style="padding:3px; color:#9C0F17; margin-top:10px;display:none;font-size:14px;">Please make a selection to continue.</div>
                                </div>

                                <div style="height:5px;">&nbsp;</div>


                                <input type="hidden" id="product_id_field" name="product_id_field" value="">
                                <input type="hidden" id="product_sale_price" name="product_sale_price" value="">
                                <input type="hidden" id="HasAssociates" name="has_associates" value="">
                                <input type="hidden" id="HasAttributes" name="has_attributes" value="">
                                <input type="hidden" name="totalattributes" id="totalAttributes" value="">
                                <input type="hidden" id="cartItemIndex" name="cartItemIndex" value="-1">
                            </form>
                        </div>
                    </div>
                </div>  
