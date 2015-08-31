<?  

 if(isset($_POST['cc'])){
	 extract($_POST);
	$x_card_num=$_POST['cc'];
	$x_exp_date=$_POST['exd'];
 	if(!isset($_POST['token']) || $_POST['token']=="undefined"){$token='0';}
 
	if(trim($cc)=='' || trim($exd)=='' || $token=='')
	{
		echo json_encode(array("css"=>"alert-error","message"=>"Please enter credit card information"));
		 //@mysql_close($mysql_conn);	
		exit;
	}
	
	$success=0;
	$gateway_token=0;
 
	if($token=="0" ){
  		require_once 'classes/gateways/tokenization/'. $objRestaurant->payment_gateway .'.php';

	if($success==1)
	{
 	 $loggedinuser->saveCCTokenWithExpiry($x_card_num,$gateway_token,1,$exd);
		 echo json_encode(array("css"=>"success","message"=>"Credit card added successfully","card"=>$x_card_num));
	}else {
		echo json_encode(array("css"=>"alert-error","message"=>"Credit card not accepted by gateway","card"=>$x_card_num));
		}
	}else{
		
		$loggedinuser->setUserDefaultCard($token);
		echo json_encode(array("css"=>"success","message"=>"Credit card selected successfully","token"=>$_POST));
		}
 //@mysql_close($mysql_conn);	
exit;
}?>
<div id="box" style="border:#e4e4e4 1px solid; ">
 <h2 style="background-color:#EFEFEF; padding:5px 10px 5px 10px; font-size:16px;margin:0px">Choose your default credit card</h2> 
      <div style="font-size:12px; font-weight:bold;margin-bottom:10px;padding:10px">Please choose default credit card	 
         </div> 
         <div id="tokenmessage" style="text-align:left;display:none;"> </div>
         <?  if (count($loggedinuser->arrTokens)){?>
         <select id="card_token" name="card_token">
       <? foreach($loggedinuser->arrTokens as $token) {
		   $card_type='';
		   $card_num='';
		   $card_ed='';
		   if($token->data_type==AMEX)
			   $card_type="American Express";
		   else  if($token->data_type==VISA)
		  		$card_type="VISA";
		   else  if($token->data_type==MASTER)
		   		$card_type="MasterCard";
		   else  if($token->data_type==DISCOVER)
			   $card_type="Discover";
			 ?>
       <option value="<?= $token->data_2 ?>" <? if($token->data_3=="1") echo "selected";?>><?= $card_type . " ending in ".$token->data_1 ?></option>
       <? } $newcard=' hidden'; $card_num='0000000000000000';$card_ed='0000' ?>
          <option value="0">New Card</option>
       </select>
       <? } ?>
           <div class="newcard<?=$newcard?>">
      		  <div class="unit">
      <div class="left col_heading">Card Number:</div>
      <div  class="left" >
        <input type="text"  size="30"  maxlength="16"   tabindex="2" id="x_card_num" name="x_card_num"   value="<?=$card_num ?>"  />
        <div class="red">* (enter number without spaces or dashes)</div> </div>
      <div class="clear"></div>
    </div>
   			 <div class="unit">
      <div class="left col_heading">Expiration Date:</div>
      <div  class="left" >
        <input type="text"  size="4"  maxlength="4"   tabindex="3" id="x_exp_date" name="x_exp_date"   value="<?=$card_ed ?>" />
        <span class="red">* (mmyy)</span> </div>
      <div class="clear"></div>
    </div>
    </div>
           <input type="button" name="btnsave" id="btnsave"  value=" Save " />
           <p></p>
 </div>
 <div style="float:right;right:25px; bottom:20px; position: absolute;">
	<input id="close" type="image" src="<?=$SiteUrl?>images/closelabel.gif" />
</div>
<script language="javascript">
	$(document).ready(function() 
	{
		$("#close").click(function() 
		{
			$(document).trigger('close.facebox');
		});
	});
</script>
 <style>
 #box{font-size:12px;}
 #card_token{width:250px; margin-left:20px;}
 .left{margin-right:0px;}
 .unit {
	margin:10px 0 10px 10px;
}
.unit .col_heading {
	 
	font-size:13px;
}</style>
 
<script type="text/javascript">
$(function(){
	$("#card_token").change(function(){
		   if($(this).val()=="0"){
				$("#x_card_num").val('');
				$("#x_exp_date").val('');
				$(".newcard").show('slow');
			  
				  
			   }else{
				  
				    $(".newcard").hide('slow',function(){
						 $("#x_card_num").val('0000000000000000');
				   	     $("#x_exp_date").val('0000');
						});
					
				   }
		});
	  $('#btnsave').click(function(){
			var cc,exd,token;
			cc=$("#x_card_num").val();
			exd=$("#x_exp_date").val();
			token=$("#card_token").val();
			var msg=$("#tokenmessage");
			$(msg).attr('class','')
			
			msg.addClass("warning");
			$(msg).html("Please wait...");
			$(this).attr('disabled', 'disabled'); 
		    $.post("?mod=resturants&item=reordercc&ajax=1",{cc:cc,exd:exd,token:token},function(data){
			 
				 
				var msg=$("#tokenmessage");
					$(msg).attr('class','')
				   $(msg).show();
			
			 
				$(msg).addClass(data.css);
				$(msg).html(data.message);
console.log(data);
			
				 $("#btnsave").attr('disabled', ''); 
				 
				
			  },  "json"
		  
		  )
		  
		  });
	  
});
</script>