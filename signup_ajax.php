<?php 
require_once("includes/config.php");
include_once("includes/class.phpmailer.php");
include_once("c_panel/classes/chargifyApi.php");
require("c_panel/includes/SimpleImage.php");


if ($_GET["call"]=="saveuser")
{   
    extract ($_POST);
    $txtTax = str_replace('%',"",$txtTax);
    mysql_query("INSERT INTO tblDebug(Step, Value1, Value2) VALUES (1, 'ABCD', '1')");
    $mSQL = "SELECT COUNT(*) AS UserCount FROM `users` WHERE LOWER(TRIM(username))='".trim(strtolower($_GET["username"]))."'";
    $mResult = mysql_query($mSQL);
    $mRow = mysql_fetch_object($mResult);
    if ($mRow->UserCount>0)
    {
            echo("duplicate");
    }
    else
    {

        if ((is_numeric($txtRestaurantID)) && ($txtRestaurantID>0))
        {
            $mSQL = "UPDATE signupdetails SET `TimeZoneID`=".$ddlTimeZone.", `RestaurantName`='".mysql_real_escape_string($txtRestaurantName)."', `Address`='".mysql_real_escape_string($txtStreetAddress)."', `State`='".mysql_real_escape_string($txtState)."', `City`='".mysql_real_escape_string($txtCity)."', `ZipCode`='".mysql_real_escape_string($txtZip)."', `Country`='".$ddlCountry1."', `PhoneNumber`='".mysql_real_escape_string($txtPhone)."', `FaxNumber`='".mysql_real_escape_string($txtFax)."', `FullName`='".mysql_real_escape_string($txtFullName)."', `EmailAddress`='".mysql_real_escape_string($txtEmailAddress)."', `Password`='".mysql_real_escape_string($txtPassword)."', `UserName`='".mysql_real_escape_string($txtUserName)."', `OrderReceive`=".$rbOrders.", `Delivery`='".$rbDelivery."', `Tax`='".mysql_real_escape_string($txtTax)."', `Cash`='".$rbCash."', `CreditCard`='".$rbCreditCard."', `GateWay`='".$rbGateWay."', `DomainName`='".mysql_real_escape_string($txtDomainName)."', `NewDomain`='".mysql_real_escape_string($txtNewDomainName)."', `MenuUse`='".$rbMenuUse."', `HostingInformation`='".$rbHosting."', `DeliveryMinimum`='".$txtDeliveryMinimum."', `DeliveryCharges`='".$txtDeliveryCharges."', `DeliveryRadius`='".$txtDeliveryRadius."', `ClientAddress`='".mysql_real_escape_string($txtClientAddress)."', `ClientState`='".mysql_real_escape_string($txtState)."', `ClientCity`='".mysql_real_escape_string($txtCity)."', `ClientZipCode`='".mysql_real_escape_string($txtZip)."', `ClientCountry`='".$ddlCountry1."',`wesite_name`='".mysql_real_escape_string($webName)."', `website_host`='".mysql_real_escape_string($webHost)."', `website_username`='".mysql_real_escape_string($webUserName)."', `website_password`='".mysql_real_escape_string($webPassword)."', `webmaster_name`='".$masterName."',`webmaster_email`='".mysql_real_escape_string($masterEmail)."', `webmaster_phone`='".mysql_real_escape_string($masterPhone)."', `hosting_name`='".mysql_real_escape_string($hostingCompany)."', `hosting_ftp`='".mysql_real_escape_string($accountName)."',`hosting_username`='".mysql_real_escape_string($accountUsername)."', `ordering_plan`='".$orderingPlan."' WHERE ID=".$txtRestaurantID;
            mysql_query($mSQL);
            $mSDID = $txtRestaurantID;
            
        }
        else
        {
            $newDomain = '';
            foreach ($txtNewDomainName as $eachInput) {
                 $newDomain .= $eachInput . ",";
            }
            $newDomain = substr($newDomain,0,-1);
            $txtNewDomainName = $newDomain;
            $mSQL = "INSERT INTO  signupdetails (`TimeZoneID`, `RestaurantName`, `Address`, `State`, `City`, `ZipCode`, `Country`, `PhoneNumber`, `FaxNumber`, `FullName`, `EmailAddress`, `Password`, `UserName`, `OrderReceive`, `Delivery`, `Tax`, `Cash`, `CreditCard`, `GateWay`, `DomainName`, `NewDomain`, `MenuUse`, `HostingInformation`, `DeliveryMinimum`, `DeliveryCharges`, `DeliveryRadius`, `ClientAddress`, `ClientState`, `ClientCity`, `ClientZipCode`, `ClientCountry`,wesite_name,website_host,website_username,website_password,webmaster_name,webmaster_email,webmaster_phone,hosting_name,hosting_ftp,hosting_username,ordering_plan) VALUES ";
            $mSQL .= "(".$ddlTimeZone.", '".mysql_real_escape_string($txtRestaurantName)."', '".mysql_real_escape_string($txtStreetAddress)."', '".mysql_real_escape_string($txtState)."', '".mysql_real_escape_string($txtCity)."', '".mysql_real_escape_string($txtZip)."', '".$ddlCountry1."', '".mysql_real_escape_string($txtPhone)."', '".mysql_real_escape_string($txtFax)."', '".mysql_real_escape_string($txtFullName)."', '".mysql_real_escape_string($txtEmailAddress)."', '".mysql_real_escape_string($txtPassword)."', '".mysql_real_escape_string($txtUserName)."', '".$rbOrders."', '".$rbDelivery."', '".mysql_real_escape_string($txtTax)."', '".$rbCash."', '".$rbCreditCard."', '".$rbGateWay."', '".mysql_real_escape_string($txtDomainName)."', '".mysql_real_escape_string($txtNewDomainName)."', '".$rbMenuUse."', '".$rbHosting."', '".$txtDeliveryMinimum."', '".$txtDeliveryCharges."', '".$txtDeliveryRadius."', '".mysql_real_escape_string($txtClientAddress)."', '".mysql_real_escape_string($txtState)."', '".mysql_real_escape_string($txtCity)."', '".mysql_real_escape_string($txtZip)."', '".mysql_real_escape_string($ddlCountry)."','".mysql_real_escape_string($webName)."','".mysql_real_escape_string($webHost)."','".mysql_real_escape_string($webUserName)."','".mysql_real_escape_string($webPassword)."','".mysql_real_escape_string($masterName)."', '".mysql_real_escape_string($masterEmail)."','".mysql_real_escape_string($masterPhone)."','".mysql_real_escape_string($hostingCompany)."','".mysql_real_escape_string($accountName)."','".mysql_real_escape_string($accountUsername)."','".$orderingPlan."')";
            Log::write('Insert into signup Details - Create restaurant', $mSQL, 'SelfSignup', 1);
            mysql_query($mSQL);
            $mSDID = mysql_insert_id();

        }
        if ((is_numeric($mSDID)) && ($mSDID>0))
        {
                $mEchoFlag = 0;

                $mParentID = '481';
                $mProductID = $rbProducts;
                $mSQL = "SELECT IFNULL(`premium_account`, 0) AS `premium_account` FROM `chargify_products` WHERE product_id=".$mProductID and "user_id =".$mParentID;
                $mResPre = mysql_query($mSQL);
                $mRowPre = mysql_fetch_object($mResPre);
                $mPremiumAccount = $mRowPre->premium_account;
                

                $mSQL = "SELECT id, license_key FROM licenses WHERE reseller_id=".$mParentID." AND status='unused' LIMIT 1";
                $mResLi = mysql_query($mSQL);
                if (mysql_num_rows($mResLi)>0)
                {
                        $mRowLi = mysql_fetch_object($mResLi);
                        $mLicenseID = $mRowLi->id;
                        $mLicense = $mRowLi->license_key;
                        $mMessage = "";
                        $mFirstName = "";
                        $mLastName = "";
                        if (strpos($txtFullName, " ")!==false)
                        {
                                $mTmpName = explode(" ", $txtFullName);
                                $mFirstName = $mTmpName[0];
                                $mLastName = $mTmpName[1];
                        }
                        else
                        {
                                $mFirstName = $txtFullName;
                                $mLastName = $txtFullName;
                        }

                        $mObjCAPI = new chargifyApi;

                        $mCustomerID = $mObjCAPI->createCustomer($mFirstName, $mLastName, $txtEmailAddress, $txtRestaurantName, $txtCity, $txtState, $txtZip, $ddlCountry1);
                        
                        if (isset($mCustomerID))
                        {
                                if (is_numeric($mCustomerID))
                                {
                                        if ($mCustomerID>0)
                                        {
                                                $mResultSubRes = $mObjCAPI->createSubcription($mProductID, $mCustomerID, "automatic", "", $txtCreditCardNumber, $ddlExpMonth, $ddlExpYear);
                                                
                                                if (!isset($mResultSubRes->errors))
                                                {
                                                        if (isset($mResultSubRes->subscription->credit_card))
                                                        {
                                                                $mCC = $mResultSubRes->subscription->credit_card;
                                                                $mCC = (object) $mCC;
                                                                $mPaymant_Profile_ID = $mCC->id;
                                                                $mMaskedCC = $mCC->masked_card_number;
                                                        }

                                                        $mResultSubRes->subscription = (object) $mResultSubRes->subscription;
                                                        $mRestSubscriptionID = $mResultSubRes->subscription->id;
                                                        if($rbOrders == 4)
                                                        {
                                                            $mResultChargeExtra = $mObjCAPI->chargeExtraAmpunt($mRestSubscriptionID);
                                                        }
                                                        $mSRID ="";
                                                        if ((trim($mPremiumAccount)==0) || (trim($mPremiumAccount)=="0") || (trim($mPremiumAccount)==""))
                                                        {
                                                                $mSRID = $mObjCAPI->createVendestaPremium($txtRestaurantName, str_replace(",", "", $txtStreetAddress), $txtCity, $txtState, $txtZip, 'true');
                                                        }
                                                        else
                                                        {
                                                                $mSRID = $mObjCAPI->createVendestaPremium($txtRestaurantName, $txtStreetAddress, $txtCity, $txtState, $txtZip, 'false');
                                                        }
                                                        
                                                        $mSQL = "UPDATE `signupdetails` SET CustomerID='".$mCustomerID."', SubscriptionID='".$mRestSubscriptionID."', SRID='".$mSRID."' WHERE ID=".$mSDID;
                                                        Log::write('Insert into signup Details - signup_ajax', "QUERY --".$mSQL, 'SelfSignup', 1);
                                                        mysql_query($mSQL);

                                                        $mSQL = "INSERT INTO `users` (firstname, lastname, email, username, password, country, state, city, zip, status, type, phone, company_name, parent_id, chargify_subcription_id, chargify_customer_id) VALUES ";
                                                        $mSQL .= " ('".$mFirstName."', '".$mLastName."', '".$txtEmailAddress."', '".$txtUserName."', '".$txtPassword."', '".$ddlCountry1."', '".$txtState."', '".$txtCity."', '".$txtZip."', '1', 'store owner', '".$txtPhone."', '".mysql_real_escape_string($txtRestaurantName)."', '".$mParentID."', '".$mCustomerID."', '".$mSubscriptionID."')";
                                                        Log::write('Insert into Users - signup_ajax', "QUERY --".$mSQL, 'SelfSignup', 1);
                                                        mysql_query($mSQL);
                                                        $mUserID = mysql_insert_id();

                                                        $mSQL = "INSERT INTO chargify_payment_method (user_id, chargify_customer_id, Payment_profile_id, card_number, billing_address, billing_address_2, billing_city, billing_country, billing_state, billing_zip, first_name, last_name, expiration_month, expiration_year) VALUES ";
                                                        $mSQL .= "(".$mUserID.", ".$mCustomerID.", ".$mPaymant_Profile_ID.", '".$mMaskedCC."', '".$txtClientAddress."', '', '".$txtClientCity."', '".$ddlCountry2."', '".$txtClientState."', '".$txtClientZip."', '".$mFirstName."', '".$mLastName."', '".$ddlExpMonth."', '".$ddlExpYear."')";
                                                        Log::write('Insert into chargify_payment_method - signup_ajax', "QUERY --".$mSQL, 'SelfSignup', 1);
                                                        mysql_query($mSQL);

                                                        $mSQLReseller = "INSERT INTO reseller_client (reseller_id,client_id) VALUES ('".$mParentID."','".$mUserID."')";
                                                        mysql_query($mSQLReseller);
                                                        Log::write('Insert into reseller_clien - signup_ajax', "QUERY --".$mSQLReseller, 'SelfSignup', 1);

                                                        $mOrderDest = "email";
                                                        if ($rbOrders==1)
                                                        {
                                                                $mOrderDest = "fax";
                                                        }
                                                        else if ($rbOrders==2)
                                                        {
                                                                $mOrderDest = "email";
                                                        }
                                                        else if ($rbOrders==3)
                                                        {
                                                                $mOrderDest = "pos";
                                                        }
                                                        else if ($rbOrders==4)
                                                        {
                                                                $mOrderDest = "Manager Tablet";
                                                        }

                                                        $mPaymentMethod = "cash";
                                                        if (($rbCash==1) && ($rbCreditCard==1))
                                                        {
                                                                $mPaymentMethod = "both";
                                                        }
                                                        else if (($rbCash==1) && ($rbCreditCard==2))
                                                        {
                                                                $mPaymentMethod = "cash";
                                                        }
                                                        else if (($rbCash==2) && ($rbCreditCard==1))
                                                        {
                                                                $mPaymentMethod = "credit";
                                                        }

                                                        $mRegion  = 1;

                                                        if (strtolower(trim($ddlCountry1))=="us")
                                                        {
                                                                $mRegion  = 1;
                                                        }
                                                        else if (strtolower(trim($ddlCountry1))=="uk")
                                                        {
                                                                $mRegion  = 0;
                                                        }
                                                        else if (strtolower(trim($ddlCountry1))=="canada")
                                                        {
                                                                $mRegion  = 2;
                                                        }


                                                        $mSQL = "SELECT * FROM `signupdetails` WHERE ID=".$mSDID;
                                                        $mResSD = mysql_query($mSQL);
                                                        $mRowSD = mysql_fetch_object($mResSD);

                                                        if ($mRowSD->delivery_option=="delivery_zones")
                                                        {
                                                                $mDelOpt = "delivery_zones";
                                                        }
                                                        else
                                                        {
                                                                $mDelOpt = "radius";
                                                        }

                                                        $mRest_url_name = url_title($mRowSD->RestaurantName);
														/*$mRest_url_name = strtolower(trim(preg_replace('/-+/', '_', preg_replace('/[^a-zA-Z0-9]+/', '_', $mRowSD->RestaurantName)), '_'));*/

                                                        $mSQL = "INSERT INTO `resturants` (name, delivery_charges,tax_percent, owner_id, license_id, status, email, fax, order_destination, phone, payment_method, region, time_zone_id, rest_address, rest_city, rest_state, rest_zip, delivery_radius, zone1, zone1_delivery_charges, zone1_min_total, zone1_coordinates, zone2, zone2_delivery_charges, zone2_min_total, zone2_coordinates, zone3, zone3_delivery_charges, zone3_min_total, zone3_coordinates, chargify_product_id, chargify_subscription_id, domain_name_details, website_integration_type, hosting_information, premium_account, srid, delivery_option, url_name) ";
                                                        $mSQL .= " VALUES ('".prepareStringForMySQL($mRowSD->RestaurantName)."', '".$mRowSD->DeliveryCharges."','".$mRowSD->Tax."', ".$mUserID.", ".$mLicenseID.", 1, '".$mRowSD->EmailAddress."', '".$mRowSD->FaxNumber."', '".$mOrderDest."', '".$mRowSD->PhoneNumber."', '".$mPaymentMethod."', ".$mRegion.", ".$mRowSD->TimeZoneID.", '".$mRowSD->Address."', '".$mRowSD->City."', '".$mRowSD->State."', '".$mRowSD->ZipCode."', '".$mRowSD->DeliveryRadius."', '".$mRowSD->zone1."', '".$mRowSD->zone1_delivery_charges."', '".$mRowSD->zone1_min_total."', '".$mRowSD->zone1_coordinates."', '".$mRowSD->zone2."', '".$mRowSD->zone2_delivery_charges."', '".$mRowSD->zone2_min_total."', '".$mRowSD->zone2_coordinates."', '".$mRowSD->zone3."', '".$mRowSD->zone3_delivery_charges."', '".$mRowSD->zone3_min_total."', '".$mRowSD->zone3_coordinates."', ".$mProductID.", '".$mRestSubscriptionID."', '".$mRowSD->DomainName."', '".$mRowSD->MenuUse."', '".$mRowSD->HostingInformation."', ".$mPremiumAccount.",  '".$mSRID."', '".$mDelOpt."', '".$mRest_url_name."')";
                                                        Log::write('Insert into resturants - signup_ajax', "QUERY --".$mSQL, 'SelfSignup', 1);
                                                        mysql_query($mSQL);
                                                        $mRestaurantID = mysql_insert_id();
                                                        if (isset($optionallogo))
                                                        {
                                                                if (trim($optionallogo)!="")
                                                                {
                                                                        if (strpos(trim(strtolower($optionallogo)), "NIA.jpg")===false)
                                                                        {
                                                                                $mImageURL = $optionallogo;
                                                                                $mImageName = "img_".$mRestaurantID."_cat_thumbnail.jpg";
                                                                                $mPath = 'images/logos_thumbnail/'.$mImageName;
                                                                                @file_put_contents($mPath, file_get_contents($mImageURL));
                                                                    @list($width, $height, $type, $attr) = getimagesize($mPath);

                                                                                if ($height > $width) {
                                                                                        @$image = new SimpleImage();
                                                                                        @$image->load($mImageURL);
                                                                                        @$image->resizeToHeight(500);
                                                                                        @$image->save($mPath);
                                                                                } else {
                                                                                        @$image = new SimpleImage();
                                                                                        @$image->load($mImageURL);
                                                                                        @$image->resizeToWidth(600);
                                                                                        @$image->save($mPath);
                                                                                }

                                                                                mysql_query("UPDATE resturants SET optionl_logo='".$mImageName."' WHERE id=".$mRestaurantID);
                                                                        }
                                                                }
                                                        }
                                                        $mImageStr = "";
                                                        if ((trim($mImageName)!="") && (trim($mImageName)!="NIA.jpg"))
                                                        {
                                                                $mImageStr = " optionl_logo='".$mImageName."', ";
                                                        }
                                                        $queryInsertRestaurantAnalytics = "INSERT INTO `analytics` SET
                                                        resturant_id = ".$mRestaurantID.",
                                                        first_letter = '".strtoupper(substr($mRowSD->RestaurantName,0,1))."',
                                                        name='".prepareStringForMySQL($mRowSD->RestaurantName)."',
                                                        url_name='".prepareStringForMySQL($mRest_url_name)."',
                                                        status='1',
                                                        orders_last_month_count='0', ".$mImageStr."
                                                        orders_last_but_second_month_count='0'";
                                                        Log::write('Insert into analytics - signup_ajax', "QUERY --".$queryInsertRestaurantAnalytics, 'SelfSignup', 1);
                                                        mysql_query($queryInsertRestaurantAnalytics);
                                                        for($j = 0; $j< 7; $j++) {
                                                            //hour and minutes are treaded as string
                                                            $open_time =  '0800';
                                                            $close_time = '1700';
                                                            mysql_query(
                                                                    "INSERT INTO business_hours
                                                                    SET rest_id = '".$mRestaurantID."'
                                                                            ,day= '".$j."'
                                                                            ,open='$open_time'
                                                                            ,close='$close_time'"
                                                            );
                                                        }
                                                        mysql_query("UPDATE `signupdetails` SET RestaurantID=".$mRestaurantID." WHERE ID=".$mSDID);

                                                        mysql_query("UPDATE licenses SET  status='activated', resturant_id=".$mRestaurantID." WHERE license_key ='".$mLicense."' and reseller_id=".$mParentID);

                                                        /* ------------------------------ Email Starts ----------------------------- */
                                                        $mDelStr = "";
                                                        $mDelOptions = "";

                                                        if (trim($txtDeliveryRadius)!="")
                                                        {
                                                                if (is_numeric($txtDeliveryRadius))
                                                                {
                                                                        $mDelOptions = "Delivery Radius";
                                                                }
                                                                else
                                                                {
                                                                        $mDelOptions = "Custom Delivery Zone";
                                                                }
                                                        }
                                                        else
                                                        {
                                                                $mDelOptions = "Custom Delivery Zone";
                                                        }

                                                        if ($rbDelivery==1)
                                                        {
                                                                $mDelStr = '<tr style="height: 10px;">
                                                                                <td valign="top" colspan="2">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td valign="top" style="width: 45%;">
                                                                                        <span style="font-weight: bold;">Do you offer delivery: </span>
                                                                                </td>
                                                                                <td valign="top" >
                                                                                        Yes
                                                                                </td>
                                                                            </tr>
                                                                            <tr style="height: 10px;">
                                                                                <td valign="top" colspan="2">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td valign="top" style="width: 45%;">
                                                                                        <span style="font-weight: bold;">Delivery Option: </span>
                                                                                </td>
                                                                                <td valign="top" >
                                                                                        '.$mDelOptions.'
                                                                                </td>
                                                                            </tr>
                                                                            <tr style="height: 10px;">
                                                                                <td valign="top" colspan="2">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td valign="top" style="width: 45%;">
                                                                                        <span style="font-weight: bold;">Delivery Minimum (in dollars): </span>
                                                                                </td>
                                                                                <td valign="top" >
                                                                                        '.$txtDeliveryMinimum.'
                                                                                </td>
                                                                            </tr>
                                                                            <tr style="height: 10px;">
                                                                                <td valign="top" colspan="2">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td valign="top" style="width: 45%;">
                                                                                        <span style="font-weight: bold;">Delivery charges: </span>
                                                                                </td>
                                                                                <td valign="top" >
                                                                                        '.$txtDeliveryCharges.'
                                                                                </td>
                                                                            </tr>';
                                                        }
                                                        else
                                                        {
                                                                $mDelStr = '<tr style="height: 10px;">
                                                                                <td valign="top" colspan="2">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td valign="top" style="width: 45%;">
                                                                                        <span style="font-weight: bold;">Do you offer delivery: </span>
                                                                                </td>
                                                                                <td valign="top" >
                                                                                        No
                                                                                </td>
                                                                            </tr>
                                                                            <tr style="height: 10px;">
                                                                                <td valign="top" colspan="2">
                                                                                </td>
                                                                            </tr>';
                                                        }

                                                        $mMenuStr = "";
                                                        $mSQL = "SELECT MenuName FROM signupuploads WHERE RestaurantID=".$mSDID;
                                                        $mResMen = mysql_query($mSQL);
                                                        if (mysql_num_rows($mResMen)>0)
                                                        {
                                                                while ($mRowMen = mysql_fetch_object($mResMen))
                                                                {
                                                                        $mMenuStr .= '<tr>
                                                                                        <td valign="top" style="width: 45%;">
                                                                                                <span style="font-weight: bold;">Menu Name: </span>
                                                                                        </td>
                                                                                        <td valign="top" >
                                                                                                '.str_replace("'", "", $mRowMen->MenuName).'
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr style="height: 10px;">
                                                                                            <td valign="top" colspan="2">
                                                                                            </td>
                                                                                    </tr>';
                                                                }
                                                        }

                                                        $mIntegrationChoice = "Use EasyWay Ordering menu as my website";
                                                        if ($rbMenuUse==1)
                                                        {
                                                                $mIntegrationChoice = "Add EasyWay Ordering menu to existing website";
                                                                $ftpDetails = '<tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Name: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $webName).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">FTP/Host: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $webHost).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Username: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $webUserName).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Password: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $webPassword).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>';
                                                        }
                                                        else if ($rbMenuUse==2)
                                                        {
                                                                $mIntegrationChoice = "Use my EasyWay Ordering with my domain name";
                                                                $mHostingStr = "I will make all necessary changes";
                                                                if ($rbHosting==1)
                                                                {
                                                                    $mHostingStr = "I will make all necessary changes";
                                                                        
                                                                }
                                                                else if ($rbHosting==2)
                                                                {
                                                                        $mHostingStr = "My webmaster will make all necessary changes";
                                                                        $ftpDetails = '<tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Name: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $masterName).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Email: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $masterEmail).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Phone#: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $masterPhone).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>';
                                                                }
                                                                else if ($rbHosting==3)
                                                                {
                                                                        $mHostingStr = "Neither";
                                                                        $ftpDetails = '<tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Name: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $hostingCompany).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">FTP/Host: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $accountName).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Username: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $accountUsername).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>';
                                                                }

                                                                         $ftpDetails =   '<tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Hosting Information Choice: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.$mHostingStr.'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>'.$ftpDetails;

                                                        }
                                                        else if ($rbMenuUse==3)
                                                        {
                                                                $mIntegrationChoice = "Use my EasyWay Ordering with my domain name";
                                                                if($orderingPlan==1)
                                                                {
                                                                    $plan= "Stand Alone(single page online ordering)";
                                                                }
                                                                else
                                                                {
                                                                        $plan= "Full Website(with online ordering included)";
                                                                }

                                                                $ftpDetails = '<tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td valign="top" style="width: 45%;">
                                                                                                <span style="font-weight: bold;">Ordering Plan: </span>
                                                                                        </td>
                                                                                        <td valign="top" >
                                                                                                '.str_replace("'", "", $plan).'
                                                                                        </td>
                                                                                </tr>
                                                                                <tr style="height: 10px;">
                                                                                        <td valign="top" colspan="2">
                                                                                        </td>
                                                                                </tr>';
                                                        }

                                                        


                                                        $mMessage = '<table style="font:Arial; font-family: Arial; font-size: 14px; width: 80%; border: 0" border="0" cellpadding="0" cellspacing="0">
                                                                        <tr>
                                                                                <td valign="top" style="width: 3%;">
                                                                                </td>
                                                                                <td valign="top" style="width: 94%;">
                                                                                <table style="font:Arial; font-family: Arial; font-size: 14px; width: 100%; border: 0" border="0" cellpadding="0" cellspacing="0">
                                                                                        <tr style="height: 15px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" colspan="2">
                                                                                                        <span style="font-size: 18px; font-weight: bold;">Account Information</span>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 15px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">First Name: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $mFirstName).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Last Name: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $mLastName).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">User Name: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtUserName).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Email Address: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtEmailAddress).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Phone: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtPhone).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Fax: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtFax).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">State: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtState).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">City: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtCity).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Zip: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtZip).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 15px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" colspan="2">
                                                                                                        <span style="font-size: 18px; font-weight: bold;">Restaurant Information</span>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 15px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Restaurant Name: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtRestaurantName).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Email Address: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtEmailAddress).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Phone: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtPhone).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Fax: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtRestaurantName).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">State: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtState).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">City: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtCity).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Zip: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtZip).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 15px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" colspan="2">
                                                                                                        <span style="font-size: 18px; font-weight: bold;">Order Settings</span>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 15px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Will you receive orders via Fax, Email, POS or Manager Tab?: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $mOrderDest).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        '.$mDelStr.'
                                                                                        <tr style="height: 15px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" colspan="2">
                                                                                                        <span style="font-size: 18px; font-weight: bold;">Menus</span>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 15px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        '.$mMenuStr.'
                                                                                        <tr style="height: 15px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" colspan="2">
                                                                                                        <span style="font-size: 18px; font-weight: bold;">Setup Instructions</span>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 15px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Existing domain name: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.str_replace("'", "", $txtDomainName).'
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td valign="top" style="width: 45%;">
                                                                                                        <span style="font-weight: bold;">Website Integration Choice: </span>
                                                                                                </td>
                                                                                                <td valign="top" >
                                                                                                        '.$mIntegrationChoice.'
                                                                                                </td>
                                                                                        </tr>'.$ftpDetails.'
                                                                                        
                                                                                        <tr style="height: 10px;">
                                                                                                <td valign="top" colspan="2">
                                                                                                </td>
                                                                                        </tr>
                                                                                </table>
                                                                                </td>
                                                                                <td valign="top" style="width: 3%;">
                                                                                </td>
                                                                        </tr>
                                                                </table>';

                                                                                $mSubject = "Menu Settings Uploaded for ".$txtRestaurantName;

                                                                                $objMail = new testmail();
                                                                                $objMail->clearattachments();

                                                                                $mSQL = "SELECT FileName FROM signupuploads WHERE RestaurantID=".$mSDID;
                                                                                $mResMen = mysql_query($mSQL);
                                                                                if (mysql_num_rows($mResMen)>0)
                                                                                {
                                                                                        while ($mRowMen = mysql_fetch_object($mResMen))
                                                                                        {
                                                                                                $objMail->addattachment("signupUploads/".$mRowMen->FileName);
                                                                                        }
                                                                                }
                                                                                $objMail->sendTo($mMessage, $mSubject, "menus@easywayordering.com", true);

                                                                                $mMessage = "<div>Hello ". strtoupper($txtFullName)."!</div>
                                                                                <div>Thank you for signing up for EasyWay Ordering. </div>
                                                                                <div>By signing up, you are taking the first step towards having your own,
                                                                                powerful online ordering.</div>
                                                                                <div>Now, our team will get to work creating your complementary online menu.</div><br />
                                                                                <div>If we need any additional information, we will reach out to you at the
                                                                                phone number or email you provided.</div>
                                                                                <br />
                                                                                <div'>Have questions? Contact us at:
                                                                                customerservice@easywayordering.com</div>
                                                                                <br /><br />
                                                                                <div'>We'll take it from here! </div>
                                                                                <div>Sincerely,  </div>
                                                                                <div>The EasyWay Ordering Team </div>
                                                                                <br /><br /><img src='http://new.easywayordering.com/signup_images/EW_newlogo.png'/>";
                                                                                $mSubject = "Thank you for registering with EasyWay Ordering ";
                                                                                $objMail->sendTo($mMessage, $mSubject, $txtEmailAddress, true);
                                                                /* ------------------------------ Email Ends ----------------------------- */

                                                }
                                                else
                                                {
                                                        $mEchoFlag = 1;
							$error = $mResultSubRes['errors'];
                                                        echo($mSDID."ERROR: ".$error[0]);
                                                }
                                        }
                                        else
                                        {
                                                $mEchoFlag = 1;
                                                echo($mSDID."ERROR: Customer in Chargify was not created.");
                                        }
                            }
                            else
                            {
                                    $mEchoFlag = 1;
                                    echo($mSDID."ERROR: Customer in Chargify was not created.");
                            }
                        }
                        else
                        {
                                $mEchoFlag = 1;
                                echo($mSDID."ERROR: Customer in Chargify was not created.");
                        }
                    }
                    else
                    {
                            $mEchoFlag = 1;
                            echo($mSDID."ERROR: No un-used license for this reseller.");
                    }
             
            if ($mEchoFlag!=1)
            {
                    echo($mSDID);
            }
        }
        else
        {
                echo("Error occurred while inserting.");
        }
    }
}
else if ($_GET["call"]=="delivery_zone")
{
        $mTmpID = "";
        extract ($_POST);
        if (is_numeric($id))
        {
                if ($id>0)
                {
                        $mSQL = "UPDATE `signupdetails` SET zone1='$zone1',zone1_delivery_charges='$zone1_delivery_charges',zone1_min_total='$zone1_min_total',zone1_coordinates='$zone1_coordinates'";
                        $mSQL .="  ,zone2='$zone2',zone2_delivery_charges='$zone2_delivery_charges',zone2_min_total='$zone2_min_total',zone2_coordinates='$zone2_coordinates'";
                        $mSQL .="  ,zone3='$zone3',zone3_delivery_charges='$zone3_delivery_charges',zone3_min_total='$zone3_min_total',zone3_coordinates='$zone3_coordinates'";
                        $mSQL .= ",delivery_option='delivery_zones' where id=".$id;
                        Log::write('Insert into signupdetails Deliver Zones - signup_ajax', "QUERY --".$mSQL, 'SelfSignup', 1);
                        mysql_query($mSQL);
                        $mTmpID = $id;
                }
                else
                {
                        $mSQL = "INSERT INTO `signupdetails` SET zone1='$zone1',zone1_delivery_charges='$zone1_delivery_charges',zone1_min_total='$zone1_min_total',zone1_coordinates='$zone1_coordinates'";
                        $mSQL .="  ,zone2='$zone2',zone2_delivery_charges='$zone2_delivery_charges',zone2_min_total='$zone2_min_total',zone2_coordinates='$zone2_coordinates'";
                        $mSQL .="  ,zone3='$zone3',zone3_delivery_charges='$zone3_delivery_charges',zone3_min_total='$zone3_min_total',zone3_coordinates='$zone3_coordinates'";
                        $mSQL .= ",delivery_option='delivery_zones'";
                        Log::write('Insert into signupdetails Deliver Zones - signup_ajax', "QUERY --".$mSQL, 'SelfSignup', 1);
                        mysql_query($mSQL);
                        $mTmpID = mysql_insert_id();
                }
        }
        else
        {
                /*
                        $mSQL = "INSERT INTO `signupdetails` (zone1, zone1_delivery_charges, zone1_min_total, zone1_coordinates, zone2, zone2_delivery_charges ,zone2_min_total, zone2_coordinates, zone3, zone3_delivery_charges, zone3_min_total, zone3_coordinates='$zone3_coordinates, delivery_option) VALUES ";
                        $mSQL .= " ('$zone1', '$zone1_delivery_charges', '$zone1_min_total', '$zone1_coordinates', '$zone2', '$zone2_delivery_charges', '$zone2_min_total', '$zone2_coordinates', '$zone3', '$zone3_delivery_charges', '$zone3_min_total', '$zone3_coordinates', 'delivery_zones') ";
                        */
                $mSQL = "INSERT INTO `signupdetails` SET zone1='$zone1',zone1_delivery_charges='$zone1_delivery_charges',zone1_min_total='$zone1_min_total',zone1_coordinates='$zone1_coordinates'";
                $mSQL .="  ,zone2='$zone2',zone2_delivery_charges='$zone2_delivery_charges',zone2_min_total='$zone2_min_total',zone2_coordinates='$zone2_coordinates'";
                $mSQL .="  ,zone3='$zone3',zone3_delivery_charges='$zone3_delivery_charges',zone3_min_total='$zone3_min_total',zone3_coordinates='$zone3_coordinates'";
                $mSQL .= ",delivery_option='delivery_zones'";
                Log::write('Insert into signupdetails Deliver Zones - signup_ajax', "QUERY --".$mSQL, 'SelfSignup', 1);
                mysql_query($mSQL);
                $mTmpID = mysql_insert_id();
        }

        echo($mTmpID);
}
else if ($_GET["call"]=="uploadMenu")
{   
    $mRestaurantID = $_GET['resrId'];
    foreach($_FILES as $key=>$value)
    {
        $mFileName = $value['name'];
        $mExt = GetFileExt($value['name']);

        if (!file_exists('signupUploads'))
        {
                mkdir('signupUploads', 0777, true);
        }

        $mPath = 'signupUploads/';
        $mRandom = mt_rand(1, mt_getrandmax());
        $mFileName = str_replace(".", "_", str_replace(" ", "_", basename($value['name'],$mExt)))."_".$mRandom.$mExt;
        $mFilePath =  $mPath.$mFileName;
        if (!move_uploaded_file($value['tmp_name'] , $mFilePath))
        {
                $mUploadError = "Error";
        }
        else
        {
                $mSQL = "INSERT INTO  signupuploads (`RestaurantID`, `FileName`, `MenuName`) VALUES ";
                $mSQL .= "(".$mRestaurantID.", '".mysql_real_escape_string($mFileName)."', '".mysql_real_escape_string($mFileName)."')";
                Log::write('Insert into signupuploads - signup_ajax', "QUERY --".$mSQL, 'SelfSignup', 1);
                mysql_query($mSQL);
        }
        
    }
}
function GetFileExt($pFileName)
{
	$mExt = substr($pFileName, strrpos($pFileName, '.'));
	$mExt = strtolower($mExt);
	return $mExt;
}

?>
