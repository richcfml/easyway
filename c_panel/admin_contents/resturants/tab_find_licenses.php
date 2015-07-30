<?
require_once("../../../includes/config.php");
$value="";
$resellerId=intval($_GET['resellerId']);
if(isset($_GET['license_id']))
{
    $value = $_GET['license_id'];
}
// get all client of specific reseller
$licenses_sqlStr =  "SELECT id, license_key FROM licenses WHERE status = 'unused' AND reseller_id  = '".$resellerId."' ";
$licenses_qry = dbAbstract::Execute($licenses_sqlStr,1);

$licensesChk_sqlStr =  "SELECT license_key FROM licenses WHERE status = 'unused' AND reseller_id  = '".$resellerId."' ";
$licensesChk_qry = dbAbstract::Execute($licensesChk_sqlStr,1);

?>
<select name="license_key" id="license_key" style="width:270px;">
<!---------------------------Start--------------------------------------------------------->      
<? if(dbAbstract::returnRowsCount($licensesChk_qry,1) == 0){ ?>
    <option value="-1">License key does not exists</option>
<? }else{ ?>
    <option value="-1">Select License Key</option>
<? } ?>
<!---------------------------End--------------------------------------------------------->      
<? while($result=dbAbstract::returnArray($licenses_qry,1)) { 
if($result['id']==$value){     ?>
<option value=<?=$result['id']?> selected><?=$result['license_key']?></option>
<? }else{?>
<option value=<?=$result['id']?>><?=$result['license_key']?></option><?
}
} ?>
</select>
<?php mysqli_close($mysqli);?>