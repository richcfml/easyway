<?
$loggedinuser->logout();
	@mysql_close($mysql_conn);
	 redirect($SiteUrl .$objRestaurant->url ."/?item=menu" );exit;

?>