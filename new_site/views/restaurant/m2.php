<div id="dvTwoCol">
	<style type="text/css">
		#dhtmltooltip
		{
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
		
		#dhtmltooltip span 
		{
			font-size:14px;
			font-weight:bold;
			color:#000;
		}	
	</style>
	
	<div id="dhtmltooltip"></div>
	<script src="<? echo $js_root;  ?>dhtmltip.js" type="text/javascript"></script>
	
	<?php
		if (isset($_GET['faverr']))
		{
			echo("<script>jQuery.facebox({div: '?item=addtofavorite&id=".$_GET["id"]."&ajax=1&err=1'});</script>");
		}
		$objCategory->menu_id = $menuid;
		$categoryid = (isset($_GET['category']) ? $_GET['category']:"");
		$categories = $objCategory->getcategories();
		$total_cats=count($categories);	
		$half=round($total_cats/2);
		$index=0;
		$loop_index=0;
	?>
	<div id="body_left_col">
	<?php 
	while ($loop_index<2) 
	{ 
	?>
		<div class="left_col_inner_block" <? if($loop_index==1) { ?> style="margin-left:10px;" <? }?>>
		<?php
		while($index<$total_cats)
		{
		$category=$categories[$index];
		?>
			<div class="listing_area">              
				<div class="product">
				<?=stripslashes($category->cat_name)?> <br /><span style="font-size:12px; font-weight:normal;"><?=stripslashes($category->cat_des)?></span></div>
				<?php 
				$products=$category->getProducts();
				foreach($products as $prod) 
				{
				?>
					<div class="products_area" style="height: 40px;">
						<table style="margin: 0px; width: 100%;" cellpadding="0" cellspacing="0" border="0">
							<tr style="height: 40px;">
								<td style="width: 2%;">
								</td>
								<td align="left" style="width: 65%; font-size:12px;" valign="top">
									<div class="product_name"><a  myItemTitle="<?=htmlspecialchars(trim($prod->item_title))?>" myItemDescription="<?=htmlspecialchars($function_obj->_esc_xmlchar(trim($prod->item_des)))?>" myItemImage="<?=trim($prod->item_image)?>" onMouseout="hideddrivetip()"
									<? if ($iscurrentMenuAvaible==0) { ?>  href="javascript:alert('menu is not available at this time');" <? } else { ?> href="?item=product&id=<? echo $prod->prd_id?>&ajax=1"  rel="facebox" <? } ?> ><?=stripslashes($prod->item_title)?></a></div>
								</td>
								<td align="right" valign="top" style="width: 10%; font-size:12px;">
									<div class="product_price"><?=$prod->retail_price?>&nbsp;&nbsp;</div>
								</td>
								<?php 
								if(is_numeric($loggedinuser->id)) 
								{
								?>
									<td align="right" style="width: 23%;">
										<a href="?item=addtofavorite&id=<? echo $prod->prd_id?>&ajax=1"  rel="facebox"><img name="imgFav" id="imgFav" class="imgFav" src="<?=$client_path?>images/addasfavorite.png" alt="Add as favorite" title="Add as favorite"/></a>
									</td>
								<?php
								}
								?>
							</tr>
						</table>	
					</div>
					 
					<div style="clear:both"></div>
			 	<?php 
				} 
				?>
				</div>
			<?php	
			$index++;
			if($index==$half) 
			{
				break;
			}
		} 
		$loop_index++; 
		?>
		</div>
	<?php 
	} 
	?>
	</div>
	 
	<div id="body_right_col" style="float:right;">
		<script language="javascript" type="text/javascript">
			function hideShowDeliveryMethod(val) 
			{
				if( val == "delivery" ) 
				{
					window.location.href="?item=detail&method="+val;
				} 
				else if( val == "pickup" ) 
				{
					window.location.href="?item=detail&method="+val;
				}
			}
	
			function PlaceOrder(subtotal, min_order, receiving_method)
			{
				var minitotal = min_order
				var sess_val = "";
				var item_val = "detail"; 
				if(subtotal >= minitotal || receiving_method == 'pickup')
				{
					if (sess_val!="" || item_val=="logincart") 
					{
						document.form1.action="?item=confirmorder";
					} 
					else 
					{
						document.form1.action="?item=logincart";
					}
				
					if(receiving_method != 'disabled' && receiving_method == '' ) 
					{
						alert('Please select Pickup or Delivery');
						document.form1.action="?item=detail";
					} 
					else 
					{
						document.form1.submit();
					}
				}
				else if(subtotal < minitotal && receiving_method == 'delivery')
				{
					alert('$'+minitotal+' of food required to checkout. Please add more items');	
				}
			}
	
			function UpdateCart()
			{
				document.form1.action="?mod=resturants&item=updatecart";
				document.form1.submit();
			}
	
			// Go Back where this page was previousely called.
			function ReturnBack() 
			{
				window.location.href="?mod=resturants&item=product";
			}//function
	
			jQuery(document).ready(function($) 
			{
				//$('a[rel*=facebox]').facebox();
				$(tipobj).mouseover(function() 
				{
					mouseOnPopupDiv=true;
				});
	
				$(tipobj).mouseout(function() 
				{
					mouseOnPopupDiv=false;
				});
	
				$('.product_name a').mouseover(function(e) 
				{
					console.log('in mouse ove')
					mouseOnProductName=true;
					var itemTitleIs=$(this).attr('myItemTitle');
					var itemDescIs=$(this).attr('myItemDescription');
					var itemImageIs=$(this).attr('myItemImage');
					var imageHTML = itemImageIs ? '<a title="'+itemDescIs+'" href="/images/item_images/'+itemImageIs+'" rel="prettyPhoto" display="inline"><img style="float:right" class="images" src="/images/dummy.png"/></a></div>' : ''
					var myNewHTML=$('<div class="popupDiv"><span>'+itemTitleIs+'</span><br /><br />'+itemDescIs+' '+imageHTML+'</div>');
					ddrivetip(myNewHTML.html());
					positiontip(e);
					$("a[rel^='prettyPhoto']").prettyPhoto()
				});
			});
		</script>
		<?php
		if($objRestaurant->announcement!= "" && $objRestaurant->announce_status == '1') 
		{
		?>
		<div style="background-color:#EAEAEC;  margin-bottom:10px; border:1px solid #A4A4A4; color:#F00; font-size:12px;">
			<div style="float:left; padding:5px 5px;"><img src="<?=$client_path?>images/dialog_warning.png" width="30"  /></div>
			<div style="padding:15px 5px 0px 5px;"><?=$objRestaurant->announcement?></div>
			<br style="clear:both"  />
		</div>
		<?php 
		} 
		?>
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
		<div id="cart">
	 	<?php
			require($site_root_path . "views/cart/cart.php");
	  	?>
	  	</div>
	</div>
	<div style="clear:left"></div>
<div class="clear"></div>
</div>