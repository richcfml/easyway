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

                if(!empty($_GET['firstname']) && !empty($_GET['lastname']) && !empty($_GET['email']) && !empty($_GET['password']))
                {
                        if(strlen( $_GET['firstname']) > 50 || !preg_match('/^[ A-Za-z0-9-\.]*$/',$_GET['firstname']))
                        {
                            $mReturn = errorFunction("13","First Name only allows alpha numeric and maximum 35 characters.","Incorrect first name.","Attribute Error");
                        }
                        else if(strlen( $_GET['lastname']) > 50 || !preg_match('/^[ A-Za-z0-9-\.]*$/',$_GET['lastname']))
                        {
                            $mReturn = errorFunction("14","Last Name only allows alpha numeric and maximum 35 characters.","Incorrect last name.","Attribute Error");
                        }
                        else if(!preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}+$/',$_GET['email']))
                        {
                            $mReturn = errorFunction("15","Incorrect email address","Incorrect email address","Attribute Error");
                        }
                        else if(!preg_match('/^.*(?=.*\d)(?=.*[a-zA-Z]).*$/', $_GET['password']) || strlen( $_GET['password']) > 20 || strlen( $_GET['password']) < 8)
                        {
                            $mReturn = errorFunction("16","Password must be alphanumeric with maximum 20 characters and minimum 8 characters.","Incorrect Password","Attribute Error");
                        }
                        else
                        {
                                $mSQL = "SELECT email FROM bh_sso_user WHERE email='".prepareStringForMySQL($_GET['email'] )."' AND password !='NULL'";
                                Log::write("check email exist - sso.php - IF", "QUERY --".$mSQL, 'sso', 1);
                                $user_qry  = mysql_query($mSQL);

                                if(mysql_num_rows($user_qry)>0 )
                                {
                                    $mReturn = errorFunction("17","Email already exists","Email already exists","Data Error");
                                }
                                else
                                {
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
                                            , ssoAccountID='". $mVerifyRequest ."'
                                            , status=1";

                                    Log::write("Add SSO user - sso.php - IF", "QUERY --".$qry, 'sso', 1);
                                    $sso_qry = mysql_query($qry);
                                    $sso_id = mysql_insert_id();
                                    $mReturn[] = array("response" => "Thanks for registering!","sso_acc" =>$sso_id);
                                }
                        }
                    }
                else
                {
                    $mReturn = errorFunction("18","Invalid call, Parameter is missing.","Invalid call.!","Attribute Error");
                }

            $json = json_encode($mReturn,true);
            echo $json;
        }
        else if (isset($_GET["signIn"]) || isset($_GET["signin"]))
        {
            session_regenerate_id(true);
            $qry = "select * from bh_sso_user WHERE email = '". $_GET['email'] ."' and password = '".$_GET['password']."'";
            Log::write("Sign In - sso.php - IF", "QUERY --".$mSQL, 'sso', 1);
            $userResult = mysql_fetch_object(mysql_query(($qry)));
            Log::write('User Response Array - sso.php', print_r($userResult,true), 'sso', 1);

            if ($userResult->id >0 && $userResult->status ==1)
            {   
                $mReturn = array("response" => "Account Verified","session_id" => session_id());
            }
            else if ($userResult->id >0 && $userResult->status ==0)
            {
                $mReturn = errorFunction("19","SSO account is not activated","Account is not activated.!","Attribute Error");
            }
            else
            {
                $mReturn = errorFunction("21","Invalid email or password.","Invalid call.!","Attribute Error");
            }
            $json = json_encode($mReturn,true);
            echo $json;
        }

        else if (isset($_GET["getuserdetail"]))
        {
            if(isset($_GET['email']) && !empty($_GET['email']))
            {
                
                    $qry = "select * from bh_sso_user WHERE email = '". $_GET['email'] ."'";
                    Log::write("Get user Detail - sso.php - IF", "QUERY --".$qry, 'sso', 1);
                    $userResult = mysql_fetch_object(mysql_query(($qry)));
                    Log::write('User Response Array - sso.php', print_r($userResult,true), 'sso', 1);
                    if (!empty($userResult))
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
                    }
                

            }
            else
            {
                $mReturn = errorFunction("22","Invalid call, Provide SSO email.","Invalid call.","Attribute Error");
            }
            $json = json_encode($mReturn,true);
            echo $json;

        }

        else if (isset($_GET["updateuserdetail"]))
        {   
            if(isset($_GET['email']) && !empty($_GET['email']))
            {
                
                $qry = "select * from bh_sso_user WHERE email = '". $_GET['email'] ."'";
                $userResult = mysql_fetch_object(mysql_query(($qry)));
                Log::write('User Response Array - sso.php', print_r($userResult,true), 'sso', 1);

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

                $update_qry = "UPDATE bh_sso_user SET
                              firstName= '".prepareStringForMySQL($firstName)."'
                            , lastName='".prepareStringForMySQL($lastName)."'
                            , city='".prepareStringForMySQL($city)."'
                            , state='".prepareStringForMySQL($state)."'
                            , password='".$password."'
                            , address1='".prepareStringForMySQL($address1)."'
                            , zip='".prepareStringForMySQL($zip)."'
                            , phone='".prepareStringForMySQL($phone)."'
                            , loyality_member='".$loyality_member."'
                            , address2='".prepareStringForMySQL($address2)."'    
                             WHERE email = '". $_GET['email'] ."'";
                Log::write("Update SSO user - sso.php - IF", "QUERY --".$update_qry, 'sso', 1);
                $query = mysql_query($update_qry);
                $afftected_rows = mysql_affected_rows();
                if($afftected_rows)
                {
                    $mReturn[] = array("response" => "Account Updated successfully");
                }
                else
                {
                    $mReturn[] = array("response" => "Some thing went wrong.");
                }
            }
            else
            {
                $mReturn = errorFunction("22","Invalid call, Provide SSO email.","Invalid call.","Attribute Error");
            }
            $json = json_encode($mReturn,true);
            echo $json;
        }

        else if (isset($_GET["signOut"]) || isset($_GET["signout"]))
        {
            if(!empty($_GET['email']))
            {
                session_regenerate_id(true);
                $mReturn = array("response" => "You have logged out");
            }
            else
            {
                $mReturn = errorFunction("22","Invalid call, Provide SSO email.","Invalid call.","Attribute Error");
            }
            $json = json_encode($mReturn,true);
            echo $json;
        }

        else if (isset($_GET["resetPassword"]) || isset($_GET["resetpassword"]))
        {
            $qry = "select password from bh_sso_user WHERE email = '". $_GET['email'] ."'";
            Log::write("Reset Password - sso.php - IF", "QUERY --".$qry, 'sso', 1);
            $userResult = mysql_fetch_object(mysql_query($qry));
            Log::write('User Response Array - sso.php', print_r($userResult,true), 'sso', 1);

            if (!empty($userResult->password))
            {  
                $mSubject = "Easywayordering Account Password Reminder";
                $mMessage ="Thank you for visiting www.easywayordering.com. We hope that you will find our easywayordering system helpful for
                               your work and home food delivery needs.<br/><br/>
                               Following are your required login information:<br/>
                               Your login: ". $_GET['email'] ."<br/>
                               Your password: ". $userResult->password ."<br/><br/>
                               We thank you for your business and look forward to serving you!<br/><br/>
                               Kind regards,<br/><br/>

                               www.easywayordering.com/<br/>";

                $objMail->sendTo($mMessage, $mSubject, $_GET['email'], true);
                $mReturn = array("response" => "We have sent you an email");
            }
            else
            {
                $mReturn = errorFunction("23","Email does not exist.","Email does not exist.","Data Error");
            }
            $json = json_encode($mReturn,true);
            echo $json;
        }
}
else if ($mVerifyRequest==0) //This will never happen
{
    $mReturn = errorFunction("10", "Apikey not specified.", "Invalid call.", "Attribute Error");
    $json = json_encode($mReturn,true);
    echo $json;
}
else if ($mVerifyRequest==2) //Session ID not present
{
    $mReturn = errorFunction("10", "Apikey not specified.", "Invalid call.","Attribute Error");
    $json = json_encode($mReturn,true);
    echo $json;
}
else if ($mVerifyRequest==3) //Session ID expred
{
    $mReturn = errorFunction("11", "Api key is not valid", "Api key is not valid", "Data Error");
    $json = json_encode($mReturn,true);
    echo $json;
}
/* General (Helping) Functions Starts Here */
function adminLogin()
{   
    $qry = "Select * from users where ewo_api_key = '".$_GET["apikey"]."'";
    Log::write("Admin Login - sso.php - IF", "QUERY --".$qry, 'menu', 1);
    $exeQry = mysql_query($qry);
    $resultArray = mysql_fetch_object($exeQry);
    $countRows = mysql_num_rows($exeQry);
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
        $Qry = mysql_query("Select * from users where ewo_api_key = '".$_GET["apikey"]."'");
        $qryCount = mysql_num_rows($Qry);
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
/* General (Helping) Functions Ends Here */
?>
