<?php
require("../../includes/SimpleImage.php");
include("../../../includes/config.php");
if (isset($_GET['importimage']))
{
	$newWidth = "";
	$newHeight = "";
	
	extract($_POST);
	$mImageURL = $imageUrl;
	$mRandom = mt_rand(1, mt_getrandmax());
	$mExtention = GetFileExt($mImageURL);
	$mName = $mRandom.$mExtention;
	$mPath = '../../tempimages/'.$mName;
	file_put_contents($mPath, file_get_contents($mImageURL));
	
	list($width, $height, $type, $attr) = getimagesize($mPath);        
	$mImage = new SimpleImage();
	$mImage->load($mPath);
	
	if ($width>$height)
	{
		$newWidth = 500;
		$newHeight = round(((500/$width)*$height));
		$mImage->resize($newWidth,$newHeight);
	}
	else if ($width<$height)
	{
		$newWidth = round(((500/$height)*$width));
		$newHeight = 500;
		$mImage->resize($newWidth,$newHeight);
	}
	else 
	{
		$newWidth = 500;
		$newHeight = 500;
		$mImage->resize($newWidth,$newHeight);
	}
	
	$mImage->save($mPath);
	
	echo($mName."~".$newWidth."~".$newHeight);
}
else if (isset($_GET["sku"]))
{
    $q=$_POST['searchword'];
    $q=str_replace("@","",$q);
    $q=trim($q);
    
    $sql_res=dbAbstract::Execute("SELECT * from bh_items WHERE ItemCode LIKE '$q%' ORDER BY ID LIMIT 3",1);
?>
<div>
    <img class="imgCloseBH" src="images/cross2.png" style="float: right; cursor: hand; cursor: pointer; margin-top: 3px; margin-right: 3px;" />
<?php
    while($row=dbAbstract::returnArray($sql_res,1))
    {
    $mItemName=$row['ItemName'];
    ?>
    <div class="display_box" contenteditable="false">
    <a href="#" class='addname' style='color: #06C; font-size: 15px; line-height: 1.5; width: 95%;' title='<?php echo $mItemName; ?>'>
    <?php echo $mItemName ?> </a>
    </div>
    <?php
    }
?>
</div>
<?php
}
else if (isset($_GET["sku1"]))
{
    $q=$_POST['searchword'];
    $q=str_replace("@","",$q);
    $q=trim($q);
    
    $sql_res=dbAbstract::Execute("SELECT * from bh_items WHERE ItemCode = '$q'",1);
    if (dbAbstract::returnRowsCount($sql_res,1)>0)
    {
        $row=dbAbstract::returnArray($sql_res,1);
        echo($row['ItemName']);
    }
    else
    {
        echo("");
    }
}

function GetFileExt($fileName) 
{
    $ext = substr($fileName, strrpos($fileName, '.'));
    $ext = strtolower($ext);
	return substr($ext, 0, 4);
}
?>
