<?
if(isset($_GET['item'])) $item = $_GET['item']; else $item = 'main';

if($item == "main") { $admin_subcontent = "admin_contents/resturants/tab_resturant_listing.php"; } 
if($item == "cus_list") { $admin_subcontent = "admin_contents/resturants/tab_resturant_cus_listing.php"; } 
if($item == "add") { $admin_subcontent = "admin_contents/resturants/tab_resturant_add.php"; } 
if($item == "edit") { $admin_subcontent = "admin_contents/resturants/tab_resturant_edit.php"; } 
if($item == "copy") { $admin_subcontent = "admin_contents/resturants/tab_resturant_copy.php"; } 

if($item == "restedit") { $admin_subcontent = "admin_contents/resturants/tab_edit_restaurant_radius.php"; }

if($item == "menuadd") { $admin_subcontent = "admin_contents/resturants/tab_menu_add.php"; } 
if($item == "menuscrh") { $admin_subcontent = "admin_contents/resturants/tab_menu_search.php"; } 
if($item == "menuedit") { $admin_subcontent = "admin_contents/resturants/tab_menu_edit2.php"; }
if($item == "reststatus") { $admin_subcontent = "admin_contents/resturants/tab_resturant_listing.php"; } 
if($item == "delivery_zone") { $admin_subcontent = "admin_contents/resturants/tab_restaurant_delivery_zones.php"; }   
if($ajax==0) {
?>

<div id="tab_items">
  <ul>
    <li><a href="?mod=<?=$mod?>&amp;item=main" class="<?=$mod=='resturant' && $item=='main' ? 'selected_red' : ''?>">Restaurants Listing</a></li>
    
    <? if($_SESSION['admin_type'] == 'admin' || $_SESSION['admin_type'] == 'reseller') {?>
    |
    <li><a href="?mod=<?=$mod?>&amp;item=add" class="<?=$mod=='resturant' && $item=='add' ? 'selected_red' : ''?>">Add New Restaurant</a></li>
    
      |
    <li><a href="?mod=<?=$mod?>&amp;item=copy" class="<?=$mod=='resturant' && $item=='copy' ? 'selected_red' : ''?>">Copy Restaurant</a></li>
    
    
    <? }?>
  </ul>
</div>


<? } ?>
<div id="contents">  
  <? include $admin_subcontent;?>
</div>
<!--End body Div-->