<?
$resellerId = 0;
$and='';
$search_by=0;
$search_field='';
if(isset($_REQUEST['sch_button'])){

	$search_by = $_REQUEST['search_by'];
	$search_field = $_REQUEST['search_field'];
	$resellerId = $_REQUEST['reseller_opt'];
	
	
		if($search_by == 1){	$and = "and firstname like '%$search_field%'";}
		else if($search_by == 2){	$and = " and lastname like '%$search_field%'";	}
		else if($search_by == 3){	$and = " and email like '%$search_field%'";	}
		else if($search_by == 4){	$and = "and (REPLACE(REPLACE(REPLACE(REPLACE(phone,'(',  ''), ')', ''),'-',''),' ',''))  like '%$search_field%'";	}
		else if($search_by == 5){	$and = "and zip like '%$search_field%'";	}
	
	  if( $resellerId > 0 || $_SESSION['admin_type'] == 'store owner'){
	  
			$and_sch = ' and '.$and;
			
	  }else{
	  
			$and_sch = ' where '.$and;
	  } 

} //isset($_REQUEST['sch_button']
if (isset($_REQUEST['del_user_id'])) {
	$id = $_REQUEST['del_user_id'];
	dbAbstract::Delete("DELETE FROM users WHERE id=$id",1);
} else if (isset($_REQUEST['user_active_id'])){
	$id = $_REQUEST['user_active_id'];
	$admin_type = $_SESSION['admin_type'];
	dbAbstract::Update("UPDATE users SET  status='1', status_changed_by='".$admin_type."' WHERE id=$id",1);
	// Activate all resturants of all clients of the resellers...
	dbAbstract::Update("UPDATE resturants SET  status='1', status_changed_by='".$admin_type."' WHERE owner_id = '".$id."' ",1);
	dbAbstract::Update("UPDATE analytics SET  status='1' WHERE resturant_id in (SELECT id as resturant_id from resturants WHERE owner_id = '".$id."') ",1);



} else if (isset($_REQUEST['user_deactive_id'])){
	$id = $_REQUEST['user_deactive_id'];
	$admin_type = $_SESSION['admin_type'];
	dbAbstract::Update( "UPDATE users SET  status='0', status_changed_by='".$admin_type."'  WHERE id=$id",1 );
	// Deactivate all resturants of all clients of the resellers...
	dbAbstract::Update("UPDATE resturants SET  status='0', status_changed_by='".$admin_type."' WHERE owner_id = '".$id."' ",1);
	dbAbstract::Update("UPDATE analytics SET  status='0' WHERE resturant_id in (SELECT id as resturant_id from resturants WHERE owner_id = '".$id."') ",1);

} 
//==========================================================================================

if( $resellerId == 0 ) 
	$resellerId	=   @$_REQUEST['resellerId'];
	
if($_SESSION['admin_type'] == 'admin') {
 
  if( $resellerId > 0)  {
		// resellers_client() will get all client of specific reseller
		$ids = resellers_client( $resellerId );
		$clientQry	=	"select * from users WHERE type = 'store owner' AND id IN ( $ids ) ";
	
	} else {  
		$clientQry	=	"select * from users WHERE type = 'store owner' ";
	}
		  
} else if ($_SESSION['admin_type'] == 'reseller' ){
	$resellerId = $_SESSION['owner_id'];
	$ids = resellers_client( $resellerId );
	$clientQry	=	"select * from users WHERE type = 'store owner' AND id IN ( $ids ) ";
}

 
$clientQry1 = dbAbstract::Insert("$clientQry $and",1);
$clientRows = dbAbstract::returnRowsCount($clientQry1,1);			
$counter = 0;
?>

<script language="javascript1.1">
function searchRestByUser(){
	var val1 = document.searchRestByUserFrm.reseller_opt.value;
	location = "?mod=clients&resellerId="+val1;
}

</script>

<div id="main_heading">

 <? if($_SESSION['admin_type'] == 'admin') {?>
  <form id="searchRestByUserFrm" name="searchRestByUserFrm" method="post" action="?mod=clients">
    <select name="reseller_opt" id="reseller_opt" style="font-size:20px;" onchange="javascript:searchRestByUser()" >
      <option value="0">=== All Resellers ===</option>
      <?=resellers_drop_down($resellerId) ?>
    </select>
    <select name="search_by" id="search_by" style="font-size:20px;" onchange="" >
      <option value="0">===Search By===</option>
       <option value="1" <? if($search_by == 1){?> selected="selected"<? }?>>By First Name</option>
       <option value="2" <? if($search_by == 2){?> selected="selected"<? }?>>By Last Name</option>
       <option value="3" <? if($search_by == 3){?> selected="selected"<? }?>>By Email</option>
       <option value="4" <? if($search_by == 4){?> selected="selected"<? }?>>By Phone</option>
       <option value="5" <? if($search_by == 5){?> selected="selected"<? }?>>By Zip</option>
    </select>
    <input name="search_field" style="font-size:20px;" value="<?=$search_field?>" type="text" />
    <label>
    <input type="submit" name="sch_button" id="sch_button" value="Submit" class="btn"/>
    </label>
   </form>
  <? } else if ( $_SESSION['admin_type'] == 'reseller' ) { ?>
 	<span>Clients List</span>
  <? }?>
</div>

<? if ( $clientRows > 0) {?>
<table class="listig_table" width="100%" border="0" align="center" cellspacing="1">
    <tr bgcolor="#729338">
      <th width="113"><strong>Name</strong></th>
      <th width="132"><strong>User Name</strong></th>
      <th width="175"><strong>Email</strong></th>
      <th width="75"><strong>Phone</strong></th>
      <th width="119"><strong>State/Province</strong></th>
      <th width="119"><strong>City</strong></th>
      <th width="119"><strong>Zip/Postal</strong></th>
      <th width="300"><strong>Action</strong></th>
    </tr>
    <? while($clientRs	=	dbAbstract::returnObject($clientQry1,1)){
		$number_rest_per_client = get_number_of_resturants( $clientRs->id );
	 ?>

   <?  if( $counter++ % 2 == 0)  
   			$bgcolor = '#F8F8F8';
	   else $bgcolor = '';
   ?>
    <tr bgcolor="<?=$bgcolor ?>" >
     
      <td><?=$clientRs->firstname.", ".$clientRs->lastname?></td>
      <td><?=$clientRs->username?></td>
      <td><?=$clientRs->email?></td>
      <td><?=$clientRs->phone?></td>
      <td><?=$clientRs->state?></td>
      <td><?=$clientRs->city?></td>
      <td><?=$clientRs->zip?></td>
      <td>  
		  <?php if( ( $_SESSION['admin_type'] == 'admin' && $clientRs->status == 0 ) ||  ( $_SESSION['admin_type'] == 'reseller' && $clientRs->status == 0 ) ) { ?>
              <a href="./?mod=<?=$mod?>&item=userstatus&user_active_id=<?=$clientRs->id?>&resellerId=<?=$resellerId?>" onclick="return confirm('Are you sure you want to change the status of this User')" style="text-decoration:underline;">Activate</a> 
          <?php }  else if( $_SESSION['admin_type'] == 'reseller' && $clientRs->status == 0 && $clientRs->status_changed_by == 'admin' ) { ?>	
          	   <span style="color:#FF0000">Deactivated by Supper Admin</span>
		  <?
          }
             
			    if($clientRs->status == 1) { 
           ?>
          <a href="./?mod=<?=$mod?>&item=userstatus&user_deactive_id=<?=$clientRs->id?>&resellerId=<?=$resellerId?>" onclick="return confirm('Are you sure you want to change the status of this User')" style="text-decoration:underline;">Deactivate</a>
          <?php } ?>
          &nbsp;&nbsp;<a href="./?mod=<?=$mod?>&item=useredit&userid=<?=$clientRs->id?>&resellerId=<?=$resellerId?>" style="text-decoration:underline;">Edit</a> 
          <!--&nbsp;&nbsp;<a href="./?mod=<?=$mod?>&item=userdell&del_user_id=<?=$clientRs->id ?>"  onclick="return confirm('Are you sure you want to Delete this User')" style="text-decoration:underline;">Delete</a> -->
          &nbsp;&nbsp;<a href="./?mod=resturant&client_id=<?=$clientRs->id ?>"  style="text-decoration:underline;">Resturants </a> (<?=$number_rest_per_client?>)
      </td>
      
    </tr>
    <? }?>
    <tr>
      <td colspan="7">&nbsp;</td>
    </tr>
  </table>
  <div style="clear:both"></div>
<? } else { ?>
		<strong>No results found, please try again.</strong>
<? }?>
