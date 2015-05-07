<?php
	require_once("../includes/config.php");
	include_once("../includes/phaxio.php"); 
		
	if (isset($_GET["orderid"]))
	{
		$mFax = json_decode_ewo($_POST["fax"]);
		mysql_query("UPDATE ordertbl SET PhaxioCallBack=1 WHERE OrderID=".$_GET["orderid"]);
		//Log::write("callback.php - Update Query - Line 11", "UPDATE ordertbl SET PhaxioCallBack=1 WHERE OrderID=".$_GET["orderid"], 'crons');
		$mObjFax=new EWOphaxio();
		$mFaxStatus = 0;
		$mFaxStatus = $mObjFax->CheckFaxStatus($mFax["id"]);
		$mObjFun=new clsFunctions();
		$mObjFun->posttoVCS($_GET["orderid"], $mFaxStatus, $mFax["id"]);
		mysql_query("UPDATE ordertbl SET PhoneCallCount=1 WHERE OrderID=".$_GET["orderid"]);
                //Log::write("callback.php - Update Query - Line 16", "UPDATE ordertbl SET PhoneCallCount=1 WHERE OrderID=".$_GET["orderid"], 'crons');
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