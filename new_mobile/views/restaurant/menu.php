<?php exit;?><section class="menu_list_wrapper" >
    <?php
    if (isset($_GET['category']))
        $categoryid = $_GET['category'];
    ?>

    <div  id="menu_<?= $categoryid ?>"   style="width:100%;" >
        <ul style="width:100%">
            <?php
            if ($menuid > 0) {
                $products = $product->getProductsByCategoryId($categoryid);
                foreach ($products as $product) {
                    ?>


                    <li>
                        <!-- Edited By Asher Ali-->
                        <a <? if ($iscurrentMenuAvaible == 0) { ?> href="javascript:alert('menu is not available at this time');"<? } else { ?>  href="?item=product&id=<?= $product->prd_id; ?>" style="color:#000;"<? } ?> >
                            <div class="menu_list_txt"><?php echo stripcslashes($product->item_title); ?></div>
                            <div class="price_wrapper">
                                <div class="menu_price"><?=$currency?><?php echo $product->retail_price; ?></div>
                            </div>
                        </a> </li>
                    <!-- -->
                    <?php
                }
            } else {
                foreach ($loggedinuser->arrFavorites as $favoritesindex => $favorite) {
                    ?>
                    <li> <a href="?item=favorites&index=<?= $favoritesindex ?>" style="color:#000;">
                            <div class="menu_list_txt"><?php echo stripcslashes($favorite->title); ?></div>
                            <div class="price_wrapper">
                                <div class="menu_price"><img src="../images/green_plus_sign.gif" width="14" height="14" border="0"></div>
                            </div>
                        </a> </li>
                <? }
            }
            ?>
            <li style="text-align: center;">
                <input type="button" name="btncheckout" value="Check out" class="button blue" onclick="window.location = '?item=cart'">
            </li>
            <li>&nbsp;</li>
        </ul>
    </div>
</section>
