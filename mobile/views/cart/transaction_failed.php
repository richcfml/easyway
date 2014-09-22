<?
$cart_total = $cart->grand_total();
if (!is_numeric($cart_total) || $cart_total <= 0) {
    redirect($SiteUrl . $objRestaurant->url . "/?item=account");
//    header("location: " . $SiteUrl . $objRestaurant->url . "/?item=account");
    exit;
}
if (!isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "off") {
    redirect($SiteUrl . $objRestaurant->url . "/?item=cart");
//    header("location: " . $SiteUrl . $objRestaurant->url . "/?item=cart");
    exit;
}
$response = $_SESSION['GATEWAY_RESPONSE'];
?>
<section class="menu_list_wrapper">
    <h1>Transaction Failed</h1>
    <div class="alert-error"> <pre><?= $response ?></pre>
    </div>

</section>