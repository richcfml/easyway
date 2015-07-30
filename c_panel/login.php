<?php
require_once("../includes/config.php");
	$function_obj = new clsFunctions();
	
	@$admin_err = 0;
	$chargify_url = "";
	
	if (isset($_POST['frm_submit']) && $_POST['frm_submit'] == 1) {
		$username = $_REQUEST['username'];
		$pass 	  = $_REQUEST['pass'];
		
		$qry_str = "SELECT id, type, status FROM users WHERE username='".prepareStringForMySQL($username)."' AND password='".prepareStringForMySQL($pass)."'";
		$user = dbAbstract::Execute($qry_str,1);
		if(dbAbstract::returnRowsCount($user,1) > 0) {
			$user = dbAbstract::returnAssoc($user,1);
			if($user["status"] == 1) {
				// get resturants whom licences suspended due to cancellation of payment
				$qry_str = "
					SELECT c.site_shared_key,r.chargify_subscription_id,c.hosted_page_url
					FROM chargify_products c
					LEFT JOIN resturants r
					ON r.chargify_subscription_canceled=1 AND r.owner_id='". $user["id"] ."'
					WHERE r.chargify_product_id=c.settings_id AND c.site_shared_key!=''
				";
				$resturants = dbAbstract::Execute($qry_str,1);
				if(dbAbstract::returnRowsCount($resturants,1) > 0) {
					// ask user to update payment of the suspended restaurant licenses
					$resturant = dbAbstract::returnAssoc($resturants,1);
					$return_url = $resturant["hosted_page_url"];
					$subdomain = substr($return_url, 7, strpos($return_url, '.')-strlen($return_url));
					$message = "update_payment--". $resturant["chargify_subscription_id"] ."--". $resturant["site_shared_key"];
					$message = SHA1($message);
					$token = substr($message, 0, 10);
					$chargify_url = "https://$subdomain.chargify.com/update_payment/". $resturant["chargify_subscription_id"] ."/$token";
					$admin_err = 3;
				} else {
					// login user
					$_SESSION['admin_session_user_name'] = $username;
					$_SESSION['admin_session_pass'] = $pass;
					$_SESSION['admin_type'] = $user["type"];
					$_SESSION['owner_id'] = $user["id"];
					
					header("location:./?mod=resturant");
				}
			} else {
				// user is not active
				$admin_err = 2;
			}
		} else {
			// provided credentials are incomplete
			$admin_err = 1;
		}
		
		// $fun_qry_str = "select * from users where username='$username'";
		// $pass_feild = "password";
					
		// if ( $function_obj -> ValidateAdmin_MySQL($username , $pass , $fun_qry_str , $pass_feild) == 1) {
			// if($_SESSION['status'] == 1) {
				// $_SESSION['admin_session_user_name'] = $username;
				// $_SESSION['admin_session_pass'] = $pass;
				// header("location:./?mod=resturant");
			// } else {
				// $admin_err = 2;
			// }
			// /*echo "<script language=\"javascript\">window.location=\"./?mod=resturant\"</script>";*/
		// } else {
			// $admin_err = 1;
		// }	
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>easywayordering</title>
<link href="css/adminMain.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">

<div style="margin-top:10%">
<? if(@$admin_err == 1){?><div class="msg_error">Either username or password is not correct!</div><? }?>
<? if(@$admin_err == 2){?><div class="msg_error">Account not activated!</div><? }?>
<? if(@$admin_err == 3){?><div class="msg_error">One or more of your restaurants may be suspended. Click <a href="<?=$chargify_url?>">here</a> to update your billing information</div><? }?>
<? if (isset($_GET["msg"])) { if($_GET["msg"] == "chargify_update_success"){?><div class="msg_error">Your account has been successfully updated and their restaurant has been re-activated</div><? } }?>
<div id="administration_box">


	<div id="contents">
    	<div id="manandlock"><img src="images/man_img.png" width="45" height="59" /></div>
        <div id="Administration"><img src="images/text.png" width="217" height="17" alt="Admonistration panel" /></div><br style="clear:both" />
        <div id="text">Username:</div>
        <div id="text_field"><input type="text" name="username" id="username" value="username" onfocus="this.value='';" style="width:199px;" /></div><br style="clear:both" />
      	<div id="text" style="padding:14px 0px 0px 1px;">Password:</div>
      	<div id="text_field" style="margin-top:10px;"><input type="Password" value="Password" name="pass" id="pass" onfocus="this.value='';" style="width:199px;" /></div><br style="clear:both" />
        <div id="login_bttn"><input type="image" src="images/login.png" name="submit" id="submit" value="Login" />
        <input type="hidden" name="frm_submit" id="frm_submit" value="1" />
        </div>
  </div><!--End contents Div-->	
</div>
</div>
</form>
</body>
</html>
<?php mysqli_close($mysqli);?>