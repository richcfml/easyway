<?
function get_file_extesion($file_name) {
	return substr($file_name, strrpos($file_name, '.')+1);
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
$chargify_product = mysql_query("SELECT settings_id FROM chargify_products WHERE product_id='". $product_id ."'");
$product_id = 0;
//echo $product_id;
if(mysql_num_rows($chargify_product) > 0) {
	$chargify_product = mysql_fetch_assoc($chargify_product);
	$product_id = $chargify_product["settings_id"];
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

$sql = 	"INSERT INTO resturants 
	SET name= '".addslashes($restaurant_name)."'
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
	";
mysql_query($sql) or die(mysql_error());
//echo $sql;
$catid = mysql_insert_id();

if(!empty($catid)) {
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
		
		$message = "Menu Settings Uploaded using Self Form Wizard for <b>$restaurant_name</b><br />" .
			"<b>Menu Name:</b> $menu_name<br />" .
			"<b>Menu Special Instructions:</b><br /> $menu_special_instructions"
		;
		$subject = "Menu Settings Uploaded for '$restaurant_name'";
		$to = "menus@easywayordering.com";
		$to = "aliraza@qualityclix.com";
		$html = true;
		$objMail->sendTo($message, $subject, $to, $html);
	}

	// if new license created then email
	if($is_license_new) {
		$objMail->clearattachments();
		$to = "cwilliams@easywayordering.com";
		$to = "aliraza@qualityclix.com";
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