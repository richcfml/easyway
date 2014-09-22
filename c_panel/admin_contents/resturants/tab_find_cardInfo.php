<?
include("../../../includes/config.php");

$ownerId=intval($_GET['ownerId']);
// get all client of specific reseller
$Card_sqlStr =  "SELECT Payment_profile_id,card_number FROM chargify_payment_method WHERE  user_id  = '".$ownerId."'";

$card_qry = mysql_query( $Card_sqlStr );

?>
<select name="credit_card_number" id="credit_card_number" style="width:270px;">
<option value="-1">Select Card</option>
<? while($result=mysql_fetch_array($card_qry)) { ?>
<option value=<?=$result['Payment_profile_id']?>><?=$result['card_number']?></option>
<? } ?>
</select>