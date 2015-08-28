<?php
	require_once("../includes/config.php");
	include("../includes/class.phpmailer.php");
	if(empty($_REQUEST["action"])) exit(0);
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
		$username = dbAbstract::returnRealEscapedString($username);
		$users = dbAbstract::Execute("SELECT COUNT(*) count FROM users WHERE username LIKE '$username'");
		$users = dbAbstract::returnAssoc($users);
		echo $users["count"];
	}
	
	function ajax_is_restaurant_name_available() {
		$restaurant_name = iin_array($_REQUEST, "restaurant_name", "");
		$restaurant_name = dbAbstract::returnRealEscapedString($restaurant_name);
		$restaurant_names = dbAbstract::Execute("SELECT COUNT(*) count FROM resturants WHERE name LIKE '$restaurant_name'");
		$restaurant_names = dbAbstract::returnAssoc($restaurant_names);
		echo $restaurant_names["count"];
	}
	
	function ajax_authenticate_user() {
		$username = iin_array($_REQUEST, "username", "");
		$password = iin_array($_REQUEST, "password", "");
		if(!empty($_REQUEST["username"]) && !empty($_REQUEST["password"])) {
			$username = dbAbstract::returnRealEscapedString($username);
			$password = dbAbstract::returnRealEscapedString($password);
                        $mRow = dbAbstract::ExecuteObject("SELECT salt FROM users WHERE username='".$username."'"));
                        if ($mRow)
                        {
                            $mSalt = $mRow->salt;
                            $epassword=hash('sha256', prepareStringForMySQL($password).$mSalt);
			$user = dbAbstract::Execute("SELECT id FROM users WHERE username LIKE '$username' AND epassword LIKE '$password'");
			if(dbAbstract::returnRowsCount($user) > 0) {
				$user = dbAbstract::returnAssoc($user);
				echo $user["id"];
			} else {
				echo 0;
                            }
			}
		} else {
			echo 0;
		}
	}
	
	function iin_array($arr, $key, $default) {
		return empty($arr[$key]) ? $default : $arr[$key];
	}
?>
