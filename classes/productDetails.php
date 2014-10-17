<?php

/**
 * This class contains function to fetch products details with associations and attributes
 *
 * @author M.Mursaleen Javed
 */
class productDetails {

    public $associations;
    public $attributes;
    public $prd_id;
    public $hello = "hello world";

    function __construct() {
        $associations = array();
        $attributes = array();
        $distinct_attributes = array();
    }

    public function associations($id) {
        $cat_qry = mysql_query("SELECT prd_id, cat_id, sub_cat_id, item_num,status, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(item_title, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS item_title, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(item_code, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS item_code, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(item_des, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS item_des, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(retail_price, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS retail_price, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(sale_price, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS sale_price, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(item_image, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS item_image, feature_sub_cat, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Alt_tag, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS Alt_tag, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Ptitle, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS Ptitle, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Meta_des, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS Meta_des, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Meta_tag, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS Meta_tag, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(imagethumb, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS imagethumb, status, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(pos_id, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS pos_id, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(item_type, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS item_type, SortOrder, HasAttributes, HasAssociates, UpdatedOn FROM product WHERE prd_id =" . $id);

        while ($productDetails = mysql_fetch_object($cat_qry)) {
            $prodDetails[] = $productDetails;
        }
        return $prodDetails;
    }

    public function getattributes($product_id) {
        $cat_qry = mysql_query("SELECT IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(attr_name, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS attr_name, extra_charge, `id`, `ProductID`, IFNULL(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(option_name, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', ''), '') AS option_name, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`Title`, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', '') AS Title, TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Price, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', '')) AS Price, `Type`, `Required`, `rest_price`, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`display_Name`, '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', '') AS display_Name, `add_to_price` , `Default`+0 AS `Default` FROM attribute  WHERE ProductID = ". $product_id  ." Order by OderingNO, id");
        $attributes_array = array();
        $distinct_attributes_array = array();
        while ($attribute = mysql_fetch_object($cat_qry, 'attribute')) {
            if (!isset($distinct_attributes_array[$attribute->option_name])) {
                $distinct_attributes_array[$attribute->option_name] = $attribute;
                $distinct_attributes_array[$attribute->option_name]->attributes = array();
            } else {
                $distinct_attributes_array[$attribute->option_name]->attributes[$attribute->id] = $attribute;
            }
        }
        return $distinct_attributes_array;
    }

    public function getproductdetails($product_id) {
        $firstindex = TRUE;
        $productDs = mysql_query("SELECT * FROM product_association WHERE product_id =". $product_id  ." order by sortOrder");
        $arr_product_list = array();
        while ($productDetails = mysql_fetch_object($productDs)) {
            $prod_id = $productDetails->product_id;
            $assoc_id = $productDetails->association_id;
            if ($prod_id != $currentID || $firstindex = TRUE) {
                $productDetails->$prod_id = self::associations($assoc_id);
                $productDetailsArray = $productDetails->$prod_id;
                if($productDetailsArray[0]->status == 1)
                {
                    $arr_product_list[] = $productDetails;
                }
            }
        }
        return $arr_product_list;
    }

}
