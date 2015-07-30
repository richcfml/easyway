<?php
require_once("includes/config.php");
include_once("includes/class.phpmailer.php");
$objMail = new testmail();
if (isset($_POST['resellerEmail'])) {
    extract($_POST);
    $Message = '<table style="font:Arial; font-family: Arial; font-size: 14px; width: 100%; border: 0" border="0" cellpadding="0" cellspacing="0">
                <tr style="height: 15px;">
                        <td valign="top" colspan="2">
                        </td>
                </tr>
                <tr>
                        <td valign="top" colspan="2">
                                <span style="font-size: 18px; font-weight: bold;">Sender Details</span>
                        </td>
                </tr>
                <tr style="height: 15px;">
                        <td valign="top" colspan="2">
                        </td>
                </tr>
                <tr>
                        <td valign="top" style="width: 45%;">
                                <span style="font-weight: bold;">Name: </span>
                        </td>
                        <td valign="top" >
                                ' . str_replace("'", "", $_POST['name']) . '
                        </td>
                </tr>
                <tr style="height: 10px;">
                        <td valign="top" colspan="2">
                        </td>
                </tr>
                <tr>
                        <td valign="top" style="width: 45%;">
                                <span style="font-weight: bold;">Email: </span>
                        </td>
                        <td valign="top" >
                                ' . str_replace("'", "", $_POST['email']) . '
                        </td>
                </tr>
                <tr style="height: 10px;">
                        <td valign="top" colspan="2">
                        </td>
                </tr>
                <tr>
                        <td valign="top" style="width: 45%;">
                                <span style="font-weight: bold;">Location: </span>
                        </td>
                        <td valign="top" >
                                ' . str_replace("'", "", $_POST['location']) . '
                        </td>
                </tr>
                <tr style="height: 10px;">
                        <td valign="top" colspan="2">
                        </td>
                </tr>
                <tr>
                        <td valign="top" style="width: 45%;">
                                <span style="font-weight: bold;">Message: </span>
                        </td>
                        <td valign="top" >
                                ' . str_replace("'", "", $_POST['message1']) . '
                        </td>
                </tr>
                </table>';

    $senderEmail = $_POST['email'];
    $mSubject = 'Locate a Reseller';
    $objMail->sendTo($Message, $mSubject, "partners@easywayordering.com", true);
    //partners@easywayordering.com

    $autoMessage = "Thank you for your interest in being an EasyWay Ordering reseller. Someone will reply to your inquiry within 1-2 business days.";
    $mSubject = "Auto generetd email";
    $objMail->sendTo($autoMessage, $mSubject, $senderEmail, true);
    $flag = 1;
}
if (isset($_POST['sendEmail'])) {
    extract($_POST);
    $mMessage = '<table style="font:Arial; font-family: Arial; font-size: 14px; width: 100%; border: 0" border="0" cellpadding="0" cellspacing="0">
                <tr style="height: 15px;">
                        <td valign="top" colspan="2">
                        </td>
                </tr>
                <tr>
                        <td valign="top" colspan="2">
                                <span style="font-size: 18px; font-weight: bold;">Sender Details</span>
                        </td>
                </tr>
                <tr style="height: 10px;">
                        <td valign="top" colspan="2">
                        </td>
                </tr>
                <tr>
                        <td valign="top" style="width: 45%;">
                                <span style="font-weight: bold;">Email: </span>
                        </td>
                        <td valign="top" >
                                ' . str_replace("'", "", $_POST['email_add']) . '
                        </td>
                </tr>
                <tr style="height: 10px;">
                        <td valign="top" colspan="2">
                        </td>
                </tr>
                <tr>
                        <td valign="top" style="width: 45%;">
                                <span style="font-weight: bold;">Message: </span>
                        </td>
                        <td valign="top" >
                                I would like more information on becoming a reseller with EasyWayOrdering.
                        </td>
                </tr>
                </table>';

    $mSubject = "More Info";
    $objMail->sendTo($mMessage, $mSubject, "partners@easywayordering.com", true);
    //partners@easywayordering.com

    $autoMessage = "Thank you for your interest in being an EasyWay Ordering reseller. Someone will reply to your inquiry within 1-2 business days.";
    $mSubject = "Auto generetd email";
    $objMail->sendTo($autoMessage, $mSubject, $_POST['email_add'], true);
}
?>


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
            function lightbox_open(){console.log(2)
                window.scrollTo(0,0);
                document.getElementById('light').style.visibility='visible';
                document.getElementById('fade').style.display='block';
            }
            function lightbox_close(){
                document.getElementById('light').style.visibility='hidden';
                document.getElementById('fade').style.display='none';
            }


        </script>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>



    </head>

    <body style="background:#fff;">
        <div id="pagewrap">
            <style>
                #MYFORM  p{
                    padding: 0px 10px;
                    font-family: calibri;
                    font-size: 14px;
                }
                #MYFORM {
                    width: 450px;
                    margin: 0 auto;

                    font-family: calibri;
                    font-size: 18px;
                }
                #MYFORM  input:focus {
                    border: 1px solid #25aae1;
                }
                #MYFORM input, #MYFORM  select{float:right;margin-bottom:0px;margin-top:5px;  width: 55%; height: 40px; border:#CCCCCC 1px solid;}

                .reset{float:right;margin-bottom:10px;margin-left: 320px;width: 150px; height: 30px; border:#CCCCCC 1px solid;}



                #MYFORM  label{ float:left;color:#666666; width: 30%; text-align:left; margin-right:20px; margin-top:10px; }

                #MYFORM  .submit-button {
                    border: #56CCF7 solid 1px;
                    float: none;
                    width: 150px !important;
                    text-align:center;
                    background: #56CCF7;
                    color: #FFFFFF;
                    padding: 15px 10px 15px 10px;
                    border-radius:5px;
                    margin-right: 0px;
                    line-height: 0px;
                }

                .reseller_form{
                    background:#fff;
                    padding-top:30px;
                    padding-bottom:30px;
                    box-shadow: 0 20px 20px #9bcfe4;

                }

                .reseller_form input{
                    height: 50px !important;
                    width:95%;
                    margin:0 auto;

                }

                .reseller_form textarea{
                    height: 245px !important;
                    width:97%;
                    margin:0 auto;

                }
                .reseller_form label{
                    display:block;
                }
                #fade{
                    display: none;
                    position: fixed;
                    top: 0%;
                    left: 0%;
                    width: 100%;
                    height: 100%;
                    background-color: #000;
                    z-index:999999;
                    -moz-opacity: 0.7;
                    opacity:.70;
                    filter: alpha(opacity=70);
                }
                #light {
                    visibility:hidden;
                    position: fixed;
                    top: 20%;
                    left: 50%;
                    width:550px;
                    background: #fff;
                    height: auto;
                    margin-left: -250px;
                    margin-top:30px;
                    z-index: 9999999;
                    overflow: visible;
                    border-radius: 10px;
                }

            </style>
            <?php include('header_a.php'); ?>
            <div class="blue_con center">
                <div class="ewo-container">
                    <h3><strong>Be a part of the online ordering revolution!</strong><br/>Offer added value to your clients</h3>
                    <br clear="all"/></div>
            </div>
            <br clear="all"/>
            <section id="ewo-pricing" class="ewo-row">
                <div class="ewo-container">
                    <br clear="all"/>
                    <h3 style="color:#f7941d;" class="aligncenter"><strong>The Perks</strong></h3>
                    <p align="center">There are multiple of reasons why being an EasyWay reseller rocks:</p>
                    <br clear="all"/>
                    <div class="grid25 floatleft">
                        <p align="center"><img src="signup_images/leads.png" width="92"><br>
                            <strong>Tele-Sales</strong>
                            <br>
                        </p>
                        <p align="center">Cut down on all that cold calling, and spend more time selling.</p>
                    </div>
                    <div class="grid25 floatleft">
                        <p align="center"><img src="signup_images/value.png" width="92"><br>
                            <strong>Training</strong>
                            <br>
                        </p>
                        <p align="center">Ensure success with our easy training program - custom tailored for you. Work with our dedicated support team. </p>
                    </div>
                    <div class="grid25 floatleft">
                        <p align="center"><img src="signup_images/revenue.png" width="92"><br>
                            <strong>Recurring Revenue Stream</strong><br>
                        </p>
                        <p align="center">Reap the benefits of an ongoing cash flow. Market to your current customers - open the doors to new cutsomers!</p>
                    </div>
                    <div class="grid25 floatleft">
                        <p align="center"><img src="signup_images/training.png" width="92"><br>
                            <strong>Customer Value</strong>
                            <br>
                        </p>
                        <p align="center">Provide clients with user-friendly software and cutting edge technology. Stay ahead of the curve.</p>
                    </div>
                    <br clear="all"/>
                </div>
                <br clear="all"/>
            </section>
            <br clear="all"/>
            <div class="blue_con center" style="padding-bottom:40px;">
                <div class="ewo-container">
                    <div class="grid70b floatleft">
                        <h3><strong>Become a Reseller in as little as a week.</strong><br/>
                            We provide all the training and documents you need!</h3>
                    </div> <div class="grid30 floatright"> <h4>
                                <span class="ewo-orange-btn" style="padding: 15px 30px; line-height: 65px;cursor:pointer" onclick="lightbox_open();">Start Now!</span></h4></div>
                    <br clear="all"/>
                </div>
            </div>
            <section id="ewo-reseller" class="ewo-row">
                <div class="ewo-container">
                    <br/><br clear="all"/>
                    <h3 class="aligncenter" style="color:#565d67;"><strong>Locate a Reseller</strong></h3>
                    <br clear="all"/>
                    <br clear="all"/>
                    <form class="grid100 reseller_form" name="reseller_form" method="post">
                        <p align="center"><strong>We are proud to have resellers all over the globe!</strong><br/>
                            Contact us to find one in your area.</p>
                        <br clear="all"/>    <br clear="all"/>
                        <div class="grid50 floatleft">
                            <div class="grid90" style="margin:0 auto;">
                                <label> Your Name*</label>
                                <input type="text" name="name" placeholder="" required id="name" value="<?php echo $name; ?>"/>
                            </div>
                            <br clear="all"/>
                            <div class="grid90" style="margin:0 auto;">
                                <label>Your Email*</label>
                                <input type="email" name="email" placeholder="" required id="email" value="<?php echo $email; ?>"/>
                            </div>
                            <br clear="all"/>
                            <div class="grid90" style="margin:0 auto;">
                                <label>Your Location*</label>
                                <input type="text" name="location" placeholder="" required id="location" value="<?php echo $location; ?>"/>
                            </div> <br clear="all"/>
                        </div>
                        <div class="grid50 floatright">
                            <div class="grid90" style="margin:0 auto;">
                                <label>Your Message*</label>
                                <textarea name="message1" placeholder="" required id="message1"><?php echo $message1; ?></textarea>
                            </div>
                        </div>
                        <br clear="all"/>
                        <?php if (isset($flag) && $flag != '') {
                        ?>
                            <p align="center" style="font-size: 20px;color: rgb(23, 203, 23);"><strong>Your email is sent</strong></p>
                        <? } ?>
                        <br/>
                        <div class="button-demo">
                            <p align="center"><button type="submit" class="ladda-button" data-color="green" id="resellerEmail" name="resellerEmail" data-style="expand-right">Send</button></p>
                        </div>
                        <br clear="all"/>

                    </form>
                    <br clear="all"/> <br clear="all"/>
                    <br clear="all"/>
                </div>

                <br clear="all"/>
            </section>
            <section>
                <div class="ewo-container">
                    <div id="light">
                        <div  style="width:100%;">
                            <form name="MYFORM" id="MYFORM" method="post" enctype="multipart/form-data" >
                                <p style="padding-top:20px;">
                                    <br clear="all" />
                                    <label for='email_add' >Email Address*:</label>
                                    <input type='email' name='email_add' id='email_add' value=''   pattern="([a-zA-Z0-9]| |/|\|@|#|$|%|&|.|)+" maxlength="50" required placeholder=""/>
                                    <br clear="all" />
                                    <br clear="all" />
                                <div class="button-demo">
                                    <p align="center"><button  class="ladda-button" data-color="green" id="sendEmail" name="sendEmail" data-style="expand-right">Send</button></p>
                                </div>
                                <br clear="all" />
                            </form>
                        </div>
                        <br clear="all" />
                    </div>
                    <!-- cd-login -->
                    <div id="fade" onClick="lightbox_close();"></div>
                </div>
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
<?php mysqli_close($mysqli);?>