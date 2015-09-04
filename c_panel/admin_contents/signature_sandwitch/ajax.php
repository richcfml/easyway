<?php
require_once("../../../includes/config.php");
require("../../includes/SimpleImage.php");
require("../../includes/snapshot.class.php");
if (isset($_GET['add_item']))
{
    extract($_POST);
    $flag =0;
    //echo "<pre>";print_r($_POST);
	$where='';
	if($id > 0){
		$where=" where id <> $id";
	}
    $query = dbAbstract::Execute("Select start_date, end_date from bh_signature_sandwitch $where");
    while ($dates = dbAbstract::returnObject($query, 1))
    {
        $db_start_date = $dates->start_date;
        $db_end_date = $dates->end_date;
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        if(($start_ts >= $db_start_date) && ($start_ts <= $db_end_date))
        {
            $flag =2;
        }
        if(($end_ts >= $db_start_date) && ($end_ts <= $db_end_date))
        {
            $flag =2;
        }
    }
    
    if($flag == 0)
    {
        $product_description = replaceBhSpecialChars($product_description);
		if($id > 0){
			$qry = "UPDATE bh_signature_sandwitch set item_name = '" . ucfirst(addslashes($item_name)) . "', item_desc = '" . prepareStringForMySQL($product_description) . "', start_date = ".strtotime($start_date).", end_date = ".  strtotime($end_date)." WHERE id='$id'";
			dbAbstract::Update($qry, 1, 0);
		  	$lastid = $id;
		}else{
        	$qry = "INSERT INTO bh_signature_sandwitch set item_name = '" . ucfirst(addslashes($item_name)) . "', item_desc = '" . prepareStringForMySQL($product_description) . "', start_date = ".strtotime($start_date).", end_date = ".  strtotime($end_date)."";
		  	$lastid = dbAbstract::Insert($qry, 1, 2);
		}
        
        if (!empty($_GET['ext']))
        {
            $exe = array_pop(explode(".", $_GET['ext']));
            $name = "img_" . $lastid . "_prd." . $exe;
            Log::write("Update Product Image- menu_ajax.php", "QUERY -- UPDATE product set item_image = '$name' where prd_id = " . $lastid, 'menu', 1 , 'cpanel');
            dbAbstract::Update("UPDATE bh_signature_sandwitch set item_image = '$name' where id = " . $lastid, 1);
            $destination_dir = "../../images/signaturesandwich/"; //path of the destination directory
            $source_dir = "../../tempimages";
            $source_img_path = $source_dir . "/" . $_GET['ext'];
            $destination_img_path = $destination_dir . "/" . $name;
            copy($source_img_path, $destination_img_path);
        }
        echo $lastid;
        
    }
    else
    {
        echo "overlap";
    }
    
    
}
else if (isset($_GET['imgupload']))
{
    $myimage = new ImageSnapshot;
    if (isset($_FILES['file-0']))
        $myimage->ImageField = $_FILES['file-0'];
   
    if (!empty($_FILES['file-0']['name']) && $myimage->ProcessImage() != false)
    {
		$newWidth = "";
		$newHeight = "";
        $path = '../../tempimages/';
        $exe = GetFileExt($_FILES['file-0']['name']);
        //$name = "img_" . $lastid . "_prd." . $exe;
        $name = $_FILES['file-0']['name'];
        $uploadfile = $path . $name;
        
        move_uploaded_file($_FILES['file-0']['tmp_name'], $uploadfile);
        list($width, $height, $type, $attr) = getimagesize("$uploadfile");
        
        
            $image = new SimpleImage();
            $image->load($uploadfile);
			
			if (($width>500) || ($height>500))
			{
				if ($width>$height)
				{
					$newWidth = 500;
					$newHeight = round(((500/$width)*$height));
					$image->resize($newWidth,$newHeight);
				}
				else if ($width<$height)
				{
					$newWidth = round(((500/$height)*$width));
					$newHeight = 500;
					$image->resize($newWidth,$newHeight);
				}
				else 
				{
					$newWidth = 500;
					$newHeight = 500;
					$image->resize($newWidth,$newHeight);
				}
			}
			else
			{
				$newWidth = $width;
				$newHeight = $height;
			}
            $image->save($uploadfile);
            
        if ($_FILES['file-0']['error'] == 0)
        {
            echo $_FILES['file-0']['name']."~".$newWidth."~".$newHeight;
        }
    }
} 
else if (isset($_GET['cropimg']))
{   $name =  substr( $_GET['ext'], strrpos( $_GET['ext'], '/' )+1 );
	$mScale =  $_GET['scale'];
    $targ_w =$_GET['w']*$mScale; 
	$targ_h = $_GET['h']*$mScale; 
	$jpeg_quality = 90;
    if(strpos($_GET['ext'], 'prd')!= true)
    {
        $src = '../../tempimages/'.$name;
    }
    else
    {
        $src = '../../../images/item_images/'.$name;
    }
    echo $src;
    $img_r = imagecreatefromjpeg($src);
    $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
    imagecopyresampled($dst_r,$img_r,0,0,$_GET['x']*$mScale,$_GET['y']*$mScale,
    $targ_w,$targ_h,$_GET['w']*$mScale,$_GET['h']*$mScale);
    imagejpeg($dst_r,$src,$jpeg_quality);
    imagedestroy($dst_r);
    
    
}
elseif(isset($_GET['ssdata'])){
	$row = dbAbstract::ExecuteObject("Select * from bh_signature_sandwitch where id = ".$_POST['id'],1);
	
	$sslink = '<a href="'.$SiteUrl.'c_panel/?mod=signaturesandwitch&ssid='.$row->id.'">'.
					$row->item_name.' ('.date('m/d',$row->start_date).' - '.date('m/d',$row->end_date).')'.
				  '</a>';
	if($_GET['ssdata']=='linkonly'){
		echo $sslink;
	}elseif($_GET['ssdata']=='row'){
		echo '<tr><td>'.$sslink.'</td></tr>';
	}
	
}
function GetFileExt($fileName) {
    $ext = substr($fileName, strrpos($fileName, '.') + 1);
    $ext = strtolower($ext);
    return $ext;
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
