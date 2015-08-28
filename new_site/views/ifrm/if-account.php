<?php 
	if(!is_numeric($loggedinuser->id)) {
		 /*echo "<script type='text/javascript'>window.location='?item=login'</script>";*/
		 header("location: ". $SiteUrl .$objRestaurant->url ."/?item=login&ifrm=login" );exit;
	}
if(isset($_POST['btnupdate'])) {
	extract($_POST);
        $mSalt = $loggedinuser->salt;
        $ePassword= hash('sha256', trim($user_password).$mSalt);
	$loggedinuser->cust_email= trim($email)=='' ? $loggedinuser->	cust_email : $email;
        $loggedinuser->epassword= trim($user_password)=='' ? $loggedinuser->epassword : $ePassword;
 	$loggedinuser->cust_your_name= trim($first_name)=='' ? $loggedinuser->	cust_your_name : $first_name;
	$loggedinuser->LastName= trim($last_name)=='' ? $loggedinuser->	LastName : $last_name;
	$loggedinuser->street1= trim($address1)=='' ? $loggedinuser->	street1 : $address1;
	$loggedinuser->street2= trim($address2)=='' ? $loggedinuser->	street2 : $address2;
	$loggedinuser->cust_ord_city= trim($city)=='' ? $loggedinuser->	cust_ord_city : $city;
	$loggedinuser->cust_ord_state= trim($state)=='' ? $loggedinuser->	cust_ord_state : $state;
	$loggedinuser->cust_ord_zip= trim($zip)=='' ? $loggedinuser->	cust_ord_zip : $zip;
	$loggedinuser->cust_phone1= trim($phone1)=='' ? $loggedinuser->	cust_phone1 : $phone1;
	$loggedinuser->delivery_street1= trim($saddress1)=='' ? $loggedinuser->	delivery_street1 : $saddress1;
	$loggedinuser->delivery_street2= trim($saddress2)=='' ? $loggedinuser->	delivery_street2 : $saddress2;
	$loggedinuser->delivery_city1= trim($scity)=='' ? $loggedinuser->	delivery_city1 : $scity;
	$loggedinuser->delivery_state1= trim($cstate)=='' ? $loggedinuser->	delivery_state1 : $cstate;
	$loggedinuser->deivery1_zip= trim($czip)=='' ? $loggedinuser->	deivery1_zip : $czip;
	$loggedinuser->update();
}

 

?>
<div id="body_left_col" style="width:64.5%; border:#e4e4e4 1px solid;">
      <div class="heading">Account Manager</div>
      <div class="account_detail"> <strong>Email:</strong>&nbsp;&nbsp;
        <?=$loggedinuser->cust_email?>
        <br />
        <br />
        <strong>Password:</strong>&nbsp;
        <?="***";?>
        
        &nbsp;[&nbsp;<a  href="?item=editaccount&ifrm=editaccount">Change</a>&nbsp;]<br />
        <br />
        <span style="font-weight:bold;" >Main Contact Information </span> [&nbsp;<a href="?item=editaccount&ifrm=editaccount" class="RedText_Underline">edit</a>&nbsp;]<br />
        <strong class="RedText">Your Name:</strong>
        <?=$loggedinuser->cust_your_name?>
        <br />
        <strong class="RedText">Business Name:</strong>
        <?=$loggedinuser->cust_business_name?>
        <br />
        <? 
$streets=str_replace("~"," ",$loggedinuser->cust_odr_address);

$mainOrdingAddress=$streets." ".$loggedinuser->cust_ord_city." ".$loggedinuser->cust_ord_state;
?>
        <strong class="RedText">Main Ordering Address:</strong> <? echo $streets." ".$loggedinuser->cust_ord_city." ".$loggedinuser->cust_ord_state;?> <br />
        <strong class="RedText">Phone Number:</strong>
        <?=$loggedinuser->cust_phone1?>
        <br />
        <strong class="RedText">Alt Phone Number:</strong>
        <?=$loggedinuser->cust_phone2?>
        <br />
        <br />
        <strong>Alternate Delivery Address:</strong> [&nbsp;<a href="?item=editaccount&ifrm=editaccount" class="RedText_Underline">edit</a>&nbsp;]<br />
        <? $d1streets=str_replace("~"," ",$loggedinuser->delivery_address1);?>
        </strong><? echo str_replace('~',' ',$loggedinuser->delivery_address1);   ?> <br/><?=$loggedinuser->delivery_city1 . " ". $loggedinuser->delivery_state1 ?><br>
       
  
        <? if(isset($_GET['chooseaddress'])){ ?>
        <br>
        <form action="?item=checkout&ifrm=checkout" method="post" name="form1">
          <strong>Select Delivery Address : </strong><br>
          <select name="delivery_address" id="delivery_address">
          	<option  >--- Select One ---</option>
            <option value="1" ><? echo $streets." ".$loggedinuser->cust_ord_city." ".$loggedinuser->cust_ord_state; ?></option>
            <option value="2" ><?  echo str_replace('~',' ',$loggedinuser->delivery_address1);   ?> <br/><?=$loggedinuser->delivery_city1 . " ". $loggedinuser->delivery_state1  ;?></option>
          </select>
          <br>
          <br>
          <input name="submit" type="submit" class="BlackBtn" value="Back to Cart">
        </form>
        <?  }?>
      </div>
      <div style="clear:both"></div>
    </div>
   
    <div id="body_right_col" style="width:32%; border:#e4e4e4 1px solid;">
      <div id="your_summery">Order History</div>
      <div class="username">
        <table width="100%" border="0" cellspacing="0" cellpadding="4" class="listing">
          <tr>
            <td width="65" align="left" valign="middle"><strong>Order No</strong></td>
            <td align="left" valign="middle"><strong>Date Placed</strong></td>
          </tr>
               <tr>
            <td colspan="2"><hr /></td>
        
          </tr>
         <?php
		 $userOrderQry	=	dbAbstract::Execute("select OrderID,DesiredDeliveryDate from ordertbl where UserID=".$loggedinuser->id);
		 
	$i=0;
		  if($userOrderQry) {  
	  	$i=1;
      	while($userOrderRs	=	dbAbstract::returnObject($userOrderQry)){
				$orderid	=	$userOrderRs->OrderID;
				$colour		=	($i%2==0) ? "#FFF": "#E4E4E4"; 
				$i++;
		?>
          <tr style="background-color:<?=$colour?>">
            <td align="left" valign="middle"><a href="?mod=<?=$mod?>&item=orderhistory&Orderid=<?=$orderid?>&ifrm=orderhistory" class="RedText_Underline">
              <?=$userOrderRs->OrderID?>
              </a></td>
            <td align="left" valign="middle"><?=$userOrderRs->DesiredDeliveryDate?></td>
          </tr>
          <? }?>
          <? } if($i==0) {?>
          <tr>
            <td colspan="2" align="left" valign="middle">
            <div class="msg_warning">No order is placed yet.</div>
            
            </td>
          </tr>
         <? }?> 
        </table>
      </div>
      <div style="clear:both"></div>
    </div>
    <!--End body_right_col Div-->
    <div style="clear:both"></div>

 
