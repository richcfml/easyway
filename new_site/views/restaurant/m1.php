<div id="dvOneCol" style="display: none;">
<style type="text/css">
#dhtmltooltip{
	position: absolute;
	width: 300px;
	border: 2px solid #E4E4E4;
	padding: 5px;
	background-color: #F4F4F4;
	visibility: hidden;
	z-index: 100;
	font-size:11px;
	color:#585858;
}

#dhtmltooltip span {
	font-size:14px;
	font-weight:bold;
	color:#000;
}
.products_area {
	height: 73px !important;
	margin: 7px 0;
}
.products_area.compact {
    height: 30px !important;
}
.product_image {
	float: left;
	margin-left: 5px;
}
.product_name {
	width: 60% !important;	
}
</style>
<? if($mSPD == 0){ ?>
	<div id="dhtmltooltip"></div>
	<script src="<? echo $js_root;  ?>dhtmltip.js" type="text/javascript"></script>
<? } ?>
<?php
	if (isset($_GET['faverr']))
	{
		echo("<script>jQuery.facebox({div: '?item=addtofavorite&id=".$_GET["id"]."&ajax=1&err=1'});</script>");
	}
	$objCategory->menu_id = $menuid;

	 // used in header
	$categoryid = (isset($_GET['category']) ? $_GET['category']:"");
	$categories = $objCategory->getcategories();
	$total_cats=count($categories);	
	$half=round($total_cats/2);
	$index=0;
	$loop_index=0;
?>
<table>
	<tr>
		<td valign="top" style="width: 71%;">
			<div class="left_col_inner_block left" style="width: 99% !important;">
			<? while($index<$total_cats){
				$category=$categories[$index];
				?>
				<div class="listing_area">              
					<div class="product" style="width: 94% !important; padding: 3%; !important;">
					<?=stripslashes($category->cat_name)?> <br /><span style="font-size:12px; font-weight:normal;"><?=stripslashes($category->cat_des)?></span></div>
					<? 
					$products=$category->getProducts();
				 
					foreach($products as $prod  ) {
						$image = stripslashes($prod->item_image);
						$image = ($image == "" ? 'no-image-available.jpg' : 'item_images/' . $image); 
					 ?>
					 <div class="products_area<? if($mSPD == 0){ echo " compact";}?>" style="width: 100% !important;">
						 <table style="margin: 0px; width: 100% !important;" cellpadding="0" cellspacing="0" border="0">
							<tr style="height: 40px;">
								<td style="width: 2%;">
								</td>
								<td align="left" style="width: 74%; font-size:12px;">
									<? if($mSPD == "1"){ ?>
										<div class="product_image"><img src="<? echo $client_path . "images/" . $image; ?>" width="70" height="70" border="0" alt="<?=stripslashes($prod->item_title)?>" /></div>
									<? } ?>
									
									
									<? if($mSPD == "1"){ ?>
										<div class="product_name">
											<a <? if ($iscurrentMenuAvaible==0) { ?>  
													href="javascript:alert('menu is not available at this time');" <? 
												} else { ?> 
													href="?item=product&id=<? echo $prod->prd_id?>&ajax=1"  rel="facebox" 
												<? } ?> >
												<?=stripslashes($prod->item_title)?>
											</a><br />
											<span style="font-size:12px;"><? echo stripslashes(stripcslashes($prod->item_des))?></span>						
										</div>
									<? } else {?>
										<div class="product_name">
											<a onMouseover="ddrivetip('<span><?=htmlspecialchars(trim($prod->item_title))?></span><br /><br /><?=htmlspecialchars($function_obj->_esc_xmlchar(trim($prod->item_des)))?>')" onMouseout="hideddrivetip()" <? if ($iscurrentMenuAvaible==0) { ?>  href="javascript:alert('menu is not available at this time');" <? } else { ?> href="?item=product&id=<? echo $prod->prd_id?>&ajax=1"  rel="facebox" <? } ?> ><?=stripslashes($prod->item_title)?></a>
										</div>
									<? } ?>
									
									
								</td>
								<td align="right" style="width: 10%; font-size:12px;">
									<?=$prod->retail_price?>&nbsp;&nbsp;
								</td>
								<?php 
								if(is_numeric($loggedinuser->id)) 
								{
								?>
								<td align="right" style="width: 13%;">
									<a href="?item=addtofavorite&id=<? echo $prod->prd_id?>&ajax=1"  rel="facebox"><img name="imgFav" id="imgFav" class="imgFav" src="<?=$client_path?>images/addasfavorite.png" alt="Add as favorite" title="Add as favorite"/></a>
								</td>
								<?php
								}
								?>
								<td style="width: 1%;">
								</td>
							</tr>
						</table>
					</div>
					 <? } ?>
				</div>
			<?	
				$index++;
			} ?>
			</div>
		</td>
		<td valign="top">
			<div id="body_right_col" style="border: 0px !important;">
				<script language="javascript" type="text/javascript">
				function hideShowDeliveryMethod(val) {
					if( val == "delivery" ) {
						window.location.href="?item=detail&method="+val;
					} else if( val == "pickup" ) {
						window.location.href="?item=detail&method="+val;	
					}
				}


				function PlaceOrder(subtotal, min_order, receiving_method){
					var minitotal = min_order
					var sess_val = "";
					var item_val = "detail"; 
					if(subtotal >= minitotal || receiving_method == 'pickup'){
							if (sess_val!="" || item_val=="logincart") {
								document.form1.action="?item=confirmorder";
							} else {
								document.form1.action="?item=logincart";
							}
							
							if(receiving_method != 'disabled' && receiving_method == '' ) {
								alert('Please select Pickup or Delivery');
								document.form1.action="?item=detail";
					} else {
						
							document.form1.submit();
					}
					}else if(subtotal < minitotal && receiving_method == 'delivery'){
							alert('$'+minitotal+' of food required to checkout. Please add more items');	
					}
				}

				function UpdateCart(){
					document.form1.action="?mod=resturants&item=updatecart";
					document.form1.submit();
				}

				// Go Back where this page was previousely called.
				function ReturnBack() {
					window.location.href="?mod=resturants&item=product";

				}//function
				</script>

				<!-- Loyality Box Code Starts //Gulfam -->
				<div id="dvLB">
				<?php 
				if (isset($mSLB))
				{
					if ($mSLB == 1) 
					{ 
				?>
				<?php 
						if ($loggedinuser->valuetec_card_number > 0)  
						{
				?>  
						<div class="heading">YOUR VIP REWARDS <span class="rewardamount">$<?=$loggedinuser->valuetec_reward?></span></div>
						<table class="listing1" width="100%" cellpadding="0px" cellspacing="0px" border="0" style="border: 1px solid #e4e4e4; border-bottom: none;">
							<tr>
								<td id="rewardwrap">
									<div class="bar">
										<div class="barOuter">
											<? $reward_points = number_format((($loggedinuser->valuetec_points/$objRestaurant->rewardLevel) *100) ,0); ?>
											<div class="barInner" <? if($reward_points >= 5)  {?>  style="width:<?= $reward_points; ?>%;max-width:100%;" <? } ?>></div>
										</div>    
										<div class="points"><?=$loggedinuser->valuetec_points?> pts</div>
										<div class="clear"></div>
									</div>
									<div class="clear"></div>
									<div class="nextreward">Next reward: $<?=$objRestaurant->rewardAmount?> @ <?=$objRestaurant->rewardLevel?> pts</div>
								</td>
							</tr>
						</table>
						<br />
				<?php 
						}  
						elseif ($loggedinuser->valuetec_card_number==0 || empty($loggedinuser->valuetec_card_number))  
						{
				?>
						<table class="listing1" width="100%" cellpadding="0px" cellspacing="0px" border="0" style="border: 1px solid #e4e4e4; border-bottom: none;">
							<tr>
								<td id="rewardwrap" style="width: 250px !important;">
									<table style="width: 100%; margin: 0px;" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td style="width: 55%;">
												<img src="../images/vip.png" />
											</td>
											<td style="font-size: 11px; width: 45%;margin-right: 2px !important;" valign="middle" align="left">
												<a style="text-decoration:none" rel="facebox" href="?item=valutec&ajax=1">Click here to register your VIP card</a>
											</td>
										</tr>
									</table>
									<div class="clear"></div>
								</td>
							</tr>
						</table>
				<?php 
						}
					} 
				}
				?>
				</div>
				<!-- Loyality Box Code Ends //Gulfam -->
				<div id="cart" style="border: 0px !important;">
				 <? 
					require($site_root_path . "views/cart/cart.php");
				  ?>
				  </div>
			</div>
			<div style="clear:left"></div>
		</td>
	</tr>
</table>
</div>