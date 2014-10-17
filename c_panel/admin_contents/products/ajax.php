<?php
require("../../includes/SimpleImage.php");

if (isset($_GET['importimage']))
{
	$newWidth = "";
	$newHeight = "";
	
	extract($_POST);
	$mImageURL = $imageUrl;
	$mRandom = mt_rand(1, mt_getrandmax());
	$mExtention = GetFileExt($mImageURL);
	$mName = $mRandom.$mExtention;
	$mPath = '../../img/'.$mName;
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

function GetFileExt($fileName) 
{
    $ext = substr($fileName, strrpos($fileName, '.'));
    $ext = strtolower($ext);
	return substr($ext, 0, 4);
}
?>