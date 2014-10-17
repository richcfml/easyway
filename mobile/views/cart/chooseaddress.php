<?php
	$mZipPostal = "Zip Code";
	$mStateProvince	= "State";
	
	if ($objRestaurant->region == "2") //Canada
	{
		$mZipPostal = "Postal Code";
		$mStateProvince	= "Province";
	}
?>
  <h1>Billing & delivery information</h1>
    <h3>Please choose your delivery address</h3>
    <input type="hidden" name="step" value="2">
    <p class="margintopsmall">
      <?= $loggedinuser->cust_your_name ." ". $loggedinuser->LastName ?>
      &nbsp; <a href="<?=$SiteUrl?><?= $objRestaurant->url ."/"?>?item=editaccount">Edit</a><br/>
      <input type="radio" checked="checked" name="address_option" value="1">
      <span class="bold">Delivery Address:</span><br/>
      &nbsp;&nbsp;
      <?= $loggedinuser->street1 ." ". $loggedinuser->street2 ." ". $loggedinuser->cust_ord_city ." ". $loggedinuser->cust_ord_state; ?>
      <br/>
      &nbsp;&nbsp;<?=$mZipPostal?>:
      <?= $loggedinuser->cust_ord_zip; ?>
    </p>
    <? if (trim($loggedinuser->delivery_street1)!='') { ?>
    OR
    <p class="margintopsmall"> <br/>
      <input type="radio"  name="address_option" value="2">
      <span class="bold">Alternate delivery Address:</span><br/>
      &nbsp;&nbsp;
      <?= $loggedinuser->delivery_street1 ." ". $loggedinuser->delivery_street2 ." ". $loggedinuser->delivery_city1 ." ". $loggedinuser->delivery_state1; ?>
      <br/>
      &nbsp;&nbsp;<?=$mZipPostal?>:
      <?= $loggedinuser->deivery1_zip; ?>
    </p>
    <? } ?>
    <div class="rightalign">
      <input type="submit" name="btnchoose" value=" Next "  class="button blue">
    </div>