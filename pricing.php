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
        <script type="text/javascript" src="jquery.immersive-slider.js"></script>
        <link href='immersive-slider.css' rel='stylesheet' type='text/css'>

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
        </style>
    </head>
    <body style="background:#fff;">
        <div id="pagewrap">
            <?php include('header_a.php'); ?>
            <div class="blue_con center">
                <div class="ewo-container">
                    <h3><strong>Pick the plan and pricing</strong><br/>
                        that works for you and your restaurant</h3>
                    <br clear="all"/></div>
            </div>
            <section id="ewo-features" class="ewo-row">
                <script src="signup_js/scrollme/bower.json"></script>
                <script src="signup_js/scrollme/jquery.scrollme.js"></script>
                <script src="signup_js/scrollme/jquery.scrollme.min.js"></script>
                <script src="signup_js/scrollme/scrollme.jquery.json"></script>
            </section>
        </div>
    </div>
    <br clear="all"/>
<section id="ewo-pricing" class="ewo-row">
    <div class="ewo-container">
        <div class="grid70" style="margin:0 auto;">
            <div class="grid50 floatleft">
                <div class="pricing_box">
                    <div class="pricing_blue">
                        <h3 class="center" ><strong style="color:#fff !important;">BASIC</strong></h3>
                        <hr/> <br clear="all"/>
                        <p style="font-weight:normal;"><sup>$</sup>69<sub>mo.</sub></p>
                    </div>
                    <div class="pricing_triangle">
                        <p  class="center" ><em>	$179 set up free</em></p>
                    </div>
                    <h5 class="center"><a href="sign_up.php?plan=basic" class="ewo-blue-btn" style="padding: 15px 30px; line-height: 65px;">GET STARTED</a></h5>
                    <hr/>
                    <div class="grid60 centermargin">
                        <p> <strong>FREE</strong> trial period</p>
                        <p> <strong>Convenient</strong> flat fee</p>
                        <p> <strong>Orders</strong> via fax, POS, email</p>
                        <p><strong>Unlimited</strong> orders</p>
                        <p> <strong>Confirmation</strong> phone calls</p>
                        <p> <strong>Mobile</strong>-ready site</p>
                        <p> <strong>Powerful</strong> order analytics</p>
                        <p> <strong>Credit card</strong> integration</p>
                        <p> <strong>E-coupon</strong> creation</p>
                        <p> <strong>Facebook</strong> ordering</p>
                        <p> <strong>Cloud</strong> control panel           </p>
                        <p>Free Manager's Tablet *</p>
                    </div>
                    <br clear="all"/>
                </div>
            </div>
            <div class="grid50 floatleft">
                <div class="pricing_box">
                    <div class="pricing_blue1">
                        <h3 class="center"><strong style="color:#fff !important;">PREMIUM</strong></h3>
                        <div class="pricing_orange">
                            <img src="signup_images/pricing_orange_most_popular.png" style=" margin-left:-2px;"/>
                        </div>
                        <p style="margin-top:15px; font-weight:normal;"><sup>$</sup>129<sub>mo.</sub></p>
                    </div>
                    <div class="pricing_triangle1">
                        <p  class="center" style="padding-top: 10px;"><em>	$179 set up free</em></p>
                        <br clear="all"/>
                    </div>
                    <h5 class="center"><a href="sign_up.php?plan=premium" class="ewo-blue-btn" style="padding: 15px 30px; line-height: 65px;">GET STARTED</a></h5>
                    <div class="grid60 centermargin">
                        <hr/>
                        <p> <strong>FREE</strong> trial period</p>
                        <p> <strong>Convenient</strong> flat fee</p>
                        <p> <strong>Orders</strong> via fax, POS, email</p>
                        <p><strong>Unlimited</strong> orders</p>
                        <p> <strong>Confirmation</strong> phone calls</p>
                        <p> <strong>Mobile</strong>-ready site</p>
                        <p> <strong>Powerful</strong> order analytics</p>
                        <p> <strong>Credit card</strong> integration</p>
                        <p> <strong>E-coupon</strong> creation</p>
                        <p> <strong>Facebook</strong> ordering</p>
                        <p> <strong>Cloud</strong> control panel           </p>
                        <p>Free Manager's Tablet *</p>
                        <p class="bluefont">Online reputation management</p>
                        <p  class="bluefont">Text message ordering</p>
                        <p  class="bluefont">Review boost</p>
                        <p class="bluefont">PowerListings™ via Yext</p>
                    </div>
                    <br clear="all"/>
                </div>
                <br clear="all"/>
            </div>
            <br clear="all"/>
            <p style="width: 213px;float: right;"><em>* With one year commitment</em></p>
        </div>
        <br clear="all"/>
    </div>
    <br clear="all"/>
</div>          
</section>

<section id="addfeatures" class="add_features" style="background:#fff; padding: 0px;">
    <div class="blue_con center" style="padding-bottom:40px;">
        <div class="ewo-container">

            <div class="grid70b floatleft">
                <h3><strong>Taking control of your business is easy</strong>.<br/> The benefits are huge, so why not?</h3>
            </div> <div class="grid30 floatright"> <h4><a href="sign_up.php" class="ewo-orange-btn" style="padding: 15px 30px; line-height: 65px;">Get Started Today</a></h4></div>

            <br clear="all"/>
        </div>
    </div>
    <br clear="all"/> <br clear="all"/>
    <h3 style="color:#25aae1;" class="center"><strong>What People Are Saying</strong></h3>
    <p align="center" style="font-size: 14px !important;"> See what members of our EasyWay Ordering family are saying about us.<br clear="all"/><br clear="all"/>
    </p>
    <br clear="all"/>
    <div class="ewo-container">
        <div class="main">
            <div class="page_container">
                <div id="immersive_slider">
                    <div class="slide"  data-blurred="signup_img/slide2_blurred.jpg">
                        <div class="image" >
                            <img src="signup_images/demovideos/zaytoons.png" alt="Slider 1" width="250">
                        </div>
                        <div class="content">
                            <h2><strong>"The customer orders directly from my website. There's more interaction with the customer."</strong></h2>
                            <p align="right" style="font-size: 14px !important;">
                                <strong style="color:#25aae1;">- Ahmad Samhan,</strong> <strong>Owner, Zaytoons</strong>
                            </p>
                        </div>
                    </div>
                    <div class="slide"  data-blurred="signup_img/slide2_blurred.jpg">
                        <div class="image" style="float:right;">
                            <img src="signup_images/demovideos/nickbistro.png" alt="Slider 1" width="250" align="right">
                        </div>
                        <div class="content" style=" float:left;">
                            <h2><strong>"EasyWay had a great track record - the customer service is great!"</strong></h2>
                            <p align="right" style="font-size: 14px !important;">
                                <strong style="color:#25aae1;">- Alfred Vitsentzos,</strong> <strong>Nick's Bistro</strong>
                            </p>
                        </div>
                    </div>
                    <div class="slide"  data-blurred="signup_img/slide2_blurred.jpg">
                        <div class="image">
                            <img src="signup_images/demovideos/fell.png" alt="Slider 1" width="250">
                        </div>
                        <div class="content">
                            <h2><strong>"It makes it easier for everybody- I'd definitely recommend it."</strong></h2>
                            <p align="right" style="font-size: 14px !important;">
                                <strong style="color:#25aae1;">- Fell,</strong> <strong>The Squeeze</strong>
                            </p>
                        </div>
                    </div>
                    <!-- <a href="#" class="is-prev">&laquo;</a>
                     <a href="#" class="is-next">&raquo;</a>-->
                </div>
            </div>
        </div>
    </div>
    <br clear="all"/>
</section>
<script type="text/javascript">
    $(document).ready( function() {
        $("#immersive_slider").immersive_slider({
            container: ".main"
        });
    });

</script>
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
