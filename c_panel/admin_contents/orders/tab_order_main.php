<?
if(isset($_GET['item'])) $item = $_GET['item']; else $item = 'main';
 
if($item == "main") { $admin_subcontent = "admin_contents/orders/tab_orders_search.php"; } 
else if($item == "approve") { $admin_subcontent = "admin_contents/orders/tab_order_approve.php"; }
else if($item == "detail") { $admin_subcontent = "admin_contents/orders/tab_exist_order_detail.php"; }
else if($item == "orderdetail") { $admin_subcontent = "admin_contents/orders/tab_report_order_detail.php"; } 
else if($item == "report") { $admin_subcontent = "admin_contents/orders/tab_report.php"; } 
else if($item == "order_delete") { $admin_subcontent = "admin_contents/orders/tab_delete_order.php"; } 
else if($item == "editorder") { $admin_subcontent = "admin_contents/orders/tab_exist_order_detail.php"; }
else if($item == "refund") { $admin_subcontent = "admin_contents/orders/tab_refund.php"; }

?>

<div id="BodyContainer">
  <? include "includes/resturant_header.php";
 	$refundpassword=trim($Objrestaurant->refund_password);
 ?>
  
  <div id="tab_items">
    <ul>
      <li> <a href="?mod=<?=$mod?>&item=main&cid=<?=$mRestaurantIDCP?>" class="<?=$mod=='order' && ($item == 'main' || $item == 'editorder') ? 'selected_red' : ''?>">View Approved Orders</a> </li>
      |
      <li> <a href="?mod=<?=$mod?>&item=approve&cid=<?=$mRestaurantIDCP?>" class="<?=$mod == 'order' && $item == 'approve' ? 'selected_red' : ''?>">New Orders</a> </li>
      |
      <li> <a href="?mod=<?=$mod?>&item=report&cid=<?=$mRestaurantIDCP?>" class="<?=$mod == 'order' && $item == 'report' ? 'selected_red' : ''?>">Restaurant Order Report</a> </li>
      <li>
        <? if($refundpassword=='') { ?>
        <a href="javascript:void(0)" onclick="alert('Refund option not available, contact your reseller to set a refund password');">Refund Orders</a>
        <? }else { ?>
        <a href="?mod=<?=$mod?>&item=refund&cid=<?=$mRestaurantIDCP?>"  class="<?=$mod == 'refund' ? 'selected_red' : ''?>">Refund Orders</a>
        
        <? } ?>
      </li>
    </ul>
  </div>
  <?   include $admin_subcontent;?>
</div>
 