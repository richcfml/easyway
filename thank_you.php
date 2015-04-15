<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>EWO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="signup_css/style.css" rel="stylesheet">
        <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
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
    </head>
    <body style="background:#fff;">
        <div id="pagewrap">
            <style>
                .sign_up{

                    width: 100%;
                    background-size:100%;

                    margin:0 auto;
                }

                .sign_up form{
                    width: 90%;
                    margin:0 auto 0 auto;

                }
                select{
                    background:url(images/select_down.png) right center no-repeat;
                    height: 50px;
                    margin: 0  1%;
                    width: 90%;max-width: 330px;
                }
                .sign_up form input
                {
                    width: 90%;
                    padding:0;

                    max-width: 330px;
                    height: 50px;
                    margin: 0  1%;
                    background:#e5e7e9;
                    font-size:20px;
                    border: 1px solid #CFCFCF;
                    box-shadow: inset 1px 1px 4px #CDCDCD;
                }

                .sign_up h3{

                    color: #25aae1;
                }
                .sign_up .radio{
                    background: none;
                    float:left;
                    height:25px !important;
                    width: 25px !important;
                    line-height:25px;
                    box-shadow: none;
                }
                .sign_up form .login {
                    width: 100%;
                    height: 50px;
                    max-width: 380px;
                    margin: 0 auto;
                    border-radius:5px;
                    -moz-border-radius:5px;
                    -webkit-border-radius:5px;
                    font-size:30px;
                    border: 1px solid #238CA5;

                }
                .sign_up .orange_con,  .sign_up .blue_con,{

                    font-weight: 600;
                    font-size:18px !important;
                }

                /*form styles*/
                #msform {
                    width: 100%;
                    margin: 50px auto;
                    text-align: center;
                    position: relative;
                    height:auto;
                    clear:both;box-shadow: 0 0 2px 0px #999;
                    overflow:hidden;
                    min-height: 300px;
                }
                #msform fieldset {
                    background: white;
                    border: 0 none;

                    padding-top:50px;
                    box-sizing: border-box;
                    width: 100%;
                    /*stacking fieldsets above each other*/
                    position: relative;
                }

                #msform fieldset h3{
                    color:#25aae1;
                }
                /*Hide all except first fieldset*/
                #msform fieldset:not(:first-of-type) {
                    display: none;
                }
                /*inputs*/
                #msform input, #msform textarea {
                    padding: 15px;
                    border: 1px solid #ccc;
                    border-radius: 3px;
                    margin-bottom: 10px;
                    width: 100%;
                    box-sizing: border-box;

                    color: #2C3E50;
                    font-size: 13px;
                }
                /*buttons*/
                #msform .action-button {
                    width: 100px;
                    background: #25aae1;
                    font-weight: bold;
                    color: white;
                    border: 0 none;
                    border-radius: 1px;
                    cursor: pointer;
                    padding: 10px 5px;
                    margin: 10px 5px;
                }

                #msform .action-button.previous {
                    width: 100px;
                    background: #f7941d;
                    font-weight: bold;
                    color: white;
                    border: 0 none;
                    border-radius: 1px;
                    cursor: pointer;
                    padding: 10px 5px;
                    margin: 10px 5px;
                }
                #msform .action-button:hover, #msform .action-button:focus {
                    box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
                }
                /*headings*/
                .fs-title {
                    font-size: 15px;

                    color: #636e75;

                }
                .fs-subtitle {
                    font-weight: normal;
                    font-size: 13px;
                    color: #666;

                }
                /*progressbar*/
                #progressbar {
                    margin:0;
                    width:100%;
                    padding:0;
                    overflow: hidden;
                    /*CSS counters to number the steps*/
                    counter-reset: step;

                }
                #progressbar li {
                    list-style-type: none;
                    color: #25aae1;
                    padding-top: 10px;
                    padding-bottom: 10px;
                    font-size: 16px;
                    width: 20%;
                    float: left;
                    position: relative;
                    background: #f0f0f0;
                    box-shadow: 0 0 2px 0px #999;
                }

                /*progressbar connectors*/


                /*marking active/completed steps green*/
                /*The number of the step and the connector before it = green*/
                #progressbar li.active {
                    background: #25aae1;
                    color: white;
                    box-shadow: 0 0 2px 0px #fff;
                }


                .cmn-toggle {
                    position: absolute;
                    margin-left: -9999px;
                    visibility: hidden;
                }
                .cmn-toggle + label {
                    display: block;
                    position: relative;
                    cursor: pointer;
                    outline: none;
                    user-select: none;
                }
                input.cmn-toggle-round + label {
                    padding: 2px;
                    width: 90px;
                    margin:0 auto;
                    height: 30px;
                    background-color: #dddddd;
                    border-radius: 30px;
                }
                input.cmn-toggle-round + label:before,
                input.cmn-toggle-round + label:after {
                    display: block;
                    position: absolute;
                    top: 1px;
                    left: 1px;
                    bottom: 1px;
                    content: "";
                }
                input.cmn-toggle-round + label:before {
                    right: 1px;
                    background:url(images/no.png) center center no-repeat #f1f1f1;
                    border-radius: 30px;
                    transition: background 0.4s;
                }
                input.cmn-toggle-round + label:after {
                    width: 28px;
                    background-color: #fff;
                    border-radius: 100%;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
                    transition: margin 0.4s;
                }
                input.cmn-toggle-round:checked + label:before {
                    background: url(images/yes.png) 20px center no-repeat #25aae1 ;
                }
                input.cmn-toggle-round:checked + label:after {
                    margin-left: 60px;
                }
            </style>


            <script>
                var form = $("#example-advanced-form").show();
 
                form.steps({
                    headerTag: "h3",
                    bodyTag: "fieldset",
                    transitionEffect: "slideLeft",
                    onStepChanging: function (event, currentIndex, newIndex)
                    {
                        // Allways allow previous action even if the current form is not valid!
                        if (currentIndex > newIndex)
                        {
                            return true;
                        }
                        // Forbid next action on "Warning" step if the user is to young
                        if (newIndex === 3 && Number($("#age-2").val()) < 18)
                        {
                            return false;
                        }
                        // Needed in some cases if the user went back (clean up)
                        if (currentIndex < newIndex)
                        {
                            // To remove error styles
                            form.find(".body:eq(" + newIndex + ") label.error").remove();
                            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                        }
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    },
                    onStepChanged: function (event, currentIndex, priorIndex)
                    {
                        // Used to skip the "Warning" step if the user is old enough.
                        if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
                        {
                            form.steps("next");
                        }
                        // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                        if (currentIndex === 2 && priorIndex === 3)
                        {
                            form.steps("previous");
                        }
                    },
                    onFinishing: function (event, currentIndex)
                    {
                        form.validate().settings.ignore = ":disabled";
                        return form.valid();
                    },
                    onFinished: function (event, currentIndex)
                    {
                        alert("Submitted!");
                    }
                }).validate({
                    errorPlacement: function errorPlacement(error, element) { element.before(error); },
                    rules: {
                        confirm: {
                            equalTo: "#password-2"
                        }
                    }
                });</script>
            <?php include('header_a.php'); ?>
            <div class="blue_con center">
                <div class="ewo-container">
                    <h3><strong>Be a part of the online ordering revolution!</strong><br/>Offer added value to your clients</h3>
                    <br clear="all"/></div>
            </div>
            <section id="sign_up" class="ewo-row">
                <div class="ewo-container">
                    <div class="sign_up">
                        <br clear="all"/>
                        <br clear="all"/>
                        <h3 style="text-align:center;"><strong>Thank You!</strong></h3>
                        <p align="center" class="bluefont"> <strong>Your Information has been received, and someone will contact you shortly.</strong></p>
                        <p align="center" class="bluefont"><strong>In the meantime, have you checked out our demo video?</strong></p>
                        <br clear="all"/>
                        <div style="background:#f0f0f0; width:80%; margin:0 auto; padding: 2%; box-shadow: #999 1px 1px 1px ; border-radius: 5px;">
                            <iframe width="100%" height="600" src="//www.youtube.com/embed/VqpyGZzv1YE?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <!-- multistep form -->
                        <br clear="all"/>
                        <!-- jQuery -->
                        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
                        <!-- jQuery easing plugin -->
                        <script src="signup_js/jquery.easing.min.js" type="text/javascript"></script>
                        <script>
                            $(function() {
                    
                                //jQuery time
                                var current_fs, next_fs, previous_fs; //fieldsets
                                var left, opacity, scale; //fieldset properties which we will animate
                                var animating; //flag to prevent quick multi-click glitches
                    
                                $(".next").click(function(){
                                    if(animating) return false;
                                    animating = true;
                        
                                    current_fs = $(this).parent();
                                    next_fs = $(this).parent().next();
                        
                                    //activate next step on progressbar using the index of next_fs
                                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                        
                                    //show the next fieldset
                                    next_fs.show();
                                    //hide the current fieldset with style
                                    current_fs.animate({opacity: 0}, {
                                        step: function(now, mx) {
                                            //as the opacity of current_fs reduces to 0 - stored in "now"
                                            //1. scale current_fs down to 80%
                                            scale = 1 - (1 - now) * 0.2;
                                            //2. bring next_fs from the right(50%)
                                            left = (now * 50)+"%";
                                            //3. increase opacity of next_fs to 1 as it moves in
                                            opacity = 1 - now;
                                            current_fs.css({'transform': 'scale('+scale+')'});
                                            next_fs.css({'left': left, 'opacity': opacity});
                                        },
                                        duration: 800,
                                        complete: function(){
                                            current_fs.hide();
                                            animating = false;
                                        },
                                        //this comes from the custom easing plugin
                                        easing: 'easeInOutBack'
                                    });
                                });
                    
                                $(".previous").click(function(){
                                    if(animating) return false;
                                    animating = true;
                        
                                    current_fs = $(this).parent();
                                    previous_fs = $(this).parent().prev();
                        
                                    //de-activate current step on progressbar
                                    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
                        
                                    //show the previous fieldset
                                    previous_fs.show();
                                    //hide the current fieldset with style
                                    current_fs.animate({opacity: 0}, {
                                        step: function(now, mx) {
                                            //as the opacity of current_fs reduces to 0 - stored in "now"
                                            //1. scale previous_fs from 80% to 100%
                                            scale = 0.8 + (1 - now) * 0.2;
                                            //2. take current_fs to the right(50%) - from 0%
                                            left = ((1-now) * 50)+"%";
                                            //3. increase opacity of previous_fs to 1 as it moves in
                                            opacity = 1 - now;
                                            current_fs.css({'left': left});
                                            previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
                                        },
                                        duration: 800,
                                        complete: function(){
                                            current_fs.hide();
                                            animating = false;
                                        },
                                        //this comes from the custom easing plugin
                                        easing: 'easeInOutBack'
                                    });
                                });
                    
                                $(".submit").click(function(){
                                    return false;
                                })
                    
                            });
                        </script>
                        <script type="text/javascript">
                    
                            var _gaq = _gaq || [];
                            _gaq.push(['_setAccount', 'UA-36251023-1']);
                            _gaq.push(['_setDomainName', 'jqueryscript.net']);
                            _gaq.push(['_trackPageview']);
                    
                            (function() {
                                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                            })();
                    
                        </script>
                        <br clear="all"/> <br clear="all"/>
                    </div>
                    <br clear="all"/>
                </div>
                <br clear="all"/>
                <br clear="all"/>
            </section>
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
