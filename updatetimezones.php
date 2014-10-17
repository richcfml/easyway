<?php
$mStr = "";
if (isset($_POST["btnSubmit"]))
{
	set_time_limit(0);
	$mysql_conn = mysql_connect("localhost","root","") or die( mysql_error()."  cannot connect...");
	mysql_select_db("easywayordering",$mysql_conn);
	
	$mResult = mysql_query("SELECT id, name, rest_address, rest_city, rest_state FROM resturants");
	if (mysql_num_rows($mResult)>0)
	{
		$mCount = 0;
		$mTimeZoneName = "US/Eastern";
		$mStr = "<table style='width: 100%; line-height: 30px;' cellspacing='0' cellpadding='0' border='0'>";
		$mStr .= "<tr style='background-color: #104E8B; color: #FFFFFF;'>";
		$mStr .= "<td style='width: 25%;'><strong>Restaurant Name</strong></td>";
		$mStr .= "<td style='width: 35%;'><strong>Street Address</strong></td>";
		$mStr .= "<td style='width: 15%;'><strong>City</strong></td>";
		$mStr .= "<td style='width: 10%;'><strong>State</strong></td>";
		$mStr .= "<td style='width: 15%;'><strong>Time Zone</strong></td>";
		$mStr .= "</tr>";
	 	while ($mRest=mysql_fetch_object($mResult)) 
		{
			if ((trim(strtolower($mRest->rest_address))!="") && (trim(strtolower($mRest->rest_city))!="") && (trim(strtolower($mRest->rest_state))!=""))
			{
				$mAddress = urlencode($mRest->rest_address." ".$mRest->rest_city." ".$mRest->rest_state);
				$mRequest_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$mAddress."&sensor=true";
				$mXML = simplexml_load_file($mRequest_url) or die("url not loading");
				$mStatus = $mXML->status;
				if ($mStatus=="OK") 
				{
					$mLat = $mXML->result->geometry->location->lat;
					$mLon = $mXML->result->geometry->location->lng;
					$mLatLng = $mLat.",".$mLon;
					$mXML = simplexml_load_file("https://maps.googleapis.com/maps/api/timezone/xml?location=".$mLatLng."&timestamp=1419283200&sensor=false");
					$mStatus = $mXML->status;
					if ($mStatus=="OK") 
					{
						$mTimeZoneName = $mXML->time_zone_name;
						if (strpos(trim(strtolower($mTimeZoneName)), "hawaii")!==false)
						{
							$mTimeZoneName = "US/Hawaii-Aleutian";
						}
						else if (strpos(trim(strtolower($mTimeZoneName)), "alaska")!==false)
						{
							$mTimeZoneName = "US/Alaska";
						}
						else if (strpos(trim(strtolower($mTimeZoneName)), "pacific")!==false)
						{
							$mTimeZoneName = "US/Pacific";
						}
						else if (strpos(trim(strtolower($mTimeZoneName)), "mountain")!==false)
						{
							$mTimeZoneName = "US/Mountain";
						}
						else if (strpos(trim(strtolower($mTimeZoneName)), "central")!==false)
						{
							$mTimeZoneName = "US/Central";
						}
						else if (strpos(trim(strtolower($mTimeZoneName)), "eastern")!==false)
						{
							$mTimeZoneName = "US/Eastern";
						}
						else
						{
							$mTimeZoneName = "US/Eastern";
						}
					}
					
					$mSuccessFlag = 0;
					$mTimeZoneIDRes = mysql_query("SELECT id FROM times_zones WHERE TRIM(LOWER(time_zone))='".trim(strtolower($mTimeZoneName))."'");
					if (mysql_num_rows($mTimeZoneIDRes)>0)
					{
						$mTimeZoneIDRow = mysql_fetch_object($mTimeZoneIDRes);
						
						if (is_numeric($mTimeZoneIDRow->id))
						{
							mysql_query("UPDATE resturants SET time_zone_id=".$mTimeZoneIDRow->id." WHERE id=".$mRest->id);
							$mSuccessFlag = 1;
						}
					}
					
					if ($mSuccessFlag==0)
					{
						$mTimeZoneIDRes = mysql_query("SELECT id FROM times_zones WHERE TRIM(LOWER(time_zone))='US/eastern'");
						if (mysql_num_rows($mTimeZoneIDRes)>0)
						{
							$mTimeZoneIDRow = mysql_fetch_object($mTimeZoneIDRes);
							
							if (is_numeric($mTimeZoneIDRow->id))
							{
								mysql_query("UPDATE resturants SET time_zone_id=".$mTimeZoneIDRow->id." WHERE id=".$mRest->id);
							}
						}
					}
				}	
				else
				{
					$mTimeZoneIDRes = mysql_query("SELECT id FROM times_zones WHERE TRIM(LOWER(time_zone))='US/eastern'");
					if (mysql_num_rows($mTimeZoneIDRes)>0)
					{
						$mTimeZoneIDRow = mysql_fetch_object($mTimeZoneIDRes);
						
						if (is_numeric($mTimeZoneIDRow->id))
						{
							mysql_query("UPDATE resturants SET time_zone_id=".$mTimeZoneIDRow->id." WHERE id=".$mRest->id);
							$mSuccessFlag = 1;
						}
					}					
				}
			}
			else
			{
				$mTimeZoneIDRes = mysql_query("SELECT id FROM times_zones WHERE TRIM(LOWER(time_zone))='US/eastern'");
				if (mysql_num_rows($mTimeZoneIDRes)>0)
				{
					$mTimeZoneIDRow = mysql_fetch_object($mTimeZoneIDRes);
					
					if (is_numeric($mTimeZoneIDRow->id))
					{
						mysql_query("UPDATE resturants SET time_zone_id=".$mTimeZoneIDRow->id." WHERE id=".$mRest->id);
						$mSuccessFlag = 1;
					}
				}
			}
			
			if ($mCount%2==0)
			{
				$mStr .= "<tr>";
			}
			else
			{
				$mStr .= "<tr style='background-color: #B9D3EE;'>";
			}
			$mStr .= "<td style='width: 25%;'>".$mRest->name."</td>";
			$mStr .= "<td style='width: 35%;'>".$mRest->rest_address."</td>";
			$mStr .= "<td style='width: 15%;'>".$mRest->rest_city."</td>";
			$mStr .= "<td style='width: 10%;'>".$mRest->rest_state."</td>";
			$mStr .= "<td style='width: 15%;'>".$mTimeZoneName."</td>";
			$mStr .= "</tr>";
			$mCount+=1;
		}
		$mStr .= "<tr style='height: 20px;'><td colspan='5'></td></tr>";
		$mStr .= "</table>";
	}
}
?>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
	<form method="post" action="">
		<?=$mStr?>
		<input type="submit" value="Update Time Zones" style="width: 150px;" id="btnSubmit" name="btnSubmit" />
	</form>
</body>