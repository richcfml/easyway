<?php
include_once ("../lib/AuthDotNet/vars.php");
include_once ("../lib/AuthDotNet/util.php");
include_once ("../lib/nmi_api/gwapi.php");

if (isset($_GET['checkfbid'])) //To Check that if a give facebook id is associated with any EWO account
{
	if (isset($_GET['fbid']))
	{
		$mRow = $loggedinuser->SelectUserIDByFBIDRestaurantID($_GET['fbid'], $objRestaurant->id);
		if ($mRow==0) //No EWO account associated
		{
			if (isset($_GET['email']))
			{
				if (trim($_GET['email'])!="")
				{
					$mRow = $loggedinuser->SelectUserIDByEmailRestaurantID(urldecode($_GET['email']), $objRestaurant->id);
					if ($mRow==0) //No EWO account associated
					{
						echo("<ewo_result>0</ewo_result>");
					}
					else
					{							
						$mUserID = $mRow->UserID;
						$mPassword = $mRow->Password;
						$mEmail = $mRow->Email;
						if ($mUserID==0) //Error, because UserID cannot be 0 if there is a row
						{
							echo("<ewo_result>-1</ewo_result>");
						}
						else if ($mUserID>0) //EWO account associated
						{
							$loggedinuser->UpdateFaceBookID($mUserID, $_GET['fbid']);
							$mUser = $loggedinuser->login($mEmail,$mPassword, $objRestaurant->id);
							if(is_null($mUser))
							{
								echo("<ewo_result>-2</ewo_result>");
							}
							else
							{
								$loggedinuser->destroysession();
								$loggedinuser = $mUser;
						
								require($site_root_path . "includes/abandoned_cart_config.php");
						
								if ($objRestaurant->useValutec == 1)  //ValuTec
								{
									if ($loggedinuser->valuetec_card_number > 0) 
									{
										$Balance = CardBalance($loggedinuser->valuetec_card_number);
										$loggedinuser->valuetec_points = $Balance['PointBalance'];
										$loggedinuser->valuetec_reward = $Balance['Balance'];
									}
								} 
								else if ($objRestaurant->useValutec == 2)  //GO3
								{
									if ($loggedinuser->valuetec_card_number > 0) 
									{
										$loggedinuser->valuetec_points = $objGO3->go3RewardPoints($loggedinuser->valuetec_card_number);
										$loggedinuser->valuetec_reward = $objGO3->go3CardBalance($loggedinuser->valuetec_card_number);
									}
								} 
								else 
								{
									$loggedinuser->valuetec_card_number = 0;
								}
						
								$mAddress1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));
						
								$loggedinuser->street1 = $mAddress1[0];
								$loggedinuser->street2 = '';
								if (count($mAddress1) >= 1)
									$loggedinuser->street2 = $mAddress1[1];
						
								$mAddress1 = explode('~', trim($loggedinuser->delivery_address1, '~'));
						
								$loggedinuser->delivery_street1 = $mAddress1[0];
								$loggedinuser->delivery_street2 = '';
								if (count($mAddress1) >= 1)
									$loggedinuser->delivery_street2 = $mAddress1[1];
						
								$loggedinuser->savetosession();
								echo("<ewo_result>".$mUserID."</ewo_result>");
							}
						}
					}
				}
			}
		}
		else
		{
			$mUserID = $mRow->UserID;
			$mPassword = $mRow->Password;
			$mEmail = $mRow->Email;
			if ($mUserID==0) //Error, because UserID cannot be 0 if there is a row
			{
				echo("<ewo_result>-1</ewo_result>");
			}
			else if ($mUserID>0) //EWO account associated
			{
				$mUser = $loggedinuser->login($mEmail,$mPassword, $objRestaurant->id);
				if(is_null($mUser))
				{
					echo("<ewo_result>-2</ewo_result>");
				}
				else
				{
					$loggedinuser->destroysession();
					$loggedinuser = $mUser;
			
					require($site_root_path . "includes/abandoned_cart_config.php");
			
					if ($objRestaurant->useValutec == 1)  //ValuTec
					{
						if ($loggedinuser->valuetec_card_number > 0) 
						{
							$Balance = CardBalance($loggedinuser->valuetec_card_number);
							$loggedinuser->valuetec_points = $Balance['PointBalance'];
							$loggedinuser->valuetec_reward = $Balance['Balance'];
						}
					} 
					else if ($objRestaurant->useValutec == 2)  //GO3
					{
						if ($loggedinuser->valuetec_card_number > 0) 
						{
							$loggedinuser->valuetec_points = $objGO3->go3RewardPoints($loggedinuser->valuetec_card_number);
							$loggedinuser->valuetec_reward = $objGO3->go3CardBalance($loggedinuser->valuetec_card_number);
						}
					} 
					else 
					{
						$loggedinuser->valuetec_card_number = 0;
					}
			
					$mAddress1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));
			
					$loggedinuser->street1 = $mAddress1[0];
					$loggedinuser->street2 = '';
					if (count($mAddress1) >= 1)
						$loggedinuser->street2 = $mAddress1[1];
			
					$mAddress1 = explode('~', trim($loggedinuser->delivery_address1, '~'));
			
					$loggedinuser->delivery_street1 = $mAddress1[0];
					$loggedinuser->delivery_street2 = '';
					if (count($mAddress1) >= 1)
						$loggedinuser->delivery_street2 = $mAddress1[1];
			
					$loggedinuser->savetosession();
					echo("<ewo_result>".$mUserID."</ewo_result>");
				}
			}
		}
	}
}
else if (isset($_GET['delsavedtoken'])) //Delete Saved Token
{
	if (isset($_GET['tokenid'])) 
	{
		$mTokenID = $_GET['tokenid'];
		$mPaymentGateWay = $objRestaurant->payment_gateway;
		$mUserName = $objRestaurant->authoriseLoginID; //Payment Gateway LoginID, UserName
		$mPassword = $objRestaurant->transKey; //Payment Gateway password, TRaskey
		if (strtolower(trim($mPaymentGateWay))=="authorisedotnet")
		{
			$mResponse = DeleteAutherizeNetToken($mTokenID, $mUserName, $mPassword);
		}
		else if (strtolower(trim($mPaymentGateWay))=="nmi") 
		{
			$mResponse = DeleteNMIToken($mTokenID, $mUserName, $mPassword);
		}
		else if (strtolower(trim($mPaymentGateWay))=="gge4") //securepay, 
		{
			$mResponse = DeleteGGe4Token($mTokenID, $mUserName, $mPassword);
		}
		echo("<ewo_result>".$mResponse."</ewo_result>");
	}
}

function DeleteAutherizeNetToken($pTokenID, $pUserName, $pPassword)
{
	$mResult = dbAbstract::Execute("SELECT id_2, data_2, data_3 FROM general_detail WHERE id=".$pTokenID);
	if (dbAbstract::returnRowsCount($mResult)>0)
	{
		$mRow = dbAbstract::returnObject($mResult);
		$mPaymentProfileID = $mRow->data_2; //Token
		$mUserID = $mRow->id_2;
		$mDefaultCard = $mRow->data_3;
		
		$mResult = dbAbstract::Execute("SELECT profile_id FROM auth_user_profile WHERE customer_id=".$mUserID);
		if (dbAbstract::returnRowsCount($mResult)>0)
		{
			$mRow = dbAbstract::returnObject($mResult);
			$mCustomerProfileID = $mRow->profile_id;
			
			
			
			$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<deleteCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
			"<merchantAuthentication>".
				"<name>".$pUserName."</name>".
				"<transactionKey>".$pPassword."</transactionKey>".
			"</merchantAuthentication>".
			"<customerProfileId>".$mCustomerProfileID."</customerProfileId>".
			"<customerPaymentProfileId>".$mPaymentProfileID."</customerPaymentProfileId>".
			"</deleteCustomerPaymentProfileRequest>";
			$response = send_xml_request($content);
			$parsedresponse = parse_api_response($response);
			if ($parsedresponse->messages->resultCode=="Ok") 
			{
				if ($mDefaultCard==1)
				{
					$mResult = dbAbstract::Execute("SELECT id FROM general_detail WHERE id_2=".$mUserID." AND data_2<>'".$mPaymentProfileID."' LIMIT 1");
					if (dbAbstract::returnRowsCount($mResult)>0)
					{
						$mRow = dbAbstract::returnObject($mResult);
						dbAbstract::Execute("UPDATE general_detail SET data_3=1 WHERE id_2=".$mUserID." AND id=".$mRow->id);
					}
				}
				
				if (dbAbstract::Delete("DELETE FROM general_detail WHERE id=".$pTokenID))
				{
					return "Success";
				}
				else
				{
					return "Error: Unable to delete card from Database. - Auth.Net";
				}
	
			}
			else
			{
				return "Error: Issue in Authorize.Net API response - Auth.Net.";
			}
		}
		else
		{
			return "Error: Customer profile not found. - Auth.Net";
		}
	}
	else
	{
		return "Error: Payment profile(Token) not found. - Auth.Net";
	}
}

function DeleteNMIToken($pTokenID, $pUserName, $pPassword)
{
	$mResult = dbAbstract::Execute("SELECT id_2, data_2, data_3 FROM general_detail WHERE id=".$pTokenID);
	if (dbAbstract::returnRowsCount($mResult)>0)
	{
		$mRow = dbAbstract::returnObject($mResult);
		$mPaymentProfileID = $mRow->data_2; //Token
		$mUserID = $mRow->id_2;
		$mDefaultCard = $mRow->data_3;
		
		
					
		$gw = new gwapi;
		$gw->setLogin($pUserName, $pPassword);
		$response = $gw->doDelete($mCardToken);
		if ($response==APPROVED) 
		{
			if ($mDefaultCard==1)
			{
				$mResult = dbAbstract::Execute("SELECT id FROM general_detail WHERE id_2=".$mUserID." AND data_2<>'".$mPaymentProfileID."' LIMIT 1");
				if (dbAbstract::returnRowsCount($mResult)>0)
				{
					$mRow = dbAbstract::returnObject($mResult);
					dbAbstract::Update("UPDATE general_detail SET data_3=1 WHERE id_2=".$mUserID." AND id=".$mRow->id);
				}
			}
			
			if (dbAbstract::Delete("DELETE FROM general_detail WHERE id=".$pTokenID))
			{
				return "Success";
			}
			else
			{
				return "Error: Unable to delete card from Database. - NMI";
			}

		}
		else
		{
			return "Error: Issue in NMI API response. - NMI";
		}
	}
	else
	{
		return "Error: Payment profile(Token) not found. - NMI";
	}
}

function DeleteGGe4Token($pTokenID, $pUserName, $pPassword) //Have to implement TransArmor (Token) Deletion after getting reply from GGe4 support
{
	$mResult = dbAbstract::Execute("SELECT id_2, data_2, data_3 FROM general_detail WHERE id=".$pTokenID);
	if (dbAbstract::returnRowsCount($mResult)>0)
	{
		$mRow = dbAbstract::returnObject($mResult);
		$mPaymentProfileID = $mRow->data_2; //Token
		$mUserID = $mRow->id_2;
		$mDefaultCard = $mRow->data_3;
		
		$mCustomerProfileID = $mRow->profile_id;

		if ($mDefaultCard==1)
		{
			$mResult = dbAbstract::Execute("SELECT id FROM general_detail WHERE id_2=".$mUserID." AND data_2<>'".$mPaymentProfileID."' LIMIT 1");
			if (dbAbstract::returnRowsCount($mResult)>0)
			{
				$mRow = dbAbstract::returnObject($mResult);
				dbAbstract::Update("UPDATE general_detail SET data_3=1 WHERE id_2=".$mUserID." AND id=".$mRow->id);
			}
		}
		
		if (dbAbstract::Delete("DELETE FROM general_detail WHERE id=".$pTokenID))
		{
			return "Success";
		}
		else
		{
			return "Error: Unable to delete card from Database. - GGe4";
		}
	}
	else
	{
		return "Error: Payment profile(Token) not found. - GGe4";
	}
}
?>