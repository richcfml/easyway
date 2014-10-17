<?php
	$mZipPostal = "Zip Code:";
	$mStateProvince = "State:";
	
	if ($objRestaurant->region == "2") //Canada
	{
		$mZipPostal = "Postal Code:";
		$mStateProvince = "Province:";
	}
?>
<section class='menu_list_wrapper'>
  <h1>Account detail</h1>
  <form action="?item=account" method="post" class="commentsblock">
    <? if (isset($result) && $result===false){?>
    <div class="alert-blue">
      
      Sorry, Your email or password is not correct</div>
    <? } ?>
    <div class="margintop normal">
      <div class="left">Email:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="150" title="email" tabindex="1" id="email" name="email"  value="<?= $loggedinuser->cust_email ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left">Password:</div>
      <div class="rightItem">
        <input type="password"  size="30"  maxlength="30" title="Password" tabindex="2" id="password" name="password"  value="<?= $loggedinuser->password ?>"  />
      </div>
      <div class="clear"></div>
    </div>
    
    
    
    <div class="margintop normal">
      <div class="left">First Name:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="First Name" tabindex="2" id="first_name" name="first_name"  value="<?= $loggedinuser->cust_your_name ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
 
    
     <div class="margintop normal">
      <div class="left">Last Name:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Last Name" tabindex="2" id="last_name" name="last_name"  value="<?= $loggedinuser->LastName ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
 
   <div class="margintop normal">
      <div class="left">Street1:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Street1" tabindex="2" id="address1" name="address1"  value="<?= $loggedinuser->street1 ?>" />
      </div>
      <div class="clear"></div>
    </div>
   
   <div class="margintop normal">
      <div class="left">Street2:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Street2" tabindex="2" id="address2" name="address2"  value="<?= $loggedinuser->street2 ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left">City:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="City" tabindex="2" id="city" name="city"  value="<?= $loggedinuser->cust_ord_city ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left"><?=$mStateProvince?></div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="State" tabindex="2" id="state" name="state"  value="<?= $loggedinuser->cust_ord_state ?>" / >
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left"><?=$mZipPostal?></div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Zip Code" tabindex="2" id="zip" name="zip"  value="<?= $loggedinuser->cust_ord_zip ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left">Phone #:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Phone #" tabindex="2" id="phone1" name="phone1"  value="<?= $loggedinuser->cust_phone1 ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="red margintop"> Alternate Delivery Address optional</div>
     <div class="margintop normal">
      <div class="left">Street1:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Street 1" tabindex="2" id="saddress1" name="saddress1"  value="<?= $loggedinuser->delivery_street1 ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left">Street2:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Street 2" tabindex="2" id="saddress2" name="saddress2"  value="<?= $loggedinuser->delivery_street2 ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left">City:</div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="City" tabindex="2" id="scity" name="scity"  value="<?= $loggedinuser->delivery_city1 ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left"><?=$mStateProvince?></div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="State" tabindex="2" id="cstate" name="cstate"  value="<?= $loggedinuser->delivery_state1 ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="margintop normal">
      <div class="left"><?=$mZipPostal?></div>
      <div class="rightItem">
        <input type="text"  size="30"  maxlength="30" title="Zip code" tabindex="2" id="czip" name="czip"  value="<?= $loggedinuser->deivery1_zip ?>" />
      </div>
      <div class="clear"></div>
    </div>
    
    <div class="rightalign">
      <input type="submit" name="btnsave" value="Save" class="button blue">
   </div>
  <br/> <br/>
  </form>
  <div style="height:5px;">&nbsp;</div>
</section><script type="text/javascript">
$(function(){
$(".commentsblock").validate({
           rules: {
				email: {required: true, email:1 },
				password: {required: true,minlength: 5},
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
				   password: {
								   required: "please enter your password",
								   minlength: "your password should contain at leat 5 characters"
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