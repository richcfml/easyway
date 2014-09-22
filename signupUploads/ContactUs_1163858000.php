<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Algronic | Contact Us</title>
<link type="text/css" href="css/GlobalStyle.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="css/menu.css">
<link href="css/ContactUs_style.css" rel="stylesheet">

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/function.js"></script>
<!--slider-->
<link href="css/slider.css" rel="stylesheet" type="text/css" media="all"/>
<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<!--<script type="text/javascript" src="js/jquery.nivo.slider.js"></script>
<script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider();
    });
    </script>-->
<link rel="stylesheet" type="text/css" href="css/demo.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/style4.css" />
<link rel="stylesheet" type="text/css" href="css/2demo.css" />
<link rel="stylesheet" type="text/css" href="css/2common.css" />
<link rel="stylesheet" type="text/css" href="css/style7.css" />
<link rel="shortcut icon" href="images/favicon.ico"/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico"/favicon.ico" type="image/x-icon">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,700' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="js/modernizr.custom.79639.js">

</script>
</head>


<!--php email code-->
<?php
//$_session["val"]=date();
//echo $_session["val"];
//$to="hasanadnan@live.com";
$to=$_POST['email']; 
$_message=$_POST['message']; 
$headers = "From: hasanadnan@live.com" . "\r\n"; //
//"CC: somebodyelse@example.com"
// the message
//$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$_message = wordwrap($_message,70);

// send email
//echo "#####";
 mail($to,"Inquiry",$_message,$headers);
//echo "@@@@";
//echo "Your Inquiry has been forwarded to the concerned department."
//echo "<PRE>";print_r($_POST);


?>
<body>

<div id="pagewrap">
	<div id="header-top">
		<div id="logo">
    		<a href="index.html"><img src="images/Algronic.png" alt="Algronic"></a>
    	</div>
        <div id="socialIcons">
        	<table>
            	<tr>
                	<td><a href="#" class="facebookIcon"></a></td>
                	<td><a href="#" class="twitterIcon"></a></td>
                </tr>
        	</table>
        </div>
        <div class="clear"></div>
    <div id="wrap">
	<header>
		<div class="inner relative">
			<!--<a class="logo" href="#"><img src="images/logo.png"></a>-->
			<a id="menu-toggle" class="button dark" href="#"><i class="icon-reorder"></i></a>
			<nav id="navigation">
				<ul id="main-menu">
					<li class=""><a href="index.html"><i class="icon-home"></i>Home</a></li>
                    <li class=""><a href="Products.html"><i class="icon-exclamation-sign"></i>Products</a></li>
                    <li class=""><a href="Certificate.html"><i class="icon-plus"></i>Certificate</a></li>
                    <li class=""><a href="Blog.html"><i class="icon-user"></i>Blog</a></li>
                    <li class=""><a href="ContactUs.html"><i class="icon-envelope"></i>Contact us</a></li>
                    <!--<li class=""><a href=""><i class="icon-user"></i>Sign in</a></li>
                    <li class=""><a href=""><i class="icon-plus"></i>Sign up</a></li>
					<li class="parent">
						<a href="">NEWS</a>
						<ul class="sub-menu">
							<li><a href=""><i class="icon-wrench"></i> Elements</a></li>
							<li><a href=""><i class="icon-credit-card"></i>  Pricing Tables</a></li>
							<li><a href=""><i class="icon-gift"></i> Icons</a></li>
							<li>
								<a class="parent" href="#"><i class="icon-file-alt"></i> Pages</a>
								<ul class="sub-menu">
									<li><a href="">Full Width</a></li>
									<li><a href="">Left Sidebar</a></li>
									<li><a href="">Right Sidebar</a></li>
									<li><a href="">Double Sidebar</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li><a href="">ABOUT</a></li>
					<li class="parent">
						<a href="">VIDEOS</a>
						<ul class="sub-menu">
							<li><a href="">Large Image</a></li>
							<li><a href="">Medium Image</a></li>
							<li><a href="">Masonry</a></li>
							<li><a href="">Double Sidebar</a></li>
							<li><a href="">Single Post</a></li>
						</ul>
					</li>
                    <li><a href="">ARCHIVE</a></li>
                    <li><a href="">TRANSIT PROJECT</a></li>
                    <li><a href="">SHOWS/AWARDS</a></li>-->
                    
				</ul>
			</nav>
			<div class="clear"></div>
		</div>
	</header>
    </div>
    </div>
    <div class="clear"></div>
    <!------ Slider ------------>
    <!--<div class="slider">
	 		<div class="slider-wrapper theme-default">
	            <div id="slider" class="nivoSlider">
	                <img src="images/banner1.jpg" alt="" />
	                <img src="images/banner2.jpg" alt="" />
	                <img src="images/banner3.jpg" alt="" />
	                <img src="images/banner4.jpg" alt="" />
	                <img src="images/banner5.jpg" alt="" />
	            </div>
	        </div>
	</div>-->
    <!------End Slider ------------>
    <hr style="background-color:#034e8d; height:5px; margin-top:2px;">
    <!------ How It works starts ------------>
    <div id="HowItWorksBox">
    	<div align="center">
    	  <p style="color:#FFF; font-family:Arial, Helvetica, sans-serif; font-size:36px;">Contact Us</p>
    	</div>
        <div id="AddressText" style="padding:30px;">
        <p style="font-size:28px; color:#FFFFFF;">
            Email Address:  <span style="font-weight:bold; color:#000000;">sales@algronic.com</span><br />
         </p>   
        </div>
    </div>
    <!------ How it works ends ------------>
    
    <!------ Latest Posts Starts --------->
    <div id="LatestPostBox">
    	<div align="center">
    	  <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:36px;">Your Feedback</p>
    	</div>
        <div class="clear"></div>
        <!------ Contact us Form Starts ----->
    <div class="wrapper">
		<div id="main" style="padding:15px 0 0 0;">
		
		<!-- Form -->
		<form id="contact-form" action="" method="post">
			<h3>Contact Us Form</h3>
				<div>
				<label>
					<span>Name: (required)</span>
					<input placeholder="Please enter your name" type="text" tabindex="1" name="name"  required autofocus>
				</label>
			</div>
			<div>
				<label>
					<span>Email: (required)</span>
					<input placeholder="Please enter your email address" type="email" name="email"  tabindex="2" required>
				</label>
			</div>
			<div>
				<!--<label>
					<span>Telephone: (required)</span>
					<input placeholder="Please enter your number" type="tel" tabindex="3" required>
				</label>
			</div>
			<div>
				<label>
					<span>Website: (required)</span>
					<input placeholder="Begin with http://" type="url" tabindex="4" required>
				</label>-->
			</div>
			<div>
				<label>
					<span>Message: (required)</span>
					<textarea placeholder="Include all the details you can" tabindex="5" name="message"  required></textarea>
				</label>
			</div>
			<div>
				<button name="submit" type="submit" id="contact-submit">Send Email</button>
			</div>
		</form>
		<!-- /Form -->
		
		</div>
	</div>
    <!------ Contact us Form Ends -------> 
        </div>
    <!------ Latest Posts Ends ------->
    <!------ Footer Starts ------->
    <div id="footer">
    	<div class="footerInnerBox">
        	<h4 style="color:#FFF;">ABOUT US</h4>
            <p style="color:#fff; font-size:12px;">Algronic is a premium supplier of organic ingredients from nature, for health,food and cosmetic industries.</p>
        </div>
        <div class="footerInnerBox">
        	<h4 style="color:#FFF;">MENU</h4>
            <table>
            <tr><td><a href="index.html"><i class="icon-home">&nbsp;&nbsp;Home</i></a></td></tr>
            <tr><td><a href="Products.html"><i class="icon-exclamation-sign">&nbsp;&nbsp;Products</i></a></td></tr>
            <tr><td><a href="Certificate.html"><i class="icon-plus">&nbsp;&nbsp;Certificate</i></a></td></tr>
            </table>
        </div>
        <div class="footerInnerBox">
        	<h4 style="color:#FFF;">FOLLOW US</h4>
            <table>
            <tr><td><a href="#"><i class="icon-facebook-sign">&nbsp;&nbsp;Facebook</i></a></td></tr>
            <tr><td><a href="#"><i class="icon-twitter-sign">&nbsp;&nbsp;Twitter</i></a></td></tr>
            <tr><td><a href="#"><i class="icon-google-plus-sign">&nbsp;&nbsp;Google +</i></a></td></tr>
            </table>
        </div>
        <div class="footerInnerBox">
        	<h4 style="color:#FFF;">USER</h4>
            <table>
            <tr><td><a href="Blog.html"><i class="icon-user">&nbsp;&nbsp;Blog</i></a></td></tr>
            <tr><td><a href="ContactUs.html"><i class="icon-envelope">&nbsp;&nbsp;Contact us</i></a></td></tr>
            </table>
        </div>
        <div class="clear"></div>
        <div id="footerBlack">
        	<div id="copyrightDiv">&copy; Copyright at 2014 | All rights reserved</div>
            <div id="designedByDiv">Designed and Developed by <span style="font-weight:bold;"><a href="http://www.facebook.com/zohaib.ahmed35" target="_blank">Ahmed Zohaib</a></span></div>
        </div>
    </div>
    <!------ Footer Ends ------->
</div>
</body>
</html>
