<?php
$favoritesindex = $_GET['index'];
if (isset($favoritesindex)) {
    if (isset($loggedinuser->arrFavorites[$favoritesindex])) {
        $favoritefood = $loggedinuser->arrFavorites[$favoritesindex]->food;
        $cart->addfavorites($favoritefood);
    }
}
?>
<body class=favorites>
<div class=notification__overlay></div>
<?php require($mobile_root_path . "includes/header.php"); ?>
<main class=main>
  <div class=main__container>
    <div class=favorites__wrapper>
      <section class=section id=favorites>
        <header class=section__header>
          <h2 class=section__title>Favorite Orders</h2>
        </header>
        <p class=section__description>Re-order your favorite dishes exactly how you like every time!</p>
        <?php
		  foreach ($loggedinuser->arrFavorites as $favoritesindex=>$favorite) {
		  ?>
        <div class=section__article>
          <header class=section__article-header>
            <hgroup class=section__article-title>
              <h3>
                <?=stripcslashes($favorite->title)?>
              </h3>
              <?php
				$total = 0;
				foreach($favorite->food as $ff){
					$total += $ff->sale_price * $ff->quantity;
				}
				$mSales_tax_ratio = $objRestaurant->tax_percent;
				$mSalesTax = number_format(($mSales_tax_ratio/100)  * $total,2);
				$total = $mSalesTax+$total+$favorite->driver_tip;
				?>
              <h4>
                <?= $currency. number_format($total,2)?>
              </h4>
            </hgroup>
            <a data-step="<?=$favoritesindex?>" class="section__article-action plus js-goto" href="#">+</a> </header>
          <a class="section__article-delete removefavoritesorder" favIndex="<?=$favoritesindex?>" href="<?=$SiteUrl.$objRestaurant->url?>/?item=cart&removefavoritesindex=">Delete</a> </div>
        <?php 
		  }
		  ?>
        <div class='section__article hidden'>
          <div class=section__article-content>
            <p class=account__empty-message>You don't have any favorite yet</p>
          </div>
        </div>
        <p class='section__footer-message center-text'> Did you know you can text in your favorite orders? <a href="#" id="learn_more">Learn More!</a> </p>
      </section>
      <?php
          foreach ($loggedinuser->arrFavorites as $favoritesindex => $favorite) {
		  ?>
      <section class=section id=<?=$favoritesindex?>>
        <header class=section__header>
          <h2 class=section__title>
            <?=stripcslashes($favorite->title)?>
          </h2>
          <a data-step=favorites class="section__action back js-goto" href="#">Back</a> </header>
        <?php foreach($favorite->food as $foodIndex => $ff){ ?>
        <div class=section__article>
          <header class=section__article-header>
            <hgroup class=section__article-title>
              <h3>
                <?=$ff->item_title?>
              </h3>
              <h4>
                <?=$currency . number_format($ff->sale_price,2)?>
              </h4>
            </hgroup>
             </header>
          
            <a class="section__article-delete" href="#"> <span id='imgBtn' favindex="<?php echo($favoritesindex); ?>"  foodIndex="<?php echo($foodIndex); ?>" onclick='postitemTMP(this);' style='cursor: pointer; cusror: hand;'  alt='Remove from favorites' title='Remove from favorites' />Delete</span> </a> </div>
        <?php } ?>
      </section>
      <?php 
		  }
		  ?>
    </div>
  </div>
</main>
<script>
  	var currency= '<?= $currency?>';
    $(".removefavoritesorder").click(function(e){
        e.preventDefault();
        postitem($(this));
    });
	
    function postitem(source){
        var favIndex= $(source).attr('favIndex')
        var url = $(source).attr('href') +favIndex+ '&ajax=1';
        $.ajax({
            url: url,
            context:source,
            type: "POST",
            success: function(data){
				var parent= $(this).parent().parent()
                var current =$(this).parent()
                current.remove();
				parent.find('.section__article .removefavoritesorder').each(function(index){$(this).attr('favindex',index);})
              	//  location.reload()
            }
        });
    }
	
    function postitemTMP(element) 
    {
            var pFavoriteIndex=$(element).attr('favindex')
            var pRemIndex=$(element).attr('foodIndex')
            var element=element
//            console.log($(this))
//            return 
            
            var url = '';
            var mRandom = Math.floor((Math.random()*1000000)+1); 
            url="<?=$SiteUrl?><?=$objRestaurant->url?>/?item=favindex&flg=3&favoriteindex="+pFavoriteIndex+"&remindex="+pRemIndex+"&rndm="+mRandom+"&ajax=1";


            $.ajax
            ({
                    url: url,
                    context:element,
                    type: "POST",
                    success: function(data)
                    {
                            var parent= $(this).parent().parent().parent()
                            var current =$(this).parent().parent()
                            current.remove();
                           parent.find('.section__article img').each(function(index){$(this).attr('foodIndex',index);})
                           id=parent.attr('id')
                           $($('#favorites .section__article')[id]).find('hgroup h4').html(currency + data)
                            
                    },
                    error: function (jqXHR, textStatus, errorThrown) 
                    {
                            $('a[rel*=facebox]').facebox();
                            //alert(jqXHR.status);
                            //alert(textStatus);
                    }
            });
    }
    $(document).ready(function(e) {
      EasyWay.Favorites.setup();
	  var favId = 0;
	  $('.js-goto').click(function(){
	  	favId = $(this).attr('data-step');
	  });
	  
	  $(".footer__stepper-next").click(function(){
	  	return window.location.href="<?=$SiteUrl.$objRestaurant->url?>/?item=favorites&index="+favId;
	  });
          
          $("#hrefTerms").click(function(){
               window.location.hash = "#dvLearnMore", EasyWay.Notification.open()
          });
    });
	
  </script>
<div class="notification" id="learn_more_popup">
  <div  class="notification__box" id="dvLM" name="dvLM">
    <header class="notification__box-header center-text"> <a class="notification__box-action" href="#">X</a>
      <h3 class="notification__box-title"></h3>
    </header>
    Quick Favortie lets you order your favorite	meal simply by sending a text message!<br>
    <br>
    + Add our number <span style="color: red;">13478159685</span> to your	contact list<br>
    + Add at least one meal to your profile<br>
    + Register phone and payment method <a style="color: red;" href="?item=account">here</a> <br>
    <br>
    Next time your hungry, just text the name of your favorite meal code to our Quick Favorite number from your mobile phone and your order will be placed. It's that easy!
    you can	update your	mobile number <a style="color: red;" href="?item=account">here</a><br>
    <br>
    <a style="cursor: pointer; cursor: hand;" id="hrefTerms" name="hrefTerms" class="hrefTerms">Terms &amp; Conditions</a> </div>
</div>
<div class="notification"  id="dvLearnMore" name="dvLearnMore" style="position: absolute;height: 500px">
  <div class="notification__box" >
    <header class="notification__box-header center-text"> <a class="notification__box-action" href="#">X</a>
      <h3 class="notification__box-title"></h3>
    </header>
    <p><b>Terms:</b></p>
    <p>There is no charge for this service; however,&nbsp;<strong>message  and data rates may apply</strong>&nbsp;from your mobile carrier. Subject to the  terms and conditions of your mobile carrier, you may receive text messages sent  to your mobile phone. By providing your consent to participate in this program,  you approve any such charges from your mobile carrier. Charges for text  messages may appear on your mobile phone bill or be deducted from your prepaid  balance. EasyWay INC reserves the right to terminate this SMS service, in whole  or in part, at any time without notice.&nbsp; The information in any message  may be subject to certain time lags and/or delays.&nbsp; EasyWay INC nor our  restaurant clients are responsible for delayed or un-received orders.&nbsp; </p>
    <p>Submission of a food order using this service will result in a  charge from the restaurant. These charges can only be reversed at the  restaurant&rsquo;s discretion which may or may not be granted. It is your responsibility to ensure that only  you or authorized users have access to your mobile phone and your secret  favorite nicknames. EasyWay INC nor our  restaurant clients are responsible for un-intentionally placed or unauthorized  orders.</p>
    <p>Use of this service constitutes your continued agreement to  these terms which may be altered at any time.</p>
    <p><strong>United States participating carriers include:</strong> <br />
      ACS/Alaska, Alltel, AT&amp;T, Bluegrass Cellular, Boost,  Cellcom, Cellone Nation, Cellular One of East Central Illinois, Cellular South,  Centennial, Cincinnati Bell, Cox Communications, Cricket, EKN/Appalachian  Wireless, Element Mobile, GCI, Golden State Cellular, Illinois Valley Cellular,  Immix/Keystone Wireless, Inland Cellular, iWireless, Nex-Tech Wireless, Nextel,  nTelos, Plateau Wireless, South Canaan, Sprint, T-Mobile, Thumb Cellular,  United Wireless, US Cellular, Verizon Wireless, Viaero Wireless, Virgin,  WCC&nbsp;<br />
      Additional carriers may be added.</p>
    <p><strong>Canada participating carriers include:</strong> <br />
      Aliant Mobility, Bell Mobility, Fido, MTS, NorthernTel Mobility,  Rogers Wireless, SaskTel Mobility, Telebec Mobilite, TELUS Mobility, Videotron,  Virgin Mobile Canada, WIND Mobile.&nbsp; Additional carriers may be added.</p>
  </div>
</div>
