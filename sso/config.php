<?php
	if($_SERVER['SERVER_NAME'] == 'localhost') {
//		$mysql_conn = mysql_connect("easywayordering.com","sysremoteuser1","e@s72ay0rder3e4r");
		$mysql_conn = mysql_connect("localhost","root","");
		mysql_select_db("easywayordering",$mysql_conn);
		$client_path = "http://localhost:88/easywayordering/";
		$google_api_key = "AIzaSyCkRkSd4hQornJOYjYMoHqi3-Wv4hVOOgg";
	} else {


		$mysql_conn = mysql_connect("localhost","easywayordering","Yh56**ew!d") or die( mysql_error()."  cannot connect...");
		mysql_select_db("easywayordering",$mysql_conn);
		//$client_path="/";

		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
				$client_path = "/";
			} else {
				$client_path = "/";
			}

		$google_api_key = "ABQIAAAAPpaOjFQ_miNP74G3g3O7oBTTwBGlz0OqYPu6tmNrU0ToxRrT5hQhlPr8PLUNIxb0D5FrOa5lJ1tp6w";



	}
?>