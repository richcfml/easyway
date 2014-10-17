<?
session_start();

include("../includes/config.php");
include("../includes/class.phpmailer.php");
include("../includes/function.php");

ini_set('display_errors', 1);

ini_set('max_execution_time', 0);

//require_once 'google-api/Google_Client.php';
//require_once 'google-api/contrib/Google_AnalyticsService.php';

//$client = new Google_Client();
//$client->setApplicationName("Google Analytics PHP Starter Application");

$client_id = '668969395261-lpo61q1sjfm2fr4ihoe9d151jvmdpupi.apps.googleusercontent.com';
$client_secret = 'ZEaTFWyxGwTJ8di1TqJslbkQ';
$redirect_uri = urlencode('http://www.easywayordering.com/c_panel/get_last_month_analytics_from_google.php');
$scope = urlencode('https://www.googleapis.com/auth/analytics.readonly');
$access_type = 'offline';

if(isset($_GET["auth_code"])) {
	$loginUrl = "https://accounts.google.com/o/oauth2/auth?
		scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile&
		state=%2Fprofile&
		redirect_uri=$redirect_uri&
		response_type=code&
		client_id=$client_id&
		access_type=offline
		";
	echo '<a href="'. $loginUrl .'">OAuth 2.0</a>';

} else if(isset($_GET["code"])) {
	echo $_GET["code"];
	$oauth2token_url = "https://accounts.google.com/o/oauth2/token";
	$clienttoken_post = array(
		"code" => $_GET["code"],
		"client_id" => $client_id,
		"client_secret" => $client_secret,
		"redirect_uri" => $redirect_uri,
		"grant_type" => "authorization_code"
	);
	$fields_string = "";
	foreach($clienttoken_post as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $oauth2token_url);
	curl_setopt($ch,CURLOPT_POST, count($clienttoken_post));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	//execute post
	$result = curl_exec($ch);

	//close connection
	curl_close($ch);

	// $curl = curl_init($oauth2token_url);

	// curl_setopt($curl, CURLOPT_POST, true);
	// curl_setopt($curl, CURLOPT_POSTFIELDS, $clienttoken_post);
	// curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	// $json_response = curl_exec($curl);
	// curl_close($curl);

	$authObj = json_decode($result);
	var_dump($authObj);	
}

exit(0);

?>