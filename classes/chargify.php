<?php
class chargifyMeteredUsage 
{
	public function sendMeteredUsageToChargify($restaurant_id, $amount, $payment_method) 
	{
		$qry_str = "SELECT c.api_access_key
					,r.chargify_subscription_id
					,c.hosted_page_url
					,c.credit_card_orders_quantity
					,c.credit_card_orders_amount
					,c.cash_orders_quantity
					,c.cash_orders_amount
					,c.rapid_reorder_text_messages
					FROM resturants r
					LEFT JOIN chargify_products c
					ON r.chargify_product_id=c.settings_id AND c.api_access_key!=''
					WHERE r.id=$restaurant_id";
		$resturants = mysql_query($qry_str);
		if(mysql_num_rows($resturants) > 0) 
		{	
			$resturant = mysql_fetch_assoc($resturants);
			$chargify_subscription_id = $resturant["chargify_subscription_id"];
			$credit_card_orders_quantity = $resturant["credit_card_orders_quantity"];
			$credit_card_orders_amount = $resturant["credit_card_orders_amount"];
			$cash_orders_quantity = $resturant["cash_orders_quantity"];
			$cash_orders_amount = $resturant["cash_orders_amount"];
			$rapid_reorder_text_messages = $resturant["rapid_reorder_text_messages"];
			$return_url = $resturant["hosted_page_url"];
                        
			$return_url= preg_replace("/^https:\/\//", "", $return_url);
			$return_url= preg_replace("/^http:\/\//", "", $return_url);
			
			$subdomain = substr($return_url, 0, strpos($return_url, '.')-strlen($return_url));
			$args = array(
				"subdomain" => $subdomain
				,"chargify_subscription_id" => $chargify_subscription_id
				,"component_id" => 0
				,"data" => array()
			);
			$result = array();
			if($payment_method == "Credit Card") 
			{
				// credit card orders count
				if($credit_card_orders_quantity != 0) 
				{
					$args["component_id"] = $credit_card_orders_quantity;
					$args["data"] = array(
						"usage" => array(
							"quantity" => 1,
							"memo" => "credit card orders count"
						)
					);
					$result[] = $this->createChargifyMeteredUsage($args);
				}
				// credit card orders amount
				if($credit_card_orders_amount != 0) 
				{	
					$args["component_id"] = $credit_card_orders_amount;
					$args["data"] = array(
						"usage" => array(
							"quantity" => $amount,
							"memo" => "credit card orders amount"
						)
					);
					$result[] = $this->createChargifyMeteredUsage($args);
				}
			} 
			else if($payment_method == "Cash") 
			{
				// cash orders count
				if($cash_orders_quantity != 0) 
				{
					$args["component_id"] = $cash_orders_quantity;
					$args["data"] = array(
						"usage" => array(
							"quantity" => 1,
							"memo" => "cash orders count"
						)
					);
					$result[] = $this->createChargifyMeteredUsage($args);
				}
				// cash orders amount
				if($cash_orders_quantity != 0) 
				{
					$args["component_id"] = $cash_orders_amount;
					$args["data"] = array(
						"usage" => array(
							"quantity" => $amount,
							"memo" => "cash orders amount"
						)
					);
					$result[] = $this->createChargifyMeteredUsage($args);
				}
			}
		} 
		else 
		{
			echo "Unable to locate restaurant"; //die(0);
		}
		return $result;
	}
	
	private function createChargifyMeteredUsage($args) 
	{	
		//$url = "https://[@subdomain].chargify.com/subscriptions/[@subscription.id]/components/[@component.id]/usages.json";
		$url = "https://". $args["subdomain"] .".chargify.com/subscriptions/". $args["chargify_subscription_id"] ."/components/". $args["component_id"] ."/usages.json";
                
                Log::write('CHARGIFY Post Array - Create Chargify Metered Usage URL', $url, 'chargify', 1);
                
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
		
		// $data = array(
			// "usage" => array(
				// "quantity" => 5,
				// "memo" => "my own memo"
			// )
		// );
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args["data"]));
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
                
                Log::write('CHARGIFY Response Array - Create Chargify Metered Usage', print_r($result,true), 'chargify');

		return $result;
	}
}
?>