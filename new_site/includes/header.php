<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
	<meta charset="utf-8">
	<title><?=$objRestaurant->name;?></title>
	<meta http-equiv="Cache-control" content="public">
	<meta http-equiv="Cache-control" Pragma="public">
   	<meta NAME="DESCRIPTION" CONTENT="<?=trim(stripslashes($objRestaurant->meta_description));?>">
	<meta NAME="KEYWORDS" CONTENT="<?=trim(stripslashes($objRestaurant->meta_keywords));?>">
    <link href="<?php echo $css_path; ?>index_style_new_1.css" type="text/css" rel="stylesheet" media="screen">
	<link href="<?php echo $css_path; ?>facebox.css" type="text/css" rel="stylesheet" media="screen">
	<link href="<?php echo $css_path; ?>prettyPhoto.css" type="text/css" rel="stylesheet" media="screen">
	
	<script src="<?php echo $js_root; ?>jquery.min.js" type="text/javascript"></script>
	<script src="<?php echo $js_root; ?>jquery-ui.min.js" type="text/javascript"></script>
	<script src="<?php echo $js_root; ?>jquery.validate.js" type="text/javascript"></script>
	<script src="<?php echo $js_root; ?>jquery.prettyPhoto.js" type="text/javascript"></script>
	<script src="<?php echo $js_root; ?>facebox.js" type="text/javascript"></script>
	
</head>
<body>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function($) 
	{
		$('a[rel*=facebox]').facebox();
		
		$(document).bind('afterReveal.facebox', function() 
		{
    		var windowHeight = $(window).height();
		    var faceboxHeight = $('#facebox').height();
		    if(faceboxHeight < windowHeight) 
			{
		        $('#facebox').css('top', (Math.floor((windowHeight - faceboxHeight) / 2) + $(window).scrollTop()) );
    		}
		});
	});
</script>

<div id="maincontainer"> 
<?php if(empty($_SESSION["restaurant_deisgn_settings_id"])) 
{ 
?>
	<div id="header" style="background:url(<?php echo $objRestaurant->header_image?>)"></div>
<?php 
} 
?>



<?php   include $site_root_path. "views/restaurant/home_nav.php" ?>