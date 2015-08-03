<?php
/*
Dated: 18 May 2015
This API is written majorly to entertain the NYCDG (NYC Deli Guide) requests for the client
Boar Head.
For login Username and Password are sent which will be authenticated in SSO (Single Sign On) table,
After successful login the sesssion id will be returned which will be used for all upcoming requests.
This session id will be updated in SSO table for authentication process.
Some DB tables are made specifically for this API, which are
1. bh_SSO (Single Sign On)
3. bh_Favorite
4. bh_Rating
*/
require_once("../includes/config.php");
set_time_limit(500);
ini_set('max_execution_time', 500);
/* Search Functions Starts Here */
$mRecordCount = 0;
$mMaxResults = 10000;
$mReturn = array();
$_GET = array_change_key_case($_GET, CASE_LOWER);
$mVerifyRequest = verifyRequest();
if ($mVerifyRequest==1) //Valid Session
{
    if (isset($_GET["max_results"]))
    {
        if (is_numeric($_GET["max_results"]))
        {
            $mMaxResults = $_GET["max_results"];
        }
    }
    
    $mUserID = 0;
    $mInvalidUser = 0;
    if (isset($_GET["uid"]))
    {
        $mUserID = $_GET["uid"];
        $mSQL = "SELECT id FROM bh_sso_user WHERE id=".$mUserID;
        $mResult = dbAbstract::ExecuteObject($mSQL);
        if ($mResult)
        {
            $mUserID = $mResult->id;
        }
        else
        {
            $mInvalidUser = 1;
        }
    }
    else if (isset($_GET["email"]))
    {
        $mSQL = "SELECT id FROM bh_sso_user WHERE email='".$_GET["email"]."'";
        $mResult = dbAbstract::ExecuteObject($mSQL);
        if ($mResult)
        {
            $mUserID = $mResult->id;
        }
        else
        {
            $mInvalidUser = 1;
        }
    }
    
    if ($mInvalidUser==1)
    {
        $mReturn = errorFunction("1","Invalid user id/email.","Invalid user id/email.","Attribute Error");
    }
    else
    {
        if (isset($_GET["getrestaurants"]) && isset($_GET["getrestaurantdetails"]))
        {
            if (isset($_GET["slugs"])) //Comma Separate Slugs
            {
                $mSlugs = explode(",", $_GET["slugs"]);
                for ($loopCount=0; $loopCount<count($mSlugs); $loopCount++)
                {
                    $mFavorite = 2;
                    $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($mSlugs[$loopCount]))."' AND status = 1 AND bh_restaurant = 1";
                    $rest_url = dbAbstract::ExecuteObject($mSQL);
                    if ($rest_url)
                    {   
                        $tmp = returnArray($rest_url, $mUserID, 0);
                        if ($tmp)
                        {
                            $mReturn[] = $tmp;
                        }
                    }

                }
            }
            else
            {
                $mReturn = errorFunction("2", "Slugs not specified.", "Slugs not specified.", "Attribute Error");
            }
        }
        else if (isset($_GET["getrestaurants"]) && isset($_GET["getfullhours"]))
        {
            if (isset($_GET["slugs"])) //Comma Separate Slugs
            {
                $mSlugs = explode(",", $_GET["slugs"]);
                for ($loopCount=0; $loopCount<count($mSlugs); $loopCount++)
                {
                    $mFavorite = 2;

                    $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($mSlugs[$loopCount]))."'";
                    $rest_url = dbAbstract::ExecuteObject($mSQL);
                    if ($rest_url)
                    {
                        $mFavorite = checkFavorite($mUserID, $rest_url->id);

                        if ($mUserID==0)
                        {
                            $mReturn[] = array(
                                    "name" => replaceSpecialChar($rest_url->name),
                                    "hours" => allBusinessHours($rest_url->id)
                                );
                        }
                        else
                        {
                            $mReturn[] = array(
                                    "name" => replaceSpecialChar($rest_url->name),
                                    "hours" => allBusinessHours($rest_url->id),
                                    "favorite" => $mFavorite
                                );
                        }
                    }
                }
            }
            else
            {
                $mReturn = errorFunction("2", "Slugs not specified.", "Slugs not specified.", "Attribute Error");
            }
        }
        else if (isset($_GET["getrestaurants"]) && isset($_GET["featured"]) && !isset($_GET["locations"]))
        {   
            $mSQL = "SELECT * FROM resturants WHERE status = 1 AND bh_restaurant = 1 AND bh_featured = 1 ORDER BY name";
            $mResFeatured = dbAbstract::Execute($mSQL);

            while ($rest_url = dbAbstract::returnObject($mResFeatured))
            {   
                if ($mRecordCount<$mMaxResults)
                {
                    $mRecordCount = $mRecordCount + 1;
                    
                    $tmp = returnArray($rest_url, $mUserID, 0);
                    if ($tmp)
                    {
                        $mReturn[] = $tmp;
                    }
                }
            }
        }
        else if (isset($_GET["getrestaurants"]))
        {
            $mLat = "";
            $mLong = "";

            if (isset($_GET['deliversto']))
            {
                if (!isset($_GET['latlong']))
                {
                    $getaddress = $_GET['deliversto'];
                    $addresslink = str_replace(' ', '+', $getaddress);
                    $result = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$addresslink.'&sensor=false&key=AIzaSyBOYImEs38uinA8zuHZo-Q9VnKAW3dSrgo');
                    $array = (json_decode($result, true));
                    if (!empty($array['results'][0]['geometry']['location']['lat']))
                    {
                        $mLat = $array['results'][0]['geometry']['location']['lat'];
                        $mLong = $array['results'][0]['geometry']['location']['lng'];
                    }
                }
                else
                {
                    $mTmp = explode(",", $_GET["deliversto"]);
                    $mLat = trim($mTmp[0]);
                    $mLong = trim($mTmp[1]);
                }
            }
            else if (isset($_GET['user_address']))
            {
                if (!isset($_GET['latlong']))
                {
                    $getaddress = $_GET['user_address'];
                    $addresslink = str_replace(' ', '+', $getaddress);
                    $result = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$addresslink.'&sensor=false&key=AIzaSyBOYImEs38uinA8zuHZo-Q9VnKAW3dSrgo');
                    $array = (json_decode($result, true));
                    if (!empty($array['results'][0]['geometry']['location']['lat']))
                    {
                        $mLat = $array['results'][0]['geometry']['location']['lat'];
                        $mLong = $array['results'][0]['geometry']['location']['lng'];
                    }
                }
                else
                {
                    $mTmp = explode(",", $_GET["user_address"]);
                    $mLat = trim($mTmp[0]);
                    $mLong = trim($mTmp[1]);
                }
            }

            $mRes = dbAbstract::Execute("SELECT * FROM resturants WHERE rest_open_close = 1 AND status = 1 AND bh_restaurant = 1 ORDER BY name");
            while ($rest_url = dbAbstract::returnObject($mRes))
            {
                doProcessing($rest_url, $mLat, $mLong, $mUserID);
            }
        }
        else if (isset($_GET["like"]))
        {
            if (isset($_GET["slug"]))
            {
                    $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
                    $rest_url = dbAbstract::ExecuteObject($mSQL);
                    if ($rest_url)
                    {
                        dbAbstract::Insert("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", 0, 2, 1)");
                        $mLikeCount = returnLikeCount($rest_url->id);
                        $mDislikeCount = returnDislikeCount($rest_url->id);
                        $mLikePercentage = 0;

                        if (($mLikeCount==0) && ($mDislikeCount==0))
                        {
                            $mLikePercentage = 0;
                        }
                        else
                        {
                            $mLikePercentage = round(($mLikeCount/($mLikeCount + $mDislikeCount))*100);
                        }
                        $mReturn = array(
                                "successDescription" => "Restaurant liked successfully",
                                "satisfactionPercentage" => $mLikePercentage
                            );
                    }
            }
            else
            {
                $mReturn = errorFunction("2", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
        }
        else if (isset($_GET["dislike"]))
        {
            if (isset($_GET["slug"]))
            {
                if (isset($_GET["options"]) && count($_GET["options"])>0)
                {
                    $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
                    $rest_url = dbAbstract::ExecuteObject($mSQL);
                    if ($rest_url)
                    {
                        $mBRRID = dbAbstract::Insert("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", 0, 2, 0)", 0, 2);
                        
                        $mDislikeComments = "";
                        $mDislikeEmail = "";
                        $mDislikeOptions = prepareStringForMySQL(implode(", ", $_GET["options"]));
                        

                        if (isset($_GET["comments"]))
                        {
                            $mDislikeComments = prepareStringForMySQL($_GET["comments"]);
                        }

                        if (isset($_GET["email"]))
                        {
                            $mDislikeEmail = prepareStringForMySQL($_GET["email"]);
                        }

                        dbAbstract::Insert("INSERT INTO bh_dislike (bh_rest_rating_id, Reason, Comments, Email) VALUES (".$mBRRID.", '".$mDislikeOptions."', '".$mDislikeComments."', '".$mDislikeEmail."')");

                        $mLikeCount = returnLikeCount($rest_url->id);
                        $mDislikeCount = returnDislikeCount($rest_url->id);
                        $mLikePercentage = 0;

                        if (($mLikeCount==0) && ($mDislikeCount==0))
                        {
                            $mLikePercentage = 0;
                        }
                        else
                        {
                            $mLikePercentage = round(($mLikeCount/($mLikeCount + $mDislikeCount))*100);
                        }
                        
                        /*Email Sending Code Starts*/
                        require	"../includes/class.phpmailer.php";
                        $mMailBody = "<div style='font-family: Arial; font-size: 14px; size: 14px; line-height: 2'>";
                        $mMailBody .= "<strong>Restaurant Slug: </strong>".$_GET["slug"]."<br />";
                        $mMailBody .= "<strong>Restaurant Name: </strong>".$rest_url->name."<br />";
                        $mMailBody .= "<strong>Dislike Reason/Options : </strong>".implode(", ", $_GET["options"])."<br />";
                        if ($mDislikeEmail!="")
                        {
                            $mMailBody .= "<strong>Email: </strong>".$mDislikeEmail."<br />";
                        }
                        
                        if ($mDislikeComments!="")
                        {
                            $mMailBody .= "<strong>Comments: </strong>".$mDislikeComments."<br />";
                        }
                        $mMailBody .= "</div>";
                        
                        $objEMail = new testmail();
                        $objEMail->from="info@easywayordering.com";
                        $objEMail->sendTo($mMailBody, "BH restaurant feedback", "BHfeedback@easywayordering.com", true);
                        /*Email Sending Code Ends*/
                        
                        $mReturn = array(
                                "successDescription" => "Restaurant disliked successfully",
                                "satisfactionPercentage" => $mLikePercentage
                            );
                    }
                }
                else
                {
                    $mReturn = errorFunction("24", "Please specify at least one Option.", "Please specify at least one Option.", "Attribute Error");
                }
            }
            else
            {
                $mReturn = errorFunction("2", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
        }
        else if (isset($_GET["favorites"]))
        {
            if (isset($_GET["email"]) || isset($_GET["uid"]))
            {   
                $mysqlqry = dbAbstract::Execute("SELECT DISTINCT R.* FROM resturants R INNER JOIN bh_rest_rating BHR ON BHR.rest_id=R.id WHERE R.status=1 AND R.bh_restaurant=1 AND BHR.user_id=".$mUserID." AND BHR.favorite = 1 ORDER BY R.name");
                if (dbAbstract::returnRowsCount($mysqlqry)>0)
                {
                    while($rest_url = dbAbstract::returnObject($mysqlqry))
                    {
                        $tmp = returnArray($rest_url, $mUserID, 0);
                        if ($tmp)
                        {
                            $mReturn[] = $tmp;
                        }
                    }
                }
                else
                {
                    $mReturn = errorFunction("5", "No Restaurant in your favorite list.", "No Restaurant in your favorite list.","Data Error");
                }
            }
            else
            {
                $mReturn = errorFunction("3", "User id/email not specified.", "Invalid call.", "Attribute Error");
            }
        }
        else if (isset($_GET["addfavorite"]))
        {
            $res_url = $_GET['slug'];

            if (isset($_GET["slug"]))
            {
                if (isset($_GET["email"]) || isset($_GET["uid"]))
                {
                    $res = "SELECT id FROM resturants WHERE url_name = '".$res_url."'";
                    $count_rest  = dbAbstract::Execute($res);
                    $resResult = dbAbstract::returnObject($count_rest);

                    if(dbAbstract::returnRowsCount($count_rest)==0 )
                    {
                         $mReturn = errorFunction("6","No restaurant exists with this slug name.","No restaurant exists with this slug name.","Data Error");
                    }
                    else
                    {
                        $mSQL = "SELECT * FROM bh_rest_rating WHERE rest_id =".$resResult->id." AND user_id =".$mUserID;
                        $user_qry  = dbAbstract::Execute($mSQL);
                        $favoriteRes = dbAbstract::returnObject($user_qry);

                        if(dbAbstract::returnRowsCount($user_qry)>0 )
                        {
                            if($favoriteRes->favorite == 1)
                            {
                                $mReturn[] = errorFunction("7","You have already marked this restaurant as your favorite!","You have already marked this restaurant as your favorite!","Data Error");
                            }
                            else
                            {
                                $record="Update bh_rest_rating SET favorite='1' where user_id=".$mUserID." and rest_id=".$resResult->id;
                                dbAbstract::Update($record);
                                $mReturn[] = array("successDescription" => "Restaurant has been added in your favorite list!");
                            }
                        }
                        else
                        {
                            $record="INSERT INTO bh_rest_rating SET user_id=".$mUserID.", rest_id=".$resResult->id.", favorite=1, Rating=0";
                            dbAbstract::Insert($record);
                            $mReturn[] = array("successDescription" => "Restaurant has been added in your favorite list!");
                        }
                    }
                }
                else
                {
                    $mReturn = errorFunction("3", "User id/email not specified.", "Invalid call.", "Attribute Error");
                }
            }
            else
            {
                $mReturn = errorFunction("2", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
        }
        else if (isset($_GET["removefavorite"]))
        {
            $res_url = $_GET['slug'];

            if (isset($_GET["slug"]))
            {
                if (isset($_GET["email"]) || isset($_GET["uid"]))
                {
                    $res = "SELECT id FROM resturants WHERE url_name = '".$res_url."'";
                    $count_rest  = dbAbstract::Execute($res);
                    $resResult = dbAbstract::returnObject($count_rest);

                    if(dbAbstract::returnRowsCount($count_rest)==0 )
                    {
                         $mReturn = errorFunction("6","No restaurant exists with this slug name.","No restaurant exists with this slug name.","Data Error");
                    }
                    else
                    {
                        $mSQL = "SELECT * FROM bh_rest_rating WHERE rest_id= $resResult->id AND user_id =".$mUserID." AND favorite = 1";
                        $user_qry  = dbAbstract::Execute($mSQL);

                        if(dbAbstract::returnRowsCount($user_qry)==0 )
                        {
                            $mReturn = errorFunction("8","This restaurant is not in your favorite list!","This restaurant is not in your favorite list!","Data Error");
                        }
                        else
                        {
                            $record="Update bh_rest_rating SET favorite='0' where user_id=".$mUserID." and rest_id=".$resResult->id;

                            dbAbstract::Update($record);
                            $mReturn[] = array("successDescription" => "Restaurant has been removed from your favorite list!");
                        }
                    }
                }
                else
                {
                    $mReturn = errorFunction("3", "User id/email not specified.", "Invalid call.", "Attribute Error");
                }
            }
            else
            {
                $mReturn = errorFunction("2", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
        }
        else if (isset($_GET["menus"]))
        {
            if (isset($_GET["slug"]))
            {
                $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
                $rest_url = dbAbstract::ExecuteObject($mSQL);
                if ($rest_url)
                {
                    if (isset($_GET["menu"]))
                    {
                        $mReturn = getMenus($rest_url->id, $_GET["menu"]);
                    }
                    else
                    {
                        $mReturn = getMenus($rest_url->id, "");
                    }
                }
            }
            else
            {
                $mReturn = errorFunction("2", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
        }
        else if (isset($_GET["landingbanner"]))
        {
           
            $mSQL = "SELECT id,header_image FROM resturants WHERE status = 1 AND bh_restaurant = 1";
            $rest_images = dbAbstract::Execute($mSQL);
            if ($rest_images)
            {
                while($images = dbAbstract::returnObject($rest_images))
                {   
                    $mArray[$images->id]['image'] = $SiteUrl."images/resturant_headers/".$images->header_image;
                }
                $mReturn = $mArray;
            }
            
        }
        else if (isset($_GET["favorite"]) && isset($_GET["locations"]))
        {
            if (isset($_GET["email"]) || isset($_GET["uid"]))
            {                                
                $rest_loc = dbAbstract::Execute("SELECT R.*, RLL.rest_latitude, RLL.rest_longitude FROM resturants R INNER JOIN bh_rest_rating BRR ON R.id = BRR.rest_id LEFT JOIN rest_langitude_latitude RLL ON RLL.rest_id = R.id WHERE BRR.favorite = 1 AND BRR.user_id=".$mUserID." AND R.status = 1 AND R.bh_restaurant = 1");
                while($rest_url = dbAbstract::returnObject($rest_loc))
                {
                    $getLatLong = findLatLong($rest_url);
                    if (isset($_GET["detail"]))
                    {
                        $tmp = returnArray($rest_url, $mUserID, 0);
                        if ($tmp)
                        {
                            $mArray[] = $tmp;
                        }
                    }
                    else
                    {
                        $tmp = returnLocationArray($rest_url);
                        if ($tmp)
                        {
                            $mArray[] = $tmp;
                        }
                    }
                }
                $mReturn = $mArray;
            }
            else
            {
                $mReturn = errorFunction("3", "User id/email not specified.", "Invalid call.", "Attribute Error");
            } 
        }
        else if (isset($_GET["highestrated"]) && isset($_GET["locations"]))
        {  
            $rest_loc = dbAbstract::Execute("SELECT R.*, COUNT(Rating) AS RatingCount FROM resturants R INNER JOIN bh_rest_rating BRR ON BRR.rest_id = R.id WHERE BRR.Rating = 1 AND R.status = 1 AND R.bh_restaurant = 1 GROUP BY R.id ORDER BY RatingCount DESC");
            while($rest_url = dbAbstract::returnObject($rest_loc))
            {
                if (isset($_GET["detail"]))
                {
                    $tmp = returnArray($rest_url, $mUserID, 0);
                    if ($tmp)
                    {
                        $mArray[] = $tmp;
                    }
                }
                else
                {
                    $tmp = returnLocationArray($rest_url, 1);
                    if ($tmp)
                    {
                        $mArray[] = $tmp;
                    }
                }
            }
            $sort = array();
            foreach($mArray as $k=>$v) {
                $sort['satisfactionPercentage'][$k] = $v['satisfactionPercentage'];
            }
            array_multisort($sort['satisfactionPercentage'], SORT_DESC, $mArray);
            
            $mReturn = $mArray;
            
            if (isset($_GET["max_results"]))
            {
                if (count($mReturn)>$_GET["max_results"])
                {
                    $mReturn = array_slice($mReturn, 0, $_GET["max_results"]);
                }
            }
        }
        else if (isset($_GET["featured"]) && isset($_GET["locations"]))
        {
            $rest_loc = dbAbstract::Execute("SELECT * FROM resturants WHERE bh_restaurant = 1 AND bh_featured=1 AND status = 1");
            while($rest_url = dbAbstract::returnObject($rest_loc))
            {
                $getLatLong = findLatLong($rest_url);
                if (isset($_GET["detail"]))
                {
                    $tmp = returnArray($rest_url, $mUserID, 0);
                    if ($tmp)
                    {
                        $mArray[] = $tmp;
                    }
                }
                else
                {
                    $tmp = returnLocationArray($rest_url);
                    if ($tmp)
                    {
                        $mArray[] = $tmp;
                    }
                }
            }
            $mReturn = $mArray;
        }
        else
        {   
            $mReturn = errorFunction("9", "Operation not specified or invalid operation.", "Invalid call.", "Operation Error");
        }
    }
}
else if ($mVerifyRequest==0) //This will never happen
{
    $mReturn = errorFunction("10", "Apikey not specified.", "Invalid call.", "Attribute Error");
}
else if ($mVerifyRequest==2) //Session ID not present
{
    $mReturn = errorFunction("10", "Apikey not specified.", "Invalid call.","Attribute Error");
}
else if ($mVerifyRequest==3) //Session ID expired
{
    $mReturn = errorFunction("11", "Api key is not valid", "Api key is not valid", "Data Error");
}

if (count($mReturn)==0)
{
    $mReturn = errorFunction("12","No restaurants found according to provided search criteria.","Empty result set.","Data Error");
}

/*echo("<pre>");
print_r($mReturn);
echo("</pre>");*/

$json = json_encode($mReturn, true);

echo($json);
/* Search Functions Ends Here */

/* General (Helping) Functions Starts Here */
function doProcessing($rest_url, $pLat="", $pLong="", $pUserID=0)
{
    global $mReturn, $SiteUrl, $mRecordCount, $mMaxResults;
    $mUserID = $pUserID;

    if (isset($_GET["deliversto"]))
    {    
        if($rest_url->delivery_offer == 1)
        {
            $mLat = $pLat;
            $mLong = $pLong;

            if ((trim($mLat)!="") && (trim($mLong)!=""))
            {
                if ($rest_url->delivery_option == 'delivery_zones')
                {
                    if (!empty($rest_url->zone1_coordinates))
                    {
                        $zone1_coordinates = explode('~', $rest_url->zone1_coordinates);
                    }
                    else
                    {
                        $zone1_coordinates = getCoordinates('0.02', $rest_url->id);
                    }

                    if (!empty($rest_url->zone2_coordinates)) {
                        $zone2_coordinates = explode('~', $rest_url->zone2_coordinates);
                    }
                    else
                    {
                        $zone2_coordinates = getCoordinates('0.025', $rest_url->id);
                    }

                    if (!empty($rest_url->zone3_coordinates))
                    {
                        $zone3_coordinates = explode('~', $rest_url->zone3_coordinates);
                    }
                    else
                    {
                        $zone3_coordinates = getCoordinates('0.03', $rest_url->id);
                    }

                    $x = $mLat;
                    $y = $mLong;

                    if (!empty($zone1_coordinates))
                    {
                        if ($rest_url->zone1 && pointInPolygon($x, $y, $zone1_coordinates))
                        {
                            if ($mRecordCount<$mMaxResults)
                            {
                                $mRecordCount = $mRecordCount + 1;
                                $tmp = returnArray($rest_url, $mUserID, 1);
                                if ($tmp)
                                {
                                    $mReturn[] = $tmp;
                                }
                            }
                        }
                        else if ($rest_url->zone2 && pointInPolygon($x, $y, $zone2_coordinates))
                        {
                            if ($mRecordCount<$mMaxResults)
                            {
                                $mRecordCount = $mRecordCount + 1;
                                $tmp = returnArray($rest_url, $mUserID, 2);
                                if ($tmp)
                                {
                                    $mReturn[] = $tmp;
                                }
                            }
                        }
                        else if ($rest_url->zone3 && pointInPolygon($x, $y, $zone3_coordinates))
                        {
                            if ($mRecordCount<$mMaxResults)
                            {
                                $mRecordCount = $mRecordCount + 1;
                                $tmp = returnArray($rest_url, $mUserID, 3);
                                if ($tmp)
                                {
                                    $mReturn[] = $tmp;
                                }
                            }
                        }
                    }
                }
                else
                {
                    $lon2 = "";
                    $lat2 = "";
                    $qry = "SELECT * FROM rest_langitude_latitude WHERE rest_id = ".$rest_url->id;
                    $lat_lon = dbAbstract::ExecuteObject($qry);
                    if (empty($lat_lon))
                    {
                        $mRestaurantAddress = $rest_url->rest_address." ".$rest_url->rest_city.", ".$rest_url->rest_state." ".$rest_url->rest_zip;
                        $mRestaurantLatLong = getLatLong($mRestaurantAddress);

                        $result = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$mRestaurantLatLong.'&sensor=false');

                        $array = (json_decode($result, true));
                        if (!empty($array['results'][0]['geometry']['location']['lat']))
                        {
                            $lat2 = $array['results'][0]['geometry']['location']['lat'];
                            $lon2 = $array['results'][0]['geometry']['location']['lng'];
                            dbAbstract::Update("UPDATE rest_langitude_latitude SET rest_latitude='".$lat2."', rest_longitude='".$lon2."' WHERE rest_id = ".$rest_url->id);
                        }
                    }
                    else
                    {
                        $lon2 = $lat_lon['rest_longitude'];
                        $lat2 = $lat_lon['rest_latitude'];
                    }

                    if (($lon2!="") && ($lat2!=""))
                    {
                        $theta = $mLong - $lon2;
                        $dist = sin(deg2rad(floatval($mLat))) * sin(deg2rad(floatval($lat2))) + cos(deg2rad(floatval($mLat))) * cos(deg2rad(floatval($lat2))) * cos(deg2rad(floatval($theta)));
                        $dist = acos($dist);
                        $dist = rad2deg($dist);
                        $miles = $dist * 60 * 1.1515;
                        $radius = $rest_url->delivery_radius;
                        if ($miles < $radius)
                        {
                            if ($mRecordCount<$mMaxResults)
                            {
                                $mRecordCount = $mRecordCount + 1;
                                $tmp = returnArray($rest_url, $mUserID, 0);
                                if ($tmp)
                                {
                                    $mReturn[] = $tmp;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    else if (isset($_GET["distance"]))
    {
        $mLat = $pLat;
        $mLong = $pLong;

        $mSourceLatLong = array();
        $mDestinationLatLong = array();

        $mSourceLatLong = array($pLat, $pLong);

        $mSQLLatLong = "SELECT rest_latitude, rest_longitude FROM rest_langitude_latitude WHERE rest_id=".$rest_url->id;
        $mResLatLong  = dbAbstract::Execute($mSQLLatLong);
        if (dbAbstract::returnRowsCount($mResLatLong)>0)
        {
            $mRowLatLong = dbAbstract::returnObject($mResLatLong);
            $mDestinationLatLong = array($mRowLatLong->rest_latitude, $mRowLatLong->rest_longitude);
        }

        if (count($mDestinationLatLong)==0)
        {
            $mRestaurantAddress = $rest_url->rest_address." ".$rest_url->rest_city.", ".$rest_url->rest_state." ".$rest_url->rest_zip;
            $mDestinationLatLong = getLatLong($mRestaurantAddress);
            dbAbstract::Update("UPDATE rest_langitude_latitude SET rest_latitude='".$mDestinationLatLong[0]."', rest_longitude='".$mDestinationLatLong[1]."' WHERE rest_id = ".$rest_url->id);
        }

        $mDistance = getDistance($mSourceLatLong, $mDestinationLatLong);
        
        if ($mDistance<=$_GET["distance"])
        {
            if(isset($_GET['locations']))
            {
                if ($mRecordCount<$mMaxResults)
                {
                    $mRecordCount = $mRecordCount + 1;
                    $tmp = returnLocationArray($rest_url);
                    if ($tmp)
                    {
                        $mReturn[] = $tmp;
                    }
                }
            }
            else
            {
                if ($mRecordCount<$mMaxResults)
                {
                    $mRecordCount = $mRecordCount + 1;
                    $tmp = returnArray($rest_url, $mUserID, 0);
                    if ($tmp)
                    {
                        $mReturn[] = $tmp;
                    }
                }
            }
        }
    }
    else
    {
        if ($mRecordCount<$mMaxResults)
        {
            $mRecordCount = $mRecordCount + 1;
            $tmp = returnArray($rest_url, $mUserID, 0);
            if ($tmp)
            {
                $mReturn[] = $tmp;
            }
        }
    }    
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

function allBusinessHours($pRestaurantID)
{
    $mSQL = "SELECT *, '' AS hours FROM business_hours WHERE rest_id='".$pRestaurantID."' ORDER BY day ASC";
    $qry= dbAbstract::Execute($mSQL);
    while($day=dbAbstract::returnObject($qry))
    {
        if($day->day == 0)
        {
            $day->hours = 'Monday';
        }
        else if($day->day == 1)
        {
            $day->hours= 'Tuesday';
        }
        else if($day->day == 2)
        {
            $day->hours = 'Wednesday';
        }
        else if($day->day == 3)
        {
            $day->hours = 'Thursday';
        }
        else if($day->day == 4)
        {
            $day->hours = 'Friday';
        }
        else if($day->day == 5)
        {
            $day->hours = 'Saturday';
        }
        else if($day->day == 6)
        {
            $day->hours = 'Sunday';
        }
       
        $mOpen = "";
        $mClose = "";
        
        if ((strrpos($day->open,"-") !== FALSE) && (strrpos($day->close,"-") !== FALSE))
        {
            $mOpen = "";
            $mClose = "";
        }
        else
        {
            if (strrpos($day->open,"-") !== FALSE)
            {
                $mOpen = "0000";
            }
            else
            {
                $mOpen = date("g:i A",strtotime($day->open));
            }
            
            if (strrpos($day->close,"-") !== FALSE)
            {
                $mClose = "2359";
            }
            else
            {
                $mClose = date("g:i A",strtotime($day->close));
            }
        }
        
        if (($mOpen=="") && ($mClose==""))
        {
            $arr_days[$day->hours] = "Closed";
        }
        else
        {
            $arr_days[$day->hours] = array("open" => $mOpen, "close" => $mClose);
        }
    }
    return $arr_days;
 }

function getLatLong($pAddress)
{
    $pAddress = str_replace(' ', '+', $pAddress);
    $result = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$pAddress.'&sensor=false');
    $json = (json_decode($result, true));
    
    return array($json['results'][0]['geometry']['location']['lat'], $json['results'][0]['geometry']['location']['lng']);
}

function getDistance($pSourceLatLong, $pDestinationLatLong)
{
    $mTheta = $pSourceLatLong[1] - $pDestinationLatLong[1];
    $mDistance = (sin(deg2rad($pSourceLatLong[0])) * sin(deg2rad($pDestinationLatLong[0]))) + (cos(deg2rad($pSourceLatLong[0])) * cos(deg2rad($pDestinationLatLong[0])) * cos(deg2rad($mTheta)));
    $mDistance = acos($mDistance);
    $mDistance = rad2deg($mDistance);
    $mDistance = $mDistance * 60 * 1.1515;
    return round($mDistance, 2);
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
    $lat_lon = dbAbstract::ExecuteObject($qry);

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

function getMenus($pRestaurantID, $pMenu = "")
{
    $mSQLMenu = "";
    if ($pMenu!="")
    {
        $mSQLMenu = "SELECT * FROM menus WHERE rest_id=".$pRestaurantID." AND menu_name='".$pMenu."' AND status = 1 ORDER BY menu_ordering";
    }
    else
    {
        $mSQLMenu = "SELECT * FROM menus WHERE rest_id=".$pRestaurantID." AND status = 1 ORDER BY menu_ordering LIMIT 1";
    }
    $mResMenu = dbAbstract::Execute($mSQLMenu);
    while ($mRowMenu = dbAbstract::returnObject($mResMenu))
    {
        $mMenus[] = array(
                        $mRowMenu->menu_name => getCategories($mRowMenu->id)
                    );
    }
    return $mMenus;
}

function getCategories($pMenuID)
{
    $arr_categories=array();
    $mResCat = dbAbstract::Execute("SELECT * FROM categories WHERE menu_id=".$pMenuID." AND status=1 ORDER BY cat_ordering");
    while ($mRowCat = dbAbstract::returnObject($mResCat))
    {
        $arr_categories[] = array(
                        "category" => replaceSpecialChar($mRowCat->cat_name),
                        "category_subdescriptions" => replaceSpecialChar($mRowCat->cat_des),
                        "active" => "true",
                        "items" => getProducts($mRowCat->cat_id)
                    );
    }
    return $arr_categories;
}

function getProducts($pCategoryID)
{
    global $SiteUrl;
    $arr_products=array();
    $mResProduct = dbAbstract::Execute("SELECT * FROM product WHERE sub_cat_id = ".$pCategoryID." AND status = 1 ORDER BY SortOrder");
    while ($mRowProduct = dbAbstract::returnObject($mResProduct))
    {
        $arr_products[] = array(
                        "name" => replaceSpecialChar($mRowProduct->item_title),
                        "details" => replaceSpecialChar($mRowProduct->item_des),
                        "price" => $mRowProduct->retail_price,
                        "image_url" => (!empty($mRowProduct->item_image)?$SiteUrl."images/item_images/".$mRowProduct->item_image:""),
                        "posID" => $mRowProduct->pos_id,
                        "active" => "true",
                        "boarshead_item" => (strpos($mRowProduct->item_type, "B")!==FALSE?"true":"false")
                    );
    }
    return $arr_products;
}

function errorFunction($errorCode, $errorDescription, $errorMessage, $errorTitile)
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


function findLatLong($rest_url)
{   
    $mDestinationLatLong = array();
    $mSQLLatLong = "SELECT rest_latitude, rest_longitude FROM rest_langitude_latitude WHERE rest_id=".$rest_url->id;
    $mResLatLong  = dbAbstract::Execute($mSQLLatLong);
    if (dbAbstract::returnRowsCount($mResLatLong)>0)
    {
        $mRowLatLong = dbAbstract::returnObject($mResLatLong);
        $mDestinationLatLong = array($mRowLatLong->rest_latitude, $mRowLatLong->rest_longitude);
    }

    if (count($mDestinationLatLong)==0)
    {   
        $mRestaurantAddress = $rest_url->rest_address." ".$rest_url->rest_city.", ".$rest_url->rest_state." ".$rest_url->rest_zip;

        $mDestinationLatLong = getLatLong($mRestaurantAddress);
        dbAbstract::Insert("insert into rest_langitude_latitude set rest_id = ".$rest_id.", rest_latitude='".$mDestinationLatLong[0]."', rest_longitude='".$mDestinationLatLong[1]."'");
    }
    return $mDestinationLatLong;
}

function replaceSpecialChar($string)
{
    $string = str_replace('<BR/>', '\n', str_replace('<BR />', '\n', str_replace('<BR>', '\n', str_replace('\t', '',str_replace('<br />', '\n',str_replace('<br/>', '\n',str_replace('<br>', '\n',str_replace('\'', '&#39;', $string))))))));
    return $string;  
}

function checkOpen($pRestaurantID, $pTimeZoneID)
{
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

    $business_hours =  dbAbstract::ExecuteObject("SELECT open, close FROM business_hours WHERE day = ".$day_of_week." AND rest_id = ".$pRestaurantID);
    $timezoneRs = dbAbstract::ExecuteArray("SELECT time_zone FROM times_zones WHERE id = ".$pTimeZoneID);
    date_default_timezone_set($timezoneRs['time_zone']);
    $current_time=date("Hi",time());
    $mOpen = "n";
    if ($current_time >= $business_hours->open && $current_time <= $business_hours->close)
    {
        $mOpen = "y";
    }
    return $mOpen;
}

function checkFavorite($pUserID, $pRestaurantID)
{
    $mFavorite = 2;
    
    if ($pUserID != 0)
    {
        $mSQL = "SELECT IFNULL(favorite, 2) AS favorite FROM bh_rest_rating WHERE rest_id=".$pRestaurantID." AND user_id=".$pUserID;

        $mResult = dbAbstract::ExecuteObject($mSQL);
        if ($mResult)
        {
            $mFavorite = $mResult->favorite;
        }
    }

    if ($mFavorite == 0)
    {
        $mFavorite = "no";
    }
    else if ($mFavorite == 1)
    {
        $mFavorite = "yes";
    }
    else
    {
        $mFavorite = "no input";
    }
    return $mFavorite;
}

function returnLikeCount($pRestaurantID)
{
    $mLikeCount = 0;
    $mSQL = "SELECT IFNULL(COUNT(*), 0) AS LikeCount FROM bh_rest_rating WHERE rest_id=".$pRestaurantID." AND Rating = 1";
    $mResult = dbAbstract::ExecuteObject($mSQL);
    if ($mResult)
    {
        $mLikeCount = $mResult->LikeCount;
    }
    return $mLikeCount;
}

function returnDislikeCount($pRestaurantID)
{
    $mDislikeCount = 0;
    $mSQL = "SELECT IFNULL(COUNT(*), 0) AS DislikeCount FROM bh_rest_rating WHERE rest_id=".$pRestaurantID." AND Rating = 0";
    $mResult = dbAbstract::ExecuteObject($mSQL);
    if ($mResult)
    {
        $mDislikeCount = $mResult->DislikeCount;
    }
    return $mDislikeCount;
}

function checkSignatureSandwich($pRestaurantID)
{
    $mSignatureSandwich = "no";
    $mSQLSigSan = "SELECT COUNT(*) AS SignatureSandwiches FROM product WHERE cat_id=".$pRestaurantID." AND LOWER(item_title)='signature sandwich'";
    $mResSigSan = dbAbstract::ExecuteObject($mSQLSigSan);
    if ($mResSigSan)
    {
        if ($mResSigSan->SignatureSandwiches>0)
        {
            $mSignatureSandwich = "yes";
        }
    }
    return $mSignatureSandwich;
}

function returnArray($rest_url, $pUserID = 0, $pDeliveryZone = 0)
{
    $mOpen = checkOpen($rest_url->id, $rest_url->time_zone_id);
    if (isset($_GET["detail"]) || (isset($_GET["getrestaurantdetails"])))
    {
        global $SiteUrl;
        $mDeliveryCharges = $rest_url->delivery_charges;
        $mOrderMinimum = $rest_url->order_minimum;

        if ($pDeliveryZone==1)
        {
            $mDeliveryCharges = $rest_url->zone1_delivery_charges;
            $mOrderMinimum = $rest_url->zone1_min_total;
        }
        else if ($pDeliveryZone==2)
        {
            $mDeliveryCharges = $rest_url->zone2_delivery_charges;
            $mOrderMinimum = $rest_url->zone2_min_total;
        }
        else if ($pDeliveryZone==3)
        {
            $mDeliveryCharges = $rest_url->zone3_delivery_charges;
            $mOrderMinimum = $rest_url->zone3_min_total;
        }


        $getLatLong = findLatLong($rest_url);
        $mLikeCount = returnLikeCount($rest_url->id);
        $mDislikeCount = returnDislikeCount($rest_url->id);
        $mLikePercentage = 0;

        if (($mLikeCount==0) && ($mDislikeCount==0))
        {
            $mLikePercentage = 0;
        }
        else
        {
            $mLikePercentage = round(($mLikeCount/($mLikeCount + $mDislikeCount))*100);
        }

        $mLikeRating[] = array("like_count" => $mLikeCount, "dislike_count" => $mDislikeCount, "like_percentage" => $mLikePercentage);
        $mSignatureSandwich = checkSignatureSandwich($rest_url->id);
    
        if ($pUserID==0)
        {
            if (isset($_GET["open"]))
            {
                if ($mOpen=="y")
                {
                    return array(
                        "name" => replaceSpecialChar($rest_url->name),
                        "slug" => $rest_url->url_name,
                        "email" => $rest_url->email,
                        "address" => replaceSpecialChar($rest_url->rest_address),
                        "latitude" => $getLatLong[0],
                        "longitude" => $getLatLong[1],
                        "Phone" => $rest_url->phone,
                        "Fax" => $rest_url->fax,
                        "deliver_charges" => $mDeliveryCharges,
                        "min_total" => $mOrderMinimum,
                        "facebookURL" => $rest_url->facebookLink,
                        "url" => $SiteUrl.$rest_url->url_name."/",
                        "domain" => $rest_url->URL,
                        "images" => array("thumbUrl" => $SiteUrl."images/resturant_logos/".$rest_url->logo, headerURL => $SiteUrl."images/resturant_headers/".$rest_url->header_image),
                        "menu_url" => $SiteUrl.$rest_url->url_name."/",
                        "delivery" => ($rest_url->delivery_offer=="1"?"y":"n"),
                        "announcement" => $rest_url->announcement,
                        "open_now" => $mOpen,
                        "featured" => ($rest_url->bh_featured>0?"y":"n"),
                        "payment_options" => ($rest_url->payment_method=="both"?"cash, credit":$rest_url->payment_method),
                        "hours" => allBusinessHours($rest_url->id),
                        "signature_sandwich" => $mSignatureSandwich,
                        "like_rating" => $mLikeRating,
                        "satisfactionPercentage" => $mLikePercentage
                    );
                }
            }
            else
            {
                return array(
                        "name" => replaceSpecialChar($rest_url->name),
                        "slug" => $rest_url->url_name,
                        "email" => $rest_url->email,
                        "address" => replaceSpecialChar($rest_url->rest_address),
                        "latitude" => $getLatLong[0],
                        "longitude" => $getLatLong[1],
                        "Phone" => $rest_url->phone,
                        "Fax" => $rest_url->fax,
                        "deliver_charges" => $mDeliveryCharges,
                        "min_total" => $mOrderMinimum,
                        "facebookURL" => $rest_url->facebookLink,
                        "url" => $SiteUrl.$rest_url->url_name."/",
                        "domain" => $rest_url->URL,
                        "images" => array("thumbUrl" => $SiteUrl."images/resturant_logos/".$rest_url->logo, headerURL => $SiteUrl."images/resturant_headers/".$rest_url->header_image),
                        "menu_url" => $SiteUrl.$rest_url->url_name."/",
                        "delivery" => ($rest_url->delivery_offer=="1"?"y":"n"),
                        "announcement" => $rest_url->announcement,
                        "open_now" => $mOpen,
                        "featured" => ($rest_url->bh_featured>0?"y":"n"),
                        "payment_options" => ($rest_url->payment_method=="both"?"cash, credit":$rest_url->payment_method),
                        "hours" => allBusinessHours($rest_url->id),
                        "signature_sandwich" => $mSignatureSandwich,
                        "like_rating" => $mLikeRating,
                        "satisfactionPercentage" => $mLikePercentage
                    );
            }
        }
        else
        {
            $mFavorite = checkFavorite($pUserID, $rest_url->id);
            if (isset($_GET["open"]))
            {
                if ($mOpen=="y")
                {
                    return array(
                        "name" => replaceSpecialChar($rest_url->name),
                        "slug" => $rest_url->url_name,
                        "email" => $rest_url->email,
                        "address" => replaceSpecialChar($rest_url->rest_address),
                        "latitude" => $getLatLong[0],
                        "longitude" => $getLatLong[1],
                        "Phone" => $rest_url->phone,
                        "Fax" => $rest_url->fax,
                        "deliver_charges" => $mDeliveryCharges,
                        "min_total" => $mOrderMinimum,
                        "facebookURL" => $rest_url->facebookLink,
                        "url" => $SiteUrl.$rest_url->url_name."/",
                        "domain" => $rest_url->URL,
                        "images" => array("thumbUrl" => $SiteUrl."images/resturant_logos/".$rest_url->logo, headerURL => $SiteUrl."images/resturant_headers/".$rest_url->header_image),
                        "menu_url" => $SiteUrl.$rest_url->url_name."/",
                        "delivery" => ($rest_url->delivery_offer=="1"?"y":"n"),
                        "announcement" => $rest_url->announcement,
                        "open_now" => $mOpen,
                        "featured" => ($rest_url->bh_featured>0?"y":"n"),
                        "payment_options" => ($rest_url->payment_method=="both"?"cash, credit":$rest_url->payment_method),
                        "hours" => allBusinessHours($rest_url->id),
                        "signature_sandwich" => $mSignatureSandwich,
                        "like_rating" => $mLikeRating,
                        "satisfactionPercentage" => $mLikePercentage,
                        "favorite" => $mFavorite
                    );
                }
            }
            else
            {
                return array(
                        "name" => replaceSpecialChar($rest_url->name),
                        "slug" => $rest_url->url_name,
                        "email" => $rest_url->email,
                        "address" => replaceSpecialChar($rest_url->rest_address),
                        "latitude" => $getLatLong[0],
                        "longitude" => $getLatLong[1],
                        "Phone" => $rest_url->phone,
                        "Fax" => $rest_url->fax,
                        "deliver_charges" => $mDeliveryCharges,
                        "min_total" => $mOrderMinimum,
                        "facebookURL" => $rest_url->facebookLink,
                        "url" => $SiteUrl.$rest_url->url_name."/",
                        "domain" => $rest_url->URL,
                        "images" => array("thumbUrl" => $SiteUrl."images/resturant_logos/".$rest_url->logo, headerURL => $SiteUrl."images/resturant_headers/".$rest_url->header_image),
                        "menu_url" => $SiteUrl.$rest_url->url_name."/",
                        "delivery" => ($rest_url->delivery_offer=="1"?"y":"n"),
                        "announcement" => $rest_url->announcement,
                        "open_now" => $mOpen,
                        "featured" => ($rest_url->bh_featured>0?"y":"n"),
                        "payment_options" => ($rest_url->payment_method=="both"?"cash, credit":$rest_url->payment_method),
                        "hours" => allBusinessHours($rest_url->id),
                        "signature_sandwich" => $mSignatureSandwich,
                        "like_rating" => $mLikeRating,
                        "satisfactionPercentage" => $mLikePercentage,
                        "favorite" => $mFavorite
                    );
            }
        }
    }
    else
    {
        if (isset($_GET["open"]))
        {
            if ($mOpen=="y")
            {
                return array(
                    "slug" => $rest_url->url_name
                );
            }
        }
        else
        {
            return array(
                "slug" => $rest_url->url_name
            );
        }
    }
}

function returnLocationArray($rest_url, $mLikePercentage = 0)
{
    $getLatLong = findLatLong($rest_url);
    
    if ($mLikePercentage == 0)
    {
        return array(
            "id" => replaceSpecialChar($rest_url->id),
            "name" => replaceSpecialChar($rest_url->name),
            "slug" => $rest_url->url_name,
            "address" => replaceSpecialChar($rest_url->rest_address),
            "city" => $rest_url->rest_city,
            "state" => $rest_url->rest_state,
            "zip" => $rest_url->rest_zip,
            "latitude" => $getLatLong[0],
            "longitude" => $getLatLong[1],
        );  
    }
    else
    {
        $mLikeCount = returnLikeCount($rest_url->id);
        $mDislikeCount = returnDislikeCount($rest_url->id);
        $mLikePercentage = 0;

        if (($mLikeCount==0) && ($mDislikeCount==0))
        {
            $mLikePercentage = 0;
        }
        else
        {
            $mLikePercentage = round(($mLikeCount/($mLikeCount + $mDislikeCount))*100);
        }
        
        return array(
            "id" => replaceSpecialChar($rest_url->id),
            "name" => replaceSpecialChar($rest_url->name),
            "slug" => $rest_url->url_name,
            "address" => replaceSpecialChar($rest_url->rest_address),
            "city" => $rest_url->rest_city,
            "state" => $rest_url->rest_state,
            "zip" => $rest_url->rest_zip,
            "latitude" => $getLatLong[0],
            "longitude" => $getLatLong[1],
            "satisfactionPercentage" => $mLikePercentage
        );
    }
}
?>