<? 
if($_SESSION['admin_session_user_name']==''){ header("location:login.php");	} 


if(isset($_GET['item'])) $item = $_GET['item']; else $item= "new_menu";

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
else if($item == 'addproduct_new'){ $include =  "admin_contents/products/tab_product_add_new.php";}
else if($item == 'updateproduct_new'){ $include =  "admin_contents/products/tab_product_update_new.php";}
else { $include =  "tab_resturant_menus_new.php";}
if($ajax==0) {
?>

<div id="BodyContainer" style="background-color: #D7D7D7;background-size: 1188px 1069px !important;"  class="BodyContainer">
<? include "includes/new_resturant_header.php";


 $rest_id = $Objrestaurant->id;

?>
<br>
<? include $include; ?>
</div>
<?  } else {  include $include; }  ?>


