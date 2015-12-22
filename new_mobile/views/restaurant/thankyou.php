<?php
function cartProductExist($title){
	global $cart;
	foreach ($cart->products as $product)
	{
		if($product->item_title == $title) return true;
	}
	return false;
}

if (is_numeric($loggedinuser->id))
{
	$clone=$cart->myclone();
}

if (isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"]) && !isset($clone))
{
    $mSQL = "SELECT SerializedCart FROM grouporder WHERE GroupID=".$_GET["grpid"]." AND CustomerID=".$_GET["grp_userid"]." AND UserID<>".$_GET["uid"]." AND FoodOrdered=1";
    $mRes = dbAbstract::Execute($mSQL);
    if (dbAbstract::returnRowsCount($mRes)>0)
    {
        while ($mRow=dbAbstract::returnObject($mRes))
        {
            $cartLoop = new $cart();
            $cartLoop = unserialize($mRow->SerializedCart);

            foreach ($cartLoop->products as $prodG)
            {
			  if(!cartProductExist($prodG->item_title)){
                $cart->addProduct($prodG);
			  }
            }
        }
		
    }
}


$favItems = array();
foreach ($loggedinuser->arrFavorites as $favItem)
{
	array_push($favItems, trim(strtolower($favItem->title)));
}

if( isset($clone))
{
	if($clone->isempty())
  	{
		//@mysql_close($mysql_conn);
		redirect($SiteUrl .$objRestaurant->url ."/" );
		exit;
	}
	
	if(isset($_POST['addfavourites']))
	{
		$repidreodering=1;
		//echo "<pre>"; print_r($clone); echo "</pre>";
		$loggedinuser->addMenuToCustomerFavorites($_POST['foodtitle'],serialize($clone->products),$repidreodering,$clone->delivery_type, (($clone->driver_tip == '')? 0:$clone->driver_tip));
			
		if($repidreodering==1 &&  $objRestaurant->did_number!='0' && trim($objRestaurant->did_number)!='') 
		{
			$objcdyne->sendSMS($loggedinuser->cust_phone1,"You now have ". $objRestaurant->name ."'s Rapid Re-Order!  Text one of your favorites to this number to Re-Order it","New Registeration",'',$objcdyne->SYSTEM_NEW_CUSTOMER);				
		}
	}		 
	//@mysql_close($mysql_conn);
	$cart->destroyclone();
	$cart->destroysession();
	redirect($SiteUrl .$objRestaurant->url ."/" );exit;
	// header("location: ?item=menu" );exit;
	
}
else 
{
	if($_SESSION['cart']==0)
	{
		//@mysql_close($mysql_conn);
		redirect($SiteUrl .$objRestaurant->url ."/" );exit;
	}
 	
	if(isset($_POST['x_order_id'])) 
	{
		extract($_POST);
		if($_POST['x_response_reason_code']==1)
		{
			$file_name = "pdffiles/pdf".$cart->order_id.".pdf";
			include $site_root_path. "views/cart/views/notify_customers.php";
			dbAbstract::Update("UPDATE ordertbl SET payment_approv=1 WHERE OrderID=".$cart->order_id); 	
			//@mysql_close($mysql_conn);	
			echo "<script type=\"text/javascript\">window.location='".$SiteUrl.$objRestaurant->url."/?item=thankyou';</script>";
			exit;
		}
		else 
		{
			//@mysql_close($mysql_conn);
			echo "<script type=\"text/javascript\">window.location='".$SiteUrl.$objRestaurant->url."/?item=failed&response_code=". $_POST['x_response_reason_code'] ."';</script>";
			
		}
	}
?>
<body class=thanks>
<?php 
	require($mobile_root_path . "includes/header.php"); 
	$rs = dbAbstract::Execute("select * from ordertbl where OrderId=".$_GET['orderid']);
	$order = dbAbstract::returnObject($rs);
	$orderDetail = json_decode($order->pay_load_json);
?>
<main class=main>
  <div class=main__container>
    <section class=thanks__section>
      <hgroup class=thanks__title>
        <h2>Thank you!</h2>
        <h3> 
          <?=(($cart->delivery_type==2)? 'Your food will be ready to <br>pick up ':'Your food will arriving soon to your location:')?>
          <?=(($cart->delivery_type==2)? (($order->asap_order==1)? 'in no time':'at'):'')?>
        </h3>
      </hgroup>
      <div class=section__article-content>
      	
		<?=($order->asap_order==0)? date('h:i A / M d', strtotime($order->DesiredDeliveryDate)):''?>
        
		<?php 
		if($cart->delivery_type == 2){
          echo '<p class=thanks__delivery>'.$objRestaurant->rest_address . ", " . $objRestaurant->rest_city . ", " . $objRestaurant->rest_state.'</p>';
        }else{
			echo '<p class=thanks__delivery>'.$order->DeliveryAddress.'</p>';
		}
		?>
        
        <p> 
          Your order number is: <br>
          <strong class=thanks__order-number>#<?=($cart->invoice_number=='')? $cart->order_id:$cart->invoice_number?></strong> 
        </p>
        
        
        
        <p> An order confirmation has been <br>
          emailed to you: </p>
        
        <?php
		if(is_numeric($loggedinuser->id) && $loggedinuser->as_guest==0)
		{ 
		?>
        <form method="post" id="favoritesform"  action="">
        	<input type="hidden" name="addfavourites" id="addfavourites" value="0">
            <p id="spnError" class="redtxt hide">A favorite order with same name already exists.</p>
            <p>
            <label for="name">Favorite Food Title: </label>
            <input name="foodtitle" id="foodtitle" />
            </p>
        	<a class=thanks__favorites id="btnAddFav" href="javascript:void(0)">Save to favorites
                <?php 
			if ($objRestaurant->did_number!='0' && trim($objRestaurant->did_number)!='') 
			{
			?>
                    <img style="vertical-align: middle" src='../css/new_mobile/images/quickfavorite.png' border='0' alt='QUICK FAVORITE' title='QUICK FAVORITE' />
                                <style>
                                   .thanks__favorites:after{ 
                                        background: none;
                                    }
                                    .thanks__favorites{
                                        padding: 0;
                                    }
                                </style>
			<?php
			}
			?>  </a>
		</form>
        <?php
		}
		?>
                
        <p>or share on:</p>
        <div class=social> 
        <?php
        $url = $SiteUrl .$objRestaurant->url."/?item=thankyou&orderid=".$_GET['orderid'];
		$title = 'Easy Way Ordering';
		?>
          <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($url.'&t='.$title)?>"
          	onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
            target="_blank" title="Share on Facebook">Facebook
          </a>

          <a class=twitter href="https://twitter.com/share?url=<?=urlencode($url)?>&via=TWITTER_HANDLE&text=<?=$title?>" 
          	onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
            target="_blank" title="Share on Twitter">Twitter
          </a>
          
        </div>
          <p>Questions about your order?</p>
          <a href='#'>Contact us</a> 
        </div>
    </section>
  </div>
</main>
<?php
	if($clone->payment_menthod==2){
		if ($objRestaurant->payment_gateway=="suregate")
		{
			$mSQL = "UPDATE ordertbl SET PNRef='".$cart->PNRef."' WHERE OrderID=".$cart->order_id;
			dbAbstract::Insert($mSQL);
		}
	}
	$cart->createclone();
 	$cart->destroysession();
}
?>
<script language="javascript">
$(document).ready(function(e) {
    $("#btnAddFav").click(function(e)
	{
		$('#spnError').hide();
		if ($('#foodtitle').val()=='') 
		{ 
			$('#foodtitle').css('border-color','#F00');
			e.preventDefault();
		} 
		else 
		{
			$('#foodtitle').css('border-color','#6b8f9a');
			var jsFav = <?php echo json_encode($favItems); ?>;
			if (jsFav.indexOf($.trim($('#foodtitle').val().toLowerCase())) >= 0)
			{
				$('#spnError').show();
				e.preventDefault();
			}else{
				$("#addfavourites").val(1);
				$("#favoritesform").submit();
			}
		}
		
	});
});
</script>