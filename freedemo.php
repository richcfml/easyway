<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>EWO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="signup_css/style.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,700,600,300,800' rel='stylesheet' type='text/css'>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="signup_js/jquery.cookie.js"></script>
        <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script>
            $(document).ready(function ()
            {
                $('.btn').click(function ()
                {
                    if ($(this).attr('data-toggle') == 'hide')
                    {
                        $(this).attr('data-toggle', 'show');
                    } else
                    {
                        $(this).attr('data-toggle', 'hide');
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
            
                var checkthis = $.cookie('openDivFY');
                $('.exp').hide();
                //alert(checkthis);
                if (checkthis != null ) {
                    $('#'+checkthis.toLowerCase()).show();
                } else {
                    $('#sendmoney').show();
                }
        
        
                $(".explodeshow").click(function () {
                
                    //$.cookie('openDivFY', $(this).text().trim());
            
                    var idExplode = $(this).attr("class").split(" ");
                    var idExp = idExplode[1];
                    $('.exp').hide();
                    $("#"+idExp).show("fade", {}, 1000);
                });
            
                $("li").click(function(){
                    $('li').removeClass('selected');
                    $(this).addClass('selected');
                });
            });
    
        </script>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <style>
            .reseller_form{
                background:#fff;
                padding: 40px;
                box-shadow: 0 20px 20px #9bcfe4;
            }

            .reseller_form input{
                height: 50px !important;
                width:95%;
                margin:0 auto;
            }

            .reseller_form textarea{
                height: 245px !important;
                width:95%;
                margin:0 auto;
            }
            .reseller_form label{
                display:block;
            }

        </style>
    </head>
    <body style="background:#fff;">
        <div id="pagewrap">
            <?php include('header_a.php'); ?>
            <div class="blue_con center">
                <div class="ewo-container">
                    <h3><strong>Get your FREE Demo Today!</strong><br/>See all of the exciting features we offer.</h3>
                    <br clear="all"/>
                </div>
            </div>
            <br clear="all"/>
            <section id="ewo-pricing" class="ewo-row">
                <div class="ewo-container">
                    <br clear="all"/>
                    <div class="grid33  floatleft" >
                        <p align="center">
                            <img src="signup_images/15.png" width="235"><br clear="all">
                        </p>
                        <p align="center"><strong>15 Min.</strong></p>
                        <p align="center"><a class="cd-signup ewo-orange-btn" href="http://www.vcita.com/v/956a329f/online_scheduling?o=c2lkZWJhcl93aWRnZXQ%3D&s=http%3A%2F%2Fwww.easywayordering.net%2Fcontact-us%2F#/schedule" target="_blank" style="border: none;padding: 10px 20px; line-height: 65px; border-radius: 0px; font-size: 16px;"><strong>Have a Rep Call Me</strong></a></p>
                    </div>
                    <div class="grid33  floatleft" >
                        <p align="center">
                            <img src="signup_images/30.png" width="235"><br clear="all">
                        </p>
                        <p align="center"><strong>30 Min.</strong></p>
                        <p align="center"><a class="cd-signup ewo-orange-btn" href="http://www.vcita.com/v/956a329f/online_scheduling?o=c2lkZWJhcl93aWRnZXQ%3D&s=http%3A%2F%2Fwww.easywayordering.net%2Fcontact-us%2F#/schedule" target="_blank" style="border: none;padding: 10px 20px; line-height: 65px; border-radius: 0px;font-size: 16px;"><strong>Take a Live Tour</strong></a></p>
                    </div>
                    <div class="grid33  floatleft" >
                        <p align="center">
                            <img src="signup_images/1hr.png" width="235"><br clear="all">
                        </p>
                        <p align="center"><strong>Flexible Timeframe</strong></p>
                        <p align="center"><a class="cd-signup ewo-orange-btn" href="http://www.vcita.com/v/956a329f/online_scheduling?o=c2lkZWJhcl93aWRnZXQ%3D&s=http%3A%2F%2Fwww.easywayordering.net%2Fcontact-us%2F#/schedule" target="_blank" style="border: none;padding: 10px 20px; line-height: 65px; border-radius: 0px;font-size: 16px;"><strong>Have Rep Visit Me</strong></a></p>
                    </div>
                    <br clear="all"/>
                    <br clear="all"/>
                    <br clear="all"/>
                </div>
                <br clear="all"/>
                <h3 class="aligncenter" style="color:#565d67;"><strong> Already Have EasyWay Ordering </strong><br/>and Need Additional Training?</h3><br/>
                <br clear="all"/>
                <p align="center"><a class="cd-signup ewo-blue-btn" href="http://www.vcita.com/v/956a329f/online_scheduling?o=c2lkZWJhcl93aWRnZXQ%3D&s=http%3A%2F%2Fwww.easywayordering.net%2Fcontact-us%2F#/schedule" target="_blank" style="border: none;padding: 15px 20px; border-radius: 0px; font-size: 16px;" onclick="lightbox_open();"><strong>Schedule Additional Training</strong></a></p>
            </section>
            <br clear="all"/>
            <br clear="all"/>
            <br clear="all"/>
        </div>
        <script src="dist/spin.min.js"></script>
        <script src="dist/ladda.min.js"></script>
        <link rel="stylesheet" href="dist/ladda.min.css">
    <script>
        Ladda.bind( '.button-demo button', { timeout: 2000 } );

        // Bind progress buttons and simulate loading progress
        Ladda.bind( '.progress-demo button', {
            callback: function( instance ) {
                var progress = 0;
                var interval = setInterval( function() {
                    progress = Math.min( progress + Math.random() * 0.1, 1 );
                    instance.setProgress( progress );

                    if( progress === 1 ) {
                        instance.stop();
                        clearInterval( interval );
                    }
                }, 200 );
            }
        } );
    </script>
    <script>

        var _gaq=[['_setAccount','UA-11278966-1'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
    <?php include('footer.php'); ?>
</body>
</html>
