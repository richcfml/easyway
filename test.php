<div style="font-family: Arial; font-size: 14px;">
<?php 
    include("includes/config.php");
    $mRes = dbAbstract::Execute("SELECT start_date, end_date FROM bh_signature_sandwitch LIMIT 1");
    if ($mRes)
    {
        $mRow = dbAbstract::returnObject($mRes);
        if ($mRow)
        {
            $mStartDateDB = $mRow->start_date;
            $mEndDateDB = $mRow->end_date;
            echo("Server Timezone: ".date_default_timezone_get());
            echo("<br />Start Date/Time: ".$mStartDateDB.", ".gmdate("Y-m-d H:i:s", $mStartDateDB));
            echo("<br />End Date/Time: ".$mEndDB.", ".gmdate("Y-m-d H:i:s", $mEndDB));
            date_default_timezone_set("Europe/London");
            $mCurrentTimeStamp = strtotime(date("Y-m-d H:i:s"));
            echo("<br /><br /><br />Restaurant Timezone: ".date_default_timezone_get());
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