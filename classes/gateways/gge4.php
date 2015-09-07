<?php
$mXML ="";
extract($_POST);
	
if(!isset($card_token)) //Checking that if the transaction is made from Existing Creditcard Dropdown.
{
	$card_token=0; //It means that transaction is made using a new Creditcard.
}

if ($card_token!=0) //Tokenization is ON and transaction is made from existing credit card.
{
	$mCardName = "";
	if ($card_type=="American Express")
	{
		$mCardName = "American Express";
	}
	else if ($card_type=="Diners Club")
	{
		$mCardName = "Diners";
	}
	else if ($card_type=="Discover Card")
	{
		$mCardName = "Discover";
	}
	else if ($card_type=="Master Card")
	{
		$mCardName = "Mastercard";
	}
	else if (($card_type=="Visa Card") || ($card_type=="VISA"))
	{
		$mCardName = "Visa";
	}
	else
	{
		$mCardName = "Discover";
	}
	
	$x_exp_date1 = "1217"; 
	$mResult = dbAbstract::Execute("SELECT card_expiry FROM general_detail WHERE data_2='".$card_token."' AND id_2=".$loggedinuser->id);
	if (dbAbstract::returnRowsCount($mResult)>0)
	{
		$mRow = dbAbstract::returnObject($mResult);
		if (is_numeric($mRow->card_expiry))
		{
			$x_exp_date1 = $mRow->card_expiry; 
		}
	}
	
	
	$mXML = '<?xml version="1.0" encoding="utf-8" ?>
				<Transaction>
					<ExactID>'.$objRestaurant->authoriseLoginID.'</ExactID>
					<Password>'.$objRestaurant->transKey.'</Password>
					<CardHoldersName>'.$x_first_name.' '.$x_last_name.'</CardHoldersName>
					<TransarmorToken>'.$card_token.'</TransarmorToken>
					<CardType>'.$mCardName.'</CardType>
					<Transaction_Type>00</Transaction_Type>
					<Expiry_Date>'.$x_exp_date1.'</Expiry_Date>
					<DollarAmount>'.$cart_total.'</DollarAmount>
				</Transaction>';
        
        Log::write('GGE4 Post XML - Tokenization ON, Existing Credit Card', $mXML, 'gge4');
}
else //All other cases including Tokenization OFF and Tokenization ON and transaction made from new credit card.
{
	$mXML = '<?xml version="1.0" encoding="utf-8" ?>
				<Transaction>
					<ExactID>'.$objRestaurant->authoriseLoginID.'</ExactID>
					<Password>'.$objRestaurant->transKey.'</Password>
					<CardHoldersName>'.$x_first_name.' '.$x_last_name.'</CardHoldersName>
					<Card_Number>'.$x_card_num.'</Card_Number>
					<Transaction_Type>00</Transaction_Type>
					<Expiry_Date>'.$x_exp_date.'</Expiry_Date>
					<DollarAmount>'.$cart_total.'</DollarAmount>
				</Transaction>';
        
        Log::write('GGE4 Post XML - New Credit Card', $mXML, 'gge4');
}

$ch = curl_init($GGe4URL); //$GGe4URL is defined in includes/config.php
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $mXML);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=UTF-8;','Accept: text/xml' ));

$result = curl_exec($ch);

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
			if ($mTransaction->Transaction_Approved!='false')
			{
				if ($mTransaction->Bank_Resp_Code == '100') 
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
					
					$_POST['transaction_id'] = $mTransaction->Transaction_Tag;
					$_POST['payment_method']=1;
					$_POST['invoice_number']=$mInvoiceNumber;
		
					//Insert record into new table gge4_authorization_num to save the Authorization_Num so that it can be used for refund Process.
					dbAbstract::Insert("INSERT INTO  gge4_authorization_num (UserID, transaction_id, Authorization_Num) VALUES (".$loggedinuser->id.", '".$mTransaction->Transaction_Tag."', '".$mTransaction->Authorization_Num."')");
					
					if ($objRestaurant->tokenization==1) //Tokenization Enabled which means its possible that User may want to save the Token.
					{
						if (isset($tokenization)) //Save Creditcard Token
						{
							if ($tokenization==1)
							{
									$gateway_token=$mTransaction->TransarmorToken;
							}
						}
					}
							 
					$success=1;
                                        Log::write('First Data GGE4 Response '."\n".'Result: Success', print_r($result,true), 'gge4');
				} 
				else 
				{
					$_SESSION['GATEWAY_RESPONSE'] = $mTransaction->Bank_Message;
                                        Log::write('First Data GGE4 Response '."\n".'Result: '.$mTransaction->Bank_Message, print_r($mTransaction->Bank_Message,true), 'gge4');
					if(isset($wp_api)&& $wp_api==true) 
					{
						redirect($SiteUrl . $objRestaurant->url ."/?item=failed&wp_api=failed&tpe=gge4&response_code=".$mTransaction->Bank_Resp_Code);
						exit;
					}
					else if(isset($ifrm)&& $ifrm==true) 
					{
						redirect($SiteUrl . $objRestaurant->url ."/?item=failed&ifrm=failed&tpe=gge4&response_code=".$mTransaction->Bank_Resp_Code);
						exit;
					}
					else 
					{
						redirect($SiteUrl . $objRestaurant->url ."/?item=failed&tpe=gge4&response_code=".$mTransaction->Bank_Resp_Code);
						exit;
					}
				}
			}
			else
			{
				$_SESSION['GATEWAY_RESPONSE'] = $result;
                                Log::write('First Data GGE4 Response '."\n".'Result: '.$result, print_r($result,true), 'gge4');
				if(isset($wp_api)&& $wp_api==true) 
				{
					redirect($SiteUrl . $objRestaurant->url ."/?item=failed&wp_api=failed&tpe=gge4&response_code=gge41");
					exit;
				}
				else if(isset($ifrm)&& $ifrm==true) 
				{
					redirect($SiteUrl . $objRestaurant->url ."/?item=failed&ifrm=failed&tpe=gge4&response_code=gge41");
					exit;
				}
				else 
				{
					redirect($SiteUrl . $objRestaurant->url ."/?item=failed&tpe=gge4&response_code=gge41");
					exit;
				}
			}
		}
		else
		{
			$_SESSION['GATEWAY_RESPONSE'] = $result;
                        Log::write('First Data GGE4 Response '."\n".'Result: '.$result, print_r($result,true), 'gge4');
			if(isset($wp_api)&& $wp_api==true) 
			{
				redirect($SiteUrl . $objRestaurant->url ."/?item=failed&wp_api=failed&tpe=gge4&response_code=gge41");
				exit;
			}
			else if(isset($ifrm)&& $ifrm==true) 
			{
				redirect($SiteUrl . $objRestaurant->url ."/?item=failed&ifrm=failed&tpe=gge4&response_code=gge41");
				exit;
			}
			else 
			{
				redirect($SiteUrl . $objRestaurant->url ."/?item=failed&tpe=gge4&response_code=gge41");
				exit;
			}
		}
	} 
	else 
	{
		$_SESSION['GATEWAY_RESPONSE'] = "First Data GGe4: Connection failure.";
                Log::write('First Data GGE4 Response '."\n".'Result: Failure', 'First Data GGe4: Connection failure.', 'gge4');
		if(isset($wp_api)&& $wp_api==true) 
		{
			redirect($SiteUrl . $objRestaurant->url ."/?item=failed&wp_api=failed&tpe=gge4&response_code=gge42");
			exit;
		}
		else if(isset($ifrm)&& $ifrm==true) 
		{
			redirect($SiteUrl . $objRestaurant->url ."/?item=failed&ifrm=failed&tpe=gge4&response_code=gge42");
			exit;
		}
		else 
		{
			redirect($SiteUrl . $objRestaurant->url ."/?item=failed&tpe=gge4&response_code=gge42");
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
		if ($mTransaction->Transaction_Approved!='false')
		{
			if ($mTransaction->Bank_Resp_Code == '100') 
			{
				$_POST['payment_method']=1;
				$_POST['invoice_number']=date('YmdHis');
				$_POST['transaction_id']=$mTransaction->Transaction_Tag; 
                                Log::write('First Data GGE4 Response - Rapid Re-ordering '."\n".'Result: Success', print_r($result,true), 'gge4');
				$success=1;
			}
			else
			{
                                Log::write('First Data GGE4 Response - Rapid Re-ordering '."\n".'Result: Failed', print_r($result,true), 'gge4');
				$success=0;
			}
		}
		else
		{
			$success=0;
		}
	}
}
?>