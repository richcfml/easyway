<?php
class cydne 
{
	public $APIkey;
	public $did;
	public $url;
	public $restaurant_name;
	 
 	public $reference, $matchedmessageid;
	public $sms;
	public $trace;
	public $phonenumber;
	public $user;
	const SYSTEM_NEW_CUSTOMER=1;
	const CUSTOMER_SMS=2;
	const SYSTEM_RESPONSE=3;
 
	public function sendSMS($to, $message, $reference='', $matchedmessageid='', $type=1)
	{
		global $objRestaurant, $CdynePostBackUrl, $gCdynePostURL;
	 	$objfun=new clsFunctions();
		$message= $objfun->_esc_xmlchar(str_replace("!","",str_replace("'","",$message)));
		$to= $objfun->esc_special($to);
		$json='{
			   		"LicenseKey":"'. $this->APIkey .'",
			   		"SMSRequests":[{
			  			"AssignedDID":"'. $this->did .'",
			   			"Message":"'. $message .'",
			  			"PhoneNumbers":["'.$to.'"],
			    		"ReferenceID":"'. $reference .'",
			    		"StatusPostBackURL":"'.$CdynePostBackUrl.$this->url .'/?item=cdyne"
					}]
				}';

		//Method
                $url= $gCdynePostURL;
		$post_array = json_decode($json);
        Log::write('CDYNE Post Array - Send SMS', print_r($post_array,true), 'cdyne');
		$cURL = curl_init();
		 
		curl_setopt($cURL, CURLOPT_URL,$url);
		curl_setopt($cURL, CURLOPT_POST,true);
		curl_setopt($cURL, CURLOPT_POSTFIELDS,$json);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);  
                curl_setopt($cURL, CURLOPT_SSLVERSION, "CURL_SSLVERSION_TLSv1_2"); //6 = CURL_SSLVERSION_TLSv1_2
		curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json'));
		//If you desire your results in xml format, use the following line for your httpheaders and comment out the httpheaders code line above.
		$result = curl_exec($cURL);
		 
		$result = json_decode($result);
        Log::write('CDYNE Response Array - Send SMS', print_r($result, true), 'cdyne');
		curl_close($cURL);
		
		if(!empty($objRestaurant) && !empty($objRestaurant->id) && $objRestaurant->id > 0) 
		{
			$restaurant_id=$objRestaurant->id;
			 // report chargify about the messages sent
			$objChargify = new chargifyMeteredUsage();
			$objChargify->sendMeteredUsageToChargify($restaurant_id, 1, "rapid_reorder");
		}
 
		$this->saveLog($to, $this->did,'',$matchedmessageid,$reference,$message,$type);
	}
	 
	public function SMSReceived()
	{
		global $objRestaurant;
		$this->trace=new SMSTrace(); 
	    $this->trace->cydne=$this;
		$this->trace->parseSMS();
		if(!empty($objRestaurant) && !empty($objRestaurant->id) && $objRestaurant->id > 0) 
		{
			$restaurant_id=$objRestaurant->id;
		   	// report chargify about the messages sent
		   	$objChargify = new chargifyMeteredUsage();
		   	$objChargify->sendMeteredUsageToChargify($restaurant_id, 1, "rapid_reorder");
	  	}
   	}
  
  
	public function saveLog($ToPhoneNumber, $FromPhoneNumber, $MessageID, $MatchedMessageId, $ReferenceId, $Message, $type) 
	{	
		$Message=dbAbstract::returnRealEscapedString($Message);
		$qry="insert into cydne_log(ToPhoneNumber,FromPhoneNumber,MessageID,MatchedMessageId,ReferenceId,Message,SMSType,LogTime)";
		$qry .=" values ( '$ToPhoneNumber' ,'$FromPhoneNumber','$MessageID','$MatchedMessageId','$ReferenceId','$Message',$type,'". time() ."')";
	 
		dbAbstract::Insert($qry);	
	}
}
	
class SMSTrace  
{
	public $id, $phone_number, $reply_phone_number, $user_id, $trace_date, $trace_status, $step, $sms, $easyway_id, $resaurant_id, $user, $arrOrder, $ntries, $order_title, $token_id;
	public $cydne;
	const TRACEOPEN=1;//	1=thread open, 2=thread closed
	const TRACECLOSE=2;
	const APPROVED=1;
	const REJECTED=2;
	const INVALIDTITLE=3;
	const NOTOKEN=4;
	const INVALIDCALLER=5;
	const SESSIONCLOSED=6;
	const USERCANCEL=7;
	
	public function parseSMS() 
	{
		global $cart, $loggedinuser, $objRestaurant, $function_obj, $objMail, $objGO3;
		global $easyway_id, $GGe4URL, $AuthorizeDotNetSandBox, $AuthorizeDotNetTestRequest, $SureGateURL, $SureGateTestFlag;
                $cdynePNRef="";
		$this->reply_phone_number=$this->cydne->phonenumber;
		$this->phone_number=$this->cydne->phonenumber;
		
		$this->sms=$this->cydne->sms;
		$this->resaurant_id=$objRestaurant->id;
		
		if(substr($this->phone_number , 0,1 )=="+" || substr($this->phone_number , 0,1)=="1")
		{
			$this->phone_number=trim($this->phone_number,"+");			
			$this->phone_number=trim($this->phone_number,"1");			
		}
		
		$mUserID = 0;
		$mRestaurantID = $this->resaurant_id;
		$mRestFlag = false;
		
		if ($objRestaurant->status==0)
		{
			$this->cydne->sendSMS($this->reply_phone_number,'Restaurant is not available at the moment! ' , '', '', cydne::SYSTEM_RESPONSE);
			$this->Close();
			$mRestFlag = true;
		}
		
		if ($objRestaurant->isOpenHour==0)
		{
			$this->cydne->sendSMS($this->reply_phone_number,'Restaurant is closed at the moment! ' , '', '', cydne::SYSTEM_RESPONSE);
			$this->Close();
			$mRestFlag = true;
		}
		
		
		if (!($mRestFlag))
		{
			$mResult = dbAbstract::Execute("SELECT id FROM customer_registration WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(cust_phone1,'(',''),')',''),'-',''),'',''),' ','')='". trim($this->phone_number)."' AND resturant_id=". $this->resaurant_id  ." AND password!=''");
			if (dbAbstract::returnRowsCount($mResult)>0)
			{
				$mRow = dbAbstract::returnObject($mResult);
				$mUserID = $mRow->id;
				if (is_numeric($mUserID))
				{
					$mSMSTmp = strtolower(trim($this->sms));
					$qry=dbAbstract::Execute("SELECT * FROM repid_reordering_trace WHERE phone_number=".$this->phone_number." AND trace_status=".SMSTrace::TRACEOPEN." AND user_id=".$mUserID);
					$result=dbAbstract::returnObject($qry);
						
					if(!isset($result->id)) 
					{
						$this->Open();
					}
					else if (($mSMSTmp!="yes") && ($mSMSTmp!="no") && ($mSMSTmp!="pickup") && ($mSMSTmp!="deliver") && ($mSMSTmp!=strtolower(trim($result->order_title))))
					{
						dbAbstract::Update("UPDATE repid_reordering_trace SET trace_status=".SMSTrace::TRACECLOSE." WHERE phone_number=".$this->phone_number." AND trace_status=".SMSTrace::TRACEOPEN." AND user_id=".$mUserID." AND LOWER(TRIM(order_title))!='".strtolower(trim($mSMSTmp))."'");
						$this->Open();
					}
					else
					{
						$this->id=$result->id;
						$this->phone_number=$result->phone_number;
						$this->user_id=$result->user_id;
						$this->step=$result->step;
						$this->order_title=$result->order_title;
						$this->easyway_id=$result->easyway_id;
						$this->ntries=$result->ntries;
						$this->token_id=$result->token_id;
						
						$mToken = $loggedinuser->selectCCTokenDetailsByUserID($this->user_id, $this->token_id);
						if($mToken->data_type==AMEX)
						{
							$card_type="American Express";
						}
						else if($mToken->data_type==VISA)
						{
							$card_type="VISA";
						}
						else if($mToken->data_type==MASTER)
						{
							$card_type="MasterCard";
						}
						else if($mToken->data_type==DISCOVER)
						{
							$card_type="Discover";
						}
						else
						{
							$card_type="Discover";
						}
						$x_exp_date1 = $mToken->card_expiry; 
						if (strtoupper($this->sms)=="YES")
						{
							$cart->clear();
							$loggedinuser=$loggedinuser->loginByUserId($this->user_id);
							$loggedinuser->delivery_address_choice = 1;
							
							$mRes = dbAbstract::Execute("SELECT * FROM customer_registration WHERE id = ".$this->user_id);
							$mRow = dbAbstract::returnObject($mRes);
						
							$loggedinuser->street1 =trim($mRow->cust_odr_address);
							$loggedinuser->street2 ='';
							$loggedinuser->cust_ord_city =trim($mRow->cust_ord_city);
							$loggedinuser->cust_ord_state =trim($mRow->cust_ord_state);
							$loggedinuser->cust_ord_zip =trim($mRow->cust_ord_zip);
							$loggedinuser->delivery_address=$loggedinuser->street1 .", ". $loggedinuser->cust_ord_city .", ". $loggedinuser->cust_ord_state;
							
							$arrfood= $loggedinuser->getFavoritesById($this->easyway_id);
							$this->arrOrder=$arrfood[0];
							$cart->addfavorites($this->arrOrder->food);
							$cart->setdelivery_type($this->arrOrder->order_receiving_method);
							$_POST['serving_date']=date("m/d/Y", time());
							$_POST['vipcard']=0;
							$_POST['serving_time']=0;
							$_POST['payment_method']=1;
							$card_token=$this->token_id;
							$cart_total=$cart->grand_total(0);
							$repid_payment=1;
							$x_card_num='';
							$x_exp_date='';
							$special_notes ='';
							$vip_discount =0;
							if($this->step==2)
							{
								$this->cydne->sendSMS($this->reply_phone_number,' We are procssing your card hold one!!! ' ,$this->order_title,'',cydne::SYSTEM_RESPONSE);
								$this->Close();
							}
							else
							{
								$DeliveryInstructionsFO = "";
								if ($this->arrOrder->order_receiving_method==1) //Delivery
								{

									if ($objRestaurant->delivery_offer==1) //Delivery Allowed
									{
										$cart->driver_tip = $this->arrOrder->driver_tip;
										$success=0;
								
										if( $objRestaurant->payment_gateway=="authoriseDotNet")  
										{
											$objRestaurant->payment_gateway="AuthorizeNet";
										}
										
										$x_first_name=$loggedinuser->cust_your_name;
										$x_last_name=$loggedinuser->LastName;
										if ($cart->OrderDeliveryMethod()=="Delivery")
										{
											$cart->driver_tip = $this->arrOrder->driver_tip;
										}
										
										require_once 'classes/gateways/'.$objRestaurant->payment_gateway.'.php';
										
										if($success==1) 
										{
											$_POST['x_card_num']='';
											$platform_used = 3;
											$DeliveryInstructionsFO = $this->arrOrder->DeliveryInstructions;
											if (is_null($DeliveryInstructionsFO))
											{
												$DeliveryInstructionsFO = "";
											}
											
											include "new_site/views/cart/submit_order.php";
                                                                                        $mSQL = "UPDATE ordertbl SET PNRef='".$cdynePNRef."' WHERE OrderID=".$cart->order_id;
                                                                                        dbAbstract::Update($mSQL);
											$this->cydne->sendSMS($this->reply_phone_number,$this->order_title .' was successfully ordered, enjoy! ' ,$this->order_title,'',cydne::SYSTEM_RESPONSE);					 $this->updatestatus(SMSTrace::APPROVED);					 
										}
										else
										{
											$this->cydne->sendSMS($this->reply_phone_number,'payment method declined.  Please order online ' ,$this->order_title,'',cydne::SYSTEM_RESPONSE);							$this->updatestatus(SMSTrace::REJECTED);		 
										}
										$this->Close();	
									}
									else ////Delivery Not Allowed
									{
										$mSMS = "We offer Pickup option only. Reply PICKUP to change to pickup.";
										$this->cydne->sendSMS($this->reply_phone_number, $mSMS ,$this->order_title,'',cydne::SYSTEM_RESPONSE);
									}
								}
								else //Pickup
								{
									$success=0;
								
									if( $objRestaurant->payment_gateway=="authoriseDotNet")  
									{
										$objRestaurant->payment_gateway="AuthorizeNet";
									}
									
									$x_first_name=$loggedinuser->cust_your_name;
									$x_last_name=$loggedinuser->LastName;
									if ($cart->OrderDeliveryMethod()=="Delivery")
									{
										$cart->driver_tip = $this->arrOrder->driver_tip;
									}
									
									require_once 'classes/gateways/'.$objRestaurant->payment_gateway.'.php';
									
									if($success==1) 
									{
										$_POST['x_card_num']='';
										$platform_used = 3;
										$DeliveryInstructionsFO = $this->arrOrder->DeliveryInstructions;
										if (is_null($DeliveryInstructionsFO))
										{
											$DeliveryInstructionsFO = "";
										}
										include "new_site/views/cart/submit_order.php";
                                                                                $mSQL = "UPDATE ordertbl SET PNRef='".$cdynePNRef."' WHERE OrderID=".$cart->order_id;
                                                                                dbAbstract::Update($mSQL);
										$this->cydne->sendSMS($this->reply_phone_number,$this->order_title .' was successfully ordered, enjoy! ' ,$this->order_title,'',cydne::SYSTEM_RESPONSE);					 $this->updatestatus(SMSTrace::APPROVED);					 
									}
									else
									{
										$this->cydne->sendSMS($this->reply_phone_number,'payment method declined.  Please order online ' ,$this->order_title,'',cydne::SYSTEM_RESPONSE);							$this->updatestatus(SMSTrace::REJECTED);		 
									}
									$this->Close();
								}
							}	
						}
						else if (strtoupper($this->sms)=="PICKUP")
						{
							$loggedinuser->updateFavoritesDeliveryMethod($this->easyway_id, 2);
							$cart->clear();
							$loggedinuser=$loggedinuser->loginByUserId($this->user_id);
							$loggedinuser->delivery_address_choice = 1;
							
							$mRes = dbAbstract::Execute("SELECT * FROM customer_registration WHERE id = ".$this->user_id);
							$mRow = dbAbstract::returnObject($mRes);
						
							$loggedinuser->street1 =trim($mRow->cust_odr_address);
							$loggedinuser->street2 ='';
							$loggedinuser->cust_ord_city =trim($mRow->cust_ord_city);
							$loggedinuser->cust_ord_state =trim($mRow->cust_ord_state);
							$loggedinuser->cust_ord_zip =trim($mRow->cust_ord_zip);
							$loggedinuser->delivery_address=$loggedinuser->street1 .", ". $loggedinuser->cust_ord_city .", ". $loggedinuser->cust_ord_state;
							
							$arrfood= $loggedinuser->getFavoritesById($this->easyway_id);
							$this->arrOrder=$arrfood[0];
							$cart->addfavorites($this->arrOrder->food);
							//$cart->driver_tip = $this->arrOrder->driver_tip;
							$cart->setdelivery_type(2);
							
							if ($objRestaurant->delivery_offer==1)
							{
								$mSMS="Pickup" .' '.$this->order_title .' $'. $cart->grand_total(0) .' pay with '.$card_type . ' - '.$mToken->data_1 . ' reply YES to confirm, or DELIVER to change to delivery.';
							}
							else
							{
								$mSMS="Pickup" .' '.$this->order_title .' $'. $cart->grand_total(0) .' pay with '.$card_type . ' - '.$mToken->data_1 . ' reply YES to confirm.';
							}
							$this->cydne->sendSMS($this->reply_phone_number, $mSMS ,$this->order_title,'',cydne::SYSTEM_RESPONSE);
						}
						else if (strtoupper($this->sms)=="DELIVER")
						{
							if ($objRestaurant->delivery_offer==1)
							{
								$cart->clear();
								$loggedinuser=$loggedinuser->loginByUserId($this->user_id);
								$loggedinuser->delivery_address_choice = 1;
								
								$mRes = dbAbstract::Execute("SELECT * FROM customer_registration WHERE id = ".$this->user_id);
								$mRow = dbAbstract::returnObject($mRes);
							
								$loggedinuser->street1 =trim($mRow->cust_odr_address);
								$loggedinuser->street2 ='';
								$loggedinuser->cust_ord_city =trim($mRow->cust_ord_city);
								$loggedinuser->cust_ord_state =trim($mRow->cust_ord_state);
								$loggedinuser->cust_ord_zip =trim($mRow->cust_ord_zip);
								$loggedinuser->delivery_address=$loggedinuser->street1 .", ". $loggedinuser->cust_ord_city .", ". $loggedinuser->cust_ord_state;
								
								$arrfood= $loggedinuser->getFavoritesById($this->easyway_id);
								$this->arrOrder=$arrfood[0];
								$cart->addfavorites($this->arrOrder->food);
								$cart->driver_tip = $this->arrOrder->driver_tip;
								
								if ($cart->grand_total(0)<$objRestaurant->order_minimum)
								{
									$mSMS="We are having a minimum limit of $".$objRestaurant->order_minimum." for Delivery orders. Please choose any other favorite order.";
									$this->cydne->sendSMS($this->reply_phone_number, $mSMS ,$this->order_title,'',cydne::SYSTEM_RESPONSE);
									$this->Close();	
								}
								else
								{
									$loggedinuser->updateFavoritesDeliveryMethod($this->easyway_id, 1);
									$cart->setdelivery_type(1);
									$mSMS="Delivery" .' '.$this->order_title .' $'. $cart->grand_total(0) .' pay with '.$card_type . ' - '.$mToken->data_1 . ' reply YES to confirm, or PICKUP to change to pickup.';
									$this->cydne->sendSMS($this->reply_phone_number, $mSMS ,$this->order_title,'',cydne::SYSTEM_RESPONSE);
								}
							}
							else
							{
								$cart->clear();
								$loggedinuser=$loggedinuser->loginByUserId($this->user_id);
								$loggedinuser->delivery_address_choice = 1;
								
								$mRes = dbAbstract::Execute("SELECT * FROM customer_registration WHERE id = ".$this->user_id);
								$mRow = dbAbstract::returnObject($mRes);
							
								$loggedinuser->street1 =trim($mRow->cust_odr_address);
								$loggedinuser->street2 ='';
								$loggedinuser->cust_ord_city =trim($mRow->cust_ord_city);
								$loggedinuser->cust_ord_state =trim($mRow->cust_ord_state);
								$loggedinuser->cust_ord_zip =trim($mRow->cust_ord_zip);
								$loggedinuser->delivery_address=$loggedinuser->street1 .", ". $loggedinuser->cust_ord_city .", ". $loggedinuser->cust_ord_state;
							
								$arrfood= $loggedinuser->getFavoritesById($this->easyway_id);
								$this->arrOrder=$arrfood[0];
								$cart->addfavorites($this->arrOrder->food);
								$cart->driver_tip = $this->arrOrder->driver_tip;
								
								$mSMS = "We offer Pickup option only. Pickup ".$this->order_title." $". $cart->grand_total(0)." pay with ".$card_type." - ".$mToken->data_1." reply YES to confirm.";
								$this->cydne->sendSMS($this->reply_phone_number, $mSMS ,$this->order_title,'',cydne::SYSTEM_RESPONSE);
							}
						}
						else if (strtoupper($this->sms)=="NO")
						{
							$this->Close();	
							$this->cydne->sendSMS($this->reply_phone_number,' please send your favorites food title again, Session CLOSED' ,$this->order_title .'User CACNCEL','',cydne::SYSTEM_RESPONSE);
							$this->updatestatus(SMSTrace::USERCANCEL);
						}
						else 
						{
							$this->ntries= $this->ntries+ 1;
							if($this->ntries==30)
							{
								$this->Close();	
								$this->cydne->sendSMS($this->reply_phone_number,' please send your favorites food title again, Session CLOSED' ,$this->order_title.' Max reply level ' ,'',cydne::SYSTEM_RESPONSE);
								$this->updatestatus(SMSTrace::SESSIONCLOSED); 
							}
							else
							{
								$this->updateTries();
								$this->cydne->sendSMS($this->reply_phone_number,' please reply with YES or NO to confirm order ' ,$this->order_title,'',cydne::SYSTEM_RESPONSE);
							}
						}
					}
				}
				else
				{
					dbAbstract::Insert("INSERT INTO tblDebug(Step, Value1, Value2) VALUES (1, 'Cdyne_User_ID_Non_Numeric', 'Phone #: .".trim($this->phone_number).", Restaurant ID: ".$this->resaurant_id.", User ID: ".$mUserID."')");
					exit;
				}
			}
			else
			{
				dbAbstract::Insert("INSERT INTO tblDebug(Step, Value1, Value2) VALUES (1, 'Cdyne_No_User_ID', 'Phone #: .".trim($this->phone_number).", Restaurant ID: ".$this->resaurant_id."')");
				exit;
			}
		}
	}
			
	public function Open() 
	{
		if($this->VerifyUser()) 
		{
			if($this->VerifyOrder())
			{
				global $cart;
				global $loggedinuser;
				global $objRestaurant;
				
				$cart->clear();
				 
				$cart->addfavorites($this->arrOrder->food);
				$cart->setdelivery_type($this->arrOrder->order_receiving_method);
				
				$token=$this->user->getUserDefaultCCToken();
				if(isset($token->data_2))
				{
					$mTraceID = dbAbstract::Insert("INSERT INTO repid_reordering_trace(phone_number,user_id,trace_date,trace_status,step,order_title,easyway_id,ntries ,token_id) VALUES(". $this->phone_number .",". $this->user->id .",". time() .",". SMSTrace::TRACEOPEN .",1,'". addslashes($this->sms) ."',".$this->easyway_id .",0,'". $token->data_2 ."')", 0, 2);
					if($token->data_type==AMEX)
					{
						$card_type="American Express";
					}
					else  if($token->data_type==VISA)
					{
						$card_type="VISA";
					}
					else  if($token->data_type==MASTER)
					{
						$card_type="MasterCard";
					}
					else  if($token->data_type==DISCOVER)
					{
						$card_type="Discover";
					}
					

					
					$mPaymentMethod = $loggedinuser->selectPaymentMethodByFavoriteID($this->easyway_id);
					
					if ($mPaymentMethod==1) //Delivery
					{
						if ($objRestaurant->delivery_offer==0)
						{
							$sms = "We offer Pickup option only. Reply PICKUP to change to pickup.";
						}						
						else
						{
							$cart->driver_tip = $this->arrOrder->driver_tip;
								
							if ($cart->grand_total(0)<$objRestaurant->order_minimum)
							{
								$sms="We are having a minimum limit of $".$objRestaurant->order_minimum." for Delivery orders. Please choose any other favorite order.";
								dbAbstract::Update("UPDATE repid_reordering_trace SET trace_status=".SMSTrace::TRACECLOSE ." WHERE id=".$mTraceID);
							}
							else
							{
								$sms=$cart->OrderDeliveryMethod() .' '.$this->sms .' $'. $cart->grand_total(0) .' pay with '.$card_type . ' - '.$token->data_1 . ' reply YES to confirm, or PICKUP to change to pickup.';
							}						
						}
					}
					else if ($mPaymentMethod==2) //Pickup
					{
						$sms=$cart->OrderDeliveryMethod() .' '.$this->sms .' $'. $cart->grand_total(0) .' pay with '.$card_type . ' - '.$token->data_1 . ' reply YES to confirm, or DELIVER to change to delivery.';
					}
				 
					$this->cydne->sendSMS($this->reply_phone_number,$sms ,$this->order_title,'',cydne::SYSTEM_RESPONSE);
							
					return true;
				}
				else
				{ 
					$this->cydne->sendSMS($this->reply_phone_number,'No gateway token found please register at easywayordering repid ordering service' ,$this->order_title,'',cydne::SYSTEM_RESPONSE);
					$this->addtrace(SMSTrace::NOTOKEN);
					return false;
				}//NO TOKEN
			} 
		}
		else 
		{
			$this->cydne->sendSMS($this->reply_phone_number,"Unknown Phone number, please make sure you have favorites added at ".$this->cydne->restaurant_name, $this->order_title,'',cydne::SYSTEM_RESPONSE);
			$this->addtrace(SMSTrace::INVALIDCALLER);
		}
		return false;
	}
	
	public function Close() 
	{
		dbAbstract::Update("UPDATE repid_reordering_trace SET trace_status=". SMSTrace::TRACECLOSE  ." WHERE id=". $this->id ."");
	}
	
	public function updateTries() 
	{
		dbAbstract::Update("UPDATE repid_reordering_trace SET ntries=". $this->ntries  ." WHERE id=". $this->id ."");
	}
	
	public function moveToCCProcessing() 
	{
		dbAbstract::Update("UPDATE repid_reordering_trace SET step=2 WHERE id=". $this->id ."");
	}
	
	public function updatestatus($status) 
	{
		dbAbstract::Update("UPDATE repid_reordering_trace SET log_status=$status WHERE id=". $this->id ."");
	}		 
	
	public function addtrace($status) 
	{
		$userid=0;
		if(isset($this->user->id))
		{
			$userid=$this->user->id;
		}
				  
		dbAbstract::Insert("INSERT INTO repid_reordering_trace(phone_number,user_id,trace_date,trace_status,step,order_title,easyway_id,ntries ,token_id,log_status) VALUES(
					". $this->phone_number .",". $userid .",". time() .",". SMSTrace::TRACECLOSE .",1,'". addslashes($this->sms) ."',0,0,'',$status)");							 
	}

	public function VerifyOrder() 
	{
		$arrfood=$this->user->getFavoritesByTitle($this->sms);
		if(count($arrfood) ==0)
		{
			$this->user->getFavoritesTitles();
			if(count($this->user->arrFavorites)==0)
			{
				$this->cydne->sendSMS($this->reply_phone_number,"please make sure you have favorites added at ".$this->cydne->restaurant_name , $this->order_title,'',cydne::SYSTEM_RESPONSE);
			}
			else
			{
				$favories=implode(" or ",$this->user->arrFavorites);
 				$this->cydne->sendSMS($this->reply_phone_number,"Unknown favorite.  Choose: ". $favories , $this->order_title,'',cydne::SYSTEM_RESPONSE);
			}
			$this->addtrace(SMSTrace::INVALIDTITLE);		
			return false;
		}
		else 
		{
			$this->easyway_id=$arrfood[0]->id;
			$this->arrOrder=$arrfood[0];
			return true;
		}		 
	}
	
	public function VerifyUser() 
	{
		global $loggedinuser;
		$this->user= $loggedinuser->getUserDetailByPhone($this->phone_number,$this->resaurant_id);
		if(!isset($this->user->id))
		{
			return false;
		}
  		return is_numeric($this->user->id);
	}
}
?>