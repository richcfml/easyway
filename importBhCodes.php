<?php
require_once "includes/config.php";

$mInserted = 0;
$mUpdated = 0;
$mFlag = 0;
if (isset($_POST['submit'])) 
{
    if (is_uploaded_file($_FILES['filename']['tmp_name']))
    {
        $mFlag = 1;
        $handle = fopen($_FILES['filename']['tmp_name'], "r");
        $i=0;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
        {
            $mFlag = 2;
            if($i>0)
            {
                if ((trim($data[0])!="") && (trim($data[1])!=""))
                {
                    $mFlag = 3;
                    $mSQL = "SELECT COUNT(*) As RecCount FROM bh_items WHERE ItemCode='".trim($data[0])."'";
                    $mResult = dbAbstract::Execute($mSQL);
                    //$mResult = mysql_query($mSQL);
                    if (dbAbstract::returnRowsCount($mResult)>0)
                    //if (mysql_num_rows($mResult)>0)
                    {
                        $mFlag = 4;
                        $mRow = dbAbstract::returnObject($mResult);
                        //$mRow = mysql_fetch_object($mResult);
                        if ($mRow)
                        {
                            $mItemName = replaceBhSpecialChars(trim(utf8_encode($data[1])));
                            if ($mRow->RecCount==0)
                            {
                                $mInserted = $mInserted + 1;
                                $mSQL = "INSERT into bh_items (ItemCode, ItemName) VALUES ('".trim($data[0])."','".$mItemName."')";
                                dbAbstract::Insert($mSQL);
                                //mysql_query($mSQL);
                            }
                            else
                            {
                                $mUpdated = $mUpdated + 1;
                                $mSQL = "UPDATE bh_items SET ItemName='".$mItemName."' WHERE ItemCode='".trim($data[0])."'";
                                dbAbstract::Update($mSQL);
                                //mysql_query($mSQL);
                            }
                        }
                    }
                }
            }
            $i=1;
        }
        echo("Import done<br />");
        if ($mFlag == 0)
        {
            echo("No Submit.");
        }
        else if ($mFlag == 1)
        {
            echo("Uploaded File.");
        }
        else if ($mFlag == 2)
        {
            echo("Loop Started.");
        }
        else if ($mFlag == 3)
        {
            echo("Data Found.");
        }
        else if ($mFlag == 4)
        {
            echo("Rows Found.");
        }
        echo("<br />Inserted: ".$mInserted);
        echo("<br />Updated: ".$mUpdated);
    }
    else
    {
        echo("Error Occurred.");
    }
    fclose($handle);

}

function replaceBhSpecialChars($pDescription)
{
    $pDescription = str_replace("'", "&#39;", $pDescription);
    $pDescription = str_replace("®", "&#174;", $pDescription);
    $pDescription = str_replace("ä", "&#228;", $pDescription);
    $pDescription = str_replace("è", "&#232;", $pDescription);
    $pDescription = str_replace("ñ", "&#241;", $pDescription);
    $pDescription = str_replace("™", "&#8482;", $pDescription);
    $pDescription = str_replace(" ", " ", $pDescription);
    return $pDescription;
}
?>
<form id="frmMain" name="frmMain" method="post" enctype="multipart/form-data">
    <input size="50" type="file" name="filename"><br /><br />
    <input type="submit" name="submit" value="Upload">
</form>
