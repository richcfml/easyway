<? 
if($_SESSION['admin_session_user_name']==''){ header("location:login.php");	} 


if(isset($_GET['item'])) $item = $_GET['item']; else $item= 'menu';
if($item == 'menu_actions'){ $include =  "ajax_actions.php";}
else if($item == 'menu'){ $include =  "tab_resturant_menu.php";}
else if($item == 'addmenu'){ $include =  "tab_add_menu.php";}
else if($item == 'editmenu'){ $include =  "tab_edit_menu.php";}
else if($item == 'menustatus'){ $include =  "tab_resturant_menu.php";}
else if($item == 'add'){ $include =  "tab_resturant_menu_add.php";}
else if($item == 'edit'){ $include =  "tab_resturant_menu_edit.php";}
else if($item == 'submenustatus'){ $include =  "tab_resturant_menu.php";}
else if($item == 'assoc'){ $include =  "add_assoc.php";}
elseif($item == 'addproduct'){ $include =  "admin_contents/products/tab_product_add.php";}
else if($item == 'editproduct'){ $include =  "admin_contents/products/tab_product_search.php";}
else if($item == 'editproduct2'){ $include =  "admin_contents/products/tab_product_edit.php";}
else if($item == 'productstatus'){ $include =  "tab_resturant_menu.php";}

//else if($item == 'addattribute'){ $include =  "admin_contents/attributes/tab_product_search_add.php";}
else if($item == 'addattribute'){ $include =  "admin_contents/attributes/tab_add_attribute.php";}

else if($item == 'editattribute'){ $include =  "admin_contents/attributes/tab_edit_attribute.php";}
else if($item == 'hours'){ $include =  "admin_contents/menus/tab_menu_businesshours.php";}
else { $include =  "tab_resturant_menu.php";}
if($ajax==0) {
?>

<div id="BodyContainer">
<? include "includes/resturant_header.php";


  if($item == 'menu' || $item=='addmenu' || $item=='editmenu' || $item=='add' || $item=='edit' || $item=='addproduct' ||  $item == 'addattribute' || $item == 'addattribute2' || $item == 'editattribute'){?>
<div id="tab_items">
  <ul>
    <li><a href="?mod=menus&catid=<?=$mRestaurantIDCP?>"class="<?=($item=='menu') ? 'selected_red' : ''?>">Sub Menus Listing</a></li> | 
    <li><a href="?mod=menus&item=addmenu&restid=<?=$mRestaurantIDCP?>" class="<?=$item=='addmenu' ? 'selected_red' : ''?>">Add Menu</a></li> |
    <li><a href="?mod=menus&item=add&catid=<?=$mRestaurantIDCP?>" class="<?=$item=='add' ? 'selected_red' : ''?>">Add Sub Menu</a></li> 
    
  <?php if($item == 'editmenu'){?>
  		| <li><a href="#" class="selected_red">Edit Menu</a></li>
  <?php }else if($item == 'edit'){?>
  		| <li><a href="#" class="selected_red">Edit Sub Menu</a></li>
  <? } else if($item == 'addproduct'){?>
  		| <li><a href="#" class="selected_red">Add Sub Menu Item</a></li>
  <? } else if($item == 'editproduct2'){ ?>
  		| <li><a href="#" class="selected_red">Edit Sub Menu Item</a></li>
  <? } else if($item == 'addattribute') {?>
  		| <li><a href="#" class="selected_red">Add Attribute</a></li>
  <? } else if($item == 'editattribute') {?>
  		| <li><a href="#" class="selected_red">Edit Attribute</a></li>
  <? }?>
  </ul>
</div>
<?php 
}
?>
<br>
<? include $include; ?>
</div>
<?  } else {  include $include; }  ?>


