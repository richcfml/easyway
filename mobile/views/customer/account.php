<?php
if (!is_numeric($loggedinuser->id)) {
    redirect($SiteUrl . $objRestaurant->url . "/?item=login");
    exit;
}
if (isset($_POST['btnsave'])) 
{
    extract($_POST);
    
    $mSalt = $loggedinuser->salt;    
    $ePassword = hash('sha256', $password.$mSalt);
    
    $loggedinuser->cust_email = trim($email) == '' ? $loggedinuser->cust_email : $email;
    $loggedinuser->epassword = trim($password) == '' ? $loggedinuser->epassword : $ePassword;
    $loggedinuser->cust_your_name = trim($first_name) == '' ? $loggedinuser->cust_your_name : $first_name;
    $loggedinuser->LastName = trim($last_name) == '' ? $loggedinuser->LastName : $last_name;
    $loggedinuser->street1 = trim($address1) == '' ? $loggedinuser->street1 : $address1;
    $loggedinuser->street2 = trim($address2) == '' ? $loggedinuser->street2 : $address2;
    $loggedinuser->cust_ord_city = trim($city) == '' ? $loggedinuser->cust_ord_city : $city;
    $loggedinuser->cust_ord_state = trim($state) == '' ? $loggedinuser->cust_ord_state : $state;
    $loggedinuser->cust_ord_zip = trim($zip) == '' ? $loggedinuser->cust_ord_zip : $zip;
    $loggedinuser->cust_phone1 = trim($phone1) == '' ? $loggedinuser->cust_phone1 : $phone1;
    $loggedinuser->delivery_street1 = trim($saddress1) == '' ? $loggedinuser->delivery_street1 : $saddress1;
    $loggedinuser->delivery_street2 = trim($saddress2) == '' ? $loggedinuser->delivery_street2 : $saddress2;
    $loggedinuser->delivery_city1 = trim($scity) == '' ? $loggedinuser->delivery_city1 : $scity;
    $loggedinuser->delivery_state1 = trim($cstate) == '' ? $loggedinuser->delivery_state1 : $cstate;
    $loggedinuser->deivery1_zip = trim($czip) == '' ? $loggedinuser->deivery1_zip : $czip;
    $loggedinuser->update();
}
?>

<section class='menu_list_wrapper'>
    <h1>My account &nbsp;&nbsp;&nbsp; <a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=editaccount">Edit</a>&nbsp;| &nbsp;<a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=checkout">Check out</a> | &nbsp;<a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=logout">Logout</a></h1>
    <p class="margintopsmall">Email:&nbsp;&nbsp;<span>
<?= $loggedinuser->cust_email ?>
        </span> </p>
    <h3>Main Contact Information [ <a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=editaccount">Edit</a> ]</h3>
    <p class="margintopsmall">Name:&nbsp;&nbsp;<span>
<?= $loggedinuser->cust_your_name . " " . $loggedinuser->LastName ?>
        </span> </p>
    <p class="margintopsmall">Main Ordering Address:&nbsp;&nbsp;<span>
<?= str_replace('~', ' ', $loggedinuser->cust_odr_address) . " " . $loggedinuser->cust_ord_city . " " . $loggedinuser->cust_ord_state ?>
        </span> </p>

    <p class="margintopsmall">Phone Number:&nbsp;&nbsp;<span>
<?= $loggedinuser->cust_phone1 ?>
        </span> </p>

    <p class="margintopsmall">Alt Phone Number:&nbsp;&nbsp;<span>
<?= $loggedinuser->cust_phone2 ?>
        </span> </p>
    <h3>Alternate Delivery Address</h3>

    <p class="margintopsmall"> <span>
<?= str_replace('~', ' ', $loggedinuser->delivery_address1); ?> <br/><?= $loggedinuser->delivery_city1 . " " . $loggedinuser->delivery_state1 ?> 
        </span> </p>
    <div style="height:10px;">&nbsp;</div>
</section>
