<? 
if(isset($_POST['btncheckout'])) {
	if($_POST['btncheckout']=='Pickup'){
		 $cart->setdelivery_type(cart::Pickup);
		}else{
			 $cart->setdelivery_type(cart::Delivery);
			}
	}
	$user_email='';
	if(isset($_COOKIE["user"])){
		$user_email=$_COOKIE["user"];
  	}
	$remeber_me='';
	if(isset($_POST['rememberme'])){
			$remeber_me=$_POST['rememberme'];
		}
$result=-1;
if (isset($_POST['login'])){
	$user_email=$_POST['email'];
	$user=$loggedinuser->login($_POST['email'],$_POST['password'],$objRestaurant->id);

 	if(is_null($user))
 	{
		 $result=false;
	 }
	 
	 else{
		 
		 	$loggedinuser->destroysession();
		    $loggedinuser=$user;
		 	
	 
	 
			if($objRestaurant->useValutec==1) {
			if($loggedinuser->valuetec_card_number>0)  {
				$Balance=CardBalance($loggedinuser->valuetec_card_number);
				 $loggedinuser->valuetec_points=$Balance['PointBalance'];
				$loggedinuser->valuetec_reward=$Balance['Balance'];
			
			}
			}else{
				$loggedinuser->valuetec_card_number=0;
				}

 			$address1=explode('~',trim($loggedinuser->cust_odr_address,'~'));
		 
			$loggedinuser->street1=$address1[0];
			$loggedinuser->street2='';
			if(count($address1)>=1)
			$loggedinuser->street2=$address1[1];
			
			$address1=explode('~',trim($loggedinuser->delivery_address1,'~'));
	 
			$loggedinuser->delivery_street1=$address1[0];
			$loggedinuser->delivery_street2='';
		if(count($address1)>=1)
			$loggedinuser->delivery_street2=$address1[1];
			
		 	$loggedinuser->savetosession();
			
			 
			
			 $result=true;
			 header("location: ". $client_path .$objRestaurant->url ."/?item=account" );exit;	
		/* echo "<script type='text/javascript'>window.location=''</script>";*/
				 
		 }
	 
	}
else if(is_numeric($loggedinuser->id)) {
	header("location: ". $client_path .$objRestaurant->url ."/?item=account" );exit;
	/* echo "<script type='text/javascript'>window.location='?item=account'</script>";*/
}	
?>
<script type="text/javascript">
$(function(){
$(".commentsblock").validate({
           rules: {
				email: {required: true, email:1 },
				password: {required: true,minlength: 3},
				 
           },
           messages: {
                   email: {
							   required: "please enter your email address",
							   email: "please enter a valid email address"
                               },
				   password: {
								   required: "please enter your password",
								   minlength: "your enter a valid password"
 								 }
	  },
		   errorElement: "div",
       
          errorClass: "alert-error",
});
});

</script>

<section class='menu_list_wrapper'>
  <h1>Exiting users</h1>
  <form action="" method="post" class="commentsblock">
  <? if ($result===false){?>
   <div class="alert-blue"> Sorry, Your email or password is not correct</div>
   
      <? } ?>
      
    <p class="margintop">Username:&nbsp;&nbsp;<span>
      <input type="text"  size="30"  maxlength="150" title="Username" tabindex="1" id="email" name="email" value="<?= $user_email ?>">
      </span> </p>
    <p class="margintop">Password:&nbsp;&nbsp; <span>
      <input type="password"  size="30"  maxlength="30" title="Password" tabindex="2" id="password" name="password">
      </span> </p>
         <div class="rightalign">Remember me <input type="checkbox"  name="rememberme"  value="1" <?= ($remeber_me=="1" ? "Checked":"")?> /> </div>
         
       <div class="rightalign">Forgot your password? <a href="<?=$client_path?><?= $objRestaurant->url ."/"?>?item=forgotpassword">Click Here</a></div>
             <div class="rightalign">New User? <a href="<?=$client_path?><?= $objRestaurant->url ."/"?>?item=register">Click Here to register</a>
 </div>
  <div class="rightalign">
  <input type="submit" name="login" value="Login" class="button blue">
  <input type="button" name="checkout" value="Continue without login" class="button blue" onclick="window.location='?item=checkout'">
  </div>
      
  </form>
  <div style="height:5px;">&nbsp;</div>
</section>
