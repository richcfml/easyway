<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$("#btnCloseST").click($.facebox.close);
	
	$(".btnDelete").click(function()
	{
		if (confirm('Are you sure you want to delete this payment method?'))
		{
			var mTokenID = $(this).attr("tokenid");
			var mTokenVal = $(this).attr("tokenval");
			var url = '';
			var mRandom = Math.floor((Math.random()*1000000)+1); 
			url="<?=$SiteUrl?><?=$objRestaurant->url?>/?item=delsavedtoken&delsavedtoken=1&tokenid="+mTokenID+"&rndm="+mRandom;
			$.ajax
			({
				url: url,
				cache: false,
				type: "POST",
				success: function(data)
				{
					mResult = data.substring(data.indexOf('<ewo_result>')+12);
					mResult = mResult.substring(0, mResult.indexOf('</ewo_result>'));
					if ($.trim(mResult).toLowerCase()=="success") //Deleted Successfully
					{
						$("#card_token option[value='"+mTokenVal+"']").remove();
						
						if ($('#card_token').children('option').length==1)
						{
							$("#x_card_num").val('');
							$("#x_exp_date").val('');
							$(".newcard").show('slow');	
							$("#dvExp").hide('slow');
						}	
						
						$("#tr"+mTokenID).css("display","none");
						$("#spnError").val("Payment method Deleted Successfully.");
						$("#spnError").text("Payment method Deleted Successfully.");
						$("#spnError").html("Payment method Deleted Successfully.");
					}
					else
					{
						$("#spnError").val(mResult);
					}
				},
				error: function (jqXHR, textStatus, errorThrown) 
				{
					$("#spnError").val("Error occurred.");
					//alert(jqXHR.status);
					//alert(textStatus);
				}
			});
		}								
	});
});
</script>
<div style="margin: 10px;">
	<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="3">
				<div style="float: left;"><strong>Payment Methods</strong></div>
				<div style="float: right;"><img src="../images/closelabel.gif" style="cursor: pointer; cursor: hand;"	alt="Close" title="Close" id="btnCloseST"/>
			</td>
		</tr>
		<tr style="height: 25px;">
			<td colspan="3">
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<span id="spnError" style="color: red;"></span>
			</td>
		</tr>
		<tr style="height: 10px;">
			<td colspan="3">
			</td>
		</tr>
	<?php
		$mLoopCount = 0;
		foreach($loggedinuser->arrTokens as $token) 
		{
			$mCardType='';
			$mImagePath='';
			if($token->data_type==AMEX)
			{
				$mCardType="American Express";
				$mImagePath='../images/Amex.gif';
			}
			else if($token->data_type==VISA)
			{
				$mCardType="VISA";
				$mImagePath='../images/V.gif';
			}
			else  if($token->data_type==MASTER)
			{
				$mCardType="MasterCard";
				$mImagePath='../images/MC.gif';
			}
			else  if($token->data_type==DISCOVER)
			{
				$mCardType="Discover";
				$mImagePath='../images/Disc.gif';
			}
			else
			{
				$mCardType="VISA";
				$mImagePath='../images/V.gif';
			}
			if ($mLoopCount%2==0)
			{
				$mBGColor="#FFFAF0";
			}
			else
			{
				$mBGColor="#FFFFFF";
			}
			$mLoopCount++;
	?>
		<tr style="height: 40px; background-color: <?=$mBGColor?>" id="tr<?=$token->id?>">
			<td>
				<img src="<?=$mImagePath?>" title="Saved Token" alt="Saved Token"/>
			</td>
			<td>
				<?=$mCardType." ending in ".$token->data_1?>
			</td>
			<td>
				<img src="../images/Trashbin.png" title="Delete" alt="Delete" tokenid="<?=$token->id?>" tokenval="<?=$token->data_2?>" id="btnDelete" class="btnDelete"/>
			</td>
		</tr>
	<?php	
		}
	?>
		<tr style="height: 20px;">
			<td colspan="3">
			</td>
		</tr>
	</table>
</div>