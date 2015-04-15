<?php
/**
* The template for displaying the footer.
*
* Contains footer content and the closing of the
* #main and #page div elements.
*
*/
global $incart_lite_shortname;
?>
	<div class="clearfix"></div>
</div>
<!-- #main --> 
<STYLE>
.grid50 {
width: 48.99%;
}
.grid40 {
width: 38.99%;
}
.floatright {
float: right;
}
.floatleft {
float: left;
}
footer p{
	margin-left:10px;
}

footer ul li {
margin: 0 0 0 0px;
padding: 0 5px;
line-height: 24px;
text-shadow: none;
float: left;
list-style:none;
width: 20%;
font-size:14px;
font-weight:bold;
}

footer h3 {
color: #fff;
font-weight: 500;
font-size: 22px;
text-align: left;
}footer h4 {
color: #fff;
font-weight: 500;
font-size: 18px;
text-align: left;
line-height: 20px;
}footer input {
background: #282d30 !important;
color: #fff;
font-size: 14px;
}
.ewo-blue-btn {
background: #25aae1 !important;
padding: 10px 5%;
border: 3px solid #fff;
width: auto !important;
text-align: center;
color: #fff;
border-radius: 10px;
}

.ewo-blue-btn:hover {
background: #1e98cb !important;
}
footer a{
	text-decoration:none;
	color:#fff;
	text-shadow: 0 0 1px 000;
}
footer a:hover{
	text-decoration:none;
	color:#f7941d;
	text-shadow: 0 0 1px 000;
}

#copy li{
	width:100%;
	border:none;
	text-align: center;
}
#copy p{
	border:none;
}</style>
<!-- #footer -->
<div id="footer" class="skt-section">
	<div class="container">
		<div class="row-fluid">
			<div class="second_wrapper">
				<?php dynamic_sidebar( 'Footer Sidebar' ); ?>
				<div class="clearfix"></div>
			</div><!-- second_wrapper -->
		</div>
	</div>
<footer>
	<div class="third_wrapper">
		<div class="container">
			<div class="row-fluid">
				
<div class="ewo-container">
	<div style="padding: 20px 0px;	"> 
    <div class="grid50 floatleft">  
     <ul class="  grid100 floatleft">
          <li><a href="index.php">Careers</a></li>
          <li><a href="about.php">Developers</a></li>
          <li><a href="privacy.php">Privacy</a></li>
          <li><a href="get_a_card.php">Support</a></li>
      
          </ul>
          
          <br><br>
          <p>50 Broad Street&nbsp;  Suite 1701<br> New York, NY 10004 USA <br><br> T: <strong>(800) 648-6238</strong><br>
F: (800) 356-1510<br>
</p>
          </div>
         
   <div class="grid40 floatright">
        <form action="http://easywayordering.us7.list-manage.com/subscribe/
        post" method="POST"> 
        <input type="hidden" name="u" value="b833067e17d155a7b4f915d2e"> 
        <input type="hidden" name="id" value="4e87551cd3">
        <input type="email" autocapitalize="off" autocorrect="off" name="MERGE0" id="MERGE0" size="25" value="" placeholder="Enter Email Address" style="height: 30px;

padding: 0px 10px;
width: 65%;
border: 1px solid #25aae1; font-size:12px;">
        
        <input type="submit" class="ewo-blue-btn" value="Sign Me Up!" style="border-radius:0; border:none;background: #25aae1;  color: #fff; height: 30px;
padding: 0px 10px;">
        
        </form>
        <br clear="all">
   <h3>GET THE LATEST NEWS! <br> </h3>
   <h4>Keep up with industry news, plus get valuable 
business tips and tricks, <strong>FOR FREE!</strong> - subscribe now</h4>

   </div>
   <br clear="all">
	</div>
    
    <div style="border-top: 1px solid #4c4c4c; clear:both; padding: 0 0px;">
	<div class="grid50 floatleft" style=" padding: 20px 0px 0 0;">Copyright © 2014 Easyway. All Rights Reserved.</div>
    <div class="grid40 floatright" style=" padding: 10px 0px 0 0;"><p align="right" style="margin-left:0px;">
    <a href="https://www.facebook.com/EasywayOrdering" target="_blank"><img src="http://ewosite.ewordering.com/images/facebook.png" align="center"></a>
    <a href="http://www.twitter.com/EasyWayInc" target="_blank"><img src="http://ewosite.ewordering.com/images/twitter.png" align="center"></a>
    <a href="https://plus.google.com/u/1/b/117596371910744353409/117596371910744353409" target="_blank"><img src="http://ewosite.ewordering.com/images/googlr.png" alt="" align="center"></a>
    <a href="http://www.youtube.com/user/EasyWayOrdering/" target="_blank"><img src="http://ewosite.ewordering.com/images/youtube.png" alt="" align="center"></a>
    <a href="http://instagram.com/easywayordering#" target="_blank"><img src="http://ewosite.ewordering.com/images/instagram.png" alt="" align="center"></a>
    <a href="https://www.linkedin.com/company/easyway-ordering" target="_blank"><img src="http://ewosite.ewordering.com/images/linkedin.png" alt="" align="center"></a></p>
    </div>
      <br clear="all"></div>
    <br clear="all">
    
   </div>
			</div>
		</div>
	</div><!-- third_wrapper --> 
    </footer>
</div>
<!-- #footer -->

</div>
<!-- #wrapper -->
	<a href="JavaScript:void(0);" title="Back To Top" id="backtop"></a>
	<?php wp_footer(); ?>
	
</body>
</html>