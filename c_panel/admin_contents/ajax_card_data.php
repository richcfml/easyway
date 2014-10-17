<?php
include("../../includes/config.php");
include("../../includes/function.php");
if(isset($_GET['payment_id']) && $_GET['payment_id']!='-1')
{
    $mSQL = mysql_fetch_object(mysql_query("select * from chargify_payment_method WHERE Payment_profile_id = ".$_GET['payment_id'].""));

    echo json_encode($mSQL);
//        print_r($mSQL);
//        $first_name = $mSQL->$first_name;
//        $last_name = $mSQL->$last_name;
//        $billingAddress1 = $mSQL->$billingAddress1;
//        $billingAddress2 = $mSQL->$billingAddress2;
//        $city = $mSQL->$city;
//        $state = $mSQL->$state;
//        $zip = $mSQL->$zip;
//        $country = $mSQL->$country;
//        $exp_month = $mSQL->$exp_month;
//        $exp_year = $mSQL->$exp_year;
}

?>
