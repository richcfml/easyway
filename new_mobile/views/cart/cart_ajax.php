<?php
	@extract($_POST);
	
	switch($action){
		case 'updateProduct':
			$mObjProduct = new Product();
			$product=$mObjProduct->getDetailsByProductId($productId);
			
			$attribute_index=1;
			
			if($quantity==''|| $quantity<=0) $quantity=1;
			
			$product_to_order->prd_id=$product->prd_id;
			$product_to_order->category_id=$product->sub_cat_id;
		
			$product_to_order->item_code=$product->item_code;
			$product_to_order->cat_name=stripslashes($product->cat_name);
			$product_to_order->quantity=$quantity;
			$product_to_order->item_title=stripslashes($product->item_title);
			$product_to_order->retail_price=$product->retail_price;
			$product_to_order->sale_price=$product->retail_price;
			
			$product_to_order->associations=array();
			$product_to_order->attributes=array();
			$product_to_order->distinct_attributes=array();
			
			
			
			while($attribute_index<=$totalattributes)
			{
				$attribute_name='attr'.$attribute_index;
				$attribute_parent_name="attrname" .$attribute_index;
				
				if(is_numeric($$attribute_name) || is_array($$attribute_name))
				{
					if(is_array($$attribute_name))
					{
						$inner_index=0;
						$arr=$$attribute_name;
						while ($inner_index<count($arr))
						{
							$ob=$product->distinct_attributes[$$attribute_parent_name];
							if($ob->id!=$arr[$inner_index])
							{
								$ob=$product->distinct_attributes[$$attribute_parent_name]->attributes[$arr[$inner_index]];
							}
	
							$attribute=new attribute();
							$attribute->id = $ob->id;
							$attribute->Title = $ob->Title;
							$attribute->Price = $ob->Price;
							$attribute->Price =  currencyToNumber_WPM($attribute->Price); //preg_replace("/[^0-9+-.]+/","",$attribute->Price);
							if($attribute->Price=='') 
							{
								$attribute->Price=0;
							}
							$attribute->Option_name=$$attribute_parent_name;
							$product_to_order->attributes[]=$attribute;
							$product_to_order->sale_price=$product_to_order->sale_price+$attribute->Price;
							$inner_index+=1;
						 }
					}
					else
					{
						$ob=$product->distinct_attributes[$$attribute_parent_name];
						if($ob->id!=$$attribute_name && is_array($product->distinct_attributes[$$attribute_parent_name]->attributes))
						{
							$ob=$product->distinct_attributes[$$attribute_parent_name]->attributes[$$attribute_name];
						}
	
						$attribute=new attribute();
						$attribute->id = $ob->id;
						$attribute->Title = $ob->Title;
						$attribute->Price = $ob->Price;
						$attribute->Price =  currencyToNumber_WPM($attribute->Price); //preg_replace("/[^0-9+-.]+/","",$attribute->Price);
						$attribute->Option_name=$$attribute_parent_name;
						if($attribute->Price=='') 
						{
							$attribute->Price=0;
						}
	
						$product_to_order->attributes[]=$attribute;
						$product_to_order->sale_price=$product_to_order->sale_price+$attribute->Price;	
					}
				}
				$attribute_index=$attribute_index+1;
			}
	
			$association_index=1;
			
			while($association_index<=count($associations))
			{
				$association=new product();
				$product_assoc=$product->associations[$associations[$association_index-1]-1];
	
				$association->prd_id=$product_assoc->prd_id;
				$association->item_title=$product_assoc->item_title;
				$association->item_des=$product_assoc->item_des;
				$association->retail_price=$product_assoc->retail_price;
				$product_to_order->associations[]=$association;
				$association_index+=1;
				$product_to_order->sale_price=$product_to_order->sale_price+$association->retail_price;
			}
			$cart->updateProduct($product_to_order,$editIndex);
			break;
	}
?>