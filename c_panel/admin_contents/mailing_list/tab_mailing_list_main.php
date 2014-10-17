<?
if(isset($_GET['item'])) $item = $_GET['item']; else $item = 'main';

if($item == "main") { $admin_subcontent = "admin_contents/mailing_list/tab_mailing_list.php"; } 
if($item == "mailadd") { $admin_subcontent = "admin_contents/mailing_list/tab_mail_add.php"; } 
if($item == "mailedit") { $admin_subcontent = "admin_contents/mailing_list/tab_mail_edit.php"; } 
if($item == "deletemail") { $admin_subcontent = "admin_contents/mailing_list/tab_mail_delete.php"; } 
?>

<div id="BodyContainer">
<? include "includes/resturant_header.php" ?>
<div id="tab_items">
	<ul>
    	<li>
        	<a href="?mod=<?=$mod?>&item=main&cid=<?=$mRestaurantIDCP?>" class="<?=$mod == 'mailing_list' && $item == 'main' ? 'selected_red' : '' ?>" >View / Edit Mailing List</a>
        </li>|
    	<li>
        	<a href="?mod=<?=$mod?>&item=mailadd&cid=<?=$mRestaurantIDCP?>" class="<?=$mod == 'mailing_list' && $item == 'mailadd' ? 'selected_red' : '' ?>">Add To Mailing List</a>
       </li>
    </ul>
</div>
<? include $admin_subcontent;?>
 </div>