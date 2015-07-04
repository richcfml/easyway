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

$mVerifyRequest = 1; //verifyRequest();

if ($mVerifyRequest==1) //Valid Session
{
    if (isset($_GET["max_results"]))
    {
        if (is_numeric($_GET["max_results"]))
        {
            $mMaxResults = $_GET["max_results"];
        }
    }
    
    if (isset($_GET["getrestaurants"]))
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
                $mTmp = explode(",", $_REQUEST["deliversto"]);
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
                $mTmp = explode(",", $_REQUEST["user_address"]);
                $mLat = trim($mTmp[0]);
                $mLong = trim($mTmp[1]);
            }
        }

        $mRes = mysql_query("SELECT * FROM resturants WHERE rest_open_close = 1 AND status = 1 AND bh_restaurant = 1 ORDER BY name");
        while ($rest_url = mysql_fetch_object($mRes))
        {
            doProcessing($rest_url, $mLat, $mLong);
        }
    }
    else if (isset($_GET["getrestaurantdetails"]))
    {
        if (isset($_GET["slugs"])) //Comma Separate Slugs
        {
            $mSlugs = explode(",", $_GET["slugs"]);
            for ($loopCount=0; $loopCount<count($mSlugs); $loopCount++)
            {
                $mUserID = 0;
                $mLiked = 2;
                $mFavorite = 2;
                $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($mSlugs[$loopCount]))."' AND status = 1 AND bh_restaurant = 1";
                $rest_url = mysql_fetch_object(mysql_query($mSQL));
                if ($rest_url)
                {
                    if (isset($_GET["uid"]))
                    {
                        $mUserID = $_GET["uid"];
                    }

                    if (isset($_GET["email"]))
                    {
                        $mSQL = "SELECT id FROM bh_sso_user WHERE email='".$_GET["email"]."'";
                        $mResult = mysql_fetch_object(mysql_query($mSQL));
                        if ($mResult)
                        {
                            $mUserID = $mResult->id;
                        }
                    }

                    if ($mUserID != 0)
                    {
                        $mSQL = "SELECT IFNULL(Rating, 2) AS Rating, IFNULL(favorite, 2) AS favorite FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND user_id=".$mUserID;

                        $mResult = mysql_fetch_object(mysql_query($mSQL));
                        if ($mResult)
                        {
                            $mLiked = $mResult->Rating;
                            $mFavorite = $mResult->favorite;
                        }
                    }

                    if ($mLiked == 0)
                    {
                        $mLiked = "false";
                    }
                    else if ($mLiked == 1)
                    {
                        $mLiked = "true";
                    }
                    else
                    {
                        $mLiked = "no input";
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

                    $business_hours =  mysql_fetch_object(mysql_query("SELECT open, close FROM business_hours WHERE day = ".$day_of_week." AND rest_id = ".$rest_url->id));
                    $timezoneRs = mysql_fetch_array(mysql_query("SELECT time_zone FROM times_zones WHERE id = ".$rest_url->time_zone_id));
                    date_default_timezone_set($timezoneRs['time_zone']);
                    $current_time=date("Hi",time());
                    $mOpen = "n";
                    if ($current_time >= $business_hours->open && $current_time <= $business_hours->close)
                    {
                        $mOpen = "y";
                    }

                    $mLikeCount = 0;
                    $mDislikeCount = 0;
                    $mLikePercentage = 0;
                    $mSQL = "SELECT IFNULL(COUNT(*), 0) AS LikeCount FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND Rating = 1";
                    $mResult = mysql_fetch_object(mysql_query($mSQL));
                    if ($mResult)
                    {
                        $mLikeCount = $mResult->LikeCount;
                    }

                    $mSQL = "SELECT IFNULL(COUNT(*), 0) AS DislikeCount FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND Rating = 0";
                    $mResult = mysql_fetch_object(mysql_query($mSQL));
                    if ($mResult)
                    {
                        $mDislikeCount = $mResult->DislikeCount;
                    }

                    if (($mLikeCount==0) && ($mDislikeCount==0))
                    {
                        $mLikePercentage = 0;
                    }
                    else
                    {
                        $mLikePercentage = round(($mLikeCount/($mLikeCount + $mDislikeCount))*100);
                    }

                    $mLikeRating = array();
                    $mLikeRating[] = array(
                        "like_count" => $mLikeCount,
                        "dislike_count" => $mDislikeCount,
                        "like_percentage" => $mLikePercentage
                            );
                    
                    $mSQLSigSan = "SELECT COUNT(*) AS SignatureSandwiches FROM product WHERE cat_id=".$rest_url->id." AND LOWER(item_title)='signature sandwich'";
                    $mResSigSan = mysql_fetch_object(mysql_query($mSQLSigSan));
                    $mSignatureSandwich = "no";
                    if ($mResSigSan)
                    {
                        if ($mResSigSan->SignatureSandwiches>0)
                        {
                            $mSignatureSandwich = "yes";
                        }
                    }
                    
                    if ($mUserID==0)
                    {
                        if (isset($_GET["open"]))
                        {
                            if ($mOpen=="y")
                            {
                                $mReturn[] = array(
                                        "name" => $rest_url->name,
                                        "slug" => $rest_url->url_name,
                                        "email" => $rest_url->email,
                                        "address" => $rest_url->rest_address,
                                        "Phone" => $rest_url->phone,
                                        "Fax" => $rest_url->fax,
                                        "deliver_charges" => $rest_url->delivery_charges,
                                        "min_total" => $rest_url->order_minimum,
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
                                        "menu" => getMenus($rest_url->id)
                                    );
                            }
                        }
                        else
                        {
                            $mReturn[] = array(
                                        "name" => $rest_url->name,
                                        "slug" => $rest_url->url_name,
                                        "email" => $rest_url->email,
                                        "address" => $rest_url->rest_address,
                                        "Phone" => $rest_url->phone,
                                        "Fax" => $rest_url->fax,
                                        "deliver_charges" => $rest_url->delivery_charges,
                                        "min_total" => $rest_url->order_minimum,
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
                                        "menu" => getMenus($rest_url->id)
                                    );
                        }
                    }
                    else
                    {
                        if (isset($_GET["open"]))
                        {
                            if ($mOpen=="y")
                            {
                                $mReturn[] = array(
                                    "name" => $rest_url->name,
                                    "slug" => $rest_url->url_name,
                                    "email" => $rest_url->email,
                                    "address" => $rest_url->rest_address,
                                    "Phone" => $rest_url->phone,
                                    "Fax" => $rest_url->fax,
                                    "deliver_charges" => $rest_url->delivery_charges,
                                    "min_total" => $rest_url->order_minimum,
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
                                    "satisfaction" => $mLiked,
                                    "satisfactionPercentage" => $mLikePercentage,
                                    "favorite" => $mFavorite,
                                    "menu" => getMenus($rest_url->id)
                                );
                            }
                        }
                        else
                        {
                            $mReturn[] = array(
                                    "name" => $rest_url->name,
                                    "slug" => $rest_url->url_name,
                                    "email" => $rest_url->email,
                                    "address" => $rest_url->rest_address,
                                    "Phone" => $rest_url->phone,
                                    "Fax" => $rest_url->fax,
                                    "deliver_charges" => $rest_url->delivery_charges,
                                    "min_total" => $rest_url->order_minimum,
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
                                    "satisfaction" => $mLiked,
                                    "satisfactionPercentage" => $mLikePercentage,
                                    "favorite" => $mFavorite,
                                    "menu" => getMenus($rest_url->id)
                                );
                        }
                    }
                }

            }
        }
        else
        {
            $mReturn = array(
                        "errorCode" => "",
                        "errorDescription" => "Inavlid call, Slugs not specified.",
                        "errorMessage" => "Inavlid call.",
                        "errorTitle" => "ERROR!"
                    );
        }
    }
    else if (isset($_GET["getfullhours"]))
    {
        if (isset($_GET["slugs"])) //Comma Separate Slugs
        {
            $mSlugs = explode(",", $_GET["slugs"]);
            for ($loopCount=0; $loopCount<count($mSlugs); $loopCount++)
            {
                $mUserID = 0;
                $mLiked = 2;
                $mFavorite = 2;

                $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($mSlugs[$loopCount]))."'";
                $rest_url = mysql_fetch_object(mysql_query($mSQL));
                if ($rest_url)
                {
                    if (isset($_GET["uid"]))
                    {
                        $mUserID = $_GET["uid"];
                    }

                    if (isset($_GET["email"]))
                    {
                        $mSQL = "SELECT id FROM bh_sso_user WHERE email='".$_GET["email"]."'";
                        $mResult = mysql_fetch_object(mysql_query($mSQL));
                        if ($mResult)
                        {
                            $mUserID = $mResult->id;
                        }
                    }

                    if ($mUserID != 0)
                    {
                        $mSQL = "SELECT IFNULL(Rating, 2) AS Rating, IFNULL(favorite, 2) AS favorite FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND user_id=".$mUserID;
                        $mResult = mysql_fetch_object(mysql_query($mSQL));
                        if ($mResult)
                        {
                            $mLiked = $mResult->Rating;
                            $mFavorite = $mResult->favorite;
                        }
                    }

                    if ($mLiked == 0)
                    {
                        $mLiked = "false";
                    }
                    else if ($mLiked == 1)
                    {
                        $mLiked = "true";
                    }
                    else
                    {
                        $mLiked = "no input";
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

                    if ($mUserID==0)
                    {
                        $mReturn[] = array(
                                "name" => $rest_url->name,
                                "hours" => allBusinessHours($rest_url->id)
                            );
                    }
                    else
                    {
                        $mReturn[] = array(
                                "name" => $rest_url->name,
                                "hours" => allBusinessHours($rest_url->id),
                                "satisfaction" => $mLiked,
                                "favorite" => $mFavorite
                            );
                    }
                }

            }
        }
        else
        {
            $mReturn = array(
                        "errorCode" => "",
                        "errorDescription" => "Inavlid call, Slugs not specified.",
                        "errorMessage" => "Inavlid call.",
                        "errorTitle" => "ERROR!"
                    );
        }
    }
    else if (isset($_GET["featured"]))
    {
        $mSQL = "SELECT * FROM resturants WHERE status = 1 AND bh_restaurant = 1 AND bh_featured = 1 ORDER BY name";
        $mResFeatured = mysql_query($mSQL);
        
        while ($rest_url = mysql_fetch_object($mResFeatured))
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

            $business_hours =  mysql_fetch_object(mysql_query("SELECT open, close FROM business_hours WHERE day = ".$day_of_week." AND rest_id = ".$rest_url->id));
            $timezoneRs = mysql_fetch_array(mysql_query("SELECT time_zone FROM times_zones WHERE id = ".$rest_url->time_zone_id));
            date_default_timezone_set($timezoneRs['time_zone']);
            $current_time=date("Hi",time());
            $mOpen = "n";
            if ($current_time >= $business_hours->open && $current_time <= $business_hours->close)
            {
                $mOpen = "y";
            }

            $mLikeCount = 0;
            $mDislikeCount = 0;
            $mLikePercentage = 0;
            $mSQL = "SELECT IFNULL(COUNT(*), 0) AS LikeCount FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND Rating = 1";
            $mResult = mysql_fetch_object(mysql_query($mSQL));
            if ($mResult)
            {
                $mLikeCount = $mResult->LikeCount;
            }

            $mSQL = "SELECT IFNULL(COUNT(*), 0) AS DislikeCount FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND Rating = 0";
            $mResult = mysql_fetch_object(mysql_query($mSQL));
            if ($mResult)
            {
                $mDislikeCount = $mResult->DislikeCount;
            }

            if (($mLikeCount==0) && ($mDislikeCount==0))
            {
                $mLikePercentage = 0;
            }
            else
            {
                $mLikePercentage = round(($mLikeCount/($mLikeCount + $mDislikeCount))*100);
            }

            $mLikeRating = array();
            $mLikeRating[] = array(
                "like_count" => $mLikeCount,
                "dislike_count" => $mDislikeCount,
                "like_percentage" => $mLikePercentage
                    );

            $mSQLSigSan = "SELECT COUNT(*) AS SignatureSandwiches FROM product WHERE cat_id=".$rest_url->id." AND LOWER(item_title)='signature sandwich'";
            $mResSigSan = mysql_fetch_object(mysql_query($mSQLSigSan));
            $mSignatureSandwich = "no";
            if ($mResSigSan)
            {
                if ($mResSigSan->SignatureSandwiches>0)
                {
                    $mSignatureSandwich = "yes";
                }
            }

            if (isset($_GET["open"]))
            {
                if ($mOpen=="y")
                {
                    if (isset($_REQUEST["detail"]))
                    {
                        if ($mRecordCount<$mMaxResults)
                        {
                            $mRecordCount = $mRecordCount + 1;
                            $mReturn[] = array(
                                "name" => $rest_url->name,
                                "slug" => $rest_url->url_name,
                                "email" => $rest_url->email,
                                "address" => $rest_url->rest_address,
                                "Phone" => $rest_url->phone,
                                "Fax" => $rest_url->fax,
                                "deliver_charges" => $rest_url->delivery_charges,
                                "min_total" => $rest_url->order_minimum,
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
                                "menu" => getMenus($rest_url->id)
                            );
                        }
                    }
                    else
                    {
                        if ($mRecordCount<$mMaxResults)
                        {
                            $mRecordCount = $mRecordCount + 1;
                            $mReturn[] = array(
                                        "slug" => $rest_url->url_name
                                    );
                        }
                    }
                }
            }
            else
            {
                if (isset($_REQUEST["detail"]))
                {
                    if ($mRecordCount<$mMaxResults)
                    {
                        $mRecordCount = $mRecordCount + 1;
                        $mReturn[] = array(
                            "name" => $rest_url->name,
                            "slug" => $rest_url->url_name,
                            "email" => $rest_url->email,
                            "address" => $rest_url->rest_address,
                            "Phone" => $rest_url->phone,
                            "Fax" => $rest_url->fax,
                            "deliver_charges" => $rest_url->delivery_charges,
                            "min_total" => $rest_url->order_minimum,
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
                            "menu" => getMenus($rest_url->id)
                        );
                    }
                }
                else
                {
                    if ($mRecordCount<$mMaxResults)
                    {
                        $mRecordCount = $mRecordCount + 1;
                        $mReturn[] = array(
                                    "slug" => $rest_url->url_name
                                );
                    }
                }
            }
        }
    }
    else if (isset($_GET["like"]))
    {
        if (isset($_GET["slug"]) && (isset($_GET["email"]) || isset($_GET["uid"])))
        {
            $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
            $rest_url = mysql_fetch_object(mysql_query($mSQL));
            if ($rest_url)
            {
                if (isset($_GET["uid"]))
                {
                    $mSQL = "SELECT COUNT(*) AS RecordCount FROM bh_rest_rating WHERE user_id = ".$_GET["uid"]." AND rest_id=".$rest_url->id;
                    $mResult = mysql_fetch_object(mysql_query($mSQL));
                    if ($mResult)
                    {
                        if ($mResult->RecordCount>0)
                        {
                            mysql_query("UPDATE bh_rest_rating SET Rating=1 WHERE user_id = ".$_GET["uid"]." AND rest_id=".$rest_url->id);
                        }
                        else
                        {
                            mysql_query("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", ".$_GET["uid"].", 2, 1)");
                        }

                         $mReturn = array(
                                "successDescription" => "Restaurant liked successfully"
                            );
                    }
                }
                else if (isset($_GET["email"]))
                {
                    $mSQL = "SELECT id FROM bh_sso_user WHERE email='".$_GET["email"]."'";
                    $mResult = mysql_fetch_object(mysql_query($mSQL));
                    if ($mResult)
                    {
                        $mUID = $mResult->id;
                        $mSQL = "SELECT COUNT(*) AS RecordCount FROM bh_rest_rating WHERE user_id = ".$mUID." AND rest_id=".$rest_url->id;
                        $mResult = mysql_fetch_object(mysql_query($mSQL));
                        if ($mResult)
                        {
                            if ($mResult->RecordCount>0)
                            {
                                mysql_query("UPDATE bh_rest_rating SET Rating=1 WHERE user_id = ".$mUID." AND rest_id=".$rest_url->id);
                            }
                            else
                            {
                                mysql_query("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", ".$mUID.", 2, 1)");
                            }
                            $mReturn = array(
                                "successDescription" => "Restaurant liked successfully"
                            );
                        }
                    }
                }
            }

        }
        else
        {
            $mReturn = array(
                        "errorCode" => "",
                        "errorDescription" => "Inavlid call, Slug or user not specified.",
                        "errorMessage" => "Inavlid call.",
                        "errorTitle" => "ERROR!"
                    );
        }
    }
    else if (isset($_GET["unlike"]))
    {
        if (isset($_GET["slug"]) && (isset($_GET["email"]) || isset($_GET["uid"])))
        {
            $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
            $rest_url = mysql_fetch_object(mysql_query($mSQL));
            if ($rest_url)
            {
                if (isset($_GET["uid"]))
                {
                    $mSQL = "SELECT COUNT(*) AS RecordCount FROM bh_rest_rating WHERE user_id = ".$_GET["uid"]." AND rest_id=".$rest_url->id;
                    $mResult = mysql_fetch_object(mysql_query($mSQL));
                    if ($mResult)
                    {
                        if ($mResult->RecordCount>0)
                        {
                            mysql_query("UPDATE bh_rest_rating SET Rating=2 WHERE user_id = ".$_GET["uid"]." AND rest_id=".$rest_url->id);
                        }
                        else
                        {
                            mysql_query("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", ".$_GET["uid"].", 2, 2)");
                        }
                        $mReturn = array(
                                "successDescription" => "Restaurant un-liked successfully"
                            );
                    }
                }
                else if (isset($_GET["email"]))
                {
                    $mSQL = "SELECT id FROM bh_sso_user WHERE email = '".$_GET["email"]."'";
                    $mResult = mysql_fetch_object(mysql_query($mSQL));
                    if ($mResult)
                    {
                        $mUID = $mResult->id;
                        $mSQL = "SELECT COUNT(*) AS RecordCount FROM bh_rest_rating WHERE user_id = ".$mUID." AND rest_id=".$rest_url->id;
                        $mResult = mysql_fetch_object(mysql_query($mSQL));
                        if ($mResult)
                        {
                            if ($mResult->RecordCount>0)
                            {
                                mysql_query("UPDATE bh_rest_rating SET Rating=2 WHERE user_id = ".$mUID." AND rest_id=".$rest_url->id);
                            }
                            else
                            {
                                mysql_query("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", ".$mUID.", 2, 2)");
                            }
                            $mReturn = array(
                                "successDescription" => "Restaurant un-liked successfully"
                            );
                        }
                    }
                }
            }

        }
        else
        {
            $mReturn = array(
                        "errorCode" => "",
                        "errorDescription" => "Inavlid call, Slug or user not specified.",
                        "errorMessage" => "Inavlid call.",
                        "errorTitle" => "ERROR!"
                    );
        }
    }
    else if (isset($_GET["dislike"]))
    {
        if (isset($_GET["slug"]) && (isset($_GET["email"]) || isset($_GET["uid"])))
        {
            $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
            $rest_url = mysql_fetch_object(mysql_query($mSQL));
            if ($rest_url)
            {
                if (isset($_GET["uid"]))
                {
                    $mSQL = "SELECT COUNT(*) AS RecordCount FROM bh_rest_rating WHERE user_id = ".$_GET["uid"]." AND rest_id=".$rest_url->id;
                    $mResult = mysql_fetch_object(mysql_query($mSQL));
                    if ($mResult)
                    {
                        if ($mResult->RecordCount>0)
                        {
                            mysql_query("UPDATE bh_rest_rating SET Rating=0 WHERE user_id = ".$_GET["uid"]." AND rest_id=".$rest_url->id);
                        }
                        else
                        {
                            mysql_query("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", ".$_GET["uid"].", 2, 0)");
                        }
                        $mReturn = array(
                                "successDescription" => "Restaurant disliked successfully"
                            );
                    }
                }
                else if (isset($_GET["email"]))
                {
                    $mSQL = "SELECT id FROM bh_sso_user WHERE email = '".$_GET["email"]."'";
                    $mResult = mysql_fetch_object(mysql_query($mSQL));
                    if ($mResult)
                    {
                        $mUID = $mResult->id;
                        $mSQL = "SELECT COUNT(*) AS RecordCount FROM bh_rest_rating WHERE user_id = ".$mUID." AND rest_id=".$rest_url->id;
                        $mResult = mysql_fetch_object(mysql_query($mSQL));
                        if ($mResult)
                        {
                            if ($mResult->RecordCount>0)
                            {
                                mysql_query("UPDATE bh_rest_rating SET Rating=0 WHERE user_id = ".$mUID." AND rest_id=".$rest_url->id);
                            }
                            else
                            {
                                mysql_query("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", ".$mUID.", 2, 0)");
                            }
                            $mReturn = array(
                                "successDescription" => "Restaurant disliked successfully"
                            );
                        }
                    }
                }
            }

        }
        else
        {
            $mReturn = array(
                        "errorCode" => "",
                        "errorDescription" => "Inavlid call, Slug or user not specified.",
                        "errorMessage" => "Inavlid call.",
                        "errorTitle" => "ERROR!"
                    );
        }
    }
    else if (isset($_GET["undislike"]))
    {
        if (isset($_GET["slug"]) && (isset($_GET["email"]) || isset($_GET["uid"])))
        {
            $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
            $rest_url = mysql_fetch_object(mysql_query($mSQL));
            if ($rest_url)
            {
                if (isset($_GET["uid"]))
                {
                    $mSQL = "SELECT COUNT(*) AS RecordCount FROM bh_rest_rating WHERE user_id = ".$_GET["uid"]." AND rest_id=".$rest_url->id;
                    $mResult = mysql_fetch_object(mysql_query($mSQL));
                    if ($mResult)
                    {
                        if ($mResult->RecordCount>0)
                        {
                            mysql_query("UPDATE bh_rest_rating SET Rating=2 WHERE user_id = ".$_GET["uid"]." AND rest_id=".$rest_url->id);
                        }
                        else
                        {
                            mysql_query("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", ".$_GET["uid"].", 2, 2)");
                        }
                        $mReturn = array(
                                "successDescription" => "Restaurant un-disliked successfully"
                            );
                    }
                }
                else if (isset($_GET["email"]))
                {
                    $mSQL = "SELECT id FROM bh_sso_user WHERE email = '".$_GET["email"]."'";
                    $mResult = mysql_fetch_object(mysql_query($mSQL));
                    if ($mResult)
                    {
                        $mUID = $mResult->id;
                        $mSQL = "SELECT COUNT(*) AS RecordCount FROM bh_rest_rating WHERE user_id = ".$mUID." AND rest_id=".$rest_url->id;
                        $mResult = mysql_fetch_object(mysql_query($mSQL));
                        if ($mResult)
                        {
                            if ($mResult->RecordCount>0)
                            {
                                mysql_query("UPDATE bh_rest_rating SET Rating=2 WHERE user_id = ".$mUID." AND rest_id=".$rest_url->id);
                            }
                            else
                            {
                                mysql_query("INSERT INTO bh_rest_rating (rest_id, user_id, favorite, Rating) VALUES (".$rest_url->id.", ".$mUID.", 2, 2)");
                            }
                            $mReturn = array(
                                "successDescription" => "Restaurant un-disliked successfully"
                            );
                        }
                    }
                }
            }

        }
        else
        {
            $mReturn = array(
                        "errorCode" => "",
                        "errorDescription" => "Inavlid call, Slug or user not specified.",
                        "errorMessage" => "Inavlid call.",
                        "errorTitle" => "ERROR!"
                    );
        }
    }
    else if (isset($_GET["favorites"]))
    {
        if (isset($_GET["email"]) || isset($_GET["uid"]))
        {   
            if(isset($_GET["email"]))
            {
                $qry = "select * from bh_sso_user WHERE email = '". $_GET['email'] ."'";
            }
            else if($_GET["uid"])
            {
                $qry = "select * from bh_sso_user WHERE id = '". $_GET['uid'] ."'";
            }
            $mResult = mysql_query($qry);
            $userFavoriteid = mysql_fetch_object(mysql_query($qry));
            $userResult = mysql_num_rows($mResult);
            if($userResult > 0)
            {
                if (!empty($userFavoriteid->id))
                {
                    $mUserID = $userFavoriteid->id;
                    $mysqlqry = mysql_query("select rest_id from bh_rest_rating WHERE user_id = '". $userFavoriteid->id ."' and favorite = 1");
                    $countFavorite = mysql_num_rows($mysqlqry);
                    if($countFavorite > 0)
                    {
                        while($restIDS = mysql_fetch_object($mysqlqry))
                        {
                            $restID.=$restIDS->rest_id .",";
                        }

                        $restID = substr($restID,0,-1);

                        $mysqlqry = mysql_query("select * from resturants WHERE id in(". $restID .") ORDER BY name");
                        while($rest_url = mysql_fetch_object($mysqlqry))
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

                            $business_hours =  mysql_fetch_object(mysql_query("SELECT open, close FROM business_hours WHERE day = ".$day_of_week." AND rest_id = ".$rest_url->id));
                            $timezoneRs = mysql_fetch_array(mysql_query("SELECT time_zone FROM times_zones WHERE id = ".$rest_url->time_zone_id));
                            date_default_timezone_set($timezoneRs['time_zone']);
                            $current_time=date("Hi",time());
                            $mOpen = "n";
                            if ($current_time >= $business_hours->open && $current_time <= $business_hours->close)
                            {
                                $mOpen = "y";
                            }

                            $mLikeCount = 0;
                            $mDislikeCount = 0;
                            $mLikePercentage = 0;
                            $mSQL = "SELECT IFNULL(COUNT(*), 0) AS LikeCount FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND Rating = 1";
                            $mResult = mysql_fetch_object(mysql_query($mSQL));
                            if ($mResult)
                            {
                                $mLikeCount = $mResult->LikeCount;
                            }

                            $mSQL = "SELECT IFNULL(COUNT(*), 0) AS DislikeCount FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND Rating = 0";
                            $mResult = mysql_fetch_object(mysql_query($mSQL));
                            if ($mResult)
                            {
                                $mDislikeCount = $mResult->DislikeCount;
                            }

                            if (($mLikeCount==0) && ($mDislikeCount==0))
                            {
                                $mLikePercentage = 0;
                            }
                            else
                            {
                                $mLikePercentage = round(($mLikeCount/($mLikeCount + $mDislikeCount))*100);
                            }

                            $mLikeRating = array();
                            $mLikeRating[] = array(
                                "like_count" => $mLikeCount,
                                "dislike_count" => $mDislikeCount,
                                "like_percentage" => $mLikePercentage
                                    );

                            $mSQLSigSan = "SELECT COUNT(*) AS SignatureSandwiches FROM product WHERE cat_id=".$rest_url->id." AND LOWER(item_title)='signature sandwich'";
                            $mResSigSan = mysql_fetch_object(mysql_query($mSQLSigSan));
                            $mSignatureSandwich = "no";
                            if ($mResSigSan)
                            {
                                if ($mResSigSan->SignatureSandwiches>0)
                                {
                                    $mSignatureSandwich = "yes";
                                }
                            }

                            $mLiked = 2;
                            $mFavorite = 2;

                            $mSQL = "SELECT IFNULL(Rating, 2) AS Rating, IFNULL(favorite, 2) AS favorite FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND user_id=".$mUserID;
                            $mResult = mysql_fetch_object(mysql_query($mSQL));
                            if ($mResult)
                            {
                                $mLiked = $mResult->Rating;
                                $mFavorite = $mResult->favorite;
                            }

                            if ($mLiked == 0)
                            {
                                $mLiked = "false";
                            }
                            else if ($mLiked == 1)
                            {
                                $mLiked = "true";
                            }
                            else
                            {
                                $mLiked = "no input";
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

                            $orderDetailsArray[] = array("name" => $rest_url->name,
                                                        "slug" => $rest_url->url_name,
                                                        "email" => $rest_url->email,
                                                        "address" => $rest_url->rest_address,
                                                        "Phone" => $rest_url->phone,
                                                        "Fax" => $rest_url->fax,
                                                        "deliver_charges" => $rest_url->delivery_charges,
                                                        "min_total" => $rest_url->order_minimum,
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
                                                        "satisfaction" => $mLiked,
                                                        "satisfactionPercentage" => $mLikePercentage,
                                                        "favorite" => $mFavorite,
                                                        "menu" => getMenus($rest_url->id));
                          
                        }
                        $arrayCount = count($orderDetailsArray);
                        for($j=0; $j<=$arrayCount-1;$j++)
                        {
                            $mReturn[$userFavoriteid->id][$j] = $orderDetailsArray[$j];
                        }
                    }
                    else
                    {
                        $mReturn = errorFunction("","No Restaurant in your favorite list.","No Restaurant in your favorite list.","ERROR!");
                    }
                }
            }
            else
            {
                $mReturn = errorFunction("","User id not exist.","User id not exist.","ERROR!");
            }
        }
        else
        {
            $mReturn = errorFunction("","Inavlid call, user id not specified.","Inavlid call.","ERROR!");
        }
    }
    else if (isset($_GET["addfavorite"]))
    {
        $sso_email = $_GET['email'];
        $res_url = $_GET['slug'];


        if (isset($_GET["email"]) || isset($_GET["uid"]))
        {
            $mVerifyRequest = verifyRequest();
            if(isset($_GET["email"]))
            {
                $qry = "select id from bh_sso_user WHERE email = '". $sso_email."'";
            }
            else if(isset($_GET["uid"]))
            {
                $qry = "select id from bh_sso_user WHERE user_id = '". $_GET["uid"]."'";
            }
            $count_user = mysql_query($qry);
            $userResult = mysql_fetch_object(mysql_query($qry));


            $res = "select id from resturants WHERE url_name = '".$res_url."'";
            $count_rest  = mysql_query($res);
            $resResult = mysql_fetch_object(mysql_query($res));

            if(mysql_num_rows($count_user)==0 )
            {
                 $mReturn = errorFunction("","User id not exist.","User id not exist","ERROR!");
            }

            else if(mysql_num_rows($count_rest)==0 )
            {
                 $mReturn = errorFunction("","No restaurant exist with this slug name.","No restaurant exist with this slug name.","ERROR!");
            }
            else
            {
                $mSQL = "SELECT * FROM bh_rest_rating WHERE rest_id= $resResult->id AND user_id = $userResult->id";
                $user_qry  = mysql_query($mSQL);
                $favoriteRes = mysql_fetch_object(mysql_query($mSQL));

                if(mysql_num_rows($user_qry)>0 )
                {
                    if($favoriteRes->favorite == 1)
                    {
                        $mReturn[] = array("errorMessage" => "You Have already marked this restaurant as your favourite!");
                    }
                    else
                    {
                        $record="Update bh_rest_rating SET favorite='1' where user_id=".$userResult->id." and rest_id=".$resResult->id."";
                        $sso_id = mysql_query($record);
                        $mReturn[] = array("successDescription" => "Restaurant has been added in your favorite list!");
                    }
                }
                else
                {
                    $record="INSERT INTO bh_rest_rating SET
                              user_id=".$userResult->id."
                            , rest_id=".$resResult->id."
                            , favorite='1'
                            , Rating='0'";

                    $sso_id = mysql_query($record);
                    $mReturn[] = array("successDescription" => "Restaurant has been added in your favorite list!");
                }
            }
        }
        else
        {
            $mReturn = errorFunction("","Inavlid call, user id not specified.","Inavlid call.","ERROR!");
        }
    }
    else if (isset($_GET["removefavorite"]))
    {
        $sso_email = $_GET['email'];
        $res_url = $_GET['slug'];


        if (isset($_GET["email"]) || isset($_GET["uid"]))
        {
            $mVerifyRequest = verifyRequest();
            if(isset($_GET["email"]))
            {
                $qry = "select id from bh_sso_user WHERE email = '". $sso_email."'";
            }
            else if(isset($_GET["uid"]))
            {
                $qry = "select id from bh_sso_user WHERE user_id = '". $_GET["uid"]."'";
            }
            
            $count_user = mysql_query($qry);
            $userResult = mysql_fetch_object(mysql_query($qry));


            $res = "select id from resturants WHERE url_name = '".$res_url."'";
            $count_rest  = mysql_query($res);
            $resResult = mysql_fetch_object(mysql_query($res));

            if(mysql_num_rows($count_user)==0 )
            {
                 $mReturn = errorFunction("","User id not exist.","User id not exist","ERROR!");
            }

            else if(mysql_num_rows($count_rest)==0 )
            {
                 $mReturn = errorFunction("","No restaurant exist with this slug name.","No restaurant exist with this slug name.","ERROR!");
            }
            else
            {
                $mSQL = "SELECT * FROM bh_rest_rating WHERE rest_id= $resResult->id AND user_id = $userResult->id and favorite = 1";
                $user_qry  = mysql_query($mSQL);

                if(mysql_num_rows($user_qry)==0 )
                {
                    $mReturn[] = array("errorMessage" => "This restaurant is not in your favorite list!");
                }
                else
                {
                    $record="Update bh_rest_rating SET favorite='0' where user_id=".$userResult->id." and rest_id=".$resResult->id."";

                    $sso_id = mysql_query($record);
                    $mReturn[] = array("successDescription" => "Restaurant has been removed from your favorite list!");
                }
            }
        }
        else
        {
            $mReturn = errorFunction("","Inavlid call, user id not specified.","Inavlid call.","ERROR!");
        }
    }
    else if (isset($_GET["menus"]))
    {
        if (isset($_GET["slug"]))
        {
            $mSQL = "SELECT * FROM resturants WHERE LOWER(TRIM(url_name))='".strtolower(trim($_GET["slug"]))."'";
            $rest_url = mysql_fetch_object(mysql_query($mSQL));
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
            $mReturn = array(
                        "errorCode" => "",
                        "errorDescription" => "Inavlid call, Slug not specified.",
                        "errorMessage" => "Inavlid call.",
                        "errorTitle" => "ERROR!"
                    );
        }
    }
    else
    {
        $mReturn = errorFunction("","Inavlid call, operation not specified or invalid operation.","Inavlid call.","ERROR!");
    }
}
else if ($mVerifyRequest==0) //This will never happen
{
    $mReturn = array(
                        "errorCode" => "",
                        "errorDescription" => "Inavlid call, SSO not specified.",
                        "errorMessage" => "Inavlid call.",
                        "errorTitle" => "ERROR!"
                    );
}
else if ($mVerifyRequest==2) //Session ID not present
{
    $mReturn = errorFunction("","Inavlid call, SSO not specified.","Inavlid call.","ERROR!");
}
else if ($mVerifyRequest==3) //Session ID expred
{
    $mReturn = errorFunction("","Your session has expired. Please Sign in again.","Session Expired.","ERROR!");
}

/*if (isset($_GET["max_results"]))
{
    if (count($mReturn)>$_GET["max_results"])
    {
        $mReturn = array_slice($mReturn, 0, $_GET["max_results"]);
    }
}*/

if (count($mReturn)==0)
{
    $mReturn = errorFunction("","No restaurants found according to provided search criteria.","Empty result set.","NOTIFICATION!");
}

/*echo("<pre>");
print_r($mReturn);
echo("</pre>");*/

$json = json_encode($mReturn, true);

echo($json);
/* Search Functions Ends Here */



/* General (Helping) Functions Starts Here */
function doProcessing($rest_url, $pLat="", $pLong="")
{
    global $mReturn, $SiteUrl, $mRecordCount, $mMaxResults;

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

    $business_hours =  mysql_fetch_object(mysql_query("SELECT open, close FROM business_hours WHERE day = ".$day_of_week." AND rest_id = ".$rest_url->id));
    $timezoneRs = mysql_fetch_array(mysql_query("SELECT time_zone FROM times_zones WHERE id = ".$rest_url->time_zone_id));
    date_default_timezone_set($timezoneRs['time_zone']);
    $current_time=date("Hi",time());
    $mOpen = "n";
    if ($current_time >= $business_hours->open && $current_time <= $business_hours->close)
    {
        $mOpen = "y";
    }

    $mLikeCount = 0;
    $mDislikeCount = 0;
    $mLikePercentage = 0;
    $mSQL = "SELECT IFNULL(COUNT(*), 0) AS LikeCount FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND Rating = 1";
    $mResult = mysql_fetch_object(mysql_query($mSQL));
    if ($mResult)
    {
        $mLikeCount = $mResult->LikeCount;
    }

    $mSQL = "SELECT IFNULL(COUNT(*), 0) AS DislikeCount FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND Rating = 0";
    $mResult = mysql_fetch_object(mysql_query($mSQL));
    if ($mResult)
    {
        $mDislikeCount = $mResult->DislikeCount;
    }

    if (($mLikeCount==0) && ($mDislikeCount==0))
    {
        $mLikePercentage = 0;
    }
    else
    {
        $mLikePercentage = round(($mLikeCount/($mLikeCount + $mDislikeCount))*100);
    }

    $mUserID = 0;
    if (isset($_GET["uid"]))
    {
        $mUserID = $_GET["uid"];
    }

    if (isset($_GET["email"]))
    {
        $mSQL = "SELECT id FROM bh_sso_user WHERE email='".$_GET["email"]."'";
        $mResult = mysql_fetch_object(mysql_query($mSQL));
        if ($mResult)
        {
            $mUserID = $mResult->id;
        }
    }

    $mLiked = 2;
    $mFavorite = 2;

    if ($mUserID != 0)
    {
        $mSQL = "SELECT IFNULL(Rating, 2) AS Rating, IFNULL(favorite, 2) AS favorite FROM bh_rest_rating WHERE rest_id=".$rest_url->id." AND user_id=".$mUserID;
        $mResult = mysql_fetch_object(mysql_query($mSQL));
        if ($mResult)
        {
            $mLiked = $mResult->Rating;
            $mFavorite = $mResult->favorite;
        }
    }

    if ($mLiked == 0)
    {
        $mLiked = "false";
    }
    else if ($mLiked == 1)
    {
        $mLiked = "true";
    }
    else
    {
        $mLiked = "no input";
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

    $mLikeRating[] = array(
                        "like_count" => $mLikeCount,
                        "dislike_count" => $mDislikeCount,
                        "like_percentage" => $mLikePercentage
                            );
    
    $mSQLSigSan = "SELECT COUNT(*) AS SignatureSandwiches FROM product WHERE cat_id=".$rest_url->id." AND LOWER(item_title)='signature sandwich'";
    $mResSigSan = mysql_fetch_object(mysql_query($mSQLSigSan));
    $mSignatureSandwich = "no";
    if ($mResSigSan)
    {
        if ($mResSigSan->SignatureSandwiches>0)
        {
            $mSignatureSandwich = "yes";
        }
    }
            
    if (isset($_REQUEST["deliversto"]))
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
                        if (isset($_REQUEST["open"]))
                        {
                            if ($mOpen == "y")
                            {
                                if (isset($_REQUEST["detail"]))
                                {
                                    if ($mUserID==0)
                                    {
                                        if ($mRecordCount<$mMaxResults)
                                        {
                                            $mRecordCount = $mRecordCount + 1;
                                            $mReturn[] = array(
                                                        "name" => $rest_url->name,
                                                        "slug" => $rest_url->url_name,
                                                        "email" => $rest_url->email,
                                                        "address" => $rest_url->rest_address,
                                                        "Phone" => $rest_url->phone,
                                                        "Fax" => $rest_url->fax,
                                                        "deliver_charges" => $rest_url->delivery_charges,
                                                        "min_total" => $rest_url->order_minimum,
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
                                                        "menu" => getMenus($rest_url->id)
                                                    );
                                        }
                                    }
                                    else
                                    {
                                        if ($mRecordCount<$mMaxResults)
                                        {
                                            $mRecordCount = $mRecordCount + 1;
                                            $mReturn[] = array(
                                                    "name" => $rest_url->name,
                                                    "slug" => $rest_url->url_name,
                                                    "email" => $rest_url->email,
                                                    "address" => $rest_url->rest_address,
                                                    "Phone" => $rest_url->phone,
                                                    "Fax" => $rest_url->fax,
                                                    "deliver_charges" => $rest_url->delivery_charges,
                                                    "min_total" => $rest_url->order_minimum,
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
                                                    "satisfaction" => $mLiked,
                                                    "satisfactionPercentage" => $mLikePercentage,
                                                    "favorite" => $mFavorite,
                                                    "menu" => getMenus($rest_url->id)
                                                );
                                        }
                                    }
                                }
                                else
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                    "slug" => $rest_url->url_name
                                                );
                                    }
                                }
                            }
                        }
                        else
                        {
                            if (isset($_REQUEST["detail"]))
                            {
                                if ($mUserID==0)
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                "name" => $rest_url->name,
                                                "slug" => $rest_url->url_name,
                                                "email" => $rest_url->email,
                                                "address" => $rest_url->rest_address,
                                                "Phone" => $rest_url->phone,
                                                "Fax" => $rest_url->fax,
                                                "deliver_charges" => $rest_url->delivery_charges,
                                                "min_total" => $rest_url->order_minimum,
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
                                                "menu" => getMenus($rest_url->id)
                                            );
                                    }
                                }
                                else
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                "name" => $rest_url->name,
                                                "slug" => $rest_url->url_name,
                                                "email" => $rest_url->email,
                                                "address" => $rest_url->rest_address,
                                                "Phone" => $rest_url->phone,
                                                "Fax" => $rest_url->fax,
                                                "deliver_charges" => $rest_url->delivery_charges,
                                                "min_total" => $rest_url->order_minimum,
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
                                                "satisfaction" => $mLiked,
                                                "satisfactionPercentage" => $mLikePercentage,
                                                "favorite" => $mFavorite,
                                                "menu" => getMenus($rest_url->id)
                                            );
                                    }
                                }
                            }
                            else
                            {
                                if ($mRecordCount<$mMaxResults)
                                {
                                    $mRecordCount = $mRecordCount + 1;
                                    $mReturn[] = array(
                                                "slug" => $rest_url->url_name
                                            );
                                }
                            }
                        }
                    }
                    else if ($rest_url->zone2 && pointInPolygon($x, $y, $zone2_coordinates))
                    {
                        if (isset($_REQUEST["open"]))
                        {
                            if ($mOpen == "y")
                            {
                                if (isset($_REQUEST["detail"]))
                                {
                                    if ($mUserID==0)
                                    {
                                        if ($mRecordCount<$mMaxResults)
                                        {
                                            $mRecordCount = $mRecordCount + 1;
                                            $mReturn[] = array(
                                                    "name" => $rest_url->name,
                                                    "slug" => $rest_url->url_name,
                                                    "email" => $rest_url->email,
                                                    "address" => $rest_url->rest_address,
                                                    "Phone" => $rest_url->phone,
                                                    "Fax" => $rest_url->fax,
                                                    "deliver_charges" => $rest_url->delivery_charges,
                                                    "min_total" => $rest_url->order_minimum,
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
                                                    "menu" => getMenus($rest_url->id)
                                                );
                                        }
                                    }
                                    else
                                    {
                                        if ($mRecordCount<$mMaxResults)
                                        {
                                            $mRecordCount = $mRecordCount + 1;
                                            $mReturn[] = array(
                                                    "name" => $rest_url->name,
                                                    "slug" => $rest_url->url_name,
                                                    "email" => $rest_url->email,
                                                    "address" => $rest_url->rest_address,
                                                    "Phone" => $rest_url->phone,
                                                    "Fax" => $rest_url->fax,
                                                    "deliver_charges" => $rest_url->delivery_charges,
                                                    "min_total" => $rest_url->order_minimum,
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
                                                    "satisfaction" => $mLiked,
                                                    "satisfactionPercentage" => $mLikePercentage,
                                                    "favorite" => $mFavorite,
                                                    "menu" => getMenus($rest_url->id)
                                                );
                                        }
                                    }
                                }
                                else
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                    "slug" => $rest_url->url_name
                                                );
                                    }
                                }
                            }
                        }
                        else
                        {
                            if (isset($_REQUEST["detail"]))
                            {
                                if ($mUserID==0)
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                "name" => $rest_url->name,
                                                "slug" => $rest_url->url_name,
                                                "email" => $rest_url->email,
                                                "address" => $rest_url->rest_address,
                                                "Phone" => $rest_url->phone,
                                                "Fax" => $rest_url->fax,
                                                "deliver_charges" => $rest_url->delivery_charges,
                                                "min_total" => $rest_url->order_minimum,
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
                                                "menu" => getMenus($rest_url->id)
                                            );
                                    }
                                }
                                else
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                "name" => $rest_url->name,
                                                "slug" => $rest_url->url_name,
                                                "email" => $rest_url->email,
                                                "address" => $rest_url->rest_address,
                                                "Phone" => $rest_url->phone,
                                                "Fax" => $rest_url->fax,
                                                "deliver_charges" => $rest_url->delivery_charges,
                                                "min_total" => $rest_url->order_minimum,
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
                                                "satisfaction" => $mLiked,
                                                "satisfactionPercentage" => $mLikePercentage,
                                                "favorite" => $mFavorite,
                                                "menu" => getMenus($rest_url->id)
                                            );
                                    }
                                }
                            }
                            else
                            {
                                if ($mRecordCount<$mMaxResults)
                                {
                                    $mRecordCount = $mRecordCount + 1;
                                    $mReturn[] = array(
                                                "slug" => $rest_url->url_name
                                            );
                                }
                            }
                        }
                    }
                    else if ($rest_url->zone3 && pointInPolygon($x, $y, $zone3_coordinates))
                    {
                        if (isset($_REQUEST["open"]))
                        {
                            if ($mOpen == "y")
                            {
                                if (isset($_REQUEST["detail"]))
                                {
                                    if ($mUserID==0)
                                    {
                                        if ($mRecordCount<$mMaxResults)
                                        {
                                            $mRecordCount = $mRecordCount + 1;
                                            $mReturn[] = array(
                                                    "name" => $rest_url->name,
                                                    "slug" => $rest_url->url_name,
                                                    "email" => $rest_url->email,
                                                    "address" => $rest_url->rest_address,
                                                    "Phone" => $rest_url->phone,
                                                    "Fax" => $rest_url->fax,
                                                    "deliver_charges" => $rest_url->delivery_charges,
                                                    "min_total" => $rest_url->order_minimum,
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
                                                    "menu" => getMenus($rest_url->id)
                                                );
                                        }
                                    }
                                    else
                                    {
                                        if ($mRecordCount<$mMaxResults)
                                        {
                                            $mRecordCount = $mRecordCount + 1;
                                            $mReturn[] = array(
                                                    "name" => $rest_url->name,
                                                    "slug" => $rest_url->url_name,
                                                    "email" => $rest_url->email,
                                                    "address" => $rest_url->rest_address,
                                                    "Phone" => $rest_url->phone,
                                                    "Fax" => $rest_url->fax,
                                                    "deliver_charges" => $rest_url->delivery_charges,
                                                    "min_total" => $rest_url->order_minimum,
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
                                                    "satisfaction" => $mLiked,
                                                    "satisfactionPercentage" => $mLikePercentage,
                                                    "favorite" => $mFavorite,
                                                    "menu" => getMenus($rest_url->id)
                                                );
                                        }
                                    }
                                }
                                else
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                    "slug" => $rest_url->url_name
                                                );
                                    }
                                }
                            }
                        }
                        else
                        {
                            if (isset($_REQUEST["detail"]))
                            {
                                if ($mUserID==0)
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                            "name" => $rest_url->name,
                                            "slug" => $rest_url->url_name,
                                            "email" => $rest_url->email,
                                            "address" => $rest_url->rest_address,
                                            "Phone" => $rest_url->phone,
                                            "Fax" => $rest_url->fax,
                                            "deliver_charges" => $rest_url->delivery_charges,
                                            "min_total" => $rest_url->order_minimum,
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
                                            "menu" => getMenus($rest_url->id)
                                        );
                                    }
                                }
                                else
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                "name" => $rest_url->name,
                                                "slug" => $rest_url->url_name,
                                                "email" => $rest_url->email,
                                                "address" => $rest_url->rest_address,
                                                "Phone" => $rest_url->phone,
                                                "Fax" => $rest_url->fax,
                                                "deliver_charges" => $rest_url->delivery_charges,
                                                "min_total" => $rest_url->order_minimum,
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
                                                "satisfaction" => $mLiked,
                                                "satisfactionPercentage" => $mLikePercentage,
                                                "favorite" => $mFavorite,
                                                "menu" => getMenus($rest_url->id)
                                            );
                                    }
                                }
                            }
                            else
                            {
                                if ($mRecordCount<$mMaxResults)
                                {
                                    $mRecordCount = $mRecordCount + 1;
                                    $mReturn[] = array(
                                                "slug" => $rest_url->url_name
                                            );
                                }
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
                $lat_lon = mysql_fetch_array(mysql_query($qry));
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
                        mysql_query("UPDATE rest_langitude_latitude SET rest_latitude='".$lat2."', rest_longitude='".$lon2."' WHERE rest_id = ".$rest_url->id);
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
                        if (isset($_REQUEST["open"]))
                        {
                            if ($mOpen == "y")
                            {
                                if (isset($_REQUEST["detail"]))
                                {
                                    if ($mUserID==0)
                                    {
                                        if ($mRecordCount<$mMaxResults)
                                        {
                                            $mRecordCount = $mRecordCount + 1;
                                            $mReturn[] = array(
                                                    "name" => $rest_url->name,
                                                    "slug" => $rest_url->url_name,
                                                    "email" => $rest_url->email,
                                                    "address" => $rest_url->rest_address,
                                                    "Phone" => $rest_url->phone,
                                                    "Fax" => $rest_url->fax,
                                                    "deliver_charges" => $rest_url->delivery_charges,
                                                    "min_total" => $rest_url->order_minimum,
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
                                                    "menu" => getMenus($rest_url->id)
                                                );
                                        }
                                    }
                                    else
                                    {
                                        if ($mRecordCount<$mMaxResults)
                                        {
                                            $mRecordCount = $mRecordCount + 1;
                                            $mReturn[] = array(
                                                    "name" => $rest_url->name,
                                                    "slug" => $rest_url->url_name,
                                                    "email" => $rest_url->email,
                                                    "address" => $rest_url->rest_address,
                                                    "Phone" => $rest_url->phone,
                                                    "Fax" => $rest_url->fax,
                                                    "deliver_charges" => $rest_url->delivery_charges,
                                                    "min_total" => $rest_url->order_minimum,
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
                                                    "satisfaction" => $mLiked,
                                                    "satisfactionPercentage" => $mLikePercentage,
                                                    "favorite" => $mFavorite,
                                                    "menu" => getMenus($rest_url->id)
                                                );
                                        }
                                    }
                                }
                                else
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                    "slug" => $rest_url->url_name
                                                );
                                    }
                                }
                            }
                        }
                        else
                        {
                            if (isset($_REQUEST["detail"]))
                            {
                                if ($mUserID==0)
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                "name" => $rest_url->name,
                                                "slug" => $rest_url->url_name,
                                                "email" => $rest_url->email,
                                                "address" => $rest_url->rest_address,
                                                "Phone" => $rest_url->phone,
                                                "Fax" => $rest_url->fax,
                                                "deliver_charges" => $rest_url->delivery_charges,
                                                "min_total" => $rest_url->order_minimum,
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
                                                "menu" => getMenus($rest_url->id)
                                            );
                                    }
                                }
                                else
                                {
                                    if ($mRecordCount<$mMaxResults)
                                    {
                                        $mRecordCount = $mRecordCount + 1;
                                        $mReturn[] = array(
                                                "name" => $rest_url->name,
                                                "slug" => $rest_url->url_name,
                                                "email" => $rest_url->email,
                                                "address" => $rest_url->rest_address,
                                                "Phone" => $rest_url->phone,
                                                "Fax" => $rest_url->fax,
                                                "deliver_charges" => $rest_url->delivery_charges,
                                                "min_total" => $rest_url->order_minimum,
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
                                                "satisfaction" => $mLiked,
                                                "favorite" => $mFavorite,
                                                "menu" => getMenus($rest_url->id)
                                            );
                                    }
                                }
                            }
                            else
                            {
                                if ($mRecordCount<$mMaxResults)
                                {
                                    $mRecordCount = $mRecordCount + 1;
                                    $mReturn[] = array(
                                                "slug" => $rest_url->url_name
                                            );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    else if (isset($_REQUEST["distance"]))
    {
        $mLat = $pLat;
        $mLong = $pLong;
        
        $mSourceLatLong = array();
        $mDestinationLatLong = array();

        $mSourceLatLong = array($pLat, $pLong);

        $mSQLLatLong = "SELECT rest_latitude, rest_longitude FROM rest_langitude_latitude WHERE rest_id=".$rest_url->id;
        $mResLatLong  = mysql_query($mSQLLatLong);
        if (mysql_num_rows($mResLatLong)>0)
        {
            $mRowLatLong = mysql_fetch_object($mResLatLong);
            $mDestinationLatLong = array($mRowLatLong->rest_latitude, $mRowLatLong->rest_longitude);
        }

        if (count($mDestinationLatLong)==0)
        {
            $mRestaurantAddress = $rest_url->rest_address." ".$rest_url->rest_city.", ".$rest_url->rest_state." ".$rest_url->rest_zip;
            $mDestinationLatLong = getLatLong($mRestaurantAddress);
            mysql_query("UPDATE rest_langitude_latitude SET rest_latitude='".$mDestinationLatLong[0]."', rest_longitude='".$mDestinationLatLong[1]."' WHERE rest_id = ".$rest_url->id);
        }

        $mDistance = getDistance($mSourceLatLong, $mDestinationLatLong);

        if ($mDistance<=$_GET["distance"])
        {
            if (isset($_REQUEST["open"]))
            {
                if ($mOpen == "y")
                {
                    if (isset($_REQUEST["detail"]))
                    {
                        if ($mUserID==0)
                        {
                            if ($mRecordCount<$mMaxResults)
                            {
                                $mRecordCount = $mRecordCount + 1;
                                $mReturn[] = array(
                                        "name" => $rest_url->name,
                                        "slug" => $rest_url->url_name,
                                        "email" => $rest_url->email,
                                        "address" => $rest_url->rest_address,
                                        "Phone" => $rest_url->phone,
                                        "Fax" => $rest_url->fax,
                                        "deliver_charges" => $rest_url->delivery_charges,
                                        "min_total" => $rest_url->order_minimum,
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
                                        "menu" => getMenus($rest_url->id)
                                    );
                            }
                        }
                        else
                        {
                            if ($mRecordCount<$mMaxResults)
                            {
                                $mRecordCount = $mRecordCount + 1;
                                $mReturn[] = array(
                                        "name" => $rest_url->name,
                                        "slug" => $rest_url->url_name,
                                        "email" => $rest_url->email,
                                        "address" => $rest_url->rest_address,
                                        "Phone" => $rest_url->phone,
                                        "Fax" => $rest_url->fax,
                                        "deliver_charges" => $rest_url->delivery_charges,
                                        "min_total" => $rest_url->order_minimum,
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
                                        "satisfaction" => $mLiked,
                                        "satisfactionPercentage" => $mLikePercentage,
                                        "favorite" => $mFavorite,
                                        "menu" => getMenus($rest_url->id)
                                    );
                            }
                        }
                    }
                    else
                    {
                        if ($mRecordCount<$mMaxResults)
                        {
                            $mRecordCount = $mRecordCount + 1;
                            $mReturn[] = array(
                                        "slug" => $rest_url->url_name
                                    );
                        }
                    }
                }
            }
            else
            {
                if (isset($_REQUEST["detail"]))
                {
                    if ($mUserID==0)
                    {
                        if ($mRecordCount<$mMaxResults)
                        {
                            $mRecordCount = $mRecordCount + 1;
                            $mReturn[] = array(
                                    "name" => $rest_url->name,
                                    "slug" => $rest_url->url_name,
                                    "email" => $rest_url->email,
                                    "address" => $rest_url->rest_address,
                                    "Phone" => $rest_url->phone,
                                    "Fax" => $rest_url->fax,
                                    "deliver_charges" => $rest_url->delivery_charges,
                                    "min_total" => $rest_url->order_minimum,
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
                                    "menu" => getMenus($rest_url->id)
                                );
                        }
                    }
                    else
                    {
                        if ($mRecordCount<$mMaxResults)
                        {
                            $mRecordCount = $mRecordCount + 1;
                            $mReturn[] = array(
                                    "name" => $rest_url->name,
                                    "slug" => $rest_url->url_name,
                                    "email" => $rest_url->email,
                                    "address" => $rest_url->rest_address,
                                    "Phone" => $rest_url->phone,
                                    "Fax" => $rest_url->fax,
                                    "deliver_charges" => $rest_url->delivery_charges,
                                    "min_total" => $rest_url->order_minimum,
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
                                    "satisfaction" => $mLiked,
                                    "satisfactionPercentage" => $mLikePercentage,
                                    "favorite" => $mFavorite,
                                    "menu" => getMenus($rest_url->id)
                                );
                        }
                    }
                }
                else
                {
                    if ($mRecordCount<$mMaxResults)
                    {
                        $mRecordCount = $mRecordCount + 1;
                        $mReturn[] = array(
                                    "slug" => $rest_url->url_name
                                );
                    }
                }
            }
        }
    }
    else
    {
        if (isset($_REQUEST["open"]))
        {
            if ($mOpen == "y")
            {
                if (isset($_REQUEST["detail"]))
                {
                    if ($mUserID==0)
                    {
                        if ($mRecordCount<$mMaxResults)
                        {
                            $mRecordCount = $mRecordCount + 1;
                            $mReturn[] = array(
                                "name" => $rest_url->name,
                                "slug" => $rest_url->url_name,
                                "email" => $rest_url->email,
                                "address" => $rest_url->rest_address,
                                "Phone" => $rest_url->phone,
                                "Fax" => $rest_url->fax,
                                "deliver_charges" => $rest_url->delivery_charges,
                                "min_total" => $rest_url->order_minimum,
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
                                "menu" => getMenus($rest_url->id)
                            );
                        }
                    }
                    else
                    {
                        if ($mRecordCount<$mMaxResults)
                        {
                            $mRecordCount = $mRecordCount + 1;
                            $mReturn[] = array(
                                "name" => $rest_url->name,
                                "slug" => $rest_url->url_name,
                                "email" => $rest_url->email,
                                "address" => $rest_url->rest_address,
                                "Phone" => $rest_url->phone,
                                "Fax" => $rest_url->fax,
                                "deliver_charges" => $rest_url->delivery_charges,
                                "min_total" => $rest_url->order_minimum,
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
                                "satisfaction" => $mLiked,
                                "satisfactionPercentage" => $mLikePercentage,
                                "favorite" => $mFavorite,
                                "menu" => getMenus($rest_url->id)
                            );
                        }
                    }
                }
                else
                {
                    if ($mRecordCount<$mMaxResults)
                    {
                        $mRecordCount = $mRecordCount + 1;
                        $mReturn[] = array(
                                    "slug" => $rest_url->url_name
                                );
                    }
                }
            }
        }
        else
        {
            if (isset($_REQUEST["detail"]))
            {
                if ($mUserID==0)
                {
                    if ($mRecordCount<$mMaxResults)
                    {
                        $mRecordCount = $mRecordCount + 1;
                        $mReturn[] = array(
                            "name" => $rest_url->name,
                            "slug" => $rest_url->url_name,
                            "email" => $rest_url->email,
                            "address" => $rest_url->rest_address,
                            "Phone" => $rest_url->phone,
                            "Fax" => $rest_url->fax,
                            "deliver_charges" => $rest_url->delivery_charges,
                            "min_total" => $rest_url->order_minimum,
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
                            "menu" => getMenus($rest_url->id)
                        );
                    }
                }
                else
                {
                    if ($mRecordCount<$mMaxResults)
                    {
                        $mRecordCount = $mRecordCount + 1;
                        $mReturn[] = array(
                            "name" => $rest_url->name,
                            "slug" => $rest_url->url_name,
                            "email" => $rest_url->email,
                            "address" => $rest_url->rest_address,
                            "Phone" => $rest_url->phone,
                            "Fax" => $rest_url->fax,
                            "deliver_charges" => $rest_url->delivery_charges,
                            "min_total" => $rest_url->order_minimum,
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
                            "satisfaction" => $mLiked,
                            "satisfactionPercentage" => $mLikePercentage,
                            "favorite" => $mFavorite,
                            "menu" => getMenus($rest_url->id)
                        );
                    }
                }
            }
            else
            {
                if ($mRecordCount<$mMaxResults)
                {
                    $mRecordCount = $mRecordCount + 1;
                    $mReturn[] = array(
                                "slug" => $rest_url->url_name
                            );
                }
            }
        }
    }
}


function verifyRequest()
{
    if (isset($_REQUEST["sso"]))
    {
        if ($_REQUEST["sso"]!=session_id())
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
    $qry= mysql_query($mSQL);
    while($day=mysql_fetch_object($qry))
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

        /*if ((strrpos($day->open,"-") === FALSE) && (strrpos($day->close,"-") === FALSE))
        {
            $day->hours .= " ".date("g:i A",strtotime($day->open))." - ".date("g:i A",strtotime($day->close));
        }
        else if ((strrpos($day->open,"-") !== FALSE) && (strrpos($day->close,"-") !== FALSE))
        {
            $day->hours .= " Closed";
        }
        else if (strrpos($day->open,"-") !== FALSE)
        {
            $day->open = "0000";
            $day->hours .= " ".date("g:i A",strtotime($day->open))." - ".date("g:i A",strtotime($day->close));
        }
        else if (strrpos($day->close,"-") !== FALSE)
        {
            $day->close = "2359";
            $day->hours .= " ".date("g:i A",strtotime($day->open))." - ".date("g:i A",strtotime($day->close));
        }*/
        
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
    $mUrl = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$pAddress.'&sensor=false&key=AIzaSyBOYImEs38uinA8zuHZo-Q9VnKAW3dSrgo';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $mUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $mGeoLoc = curl_exec($ch);

    $json = json_decode($mGeoLoc);
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
    $lat_lon = mysql_fetch_array(mysql_query($qry));

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
    $mResMenu = mysql_query($mSQLMenu);
    while ($mRowMenu = mysql_fetch_object($mResMenu))
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
    $mResCat = mysql_query("SELECT * FROM categories WHERE menu_id=".$pMenuID." AND status=1 ORDER BY cat_ordering");
    while ($mRowCat = mysql_fetch_object($mResCat))
    {
        $arr_categories[] = array(
                        "category" => $mRowCat->cat_name,
                        "category_subdescriptions" => $mRowCat->cat_des,
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
    $mResProduct = mysql_query("SELECT * FROM product WHERE sub_cat_id = ".$pCategoryID." AND status = 1 ORDER BY SortOrder");
    while ($mRowProduct = mysql_fetch_object($mResProduct))
    {
        $arr_products[] = array(
                        "name" => $mRowProduct->item_title,
                        "details" => $mRowProduct->item_des,
                        "price" => $mRowProduct->retail_price,
                        "image_url" => (!empty($mRowProduct->item_image)?$SiteUrl."images/item_images/".$mRowProduct->item_image:""),
                        "posID" => $mRowProduct->pos_id,
                        "active" => "true",
                        "boarshead_item" => (strpos($mRowProduct->item_type, "B")!==FALSE?"true":"false")
                    );
    }
    return $arr_products;
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