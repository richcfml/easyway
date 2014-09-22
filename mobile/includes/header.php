<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8">
    <title><?=$objRestaurant->name;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta NAME="DESCRIPTION" CONTENT="<?=trim(stripslashes($objRestaurant->meta_description));?>">
    <meta NAME="KEYWORDS" CONTENT="<?=trim(stripslashes($objRestaurant->meta_keywords));?>">

    <link href="<?php echo $mobile_css_path; ?>style.v.3.css?t=<?= time() ?>" type="text/css" rel="stylesheet" media="screen">
    <link href="<?php echo $mobile_css_path; ?>model.css" type="text/css" rel="stylesheet" media="screen">
    <!-- added by Asher Ali -->
    <link href="<? echo $css_root_path;  ?>facebox.css" media="screen" rel="stylesheet" type="text/css"/>

    <!--  JS FILES -->
    <script src="<?php echo $mobile_js_path; ?>jquery.min.js"></script>
    <script src="<?php echo $mobile_js_path; ?>jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?php echo $mobile_js_path; ?>jquery.cycle.all.js" type="text/javascript"></script>
    <script src="<?php echo $js_root; ?>jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo $mobile_js_path; ?>main.v.1.js?t=<?= time() ?>" type="text/javascript"></script>
    <!-- added by Asher Ali -->
    <script src="<? echo $mobile_js_path;  ?>facebox.js" type="text/javascript"></script>
</head>
<body>
<? if($objRestaurant->status==1){ ?>
	<header class="container">
		<!-- Top header-->
		<div class="top_header">
			<img src="<?php echo $objRestaurant->header_image; ?>" alt="<?php echo $objRestaurant->name; ?>">
		</div>
		 <? if($objRestaurant->isOpenHour) include $mobile_root_path. "views/restaurant/home_nav.php" ?>
		<div class="clear"></div>
	</header>
    <? } ?>