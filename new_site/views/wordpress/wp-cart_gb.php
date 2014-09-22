<?php
if (isset($_GET['ajax'])) {

    extract($_GET);

    if (isset($index)) {
        $cart->remove_Item($index);
    } else if (isset($delivery_type)) {
        $cart->setdelivery_type($delivery_type);
    } else if (isset($favoritesindex)) {
        if (isset($loggedinuser->arrFavorites[$favoritesindex])) {
            $favoritefood = $loggedinuser->arrFavorites[$favoritesindex]->food;
            $cart->addfavorites($favoritefood);
        }
    } else if (isset($removefavoritesindex)) {

        if (isset($loggedinuser->arrFavorites[$removefavoritesindex])) {
            $loggedinuser->removefavoriteOrder($removefavoritesindex);
        }
    } else if (isset($rapidreorder)) {
        $loggedinuser->changerepidreorderingstatus($rapidreorder, ($status == 1 ? 0 : 1));
    }
}
?>



    <div id="your_summery">Your Order Summary</div>
    <div id="contents">
<?php
$index = -1;
foreach ($cart->products as $prod) {
    $index +=1;
    ?>  
            <div class="flip">
      <div id="edit_sign"><a href="?item=product&amp;id=<? echo $prod->prd_id?>&amp;ajax=1&amp;edit=1&amp;index=<?=$index?>&wp_api=product" rel="facebox2" style="color:#8c1515;"><img border="0" src="../images/gray_edit.gif" height="14px"></a></div>
                <div id="counting"><?= stripslashes(stripcslashes($prod->item_title)) ?></div>
      <div id="minus_sign" class="remove_cart"><a href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?item=cart&amp;index=<?=$index?>&wp_api=cart"><img src="../images/minus_sign.gif" width="14" height="14" border="0"></a></div>
                <div id="dollor">$<?= $prod->sale_price * $prod->quantity ?></div>
                <div style="clear:left"></div>
            </div>
<? } ?>
        <!--End flip Div-->
        <!--<div class="delivery_radio">
<? if ($objRestaurant->delivery_offer == 1) { ?>
    <div style="float:left;">
        <input type="radio" name="receiving_method_radio" id="receiving_method_radio" value="<?= cart::Delivery ?>"    <?= $cart->delivery_type == cart::Delivery ? "checked" : "" ?>  />
        delivery</div>
<? } else { ?>
    <div style="float:left;">
        <input type="radio" name="receiving_method_radio" id="receiving_method_radio" disabled="disabled"/>
        delivery</div>
<? } ?>
<div style="float:right; padding-right:12px;">
    <input  type="radio" name="receiving_method_radio" id="receiving_method_radio" value="<?= cart::Pickup ?>" <?= $cart->delivery_type == cart::Pickup ? "checked" : "" ?>  />
    pickup</div>
<div style="clear:left"></div>
</div>-->
        <div class="subtotal">Subtotal:</div>
        <div class="amount">$<?= $cart->sub_total ?></div>
        <div style="clear:left"></div>
<? if ($cart->delivery_type == cart::Delivery) {
    ?>
            <div class="subtotal">Delivery Charges:</div>
            <div class="amount">$<?= $cart->delivery_charges() ?></div>
            <div style="clear:left"></div>


<? } if ($cart->driver_tip > 0) {
    ?>
            <div class="subtotal">Driver Tip:</div>
            <div class="amount">$<?= $cart->driver_tip ?></div>
            <div style="clear:left"></div>

        <? }if ($cart->coupon_discount > 0) {
            ?>
            <div class="subtotal">Coupon Discount:</div>
            <div class="amount">$<?= $cart->coupon_discount ?></div>
            <div style="clear:left"></div>

        <? } if ($cart->vip_discount > 0) { ?>
            <div class="subtotal">VIP Discount:</div>
            <div class="amount">$<?= number_format($cart->vip_discount, 2) ?></div>
            <div style="clear:left"></div>

        <? } ?>

        <div class="subtotal">Sales Tax:</div>
        <div class="amount">$<?= $cart->sales_tax() ?></div>
        <div style="clear:left"></div>
        <div class="subtotal">Total:</div>
        <div class="amount">$<?= $cart->grand_total() ?></div>
        <div style="clear:left"></div>
    
	
  </div><!--End contents Div--> 
  <?	if ($objRestaurant->isOpenHour==1) {  	  
    if (isset($without_loggin)) {
?>             
        <div class="online_ordering"><span>Continue without login</span></div>
<!--        <div class="online_ordering"><a href="?item=checkout"><img  src="../images/without_login.gif" width="255" height="37" border="0"></a> </div>-->
        <form method="post" name="form1" id="form1" action="?item=checkout&amp;wp_api=checkout">
            <div class="online_ordering">
               <!--<a href="<?= $action ?>"><img  src="../images/online_ordering.gif" width="255" height="37" border="0"></a> -->
            <? if ($objRestaurant->delivery_offer == 1) { ?>
                    <input type="submit" name="btncheckout" value="Delivery" class="delivery_button" onclick="return totalVerified();"> 
            <? } ?>
                    <input type="submit" name="btncheckout" value="Pickup" class="pickup_button"> 
            </div>
        </form>
 <?php
    } else {
        $action = "?item=login&amp;wp_api=login";
        if (is_numeric($loggedinuser->id)) {
            $action = "?item=checkout&amp;wp_api=checkout";
        }
    ?>
        <form method="post" name="form1" id="form1" action="<?=$action?>">
            <div class="online_ordering">
               <!--<a href="<?= $action ?>"><img  src="../images/online_ordering.gif" width="255" height="37" border="0"></a> -->
            <? if ($objRestaurant->delivery_offer == 1) { ?>
                    <input type="submit" name="btncheckout" value="Delivery" class="delivery_button" onclick="return totalVerified();"> 
            <? } ?>
                    <input type="submit" name="btncheckout" value="Pickup" class="pickup_button"> 
            </div>
        </form>
        <!--End contents Div--> 
<? }
} ?>
    </div>
			</div>
  <? require("wp-favorites.php");   ?>
</form>
<script type="text/javascript">
    
function totalVerified() {
    var cartSubTotal =<?= $cart->sub_total ?>;
    var restMinTotal =<?= $objRestaurant->order_minimum ?>;
    if (cartSubTotal < restMinTotal)
    {
        alert("$" + restMinTotal + " of food required to checkout. Please add more items");
        return false;
    }
    return true;
}

$(function() {
    $(".remove_cart a").click(function(e) {
        e.preventDefault();
        postitem($(this));

    });

    $(".addfavoritesorder ,.removefavoritesorder,.rapidreorder").click(function(e) {
        e.preventDefault();

        postitem($(this));
        if ($(this).hasClass("addfavoritesorder")) {
            alert("Favorite food added to cart");
        } else if ($(this).hasClass("rapidreorder")) {
            alert("Rapid Reordering status changed");
        } else {
            alert("Favorite removed from list");
        }
    });

/*  $(":radio").click(function(e) {

        url = "?item=cart&ajax=1&delivery_type=" + $(this).val();

        $.ajax({
            url: url,
            type: "POST",
            success: function(data) {
                $('#cart').html(data);

            }
        });
    });*/

});

$(".online_ordering a").click(function(e) {
    e.preventDefault();
    var action = $(this).attr('href');

    var orderType =<?= $cart->delivery_type ?>;
    if (orderType == 0) {
        alert('Please select Pickup or Delivery');
    } else if (orderType ==<?= cart::Delivery ?> && !totalVerified()) {
        return false;
    } else {
        window.location = action;
    }

});
function postitem(source) {
	 var url=$(source).attr('href')+ '&ajax=1&wp_api=cart';
    $.ajax({
        url: url,
        type: "POST",
        success: function(data) {
            $('#cart').html(data);

        }
    });
}
<? if (isset($_GET['ajax'])) { ?>
    jQuery(document).ready(function($) {
        $('a[rel*=facebox2]').unbind('click.facebox');
        $('a[rel*=facebox2]').facebox();
    });
<? } ?>
</script>
