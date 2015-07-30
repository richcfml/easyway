<?php require_once ("../includes/config.php");
        $id = $_GET['menu_id'];
        $menu_id = $_GET['rest_id'];
        $mysqlqry=dbAbstract::Execute("select * from menus where id =".$menu_id ."",1);

         while($menu_rs = dbAbstract::returnObject($mysqlqry,1)){
                 $newMenuId = dbAbstract::Insert("insert into menus(rest_id,menu_name,menu_desc,menu_ordering,status) values (
                 '". $id ."','". prepareStringForMySQL($menu_rs->menu_name) ."','". prepareStringForMySQL($menu_rs->menu_desc) ."','". $menu_rs->menu_ordering ."'
                 ,'". $menu_rs->status ."')",1,2);

               $mysqlcatqry=dbAbstract::Execute("select * from categories where menu_id =".$menu_rs->id ."  Order by  cat_id",1);

                while($cat_rs = dbAbstract::returnObject($mysqlcatqry)){

                 $newCatId = dbAbstract::Insert("insert into categories(`parent_id`, `menu_id`, `cat_name`, `cat_des`, `cat_ordering`, `status`) values (
                                                 '". $id ."','". $newMenuId ."','". prepareStringForMySQL($cat_rs->cat_name) ."','". prepareStringForMySQL($cat_rs->cat_des) ."','". $cat_rs->cat_ordering ."'
                                                 ,'". $cat_rs->status ."')",1,2);

                    $mysql_cat_products=dbAbstract::Execute("select * from product where sub_cat_id =".$cat_rs->cat_id ." Order by  prd_id",1);
                            while($product_rs = dbAbstract::returnObject($mysql_cat_products,1)){

                            $newProductID = dbAbstract::Insert("insert into product(
                            `cat_id`, `sub_cat_id`, `item_num`, `item_title`, `item_code`, `item_des`, `retail_price`, `sale_price`, `item_image`, `feature_sub_cat`, `Alt_tag`, `Ptitle`, `Meta_des`, `Meta_tag`, `imagethumb`, `status`,`item_type`,`SortOrder`
                            ) values ( '". $id ."', '". $newCatId ."', '". $product_rs->item_num ."'
                            , '". prepareStringForMySQL($product_rs->item_title) ."'
                            , '". $product_rs->item_code ."'
                            , '". prepareStringForMySQL($product_rs->item_des) ."'
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
                            , '" . $product_rs->SortOrder . "')",1,2);
                            dbAbstract::Insert("insert into attribute(`ProductID`, `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` )  select " . $newProductID . ", `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` from attribute where ProductID=" . $product_rs->prd_id . "",1);
                            dbAbstract::Insert("insert into new_attribute(`sub_catid`, `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` )  select " . $newCatId . ", `option_name`, `Title`, `Price`, `option_display_preference`, `apply_sub_cat`, `Type`, `Required`, `OderingNO`, `rest_price`,`display_Name`,`Default`,`add_to_price`,`attr_name`,`extra_charge` from attribute where sub_catid=" . $cat_rs->cat_id . "",1);
                            //----------------------Copy attribute Start-----------------------------------------------
                            dbAbstract::Insert("insert into product_association(`product_id`, `association_id` )  select " . $newProductID . ", `association_id`  from product_association where product_id =" . $product_rs->prd_id . "",1);
                            dbAbstract::Update("UPDATE product set HasAttributes=1,HasAssociates=1 WHERE prd_id = " . $newProductID . "",1);
                            //----------------------Copy attribute End-----------------------------------------------

                            }
                }
         }

?>
<?php mysqli_close($mysqli);?>