<?
if(isset( $_REQUEST['id'])) {
	$reseller_id = $_REQUEST['id'];
	
	if ($_REQUEST['action'] == 'del') {
	
		mysql_query("DELETE FROM users WHERE id=$reseller_id");
	
	} else if ($_REQUEST['action'] == 'activate'){
		
		mysql_query("UPDATE users SET  status='1', status_changed_by='admin' WHERE id=$reseller_id");
		$clients_ids = resellers_client( $reseller_id);
		// Activate all clients of this reseller...
		mysql_query("UPDATE users SET  status='1', status_changed_by='admin' WHERE id IN ( $clients_ids )");
		// Activate all resturants of all clients of the resellers...
		mysql_query("UPDATE resturants SET  status='1', status_changed_by='admin' WHERE owner_id IN ( $clients_ids )");
		mysql_query("UPDATE analytics SET  status='1' where resturant_id in (SELECT id as resturant_id form resturants WHERE owner_id IN ( $clients_ids )");
	
	} else if ($_REQUEST['action'] == 'deactivate'){
		mysql_query("UPDATE users SET  status='0', status_changed_by='admin' WHERE id=$reseller_id");
		$clients_ids = resellers_client( $reseller_id);
		// Deactivate all clients of this reseller...
		mysql_query("UPDATE users SET  status='0', status_changed_by='admin' WHERE id IN ( $clients_ids )");
		
		// Deactivate all resturants of all clients of the resellers...
		mysql_query("UPDATE resturants SET  status='0', status_changed_by='admin' WHERE owner_id IN ( $clients_ids )");
		mysql_query("UPDATE analytics SET  status='0' WHERE resturant_id in (SELECT id as resturant_id from resturants WHERE owner_id IN ( $clients_ids ))");

	}
} 

$resellerQry	=	mysql_query("select * from users WHERE type = 'reseller'");
$resellerRows	=	mysql_num_rows($resellerQry);
$counter = 0;
?>
<div id="main_heading">
<span>Resellers List</span>
</div>
  <table class="listig_table" width="100%" border="0" align="center" cellspacing="1">
    <tr bgcolor="#729338">
      <th width="150"><strong>Name</strong></th>
      <th width="100"><strong>User Name</strong></th>
      <th width="165"><strong>Email</strong></th>
      <th width="84"><strong>State/Prov.</strong></th>
      <th width="80"><strong>City</strong></th>
      <th width="76"><strong>Zip/Postal</strong></th>
      <th width="130"><strong>Company Name</strong></th>
      <th width="80"><strong>Number of Licenses</strong></th>
      <th width="320"><strong>Action</strong></th>
    </tr>
    <? while($resellerRs	=	mysql_fetch_object($resellerQry)){ ?>
   <?  if( $counter++ % 2 == 0)  
   			$bgcolor = '#F8F8F8';
	   else $bgcolor = '';
   ?>
    <tr bgcolor="<?=$bgcolor ?>" >
     
      <td><?=$resellerRs->firstname.", ".$resellerRs->lastname?></td>
      <td><?=$resellerRs->username?></td>
      <td><?=$resellerRs->email?></td>
      <td><?=$resellerRs->state?></td>
      <td><?=$resellerRs->city?></td>
      <td><?=$resellerRs->zip?></td>
      <td><?=$resellerRs->company_name?></td>
      <td><?=$resellerRs->number_of_licenses?></td>
      <td>
          <?php if($resellerRs->status == 0) { ?>
              <a href="./?mod=<?=$mod?>&action=activate&id=<?=$resellerRs->id?>" onclick="return confirm('Are you sure you want to change the status of this Reseller')" style="text-decoration:underline;">Activate</a> 
          <?php } 
                if($resellerRs->status == 1) { 
           ?>
          <a href="./?mod=<?=$mod?>&action=deactivate&id=<?=$resellerRs->id?>" onclick="return confirm('Are you sure you want to change the status of this Reseller')" style="text-decoration:underline;">Deactivate</a>
          <?php } ?>
          &nbsp;&nbsp;<a href="./?mod=<?=$mod?>&item=edit&reseller_id=<?=$resellerRs->id ?>" style="text-decoration:underline;">Edit</a> 
          <!--&nbsp;&nbsp;<a href="./?mod=<?=$mod?>&action=del&id=<?=$resellerRs->id ?>"  onclick="return confirm('Are you sure you want to Delete this Reseller')" style="text-decoration:underline;">Delete</a> -->
          <? 
		  $number_of_clients	= get_number_of_clients( $resellerRs->id );
		  $number_of_licenses 	= get_number_of_licenses( $resellerRs->id );
		  ?>
          &nbsp;&nbsp;<a href="./?mod=clients&resellerId=<?=$resellerRs->id ?>" style="text-decoration:underline;">Clients</a>&nbsp;(<?=$number_of_clients?>)
          &nbsp;&nbsp;<a href="./?mod=resellers&item=profile&reseller_id=<?=$resellerRs->id ?>" style="text-decoration:underline;">Licenses</a>&nbsp;(<?=$number_of_licenses?>)
      </td>
      
    </tr>
    <? }?>
    <tr>
      <td colspan="9">&nbsp;</td>
    </tr>
  </table>
