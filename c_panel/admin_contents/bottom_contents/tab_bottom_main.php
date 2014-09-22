<?
if($_GET['item']) $item = $_GET['item']; else $item = 'main';

if($item == "main") { $admin_subcontent = "admin_contents/bottom_contents/tab_edit_faq.php"; } 
//if($item == "add") { $admin_subcontent = "admin_contents/products/tab_product_add.php"; } 
//if($item == "edit") { $admin_subcontent = "admin_contents/products/tab_product_edit.php"; } 

?>


<div id="SubNav"><a href="./?mod=contents&t=1">FAQ</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="./?mod=contents&t=2">About Us</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="./?mod=contents&t=3">Contact Us</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="./?mod=contents&t=4">Privacy Policy</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="./?mod=contents&t=5">Delivery Info</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="./?mod=contents&t=6">Corporate & Catering</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="./?mod=contents&t=7">Customer Care</a>
<div id="BodyContainer">
<? include $admin_subcontent;?>
 </div>