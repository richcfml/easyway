<?php
include("../includes/config.php");
require_once('../classes/users.php');
require	"../includes/class.phpmailer.php";
include "../classes/restaurant.php";

if (isset($_GET['apikey']))
{
    if (isset($_GET['apireq']))
    {
        if ($_GET['apireq']=='deliver')
        {
            if(isset ($_GET['address']))
            {
                checkDelivery();
            }
            else
            {   
                echo("Bad Request.");
            }
        }
        else if ($_GET['apireq']=='open')
        {
            isOpen();
        }
        else if ($_GET['apireq']=='login')
        {
            login();
        }
        else if ($_GET['apireq']=='distance')
        {
            if (isset($_GET['address']) && isset($_GET['distance']))
            {
                checkDistance();
            }
            else
            {   
                echo("Bad Request.");
            }
        }
        else
        {
            echo("Bad Request.");
        }
    }
    else
    {
        echo("Bad Request.");
    }
}
else
{
    echo("API Key not found.");
}

function checkDelivery() 
{
    global $SiteUrl;
    $mAPIKey = $_GET['apikey'];
    $rest_url = array();
    $arr_restId = array();
    $arr_restslugs = array();
    $deliver_charges = array();
    $min_total = array();
    $fax = array();
    $email = array();
    $address = array();
    $phone = array();
    $res_name = array();

    $day_name = date('l');
    if ($day_name == 'Monday') 
    {
        $day_of_week = 0;
    } 
    else if ($day_name == 'Tuesday') 
    {
        $day_of_week = 1;
    } 
    else if ($day_name == 'Wednesday') 
    {
        $day_of_week = 2;
    } 
    else if ($day_name == 'Thursday') 
    {
        $day_of_week = 3;
    } 
    else if ($day_name == 'Friday') 
    {
        $day_of_week = 4;
    } 
    else if ($day_name == 'Saturday') 
    {
        $day_of_week = 5;
    } 
    else if ($day_name == 'Sunday') 
    {
        $day_of_week = 6;
    }

    $mResult = dbAbstract::Execute("SELECT id, type FROM users WHERE ewo_api_key='".$mAPIKey."'");
    if (dbAbstract::returnRowsCount($mResult)>0)
    {
        $mRow = dbAbstract::returnObject($mResult);
        $mUserID = $mRow->id;
        $mType = $mRow->type;

        if (trim(strtolower($mType))=="admin")
        {
            $activeres = dbAbstract::Execute("SELECT id, time_zone_id FROM resturants WHERE rest_open_close = 1  And delivery_offer = 1 AND status=1");
        }
        else if (trim(strtolower($mType))=="reseller")
        {
            $activeres = dbAbstract::Execute("SELECT id, time_zone_id FROM resturants WHERE rest_open_close = 1  And delivery_offer = 1 AND owner_id IN (SELECT client_id FROM reseller_client WHERE reseller_id=".$mUserID.") AND status=1");
        }
        else if (trim(strtolower($mType))=="store owner")
        {
            $activeres = dbAbstract::Execute("SELECT id, time_zone_id FROM resturants WHERE rest_open_close = 1  And delivery_offer = 1 AND owner_id=".$mUserID." AND status=1");
        }
        else
        {
            echo("Error: User type not found.");
            exit;
        }

        while ($rest_id = dbAbstract::returnObject($activeres)) 
        {
            $businessHrQry = dbAbstract::Execute("SELECT open, close FROM business_hours WHERE day = ".$day_of_week." AND rest_id = ".$rest_id->id);
            $business_hours = dbAbstract::returnObject($businessHrQry);
            $timezoneQry = dbAbstract::Execute("SELECT time_zone FROM times_zones WHERE id = ".$rest_id->time_zone_id);
            @$timezoneRs = dbAbstract::returnArray($timezoneQry);
            date_default_timezone_set($timezoneRs['time_zone']);
            $current_time = date("Hi", time());
            if (isset($_GET["OPEN"]) || isset($_GET["open"]))
            {
                $mOpenVal = strtolower(trim($_GET["OPEN"]));
                if ($mOpenVal=="yes")
                {
                    if (($current_time >= $business_hours->open) && ($current_time <= $business_hours->close))
                    {
                        $rest_url[] = dbAbstract::ExecuteArray("SELECT * FROM resturants WHERE id = ".$rest_id->id);
                    }
                }
                else
                {
                    $rest_url[] = dbAbstract::ExecuteArray("SELECT * FROM resturants WHERE id = ".$rest_id->id);
                }
            }
            else
            {
                $rest_url[] = dbAbstract::ExecuteArray("SELECT * FROM resturants WHERE id = ".$rest_id->id);
            }
        }

        $getaddress = $_GET['address'];
        $addresslink = str_replace(' ', '+', $getaddress);
        $result = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$addresslink.'&sensor=false');
        $arr = array();
        $array = (json_decode($result, true));
        if (!empty($array['results'][0]['geometry']['location']['lat'])) 
        {
            foreach ($rest_url as $rest_arr) 
            {
                $lat = $array['results'][0]['geometry']['location']['lat'];
                $long = $array['results'][0]['geometry']['location']['lng'];

                $qry = "SELECT * FROM resturants where id = ".$rest_arr['id'];
                $vertices = dbAbstract::ExecuteArray($qry);
                if ($vertices['delivery_option'] == 'delivery_zones') 
                {
                    if (!empty($vertices['zone1_coordinates'])) 
                    {
                        $zone1_coordinates = explode('~', $vertices['zone1_coordinates']);
                    } 
                    else 
                    {
                        $zone1_coordinates = getCoordinates('0.02', $rest_arr['id']);
                    }

                    if (!empty($vertices['zone2_coordinates'])) {
                        $zone2_coordinates = explode('~', $vertices['zone2_coordinates']);
                    } 
                    else 
                    {
                        $zone2_coordinates = getCoordinates('0.025', $rest_arr['id']);
                    }

                    if (!empty($vertices['zone3_coordinates'])) 
                    {
                        $zone3_coordinates = explode('~', $vertices['zone3_coordinates']);
                    } 
                    else 
                    {
                        $zone3_coordinates = getCoordinates('0.03', $rest_arr['id']);
                    }

                    $x = $lat;
                    $y = $long;

                    if (!empty($zone1_coordinates)) 
                    {
                        if ($vertices['zone1'] && pointInPolygon($x, $y, $zone1_coordinates)) 
                        {
                            $arr_restId[] = $rest_arr['id'];
                            $arr_restslugs[] = $rest_arr['url_name'];
                            $deliver_charges[] = $rest_arr['zone1_delivery_charges'];
                            $min_total[] = $rest_arr['zone1_min_total'];
                            $fax[] = $rest_arr['fax'];
                            $email[] = $rest_arr['email'];
                            $address[] = $rest_arr['rest_address']." ".$rest_arr['rest_city']." ".$rest_arr['rest_state'];
                            $phone[] =  $rest_arr['phone'];
                            $res_name[] =  $rest_arr['name'];
                        } 
                        else if ($vertices['zone2'] && pointInPolygon($x, $y, $zone2_coordinates)) 
                        {
                            $arr_restId[] = $rest_arr['id'];
                            $arr_restslugs[] = $rest_arr['url_name'];
                            $deliver_charges[] = $rest_arr['zone2_delivery_charges'];
                            $min_total[] = $rest_arr['zone2_min_total'];
                            $fax[] = $rest_arr['fax'];
                            $email[] = $rest_arr['email'];
                            $address[] = $rest_arr['rest_address']." ".$rest_arr['rest_city']." ".$rest_arr['rest_state'];
                            $phone[] =  $rest_arr['phone'];
                            $res_name[] =  $rest_arr['name'];
                        } 
                        else if ($vertices['zone3'] && pointInPolygon($x, $y, $zone3_coordinates)) 
                        {
                            $arr_restId[] = $rest_arr['id'];
                            $arr_restslugs[] = $rest_arr['url_name'];
                            $deliver_charges[]  = $rest_arr['zone3_delivery_charges'];
                            $min_total[] = $rest_arr['zone3_min_total'];
                            $fax[] = $rest_arr['fax'];
                            $email[] = $rest_arr['email'];
                            $address[] = $rest_arr['rest_address']." ".$rest_arr['rest_city']." ".$rest_arr['rest_state'];
                            $phone[] =  $rest_arr['phone'];
                            $res_name[] =  $rest_arr['name'];
                        }
                    }
                } 
                else 
                {
                    $qry = "SELECT * FROM rest_langitude_latitude WHERE rest_id = ".$rest_arr['id'];
                    $lat_lon = dbAbstract::ExecuteArray($qry);
                    if (!empty($lat_lon)) 
                    {
                        $lon2 = $lat_lon['rest_longitude'];
                        $lat2 = $lat_lon['rest_latitude'];

                        $theta = $long - $lon2;
                        $dist = sin(deg2rad(floatval($lat))) * sin(deg2rad(floatval($lat2))) + cos(deg2rad(floatval($lat))) * cos(deg2rad(floatval($lat2))) * cos(deg2rad(floatval($theta)));
                        $dist = acos($dist);
                        $dist = rad2deg($dist);
                        $miles = $dist * 60 * 1.1515;
                        $radius = $rest_arr['delivery_radius'];
                        if ($miles < $radius) 
                        {
                            $arr_restId[] = $rest_arr['id'];
                            $arr_restslugs[] = $rest_arr['url_name'];
                            $deliver_charges[] = $rest_arr['delivery_charges'];
                            $min_total[] = $rest_arr['order_minimum'];
                            $fax[] = $rest_arr['fax'];
                            $email[] = $rest_arr['email'];
                            $address[] = $rest_arr['rest_address']." ".$rest_arr['rest_city']." ".$rest_arr['rest_state'];
                            $phone[] =  $rest_arr['phone'];
                            $res_name[] =  $rest_arr['name'];
                        }
                    }
                }
            }

            $result = array();
            foreach ($arr_restslugs as $i => $val) 
            {
                $result[] = array("name" =>$res_name[$i],
                            "slug" =>$val,
                            "email" => $email[$i],
                            "address" => $address[$i],
                            "fax" => $fax[$i],
                            "Phone" => $phone[$i],
                            "deliver_charges" =>$deliver_charges[$i],
                            "min_total" =>$min_total[$i],
                            "url" => $SiteUrl.$val."/"
                );
            }

            $json = json_encode($result,true);
            echo($json);
        }
    }
    else
    {
        echo("Error: User authentication using API Key failed.");
        exit;
    }
}

function pointInPolygon($x, $y, $coordinates) 
{
    foreach ($coordinates as $arr1) 
    {
        list($polyX[], $polyY[]) = explode(',', $arr1);
    }

    $polySides = count($polyX);
    $j = $polySides - 1;
    $oddNodes = 0;
    for ($i = 0; $i < $polySides; $i++) 
    {
        if ($polyY[$i] < $y && $polyY[$j] >= $y || $polyY[$j] < $y && $polyY[$i] >= $y) 
        {
            if ($polyX[$i] + ($y - $polyY[$i]) / ($polyY[$j] - $polyY[$i]) * ($polyX[$j] - $polyX[$i]) < $x) 
            {
                $oddNodes = !$oddNodes;
            }
        }
        $j = $i;
    }
    return $oddNodes;
}

function getCoordinates($radius, $rest_id) 
{
    $coordinates = array();
    $qry = "Select * from rest_langitude_latitude where rest_id = " . $rest_id . "";
    $lat_lon = dbAbstract::ExecuteArray($qry);

    if (!empty($lat_lon)) 
    {
        $lon2 = $lat_lon['rest_longitude'];
        $lat2 = $lat_lon['rest_latitude'];
        for ($i = 0; $i < 12; $i++) 
        {
            $x = ($lat2 + $radius * cos(2 * PI() * $i / 12));
            $y = ($lon2 + $radius * sin(2 * PI() * $i / 12));
            $coordinates[] = $x . "," . $y;
        }
        return $coordinates;
    }
}

function isOpen() 
{
    $mAPIKey = $_GET['apikey'];
    $arr_resturl=array();
    $day_name=date('l');
    if($day_name == 'Monday') 
    {
        $day_of_week = 0;
    } 
    else if($day_name == 'Tuesday') 
    {
        $day_of_week = 1;
    } 
    else if($day_name == 'Wednesday') 
    {
        $day_of_week = 2;
    } 
    else if($day_name == 'Thursday') 
    {
        $day_of_week = 3;
    } 
    else if($day_name == 'Friday') 
    {
        $day_of_week = 4;
    } 
    else if($day_name == 'Saturday') 
    {
        $day_of_week = 5;
    } 
    else if($day_name == 'Sunday') 
    {
        $day_of_week = 6;
    }

    $mResult = dbAbstract::Execute("SELECT id, type FROM users WHERE ewo_api_key='".$mAPIKey."'");
    if (dbAbstract::returnRowsCount($mResult)>0)
    {
        $mRow = dbAbstract::returnObject($mResult);
        $mUserID = $mRow->id;
        $mType = $mRow->type;

        if (trim(strtolower($mType))=="admin")
        {
            $activeres =  dbAbstract::Execute("SELECT id,time_zone_id FROM resturants WHERE rest_open_close = 1 AND status=1");
        }
        else if (trim(strtolower($mType))=="reseller")
        {
            $activeres = dbAbstract::Execute("SELECT id,time_zone_id FROM resturants WHERE rest_open_close = 1 AND owner_id IN (SELECT client_id FROM reseller_client WHERE reseller_id=".$mUserID.") AND status=1");
        }
        else if (trim(strtolower($mType))=="store owner")
        {
            $activeres =  dbAbstract::Execute("SELECT id,time_zone_id FROM resturants WHERE rest_open_close = 1 AND owner_id=".$mUserID." AND status=1");
        }
        else
        {
            echo("Error: User type not found.");
            exit;
        }

        while($rest_id = dbAbstract::returnObject($activeres))
        {
            $businessHrQry =  dbAbstract::Execute("SELECT open, close FROM business_hours WHERE day = $day_of_week AND rest_id = ". $rest_id->id ."");
            $business_hours=dbAbstract::returnObject($businessHrQry);
            $timezoneQry = dbAbstract::Execute("SELECT  time_zone FROM times_zones WHERE id = ".$rest_id->time_zone_id );
            @$timezoneRs = dbAbstract::returnArray($timezoneQry);
            date_default_timezone_set($timezoneRs['time_zone']);
            $current_time=date("Hi",time());
            if($current_time >= $business_hours->open && $current_time <= $business_hours->close) 
            {
                $rest_url =  dbAbstract::ExecuteObject("SELECT url_name from resturants WHERE id  = ". $rest_id->id ."");
                $arr_resturl[]=$rest_url->url_name;
            }
        }

        foreach ($arr_resturl as $i => $val) 
        {
            $result[] = array("slug" =>$val);  
        }

        $json1=  json_encode($result,true);
        echo($json1);
    }
    else
    {
        echo("Error: User authentication using API Key failed.");
        exit;
    }
}

function login()
{     
    global $SiteUrl;
    $mAPIKey = $_GET['apikey'];
    $objMail = new testmail();
    $loggedinuser = new users();
    $objRestaurant = new restaurant();
    error_reporting(E_ALL);
    $mSalt = hash('sha256', mt_rand(10,1000000));    
    $ePassword = hash('sha256', '12345'.$mSalt);
    $loggedinuser->cust_email=  $_GET['email'];
    $loggedinuser->epassword= $ePassword;
    $loggedinuser->salt= $mSalt ;
    $loggedinuser->cust_your_name= trim($_GET['Fname']);
    $loggedinuser->LastName= trim($_GET['Lname']) ;
    $loggedinuser->street1= trim($_GET['street1']) ;
    $loggedinuser->street2= trim($_GET['street2']) ;
    $loggedinuser->cust_ord_city= trim($_GET['city']) ;
    $loggedinuser->cust_ord_state= trim($_GET['state']) ;
    $loggedinuser->cust_ord_zip= trim($_GET['zip']) ;
    $loggedinuser->cust_phone1= trim($_GET['phone']) ;

    session_start();

    $qry = "Select *,url_name as url from resturants where url_name = '" .$_GET['rest_slug'] . "' AND status=1";
    $objRestaurant = dbAbstract::ExecuteObject($qry);
    $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
    $emailaddress = $_GET['email'];
    if (preg_match($pattern, $emailaddress) === 1) 
    {
        if(!empty($_GET['email'])& !empty($_GET['rest_slug']))
        {
            $user_qry  = dbAbstract::Execute("SELECT * FROM customer_registration WHERE cust_email='".$_GET['email']."' AND resturant_id= ". $objRestaurant->id ."  AND LENGTH(epassword)>0 LIMIT 1");
            if(dbAbstract::returnRowsCount($user_qry)>1)
            { 
                return NULL;
            }

            if(dbAbstract::returnRowsCount($user_qry)==1)
            {
                $user=dbAbstract::returnObject($user_qry,"users");
                $loggedinuser->destroysession();
                $loggedinuser = $user;
                $address1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));

                $loggedinuser->street1 = $address1[0];
                $loggedinuser->street2 = '';
                if (count($address1) >= 1)
                $loggedinuser->street2 = $address1[1];

                $loggedinuser->savetosession();
                redirect($SiteUrl.$_GET['rest_slug']."/?item=menu");
                exit;
            }

            if(dbAbstract::returnRowsCount($user_qry)==0)
            {
                if(!empty( $_GET['Fname'] )& !empty( $_GET['Lname'] )& !empty( $_GET['street1'] )& !empty( $_GET['city'] ) & !empty( $_GET['state'] ) & !empty( $_GET['zip'] ) & !empty( $_GET['phone'] ))
                {
                    $result=$loggedinuser->register($objRestaurant,$objMail);
                }
                else
                {
                    echo("Bad Request.");
                }
            }

            if($result===true)
            {
               $loggedinuser->destroysession();
               $loggedinuser->savetosession();
               redirect($SiteUrl.$_GET['rest_slug']."/?item=menu");
               exit;
            }
        }
        else
        {
            echo("Bad Request.");
        }
    }
    else
    {
        echo("Bad Request.");
    }
}

function checkDistance()
{
    set_time_limit(300);
    ini_set('max_execution_time', 300);
    global $SiteUrl;
    $mAPIKey = $_GET['apikey'];
    $rest_url = array();
    $arr_restId = array();
    $arr_restslugs = array();
    $deliver_charges = array();
    $min_total = array();
    $fax = array();
    $email = array();
    $address = array();
    $phone = array();
    $res_name = array();
    $day_name = date('l');
    if ($day_name == 'Monday') 
    {
        $day_of_week = 0;
    } 
    else if ($day_name == 'Tuesday') 
    {
        $day_of_week = 1;
    } 
    else if ($day_name == 'Wednesday') 
    {
        $day_of_week = 2;
    } 
    else if ($day_name == 'Thursday') 
    {
        $day_of_week = 3;
    } 
    else if ($day_name == 'Friday') 
    {
        $day_of_week = 4;
    } 
    else if ($day_name == 'Saturday') 
    {
        $day_of_week = 5;
    } 
    else if ($day_name == 'Sunday') 
    {
        $day_of_week = 6;
    }

    $mResult = dbAbstract::Execute("SELECT id, type FROM users WHERE ewo_api_key='".$mAPIKey."'");
    if (dbAbstract::returnRowsCount($mResult)>0)
    {
        $mRow = dbAbstract::returnObject($mResult);
        $mUserID = $mRow->id;
        $mType = $mRow->type;

        if (trim(strtolower($mType))=="admin")
        {
            $activeres = dbAbstract::Execute("SELECT id, time_zone_id FROM resturants WHERE rest_open_close = 1  And delivery_offer = 1 AND status=1");
        }
        else if (trim(strtolower($mType))=="reseller")
        {
            $activeres = dbAbstract::Execute("SELECT id, time_zone_id FROM resturants WHERE rest_open_close = 1  And delivery_offer = 1 AND owner_id IN (SELECT client_id FROM reseller_client WHERE reseller_id=".$mUserID.") AND status=1");
        }
        else if (trim(strtolower($mType))=="store owner")
        {
            $activeres = dbAbstract::Execute("SELECT id, time_zone_id FROM resturants WHERE rest_open_close = 1  And delivery_offer = 1 AND owner_id=".$mUserID." AND status=1");
        }
        else
        {
            echo("Error: User type not found.");
            exit;
        }
        $result = array();
        while ($rest_id = dbAbstract::returnObject($activeres)) 
        {
            $businessHrQry = dbAbstract::Execute("SELECT open, close FROM business_hours WHERE day = ".$day_of_week." AND rest_id = ".$rest_id->id);
            $business_hours = dbAbstract::returnObject($businessHrQry);
            $timezoneQry = dbAbstract::Execute("SELECT time_zone FROM times_zones WHERE id = ".$rest_id->time_zone_id);
            @$timezoneRs = dbAbstract::returnArray($timezoneQry);
            date_default_timezone_set($timezoneRs['time_zone']);
            $current_time = date("Hi", time());
            
            $mSourceLatLang = array();
            $mDestinationLatLang = array();
            
            $mSQLLatLang = "SELECT rest_latitude, rest_longitude FROM rest_langitude_latitude WHERE rest_id=".$rest_id->id;
            $mResLatLang  = dbAbstract::Execute($mSQLLatLang);
            if (dbAbstract::returnRowsCount($mResLatLang)>0)
            {
                $mRowLatLang = dbAbstract::returnObject($mResLatLang);
                $mDestinationLatLang = array($mRowLatLang->rest_latitude, $mRowLatLang->rest_longitude);
            }
            if (isset($_GET["OPEN"]) || isset($_GET["open"]))
            {
                $mOpenVal = strtolower(trim($_GET["OPEN"]));
                if ($mOpenVal=="yes")
                {
                    if (($current_time >= $business_hours->open) && ($current_time <= $business_hours->close))
                    {
                        $mSQL = "SELECT * FROM resturants WHERE id = ".$rest_id->id;
                        $mRes = dbAbstract::Execute($mSQL);
                        $mRow = dbAbstract::returnObject($mRes);
                        $mRestaurantAddress = $mRow->rest_address." ".$mRow->rest_city.", ".$mRow->rest_state." ".$mRow->rest_zip;
                        $mUserAddress = $_GET['address'];
                        $mSourceLatLang = getLatLong($mUserAddress);
                        
                        if (count($mDestinationLatLang)==0)
                        {
                            $mDestinationLatLang = getLatLong($mRestaurantAddress);
                        }
                        $mDistance = getDistance($mSourceLatLang, $mDestinationLatLang);
                        if ($mDistance<=$_GET["distance"])
                        {
                            $result[] = array("name" => $mRow->name,
                                        "slug" => $mRow->url_name,
                                        "email" => $mRow->email,
                                        "address" => $mRow->rest_address." ".$mRow->rest_city.", ".$mRow->rest_state." ".$mRow->rest_zip,
                                        "fax" => $mRow->fax,
                                        "Phone" => $mRow->phone,
                                        "deliver_charges" => $mRow->deliver_charges,
                                        "min_total" => $mRow->order_minimum,
                                        "url" => $SiteUrl.$mRow->url_name."/"
                            );
                        }
                    }
                }
                else
                {
                    $mSQL = "SELECT * FROM resturants WHERE id = ".$rest_id->id;
                    $mRes = dbAbstract::Execute($mSQL);
                    $mRow = dbAbstract::returnObject($mRes);
                    $mRestaurantAddress = $mRow->rest_address." ".$mRow->rest_city.", ".$mRow->rest_state." ".$mRow->rest_zip;
                    $mUserAddress = $_GET['address'];
                    $mSourceLatLang = getLatLong($mUserAddress);
                    if (count($mDestinationLatLang)==0)
                    {
                        $mDestinationLatLang = getLatLong($mRestaurantAddress);
                    }
                    $mDistance = getDistance($mSourceLatLang, $mDestinationLatLang);
                    if ($mDistance<=$_GET["distance"])
                    {
                        $result[] = array("name" => $mRow->name,
                                    "slug" => $mRow->url_name,
                                    "email" => $mRow->email,
                                    "address" => $mRow->rest_address." ".$mRow->rest_city.", ".$mRow->rest_state." ".$mRow->rest_zip,
                                    "fax" => $mRow->fax,
                                    "Phone" => $mRow->phone,
                                    "deliver_charges" => $mRow->deliver_charges,
                                    "min_total" => $mRow->order_minimum,
                                    "url" => $SiteUrl.$mRow->url_name."/"
                        );
                    }
                }
            }
            else
            {
                $mSQL = "SELECT * FROM resturants WHERE id = ".$rest_id->id;
                $mRes = dbAbstract::Execute($mSQL);
                $mRow = dbAbstract::returnObject($mRes);
                $mRestaurantAddress = $mRow->rest_address." ".$mRow->rest_city.", ".$mRow->rest_state." ".$mRow->rest_zip;
                $mUserAddress = $_GET['address'];
                $mSourceLatLang = getLatLong($mUserAddress);
                
                if (count($mDestinationLatLang)==0)
                {
                    $mDestinationLatLang = getLatLong($mRestaurantAddress);
                }
                $mDistance = getDistance($mSourceLatLang, $mDestinationLatLang);
                if ($mDistance<=$_GET["distance"])
                {
                    $result[] = array("name" => $mRow->name,
                                "slug" => $mRow->url_name,
                                "email" => $mRow->email,
                                "address" => $mRow->rest_address." ".$mRow->rest_city.", ".$mRow->rest_state." ".$mRow->rest_zip,
                                "fax" => $mRow->fax,
                                "Phone" => $mRow->phone,
                                "deliver_charges" => $mRow->deliver_charges,
                                "min_total" => $mRow->order_minimum,
                                "url" => $SiteUrl.$mRow->url_name."/"
                    );
                }
            }
        }
        $json = json_encode($result,true);
        echo($json);
    }
    else
    {
        echo("Error: User authentication using API Key failed.");
        exit;
    }
}

function getLatLong($pAddress) 
{ 
    $pAddress = str_replace(' ', '+', $pAddress);
    $mUrl = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$pAddress.'&sensor=false';
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $mUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $mGeoLoc = curl_exec($ch);
 
    $json = json_decode($mGeoLoc);
    return array($json->results[0]->geometry->location->lat, $json->results[0]->geometry->location->lng);
}

function getDistance($pSourceLatLang, $pDestinationLatLang) 
{
    $mTheta = $pSourceLatLang[1] - $pDestinationLatLang[1];
    $mDistance = (sin(deg2rad($pSourceLatLang[0])) * sin(deg2rad($pDestinationLatLang[0]))) + (cos(deg2rad($pSourceLatLang[0])) * cos(deg2rad($pDestinationLatLang[0])) * cos(deg2rad($mTheta)));
    $mDistance = acos($mDistance);
    $mDistance = rad2deg($mDistance);
    $mDistance = $mDistance * 60 * 1.1515;
 
    return round($mDistance, 2); 
}
?>
