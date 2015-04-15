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
    </head>
    <body style="background:#fff;">

        <div id="pagewrap">
            <?php include('header_a.php'); ?>
            <div class="ewo-container"><h3 class="blue center"> <strong>Our online ordering tools make it easy</strong><br/> for you to grow your business</h3><br clear="all"/></div>
            <br clear="all"/>
            <div class="feature-banner"> <p align="center"><img src="signup_images/features/laptop.png" width="90%" style="max-width:590px;"/></p></div>
            <div class="blue_con center">
                <div class="ewo-container"><h4><a href="sign_up.php" class="ewo-orange-btn"><strong>Get Started Today!</strong></a></h4><br></div>

            </div>

            <section id="ewo-features" class="ewo-row">
                <script src="signup_js/scrollme/bower.json"></script>
                <script src="signup_js/scrollme/jquery.scrollme.js"></script>
                <script src="signup_js/scrollme/jquery.scrollme.min.js"></script>
                <script src="signup_js/scrollme/scrollme.jquery.json"></script>
            </section>
        </div>
    <br clear="all"/>
<section id="ewo-home" class="ewo-row">
    <div class="ewo-container">

        <h3 class="blue center"> <strong>Powerful Tools Let You Maximize Revenue</strong></h3>
        <br clear="all"/><br clear="all"/>
        <div class="grid45 floatleft" >

            <div class=" scrollme animateme"
                 data-translatey="600"
                 data-when="enter"
                 data-from="0.5"
                 data-to="0"
                 data-crop="false"
                 data-opacity="0"
                 data-scale="1.5"
                 style="opacity: 1;
                 transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.3s"
                 <?php /* ?>  data-when="enter"
                   data-from="0.5"
                   data-to="0"
                   data-opacity="0"
                   data-translatex="850"
                   data-rotatez="60"<?php */ ?>
                 >
                <p align="center"><img src="signup_images/features/delivery_zone.png" width="250"/></p>
                <h3 class="blue center"> <strong>Customize Your Delivery Zones</strong></h3>

                <p align="center"><strong>Create your own delivery zones</strong>, <br/>down to street, with our customizable tools</p>
            </div>

        </div>
        <br clear="all"/>
        <div class="grid45 floatright">
            <div class="scrollme">
                <div class=" scrollme animateme"
                     data-translatey="600"
                     data-when="enter"
                     data-from="0.5"
                     data-to="0"
                     data-crop="false"
                     data-opacity="0"
                     data-scale="1.5"
                     style="opacity: 1;
                     transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.3s"
                     <?php /* ?>data-when="enter"
                       data-from="0.5"
                       data-to="0"
                       data-opacity="0"
                       data-translatex="-1050"
                       data-rotatez="60"<?php */ ?>>
                    <p align="center"><img src="signup_images/features/facebook.png" width="250"/></p>
                    <h3 class="blue center"> <strong>Get Orders via Facebook</strong></h3>

                    <p align="center"><strong>Generate revenue through Facebook!</strong> Allow customers<br/>
                        to place orders directly from <strong>your very own Facebook page app.</strong><br/>
                        We take care of the set-up!</p>
                </div>
            </div>
        </div>
        <br clear="all"/>
        <div class="grid45 floatleft">
            <div class="scrollme">
                <div class=" scrollme animateme"
                     data-translatey="700"
                     data-when="enter"
                     data-from="0.5"
                     data-to="0"
                     data-crop="false"
                     data-opacity="0"
                     data-scale="1.5"
                     style="opacity: 1;
                     transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.3s"
                     <?php /* ?>data-when="enter"
                       data-from="0.5"
                       data-to="0"
                       data-opacity="0"
                       data-translatex="850"
                       data-rotatez="60"<?php */ ?>>
                    <p align="center"><img src="signup_images/features/integrate.png" width="250"/></p>
                    <h3 class="blue center"> <strong>Intergrate with Your Business</strong></h3>

                    <p align="center">We work with any existing business model, no need to worry. <strong>Receive orders how you prefer,</strong> whether it's by email, fax or POS.<br/>
                        <strong>No hassle, no disruptions.</strong></p>
                </div>
            </div>
        </div>
        <br clear="all"/>
        <div class="grid45 floatright">
            <div class="scrollme">
                <div class=" scrollme animateme"
                     data-translatey="700"
                     data-when="enter"
                     data-from="0.5"
                     data-to="0"
                     data-crop="false"
                     data-opacity="0"
                     data-scale="1.5"
                     style="opacity: 1;
                     transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.3s"
                     <?php /* ?>data-when="enter"
                       data-from="0.5"
                       data-to="0"
                       data-opacity="0"
                       data-translatex="-1050"
                       data-rotatez="60"<?php */ ?>>
                    <p align="center"><img src="signup_images/features/customers.png" width="250"/></p>
                    <h3 class="blue center"> <strong>Collect Customer Data</strong></h3>

                    <p align="center">Get to know your customer base with our powerful order analytics.<br/>
                        Collect emails, view trends -<strong> learn the best ways to reach your customers.</strong></p>
                </div>
            </div>
        </div>
        <br clear="all"/>

        <div class="grid45 floatleft">
            <div class="scrollme">
                <div class=" scrollme animateme"
                     data-translatey="800"
                     data-when="enter"
                     data-from="0.5"
                     data-to="0"
                     data-crop="false"
                     data-opacity="0"
                     data-scale="1.5"
                     style="opacity: 1;
                     transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.3s"
                     <?php /* ?>data-when="enter"
                       data-from="0.5"
                       data-to="0"
                       data-opacity="0"
                       data-translatex="850"
                       data-rotatez="60"<?php */ ?>>
                    <p align="center"><img src="signup_images/features/reputation.png" width="250"/></p>
                    <h3 class="blue center"> <strong>Maintain Your Digital Rep</strong></h3>

                    <p align="center"><strong>Maximize exposure </strong>with updated listings and search engine friendly menus.</p>
                </div>
            </div>
        </div>
        <br clear="all"/>
        <div class="grid45 floatright">
            <div class="scrollme">
                <div class=" scrollme animateme"
                     data-translatey="900"
                     data-when="enter"
                     data-from="0.5"
                     data-to="0"
                     data-crop="false"
                     data-opacity="0"
                     data-scale="1.5"
                     style="opacity: 1;
                     transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.3s"
                     <?php /* ?>data-when="enter"
                       data-from="0.5"
                       data-to="0"
                       data-opacity="0"
                       data-translatex="-1050"
                       data-rotatez="60"<?php */ ?>>
                    <p align="center"><img src="signup_images/features/photos.png" width="250"/></p>
                    <h3 class="blue center"> <strong>Create Beautiful Menus</strong></h3>

                    <p align="center">Create gorgeous menus your customers can easily access.<strong> Include photos, identifiers, descriptions and up-sell items</strong> - all with a few simple clicks.</p>
                </div>
            </div>
        </div>

        <br clear="all"/>

        <div class="grid45 floatleft">
            <div class="scrollme">
                <div class=" scrollme animateme"
                     data-translatey="900"
                     data-when="enter"
                     data-from="0.5"
                     data-to="0"
                     data-crop="false"
                     data-opacity="0"
                     data-scale="1.5"
                     style="opacity: 1;
                     transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.3s"
                     <?php /* ?>data-when="enter"
                       data-from="0.5"
                       data-to="0"
                       data-opacity="0"
                       data-translatex="850"
                       data-rotatez="60"<?php */ ?>>
                    <p align="center"><img src="signup_images/features/loyalty.png" width="250"/></p>
                    <h3 class="blue center"> <strong>Build Customer Loyalty</strong></h3>

                    <p align="center">Build your restaurant's brand loyalty, by having customers order directly from you - over and over!<strong> Did we mention you get unlimited orders? No per-order fee.</strong></p>
                </div>
            </div>
        </div>
        <br clear="all"/>


        <div class="grid45 floatright">
            <div class="scrollme">
                <div class=" scrollme animateme"
                     data-translatey="900"
                     data-when="enter"
                     data-from="0.5"
                     data-to="0"
                     data-crop="false"
                     data-opacity="0"
                     data-scale="1.5"
                     style="opacity: 1;
                     transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.3s"
                     <?php /* ?>data-when="enter"
                       data-from="0.5"
                       data-to="0"
                       data-opacity="0"
                       data-translatex="-1350"
                       data-rotatez="60"<?php */ ?>>
                    <p align="center"><img src="signup_images/features/hours.png" width="250"/></p>
                    <h3 class="blue center"> <strong>Manage Store Hours</strong></h3>

                    <p align="center">Close your online store at a moment's notice with just one click.<strong> <br/>Never miss an order.</strong></p>
                </div>
            </div>
        </div>

        <br clear="all"/>
    </div>
</section>

<section id="" class="add_features">

    <div class="ewo-container">

        <h3 class=" center"> <strong>Additional Features</strong></h3>
        <br clear="all"/><br clear="all"/>
        <div class="grid33  floatleft scrollme animateme"  
             data-translatey="200"
             data-when="enter"
             data-from="0.5"
             data-to="0"
             data-crop="false"
             data-opacity="0"
             data-scale="1.5"
             style="opacity: 1;
             transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.3s">
            <p align="center" >
                <img src="signup_images/additionalfeatures/money.png" width="78"/><br clear="all"/>
            </p>

            <p align="center"><strong>Money Deposited in 1-2 Days</strong></p>
        </div>
        <div class="grid33 floatleft scrollme animateme"
             data-translatey="400"
             data-when="enter"
             data-from="0.5"
             data-to="0"
             data-crop="false"
             data-opacity="0"
             data-scale="1.5"
             style="opacity: 1;
             transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.4s">
            <p align="center"><img src="signup_images/additionalfeatures/cloud_control.png" width="78"/><br clear="all"/>
            </p>
            <p align="center"><strong>Cloud Control Panel</strong></p>
        </div>
        <div class="grid33 floatleft scrollme animateme"  
             data-translatey="500"
             data-when="enter"
             data-from="0.5"
             data-to="0"
             data-crop="false"
             data-opacity="0"
             data-scale="1.5"
             style="opacity: 1;
             transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.4s">
            <p align="center"><img src="signup_images/additionalfeatures/customer_support.png" width="78"/><br clear="all"/>
            </p>
            <p align="center"><strong>24/7 Customer Support</strong></p>
        </div>


        <br clear="all"/>
        <br clear="all"/>

        <br clear="all"/>
        <div class="grid33 floatleft scrollme animateme"
             data-translatey="300"
             data-when="enter"
             data-from="0.5"
             data-to="0"
             data-crop="false"
             data-opacity="0"
             data-scale="1.5"
             style="opacity: 1;
             transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.4s">
            <p align="center"><img src="signup_images/additionalfeatures/web_app.png" width="78"/><br clear="all"/>
            </p>
            <p align="center"><strong>Mobile Web App</strong></p>
        </div>
        <div class="grid33 floatleft scrollme animateme"
             data-translatey="400"
             data-when="enter"
             data-from="0.5"
             data-to="0"
             data-crop="false"
             data-opacity="0"
             data-scale="1.5"
             style="opacity: 1;
             transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.5s">

            <p align="center"><img src="signup_images/additionalfeatures/text_ordering.png" width="78"/><br clear="all"/>
            </p>
            <p align="center"><strong>Text Message Ordering</strong></p>
        </div>
        <div class="grid33 floatleft scrollme animateme"  
             data-translatey="500"
             data-when="enter"
             data-from="0.5"
             data-to="0"
             data-crop="false"
             data-opacity="0"
             data-scale="1.5"
             style="opacity: 1;
             transform: translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale3d(1, 1, 1);visibility: visible; -webkit-animation: fadeInUp 0.3s;" data-delay="0.5s">

            <p align="center"><img src="signup_images/additionalfeatures/coupons.png" width="78"/><br clear="all"/>
            </p>
            <p align="center"><strong>Custom Coupons</strong></p>
        </div>


        <br clear="all"/>
    </div>
    <br clear="all"/>
</section>
<section id="addfeatures" class="add_features" style="background:#fff; padding: 0px;">
    <div class="blue_con center" style="padding-bottom:40px;">
        <div class="ewo-container">

            <div class="grid70b floatleft">
                <h3>Don't Give Control to 3rd Party Sites!<br/> <strong>Stop Paying huge Fees - Start Increasing Sales.</strong></h3>
            </div> <div class="grid30 floatright"> <h4><a href="sign_up.php" class="ewo-orange-btn" style="padding: 15px 30px; line-height: 65px;">Sign Up Now!</a></h4></div>

            <br clear="all"/>
        </div>
    </div>
    <br clear="all"/> <br clear="all"/>
    <h3 style="color:#333;" class="center"><strong>Give us 2 minutes.</strong><br/> See all the great features you get!</h3>
    <br clear="all"/><br clear="all"/>
</section>
<div style="background:#000;">
    <iframe width="100%" height="600" src="//www.youtube.com/embed/VqpyGZzv1YE?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
    <br clear="all"/>
    <div class="grid33 floatleft" id="youvideo">
        <iframe width="100%" height="315" src="//www.youtube.com/embed/QeJ0MuIA3sA?rel=0&amp;controls=0" frameborder="0" allowfullscreen></iframe>    </div>
    <div class="grid33 floatleft" id="youvideo">
        <iframe width="100%" height="315" src="//www.youtube.com/embed/CP8WQWRcnOc?rel=0&amp;controls=0" frameborder="0" allowfullscreen></iframe>    </div>
    <div class="grid33 floatleft" id="youvideo">
        <iframe width="100%" height="315" src="//www.youtube.com/embed/b7x_x7YrZzk?rel=0&amp;controls=0" frameborder="0" allowfullscreen></iframe>    </div>
    <br clear="all"/>
</div>

<?php include('footer.php'); ?>
</body>
</html>
