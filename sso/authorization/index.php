<?php

session_start();
include "../config.php";

if (isset($_SESSION['admin_session_user_name'])) {
    $sso_token = $_REQUEST['sso_token'];
    $restaurant_query = mysql_query("SELECT id,chargify_subscription_id from resturants where chargify_subscription_id = '" . $sso_token . "'");
    $restaurant_info = mysql_fetch_array($restaurant_query);
    if ($restaurant_info['id'] == $_SESSION['ResturantId']) {
        $sso_ticket = "54321";
        $next = urldecode($_REQUEST['next']);
        $next_url = $next.'&sso_token='.$sso_token.'&sso_ticket='.$sso_ticket;
        mysql_query("INSERT INTO vendasta_validation set sso_token = '" . $sso_token . "'
                                                ,sso_ticket = '" . $sso_ticket . "'
                                                ,next_url = '" . $next_url . "'
                                                ,restaurant_id = '" . $restaurant_info['id'] . "'
                   ");
        header("Location: $next_url");
    } else {
        return 500;
    }
    
} else {
    return 500;
}
?>