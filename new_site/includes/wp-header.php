<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
	<meta charset="utf-8">
	<title><?=$objRestaurant->name;?></title>
   	<meta NAME="DESCRIPTION" CONTENT="<?=trim(stripslashes($objRestaurant->meta_description));?>">
	<meta NAME="KEYWORDS" CONTENT="<?=trim(stripslashes($objRestaurant->meta_keywords));?>">

    <link href="<?php echo $css_path; ?>index_style_new_wp_api.css" type="text/css" rel="stylesheet" media="screen">
	<script src="<?php echo $js_root; ?>jquery.min.js"></script>
    <script src="<?php echo $js_root; ?>jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?php echo $js_root; ?>jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo $js_root; ?>jquery.prettyPhoto.js" type="text/javascript"></script>

    <link href="<? echo $css_path;  ?>facebox.css" media="screen" rel="stylesheet" type="text/css"/>
    <link href="<? echo $css_path;  ?>prettyPhoto.css" media="screen" rel="stylesheet" type="text/css"/>
	<script src="<? echo $js_root;  ?>facebox.js" type="text/javascript"></script>
	<script src="<? echo $js_root;  ?>crossdomain.js" type="text/javascript"></script>
	
    <script language="javascript">
        jQuery(document).ready(function($) {
            $('a[rel*=facebox]').facebox();
        });

        function showDiv(divname){
            $(divname).show();
        }
        function hideDiv(divname){
            $(divname).hide();
        }
    </script>
<?
//echo "<pre>";print_r($_SESSION);echo "</pre>";
	$options = array(
		"active_menu_link_color" => "#CC0000",
		"general_text_color" => "#000000",
		"secondary_text_color" => "#000000",

		"general_font_size" => "12px",
		"inactive_menu_link_color" => "#333333",
		"menu_bg_color" => "#F4F4F4",
		"sub_menu_headings_color" => "#585858",
		"sub_menu_headings_background_color" => "#F4F4F4",
		"sub_menu_headings_background_image" => "",
		"sub_menu_descriptions_color" => "#585858",
		"titles_font_family" => "arial",
		"titles_font_size" => "14px",
		"your_order_summary_color" => "#5F5F5F",
		"your_order_summary_font_size" => "14px",
		"items_and_prices_font_size" => "14px",
		"items_title_color" => "#000000",
		"items_price_color" => "#000000",
		"items_description_color" => "#000000",
		"min_width_of_the_container" => "0px",
		"cell_bg_color" => "#fff",
		"cell_bg_image" => "",
		"cell_bg_image_strech_or_tile" => "1",
		"order_online_button_image" => "",
		"appearence" => "1",
		"restaurant_slug" => "",
		"show_loyalty_box_about_the_cart" => "true",
		"show_item_pictures_and_description" => "1",
		"iframe_height" => "500px",
		"iframe_height_infinite" => "1",
		"cell_border_color" => "#e4e4e4",
		"cell_border_thickness" => "1",
		"vip_progress_bar_color" => "#00CCFF",
		"easyway_url" => ""
	);
	if(!empty($settings) and $settings != false) {
		foreach($settings as $key=>$val) {
			if(array_key_exists($key, $options)) {
				if($val != NULL)
					$options[$key] = $val;
			}
		}
	}
	//print_r($options);
?>
<style type="text/css">

body, .radio_bttn, #body #items_bg, .second_body_heading, #body #body_heading, .text_12px  {
	color: <? echo $options["general_text_color"]; ?> !important;
	font-size: <? echo $options["general_font_size"]; ?> !important;
}
.generaltext2,.second_body_text ,.second_body_text a,.second_body_text a:hover,.account_detail,.account_detail a,.account_detail a:hover,.vipmessage{
	color: <? echo $options["secondary_text_color"]; ?> !important;
	}

.second_body_heading, #body #body_heading, .forget, .username, #rewardwrap a {
	color: <? echo $options["general_text_color"]; ?> !important;
}
#maincontainer {
	<? if($options["appearence"] == "1") { ?>
		min-width: 	<? echo (empty($options["min_width_of_the_container"]) || $options["min_width_of_the_container"] == "0px" ? "830px" : $options["min_width_of_the_container"]); ?> !important;
	<? } else { ?>
		min-width: 	<? echo (empty($options["min_width_of_the_container"]) || $options["min_width_of_the_container"] == "0px" ? "640px" : $options["min_width_of_the_container"]); ?> !important;
	<? } ?>
}
#display_uername_area a {
	color: <? echo $options["inactive_menu_link_color"]; ?> !important;
}
#display_uername_area a.selected_red {
	color: <? echo $options["active_menu_link_color"]; ?> !important;
}
#display_uername_area {
    background-color: <? echo $options["menu_bg_color"]; ?> !important;
}
.left_col_inner_block .product {
    color: <? echo $options["sub_menu_headings_color"]; ?> !important;
	font-family: <? echo $options["titles_font_family"]; ?> !important;
	font-size: <? echo $options["titles_font_size"]; ?> !important;
	background-color: <? echo $options["sub_menu_headings_background_color"]; ?> !important;
	background-image: <? echo ($options["sub_menu_headings_background_image"] != "" ? "url('" . $options["sub_menu_headings_background_image"] . "')" : "none"); ?> !important;
}

.left_col_inner_block .product_name a, .flip #counting, #contents .subtotal {
    color: <? echo $options["items_title_color"]; ?> !important;
}

.favTitle {
    color: <? echo $options["items_title_color"]; ?> !important;
}

.left_col_inner_block .product_price, .flip #dollor, #contents .amount  {
    color: <? echo $options["items_price_color"]; ?> !important;
}

.favPrice  {
    color: <? echo $options["items_price_color"]; ?> !important;
}

.left_col_inner_block .product_name span {
    color: <? echo $options["items_description_color"]; ?> !important;
}

.left_col_inner_block .product span {
    color: <? echo $options["sub_menu_descriptions_color"]; ?> !important;
}

.favHead {
    color: <? echo $options["sub_menu_descriptions_color"]; ?> !important;
}

#body #body_right_col #your_summery {
    color: <? echo $options["your_order_summary_color"]; ?> !important;
	font-size: <? echo $options["your_order_summary_font_size"]; ?> !important;
}

.products_area, .products_area a {
	font-size: <? echo $options["items_and_prices_font_size"]; ?> !important;
}

#dhtmltooltip span {
	color: <? echo $options["sub_menu_headings_color"]; ?> !important;
}

.lMore {
	color: <? echo $options["sub_menu_headings_color"]; ?> !important;
}

#dhtmltooltip {
	color: <? echo $options["sub_menu_descriptions_color"]; ?> !important;
}
#body_right_col #contents {
	padding-bottom: 10px !important;
}
.left_col_inner_block, #body_right_col {
	background-color: <? echo $options["cell_bg_color"]; ?> !important;
	background-image: <? echo ($options["cell_bg_image"] != "" ? "url('" . $options["cell_bg_image"] . "')" : "none"); ?> !important;

	<? if($options["cell_bg_image_strech_or_tile"] == 2) { ?>
		background-repeat: repeat !important;
	<? } else { ?>
		background-position: center top !important;
		background-size: 100% 100% !important;
	<? } ?>
}
.left_col_inner_block, #body_right_col {
	border-width: <? echo $options["cell_border_thickness"]; ?> !important;
	border-color: <? echo $options["cell_border_color"]; ?> !important;
	border-style: solid;
}
.online_ordering .button {
	background-image: <? echo ($options["order_online_button_image"] != "" ? "url('" . $options["order_online_button_image"] . "')" : "url('../images/online_ordering.gif')"); ?> !important;
	background-position: center center;
	background-repeat: no-repeat;
	width: 255px;
	height: 37px;
	display:block;
	margin: 10px auto;
}
#rewardwrap .bar .barOuter .barInner {
    background: none repeat scroll 0 0 <? echo $options["vip_progress_bar_color"]; ?> !important;
}
</style>
<? //echo "<pre>"; var_dump($settings);  echo "</pre>";
 if($options["iframe_height_infinite"] == "1"){   ?>
	<script type="text/javascript">
		$(function(){
			var height=$("#maincontainer").height()+40;
			window.parent.postMessage('{"height":'+ height +'}', '*');
		});
	</script>
<? } ?>
</head>
<body>

<div id="maincontainer">
<? require($site_root_path . "views/wordpress/wp_home_nav.php") ?>

 