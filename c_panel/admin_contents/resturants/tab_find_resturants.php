<?
require_once("../../../includes/config.php");
$clientId=$_REQUEST['clientId'];

$qry	= "select * from resturants where owner_id ='".$clientId."' ";
$cat_qry1 =dbAbstract::Execute("$qry  order by name",1);
$numrows1 = dbAbstract::returnRowsCount($cat_qry1,1);	

$i = 0;
$name_str = get_reseller_client_names($clientId);
?>
 <strong  style="font-size:18px;">&nbsp;<?=$name_str?></strong><br />
<?
if( $numrows1 > 0) {

	while($cat_qryRs = dbAbstract::returnObject($cat_qry1,1)){
					
		$cat_id			= 	dbAbstract::returnResult($cat_qry1,$i,"id",1);
		$cat_name		= 	dbAbstract::returnResult($cat_qry1,$i,"name",1);
		$url_name		= 	dbAbstract::returnResult($cat_qry1,$i,"url_name",1);
		$cat_image		= 	dbAbstract::returnResult($cat_qry1,$i,"optionl_logo",1);
		$cat_des		= 	dbAbstract::returnResult($cat_qry1,$i,"des",1);
		$cat_status		= 	dbAbstract::returnResult($cat_qry1,$i,"status",1);
		$status_changed_by		= 	dbAbstract::returnResult($cat_qry1,$i,"status_changed_by",1);
		$full_address	=	trim($cat_address.' '.$cat_zip);					
		if($cat_image == ''){ $cat_image = 'default_200_by_200.jpg'; }
		($i%2 == 0)?$row_bg='':$row_bg='bgcolor="#F8F8F8"';		
		?>
	  <div class="listbox" onMouseOver="this.style.backgroundColor='#FFC';" onMouseOut="this.style.backgroundColor='';">
		<div id="imagebox" > <img src="../images/logos_thumbnail/<?=$cat_image?>" border="0" width="80" height="50" /> </div>
        <div id="URL_Links">
		<div id="title"><a href="./?mod=resturant&item=restedit&cid=<?=$cat_id?>">
		  <?=stripslashes(stripcslashes($cat_name))?>
		  </a> </div>
          
		<br style="clear:both" >
		<?php if($_SESSION['admin_type'] == 'admin' || $_SESSION['admin_type'] == 'reseller') { ?>
		<div id="actions">
		  <div id="icons"><a href="./?mod=resturant&item=restedit&cid=<?=$cat_id?>">Edit</a></div>
		 
          <?php if( ( $_SESSION['admin_type'] == 'admin' && $cat_status == 0 ) ||  ( $_SESSION['admin_type'] == 'reseller' && $cat_status == 0 && $status_changed_by == 'reseller' ) ) { ?>
		  <div id="icons"><a href="./?mod=<?=$mod?>&item=reststatus&rest_activ_id=<?=$cat_id?>" onClick="return confirm('Are you sure you want to change the status of this resturant')">Activate</a></div>
		  <br style="clear:right;" />
		 
          <?php } else if($cat_status == 1) { ?>
		  <div id="icons" ><a href="./?mod=<?=$mod?>&item=reststatus&rest_deactive_id=<?=$cat_id?>" onClick="return confirm('Are you sure you want to change the status of this resturant')">Deactivate</a></div>
		  <br style="clear:right;" />
		  <? } ?>
		</div>
		<? }?>
		<div id="Site_URL">Site URL:&nbsp;<a href="<?php echo $SiteUrl.$url_name;?>/" target="_blank"><?php echo $SiteUrl.$url_name;?>/</a> </div>
        </div>  
	  </div>
	  <? 
		$i++;
	} ?>
   
<? } else { ?>
	<strong>There is no resturant under this Client.</strong>
<? }?>
<?php mysqli_close($mysqli);?>
