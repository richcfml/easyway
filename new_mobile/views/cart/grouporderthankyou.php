<?php
    $cart->destroysession();
    $mSQL = "SELECT * FROM grouporder WHERE CustomerID=".$_GET["grp_userid"]." AND GroupID=".$_GET["grpid"]." AND UserID=".$_GET["uid"];
    $mRow = dbAbstract::ExecuteObject($mSQL);
    $to = $mRow->grp_useremail; 
    $subject = 'Easywayordering Group Order Confirmation';
    $message = "<div style='width: 100%; font-family: Arial; font-size: 14px;'>";
    $message .= "Thank you for your order!<br />Your order will be processed when the Group Order is submitted.";
    $message .= "</div>";
    $from = $mRow->grp_name;
    $objMail->sendEmailTo($from, $message, $subject, $to);
    
    $mSQL = "SELECT grp_useremail FROM grouporder WHERE CustomerID=".$_GET["grp_userid"]." AND GroupID=".$_GET["grpid"]." AND grp_usertype='leader'";
    $mRow = dbAbstract::ExecuteObject($mSQL);
?>

<body class=thanks>
<?php 
	require($mobile_root_path . "includes/header.php"); 
?>
<main class=main>
  <div class=main__container>
    <section class=thanks__section>
      <hgroup class=thanks__title>
        <h2>Thank you!</h2>
        <h3> 
          Your order will be processed when the Group Order is submitted. <br><a href="mailto:<?=$mRow->grp_useremail?>">Contact Group Order Leader
        </h3>
      </hgroup>
      
      <div class=section__article-content>
      	<p>or share on:</p>
        <div class=social> 
          <a class="facebook" id="share_button" href="javascript:void(0)" title="Share on Facebook">Facebook</a>
          <a target="_blank" class=twitter href="http://twitter.com/?status=Just+ordered+something+great+from+&quot;<?=urlencode($objRestaurant->name)?>&quot;. <?=$SiteUrl.$objRestaurant->url?>/" title="Share on Twitter">Twitter</a>
          
        </div>
          <p>Questions about your order?</p>
          <a href='#'>Contact us</a> 
        </div>
    </section>
  </div>
</main>

<script type="text/javascript" language="javascript">
$(document).ready(function(){
  $('#share_button').click(function(e){
	  FB.ui({
		  method: 'feed',
		  name: '<?=str_replace("'", "", $objRestaurant->name)?>',
		  link: '<?=$SiteUrl.$objRestaurant->url?>/',
		  caption: '<?=str_replace("'", "", $objRestaurant->name)?> Online Ordering',
		  description: 'Just ordered something great from &quot;<?=str_replace("'", "", $objRestaurant->name)?>&quot;. <?=$SiteUrl.$objRestaurant->url?>/',
		  message: 'Just ordered something great from &quot;<?=str_replace("'", "", $objRestaurant->name)?>&quot;. <?=$SiteUrl.$objRestaurant->url?>/'
	  });
  });
});
</script>