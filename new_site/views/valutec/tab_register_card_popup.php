<div id="cardentry_holder">
	<div class="valutec">
  		<center>
    		<h2> Register your V.I.P card and earn rewards with every order</h2>
  		</center>
  		<div class="container">
   		<?php 
		if(!isset($loggedinuser->id)) 
		{
		?>
    		<div class="loginmessage">You must be logged in to activate your card, please login <a href="?item=login">here </a> or click <a href="?item=login">here</a> to create an account</div>
    	<?php 
		} 
		else 
		{ 
		?>
      		<div class="msg_warning hidden"></div>
    		<div class="inputlabel">Enter your V.I.P card number:<font color="#FF0000">*</font></div>
    		<div class="inputtext">
      			<input name="InputCardNumber" id="InputCardNumber" type="text" maxlength="20" value="">
    		</div>
      		<?php 
			if($objRestaurant->useValutec==  2) //GO3
			{
			?>
     		<div style="margin-top: 31px;">
            	<div class="inputlabel">  CVV:<font color="#FF0000">*</font></div>
               	<div style="margin-left: 170px;">   
					<input name="InputCvvNumber" id="InputCvvNumber" type="text" maxlength="5" value="" >
				</div>
            </div>
     		<div style="margin-top: 9px; display: none;">
            	<div class="inputlabel"> Expiration Date: <i>(mmyyyy)</i><font color="#FF0000">*</font></div>
                <div style="margin-left: 170px;">  
					<input name="InputExpirationDate" id="InputExpirationDate" type="text" maxlength="20" value="" >
				</div>
			</div>
        	<?php 
			}
			?>
        	<div style="clear:both"></div>
			<div class="bttn"><input type="button" name="registercard" id="registercard" value="Register card">
				<img id="loader" src="<?=$SiteUrl?>images/loader.gif">
			</div>
		<?php 
		}
		?>
		<br />
  		<br />
  		<br />
		</div>
		<div style="float:right">
			<input id="close" class="close" type="image" src="<?=$SiteUrl?>images/closelabel.gif" />
		</div>
	</div>
</div>
<div id="mail_subscription_entry_holder" style="display:none;"></div>
<script language="javascript">	
$(function() 
{
	$(".close").click(function() 
	{
		$(document).trigger('close.facebox');
		location.reload();
	});
		
	$("#close").live('click',function() 
	{
		$(document).trigger('close.facebox');
		location.reload();
	});
		
		 
	$("#registercard").click(function() 
	{
		var vip_card_number= $.trim($("#InputCardNumber").val());
        var vip_card_Date= "";
        var vip_card_Cvv= "";
		<?php
	    if($objRestaurant->useValutec==  2) //GO3
		{
		?>
		if(vip_card_number.length<16) 
		<?php
		}
		else
		{
		?>
		if(vip_card_number.length<19) 
		<?php
		}
		?>
		{ 
			alert("please enter valid card number ");
			return false;
		}
		<?php
	    if($objRestaurant->useValutec==  2) //GO3
		{
		?>
		var vip_card_Date= $.trim($("#InputExpirationDate").val());
        var vip_card_Cvv= $.trim($("#InputCvvNumber").val());
		
		if(vip_card_Cvv.length<3) 
		{ 
			alert("please enter valid CVV ");return false;
		}
		<?php
		}
		?>
		$("#loader").show();
		var button= $(this)
		$(button).attr("disabled", "disabled");
		
		$.post("?item=register_card&ajax=1",{InputCardNumber:vip_card_number, InputExpDate:vip_card_Date, InputCvv:vip_card_Cvv}  , function (data) 
		{
			$("#loader").hide();
			if(data.success=="0")
			{
				$(".msg_warning").show().append("<div></div>").html(data.message);
			}
			else if(data.success=="1")
			{			 
				$("#mail_subscription_entry_holder").slideDown(1000);
				$("#cardentry_holder").slideUp(1000);
				$("#viprewarmessage").show();
				$("#viprewarmessage").html(data.message);
			}
					 	 
			$(button).removeAttr("disabled");
			}, "json");
		});
			
		$.get("?item=mailinglist&ajax=1" ,function(data) 
		{
			$("#mail_subscription_entry_holder").html(data);
		});
	});
</script>

