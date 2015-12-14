<?php
if (isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]))
{
    $mSQL = "UPDATE grouporder SET FoodOrdered=1 WHERE CustomerID=".$_GET["grp_userid"]." AND GroupID=".$_GET["grpid"]." AND UserID=".$_GET["uid"];
    dbAbstract::Update($mSQL);
    redirect("?item=grouporderthankyou&grp_userid=".$_GET["grp_userid"]."&grpid=".$_GET["grpid"]."&uid=".$_GET["uid"]."&grp_keyvalue=".$_GET["grp_keyvalue"]);
	exit;
}
?>