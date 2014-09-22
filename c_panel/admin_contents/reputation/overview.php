<link href="../css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox.js" type="text/javascript"></script>
<?php 
	ini_set( "display_errors", "1" );
	error_reporting( E_ALL & ~E_NOTICE);
	
	$mCompError = "";
    $srid = $Objrestaurant->srid;
    $url = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/getStats/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=" . $srid;
    $mch = curl_init();
    curl_setopt($mch, CURLOPT_URL, $url);
    curl_setopt($mch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, 0);

    $visResult = curl_exec($mch);
    curl_close($mch);
    unset($mch);
    $visResult = json_decode($visResult);
    $visResult = (object) $visResult;
    $visResult->data = (object) $visResult->data;
    $data = $visResult->data;
    $sourcesFoundWithErrors = $data->sourcesFoundWithErrors;
    $sourcesFound = $data->sourcesFound;
    $sourcesNotFound=     $data->sourcesNotFound;
    $total = intval($sourcesNotFound) + intval($sourcesFoundWithErrors);
	$percentage = 0;
	if ($total>0)
	{
	    $percentage = (intval($sourcesFoundWithErrors)/intval($total)*100);
	}
    ?>
	<?php include("nav.php"); ?>
    <div id="overview">
        <div class="tab-title">
            <div id="main_heading">
                <span>Overview</span>
            </div>
           	<a class="modal tooltip-icon help-titles" rel="about-overview-header" title="About Overview" href="#"></a>
            <div class="clear"></div>
        </div>
        <div id="overview-visibility" class="box-heading">
			<h3>Visibility</h3>
            <span id="percent"><?=round($percentage)."%"?></span>
            <span id="presece_heading">Presence Score</span>
            <div id="vis-list">
				<div id="accurate"><span style="float:left"><img src="images/Tick.png"/></span><span style="float:left;margin-top: 22px;width:150px;margin-left: 20px;"><?=$sourcesFound?> listings found with accurate information </span></div>
				<div id="errors"><span style="float:left"><img src="images/Exclaimation.png"/></span><span style="float:left;margin-top: 22px;width:150px;margin-left: 20px;"><?=$sourcesFoundWithErrors?> listings found with possible errors</span></div>
				<div id="missing"><span style="float:left"><img src="images/Cross.png"/></span><span style="float:left;margin-top: 22px;width:150px;margin-left: 20px;"><?=$sourcesNotFound?> source missing your listing</span></div>
            </div>
		</div>
    </div>

	<div id="graphing" style="margin: 0 auto; width: 100%; text-align: center !important;">

    <?php
        $mURL2 = "https://reputation-intelligence-api.vendasta.com/api/v2/review/getStats/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=" . $srid;
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $mURL2);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);

        $mResult2 = curl_exec($ch2);
        curl_close($ch2);
        unset($ch2);
        ?>
		<script type="text/javascript">
			var count = 0;
			count = parseInt(count);
			var total = 0;
			total = parseInt(total);
			google.load('visualization', '1.0', {'packages':['corechart']});
			google.setOnLoadCallback(drawChart);
		
			function drawChart() 
			{
				var data= jQuery.parseJSON('<?php print_r($mResult2)?>');
				var data1 = new google.visualization.DataTable();
				data1.addColumn('string', 'Topping');
				data1.addColumn('number', 'Slices');
		
				var data2 = new google.visualization.DataTable();
				data2.addColumn('string', 'Star');
				data2.addColumn('number', 'Reviews');
				var options = {'title':"",'width':360,'height':300};
				var options1 = {'title':"",'width':360,'height':300};
				for (var i in data.data.ratingCounts)
				{
					if(count<2)
					{
						total+=data.data.ratingCounts[i];
						data2.addRows([[i+' Star', data.data.ratingCounts[i]] ]);
					}
		
					if(count==2)
					{
						data1.addRows([['Negative', Math.round(total)] ]);
						total = 0;
						total=data.data.ratingCounts[i];
						data1.addRows([['Neutral', Math.round(total)]]);
						total = 0;
						
						data2.addRows([[i+' Star', data.data.ratingCounts[i]] ]);
					}
		
					if(count>2)
					{
						total+=data.data.ratingCounts[i];
						
						data2.addRows([[i+' Star', data.data.ratingCounts[i]] ]);
					
					}
		
					if(count==4)
					{
						data1.addRows([['Positive',  Math.round(total)]]);
						total = 0;
					}
					count++;
				}	
		
				var chart = new google.visualization.PieChart(document.getElementById('PieChartforReviews'));
				chart.draw(data1, options);
		
				var chart1 = new google.visualization.BarChart(document.getElementById('BarChartforReviews'));
				chart1.draw(data2, options1);
				
				if (count==0)
				{
					$("#lnkViewDet").hide();
					$("#BarChartforReviews").text("No Data Available");
					$("#BarChartforReviews").css("vertical-align", "middle");
					$("#BarChartforReviews").css("margin-top", "160px");					
					$("#BarChartforReviews").css("height", "160px");
				}
			}
		
			$( document ).ready(function() 
			{
				$(".fancyAddAttribute").click(function()
				{
					$(".fancyAddAttribute").fancybox();
				});
			});
		</script>
    
        <div class="graph-container first box-heading" style="margin-left: 0px !important;">
			<div>
            	<div class="graph-heading" style="width: 360px;">
	               	<h3>Reviews</h3>
	                <div id="PieChartforReviews"></div>
				</div>
                <a class="fancyAddAttribute" href="#TheFancyboxAddExisting" id="lnkViewDet" style="display: none;">Details</a>
				<div id="TheFancyboxAddExisting" style="display:none;" >
					<div class="graph-container first box-heading">
						<div>
							<div class="graph-heading">
								<h3>Review</h3>
                                <div id="BarChartforReviews"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
       

    <?php
		$mURL1 = "https://reputation-intelligence-api.vendasta.com/api/v2/competition/lookupShareOfVoice/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=" . $srid;
		$ch1 = curl_init();
		curl_setopt($ch1, CURLOPT_URL, $mURL1);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
	
		$mResult1 = curl_exec($ch1);
		curl_close($ch1);
		unset($ch1);
    ?>
   		
		<script type="text/javascript">
			google.load('visualization', '1.0', {'packages':['corechart']});
			google.setOnLoadCallback(drawChart);
			function drawChart() 
			{
				var count=0;
				count = parseInt(count);
				var data= jQuery.parseJSON('<?php print_r($mResult1)?>');
				for (var i in data.data.services)
				{
					var data1 = new google.visualization.DataTable();
					data1.addColumn('string', 'Topping');
					data1.addColumn('number', 'Slices');
					var options = {'title':"", 'width':360, 'height':300};
					var options1 = {'title':i};
					count++;

					$("#PieChartforCompetitors").append($('<div id=\'PieChartforCompetitors'+count+ '\' style="width: 33%;float: left;"></div>'));
					data1.addRows([['Burger King', Math.round(data.data.services[i].companyPercent)] ]);
					for (var j in data.data.services[i].competitors)
					{
						data1.addRows([[data.data.services[i].competitors[j].competitorName, Math.round (data.data.services[i].competitors[j].competitorPercent)]]);
					}
					var chart = new google.visualization.PieChart(document.getElementById('PieChartforCompetitor'));
					chart.draw(data1, options);
					var chart1 = new google.visualization.PieChart(document.getElementById('PieChartforCompetitors'+count));
					chart1.draw(data1, options1);
				}
				
			
				setTimeout(function()
				{
					$("#TheFancyboxcompetitors").removeAttr('style').hide();
				},500);

				if (count==0)
				{
					$("#lnkCompDet").hide();
					$("#PieChartforCompetitor").text("No Data Available");
					$("#PieChartforCompetitor").css("vertical-align", "middle");
					$("#PieChartforCompetitor").css("margin-top", "160px");					
					$("#PieChartforCompetitor").css("height", "160px");
				}
			}
				
			$( document ).ready(function() 
			{
				$(".competitors").click(function()
				{
					$(".competitors").fancybox();
				});
			});
		</script>
		
		<div class="graph-container first box-heading">
			<div>
				<div class="graph-heading">
					<h3>Competition</h3>
					<div id="PieChartforCompetitor"></div>
				</div>
			</div>
            <a class="competitors" href="#TheFancyboxcompetitors" id="lnkCompDet" style="display: none;">Details</a>
			<div id="TheFancyboxcompetitors" style="position:absolute;left:-2000px;" >
				<div class="graph-container first box-heading" >
					<div>
						<div class="graph-heading">
							<h3>Competition</h3>
							<div id="PieChartforCompetitors"><?=$mCompError?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
		$mResult3 = array();
        $CurrentTime = date("Y-m-d");
        $CurrentTime= $CurrentTime.'T11:59:59Z';
        $prvDate = date('Y-m-d', strtotime('-10 days'));
        $prvDate = $prvDate.'T12:00:00Z';
        $mURL3 = "https://reputation-intelligence-api.vendasta.com/api/v2/mention/search/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=" . $srid."&endDateTime=".$CurrentTime."&startDateTime=".$prvDate."&pageSize=200&minSentimentRank=1&maxSentimentRank=5&relevantFlag=true";
        $ch3 = curl_init();
        curl_setopt($ch3, CURLOPT_URL, $mURL3);
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, 0);

        $mResult3 = curl_exec($ch3);
        curl_close($ch3);
        unset($ch3);
        $mRes = json_decode($mResult3);
        $mRes = (object) $mRes;
		
		$mPositiveID = array();
		$mPositiveText = array();
		$mPositiveRank = array();
		$mPositiveType = array();
		$mPositiveContent = array();
		$mPositiveLink = array();
		$mPositiveStar = array();
		$mPositiveMy = array();
		
		$mNegativeID = array();
		$mNegativeText = array();
		$mNegativeRank = array();
		$mNegativeType = array();
		$mNegativeContent = array();
		$mNegativeLink = array();
		$mNegativeStar = array();
		$mNegativeMy = array();
		$mMentionFlag = false;
		
		if (isset($mRes->data))
		{	
			$mMentionFlag = true;
			foreach ($mRes->data as $mRow)
			{
				$mRow = (object) $mRow;
				$mDate = date("d/m/Y", strtotime($mRow->publishedDateTime));
	
				
				if (!isset($mArray[$mDate][0]))
				{
					$mArray[$mDate][0] = 0;
				}
	
				if (!isset($mArray[$mDate][1]))
				{
					$mArray[$mDate][1] = 0;
				}	
	
				if (!isset($mArray[$mDate][2]))
				{
					$mArray[$mDate][2] = 0;
				}
	
				if (($mRow->sentimentRank==1) || ($mRow->sentimentRank==2))
				{
					$mArray[$mDate][0] = $mArray[$mDate][0] + 1;
					if (count($mNegativeID)<5)
					{
						array_push($mNegativeID, $mRow->mentionId);
						array_push($mNegativeText, $mRow->title);
						array_push($mNegativeRank, $mRow->sentimentRank);
						array_push($mNegativeType, $mRow->type);
						array_push($mNegativeContent, $mRow->content);
						array_push($mNegativeLink, $mRow->permalink);
						array_push($mNegativeStar, $mRow->starFlag);
						array_push($mNegativeMy, $mRow->mineFlag);
					}
					else
					{						  
						for ($loopCount=0; $loopCount<count($mNegativeRank); $loopCount++)
						{
							if ($mRow->sentimentRank < $mNegativeRank[$loopCount])
							{
								$mNegativeID[$loopCount] = $mRow->mentionId;
								$mNegativeText[$loopCount] = $mRow->title;
								$mNegativeRank[$loopCount] = $mRow->sentimentRank;
								$mNegativeType[$loopCount] = $mRow->type;
								$mNegativeContent[$loopCount] = $mRow->content;	
								$mNegativeLink[$loopCount] = $mRow->permalink;
								$mNegativeStar[$loopCount] = $mRow->starFlag;
								$mNegativeMy[$loopCount] = $mRow->mineFlag;
							}
						}
					}
				}
				else if ($mRow->sentimentRank==3)
				{
					$mArray[$mDate][1] = $mArray[$mDate][1] + 1;
				}
				else if (($mRow->sentimentRank==4) || ($mRow->sentimentRank==5))
				{
					$mArray[$mDate][2] = $mArray[$mDate][2] + 1;
					if (count($mPositiveID)<5)
					{
						array_push($mPositiveID, $mRow->mentionId);
						array_push($mPositiveText, $mRow->title);
						array_push($mPositiveRank, $mRow->sentimentRank);
						array_push($mPositiveType, $mRow->type);
						array_push($mPositiveContent, $mRow->content);
						array_push($mPositiveLink, $mRow->permalink);
						array_push($mPositiveStar, $mRow->starFlag);
						array_push($mPositiveMy, $mRow->mineFlag);
					}
					else
					{						  
						for ($loopCount=0; $loopCount<count($mPositiveRank); $loopCount++)
						{
							if ($mRow->sentimentRank > $mPositiveRank[$loopCount])
							{
								$mPositiveID[$loopCount] = $mRow->mentionId;
								$mPositiveText[$loopCount] = $mRow->title;
								$mPositiveRank[$loopCount] = $mRow->sentimentRank;
								$mPositiveType[$loopCount] = $mRow->type;
								$mPositiveContent[$loopCount] = $mRow->content;		
								$mPositiveLink[$loopCount] = $mRow->permalink;
								$mPositiveStar[$loopCount] = $mRow->starFlag;
								$mPositiveMy[$loopCount] = $mRow->mineFlag;
							}
						}
					}
				}
			}
			$mArray= json_encode($mArray);
		}
	?>
		
		<?php
		if ($mMentionFlag)
		{
		?>
			<script type="text/javascript">
				var record =<?php print($mArray)?>;
				google.setOnLoadCallback(drawChart);
				function drawChart() 
				{
					var monthNames = [ "","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'Date');
					data.addColumn('number', 'Negative');
					data.addColumn('number', 'Neutral');
					data.addColumn('number', 'Positive');
					var options = {width: 360, height: 300, legend: { position: 'top', maxLines: 3 }, bar: { groupWidth: '20%' }, isStacked: true};
					var count = 0;
					for(var i in record)
					{
						count++;
						var month = i.substring(3, 5);
						var mName = monthNames[parseInt(month)];
						var day = i.substring(0,2);
						var publishDate = day + ' '+ mName;
						data.addRow([publishDate.toString(),parseInt(record[i][0]),parseInt(record[i][1]),parseInt(record[i][2])]);
					}
		
					var visualization = new google.visualization.ColumnChart(document.getElementById('ColumnChartforMention'));
					visualization.draw(data, options);
					google.load("visualization", "1", {packages: ["corechart"]});
					
					if (count==0)
					{
						$("#ColumnChartforMention").text("No Data Available");
						$("#ColumnChartforMention").css("vertical-align", "middle");
						$("#ColumnChartforMention").css("margin-top", "160px");					
						$("#ColumnChartforMention").css("height", "160px");
					}
				}
			</script>
		<?php
		}
		else
		{
		?>
				<script type="text/javascript">
				$("#ColumnChartforMention").text("No Data Available");
				$("#ColumnChartforMention").css("vertical-align", "middle");
				$("#ColumnChartforMention").css("margin-top", "160px");					
				$("#ColumnChartforMention").css("height", "160px");
			</script>
		<?php
		}
		?>
		<div class="graph-container first box-heading">
			<div class="graph-heading" style="width: 360px;">
				<h3>Mentions</h3>
				<a class="modal tooltip-icon help" rel="about-reviews" title="About Reviews" href="#"></a>
				<div id="ColumnChartforMention"></div>
			</div>
            <a class="competitors" href="#TheFancyboxcompetitors" style="display: none;">Details</a>
		</div>
	</div>
	<div style="clear: both;"></div>
	<!-- Top Positive Mentiones Starts Here -->
	<div class="graph-container first box-heading" style="width: 100%;">
		<div class="graph-heading" style="width: 100%;">
			<h3>Top Positive Mentions (Last 10 Days - Maximum 5 Mentions)</h3>
		</div>
		<div style="height: 10px;"></div>
		<script type="text/javascript" language="javascript">
			$(document).ready(function()
			{
				$(".minus").click(function()
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
						
						mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=setrank&mentionid="+mMentionID+"&rank="+mRank+"&srid=<?=$srid?>&rndm="+mRandom;				
						
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
				
				$(".plus").click(function()
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
						
						mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=setrank&mentionid="+mMentionID+"&rank="+mRank+"&srid=<?=$srid?>&rndm="+mRandom;				
						
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
				
				$(".Starred").click(function()
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
					
					mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=togglestarred&mentionid="+mMentionID+"&star="+mStar+"&srid=<?=$srid?>&rndm="+mRandom;				
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
				
				$(".my").click(function()
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
					
					mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=togglemine&mentionid="+mMentionID+"&my="+mMine+"&srid=<?=$srid?>&rndm="+mRandom;				
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
				
				$(".rel").click(function()
				{
					var mMyID = $(this).attr("id");
					var mRowID = $(this).attr("rowid");
					var mMentionID = $(this).attr("mentionid");
					var mProcessing = $(this).attr("processing");
					$("#"+mProcessing).show();
					
					var mUrl = '';
					var mRandom = Math.floor((Math.random()*1000000)+1); 
					
					mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=togglerelevant&mentionid="+mMentionID+"&srid=<?=$srid?>&rndm="+mRandom;				
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
				
				$(".lnkDet").click(function()
				{
					var mMentionID = $(this).attr("mentionid");
					$.facebox({div: 'admin_contents/reputation/mentiondetails.php?mentionid='+mMentionID+'&srid=<?=$srid?>'});  
					$('#facebox').css("position","absolute");
					$('#facebox').css("height","600px");
					$('#facebox').css("width","800px");
					$('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) - 100 + "px");
					$('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
				});
			});
		</script>
		<table style="width: 100%; background-color: #FFFFFF !important;" border="0" cellpadding="0" cellspacing="0">
		<?php
		if (count($mPositiveID)>0)
		{
			for ($loopCount = 0; $loopCount<count($mPositiveID); $loopCount++)
			{
		?>
			<tr style="background-color: #FFFFFF !important;" id="tr<?=$loopCount?>">
				<td style="width: 1%;">
				</td>
				<td align="left" style="width: 69%; text-align: left !important; vertical-align: top !important;" valign="top">
					<table style="width: 100%; background-color: #FFFFFF !important;" border="0" cellpadding="0" cellspacing="0">
						<tr style="background-color: #FFFFFF !important;">
							<td align="left" valign="top" style="vertical-align: top !important; text-align: left !important;">
								<a href="<?=$mPositiveLink[$loopCount]?>" target="_blank" style="float: left !important;"><?=$mPositiveText[$loopCount]?></a>
							</td>
						</tr>
						<tr style="background-color: #FFFFFF !important;">
							<td>
								<?=$mPositiveContent[$loopCount]?>
							</td>
						</tr>
						<tr style="background-color: #FFFFFF !important;">
							<td>
								<a class="lnkDet" style="cursor: pointer; cursor: hand; float: left !important;" mentionid="<?=$mPositiveID[$loopCount]?>">View Details</a>
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
								<img src="images/ajax.gif" alt="Processing" id="imgProcessingPM<?=$loopCount?>" style="display: none;" /><img src="images/minus.png" processingImg="imgProcessingPM<?=$loopCount?>" alt="Minus" id="lnkMinusP<?=$loopCount?>" otherImg="lnkPlusP<?=$loopCount?>" class="minus" rank="<?=$mPositiveRank[$loopCount]?>" imageid="posRank<?=$loopCount?>" mentionid="<?=$mPositiveID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/>
							</td>
							<td align="center" valign="top">
							<?php
								if ($mPositiveRank[$loopCount]=="4")
								{
							?>
								<img src="images/pos1.jpg" alt="Mention Classification" id="posRank<?=$loopCount?>"/>
							<?php
								}
								else if ($mPositiveRank[$loopCount]=="5")
								{
							?>
								<img src="images/pos2.jpg" alt="Mention Classification" id="posRank<?=$loopCount?>"/>
							<?php							
								}
							?>
							</td>
							<td valign="top" style="width: 15%;">
								<img src="images/plus.png" processingImg="imgProcessingPP<?=$loopCount?>" alt="Plus" id="lnkPlusP<?=$loopCount?>" otherImg="lnkMinusP<?=$loopCount?>" class="plus" rank="<?=$mPositiveRank[$loopCount]?>" imageid="posRank<?=$loopCount?>" mentionid="<?=$mPositiveID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/><img src="images/ajax.gif" alt="Processing" id="imgProcessingPP<?=$loopCount?>" style="display: none;" />							
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
											<img src="images/ajax.gif" alt="Processing" id="imgProcessingStarP<?=$loopCount?>" style="display: none;" />
										</td>
										<td style="text-align: left;">
											<?php	
											if ($mPositiveStar[$loopCount] == "1")
											{
											?>
												<img src="images/star2.png" processing="imgProcessingStarP<?=$loopCount?>" starred="<?=$mPositiveStar[$loopCount]?>" class="Starred" id="imgStarredP<?=$loopCount?>" mentionid="<?=$mPositiveID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
											<?php
											}
											else
											{
											?>
												<img src="images/star1.png" processing="imgProcessingStarP<?=$loopCount?>" starred="<?=$mPositiveStar[$loopCount]?>" class="Starred" id="imgStarredP<?=$loopCount?>" mentionid="<?=$mPositiveID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
											<?php
											}
											if ($mPositiveMy[$loopCount] == "1")
											{
											?>
												&nbsp;&nbsp;<img src="images/my2.png" processing="imgProcessingStarP<?=$loopCount?>" my="<?=$mPositiveMy[$loopCount]?>" class="my" id="imgMyP<?=$loopCount?>" mentionid="<?=$mPositiveID[$loopCount]?>" style="cursor: hand; cursor: pointer;" />&nbsp;&nbsp;
											<?php
											}
											else
											{
											?>
												&nbsp;&nbsp;<img src="images/my1.png" processing="imgProcessingStarP<?=$loopCount?>" my="<?=$mPositiveMy[$loopCount]?>" class="my" id="imgMyP<?=$loopCount?>" mentionid="<?=$mPositiveID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
											<?php
											}
											?>
												&nbsp;&nbsp;<img src="images/cross1.png" alt="Not Relevant" rowid="tr<?=$loopCount?>" processing="imgProcessingStarP<?=$loopCount?>" class="rel" id="imgRelP<?=$loopCount?>" mentionid="<?=$mPositiveID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
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
		<?php
			}
		}
		else
		{
		?>
			<tr style="height: 40px; background-color: #FFFFFF;">
				<td style="width: 1%;">
				</td>
				<td colspan="4">
					<span style="font-weight: bold; font-size: 14px;">No data found.</span>
				</td>
			</tr>
		<?php
		}
		?>
		</table>
	</div>
	<!-- Top Positive Mentiones Ends Here -->
	
	<!-- Top Negative Mentiones Starts Here -->
	<div class="graph-container first box-heading" style="width: 100%;">
		<div class="graph-heading" style="width: 100%;">
			<h3>Top Negative Mentions (Last 10 Days - Maximum 5 Mentions)</h3>
		</div>
		<div style="height: 10px;"></div>
		<table style="width: 100%; background-color: #FFFFFF !important;" border="0" cellpadding="0" cellspacing="0">
		<?php
		if (count($mNegativeID)>0)
		{
			for ($loopCount = 0; $loopCount<count($mNegativeID); $loopCount++)
			{
		?>
			<tr style="background-color: #FFFFFF !important;">
				<td style="width: 1%;">
				</td>
				<td align="left" style="width: 69%; text-align: left !important; vertical-align: top !important;" valign="top">
					<table style="width: 100%; background-color: #FFFFFF !important;" border="0" cellpadding="0" cellspacing="0">
						<tr style="background-color: #FFFFFF !important;">
							<td align="left" valign="top" style="vertical-align: top !important; text-align: left !important;">
								<a href="<?=$mNegativeLink[$loopCount]?>" target="_blank" style="float: left !important;"><?=$mNegativeText[$loopCount]?></a>
							</td>
						</tr>
						<tr>
							<td>
								<?=$mNegativeContent[$loopCount]?>
							</td>
						</tr>
						<tr style="background-color: #FFFFFF !important;">
							<td>
								<a class="lnkDet" style="cursor: pointer; cursor: hand; float: left !important;" mentionid="<?=$mNegativeID[$loopCount]?>">View Details</a>
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
								<img src="images/ajax.gif" alt="Processing" id="imgProcessingNM<?=$loopCount?>" style="display: none;" /><img src="images/minus.png" processingImg="imgProcessingNM<?=$loopCount?>" alt="Minus" id="lnkMinusN<?=$loopCount?>" otherImg="lnkPlusN<?=$loopCount?>" class="minus" rank="<?=$mNegativeRank[$loopCount]?>" imageid="negRank<?=$loopCount?>" mentionid="<?=$mNegativeID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/>
							</td>
							<td align="center" valign="top">
							<?php
								if ($mNegativeRank[$loopCount]=="1")
								{
							?>
								<img src="images/neg1.jpg" alt="Mention Classification" id="negRank<?=$loopCount?>"/>
							<?php
								}
								else if ($mNegativeRank[$loopCount]=="2")
								{
							?>
								<img src="images/neg2.jpg" alt="Mention Classification" id="negRank<?=$loopCount?>"/>
							<?php							
								}
							?>
							</td>
							<td valign="top" style="width: 15%;">
								<img src="images/plus.png" processingImg="imgProcessingNP<?=$loopCount?>" alt="Plus" id="lnkPlusN<?=$loopCount?>" otherImg="lnkMinusN<?=$loopCount?>" class="plus" rank="<?=$mNegativeRank[$loopCount]?>" imageid="negRank<?=$loopCount?>" mentionid="<?=$mNegativeID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/><img src="images/ajax.gif" alt="Processing" id="imgProcessingNP<?=$loopCount?>" style="display: none;"/>
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
											<img src="images/ajax.gif" alt="Processing" id="imgProcessingStarN<?=$loopCount?>" style="display: none;" />
										</td>
										<td style="text-align: left;">
											<?php
												if ($mNegativeStar[$loopCount] == "1")
												{
												?>
													<img src="images/star2.png" processing="imgProcessingStarN<?=$loopCount?>" starred="<?=$mNegativeStar[$loopCount]?>" class="Starred" id="imgStarredN<?=$loopCount?>" mentionid="<?=$mNegativeID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
												<?php
												}
												else
												{
												?>
													<img src="images/star1.png" processing="imgProcessingStarN<?=$loopCount?>" starred="<?=$mNegativeStar[$loopCount]?>" class="Starred" id="imgStarredN<?=$loopCount?>" mentionid="<?=$mNegativeID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
												<?php
												}
												if ($mNegativeMy[$loopCount] == "1")
												{
												?>
													&nbsp;&nbsp;<img src="images/my2.png" processing="imgProcessingStarN<?=$loopCount?>" my="<?=$mNegativeMy[$loopCount]?>" class="my" id="imgMyN<?=$loopCount?>" mentionid="<?=$mNegativeID[$loopCount]?>" style="cursor: hand; cursor: pointer;" />&nbsp;&nbsp;
												<?php
												}
												else
												{
												?>
													&nbsp;&nbsp;<img src="images/my1.png" processing="imgProcessingStarN<?=$loopCount?>" my="<?=$mNegativeMy[$loopCount]?>" class="my" id="imgMyN<?=$loopCount?>" mentionid="<?=$mNegativeID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
												<?php
												}
												?>
													&nbsp;&nbsp;<img src="images/cross1.png" alt="Not Relevant" rowid="tr<?=$loopCount?>" processing="imgProcessingStarN<?=$loopCount?>" class="rel" id="imgRelN<?=$loopCount?>" mentionid="<?=$mNegativeID[$loopCount]?>" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;
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
			<tr style="height: 5px;">
				<td colspan="5">
				</td>
			</tr>
			<tr style="height: 1px; background-color:#000;">
				<td colspan="5">
				</td>
			</tr>
			<tr style="height: 5px;">
				<td colspan="5">
				</td>
			</tr>
		<?php
			}
		}
		else
		{
		?>
			<tr style="height: 40px; background-color: #FFFFFF;">
				<td style="width: 1%;">
				</td>
				<td colspan="4">
					<span style="font-weight: bold; font-size: 14px;">No data found.</span>
				</td>
			</tr>
		<?php
		}
		?>
		</table>
	</div>
	<!-- Top Negative Mentiones Ends Here -->