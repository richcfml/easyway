<?
//int_set("display_errors", 1);
if(isset($_GET['item'])) $item = $_GET['item']; else $item = 'orders';

if($item == "orders") { $admin_subcontent = "admin_contents/analytics/tab_orders_analytics_listing.php"; }
elseif($item == "traffic") { $admin_subcontent = "admin_contents/analytics/tab_traffic_analytics_listing.php"; }
elseif($item == "abandoned_carts") { $admin_subcontent = "admin_contents/analytics/tab_abandoned_carts_analytics_listing.php"; }
?>
<div id="contents">
<?	
$and_sch='';
$Clentid = 0;
$resturant_id = 0;
$selected_resturant_name = "";

include "includes/resturant_header.php";

if(isset($_POST['sch_button'])) 
{
	$resturant_id = (isset($_POST["search_by"]) ? $_POST["search_by"] : 0);
} 
else
{
	$resturant_id = $mRestaurantIDCP; 
} 

?>
	<div id="main_heading" class="clearfix">
		<span style="font-size:18px; display: block; float: left;"><? if($selected_resturant_name != "") { echo $selected_resturant_name . "'s"; }?> <?=ucfirst(str_replace("_", " ", $item)); ?> Analytics</span>
		<span style="display: block; float: right; padding-right: 20px;">
			<?
				if($item == "traffic" or $item == "abandoned_carts") {
					echo date("F j, Y", strtotime("-30 day")) . " - " . date("F j, Y", strtotime("-1 day"));
				} else {
					echo date("F j, Y", strtotime("-60 day")) . " - " . date("F j, Y", strtotime("-1 day"));
				}
			?>
		</span>
	</div>
	
	<?
		include("left_nav_analytics.php");
	?>
	<div id="contents_area" style="float:left; margin-left:15px; width:78%;">
		<?
			if($resturant_id > 0 ){
				include $admin_subcontent;
			} else {
				echo "<b>Please select a resturant to views it's analytics</b>";
			}
			
		?>
	</div>
	<div style="clear: both;"></div>
</div>
<!--End body Div-->