<?          
if ($Objrestaurant->id==141)
{
	$orderquery=mysql_query("select o.*, OrderDate  OrderDate,c.cust_your_name, c.LastName from customer_registration c,ordertbl o where o.UserID=c.id and Approve=0  AND payment_approv=1 AND o.cat_id = ". $Objrestaurant->id ." ORDER BY o.OrderID DESC LIMIT 20");
}
else
{
	$orderquery=mysql_query("select o.*, OrderDate  OrderDate,c.cust_your_name, c.LastName from customer_registration c,ordertbl o where o.UserID=c.id and Approve=0  AND payment_approv=1 AND o.cat_id = ". $Objrestaurant->id ." ORDER BY o.OrderID DESC");
}
@$numrows=mysql_num_rows($orderquery);

 	
	$restaurant_name= $Objrestaurant->name;
	
$checked="";

		if(isset($_REQUEST['submit']))
			{
				$OID=@$_REQUEST['OID'];
				for ($i=0;$i<count($OID);$i++)
					{
                                                Log::write("Update Order status in ordertbl - tab_order_approve.php", "QUERY -- UPDATE ordertbl SET Approve= 1 WHERE OrderID=$OID[$i]", 'order', 1 , 'cpanel');
						mysql_query("UPDATE ordertbl SET Approve= 1 WHERE OrderID=$OID[$i]");
					}
				?>
<script language="javascript">
					window.location="./?mod=order&item=main&cid=<?=$mRestaurantIDCP?>";
				</script>
<? } ?>

<div id="main_heading"> APPROVE NEW ORDERS</div>
<script language="JavaScript">
function SelectAllAprove(){
  var objForm = document.forms[0];
  var el = document.getElementsByName('OID[]')
  for(i=0;i<el.length;i++){
      if(el[i].checked){
	  el[i].checked=false;
	  }else{
	  el[i].checked=true;
	  }   
  }
}
</script>
<? if($numrows>0){ ?>
<form name="form1" action="" method="post">
  <table width="100%" cellpadding="4" cellspacing="0" class="listig_table">
    <tr >
      <th width="27" style="text-align:right">&nbsp;</th>
      <th width="34"><strong>#</strong></th>
      <th width="100"><strong>Resturant Name</strong></th>
      <th width="66"><strong>Date Placed</strong></th>
      <th width="100"><strong>Customer Name</strong></th>
      <th width="494" style="text-align:center"><strong><br />
        Order Detail</strong>
        <table width="100%" class="tbl_small">
          <tr>
            <th width="22%" >Item Title</th>
            <th width="31%" >Item Detail</th>
            <th width="17%">Quantity</th>
            <th width="26%"style="text-align:right" >Item Price&nbsp;</th>
          </tr>
        </table>
        <br />
        <p><strong> </strong></p></th>
    </tr>
    <?
	 while($orderRs=mysql_fetch_array($orderquery)){	 
       ?>
    <!-- test code starts -->
    <?	   
	   $OrderID = @$orderRs["OrderID"];
// 
	    ?>
    <!-- test code ends-->
    <tr>
      <td><input type="checkbox" name="OID[]" value="<? echo $orderRs["OrderID"]?>" <? echo $checked?> /></td>
      <? $substr = substr($orderRs["OrderDate"],0,10);?>
      <td><a href="./?mod=order&item=detail&OrderID=<? echo $orderRs["OrderID"];?>"><? echo $orderRs["OrderID"];?></a></td>
      <td><? 
			echo $Objrestaurant->name;
	      ?>
      </td>
      <td><? echo $orderRs["OrderDate"]; ?></td>
      <td><? echo trim($orderRs["cust_your_name"].' '.$orderRs["LastName"]);?> </td>
      <td>
      <table width="100%" class="tbl_small">
        <?  $prdQuery2 ="select * from orderdetails where orderid = $OrderID";
			     $GrandTotal=0;
				 $prdQuery2= mysql_query($prdQuery2);
				 while($Ord_RS2=mysql_fetch_array($prdQuery2,MYSQL_BOTH)){
				 $ProductID= $Ord_RS2["prd_id"]?>
        <tr width="22%">
          <td width="22%" ><? echo stripslashes(stripcslashes($Ord_RS2["ItemName"]))?>
          <td><? if($Ord_RS2["extra"]) {?>
            <strong>Extras:</strong>
            <?
					$str_extra = str_replace('~','<br />',stripslashes(stripcslashes($Ord_RS2["extra"]))); 
					echo str_replace('|','- add '.$currency,$str_extra);
					?>
            <? } ?>
            <br />
            <? if($Ord_RS2["associations"]) {?>
            <strong>Associated Products:</strong>
            <? 
					   $str_assoc = str_replace('~','<br />',stripslashes(stripcslashes($Ord_RS2["associations"])));
					   echo str_replace('|','- add '.$currency,$str_assoc);
					?>
            <? } ?>
          </td>
        </td>
        <td width="18%"><? echo $Ord_RS2["quantity"]?></td>
          <td width="25%" style="text-align:right"><?
                      
                          $cart_price = $Ord_RS2['retail_price'];
                      ?>
            <?=$currency?><? echo $cart_price?> </td>
        </tr>
        <? } ?>
        <tr bgcolor="#FFFFCC">
          <td><strong>Total Price:</strong></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td  style="text-align:right"><strong><? echo $orderRs["Totel"]?></strong></td>
        </tr>
      </table>
      </td>
      <? if($orderRs["Approve"]==1){$checked="checked";}else{$checked="";}?>
    </tr>
    <? }?>
  </table>
  <div style="padding-top:10px;" >
    <input type="checkbox" name="checkbox" value="checkbox" onclick="SelectAllAprove();" />
    Check All / UnCheck All&nbsp;&nbsp;&nbsp;
    <input type="submit" name="submit" value="Approve Selected Orders" />
  </div>
</form>
<? }else{
		 if($search_numrows < 1) {
		?>
<div align="left">
<strong>No record found against this search.</strong>
<?
		 } else {
		?>
<div align="left"><strong>There are currently no new orders to review.</strong>
  <?
		 }
		?>
</div>
<? }?>
