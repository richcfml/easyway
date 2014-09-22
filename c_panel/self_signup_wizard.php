<?
	session_start();
	ini_set("display_errors", 0);
	include("../includes/config.php");
	include("includes/function.php");
	$function_obj = new clsFunctions();
	
	@$admin_err = 0;
	
	if ($_POST['frm_submit'] == 1) {
		//echo "i am on page";
		$username = $_REQUEST['username'];
		$pass 	  = $_REQUEST['pass'];
		
		$fun_qry_str = "select * from users where username='$username'";
		$pass_feild = "password";
					
		if ( $function_obj -> ValidateAdmin_MySQL($username , $pass , $fun_qry_str , $pass_feild) == 1) {
				if($_SESSION['status'] == 1) {
				$_SESSION['admin_session_user_name'] = $username;
				$_SESSION['admin_session_pass'] = $pass;
				header("location:./?mod=resturant");
			} else {
				$admin_err = 2;
			
			}
			/*echo "<script language=\"javascript\">window.location=\"./?mod=resturant\"</script>";*/
		} else {
			$admin_err = 1;
		}	
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Self Signup Wizard - EasyWayOrdering</title>
<link href="css/adminMain.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">

<div style="margin-top:10%">
<? if(@$admin_err == 1){?><div class="msg_error">Either username or password is not correct!</div><? }?>
<? if(@$admin_err == 2){?><div class="msg_error">Account not activated!</div><? }?>
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
