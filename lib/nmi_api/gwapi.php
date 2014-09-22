<?php
define("APPROVED", 1);
define("DECLINED", 2);
define("ERROR", 3);
class gwapi {
 
	// Initial Setting Functions
 
	function setLogin($username, $password) {/*{{{*/
		$this->login['username'] = $username;
		$this->login['password'] = $password;
	}/*}}}*/
 
	function setOrder() {/*{{{*/
        $this->order['orderid']          = date('YmdHis');
        $this->order['orderdescription'] = 'Customer Order';
        $this->order['tax']              = 0.00;
        $this->order['shipping']         = 0.00;
        $this->order['ponumber']         = '';
        $this->order['ipaddress']        = $_SERVER["REMOTE_ADDR"];
	}/*}}}*/
 
	function setBilling($firstname,
            $lastname,
            $address1,             
            $city,
            $state,
            $zip,          
            $phone,
            $email,$tokenization
            ) {/*{{{*/
        $this->billing['firstname'] = $firstname;
        $this->billing['lastname']  = $lastname;         
        $this->billing['address1']  = $address1; 
        $this->billing['city']      = $city;
        $this->billing['state']     = $state;
        $this->billing['zip']       = $zip;
        $this->billing['country']   = 'USA';
        $this->billing['phone']     = $phone;
       if($tokenization==1) $this->billing['customer_vault']     = 'add_customer';
	    $this->billing['email']     = $email;
        
	}/*}}}*/
 
	function setShipping($firstname,
            $lastname,           
            $address1,         
            $city,
            $state,
            $zip,
			$email) {/*{{{*/
        $this->shipping['firstname'] = $firstname;
        $this->shipping['lastname']  = $lastname;
        $this->shipping['address1']  = $address1;
        $this->shipping['city']      = $city;
        $this->shipping['state']     = $state;
        $this->shipping['zip']       = $zip;
        $this->shipping['country']   = 'USA';
        $this->shipping['email']     = $email;
	}/*}}}*/
 
	// Transaction Functions
 
	function doSale($amount, $ccnumber, $ccexp,$card_token) {/*{{{*/
 
		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		
		// Sales Information
		if($card_token==0){
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		
		$query .= "cvv=" . urlencode($cvv) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
	 
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		 
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		 
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "customer_vault=" . urlencode($this->billing['customer_vault']) . "&";
		$query .= "type=sale";
		}else {
				$query .= "billing_method=recurring&";
				$query .= "customer_vault_id=". urlencode($card_token) ."";
				 
		 }
 
		return $this->_doPost($query);
 
	}/*}}}*/
 

	function addToVault($ccnumber, $ccexp) {/*{{{*/
 
		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
	 
		 
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		 
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
	 
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		 
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		 
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "customer_vault=" . urlencode($this->billing['customer_vault']);
		 
 
		return $this->_doPost($query);
 
	}/*}}}*/
	
	function doDelete($pCustomer_vault_id)
 	{
		$query  = "";
		// Login Information
		$query .= "username=".urlencode($this->login['username'])."&";
		$query .= "password=".urlencode($this->login['password'])."&";
		$query .= "customer_vault_id=".$pCustomer_vault_id."&";
		$query .= "customer_vault=delete_customer";
		return $this->_doPost($query);
 	}
 
	function doAuth($amount, $ccnumber, $ccexp, $cvv="") {/*{{{*/
 
		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "cvv=" . urlencode($cvv) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=auth";
		return $this->_doPost($query);
 
	}/*}}}*/
 
	function doCredit($amount, $ccnumber, $ccexp) {/*{{{*/
 
		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		
		$query .= "type=credit";
		return $this->_doPost($query);
 
	}/*}}}*/
 
	function doOffline($authorizationcode, $amount, $ccnumber, $ccexp) {/*{{{*/
 
		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "authorizationcode=" . urlencode($authorizationcode) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=offline";
		return $this->_doPost($query);
 
	}/*}}}*/
 
	function doCapture($transactionid, $amount =0) {/*{{{*/
 
		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		if ($amount>0) {
			$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		}
		$query .= "type=capture";
		return $this->_doPost($query);
 
	}/*}}}*/
 
	function doVoid($transactionid) {/*{{{*/
 
		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		$query .= "type=void";
		return $this->_doPost($query);
 
	}/*}}}*/
 
	function doRefund($transactionid, $amount = 0) {/*{{{*/
 
		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		if ($amount>0) {
			$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		}
		$query .= "type=refund";
		return $this->_doPost($query);
 
	}/*}}}*/
 
	function _doPost($query) {/*{{{*/
                
                parse_str($query,$post_array);
                Log::write("NMI Post Query - ".$post_array['type'], print_r($post_array, true), 'nmi');
		
                $ch = curl_init();
	 	curl_setopt($ch, CURLOPT_URL, "https://secure.nmi.com/api/transact.php");
  		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($ch, CURLOPT_POST, 1);
 
		if (!($data = curl_exec($ch))) {
			die("error" .$query);
			return ERROR;
		}
		curl_close($ch);
		unset($ch);
		
		 
		
		$data = explode("&",$data);
		for($i=0;$i<count($data);$i++) {
			$rdata = explode("=",$data[$i]);
			$this->responses[$rdata[0]] = $rdata[1];
		}
                
		return $this->responses['response'];
	}/*}}}*/
 
	 function getGUID(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000*rand());//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				.chr(125);// "}"
			return $uuid;
		}
	}

}

?>