<!--	Cart -->
<?php
if (isset($_GET['ajax'])) {
    extract($_GET);
    if (isset($index)) {
        $cart->remove_Item($index);

        if (isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"])) {
            if ($cart->isempty()) {
                $mSQL = "UPDATE grouporder SET SerializedCart='' WHERE CustomerID=" . $_GET["grp_userid"] . " AND GroupID=" . $_GET["grpid"] . " AND UserID=" . $_GET["uid"];
            } else {
                $mSQL = "UPDATE grouporder SET SerializedCart='" . prepareStringForMySQL($_SESSION['CART']) . "' WHERE CustomerID=" . $_GET["grp_userid"] . " AND GroupID=" . $_GET["grpid"] . " AND UserID=" . $_GET["uid"];
            }
            dbAbstract::Update($mSQL);
        }
    }else if (isset($removefavoritesindex)) 
	{
        if (isset($loggedinuser->arrFavorites[$removefavoritesindex])) 
		{
            $loggedinuser->removeUserFavoriteOrder($removefavoritesindex);
            echo $currency . $cart->grand_total();exit; 
        }
    }
}
?>

<div class="cart__container FrontendShippingCartBigDiv" >
    <hgroup class=cart__header>
        <h2 class=cart__title><br>Your Order</h2>
        <?php if (!$cart->isempty()) {
            ?> <h3  class= cart__total2> <span>Total:</span> <?= $currency . number_format($cart->grand_total(),2) ?> </h3>
        <?php } ?>
    </hgroup>
    <?php if ($cart->isempty()) { ?>
        <div class='cart-empty' style="height:89%">
            <div class=cart-empty__center> <i class=cart-empty__bag></i>
                <p class=cart-empty__info> 
                    You haven't added <br>anything to your cart yet. 
                </p>
                <?php if (isset($loggedinuser->id)) {?>
                <a class=cart-empty__favorites href="<?= $SiteUrl . $objRestaurant->url ?>/?item=favorites">Order from your favorites</a> 
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <ul style="display:block; list-style-type:none; margin:0; padding:0px 0px 100px 20px; height: 100%; overflow-y: scroll;" class='cart-item__list2 '>
        <?php
		include('../../../classes/Product.php');
		$arr_tmp = array();
		$myurl = $_SERVER['REQUEST_URI'];
		$url = explode('/', $myurl);
		$is_rest_bh_new_promo = product::isRestPartOfNewBHPromo($url[1]);
		if(isset($is_rest_bh_new_promo) && $is_rest_bh_new_promo->bh_new_promotion == 1){
			foreach ($cart->products as $index => $cproduct)
			{
        			$bh_item_array = product::productIsBH($cproduct->prd_id);
        			$bh_flag =  (strstr( $bh_item_array->item_type, "B" ) ? 1 : 0 );
				if($bh_flag){
                			$arr_tmp[$cproduct->prd_id] = $bh_item_array->retail_price;
				}
        		}
		}
		$maxprice = max($arr_tmp);
		$product_key = array_search($maxprice, $arr_tmp);
		
	?>
	<?php  foreach ($cart->products as $index => $cproduct) { ?>
            <li class='cart-item  index_<?= $index ?> '>
                
                <input style="width: 46px" class='qnty cart-item__quantity' maxlength="3" id="itemQ_<?= $cproduct->prd_id ?>" value=<?= $cproduct->quantity ?>>
                <div style="max-width: 39%;padding-left: 5px; padding-right: 0px" class=cart-item__info>
                    <h4 class=cart-item__name><?= $cproduct->item_title ?></h4> 
                    <?php if($cproduct->prd_id == $product_key){
                        $test_pricing = ($cproduct->sale_price * ($cproduct->quantity - 1)) + (($cproduct->sale_price * ($cproduct->quantity - ($cproduct->quantity - 1)))/2);
                        $my_new_price = $test_pricing;
                        echo "(<span style=\"text-decoration: line-through;\">" . $currency . number_format($cproduct->sale_price * $cproduct->quantity,2);
                        echo "</span> " . $currency . number_format($my_new_price,2)  . ")";
                     }else{  ?>

                        <span class='cart-item__price' price='<?= number_format($cproduct->sale_price * $cproduct->quantity,2) ?>'>
                                <?= $currency . number_format($cproduct->sale_price * $cproduct->quantity,2) ?>
                        </span>
                    <?php } ?>
		</div>
                <i onclick="event.preventDefault();  showPopup(<?php echo $cproduct->prd_id; ?>, <?php echo '1'; ?>, <?php echo '1'; ?>, <?php echo $index; ?>, this)" class="cart-item__edit" id="<?= $cproduct->prd_id ?>" index="<?= $index ?>" style="cursor:pointer"></i>
                <p class="cart-item__description"><?= $cproduct->requestnote; ?></p>
               
               <div id="minus_sign" class="remove_cart ">
                   <a class="cart-item__delete js-delete-item" href="<?php echo $SiteUrl . $objRestaurant->url . '/?item=cart&amp;index=' . $index; ?>">
                       Delete Item
                   </a>
               </div>
            </li>
            <? } ?>
        </ul>
    </div>

<footer class="cart__footer FrontendShippingCartBigDiv"> <span class=cart__place-order>Place order</span> </footer>

<?php
$cartTotalDisplay = (!empty($cart)) ? 'show' : 'hide';
$checkoutUrl = ($loggedinuser->id > 0) ? $SiteUrl . $objRestaurant->url . '/?item=checkout'.$grouporderChkout : 'login';

$item = 'cart';
$updateCartUrl='';
if(isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"])){
  $item = 'grouporder';
  $updateCartUrl = '&grp_userid='.$_GET["grp_userid"].'&grpid='.$_GET["grpid"].'&uid='.$_GET["uid"].'&grp_keyvalue='.$_GET['grp_keyvalue'];
}
?>
<script>
    var loginCondition='<?php echo $checkoutUrl?>'
	$(document).ready(function () {
		$(".cart__total2").<?= $cartTotalDisplay ?>();
		
		//change keyup paste
		$('.qnty').on('focusout', function (e) {

                if ($(this).val() <= 0) {
                    $(this).val(1);
                }
                if ($.inArray(e.keyCode, [173, 46, 8, 9, 27, 13, 110, 190, 109, 189]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) 
                {

                    return;
                }
                if ((e.keyCode != 173) && (e.keyCode != 109) && (e.keyCode != 189)) {
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                }
                $(this).attr("disabled", "disabled");
                var qty = $(this).val();
                var index = $(this).parent().find('.cart-item__edit').attr('index')
                var price = $(this).parent().find('.cart-item__price').attr('price')

			$.post('?item=addtip<?=$updateCartUrl?>&ajax=1', {quantity: qty, itemIndex: index, action: "increasequantity"}, function (data) {
                    $(".cart_statsarea").html(data);
				$(".cart").load("<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=<?=$item.$updateCartUrl?>&ajax=1", function(){
                                                                    $('.header__cart').html($('.cart-item').length);
                                                                });
                    $(this).removeAttr("disabled");
				$(this).focus();
                });
                console.log(index)
                $('.index_' + index + ' input').focus()
            });

        });





	$(document).ready(function () {
		$(".remove_cart a").click(function (e)
		{
			e.preventDefault();
			postitem($(this));

		});
		
		function postitem(source)
		{

			var url = $(source).attr('href') + '&ajax=1';
			console.log(url)
			$.ajax({
				url: url,
				type: "POST",
				success: function (data)
				{
					console.log(data)
					$('.cart').html(data);
                                        $('.header__cart').html($('.cart-item').length);
					source.parent().parent().parent().css('display', 'none')
					$('.footer__stepper span').html($('.cart__total2').html())
				}
			});
		}

		$(".cart__total").<?= $cartTotalDisplay ?>();

		$(".cart__place-order").click(function () {
			  if ('<?= $checkoutUrl ?>' != 'login') {	
				return window.location.href = '<?= $checkoutUrl ?>';
			  }
			  else{
					  $.ajax({
						  type:"POST",
						  url: "?item=accountajax&ajax=1&getAuthenticationHtml=1&reponse=<?=$_GET['reponse']?>&hash=login",
						  data:{},
						  success: function(data) {
							  $("#ajax_notification").html(data)
						  }
					  });
				  
			  }
			});

	
});
</script>
