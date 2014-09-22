<!--///////////////////////////////////////////////////////START SCRIPT FOR CALLENTER////////////////////////////////////////////////////////-->
<SCRIPT type="text/javascript">
var bas_cal,dp_cal,ms_cal;      
window.onload = function () {
	stime = new Date();
	//bas_cal = new Epoch('bas_cal','flat',document.getElementById('bas_cal'));
	dp_cal  = new Epoch('dp_cal','popup',document.getElementById('event_date'));
	dp_cal_end  = new Epoch('dp_cal_end','popup',document.getElementById('end_event_date'));
	//ms_cal  = new Epoch('ms_cal','flat',document.getElementById('ms_cal'),true);
	//document.getElementById('load_time').firstChild.nodeValue = '3 calendars loaded in ' +  ((new Date() - stime)/1000.0).toFixed(3) +' seconds.';
}; 
</SCRIPT>
<TABLE id="dp_cal_calendar" class="calendar" classname="calendar" style="position: absolute; top: 800px; left: 310px; display: none; "><TBODY><TR><TD><DIV class="mainheading" classname="mainheading"><INPUT type="button" value="&lt;" title="Go to the previous month"><SELECT><OPTION value="0">Jan</OPTION><OPTION value="1">Feb</OPTION><OPTION value="2">Mar</OPTION><OPTION value="3">Apr</OPTION><OPTION value="4">May</OPTION><OPTION value="5">Jun</OPTION><OPTION value="6">Jul</OPTION><OPTION value="7">Aug</OPTION><OPTION value="8" selected="selected">Sep</OPTION><OPTION value="9">Oct</OPTION><OPTION value="10">Nov</OPTION><OPTION value="11">Dec</OPTION></SELECT><SELECT><OPTION value="2006">2006</OPTION><OPTION value="2007">2007</OPTION><OPTION value="2008">2008</OPTION><OPTION value="2009">2009</OPTION><OPTION value="2010" selected="selected">2010</OPTION><OPTION value="2011">2011</OPTION><OPTION value="2012">2012</OPTION></SELECT><INPUT type="button" value="&gt;" title="Go to the next month"></DIV></TD></TR><TR><TD><TABLE class="cells" classname="cells"><THEAD class="caldayheading" classname="caldayheading"><TR><TH class="wkhead" classname="wkhead"><TH>S</TH><TH>M</TH><TH>T</TH><TH>W</TH><TH>T</TH><TH>F</TH><TH>S</TH></TR></THEAD><TBODY><TR><TD class="wkhead" classname="wkhead">35</TD><TD class="notmnth" classname="notmnth">29</TD><TD class="notmnth" classname="notmnth">30</TD><TD class="notmnth" classname="notmnth">31</TD><TD class="wkday" classname="wkday">1</TD><TD class="wkday" classname="wkday">2</TD><TD class="wkday" classname="wkday">3</TD><TD class="wkend" classname="wkend">4</TD></TR><TR><TD class="wkhead" classname="wkhead">36</TD><TD class="wkend" classname="wkend">5</TD><TD class="wkday" classname="wkday">6</TD><TD class="wkday" classname="wkday">7</TD><TD class="wkday" classname="wkday">8</TD><TD class="wkday" classname="wkday">9</TD><TD class="wkday" classname="wkday">10</TD><TD class="wkend" classname="wkend">11</TD></TR><TR><TD class="wkhead" classname="wkhead">37</TD><TD class="wkend" classname="wkend">12</TD><TD class="wkday" classname="wkday">13</TD><TD class="wkday" classname="wkday">14</TD><TD class="wkday" classname="wkday">15</TD><TD class="wkday" classname="wkday">16</TD><TD class="wkday" classname="wkday">17</TD><TD class="wkend" classname="wkend">18</TD></TR><TR><TD class="wkhead" classname="wkhead">38</TD><TD class="wkend" classname="wkend">19</TD><TD class="wkday" classname="wkday">20</TD><TD class="wkday" classname="wkday">21</TD><TD class="wkday" classname="wkday">22</TD><TD class="wkday" classname="wkday">23</TD><TD class="wkday" classname="wkday">24</TD><TD class="wkend" classname="wkend">25</TD></TR><TR><TD class="wkhead" classname="wkhead">39</TD><TD class="wkend" classname="wkend">26</TD><TD class="wkday" classname="wkday">27</TD><TD class="wkday" classname="wkday">28</TD><TD class="wkday" classname="wkday">29</TD><TD class="wkday curdate" classname="wkday curdate">30</TD><TD class="notmnth" classname="notmnth">1</TD><TD class="notmnth" classname="notmnth">2</TD></TR><TR><TD class="wkhead" classname="wkhead">40</TD><TD class="notmnth" classname="notmnth">3</TD><TD class="notmnth" classname="notmnth">4</TD><TD class="notmnth" classname="notmnth">5</TD><TD class="notmnth" classname="notmnth">6</TD><TD class="notmnth" classname="notmnth">7</TD><TD class="notmnth" classname="notmnth">8</TD><TD class="notmnth" classname="notmnth">9</TD></TR></TBODY></TABLE></TD></TR><TR><TD><DIV><INPUT type="button" value="Clear" title="Clears any dates selected on the calendar"><INPUT type="button" value="Close" title="Close the calendar" class="closeBtn" classname="closeBtn"></DIV></TD></TR></TBODY></TABLE>
<!--///////////////////////////////////////////////////////END SCRIPT FOR CALLENTER//////////////////////////////////////////////////////////-->			
			<?
            ///////////////////////////////////////////Search Order//////////////////////////////
			if(isset($_REQUEST['sch_button'])){
				$search_by = $_REQUEST['search_by'];
				$search_field = $_REQUEST['search_field'];
				$date_field = $_REQUEST['end_event_date'];
			
				if($search_by == 1){	$and = " AND OrderDate like '%$date_field%'";}
				else if($search_by == 2){	$and = " AND OrderID like '%$search_field%'";	}
				else if($search_by == 3){	$and = " AND c.cust_email like '%$search_field%'";	}
								
				$orderquery=mysql_query("select o.*,DATE_FORMAT(OrderDate,'%m/%d/%Y'),c.cust_your_name, c.LastName from customer_registration c,ordertbl o where o.UserID=c.id   AND payment_approv=1 AND o.cat_id = ".$Objrestaurant->id .$and." ORDER BY o.OrderID DESC");
@$numrows=mysql_num_rows($orderquery);
@$search_numrows = $numrows;
			
			} else {
				$orderquery=mysql_query("select o.*,DATE_FORMAT(OrderDate,'%m/%d/%Y'),c.cust_your_name, c.LastName from customer_registration c,ordertbl o where o.UserID=c.id   AND payment_approv=1 AND o.cat_id = ".$Objrestaurant->id." ORDER BY o.OrderID DESC");
@$numrows=mysql_num_rows($orderquery);
					
			}			
			?>
      
<div id="main_heading">RESTAURANT ORDER REPORT</div>
<LINK rel="stylesheet" type="text/css" href="css/epoch_styles.css">
<script type="text/javascript" src="js/epoch_classes.js"></script>
<SCRIPT type="text/javascript" src="epoch_classes.js"></SCRIPT>
<script type="text/javascript">
function display(obj) {
	val = obj.options[obj.selectedIndex].value;
	if( val == '1' ) {
		document.getElementById('end_event_date').style.display = 'block';
		document.getElementById('search_field').style.display = 'none';
		} else {
		document.getElementById('end_event_date').style.display = 'none';
		document.getElementById('search_field').style.display = 'block';		
		}
		document.search_from.search_field.value="";
		document.search_from.end_event_date.value="";
}
</script>
<!--<form method="post" id="search_from" name="search_from" action="">
	<select id="search_by" name="search_by" onchange="display(this)" style="float:left; margin-right:5px;">
    	<option value="0">===Search By===</option>
        <option value="1" <? if($search_by == 1){?> selected="selected"<? }?> >Date</option>
        <option value="2" <? if($search_by == 2){?> selected="selected"<? }?> >Order Id</option>
        <option value="3" <? if($search_by == 3){?> selected="selected"<? }?> >Customer Email</option>
    </select>&nbsp;
   <? if($search_by != 1) {$date_field = "";  }?>
    <input type="text" id="search_field" name="search_field" style="display:<? if ($date_field != "") {?>none <? }else { ?> block <? }?>;float:left; margin-right:5px;" value="<?=$search_field ?>" />&nbsp;<input style="display:<? if ($date_field == "") {?>none <? }else { ?> block <? }?>; float:left;margin-right:5px;" type="text" name="end_event_date" id="end_event_date" value="<?=$date_field ?>" />
    <input type="submit"  value="Submit" id="sch_button" name="sch_button" style="float:left"/>
    <div style="clear:both"></div>
 </form><br />-->
     <? if($numrows>0){?> 
    <table width="100%" cellpadding="4" cellspacing="0" class="listig_table">
      <tr >
        <th width="34"><strong>#</strong></th>
        <th width="100"><strong>Resturant Name</strong></th>
        <th width="66"><strong>Date Placed</strong></th>
        <th width="100"><strong>Customer Name</strong></th>
        <th width="494" style="text-align:center"><strong><br />
          Order Detail</strong>
          <table width="100%" class="tbl_small">
            <tr>
              <th width="22%" >Item Title</th>
               <th width="31%" >Item Detail</th>
              <th width="17%">Quantity</th>
              <th width="26%"style="text-align:right" >Item Price&nbsp;</th>
            </tr>
          </table>
          <br />
<p><strong>        </strong></p></th>
      </tr>
      <?	while($orderRs=mysql_fetch_array($orderquery)){	 	   
	   $OrderID = @$orderRs["OrderID"];

	    ?>
      <!-- test code ends--> 
      <tr  >
        <? $substr = substr($orderRs["DesiredDeliveryDate"],0,10);?>
        <td><a href="./?mod=order&item=detail&OrderID=<? echo $orderRs["OrderID"];?>"><? echo $orderRs["OrderID"];?></a></td>
        <td>
		  <? 
				echo $Objrestaurant->name
	      ?>
        </td>
        <td><? echo $substr; ?></td>
        <td><? echo trim($orderRs["cust_your_name"].' '.$orderRs["LastName"]);?> </td>
         <td>
         	<table width="100%" class="tbl_small">
              		 <?  $prdQuery2 ="select * from orderdetails where orderid = $OrderID";
			     $GrandTotal=0;
				 $prdQuery2= mysql_query($prdQuery2);
				 while($Ord_RS2=mysql_fetch_array($prdQuery2,MYSQL_BOTH)){
				 $ProductID= $Ord_RS2["prd_id"]?>
              	 <tr width="22%">
                	<td width="22%" ><? echo stripslashes(stripcslashes($Ord_RS2["item_title"]))?>
     				<td>
					<? if($Ord_RS2["extra"]) {?>
                    <strong>Extras:</strong>
                    <?
					$str_extra = str_replace('~','<br />',stripslashes(stripcslashes($Ord_RS2["extra"]))); 
					echo str_replace('|','- add '.$currency,$str_extra);
					?>
                    <? } ?>
                    <br />
                    <? if($Ord_RS2["associations"]) {?>
                    <strong>Associated Products:</strong>
					<? 
					   $str_assoc = str_replace('~','<br />',stripslashes(stripcslashes($Ord_RS2["associations"])));
					   echo str_replace('|','- add '.$currency,$str_assoc);
					?>
                    <? } ?>
                    </td>
                    </td><td width="18%"><? echo $Ord_RS2["quantity"]?></td>
                    <td width="25%" style="text-align:right">
						<?
                      
                          $cart_price = $Ord_RS2['retail_price'];
                      ?>
                    <?=$currency?><? echo $cart_price?>
                  </td>
                </tr>
                <? } ?>
                <tr bgcolor="#FFFFCC">
                	<td><strong>Total Price:</strong></td>
                	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                	<td  style="text-align:right"><strong><? echo $orderRs["Totel"]?></strong></td>
                </tr>
            </table>
      </td>
      </tr>
        <? }?>
    </table>
  <? }else{
		 if($search_numrows < 1) {
		?>
        <div align="left"><strong>No record found against this search.</strong>
        <?
		 } else {
		?>
    <div align="left"><strong>There are currently no new orders to review.</strong>
    	<?
		 }
		?>
        </div>
      <? }?>