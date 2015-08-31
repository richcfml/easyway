<?php
	if(!isset($repid_payment))
	{
		if(!isset($_POST['serving_date'])) 
		{
			redirect($SiteUrl .$objRestaurant->url ."/?item=cart" );
			exit;
		}
		$total=$cart->grand_total();
		if($cart->isempty()) 
		{ 
			//@mysql_close($mysql_conn);
			redirect($SiteUrl .$objRestaurant->url ."/?item=menu" );
			exit;
		}
	}
	//--------Edited by Asher Ali------------
	// if(!is_numeric($total) || $total<=0) {
	//echo $total<=0; exit;
	//redirect($SiteUrl .$objRestaurant->url ."/?item=cart" );exit;
	////header("location: ". $SiteUrl .$objRestaurant->url ."/?item=cart" );exit;
	//}
	//---------------------------------------------//
	$is_guest = 0;
	if(!is_numeric($loggedinuser->id))
	{
		$loggedinuser->createNewUser();
		$loggedinuser->saveToSession();
		$is_guest = 1;
	}
	
	extract($_POST);
	
	$serving_time=$_POST['serving_time'];
	$serving_date=date("m-d-Y",$_POST['serving_date']);
	$asap=0;
	if($serving_time=='0')
	{
		$asap=1;
		$serving_time=date("H:i");
	}
	
	$serving_date=$serving_date ." ".$serving_time;
	$payment_method=($_POST['payment_method'] == "1" ? "Credit Card" : "Cash");
	$address=$loggedinuser->getUserDeliveryAddress(0) .", ".$loggedinuser->getUserDeliveryZipCode();
	$invoice_number='';
	
	if(isset($_POST['invoice_number']))
	{
		$invoice_number=$_POST['invoice_number'];
	}
	
	$cart->payment_menthod=$_POST['payment_method'];
	
	if(!isset($type)) 
	{
		$type='';
	}
	
	if(!isset($cc)) 
	{
		$cc='';
	}
	
	if(!isset($gateway_token)) 
	{
		$gateway_token='';
	}
	
	if(!isset($transaction_id))
	{
		$transaction_id=0;
	}
	
	if(isset($cart_delivery_charges))
	{
		$objRestaurant->delivery_charges=$cart_delivery_charges;
		$cart->rest_delivery_charges=$objRestaurant->delivery_charges;
	}
	
	$cart->invoice_number=$invoice_number;
	$cart->payment_menthod=$_POST['payment_method'];
	if(!isset($special_notes))
	{
		$special_notes = "";
	}
	
	$cart->createNewOrder($loggedinuser->id,$address,$serving_date,$asap,$payment_method,$invoice_number,1,$type,$cc,$gateway_token,$transaction_id, $platform_used, $is_guest,$special_notes, $CarkTokenOrderTbl);
	$cart->order_created=1;
	$cart->save();
	$faxstatus = 0;
	
	if($auth_defined)
	{//uses auth.net
		AuthorizeNetTransactionModel::saveTransaction($txn_id, $cart->order_id, $cart->restaurant_id, intval($cart->grand_total()), $cc);
	}
	
	if(isset($_SESSION["abandoned_cart_id"]) && $_SESSION["abandoned_cart_id"] > 0) 
	{
		$abandoned_carts->delete_abandoned_cart($_SESSION["abandoned_cart_id"]);
	}
	
	$objChargify = new chargifyMeteredUsage();
	$objChargify->sendMeteredUsageToChargify($cart->restaurant_id, intval($cart->grand_total()), $payment_method);
	
	if ( $objRestaurant->order_destination == "POS" ) 
	{
            // Saad Changes - 22-Sept-2014 --- Need to send credit card type(VISA/AMEX...) to order server for POS.
            $creditCardProfileId = 0;
            if($gateway_token!=''){
                // If new card and user also saved in db
                $creditCardProfileId = $gateway_token;
            }else if(isset($card_token) && $card_token > 0) {
                //Exisiting card selected by user
                $creditCardProfileId = $card_token;
            }else if(isset($creditCardType) && $creditCardType > 0){
                // If new card and (user do not wish to save) or (restaurant not allowed to save(tokenization))in db
                $typeForOrderServerOnly = 1;
                $creditCardProfileId = $creditCardType;
            }
            //Create a function object and call to posttoORDRSRVR function
            $fun=new clsFunctions();
            //$fun->posttoORDRSRVR($cart->order_id);
            $fun->posttoORDRSRVR($cart->order_id,$creditCardProfileId,$typeForOrderServerOnly);
	}
	
	/*********************************************************************************
		ORDER PDF
	***********************************************************************************/
	$arrCustomer['NAME']=$loggedinuser->cust_your_name.' '.$loggedinuser->LastName;
	$arrCustomer['EMAIL']=$loggedinuser->cust_email;
	$arrCustomer['PHONENUMBER']=$loggedinuser->cust_phone1;
	$arrCustomer['ADDRESS']= str_replace('~','',$loggedinuser->cust_odr_address) .', '. $loggedinuser->cust_ord_city.', '.$loggedinuser->cust_ord_state.', '.$loggedinuser->cust_ord_zip;
	$arrCustomer['DELIVERYADDRESS']=$loggedinuser->getUserDeliveryAddress(0);
	$arrCustomer['ZIPCODE']=$loggedinuser->getUserDeliveryZipCode();
	
	
	$arrRestaurant['RESTAURANTNAME']=$objRestaurant->name;
	$arrRestaurant['DELIVERYMETHOD']= $cart->delivery_type==cart::Pickup ?  "Pickup": "Delivery";
	$arrRestaurant['PAYMENTMETHOD']=$payment_method ;
	$arrRestaurant['ORDERID']=$cart->order_id;
	$arrRestaurant['DEVLIVERYDATETIME']=$serving_date;
	$arrRestaurant['DELIVERYNOTES']='';
	$arrRestaurant['VIPSECTION']='';
	
	
	$index=0;
	foreach ($cart->products as $product)
	{
		$str_attributes='<tr><td width="30%"></td><td width="70%"><strong>Extras </strong>';
		$str_assoc='<tr><td width="30%"></td><td width="70%"><strong>Associated Items </strong>';
		$last_attr_name='';
		$str_attributes .='<font size="8">';
		foreach($product->attributes as $attr)
		{
			if($last_attr_name != $attr->Option_name )
			{
				$str_attributes .='<b>'. $attr->Option_name .':</b>';
			}
			$str_attributes .=	trim($attr->Title) .($attr->Price!=0 ? " Add ". $attr->Price:"").', ';
			$last_attr_name = $attr->Option_name;
		}
		$str_attributes .='</font></td></tr>';
		
		foreach($product->associations as $association)
		{
			$str_assoc .= trim($association->item_title).'     Add - '. $association->retail_price .', ';
		}
		$str_assoc .='</td></tr>';
						
		if(count($product->attributes)==0) 
		{
			$str_attributes='';
		}
		
		if(count($product->associations)==0) 
		{
			$str_assoc='';
		}
		
		$str_requestNote='';
		$str_item_for='';
		if($product->requestnote)
		{
			$str_requestNote='<tr><td width="30%"></td><td width="70%"><strong>Special Requests: </strong>'.stripslashes($product->requestnote).'</td></tr>';
		}
		
		if($product->item_for)
		{
			$str_item_for = '<tr><td width="30%"></td><td width="70%"><strong>This item is for: </strong>'.stripslashes($product->item_for).'</td></tr>';
		}
	
		$arrOrder[$index]=array(
								"ITEMTITLE"=>stripslashes($product->item_title). " [ ". stripslashes($product->cat_name)." ]",
								"QTY"=>"  " .$product->quantity,
								"ITEM"=>$currency.number_format($product->retail_price, 2),
								"EACH"=>$currency.number_format($product->sale_price, 2),
								"ITEMTOTAL"=>$currency.number_format($product->sale_price*$product->quantity, 2),
								"REQUESTNOTES"=>trim($str_requestNote),
								"ASSOCITEMS"=>$str_assoc,
								"EXTRA"=>$str_attributes,
								"ITEMFOR"=>''
								);
		$index+=1;
	}//Product loop
	
	$arrSummary=array(
					"SUBTOTAL"=>$currency.number_format($cart->sub_total, 2),
					"COUPONDISCOUNT"=>($cart->coupon_discount >0?'<tr><td></td><td>Coupon Discount:</td><td>'. $currency.number_format($cart->coupon_discount, 2) .'</td></tr>':""),
					"DELIVERYCHARGES"=>($cart->delivery_type==cart::Delivery ? '<tr><td></td><td>Delivery Charges:</td><td>'.$currency.number_format($cart->delivery_charges(), 2) .'</td></tr>':''),
					"SALESTAX" =>$currency.number_format($cart->sales_tax(), 2),
					"DRIVERTIP" =>($cart->driver_tip >0 ?'<tr><td></td><td>Driver Tip:</td><td>'.$currency.number_format($cart->driver_tip, 2) .'</td></tr>':""),
					"VIPDISCOUNT" =>($cart->vip_discount>0 ? '<tr><td></td><td>VIP Discount:</td><td>'.$currency.number_format($cart->vip_discount,2) .'</td></tr>' :''),
					"TOTAL"=>$currency.number_format($cart->grand_total(), 2)
					);

	if (file_exists('tcpdf/config/lang/eng.php'))
	{
		require_once 'tcpdf/config/lang/eng.php';
		require_once 'tcpdf/tcpdf.php';
	}
	else
	{
		require_once '../tcpdf/config/lang/eng.php';
		require_once '../tcpdf/tcpdf.php';
	}
	
	$file_name = "pdffiles/pdf".$cart->order_id.".pdf";
	$funct = new clsFunctions();
	$funct->writePdf($arrCustomer,$arrRestaurant,$arrOrder,$arrSummary,$objRestaurant->reseller->pdf_image_header,$cart->order_id);
		
	
	/*********************************************************************************
			ORDER PDF  ENDS HERE
	***********************************************************************************/

	$confimration_email='Thank you for your recent order on <a href=\"'.$SiteUrl . $objRestaurant->url .'/\">'.$SiteUrl . $objRestaurant->url .'/</a>.  We are pleased to have an opportunity to serve you.  Please view your order below.';
	$common_confimration_email='<br><br>CUSTOMER INFORMATION: <br>&nbsp;
	Name: &nbsp;'. $arrCustomer['NAME'] .'<br>&nbsp;
	Email: <a href=\"mailto:'. $arrCustomer['EMAIL'] .'\">'. $arrCustomer['EMAIL'] .'</a><br>&nbsp;
	Phone: '. $arrCustomer['PHONENUMBER'] .'<br>&nbsp;
	Alternate Phone :'. $loggedinuser->cust_phone2 .' <br>&nbsp;
	Address: '. $arrCustomer['ADDRESS'] .'<br>&nbsp;
	Delivery Address:'. $arrCustomer['DELIVERYADDRESS'] .'<br>&nbsp;
	Zip Code: '. $arrCustomer['ZIPCODE'] .'<br>
	<hr> <br>
	ORDER INFORMATION:<br>
	Restaurants Name: '. $arrRestaurant['RESTAURANTNAME'] .'<br>
	Order Number: '. $arrRestaurant['ORDERID'] .'<br>
	'. $arrRestaurant['DELIVERYMETHOD'] .' Date And Time: '. $arrRestaurant['DEVLIVERYDATETIME'] .'<br>
	Order Receiving Method: '. $arrRestaurant['DELIVERYMETHOD'] .'<br>
	Special Instructions: <br>'.$arrRestaurant['DELIVERYNOTES'].'
	<hr> <br>
	ITEM INFORMATION: <br>';

	foreach ($cart->products as $product)
	{
		$common_confimration_email.='<strong>Item ID/Code - </strong>'. $product->item_code .'<br>
									<strong>Item Title - </strong>'. $product->item_title .'<br/>
									<strong>Item Price - </strong>'. $product->retail_price .'<br/>
									<strong>Quantity - </strong>'. $product->quantity .'';
		$last_attr_name='';
		foreach($product->attributes as $attr)
		{
			if($last_attr_name != $attr->Option_name )
			{
				$common_confimration_email.='<br/><strong>'. $attr->Option_name .' </strong>';
			}
			$common_confimration_email.=trim($attr->Title) . ($attr->Price!=0 ? " ".($attr->Price[0]=='-' ?"Subtract ":"Add "). $attr->Price:"") .', ';
			$last_attr_name = $attr->Option_name;
		}
		
		if(count($product->associations)>0)
		{
			$common_confimration_email.='<br/><strong>Associated Items - </strong>';
			foreach($product->associations as $association)
			{
				$common_confimration_email.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. stripslashes(trim($association->item_title)) .'  Add - '.$currency.$association->retail_price;
			}
		}

		$common_confimration_email.='<br/><strong>Special Notes - </strong>'. $product->requestnote .'<br><br>
									 <strong>Each Item Price - </strong>'.$currency. number_format($product->retail_price,2) .'<br>
									 <strong>Item Total Price - </strong>'.$currency. number_format($product->sale_price * $product->quantity,2) .'<br>
									 <hr><br>';
	}
	
	
	$common_confimration_email.='<b>TOTAL AMOUNT DUE:</b><br><b>Sub Total: </b>'. $arrSummary['SUBTOTAL']  .'<br>';
	if($cart->delivery_type==cart::Delivery)
	{
		$common_confimration_email.=' <b>Delivery Charges: </b>'.$currency. number_format($cart->delivery_charges(), 2)  .'<br>';
	}
	
	if($cart->driver_tip >0)
	{
		$common_confimration_email.=' <b>Driver Tip: </b>'.$currency. number_format($cart->driver_tip, 2)  .'<br>';
	}
		 
	$common_confimration_email.='<b>Tax: </b>'. $arrSummary['SALESTAX']  .'<br><b>---------------------</b><br>';
	
	if($cart->coupon_discount >0)
	{
		$common_confimration_email.=' <b>Coupon Discount: </b>'.$currency. number_format($cart->coupon_discount, 2)  .'<br>';
	}
	
	if($cart->vip_discount >0)
	{
		$common_confimration_email.=' <b>VIP Discount: </b>'.$currency. number_format($cart->vip_discount, 2)  .'<br>';
	}
		 
	$common_confimration_email.='<strong>Total=</strong>'. $arrSummary['TOTAL']  .'<br><br>
								<strong>PAYMENT METHOD:</strong><br>
								'. ($payment_method =='Cash' ? 'Cash Payment at '.$arrRestaurant['DELIVERYMETHOD'] :$payment_method).'<br>
								<br><br>';
	$restuarant_email='';
	  
	if( $objRestaurant->reseller->plain_text_header!='')
	{
		$restuarant_email =   $objRestaurant->reseller->plain_text_header ."<br><br>";		
	}
	
	$restuarant_email .= $common_confimration_email;
	if($objRestaurant->useValutec==true) 
	{
		if($_POST['vipcard']=="1") 
		{
			$restuarant_email.='<hr><br>VIP CARD INFORMATION:<br>';
			$restuarant_email.='<strong>VIP Member:</strong>&nbsp;&nbsp; New VIP Card member, please bring card!<br>';
			$restuarant_email.='<strong>VIP Points:</strong>&nbsp;&nbsp;'.round($objRestaurant->rewardPoints * $cart->sub_total, 0).'<br><br>';
		}
		elseif($loggedinuser->valuetec_card_number>0)  
		{
			$restuarant_email.='<hr><br>VIP CARD INFORMATION:<br>';
			$restuarant_email.='<strong>VIP Member Since:</strong>&nbsp;&nbsp;'.$loggedinuser->valutec_registeration_date.'<br>';
			$restuarant_email.='<strong>VIP Card Number:</strong>&nbsp;&nbsp;'. $loggedinuser->valuetec_card_number.'<br>';
			$restuarant_email.='<strong>VIP Points:</strong>&nbsp;&nbsp;'. $loggedinuser->valuetec_points.'<br>';
			$restuarant_email.='<strong>VIP Balance:</strong>&nbsp;&nbsp;'.$currency. number_format($loggedinuser->valuetec_reward ,2) .'<br>';
				
			if($cart->vip_discount >0) 
			{
				$restuarant_email .='<strong>Redeemed on this sale:</strong>&nbsp;&nbsp;'.$currency.  number_format($cart->vip_discount ,2) .'<br><br>';
			}
			else
			{
				$restuarant_email .='<strong>Points earned on this sale:</strong>&nbsp;&nbsp;'.  round ($objRestaurant->rewardPoints * $cart->sub_total, 0).'<br><br>';
			}
		} //CUSTOMER HAVE CARD	
	}//RESTAURANT HAVE CARD SYSTEM
	
	$restuarant_email .= '<br>Phone: '. $objRestaurant->phone .'<br>Fax: '. $objRestaurant->fax .'<br>';
	
	if ($objRestaurant->yelp_review_request == 1 && !empty($objRestaurant->yelp_restaurant_url)) 
	{
		$common_confimration_email .=
									'<br><br>We want you to love your meal.
									If you do, please leave us a <a href="'.$objRestaurant->yelp_restaurant_url.'" target="_blank">Yelp review</a>.
									If you are unsatisfied for any reason please <a href="mailto:'.$objRestaurant->email.'">let us know</a> so we can make it right<br><br>';
	}
	$confimration_email .=$common_confimration_email;
	$confimration_email .=' Thank you for your order. <br><br>
	<a href=\"'.$SiteUrl .$objRestaurant->url.'\"/>'.$SiteUrl . $objRestaurant->url .'/</a><br>Phone: '. $objRestaurant->phone .'<br>Fax: '. $objRestaurant->fax .'<br>';
	
	$objMail->sendTo($confimration_email,"Order Confirmation",$arrCustomer['EMAIL']);
	
	if ( $objRestaurant->order_destination == "fax" ) 
	{
		if(strlen($objRestaurant->fax) == 10)
		{
			$objRestaurant->fax = '1'.$objRestaurant->fax;
		}
		
		if (file_exists("includes/phaxio.php"))
		{
			include_once("includes/phaxio.php"); 
		}
		else if (file_exists("../includes/phaxio.php"))
		{
			include_once("../includes/phaxio.php"); 
		}
		else if (file_exists("../../includes/phaxio.php"))
		{
			include_once("../../includes/phaxio.php"); 
		}
	
		$mFax=new EWOphaxio();
		$mFax->sendfax($cart->order_id, $objRestaurant->fax);
		unset($mFax);
	}//FAX METHOD
	else
	{
		if($objRestaurant->rest_order_email_fromat == 'pdf') 
		{
			$objMail->clearattachments();
			$objMail->addattachment($file_name);
			$objMail->sendTo('', $objRestaurant->name . ' order '. $cart->order_id .' Email',$objRestaurant->email,false);
		}
		else
		{
			$objMail->sendTo($restuarant_email,$objRestaurant->name . ' order '. $orderId ,$objRestaurant->email,true);
		}//ELSE PLAIN TEXT
	
		$function_obj->posttoVCS($cart->order_id,0,123);//fax_status==0 successfullt sent
	}
	
	//////////////////////////////if Voice Confirmation status is ON from admin site then below email will be deliverd//////////////////////
	if($objRestaurant->phone_notification == 1) 
	{
		$amount = $arrSummary['TOTAL'];
		 ///////////////////////voice confirmation email with txt attachment  ///////////////////
		$objMail->clearattachments();
		$phone_notification_mail_body = $objRestaurant->name." received a ".$arrRestaurant['DELIVERYMETHOD'] ." order from ".
										$arrCustomer['NAME'] ." in the amount of ".$currency.$amount." at ".$serving_date;
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
		$loggedinuser->saveToSession();
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
		$loggedinuser->saveToSession();
	}
	
	$cart->order_created=1;
	$cart->save();
	
	//-------Eddited by Asher------------//
	$cart->destroysession();
	$loggedinuser->destroyUserSession();
	$_SESSION['total'] = $total=$cart->grand_total();
	$_SESSION['cart'] = $cart->order_created;
	//-------------------------------------//
        redirect($SiteUrl .$objRestaurant->url ."/?item=thankyou&orderid=".$cart->order_id );
?>
