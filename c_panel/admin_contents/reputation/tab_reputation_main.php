

<div id="BodyContainer">
    <? include "includes/resturant_header.php" ?>

    <? //include $admin_subcontent;
    start_session();
     $_SESSION['sso_token']=$Objrestaurant->chargify_subscription_id; echo 'herer';exit;
    ?>
    <div>
    <iframe src="https://www.youtube.com" width="100%"></iframe>
    </div>
    <!--<iframe src="http://easywayordering.steprep.com/overview/?ssoToken=<?=$Objrestaurant->chargify_subscription_id?>" width="100%" height="500px"></iframe>-->
<!--    <iframe src="http://easywayordering.repintel.com/overview/?ssoToken=<?=$Objrestaurant->chargify_subscription_id?>" width="100%" height="500px"></iframe>-->


</div>


