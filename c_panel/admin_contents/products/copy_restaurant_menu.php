<?php
require_once ("../../../includes/config.php");
$menu_id = $_REQUEST['hdnMenu_id'];
if(isset($_REQUEST['restaurantcheck']))
{
    $rest_ids = $_REQUEST['restaurantcheck'];
    foreach($rest_ids as $id )
    {
        mysql_query("Delete from menus where id =".$menu_id ." and rest_id = ".$id."");
        mysql_query("Delete from categories where menu_id = ".$menu_id." and rest_id = ".$id."");

        $mysqlqry=mysql_query("select * from menus where id =".$menu_id ."");

         while($menu_rs = mysql_fetch_object($mysqlqry)){
                 mysql_query("insert into menus(rest_id,menu_name,menu_desc,menu_ordering,status) values (
                 '". $id ."','". $menu_rs->menu_name ."','". $menu_rs->menu_desc ."','". $menu_rs->menu_ordering ."'
                 ,'". $menu_rs->status ."')");

                 $newMenuId=mysql_insert_id();

                   $mysqlcatqry=mysql_query("select * from categories where menu_id =".$menu_rs->id ."  Order by  cat_id");

                                while($cat_rs = mysql_fetch_object($mysqlcatqry)){

                                 mysql_query("insert into categories(`parent_id`, `menu_id`, `cat_name`, `cat_des`, `cat_ordering`, `status`) values (
                                                 '". $id ."','". $newMenuId ."','". $cat_rs->cat_name ."','". $cat_rs->cat_des ."','". $cat_rs->cat_ordering ."'
                                                 ,'". $cat_rs->status ."')");

                                                  $newCatId=mysql_insert_id();

                                                        $mysql_cat_products=mysql_query("select * from product where sub_cat_id =".$cat_rs->cat_id ." Order by  prd_id");
                                                                while($product_rs = mysql_fetch_object($mysql_cat_products)){

                                                                mysql_query("insert into product(
                                                                `cat_id`, `sub_cat_id`, `item_num`, `item_title`, `item_code`, `item_des`, `retail_price`, `sale_price`, `item_image`, `feature_sub_cat`, `Alt_tag`, `Ptitle`, `Meta_des`, `Meta_tag`, `imagethumb`, `status`,`item_type`

                                                                ) values ( '". $id ."', '". $newCatId ."', '". $product_rs->item_num ."'
                                                                , '". $product_rs->item_title ."'
                                                                , '". $product_rs->item_code ."'
                                                                , '". $product_rs->item_des ."'
                                                                , '". $product_rs->retail_price ."'
                                                                , '". $product_rs->sale_price ."'
                                                                , '". $product_rs->item_image ."'
                                                                , '". $product_rs->feature_sub_cat ."'
                                                                , '". $product_rs->Alt_tag ."'
                                                                , '". $product_rs->Ptitle ."'
                                                                , '". $product_rs->Meta_des ."'
                                                                , '". $product_rs->Meta_tag ."'
                                                                , '". $product_rs->imagethumb ."'
                                                                , '". $product_rs->status ."'
                                                                , '" . $product_rs->item_type . "'


                                                                                         )");

                                                               $newProductID=mysql_insert_id();
                                                                mysql_query("insert into attribute(`ProductID`, `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price` )  select " . $newProductID . ", `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price` from attribute where ProductID=" . $product_rs->prd_id . "");
                                                                mysql_query("insert into product_association(`product_id`, `association_id` )  select " . $newProductID . ", `association_id`  from product_association where product_id =" . $product_rs->prd_id . "");

                                                                }
                                }
         }
    }
}
header('Location:'. $AdminSiteUrl.'?mod=new_menu&catid='.$_REQUEST['hdnRestaurantID'].'&menuid='.$_REQUEST['hdnMenu_id'].'&menu_name='.$_REQUEST['hdnMenuName']);exit;
?>
