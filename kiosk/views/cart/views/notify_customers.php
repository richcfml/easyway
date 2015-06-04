<?php
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
        $_SESSION["confimration_email"] = $confimration_email;
        
?>