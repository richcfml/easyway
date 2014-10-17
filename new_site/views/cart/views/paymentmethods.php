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
<div class="tip_section" id="tip_section">
    <div class="tipmain">Oops!  Looks like you forgot to enter a tip</div>
    <ul style="list-style: none;"><ol><li>
            <div class="tiptext"> <input type="radio" name="rbd_tip" value="1" class="rdbclass"/>Please add 20% (<?=$currency.number_format((20/100)*$cart->grand_total(),2)?>)</div>
        </li>
        <li>
            <div class="tiptext"><input type="radio" name="rbd_tip"  value="2" class="rdbclass"/>Enter my own Amount:
     <input type="text" id="delivery_tip" value="$0" size="4" maxlength="4" name="delivery_tip" class="valid" style="margin-left: 21px;">
   <input type="button" name="btn_delivery_tip" id="btn_delivery_tip" value="Add gratuity"></div>
    </li>
    <li>
        <div class="tiptext"><input type="radio" name="rbd_tip" value="0" class="rdbclass"/>I will not tip on the card</div>
        </li></ol>
    </ul>
   </div>
<div style="text-align:left">
<? if ($objRestaurant->isOpenHour==1) 
{ 
$_SESSION['tmpDelAddressChoice'] = $loggedinuser->delivery_address_choice;
?>
  <input type="button" name="btncheckout" id="btncheckout" value="I Agree & Place Order"  onclick="verifylocation();"   >
 <? } ?>
</div>
</div>