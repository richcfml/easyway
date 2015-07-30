<?php
	require_once("../includes/config.php");
	include("../includes/class.phpmailer.php");
	require_once('chargify-api/Chargify.php');
	$function_obj = new clsFunctions();
	$objMail = new testmail();

	session_destroy();
	
	$error = "";
	if(!empty($_POST["btn_submit"])) {
		require("submit_handler.php");
	}
        else {
		if(!empty($_GET["customer_id"]) && !empty($_GET["product_id"]) && !empty($_GET["subscription_id"])) {
			$chargify_customer_id = $_GET["customer_id"];
			$chargify_product_id = $_GET["product_id"];
			$premium_account_qry = dbAbstract::Execute("SELECT * from chargify_products where product_id = $chargify_product_id");
                        $premium_account = dbAbstract::returnArray($premium_account_qry);
                        if (isset($premium_account['premium_account']) && $premium_account['premium_account'] == "1") {
                            $premium_account_true = true;
                        } else {
                            $premium_account_true = false;
                        }
			$chargify_subscription_id = $_GET["subscription_id"];
			// $chargify_customer_id=3440525;
			// $chargify_product_id=3318535;
			// $chargify_subscription_id=3591928;
			
			// check if chargify subscription id available
			$restaurant_count = dbAbstract::Execute("SELECT COUNT(*) AS count FROM resturants WHERE chargify_subscription_id=$chargify_subscription_id");
			$restaurant_count = dbAbstract::returnAssoc($restaurant_count);
			$restaurant_count = $restaurant_count["count"];
			if($restaurant_count < 1) {
				$reseller_id = 0;
				$active_domain = "";
				$reseller_company_logo = "";
				$active_api_key = "";
				
				$reseller = dbAbstract::Execute("
					SELECT cp.user_id, cp.hosted_page_url, u.company_logo, api_access_key 
					FROM chargify_products cp 
					LEFT JOIN users u
						ON u.id=cp.user_id
					WHERE product_id=$chargify_product_id"
				);
				if(dbAbstract::returnRowsCount($reseller) > 0) {
					$reseller = dbAbstract::returnAssoc($reseller);
					$reseller_id = $reseller["user_id"];
					$active_domain = $reseller["hosted_page_url"];
					$reseller_company_logo = $reseller["company_logo"];
					$active_api_key = $reseller["api_access_key"];
					$active_domain = substr($active_domain, 8, strpos($active_domain, '.') - strlen($active_domain));
				} else {
					//die("Provided data is incomplete");
				}

				$test_mode = false;
				
				$reseller_credit_card_processor	= false;
				$chargify = new ChargifyConnector($test_mode, $active_domain, $active_api_key);
				$customer = $chargify->getCustomerByID($chargify_customer_id);	
				if(empty($customer->first_name)) {
					$error = "Unable to connect to chargify";
				}
			} else {
				$error = "This chargify subscription id is not available.";
			}
		} else {
			$error = "Incomplete data provided, Chargify product id, subscription id and customer id are required.";
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Self Signup Wizard | EasyWay Ordering</title>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.css" />
	<link type="text/css" rel="stylesheet" href="css/style.css" />
        <script>
            premium_account = false;
	<?php if($premium_account_true){ ?>
            premium_account = true;
        <?php }?>
        </script>
	<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../js/jquery.validate.js"></script>
	<script type="text/javascript" src="../js/mask.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript">
		// jQuery(function($) {
			// $('#client_phone').mask('(999) 999-9999');
			// $('#restaurant_phone').mask('(999) 999-9999');
			// $('#restaurant_fax').mask('(999) 999-9999');
		// });
	</script>
    <script>
	function open_win()
	{
		//var date = new Date();
		var clntfirstname = document.getElementById('client_firstname').value;
		var clntlasttname = document.getElementById('client_lastname').value;
		var restphone = document.getElementById('restaurant_phone').value;
		var restfax = document.getElementById('restaurant_fax').value;
		var restname = document.getElementById('restaurant_name').value;
		
		window.open('coversheet.php?fn='+clntfirstname+'&ln='+clntlasttname+'&restph='+restphone+'&resfax='+restfax+'&restname='+restname, '_blank')
	}
	</script>
	<script type="text/javascript">


    $(document).ready(function() {

            jQuery("#region").attr('checked', 'checked');
            $('#client_phone').unmask();
            $('#restaurant_fax').unmask();
            $('#restaurant_phone').unmask();
            $('#client_phone').mask('(999) 999-9999');
            $('#restaurant_fax').mask('(999) 999-9999');
            $('#restaurant_phone').mask('(999) 999-9999');


            $("#restaurant_timezone").children('option:contains("London")').hide();
            $("#restaurant_timezone").children('option:contains("America")').show();
            $("#restaurant_timezone").val(-1);


//
//    var region = <?php echo $Objrestaurant->region ?>;
//        if(region==1)
//        {
//           $("#time_zone").children('option:contains("America")').show();
//        $("#time_zone").children('option:contains("London")').hide();
//        $("#time_zone").val(-1);
//        }
//        else if(region==0)
//            {
//                $("#time_zone").children('option:contains("America")').hide();
//        $("#time_zone").children('option:contains("London")').show();
//        $("#time_zone").val(-1);
//            }
});

    function loadTimeZoneUS()
        {
            $('#client_phone').unmask();
            $('#restaurant_fax').unmask();
            $('#restaurant_phone').unmask();
            $('#client_phone').mask('(999) 999-9999');
            $('#restaurant_fax').mask('(999) 999-9999');
            $('#restaurant_phone').mask('(999) 999-9999');


            $("#restaurant_timezone").children('option:contains("London")').hide();
            $("#restaurant_timezone").children('option:contains("America")').show();
            $("#restaurant_timezone").val($("#restaurant_timezone").children('option:contains("America")').first().val());
}

function loadTimeZoneUK()
{
        $('#client_phone').unmask();
        $('#restaurant_fax').unmask();
        $('#restaurant_phone').unmask();
        $('#client_phone').mask('(9999) 999-9999');
        $('#restaurant_fax').mask('(9999) 999-9999');
        $('#restaurant_phone').mask('(9999) 999-9999');

        $("#restaurant_timezone").children('option:contains("America")').hide();
        $("#restaurant_timezone").children('option:contains("Europe")').show();
        $("#restaurant_timezone").val($("#restaurant_timezone").children('option:contains("Europe")').first().val());
}



</script>
    <style>
        .tab_label {
            width:185px;
        }
    </style>
</head>

<body>
<div class="cont">    
<div class="header_2">
	<!--<img src="/images/logos_thumbnail/<? echo $reseller_company_logo; ?>" border="0" />-->
	<img src="small.jpg" border="0" />
	<? if($error == "") { ?>
	<!--<div style="float: right; width: 550px; padding-top: 10px;">
		<p>-->
    <div class="wizrd_txt_div">
        <div class="wizrd_txt_cont">
		This wizard will guide you through the restaurant set up process.  If you don't have some of the information you can skip any of the sections and return to them later.
		If you need help you can call us at <b>877-211-0213</b> or <a href="https://www.easywayordering.com/contact.html"><span style="font-weight:bold; color:#000;">Contact Us</span></a>.
        </div>
    </div>
	<div class="clr"></div>
		<!--</p>
	</div>-->
	<? } ?>
</div>
<div class="content">
	<div class="container">
		<div class="tabs-cont">
			<? if($error == "") { ?>
				<div class="left-col">
					<div class="first" id="tab_1">
						<span class="tile selected first"></span>
						<span class="tab_label selected tab_label_1">Create Your Account</span>
					</div>
					<div id="tab_2">
						<span class="tile"></span>
						<span class="tab_label">Restaurant Information</span>
					</div>
					<div id="tab_3">
						<span class="tile"></span>
						<span class="tab_label">Order Settings</span>
					</div>
					<div id="tab_4">
						<span class="tile"></span>
						<span class="tab_label">Menus Settings</span>
					</div>
					<div id="tab_5">
						<span class="tile"></span>
						<span class="tab_label">Payment Settings</span>
					</div>
					
                                    <?php
                                    if($premium_account_true){ ?>
				    	<div id="tab_6">
						<span class="tile"></span>
						<span class="tab_label">Setup Instructions</span>
					</div>
					<div id="tab_7" class="last">
						<span class="tile last"></span>
						<span class="tab_label">Reputation Intelligence
                                                    Settings</span>
					</div>
                                    <?php } else {?>
				    	<div id="tab_6" class="last">
						<span class="tile last"></span>
						<span class="tab_label">Setup Instructions</span>
					</div>		
				    <?php } ?>
				</div><!--/tab nav end-->
				<form action="<? echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" id="self_setup_form">
				<input type="hidden" name="reseller_id" value="<? echo $reseller_id; ?>" />
				<input type="hidden" name="subscription_id" value="<? echo $chargify_subscription_id; ?>" />
				<input type="hidden" name="product_id" value="<? echo $chargify_product_id; ?>" />
				<input type="hidden" name="client_login_id" id="client_login_id" value="" />
				<input type="hidden" name="reseller_company_logo" id="reseller_company_logo" value="<? echo $reseller_company_logo; ?>" />
                <div class="clearfix center-col">
					<div class="tab-container" id="tab1" style="display: block;">
						<div class="reg-div" id="reg-div">
							<h3>Create Your Account</h3>
							<div class="reg_table_div">
								<table class="reg-table" width="100%" cellpadding="0" cellspacing="0" align="">
									<tr>
										<td width="50%">
											<label for="client_firstname">First Name: <span class="starig">*</span></label>
											<input type="text"  id="client_firstname" name="client_firstname" value="<? echo $customer->first_name;?>" class="required" />
										</td>
										<td width="50%">
											<label for="client_lastname"> Last Name: <span class="starig">*</span></label>
											<input type="text" id="client_lastname" name="client_lastname" value="<? echo $customer->last_name;?>" class="required" />
										</td>
									</tr>
									<tr>
										<td>
											<label for="client_email">Email: <span class="starig">*</span></label>
											<input type="text" id="client_email" name="client_email" value="<? echo $customer->email;;?>" class="email required" />
										</td>
										<td>
											<label for="client_username">Username: <span class="starig">*</span></label>
											<input type="text" name="client_username" id="client_username" />
											<label for="client_username" class="error" id="username_not_available_label" style="display: none;">Username is not available</span></label>
										</td>
									 </tr>
									 <tr>
										<td>
											<label for="client_password">Password: <span class="starig">*</span></label>
											<input type="password" name="client_password" id="client_password" class="required" />
										</td>
										<td>
											<label for="client_confirrm_password">Confirm Password: <span class="starig">*</span></label>
											<input type="password" name="client_confirrm_password" id="client_confirrm_password" class="required" />
										</td>
									 </tr>
									 <tr>
										<td>
											<label for="client_Region">Region: <span class="starig">*</span></label>
                                                                                        <input name="region" type="radio" value="1" onclick="loadTimeZoneUS()" id="region" />USA/Canada &nbsp;&nbsp;
                                                                                         <input name="region" type="radio" value="0" onclick="loadTimeZoneUK()" id="region"/>UK
										</td>
										<td>
											<label for="client_country">Country: <span class="starig">*</span></label>
											<select name="client_country" id="client_country">										
												<option value="-1">Select Country</option>
												<option value="AF">Afghanistan</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua and Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="BN">Brunei Darussalam</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo</option><option value="CD">Congo, the Democratic Republic of the</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CI">Cote D'Ivoire</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands (Malvinas)</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard Island and Mcdonald Islands</option><option value="VA">Holy See (Vatican City State)</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran, Islamic Republic of</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option va



lue="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KP">Korea, Democratic People's Republic of</option><option value="KR">Korea, Republic of</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Lao People's Democratic Republic</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libyan Arab Jamahiriya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macao</option><option value="MK">Macedonia, the Former Yugoslav Republic of</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia, Federated States of</option><option value="MD">Moldova, Republic of</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="AN">Netherlands Antilles</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PS">Palestinian Territory, Occupied</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Reunion</option><option value="RO">Romania</option><option value="RU">Russian Federation</option><option value="RW">Rwanda</option><option value="SH">Saint Helena</option><option value="KN">Saint Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="PM">Saint Pierre and Miquelon</option><option value="VC">Saint Vincent and the Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">Sao Tome and Principe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="CS">Serbia and Montenegro</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia and the South Sandwich Islands</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SJ">Svalbard and Jan Mayen</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syrian Arab Republic</option><option value="TW">Taiwan, Province of China</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania, United Republic of</



option><option value="TH">Thailand</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad and Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks and Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="US">United States</option><option value="UM">United States Minor Outlying Islands</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VE">Venezuela</option><option value="VN">Viet Nam</option><option value="VG">Virgin Islands, British</option><option value="VI">Virgin Islands, U.s.</option><option value="WF">Wallis and Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option>          
											</select>
										</td>
									 </tr>
									 <tr>
										<td>
											<label for="client_state">State: <span class="starig">*</span></label>
											<input type="text" name="client_state" id="client_state" value="<? echo $customer->state;?>" class="required" />
										</td>
										<td>
											<label for="client_city">City: <span class="starig">*</span></label>
											<input type="text" name="client_city" id="client_city" value="<? echo $customer->city;?>" class="required" />
										</td>
									 </tr>
									 <tr>
										
										<td>
											<label for="client_zip">Zip: <span class="starig">*</span></label>
											<input type="text" name="client_zip" id="client_zip" value="<? echo $customer->zip;?>" class="required" />
										</td>
										  <td>
                                                                                 <label for="client_phone">Phone: <span class="starig">*</span></label>
											<input type="text" id="client_phone" name="client_phone" value="<? echo $customer->phone;?>" class="required" />
                                          </td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td colspan="2">
											Already a user? <a href="#" class="link" id="login-id">Sign in</a>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="login-div clearfix" name="" id="login-div">
							<h3>Login</h3>
							<div class="reg_table_div">
								<table class="reg-table login_form_container" width="100%" cellpadding="0" cellspacing="0">
									<tr id="login_error_container" style="display: none;">
										<td><b style="color: red;">Unable to login. Please provide valid username and password.</b></td>
									</tr>
									<tr>
										<td>
											<label for="client_login_usrname">Username: <span class="starig">*</span></label>
											<input type="text" name="client_login_usrname" id="client_login_usrname" class="required" />
										</td>
									</tr>
									<tr>
										<td>
											<label for="client_login_password">Password: <span class="starig">*</span></label>
											<input type="password" name="client_login_password" id="client_login_password" class="required" />
										</td>
									</tr>
									<tr>
										<td>Don't have an account? <a href="#" class="link" id="reg-id">Sign Up</a></td>
									</tr>
								</table>
								<table class="reg-table loading_container" width="100%" height="60%" cellpadding="0" cellspacing="0" style="display: none;">
									<tr>
										<td style="text-align: center;">
											<img src="images/ajax-loader.gif" alt="Logging In..." />
											<br /><br /><span> Logging In...</span>
										</td>
									</tr>
								</table>
								<table class="reg-table login_success_container" width="100%" height="60%" cellpadding="0" cellspacing="0" style="display: none;">
									<tr>
										<td>
											<h3 style="text-align: center; color: #33ff33;">You are successfully logged in.</h3>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div><!--/tab one container end-->
					
					<div class="tab-container" id="tab2">
						<h3>Restaurant Information</h3>
						<div class="reg_table_div">
							<table class="reg-table" width="100%" cellpadding="0" cellspacing="0" align="">
								<tr>
									<td width="50%">
										<label for="restaurant_name">Restaurant Name: <span class="starig">*</span></label>
										<input type="text" name="restaurant_name" id="restaurant_name" class="required"/>
										<label for="restaurant_name" class="error" id="restaurant_name_not_available_label" style="display: none;">Restaurant name is not available</span></label>									
									</td>
									<td>
										<label for="restaurant_email">Email: <span class="starig">*</span></label>
										<input type="text" name="restaurant_email" id="restaurant_email" class="required email"/>
									</td>
								</tr>
								<tr>
									<td width="50%">
										<label for="restaurant_phone">Phone: <span class="starig">*</span></label>
										<input type="text" name="restaurant_phone" id="restaurant_phone" class="required" />
									</td>
									<td>
										<label for="restaurant_fax">Fax: <span class="starig">*</span></label>
										<input type="text" name="restaurant_fax" id="restaurant_fax" />
									</td>
								</tr>
								<tr>
									<td>
										<label for="restaurant_address">Address: <span class="starig">*</span></label>
										<input type="text" name="restaurant_address" id="restaurant_address" class="required"  />
									</td>
									<td>
										<label for="restaurant_state">State: <span class="starig">*</span></label>
										<input type="text" name="restaurant_state" id="restaurant_state" class="required"  />
									</td>
								</tr>
								<tr>
									<td>
										<label for="restaurant_city">City: <span class="starig">*</span></label>
										<input type="text" name="restaurant_city" id="restaurant_city" class="required" />
									</td>
									<td>
										<label for="restaurant_zip">Zip: <span class="starig">*</span></label>
										<input type="text" name="restaurant_zip" id="restaurant_zip" class="required number" />
									</td>
								</tr>
								<tr>
									<td>
										<label for="restaurant_timezone">Select Time Zone: <span class="starig">*</span></label>
										<select name="restaurant_timezone" id="restaurant_timezone" class="required">
											<option value="-1">Select Time Zone</option>
											<option value="1">America/Adak</option><option value="2">America/Anchorage</option><option value="3">America/Anguilla</option><option value="4">America/Antigua</option><option value="5">America/Araguaina</option><option value="6">America/Argentina/Buenos_Aires</option><option value="7">America/Argentina/Catamarca</option><option value="8">America/Argentina/ComodRivadavia</option><option value="9">America/Argentina/Cordoba</option><option value="10">America/Argentina/Jujuy</option><option value="11">America/Argentina/La_Rioja</option><option value="12">America/Argentina/Mendoza</option><option value="13">America/Argentina/Rio_Gallegos</option><option value="14">America/Argentina/Salta</option><option value="15">America/Argentina/San_Juan</option><option value="16">America/Argentina/San_Luis</option><option value="17">America/Argentina/Tucuman</option><option value="18">America/Argentina/Ushuaia</option><option value="19">America/Aruba</option><option value="20">America/Asuncion</option><option value="21">America/Atikokan</option><option value="22">America/Atka</option><option value="23">America/Bahia</option><option value="24">America/Bahia_Banderas</option><option value="25">America/Barbados</option><option value="26">America/Belem</option><option value="27">America/Belize</option><option value="28">America/Blanc-Sablon</option><option value="29">America/Boa_Vista</option><option value="30">America/Bogota</option><option value="31">America/Boise</option><option value="32">America/Buenos_Aires</option><option value="33">America/Cambridge_Bay</option><option value="34">America/Campo_Grande</option><option value="35">America/Cancun</option><option value="36">America/Caracas</option><option value="37">America/Catamarca</option><option value="38">America/Cayenne</option><option value="39">America/Cayman</option><option value="40">America/Chicago</option><option value="41">America/Chihuahua</option><option value="42">America/Coral_Harbour</option><option value="43">America/Cordoba</option><option value="44">America/Costa_Rica</option><option value="45">America/Cuiaba</option><option value="46">America/Curacao</option><option value="47">America/Danmarkshavn</option><option value="48">America/Dawson</option><option value="49">America/Dawson_Creek</option><option value="50">America/Denver</option><option value="51">America/Detroit</option><option value="52">America/Dominica</option><option value="53">America/Edmonton</option><option value="54">America/Eirunepe</option><option value="55">America/El_Salvador</option><option value="56">America/Ensenada</option><option value="57">America/Fort_Wayne</option><option value="58">America/Fortaleza</option><option value="59">America/Glace_Bay</option><option value="60">America/Godthab</option><option value="61">America/Goose_Bay</option><option value="62">America/Grand_Turk</option><option value="63">America/Grenada</option><option value="64">America/Guadeloupe</option><option value="65">America/Guatemala</option><option value="66">America/Guayaquil</option><option value="67">America/Guyana</option><option value="68">America/Halifax</option><option value="69">America/Havana</option><option value="70">America/Hermosillo</option><option value="71">America/Indiana/Indianapolis</option><option value="72">America/Indiana/Knox</option><option value="73">America/Indiana/Marengo</option><option value="74">America/Indiana/Petersburg</option><option value="75">America/Indiana/Tell_City</option><option value="76">America/Indiana/Vevay</option><option value="77">America/Indiana/Vincennes</option><option value="78">America/Indiana/Winamac</option><option value="79">America/Indianapolis</option><option value="80">America/Inuvik</option><option value="81">America/Iqaluit</option><option value="82">America/Jamaica</option><option value="83">America/Jujuy</option><option value="84">America/Juneau</option><option value="85">America/Kentucky/Louisville</option><option value="86">America/Kentucky/Monticello</option><option value="87">America/Knox_IN</option><option value="88">America/La_Paz</option><option value="89">America/Lima</option><option value="90">America/Los_Angeles</option><option value="91">America/Louisville</option><option value="92">America/Maceio</option><option value="93">America/Managua</option><option value="94">America/Manaus</option><option value="95">America/Marigot</option><option value="96">America/Martinique</option><option value="97">America/Matamoros</option><option value="98">America/Mazatlan</option><option value="99">America/Mendoza</option><option value="100">America/Menominee</option><option value="101">America/Merida</option><option value="102">America/Mexico_City</option><option value="103">America/Miquelon</option><option value="104">America/Moncton</option><option value="105">America/Monterrey</option><option value="106">America/Montevideo</option><option value="107">America/Montreal</option><option value="108">America/Montserrat</option><option value="109">America/Nassau</option><option value="110" selected="">America/New_York</option><option value="111">America/Nipigon</option><option value="112">America/Nome</option><option value="113">America/Noronha</option><option value="114">America/North_Dakota/Beulah</option><option value="115">America/North_Dakota/Center</option><option value="116">America/North_Dakota/New_Salem</option><option value="117">America/Ojinaga</option><option value="118">America/Panama</option><option value="119">America/Pangnirtung</option><option value="120">America/Paramaribo</option><option value="121">America/Phoenix</option><option value="122">America/Port-au-Prince</option><option value="123">America/Port_of_Spain</option><option value="124">America/Porto_Acre</option><option value="125">America/Porto_Velho</option><option value="126">America/Puerto_Rico</option><option value="127">America/Rainy_River</option><option value="128">America/Rankin_Inlet</option><option value="129">America/Recife</option><option value="130">America/Regina</option><option value="131">America/Resolute</option><option value="132">America/Rio_Branco</option><option value="133">America/Rosario</option><option value="134">America/Santa_Isabel</option><option value="135">America/Santarem</option><option value="136">America/Santiago</option><option value="137">America/Santo_Domingo</option><option value="138">America/Sao_Paulo</option><option value="139">America/Scoresbysund</option><option value="140">America/Shiprock</option><option value="141">America/St_Barthelemy</option><option value="142">America/St_Johns</option><option value="143">America/St_Kitts</option><option value="144">America/St_Lucia</option><option value="145">America/St_Thomas</option><option value="146">America/St_Vincent</option><option value="147">America/Swift_Current</option><option value="148">America/Tegucigalpa</option><option value="149">America/Thule</option><option value="150">America/Thunder_Bay</option><option value="151">America/Tijuana</option><option value="152">America/Toronto</option><option value="153">America/Tortola</option><option value="154">America/Vancouver</option><option value="155">America/Virgin</option><option value="156">America/Whitehorse</option><option value="157">America/Winnipeg</option><option value="158">America/Yakutat</option><option value="159">America/Yellowknife</option><option value="160">Europe/London</option>
										</select>
									</td>
									
								</tr>
							</table>
						</div>
					</div><!--/tab two container end-->
					
					<div class="tab-container" id="tab3">
						<h3>Order Settings</h3>
						<div class="reg_table_div">
							<table width="100%" class="reg-table">
								<tr>
									<Td>
										<strong>Will you receive orders via Fax, Email or POS?</strong><br />
										<div class="clearfix">
											<label for="fax" class="left first"><input type="radio" value="fax" name="orders_medium" id="fax" checked="checked" /> Fax </label>
											<label for="email" class="left"><input type="radio" value="email" name="orders_medium" id="email" /> Email </label>
											<label for="pos" class="left"><input type="radio" value="pos" name="orders_medium" id="pos" /> POS </label>
										</div>
										<!--<div class="faxDiv" id="FaxDivId" style="display: block;">
											<label for="restaurant_fax_number_for_orders">Fax Number: <span class="starig">*</span></label>
											<input type="text" id="restaurant_fax_number_for_orders" name="restaurant_fax_number_for_orders" />
										</div>
										<div class="faxDiv" id="EmailDivId">
											<label for="restaurant_email_for_orders">Email: <span class="starig">*</span></label>
											<input type="text" id="restaurant_email_for_orders" name="restaurant_email_for_orders" />
										</div>-->
										<div class="faxDiv" id="POSDivId" style="height: 20px;">
											Your Online Ordering Coach will configure this for you during your training call.
										</div>
									</Td>
								</tr>
								<tr>
									<td>
										<label for="order_sales_tax">Sales Tax %: <span class="starig">*</span></label>
										<input type="text" name="order_sales_tax" id="order_sales_tax" class="required number" />
									</td>
								</tr>
								<tr>
									<td>
										<div class="clearfix">
											<strong>Do you offer delivery?</strong> <span class="starig">*</span><br />
											<label for="del_yes" class="left first"><input type="radio" value="1" name="order_delivery" id="del_yes" /> Yes </label>
											<label for="del_no" class="left"><input type="radio" value="0" name="order_delivery" id="del_no" checked="checked" />  No </label>
										</div>
										<div class="faxDiv clearfix" id="del_yes_div" style="height: auto;">
											<div class="clearfix">
												<strong>Delivery Options:</strong><br />
												<label for="delRad" class="left first"><input type="radio" value="1" name="delivery_option" id="delRad" checked="checked" /> Delivery Radius </label>
												<label for="cusDelZon" class="left"><input type="radio" value="2" name="delivery_option" id="cusDelZon" /> Custom Delivery Zone </label>
											</div>
											<div class="faxDiv clearfix" id="delRadDiv" style="display: block; height: auto;">
												<label for="order_minimum">Delivery Minimum (in dollars): <span class="starig">*</span></label>
												<input type="text" name="order_minimum" id="order_minimum" class="required number" />
												<br />
												<label for="client_deliveryRadius">Delivery Radius (in miles): </label>
												<input type="text" name="client_delivery_radius" id="client_deliveryRadius" class="required" />
												<br />
												<label for="client_deliveryCharges">Delivery charges: </label>
												<input type="text" name="client_delivery_charges" id="client_deliveryCharges" class="required number" />
											</div>
											<div class="faxDiv" id="cusDelZonDiv" style="height: 240px;">
												<a href="#" rel="facebox"><img src="../images/zones.png" /></a>
												<a id="facebox_link" href="#" rev="iframe" style="display: none;">Hidden Link</a>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div><!--/tab three container end-->
					
					<div class="tab-container" id="tab4">
						<h3>Menus</h3>
						<div class="reg_table_div">
							<table width="100%" class="reg-table">
								<tr>
									<td>
										<label for="uploadMenu"><input type="radio" name="menu_settings" id="uploadMenu" value="upload" checked="checked" /> Upload your menus</label>
									</td>
									<td>
										<label for="uploadMenu2"><input type="radio" name="menu_settings" value="fax" id="uploadMenu2" /> Fax your menu to 877-211-0213</label>
									</td>
									<!--<Td>
										<label for="uploadMenu3"><input type="radio" name="menu_settings" value="print_fax_cover" id="uploadMenu3" />Print fax cover sheet </label>    
									</Td>-->
								</tr>
							</table>
							
							<div class="uploadMenuDiv" id="uploadMenuDiv" style="display: block;">
								<table width="100%" class="reg-table">
									<tr>
										<td>
											<label for="menu_name">Menu Name:</label>
											<input type="text" name="menu_name" id="menu_name" class="required" /> 
										</td>
									</tr>
									<tr>
										<td>
											<label for="menu_file1">Upload menu file:</label>
											<div id="menu_files_container">
												<div id="menu_file_1" class="menu_file_field clearfix" style="width: 300px;">
													<input type="file" name="menu_file[]" id="menu_file1" class="required" style="width: 250px; overflow: hidden;"/>
													<a href="#" style="display: inline-block; float: right;font-weigh: bold; color: red;" class="cancel_menu_file">cancel</a>
												</div>
											</div>
											<div style="width: 300px;">
												<a href="#" id="add_another_menu_file">+ upload another menu</a>
											</div>
											<script type="text/javascript">
												(function($) {
													$(function() {
														var input_file_count = 1;
														$("#add_another_menu_file").click(function() {
															input_file_count++;
															var elem = $(".menu_file_field").eq(0).clone(true);
															elem.attr("id", "menu_file_" + input_file_count)
															var uploadelem = elem.find("input[type='file']");
															if (/MSIE/.test(navigator.userAgent)) {
																uploadelem.replaceWith(uploadelem.clone(true));
															} else {
																uploadelem.val('');
															}
															elem.appendTo("#menu_files_container");
															return false;
														});
														$(".cancel_menu_file").click(function() {
															if($(this).parent().attr("id") == "menu_file_1") return false;
															$(this).parent().remove();
															return false;
														});
													});
												})(jQuery);
											</script>
										</td>
									</tr>
									<tr>
										<td style="text-align: right;">
											
										</td>
									</tr>
									<tr>
										<td>
											<label for="menu_special_instructions">Special instructions:</label>
											<textarea rows="7" cols="4" name="menu_special_instructions" id="menu_special_instructions" class="required" style="width: 350px;"></textarea>
										</td>
									</tr>
								</table>
							</div>
							<div class="uploadMenuDiv" id="uploadMenuDiv2"  style="display: none;">
								<table width="100%" class="reg-table" align="">
									<tr>
										<td>
											<input type="button" value="Print Fax Cover Sheet" id="print_fax_cover_sheet" name="print_fax_cover_sheet" onclick="open_win();"/>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div><!--/tab four container end-->
					
					<div class="tab-container" id="tab5">
						<h3>Payment Setting</h3>
						<div class="reg_table_div">
							<table width="100%" class="reg-table">
								<tr>
									<td>
										<div class="clearfix">
											<strong>Will you accept cash?</strong><br />
											<label for="cash_yes" class="left first"><input type="radio" name="cash_payment" value="yes" id="cash_yes" checked="checked" /> Yes </label>
											<label for="cash_no" class="left"><input type="radio" name="cash_payment" value="no" id="cash_no" /> No </label>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="clearfix">
											<strong>Will you accept credit cards?</strong><br />
											<label for="credit_card_yes" class="left first"><input type="radio" name="credit_card_payment" value="yes" id="credit_card_yes" /> Yes </label>
											<label for="credit_card_no" class="left"><input type="radio" name="credit_card_payment" value="no" id="credit_card_no" checked="checked" /> No </label>
										</div>
										<? if($reseller_credit_card_processor) { ?>
											<div class="faxDiv" id="cashDivYes" style="height: auto;">
												<label for="credit_card_payment_option1" class="multiline"><input type="radio" value="1" name="credit_card_payment_option" id="credit_card_payment_option1" />  I already have a [credit card processor name] merchant account for online transactions.</label>
												<label for="credit_card_payment_option2" class="multiline"><input type="radio" value="2" name="credit_card_payment_option" id="credit_card_payment_option2" checked="checked" /> I would like to set up a new [credit card processor name] merchant account for online transactions.</label>
												<label for="credit_card_payment_option3" class="multiline"><input type="radio" value="3" name="credit_card_payment_option" id="credit_card_payment_option3" /> I plan to open an e-commerce merchant account on my own.</label>
											</div>
										<? } else { ?>
											<div class="faxDiv" id="cashDivYes" style="height: auto;">
												<label for="credit_card_payment_option1" class="multiline"><input type="radio" value="1" name="credit_card_payment_option" id="credit_card_payment_option1" /> I already have a payment gateway for online transactions.</label>
												<div class="faxDiv clearfix" id="creditCardOptionDiv" style="height: auto;">
													<div class="clearfix">
														<strong>Payment gateway</strong> <br />
														<label for="payment_gateway_type1" class="left first"><input type="radio" value="authoriseDotNet" name="payment_gateway_type" id="payment_gateway_type1" checked="checked" />  Authorize Dot Net</label>
														<label for="payment_gateway_type3 " class="left"><input type="radio" value="nmi" name="payment_gateway_type" id="payment_gateway_type3" /> NMI</label>
													</div>
													<div class="clearfix" style="margin-top: 5px;">
														<label for="payment_gateway_login"><strong>Payment gateway login ID: <span class="starig">*</span></strong></label>
														<input type="text" name="payment_gateway_login" id="payment_gateway_login" class="required" />
													</div>
													<div class="clearfix"  style="margin-top: 5px;">
														<label for="payment_gateway_transaction_key"><strong>Payment gateway transaction key : <span class="starig">*</span></strong></label>
														<input type="password" name="payment_gateway_transaction_key" id="payment_gateway_transaction_key" class="required" />
													</div>
													<div class="clearfix" style="margin-top: 5px; margin-bottom: 5px;">
														<strong>Does your gateway support tokenization?</strong> <br />
														<label for="gateway_support_tokenization1" class="left first"><input type="radio" value="1" name="gateway_support_tokenization" id="gateway_support_tokenization1" checked="checked" />  Yes</label>
														<label for="gateway_support_tokenization2" class="left"><input type="radio" value="0" name="gateway_support_tokenization" id="gateway_support_tokenization2" /> No</label>
														<label for="gateway_support_tokenization3 " class="left"><input type="radio" value="2" name="gateway_support_tokenization" id="gateway_support_tokenization3" /> I don't know</label>
													</div>
												</div>
												<label for="credit_card_payment_option2" class="multiline"><input type="radio" value="2" name="credit_card_payment_option" id="credit_card_payment_option2" checked="checked" /> I would like to set up a new e-commerce merchant account with an EasyWay certified partner.</label>
												<label for="credit_card_payment_option3" class="multiline"><input type="radio" value="3" name="credit_card_payment_option" id="credit_card_payment_option3" /> I plan to open an e-commerce merchant account on my own.</label>
											</div>
										<? } ?>
										
									</td>
								</tr>
							</table>
						</div>
					</div><!--/tab five container end-->
					
					<div class="tab-container" id="tab6">
						<h3>Setup Instructions</h3>
						<div class="reg_table_div">
							<table width="100%" class="reg-table">
								<tr>
									<td>
										<div class="clearfix" style="margin-top: 0;">
											<strong>Domain Name of your website</strong> <br />
											<div>       
												<label for="domainName1" class="left first"><input type="radio" name="have_domain_name" id="domainName1" value="yes" checked="checked" />  Already have a domain name. </label>
												<label for="domainName2" class="left"><input type="radio" name="have_domain_name" id="domainName2" value="no" />  I would like a new domain name. </label>
											</div>
										</div>
										<div class="faxDiv1" id="domainDiv2">
											<label for="domain_name">Enter domain name:</label>
											<input type="text" name="domain_name" id="domain_name" class="required"/>
										</div>
										<div class="faxDiv" id="domainDiv3">
											<label for="desired_domain_name">Enter desired domain name:</label>
											<input type="text" name="desired_domain_name" id="desired_domain_name" class="required"/>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<strong>Website Integration</strong> <br /> 
										<label for="wesbite_integration1"><input type="radio" name="wesbite_integration" id="wesbite_integration1" value="1" checked="checked"/> I am adding the EasyWay Ordering menu to my existing website </label>
										<label for="wesbite_integration2"><input type="radio" name="wesbite_integration" id="wesbite_integration2" value="2" /> I will use the EasyWay Ordering menu as my website </label>
										<label for="wesbite_integration3"><input type="radio" name="wesbite_integration" id="wesbite_integration3" value="3" /> I would like EasyWay to build me a custom website </label>
									</td>
								</tr>
								<tr id="hosting_information_container">
									<td>
										<strong> Hosting Information</strong> <br /> 
										<label for="hosting_integration_type1" class="multiline"><input type="radio" value="1" name="hosting_integration_type" id="hosting_integration_type1" /> I would like EasyWay to configure my hosting account and or integrate the ordering page with my website. </label>
										
										<div class="faxDiv" id="hosting_div1" style="height: auto;">									
											<label for="hosting_provider_name">Name of hosting company (ex GoDaddy):</label>
											<input type="text" name="hosting_provider_name" id="hosting_provider_name" class="required" />
											<br />
											<label for="hosting_username">Account number or username:</label>
											<input type="text" name="hosting_username" id="hosting_username" class="required" />
											<br />
											<label for="hosting_password">Account Password:</label>
											<input type="password" name="hosting_password" id="hosting_password" class="required" />
										</div>
										
										<label for="hosting_integration_type2"><input type="radio" value="2" name="hosting_integration_type" id="hosting_integration_type2" /> My web designer will apply the necessary updates </label>
										<div class="faxDiv" id="hosting_div2" style="height: auto;">
											<label for="web_designer_name">Name of Webmaster:</label>
											<input type="text" name="web_designer_name" id="web_designer_name" class="required"/>
											<br />
											<label for="web_designer_phone">Webmaster Phone Number:</label>
											<input type="text" name="web_designer_phone" id="web_designer_phone" class="required"/>
											<br />
											<label for="web_designer_email">Webmaster Email Address:</label>
											<input type="text" name="web_designer_email" id="web_designer_email" class="required email"/>
										</div>
										<label for="hosting_integration_type3"><input type="radio" value="3" name="hosting_integration_type" id="hosting_integration_type3" checked="checked" /> I will apply the updates myself </label>
									</td>
								</tr>
							</table>
						</div>
					</div><!--/tab six container end-->
                                         <?php  if($premium_account_true){ ?>
					<div class="tab-container" id="tab7">
						<h3>Reputation Intelligence Settings</h3>
						<div class="reputation_table_div">
							<table width="100%" class="reputation-table">
                                                                <tr>
                                                                    <td width="50%">
                                                                        <strong>Competitors:</strong><br>
                                                                        <span>Please list up to 3 competitors. This is used to determine how you�re stacking up against the competition in social media.</span>
                                                                        <br>
                                                                        <label for="competitors_name1">Competitor 1: <span class="starig">*</span></label><input type="text" name="competitors_name1" id="competitors_name1" class="required"/><br>
                                                                        <label for="competitors_name2">Competitor 2: </label><input type="text" name="competitors_name2" id="competitors_name2" /><br>
                                                                        <label for="competitors_name3">Competitor 3: </label><input type="text" name="competitors_name3" id="competitors_name3" /><br>
                                                                        <label for="competitors_name1" class="error" id="competitors_name1_not_available_label" style="display: none;">You must name at least one competitor.</span></label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <strong>Common Company Names: </strong><br>
                                                                        <span>List up to 3 names customers might use to identify your business online. For example "Mario's Italian Cuisine" might also be commonly referred to as "Mario's Pizza" and "Mario's Brooklyn"</span>
                                                                        <br>
                                                                        <label for="common_company_name1">Common Company Name 1: <span class="starig">*</span></label><input type="text" name="common_company_name1" id="common_company_name1" class="required"/><br>
                                                                        <label for="common_company_name2">Common Company Name 2: </label><input type="text" name="common_company_name2" id="common_company_name2"/><br>
                                                                        <label for="common_company_name3">Common Company Name 3: </label><input type="text" name="common_company_name3" id="common_company_name3"/><br>
                                                                        <label for="common_company_name1" class="error" id="common_company_name1_not_available_label" style="display: none;">You must name at least one common name.</span></label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%">
                                                                        <strong>Categories: </strong><br>
                                                                        <span>Enter up to 3 categories that describe your business. Ex: Italian restaurant, pizza delivery, takeout</span><br>
                                                                        <label for="category1">Category 1: <span class="starig">*</span></label><input type="text" name="category1" id="category1" class="required"/><br>
                                                                        <label for="category2">Category 2: </label><input type="text" name="category2" id="category3"/><br>
                                                                        <label for="category3">Category 3: </label><input type="text" name="category3" id="category2"/><br>
                                                                    </td>
                                                                </tr>
							</table>
						</div>
					</div><!--/tab 7 container end-->
                                        <?php } ?>
					
					<div class="btn-hoder">
						<input type="button" value="&laquo; Previous" name="btn_previous" id="btn_previous" class="active-btn" style="display: none;"/>
						<input type="button" value="Skip" name="btn_skip" id="btn_skip" class="active-btn"  style="display: none;"/>
						<input type="button" value="Next &raquo;" name="btn_next" id="btn_next" class="active-btn"/>
						<input type="submit" value="Submit" name="btn_submit" id="btn_submit" class="active-btn" style="display: none;" />
					</div><!--/tab button nav container end-->
				</div>
				<div class="right-col">
					<div id="description1" style="display: block;">
						<div id="register_desciption">
							<h3>Create Your Account</h3>
							<p>You will use this account to access your restaurant control panel. If you have more than one restaurant or multiple locations you will be able to access all of them from this account.</p>
						</div>
						<div id="login_desciption" style="display: none;">
							<h3>Login</h3>
							<p>If you already have an account on EasyWay Ordering then you can use that. (New restaurant will be assigned to the existing account)</p>
						</div>
					</div>
					<div id="description2" style="display: none;">
						<h3>Restaurant Information</h3>
						<p>This step will populate the restaurant info and create the restaurant in the control panel.</p>
					</div>
					<div id="description3" style="display: none;">
						<h3>Order Settings</h3>
						<p>This step will input order related settings like order destination, delivery method, order minimum cost and the area in which restaurant provides delivery.</p>
					</div>
					<div id="description4" style="display: none;">
						<h3>Menus</h3>
						<p>Restaurant Menus will be uploaded in this step. You can provide menu name, menu files etc or can print fax cover sheet.</p>
					</div>
					<div id="description5" style="display: none;">
						<h3>Payment Settings</h3>
						<p>Configurtion of all payment settings done in this step. Like payment methods i.e cash or credit, payment gateway (gateway credentials) etc</p>
					</div>
					<div id="description6" style="display: none;">
						<h3>Setup Instructions</h3>
						<p>Provides option to the user to gather details about setup of the restaurant. Like domain name, hosting, integration.</p>
					</div>
				</div><!--/right-col end-->
			<? } else { ?>
				<h3 style="color: #ff000; text-align: center;">Sorry! <? echo $error; ?></h3>
			<? } ?>
		</div>
		</form>
	</div>
</div>

	<!-- Google add code start -->
    <!-- Google Code for EWO Signup Conversion Page --> 
    <script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 1002845623;
    var google_conversion_language = "en";
    var google_conversion_format = "2";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "7RXbCLme5gYQt-uY3gM"; var
    google_conversion_value = 500.00;
    /* ]]> */
    </script>
    <script type="text/javascript"
    src="//www.googleadservices.com/pagead/conversion.js">
    </script>
    <noscript>
    <div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt=""
    src="//www.googleadservices.com/pagead/conversion/1002845623/?value=500.00&a
    mp;label=7RXbCLme5gYQt-uY3gM&amp;guid=ON&amp;script=0"/>
    </div>
    </noscript>

    <!-- Google add code ends -->
</div>    
</body>
</html>
<?php mysqli_close($mysqli);?>
