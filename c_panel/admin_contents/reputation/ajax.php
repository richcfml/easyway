<?php
include("../../../includes/config.php");
include_once("../../../includes/function.php");

if (isset($_GET["vendastaApi"]))
{
	if (isset($_GET["call"]))
	{
		if ($_GET["call"]=="setrank")
		{
			if (isset($_GET["rank"]))				
			{
				if (isset($_GET["srid"]))				
				{
					if (isset($_GET["mentionid"]))				
					{
						$mUrl = "https://reputation-intelligence-api.vendasta.com/api/v2/mention/setSentiment/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$_GET["srid"]."&sentimentRank=".$_GET["rank"]."&mentionId=".$_GET["mentionid"];
						$mData = "{sentimentRank:".$_GET["rank"].", mentionId:".$_GET["mentionid"]."}";
						$mCh = curl_init();
						curl_setopt($mCh, CURLOPT_URL, $mUrl);
						curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($mCh, CURLOPT_POST, 1);
					    curl_setopt($mCh, CURLOPT_POSTFIELDS, $mData);
					    curl_setopt($mCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					
						$mResult = curl_exec($mCh);
						curl_close($mCh);
						unset($mCh);
						$mResult = json_decode($mResult);
						$mResult = (object) $mResult;
						if (($mResult->statusCode=="200") || ($mResult->statusCode==200))
						{
							echo($_GET["rank"]);
						}
						else
						{
							echo($mResult->message);
						}
					}
				}	
			}
		}
		else if ($_GET["call"]=="togglestarred")
		{
			if (isset($_GET["star"]))				
			{
				if (isset($_GET["srid"]))				
				{
					if (isset($_GET["mentionid"]))				
					{
						$mStar = 'true';
						if ($_GET["star"]==0)
						{
							$mStar = 'false';
						}
						$mUrl = "https://reputation-intelligence-api.vendasta.com/api/v2/mention/setFlag/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$_GET["srid"]."&starFlag=".$mStar."&mentionId=".$_GET["mentionid"];
						$mData = "{starFlag:".$mStar.", mentionId:".$_GET["mentionid"]."}";
						$mCh = curl_init();
						curl_setopt($mCh, CURLOPT_URL, $mUrl);
						curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($mCh, CURLOPT_POST, 1);
					    curl_setopt($mCh, CURLOPT_POSTFIELDS, $mData);
					    curl_setopt($mCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					
						$mResult = curl_exec($mCh);
						curl_close($mCh);
						unset($mCh);
						$mResult = json_decode($mResult);
						$mResult = (object) $mResult;
						if (($mResult->statusCode=="200") || ($mResult->statusCode==200))
						{
							echo($_GET["star"]);
						}
						else
						{
							echo($mResult->message);
						}
					}
				}
			}
		}
		else if ($_GET["call"]=="togglemine")
		{
			if (isset($_GET["my"]))				
			{
				if (isset($_GET["srid"]))				
				{
					if (isset($_GET["mentionid"]))				
					{
						$mMy = 'true';
						if ($_GET["my"]==0)
						{
							$mMy = 'false';
						}
						$mUrl = "https://reputation-intelligence-api.vendasta.com/api/v2/mention/setFlag/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$_GET["srid"]."&mineFlag=".$mMy."&mentionId=".$_GET["mentionid"];
						$mData = "{mineFlag:".$mMy.", mentionId:".$_GET["mentionid"]."}";
						$mCh = curl_init();
						curl_setopt($mCh, CURLOPT_URL, $mUrl);
						curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($mCh, CURLOPT_POST, 1);
					    curl_setopt($mCh, CURLOPT_POSTFIELDS, $mData);
					    curl_setopt($mCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					
						$mResult = curl_exec($mCh);
						curl_close($mCh);
						unset($mCh);
						$mResult = json_decode($mResult);
						$mResult = (object) $mResult;
						if (($mResult->statusCode=="200") || ($mResult->statusCode==200))
						{
							echo($_GET["my"]);
						}
						else
						{
							echo($mResult->message);
						}
					}
				}
			}
		}
		else if ($_GET["call"]=="togglerelevant")
		{
			if (isset($_GET["srid"]))				
			{
				if (isset($_GET["mentionid"]))				
				{
					$mUrl = "https://reputation-intelligence-api.vendasta.com/api/v2/mention/setFlag/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$_GET["srid"]."&relevantFlag=false&mentionId=".$_GET["mentionid"];
					$mData = "{relevantFlag:false, mentionId:".$_GET["mentionid"]."}";
					$mCh = curl_init();
					curl_setopt($mCh, CURLOPT_URL, $mUrl);
					curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($mCh, CURLOPT_POST, 1);
					curl_setopt($mCh, CURLOPT_POSTFIELDS, $mData);
					curl_setopt($mCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				
					$mResult = curl_exec($mCh);
					curl_close($mCh);
					unset($mCh);
					$mResult = json_decode($mResult);
					$mResult = (object) $mResult;
					if (($mResult->statusCode=="200") || ($mResult->statusCode==200))
					{
						echo("1");
					}
					else
					{
						echo($mResult->message);
					}
				}
			}
		}
		else if ($_GET["call"]=="loadMore")
		{
			extract($_POST);
			$mNextURL = $nextUrl;
			$mNextURL .= "&apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1";
			$mCh = curl_init();
			curl_setopt($mCh, CURLOPT_URL, $mNextURL);
			curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
			
			$mResult = curl_exec($mCh);
			curl_close($mCh);
			unset($mCh);
			$mResult = json_decode($mResult);
			$mResult = (object) $mResult;
			$loopCount = $_GET["loopCount"] + 1;
			$mLoadMoreHTML = "";
			foreach ($mResult->data as $mRow)
			{	
				$mRow = (object) $mRow;
				if ($_GET["type"] == "blog")
				{
					if (($mRow->domain!="wordpress.com") && (strpos(strtolower($mRow->source), "blog")===false) && (strpos(strtolower($mRow->permalink), "blog")===false))
					{
						continue;
					}
				}
				
				if (($_GET["type"] == "blog") || ($_GET["type"] == "web"))
				{
					if ($mRow->type=="image")
					{
						continue;
					}
				}
				$mLoadMoreHTML .= '<table style="width: 100%; background-color:#CCC !important;" border="0" cellpadding="0" cellspacing="0" id="tblMain"><tr>
					<td style="width: 100%;">
						<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
							<tr style="background-color: #FFFFFF !important;" id="tr'.$loopCount.'">
								<td style="width: 1%;">
								</td>
								<td align="left" style="width: 69%; text-align: left !important; vertical-align: top !important;" valign="top">
									<table style="width: 100%; background-color: #FFFFFF !important;" border="0" cellpadding="0" cellspacing="0">
										<tr style="background-color: #FFFFFF !important;">
											<td align="left" valign="top" style="vertical-align: top !important; text-align: left !important;">
												<a href="'.$mRow->permalink.'" target="_blank" style="float: left !important;">'.$mRow->title.'</a>
											</td>
										</tr>
										<tr style="background-color: #FFFFFF !important;">
											<td>
												'.$mRow->content.'
											</td>
										</tr>
										<tr style="background-color: #FFFFFF !important;">
											<td>
												<a class="lnkDet" style="cursor: pointer; cursor: hand; float: left !important;" mentionid="'.$mRow->mentionId.'">View Details</a>
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
												<img src="images/ajax.gif" alt="Processing" id="imgProcessingM'.$loopCount.'" style="display: none;" /><img src="images/minus.png" processingImg="imgProcessingM'.$loopCount.'" alt="Minus" id="lnkMinus'.$loopCount.'" otherImg="lnkPlus'.$loopCount.'" class="minus" rank="'.$mRow->sentimentRank.'" imageid="Rank'.$loopCount.'" mentionid="'.$mRow->mentionId.'" style="cursor: hand; cursor: pointer;"/>
											</td>
											<td align="center" valign="top">';
												if ($mRow->sentimentRank=="1")
												{
													$mLoadMoreHTML .= '<img src="images/neg1.jpg" alt="Mention Classification" id="Rank'.$loopCount.'"/>';
												}
												else if ($mRow->sentimentRank=="2")
												{
													$mLoadMoreHTML .= '<img src="images/neg2.jpg" alt="Mention Classification" id="Rank'.$loopCount.'"/>';
												}
												else if ($mRow->sentimentRank=="3")
												{
													$mLoadMoreHTML .= '<img src="images/neu.jpg" alt="Mention Classification" id="Rank'.$loopCount.'"/>';
												}
												else if ($mRow->sentimentRank=="4")
												{
													$mLoadMoreHTML .= '<img src="images/pos1.jpg" alt="Mention Classification" id="Rank'.$loopCount.'"/>';
												}
												else if ($mRow->sentimentRank=="5")
												{
													$mLoadMoreHTML .= '<img src="images/pos2.jpg" alt="Mention Classification" id="Rank'.$loopCount.'"/>';
												}
											$mLoadMoreHTML .= '</td>
											<td valign="top" style="width: 15%;">
												<img src="images/plus.png" processingImg="imgProcessingP'.$loopCount.'" alt="Plus" id="lnkPlus'.$loopCount.'" otherImg="lnkMinus'.$loopCount.'" class="plus" rank="'.$mRow->sentimentRank.'" imageid="Rank'.$loopCount.'" mentionid="'.$mRow->mentionId.'" style="cursor: hand; cursor: pointer;"/><img src="images/ajax.gif" alt="Processing" id="imgProcessingP'.$loopCount.'" style="display: none;" />							
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
															<img src="images/ajax.gif" alt="Processing" id="imgProcessingStar'.$loopCount.'" style="display: none;" />
														</td>
														<td style="text-align: left;">';
															if ($mRow->starFlag == "1")
															{
																$mLoadMoreHTML .= '<img src="images/star2.png" processing="imgProcessingStar'.$loopCount.'" starred="'.$mRow->starFlag.'" class="Starred" id="imgStarred'.$loopCount.'" mentionid="'.$mRow->mentionId.'" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;';
															}
															else
															{
																$mLoadMoreHTML .= '<img src="images/star1.png" processing="imgProcessingStar'.$loopCount.'" starred="'.$mRow->starFlag.'" class="Starred" id="imgStarred'.$loopCount.'" mentionid="'.$mRow->mentionId.'" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;';
															}
															if ($mRow->mineFlag == "1")
															{
																$mLoadMoreHTML .= '&nbsp;&nbsp;<img src="images/my2.png" processing="imgProcessingStar'.$loopCount.'" my="'.$mRow->mineFlag.'" class="my" id="imgMy'.$loopCount.'" mentionid="'.$mRow->mentionId.'" style="cursor: hand; cursor: pointer;" />&nbsp;&nbsp;';
															}
															else
															{
																$mLoadMoreHTML .= '&nbsp;&nbsp;<img src="images/my1.png" processing="imgProcessingStar'.$loopCount.'" my="'.$mRow->mineFlag.'" class="my" id="imgMy'.$loopCount.'" mentionid="'.$mRow->mentionId.'" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;';
															}
																$mLoadMoreHTML .= '&nbsp;&nbsp;<img src="images/cross1.png" alt="Not Relevant" rowid="tr'.$loopCount.'" processing="imgProcessingStar'.$loopCount.'" class="rel" id="imgRel'.$loopCount.'" mentionid="'.$mRow->mentionId.'" style="cursor: hand; cursor: pointer;"/>&nbsp;&nbsp;';
													$mLoadMoreHTML .= '</td>
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
				</tr>';
				$loopCount++;
			}
			$mNextURLTmp = "";
			if (isset($mResult->nextUrl))
			{
				$mNextURLTmp = $mResult->nextUrl;
				$mLoadMoreHTML .= '<tr>
					<td colspan="3" align="center">
						<img src="images/ajax.gif" alt="Processing" id="imgProcessingLM'.$loopCount.'" style="display: none;" /><a class="lnkMore" style="cursor: pointer; cursor: hand;" id="lnkMore'.$loopCount.'" loopCount='.$loopCount.' type="'.$_GET["type"].'" processing="imgProcessingLM'.$loopCount.'">Load More</a>
					</td>
				</tr>';
			}
			$mLoadMoreHTML .= '</table>';
			$mLoadMoreHTML = $mLoadMoreHTML.'|||'.$mNextURLTmp;
			
			echo($mLoadMoreHTML);
		}
		else if ($_GET["call"]=="notminev")
		{
			if (isset($_GET["srid"]))				
			{
				if (isset($_GET["listingid"]))				
				{
					$mUrl = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/markListingNotMine/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$_GET["srid"]."&listingId=".$_GET["listingid"];
					$mData = "{listingId:".$_GET["listingid"]."}";
					$mCh = curl_init();
					curl_setopt($mCh, CURLOPT_URL, $mUrl);
					curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($mCh, CURLOPT_POST, 1);
					curl_setopt($mCh, CURLOPT_POSTFIELDS, $mData);
					curl_setopt($mCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				
					$mResult = curl_exec($mCh);
					curl_close($mCh);
					unset($mCh);
					$mResult = json_decode($mResult);
					$mResult = (object) $mResult;
					if (($mResult->statusCode=="200") || ($mResult->statusCode==200))
					{
						echo("1");
					}
					else
					{
						echo($mResult->message);
					}
				}
			}
		}
		else if ($_GET["call"]=="minev")
		{
			if (isset($_GET["srid"]))				
			{
				if (isset($_GET["listingid"]))				
				{
					$mUrl = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/markListingMine/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$_GET["srid"]."&listingId=".$_GET["listingid"];
					$mData = "{listingId:".$_GET["listingid"]."}";
					$mCh = curl_init();
					curl_setopt($mCh, CURLOPT_URL, $mUrl);
					curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($mCh, CURLOPT_POST, 1);
					curl_setopt($mCh, CURLOPT_POSTFIELDS, $mData);
					curl_setopt($mCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				
					$mResult = curl_exec($mCh);
					curl_close($mCh);
					unset($mCh);
					$mResult = json_decode($mResult);
					$mResult = (object) $mResult;
					if (($mResult->statusCode=="200") || ($mResult->statusCode==200))
					{
						echo("1");
					}
					else
					{
						echo($mResult->message);
					}
				}
			}
		}
		else if ($_GET["call"]=="verified")
		{
			if (isset($_GET["srid"]))				
			{
				if (isset($_GET["listingid"]))				
				{
					$mUrl = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/markListingVerified/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$_GET["srid"]."&listingId=".$_GET["listingid"];
					$mData = "{listingId:".$_GET["listingid"]."}";
					$mCh = curl_init();
					curl_setopt($mCh, CURLOPT_URL, $mUrl);
					curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($mCh, CURLOPT_POST, 1);
					curl_setopt($mCh, CURLOPT_POSTFIELDS, $mData);
					curl_setopt($mCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				
					$mResult = curl_exec($mCh);
					curl_close($mCh);
					unset($mCh);
					$mResult = json_decode($mResult);
					$mResult = (object) $mResult;
					if (($mResult->statusCode=="200") || ($mResult->statusCode==200))
					{
						echo("1");
					}
					else
					{
						echo($mResult->message);
					}
				}
			}
		}
		else if ($_GET["call"]=="notverified")
		{
			if (isset($_GET["srid"]))				
			{
				if (isset($_GET["listingid"]))				
				{
					$mUrl = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/markListingNotVerified/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$_GET["srid"]."&listingId=".$_GET["listingid"];
					$mData = "{listingId:".$_GET["listingid"]."}";
					$mCh = curl_init();
					curl_setopt($mCh, CURLOPT_URL, $mUrl);
					curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($mCh, CURLOPT_POST, 1);
					curl_setopt($mCh, CURLOPT_POSTFIELDS, $mData);
					curl_setopt($mCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				
					$mResult = curl_exec($mCh);
					curl_close($mCh);
					unset($mCh);
					$mResult = json_decode($mResult);
					$mResult = (object) $mResult;
					if (($mResult->statusCode=="200") || ($mResult->statusCode==200))
					{
						echo("1");
					}
					else
					{
						echo($mResult->message);
					}
				}
			}
		}
		else if ($_GET["call"]=="saveAlert")
		{
                    $srid = '';
                    if(isset($_GET['srid']))
                        $srid = $_GET['srid'];
                    
                    $uri = 'https://reputation-intelligence-api.vendasta.com/api/v2/account/update/?apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&apiUser=ESWY&srid=' . $srid;

                    extract($_POST);
                    if(isset($email) || isset($altEmail1) || isset($altEmail2) || isset($altEmail3) || isset($altEmail4) || isset($altEmail5)){

$postString ='
------VendastaBoundary
Content-Disposition: form-data; name="email"

'.$email . '';

if(!empty($altEmail1)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="alternateEmail"

'.$altEmail1.'';
}
if(!empty($altEmail2)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="alternateEmail"

'.$altEmail2.'';
}
if(!empty($altEmail3)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="alternateEmail"

'.$altEmail3.'';
}
if(!empty($altEmail4)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="alternateEmail"

'.$altEmail4.'';
}
if(!empty($altEmail5)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="alternateEmail"

'.$altEmail5.'';
}

$postString .='
------VendastaBoundary--';

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $uri);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POST, true);
                        //curl_setopt($ch, CURLOPT_POST, 'multipart/form-data; boundary=----WebKitFormBoundaryrl2Zle6rOLWYq123');
                        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data;boundary=----VendastaBoundary"));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

                        $api_response = curl_exec($ch);
                        $result = json_decode($api_response);

                        if ($result['statusCode'] == 200) {
                            echo 'Account Updated Successfully';
                        } else {
                            echo "Unable to Update Vendasta account - ";
                            echo $result['message'];
                        }
                        exit;
                    }
		}
		else if ($_GET["call"]=="saveContact")
		{
                    $srid = '';
                    if(isset($_GET['srid']))
                        $srid = $_GET['srid'];                    
                    
                    $uri = 'https://reputation-intelligence-api.vendasta.com/api/v2/account/update/?apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&apiUser=ESWY&srid=' . $srid;

                    extract($_POST);
                    if(isset($firstname) || isset($lastname)){

$postString ='';

if(!empty($firstname)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="firstName"

'.$firstname.'';
}
if(!empty($lastname)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="lastName"

'.$lastname.'';
}

$postString .='
------VendastaBoundary--';

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $uri);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POST, true);
                        //curl_setopt($ch, CURLOPT_POST, 'multipart/form-data; boundary=----WebKitFormBoundaryrl2Zle6rOLWYq123');
                        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data;boundary=----VendastaBoundary"));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

                        $api_response = curl_exec($ch);
                        $result = json_decode($api_response);

                        if ($result['statusCode'] == 200) {
                            echo 'Account Updated Successfully.';
                            exit;
                        } else {
                            echo "Unable to Update Vendasta account - ";
                            echo $result['message'];
                            exit;
                        }
                    }
		}
		else if ($_GET["call"]=="saveInformation")
		{   
                    $srid = '';
                    if (isset($_GET['srid']))
                        $srid = $_GET['srid'];

                    $uri = 'https://reputation-intelligence-api.vendasta.com/api/v2/account/update/?apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&apiUser=ESWY&srid=' . $srid;
                    extract($_POST);
                    

                    if(!empty($service)){
                        $service = explode(",", $service);
                    }

$postString ='
------VendastaBoundary
Content-Disposition: form-data; name="companyName"

'.$businessName . '';
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="address"

'.$address . '';
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="country"

'.$country . '';
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="city"

'.$city . '';
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="state"

'.$state . '';
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="zip"

'.$zip . '';

if(!empty($worknumber1)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="workNumber"

'.$worknumber1.'';
}
if(!empty($worknumber2)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="workNumber"

'.$worknumber2.'';
}
if(!empty($worknumber3)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="workNumber"

'.$worknumber3.'';
}
if(!empty($worknumber4)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="workNumber"

'.$worknumber4.'';
}
if(!empty($worknumber5)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="workNumber"

'.$worknumber5.'';
}
if(!empty($worknumber6)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="workNumber"

'.$worknumber6.'';
}

if(!empty($website)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="website"

'.$website.'';
}


if(isset($service['0'])){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="service"

'.$service['0'].'';
}
if(isset($service['1'])){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="service"

'.$service['1'].'';
}
if(isset($service['2'])){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="service"

'.$service['2'].'';
}
if(isset($service['3'])){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="service"

'.$service['3'].'';
}
if(isset($service['4'])){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="service"

'.$service['4'].'';
}
if(isset($service['5'])){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="service"

'.$service['5'].'';
}


if(!empty($keyperson1)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="employee"

'.$keyperson1.'';
}
if(!empty($keyperson2)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="employee"

'.$keyperson2.'';
}
if(!empty($keyperson3)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="employee"

'.$keyperson3.'';
}

if(!empty($commonBusinessName1)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="commonCompanyName"

'.$commonBusinessName1.'';
}
if(!empty($commonBusinessName2)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="commonCompanyName"

'.$commonBusinessName2.'';
}
if(!empty($commonBusinessName3)){
$postString .='
------VendastaBoundary
Content-Disposition: form-data; name="commonCompanyName"

'.$commonBusinessName3.'';
}                     

$postString .='
------VendastaBoundary--';


                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $uri);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POST, true);
                    //curl_setopt($ch, CURLOPT_POST, 'multipart/form-data; boundary=----WebKitFormBoundaryrl2Zle6rOLWYq123');
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data;boundary=----VendastaBoundary"));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

                    $api_response = curl_exec($ch);
                    $result = json_decode($api_response);

                    if ($result['statusCode'] == 200) {
                        echo 'Account Updated Successfully';
                    } else {
                        echo "Unable to Update Vendasta account - ";
                        echo $result['message'];
                    }
                    exit;
		}
		else if ($_GET["call"]=="visibilitylisting")
		{
			$mCompanyName = ""; 
			$mAddress = "";
			$mCountry = "";
			$mState = "";
			$mCity = "";
			$mPhone = "";
			$mWebSite = "";
			$mZip = "";
			
			$mSourceID = $_GET["sourceid"];
			$mFlag = $_GET["flag"];
			$mSRID = $_GET["srid"];
			$mCategory = strtolower(str_replace(" ", "", $_GET["category"]));
			$mCount = $_GET["count"];
			
			$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/account/get/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID;
			$mCH = curl_init();
			curl_setopt($mCH, CURLOPT_URL, $mURL);
			curl_setopt($mCH, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($mCH, CURLOPT_SSL_VERIFYPEER, 0);
		
			$mResult = curl_exec($mCH);
			curl_close($mCH);
			unset($mCH);
			$mResult = json_decode($mResult);
			$mResult = (object) $mResult;
			if (isset($mResult->data))
			{
				$mData = (object) $mResult->data;
				if (isset($mData->$mSRID))
				{
					$mAccount = (object) $mData->$mSRID;
					$mCompanyName = $mAccount->companyName; 
					$mAddress = $mAccount->address; 
					$mCountry = $mAccount->country; 
					$mState = $mAccount->state; 
					$mCity = $mAccount->city; 
					$mWebSite = $mAccount->website; 
					$mZip = $mAccount->zip; 

					if (isset($mAccount->workNumber))
					{
						$mPhone = $mAccount->workNumber[0]; 
					}
					
					if (trim($mPhone)=="")
					{
						$mPhone = $mAccount->cellNumber; 
					}
				}
			}
			
			
			if ($mFlag==0)
			{
				$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/lookupPossibleListings/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&sourceId=".$mSourceID;
			}
			else if ($mFlag==1)
			{
				$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/lookupListings/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&sourceId=".$mSourceID;
			}
			else if ($mFlag==2)
			{
				$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/lookupListings/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&sourceId=".$mSourceID;
			}
			$mCH = curl_init();
			curl_setopt($mCH, CURLOPT_URL, $mURL);
			curl_setopt($mCH, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($mCH, CURLOPT_SSL_VERIFYPEER, 0);
		
			$mResult = curl_exec($mCH);
			curl_close($mCH);
			unset($mCH);
			$mResult = json_decode($mResult);
			$mResult = (object) $mResult;
			if (isset($mResult->data))
			{
				$mData = (object) $mResult->data;
				if (isset($mData->$mCategory))
				{
					$mHTML = "";
					$mCategory = (array) $mData->$mCategory;
					if (isset($mCategory[0]->listings))
					{
						$mCategory[0] = (object) $mCategory[0];
						$mListingCount = 0;
						foreach ($mCategory[0]->listings as $mListing)
						{
							if ($mListingCount>0)
							{
								$mHTML .= "<br /><br />";
							}
							
							$mListingCount++;
							
							$mListing = (object) $mListing;
							if (isset($mListing->anchorData))
							{
								$mListing->anchorData = (object) $mListing->anchorData;
								$mHTML .= "<div id='dvLSE".$mListingCount.$mCount."'>";
								if (strtolower(trim($mCompanyName))!=strtolower(trim($mListing->anchorData->companyName)))
								{
									$mHTML .= "<span id='spnName".$mListingCount.$mCount."' style='font-weight: bold; color: #FF0000;' onMouseover='ddrivetip(\"<strong><font color=green>Yours: </font></strong>".$mCompanyName."<br /><strong><font color=red>Listings: </font></strong>".$mListing->anchorData->companyName."\", \"white\", 300)'; onMouseout='hideddrivetip()'>".$mListing->anchorData->companyName."</span><br />";
								}
								else
								{
									$mHTML .= $mListing->anchorData->companyName."<br />";
								}
								
								if (strtolower(trim($mAddress))!=strtolower(trim($mListing->anchorData->address)))
								{
									$mHTML .= "<span id='spnAddress".$mListingCount.$mCount."' style='font-weight: bold; color: #FF0000;' onMouseover='ddrivetip(\"<strong><font color=green>Yours: </font></strong>".$mAddress."<br /><strong><font color=red>Listings: </font></strong>".$mListing->anchorData->address."\", \"white\", 300)'; onMouseout='hideddrivetip()'>".$mListing->anchorData->address."</span><br />";
								}
								else
								{
									$mHTML .= $mListing->anchorData->address."<br />";
								}
								
								if ((strtolower(trim($mCity))!=strtolower(trim($mListing->anchorData->city))) || (strtolower(trim($mState))!=strtolower(trim($mListing->anchorData->state))) || (strtolower(trim($mZip))!=strtolower(trim($mListing->anchorData->zip))))
								{
									$mHTML .= "<span id='spnCityStateZip".$mListingCount.$mCount."' style='font-weight: bold; color: #FF0000;' onMouseover='ddrivetip(\"<strong><font color=green>Yours: </font></strong>".$mCity." ".$mState.", ".$mZip."<br /><strong><font color=red>Listings: </font></strong>".$mListing->anchorData->city." ".$mListing->anchorData->state.", ".$mListing->anchorData->zip."\", \"white\", 300)'; onMouseout='hideddrivetip()'>".$mListing->anchorData->city.", ".$mListing->anchorData->state." ".$mListing->anchorData->zip."</span><br />";
								}
								else
								{
									$mHTML .= $mListing->anchorData->city.", ".$mListing->anchorData->state." ".$mListing->anchorData->zip."<br />";
								}
								
								
								if (strtolower(trim($mCountry))!=strtolower(trim($mListing->anchorData->country)))
								{
									$mHTML .= "<span id='spnCountry".$mListingCount.$mCount."' style='font-weight: bold; color: #FF0000;' onMouseover='ddrivetip(\"<strong><font color=green>Yours: </font></strong>".$mCountry."<br /><strong><font color=red>Listings: </font></strong>".$mListing->anchorData->country."\", \"white\", 300)'; onMouseout='hideddrivetip()'>".$mListing->anchorData->country."</span><br />";
								}
								else
								{
									$mHTML .= $mListing->anchorData->country."<br />";
								}
								
								if (strtolower(trim(str_replace("-", "", str_replace(" ", "", $mPhone))))!=strtolower(trim(str_replace("-", "", str_replace(" ", "", $mListing->anchorData->phone)))))
								{
									if ("1".strtolower(trim(str_replace("-", "", str_replace(" ", "", $mPhone))))==strtolower(trim(str_replace("-", "", str_replace(" ", "", $mListing->anchorData->phone)))))
									{
										$mHTML .= $mListing->anchorData->phone."<br />";
									}
									else
									{
										if (strtolower(trim(str_replace("-", "", str_replace(" ", "", $mPhone))))=="1".strtolower(trim(str_replace("-", "", str_replace(" ", "", $mListing->anchorData->phone)))))
										{
											$mHTML .= $mListing->anchorData->phone."<br />";
										}
										else
										{
											$mHTML .= "<span id='spnPhone".$mListingCount.$mCount."' style='font-weight: bold; color: #FF0000;' onMouseover='ddrivetip(\"<strong><font color=green>Yours: </font></strong>".$mPhone."<br /><strong><font color=red>Listings: </font></strong>".$mListing->anchorData->phone."\", \"white\", 300)'; onMouseout='hideddrivetip()'>".$mListing->anchorData->phone."</span><br />";
										}
									}
								}
								else
								{
									$mHTML .= $mListing->anchorData->phone."<br />";
								}
								
								if ($mListing->verifiedCorrectFlag=="1") 
								{
									$mNotVerified = " display: inline; ";
									$mVerified = " display: none; ";
								}
								else
								{
									$mNotVerified = " display: none; ";
									$mVerified = " display: inline; ";
								}
								
								if ($mFlag==0)
								{
									$mHTML .= "<br /><a target='_blank' href='".$mListing->url."'>View</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a divid='dvLSE".$mListingCount.$mCount."' imageid='imgProcessingLSE".$mListingCount.$mCount."' listingid='".$mListing->listingId."' class='clsMine' style='cursor: hand; cursor: pointer;'>Make Mine</a><img src='images/ajax.gif' alt='Processing' id='imgProcessingLSE".$mListingCount.$mCount."' style='display: none;' /></div>";
								}
								else if ($mFlag==1)
								{
									$mHTML .= "<br /><a target='_blank' href='".$mListing->url."'>View</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a divid='dvLSE".$mListingCount.$mCount."' imageid='imgProcessingLSE".$mListingCount.$mCount."' listingid='".$mListing->listingId."' class='clsNotMine' style='cursor: hand; cursor: pointer;'>Not Mine</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a imageid='imgProcessingLSE".$mListingCount.$mCount."' id='hrefLSEV".$mListingCount.$mCount."' otherid='hrefLSENV".$mListingCount.$mCount."' listingid='".$mListing->listingId."' class='clsCorrect' style='cursor: hand; cursor: pointer; ".$mVerified."'>Verify Correct</a><a imageid='imgProcessingLSE".$mListingCount.$mCount."' id='hrefLSENV".$mListingCount.$mCount."' otherid='hrefLSEV".$mListingCount.$mCount."' listingid='".$mListing->listingId."' class='clsNotCorrect' style='cursor: hand; cursor: pointer; ".$mNotVerified."'>Verify Not Correct</a><img src='images/ajax.gif' alt='Processing' id='imgProcessingLSE".$mListingCount.$mCount."' style='display: none;' /></div>";
								}
								else if ($mFlag==2)
								{
									$mHTML .= "<br /><a target='_blank' href='".$mListing->url."'>View</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a divid='dvLSE".$mListingCount.$mCount."' imageid='imgProcessingLSE".$mListingCount.$mCount."' listingid='".$mListing->listingId."' class='clsNotMine' style='cursor: hand; cursor: pointer;'>Not Mine</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a imageid='imgProcessingLSE".$mListingCount.$mCount."' id='hrefLSEV".$mListingCount.$mCount."' otherid='hrefLSENV".$mListingCount.$mCount."' listingid='".$mListing->listingId."' class='clsCorrect' style='cursor: hand; cursor: pointer; ".$mVerified."'>Verify Correct</a><a imageid='imgProcessingLSE".$mListingCount.$mCount."' id='hrefLSENV".$mListingCount.$mCount."' otherid='hrefLSEV".$mListingCount.$mCount."' listingid='".$mListing->listingId."' class='clsNotCorrect' style='cursor: hand; cursor: pointer; ".$mNotVerified."'>Verify Not Correct</a><img src='images/ajax.gif' alt='Processing' id='imgProcessingLSE".$mListingCount.$mCount."' style='display: none;' /></div>";
								}
							}

						}
					}
										
					if (trim($mHTML)!="")
					{
						$mHTML .= "<br /><br /><strong>Submit your listing (URL)</strong><br />";
						$mHTML .= "<input type='text' size='40' id='txtListing".$mListingCount.$mCount."' />&nbsp;&nbsp;<input type='button' value='Submit' class='submitListing' id='btnSubmitListing".$mListingCount.$mCount."' size='20' processing='imgProcessingSL".$mListingCount.$mCount."' textboxid='txtListing".$mListingCount.$mCount."' spanid='spnMsg".$mListingCount.$mCount."' sourceid='".$mSourceID."' srid='".$mSRID."'/>&nbsp;&nbsp;<img src='images/ajax.gif' alt='Processing' id='imgProcessingSL".$mListingCount.$mCount."' style='display: none;' />&nbsp;&nbsp;<span style='color: #FF0000; display: none;' id='spnMsg".$mListingCount.$mCount."'>Please enter listing URL.</span><br />";
					}
					else
					{
						$mHTML = "No Data Found.<br /><br /><strong>Submit your listing (URL)</strong><br />";
						$mHTML .= "<input type='text' size='40' id='txtListing".$mListingCount.$mCount."' />&nbsp;&nbsp;<input type='button' value='Submit' class='submitListing' id='btnSubmitListing".$mListingCount.$mCount."' size='20' processing='imgProcessingSL".$mListingCount.$mCount."' textboxid='txtListing".$mListingCount.$mCount."' spanid='spnMsg".$mListingCount.$mCount."' sourceid='".$mSourceID."' srid='".$mSRID."'/>&nbsp;&nbsp;<img src='images/ajax.gif' alt='Processing' id='imgProcessingSL".$mListingCount.$mCount."' style='display: none;' />&nbsp;&nbsp;<span style='color: #FF0000; display: none;' id='spnMsg".$mListingCount.$mCount."'>Please enter listing URL.</span><br />";
					}
					
					echo($mHTML);
				}
				else
				{
					echo("No Data Found.");
				}
			}
			else
			{
				echo("error");	
			}
		}
		else if ($_GET["call"]=="submitlisting")
		{
			extract($_POST);			
			$mListingURL = $listingURL;
			$mSRID = $_GET["srid"];
			$mSourceID = $_GET["sourceid"];
			
			$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/visibility/submitListing/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&sourceId=".$mSourceID."&url=".$mListingURL;
			$mData = "{url:".$mListingURL."}";
			$mCh = curl_init();
			curl_setopt($mCh, CURLOPT_URL, $mURL);
			curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($mCh, CURLOPT_POST, 1);
			curl_setopt($mCh, CURLOPT_POSTFIELDS, $mData);
			curl_setopt($mCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			
			$mResult = curl_exec($mCh);
			curl_close($mCh);
			unset($mCh);
			$mResult = json_decode($mResult);
			$mResult = (object) $mResult;
			if (($mResult->statusCode=="200") || ($mResult->statusCode==200))
			{
				echo("1");
			}
			else
			{
				echo($mResult->message);
			}
		}
	}
}
