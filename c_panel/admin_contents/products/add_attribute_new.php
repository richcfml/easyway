<?php 
require_once ("../../../includes/config.php");

if (strpos($_SERVER['HTTP_REFERER'], 'product') != true) 
{
    $scat_id = $_REQUEST['subcat_id'];
	
    $mMenuIDRes = dbAbstract::Execute("SELECT menu_id FROM categories WHERE cat_id = ".$scat_id,1);
    $mMenuIDRow = dbAbstract::returnObject($mMenuIDRes,1);
    $mMenuID = $mMenuIDRow->menu_id;
	
    $attribute_id = $_GET['itemcheckAttribute'];

    $mQuery = "DELETE FROM attribute where ProductID in (Select prd_id from product where sub_cat_id  IN (SELECT cat_id FROM categories WHERE menu_id=".$mMenuID."))";
    dbAbstract::Delete($mQuery,1);
    Log::write("Delete attribute - add_attribute_new.php - LINE 16", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');

    $prodQry = dbAbstract::Execute("select prd_id from product where sub_cat_id= $scat_id",1);
    while ($prodRs = dbAbstract::returnArray($prodQry,1)) 
    {
        for ($i = 0; $i < count($_GET['itemcheckAttribute']); $i++) 
        {
            $getattr = dbAbstract::Execute("Select * from new_attribute where display_Name = '" . $attribute_id[$i] . "' and  sub_catid  IN (SELECT cat_id FROM categories WHERE menu_id=".$mMenuID.")",1);
            while ($attr = dbAbstract::returnArray($getattr,1)) 
            {
                $query = "INSERT INTO attribute SET productid = '" . $prodRs['prd_id'] . "', option_name = '" . $attr['option_name'] . "',Title = '" . $attr['Title'] . "',Price = '" . $attr['Price'] . "',option_display_preference = '" . $attr['option_display_preference'] . "',apply_sub_cat=1,Type	 = '" . $attr['Type'] . "',Required = '" . $attr['Required'] . "',OderingNO = '" . $attr['OderingNO'] . "',rest_price = '" . $attr['rest_price'] . "',display_Name = '".$attr['display_Name']."'";
                Log::write("Add new attribute - add_attribute_new.php - IF", "QUERY --".$query, 'menu', 1 , 'cpanel');
		dbAbstract::Insert($query,1);
                Log::write("Set product HasAttributes=1 - add_attribute_new.php - IF", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $prodRs['prd_id'] . "", 'menu', 1 , 'cpanel');
                dbAbstract::Update("UPDATE product set HasAttributes=1 WHERE prd_id = " . $prodRs['prd_id'] . "",1);
            }
        }
   }
   ?>
    <script type="text/javascript" language="javascript">
        var sPageURL = '';

        sPageURL = '<?= $_SERVER['HTTP_REFERER']?>';
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == "catid")
            {
                catid =  sParameterName[1];
            }
            else if (sParameterName[0] == "menuid")
            {
                menu_id =  sParameterName[1];
            }
            else if (sParameterName[0] == "menu_name")
            {
                menu_name =  sParameterName[1];
            }
        }
        window.location.href = "<?= $AdminSiteUrl ?>?mod=new_menu&catid="+catid+"&menuid="+menu_id+"&menu_name="+menu_name;
    </script>
<?php
}
else 
{   
    $mQuery = "DELETE FROM attribute where ProductID  = '" . $_GET['prod_id'] . "' ";
    dbAbstract::Execute($mQuery,1);    
    Log::write("Delete attribute - add_attribute_new.php - LINE 64", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');
    
    $scat_id = $_REQUEST['subcat_id'];
	
    $mMenuIDRes = dbAbstract::Execute("SELECT menu_id FROM categories WHERE cat_id = ".$scat_id,1);
    $mMenuIDRow = dbAbstract::returnObject($mMenuIDRes,1);
    $mMenuID = $mMenuIDRow->menu_id;

    $attribute_id = $_GET['itemcheckAttribute'];
    for ($i = 0; $i < count($_GET['itemcheckAttribute']); $i++) 
    {
        $getattr = dbAbstract::Execute("Select *,`Default`+0 AS `Default1` from new_attribute where display_Name = '" . $attribute_id[$i] . "' and  sub_catid IN (SELECT cat_id FROM categories WHERE menu_id=".$mMenuID.")",1);
        while ($attr = dbAbstract::returnArray($getattr,1)) 
        {
            $default = $attr['Default1']?1:0;
            $query = "INSERT INTO attribute SET productid = '" . $_GET['prod_id'] . "', option_name = '" . $attr['option_name'] . "',Title = '" . $attr['Title'] . "',Price = '" . $attr['Price'] . "',option_display_preference = '" . $attr['option_display_preference'] . "',apply_sub_cat=0,Type	 = '" . $attr['Type'] . "',Required = '" . $attr['Required'] . "',OderingNO = '" . $attr['OderingNO'] . "',rest_price = '" . $attr['rest_price'] . "',display_Name='" . $attr['display_Name'] . "' ,`Default`= " . $default . " ,add_to_price='" . $attr['add_to_price'] . "',attr_name ='" . $attr['attr_name'] . "',extra_charge ='" . $attr['extra_charge'] . "'";
            Log::write("Add new attribute - add_attribute_new.php - ELSE", "QUERY --".$query, 'menu', 1 , 'cpanel');
	    dbAbstract::Insert($query,1);
            Log::write("Set product HasAttributes=1 - add_attribute_new.php - ELSE", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $_GET['prod_id'] . "", 'menu', 1 , 'cpanel');
            dbAbstract::Update("UPDATE product set HasAttributes=1 WHERE prd_id = " . $_GET['prod_id'] . "",1);
        }
    }
    ?>
<script type="text/javascript" language="javascript">
    window.location.href = "<?= $AdminSiteUrl ?>?mod=new_menu&item=updateproduct_new&prd_id=<?= $_GET['prod_id'] ?>&sub_cat=<?= $_GET['subcat_id'] ?>";
</script>
<?php
}
?>
<?php mysqli_close($mysqli);?>
