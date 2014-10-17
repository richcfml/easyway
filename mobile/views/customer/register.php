<?
$mZipPostal = "Zip Code:";
$mStateProvince = "State:";

if ($objRestaurant->region == "2") //Canada
{
	$mZipPostal = "Postal Code:";
	$mStateProvince = "Province:";
}
	
$result=-1;
if(isset($_POST['btnsave'])) {
	extract($_POST);
	 	$loggedinuser->	cust_email=  $email;
		$loggedinuser->	password= trim($user_password) ;
		$loggedinuser->	cust_your_name= trim($first_name) ;
		$loggedinuser->	LastName= trim($last_name) ;
		$loggedinuser->	street1= trim($address1) ;
		$loggedinuser->	street2= trim($address2) ;
		$loggedinuser->	cust_ord_city= trim($city) ;
		$loggedinuser->	cust_ord_state= trim($state) ;
		$loggedinuser->	cust_ord_zip= trim($zip) ;
		$loggedinuser->	cust_phone1= trim($phone1) ;
		$loggedinuser->	delivery_street1= trim($saddress1) ;
		$loggedinuser->	delivery_street2= trim($saddress2) ;
		$loggedinuser->	delivery_city1= trim($scity) ;
		$loggedinuser->	delivery_state1= trim($cstate) ;
			$loggedinuser->	deivery1_zip= trim($czip) ;
		$loggedinuser->resturant_id =$objRestaurant->id;
		$result=$loggedinuser->register($objRestaurant,$objMail);
		if($result===true){
			 header("location: ". $SiteUrl .$objRestaurant->url ."/?item=account" );
			 exit;	
			}
	}
?>
<section class='menu_list_wrapper'>
  <h1>Registeration detail</h1>
  <form action="" id="registerationform" method="post" class="commentsblock">
   <? if ($result===false){?>
   <div class="alert-blue"> Sorry, registeration failed please try again.</div>
   
      <? } ?>
       <? if ($result===0){?>
   <div class="alert-blue"> This email is already registerd with easywayordering.com/<?= $objRestaurant->url ?>. Please <a href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?item=forgotpassword">Click Here</a> if you forgot your passowrd.</div>
   
      <? } ?>
    <div class="margintop normal">
      <div class="left">Email:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="150" title="email" tabindex="1" id="email" name="email"/>
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left">Password:</div>
      <div class="rightItem">
        <input type="password"  size="30"  maxlength="30" title="Password" tabindex="2" id="user_password" name="user_password"     />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left">Confirm Password:</div>
      <div class="rightItem">
        <input type="password"  size="30"  maxlength="30" title="Password" tabindex="2" id="user_password_confirm" name="user_password_confirm"   />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left">First Name:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="First Name" tabindex="3" id="first_name" name="first_name"   />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">Last Name:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Last Name" tabindex="4" id="last_name" name="last_name"   />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">Street1:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Street1" tabindex="5" id="address1" name="address1" />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">Street2:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Street2" tabindex="6" id="address2" name="address2"  value="<?= $loggedinuser->street2 ?>" />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">City:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="City" tabindex="7" id="city" name="city" />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left"><?=$mStateProvince?></div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="State" tabindex="8" id="state" name="state" / >
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left"><?=$mZipPostal?></div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Zip Code" tabindex="9" id="zip" name="zip" />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">Phone #:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Phone #" tabindex="10" id="phone1" name="phone1" />
      </div>
      <div class="clear"></div>
    </div>
    <div class="red margintop"> Alternate Delivery Address optional</div>
    <div class="margintop normal">
      <div class="left">Street1:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Street 1" tabindex="11" id="saddress1" name="saddress1"   />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">Street2:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Street 2" tabindex="12" id="saddress2" name="saddress2"  />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left">City:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="City" tabindex="13" id="scity" name="scity"  />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left"><?=$mStateProvince?></div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="State" tabindex="14" id="cstate" name="cstate" />
      </div>
      <div class="clear"></div>
    </div>
    <div class="margintop normal">
      <div class="left"><?=$mZipPostal?></div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Zip code" tabindex="15" id="czip" name="czip"  />
      </div>
      <div class="clear"></div>
    </div>
    <div class="rightalign">
      <input type="submit" name="btnsave" value="Save" class="button blue">
      <input type="button" name="checkout" value="Continue without login" class="button blue" onclick="window.location='?item=checkout'">
    </div>
    <br/>
    <br/>
  </form>
</section>
<script type="text/javascript">
$(function(){
$("#registerationform").validate({
           rules: {
				email: {required: true, email:1 },
				user_password: {required: true,minlength: 5},
				user_password_confirm: {equalTo: "#user_password",required: true,minlength: 5},
				first_name: {required: true,minlength: 3},
				last_name: {required: true,minlength: 3},
				address1: {required: true,minlength: 3},
				city: {required: true,minlength: 2},
				state: {required: true,minlength: 2},
				zip: {required: true,minlength: 3},
				phone1: {required: true,minlength: 3},
           },
           messages: {
                   email: {
							   required: "please enter your email address",
							   email: "please enter a valid email address"
                               },
				   user_password: {
								   required: "please enter your password",
								   minlength: "your password should contain at leat 5 characters"
 								 },
				   
				   user_password_confirm : {
										  equalTo : "password mismatched, please confirm your password",
										  required: "please enter your password",
										  minlength: "your password should contain at leat 5 characters",
					  			 			},
				  first_name: {
					   			required: "please enter your first namess",
							   minlength: "please enter a valid first namess"
						   },
				   last_name: {
					   required: "please enter your last namess",
					   minlength: "please enter a valid last name"
                        
                   },
				   
				  address1: {
					   required: "please enter your street 1",
					   minlength: "please enter a valid street 1"
                        
                   },
				 city: {
					   required: "please enter your city",
					   minlength: "please enter a valid city"
                        
                   },
				 state: {
					   required: "please enter your <?=$mStateProvince?>",
					   minlength: "please enter a valid <?=$mStateProvince?>"
                        
                   },
				zip: {
					   required: "please enter your <?=$mZipPostal?>",
					   minlength: "please enter a valid <?=$mZipPostal?>"
                        
                   },
				 phone1: {
					   required: "please enter your phone1",
					   minlength: "please enter a valid phone1"
                        
                   },
					   
           },
		   errorElement: "div",
       
          errorClass: "alert-error",
});
});
</script>