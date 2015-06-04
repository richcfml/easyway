<?php 
    $cc=  substr($_POST["CardNumber"], -4, 4);
    $creditCardType = substr($_POST["CardNumber"], 0,1);
    $type = '';
    $gateway_token='';
    if (trim(strtolower($_POST["CardType"])=="amex"))
    {
        $type = 3;
    }
    else if (trim(strtolower($_POST["CardType"])=="visa"))
    {
        $type = 4;
    }
    else if (trim(strtolower($_POST["CardType"])=="master"))
    {
        $type = 5;
    }
    else if (trim(strtolower($_POST["CardType"])=="discover"))
    {
        $type = 6;
    }
    
    $mZipPostal = "Zip Code:";

    if ($objRestaurant->region == "2") //Canada
    {
        $mZipPostal = "Postal Code:";
    }
	
    Log::write(" In Submit Order","LoggedinUser Data:".print_r($loggedinuser,true),'order', 0 , 'user');

    $loggedinuser->cust_your_name= trim($_POST["customer_name"]) ;
    $loggedinuser->LastName= trim($_POST["customer_last_name"]) ;
    $loggedinuser->cust_phone1= "";
    $loggedinuser->cust_email="";
    Log::write("Customer name is:".$_POST['customer_name'],"LoggedinUser Data:".print_r($loggedinuser,true),'order', 0 , 'user');
		
    $loggedinuser->delivery_address_choice=1;
    $loggedinuser->street1 = "";
    $loggedinuser->street2 ="";
    $loggedinuser->cust_ord_city ="";
    $loggedinuser->cust_ord_state ="";
    $loggedinuser->cust_ord_zip ="";
    $loggedinuser->delivery_address="";

    $loggedinuser->createNewUser();
    $loggedinuser->savetosession();	
    Log::write("Customer name is:".$_POST['customer_name'],"LoggedinUser Data After shipping info:".print_r($loggedinuser,true),'order', 0 , 'user');
    $is_guest = 1;		

	
    $serving_date=date("m-d-Y",time());
    $asap=1;
    $serving_time=date("H:i");	
	
    $serving_date=$serving_date ." ".$serving_time;
    $payment_method="Credit Card";
			
    $address = "";
    $invoice_number=$_POST['invoice_number'];
    $transaction_id= $_POST["TransNumber"];
	
    $del_special_notes = "";
    $CarkTokenOrderTbl = "";
		 
    $cart->invoice_number=$invoice_number;
    $cart->payment_menthod=1;
    $cart->special_notes = $del_special_notes;
	
    $cart->createNewOrder($loggedinuser->id,$address,$serving_date,$asap,$payment_method,$invoice_number,1,$type,$cc,$gateway_token,$transaction_id, 4, $is_guest,$del_special_notes, $CarkTokenOrderTbl);
    $cart->order_created=1;
    $cart->save();

    $loggedinuser->savetosession();

	
    if(isset($_SESSION["abandoned_cart_id"]) && $_SESSION["abandoned_cart_id"] > 0) 
    {
        $abandoned_carts->delete_abandoned_cart($_SESSION["abandoned_cart_id"]);
    }
	
    $objChargify = new chargifyMeteredUsage();
    $objChargify->sendMeteredUsageToChargify($cart->restaurant_id, intval($cart->grand_total()), $payment_method);

    if ( $objRestaurant->order_destination == "POS" ) 
    { 
        $typeForOrderServerOnly = 1;
        $creditCardProfileId = $creditCardType;
        $fun=new clsFunctions();    
        $fun->posttoORDRSRVR($cart->order_id,$creditCardProfileId,$typeForOrderServerOnly);
    }
		
	/*********************************************************************************
				ORDER PDF 
	***********************************************************************************/
	$arrCustomer['NAME']=$loggedinuser->cust_your_name.' '.$loggedinuser->LastName;
	$arrCustomer['EMAIL']=$loggedinuser->cust_email;
	$arrCustomer['PHONENUMBER']=$loggedinuser->cust_phone1;
	$arrCustomer['ADDRESS']= str_replace('~','',$loggedinuser->cust_odr_address) .', '. $loggedinuser->cust_ord_city.', '.$loggedinuser->cust_ord_state.', '.$loggedinuser->cust_ord_zip;
	$arrCustomer['DELIVERYADDRESS']=$loggedinuser->get_delivery_address(0);
	$arrCustomer['ZIPCODE']=$loggedinuser->get_delivery_zip();
	
	$arrRestaurant['RESTAURANTNAME']=$objRestaurant->name;
	$arrRestaurant['DELIVERYMETHOD']= $cart->delivery_type==cart::Pickup ?  "Pickup": "Delivery";
	$arrRestaurant['PAYMENTMETHOD']=$payment_method ;
	$arrRestaurant['ORDERID']=$cart->order_id;
	$arrRestaurant['DEVLIVERYDATETIME']=$serving_date;
	$arrRestaurant['DELIVERYNOTES']=$cart->special_notes;
	
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
                        if(substr($attr->Price,0,1)=="-"){
                            $sign = ' Subtract ';
                        }
                        else{
                            $sign = ' Add ';
                        }
			$str_attributes .=	trim($attr->Title) .($attr->Price!=0 ? $sign. $attr->Price:"").', ';
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
								"ITEMFOR"=>$str_item_for
								 );
		$index+=1;
	}//Product loop
				
	$arrSummary=array(
					"SUBTOTAL"=>$currency.number_format($cart->sub_total, 2),
					"SALESTAX" =>$currency.number_format($cart->sales_tax(), 2),
					"DRIVERTIP" =>($cart->driver_tip >0 ?'<tr><td></td><td>Driver Tip:</td><td>'.$currency.number_format($cart->driver_tip, 2) .'</td></tr>':""),
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
		
	$confimration_email='Thank you for your recent order on <a href=\"http://easywayordering.com/'. $objRestaurant->url .'/\">http://www.easywayordering.com/'. $objRestaurant->url .'/</a>.  We are pleased to have an opportunity to serve you.  Please view your order below.';
	
	$common_confimration_email='<br><br>CUSTOMER INFORMATION: <br>&nbsp;
	Name: &nbsp;'. $arrCustomer['NAME'] .'<br>&nbsp;
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
		<strong>Item For - </strong>'. $product->item_for .'<br/>
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
	
	if($cart->driver_tip >0)
	{
		$common_confimration_email.=' <b>Driver Tip: </b>'.$currency. number_format($cart->driver_tip, 2)  .'<br>';
	}
		 
	$common_confimration_email.='<b>Tax: </b>'. $arrSummary['SALESTAX']  .'<br><b>---------------------</b><br>';
	 
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
	
	$restuarant_email .= '<br>Phone: '. $objRestaurant->phone .'<br>Fax: '. $objRestaurant->fax .'<br>';
	
	if ($objRestaurant->yelp_review_request == 1 && !empty($objRestaurant->yelp_restaurant_url)) 
	{
		$common_confimration_email .= '<br><br>We want you to love your meal.
                                                If you do, please leave us a <a href="'.$objRestaurant->yelp_restaurant_url.'" target="_blank">Yelp review</a>.
                                                If you are unsatisfied for any reason please <a href="mailto:'.$objRestaurant->email.'">let us know</a> so we can make it right<br><br>';
	}
	$confimration_email .= $common_confimration_email .' <a href=\"http://easywayordering.com/ozzies/\">http://www.easywayordering.com/'. $objRestaurant->url .'/</a><br>Phone: '. $objRestaurant->phone .'<br>Fax: '. $objRestaurant->fax .'<br>';
	$_SESSION["invoice_number_thankyou"] = $_POST['invoice_number'];
	include "views/notify_customers.php";
        redirect($SiteUrl .$objRestaurant->url ."/?item=thankyou&kiosk=1" );
        exit;
?>


