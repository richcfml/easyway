<?php  
if(isset($_POST['cc']))
{
	extract($_POST);
	$x_card_num=$_POST['cc'];
	$x_exp_date=$_POST['exd'];
	$mPhoneNumber=$_POST['pm'];
 	if(!isset($_POST['token']) || $_POST['token']=="undefined")
	{
		$token='0';
	}
 
	if(trim($cc)=='' || trim($exd)=='' || $token=='')
	{
		echo json_encode(array("css"=>"alert-error","message"=>"Please enter credit card information"));
		//@mysql_close($mysql_conn);	
		exit;
	}
	
	if(trim($mPhoneNumber)=='')
	{
		echo json_encode(array("css"=>"alert-error","message"=>"Please enter valid mobile number"));
		//@mysql_close($mysql_conn);	
		exit;
	}
	
	$success=0;
	$gateway_token=0;
 
	if($token=="0" )
	{
		if ($objRestaurant->payment_gateway == "authoriseDotNet") 
		{
        	$objRestaurant->payment_gateway = "AuthorizeNet";
        }
		$error_message='';
  		require_once 'classes/gateways/tokenization/'. $objRestaurant->payment_gateway .'.php';
                    
        if(!empty ($error_message))
		{
        	echo json_encode(array("css"=>"alert-error","message"=>$error_message));
        }
        else
        {
			if($success==1)
			{
				$loggedinuser->saveCCTokenWithExpiry($x_card_num,$gateway_token,1,$exd);
				if ($loggedinuser->cust_phone1!=$mPhoneNumber)
				{
					$loggedinuser->cust_phone1=$mPhoneNumber;
					$loggedinuser->saveUserPhone();
					echo json_encode(array("css"=>"success","message"=>"Credit card/Phone updated successfully","card"=>$x_card_num));
				}
				else
				{
					echo json_encode(array("css"=>"success","message"=>"Credit card added successfully","card"=>$x_card_num));
				}
			}
			else 
			{
				if ($loggedinuser->cust_phone1!=$mPhoneNumber)
				{
					$loggedinuser->cust_phone1=$mPhoneNumber;
					$loggedinuser->saveUserPhone();
					echo json_encode(array("css"=>"alert-error","message"=>"Credit card not accepted by gateway. Mobile updated successfully.","card"=>$x_card_num));
				}
				else
				{
					echo json_encode(array("css"=>"alert-error","message"=>"Credit card not accepted by gateway","card"=>$x_card_num));
				}
			}
		}
	}
	else
	{	
		$loggedinuser->setUserDefaultCard($token);
		if ($loggedinuser->cust_phone1!=$mPhoneNumber)
		{
			$loggedinuser->cust_phone1=$mPhoneNumber;
			$loggedinuser->saveUserPhone();
			echo json_encode(array("css"=>"success","message"=>"Credit card/Phone updated successfully","token"=>$_POST));
		}
		else
		{
			echo json_encode(array("css"=>"success","message"=>"Credit card selected successfully","token"=>$_POST));
		}
	}
	//@mysql_close($mysql_conn);	
	exit;
}
?>
<div id="box" style="border:#e4e4e4 1px solid; ">
	<h2 style="background-color:#EFEFEF; padding:5px 10px 5px 10px; font-size:16px;margin:0px">Choose your default credit card</h2> 
	<div style="font-size:12px; font-weight:bold;margin-bottom:10px;padding:10px">Please choose default credit card</div> 
	<div id="tokenmessage" style="text-align:left;display:none;"> </div>
	<?php
	if (count($loggedinuser->arrTokens))
	{
	?>
		<select id="card_token" name="card_token" tabindex="1">
		<?php
		foreach($loggedinuser->arrTokens as $token) 
		{
			$card_type='';
			$card_num='';
			$card_ed='';
			if($token->data_type==AMEX)
				$card_type="American Express";
			else if($token->data_type==VISA)
				$card_type="VISA";
			else if($token->data_type==MASTER)
				$card_type="MasterCard";
			else if($token->data_type==DISCOVER)
				$card_type="Discover";
		?>
		<option value="<?= $token->data_2 ?>" <? if($token->data_3=="1") echo "selected";?>><?= $card_type . " ending in ".$token->data_1 ?></option>
	<?php 
		} 
		$newcard=' hidden'; 
		$card_num='0000000000000000';
		$card_ed='0000' ?>
		<option value="0">New Card</option>
	</select>
	<?php 
	} 
	?>
	<div class="newcard<?=$newcard?>">
		<div class="unit">
			<div class="left col_heading" style="width: 100px;">Card Number:</div>
			<div  class="left" >
				<input type="text"  size="30"  maxlength="16"   tabindex="2" id="x_card_num" name="x_card_num"   value="<?=$card_num ?>"  />
				<div class="red">* (enter number without spaces or dashes)</div> 
			</div>
			<div class="clear"></div>
		</div>
		<div class="unit">
			<div class="left col_heading" style="width: 100px;">Expiration Date:</div>
			<div  class="left" >
				<input type="text"  size="4"  maxlength="4"   tabindex="3" id="x_exp_date" name="x_exp_date"   value="<?=$card_ed ?>" />
				<span class="red">* (mmyy)</span> 
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div id="dvMObile">
		<div style="font-size:12px; font-weight:bold;padding:10px; margin-top: 15px;">
			Please enter your mobile number. <br /><br />
			&nbsp;&nbsp;&nbsp;<input type="text" name="phone1" tabindex="4" id="phone1" maxlength="60" value="<?= $loggedinuser-> cust_phone1?>"  />
		</div>
	</div>	
	<br />
	<input type="button" name="btnsave" id="btnsave" tabindex="5" value=" Save " style="margin-left: 20px;" />
	<p></p>
</div>
 <div style="float:right;right:25px; bottom:20px; position: absolute;">
	<input id="close" type="image" tabindex="6" src="<?=$SiteUrl?>images/closelabel.gif"  onclick="$(document).trigger('close.facebox');"/>
</div>
<style type="text/css">
	#box
	{
		font-size:12px;
	}
	
	#card_token
	{
		width:250px; 
		margin-left:20px;
	}
	
	.left
	{
		margin-right:0px;
	}
	
	.unit 
	{
		margin:10px 0 10px 10px;
	}
	
	.unit .col_heading 
	{
		font-size:13px;
	}
</style>
<script src="../js/mask.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	$('#phone1').mask("(999) 999-9999? x99999");
	$("#card_token").change(function()
	{
		if($(this).val()=="0")
		{
			$("#x_card_num").val('');
			$("#x_exp_date").val('');
			$(".newcard").show('slow');
		}
		else
		{		  
			$(".newcard").hide('slow',function()
			{
				$("#x_card_num").val('0000000000000000');
				$("#x_exp_date").val('0000');
			});
	   }
	});
	
	$('#btnsave').click(function()
	{
		var cc,exd,token, pm;
		cc=$("#x_card_num").val();
		exd=$("#x_exp_date").val();
		token=$("#card_token").val();
		pm=$("#phone1").val();
		var msg=$("#tokenmessage");
		$(msg).attr('class','')
			
		msg.addClass("warning");
		$(msg).html("Please wait...");
		$(this).attr('disabled', 'disabled'); 
		$.post("?mod=resturants&item=reordercm&ajax=1",{cc:cc,exd:exd,token:token,pm:pm},function(data)
		{
			var msg=$("#tokenmessage");
			$(msg).attr('class','')
			$(msg).show();
 
			$(msg).addClass(data.css);
			$(msg).html(data.message);
			console.log(data);			
			$("#btnsave").attr('disabled', ''); 
		},  "json")
	 });  
});
</script>