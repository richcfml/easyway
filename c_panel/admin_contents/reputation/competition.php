<?php

include("nav.php");

$srid = $Objrestaurant->srid; 
?>

<div id="overview">
    <div class="tab-title">
        <div id="main_heading">
            <span>Competition</span>
        </div>
        <div class="clear"></div>
    </div>
</div>

<?php

$mCompError = "";
$srid = $Objrestaurant->srid;
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
    google.load('visualization', '1.0', {'packages': ['corechart']});
    google.setOnLoadCallback(drawChart);
    function drawChart()
    {
        var count = 0;
        count = parseInt(count);
        var data = jQuery.parseJSON('<?php print_r($mResult1) ?>');
        for (var i in data.data.services)
        {
            var data1 = new google.visualization.DataTable();
            data1.addColumn('string', 'Topping');
            data1.addColumn('number', 'Slices');
            var options1 = {
                pieSliceText : 'none',
                title: i,
                width: 700,
                height: 350,
                colors: ['#2e8ded' ,'#f48047', '#eae400', '#42c05c']};
            count++;

            data1.addRows([['Burger King ('+ Math.round(data.data.services[i].companyPercent) + '%)', Math.round(data.data.services[i].companyPercent)]]);
            for (var j in data.data.services[i].competitors)
            {
                data1.addRows([[data.data.services[i].competitors[j].competitorName + ' (' + Math.round(data.data.services[i].competitors[j].competitorPercent) + '%)', Math.round(data.data.services[i].competitors[j].competitorPercent)]]);
            }
            
            var chart1 = new google.visualization.PieChart(document.getElementById('PieChartforCompetitors' + count));
            chart1.draw(data1, options1);
        }


        if (count == 0)
        {
            $("#lnkCompDet").hide();
            $("#PieChartforCompetitor").text("No Data Available");
            $("#PieChartforCompetitor").css("vertical-align", "middle");
            $("#PieChartforCompetitor").css("margin-top", "160px");
            $("#PieChartforCompetitor").css("height", "160px");
        }
    }

</script>

<div class="graph-container first box-heading">
    <div class="graph-heading">
        <h3>Search Engine Share of Voice</h3>
        <div id="PieChartforCompetitors1" align="center"></div>
        <div id="PieChartforCompetitors2" align="center"></div>
        <div id="PieChartforCompetitors3" align="center"></div>
    </div>
</div>