<div id="logo_area">
    <div id="logo"><img src="images/man_img.png" width="57" height="74" /></div>
    <div id="logo_text">ADMINISTRATION PANEL</div>
    <? 	if( $_SESSION['admin_type'] == 'admin' ) { 
			$admin_type = "Admin";
		} else if ( $_SESSION['admin_type'] == 'reseller' ) {
			$admin_type = "Reseller";
		} else if ( $_SESSION['admin_type'] == 'store owner' ) {
			$admin_type = "Store Owner";
		}
		$admin_name_sql = "SELECT firstname, lastname FROM users WHERE id = '".$_SESSION['owner_id']."'";
		$admin_name_qry = dbAbstract::Execute($admin_name_sql,1);
		$admin_name_rs	= dbAbstract::returnArray($admin_name_qry,1);
		 
  		$name_str = $admin_type.", ".$admin_name_rs['firstname']." ".$admin_name_rs['lastname'];
		
	?>
    <? if(  $_SESSION['admin_type'] == 'admin' ||  $_SESSION['admin_type'] == 'store owner' ) {?>
    	 <div id="login_text"><strong>Welcome</strong>&nbsp;&nbsp;<?=$name_str?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="logout.php">Log out</a></div>
	<? } else {?>
    <div id="login_text"><strong>Welcome</strong><a href="<?=$AdminSiteUrl?>?mod=resellers&item=profile"> <?=$name_str?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="logout.php">Log out</a></div>
    <? }?>
    <br style="clear:both" />
</div>