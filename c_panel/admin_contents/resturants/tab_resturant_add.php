<!--Start script for dependent list-->
<script language="javascript" type="text/javascript">
// Roshan's Ajax dropdown code with php
// This notice must stay intact for legal use
// Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
// If you have any problem contact me at http://roshanbh.com.np
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	function getclients( resellerId, pageName,owner_name,product_id,license_id ) {		
		var milliseconds = (new Date).getTime();
		var strURL="admin_contents/resturants/tab_find_clients.php?resellerId="+resellerId+"&pageName="+pageName+"&owner_name="+owner_name+"&"+milliseconds;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('client_div').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}	
		get_licenses_of_reseller( resellerId,license_id );
                get_product_of_reseller( resellerId,product_id );
	}

        function getCardInfo( ownerId  ) {
            	var milliseconds = (new Date).getTime();
		var strURL="admin_contents/resturants/tab_find_cardInfo.php?ownerId="+ownerId+"&"+milliseconds;
		var req = getXMLHTTP();

		if (req) {

			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
						document.getElementById('card_div').innerHTML=req.responseText;
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
		//get_licenses_of_reseller( resellerId );
	}
	
	function get_licenses_of_reseller(resellerId,license_id) {		
		var milliseconds = (new Date).getTime();
		var strURL="admin_contents/resturants/tab_find_licenses.php?resellerId="+resellerId+"&license_id="+license_id+"&"+milliseconds;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('licenses_div').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
        function get_product_of_reseller(resellerId,product_id) {

		var milliseconds = (new Date).getTime();
		var strURL="admin_contents/resturants/tab_find_product.php?resellerId="+resellerId+"&product_id="+product_id+"&"+milliseconds;
		var req = getXMLHTTP();

		if (req) {

			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
						document.getElementById('product_div').innerHTML=req.responseText;
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
</script>
<!--End script for dependent list-->
<script src="../js/mask.js" type="text/javascript"></script>
<script type="text/javascript">/*<![CDATA[*/// 
   jQuery(function($) {
      //$.mask.definitions['~']='[+-]';
      //$('#date').mask('99/99/9999');
       
	  $('#phone').mask('(999) 999-9999');
	  $('#fax').mask('(999) 999-9999');
      //$('#customer_cell').mask("(999) 999-9999? x99999");
     // $("#tin").mask("99-9999999");
      //$("#ssn").mask("999-99-9999");
    //  $("#product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("You typed the following: "+this.val());}});
     // $("#eyescript").mask("~9.99 ~9.99 999");
   });
// ]]&gt;/*]]>*/</script>
<?	
	//////////////rest_exist variable identify that resturant name already exist or not in the db//////////// 
	$rest_exist = 0;
	$errMessage=''; 
//---------------------------------Start--------------------------------------------------//       
        $cntry=$_POST['region'];
        if($cntry=='0'){$cntry="GB";}else if($cntry=='1'){$cntry="US";} else if($cntry=='2'){$cntry="CA";}else{$cntry="US";}
//        print_r($cntry); echo "  22222";exit;
//---------------------------------End---------------------------------------------------//
	$myimage = new ImageSnapshot; //new instance
	$myimage2 = new ImageSnapshot; //new instance
	if(isset($_FILES['userfile']))
		$myimage->ImageField = $_FILES['userfile']; //uploaded file array
	if(isset($_FILES['userfile2']))
		$myimage2->ImageField = $_FILES['userfile2']; //uploaded file array

	function GetFileExt($fileName) {
		$ext = substr($fileName, strrpos($fileName, '.') + 1);
		$ext = strtolower($ext);
		return $ext;
	}

	if(! empty( $_POST )) {
		extract( $_POST ) ;
	} else if(! empty( $HTTP_POST_VARS )) {
		extract( $HTTP_POST_VARS ) ;
	}

	if(! empty( $_GET )) {
		extract( $_GET ) ;
	} else if(! empty( $HTTP_GET_VARS )) {
		extract( $HTTP_GET_VARS ) ;
	}

	if (isset($_REQUEST['submit'])){
		$restQry=mysql_query("select name from resturants where name='$catname'");
		@$restRs	=	mysql_num_rows($restQry);
		if($restRs > 0) 
			$rest_exist = 1;	

		$errMessage ="";
		
		// check if chargify_subscription_id already taken
		$restRs1 = 0;
		if(!empty($chargify_subscription_id)) {
			$restQry1 = mysql_query("SELECT COUNT(*) AS total FROM resturants WHERE chargify_subscription_id='$chargify_subscription_id' AND id!='$catid'");
			$restRs1	= mysql_fetch_object($restQry1);
			$restRs1 = $restRs1->total;
		}
		

		if ($catname == '') {
			$errMessage="Please enter restaurant name";
		} else if($email == '') {
			$errMessage = "Please enter email address";
		} else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
			$errMessage = "Please enter email address in correct format";
		} else if($phone == '') {
			$errMessage = "Please enter phone number";
		} else if($fax == '') {
			$errMessage = "Please enter fax number";
		} else if($owner_name < 0) {
			$errMessage = "Please select owner's name";
		} else if($license_key < 0) {
			$errMessage = "Please select license key";
		} else if($time_zone < 0) {
			$errMessage = "Please select resturant's time zone";
		} else if($rest_address == '') {
			$errMessage = "Please enter resturant address";
		} else if($rest_state == '') {
			$errMessage = "Please enter resturant city";
		} else if($rest_city == '') {
			$errMessage = "Please enter resturant state";
		}  else if($rest_zip == '') {
			$errMessage = "Please enter resturant zip code";
		}
		else if($product_details < 0) {
			$errMessage = "Please select product";
		}else {
			/* $catname = trim($catname," ");
				$rest_url_name = str_replace(" ","_", $catname);
				$rest_url_name = strtolower($rest_url_name);
			*/
			/*==============================*/
			$characters_arr = array("'", ";", ",", "%", "&", "-" , "$", "#", "@", "~", "`", "/",  "|", '"',"\\\\" ,"\\", " ",":", ".");
			$rest_url_name = str_replace($characters_arr, "_", stripslashes($catname));
			$rest_url_name = strtolower($rest_url_name);
			/*=================================*/
			$homefeatute = 0;	
			if($open_close == ''){ $open_close = 0; }

			if($credit & $cash) {
				$payment_method = "both";
			} else if($credit) {
				$payment_method = "credit";	 
			} else if($cash) {
				$payment_method = "cash"; 
			}else{
                                $payment_method = " ";
                        }

			// Get Chargify Product ID Starts Here // -- Gulfam
			$mChargify_Settings_ID = 0;
                        $mProduct_ID = 0;
			$mResult = mysql_query("SELECT settings_id,product_id FROM chargify_products WHERE user_id=".addslashes($reselelr));
			if (mysql_num_rows($mResult)>0)
			{
				$mRow = mysql_fetch_object($mResult);
				if (is_numeric($mRow->settings_id))
				{
					$mChargify_Settings_ID = $mRow->settings_id;
                                        $mProduct_ID = $mRow->product_id;
				}
			}
                        
                        $chargify_customer_id = mysql_fetch_object(mysql_query("SELECT chargify_customer_id,chargify_subcription_id,email FROM users WHERE id=".addslashes($owner_name)));
                        
//                        if(empty($chargify_customer_id->chargify_subcription_id))
//                        {
//                            $customer_subscription_id = $chargify->createSubcription($product_details ,$chargify_customer_id->chargify_customer_id,$subcription_method,$credit_card_number,$card_no,$exp_date,$exp_year );
//                            mysql_query("update users SET chargify_subcription_id= '".$customer_subscription_id->subscription->id."' where chargify_customer_id = ".$chargify_customer_id->chargify_customer_id."");
//
//                        }
                        
                        $chargify_subscription_id = $chargify->createSubcription($product_details ,$chargify_customer_id->chargify_customer_id,$subcription_method,$credit_card_number,$card_no,$exp_date,$exp_year );
			// Get Chargify Product ID Ends Here // -- Gulfam
                        
                         // ***********Create vendesta Account for restaurant*************//
                            $demoAccountFlag = 'true';
                            $parameters='';
                            $mURL2 ='';
                            $check_premium = mysql_fetch_object(mysql_query("select premium_account from chargify_products where product_id = $product_details and user_id=".$reselelr.""));
                            $premium = $check_premium->premium_account;

                            $parameters = "address=".$rest_address."&city=".$rest_city."&companyName=".$catname."&country=".$cntry."&state=".$rest_state."&zip=".$rest_zip."&email=".$chargify_customer_id->email;
                            if($premium == 0)
                            {
                                $parameters = $parameters."&demoAccountFlag=".$demoAccountFlag."&salesPersonEmail=cwilliams@easywayordering.com";

                                $mURL2 = "https://reputation-intelligence-api.vendasta.com/api/v2/account/create/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1";
                                $ch2 = curl_init();
                                curl_setopt($ch2, CURLOPT_URL, $mURL2);
                                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch2, CURLOPT_POST, 1);
                                curl_setopt($ch2, CURLOPT_POSTFIELDS, $parameters);

                                $mResult2 = curl_exec($ch2);
                                curl_close($ch2);
                                unset($ch2);
                                $mResult2= json_decode($mResult2);
                                //print_r($mResult2);
                                $mResult2 = (object) $mResult2;
                                
                                $mResult2->data = (object) $mResult2->data;
                                $data = $mResult2->data;
                                $getSrid = $data->srid;
                                //mysql_query("UPDATE resturants SET srid='".$getSrid."' where id = $catid");


                            }

                            else if($premium == 1)
                            {
                                $demoAccountFlag = "false";
                                $parameters = $parameters."&workNumber=".$phone."&demoAccountFlag=".$demoAccountFlag."&salesPersonEmail=cwilliams@easywayordering.com";

                                $mURL2 = "https://reputation-intelligence-api.vendasta.com/api/v2/account/create/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1";
                                $ch2 = curl_init();
                                curl_setopt($ch2, CURLOPT_URL, $mURL2);
                                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch2, CURLOPT_POST, 1);
                                curl_setopt($ch2, CURLOPT_POSTFIELDS, $parameters);

                                $mResult2 = curl_exec($ch2);
                                curl_close($ch2);
                                unset($ch2);
                                $mResult2= json_decode($mResult2);
                                //print_r($mResult2);
                                $mResult2 = (object) $mResult2;

                                $mResult2->data = (object) $mResult2->data;
                                $data = $mResult2->data;
                                $getSrid = $data->srid;
                                
                               // mysql_query("UPDATE resturants SET srid='".$getSrid."' where id = $catid");
                            }
			

                            //************End Vedesta Account**********************//
			
			
                        if(!empty($getSrid))
                        {
                        if(!empty($chargify_subscription_id->subscription))
                        {
                            	$credit_card_info = $chargify_subscription_id->subscription;
                                if(!empty($credit_card_info->credit_card->id))
                                {
                                    $check_card_data_Qry = mysql_fetch_object(mysql_query("Select * from chargify_payment_method where chargify_customer_id = '".$chargify_customer_id->chargify_customer_id."' and card_number='".$credit_card_info->credit_card->masked_card_number."'"));
                                    if(empty($check_card_data_Qry))
                                    {
                                        mysql_query(
                                                "INSERT INTO chargify_payment_method
                                                SET user_id= '".addslashes($owner_name)."'
                                                        ,chargify_customer_id= '".addslashes($chargify_customer_id->chargify_customer_id)."'
                                                        ,Payment_profile_id='".addslashes($credit_card_info->credit_card->id)."'
                                                        ,card_number='".$credit_card_info->credit_card->masked_card_number."'"
                                        );
                                    }
                                }				
			    $check_product_premium = mysql_fetch_object(mysql_query("Select premium_account from chargify_products where product_id = ".$product_details." and user_id = ".$reselelr.""));
                            
	
			    $reseller_chargify_id = mysql_fetch_object(mysql_query("SELECT chargify_subcription_id FROM users WHERE id=".addslashes($reselelr)));
                              $quantity = 0;  
				$quantity = $chargify->getallocationQuantity($reseller_chargify_id->chargify_subcription_id,$check_product_premium->premium_account);
                                //if(!empty($quantity))
                                //{   	
                                    $quantity = $quantity+1;
                                    $chargify->allocationQuantity($reseller_chargify_id->chargify_subcription_id,$quantity,$check_product_premium->premium_account,'activate');
                                //}
	
			     $queryInsertRestaurant = "INSERT INTO resturants
                                    SET name= '".prepareStringForMySQL($catname)."'
                                            ,url_name= '".prepareStringForMySQL($rest_url_name)."'
                                            ,owner_id='".prepareStringForMySQL($owner_name)."'
                                            ,license_id='".$license_key."'
					    ,status = '1'
                                            ,email='".prepareStringForMySQL($email)."'
                                            ,fax='".prepareStringForMySQL($fax)."'
                                            ,phone='".prepareStringForMySQL($phone)."'
                                            ,rest_open_close = '1'
                                            ,delivery_offer = '0'
					    ,order_minimum = '".$order_minimum."'
                                            ,payment_method='".prepareStringForMySQL($payment_method)."'
                                            ,time_zone_id='".prepareStringForMySQL($time_zone)."'
                                            ,rest_address= '".prepareStringForMySQL($rest_address)."'
                                            ,rest_city= '".prepareStringForMySQL($rest_city)."'
                                            ,rest_state= '".prepareStringForMySQL($rest_state)."'
                                            ,rest_zip= '".prepareStringForMySQL($rest_zip)."'
					    ,premium_account = '".$check_product_premium->premium_account."'
                                            ,chargify_subscription_id='".$chargify_subscription_id->subscription->id."'
                                            ,region='" . prepareStringForMySQL(trim($region)) . "'
					    ,srid = '".$getSrid."'";
			    mysql_query($queryInsertRestaurant);
				Log::write("Adding restaurant - tab_resturant_add.php", "QUERY --".$queryInsertRestaurant , 'menu', 1 , 'cpanel');

                            $catid = mysql_insert_id();
				if($catid>0)
				{
					if (isset($optionallogo))
					{
						if (trim($optionallogo)!="")
						{
							if (strpos(trim(strtolower($optionallogo)), "NIA.jpg")===false)
							{
								$mImageURL = $optionallogo;
								$mImageName = "img_".$catid."_cat_thumbnail.jpg";
								$mPath = '../images/logos_thumbnail/'.$mImageName;
								@file_put_contents($mPath, file_get_contents($mImageURL));
					            @list($width, $height, $type, $attr) = getimagesize($mPath);
								
								if ($height > $width) {
									@$image = new SimpleImage();
									@$image->load($mImageURL);
									@$image->resizeToHeight(500);
									@$image->save($mPath);
								} else {
									@$image = new SimpleImage();
									@$image->load($mImageURL);
									@$image->resizeToWidth(600);
									@$image->save($mPath);
								}
								
								mysql_query("UPDATE resturants SET optionl_logo='".$mImageName."' WHERE id=".$catid);
							}
						}
					}

					$mImageStr = "";
					if ((trim($mImageName)!="") && (trim($mImageName)!="NIA.jpg"))
					{
						$mImageStr = " optionl_logo='".$mImageName."', ";
					}
					
					$queryInsertRestaurantAnalytics = 	"INSERT INTO `analytics` SET
					resturant_id = ".$catid.",
                                	first_letter = '".strtoupper(substr($catname,0,1))."',
                                	name='".prepareStringForMySQL($catname)."',
                                	url_name='".prepareStringForMySQL($rest_url_name)."',
                                	status='1',
                                	orders_last_month_count='0', ".$mImageStr."
                                	orders_last_but_second_month_count='0'";												 
	                            mysql_query($queryInsertRestaurantAnalytics);
					Log::write("Adding restaurant - tab_resturant_add.php", "QUERY --".$queryInsertRestaurantAnalytics , 'menu', 1 , 'cpanel');
                                    
/*-------------------- Insert Query For Main Menu And Sub Menu  ----------------------------------------*/
                                    mysql_query("INSERT INTO menus SET rest_id= ".$catid.", menu_name= '" . "Main Menu" . "', menu_ordering= '" . "0" . "', menu_desc= '" . "Menu Description" . "', status= 1");
                                    $menuid = mysql_insert_id();
                                    mysql_query("INSERT INTO categories SET parent_id= ".$catid.", menu_id= ". $menuid .", cat_name= '" . "Sub Menu Category" . "', cat_ordering= 1, cat_des= '" . "Sub Menu Description" . "'");
/*------------------------------------------------------------------------------------------------------*/
                                        
                                   
					for($j = 0; $j< 7; $j++) {
						//hour and minutes are treaded as string
						$open_time =  '0800';
						$close_time = '1700';
										Log::write("Add restaurant business hours - tab_resturant_add.php", "QUERY --INSERT INTO business_hours 
							SET rest_id = '".$catid."'
								,day= '".$j."'
								,open='$open_time'
								,close='$close_time'", 'menu', 1 , 'cpanel');
						mysql_query(
							"INSERT INTO business_hours 
							SET rest_id = '".$catid."'
								,day= '".$j."'
								,open='$open_time'
								,close='$close_time'"
						);
					}
				}
			//When resturant has been created then the granted license status will become activated.
			mysql_query("UPDATE licenses SET status= 'activated',resturant_id=".$catid.", activation_date= '".time()."' where id =".$license_key);
			mysql_query("UPDATE reseller_client SET reseller_client.firstname=(SELECT firstname FROM users where users.id = ".$owner_name."),reseller_client.lastname=(SELECT lastname FROM users where users.id = ".$owner_name."),reseller_client.restaurant_count=(SELECT count(name) FROM resturants where resturants.owner_id = ".$owner_name.") Where reseller_client.client_id=".$owner_name);

			if($rest_exist) {
				$rest_url_name = $rest_url_name.$catid;
				mysql_query("UPDATE resturants SET url_name= '$rest_url_name' where id =".$catid);
				mysql_query("UPDATE analytics SET url_name= '$rest_url_name' where resturant_id =".$catid);
			}

			/////////////////////////Get site owner's email address to send email with resturant URL//////////////////////////////////
			$rest_owner_query = mysql_query("SELECT email FROM users WHERE id ='".$owner_name."'");
			$rest_owner_row = mysql_fetch_row($rest_owner_query);
			$rest_owner_email = $rest_owner_row[0];  

			$rest_urlname_query = mysql_query("SELECT url_name FROM resturants WHERE id ='".$catid."'");
			$rest_urlname_row = mysql_fetch_row($rest_urlname_query);
			$rest_url = $rest_urlname_row[0]; 

			//$from = "From:onlineorder_admin@onlineorder.com";
			$from = "From:qasim@qualityclix.com";
			$subject = "Resturant Created";
			$body="";
			$body=$body."Your Resturant has been created. Please find the URL below.<br><br>";

			$body=$body."onlineorder.qualityclix.com/".$rest_url;
			if($rest_owner_email != ""){
			//$function_obj->Send_Mail($email,$subject,$body,$from);
			}
			/////////////////////////////////////////////////////////////////////////////////////////////////////
?>
			<script language="javascript">
				window.location="./?mod=resturant&item=main";
			</script>
<?
                        }
                        else
                            {
                                $errorMsg = $chargify_subscription_id['errors'];
                                $errMessage =  $errorMsg[0];
                            }
                        }
                        else
                        {
                                $chargify->cancelSubcriptionByRestowner($chargify_subscription_id->subscription->id);
                                $errorMsg = $mResult2->message;
                                $errMessage =  $errorMsg;
                        }

                } //end else

	} //end submit if
        if (isset($_POST["btnSearch"]))
        {

         $mResultDiv = " style='display: none;' ";
        $mdvTwenty = " style='display: block;' ";
        $mRestaurantID = -1;
        $mDupError = "";
        $mDupShowHide = " style='display: none;' ";
	$mRestaurant = $_POST["txtRestaurant"];
	$mCityStateZip = $_POST["txtCSZ"];
	$mCountry = $_POST["ddlCountry"];

	$mURL = "http://maps.google.com/maps/api/geocode/json?address=".$mCityStateZip."&sensor=false&region=".$mCountry;
	$mURL = str_replace(" ", "%20", $mURL);
	$mResponse = file_get_contents($mURL);
	$mResponse = json_decode($mResponse, true);
	$mLat = $mResponse["results"][0]["geometry"]["location"]["lat"];
	$mLong = $mResponse["results"][0]["geometry"]["location"]["lng"];
	$mGoogleAPIKey = "AIzaSyBOYImEs38uinA8zuHZo-Q9VnKAW3dSrgo";
	/*Time Zone Code Starts Here*/ //Gulfam - 16 August 2014
	$mURL = "https://maps.googleapis.com/maps/api/timezone/json?location=".$mLat.",".$mLong."&timestamp=".time();
	$mURL = str_replace(" ", "%20", $mURL);
	$mResponse = file_get_contents($mURL);
	$mResponse = json_decode($mResponse, true);
	$mTimeZoneSelect = "Select Time Zone";
	if (isset($mResponse['timeZoneName']))
	{
		$mTimeZone = trim(strtolower($mResponse['timeZoneName']));
		
		if ($mTimeZone!="")
		{
			if (trim(strtolower($mCountry))=="us")
			{
				if (strpos($mTimeZone, "eastern")>=0)
				{
					$mTimeZoneSelect = "US/Eastern";
				}
				else if (strpos($mTimeZone, "hawaii")>=0)
				{
					$mTimeZoneSelect = "US/Hawaii";
				}
				else if (strpos($mTimeZone, "alaska")>=0)
				{
					$mTimeZoneSelect = "US/Alaska";
				}
				else if (strpos($mTimeZone, "pacific")>=0)
				{
					$mTimeZoneSelect = "US/Pacific";
				}
				else if (strpos($mTimeZone, "mountain")>=0)
				{
					$mTimeZoneSelect = "US/Mountain";
				}
				else if (strpos($mTimeZone, "central")>=0)
				{
					$mTimeZoneSelect = "US/Central";
				}
			}
			else if (trim(strtolower($mCountry))=="canada")
			{
				if (strpos($mTimeZone, "pacific")>=0)
				{
					$mTimeZoneSelect = "Canada/Pacific";
				}
				else if (strpos($mTimeZone, "central")>=0)
				{
					$mTimeZoneSelect = "Canada/Central";
				}
				else if (strpos($mTimeZone, "mountain")>=0)
				{
					$mTimeZoneSelect = "Canada/Mountain";
				}
				else if (strpos($mTimeZone, "eastern")>=0)
				{
					$mTimeZoneSelect = "Canada/Eastern";
				}
				else if (strpos($mTimeZone, "atlantic")>=0)
				{
					$mTimeZoneSelect = "Canada/Atlantic";
				}
				else if (strpos($mTimeZone, "newfoundland")>=0)
				{
					$mTimeZoneSelect = "Canada/Newfoundland";
				}
			}
			else if (trim(strtolower($mCountry))=="uk")
			{
				if (strpos($mTimeZone, "british")>=0)
				{
					$mTimeZoneSelect = "Europe/London";
				}
			}
		}
	}
	/*Time Zone Code Ends Here*/ //Gulfam - 16 August 2014
         $mURL = "https://maps.googleapis.com/maps/api/place/search/json?location=".$mLat.",".$mLong."&rankby=distance&types=establishment&name=".$mRestaurant."&sensor=false&key=".$mGoogleAPIKey;
        
         $mURL = str_replace(" ", "%20", $mURL);

         $mResponse = file_get_contents($mURL);
         $mResponse = json_decode($mResponse, true);
         
         $mTotalCount = count($mResponse['results']);

         if ($mTotalCount>0)
         {
          if ($mTotalCount>2)
          {
           $loopMax = 3;
          }
          else
          {
           $loopMax = $mTotalCount;
          }


          for($loopCount=0; $loopCount<$loopMax; $loopCount++)
          {
           $mRestDet[$loopCount]["DivShowHide"] = " style='display: inline;' ";
           $mURL = "https://maps.googleapis.com/maps/api/place/details/json?reference=".$mResponse["results"][$loopCount]["reference"]."&sensor=false&key=".$mGoogleAPIKey;

           $mURL = str_replace(" ", "%20", $mURL);
           $mResponseDet = file_get_contents($mURL);
           $mResponseDet = json_decode($mResponseDet, true);

           $mAddress_components = $mResponseDet['result']['address_components'];
		   /*echo("<pre>");
           print_r($mAddress_components);*/
           $mAddress = array();
           foreach ($mAddress_components as $components)
           {
            $mAddress[$components['types'][0]] = array ('short_name' => $components['short_name'], 'long_name' =>  $components['long_name']);
           }

           $mRestDet[$loopCount]['street_number'] = (($mAddress['street_number']['long_name'] != 'false') && ($mAddress['street_number']['long_name'] != '')) ? $mAddress['street_number']['long_name'] : '';
           $mRestDet[$loopCount]['street_name'] = (($mAddress['route']['long_name'] != 'false') && ($mAddress['route']['long_name'] != '')) ? $mAddress['route']['long_name'] : '';

           if (($mRestDet[$loopCount]['street_number']=='') && ($mRestDet[$loopCount]['street_name']==''))
           {
            $mRestDet[$loopCount]['street_number'] = substr($mResponseDet["result"]["formatted_address"], 0, strpos($mResponseDet["result"]["formatted_address"], ","));
           }

           $mRestDet[$loopCount]['city'] = (($mAddress['locality']['long_name'] != 'false') && ($mAddress['locality']['long_name'] != '')) ? $mAddress['locality']['long_name'] : '';
           if (trim($mRestDet[$loopCount]['city'])=='')
           {
            $mRestDet[$loopCount]['city'] = (($mAddress['postal_town']['long_name'] != 'false') && ($mAddress['postal_town']['long_name'] != '')) ? $mAddress['postal_town']['long_name'] : '';
           }
		   
		   if (trim($mRestDet[$loopCount]['city'])=='')
           {
            $mRestDet[$loopCount]['city'] = (($mAddress['sublocality']['long_name'] != 'false') && ($mAddress['sublocality']['long_name'] != '')) ? $mAddress['sublocality']['long_name'] : '';
           }
		   
		   if (trim($mRestDet[$loopCount]['city'])=='')
           {
            $mRestDet[$loopCount]['city'] = (($mAddress['sublocality_level_1']['long_name'] != 'false') && ($mAddress['sublocality_level_1']['long_name'] != '')) ? $mAddress['sublocality_level_1']['long_name'] : '';
           }
		   
           $mRestDet[$loopCount]['state'] = (($mAddress['administrative_area_level_1']['short_name'] != 'false') && ($mAddress['administrative_area_level_1']['short_name'] != '')) ? $mAddress['administrative_area_level_1']['short_name'] : '';
           $mRestDet[$loopCount]['country'] = (($mAddress['country']['short_name'] != 'false') && ($mAddress['country']['short_name'] != '')) ? $mAddress['country']['short_name'] : '';
           $mRestDet[$loopCount]['zip_code'] = (($mAddress['postal_code']['long_name'] != 'false') && ($mAddress['postal_code']['long_name'] != '')) ? $mAddress['postal_code']['long_name'] : '';

           if (isset($mResponseDet["result"]["formatted_address"]))
           {
            $mRestDet[$loopCount]["Address"] = $mResponseDet["result"]["formatted_address"];
           }

           if (isset($mResponseDet["result"]["formatted_phone_number"]))
           {
            $mRestDet[$loopCount]["Phone"] = $mResponseDet["result"]["formatted_phone_number"];
           }

           if (isset($mResponseDet["result"]["name"]))
           {
            $mRestDet[$loopCount]["Name"] = $mResponseDet["result"]["name"];
           }

           if (isset($mResponseDet["result"]["photos"]))
           {
            $mPhotoReference =  $mResponseDet["result"]["photos"][0]["photo_reference"];
            $mURL = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=1200&photoreference=".$mPhotoReference."&sensor=false&key=".$mGoogleAPIKey;
            $mURL = str_replace(" ", "%20", $mURL);
            $mRestDet[$loopCount]["Image"] = $mURL;
           }
           else
           {
            $mRestDet[$loopCount]["Image"] = "../images/NIA.jpg";
           }
          }
         }
         else
         {
          $mdvTwenty = " style='display: none;' ";
         }
        }
?>

<style type="text/css">
    #SearchResultsDiv
    {
        width: 860px;
        border: 2px solid #777777;
        position: relative;
        float: left;
        padding-bottom: 35px;
    }
    .ResultsDiv
    {
        float: left;
        width: 855px;
        padding: 35px 20px;
        padding-bottom: 0px;
        position: relative;
    }
    .ResultImg
    {
        float: left;
        position: relative;
        width: 180px;
        height: 150px;
    }
    .RestaurantName {
    font-size: 24px;
    float: left;
    font-weight: bold;
    color: #222222;
    margin-left: 40px;
    width: 280px;
    }
    .RestaurantAddress {
    float: left;
    position: relative;
    margin-left: 20px;
    width: 100px;
    font-size: 14px;
    color: #222222;
    }
    .ThisIsMe {
    background-color: #f7941d;
    color: #fff;
    font-size: 18px;
    font-weight: bold;
    padding: 20px 10px;
    text-decoration: none;
    border-radius: 2px;
    }
    .ThisIsMeDiv {
    float: left;
    position: relative;
    margin-left: 75px;
    margin-top: 60px;
    width: 120px;
    }
    .HrDiv {
    float: left;
    width: 92%;
    margin-top: 35px;
    border-top: 1px solid #777;
    }
    .TextBoxHeading {
    font-size: 16px;
    color: #777;
    margin-top: 10px;
    font-weight: bold;
    }
</style>
<div id="main_heading">
<span>Add Restaurant</span>
</div>
<? if ($errMessage != "" ) { ?><div class="msg_done"><?=$errMessage?></div>
<? }?>
<div class="form_outer"  >
<form action="" method="post" enctype="multipart/form-data" name="">
    <!--********************** Buisness Search**********************************************-->
    
<div id="SearchFormBigDiv" align="left" style="width:100%">
				<input type="hidden" id="txtTimeZone" name="txtTimeZone" value="<?=$mTimeZoneSelect?>" />
            	<div id="RestaurantTextBoxDiv" style="width: 20%;float: left;">
                	<input type="text" class="RestaurantTextBox" placeholder="Restaurant Name" id="txtRestaurant" name="txtRestaurant" maxlength="80" style="padding: 9px 15px;">
                    <p class="TextBoxHeading">Your Restaurant's Name</p>
                </div>
                <div id="CityZipTextBoxDiv" style="float: left;width: 22%;">
                	<input type="text" class="CityZipTextBox" placeholder="City, State or Zip Code" id="txtCSZ" name="txtCSZ" maxlength="40" style="padding: 9px 25px;">
                    <p class="TextBoxHeading">Location of Your Business</p>
                </div>
                <div id="CountrySelectDiv" style="float: left;width: 15%;">
					<select class="CountrySelect CountrySelectImg" id="ddlCountry" name="ddlCountry" style="padding: 9px 15px;">
						<option id="US">US</option>
						<option id="Canada">Canada</option>
						<option id="UK">UK</option>
					</select>
				</div>
            </div>
<div id="SearchAgainDiv" name="SearchAgainDiv"><input id="btnSearch" name="btnSearch" type="submit" class="SearchAgain" value="Find My Restaurant" style="padding: 9px 15px;"></div>

<? if(isset($mRestDet)){?>
    <div id="SearchResultsDiv" style="margin-top:30px">
                	<div class="ResultsDiv" <?=$mRestDet[0]["DivShowHide"]?> style="display:none;">

                    	<div class="ResultImg"><img src="<?=$mRestDet[0]["Image"]?>" style="width: 180px; height: 150px;" /></div>
                        <div class="RestaurantName" style="width: 220px !important;">
                        	<?=$mRestDet[0]["Name"]?>
                        </div>
                        <div class="RestaurantAddress" style="width: 160px !important;"><?=$mRestDet[0]["Address"]?><br  /><br  /><?=$mRestDet[0]["Phone"]?></div>
                        <div class="ThisIsMeDiv"><a href="#dvMore" class="ThisIsMe" onclick="FillDetails('<?=str_replace("'", "|||", $mRestDet[0]["Name"])?>', '<?=$mRestDet[0]["Phone"]?>', '<?=str_replace("'", "|||", $mRestDet[0]['street_number'])?>', '<?=str_replace("'", "|||", $mRestDet[0]['street_name'])?>', '<?=$mRestDet[0]['city']?>', '<?=$mRestDet[0]['state']?>', '<?=$mRestDet[0]['country']?>', '<?=$mRestDet[0]['zip_code']?>', '<?=$mRestDet[0]["Image"]?>');">This Is Me!</a></div>
                    	<div class="HrDiv"></div>

                    </div>

                    <div class="ResultsDiv" <?=$mRestDet[1]["DivShowHide"]?> style="display:none;">
                    	<div class="ResultImg"><img src="<?=$mRestDet[1]["Image"]?>" style="width: 180px; height: 150px;" /></div>
                        <div class="RestaurantName" style="width: 220px !important;">
                        	<?=$mRestDet[1]["Name"]?>
                        </div>
                        <div class="RestaurantAddress" style="width: 160px !important;"><?=$mRestDet[1]["Address"]?><br  /><br  /><?=$mRestDet[1]["Phone"]?></div>
                        <div class="ThisIsMeDiv"><a href="#dvMore" class="ThisIsMe" onclick="FillDetails('<?=str_replace("'", "|||", $mRestDet[1]["Name"])?>', '<?=$mRestDet[1]["Phone"]?>', '<?=str_replace("'", "|||", $mRestDet[1]['street_number'])?>', '<?=str_replace("'", "|||", $mRestDet[1]['street_name'])?>', '<?=$mRestDet[1]['city']?>', '<?=$mRestDet[1]['state']?>', '<?=$mRestDet[1]['country']?>', '<?=$mRestDet[1]['zip_code']?>', '<?=$mRestDet[1]["Image"]?>');">This Is Me!</a></div>
                        <div class="HrDiv"></div>
                    </div>

                    <div class="ResultsDiv" <?=$mRestDet[2]["DivShowHide"]?> style="display:none;">
                    	<div class="ResultImg"><img src="<?=$mRestDet[2]["Image"]?>" style="width: 180px; height: 150px;" /></div>
                        <div class="RestaurantName" style="width: 220px !important;">
                        	<?=$mRestDet[2]["Name"]?>
                        </div>
                        <div class="RestaurantAddress" style="width: 160px !important;"><?=$mRestDet[2]["Address"]?><br  /><br  /><?=$mRestDet[2]["Phone"]?></div>
                        <div class="ThisIsMeDiv"><a href="#dvMore" class="ThisIsMe" onclick="FillDetails('<?=str_replace("'", "|||", $mRestDet[2]["Name"])?>', '<?=$mRestDet[2]["Phone"]?>', '<?=str_replace("'", "|||", $mRestDet[2]['street_number'])?>', '<?=str_replace("'", "|||", $mRestDet[2]['street_name'])?>', '<?=$mRestDet[2]['city']?>', '<?=$mRestDet[2]['state']?>', '<?=$mRestDet[2]['country']?>', '<?=$mRestDet[2]['zip_code']?>', '<?=$mRestDet[2]["Image"]?>');">This Is Me!</a></div>
                        <div class="HrDiv"></div>
                    </div>

                </div>
<?}?>
                <div style="clear:both"></div>

<!-- ***********************End************************-->
        <table width="575" border="0" cellpadding="4" cellspacing="0" style="margin-top:30px">
          <tr align="left" valign="top"> 
            <td width="254">Restaurant Name:</td>
            <td> <input name="catname" id="cat2" type="text" size="40" value="<?=@$catname?>"> </td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="254">Email:</td>
            <td> <input name="email" type="text" size="40" value="<?=@$email?>" id="email" /> </td>
          </tr>
	  <tr align="left" valign="top">
                <td width="254">Regional Settings:</td>
                <td>
<!--------------------------------Start NK(9-10-2014)----------------------------------------------------------------------------->                    
                    <input name="region" type="radio" value="1" onclick="loadTimeZoneUS()" id="region" <?php if(($_POST['region'])=="1"){echo "checked";}?> <?php if(empty($_POST['region'])){ echo "checked"; } ?> />USA&nbsp;&nbsp;
                    <input name="region" type="radio" value="0" onclick="loadTimeZoneUK()" id="region" <?php if(($_POST['region'])=="0"){echo "checked";}?>  />UK&nbsp;&nbsp;
                    <input name="region" type="radio" value="2" onclick="loadTimeZoneCanada()" id="region" <?php if(($_POST['region'])=="2"){echo "checked";}?> />Canada 
<!--------------------------------End NK(9-10-2014)----------------------------------------------------------------------------->                                        
                </td>
            </tr>
           <tr align="left" valign="top"> 
            <td width="254">Phone:</td>
            <td> <input name="phone" type="text" size="40" value="<?=@$phone?>" id="phone" /><input name="optionallogo" type="hidden" value="<?=@$optionallogo?>" id="optionallogo" /> </td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="254">Fax:</td>
            <td> <input name="fax" type="text" size="40" value="<?=@$fax?>" id="fax" /> </td>
          </tr>
          <? if ( $_SESSION['admin_type'] == 'admin' ) {?>
          <tr align="left" valign="top"> 
            <td width="254">Reseller Name:</td>
            <td>
            <select name="reselelr" id="reselelr" style="width:270px;" onChange="getclients(this.value,'add_resturant')" >
              <option value="-1">Select Reseller</option>
               <?=resellers_drop_down(@$reselelr) ?>
            </select>
             </td>
          </tr>
          <tr  align="left" valign="top"> 
            <td width="254">Owner's Name:</td>
            <td id="client_div">
            <select name="owner_name" id="1" style="width:270px;" onChange="getCardInfo(this.value)">
              <option value="-1">Select Restaurant Owner</option>
		<?=client_drop_down(@$owner_name) ?>
            </select>
             </td>
          </tr>
		  <? }?>
		   <? if( $_SESSION['admin_type'] == 'reseller' ) {?>
           <tr align="left" valign="top"> 
            <td width="254">Owner's Name:</td>
            <td>
            <select name="owner_name" id="owner_name" style="width:270px;" onChange="getCardInfo(this.value)">
              <option value="-1">Select Restaurant Owner</option>
               <?=client_drop_down(@$owner_name) ?>
            </select>
             </td>
          </tr>
          <? }?>    
          <? if ( $_SESSION['admin_type'] == 'admin' ) {?>
           <tr  align="left" valign="top"> 
            <td width="254">License Key:</td>
            <td id="licenses_div">
            <select name="license_key" id="license_key" style="width:270px;">
				<option value="-1">Select License Key</option>
            </select>
             </td>
          </tr>
          <? } else if ( $_SESSION['admin_type'] == 'reseller'  ){?>
          <tr  align="left" valign="top"> 
            <td width="254">License Key:</td>
<!---------------------------Start--------------------------------------------------------->            
            <? $reslID=$_SESSION['owner_id']; ?>
            <td id="licenses_div"><input type="hidden" name="reselelr" id="reselelr" value=<?=$reslID?>>
<!---------------------------End----------------------------------------------------------->      
             <select name="license_key" id="license_key" style="width:270px;">
              <option value="-1">Select License Key</option>
               <?=licenses_drop_down(@$owner_name) ?>
            </select>
             </td>
          </tr>
          <? }?>
          
          <tr align="left" valign="top">
            <td>Order Minimum</td>
            <td><input name="order_minimum" type="text" size="40" id="order_minimum" value="<?=@$order_minimum?>"></td>
          </tr>
         
          <tr align="left" valign="top">
            <td>Payment Mathod</td>
            <td><input name="credit" type="checkbox" value="credit"  id="payment_method" <? if(@$payment_method == "credit" || @$payment_method == "both" ){ echo "checked";} ?>>Credit Card            &nbsp;&nbsp;
            <input name="cash" type="checkbox" value="cash"  id="payment_method" <? if(@$payment_method == "cash" || @$payment_method == "both" ){ echo "checked";} ?>>Cash</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Select Time Zone</td>
            <td>
            <select name="time_zone" id="time_zone" style="width:270px;">
                  <option value="-1">Select Time Zone</option>
             	  <?=get_timezone_drop_down(@$time_zone) ; ?>
                </select>
            </td>
          </tr>
          <tr align="left" valign="top">
                <td>Resturant Address:</td>
               <td>
                    <input name="rest_address" type="text" size="40" id="rest_address" value="<?= @$rest_address ?>">
                </td>
            </tr>

            <tr align="left" valign="top">
                <td>Resturant City:</td>
                <td>
                    <input name="rest_city" type="text" size="40" id="rest_city" value="<?= @$rest_city ?>">
                </td>
            </tr>
            <tr align="left" valign="top">
                <td>Resturant State:</td>
                <td>
                    <input name="rest_state" type="text" size="40" id="rest_state" value="<?= @$rest_state ?>">
                </td>
            </tr>
            
            <tr align="left" valign="top">
                <td>Resturant Zip Code:</td>
                <td>
                    <input name="rest_zip" type="text" size="40" id="rest_zip" value="<?= @$rest_zip ?>">
                </td>
            </tr>
           <? 
			$aDotNet_access_qry = mysql_query("SELECT aDotNet_access FROM users WHERE id ='".$_SESSION['owner_id']."'");
			$aDotNet_access_rs = mysql_fetch_array($aDotNet_access_qry);
			$aDotNet_access = $aDotNet_access_rs['aDotNet_access'];
		   //admin can access AUTHORISE.NET, reseller can only access if aDotNet_access_status = 1
		   //if( ( $_SESSION['admin_type'] == 'admin' ) || ( $_SESSION['admin_type'] == 'reseller' && $aDotNet_access == 1  ) ){ ?>
          
          <tr align="left" valign="top">
                <td><strong>Choose Product:</strong></td>
                <td  id="product_div">
                    <select name="product_details" id="product_details" style="width:270px;" >
                    <option value="-1">Select Product</option>
                    </select>
                </td>
          </tr>

          <tr align="left" valign="top">
            <td><strong>Billing Information</strong></td>
            <td></td>
          </tr>

          <tr align="left" valign="top">
            <td>Subcription type</td>
            <td><input name="subcription_method" type="radio" value="automatic"  id="payment_collection_method" checked> automatic           &nbsp;&nbsp;
            <input name="subcription_method" type="radio" value="invoice"  id="payment_collection_method" >Invoice</td>
          </tr>


          <tbody class="card_new_existing">
          <tr align="left" valign="top">
               <td></td>
            <td><input name="credit_card" type="radio" value="0"  id="credit_card" checked>New Credit Card            &nbsp;&nbsp;
            <input name="credit_card" type="radio" value="1"  id="credit_card" >Choose Existing Card</td>
          </tr>
          </tbody>
          <tbody class="payment_Method">

            <tr>
             <td></td>
            <td>
		 </td>
          </tr>
           <tr align="left" valign="top">
            <td><strong>Card no:</strong></td>
            <td>
		<input name="card_no" type="text" size="40"  id="card_no" maxlength="20"/> </td>
           </tr>

          

          <tr align="left" valign="top">
            <td><strong>Exp Date:</strong></td>
            <td>
                 <select name="exp_date" id="exp_date" style="width:70px;" >
                    <option value="1">1 -Jan</option>
                    <option value="2">2 -Feb</option>
                    <option value="3">3 -Mar</option>
                    <option value="4">4 -Apr</option>
                    <option value="5">5 -May</option>
                    <option value="6">6 -Jun</option>
                    <option value="7">7 -Jul</option>
                    <option value="8">8 -Aug</option>
                    <option value="9">9 -Sep</option>
                    <option value="10">10 -Oct</option>
                    <option value="11">11 -Nov</option>
                    <option value="12">12 -Dec</option>
                 </select>
                
                <select name="exp_year" id="exp_year" style="width:70px;" >
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                </select>
            </td>
          </tr>
          
          </tbody>
          <tbody class="choose_existing">
              
              <tr align="left" valign="top">
                <td><strong>Credit card no:</strong></td>
                <td  id="card_div">
                    <select name="credit_card_number" id="credit_card_number" style="width:270px;" >
                    <option value="-1">Select Card</option>
                    </select>
                </td>
            </tr>
          <tr><td></td></tr>
          </tbody>
         
          <? //}?>
          <tr> 
            <td>&nbsp;</td>
            <td> <input type="submit" name="submit" value="Add Restaurant"> </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="owner_id" id ="owner_id" value="<?= @$owner_name ?>">
        <input type="hidden" name="product_id" id ="product_id" value="<?= @$product_details ?>">
        <input type="hidden" name="license_id" id ="license_id" value="<?= @$license_key ?>">

</form>
</div>
<script type="text/javascript">


    $(document).ready(function() {
            var reseller_id = $('#reselelr').val();
            console.log($('#owner_name').val());
            getclients(reseller_id,'add_resturant',$('#owner_id').val(),$('#product_id').val(),$('#license_id').val());
//-----------------------------Start NK(9-10-2014)--------------------------------------------------------            
//            jQuery("#region").attr('checked', 'checked');
//-----------------------------End NK(9-10-2014)--------------------------------------------------------
            $('#phone').unmask();
            $('#fax').unmask();
            $('#phone').mask('(999) 999-9999');
            $('#fax').mask('(999) 999-9999');
            


            $("#time_zone").children('option:contains("London")').hide();
			$("#time_zone").children('option:contains("Canada")').hide();
            $("#time_zone").children('option:contains("US")').show();
            //$("#time_zone").val(-1);

});

function loadTimeZoneUS()
{
	$('#phone').unmask();
	$('#fax').unmask();
	
	$('#phone').mask('(999) 999-9999');
	$('#fax').mask('(999) 999-9999');
	
	$("#time_zone").children('option:contains("London")').hide();
	$("#time_zone").children('option:contains("US")').show();
	$("#time_zone").children('option:contains("Canada")').hide();
	$("#time_zone").val($("#time_zone").children('option:contains("US")').first().val());
}

function loadTimeZoneCanada()
{
	$('#phone').unmask();
    $('#fax').unmask();

	$('#phone').mask('(999) 999-9999');
	$('#fax').mask('(999) 999-9999');

	$("#time_zone").children('option:contains("London")').hide();
	$("#time_zone").children('option:contains("US")').hide();
	$("#time_zone").children('option:contains("Canada")').show();
	$("#time_zone").val($("#time_zone").children('option:contains("Canada")').first().val());
}

function loadTimeZoneUK()
{
	$('#phone').unmask();
	$('#fax').unmask();

	$('#phone').mask('(9999) 999-9999');
	$('#fax').mask('(9999) 999-9999');

	$("#time_zone").children('option:contains("US")').hide();
	$("#time_zone").children('option:contains("Europe")').show();
	$("#time_zone").children('option:contains("Canada")').hide();
	$("#time_zone").val($("#time_zone").children('option:contains("Europe")').first().val());
}


</script>
<script type="text/javascript">

    $('.choose_existing').hide();
    $('input[type=radio][name=credit_card]').change(function()
    {   
        var rdbval = $('input[name=credit_card]:checked').val();
        if(rdbval==1)
        {
            $('.payment_Method').hide();
            $('.choose_existing').show();
        }
        else
        {
            $('.payment_Method').show();
            $('.choose_existing').hide();
        }
    });

	$('input[type=radio][name=subcription_method]').change(function()
    {
        var rdbvalcard = $('input[name=credit_card]:checked').val();
        var rdbval = $('input[name=subcription_method]:checked').val();
        if(rdbval=="automatic")
        {
            if(rdbvalcard==1)
            {
                $('.payment_Method').hide();
                $('.card_new_existing').show();
                $('.choose_existing').show();
            }
            else
            {
                $('.choose_existing')
                $('.payment_Method').show();
                $('.card_new_existing').show();
            }
        }
        else
        {
            $('.payment_Method').hide();
            $('.choose_existing').hide();
            $('.card_new_existing').hide();

        }
    });


</script>
<script type="text/javascript" language="javascript">
				$(document).ready(function()
			   	{
					$("#btnSearch").click(function()
					{
						$("#txtRestaurant").css("border", "");
						$("#txtCSZ").css("border", "");
						$("#SearchRestaurantError").hide();

						if ($.trim($("#txtRestaurant").val())=="")
						{
							$("#SearchRestaurantError").show();
							$("#txtRestaurant").focus();
							$("#txtRestaurant").css("border", "2px solid #FF0000");
							return false;
						}
						else
						{
							$("#SearchRestaurantError").hide();
							$("#txtRestaurant").css("border", "");
						}

						if ($.trim($("#txtCSZ").val())=="")
						{
							$("#SearchRestaurantError").show();
							$("#txtCSZ").focus();
							$("#txtCSZ").css("border", "2px solid #FF0000");
							return false;
						}
						else
						{
							$("#SearchRestaurantError").hide();
							$("#txtCSZ").css("border", "");
						}
					});


				});

				function FillDetails(pName, pPhone, pStreet_number, pStreet_name, pCity, pState, pCountry, pZip_code, pImage)
				{
					$("html, body").animate({scrollTop:1100},"slow");
					pName = pName.replace("|||", "'");
					pStreet_number = pStreet_number.replace("|||", "'");
					pStreet_name = pStreet_name.replace("|||", "'");
					$("#cat2").val(pName);
					if ($.trim(pStreet_name)!="")
					{
						$("#rest_address").val(pStreet_number+" "+pStreet_name);
					}
					else
					{
						$("#rest_address").val(pStreet_number);
					}
					$("#rest_state").val(pState);
					$("#rest_city").val(pCity);
					$("#rest_zip").val(pZip_code);
					$("#phone").val(pPhone);
					$("#optionallogo").val(pImage);

					if (($.trim(pCountry.toLowerCase())=="us") || ($.trim(pCountry.toLowerCase())=="usa") || ($.trim(pCountry.toLowerCase())=="united states") || ($.trim(pCountry.toLowerCase())=="united states of america") || ($.trim(pCountry.toLowerCase())=="america"))
					{
                                                $("#time_zone").children('option:contains("London")').hide();
						$("#time_zone").children('option:contains("Canada")').hide();
						$("#time_zone").children('option:contains("US")').show();
					}
					else if (($.trim(pCountry.toLowerCase())=="uk") || ($.trim(pCountry.toLowerCase())=="gb") || ($.trim(pCountry.toLowerCase())=="united kingdom") || ($.trim(pCountry.toLowerCase())=="england"))
					{
			            $("#time_zone").children('option:contains("London")').show();
						$("#time_zone").children('option:contains("Canada")').hide();
						$("#time_zone").children('option:contains("US")').hide();
					}
					else if (($.trim(pCountry.toLowerCase())=="canada") || ($.trim(pCountry.toLowerCase())=="ca"))
					{
			            $("#time_zone").children('option:contains("London")').hide();
						$("#time_zone").children('option:contains("Canada")').show();
						$("#time_zone").children('option:contains("US")').hide();
					}
					
					$("#time_zone option").each(function() 
					{
						if($.trim($(this).text().toLowerCase()) == $.trim($("#txtTimeZone").val().toLowerCase())) 
						{
							$(this).attr('selected', 'selected');            
						}                        
					});
				}

				
			</script>