<?

$favoritesindex = $_GET['index'];
if (isset($favoritesindex)) {
;
    if (isset($loggedinuser->arrFavorites[$favoritesindex])) {
        $favoritefood = $loggedinuser->arrFavorites[$favoritesindex]->food;
        $cart->addfavorites($favoritefood);
    }
}
redirect($SiteUrl . $objRestaurant->url . "/?item=cart");
//header("location: " . $SiteUrl . $objRestaurant->url . "/?item=cart");
exit;
?>