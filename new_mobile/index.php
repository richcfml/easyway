<?php
	$mobile_root_path = "new_mobile/";
	$mobile_css_path = "../css/new_mobile/";
	$css_root_path = "../css/";
	$js_root="../js/";
	$mobile_js_path = "../js/new_mobile/";
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
	 
    if (is_null($cart)) {
    $cart = new cart();
} else {
    $cart = unserializeData($_SESSION['CART']);
    if ($objRestaurant->id != $cart->restaurant_id) {
        $cart->destroyclone();
        $cart->destroysession();
        $cart = new cart();
    } else {
        $cart = unserializeData($_SESSION['CART']);
    }
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
			if($cust_row->cust_email != '' && $cust_row->epassword != ''){
                            dbAbstract::Update("update general_detail set sso_user_id='".$sso_row->id."' where id_2='".$cust_row->id."'");
?>
			<form method="post" name="sso_login" action="<?= $SiteUrl . $objRestaurant->url ?>/?item=login">
            	<input type="hidden" name="ssoUserId" value="<?=$sso_row->id?>"/>
				<input type="hidden" name="email" value="<?=$cust_row->cust_email?>"/>
				<input type="hidden" name="login" value="sso"/>
			</form>
			<script language="javascript">
				document.sso_login.submit();
			</script>
<?php
			}
			else{
				echo '<div style="width=100%; text-align:center; color:#F00; height:20px; background-color:#F3B5B5; border:1px solid #C37D7D; margin: 0 auto;">Sorry! Invalid E-mail or Password.</div>';
			}
		}
		else{
		  // if customer record not exist than register & login
		  $loggedinuser->cust_email=  $sso_row->email;
		  $mSalt = hash('sha256', mt_rand(10,1000000));    
		  $loggedinuser->salt= $mSalt;
		  $loggedinuser->epassword= hash('sha256', trim($sso_row->password).$mSalt);
		  
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
		  $loggedinuser->ssoUserId = $sso_row->id;
		  
		  $result=$loggedinuser->customerRegistration($objRestaurant,$objMail);
		  if($result===true){
			$loggedinuser->getUserCCTokens();
		  	$loggedinuser->saveToSession();
			
			header("location: ". $SiteUrl .$objRestaurant->url.'/');
			exit;	
		  }
		}
	}else{
		echo '<div style="width=100%; text-align:center; color:#F00; height:20px; background-color:#F3B5B5; border:1px solid #C37D7D; margin: 0 auto;">Sorry! Invalid session id or session id has been expired.</div>';
	}
}

if(isset($_GET['ajax']))
{
 
	require($include);	exit;
}
else{
?>

    <!DOCTYPE html>
    <html dir="ltr" lang="en-US">
        <head>
            <meta charset="utf-8">
            <title><?= $objRestaurant->name; ?></title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta NAME="DESCRIPTION" CONTENT="<?= trim(stripslashes($objRestaurant->meta_description)); ?>">
            <meta NAME="KEYWORDS" CONTENT="<?= trim(stripslashes($objRestaurant->meta_keywords)); ?>">


            <meta content="on http-equiv=cleartype">
            <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
            <meta content="initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            
            <link href="<?= $mobile_css_path ?>application.css" rel="stylesheet" id="theme" >
            <link href="<?= $mobile_css_path ?>themes/<?=($objRestaurant->theme_name != '')? $objRestaurant->theme_name:'default'?>.css" rel=stylesheet id=theme />
            
            <link rel="stylesheet" href="<?= $css_root_path ?>font-awesome.min.css">
            <link rel="stylesheet" href="<?= $mobile_css_path ?>style.css">
            <script src="//maps.googleapis.com/maps/api/js?key=<?=$google_api_key?>&sensor=false" type="text/javascript"></script>
            <script language="javascript" src="<?= $js_root ?>jquery.min.js"></script>
            
            
            <script language="javascript">
                                    $(document).ready(function (e) {
				EasyWay.ThemeSelector.appendSelector(["<?=$objRestaurant->theme_name?>"]);

				var reg = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				var regName = /^[a-zA-Z ]{2,30}$/;
				
				$(".email").keyup(function () {
					if (reg.test($(this).val()) == false) {
						$(".invalidVal").show();
						$(".validVal").hide();
					} else {
						$(".validVal").show();
						$(".invalidVal").hide();
					}
				});

                                        $("#registerationform").submit(function () {
                                            if (reg.test($("#reg_email").val()) == false) {
                                                $(".invalidVal").show();
                                                $(".validVal").hide();
                                                $('#reg_email').css('border-color', '#F00');
                                                return false;
                                            } else {
                                                $(".validVal").show();
                                                $(".invalidVal").hide();
                                                $('#reg_email').css('border-color', '#6b8f9a');
                                            }

                                            if ($('#user_password').val() == '') {
                                                $('#user_password').css('border-color', '#F00');
                                                return false;
                                            } else {
                                                $('#user_password').css('border-color', '#6b8f9a');
                                            }

					if (regName.test($('#first_name').val()) == false) {
						$('#first_name').css('border-color', '#F00');
						return false;
					} else {
						$('#first_name').css('border-color', '#6b8f9a');
					}

					if (regName.test($('#last_name').val()) == false) {
						$('#last_name').css('border-color', '#F00');
						return false;
					} else {
						$('#last_name').css('border-color', '#6b8f9a');
					}

                                        });

                                        $("#loginfrm").submit(function () {
                                            if (reg.test($("#login_email").val()) == false) {
                                                $(".invalidVal").show();
                                                $(".validVal").hide();
                                                $('#login_email').css('border-color', '#F00');
                                                return false;
                                            } else {
                                                $(".validVal").show();
                                                $(".invalidVal").hide();
                                                $('#login_email').css('border-color', '#6b8f9a');
                                            }

                                            if ($('#password').val() == '') {
                                                $('#password').css('border-color', '#F00');
                                                return false;
                                            } else {
                                                $('#password').css('border-color', '#6b8f9a');
                                            }
                                        });

                                        $("#forgotpswdfrm").submit(function () {
                                            if (reg.test($("#fp_email").val()) == false) {
                                                $(".invalidVal").show();
                                                $(".validVal").hide();
                                                $('#fp_email').css('border-color', '#F00');
                                                return false;
                                            } else {
                                                $(".validVal").show();
                                                $(".invalidVal").hide();
                                                $('#fp_email').css('border-color', '#6b8f9a');
                                            }
                                        });
                                    });
            </script>
        </head>

    <?php //include_once $mobile_root_path . 'views/restaurant/sub_menu.php'; ?>



    <?php
    require($mobile_root_path . "../new_site/includes/abandoned_cart_config.php");
    require($include);

    require($mobile_root_path . "includes/footer.php");
    ?>

    </body>
    </html>

    <?php } ?>