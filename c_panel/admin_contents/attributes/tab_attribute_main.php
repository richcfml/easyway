<?
if($_GET['item']) $item = $_GET['item']; else $item = 'main';

if($item == "main") { $admin_subcontent = "admin_contents/attributes/tab_product_search1.php"; } 
if($item == "editatt") { $admin_subcontent = "admin_contents/attributes/tab_edit_attribute1.php"; }
if($item == "editatt1") { $admin_subcontent = "admin_contents/attributes/tab_edit_attribute2.php"; }
 
if($item == "add") { $admin_subcontent = "admin_contents/attributes/tab_product_search2.php"; } 
if($item == "addatt") { $admin_subcontent = "admin_contents/attributes/tab_add_attribute.php"; }

?>


<div id="SubNav"><a href="?mod=<?=$mod?>">Edit An Existing Menu Item Attribute</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="?mod=<?=$mod?>&item=add">Add A New Menu Item Attribute</a></div>
<div id="BodyContainer">
<? include $admin_subcontent;?>
 </div>