<? 
include("../../../includes/config.php");
require("../../includes/SimpleImage.php");
require("../../includes/snapshot.class.php"); 
include("../../admin_includes/function.php");
if($_SESSION['admin_session_user_name']==''){ header("location:login.php");
											} 

$catid = $_GET['catid'];

if($_GET['subitem']) $subitem = $_GET['subitem']; else $subitem= 'menu';
if($subitem == 'menu'){ $include =  "tab_resturant_menu.php";}
if($subitem == 'add'){ $include =  "tab_resturant_menu_add.php";}
if($subitem == 'edit'){ $include =  "tab_resturant_menu_edit.php";}
if($subitem == 'addproduct'){ $include =  "../products/tab_product_add.php";}
if($subitem == 'editproduct'){ $include =  "../products/tab_product_search.php";}
if($subitem == 'editproduct2'){ $include =  "../products/tab_product_edit.php";}
if($subitem == 'addattribute'){ $include =  "../attributes/tab_product_search_add.php";}
if($subitem == 'addattribute2'){ $include =  "../attributes/tab_add_attribute.php";}
if($subitem == 'editattribute'){ $include =  "../attributes/tab_product_search_edit.php";}
if($subitem == 'editattribute2'){ $include =  "../attributes/tab_edit_attribute1.php";}
if($subitem == 'editattribute3'){ $include =  "../attributes/tab_edit_attribute2.php";}

?>
<link href="../../css/adminMain.css" rel="stylesheet" type="text/css" />
<body style="background:none">
<div id="navigation_links">
  <div id="navigation">
    <div id="links" style="border:0px; border-right:#dedede 1px solid;" >&nbsp;</div>
    <div id="links" ><a href="?subitem=menu&catid=<?=$catid?>"class="<?=($subitem=='menu' || $subitem=='edit') ? 'selected' : ''?>">Menus</a></div>
    <br style="clear:both" />
  </div>
</div>

<?php if($subitem == 'menu' || $subitem=='add' || $subitem=='edit' || $subitem=='addproduct' || $subitem=='editproduct2'){?>
<div id="tab_items">
  <ul>
    <li><a href="?subitem=menu&catid=<?=$catid?>"class="<?=($subitem=='menu') ? 'selected_red' : ''?>">Menus Listing</a></li> | 
    <li><a href="?subitem=add&catid=<?=$catid?>" class="<?=$subitem=='add' ? 'selected_red' : ''?>">Add Menu</a></li>
  <?php if($subitem == 'edit'){?>
  		| <li><a href="#" class="selected_red">Edit Menu</a></li>
  <? } else if($subitem == 'addproduct'){?>
  		| <li><a href="#" class="selected_red">Add Menu Item</a></li>
  <? } else if($subitem == 'editproduct2'){ ?>
  		| <li><a href="#" class="selected_red">Edit Menu Item</a></li>
  <? }?>
  </ul>
</div>
<?php } else if($subitem == 'addattribute' || $subitem == 'addattribute2') {?>
<div id="tab_items">
  <ul>
    <li><a href="#" class="selected_red">Add Attribute</a></li>
  </ul>
</div>
<?php } else if($subitem == 'editattribute' || $subitem == 'editattribute2'  || $subitem == 'editattribute3') {?>
<div id="tab_items">
  <ul>
    <li><a href="#" class="selected_red">Edit Attribute</a></li>
  </ul>
</div>
<? }?>
<br>
<? include $include; ?>
<?php mysqli_close($mysqli);?>
</body>
