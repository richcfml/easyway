<script type="text/javascript" language="javascript">
function idleLogout() 
    {
        var t;
        window.onload = resetTimer;
        window.onmousemove = resetTimer;
        window.onmousedown = resetTimer; // catches touchscreen presses
        window.onclick = resetTimer;     // catches touchpad clicks
        window.onscroll = resetTimer;    // catches scrolling with arrow keys
        window.onkeypress = resetTimer;

        function clearCart() 
        {
            location.href = '<?=$SiteUrl.$objRestaurant->url."/?item=clearcart&kiosk=1"?>';
        }

        function resetTimer() 
        {
            clearTimeout(t);
            t = setTimeout(clearCart, 60000);  // time is in milliseconds, 60000 = 60 seconds = 1 minute
        }
    }
    idleLogout();
</script>
<?php
$cart->delivery_type=cart::Pickup;
$cart->save();
$mPost="";
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
			$loggedinuser->updateFavorite_TipAmount_DeliveryMethod($mFavoriteID, $mTip, $mDM); 
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
			<div id="minus_sign" class="remove_cart"><a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=cart&kiosk=1&amp;index=<?= $index ?>"><img src="../images/minus_sign.gif" width="14" height="14" border="0"></a></div>
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
if ($cart->driver_tip > 0) 
{
?>
		<div class="subtotal">Driver Tip:</div>
		<div class="amount"><?=$currency?><?= number_format($cart->driver_tip,2) ?></div>
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
?>	
            <div class="online_ordering">
                <input type="hidden" value="<?= str_replace(".", "", number_format($cart->grand_total(),2)) ?>" id="Amount" name="Amount" />
                <input type="hidden" value="EWO-EVE1" name="MerchantID" />
                <input type="hidden" value="<?=date('YmdHis')?>" name="InvoiceNum" />
                <input type="hidden" value="C02" name="PaymentType" />
                <input type="hidden" value="USD" name="CurrencyIso" />
                <input type="hidden" value="FALSE" name="SignCapture" />
                <input type="hidden" value="<?=$SiteUrl.$objRestaurant->url."/?item=parseresponse&kiosk=1" ?>" name="RedirectURL" />
                <?php
                if ($cart->grand_total()>0)
                {
                ?>
                <a style="text-decoration: none;" href="testDMPOSApp://EWO-EVE1/<?= str_replace(".", "", number_format($cart->grand_total(),2)) ?>/<?=date('YmdHis')?>/C02/USD/FALSE/<?=urlencode($SiteUrl.$objRestaurant->url."/?item=parseresponse&kiosk=1") ?>">
                    <input type="button" name="btncheckout" value="Pay & Order" class="delivery_button"> 
                </a>
                <?php
                }
                else
                {
                ?>
                <script language="javascript" type="text/javascript">
                    function cartEmpty()
                    {
                        swal({
                            title: "",
                            text: "<span style='font-size: 17px; color: #575757; font-weight: 500 !important;'>Cart is empty.</span>",
                            confirmButtonColor: "#11b1b3",
                            html: true
                        });
                    }
                </script>
                    <input style="cursor: pointer; cursor: hand;" type="button" name="btncheckout" value="Pay & Order" class="delivery_button" onclick="cartEmpty();">
                <?php
                }
                ?>
            </div>
        <!--End contents Div--> 
<?php 
} 
?>
    </div>
    <br/>
<script type="text/javascript">
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
        swal({
            title: "",
            text: "<span style='font-size: 17px; color: #575757; font-weight: 500 !important;'>Please select Pickup or Delivery.</span>",
            confirmButtonColor: "#11b1b3",
            html: true
        });
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