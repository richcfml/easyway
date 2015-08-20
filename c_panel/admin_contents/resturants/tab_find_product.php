<?
require_once("../../../includes/config.php");
require("../../classes/ChargifyApi.php");

$chargify = new ChargifyApi();
$resellerId=intval($_GET['resellerId']);
if(isset($_GET['product_id']))
{
    $value = $_GET['product_id'];

}
// get all client of specific reseller
$product_sqlStr =  "SELECT product_id FROM chargify_products WHERE user_id  = ".$resellerId;
$product_qry = dbAbstract::Execute( $product_sqlStr,1 );

?>
<select name="product_details" id="product_details" style="width:270px;">
<option value="-1">Select Product</option>
<? while($result=dbAbstract::returnArray($product_qry,1)) {

$product = $chargify->getProductById($result['product_id']);
$price = number_format(round($product->price_in_cents/100,2),2);
$price = "$".$price;
if($result['product_id']==$value){
?>

<option value=<?=$result['product_id']?> selected title="<?=$product->description;?>"><?=$product->name.'     Price:  '.$price;?></option>
<? }else{?>
<option value=<?=$result['product_id']?> title="<?=$product->description;?>"><?=$product->name.'     Price:  '.$price;?></option><?
}
} ?>
</select>
