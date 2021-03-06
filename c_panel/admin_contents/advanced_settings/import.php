<?php

require_once("../../../includes/config.php");
if(isset($_GET['count']))
{
    $likeQry = dbAbstract::Execute("SELECT
         bh.rest_id,
        SUM(bh.Rating = '0') AS `dislike`,
        SUM(bh.Rating = '1') AS `like`,
        r.name
    FROM bh_rest_rating bh inner join resturants r on r.id = bh.rest_id where r.bh_restaurant = 1
    GROUP BY rest_id");

    $out = 'Name,Like,Dislike,Satisfaction Percentage';
    $out .="\n";
    while($likeArray = dbAbstract::returnArray($likeQry)){
        $mLikePercentage = 0;
        if (($likeArray['like']==0) && ($likeArray['dislike']==0))
        {
              $mLikePercentage = 0;
        }
        else
        {
              $mLikePercentage = round(($likeArray['like']/($likeArray['like'] + $likeArray['dislike']))*100);
        }
        $out .= $likeArray['name'] . "," . $likeArray['like'] . "," . $likeArray['dislike'] . "," . $mLikePercentage;
        $out .="\n";
    }

    // Output to browser with appropriate mime type, you choose ;)
    header("Content-type: text/x-xlsx");
    //header("Content-type: text/csv");
    //header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=report.csv");
    echo $out;
    exit;
}
else if(isset($_GET['import']))
{
    
$result = dbAbstract::Execute("SELECT r.url_name as RestaurantSlug,dl.Reason, dl.Comments,date(dl.CreateDate) as `Date` FROM bh_rest_rating bh inner join resturants r on r.id = bh.rest_id left join bh_dislike dl on dl.bh_rest_rating_id = bh.id where r.bh_restaurant = 1");

    ob_end_clean();
// I assume you already have your $result

// Fetch MySQL result headers
$headers = array();

$headers = array();
$headers[] = "No.";
$headers[] = "RESTAURANT SLUG";
$headers[] = "REASON";
$headers[] = "COMMENTS";
$headers[] = "Date";
// Filename with current date
$current_date = date("y/m/d");
$filename = "customerfeedback.csv";

// Open php output stream and write headers
$fp = fopen('php://output', 'w');
if ($fp && $result) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    header('Pragma: no-cache');
    header('Expires: 0');
    echo "Customer Feedback\n\n";
    // Write mysql headers to csv
    fputcsv($fp, $headers);
    $row_tally = 0;
    // Write mysql rows to csv
    while ($row = dbAbstract::returnAssoc($result)) {
    
   $row_tally = $row_tally + 1;
    echo $row_tally.",";
      fputcsv($fp, array_values($row));
    }
    die;
}
}

