<?php

define('JSON_SERVER_RESPONSE_BREAKER', '|_|_|_|_|_|_|_|_|');
require_once ($r = $r ? $r : '').'lib/authorize_api/AuthNetCIM.php';

Class AuthorizeNetTransactionModel{

    public static function saveTransaction($txn_id, $orderid, $rest_id, $amount, $cc_no){
       
        $time = time();
        mysql_query('INSERT INTO auth_transactions (rest_id, order_id, txn_id, time, amount, status) VALUES ('.mysql_escape_string($rest_id).', '.mysql_escape_string($orderid).', "'.mysql_escape_string($txn_id).'", '.mysql_escape_string($time).', "'.mysql_escape_string($amount).'", 1)') or die(mysql_error());
        return mysql_insert_id();
    }

    public static function getTransaction($txn_id){
        return mysql_fetch_object(mysql_query('SELECT * FROM auth_transactions WHERE txn_id = "'.mysql_escape_string($txn_id).'"'));
    }
    
    public static function getTransactionByOrder($order_id){
        return mysql_fetch_object(mysql_query('SELECT * FROM auth_transactions WHERE order_id = "'.mysql_escape_string($order_id).'"'));
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
        $order = mysql_fetch_object(mysql_query('SELECT * FROM ordertbl WHERE id = "'.mysql_escape_string($order_id).'"'));
        return $order->order_confirm == 1;
    }
    public static function setRefund($order_id){
        mysql_query('UPDATE auth_transactions set status = 2 WHERE id = "'.mysql_escape_string($order_id).'"');
        
    }

}
Class AuthorizeNetTokenizationModel{
    private $cim = null;
    private $profileId = null;
    private $email = null;


    public function __construct($login, $password){
        $this->cim = new AuthnetCIM($login, $password);

    }
    
    public function loadProfile($email, $rest_id){
        $profile = mysql_fetch_object(mysql_query('SELECT * FROM auth_user_profile WHERE email = "'.mysql_escape_string($email).'" AND rest_id = "'.mysql_escape_string($rest_id).'"'));

        if(!$profile)return false;

        $this->email = $email;
        $this->profileId = $profile->profile_id;
        $this->customerId = $profile->customer_id;
        
        return $profile;
    }


    public function returnAuthNet($login, $password, $sandbox = true){
        $this->cim = new AuthnetCIM($login, $password, $sandbox);
    }
    
    public function createProfile($email, $rest_id){

        $customer = mysql_fetch_object(mysql_query('SELECT * FROM customer_registration WHERE cust_email = "'.mysql_escape_string($email).'" AND resturant_id = "'.mysql_escape_string($rest_id).'"'));

        $this->cim->setParameter('email', $email);
        $this->cim->setParameter('description', 'Profile for rest#'.$rest_id.' : '.$email); // Optional
        $this->cim->setParameter('merchantCustomerId', $customer->id);
        $this->cim->createCustomerProfile();

        $this->profileId = $this->cim->getProfileID();
        $this->customerId = $customer->id;
        $this->email = $email;

        mysql_query('INSERT INTO auth_user_profile (customer_id, rest_id, profile_id, email, time) VALUES ("'.$customer->id.'", "'.$rest_id.'", "'.$this->profileId.'", "'.$email.'", "'.time().'")');
    }
    
    public function saveCCToken($name, $ccno, $cvv, $exp_year, $exp_month, $fname, $lname, $addr, $city, $state, $zip, $country, $phno, $fax){

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
        $this->cim->setParameter('cardCode', $cvv);
        $this->cim->setParameter('expirationDate', $exp_year.'-'.$exp_month);
        $this->cim->createCustomerPaymentProfile();
        $payment_profile_id = $this->cim->getPaymentProfileId();


        mysql_query('INSERT INTO auth_cc_tokens (name, customer_id, ccno, token, status, time) VALUES ("'.$name.'", "'.$this->customerId.'", "'.$ccno.'", "'.$payment_profile_id.'", 1, "'.time().'")');
        return $payment_profile_id;
    }
    
    public function useToken($amount){
        $this->cim->setParameter('amount', $purchase_amount);
        $this->cim->setParameter('customerProfileId', $profile_id);
        $this->cim->setParameter('customerPaymentProfileId', $payment_profile_id);
        $this->cim->setParameter('customerShippingAddressId', $shipping_profile_id);
        $this->cim->setParameter('cardCode', '123');
        $this->cim->setLineItem('12', 'test item', 'it lets you test stuff', '1', '1.00');
        $this->cim->createCustomerProfileTransaction();

        // Get the payment profile ID returned from the request
        if ($this->cim->isSuccessful())
        {
            return $this->cim->getAuthCode();
        }else{
            
        }
    }
}




?>
