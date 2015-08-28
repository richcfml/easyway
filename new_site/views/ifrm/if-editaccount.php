<?php
	$mZipPostal = "Zip Code";
	$mStateProvince = "State";
	
	if ($objRestaurant->region == "2") //Canada
	{
		$mZipPostal = "Postal Code";
		$mStateProvince = "Province";
	}
?>
<div id="body_left_col" style="width:980px; border: #e4e4e4 1px solid;">
     <form name="registerationform" id="registerationform" method="post" action="?item=account&ifrm=account">
             <div class="heading">Edit User Account</div>
             <? if ($errMessage != ""){?>
            <div class="msg_warning"><font color="#FF3333"><?=$errMessage?></font></div>
            <? }?>
              <? if ($register_result===false){?>
                   <div class="msg_warning"> Sorry, registeration failed please try again.</div>
                   
                      <? } ?>
                       <? if ($register_result===0){?>
                   <div class="msg_warning"> This email is already registerd with easywayordering.com/<?= $objRestaurant->url ?>. Please <a href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?item=forgotpassword">Click Here</a> if you forgot your passowrd.</div>
                   
                      <? } ?>
                      
      <div style="width:350px;">
      
           	 <div class="username">Email<font color="#FF0000">*</font></div>
             <div class="username_text_bar">
                 <input name="email" id="email" type="text" value="<?= $loggedinuser->cust_email ?>" />
</div><div style="clear:both"></div>
             <div class="username">Password<font color="#FF0000">*</font></div>
             <div class="username_text_bar">
               <input name="user_password" id="user_password" type="password"  />
             </div><div style="clear:both"></div>
             <div class="username">First Name<font color="#FF0000">*</font></div>
             <div class="username_text_bar">
               <input id="first_name" name="first_name" type="text" value="<?= $loggedinuser->cust_your_name ?>" />
             </div><div style="clear:both"></div>
             <div class="username">Last Name<font color="#FF0000">*</font></div>
             <div class="username_text_bar">
                 <input name="last_name" id="last_name" type="text" value="<?=stripslashes($loggedinuser->LastName)?>" />
			 </div><div style="clear:both"></div>
			 <div class="username">Street1<font color="#FF0000">*</font></div>
             <div class="username_text_bar"><input name="address1" id="address1" type="text" value="<?=stripslashes($loggedinuser->street1)?>"  /></div><div style="clear:both"></div>  
             <div class="username">Street2</div>
             <div class="username_text_bar"><input name="address2" id="address2" type="text" value="<?=stripslashes($loggedinuser->street2)?>" /></div><div style="clear:both"></div>            
             <div class="username">City<font color="#FF0000">*</font></div>
             <div class="username_text_bar"><input name="city" id="city" type="text" value="<?=stripslashes($loggedinuser->cust_ord_city)?>"  /></div><div style="clear:both"></div>
             <div class="username"><?=$mStateProvince?><font color="#FF0000">*</font></div>
             <div class="username_text_bar"><input name="state" id="state" type="text" value="<?=stripslashes($loggedinuser->cust_ord_state)?>"  /></div><div style="clear:both"></div>
             <div class="username"><?=$mZipPostal?><font color="#FF0000">*</font></div>
             <div class="username_text_bar"><input name="zip" id="zip" type="text" value="<?=stripslashes($loggedinuser->cust_ord_zip)?>"  /></div><div style="clear:both"></div>
             <div class="username">Phone<font color="#FF0000">*</font></div>
             <div class="username_text_bar"><input type="text" name="phone1" id="phone1" value="<?=stripslashes($loggedinuser->cust_phone1)?>"  /></div><div style="clear:both"></div>
             <div class="username">&nbsp;</div>
             <div class="username_text_bar">&nbsp;</div>
             <div class="username_text_bar">&nbsp;</div><div style="clear:both"></div>
             <span style="font-size:12px; padding-left:10px;" ><font color="#FF0000">Alternate Delivery Address optional:</font></span><div style="clear:both"></div>
           	 <div class="username">Street1</div>
             <div class="username_text_bar"><input name="saddress1" id="saddress1" type="text" value="<?=stripslashes($loggedinuser->delivery_street1)?>" /></div><div style="clear:both"></div>  
             <div class="username">Street2</div>
             <div class="username_text_bar"><input name="saddress2" id="saddress2" type="text" value="<?=stripslashes($loggedinuser->delivery_street2)?>"  /></div><div style="clear:both"></div>            
             <div class="username">City</div>
             <div class="username_text_bar"><input name="scity" id="scity" type="text" value="<?=stripslashes($loggedinuser->delivery_city1)?>" /></div><div style="clear:both"></div>
             <div class="username"><?=$mStateProvince?></div>
             <div class="username_text_bar"><input name="cstate" id="cstate" type="text"  value="<?=stripslashes($loggedinuser->delivery_state1)?>"  /></div><div style="clear:both"></div>
             <div class="username"><?=$mZipPostal?></div>
             <div class="username_text_bar"><input type="text" name="czip"  id="czip" value="<?=stripslashes($loggedinuser->deivery1_zip)?>"  /></div><div style="clear:both"></div>

             
             <div class="bttn"><input type="submit" name="btnupdate" id="btnupdate" value="Edit Customer Information"  /></div><div style="clear:both"></div>
                </div>
             </form>
          
         </div>
         <div style="clear:both"></div>
         <script src="../js/mask.js" type="text/javascript"></script>

<script type="text/javascript">
$(function(){
	 $('#phone1').mask("(999) 999-9999? x99999");

$("#registerationform").validate({
           rules: {
				email: {required: true, email:1 },
				user_password: {required: true,minlength: 5},
				first_name: {required: true,minlength: 3},
				last_name: {required: true,minlength: 3},
				address1: {required: true,minlength: 3},
				city: {required: true,minlength: 2},
				state: {required: true,minlength: 2},
				zip: {required: true,minlength: 3},
				phone1: {required: true,minlength: 3}
           },
           messages: {
                   email: {
							   required: "Please enter your email address",
							   email: "Please enter a valid email address"
                               },
							   user_password: {
								   required: "Please enter your password",
								   minlength: "Your password should contain at leat 5 characters"
 								 },

				  first_name: {
					   			required: "Please enter your first name",
							   minlength: "Please enter at leat 3 characters"
						   },
				   last_name: {
					   required: "Please enter your last name",
					   minlength: "Please enter at leat 3 characters"

                   },

				  address1: {
					   required: "Please enter your street",
					   minlength: "Please enter a valid street"

                   },
				 city: {
					   required: "Please enter your city",
					   minlength: "Please enter a valid city"

                   },
				 state: {
					   required: "Please enter your <?=$mStateProvince?>",
					   minlength: "Please enter a valid <?=$mStateProvince?>"

                   },
				zip: {
					   required: "Please enter your <?=$mZipPostal?>",
					   minlength: "Please enter a valid <?=$mZipPostal?>"

                   },
				 phone1: {
					   required: "Please enter your phone",
					   minlength: "Please enter a valid phone"

                   }

           },
		   errorElement: "br",
       
          errorClass: "alert-error"
});
});
</script>