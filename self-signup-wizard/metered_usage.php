<?php
	require_once("../includes/config.php");
	
	$url = "https://[@subdomain].chargify.com/subscriptions/[@subscription.id]/components/[@component.id]/usages.json";
	
	
	$url = "https://easywayordering.chargify.com/subscriptions/3643164/components/19692/usages.json";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Accept: application/json'
	));
	curl_setopt($ch, CURLOPT_USERPWD, '2aRl08rsgL3H3WiWl5ar:x');
	curl_setopt($ch, CURLOPT_POST, true);
	
	$data = array(
		"usage" => array(
			"quantity" => 5,
			"memo" => "my own memo"
		)
	);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);

	$result = new StdClass();
	$result->response = curl_exec($ch);
	$result->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$result->meta = curl_getinfo($ch);
	
	$curl_error = ($result->code > 0 ? null : curl_error($ch) . ' (' . curl_errno($ch) . ')');

	curl_close($ch);
	
	if ($curl_error) {
		throw Exception('An error occurred while connecting to Chargify: ' . $curl_error);
	}

	echo "<pre>";
		var_dump($result);
	echo "</pre>";
?>
<?php mysqli_close($mysqli);?>