<?
function get_file_extesion($file_name) {
	return substr($file_name, strrpos($file_name, '.')+1);
}

function prepareStringForMySQL($string){
    $string=str_replace ( "\r" , "<br/>",$string);
    $string=str_replace ( "\n" , "<br/>",$string);
    $string=str_replace ( "\t" , " ",$string);
    $string=mysql_real_escape_string($string);
    return $string;
}

extract($_REQUEST);

$client_id = $client_login_id;
if(empty($client_login_id)) {
	$sql="
		INSERT INTO users(
			firstname
			,lastname
			,email
			,username
			,password
			,phone
			,country
			,state
			,city
			,zip
			,type
			,status
		) values (
			'".mysql_real_escape_string(stripslashes($client_firstname))."'
			,'".mysql_real_escape_string(stripslashes($client_lastname))."'
			,'".mysql_real_escape_string(stripslashes($client_email))."'
			,'".mysql_real_escape_string(stripslashes($client_username))."'
			,'$client_password'
			,'$client_phone'
			,'".mysql_real_escape_string(stripslashes($client_country))."'
			,'".mysql_real_escape_string(stripslashes($client_state))."'
			,'".mysql_real_escape_string(stripslashes($client_city))."'
			,'$client_zip'
			,'store owner'
			,1
		)";
		//echo $sql;
		
	mysql_query($sql);
	$client_id = mysql_insert_id();
	$reseller_client_sql = "INSERT INTO reseller_client (reseller_id,client_id) VALUES ('".$reseller_id."','".$client_id."')";
	mysql_query($reseller_client_sql);
}

/* add restaurant */
if($cash_payment == "yes" && $credit_card_payment == "yes") {
	$payment_method = "both";
} else if($credit_card_payment == "yes") {
	$payment_method = "credit";	 
} else if($cash_payment == "yes") {
	$payment_method = "cash"; 
}


//create restaurant url from restaurant name
$rest_url_name = strtolower(trim(preg_replace('/-+/', '_', preg_replace('/[^a-zA-Z0-9]+/', '_', $restaurant_name)), '_'));

// search if a license is unused else create one new
$unused_licenses = mysql_query("SELECT id,license_key FROM licenses WHERE status='unused' AND reseller_id=$reseller_id LIMIT 1");
$license_id = 0;
$license_key = "";
$is_license_new = false;
if(mysql_num_rows($unused_licenses) > 0) {
	$license = mysql_fetch_assoc($unused_licenses);
	$license_id = $license["id"];
	$license_key = $license["license_key"];
	//echo "license found";
} else {
	//echo "new license added";
	$license_key = rand(0,99999999);
	$license_qry = "INSERT INTO licenses (reseller_id,status,dated) VALUES ('$reseller_id','unused',".time().")";
	mysql_query( $license_qry );
	$license_id = mysql_insert_id();				
	$license_key = $license_id.$license_key;
	$license_update_qry_srt = "UPDATE licenses SET license_key ='".$license_key."' WHERE id = '".$license_id."'";
	mysql_query( $license_update_qry_srt );
	$is_license_new = true;
	
	// increment user licences count in users table
	$user_license_count_update_qry = "UPDATE users SET number_of_licenses=number_of_licenses+1 WHERE id = $reseller_id";
	mysql_query( $user_license_count_update_qry );
}

// if fax not provided then use default
$restaurant_fax = ($restaurant_fax == "" ? "111-111-1111" : $restaurant_fax);

// gather all domain name details in array and then store it's json in db
$domain_name_details = array(
	"have_domain_name" => $have_domain_name
	,"domain_name" => $domain_name
	,"desired_domain_name" => $desired_domain_name
);

// gather all hosting information in array and then store it's json in db
$hosting_information = array(
	"hosting_integration_type" => $hosting_integration_type
	,"hosting_provider_name" => $hosting_provider_name
	,"hosting_username" => $hosting_username
	,"hosting_password" => $hosting_password
	,"web_designer_name" => $web_designer_name
	,"web_designer_phone" => $web_designer_phone
	,"web_designer_email" => $web_designer_email
);

// get local charfigy product id
$chargify_product = mysql_query("SELECT settings_id,premium_account FROM chargify_products WHERE product_id='". $product_id ."'");
$product_id = 0;
//echo $product_id;
if(mysql_num_rows($chargify_product) > 0) {
	$chargify_product = mysql_fetch_assoc($chargify_product);
	$product_id = $chargify_product["settings_id"];
        $premium_account_true = false;
        if($chargify_product["premium_account"] == 1)
            $premium_account_true = true;
}
//echo ">>" . $product_id;
$zone1 = 1;
$zone1_delivery_charges = 0;
$zone1_min_total = 0;

$zone2 = 1;
$zone2_delivery_charges = 0;
$zone2_min_total = 0;

$zone3 = 1;
$zone3_delivery_charges = 0;
$zone3_min_total = 0;

$zone1_coordinates = "";
$zone2_coordinates = "";
$zone3_coordinates = "";

if(!empty($_SESSION["restaurant_delivery_zones"]) ) {
	extract($_SESSION["restaurant_delivery_zones"]);
}
if(!isset($competitors_name1)){ $competitors_name1 = "";}
if(!isset($competitors_name2)){ $competitors_name2 = "";}
if(!isset($competitors_name3)){ $competitors_name3 = "";}
if(!isset($common_company_name1)){ $common_company_name1 = "";}
if(!isset($common_company_name2)){ $common_company_name2 = "";}
if(!isset($common_company_name3)){ $common_company_name3 = "";}
if(!isset($category1)){ $category1 = "";}
if(!isset($category2)){ $category2 = "";}
if(!isset($category3)){ $category3 = "";}

$sql = 	"INSERT INTO resturants 
	SET name= '".prepareStringForMySQL($restaurant_name)."'
		,url_name= '".addslashes($rest_url_name)."'
		,owner_id='".addslashes($client_id)."'
		,license_id='".$license_id."'
		,email='".addslashes($restaurant_email)."'
		,fax='".addslashes($restaurant_fax)."'
		,phone='".addslashes($restaurant_phone)."'
		,rest_address='".addslashes($restaurant_address)."'
		,rest_city='".addslashes($restaurant_city)."'
		,rest_state='".addslashes($restaurant_state)."'
		,rest_zip='".addslashes($restaurant_zip)."'
		,tax_percent = '$order_sales_tax'
		,delivery_charges = '$client_delivery_charges'
		,rest_open_close = '1'
		,delivery_offer = '$order_delivery'
		,delivery_option = '$delivery_option'
		,delivery_radius='$client_delivery_radius'
		,order_minimum='$order_minimum'
		,payment_gateway='$payment_gateway_type'
		,payment_method='".addslashes($payment_method)."'
		,tokenization= '$gateway_support_tokenization'
		,authoriseLoginID='$payment_gateway_login'
		,transKey='$payment_gateway_transaction_key'
		,order_destination='$orders_medium'
		,rest_order_email_fromat= '". ($orders_medium == "fax" ? "pdf" : "plain text") ."'
		,time_zone_id='$restaurant_timezone'
		,chargify_product_id='$product_id'
		,chargify_subscription_id='$subscription_id'
		,chargify_subscription_status='1'
		,domain_name_details='" . json_encode($domain_name_details) . "'
		,website_integration_type='$wesbite_integration'
		,hosting_information='" . json_encode($hosting_information) . "'
		,status=1
		
		,zone1='$zone1'
		,zone1_delivery_charges='$zone1_delivery_charges'
		,zone1_min_total='$zone1_min_total'
		,zone1_coordinates='$zone1_coordinates'
		
		,zone2='$zone2'
		,zone2_delivery_charges='$zone2_delivery_charges'
		,zone2_min_total='$zone2_min_total'
		,zone2_coordinates='$zone2_coordinates'
		
		,zone3='$zone3'
		,zone3_delivery_charges='$zone3_delivery_charges'
		,zone3_min_total='$zone3_min_total'
		,zone3_coordinates='$zone3_coordinates'

              ,competitors_name1='".addslashes($competitors_name1)."'
		,competitors_name2='".addslashes($competitors_name2)."'
		,competitors_name3='".addslashes($competitors_name3)."'
		,common_company_name1='".addslashes($common_company_name1)."'
		,common_company_name2='".addslashes($common_company_name2)."'
		,common_company_name3='".addslashes($common_company_name3)."'
		,category1='".addslashes($category1)."'
		,category2='".addslashes($category2)."'
		,category3='".addslashes($category3)."'
		,region='" . addslashes(trim($region)) . "'


	";
mysql_query($sql) or die(mysql_error());
//echo $sql;
$catid = mysql_insert_id();
if($catid) {

    mysql_query("INSERT INTO analytics
		SET name= '" . prepareStringForMySQL($restaurant_name) . "'
		,url_name= '" . addslashes($rest_url_name) . "'
		,first_letter = '".strtoupper(substr($restaurant_name,0,1))."'
		,resturant_id= " . $catid . " 
		,status=1");
}

if(!empty($catid)) {
        if($premium_account_true){
            $uri = 'https://reputation-intelligence-api.vendasta.com/api/v2/account/create/?apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&apiUser=ESWY';
            $vendastaaccount = array();
	     $restaurant_phone = preg_replace('/[^0-9.]+/', '', $restaurant_phone);
            $restaurant_fax = preg_replace('/[^0-9.]+/', '', $restaurant_fax);
           /* $vendastaaccount = array("address" => "$restaurant_address",
                "city" => "$restaurant_city",
                "companyName" => "$restaurant_name",
                "competitor" => $competitor,
                "country" => "US",
                "email" => "$restaurant_email",
                "state" => "$restaurant_state",
                "zip" => "$restaurant_zip",
                "billingCode" => "$subscription_id",
                "businessCategory" => "FOOD",
                "commonCompanyName" => $commonCompanyName,
                "customerIdentifier" => "$subscription_id",
                "faxNumber" => "$restaurant_fax",
                "firstName" => "$client_firstname",
                "lastName" => "$client_lastname",
                "service" => $service,
                "ssoToken" => "$subscription_id",
                "twitterSearches" => $twitterSearches,
                "website" => "$domain_name",
                "workNumber" => "$restaurant_phone"
            );
            $postString = $vendastaaccount;*/

            $postString ='
------VendastaBoundary
Content-Disposition: form-data; name="address"

'.$restaurant_address.'
------VendastaBoundary
Content-Disposition: form-data; name="city"

'.$restaurant_city.'
------VendastaBoundary
Content-Disposition: form-data; name="companyName"

'.$restaurant_name.'
------VendastaBoundary
Content-Disposition: form-data; name="competitor"

'.$competitors_name1.'';
if(!empty($competitors_name2)){
$postString .= '
------VendastaBoundary
Content-Disposition: form-data; name="competitor"

'.$competitors_name2.'';
}
if(!empty($competitors_name3)){
$postString .= '
------VendastaBoundary
Content-Disposition: form-data; name="competitor"

'.$competitors_name3.'';
}

$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="country"

'.mysql_real_escape_string(stripslashes($client_country)).'
------VendastaBoundary
Content-Disposition: form-data; name="email"

'.$restaurant_email.'
------VendastaBoundary
Content-Disposition: form-data; name="state"

'.$restaurant_state.'
------VendastaBoundary
Content-Disposition: form-data; name="zip"

'.$restaurant_zip.'
------VendastaBoundary
Content-Disposition: form-data; name="billingCode"

'.$subscription_id.'
------VendastaBoundary
Content-Disposition: form-data; name="businessCategory"

FOOD
------VendastaBoundary
Content-Disposition: form-data; name="commonCompanyName"

'.$common_company_name1.'';
if(!empty($common_company_name2)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="commonCompanyName"

'.$common_company_name2.'';
}
if(!empty($common_company_name3)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="commonCompanyName"

'.$common_company_name3.'';
}

$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="customerIdentifier"

'.$subscription_id.'
------VendastaBoundary
Content-Disposition: form-data; name="faxNumber"

'.$restaurant_fax.'
------VendastaBoundary
Content-Disposition: form-data; name="firstName"

'.$client_firstname.'
------VendastaBoundary
Content-Disposition: form-data; name="lastName"

'.$client_lastname.'
------VendastaBoundary
Content-Disposition: form-data; name="service"

'.$category1.'';

if(!empty($category2)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="service"

'.$category2.'';
}
if(!empty($category3)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="service"

'.$category3.'';
}
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="ssoToken"

'.$subscription_id.'
------VendastaBoundary
Content-Disposition: form-data; name="twitterSearches"

'.$common_company_name1.'';
if(!empty($common_company_name2)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="twitterSearches"

'.$common_company_name2.'';
}
if(!empty($common_company_name3)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="twitterSearches"

'.$common_company_name3.'';
}
if(!empty($domain_name)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="website"

'.$domain_name.'';
}
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="workNumber"

'.$restaurant_phone.'
------VendastaBoundary--';

            //print_r($postString );exit;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $uri);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_POST, 'multipart/form-data; boundary=----WebKitFormBoundaryrl2Zle6rOLWYq123');
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data;boundary=----VendastaBoundary"));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

            $api_response = curl_exec($ch);
            $result = json_decode($api_response);
            if($result['statusCode'] == 201){
                mysql_query("UPDATE resturants SET vendasta_account_created=1 WHERE id=$catid");
                mysql_query("UPDATE resturants SET premium_account=1 WHERE id=$catid");
            } else{
                echo "Unable to create Vendasta account the reason is as follows";
                echo $result['message'];
                exit;
            }
        }

	mysql_query("UPDATE licenses SET status='activated', resturant_id=$catid,activation_date= '".time()."' WHERE id=$license_id");

	for($j = 0; $j< 7; $j++) {
		//hour and minutes are treaded as string
		$open_time =  '0800';
		$close_time = '1700';
		mysql_query(
			"INSERT INTO business_hours 
			SET rest_id = '".$catid."'
				,day= '".$j."'
				,open='$open_time'
				,close='$close_time'"
		);
	}

	// upload and send email if menus are uploaded
	if($menu_settings == "upload") {
		$path = '../images/resturant_logos/';
		
		$objMail->clearattachments();
		for($i = 0; $i < count($_FILES["menu_file"]["name"]); $i++) {
			if($_FILES['menu_file']['name'][$i] != "") {
				$ext = get_file_extesion($_FILES['menu_file']['name'][$i]);
				$name = "menu_". $catid . $i ."_". time() .".". $ext;
				$uploadfile1 = $path . $name;
				move_uploaded_file($_FILES['menu_file']['tmp_name'][$i] , $uploadfile1);
				$objMail->addattachment($uploadfile1);
			}
		}
		
		//Account Info
		$message = "<table width=\"100%\" cellpadding=\"3px\"  cellspacing=\"0\" style=\"border: 1px solid #d1d1d1;\">".
			"<tr><td colspan=\"2\" width=\"100%\" style=\"border: 1px solid #d1d1d1;background-color:#f2f2f2;\"><b>Account Information</b></td></tr>" .
			"<tr><td width=\"50%\"><b>First Name</b></td>
			<td width=\"50%\">$client_firstname</td></tr>" .
			"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Last Name</b></td>
			<td width=\"50%\">$client_lastname</td></tr>" .
			"<tr><td width=\"50%\"><b>Email</b></td>
			<td width=\"50%\">$client_email</td></tr>" .
			"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>User Name</b></td>
			<td width=\"50%\">$client_username</td></tr>" .
			"<tr><td width=\"50%\"><b>Password</b></td>
			<td width=\"50%\">$client_password</td></tr>" .
			"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Phone</b></td>
			<td width=\"50%\">$client_phone</td></tr>" .
			"<tr><td width=\"50%\"><b>Country</b></td>
			<td width=\"50%\">$client_country</td></tr>" .
			"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>State</b></td>
			<td width=\"50%\">$client_state</td></tr>" .
			"<tr><td width=\"50%\"><b>City</b></td>
			<td width=\"50%\">$client_city</td></tr>" .
			"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Zip</b></td>
			<td width=\"50%\">$client_zip</td></tr>" .
			
			//Resturent Info
			"<tr><td colspan=\"2\" width=\"100%\">&nbsp;</td></tr>" .
			"<tr><td colspan=\"2\" width=\"100%\" style=\"border: 1px solid #d1d1d1;background-color:#f2f2f2;\"><b>Restaurant Information</b></td></tr>" .
			"<tr><td width=\"50%\"><b>Restaurant Name</b></td>
			<td width=\"50%\">$restaurant_name</td></tr>" .
			"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Email</b></td>
			<td width=\"50%\">$restaurant_email</td></tr>" .
			"<tr><td width=\"50%\"><b>Phone</b></td>
			<td width=\"50%\">$restaurant_phone</td></tr>" .
			"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Fax</b></td>
			<td width=\"50%\">$restaurant_fax</td></tr>" .
			"<tr><td width=\"50%\"><b>State</b></td>
			<td width=\"50%\">$restaurant_state</td></tr>" .
			"<tr style=\"background-color:#f2f2f2;\"><td><b>City</b></td>
			<td width=\"50%\">$restaurant_city</td></tr>" .
			"<tr><td><b>Zip</b></td>
			<td width=\"50%\">$restaurant_zip</td></tr>" .
			
			//Order Info
			"<tr><td colspan=\"2\" width=\"100%\">&nbsp;</td></tr>" .
			"<tr><td colspan=\"2\" width=\"100%\" style=\"border: 1px solid #d1d1d1;background-color:#f2f2f2;\"><b>Order Settings</b></td></tr>" .
			"<tr><td width=\"50%\"><b>Will you receive orders via Fax, Email or POS?</b></td>
			<td width=\"50%\">$orders_medium</td></tr>";
			
			if($order_delivery == 1)
			{
				$message .= "<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Do you offer delivery?</b></td>
				<td width=\"50%\">Yes</td></tr>" .
				"<tr><td width=\"50%\"><b>Delivery Options:</b></td>";
				
				if($delivery_option == 1)
				{
					$message .= "<td width=\"50%\">Delivery Radius</td></tr>";
				}
				else
				{
					$message .= "<td width=\"50%\">Custom Delivery Zone</td></tr>";
				}
				
				$message .= "<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Delivery Minimum (in dollars)</b></td>
				<td width=\"50%\">$order_minimum</td></tr>" .
				"<tr><td width=\"50%\"><b>Delivery Radius (in miles)</b></td>
				<td width=\"50%\">$client_delivery_radius</td></tr>" .
				"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Delivery charges</b></td>
				<td width=\"50%\">$client_delivery_charges</td></tr>";
			}
			else
			{
				$message .= "<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Do you offer delivery</b></td>
				<td width=\"50%\">No</td></tr>";
			}
			
			//Menu
			$message .= "<tr><td colspan=\"2\" width=\"100%\">&nbsp;</td></tr>" .
			"<tr><td colspan=\"2\" width=\"100%\" style=\"border: 1px solid #d1d1d1;background-color:#f2f2f2;\"><b>Menu Settings for <b>$restaurant_name</b></td></tr>" .
			"<tr><td width=\"50%\"><b>Menu Name</b></td>
			<td width=\"50%\">$menu_name</td></tr>" .
			"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Menu Special Instructions</b></td>
			<td width=\"50%\">$menu_special_instructions</td></tr>" .
			
			//Payments
			"<tr><td colspan=\"2\" width=\"100%\">&nbsp;</td></tr>" .
			"<tr><td colspan=\"2\" width=\"100%\" style=\"border: 1px solid #d1d1d1;background-color:#f2f2f2;\"><b>Payments Settings</b></td></tr>";
			
			if($credit_card_payment == "yes")
			{
				if($credit_card_payment_option == 1)
				{
					$message .= "<tr><td colspan=\"2\" width=\"100%\"><b>I already have a payment gateway for online transactions</b></td></tr>".
					"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Payment gateway</b></td>
					<td width=\"50%\">$payment_gateway_type</td></tr>" .
					"<tr><td width=\"50%\"><b>Payment gateway login ID</b></td>
					<td width=\"50%\">$payment_gateway_login</td></tr>" .
					"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Payment gateway transaction key</b></td>
					<td width=\"50%\">$payment_gateway_transaction_key</td></tr>" .
					"<tr><td width=\"50%\"><b>Does your gateway support tokenization</b></td>";
			
					if($gateway_support_tokenization == 1)
						$message .= "<td width=\"50%\">Yes</td></tr>";
					else if($gateway_support_tokenization == 0)
						$message .= "<td width=\"50%\">No</td></tr>";
					else if($gateway_support_tokenization == 2)
						$message .= "<td width=\"50%\">I don't know</td></tr>";
						
				}
				else if($credit_card_payment_option == 2)
				{
					$message .= "<tr><td colspan=\"2\" width=\"100%\">I would like to set up a new e-commerce merchant account with an EasyWay certified partner</td></tr>";
				}
				else if($credit_card_payment_option == 3)
				{
					$message .= "<tr><td colspan=\"2\" width=\"100%\">I plan to open an e-commerce merchant account on my own</td></tr>";
				}
			}
			else
			{
				$message .= "<tr><td width=\"50%\"><b>Will you accept credit cards</b></td>
			<td width=\"50%\">$credit_card_payment</td></tr>";
			}
			
			//setup
			$message .= "<tr><td colspan=\"2\" width=\"100%\">&nbsp;</td></tr>" .
			"<tr><td colspan=\"2\" width=\"100%\" style=\"border: 1px solid #d1d1d1;background-color:#f2f2f2;\"><b>Setup Instructions</b></td></tr>";
			
			if($domain_name != "")
			{
				$message .= "<tr><td width=\"50%\"><b>Existing domain name</b></td>
			<td width=\"50%\">$domain_name</td></tr>";
			}
			else if($desired_domain_name != "")
			{
				$message .= "<tr><td width=\"50%\"><b>Desired domain name</b></td>
			<td width=\"50%\">$desired_domain_name</td></tr>";
			}
			
			$message .= "<tr style=\"background-color:#f2f2f2;\"><td colspan=\"2\" width=\"100%\"><b>Website Integration Choice</b></td></tr>";
			if($wesbite_integration == 1)
			{
				$message .= "<tr><td colspan=\"2\" width=\"100%\">I am adding the EasyWay Ordering menu to my existing website</td></tr>";
			}
			else if($wesbite_integration == 2)
			{
				$message .= "<tr><td colspan=\"2\" width=\"100%\">I will use the EasyWay Ordering menu as my website</td></tr>";
			}
			else if($wesbite_integration == 3)
			{
				$message .= "<tr><td colspan=\"2\" width=\"100%\">I would like EasyWay to build me a custom website</td></tr>";
			}
			
			$message .= "<tr style=\"background-color:#f2f2f2;\"><td colspan=\"2\" width=\"100%\"><b>Hosting Information Choice</b></td></tr>";
			if($hosting_integration_type == 1)
			{
				$message .= "<tr><td colspan=\"2\" width=\"100%\"><b>Hosting Info:</b></td></tr>".
				"<tr><td width=\"50%\"><b>Name of hosting company</b></td>
				<td width=\"50%\">$hosting_provider_name</td></tr>" .
				"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Account number or username</b></td>
				<td width=\"50%\">$hosting_username</td></tr>" .
				"<tr><td width=\"50%\"><b>Account Password</b></td>
				<td width=\"50%\">$hosting_password</td></tr>";
				
			}
			else if($hosting_integration_type == 2)
			{
				$message .= "<tr><td colspan=\"2\" width=\"100%\"><b>Web designer Info:</b></td></tr>".
				"<tr><td width=\"50%\"><b>Name of Webmaster</b></td>
				<td width=\"50%\">$web_designer_name</td></tr>" .
				"<tr style=\"background-color:#f2f2f2;\"><td width=\"50%\"><b>Webmaster Phone Number</b></td>
				<td width=\"50%\">$web_designer_phone</td></tr>" .
				"<tr><td width=\"50%\"><b>Webmaster Email Address</b></td>
				<td width=\"50%\">$web_designer_email</td></tr>";
			}
			else if($hosting_integration_type == 3)
			{
				$message .= "<tr><td colspan=\"2\" width=\"100%\">I will apply the updates myself </td></tr>";
			}
			
			$message .= "</table>";
		;
		
		$subject = "Menu Settings Uploaded for '$restaurant_name'";
		$to = "menus@easywayordering.com";
		//$to = "raja@qualityclix.com";
		$html = true;
		$objMail->sendTo($message, $subject, $to, $html);
	}

	// if new license created then email
	if($is_license_new) {
		$objMail->clearattachments();
		$to = "cwilliams@easywayordering.com";
		//$to = "raja@qualityclix.com";
		$from = "From:admin@easywayordering.com";
		$subject = "License Created";
		$body = "A new license automatically has been created by Self Signup Wizard for reseller '$reseller_id' ";
		$objMail->sendTo($body, $subject, $to, true);
	}

	header("location:/c_panel/login.php");
	// login current user
	// $user = mysql_query("SELECT * FROM users WHERE id='$client_id'");
	// if(mysql_num_rows($user) > 0) {
		// $user = mysql_fetch_assoc($user);
		// $_SESSION['admin_session_user_name'] = $user["username"];
		// $_SESSION['admin_session_pass'] = $user["password"];
		// header("location:/c_panel/?mod=resturant");
	// } else {
		// $error = "Client and restaurant added. Unable to login. Try again later.";
	// }
} else {
	$error = "Unable to add new client and restaurant. Try again later.";
}
?>