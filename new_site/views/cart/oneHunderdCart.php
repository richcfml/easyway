<?php
$mPost="";
if (isset($_GET['ajax'])) 
{
    extract($_GET);
    if (isset($index)) 
    {
        $cart->remove_Item($index);
    } 
    else if (isset($delivery_type)) 
    {
        $cart->setdelivery_type($delivery_type);
    } 
    else if (isset($favoritesindex)) 
    {
        if (isset($loggedinuser->arrFavorites[$favoritesindex])) 
        {
            $favoritefood = $loggedinuser->arrFavorites[$favoritesindex]->food;
            $cart->addfavorites($favoritefood);
        }
    } 
    else if (isset($removefavoritesindex)) 
    {
        if (isset($loggedinuser->arrFavorites[$removefavoritesindex])) 
        {
            $loggedinuser->removeUserFavoriteOrder($removefavoritesindex);
        }
    } 
    else if (isset($rapidreorder)) 
    {
        $loggedinuser->changeRepidReorderingStatus($rapidreorder, ($status == 1 ? 0 : 1));
    }
    else if (isset($findex)) 
    {
        $mFavoriteID=$loggedinuser->arrFavorites[$findex]->id;
        $mTip= $tip;
        $mDM =$DM;
        $loggedinuser->updateFavoriteTipAmountDeliveryMethod($mFavoriteID, $mTip, $mDM); 
    }
    else if (isset($addtocart)) 
    {
        extract ($_POST);
        $mObjProduct = new product();
        $product=$mObjProduct->getDetailsByProductId($_GET["ProductID"]);
        $attribute_index=1;
        $product_to_order=new product();
        if($quantity==''|| $quantity<=0) $quantity=1;
        $product_to_order->prd_id=$product->prd_id;
        $product_to_order->category_id=$product->sub_cat_id;

        $product_to_order->item_code=$product->item_code;
        $product_to_order->cat_name=stripslashes($product->cat_name);
        $product_to_order->quantity=$quantity;
        $product_to_order->item_title=stripslashes($product->item_title);
        $product_to_order->retail_price=$product->retail_price;
        $product_to_order->sale_price=$product->retail_price;
        $product_to_order->item_for=$item_for;
        $product_to_order->requestnote=$requestnote;

        $product_to_order->associations=array();
        $product_to_order->attributes=array();
        $product_to_order->distinct_attributes=array();

        while($attribute_index<=$totalattributes)
        {
            $aLimit = 0;
            $aLimitPrice = 0;
            $attribute_name='attr'.$attribute_index;
            $attribute_parent_name="attrname" .$attribute_index;
            if(is_numeric($$attribute_name) || is_array($$attribute_name))
            {
                if(is_array($$attribute_name))
                {
                    $inner_index=0;
                    $arr=$$attribute_name;
                    while ($inner_index<count($arr))
                    {
                        $ob=$product->distinct_attributes[$$attribute_parent_name];
                        if($ob->id!=$arr[$inner_index])
                        {
                            $ob=$product->distinct_attributes[$$attribute_parent_name]->attributes[$arr[$inner_index]];
                        }
                        $attribute=new attribute();
                        $attribute->id = $ob->id;
                        $attribute->Title = $ob->Title;
                        $attribute->Price = $ob->Price;
                        $attribute->Price =  currencyToNumber_WPM($attribute->Price); //preg_replace("/[^0-9+-.]+/","",$attribute->Price);
                        $attribute->ExtraPriceCharge = currencyToNumber_WPM($ob->extra_charge); //preg_replace("/[^0-9+-.]+/","",$ob->extra_charge);
                        if($attribute->Price=='')
                        {
                            $attribute->Price=0;
                        }
                        $attribute->Option_name=$$attribute_parent_name;
                        $product_to_order->attributes[$ob->id]=$attribute;
                        $product_to_order->sale_price=$product_to_order->sale_price+$attribute->Price;
                        $aLimit++;
                        if (trim($ob->attr_name)!="");
                        {
                            $mTmp = explode('~',$ob->attr_name);
                            $mLimit = $mTmp[2];
                        }

                        if($aLimit > $mLimit)
                        {
                            $product_to_order->sale_price = $product_to_order->sale_price + $attribute->ExtraPriceCharge;
                        }

                        $inner_index+=1;
                     }
                }
                else
                {
                    $ob=$product->distinct_attributes[$$attribute_parent_name];
                    if($ob->id!=$$attribute_name && is_array($product->distinct_attributes[$$attribute_parent_name]->attributes))
                    {
                        $ob=$product->distinct_attributes[$$attribute_parent_name]->attributes[$$attribute_name];
                    }

                    $attribute=new attribute();
                    $attribute->id = $ob->id;
                    $attribute->Title = $ob->Title;
                    $attribute->Price = $ob->Price;
                    $attribute->Price =  currencyToNumber_WPM($attribute->Price); //preg_replace("/[^0-9+-.]+/","",$attribute->Price);
                    $attribute->Option_name=$$attribute_parent_name;
                    $attribute->ExtraPriceCharge = currencyToNumber_WPM($ob->extra_charge); //preg_replace("/[^0-9+-.]+/","",$ob->extra_charge);

                    if($attribute->Price=='')
                    {
                        $attribute->Price=0;
                    }

                    $product_to_order->attributes[$ob->id]=$attribute;
                    $product_to_order->sale_price=$product_to_order->sale_price+$attribute->Price;
                    $aLimit++;
                    if (trim($ob->attr_name)!="");
                    {
                        $mTmp = explode('~',$ob->attr_name);
                        $mLimit = $mTmp[2];
                    }
                    if($aLimit > $mLimit)
                    {
                        $product_to_order->sale_price = $product_to_order->sale_price + $attribute->ExtraPriceCharge;
                    }
                }
            }
            $attribute_index=$attribute_index+1;
        }

        $association_index=1;

        while($association_index<=count($associations))
        {
            $association=new product();
            $product_assoc=$product->associations[$associations[$association_index-1]-1];

            $association->prd_id=$product_assoc->prd_id;
            $association->item_title=$product_assoc->item_title;
            $association->item_des=$product_assoc->item_des;
            $association->retail_price=$product_assoc->retail_price;
            $product_to_order->associations[$association->prd_id]=$association;
            $association_index+=1;
            $product_to_order->sale_price=$product_to_order->sale_price+$association->retail_price;
        }
        $cart->addProduct($product_to_order,$cartItemIndex);
    }
}
?>
    <div id="your_summery">Your Order Summary</div>
    <div id="contents">
    
<?php
$index = -1;
$arr_tmp = array();
$myurl = $_SERVER['REQUEST_URI'];
$url = explode('/', $myurl);
$is_rest_bh_new_promo = product::isRestPartOfNewBHPromo($url[1]);
//var_dump($is_rest_bh_new_promo);
if(isset($is_rest_bh_new_promo) && $is_rest_bh_new_promo->bh_new_promotion == 1){
$lrgr_ordr_id = array();
foreach ($cart->products as $prod) 
{
    $bh_item_array = product::productIsBH($prod->prd_id);
    //echo '<pre>' . print_r($bh_item_array) . '</pre>';
    $my_quantity =  number_format($prod->sale_price * $prod->quantity,2);
    array_push($lrgr_ordr_id, $bh_item_array->prd_id, $my_quantity);
    //echo $bh_item_array->prd_id . ' - ' . $my_quantity =  number_format($prod->sale_price * $prod->quantity,2);
    //echo '<br/>';
    $bh_flag =  (strstr( $bh_item_array->item_type, "B" ) ? 1 : 0 );     
    if($bh_flag){
        //$arr_tmp[$prod->prd_id] = $bh_item_array->retail_price;
        $arr_tmp[$prod->prd_id] = $prod->sale_price * $prod->quantity;
    }
}
//echo '<pre>' . print_r($arr_tmp) . '</pre>';
$maxprice = max($arr_tmp);
$product_key = array_search($maxprice, $arr_tmp);
//print_r($product_key);
}
//echo '<pre>' . print_r($cart) . '</pre>';
//echo "<pre>" . print_r($lrgr_ordr_id) . '</pre>';
$pid_counts = array_count_values($lrgr_ordr_id);
//echo $count = $pid_counts[17675];
/*$keys = array_keys($lrgr_ordr_id, '17675');
print_r($keys);
$n_arr = array();
foreach($keys as $v){
//    echo $v;
    $k = $v + 1;
    array_push($n_arr, $lrgr_ordr_id[$k]);
}
$mx = max($n_arr);
print_r($mx);*/
foreach ($cart->products as $prod)
{
    $index +=1;
    $checkAtt = product::checkAttrAndAssoc($prod->prd_id);
?>  
        <div class="flip">
            <div id="edit_sign"><a href="#" onclick="event.preventDefault();showPopup(<?= $prod->prd_id ?>, <?= $checkAtt->HasAssociates ?> , <?= $checkAtt->HasAttributes ?>,<?= $index ?>);" style="color:#8c1515;"><img border="0" src="../images/gray_edit.gif" height="14px"></a></div>
            <div id="counting"><?= stripslashes(stripcslashes($prod->item_title)) ?></div>
            <div id="minus_sign" class="remove_cart"><a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=cart&amp;index=<?= $index ?>"><img src="../images/minus_sign.gif" width="14" height="14" border="0"></a></div>
            <div id="dollor"><?#=$currency?>
                   <? if(isset($is_rest_bh_new_promo) && $is_rest_bh_new_promo->bh_new_promotion == 1){
                    if($prod->prd_id == $product_key){
                            if($prod->quantity > 1){
                                //$test_pricing = ($prod->sale_price * ($prod->quantity - 1)) + (($prod->sale_price * ($prod->quantity - ($prod->quantity - 1)))-($prod->sale_price * ($prod->quantity - ($prod->quantity - 1))));    
                                //above is used to discoung 50% and commented out since promo changed to be 100%, below
                                $test_pricing = ($prod->sale_price * ($prod->quantity - 1));
                                
                                $my_new_price = $test_pricing;
                                echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                            echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                            }else{
                                $count = $pid_counts[$prod->prd_id];
                                $keys = array_keys($lrgr_ordr_id, $prod->prd_id);
                                if($count > 1){
                                    $n_arr = array();
                                    foreach($keys as $v){
                                                $k = $v + 1;
                                            array_push($n_arr, $lrgr_ordr_id[$k]);
                                    }
                                    $mx = max($n_arr);
                                    if(number_format($prod->sale_price * $prod->quantity,2) == $mx){
                                        //$my_new_price = ($prod->sale_price * $prod->quantity)/2;//COMMENTED OUT FOR 50% DISCOUNT. 100% DISCOUNT BELOW

                                        $my_new_price = ($prod->sale_price * $prod->quantity) - ($prod->sale_price * $prod->quantity);

                                                                            echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                                            echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                    }else{
                                        echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                    }
                                }elseif(isset($test_pricing) && $prod->prd_id == $product_key){
                                    $my_new_price = (($prod->sale_price * $prod->quantity)-($prod->sale_price * $prod->quantity));
                                    echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                }elseif($prod->prd_id == $product_key){
                                    //echo $prod->prd_id . ' - ' . count($lrgr_ordr_id);
                                    //$my_new_price = ($prod->sale_price * $prod->quantity); //COMMENTED OUT FOR 50% DISCOUNT. 100% DISCOUNT BELOW
                                    
                                    $my_new_price = ($prod->sale_price * $prod->quantity) - ($prod->sale_price * $prod->quantity);
                                    
                                    echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($prod->sale_price * $prod->quantity,2);
                                                                echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                                }else{
                                    echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                                }
                            }
                                                $cart->coupon_code += "BHNewPromo";
                    }else{
                        echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                    }
                   }else{
                    echo $currency . number_format($prod->sale_price * $prod->quantity,2);
                   }
                   
                ?>
            </div>
            <div style="clear:left"></div>
        </div>
<?php 
}
?>
        <!--End flip Div-->
        <div class="subtotal">Subtotal:</div>
        <div class="amount"><?=$currency?><?= number_format($cart->sub_total,2) ?></div>
        <div style="clear:left"></div>
<?php
if ($cart->delivery_type == cart::Delivery) 
{
?>
        <div class="subtotal">Delivery Charges:</div>
        <div class="amount"><?=$currency?><?= number_format($cart->delivery_charges(),2) ?></div>
        <div style="clear:left"></div>
<?php 
} 
if ($cart->driver_tip > 0) 
{
?>
        <div class="subtotal">Driver Tip:</div>
        <div class="amount"><?=$currency?><?= number_format($cart->driver_tip,2) ?></div>
        <div style="clear:left"></div>
<?php 
}
if ($cart->coupon_discount > 0) 
{
?>
        <div class="subtotal">Coupon Discount:</div>
        <div class="amount"><?=$currency?><?= number_format($cart->coupon_discount,2) ?></div>
        <div style="clear:left"></div>
<?php 
} 
if ($cart->vip_discount > 0) 
{ 
?>
        <div class="subtotal">VIP Discount:</div>
        <div class="amount"><?=$currency?><?= number_format($cart->vip_discount, 2) ?></div>
        <div style="clear:left"></div>
<?php 
} 
?>

        <div class="subtotal">Sales Tax:</div>
        <div class="amount"><?=$currency?><?= number_format($cart->sales_tax(),2) ?></div>
        <div style="clear:left"></div>
        <div class="subtotal">Total:</div>
        <div class="amount"><?=$currency?><?= number_format($cart->grand_total(),2) ?></div>
        <div style="clear:left"></div>
        <div class="vertical_line">&nbsp;</div>
<?php
if ($objRestaurant->isOpenHour == 1) 
{
    if (isset($without_loggin)) 
    {
?>             
        <form method="post" name="form1" id="form1" action="?item=checkout">
            <div class="online_ordering" style="font-size: 12px">
                <input type="submit" name="with_out_login" value="Continue without login" class="pickup_button" style="width: 60%">
            </div>
        </form>
<?php
    } 
    else 
    {
            if (is_numeric($loggedinuser->id)) 
            {
                $mPost = $SiteUrl.$objRestaurant->url."/?item=checkout";
            }
            else
            {
                $mPost = $SiteUrl.$objRestaurant->url."/?item=login";
            }    
?>
        <form method="post" name="form1" id="form1" action="<?=$mPost?>">
            <div class="online_ordering">
<?php 
        if ($objRestaurant->delivery_offer == 1) 
        { 
?>
                <input type="submit" name="btncheckout" value="Delivery" class="delivery_button" onclick="return totalVerified();"> 
<?php 
        } 
?>
                <input type="submit" name="btncheckout" value="Pickup" class="pickup_button"> 
            </div>
        </form>
        <!--End contents Div--> 
<?php 
    }
}
?>
    </div>
    <br/>
<?php
    require($site_root_path . "views/customer/favorites.php"); 
?>
<script type="text/javascript">
function totalVerified() 
{
    var cartSubTotal =<?= $cart->sub_total ?>;
    var restMinTotal =<?= $objRestaurant->order_minimum ?>;
    if (cartSubTotal < restMinTotal)
    {
        alert("<?=$java_currency?>" + restMinTotal + " of food required to checkout. Please add more items");
        return false;
    }
    return true;
}

$(function() 
{
    $(".remove_cart a").click(function(e) 
    {
        e.preventDefault();
        postitem($(this));

    });

    $(".addfavoritesorder ,.removefavoritesorder,.rapidreorder").click(function(e) 
    {
        e.preventDefault();

        postitem($(this));
        if ($(this).hasClass("addfavoritesorder")) 
        {
            alert("Favorite order added to cart");
        } 
        else if ($(this).hasClass("rapidreorder")) 
        {
            alert("Rapid Reordering status changed");
        } 
        else 
        {
            alert("Favorite order removed from list");
        }
    });
});

$(".online_ordering a").click(function(e) 
{
    e.preventDefault();
    var action = $(this).attr('href');

    var orderType =<?= $cart->delivery_type ?>;
    if (orderType == 0) 
    {
        alert('Please select Pickup or Delivery');
    } 
    else if (orderType ==<?= cart::Delivery ?> && !totalVerified()) 
    {
        return false;
    } 
    else 
    {
        window.location = action;
    }

});

function postitem(source) 
{
    var url = $(source).attr('href') + '&ajax=1';
    $.ajax({
        url: url,
        type: "POST",
        success: function(data) 
        {
            $('#cart').html(data);
        }
    });
}
<?php 
if (isset($_GET['ajax'])) 
{
?>
    jQuery(document).ready(function($) 
    {
        $('a[rel*=facebox2]').unbind('click.facebox');
        $('a[rel*=facebox2]').facebox();
    });
<?php 
} 
?>
</script>

