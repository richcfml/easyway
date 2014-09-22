<?
include("../../../includes/config.php");
$value="";
$resellerId=intval($_GET['resellerId']);
if(isset($_GET['license_id']))
{
    $value = $_GET['license_id'];
}
// get all client of specific reseller
$licenses_sqlStr =  "SELECT id, license_key FROM licenses WHERE status = 'unused' AND reseller_id  = '".$resellerId."' ";
$licenses_qry = mysql_query( $licenses_sqlStr );

$licensesChk_sqlStr =  "SELECT license_key FROM licenses WHERE status = 'unused' AND reseller_id  = '".$resellerId."' ";
$licensesChk_qry = mysql_query( $licensesChk_sqlStr );

?>
<select name="license_key" id="license_key" style="width:270px;">
<!---------------------------Start--------------------------------------------------------->      
<? if(mysql_num_rows($licensesChk_qry) == 0){ ?>
    <option value="-1">License key does not exists</option>
<? }else{ ?>
    <option value="-1">Select License Key</option>
<? } ?>
<!---------------------------End--------------------------------------------------------->      
<? while($result=mysql_fetch_array($licenses_qry)) { 
if($result['id']==$value){     ?>
<option value=<?=$result['id']?> selected><?=$result['license_key']?></option>
<? }else{?>
<option value=<?=$result['id']?>><?=$result['license_key']?></option><?
}
} ?>
</select>