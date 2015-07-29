<?php
	$mobile_root_path = "mobile/";
	$mobile_css_path = "../css/mobile/";
	$css_root_path = "../css/";
	$js_root="../js/";
	$mobile_js_path = "../js/mobile/";
	$mobile_images_path = "../images/mobile/";
	if (($objRestaurant->region == 1) || ($objRestaurant->region == 2))
	{
		$currency = "$";
		$java_currency = "$";
	}
	else
	{
		$currency = "&#163;"; 
		$java_currency = "\u00A3";
	}
	
 
	// get restaurant details

	$cart=NULL;
	if(isset($_SESSION['CART']))
	$cart=$_SESSION['CART'];
	 
	if(is_null($cart)) {
	   $cart=new cart();
	  }else{
		$cart=unserializeData($_SESSION['CART']); 
	 }
	$cart->restaurant_id=$objRestaurant->id;
	$cart->sales_tax_ratio=$objRestaurant->tax_percent;
 	$cart->rest_delivery_charges=$objRestaurant->delivery_charges;
	 
	 if(!is_numeric($loggedinuser->id)){ 
 		$loggedinuser->resturant_id=$objRestaurant->id;
	 }
	 
	 
	 if($objRestaurant->delivery_option=='delivery_zones'){
			$objRestaurant->delivery_charges=$objRestaurant->zone1_delivery_charges;
			$objRestaurant->order_minimum=$objRestaurant->zone1_min_total;
			}
			
	 if($cart->isempty()){	 
		$cart->rest_delivery_charges=$objRestaurant->delivery_charges;
		 }
		 
	require($mobile_root_path . "includes/controller.php");
	 

if(isset($_POST['rememberme'])){
		$expire=time()+60*60*24*30;
		setcookie("user", $_POST['email'], $expire);
	}
	
if(isset($_GET['ajax']))
{
 
	require($include);	exit;
}
else{
	
  require($mobile_root_path . "includes/header.php");

?>

<!-- pull down menu's submenus-->
 
<? 
 require($mobile_root_path . "../new_site/includes/abandoned_cart_config.php");
require($include);	
if($mod=='resturants') {
?>
<!--Footer -->

<footer  class="footer_wrapper" data-role="footer">
  <a  class="current" href="?desktop=1">Switch to Desktop View </a>  
 </footer>
 <? }?>
 
</body></html>

<script type="text/javascript">
$(window).ready(function() {
	menu_down();
	<? if (isset($_GET['category'])){ ?> 
	  $('#sub_menu_contain').slideToggle('slow', menu_down);
	  $('.pull_arrow').css("background-position","0px -22px");
			$('.pull_txt').html('Pull Down Menu');
	  <? }?>
	  
});
// Added by Asher Ali 
$(function(){
        $(".menu_disabled").click(function(e) {
                e.preventDefault();
                $.facebox("<div class='alert-error'><span class='alert-bold'>"+ $(this).attr("title") + "</span> is not available at this time.  Would you like to view the menu anyway? <br/> <br/> <a href='?item=menu&menuid="+ $(this).attr("menuid") +"' class='boldlink'>View Menu</a> | <a href='?item=menu' class='boldlink'>Return to Main Menu</a>&nbsp;&nbsp;<br/><br/>  <span class='alert-bold'> Menu Timing: &nbsp; "+ $(this).attr("timings")  +"</span> </div>");
        })
});
</script>
<?   } ?>