<?
	if(empty($_REQUEST["action"])) exit(0);
	
	session_start();
	
	ini_set("display_errors", 1);
	include("../includes/config.php");
	include("../includes/class.phpmailer.php");
	include("../includes/function.php");
	
	$hook = "ajax_" . $_REQUEST["action"];
	
	if(function_exists($hook)) {
		$hook();
		die(0);
	}
	
	function ajax_client_register() {
		echo "client_register";
	}
	
	function ajax_is_username_available() {
		$username = iin_array($_REQUEST, "username", "");
		$username = mysql_real_escape_string($username);
		$users = mysql_query("SELECT COUNT(*) count FROM users WHERE username LIKE '$username'") or die(mysql_error());
		$users = mysql_fetch_assoc($users);
		echo $users["count"];
	}
	
	function ajax_is_restaurant_name_available() {
		$restaurant_name = iin_array($_REQUEST, "restaurant_name", "");
		$restaurant_name = mysql_real_escape_string($restaurant_name);
		$restaurant_names = mysql_query("SELECT COUNT(*) count FROM resturants WHERE name LIKE '$restaurant_name'") or die(mysql_error());
		$restaurant_names = mysql_fetch_assoc($restaurant_names);
		echo $restaurant_names["count"];
	}
	
	function ajax_authenticate_user() {
		$username = iin_array($_REQUEST, "username", "");
		$password = iin_array($_REQUEST, "password", "");
		if(!empty($_REQUEST["username"]) && !empty($_REQUEST["password"])) {
			$username = mysql_real_escape_string($username);
			$password = mysql_real_escape_string($password);
			$user = mysql_query("SELECT id FROM users WHERE username LIKE '$username' AND password LIKE '$password'") or die(mysql_error());
			if(mysql_num_rows($user) > 0) {
				$user = mysql_fetch_assoc($user);
				echo $user["id"];
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	}
	
	function iin_array($arr, $key, $default) {
		return empty($arr[$key]) ? $default : $arr[$key];
	}
?>