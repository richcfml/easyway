<?php
require_once("includes/config.php");
include_once("includes/class.phpmailer.php");
include_once("c_panel/classes/ChargifyApi.php");

function prepareStringForMySQL($string){
    $string=str_replace ( "\r" , "<br/>",$string);
    $string=str_replace ( "\n" , "<br/>",$string);
    $string=str_replace ( "\t" , " ",$string);
    $string=dbAbstract::returnRealEscapedString($string);
    return $string;
}

if (isset($_GET["call"]))
{
	if ($_GET["call"]=="validateuser")
	{
		if (isset($_GET["username"]))
		{
			dbAbstract::Insert("INSERT INTO tblDebug(Step, Value1, Value2) VALUES (1, 'ABCD', '1')");
			$mSQL = "SELECT COUNT(*) AS UserCount FROM `users` WHERE LOWER(TRIM(username))='".trim(strtolower($_GET["username"]))."'";
			$mResult = dbAbstract::Execute($mSQL);
			if (dbAbstract::returnRowsCount($mResult)>0)
			{
				$mRow = dbAbstract::returnObject($mResult);
				if ($mRow->UserCount>0)
				{
					echo("duplicate");
				}
				else
				{
					echo("new");
				}
			}
			else
			{
				echo("error2");
			}
		}
		else
		{
			echo("error1");
		}
	}
	else if ($_GET["call"]=="saveuser")
	{
		extract ($_POST);
		$mNewDomain = 0;
		if (isset($rbNewDomain))
		{
			$mNewDomain = 1;
		}
		
		if (trim(strtolower($txtDomainName))=="")
		{
			$mNewDomain = 1;
		}

		$mSQL = "INSERT INTO  signupdetails (`TimeZoneID`, `RestaurantName`, `Address`, `State`, `City`, `ZipCode`, `Country`, `PhoneNumber`, `FaxNumber`, `FullName`, `EmailAddress`, `Password`, `UserName`, `OrderReceive`, `Delivery`, `Tax`, `Cash`, `CreditCard`, `GateWay`, `DomainName`, `NewDomain`, `MenuUse`, `HostingInformation`, `DeliveryMinimum`, `DeliveryCharges`, `DeliveryRadius`, `ClientAddress`, `ClientState`, `ClientCity`, `ClientZipCode`, `ClientCountry`) VALUES ";
		$mSQL .= "(".$ddlTimeZone.", '".dbAbstract::returnRealEscapedString($txtRestaurantName)."', '".dbAbstract::returnRealEscapedString($txtStreetAddress)."', '".dbAbstract::returnRealEscapedString($txtState)."', '".dbAbstract::returnRealEscapedString($txtCity)."', '".dbAbstract::returnRealEscapedString($txtZip)."', '".$ddlCountry1."', '".dbAbstract::returnRealEscapedString($txtPhone)."', '".dbAbstract::returnRealEscapedString($txtFax)."', '".dbAbstract::returnRealEscapedString($txtFullName)."', '".dbAbstract::returnRealEscapedString($txtEmailAddress)."', '".dbAbstract::returnRealEscapedString($txtPassword)."', '".dbAbstract::returnRealEscapedString($txtUserName)."', ".$rbOrders.", ".$rbDelivery.", '".dbAbstract::returnRealEscapedString($txtTax)."', ".$rbCash.", ".$rbCreditCard.", ".$rbGateWay.", '".dbAbstract::returnRealEscapedString($txtDomainName)."', ".$mNewDomain.", ".$rbMenuUse.", ".$rbHosting.", '".$txtDeliveryMinimum."', '".$txtDeliveryCharges."', '".$txtDeliveryRadius."', '".dbAbstract::returnRealEscapedString($txtClientAddress)."', '".dbAbstract::returnRealEscapedString($txtClientState)."', '".dbAbstract::returnRealEscapedString($txtClientCity)."', '".dbAbstract::returnRealEscapedString($txtClientZip)."', '".dbAbstract::returnRealEscapedString($ddlCountry2)."')";   
                $mInsertID = dbAbstract::Insert($mSQL, 0, 2);
		if ($mInsertID > 0)
		{
			$mSDID = $mInsertID;
			$mEchoFlag = 0;
			
			if (isset($_GET["savebtn"]))
			{
				$mProductID = $ddlProducts;
				$mSQL = "SELECT `user_id`, IFNULL(`premium_account`, 0) AS `premium_account` FROM `chargify_products` WHERE product_id=".$mProductID;
				$mResPre = dbAbstract::Execute($mSQL);
				$mRowPre = dbAbstract::returnObject($mResPre);
				$mPremiumAccount = $mRowPre->premium_account;
				$mParentID = $mRowPre->user_id;
				
				$mSQL = "SELECT id, license_key FROM licenses WHERE reseller_id=".$mParentID." AND status='unused' LIMIT 1";
				$mResLi = dbAbstract::Execute($mSQL);
				if (dbAbstract::returnRowsCount($mResLi)>0)
				{
					$mRowLi = dbAbstract::returnObject($mResLi);
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
	
					$mProductID = $ddlProducts;
					$mObjCAPI = new ChargifyApi();
	
					$mCustomerID = $mObjCAPI->createCustomer($mFirstName, $mLastName, $txtEmailAddress, $txtRestaurantName, $txtCity, $txtState, $txtZip, $ddlCountry1);
					if (isset($mCustomerID))
					{
						if (is_numeric($mCustomerID))
						{
							if ($mCustomerID>0)
							{
								$mResultSub = $mObjCAPI->createSubcription($mProductID, $mCustomerID, "automatic", "", $txtCreditCardNumber, $ddlExpMonth, $ddlExpYear);	
								
								if (!isset($mResultSub->errors))
								{
									$mResultSub->subscription = (object) $mResultSub->subscription;
									$mSubscriptionID = $mResultSub->subscription->id;
									$mCC = "";
									$mPaymant_Profile_ID = "";
									$mMaskedCC = "";
									if (isset($mResultSub->subscription->credit_card))
									{
										$mCC = $mResultSub->subscription->credit_card;
										$mCC = (object) $mCC;
										$mPaymant_Profile_ID = $mCC->id;
										$mMaskedCC = $mCC->masked_card_number;
									}
									
									$mResultSubRes = $mObjCAPI->createSubcription($mProductID, $mCustomerID, "automatic", "", $txtCreditCardNumber, $ddlExpMonth, $ddlExpYear);	
									
									if (!isset($mResultSubRes->errors))
									{
										$mResultSubRes->subscription = (object) $mResultSubRes->subscription;
										$mRestSubscriptionID = $mResultSubRes->subscription->id;
										$mSRID ="";
										if ((trim($mPremiumAccount)==0) || (trim($mPremiumAccount)=="0") || (trim($mPremiumAccount)==""))
										{
											$mSRID = $mObjCAPI->createVendestaPremium($txtRestaurantName, str_replace(",", "", $txtStreetAddress), $txtCity, $txtState, $txtZip, 'true');
										}
										else
										{
											$mSRID = $mObjCAPI->createVendestaPremium($txtRestaurantName, $txtStreetAddress, $txtCity, $txtState, $txtZip, 'false');
										}
										
										$mSQL = "UPDATE `signupdetails` SET CustomerID='".$mCustomerID."', SubscriptionID='".$mSubscriptionID."', SRID='".$mSRID."' WHERE ID=".$mSDID;
										dbAbstract::Update($mSQL);
										
										$mSQL = "INSERT INTO `users` (firstname, lastname, email, username, password, country, state, city, zip, status, type, phone, company_name, parent_id, chargify_subcription_id, chargify_customer_id) VALUES ";
										$mSQL .= " ('".$mFirstName."', '".$mLastName."', '".$txtEmailAddress."', '".$txtUserName."', '".$txtPassword."', '".$ddlCountry1."', '".$txtState."', '".$txtCity."', '".$txtZip."', '1', 'store owner', '".$txtPhone."', '".$txtRestaurantName."', '".$mParentID."', '".$mCustomerID."', '".$mSubscriptionID."')";
										$mUserID = dbAbstract::Insert($mSQL, 0, 2);
										
										$mSQL = "INSERT INTO chargify_payment_method (user_id, chargify_customer_id, Payment_profile_id, card_number, billing_address, billing_address_2, billing_city, billing_country, billing_state, billing_zip, first_name, last_name, expiration_month, expiration_year) VALUES ";
										$mSQL .= "(".$mUserID.", ".$mCustomerID.", ".$mPaymant_Profile_ID.", '".$mMaskedCC."', '".$txtClientAddress."', '', '".$txtClientCity."', '".$ddlCountry2."', '".$txtClientState."', '".$txtClientZip."', '".$mFirstName."', '".$mLastName."', '".$ddlExpMonth."', '".$ddlExpYear."')";
										dbAbstract::Insert($mSQL);
										
										dbAbstract::Insert("INSERT INTO reseller_client (reseller_id,client_id) VALUES ('".$mParentID."','".$mUserID."')");
										
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
										$mResSD = dbAbstract::Execute($mSQL);
										$mRowSD = dbAbstract::returnObject($mResSD);

										if ($mRowSD->delivery_option=="delivery_zones")
										{
											$mDelOpt = "delivery_zones";
										}
										else
										{
											$mDelOpt = "radius";
										}
										
										$mRest_url_name = url_title($mRowSD->RestaurantName);
										
										$mSQL = "INSERT INTO `resturants` (name, delivery_charges, owner_id, license_id, status, email, fax, order_destination, phone, payment_method, region, time_zone_id, rest_address, rest_city, rest_state, rest_zip, delivery_radius, zone1, zone1_delivery_charges, zone1_min_total, zone1_coordinates, zone2, zone2_delivery_charges, zone2_min_total, zone2_coordinates, zone3, zone3_delivery_charges, zone3_min_total, zone3_coordinates, chargify_product_id, chargify_subscription_id, domain_name_details, website_integration_type, hosting_information, premium_account, srid, delivery_option, url_name) ";
										$mSQL .= " VALUES ('".prepareStringForMySQL($mRowSD->RestaurantName)."', '".$mRowSD->DeliveryCharges."', ".$mUserID.", ".$mLicenseID.", 1, '".$mRowSD->EmailAddress."', '".$mRowSD->FaxNumber."', '".$mOrderDest."', '".$mRowSD->PhoneNumber."', '".$mPaymentMethod."', ".$mRegion.", ".$mRowSD->TimeZoneID.", '".$mRowSD->Address."', '".$mRowSD->City."', '".$mRowSD->State."', '".$mRowSD->ZipCode."', '".$mRowSD->DeliveryRadius."', '".$mRowSD->zone1."', '".$mRowSD->zone1_delivery_charges."', '".$mRowSD->zone1_min_total."', '".$mRowSD->zone1_coordinates."', '".$mRowSD->zone2."', '".$mRowSD->zone2_delivery_charges."', '".$mRowSD->zone2_min_total."', '".$mRowSD->zone2_coordinates."', '".$mRowSD->zone3."', '".$mRowSD->zone3_delivery_charges."', '".$mRowSD->zone3_min_total."', '".$mRowSD->zone3_coordinates."', ".$mProductID.", '".$mRestSubscriptionID."', '".$mRowSD->DomainName."', '".$mRowSD->MenuUse."', '".$mRowSD->HostingInformation."', ".$mPremiumAccount.",  '".$mSRID."', '".$mDelOpt."', '".$mRest_url_name."')";

										$mRestaurantID = dbAbstract::Insert($mSQL, 0, 2);
										
										dbAbstract::Update("UPDATE `signupdetails` SET RestaurantID=".$mRestaurantID." WHERE ID=".$mSDID);
										
										dbAbstract::Update("UPDATE licenses SET  status='activated', resturant_id=".$mRestaurantID." WHERE license_key ='".$mLicense."' and reseller_id=".$mParentID);	
										
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
										$mResMen = dbAbstract::Execute($mSQL);
										if (dbAbstract::returnRowsCount($mResMen)>0)
										{
											while ($mRowMen = dbAbstract::returnObject($mResMen))
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
										}
										else if ($rbMenuUse==2)
										{
											$mIntegrationChoice = "Use EasyWay Ordering menu as my website";
										}
										else if ($rbMenuUse==3)
										{
											$mIntegrationChoice = "I would like a custome website built for me";
										}
										
										$mHostingStr = "I will make all necessary changes";
										if ($rbHosting==1)
										{
											$mHostingStr = "I would like EasyWay to configure my hosting account and or integrate the ordering page with my website.";											
										}
										else if ($rbHosting==2)
										{
											$mHostingStr = "My webmaster will make all necessary changes";												
										}
										else if ($rbHosting==3)
										{
											$mHostingStr = "I will make all necessary changes";											
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
																		<span style="font-weight: bold;">Will you receive orders via Fax, Email or POS?: </span>
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
																</tr>
																<tr style="height: 10px;">
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
													$mResMen = dbAbstract::Execute($mSQL);
													if (dbAbstract::returnRowsCount($mResMen)>0)
													{
														while ($mRowMen = dbAbstract::returnObject($mResMen))
														{
															$objMail->addattachment("signupUploads/".$mRowMen->FileName);
														}
													}
													$objMail->sendTo($mMessage, $mSubject, "gulfam@qualityclix.com", true);
													$objMail->sendTo($mMessage, $mSubject, "menus@easywayordering.com", true);
										/* ------------------------------ Email Ends ----------------------------- */
									}
									else
									{
										$mEchoFlag = 1;
										echo($mSDID."ERROR: ".$mResultSubRes->errors[0]);
									}
								}
								else
								{
									$mEchoFlag = 1;
									echo($mSDID."ERROR: ".$mResultSub->errors[0]);
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
			}
			else
			{
				$objMail = new testmail();
				$objMail->clearattachments();
				
				$mToken = "";
				$mResTKn = dbAbstract::Execute("SELECT IFNULL(Token, 0) AS Token FROM signupdetails WHERE ID=".$mSDID);
				$mRowTKn = dbAbstract::returnObject($mResTKn);
				if ($mRowTKn->Token>0)
				{
					$mToken = $mRowTKn->Token;
				}
				else
				{
					$mToken = mt_rand(1, mt_getrandmax());
				}
				dbAbstract::Update("UPDATE signupdetails SET Token='".$mToken."' WHERE ID=".$mSDID);
				
				$mMessage = '<table style="font:Arial; font-family: Arial; font-size: 14px; width: 80%; border: 0" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td valign="top" style="width: 3%;">
									</td>
									<td valign="top" style="width: 94%;">
										<table style="font:Arial; font-family: Arial; font-size: 14px; width: 100%; border: 0" border="0" cellpadding="0" cellspacing="0">
											<tr style="height: 15px;">
												<td valign="top">
												</td>
											</tr>
											<tr>
												<td valign="top">
													<span style="font-size: 14px;">Signup process has been started. In case the Signup process cannot be completed. The Signup process can be resumed using following link.</span>
												</td>
											</tr>
											<tr style="height: 10px;">
												<td valign="top">
												</td>
											</tr>
											<tr style="height: 15px;">
												<td valign="top">
													<a href="'.$SiteUrl.'signup.php?sdid='.$mSDID.'&token='.$mToken.'&flag=start#signupstarted">'.$SiteUrl.'signup.php?sdid='.$mSDID.'&token='.$mToken.'&flag=start#signupstarted</a>
												</td>
											</tr>
											<tr style="height: 30px;">
												<td valign="top">
												</td>
											</tr>
											<tr style="height: 15px;">
												<td valign="top">
													Thank You,
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>';
							
				$mResMS = dbAbstract::Execute("SELECT IFNULL(MenusMailSent, 0) AS MenusMailSent, IFNULL(MailSent, 0) AS MailSent FROM signupdetails WHERE ID=".$mSDID);
				$mRowMS = dbAbstract::returnObject($mResMS);
				
				if ($mRowMS->MenusMailSent==0)
				{
					$objMail->sendTo($mMessage, "Signup wizard Started", "menus@easywayordering.com", true);
					$objMail->sendTo($mMessage, "Signup wizard Started", "gulfam@qualityclix.com", true);
					dbAbstract::Update("UPDATE signupdetails SET MenusMailSent=1 WHERE ID=".$mSDID);
				}
				
				if ($mRowMS->MailSent==0)
				{
					if (trim($txtEmailAddress)!="")
					{
						$objMail->sendTo($mMessage, "Signup wizard Started", $txtEmailAddress, true);
						dbAbstract::Update("UPDATE signupdetails SET MailSent=1 WHERE ID=".$mSDID);
					}
				}
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
	else if ($_GET["call"]=="updateuser")
	{
		extract ($_POST);
		$mNewDomain = 0;
		if (isset($rbNewDomain))
		{
			$mNewDomain = 1;
		}
		
		if (trim(strtolower($txtDomainName))=="")
		{
			$mNewDomain = 1;
		}

		$mSQL = "UPDATE signupdetails SET `TimeZoneID`=".$ddlTimeZone.", `RestaurantName`='".dbAbstract::returnRealEscapedString($txtRestaurantName)."', `Address`='".dbAbstract::returnRealEscapedString($txtStreetAddress)."', `State`='".dbAbstract::returnRealEscapedString($txtState)."', `City`='".dbAbstract::returnRealEscapedString($txtCity)."', `ZipCode`='".dbAbstract::returnRealEscapedString($txtZip)."', `Country`='".$ddlCountry1."', `PhoneNumber`='".dbAbstract::returnRealEscapedString($txtPhone)."', `FaxNumber`='".dbAbstract::returnRealEscapedString($txtFax)."', `FullName`='".dbAbstract::returnRealEscapedString($txtFullName)."', `EmailAddress`='".dbAbstract::returnRealEscapedString($txtEmailAddress)."', `Password`='".dbAbstract::returnRealEscapedString($txtPassword)."', `UserName`='".dbAbstract::returnRealEscapedString($txtUserName)."', `OrderReceive`=".$rbOrders.", `Delivery`=".$rbDelivery.", `Tax`='".dbAbstract::returnRealEscapedString($txtTax)."', `Cash`=".$rbCash.", `CreditCard`=".$rbCreditCard.", `GateWay`=".$rbGateWay.", `DomainName`='".dbAbstract::returnRealEscapedString($txtDomainName)."', `NewDomain`=".$mNewDomain.", `MenuUse`=".$rbMenuUse.", `HostingInformation`=".$rbHosting.", `DeliveryMinimum`='".$txtDeliveryMinimum."', `DeliveryCharges`='".$txtDeliveryCharges."', `DeliveryRadius`='".$txtDeliveryRadius."', `ClientAddress`='".dbAbstract::returnRealEscapedString($txtClientAddress)."', `ClientState`='".dbAbstract::returnRealEscapedString($txtClientState)."', `ClientCity`='".dbAbstract::returnRealEscapedString($txtClientCity)."', `ClientZipCode`='".dbAbstract::returnRealEscapedString($txtClientZip)."', `ClientCountry`='".$ddlCountry2."' WHERE ID=".$txtRestaurantID;
		
		if (dbAbstract::Update($mSQL))
		{
			$mEchoFlag = 0;
			if (isset($_GET["savebtn"]))
			{
				if (trim(strlen($txtDeletedFiles))>0)
				{
					if (strpos(trim($txtDeletedFiles), ",")>0)
					{
						$mIDs = explode(",", trim($txtDeletedFiles));
						for ($loopCount=0; $loopCount<count($mIDs); $loopCount++)
						{
							if (is_numeric($mIDs[$loopCount]) && ($mIDs[$loopCount]>0))
							{
								$mSQL = "SELECT FileName FROM `signupuploads` WHERE ID=".$mIDs[$loopCount];
								$mResFiles = dbAbstract::Execute($mSQL);
								if (dbAbstract::returnRowsCount($mResFiles)>0)
								{
									$mRowFile = dbAbstract::returnObject($mResFiles);
									$mFileName = $mRowFile->FileName;
									if (file_exists(realpath("signupUploads/".$mFileName))) 
									{
										@unlink(realpath("signupUploads/".$mFileName));
									}
									
									$mSQL = "DELETE FROM `signupuploads` WHERE ID=".$mIDs[$loopCount];
									dbAbstract::Delete($mSQL);
								}
							}
						}
						
					}
				}
		
				$mProductID = $ddlProducts;
				$mSQL = "SELECT `user_id`, IFNULL(`premium_account`, 0) AS `premium_account` FROM `chargify_products` WHERE product_id=".$mProductID;
				$mResPre = dbAbstract::Execute($mSQL);
				$mRowPre = dbAbstract::returnObject($mResPre);
				$mPremiumAccount = $mRowPre->premium_account;
				$mParentID = $mRowPre->user_id;
				
				$mSQL = "SELECT id, license_key FROM licenses WHERE reseller_id=".$mParentID." AND status='unused' LIMIT 1";
				$mResLi = dbAbstract::Execute($mSQL);
				if (dbAbstract::returnRowsCount($mResLi)>0)
				{
					$mRowLi = dbAbstract::returnObject($mResLi);
					$mLicense = $mRowLi->license_key;
					$mLicenseID = $mRowLi->id;
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
	
					$mObjCAPI = new ChargifyApi();
	
					$mCustomerID = $mObjCAPI->createCustomer($mFirstName, $mLastName, $txtEmailAddress, $txtRestaurantName, $txtCity, $txtState, $txtZip, $ddlCountry1);
					if (isset($mCustomerID))
					{
						if (is_numeric($mCustomerID))
						{
							if ($mCustomerID>0)
							{
								$mResultSub = $mObjCAPI->createSubcription($mProductID, $mCustomerID, "automatic", "", $txtCreditCardNumber, $ddlExpMonth, $ddlExpYear);	
								
								if (!isset($mResultSub->errors))
								{
									$mResultSub->subscription = (object) $mResultSub->subscription;
									$mSubscriptionID = $mResultSub->subscription->id;
									$mCC = "";
									$mPaymant_Profile_ID = "";
									$mMaskedCC = "";
									if (isset($mResultSub->subscription->credit_card))
									{
										$mCC = $mResultSub->subscription->credit_card;
										$mCC = (object) $mCC;
										$mPaymant_Profile_ID = $mCC->id;
										$mMaskedCC = $mCC->masked_card_number;
									}
									
									$mResultSubRes = $mObjCAPI->createSubcription($mProductID, $mCustomerID, "automatic", "", $txtCreditCardNumber, $ddlExpMonth, $ddlExpYear);	
									
									if (!isset($mResultSubRes->errors))
									{
										$mResultSubRes->subscription = (object) $mResultSubRes->subscription;
										$mRestSubscriptionID = $mResultSubRes->subscription->id;
										$mSRID ="";
										if ((trim($mPremiumAccount)==0) || (trim($mPremiumAccount)=="0") || (trim($mPremiumAccount)==""))
										{
											$mSRID = $mObjCAPI->createVendestaPremium($txtRestaurantName, str_replace(",", "", $txtStreetAddress), $txtCity, $txtState, $txtZip, 'true');
										}
										else
										{
											$mSRID = $mObjCAPI->createVendestaPremium($txtRestaurantName, $txtStreetAddress, $txtCity, $txtState, $txtZip, 'false');
										}
										$mSQL = "UPDATE `signupdetails` SET CustomerID='".$mCustomerID."', SubscriptionID='".$mSubscriptionID."', SRID='".$mSRID."' WHERE ID=".$txtRestaurantID;
										dbAbstract::Update($mSQL);
															
										$mSQL = "INSERT INTO `users` (firstname, lastname, email, username, password, country, state, city, zip, status, type, phone, company_name, parent_id, chargify_subcription_id, chargify_customer_id) VALUES ";
										$mSQL .= " ('".$mFirstName."', '".$mLastName."', '".$txtEmailAddress."', '".$txtUserName."', '".$txtPassword."', '".$ddlCountry1."', '".$txtState."', '".$txtCity."', '".$txtZip."', '1', 'store owner', '".$txtPhone."', '".$txtRestaurantName."', '".$mParentID."', '".$mCustomerID."', '".$mSubscriptionID."')";
										$mUserID = dbAbstract::Insert($mSQL, 0 , 2);
										
										$mSQL = "INSERT INTO chargify_payment_method (user_id, chargify_customer_id, Payment_profile_id, card_number, billing_address, billing_address_2, billing_city, billing_country, billing_state, billing_zip, first_name, last_name, expiration_month, expiration_year) VALUES ";
										$mSQL .= "(".$mUserID.", ".$mCustomerID.", ".$mPaymant_Profile_ID.", '".$mMaskedCC."', '".$txtClientAddress."', '', '".$txtClientCity."', '".$ddlCountry2."', '".$txtClientState."', '".$txtClientZip."', '".$mFirstName."', '".$mLastName."', '".$ddlExpMonth."', '".$ddlExpYear."')";
										dbAbstract::Insert($mSQL);
										
										
										dbAbstract::Insert("INSERT INTO reseller_client (reseller_id,client_id) VALUES ('".$mParentID."','".$mUserID."')");
										
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
										
										
										$mSQL = "SELECT * FROM `signupdetails` WHERE ID=".$txtRestaurantID;
										$mResSD = dbAbstract::Execute($mSQL);
										$mRowSD = dbAbstract::returnObject($mResSD);

										if ($mRowSD->delivery_option=="delivery_zones")
										{
											$mDelOpt = "delivery_zones";
										}
										else
										{
											$mDelOpt = "radius";
										}
										
										$mRest_url_name = url_title($mRowSD->RestaurantName);
										
										$mSQL = "INSERT INTO `resturants` (name, delivery_charges, owner_id, license_id, status, email, fax, order_destination, phone, payment_method, region, time_zone_id, rest_address, rest_city, rest_state, rest_zip, delivery_radius, zone1, zone1_delivery_charges, zone1_min_total, zone1_coordinates, zone2, zone2_delivery_charges, zone2_min_total, zone2_coordinates, zone3, zone3_delivery_charges, zone3_min_total, zone3_coordinates, chargify_product_id, chargify_subscription_id, domain_name_details, website_integration_type, hosting_information, premium_account, srid, delivery_option, url_name) ";
										$mSQL .= " VALUES ('".prepareStringForMySQL($mRowSD->RestaurantName)."', '".$mRowSD->DeliveryCharges."', ".$mUserID.", ".$mLicenseID.", 1, '".$mRowSD->EmailAddress."', '".$mRowSD->FaxNumber."', '".$mOrderDest."', '".$mRowSD->PhoneNumber."', '".$mPaymentMethod."', ".$mRegion.", ".$mRowSD->TimeZoneID.", '".$mRowSD->Address."', '".$mRowSD->City."', '".$mRowSD->State."', '".$mRowSD->ZipCode."', '".$mRowSD->DeliveryRadius."', '".$mRowSD->zone1."', '".$mRowSD->zone1_delivery_charges."', '".$mRowSD->zone1_min_total."', '".$mRowSD->zone1_coordinates."', '".$mRowSD->zone2."', '".$mRowSD->zone2_delivery_charges."', '".$mRowSD->zone2_min_total."', '".$mRowSD->zone2_coordinates."', '".$mRowSD->zone3."', '".$mRowSD->zone3_delivery_charges."', '".$mRowSD->zone3_min_total."', '".$mRowSD->zone3_coordinates."', ".$mProductID.", '".$mRestSubscriptionID."', '".$mRowSD->DomainName."', '".$mRowSD->MenuUse."', '".$mRowSD->HostingInformation."', ".$mPremiumAccount.",  '".$mSRID."', '".$mDelOpt."', '".$mRest_url_name."')";

										$mRestaurantID = dbAbstract::Insert($mSQL, 0, 2);
										
										dbAbstract::Update("UPDATE `signupdetails` SET RestaurantID=".$mRestaurantID." WHERE ID=".$txtRestaurantID);
										
										dbAbstract::Update("UPDATE licenses SET  status='activated', resturant_id=".$mRestaurantID." WHERE license_key ='".$mLicense."' and reseller_id=".$mParentID);	
										
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
										$mSQL = "SELECT MenuName FROM signupuploads WHERE RestaurantID=".$txtRestaurantID;
										$mResMen = dbAbstract::Execute($mSQL);
										if (dbAbstract::returnRowsCount($mResMen)>0)
										{
											while ($mRowMen = dbAbstract::returnObject($mResMen))
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
										}
										else if ($rbMenuUse==2)
										{
											$mIntegrationChoice = "Use EasyWay Ordering menu as my website";
										}
										else if ($rbMenuUse==3)
										{
											$mIntegrationChoice = "I would like a custome website built for me";
										}
										
										$mHostingStr = "I will make all necessary changes";
										if ($rbHosting==1)
										{
											$mHostingStr = "I would like EasyWay to configure my hosting account and or integrate the ordering page with my website.";											
										}
										else if ($rbHosting==2)
										{
											$mHostingStr = "My webmaster will make all necessary changes";												
										}
										else if ($rbHosting==3)
										{
											$mHostingStr = "I will make all necessary changes";											
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
																		<span style="font-weight: bold;">Will you receive orders via Fax, Email or POS?: </span>
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
																</tr>
																<tr style="height: 10px;">
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
													
													$mSQL = "SELECT FileName FROM signupuploads WHERE RestaurantID=".$txtRestaurantID;
													$mResMen = dbAbstract::Execute($mSQL);
													if (dbAbstract::returnRowsCount($mResMen)>0)
													{
														while ($mRowMen = dbAbstract::returnObject($mResMen))
														{
															$objMail->addattachment("signupUploads/".$mRowMen->FileName);
														}
													}
													$objMail->sendTo($mMessage, $mSubject, "gulfam@qualityclix.com", true);
													$objMail->sendTo($mMessage, $mSubject, "menus@easywayordering.com", true);
										/* ------------------------------ Email Ends ----------------------------- */
										
									}
									else
									{
										$mEchoFlag = 1;
										echo($mResultSubRes->errors[0]);
									}
								}
								else
								{
									$mEchoFlag = 1;
									echo($mResultSub->errors[0]);
								}
							}
							else
							{
								$mEchoFlag = 1;							
								echo("ERROR: Customer in Chargify was not created.");
							}
						}
						else
						{
							$mEchoFlag = 1;						
							echo("ERROR: Customer in Chargify was not created.");
						}
					}
					else
					{
						$mEchoFlag = 1;
						echo("ERROR: Customer in Chargify was not created.");
					}
				}
				else
				{
					$mEchoFlag = 1;
					echo("No un-used license for this reseller.");
				}
			}
			else
			{
				$objMail = new testmail();
				$objMail->clearattachments();
				$mToken = "";
				$mResTKn = dbAbstract::Execute("SELECT IFNULL(Token, 0) AS Token FROM signupdetails WHERE ID=".$txtRestaurantID);
				$mRowTKn = dbAbstract::returnObject($mResTKn);
				if ($mRowTKn->Token>0)
				{
					$mToken = $mRowTKn->Token;
				}
				else
				{
					$mToken = mt_rand(1, mt_getrandmax());
				}
				dbAbstract::Update("UPDATE signupdetails SET Token='".$mToken."' WHERE ID=".$txtRestaurantID);
				
				$mMessage = '<table style="font:Arial; font-family: Arial; font-size: 14px; width: 80%; border: 0" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td valign="top" style="width: 3%;">
									</td>
									<td valign="top" style="width: 94%;">
										<table style="font:Arial; font-family: Arial; font-size: 14px; width: 100%; border: 0" border="0" cellpadding="0" cellspacing="0">
											<tr style="height: 15px;">
												<td valign="top">
												</td>
											</tr>
											<tr>
												<td valign="top">
													<span style="font-size: 14px;">Signup process has been started. In case the Signup process cannot be completed. The Signup process can be resumed using following link.</span>
												</td>
											</tr>
											<tr style="height: 10px;">
												<td valign="top">
												</td>
											</tr>
											<tr style="height: 15px;">
												<td valign="top">
													<a href="'.$SiteUrl.'signup.php?sdid='.$txtRestaurantID.'&token='.$mToken.'&flag=start#signupstarted">'.$SiteUrl.'signup.php?sdid='.$txtRestaurantID.'&token='.$mToken.'&flag=start#signupstarted</a>
												</td>
											</tr>
											<tr style="height: 30px;">
												<td valign="top">
												</td>
											</tr>
											<tr style="height: 15px;">
												<td valign="top">
													Thank You,
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>';
				$mResMS = dbAbstract::Execute("SELECT IFNULL(MenusMailSent, 0) AS MenusMailSent, IFNULL(MailSent, 0) AS MailSent FROM signupdetails WHERE ID=".$txtRestaurantID);
				$mRowMS = dbAbstract::returnObject($mResMS);
				
				if ($mRowMS->MenusMailSent==0)
				{
					$objMail->sendTo($mMessage, "Signup wizard Started", "menus@easywayordering.com", true);
					$objMail->sendTo($mMessage, "Signup wizard Started", "gulfam@qualityclix.com", true);
					dbAbstract::Update("UPDATE signupdetails SET MenusMailSent=1 WHERE ID=".$txtRestaurantID);
				}
				
				if ($mRowMS->MailSent==0)
				{
					if (trim($txtEmailAddress)!="")
					{
						$objMail->sendTo($mMessage, "Signup wizard Started", $txtEmailAddress, true);
						dbAbstract::Update("UPDATE signupdetails SET MailSent=1 WHERE ID=".$txtRestaurantID);
					}
				}
			}
			
			if ($mEchoFlag!=1)
			{
				echo($txtRestaurantID);
			}
		}
		else
		{
			echo("Error occurred while updating.");
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
				dbAbstract::Update($mSQL);
				$mTmpID = $id;
			}
			else
			{
				$mSQL = "INSERT INTO `signupdetails` SET zone1='$zone1',zone1_delivery_charges='$zone1_delivery_charges',zone1_min_total='$zone1_min_total',zone1_coordinates='$zone1_coordinates'";
				$mSQL .="  ,zone2='$zone2',zone2_delivery_charges='$zone2_delivery_charges',zone2_min_total='$zone2_min_total',zone2_coordinates='$zone2_coordinates'";
				$mSQL .="  ,zone3='$zone3',zone3_delivery_charges='$zone3_delivery_charges',zone3_min_total='$zone3_min_total',zone3_coordinates='$zone3_coordinates'";
				$mSQL .= ",delivery_option='delivery_zones'";
				$mTmpID = dbAbstract::Insert($mSQL, 0, 2);
			}
		}
		else
		{
			$mSQL = "INSERT INTO `signupdetails` SET zone1='$zone1',zone1_delivery_charges='$zone1_delivery_charges',zone1_min_total='$zone1_min_total',zone1_coordinates='$zone1_coordinates'";
			$mSQL .="  ,zone2='$zone2',zone2_delivery_charges='$zone2_delivery_charges',zone2_min_total='$zone2_min_total',zone2_coordinates='$zone2_coordinates'";
			$mSQL .="  ,zone3='$zone3',zone3_delivery_charges='$zone3_delivery_charges',zone3_min_total='$zone3_min_total',zone3_coordinates='$zone3_coordinates'";
			$mSQL .= ",delivery_option='delivery_zones'";
			$mTmpID = dbAbstract::Insert($mSQL, 0, 2);
		}

		echo($mTmpID);
	}
	else
	{
		echo("error");
	}
}
?>