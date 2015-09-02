<? 

if(!isset($repid_payment)){
	if(!isset($_POST['serving_date'])) { @mysql_close($mysql_conn);
	 redirect($client_path .$objRestaurant->url ."/?item=menu&ifrm=load_resturant" );exit;
	 //header("location: ". $client_path .$objRestaurant->url ."/?item=cart" );
	}
	if($cart->isempty()) { @mysql_close($mysql_conn);
	   redirect($client_path .$objRestaurant->url ."/?item=menu&ifrm=load_resturant" );exit;
		// header("location: ". $client_path .$objRestaurant->url ."/?item=cart" );
	}	
}
	extract($_POST);
	 if(isset($cart_delivery_charges)){
		    $objRestaurant->delivery_charges=$cart_delivery_charges;
			$cart->rest_delivery_charges=$objRestaurant->delivery_charges;
			 
		}
		$is_guest = 0;
		if(isset($_POST['customer_name'])){
			$loggedinuser->	cust_your_name= trim($customer_name) ;
			$loggedinuser->	LastName= trim($customer_last_name) ;
			$loggedinuser->	cust_phone1= trim($customer_phone);
			$loggedinuser->	cust_email=trim($customer_email);
			
			if($cart->delivery_type==cart::Delivery) {  
				$loggedinuser->street1 =trim($customer_address);
				$loggedinuser->street2 ='';
				$loggedinuser->cust_ord_city =trim($customer_city);
				$loggedinuser->cust_ord_state =trim($customer_state);
				$loggedinuser->cust_ord_zip =trim($customer_zip);
				$loggedinuser->delivery_address=$loggedinuser->street1 .", ". $loggedinuser->cust_ord_city .", ". $loggedinuser->cust_ord_state;
				$loggedinuser->delivery_address_choice=1;
			}else{
				$loggedinuser->street1 ='';
				$loggedinuser->street2 ='';
				$loggedinuser->cust_ord_city ='';
				$loggedinuser->cust_ord_state ='';
				$loggedinuser->cust_ord_zip ='';
				$loggedinuser->delivery_address='';
			 }
			 
			$loggedinuser->createNewUser();
			$loggedinuser->saveToSession();	
			$is_guest = 1;
		}
 	    $arrRestaurant['VIPSECTION']='';
	if($objRestaurant->useValutec==true) {
		if($_POST['vipcard']=="1") {
			
		$arrRestaurant['VIPSECTION']='<tr><td width="55%">VIP Member:&nbsp;&nbsp; New VIP Card member, please bring card!</td><td width="45%" style="text-align:right;">VIP Points:&nbsp;&nbsp;'. round ($objRestaurant->rewardPoints * $cart->sub_total, 0) .'</td></tr>';
		
			 
		}elseif($loggedinuser->valuetec_card_number>0)  {
			
			$arrRestaurant['VIPSECTION']='<tr><td width="55%">VIP Member Since:&nbsp;&nbsp;'.  $loggedinuser->valutec_registeration_date .'</td><td width="45%" style="text-align:right;">VIP Card Number:&nbsp;&nbsp;'. $loggedinuser->valuetec_card_number .'</td></tr>';
			
			$arrRestaurant['VIPSECTION'] .='<tr><td width="55%">VIP Points:&nbsp;&nbsp;'.  $loggedinuser->valuetec_points .'</td><td width="45%" style="text-align:right;">VIP Balance:&nbsp;&nbsp;$'. number_format($loggedinuser->valuetec_reward ,2) .'</td></tr>';
			
			if($vip_discount >0) {
					$arrRestaurant['VIPSECTION'] .='<tr><td   colspan="2">Redeemed on this sale:&nbsp;&nbsp;$'.  number_format($vip_discount ,2) .'</td></tr>';
				 
			
			  }else{
				  $arrRestaurant['VIPSECTION'] .='<tr><td   colspan="2">Points earned on this sale:&nbsp;&nbsp;'.  round ($objRestaurant->rewardPoints * $cart->sub_total, 0).'</td></tr>';
			  }
		   
			
			} //CUSTOMER HAVE CARD
		
	}//RESTAURANT HAVE CARD SYSTEM
	
	 	if(!is_numeric($loggedinuser->id) || $loggedinuser->id==0){
			$loggedinuser->createNewUser();
			$is_guest = 1;
		}
 
 
	$serving_time=$_POST['serving_time'];
	$serving_date=date("m-d-Y",$_POST['serving_date']);
	$asap=0;
	 if($serving_time=='0'){
		 $asap=1;
		 $serving_time=date("H:i");
	 }
	$serving_date=$serving_date ." ".$serving_time;
	$payment_method=($_POST['payment_method'] == "1" ? "Credit Card" : "Cash");
	$address=$loggedinuser->getUserDeliveryAddress(0) .", ".$loggedinuser->getUserDeliveryZipCode();
	$invoice_number='';
	 if(isset($_POST['invoice_number'])){
		 	 $invoice_number=$_POST['invoice_number'];
		 }
	 if(!isset($type)) $type='';
	 if(!isset($cc)) $cc='';
	 if(!isset($gateway_token)) $gateway_token='';
	 if(!isset($transaction_id)){
		 $transaction_id=0;
		 }
	 	 
	$cart->invoice_number=$invoice_number;
	$cart->payment_menthod=$_POST['payment_method'];
	
	$cart->createNewOrder($loggedinuser->id,$address,$serving_date,$asap,$payment_method,$invoice_number,1,$type,$cc,$gateway_token,$transaction_id, $platform_used, $is_guest);
	$cart->order_created=1;
	$cart->save();
	if(isset($_SESSION["abandoned_cart_id"]) and $_SESSION["abandoned_cart_id"] > 0) {
		$abandoned_carts->deleteAbandonedCart($_SESSION["abandoned_cart_id"]);
	}
        if ( $objRestaurant->order_destination == "POS" ) { 
            //Create a function object and call to posttoORDRSRVR function
            $fun=new clsFunctions();
            $fun->posttoORDRSRVR($cart->order_id);
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
	$arrRestaurant['DELIVERYNOTES']=$special_notes;
	 
	
	
	$index=0;
 	foreach ($cart->products as $product){
			$str_attributes='<tr><td width="30%"></td><td width="70%" style="font-weight:bold;">Extras</td></tr>';
		 
			$str_assoc='<tr><td width="30%"></td><td width="70%" style="font-weight:bold;">Associated Items</td></tr>';
			$last_attr_name='';
				foreach($product->attributes as $attr){
					 if($last_attr_name != $attr->Option_name ){
						  $str_attributes .='<tr><td></td><td><font size="8"><b>'. $attr->Option_name .':</b></font></td></tr>';
				 	 }
					 $str_attributes .='<tr><td></td><td> <font size="8">'.
					   trim($attr->Title) .($attr->Price!=0 ? " Add ". $attr->Price:"")
					   .'</font></td></tr>';
						$last_attr_name = $attr->Option_name;
					}
					
				foreach($product->associations as $association){
					 $str_assoc .='<tr><td></td><td>'.trim($association->item_title)."     Add - ". $association->retail_price .'</td></tr>';
					}
						
			    if(count($product->attributes)==0) $str_attributes='';
	 			if(count($product->associations)==0) $str_assoc='';
			   $str_requestNote='';
				 if($product->requestnote)
				{
				  $str_requestNote='<tr><td width="30%"></td><td width="70%" style="font-weight:bold;">Special Requests:</td></tr>';
				  $str_requestNote.='<tr><td></td><td>'.stripslashes($product->requestnote).'</td></tr>';	
				}

			 	$arrOrder[$index]=array(
					"ITEMTITLE"=>stripslashes($product->item_title). " [ ". stripslashes($product->cat_name)." ]",
					"QTY"=>"  " .$product->quantity,
					"ITEM"=>"$".number_format($product->retail_price, 2),
					"EACH"=>"$".number_format($product->sale_price, 2),
					"ITEMTOTAL"=>"$".number_format($product->sale_price*$product->quantity, 2),
					"REQUESTNOTES"=>trim($str_requestNote),
					"ASSOCITEMS"=>$str_assoc,
					"EXTRA"=>$str_attributes,
					"ITEMFOR"=>''
				 );
			 $index+=1;
			}//Product loop
			
	 			 
		 $arrSummary=array(
			"SUBTOTAL"=>"$".number_format($cart->sub_total, 2),
			"COUPONDISCOUNT"=>($cart->coupon_discount >0?'<tr><td></td><td>Coupon Discount:</td><td>'. "$".number_format($cart->coupon_discount, 2) .'</td></tr>':""),
			"DELIVERYCHARGES"=>($cart->delivery_type==cart::Delivery ? '<tr><td></td><td>Delivery Charges:</td><td>$'.number_format($cart->delivery_charges(), 2) .'</td></tr>':''),
			"SALESTAX" =>"$".number_format($cart->sales_tax(), 2),
			"DRIVERTIP" =>($cart->driver_tip >0 ?'<tr><td></td><td>Driver Tip:</td><td>$'.number_format($cart->driver_tip, 2) .'</td></tr>':""),
			"VIPDISCOUNT" =>($cart->vip_discount>0 ? '<tr><td></td><td>VIP Discount:</td><td>$'.number_format($cart->vip_discount,2) .'</td></tr>' :''),
			"TOTAL"=>"$".number_format($cart->grand_total(), 2)
			
		);
	
	require_once 'tcpdf/config/lang/eng.php';
	require_once 'tcpdf/tcpdf.php';
	$file_name = "pdffiles/pdf".$cart->order_id.".pdf";
	 
 	$funct = new clsFunctions();
	$funct->writePdf($arrCustomer,$arrRestaurant,$arrOrder,$arrSummary,$objRestaurant->reseller->pdf_image_header,$cart->order_id);

	/*********************************************************************************
			ORDER PDF  ENDS HERE
	***********************************************************************************/
 
	
	$confimration_email='Thank you for your recent order on <a href=\"http://easywayordering.com/'. $objRestaurant->url .'/\">http://www.easywayordering.com/'. $objRestaurant->url .'/</a>.  We are pleased to have an opportunity to serve you.  Please view your order below.';
	
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
	 
 foreach ($cart->products as $product){
 
		 $common_confimration_email.='<strong>Item ID/Code - </strong>'. $product->item_code .'<br>
		 <strong>Item Title - </strong>'. $product->item_title .'<br/>
                 <strong>Item For - </strong>'. $product->item_for .'<br/>
		 <strong>Item Price - </strong>'. $product->retail_price .'<br/>
			<strong>Quantity - </strong>'. $product->quantity .'';
		 $last_attr_name='';
		 foreach($product->attributes as $attr){
		 	  if($last_attr_name != $attr->Option_name ){
				 $common_confimration_email.='<br/><strong>'. $attr->Option_name .' </strong>';
				}
				$common_confimration_email.=trim($attr->Title) . ($attr->Price!=0 ? " ".($attr->Price[0]=='-' ?"Subtract ":"Add "). $attr->Price:"") .', ';
				$last_attr_name = $attr->Option_name;
		 	 
		 }
		if(count($product->associations)>0){
			 $common_confimration_email.='<br/><strong>Associated Items - </strong>';
	 	  	foreach($product->associations as $association){
					$common_confimration_email.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. stripslashes(trim($association->item_title)) .'  Add - $'.$association->retail_price;
		  	}
		}
		 
		$common_confimration_email.='<br/><strong>Special Notes - </strong>'. $product->requestnote .'<br><br>
		 <strong>Each Item Price - </strong>$'. $product->retail_price .'<br>
		 <strong>Item Total Price - </strong>$'.  round($product->sale_price * $product->quantity,2) .'<br>
		 <hr><br>';
 
 }
 
		
$common_confimration_email.='<b>TOTAL AMOUNT DUE:</b><br><b>Sub Total: </b>'. $arrSummary['SUBTOTAL']  .'<br>';
  if($cart->delivery_type==cart::Delivery)
 	 $common_confimration_email.=' <b>Delivery Charges: </b>$'. number_format($cart->delivery_charges(), 2)  .'<br>';
 if($cart->driver_tip >0)
 	 $common_confimration_email.=' <b>Driver Tip: </b>$'. number_format($cart->driver_tip, 2)  .'<br>';
	 
 $common_confimration_email.='<b>Tax: </b>'. $arrSummary['SALESTAX']  .'<br><b>---------------------</b><br>';
 
 if($cart->coupon_discount >0)
 	 $common_confimration_email.=' <b>Coupon Discount: </b>$'. number_format($cart->coupon_discount, 2)  .'<br>';
  if($cart->vip_discount >0)
 	 $common_confimration_email.=' <b>VIP Discount: </b>$'. number_format($cart->vip_discount, 2)  .'<br>';
	 
 $common_confimration_email.='<strong>Total=</strong>'. $arrSummary['TOTAL']  .'<br><br>
 <strong>PAYMENT METHOD:</strong><br>
 '. ($payment_method =='Cash' ? 'Cash Payment at '.$arrRestaurant['DELIVERYMETHOD'] :$payment_method).'<br>
 <br><br>';
 
 $restuarant_email='';
  
 	if( $objRestaurant->reseller->plain_text_header!='')
			$restuarant_email =   $objRestaurant->reseller->plain_text_header ."<br><br>";		
			
 $restuarant_email .= $common_confimration_email.'<br>Phone: '. $objRestaurant->phone .'<br>Fax: '. $objRestaurant->fax .'<br>';
 
 
$confimration_email .= $common_confimration_email .' <a href=\"http://easywayordering.com/ozzies/\">http://www.easywayordering.com/'. $objRestaurant->url .'/</a><br>Phone: '. $objRestaurant->phone .'<br>Fax: '. $objRestaurant->fax .'<br>';

/*if($cart->payment_menthod==cart::GATEWAY){
	include "views/authorize_gateway.php";
	exit;
}*/
include "views/notify_customers.php";
if(!isset($repid_payment)){
 @mysql_close($mysql_conn);
 redirect($client_path .$objRestaurant->url ."/?item=thankyou&ifrm=thankyou" );
	 exit;
}
 ?>


