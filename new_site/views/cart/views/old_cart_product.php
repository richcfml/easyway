<div id="body_heading">Shopping Cart - Confirm Order</div>
<div class="text_12px">Please review your order below.</div>
<div id="body_heading" style="float:left; padding-right:10px;">You chose
  <?= $cart->delivery_type==cart::Delivery ?"delivery" :"pickup" ?>
</div>
<?php if ($objRestaurant->delivery_offer == 1)
{
?>
<div style="font-size:14px; font-weight:bold; margin:3px 0px 0px 174px;"> <a href="?item=menu" style="text-decoration:none; color:#CC0000;">Change to
  <?= $cart->delivery_type==cart::Delivery ?"pickup" :"delivery" ?>
  </a></div>
<?php
}
?>
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
  $myurl = $_SERVER['REQUEST_URI'];
  $url = explode('/', $myurl);
  $is_rest_bh_new_promo = product::isRestPartOfNewBHPromo($url[1]);
  if(isset($is_rest_bh_new_promo) && $is_rest_bh_new_promo->bh_new_promotion == 1){
	$lrgr_ordr_id = array();
	foreach ($cart->products as $prod)
	{
        	$bh_item_array = product::productIsBH($prod->prd_id);
		//echo "<pre>";print_r($bh_item_array);echo "</pre>";
		$my_quantity =  number_format($prod->sale_price * $prod->quantity,2);
        	array_push($lrgr_ordr_id, $bh_item_array->prd_id, $my_quantity);
        	$bh_flag =  (strstr( $bh_item_array->item_type, "B" ) ? 1 : 0 );
        	if($bh_flag){
		//if($bh_item_array->item_type == "B"){
                	$arr_tmp[$prod->prd_id] = $prod->sale_price * $prod->quantity;
        	}
	}
	$maxprice = max($arr_tmp);
	$product_key = array_search($maxprice, $arr_tmp);
  }
	$pid_counts = array_count_values($lrgr_ordr_id);  
	#echo "<pre>";print_r($cart);echo "</pre>";
  foreach($cart->products as $prod ) {
	 $index +=1;
	#echo "<pre>";print_r($prod);echo "</pre>";
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
      <? } echo ($arr_index>0 ? ", ":"") . $attr->Title ." ".  ($attr->Price=='0' ?'' : ($attr->Price[0]=='-' ? ' - Subtract': ' - Add'   ).$currency.currencyToNumber(number_format($attr->Price,2))/*preg_replace("/[^0-9.]+/","",number_format($attr->Price,2))*/) ;?>
      <? $arr_index +=1;
                          }
                      // FO RACH ATTR 
                  //   if($arr_index>0) echo "</div>";
                      
      if(count($prod->associations)) echo "<br/><strong>Associated Product:</strong>";
                      
     foreach($prod->associations as $prd) { ?>
                    <?= $prd->item_title . ' - Add '.$currency . number_format($prd->retail_price, 2) ?>
      <? }
                      
                     ?>
    </div>
    <div class="qtys color_black">
      <?= $prod->quantity ?>
    </div>
    <div class="qtys color_black">
    		<?
		if(isset($is_rest_bh_new_promo) && $is_rest_bh_new_promo->bh_new_promotion == 1){
        		if($prod->prd_id == $product_key){
                		if($prod->quantity > 1){
                                	$test_pricing = ($prod->sale_price * ($prod->quantity - 1)) + (($prod->sale_price * ($prod->quantity - ($prod->quantity - 1)))/2);
                                        $my_new_price = $test_pricing;
                                        echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                        echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                }else{
                                	$count = $pid_counts[$prod->prd_id];
                                        $keys = array_keys($lrgr_ordr_id, $prod->prd_id);
                                        if($count > 1){
                                        	$n_arr = array();
                                                foreach($keys as $v){
                                                	$k = $v + 1;
                                                        array_push($n_arr, $lrgr_ordr_id[$k]);
                                                }
                                                $mx = max($n_arr);
                                                if(number_format($prod->sale_price * $prod->quantity,2) == $mx){
                                                	$my_new_price = ($prod->sale_price * $prod->quantity)/2;
                                                        echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                        echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                                }else{
                                                	echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                }
                                        }elseif(isset($test_pricing) && $prod->prd_id == $product_key){
                                                $my_new_price = ($prod->sale_price * $prod->quantity);
                                                echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                        }elseif($prod->prd_id == $product_key){
                                                //echo $prod->prd_id . ' - ' . count($lrgr_ordr_id);
                                                $my_new_price = ($prod->sale_price * $prod->quantity)/2;
                                                echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                        }else{
                                                echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                        }
                                }
				//$is_bh_item = product::productIsBH($product_key);
				//print_r($cart);
                		//if($is_bh_item->item_type == "B"){
                        	/*if($prod->quantity > 1){
					$my_new_price = ($prod->sale_price * ($prod->quantity - 1)) + (($prod->sale_price * ($prod->quantity - ($prod->quantity - 1)))/2);	
				}else{
					$my_new_price = ($prod->sale_price * $prod->quantity)/2;
				}//$cart->coupon_discount = $my_new_price;
					//$cart->coupon_code = "BHNewPromo";
					//echo "<br/><pre>";print_r($cart);echo "</pre><br/>";
                			echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
					echo "</span><br/>" . $currency . number_format($my_new_price,2) . ")";
				*///}else{
                        		#echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                        	//	echo $currency . number_format($prod->sale_price * $prod->quantity,2);
					//echo $currency . number_format($my_new_price,2);
				//}
        		}else{
                		echo $currency . number_format($prod->sale_price * $prod->quantity,2);
        		}
    		}else{
			echo $currency . number_format($prod->retail_price, 2);
		}
		?>
		 <?#=$currency?><?#= number_format($prod->retail_price, 2) ?>
    </div>
    <div class="eachs color_black">
		<?
                if(isset($is_rest_bh_new_promo) && $is_rest_bh_new_promo->bh_new_promotion == 1){
                        if($prod->prd_id == $product_key){
                                if($prod->quantity > 1){
                                        $test_pricing = ($prod->sale_price * ($prod->quantity - 1)) + (($prod->sale_price * ($prod->quantity - ($prod->quantity - 1)))/2);
                                        $my_new_price = $test_pricing;
                                        echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                        echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                }else{
                                        $count = $pid_counts[$prod->prd_id];
                                        $keys = array_keys($lrgr_ordr_id, $prod->prd_id);
                                        if($count > 1){
                                                $n_arr = array();
                                                foreach($keys as $v){
                                                        $k = $v + 1;
                                                        array_push($n_arr, $lrgr_ordr_id[$k]);
                                                }
                                                $mx = max($n_arr);
                                                if(number_format($prod->sale_price * $prod->quantity,2) == $mx){
                                                        $my_new_price = ($prod->sale_price * $prod->quantity)/2;
                                                        echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                        echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                                }else{
                                                        echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                }
                                        }elseif(isset($test_pricing) && $prod->prd_id == $product_key){
                                                $my_new_price = ($prod->sale_price * $prod->quantity);
                                                echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                        }elseif($prod->prd_id == $product_key){
                                                //echo $prod->prd_id . ' - ' . count($lrgr_ordr_id);
                                                $my_new_price = ($prod->sale_price * $prod->quantity)/2;
                                                echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                        }else{
                                                echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                        }
                                }
				//$is_bh_item = product::productIsBH($product_key);
                                //if($is_bh_item->item_type == "B"){
                                /*if($prod->quantity > 1){
                                        $my_new_price = ($prod->sale_price * ($prod->quantity - 1)) + (($prod->sale_price * ($prod->quantity - ($prod->quantity - 1)))/2);
                                }else{
					$my_new_price = ($prod->sale_price * $prod->quantity)/2;
				}	//echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                        echo $currency . number_format($my_new_price,2);
                        	*///}else{
                                //echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                //echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                //echo $currency . number_format($my_new_price,2);
				//	echo $currency . number_format($prod->sale_price * $prod->quantity,2);
				//}
                        }else{
                                echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                        }
                }else{
                        echo $currency . number_format($prod->retail_price, 2);
                }
                ?> 
                <?#=$currency?><?#= number_format($prod->sale_price, 2) ?>
    </div>
    <div class="totals color_black">
		<?
                if(isset($is_rest_bh_new_promo) && $is_rest_bh_new_promo->bh_new_promotion == 1){
                        if($prod->prd_id == $product_key){
                                if($prod->quantity > 1){
                                        $test_pricing = ($prod->sale_price * ($prod->quantity - 1)) + (($prod->sale_price * ($prod->quantity - ($prod->quantity - 1)))/2);
                                        $my_new_price = $test_pricing;
                                        echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                        echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                }else{
                                        $count = $pid_counts[$prod->prd_id];
                                        $keys = array_keys($lrgr_ordr_id, $prod->prd_id);
                                        if($count > 1){
                                                $n_arr = array();
                                                foreach($keys as $v){
                                                        $k = $v + 1;
                                                        array_push($n_arr, $lrgr_ordr_id[$k]);
                                                }
                                                $mx = max($n_arr);
                                                if(number_format($prod->sale_price * $prod->quantity,2) == $mx){
                                                        $my_new_price = ($prod->sale_price * $prod->quantity)/2;
                                                        echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                        echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                                }else{
                                                        echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                }
                                        }elseif(isset($test_pricing) && $prod->prd_id == $product_key){
                                                $my_new_price = ($prod->sale_price * $prod->quantity);
                                                echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                        }elseif($prod->prd_id == $product_key){
                                                //echo $prod->prd_id . ' - ' . count($lrgr_ordr_id);
                                                $my_new_price = ($prod->sale_price * $prod->quantity)/2;
                                                echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                        }else{
                                                echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                        }
                                }
				//$is_bh_item = product::productIsBH($product_key);
                                //if($is_bh_item->item_type == "B"){
                                /*if($prod->quantity > 1){
                                        $my_new_price = ($prod->sale_price * ($prod->quantity - 1)) + (($prod->sale_price * ($prod->quantity - ($prod->quantity - 1)))/2);
                                }else{
					$my_new_price = ($prod->sale_price * $prod->quantity)/2;
                        	}
				echo $currency . number_format($my_new_price,2);
				*///}else{
                                //echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                //echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                //	echo $currency . number_format($prod->sale_price * $prod->quantity,2);
				//}
                        }else{
                                echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                        }
                }else{
                        echo $currency . number_format($prod->retail_price, 2);
                }
                ?> 
                <?#=$currency?><?#= number_format(($prod->sale_price * $prod->quantity), 2) ?>
    </div>
    <div style="clear:both"></div>
  </div>
  <? }// FOR EACH PRODUCT ?>
  <div style="float:left; width:750px; padding:10px; ">
  <?
	if(isset($is_rest_bh_new_promo) && $is_rest_bh_new_promo->bh_new_promotion == 1){
		$is_bh_item = product::productIsBH($product_key);
                $bh_flag =  (strstr( $is_bh_item->item_type, "B" ) ? 1 : 0 );
                if(($prod->prd_id == $product_key) || $bh_flag){	
			echo '<input type="hidden" name="coupon_code" id="coupon_code" value="BHNewPromo" />';
			if($prod->quantity > 1){
                                $my_new_price = (($prod->sale_price * ($prod->quantity - ($prod->quantity - 1)))/2);
                        }else{
				$my_new_price = ($prod->sale_price * $prod->quantity)/2;
			}
			echo '<input type="hidden" name="coupon_discount" id="coupon_discount" value="'. $my_new_price .'" />';
    		}else{
			echo '<div style="float:right;"> <strong>Enter coupon code</strong>&nbsp;&nbsp;
      			<input type="button" name="btncoupon" id="btncoupon" value="Redeem"  />
      			&nbsp;&nbsp;
      			<input name="coupon_code" id="coupon_code" value="" size="3" />
   			</div>';
		}
	}else{
  ?>
    <div style="float:right;"> <strong>Enter coupon code</strong>&nbsp;&nbsp;
      <input type="button" name="btncoupon" id="btncoupon" value="Redeem"  />
      &nbsp;&nbsp;
      <input name="coupon_code" id="coupon_code" value="" size="3" />
    </div>
  <?
	}
   ?>    
            <div style=" float:left;"><strong>For your convenience, you can add gratuity here</strong>&nbsp;
                <input type="text" id="driver_tip"  value="<?=$currency?><?= $cart->driver_tip ?>" size="4"  maxlength="6" name="driver_tip"  />
                <input type="button" name="btntip" id="btntip" value="Add gratuity"    />
            </div>
 
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
