<?php
    $spnHTML = "";
    $mMessage = "";
    $confirmDisplay = " display: none; ";
    if (isset($_POST["btnSubmit"]))
    {
        require_once "includes/config.php";
        if ((trim($_POST["txtSource"])!="") && (trim($_POST["txtDestination"])!=""))
        {
            if ((is_numeric($_POST["txtSource"])) && (is_numeric($_POST["txtDestination"])))
            {
                $mSQL = "SELECT M.menu_name AS MenuName, R.id AS RestaurantID, R.name AS RestaurantName FROM menus M INNER JOIN resturants R ON R.id = M.rest_id WHERE M.id=".$_POST["txtSource"];
                $mRes = mysql_query($mSQL);
                
                $mSQL = "SELECT M.menu_name AS MenuName, R.id AS RestaurantID, R.name AS RestaurantName FROM menus M INNER JOIN resturants R ON R.id = M.rest_id WHERE M.id=".$_POST["txtDestination"];
                $mRes1 = mysql_query($mSQL);
                
                if ((mysql_num_rows($mRes)>0) && (mysql_num_rows($mRes1)>0))
                {
                    $mRow = mysql_fetch_object($mRes);
                    $mRow1 = mysql_fetch_object($mRes1);
                    
                    /*if ($mRow->RestaurantID==$mRow1->RestaurantID)
                    {*/
                    
                        $spnHTML = "<span style='font-weight: bold; font-size: 16px; color: maroon'>Source Details</span><br />";
                        $spnHTML .= "<strong>Restaurant Name:&nbsp;</strong>".$mRow->RestaurantName."<br />";
                        $spnHTML .= "<strong>Menu Name:&nbsp;</strong>".$mRow->MenuName."<br /><br />";

                        $spnHTML .= "<span style='font-weight: bold; font-size: 16px; color: maroon'>Destination Details</span><br />";
                        $spnHTML .= "<strong>Restaurant Name:&nbsp;</strong>".$mRow1->RestaurantName."<br />";
                        $spnHTML .= "<strong>Menu Name:&nbsp;</strong>".$mRow1->MenuName."<br /><br />";

                        $confirmDisplay = " display: inline; ";
                    /*}
                    else
                    {
                        $mMessage = "Source and destination menus should be of same restaurat.";
                    }*/
                }
                else
                {
                    $mMessage = "Source or Destination menu details not found.";
                }
            }
            else
            {
                $mMessage = "Invalid Source or Destination menu id.";
            }
        }
        else
        {
            $mMessage = "Source and Destination menu ids are required.";
        }
    }
    else if (isset($_POST["btnConfirm"]))
    {
        require_once "includes/config.php";
        
        $mSQL = "SELECT Column1Count FROM menus WHERE id=".$_POST["txtSource"];
        $mRes = mysql_query($mSQL);
        $mRow = mysql_fetch_object($mRes);
        $mSQL = "UPDATE menus SET Column1Count='".$mRow->Column1Count."' WHERE id=".$_POST["txtDestination"];
        mysql_query($mSQL);
        
        $mSQL = "SELECT cat_id, parent_id, menu_id, cat_name, cat_des, cat_ordering, `status` FROM categories WHERE menu_id = ".$_POST["txtSource"];
        $mRes = mysql_query($mSQL);
        if (mysql_num_rows($mRes)>0)
        {
            while ($mRow = mysql_fetch_object($mRes))
            {
                $mSQL = "INSERT INTO categories (parent_id, menu_id, cat_name, cat_des, cat_ordering, `status`)";
                $mSQL .= " VALUES (".$mRow->parent_id.", ".$_POST["txtDestination"].", '".mysql_real_escape_string($mRow->cat_name)."', '".mysql_real_escape_string($mRow->cat_des)."', ".$mRow->cat_ordering.", ".$mRow->status.")";
                if (mysql_query($mSQL))
                {
                    $mNewCatId=mysql_insert_id();

                    $mSQL = "SELECT prd_id, cat_id, sub_cat_id, item_num, item_title, item_code, item_des, retail_price, sale_price, item_image, feature_sub_cat, Alt_tag, Ptitle, Meta_des, Meta_tag, imagethumb, status, pos_id, item_type, SortOrder, HasAttributes, HasAssociates, UpdatedOn FROM product WHERE sub_cat_id =".$mRow->cat_id;
                    $mRes1 = mysql_query($mSQL);

                    while ($mRow1 = mysql_fetch_object($mRes1))
                    {
                        $mSQL = "INSERT INTO product(cat_id, sub_cat_id, item_num, item_title, item_code, item_des, retail_price, sale_price, item_image, feature_sub_cat, Alt_tag, Ptitle, Meta_des, Meta_tag, imagethumb, `status`, item_type, SortOrder, HasAttributes, HasAssociates, UpdatedOn, pos_id)";
                        $mSQL .= " VALUES (".$mRow1->cat_id.", ".$mNewCatId.", '".$mRow1->item_num ."', '".mysql_real_escape_string($mRow1->item_title)."', '".mysql_real_escape_string($mRow1->item_code)."', '".mysql_real_escape_string($mRow1->item_des)."', '".mysql_real_escape_string($mRow1->retail_price)."', '".mysql_real_escape_string($mRow1->sale_price)."', '".mysql_real_escape_string($mRow1->item_image)."', ".$mRow1->feature_sub_cat.", '".mysql_real_escape_string($mRow1->Alt_tag)."', '".mysql_real_escape_string($mRow1->Ptitle)."', '".mysql_real_escape_string($mRow1->Meta_des)."', '".mysql_real_escape_string($mRow1->Meta_tag)."', '".mysql_real_escape_string($mRow1->imagethumb)."', ".$mRow1->status .", '".mysql_real_escape_string($mRow1->item_type)."', '".$mRow1->SortOrder."', '".$mRow1->HasAttributes."', '".$mRow1->HasAssociates."', '".$mRow1->UpdatedOn."', '".$mRow1->pos_id."')";
                        if (mysql_query($mSQL))
                        {
                            $mNewProductID=mysql_insert_id();

                            $mSQL = "INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, `Type`, `Required`, OderingNO, rest_price, display_Name, `Default`, attr_name, extra_charge, add_to_price) SELECT ".$mNewProductID.", option_name, Title, Price, option_display_preference, apply_sub_cat, `Type`, `Required`, OderingNO, rest_price, display_Name, `Default`, attr_name, extra_charge, add_to_price FROM attribute WHERE ProductID=".$mRow1->prd_id." ORDER BY id";
                            if (mysql_query($mSQL))
                            {
                                $mSQL = "INSERT INTO product_association (product_id, association_id, SortOrder)  SELECT ".$mNewProductID.", association_id, SortOrder FROM product_association WHERE product_id =".$mRow1->prd_id;
                                if (mysql_query($mSQL))
                                {
                                    $mMessage = "Menu copied successfully.";
                                }
                                else
                                {
                                    $mMessage = "Error occurred while creating associates.";
                                }
                            }
                            else
                            {
                                $mMessage = "Error occurred while creating attributes.";
                            }
                        }
                        else
                        {
                            $mMessage = "Error occurred while creating items.";
                        }
                    }
                }
                else
                {
                    $mMessage = "Error occurred while creating submenus.";
                }
            }
        }
        else
        {
            $mMessage = "No sub menus in Source menu.";
        }
    }
?>
<html dir="ltr" lang="en-US">
<head>
    <title>Copy Menu</title>
</head>
<style type="text/css">
    strong
    {
        font-family: Arial; 
        font-size: 14px;
    }
    
    input
    {
        font-family: Arial; 
        font-size: 14px;
    }
</style>
<body style="font-family: Arial; font-size: 13px;">
    <form id="frmCopy" name="frmCopy" action="" method="post">
        <table style="width: 100%;" cellpadding="0" cellspacing="0" border="0">
            <tr style="height: 200px;">
                <td colspan="3">
                    
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    
                </td>
                <td style="width: 75%;" colspan="2">
                    <span style="color: red;"><?=$mMessage?></span>
                </td>
            </tr>
            <tr style="height: 30px;">
                <td colspan="3">
                    
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    
                </td>
                <td style="width: 15%;">
                    <strong>Source Menu ID</strong>
                </td>
                <td style="width: 60%;">
                    <input type="text" id="txtSource" name="txtSource" value="<?=(isset($_POST["txtSource"])?$_POST["txtSource"]:"")?>" />
                </td>
            </tr>
            <tr style="height: 10px;">
                <td colspan="3">
                    
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    
                </td>
                <td style="width: 15%;">
                    <strong>Destination Menu ID</strong>
                </td>
                <td style="width: 60%;">
                    <input type="text" id="txtDestination" name="txtDestination" value="<?=(isset($_POST["txtSource"])?$_POST["txtDestination"]:"")?>" />
                </td>
            </tr>
            <tr style="height: 20px;">
                <td colspan="3">
                    
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    
                </td>
                <td style="width: 15%;">
                    
                </td>
                <td style="width: 60%;">
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Copy" style="width: 150px;" />
                </td>
            </tr>
             <tr style="height: 30px;">
                <td colspan="3">
                    
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    
                </td>
                <td style="width: 75%;" colspan="2">
                    <?=$spnHTML?>
                </td>
            </tr>
            <tr style="height: 10px;">
                <td colspan="3">
                    
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    
                </td>
                <td style="width: 15%;">
                    
                </td>
                <td style="width: 60%;">
                    <input type="submit" id="btnConfirm" name="btnConfirm" value="Confirm Copy" style="width: 150px; <?=$confirmDisplay?>" />
                </td>
            </tr>
        </table>
    </form>
</body>
<?php
    @mysql_close($mysql_conn);
?>