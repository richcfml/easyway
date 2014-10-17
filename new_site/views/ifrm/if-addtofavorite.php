<link href="<?=$SiteUrl?>css/style.css" media="screen" rel="stylesheet" type="text/css"/>
<?php
$favItems = array();
foreach ($loggedinuser->arrFavorites as $favItem)
{
	array_push($favItems, trim(strtolower($favItem->title)));
}
?>
<script type="text/javascript" language="javascript">
$(document).bind('beforeReveal.facebox', function() {
	$("#facebox .content").empty();
});

$("#addtofavTMP").die('click').live("click", function()
{
	if ($.trim($("#txtCode").val())=="")
	{
		$("#spnCodeError").show();
		$("#txtCode").focus();
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
	window.location = document.URL;
});
</script>
<?php
//Submit Code Starts Here
if (isset($_POST["txtCode"]))
{
	$mDeleted = $_POST["txtDel"];
	$products = array();
	$mLoopCount = 0;
	foreach ($cart->products as $mProd) 
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
	
	$loggedinuser->addtoMyFavorites($_POST['txtCode'],serialize($products),1,$_POST['rbPDFav'], $mTip);
	header("location: ". $SiteUrl .$objRestaurant->url ."/?ifrm=load_resturant");
	exit;
}
//Submit Code Ends Here
if($cart->isempty())
{
	header("location: ". $SiteUrl .$objRestaurant->url ."/?ifrm=load_resturant");
	exit;
}

$favoritefood=$cart->products;
?>
<div>
	<form method="post" action="?item=addtofavorite&ifrm=addtofavorite&ajax=1">
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
						<input type="text" id="txtCode" maxlength="12" name="txtCode" />&nbsp;<span style="color: red; display: none;" id="spnCodeError">Please Name Your Favorite Order</span><span style="color: red; display: none;" id="spnError">Error: Duplicate favorite order name.</span>
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
							<?php
							$mDel="";
							$mPic="";
							$mDelTyDis="";
							if($cart->delivery_type==cart::Delivery) 
							{
								$mDel="checked='checked'";
								$mDelTyDis="display: inline;";
							}
							else
							{
								$mPic="checked='checked'";
								$mDelTyDis="display: none;";
							}
							?>
							<script type="text/javascript" language="javascript">
								$(".rbPDFav").change(function()
								{
									if ($("#rbPDFav1").is(':checked'))
									{
										$("#spnDelC").text(<?=$cart->delivery_charges()?>);
										$("#trDelC").show();										
										$("#trDelC1").show();								
										$("#spnTotal").text(parseFloat($("#spnTotal").text())+parseFloat(<?=$cart->delivery_charges()?>)); 
									}
									else if ($("#rbPDFav2").is(':checked'))
									{
										$("#spnDelC").text("");
										$("#trDelC").hide();										
										$("#trDelC1").hide();
										$("#spnTotal").text(parseFloat($("#spnTotal").text())-parseFloat(<?=$cart->delivery_charges()?>)); 
									}
								});
							</script>
							<input type='radio' name='rbPDFav' id='rbPDFav1' value='1' <?=$mDel?> class="rbPDFav"/>Delivery
							&nbsp;&nbsp;&nbsp;
							<input type='radio' name='rbPDFav' id='rbPDFav2' value='2' <?=$mPic?> class="rbPDFav"/>Pick Up
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
					$mTotal = $mSalesTax+$mTotal+$ff->driver_tip+$cart->delivery_charges();
					?>
					<tr>
						<td colspan='2' align='left'>
							Sales Tax:&nbsp;&nbsp;$<span id="spnSalesTax" name="spnSalesTax"><?php echo($mSalesTax); ?></span>
						</td>
					</tr>
					<tr id="trDelC" style='height: 10px;<?=mDelTyDis?>'><td colspan='2'></td></tr>
					<tr id="trDelC1" style='<?=mDelTyDis?>'>
						<td colspan='2' align='left'>
							Delivery Charges:&nbsp;&nbsp;$<span id="spnDelC" name="spnDelC"><?=$cart->delivery_charges()?></span>
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
			<input id='close' type='image' src='<?php echo($SiteUrl); ?>images/closelabel.gif' />
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
		
	</form>
</div>