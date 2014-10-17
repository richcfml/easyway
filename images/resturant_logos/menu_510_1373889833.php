<?
	session_start();
	ini_set("display_errors", 1);
	include("../includes/config.php");
	include("../includes/class.phpmailer.php");
	include("../includes/function.php");
	require_once('chargify-api/Chargify.php');
	$function_obj = new clsFunctions();
	
	$test_mode = false;
	$active_api_key = "2aRl08rsgL3H3WiWl5ar";
	$active_domain = "easywayordering";
	
	$chargify = new ChargifyConnector($test_mode, $active_domain, $active_api_key);
	
	
	$customer_id = "3399495";
	$customer_x = $chargify->getCustomerByID($customer_id);
	
	//echo '<h2>Single customer object by ID</h2>';
	//echo "<pre>"; print_r($customer_x); echo "</pre>";
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Self Signup Wizard | EasyWay Ordering</title>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.css">
	<link type="text/css" rel="stylesheet" href="css/style.css">

	<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</head>

<body>
<div class="header_2"><a href="#"><img src="images/small.jpg" border="0" /></a></div>
<div class="content">
	<div class="container">
		<div class="tabs-cont clearfix">
			<div class="left-col">
				<div class="first">
					<span class="tile selected first" name="" id="tab_1"></span>
					<span class="tab_label selected tab_label_1">Create Your Account</span>
				</div>
				<div>
					<span class="tile" name="" id="tab_2"></span>
					<span class="tab_label">Restaurant Information</span>
				</div>
				<div>
					<span class="tile" name="" id="tab_3"></span>
					<span class="tab_label">Order settings</span>
				</div>
				<div>
					<span class="tile" name="" id="tab_4"></span>
					<span class="tab_label">Menus</span>
				</div>
				<div>
					<span class="tile" name="" id="tab_5"></span>
					<span class="tab_label">Payment Setting</span>
				</div>
				<div>
					<span class="tile last" name="" id="tab_6"></span>
					<span class="tab_label">Setup Instructions</span>
				</div>
			</div><!--/tab nav end-->
			
			<div class="clearfix center-col">
				<div class="tab1" name="" id="tab1">
					<div class="reg-div" name="" id="reg-div">
						<h3>Sign Up</h3>
						<div class="reg_table_div">
							<table class="reg-table" width="100%" cellpadding="0" cellspacing="0" align="">
								<tr>
									<td width="50%">
										<label for="client_firstname">First Name: <span class="starig">*</span></label>
										<input type="text"  id="client_firstname" name="client_firstname" />
									</td>
									<td width="50%">
										<label for="client_lastname"> Last Name: <span class="starig">*</span></label>
										<input type="text" id="client_lastname" name="client_lastname"/>
									</td>
								</tr>
								<tr>
									<td>
										<label for="client_phone">Phone: <span class="starig">*</span></label>
										<input type="text" id="client_phone" name="client_phone"/>
									</td>
									<td>
										<label for="client_email">Email: <span class="starig">*</span></label>
										<input type="text" id="client_email" name="client_email"/>
									</td>
								 </tr>
								 <tr>
									<td>
										<label for="client_username">User Name: <span class="starig">*</span></label>
										<input type="text" name="client_username" id="client_username" />
									</td>
									<td>
										<label for="client_pass">Password: <span class="starig">*</span></label>
										<input type="text" name="client_pass" id="client_pass" />
									</td>
								 </tr>
								 <tr>
									<td>
										<label for="client_conformpass">Conform Password: <span class="starig">*</span></label>
										<input type="text" name="client_conformpass" id="client_conformpass" />
									</td>
									<td>
										<label for="client_country">Country: <span class="starig">*</span></label>
										<select name="client_country" id="client_country">
											<option>Select Country</option>
											<option>item 1</option>
											<option>item 2</option>
										</select>
									</td>
								 </tr>
								 <tr>
									
									<td>
										<label for="client_state">State: <span class="starig">*</span></label>
										<input type="text" name="client_state" id="client_state" />
									</td>
									<td>
										<label for="client_city">City: <span class="starig">*</span></label>
										<input type="text" name="client_city" id="client_city"/>
									</td>
								 </tr>
								 <tr>
									
									<td>
										<label for="client_zip">Zip: <span class="starig">*</span></label>
										<input type="text" name="client_zip" id="client_zip" />
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td colspan="2">
										Alredy User ? <a href="#" class="link" name="" id="login-id" >Signin</a>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="login-div clearfix" name="" id="login-div">
						<h3>Login</h3>
						<div class="reg_table_div">
							<table class="reg-table" width="100%" cellpadding="0" cellspacing="0" align="">
								<tr>
									<td>
										<label for="client_usrName">Username: <span class="starig">*</span></label>
										<input type="text" name="client_usrName" id="client_usrName"/>
									</td>
								</tr>
								<tr>
									<td>
										<label for="client_password1">Password: <span class="starig">*</span></label>
										<input type="text" name="client_password1" id="client_password1" />
									</td>
								</tr>
								<tr>
									<td>Don't have a account? <a href="#" class="link" name="" id="reg-id" >Sign Up</a></td>
								</tr>
							</table>
						</div>
					</div>
				</div><!--/tab one container end-->
				
				<div class="tab2" name="" id="tab2">
					<h3>Restaurant Information</h3>
					<div class="reg_table_div">
						<table class="reg-table" width="100%" cellpadding="0" cellspacing="0" align="">
							<tr>
								<td width="50%">
									<label for="client_resturantname">Restaurant Name: <span class="starig">*</span></label>
									<input type="text" name="client_resturantname" id="client_resturantname"/>	
								</td>
								<td width="50%">
									<label for="client_rest_phone">Restaurant Phone: <span class="starig">*</span></label>
									<input type="text" />
								</td>
							</tr>
							<tr>
								<td>
									<label for="client_mail">Email: <span class="starig">*</span></label>
									<input type="text" name="client_mail" id="client_mail"/>
								</td>
								<td>
									<label for="client_fax">Fax: <span class="starig">*</span></label>
									<input type="text" name="client_fax" id="client_fax" />
								</td>
							</tr>
							<tr>
								<td>
									<label for="client_conformpassword">Conform Password: <span class="starig">*</span></label>
									<input type="text" name="client_conformpassword" id="client_conformpassword" />
								</td>
								<td>
									<label for="client_reseller">Reseller: <span class="starig">*</span></label>
									<select name="client_reseller" id="client_reseller">
										<option>Select Reseller</option>
										<option>item 1</option>
										<option>item 2</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label for="client_coun">Country: <span class="starig">*</span></label>
									<select name="client_coun" id="client_coun">
										<option>Select Country</option>
										<option>item 1</option>
										<option>item 2</option>
									</select>
								</td>
								<td>
									<label for="client_state1">State: <span class="starig">*</span></label>
									<input type="text" name="client_state1" id="client_state1"/>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label for="client_timezone">Select Time Zone: <span class="starig">*</span></label>
									<select name="client_timezone" id="client_timezone">
										<option>Select Here...</option>
										<option>Item 1</option>
										<option>Item 2</option>
									</select>
								</td>
							</tr>
						</table>
					</div>
				</div><!--/tab two container end-->
				
				<div class="tab3" name="" id="tab3">
					<h3>Order Settings</h3>
					<div class="reg_table_div">
						<table width="100%" class="reg-table">
							<tr>
								<Td>
									<strong>Will you receive orders via Fax, Email or POS?</strong><br />
									<input type="radio" name="FEP" value="2" name="fax" id="fax" /><label for="fax"> Fax &nbsp;&nbsp;&nbsp;&nbsp;</label>
									<input type="radio" name="FEP" value="1" name="email" id="email" /><label for="email"> Email &nbsp;&nbsp;&nbsp;&nbsp;</label>
									<input type="radio" name="FEP" value="0" name="pos" id="pos" /><label for="pos">POS &nbsp;&nbsp;&nbsp;&nbsp;</label>
									<div class="faxDiv1" name="FaxDivId" id="FaxDivId">
									<input type="text" placeholder="Enter Fax Number..." />
									</div>
									<div class="faxDiv" name="EmailDivId" id="EmailDivId">
									<input type="text" placeholder="Enter Your Email..." />
									</div>
									<div class="faxDiv" name="POSDivId" id="POSDivId">
									Your Online Ordering Coach will configure this for you during your training call.
									</div>
									
								</Td>
							</tr>
							<tr>
								<td>
									<label for="client_orderminimum">Order Minimum: <span class="starig">*</span></label>
									<input type="text" name="client_orderminimum" id="client_orderminimum"/>
								</td>
							</tr>
							<tr>
								<td>
									<label for="client_salextex">Sales Tax %: <span class="starig">*</span></label>
									<input type="text" name="client_salextex" id="client_salextex" />
								</td>
							</tr>
							<tr>
								<td>
									<strong>Do you offer delivery (yes / no):</strong> <span class="starig">*</span><br />
									 <input type="radio" name="delivery" name="del_yes" id="del_yes" checked="checked"/><label for="del_yes"> Yes &nbsp;&nbsp;&nbsp;&nbsp;</label>
									 <input type="radio" name="delivery" name="del_no" id="del_no" /> <label for="del_no"> No &nbsp;&nbsp;&nbsp;&nbsp;</label>
									 <div class="faxDiv1" name="" id="del_yes_div">
										<strong>Delivery Option</strong><br />
										<input type="radio" name="yes" name="delRad" id="delRad" checked="checked"/><label for="delRad"> Delivery Radius &nbsp;&nbsp;&nbsp; </label>
										<input type="radio" name="yes" name="cusDelZon" id="cusDelZon" /><label for="cusDelZon"> Custome Delivery Zone&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<div class="faxDiv1" name="" id="delRadDiv">
											<label for="client_diliveryRadius">Dilivery Radius for Resturant:</label>
											<input type="text" name="client_diliveryRadius" id="client_diliveryRadius"/>
											</div>
											<div class="delRed" name="" id="cusDelZonDiv"><img src="images/cus_img.png" /></div>
									 </div>
									 <div class="del_no" name="" id="del_no_div">
									 <!--no-->
									 </div>
								</td>
							</tr>
						</table>
					</div>
				</div><!--/tab three container end-->
				
				<div class="tab4" name="" id="tab4">
					<h3>Menus</h3>
					<div class="reg_table_div">
						<table width="100%" class="reg-table">
							<tr>
								<td>
									<input type="radio" name="menue" name="uploadMenu" id="uploadMenu" checked="checked" /><label for="uploadMenu"> Upload your menus &nbsp;&nbsp;&nbsp;&nbsp;</label>
								</td>
								<td>
									<input type="radio" name="menue" name="uploadMenu2" id="uploadMenu2" /><label for="uploadMenu2"> Fax your menu to 877-211-0213 &nbsp;&nbsp;&nbsp;&nbsp;</label>
								</td>
								<Td>
									<input type="radio" name="menue" name="uploadMenu3" id="uploadMenu3" /><label for="uploadMenu3">rint fax cover sheet &nbsp;&nbsp;&nbsp;&nbsp;</label>    
								</Td>
							</tr>
						</table>
					</div>
					<div class="uploadMenuDiv" name="" id="uploadMenuDiv">
						<div class="reg_table_div">	
							<table width="100%" class="reg-table">
								<tr>
									<td>
										<label for="client_menuName">Menu Name:</label>
										<input type="text" name="client_menuName" id="client_menuName"/> 
									</td>
								</tr>
								<tr>
									<td>
										<label for="client_upload">Upload (browse for file on PC):</label>
										<input type="file" name="client_upload" id="client_upload" />
                                    </td>
                                 <tr>
                                 	<td>    
										<label for="client_uploadAnother">Upload another menu file:</label>
										<input type="file" name="client_uploadAnother" id="client_uploadAnother" /> <br />
									</td>
								</tr>
								<tr>
									<td>
										<label for="client_specialInst">Special instructions:</label>
										<textarea rows="3" name="client_specialInst" id="client_specialInst"></textarea>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="uploadMenuDiv" name="" id="uploadMenuDiv2">
						<div class="reg_table_div">
							<table width="100%" class="reg-table">
								<tr>
								<Td>
									<label for="client_faxYourMenue">Fax your Menue:</label>
									<input type="text" name="client_faxYourMenue" id="client_faxYourMenue" />    
								</Td>
								</tr>
							</table>
							
						</div>
					</div>
					<div class="uploadMenuDiv" name="" id="uploadMenuDiv3">
						<div class="reg_table_div">
							<table width="100%" class="reg-table" align="">
								<tr>
									<Td width="50%">
										<label for="client_restName">Restaurant Name:</label>
										<input type="text" name="client_restName" id="client_restName"/>
									</Td>
									<td width="50%">
									<label for="client_contactName">Contact Name: <span class="starig">*</span></label>
									<input type="text" name="client_contactName" id="client_contactName"/>
								</td>
								</tr>
								<tr>
									<td>
										<label for="client_phone2">Phone: <span class="starig">*</span></label>
										<input type="text" name="client_phone2" id="client_phone2" />
									</td>                       
									 <td>
										<label for="client_country2">Country: <span class="starig">*</span></label>
										<select name="client_country2" id="client_country2">
											<option>Select Country</option>
											<option>item 1</option>
											<option>item 2</option>
										</select>
									</td>
								</tr>
								
								<tr>
									<td>
										<label for="client_state2">State: <span class="starig">*</span></label>
										<input type="text" name="client_state2" id="client_state2" />
									</td>
									<td>
										<label for="client_city2">City: <span class="starig">*</span></label>
										<input type="text" name="client_city2" id="client_city2" />
									</td>
								</tr>
								<tr>
									<td>
										<label for="client_zip2">Zip: <span class="starig">*</span></label>
										<input type="text" name="client_zip2" id="client_zip2"/>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div><!--/tab four container end-->
				
				<div class="tab5" name="" id="tab5">
					<h3>Payment Setting</h3>
					<div class="reg_table_div">
						<table width="100%" class="reg-table">
							<tr>
								<td>
								<strong>Will you accept cash (yes / no):</strong><br />
								<input type="radio" name="cash" name="cash_yes" id="cash_yes" checked="checked"/><label for="cash_yes"> Yes &nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="radio" name="cash" name="cash_no" id="cash_no" /><label for="cash_no"> No &nbsp;&nbsp;&nbsp;&nbsp;</label>
								<div class="faxDiv1" name="" id="cashDivYes">
									<input type="radio" name="cash1" name="cash1_1" id="cash1_1"/> <label for="cash1_1"> I already have a [credit card processor name] merchant account for online transactions.<br /></label>
									<input type="radio" name="cash1" name="cash1_2" id="cash1_2"/> <label for="cash1_2">I would like to set up a new [credit card processor name] merchant account for online transactions.<br /></label>
									<input type="radio" name="cash1" name="cash1_3" id="cash1_3"/> <label for="cash1_3 ">I will not accept credit cards on my website.<br /></label>
								</div>
								<div class="cashDiv" name="" id="cashDivNo">no</div>
								</td>
							</tr>
						</table>
					</div>
				</div><!--/tab five container end-->
				
				<div class="tab6" name="" id="tab6">
					<h3>Setup Instructions</h3>
					<div class="reg_table_div">
						<table width="100%" class="reg-table">
							<tr>
								<td>
									<strong>Domain Name of your website</strong> <br />
									<div>       
										<input type="radio" name="domainName" name="domainName1" id="domainName1" checked="checked" />  <label for="domainName1"> Already have a domain name. &nbsp;&nbsp;&nbsp;</label>
										<input type="radio" name="domainName" name="domainName2" id="domainName2" />  <label for="domainName2">I would like a new domain name. &nbsp;&nbsp;&nbsp;</label>
									</div>
									<div class="faxDiv1" name="" id="domainDiv2">
										<table width="100%" class="reg-table step_6_table">
											<tr>
												<td>
												<label for="client_domainName">Enter domain name:</label>
												<input type="text" name="client_domainName" id="client_domainName"/>
												</td>
											</tr>
										</table>
									</div>
									<div class="cashDiv" name="" id="domainDiv3">
										<table width="100%" class="reg-table step_6_table">
											<tr>
												<td>
												<label for="client_desireDomainName">Enter desired domain name:</label>
												<input type="text" name="client_desireDomainName" id="client_desireDomainName"/>
												</td>
											</tr>
										</table>
									</div>
									<br />
									
									<strong>Website Integration</strong> <br /> 
									<input type="radio" name="wesbite_integration" name="wesbite_integration1" id="wesbite_integration1"  checked="checked"/> <label for="wesbite_integration1"> I am adding the EasyWay Ordering menu to my existing website <br /></label>
									<input type="radio" name="wesbite_integration" name="wesbite_integration2" id="wesbite_integration2" /> <label for="wesbite_integration2"> I will use the EasyWay Ordering menu as my website <br /></label>
									<input type="radio" name="wesbite_integration" name="wesbite_integration3" id="wesbite_integration3" /> <label for="wesbite_integration3"> I would like EasyWay to build me a custom website <br /></label>
									<br />
									
									<strong> Hosting Information</strong> <br /> 
									<input type="radio" name="wesbite_integration" name="hosting_info1" id="hosting_info1" /> I would like EasyWay to configure my hosting account and or integrate the ordering page with my website. <br />
									
									<div class="faxDiv1" name="" id="hosting_div1">
										<table width="100%" class="reg-table step_6_table">
											<tr>
												<td>
													<label for="client_hostingName">Name of hosting company (ex GoDaddy):</label>
													<input type="text" name="client_hostingName" id="client_hostingName"/>
												</td>
											</tr>
											<tr>
												<td>
													<label for="client_accountNumber">Account number or username:</label>
													<input type="text" name="client_accountNumber" id="client_accountNumber"/>
												</td>
											</tr>
											<tr>
												<td>
													<label for="client_accountPassword">Account Password:</label>
													<input type="password" name="client_accountPassword" id="client_accountPassword" />
												</td>
											</tr>
										</table>
									</div>
									<input type="radio" name="wesbite_integration" name="hosting_info2" id="hosting_info2" /> My web designer will apply the necessary updates <br />
									<div class="cashDiv" name="" id="hosting_div2">
										<table width="100%" class="reg-table step_6_table">
											<tr>
												<td>
												<label for="client_hostName">Name:</label>
												<input type="text" name="client_hostName" id="client_hostName"/>
												</td>
											</tr>
											<tr>
												<td>
												<label for="client_hostPhone">Phone:</label>
												<input type="text" name="client_hostPhone" id="client_hostPhone"/>
												</td>
											</tr>
											<tr>
												<td>
												<label for="client_hostEmail">Email address:</label>
												<input type="password" name="client_hostEmail" id="client_hostEmail"/>
												</td>
											</tr>
											
										</table>
									</div>
									<input type="radio" name="wesbite_integration" name="hosting_info3" id="hosting_info3" /> <label for="hosting_info3"> I will apply the updates myself <br /></label>
									<div class="cashDiv" name="" id="hosting_div3"></div>
								</td>
							</tr>
						</table>
					</div>
				</div><!--/tab six container end-->
				
				<div class="btn-hoder">
					<input type="button" value="&laquo; Previous" name="btn_previous" id="btn_previous" class="active-btn" style="display: none;"/>
					<input type="submit" value="Skip" name="btn_skip" id="btn_skip" class="active-btn"  style="display: none;"/>
					<input type="button" value="Next &raquo;" name="btn_next" id="btn_next" class="active-btn"/>
					<input type="submit" value="Submit" name="btn_submit" id="btn_submit" class="active-btn" style="display: none;" />
				</div><!--/tab button nav container end-->
			</div>
			<div class="right-col">
				<h3>Description</h3>
				<p>
				"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
				</p>
			</div>
		</div>
	</div>
</div>
</body>
</html>
