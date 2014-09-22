<?
if(isset($_GET['item'])) $item = $_GET['item']; else $item = 'main';

if($item == "search") { $admin_subcontent = "admin_contents/customer/tab_customer_search.php"; } 
if($item == "edituser") { $admin_subcontent = "admin_contents/customer/tab_customer_detail.php"; }
if($item == "useredit") { $admin_subcontent = "admin_contents/customer/tab_customer_edit.php"; }
if($item == "deleteuser") { $admin_subcontent = "admin_contents/customer/tab_delete_customer.php"; }
if($item == "customerorder") { $admin_subcontent = "admin_contents/customer/tab_customer_order.php"; } 
if($item == "main") { $admin_subcontent = "admin_contents/customer/tab_customer_all.php"; } 

?>



<div id="BodyContainer">
<? include "includes/resturant_header.php" ?>
<div id="tab_items">
	<ul>
    	<li>
        	<a href="?mod=<?=$mod?>&item=main&cid=<?=$mRestaurantIDCP?>" class="<?=$mod == 'customer' && $item == 'main' ? 'selected_red' : '' ?>" >View / Edit Existing Customer List</a>
        </li>|
    	<li>
        	<a href="?mod=<?=$mod?>&item=search&cid=<?=$mRestaurantIDCP?>" class="<?=$mod == 'customer' && $item == 'search' ? 'selected_red' : '' ?>">Search Existing Customer</a>
       </li>
    </ul>
</div>

<? include $admin_subcontent;?>
 </div>