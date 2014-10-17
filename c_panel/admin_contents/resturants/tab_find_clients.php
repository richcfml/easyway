<?
require_once("../../../includes/config.php");

$resellerId=intval($_GET['resellerId']);
if(isset($_GET['owner_name']))
{
    $value = $_GET['owner_name'];
}
// get all client of specific reseller
$reseller_client_sqlStr =  "SELECT client_id FROM reseller_client WHERE reseller_id  = '".$resellerId."' ";
$reseller_client_qry = mysql_query( $reseller_client_sqlStr );
$client_ids = "";
$i = 0;
while ( $reseller_client_rs = mysql_fetch_array ( $reseller_client_qry ) ) {
		   
	if( $i == 0) 
		$client_ids = $reseller_client_rs['client_id'];
	else 
		$client_ids .= " ,".$reseller_client_rs['client_id'];
	 
	$i++;
}
// this function will bring the only ids whic belongs to current reseller.
$client_sql	=	"select * from users WHERE type = 'store owner' AND status = 1 AND id IN ( $client_ids ) ";
$client_qry=mysql_query($client_sql);
if( $_GET['pageName'] == 'add_resturant' || $_GET['pageName'] == 'edit_resturant' ){
	$style = "width:270px;";
	$dropDownName = "owner_name";
	$optionDefaultCaption = "Select Restaurant Owner";
	$jsFunction = "";
} else if( $_GET['pageName'] == 'resturant_listing' ) {
	$style = "font-size:20px;";
	$dropDownName = "client_opt";
	$optionDefaultCaption = "=== All Client ===";
	//$jsFunction = 'onchange="javascript:searchRestByUser()"';
	$jsFunction = 'onChange="findresturants(this.value)"';
}

?>
<select name="<?=$dropDownName?>" id="<?=$dropDownName?>" style=" <?=$style?>" <?=$jsFunction?> onChange="getCardInfo(this.value)">
<option value="-1"><?=$optionDefaultCaption?></option>
<? while($result=mysql_fetch_array($client_qry)) { 
if($result['id']==$value){     ?>
<option value=<?=$result['id']?> selected><?=$result['firstname']." ".$result['lastname']?></option>
<? }else{?>
<option value=<?=$result['id']?>><?=$result['firstname']." ".$result['lastname']?></option><?
}
}?>
</select>