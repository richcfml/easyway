<?php
include("../../includes/config.php");
include("../../includes/function.php");
if(isset($_GET['payment_id']) && $_GET['payment_id']!='-1')
{
    $mSQL = dbAbstract::ExecuteObject("select * from chargify_payment_method WHERE Payment_profile_id = ".$_GET['payment_id']."", 1);

    echo json_encode($mSQL);
}
?>
