<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Online Ordering System</title>
<style>
body{ background-color:#068d32; margin:0px; font-family:Arial;}
#maincontainer{ min-width:1003px; margin:20px;}
#maincontainer #header{ }
#maincontainer #header #top{ background-image:url(Images/top.png); height:7px;}
#maincontainer #header #top #top_left{background-image:url(Images/tl.png); background-repeat:no-repeat; width:7px; height:7px; background-color:#068d32; float:left;}
#maincontainer #header #top #top_right{background-image:url(Images/tr.png); background-repeat:no-repeat; width:7px; height:7px; background-color:#068d32; float:right;}

#maincontainer #text_holder{ background-color:#FFF;}
#maincontainer #text_holder #logo_area{background-color:#fede02; height:95px; border-left:#FFF 3px solid;border-right:#FFF 3px solid;}
#maincontainer #text_holder #logo_area #logo{ padding:10px 0px 10px 20px; float:left;}
#maincontainer #text_holder #logo_area #logo_text{ padding:46px 0px 0px 20px; float:left; font-size:25px; font-weight:bold; color:#545454;}
#maincontainer #text_holder #logo_area #login_text{ padding:10px 20px 0px 0px; float:right; font-size:12px;color:#545454;}
#maincontainer #text_holder #logo_area #login_text a{ text-decoration:none;color:#545454;}
#maincontainer #text_holder #logo_area #login_text a:hover{ text-decoration:underline;color:#545454;}
#maincontainer #text_holder #navigation_links{ }
#maincontainer #text_holder #navigation_links #navigation{ padding:20px 0px 0px 0px;background-image:url(Images/selecter.png);background-repeat:repeat-x; background-position:bottom; height:23px; }
#maincontainer #text_holder #navigation_links #navigation #links{ float:left; font-size:14px; font-weight:bold;margin:0px 0px 0px 0px; padding:0px 0px 0px 0px;}
#maincontainer #text_holder #navigation_links #navigation #links a{margin:0px 0px 0px 0px; padding:6px 10px 6px 10px; text-decoration:none; color:#545454; border:#dedede 1px solid;}

/*#maincontainer #contents_box #other_contents #navigation #links a:hover{margin:0px 0px 0px 0px; padding:10px 10px 10px 10px;border-left:#666 1px solid;border-top:#666 1px solid;border-right:#666 1px solid;border-bottom:#FFF 1px solid;color:#009c0c;}*/
#maincontainer #text_holder #navigation_links #navigation #links .selected{border-bottom:#FFF 1px solid;color:#cc0000;}

#maincontainer #navigation_links #tab_items{margin:10px 0px 0px 0px;padding:0px 0px 5px 22px; font-size:12px;border-bottom:#dedede 1px solid;}
#maincontainer #navigation_links #tab_items ul{ margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;color:#dedede;}
#maincontainer #navigation_links #tab_items ul li{margin:0px 0px 0px 0px;padding:0px 10px 0px 10px; display:inline;}
#maincontainer #navigation_links #tab_items ul li a{ text-decoration:none; color:#545454;}
#maincontainer #navigation_links #tab_items ul li a:hover{ text-decoration:underline; }
#maincontainer #navigation_links #tab_items ul li .selected_red{ text-decoration:none; color:#cc0000;}

#maincontainer #page_content{ padding:0px 5px 50px 5px; }
#maincontainer #page_content #contents{  margin:20px 0px 0px 0px}
#maincontainer #page_content #contents #main_heading{font-size:18px;color:#4b4b4b; padding:5px 0px 5px 20px; background-color:#f4f4f4; margin-bottom:20px;}

.listig_table{ border:1px  #e8e8e8 solid; border-right:0px;}
.listig_table th{ border-right:1px solid #e8e8e8;  text-align:left; font-size:12px; font-weight:bold; background-color:#eed000; height:25px; padding:0px 0px 0px 5px; }
.listig_table td{  border-right:1px solid #e8e8e8; text-align:left; font-size:12px; color:#666;height:25px; padding:0px 0px 0px 5px; }

#maincontainer #footer{}
#maincontainer #footer #bottom{ background-image:url(Images/bottom.png); height:7px;}
#maincontainer #footer #bottom #bottom_left{background-image:url(Images/bl.png); background-repeat:no-repeat; width:7px; height:7px; background-color:#068d32; float:left;}
#maincontainer #footer #bottom #bottom_right{background-image:url(Images/br.png); background-repeat:no-repeat; width:7px; height:7px; background-color:#068d32; float:right;}
span.font_12px{ font-size:12px; font-weight:normal;}
.line{ background-color:#dedede; height:1px; margin:10px 30px 0px  30px;}

</style>
</head>
<body>
<div id="maincontainer">
	<div id="header">
            <div id="top">
                <div id="top_left"></div>
                <div id="top_right"></div>
            </div><!--End top Div-->
    </div><!--End header Div-->        
            <div id="text_holder">
                <div id="logo_area">
                    <div id="logo"><img src="images/man_img.png" width="57" height="74" /></div>
                    <div id="logo_text">ADMINISTRATION PANEL</div>
                    <div id="login_text"><a href="#">Creat Acount</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#">My Account</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#">Log out</a></div><br style="clear:both" />
            </div><!--End logo_area Div-->
            
            <div id="page_content">
            
   			<div id="navigation_links">
                  <div id="navigation">
                  		<div id="links" style=" margin-left:20px;"><a href="#" style="border-right:none;">Home</a></div>
                  		<div id="links"><a href="#" style="border-right:none;" class="selected">Home</a></div>
                  		<div id="links"><a href="#" style="border-right:none;">Home</a></div>
                  		<div id="links"><a href="#" style="border-right:none;">Home</a></div>
                  		<div id="links"><a href="#" style="border-right:none;">Home</a></div>
                  		<div id="links"><a href="#">Home</a></div>
                  		<br style="clear:both" />
			</div><!--End navigation Div-->
             <div id="tab_items">
            	<ul>
                	<li><a href="#" class="selected_red">Home</a></li>|
                    <li><a href="#">Home</a></li>|
                    <li><a href="#">Home</a></li>|
                    <li><a href="#">Home</a></li>
                </ul>
            </div><!--End tab_items Div-->
            </div><!--End navigation_links Div-->
           
            <div id="contents">
              	<div id="main_heading">Administration Panel</div>
                <table class="listig_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <th>HOME</th>
                        <th>HOME</th>
                        <th>HOME</th>
                        <th>HOME</th>
                        <th>HOME</th>
                      </tr>
                        <td><a href="#">Qualityclix</a></td>
                        <td>HOME</td>
                        <td>HOME</td>
                        <td>HOME</td>
                        <td>HOME</td>                      
                        <tr>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                        </tr>  
                        <tr>
                          <td>HOME</td>
                          <td>HOME</td>
                          <td>HOME</td>
                          <td>HOME</td>
                          <td>HOME</td>
                        </tr>
                        <tr>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                        </tr>
                        <tr>
                          <td>HOME</td>
                          <td>HOME</td>
                          <td>HOME</td>
                          <td>HOME</td>
                          <td>HOME</td>
                        </tr>
                        <tr>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                          <td bgcolor="#F8F8F8">HOME</td>
                        </tr>
                        <tr>
                          <td>HOME</td>
                          <td>HOME</td>
                          <td>HOME</td>
                          <td>HOME</td>
                          <td>HOME</td>
                        </tr>
                      <tr>
                        
                  </tr>
                </table>
			</div><!--End body Div-->	
         
         </div>   
            
        </div><!--End text_holder Div-->	
        <div id="footer">
            <div id="bottom">
                <div id="bottom_left"></div>
                <div id="bottom_right"></div>
            </div><!--End bottom Div--><br style="clear:both" />
        </div><!--End footer Div-->
    </div><!--End header Div-->
</div><!--End maincontainer Div-->
</body>
</html>
