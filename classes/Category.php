<?php 
/**
 * This class contain function to fetch category details.
 *
 * 
 */
class Category {
    public $menu_id;

    /**
     *
     * @return type array return category details
     */
    public function getCategoryByMenuId() {
        $mSQL = "select * from categories where menu_id = ". $this->menu_id ."  AND status = '1' order by cat_ordering";
        $cat_qry = dbAbstract::Execute($mSQL);
        $arr_category_list = array();
        while ($cat = dbAbstract::returnObject($cat_qry, 0, "category")) {
            $arr_category_list[] = $cat;
        }
        return $arr_category_list;

    }

}

?>