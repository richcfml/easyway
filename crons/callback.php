<?php
	require_once("../includes/config.php");
	include_once("../includes/phaxio.php"); 
		
	$mysql_conn = mysql_connect("localhost","easywayordering","Yh56**ew!d") or die( mysql_error()."  cannot connect...");
	mysql_select_db("dev_easywayordering",$mysql_conn);
	
	if (isset($_GET["orderid"]))
	{
		$mFax = json_decode_ewo($_POST["fax"]);
		mysql_query("INSERT INTO tblDebug(Step, Value1, Value2) VALUES (1, 'CallBack', 'OrderID: ".$_GET["orderid"].", FaxID: ".$mFax["id"].", FaxStatus: ".$mFax["status"]."')");
		mysql_query("UPDATE ordertbl SET PhaxioCallBack=1 WHERE OrderID=".$_GET["orderid"]);
		
		$mObjFax=new EWOphaxio();
		$mFaxStatus = 0;
		$mFaxStatus = $mObjFax->CheckFaxStatus($mFax["id"]);
		$mObjFun=new clsFunctions();
		$mObjFun->posttoVCS($_GET["orderid"], $mFaxStatus, $mFax["id"]);
		mysql_query("UPDATE ordertbl SET PhoneCallCount=1 WHERE OrderID=".$_GET["orderid"]);
		unset($mObjFax);
		unset($mObjFun);
	}
	
	function json_decode_ewo($json)
   	{
		$comment = false;
		$out = '$x=';
		for ($i=0; $i<strlen($json); $i++)
		{
			if (!$comment)
			{
				if (($json[$i] == '{') || ($json[$i] == '['))
					$out .= ' array(';
				else if (($json[$i] == '}') || ($json[$i] == ']'))
					$out .= ')';
				else if ($json[$i] == ':')
					$out .= '=>';
				else
					$out .= $json[$i];
			}
			else
				$out .= $json[$i];
			if ($json[$i] == '"' && $json[($i-1)]!="\\")
				$comment = !$comment;
		}
		eval($out . ';');
		return $x;
	}
?>