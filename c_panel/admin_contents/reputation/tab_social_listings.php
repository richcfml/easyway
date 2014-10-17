<?php

include("nav.php");

$srid = $Objrestaurant->srid; 
?>

<div id="overview">
    <div class="tab-title">
        <div id="main_heading">
            <span>Social</span>
        </div>
        <div class="clear"></div>
    </div>
</div>

<?php
	$mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/social/getStats/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=" . $srid;
        $mCh = curl_init();
	curl_setopt($mCh, CURLOPT_URL, $mURL);
	curl_setopt($mCh, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($mCh, CURLOPT_SSL_VERIFYPEER, 0);
	
	$mResult = curl_exec($mCh);
	curl_close($mCh);
	unset($mCh);
	$mResult = json_decode($mResult);
	$mResult = (object) $mResult;
        
        // Facebook Stats
	 $mFacebookArray = $mResult->data['facebook']['0']['stats'];
        ksort($mFacebookArray);
        $minLikes = min($mFacebookArray) == 0 ? 0 : min($mFacebookArray) - 10;
        $maxLikes = max($mFacebookArray) == 0 ? 0 : max($mFacebookArray) + 10;
        $likes = number_format(end($mFacebookArray));
        $mFacebookArray= json_encode($mFacebookArray);
        
        // Twitter Stats
	 $mTwitterArray = $mResult->data['twitter']['0']['stats'];
        ksort($mTwitterArray);
        $minFollowers = min($mTwitterArray) == 0 ? 0 : min($mTwitterArray) - 10;
        $maxFollowers = max($mTwitterArray) == 0 ? 0 : max($mTwitterArray) + 10;
        $followers = number_format(end($mTwitterArray));
        $mTwitterArray= json_encode($mTwitterArray);
        
        // Foursquare Stats
	 $mFoursquareArray = $mResult->data['foursquare']['0']['stats'];
	 ksort($mFoursquareArray);
        $minCheck = min($mFoursquareArray) == 0 ? 0 : min($mFoursquareArray) - 10;
        $maxCheck = max($mFoursquareArray) == 0 ? 0 : max($mFoursquareArray) + 10;
        $check_in = number_format(end($mFoursquareArray));
        $mFoursquareArray= json_encode($mFoursquareArray);
?>

<script type="text/javascript">
    
    
    google.load('visualization', '1.0', {'packages':['corechart']});
    google.setOnLoadCallback(drawChart);
    
    function drawChart() 
    {
            var monthNames = [ "","Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Date');
            data.addColumn('number', 'Page Likes');
            var options = {
                width: 750, 
                height: 250, 
                legend: { 
                    position: 'top', 
                    maxLines: 3 
                }, 
                bar: { 
                    groupWidth: '20%' 
                }, 
                isStacked: true,
                vAxis: {
                    viewWindowMode:'explicit',
                    viewWindow:{
                        max:<?=$maxLikes; ?>,
                        min:<?=$minLikes; ?>
                    }
                }
            };
            var count = 0;
            var record =<?php print($mFacebookArray) ?>;
            for(var i in record)
            {
                    count++;
                    var month = i.substring(5, 7);
                    var mName = monthNames[parseInt(month)];
                    var day = i.substring(8,10);
                    console.log(i);
                    console.log(day);
                    var publishDate = mName + ' '+ day;
                    data.addRow([publishDate.toString(),parseInt(record[i])]);
            }

            var visualization = new google.visualization.AreaChart(document.getElementById('ColumnChartforFacebook'));
            visualization.draw(data, options);
            google.load("visualization", "1", {packages: ["corechart"]});

            if (count==0)
            {
                    $("#ColumnChartforFacebook").text("No Data Available");
                    $("#ColumnChartforFacebook").css("vertical-align", "middle");
                    $("#ColumnChartforFacebook").css("margin-top", "160px");					
                    $("#ColumnChartforFacebook").css("height", "160px");
            }
            
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Date');
            data.addColumn('number', 'Followers');
            var options = {
                width: 750, 
                height: 250, 
                legend: { 
                    position: 'top', 
                    maxLines: 3 
                }, 
                bar: { 
                    groupWidth: '20%' 
                }, 
                isStacked: true, 
                vAxis: { 
                    viewWindowMode:'explicit',
                    viewWindow:{
                        max:<?=$maxFollowers?>,
                        min:<?=$minFollowers?>
                    }
                }
            };
            var count = 0;
            var record =<?php print($mTwitterArray) ?>;
            for(var i in record)
            {
                    count++;
                    var month = i.substring(5, 7);
                    var mName = monthNames[parseInt(month)];
                    var day = i.substring(8,10);
                    console.log(i);
                    console.log(day);
                    var publishDate = day + ' '+ mName;
                    data.addRow([publishDate.toString(),parseInt(record[i])]);
            }

            var visualization = new google.visualization.AreaChart(document.getElementById('ColumnChartforTwitter'));
            visualization.draw(data, options);
            google.load("visualization", "1", {packages: ["corechart"]});

            if (count==0)
            {
                    $("#ColumnChartforTwitter").text("No Data Available");
                    $("#ColumnChartforTwitter").css("vertical-align", "middle");
                    $("#ColumnChartforTwitter").css("margin-top", "160px");					
                    $("#ColumnChartforTwitter").css("height", "160px");
            }
            
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Date');
            data.addColumn('number', 'Check-Ins');
            var options = {
                width: 750, 
                height: 250, 
                legend: { 
                    position: 'top', maxLines: 3 
                }, 
                bar: { 
                    groupWidth: '20%' 
                }, 
                isStacked: true, 
                vAxis: { 
                    viewWindowMode:'explicit',
                    viewWindow:{
                        max:<?=$maxCheck?>,
                        min:<?=$minCheck?>
                    }
                }
            };
            var count = 0;
            var record =<?php print($mFoursquareArray) ?>;
            for(var i in record)
            {
                    count++;
                    var month = i.substring(5, 7);
                    var mName = monthNames[parseInt(month)];
                    var day = i.substring(8,10);
                    console.log(i);
                    console.log(day);
                    var publishDate = day + ' '+ mName;
                    data.addRow([publishDate.toString(),parseInt(record[i])]);
            }

            var visualization = new google.visualization.AreaChart(document.getElementById('ColumnChartforFoursquare'));
            visualization.draw(data, options);
            google.load("visualization", "1", {packages: ["corechart"]});

            if (count==0)
            {
                    $("#ColumnChartforFoursquare").text("No Data Available");
                    $("#ColumnChartforFoursquare").css("vertical-align", "middle");
                    $("#ColumnChartforFoursquare").css("margin-top", "160px");					
                    $("#ColumnChartforFoursquare").css("height", "160px");
            }
    }

</script>

<div class="v-box-heading">
    <h2>
        <span class="icon32-sourceId-10050"></span>
        <span class="social-stat-header">Facebook Stats</span>

        <select class="select-social-service">
            <option value="<?=$mResult->data['facebook']['0']['name']?>"><?=$mResult->data['facebook']['0']['name']?></option>
        </select>

    </h2>
    <div class="base-table striped">
        <table>
            <thead>
                <tr>
                    <th class="table-2-col">Monitored Item</th>
                    <th class="table-3-col">Total Likes</th>
                    <th class="table-7-col-last">Historical Progress</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span>Page Likes</span>
                    </td>
                    <td>
                        <span class="your-total"><?=$likes?></span>
                    </td>
                    <td>
                        <div id="ColumnChartforFacebook"></div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</div>

<div class="v-box-heading">
    <h2>
        <span class="icon32-sourceId-10060"></span>
        <span class="social-stat-header">Twitter Stats</span>

        <select class="select-social-service">

            <option value="<?=$mResult->data['twitter']['0']['twitterUserId']?>"><?=$mResult->data['twitter']['0']['name']?></option>

        </select>
    </h2>
    
    <div class="base-table striped" >
        <table>
            <thead>
                <tr>
                    <th class="table-2-col">Monitored Item</th>
                    <th class="table-3-col">Followers</th>
                    <th class="table-7-col-last">Historical Progress</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span>Followers</span>
                    </td>
                    <td>
                        <span class="your-total"><?=$followers?></span>
                    </td>
                    <td>
                        <div id="ColumnChartforTwitter"></div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</div>


<div class="v-box-heading">
    <h2>
        <span class="icon32-sourceId-10080"></span>
        <span class="social-stat-header">Foursquare Stats</span>

        <select class="select-social-service" >

            <option value="<?=$mResult->data['foursquare']['0']['foursquareVenueId']?>"><?=$mResult->data['foursquare']['0']['name']?></option>

        </select>

    </h2>
    <div class="base-table striped">
        <table>
            <thead>
                <tr>
                    <th class="table-2-col">Monitored Item</th>
                    <th class="table-3-col">Check Ins</th>
                    <th class="table-7-col-last">Historical Progress</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span>Check-Ins</span>
                    </td>
                    <td>
                        <span class="your-total"><?=$check_in?></span>
                    </td>
                    <td>
                        <div id="ColumnChartforFoursquare"></div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</div>
