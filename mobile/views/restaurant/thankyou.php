<section class='menu_list_wrapper'>
    <?
// $total=$cart->grand_total();
// if(!is_numeric($total) || $total<=0 || $cart->order_created===0) {
//	 header("location: ". $SiteUrl .$objRestaurant->url ."/?item=cart" );exit;
//	}
// Replaced this with above code: Edited by Asher//
    $total = $cart->grand_total();

    /* if(!is_numeric($_SESSION['total']) || $_SESSION['total']<=0 ||  $_SESSION['cart']===0 || empty ( $_SESSION['cart'])) {
     header("location: ". $SiteUrl .$objRestaurant->url ."/?item=cart" );exit;
    }*/

//-------------------//
    ?>
    <div  class="border thankyou">
        <div class="bold">Shopping Cart - Thank You </div><br/>
        <div class="normal"> Your order has been received. A copy of your order has been emailed to you. Should you have any questions, please do not hesitate to contact us. <br/><br/><span class="bold"><a href="/<?= $objRestaurant->url ?>/"> Go to Home Page</a> </span></div>
        <div class="clear"></div>
    </div>
    <?php
    if ($objRestaurant->yelp_review_request == 1 && !empty($objRestaurant->yelp_restaurant_url)) {
        ?>
        <div id="yelp_review">
            <div class="yelpclass"> <a href="<?= $objRestaurant->yelp_restaurant_url ?>" target="_blank"><img src="<?= $SiteUrl ?>images/Yelp-Review-Button.jpg" width="236px"></a></div>
            <div style="font-size:19px;padding-top: 12px;margin-left: 10px;">We want you to love your meal.
                If you do, please leave us a <a href="<?= $objRestaurant->yelp_restaurant_url ?>" target="_blank">Yelp review</a>.
                If you are unsatisfied for any reason please <a href="mailto:<?= $objRestaurant->email ?>">let us know</a> so we can make it right</div>
        </div>
    <?php } ?>
    <div class="clear"> </div>
</section>
<?
$cart->destroysession();
$loggedinuser->destroyUserSession();
if(isset($_SESSION['total'])){
     unset($_SESSION['total']);
}
?>
