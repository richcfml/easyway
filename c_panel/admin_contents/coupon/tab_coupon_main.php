<?

if(isset($_GET['item'])) $item = $_GET['item']; else $item = 'main';

if($item == "main") { $admin_subcontent = "admin_contents/coupon/tab_coupon_listing.php"; } 
if($item == "delete") { $admin_subcontent = "admin_contents/coupon/tab_coupon_listing.php"; } 
if($item == "add") { $admin_subcontent = "admin_contents/coupon/tab_coupon_add.php"; } 
if($item == "edit") { $admin_subcontent = "admin_contents/coupon/tab_coupon_edit.php"; }

?>
 
 <div id="BodyContainer">
<? include "includes/resturant_header.php" ?>
<div id="tab_items">
	<ul>
    	<li>
			<a href="?mod=<?=$mod?>&item=main&cid=<?=$mRestaurantIDCP?>">Edit Exisiting Coupons</a>
        </li>|
        <li>
        	<a href="?mod=<?=$mod?>&item=add&cid=<?=$mRestaurantIDCP?>">Add New Coupon</a>
        </li>
     </ul>
</div>

<? include $admin_subcontent;?>
 </div>