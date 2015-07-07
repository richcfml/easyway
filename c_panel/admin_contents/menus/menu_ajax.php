<?php
require_once("../../../includes/config.php");
require("../../includes/SimpleImage.php");
require("../../includes/snapshot.class.php");

if (!function_exists('json_encode')) {
    function json_encode($data) {
        switch ($type = gettype($data)) {
            case 'NULL':
                return 'null';
            case 'boolean':
                return ($data ? 'true' : 'false');
            case 'integer':
            case 'double':
            case 'float':
                  return '"' . addslashes($data) . '"';
            case 'string':
                return '"' . addslashes($data) . '"';
            case 'object':
                $data = get_object_vars($data);
            case 'array':
                $output_index_count = 0;
                $output_indexed = array();
                $output_associative = array();
                foreach ($data as $key => $value) {
                    $output_indexed[] = json_encode($value);
                    $output_associative[] = json_encode($key) . ':' . json_encode($value);
                    if ($output_index_count !== NULL && $output_index_count++ !== $key) {
                        $output_index_count = NULL;
                    }
                }
                if ($output_index_count !== NULL) {
                    return '[' . implode(',', $output_indexed) . ']';
                } else {
                    return '{' . implode(',', $output_associative) . '}';
                }
            default:
                return ''; // Not supported
        }
    }

}
// Delete sub menu item
if (isset($_GET['prd_delete']) && $_GET['prd_delete'] == 1 && isset($_GET['prd_id']))
{
    $prd_id = $_GET['prd_id'];
    Log::write("Delete product - menu_ajax.php", "QUERY -- Delete from product where prd_id = " . $prd_id . "", 'menu', 1 , 'cpanel');
    $result = mysql_query("Delete from product where prd_id = " . $prd_id . "");
    mysql_query("Delete from product_association where association_id = " . $prd_id . " or product_id = ". $prd_id."");
    Log::write("Delete product_association - menu_ajax.php", "QUERY -- Delete from product_association where association_id = " . $prd_id . "or product_id = ". $prd_id."", 'menu', 1 , 'cpanel');
    echo $result;
}

// add sub menu
if (isset($_GET['add']))
{
    if (!empty($_POST))
    {
        extract($_POST);
    }

    $query = mysql_query("SELECT * FROM categories where menu_id=".$_GET['menuid']."");
    
    $mResult = mysql_query("SELECT IFNULL(MAX(cat_ordering), 0) AS OrderNo FROM categories Where menu_id=".$_GET['menuid']."");
    $mOrderNoRow = mysql_fetch_object($mResult);
    $mOrderNo = $mOrderNoRow->OrderNo+1;
    
    Log::write("Add new category - menu_ajax.php", "QUERY --INSERT INTO categories SET parent_id= ".$_GET['catid'].", menu_id= ".$_GET['menuid'].", cat_name= '" . ucfirst(addslashes($submenu_name)) . "', cat_ordering= 1, cat_des= '" . addslashes($description) . "'", 'menu', 1 , 'cpanel');
    $result = mysql_query("INSERT INTO categories SET parent_id= ".$_GET['catid'].", menu_id= ".$_GET['menuid'].", cat_name= '" . ucfirst(addslashes($submenu_name)) . "', cat_ordering= ".$mOrderNo.", cat_des= '" . addslashes($description) . "'");
	
	$mResC1 = mysql_query("SELECT IFNULL(Column1Count, -1) AS Column1Count FROM menus WHERE id=".trim($_GET['menuid']));
	if (mysql_num_rows($mResC1)>0)
	{
		$mRowC1 = mysql_fetch_object($mResC1);
		$mC1Cnt = $mRowC1->Column1Count;
		
		$mTotalProductsRes = mysql_query("SELECT COUNT(*) AS CatCount FROM  categories WHERE menu_id=".trim($_GET['menuid']));
		
		if (mysql_num_rows($mTotalProductsRes)>0)
		{
			$mTotalProductsRow = mysql_fetch_object($mTotalProductsRes);
			$mProductCount = $mTotalProductsRow->CatCount;
			
			if (($mC1Cnt==($mProductCount-1)))
			{
				Log::write("Adding new category - menu_ajax.php", "QUERY -- UPDATE menus SET Column1Count=Column1Count+1 WHERE id=".trim($_GET['menuid']), 'menu', 1 , 'cpanel');
				mysql_query("UPDATE menus SET Column1Count=Column1Count+1 WHERE id=".trim($_GET['menuid']));
			}
											  
		}
	}
    echo $result;
}



//update sub menu
else if (isset($_GET['update']))
{
    if (!empty($_POST))
    {
        extract($_POST);
    }
    Log::write("Update category - menu_ajax.php", "QUERY --UPDATE categories SET cat_name='" . ucfirst(addslashes($submenu_name)) . "', cat_des= '" . addslashes($description) . "' where cat_id = '$hdnCatid'", 'menu', 1 , 'cpanel');
    $result = mysql_query("UPDATE categories SET cat_name='" . ucfirst(addslashes($submenu_name)) . "', cat_des= '" . addslashes($description) . "' where cat_id = '$hdnCatid'");
    echo $result;
}


// upload sub menu item image
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


else if (isset($_GET['add_menu_item']))
{
    $type = '';
    $sub_cat = $_GET['sub_cat'];
    extract($_POST);
    $feature_subcat = 0;

    if (!empty($_POST['type']))
    {
        $type = rtrim(implode(',', $_POST['type']), ',');
    }

    $price = str_replace("$",",",$price);
    $price = explode(",",$price);
    $price = trim(end($price));

    if (strstr($price, ".")==FALSE)
    {
           $price = $price.".00";
    }
    else
    {
           $price = $price;
           
           if(substr($price,0,1) == '.')
           {
                 $price = '0'.$price;
           }
    }

    $mRestBH = 0;
    $mSQLBH = "SELECT COUNT(*) AS RestCount FROM resturants WHERE id = ".$_GET['restid']. " AND bh_restaurant=1";
    $mResBH = mysql_fetch_object(mysql_query($mSQLBH));
    
    if ($mResBH)
    {
        if ($mResBH->RestCount>0)
        {
            $mRestBH = 1;
        }
    }
    Log::write("Add- Description1", $product_description, 'debug');
    $product_description = replaceBhSpecialChars($product_description);
    
    $mBHItem = 0;
    if ($mRestBH==1)
    {
        $mSQLBH = "SELECT COUNT(*) AS ItemCount FROM bh_items WHERE LOCATE(ItemName, '".$product_description."')>0";
        $mResBH = mysql_fetch_object(mysql_query($mSQLBH));
        if ($mResBH)
        {
            if ($mResBH->ItemCount>0)
            {
                if ($type=="")
                {
                    $type = "B";
                }
                else
                {
                    $type = $type.",B";
                }
            }
        }
    }
    
    Log::write("Select MaxSortOrder product - menu_ajax.php", "QUERY -- Select max(SortOrder) as maxOrder from product where sub_cat_id = ".$sub_cat, 'menu', 1 , 'cpanel');

    $maxSortOrderNo = mysql_fetch_object(mysql_query("Select max(SortOrder) as maxOrder from product where sub_cat_id = ".$sub_cat));

    $maxOrderNo = 0;
    if($maxSortOrderNo->maxOrder != null) {
        // MaxOrder is present -- Get it and use it for adding new products;
        $maxOrderNo = $maxSortOrderNo->maxOrder;
        $maxOrderNo++;
    }
    
    Log::write("Add new product - menu_ajax.php", "QUERY -- INSERT INTO product set cat_id = '".$_GET['restid']."', sub_cat_id = $sub_cat,item_title = '" . ucfirst(addslashes($item_name)) . "', item_des = '" . addslashes($product_description) . "', retail_price = '$price', feature_sub_cat = $feature_subcat,item_type='" . $type . "',SortOrder=" . $maxOrderNo . "", 'menu', 1 , 'cpanel');
    Log::write("Add- Description2", $product_description, 'debug');
    mysql_query("INSERT INTO product set cat_id = '".$_GET['restid']."', sub_cat_id = $sub_cat,item_title = '" . ucfirst(addslashes($item_name)) . "', item_des = '" . prepareStringForMySQL($product_description) . "', retail_price = '$price', feature_sub_cat = $feature_subcat,pos_id = '$pos_id',item_type='" . $type . "',SortOrder=" . $maxOrderNo . "");
    $lastid = mysql_insert_id();
    if (!empty($_GET['ext']))
    {
        $exe = array_pop(explode(".", $_GET['ext']));
        $name = "img_" . $lastid . "_prd." . $exe;
        Log::write("Update Product Image- menu_ajax.php", "QUERY -- UPDATE product set item_image = '$name' where prd_id = " . $lastid, 'menu', 1 , 'cpanel');
        mysql_query("UPDATE product set item_image = '$name' where prd_id = " . $lastid);
        $destination_dir = "../../../images/item_images/"; //path of the destination directory
        $source_dir = "../../tempimages";
        $source_img_path = $source_dir . "/" . $_GET['ext'];
        $destination_img_path = $destination_dir . "/" . $name;
        copy($source_img_path, $destination_img_path);
    }
    echo $lastid;
    
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
    //imagejpeg($dst_r,null,$jpeg_quality);
    //header('Content-type: image/jpeg');
    imagejpeg($dst_r,$src,$jpeg_quality);
    imagedestroy($dst_r);
    
    
}
else if (isset($_GET['update_menu_item']))
{   
    $type = '';
    extract($_POST);
    $feature_subcat = 0;

    if (!empty($_POST['type']))
    {
        $type = rtrim(implode(',', $_POST['type']), ',');
    }

    
	//Gulfam - Code to for Editing restaurants other than US region starts 
	$mRegioRes = mysql_query("SELECT IFNULL(R.region, 1) AS RestaurantRegion FROM  resturants R INNER JOIN  product P ON R.id = P.cat_id AND P.prd_id=".$_GET['prd_id']);
	
	if (mysql_num_rows($mRegioRes)>0)
	{
		$mRegioRow = mysql_fetch_object($mRegioRes);
		if (($mRegioRow->RestaurantRegion!=1) && ($mRegioRow->RestaurantRegion!=2))
		{
				$price = str_replace("�",",",$price);
		}
		else
		{
			$price = str_replace("$",",",$price);
		}
	}
	else
	{
		$price = str_replace("$",",",$price);
	}
	//Gulfam - Code to for Editing restaurants other than US region Ends 
	
	

    $price = explode(",",$price);
    $price = trim(end($price));

    if (strstr($price, ".")==FALSE)
    {
       $price = $price.".00";
    }
    else
    {

      $price = $price;
      if(substr($price,0,1) == '.')
      {
        $price = '0'.$price;
      }
    }
    
    
    $mRestBH = 0;
    $mProductIDBH = $_GET['prd_id'];
    $mSQLBH = "SELECT cat_id FROM product WHERE prd_id=".$mProductIDBH;
    $mResBH = mysql_fetch_object(mysql_query($mSQLBH));
    if ($mResBH)
    {
        $mRestaurantIDBH = $mResBH->cat_id;
        $mSQLBH = "SELECT COUNT(*) AS RestCount FROM resturants WHERE id = ".$mRestaurantIDBH. " AND bh_restaurant=1";
        $mResBH = mysql_fetch_object(mysql_query($mSQLBH));

        if ($mResBH)
        {
            if ($mResBH->RestCount>0)
            {
                $mRestBH = 1;
            }
        }
        Log::write("Update- Description1", $product_description, 'debug');
        $product_description = replaceBhSpecialChars($product_description);

        $mBHItem = 0;
        if ($mRestBH==1)
        {
            $mSQLBH = "SELECT COUNT(*) AS ItemCount FROM bh_items WHERE LOCATE(ItemName, '".$product_description."')>0";
            $mResBH = mysql_fetch_object(mysql_query($mSQLBH));
            if ($mResBH)
            {
                if ($mResBH->ItemCount>0)
                {
                    if ($type=="")
                    {
                        $type = "B";
                    }
                    else
                    {
                        $type = $type.",B";
                    }
                }
                else
                {
                    if (trim($type)=="B")
                    {
                        $type = "";
                    }
                    else
                    {
                        $type = str_replace(",B", "", $type);
                        $type = str_replace(", B", "", $type);
                    }
                }
            }
            else
            {
                if (trim($type)=="B")
                {
                    $type = "";
                }
                else
                {
                    $type = str_replace(",B", "", $type);
                    $type = str_replace(", B", "", $type);
                }
            }
        }
    }
    
    
    
    Log::write("Update Product Title, Desc, Retail Pric, Type - menu_ajax.php", "QUERY -- update product set item_title = '" . ucfirst(addslashes($item_name)) . "', item_des = '" . prepareStringForMySQL($product_description) . "', retail_price = ".str_replace('$','',$price).", item_type='" . $type . "' where prd_id = " . $_GET['prd_id'] . "", 'menu', 1 , 'cpanel');
    
    mysql_query("update product set item_title = '" . ucfirst(addslashes($item_name)) . "', item_des = '" . prepareStringForMySQL($product_description) . "', retail_price = ".str_replace('$','',$price).",pos_id = '$pos_id', item_type='" . $type . "' where prd_id = " . $_GET['prd_id'] . "");
    Log::write("Update- Description2", $product_description, 'debug');
    if (!empty($_GET['ext']))
    {
        $exe = array_pop(explode(".", $_GET['ext']));
        $name = "img_" . $_GET['prd_id'] . "_prd." . $exe;
        Log::write("Update Product Image - menu_ajax.php", "QUERY -- UPDATE product set item_image = '$name' where prd_id = " . $_GET['prd_id'], 'menu', 1 , 'cpanel');
        mysql_query("UPDATE product set item_image = '$name' where prd_id = " . $_GET['prd_id']);
        //echo __DIR__;exit;
        $destination_dir = "../../../images/item_images/"; //path of the destination directory
        $source_dir = "../../tempimages";
        $source_img_path = $source_dir . "/" . $_GET['ext'];
        $destination_img_path = $destination_dir . "/" . $name;
        if(file_exists($source_img_path)){
        copy($source_img_path, $destination_img_path);
        }

    }
    else
    {
        Log::write("Update Product Image - menu_ajax.php", "QUERY -- UPDATE product set item_image = '' where prd_id = " . $_GET['prd_id'], 'menu', 1 , 'cpanel'); 
         mysql_query("UPDATE product set item_image = '' where prd_id = " . $_GET['prd_id']);
    }

    $menuSQl =  mysql_fetch_assoc(mysql_query("Select id,menu_name,rest_id from menus where id in (Select menu_id from categories where cat_id = ".$_GET['sub_catid'].")"));
    $menuSQl['menu_name'] = str_replace("'","&#39;",$menuSQl['menu_name']);
    print_r(json_encode($menuSQl));
}


else if (isset($_GET['copy']) && $_GET['copy'] == 1 && !empty($_GET['cat_id']))
{
    
//-------------------------------Start NK(10-10-2014)----------------------------------------------------------------------------------------------    
    $CatOrder = mysql_query("SELECT IFNULL(MAX(cat_ordering), 0) AS cat_ordering FROM categories Where menu_id=".$_GET['menuid']."");
    $CatOrderRow = mysql_fetch_object($CatOrder);
    $ResCatOrder = $CatOrderRow->cat_ordering+1; 
    
    $cat_id = $_GET['cat_id'];
    $cat_rs = mysql_fetch_object(mysql_query("select * from categories where cat_id =" . $cat_id . ""));
    Log::write("Add new category - menu_ajax.php", "QUERY --insert into categories(`parent_id`, `menu_id`, `cat_name`, `cat_des`, `cat_ordering`, `status`) values (
                '" . $cat_rs->parent_id . "','" . $cat_rs->menu_id . "','" . $cat_rs->cat_name . "','" . $cat_rs->cat_des . "',1
                ,'" . $cat_rs->status . "')", 'menu', 1 , 'cpanel');
    
    mysql_query("insert into categories(`parent_id`, `menu_id`, `cat_name`, `cat_des`, `cat_ordering`, `status`) values (
    '" . $cat_rs->parent_id . "','" . $cat_rs->menu_id . "','" . $cat_rs->cat_name . "','" . $cat_rs->cat_des . "',$ResCatOrder
    ,'" . $cat_rs->status . "')");
    $newCatId = mysql_insert_id();
//-------------------------------End NK(10-10-2014)----------------------------------------------------------------------------------------------    

    $mysql_cat_products = mysql_query("select * from product where sub_cat_id =" . $cat_rs->cat_id . " Order by  prd_id");
    while ($product_rs = mysql_fetch_object($mysql_cat_products))
    {
        Log::write("Add new product - menu_ajax.php", "QUERY --insert into product(
				`cat_id`, `sub_cat_id`, `item_num`, `item_title`, `item_code`, `item_des`, `retail_price`, `sale_price`, `item_image`, `feature_sub_cat`, `Alt_tag`, `Ptitle`, `Meta_des`, `Meta_tag`, `imagethumb`, `status`,`pos_id`,`item_type`, `SortOrder`

					) values ( '" . $product_rs->cat_id . "', '" . $newCatId . "', '" . $product_rs->item_num . "'
					, '" . $product_rs->item_title . "'
					, '" . $product_rs->item_code . "'
					, '" . $product_rs->item_des . "'
					, '" . $product_rs->retail_price . "'
					, '" . $product_rs->sale_price . "'
					, '" . $product_rs->item_image . "'
					, '" . $product_rs->feature_sub_cat . "'
					, '" . $product_rs->Alt_tag . "'
					, '" . $product_rs->Ptitle . "'
					, '" . $product_rs->Meta_des . "'
					, '" . $product_rs->Meta_tag . "'
                                        , '" . $product_rs->imagethumb . "'
					, '" . $product_rs->status . "'
					, '" . $product_rs->pos_id . "'
                                        , '" . $product_rs->item_type . "'
                                        , " . $product_rs->SortOrder . "

					 )", 'menu', 1 , 'cpanel');
        mysql_query("insert into product(
				`cat_id`, `sub_cat_id`, `item_num`, `item_title`, `item_code`, `item_des`, `retail_price`, `sale_price`, `item_image`, `feature_sub_cat`, `Alt_tag`, `Ptitle`, `Meta_des`, `Meta_tag`, `imagethumb`, `status`,`pos_id`,`item_type`, `SortOrder`

					) values ( '" . $product_rs->cat_id . "', '" . $newCatId . "', '" . $product_rs->item_num . "'
					, '" . $product_rs->item_title . "'
					, '" . $product_rs->item_code . "'
					, '" . prepareStringForMySQL($product_rs->item_des) . "'
					, '" . $product_rs->retail_price . "'
					, '" . $product_rs->sale_price . "'
					, '" . $product_rs->item_image . "'
					, '" . $product_rs->feature_sub_cat . "'
					, '" . $product_rs->Alt_tag . "'
					, '" . $product_rs->Ptitle . "'
					, '" . $product_rs->Meta_des . "'
					, '" . $product_rs->Meta_tag . "'
                                        , '" . $product_rs->imagethumb . "'
					, '" . $product_rs->status . "'
					, '" . $product_rs->pos_id . "'
                                        , '" . $product_rs->item_type . "'
                                        , " . $product_rs->SortOrder . "

					 )");

        $newProductID = mysql_insert_id();
        Log::write("Add new attribute - menu_ajax.php", "QUERY -- insert into attribute(`ProductID`, `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price`,attr_name,extra_charge )  select " . $newProductID . ", `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` from attribute where ProductID=" . $product_rs->prd_id . "", 'menu', 1 , 'cpanel');
        mysql_query("insert into attribute(`ProductID`, `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price`,attr_name,extra_charge )  select " . $newProductID . ", `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` from attribute where ProductID=" . $product_rs->prd_id . "");
        Log::write("Add new product_association - menu_ajax.php", "QUERY -- insert into product_association(`product_id`, `association_id`,`sortOrder` )  select " . $newProductID . ", `association_id`,`sortOrder`  from product_association where product_id =" . $product_rs->prd_id . "", 'menu', 1 , 'cpanel');
        mysql_query("insert into product_association(`product_id`, `association_id`,`sortOrder` )  select " . $newProductID . ", `association_id`,`sortOrder`  from product_association where product_id =" . $product_rs->prd_id . "");
        Log::write("Set product HasAttributes=1, HasAssociates=1 - menu_ajax.php", "QUERY -- UPDATE product set HasAttributes=1,HasAssociates=1 WHERE prd_id = " . $newProductID . "", 'menu', 1 , 'cpanel');
        mysql_query("UPDATE product set HasAttributes=1,HasAssociates=1 WHERE prd_id = " . $newProductID . "");
    }

    $mSQlAttr = mysql_query("Select * from new_attribute where sub_catid=" . $cat_id  . "");
    
    while($attrSql = mysql_fetch_object($mSQlAttr))
    {
        Log::write("Add new attribute int new_attribute Table - menu_ajax.php", "QUERY --insert into new_attribute(`sub_catid`, `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` )  values('".$newCatId."','".$attrSql->option_name."','".$attrSql->Title."','".$attrSql->Price."','".$attrSql->option_display_preference."','".$attrSql->apply_sub_cat."','".$attrSql->Type."','".$attrSql->Required."','".$attrSql->OderingNO."','".$attrSql->rest_price."','".$attrSql->display_Name."','".$attrSql->Default."','".$attrSql->add_to_price."','".$attrSql->attr_name."','".$attrSql->extra_charge."')", 'menu', 1 , 'cpanel');
        mysql_query("insert into new_attribute(`sub_catid`, `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` )  values('".$newCatId."','".$attrSql->option_name."','".$attrSql->Title."','".$attrSql->Price."','".$attrSql->option_display_preference."','".$attrSql->apply_sub_cat."','".$attrSql->Type."','".$attrSql->Required."','".$attrSql->OderingNO."','".$attrSql->rest_price."','".$attrSql->display_Name."','".$attrSql->Default."','".$attrSql->add_to_price."','".$attrSql->attr_name."','".$attrSql->extra_charge."')");
    }
    echo $newCatId;
}


else if (isset($_GET['prd_copy']) && $_GET['prd_copy'] == 1 && !empty($_GET['prd_id']))
{
    
//-------------------------------Start NK(10-10-2014)----------------------------------------------------------------------------------------------
    
    $qryCat_ID=mysql_query("SELECT cat_id AS cat_id FROM product Where prd_id=".$_GET['prd_id']."");
    $rowCat_ID = mysql_fetch_object($qryCat_ID);
    $getCat_ID = $rowCat_ID->cat_id;
    
    $OrderNo = mysql_query("SELECT MAX(SortOrder) AS SortOrder FROM product Where cat_id=".$getCat_ID);
    $OrderNoRow = mysql_fetch_object($OrderNo);
    $ResOrderNo = $OrderNoRow->SortOrder+1;
  
    $product_id = $_GET['prd_id'];
    $product_rs = mysql_fetch_object(mysql_query("select * from product where prd_id  =" . $product_id . ""));
    Log::write("Add new product - menu_ajax.php", "QUERY -- insert into product(
				`cat_id`, `sub_cat_id`, `item_num`, `item_title`, `item_code`, `item_des`, `retail_price`, `sale_price`, `item_image`, `feature_sub_cat`, `Alt_tag`, `Ptitle`, `Meta_des`, `Meta_tag`, `imagethumb`, `status`,`pos_id`,`item_type`,`SortOrder`

					) values ( '" . $product_rs->cat_id . "', '" . $product_rs->sub_cat_id . "', '" . $product_rs->item_num . "'
					, '" . $product_rs->item_title . "'
					, '" . $product_rs->item_code . "'
					, '" . $product_rs->item_des . "'
					, '" . $product_rs->retail_price . "'
					, '" . $product_rs->sale_price . "'
					, '" . $product_rs->item_image . "'
					, '" . $product_rs->feature_sub_cat . "'
					, '" . $product_rs->Alt_tag . "'
					, '" . $product_rs->Ptitle . "'
					, '" . $product_rs->Meta_des . "'
					, '" . $product_rs->Meta_tag . "'
                                        , '" . $product_rs->imagethumb . "'
					, '" . $product_rs->status . "'
					, '" . $product_rs->pos_id . "'
                                        , '" . $product_rs->item_type . "'
                                        , '" . $ResOrderNo . "'    

					 )", 'menu', 1 , 'cpanel');
    mysql_query("insert into product(
				`cat_id`, `sub_cat_id`, `item_num`, `item_title`, `item_code`, `item_des`, `retail_price`, `sale_price`, `item_image`, `feature_sub_cat`, `Alt_tag`, `Ptitle`, `Meta_des`, `Meta_tag`, `imagethumb`, `status`,`pos_id`,`item_type`,`SortOrder`

					) values ( '" . $product_rs->cat_id . "', '" . $product_rs->sub_cat_id . "', '" . $product_rs->item_num . "'
					, '" . $product_rs->item_title . "'
					, '" . $product_rs->item_code . "'
					, '" . prepareStringForMySQL($product_rs->item_des) . "'
					, '" . $product_rs->retail_price . "'
					, '" . $product_rs->sale_price . "'
					, '" . $product_rs->item_image . "'
					, '" . $product_rs->feature_sub_cat . "'
					, '" . $product_rs->Alt_tag . "'
					, '" . $product_rs->Ptitle . "'
					, '" . $product_rs->Meta_des . "'
					, '" . $product_rs->Meta_tag . "'
                                        , '" . $product_rs->imagethumb . "'
					, '" . $product_rs->status . "'
					, '" . $product_rs->pos_id . "'
                                        , '" . $product_rs->item_type . "'
                                        , '" . $ResOrderNo . "'     

					 )");
    
//-------------------------------End NK(10-10-2014)----------------------------------------------------------------------------------------------
    
    $newProductID = mysql_insert_id();
    
    Log::write("Add new attribute - menu_ajax.php", "QUERY -- insert into attribute(`ProductID`, `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`, `display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` )  select " . $newProductID . ", `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`, `display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` from attribute where ProductID=" . $product_rs->prd_id . "", 'menu', 1 , 'cpanel');
    mysql_query("insert into attribute(`ProductID`, `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`, `display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` )  select " . $newProductID . ", `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`, `display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` from attribute where ProductID=" . $product_rs->prd_id . "");
    Log::write("Set product HasAttributes=1,HasAssociates - menu_ajax.php", "QUERY -- UPDATE product set HasAttributes=1,HasAssociates WHERE prd_id = " . $newProductID . "", 'menu', 1 , 'cpanel');
    mysql_query("UPDATE product set HasAttributes=1,HasAssociates=1 WHERE prd_id = " . $newProductID . "");
    Log::write("Add new product association - menu_ajax.php", "QUERY -- insert into product_association(`product_id`, `association_id`,`sortOrder` )  select " . $newProductID . ", `association_id`,`sortOrder`  from product_association where product_id =" . $product_rs->prd_id . "", 'menu', 1 , 'cpanel');
    mysql_query("insert into product_association(`product_id`, `association_id`,`sortOrder` )  select " . $newProductID . ", `association_id`,`sortOrder`  from product_association where product_id =" . $product_rs->prd_id . "");
    echo $newProductID;

}

else if (isset($_GET['delete_submenu']) && $_GET['delete_submenu'] == 1 && !empty($_GET['cat_id']))
{
    Log::write("Delete category - menu_ajax.php", "QUERY -- Delete from categories where cat_id = ".$_GET['cat_id']."", 'menu', 1 , 'cpanel');
    $result = mysql_query("Delete from categories where cat_id = ".$_GET['cat_id']."");
    $prdQry = mysql_query("select prd_id from product where sub_cat_id = ".$_GET['cat_id']."");
      
    while($prd = mysql_fetch_object($prdQry))
    {
          Log::write("Delete attribute - menu_ajax.php - LINE 508", "QUERY -- Delete from attribute where ProductID = ".$prd->prd_id."", 'menu', 1 , 'cpanel');
          mysql_query("Delete from attribute where ProductID = ".$prd->prd_id."");
          Log::write("Delete product association - menu_ajax.php", "QUERY -- Delete from product_association where product_id = ".$prd->prd_id." or association_id = ".$prd->prd_id."", 'menu', 1 , 'cpanel');
          mysql_query("Delete from product_association where product_id = ".$prd->prd_id." or association_id = ".$prd->prd_id."");
    }
    Log::write("Delete product - menu_ajax.php", "QUERY -- Delete from product where sub_cat_id = ".$_GET['cat_id']."", 'menu', 1 , 'cpanel');
    mysql_query("Delete from product where sub_cat_id = ".$_GET['cat_id']."");
	
	if ((isset($_GET['column'])) && (isset($_GET['mid'])))
	{
		if ((trim($_GET['column'])==1) || (trim($_GET['column'])=="1"))
		{
			Log::write("Delete category - menu_ajax.php", "QUERY -- UPDATE menus SET Column1Count=Column1Count-1 WHERE id=".trim($_GET['mid']), 'menu', 1 , 'cpanel');
			mysql_query("UPDATE menus SET Column1Count=Column1Count-1 WHERE id=".trim($_GET['mid']));
		}
	}
	
    if($result==1)
    {
        echo $result;
    }
}

else if (isset($_GET['item_deactivate']) && $_GET['item_deactivate'] == 1 && !empty($_GET['prd_id']))
{

    $result = '';
    $id = $_GET['prd_id'];
    if ($_GET['status'] == "0")
    {
        Log::write("Update Product Status - menu_ajax.php", "QUERY -- UPDATE product SET  status='1' WHERE prd_id=$id", 'menu', 1 , 'cpanel'); 
        $result = mysql_query("UPDATE product SET  status='1' WHERE prd_id=$id");
        echo 'Activate';
    } 
    
    else if ($_GET['status'] == "1")
    {
        Log::write("Update Product Status - menu_ajax.php", "QUERY -- UPDATE product SET  status='0' WHERE prd_id=$id", 'menu', 1 , 'cpanel'); 
        $result = mysql_query("UPDATE product SET  status='0' WHERE prd_id=$id");
        echo 'Deactivate';
    }
}
else if (isset($_GET['submenu_deactivate']) && $_GET['submenu_deactivate'] == 1 && !empty($_GET['cat_id'])) //cat_id = Sub MenuID
{
    $result = '';
    $id = $_GET['cat_id'];
    if ($_GET['status'] == "0")
    {
        Log::write("Update Categories Status - menu_ajax.php", "QUERY -- UPDATE categories SET  status='1' WHERE cat_id=$id", 'menu', 1 , 'cpanel'); 
        $result = mysql_query("UPDATE categories SET  status='1' WHERE cat_id=$id");
        echo 'Activate';
    } 
    
    else if ($_GET['status'] == "1")
    {
        Log::write("Update Categories Status - menu_ajax.php", "QUERY -- UPDATE categories SET  status='0' WHERE cat_id=$id", 'menu', 1 , 'cpanel'); 
        $result = mysql_query("UPDATE categories SET  status='0' WHERE cat_id=$id");
        echo 'Deactivate';
    }
}


else if (isset($_GET['count_attr']) && $_GET['count_attr'] == 1 && !empty($_GET['sub_menuid']))
{
    $atrr_count = mysql_fetch_array(mysql_query("SELECT count(distinct(option_name)) as TotalAttribute FROM  `new_attribute` WHERE sub_catid =".$_GET['sub_menuid']));
    echo $atrr_count['TotalAttribute'];
}

else if (isset($_GET['remove_attributes']) && $_GET['remove_attributes'] == 1 && !empty($_GET['option_name']))
{
	$attrributeid = str_replace('-',',',$_GET['attributeids']);
// ------------------------------------Start NK--------------------------------------------------------------    
    $subCat_id = $_GET['subcatid'];   
    $cat_query = mysql_query("SELECT prd_id FROM product WHERE sub_cat_id= '" . $subCat_id . "'");
    while ($mRow1 = mysql_fetch_assoc($cat_query))
    {
        $productIDs.=$mRow1['prd_id'].",";     
    }
    $productIDs = substr($productIDs,0,-1);
    
    $mQuery = "Delete from attribute where id in (".$attrributeid.") and ProductID = ".$_GET['prd_id']."";
    Log::write("Delete attribute - menu_ajax.php - LINE 589", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');
    $result =mysql_query($mQuery);
    
    $mDisplayQryChk = mysql_fetch_array(mysql_query("Select 1 as count from attribute where option_name = '".$_GET['option_name']."' and ProductID in (".$productIDs.") limit 1"));

    Log::write("Select Attribute - menu_ajax.php", "QUERY -- Select count(option_name) as Total from attribute where ProductID = ".$_GET['prd_id']."", 'menu', 1 , 'cpanel');
    $moreAttributeCount = mysql_fetch_array(mysql_query("Select count(option_name) as Total from attribute where ProductID = ".$_GET['prd_id'].""));
    if($moreAttributeCount['Total']==0)
    {
        Log::write("Set product HasAttributes=0 - menu_ajax.php", "QUERY -- UPDATE product set HasAttributes=0  WHERE prd_id = " . $_GET['prd_id'] . "", 'menu', 1, 'cpanel');
        mysql_query("UPDATE product set HasAttributes=0 WHERE prd_id = " . $_GET['prd_id'] . "");
    }
    
    if(count($mDisplayQryChk['count']) > 0){        
        echo $result;
    }else{
        Log::write("Delete from new_attribute where sub_catid = ".$subCat_id." and option_name= '".$_GET['option_name']."'", 'menu', 1 , 'cpanel');
        mysql_query("Delete from new_attribute where sub_catid = ".$subCat_id." and option_name= '".$_GET['option_name']."'");
        echo $result;
    }
// ------------------------------------End NK---------------------------------------------------------------- 

}

else if (isset($_GET['remove_relatedItems']) && $_GET['remove_relatedItems'] == 1 && !empty($_GET['relatedItemid']))
{
    Log::write("Delete product association - menu_ajax.php", "QUERY -- Delete from product_association where product_id = ".$_GET['prd_id']." and association_id= ".$_GET['relatedItemid']."", 'menu', 1 , 'cpanel');
    $result =mysql_query("Delete from product_association where product_id = ".$_GET['prd_id']." and association_id= ".$_GET['relatedItemid']."");

    Log::write("Select product association - menu_ajax.php", "QUERY -- Select count(association_id) from product_association where product_id = ".$_GET['prd_id']."", 'menu', 1 , 'cpanel');
    $moreAssociateCount = mysql_fetch_array(mysql_query("Select count(association_id) as Total from product_association where product_id = ".$_GET['prd_id'].""));

    if($moreAssociateCount['Total']==0)
    {
        Log::write("Set product HasAssociates=0 - menu_ajax.php", "QUERY -- UPDATE product set HasAssociates=0 WHERE prd_id = " . $_GET['prd_id'] . "", 'menu', 1, 'cpanel');
        mysql_query("UPDATE product set HasAssociates=0 WHERE prd_id = " . $_GET['prd_id'] . "");
    }
    echo $result;
}

else if (isset($_GET['addRelatedItem']) && $_GET['addRelatedItem'] == 1 && !empty($_GET['assoc_id']))
{
    $val = 0;
    if($_GET['applyToall']==1)
    {
        $scat_id = $_GET['scid'];
		$prodQry = mysql_query("select prd_id from product where sub_cat_id= $scat_id");
		while($prodRs = mysql_fetch_array($prodQry)){
			Log::write("Delete product association - add_assoc_new.php", "QUERY -- DELETE FROM product_association where product_id = '".$prodRs['prd_id']."' and association_id = '".$association_id."'", 'menu', 1 , 'cpanel');
			mysql_query("DELETE FROM product_association where product_id = '".$prodRs['prd_id']."' and association_id = '".$_GET['assoc_id']."'");

	}

        $prodQry = mysql_query("select prd_id from product where sub_cat_id= $scat_id");
		while($prodRs = mysql_fetch_array($prodQry)){
			$association_id = $_GET['assoc_id'];

                        $mResult = mysql_query("SELECT IFNULL(MAX(sortOrder), 0) AS OrderNo FROM product_association Where product_id	=".$_GET['prd_id']);
                        $mOrderNoRow = mysql_fetch_object($mResult);
                        $mOrderNo = $mOrderNoRow->OrderNo+1;

                        $query = "INSERT INTO product_association SET product_id = '".$prodRs['prd_id']."', association_id = '".$association_id."',sortOrder = ".$mOrderNo."";
                        //echo $query;
                        Log::write("Add new product association - add_assoc_new.php", "QUERY -- INSERT INTO product_association SET product_id = '".$prodRs['prd_id']."', association_id = '".$association_id."',sortOrder = ".$mOrderNo."", 'menu', 1 , 'cpanel');
                        $val = mysql_query($query);
                        mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id = " . $prodRs['prd_id'] . "");
                        Log::write("Set product HasAssociates=1 - add_assoc_new.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $prodRs['prd_id'] . "", 'menu', 1, 'cpanel');
                        echo $val;
                }
    }
    else
    {
        $relSql = mysql_fetch_array(mysql_query("Select  count(product_id) as TotalRows from product_association where product_id = '" . $_GET['prd_id'] . "' and  association_id='" . $_GET['assoc_id'] . "'"));
        if ($relSql['TotalRows'] > 0)
        {
            echo $val;
        }
        else
        {
            $mResult = mysql_query("SELECT IFNULL(MAX(sortOrder), 0) AS OrderNo FROM product_association Where product_id	=".$_GET['prd_id']);
            $mOrderNoRow = mysql_fetch_object($mResult);
            $mOrderNo = $mOrderNoRow->OrderNo+1;

            Log::write("Add new product association - menu_ajax.php", "QUERY -- INSERT INTO product_association SET product_id = '" . $_GET['prd_id'] . "', association_id='" . $_GET['assoc_id'] . "'", 'menu', 1 , 'cpanel');
            $val = mysql_query("INSERT INTO product_association SET product_id = '" . $_GET['prd_id'] . "', association_id='" . $_GET['assoc_id'] . "', sortOrder = ".$mOrderNo."");
            Log::write("Set product HasAssociates=1 - menu_ajax.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $_GET['prd_id'] . "", 'menu', 1, 'cpanel');
            mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id = " . $_GET['prd_id'] . "");
            echo $val;
        }
    }
}

// Add Related Item in Category from AutoComplete texbox
else if (isset($_GET['addRelatedItemInCategory']) && $_GET['addRelatedItemInCategory'] == 1 && !empty($_GET['assoc_id']))
{
    $result ='';
    $option_name = '';
    $scat_id = $_GET['sub_catid'];
    Log::write("Delete product association - menu_ajax.php", "QUERY -- DELETE FROM product_association where association_id = '".$_GET['assoc_id']."' and product_id in (select prd_id from product where sub_cat_id = '".$scat_id."')", 'menu', 1 , 'cpanel');
    mysql_query("DELETE FROM product_association where association_id = '".$_GET['assoc_id']."' and product_id in (select prd_id from product where sub_cat_id = '".$scat_id."')");
    
    $prodQry = mysql_query("select prd_id from product where sub_cat_id= $scat_id");
    while ($prodRs = mysql_fetch_array($prodQry))
    {
            Log::write("Set product HasAssociates=1 - menu_ajax.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $prodRs['prd_id'] . "", 'menu', 1, 'cpanel');
            mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id = " . $prodRs['prd_id'] . "");
            Log::write("Add new product association - menu_ajax.php", "QUERY -- INSERT INTO product_association SET product_id = '" . $prodRs['prd_id'] . "', association_id='" . $_GET['assoc_id'] . "'", 'menu', 1 , 'cpanel');
            $result = mysql_query("INSERT INTO product_association SET product_id = '" . $prodRs['prd_id'] . "', association_id='" . $_GET['assoc_id'] . "'");
            
    }
    echo mysql_affected_rows();
}


else if (isset($_GET['LoadRelatedItem']) && $_GET['LoadRelatedItem'] == 1)
{
    $counter = 0;
    $result = '';
    $cat_query = mysql_query("SELECT * FROM categories WHERE parent_id='" . $_GET['cat_id'] . "'");
    while ($mRow1 = mysql_fetch_assoc($cat_query))
    {
            $mRow1['cat_name']=str_replace("'","&#39;",$mRow1['cat_name']);
            $subcatIDs.=$mRow1["cat_id"].",";
            $getcategories[]=$mRow1;
    }
    $subcatIDs = substr($subcatIDs,0,-1);
    
    $item_query = mysql_query("SELECT prd_id,item_title,sub_cat_id FROM product WHERE sub_cat_id in (" . $subcatIDs . ") AND prd_id!='" . $_GET['prd_id'] . "' and status = 1");
    $getItem = array();
    while($getRecord = mysql_fetch_assoc($item_query))
    {
       $getRecord['item_title'] = str_replace("'","&#39;",$getRecord['item_title']);
       $getItem[]=$getRecord;
    }
    $catCount = count($getcategories);
    $itemCount = count($getItem);
    
    for($i=0; $i<=$catCount-1;$i++)
    {
        $result .= "<div class='div_submenu_heading' style='margin-top: 13px;'><div style='float:left;font-weight: bold;text-decoration: underline;'>". stripslashes($getcategories[$i]['cat_name']) ."</div><div style='clear:both'></div>";
        for($j=0; $j<=$itemCount-1;$j++)
        {
            if($getcategories[$i]['cat_id'] == $getItem[$j]['sub_cat_id'])
            {   
                $assoc_query = mysql_query("SELECT  id FROM product_association WHERE product_id='" . $_GET['prd_id'] . "' AND association_id ='" . $getItem[$j]['prd_id'] . "' ");
                $assoc_rows = mysql_fetch_array($assoc_query);
                $result .= "<div class='check_box_dynamic_div'><input type='checkbox' name='itemcheck[]' id='itemcheck'" . (($assoc_rows['id']) ? " checked " : "") . "value='" . $getItem[$j]['prd_id'] . "'>" . stripslashes($getItem[$j]['item_title']) . "</div>";
                $counter++;
            }
        }
    }
   
//    if(mysql_num_rows($item_query) ==0)
//    {
//      $result .=  "<div class='check_box_dynamic_div' >No Item found</div>";
//    }
    $result .= "</div><div style='clear:both'></div>";
        

     if($result!='')
     {
          echo $result.="<div style='clear:both'></div><div class='line_540px'>&nbsp;</div><div class='check_box_dynamic_div_bold'><input type='checkbox' id='apply_subcat' name='apply_subcat' value='applytosubcat' /><strong>Apply This Association To All Menu Items In the Same Sub-Category</strong></div><input type='submit' name='closebtn' id='closebtn' value='Submit' class='cancel' style='margin-left:0px;width:80px; font-size:14px; padding-bottom:2px; margin-top:10px;'/>";
     }
     else
     {
         echo $result;
     }

    
}
else if (isset($_GET['LoadRelatedItemByCategory']) && $_GET['LoadRelatedItemByCategory'] == 1 && !empty($_GET['sub_catid']))
{
    $counter = 0;
    $result = "";
    $cat_query = mysql_query("SELECT * FROM categories WHERE parent_id='" . $_GET['cat_id'] . "'");
    while ($mRow1 = mysql_fetch_assoc($cat_query))
    {
            $mRow1['cat_name']=str_replace("'","&#39;",$mRow1['cat_name']);
            $subcatIDs.=$mRow1["cat_id"].",";
            $getcategories[]=$mRow1;
    }
    $subcatIDs = substr($subcatIDs,0,-1);

    
    $result .= "<div class='div_submenu_heading' style='margin-top: 13px;'><div style='float:left;font-weight: bold;text-decoration: underline;'>". stripslashes($catRs->cat_name) ."</div><div style='clear:both'></div>";
    $item_query = mysql_query("SELECT prd_id,item_title,sub_cat_id FROM product WHERE sub_cat_id in (" . $subcatIDs . ") and status = 1");
    $getItem = array();
    while($getRecord = mysql_fetch_assoc($item_query))
    {
       $getRecord['item_title'] = str_replace("'","&#39;",$getRecord['item_title']);
       $getItem[]=$getRecord;
    }
    $catCount = count($getcategories);
    $itemCount = count($getItem);
    for($i=0; $i<=$catCount-1;$i++)
    {
        $result .= "<div class='div_submenu_heading' style='margin-top: 13px;'><div style='float:left;font-weight: bold;text-decoration: underline;'>". stripslashes($getcategories[$i]['cat_name']) ."</div><div style='clear:both'></div>";
        for($j=0; $j<=$itemCount-1;$j++)
        {
            if($getcategories[$i]['cat_id'] == $getItem[$j]['sub_cat_id'])
            {
                $assoc_query = mysql_query("SELECT  id FROM product_association WHERE product_id in (select prd_id from product where sub_cat_id='" . $_GET['sub_catid'] . "') AND association_id ='" . $getItem[$j]['prd_id'] . "' ");
                $assoc_rows = mysql_fetch_array($assoc_query);
                $result .= "<div class='check_box_dynamic_div'><input type='checkbox' name='itemcheck[]' id='itemcheck'" . (($assoc_rows['id']) ? " checked " : "") . "value='" . $getItem[$j]['prd_id'] . "'>" . stripslashes($getItem[$j]['item_title']) . "</div>";
                $counter++;
            }
        }
    }
    $result .= "</div><div style='clear:both'></div>";
    

     if($result!='')
     {
         echo $result.="<div style='clear:both'></div><div class='line_540px'>&nbsp;</div><input type='submit' name='closebtn' id='closebtn' class='cancel' value='Submit' style='width:80px; margin-left:0px;; font-size:14px; padding-bottom:2px; margin-top:10px;'/>";
     }
     else
     {
         echo $result;
     }

}

else if (isset($_GET['LoadAttribute']) && $_GET['LoadAttribute'] == 1 && !empty($_GET['sub_cat_id']))
{

    $counter = 0;
    $result = '';


	$mMenuIDRes = mysql_query("SELECT menu_id FROM categories WHERE cat_id = ".$_GET['sub_cat_id']);
	$mMenuIDRow = mysql_fetch_object($mMenuIDRes);
	$mMenuID = $mMenuIDRow->menu_id;

    $item_query = mysql_query("SELECT distinct(display_Name),option_name FROM `new_attribute` WHERE sub_catid IN (SELECT cat_id FROM categories WHERE menu_id=".$mMenuID.") AND option_name not in(".$getdisplay_Name.")");


     $item_query = mysql_query("SELECT DISTINCT (option_name),display_Name FROM  `new_attribute` WHERE sub_catid IN (SELECT cat_id FROM categories WHERE menu_id=".$mMenuID.")");
     while($itemRs	= mysql_fetch_object($item_query))
     {
             $assoc_query = mysql_query("SELECT distinct(option_name) FROM attribute WHERE option_name = '".$itemRs->option_name."' and ProductID = ".$_GET['prd_id']."");
             $assoc_rows = mysql_fetch_array($assoc_query);
             $result .=  "<div class='checkboxBindAttribute'><input type='checkbox' name='itemcheckAttribute[]' id='itemcheckAttribute'" . (($assoc_rows['option_name'])?" checked ":""). "value='".$itemRs->display_Name ."'>".stripslashes($itemRs->option_name)."</div>";
             $counter++;
     }

     if($result!='')
     {
         echo $result.="<div style='clear:both'></div><div class='line_540px'>&nbsp;</div><input type='submit' name='closebtn' id='closebtn' value='Submit' class='cancel' style='width:80px; margin-left:0px; font-size:14px; padding-bottom:2px; margin-top:10px;'/>";;
     }
     else
     {
         echo $result;
     }

}
else if (isset($_GET['GetallRelatedItem']) && $_GET['GetallRelatedItem'] == 1 && !empty($_GET['prd_id']))
{

    $result = array();
    $item_query = mysql_query("select prd_id,item_title from product where cat_id ='".$_GET['cat_id']."' and prd_id not in(select PC.association_id from product_association PC inner join product p where p.sub_cat_id = ".$_GET['subcatid'].")");
    
    while($itemRs = mysql_fetch_object($item_query))
    {

     $result[]=$itemRs->item_title;

    }
      echo json_encode($result);
}

else if (isset($_GET['loadRelatedDropdown']) && $_GET['loadRelatedDropdown'] == 1 && !empty($_GET['rest_id']))
{
    $rest_id = $_GET['rest_id'];
    $result = array();
    $getcategories = array();
    $subcatIDs = '';
    $cat_query = mysql_query("SELECT cat_id,cat_name FROM categories WHERE parent_id= '" . $rest_id . "'");
    while ($mRow1 = mysql_fetch_assoc($cat_query))
    {
            $mRow1['cat_name']=str_replace("'","&#39;",$mRow1['cat_name']);
            $subcatIDs.=$mRow1["cat_id"].",";
            $getcategories[]=$mRow1;
    }
    $catCount = count($getcategories);
    
    $subcatIDs = substr($subcatIDs,0,-1);
    $item_query = mysql_query("SELECT prd_id,item_title,sub_cat_id FROM product WHERE sub_cat_id in (".$subcatIDs.") and status = 1");
    
    $getItem = array();
    
    while($getRecord = mysql_fetch_assoc($item_query))
    {
       $getRecord['item_title'] = str_replace("'","&#39;",$getRecord['item_title']);
       $getRecord['item_title'] = prepareStringForMySQL($getRecord['item_title']);
        $getItem[]=$getRecord;
    }
    
    $itemCount = count($getItem);

    for($i=0; $i<=$catCount-1;$i++)
    {
        for($j=0; $j<=$itemCount-1;$j++)
        {
            if($getcategories[$i]['cat_id'] ==$getItem[$j]['sub_cat_id'])
            {

                $getcategories[$i]['products'][$j] = $getItem[$j];
            }
        }
    }
      //echo "<pre>"; print_r($getcategories);
      echo json_encode($getcategories);
      
}

//Get attribute for Autocomplete
else if (isset($_GET['GetallAttributeOptionName']) && $_GET['GetallAttributeOptionName'] == 1 && !empty($_GET['sub_catid']))
{
    $result = array();
    $getdisplay_Name = array();
    $getItem  = array();
    $mDisplayQry = mysql_query("Select distinct(option_name) from attribute where ProductID  = ".$_GET['prd_id']."");
    while($display_name = mysql_fetch_assoc($mDisplayQry))
    {
        $display_name['option_name'] = "'".$display_name['option_name']."'";
        $getItem[]= $display_name;
    }
    $attrcount = count($getItem);
    for($j=0; $j<=$attrcount-1;$j++)
    {
        $getdisplay_Name['option_name'][$j] = $getItem[$j]['option_name'];
    }
    
    $getdisplay_Name = implode (", ", $getdisplay_Name['option_name']);
    if($getdisplay_Name == '')
    {
        $getdisplay_Name = "'".$getdisplay_Name."'";
    }
	
    $mMenuIDRes = mysql_query("SELECT menu_id FROM categories WHERE cat_id = ".$_GET['sub_catid']);
	$mMenuIDRow = mysql_fetch_object($mMenuIDRes);
	$mMenuID = $mMenuIDRow->menu_id;

    $item_query = mysql_query("SELECT distinct(display_Name),option_name FROM `new_attribute` WHERE sub_catid IN (SELECT cat_id FROM categories WHERE menu_id=".$mMenuID.") AND option_name not in(".$getdisplay_Name.")");
    while($itemRs = mysql_fetch_array($item_query))
    {
         $result[$itemRs['option_name']]=$itemRs['display_Name'];
    }
         echo json_encode($result);
}

// Add Attribute in product from AutoComplete texbox
else if (isset($_GET['addAttributeinProduct']) && $_GET['addAttributeinProduct'] == 1 && !empty($_GET['display_Name']))
{
    $mResult = mysql_query("SELECT IFNULL(MAX(OderingNO), 0) AS OrderNo FROM attribute Where ProductID =" . $_GET['prd_id'] . "");
    $mOrderNoRow = mysql_fetch_object($mResult);
    $mOrderNo = $mOrderNoRow->OrderNo+1;

    $option_name = '';
    $result = 0;
    $countSql = mysql_fetch_array(mysql_query("Select count(*) as TotalRows from attribute where display_Name = '" . $_GET['display_Name'] . "' and ProductID =" . $_GET['prd_id'] . " and option_name = '" . $_GET['option_name'] . "'"));
    
    if ($countSql['TotalRows'] > 0)
    {
        echo $result;
    } 
    else
    {
		$mMenuIDRes = mysql_query("SELECT menu_id FROM categories WHERE cat_id = ".$_GET['sub_catid']);
		$mMenuIDRow = mysql_fetch_object($mMenuIDRes);
		$mMenuID = $mMenuIDRow->menu_id;

    	$item_query = mysql_query("SELECT distinct(id),option_name FROM `new_attribute` WHERE sub_catid IN (SELECT cat_id FROM categories WHERE menu_id=".$mMenuID.") AND option_name not in(".$getdisplay_Name.")");
	
	
	
        $getattr = mysql_query("Select * from new_attribute where display_Name = '" . $_GET['display_Name'] . "' and sub_catid IN (SELECT cat_id FROM categories WHERE menu_id=".$mMenuID.") AND option_name = '" . $_GET['option_name'] . "'");
        while ($attr = mysql_fetch_array($getattr))
        {
            if (!empty($attr['id']))
            {
                Log::write("Set product HasAttributes=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $_GET['prd_id'] . "", 'menu', 1, 'cpanel');
                mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id = " . $_GET['prd_id'] . "");
                Log::write("Add new attribute - menu_ajax.php", "QUERY -- INSERT INTO attribute SET productid = '" . $_GET['prd_id'] . "', option_name = '" . $attr['option_name'] . "',Title = '" . $attr['Title'] . "',Price = '" . $attr['Price'] . "',option_display_preference = '" . $attr['option_display_preference'] . "',apply_sub_cat=0,Type	 = '" . $attr['Type'] . "',Required = '" . $attr['Required'] . "',OderingNO = '" . $mOrderNo . "',rest_price = '" . $attr['rest_price'] . "',display_Name='" . $attr['display_Name'] . "' ,`Default`='" . $attr['Default'] . "' ,add_to_price='" . $attr['add_to_price'] . "',attr_name ='" . $attr['attr_name'] . "',extra_charge ='" . $attr['extra_charge'] . "'", 'menu', 1 , 'cpanel');
                $result = mysql_query("INSERT INTO attribute SET productid = '" . $_GET['prd_id'] . "', option_name = '" . $attr['option_name'] . "',Title = '" . $attr['Title'] . "',Price = '" . $attr['Price'] . "',option_display_preference = '" . $attr['option_display_preference'] . "',apply_sub_cat=0,Type	 = '" . $attr['Type'] . "',Required = '" . $attr['Required'] . "',OderingNO = '" . $mOrderNo . "',rest_price = '" . $attr['rest_price'] . "',display_Name='" . $attr['display_Name'] . "' ,`Default`='" . $attr['Default'] . "' ,add_to_price='" . $attr['add_to_price'] . "',attr_name ='" . $attr['attr_name'] . "',extra_charge ='" . $attr['extra_charge'] . "'");
                $attrids .= mysql_insert_id()."-";
            }
        }
    }

        if(!empty($attrids))
        {
            echo substr($attrids,0,-1);
        }
        else
        {
            echo("Failure");
        }
}



// Add Attribute in Category from AutoComplete texbox
else if (isset($_GET['addAttributeinCategory']) && $_GET['addAttributeinCategory'] == 1 && !empty($_GET['display_Name']))
{
    $option_name = '';
    $scat_id = $_GET['sub_catid'];
    $mQuery = "DELETE FROM attribute where ProductID in (Select prd_id from product where sub_cat_id = " . $_GET['sub_catid'] . "";
    mysql_query($mQuery);
    Log::write("Delete Attribute - menu_ajax.php - LINE 998", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');

    $prodQry = mysql_query("select prd_id from product where sub_cat_id= $scat_id");
    while ($prodRs = mysql_fetch_array($prodQry))
    {
        $getattr = mysql_query("Select * from new_attribute where display_Name = '" . $_GET['display_Name'] . "' and sub_catid =" . $scat_id . "");
        while ($attr = mysql_fetch_array($getattr))
        {
            if (!empty($attr['id']))
            {
                Log::write("Add new attribute - menu_ajax.php", "QUERY --INSERT INTO attribute SET productid = '" . $prodRs['prd_id'] . "', option_name = '" . $attr['option_name'] . "',Title = '" . $attr['Title'] . "',Price = '" . $attr['Price'] . "',option_display_preference = '" . $attr['option_display_preference'] . "',apply_sub_cat=0,Type	 = '" . $attr['Type'] . "',Required = '" . $attr['Required'] . "',OderingNO = '" . $attr['OderingNO'] . "',rest_price = '" . $attr['rest_price'] . "',display_Name = '".$attr['display_Name']."',`Default`='" . $attr['Default'] . "' ,add_to_price='" . $attr['add_to_price'] . "',attr_name ='" . $attr['attr_name'] . "',extra_charge ='" . $attr['extra_charge'] . "'", 'menu', 1 , 'cpanel');
                $result = mysql_query("INSERT INTO attribute SET productid = '" . $prodRs['prd_id'] . "', option_name = '" . $attr['option_name'] . "',Title = '" . $attr['Title'] . "',Price = '" . $attr['Price'] . "',option_display_preference = '" . $attr['option_display_preference'] . "',apply_sub_cat=0,Type	 = '" . $attr['Type'] . "',Required = '" . $attr['Required'] . "',OderingNO = '" . $attr['OderingNO'] . "',rest_price = '" . $attr['rest_price'] . "',display_Name = '".$attr['display_Name']."',`Default`='" . $attr['Default'] . "' ,add_to_price='" . $attr['add_to_price'] . "',attr_name ='" . $attr['attr_name'] . "',extra_charge ='" . $attr['extra_charge'] . "'");
                Log::write("Set product HasAttributes=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $prodRs['prd_id'] . "", 'menu', 1 , 'cpanel');
                mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id = " . $prodRs['prd_id']. "");
                $option_name = $attr['display_Name'];
            }
        }
    }

    echo $option_name;
}



//Remove attributes frm Sub category
else if (isset($_GET['remove_attributesfromCategory']) && $_GET['remove_attributesfromCategory'] == 1 && !empty($_GET['option_name']))
{
    $result =mysql_query("Delete from new_attribute where option_name = '".$_GET['option_name']."'  and  sub_catid = " . $_GET['sub_catid'] ."");
    echo $result;
}

else if (isset($_GET['LoadAttributeByCategory']) && $_GET['LoadAttributeByCategory'] == 1 && !empty($_GET['sub_cat_id']))
{

    $counter = 0;
    $result = '';

	$mMenuIDRes = mysql_query("SELECT menu_id FROM categories WHERE cat_id = ".$_GET['sub_cat_id']);
	$mMenuIDRow = mysql_fetch_object($mMenuIDRes);
	$mMenuID = $mMenuIDRow->menu_id;
	
    $item_query = mysql_query("SELECT DISTINCT (option_name),display_Name FROM  `new_attribute` WHERE sub_catid	 IN (SELECT cat_id FROM categories WHERE menu_id=".$mMenuID.")");
    while($itemRs	= mysql_fetch_object($item_query))
    {
        $assoc_query = mysql_query("SELECT distinct(option_name) FROM attribute WHERE option_name = '".$itemRs->option_name."' and ProductID in (select prd_id from product where sub_cat_id IN (SELECT cat_id FROM categories WHERE menu_id=".$mMenuID."))");
        $assoc_rows = mysql_fetch_array($assoc_query);
        $result .=  "<div class='checkboxBindAttribute'><input type='checkbox' name='itemcheckAttribute[]' id='itemcheckAttribute'" . (($assoc_rows['option_name'])?" checked ":""). "value='".$itemRs->display_Name ."'>".stripslashes($itemRs->option_name)."</div>";
        $counter++;
    }

     echo $result;
}



//Remove Related Items frm Sub category
else if (isset($_GET['remove_relItemCategory']) && $_GET['remove_relItemCategory'] == 1 && !empty($_GET['relatedItemid']))
{
    Log::write("Delete product association - menu_ajax.php", "QUERY -- Delete from product_association where association_id = '".$_GET['relatedItemid']."' and product_id in(Select prd_id from product where sub_cat_id = " . $_GET['subcat_id'] .")", 'menu', 1 , 'cpanel');
    $result =mysql_query("Delete from product_association where association_id = '".$_GET['relatedItemid']."' and product_id in(Select prd_id from product where sub_cat_id = " . $_GET['subcat_id'] .")");

    $setHasAssociates = mysql_query("SELECT prd_id FROM `product` where  prd_id not in(Select product_id from product_association ) and sub_cat_id = " . $_GET['subcat_id'] ."");
    while ($Get_prd_id = mysql_fetch_array($setHasAssociates))
    {
        mysql_query("UPDATE product set HasAssociates=0 WHERE prd_id = " . $Get_prd_id['prd_id'] . "");
        Log::write("UPDATE product set HasAssociates=0 - menu_ajax.php", "QUERY -- UPDATE product set HasAssociates=0 WHERE prd_id = " . $Get_prd_id['prd_id'] . ")", 'menu', 1 , 'cpanel');
    }
    echo $result;
}



//Get Attributes and Related Item for Right Tool Box for Menu Page
else if (isset($_GET['GetAttributeForToolBox']) && $_GET['GetAttributeForToolBox'] == 1 && !empty($_GET['sub_catid']))
{
    $attributeArray = array();
    $accosArray = array();
    $attrib_qry = mysql_query("SELECT REPLACE(option_name, '\'', '&#39;') AS option_name, REPLACE(display_Name, '\'', '&#39;') AS display_Name FROM `new_attribute` WHERE sub_catid =".$_GET['sub_catid']);
    while ($attribRs = mysql_fetch_array($attrib_qry))
    {
       $attributeArray[$attribRs['display_Name']] = $attribRs['option_name'];
    }

    echo json_encode($attributeArray);
}

else if (isset($_GET['GetAssociationForToolBox']) && $_GET['GetAssociationForToolBox'] == 1 && !empty($_GET['sub_catid']))
{
     $accosArray = array();
     $product_assoc_qry = mysql_query("SELECT distinct(association_id) FROM `product_association` WHERE product_Id in (Select prd_id from product where  sub_cat_id=".$_GET['sub_catid'].")");
     while ($assocRs = mysql_fetch_array($product_assoc_qry))
     {
                $product_query = mysql_query("SELECT item_title,prd_id FROM product WHERE prd_id='" . $assocRs['association_id'] . "'");
                while ($productRS = mysql_fetch_array($product_query))
                {
                    $productRS['item_title'] = str_replace("'","&#39;",$productRS['item_title']);
                    $accosArray[$productRS['prd_id']] = $productRS['item_title'];
                }
     }
    echo json_encode($accosArray);
}


else if (isset($_GET['addMainMenu']))
{
    if (!empty($_POST))
    {
        extract($_POST);
    }
    
    Log::write("Adding new menu - menu_ajax.php", "QUERY -- INSERT INTO menus SET rest_id= ".$_GET['restId'].", menu_name= '".ucfirst(addslashes($txt_menuname))."', menu_desc= '" . addslashes($txt_menudescription) . "', status= 1", 'menu', 1 , 'cpanel');
     mysql_query("INSERT INTO menus SET rest_id= ".$_GET['restId'].", menu_name= '".ucfirst(addslashes($txt_menuname))."', menu_ordering= '".$menu_ordering."', menu_desc= '" . addslashes($txt_menudescription) . "', status= 1");
     $result =  mysql_insert_id();
     echo $result;
}



else if (isset($_GET['AddNewAttribute']))
{
	$mSubCatID = $_GET["SubCatID"];
	$mName = $_GET["Name"];
	$mOptionName = $_GET["OptionName"];
	$mLayout = $_GET["Layout"];
	$mApplySubCat = $_GET["ApplySubCat"];
	$mRequired = $_GET["Required"];
	$Title_Price_Defalt = $_GET["Title_Price_Defalt"];
	$mAdd_to_Price = $_GET["add_to_price"];
        $mPrd_id = $_GET["prd_id"];
        $attrids='';
	
	$mDupQry = "SELECT COUNT(*) AS AttributeCount FROM attribute WHERE TRIM(LOWER(option_name))='".trim(strtolower($mOptionName))."' AND ProductID=".$mPrd_id;

	$mDupRes = mysql_query($mDupQry);
	if (mysql_num_rows($mDupRes)>0)
	{
		$mDupRow = mysql_fetch_object($mDupRes);
		if ($mDupRow->AttributeCount>0) //Attribute with same name already exists
		{
			echo("duplicate");
			return;
		}
	}
	
	$mResult = mysql_query("SELECT IFNULL(MAX(OderingNO), 0) AS OrderNo FROM attribute Where ProductID = ".$mPrd_id);
	$mOrderNoRow = mysql_fetch_object($mResult);
	$mOrderNo = $mOrderNoRow->OrderNo+1;
        $Title_Price_Defalt = substr($Title_Price_Defalt, 1);
        $titarray = explode("|", $Title_Price_Defalt);
        $mAttrFields = $_GET["attrFields"];
        $mExtraCharger = $_GET["extraCharger"];
       
        foreach($titarray as $data)
        {
           $arr = explode("~", $data);
           
               $mTitle = $arr[0];
               $mPrice = $arr[1];
               $mDefault = $arr[2];
               $mPrice = (trim($mPrice)=="NA"?"0":$mPrice);
               $mDefault = (trim($mDefault)=="No"?"0":"1");
               
               if ($mPrice=="undefined")
               {
                        $mPrice=0;
               }
               if($mApplySubCat!=1)
                {   
                   Log::write("Add attribute into new_attribute - menu_ajax.php", "QUERY --INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')", 'menu', 1 , 'cpanel');
                    if (mysql_query("INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')"))
                    {
                            
                            if($mPrd_id!="")
                            {
                                Log::write("Add new attribute - menu_ajax.php", "QUERY -- INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mPrd_id.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')", 'menu', 1 , 'cpanel');
                                mysql_query("INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mPrd_id.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')");
                                $attrids .= mysql_insert_id()."-";
                                Log::write("Set product HasAttributes=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $mPrd_id . "", 'menu', 1 , 'cpanel');
                                mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id = " . $mPrd_id . "");
                            }
                    }
                    else
                    {
                            echo("Failure");
                    }
                }
                else
                {
                    Log::write("Add new attribute into new_attribute - menu_ajax.php", "QUERY -- INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')", 'menu', 1 , 'cpanel');
                    if (mysql_query("INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')"))
                    {
                            $mProduct = mysql_query("Select prd_id from product where sub_cat_id = ".$mSubCatID."");
                            while($mProductID = mysql_fetch_array($mProduct))
                            {
                                Log::write("Add new attribute - menu_ajax.php", "QUERY -- INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mProductID['prd_id'].", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."',".$mExtraCharger.")", 'menu', 1 , 'cpanel');
                                mysql_query("INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mProductID['prd_id'].", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')");
                                if($mProductID['prd_id'] == $mPrd_id)
                                {
                                    $attrids .= mysql_insert_id()."-";
                                }
                                Log::write("Set product HasAttributes=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $mProductID['prd_id'] . "", 'menu', 1 , 'cpanel');
                                mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id = " . $mProductID['prd_id'] . "");
                            }
                    }
                    else
                    {
                            echo("Failure");
                    }
                }
         
        }
        if(!empty($attrids))
        {
            echo substr($attrids,0,-1);
        }
        else
        {
            echo("Failure");
        }
}


else if (isset($_GET['GetAttrbutedataforEdit']))
{
    $mAttribute1 = array();
    $mSubCatID = $_GET['sub_catid'];
    $mOption_Name = $_GET['option_name'];
    $mPrd_id = $_GET['prd_id'];
    $mAttributeQry = mysql_query("Select id, ProductID, REPLACE(option_name, '\'', '&#39;') AS option_name, REPLACE(Title, '\'', '&#39;') AS Title, Price, option_display_preference, apply_sub_cat, Type, Required,OderingNO, rest_price, REPLACE(display_Name, '\'', '&#39;') AS display_Name, add_to_price, REPLACE(attr_name, '\'', '&#39;') AS attr_name, extra_charge ,`Default`+0 AS `Default` from attribute where ProductID = ".$mPrd_id." and option_name = '".addslashes($mOption_Name)."' ORDER BY id");
//    $mAttributeQry = mysql_query("Select *,`Default`+0 AS `Default` from attribute where ProductID = ".$mPrd_id." and option_name = '".addslashes($mOption_Name)."' ORDER BY id");
    while($mAttribute = mysql_fetch_object($mAttributeQry))
    {
        $mAttribute->Default = intval($mAttribute->Default);
        $mAttribute1[] = $mAttribute;
    }
    echo json_encode($mAttribute1);
}



else if (isset($_GET['UpdateAttribute']))
{       
	$mSubCatID = $_GET["SubCatID"];
	$mName = $_GET["Name"];
	$mOptionName = $_GET["OptionName"];
	$mLayout = $_GET["Layout"];
	$mApplySubCat = $_GET["ApplySubCat"];
	$mRequired = $_GET["Required"];
        $Title_Price_Defalt = $_GET["Title_Price_Defalt"];
	$mAdd_to_Price = $_GET["add_to_price"];
        $mOldOptionName = $_GET["oldOptionName"];
        $mPrd_id = $_GET["prd_id"];
        $mAttrFields = $_GET["attrFields"];
        $mExtraCharger = $_GET["extraCharger"];
        $attrids='';

	if (trim(strtolower($mOldOptionName))!=trim(strtolower($mOptionName)))
	{
		$mDupQry = "SELECT COUNT(*) AS AttributeCount FROM attribute WHERE TRIM(LOWER(option_name))='".trim(strtolower($mOptionName))."' AND ProductID=".$mPrd_id;
	
		$mDupRes = mysql_query($mDupQry);
		if (mysql_num_rows($mDupRes)>0)
		{
			$mDupRow = mysql_fetch_object($mDupRes);
			if ($mDupRow->AttributeCount>0) //Attribute with same name already exists
			{
				echo("duplicate");
				return;
			}
		}
	}
        
        $mResult = mysql_query("SELECT OderingNO AS OrderNo FROM attribute Where ProductID = ".$mPrd_id." and option_name = '".$mOldOptionName."'");
	$mOrderNoRow = mysql_fetch_object($mResult);
	$mOrderNo = $mOrderNoRow->OrderNo;
        
        $ProductIDs = array();
        $ProdIDs = array();
        $Title_Price_Defalt = substr($Title_Price_Defalt, 1);
        $titarray = explode("|", $Title_Price_Defalt);


        if($mApplySubCat!=1)
        {
            
            $mQuery = "Delete from attribute where ProductID = ".$mPrd_id." and option_name = '".$mOldOptionName."'";
            mysql_query($mQuery);
            Log::write("Delete Attribute - menu_ajax.php - LINE 1285", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');
        }
        else if($mApplySubCat==1)
        {
            $mProductIdQry = mysql_query("Select distinct(ProductID),OderingNO from attribute where option_name = '".$mOldOptionName."' and ProductID in (Select prd_id from product where sub_cat_id = ".$mSubCatID.")");
            while($prdids = mysql_fetch_array($mProductIdQry))
            {
                $ProductIDs[$prdids['ProductID']][0] = $prdids['ProductID'];
                $ProductIDs[$prdids['ProductID']][1] = $prdids['OderingNO'];
                $ProdIDs[] = $prdids['ProductID'];
            }
            
            $mQuery = "Delete from attribute where ProductID in  (".implode(",",$ProdIDs).") and option_name = '".$mOldOptionName."'";
            mysql_query($mQuery);
            Log::write("Delete Attribute - menu_ajax.php - LINE 1299", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');
        }

        
        foreach($titarray as $data)
        {
           $arr = explode("~", $data);

           $mTitle = $arr[0];
           $mPrice = $arr[1];
           $mDefault = $arr[2];
           $mPrice = (trim($mPrice)=="NA"?"0":$mPrice);
           $mDefault = (trim($mDefault)=="No"?"0":"1");
           if ($mPrice=="undefined")
           {
               $mPrice=0;
           }
            if($mApplySubCat==1)
            {
                foreach($ProductIDs as $id)
                {                
                    Log::write("Set product HasAttributes=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $id[0] . "", 'menu', 1 , 'cpanel');
                    mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id = " . $id[0] . "");
                    Log::write("Add new attribute - menu_ajax.php", "QUERY --INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$id[0].", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$id[1].",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')", 'menu', 1 , 'cpanel');
                    mysql_query("INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$id[0].", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$id[1].",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')");
                    if($id[0] == $mPrd_id)
                    {
                        $attrids .= mysql_insert_id()."-";
                    }
                }
            }
            else
            {
                Log::write("Set product HasAttributes=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $mPrd_id . "", 'menu', 1 , 'cpanel');
                mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id = " . $mPrd_id . "");
                Log::write("Add new attribute - menu_ajax.php", "QUERY -- INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mPrd_id.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')", 'menu', 1 , 'cpanel');
                mysql_query("INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mPrd_id.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')");
                $attrids .= mysql_insert_id()."-";
            }
        }
        if(!empty($attrids))
        {
            echo substr($attrids,0,-1);
        }
        else
        {
            echo("Failure");
        }

}

else if (isset($_GET['AddNewAttributeInCategory']))
{
	$mSubCatID = $_GET["SubCatID"];
	$mName = $_GET["Name"];
	$mOptionName = $_GET["OptionName"];
	$mLayout = $_GET["Layout"];
	$mApplySubCat = $_GET["ApplySubCat"];
	$mRequired = $_GET["Required"];
	$Title_Price_Defalt = $_GET["Title_Price_Defalt"];
        $mAdd_to_Price = $_GET["add_to_price"];
		
	$mDupQry = "SELECT COUNT(*) AS AttributeCount FROM new_attribute WHERE TRIM(LOWER(option_name))='".trim(strtolower($mOptionName))."' AND sub_catid=".$mSubCatID;

	$mDupRes = mysql_query($mDupQry);
	if (mysql_num_rows($mDupRes)>0)
	{
		$mDupRow = mysql_fetch_object($mDupRes);
		if ($mDupRow->AttributeCount>0) //Attribute with same name already exists
		{
			echo("duplicate");
			return;
		}
	}
		
	$mResult = mysql_query("SELECT IFNULL(MAX(OderingNO), 0) AS OrderNo FROM new_attribute Where sub_catid=".$mSubCatID);
	$mOrderNoRow = mysql_fetch_object($mResult);
	$mOrderNo = $mOrderNoRow->OrderNo+1;
        $Title_Price_Defalt = substr($Title_Price_Defalt, 1);
        $titarray = explode("|", $Title_Price_Defalt);
        $mAttrFields = $_GET["attrFields"];
        $mExtraCharger = $_GET["extraCharger"];
        
       // print_r($titarray);exit;
        foreach($titarray as $data)
        {
           $arr = explode("~", $data);


               $mTitle = $arr[0];
               $mPrice = $arr[1];
               $mDefault = $arr[2];
               $mPrice = (trim($mPrice)=="NA"?"0":$mPrice);
               $mDefault = (trim($mDefault)=="No"?"0":"1");
               if ($mPrice=="undefined")
               {
                        $mPrice=0;
               }
               if($mApplySubCat!=1)
               {
                   Log::write("Add new attribute into new_attribute - menu_ajax.php", "QUERY -- INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')", 'menu', 1 , 'cpanel');
                    if (mysql_query("INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')"))
                    {
                            echo(mysql_insert_id());
                    }
               }
               else
               {
                   Log::write("Add new attribute into new_attribute - menu_ajax.php", "QUERY -- INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')", 'menu', 1 , 'cpanel');
                    if (mysql_query("INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')"))
                    {
                            echo(mysql_insert_id());
                            $mProduct = mysql_query("Select prd_id from product where sub_cat_id = ".$mSubCatID."");
                            while($mProductID = mysql_fetch_array($mProduct))
                            {
                                Log::write("Add new attribute - menu_ajax.php", "QUERY --INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mProductID['prd_id'].", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')", 'menu', 1 , 'cpanel');
                                mysql_query("INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mProductID['prd_id'].", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')");
                                Log::write("Set product HasAttributes=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $mProductID['prd_id'] . "", 'menu', 1 , 'cpanel');
                                mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id = " . $mProductID['prd_id'] . "");
                            }
                    }
                    else
                    {
                            echo("Failure");
                    }
               }
        }
}


else if (isset($_GET['GetCategoryAttrbutedataforEdit']))
{
    $mAttribute1 = array();
    $mSubCatID = $_GET['sub_catid'];
    $mOption_Name = $_GET['option_name'];

	$mAttributeQry = mysql_query("Select id, sub_catid, REPLACE(option_name, '\'', '&#39;') AS option_name, REPLACE(Title, '\'', '&#39;') AS Title, Price, option_display_preference, apply_sub_cat, Type, Required,OderingNO, rest_price, REPLACE(display_Name, '\'', '&#39;') AS display_Name, add_to_price, REPLACE(attr_name, '\'', '&#39;') AS attr_name, extra_charge ,`Default`+0 AS `Default` from new_attribute where sub_catid = ".$mSubCatID." and option_name = '".addslashes($mOption_Name)."'");
//	$mAttributeQry = mysql_query("Select *,`Default`+0 AS `Default` from new_attribute where sub_catid = ".$mSubCatID." and option_name = '".$mOption_Name."'");
    while($mAttribute = mysql_fetch_object($mAttributeQry))
    {

        $mAttribute->Default = intval($mAttribute->Default);
        $mAttribute1[] = $mAttribute;
    }
    echo json_encode($mAttribute1);
}


else if (isset($_GET['UpdateAttributeInCategory']))
{
	$mSubCatID = $_GET["SubCatID"];
	$mName = $_GET["Name"];
	$mOptionName = $_GET["OptionName"];
	$mLayout = $_GET["Layout"];
	$mApplySubCat = $_GET["ApplySubCat"];
	$mRequired = $_GET["Required"];
        $Title_Price_Defalt = $_GET["Title_Price_Defalt"];
        $mAdd_to_Price = $_GET["add_to_price"];
        $mOldOptionName = $_GET["oldOptionName"];
        $mAttrFields = $_GET["attrFields"];
        $mExtraCharger = $_GET["extraCharger"];
		
	if (trim(strtolower($mOldOptionName))!=trim(strtolower($mOptionName)))
	{	
		$mDupQry = "SELECT COUNT(*) AS AttributeCount FROM new_attribute WHERE TRIM(LOWER(option_name))='".trim(strtolower($mOptionName))."' AND sub_catid=".$mSubCatID;
		$mDupRes = mysql_query($mDupQry);
		if (mysql_num_rows($mDupRes)>0)
		{
			$mDupRow = mysql_fetch_object($mDupRes);
			if ($mDupRow->AttributeCount>0) //Attribute with same name already exists
			{
				echo("duplicate");
				return;
			}
		}
	}
        
        $mResult = mysql_query("SELECT IFNULL(MAX(OderingNO), 0) AS OrderNo FROM new_attribute Where sub_catid=".$mSubCatID);
	$mOrderNoRow = mysql_fetch_object($mResult);
	$mOrderNo = $mOrderNoRow->OrderNo+1;

        $ProductIDs = array();
        $Title_Price_Defalt = substr($Title_Price_Defalt, 1);
        $titarray = explode("|", $Title_Price_Defalt);
             Log::write("Delete attribute from new_attribute - menu_ajax.php", "QUERY --Delete from new_attribute where sub_catid = ".$mSubCatID." and option_name = '".$mOldOptionName."'", 'menu', 1 , 'cpanel');
             mysql_query("Delete from new_attribute where sub_catid = ".$mSubCatID." and option_name = '".$mOldOptionName."'");
        
        foreach($titarray as $data)
        {
           $arr = explode("~", $data);

           $mTitle = $arr[0];
           $mPrice = $arr[1];
           $mDefault = $arr[2];
           $mPrice = (trim($mPrice)=="NA"?"0":$mPrice);
           $mDefault = (trim($mDefault)=="No"?"0":"1");
           if ($mPrice=="undefined")
           {
               $mPrice=0;
           }
           
            if($mApplySubCat!=1)
            {
            Log::write("Add new attribute - menu_ajax.php", "QUERY --INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",$mAdd_to_Price,'".$mAttrFields."','".$mExtraCharger."')", 'menu', 1 , 'cpanel');
            mysql_query("INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",$mAdd_to_Price,'".$mAttrFields."','".$mExtraCharger."')");
            echo(mysql_insert_id());
            }
            else
            {
                
            $mQuery = "Delete from attribute where ProductID in (Select prd_id from product where sub_cat_id = ".$mSubCatID.") and option_name = '".$mOldOptionName."'";
            mysql_query($mQuery);
            Log::write("Delete attribute - menu_ajax.php - LINE 1511", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');

                Log::write("Insert attribute into new_attribute - menu_ajax.php", "QUERY --INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."',".$mExtraCharger.")", 'menu', 1 , 'cpanel');
                if (mysql_query("INSERT INTO new_attribute (sub_catid, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mSubCatID.", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')"))
                {
                        echo(mysql_insert_id());
                        $mProduct = mysql_query("Select prd_id from product where sub_cat_id = ".$mSubCatID."");
                        while($mProductID = mysql_fetch_array($mProduct))
                        {
                            Log::write("Add new attribute - menu_ajax.php", "QUERY --INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mProductID['prd_id'].", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."',".$mExtraCharger.")", 'menu', 1 , 'cpanel');
                            mysql_query("INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type, Required, OderingNO, rest_price, display_Name, `Default`, add_to_price,attr_name,extra_charge) VALUES (".$mProductID['prd_id'].", '".$mOptionName."', '".$mTitle."', '".$mPrice."', 0, ".$mApplySubCat.", ".$mLayout.", ".$mRequired.", ".$mOrderNo.",'', '".$mName."', ".$mDefault.",".$mAdd_to_Price.",'".$mAttrFields."','".$mExtraCharger."')");
                            Log::write("Set product HasAttributes=1 - menu_ajax.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $mProductID['prd_id'] . "", 'menu', 1 , 'cpanel');
                            mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id = " . $mProductID['prd_id'] . "");
                        }
                }
                else
                {
                        echo("Failure");
                }
            }
        }

}


else if (isset($_GET['check_availability']))
{
    $username = $_GET['username'];
    $subcatid = $_GET['subcatid'];
    $result = mysql_query("select display_Name from new_attribute where display_Name = '". $username ."' and sub_catid = ".$subcatid."");

    if(mysql_num_rows($result)>0)
    {
        echo 0;
    }
    else
    {
        echo 1;
    }
}
else if (isset($_GET['check_availabilityforItem']))
{
    $username = $_GET['username'];
    $subcatid = $_GET['subcatid'];
    $result = mysql_query("select display_Name from new_attribute where display_Name = '". $username ."' and sub_catid = ".$subcatid."");

    if(mysql_num_rows($result)>0)
    {
        echo 'inCategory';
    }
    else if(mysql_num_rows($result)==0)
    {
        $result1 = mysql_query("select display_Name from attribute where display_Name = '". $username ."' and ProductID in (select prd_id from product where sub_cat_id = ".$subcatid.")");
        if(mysql_num_rows($result1)>0)
        {
            echo 'inProduct';
        }
        else
        {
            echo 1;
        }
    }
    else 
    {
        echo 1;
    }
}
//Update Attribute Sort Order
else if (isset($_GET['updateAttributeOrder']))
{
    $prd_id = $_GET['prd_id'];
    $attributes_Name = $_GET['attrbutes'];
    $attr_array = explode("|", $attributes_Name);
    $order = 0;
    foreach($attr_array as $data)
    {
       $attributeIds = str_replace("-",",", $data);
       if($attributeIds != "")
       {
           $result = mysql_query("update attribute set OderingNO = ".$order." where ProductID = ".$prd_id." and id in (".$attributeIds.")");
       }
       $order++;
    }
    if(!$result)
    {
        echo "error occured";
    }
    else
    {
        echo $result;
    }
    
}
//Update Attribute Sort Order
else if (isset($_GET['updateComplimentryOrder']))
{
    $associations = $_GET['associations'];
    $assoc_array = explode("|", $associations);
    $order = 0;
    foreach($assoc_array as $data)
    {
       $arr = explode("~", $data);
       if($arr[0] != "")
       {
           $association_id = $arr[0];
           $result = mysql_query("update product_association set sortOrder = ".$order." where product_id = ".$_GET['prd_id']." and association_id = '".$association_id."'");
       }
       $order++;
    }
    echo $result;
}
else if (isset($_GET["menuonoff"]))
{
	$mMenuID = $_GET["menuid"];
	$mStatus = $_GET["status"];
	
	$mNewStatus = 0;
	
	if (trim($mStatus)==0 || trim($mStatus)=="0")
	{
		$mNewStatus = 1;
	}
	
	
	if (is_numeric($mMenuID) && is_numeric($mStatus))
	{
		if (mysql_query("UPDATE menus SET status=".$mNewStatus." WHERE id=".$mMenuID))
		{
			echo($mNewStatus);
		}
		else
		{
			echo("Error occurred.");
		}
	}
	else
	{
		echo("Error");
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

?>
