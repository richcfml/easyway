<?php
ini_set( "display_errors", "1" );
error_reporting( E_ALL & ~E_NOTICE);
?>
<link href="../css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox.js" type="text/javascript"></script>
<style type="text/css">
	body
	{
		font-size: 14px;
	}
	
	#RatingDiv
	{
		width:250px;
		height:180px;
		-webkit-border-radius: 20px 16px 16px 16px;
		-moz-border-radius: 20px 16px 16px 16px;
		border-radius: 20px 16px 16px 16px;
		background-color:#D9EFF0;
	}
	
	.RatingDiv span
	{
		display:inline-block;
		font-weight: bold;
	}
	
	tr
	{
		background-color: #FFFFFF !important;
	}
	
	a
	{
		color: #3faae0;
		text-decoration: none;
	}
	
	a:hover
	{
		text-decoration: underline;
	}
</style>
<?php
	include("nav.php");
	$mRating = "NA";
	$mDetails = "";
	$mRatingImg = "images/ratingstar.png";
	$mSRID = $Objrestaurant->srid;
	$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/review/getStats/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID;
	$mCh = curl_init();
	curl_setopt($mCh, CURLOPT_URL, $mURL);
	curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
	
	$mResult = curl_exec($mCh);
	curl_close($mCh);
	unset($mCh);
	$mResult = json_decode($mResult);
	$mResult = (object) $mResult;

	if (isset($mResult->data))
	{
		$mData = (object) $mResult->data;
		if (isset($mData->ratingCounts))
		{
			$mRatingsCount = (array) $mData->ratingCounts;
						
			if (isset($mRatingsCount['1']))
			{
				$mOne = $mRatingsCount['1'];
				$mTwo = $mRatingsCount['2'];
				$mThree = $mRatingsCount['3'];
				$mFour = $mRatingsCount['4'];
				$mFive = $mRatingsCount['5'];			
				if (isset($mRatingsCount['N']))
				{
					$mNo = $mRatingsCount['N'];
				}
				else
				{
					$mNo = 0;
				}
			}
			else
			{
				$mRatingsCount = (object) $mData->ratingCounts;
				$mOne = $mRatingsCount->{'1'};
				$mTwo = $mRatingsCount->{'2'};
				$mThree = $mRatingsCount->{'3'};
				$mFour = $mRatingsCount->{'4'};
				$mFive = $mRatingsCount->{'5'};			
				if (isset($mRatingsCount->N))
				{
					$mNo = $mRatingsCount->N;
				}
				else
				{
					$mNo = 0;
				}
			}
			
			if (!is_numeric($mOne))
			{
				$mOne = 0;
			}
			
			if (!is_numeric($mTwo))
			{
				$mTwo = 0;
			}
			
			if (!is_numeric($mThree))
			{
				$mThree = 0;
			}
			
			if (!is_numeric($mFour))
			{
				$mFour = 0;
			}
			
			if (!is_numeric($mFive))
			{
				$mFive = 0;
			}
			
			if (!is_numeric($mNo))
			{
				$mNo = 0;
			}
			
			if ($mOne + $mTwo + $mThree + $mFour + $mFive>0)
			{
				$mRating = ((($mOne*1) + ($mTwo*2) + ($mThree*3) + ($mFour*4) + ($mFive*5))/($mOne + $mTwo + $mThree + $mFour + $mFive));
			}
			$mRating  = round($mRating, 1);
			$mDetails = ($mOne + $mTwo + $mThree + $mFour + $mFive)." Review<br />".$mNo." with No Star Rating";
		}
	}
?>
<script type="text/javascript" language="javascript">
	$( document ).ready(function() 
	{
		$(".lnkDet").live('click', function()
		{
			var mReviewID = $(this).attr("reviewid");
			$.facebox({div: 'admin_contents/reputation/reviewdetails.php?reviewid='+mReviewID+'&srid=<?=$mSRID?>'});  
			$('#facebox').css("position","absolute");
			$('#facebox').css("height","600px");
			$('#facebox').css("width","800px");
			$('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) - 100 + "px");
			$('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
		});
		
		$("#ddlRatings").live('change', function()
		{
			$("#frmMain").submit();
		});									 
	});
</script>
<form name="frmMain" id="frmMain" method="post" action="?mod=reviews&cid=<?=$mRestaurantIDCP?>">
<div style="height: 5px;"></div>
<div id="main_heading">
	<span>Reviews</span>
</div>
<table style="width: 100%; border: 1px solid black;" cellpadding="0" cellspacing="0">
	<tr>
		<td style="width: 2%;"></td>
		<td style="width: 96%;">
			<div style="margin-left: 25px;">
				<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td style="width: 26%; margin-top: 10px;">
							<div id="RatingDiv" style="text-align: center; vertical-align: middle !important; margin-top: 20px;">
								<span style="font-size: 20px; margin-top: 20px !important; display: inline-block;">Average Rating</span><br />
								<?php
								if ($mRating=="NA")
								{
								?>
									<span style="font-size: 24px !important;">NA</span><br />
								<?php	
								}
								else if (($mRating>0) && ($mRating<1))
								{
								?>
									<img src="images/ratingstar.png" /><img src="images/ratingstar1.png" /><img src="images/ratingstar1.png" /><img src="images/ratingstar1.png" /><img src="images/ratingstar1.png" /><br />
								<?php	
								}
								else if (($mRating>1) && ($mRating<2))
								{
								?>
									<img src="images/ratingstar.png" /><img src="images/ratingstar.png" /><img src="images/ratingstar1.png" /><img src="images/ratingstar1.png" /><img src="images/ratingstar1.png" /><br />
								<?php
								}
								else if (($mRating>2) && ($mRating<3))
								{
								?>
									<img src="images/ratingstar.png" /><img src="images/ratingstar.png" /><img src="images/ratingstar.png" /><img src="images/ratingstar1.png" /><img src="images/ratingstar1.png" /><br />
								<?php						
								}
								else if (($mRating>3) && ($mRating<4))
								{
								?>
									<img src="images/ratingstar.png" /><img src="images/ratingstar.png" /><img src="images/ratingstar.png" /><img src="images/ratingstar.png" /><img src="images/ratingstar1.png" /><br />
								<?php						
								}
								else if (($mRating>4) && ($mRating<5))
								{
								?>
									<img src="images/ratingstar.png" /><img src="images/ratingstar.png" /><img src="images/ratingstar.png" /><img src="images/ratingstar.png" /><img src="images/ratingstar.png" /><br />
								<?php						
								}
								?>
								<span style="font-size: 24px !important; margin-top: 5px;"><?=$mRating?>&nbsp;/&nbsp;5</span><br /><br />
								<?=$mDetails?>
							</div>
						</td>
						<td style="width: 6%;"></td>
						<td style="width: 29%;">
							<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
								<tr style="height: 20px;">
									<td colspan="5">
									</td>
								</tr>
								<tr>
									<td style="width: 10%;"></td>
									<td valign="top" style="width: 30%;">
										<span style="font-weight: bold; font-size: 16px; margin-top: 0px !important;">5 Star</span>							
									</td>
									<td>
										<img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" />
									</td>
									<td valign="top" style="width: 4%;">
										<?=$mFive?>
									</td>
									<td style="width: 10%;"></td>
								</tr>
								<tr style="height: 15px;">
									<td colspan="5">
									</td>
								</tr>
								<tr>
									<td style="width: 10%;"></td>
									<td valign="top">
										<span style="font-weight: bold; font-size: 16px; margin-top: 0px !important;">4 Star</span>
									</td>
									<td>
										<img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar3.png" />
									</td>
									<td valign="top">
										<?=$mFour?>
									</td>
									<td style="width: 10%;"></td>
								</tr>
								<tr style="height: 15px;">
									<td colspan="5">
									</td>
								</tr>
								<tr>
									<td style="width: 10%;"></td>
									<td valign="top">
										<span style="font-weight: bold; font-size: 16px; margin-top: 0px !important;">3 Star</span>
									</td>
									<td>
										<img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" />
									</td>
									<td valign="top">
										<?=$mThree?>
									</td>							
									<td style="width: 10%;"></td>
								</tr>
								<tr style="height: 15px;">
									<td colspan="5">
									</td>
								</tr>
								<tr>
									<td style="width: 10%;"></td>
									<td valign="top">
										<span style="font-weight: bold; font-size: 16px; margin-top: 0px !important;">2 Star</span>
									</td>
									<td>
										<img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" />
									</td>
									<td valign="top">
										<?=$mTwo?>
									</td>							
									<td style="width: 10%;"></td>
								</tr>
								<tr style="height: 15px;">
									<td colspan="5">
									</td>
								</tr>
								<tr>
									<td style="width: 10%;"></td>
									<td valign="top">
										<span style="font-weight: bold; font-size: 16px; margin-top: 0px !important;">1 Star</span>
									</td>
									<td>
										<img src="images/ratingstar2.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" />
									</td>
									<td valign="top">
										<?=$mOne?>
									</td>				
									<td style="width: 10%;"></td>			
								</tr>
							</table>
						</td>
						<td style="width: 6%;"></td>
						<td style="width: 30%;">
							<div id="PieChartforReviews" style="margin-top: 40px !important;"></div>	
							<?php
								$mURL2 = "https://reputation-intelligence-api.vendasta.com/api/v2/review/getStats/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID;
								$ch2 = curl_init();
								curl_setopt($ch2, CURLOPT_URL, $mURL2);
								curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
						
								$mResult2 = curl_exec($ch2);
								$mResult2 = json_decode($mResult2);
								$mResult2 = (object) $mResult2;
								$mDataR = (object) $mResult2->data;
								
								$mSources = (array) $mDataR->sourceCounts;
								
								$mReviews = array();
								$mCount = 0;
								foreach ($mSources as $key => $value)
								{
									$mSourceName = "NA".$mCount;
									
									$mSQL = "SELECT IFNULL(SourceName, 'NA') AS SourceName FROM vendasta_sources WHERE SourceID='".$key."'";
									$mResultQ = mysql_query($mSQL);
									if (mysql_num_rows($mResultQ)>0)
									{
										$mRowQ = mysql_fetch_object($mResultQ);
										$mSourceName = $mRowQ->SourceName;
									}
									$mReviews[$mSourceName]= $value;
									$mCount++;
								}
								curl_close($ch2);
								unset($ch2);
							?>
							<script type="text/javascript">
								var total = 0;
								var count = 0;
								total = parseInt(total);
								google.load('visualization', '1.0', {'packages':['corechart']});
								google.setOnLoadCallback(drawChart);
							
								function drawChart() 
								{
									var data1 = new google.visualization.DataTable();
									data1.addColumn('string', 'Topping');
									data1.addColumn('number', 'Slices');
									var options = {'title':"Distribution",'width':400,'height':340, chartArea: {width: '80%'}};		
									
									var data = new Array();
									<?php 
									foreach($mReviews as $key => $val)
									{ 
									?>
									data1.addRows([['<?=$key?>', Math.round(<?=$val?>)] ]);
									count++;
									<?php 
									} 
									?>
							
																
									var chart = new google.visualization.PieChart(document.getElementById('PieChartforReviews'));
									chart.draw(data1, options);
									
									if (count==0)
									{
										$("#PieChartforReviews").text("No Data Available");
										$("#PieChartforReviews").css("vertical-align", "middle");
										$("#PieChartforReviews").css("margin-top", "160px");					
										$("#PieChartforReviews").css("height", "160px");
									}
								}
							</script>
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td style="width: 2%;"></td>
	</tr>
</table>
<div style="height: 30px;"></div>
<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<span style="font-size: 24px; font-weight: bold;">Reviews List</span>
		</td>
	</tr>
		<tr style="height: 20px;">
		<td></td>
	</tr>
	<?php
	$mRatingSelect = 0;
	if (isset($_POST["ddlRatings"]))
	{
		if ($_POST["ddlRatings"]==0)
		{
			$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/review/search/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&pageSize=300";
		}
		else
		{
			$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/review/search/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&pageSize=300&rating=".$_POST["ddlRatings"];
			$mRatingSelect = $_POST["ddlRatings"];
		}
	}
	else
	{
		$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/review/search/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&pageSize=300";
	}
	?>
	<tr style="background-color: #EFEFEF !important; height: 35px !important;">
		<td>
			<span style="font-weight: bold;">Rating:</span>&nbsp;&nbsp;
			<select name="ddlRatings" id="ddlRatings">
				<option value="0" <?php if ($mRatingSelect==0) echo( 'selected="selected"' ); else echo(''); ?>></option>
				<option value="1" <?php if ($mRatingSelect==1) echo( 'selected="selected"' ); else echo(''); ?>>1</option>
				<option value="2" <?php if ($mRatingSelect==2) echo( 'selected="selected"' ); else echo(''); ?>>2</option>
				<option value="3" <?php if ($mRatingSelect==3) echo( 'selected="selected"' ); else echo(''); ?>>3</option>
				<option value="4" <?php if ($mRatingSelect==4) echo( 'selected="selected"' ); else echo(''); ?>>4</option>
				<option value="5" <?php if ($mRatingSelect==5) echo( 'selected="selected"' ); else echo(''); ?>>5</option>																
			</select>
		</td>
	</tr>	
</table>
	<?php
	$mCh = curl_init();
	curl_setopt($mCh, CURLOPT_URL, $mURL);
	curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
	
	$mResult = curl_exec($mCh);
	curl_close($mCh);
	unset($mCh);
	$mResult = json_decode($mResult);
	$mResult = (object) $mResult;
	$mReviewCount = 0;
	if (isset($mResult->data))
	{
		foreach ($mResult->data as $mRow)
		{
			$mReviewCount++;
			$mRow = (object) $mRow;
	?>
			<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
				<tr style="height: 2px; background-color: #000000;">
					<td colspan="2"></td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="2"></td>
				</tr>
				<tr>
					<td align="left">
						<a href="<?=$mRow->url?>" target="_blank"><?=$mRow->title?></a>
					</td>
					<td align="right">
					<?php
						if ($mRow->rating==1)
						{
					?>
						<img src="images/ratingstar2.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" />
					<?php		
						}
						else if ($mRow->rating==2)
						{
					?>
						<img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" />
					<?php				
						}
						else if ($mRow->rating==3)
						{
					?>
						<img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar3.png" /><img src="images/ratingstar3.png" />
					<?php				
						}
						else if ($mRow->rating==4)
						{
					?>
						<img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar3.png" />
					<?php				
						}
						else if ($mRow->rating==5)
						{
					?>
						<img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" /><img src="images/ratingstar2.png" />
					<?php		
						}
						else
						{
					?>		
						<span style="font-size: 24px !important;">NA</span><br />
					<?php	
						}
					?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<span><?=str_replace("Z", "", str_replace("T", " ", $mRow->publishedDateTime))?></span>&nbsp;&nbsp;<a href="<?=$mRow->reviewerUrl?>" target="_blank"><?=$mRow->reviewerName?></a>&nbsp;&nbsp;via&nbsp;&nbsp;<a href="<?=$mRow->url?>" target="_blank"><?=$mRow->sourceName?></a>
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="2">
						<span><?=$mRow->contentSnippet?></span>
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="2">
						<a class="lnkDet" style="cursor: hand; cursor: pointer;" reviewid="<?=$mRow->reviewId?>">View Details</a>
					</td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="2"></td>
				</tr>
				<tr style="height: 1px; background-color: #000000 !important;">
					<td colspan="2"></td>
				</tr>
				<tr style="height: 10px;">
					<td colspan="2"></td>
				</tr>
			</table>
	<?php		
		}
	}
	if ($mReviewCount==0)
	{
	?>
	<div style="height: 20px;"></div>
	<span style="font-size: 18px; font-weight: bold;">No Data Available.</span>
	<?php
	}
	?>
</form>	