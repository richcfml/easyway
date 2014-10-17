<?
if (isset($_POST['btncheckout'])) {
    if ($_POST['btncheckout'] == 'Pickup') {
        $cart->setdelivery_type(cart::Pickup);
    } else {
        if ($cart->sub_total < $objRestaurant->order_minimum) {
            redirect($SiteUrl . $objRestaurant->url . "/?item=cart");
            //header("location: " . $SiteUrl . $objRestaurant->url . "/?item=cart");
            exit;
        }
        $cart->setdelivery_type(cart::Delivery);
    }
}
$cart_total = $cart->grand_total();
if (!is_numeric($cart_total) || $cart_total <= 0) {
    redirect($SiteUrl . $objRestaurant->url . "/");
    header("location: " . $SiteUrl . $objRestaurant->url . "/");
    exit;
}
if ($cart->delivery_type == cart::None) {
    redirect($SiteUrl . $objRestaurant->url . "/?item=cart");
    header("location: " . $SiteUrl . $objRestaurant->url . "/?item=cart");
    exit;
}

isset($_POST['step']) ? $step = $_POST['step'] : $step = 1;

if (isset($_POST['btnchoose'])) {
    $loggedinuser->set_delivery_address($_POST['address_option']);
} else if (isset($_POST['btntempcustomer'])) {
    extract($_POST);

    $loggedinuser->cust_your_name = trim($customer_name);
    $loggedinuser->LastName = trim($customer_last_name);
    $loggedinuser->cust_phone1 = trim($customer_phone);
    $loggedinuser->cust_email = trim($customer_email);


	$loggedinuser->street1 = trim($customer_address);
	$loggedinuser->street2 = '';
	$loggedinuser->cust_ord_city = trim($customer_city);
	$loggedinuser->cust_ord_state = trim($customer_state);
	$loggedinuser->cust_ord_zip = trim($customer_zip);
	$loggedinuser->delivery_address = $loggedinuser->street1 . ", " . $loggedinuser->cust_ord_city . ", " . $loggedinuser->cust_ord_state;

    $loggedinuser->savetosession();
}
?>

<section class="menu_list_wrapper">
    <form action="" method="post" id="checkoutform"  >
        <?php
        if ($cart->delivery_type == cart::Delivery && is_numeric($loggedinuser->id) && $step == 1) {
            include "chooseaddress.php";
        } else if (!is_numeric($loggedinuser->id) && $step == 1) {
            include "resgister_customer.php";
        } else {
            include "checkout_cart.php";
        } ///ELSE
        ?>
    </form>
    <br/>
    <br/>
    <div style="height:5px;">&nbsp;</div>
</section>
