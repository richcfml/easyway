<?php
$result=-1;
if (isset($_POST['forgotpassword'])){
	$result=$loggedinuser->remindUserPassword($_POST['email'],$objRestaurant,$objMail);
}
if ($result===false){
	redirect($SiteUrl.$objRestaurant->url ."/?reponse=-1#forgotpassword");
	exit;
}
if ($result===true){
	redirect($SiteUrl.$objRestaurant->url ."/?reponse=1#forgotpassword");
	exit;
}
?>
    