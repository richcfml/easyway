<script type="text/javascript">
$(function(){
	$(".commentsblock").validate({
		rules: {
			email: {required: true, email:1 },
		},
		messages: {
			email: {
				required: "please enter your email address",
				email: "please enter a valid email address"
			}
		},
		errorElement: "div",
		errorClass: "alert-error",
	});
});
</script>
<?
$result=-1;
//$userQuery = mysql_query("UPDATE customer_registration SET password='12345' WHERE cust_email='aliraza@qualityclix.com'");
if (isset($_POST['forgotpassword'])){
	$result=$loggedinuser->remindPassword($_POST['email'],$objRestaurant,$objMail);
}
?>

<div id="body_left_col" style="width:99.7%; border:#e4e4e4 1px solid;margin-bottom:15px;">
<div class="heading">Forgot Password </div>
<form action="" method="post" class="commentsblock">
  <? if ($result===false){?>
  <div class="error_message">Sorry, that email address is currently not registered at easywayordering.com</div>
  <? } ?>
  <? if ($result===true){?>
  <div class="success">Your account password has been sent to your email address.&nbsp;&nbsp;<a href="<?=$SiteUrl . $objRestaurant->url ."/?wp_api=load_resturant"?>">Back to Home</a></font></div>
  <?  } ?>
  
  <div class="username">Email:<font color="#FF0000">*</font></div>
  <div class="username">
    <input type="text"  size="30"  maxlength="150" title="user email" tabindex="1" id="email" name="email">
  </div>
  <div style="clear:both"></div>
   <div class="username">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
  <div class="username">
    <input type="submit" name="forgotpassword" value="Submit" class="button blue">
  </div>
  <div style="clear:both"></div>
  <br />
  <br />
</form>
</div>
<br style="clear: both;" />
