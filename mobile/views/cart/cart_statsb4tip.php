<?php 
	if(isset($_POST['action'])){
		if($_POST['action']=='updatetip'){
			$tip=currencyToNumber($_POST['tip']);
			//$tip=preg_replace("/[^0-9.]+/","",$_POST['tip']);
			$cart->setdriver_tip($tip);
		}elseif($_POST['action']=='redeemcoupon'){
				$cart->redeemcoupon($_POST['coupon_code']);
		//Added Below Code By Asher

                }elseif($_POST['action']=='vipreward'){
				$cart->apply_vip_discount($_POST['vipreward']);
		}
                //------------------------

	}	 
?>
<div class="bold strip">Subtotal: <span class="normal">$<?= $cart->sub_total?></span></div>
 <? if($cart->delivery_type==cart::Delivery) { ?>
<div class="bold strip">Delivery Charges : <span class="normal">$<?= $cart->rest_delivery_charges?></span></div>
<? }?>
 <? if($cart->driver_tip>0) { ?>
<div class="bold strip">Driver Tip : <span class="normal">$<?= $cart->driver_tip?></span></div>
<? }?>
 <? if($cart->coupon_discount>0) { ?>
<div class="bold strip">Coupon Discount : <span class="normal">$<?= $cart->coupon_discount?></span></div>
<? }?>
<!---Added Below Code By Asher -->
<? if($cart->vip_discount>0) {?>
    <div class="bold strip">VIP Discount : <span class="normal">$<?= $cart->vip_discount?></span></div>
<? }?>
<!--------          ----------------->


<div class="bold strip">Sales Tax: <span class="normal">$<?= $cart->sales_tax()?></span></div>
<div class="bold strip">Total: <span class="normal">$<?= $cart->grand_total()?></span>
<input type="hidden" id="cart_total" name="cart_total" value="<?= $cart->grand_total() ?>" />
 <input type="hidden" id="cart_delivery_charges" name="cart_delivery_charges" value="<?= $cart->delivery_charges() ?>" />
</div>
<?
