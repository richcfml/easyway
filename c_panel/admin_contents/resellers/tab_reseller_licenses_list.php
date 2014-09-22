<?
session_start();
?>
<link href="../../css/adminMain.css" rel="stylesheet" type="text/css" />
<?
include("../../../includes/config.php");
 $reseller_id = (isset($_REQUEST['reseller_id']) ?$_REQUEST['reseller_id'] :$_SESSION['owner_id']);
 
	 
	$licenseQry		=	mysql_query("select * from licenses WHERE reseller_id = $reseller_id");
	$total_licenses = mysql_num_rows( $licenseQry	);
	$active_licenses_Qry		=	mysql_query("select * from licenses WHERE status = 'activated' AND reseller_id = $reseller_id");
	$total_active_licenses = mysql_num_rows( $active_licenses_Qry	);
	$suspended_licenses_Qry		=	mysql_query("select * from licenses WHERE status = 'suspended' AND reseller_id = $reseller_id");
	$total_suspended_licenses = mysql_num_rows( $suspended_licenses_Qry	);
	$unused_licenses_Qry		=	mysql_query("select * from licenses WHERE status = 'unused' AND reseller_id = $reseller_id");
	$total_unused_licenses = mysql_num_rows( $unused_licenses_Qry	);
 
 
$counter = 0;
?>
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
    <? while($licenseRs	=	mysql_fetch_object($licenseQry)){ ?>
  	
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