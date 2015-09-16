<?php
require_once "includes/config.php";

if (isset($_POST['submit'])) 
{
    if (is_uploaded_file($_FILES['filename']['tmp_name']))
    {
        $handle = fopen($_FILES['filename']['tmp_name'], "r");
        $i=0;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
        {
            if($i>0)
            {
                if ((trim($data[0])!="") && (trim($data[1])!=""))
                {
                    $mSQL = "SELECT COUNT(*) As RecCount FROM bh_items WHERE ItemCode='".trim($data[0])."'";
                    $mResult = dbAbstract::Execute($mSQL);
                    //$mResult = mysql_query($mSQL);
                    if ($mResult)
                    {
                        $mRow = dbAbstract::returnObject($mResult);
                        //$mRow = mysql_fetch_object($mResult);
                        if ($mRow)
                        {
                            if ($mRow->RecCount==0)
                            {
                                $mItemName = replaceBhSpecialChars(trim($data[1]));
                                $mSQL = "INSERT into bh_items (ItemCode, ItemName) VALUES ('".trim($data[0])."','".$mItemName."')";
                                dbAbstract::Insert($mSQL);
                                //mysql_query($mSQL);
                            }
                        }
                    }
                }
            }
            $i=1;
        }
        echo("Import done");
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
    $pDescription = str_replace(" ", " ", $pDescription);
    return $pDescription;
}
?>
<form id="frmMain" name="frmMain" method="post" enctype="multipart/form-data">
    <input size="50" type="file" name="filename"><br /><br />
    <input type="submit" name="submit" value="Upload">
</form>
