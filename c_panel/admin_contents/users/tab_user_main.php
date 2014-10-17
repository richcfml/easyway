<?
if(isset($_GET['item'])) $item = $_GET['item']; else $item = 'main';

if($item == "main") { $admin_subcontent = "admin_contents/users/tab_user_all.php"; } 
if($item == "add") { $admin_subcontent = "admin_contents/users/tab_user_add.php"; }
if($item == "useredit") { $admin_subcontent = "admin_contents/users/tab_user_edit.php"; }
if($item == "userdell") { $admin_subcontent = "admin_contents/users/tab_user_all.php"; }
if($item == "userstatus") { $admin_subcontent = "admin_contents/users/tab_user_all.php"; }
?>

<div id="tab_items">
	<ul>
		<li><a href="?mod=<?=$mod?>&amp;item=main" class="<?=$mod=='clients' && $item=='main' ? 'selected_red' : ''?>"  >View / Edit Existing Clients List</a>
   		</li>|
        <li><a href="?mod=<?=$mod?>&amp;item=add" class="<?=$mod=='clients' && $item=='add' ? 'selected_red' : ''?>">Add New Client</a>
        </li>
    </ul>
</div>

<div id="contents">
<? 
include $admin_subcontent;?>
 </div>