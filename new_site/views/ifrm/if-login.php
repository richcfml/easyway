<?php 
$mZipPostal = "Zip Code";
$mStateProvince = "State";

if ($objRestaurant->region == "2") //Canada
{
	$mZipPostal = "Postal Code";
	$mStateProvince = "Province";
}
$result = -1;
$register_result = -1;
$errMessage = "";
if (isset($_POST['login'])) 
{
        if($email == '') {
             $result=false;
        } else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
                 $result=false;
        } else if($user_password == '') {
                     $result=false;
        }
	$user_email = $_POST['email'];
	$user = $loggedinuser->loginUser($_POST['email'], $_POST['password'], $objRestaurant->id);

 	if(is_null($user)) 
	{
		$result=false;
	} 
	else 
	{
		$loggedinuser->destroyUserSession();
		$loggedinuser = $user;

		
		if ($objRestaurant->useValutec == 1)  //ValuTec
		{
            if ($loggedinuser->valuetec_card_number > 0) 
			{
                $Balance = valutecCardBalance($loggedinuser->valuetec_card_number);
                $loggedinuser->valuetec_points = $Balance['PointBalance'];
                $loggedinuser->valuetec_reward = $Balance['Balance'];
            }
        } 
		else if ($objRestaurant->useValutec == 2)  //GO3
		{
            if ($loggedinuser->valuetec_card_number > 0) 
			{
                $loggedinuser->valuetec_points = $objGO3->go3RewardPoints($loggedinuser->valuetec_card_number);
                $loggedinuser->valuetec_reward = $objGO3->go3CardBalance($loggedinuser->valuetec_card_number);
            }
        } 
		else 
		{
            $loggedinuser->valuetec_card_number = 0;
        }

		$address1=explode('~',trim($loggedinuser->cust_odr_address,'~'));
	 
		$loggedinuser->street1=$address1[0];
		$loggedinuser->street2='';
		if(count($address1)>=1)
		{
			$loggedinuser->street2=$address1[1];
		}
		
		$address1=explode('~',trim($loggedinuser->delivery_address1,'~'));
 
		$loggedinuser->delivery_street1=$address1[0];
		$loggedinuser->delivery_street2='';
		if(count($address1)>=1)
		{
			$loggedinuser->delivery_street2=$address1[1];
		}
		$loggedinuser->saveToSession();

		$result=true;
		if($cart->isempty()) 
		{
			redirect($SiteUrl .$objRestaurant->url ."/?item=menu&ifrm=load_resturant" );exit;
		} 
		else 
		{
			redirect($SiteUrl .$objRestaurant->url ."/?item=menu&ifrm=checkout" );exit;
		}
	}
} 
else if(isset($_POST['btnregister'])) 
{
	extract($_POST);
	$mFBID='';
	if (isset($_POST["txtFBID"]))
	{
		$mFBID=$_POST["txtFBID"];
	}
        if($email == '') {
             $errMessage = "Please enter email address";
        } else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
                 $errMessage = "Please enter email address in correct format";
        } else if($user_password == '') {
                 $errMessage = "Please enter password";
        } else if(!preg_match("/^.*(?=.*\d)(?=.*[a-zA-Z]).*$/", $user_password) || strlen($user_password) < 5) {
                 $errMessage = "password must be alphanumeric with minimum 5 characters and maximum 20 character";
        } else if($first_name == '') {
                 $errMessage = "Please enter first name";
        } else if($last_name == '') {
                 $errMessage = "Please enter last name";
        } else if($address1 == '') {
                 $errMessage = "Please enter address";
        } else if($city == '') {
                 $errMessage = "Please enter city";
        } else if($state == '') {
                 $errMessage = "Please enter state";
        } else if($zip == '') {
                 $errMessage = "Please enter zip";
        } else if($phone1 == '') {
                 $errMessage = "Please enter phone";
        } else{
		$loggedinuser->	cust_email=  $email;
	$mSalt = hash('sha256', mt_rand(10,1000000));    
        $loggedinuser->salt= $mSalt;
	$loggedinuser->epassword= hash('sha256', trim($user_password).$mSalt);
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
	$register_result=$loggedinuser->customerRegistration($objRestaurant,$objMail);
	if($register_result===true)
	{
		if($cart->isempty()) 
		{
			redirect($SiteUrl .$objRestaurant->url ."/?item=menu&ifrm=load_resturant" );
			exit;
		} 
		else 
		{
			redirect($SiteUrl .$objRestaurant->url ."/?item=menu&ifrm=checkout" );
			exit;		
		}
	}
        }
} 
else if(is_numeric($loggedinuser->id)) 
{
	if($cart->isempty()) 
	{
		redirect($SiteUrl .$objRestaurant->url ."/?item=menu&ifrm=load_resturant" );
		exit;
	} 
	else 
	{
		redirect($SiteUrl .$objRestaurant->url ."/?item=menu&ifrm=checkout" );
		exit;
	}
}	
?>
<script src="../js/mask.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function(){
		var region = <?php echo $objRestaurant->region ?>;
		if (region==0) //UK
		{
			$('#phone1').unmask();
			$('#phone1').mask('(9999) 999-9999');
		}
		else //US, Canada
		{
			$('#phone1').unmask();
			$('#phone1').mask('(999) 999-9999');
		}
		$("#loginForm").validate({
				   rules: {
						email: {required: true, email:1 },
						password: {required: true,minlength: 3}
						 
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
				   errorElement: "br",
			   
				  errorClass: "alert-error"
		});
		
		$("#registerationform").validate({
           rules: {
				email: {required: true, email:1 },
				email1: {required: true, email:1 },
				user_password: {required: true,minlength: 5,maxlength: 20},
				user_password_confirm: {equalTo: "#user_password",required: true,minlength: 5,maxlength: 20},
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
							   required: "please enter your email address",
							   email: "please enter a valid email address"
                               },
					email1: {
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
                        
                   }
					   
           },
		   errorElement: "br",
       
          errorClass: "alert-error"
			
			});
	});

</script>
<table>
	<tr>
		<td valign="top" style="width: 35.5%;">
			<div id="body_left_col" style="border:#e4e4e4 1px solid; width: 97%; margin-right: 10px;">
				<form name="loginForm" id="loginForm" method="post">
				<div class="heading">Log in</div>
				<div align="center" style="text-align: center;">
				<table style="width: 100%; margin: 0px;" border="0" cellpadding="0" cellspacing="0" align="center">
					<tr>
						<td>
							<?php 
							if ($result == false) 
							{ 
							?>
							<div class="msg_warning"><span id="spnError" name="spnError" style="color: red;">Your email or password is not correct</span></div>
							<?php 
							} 
							else
							{
							?>
							<div class="msg_warning"><span id="spnError" name="spnError" style="color: red;"></span></div>
							<?php
							}
							?>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<div id="fbLogin">
								<div id="fb-root"></div>
								<script>
								window.fbAsyncInit = function() 
								{
									FB.init
									({
										appId      : '569304429756200', //597714500283054
										status     : false, // check login status
										cookie     : true, // enable cookies to allow the server to access the session
										xfbml      : true  // parse XFBML
									});
									
									FB.Event.subscribe('auth.statusChange', function(response) 
									{
										if (response.status === 'connected') 
										{
											checkAssociation();
										} 
										else if (response.status === 'not_authorized') 
										{
											FB.login(function(response) 
											{
												if (response.authResponse) // connected
												{
													checkAssociation();
												} 
												else 
												{
													// cancelled
												}
											}, { scope: 'email' });
										} 
										else 
										{
											FB.login(function(response) 
											{
												if (response.authResponse) // connected
												{
													checkAssociation();
												} 
												else 
												{
													// cancelled
												}
											}, { scope: 'email' });
										}
									});
								};
								
								// Load the SDK asynchronously
								(function(d)
								{
									var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
									if (d.getElementById(id)) {return;}
									js = d.createElement('script'); js.id = id; js.async = true;
									js.src = "//connect.facebook.net/en_US/all.js";
									ref.parentNode.insertBefore(js, ref);
								 }
								 (document));
								
								function checkAssociation() 
								{
									FB.api('/me', function (response) 
									{
										var mEmail = '';
										if (response.email)
										{
											if ((response.email != undefined))
											{
												mEmail = response.email;
											}
										}
										var url = '';
										var mRandom = Math.floor((Math.random()*1000000)+1); 
										url="<?=$SiteUrl?><?=$objRestaurant->url?>/?item=checkfbid&checkfbid=1&fbid="+response.id+"&email="+myURLEncode(mEmail)+"&rndm="+mRandom;
	
										$.ajax
										({
											url: url,
											cache: false,
											type: "POST",
											success: function(data)
													 {
														mResult = data.substring(data.indexOf('<ewo_result>')+12);
														mResult = mResult.substring(0, mResult.indexOf('</ewo_result>'));
		
														if (mResult<0) //Error
														{
															document.getElementById("spnError").innerHTML = "Error occurred.<br />Cannot login to Easyway Ordering.";
														}
														else if (mResult==0) //No Associated Account
														{
															document.getElementById("txtFBID").value = response.id;
															document.getElementById("email1").value = mEmail;
															document.getElementById("first_name").value = response.first_name;
															document.getElementById("last_name").value = response.last_name;
															
															if (response.location)
															{
																if (response.location.name)
																{
																	var mLoc = response.location.name;
																	if (mLoc.indexOf(',')>=0)
																	{
																		var mLocA = mLoc.split(',');
																		document.getElementById("city").value = $.trim(mLocA[0]);
																		document.getElementById("state").value = $.trim(mLocA[1]);
																		document.getElementById("scity").value = $.trim(mLocA[0]);
																		document.getElementById("cstate").value = $.trim(mLocA[1]);
																	}
																	else
																	{
																		document.getElementById("city").value = mLoc;
																		document.getElementById("scity").value = mLoc;
																	}
																}
															}
		
															
															document.getElementById("spnError").innerHTML = 'Since this is your first time, please complete the rest of the form and click "register account"';

															$("#user_password").addClass("alert-errorG");
															$("#user_password_confirm").addClass("alert-errorG");
															
															if ($("#email1").val()=="")
															{
																$("#email1").addClass("alert-errorG");
															}
															
															if ($("#first_name").val()=="")
															{
																$("#first_name").addClass("alert-errorG");
															}
															
															if ($("#last_name").val()=="")
															{
																$("#last_name").addClass("alert-errorG");
															}
															
															if ($("#address1").val()=="")
															{
																$("#address1").addClass("alert-errorG");
															}
															
															if ($("#city").val()=="")
															{
																$("#city").addClass("alert-errorG");
															}
															
															if ($("#state").val()=="")
															{
																$("#state").addClass("alert-errorG");
															}
															
															if ($("#zip").val()=="")
															{
																$("#zip").addClass("alert-errorG");
															}
															
															if ($("#phone1").val()=="")
															{
																$("#phone1").addClass("alert-errorG");
															}
															//document.getElementById("btnregister").click();
														}
														else if (mResult>0) //EWO Associated Account
														{
															var itemCount = <?=$cart->totalItems()?>;
															if (itemCount<=0)
															{
																window.location ="<?php echo($SiteUrl.$objRestaurant->url); ?>/?item=load_resturant&ifrm=load_resturant";
															}
															else
															{
																window.location ="<?php echo($SiteUrl.$objRestaurant->url); ?>/?item=menu&ifrm=checkout";
															}
														}
													 },
											 error: function (jqXHR, textStatus, errorThrown) 
													{
														document.getElementById("spnError").innerHTML = "Error occurred.<br />Cannot login to Easyway Ordering.";
														//alert(jqXHR.status);
														//alert(textStatus);
													}
										});
									});
								}
		
								function myURLEncode(str) 
								{
									str = (str + '').toString();
									return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+').replace('.', '%2E');
								}
								
								</script>
								<img src="../images/fb.png" style="cursor: hand; cursor: pointer;" align="Login with Facebook" title="Login with Facebook"  onclick="FB.login();"/>
							</div>
						</td>
					</tr>
					<tr style="height: 15px;">
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<table style="width: 100%;" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td style="width: 10%;">
									</td>
									<td style="width: 35%;">
										<hr style="color: #cccccc;" />
									</td>
									<td style="width: 10%;" align="center">
										OR
									</td>
									<td style="width: 35%;">
										<hr style="color: #cccccc;" />
									</td>
									<td style="width: 10%;">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: 15px;">
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<input name="email" id="email" type="text" value="" style="width: 270px;" placeholder="Email Address"/>
						</td>
					</tr>
					<tr style="height: 8px;">
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<input name="password" id="password" type="password" value="" style="width: 270px;" placeholder="Password"/>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td>
						</td>
					</tr>
					<tr>
						<td style="font-size: 12px;">
							Forget your password? <a href="?item=forgotpassword&ifrm=forgotpassword" style="color:#03F; font-size:12px;">Click Here</a>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td>
						</td>
					</tr>
					<tr>
						<td>
							<style type="text/css">
								.btnLogin {
									margin-right:5px
								}
								.btnLogin {
									display:inline-block;
									-webkit-border-radius:3px;
									-moz-border-radius:3px;
									border-radius:3px;
								}
								.btnLogin {
									white-space:nowrap;
									nowhitespace:afterproperty;
									line-height:1em;
									position:relative;
									outline:none;
									overflow:visible;
									cursor:pointer;
									nowhitespace:afterproperty;
									border:1px solid #999;
									border:rgba(0, 0, 0, .1) 1px solid;
									border-bottom:rgba(0, 0, 0, .3) 1px solid;
									nowhitespace:afterproperty;
									background:-moz-linear-gradient(center top, rgba(255, 255, 255, .1) 0%, rgba(0, 0, 0, .1) 100%);
									background:-webkit-gradient(linear, center bottom, center top, from(rgba(0, 0, 0, .1)), to(rgba(255, 255, 255, .1)));
									filter:progid:DXImageTransform.Microsoft.gradient(startColorStr='#19FFFFFF', EndColorStr='#19000000');
									-ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorStr='#19FFFFFF',EndColorStr='#19000000')";
									-moz-user-select:none;
									-webkit-user-select:none;
									-khtml-user-select:none;
									user-select:none;
									margin-bottom:10px;
									font-family:'Helvetica Neue', Arial, sans-serif;
									font-size:11px;
									min-height:34px;
									text-decoration:none;
								}
								
								.btnLogin:hover {
									opacity:0.8
								}
								
								.btnLogin:active  {
									top:1px
								}
								.btnLogin {
									position:relative;
									color:#fff;
									font-weight:bold;
									text-shadow:0 1px 1px rgba(0, 0, 0, 0.25);
									border-top:rgba(255, 255, 255, .4) 1px solid;
									padding:0.8em 1.3em;
									line-height:1.3em;
									text-decoration:none;
									text-align:center;
									white-space:nowrap;
								}
								.btnLogin{
									background-color: #025D8C;
									width:270px;
									height: 35px;
								}
								
								.alert-errorG {
									background-color:#92d992;
									border-color:#eed3d7;
									text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);
									-webkit-border-radius:4px;
									-moz-border-radius:4px;
									border-radius:4px;
								}
							</style>
							<input  name="login" id="login" class="btnLogin"  type="submit"   value="Log In" />
						</td>
					</tr>
					<tr style="height: 20px;">
						<td>
						</td>
					</tr>
				</table>
				</div>   
				</form>
			</div>
		</td>
		<td valign="top" style="width: 35.5%;">
			<div id="body_center_col" style="width: 98%; margin: 0 10px 0 0;">
			 <form name="registerationform" id="registerationform" method="post">
					 <div class="heading">New Users</div>
					 <? if ($errMessage != ""){?>
					<div class="msg_warning"><font color="#FF3333"><?=$errMessage?></font></div>
					<? }?>
					  <? if ($register_result===false){?>
						   <div class="msg_warning"> Sorry, registeration failed please try again.</div>
						   
							  <? } ?>
							   <? if ($register_result===0){?>
									<div class="msg_warning"> This email is already registerd with easywayordering.com/<?= $objRestaurant->url ?>. Please <a href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?item=forgotpassword&ifrm=forgotpassword">Click Here</a> if you forgot your passowrd.</div>
						   
							  <? } ?>
			  <input type="hidden" id="txtFBID" name="txtFBID"/>
					 <div class="username_text_bar">
						 <input name="email" id="email1" type="text" value="<?=stripslashes($email)?>" placeholder="Email Address"/>
					 </div>
					 <div style="clear:both"></div>
					 <div class="username_text_bar">
					   <input name="user_password" id="user_password" type="password" value="<?=stripslashes($user_password)?>" placeholder="Password"/>
					 </div><div style="clear:both"></div>
					 <div class="username_text_bar">
					   <input name="user_password_confirm" id="user_password_confirm" type="password" value="" placeholder="Confirm Password"/>
					 </div><div style="clear:both"></div>
					 <div class="username_text_bar">
					   <input id="first_name" name="first_name" type="text" value="<?=stripslashes($first_name)?>" placeholder="First Name"/>
					 </div><div style="clear:both"></div>
					 <div class="username_text_bar">
						 <input name="last_name" id="last_name" type="text" value="<?=stripslashes($last_name)?>" placeholder="Last Name"/>
					 </div><div style="clear:both"></div>
					 <div class="username_text_bar"><input name="address1" id="address1" type="text" value="<?=stripslashes($address1)?>"  placeholder="Street1"/></div><div style="clear:both"></div>
					 <div class="username_text_bar"><input name="address2" id="address2" type="text" value="<?=stripslashes($address2)?>" placeholder="Street2"/></div><div style="clear:both"></div>            
					 <div class="username_text_bar"><input name="city" id="city" type="text" value="<?=stripslashes($city)?>"  placeholder="City"/></div><div style="clear:both"></div>
					 <div class="username_text_bar"><input name="state" id="state" type="text" value="<?=stripslashes($state)?>"  placeholder="<?=$mStateProvince?>"/></div><div style="clear:both"></div>
					 <div class="username_text_bar"><input name="zip" id="zip" type="text" value="<?=stripslashes($zip)?>"  placeholder="<?=$mZipPostal?>"/></div><div style="clear:both"></div>
					 <div class="username_text_bar"><input type="text" name="phone1" id="phone1" value="<?=stripslashes($phone1)?>"  placeholder="Phone"/></div><div style="clear:both"></div>
					 <div style="display: none;"> <!-- Alternate Delivery Address no longer needed -->
						 <span style="font-size:12px; padding-left:10px;" ><font color="#FF0000">Alternate Delivery Address optional:</font></span><div style="clear:both"></div>
						 <div class="username">Street1</div>
						 <div class="username_text_bar"><input name="saddress1" id="saddress1" type="text" value="<?=stripslashes($delivery_address1)?>" /></div><div style="clear:both"></div>  
						 <div class="username">Street2</div>
						 <div class="username_text_bar"><input name="saddress2" id="saddress2" type="text" value="<?=stripslashes($delivery1_address1)?>"  /></div><div style="clear:both"></div>            
						 <div class="username">City</div>
						 <div class="username_text_bar"><input name="scity" id="scity" type="text" value="<?=stripslashes($delivery_city1)?>" /></div><div style="clear:both"></div>
						 <div class="username">State</div>
						 <div class="username_text_bar"><input name="cstate" id="cstate" type="text"  value="<?=stripslashes($delivery_state1)?>"  /></div><div style="clear:both"></div>
						 <div class="username">Zip Code</div>
						 <div class="username_text_bar"><input type="text" name="czip"  id="czip" value="<?=stripslashes($deivery1_zip)?>"  /></div><div style="clear:both"></div>
					</div>
					 
					 <div class="bttn"><input type="submit" name="btnregister" id="btnregister" value="Register Account"  /></div><div style="clear:both"></div>
				</form>
			</div>	
		</td>
		<td valign="top" style="width: 29%;">
		 <div id="body_right_col">
             <div id="cart">
             <?php 
 				$without_loggin=1;
			 	if (isset($_POST['btncheckout'])) 
				{
					if ($_POST['btncheckout'] == 'Pickup') 
					{
						$cart->setdelivery_type(cart::Pickup);
					} 
					else 
					{
						$cart->setdelivery_type(cart::Delivery);
					}
				}
                require($site_root_path . "views/ifrm/if-cart.php");
              ?>
			  </div>
		  </div>
		</td>
	</tr>
</table>
	
   
 	<div style="clear:left"></div>
         
 
