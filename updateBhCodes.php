<?php
require_once "includes/config.php";

$mResBH = dbAbstract::Execute("SELECT * FROM bh_items ORDER BY LENGTH(ItemName) DESC");
$bhItemArr = array();

while ($mRowBH = dbAbstract::returnObject($mResBH,1))
{
	$bhItemArr[$mRowBH->ItemName] = '@'.$mRowBH->ItemCode;
}

$rs = dbAbstract::Execute("SELECT prd_id,item_des FROM product");
while ($row = dbAbstract::returnObject($rs))
{
	$description='';
	$update = false;
	$mPrevItem = "";
	
	foreach($bhItemArr as $key => $val){
		if (strpos($row->item_des, $key)!==FALSE)
		{
			if ($mPrevItem!=$key)
			{
				$mPrevItem = $key;
				$description = str_replace($key, $val ,$row->item_des);
				$update = true;
				echo $key." : ".$val."<br>";
			}
		}
	}
	
	if($update){
		dbAbstract::Update("update product set item_des = '".$description."' where prd_id=".$row->prd_id);
		echo "Before<br>";
		echo $row->item_des."<br><br>";
		
		echo "After<br>";
		echo $description."<br>";
		
		echo "-------------------------------------------------------------------------------------<br>";
	}
}