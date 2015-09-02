<?php
if (isset($_GET['checkfbid'])) //To Check that if a give facebook id is associated with any EWO account
{
	if (isset($_GET['fbid']))
	{
		$mRow = $loggedinuser->selectUserIDByFBID_RestaurantID($_GET['fbid'], $objRestaurant->id);
		if ($mRow==0) //No EWO account associated
		{
			if (isset($_GET['email']))
			{
				if (trim($_GET['email'])!="")
				{
					$mRow = $loggedinuser->selectUserIDByEmail_RestaurantID(urldecode($_GET['email']), $objRestaurant->id);
					if ($mRow==0) //No EWO account associated
					{
						echo("<ewo_result>0</ewo_result>");
					}
					else
					{							
						$mUserID = $mRow->UserID;
						
						$mEmail = $mRow->Email;
						if ($mUserID==0) //Error, because UserID cannot be 0 if there is a row
						{
							echo("<ewo_result>-1</ewo_result>");
						}
						else if ($mUserID>0) //EWO account associated
						{
							$loggedinuser->updateCustomerFacebookID($mUserID, $_GET['fbid']);
							$mUser = $loggedinuser->ssoUserLogin($mEmail, $objRestaurant->id);
							if(is_null($mUser))
							{
								echo("<ewo_result>-2</ewo_result>");
							}
							else
							{
								$loggedinuser->destroyUserSession();
								$loggedinuser = $mUser;
						
								require($site_root_path . "includes/abandoned_cart_config.php");
						
								if ($objRestaurant->useValutec == 1) 
								{
									if ($loggedinuser->valuetec_card_number > 0) 
									{
										$Balance = valutecCardBalance($loggedinuser->valuetec_card_number);
										$loggedinuser->valuetec_points = $Balance['PointBalance'];
										$loggedinuser->valuetec_reward = $Balance['Balance'];
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
						
								$loggedinuser->saveToSession();
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
			
			$mEmail = $mRow->Email;
			if ($mUserID==0) //Error, because UserID cannot be 0 if there is a row
			{
				echo("<ewo_result>-1</ewo_result>");
			}
			else if ($mUserID>0) //EWO account associated
			{
				$mUser = $loggedinuser->ssoUserLogin($mEmail, $objRestaurant->id);
				if(is_null($mUser))
				{
					echo("<ewo_result>-2</ewo_result>");
				}
				else
				{
					$loggedinuser->destroyUserSession();
					$loggedinuser = $mUser;
			
					require($mobile_root_path . "../new_site/includes/abandoned_cart_config.php");
			
					if ($objRestaurant->useValutec == 1) 
					{
						if ($loggedinuser->valuetec_card_number > 0) 
						{
							$Balance = valutecCardBalance($loggedinuser->valuetec_card_number);
							$loggedinuser->valuetec_points = $Balance['PointBalance'];
							$loggedinuser->valuetec_reward = $Balance['Balance'];
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
			
					$loggedinuser->saveToSession();
					echo("<ewo_result>".$mUserID."</ewo_result>");
				}
			}
		}
	}
}
?>