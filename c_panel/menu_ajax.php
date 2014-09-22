<?php

include("../includes/config.php");
include("includes/function.php");
require("includes/SimpleImage.php");
require("includes/snapshot.class.php");
// Delete sub menu item
if (isset($_GET['prd_id'])) {
    $prd_id = $_GET['prd_id'];
    Log::write("Delete Product - c_panel/menu_ajax.php", "QUERY -- Delete from Product where prd_id = " . $prd_id . "", 'menu', 1 , 'cpanel');
    $result = mysql_query("Delete from Product where prd_id = " . $prd_id . "");
    echo $result;
}

// add sub menu
if (isset($_GET['add'])) {
    if (!empty($_POST)) {
        extract($_POST);
        //print_r($_POST);exit;
    }
    $query = mysql_query("SELECT * FROM categories where menu_id=5");
    while ($select = mysql_fetch_array($query)) {
        Log::write("Update category - menu_ajax.php", "QUERY --UPDATE categories SET cat_ordering='$select[cat_ordering]'+1 where cat_id = '$select[cat_id]'", 'menu', 1 , 'cpanel');
        $update = mysql_query("UPDATE categories SET cat_ordering='$select[cat_ordering]'+1 where cat_id = '$select[cat_id]'");
    }
    Log::write("Add new category - menu_ajax.php", "QUERY --INSERT INTO categories SET parent_id= 141, menu_id=5, cat_name= '" . addslashes($submenu_name) . "', cat_ordering= 1, cat_des= '" . addslashes($description) . "'", 'menu', 1 , 'cpanel');
    $result = mysql_query("INSERT INTO categories SET parent_id= 141, menu_id=5, cat_name= '" . addslashes($submenu_name) . "', cat_ordering= 1, cat_des= '" . addslashes($description) . "'");
    echo $result;


    //update sub menu 
} else if (isset($_GET['update'])) {
    if (!empty($_POST)) {
        extract($_POST);
        //print_r($_POST);exit;
    }
    Log::write("Update category - menu_ajax.php", "QUERY --UPDATE categories SET cat_name='" . addslashes($submenu_name) . "', cat_des= '" . addslashes($description) . "' where cat_id = '$hdnCatid'", 'menu', 1 , 'cpanel');
    $result = mysql_query("UPDATE categories SET cat_name='" . addslashes($submenu_name) . "', cat_des= '" . addslashes($description) . "' where cat_id = '$hdnCatid'");
    echo $result;

    // upload sub menu item image
} else if (isset($_GET['imgupload'])) {
    $myimage = new ImageSnapshot;
    if (isset($_FILES['file-0']))
        $myimage->ImageField = $_FILES['file-0'];
//    if (!empty($_FILES['file-0']['name']) && $myimage->ProcessImage() == false) {
//
//    }
    if (!empty($_FILES['file-0']['name']) && $myimage->ProcessImage() != false) {

        $path = 'img/';
        $exe = GetFileExt($_FILES['file-0']['name']);
        //$name = "img_" . $lastid . "_prd." . $exe;
        $name = $_FILES['file-0']['name'];
        $uploadfile = $path . $name;
        move_uploaded_file($_FILES['file-0']['tmp_name'], $uploadfile);
        list($width, $height, $type, $attr) = getimagesize("$uploadfile");
        if ($height > $width) {
            $image = new SimpleImage();
            $image->load($uploadfile);
            $image->resizeToHeight(500);
            $image->save($uploadfile);
        } else {
            $image = new SimpleImage();
            $image->load($uploadfile);
            $image->resizeToWidth(500);
            $image->save($uploadfile);
        }
        if ($_FILES['file-0']['error'] == 0) {
            echo $_FILES['file-0']['name'];
        }
        //mysql_query("UPDATE product set item_image = '$name' where prd_id = ".$lastid);
    }
} else if (isset($_GET['add_menu_item']) && isset($_GET['ext'])) {
    $exe = array_pop(explode(".", $_GET['ext']));
    $sub_cat = $_GET['scid'];

    extract($_POST);
    $feature_subcat = 0;
    $string = rtrim(implode(',', $_POST['type']), ',');
    Log::write("Add new product - menu_ajax.php", "QUERY -- INSERT INTO product set cat_id = 141, sub_cat_id = $sub_cat,item_title = '" . addslashes($item_name) . "', item_des = '" . addslashes($description) . "', retail_price = '$price', feature_sub_cat = $feature_subcat,item_type='" . $string . "'", 'menu', 1 , 'cpanel');
    mysql_query("INSERT INTO product set cat_id = 141, sub_cat_id = $sub_cat,item_title = '" . addslashes($item_name) . "', item_des = '" . addslashes($description) . "', retail_price = '$price', feature_sub_cat = $feature_subcat,item_type='" . $string . "'");
    $lastid = mysql_insert_id();
    $name = "img_" . $lastid . "_prd." . $exe;
    //echo $name;
    Log::write("Update Product - menu_ajax.php", "QUERY -- UPDATE product set item_image = '$name' where prd_id = " . $lastid, 'menu', 1 , 'cpanel');
    mysql_query("UPDATE product set item_image = '$name' where prd_id = " . $lastid);
    $destination_dir = "../images/item_images/"; //path of the destination directory
    $source_dir = "img";
    $source_img_path = $source_dir . "/" . $_GET['ext'];
    $destination_img_path = $destination_dir . "/" . $name;
    copy($source_img_path, $destination_img_path);
    echo $lastid;
    //print_r($_POST['type']);
}

function GetFileExt($fileName) {
    $ext = substr($fileName, strrpos($fileName, '.') + 1);
    $ext = strtolower($ext);
    return $ext;
}

?>
