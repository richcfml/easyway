<? 
	$delverFlag =	$_REQUEST["delverFlag"];
	$userid		=	$_REQUEST["userid"];
		
	if(empty($userid)){
		$userid=@$_SESSION['sessionUserID'];
	}

	$userQry	=	mysql_query("select * from customer_registration where id=$userid");
	$userRs		=	mysql_fetch_object($userQry);
	
	$userOrderQry	=	mysql_query("select OrderID,DesiredDeliveryDate from ordertbl where UserID=$userid");
 
?>
<div id="main_heading">VIEW REGISTER CUSTOMER INFO</div>
    <div class="form_outer">
    <div id="BodyLeftArea"><span class="RedText"><strong>Email:</strong></span> <?=$userRs->cust_email?><br />
<br />
<span class="RedText"><strong>Password:</strong></span> <? echo '******';?> <br />
<br />
<strong>Main Contact Information </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="./?mod=<?=$mod?>&item=useredit&userid=<?=$userRs->id?>" style="text-decoration:underline;">Edit</a><br /><br>

<strong class="RedText">Your Name:</strong> <?=$userRs->cust_your_name?><br />
<strong class="RedText">Business Name:</strong> <?=$userRs->cust_business_name?><br />
<? 
$streets=str_replace("~"," ",$userRs->cust_odr_address);

$mainOrdingAddress=$streets." ".$userRs->cust_ord_city." ".$userRs->cust_ord_state;
?>
<strong class="RedText">Main Ordering Address:</strong> <? echo $streets." ".$userRs->cust_ord_city." ".$userRs->cust_ord_state;?> <br />
<strong class="RedText">Phone Number:</strong> <?=$userRs->cust_phone1?> <br />
<strong class="RedText">Alt Phone Number:</strong> <?=$userRs->cust_phone2?> <br />
<br />
<strong>Alternate Delivery Address #1:</strong> <br /><br>

<? $d1streets=str_replace("~"," ",$userRs->delivery_address1);?>
						  </strong><? echo $d1streets;?><br>
                        <? $del_address1=$d1streets." ".$userRs->delivery_city1." ".$userRs->delivery_state1;?>
						 <? echo $userRs->delivery_city1;?><? if(!empty($userRs->delivery_state1)){?>,<? }?> <? echo $userRs->delivery_state1;?>
                         <br />
<strong>Alternate Delivery Address #2:</strong><br /><br>

 <? $d2streets=str_replace("~"," ",$userRs->delivery_address2);?>
				      <? $del_address2=$d2streets." ".$userRs->delivery_city2." ".$userRs->delivery_state2;?>
					 <? echo $d2streets;?> <br><? echo $userRs->delivery_city2;?><? if(!empty($userRs->delivery_state2)){?>,<? }?> <? echo $userRs->delivery_state2;?>
                     <br />
<strong>Alternate Delivery Address #3:</strong><br /><br>

 <? $d3streets=str_replace("~"," ",$userRs->delivery_address3);?>
				     <? $del_address3=$d3streets." ".$userRs->delivery_city3." ".$userRs->delivery_state3;?>
					  </strong><? echo $d3streets;?><br><? echo $userRs->delivery_city3;?><? if(!empty($userRs->delivery_state3)){?>,<? }?> <? echo $userRs->delivery_state3;?>
                     <br />
                    

</div>
<br />

    <div id="BodyRightArea">
      <div id="HeadingArea"><h1>Order History</h1>
      </div>
      <div class="form_outer">
      <table width="100%" border="0" cellspacing="0" cellpadding="4" class="listig_table">
        <tr bgcolor="#729338">
          <th  align="left" valign="middle"><strong>Order No</strong></th>
          <th align="left" valign="middle"> <strong>Date Placed</strong></th>
        </tr>
        <?
		$userOrderRows	=	mysql_num_rows($userOrderQry);
		if ($userOrderRows > 0){
	  	$i=0;
      	while($userOrderRs	=	mysql_fetch_object($userOrderQry)){
				$orderid	=	$userOrderRs->OrderID;
				$colour		=	($i%2!=0) ? "#F8F8F8": "";
		?>
       
        <tr bgcolor="<?=$colour?>">
          <td align="left" valign="middle"><a href="./?mod=<?=$mod?>&item=customerorder&userid=<?=$userid?>&Orderid=<?=$orderid?>" style="text-decoration:underline;"><?=$userOrderRs->OrderID?></a></td>
          <td align="left" valign="middle"><?=$userOrderRs->DesiredDeliveryDate?></td>
        </tr>
          <? }
		}else{
		  ?>
          <tr>
          <td  align="left" valign="middle">&nbsp;</td>
          <td align="left" valign="middle">&nbsp;</td>
        </tr>
        <tr class="msg_error">
          <td width="65" align="center" valign="middle" colspan="2" class="msg_warning" >No Order Found.</td>
        </tr>
        <? }?>
      </table>
      </div>
</div>
    <br class="clearfloat" />
    </div>