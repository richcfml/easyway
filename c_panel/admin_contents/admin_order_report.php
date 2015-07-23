<? require_once("../../../includes/config.php");
 $OrderID = @$_GET['OrderID'];
 
 $prdQuery="select o.*,DATE_FORMAT(OrderDate,'%m/%d/%Y'),c.cust_your_name, c.LastName,c.cust_phone1,c.cust_odr_address DeliveryAddress from customer_registration c,ordertbl o where o.UserID=c.id  and
	 o.OrderID = ". $OrderID ." ORDER BY o.OrderID DESC";
	 
 	 $prdQuery= mysql_query($prdQuery);
 
	 $Ord_RS=mysql_fetch_array($prdQuery);
	
 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>easywayordering</title>
<link href="../includes/main.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td colspan="2"></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
  <td width="200">&nbsp;</td>
    <td height="698" align="left" valign="top"  >
      <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
        <tr> 
          <td>
              <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <tr> 
                <td colspan="3"><h2><center>Order Detail</center></h2></td>
                <td>&nbsp;</td>
              </tr>
               
              <tr> 
                <td colspan="3" class="style1"><HR width="100%" noShade SIZE=1></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="2"><strong>Customer Information</strong></td>
                <td width="14%" class="style1" >&nbsp;</td>
                <td width="3%">&nbsp;</td>
              </tr>
              <tr> 
                <? $customer_address=explode("~",$Ord_RS["cust_odr_address"]);
						$str_Cust_add="";
						for($i=0;$i<count($customer_address);$i++){
						$str_Cust_add=$str_Cust_add.$customer_address[$i];
						}
				?>
                <td colspan="3"><strong>Customer Name: </strong><? echo $Ord_RS["cust_your_name"]?> 
                  <? echo $Ord_RS["LastName"]?></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="3"><strong>Address: </strong><? echo $str_Cust_add." ".$Ord_RS["cust_ord_city"]." ".$Ord_RS["cust_ord_state"] ;?></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="3"><strong>Phone: </strong><? echo $Ord_RS["cust_phone1"]?></td>
                <td>&nbsp;</td>
              </tr>
			    <tr> 
                <td colspan="3"><strong>Delivery Address:</strong> <? echo stripslashes(str_replace('~',' ',$Ord_RS["DeliveryAddress"]))?></td>
                <td>&nbsp;</td>
              </tr>
              
              <tr> 
                <td colspan="3" class="style1"><HR width="100%" noShade SIZE=1></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3" class="style1"><strong>Order Information</strong></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3" ><strong>Order No: </strong><? echo $Ord_RS["OrderID"]?></td>
                <td>&nbsp;</td>
              </tr>
               <? 
				$res_qry	=	mysql_query("select * from resturants where id =".$Ord_RS["cat_id"]);
				
				$res_rs		=	mysql_fetch_object($res_qry);
				?>
                <tr>
                  <td colspan="3" ><strong>Restaurant Name:  </strong><?=$res_rs->name?></td>
                  <td>&nbsp;</td>
                </tr>
                 <tr>
                  <td colspan="3" ><strong>Payment Method:  </strong><?=$Ord_RS['payment_method']?></td>
                  <td>&nbsp;</td>
                </tr>
                 <tr>
                  <td colspan="3" ><strong>Order Receiving Method:  </strong><?=$Ord_RS['order_receiving_method']?></td>
                  <td>&nbsp;</td>
                </tr>
                <? if( $res_rs->payment_method == "Credit Card" ) {?>
                <tr>
                  <td colspan="3" ><strong>Authorise.net Invoice Number:  </strong><?=$Ord_RS['a_dotnet_invoice_number']?></td>
                  <td>&nbsp;</td>
                </tr>
                <? }?>
                
              <tr>
                <td colspan="3" ><strong>Deliver Date &amp; Time: </strong><? echo $Ord_RS["DesiredDeliveryDate"]?> </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3" ><strong>Special Requests/Notes: </strong><? echo $Ord_RS["DelSpecialReq"]?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3" class="style1"><HR width="100%" noShade SIZE=1></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="3" class="style1"><strong>Order Detail</strong></td>
                <td>&nbsp;</td>
              </tr>
              <?   $prdQuery2 ="select od.*,p.prd_id,p.item_title,p.item_code,p.retail_price,p.sale_price  from orderdetails od, product p  where od.orderid = $OrderID and  p.prd_id=od.pid  ";
			     $GrandTotal=0;
				 $prdQuery2= mysql_query($prdQuery2);
				while($Ord_RS2=mysql_fetch_array($prdQuery2,MYSQL_BOTH)){
				 $ProductID= $Ord_RS2["prd_id"]?>
				                 <tr> 
                  <td colspan="3"><strong>Item Code:</strong> <? echo $Ord_RS2["item_code"]?></td>
                  <td>&nbsp;</td>
                </tr>
              <tr> 
                <td colspan="3"><strong>Item Title:</strong> <? echo stripslashes($Ord_RS2["item_title"])?></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="3"><strong>Quantity:</strong> <? echo $Ord_RS2["quantity"]?></td>
                <td>&nbsp;</td>
              </tr>
			
                <tr> 
                  <td colspan="3"><strong>Special Notes:</strong> <? echo $Ord_RS2["RequestNote"]?></td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
              <tr> 
                <td colspan="3"><strong>Item Price:</strong> $<? echo $Ord_RS2["retail_price"]?></td>
                <td>&nbsp;</td>
              </tr>
              <? if($Ord_RS2['extra']) { ?>
              <tr> 
                <td colspan="3"> 
                  <? $sybTotelAttribute=0;
					 $Tot_atrib_price_Plus=0;
					$Tot_atrib_price_mines=0;
					$TemOptNme="";
					$OptType=0;
					?>
                  <? $attribueArray=explode("~", $Ord_RS2['extra'])?>
                  <? for( $t=0;$t <count($attribueArray);$t++){
										//  echo $attribueArray[$t]."<br>";
										
										$attribueArrayString=str_replace("|","~",$attribueArray[$t]);
										 $ATrOptions= explode("~", $attribueArrayString);
									
									//	  echo $ATrOptions[0]."<br>";
									//	    echo $ATrOptions[1]."<br>";
										    
											if(!empty($ATrOptions[0])){
											$Atribut_Opt=trim($ATrOptions[0]);
											$Atribut_Opt=str_replace("^",'"',$Atribut_Opt);
											$Atribut_Opt=str_replace("'",'"',$Atribut_Opt);
											$OptionNameQry=mysql_query("select option_name,Type from attribute where ProductID=$ProductID AND Title='$Atribut_Opt'");
										//	echo "select option_name,Type from attribute where ProductID=$ProductID AND Title='$Atribut_Opt'"."<br>";
											$OptonNameRs=mysql_fetch_row($OptionNameQry);
												
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
													$OptionNameQry2=mysql_query("select option_name,Type from attribute where ProductID=$ProductID AND Title='$Atribut_Opt'");
											
													$OptonNameRs2=mysql_fetch_row($OptionNameQry2);
													
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
											
											}
											
											
											 ?>

                  <br> 
                  <? }
					}?>
                  <?  } ?>                </td>
                <td>&nbsp;</td>
              </tr>
              <? } ?>
            	<tr>
                	<td colspan="3">
                    
                    <? if($Ord_RS2[associations]) {?>
					<strong>Associated Items:</strong>
					<?   
                       	echo str_replace('|','- add $',$Ord_RS2[associations]);
                   ?>
                    <? } ?>
                    </td>                
                </tr>
            
           	 <tr> 
			  <? 
			  $assocItemArr = explode("~",$Ord_RS2[associations]);
			  $assocTotalPrice = 0;
			  for($j=0; $j<count($assocItemArr); $j++) {
				  $assocOptions= explode("|", $assocItemArr[$j]);
				  $assocPrice = $assocOptions[1];
				  $assocTotalPrice = $assocTotalPrice + $assocPrice; 
				  }
		  	?>
                <td colspan="3">
                <? $itemTotalPrice = $Ord_RS2["retail_price"] + $Tot_atrib_price_Plus + $assocTotalPrice;
				  	 $Tot_atrib_price_Plus = 0;
				?>
				<strong>Item Total Price:</strong> $<? echo $itemTotalPrice;?>
               </td>
                <td>&nbsp;</td>
              </tr>
              
            
              <? $TotalPlus_atribue= $Ord_RS2['retail_price']+$Tot_atrib_price_Plus+$Tot_atrib_price_mines;?>
              <? $itemQuanTotel=$Ord_RS2['quantity']*$TotalPlus_atribue;?>
              <tr> 
                <td colspan="3" class="style1"><HR width="100%" noShade SIZE=1></td>
                <td>&nbsp;</td>
              </tr>
              <? }
			  
			  if($Ord_RS["coupon_discount"] == ""){
				 	$coupon_discount = '0.00';
				 }else{
				 	$coupon_discount = number_format($Ord_RS["coupon_discount"], 2);
				 }
				 ?>
                 <tr> 
                  <td colspan="3"><strong>Coupon Discount:</strong> $<? echo number_format($coupon_discount,2);?></td>
                  <td>&nbsp;</td>
                </tr>
              <tr> 
                <td colspan="3"><strong>Delivery:</strong> $<? echo number_format($Ord_RS["delivery_chagres"],2);?></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="3" ><strong>Tax:</strong> $<? echo number_format($Ord_RS["Tax"],2);?></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="3" class="style1"><strong>Total: </strong>$<? echo number_format($Ord_RS["Totel"],2);?></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="3" class="style1"><HR width="100%" noShade SIZE=1></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td width="30%">&nbsp;</td>
                <td width="53%"><input type="button" name="Submit" value="   Print   " onClick="javascript:window.print()"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr align="left" valign="top"> 
          <td>&nbsp;</td>
        </tr>
      </table>
      </td>
    <td width="9" >&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2"></td>
  </tr>
</table>
</body>
</html>
