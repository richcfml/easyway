<?
	$order_id	=	$_REQUEST['oid'];
	$flag = @$_REQUEST['f'];
	$order_detailQry	=	dbAbstract::Execute("select * from orderdetails where orderid = $order_id",1);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<div id="main_heading">ORDER DETAIL</div>
<div class="form_outer">
<table width="100%" border="0" class="listig_table">
  <tr align="center" bgcolor="#729338">
    <th><strong>Item Name</strong></th>
    <th><strong>Quantity</strong></th>
    <th><strong>Request Notes</strong></th>
    <th><strong>Associated Items</strong></td>
    <th><strong>Extra</strong></td>
  </tr>
  <? while($order_detailRs = dbAbstract::returnObject($order_detailQry,1)){?>
   <tr align="center" bgcolor="#F8F8F8">
    <td><?=stripslashes(stripcslashes(product_name($order_detailRs->pid)))?></td>
    <td><?=$order_detailRs->quantity?></td>
   
    <td><?=stripslashes($order_detailRs->RequestNote)?></td>
    <td>
    <? if($order_detailRs->associations) {?>
	  <? 
         $str_assoc = str_replace('~','<br />',stripslashes(stripcslashes($order_detailRs->associations)));
         echo str_replace('|','- add $',$str_assoc);
      ?>
    <? } ?>
       
    
    </td>
    <td><?=$order_detailRs->extra?></td>
  </tr>
    <? }?>
     <tr align="center">
     <td colspan="4">
     <? if ($flag == 'oers'){?>
     <a href="./?mod=order&item=report">Go Back</a>
     <? }else{?>
     <a href="./?mod=order&item=orderreport">Go Back</a>
     <? }?>
     
     </td>
   </tr>
</table>
</div>
</body>
</html>



       
       