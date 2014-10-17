<?

// get all markers within the bounds
SELECT * FROM tilistings WHERE lat BETWEEN a AND < c AND lng between b AND  d



function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    $latd = deg2rad($lat2 - $lat1);
    $lond = deg2rad($lon2 - $lon1);
    $a = sin($latd / 2) * sin($latd / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($lond / 2) * sin($lond / 2);
         $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return 6371.0 * $c;
}

define('OFFSET', 268435456);
define('RADIUS', 85445659.4471); /* $offset / pi() */
    
function lonToX($lon) {
    return round(OFFSET + RADIUS * $lon * pi() / 180);        
}

function latToY($lat) {
    return round(OFFSET - RADIUS * 
                log((1 + sin($lat * pi() / 180)) / 
                (1 - sin($lat * pi() / 180))) / 2);
}

function pixelDistance($lat1, $lon1, $lat2, $lon2, $zoom) {
    $x1 = lonToX($lon1);
    $y1 = latToY($lat1);

    $x2 = lonToX($lon2);
    $y2 = latToY($lat2);
        
    return sqrt(pow(($x1-$x2),2) + pow(($y1-$y2),2)) >> (21 - $zoom);
}

function cluster($markers, $distance, $zoom) {
    $clustered = array();
    /* Loop until all markers have been compared. */
    while (count($markers)) {
        $marker  = array_pop($markers);
        $cluster = array();
        /* Compare against all markers which are left. */
        foreach ($markers as $key => $target) {
            $pixels = pixelDistance($marker['lat'], $marker['lon'],
                                    $target['lat'], $target['lon'],
                                    $zoom);
            /* If two markers are closer than given distance remove */
            /* target marker from array and add it to cluster.      */
            if ($distance > $pixels) {
                printf("Distance between %s,%s and %s,%s is %d pixels.\n", 
                    $marker['lat'], $marker['lon'],
                    $target['lat'], $target['lon'],
                    $pixels);
                unset($markers[$key]);
                $cluster[] = $target;
            }
        }

        /* If a marker has been added to cluster, add also the one  */
        /* we were comparing to and remove the original from array. */
        if (count($cluster) > 0) {
            $cluster[] = $marker;
            $clustered[] = $cluster;
        } else {
            $clustered[] = $marker;
        }
    }
    return $clustered;
}


$markers   = array();
$markers[] = array('id' => 'marker_1', 
                   'lat' => 59.441193, 'lon' => 24.729494);
$markers[] = array('id' => 'marker_2', 
                   'lat' => 59.432365, 'lon' => 24.742992);
$markers[] = array('id' => 'marker_3', 
                   'lat' => 59.431602, 'lon' => 24.757563);
$markers[] = array('id' => 'marker_4', 
                   'lat' => 59.437843, 'lon' => 24.765759);
$markers[] = array('id' => 'marker_5', 
                   'lat' => 59.439644, 'lon' => 24.779041);
$markers[] = array('id' => 'marker_6', 
                   'lat' => 59.434776, 'lon' => 24.756681);

$clustered = cluster($markers, 20, 11);

print_r($clustered);















//==================================================
/* Calculate average lat and lon of markers. */
function calculateCenter($markers) {
	$lat_sum = $lon_sum = 0;
	foreach ($markers as $marker) {
		$lat_sum += $marker["lat"];
		$lon_sum += $marker["lon"];
	}
	return array($lat_sum / count($markers), $lon_sum / count($markers));
}
  ?>
  
  
  
  live example: bounds, fitbound etc http://www.pittss.lv/jquery/gomap/examples/bounds.php
  
Creating a Store Locator with PHP, MySQL & Google Maps: https://developers.google.com/maps/articles/phpsqlsearch_v3

Introduction to Marker Clustering With Google Maps: http://www.appelsiini.net/2008/11/introduction-to-marker-clustering-with-google-maps

google-maps-server-side-clustering-dotnet: http://code.google.com/p/google-maps-server-side-clustering-dotnet/source/browse/trunk/GoogleMapsClustering/Kunukn.GooglemapsClustering.Clustering/Algorithm/ClusterAlgorithmBase.cs?r=182

 galen / PHPGoogleMaps-Examples: https://github.com/galen/PHPGoogleMaps-Examples/blob/master/markers_clustering.php
 
 serverside clustering google maps markers: http://stackoverflow.com/questions/4862978/serverside-clustering-google-maps-markers
 
 Map Clustering Algorithm: http://stackoverflow.com/questions/1434222/map-clustering-algorithm
 
  tuupola / php_google_maps: https://github.com/tuupola/php_google_maps/blob/master/Google/Maps/Bounds.php
  
  