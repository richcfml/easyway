<? 
class securepayapi {
 
	// Initial Setting Functions
 
	function setLogin($username, $password) {/*{{{*/
		$this->order['MERCH_ID'] = $username;
		$this->order['TransKey'] = $password;
	}/*}}}*/
 
	function setOrder() {/*{{{*/
		$this->order['orderid']          = date('YmdHis');
		$this->order['orderdescription'] = 'Customer Order';
	
		$this->order['FMETHOD']        = 'POST';
		$this->order['Tr_Type']        = 'SALE';
		$this->order['SEND_MAIL']        = 'Yes';
	}/*}}}*/
 
 
  
	function setBilling($firstname,
            $lastname,
            $address,             
            $city,
            $state,
            $zip,          
            $email
            ) {/*{{{*/
        $this->order['NAME'] = $firstname .' '.$lastname;
        $this->order['STREET']  = $address;         
        $this->order['CITY']      = $city;
        $this->order['STATE']     = $state;
        $this->order['ZIP']       = $zip;
        $this->order['EMAIL']     = $email;
        
	}/*}}}*/
 
 
function doSale($amount, $ccnumber, $ccexp) {
		$this->order['AMOUNT']        = $amount; 
		$this->order['cardNumber']        = $ccnumber; 
 		$this->order['expiryDate']        = $ccexp; 
					
		return $this->_doPost($this->order);
	}
 
	function _doPost($arrPost) {/*{{{*/
		
		$url="https://www.securepay.com/tokenpayment/index.cfm";
	   $fields_string ='';
		foreach($arrPost as $key=>$value) { $fields_string .= $key.'='. urlencode($value).'&'; }
		rtrim($fields_string,'&');

    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
		 CURLOPT_POST		   => true,
		 CURLOPT_POSTFIELDS	   => $fields_string
    );
	


    $ch      = curl_init( $url );
	 
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
	
	
    return $header;
	}/*}}}*/
 }
 
?>