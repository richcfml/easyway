<?
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/lib/nmi_api/gwapi.php';
		 
$gw = new gwapi;
$gw->setLogin($objRestaurant->authoriseLoginID, $objRestaurant->transKey);
 
 
$gw->setBilling($loggedinuser->cust_your_name,$loggedinuser->LastName, $loggedinuser->cust_odr_address, $loggedinuser->cust_ord_city, $loggedinuser->cust_ord_state,$loggedinuser->cust_ord_zip,$loggedinuser->cust_phone1,$loggedinuser->cust_email,1);
 $response = $gw->addToVault($x_card_num,$x_exp_date);
	$success=0;
	if ($response==APPROVED) {
		$success=1;
		$gateway_token=$gw->responses['customer_vault_id'] ;
	} 
			
?>