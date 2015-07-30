<?php
$orderquery=  dbAbstract::Execute("select  o.OrderID,o.DesiredDeliveryDate,o.order_receiving_method,DATE_FORMAT(OrderDate,'%m/%d/%Y')OrderDate,c.cust_your_name, c.LastName from customer_registration c,ordertbl o where o.UserID=c.id and Approve=1  AND payment_approv=1 AND o.cat_id = ". $Objrestaurant->id ." ORDER BY o.OrderID DESC",1);
@$numrows=dbAbstract::returnRowsCount($orderquery,1);	
?>
<div id="main_heading">VIEW APPROVED ORDERS</div>
 
<? if($numrows>0){?>
  <table width="100%" cellpadding="4" cellspacing="0" class="listig_table">
    <tr >
      <th width="34"><strong>#</strong></th>
      <th width="66"><strong>Order Date</strong></th>
	   <th width="100"><strong>Delivery/Pickup</strong></th>
      <th width="100"><strong>Customer Name</strong></th>
    </tr>
    <? 		
	$counter=0;
	while($orderRs=dbAbstract::returnAssoc($orderquery,1)){	 
     $OrderID = @$orderRs["OrderID"];
	?>
    <!-- test code ends-->
    <tr>
      <? 
	  if($orderRs["order_receiving_method"]=="Pickup"){
	  $substr =$orderRs["OrderDate"];
	  }
	 else{ $substr = substr($orderRs["DesiredDeliveryDate"],0,10);}
	 ?>
      <td><a href="./?mod=order&item=detail&OrderID=<? echo $orderRs["OrderID"];?>"><? echo $orderRs["OrderID"];?></a></td>
      <td><? echo $substr; ?></td>
	    <td><? echo $orderRs["order_receiving_method"]; ?></td>
      <td><? echo trim($orderRs["cust_your_name"].' '.$orderRs["LastName"]);?> </td>
    </tr>
    <? }?>
  </table>
<? } else {
		?>
<div align="left"><strong>There are currently no new orders to review.</strong>
  <?
		 }
		?>
</div>
 
