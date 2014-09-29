<?php

define('JSON_SERVER_RESPONSE_BREAKER', '|_|_|_|_|_|_|_|_|');
require_once 'lib/authorize_api/AuthNetCIM.php';



Class AuthNetTokenizationModel{

    private $cim = null;
    private $profileId = null;
    private $email = null;


    public function __construct($login, $password){

        $this->cim = new AuthnetCIM($login, $password, false);


        }

    public function loadProfile($email, $rest_id){

        $profile = mysql_fetch_object(mysql_query('SELECT * FROM auth_user_profile WHERE customer_id = "'.mysql_escape_string($email).'" AND rest_id = "'.mysql_escape_string($rest_id).'"'));

        if(!$profile)return false;

        $this->email = $email;
        $this->profileId = $profile->profile_id;
        $this->customerId = $profile->customer_id;

        return $profile;

    }


    public function returnAuthNet($login, $password, $sandbox = false){
        $this->cim = new AuthnetCIM($login, $password, $sandbox);
    }

    public function createProfile($email, $rest_id){


        $customer = mysql_fetch_object(mysql_query('SELECT * FROM customer_registration WHERE cust_email = "'.mysql_escape_string($email).'" AND resturant_id = "'.mysql_escape_string($rest_id).'"'));

        $this->cim->setParameter('email', $email);
        $this->cim->setParameter('description', 'Profile for rest#'.$rest_id.' : '.$email);
        $this->cim->setParameter('merchantCustomerId', $customer->id);
        $this->cim->createCustomerProfile();

        $this->profileId = $this->cim->getProfileID();
        $this->customerId = $customer->id;
        $this->email = $email;
        if($this->profileId>0){
            mysql_query('INSERT INTO auth_user_profile (customer_id, rest_id, profile_id, email, time) VALUES ("'.$customer->id.'", "'.$rest_id.'", "'.$this->profileId.'", "'.$email.'", "'.time().'")');
            $profile_id =  $this->profileId;
            return $profile_id;
        }
        else
        {
            return $this->cim->getResponse();
        }
    }

    public function saveCCToken($name, $ccno, $exp_year, $exp_month, $fname, $lname, $addr, $city, $state, $zip, $country, $phno, $fax,$custId,$rest_id){

      $userprofile = mysql_fetch_object(mysql_query('SELECT * FROM auth_user_profile WHERE customer_id = "'.mysql_escape_string($custId).'" AND rest_id = "'.mysql_escape_string($rest_id).'"'));
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

        $this->cim->setParameter('expirationDate', "20".$exp_year.'-'.$exp_month);

        $this->cim->createCustomerPaymentProfile();

        $payment_profile_id = $this->cim->getPaymentProfileId();

        if($payment_profile_id>0){

            mysql_query('INSERT INTO auth_cc_tokens (name, customer_id, ccno, token, status, time) VALUES ("'.$name.'", "'.$custId.'", "'.$ccno.'", "'.$payment_profile_id.'", 1, "'.time().'")');
            return $payment_profile_id;

        }
            else
            {
                return $this->cim->getResponse();
            }
    }



    public function useToken($amount,$payment_profile_id,$shipping_id,$profile_id){


       $qry = 'INSERT INTO cydne_log (ToPhoneNumber, MessageID, MatchedMessageID, ReferenceId,FromPhoneNumber,Message,LogTime,SMSType) VALUES ("'.$amount.'", "'.mysql_escape_string($payment_profile_id).'", "'.mysql_escape_string($profile_id).'", "'.mysql_escape_string($shipping_id).'","'.mysql_escape_string(111).'","'.mysql_escape_string(222).'","'.mysql_escape_string(1111).'","'.mysql_escape_string(3).'")';
		mysql_query($qry);
        $this->cim->setParameter('amount', $amount);
        $this->cim->setParameter('customerProfileId', $profile_id);
        $this->cim->setParameter('customerPaymentProfileId', $payment_profile_id);
        $this->cim->setParameter('customerShippingAddressId', $shipping_id);

        $this->cim->setLineItem('12', 'test item', 'it lets you test stuff', 1, '1.00');
        $this->cim->createCustomerProfileTransaction();
        $transaction_id = $this->cim->getTransactionID();


        if ($this->cim->isSuccessful())
        {
            return $transaction_id;
        }
        else
            {
                return $this->cim->getResponse();
            }

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


        $this->cim->createCustomerShippingAddress();
        $shipping_id = $this->cim->getCustomerAddressId();

        if($shipping_id>0)
        {
            mysql_query('INSERT INTO auth_shipping_details (customer_id, rest_id, payment_profile_id, FirstName,LastName,Address,City,state,zip_code,Email,phone_number,cust_shipping_id) VALUES ("'.$custId.'", "'.$rest_id.'", "'.$payment_profile_id.'", "'.mysql_escape_string($x_first_name).'","'.mysql_escape_string($x_last_name).'","'.mysql_escape_string($x_address).'","'.mysql_escape_string($x_city).'","'.mysql_escape_string($x_state).'","'.mysql_escape_string($x_zip).'","'.mysql_escape_string($x_phone).'","'.mysql_escape_string($email).'","'.$shipping_id.'")');
            return $shipping_id;
        }
        else
        {
            return $this->cim->getResponse();
        }
    }
}

 ?>