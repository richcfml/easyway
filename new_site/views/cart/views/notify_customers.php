<?php
	$objMail->sendTo($confimration_email,"Order Confirmation",$loggedinuser->cust_email);
	if ( $objRestaurant->order_destination == "fax" ) 
	{
		if($objRestaurant->region == "0")
		{
			if(strlen($objRestaurant->fax) == 11)
			{
				$objRestaurant->fax = preg_replace('/^0/','01144',$objRestaurant->fax);
			}
			else 
			{
				$objRestaurant->fax = '01144'.$objRestaurant->fax;
			}
		}
		
		include_once("includes/phaxio.php"); 
		
		$mFax=new EWOphaxio();
		$mFax->sendfax($cart->order_id, $objRestaurant->fax);
		unset($mFax);
	}//FAX METHOD
	elseif ( $objRestaurant->order_destination == "email" )
	{
		if($objRestaurant->rest_order_email_fromat == 'pdf') 
		{ 
			$objMail->clearattachments();
			$objMail->addattachment($file_name);
			$objMail->sendTo('', $objRestaurant->name . ' order '. $cart->order_id .' Email',$objRestaurant->email,false);
		}
		else //ELSE PLAIN TEXT
		{
			$objMail->sendTo($restuarant_email,$objRestaurant->name . ' order '. $orderId ,$objRestaurant->email,true);
		}
		$function_obj->posttoVCS($cart->order_id, 1, 123);
	}
						
	//////////////////////////////if Voice Confirmation status is ON from admin site then below email will be deliverd//////////////////////
	if($objRestaurant->phone_notification == 1) 
	{
		$amount = number_format($cart->grand_total(), 2);		  
		///////////////////////voice confirmation email with txt attachment  ///////////////////
		$objMail->clearattachments();
		$phone_notification_mail_body = $objRestaurant->name." received a ". $cart->delivery_type==cart::Pickup ?  "Pickup": "Delivery" ." order from ".
		$loggedinuser->cust_your_name.' '.$loggedinuser->LastName." in the amount of $".$amount." at ".$serving_date ;
		$objMail->sendTo(addslashes($phone_notification_mail_body), '',$objRestaurant->phone."@call.fax2me.com",false);
	}
			  
	if($objRestaurant->useValutec=="1" && $loggedinuser->valuetec_card_number>0)  
	{
		$vip_discount=$cart->vip_discount;
		if($vip_discount >0) 
		{
			Sale($loggedinuser->valuetec_card_number, $vip_discount);
		}
		else 
		{
			AddValue($loggedinuser->valuetec_card_number, round($objRestaurant->rewardPoints  *  $cart->sub_total, 0) );
		}
				
		$Balance=CardBalance($loggedinuser->valuetec_card_number);
		$loggedinuser->valuetec_points=$Balance['PointBalance'];
		$loggedinuser->valuetec_reward=$Balance['Balance'];
		$loggedinuser->savetosession();			
	}
	else if($objRestaurant->useValutec=="2" && $loggedinuser->valuetec_card_number>0)  
	{
		$vip_discount=$cart->vip_discount;
		if($vip_discount >0) 
		{  
			$objGO3->go3Sale($loggedinuser->valuetec_card_number, $vip_discount, $cart->order_id);
		}
		else 
		{
			$objGO3->go3AddValue($loggedinuser->valuetec_card_number, round($objRestaurant->rewardPoints  *  $cart->sub_total, 0) );
		}
		$rewardPoints = $objGO3->go3RewardPoints($loggedinuser->valuetec_card_number);
		$Balance = $objGO3->go3CardBalance($loggedinuser->valuetec_card_number);
		$loggedinuser->valuetec_points = $rewardPoints;
		$loggedinuser->valuetec_reward = $Balance;
		$loggedinuser->savetosession();
	}
?>