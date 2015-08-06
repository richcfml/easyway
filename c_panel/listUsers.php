<?php
require_once("../includes/config.php");

?>


<strong>Restaurant Owner</strong>
<table>
    <th style="width: 154px;text-align: center;">ID</th>
    <th style="width: 154px;text-align: center;">First Name</th>
    <th style="width: 154px;text-align: center;">Last Name</th>
<?
$ids = '';
$sql = dbAbstract::Execute("Select id,firstname,lastname from users where type ='store owner' and chargify_customer_id is null",1);
while($getids = dbAbstract::returnAssoc($sql,1))
{
    $ids .= $getids['id'].',';?>
    <tr><td style="width: 154px;text-align: center;"><?=$getids['id']?></td>
    <td style="width: 154px;text-align: center;"><?=$getids['firstname']?></td>
    <td style="width: 154px;text-align: center;"><?=$getids['lastname']?></td>
    <tr><?

}
?>
    
</table>
<strong>Restaurant Owner Ids:</strong><?echo $ids?>

<div style="margin-top: 25px;">
<strong>Reseller Details</strong>
<table>
    <th style="width: 154px;text-align: center;">ID</th>
    <th style="width: 154px;text-align: center;">Name</th>
     <th style="width: 154px;text-align: center;">Last Name</th>
<?
$resellerids = '';
$sql = dbAbstract::Execute("Select id,firstname,lastname from users where type ='reseller' and chargify_customer_id is null",1);
while($reselleridsarray = dbAbstract::returnAssoc($sql,1))
{
    $resellerids .= $reselleridsarray['id'].',';
    ?>
    <tr> <td style="width: 154px;text-align: center;"><?=$reselleridsarray['id']?></td>
    <td style="width: 154px;text-align: center;"> <?=$reselleridsarray['firstname']?></td>
    <td style="width: 154px;text-align: center;"> <?=$reselleridsarray['lastname']?></td>
    </tr>
    <?

}
?>
   
</table>
<strong>Reseller Ids:</strong><?echo $resellerids?>

</div>
