<?php
    $cart->destroysession();
    redirect($SiteUrl.$objRestaurant->url."/?kiosk=1");
?>