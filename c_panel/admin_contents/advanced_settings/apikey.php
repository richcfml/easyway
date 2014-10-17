<?php
	$mAPIKey = "";
	$mUserID = $_SESSION['owner_id'];
	$mResult = mysql_query("SELECT IFNULL(ewo_api_key, '0') AS ewo_api_key FROM users WHERE id=".$mUserID);
	if (mysql_num_rows($mResult)>0)
	{
		$mRow = mysql_fetch_object($mResult);
		$mAPIKey = $mRow->ewo_api_key;
		if (($mAPIKey=="") || ($mAPIKey=="0"))
		{
			$mAPIKey = getUniqueAPIKey();
			mysql_query("UPDATE users SET ewo_api_key='".$mAPIKey."' WHERE id=".$mUserID);
		}
	}
	else
	{
		header("location:./?mod=resturant");
	}

	function getUniqueAPIKey()
	{
		$mAPIKey = substr(str_shuffle(str_repeat("23456789abcdefghijkmnpqrstuvwxyz", 10)), 0, 10);
		$mResult = mysql_query("SELECT COUNT(*) AS KeyCount FROM users WHERE ewo_api_key='".$mAPIKey."'");
		if (mysql_num_rows($mResult)>0)
		{
			$mRow = mysql_fetch_object($mResult);
			$mKeyCount = $mRow->KeyCount;
			if ($mKeyCount>0)
			{
				getUniqueAPIKey();
			}
			else
			{
				return $mAPIKey;
			}
		}
		else
		{
			header("location:./?mod=resturant");
		}
	}
	
?>
<div style="margin: 50px;">
	<span style="font-family: Arial, Helvetica, sans-serif; color: Maroon; font-weight: bold; font-size: 20px;">
		Your API Key is
	</span>
	<br />
	<span style="font-size: 16px;">
	<?=$mAPIKey?>
	</span>
</div>