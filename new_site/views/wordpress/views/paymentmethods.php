<div id="paymenmethods">
 <div class="bold" style="padding-top:20px;">Payment Method</div>
    <div class="radio_bttn">
     <? if ($objRestaurant->credit_card==1) { ?>
     <input type="radio"  name="payment_method" id="payment_method" value='1' checked="checked">
      Credit Card Payment &nbsp;&nbsp;&nbsp;&nbsp;
      <? } ?>
      <?  if ($objRestaurant->cash==1) { ?>
        <input type="radio"  name="payment_method" id="payment_method" value='2' <?= $objRestaurant->credit_card==0 ?'checked="checked"' : ""  ?>>
      Cash Payment After <?= ($cart->delivery_type==cart::Delivery ? "Delivery" :"Pickup"); ?>
      <? }  ?>
 
    </div>
    <div style="clear:both"></div>
    <? if($objRestaurant->payment_gateway == 'securePay') { ?>
    <div id="securepaygateway">
     <div class="second_body_heading tokenOptions"  style="padding-top:20px;">Credit Card Option</div>
    <div class="radio_bttn tokenOptions">
      <? if($loggedinuser->gateway_token==0) { ?>
      <input type="radio" name="useToken" id="useToken" value="1" onClick="showhideCCoption(0);" checked/>
      Use the saved card
      <input type="radio" name="useToken" id="useToken" value="0" onClick="showhideCCoption(1);"/>
      Enter new card
      <?  
		$style='style="display:none;"';
		} 
		else  {
			
		?>
      <input type="hidden" name="useToken" id="useToken" value="0">
      <? }?>
      <div id="cardOpt"  <?=$style?>>
        <input type="checkbox" name="tokenization" id="tokenization" value="1"/>
        I would like to save my credit card information for next purchase </div>
    </div>
      <div class="clear"> </div>
    </div>
    <? } ?>
       
    <br/>
<div class="display_radius_msg_area" id="delivery_msg_area"></div>
<div style="text-align:left">
<? if ($objRestaurant->isOpenHour==1) { ?>
  <input type="button" name="btncheckout" id="btncheckout" value="I Agree & Place Order"  onclick="verifylocation();"   >
 <? } ?>
</div>
</div>