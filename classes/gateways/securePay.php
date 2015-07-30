<?php
  require_once 'lib/securepay_api/securepay_api.php';
		 
$gw = new securepayapi;
$gw->setLogin($objRestaurant->authoriseLoginID, $objRestaurant->transKey);
extract($_POST);
$gw->setOrder();
$gw->setBilling($x_first_name,$x_last_name, $x_address, $x_city, $x_state,$x_zip,$x_email);

$response = $gw->doSale($cart_total,$x_card_num,$x_exp_date);

 if ($response==APPROVED) {
			$_POST['payment_method']=1;
			$_POST['invoice_number']=$gw->order['orderid'];
			$success=1;
			//echo "<pre>";print_r($gw);echo "</pre>";
			//@mysql_close($mysql_conn);
			die();
			} else {
			$_SESSION['GATEWAY_RESPONSE']=$gw->responses['responsetext'];
                        if (isset($api) && $api == true) {
                            return;
                        }
			//@mysql_close($mysql_conn);
			header("location: ". $SiteUrl .$objRestaurant->url ."/?item=failed&test=1&response_code=".$response->response_code."" );exit;
 }
?>