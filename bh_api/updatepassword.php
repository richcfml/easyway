<?php
require_once("../includes/config.php");
require_once('../classes/Encrypt.php');
$encrypt = new Encrypt();

$result=-1;

// Decode Id
$id = $encrypt->decode($_GET['id']);
$row = dbAbstract::ExecuteObject("select * from bh_sso_user where id='$id'");

if(strtotime($row->passwordupdate_expiry) < strtotime(date("Y-m-d H:i:s"))){
	$result=-2;
}

if (isset($_POST['password']) && $result != -2){
	@extract($_POST);
	if(dbAbstract::Update("update bh_sso_user set password='$password' where id='$id'")){
		header("location:".$SiteUrl ."bh_api/pwd_update_success.php");
	}else{
		$result = false;
	}
}
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Create A New Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="../css/bh_custom.css">
    <link type="text/css" rel="stylesheet" href="../css/bootstrap.css">
    
    <script language="javascript" src="../js/jquery.min.js"></script>
	<script language="javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript">
    $(function(){
      $(".commentsblock").validate({
         rules: {
              password: {required: true, minlength:8, maxlength:20, pwcheck: true },
              cpassword: {
                  required: true, minlength:8, maxlength:20 ,
                  equalTo : "#password"
              }
         },
         messages: {
           password: {
             required: "please enter your new password",
             minlength: "Password length should be between 8 and 20",
             maxlength: "Password length should be between 8 and 20",
			 pwcheck: "Passwords must contain one or more capital letters and one or more numbers!"
           },
           cpassword: {
             required: "please confirm your new password",
             minlength: "Password length should be between 8 and 20",
             maxlength: "Password length should be between 8 and 20",
             equalTo: "Password & confirm password does not matched."
           }
         },
         errorElement: "div",
         errorClass: "alert-error",
      });
	  
	  $.validator.addMethod("pwcheck", function(value) {
	    return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
			 	&& /[a-z]/.test(value) // has a lowercase letter
			 	&& /\d/.test(value) // has a digit
	  });
    });
    </script>
  </head>
  <body>
  <div class="wrapper text-center">
  	<div class="contents">
    	<div id="logo"><a href="#"><img src="../images/bh_logo.png"></a></div>
        <div id="main" style="margin-top:-5px">
      <div class="container">
        <div class="row">
          <div class="col-md-3 col-sm-3 col-xs-12">&nbsp;</div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <h1>Create A New Password</h1>
            <p class="margin-bottom-45">
                Passwords must be 8-20 characters long and contain one or more capital letters and one or more numbers.
            </p>
            
            <? if ($result===-2){?>
              <div class="error_message">Sorry, The password reset link has been expired.</div>
              <? } ?>
              
              <? if ($result===false){?>
              <div class="error_message">Sorry, there is some problem please try again later.</div>
              <? } ?>
              
              <? if ($result===true){?>
              <div class="success">Your account password has been changed successfully.</div>
              <?  } ?>
              
            <form action="" method="post" class="commentsblock">
                <div class="form_group">
                    <label for="password">Enter your new password</label>
                    <input type="password" name="password" id="password" class="text_box" placeholder="* * * * * *">
                </div>
                <div class="form_group">
                    <label for="cpassword">Confirm your new password</label>
                    <input type="password" name="cpassword" id="cpassword" class="text_box" placeholder="* * * * * *">
                </div>
                
                <div class="form_group">
                    <button type="submit">reset your password</button>
                </div>
            </form>
            
             
          </div>
        </div>
      </div>
    </div>
    </div>
    <footer>
        <p class="font_14 upperCase footer_text bold">
            powered by <img src="../images/bh_logo2.png" width="150">
        </p>
        <p class="footer_text">
            &copy; 2015 Boar’s Head Brand&reg; All rights reserved.
        </p>
    </footer> 
  </div>
  
  
  
  
    
    
  </body>
</html>