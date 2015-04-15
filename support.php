<?php
require_once("includes/config.php");
include_once("includes/class.phpmailer.php");
$objMail = new testmail();
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
                                ' . str_replace("'", "", $_POST['fname'] . ' ' . $_POST['lname']) . '
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
                                <span style="font-weight: bold;">Mobile: </span>
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
                                <span style="font-weight: bold;">Message: </span>
                        </td>
                        <td valign="top" >
                                ' . str_replace("'", "", $_POST['message']) . '
                        </td>
                </tr>
                </table>';


    $senderEmail = $_POST['email_add'];
    $mSubject = "Email for customer service team.";
    $objMail->sendTo($Message, $mSubject, "customerservice@easywayordering.com", true);
    //customerservice@easywayordering.com

    $autoMessage = "Thank you for contacting EasyWay Ordering Customer Service. Your business is important to us, and we will respond to your request within 24 hours.<br /> Thank you.";
    $mSubject = "Auto generetd email";
    $objMail->sendTo($autoMessage, $mSubject, $senderEmail, true);
    $flag = 1;
}
if ($_GET['call'] == 'download') {
    $username = $_GET['username'];
    $password = $_GET['password'];
    $qry_str = "SELECT id, type, status FROM users WHERE username='" . prepareStringForMySQL($username) . "' AND password='" . prepareStringForMySQL($password) . "'";
    $user = mysql_query($qry_str);
    if (mysql_num_rows($user) > 0) {
        echo "1";
        exit;
    } else {
        echo 0;
        exit;
    }
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

                $("#download").click(function(event){
                    event.preventDefault();
                    $("#alert-error").hide();
                    if ($("#userName").val().length == 0) {
                        $("#userName").focus();
                        $("#userName").css("border", "2px solid #FF0000");
                        return;
                    }
                    if ($("#password").val().length == 0) {
                        $("#password").focus();
                        $("#password").css("border", "2px solid #FF0000");
                        return;
                    }
                         
                    $.ajax({
                        url:"support.php?call=download&username="+$.trim($("#userName").val())+"&password="+$.trim($("#password").val()),
                        type:"POST",
                        success:function(data)
                        {
                            if(data>0)
                            {
                                window.location = "download_manual.php"
                            }
                            else
                            {
                                $("#alert-error").show();
                            }
                        }
                    });
                    
                });
            });
        </script>
    </head>
    <body style="background:#fff;">
        <div id="pagewrap">
            <style>
                .gray_box{
                    background:#f0f4f6;
                    padding-top:20px;
                    padding-bottom:40px;
                    border-radius:3px;
                    -moz-border-radius:3px;
                    -webkit-border-radius:3px;
                    min-height: 340px;
                    margin-top: 20px;
                }
                .gray_box:hover{

                    background:#f0f4f6;
                    padding-top:20px;
                    padding-bottom:40px;
                    border-radius:3px;
                    -moz-border-radius:3px;
                    -webkit-border-radius:3px;
                    min-height: 340px;
                    margin-top: 15px;
                    box-shadow:0 4px 5px #666;
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
                .fade{
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
                .light {
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

                }
            </style>

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


                function lightbox_open1(){
                    window.scrollTo(0,0);
                    document.getElementById('light1').style.visibility='visible';
                    document.getElementById('fade1').style.display='block';
                }
                function lightbox_close1(){
                    document.getElementById('light1').style.visibility='hidden';
                    document.getElementById('fade1').style.display='none';
                }

                function lightbox_open2(){
                    window.scrollTo(0,0);
                    $("#alert-error").hide()
                    document.getElementById('light2').style.visibility='visible';
                    document.getElementById('fade2').style.display='block';
                }
                function lightbox_close2(){
                    document.getElementById('light2').style.visibility='hidden';
                    document.getElementById('fade2').style.display='none';
                }

                function fileSelectedChanged(obj2) {
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
                }
            </script>
            <?php include('header_a.php'); ?>
            <div class="blue_con center">
                <div class="ewo-container">
                    <h3><strong>Have a question?</strong><br/>
                        We're here to help.<br clear="all"/></h3></div>
            </div>
            <section>
                <div class="ewo-container">
                    <div id="light2" class="light">
                        <div  style="width:100%;">
                            <form name="MYFORM" id="MYFORM" method="post">
                                <br clear="all">
                                <h3 style="color:#25aae1; text-align:center;"> Please Log in to download manual</h3>
                                <p style="padding-top:20px;">
                                <p id="alert-error" style="display:none;background-color: #f2dede;color: #b94a48;padding: 8px 10px;margin: 5px 0 10px 0;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);font-size: 18px;">Invaild UserName or Password</p>
                                <br clear="all" />
                                <label for='userName' >UserName*: </label>
                                <input type='text' name='userName' id='userName' value='' pattern="([A-z ]| |)+"  maxlength="50" required />
                                <br clear="all" />
                                <label for='password' >Password*:</label>
                                <input type='password' name='password' id='password' value='' maxlength="50" required />
                                <br clear="all" />
                                <br clear="all" />
                                <div class="button-demo">
                                    <p align="center"><button type="button"  class="ladda-button" data-color="green" id="download" name="download" data-style="expand-right">Log in</button></p>
                                </div>
                                <br clear="all" />
                            </form>
                        </div>
                        <br clear="all" />
                    </div>
                    <!-- cd-login -->
                    <div id="fade2" class="fade" onClick="lightbox_close2();"></div>
                </div>
            </section>
            <section>
                <div class="ewo-container">
                    <div id="light" class="light">
                        <div  style="width:100%;">
                            <form id="MYFORM" name="MYFORM" method ="post" enctype="multipart/form-data" >
                                <br clear="all" />
                                <h3 style="color:#25aae1; text-align:center;"> Email Us</h3>
                                <p style="padding-top:20px;">
                                    <label for='fname' >Firstname*: </label>
                                    <input type='text' name='fname' id='fname' value='' pattern="([A-z ]| |)+" onkeypress="return isAlphanumeric(event)"  maxlength="50" required placeholder="First Name" />
                                    <br clear="all" />
                                    <label for='lname' >Lastname*:</label>
                                    <input type='text' name='lname' id='lname' value='' pattern="([A-z ]| |)+" maxlength="50" required  placeholder="Last Name"/>
                                    <br clear="all" />
                                    <label for='email_add' >Email Address*:</label>
                                    <input type='email' name='email_add' id='email_add' value=''   pattern="([a-zA-Z0-9]| |/|\|@|#|$|%|&|.|)+" maxlength="50" required placeholder="name@email.com"/>
                                    <br clear="all" />
                                    <label for='mobile_phone' >Mobile Phone*:</label>
                                    <input type='text' name='mobile_phone' id='mobile_phone' value=''  onkeypress="return isNumberKey(event)" pattern="[0-9]+"  maxlength="50" required placeholder="(800) 123-4567"/>
                                    <br clear="all" />
                                    <label for='message' >Message</label>
                                    <input type='text' name='message' id='message' value='' required maxlength="50"  placeholder=" What can we help you with?"/>
                                    <br clear="all" />
                                    <br clear="all" />
                                <div class="button-demo">
                                    <p align="center"><button type="submit"  class="ladda-button" data-color="green" id="sendEmail" name="sendEmail" data-style="expand-right">Send</button></p>
                                </div>
                                <br clear="all" />
                            </form>
                        </div>
                        <br clear="all" />	</div>
                    <!-- cd-login -->
                    <div id="fade" class="fade" onClick="lightbox_close();"></div>
                </div>
            </section>
            <section>
                <div class="ewo-container">
                    <div id="light1" class="light">
                        <div>
                            <br clear="all"/>
                            <h4 style="color:#25aae1; text-align:center;"> <strong>Chat with a Representative</strong></h4> <br clear="all"/>
                            <p align="center">
                                <iframe src="http://www.vcita.com/widgets/sidebar/956a329f?ver=2" width="90%" height="300" scrolling="no" frameborder="0" style=" margin:0 auto;"></iframe></p>
                        </div>
                        <br clear="all" />	</div>
                    <!-- cd-login -->
                    <div id="fade1" class="fade" onClick="lightbox_close1();"></div>
                </div>
            </section>
            <br clear="all"/>
            <section id="ewo-support" class="ewo-row">
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
                <div class="ewo-container">
                    <p align="center">&nbsp;</p>
                    <br clear="all"/>
                    <div class="grid25 floatleft gray_box">
                        <p align="center"><img src="signup_images/give_call.png" width="112"><br>
                            <strong class="bluefont">Give Us a Call</strong>
                            <br>
                            <br>
                        </p>
                        <p align="center"><strong>Monday - Friday 9 am - 8 pm</strong><br/>
                            1-800-648-6238</p>
                        <p align="center"><strong>For question about order, <br/>please call</strong><br/>
                            877-299-0461</p>
                    </div>
                    <div class="grid25 floatleft gray_box">
                        <p align="center"><img src="signup_images/chat.png" width="112"><br>
                            <strong class="bluefont">Chat with a Representative</strong>
                            <br>
                            <br>
                        </p>
                        <p align="center"><strong>Chat online with a customer service rep</strong></p>
                        <br><br>
                        <p align="center"> <a href="#" class="ewo-blue-btn"  onclick="lightbox_open1();"> Start Chat</a></p>
                    </div>
                    <div class="grid25 floatleft gray_box">
                        <p align="center"><img src="signup_images/email.png" width="112"><br>
                            <strong class="bluefont">Send Us an Email</strong><br>
                            <br>
                        </p>
                        <p align="center"><strong>Email our cutomer service team directly.</strong></p>
                        <br><br>
                        <p align="center"> <a href="#" class="ewo-blue-btn" onclick="lightbox_open();">Email Us</a><br><br>We will respond within 24 hours!</p>
                    </div>
                    <div class="grid25 floatleft gray_box">
                        <p align="center"><img src="signup_images/manual.png" width="112"><br>
                            <strong class="bluefont">Donwload Our Manual</strong>
                            <br>
                            <br>
                        </p>
                        <p align="center"><strong>Have EasyWay Ordering, </strong>and need a copy of the owner's manual? Click below.</p>
                        <br>
                        <p align="center"> <span style="cursor:pointer;" class="ewo-blue-btn" onclick="lightbox_open2();"> Download</span></p>
                    </div>
                    <br clear="all"/>
                </div>
                <br clear="all"/>
                <br clear="all"/>
                <br clear="all"/>
            </section>
            <br clear="all"/>
        </div>
        <script src="dist/spin.min.js"></script>
        <script src="dist/ladda.min.js"></script>
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
        <?php include('footer.php'); ?>
    </body>
</html>
