<?php
 if (isset($api) && $api == true)
    {
    require_once '../lib/authorize_api/AuthorizeNet.php';
    }
 else
    {
    require_once 'lib/authorize_api/AuthorizeNet.php';
    }

    require_once 'Auth_Transaction.php';
    $gw = new AuthNetTokenizationModel($objRestaurant->authoriseLoginID, $objRestaurant->transKey);
    extract($_POST);
    
    $profile=$gw->loadProfile($loggedinuser->cust_email, $objRestaurant->id);
    
    if(!empty ($profile ))
    {
    $payment_profileid = $gw->saveCCToken($objRestaurant->name,$x_card_num,substr($x_exp_date,2,4),substr($x_exp_date,0,2),$loggedinuser->cust_your_name,$loggedinuser->LastName,$loggedinuser->cust_odr_address,$loggedinuser->cust_ord_city,$loggedinuser->cust_ord_state,$loggedinuser->cust_ord_zip,"USA",$loggedinuser->cust_phone1,"1123",$loggedinuser->id,$objRestaurant->id);
   
    if($payment_profileid>0 ){
        $success=1;
        $gateway_token = $payment_profileid;
   }
   else
   {
        $error_message = $payment_profileid;
   }
   

}


if(empty ($profile )){
   
  $profile=  $gw->createProfile($loggedinuser->cust_email, $objRestaurant->id);
  // if($profile>0){
   $payment_profileid = $gw->saveCCToken($objRestaurant->name,$x_card_num,substr($x_exp_date,2,4),substr($x_exp_date,0,2),$loggedinuser->cust_your_name,$loggedinuser->LastName,$loggedinuser->cust_odr_address,$loggedinuser->cust_ord_city,$loggedinuser->cust_ord_state,$loggedinuser->cust_ord_zip,"USA",$loggedinuser->cust_phone1,"1123",$loggedinuser->id,$objRestaurant->id);
    
   if($payment_profileid>0){
        $success=1;
        $gateway_token = $payment_profileid;
    }else{
          $error_message=$payment_profileid;
    }
  // }
//    else
//    {
//         $transaction_id=$profile;
//    }
//    if($transaction_id>0)
//    {
//        $response =1;
//        $gateway_token = $payment_profileid;
//        $invoice_number = $gateway_token;
//
//    }
//    else
//    {
//        $error_message = $transaction_id;
//    }
 }
//echo 'here';exit;
?>