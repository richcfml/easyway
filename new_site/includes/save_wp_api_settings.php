<?php
//print_r($_POST);
ini_set("display_errors", 1);
$options = array(
	"easyway_active_menu_link_color" => "",
	"easyway_appearence" => "1",
	"easyway_cell_bg_color" => "#ffffff",
	"easyway_general_font_size" => "12px",
	"easyway_general_text_color" => "#000000",
	"easyway_secondary_text_color" => "#000000",
	"easyway_cell_bg_image" => "",
	"easyway_cell_bg_image_strech_or_tile" => "2",
	"easyway_inactive_menu_link_color" => "#333333",
	"easyway_items_and_prices_font_size" => "14px",
	"easyway_items_title_color" => "#000000",
	"easyway_items_price_color" => "#000000",
	"easyway_items_description_color" => "#000000",
	"easyway_menu_bg_color" => "#f4f4f4",
	"easyway_min_width_of_the_container" => "",
	"easyway_order_online_button_image" => "",
	"easyway_restaurant_slug" => "",
	"easyway_show_loyalty_box_about_the_cart" => "1",
	"easyway_sub_menu_descriptions_color" => "#585858",
	"easyway_sub_menu_headings_color" => "#585858",
	"easyway_sub_menu_headings_background_color" => "#F4F4F4",
	"easyway_sub_menu_headings_background_image" => "",
	"easyway_titles_font_family" => "Arial,Helvetica,sans-serif",
	"easyway_titles_font_size" => "16px",
	"easyway_your_order_summary_color" => "#5F5F5F",
	"easyway_your_order_summary_font_size" => "18px",
	"easyway_show_item_pictures_and_description" => "1",
	"easyway_iframe_height" => "550px",
	"easyway_iframe_height_infinite" => "1",
	"easyway_cell_border_color" => "#F4F4F4",
	"easyway_cell_border_thickness" => "1px",
	"easyway_vip_progress_bar_color" => "#00CCFF"
);

foreach($options as $key => $option) {
	$options[$key] = isset($_POST[$key]) ? mysql_real_escape_string($_POST[$key]) : "";
}

//mysql_query("DELETE FROM `wp_restaurent_design_settings`");
 
extract($_POST);
//var_dump($_POST);
 
if(!empty($_POST["easyway_settings_id"]) && is_numeric($_POST["easyway_settings_id"]) && intval($_POST["easyway_settings_id"]) > 0) {
	$q = "DELETE FROM `wp_restaurent_design_settings` WHERE setting_id=" . $_POST["easyway_settings_id"];
	mysql_query($q);
}

$q = "INSERT INTO `wp_restaurent_design_settings`(
		`active_menu_link_color`, 
		`appearence`, 
		`general_font_size`,
		`general_text_color`,
		`secondary_text_color`,
		`cell_bg_color`, 
		`cell_bg_image`, 
		`cell_bg_image_strech_or_tile`, 
		`inactive_menu_link_color`, 
		`items_and_prices_font_size`, 
		`items_title_color`, 
		`items_price_color`, 
		`items_description_color`, 
		`menu_bg_color`, 
		`min_width_of_the_container`, 
		`order_online_button_image`, 
		`restaurant_slug`, 
		`show_loyalty_box_about_the_cart`, 
		`sub_menu_descriptions_color`, 
		`sub_menu_headings_color`, 
		`sub_menu_headings_background_color`, 
		`sub_menu_headings_background_image`, 
		`titles_font_family`, 
		`titles_font_size`, 
		`your_order_summary_color`, 
		`your_order_summary_font_size`, 
		`show_item_pictures_and_description`,
		`iframe_height`,
		`iframe_height_infinite`,
		`cell_border_color`,
		`cell_border_thickness`,
		`vip_progress_bar_color`,
		`easyway_url`,
		`status`,
		`restaurant_id`
		) VALUES (
		'". $easyway_active_menu_link_color . "','" .
		$easyway_appearence . "','" .
		$easyway_general_font_size . "','" .
		$easyway_general_text_color . "','" .
		$easyway_secondary_text_color . "','" .
		$easyway_cell_bg_color . "','" .
		$easyway_cell_bg_image . "','" .
		$easyway_cell_bg_image_strech_or_tile . "','" .
		$easyway_inactive_menu_link_color . "','" .
		$easyway_items_and_prices_font_size . "','" .
		$easyway_items_title_color . "','" .
		$easyway_items_price_color . "','" .
		$easyway_items_description_color . "','" .
		$easyway_menu_bg_color . "','" .
		$easyway_min_width_of_the_container . "','" .
		$easyway_order_online_button_image . "','" .
		$easyway_restaurant_slug . "','" .
		$easyway_show_loyalty_box_about_the_cart . "','" .
		$easyway_sub_menu_descriptions_color . "','" .
		$easyway_sub_menu_headings_color . "','" .
		$easyway_sub_menu_headings_background_color . "','" .
		$easyway_sub_menu_headings_background_image . "','" .
		$easyway_titles_font_family . "','" .
		$easyway_titles_font_size . "','" .
		$easyway_your_order_summary_color . "','" .
		$easyway_your_order_summary_font_size . "','" .
		$easyway_show_item_pictures_and_description . "','" .
		$easyway_iframe_height . "','1','" .
		$easyway_cell_border_color . "','" .
		$easyway_cell_border_thickness . "','" .
		$easyway_vip_progress_bar_color . "','" .
		$easyway_url . "'," .
		"'active',
		 $objRestaurant->id
		)";
		
$result = mysql_query($q) or die("Unable to save WP design settings." . mysql_error());
echo mysql_insert_id();
 
?>