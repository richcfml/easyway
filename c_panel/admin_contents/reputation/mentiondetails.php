<?php
include("../../../includes/config.php");
include_once("../../../includes/function.php");

if (isset($_GET["mentionid"]))
{
	if (isset($_GET["srid"]))
	{
		$mMentionID = $_GET["mentionid"];
		$mUrl = "https://reputation-intelligence-api.vendasta.com/api/v2/mention/get/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&mentionId=".$_GET["mentionid"]."&srid=".$_GET["srid"];
		$mCh = curl_init();
		curl_setopt($mCh, CURLOPT_URL, $mUrl);
		curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
	
		$mResult = curl_exec($mCh);
		curl_close($mCh);
		unset($mCh);
		$mResult = json_decode($mResult);
		$mResult = (object) $mResult;
		if (isset($mResult->data))
		{
			$mResult->data = (object)$mResult->data;
			$mData = $mResult->data;
			if (isset($mData->$_GET["mentionid"]))
			{
				$mMention = $mData->$_GET["mentionid"];
				$mMention = (object) $mMention;
			}
			else
			{
				echo("<script type='text/javascript' language='javascript'>$.facebox.close();</script>");				
			}
		}
		else
		{
			echo("<script type='text/javascript' language='javascript'>$.facebox.close();</script>");
		}
		//echo("<pre>");
		//print_r($mResult);
	}
	else
	{
		echo("<script type='text/javascript' language='javascript'>$.facebox.close();</script>");
	}
}
else
{
	echo("<script type='text/javascript' language='javascript'>$.facebox.close();</script>");
}
?>
<script type="text/javascript" language="javascript">
	$( document ).ready(function() 
	{
		$("#imgClose").click(function()
		{
			$.facebox.close();
		});
	});
</script>
<table style="width: 700px; font-size: 14px; background-color: #FFFFFF !important;" cellpadding="0" cellspacing="0" border="0">
	<tr style="height: 20px; background-color: #FFFFFF !important;">
		<td valign="top" colspan="5">
			<img src="../images/closelabel.gif" alt="Close" id="imgClose" style="cursor: pointer; cursor: hand; float: right; margin-right: 10px; margin-top: 10px;" />
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important;">
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 96%;" colspan="3">	
			<span style="font-weight: bold; font-size: 20px;"><?=$mMention->title?></span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important; height: 25px;">
		<td valign="top" colspan="5">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important;">
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 26%;">	
			<span style="font-weight: bold;">Published:</span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 68%;">
			<span><?=str_replace("Z", "", str_replace("T", " ", $mMention->publishedDateTime))?></span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important; height: 10px">
		<td valign="top" colspan="5">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important;">
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 26%;">	
			<span style="font-weight: bold;">Discovered</span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 68%;">
			<span><?=str_replace("Z", "", str_replace("T", " ", $mMention->discoveredDateTime))?></span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important; height: 10px;">
		<td valign="top" colspan="5">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important;">
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 26%;">	
			<span style="font-weight: bold;">Source:</span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 68%;">
			<span><?=$mMention->source?></span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important; height: 10px;">
		<td valign="top" colspan="5">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important;">
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 26%;">	
			<span style="font-weight: bold;">Sentiment Rank</span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 68%;">
			<span><?=$mMention->sentimentRank?></span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important; height: 10px;">
		<td valign="top" colspan="5">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important;">
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 26%;">	
			<span style="font-weight: bold;">Link</span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 68%;">
			<a href="<?=$mMention->permalink?>" target="_blank"><?=$mMention->permalink?></a>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important; height: 10px;">
		<td valign="top" colspan="5">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important;">
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 26%;">	
			<span style="font-weight: bold;">Content</span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
		<td valign="top" style="width: 68%;">
			<span><?=$mMention->content?></span>
		</td>
		<td valign="top" style="width: 2%;">
		</td>
	</tr>
	<tr style="background-color: #FFFFFF !important; height: 10px;">
		<td valign="top" colspan="5">
		</td>
	</tr>
</table>