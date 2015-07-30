<?
require_once("../../../includes/config.php");
 $reseller_id = (isset($_REQUEST['reseller_id']) ?$_REQUEST['reseller_id'] :$_SESSION['owner_id']);
 
	 
	$licenseQry		=	dbAbstract::Execute("select * from licenses WHERE reseller_id = $reseller_id",1);
	$total_licenses = dbAbstract::returnRowsCount( $licenseQry,1	);
	$active_licenses_Qry		=	dbAbstract::Execute("select * from licenses WHERE status = 'activated' AND reseller_id = $reseller_id",1);
	$total_active_licenses = dbAbstract::returnRowsCount( $active_licenses_Qry,1	);
	$suspended_licenses_Qry		=	dbAbstract::Execute("select * from licenses WHERE status = 'suspended' AND reseller_id = $reseller_id",1);
	$total_suspended_licenses = dbAbstract::returnRowsCount( $suspended_licenses_Qry	);
	$unused_licenses_Qry		=	dbAbstract::Execute("select * from licenses WHERE status = 'unused' AND reseller_id = $reseller_id",1);
	$total_unused_licenses = dbAbstract::returnRowsCount( $unused_licenses_Qry,1	);
 
 
$counter = 0;
?>
<link href="../../css/adminMain.css" rel="stylesheet" type="text/css" />
<body style="background-color:#FFFFFF">
<div id="main_heading">
<span>Licenses List</span>
</div>
  <table class="listig_table" width="100%" border="0" align="center" cellspacing="1">
    <tr >
      <td colspan="3">Total Number of Licenses: &nbsp;&nbsp;&nbsp;<?=$total_licenses?><br/>
      	   Active Licenses: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$total_active_licenses?><br/>
           Suspended Licenses:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$total_suspended_licenses?><br/>
           Unused Licenses: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$total_unused_licenses?><br/>
      </td>
    </tr>
    <tr>
    
    <tr bgcolor="#729338">
      <th width="113"><strong>License Key</strong></th>
      <th width="113"><strong>License status</strong></th>
    </tr>
    <? while($licenseRs	=	dbAbstract::returnObject($licenseQry,1)){ ?>
  	
   <?  if( $counter++ % 2 == 0)  $bgcolor = '#F8F8F8';
	   else $bgcolor = '';
   ?>
    <tr bgcolor="<?=$bgcolor ?>" >
      <td><?=$licenseRs->license_key?></td>
      <td><?=$licenseRs->status?></td>
    </tr>
    <? }?>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
</body>
<?php mysqli_close($mysqli);?>