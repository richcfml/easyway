<script type="text/javascript" src="<?=$js_root?>fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?=$js_root?>fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		$("#hrefFB").fancybox({
			'showCloseButton'   : false,
			'overlayShow'	: false
		});
   });
</script>
<style type="text/css">
#fancybox-content {
	border-radius:1px !important;
	padding: 5px !important;
	outline: none !important;
	margin-left: -4px !important;
	box-shadow: 0 0 0 10px rgba(132,132,132,0.8) !important;
}
</style>
<?php
	if(!is_numeric($loggedinuser->id)) 
	{
		 redirect($SiteUrl .$objRestaurant1->url ."/?item=login&ifrm=login");
		 exit;
	}
	
	if (!isset($_GET["Orderid"]))
	{
		redirect($SiteUrl .$objRestaurant->url ."/?item=menu&ifrm=load_resturant");
		exit;
	}
	
	$mOrderID = $_GET["Orderid"];
	$mSQL = "SELECT *, DATE_FORMAT(OrderDate,'%m/%d/%Y') AS OrderDateF FROM ordertbl WHERE OrderID =".$mOrderID; 
	$mResult= dbAbstract::Execute($mSQL);

	if (dbAbstract::returnRowsCount($mResult)<1)
	{
		redirect($SiteUrl .$objRestaurant->url ."/?item=menu&ifrm=load_resturant");
		exit;
	}
	
	
	
	
	
	$mProducts = array();
	$mSQLS = dbAbstract::Execute("SELECT OD.item_for AS ItemFor, OD.quantity AS Quantity, P.prd_id AS ProductID FROM orderdetails OD, product P WHERE OD.orderid = ".$mOrderID." AND P.prd_id=OD.pid");

	while($mRowS = dbAbstract::returnArray($mSQLS))
	{       
		$product = product::getDetailsByProductId($mRowS["ProductID"]);	
		$attribute_index = 1;
		$totalattributes = count($product->distinct_attributes);
		
		$product_to_order = new product();
		$quantity = $mRowS["Quantity"];
		$product_to_order->prd_id = $product->prd_id;
		$product_to_order->category_id = $product->sub_cat_id;
		$product_to_order->item_code = $product->item_code;
		$product_to_order->cat_name = stripslashes($product->cat_name);
		$product_to_order->quantity = $quantity;
		$product_to_order->item_title = stripslashes($product->item_title);
		$product_to_order->retail_price = $product->retail_price;
		$product_to_order->sale_price = $product->retail_price;
		$product_to_order->item_for = $mRowS["ItemFor"];
	
		$product_to_order->requestnote = $mRowS["RequestNote"];
	
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
						$attribute->Price = currencyToNumber_WPM($attribute->Price); //preg_replace("/[^0-9+-.]+/", "", $attribute->Price);
						if ($attribute->Price == '')
						{
							$attribute->Price = 0;
						}
						$attribute->Option_name = $$attribute_parent_name;
						$product_to_order->attributes[] = $attribute;
						$product_to_order->sale_price = $product_to_order->sale_price + $attribute->Price;
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
				}//else if not array
			}
			$attribute_index = $attribute_index + 1;
		}//WHILE
	
		$association_index = 1;
		if(isset($product->associations))
		{
			while ($association_index <= count($product->associations)) 
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
		$mProducts[] = $product_to_order;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	// Submit Code Starts Here
	if (isset($_POST["btnReOrder"])) //Re-Order
	{
		foreach ($mProducts as $mProd) 
		{
			$cart->addProduct($mProd);
		}

		redirect($SiteUrl .$objRestaurant->url ."/?item=menu&ifrm=load_resturant");
		exit;
	}
	else if (isset($_POST["txtCode"])) //Add To Fav
	{
		$mDeleted = $_POST["txtDel"];
		$products = array();
		$mLoopCount = 0;
		foreach ($mProducts as $mProd) 
		{
			if (strpos($mDeleted, ",".$mLoopCount.",")===false)
			{
				$products[] = $mProd;
			}
			$mLoopCount++;
		}
		
		$mTip = 0;
		if (isset($_POST['txtTipFav']))
		{
			if (is_numeric($_POST['txtTipFav']))
			{
				$mTip = $_POST['txtTipFav'];
			}
		}
		
		$loggedinuser->addMenuToCustomerFavorites($_POST['txtCode'],serialize($products),1,$_POST['rbPDFav'], $mTip);
		
		redirect($SiteUrl .$objRestaurant->url ."/?item=menu&ifrm=load_resturant");
		exit;
	}
	// Submit Code Ends Here
 	
	$mOrderArray=dbAbstract::returnArray($mResult);
	
	if($mOrderArray["coupon_discount"] == "")
	{
		$coupon_discount = '0.00';
	}
	else
	{
		$coupon_discount = number_format($mOrderArray["coupon_discount"], 2);
	}
	
	$mDelCh = $mOrderArray["delivery_chagres"];
	$mTax = $mOrderArray["Tax"];
	$mTotal = $mOrderArray["Totel"];

	$mGrandTotal=0;
	$mSQL ="SELECT OD.*, P.prd_id, P.item_title, P.item_code, P.retail_price, P.sale_price FROM orderdetails OD, product P WHERE OD.orderid = ".$mOrderID." AND P.prd_id=OD.pid";
	$mResult= dbAbstract::Execute($mSQL);
?>
<style type="text/css">
.tblH tr
{
	height: 23px;
}
</style>
<table class="tblH" style="width: 100%; font-size: 12px;" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td style="width: 5%;"></td>
		<td colspan="2" style="width: 90%;">
			<strong style="font-size: 16px; color: #900;">ORDER SUMMARY</strong>
		</td>
	</tr>
	<tr style="background-color:#EFEFEF;">
		<td style="width: 5%;"></td>
		<td style="width: 25%;">
			<strong>Order No:</strong>
		</td>
		<td style="width: 70%;">
			<?=$mOrderArray["OrderID"]?>
		</td>
	</tr>
	<tr>
		<td style="width: 5%;"></td>
		<td style="width: 25%;">
			<strong>Restaurant Name:</strong>
		</td>
		<td style="width: 70%;">
			<?=$objRestaurant->name?>
		</td>
	</tr>
	<tr style="background-color:#EFEFEF;">
		<td style="width: 5%;"></td>
		<td style="width: 25%;">
			<strong>Payment Method:</strong>
		</td>
		<td style="width: 70%;">
			<?=$mOrderArray["payment_method"]?>
		</td>
	</tr>
	<tr>
		<td style="width: 5%;"></td>
		<td style="width: 25%;">
			<strong>Order Receiving Method:</strong>
		</td>
		<td style="width: 70%;">
			<?=$mOrderArray["order_receiving_method"]?>
		</td>
	</tr>
	<tr style="background-color:#EFEFEF;">
		<td style="width: 5%;"></td>
		<td style="width: 25%;">
			<strong>Deliver Date &amp; Time:</strong>
		</td>
		<td style="width: 70%;">
			<?=$mOrderArray["DesiredDeliveryDate"]?>
		</td>
	</tr>
	<tr>
		<td style="width: 5%;"></td>
		<td style="width: 25%;">
			<strong>Submitation Date &amp; Time:</strong>
		</td>
		<td style="width: 70%;">
			<?=date("m-d-Y h:i:s", strtotime($mOrderArray["submit_time"]))?>
		</td>
	</tr>
	<tr style="background-color:#EFEFEF;">
		<td style="width: 5%;"></td>
		<td style="width: 25%;">
			<strong>Special Requests/Notes:</strong>
		</td>
		<td style="width: 70%;">
			<?=((trim(stripslashes(stripcslashes($mOrderArray["DelSpecialReq"])))=="")?"NA":trim(stripslashes(stripcslashes($mOrderArray["DelSpecialReq"]))))?>
		</td>
	</tr>
	<?php
	if (dbAbstract::returnRowsCount($mResult)>0)
	{
	?>
	<tr>
		<td style="width: 5%;"></td>
		<td colspan="2" style="width: 90%;">
			<strong style="font-size: 16px; color: #900;">ITEM DETAILS</strong>
		</td>
	</tr>
	<?php
		$mLoopCount = 1;
		while($mRow=dbAbstract::returnArray($mResult))
		{
			$mProductID= $mRow["prd_id"]
		?>
		<tr>
			<td style="width: 5%;"></td>
			<td colspan="2">
				<strong style="font-size: 14px;  color: #039;">Item <?=$mLoopCount?></strong>
			</td>
		</tr>
		<tr style="background-color:#EFEFEF;">
			<td style="width: 5%;"></td>
			<td style="width: 25%;">
				<strong>Item Code:</strong>
			</td>
			<td style="width: 70%;">
				<?=((trim($mRow["item_code"])=="")?"NA":trim($mRow["item_code"]))?>
			</td>
		</tr>
		<tr>
			<td style="width: 5%;"></td>
			<td style="width: 25%;">
				<strong>Item Title:</strong>
			</td>
			<td style="width: 70%;">
				<?=$mRow["item_title"]?>
			</td>
		</tr>
		<tr style="background-color:#EFEFEF;">
			<td style="width: 5%;"></td>
			<td style="width: 25%;">
				<strong>Quantity:</strong>
			</td>
			<td style="width: 70%;">
				<?=$mRow["quantity"]?>
			</td>
		</tr>
		<tr>
			<td style="width: 5%;"></td>
			<td style="width: 25%;">
				<strong>Special Notes:</strong>
			</td>
			<td style="width: 70%;">
				<?=((trim(stripslashes(stripcslashes($mRow["RequestNote"])))=="")?"NA":trim(trim(stripslashes(stripcslashes($mRow["RequestNote"])))))?>
			</td>
		</tr>
		<?php
		$mPrice = 0;
		if ($mRow['sale_price']!="0") 
		{
			$mPrice = $mRow['sale_price'];
		} 
		else 
		{
			$mPrice = $mRow['retail_price'];
		}
		?>
		<tr style="background-color:#EFEFEF;">
			<td style="width: 5%;"></td>
			<td style="width: 25%;">
				<strong>Item Price:</strong>
			</td>
			<td style="width: 70%;">
				<?=$currency.$mPrice?>
			</td>
		</tr>	
		<?php 
		$mFlag=0;
		if ($mRow['extra']) 
		{
			$mFlag=1;
		?>
		
        <tr> 
			<td style="width: 5%;"></td>
			<td style="width: 25%;">
				<strong>Extras:</strong>
			</td>
			<td style="width: 70%;">

            <?php 
				$sybTotelAttribute=0;
				$Tot_atrib_price_Plus=0;
				$Tot_atrib_price_mines=0;
				$TemOptNme="";
				$OptType=0;
			    $attribueArray=explode("~", $mRow['extra']);
                for( $t=0;$t <count($attribueArray);$t++)
				{
					$attribueArrayString=str_replace("|","~",$attribueArray[$t]);
					$ATrOptions= explode("~", $attribueArrayString);					    
					if(!empty($ATrOptions[0]))
					{
						$Atribut_Opt=trim($ATrOptions[0]);
						$Atribut_Opt=str_replace("^",'"',$Atribut_Opt);
						$Atribut_Opt=str_replace("'",'"',$Atribut_Opt);
						$OptionNameQry=dbAbstract::Execute("SELECT option_name,Type FROM attribute WHERE ProductID=".$mProductID);
						$OptonNameRs=dbAbstract::returnRow($OptionNameQry);
											
						if($OptonNameRs[1]==2)
						{  
							if($OptonNameRs[0]!=$TemOptNme)
							{ 
								if($OptType==2)
								{
									echo "<BR>";
								}
								$Option_Name=str_replace("Select","",str_replace("select","",$OptonNameRs[0]));
								echo "<strong>".$Option_Name." "."</strong>";
								$flagcoma=1;
								$TemOptNme=$OptonNameRs[0];
							}
							echo $ATrOptions[0];
							if(!empty($ATrOptions[1]))
							{
								$price = $ATrOptions[1];
								$price1 =  $ATrOptions[1];
								if ($price!='0' || $price!='0.00') 
								{
									$decider = "";
									if ($price<0) 
									{
										$decider = "Subtract";
										$price1	= trim(substr($price1,1)); 
										$Tot_atrib_price_mines=$Tot_atrib_price_mines + $price;
									} 
									else 
									{
										$Tot_atrib_price_Plus=$Tot_atrib_price_Plus + $price;
										$decider = "Add";
									}
									$price = "|".$price; 
									$price1 = " - $decider ".$currency .$price1; 
								} 
								else 
								{
									$price = ""; 
									$price1 = ""; 
								}
								echo $price1;
							}
							if(isset($attribueArray[$t+1]))
							{
 								$attribueArrayString=str_replace("|","~",$attribueArray[$t+1]);
								$ATrOptions= explode("~", $attribueArrayString);
								if(!empty($ATrOptions[0]))
								{
									$Atribut_Opt=trim($ATrOptions[0]);
									$Atribut_Opt=str_replace("^",'"',$Atribut_Opt);
									$Atribut_Opt=str_replace("'",'"',$Atribut_Opt);
									$OptionNameQry2=dbAbstract::Execute("SELECT option_name,Type from attribute WHERE ProductID=".$mProductID);
									$OptonNameRs2=dbAbstract::returnRow($OptionNameQry2);
									if($OptonNameRs2[1]==2)
									{
										if($OptonNameRs2[0]==$OptonNameRs[0])
										{ 
											echo ",";
										}	
									}
								} 	 	
							}
							else
							{
								echo "";
							}
							$OptType=$OptonNameRs[1];
						}
						else
						{
							$Option_Name=str_replace("Select","",str_replace("select","",$OptonNameRs[0]));
							echo "<strong>".$Option_Name." "."</strong>";
							$AttbuteShow=str_replace("^",'"',$ATrOptions[0]);
							echo $AttbuteShow." ";
							if(!empty($ATrOptions[1]))
							{
								$price = $ATrOptions[1];
								$price1 =   $ATrOptions[1];
								if ($price!='0' || $price!='0.00') 
								{
									$decider = "";
									if ($price<0) 
									{
										$decider = "Subtract";
										$price1	= trim(substr($price1,1)); 
										$Tot_atrib_price_mines=$Tot_atrib_price_mines + $price;
									} 
									else 
									{
										$Tot_atrib_price_Plus=$Tot_atrib_price_Plus + $price;
										$decider = "Add";
									}
									$price = "|".$price; 
									$price1 = " - $decider ".$currency .$price1; 
								} 
								else 
								{
									$price = ""; 
									$price1 = ""; 
								}
								echo $price1;
							}
							else
							{
								$Tot_atrib_price_Plus=$AttbuteShow;
							}
						?>
                    <br> 
                    <?php 
					}
				}
			}  
			?>                  
			</td>
		</tr>
		<?php 
		}
		if($mRow['associations']) 
		{
			if ($mFlag == 1)
			{
				$mFlag = 0;
			?>
		<tr style="background-color:#EFEFEF;">
			<?php	
			}
			else
			{
			$mFlag = 1;
			?>
		<tr>
			<?php
			}
		?>
			<td style="width: 5%;"></td>
			<td style="width: 25%;">
				<strong>Associated Items:</strong>
            </td>
			<td style="width: 70%;">  
				<?php echo str_replace('|','- add '.$currency,$mRow['associations']); ?>
			</td>
		</tr>
		<?php
		}
		if ($mFlag == 1)
		{
			$mFlag = 0;
		?>
		<tr style="background-color:#EFEFEF;">
		<?php	
		}
		else
		{
		$mFlag = 1;
		?>
        <tr>
		<?php
		}
			$assocItemArr = explode("~",$mRow['associations']);
			$assocTotalPrice = 0;
			for($j=0; $j<count($assocItemArr); $j++) 
			{
				$assocOptions= explode("|", $assocItemArr[$j]);
				$assocPrice = (count($assocOptions)>1 ? $assocOptions[1]:0);
				$assocTotalPrice = $assocTotalPrice + $assocPrice; 
			}
		?>
			<td style="width: 5%;"></td>
			<td style="width: 25%;">
		<?php 
				$itemTotalPrice = $mPrice + $Tot_atrib_price_Plus + $assocTotalPrice;
				$Tot_atrib_price_Plus = 0;
		?>
				<strong>Item Total Price:</strong>
			</td>
			<td style="width: 70%;">
				<?=$currency.$itemTotalPrice;?>
			</td>
		</tr>
		<?php 
			$TotalPlus_atribue= $cart_price+$Tot_atrib_price_Plus+$Tot_atrib_price_mines;
			$itemQuanTotel=$mRow['quantity']*$TotalPlus_atribue;
			$mLoopCount++;
		}
		?>
		
		<tr>
			<td colspan="3">
			</td>
		</tr>
		
		
		<?php
		if ($mFlag == 1)
		{
			$mFlag = 0;
		?>
		<tr style="background-color:#EFEFEF;">
		<?php	
		}
		else
		{
		$mFlag = 1;
		?>
        <tr>
		<?php
		}
		?>
			<td style="width: 5%;"></td>
			<td style="width: 25%;"><strong>Coupon Discount:</strong></td>
			<td style="width: 70%;"><?=$currency.$coupon_discount ?></td>
		</tr>
		<?php
		if ($mFlag == 1)
		{
			$mFlag = 0;
		?>
		<tr style="background-color:#EFEFEF;">
		<?php	
		}
		else
		{
		$mFlag = 1;
		?>
        <tr>
		<?php
		}
		?>
			<td style="width: 5%;"></td>
			<td style="width: 25%;"><strong>Delivery Charges:</strong></td>
			<td style="width: 70%;"><?=$currency?><? echo number_format($mDelCh,2);?> </td>
		</tr>
		<?php 
		if($mTax) 
		{
			if ($mFlag == 1)
			{
				$mFlag = 0;
		?>
		<tr style="background-color:#EFEFEF;">
		<?php	
			}
			else
			{
				$mFlag = 1;
		?>
        <tr>
		<?php
			}
		?>	
			<td style="width: 5%;"></td>
			<td style="width: 25%;"><strong>Tax:</strong></td>
			<td style="width: 70%;"><?=$currency?><? echo number_format($mTax,2);?></td>
		</tr>
		<?php 
		}
		$mMainTotal =  $mTotal;
		if ($mFlag == 1)
		{
			$mFlag = 0;
		?>
		<tr style="background-color:#EFEFEF;">
		<?php	
		}
		else
		{
		$mFlag = 1;
		?>
        <tr>
		<?php
		}
		?>
			<td style="width: 5%;"></td>
			<td style="width: 25%;"><strong>Total: </strong></td>
			<td style="width: 70%;"><?=$currency?><? echo number_format($mMainTotal,2);?></td>
		</tr>
		
		
		
		
		
		
		
		
	<?php		
	}
	?>
</table>
<table class="tblH" style="width: 100%; font-size: 12px;" border="0" cellpadding="0" cellspacing="0">
	<tr style="height: 20px;">
		<td colspan="2">
		</td>
	</tr>
	<tr>
		<td style="width: 5%;">
		</td>
		<td>
			<div style="width: 32%;">
				<div style="float: left;">
					<form id="myForm" name="myForm" method="post" action="?item=orderhistory&ifrm=orderhistory&Orderid=<?=$mOrderID?>">
						<input style="cursor: hand; cursor: pointer;" type="submit" value="Re-order Online" id="btnReOrder" name="btnReOrder"/>
					</form>
				</div>
				<div style="clear: none;"></div>
				<div style="float: right;">
					<a id="hrefFB" style="text-decoration: none;" href="#dvFav">
						<input style="cursor: hand; cursor: pointer;" type="button" value="Add to favorites"  id="btnFav" name="btnFav"/>
					</a>
				</div>
			</div>
				<div style="display: none; border-radius:7px;">
					<div id="dvFav" style="width: 500px; border-radius:7px;">
					<form id="myForm1" name="myForm1" method="post" action="?item=orderhistory&ifrm=orderhistory&Orderid=<?=$mOrderID?>">
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					<?php
						$favItems = array();
						foreach ($loggedinuser->arrFavorites as $favItem)
						{
							array_push($favItems, trim(strtolower($favItem->title)));
						}
						?>
						<script type="text/javascript" language="javascript">
						
							$(document).ready(function() {
								$("#addtofavTMP").die('click').live("click", function()
								{
									if ($.trim($("#txtCode").val())=="")
									{
										$("#spnCodeError").show();
										return false;
									}
									else
									{
										$('#spnCodeError').hide();
									}
									
									var jsFav = <?php echo json_encode($favItems); ?>;
									if (jsFav.indexOf($.trim($('#txtCode').val().toLowerCase())) >= 0)
									{
										$('#spnError').show();
										return false;	
									}
									else
									{
										$('#spnError').hide();
									}
									
									if (isNaN($("#txtTipFav").val()))
									{
										$('#spnInvalid').show();
										return false;
									}
									else
									{
										$('#spnInvalid').hide();
									}
								});
								
								$("#close").die('click').live("click", function()
								{
									$.fancybox.close();
								});
							});
						</script>
						<?php
							$favoritefood=$mProducts;
						?>

						<div>
							<div id="dvItemsTmp" name="dvItemsTmp">
								<div style='font-size: 12px;'>
									<div style='vertical-align: top; text-align: left;'>
										<?php 
										if (($objRestaurant->did_number!='0') && (trim($objRestaurant->did_number)!='')) 
										{
										?>
											<img src="../images/quickfavorite.png" border="0" alt="QUICK FAVORITE" title="QUICK FAVORITE" id="imgQF"/>
										<?php
										}
										else
										{
										?>
											<img src="../images/favouriteorder.png" border="0" alt="QUICK FAVORITE" title="QUICK FAVORITE" id="imgQF"/>
										<?php
										}
										?>				
									</div>
									<div style='margin-top: 10px;'>
										<span style='font-weight: bold; font-size: 13px;'>
											CODE:
										</span>
										&nbsp;&nbsp;&nbsp;
										<span style='color:#36C; font-weight: bold;'>
											<input type="text" id="txtCode" maxlength="12" name="txtCode" />&nbsp;<span style="color: red; display: none;" id="spnCodeError">*</span><span style="color: red; display: none;" id="spnError">Error: Duplicate favorite order name.</span>
										</span>
									</div>
									<script type="text/javascript" language="javascript">
										function deleteItem(pCount)
										{
											mSalesTax = 0;
											mDelCha = 0;
											mTotal = 0;
											mPrice = 0;
											
											if ($("#spnTotal").text().length>0)
											{
												mTotal = $("#spnTotal").text();
											}
											
											if ($("#spnDelC").text().length>0)
											{
												mDelCha = $("#spnDelC").text();
											}
											
											if ($("#spnSalesTax").text().length>0)
											{
												mSalesTax = $("#spnSalesTax").text();
											}
											
											if ($("#price"+pCount).text().length>0)
											{
												mPrice = $("#price"+pCount).text();
											}
											
											mNewTotal = (mTotal-mPrice).toFixed(2)-mSalesTax-mDelCha;
											mNewSalesTax = (<?=$objRestaurant->tax_percent/100?>*mNewTotal).toFixed(2);
											$("#spnSalesTax").text(mNewSalesTax); 
											$("#spnTotal").text(parseFloat(mNewTotal)+parseFloat(mNewSalesTax)+parseFloat(mDelCha)); 
											$("#spnTotal").text(parseFloat($("#spnTotal").text()).toFixed(2));
											$("#tr"+pCount).hide(); 
											$("#hr"+pCount).hide(); 
											$("#sepOne"+pCount).hide(); 
											$("#sepTwo"+pCount).hide(); 
											$("#txtDel").val($("#txtDel").val()+pCount+",");
										}
									</script>
									<table style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>
										<tr style='height: 15px;'>
											<td colspan='7'></td>
										</tr>
										<?php
										$mTotal=0;
										$mCount=0;
										foreach($favoritefood as $ff)
										{
										?>		
										<tr id="tr<?=$mCount?>">
											<td style='width: 56%;' valign='top'>
												<?php echo($ff->item_title); ?><br />
												<table style='width: 100%; margin: 0;' cellpadding='0' cellspacing='0'>
												<?php
												foreach($ff->attributes as $attr) 
												{
												?>
													<tr>
														<td>
															<span style="margin-left: 20px;">
																-&nbsp;&nbsp;<?php echo($attr->Title); ?>
															</span>
														</td>
													</tr>
												<?php
												}
												if ($ff->item_for!='')
												{
												?>
													<tr><td><span style="margin-left: 20px;">This Item For:&nbsp;&nbsp;<?php echo($ff->item_for); ?></span></td></tr>
												<?php
												}
												if ($ff->requestnote!='')
												{
												?>
													<tr><td><span style="margin-left: 20px;">Special Instructions:&nbsp;&nbsp;<?php echo($ff->requestnote); ?></span></td></tr>
												<?php
												}
												?>	
												</table>
											</td>
											<td style='width: 13%;' valign='top'>$
												<?php echo($ff->sale_price); ?>
											</td>
											<td style='width: 2%;' valign='top'>
												x
											</td>
											<td style='width: 5%;' valign='top'>
												<?php echo($ff->quantity); ?>
											</td>
											<td style='width: 5%;' valign='top'>
												=
											</td>
											<td style='width: 15%;' valign='top'>$
												<?php echo($ff->sale_price)*($ff->quantity); ?>
												<?php $mTotal=$mTotal+($ff->sale_price)*($ff->quantity); ?>
												<span id="price<?=$mCount?>" style="display: none;"><?=($ff->sale_price)*($ff->quantity)?></span>
											</td>
											<td style='width: 4%;' align='right' valign='top'>
												<img id='imgBtn' onclick='deleteItem(<?=$mCount?>);' style='cursor: pointer; cusror: hand;' src='../images/Trashbin.png' alt='Remove from favorites' title='Remove from favorites' />
											</td>
										</tr>
										<tr style='height: 10px;' id="sepOne<?=$mCount?>"><td colspan='7'></td></tr>
										<tr style='height: 1px; background-color: black;' id="hr<?=$mCount?>"><td colspan='7'></td></tr>
										<tr style='height: 10px;' id="sepTwo<?=$mCount?>"><td colspan='7'></td></tr>
										<?php
										$mCount=$mCount+1;		
										}
										?>
									</table>
									<table style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>
										<tr style='height: 5px;'>
											<td colspan='2'><input type="hidden" id="txtDel" name="txtDel" value="," /></td>
										</tr>
										<tr>
											<td colspan='2'>
												<script type="text/javascript" language="javascript">
													$(document).ready(function() {	
														$(".rbPDFav").die('change').live("change", function()
														{
															if ($("#rbPDFav1").is(':checked'))
															{
																$("#spnDelC").text(<?=$mDelCh?>);
																$("#trDelC").show();										
																$("#trDelC1").show();								
																$("#spnTotal").text(parseFloat($("#spnTotal").text())+parseFloat(<?=$mDelCh?>)); 
															}
															else if ($("#rbPDFav2").is(':checked'))
															{
																$("#spnDelC").text("");
																$("#trDelC").hide();										
																$("#trDelC1").hide();
																$("#spnTotal").text(parseFloat($("#spnTotal").text())-parseFloat(<?=$mDelCh?>)); 
															}
														});
													});
												</script>
												<input type='radio' name='rbPDFav' id='rbPDFav1' value='1' checked="checked" class="rbPDFav"/>Delivery
												&nbsp;&nbsp;&nbsp;
												<input type='radio' name='rbPDFav' id='rbPDFav2' value='2' class="rbPDFav"/>Pick Up
											</td>
										</tr>
										<tr style='height: 10px;'><td colspan='2'></td></tr>
										<tr style='height: 1px; background-color: black;'><td colspan='2'></td></tr>
										<tr style='height: 10px;'><td colspan='2'></td></tr>
										<tr style='height: 15px;'><td colspan='2'></td></tr>
										<tr>
											<td colspan='2' style='width: 100%;' align='left'>
												Tip Amount:&nbsp;&nbsp;$
												<input type='text' name='txtTipFav' id='txtTipFav' size='8' value='<?php echo($ff->driver_tip); ?>'/>&nbsp;<span style='display: none; color: red;' id='spnInvalid'>Invalid</span>
											</td>
										</tr>
										<tr style='height: 10px;'><td colspan='2'></td></tr>
										<?php
										$mSales_tax_ratio=$objRestaurant->tax_percent;
										$mSalesTax = number_format(($mSales_tax_ratio/100)  * $mTotal,2);
										$mTotal = $mSalesTax+$mTotal+$ff->driver_tip+$mDelCh;
										?>
										<tr>
											<td colspan='2' align='left'>
												Sales Tax:&nbsp;&nbsp;$<span id="spnSalesTax" name="spnSalesTax"><?php echo($mSalesTax); ?></span>
											</td>
										</tr>
										<tr id="trDelC" style='height: 10px;'><td colspan='2'></td></tr>
										<tr id="trDelC1">
											<td colspan='2' align='left'>
												Delivery Charges:&nbsp;&nbsp;$<span id="spnDelC" name="spnDelC"><?=$mDelCh?></span>
											</td>
										</tr>
										<tr style='height: 10px;'><td colspan='2'></td></tr>
										<tr>
											<td colspan='2' style='font-weight: bold; width: 100%; color:#ff0000' align='left'>
												TOTAL:&nbsp;&nbsp;$<span id="spnTotal" name="spnTotal"><?php echo($mTotal); ?></span>
											</td>
										</tr>
										<tr style='height: 10px;'><td colspan='2'></td></tr>
										<tr>
											<td width='20%' align='left' valign='middle'>
						
											</td>
											<td align='left' valign='top'>
						
											</td>
										</tr>
									</table>
								</div>
							</div>
							<div style='float:right;right:15px; top:10px; position: absolute;'>
								<img src='<?php echo($SiteUrl); ?>images/closelabel.gif' alt="Close" title="Close" id='close' style="cursor: pointer; cursor: hand;"/>
							</div>
							<?php 
							if (($objRestaurant->did_number!='0') && (trim($objRestaurant->did_number)!='')) 
							{
							?>
								<input style='float: right;' type='image' name='addtofavTMP' id='addtofavTMP' src='<?php echo($SiteUrl); ?>images/favsave.png' alt='Save' title='Save'/>
							<?php
							}
							else
							{
							?>
								<input style='float: right;' type='image' name='addtofavTMP' id='addtofavTMP' src='<?php echo($SiteUrl); ?>images/favsave1.png' alt='Save' title='Save'/>
							<?php
							}
							?>
						</div>
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
						</form>		
					</div>
				</div>
		</td>
	</tr>
</table>