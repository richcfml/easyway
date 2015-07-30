<?php

$Orderid=$_REQUEST['Orderid'];
$userid=$_REQUEST['userid'];
$delverFlag=@$_REQUEST['delverFlag'];
//*************************************************************

$prdQuery = dbAbstract::Execute("select od.*, 
							p.prd_id, 
							p.item_title, 
							p.retail_price, 
							c.cust_desire_name, 
							c.cust_odr_address, 
							c.cust_ord_city, 
							c.cust_ord_state, 
							c.cust_phone1, 
							c.cust_phone2,
							ot.driver_tip 
							from orderdetails od,ordertbl ot,product p,customer_registration c 
							where ot.OrderID = $Orderid 
							and od.orderid =ot.OrderID 
							and p.prd_id=od.pid 
							AND c.id=ot.UserID", 1);
 $num = dbAbstract::returnRowsCount($prdQuery, 1);
?>
<script language="JavaScript">
function backto(){
 window.location="./?mod=<?=$mod?>&item=edituser&userid=<?=$userid?>";
}	
</script>
<h1>VIEW REGISTER CUSTOMER INFO</h1>

    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr bgcolor="#729338">
        <td width="25" align="left" valign="middle">&nbsp;</td>
        <td width="191" align="left" valign="middle"><strong>Item</strong></td>
        <td width="646" align="left" valign="middle"><strong>Description</strong></td>
        <td width="40" align="left" valign="middle"><strong>Qty</strong></td>
        <td width="93" align="left" valign="middle"><strong>Each</strong></td>
        <td width="60" align="left" valign="middle"><strong>Total </strong></td>
        </tr>
        <?php
	$i = 0;
	$itemArray="";
		$cartIdstr="";
		$subTotel=0;
	while( $saerchQry = dbAbstract::returnArray($prdQuery,1) ){ 
		if($i==0){$itemArray=$itemArray.$saerchQry[2];}else{$itemArray=$itemArray."~".$saerchQry[2];}
		$ProductID= $saerchQry["prd_id"];
	    if ( $i%2==0 ) {
			$color = "#F7E696";
		} else { 
			$color = "#FAF0C0";
		}
	?>
      <tr bgcolor="<? echo $color?>">
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><strong><? echo stripslashes(stripcslashes($saerchQry['item_title']));?>  </strong></td>
        <td> 
                <? $attribueArray=explode("~", $saerchQry['extra'])?>
                <? $Tot_atrib_price_Plus=0;
				$Tot_atrib_price_mines=0;
				$Option_N="";
				$TemOptNme="";
				$OptType=0;
				?>
                <?php for( $t=0;$t <count($attribueArray);$t++){							
										$attribueArrayString=str_replace("|","~",$attribueArray[$t]);
										 $ATrOptions= explode("~", $attribueArrayString);
										    
											if(!empty($ATrOptions[0])){
											$Atribut_Opt=trim($ATrOptions[0]);
											$Atribut_Opt=str_replace("^",'"',$Atribut_Opt);
											$Atribut_Opt=str_replace("'",'"',$Atribut_Opt);
											$OptionNameQry=dbAbstract::Execute("select option_name,Type from attribute where ProductID=$ProductID", 1);
											$OptonNameRs=dbAbstract::returnRow($OptionNameQry, 1);
												
											if($OptonNameRs[1]==2){  
												
												 if($OptonNameRs[0]!=$TemOptNme){ 
													if($OptType==2){
													  echo "<BR>";
														
													  }
												   $Option_Name=str_replace("Select","",str_replace("select","",$OptonNameRs[0]));
												   echo "<strong>".$Option_Name." "."</strong>";
												   $flagcoma=1;
												   $TemOptNme=$OptonNameRs[0];
													 
												  }
												 echo $ATrOptions[0];
											 if(!empty($ATrOptions[1])){
											  $price =   $ATrOptions[1];
											  
												$price1 =   $ATrOptions[1];
											if ($price!='0' || $price!='0.00') {
													$decider = "";
													if ($price<0) {
													   $decider = "Subtract";
													   $price1	= trim(substr($price1,1)); 
													  
													   $Tot_atrib_price_mines=$Tot_atrib_price_mines + $price;
													  
													} else {
														 $Tot_atrib_price_Plus=$Tot_atrib_price_Plus + $price;
														$decider = "Add";
													}
													
													$price = "|".$price; 
													$price1 = " - $decider $".$price1; 
											} else {
													$price = ""; 
													$price1 = ""; 
											}
											
												  echo $price1;
											}else{
											
											} 
											if(isset($attribueArray[$t+1])){
 												$attribueArrayString=str_replace("|","~",$attribueArray[$t+1]);
												 $ATrOptions= explode("~", $attribueArrayString);

												if(!empty($ATrOptions[0])){
													$Atribut_Opt=trim($ATrOptions[0]);
													$Atribut_Opt=str_replace("^",'"',$Atribut_Opt);
													$Atribut_Opt=str_replace("'",'"',$Atribut_Opt);
													$OptionNameQry2=dbAbstract::Execute("select option_name,Type from attribute where ProductID=$ProductID AND Title='$Atribut_Opt'", 1);
											
													$OptonNameRs2=dbAbstract::returnRow($OptionNameQry2, 1);
													
													if($OptonNameRs2[1]==2){
														if($OptonNameRs2[0]==$OptonNameRs[0]){ 
															echo ",";
														}	
													}
												 } 	 	
												
												
											}else{
												echo "";
											}
																						
												$OptType=$OptonNameRs[1];
											}else{
												 $Option_Name=str_replace("Select","",str_replace("select","",$OptonNameRs[0]));
												echo "<strong>".$Option_Name." "."</strong>";
												$AttbuteShow=str_replace("^",'"',$ATrOptions[0]);
											 	 echo $AttbuteShow." ";
													
										if(!empty($ATrOptions[1])){
											  $price =   $ATrOptions[1];
											  
												$price1 =   $ATrOptions[1];
											if ($price!='0' || $price!='0.00') {
													$decider = "";
													if ($price<0) {
													   $decider = "Subtract";
													   $price1	= trim(substr($price1,1)); 
													  
													   $Tot_atrib_price_mines=$Tot_atrib_price_mines + $price;
													  
													} else {
														 $Tot_atrib_price_Plus=$Tot_atrib_price_Plus + $price;
														$decider = "Add";
													}
													
													$price = "|".$price; 
													$price1 = " - $decider $".$price1; 
											} else {
													$price = ""; 
													$price1 = ""; 
											}
											
												  echo $price1;
											}else{
											$Tot_atrib_price_Plus=$AttbuteShow;
											} 
											
											
											 ?>
                <br>
                <? 
									}
								
							 
							}?>
                <? }  if($OptonNameRs[1]==2){ echo "<BR>"; }?>
                <strong>Special Instructions</strong>: 
                <? if(empty($saerchQry['RequestNote'])){echo "None";}else{ echo stripslashes(stripcslashes($saerchQry['RequestNote']));}?>
                <br> </td>
        <td align="left" valign="top"><? echo $saerchQry['quantity'];?></td>
        <? $TotalPlus_atribue= $saerchQry['retail_price']+$Tot_atrib_price_Plus+$Tot_atrib_price_mines;?>
        <td align="left" valign="top"><? echo "$". number_format($TotalPlus_atribue, 2);?></td>
        <? $total=$saerchQry['quantity']*$TotalPlus_atribue;?>
        <td align="left" valign="top">$<? echo number_format($total, 2) ?></td>
        <? $subTotel= $subTotel+$total;?>
        </tr>
        <?         
	 
	    $i++;
	     }?>
      <tr>
        <td colspan="3" align="right" valign="top"> <input name="button3" type="button" class="BlackBtn"  value="Back" onClick="backto()" /></td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><strong>Subtotal:</strong></td>
        <td align="left" valign="top">$<? echo number_format($subTotel, 2) ;?> </td>
        </tr>
         <?php $qry	=	dbAbstract::Execute("select * from ordertbl where OrderID=$Orderid and UserID=$userid", 1);
			$qryRs	=	dbAbstract::returnObject($qry, 1);
			
		?>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><strong>Coupon Discount:</strong></td>
        <td align="left" valign="middle">$<?=number_format($qryRs->coupon_discount, 2)?></td>
      </tr>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><strong>Driver's Tip:</strong></td>
       
        <td align="left" valign="top">$<? echo number_format($qryRs->driver_tip, 2)?></td>
      </tr>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><strong>Delivery Charges:</strong></td>
        <td align="left" valign="top">$<? echo number_format($qryRs->delivery_chagres, 2) ?> </td>
        </tr>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><strong>Tax:</strong></td>
        <?  $GT_TOTAL=$subTotel+$qryRs->delivery_chagres;
         	//$TAX=($subTotel * 5)/100 ;
			$tax_percent = $function_obj->get_cat_tax($qryRs->cat_id);
			$TAX=($subTotel * $tax_percent)/100;
			?>
        <td align="left" valign="top"><? echo "$".number_format($TAX, 2) ;?> </td>
        </tr>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top" bgcolor="#729338"><strong>Total:</strong></td>
        <?  $GT_TOTAL= $GT_TOTAL+$TAX+$qryRs->driver_tip-$qryRs->coupon_discount;?>
        <td align="left" valign="top" bgcolor="#729338"><strong><? $GT_TOTAL = number_format($GT_TOTAL, 2); echo "$". $GT_TOTAL;?> </strong></td>
        </tr>
    </table>
