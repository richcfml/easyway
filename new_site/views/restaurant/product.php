<link href="<?=$SiteUrl?>css/style.css" media="screen" rel="stylesheet" type="text/css"/>
<?php
	$id=$_GET['id'];

 	if(!is_numeric($id) || $id<=0) 
	{
	  header("location: ". $SiteUrl .$objRestaurant->url ."/" );
	  exit;
	}

 	$product=product::getDetailsByProductId($id);
	$quantity=1;
	$cart_item_for='';
	$notes='';

	$editIndex=-1;
	if(is_null($product)) 
	{ 
		header("location: ". $SiteUrl .$objRestaurant->url ."/" );
		exit;	 
	}
?>

<div id="BodyLeftArea" style="width:750px;">
		<div style="width: 65%; height: 80px;">
			<?php 
			if($product->item_image) 
			{ 
			?>
				<div style="margin:6px 6px 6px 0; width: 75px; float: left;"> 
					<a href="<? echo $SiteUrl."images/item_images/".stripslashes($product->item_image)?>" rel="prettyPhoto" display="inline"><img  class="images" src="<? echo $SiteUrl."images/item_images/".stripslashes($product->item_image)?>" width="70" height="70" border="0"/></a>
					<script src="/easywayordering/js/jquery.prettyPhoto.js"></script>
					<script type="text/javascript">
						jQuery(document).ready(function($) 
						{
							$("a[rel^='prettyPhoto']").prettyPhoto()
						});
					</script>
				</div>
				<div style="float: right; margin-top: 9px;">
			<?php 
			}
			else
			{
			?>
				<div style="float: left; margin-top: 9px;">
			<?php
			}
			?>
				<div style="width:380px; margin-top:5px;"> 
					<strong style="padding-top:3px;"><? echo stripslashes(stripcslashes($product->item_title))?> </strong>
					<?php
                                		if (strpos($product->item_type, '0') !== false)
                                		{
                                    			echo '<img src="../c_panel/img/new_icon22.png" style="margin-left: 10px;"/>';
                                		}
                                		if (strpos($product->item_type, '1') !== false)
                                		{
                                   			echo '<img src="../c_panel/img/vegan_icon22.png" style="margin-left: 10px;"/>';
                                		}	
                                		if (strpos($product->item_type, '2') !== false) {
                                    			echo '<img src="../c_panel/img/POPULAR_icon22.png" style="margin-left: 10px;"/>';
                                		}
                                		if (strpos($product->item_type, '3') !== false) {
                                    			echo '<img src="../c_panel/img/nutfree_icon22.png" style="margin-left: 10px;"/>';
                                		}
                                		if (strpos($product->item_type, '4') !== false) {
                                    			echo '<img src="../c_panel/img/glutenfree_icon22.png" style="margin-left: 10px;"/>';
                                		}
                                		if (strpos($product->item_type, '5') !== false) {
                                    			echo '<img src="../c_panel/img/LOWFAT_icon22.png" style="margin-left: 10px;"/>';
                                		}
                                		if (strpos($product->item_type, '6') !== false) {
                                    			echo '<img src="../c_panel/img/vegetarian_icon22.png" style="margin-left: 10px;"/>';
                                		}
                                		if (strpos($product->item_type, '7') !== false) {
                                    			echo '<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;"/>';
                                		}
                                		if (strpos($product->item_type, '8') !== false) {
                                    			echo '<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;"/>';
                                    			echo '<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;"/>';
                                		}
                                		if (strpos($product->item_type, '9') !== false) {
                                    			echo '<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;"/>';
                                    			echo '<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;"/>';
                                    			echo '<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;"/>';
                                		}
                        		?>
					<br />
					<span style="font-size:12px;"><? echo stripslashes(stripcslashes($product->item_des))?></span> 
				</div>
				<div style="clear:both"></div>
				<div style="margin-top: 6px;"> 
					<strong >Price:</strong> <span style="font-size:12px; color:#ff0000;"><?=$currency?><? echo $product->retail_price ?></span>
				</div>
			</div>
			<br/>
		</div>
		<div style="clear: both;"></div>
		<form action="?item=product&id=<? echo $product->prd_id?>&ajax=1" method="post" id="frmPrd" name="frmPrd">
		<?php
			$attribute_index=0;
		   	$attribute_name="attr";
			$attribute_parent_name="attrname";

	    	$totalattributes=count($product->distinct_attributes);
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<?php
	 	foreach  ($product->distinct_attributes as $attribute ) 
		{
			$attribute_index +=1;
		  	$attribute_name="attr" .$attribute_index."[]";
		  	if ($attribute->Type==attribute::Combo || $attribute->Type==attribute::Radio )
			{
				$attribute_name="attr" .$attribute_index;
			}
			  $attribute_parent_name="attrname" .$attribute_index;
		?>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr >
				<td>
					<strong class="Text_14px">
						<?= $attribute->option_name ?>
					</strong>
					<input type="hidden" name="<?=$attribute_parent_name?>" value="<?= $attribute->option_name ?>" />
				</td>
			</tr>
			<?php
			if ($attribute->Type==attribute::Combo)
			{
				echo "  <tr><td><select name='".$attribute_name."'>"; if($attribute->Required==0) echo "<option value='' selected>-	Please Select -</option>";
			}

			$attribute_option_index=0;
			$option_name="option";
			$attributes = array();

			$tempfirstatt=new attribute();
			$tempfirstatt->id =$attribute->id;
			$tempfirstatt->ProductID =$attribute->ProductID;
			$tempfirstatt->option_name =$attribute->option_name;
			$tempfirstatt->Title =$attribute->Title;
			$tempfirstatt->Price  =$attribute->Price;
			$tempfirstatt->option_display_preference =$attribute->option_display_preference;
			$tempfirstatt->apply_sub_cat =$attribute->apply_sub_cat;
			$tempfirstatt->Type =$attribute->Type;
			$tempfirstatt->Required =$attribute->Required;
			$tempfirstatt->OderingNO =$attribute->OderingNO;
			$tempfirstatt->rest_price=$attribute->rest_price;
			$tempfirstatt->Default	= $attribute->Default;
			$tempfirstatt->add_to_price= $attribute->add_to_price;
			$attributes[]=$tempfirstatt;
			$attributes= array_merge($attributes,$attribute->attributes);

			$col_index=0;
		  	
			foreach  ($attributes as $attribute_option ) 
			{
				$attribute_option_index +=1;
		 	 	$selected='';
			 	if(isset($productToEdit))
				{
					$found = findInArray($productToEdit->attributes,$attribute_option->id,"id");
				  	if($found==1)
					{
						if ($attribute->Type==attribute::Combo)
						{
							$selected="selected";
						}
						else
						{
							$selected="checked";
						}
				  	}
			 	}
			 	$attribute_option->Price=trim($attribute_option->Price);
		     	$attribute_option->Price = currencyToNumber_WPM($attribute_option->Price);
				//preg_replace("/[^0-9+-.]+/","",$attribute_option->Price);
	 			
				if (is_numeric($attribute_option->Price) && $attribute_option->Price !=0) 
				{
					if ($attribute_option->Price[0]=='-') 
					{
						if($attribute_option->add_to_price== 1 || $attribute_option->add_to_price=='')
						{
							$attribute_option->displayprice = "<span class='red'> - Subtract $". currencyToNumber($attribute_option->Price) /*preg_replace("/[^0-9.]+/","",$attribute_option->Price)*/."</span>";
						}
						else
						{
							$attribute_option->displayprice = "<span class='red'>  $". currencyToNumber($attribute_option->Price-$product->retail_price) /*preg_replace("/[^0-9.]+/","",$attribute_option->Price-$product->retail_price)*/."</span>";
						}
					}
					else 
					{   $attribute_option->add_to_price;
						if($attribute_option->add_to_price== 1 || $attribute_option->add_to_price=='')
						{         
							$attribute_option->displayprice = "<span class='red'> + Add $". currencyToNumber($attribute_option->Price) /*preg_replace("/[^0-9.]+/","",$attribute_option->Price)*/."</span>";
						}
						else
						{
							$attribute_option->displayprice = "<span class='red'>  $". currencyToNumber($attribute_option->Price+$product->retail_price) /*preg_replace("/[^0-9.]+/","",$attribute_option->Price+$product->retail_price)*/."</span>";
						}
					}
				}
				else
				{
					$attribute_option->displayprice='';
				}
				
				if ($attribute->Type==attribute::Combo)
				{
					if($attribute->Default==1 && $i=='')
                    {   
                    	$attrid = $attribute_option->id;
                        $i=1;
                    }
	
				?>
				<option  value="<?=$attribute_option->id?>" <?php if($attrid=='' || $attrid==$attribute_option->id){echo 'selected'; } ?> >
					<?=$attribute_option->Title . $attribute_option->displayprice ?>
				</option>
				<?php
				}
				else if ($attribute->Type==attribute::CheckBox || $attribute->Type==attribute::Radio)
				{
					if($col_index % 3==0) 
					{
					?>
						<tr>
					<?php 
					} 
					?>
						<td class="attribute"><input name="<?=$attribute_name?>" class="<?= $attribute_option->Required==1 ?'required':'' ?>" <?= $attribute->Required==1 ? "Checked":"" ?> type="<?= $attribute->Type==attribute::CheckBox ?'checkbox':'radio' ?>" value="<?=$attribute_option->id?>" <?= $attribute_option->Default==1 ? 'checked':'' ?> >
							<?=$attribute_option->Title . $attribute_option->displayprice?>
						</td>
					<?php
						$col_index +=1; 
						if($col_index % 3==0) 
						{
					?>
						</tr>
					<?php 
					}  
				}
		 		$attribute->Required=0;
		 	}
			if ($attribute->Type==attribute::Combo)
			{
				echo "</select></td></tr>";
			}
		} 
		?>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
		<?php
		if(count($product->associations))
		{
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><strong>Add to your meal</strong></td>
			</tr>
		<?php
		$index=0;
  	 	foreach  ($product->associations as $product ) 
		{
	 		$selected='';
		 	if(isset($productToEdit))
			{
				$found = findInArray($productToEdit->associations,$product->prd_id,"prd_id");
				if($found==1)
				{
					$selected="checked";
				}
			}
	    	if($index % 3==0) 
			{ 
				echo "<tr>"; 
			}
		?>
					<td class="attribute"><input name="associations[]" type="checkbox" value="<?=$index +1 ?>" <?= $selected ?>>
						<?=$product->item_title?>
							&nbsp;<span class='red'><?=$currency?>
							<?= $product->retail_price  ?>
							</span>
					</td>
					<?php 
					$index +=1;
					if($index % 3==0) 
					{ 
						echo "</tr>"; 
					} 
				}  
			?>
		</table>
		<?php 
		} 
		?>
		<hr width="96%" size="1" class="hr"  />
			<div>
				<label><strong>Quantity:</strong></label>
				<input name="quantity" type="text" id="quantity" tabindex="1" title="Quantity" maxlength="3"  value="<?= $quantity ?>"  size="5" />
				<input type="hidden" name="totalattributes" id="totalattributes" value="<?=$totalattributes ?>"  />
				<input type="hidden" name="editIndex" value="<?=$editIndex ?>"  />
			</div>
			<br />
			<div class="attribute">
				<br />
				<label>Who is this item for</label>
				<br />
				<input name="item_for" type="text" id="item_for" tabindex="1"  value="<?=$cart_item_for?>" size="25" />
			</div>
			<br />
			<div class="attribute">
				<label>List any special requests or notes</label>
				<br>
				<textarea name="requestnote" id="requestnote"  tabindex="3" cols="35" rows="4"><?= $notes ?></textarea>
			</div>
			<div class="attribute">
				<script type="text/javascript" language="javascript">
					$(document).ready(function($)
					{
						$("#addtocart").click(function()
						{
							mQuantity = $("#quantity").val();
							mItemFor = $("#item_for").val();
							mRequestNote = $("#requestnote").val();
							mTotalAttributes = $("#totalattributes").val();
							
							var mUrl = '';
							var mRandom = Math.floor((Math.random()*1000000)+1); 
							mUrl="<?=$SiteUrl?><?=$objRestaurant->url?>/?item=favindex&addtocart=1&ProductID=<?=$_GET['id']?>&rndm="+mRandom+"&ajax=1";
							$.ajax
							({
								url: mUrl,
								type:'POST',
								data: $("#frmPrd").serialize(), 
								success: function(data)
								{
									$("#cart").load("<?=$SiteUrl?><?=$objRestaurant->url?>/?item=cart&ajax=1");
									$.facebox.close();
								},
								error:function(data)
								{
									alert('Error occurred.');
								}
							});
						});
					});
				</script>
				<input type="button" name="addtocart" id="addtocart" value="Add to Cart"/>
			</div>
		</form>
	<div style="height:5px;">&nbsp;</div>
</div>
<div style="float:right;right:15px; bottom:10px; position: absolute;">
	<input id="close" type="image" src="<?=$SiteUrl?>images/closelabel.gif" onclick="$(document).trigger('close.facebox');"/>
</div>
<script language="javascript">
	$(window).resize(function() 
	{
		var width = window.innerWidth || document.body.clientWidth || document.documentElement.clientWidth;
		var box = $("#facebox");
		var box_width = box.width();
		box.css("left", (width-box_width)/2);
	});
</script>
<?php
function findInArray($array,$IdTofind,$key) 
{
	$found=0;
	foreach($array as $object) 
	{
		if ($IdTofind == $object->$key) 
		{
			$found=1;
		 	break;
		}
	}

	return $found;
}

?>
