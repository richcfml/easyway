<?php
$mPost="";
$mLogID = mt_rand(0, mt_getrandmax());
log::write("Log ID", $mLogID, "debug");
if (isset($_GET['ajax'])) 
{
    extract($_GET);
    if (isset($index)) 
    {
    	$cart->remove_Item($index);
    } 
    else if (isset($delivery_type)) 
    {
    	$cart->setdelivery_type($delivery_type);
    } 
    else if (isset($favoritesindex)) 
    {
    	if (isset($loggedinuser->arrFavorites[$favoritesindex])) 
        {
            $favoritefood = $loggedinuser->arrFavorites[$favoritesindex]->food;
            $cart->addfavorites($favoritefood);
        }
    } 
    else if (isset($removefavoritesindex)) 
    {
        if (isset($loggedinuser->arrFavorites[$removefavoritesindex])) 
        {
            $loggedinuser->removeUserFavoriteOrder($removefavoritesindex);
        }
    } 
    else if (isset($rapidreorder)) 
    {
        $loggedinuser->changeRepidReorderingStatus($rapidreorder, ($status == 1 ? 0 : 1));
    }
    else if (isset($findex)) 
    {
        $mFavoriteID=$loggedinuser->arrFavorites[$findex]->id;
        $mTip= $tip;
        $mDM =$DM;
        $loggedinuser->updateFavoriteTipAmountDeliveryMethod($mFavoriteID, $mTip, $mDM); 
    }
}
?>
    <div id="your_summery">Your Order Summary</div>
    <div id="contents">
<?php
$index = -1;
foreach ($cart->products as $prod) 
{
	$index +=1;
        $checkAtt = product::checkAttrAndAssoc($prod->prd_id);
?>  
		<div class="flip">
			<div id="edit_sign"><a href="#" onclick="event.preventDefault();showPopup(<?= $prod->prd_id ?>, <?= $checkAtt->HasAssociates ?> , <?= $checkAtt->HasAttributes ?>,<?= $index ?>);" style="color:#8c1515;"><img border="0" src="../images/gray_edit.gif" height="14px"></a></div>
			<div id="counting"><?= stripslashes(stripcslashes($prod->item_title)) ?></div>
			<div id="minus_sign" class="remove_cart"><a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=cart&amp;index=<?= $index ?>"><img src="../images/minus_sign.gif" width="14" height="14" border="0"></a></div>
			<div id="dollor"><?=$currency?><?= number_format($prod->sale_price * $prod->quantity,2) ?></div>
			<div style="clear:left"></div>
		</div>
<?php 
}
?>
        <!--End flip Div-->
        <div class="subtotal">Subtotal:</div>
        <div class="amount"><?=$currency?><?= number_format($cart->sub_total,2) ?></div>
        <div style="clear:left"></div>
<?php
if ($cart->delivery_type == cart::Delivery) 
{
?>
		<div class="subtotal">Delivery Charges:</div>
		<div class="amount"><?=$currency?><?= number_format($cart->delivery_charges(),2) ?></div>
		<div style="clear:left"></div>
<?php 
} 
if ($cart->driver_tip > 0) 
{
?>
		<div class="subtotal">Driver Tip:</div>
		<div class="amount"><?=$currency?><?= number_format($cart->driver_tip,2) ?></div>
		<div style="clear:left"></div>
<?php 
}
if ($cart->coupon_discount > 0) 
{
?>
		<div class="subtotal">Coupon Discount:</div>
		<div class="amount"><?=$currency?><?= number_format($cart->coupon_discount,2) ?></div>
		<div style="clear:left"></div>
<?php 
} 
if ($cart->vip_discount > 0) 
{ 
?>
		<div class="subtotal">VIP Discount:</div>
		<div class="amount"><?=$currency?><?= number_format($cart->vip_discount, 2) ?></div>
		<div style="clear:left"></div>
<?php 
} 
?>

		<div class="subtotal">Sales Tax:</div>
		<div class="amount"><?=$currency?><?= number_format($cart->sales_tax(),2) ?></div>
		<div style="clear:left"></div>
		<div class="subtotal">Total:</div>
		<div class="amount"><?=$currency?><?= number_format($cart->grand_total(),2) ?></div>
		<div style="clear:left"></div>
		<div class="vertical_line">&nbsp;</div>
<?php
if ($objRestaurant->isOpenHour == 1) 
{
	if (isset($without_loggin)) 
	{
?>             
        <form method="post" name="form1" id="form1" action="?item=checkout">
            <div class="online_ordering" style="font-size: 12px">
	            <input type="submit" name="with_out_login" value="Continue without login" class="pickup_button" style="width: 60%">
            </div>
        </form>
<?php
    } 
	else 
	{
            if (is_numeric($loggedinuser->id)) 
            {
                $mPost = $SiteUrl.$objRestaurant->url."/?item=checkout";
            }
            else
            {
                $mPost = $SiteUrl.$objRestaurant->url."/?item=login";
            }	
?>
        <form method="post" name="form1" id="form1" action="<?=$mPost?>">
            <div class="online_ordering">
<?php 
		if ($objRestaurant->delivery_offer == 1) 
		{ 
?>
	            <input type="submit" name="btncheckout" value="Delivery" class="delivery_button" onclick="return totalVerified();"> 
<?php 
		} 
?>
                <input type="submit" name="btncheckout" value="Pickup" class="pickup_button"> 
            </div>
        </form>
        <!--End contents Div--> 
<?php 
	}
}
?>
    </div>
    <br/>
<?php
    require($site_root_path . "views/customer/favorites.php"); 
?>
<script type="text/javascript">
function totalVerified() 
{
    var cartSubTotal =<?= $cart->sub_total ?>;
    var restMinTotal =<?= $objRestaurant->order_minimum ?>;
    if (cartSubTotal < restMinTotal)
    {
        alert("<?=$java_currency?>" + restMinTotal + " of food required to checkout. Please add more items");
        return false;
    }
    return true;
}

$(function() 
{
    $(".remove_cart a").click(function(e) 
	{
        e.preventDefault();
        postitem($(this));

    });

    $(".addfavoritesorder ,.removefavoritesorder,.rapidreorder").click(function(e) 
	{
        e.preventDefault();

        postitem($(this));
        if ($(this).hasClass("addfavoritesorder")) 
		{
            alert("Favorite order added to cart");
        } 
		else if ($(this).hasClass("rapidreorder")) 
		{
            alert("Rapid Reordering status changed");
        } 
		else 
		{
            alert("Favorite order removed from list");
        }
    });
});

$(".online_ordering a").click(function(e) 
{
    e.preventDefault();
    var action = $(this).attr('href');

    var orderType =<?= $cart->delivery_type ?>;
    if (orderType == 0) 
	{
        alert('Please select Pickup or Delivery');
    } 
	else if (orderType ==<?= cart::Delivery ?> && !totalVerified()) 
	{
        return false;
    } 
	else 
	{
        window.location = action;
    }

});

function postitem(source) 
{
    var url = $(source).attr('href') + '&ajax=1';
    $.ajax({
        url: url,
        type: "POST",
        success: function(data) 
		{
            $('#cart').html(data);
        }
    });
}
<?php 
if (isset($_GET['ajax'])) 
{
?>
	jQuery(document).ready(function($) 
	{
        $('a[rel*=facebox2]').unbind('click.facebox');
        $('a[rel*=facebox2]').facebox();
    });
<?php 
} 
?>
</script>