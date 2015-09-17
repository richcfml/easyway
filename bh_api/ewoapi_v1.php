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

$mAPICallNumber = mt_rand(10,1000000);

//$mRecordCount = 0;
//$mMaxResults = 25;
$mReturn = array();
$_GET = array_change_key_case($_GET, CASE_LOWER);
$mVerifyRequest = verifyRequest();
if ($mVerifyRequest==1) //Valid Session
{
    /*if (isset($_GET["max_results"]))
    {
        if (is_numeric($_GET["max_results"]))
        {
            $mMaxResults = $_GET["max_results"];
        }
    }*/
    
    $mUserID = 0;
    $mInvalidUser = 0;
    if (isset($_GET["sso"]))
    {
        $mUserID = $_GET["sso"];
        $mSQL = "SELECT sso_user_id,session_expiry FROM bh_sso_session WHERE session_id='".$mUserID."'";
        $mResult = dbAbstract::ExecuteObject($mSQL);
        
        if ($mResult)
        {
            if(time() < $mResult->session_expiry)
            {
                $expiryDate = date("c",  strtotime("+1 day"));  
                $mUserID = $mResult->sso_user_id;
                dbAbstract::Update("update bh_sso_session set session_expiry = ".strtotime($expiryDate)." where session_id = '".$_GET["sso"]."'");
            }
            else
            {
                $mInvalidUser = 2;           
            }
        }
        else
        {
            $mInvalidUser = 1;
        }
    }
    
    if ($mInvalidUser==1)
    {
        $mReturn = errorFunction("1","Invalid session id.","Invalid session id.","Attribute Error");
    }
    else if ($mInvalidUser==2)
    {
        $mReturn = errorFunction("2","Your session id has expired.","Session id has expired","Attribute Error");
    }
    else
    {
        if (isset($_GET["getrestaurants"]) && isset($_GET["getrestaurantdetails"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
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
                $mReturn = errorFunction("3", "Slugs not specified.", "Slugs not specified.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: getrestaurants, getrestaurantdetails", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["getrestaurants"]) && isset($_GET["getfullhours"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            if (isset($_GET["slugs"])) //Comma Separate Slugs
            {
                $mSlugs = explode(",", $_GET["slugs"]);
                for ($loopCount=0; $loopCount<count($mSlugs); $loopCount++)
                {
                    $mFavorite = 2;

                    $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($mSlugs[$loopCount]))."' AND bh_restaurant = 1";
                    $rest_url = dbAbstract::ExecuteObject($mSQL);
                    if ($rest_url)
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
                        
                        $mFavorite = checkFavorite($mUserID, $rest_url->id);
                        $getLatLong = findLatLong($rest_url);
                        
                        if ($mUserID==0)
                        {
                            $mReturn[] = array(
                                "name" => replaceSpecialChar($rest_url->name),
                                "hours" => allBusinessHours($rest_url->id),
                                "satisfactionPercentage" => $mLikePercentage,
                                "likes" => $mLikeCount,
                                "latitude" => $getLatLong[0],
                                "longitude" => $getLatLong[1]
                            );
                        }
                        else
                        {
                            $mReturn[] = array(
                                "name" => replaceSpecialChar($rest_url->name),
                                "hours" => allBusinessHours($rest_url->id),
                                "satisfactionPercentage" => $mLikePercentage,
                                "likes" => $mLikeCount,
                                "favorite" => $mFavorite,
                                "latitude" => $getLatLong[0],
                                "longitude" => $getLatLong[1]
                            );
                        }
                    }
                }
            }
            else
            {
                $mReturn = errorFunction("3", "Slugs not specified.", "Slugs not specified.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: getrestaurants, getfullhours", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["getrestaurants"]) && isset($_GET["featured"]) && !isset($_GET["locations"]))
        {   
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            $mSQL = "SELECT * FROM resturants WHERE status = 1 AND bh_restaurant = 1 AND bh_featured = 1";
            $mResFeatured = dbAbstract::Execute($mSQL);

            while ($rest_url = dbAbstract::returnObject($mResFeatured))
            {   
                //if ($mRecordCount<$mMaxResults)
                //{
                    //$mRecordCount = $mRecordCount + 1;
                    
                    $tmp = returnArray($rest_url, $mUserID, 0);
                    if ($tmp)
                    {
                        $mReturn[] = $tmp;
                    }
                //}
            }

            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: getrestaurants, featured", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["getrestaurants"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
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

            $mRes = dbAbstract::Execute("SELECT * FROM resturants WHERE rest_open_close = 1 AND status = 1 AND bh_restaurant = 1");
            while ($rest_url = dbAbstract::returnObject($mRes))
            {
                doProcessing($rest_url, $mLat, $mLong, $mUserID);
            }
            
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: getrestaurants", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["like"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
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
                $mReturn = errorFunction("3", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: like", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["unlike"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            if (isset($_GET["slug"]))
            {
                    $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
                    $rest_url = dbAbstract::ExecuteObject($mSQL);
                    if ($rest_url)
                    {
                        $mMaxID = 0;
                        $mRowMaxID = dbAbstract::ExecuteObject("SELECT MAX(id) AS id FROM bh_rest_rating WHERE Rating = 1 AND rest_id=".$rest_url->id);
                        if ($mRowMaxID)
                        {
                            $mMaxID = $mRowMaxID->id;
                            if (isset($mMaxID) && ($mMaxID>0))
                            {
                                dbAbstract::UPDATE("UPDATE bh_rest_rating SET Rating = 2 WHERE id=".$mMaxID);
                                $mUnLikeMessage = "Restaurant un-liked successfully.";
                            }
                            else
                            {
                                $mUnLikeMessage = "No likes for this restaurant.";    
                            }
                        }
                        else
                        {
                            $mUnLikeMessage = "No likes for this restaurant.";
                        }
                        
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
                                        "successDescription" => $mUnLikeMessage,
                                        "satisfactionPercentage" => $mLikePercentage
                                        );
                    }
            }
            else
            {
                $mReturn = errorFunction("3", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: like", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["dislike"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            if (isset($_GET["slug"]))
            {
                if (isset($_GET["options"]) && count($_GET["options"])>0)
                {
                    $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
                    $rest_url = dbAbstract::ExecuteObject($mSQL);
                    if ($rest_url)
                    {
                        $mBRRID = dbAbstract::Insert("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", 0, 2, 0)",0,2);
                        
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
                    $mReturn = errorFunction("4", "Please specify at least one Option.", "Please specify at least one Option.", "Attribute Error");
                }
            }
            else
            {
                $mReturn = errorFunction("3", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: dislike", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["undislike"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            if (isset($_GET["slug"]))
            {
                $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
                $rest_url = dbAbstract::ExecuteObject($mSQL);
                if ($rest_url)
                {
                    $mMaxID = 0;
                    $mRowMaxID = dbAbstract::ExecuteObject("SELECT MAX(id) AS id FROM bh_rest_rating WHERE Rating = 0 AND rest_id=".$rest_url->id);
                    if ($mRowMaxID)
                    {
                        $mMaxID = $mRowMaxID->id;
                        if (isset($mMaxID) && ($mMaxID>0))
                        {
                            dbAbstract::UPDATE("UPDATE bh_rest_rating SET Rating = 2 WHERE id=".$mMaxID);
                            $mUnLikeMessage = "Restaurant un-disliked successfully.";
                        }
                        else
                        {
                            $mUnLikeMessage = "No dislikes for this restaurant.";
                        }
                    }
                    else
                    {
                        $mUnLikeMessage = "No dislikes for this restaurant.";
                    }

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
                                    "successDescription" => $mUnLikeMessage,
                                    "satisfactionPercentage" => $mLikePercentage
                                    );
                }
            }
            else
            {
                $mReturn = errorFunction("3", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: dislike", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["favorites"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            if (isset($_GET["sso"]))
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
                $mReturn = errorFunction("6", "session id not specified.", "Invalid call.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: favorites", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["addfavorite"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            $res_url = $_GET['slug'];

            if (isset($_GET["slug"]))
            {   
                if (isset($_GET["sso"]))
                {
                    $res = "SELECT id FROM resturants WHERE url_name = '".$res_url."'";
                    $count_rest  = dbAbstract::Execute($res);
                    $resResult = dbAbstract::ExecuteObject($res);
                    
                    
                    if(dbAbstract::returnRowsCount($count_rest)==0 )
                    {
                         $mReturn = errorFunction("7", "No restaurant exists with this slug name.", "No restaurant exists with this slug name.","Data Error");
                    }
                    else
                    {   
                        $mSQL = "SELECT * FROM bh_rest_rating WHERE rest_id =".$resResult->id." AND user_id =".$mUserID;
                        $user_qry  = dbAbstract::Execute($mSQL);
                        $favoriteRes = dbAbstract::ExecuteObject($mSQL);

                        if(dbAbstract::returnRowsCount($user_qry)>0 )
                        {
                            if($favoriteRes->favorite == 1)
                            {
                                $mReturn[] = errorFunction("8", "You have already marked this restaurant as your favorite!","You have already marked this restaurant as your favorite!", "Data Error");
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
                    $mReturn = errorFunction("6", "session id not specified.", "Invalid call.", "Attribute Error");
                }
            }
            else
            {
                $mReturn = errorFunction("3", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: Add favorite", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["removefavorite"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            $res_url = $_GET['slug'];

            if (isset($_GET["slug"]))
            {
                if (isset($_GET["sso"]))
                {
                    $res = "SELECT id FROM resturants WHERE url_name = '".$res_url."'";
                    $count_rest  = dbAbstract::Execute($res);
                    $resResult = dbAbstract::ExecuteObject($res);

                    if(dbAbstract::returnRowsCount($count_rest)==0 )
                    {
                         $mReturn = errorFunction("5","No restaurant exists with this slug name.","No restaurant exists with this slug name.","Data Error");
                    }
                    else
                    {
                        $mSQL = "SELECT * FROM bh_rest_rating WHERE rest_id= $resResult->id AND user_id =".$mUserID." AND favorite = 1";
                        $user_qry  = dbAbstract::Execute($mSQL);

                        if(dbAbstract::returnRowsCount($user_qry)==0 )
                        {
                            $mReturn = errorFunction("9","This restaurant is not in your favorite list!","This restaurant is not in your favorite list!","Data Error");
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
                    $mReturn = errorFunction("6", "session id not specified.", "Invalid call.", "Attribute Error");
                }
            }
            else
            {
                $mReturn = errorFunction("3", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: Remove favorite", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["menus"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
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
                $mReturn = errorFunction("3", "Slug not specified.", "Slug not specified.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: menus", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["landingbanner"]))
        {
            $mRandomNumber = mt_rand(10,1000000);    
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            $mSQL = "SELECT id, bh_banner_image FROM resturants WHERE status = 1 AND bh_restaurant = 1";
            $rest_images = dbAbstract::Execute($mSQL);
            if ($rest_images)
            {
                while($images = dbAbstract::returnObject($rest_images))
                {   
                    if (trim($images->bh_banner_image)!="")
                    {
                        if (file_exists(realpath("../images/resturant_bh_banner/".$rest_url->bh_banner_image)))
                        {
                            $mArray[$images->id]['image'] = $SiteUrl."images/resturant_bh_banner/".$images->bh_banner_image."?".$mRandomNumber;
                        }
                    }
                }
                $mReturn = $mArray;
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: Landing Banner", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["favorite"]) && isset($_GET["locations"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            if (isset($_GET["sso"]))
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
                $mReturn = errorFunction("6", "session id not specified.", "Invalid call.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: favorite, locations", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["highestrated"]) && isset($_GET["locations"]))
        {  
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
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
                    $tmp = returnLocationArray($rest_url,0,$mUserID);
                    if ($tmp)
                    {
                        $mArray[] = $tmp;
                    }
                }
            }
            $mReturn = $mArray;
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: highest rated, locations", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["featured"]) && isset($_GET["locations"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
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
                    $tmp = returnLocationArray($rest_url,0,$mUserID);
                    if ($tmp)
                    {
                        $mArray[] = $tmp;
                    }
                }
            }
            $mReturn = $mArray;
            
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: featured, locations", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["fetchorderdetail"]))
        {
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            if(isset($_GET['orderid']))
            {
                $orderid = $_GET['orderid'];
                $tmp = getorderdetails($orderid);
                if ($tmp)
                {
                    $mArray[] = $tmp;
                }
                $mReturn = $mArray;
            }
            else
            {
                $mReturn = errorFunction("14", "orderid not specified.", "Invalid call.", "Attribute Error");
            }
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: fetchOrderDetail", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else if (isset($_GET["highestrated"]))
        { 
            $mStartTime = strtotime(date("Y-m-d H:i:s"));
            $rest_loc = dbAbstract::Execute("SELECT R.*, COUNT(Rating) AS RatingCount FROM resturants R INNER JOIN bh_rest_rating BRR ON BRR.rest_id = R.id WHERE BRR.Rating = 1 AND R.status = 1 AND R.bh_restaurant = 1 GROUP BY R.id ORDER BY RatingCount DESC");
            while($rest_url = dbAbstract::returnObject($rest_loc))
            {
                $tmp = returnArray($rest_url, $mUserID, 0);
                if ($tmp)
                {
                    $mArray[] = $tmp;
                }
            }
            $mReturn = $mArray;
            $mEndTime = strtotime(date("Y-m-d H:i:s"));
            $mExecutionTime = $mEndTime - $mStartTime;
            Log::write("BH API: highestrated", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
        }
        else
        {   
            $mReturn = errorFunction("11", "Operation not specified or invalid operation.", "Invalid call.", "Operation Error");
        }
    }
}
else if ($mVerifyRequest==0) //This will never happen
{
    $mReturn = errorFunction("15", "Apikey not specified.", "Invalid call.", "Attribute Error");
}
else if ($mVerifyRequest==2) //Session ID not present
{
    $mReturn = errorFunction("15", "Apikey not specified.", "Invalid call.","Attribute Error");
}
else if ($mVerifyRequest==3) //Session ID expired
{
    $mReturn = errorFunction("16", "Api key is not valid", "Api key is not valid", "Data Error");
}

if (count($mReturn)==0)
{
    $mReturn = errorFunction("12","No restaurants found according to provided search criteria.","Empty result set.","Data Error");
}

if (!(isset($mReturn["errorCode"])))
{
    if (isset($_GET["latlong"]))
    {
        $mSourceLatLongSort = array();
        $mDestinationLatLongSort = array();

        $mTmp = explode(",", $_GET["latlong"]);

        $mSourceLatLongSort = array(trim($mTmp[0]), trim($mTmp[1]));
        for ($loopCount=0; $loopCount<count($mReturn); $loopCount++)
        {
            if (!isset($mReturn[$loopCount]["distance"]))
            {
                $mDestinationLatLongSort = array(trim($mReturn[$loopCount]["latitude"]), trim($mReturn[$loopCount]["longitude"]));
                $mDistanceSort = getDistance($mSourceLatLongSort, $mDestinationLatLongSort);
                $mReturn[$loopCount]["distance"] = $mDistanceSort;
            }
        }

        $sort = array();
        foreach($mReturn as $k=>$v) 
        {
            $sort['distance'][$k] = $v['distance'];
        }
        array_multisort($sort['distance'], SORT_ASC, $mReturn);
    }
    else
    {
        $sort = array();
        foreach($mReturn as $k=>$v) 
        {
            $sort['satisfactionPercentage'][$k] = $v['satisfactionPercentage'];
            $sort['likes'][$k] = $v['likes'];
        }
        array_multisort($sort['satisfactionPercentage'], SORT_DESC, $sort['likes'], SORT_DESC, $mReturn);
    }

    $mMax = 25;
    if (isset($_GET["max_results"]))
    {
        if (is_numeric($_GET["max_results"]))
        {
            $mMax = $_GET["max_results"];
        }
    }
    
    if (count($mReturn)>$mMax)
    {
        $mReturn = array_slice($mReturn, 0, $mMax);   
    }
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
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    global $mReturn, $SiteUrl, $mRecordCount, $mMaxResults, $mAPICallNumber;
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
                            //if ($mRecordCount<$mMaxResults)
                            //{
                                //$mRecordCount = $mRecordCount + 1;
                                $tmp = returnArray($rest_url, $mUserID, 1);
                                if ($tmp)
                                {
                                    $mReturn[] = $tmp;
                                }
                            //}
                        }
                        else if ($rest_url->zone2 && pointInPolygon($x, $y, $zone2_coordinates))
                        {
                            //if ($mRecordCount<$mMaxResults)
                            //{
                                //$mRecordCount = $mRecordCount + 1;
                                $tmp = returnArray($rest_url, $mUserID, 2);
                                if ($tmp)
                                {
                                    $mReturn[] = $tmp;
                                }
                            //}
                        }
                        else if ($rest_url->zone3 && pointInPolygon($x, $y, $zone3_coordinates))
                        {
                            //if ($mRecordCount<$mMaxResults)
                            //{
                                //$mRecordCount = $mRecordCount + 1;
                                $tmp = returnArray($rest_url, $mUserID, 3);
                                if ($tmp)
                                {
                                    $mReturn[] = $tmp;
                                }
                            //}
                        }
                    }
                }
                else
                {
                    $lon2 = "";
                    $lat2 = "";
                    $qry = "SELECT * FROM rest_langitude_latitude WHERE rest_id = ".$rest_url->id;
                    $lat_lon = dbAbstract::ExecuteArray($qry);
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
                            //if ($mRecordCount<$mMaxResults)
                            //{
                                //$mRecordCount = $mRecordCount + 1;
                                $tmp = returnArray($rest_url, $mUserID, 0);
                                if ($tmp)
                                {
                                    $mReturn[] = $tmp;
                                }
                            //}
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
                //if ($mRecordCount<$mMaxResults)
                //{
                    //$mRecordCount = $mRecordCount + 1;
                    $tmp = returnLocationArray($rest_url,0,$mUserID);
                    if ($tmp)
                    {
                        $tmp["distance"] = $mDistance;
                        $mReturn[] = $tmp;
                    }
                //}
            }
            else
            { 
                //if ($mRecordCount<$mMaxResults)
                //{
                    //$mRecordCount = $mRecordCount + 1;
                    $tmp = returnArray($rest_url, $mUserID, 0);
                    if ($tmp)
                    {
                        $tmp["distance"] = $mDistance;
                        $mReturn[] = $tmp;
                    }
                //}
            }
        }
    }
    else
    {
        //if ($mRecordCount<$mMaxResults)
        //{
            //$mRecordCount = $mRecordCount + 1;
            $tmp = returnArray($rest_url, $mUserID, 0);
            if ($tmp)
            {
                $mReturn[] = $tmp;
            }
        //}
    }    
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: doProcessing()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
}

function verifyRequest()
{
    global $mAPICallNumber;
    $mApiKey = "";
    foreach (getallheaders() as $name => $value) 
    {
        if (trim(strtolower($name))=="apikey")
        {
            $mApiKey = trim($value);
        }
    }
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    
    /*if ($mApiKey == "")
    {
        if (isset($_GET["apikey"]))
        {
            $mApiKey = $_GET["apikey"];
        }
    }*/
    
    if ($mApiKey!="")
    {
        $Qry = dbAbstract::Execute("Select * from users where ewo_api_key = '".$mApiKey."'");
        $qryCount = dbAbstract::returnRowsCount($Qry);
        if ($qryCount <= 0)
        {
            $mReturnVal = 3; //sso (session id) is different than current session id (Session expired)
        }
        else
        {
            $mReturnVal = 1; //sso (session id) is same as current (Valid Session)
        }
    }
    else
    {
        $mReturnVal = 2; //sso (session id) not present
    }
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: verifyRequest()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $mReturnVal;
}

function allBusinessHours($pRestaurantID)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
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
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: allBusinessHours()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $arr_days;
 }

function getLatLong($pAddress)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    $pAddress = str_replace(' ', '+', $pAddress);
    $result = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$pAddress.'&sensor=false');
    $json = (json_decode($result, true));
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: getLatLong()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return array($json['results'][0]['geometry']['location']['lat'], $json['results'][0]['geometry']['location']['lng']);
}

function getDistance($pSourceLatLong, $pDestinationLatLong)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    $mTheta = $pSourceLatLong[1] - $pDestinationLatLong[1];
    $mDistance = (sin(deg2rad($pSourceLatLong[0])) * sin(deg2rad($pDestinationLatLong[0]))) + (cos(deg2rad($pSourceLatLong[0])) * cos(deg2rad($pDestinationLatLong[0])) * cos(deg2rad($mTheta)));
    $mDistance = acos($mDistance);
    $mDistance = rad2deg($mDistance);
    $mDistance = $mDistance * 60 * 1.1515;
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: getDistance()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return round($mDistance, 2);
}

function pointInPolygon($x, $y, $coordinates)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
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
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: pointInPolygon()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $oddNodes;
}

function getCoordinates($radius, $rest_id)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
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
    }
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: getCoordinates()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $coordinates;
}

function getMenus($pRestaurantID, $pMenu = "")
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
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
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: getMenus()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $mMenus;
}

function getCategories($pMenuID)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
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
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: getCategories()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $arr_categories;
}

function getProducts($pCategoryID)
{
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    global $SiteUrl, $mAPICallNumber;
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
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: getProducts()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $arr_products;
}

function errorFunction($errorCode, $errorDescription, $errorMessage, $errorTitile)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    $result = array(
            "errorCode" => $errorCode,
            "errorDescription" => $errorDescription,
            "errorMessage" => $errorMessage,
            "errorTitle" => $errorTitile
        );
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: errorFunction()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $result;
}
/* General (Helping) Functions Ends Here */

function findLatLong($rest_url)
{ 
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
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
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: findLatLong()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $mDestinationLatLong;
}

function replaceSpecialChar($string)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    $string = str_replace('<BR/>', '\n', str_replace('<BR />', '\n', str_replace('<BR>', '\n', str_replace('\t', '',str_replace('<br />', '\n',str_replace('<br/>', '\n',str_replace('<br>', '\n',str_replace('\'', '&#39;', $string))))))));
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: replaceSpecialChar()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $string;  
}

function checkOpen($pRestaurantID, $pTimeZoneID)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
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
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: checkOpen()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $mOpen;
}

function checkFavorite($pUserID, $pRestaurantID)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
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
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: checkFavorite()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $mFavorite;
}

function returnLikeCount($pRestaurantID)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    $mLikeCount = 0;
    $mSQL = "SELECT IFNULL(COUNT(*), 0) AS LikeCount FROM bh_rest_rating WHERE rest_id=".$pRestaurantID." AND Rating = 1";
    $mResult = dbAbstract::ExecuteObject($mSQL);
    if ($mResult)
    {
        $mLikeCount = $mResult->LikeCount;
    }
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: returnLikeCount()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $mLikeCount;
}

function returnDislikeCount($pRestaurantID)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    $mDislikeCount = 0;
    $mSQL = "SELECT IFNULL(COUNT(*), 0) AS DislikeCount FROM bh_rest_rating WHERE rest_id=".$pRestaurantID." AND Rating = 0";
    $mResult = dbAbstract::ExecuteObject($mSQL);
    if ($mResult)
    {
        $mDislikeCount = $mResult->DislikeCount;
    }
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: returnDislikeCount()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $mDislikeCount;
}

function checkSignatureSandwich($rest_id)
{ 
    $qry = "Select signature_sandwitch_id from product p inner join bh_signature_sandwitch bh on bh.id = p.signature_sandwitch_id where p.signature_sandwitch_id !='' and p.status = 1 and bh.start_date <= '".date('Y-m-d')."' and bh.end_date >= '".date('Y-m-d')."' and p.cat_id = ".$rest_id."";
    
    $query = dbAbstract::Execute($qry);
    $rowsCount = dbAbstract::returnRowsCount($query);
    if($rowsCount > 0)
    {
        return "yes";
    }
    else
    {
        return "No";
    }
    
}
function returnArray($rest_url, $pUserID = 0, $pDeliveryZone = 0)
{
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    global $SiteUrl, $mAPICallNumber;
    $mThumbnailURL = "";
    $mBannerURL = "";
    $mRandomNumber = mt_rand(10,1000000);    
    if (trim($rest_url->optionl_logo)!="")
    {
        //if (file_exists(realpath("../images/logos_thumbnail/".$rest_url->optionl_logo)))
        //{
            $mThumbnailURL = $SiteUrl."images/logos_thumbnail/".$rest_url->optionl_logo."?".$mRandomNumber;
        //}
    }
    
    if (trim($rest_url->bh_banner_image)!="")
    {
        //if (file_exists(realpath("../images/resturant_bh_banner/".$rest_url->bh_banner_image)))
        //{
            $mBannerURL = $SiteUrl."images/resturant_bh_banner/".$rest_url->bh_banner_image."?".$mRandomNumber;
        //}
    }
    $mSignatureSandwich = checkSignatureSandwich($rest_url->id);
    $mOpen = checkOpen($rest_url->id, $rest_url->time_zone_id);
    
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
    
    $getLatLong = findLatLong($rest_url);
    
    if (isset($_GET["detail"]) || (isset($_GET["getrestaurantdetails"])))
    {
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

        $mLikeRating[] = array("like_count" => $mLikeCount, "dislike_count" => $mDislikeCount, "like_percentage" => $mLikePercentage);
        
    
        if ($pUserID==0)
        {
            if (isset($_GET["open"]))
            {
                if ($mOpen=="y")
                {
                    $mRetArray = array(
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
                        "images" => array("thumbUrl" => $mThumbnailURL, bannerURL => $mBannerURL),
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
                        "likes" => $mLikeCount
                    );
                }
            }
            else
            {
                $mRetArray = array(
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
                        "images" => array("thumbUrl" => $mThumbnailURL, bannerURL => $mBannerURL),
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
                        "likes" => $mLikeCount
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
                    $mRetArray = array(
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
                        "images" => array("thumbUrl" => $mThumbnailURL, bannerURL => $mBannerURL),
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
                        "favorite" => $mFavorite,
                        "likes" => $mLikeCount
                    );
                }
            }
            else
            {
                $mRetArray = array(
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
                        "images" => array("thumbUrl" => $mThumbnailURL, bannerURL => $mBannerURL),
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
                        "favorite" => $mFavorite,
                        "likes" => $mLikeCount
                    );
            }
        }
    }
    else
    {
        if ($pUserID==0)
        {
            if (isset($_GET["open"]))
            {
                if ($mOpen=="y")
                {
                    $mRetArray = array(
                        "slug" => $rest_url->url_name,
                        "satisfactionPercentage" => $mLikePercentage,
                        "likes" => $mLikeCount,
                        "latitude" => $getLatLong[0],
                        "longitude" => $getLatLong[1],
                    );
                }
            }
            else
            {
                $mRetArray = array(
                    "slug" => $rest_url->url_name,
                    "satisfactionPercentage" => $mLikePercentage,
                    "likes" => $mLikeCount,
                    "latitude" => $getLatLong[0],
                    "longitude" => $getLatLong[1],
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
                    $mRetArray = array(
                        "slug" => $rest_url->url_name,
                        "satisfactionPercentage" => $mLikePercentage,
                        "likes" => $mLikeCount,
                        "latitude" => $getLatLong[0],
                        "longitude" => $getLatLong[1],
                        "favorite" => $mFavorite
                    );
                }
            }
            else
            {
                $mRetArray = array(
                    "slug" => $rest_url->url_name,
                    "satisfactionPercentage" => $mLikePercentage,
                    "likes" => $mLikeCount,
                    "latitude" => $getLatLong[0],
                    "longitude" => $getLatLong[1],
                    "favorite" => $mFavorite
                );
            }
        }
    }
    if(isset($_GET['signature']) && strtolower($_GET['signature']) == "1")
    {
        
        if($mSignatureSandwich == "yes")
        {
             return $mRetArray;
        }
    }
    else
    {
        return $mRetArray;
    }
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: returmArray()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    
}

function getorderdetails($OrderID)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    $prdQuery="select o.*,o.DeliveryAddress as delivery,DATE_FORMAT(OrderDate,'%m/%d/%Y'),c.cust_your_name, c.LastName,c.cust_phone1,cust_ord_city,cust_ord_state,cust_room,c.cust_odr_address DeliveryAddress from customer_registration c,ordertbl o where o.UserID=c.id  and
    o.OrderID = ". $OrderID ." ORDER BY o.OrderID DESC";
    
    $prdQuery= dbAbstract::Execute($prdQuery);
    if(dbAbstract::returnRowsCount($prdQuery) > 0)
    {
        $Ord_RS=dbAbstract::returnArray($prdQuery,MYSQL_BOTH);
        $mApiKey = "";
        foreach (getallheaders() as $name => $value) 
        {
            if (trim(strtolower($name))=="apikey")
            {
                $mApiKey = trim($value);
            }
        }
        
        if ($mApiKey == "")
        {
            if (isset($_GET["apikey"]))
            {
                $mApiKey = $_GET["apikey"];
            }
        }
        
        $userQry = dbAbstract::ExecuteArray("Select id,type from users where ewo_api_key ='".$mApiKey."'");
        if($userQry['type']=='bh')
        {
            $mSQLBH = dbAbstract::ExecuteObject("SELECT bh_restaurant FROM resturants WHERE id=".$Ord_RS['cat_id']);
            if ($mSQLBH)
            {
                if ($mSQLBH->bh_restaurant<=0)
                {   
                    $orderArray = errorFunction("16", "You do not have permission to get this order details", "Access Denied.", "Permission Error");
                    $mEndTime = strtotime(date("Y-m-d H:i:s"));
                    $mExecutionTime = $mEndTime - $mStartTime;
                    Log::write("BH API: returmArray()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
                    return $orderArray;
                }
            }
        }
        if($userQry['type']=='reseller')
        {
            $mSQLBH = dbAbstract::ExecuteObject("SELECT count(r.id) as count from resturants r inner join reseller_client c on c.client_id = r.owner_id where c.reseller_id = ".$userQry['id']." and r.id = ".$Ord_RS['cat_id']."");
            if ($mSQLBH->count <= 0)
            {
                $orderArray = errorFunction("16", "You do not have permission to get this order details", "Access Denied.", "Permission Error");
                $mEndTime = strtotime(date("Y-m-d H:i:s"));
                $mExecutionTime = $mEndTime - $mStartTime;
                Log::write("BH API: returmArray()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
                return $orderArray;
            }
        }
        if($userQry['type']=='store owner')
        {
            $mSQLBH = dbAbstract::ExecuteObject("SELECT count(id) as count from resturants where owner_id = ".$userQry['id']." and id = ".$Ord_RS['cat_id']."");
            if ($mSQLBH->count <= 0)
            {
                $orderArray = errorFunction("16", "You do not have permission to get this order details", "Access Denied.", "Permission Error");
                $mEndTime = strtotime(date("Y-m-d H:i:s"));
                $mExecutionTime = $mEndTime - $mStartTime;
                Log::write("BH API: returmArray()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');
                return $orderArray;
            }
        }
        $prdQuery2 ="select * from orderdetails where orderid = $OrderID";
        $GrandTotal=0;
        $prdQuery2= dbAbstract::Execute($prdQuery2);

        $i=0;
    
        while($Ord_RS2=dbAbstract::returnArray($prdQuery2,MYSQL_BOTH))
        {
            $ProductID = $Ord_RS2["pid"];
            $mOrderDetailsID = $Ord_RS2["OrdDetailID"];


            $mAttributeSQL = "SELECT ProductCount, OptionName AS option_name, Type, AttributeTitle AS Title, AttributePrice AS Price, IFNULL(`Limit`, 0) AS AttributeLimit, IFNULL(LimitPrice, 0) AS LimitPrice FROM orderdetails_attribute_options WHERE OrderID=".$OrderID." AND OrderDetailsID=".$mOrderDetailsID;
            $mAttributeRes = dbAbstract::Execute($mAttributeSQL);
            if (dbAbstract::returnRowsCount($mAttributeRes)>0)
            {
                    $attribute_array = array();
                    $mPricePlus = 0;
                    $mPriceMinus = 0;
                    $mPrevOptionName = "";
                    $mLimitCount = 1;
                    $mPrevProductCount = 0 ;
                    $mLimit = 0;
                    $mLimitPrice = 0;

                    while ($mAttributeRow = dbAbstract::returnObject($mAttributeRes))
                    {	
                            $mLimit = trim($mAttributeRow->AttributeLimit);
                            $mLimitPrice = trim($mAttributeRow->LimitPrice);

                            if (($mLimit<0) || ($mLimitPrice<0))
                            {
                                    $mLimit = 0;
                                    $mLimitPrice = 0;
                            }


                            if (trim(strtolower($mPrevOptionName)) == trim(strtolower($mAttributeRow->option_name)))
                            {
                                    $mLimitCount++;	
                            }
                            else
                            {
                                    if ($mPrevProductCount!=0)
                                    {


                                    }
                                    $mPrevOptionName = $mAttributeRow->option_name;
                                    $mPrevProductCount = $mAttributeRow->ProductCount;
                                    $mLimitCount = 1;


                                    $attribute_name= $mAttributeRow->option_name;
                            }

                            $attribute_inner = $mAttributeRow->Title;
                            $mPrice =  $mAttributeRow->Price; 
                            $mPriceDisp = $mPrice;
                            if ($mPrice!='0' || $mPrice!='0.00') 
                            {
                                    if ($mPrice<0) 
                                    {
                                            $mPriceMinus = $mPriceMinus + $mPrice;		  
                                            $mPriceDisp = " - Subtract ".$currency.$mPrice;
                                    } 
                                    else 
                                    {
                                            $mPricePlus = $mPricePlus + $mPrice;
                                            $mPriceDisp = " - Add ".$currency.$mPrice;
                                    }
                            }
                            else
                            {
                                    $mPrice = ""; 
                            }                                                

                            if ((trim($mPriceDisp)!="0") && (trim($mPriceDisp)!="$0"))
                            {
                            $attribute_inner .= $mPriceDisp.", ";
                            }
                            else
                            {
                            $attribute_inner .= ", ";
                            }

                            if (($mLimit>0) && ($mLimitPrice>0) && ($mLimitCount>$mLimit))
                            {
                                    if (str_replace("|", "", $mPrice)<0) 
                                    {
                                            $mPriceMinus = $mPriceMinus + $mLimitPrice;
                                    }
                                    else
                                    {
                                            $mPricePlus = $mPricePlus + $mLimitPrice;
                                    }

                            }
                            $attribute_array[$attribute_name][] = replaceSpecialChar($attribute_inner);
                            $Tot_atrib_price_Plus = $mPricePlus;
                            $Tot_atrib_price_mines = $mPriceMinus;


                    }
            }


            $assocItemArr = split("~",$Ord_RS2['associations']);
            $assocTotalPrice = 0;
            for($j=0; $j<count($assocItemArr); $j++) 
            {
                $assocOptions= explode("|", $assocItemArr[$j]);
                $assocPrice = (count($assocOptions)>1 ? $assocOptions[1]:0);
                $assocTotalPrice = $assocTotalPrice + $assocPrice; 
            }
            $mQuantity = 1;
            if (isset($Ord_RS2["quantity"]))
            {
                if (is_numeric($Ord_RS2["quantity"]))
                {
                    $mQuantity = $Ord_RS2["quantity"];
                }
            }
            $cart_price = $Ord_RS2['retail_price'];
            $itemTotalPrice = $mQuantity * ($cart_price + $Tot_atrib_price_Plus + $Tot_atrib_price_mines + $assocTotalPrice);
            $order_detail_array[] = array("item_title" => replaceSpecialChar($Ord_RS2["ItemName"]),
                                        "Quantity"=> $Ord_RS2["quantity"],
                                        "special_notes"=> replaceSpecialChar($Ord_RS2["RequestNote"]),
                                        "item_price"=> $Ord_RS2['retail_price']

                                       );

            if($attribute_array) {
                $order_detail_array[$i]['attributes'] = $attribute_array ;
            }
            if($Ord_RS2['associations']) {
                $order_detail_array[$i]['associations'] = replaceSpecialChar(str_replace('|','- add '.$currency,$Ord_RS2['associations']));
            }
            $order_detail_array[$i]['item_total_price'] = "$".number_format($itemTotalPrice,2);
            $i++;
            $itemTotalPrice = 0; 

        }

        $mResturantRow = dbAbstract::ExecuteObject("SELECT * FROM resturants WHERE id = ".$Ord_RS['cat_id']);
        
        $getLatLong = findLatLong($mResturantRow);
        
        $mDDT = "delivery_datetime";
        
        if (trim(strtolower($Ord_RS["order_receiving_method"]))=="pickup")
        {
            $mDDT = "pickup_datetime";
        }
        
        $orderArray =  array(
                    "customer_information" => array("customer_name" => replaceSpecialChar($Ord_RS['cust_your_name']).' '.replaceSpecialChar($Ord_RS["LastName"]), "address" => replaceSpecialChar(trim($Ord_RS["DeliveryAddress"],"~")." ".$Ord_RS["cust_ord_city"]." ".$Ord_RS["cust_ord_state"]),"phone" => replaceSpecialChar($Ord_RS['cust_phone1'])),
                    "restaurant_information" => array("name" => replaceSpecialChar($mResturantRow->name), "slug" => $mResturantRow->url_name, "email" => $mResturantRow->email, "address" => $mResturantRow->rest_address.', '.$mResturantRow->rest_city.' '.$mResturantRow->rest_state.' '.$mResturantRow->rest_zip, "latitude" => $getLatLong[0], "longitude" => $getLatLong[1], "phone" => $mResturantRow->phone, "fax" => $mResturantRow->fax),
                    "order_information" =>array("order_no" => $Ord_RS['OrderID'],
                                                "payment_method" => $Ord_RS['payment_method'],
                                                "order_receiving_method" => $Ord_RS['order_receiving_method'],
                                                $mDDT => $Ord_RS["DesiredDeliveryDate"],
                                                "submission_datetime" => date("m-d-Y h:i:s", strtotime($Ord_RS["submit_time"])),
                                                "special_request" => replaceSpecialChar( $Ord_RS["DelSpecialReq"])),
                    "order_detail" => $order_detail_array, 
                    "coupon_discount" => ($Ord_RS["coupon_discount"] == "")? "$0.00" :"$".number_format($Ord_RS["coupon_discount"], 2),
                    "delivery" => "$".number_format($Ord_RS["delivery_chagres"],2)
                );
        if (trim(strtolower($Ord_RS["order_receiving_method"]))== "delivery")
        {
            $orderArray['customer_information']['delivery_address'] = replaceSpecialChar(str_replace('~',' ',$Ord_RS["delivery"]));
        }
        if($Ord_RS["Tax"])
        {
            $orderArray['tax'] = "$".number_format($Ord_RS["Tax"],2);
        }
        if($Ord_RS["driver_tip"]) 
        {
            $orderArray['driver_tip'] = "$".$Ord_RS["driver_tip"];
        }
        $orderArray['Total'] = "$".number_format($Ord_RS["Totel"],2);
        
    }
    else
    {
        $orderArray = errorFunction("10", "No order exist", "No order exist.", "Data Error");
    }
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: getorderdetails()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    return $orderArray;
}

function returnLocationArray($rest_url, $mLikePercentage = 0,$pUserID=0)
{
    global $mAPICallNumber;
    //$mStartTime = strtotime(date("Y-m-d H:i:s"));
    $getLatLong = findLatLong($rest_url);
    $mLikeCount = returnLikeCount($rest_url->id);
    $mDislikeCount = returnDislikeCount($rest_url->id);
    $mLikePercentage = 0;
    $mSignatureSandwich = checkSignatureSandwich($rest_url->id);
    if (($mLikeCount==0) && ($mDislikeCount==0))
    {
        $mLikePercentage = 0;
    }
    else
    {
        $mLikePercentage = round(($mLikeCount/($mLikeCount + $mDislikeCount))*100);
    }
    if ($pUserID==0)
    {
        $mRetArray = array(
            "id" => replaceSpecialChar($rest_url->id),
            "name" => replaceSpecialChar($rest_url->name),
            "slug" => $rest_url->url_name,
            "address" => replaceSpecialChar($rest_url->rest_address),
            "city" => $rest_url->rest_city,
            "state" => $rest_url->rest_state,
            "zip" => $rest_url->rest_zip,
            "latitude" => $getLatLong[0],
            "longitude" => $getLatLong[1],
            "satisfactionPercentage" => $mLikePercentage,
            "likes" => $mLikeCount,
            "signature_sandwich" => $mSignatureSandwich,
        );
    }
    else
    {
        $mFavorite = checkFavorite($pUserID, $rest_url->id);
        $mRetArray = array(
        "id" => replaceSpecialChar($rest_url->id),
        "name" => replaceSpecialChar($rest_url->name),
        "slug" => $rest_url->url_name,
        "address" => replaceSpecialChar($rest_url->rest_address),
        "city" => $rest_url->rest_city,
        "state" => $rest_url->rest_state,
        "zip" => $rest_url->rest_zip,
        "latitude" => $getLatLong[0],
        "longitude" => $getLatLong[1],
        "satisfactionPercentage" => $mLikePercentage,
        "likes" => $mLikeCount,
        "favorite" => $mFavorite,
        "signature_sandwich" => $mSignatureSandwich,
        );
    }
    if(isset($_GET['signature_sandwitch']) && strtolower($_GET['signature_sandwitch']) == "yes")
    {
        
        if($mSignatureSandwich == "yes")
        {
             return $mRetArray;
        }
    }
    else
    {
        return $mRetArray;
    }
    /*$mEndTime = strtotime(date("Y-m-d H:i:s"));
    $mExecutionTime = $mEndTime - $mStartTime;
    Log::write("BH API: returnLocationArray()", "Line Number: ".__LINE__."\nAPI Call Number: ".$mAPICallNumber."\nExecution Time: ".$mExecutionTime." Seconds", 'BH_API');*/
    
}
?>