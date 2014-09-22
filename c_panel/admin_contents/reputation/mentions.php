<?php
	include("nav.php");
	$mEndDateTime = date("Y-m-d");
	$mEndDateTime= $mEndDateTime.'T11:59:59Z';
	$mStartDateTime = date('Y-m-d', strtotime('-10 days'));
	$mStartDateTime = $mStartDateTime.'T12:00:00Z';
	$mSRID = $Objrestaurant->srid;	
	$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/mention/search/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$mSRID."&endDateTime=".$mEndDateTime."&startDateTime=".$mStartDateTime."&pageSize=200&minSentimentRank=1&maxSentimentRank=5&relevantFlag=true";
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
		$mSentimentText = "";
		$mBlogTable = "<table style='width: 100%; border: 1px solid #000000;' cellpading='0' cellspacing='0'>";
		$mBlogTable .= "<tr style='height: 35px; background-color: #CCCCCC;'>";
		$mBlogTable .= "<td valign='top' style='width: 2%;'></td>";
		$mBlogTable .= "<td valign='top' style='width: 96%; vertical-align: middle;'>";
		$mBlogTable .= "<strong style='color: maroon; font-size: 18px;'>BLOG</strong>";
		$mBlogTable .= "</td>";
		$mBlogTable .= "<td valign='top' style='width: 2%;'></td>";
		$mBlogTable .= "</tr>";
		$mBlogTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
		$mBlogCount = 0;
		
		$mNewsTable = "<table style='width: 100%; border: 1px solid #000000;' cellpading='0' cellspacing='0'>";
		$mNewsTable .= "<tr style='height: 35px; background-color: #CCCCCC;'>";
		$mNewsTable .= "<td valign='top' style='width: 2%;'></td>";
		$mNewsTable .= "<td valign='top' style='width: 96%; vertical-align: middle;'>";
		$mNewsTable .= "<strong style='color: maroon; font-size: 18px;'>NEWS</strong>";
		$mNewsTable .= "</td>";
		$mNewsTable .= "<td valign='top' style='width: 2%;'></td>";
		$mNewsTable .= "</tr>";
		$mNewsTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
		$mNewsCount = 0;
				
		$mWebTable = "<table style='width: 100%; border: 1px solid #000000;' cellpading='0' cellspacing='0'>";
		$mWebTable .= "<tr style='height: 35px; background-color: #CCCCCC;'>";
		$mWebTable .= "<td valign='top' style='width: 2%;'></td>";
		$mWebTable .= "<td valign='top' style='width: 96%; vertical-align: middle;'>";
		$mWebTable .= "<strong style='color: maroon; font-size: 18px;'>Web</strong>";
		$mWebTable .= "</td>";
		$mWebTable .= "<td valign='top' style='width: 2%;'></td>";
		$mWebTable .= "</tr>";
		$mWebTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
		$mWebCount = 0;
		
		$mFacebookTable = "<table style='width: 100%; border: 1px solid #000000;' cellpading='0' cellspacing='0'>";
		$mFacebookTable .= "<tr style='height: 35px; background-color: #CCCCCC;'>";
		$mFacebookTable .= "<td valign='top' style='width: 2%;'></td>";
		$mFacebookTable .= "<td valign='top' style='width: 96%; vertical-align: middle;'>";
		$mFacebookTable .= "<strong style='color: maroon; font-size: 18px;'>Facebook</strong>";
		$mFacebookTable .= "</td>";
		$mFacebookTable .= "<td valign='top' style='width: 2%;'></td>";
		$mFacebookTable .= "</tr>";
		$mFacebookTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
		$mFacebookCount = 0;
		
		$mTwitterTable = "<table style='width: 100%; border: 1px solid #000000;' cellpading='0' cellspacing='0'>";
		$mTwitterTable .= "<tr style='height: 35px; background-color: #CCCCCC;'>";
		$mTwitterTable .= "<td valign='top' style='width: 2%;'></td>";
		$mTwitterTable .= "<td valign='top' style='width: 96%; vertical-align: middle;'>";
		$mTwitterTable .= "<strong style='color: maroon; font-size: 18px;'>Twitter</strong>";
		$mTwitterTable .= "</td>";
		$mTwitterTable .= "<td valign='top' style='width: 2%;'></td>";
		$mTwitterTable .= "</tr>";
		$mTwitterTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
		$mTwitterCount = 0;
		
		$mImageTable = "<table style='width: 100%; border: 1px solid #000000;' cellpading='0' cellspacing='0'>";
		$mImageTable .= "<tr style='height: 35px; background-color: #CCCCCC;'>";
		$mImageTable .= "<td valign='top' style='width: 2%;'></td>";
		$mImageTable .= "<td valign='top' style='width: 96%; vertical-align: middle;' colspan='3'>";
		$mImageTable .= "<strong style='color: maroon; font-size: 18px;'>Images</strong>";
		$mImageTable .= "</td>";
		$mImageTable .= "<td valign='top' style='width: 2%;'></td>";
		$mImageTable .= "</tr>";
		$mImageTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
		$mImageCount = 0;
        
		foreach ($mResult->data as $mRow)
		{
			$mRow = (object) $mRow;
			if ($mRow->sentimentRank==1)
			{
				$mSentimentText = "Negative";
			}
			else if ($mRow->sentimentRank==2)
			{
				$mSentimentText = "Somewhat Negative";
			}
			else if ($mRow->sentimentRank==3)
			{
				$mSentimentText = "Neutral";
			}
			else if ($mRow->sentimentRank==4)
			{
				$mSentimentText = "Somewhat Positive";
			}
			else if ($mRow->sentimentRank==5)
			{
				$mSentimentText = "Positive";
			}
							
			if ($mRow->type=="web")
			{
				$mWebCount++;
				$mWebTable .= "<tr style='background-color: white;'>";
				$mWebTable .= "<td valign='top' style='width: 2%;'></td>";
				$mWebTable .= "<td valign='top' style='width: 96%;' align='left'>";
				$mWebTable .= "<strong>".$mRow->title."</strong><br />";
				$mWebTable .= "<span style='color: #448866;'>".$mSentimentText."</span><br />";
				$mWebTable .= $mRow->content."<br />";
				$mWebTable .= "<a href='".$mRow->permalink."' target='_blank' style='text-decoration: none; color: #3499cc;'>".$mRow->permalink."</a>";
				$mWebTable .= "</td>";
				$mWebTable .= "<td valign='top' style='width: 2%;'></td>";
				$mWebTable .= "</tr>";
				$mWebTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
				$mWebTable .= "<tr><td valign='top' style='border-bottom: 1px solid #000000 !important;' colspan='3'></td></tr>";
				$mWebTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
			}
			else if ($mRow->type=="facebook")
			{
				$mFacebookCount++;
				$mFacebookTable .= "<tr style='background-color: white;'>";
				$mFacebookTable .= "<td valign='top' style='width: 2%;'></td>";
				$mFacebookTable .= "<td valign='top' style='width: 96%;' align='left'>";
				$mFacebookTable .= "<strong>".$mRow->title."</strong><br />";
				$mFacebookTable .= "<span style='color: #448866;'>".$mSentimentText."</span><br />";
				$mFacebookTable .= $mRow->content."<br />";
				$mFacebookTable .= "<a href='".$mRow->permalink."' target='_blank' style='text-decoration: none; color: #3499cc;'>".$mRow->permalink."</a>";
				$mFacebookTable .= "</td>";
				$mFacebookTable .= "<td valign='top' style='width: 2%;'></td>";
				$mFacebookTable .= "</tr>";
				$mFacebookTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
				$mFacebookTable .= "<tr><td valign='top' style='border-bottom: 1px solid #000000 !important;' colspan='3'></td></tr>";
				$mFacebookTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
			}
			else if ($mRow->type=="twitter")
			{
				$mTwitterCount++;
				$mTwitterTable .= "<tr style='background-color: white;'>";
				$mTwitterTable .= "<td valign='top' style='width: 2%;'></td>";
				$mTwitterTable .= "<td valign='top' style='width: 96%;' align='left'>";
				$mTwitterTable .= "<strong>".$mRow->title."</strong><br />";
				$mTwitterTable .= "<span style='color: #448866;'>".$mSentimentText."</span><br />";
				$mTwitterTable .= $mRow->content."<br />";
				$mTwitterTable .= "<a href='".$mRow->permalink."' target='_blank' style='text-decoration: none; color: #3499cc;'>".$mRow->permalink."</a>";
				$mTwitterTable .= "</td>";
				$mTwitterTable .= "<td valign='top' style='width: 2%;'></td>";
				$mTwitterTable .= "</tr>";
				$mTwitterTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
				$mTwitterTable .= "<tr><td valign='top' style='border-bottom: 1px solid #000000 !important;' colspan='3'></td></tr>";
				$mTwitterTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
			}	
			else if ($mRow->type=="blog")
			{
				$mBlogCount++;
				$mBlogTable .= "<tr style='background-color: white;'>";
				$mBlogTable .= "<td valign='top' style='width: 2%;'></td>";
				$mBlogTable .= "<td valign='top' style='width: 96%;' align='left'>";
				$mBlogTable .= "<strong>".$mRow->title."</strong><br />";
				$mBlogTable .= "<span style='color: #448866;'>".$mSentimentText."</span><br />";
				$mBlogTable .= $mRow->content."<br />";
				$mBlogTable .= "<a href='".$mRow->permalink."' target='_blank' style='text-decoration: none; color: #3499cc;'>".$mRow->permalink."</a>";
				$mBlogTable .= "</td>";
				$mBlogTable .= "<td valign='top' style='width: 2%;'></td>";
				$mBlogTable .= "</tr>";
				$mBlogTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
				$mBlogTable .= "<tr><td valign='top' style='border-bottom: 1px solid #000000 !important;' colspan='3'></td></tr>";
				$mBlogTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
			}	
			else if ($mRow->type=="news")
			{
				$mNewsCount++;
				$mNewsTable .= "<tr style='background-color: white;'>";
				$mNewsTable .= "<td valign='top' style='width: 2%;'></td>";
				$mNewsTable .= "<td valign='top' style='width: 96%;' align='left'>";
				$mNewsTable .= "<strong>".$mRow->title."</strong><br />";
				$mNewsTable .= "<span style='color: #448866;'>".$mSentimentText."</span><br />";
				$mNewsTable .= $mRow->content."<br />";
				$mNewsTable .= "<a href='".$mRow->permalink."' target='_blank' style='text-decoration: none; color: #3499cc;'>".$mRow->permalink."</a>";
				$mNewsTable .= "</td>";
				$mNewsTable .= "<td valign='top' style='width: 2%;'></td>";
				$mNewsTable .= "</tr>";
				$mNewsTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
				$mNewsTable .= "<tr><td valign='top' style='border-bottom: 1px solid #000000 !important;' colspan='3'></td></tr>";
				$mNewsTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='3'></td></tr>";
			}	
			else if ($mRow->type=="image")
			{
				if (($mImageCount%3==0) && ($mImageCount==0))
				{
					$mImageTable .= "<tr style='background-color: white;'>";
					$mImageTable .= "<td valign='top' style='width: 2%;'></td>";
				}
				else if ($mImageCount%3==0)
				{
					$mImageTable .= "<td valign='top' style='width: 2%;'></td>";
					$mImageTable .= "</tr>";					
					$mImageTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='5'></td></tr>";
					$mImageTable .= "<tr><td valign='top' style='border-bottom: 1px solid #000000 !important;' colspan='5'></td></tr>";
					$mImageTable .= "<tr style='height: 10px; background-color: white;'><td valign='top' colspan='5'></td></tr>";
					$mImageTable .= "<tr style='background-color: white;'>";
					$mImageTable .= "<td valign='top' style='width: 2%;'></td>";
				}
				
				$mImageTable .= "<td valign='top' style='width: 32%;' align='left'>";
				$mImageTable .= "<strong>".substr($mRow->title, 0, 15)."...</strong><br />";
				$mImageTable .= "<span style='color: #448866;'>".$mSentimentText."</span><br />";
				$mImageTable .= "<a href='".$mRow->permalink."' target='_blank' style='text-decoration: none;'>".$mRow->content."</a><br />";
				$mImageTable .= "</td>";
				$mImageCount++;
			}
		}
		
		if ($mBlogCount==0)
		{
			$mBlogTable .= "<tr style='background-color: white;'>";
			$mBlogTable .= "<td valign='top' style='width: 2%;'></td>";
			$mBlogTable .= "<td valign='top' style='width: 96%;' align='left'>";
			$mBlogTable .= "<strong>No Data Found</strong>";
			$mBlogTable .= "</td>";
			$mBlogTable .= "<td valign='top' style='width: 2%;'></td>";
			$mBlogTable .= "</tr>";
		}
		else
		{
			$mBlogTable .= "<tr style='background-color: white;'>";
			$mBlogTable .= "<td valign='top' style='width: 2%;'></td>";
			$mBlogTable .= "<td valign='top' style='width: 96%;' align='left'>";
			$mBlogTable .= "<a href='?mod=mentionsall&type=blog&cid=".$mRestaurantIDCP."' style='font-weight: bold; font-size: 16px; text-decoration: none; color: #3499CC;'>View All</a>";
			$mBlogTable .= "</td>";
			$mBlogTable .= "<td valign='top' style='width: 2%;'></td>";
			$mBlogTable .= "</tr>";
		}
		
		if ($mNewsCount==0)
		{
			$mNewsTable .= "<tr style='background-color: white;'>";
			$mNewsTable .= "<td valign='top' style='width: 2%;'></td>";
			$mNewsTable .= "<td valign='top' style='width: 96%;' align='left'>";
			$mNewsTable .= "<strong>No Data Found</strong>";
			$mNewsTable .= "</td>";
			$mNewsTable .= "<td valign='top' style='width: 2%;'></td>";
			$mNewsTable .= "</tr>";
		}
		else
		{
			$mNewsTable .= "<tr style='background-color: white;'>";
			$mNewsTable .= "<td valign='top' style='width: 2%;'></td>";
			$mNewsTable .= "<td valign='top' style='width: 96%;' align='left'>";
			$mNewsTable .= "<a href='?mod=mentionsall&type=news&cid=".$mRestaurantIDCP."' style='font-weight: bold; font-size: 16px; text-decoration: none; color: #3499CC;'>View All</a>";
			$mNewsTable .= "</td>";
			$mNewsTable .= "<td valign='top' style='width: 2%;'></td>";
			$mNewsTable .= "</tr>";
		}
		
		if ($mWebCount==0)
		{
			$mWebTable .= "<tr style='background-color: white;'>";
			$mWebTable .= "<td valign='top' style='width: 2%;'></td>";
			$mWebTable .= "<td valign='top' style='width: 96%;' align='left'>";
			$mWebTable .= "<strong>No Data Found</strong>";
			$mWebTable .= "</td>";
			$mWebTable .= "<td valign='top' style='width: 2%;'></td>";
			$mWebTable .= "</tr>";
		}
		else
		{
			$mWebTable .= "<tr style='background-color: white;'>";
			$mWebTable .= "<td valign='top' style='width: 2%;'></td>";
			$mWebTable .= "<td valign='top' style='width: 96%;' align='left'>";
			$mWebTable .= "<a href='?mod=mentionsall&type=web&cid=".$mRestaurantIDCP."' style='font-weight: bold; font-size: 16px; text-decoration: none; color: #3499CC;'>View All</a>";
			$mWebTable .= "</td>";
			$mWebTable .= "<td valign='top' style='width: 2%;'></td>";
			$mWebTable .= "</tr>";
		}
		
		if ($mFacebookCount==0)
		{
			$mFacebookTable .= "<tr style='background-color: white;'>";
			$mFacebookTable .= "<td valign='top' style='width: 2%;'></td>";
			$mFacebookTable .= "<td valign='top' style='width: 96%;' align='left'>";
			$mFacebookTable .= "<strong>No Data Found</strong>";
			$mFacebookTable .= "</td>";
			$mFacebookTable .= "<td valign='top' style='width: 2%;'></td>";
			$mFacebookTable .= "</tr>";
		}
		else
		{
			$mFacebookTable .= "<tr style='background-color: white;'>";
			$mFacebookTable .= "<td valign='top' style='width: 2%;'></td>";
			$mFacebookTable .= "<td valign='top' style='width: 96%;' align='left'>";
			$mFacebookTable .= "<a href='?mod=mentionsall&type=facebook&cid=".$mRestaurantIDCP."' style='font-weight: bold; font-size: 16px; text-decoration: none; color: #3499CC;'>View All</a>";
			$mFacebookTable .= "</td>";
			$mFacebookTable .= "<td valign='top' style='width: 2%;'></td>";
			$mFacebookTable .= "</tr>";
		}
		
		if ($mTwitterCount==0)
		{
			$mTwitterTable .= "<tr style='background-color: white;'>";
			$mTwitterTable .= "<td valign='top' style='width: 2%;'></td>";
			$mTwitterTable .= "<td valign='top' style='width: 96%;' align='left'>";
			$mTwitterTable .= "<strong>No Data Found</strong>";
			$mTwitterTable .= "</td>";
			$mTwitterTable .= "<td valign='top' style='width: 2%;'></td>";
			$mTwitterTable .= "</tr>";
		}
		else
		{
			$mTwitterTable .= "<tr style='background-color: white;'>";
			$mTwitterTable .= "<td valign='top' style='width: 2%;'></td>";
			$mTwitterTable .= "<td valign='top' style='width: 96%;' align='left'>";
			$mTwitterTable .= "<a href='?mod=mentionsall&type=twitter&cid=".$mRestaurantIDCP."' style='font-weight: bold; font-size: 16px; text-decoration: none; color: #3499CC;'>View All</a>";
			$mTwitterTable .= "</td>";
			$mTwitterTable .= "<td valign='top' style='width: 2%;'></td>";
			$mTwitterTable .= "</tr>";
		}
		
		if ($mImageCount==0)
		{
			$mImageTable .= "<tr style='background-color: white;'>";
			$mImageTable .= "<td valign='top' style='width: 2%;'></td>";
			$mImageTable .= "<td valign='top' style='width: 96%;' align='left' colspan='3'>";
			$mImageTable .= "<strong>No Data Found</strong>";
			$mImageTable .= "</td>";
			$mImageTable .= "<td valign='top' style='width: 2%;'></td>";
			$mImageTable .= "</tr>";
		}
		else
		{
			$mImageTable .= "<tr style='background-color: white;'>";
			$mImageTable .= "<td valign='top' style='width: 2%;'></td>";
			$mImageTable .= "<td valign='top' style='width: 96%;' align='left' colspan='3'>";
			$mImageTable .= "<a href='?mod=mentionsall&type=image&cid=".$mRestaurantIDCP."' style='font-weight: bold; font-size: 16px; text-decoration: none; color: #3499CC;'>View All</a>";
			$mImageTable .= "</td>";
			$mImageTable .= "<td valign='top' style='width: 2%;'></td>";
			$mImageTable .= "</tr>";
		}
		
		$mBlogTable .= "</table>";
		$mWebTable .= "</table>";
		$mFacebookTable .= "</table>";
		$mTwitterTable .= "</table>";
		$mImageTable .= "</table>";
	}

	echo("<div id='main_heading'>
		<span>Mentions</span>
	</div>");
	echo($mWebTable);
	echo("<div style='height: 20px;'></div>");
	echo($mFacebookTable);
	echo("<div style='height: 20px;'></div>");
	echo($mTwitterTable);
	echo("<div style='height: 20px;'></div>");
	echo($mImageTable);
	echo("<div style='height: 20px;'></div>");
	echo($mBlogTable);	
	echo("<div style='height: 20px;'></div>");
	echo($mNewsTable);		
?>