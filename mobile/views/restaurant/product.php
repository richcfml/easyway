<script>
function isNumeric(n) 
{
  return !isNaN(parseFloat(n)) && isFinite(n);
}	
	
$(document).ready(function()
{
	$(".clsAssoc").live("change", function() 
	{
		var mPrice = $(this).attr("price");			
		var mID = $(this).attr("id");
		var mChecked = 0;

		if ($(this).is(':checked'))
		{
			mChecked = 1;
		}
		
		if ($.trim(mPrice)!="")
		{
			if (isNumeric(mPrice))
			{
				mPreviousPrice = $('span[id=retail_price]:last').html();
				mPreviousPrice = mPreviousPrice.replace('$', '');
				mTxtChecked = $('input[id=txtAssoc]:last').val();
				
				if (mChecked==1)
				{
					mTxtChecked = mTxtChecked+mID+",";
					mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
					$('input[id=txtAssoc]:last').val(mTxtChecked);
				}
				else if (mChecked==0)
				{
					mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPrice);									
					mTxtChecked = mTxtChecked.replace(mID+",", '');
					$('input[id=txtAssoc]:last').val(mTxtChecked);
				}
			}
			mPreviousPrice = mPreviousPrice.toFixed(2);
			$('span[id=retail_price]:last').html("$"+mPreviousPrice);
		}
	});
	
	$(".inputAttr").live("change", function() 
	{
		var mPrice = $(this).attr("price");
		var mType = $(this).attr("type");
		var mID = $(this).attr("id");
		var mAttributeID = $(this).attr("attributeid");
		var mLimit = $(this).attr("limit");
		var mLimitPrice = $(this).attr("limitprice");

		var mChecked = 0;
		if (mType=="checkbox")
		{
			if ($(this).is(':checked'))
			{
				mChecked = 1;
			}
		}

		if ($.trim(mPrice)!="")
		{
						if (isNumeric(mPrice))
						{
							mPreviousPrice = $('span[id=retail_price]:last').html();
							mPreviousPrice = mPreviousPrice.replace('$', '');
							mTxtChecked = $('input[id=txtChecked'+mAttributeID+']:last').val();
							if (mChecked==1)
							{
								mTxtChecked = mTxtChecked+mID+",";
								var mTmp = mTxtChecked.split(",");
								var mLength = mTmp.length;
								if ($.trim(mLimit)!="")
								{
									if (mLength-1>mLimit)
									{
										mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice) + parseFloat(mLimitPrice);
									}
									else
									{
										mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
									}
								}
								else
								{
									mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
								}
								$('input[id=txtChecked'+mAttributeID+']:last').val(mTxtChecked);
							}
							else if (mChecked==0)
							{
								var mTmp = mTxtChecked.split(",");
								var mLength = mTmp.length;
								if ($.trim(mLimit)!="")
								{
									if (mLength-1<=mLimit)
									{
										mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPrice);
									}
									else
									{
										mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPrice) - parseFloat(mLimitPrice);
									}
								}
								else
								{
									mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPrice) - parseFloat(mLimitPrice);
								}
								mTxtChecked = mTxtChecked.replace(mID+",", '');
								$('input[id=txtChecked'+mAttributeID+']:last').val(mTxtChecked);
							}
								mPreviousPrice = mPreviousPrice.toFixed(2);
								mPreviousPrice = '$'+mPreviousPrice;
								$('span[id=retail_price]:last').html(mPreviousPrice);
						}
		}
	});
	
	$(".inputAttrRB").die('change').live("change", function() 
	{
		var mPrice = $(this).attr("price");
		var mID = $(this).attr("id");
		var mName = $(this).attr("name");
		var mAttributeID = $(this).attr("attributeid");
		var mTxtChecked = $('input[id=txtChecked'+mAttributeID+']:last').val();
		
		if ($.trim(mTxtChecked)!="")
		{
			$('input[name="'+mName+'"]').each(function (index) 
			{
				var mLoopPrice = $(this).attr("price");
				var mLoopID = $(this).attr("id");
				
				if ($.trim(mTxtChecked).indexOf(mLoopID+",")>=0)
				{
					mPreviousPrice = $('span[id=retail_price]:last').html();
					mPreviousPrice = mPreviousPrice.replace('$', '');
					mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mLoopPrice);

					if ($.trim(mPrice)!="")
					{
						mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
					}
					
					mPreviousPrice = mPreviousPrice.toFixed(2);
					mPreviousPrice = '$'+mPreviousPrice;
					$('input[id=txtChecked'+mAttributeID+']:last').val(mID+",")
					$('span[id=retail_price]:last').html(mPreviousPrice);
					return false;
				}
			});	
		}
		else
		{
			mPreviousPrice = $('span[id=retail_price]:last').html();
			mPreviousPrice = mPreviousPrice.replace('$', '');

			if ($.trim(mPrice)!="")
			{
				mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
			}
			
			mPreviousPrice = mPreviousPrice.toFixed(2);
			mPreviousPrice = '$'+mPreviousPrice;
			$('input[id=txtChecked'+mAttributeID+']:last').val(mID+",")
			$('span[id=retail_price]:last').html(mPreviousPrice);
		}
	});
	
	$(".inputAttrDD").live("change", function() 
	{
		var mTextboxId = $(this).attr("textboxid");
		var mID = $(this).attr("id");
		var mPrice = $('option:selected', this).attr('price');
		var mOptionID = $('option:selected', this).attr('optionid');
	
		if ($(this).val()!="")
		{
			if ($.trim($("#"+mTextboxId).val())!="")
			{
				$("#"+mID+" > option").each(function() 
				{
					var mOptionIDLoop = $(this).attr("value");
					var mPriceLoop = $(this).attr("price");

					if ($.trim(mOptionIDLoop)!="")
					{
						if ($.trim($("#"+mTextboxId).val()).indexOf(mOptionIDLoop+",")>=0)
						{
							mPreviousPrice = $('span[id=retail_price]:last').html();
							mPreviousPrice = mPreviousPrice.replace('$', '');
							mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPriceLoop);
	
							if ($.trim(mPrice)!="")
							{
								mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
							}
							
							mPreviousPrice = mPreviousPrice.toFixed(2);
							mPreviousPrice = '$'+mPreviousPrice;
							$("#"+mTextboxId).val($("#"+mTextboxId).val().replace(mOptionIDLoop+",", ""));
							$("#"+mTextboxId).val(mOptionID+",")
							$('span[id=retail_price]:last').html(mPreviousPrice);
						}
					}
				});
			}
			else
			{
				if ($.trim(mPrice)!="")
				{
					$("#"+mTextboxId).val($('option:selected', this).attr('optionid')+",")
					mPreviousPrice = $('span[id=retail_price]:last').html();
					mPreviousPrice = mPreviousPrice.replace('$', '');
					mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
					mPreviousPrice = mPreviousPrice.toFixed(2);
					mPreviousPrice = '$'+mPreviousPrice;
					$('span[id=retail_price]:last').html(mPreviousPrice);
				}
			}
		}
		else
		{
			if ($.trim($("#"+mTextboxId).val())!="")
			{
				$("#"+mID+" > option").each(function() 
				{
					var mOptionIDLoop = $(this).attr("value");
					var mPriceLoop = $(this).attr("price");
					
					if ($.trim(mOptionIDLoop)!="")
					{
						if ($.trim($("#"+mTextboxId).val()).indexOf($.trim(mOptionIDLoop)+",")>=0)
						{
							mPreviousPrice = $('span[id=retail_price]:last').html();
							mPreviousPrice = mPreviousPrice.replace('$', '');
							if (isNumeric(mPriceLoop))
							{
								mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPriceLoop);
								mPreviousPrice = mPreviousPrice.toFixed(2);
							}
							mPreviousPrice = '$'+mPreviousPrice;
							$("#"+mTextboxId).val($("#"+mTextboxId).val().replace(mOptionIDLoop+",", ""));
							$('span[id=retail_price]:last').html(mPreviousPrice);
						}
					}
				});
			}
		}
	});
});
</script>
<?php

$id = $_GET['id'];
if (!is_numeric($id) || $id <= 0) 
{
    redirect($SiteUrl . $objRestaurant->url . "/");
    exit;
}

$product = product::getDetailsByProductId($id);
/*echo("<pre>");
print_r($product);*/
if (is_null($product)) 
{
    redirect($SiteUrl . $objRestaurant->url . "/");
    exit;
}

if (isset($_POST['addtocart'])) 
{
    extract($_POST);
    $attribute_index = 1;
    $product_to_order = new product();
    if ($quantity == '' || $quantity <= 0)
	{
        $quantity = 1;
	}
    $product_to_order->prd_id = $product->prd_id;
    $product_to_order->category_id = $product->sub_cat_id;
    $product_to_order->item_code = $product->item_code;
    $product_to_order->cat_name = stripslashes($product->cat_name);
    $product_to_order->quantity = $quantity;
    $product_to_order->item_title = stripslashes($product->item_title);
    $product_to_order->retail_price = $product->retail_price;
    $product_to_order->sale_price = $product->retail_price;
    $product_to_order->item_for = '';

    $product_to_order->requestnote = $requestnote;

    $product_to_order->associations = array();
    $product_to_order->attributes = array();
    $product_to_order->distinct_attributes = array();

    while ($attribute_index <= $totalattributes) 
	{
        $attribute_name = 'attr' . $attribute_index;
        $attribute_parent_name = "attrname" . $attribute_index;

        if (is_numeric($$attribute_name) || is_array($$attribute_name)) 
		{
            if (is_array($$attribute_name)) 
			{
                $inner_index = 0;
                $arr = $$attribute_name;
				
                while ($inner_index < count($arr)) 
				{
                    $ob = $product->distinct_attributes[$$attribute_parent_name];

                    if ($ob->id != $arr[$inner_index]) 
					{
                        $ob = $product->distinct_attributes[$$attribute_parent_name]->attributes[$arr[$inner_index]];
                    }

                    $attribute = new attribute();
                    $attribute->id = $ob->id;
                    $attribute->Title = $ob->Title;
                    $attribute->Price = $ob->Price;
                    $attribute->Price = currencyToNumber_WPM($attribute->Price);
					//$attribute->Price = preg_replace("/[^0-9+-.]+/", "", $attribute->Price);
                    if ($attribute->Price == '')
					{
                        $attribute->Price = 0;
					}
                    $attribute->Option_name = $$attribute_parent_name;
                    $product_to_order->attributes[] = $attribute;
                    $product_to_order->sale_price = $product_to_order->sale_price + $attribute->Price;
					$mLimitS = "";
					$mLimitPriceS = "";
					if (trim($ob->attr_name)!="")
					{
						$mTmpS = explode("~", $ob->attr_name);
						$mLimitS = $mTmpS[2];
						$mLimitPriceS = $ob->extra_charge;
					}
					if ((trim($mLimitS)!="") && (trim($mLimitPriceS)!=""))
                	{
						if (($mLimitS>0) && ($mLimitPriceS>0))
						{
							if ($inner_index+1>$mLimitS)
							{
								$product_to_order->sale_price = $product_to_order->sale_price + $mLimitPriceS;
							}
						}
					}
                    $inner_index+=1;
                }//END WHILE
            }
			else 
			{
                $ob = $product->distinct_attributes[$$attribute_parent_name];

                if ($ob->id != $$attribute_name && is_array($product->distinct_attributes[$$attribute_parent_name]->attributes)) 
				{
                    $ob = $product->distinct_attributes[$$attribute_parent_name]->attributes[$$attribute_name];
                }

                $attribute = new attribute();
                $attribute->id = $ob->id;
                $attribute->Title = $ob->Title;
                $attribute->Price = $ob->Price;
                $attribute->Price = currencyToNumber_WPM($attribute->Price); //preg_replace("/[^0-9+-.]+/", "", $attribute->Price);
                $attribute->Option_name = $$attribute_parent_name;
                if ($attribute->Price == '')
				{
                    $attribute->Price = 0;
				}

                $product_to_order->attributes[] = $attribute;
                $product_to_order->sale_price = $product_to_order->sale_price + $attribute->Price;
            }
        }
        $attribute_index = $attribute_index + 1;
    }

    $association_index = 1;
    if(isset($associations))
	{
        while ($association_index <= count($associations)) 
		{
            $association = new product();
            $product_assoc = $product->associations[$associations[$association_index - 1] - 1];

            $association->prd_id = $product_assoc->prd_id;
            $association->item_title = $product_assoc->item_title;
            $association->item_des = $product_assoc->item_des;
            $association->retail_price = $product_assoc->retail_price;
            $product_to_order->associations[] = $association;
            $association_index+=1;
            $product_to_order->sale_price = $product_to_order->sale_price + $association->retail_price;
        }
    }

    $cart->addProduct($product_to_order);
    redirect($SiteUrl . $objRestaurant->url . "/");
    exit;
}
?>

<section class="menu_list_wrapper content">
    <div>
        <h1 class="product"><? echo stripslashes(stripcslashes($product->item_title)) ?> </h1>
        <div  class="normal"><? echo stripslashes(stripcslashes($product->item_des)) ?> </div>
		<?php
			$mPreviousPrice = $product->retail_price;
			$mPreviousPrice = trim(str_replace("$", "", $mPreviousPrice));
		?>
        <div><b>Price:</b> <span class="red" id="retail_price"><?=$currency?><? echo $product->retail_price ?></span> </div>
        <form action="" method="post" class="product_details_form commentsblock">
            <style type="text/css"> .error {color: red;} </style>
            <?php
            $attribute_index = 0;
            $attribute_name = "attr";
            $attribute_parent_name = "attrname";
            $totalattributes = count($product->distinct_attributes);
            foreach ($product->distinct_attributes as $attribute) 
			{
				$mLimit = "";
				$mLimitPrice = "";
				$mStrRe = "";
				$mSelected = "";
				
				$mCheckCount = 0;
				$mTxtCheckedLoad = '';
				$j = 0;
				$mAddFlag = 0;
				$mAddFlag1 = 0;
				
				if (trim($attribute->attr_name)!="")
				{
					$mTmp =	explode("~", $attribute->attr_name);
                    $mLimit = $mTmp[2];
					$mLimitPrice = $attribute->extra_charge;
				}
				
				if ($attribute->Required == 1)
                {
	                $mStrRe = 'Choose at least one. ';
                }
				
				if ((trim($mLimit)!="") && (trim($mLimitPrice)!=""))
                {
					if (($mLimit>0) && ($mLimitPrice>0))
					{
						$mStrRe = $mStrRe.$currency.$mLimitPrice.' will be added for each additional selection';	
					}
				}
                $attribute_index +=1;
                $attribute_name = "attr" . $attribute_index . "[]";
                if ($attribute->Type == attribute::Combo || $attribute->Type == attribute::Radio) 
				{
                    $attribute_name = "attr" . $attribute_index;
                }
                $attribute_parent_name = "attrname" . $attribute_index;
            ?>
                <div class="bold margintop">
                    <?= $attribute->option_name ?>
                    <input type="hidden" name="<?= $attribute_parent_name ?>" value="<?= $attribute->option_name ?>" />
                </div>
				<span style="color: red; font-size: 12px; font-style: italic;"><?php echo($mStrRe); ?></span>
                <div class="normal margintopsmall">
            <?php
                    if ($attribute->Type == attribute::Combo) 
					{
						echo("<select id='ddlAttr' textboxid='txtChecked".$attribute->id."' type='select' name='".$attribute_name."' class='inputAttrDD'>");
                        if ($attribute->Required == 0)
						{
                            echo "<option price='' value='' selected>- Select -</option>";
						}
                    }

                    $attribute_option_index = 0;
                    $option_name = "option";
                    $attributes = array();

                    $tempfirstatt = new attribute();
                    $tempfirstatt->id = $attribute->id;
                    $tempfirstatt->ProductID = $attribute->ProductID;
                    $tempfirstatt->option_name = $attribute->option_name;
                    $tempfirstatt->Title = $attribute->Title;
                    $tempfirstatt->Price = $attribute->Price;
                    $tempfirstatt->option_display_preference = $attribute->option_display_preference;
                    $tempfirstatt->apply_sub_cat = $attribute->apply_sub_cat;
                    $tempfirstatt->Type = $attribute->Type;
                    $tempfirstatt->Required = $attribute->Required;
                    $tempfirstatt->OderingNO = $attribute->OderingNO;
                    $tempfirstatt->rest_price = $attribute->rest_price;
					$tempfirstatt->display_Name = $attribute->display_Name;
					$tempfirstatt->Default = $attribute->Default;
					$tempfirstatt->add_to_price = $attribute->add_to_price;
					$tempfirstatt-> attr_name  = $attribute->attr_name;
					$tempfirstatt->extra_charge = $attribute->extra_charge;
                    $attributes[] = $tempfirstatt;
    
					$attributes = array_merge($attributes, $attribute->attributes);

                    foreach ($attributes as $attribute_option) 
					{
                        $attribute_option_index +=1;

                        $attribute_option->Price = trim($attribute_option->Price);
                        $attribute_option->Price = currencyToNumber_WPM($attribute_option->Price); //preg_replace("/[^0-9+-.]+/", "", $attribute_option->Price);

                        if (is_numeric($attribute_option->Price) && $attribute_option->Price != 0) 
						{
                            if ($attribute_option->Price[0] == '-') 
							{
                                $attribute_option->displayprice = "<span class='red'> - Subtract ".$currency . currencyToNumber($attribute_option->Price) . "</span>";	/*preg_replace("/[^0-9.]+/", "", $attribute_option->Price)*/
                            } 
							else 
							{
                                $attribute_option->displayprice = "<span class='red'> + Add ".$currency . currencyToNumber($attribute_option->Price) . "</span>";	//preg_replace("/[^0-9.]+/", "", $attribute_option->Price)
                            }
                        } 
						else 
						{
                            $attribute_option->displayprice = '';
                        }

                        if ($attribute->Type == attribute::Combo) 
						{
							if ($attribute_option->Default == 1)
							{
								if ($mAddFlag==0)
								{
									$mSelected = " selected='selected' ";
									$mAddFlag=1;
									$mCheckCount = $mCheckCount + 1;
									if (trim($mLimit)!="")
									{
										if ($mCheckCount>$mLimit)
										{
											$mPreviousPrice = trim(str_replace("$", "", $mPreviousPrice));
											$mPreviousPrice = $mPreviousPrice + $attribute_option->Price + $mLimitPrice;
											$mPreviousPrice = number_format($mPreviousPrice, 2);
											$mPreviousPrice = '$'.$mPreviousPrice;
											$mTxtCheckedLoad = $mTxtCheckedLoad.$attribute_option->id.",";
										}
										else
										{
											$mPreviousPrice = trim(str_replace("$", "", $mPreviousPrice));
											$mPreviousPrice = $mPreviousPrice + $attribute_option->Price;
											$mPreviousPrice = number_format($mPreviousPrice, 2);
											$mPreviousPrice = '$'.$mPreviousPrice;
											$mTxtCheckedLoad = $mTxtCheckedLoad.$attribute_option->id.",";
										}
									}
									else
									{
										$mPreviousPrice = trim(str_replace("$", "", $mPreviousPrice));
										$mPreviousPrice = $mPreviousPrice + $attribute_option->Price;
										$mPreviousPrice = number_format($mPreviousPrice, 2);
										$mPreviousPrice = '$'.$mPreviousPrice;
										$mTxtCheckedLoad = $mTxtCheckedLoad.$attribute_option->id.",";
									}
								}
								else
								{
									$mSelected = "";
								}
							}
							else
							{
								$mSelected = "";
							}
			?>
                            <option optionid="<?=$attribute_option->id?>" textboxid="txtChecked<?=$attribute->id?>" price="<?=$attribute_option->Price?>" value="<?= $attribute_option->id ?>" <?=$mSelected?>>
                                <?= $attribute_option->Title . $attribute_option->displayprice ?>
                            </option>
			<?php
                        } 
						else if ($attribute->Type == attribute::CheckBox || $attribute->Type == attribute::Radio) 
						{
							if ($attribute->Type == attribute::Radio)
							{
								if ($attribute_option->Default == 1)
								{
									if ($mAddFlag==0)
									{
										$mSelected = " checked='checked' ";
										$mAddFlag=1;
										$mCheckCount = $mCheckCount + 1;
										if (trim($mLimit)!="")
										{
											if ($mCheckCount>$mLimit)
											{
												$mPreviousPrice = trim(str_replace("$", "", $mPreviousPrice));
												$mPreviousPrice = $mPreviousPrice + $attribute_option->Price + $mLimitPrice;
												$mPreviousPrice = number_format($mPreviousPrice, 2);
												$mPreviousPrice = '$'.$mPreviousPrice;
												$mTxtCheckedLoad = $mTxtCheckedLoad.$attribute_option->id.",";
											}
											else
											{
												$mPreviousPrice = trim(str_replace("$", "", $mPreviousPrice));
												$mPreviousPrice = $mPreviousPrice + $attribute_option->Price;
												$mPreviousPrice = number_format($mPreviousPrice, 2);
												$mPreviousPrice = '$'.$mPreviousPrice;
												$mTxtCheckedLoad = $mTxtCheckedLoad.$attribute_option->id.",";
											}
										}
										else
										{
											$mPreviousPrice = trim(str_replace("$", "", $mPreviousPrice));
											$mPreviousPrice = $mPreviousPrice + $attribute_option->Price;
											$mPreviousPrice = number_format($mPreviousPrice, 2);
											$mPreviousPrice = '$'.$mPreviousPrice;
											$mTxtCheckedLoad = $mTxtCheckedLoad.$attribute_option->id.",";
										}
									}
									else
									{
										$mSelected = "";
									}
								}
								else
								{
									$mSelected = "";
								}
							}
							else
							{
								if ($attribute_option->Default == 1)
								{
									$mSelected = " checked='checked' ";
									$mCheckCount = $mCheckCount + 1;
									if (trim($mLimit)!="")
									{
										if ($mCheckCount>$mLimit)
										{
											$mPreviousPrice = trim(str_replace("$", "", $mPreviousPrice));
											$mPreviousPrice = $mPreviousPrice + $attribute_option->Price + $mLimitPrice;
											$mPreviousPrice = number_format($mPreviousPrice, 2);
											$mPreviousPrice = '$'.$mPreviousPrice;
											$mTxtCheckedLoad = $mTxtCheckedLoad.$attribute_option->id.",";
										}
										else
										{
											$mPreviousPrice = trim(str_replace("$", "", $mPreviousPrice));
											$mPreviousPrice = $mPreviousPrice + $attribute_option->Price;
											$mPreviousPrice = number_format($mPreviousPrice, 2);
											$mPreviousPrice = '$'.$mPreviousPrice;
											$mTxtCheckedLoad = $mTxtCheckedLoad.$attribute_option->id.",";
										}
									}
									else
									{
										$mPreviousPrice = trim(str_replace("$", "", $mPreviousPrice));
										$mPreviousPrice = $mPreviousPrice + $attribute_option->Price;
										$mPreviousPrice = number_format($mPreviousPrice, 2);
										$mPreviousPrice = '$'.$mPreviousPrice;
										$mTxtCheckedLoad = $mTxtCheckedLoad.$attribute_option->id.",";
									}
								}
								else
								{
									$mSelected = "";
								}
							}
							
			?>
                            <div>
								<input limit="<?=$mLimit?>" limitprice="<?=$mLimitPrice?>" price="<?=$attribute_option->Price?>" id="<?=$attribute_option->id?>" attributeid="<?=$attribute->id?>" name="<?=$attribute_name?>" class=<?=($attribute->Type == 2 ? "inputAttr":"inputAttrRB")?> type=<?=($attribute->Type == 2 ? "checkbox" : "radio")?> value="<?=$attribute_option->id?>" <?=$mSelected?> />
                                <?= $attribute_option->Title . $attribute_option->displayprice ?>
                            </div>
            <?php
                        }
                        $attribute->Required = 0;
                    }
                    
					if ($attribute->Required == 1)
					{
					?>
						<input type="hidden" id="txtChecked<?=$attribute->id?>" value="<?=$mTxtCheckedLoad?>" />
						<input type="hidden" id="txtReq<?=$attribute->id?>" value="1" />
					<?php
					}
					else
					{
					?>	
						<input type="hidden" id="txtChecked<?=$attribute->id?>" value="<?=$mTxtCheckedLoad?>" />
						<input type="hidden" id="txtReq<?=$attribute->id?>" value="0" />
					<?php
					}
					
					if ($attribute->Type == attribute::Combo) 
					{
                        echo "</select>";
                    }
            ?>
                    <div class="clear"></div>
                </div>
            <?php 
			} 
            
			if (count($product->associations)) 
			{
            ?>
                <div class="bold margintop">Add to your meal</div>
                <div class="normal margintopsmall">
            <?php
                    $index = 0;
                    foreach ($product->associations as $product) 
					{
                        $index +=1;
			?>
                        <div>
                            <input class="clsAssoc" price="<?=$product->retail_price?>" name="associations[]" type="checkbox" value="<?= $index ?>">
                            <?= $product->item_title ?>
                            &nbsp;<span class='red'><?=$currency?>
                                <?= $product->retail_price ?>
                            </span> </div>
            <?php 
					} 
			?>
                    <div class="clear"></div>
                </div>
            <?php 
			} 
			echo("<script>$('#retail_price').html('".$mPreviousPrice."');</script>");
			?>
            <p class="margintop">Quantity <span class="">
                    <input name="quantity" type="text" id="quantity" tabindex="1" title="Quantity" maxlength="3"  value="1"  size="5" />
                    <input type="hidden" name="totalattributes" value="<?= $totalattributes ?>"  />
                </span> 
			</p>
            <p class="normal">List any special requests or notes<br>
                <textarea name="requestnote"  tabindex="3" cols="10" rows="3"></textarea>
            </p>
            <div class="margintop">
				<input type="hidden" id="txtAssoc" />
                <input type="submit" name="addtocart" value="Add to Cart" class="button blue">
            </div>
            <script type="text/javascript">
                $(document).ready(function() 
				{
                    $(".product_details_form").validate(
					{
                    	errorPlacement: function(error, element) 
						{
                        	var type = $(element).attr('type').toLowerCase();
                            if (type == "checkbox" || type == "radio") 
							{
                            	$(element).parent().parent().prepend(error);
                            } 
							else 
							{
                            	error.insertAfter($(element));
                            }
						}
                    });
                });
            </script>
        </form>
    </div>
    <div style="height:5px;">&nbsp;</div>
</section>
