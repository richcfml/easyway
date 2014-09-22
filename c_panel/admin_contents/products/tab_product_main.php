<?
if($_GET['item']) $item = $_GET['item']; else $item = 'main';

if($item == "main") { $admin_subcontent = "admin_contents/products/tab_product_search.php"; } 
if($item == "add") { $admin_subcontent = "admin_contents/products/tab_product_add.php"; } 
if($item == "edit") { $admin_subcontent = "admin_contents/products/tab_product_edit.php"; } 

?>


<div id="SubNav"><a href="javascript:void(0)">Products Listing (50)</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="?mod=<?=$mod?>&item=add">Add New product</a></div>
<div id="BodyContainer">
<? include $admin_subcontent;?>
 </div>