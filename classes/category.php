<?php 
require_once("product.php");

class category  
{
    public $menu_id;
    public $cat_id	;
    public $products;

    public function getcategories() 
    {
        $mSQL = "select * from categories where menu_id = ".$this->menu_id."  AND status = '1' order by cat_ordering";
        $cat_qry = dbAbstract::Execute($mSQL);
        $arr_category_list=array();
        while($cat = dbAbstract::returnObject($cat_qry, 0, "category")) 
        {
            $arr_category_list[] = $cat;
        }
        return $arr_category_list;

    }

    public function getdetails() 
    {
        $mSQL = "select * from categories where cat_id = ".$this->cat_id." ";
        $cat = dbAbstract::ExecuteObject($mSQL, 0, "category");
        return $cat;
    }

    public function getProducts($loadproperties=0) 
    {
        $this->products = array();	 
        $this->products = product::getproducts($this->cat_id,$loadproperties);
        return $this->products;
    }
}

?>