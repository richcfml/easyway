
<?php

include("../includes/config.php");
include("../includes/function.php");
$objres = new getresturantdetails();
$objres->isDelivered();
$objres->isOpen();
//$objres->savelatitude();
class getresturantdetails {

    public function isDelivered() {
               $rest_url = array();
        $arr_restId = array();
        $arr_restslugs = array();
        $deliver_charges = array();
        $min_total = array();

        $day_name = date('l');
        if ($day_name == 'Monday') {
            $day_of_week = 0;
        } else if ($day_name == 'Tuesday') {
            $day_of_week = 1;
        } else if ($day_name == 'Wednesday') {
            $day_of_week = 2;
        } else if ($day_name == 'Thursday') {
            $day_of_week = 3;
        } else if ($day_name == 'Friday') {
            $day_of_week = 4;
        } else if ($day_name == 'Saturday') {
            $day_of_week = 5;
        } else if ($day_name == 'Sunday') {
            $day_of_week = 6;
        }
        $activeres = mysql_query("SELECT id,time_zone_id FROM resturants WHERE rest_open_close = 1  And delivery_offer = 1");
        while ($rest_id = mysql_fetch_object($activeres)) {
            $businessHrQry = mysql_query("SELECT open, close FROM business_hours WHERE day = $day_of_week AND rest_id = " . $rest_id->id . "");
            $business_hours = mysql_fetch_object($businessHrQry);
            $timezoneQry = mysql_query("SELECT  time_zone FROM times_zones WHERE id = ".$rest_id->time_zone_id );
		@$timezoneRs = mysql_fetch_array($timezoneQry);
                date_default_timezone_set($timezoneRs['time_zone']);

            $current_time = date("Hi", time());

            if ($current_time >= $business_hours->open && $current_time <= $business_hours->close) {
                $rest_url[] = mysql_fetch_array(mysql_query("SELECT * from resturants WHERE id  = " . $rest_id->id . ""));
            }
        }
       //echo "<pre>";print_r($rest_url);exit;
         $street = $_GET['street'];
        $city = $_GET['city'];
        $state = $_GET['State'];
        $addresslink = str_replace(' ', '+', $street . " " . $city . " " . $state);
        $result = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $addresslink . '&sensor=false');
        $arr = array();
        $array = (json_decode($result, true));
        if (!empty($array['results'][0]['geometry']['location']['lat'])) {
            foreach ($rest_url as $rest_arr) {
                //echo "<pre>";print_r($rest_arr);exit;
                $lat = $array['results'][0]['geometry']['location']['lat'];
                $long = $array['results'][0]['geometry']['location']['lng'];

                $qry = "Select * from resturants where id = " . $rest_arr['id'] . "";
                $vertices = mysql_fetch_array(mysql_query($qry));
                if ($vertices['delivery_option'] == 'delivery_zones') {
                    if (!empty($vertices['zone1_coordinates'])) {
                        $zone1_coordinates = explode('~', $vertices['zone1_coordinates']);
                    } else {
                        $zone1_coordinates = $this->getCoordinates('0.02', $rest_arr['id']);
                    }
                    if (!empty($vertices['zone2_coordinates'])) {
                        $zone2_coordinates = explode('~', $vertices['zone2_coordinates']);
                    } else {
                        $zone2_coordinates = $this->getCoordinates('0.025', $rest_arr['id']);
                    }
                    if (!empty($vertices['zone3_coordinates'])) {
                        $zone3_coordinates = explode('~', $vertices['zone3_coordinates']);
                        //echo "</br>";echo "<pre>";print_r($zone1_coordinates);
                    } else {
                        $zone3_coordinates = $this->getCoordinates('0.03', $rest_arr['id']);
                        // echo "</br>";echo "<pre>";print_r($zone1_coordinates);
                    }
                    $x = $lat;
                    $y = $long;
                    if (!empty($zone1_coordinates)) {
                        if ($vertices['zone1'] && $this->pointInPolygon($x, $y, $zone1_coordinates)) {

                            $arr_restId[] = $rest_arr['id'];
                            $arr_restslugs[] = $rest_arr['url_name'];
                            $deliver_charges[] = $rest_arr['zone1_delivery_charges'];
                            $min_total[] = $rest_arr['zone1_min_total'];
                            //echo "Is in polygon Zone1!";
                        } else if ($vertices['zone2'] && $this->pointInPolygon($x, $y, $zone2_coordinates)) {
                            $arr_restId[] = $rest_arr['id'];
                            $arr_restslugs[] = $rest_arr['url_name'];
                            $deliver_charges[] = $rest_arr['zone2_delivery_charges'];
                            $min_total[] = $rest_arr['zone2_min_total'];
                            //echo "Is in polygon Zone2!";
                        } else if ($vertices['zone3'] && $this->pointInPolygon($x, $y, $zone3_coordinates)) {
                            $arr_restId[] = $rest_arr['id'];
                            $arr_restslugs[] = $rest_arr['url_name'];
                            $deliver_charges[]  = $rest_arr['zone3_delivery_charges'];
                            $min_total[] = $rest_arr['zone3_min_total'];
                            //echo "Is in polygon Zone3!";
                        }
                    }
                } else {
                    $qry = "Select * from rest_langitude_latitude where rest_id = " . $rest_arr['id'] . "";
                    $lat_lon = mysql_fetch_array(mysql_query($qry));
                    if (!empty($lat_lon)) {
                        $lon2 = $lat_lon['rest_longitude'];
                        $lat2 = $lat_lon['rest_latitude'];

                        $theta = $long - $lon2;
                        $dist = sin(deg2rad(floatval($lat))) * sin(deg2rad(floatval($lat2))) + cos(deg2rad(floatval($lat))) * cos(deg2rad(floatval($lat2))) * cos(deg2rad(floatval($theta)));
                        $dist = acos($dist);
                        $dist = rad2deg($dist);
                        $miles = $dist * 60 * 1.1515;
                        $radius = $rest_arr['delivery_radius'];
                        if ($miles < $radius) {
                            $arr_restId[] = $rest_arr['id'];
                            $arr_restslugs[] = $rest_arr['url_name'];
                            $deliver_charges[] = $rest_arr['delivery_charges'];
                            $min_total[] = $rest_arr['order_minimum'];

                        }
                    }
                }
            }
            echo "<pre>";
            print_r($arr_restId);
            print_r($arr_restslugs);
            print_r($deliver_charges);
            print_r($arr_restslugs);
            print_r($min_total);
        }
    }

    function pointInPolygon($x, $y, $coordinates) {

        foreach ($coordinates as $arr1) {
            list($polyX[], $polyY[]) = explode(',', $arr1);
        }
        $polySides = count($polyX);
        $j = $polySides - 1;
        $oddNodes = 0;
        for ($i = 0; $i < $polySides; $i++) {
            if ($polyY[$i] < $y && $polyY[$j] >= $y || $polyY[$j] < $y && $polyY[$i] >= $y) {
                if ($polyX[$i] + ($y - $polyY[$i]) / ($polyY[$j] - $polyY[$i]) * ($polyX[$j] - $polyX[$i]) < $x) {
                    $oddNodes = !$oddNodes;
                }
            }
            $j = $i;
        }

        return $oddNodes;
    }

    function getCoordinates($radius, $rest_id) {
        $coordinates = array();
        $qry = "Select * from rest_langitude_latitude where rest_id = " . $rest_id . "";
        $lat_lon = mysql_fetch_array(mysql_query($qry));

        if (!empty($lat_lon)) {
            $lon2 = $lat_lon['rest_longitude'];
            $lat2 = $lat_lon['rest_latitude'];
            for ($i = 0; $i < 12; $i++) {
                $x = ($lat2 + $radius * cos(2 * PI() * $i / 12));
                $y = ($lon2 + $radius * sin(2 * PI() * $i / 12));
                $coordinates[] = $x . "," . $y;
            }
            return $coordinates;
        }
    }

        public function isOpen() {
			$this->isOpenHour=0;
                        $arr_resturl=array();
			$day_name=date('l');
			  if($day_name == 'Monday') {
					  $day_of_week = 0;
			   } else if($day_name == 'Tuesday') {
					  $day_of_week = 1;
			   } else if($day_name == 'Wednesday') {
					  $day_of_week = 2;
			   } else if($day_name == 'Thursday') {
					  $day_of_week = 3;
			   } else if($day_name == 'Friday') {
					  $day_of_week = 4;
			   } else if($day_name == 'Saturday') {
					  $day_of_week = 5;
			   } else if($day_name == 'Sunday') {
					  $day_of_week = 6;
			   }
                         $activeres =  mysql_query("SELECT id,time_zone_id FROM resturants WHERE rest_open_close = 1");
                         while($rest_id = mysql_fetch_object($activeres)){
                         $businessHrQry =  mysql_query("SELECT open, close FROM business_hours WHERE day = $day_of_week AND rest_id = ". $rest_id->id ."");
                         $business_hours=mysql_fetch_object($businessHrQry);
                           $timezoneQry = mysql_query("SELECT  time_zone FROM times_zones WHERE id = ".$rest_id->time_zone_id );
                         @$timezoneRs = mysql_fetch_array($timezoneQry);
                         date_default_timezone_set($timezoneRs['time_zone']);
                         $current_time=date("Hi",time());
			 if($current_time >= $business_hours->open && $current_time <= $business_hours->close) {
                             $rest_url =  mysql_fetch_object(mysql_query("SELECT url_name from resturants WHERE id  = ". $rest_id->id .""));
                             $arr_resturl[]=$rest_url->url_name;


                        }
                        }
                        echo "<pre>"; print_r($arr_resturl);
		}

                 

}
?>


