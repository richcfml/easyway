<?php

	$custQry = mysql_query("select * from customer_registration where password != '' AND resturant_id=".$Objrestaurant->id);
 	$counter = 0;

	$mZipPostal = "Zip Code";
	$mStateProvince = "State";

	if ($Objrestaurant->region == "2") //Canada
	{
		$mZipPostal = "Postal Code";
		$mStateProvince = "Province";
	}
?>
<div id="contents">
<div id="main_heading">EXISTING REGISTER CUSTOMERS LIST </div>
<div class="form_outer" >
<table width="100%" border="0" cellspacing="1" class="listig_table">
  <tr>
    
    <th width="113"><strong>First Name</strong></th>
    <th width="113"><strong>Last Name</strong></th>
    <th width="175"><strong>Email</strong></th>
    <th width="132"><strong>Business Name</strong></th>
    <th width="119"><strong>Phone No.</strong></th>
    <th width="99"><strong><?=$mZipPostal?></strong></th>
    <th width="119"><strong>City</strong></th>
    <th width="119"><strong><?=$mStateProvince?></strong></th>
    <th width="119"><strong>Edit Info</strong></th>
  </tr>
  <tr>
    <td colspan="8">&nbsp;</td>
  </tr>
  <? while($custRs	=	mysql_fetch_object($custQry)){?>
  
  <tr bgcolor="<?=$bgcolor ?>" >
   
    <td><?=$custRs->cust_your_name?></td>
    <td><?=$custRs->LastName?></td>
    <td><a href="./?mod=<?=$mod?>&item=edituser&userid=<?=$custRs->id?>" style="text-decoration:underline;"><?=$custRs->cust_email?></a></td>
    <td><?=$custRs->cust_business_name?></td>
    <td><?=$custRs->cust_phone1?></td>
    <td><?=$custRs->cust_ord_zip?></td>
    <td><?=$custRs->cust_ord_city?></td>
    <td><?=$custRs->cust_ord_state?></td>
    <td><a href="./?mod=<?=$mod?>&item=useredit&userid=<?=$custRs->id?>" style="text-decoration:underline;">Edit</a></td>
    
  </tr>
  <? }?>
  <tr>
    <td colspan="9">&nbsp;</td>
  </tr>
</table>
</div>
