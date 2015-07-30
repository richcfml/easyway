<?php
require_once("../includes/config.php");
include("../includes/class.phpmailer.php");
include("../includes/function.php");

require_once 'google-api/Google_Client.php';
require_once 'google-api/contrib/Google_AnalyticsService.php';
require_once 'refresh_token.php';

$client = new Google_Client();
$client->setApplicationName("EasyWayOrdering INC");

$client_id = '668969395261-lpo61q1sjfm2fr4ihoe9d151jvmdpupi.apps.googleusercontent.com';
$client_secret = 'ZEaTFWyxGwTJ8di1TqJslbkQ';
$redirect_uri = urlencode($SiteUrl.'c_panel/get_last_month_analytics_from_google.php');
$scope = urlencode('https://www.googleapis.com/auth/analytics.readonly');
$access_type = 'offline';

$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->setScopes(array($scope));
$client->setAccessType($access_type);

$client->refreshToken($refresh_token);

if($client->isAccessTokenExpired()) {
	$client->authenticate();
    $new_token = json_decode($client->getAccessToken());
    $client->refreshToken($new_token->refresh_token);
	
	file_put_contents("refresh_token.php", '<? $refresh_token = "' . $new_token->refresh_token . '"; ?>');
	
	$to      = 'aliraza@qualityclix.com';
	$subject = 'Refresh Token Expired';
	$message = 'Refresh Token Expired - New token fetched @ ' . date("F j, Y, g:i a") . '. Now token is: ' . $new_token->refresh_token;
	$testmail=new testmail();
	$testmail->sendTo($message, $subject, $to, true);
}

$client->setUseObjects(true);

if($client->getAccessToken()) {
	$service = new Google_AnalyticsService($client);
	
	//echo "<pre>";
	
	$profile_id = urldecode("ga:72879926");
	$start_date = date('Y-m-d', strtotime('-30 days'));
	$end_date = date('Y-m-d');
	
	$service = new Google_AnalyticsService($client);
	$profile_id = urldecode("ga:72879926");
	$start_date = date('Y-m-d', strtotime('-30 days'));
	$end_date = date('Y-m-d');
	
	$analytics = dbAbstract::Execute("SELECT r.id, r.url_name FROM `resturants` r, `analytics` a WHERE r.id = a.resturant_id ORDER BY a.dated LIMIT 10",1);
	
        if(!empty($analytics) && (dbAbstract::returnRowsCount($analytics,1) > 0)) {
		while($row = dbAbstract::returnAssoc($analytics,1)) {
			//get total views of a resturant
			$resturant_slug = str_replace(',', '', $row["url_name"]);
			//$resturant_slug = "slice_pizza";
                        
			$data = array();
			
			// get total visits by a mobile and desktop and average time on site, bounce rate
			$response = $service->data_ga->get(
						$profile_id,
						$start_date,
						$end_date,
						'ga:visits, ga:avgTimeOnPage, ga:entranceBounceRate',
						array(
							'dimensions' => "ga:isMobile",
							'filters' => "ga:pagePath==@$resturant_slug"
						)
					);
			if(!empty($response->rows)) {
				$data["desktop_traffic_count"] = (isset($response->rows[0][1]) ?  $response->rows[0][1] : 0);
				$data["mobile_traffic_count"] = (isset($response->rows[1][1]) ?  $response->rows[1][1] : 0);
				$data["total_visits"] = $response->totalsForAllResults["ga:visits"];
				$data["average_time_on_site"] = intval($response->totalsForAllResults["ga:avgTimeOnPage"]);
				$data["bounce_rate"] = intval($response->totalsForAllResults["ga:entranceBounceRate"]);
			}
			
			// get total visits last but second month of a resturant
			$response = $service->data_ga->get(
						$profile_id,
						date('Y-m-d', strtotime('-60 days')),
						date('Y-m-d', strtotime('-30 days')),
						'ga:visits',
						array(
							'filters' => "ga:pagePath==@$resturant_slug"
						)
					);
			if(!empty($response->totalsForAllResults)) {
				$data["total_visits_second_last_month"] = $response->totalsForAllResults["ga:visits"];
			}
			
			// get total visits by a referrals and referrals urls
			$response = $service->data_ga->get(
						$profile_id,
						$start_date,
						$end_date,
						'ga:visits',
						array(
							'dimensions' => "ga:source",
							'filters' => "ga:pagePath==@$resturant_slug"
						)
					);
			if(!empty($response->rows)) {
				$data["referring_traffic_sources"] = addslashes(json_encode($response->rows));
			}
			
			// get refering keywords 
			$response = $service->data_ga->get(
						$profile_id,
						$start_date,
						$end_date,
						'ga:visits',
						array(
							'dimensions' => "ga:keyword",
							'filters' => "ga:pagePath==@$resturant_slug"
						)
					);
			if(!empty($response->rows)) {
				$data["referring_keywords_by_search_engine"] = addslashes(json_encode($response->rows));
			}
			
			// get social media referrals count and names
			$response = $service->data_ga->get(
						$profile_id,
						$start_date,
						$end_date,
						'ga:visits',
						array(
							'dimensions' => "ga:hasSocialSourceReferral, ga:socialNetwork",
							'filters' => "ga:pagePath==@$resturant_slug"
						)
					);
			if(!empty($response->rows)) {
				$data["social_media_referrals"] = addslashes(json_encode($response->rows));
			}
			
			if(!empty($data)) {
				$qry = "";
				foreach($data as $key => $val) {
					$qry .= "`" . $key . "`='" . $val . "', ";
				}
				$qry .= "dated=NOW()";
				
				dbAbstract::Update(
					"UPDATE `analytics` 
					SET $qry
					WHERE resturant_id=".  $row["id"],1
				);
				
				echo $resturant_slug . "<br>";
				echo "<pre>";print_r($data);echo "</pre>";
			} else {
				dbAbstract::Update(
					"UPDATE `analytics` 
					SET dated=NOW()
					WHERE resturant_id=".  $row["id"],1);
				echo "No data found for: " . $resturant_slug . "<br>";
			}
		}
		$to      = 'ashernedian02@hotmail.com';
		$subject = 'EasyWay - Analytics extracted from google';
		$message = 'EasyWay - Analytics extracted from google @ ' . date("F j, Y, g:i a");

		$testmail=new testmail();
		$testmail->sendTo($message, $subject, $to, true);
	}
}
mysqli_close($mysqli);
exit(0);

?>