<link href="../css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox.js" type="text/javascript"></script>
<?php
	ini_set( "display_errors", "1" );
	error_reporting( E_ALL & ~E_NOTICE);
	include("nav.php");
	$mWeb = "";
	$mBlog = "";
	$mImages = "";
	$mType = "";
	$mTwitter = "";
	$mFacebook = "";
	$mNews = "";
	
	$mRank1 = "";
	$mRank2 = "";
	$mRank3 = "";
	$mRank4 = "";
	$mRank5 = "";
	
	$mSRID = $Objrestaurant->srid;	
	$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/mention/search/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&relevantFlag=true&pageSize=100";
	
	if (isset($_GET["type"]))
	{
		$mType = $_GET["type"];
		if ($_GET["type"]=="web")
		{
			$mWeb = " selected='selected' ";
		}
		else if ($_GET["type"]=="blog")
		{
			$mBlog = " selected='selected' ";
		}
		else if ($_GET["type"]=="news")
		{
			$mNews = " selected='selected' ";
		}
		else if ($_GET["type"]=="image")
		{
			$mImages = " selected='selected' ";
		}
		else if ($_GET["type"]=="twitter")
		{
			$mTwitter = " selected='selected' ";
		}
		else if ($_GET["type"]=="facebook")
		{
			$mFacebook = " selected='selected' ";
		}
		
		$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/mention/search/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&relevantFlag=true&type=".$mType."&pageSize=100";

	}
	else if (isset($_POST["btnSubmit"]))
	{
		$mStartDate = $_POST["txtStartDate"];
		$mEndDate = $_POST["txtEndDate"];
		$mSources = $_POST["ddlSources"];
		$mRank = $_POST["ddlRank"];
		
		if (trim($mStartDate)!="")
		{
			$mStartDate = strtotime($mStartDate);
			$mStartDate = date('Y-m-d',$mStartDate);
		}
		
		if (trim($mEndDate)!="")
		{
			$mEndDate = strtotime($mEndDate);
			$mEndDate = date('Y-m-d',$mEndDate);
		}
		
		if ((trim($mStartDate)!="") && (trim($mEndDate)!=""))
		{
			if ($mStartDate<$mEndDate)
			{
				$mURL .= "&startDateTime=".$mStartDate.'T12:00:00Z'."&endDateTime=".$mEndDate.'T11:59:59Z';
			}
		}
		else if (trim($mStartDate)!="")
		{
			$mURL .= "&startDateTime=".$mStartDate.'T12:00:00Z';
		}
		else if (trim($mEndDate)!="")
		{
			$mURL .= "&endDateTime=".$mEndDate.'T11:59:59Z';
		}
		
		if (trim($mSources)!="")
		{
			if (trim($mSources)=="web")
			{
				$mWeb = " selected='selected' ";
			}
			else if (trim($mSources)=="blog")
			{
				$mBlog = " selected='selected' ";
			}
			else if (trim($mSources)=="news")
			{
				$mNews = " selected='selected' ";
			}
			else if (trim($mSources)=="image")
			{
				$mImages = " selected='selected' ";
			}
			else if (trim($mSources)=="twitter")
			{
				$mTwitter = " selected='selected' ";
			}
			else if (trim($mSources)=="facebook")
			{
				$mFacebook = " selected='selected' ";
			}
			
			$mURL .= "&type=".$mSources;
		}
		
		if (trim($mRank)!="")
		{
			if (trim($mRank)=="1")
			{
				$mRank1 = " selected='selected' ";
			}
			else if (trim($mRank)=="2")
			{
				$mRank2 = " selected='selected' ";
			}
			else if (trim($mRank)=="3")
			{
				$mRank3 = " selected='selected' ";
			}
			else if (trim($mRank)=="4")
			{
				$mRank4 = " selected='selected' ";				
			}
			else if (trim($mRank)=="5")
			{
				$mRank5 = " selected='selected' ";				
			}
			
			$mURL .= "&minSentimentRank=".$mRank."&maxSentimentRank=".$mRank;
		}
		
	}
	
	$mCh = curl_init();
	curl_setopt($mCh, CURLOPT_URL, $mURL);
	curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
	
	$mResult = curl_exec($mCh);
	curl_close($mCh);
	unset($mCh);
	$mResult = json_decode($mResult);
	$mResult = (object) $mResult;
?>
<style type="text/css">
#tblMain tr
{
	background-color: #FFFFFF;
}

#tblMain a
{
    color: #3faae0;
    text-decoration: none;
    margin-right: 5px;
    font-size: 14px;
    margin-bottom: 4px;
}

#tblMain a:hover
{
    text-decoration: underline;
}
</style>
<link rel="stylesheet" type="text/css" media="all" href="js/calendar/jsDatePick_ltr.min.css" />
<script type="text/javascript" src="js/calendar/jsDatePick.min.1.3.js"></script>
<script type="text/javascript" language="javascript">
	$( document ).ready(function() 
	{
		$(".lnkMore").live('click', function()
		{
			var mUrl = '';
			var mRandom = Math.floor((Math.random()*1000000)+1); 
			var loopCount = $(this).attr("loopCount");
			var type = $(this).attr("type");
			var processing = $(this).attr("processing");
			var myId = $(this).attr("id");
			
			$("#"+processing).show();
			mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=loadMore&rndm="+mRandom+"&loopCount="+loopCount+"&type="+type;
			$.ajax
			({
				url: mUrl,
				type:'POST',
				data: {'nextUrl': $("#txtLoadMore").val()}, 
				success: function(data)
				{
					var dataTmp = data.split("|||");
					$("#dvMain").append(dataTmp[0]);
					$("#txtLoadMore").val(dataTmp[1]);
					$("#"+processing).hide();
					$("#"+myId).hide();
				},
				error:function(data)
				{
					alert('Error occurred.');
				}
			});
		});
		
		$(".minus").live('click', function()
		{
			var mMyID = $(this).attr("id");
			var mOtherImage = $(this).attr("otherImg");
			var mMentionID = $(this).attr("mentionid");
			var mImageID = $(this).attr("imageid");
			var mRank = $(this).attr("rank");
			var mProcessing = $(this).attr("processingImg");
			$("#"+mProcessing).show();
			if (mRank>1)
			{
				mRank--;
			
				var mUrl = '';
				var mRandom = Math.floor((Math.random()*1000000)+1); 
				
				mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=setrank&mentionid="+mMentionID+"&rank="+mRank+"&srid=<?=$mSRID?>&rndm="+mRandom;				
				
				$.ajax
				({
					url: mUrl,
					type:'GET',
					success: function(data)
					{
						//if ($.isNumeric(data))
						//{
							if (data>0)
							{
								if (data==1)
								{
									$("#"+mImageID).attr("src", "images/neg1.jpg");
								}
								else if (data==2)
								{
									$("#"+mImageID).attr("src", "images/neg2.jpg");
								}
								else if (data==3)
								{
									$("#"+mImageID).attr("src", "images/neu.jpg");
								}
								else if (data==4)
								{
									$("#"+mImageID).attr("src", "images/pos1.jpg");
								}
								else if (data==5)
								{
									$("#"+mImageID).attr("src", "images/pos2.jpg");
								}
								$("#"+mMyID).attr("rank", data);
								$("#"+mOtherImage).attr("rank", data);
							}
							else
							{
								alert("Error occurred. Please try again later.");
							}
						//}
						//else
						//{
						//	alert("Error occurred. Please try again later.");
						//}
						$("#"+mProcessing).hide();
					},
					error:function(data)
					{
						alert("Error occurred. Please try again later.");
						$("#"+mProcessing).hide();
					}
				});
			}
		});
		
		$(".plus").live('click', function()
		{
			var mMyID = $(this).attr("id");
			var mOtherImage = $(this).attr("otherImg");
			var mMentionID = $(this).attr("mentionid");
			var mImageID = $(this).attr("imageid");
			var mRank = $(this).attr("rank");
			var mProcessing = $(this).attr("processingImg");
			$("#"+mProcessing).show();
			
			if (mRank<5)
			{
				mRank++;
			
				var mUrl = '';
				var mRandom = Math.floor((Math.random()*1000000)+1); 
				
				mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=setrank&mentionid="+mMentionID+"&rank="+mRank+"&srid=<?=$mSRID?>&rndm="+mRandom;
				$.ajax
				({
					url: mUrl,
					type:'GET',
					success: function(data)
					{
						//if ($.isNumeric(data))
						//{
							if (data>0)
							{
								if (data==1)
								{
									$("#"+mImageID).attr("src", "images/neg1.jpg");
								}
								else if (data==2)
								{
									$("#"+mImageID).attr("src", "images/neg2.jpg");
								}
								else if (data==3)
								{
									$("#"+mImageID).attr("src", "images/neu.jpg");
								}
								else if (data==4)
								{
									$("#"+mImageID).attr("src", "images/pos1.jpg");
								}
								else if (data==5)
								{
									$("#"+mImageID).attr("src", "images/pos2.jpg");
								}
								
								$("#"+mMyID).attr("rank", data);
								$("#"+mOtherImage).attr("rank", data);
							}
							else
							{
								alert("Error occurred. Please try again later.");
							}
							$("#"+mProcessing).hide();
						//}
						//else
						//{
						//	alert("Error occurred. Please try again later.");
						//}
					},
					error:function(data)
					{
						alert("Error occurred. Please try again later.");
						$("#"+mProcessing).hide();
					}
				});
			}
		});
		
		$(".Starred").live('click', function()
		{
			var mMyID = $(this).attr("id");
			var mStarred = $(this).attr("starred");
			var mMentionID = $(this).attr("mentionid");
			var mProcessing = $(this).attr("processing");
			$("#"+mProcessing).show();
			var mStar = 0;
			if (mStarred==0)
			{
				mStar = 1;
			}
			
			var mUrl = '';
			var mRandom = Math.floor((Math.random()*1000000)+1); 
			
			mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=togglestarred&mentionid="+mMentionID+"&star="+mStar+"&srid=<?=$mSRID?>&rndm="+mRandom;				
			$.ajax
			({
				url: mUrl,
				type:'GET',
				success: function(data)
				{
					//if ($.isNumeric(data))
					//{
						if ((data==0) || (data==1))
						{
							if (data==0)
							{
								$("#"+mMyID).attr("src", "images/star1.png");
							}
							else if (data==1)
							{
								$("#"+mMyID).attr("src", "images/star2.png");
							}
							
							$("#"+mMyID).attr("starred", data);
						}
						else
						{
							alert("Error occurred. Please try again later.");
						}
						$("#"+mProcessing).hide();
					//}
					//else
					//{
					//	alert("Error occurred. Please try again later.");
					//}
				},
				error:function(data)
				{
					alert("Error occurred. Please try again later.");
					$("#"+mProcessing).hide();
				}
			});
			
		});
		
		$(".my").live('click', function()
		{
			var mMyID = $(this).attr("id");
			var mMy = $(this).attr("my");
			var mMentionID = $(this).attr("mentionid");
			var mProcessing = $(this).attr("processing");
			$("#"+mProcessing).show();
			var mMine = 0;
			if (mMy==0)
			{
				mMine = 1;
			}
			
			var mUrl = '';
			var mRandom = Math.floor((Math.random()*1000000)+1); 
			
			mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=togglemine&mentionid="+mMentionID+"&my="+mMine+"&srid=<?=$mSRID?>&rndm="+mRandom;				
			$.ajax
			({
				url: mUrl,
				type:'GET',
				success: function(data)
				{
					//if ($.isNumeric(data))
					//{
						if ((data==0) || (data==1))
						{
							if (data==0)
							{
								$("#"+mMyID).attr("src", "images/my1.png");
							}
							else if (data==1)
							{
								$("#"+mMyID).attr("src", "images/my2.png");
							}
							
							$("#"+mMyID).attr("my", data);
						}
						else
						{
							alert("Error occurred. Please try again later.");
						}
						$("#"+mProcessing).hide();
					//}
					//else
					//{
					//	alert("Error occurred. Please try again later.");
					//}
				},
				error:function(data)
				{
					alert("Error occurred. Please try again later.");
					$("#"+mProcessing).hide();
				}
			});
			
		});
		
		$(".rel").live('click', function()
		{
			var mMyID = $(this).attr("id");
			var mRowID = $(this).attr("rowid");
			var mMentionID = $(this).attr("mentionid");
			var mProcessing = $(this).attr("processing");
			$("#"+mProcessing).show();
			
			var mUrl = '';
			var mRandom = Math.floor((Math.random()*1000000)+1); 
			
			mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=togglerelevant&mentionid="+mMentionID+"&srid=<?=$mSRID?>&rndm="+mRandom;				
			$.ajax
			({
				url: mUrl,
				type:'GET',
				success: function(data)
				{
					//if ($.isNumeric(data))
					//{
						if (data==1)
						{
							$("#"+mRowID).hide();
						}
						else
						{
							alert("Error occurred. Please try again later.");
						}
						$("#"+mProcessing).hide();
					//}
					//else
					//{
					//	alert("Error occurred. Please try again later.");
					//}
				},
				error:function(data)
				{
					alert("Error occurred. Please try again later.");
					$("#"+mProcessing).hide();
				}
			});
			
		});
		
		$(".lnkDet").live('click', function()
		{
			var mMentionID = $(this).attr("mentionid");
			$.facebox({div: 'admin_contents/reputation/mentiondetails.php?mentionid='+mMentionID+'&srid=<?=$mSRID?>'});  
			$('#facebox').css("position","absolute");
			$('#facebox').css("height","600px");
			$('#facebox').css("width","800px");
			$('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) - 100 + "px");
			$('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
		});
	});
									 

	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"txtStartDate",
			dateFormat:"%Y-%m-%d",
			/*selectedDate:{				This is an example of what the full configuration offers.
				day:5,						For full documentation about these settings please see the full version of the code.
				month:9,
				year:2006
			},*/
			yearsRange:[2012,2014]
			/*limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
		
		new JsDatePick({
			useMode:2,
			target:"txtEndDate",
			dateFormat:"%Y-%m-%d",
			/*selectedDate:{				This is an example of what the full configuration offers.
				day:5,						For full documentation about these settings please see the full version of the code.
				month:9,
				year:2006
			},*/
			yearsRange:[2012,2014]
			/*limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
	};
</script>
<form name="frmSearch" id="frmSearch" method="post" action="?mod=mentionsall&cid=<?=mRestaurantIDCP?>">
<div id="main_heading">
	<span>Mentions Search</span>
</div>
<div id="dvMain" style="border: 1px solid black;">
<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0" id="tblMain">
	<tr style="height: 10px;">
		<td></td>
	</tr>
	<tr style="background-color:#CCC !important;">
		<td style="width: 100%;">
			<table style="width: 100%; background-color:#CCC !important;" border="0" cellpadding="0" cellspacing="0" id="tblMain">
				<tr style="height: 15px; background-color:#CCC !important;">
					<td colspan="7">
					</td>
				</tr>
				<tr style="background-color:#CCC !important;">
					<td style="width: 2%;"></td>
					<td style="width: 20%;">
						<strong>Start Date</strong>&nbsp;<i>(YYYY-MM-DD)</i>
					</td>
					<td style="width: 20%;">
						<strong>End Date</strong>&nbsp;<i>(YYYY-MM-DD)</i>
					</td>
					<td style="width: 20%;">
						<strong>Sources</strong>
					</td>
					<td style="width: 20%;">
						<strong>Sentiment/Rank</strong>
					</td>
					<td style="width: 16%;">
					</td>
					<td style="width: 2%;"></td>
				</tr>
				<tr style="background-color:#CCC !important;">
					<td style="width: 2%;"></td>
					<td style="width: 20%;">
						<input type="text" id="txtStartDate" name="txtStartDate" size="24" value="<?php if (isset($_POST["txtStartDate"])) echo($_POST["txtStartDate"]);?>" />
					</td>
					<td style="width: 20%;">
						<input type="text" id="txtEndDate" name="txtEndDate" size="24" value="<?php if (isset($_POST["txtEndDate"])) echo($_POST["txtEndDate"]);?>"/>
					</td>
					<td style="width: 20%;">
						<select name="ddlSources" id="ddlSources">
							<option value=""></option>
							<option value="blog" <?=$mBlog?>>BLOG</option>
							<option value="news" <?=$mNews?>>NEWS</option>
							<option value="web" <?=$mWeb?>>Web</option>
							<option value="image" <?=$mImages?>>Images</option>
							<option value="twitter" <?=$mTwitter?>>Twitter</option>
							<option value="facebook" <?=$mFacebook?>>Facebook</option>
						</select>
					</td>
					<td style="width: 20%;">
						<select name="ddlRank" id="ddlRank" disabled="disabled">
							<option value=""></option>
							<option value="1" <?=$mRank1?>>Negative</option>
							<option value="2" <?=$mRank2?>>Somewhat Negative</option>
							<option value="3" <?=$mRank3?>>Neutral</option>
							<option value="4" <?=$mRank4?>>Somewhat Positive</option>
							<option value="5" <?=$mRank5?>>Positive</option>
						</select>
					</td>
					<td style="width: 16%;">
						<input type="submit" value="Search" id="btnSubmit" name="btnSubmit" style="height: 30px !important; width: 100px !important; background-color: #066 !important; color: #fff !important; font-weight: bold !important;"/>
					</td>
					<td style="width: 2%;"></td>
				</tr>
				<tr style="height: 15px; background-color:#CCC !important;">
					<td colspan="7">
						<input type="hidden" id="txtLoadMore" name="txtLoadMore" value="<?=$mResult->nextUrl?>" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	$loopCount = 0;
	if (count($mResult->data)>0)
	{
		foreach ($mResult->data as $mRow)
		{
			$mRow = (object) $mRow;
	?>
	<tr>
		<td style="width: 100%;">
			<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
				<tr style="height: 10px;">
					<td colspan="3"></td>
				</tr>
				<tr style="background-color: #FFFFFF !important;" id="tr<?=$loopCount?>">
					<td style="width: 1%;">
					</td>
					<td align="left" style="width: 69%; text-align: left !important; vertical-align: top !important;" valign="top">
						<table style="width: 100%; background-color: #FFFFFF !important;" border="0" cellpadding="0" cellspacing="0">
							<tr style="background-color: #FFFFFF !important;">
								<td align="left" valign="top" style="vertical-align: top !important; text-align: left !important;">
									<a href="<?=$mRow->permalink?>" target="_blank" style="float: left !important;"><?=$mRow->title?></a>
								</td>
							</tr>
							<tr style="background-color: #FFFFFF !important;">
								<td>
									<?=$mRow->content?>
								</td>
							</tr>
							<tr style="background-color: #FFFFFF !important;">
								<td>
									<a class="lnkDet" style="cursor: pointer; cursor: hand; float: left !important;" mentionid="<?=$mRow->mentionId?>">View Details</a>
								</td>
							</tr>
						</table>
					</td>
					<td style="width: 1%;">
					</td>
					<td style="width: 28%;" align="center" valign="top">
						<table style="width: 100%; background-color: #FFFFFF !important;" border="0" cellpadding="0" cellspacing="0">
							<tr style="background-color: #FFFFFF !important;">
								<td valign="top" style="width: 15%;" align="right">
									<img src="images/ajax.gif" alt="Processing" id="imgProcessingM<?=$loopCount?>" style="display: none;" /><img src="images/minus.png" processingImg="imgProcessingM<?=$loopCount?>" alt="Minus" id="lnkMinus<?=$loopCount?>" otherImg="lnkPlus<?=$loopCount?>" class="minus" rank="<?=$mRow->sentimentRank?>" imageid="Rank<?=$loopCount?>" mentionid="<?=$mRow->mentionId?>" style="cursor: hand; cursor: pointer;"/>
								</td>
								<td align="center" valign="top">
								<?php
									if ($mRow->sentimentRank=="1")
									{
								?>
									<img src="images/neg1.jpg" alt="Mention Classification" id="Rank<?=$loopCount?>"/>
								<?php
									}
									else if ($mRow->sentimentRank=="2")
									{
								?>
									<img src="images/neg2.jpg" alt="Mention Classification" id="Rank<?=$loopCount?>"/>
								<?php							
									}
									else if ($mRow->sentimentRank=="3")
									{
								?>
									<img src="images/neu.jpg" alt="Mention Classification" id="Rank<?=$loopCount?>"/>
								<?php							
									}
									else if ($mRow->sentimentRank=="4")
									{
								?>
									<img src="images/pos1.jpg" alt="Mention Classification" id="Rank<?=$loopCount?>"/>
								<?php							
									}
									else if ($mRow->sentimentRank=="5")
									{
								?>
									<img src="images/pos2.jpg" alt="Mention Classification" id="Rank<?=$loopCount?>"/>
								<?php							
									}
								?>
								</td>
								<td valign="top" style="width: 15%;">
									<img src="images/plus.png" processingImg="imgProcessingP<?=$loopCount?>" alt="Plus" id="lnkPlus<?=$loopCount?>" otherImg="lnkMinus<?=$loopCount?>" class="plus" rank="<?=$mRow->sentimentRank?>" imageid="Rank<?=$loopCount?>" mentionid="<?=$mRow->mentionId?>" style="cursor: hand; cursor: pointer;"/><img src="images/ajax.gif" alt="Processing" id="imgProcessingP<?=$loopCount?>" style="display: none;" />							
								</td>
							</tr>
							<tr style="height: 5px;">
								<td colspan="3">
								</td>
							</tr>
							<tr style="background-color: #FFFFFF !important;">
								<td align="center" colspan="3">
									<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
										<tr style="background-color: #FFFFFF !important;">
											<td style="width: 30%; text-align: right;">
												<img src="images/ajax.gif" alt="Processing" id="imgProcessingStar<?=$loopCount?>" style="display: none;" />
											</td>
											<td style="text-align: left;">
												<?php	
												if ($mRow->starFlag == "1")
												{
												?>
													<img src="images/star2.png" processing="imgProcessingStar<?=$loopCount?>" starred="<?=$mRow->starFlag?>" class="Starred" id="imgStarred<?=$loopCount?>" mentionid="<?=$mRow->mentionId?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
												<?php
												}
												else
												{
												?>
													<img src="images/star1.png" processing="imgProcessingStar<?=$loopCount?>" starred="<?=$mRow->starFlag?>" class="Starred" id="imgStarred<?=$loopCount?>" mentionid="<?=$mRow->mentionId?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
												<?php
												}
												if ($mRow->mineFlag == "1")
												{
												?>
													&nbsp;&nbsp;<img src="images/my2.png" processing="imgProcessingStar<?=$loopCount?>" my="<?=$mRow->mineFlag?>" class="my" id="imgMy<?=$loopCount?>" mentionid="<?=$mRow->mentionId?>" style="cursor: hand; cursor: pointer;" />&nbsp;&nbsp;
												<?php
												}
												else
												{
												?>
													&nbsp;&nbsp;<img src="images/my1.png" processing="imgProcessingStar<?=$loopCount?>" my="<?=$mRow->mineFlag?>" class="my" id="imgMy<?=$loopCount?>" mentionid="<?=$mRow->mentionId?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
												<?php
												}
												?>
													&nbsp;&nbsp;<img src="images/cross1.png" alt="Not Relevant" rowid="tr<?=$loopCount?>" processing="imgProcessingStar<?=$loopCount?>" class="rel" id="imgRel<?=$loopCount?>" mentionid="<?=$mRow->mentionId?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td style="width: 1%;">
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="5">
					</td>
				</tr>
				<tr style="height: 1px; background-color:#000;">
					<td colspan="5">
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="5">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
		$loopCount++;
		}
	}
	else
	{
	?>
	<tr style="height: 10px;">
		<td></td>
	</tr>
	<tr>
		<td style="width: 100%;" align="center">	
			<span style="font-weight: bold; font-size: 18px;">No data found.</span>
		</td>
	</tr>
	<tr style="height: 10px;">
		<td></td>
	</tr>
	<?php
	}
	
	if (isset($mResult->nextUrl))
	{
	?>
	<tr>
		<td align="center">
			<img src="images/ajax.gif" alt="Processing" id="imgProcessingLM<?=$loopCount?>" style="display: none;" /><a class="lnkMore" style="cursor: pointer; cursor: hand; font-weight: bold; font-size: 16px !important;" id="lnkMore<?=$loopCount?>" loopCount="<?=$loopCount?>" type="<?=$mType?>" processing="imgProcessingLM<?=$loopCount?>">Load More</a>
		</td>
	</tr>
	<?php
	}
	?>
</table>
</div>
</form>