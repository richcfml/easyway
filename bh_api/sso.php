<?php
$_GET = array_change_key_case($_GET, CASE_LOWER);
require_once("../includes/config.php");
include_once("../includes/class.phpmailer.php");

$objMail = new testmail();
$mReturn = array();
$mVerifyRequest = verifyRequest();
if ($mVerifyRequest==1) //Valid Session
{
        if (isset($_GET["createUser"]) || isset($_GET["createuser"]))
        {    
                if(empty($_GET['firstname']) && !isset($_GET['firstname']))
                {
                    $mReturn = errorFunction("1","Invalid call, First name is missing.","Invalid call.!","Attribute Error");
                }
                else if(empty($_GET['lastname']) && !isset($_GET['lastname']))
                {
                    $mReturn = errorFunction("2","Invalid call, Last name is missing.","Invalid call.!","Attribute Error");
                }
                else if(empty($_GET['email']) && !isset($_GET['email']))
                {
                    $mReturn = errorFunction("3","Invalid call, email is missing.","Invalid call.!","Attribute Error");
                }
                else if(empty($_GET['password']) && !isset($_GET['password']))
                {
                    $mReturn = errorFunction("4","Invalid call, password is missing.","Invalid call.!","Attribute Error");
                }
                else if(strlen( $_GET['firstname']) > 50 || !preg_match('/^[ A-Za-z0-9-\.]*$/',$_GET['firstname']))
                {
                    $mReturn = errorFunction("5","First Name only allows alpha numeric and maximum 35 characters.","Incorrect first name.","Attribute Error");
                }
                else if(strlen( $_GET['lastname']) > 50 || !preg_match('/^[ A-Za-z0-9-\.]*$/',$_GET['lastname']))
                {
                    $mReturn = errorFunction("6","Last Name only allows alpha numeric and maximum 35 characters.","Incorrect last name.","Attribute Error");
                }
                else if(!preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}+$/',$_GET['email']))
                {
                    $mReturn = errorFunction("7","Incorrect email address","Incorrect email address","Attribute Error");
                }
                else if(!preg_match('/^.*(?=.*\d)(?=.*[a-zA-Z]).*$/', $_GET['password']) || strlen( $_GET['password']) > 20 || strlen( $_GET['password']) < 8)
                {
                    $mReturn = errorFunction("8","Password must be alphanumeric with maximum 20 characters and minimum 8 characters.","Incorrect Password","Attribute Error");
                }
                else
                {
                        $mSQL = "SELECT email FROM bh_sso_user WHERE email='".prepareStringForMySQL($_GET['email'] )."' AND password !='NULL'";
                        Log::write("check email exist - sso.php - IF", "QUERY --".$mSQL, 'sso', 1);
                        $user_qry  = dbAbstract::Execute($mSQL);

                        if(dbAbstract::returnRowsCount($user_qry)>0 )
                        {
                            $mReturn = errorFunction("9","Email already exists","Email already exists","Data Error");
                        }
                        else
                        {
                            $expiryDate = date("c",  strtotime("+1 day"));  
                            $mAPIKey = substr(str_shuffle(str_repeat("23456789abcdefghijkmnpqrstuvwxyz", 10)), 0, 10);
                            $qry="INSERT INTO bh_sso_user SET
                                      firstName='". prepareStringForMySQL($_GET['firstname'] ) ."'
                                    , lastName='". prepareStringForMySQL($_GET['lastname'] ) ."'
                                    , email='". prepareStringForMySQL($_GET['email'] ) ."'
                                    , password='". prepareStringForMySQL($_GET['password'] ) ."'
                                    , address1='". prepareStringForMySQL($_GET['address1'] ) ."'
                                    , address2='". prepareStringForMySQL($_GET['address2'] ) ."'
                                    , loyality_member='". prepareStringForMySQL($_GET['loyality_member'] ) ."'
                                    , city='". prepareStringForMySQL($_GET['city'] ) ."'
                                    , state='". prepareStringForMySQL($_GET['state'] ) ."'
                                    , zip='". prepareStringForMySQL($_GET['zip'] ) ."'
                                    , phone='". prepareStringForMySQL($_GET['phone'] ) ."'
                                    , status=1";

                            Log::write("Add SSO user - sso.php - IF", "QUERY --".$qry, 'sso', 1);
                            $sso_id = dbAbstract::Insert($qry,0,2);
                            if($sso_id > 0)
                            {
                                $mSql = dbAbstract::Insert("INSERT INTO bh_sso_session SET sso_user_id='". $sso_id ."', session_id='". $mAPIKey."', session_expiry='".strtotime($expiryDate)."'");
                                $mReturn[] = array("response" => "Thanks for registering!","sso_acc" =>$sso_id,"sso"=>$mAPIKey,"Expiration_Date"=>$expiryDate);
                            }
                        }
                }
            $json = json_encode($mReturn,true);
            echo $json;
        }
        
        else if (isset($_GET["signIn"]) || isset($_GET["signin"]))
        {
            if(empty($_GET['email']) && !isset($_GET['email']))
            {
                $mReturn = errorFunction("3","Invalid call, email is missing.","Invalid call.!","Attribute Error");
            }
            else if((empty($_GET['sso']) && !isset($_GET['sso'])) && (empty($_GET['password']) && !isset($_GET['password'])))
            {
                $mReturn = errorFunction("10","Invalid call, Provide session id or password.","Invalid call.!","Attribute Error");
            }
            else 
            {
                if(isset($_GET['sso']))
                {
                    $qry = "select u.id from bh_sso_user u inner join bh_sso_session bhs on u.id = bhs.sso_user_id WHERE u.email = '". $_GET['email'] ."' and bhs.session_id = '".$_GET['sso']."'";
                }
                else if(isset($_GET['password']))
                {
                    $qry = "select * from bh_sso_user WHERE email = '". $_GET['email'] ."' and password = '".$_GET['password']."'";
                }
                $expiryDate = date("c",  strtotime("+1 day"));  
                $date = strtotime($expiryDate);
                Log::write("Sign In - sso.php - IF", "QUERY --".$mSQL, 'sso', 1);
                $userResult = dbAbstract::ExecuteObject($qry);
                Log::write('User Response Array - sso.php', print_r($userResult,true), 'sso', 1);
                if ($userResult->id >0)
                {   
                    $mAPIKey = substr(str_shuffle(str_repeat("23456789abcdefghijkmnpqrstuvwxyz", 10)), 0, 10);
                    $mSql = dbAbstract::Insert("INSERT INTO bh_sso_session SET sso_user_id='". $userResult->id ."', session_id='". $mAPIKey."', session_expiry='".strtotime($expiryDate)."'");
                    $mReturn = array("response" => "Account Verified","sso" => $mAPIKey,"Expiration_Date"=>$expiryDate);
                }
                else
                {
                    if(isset($_GET['sso']))
                    {
                        $mReturn = errorFunction("11","Invalid email or Session id.","Invalid call.!","Attribute Error");
                    }
                    else if(isset($_GET['password']))
                    {
                        $mReturn = errorFunction("11","Invalid email or password.","Invalid call.!","Attribute Error");
                    }
                }
            }
            $json = json_encode($mReturn,true);
            echo $json;
            
        }

        else if (isset($_GET["getuserdetail"]))
        {
            if(isset($_GET['sso']) && !empty($_GET['sso']))
            {
                
                    $qry = "select u.*,s.session_expiry from bh_sso_user u inner join bh_sso_session s on s.sso_user_id = u.id WHERE s.session_id = '". $_GET['sso'] ."'";
                    Log::write("Get user Detail - sso.php - IF", "QUERY --".$qry, 'sso', 1);
                    $rs = dbAbstract::Execute($qry);
                    $userResult = dbAbstract::returnObject($rs);
                    Log::write('User Response Array - sso.php', print_r($userResult,true), 'sso', 1);
                    if(dbAbstract::returnRowsCount($rs) > 0)
                    {
                        if(time() < $userResult->session_expiry)
                        {
                            $mReturn = array(
                                "firstName" => "$userResult->firstName",
                                "lastName" => "$userResult->lastName",
                                "email" => "$userResult->email",
                                "city" => "$userResult->city",
                                "state" => "$userResult->state",
                                "zip" => "$userResult->zip",
                                "phone" => "$userResult->phone",
                                "address1" => "$userResult->address1",
                                "address2" => "$userResult->address2",
                                "loyality_member" => "$userResult->loyality_member"
                                );

                                  if(isset($_GET['slug']) && !empty($_GET['slug'])){
                                        // Getting CC Info Start
                                        $stmt = "select g.id, g.data_type, g. data_1, g.data_2, c.id as cust_id, r.url_name
                                                        From general_detail g, resturants r, customer_registration c
                                                        where g.id_2=c.id and c.resturant_id=r.id and g.sso_user_id='".$userResult->id."' and r.url_name='".$_GET['slug']."'";
										$rs = dbAbstract::Execute($stmt);
                                        $ccinfo_arr = array();
                                        while($row = dbAbstract::returnObject($rs)){
                                                $ccinfo_arr[] = array('restaurant' => $row->url_name,
																	  'user_id' => urlencode(base64_encode($row->cust_id)),
																	  'cc_id' => urlencode(base64_encode($row->id)),
																	  'cc_type' => $row->data_type,
																	  'cc_endwith' => $row->data_1,
																	  'cc_token' => $row->data_2);
                                        }
                                        //echo "<pre>"; print_r($ccinfo_arr); echo "</pre>";
                                        // Getting CC Info End

                                        if(count($ccinfo_arr) > 0){
                                                $mReturn[] = $ccinfo_arr;
                                        }
                                }
                        }
                        else
                        {
                            $mReturn = errorFunction("2","Your session id has expired.","Session id has expired","Attribute Error");
                        }
                    }
                    else
                    {
                       $mReturn = errorFunction("12","Invalid session id.","Invalid session id.","Data Error");
                    }
            }
            else
            {
                $mReturn = errorFunction("3","Invalid call, session id is missing.","Invalid call.!","Attribute Error");
            }
            $json = json_encode($mReturn,true);
            echo $json;

        }

        else if (isset($_GET["updateuserdetail"]))
        {
            $email = '';
            if(isset($_GET['sso']) && !empty($_GET['sso']))
            {
                $qry = "select u.*,s.session_expiry from bh_sso_user u inner join bh_sso_session s on s.sso_user_id = u.id WHERE s.session_id = '". $_GET['sso'] ."'";
                $rs = dbAbstract::Execute($qry);
                $userResult = dbAbstract::returnObject($rs);
                Log::write('User Response Array - sso.php', print_r($userResult,true), 'sso', 1);
                if(dbAbstract::returnRowsCount($rs) > 0)
                {
                    if(time() < $userResult->session_expiry)
                    {
						if(empty($_GET['cc_action'])){
						  $firstName = (!isset($_GET['firstname']) || empty($_GET['firstname'])) ? $userResult->firstName   : prepareStringForMySQL($_GET['firstname']);
						  $lastName = (!isset($_GET['lastname'])|| empty($_GET['lastname'])) ? $userResult->lastName  : prepareStringForMySQL($_GET['lastname']);
						  $city = (!isset($_GET['city'])|| empty($_GET['city'])) ? $userResult->city   : prepareStringForMySQL($_GET['city']);
						  $state = (!isset($_GET['state'])|| empty($_GET['state'])) ? $userResult->state   : prepareStringForMySQL($_GET['state']);
						  $password = (!isset($_GET['password'])|| empty($_GET['password'])) ? $userResult->password  : prepareStringForMySQL($_GET['password']);
						  $address1 = (!isset($_GET['address1'])|| empty($_GET['address1'])) ? $userResult->address1   : prepareStringForMySQL($_GET['address1']);
						  $address2 = (!isset($_GET['address2'])|| empty($_GET['address2'])) ? $userResult->address2   : prepareStringForMySQL($_GET['address2']);
						  $zip = (!isset($_GET['zip'])|| empty($_GET['zip'])) ? $userResult->zip   : prepareStringForMySQL($_GET['zip']);
						  $phone = (!isset($_GET['phone'])|| empty($_GET['phone'])) ? $userResult->phone   : prepareStringForMySQL($_GET['phone']);
						  $loyality_member = (!isset($_GET['loyality_member'])|| empty($_GET['loyality_member'])) ? $userResult->loyality_member   : prepareStringForMySQL($_GET['loyality_member']);
						  $email = $userResult->email;
						  $update_qry = "UPDATE bh_sso_user SET
										firstName= '".prepareStringForMySQL($firstName)."', 
																		lastName='".prepareStringForMySQL($lastName)."', 
																		city='".prepareStringForMySQL($city)."', 
																		state='".prepareStringForMySQL($state)."', 
																		password='".$password."', 
																		address1='".prepareStringForMySQL($address1)."', 
																		zip='".prepareStringForMySQL($zip)."', 
																		phone='".prepareStringForMySQL($phone)."', 
																		loyality_member='".$loyality_member."', 
																		address2='".prepareStringForMySQL($address2)."'    
										WHERE email = '". $email ."'";
  
						  Log::write("Update SSO user - sso.php - IF", "QUERY --".$update_qry, 'sso', 1);
						  $afftected_rows = dbAbstract::Update($update_qry,0,1);
						  if($afftected_rows)
						  {
							  $mReturn[] = array("response" => "Account Updated successfully");
						  }
						  else
						  {
							  $mReturn = errorFunction("14", "0 rows updated", "Record not updated.","Data Error");
						  }
						}
						elseif(isset($_GET['cc_action']) && !empty($_GET['slug'])){
						  /*$qry = "select u.email from bh_sso_user u inner join bh_sso_session s on s.sso_user_id = u.id WHERE s.session_id = '". $_GET['sso'] ."'";
						  $rs = mysql_query($qry);
						  $userResult = mysql_fetch_object($rs);*/
						  $email = $userResult->email;
						  include_once ("../classes/restaurant.php");
						  $objRestaurant = new restaurant();
						  $objRestaurant = $objRestaurant->getDetailbyUrl($_GET["slug"]);
						  
						  switch($_GET['cc_action']){
							case 'delete':
							  if(!empty($_GET['cc_id']) &&  !empty($_GET['user_id'])){
								$ccid = base64_decode(urldecode($_GET['cc_id']));
								$userid = base64_decode(urldecode($_GET['user_id']));
								
								$gd_rs = dbAbstract::Execute("select * from general_detail where id='$ccid' and id_2='$userid'");
								if(dbAbstract::returnRowsCount($gd_rs) > 0){
								  $gd_row = dbAbstract::returnObject($gd_rs);
								  //echo "<pre>"; print_r($gd_row); echo "</pre>";
								  
								  $mTokenID = $gd_row->id;
								  $mPaymentGateWay = $objRestaurant->payment_gateway;
								  $mUserName = $objRestaurant->authoriseLoginID; //Payment Gateway LoginID, UserName
								  $mPassword = $objRestaurant->transKey; //Payment Gateway password, TRaskey
								  if (strtolower(trim($mPaymentGateWay))=="authorisedotnet")
								  {
									  $mResponse = DeleteAutherizeNetToken($mTokenID, $mUserName, $mPassword);
								  }
								  else if (strtolower(trim($mPaymentGateWay))=="nmi") 
								  {
									  $mResponse = DeleteNMIToken($mTokenID, $mUserName, $mPassword);
								  }
								  else if (strtolower(trim($mPaymentGateWay))=="gge4") //securepay, 
								  {
									  $mResponse = DeleteGGe4Token($mTokenID, $mUserName, $mPassword);
								  }
								  
								  if($mResponse == 'Success'){
									$mReturn = array("response" => "CC Token delete successfully.");
								  }else{
									  $mReturn = errorFunction("14", "CC token not deleted", $mResponse,"Data Error");
								  }
								}
								else $mReturn = errorFunction("12","CC token does not exist.","CC token does not exist.","Data Error");
							  }
							  else
							  {
								  if(empty($_GET['cc_id'])){
								  	$mReturn = errorFunction("3","Invalid call, cc_id attribute is missing.","Invalid call.!","Attribute Error");
								  }
								  elseif(empty($_GET['user_id'])){
									  $mReturn = errorFunction("3","Invalid call, user_id attribute is missing.","Invalid call.!","Attribute Error");
								  }
								  
							  }
							  break;
							  
							case 'add':
							  if(!empty($_GET['secure_data']) && ($_GET['default'] >= 0) && !empty($_GET['pcardexpiry'])){
								@extract($_GET);
								$stmt = "select c.id from customer_registration c, resturants r
										where c.cust_email='$email' and c.resturant_id=r.id and r.url_name='$slug'";
										
								$rs = dbAbstract::Execute($stmt);
								if(dbAbstract::returnRowsCount($rs) > 0){
								  $userResult = dbAbstract::returnObject($rs);
								  $response = saveTokenWithExpiry($userResult->id, $objRestaurant, $_GET['secure_data'], $_GET['default'], $_GET['pcardexpiry'],$email);
								  
								  if($response == true){
									  $mReturn[] = array("response" => "Thanks for registering new credit card!");
								  }else{
									  $mReturn = errorFunction("17","CC Tokey not added.");
								  }
								}
							  }
							  else
							  {
								  if(empty($_GET['secure_data'])){
								    $mReturn = errorFunction("3","Invalid call, secure_data attribute is missing.","Invalid call.!","Attribute Error");
								  }
								  elseif($_GET['default']==''){
								    $mReturn = errorFunction("3","Invalid call, default attribute is missing.","Invalid call.!","Attribute Error");
								  }
								  elseif(empty($_GET['pcardexpiry'])){
								    $mReturn = errorFunction("3","Invalid call, pcardexpiry attribute is missing.","Invalid call.!","Attribute Error");
								  }
								  
							  }
							  break;
						  }
						
					}
                    }
                    else
                    {
                        $mReturn = errorFunction("2","Your session id has expired.","Session id has expired","Attribute Error");
                    }
                }
                else
                {
                    $mReturn = errorFunction("12","Invalid session id.","Invalid session id.","Data Error");
                }
            }
            else
            {
                $mReturn = errorFunction("3","Invalid call, session id is missing.","Invalid call.!","Attribute Error");
            }
            $json = json_encode($mReturn,true);
            echo $json;
        }

        else if (isset($_GET["signOut"]) || isset($_GET["signout"]))
        {
            if(!empty($_GET['sso']))
            {
                $Qry = dbAbstract::Execute("Update bh_sso_session set session_expiry = 0 where session_id = '". $_GET['sso'] ."'");
                $mReturn = array("response" => "You have logged out");
            }
            else
            {
                $mReturn = errorFunction("11","Invalid call, session id is missing.","Invalid call.!","Attribute Error");
            }
            $json = json_encode($mReturn,true);
            echo $json;
        }

        else if (isset($_GET["resetPassword"]) || isset($_GET["resetpassword"]))
        {
            if(isset($_GET['email']) && !empty($_GET['email']))
            {
                $qry = "select * from bh_sso_user where email='".$_GET['email']."'";
                Log::write("Reset Password - sso.php - IF", "QUERY --".$qry, 'sso', 1);
                $rs = dbAbstract::Execute($qry);
                if(dbAbstract::returnRowsCount($rs) > 0)
                {
                        // Get customer data
                        $row = dbAbstract::returnObject($rs);

                        // Add expiry date time to bh_sso_user table
                        dbAbstract::Update("update bh_sso_user set passwordupdate_expiry='".date("Y-m-d h:i:s", strtotime("+24 Hours"))."' where id='".$row->id."'");

                        sendPasswordRecoveryEmail($row);
                        $mReturn = array("response" => "We have sent you an email with password reset instructions");
                }
                else
                {
                    $mReturn = errorFunction("12","Email does not exist.","Email does not exist.","Data Error");
                }
            }
            else
            {
                $mReturn = errorFunction("3","Invalid call, email is missing.","Invalid call.!","Attribute Error");
            }
            $json = json_encode($mReturn,true);
            echo $json;
        }
}
else if ($mVerifyRequest==0) //This will never happen
{
    $mReturn = errorFunction("15", "Apikey not specified.", "Invalid call.", "Attribute Error");
    $json = json_encode($mReturn,true);
    echo $json;
}
else if ($mVerifyRequest==2) //Session ID not present
{
    $mReturn = errorFunction("15", "Apikey not specified.", "Invalid call.","Attribute Error");
    $json = json_encode($mReturn,true);
    echo $json;
}
else if ($mVerifyRequest==3) //Session ID expred
{
    $mReturn = errorFunction("16", "Api key is not valid", "Api key is not valid", "Data Error");
    $json = json_encode($mReturn,true);
    echo $json;
}
/* General (Helping) Functions Starts Here */
function adminLogin()
{   
    $qry = "Select * from users where ewo_api_key = '".$_GET["apikey"]."'";
    Log::write("Admin Login - sso.php - IF", "QUERY --".$qry, 'menu', 1);
    $exeQry = dbAbstract::Execute($qry);
    $resultArray = dbAbstract::returnObject($exeQry);
    $countRows = dbAbstract::returnRowsCount($exeQry);
    Log::write('Admin Response Array - sso.php', print_r($resultArray,true), 'sso', 1);
    
    if ($countRows >0 && $resultArray->status ==1)
    {
        $mReturn = $resultArray->id;
    }
    else if ($countRows >0 && $resultArray->status ==0)
    {
        $mReturn = 0;
    }
    else
    {
        $mReturn = -1;
    }
    return $mReturn;
}
function verifyRequest()
{
    if (isset($_GET["apikey"]))
    {
        $Qry = dbAbstract::Execute("Select * from users where ewo_api_key = '".$_GET["apikey"]."'");
        $qryCount = dbAbstract::returnRowsCount($Qry);
        if ($qryCount <= 0)
        {
            return 3; //sso (session id) is different than current session id (Session expired)
        }
        else
        {
            return 1; //sso (session id) is same as current (Valid Session)
        }
    }
    else
    {
        return 2; //sso (session id) not present
    }
}
function errorFunction($errorCode,$errorDescription,$errorMessage,$errorTitile)
{
    $result = array(
            "errorCode" => $errorCode,
            "errorDescription" => $errorDescription,
            "errorMessage" => $errorMessage,
            "errorTitle" => $errorTitile
        );
    return $result;
}

function sendPasswordRecoveryEmail($row){
	global $objMail;
	global $SiteUrl;
	//require_once('../classes/Encrypt.php');
	//$encrypt = new Encrypt();
	$funObj = new clsFunctions();
	// Encrypt user id
	$userId = $funObj->encrypt($row->id, '@e*w*o@');
	
	$subject = 'Reset Your Password';
	$recovery_url = $SiteUrl ."bh_api/updatepassword.php?id=".$userId;
	$body = "Hello ".$row->firstName.",<br /><br />
				To reset your password for the New York Deli Guide, please follow this link: 
				<br /><br />
				<a href='". $recovery_url ."'>". $recovery_url ."</a>
				<br /><br />
				If you don't wish to reset your password, simply ignore this email.";
	
	$objMail->FromName="Boar's Head NY Deli Guide App";
	$objMail->from="NYDG@boarshead.com";
	$objMail->sendTo($body,$subject,$row->email);
}

function DeleteAutherizeNetToken($pTokenID, $pUserName, $pPassword)
{
	include_once ("../lib/AuthDotNet/vars.php");
	include_once ("../lib/AuthDotNet/util.php");

	$mResult = dbAbstract::Execute("SELECT id_2, data_2, data_3 FROM general_detail WHERE id=".$pTokenID);
	if (dbAbstract::returnRowsCount($mResult)>0)
	{
		$mRow = dbAbstract::returnObject($mResult);
		$mPaymentProfileID = $mRow->data_2; //Token
		$mUserID = $mRow->id_2;
		$mDefaultCard = $mRow->data_3;
		
		$mResult = dbAbstract::Execute("SELECT profile_id FROM auth_user_profile WHERE customer_id=".$mUserID);
		if (dbAbstract::returnRowsCount($mResult)>0)
		{
			$mRow = dbAbstract::returnObject($mResult);
			$mCustomerProfileID = $mRow->profile_id;
			
			
			
			$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<deleteCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
			"<merchantAuthentication>".
				"<name>".$pUserName."</name>".
				"<transactionKey>".$pPassword."</transactionKey>".
			"</merchantAuthentication>".
			"<customerProfileId>".$mCustomerProfileID."</customerProfileId>".
			"<customerPaymentProfileId>".$mPaymentProfileID."</customerPaymentProfileId>".
			"</deleteCustomerPaymentProfileRequest>";
			$response = send_xml_request($content);
			$parsedresponse = parse_api_response($response);
			if ($parsedresponse->messages->resultCode=="Ok") 
			{
				if ($mDefaultCard==1)
				{
					$mResult = dbAbstract::Execute("SELECT id FROM general_detail WHERE id_2=".$mUserID." AND data_2<>'".$mPaymentProfileID."' LIMIT 1");
					if (dbAbstract::returnRowsCount($mResult)>0)
					{
						$mRow = dbAbstract::returnObject($mResult);
						dbAbstract::Update("UPDATE general_detail SET data_3=1 WHERE id_2=".$mUserID." AND id=".$mRow->id);
					}
				}
				
				if (dbAbstract::Delete("DELETE FROM general_detail WHERE id=".$pTokenID,0,1))
				{
					return "Success";
				}
				else
				{
					return "Error: Unable to delete card from Database. - Auth.Net";
				}
	
			}
			else
			{
				return "Error: Issue in Authorize.Net API response - Auth.Net.";
			}
		}
		else
		{
			return "Error: Customer profile not found. - Auth.Net";
		}
	}
	else
	{
		return "Error: Payment profile(Token) not found. - Auth.Net";
	}
}

function DeleteNMIToken($pTokenID, $pUserName, $pPassword)
{
	$mResult = dbAbstract::Execute("SELECT id_2, data_2, data_3 FROM general_detail WHERE id=".$pTokenID);
	if (mysql_num_rows($mResult)>0)
	{
		$mRow = dbAbstract::returnRowsCount($mResult);
		$mPaymentProfileID = $mRow->data_2; //Token
		$mUserID = $mRow->id_2;
		$mDefaultCard = $mRow->data_3;
		$mCardToken = $mRow->data_2;
		include_once ("../lib/nmi_api/gwapi.php");
		$gw = new gwapi;
		
		$gw->setLogin($pUserName, $pPassword);
		$response = $gw->doDelete($mCardToken);
		if ($response==APPROVED) 
		{
			if ($mDefaultCard==1)
			{
				$mResult = dbAbstract::Execute("SELECT id FROM general_detail WHERE id_2=".$mUserID." AND data_2<>'".$mPaymentProfileID."' LIMIT 1");
				if (dbAbstract::returnRowsCount($mResult)>0)
				{
					$mRow = dbAbstract::returnObject($mResult);
					dbAbstract::Update("UPDATE general_detail SET data_3=1 WHERE id_2=".$mUserID." AND id=".$mRow->id);
				}
			}
			
			if (dbAbstract::Delete("DELETE FROM general_detail WHERE id=".$pTokenID,0,1))
			{
				return "Success";
			}
			else
			{
				return "Error: Unable to delete card from Database. - NMI";
			}

		}
		else
		{
			return "Error: Issue in NMI API response. - NMI";
		}
	}
	else
	{
		return "Error: Payment profile(Token) not found. - NMI";
	}
}

function DeleteGGe4Token($pTokenID, $pUserName, $pPassword) //Have to implement TransArmor (Token) Deletion after getting reply from GGe4 support
{
	$mResult = dbAbstract::Execute("SELECT id_2, data_2, data_3 FROM general_detail WHERE id=".$pTokenID);
	if (dbAbstract::returnRowsCount($mResult)>0)
	{
		$mRow = dbAbstract::returnObject($mResult);
		$mPaymentProfileID = $mRow->data_2; //Token
		$mUserID = $mRow->id_2;
		$mDefaultCard = $mRow->data_3;
		
		$mRow = dbAbstract::returnObject($mResult);
		$mCustomerProfileID = $mRow->profile_id;

		if ($mDefaultCard==1)
		{
			$mResult = dbAbstract::Execute("SELECT id FROM general_detail WHERE id_2=".$mUserID." AND data_2<>'".$mPaymentProfileID."' LIMIT 1");
			if (dbAbstract::returnRowsCount($mResult)>0)
			{
				$mRow = dbAbstract::returnObject($mResult);
				dbAbstract::Update("UPDATE general_detail SET data_3=1 WHERE id_2=".$mUserID." AND id=".$mRow->id);
			}
		}
		
		if (dbAbstract::Delete("DELETE FROM general_detail WHERE id=".$pTokenID,0,1))
		{
			return "Success";
		}
		else
		{
			return "Error: Unable to delete card from Database. - GGe4";
		}
	}
	else
	{
		return "Error: Payment profile(Token) not found. - GGe4";
	}
}

function saveTokenWithExpiry($cust_id, $objRestaurant, $secure_data,$default,$pCardExpiry,$email)
{
	error_reporting(0);
	$x_card_num=$secure_data;
	$x_exp_date=$pCardExpiry;
	$token='0';
	
	$gateway_token=0;
	if ($objRestaurant->payment_gateway == "authoriseDotNet") 
	{
		$objRestaurant->payment_gateway = "AuthorizeNet";
	}
	
	$error_message='';
      
	require_once '../classes/gateways/tokenization/'. $objRestaurant->payment_gateway .'.php';
	
	if(!empty ($error_message))
	{
		echo json_encode(array("css"=>"alert-error","message"=>$error_message));
	}
	else{
		$rs = dbAbstract::Execute("select * from bh_sso_user WHERE email = '". $email ."'");
		$userResult = dbAbstract::returnObject($rs);
		$type=  substr($secure_data, 0,1);
		$cc=  substr($secure_data, -4, 4);
		$default = ($default==0)? 0:1;
		
		$result=dbAbstract::ExecuteObject("select count(*) as total from general_detail  where id_2=". $cust_id ." and data_type=$type and data_1=$cc");
			 
		if (($result->total==0) && ($type!=0) && ($cc!=0))
		{
			if(dbAbstract::Insert("insert into general_detail(sso_user_id, id_2,data_type,data_1,data_2,data_3,card_expiry) 
						values(".$userResult->id.",". $cust_id  ." ,'$type' ,'$cc','$gateway_token','$default',".$pCardExpiry.")"))
			{			
				return true;
			}else{ 
				return false;
			}
		}
		else 
		{
			return false;
		}
	}
}
/* General (Helping) Functions Ends Here */
?>
