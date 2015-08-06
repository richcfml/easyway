<? require_once("../../../includes/config.php");

					$s_date	=	@$_REQUEST['sd'];
					$e_date	=	@$_REQUEST['ed'];
					$cat_id	=	$_REQUEST['id'];
						
	if($s_date == '' && $e_date == ""){

			$firstcat_qry	=	dbAbstract::Execute("select * from categories where parent_id = 0 order by cat_name",1);	
			$firstcat_rs	=	dbAbstract::returnObject($firstcat_qry,1);
			$cat_id			=	$firstcat_rs->cat_id;
						
			$date_end = date("Y-m-d",time());
			list($year,$month,$day)	= explode("-",$date_end);
			$date_start = date("Y-m-d", mktime(0, 0, 0, $month,$day-6,$year));
			
		}else{
			
			$date_end = $e_date;
			$date_start = $s_date;
			
		}
		
	if ($cat_id == ""){
		$cat_id = 0 ;
	}	
	
		$report_qry	=	dbAbstract::Execute("select * from ordertbl where cat_id = $cat_id and OrderDate between '$date_start' and '$date_end'",1);
		$report_numrow	=	dbAbstract::returnRowsCount($report_qry,1);

			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>easywayordering</title>
<link href="../../../css/adminMain.css" rel="stylesheet" type="text/css">
</head>
<script language="javascript">
function populate1(){
		var val = document.form1.start_date.value;
		var val1 = document.form1.cat.value;
		window.location = "./?mod=order&item=report&sd="+val+"&id="+val1;
	}
	
	function populate2(){
		var val = document.form1.end_date.value;
		var val2 = document.form1.start_date.value;
		var val1 = document.form1.cat.value;
		window.location = "./?mod=order&item=report&sd="+val2+"&ed="+val+"&id="+val1;
	}

</script>
<body>
<h1 align="center">RESTAURANT ORDER REPORT</h1>

<table width="950" border="0" align="center">
  <tr bgcolor="#f8f8f8">
  <? 
  		$catinfo = $cat_info[$cat_id];
		$cat_name = $catinfo[0];
		$cat_tax =  $catinfo[1];
		$cat_report_dis = $catinfo[2];
  ?>
    <td colspan="7" align="center">Order Report of  "<strong><?=$cat_name?></strong>" From  <strong><?=$date_start?></strong>  To  <strong><?=$date_end?></strong></td>
  </tr>
  
  <tr>
    <td colspan="7" align="center">&nbsp;</td>
  </tr>
  <tr align="center"   bgcolor="#EED000">
    <td width="51"><strong>Order No.</strong></td>
     <td width="77"><strong>Order Date</strong></td>
     <td width="61"><strong>Item Code</strong></td>
    <td width="357"><strong>Item Name</strong></td>
    <!--<td width="96"><strong>Special Notes</strong></td>-->
    <td width="85"><strong>Extra's</strong></td>
    <td width="37"><strong>Qty</strong></td>
   <!--<td width="69"><strong>Customer Price</strong></td>-->
    <td width="79" ><strong>Restaurant Price</strong></td>
  </tr>
  <? 
  if($report_numrow >0){
	  $weekly_total = 0;
  while($report_qryRs = dbAbstract::returnObject($report_qry,1)){?>
  
   <? $Orderid = $report_qryRs->OrderID;
  
  $item_detailQry	=	dbAbstract::Execute("select od.*, 
							p.prd_id, 
							p.item_code,
							c.cust_desire_name, 
							c.cust_odr_address, 
							c.cust_ord_city, 
							c.cust_ord_state, 
							c.cust_phone1, 
							c.cust_phone2,
							od.quantity,
							od.OrdDetailID,
							ot.UserID,
							ot.driver_tip,
							od.extra,
							od.RequestNote,
							ot.cat_id
							from orderdetails od,ordertbl ot,product p,customer_registration c 
							where ot.OrderID = $Orderid 
							and od.orderid =ot.OrderID 
							and p.prd_id=od.pid 
							and item_code NOT IN(10000,10001,10002,10003,10004)
							AND c.id=ot.UserID");
		while($order_detailRs = dbAbstract::returnObject($item_detailQry,1)){
  ?>
  <tr align="center" bgcolor="#bgcolor="#F8F8F8"">
    <td><?=$report_qryRs->OrderID?></td>
     <? $order_date = date('m-d-Y',strtotime($report_qryRs->OrderDate));?>
    <td><?=$order_date?></td>
     <td><?=$order_detailRs->item_code?></td>
    <td><?=stripslashes($order_detailRs->ItemName)?>
      <!--<br />--><?
      		$extra = explode('~',$order_detailRs->extra);
			
	 	$cus_amount="";
	   			for($i=1; $i<count($extra); $i++){
				
					$extradetail = explode('|',$extra[$i]);

						$extradetail[1]."<br />";
						$cus_amount= $cus_amount+$extradetail[1];
						
				} 
				 
				 
		$e_amount	=	explode('#',$order_detailRs->od_rest_price);
		$e_total = "";
		for($j=1; $j<count($e_amount); $j++){
					$e_amount[$j]."<br />";
					$e_total = $e_total+$e_amount[$j];
		} 	 
	  ?></td>
      <!--<td><?=$order_detailRs->RequestNote?></td>-->
       <td width="85"><? $extra_detail = explode('~',$order_detailRs->extra);
		$e_amount_D	=	explode('#',$order_detailRs->od_rest_price);
	 	for($z=1; $z<count($extra_detail); $z++){
				$extradetail = explode('|',$extra[$z]);
						if($e_amount_D[$z] == "0"){$e_a = "";}else{$e_a = " ".$e_amount_D[$z];}		
				echo $extra_detaild= $extradetail[0].$e_a."<br />";
		 }
	   ?></td>
    <td><?=$order_detailRs->quantity?></td>
     <!--<td><? 
			//$order_detailRs->retail_price
			
			if($add_sub == " Add "){ 
				$cus_item_p = $order_detailRs->retail_price + $extra_last[0];
			} 
			if($add_sub == " Subtract "){ 
				$cus_item_p = $order_detailRs->retail_price - $extra_last[1];
			}
			if($add_sub == ""){ 
				$cus_item_p = $order_detailRs->retail_price;
			}
			$cus_item_price =  $cus_item_p*$order_detailRs->quantity;
			$cus_weekly_total	=	$cus_weekly_total+$cus_item_price;
			echo number_format($cus_item_price,2);
	?></td>-->
    <td ><?
	/*if($add_sub == " Add "){ 
		$item_p = $order_detailRs->rest_price + $extra_last[0];
	} 
	if($add_sub == " Subtract "){ 
		$item_p = $order_detailRs->rest_price - $extra_last[1];
	}
	if($add_sub == ""){ 
		$item_p = $order_detailRs->rest_price;
	}*/

	//echo $item_p = $order_detailRs->rest_price."<br />";
	
	$item_p = $order_detailRs->retail_price+$e_total;
    $rest_price = $item_p * $order_detailRs->quantity;
	$weekly_total	=	$weekly_total+$rest_price;
	//$cus_total	=	$cus_total+$cus_item_price;
	echo number_format($rest_price,2);
	
/*    $rest_price = $item_p * $order_detailRs->quantity;
	$weekly_total	=	$weekly_total+$rest_price;
	echo number_format($rest_price,2);
*/	
	
	?></td>
  </tr>
  <? } // end of while ?>
  <tr height="1">
  	<td colspan="7" height="1" bgcolor="#999999">
   <!--<hr align="center" width="72%" size="1" noshade>-->
   	</td>
   </tr>
 <?
 		
		
 } //End of outter while
 
				
		$discount_ammount	=	($weekly_total*$cat_report_dis)/100;
		$weekly_total_dis 	=	$weekly_total - $discount_ammount;
		$tax_amount			=	($weekly_total_dis*$cat_tax)/100;
		$g_total			=	$weekly_total_dis + $tax_amount;
		
		//_______________________   cus_weekly total ______________
		
		/*$cus_discount_ammount	=	($cus_weekly_total*$cat_report_dis)/100;
		$cus_weekly_total_dis 	=	$cus_weekly_total - $cus_discount_ammount;
		$cus_tax_amount			=	($cus_weekly_total_dis*$cat_tax)/100;
		$cus_g_total			=	$cus_weekly_total_dis + $cus_tax_amount;*/
		
  ?>
  <tr align="center" bgcolor="#f8f8f8">
    <td colspan="7">&nbsp;</td>
  </tr>
  <tr align="center" bgcolor="#f8f8f8">
    <td colspan="6" align="right"><strong>Sub-Total</strong></td>
    <!--<td ><?=number_format($cus_weekly_total,2)?></td>-->
    <td ><?=number_format($weekly_total,2)?></td>
  </tr>
  <tr align="center" bgcolor="#f8f8f8">
    <td colspan="7">&nbsp;</td>
  </tr>
  <tr align="center" bgcolor="#f8f8f8">
    <td colspan="6" align="right"><strong>Total</strong></td>
     <!--<td ><?=number_format($cus_g_total,2)?></td>-->
    <td ><?=number_format($g_total,2)?></td>
  </tr>
  <? } 
  
 
  ?>
   <tr >
    <td colspan="7">&nbsp;</td>
  </tr>
  <tr >
    <td colspan="7" align="center"><input type="button" name="print" id="print" value="Print Report" onclick="javascript:window.print()" /></td>
  </tr>
  
  <br />

</table>

</body>
</html>
