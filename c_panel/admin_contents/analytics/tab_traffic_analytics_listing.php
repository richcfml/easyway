<?
//	ini_set("display_errors", 1);
	//$resturant_id = 210;
	$analytics = mysql_query("SELECT * FROM analytics WHERE resturant_id=$resturant_id");
	if(!empty($analytics) && mysql_num_rows($analytics) > 0) {
		$analytics = mysql_fetch_object($analytics);
?>
		<style type="text/css">
		
			table#analytics {
				border: 1px solid #d0d0d0;
				border-radius: 5px;
				margin-bottom: 20px;
				width: 275px;
			}
			table#analytics th {
				background-color: #e0e0e0;
				text-align: left;
				font-size: 14px;
			}
			table#analytics tr {
				background-color: #ffffff !important;
			}
			table#analytics .title {
				font-weight: bold;
			}
		</style>
		<table id="analytics" cellpadding="10px" style="width: 60%; border-width: 0;">
			<tr>
				<td style="vertical-align: top;">
					<table cellpadding="10px" id="analytics" cellspacing="0">
						<thead>
							<tr>
								<th colspan="2">Mobile vs Desktop</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="title">Mobile</td>
								<td><? echo $analytics->mobile_traffic_count; ?></td>
							</tr>
							<tr>
								<td class="title">Desktop</td>
								<td><? echo $analytics->desktop_traffic_count; ?></td>
							</tr>
						</tbody>
					</table>
					
					<table cellpadding="10px" id="analytics" cellspacing="0">
						<thead>
							<tr>
								<th colspan="2">Facebook</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="title">Visits</td>
								<?php
								$mFaceBookVisits = "Not available";
								$mResult = mysql_query("SELECT COUNT(*) AS VisitCount FROM facebookvisits WHERE RestaurantID=".$resturant_id);
								if (mysql_num_rows($mResult)>0)
								{
									$mRow = mysql_fetch_object($mResult);
									if (is_numeric($mRow->VisitCount))
									{
										$mFaceBookVisits = $mRow->VisitCount;
									}
								}
								?>
								<td><?=$mFaceBookVisits?></td>
							</tr>
						</tbody>
					</table>
					
					<table cellpadding="10px" id="analytics" cellspacing="0">
						<thead>
							<tr>
								<th colspan="3">Referring Traffic Sources</th>
							</tr>
							<tr>
								<td class="title">#</td>
								<td class="title">Referral Source</td>
								<td class="title">Visits</td>
							</tr>
						</thead>
						<tbody>
							<?
								$sources = json_decode(stripslashes($analytics->referring_traffic_sources));
								if(count($sources) > 0 ) {
									$count = 1;
									foreach($sources as $key => $source) {
							?>
										<tr>
											<td><? echo $count++; ?></td>
											<td><? echo $source[0]; ?></td>
											<td><? echo $source[1]; ?></td>
										</tr>
							<?
									}
								} else {
									echo '<tr><td colspan="3" align="center">No data to display.</td></tr>';
								}
							?>
						</tbody>
					</table>
					
					<table cellpadding="10px" id="analytics" cellspacing="0">
						<thead>
							<tr>
								<th colspan="4">Social Media Referrals</th>
							</tr>
							<tr>
								<td class="title">#</td>
								<td class="title">isSocial</td>
								<td class="title">Source</td>
								<td class="title">Visits</td>
							</tr>
						</thead>
						<tbody>
							<?
								$keywords = json_decode(stripslashes($analytics->social_media_referrals));
								if(count($keywords) > 0 ) {
									$count = 1;
									foreach($keywords as $key => $keyword) {
							?>
										<tr>
											<td><? echo $count++; ?></td>
											<td><? echo $keyword[0]; ?></td>
											<td><? echo $keyword[1]; ?></td>
											<td><? echo $keyword[2]; ?></td>
										</tr>
							<?		}
								} else {
									echo '<tr><td colspan="4" align="center">No data to display.</td></tr>';
								}
							?>
						</tbody>
					</table>
				</td>
				<td style="vertical-align: top;">
					<table cellpadding="10px" id="analytics" cellspacing="0">
						<thead>
							<tr>
								<th colspan="2">Average Time vs Bounce Rate</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="title">Average Time</td>
								<td><? echo gmdate("H:i:s", $analytics->average_time_on_site); ?></td>
							</tr>
							<tr>
								<td class="title">Bounce Rate</td>
								<td><? echo $analytics->bounce_rate; ?>%</td>
							</tr>
						</tbody>
					</table>
					
					<table cellpadding="10px" id="analytics" cellspacing="0">
						<thead>
							<tr>
								<th colspan="3">Referring Keywords</th>
							</tr>
							<tr>
								<td class="title">#</td>
								<td class="title">Keywords</td>
								<td class="title">Visits</td>
							</tr>
						</thead>
						<tbody>
							<?
								$keywords = json_decode(stripslashes($analytics->referring_keywords_by_search_engine));
								if(count($keywords) > 0 ) {
									$count = 1;
									foreach($keywords as $key => $keyword) {
							?>
										<tr>
											<td><? echo $count++; ?></td>
											<td><? echo $keyword[0]; ?></td>
											<td><? echo $keyword[1]; ?></td>
										</tr>
							<?
									}
								} else {
									echo '<tr><td colspan="3" align="center">No data to display.</td></tr>';
								}
							?>
						</tbody>
					</table>
				</td>
			</tr>
		</table>

		<br style="clear:both;" />
<?
	}
?>