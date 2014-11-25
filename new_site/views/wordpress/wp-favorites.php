<link href="<?=$SiteUrl?>css/style.css" media="screen" rel="stylesheet" type="text/css"/>
<style type="text/css">
.learnlink 
{
	font-size: 14px;
	font-weight:bold;
	text-decoration:none;
}

.learnlink:hover
{ 
	color:#e35c00;
}
</style>
<script language="javascript" type="text/javascript">
	$(document).ready(function()	
	{	
		jQuery.fn.center = function () 
		{
				$(this).css("position","absolute");
				$(this).css("top", $("#imgQF").offset().top + "px");
				$(this).css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
				return $(this);
		}
	});
</script>
<div style="border: 0px !important; background-color:#FFF;">
	<div style="margin: 5px;">
		<div style="vertical-align: top; text-align: left;">
			<?php
			$mTooTip = "";
			$mHrefStart = "";
			$mHrefEnd = "";
			$mSrc="";
			if($cart->isempty())
			{
				$mToolTip = "QUICK FAVORITE";
			}
			else
			{
				if (is_numeric($loggedinuser->id)) 
				{
					$mHrefStart = "<a style='text-decoration: none;' href='?item=addtofavorite&ajax=1&wp_api=addtofavorite'  rel='facebox2'>";
					$mHrefEnd = "</a>";
					
					if (($objRestaurant->did_number=='0') || (trim($objRestaurant->did_number)=='') || (strlen($objRestaurant->did_number)<1))
					{
						$mToolTip = "Click to save your order as a favorite";
					}
					else
					{
						$mToolTip = "Click to save your order as a Quick Favorite";
					}
				}
				else
				{
					$mToolTip = "QUICK FAVORITE";
				}
			}
			
			if (($objRestaurant->did_number=='0') || (trim($objRestaurant->did_number)=='') || (strlen($objRestaurant->did_number)<1))
			{
				$mSrc="../images/favouriteorder.png";
			}
			else
			{
				$mSrc="../images/quickfavorite.png";
			}
			?>
			<?=$mHrefStart?><img src="<?=$mSrc?>" border="0" alt="<?=$mToolTip?>" title="<?=$mToolTip?>" id="imgQF" /><?=$mHrefEnd?>
		</div>
		<?php 
		if ($objRestaurant->did_number!='' )
		{ 
		?>
		<div>
			<script type="application/javascript" language="javascript">
			   $("#hrefLM").die('click').live("click", function()
				{
					jQuery.facebox({ div: '#dvLM' });
					
					$('.lnkCM').click(function()
					{ 
						jQuery.facebox({div: '?mod=resturants&item=reordercm&ajax=1&wp_api=resturants'});  
						return false;  
					});
					
					$('.lnkCC1').click(function()
					{ 
						jQuery.facebox({div: '?mod=resturants&item=reordermobile&ajax=1&wp_api=resturants'});  
						return false;  
					});
					
					$('.hrefTerms').click(function()
					{  	
						jQuery.facebox({div: '#dvLearnMore'});  
					}); 
					$('#facebox').center();
				});
			</script>
			<a style="cursor: pointer; cursor: hand; text-decoration: none; font-size: 13px;" class="hrefLM" name="hrefLM" id="hrefLM"><span class="lMore">Learn More</span></a>
			
			<div style="width: 800px; padding:10px;font-weight:normal;font-size:12px; display:none;"  class="dvLearnMore" id="dvLearnMore" name="dvLearnMore">
				<div style="width:98%;height:550px; border:1px solid #EFEFEF;" > 
					<p><b>Terms:</b></p>
					<p>There is no charge for this service; however,&nbsp;<strong>message  and data rates may apply</strong>&nbsp;from your mobile carrier. Subject to the  terms and conditions of your mobile carrier, you may receive text messages sent  to your mobile phone. By providing your consent to participate in this program,  you approve any such charges from your mobile carrier. Charges for text  messages may appear on your mobile phone bill or be deducted from your prepaid  balance. EasyWay INC reserves the right to terminate this SMS service, in whole  or in part, at any time without notice.&nbsp; The information in any message  may be subject to certain time lags and/or delays.&nbsp; EasyWay INC nor our  restaurant clients are responsible for delayed or un-received orders.&nbsp; </p>
					<p>Submission of a food order using this service will result in a  charge from the restaurant. These charges can only be reversed at the  restaurant&rsquo;s discretion which may or may not be granted. It is your responsibility to ensure that only  you or authorized users have access to your mobile phone and your secret  favorite nicknames. EasyWay INC nor our  restaurant clients are responsible for un-intentionally placed or unauthorized  orders.</p>
					<p>Use of this service constitutes your continued agreement to  these terms which may be altered at any time.</p>
					<p><strong>United States participating carriers include:</strong> <br />
					ACS/Alaska, Alltel, AT&amp;T, Bluegrass Cellular, Boost,  Cellcom, Cellone Nation, Cellular One of East Central Illinois, Cellular South,  Centennial, Cincinnati Bell, Cox Communications, Cricket, EKN/Appalachian  Wireless, Element Mobile, GCI, Golden State Cellular, Illinois Valley Cellular,  Immix/Keystone Wireless, Inland Cellular, iWireless, Nex-Tech Wireless, Nextel,  nTelos, Plateau Wireless, South Canaan, Sprint, T-Mobile, Thumb Cellular,  United Wireless, US Cellular, Verizon Wireless, Viaero Wireless, Virgin,  WCC&nbsp;<br />
					Additional carriers may be added.</p>
					<p><strong>Canada participating carriers include:</strong> <br />
					Aliant Mobility, Bell Mobility, Fido, MTS, NorthernTel Mobility,  Rogers Wireless, SaskTel Mobility, Telebec Mobilite, TELUS Mobility, Videotron,  Virgin Mobile Canada, WIND Mobile.&nbsp; Additional carriers may be added.</p>
					<div style="float:right;right:50px; bottom:30px; position: absolute;">
						<input id="close" type="image" src="<?=$SiteUrl?>images/closelabel.gif" onclick="$(document).trigger('close.facebox');"/>
					</div>
				</div>
			</div>
			<div style="width: 430px; padding:10px;font-weight:normal;font-size:12px; display:none;"  class="dvLM" id="dvLM" name="dvLM">
				Quick Favortie lets you order your favorite	meal simply by sending a text message!<br /><br />
				+ Add our number <span style="color: red; font-weight: bold;"><?= $objRestaurant->did_number ?></span> to your	contact list<br />
				+ Add at least one meal to your profile<br />
				+ Register phone and payment method <span style="color: red; font-weight: bold;"><?php if(is_numeric($loggedinuser->id)) {	?><a href="#" class="lnkCM" style="color:red" >here</a>	<?php } else { ?>here<?php } ?></span><br /><br />
				Next time your hungry, just text the name of your favorite meal code to our Quick Favorite number from your mobile phone and your order will be placed. It's that easy!
				you can	update your	mobile number <span style="color: red; font-weight: bold;"><?php if(is_numeric($loggedinuser->id)) {	?><a href="#" class="lnkCC1" style="color:red" >here</a> <?php } else { ?>here<?php } ?></span><br /><br />
				<a style="cursor: pointer; cursor: hand;" id="hrefTerms" name="hrefTerms" class="hrefTerms">Terms & Conditions</a>
				<div style="float:right;right:15px; bottom:10px; position: absolute;">
					<input id="close" type="image" src="<?=$SiteUrl?>images/closelabel.gif"  onclick="$(document).trigger('close.facebox');"/>
				</div>
			</div>
		</div>
		<?php
		}
		?>
		<div style="margin-top: 10px; margin-bottom: 10px; border-top: 1px solid #000000;"/></div>
		<div>
			<?php
			if(count($loggedinuser->arrFavorites)>0) 
			{
			?>
				<?=$mHrefStart?><span  style="font-weight: bold; font-size: 13px; color:#000;" class="favHead" id="spnFavHead" title="<?=$mToolTip?>">FAVORITE ORDERS</span><?=$mHrefEnd?>
			<?php
			}
			?>
		</div>
		<div style="margin-top: 10px;">
			<?php
			if(count($loggedinuser->arrFavorites)>0) 
			{
			?>
				<span  style="font-weight: bold; font-size: 13px; color:#000;" class="favHead">CODE</span>
			<?php
			}
			?>
		</div>
		<?php
		if(count($loggedinuser->arrFavorites)>0) 
		{
		?>
		<div style="margin-top: 10px; margin-bottom: 10px; border-top: 1px solid #000000;"/></div>
		<?php
		}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-size: 11px;">
		<?php
		if(count($loggedinuser->arrFavorites)>0) 
		{
			$mCount = 0;
			$mTotal = 0;
			foreach($loggedinuser->arrFavorites as $favoritesindex=>$favorite)
			{
				$mFavoritefood=$loggedinuser->arrFavorites[$mCount]->food;
				foreach($mFavoritefood as $ff)
				{
					$mTotal=$mTotal+($ff->sale_price)*($ff->quantity);
				}
				$mSales_tax_ratio=$objRestaurant->tax_percent;
				$mSalesTax = number_format(($mSales_tax_ratio/100)  * $mTotal,2);
				$mTip = $loggedinuser->arrFavorites[$mCount]->driver_tip;
				$mTotal = $mSalesTax+$mTotal+$mTip;
		?>
				<tr>
					<td align="left" valign="middle" width="29%">
						<a rel="facebox2" href="<?=$SiteUrl?><?=$objRestaurant->url?>/?item=favdetail&wp_api=favdetail&ajax=1&favoriteindex=<?=$mCount?>" class="favTitle"><?= $favorite->title ?></a>
					</td>
					<td align="left" valign="middle" width="12%">
						<span class="favPrice"><?= $currency.$mTotal ?></span>
					</td>
					<td align="center" valign="middle" width="52%">
						<a rel="facebox2" href="<?=$SiteUrl?><?=$objRestaurant->url?>/?item=favdetail&wp_api=favdetail&ajax=1&favoriteindex=<?=$mCount?>" class="favTitle">view order / reorder</a>
					</td>
					<td align="left" valign="middle" width="7%">
						<a class="removefavoritesorder"  href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?item=cart&wp_api=cart&removefavoritesindex=<?=$favoritesindex?>" title="Remove from favorites"><img src="../images/Trashbin.png"></a>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<div style="margin-top: 2px; margin-bottom: 2px; border-top: 1px solid #000000;"/></div>
					</td>
				</tr>
		<?php
				$mCount = $mCount + 1;
				$mTotal = 0;
			}
		}
		else
		{
		?>
			<tr>
				<td>
					<span class="favHead">You currently have no favorites on your list.</span>
				</td>
			</tr>
		<?php
		}
		?>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 5px;"> 
			<tr>
				<td>
					<?php
					if(is_numeric($loggedinuser->id)) 
					{			  
						if (($objRestaurant->did_number!='0') && (trim($objRestaurant->did_number)!='') && (strlen($objRestaurant->did_number)>0))
						{ 
					?>
						<a rel="facebox2" href="?mod=resturants&wp_api=resturants&item=reordercm&ajax=1" style="color:#36C; font-size: 12px; text-decoration: none;">EDIT QUICK FAVORITE PREFERENCES</a>
					<?php
						}
					}
					?>
				</td>
			</tr>
		</table>
	</div>	
</div>