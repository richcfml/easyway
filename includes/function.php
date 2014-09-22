<? 
	//*************************** Class utility for necessery function  *********************/
	 class clsFunctions  {
	 
	 //***************************************     VALID LOGIN	*****************************************

	function ValidateAdmin_MySQL($user , $pass , $fun_qry_str , $pass_feild){
					
					if(($user) && ($user != '')) { 
                    	$quary = @mysql_query($fun_qry_str);
                      	$quary_rs = @mysql_fetch_array($quary);
						
						if(!empty($quary_rs['id'])) {
						//echo "select * from adminuser where adminUserPass = $pass";
                             $pass_sql = $quary_rs[$pass_feild];
                                       
									 if($pass_sql == $pass){
										// V A L I D  L O G I N
										return 1;

									 } else {
										
										return 0;
									 
									 }
							
                        }
					} // end of 1st if
	
	} // End of function	
//___________________________________________________________________________________________________	 
 //**********************  SEND MAIL **********************

function Send_Mail($email,$subject,$body,$from){
			// message
			$message = '

			<html>
			<head>
			 
			</head>
			<body>
			  '."$body".'
			</body>
			</html>
			';
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $headers .= "From:"."<".$from.">". "\r\n";
			mail($email, $subject, $message, $headers);
}


function SendHtmlMail($email, $subject_mail, $body, $from){
	$mime_boundary = "----easywayordering.com----".md5(time());
	
	$to		 = $email;
	$subject = $subject_mail;
	
	$headers = "From: $from\n";
	$headers .= "Reply-To: $from\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
	
	# -=-=-=- TEXT EMAIL PART
	
	$message = "--$mime_boundary\n";
	$message .= "Content-Type: text/plain; charset=UTF-8\n";
	$message .= "Content-Transfer-Encoding: 8bit\n\n";
	
	

	# -=-=-=- HTML EMAIL PART
	 
	$message .= "--$mime_boundary\n";
	$message .= "Content-Type: text/html; charset=UTF-8\n";
	$message .= "Content-Transfer-Encoding: 8bit\n\n";
	
	$message .= "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
	$message .= "<html>\n";
	$message .= "<body style='margin:0px;font-family:Arial, Helvetica, sans-serif;'>\n"; 
	$message .= $body;
	$message .= "</body>\n";
	$message .= "</html>\n";
	
	# -=-=-=- FINAL BOUNDARY
	
	$message .= "--$mime_boundary--\n\n";
	
	# -=-=-=- SEND MAIL
	
	
	$mail_sent = mail( $to, $subject, $message, $headers,'-f '.$from );
	return $mail_sent;

}





	 
	//*************************** End of Class utility for necessery function  *********************/
	function get_datelist($select_date){
	
	$date	=	time();
	$today 	= date("m/d/Y",$date);
	$select_list = "<option value=\"$date\""; 
	$select_list .= ">";
	$select_list .= "".$today."</option>";
		
	for($i=1; $i<30; $i++) 
			{
				$next			= mktime(0,0,0,date("m"),date("d")+$i,date("Y"));
				$next_date		= date("m/d/Y", $next);
				$select_list .= "<option value=\"$next\"";  
				if($select_date == $next) $select_list .= "selected=\"selected\"" ;
				$select_list .= ">";
				$select_list .= "".$next_date."</option>";
			}
	
		return $select_list;    
}
		//*************************** End of Get date function  *********************/
		
		//////////////////////////////GET CONTENTS/////////////////////////////////////////////
		function get_contents($type){

		$contents_qry		=	mysql_query("select * from bottom_contents where content_text_type = $type");
		$contents_infoRs	=	mysql_fetch_object($contents_qry);	
		return	stripslashes($contents_infoRs->content_text);
		
		}
		//////////////////////////////////////////////////////////////////////////////////////
		
	function get_image($img_path,$w,$h,$alt){
			
			
		if (isset($img_path)) {
		
		list($m, $d) = split('/',$img_path);
		$img_path=str_replace("$m","$a","$img_path");
			echo  <<<EOD
			<img src="thum_creater/phpThumb.php?src=http://localhost/carry4u/$img_path&w=$w&h=$h&zc=1" border="0" alt="$alt">
EOD;
} else {
	echo  <<<EOD
			<img src="thum_creater/phpThumb.php?src=http://localhost/carry4u/images/resturant_logos/default_200_by_200.jpg&zc=1&w=$w&h=$h" border="0" >
EOD;
}
	}	
	
	//////////////////////////////GET CONTENTS/////////////////////////////////////////////
		function get_product_subcat($id){

		$pro_subcat_qry		=	mysql_query("select sub_cat_id from product where prd_id=$id");
		$pro_subcat_qry_infoRs	=	mysql_fetch_object($pro_subcat_qry);	
		return	$pro_subcat_qry_infoRs->sub_cat_id;
		
		}
		//////////////////////////////////////////////////////////////////////////////////////
		
		function get_subcat_id($catID){

				$Subquery = mysql_query("select cat_id,cat_name from categories where parent_id=".$catID." order by cat_id LIMIT 0,1");
				$Subid	=	mysql_fetch_object($Subquery);
		return	$SubCatid = $Subid->cat_id;
		
		
		}
		//////////////////////////////////////////////////////////////////////////////////////
		function get_cat_tax($catID){
				$cat_taxquery = mysql_query("select id,tax_percent from resturants where id =".$catID);
				//$cat_taxquery = mysql_query("select cat_id,tax_percent from categories where cat_id =".$catID);
				$cat_tax	=	mysql_fetch_object($cat_taxquery);
		return	$tax = $cat_tax->tax_percent;
		
		
		}
		//////////////////////////////////////////////////////////////////////////////////////
		function get_subcat_des($subcatID){

				$subcat_query = mysql_query("select cat_id,cat_des from categories where cat_id =".$subcatID);
				$subcat_des	=	mysql_fetch_object($subcat_query);
		return	stripslashes($subcat_des->cat_des);
		
		
		}
		//////////////////////////////////////////////////////////////////////////////////////
		
		//////////////////////////////////////function to get days names///////////////////////
		function daysName($j) {
		  if($j == 0) {
			  $days = 'Monday';
		  }else if($j == 1) {
			  $days = 'Tuesday';
		  }else if($j == 2) {
			  $days = 'Wednesday';
		  }else if($j == 3) {
			  $days = 'Thursday';
		  }else if($j == 4) {
			  $days = 'Friday';
		  }else if($j == 5) {
			  $days = 'Saturday';
		  }else if($j == 6) {
			  $days = 'Sunday';
		  }
		  return $days;
		}
		///////////////////////////////////////////////////////////////////////////////////////
		function _esc_xmlchar($string)
		{
	 
							$data=strtr($string,array('&'  => '&amp;',
									   '&#0084;'  => ' ',
									   '&#0093;'  => ' ',                                                                       
									   '“'  => '"',
									   '”'  => '"',
									   'â€œ'  => '"',
									   'â€'  => '"',
									   ' '  => ' ',
									   '>'  => '&gt;',
									   '<'  => '&lt;',
									   '"'  => '&quot;',
									   '&rdquo;'  => '"',
									   '&rsquo;'  => '&apos;',
									   'â€™;'  => '---',
									   '\'' => '&apos;' 									  
									    )
						);
					
			return preg_replace( '/\r\n/', ' ', trim($data) );  
		}
			function esc_special($string)
		{
	 
							$data=strtr($string,array('('  => '',
									   ')'  => '',
									   '-'  => '' ,                                                                   
									 		' '  => ''    		  
									    )
						);
					
			return preg_replace( '/\r\n/', ' ', trim($data) );  
		}

function GetFileExt($fileName){
			$ext = substr($fileName, strrpos($fileName, '.') + 1);
			$ext = strtolower($ext);
			return $ext;
		}
		
function SubmitToken($arrPost )
	
{
	 
		$url="https://www.securepay.com/tokenpayment/update.cfm";
	$fields_string ='';
		foreach($arrPost as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');

    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
		 CURLOPT_POST		   => true,
		 CURLOPT_POSTFIELDS	   => $fields_string
    );
	


    $ch      = curl_init( $url );
	 
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
	
	
    return $header;
}


function posttoVCS($orderId, $faxstatus, $faxid) 
{
	//14 May 2014, Gulfam - New requimrnet is to send Phone calls whatever the Fax status is
	ini_set('diplay_errors',0);
	$order_qry = mysql_query("SELECT OrderID, Totel as total,coupon_discount, driver_tip,delivery_chagres,Tax,UserID,order_receiving_method,DesiredDeliveryDate,
							submit_time,asap_order,payment_method,fax_sent,fax_date,DelSpecialReq,DeliveryAddress,cat_id,OrderDate  FROM ordertbl WHERE OrderID =".$orderId);
	$order_rs  = mysql_fetch_assoc( $order_qry );
 
	$order_detail_qry = mysql_query("SELECT item_title , 0 as item_total_price,quantity as item_qty,prd.retail_price as item_price,RequestNote as special_notes,extra, pid as item_id ,associations as associated_items, item_for
								FROM orderdetails  ord Inner join product prd
 								on ord.pid=prd.prd_id
 								WHERE orderid = ". $order_rs['OrderID'] ."" );
  
	$cust_qry = mysql_query("SELECT cust_your_name, LastName ,cust_phone1  FROM customer_registration WHERE  id = ". $order_rs['UserID'] ."	");
	$cust_rs  = mysql_fetch_assoc( $cust_qry );

	$rest_qry = mysql_query("SELECT phone, phone_notification  FROM resturants WHERE  id = ". $order_rs['cat_id'] ."	");
	$rest_rs  = mysql_fetch_assoc( $rest_qry );
 	
	$mPhoneQuery = mysql_query("SELECT PhoneNumber FROM ordertbl WHERE  OrderID = ".$orderId);
	$mPhone = "";
	if (mysql_num_rows($mPhoneQuery)>0)
	{
		$mRow = mysql_fetch_object($mPhoneQuery);
		if (trim($mRow->PhoneNumber)!="")
		{
			$mPhone  = mysql_fetch_assoc( $rest_qry );
		}
	}

 
	$associated_items_price = 0;
	$extra_items_price = 0;
	$index=1;
	while ($order_detail_rs = mysql_fetch_object ($order_detail_qry )) 
	{
		$associated_items_price = 0;
		$extra_items_price = 0;
		$assoc_split_arr1 = explode ('~',$order_detail_rs->associated_items);
	
 	 	$order_detail_rs->item_title=$this->replaceSpecial($order_detail_rs->item_title);
	    $order_detail_rs->special_notes=$this->replaceSpecial($order_detail_rs->special_notes);
		
	 	for($j = 0; $j< count($assoc_split_arr1 ); $j++ ) 
		{
			$assoc_split_arr2 = explode ('|',$assoc_split_arr1[$j] );
			if(count($assoc_split_arr2)>1)
			{
 				$associated_items_price += $assoc_split_arr2[1];
			}		 
		}

		$extra_split_arr1 = explode ('~',$order_detail_rs->extra);
 
	 	for($k = 0; $k< count($extra_split_arr1 ); $k++ ) 
		{
			$extra_split_arr2 = explode ('|',$extra_split_arr1[$k] );
			if(count($extra_split_arr2)>1)
			{
		 		$extra_items_price += $extra_split_arr2[1];
			}
		}
 
		$order_detail_rs->item_total_price  =($order_detail_rs->item_price  + $extra_items_price + $associated_items_price)  * $order_detail_rs->item_qty;
		$order_detail_rs->extra=ltrim(str_replace('~','|',str_replace('|','=',$order_detail_rs->extra)),"|");
		$order_detail_rs->associated_items=ltrim(str_replace('~','|',str_replace('|','=',$order_detail_rs->associated_items)),"|");
		$product_rs[$index]=$order_detail_rs;
		$index++;
	}
	//maake associative array
	$order_rs['DesiredDeliveryDate']=str_replace("as soon as possible", date("H:i",strtotime($order_rs['submit_time'])),strtolower($order_rs['DesiredDeliveryDate']));
	$arr_Date=explode("-",$order_rs['DesiredDeliveryDate']);
	$arr_Time=explode(" ",$arr_Date[2]);

	$mTmpAdd = "";
	$mTmpCity = "";
	$mTmpState = "";
	$mTmpZip = "";
	if (strpos($order_rs['DeliveryAddress'], ","))
	{
		$mTmp = explode(",", $order_rs['DeliveryAddress']);
		$mTmpAdd = trim($mTmp[0]);
		$mTmpCity = trim($mTmp[1]);
		if (count($mTmp)>2)
		{
			$mTmpState = trim($mTmp[2]);
			if (count($mTmp)>3)
			{
				$mTmpZip= trim($mTmp[3]);
			}
		}
	}
   //  echo "<pre>";print_r($arr_Date);echo "</pre>";
	
	$order["web-app"]=array( 
					  "app_id"=>"ewo0001", 
					  "auth_id"=>"105fdb19-1fc9-4c81-8bbd-61427912f83c",  
					  "app_key"=>"8346-a056344a2699"
	
	);
	
	$order["ORDERINFO"]=array(
					"delivery_method"  => $order_rs['order_receiving_method'],
					"delivery_time_reqested"  =>$arr_Time[1],
					"delivery_date_requested"  =>date("Y")."-".$arr_Date[0]."-".$arr_Date[1],
					"time_of_order"  => date("H:i",strtotime($order_rs['submit_time'])),
					"date_of_order"  => date("Y-m-d",strtotime($order_rs['submit_time'])),
					"asap_order" =>($order_rs['asap_order']==1?"True":"False"),
					"transaction_type"    => $order_rs['payment_method'],
					"delivery_instructions" =>  $this->replaceSpecial($order_rs['DelSpecialReq']) ,
					"delivery_address" => $this->replaceSpecial($order_rs['DeliveryAddress']), 
					"street" => $this->replaceSpecial($mTmpAdd), 
					"city" => $this->replaceSpecial($mTmpCity), 
					"state" => $this->replaceSpecial($mTmpState), 
					"zip" => $this->replaceSpecial($mTmpZip), 
					"customer_name"=>$this->replaceSpecial($cust_rs['cust_your_name']), 
					"customer_phone" => $cust_rs['cust_phone1'],  
					"special_instructions" =>  $this->replaceSpecial($order_rs['DelSpecialReq']) ,
			);	
				
					
	$phonechars = array("(", ")", "-", " ");      

	$mTmpPhone = "";
	
	if ($mPhone!="")
	{
		$mTmpPhone = $mPhone;
	}
	else
	{
		$mTmpPhone = $rest_rs['phone'];
	}
	
	$order["CALLINFO"]=array( 
			"phone_number"=> str_replace($phonechars, "",$mTmpPhone), 
			"call_delay"=>"300",  
			"max_retries"=>"5", 
			"retry_delay"=>"300", 
			"wait_time"=>"45"
	);
	
	$order["FAXINFO"]=array( 
		 	 "fax_status"=> $faxstatus=="1" ? "sent":"not sent",	
			 "fax_sent_date"=>date("Y-m-d",strtotime($order_rs['fax_date'])),
	);

	$order["ORDERS"]=array(
				"order_id"=>$order_rs['OrderID'], 
				"items" => $product_rs
	);
								
   	if($order_rs['coupon_discount']=null)
   	{
		$order_rs['coupon_discount']=0;
   	}

	$sub_total = $order_rs['total'] - ($order_rs['coupon_discount']+$order_rs['driver_tip']+$order_rs['delivery_chagres']+$order_rs['Tax']);
	
	$order["TOTAL"]=array(
				"sub_total"=>number_format($sub_total,2), 
				"coupon_discount"=>number_format($order_rs['coupon_discount'],2),
				"driver_charge"=>number_format($order_rs['driver_tip'],2), 
				"delivery_charges"=>number_format($order_rs['delivery_chagres'],2),
				"total"=>number_format($order_rs['total'],2), 
				"tax"=>number_format($order_rs['Tax'],2), 
				"tip_amt"=>$order_rs['driver_tip'], 
				"PAYMENT"=>$order_rs['total']
	
	);
 
	$url="http://74.85.240.60:8080/easywayordering";

	$json_sent=1;

	try 
	{
		if($rest_rs['phone_notification'] == 1) //If phone notifications are ON then send JSON to phone server.
		{
    		$this->do_post_request($url,json_encode ($order),"");
		}
	}
	catch (Exception $e)  
	{
			$json_sent=0;	
			//	echo "<pre>";print_r($e);echo "</pre>"; 
	}
	$qry= "UPDATE ordertbl SET pay_load_json='". mysql_escape_string(json_encode($order)) ."' , json_sent=". $json_sent .", faxid=".$faxid." WHERE OrderID=".$orderId;
	mysql_query ($qry);
}
	
function posttoORDRSRVR($orderId) 
{
	$qryresult =  mysql_query("SELECT pos_json,pos_json_sent FROM ordertbl WHERE OrderID=".$orderId);
	$pos_jason_result=mysql_fetch_object($qryresult);
	if($pos_jason_result->pos_json_sent==0)  
	{
		$order_qry = mysql_query("SELECT OrderID, Totel as total,coupon_discount, driver_tip,delivery_chagres,Tax,UserID,order_receiving_method,DesiredDeliveryDate,
								submit_time,asap_order,payment_method,fax_sent,fax_date,DelSpecialReq,DeliveryAddress,cat_id,OrderDate,Approve,payment_approv,coupons,order_confirm,est_delivery_time,vip_discount,transaction_id,refund_request,is_guest,platform_used  FROM ordertbl WHERE OrderID =".$orderId);
	
		$order_rs  = mysql_fetch_assoc($order_qry);


		$order_detail_qry = mysql_query("SELECT item_title , 0 as item_total_price,quantity as item_qty,prd.retail_price as item_price,RequestNote as special_notes,extra, pid as item_id ,associations as associated_items, item_for,prd.pos_id
										 FROM orderdetails  ord Inner join product prd
										 on ord.pid=prd.prd_id
										 WHERE orderid = ". $order_rs['OrderID'] ."");


		$cust_qry = mysql_query("SELECT cust_your_name, LastName ,cust_phone1  FROM customer_registration WHERE  id = ". $order_rs['UserID'] ."	");
		$cust_rs  = mysql_fetch_assoc($cust_qry );
 
 
		$rest_qry = mysql_query("SELECT id as restid, name as restname, phone, phone_notification  FROM resturants WHERE  id = ". $order_rs['cat_id'] ."	");
		$rest_rs  = mysql_fetch_assoc($rest_qry );
 
		$associated_items_price = 0;
		$extra_items_price = 0;
		$index=1;
	
		while ( $order_detail_rs  = mysql_fetch_object($order_detail_qry)) 
		{
			$associated_items_price = 0;
			$extra_items_price = 0;
			$assoc_split_arr1 = explode ('~',$order_detail_rs->associated_items);
		
			$order_detail_rs->item_title=$this->replaceSpecial($order_detail_rs->item_title);
			$order_detail_rs->special_notes=$this->replaceSpecial($order_detail_rs->special_notes);
 
			for($j = 0; $j< count($assoc_split_arr1 ); $j++ ) 
			{
				$assoc_split_arr2 = explode ('|',$assoc_split_arr1[$j] );
				if(count($assoc_split_arr2)>1)
				{
 					$associated_items_price += $assoc_split_arr2[1];
				}
			}

			$extra_split_arr1 = explode ('~',$order_detail_rs->extra);
 	 
			for($k = 0; $k< count($extra_split_arr1 ); $k++ ) 
			{
				$extra_split_arr2 = explode ('|',$extra_split_arr1[$k] );
				if(count($extra_split_arr2)>1)
				{
					$extra_items_price += $extra_split_arr2[1];
				}
			}
 
			$order_detail_rs->item_total_price  =($order_detail_rs->item_price  + $extra_items_price + $associated_items_price)  * $order_detail_rs->item_qty;

			$order_detail_rs->extra=ltrim(str_replace('~','|',str_replace('|','=',$order_detail_rs->extra)),"|");
			$order_detail_rs->associated_items=ltrim(str_replace('~','|',str_replace('|','=',$order_detail_rs->associated_items)),"|");
			$product_rs[$index]=$order_detail_rs;
			$index++;
		}
		$order_rs['DesiredDeliveryDate']=str_replace("as soon as possible", date("H:i",strtotime($order_rs['submit_time'])),strtolower($order_rs['DesiredDeliveryDate']));
		$arr_Date=explode("-",$order_rs['DesiredDeliveryDate']);
		$arr_Time=explode(" ",$arr_Date[2]);
		
		$mTmpAdd = "";
		$mTmpCity = "";
		$mTmpState = "";
		$mTmpZip = "";

		if (strpos($order_rs['DeliveryAddress'], ","))
		{
			$mTmp = explode(",", $order_rs['DeliveryAddress']);
			$mTmpAdd = trim($mTmp[0]);
			$mTmpCity = trim($mTmp[1]);
			if (count($mTmp)>2)
			{
				$mTmpState = trim($mTmp[2]);
				if (count($mTmp)>3)
				{
					$mTmpZip= trim($mTmp[3]);
				}
			}
		}

 		$order["web-app"]=array( 
					  "app_id"=>"ewo0001", 
					  "auth_id"=>"105fdb19-1fc9-4c81-8bbd-61427912f83c",  
					  "app_key"=>"8346-a056344a2699"
	
		);

		$order["ORDERINFO"]=array(
					"OrderID"  => $order_rs['OrderID'],
					"Total"  => $order_rs['total'],
					"UserID"  => $order_rs['UserID'],
					"delivery_method"  => $order_rs['order_receiving_method'],
					"delivery_time_reqested"  =>$arr_Time[1],
					"delivery_date_requested"  =>date("Y")."-".$arr_Date[0]."-".$arr_Date[1],
					"time_of_order"  => date("H:i",strtotime($order_rs['submit_time'])),
					"date_of_order"  => date("Y-m-d",strtotime($order_rs['submit_time'])),
				 	"asap_order" =>$order_rs['asap_order'],
					"transaction_type"    => $order_rs['payment_method'],
					"delivery_instructions" =>  $this->replaceSpecial($order_rs['DelSpecialReq']) ,
					"delivery_address" => $this->replaceSpecial($order_rs['DeliveryAddress']), 
					"street" => $this->replaceSpecial($mTmpAdd), 
					"city" => $this->replaceSpecial($mTmpCity), 
					"state" => $this->replaceSpecial($mTmpState), 
					"zip" => $this->replaceSpecial($mTmpZip), 
					"customer_name"=>$this->replaceSpecial($cust_rs['cust_your_name']), 
					"customer_phone" => $cust_rs['cust_phone1'],  
					"special_instructions" => $this->replaceSpecial($order_rs['DelSpecialReq']),
					"cat_id" => $order_rs['cat_id'],
					"payment_approv" => $order_rs['payment_approv'],
					"coupons" => $order_rs['coupons'],
					"order_confirm" => $order_rs['order_confirm'],
					"est_delivery_time" => $order_rs['est_delivery_time'],
					"vip_discount" => $order_rs['vip_discount'],
					"transaction_id" => $order_rs['transaction_id'],
					"refund_request" => $order_rs['refund_request'],
					"platform_used" => $order_rs['platform_used'],
					"is_guest" => $order_rs['is_guest']
			);	
				
					
  		$phonechars = array("(", ")", "-", " ");      

		$order["CALLINFO"]=array( 
		  	"phone_number"=> str_replace($phonechars, "",$rest_rs['phone']), 
		  	"call_delay"=>"300",  
		  	"max_retries"=>"5", 
		  	"retry_delay"=>"300", 
		  	"wait_time"=>"45",
		  	"restid"=> $rest_rs['restid'],
		  	"restname" =>$rest_rs['restname']
		);
 
		$order["FAXINFO"]=array( 
			"fax_status"=> $faxstatus=="1" ? "sent":"not sent",	
			"fax_sent_date"=>date("Y-m-d",strtotime($order_rs['fax_date'])),
		);

		$order["ORDERS"]=array(
			"order_id"=>$order_rs['OrderID'], 
			"items" => $product_rs
		);
			
			
		if($order_rs['coupon_discount'] == null)
		{
			$order_rs['coupon_discount']=0;
		}

		$sub_total = $order_rs['total'] - ($order_rs['coupon_discount']+$order_rs['driver_tip']+$order_rs['delivery_chagres']+$order_rs['Tax']);

		$order["TOTAL"]=array(
			"sub_total"=>number_format($sub_total,2), 
			"coupon_discount"=>number_format($order_rs['coupon_discount'],2),
			"driver_charge"=>number_format($order_rs['driver_tip'],2), 
			"delivery_charges"=>number_format($order_rs['delivery_chagres'],2),
			"total"=>number_format($order_rs['total'],2), 
			"tax"=>number_format($order_rs['Tax'],2), 
			"tip_amt"=>$order_rs['driver_tip'], 
			"PAYMENT"=>$order_rs['total']
		);

		$encoded = json_encode($order);
	}
	else 
	{
		$encoded=$pos_jason_result->pos_json;
	}
	
	$mURL="http://www.ordrsrvr.net/index.php";
	$cURL = curl_init();
 
 	curl_setopt($cURL,CURLOPT_URL,$mURL);
 	curl_setopt($cURL,CURLOPT_POST,true);
 	curl_setopt($cURL,CURLOPT_POSTFIELDS,$encoded);
 	curl_setopt($cURL,CURLOPT_RETURNTRANSFER, true); 
 	curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
	$result = curl_exec($cURL);
	$result_info= json_decode($result,true);
 	curl_close($cURL);
	mysql_query("UPDATE ordertbl  set pos_json_sent=". $result_info['Result'].",pos_json='".mysql_escape_string($encoded)."' WHERE OrderID =".$orderId.""); 
}

function replaceSpecial($data) {
	
	$data = trim(preg_replace('/\'/', ' ', $data));
 
	 	
	$data = preg_replace('/\n/', ' ',  $data);
	//remove any double spaces
	$data = trim(preg_replace('/\s{2,}/', ' ', $data));
	

	
	 return $data; 


	}
	
	function sendcall($data){
		   $url="http://74.85.240.60:8080/easywayordering";
		   $this->do_post_request($url,$data,"");
		   
		
 }
function do_post_request($url, $data, $optional_headers = null)
{
	 
 
  $params = array('http' => array(
              'method' => 'POST',
			 'header'  => 'Content-type:  application/json',
              'content' => $data
            ));
  if ($optional_headers !== null) {
    $params['http']['header'] = $optional_headers;
  }
  $ctx = stream_context_create($params);
  $fp = @fopen($url, 'rb', false, $ctx);
  if (!$fp) {
      throw new Exception("Problem with $url, $php_errormsg");
  }
  $response = @stream_get_contents($fp);
  
  if(isset($_GET['id'])){
	  echo "<pre>"; print_r($data); echo "</pre>";
	  echo "<pre>"; print_r($response); echo "</pre>";
	  }

  if ($response === false) {
    throw new Exception("Problem reading data from $url, $php_errormsg");
  }
  return $response;
}


function writePdf($arrCustomer,$arrRestaurant,$arrOrder,$arrSummary,$pdfHeaderImage,$orderId) 
{
		$mRegion = 1;
	
		$mRes =	mysql_query("SELECT IFNULL(R.region, 1) AS Rest_Region FROM resturants R INNER JOIN customer_registration CR ON CR.resturant_id = R.id INNER JOIN ordertbl OT ON OT.UserID = CR.id WHERE OT.OrderID = ".$orderId);
		if (mysql_num_rows($mRes)>0)
		{
			$mRow = mysql_fetch_array($mRes);
			$mRegion = $mRow["Rest_Region"];
		}
	
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(8.5, 11), true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Easywayordering');
		$pdf->SetTitle('Online Order');
		$pdf->SetSubject('Online Order Fax ');
		$pdf->SetKeywords('Easywayordering, Order, Online, fax');
		
		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE .$title.' ', PDF_HEADER_STRING);
		
		// set header and footer fonts
	/*	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	*/	
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		 
		
		// ---------------------------------------------------------
		// set font
		//$pdf->SetFont('Arial', '', 8);
		
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		
		// add a page
		$pdf->AddPage();
		
		
		$Template=file_get_contents("includes/pdftemplate/main.html");
		
				
		if ($mRegion == "2") //Canada
		{
			$CustomerTemplate=file_get_contents("includes/pdftemplate/customerinfo1.html");		
		}
		else
		{
			$CustomerTemplate=file_get_contents("includes/pdftemplate/customerinfo.html");
		}

		$RestaurantTemplate=file_get_contents("includes/pdftemplate/restaurantinfo.html");
		$OrderTemplate=file_get_contents("includes/pdftemplate/order.html");
		$OrderSummaryTemplate=file_get_contents("includes/pdftemplate/ordertotal.html");
		
		$CustomerTemplate=$this->generateTemplate($arrCustomer,$CustomerTemplate);
		
		 
		$RestaurantTemplate=$this->generateTemplate($arrRestaurant,$RestaurantTemplate);
		$OrderTemplate=$this->generateTemplate($arrOrder,$OrderTemplate);
		$OrderSummaryTemplate=$this->generateTemplate($arrSummary,$OrderSummaryTemplate);
				
		
		$Template =  str_replace("[CUSTOMERINFO]",$CustomerTemplate,$Template);
		$Template =  str_replace("[RESTAURANTINFO]",$RestaurantTemplate,$Template);
		$Template =	str_replace("[ORDER]",$OrderTemplate,$Template);
		$Template =	str_replace("[ORDERTOTAL]",$OrderSummaryTemplate,$Template);
		if($pdfHeaderImage!="")
			$pdfHeaderImage= '<img  src="images/resturant_headers/'.$pdfHeaderImage .'"  height="2in;"  width="10in"/>';
			
		$Template =	str_replace("[HEADER_IMAGE]",$pdfHeaderImage,$Template);
		// output the HTML content
		$pdf->writeHTML($Template, true,  false, true, false, '');		
		//Close and output PDF document
		$pdf->Output('pdffiles/pdf'.$orderId.'.pdf', 'F');
	
	
	
	 
		}
		function generateTemplate($arrData,$Template) {
			$result='';
			$loopedresult=0;
			
			foreach($arrData as $key=>$value) {
				if(is_array($value)){
					$parsedTemplate= $Template;
					
					foreach($value as $subkey=>$subvalue) {
						$parsedTemplate = str_replace("[". $subkey ."]",$subvalue,$parsedTemplate);
					}
					$result .=$parsedTemplate;
					$loopedresult=1;
				}
				else{
			 		$Template = str_replace("[". $key ."]",$value,$Template);
				}
			}
			 if($loopedresult==1) $Template=$result;
			 
			 			return $Template;
	}//WRITE PDF
	
	
		function encrypt($string, $key) {
  $result = '';
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)+ord($keychar));
    $result.=$char;
  }
  return base64_encode($result);
			}
			function decrypt($string, $key) {
		  $result = '';
		  $string = base64_decode($string);
		  for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		  }
		  return $result;
		}

} //Class End 

function isJSON($pString)
{
	if (!is_string($pString)) 
	{
		return false;
	}
 
	$string = trim($pString);
 
	$firstChar = substr($pString, 0, 1);
	$lastChar = substr($pString, -1);
 
	if (!$firstChar || !$lastChar) 
	{
		return false;
	}
 
	if (($firstChar !== '{') && ($firstChar !== '['))
	{
		return false;
	}
 
	if (($lastChar !== '}') && ($lastChar !== ']')) 
	{
		return false;
	}
	
	return true;
}

if (!function_exists('json_encode')) {
    function json_encode($data) {
        switch ($type = gettype($data)) {
            case 'NULL':
                return 'null';
            case 'boolean':
                return ($data ? 'true' : 'false');
            case 'integer':
            case 'double':
            case 'float':
                  return '"' . addslashes($data) . '"';
            case 'string':
                return '"' . addslashes($data) . '"';
            case 'object':
                $data = get_object_vars($data);
            case 'array':
                $output_index_count = 0;
                $output_indexed = array();
                $output_associative = array();
                foreach ($data as $key => $value) {
                    $output_indexed[] = json_encode($value);
                    $output_associative[] = json_encode($key) . ':' . json_encode($value);
                    if ($output_index_count !== NULL && $output_index_count++ !== $key) {
                        $output_index_count = NULL;
                    }
                }
                if ($output_index_count !== NULL) {
                    return '[' . implode(',', $output_indexed) . ']';
                } else {
                    return '{' . implode(',', $output_associative) . '}';
                }
            default:
                return ''; // Not supported
        }
    }
	
	

}

if(!function_exists('json_decode'))
{
    function json_decode($json)
    {
        $comment = false;
        $out = '$x=';
        for ($i=0; $i<strlen($json); $i++)
        {
            if (!$comment)
            {
                if (($json[$i] == '{') || ($json[$i] == '['))
                    $out .= ' array(';
                else if (($json[$i] == '}') || ($json[$i] == ']'))
                    $out .= ')';
                else if ($json[$i] == ':')
                    $out .= '=>';
                else
                    $out .= $json[$i];
            }
            else
                $out .= $json[$i];
            if ($json[$i] == '"' && $json[($i-1)]!="\\")
                $comment = !$comment;
        }
        eval($out . ';');
        return $x;
    }
}

class testmail  {
	
		public $MAIL_HOST_NAME="secure.emailsrvr.com";
		public $from;
			 
		public $customermail;
		
				 function __construct() {
							$this->customermail = new PHPMailer();
							$this->customermail->IsSMTP(); // telling the class to use SMTP
							$this->customermail->Host = $this->MAIL_HOST_NAME;// SMTP server
							$this->customermail->Port = 25;// SMTP port
							$this->customermail->SMTPAuth = true;
							$this->customermail->Username = 'orders@easywayordering.com';
							$this->customermail->Password = 'U7yOderEa';	
							$this->customermail->From = "orders@easywayordering.com";
							$this->customermail->FromName="";
							$this->customermail->Sender=""; // indicates ReturnPath header
							$this->customermail->AddAddress("gulfam@qualityclix.com");
							$this->from="";
		
					  }
					  public function send($message,$subject) {
							$this->customermail->IsHTML(true);
							$this->customermail->Subject = $subject;
							$this->customermail->Body = $message;
							$this->customermail->Send();	
						  
						  }
						  
				  public function sendTo($message,$subject,$to,$html=true) {
							$this->customermail->ClearAllRecipients();
							$this->customermail->AddAddress($to);
						//	$this->customermail->AddBCC("irfan@qualityclix.com");
							$this->customermail->IsHTML($html);
							$this->customermail->Subject = $subject;
							$this->customermail->Body = $message;
							if($this->from!="")
								$this->customermail->From = $this->from;
							 
							 $this->customermail->Send();
						 
						  
						  }
						  
						  public function addattachment($attachment){
							
							    $this->customermail->AddAttachment($attachment);
							  }
					  public function addbcc($bcc){
							
							    $this->customermail->AddBCC($bcc);
							  }
							  
							  public function clearattachments() {
								     $this->customermail->ClearAttachments();
								  
								  }
		
	}
function redirect($to){
	echo"<script>window.location='$to';</script>";
	
	}
	
function prepareStringForMySQL($string)
{
    $string=str_replace ( "\r" , "<br/>",$string);
    $string=str_replace ( "\n" , "<br/>",$string);
    $string=str_replace ( "\t" , " ",$string);
    $string=mysql_real_escape_string($string);
    return $string;
}

?>