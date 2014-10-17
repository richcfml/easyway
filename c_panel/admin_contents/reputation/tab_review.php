 <?php
        include "nav.php";
        //Reviews Competators Data
        $mCustomerIdentifier = 4513008;
        $mURL2 = "https://reputation-intelligence-api.vendasta.com/api/v2/review/getStats/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&customerIdentifier=" . $mCustomerIdentifier;
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $mURL2);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);

        $mResult2 = curl_exec($ch2);
        curl_close($ch2);
        unset($ch2);
        //echo "<pre>";print_r(json_decode($mResult2));
        ?>
          <script type="text/javascript">
          var count = 0;
          count = parseInt(count);
          var total = 0;
          total = parseInt(total);
          google.load('visualization', '1.0', {'packages':['corechart']});
          google.setOnLoadCallback(drawChart);

          function drawChart() {
            var data= jQuery.parseJSON('<?php print_r($mResult2)?>');

            var data2 = new google.visualization.DataTable();
                data2.addColumn('string', 'Star');
                data2.addColumn('number', 'Reviews');
               
               var options1 = {'title':"",
                   'width':400,
                   'height':300,
                   legend: { position: "none" }
                   };
            for (var i in data.data.ratingCounts)
            {
                if(count<2)
                {
                    total+=data.data.ratingCounts[i];
                    data2.addRows([[i+' Star', data.data.ratingCounts[i]] ]);
                }

                if(count==2)
                {
                    
                    total = 0;
                    total=data.data.ratingCounts[i];
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
                    
                    total = 0;
                }
                //console.log(data.data.ratingCounts[i]);


                count++;

            }



            var chart1 = new google.visualization.BarChart(document.getElementById('BarChartforReviews'));
            chart1.draw(data2, options1);
          }

    </script>

            <div class="graph-container first box-heading">
               <div>
                   <div class="graph-heading">
                      <h3>Review</h3>
                    <div id="BarChartforReviews"></div>
                   </div>

                </div>
           </div>