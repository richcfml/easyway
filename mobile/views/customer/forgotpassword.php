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
if (isset($_POST['forgotpassword'])){
	$result=$loggedinuser->remindUserPassword($_POST['email'],$objRestaurant,$objMail);
}

//echo "<pre>";print_r($loggedinuser);echo "</pre>";
?>
<section class='menu_list_wrapper'>
  <h1>Forgot Password</h1>
  <form action="" method="post" class="commentsblock">
   <? if ($result===false){?>
   <div class="alert-blue">Sorry, that email address is currently not registered at easywayordering.com</div>
   
      <? } ?>
      
    <p class="margintop">Email:&nbsp;&nbsp; <span>
      <input type="text"  size="30"  maxlength="150" title="user email" tabindex="1" id="email" name="email">
      </span> </p>
    </div>
     <? if ($result===true){?>
    <div class="alert-blue">Your account password has been sent to your email address.&nbsp;&nbsp;<a href="<?=$SiteUrl . $objRestaurant->url ."/"?>">Back to Home</a></font></div>
   <?  } ?>
    <div class="rightalign">
      <input type="submit" name="forgotpassword" value="Submit" class="button blue">
    </div>
  </form>
  <div style="height:5px;">&nbsp;</div>
</section>
