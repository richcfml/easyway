<?
$loggedinuser->logout();
	@mysql_close($mysql_conn);
	 redirect($SiteUrl .$objRestaurant->url ."/?item=menu&wp_api=load_resturant" );exit;

?>