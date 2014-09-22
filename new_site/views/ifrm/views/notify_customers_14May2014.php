<?php
	$objMail->sendTo($confimration_email,"Order Confirmation",$loggedinuser->cust_email);
	if ( $objRestaurant->order_destination == "fax" ) 
	{
		$fh = fopen($file_name, 'rb');
		$fc = fread($fh, filesize($file_name));
		fclose($fh);
		$raw = base64_encode($fc);
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
		
		if(strlen($objRestaurant->fax) == 10)
		{
			$objRestaurant->fax = '1'.$objRestaurant->fax;
		}
		
		$objMetroFax->sendfax($cart->order_id,$raw,$objRestaurant->fax,$objRestaurant->name);
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
		$function_obj->posttoVCS($cart->order_id,0,123);//fax_status==0 successfullt sent 
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