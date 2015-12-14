<?php 
function cartProductExist($title){
	global $cart;
	foreach ($cart->products as $product)
	{
		if($product->item_title == $title) return true;
	}
	return false;
}

if (isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"]) && $_POST['action'] != 'increasequantity')
{
    $mSQL = "SELECT SerializedCart FROM grouporder WHERE GroupID=".$_GET["grpid"]." AND CustomerID=".$_GET["grp_userid"]." AND UserID<>".$_GET["uid"]." AND FoodOrdered=1";
    $mRes = dbAbstract::Execute($mSQL);
    if (dbAbstract::returnRowsCount($mRes)>0)
    {
        while ($mRow=dbAbstract::returnObject($mRes))
        {
            $cartLoop = new $cart();
            $cartLoop = unserialize($mRow->SerializedCart);

            foreach ($cartLoop->products as $prodG)
            {
			  if(!cartProductExist($prodG->item_title)){
                $cart->addProduct($prodG);
			  }
            }
        }
		
    }
}

	if(isset($_POST['action'])){
		if($_POST['action']=='updateDeliverType'){
			$cart->setdelivery_type($_POST['type']);
			
			if (isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"])) {
				$deliveryType = ($_POST['type']==1)? 'Delivery':'Pickup';
				$mSQL = "UPDATE grouporder SET ReceivingMethod='$deliveryType' WHERE CustomerID=" . $_GET["grp_userid"] . " AND GroupID=" . $_GET["grpid"] . " AND UserID=" . $_GET["uid"];
				dbAbstract::Update($mSQL);
			}
			echo $cart->delivery_type;
			exit;
		}
		elseif($_POST['action']=='updatetip'){
			$tip = currencyToNumber($_POST['tip']);
			//$tip=preg_replace("/[^0-9.]+/","",$_POST['tip']); 
			$cart->setdriver_tip($tip);
		}
		elseif($_POST['action']=='redeemcoupon'){
			$msg= $cart->redeemcoupon($_POST['coupon_code']);
			//Added Below Code By Asher
		}
		elseif($_POST['action']=='vipreward'){
			$cart->apply_vip_discount($_POST['vipreward']);
		} 
		elseif ($_POST['action'] == 'increasequantity') {
			$cart->increaseQuantity($_POST['quantity'], $_POST['itemIndex']);
			if (isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"])) {
				$mSQL = "UPDATE grouporder SET SerializedCart='" . prepareStringForMySQL($_SESSION['CART']) . "' WHERE CustomerID=" . $_GET["grp_userid"] . " AND GroupID=" . $_GET["grpid"] . " AND UserID=" . $_GET["uid"];
				dbAbstract::Update($mSQL);
			}
		}
	}	 
?>
<!--------          ----------------->

<dt>Subtotal:</dt>
<dd><?=$currency.number_format($cart->sub_total,2)?></dd>

<dt>Sales tax:</dt>
<dd><?=$currency.number_format($cart->sales_tax(),2)?></dd>

<?php 
if ($cart->delivery_type == cart::Delivery) {
	if($cart->delivery_charges() > 0){
	}elseif(isset($_POST['delivery_charges']) && intval($_POST['delivery_charges']) > 0){
		$cart->rest_delivery_charges = $_POST['delivery_charges'];
	}
?>
    <dt>Delivery:</dt>
    <dd><span id="deliverchrgs"><?= $currency . number_format($cart->rest_delivery_charges,2) ?></span></dd>
<?php }

if($cart->vip_discount > 0){ ?>
<dt>VIP Discount:</dt>
<dd><?=$currency. number_format($cart->vip_discount,2)?></dd>

<?php } 

if($cart->coupon_discount > 0){ ?>
<dt>Coupon Discount:</dt>
<dd><?=$currency. number_format($cart->coupon_discount,2)?></dd>

<?php } 

if($cart->driver_tip >= 0 && $cart->delivery_type==cart::Delivery) { ?>
<dt>Tip amount:</dt>
<dd class=checkout__summary-tip>
<input name="tip" id="tip" placeholder="<?=$currency.$cart->driver_tip?>" value="<?= ($cart->driver_tip >0) ? $cart->driver_tip : '' ?>"/>
</dd>
<?php } ?>

<input type="hidden" id="cart_total" name="cart_total" value="<?=$cart->grand_total()?>" />
<input type="hidden" id="cart_delivery_charges" name="cart_delivery_charges" value="<?= $rest_delivery_charges ?>" />

<script language="javascript">
$(document).ready(function(e) {
	$("#tip").blur(function(){
		$('#tip').val(formatCurrency($('#tip').val()));
            add_delivery_tip($(this).val());
	});
});
</script>
<?php
if($_POST['action']=='redeemcoupon'){
	if($cart->coupon_discount>0){
	?><script type="text/javascript">    
            $(".checkout__coupon-message").html('Coupon discount of <b><?=$java_currency?><?=number_format($cart->coupon_discount,2)?></b> is applied');
		
	</script>
    <?php
	}else {	
	$_SESSION["abandoned_cart_error"]["redeemcoupon"] = array("msg" => $msg, "coupon_code" => $_POST['coupon_code']);
	?>
	<script type="text/javascript">
		$(".checkout__coupon-message").html('<?=$msg; ?>');
		$("#gtotal").html('<?=$currency?>' + $("#cart_total").val());
	</script>
	<?php 
	}
}
?> 