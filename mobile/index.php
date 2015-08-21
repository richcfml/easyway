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


if(isset($_GET['sso']) && $_GET['sso']!=''){
	$mSQL = "select u.*, bhs.session_id as session_id, bhs.session_expiry from bh_sso_user u inner join bh_sso_session bhs on u.id = bhs.sso_user_id WHERE bhs.session_id = '".$_GET['sso']."' and bhs.session_expiry > '".time()."'";
	
	Log::write("Sign In - SSO User - IF", "QUERY --".$mSQL, 'sso', 1);
	
	$sso_rs = dbAbstract::Execute($mSQL);
	if(dbAbstract::returnRowsCount($sso_rs) > 0){
		$sso_row = dbAbstract::returnObject($sso_rs);
		
		$mSQL = "select * from customer_registration where cust_email='".$sso_row->email."' and resturant_id='".$objRestaurant->id."'";
		Log::write("Sign In - SSO User - IF", "QUERY --".$mSQL, 'sso', 1);
		$cust_rs = dbAbstract::Execute($mSQL);
		
		// if customer record exist than login
		if(dbAbstract::returnRowsCount($cust_rs) > 0){
			$cust_row = dbAbstract::returnObject($cust_rs);
			if($cust_row->cust_email != '' && $cust_row->password != ''){
				dbAbstract::Update("update general_detail set sso_user_id='".$sso_row->id."' where id_2='".$cust_row->id."'");
?>
			<form method="post" name="sso_login" action="<?=$SiteUrl.$objRestaurant->url.'/?item=login'?>">
				<input type="hidden" name="email" value="<?=$cust_row->cust_email?>"/>
				<input type="hidden" name="password" value="<?=$cust_row->password?>"/>
				<input type="hidden" name="login" value="sso"/>
			</form>
			<script language="javascript">
				document.sso_login.submit();
			</script>
<?php					
			}
			else{
				echo '<div style="width=100%; text-align:center; color:#F00; height:20px; width:982px; background-color:#F3B5B5; border:1px solid #C37D7D; margin: 0 auto;">Sorry! Invalid E-mail or Password.</div>';
			}
		}
		else{
		  // if customer record not exist than register & login
		  $loggedinuser->cust_email=  $sso_row->email;
		  $loggedinuser->password= trim($sso_row->password);
		  $loggedinuser->cust_your_name= trim($sso_row->firstName);
		  $loggedinuser->LastName= trim($sso_row->lastName);
		  $loggedinuser->street1= trim($sso_row->address1) ;
		  $loggedinuser->street2= trim($sso_row->address2) ;
		  $loggedinuser->cust_ord_city= trim($sso_row->city) ;
		  $loggedinuser->cust_ord_state= trim($sso_row->state) ;
		  $loggedinuser->cust_ord_zip= trim($sso_row->zip) ;
		  $loggedinuser->cust_phone1= trim($sso_row->phone) ;
		  
		  $loggedinuser->delivery_street1= trim($sso_row->address1) ;
		  $loggedinuser->delivery_street2= trim($sso_row->address2) ;
		  $loggedinuser->delivery_city1= trim($sso_row->city) ;
		  $loggedinuser->delivery_state1= trim($sso_row->state) ;
		  $loggedinuser->deivery1_zip= trim($sso_row->zip) ;
		  
		  $loggedinuser->resturant_id =$objRestaurant->id;
		  $result=$loggedinuser->register($objRestaurant,$objMail);
		  if($result===true){
			header("location: ". $SiteUrl .$objRestaurant->url."/");
			exit;	
		  }
		}
	}else{
		echo '<div style="width=100%; text-align:center; color:#F00; height:20px; width:982px; background-color:#F3B5B5; border:1px solid #C37D7D; margin: 0 auto;">Sorry! Invalid session id or session id has been expired.</div>';
	}
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