<? 
	if(isset($_POST['action'])){
		if($_POST['action']=='updatetip'){
			$tip=preg_replace("/[^0-9.]+/","",$_POST['tip']); 
			$cart->setdriver_tip($tip);
		}elseif($_POST['action']=='redeemcoupon'){
			$msg=	$cart->redeemcoupon($_POST['coupon_code']);
		}elseif($_POST['action']=='vipreward'){
				$cart->apply_vip_discount($_POST['vipreward']);
		}
	
	}	 
?>
<div id="counting_left_col">
  <ul>
    <li>Subtotal</li>
   <? if($cart->delivery_type==cart::Delivery) { ?> <li>Delivery Charges</li> <? }?>
   <? if($cart->driver_tip>0 && $cart->delivery_type==cart::Delivery) { ?><li>Driver Tip</li> <? } else if($cart->driver_tip>0 && $cart->delivery_type==cart::Pickup){ ?><li>Tip</li> <? }?>
   <? if($cart->coupon_discount>0) { ?><li>Coupon Discount</li> <? }?>
   <? if($cart->vip_discount>0) { ?><li>VIP Discount</li> <? }?>
    <li>Sales Tax</li>
    <li><b>Total</b></li>
  </ul>
</div>
<div id="counting_right_col">
  <ul>
    <li><?=$currency?><?= number_format($cart->sub_total,2)?></li>
	<? if($cart->delivery_type==cart::Delivery) { ?> <li><?=$currency?><?= $cart->delivery_charges() ?></li> <? }?>
    <? if($cart->driver_tip>0) { ?><li id="li_tip"><?=$currency?><?= $cart->driver_tip?></li> <? }?>
    <? if($cart->coupon_discount>0) { ?><li><?=$currency?><?= $cart->coupon_discount?></li> <? }?>
     <? if($cart->vip_discount>0) { ?><li id="vipdiscount"><?=$currency?><?=number_format($cart->vip_discount,2)?></li> <? }?>
    <li><?=$currency?><?= $cart->sales_tax()?></li>
 	<li><b><?=$currency?><?= $cart->grand_total()?></b></li>
      <input type="hidden" id="cart_total" name="cart_total" value="<?= $cart->grand_total() ?>" />
      <input type="hidden" id="cart_delivery_charges" name="cart_delivery_charges" value="<?= $cart->delivery_charges() ?>" />
  </ul>
</div>
<div id="bttn">
  <input type="button" value="Edit Order" onclick="javascript:window.location='?item=detail&ifrm=load_resturant'">
</div>
<?
if($_POST['action']=='redeemcoupon'){
	if($cart->coupon_discount>0){
	?><script type="text/javascript">    
		jQuery.facebox('<div class="alert-error"> Coupon discount of <b><?=$java_currency?><?=$cart->coupon_discount?></b> is applied</div>');
	</script>
    <?
	}else {	
	?>
	<script type="text/javascript">
    	 jQuery.facebox('<div class="alert-error"><?= $msg ?></div>');
	</script>
	<? 
	}
	
//if($this->coupon_discount
}
?> 
 