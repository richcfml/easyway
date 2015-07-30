 <?php
 
 $OrderID = @$_GET['OrderID'];
 
	
	$prdQuery="select o.*,DATE_FORMAT(OrderDate,'%m/%d/%Y'),c.cust_your_name, c.LastName,c.cust_phone1,cust_ord_city,cust_ord_state,cust_room,c.cust_odr_address DeliveryAddress from customer_registration c,ordertbl o where o.UserID=c.id  and
	 o.OrderID = ". $OrderID ." ORDER BY o.OrderID DESC";
 
 	 $prdQuery= dbAbstract::Execute($prdQuery, 1);
 
	 $Ord_RS=dbAbstract::returnArray($prdQuery,1);
?>
<div id="main_heading">VIEW EXISTING ORDERS </div>
<script language="JavaScript">
function DeleteOrder(OrdID){

alert("Are You sure delete this Order");

}
function showPopReport(OrdID){

window.open("admin_contents/orders/admin_order_report.php?OrderID="+OrdID,"Report","width=1100, height=700, top=0, left=100, toolbar=0, menubar=0, location=0, status=1, scrollbars=1, resizable=0,titlebar=0");
}

function updateOrder(id){
		
		window.location.href="?mod=order&item=orderedit&OrderID="+id;	
	}

</script>
<div class="form_outer">
   <table width="750" border="0" cellpadding="4" cellspacing="0">
       
        <tr> 
          <td><form name="form1" method="post" action="" enctype="multipart/form-data" >
          <table width="100%" border="0" cellspacing="3" cellpadding="0">
                <tr> 
                  <td width="73" colspan="2" class="style3"><strong>Customer Information:</strong></td>
                  <td width="24%" class="style1" ><a href="./?mod=<?=$mod?>&item=order_delete&OrderID=<?=$OrderID?>" onClick="return confirm('Are you sure you want to delete this order?')">Delete</a></td>
                  <td width="3%">&nbsp;</td>
                </tr>
                <tr> 
               
                  <td colspan="3"><strong>Customer Name:</strong> <? echo $Ord_RS["cust_your_name"].' '.$Ord_RS["LastName"]?></td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td colspan="3"><strong>Address:</strong> <? echo stripslashes(stripcslashes(trim($Ord_RS["DeliveryAddress"],"~")." ".$Ord_RS["cust_ord_city"]." ".$Ord_RS["cust_ord_state"] ));?></td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td colspan="3"><strong>Phone: </strong><? echo $Ord_RS["cust_phone1"]?></td>
                  <td>&nbsp;</td>
                </tr>
                <? if($Ord_RS["cust_room"] > 0){?>
                	<tr> 
                	  <td colspan="3"><strong>Customer Room:</strong> <? echo $Ord_RS["cust_room"]?></td>
                  	  <td>&nbsp;</td>
                	</tr>
                    
                <? }?>
				<?php 
					if (trim(strtolower($Ord_RS["order_receiving_method"]))== "delivery")
					{
				?>
                <tr> 
                  <td colspan="3"><strong>Delivery Address:</strong> <? echo stripslashes(str_replace('~',' ',$Ord_RS["DeliveryAddress"]))?></td>
                  <td>&nbsp;</td>
                </tr>
                <?php
					}
				?>
                <tr> 
                  <td colspan="3" class="style1"><HR width="100%" noShade SIZE=1></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3" class="style3"><strong>Order Information:</strong></td>
                  <td>&nbsp;</td>
                </tr>
                
                <tr>
                  <td colspan="3" ><strong>Order No: </strong> <? echo $Ord_RS["OrderID"]?></td>
                  <td>&nbsp;</td>
                </tr>
                   
			  <tr>
                  <td colspan="3" ><strong>Restaurant Name: </strong> <?=$Objrestaurant->name?></td>
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
                <? if( $Objrestaurant->payment_method == "Credit Card" ) {?>
                <tr>
                  <td colspan="3" ><strong>Authorise.net Invoice Number: </strong> <?=$Ord_RS['a_dotnet_invoice_number']?></td>
                  <td>&nbsp;</td>
                </tr>
                <? }?>
                <tr>
                  <td colspan="3" ><strong>Deliver Date &amp; Time: </strong><? echo $Ord_RS["DesiredDeliveryDate"]?> </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3" ><strong>Submitation Date &amp; Time: </strong><? echo date("m-d-Y h:i:s", strtotime($Ord_RS["submit_time"]))?> </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3" ><strong>Special Requests/Notes: </strong><? echo stripslashes( stripcslashes( $Ord_RS["DelSpecialReq"] ) ) ?> </td>
                  <td>&nbsp;</td>
                </tr>

                <tr>
                  <td colspan="3" class="style1"><HR width="100%" noShade SIZE=1></td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td colspan="3" class="style3"><strong>Order Detail:</strong></td>
                  <td>&nbsp;</td>
                </tr>
                 <?php  $prdQuery2 ="select * from orderdetails where orderid = $OrderID";
			     $GrandTotal=0;
				 $prdQuery2= dbAbstract::Execute($prdQuery2, 1);

				 while($Ord_RS2=dbAbstract::returnArray($prdQuery2,1)){
				 $ProductID = $Ord_RS2["pid"];
				 $mOrderDetailsID = $Ord_RS2["OrdDetailID"];
				 ?>
                <tr> 
                  <td colspan="3"><strong>Item Title:</strong> <? echo stripslashes(stripcslashes($Ord_RS2["ItemName"]))?></td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td colspan="3"><strong>Quantity:</strong> <? echo $Ord_RS2["quantity"]?></td>
                  <td>&nbsp;</td>
                </tr>
                <!--<tr>
                  <td colspan="3"><strong>Item For:</strong> <? echo $Ord_RS2["ItemName"]?></td>
                  <td>&nbsp;</td>
                </tr>-->
                <tr> 
                  <td colspan="3"><strong>Special Notes:</strong> <? echo stripslashes(stripcslashes($Ord_RS2["RequestNote"]))?></td>
                  <td>&nbsp;</td>
                </tr>
				<?
				 	$cart_price = $Ord_RS2['retail_price'];
				?>
                <tr> 
                  <td colspan="3"><strong>Item Price:</strong> <?=$currency?><? echo $cart_price?></td>
                  <td>&nbsp;</td>
                </tr>
               	<?php
				$mAttributeSQL = "SELECT ProductCount, OptionName AS option_name, Type, AttributeTitle AS Title, AttributePrice AS Price, IFNULL(`Limit`, 0) AS AttributeLimit, IFNULL(LimitPrice, 0) AS LimitPrice FROM orderdetails_attribute_options WHERE OrderID=".$OrderID." AND OrderDetailsID=".$mOrderDetailsID;
				$mAttributeRes = dbAbstract::Execute($mAttributeSQL, 1);
				if (dbAbstract::returnRowsCount($mAttributeRes)>0)
				{
					echo('<tr>');
               		echo('<td colspan="3">'); 
					$mPricePlus = 0;
					$mPriceMinus = 0;
					$mPrevOptionName = "";
					$mLimitCount = 1;
					$mPrevProductCount = 0 ;
					$mLimit = 0;
					$mLimitPrice = 0;
						
					while ($mAttributeRow = dbAbstract::returnObject($mAttributeRes, 1))
					{	
						$mLimit = trim($mAttributeRow->AttributeLimit);
						$mLimitPrice = trim($mAttributeRow->LimitPrice);
						
						if (($mLimit<0) || ($mLimitPrice<0))
						{
							$mLimit = 0;
							$mLimitPrice = 0;
						}
						
						
						if (trim(strtolower($mPrevOptionName)) == trim(strtolower($mAttributeRow->option_name)))
						{
							$mLimitCount++;	
						}
						else
						{
							if ($mPrevProductCount!=0)
							{
								echo("<br />");
							}
							$mPrevOptionName = $mAttributeRow->option_name;
							$mPrevProductCount = $mAttributeRow->ProductCount;
							$mLimitCount = 1;
							
							echo("<strong>".$mAttributeRow->option_name." "."</strong>");
						}
						
						echo($mAttributeRow->Title);
						$mPrice =  $mAttributeRow->Price; 
						$mPriceDisp = $mPrice;
						if ($mPrice!='0' || $mPrice!='0.00') 
						{
							if ($mPrice<0) 
							{
								$mPriceMinus = $mPriceMinus + $mPrice;		  
								$mPriceDisp = " - Subtract ".$currency.$mPrice;
							} 
							else 
							{
								$mPricePlus = $mPricePlus + $mPrice;
								$mPriceDisp = " - Add ".$currency.$mPrice;
							}
						}
						else
						{
							$mPrice = ""; 
						}
						
						if ((trim($mPriceDisp)!="0") && (trim($mPriceDisp)!="$0"))
						{
							echo($mPriceDisp.", ");
						}
						else
						{
							echo(", ");
						}
					
						
						if (($mLimit>0) && ($mLimitPrice>0) && ($mLimitCount>$mLimit))
						{
							if (str_replace("|", "", $mPrice)<0) 
							{
								$mPriceMinus = $mPriceMinus + $mLimitPrice;
							}
							else
							{
								$mPricePlus = $mPricePlus + $mLimitPrice;
							}
							
						}
						
						$Tot_atrib_price_Plus = $mPricePlus;
						$Tot_atrib_price_mines = $mPriceMinus;
					}
					
					echo("</td>");
                  	echo("<td>&nbsp;</td>");
                	echo("</tr>");
				}
			   	else if ($Ord_RS2['extra'])  //Start Exra Row
			   	{ 
			   	?>
           	   	<tr> 
               		<td colspan="3"> 
                    <?php 
				 		$Tot_atrib_price_Plus=0;
						$Tot_atrib_price_mines=0;
						$TemOptNme="";
						$OptType=0;
					?>
                    <?php 
						$attribueArray=explode("~", $Ord_RS2['extra'])
					?>
                    <?php 
						for( $t=0;$t <count($attribueArray);$t++)
						{
							$attribueArrayString=str_replace("|","~",$attribueArray[$t]);
						 	$ATrOptions= explode("~", $attribueArrayString);
							if(!empty($ATrOptions[0]))
							{
								$Atribut_Opt=trim($ATrOptions[0]);
								$Atribut_Opt=str_replace("^",'"',$Atribut_Opt);
								$Atribut_Opt=str_replace("'",'"',$Atribut_Opt);
								$OptionNameQry=dbAbstract::Execute("select option_name,Type from attribute where ProductID=$ProductID AND TRIM(LOWER(Title))='".trim(strtolower($Atribut_Opt))."'", 1);
								$OptonNameRs=dbAbstract::returnRow($OptionNameQry, 1);								
								
								if($OptonNameRs[1]==2)
								{  
									if($OptonNameRs[0]!=$TemOptNme)
									{ 
										if($OptType==2)
										{
											echo "<BR>";					
										}
										$Option_Name=str_replace("Select","",str_replace("select","",$OptonNameRs[0]));
										echo "<strong>".$Option_Name." "."</strong>";
										$flagcoma=1;
										$TemOptNme=$OptonNameRs[0];					 
									}
									
									echo $ATrOptions[0];
									
									if(!empty($ATrOptions[1]))
									{
										$price =  $ATrOptions[1]; 
										$price1 =   $ATrOptions[1];
										if ($price!='0' || $price!='0.00') 
										{
											$decider = "";
											if ($price<0) 
											{
												$decider = "Subtract";
												$price1	= trim(substr($price1,1)); 		  
												$Tot_atrib_price_mines=$Tot_atrib_price_mines + $price;		  
											} 
											else 
											{
												$Tot_atrib_price_Plus=$Tot_atrib_price_Plus + $price;
												$decider = "Add";
											}
											$price = "|".$price; 
											$price1 = " - $decider ".$currency .$price1; 
														
										} 
										else 
										{
											$price = ""; 
											$price1 = "~"; 
										}
//--------------------------------------------------------------------Start NK(23-09-2014)------------------------------------------------------------                                                                                
//										echo $price1.", ";
//                                                                                echo '12345 --- ';print_r($price1);exit;
                                                                                if(!isset($attribueArray[$t+1])){
                                                                                echo $price1. " ";}else{echo $price1. ", ";}
//--------------------------------------------------------------------End NK(23-09-2014)--------------------------------------------------------------                                                                                
									}
									else
									{
//--------------------------------------------------------------------Start NK(23-09-2014)------------------------------------------------------------                                                                                
//										echo ", ";
                                                                            if(!isset($attribueArray[$t+1])){
                                                                            echo " ";}else{echo ", ";}
//--------------------------------------------------------------------End NK(23-09-2014)--------------------------------------------------------------
									}
								
									
																							
									$OptType=$OptonNameRs[1];
								}
								else
								{
									echo"<br/>";
									$Option_Name=str_replace("Select","",str_replace("select","",$OptonNameRs[0]));
									echo "<strong>".$Option_Name." "."</strong>";
									$AttbuteShow=str_replace("^",'"',$ATrOptions[0]);
									echo $AttbuteShow;
									if(!empty($ATrOptions[1]))
									{
										$price = $ATrOptions[1];		  
										$price1 =   $ATrOptions[1];
										if ($price!='0' || $price!='0.00') 
										{
											$decider = "";
											if ($price<0) 
											{
												$decider = "Subtract";
												$price1	= trim(substr($price1,1)); 
												$Tot_atrib_price_mines=$Tot_atrib_price_mines + $price;
											} 
											else 
											{
												$Tot_atrib_price_Plus=$Tot_atrib_price_Plus + $price;
												$decider = "Add";
											}
														
											$price = "|".$price; 
											$price1 = " - $decider ".$currency .$price1; 
										} 
										else 
										{
											$price = ""; 
											$price1 = "~"; 
										}
										echo $price1.", ";
									}
									else
									{
										echo(", ");
									}			
								}
							}
						}  
					?>
					</td>
                  	<td>&nbsp;</td>
                </tr>
                <?php 
				} //End Exra Row
				?>
                <tr>
                	<td colspan="3">
                    
                    <? if($Ord_RS2['associations']) {?>
					<strong>Associated Items:</strong>
					<?   
                       	echo str_replace('|','- add '.$currency,$Ord_RS2['associations']);
                   ?>
                    <? } ?>
                    </td>                
                </tr>
                <tr> 
                	<? 
						$assocItemArr = explode("~",$Ord_RS2['associations']);
						$assocTotalPrice = 0;
						for($j=0; $j<count($assocItemArr); $j++) {
							$assocOptions= explode("|", $assocItemArr[$j]);
							 
							$assocPrice = (count($assocOptions)>1 ? $assocOptions[1]:0);
							$assocTotalPrice = $assocTotalPrice + $assocPrice; 
							}
					?>
                  <td colspan="3">
                  <?
				  	$mQuantity = 1;
					if (isset($Ord_RS2["quantity"]))
					{
						if (is_numeric($Ord_RS2["quantity"]))
						{
							$mQuantity = $Ord_RS2["quantity"];
						}
					}
				  	$itemTotalPrice = $mQuantity * ($cart_price + $Tot_atrib_price_Plus + $Tot_atrib_price_mines + $assocTotalPrice);
				  	 $Tot_atrib_price_Plus = 0;
				  ?>
                  <strong>Item Total Price:</strong> <?=$currency?><? echo $itemTotalPrice;?></td>
                  <td>&nbsp;</td>
                </tr>
                 <? 
					$TotalPlus_atribue= $cart_price+$Tot_atrib_price_Plus+$Tot_atrib_price_mines;?>
                <? $itemQuanTotel=$Ord_RS2['quantity']*$TotalPlus_atribue;?>
                <tr> 
                  <td colspan="3" class="style1"><HR width="100%" noShade SIZE=1></td>
                  <td>&nbsp;</td>
                </tr>
                 <? } // end of while
				 
				 if($Ord_RS["coupon_discount"] == ""){
				 	$coupon_discount = '0.00';
				 }else{
				 	$coupon_discount = number_format($Ord_RS["coupon_discount"], 2);
				 }
				 ?>
                <tr> 
                  <td colspan="3"><strong>Coupon Discount:</strong><?=$currency?><?=$coupon_discount ?></td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td colspan="3"><strong>Delivery:</strong><?=$currency?><? echo number_format($Ord_RS["delivery_chagres"],2);?> </td>
                  <td>&nbsp;</td>
                </tr>
                <? if($Ord_RS["Tax"]) {?>
                <tr> 
                  <td colspan="3" ><strong>Tax:</strong> <?=$currency?><? echo number_format($Ord_RS["Tax"],2);?></td>
                  <td>&nbsp;</td>
                </tr>
                <? }?>
				 <? if($Ord_RS["driver_tip"]) {?>
                <tr> 
                  <td colspan="3" ><strong>Driver Tip:</strong> <?=$currency?><? echo number_format($Ord_RS["driver_tip"],2);?></td>
                  <td>&nbsp;</td>
                </tr>
                <? }?>
                <? 
				//$maintotel = ($Ord_RS["Totel"] +  $Ord_RS["driver_tip"]) - $Ord_RS["coupon_discount"];
				$maintotel =  $Ord_RS["Totel"] ;
				?>
                <tr> 
                  <td colspan="3" class="style1"><strong>Total: </strong><?=$currency?><? echo number_format($maintotel,2);?></td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td colspan="3" class="style1"><HR width="100%" noShade SIZE=1></td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td width="73%" align="right"><!--<input type="button" name="Submit" value="Update Order" onclick="javascript:updateOrder('<? echo $Ord_RS["OrderID"]?>')">&nbsp;&nbsp;--></td>
                  <td><input type="button" name="Submit" value=" Print view " onClick="javascript:showPopReport('<? echo $Ord_RS["OrderID"]?>');"></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
          </form></td>
        </tr>
       <!-- <tr align="left" valign="top"> 
          <td>&nbsp;</td>
        </tr>-->
      </table>
</div>
      
</td>
