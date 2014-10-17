<?
 if(!is_numeric($loggedinuser->id)) {
	  $action=$SiteUrl. $objRestaurant->url ."/?item=login"; 
  } else {
	  $action=$SiteUrl. $objRestaurant->url ."/?item=checkout"; 
  }
  
if(isset($_GET['ajax'])){
	
	extract($_GET);
	 
	if(isset($index))
	 {
		 $cart->remove_Item($index);
	 }else if(isset($delivery_type)){
		  $cart->setdelivery_type($delivery_type);
		 }
 
 
 }else{ 

echo "<section class='menu_list_wrapper'>";
}
?>
 <style>
 .menu_list_wrapper ul li  {  border-bottom: 2px dotted #EBEBEB; }
 .menu_list_wrapper ul.border2 li:last-child { border-bottom: none;}
  
 </style>
  <h1>Your Order Summary</h1>
  <ul class="border2">
    <? 
$index=-1;
  foreach($cart->products as $index=>$prod ) {
	 
  ?>
    <li>
      <div class="menu_list_txt_small">
        <?= $prod->item_title ?>
      </div>
      <div class="price_wrapper">
        <div class="remove_cart"><a href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?item=cart&index=<?=$index?>"><img src="../images/minus_sign.gif" width="14" height="14" border="0"></a> </div>
        <div class="menu_price_small"><?=$currency?>
          <?= $prod->sale_price * $prod->quantity ?>
        </div>
      </div>
    </li>
    <? } ?>
  </ul>
   <div style="border-top:3px solid #CCCCCC;   padding:5px; margin-top:10px;">
  <ul>
    <li>
      <div class="menu_list_txt_small">Subtotal:</div>
      <div class="price_wrapper">
        <div class="remove_cart"></div>
        <div class="menu_price_small"><?=$currency?><?= $cart->sub_total ?>
        </div>
      </div>
    </li>
    <?
	
	if($cart->delivery_type==cart::Delivery) {
	?>
    <li>
      <div class="menu_list_txt_small">Delivery Charges:</div>
      <div class="price_wrapper">
        <div class="remove_cart"></div>
        <div class="menu_price_small"><?=$currency?><?= $cart->delivery_charges() ?>
        </div>
      </div>
    </li>
    <? }  
	
	if($cart->driver_tip>0) {
	?>
    <li>
      <div class="menu_list_txt_small">Driver Tip:</div>
      <div class="price_wrapper">
        <div class="remove_cart"></div>
        <div class="menu_price_small"><?=$currency?><?= $cart->driver_tip ?>
        </div>
      </div>
    </li>
    <? }if($cart->coupon_discount>0) {
		
	?>
    <li>
      <div class="menu_list_txt_small">Coupon Discount:</div>
      <div class="price_wrapper">
        <div class="remove_cart"></div>
        <div class="menu_price_small"><?=$currency?><?= $cart->coupon_discount ?>
        </div>
      </div>
    </li>
    <? } ?>
     
    <li>
      <div class="menu_list_txt_small">Sales Tax:</div>
      <div class="price_wrapper">
        <div class="remove_cart"></div>
        <div class="menu_price_small"><?=$currency?><?= $cart->sales_tax() ?>
        </div>
      </div>
    </li>
   
    <li>
      <div class="menu_list_txt_small">Total:</div>
      <div class="price_wrapper">
        <div class="remove_cart"></div>
        <div class="menu_price_small bold"><?=$currency?><?=$cart->grand_total() ?>
        </div>
      </div>
    </li>
  </ul>
  </div>
  <div  style="margin: 0 auto; padding-top:20px; text-align:center;">
 <form action="<?=$action?>" method="post" class="commentsblock">
  <? if($objRestaurant->delivery_offer == 1) {?>
     <input type="submit" name="btncheckout" value="Delivery"  class="button blue" onclick="return verifytotal();"> 
     <? } ?>
     <input type="submit" name="btncheckout" value="Pickup"  class="button orange"> 
 </form>
     <br/><br/>
  </div>
  
<? if(!isset($_GET['ajax'])){ ?>
</section> <? } ?>
<script type="text/javascript">
function verifytotal() {
	var t=<?= $cart->sub_total ?>;
	var m=<?= $objRestaurant->order_minimum ?>;
	if(t < m)
		{
			alert("<?=$java_currency?><?=$objRestaurant->order_minimum ?> of food required to checkout. Please add more items");
			return false;	
		}
		return true;	
	}
 $(function(){
	 
	 $(".remove_cart a").click(function(e){
		e.preventDefault();
		 itemRemoved($(this));
			var count= $(".shopping-cart-header-count").html()
		 $(".shopping-cart-header-count").html(count-1)
		 });
		 
	  $(":radio").click(function(e){
	 
			url="?item=cart&ajax=1&delivery_type="+$(this).val();
			
			  $.ajax({
						url: url,
						type: "GET",
						success: function(data){
						 $('.menu_list_wrapper').html(data);
						 
						}
					 });  
					 
		 
		 });
	 
	 });
 function itemRemoved(source) {
	 var url=$(source).attr('href')+ '&ajax=1';
		 $.ajax({
						url: url,
						type: "POST",
						success: function(data){
						 $('.menu_list_wrapper').html(data);
						 
						}
					 });  
				 
	 }
 
 </script>
