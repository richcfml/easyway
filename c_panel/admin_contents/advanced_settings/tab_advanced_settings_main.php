<?
if($mod == 'advanced_settings') 
		$admin_subcontent = "admin_contents/advanced_settings/tab_set_advanced_settings.php";
 else if($mod == 'coverstation_settings') 
	 $admin_subcontent = "admin_contents/advanced_settings/coverstation_settings.php";
  else if($mod == 'valutec_loyalty') 
	 $admin_subcontent = "admin_contents/advanced_settings/valutec_loyalty.php";
?>

<div id="BodyContainer">
<? include "includes/resturant_header.php" ?>
 
<?  include $admin_subcontent;?>

 </div>
 
 
	