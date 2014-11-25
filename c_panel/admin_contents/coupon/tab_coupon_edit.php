<script src="../js/jquery.js" type="text/javascript"></script>
<link href="../css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox.js" type="text/javascript"></script>

<script language="javascript">
	jQuery(document).ready(function($) {
		$('a[rel*=facebox]').facebox();

	});
</script>
<!--//////////////////////////////////////////////////////////START SCRIPT FOR CALLENTER////////////////////////////////////////////////////////-->
<SCRIPT type="text/javascript">
var bas_cal,dp_cal,ms_cal;      
window.onload = function () {
	stime = new Date();
	//bas_cal = new Epoch('bas_cal','flat',document.getElementById('bas_cal'));
	dp_cal  = new Epoch('dp_cal','popup',document.getElementById('event_date'));
	dp_cal_end  = new Epoch('dp_cal_end','popup',document.getElementById('coupon_date'));
	//ms_cal  = new Epoch('ms_cal','flat',document.getElementById('ms_cal'),true);
	//document.getElementById('load_time').firstChild.nodeValue = '3 calendars loaded in ' +  ((new Date() - stime)/1000.0).toFixed(3) +' seconds.';
}; 
</SCRIPT>
<LINK rel="stylesheet" type="text/css" href="css/epoch_styles.css">
<script type="text/javascript" src="js/epoch_classes.js"></script>

<TABLE id="dp_cal_calendar" class="calendar" classname="calendar" style="position: absolute; top: 800px; left: 310px; display: none; "><TBODY><TR><TD><DIV class="mainheading" classname="mainheading"><INPUT type="button" value="&lt;" title="Go to the previous month"><SELECT><OPTION value="0">Jan</OPTION><OPTION value="1">Feb</OPTION><OPTION value="2">Mar</OPTION><OPTION value="3">Apr</OPTION><OPTION value="4">May</OPTION><OPTION value="5">Jun</OPTION><OPTION value="6">Jul</OPTION><OPTION value="7">Aug</OPTION><OPTION value="8" selected="selected">Sep</OPTION><OPTION value="9">Oct</OPTION><OPTION value="10">Nov</OPTION><OPTION value="11">Dec</OPTION></SELECT><SELECT><OPTION value="2006">2006</OPTION><OPTION value="2007">2007</OPTION><OPTION value="2008">2008</OPTION><OPTION value="2009">2009</OPTION><OPTION value="2010" selected="selected">2010</OPTION><OPTION value="2011">2011</OPTION><OPTION value="2012">2012</OPTION></SELECT><INPUT type="button" value="&gt;" title="Go to the next month"></DIV></TD></TR><TR><TD><TABLE class="cells" classname="cells"><THEAD class="caldayheading" classname="caldayheading"><TR><TH class="wkhead" classname="wkhead"><TH>S</TH><TH>M</TH><TH>T</TH><TH>W</TH><TH>T</TH><TH>F</TH><TH>S</TH></TR></THEAD><TBODY><TR><TD class="wkhead" classname="wkhead">35</TD><TD class="notmnth" classname="notmnth">29</TD><TD class="notmnth" classname="notmnth">30</TD><TD class="notmnth" classname="notmnth">31</TD><TD class="wkday" classname="wkday">1</TD><TD class="wkday" classname="wkday">2</TD><TD class="wkday" classname="wkday">3</TD><TD class="wkend" classname="wkend">4</TD></TR><TR><TD class="wkhead" classname="wkhead">36</TD><TD class="wkend" classname="wkend">5</TD><TD class="wkday" classname="wkday">6</TD><TD class="wkday" classname="wkday">7</TD><TD class="wkday" classname="wkday">8</TD><TD class="wkday" classname="wkday">9</TD><TD class="wkday" classname="wkday">10</TD><TD class="wkend" classname="wkend">11</TD></TR><TR><TD class="wkhead" classname="wkhead">37</TD><TD class="wkend" classname="wkend">12</TD><TD class="wkday" classname="wkday">13</TD><TD class="wkday" classname="wkday">14</TD><TD class="wkday" classname="wkday">15</TD><TD class="wkday" classname="wkday">16</TD><TD class="wkday" classname="wkday">17</TD><TD class="wkend" classname="wkend">18</TD></TR><TR><TD class="wkhead" classname="wkhead">38</TD><TD class="wkend" classname="wkend">19</TD><TD class="wkday" classname="wkday">20</TD><TD class="wkday" classname="wkday">21</TD><TD class="wkday" classname="wkday">22</TD><TD class="wkday" classname="wkday">23</TD><TD class="wkday" classname="wkday">24</TD><TD class="wkend" classname="wkend">25</TD></TR><TR><TD class="wkhead" classname="wkhead">39</TD><TD class="wkend" classname="wkend">26</TD><TD class="wkday" classname="wkday">27</TD><TD class="wkday" classname="wkday">28</TD><TD class="wkday" classname="wkday">29</TD><TD class="wkday curdate" classname="wkday curdate">30</TD><TD class="notmnth" classname="notmnth">1</TD><TD class="notmnth" classname="notmnth">2</TD></TR><TR><TD class="wkhead" classname="wkhead">40</TD><TD class="notmnth" classname="notmnth">3</TD><TD class="notmnth" classname="notmnth">4</TD><TD class="notmnth" classname="notmnth">5</TD><TD class="notmnth" classname="notmnth">6</TD><TD class="notmnth" classname="notmnth">7</TD><TD class="notmnth" classname="notmnth">8</TD><TD class="notmnth" classname="notmnth">9</TD></TR></TBODY></TABLE></TD></TR><TR><TD><DIV><INPUT type="button" value="Clear" title="Clears any dates selected on the calendar"><INPUT type="button" value="Close" title="Close the calendar" class="closeBtn" classname="closeBtn"></DIV></TD></TR></TBODY></TABLE>
<!--///////////////////////////////////////////////////////END SCRIPT FOR CALLENTER///////////////////////////////////////////////////////////////-->	
<script language="javascript" type="text/javascript">
	function radioselect( radioVal ) {
		document.getElementById(radioVal).checked = true;
	}
</script>


<style type="text/css">
#dhtmltooltip{
position: absolute;
width: 300px;
border: 2px solid #E4E4E4;
padding: 5px;
background-color: #F4F4F4;
visibility: hidden;
z-index: 100;
font-size:11px;
color:#585858;
}

#dhtmltooltip span {
	font-size:14px;
	font-weight:bold;
	color:#000;
	}

</style>
<div id="dhtmltooltip"></div>

<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}
document.onmousemove=positiontip
</script>



<? 	$errMessage='';

	if (isset($_POST['submit'])){
		
				$couponId				=	$_POST['coupon_id'];
				$coupon_title			=	$_POST['coupon_title'];
				$coupon_code			=	$_POST['coupon_code'];
				$min_order_total    	=   $_POST['min_order_total'];
				$coupon_discount		=	$_POST['coupon_discount'];
				$discount_in			= 	$_POST['discount_in'];
				$coupon_discount_type2 	= 	$_POST['coupon_discount_type2'];
				$coupon_discount_type3 	= 	$_POST['coupon_discount_type3'];
				$discount_applied		= 	$_POST['discount_applied'];
				$coupon_items1			=	$_POST['coupon_items1'];
				$coupon_items2			=	$_POST['coupon_items2'];
				$coupon_items3			=	$_POST['coupon_items3'];
				$type1_time				=	$_POST['type1_time'];
				$coupon_user_date		=	$_POST['coupon_date'];
				$coupon_des				=	$_POST['coupon_des'];
				$type					=	$_POST['type'];
				$sys_date				=	time();
				$errMessage				=	"";
				
				//to check wheather same coupon code is already exists in the data base....
				$couponCodeQuery = mysql_query("SELECT * FROM coupontbl WHERE coupon_code ='".$coupon_code."' AND coupon_id !=$couponId  AND resturant_id= ".$Objrestaurant->id);
				@$CouponCodeNumRows = mysql_num_rows($couponCodeQuery);
				   
			if($coupon_title == ''){
					$errMessage="Please Enter Coupon Title";
			}elseif($coupon_code == ''){
					$errMessage="Please Enter Coupon Code";
			}elseif($CouponCodeNumRows > 0){
					$errMessage="Coupon Code Already Exists in the database";
			}elseif($min_order_total == '' && $discount_applied == "whole order"){
					$errMessage="Please Enter Minimum Order Total";
			}elseif($coupon_user_date == '' ){
					$errMessage="Please Enter coupon_date";
			}elseif($type1_time < 0 ){
					$errMessage="Please Enter coupon time";
			}elseif($coupon_discount == '' && $discount_applied == 'whole order'){
					$errMessage="Please Enter Coupon Discount";
			}elseif($coupon_discount_type2 == '' && $discount_applied == 'selected items' && $type == "2"){
					$errMessage="Please Enter Coupon Discount";	
			}elseif($coupon_items1 == '' && $discount_applied == 'selected items' && $type == "2"){
					$errMessage="Please Enter first items";	
			}elseif($coupon_items2 == '' && $discount_applied == 'selected items' && $type == "2"){
					$errMessage="Please Enter second items";	
			}elseif($coupon_items3 == '' && $discount_applied == 'selected items' && $type == "3"){
					$errMessage="Please Enter first items";		
			}elseif($coupon_discount_type3 == '' && $discount_applied == 'selected items' && $type == "3"){
					$errMessage="Please Enter Coupon Discount";	
			}else{
					if( $discount_applied == "whole order" ) {
					 
						mysql_query("UPDATE coupontbl SET coupon_title = '".addslashes($coupon_title)."',coupon_code = '".addslashes($coupon_code)."',	min_order_total = '$min_order_total',coupon_discount = '$coupon_discount',discount_in = '$discount_in',coupon_date = '".addslashes($coupon_user_date)."',coupon_time= '".addslashes($type1_time)."',coupon_type = '1', coupon_des = '".addslashes($coupon_des)."' where coupon_id=$couponId");
					}else if ( $discount_applied == "selected items" && $type == "2" ) {
						mysql_query("UPDATE coupontbl SET coupon_title = '".addslashes($coupon_title)."',coupon_code = '".addslashes($coupon_code)."',	min_order_total = '$min_order_total',coupon_discount = '$coupon_discount_type2',discount_in = '$discount_in',coupon_date = '".addslashes($coupon_user_date)."',coupon_type = '2', coupon_items1= '".addslashes($coupon_items1)."',coupon_items2= '".addslashes($coupon_items2)."' ,coupon_time= '".addslashes($type1_time)."', coupon_des = '".addslashes($coupon_des)."' where coupon_id=$couponId");
					}  else if ( $discount_applied == "selected items" && $type == "3" ) {
					mysql_query("UPDATE coupontbl SET coupon_title = '".addslashes($coupon_title)."',coupon_code = '".addslashes($coupon_code)."',	min_order_total = '$min_order_total',coupon_discount = '$coupon_discount_type3',discount_in = '$discount_in',coupon_date = '".addslashes($coupon_user_date)."',coupon_time= '".addslashes($type1_time)."',coupon_type = '3', coupon_items1= '".addslashes($coupon_items3)."',coupon_items2= '' ,coupon_des = '".addslashes($coupon_des)."' where coupon_id=$couponId");
					}							
			?>
<script language="javascript">
				 	//window.location="./?mod=coupon";
			 </script>
<?										 						
			} // end of else
	}// end submit
?>

<div id="main_heading">EDIT / REMOVE COUPONS</div>
<? if ($errMessage != "" ) { ?><div class="msg_done"><?=$errMessage?></div> <? }?> 

  <?
	 if($_REQUEST['coupon_id']){
		 				$coupon_id 			= 	$_REQUEST['coupon_id'];
						 
 						$coupon_info_qry 	=	mysql_query("select * from coupontbl where coupon_id = $coupon_id");	
						$coupon_infoRs		=	mysql_fetch_array($coupon_info_qry);
						$coupon_title		=	$coupon_infoRs['coupon_title'];
						$coupon_code		=	$coupon_infoRs['coupon_code'];
						$min_order_total	=	$coupon_infoRs['min_order_total'];
						$coupon_date		=	$coupon_infoRs['coupon_date']; 
						$coupon_time		=	$coupon_infoRs['coupon_time'];
						$coupon_type		=	$coupon_infoRs['coupon_type'];
						$coupon_items1		=	$coupon_infoRs['coupon_items1'];
						$coupon_items2		=	$coupon_infoRs['coupon_items2'];
						$coupon_discount	=	$coupon_infoRs['coupon_discount'];
						$discount_in		=	$coupon_infoRs['discount_in'];
						$coupon_des			=	$coupon_infoRs['coupon_des'];
						//echo "DISCOUNT IN => ".$discount_in;
	} 
?>
<div class="form_outer">
 <form name="form1" method="post" action=""  >
    <table width="100%" border="0" cellpadding="4" cellspacing="0">
      <tr align="left" valign="top">
        <td width="172"><strong>Coupon Title:</strong></td>
        <td width="973"><input name="coupon_title" type="text" size="40" id="coupon_title" value="<?=@$coupon_title?>"></td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Coupon Code:</strong></td>
        <td><input name="coupon_code" maxlength="11" type="text" size="40" id="coupon_code" value="<?=@$coupon_code?>"></td>
      </tr>
      <tr align="left" valign="top">
        <td width="172"><strong>Minimum Order Total:</strong></td>
        <td><input name="min_order_total" type="text" size="40" id="min_order_total" value="<?=@$min_order_total?>"></td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Coupon Date:</strong></td>
        <td><input name="coupon_date" type="text" size="23" id="coupon_date" value="<?=@$coupon_date?>">
        	 <select id="type1_time" name="type1_time"  onfocus="radioselect('type2');"  >
              	<option value="-1" >Select Time</option>
                <?
                for($i = 0; $i<= 23; $i++) {
					if( $i < 10 )
						$leading_zeros = "0";
					else 
						 $leading_zeros = "";
					
				?><option value="<?=$leading_zeros.$i;?>00" <? if($leading_zeros.$i."00" == $coupon_time ) {?> selected="selected" <? }?>   ><?=$leading_zeros.$i; ?></option>
				<?
                    }
				?>
              </select>
        </td>
      </tr>
      	  <?
      if ($coupon_type == 2 || $coupon_type == 3) {
          $none_str = "block";
          $coupon_discount_none_str = "none";
          $select_item_check = "checked";
          $whole_order_check = "";
		  $caption1_display = "none";
      } else {
          $none_str = "none";
          $coupon_discount_none_str = "";
          $select_item_check = "";
          $whole_order_check = "checked";
		  $caption2_display = "none";
      }
      ?>
      <tr align="left" valign="top">
        <td><strong>Discount in:</strong></td>
        <td>
            <input type="radio" name="discount_in" id="discount_in" value="%" <? if($discount_in == "%") {?> checked="checked" <? }?>  />%&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="discount_in" id="discount_in" value="$" <? if($discount_in == "$") {?> checked="checked" <? }?> /><?=$currency?>
        </td>
      </tr>    
      <tr align="left" valign="top">
        <td><strong>Discount Applied On:</strong></td>
        <td>Selected Items<input type="radio"  name="discount_applied" id="discount_applied" value="selected items"  onclick="document.getElementById('selected_items1').style.display = 'block'; document.getElementById('discount_msg').style.display = 'block'; document.getElementById('caption2').style.display = 'none'; document.getElementById('caption1').style.display = 'block'; document.getElementById('selected_items2').style.display = 'block'; document.getElementById('whole_order').style.display = 'none';  " <?=$select_item_check?>/> &nbsp;&nbsp;Whole Order<input type="radio"  name="discount_applied" id="discount_applied" value="whole order" onclick="document.getElementById('selected_items1').style.display = 'none'; document.getElementById('discount_msg').style.display = 'none'; document.getElementById('selected_items2').style.display = 'none'; document.getElementById('whole_order').style.display = 'block'; document.getElementById('caption2').style.display = 'block'; document.getElementById('caption1').style.display = 'none'; " <?=$whole_order_check?>/></td>
      </tr>
      <tr align="left" valign="top">
        <td><span id="caption1"  style="display:<? if($coupon_type == 1) { ?> none; <? }?>"><strong>Discount Options:</strong></span>
        	<br />
        	<span id="caption2" style="display:<? if($coupon_type != 1) { ?> none; <? }?>"><strong>Discount:</strong></span></td>
        <td><table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2" ><span id="discount_msg" style="padding:5px; display:<?=$none_str?>;  background-color:#FF6; font-size:13px;">Please select one of the options below to apply discount.</span></td>
        </tr>
        <tr>
          <td colspan="2" style="padding:5px;"></td>
        </tr>
          <tr id="selected_items1" style="display:<?=$none_str?>; border:#DFDFDF solid thin; padding:5px;">
            <td width="120px" ><input type="radio"  name="type" id="type2" value="2" <? if ($coupon_type == 2) { ?> checked <? }?>/>&nbsp;Option 1:&nbsp;(<a style="text-decoration:none;"  onMouseover="ddrivetip('Ex: 50% off french fries when you order a chicken sandwich.')" onMouseout="hideddrivetip()"  href="#"   >?</a>)&nbsp;&nbsp;</td>
            <td style="padding-bottom:10px"><input name="coupon_discount_type2" type="text" size="5" id="coupon_discount_type2" value="<? if($coupon_type == 2) {echo @$coupon_discount; }?>" onfocus="radioselect('type2');"> OFF &nbsp; 
             <? if($coupon_type == 2)  
				$str = explode(",",$coupon_items1);
				 for($i = 0; $i < count($str); $i++) {
					$items_query = mysql_query("SELECT  item_title FROM product WHERE prd_id='".$str[$i]."' ");
					$items_rows = mysql_fetch_array($items_query);
					//echo stripslashes( ($items_rows['item_title']) ); 
					if($i == 0  ) {
						$items_str =   $items_rows['item_title'];
					} else {
						$items_str .= ", ".  $items_rows['item_title'];	
					}	
				 }
				 //echo "<span style='color:#933'>".stripslashes($items_str)."</span>";
			?>
            <span id="selitems1" style="color:#933;"><?=stripslashes(rtrim($items_str,', '))?></span>
            <a  href="admin_contents/coupon/popup.php?coupon_item=1&catid=<?=$mRestaurantIDCP?>&couponid=<?=$coupon_id?>"  rel="facebox" >
            <img style="border:none" src="../images/Select-icon.png" title="Add Items"  /></a> &nbsp;when Order a &nbsp; 
             <?  if($coupon_type == 2)
				$str1 = explode(",",$coupon_items2);
				 for($i = 0; $i < count($str1); $i++) {
					$items_query1 = mysql_query("SELECT  item_title FROM product WHERE prd_id='".$str1[$i]."' ");
					$items_rows1 = mysql_fetch_array($items_query1);
					//echo stripslashes( ($items_rows['item_title']) ); 
					if($i == 0  ) {
						$items_str1 =   $items_rows1['item_title'];
					} else {
						$items_str1 .= ", ".  $items_rows1['item_title'];	
					}
					
					
				 }
				//echo "<span style='color:#933'>".stripslashes($items_str1)."</span>";
			?>
            <span id="selitems2" style="color:#933;"><?=stripslashes(rtrim($items_str1,', '))?></span>
            <a  href="admin_contents/coupon/popup.php?coupon_item=2&catid=<?=$mRestaurantIDCP?>&couponid=<?=$coupon_id?>"  rel="facebox" ><img style="border:none" src="../images/Select-icon.png" title="Add Items"  /></a> 
            &nbsp;&nbsp;</td>
          </tr>
          <tr height="5px;"><td colspan="2"></td></tr>
          <tr id="selected_items2" style="display:<?=$none_str?>; border:#DFDFDF solid thin; padding:5px;">
            <td width="120px" ><input type="radio"  name="type" id="type3" value="3" <? if ($coupon_type == 3) { ?> checked <? }?> />&nbsp;Option 2:&nbsp;(<a style="text-decoration:none;"  onMouseover="ddrivetip('Ex: <?=$currency?>1.00 off any appetizer.')" onMouseout="hideddrivetip()"  href="#"   >?</a>)&nbsp;&nbsp;</td>
            <td><input name="coupon_discount_type3" type="text" size="5" id="coupon_discount_type3" value="<? if($coupon_type == 3) { echo @$coupon_discount; }?>" onfocus="radioselect('type3');"> OFF any 
             <? 
			 $items_str2="";
			 if($coupon_type == 3)
			 $str2=array();
				$str2 = explode(",",$coupon_items1);
				 for($i = 0; $i < count($str2); $i++) {
					$items_query2 = mysql_query("SELECT cat_name FROM categories WHERE cat_id='".$str2[$i]."' ");
					$items_rows2 = mysql_fetch_array($items_query2);
					//echo stripslashes( ($items_rows['item_title']) ); 
					if($i == 0  ) {
						$items_str2 =   $items_rows2['cat_name'];
					} else {
						$items_str2 .= ", ".  $items_rows2['cat_name'];	
					}
					
						
				 }
				//echo "<span style='color:#933'>".stripslashes($items_str2)."</span>";
			?>
             <span id="selitems3" style="color:#933;"><?=stripslashes($items_str2)?></span>
            &nbsp;<a  href="admin_contents/coupon/popup.php?coupon_item=3&catid=<?=$mRestaurantIDCP?>&couponid=<?=$coupon_id?> "  rel="facebox" ><img style="border:none" src="../images/Select-icon.png" title="Add Categories"  /></a></td>
          </tr>
          <tr id="whole_order" style="display:<?=$coupon_discount_none_str?>">
            <td><input name="coupon_discount" type="text" size="26" id="coupon_discount" value="<? if($coupon_type == 1) { echo @$coupon_discount; }?>"></td>
          </tr>
        </table></td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Coupon Description:</strong></td>
        <td><textarea name="coupon_des" cols="40" rows="6" id="coupon_des"><?=@$coupon_des?></textarea>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td>&nbsp;</td>
        <input type="hidden" name="coupon_id" id="coupon_id" value="<?=$coupon_id ?>" />
       	<input type="hidden" name="coupon_items1" id="coupon_items1" value="<?=$coupon_items1 ?>" />
        <input type="hidden" name="coupon_items2" id="coupon_items2" value="<?=$coupon_items2 ?>" />
        <input type="hidden" name="coupon_items3" id="coupon_items3" value="<?=$coupon_items1?>" />
        <td><input type="submit" name="submit" value="Update Coupon"></td>
      </tr>
      
   </table>
  </form>
</div>
