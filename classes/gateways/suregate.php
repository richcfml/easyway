<?php
$mXML ="";
$mURL = "";
$mUserName = "";
$mPassword = "";
extract($_POST);
	
if(!isset($card_token)) //Checking that if the transaction is made from Existing Creditcard Dropdown.
{
    $card_token=0; //It means that transaction is made using a new Creditcard.
}

if ($card_token!=0) //Tokenization is ON and transaction is made from existing credit card.
{
    $CarkTokenOrderTbl = $card_token;
    $mURL = $SureGateURL."ws/cardsafe.asmx/ProcessCreditCard"; //$SureGateURL is defined in includes/config.php
    $mUserName = $objRestaurant->authoriseLoginID;
    $mPassword = $objRestaurant->transKey;
    $mTransType = "Sale";
    $mCardToken = $card_token;
    $mTokenMode = "Default";
    $mInvNum = "";
    $mPNRef	= "";
    $mExtData = "";
    $mAmount = 0;
    if ($SureGateTestFlag) // $SureGateTestFlag is defined in Config
    {
        $mAmount = round($cart_total); //Because for test credentials only round figure amount is accepted by Gateway
    }
    else
    {
        $mAmount = $cart_total;
    }

    $mPostString = "UserName=".$mUserName."&Password=".$mPassword."&TransType=".$mTransType."&CardToken=".$mCardToken."&TokenMode=".$mTokenMode."&Amount=".$mAmount."&InvNum=".$mInvNum."&PNRef=".$mPNRef."&ExtData=".$mExtData;
    Log::write('SureGate Post Variables - Token Payment', $mPostString, 'suregate');
}
else //All other cases including Tokenization OFF and Tokenization ON and transaction made from new credit card.
{
    $mURL = $SureGateURL."ws/transact.asmx/ProcessCreditCard"; //$SureGateURL is defined in includes/config.php
    $mUserName = $objRestaurant->authoriseLoginID;
    $mPassword = $objRestaurant->transKey;
    $mTransType = "Sale";
    $mCardNum = $x_card_num;
    $mExpDate = $x_exp_date;
    $mAmount = 0;
    if ($SureGateTestFlag) // $SureGateTestFlag is defined in Config
    {
        $mAmount = round($cart_total); //Because for test credentials only round figure amount is accepted by Gateway
    }
    else
    {
        $mAmount = $cart_total;
    }
    $mNameOnCard = $loggedinuser->cust_your_name;
    $mMagData = "";
    $mInvNum = "";
    $mPNRef	= "";
    $mZip = "";
    $mStreet = "";
    $mCVNum = "";
    $mExtData = "";

    $mPostString = "UserName=".$mUserName."&Password=".$mPassword."&TransType=".$mTransType."&CardNum=".$mCardNum."&ExpDate=".$mExpDate."&Amount=".$mAmount."&MagData=".$mMagData."&NameOnCard=".$mNameOnCard."&InvNum=".$mInvNum."&PNRef=".$mPNRef."&Zip=".$mZip."&Street=".$mStreet."&ExtData=".$mExtData."&CVNum=".$mCVNum;
    Log::write('SureGate Post Variables - New Credit Card Payment', $mPostString, 'suregate');
}

$ch = curl_init(); 
curl_setopt ($ch, CURLOPT_URL, $mURL);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $mPostString);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_TIMEOUT, 120);			
curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
unset($ch);

$mRapidReOrderFlag=0;

if (isset($repid_payment))
{
    if ($repid_payment==1)
    {
        $mRapidReOrderFlag=1; //For Rapid Re-Ordering (Order was requested/paid by SMS)
    }
}

if ($mRapidReOrderFlag==0) //Not for Rapid Re-Ordering (Order was not requested/paid by SMS)
{
    if ($result) 
    {
        $mTransaction = simplexml_load_string($result); 
        /*$tmp= new SimpleXMLElement($result);
        echo($tmp->asXML());*/
        if ($mTransaction)
        {
            if ($mTransaction->Result=="0")
            {
                $mInvoiceNumber = date('YmdHis');
                if(!isset($card_token))
                {
                    $card_token=0;
                }

                if($card_token!=0) 
                {
                    $tokenization=1;
                }

                if(!isset($tokenization)) 
                {
                    $card_token=0;
                    $objRestaurant->tokenization=0;
                }
                                
                $cart->PNRef = (string) $mTransaction->PNRef;
                $cdynePNRef = (string) $mTransaction->PNRef;
                $_POST['transaction_id'] = $mTransaction->PNRef;
                $_POST['payment_method']=1;
                $_POST['invoice_number']=$mInvoiceNumber;

                if ($objRestaurant->tokenization==1) //Tokenization Enabled which means its possible that User may want to save the Token.
                {
                    if (isset($tokenization)) //Save Creditcard Token
                    {
                        if ($tokenization==1)
                        {
                            $mCustomerKey = "";
                            $mSQL = "SELECT IFNULL(CustomerKey, '') AS  CustomerKey FROM  customer_registration WHERE id=".$loggedinuser->id;
                            $mRes = mysql_query($mSQL);
                            $mRow = mysql_fetch_object($mRes);
                            $mCustomerKey = $mRow->CustomerKey;
                            if (trim($mCustomerKey)=="")
                            {
                                $mURL = $SureGateURL."paygate/ws/recurring.asmx/ManageCustomer"; //$SureGateURL is defined in includes/config.php
                                $mTransType = "ADD";
                                $mVendorID = $objRestaurant->VendorID;
                                $mCustomerID = $loggedinuser->id;
                                $mCustomerKey = "";
                                $mFirstName = $loggedinuser->cust_your_name;
                                $mLastName = $loggedinuser->LastName;
                                $mCustomerName = $mFirstName." ".$mLastName;
                                $mTitle = $loggedinuser->LastName;
                                $mDepartment = "";
                                $mStreet1 = "";
                                $mStreet2 = "";
                                $mStreet3 = "";
                                $mCity = "";
                                $mStateID = "";
                                $mProvince = "";
                                $mZip = "";
                                $mCountryID = "";
                                $mDayPhone = "";
                                $mNightPhone = "";
                                $mFax = "";
                                $mEmail = "";
                                $mMobile = "";
                                $mStatus = "";
                                $mExtData = "";

                                $mPostString = "UserName=".$mUserName."&Password=".$mPassword."&TransType=".$mTransType."&Vendor=".$mVendorID."&CustomerID=".$mCustomerID."&CustomerName=".$mCustomerName."&CustomerKey=".$mCustomerKey."&FirstName=".$mFirstName."&LastName=".$mLastName."&Title=".$mTitle."&Department=".$mDepartment."&Street1=".$mStreet1."&Street2=".$mStreet2."&Street3=".$mStreet3."&City=".$mCity."&StateID=".$mStateID."&Province=".$mProvince."&Zip=".$mZip."&CountryID=".$mCountryID."&DayPhone=".$mDayPhone."&NightPhone=".$mNightPhone."&Fax=".$mFax."&Email=".$mEmail."&Mobile=".$mMobile."&Status=".$mStatus."&ExtData=".$mExtData;
                                Log::write('SureGate Post Variables - Save Customer', $mPostString, 'suregate');

                                $ch = curl_init(); 
                                curl_setopt ($ch, CURLOPT_URL, $mURL);
                                curl_setopt ($ch, CURLOPT_POST, 1);
                                curl_setopt ($ch, CURLOPT_POSTFIELDS, $mPostString);
                                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt ($ch, CURLOPT_TIMEOUT, 120);			
                                curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);

                                $result = curl_exec($ch);

                                unset($ch);
                                if ($result) 
                                {
                                    $mTransaction = simplexml_load_string($result); 
                                    /*$tmp= new SimpleXMLElement($result);
                                    echo($tmp->asXML());*/
                                    if (isset($mTransaction->CustomerKey))
                                    {
                                        $mCustomerKey = $mTransaction->CustomerKey;
                                        $mSQL = "UPDATE customer_registration SET CustomerKey='".$mCustomerKey."' WHERE id=".$loggedinuser->id;
                                        mysql_query($mSQL);
                                    }
                                }
                            }

                            if (trim($mCustomerKey)!="")
                            {	
                                $mURL = $SureGateURL."ws/cardsafe.asmx/StoreCard"; //$SureGateURL is defined in includes/config.php
                                $mTokenMode = "DEFAULT";
                                $mCardNum = $x_card_num;
                                $mExpDate = $x_exp_date;
                                $mNameOnCard = "";
                                $mStreet = "";
                                $mZip = "";
                                $mExtData = "";

                                $mPostString = "UserName=".$mUserName."&Password=".$mPassword."&CustomerKey=".$mCustomerKey."&CardNum=".$mCardNum."&ExpDate=".$mExpDate."&TokenMode=".$mTokenMode."&NameOnCard=".$mNameOnCard."&Street=".$mStreet."&Zip=".$mZip."&ExtData=".$mExtData;
                                Log::write('SureGate Post Variables - Save Card', $mPostString, 'suregate');

                                $ch = curl_init(); 
                                curl_setopt ($ch, CURLOPT_URL, $mURL);
                                curl_setopt ($ch, CURLOPT_POST, 1);
                                curl_setopt ($ch, CURLOPT_POSTFIELDS, $mPostString);
                                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt ($ch, CURLOPT_TIMEOUT, 120);			
                                curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);

                                $result = curl_exec($ch);

                                unset($ch);

                                if ($result) 
                                {
                                    $mTransaction = simplexml_load_string($result); 
                                    if ($mTransaction)
                                    {
                                        if ($mTransaction->Result=="0")
                                        {
                                            $mExtDataOp = new SimpleXMLElement($mTransaction->ExtData);
                                            $gateway_token = $mExtDataOp;
                                            if ($CarkTokenOrderTbl=="")
                                            {
                                                $CarkTokenOrderTbl = $mExtDataOp;
                                            }
                                        }
                                    }
                                }	
                            }
                        }
                    }
                }

                $success=1;
                Log::write('SureGate Response '."\n".'Result: Success', print_r($result,true), 'suregate');				
            }
            else
            {
                if (trim($mTransaction->Message)!="")
                {
                    $_SESSION['GATEWAY_RESPONSE'] = $mTransaction->Message;   
                }
                else
                {
                    $_SESSION['GATEWAY_RESPONSE'] = $mTransaction->RespMSG;
                }
                
                Log::write('SureGate Response ', print_r($result,true), 'suregate');
                if(isset($wp_api)&& $wp_api==true) 
                {
                    redirect($SiteUrl.$objRestaurant->url ."/?item=failed&wp_api=failed&tpe=suregate&response_code=".$mTransaction->Result);
                    exit;
                }
                else if(isset($ifrm)&& $ifrm==true) 
                {
                    redirect($SiteUrl.$objRestaurant->url ."/?item=failed&ifrm=failed&tpe=suregate&response_code=".$mTransaction->Result);
                    exit;
                }
                else 
                {
                    redirect($SiteUrl.$objRestaurant->url ."/?item=failed&tpe=suregate&response_code=".$mTransaction->Result);
                    exit;
                }
            }
        }
        else
        {
            $_SESSION['GATEWAY_RESPONSE'] = $result;
            Log::write('SureGate Response ', print_r($result,true), 'suregate');
            if(isset($wp_api)&& $wp_api==true) 
            {
                redirect($SiteUrl . $objRestaurant->url ."/?item=failed&wp_api=failed&tpe=suregate&response_code=suregate1");
                exit;
            }
            else if(isset($ifrm)&& $ifrm==true) 
            {
                redirect($SiteUrl . $objRestaurant->url ."/?item=failed&ifrm=failed&tpe=suregate&response_code=suregate1");
                exit;
            }
            else 
            {
                redirect($SiteUrl . $objRestaurant->url ."/?item=failed&tpe=suregate&response_code=suregate1");
                exit;
            }
        }
    } 
    else 
    {
        $_SESSION['GATEWAY_RESPONSE'] = "SureGate: Connection failure.";
        Log::write('SureGate Response '."\n".'Result: Failure', 'SureGate: Connection failure.', 'suregate');
        if(isset($wp_api)&& $wp_api==true) 
        {
            redirect($SiteUrl . $objRestaurant->url ."/?item=failed&wp_api=failed&tpe=suregate&response_code=suregate2");
            exit;
        }
        else if(isset($ifrm)&& $ifrm==true) 
        {
            redirect($SiteUrl . $objRestaurant->url ."/?item=failed&ifrm=failed&tpe=suregate&response_code=suregate2");
            exit;
        }
        else 
        {
            redirect($SiteUrl . $objRestaurant->url ."/?item=failed&tpe=suregate&response_code=suregate2");
            exit;
        }
    }
}
else //For Rapid Re-Ordering (Order was requested/paid by SMS)
{
    if ($result) 
    {
        $mTransaction = simplexml_load_string($result); 
        /*$tmp= new SimpleXMLElement($result);
        echo($tmp->asXML());*/
        if ($mTransaction->Result == "0") 
        {
            $_POST['payment_method']=1;
            $_POST['invoice_number']=date('YmdHis');
            $_POST['transaction_id']=$mTransaction->PNRef; 
            $cdynePNRef = (string) $mTransaction->PNRef;
            Log::write('SureGate Response - Rapid Re-ordering '."\n".'Result: Success', print_r($result,true), 'suregate');
            $success=1;
        }
        else
        {
            Log::write('SureGate Response - Rapid Re-ordering '."\n".'Result: Failed', print_r($result,true), 'suregate');
            $success=0;
        }
    }
}
?>