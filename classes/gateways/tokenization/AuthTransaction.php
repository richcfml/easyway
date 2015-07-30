<?php

define('JSON_SERVER_RESPONSE_BREAKER', '|_|_|_|_|_|_|_|_|');
require_once ($r = $r ? $r : '').'lib/authorize_api/AuthNetCIM.php';

Class AuthorizeNetTransactionModel{

    public static function saveTransaction($txn_id, $orderid, $rest_id, $amount, $cc_no){
       
        $time = time();
        return dbAbstract::Execute('INSERT INTO auth_transactions (rest_id, order_id, txn_id, time, amount, status) VALUES ('.dbAbstract::returnRealEscapedString($rest_id).', '.dbAbstract::returnRealEscapedString($orderid).', "'.dbAbstract::returnRealEscapedString($txn_id).'", '.dbAbstract::returnRealEscapedString($time).', "'.dbAbstract::returnRealEscapedString($amount).'", 1)', 0 , 2);
    }

    public static function getTransaction($txn_id){
        return dbAbstract::ExecuteObject('SELECT * FROM auth_transactions WHERE txn_id = "'.dbAbstract::returnRealEscapedString($txn_id).'"');
    }
    
    public static function getTransactionByOrder($order_id){
        return dbAbstract::ExecuteObject('SELECT * FROM auth_transactions WHERE order_id = "'.dbAbstract::returnRealEscapedString($order_id).'"');
    }

    public static function refundTransaction($txn_id, $auth){
        $transaction = AuthorizeNetTransactionModel::getTransaction($txn_id);
        if($transaction){
            if(!AuthorizeNetTransactionModel::isConfirmed($transaction->order_id)){
                echo $transaction->cc_no;
                $response = $auth->credit($txn_id, ''.$transaction->amount, ''.$transaction->cc_no);
                if($response->approved){
                    AuthorizeNetTransactionModel::setRefund($transaction->order_id);
                }else{
                    return $response->error_message;
                }

            }else{
                return 'order already confirmed!';
            }
        }else{
            return false;
        }
    }
    public static function isConfirmed($order_id){
        $order = dbAbstract::ExecuteObject('SELECT * FROM ordertbl WHERE id = "'.dbAbstract::returnRealEscapedString($order_id).'"');
        return $order->order_confirm == 1;
    }
    public static function setRefund($order_id){
        dbAbstract::Update('UPDATE auth_transactions set status = 2 WHERE id = "'.dbAbstract::returnRealEscapedString($order_id).'"');
        
    }

}
Class AuthorizeNetTokenizationModel{
    private $cim = null;
    private $profileId = null;
    private $email = null;


    public function __construct($login, $password){
       
        $this->cim = new AuthnetCIM($login, $password, $AuthorizeDotNetSandBox); //$AuthorizeDotNetSandBox is defined in includes/config.php

        //echo "<pre>"; print_r($this->cim);exit;
        }
    
    public function loadProfile($email, $rest_id){
       
        $profile = dbAbstract::ExecuteObject('SELECT * FROM auth_user_profile WHERE customer_id = "'.dbAbstract::returnRealEscapedString($email).'" AND rest_id = "'.dbAbstract::returnRealEscapedString($rest_id).'"');
   
        if(!$profile)return false;

        $this->email = $email;
        $this->profileId = $profile->profile_id;
        $this->customerId = $profile->customer_id;
        
        return $profile;
        
    }


    public function returnAuthNet($login, $password, $sandbox = true){
        $this->cim = new AuthnetCIM($login, $password, $AuthorizeDotNetSandBox); //$AuthorizeDotNetSandBox is defined in includes/config.php
    }
    
    public function createProfile($email, $rest_id){

        
        $customer = dbAbstract::ExecuteObject('SELECT * FROM customer_registration WHERE cust_email = "'.dbAbstract::returnRealEscapedString($email).'" AND resturant_id = "'.dbAbstract::returnRealEscapedString($rest_id).'"');
        
        $this->cim->setParameter('email', $email);
        $this->cim->setParameter('description', 'Profile for rest#'.$rest_id.' : '.$email); // Optional
        $this->cim->setParameter('merchantCustomerId', $customer->id);
        $this->cim->createCustomerProfile();
       // print_r($this->cim->getAuthCode());exit;
        $this->profileId = $this->cim->getProfileID();
        $this->customerId = $customer->id;
        $this->email = $email;
        if($this->profileId>0){
        dbAbstract::Insert('INSERT INTO auth_user_profile (customer_id, rest_id, profile_id, email, time) VALUES ("'.$customer->id.'", "'.$rest_id.'", "'.$this->profileId.'", "'.$email.'", "'.time().'")');
        $profile_id =  $this->profileId;
        return $profile_id;
        }
        else
        {
            return $this->cim->getResponse();
        }
    }
    
    public function saveCCToken($name, $ccno, $exp_year, $exp_month, $fname, $lname, $addr, $city, $state, $zip, $country, $phno, $fax,$custId,$rest_id){

      //$userprofile = 'SELECT * FROM auth_user_profile WHERE customer_id = "'.dbAbstract::returnRealEscapedString($custId).'" AND rest_id = "'.dbAbstract::returnRealEscapedString($rest_id).'"';
      //echo $userprofile;exit;
        $userprofile = dbAbstract::ExecuteObject('SELECT * FROM auth_user_profile WHERE customer_id = "'.dbAbstract::returnRealEscapedString($custId).'" AND rest_id = "'.dbAbstract::returnRealEscapedString($rest_id).'"');
        $profile_id=$userprofile->profile_id;
       
        $this->cim->setParameter('customerProfileId', $profile_id);
        $this->cim->setParameter('billToFirstName', $fname);
        $this->cim->setParameter('billToLastName', $lname);
        $this->cim->setParameter('billToAddress', $addr);
        $this->cim->setParameter('billToCity', $city);
        $this->cim->setParameter('billToState', $state);
        $this->cim->setParameter('billToZip', $zip);
        $this->cim->setParameter('billToCountry', $country);
        $this->cim->setParameter('billToPhoneNumber', $country);
        $this->cim->setParameter('billToFaxNumber', $fax);
        $this->cim->setParameter('cardNumber', $ccno);
        //$this->cim->setParameter('cardCode', '1234');
        $this->cim->setParameter('expirationDate', "20".$exp_year.'-'.$exp_month);
        
        $this->cim->createCustomerPaymentProfile();
        
        $payment_profile_id = $this->cim->getPaymentProfileId();

        if($payment_profile_id>0){

            dbAbstract::Insert('INSERT INTO auth_cc_tokens (name, customer_id, ccno, token, status, time) VALUES ("'.$name.'", "'.$custId.'", "'.$ccno.'", "'.$payment_profile_id.'", 1, "'.time().'")');
            return $payment_profile_id;

        }
            else
            {
                return $this->cim->getResponse();
            }
    }


    
    public function useToken($amount,$payment_profile_id,$shipping_id,$profile_id){

       //echo  $amount."sdd".$this->profileId;exit;
       $qry = 'INSERT INTO cydne_log (ToPhoneNumber, MessageID, MatchedMessageID, ReferenceId,FromPhoneNumber,Message,LogTime,SMSType) VALUES ("'.$amount.'", "'.dbAbstract::returnRealEscapedString($payment_profile_id).'", "'.dbAbstract::returnRealEscapedString($profile_id).'", "'.dbAbstract::returnRealEscapedString($shipping_id).'","'.dbAbstract::returnRealEscapedString(111).'","'.dbAbstract::returnRealEscapedString(222).'","'.dbAbstract::returnRealEscapedString(1111).'","'.dbAbstract::returnRealEscapedString(3).'")';
		dbAbstract::Insert($qry);
        $this->cim->setParameter('amount', $amount);
        $this->cim->setParameter('customerProfileId', $profile_id);
        $this->cim->setParameter('customerPaymentProfileId', $payment_profile_id);
        $this->cim->setParameter('customerShippingAddressId', $shipping_id);
        //$this->cim->setParameter('cardCode', '1234');
        $this->cim->setLineItem('12', 'test item', 'it lets you test stuff', 1, '1.00');
        $this->cim->createCustomerProfileTransaction();
        $transaction_id = $this->cim->getTransactionID();
			
       // print_r("sasd".$this->cim->createCustomerProfileTransaction());
        // Get the payment profile ID returned from the request
        if ($this->cim->isSuccessful())
        {   
            return $transaction_id;
        }
        else
            {
                return $this->cim->getResponse();
            }
        //echo $$success."breake.";
       // echo "Success".$this->cim->isSuccessful();
       // echo "Failed".$this->cim->isError();exit;
    }

    public function getshippingid($x_first_name, $x_last_name,$x_address,$x_city,$x_state,$x_zip,$x_phone,$rest_id,$payment_profile_id,$email,$custId){
       
        $this->cim->setParameter('customerProfileId', $this->profileId);
        $this->cim->setParameter('shipToFirstName', $x_first_name);
        $this->cim->setParameter('shipToLastName', $x_last_name);
        $this->cim->setParameter('shipToCompany', 'ozzies');
        $this->cim->setParameter('shipToAddress',$x_address);
        $this->cim->setParameter('shipToCity', $x_city);
        $this->cim->setParameter('shipToState', $x_state);
        $this->cim->setParameter('shipToZip', $x_zip);
        $this->cim->setParameter('shipToCountry', 'USA');
        $this->cim->setParameter('shipToPhoneNumber', $x_phone);
        //$this->cim->setParameter('shipToFaxNumber', $customer->);

        $this->cim->createCustomerShippingAddress();
        $shipping_id = $this->cim->getCustomerAddressId();
		
        if($shipping_id>0)
        {
            dbAbstract::Insert('INSERT INTO auth_shipping_details (customer_id, rest_id, payment_profile_id, FirstName,LastName,Address,City,state,zip_code,Email,phone_number,cust_shipping_id) VALUES ("'.$custId.'", "'.$rest_id.'", "'.$payment_profile_id.'", "'.dbAbstract::returnRealEscapedString($x_first_name).'","'.dbAbstract::returnRealEscapedString($x_last_name).'","'.dbAbstract::returnRealEscapedString($x_address).'","'.dbAbstract::returnRealEscapedString($x_city).'","'.dbAbstract::returnRealEscapedString($x_state).'","'.dbAbstract::returnRealEscapedString($x_zip).'","'.dbAbstract::returnRealEscapedString($x_phone).'","'.dbAbstract::returnRealEscapedString($email).'","'.$shipping_id.'")');
            return $shipping_id;
        }
        else
        {
            return $this->cim->getResponse();
        }
    }
}




?>
