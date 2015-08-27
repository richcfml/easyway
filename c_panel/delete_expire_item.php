<?php
require_once("../includes/config.php");

//delete expire item from product and attribute
$qry = dbAbstract::Execute("Select p.prd_id,bh.start_date,bh.end_date from product p inner join bh_signature_sandwitch bh on bh.id = p.signature_sandwitch_id");
while($product = dbAbstract::returnObject($qry))
{
    $end_date = $product->end_date;
    if($end_date < strtotime(date('Y-m-d')))
    {
        dbAbstract::Delete("Delete from product where prd_id =  ".$product->prd_id."");
        dbAbstract::Delete("Delete from attribute where ProductID = ".$product->prd_id."");
        dbAbstract::Delete("Delete from product_associationattribute w where product_id = ".$product->prd_id." or association_id = ".$product->prd_id."");
        
    }  
}
//delete expire item from signature sandwitch
$query = "Delete from bh_signature_sandwitch where end_date < ".strtotime(date('Y-m-d'))."";
dbAbstract::Delete($query);

//remove attributes where product not exist
dbAbstract::Delete("Delete from attribute where ProductID not in(select prd_id from product where signature_sandwitch_id != '')");





