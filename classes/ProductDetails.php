<?php

/**
 * This class contains function to fetch products details with associations and attributes
 *
 * 
 */
class ProductDetails {
    /**
     * 
     * @param type $id product id
     * @return type it will return product details
     */
    public function getAssociationsByProductId($id) {
        $mSQL = "SELECT prd_id,status, IFNULL(".self::replaceSpecialCharacters("item_title") .",'') AS item_title, 
                 IFNULL(".self::replaceSpecialCharacters("retail_price") .",'') AS retail_price FROM product WHERE prd_id =" . $id;
        $cat_qry = dbAbstract::Execute($mSQL);
        
        while ($productDetails = dbAbstract::returnObject($cat_qry)) {
            $prodDetails[] = $productDetails;
        }
        return $prodDetails;
    }
    
    /**
     * 
     * @param type $id product id
     * @return type it will return attribute of given product 
     */
    public function getAttributesByProductId($product_id) {
        $mSQL = "SELECT IFNULL(".self::replaceSpecialCharacters("attr_name") .",'') AS attr_name, 
                extra_charge, `id`, `ProductID`, IFNULL(".self::replaceSpecialCharacters("option_name") .",'') AS option_name, 
                ".self::replaceSpecialCharacters("`Title`") ." AS Title, TRIM(".self::replaceSpecialCharacters("Price") .") AS Price, 
                `Type`, `Required`, `rest_price`, ".self::replaceSpecialCharacters("`display_Name`") ." AS display_Name, `add_to_price` , 
                `Default`+0 AS `Default` FROM attribute  WHERE ProductID = ". $product_id  ." Order by OderingNO, id";
        
        $cat_qry1 = dbAbstract::Execute($mSQL);
        
        $attributes_array = array();
        $distinct_attributes_array = array();
        while ($attribute = dbAbstract::returnObject($cat_qry1)){
            if (!isset($distinct_attributes_array[$attribute->option_name])){
                $distinct_attributes_array[$attribute->option_name] = $attribute;
                $distinct_attributes_array[$attribute->option_name]->attributes = array();
            } 
            else {
                $distinct_attributes_array[$attribute->option_name]->attributes[$attribute->id] = $attribute;
            }
        }
        return $distinct_attributes_array;
    }
    /**
     * 
     * @param type $product_id
     * @return type returna all associations of given product
     */
    public function getProductAssocByProductId($product_id) {
        $firstindex = TRUE;
        $mSQL = "SELECT * FROM product_association WHERE product_id =". $product_id  ." order by sortOrder";
        $productDs = dbAbstract::Execute($mSQL);
        $arr_product_list = array();
        while ($productDetails = dbAbstract::returnObject($productDs)){
            $prod_id = $productDetails->product_id;
            $assoc_id = $productDetails->association_id;
            
            if ($prod_id != $currentID || $firstindex = TRUE){
                $productDetails->$prod_id = self::getAssociationsByProductId($assoc_id);
                $productDetailsArray = $productDetails->$prod_id;
                if($productDetailsArray[0]->status == 1){
                    $arr_product_list[] = $productDetails;
                }
            }
        }
        return $arr_product_list;
    }
    
    /**
     * 
     * @param type $string
     * @return type rturn string after replace special characters.
     */
    public function replaceSpecialCharacters($string){
        return "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(".$string.", '\'', '&#39;'), '\r\n', '<br />'),'\n\r','<br />'), '\r', '<br />'), '\n', '<br />'), '\t', '')";
    }

}
