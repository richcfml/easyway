<link href="<?=$SiteUrl?>css/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" language="javascript">
$(document).bind('beforeReveal.facebox', function() {
	$("#facebox .content").empty();
});

function postitemTMP(pFavoriteIndex, pRemIndex) 
{
	var url = '';
	var mRandom = Math.floor((Math.random()*1000000)+1); 
	url="<?=$SiteUrl?><?=$objRestaurant->url?>/?item=favindex&flg=3&favoriteindex="+pFavoriteIndex+"&remindex="+pRemIndex+"&rndm="+mRandom;


	$.ajax
	({
		url: url,
		cache: false,
		type: "POST",
		success: function(data)
		{
			$('#dvItemsTmp').empty();
			$('#addtofavTMP').hide();
			mResult = data.substring(data.indexOf('<favitem_ewo>')+13);
			mResult = mResult.substring(0, mResult.indexOf('</favitem_ewo>'));
			mTip = mResult.substring(mResult.indexOf('tttppp')+6, mResult.indexOf('pppttt'));
			mResult = mResult.substring(0, mResult.indexOf('tttppp'));
			$('#dvItemsTmp').html(mResult);
			$('a[rel*=facebox]').facebox();
		},
		error: function (jqXHR, textStatus, errorThrown) 
		{
			$('a[rel*=facebox]').facebox();
			//alert(jqXHR.status);
			//alert(textStatus);
		}
	});
}

$("#clsAddtoCartTMP").die('click').live("click", function()
{
	var favindex = $(this).attr('index');
	var mRandom = Math.floor((Math.random()*1000000)+1); 
	var url = "<?=$SiteUrl?><?=$objRestaurant->url?>/?item=cart&favoritesindex="+favindex+"&ajax=1&wp_api=cart&rndm="+mRandom;
	$.ajax
	({
		url: url,
		cache: false,
		type: "POST",
		success: function(data)
		{
			$('#cart').html(data);
			$.facebox.close();		 
			$('a[rel*=facebox]').facebox();
			//window.location = document.URL;
		},
		error: function (jqXHR, textStatus, errorThrown) 
		{
			//window.location = document.URL;
			$('a[rel*=facebox]').facebox();
			//alert(jqXHR.status);
			//alert(textStatus);
		}
	});
});

$("#addtofavTMP").die('click').live("click", function()
{
	mTip = $('input[id="txtTip"]:last').val();
	mDelMethod=1;
	if ($('input[id="rbPD1"]:last').is(':checked'))
	{
		mDelMethod=1;
	}
	else if ($('input[id="rbPD2"]:last').is(':checked'))
	{
		mDelMethod=2;
	}
	

	var favindex = $('input[id="rbPD2"]:last').attr('index');
	
	var mRandom = Math.floor((Math.random()*1000000)+1); 
	var url = "<?=$SiteUrl?><?=$objRestaurant->url?>/?item=cart&findex="+favindex+"&tip="+mTip+"&DM="+mDelMethod+"&ajax=1&wp_api=cart&rndm="+mRandom;
	$.ajax
	({
		url: url,
		cache: false,
		type: "POST",
		success: function(data)
		{
			//window.location = document.URL;
			$('#cart').html(data);
			$.facebox.close();		 
			$('a[rel*=facebox]').facebox();
		},
		error: function (jqXHR, textStatus, errorThrown) 
		{
			//window.location = document.URL;
			$('a[rel*=facebox]').facebox();
			//alert(jqXHR.status);
			//alert(textStatus);
		}
	});
});

$("#close").die('click').live("click", function()
{
	window.location = document.URL;
	//$(document).trigger('close.facebox');
});
</script>
<?php
if (isset($_GET['favoriteindex']))
{
	$loggedinuser->loadfavorites();
	
	$favoritefood=$loggedinuser->arrFavorites[$_GET['favoriteindex']]->food;
?>
	<div id="dvItemsTmp" name="dvItemsTmp">
		<div style='font-size: 12px;'>
			<div style='vertical-align: top; text-align: left;'>
				<?php 
				if ($objRestaurant->did_number!='0' && trim($objRestaurant->did_number)!='') 
				{
				?>
					<img src='../images/quickfavorite.png' border='0' alt='QUICK FAVORITE' title='QUICK FAVORITE' />
				<?php
				}
				else
				{
				?>
					<img src='../images/favouriteorder.png' border='0' alt='QUICK FAVORITE' title='QUICK FAVORITE' style="margin-left:-8px" />
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
					<?php echo($loggedinuser->arrFavorites[$_GET['favoriteindex']]->title); ?>
				</span>
			</div>
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
				<tr>
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
					</td>
					<td style='width: 4%;' align='right' valign='top'>
						<img id='imgBtn' onclick='postitemTMP(<?php echo($_GET['favoriteindex']); ?>, <?php echo($mCount); ?>);' style='cursor: pointer; cusror: hand;' src='../images/Trashbin.png' alt='Remove from favorites' title='Remove from favorites' />
					</td>
					</tr>
					<tr style='height: 10px;'><td colspan='7'></td></tr>
					<tr style='height: 1px; background-color: black;'><td colspan='7'></td></tr>
					<tr style='height: 10px;'><td colspan='7'></td></tr>
				<?php
				$mCount=$mCount+1;		
				}
				?>
				</table>
				<table style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>
					<tr style='height: 5px;'>
						<td colspan='2'></td>
					</tr>
				<?php
				$mPU='';
				$mDe='';
				if ($loggedinuser->arrFavorites[$_GET['favoriteindex']]->order_receiving_method==1)
				{
					$mDe = " checked='checked'";
				}
				else if ($loggedinuser->arrFavorites[$_GET['favoriteindex']]->order_receiving_method==2)
				{
					$mPU = " checked='checked'"; 
				}
				?>
					<tr>
						<td colspan='2'>
							<input type='radio' name='rbPD' id='rbPD1' value='1' index='<?php echo($_GET["favoriteindex"]); ?>' <?php echo($mDe); ?> />Delivery
							&nbsp;&nbsp;&nbsp;
							<input type='radio' id='rbPD2' name='rbPD' value='2' index='<?php echo($_GET["favoriteindex"]); ?>' <?php echo($mPU); ?>/>Pick Up
						</td>
					</tr>
					<tr style='height: 10px;'><td colspan='2'></td></tr>
					<tr style='height: 1px; background-color: black;'><td colspan='2'></td></tr>
					<tr style='height: 10px;'><td colspan='2'></td></tr>
					<tr style='height: 15px;'><td colspan='2'></td></tr>
					<tr>
						<td colspan='2' style='width: 100%;' align='left'>
							Tip Amount:&nbsp;&nbsp;$
							<input type='text' name='txtTip' id='txtTip' size='8' value='<?php echo($loggedinuser->arrFavorites[$_GET['favoriteindex']]->driver_tip); ?>'/>
							&nbsp;
							<span style='display: none; color: red;' id='spnInvalid'>Invalid</span>
						</td>
					</tr>
					<tr style='height: 10px;'><td colspan='2'></td></tr>
					<?php
					$mSales_tax_ratio=$objRestaurant->tax_percent;
					$mSalesTax = number_format(($mSales_tax_ratio/100)  * $mTotal,2);
					$mTotal = $mSalesTax+$mTotal+$loggedinuser->arrFavorites[$_GET['favoriteindex']]->driver_tip;
					?>
					<tr>
						<td colspan='2' align='left'>
							Sales Tax:&nbsp;&nbsp;$<?php echo($mSalesTax); ?>
						</td>
					</tr>
					<tr style='height: 10px;'><td colspan='2'></td></tr>
					<tr>
						<td colspan='2' style='font-weight: bold; width: 100%; color:#ff0000' align='left'>
							TOTAL:&nbsp;&nbsp;$<?php echo($mTotal); ?>
						</td>
					</tr>
					<tr style='height: 10px;'><td colspan='2'></td></tr>
					<tr>
						<td width='20%' align='left' valign='middle'>
							<a class='clsAddtoCartTMP' name='clsAddtoCartTMP' id='clsAddtoCartTMP' index='<?php echo($_GET["favoriteindex"]); ?>' style='cursor: hand; cursor: pointer;color:#36C; text-decoration: none; font-weight: bold;'>Re-order online</a>
						</td>
						<td align='left' valign='top'>
							<a id='clsAddtoCartTMP' name='clsAddtoCartTMP' class='clsAddtoCartTMP' index='<?php echo($_GET["favoriteindex"]); ?>' style='cursor: hand; cursor: pointer;'>
								<img src='<?php echo($SiteUrl); ?>images/addtocart1.png' title='Add to Cart' alt='Add to Cart'/>
							</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div style='float:right;right:15px; top:10px; position: absolute;'>
			<input id='close' type='image' src='<?php echo($SiteUrl); ?>images/closelabel.gif' />
		</div>
		<?php 
		if ($objRestaurant->did_number!='0' && trim($objRestaurant->did_number)!='') 
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
<?php
}
?>