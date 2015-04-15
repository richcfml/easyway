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
            a {
                text-decoration: none;

            }
            .pointer {
                color: #9b59b6;
                font-family: 'Pacifico', cursive;
                font-size: 30px;
                margin-top: 15px;
            }
            pre {
                margin: 0px auto;
            }
            pre code {
                padding: 35px;
                border-radius: 5px;
                font-size: 15px;
                background: rgba(0,0,0,0.1);
                border: rgba(0,0,0,0.05) 5px solid;
                max-width: 500px;
            }

            .main {
                float: left;
                width: 100%;
                margin: 0 auto;
                background: #fff;
            }

            .main h1 {
                padding:20px 50px;
                float: left;
                width: 100%;
                font-size: 90px;
                box-sizing: border-box;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                font-weight: 100;
                color: black;
                margin: 0;
                margin-top: 70px;
                font-family: 'Playfair Display';
                letter-spacing: -1px;
            }

            .main h1.demo1 {
                background: #1ABC9C;
            }

            .reload.bell {
                font-size: 12px;
                padding: 20px;
                width: 45px;
                text-align: center;
                height: 47px;
                border-radius: 50px;
                -webkit-border-radius: 50px;
                -moz-border-radius: 50px;
            }

            .reload.bell #notification {
                font-size: 25px;
                line-height: 140%;
            }


            .reload:hover{
                background: #A2261E;
            }

            .clear {
                width: auto;
            }
            .btn:hover, .btn:hover {
                background: rgba(0,0,0,0.8);
            }
            .btns {
                width: 410px;
                margin: 50px auto;
            }
            .credit {
                text-align: center;
                color: #444;
                padding: 10px;
                margin: 0 0 0 0;
                background: #161923;
                color: #FFF;
                float: left;
                width: 100%;
            }
            .credit a {
                color: #fff;
                text-decoration: none;
                font-weight: bold;
            }

            .back {
                position: absolute;
                top: 0;
                left: 0;
                text-align: center;
                display: block;
                padding: 7px;
                width: 100%;
                box-sizing: border-box;
                -moz-box-sizing: border-box;
                -webkit-box-sizing: border-box;
                background: rgba(255, 255, 255, 0.25);
                font-weight: bold;
                font-size: 13px;
                color: #000;
                -webkit-transition: all 200ms ease-out;
                -moz-transition: all 200ms ease-out;
                -o-transition: all 200ms ease-out;
                transition: all 200ms ease-out;
            }
            .back:hover {
                color: black;
                background: rgba(255, 255, 255, 0.5);
            }
            .page_container {
                max-width: 960px;
                margin: 0px auto;
            }
            .header {
                background: white;
                padding-top: 10px;
                margin-bottom: 0;
            }
            .header h1{
                margin-bottom: 0;
                font-size: 45px;
            }
            .header .menu {
                padding-bottom: 10px;
            }

            .benefits {
                color: black;
                height: 100px;
                background: #FFF;
                position: relative;
                width: 100%;
                padding: 25px;
                font-size: 40px;
                font-weight: 100;
                float: left;
                box-sizing: border-box;
                -moz-box-sizing: border-box;
                -webkit-box-sizing: border-box;
            }

            .benefits .page_container{
                margin-bottom: 50px;
                margin-top: 0;
            }

            .immersive_slider .is-slide .content h2{
                line-height: 140%;
                font-weight: 100;
                color: white;
                font-weight: 100;
            }
            .immersive_slider .is-slide .content a {
                color: white;
            }

            .immersive_slider .is-slide .content p{
                float: left;
                font-weight: 100;
                width: 100%;
                font-size: 17px;
                margin-top: 5px;
            }
            p{
                font-size: 16px;
                margin-left: 40px;
            }
        </style>
    </head>
    <body style="background:#fff;">
        <div id="pagewrap">
            <?php include('header_a.php'); ?>
            <div class="blue_con center">
                <div class="ewo-container">
                    <h3><strong>Our Privacy Policy</strong></h3>
                    <br clear="all"/></div>
            </div>
            <section id="ewo-features" class="ewo-row">
            </section>
        </div>
    </div>
    <br clear="all"/>
<section id="ewo-pricing" class="ewo-row">
    <div class="ewo-container" style="font-size:16px !important;">
        <div class="grid60 centermargin">
            <p align="center" style="margin-left:0px;">This Application collects some Personal Data from its Users.</p>
            <br/><br/>
            <h4 style="color:#f7941d;"><strong>Personal Data</strong></h4>
            <br/>
            <p>
                Data are collected for the following purposes and using the following services:
            </p>
            <p><strong>Analytics </strong></p>
            <p>
                Google Analytics</p>
            <p>Personal Data: Cookie and Usage Data
            </p>
            <br/>
            <p>
                <strong>Contacting the User</strong>
            </p>
            <p>
                Contact form</p>
            <p>Personal Data: Company name, Email, First Name, Last Name and Phone number
            </p>
            <br/>
            <p>
                <strong>Handling payments</strong>
            </p>

            <p>Authorize.Net
            </p>
            <p>Personal Data: Various types of Data
            </p>
            <br/>
            <p>
                <strong>Interaction with external social networks and platforms</strong>
            </p>
            <p>Facebook Like button and social widgets
            </p>
            <p>Personal Data: Cookie and Usage Data
            </p>
            <br/>
            <p>
                <strong>Remarketing and Behavioral Targeting</strong></p>
            <p>
                AdRoll</p>
            <p>Personal Data: Cookie and Usage Data</p>

            <br/><br/>
            <h4 style="color:#f7941d;"><strong>Contact</strong></h4><br/>
            <p> <strong>Data owner</strong></p><br/>
            <p>EasyWay INC
                <br/>50 Broad St  Suite 1701
                <br/>New York, NY 10004
                <br/>cwilliams@easywayordering.com</p>
        </div>
        <br clear="all"/>
    </div>
    <br clear="all"/>
</div>          
</section>
</div>
<script>

    var _gaq=[['_setAccount','UA-11278966-1'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
        g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php include('footer.php'); ?>
</body>
</html>
