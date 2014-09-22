<div id="main_heading">EDIT / REMOVE COUPONS</div>
<?
if(isset($_REQUEST['coupon_id'])) {
		mysql_query("DELETE FROM coupontbl where coupon_id = ".$_REQUEST['coupon_id']);
	}

  ?>
 
  <table width="100%" border="0"  cellpadding="4" cellspacing="0" class="listig_table">
     <tr>
      	<th width="12%"><strong>coupon code</strong></th>
        <th width="16%"><strong>Coupon title</strong></th>
        <th width="22%"><strong>Coupon date</strong></th>
        <th width="50%"><strong>Action</strong></th>
      </tr>
     <?
         $coupon_qry 	= mysql_query("select * from coupontbl where resturant_id=". $Objrestaurant->id ." order by coupon_date desc");
      ?>
       <? while(@$couponRs = mysql_fetch_object($coupon_qry)){?>
      <tr>
      	<td><?=stripslashes($couponRs->coupon_code);?></td>
        <td><?=stripslashes($couponRs->coupon_title);?></td>
        <td><?=stripslashes($couponRs->coupon_date);?></td>
        <td><a href="?mod=coupon&item=edit&coupon_id=<?=$couponRs->coupon_id?>">Edit</a> | <a href="?mod=coupon&item=delete&coupon_id=<?=$couponRs->coupon_id?>" onClick="return confirm('Are you sure you would like to delete this coupon?')">Delete</a></td>
        <? }?>
      </tr>
  </table>

