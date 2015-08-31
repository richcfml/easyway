<?php
	$mZipPostal = "Zip Code";
	$mStateProvince	= "State";
	
	if ($objRestaurant->region == "2") //Canada
	{
		$mZipPostal = "Postal Code";
		$mStateProvince	= "Province";
	}
?>
  <? if(is_numeric($loggedinuser->id)) {?>
        
        <? if ($cart->delivery_type==cart::Delivery){  ?>
        <div class="second_body_heading">Delivery Address/Contact</div>
        <div class="second_body_text">
     	<?= $loggedinuser->cust_your_name . " ". $loggedinuser->LastName?><br/>
    	 Delivery Address:    <?= $loggedinuser->getUserDeliveryAddress(0)?><br/>
         <?=$mZipPostal?>:    <?= $loggedinuser->getUserDeliveryZipCode()?><br/>
         

          <a href="?item=account&chooseaddress=1">Select another address from account</a> </div>
        <br />
        <? }?>
        <? }else {  ?>
        
         
         <table width="100%" border="0" cellspacing="8" cellpadding="" class="table" align="left">
          <!-- ------------------------------------------------------------------------->
          
       <? if ($cart->delivery_type==cart::Delivery){?>
          <tr>  
          
            <td colspan="2">   <div class="second_body_heading">Delivery Address/Contact</div></td>
           </tr>
           <? }?>
          
        
          <tr>
            <td width="80px">First Name:</td>
            <td><input type="text" name="customer_name" id="customer_name" value=""  maxlength="150"/></td>
          </tr>
          <tr>
            <td>Last Name:</td>
            <td><input type="text" name="customer_last_name" id="customer_last_name" value=""  maxlength="150"/></td>
          </tr>
          
          <tr>
            <td>Phone</td>
            <td><input type="text" name="customer_phone" id="customer_phone" value="" maxlength="20"/></td>
          </tr>
        
          <tr>
            <td>Email</td>
            <td><input type="text" name="customer_email" id="customer_email" value="" maxlength="150" /></td>
          </tr>

        
           
       
			<tr>  
            <td>Address</td>
            <td><input type="text" name="customer_address" id="customer_address" value="" maxlength="250"/></td>
           </tr>
 
	 	<tr> 
            <td>City</td>
            <td ><input type="text" name="customer_city" id="customer_city" value="" maxlength="150"/></td>
          </tr>
         	<tr> 
            <td><?=$mStateProvince?></td>
            <td ><input type="text" name="customer_state" id="customer_state" value="" maxlength="150"/></td>
          </tr>
     
          
          <tr>
            <td><?=$mZipPostal?></td>
            <td><input type="text" name="customer_zip" id="customer_zip" value="" maxlength="150"/></td>
          </tr>
     
       

   </table>
         
           
            
       <?  } ?>