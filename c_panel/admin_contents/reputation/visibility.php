<div id="dhtmltooltip"></div>

<script type="text/javascript" language="javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip
</script>
<?php
$mSRID = $Objrestaurant->srid;
?>
<style type="text/css">
	#dhtmltooltip
	{
		position: absolute;
		width: 150px;
		border: 2px solid black;
		padding: 2px;
		background-color: lightyellow;
		visibility: hidden;
		z-index: 100;
	}
	tr
	{
		background-color: #FFFFFF;
	}
	
	a
	{
		text-decoration: none;
		color: #069;
	}
	
	a:hover
	{
		text-decoration: underline;
		color: #069;
	}
</style>
<script type="text/javascript" language="javascript">
	$( document ).ready(function() 
	{
		$(".submitListing").live('click', function()
		{
			var mSpanID = $(this).attr("spanid");
			var mTextboxID = $(this).attr("textboxid");
			var mSourceID = $(this).attr("sourceid");
			var mSRID = $(this).attr("srid");
			var mProcessing = $(this).attr("processing");
			
			if ($.trim($("#"+mTextboxID).val())=="")
			{
				$("#"+mSpanID).val("Please enter listing URL.");
				$("#"+mSpanID).show();
				return false;
			}
			else
			{
				$("#"+mSpanID).hide();
				$("#"+mProcessing).show();
				
				var mRandom = Math.floor((Math.random()*1000000)+1); 
			
				var mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=submitlisting&sourceid="+mSourceID+"&srid=<?=$mSRID?>&rndm="+mRandom;				
				
				$.ajax
				({
					url: mUrl,
					type:'POST',
					data: 
					{
                        listingURL: $("#"+mTextboxID).val()
					},
					success: function(data)
					{
						if (data>0)
						{
							$("#"+mSpanID).val("Listing submitted successfully.");
							$("#"+mSpanID).text("Listing submitted successfully.");
							$("#"+mSpanID).show();
						}
						else
						{
							$("#"+mSpanID).val(data);
							$("#"+mSpanID).text(data);
							$("#"+mSpanID).show();
						}
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
		
		$(".EC").live('click', function()
		{
			var mDivID = $(this).attr("divid");
			var mSourceID = $(this).attr("sourceid");
			var mProcessing =  $(this).attr("processing");
			var mImageID =  $(this).attr("imageid");
			var mLinkID =  $(this).attr("linkid");
			var mHiddenID =  $(this).attr("hiddenid");
			var mCategory =  $(this).attr("category");
			var mCount =  $(this).attr("count");
			var mFlag =  $(this).attr("flag");
			
			fillListings(mDivID, mSourceID, mProcessing, mImageID, mLinkID, mHiddenID, mCategory, mCount, mFlag);
		});
		
		$(".hrefLnk").live('click', function()
		{
			var mDivID = $(this).attr("divid");
			var mSourceID = $(this).attr("sourceid");
			var mProcessing =  $(this).attr("processing");
			var mImageID =  $(this).attr("imageid");
			var mLinkID =  $(this).attr("linkid");
			var mHiddenID =  $(this).attr("hiddenid");			
			var mCategory =  $(this).attr("category");
			var mCount =  $(this).attr("count");
			var mFlag =  $(this).attr("flag");
						
			fillListings(mDivID, mSourceID, mProcessing, mImageID, mLinkID, mHiddenID, mCategory, mCount, mFlag);
		});
		
		function fillListings(pDivID, pSourceID, pProcessing, pImageID, pLinkID, pHiddenID, pCategory, pCount, pFlag)
		{
			if ($("#"+pImageID).attr("src")=="images/expand.png")
			{
				$("#"+pImageID).attr("src", "images/collapse.png");
				if ($("#"+pHiddenID).val()=="0")
				{
					$("#"+pProcessing).show();
					var mRandom = Math.floor((Math.random()*1000000)+1); 
					
					var mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=visibilitylisting&sourceid="+pSourceID+"&flag="+pFlag+"&srid=<?=$mSRID?>&rndm="+mRandom+"&category="+pCategory+"&count="+pCount;				
				
					$.ajax
					({
						url: mUrl,
						type:'GET',
						success: function(data)
						{
							if (data!="error")
							{
								$("#"+pDivID).html(data);							
								$("#"+pHiddenID).val("1");
							}
							else
							{
								alert("Error occurred. Please try again later.");
							}
							$("#"+pProcessing).hide();
						},
						error:function(data)
						{
							alert("Error occurred. Please try again later.");
							$("#"+pProcessing).hide();
						}
					});
				}
				$("#"+pDivID).show();
			}
			else
			{
				$("#"+pImageID).attr("src", "images/expand.png");
				$("#"+pDivID).hide();
			}
		}
		
		$(".clsNotMine").live('click', function()
		{
			var mListingID = $(this).attr("listingid");
			var mProcessing = $(this).attr("imageid");
			var mDiv = $(this).attr("divid");
			
			$("#"+mProcessing).show();
			var mRandom = Math.floor((Math.random()*1000000)+1); 
			
			var mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=notminev&listingid="+mListingID+"&srid=<?=$mSRID?>&rndm="+mRandom;				
			
			$.ajax
			({
				url: mUrl,
				type:'GET',
				success: function(data)
				{
					if (data>0)
					{
						$("#"+mDiv).hide();							
					}
					else
					{
						alert("Error occurred. Please try again later.");
					}
					$("#"+mProcessing).hide();
				},
				error:function(data)
				{
					alert("Error occurred. Please try again later.");
					$("#"+mProcessing).hide();
				}
			});
		});
		
		$(".clsMine").live('click', function()
		{
			var mListingID = $(this).attr("listingid");
			var mProcessing = $(this).attr("imageid");
			var mDiv = $(this).attr("divid");
			
			$("#"+mProcessing).show();
			var mRandom = Math.floor((Math.random()*1000000)+1); 
			
			var mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=minev&listingid="+mListingID+"&srid=<?=$mSRID?>&rndm="+mRandom;				
			
			$.ajax
			({
				url: mUrl,
				type:'GET',
				success: function(data)
				{
					if (data>0)
					{
						$("#"+mDiv).hide();							
					}
					else
					{
						alert("Error occurred. Please try again later.");
					}
					$("#"+mProcessing).hide();
				},
				error:function(data)
				{
					alert("Error occurred. Please try again later.");
					$("#"+mProcessing).hide();
				}
			});
		});
		
		$(".clsCorrect").live('click', function()
		{
			var mListingID = $(this).attr("listingid");
			var mProcessing = $(this).attr("imageid");
			var mDiv = $(this).attr("divid");
			var mMyID = $(this).attr("id");
			var mOtherID = $(this).attr("otherid");
						
			$("#"+mProcessing).show();
			var mRandom = Math.floor((Math.random()*1000000)+1); 
			
			var mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=verified&listingid="+mListingID+"&srid=<?=$mSRID?>&rndm="+mRandom;				
			
			$.ajax
			({
				url: mUrl,
				type:'GET',
				success: function(data)
				{
					if (data>0)
					{
						$("#"+mMyID).hide();
						$("#"+mOtherID).show();
					}
					else
					{
						alert("Error occurred. Please try again later.");
					}
					$("#"+mProcessing).hide();
				},
				error:function(data)
				{
					alert("Error occurred. Please try again later.");
					$("#"+mProcessing).hide();
				}
			});
		});
		
		$(".clsNotCorrect").live('click', function()
		{
			var mListingID = $(this).attr("listingid");
			var mProcessing = $(this).attr("imageid");
			var mDiv = $(this).attr("divid");
			var mMyID = $(this).attr("id");
			var mOtherID = $(this).attr("otherid");
						
			$("#"+mProcessing).show();
			var mRandom = Math.floor((Math.random()*1000000)+1); 
			
			var mUrl="admin_contents/reputation/ajax.php?vendastaApi=1&call=notverified&listingid="+mListingID+"&srid=<?=$mSRID?>&rndm="+mRandom;				
			
			$.ajax
			({
				url: mUrl,
				type:'GET',
				success: function(data)
				{
					if (data>0)
					{
						$("#"+mMyID).hide();
						$("#"+mOtherID).show();
					}
					else
					{
						alert("Error occurred. Please try again later.");
					}
					$("#"+mProcessing).hide();
				},
				error:function(data)
				{
					alert("Error occurred. Please try again later.");
					$("#"+mProcessing).hide();
				}
			});
		});
	});
</script>
		
<?php
	include("nav.php");
	
	$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/getStats/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID;
    $mCH = curl_init();
    curl_setopt($mCH, CURLOPT_URL, $mURL);
    curl_setopt($mCH, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($mCH, CURLOPT_SSL_VERIFYPEER, 0);

    $mResult = curl_exec($mCH);
    curl_close($mCH);
    unset($mCH);
    $mResult = json_decode($mResult);
    $mResult = (object) $mResult;
    $mResult->data = (object) $mResult->data;
    $mData = $mResult->data;
    $mSourcesFoundWithErrors = $mData->sourcesFoundWithErrors;
    $mSourcesFound = $mData->sourcesFound;
    $mSourcesNotFound= $mData->sourcesNotFound;
    $mTotal = intval($mSourcesNotFound) + intval($mSourcesFoundWithErrors);
	$mPercentage = 0;
	if ($mTotal>0)
	{
	    $mPercentage = (intval($mSourcesFoundWithErrors)/intval($mTotal)*100);
	}
?>
<div id="main_heading">
	<span>Visibility</span>
</div>
<table style="width: 100%; border: 1px solid black;" cellpadding="0" cellspacing="0">
	<tr style="background-color: #DBDBDB !important; height: 60px;">
		<td>
			<span style="font-weight: bold; font-size: 22px; margin-left: 20px; margin-top: 15px !important; color: navy;">Visibility Stats</span>
		</td>
	</tr>
	<tr>
		<td>
			<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
				<tr style="background-color: #F0F0F0 !important; height: 40px;">
					<td style="width: 100%;" colspan="3">
						<span style="font-weight: bold; font-size: 16px; margin-left: 20px; margin-top: 15px !important; color: maroon;"><?=round($mPercentage)."%"?>&nbsp;&nbsp;Presence Score</span>
					</td>
				</tr>
				<tr style="height: 10px; background-color: #FFFFFF;">
					<td></td>
				</tr>
				<tr style="background-color: #FFFFFF !important;">
					<td style="width: 33%;">
						<img src="images/Tick.png"/><span style="float:left;margin-top: 22px;width:150px;margin-left: 20px; font-size: 16px;"><?=$mSourcesFound?> listings found with accurate information </span>
					</td>
					<td style="width: 33%;">
						<img src="images/Exclaimation.png"/><span style="float:left;margin-top: 22px;width:150px;margin-left: 20px; font-size: 16px;"><?=$mSourcesFoundWithErrors?> listings found with possible errors</span>
					</td>
					<td style="width: 34%;">
						<img src="images/Cross.png"/><span style="float:left;margin-top: 22px;width:150px;margin-left: 20px; font-size: 16px;"><?=$mSourcesNotFound?> source missing your listing</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table style="width: 100%; height: 30px;">
	<tr style="height: 30px; background-color: #FFFFFF !important;">
		<td>
		</td>
	</tr>
</table>
<table style="width: 100%; border: 1px solid #CCCCCC;" cellpadding="0" cellspacing="0">
<?php
	$mSQL = "SELECT DISTINCT(Category) AS Category FROM vendasta_sources";
	$mResultCat = dbAbstract::Execute($mSQL,1);
	$mCatCount = 0;
	$mSourceCount = 0;
	
	while ($mRowCat=dbAbstract::returnObject($mResultCat,1))
	{
?>
	<tr style="background-color:#EFEFEF !important; border-bottom: 1px solid #CCCCCC !important; height: 40px;">
		<td colspan="5" style="border-bottom: 1px solid #CCCCCC !important;">
			<span style="margin-left: 10px; font-size: 18px; font-weight: bold;">
				<?=(trim($mRowCat->Category)!=""?$mRowCat->Category:"Uncategorized")?>
			</span>
		</td>
	</tr>
<?php
		$mSQL = "SELECT SourceID, SourceName, SourceImage FROM vendasta_sources WHERE Category='".$mRowCat->Category."'";
		$mResultSo = dbAbstract::Execute($mSQL,1);
		$mCategory = strtolower(str_replace(" ", "", $mRowCat->Category));
		while ($mRowSo=dbAbstract::returnObject($mResultSo,1))
		{
			$mSourceName = $mRowSo->SourceName;
			$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/lookupListings/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&sourceId=".$mRowSo->SourceID;	
			$mCh = curl_init();
			curl_setopt($mCh, CURLOPT_URL, $mURL);
			curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
			
			$mResult = curl_exec($mCh);
			curl_close($mCh);
			unset($mCh);
			$mResult = json_decode($mResult);
			$mResult = (object) $mResult;
			$mTDBg = "#FFFFFF";
			$mImg = "images/notfound.png";
			$mFound = 0;
			
			if (isset($mResult->data))
			{
				$mCatRow = (object) $mResult->data;
				if (isset($mCatRow->$mCategory))
				{
					foreach ($mCatRow->$mCategory as $mRow)
					{
						$mRow = (object) $mRow;
						if (isset($mRow->listings))
						{
							if (count($mRow->listings)>0)
							{
								$mRow->listings[0] = (object) $mRow->listings[0];
								$mAnchorDataMatch = (object) $mRow->listings[0]->anchorDataMatches;
								if (($mAnchorDataMatch->companyName==1) && ($mAnchorDataMatch->address==1) && ($mAnchorDataMatch->city==1) && ($mAnchorDataMatch->country==1) && ($mAnchorDataMatch->phone==1) && ($mAnchorDataMatch->state==1) && ($mAnchorDataMatch->website==1) && ($mAnchorDataMatch->zip==1))
								{
									$mTDBg = "#D3F7B9";
									$mImg = "images/found.png";
									$mFound = 1;
								}
								else
								{
									$mTDBg = "#FDFED8";
									$mImg = "images/possiblefound.png";
									$mFound = 2;
								}
							}
							else
							{
								$mTDBg = "#FFAEC9";
								$mImg = "images/notfound.png";
							}
						}
						else
						{
							$mTDBg = "#FFAEC9";
							$mImg = "images/notfound.png";
						}
					}
				}
				else
				{
					$mTDBg = "#FFAEC9";
					$mImg = "images/notfound.png";
				}
			}
			else
			{
				$mTDBg = "#FFAEC9";
				$mImg = "images/notfound.png";
			}
?>			
		<tr style="background-color:#FFFFFF !important; height: 50px;">
			<td style="width: 2%; border-bottom: 1px solid #CCCCCC !important;">
			</td>
			<td style="width: 5%; border-bottom: 1px solid #CCCCCC !important;">
				<img src="<?=$mRowSo->SourceImage?>"/>
			</td>
			<td style="width: 22%; border-bottom: 1px solid #CCCCCC !important; border-right: 1px solid #CCCCCC !important;">
				<input id="txtSourceID<?=$mCatCount.$mSourceCount?>" type="hidden" value="<?=$mRowSo->SourceID?>" />
				<span style="font-size: 16px;"><?=$mRowSo->SourceName?></span>
			</td>
			<td style="width: 3%; border-bottom: 1px solid #CCCCCC !important; border-right: 1px solid #CCCCCC !important; background-color: <?=$mTDBg?> !important; text-align:center !important; vertical-align: middle !important;">
				<img src="<?=$mImg?>" />
			</td>
			<td style="width: 68%; border-bottom: 1px solid #CCCCCC !important;">
				<div style="margin-left: 10px;">
					<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
<?php
				if ($mFound==0)
				{
?>
						<tr style="height: 15px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>	
						<tr style="background-color: #FFFFFF;">
							<td colspan="3">
								<span>We have not found a listing for you on <strong><?=$mSourceName?></strong>.</span>
							</td>
						</tr>
						<tr style="height: 10px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>
						<tr style="background-color: #FFFFFF;">
							<td style="width: 3%;">
								<input type="hidden" id="hdn<?=$mSourceCount.$mCatCount?>" value="0" /><img style="cursor: hand; cursor: pointer;" flag="<?=$mFound?>" count="<?=$mCatCount.$mSourceCount?>" category="<?=$mRowCat->Category?>" category="<?=$mRowSo->Category?>" hiddenid="hdn<?=$mSourceCount.$mCatCount?>" src="images/expand.png" linkid="href<?=$mSourceCount.$mCatCount?>" id="imgEC<?=$mSourceCount.$mCatCount?>" imageid="imgEC<?=$mSourceCount.$mCatCount?>" class="EC" sourceid="<?=$mRowSo->SourceID?>" processing="imgProcessing<?=$mSourceCount.$mCatCount?>" divid="dv<?=$mSourceCount.$mCatCount?>"/>
							</td>
							<td style="width: 20%;">
								<a flag="<?=$mFound?>" count="<?=$mCatCount.$mSourceCount?>" category="<?=$mRowCat->Category?>" hiddenid="hdn<?=$mSourceCount.$mCatCount?>" id="href<?=$mSourceCount.$mCatCount?>" linkid="href<?=$mSourceCount.$mCatCount?>" style="cursor: hand; cursor: pointer;" class="hrefLnk" imageid="imgEC<?=$mSourceCount.$mCatCount?>" sourceid="<?=$mRowSo->SourceID?>" processing="imgProcessing<?=$mSourceCount.$mCatCount?>" divid="dv<?=$mSourceCount.$mCatCount?>">View possible matches</a>
							</td>
							<td align="left" style="width: 77%;">
								<img src="images/ajax.gif" id="imgProcessing<?=$mSourceCount.$mCatCount?>" style="display: none;"/>
							</td>
						</tr>
						<tr style="height: 15px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>
						<tr style="height: 10px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>
						<tr style="background-color: #FFFFFF;">
							<td colspan="3">
								<div id="dv<?=$mSourceCount.$mCatCount?>">
								</div>
							</td>
						</tr>	
						<tr style="height: 15px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>
<?php				
				}
				else if ($mFound==1)
				{
?>
						<tr style="height: 15px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>	
						<tr style="background-color: #FFFFFF;">
							<td colspan="3">
								<span>You are listed on <strong><?=$mSourceName?></strong>.</span>
							</td>
						</tr>
						<tr style="height: 10px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>	
						<tr style="background-color: #FFFFFF;">
							<td style="width: 3%;">
								<input type="hidden" id="hdn<?=$mSourceCount.$mCatCount?>" value="0" /><img style="cursor: hand; cursor: pointer;" flag="<?=$mFound?>" count="<?=$mCatCount.$mSourceCount?>" category="<?=$mRowCat->Category?>" hiddenid="hdn<?=$mSourceCount.$mCatCount?>" src="images/expand.png" linkid="href<?=$mSourceCount.$mCatCount?>" id="imgEC<?=$mSourceCount.$mCatCount?>" imageid="imgEC<?=$mSourceCount.$mCatCount?>"  class="EC" sourceid="<?=$mRowSo->SourceID?>" processing="imgProcessing<?=$mSourceCount.$mCatCount?>" divid="dv<?=$mSourceCount.$mCatCount?>"/>
							</td>
							<td style="width: 20%;">
								<a flag="<?=$mFound?>" count="<?=$mCatCount.$mSourceCount?>" category="<?=$mRowCat->Category?>" hiddenid="hdn<?=$mSourceCount.$mCatCount?>" id="href<?=$mSourceCount.$mCatCount?>" linkid="href<?=$mSourceCount.$mCatCount?>" style="cursor: hand; cursor: pointer;" imageid="imgEC<?=$mSourceCount.$mCatCount?>" class="hrefLnk" sourceid="<?=$mRowSo->SourceID?>" processing="imgProcessing<?=$mSourceCount.$mCatCount?>" divid="dv<?=$mSourceCount.$mCatCount?>">Show details</a>
							</td>
							<td align="left" style="width: 77%;">
								<img src="images/ajax.gif" id="imgProcessing<?=$mSourceCount.$mCatCount?>" style="display: none;"/>
							</td>
						</tr>
						<tr style="height: 15px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>
						<tr style="height: 10px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>
						<tr style="background-color: #FFFFFF;">
							<td colspan="3">
								<div id="dv<?=$mSourceCount.$mCatCount?>">
								</div>
							</td>
						</tr>	
						<tr style="height: 15px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>	
<?php
				}
				else if ($mFound==2)
				{
?>
						<tr style="height: 15px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>	
						<tr style="background-color: #FFFFFF;">
							<td colspan="3">
								<span>We found your listing on <strong><?=$mSourceName?></strong> but there are possible errors in the contact info.</span>
							</td>
						</tr>
						<tr style="height: 10px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>	
						<tr style="background-color: #FFFFFF;">
							<td style="width: 3%;">
								<input type="hidden" id="hdn<?=$mSourceCount.$mCatCount?>" value="0" /><img style="cursor: hand; cursor: pointer;" flag="<?=$mFound?>" count="<?=$mCatCount.$mSourceCount?>" category="<?=$mRowCat->Category?>" hiddenid="hdn<?=$mSourceCount.$mCatCount?>" src="images/expand.png" linkid="href<?=$mSourceCount.$mCatCount?>" id="imgEC<?=$mSourceCount.$mCatCount?>" imageid="imgEC<?=$mSourceCount.$mCatCount?>"  class="EC" sourceid="<?=$mRowSo->SourceID?>" processing="imgProcessing<?=$mSourceCount.$mCatCount?>" divid="dv<?=$mSourceCount.$mCatCount?>"/>
							</td>
							<td style="width: 20%;">
								<a flag="<?=$mFound?>" count="<?=$mCatCount.$mSourceCount?>" category="<?=$mRowCat->Category?>" id="href<?=$mSourceCount.$mCatCount?>" hiddenid="hdn<?=$mSourceCount.$mCatCount?>" linkid="href<?=$mSourceCount.$mCatCount?>" style="cursor: hand; cursor: pointer;" class="hrefLnk" imageid="imgEC<?=$mSourceCount.$mCatCount?>" sourceid="<?=$mRowSo->SourceID?>" processing="imgProcessing<?=$mSourceCount.$mCatCount?>" divid="dv<?=$mSourceCount.$mCatCount?>">Show details</a>
							</td>
							<td align="left" style="width: 77%;">
								<img src="images/ajax.gif" id="imgProcessing<?=$mSourceCount.$mCatCount?>" style="display: none;"/>
							</td>
						</tr>
						<tr style="height: 10px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>
						<tr style="background-color: #FFFFFF;">
							<td colspan="3">
								<div id="dv<?=$mSourceCount.$mCatCount?>">
								</div>
							</td>
						</tr>	
						<tr style="height: 15px;background-color: #FFFFFF;">
							<td colspan="3">
							</td>
						</tr>
<?php
				}
?>
					</table>
				</div>
			</td>
		</tr>
<?php				
			$mSourceCount++;
		}
		$mCatCount++;
	}
?>
</table>