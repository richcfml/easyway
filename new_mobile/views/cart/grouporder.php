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
    }
}
?>

<?php
$grp_useremail = "";
$grp_userid = 0;
$grpid = 0;
$mFoodCount = 0;
if(isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"]))
{
?>
<script type="text/javascript" language="javascript">
    var mTimer; 
    startTimer();
    
    function startTimer()
    {
        mTimer = setTimeout(function() 
        {
            var grpMessage=$('#GroupMessage').val();
			
            $(".cart").load("<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=grouporder&grp_userid=<?=$_GET["grp_userid"]?>&grpid=<?=$_GET["grpid"]?>&uid=<?=$_GET["uid"]?>&grp_keyvalue=<?=$_GET["grp_keyvalue"]?>&ajax=1", function() {
            });
        }, 7000);
    }
</script>
<?php
    unset($cart);
    $cart=new cart();
    $mSQL = "SELECT grp_name, grp_username, grporder_id, grp_usertype, grp_useremail, ReceivingMethod FROM grouporder WHERE CustomerID=".$_GET["grp_userid"]." AND UserID=".$_GET["uid"]." AND GroupID=".$_GET["grpid"]." AND Status=0 AND FoodOrdered=0";
    $mRes = dbAbstract::Execute($mSQL);
    if (dbAbstract::returnRowsCount($mRes)>0)
    {
	   $gp_username = dbAbstract::returnObject($mRes);
        if(isset($gp_username->grp_username))
        {
            $gp_username->grp_username="Your Order";
            $grp_useremail = $gp_username->grp_useremail;
            $grp_userid = $_GET["grp_userid"];
            $grpid = $_GET["grpid"];
        }
    }
    else
    {
        redirect($SiteUrl.$objRestaurant->url."/");
    }
}

$theme_query="SELECT theme_name FROM  resturants WHERE id=".$objRestaurant->id."";
$theme_obj =  dbAbstract::ExecuteObject($theme_query);
$theme_name="";
if(!empty($theme_obj->theme_name))
{
    $theme_name = "_".$theme_obj->theme_name;
}
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/0.5.0/sweet-alert.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/0.5.0/sweet-alert.min.js"></script>
<script src="../js/mask.js" type="text/javascript"></script>
<script>
    
    var grp_useremail = '<?=$grp_useremail?>';
    var grp_keyvalue = getUrlParameter('grp_keyvalue');
    
    $( document ).ready(function() 
    {
        $('.lnkDelUser').click(function(e) 
        {
            e.preventDefault();
            var id = $(this).attr('id');
            
            var url = "?item=grouporder_ajax&del_user&id="+id+"&ajax=1";
            
            $.post(url, function(data) 
            {
                $(".cart").load("<?=$SiteUrl . $objRestaurant->url?>/?item=grouporder&grp_userid=<?=$_GET["grp_userid"]?>&grpid=<?=$_GET["grpid"]?>&uid=<?=$_GET["uid"]?>&grp_keyvalue=<?=$_GET["grp_keyvalue"]?>&ajax=1", function(){
                                                                    $('.header__cart').html($('.cart-item').length);
                                                                });
            });
        });
               
        if((!grp_useremail) && (!grp_keyvalue)) {
            $(".FrontendShippingCartBigDiv").show();
            $(".GroupOrderBigDiv").hide();
        } else {
			$(".FrontendShippingCartBigDiv").hide();
            $(".GroupOrderBigDiv").show();
        }
		
		$('.CancelGroupOrder').click(function() {
			var grp_userid = <?=$grp_userid?>;
			var grpid = <?=$grpid?>;
			
			swal({
				title: "Are you sure want to cancel group order?",
				showCancelButton: true,
				confirmButtonColor: "#a1adc1",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: false,
				closeOnCancel: true
			},
			function(isConfirm){
				if (isConfirm) 
				{
					var url = "?item=grouporder_ajax&cancel_grouporder&grpid="+grpid+"&grp_userid="+grp_userid+"&ajax=1";
					$.ajax({
						url: url,
						type: "POST",
						data: "",
						success: function(data) 
						{
							window.location.href = "<?= $SiteUrl ?><?= $objRestaurant->url ?>/";
						},
						error: function(a) 
						{ 
							alert(a.responseText);
						}
					}); 
				}
			});
		});
		
		$('.SendText').click(function() {
			clearTimeout(mTimer);
			var myID = $(this).attr("id");
			$("#spnSend").hide();
			$("#imgEmail").show();
			var grp_userid = <?=$grp_userid?>;
			var grpid = <?=$grpid?>;
			
			var url = "?item=grouporder_ajax&groupEmail&grpid="+grpid+"&grp_userid="+grp_userid+"&ajax=1";
			var grpMessage=$('#GroupMessage').val();
			
			$.ajax({
				url: url,
				type: "POST",
				data: "grpOrderMessage="+grpMessage,
				success: function(data) 
				{
					$("#imgEmail").hide();
					$("#spnSend").show();
					$("#"+myID).html('Send');
					$('#GroupMessage').val('');
					
					swal({
						title: "",
						text: "<span style='font-size: 17px; color: #575757; font-weight: 500 !important;'>Message has been sent!</span>",
						confirmButtonColor: "#11b1b3",
						html: true
					});
					startTimer();
				},
				error: function(a) 
				{ 
					$("#imgEmail").hide();
					alert(a.responseText);
					startTimer();
				}
			}); 
		});
        
    });
    
    
    function postitemG(source)
    {
        var url = $(source).attr('href') + '&ajax=1';
        $.post(url, function(data) 
        {
            $("#grouporder").load("<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=grouporder&grp_userid=<?=$_GET["grp_userid"]?>&grpid=<?=$_GET["grpid"]?>&uid=<?=$_GET["uid"]?>&grp_keyvalue=<?=$_GET["grp_keyvalue"]?>&ajax=1");
        });
    }
    
    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++) {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) {
                return sParameterName[1];
            }
        }
    }
</script>

<div class="cart__container GroupOrderBigDiv">
    
    <hgroup class=cart__header>
        <h2 class=cart__title>Group Ordering</h2>
        <?php if (!$cart->isempty()) {
            ?> <h3 class= cart__total2> <span>Total:</span> <?= $currency . number_format($cart->grand_total(),2) ?> </h3>
        <?php } ?>
    </hgroup>
	
    <?php
	  if(isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"])){
        $sql_gpuser=dbAbstract::Execute("SELECT FoodOrdered, grporder_id, grp_username, grp_useremail, SerializedCart FROM grouporder WHERE CustomerID=".$_GET["grp_userid"]." AND GroupID=".$_GET["grpid"]);
        
		$grp_usercount = dbAbstract::returnRowsCount($sql_gpuser);
		$cartheight = intval(100/$grp_usercount);
		for($k = 0; $k < $grp_usercount; $k++){
			$grp_userinfo = dbAbstract::returnObject($sql_gpuser);
            $mFetchCart = false;
            $cartG = new cart();
            if (!$mFetchCart && ($grp_userinfo->grp_useremail == $grp_useremail))
            {
				$mFetchCart = true;
                if (trim($grp_userinfo->SerializedCart)!="")
                {
                    $_SESSION["CART"] = $grp_userinfo->SerializedCart;
                    $cart = unserialize($_SESSION["CART"]);
                }
            }
            else if (($grp_userinfo->grp_useremail != $grp_useremail) && ($grp_userinfo->FoodOrdered==1))
            {
				$cartG = unserialize($grp_userinfo->SerializedCart);
			}
			
			if ((trim($grp_userinfo->SerializedCart)!="") && ($grp_userinfo->grp_useremail == $grp_useremail || $grp_userinfo->FoodOrdered==1))
            {
                $mFoodCount+=1;
        ?>
        	
            <div class="GroupOrderName NameInGreen" style="width: 100%; text-decoration: none !important; margin-top:15px">
        <?php 
                if($grp_userinfo->grp_useremail == $grp_useremail)
                {
                    echo("<span style='text-decoration: underline !important; margin-left:15px;'>".$gp_username->grp_username."</span>");
                } 
                else 
                {
                    if ($gp_username->grp_usertype == 'leader')
                    {
                        echo("<span style='text-decoration: underline !important; margin-left:15px;'>".$grp_userinfo->grp_username."</span><span style='text-decoration: none !important;'>&nbsp;&nbsp;</span>");
						//echo "<a class='lnkDelUser' id='".$grp_userinfo->grporder_id."' style='text-decoration: underline !important; color:#FF0000;' href='#'>Delete</a>";
                    }
                    else
                    {
                        echo("<span style='text-decoration: underline !important; margin-left:15px;'>".$grp_userinfo->grp_username."</span>");
                    }
                }
        ?>
        	
            </div>
            
        <?php 
                if($grp_userinfo->grp_useremail == $grp_useremail)
                {
                    $itemTotalPrice = 0;
                    foreach ($cart->products as $prod)    
                    {
                        $itemTotalPrice = $itemTotalPrice + ($prod->sale_price * $prod->quantity);
                    }
        ?>
                    <!--<div id="YouTickAndGreen">
                        <div class="SpanTickPrice" id="YouSpanTickPrice<?=$grp_userinfo->grporder_id?>" style="float: right; margin-top: -18px;"><?=$currency?><?=$itemTotalPrice;?></div>
                        <span class="SpanTick" style="float: right;margin-top: -17px;color: #3DB325;font-size: 18px;margin-right: 10px;">
                        <i class="fa fa-check"></i>
                        </span>
                    </div>-->
                    <ul style="display:block; list-style-type:none; margin:0; padding:0px 0px 30px 20px; height:auto; max-height:<?=$cartheight?>%; overflow-y:scroll;" class='cart-item__list2 '>
                    <?php foreach ($cart->products as $index => $cproduct) { ?>
                        <li class='cart-item  index_<?= $index ?> '>
                              <input style="width: 46px" class='gqnty cart-item__quantity' maxlength="3" id="itemQ_<?= $cproduct->prd_id ?>" value=<?= $cproduct->quantity ?>>
                              <div style="max-width: 39%;padding-left: 5px; padding-right: 0px" class=cart-item__info>
                                  <h4 class=cart-item__name><?= $cproduct->item_title ?></h4>
                                  <span class='cart-item__price' price='<?= number_format($cproduct->sale_price * $cproduct->quantity,2) ?>'><?= $currency . number_format($cproduct->sale_price * $cproduct->quantity,2) ?></span> 
                              </div>
                              
                              <i onclick="event.preventDefault();  showPopup(<?=$cproduct->prd_id.',1,1,'.$index?>, this)" class="cart-item__edit" id="<?= $cproduct->prd_id ?>" index="<?= $index ?>" style="cursor:pointer"></i>
                			  
                              <p class="cart-item__description"><?= $cproduct->requestnote; ?></p>
                              
                              <div id="minus_sign" class="remove_gcart">
                                <a class="cart-item__delete js-delete-item" href="<?=$SiteUrl . $objRestaurant->url . '/?item=grouporder&amp;index=' . $index?>&grp_userid=<?=$_GET["grp_userid"]?>&grpid=<?=$_GET["grpid"]?>&uid=<?=$_GET["uid"]?>&grp_keyvalue=group_order">
                                  Delete Item
                                </a>
                              </div>
                        </li>
                        <? } ?>
                      </ul>
        <?php 
                } 
                else 
                {
                    $itemTotalPrice = 0;
                    foreach ($cartG->products as $prod)    
                    {
                        $itemTotalPrice = $itemTotalPrice + ($prod->sale_price * $prod->quantity);
                    }
        ?>
                    <!--<div id="OtherTickAndGreen">
                      <div class="SpanTickPrice" id="OtherSpanTickPrice" style="float: right; margin-top: -18px;">
					  	<?=$currency.number_format($itemTotalPrice,2)?>
                      </div>
                      <span class="SpanTick" style="float: right;margin-top: -17px;color: #3DB325;font-size: 18px;margin-right: 10px;">
                        <i class="fa fa-check"></i>
                        </span>
                    </div>-->
                    
                    <ul style="display:block; list-style-type:none; margin:0; padding:0px 0px 30px 20px; height:auto; max-height:<?=$cartheight?>%; overflow-y:scroll;" class='cart-item__list2'>
						<?php 
						$itemTotalPrice = 0;
						$index = -1;
						foreach ($cartG->products as $prod){ 
						?>
                        <li class='cart-item'>
                              <input style="width: 46px"  class='cart-item__quantity' maxlength="3" id="itemQ_<?= $cproduct->prd_id ?>" value=<?=$prod->quantity?> readonly>
                              <div style="max-width: 39%;padding-left: 5px; padding-right: 0px" class=cart-item__info>
                                  <h4 class=cart-item__name><?= $prod->item_title ?></h4>
                                  <span class='cart-item__price' price='<?= number_format(($prod->sale_price * $prod->quantity), 2) ?>'>
								  <?= $currency . number_format(($prod->sale_price * $prod->quantity), 2) ?>
                                  </span> 
                              </div>
                        </li>
                        <? } ?>
                      </ul>
        <?php 
                } 
        ?>
        <?php 
            }
			else 
            { 
    ?>
        <div class="GroupOrderName NameInGreen" style="width: 75%; padding-top: 10px; color: #9e0b0f; text-decoration: none !important;">
    <?php 
            if($grp_userinfo->grp_useremail == $grp_useremail)
            { 
                echo("<span style='text-decoration: underline !important; color:#FFF; margin-left:15px'>".$gp_username->grp_username."</span>"); 
            } 
            else 
            { 
                if ($gp_username->grp_usertype == 'leader')
                {
                    echo("<span style='text-decoration: underline !important; color:#FFF; margin-left:15px'>".$grp_userinfo->grp_username."</span><span style='text-decoration: none !important;'>&nbsp;&nbsp;</span>");
					//echo "<a class='lnkDelUser' id='".$grp_userinfo->grporder_id."' style='text-decoration: underline !important; color:#FF0000;' href='#'>Delete</a>";
                }
                else
                {
                    echo("<span style='text-decoration: underline !important; color:#FFF; margin-left:15px'>".$grp_userinfo->grp_username."</span>");
                }
            }
        ?>
        </div>
        <!--<div class="GroupOrderFoodStuff NeedFood" style=" color:#FFF; margin-left:20px">Still needs food</div>-->
		  <p class=cart-empty__info style="margin-left:40px">You haven't add <br>anything to your cart yet.</p>

        <div class="CartItemRow"></div>
        <?php 
			}
            
		}
		
		/*if($gp_username->grp_usertype == 'leader') 
		{
		?>
		<div id="CancelGroupOrder"><a class="CancelGroupOrder" style="cursor: pointer; cursor: hand;">Cancel Group Order</a></div>
		<?php
		}*/
		?>
        <!--<div class="groupmsg_wrap">
          <div id="MessageGroupOrder">Message Group Members</div>
          <div id="MessageTextArea"><textarea id="GroupMessage" class="MessageTextArea"></textarea></div>
          <div id="SendText" class="SendText" style="cursor: pointer;">
            <img src="../images/ajax.gif" alt="Processing..." id="imgEmail" style="display: none;" />
            <span id="spnSend">Send</span>
          </div>
        </div>-->
        <div class="clear"></div>
		<?php	
		if($gp_username->grp_usertype == 'leader'){
			if ($mFoodCount > 0){
				$grouporderChkout = '&grp_userid='.$_GET["grp_userid"].'&grpid='.$_GET["grpid"].'&uid='.$_GET["uid"].'&grp_keyvalue='.$_GET["grp_keyvalue"];
			}
			else{
				$placeOderDisplay = 'none';
			}
		}
		else{
        ?>

            <input type=button value=Done class="btnRespond full-width js-respond-group-order" 
            onClick="window.location.href='?item=grouporderdone&grp_userid=<?=$_GET["grp_userid"]?>&grpid=<?=$_GET["grpid"]?>&uid=<?=$_GET["uid"]?>&grp_keyvalue=<?=$_GET["grp_keyvalue"]?>'" />

        <?php 
        }
	  }
	?>
    
    <?php /*if ($cart->isempty()) { ?>
        <div class='cart-empty'>
            <div class=cart-empty__center> <i class=cart-empty__bag></i>
                <p class=cart-empty__info> 
                    You haven't add <br>anything to your cart yet. 
                </p>
                <a class=cart-empty__favorites href="<?= $SiteUrl . $objRestaurant->url ?>/?item=favorites">Order from your favorites</a> 
            </div>
        </div>
    <?php }*/ ?>
</div>

<?php
if(isset($gp_username) && $gp_username->grp_usertype == 'leader') 
{ 
?>
<footer class="cart__footer GroupOrderBigDiv" style="display:<?=$placeOderDisplay?>"> <span class=cart__place-order>Place order</span> </footer>
<?php
}
?>

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
		
		$('.gqnty').on('focusout', function (e) {
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

		$.post(
			'?item=addtip<?=$updateCartUrl?>&ajax=1', 
			{quantity: qty, itemIndex: index, action: "increasequantity"}, 
			function (data) {
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
		$(".remove_gcart a").click(function (e)
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
					$('.cart').html(data)
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

	$(".cart-item__edit").click(function () {
		var productId = $(this).attr("id");
		var quantity = $("#itemQ_" + productId).val();
		var index = $(this).attr("index");

		if (quantity < 0 || quantity == '') {
			quantity = 1;
		}

		$.post(
			'?item=cartajax<?=$updateCartUrl?>',
			{productId: productId, quantity: quantity, action: 'updateProduct', editIndex: index},
			function (data) {});

	});
});
</script>
