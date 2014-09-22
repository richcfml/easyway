<?php
    
    include "nav.php";
    $mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/account/get/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1";
    $mCustomerIdentifier = 4513008;
    $mSrid = '';
    $mURL = $mURL . "&customerIdentifier=" . $mCustomerIdentifier;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $mURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $mResult = curl_exec($ch);
    curl_close($ch);
    unset($ch);
    $mResult = json_decode($mResult);
    //echo "<pre>";print_r($mResult->data);
    foreach ($mResult->data as $data) {
        $mSrid = $data->srid;
    }



    //Get Competators Data
    $mURL1 = "https://reputation-intelligence-api.vendasta.com/api/v2/competition/lookupShareOfVoice/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&customerIdentifier=" . $mCustomerIdentifier;
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $mURL1);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);

    $mResult1 = curl_exec($ch1);
    curl_close($ch1);
    unset($ch1);
    ?>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
      google.setOnLoadCallback(drawChart);

      function drawChart() {

        //var data = '<?//php print_r($mResult1)?>';
        var count=0;
        count = parseInt(count);
        var data= jQuery.parseJSON('<?php print_r($mResult1)?>');

        for (var i in data.data.services)
        {
            var data1 = new google.visualization.DataTable();
            data1.addColumn('string', 'Topping');
            data1.addColumn('number', 'Slices');
            var options = {'title':i,
                       'width':400,
                       'height':300};
            count++;

            $("#PieChartforCompetitors").append($('<div id=\'PieChartforCompetitors'+count+ '\' style="width: 30%;float: left;"></div>'));
            data1.addRows([['Burger King', Math.round(data.data.services[i].companyPercent)] ]);
            for (var j in data.data.services[i].competitors)
            {
               data1.addRows([
              [data.data.services[i].competitors[j].competitorName, Math.round (data.data.services[i].competitors[j].competitorPercent)]
            ]);
               // console.log(data.data.services[i].competitors[j].competitorPercent)
            }
            var chart = new google.visualization.PieChart(document.getElementById('PieChartforCompetitors'+count));
            chart.draw(data1, options);
        }
      }
    </script>

   	

        <div class="graph-container first box-heading" style="min-height: 395px;max-height: 1000px;">
               <div>
                   <div class="graph-heading">
                       <h3>Competition</h3>
                       <div id="PieChartforCompetitors"></div>
                    </div>
                </div>
           </div>
