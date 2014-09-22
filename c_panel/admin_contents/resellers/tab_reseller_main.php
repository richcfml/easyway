<?
if(isset($_GET['item'])) $item = $_GET['item']; else $item = 'main';
 
if($item == "main") { $admin_subcontent = "admin_contents/resellers/tab_reseller_all.php"; } 
else if($item == "add") { $admin_subcontent = "admin_contents/resellers/tab_reseller_add.php"; }
else if($item == "edit") { $admin_subcontent = "admin_contents/resellers/tab_reseller_edit.php"; }
else if($item == "profile") { $admin_subcontent = "admin_contents/resellers/tab_reseller_profile.php"; }
else if($item == "resellerprofile") { $admin_subcontent = "admin_contents/resellers/tab_reseller_edit_profile.php"; }
else if($item == "liceselisting") { $admin_subcontent = "admin_contents/resellers/tab_license_listing.php"; }

if( $_SESSION['admin_type'] == 'admin') {
?>
<div id="tab_items">
	<ul>
		<li><a href="?mod=<?=$mod?>&amp;item=main" class="<?=$mod=='resellers' && $item=='main' ? 'selected_red' : ''?>"  >View / Edit Existing Resellers List</a>
   		</li>|
        <li><a href="?mod=<?=$mod?>&amp;item=add" class="<?=$mod=='resellers' && $item=='add' ? 'selected_red' : ''?>">Add New Resellers</a>
        </li>
    </ul>
</div>
<? }?>
<div id="contents">
<? 
include $admin_subcontent;?>
 </div>