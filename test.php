<div style="font-family: Arial; font-size: 14px;">
<?php 
    error_reporting(E_ALL & ~E_NOTICE);
    function get_timezone_offset($remote_tz, $origin_tz = null) 
    {
        if($origin_tz === null) 
        {
            if(!is_string($origin_tz = date_default_timezone_get())) 
            {
                return false; 
            }
        }
        $origin_dtz = new DateTimeZone($origin_tz);
        $remote_dtz = new DateTimeZone($remote_tz);
        $origin_dt = new DateTime("now", $origin_dtz);
        $remote_dt = new DateTime("now", $remote_dtz);
        $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
        return $offset;
    }
    
    include("includes/config.php");
    $mRes = dbAbstract::Execute("SELECT start_date, end_date FROM bh_signature_sandwitch");
    if ($mRes)
    {
        if (dbAbstract::returnRowsCount($mRes))
        {
            echo("Server Timezone: ".date_default_timezone_get());
            while ($mRow = dbAbstract::returnObject($mRes))
            {
                echo("<br />");
                $mStartDateDB = $mRow->start_date;
                $mEndDateDB = $mRow->end_date;
                echo("<br />Start Date/Time: ".$mStartDateDB.", ".gmdate("Y-m-d H:i:s", $mStartDateDB));
                echo("<br />End Date/Time: ".$mEndDateDB.", ".gmdate("Y-m-d H:i:s", $mEndDateDB));
            }
            
            date_default_timezone_set("US/Eastern");
            $mCurrentTimeStamp = strtotime(date("Y-m-d H:i:s"));           
            echo("<br /><br /><br />Restaurant Timezone: ".date_default_timezone_get());
            
            echo("<br />Difference from UTC/GMT: ".round(((get_timezone_offset('UTC', 'US/Eastern')/60)/60))." Hours");
            echo("<br />Current Date/Time: ".$mCurrentTimeStamp.", ".gmdate("Y-m-d H:i:s", $mCurrentTimeStamp));
        }
        else
        {
            echo("No Signature Sandwich 1");
        }
    }
    else
    {
        echo("No Signature Sandwich 2");
    }
?>
</div>