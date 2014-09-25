<?php
require_once("attribute.php");

class product{
	public $associations;
	public $attributes;
	public $prd_id;
	public $category_id;
	public $cat_name;
	public $distinct_attributes;
	public $quantity;
   function __construct() {
		$associations=array();
		$attributes=array();
		$distinct_attributes=array();
  }
  
	public function getproducts($category_id,$loadproperties=1) {
		 
		 $qry="select * from product where sub_cat_id =".$category_id;
		 if($loadproperties==0){
			 $qry .=" and status=1";
		 }
		$qry .=" order by SortOrder asc";
		 
		$cat_qry = mysql_query($qry);
		$arr_product_list = array();
		while($product = mysql_fetch_object($cat_qry,'product')) {
				if($loadproperties==1){
					$product->getassociations();
					$product->getattributes();
				}
			$arr_product_list[] = $product;
		}
	
		return $arr_product_list;

	}

	public function getassociations() {
		$cat_qry = mysql_query("SELECT  p.* FROM product_association pa INNER JOIN product p ON pa.association_id = p.prd_id 
							WHERE product_id =". $this->prd_id);
		$this->associations=array();
		while($product = mysql_fetch_object($cat_qry,'product')) {
			$this->associations[]=$product;
		}

	}
	
	public function getattributes() {
		$cat_qry = mysql_query("SELECT  *,`Default`+0 AS `Default` FROM attribute  WHERE ProductID =". $this->prd_id . " Order by id");
		$this->attributes = array();
		$this->distinct_attributes = array();
		while($attribute = mysql_fetch_object($cat_qry,'attribute')) {
//			 $this->attributes[] = $attribute;
			if(!isset($this->distinct_attributes[$attribute->option_name])) {
				$this->distinct_attributes[$attribute->option_name]=$attribute;
				$this->distinct_attributes[$attribute->option_name]->attributes=array();
			 
			}else{
				$this->distinct_attributes[$attribute->option_name]->attributes[$attribute->id]=$attribute;
				}

		}

	}
        
    //This function will update all the attributes of Products i.e Product table column HasAttibutes
    public static function setAttributes() {
        $cat_qry = mysql_query("SELECT distinct(ProductID) FROM `attribute` inner join product on attribute.ProductID =product.prd_id");
        while ($cat = mysql_fetch_object($cat_qry)) {
            $prd_id_list.=$cat->ProductID . ',';
        }
        $prd_id_list = trim($prd_id_list, ',');
        $cat_qry = mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id IN (" . $prd_id_list . ")");
    }

    //This function will update all the association of a Product i.e Product table column HasAssociations
    public static function setAssociates() {
        $cat_qry = mysql_query("SELECT product_id FROM product_association");
        while ($cat = mysql_fetch_object($cat_qry)) {
            $prd_id_list.=$cat->product_id . ',';
        }
        $prd_id_list = trim($prd_id_list, ',');
        $cat_qry = mysql_query("UPDATE product set HasAssociates=1 WHERE prd_id IN (" . $prd_id_list . ")");
    }

   public static function getproductsbyallcategories($menuid) 
	{
//		$mTime1 = time();
        $query = "SELECT categories.cat_ordering, categories.cat_id, categories.cat_name, categories.cat_des AS cat_des"
                ." FROM menus INNER JOIN categories ON menus.id = categories.menu_id"
                ." WHERE menus.status=1 AND menus.id =".$menuid." Order by categories.cat_ordering";
        $catResult = mysql_query($query);
        $cat_count = mysql_num_rows($catResult);
        //echo $query;

        $prd_id_list = "";
        $arrCats=array();
        $subCatIDs="";
        while ($cat = mysql_fetch_object($catResult)) 
        {
            $cat->cat_des = preg_replace( "/\r|\n/", " ", $cat->cat_des);
            $cat->cat_des = str_replace("'", "&#39;",str_replace("<br />"," ",str_replace("\t", "",$cat->cat_des)));
            $arrCats[$cat->cat_id]=$cat;
            $subCatIDs.=$cat->cat_id.",";
        }
        //echo '<pre>';print_r($arrCats);echo '</pre>';
		
        $subCatIDs=  substr($subCatIDs, 0,-1);//remove last ,
		
        $queryProducts= "SELECT SortOrder, prd_id,product.status, product.HasAssociates, product.HasAttributes, product.sub_cat_id, product.item_title,"
                        ." product.item_type,product.prd_id, product.item_des AS item_des,product.retail_price,product.sale_price,product.item_image"
                        ." FROM product "
                        ." WHERE sub_cat_id in (".$subCatIDs.") Order by SortOrder";
        $prodResult = mysql_query($queryProducts);

        $arrProductList=array();
        $mIndex = 0;

        $subCatIDsLoop = "";
        while ($prodRow = mysql_fetch_object($prodResult)) 
        {
            if (strpos($subCatIDsLoop, ",".$prodRow->sub_cat_id.",")===false)
            {
                    $subCatIDsLoop.=$prodRow->sub_cat_id.",";
            }
            $prd_id_list.=$prodRow->prd_id.',';
            $arrProductList[$mIndex]=new stdClass;
            $arrProductList[$mIndex]->status=$prodRow->status;
            $arrProductList[$mIndex]->cat_name =$arrCats[$prodRow->sub_cat_id]->cat_name;
            $arrProductList[$mIndex]->cat_des = $arrCats[$prodRow->sub_cat_id]->cat_des;
            $arrProductList[$mIndex]->HasAssociates=$prodRow->HasAssociates;
            $arrProductList[$mIndex]->HasAttributes = $prodRow->HasAttributes;
            $arrProductList[$mIndex]->sub_cat_id = $prodRow->sub_cat_id;
            $arrProductList[$mIndex]->item_title = $prodRow->item_title;
            $arrProductList[$mIndex]->item_type = $prodRow->item_type;
            $arrProductList[$mIndex]->prd_id=$prodRow->prd_id;
            $itemDesc = preg_replace( "/\r|\n/", " ", $prodRow->item_des);
            $itemDesc=  str_replace("'", "&#39;",str_replace("<br />"," ",str_replace("\t", "",$itemDesc)));
            $arrProductList[$mIndex]->item_des = $itemDesc;
            $arrProductList[$mIndex]->retail_price = $prodRow->retail_price;
            $arrProductList[$mIndex]->sale_price = $prodRow->sale_price;
            $arrProductList[$mIndex]->item_image = $prodRow->item_image;
            $arrProductList[$mIndex]->cat_ordering = $arrCats[$prodRow->sub_cat_id]->cat_ordering;
            $arrProductList[$mIndex]->SortOrder = $prodRow->SortOrder;
            $arrProductList[$mIndex]->display = "";
        	//set rest of the fields
            $mIndex++;
        }
		
		$subCatIDsLoop =  substr($subCatIDsLoop, 0,-1);//remove last ,
		
		$mSubCatArr = explode(",", $subCatIDs);
		$mSubCatLoopArr = explode(",", $subCatIDsLoop);
				
		$mLoopFlag = false;
		for ($loopCount=0;$loopCount<count($mSubCatArr);$loopCount++)
		{
			for ($innerLoopCount=0;$innerLoopCount<count($mSubCatLoopArr);$innerLoopCount++)
			{
				if ($mSubCatLoopArr[$innerLoopCount]==$mSubCatArr[$loopCount])
				{
					$mLoopFlag = true;
				}
			}
			
			if ($mLoopFlag == false)
			{
				$arrProductList[$mIndex]->status=0;
				$arrProductList[$mIndex]->cat_name = $arrCats[$mSubCatArr[$loopCount]]->cat_name;
				$arrProductList[$mIndex]->cat_des = $arrCats[$mSubCatArr[$loopCount]]->cat_des;
				$arrProductList[$mIndex]->HasAssociates = 0;
				$arrProductList[$mIndex]->HasAttributes = 0;
				$arrProductList[$mIndex]->sub_cat_id = $mSubCatArr[$loopCount];
				$arrProductList[$mIndex]->item_title = "";
				$arrProductList[$mIndex]->item_type = 0;
				$arrProductList[$mIndex]->prd_id=0;
				$arrProductList[$mIndex]->item_des = "";
				$arrProductList[$mIndex]->retail_price = 0;
				$arrProductList[$mIndex]->sale_price = 0;
				$arrProductList[$mIndex]->item_image = 0;
				$arrProductList[$mIndex]->cat_ordering = $arrCats[$mSubCatArr[$loopCount]]->cat_ordering;
				$arrProductList[$mIndex]->SortOrder = 0;
				$arrProductList[$mIndex]->display = " style='display: none;' ";
				$mIndex++;
			}
			$mLoopFlag = false;
		}
		
		$arrProductList = (array) $arrProductList;
		
		foreach ($arrProductList as $key => $row) 
		{
		    $cat_ordering[$key] = $row->cat_ordering;
			$SortOrder[$key] = $row->SortOrder;
			$prd_id[$key] = $row->prd_id;
		}
		
		array_multisort($cat_ordering, SORT_ASC, $SortOrder, SORT_ASC, $prd_id, SORT_ASC, $arrProductList);
//		echo("<pre>");print_r($arrProductList);echo("</pre>");exit;
//		$mTime2 = time();
//		echo("Time1: ".$mTime1."<br />Time2: ".$mTime2);
		return array('details'=>$arrProductList, 'count'=>$cat_count, 'prd_list'=>$prd_id_list);
    }
    
    public static function checkAttrAndAssoc($product_id){
        $cat_qry = mysql_query("SELECT HasAttributes,HasAssociates from product where prd_id = ".$product_id);
        $row = mysql_fetch_object($cat_qry);
        return $row;
    }

    public function getdetail($id) {
        $prd_qry = mysql_query("select * from product where prd_id =" . $id);
        $product = mysql_fetch_object($prd_qry, 'product');
        if (is_null($product))
            return NULL;
        if (!is_numeric($product->prd_id))
            return NULL;
        $product->getassociations();
        $product->getattributes();

        $category = mysql_fetch_object(mysql_query("select cat_name from categories where cat_id =" . $product->sub_cat_id));
        $product->cat_name = $category->cat_name;
        return $product;
    }

}

?>