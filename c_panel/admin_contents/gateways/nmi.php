<?
if (file_exists('../lib/nmi_api/gwapi.php'))
{
	require_once '../lib/nmi_api/gwapi.php';
}
else if (file_exists('lib/nmi_api/gwapi.php'))
{
	require_once 'lib/nmi_api/gwapi.php';
}

$gw = new gwapi;
$gw->setLogin($Objrestaurant->authoriseLoginID, $Objrestaurant->transKey);
$response = $gw->doRefund($transactionid);

$result = $response == 1 ? 'Success' : 'Failure';
Log::write("NMI Response - Refund \nResult: ".$result, print_r($response, true), 'nmi' ,1);
if($response==1)
{
	$success=1;
}
$transaction_id=$gw->responses['transactionid'];
$message=$gw->responses['responsetext'];
?>