<div id="body_heading">Shopping Cart - Confirm Order</div>
<div class="text_12px">Please review your order below.</div>
<div id="body_heading" style="float:left; padding-right:10px;">You chose
  <?= $cart->delivery_type==cart::Delivery ?"delivery" :"pickup" ?>
</div>
<div style="font-size:14px; font-weight:bold; margin:3px 0px 0px 174px;"> <a href="?item=menu" style="text-decoration:none; color:#CC0000;">Change to
  <?= $cart->delivery_type==cart::Delivery ?"pickup" :"delivery" ?>
  </a></div>
<div style=" clear:both"></div>
<div class="top_headings">
  <div class="item font_14px">Item</div>
  <div class="des font_14px">Description</div>
  <div class="qty font_14px">Qty</div>
  <div class="qty font_14px">Item</div>
  <div class="each font_14px">Each</div>
  <div class="total font_14px">Total</div>
  <div style="clear:both"></div>
</div>
<div id="items_bg">
  <? 
$index=-1;
  
  foreach($cart->products as $prod ) {
	 $index +=1;
	// echo "<pre>";print_r($prod);echo "</pre>";
  ?>
  <div id="description_area" >
    <div class="items color_black">
      <?=  stripslashes(stripcslashes($prod->item_title)) ?>
    </div>
    <div class="description color_black"> <strong>Special Instructions</strong>:
      <?= $prod->requestnote ? $prod->requestnote : "None" ?>
      <br>
      <strong>This Item For</strong>:
      <?= $prod->item_for ?>
      <? $last_att='--';
                    $arr_index=0;
                     foreach($prod->attributes as $attr) { 
                      if($attr->Option_name!=$last_att && $last_att!='--')  echo "<br/>";
                      if($attr->Option_name!=$last_att) {$arr_index=0; $last_att=$attr->Option_name;
                      echo " <strong>".$attr->Option_name .":</strong>"
                        ?>
      <? } echo ($arr_index>0 ? ", ":"") . $attr->Title ." ".  ($attr->Price=='0' ?'' : ($attr->Price[0]=='-' ? ' - Subtract': ' - Add'   ).$currency.preg_replace("/[^0-9.]+/","",number_format($attr->Price,2))) ;?>
      <? $arr_index +=1;
                          }
                      // FO RACH ATTR 
                  //   if($arr_index>0) echo "</div>";
                      
      if(count($prod->associations)) echo "<br/><strong>Associated Product:</strong>";
                      
     foreach($prod->associations as $prd) { ?>
      <?= $prd->item_title .' - Add '.$currency. number_format($prd->retail_price,2) ?>
      <? }
                      
                     ?>
    </div>
    <div class="qtys color_black">
      <?= $prod->quantity ?>
    </div>
    <div class="qtys color_black">
      <?=$currency?><?= number_format($prod->retail_price,2) ?>
    </div>
    <div class="eachs color_black"> 
      <?=$currency?><?= number_format($prod->sale_price,2) ?>
    </div>
    <div class="totals color_black"> 
      <?=$currency?><?= number_format(($prod->sale_price * $prod->quantity),2) ?>
    </div>
    <div style="clear:both"></div>
  </div>
  <? }// FOR EACH PRODUCT ?>
  <div style="float:left; width:750px; padding:10px; ">
    <div style="float:right;"> <strong>Enter coupon code</strong>&nbsp;&nbsp;
      <input type="button" name="btncoupon" id="btncoupon" value="Redeem"  />
      &nbsp;&nbsp;
      <input name="coupon_code" id="coupon_code" value="" size="3" />
    </div>
    <? if($cart->delivery_type==cart::Delivery) {?>
    <div style=" float:left;"><strong>For your convenience, you can add gratuity here</strong>&nbsp;
      <input type="text" id="driver_tip"  value="<?=$currency?><?= $cart->driver_tip ?>" size="4"  maxlength="6" name="driver_tip"  />
      <input type="button" name="btntip" id="btntip" value="Add gratuity"    />
    </div>
    <? } ?>
    <div style="clear:both"></div>
    
    <div  style="padding-top:13px;">
      <strong> Select<?= ($cart->delivery_type==cart::Delivery ? "Delivery" :"Pickup"); ?> Date&nbsp;&nbsp; </strong>  
        <select name="serving_date" id="serving_date"  style="width:120px; ">
          <?=$function_obj->get_datelist()?>
        </select>
       <div style="padding-top:13px;"><strong>Select <?= ($cart->delivery_type==cart::Delivery ? "Delivery" :"Pickup"); ?> Time&nbsp;&nbsp;</strong> 
       <select name="serving_time" id="serving_time" style="width:155px; ">
          <option value="0">As Soon as Possible</option>
          <option value="10:00">10:00 AM</option>
          <option value="10:15" >10:15 AM</option>
          <option value="10:30">10:30 AM</option>
          <option value="10:45">10:45 AM</option>
          <option value="11:00">11:00 AM</option>
          <option value="11:15">11:15 AM</option>
          <option value="11:30">11:30 AM</option>
          <option value="11:45">11:45 AM</option>
          <option value="12:00">12:00 PM</option>
          <option value="12:15">12:15 PM</option>
          <option value="12:30">12:30 PM</option>
          <option value="12:45">12:45 PM</option>
          <?
				$val = 13;
				 for($i=1; $i<=10; $i++)
					{
						for($j=0; $j<=45; $j = $j+15)
							{
								if($i < 10){ $hour = '0'.$i;}else{ $hour = $i;}
								if($j == 0){ $mints = '00';}else{	$mints = $j;}
								$option_title = $hour.':'.$mints.' PM';
								$option_val = $val.':'.$mints;?>
                          <option value="<?=$option_val?>">
                          <?=$option_title?>
                          </option>
			  <?   }$val++;
                        
                        }
                    ?>
          <option value="23:00">11:00 PM</option>
        </select>
      </div>
     
    </div>
    
    
  </div>
   
  <div id="counting_area" class="cart_statsarea">
    <? include $site_root_path."views/cart/cart_stats.php"; ?>
  </div>
  <div class="clear"> </div>
</div>