<?php
require_once("includes/config.php");
include_once("includes/class.phpmailer.php");
$objMail = new testmail();

function GetFileExt($pFileName) {
    $mExt = substr($pFileName, strrpos($pFileName, '.'));
    $mExt = strtolower($mExt);
    return $mExt;
}

if (isset($_POST['sendEmail'])) {
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
                                ' . str_replace("'", "", $_POST['name1']) . '
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
                                ' . str_replace("'", "", $_POST['email1']) . '
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


    $objMail->sendTo($Message, $mSubject, "customerservice@easywayordering.com", true);
    //customerservice@easywayordering.com
    $flag = 1;
}

if (isset($_POST['sendResume'])) {
    $mFileName = $_FILES['uploadme']['name'];
    $mExt = GetFileExt($_FILES['uploadme']['name']);

    if (!file_exists('sigup_resume')) {
        mkdir('sigup_resume', 0777, true);
    }

    $mPath = 'sigup_resume/';
    $mRandom = mt_rand(1, mt_getrandmax());
    $mFileName = str_replace(".", "_", str_replace(" ", "_", basename($_FILES['uploadme']['name'], $mExt))) . "_" . $mRandom . $mExt;
    $mFilePath = $mPath . $mFileName;
    move_uploaded_file($_FILES['uploadme']['tmp_name'], $mFilePath);

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
                                ' . str_replace("'", "", $_POST['fname'] . $_POST['lname']) . '
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
                                <span style="font-weight: bold;">Mobile#: </span>
                        </td>
                        <td valign="top" >
                                ' . str_replace("'", "", $_POST['mobile_phone']) . '
                        </td>
                </tr>
                <tr style="height: 10px;">
                        <td valign="top" colspan="2">
                        </td>
                </tr>
                <tr>
                        <td valign="top" style="width: 45%;">
                                <span style="font-weight: bold;">Applying For: </span>
                        </td>
                        <td valign="top" >
                                ' . str_replace("'", "", $_POST['message']) . '
                        </td>
                </tr>
                </table>';





    $senderEmail = $_POST['email_add'];
    $mSubject = 'Apply for job';

    $objMail->clearattachments();
    $objMail->addattachment($mFilePath);
    $objMail->sendTo($Message, $mSubject, "careers@easywayordering.com", true);

    $mSubject = "Auto generetd email";
    $mMessage = "Thank you for applying with EasyWay Ordering! If your qualifications meet our needs, we will contact you for a possible interview. Due to the volume of resumes received, we request that applicants not call regarding submitted resumes. ";
    $objMail->clearattachments();
    $objMail->sendTo($mMessage, $mSubject, $senderEmail, true);

    //careers@easywayordering.com
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
        <link rel="stylesheet" href="dist/ladda.min.css">
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

        <style>
            #sticky{
                height:auto !important;
            }
            img#refresh{
                float:left;
                margin-top:30px;
                margin-left:4px;
                cursor:pointer;
            }

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
                top: 0%;
                left: 50%;
                width:550px;
                background: #fff;
                height: auto;
                margin-left: -250px;
                margin-top:30px;



                z-index: 9999999;
                overflow: visible;
                border-radius: 10px;

            }</style>

        <script>
            window.document.onkeydown = function (e)
            {
                if (!e){
                    e = event;
                }
                if (e.keyCode == 27){
                    lightbox_close();
                }
            }
            function lightbox_open(){
                window.scrollTo(0,0);
                document.getElementById('light').style.visibility='visible';
                document.getElementById('fade').style.display='block';
            }
            function lightbox_close(){
                document.getElementById('light').style.visibility='hidden';
                document.getElementById('fade').style.display='none';
            }

            function fileSelectedChanged(obj2) {
                var fileSize = obj2.files[0].size;
                var logoPath = obj2.value;

                var ext = logoPath.substring(logoPath.lastIndexOf('.') + 1).toLowerCase();
                if(ext == 'jpg') {

                } else if (ext == 'jpeg') {
                } else if (ext == 'jpg') {
                } else if (ext == 'png') {

                } else if (ext == 'gif') {
                } else if (ext == 'pdf') {
                } else if (ext == 'doc') {
                } else if (ext == 'docx') {
                } else {
                    alert('Only PNG, DOC, DOCX, PDF, JPG, JPEG, GIF Files are Allowed');
                    obj2.value = "";
                }

                if(fileSize > 3000000)
                {
                    alert('File size not greator than 3mb');
                    obj2.value = "";
                }
            }
        </script>

    </head>
    <body style="background:#fff;">
        <div id="pagewrap">

            <?php include('header_a.php'); ?>
            <br clear="all"/>
            <div class="blue_con center">
                <br clear="all"/>
                <div class="ewo-container"><h3><strong>EasyWay Ordering - empowering restaurants </strong></h3><h3>with the best online ordering experience</h3><br></div>
            </div>
        </div>
    </div>
    <br clear="all"/>
<section id="ewo-about" class="ewo-row">
    <div class="ewo-container">
        <p align="center"><img src="signup_images/aboput_img1.png" /> </p>
        <h3 class="center"> <strong>What We Do</strong></h3>
        <br/>
        <p align="center"><span class="blue"><strong>Our Mission Statement:</strong></span> <strong>Empowering small and medium size restaurants to offer their customers <br/>
                the same online ordering convenience as their larger counterparts.</strong></p><br/>
        <p align="center">
            EasyWay Ordering is leading an online-ordering revolution. We reconnect you with <br/>
            your customers, and provide you with the same tools as the big-brand competitors.
        </p><br/>
        <p align="center">
            Our clients seek to <em>strengthen</em> their brand identity, <em>streamline</em> their ordering processes <br/>
            and <em>increase</em> their customer loyalty.</p>
        <br clear="all"/><br clear="all"/>
        <br clear="all"/>
    </div>
</section>
<div class="blue_con center" style="padding-bottom:40px;">
    <div class="ewo-container">

        <div class="grid70b floatleft">
            <h3><strong>Be a part of the revolution!</strong><br/> Get set up fast, and start receiving orders.</h3>
        </div> <div class="grid30 floatright"> <h4><a href="sign_up.php" class="ewo-orange-btn" style="padding: 10px 30px; line-height: 65px; font-size: 20px;">Get Going Today!</a></h4></div>

        <br clear="all"/>
    </div>
</div>

<section>
    <div class="ewo-container">
        <div id="light">
            <div  style="width:100%;">
                <form name="MYFORM" id="MYFORM" method="post" enctype="multipart/form-data" >
                    <p style="padding-top:20px;">
                        <br clear="all" />
                        <label for='fname' >Firstname*: </label>
                        <input type='text' name='fname' id='fname' value='' pattern="([A-z ]| |)+" onkeypress="return isAlphanumeric(event)"  maxlength="50" required placeholder="" />

                        <br clear="all" />
                        <label for='lname' >Lastname*:</label>
                        <input type='text' name='lname' id='lname' value='' pattern="([A-z ]| |)+" maxlength="50" required  placeholder=""/>


                        <br clear="all" />
                        <label for='mobile_phone' >Mobile Phone*:</label>
                        <input type='text' name='mobile_phone' id='mobile_phone' value=''  onkeypress="return isNumberKey(event)" pattern="[0-9]+"  maxlength="50" required placeholder=""/>
                        <br clear="all" />

                        <label for='email_add' >Email Address*:</label>
                        <input type='email' name='email_add' id='email_add' value=''   pattern="([a-zA-Z0-9]| |/|\|@|#|$|%|&|.|)+" maxlength="50" required placeholder=""/>
                        <br clear="all" />

                        <label for='message' >What Position Are Your Applying for?</label>
                        <input type='text' name='message' id='message' value=''  maxlength="50"  placeholder=""/>
                        <br clear="all" />      <br clear="all" />
                        Upload Resume (DOC, DOCX, PDF, JPEG, JPG, PDF Limit: 3MB)<br/>     <br clear="all" />
                    <p>
                        <label for='file' >Resume*:</label>
                        <input type="file" name="uploadme" id="uploadme"  onchange="fileSelectedChanged(this);" required>
                        <br clear="all" />
                        <br clear="all" />
                    <div class="button-demo">
                        <p align="center"><button  class="ladda-button" data-color="green" id="sendResume" name="sendResume" data-style="expand-right">Send</button></p>
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

<section id="ewo-about" class="" style="padding-top: 40px;">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <div class="ewo-container">
        <br clear="all"/>
        <h3 class=" center"> <strong>Join The Team</strong></h3>
        <p align="center"> Come be a part of our family. Join a group of dynamic individuals, dedicated to providing the best<br/>
            of the best for our clients. <br clear="all"/><br clear="all"/>
        </p>
        <div class="" style="margin:0 auto;">
            <div class="grid25 floatleft">
                <p align="center"><img src="signup_images/about/imag1.png" width="132"/><br/>
                    <u><strong>Tele-Sales</strong></u>
                    <br/>
                <nav class="main-nav">
                    <ul>
                        <li><p align="center"><a class="cd-signup ewo-orange-btn" href="#" style="border: none;padding: 10px 20px; line-height: 65px; border-radius: 5px; list-style:none;" onclick="lightbox_open();">APPLY</a></p></li>
                    </ul>
                </nav>
                </p>
            </div>
            <div class="grid25 floatleft">
                <p align="center"><img src="signup_images/about/imag2.png" width="132"/><br/>
                    <u><strong>SEO Expert</strong></u>
                    <br/>
                <nav class="main-nav">
                    <ul>
                        <li><p align="center"><a class="cd-signup ewo-orange-btn" href="#0" style="border: none;padding: 10px 20px; line-height: 65px; border-radius: 5px; list-style:none;" onclick="lightbox_open();">APPLY</a></p></li>
                    </ul>
                </nav>
                </p>
            </div>
            <div class="grid25 floatleft">
                <p align="center"><a href="http://burgerburgernyc.com/" target="_blank"><img src="signup_images/about/imag3.png" width="132"/></a><br/>
                    <u><strong><a href="http://burgerburgernyc.com/" target="_blank">Mobile Developer</a></strong></u>
                    <br/>
                <nav class="main-nav">
                    <ul>
                        <li><p align="center"><a class="cd-signup ewo-orange-btn" href="#0" style="border: none;padding: 10px 20px; line-height: 65px; border-radius: 5px; list-style:none;" onclick="lightbox_open();">APPLY</a></p></li>
                    </ul>
                </nav>
                </p>
            </div>
            <div class="grid25 floatleft">
                <p align="center"><img src="signup_images/about/imag4.png" width="132"/><br/>
                    <u><strong><a href="http://tequilascantina.com/" target="_blank">Sales Team</a></strong></u>
                    <br/>
                <nav class="main-nav">
                    <ul>
                        <li><p align="center"><a class="cd-signup ewo-orange-btn" href="#0" style="border: none;padding: 10px 20px; line-height: 65px; border-radius: 5px; list-style:none;" onclick="lightbox_open();">APPLY</a></p></li>
                    </ul>
                </nav>
                </p>
            </div>
        </div>
        <br clear="all"/>
        <br clear="all"/>
        <br clear="all"/>
    </div>
    <br clear="all"/>
</section>
<section id="ewo-about" class="add_features" style="border-bottom:8px solid #775429;">
    <div class="ewo-container">
        <h3 class=" center"> <strong>Chat With Us</strong></h3>
        <p align="center">Comments? Questions? Let us know! We're all ears.</p>
        <form class="grid100" name="contact" method="post">
            <div class="grid50">
                <input type="text" name="name1" id="name1" placeholder="Your Name" required/>
            </div>
            <br clear="all"/>
            <div class="grid50">
                <input type="email" name="email1" id="email1" placeholder="Your Email" required/>
            </div>
            <br clear="all"/>
            <div class="grid100">
                <textarea name="message1"id="message1" placeholder="What's on your mind?" required></textarea>
            </div>
            <br/>
            <div class="button-demo">
                <p align="center"><button type="submit" class="ladda-button" data-color="green" id="sendEmail" name="sendEmail" data-style="expand-right">Send</button></p>
            </div>
            <p align="center">
            </p><br clear="all"/>
            <h4 class="center"><a href="freedemo.php" class="ewo-orange-btn demo-about">Get a FREE Demo Now</a></h4>
            <br/>
            <p align="center"><u style="color:#e8f3f8;"><strong><a href="sign_up.php"  style="color:#e8f3f8;">I'm Ready to Sign Up Today!</a></strong></u></p>
        </form>
        <script src="dist/spin.min.js"></script>
        <script src="dist/ladda.min.js"></script>
        <script>
            $("#send").click(function(){
		
                var n = $('#name1').val();
                var e = $('#email1').val();
                var j = $('#message1').val();

                if(n.length != 0 & e.length != 0 & j.length !=0 & c.length != 0 & nu.length !=0){
                    $.ajax({
                        url: 'http://ewosite.ewordering.com/about-process.php',
                        data:{
                            "name1": $("#name1").val(),
                            "email1": $("#email1").val(),
                            "message1": $("#message1").val(),
	
		 
                        },
                        type: "POST",
                        success: function(d){
                            //success
                            $('#dlform').hide();
                            $('#dlwrap').show();
                            $('#dlbtnwrap').append('<a href="'+ dl +'" style="color:#fff; " download>DOWNLOAD</a>');
                        },
                        error: function(){
                            alert("Error: Please check your internet connection.");
                        }
                    });
                }
            });

        </script>
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
    </div>
    <br clear="all"/>
</section>
<?php include('footer.php'); ?>
</body>
</html>
<?php
    if (isset($_GET["careers"])) {

        echo("<script type='text/javascript' language='javascript'>$('html, body').animate({scrollTop : 996},800);</script>");
    }
   mysqli_close($mysqli);
?>