<?php
$mOutPut='';
$mTestStr='';

if (isset($_GET['favoriteindex']))
{
	if(isset($_GET['remindex']))	
	{
		if(isset($loggedinuser->arrFavorites[$_GET['favoriteindex']]))
		{
			$mFavoriteID=$loggedinuser->arrFavorites[$_GET['favoriteindex']]->id;
			/*$favoritefood=$loggedinuser->arrFavorites[$_GET['favoriteindex']]->food;

			$mTmpCount = 0;
			foreach($favoritefood as $ff)
			{
				if ($mTmpCount==$_GET['remindex'])
				{
					unset($favoritefood[$mTmpCount]);
					$favoritefood = array_values($favoritefood);
					$loggedinuser->arrFavorites[$_GET['favoriteindex']]->food = $favoritefood;
				}
				$mTmpCount = $mTmpCount + 1;
			}*/
			unset($loggedinuser->arrFavorites[$_GET['favoriteindex']]->food[$_GET['remindex']]);
			$loggedinuser->arrFavorites[$_GET['favoriteindex']]->food = array_values($loggedinuser->arrFavorites[$_GET['favoriteindex']]->food);
			
			$loggedinuser->UpdateFavorite($mFavoriteID, serialize($loggedinuser->arrFavorites[$_GET['favoriteindex']]->food), -1, -1);
		}
	}
	
	$mTestStr='1';
	$loggedinuser->loadfavorites();
	
	$favoritefood=$loggedinuser->arrFavorites[$_GET['favoriteindex']]->food;
	$mTestStr=$mTestStr.'2';
	$mOutPut=$mOutPut."<div style='font-size: 12px;'>";
	if ($_GET['flg']==1)
	{
		$mOutPut=$mOutPut."<div class='normaltext' style='marging-bottom: 5px;'><span>Other Items in <span style='font-weight: bold;'>".$loggedinuser->arrFavorites[$_GET['favoriteindex']]->title."</span> Order</span></div>";
	}
	else if (($_GET['flg']==2) || ($_GET['flg']==3))
	{
		$mOutPut=$mOutPut."<div style='vertical-align: top; text-align: left;'><img src='../images/quickfavorite.png' border='0' alt='QUICK FAVORITE' title='QUICK FAVORITE' /></div>";
		$mOutPut=$mOutPut."<div style='margin-top: 10px;'><span style='font-weight: bold; font-size: 13px;'>CODE:</span>&nbsp;&nbsp;&nbsp;<span style='color:#36C; font-weight: bold;'>".$loggedinuser->arrFavorites[$_GET['favoriteindex']]->title."</span></div>"; 
	}
	$mOutPut=$mOutPut."<table style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>";
	$mOutPut=$mOutPut."<tr style='height: 15px;'><td colspan='7'></td></tr>";
	$mTotal=0;
	$mCount=0;
	$mTestStr=$mTestStr.'3';
	foreach($favoritefood as $ff)
	{
		$mAttributes = "<table style='width: 100%; margin: 0;' cellpadding='0' cellspacing='0'>";
		$mTmp='';
		foreach($ff->attributes as $attr) 
		{
			$mTmp=$mTmp.'<tr><td><span style="margin-left: 20px;">-&nbsp;&nbsp;'.$attr->Title.'</span></td></tr>';
		}
		if ($ff->item_for!='')
		{
			$mTmp=$mTmp.'<tr><td><span style="margin-left: 20px;">This Item For:&nbsp;&nbsp;'.$ff->item_for.'</span></td></tr>';
		}
		if ($ff->requestnote!='')
		{
			$mTmp=$mTmp.'<tr><td><span style="margin-left: 20px;">Special Instructions:&nbsp;&nbsp;'.$ff->requestnote.'</span></td></tr>';
		}
		
		
		if ($mTmp != "")
		{
			$mAttributes=$mAttributes.$mTmp."</table>";
		}
		else
		{
			$mAttributes = '';
		}
		$mOutPut=$mOutPut."<tr>";
		$mOutPut=$mOutPut."<td style='width: 56%;' valign='top'>";
		$mOutPut=$mOutPut.$ff->item_title.'<br />'.$mAttributes;
		$mOutPut=$mOutPut."</td>";
		$mOutPut=$mOutPut."<td style='width: 13%;' valign='top'>$";
		$mOutPut=$mOutPut.$ff->sale_price;
		$mOutPut=$mOutPut."</td>";
		$mOutPut=$mOutPut."<td style='width: 2%;' valign='top'>";
		$mOutPut=$mOutPut."x";
		$mOutPut=$mOutPut."</td>";
		$mOutPut=$mOutPut."<td style='width: 5%;' valign='top'>";
		$mOutPut=$mOutPut.$ff->quantity;
		$mOutPut=$mOutPut."</td>";
		$mOutPut=$mOutPut."<td style='width: 5%;' valign='top'>";
		$mOutPut=$mOutPut."=";
		$mOutPut=$mOutPut."</td>";
		$mOutPut=$mOutPut."<td style='width: 15%;' valign='top'>$";
		$mOutPut=$mOutPut.($ff->sale_price)*($ff->quantity);
		$mTotal=$mTotal+($ff->sale_price)*($ff->quantity);
		$mOutPut=$mOutPut."</td>";
		$mOutPut=$mOutPut."<td style='width: 4%;' align='right' valign='top'>";
		if ($_GET['flg']==1)
		{
			$mOutPut=$mOutPut."<img id='imgBtn' onclick='postitem(".$_GET['favoriteindex'].", 1, ".$mCount.");' style='cursor: pointer; cusror: hand;' src='../images/Trashbin.png' alt='Remove from favorites' title='Remove from favorites' />";
		}
		else if ($_GET['flg']==2)
		{
			$mOutPut=$mOutPut."<img id='imgBtn' onclick='postitemL(".$_GET['favoriteindex'].", 1, ".$mCount.");' style='cursor: pointer; cusror: hand;' src='../images/Trashbin.png' alt='Remove from favorites' title='Remove from favorites' />";
		}
		else if ($_GET['flg']==3)
		{
			$mOutPut=$mOutPut."<img id='imgBtn' onclick='postitemTMP(".$_GET['favoriteindex'].", ".$mCount.");' style='cursor: pointer; cusror: hand;' src='../images/Trashbin.png' alt='Remove from favorites' title='Remove from favorites' />";
		}
		$mOutPut=$mOutPut."</td>";
		$mOutPut=$mOutPut."</tr>";
		$mOutPut=$mOutPut."<tr style='height: 10px;'><td colspan='7'></td></tr>";
		$mOutPut=$mOutPut."<tr style='height: 1px; background-color: black;'><td colspan='7'></td></tr>";
		$mOutPut=$mOutPut."<tr style='height: 10px;'><td colspan='7'></td></tr>";
		$mCount=$mCount+1;		
	}
	$mTestStr=$mTestStr.'4';
	$mOutPut=$mOutPut."</table>";
	$mOutPut=$mOutPut."<table style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>";
	if (($_GET['flg']==2) || ($_GET['flg']==3))
	{
		$mOutPut=$mOutPut."<tr style='height: 5px;'><td colspan='2'></td></tr>";
		$mPU='';
		$mDe='';
		if ($loggedinuser->arrFavorites[$_GET['favoriteindex']]->order_receiving_method==1)
		{
			$mDe = " checked='checked' ";
		}
		else if ($loggedinuser->arrFavorites[$_GET['favoriteindex']]->order_receiving_method==2)
		{
			$mPU = " checked='checked' ";
		}
		$mOutPut=$mOutPut."<tr><td colspan='2'><input type='radio' name='rbPD' id='rbPD1' value='1' index='".$_GET["favoriteindex"]."' ".$mDe." />Delivery&nbsp;&nbsp;&nbsp;<input type='radio' id='rbPD2' name='rbPD' value='2' index='".$_GET["favoriteindex"]."' ".$mPU."/>Pick Up</td></tr>";
		$mOutPut=$mOutPut."<tr style='height: 10px;'><td colspan='2'></td></tr>";
		$mOutPut=$mOutPut."<tr style='height: 1px; background-color: black;'><td colspan='2'></td></tr>";
		$mOutPut=$mOutPut."<tr style='height: 10px;'><td colspan='2'></td></tr>";
		$mOutPut=$mOutPut."<tr style='height: 15px;'><td colspan='2'></td></tr>";
		$mOutPut=$mOutPut."<tr><td colspan='2' style='width: 100%;' align='left'>Tip Amount:&nbsp;&nbsp;$<input type='text' name='txtTip' id='txtTip' size='8' value='".$loggedinuser->arrFavorites[$_GET['favoriteindex']]->driver_tip."'/>&nbsp;<span style='display: none; color: red;' id='spnInvalid'>Invalid</span></td></tr>";
		$mOutPut=$mOutPut."<tr style='height: 10px;'><td colspan='2'></td></tr>";
	}
	$mSales_tax_ratio=$objRestaurant->tax_percent;
	$mSalesTax = number_format(($mSales_tax_ratio/100)  * $mTotal,2);
	$mTotal = $mSalesTax+$mTotal+$loggedinuser->arrFavorites[$_GET['favoriteindex']]->driver_tip;
	$mOutPut=$mOutPut."<tr><td colspan='2' align='left'>Sales Tax:&nbsp;&nbsp;$".$mSalesTax."</td></tr>";
	$mOutPut=$mOutPut."<tr style='height: 10px;'><td colspan='2'></td></tr>";
	$mOutPut=$mOutPut."<tr><td colspan='2' style='font-weight: bold; width: 100%; color:#ff0000' align='left'>TOTAL:&nbsp;&nbsp;$".$mTotal."</td></tr>";
	$mOutPut=$mOutPut."<tr style='height: 10px;'><td colspan='2'></td></tr>";
	if ($_GET['flg']==2)
	{
		$mOutPut=$mOutPut."<tr><td width='20%' align='left' valign='middle'>";
		$mOutPut=$mOutPut."<a class='clsAddtoCart' name='clsAddtoCart' id='clsAddtoCart' index='".$_GET["favoriteindex"]."' style='cursor: hand; cursor: pointer;color:#36C; text-decoration: none; font-weight: bold;'>Re-order online</a></td>";
		$mOutPut=$mOutPut."<td align='left' valign='top'><a id='clsAddtoCart' name='clsAddtoCart' class='clsAddtoCart' index='".$_GET["favoriteindex"]."' style='cursor: hand; cursor: pointer;'><img src='".$client_path."images/addtocart1.png' title='Add to Cart' alt='Add to Cart'/></a></td></tr>";
	}
	else if ($_GET['flg']==3)
	{
		$mOutPut=$mOutPut."<tr><td width='20%' align='left' valign='middle'>";
		$mOutPut=$mOutPut."<a class='clsAddtoCartTMP' name='clsAddtoCartTMP' id='clsAddtoCartTMP' index='".$_GET["favoriteindex"]."' style='cursor: hand; cursor: pointer;color:#36C; text-decoration: none; font-weight: bold;'>Re-order online</a></td>";
		$mOutPut=$mOutPut."<td align='left' valign='top'><a id='clsAddtoCartTMP' name='clsAddtoCartTMP' class='clsAddtoCartTMP' index='".$_GET["favoriteindex"]."' style='cursor: hand; cursor: pointer;'><img src='".$client_path."images/addtocart1.png' title='Add to Cart' alt='Add to Cart'/></a></td></tr>";
	}
	$mOutPut=$mOutPut."</table></div></div>";
	if ($_GET['flg']==2)
	{
		$mOutPut=$mOutPut."<div style='float:right;right:15px; top:10px; position: absolute;'><input id='close' type='image' src='".$client_path."images/closelabel.gif' /></div>";
		$mOutPut=$mOutPut."<input style='float: right;' type='image' name='addtofav' id='addtofav' src='".$client_path."images/favsave.png' alt='Save' title='Save'/>";
	}
	else if ($_GET['flg']==3)
	{
		$mOutPut=$mOutPut."<div style='float:right;right:15px; top:10px; position: absolute;'><input id='close' type='image' src='".$client_path."images/closelabel.gif' /></div>";
		$mOutPut=$mOutPut."<input style='float: right;' type='image' name='addtofavTMP' id='addtofavTMP' src='".$client_path."images/favsave.png' alt='Save' title='Save'/>";
	}
	$mTestStr=$mTestStr.'5';
	$mOutPut=$mOutPut."tttppp".$loggedinuser->arrFavorites[$_GET['favoriteindex']]->driver_tip."pppttt";
	$mOutPut=$mOutPut."dddmmm".$loggedinuser->arrFavorites[$_GET['favoriteindex']]->order_receiving_method."mmmddd";
	echo('<favitem_ewo>'.$mOutPut.'</favitem_ewo>');
//	echo('<favitem_ewo>'.$mTestStr.'</favitem_ewo>');
}
else if (isset($_GET['addtocart']))
{
	extract ($_POST);
	$mObjProduct = new product();
	$product=$mObjProduct->getdetail($_GET["ProductID"]);
	$attribute_index=1;
	$product_to_order=new product();
	if($quantity==''|| $quantity<=0) $quantity=1;
	$product_to_order->prd_id=$product->prd_id;
	$product_to_order->category_id=$product->sub_cat_id;

	$product_to_order->item_code=$product->item_code;
	$product_to_order->cat_name=stripslashes($product->cat_name);
	$product_to_order->quantity=$quantity;
	$product_to_order->item_title=stripslashes($product->item_title);
	$product_to_order->retail_price=$product->retail_price;
	$product_to_order->sale_price=$product->retail_price;
	$product_to_order->item_for=$item_for;
	$product_to_order->requestnote=$requestnote;

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
	$cart->addProduct($product_to_order);
//	$mCartPage = file_get_contents($client_path.$objRestaurant->url."/?item=cart&ajax=1");
	echo("1");
}
?>