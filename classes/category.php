<? 
require_once("product.php");

class category  {
	public $menu_id;
	public $cat_id	;
	public $products;

	public function getcategories() {
		$cat_qry = mysql_query("select * from categories where menu_id = ".$this->menu_id."  AND status = '1' order by cat_ordering");
		$arr_category_list=array();
		while($cat = mysql_fetch_object($cat_qry, 'category') ) {
			 
			$arr_category_list[] = $cat;
		}
		return $arr_category_list;

	}
	
	public function getdetails() {
		$cat_qry = mysql_query("select * from categories where cat_id = ".$this->cat_id." ");
		$cat = mysql_fetch_object($cat_qry,'category') ;
		return $cat;

	}
	
	public function getProducts($loadproperties=0) {
		$this->products = array();	 
		$this->products = product::getproducts($this->cat_id,$loadproperties);
	 
		return $this->products;
	}
}

?>