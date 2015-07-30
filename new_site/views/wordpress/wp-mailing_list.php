<?php 	if($_POST) {
		
		if($_POST['discount_opt'] == "1" || $_POST['discount_opt'] == "3") {
			// to avoid duplicate email insertion...
			$emailQry = dbAbstract::Execute("select * from mailing_list where email='".$_POST['email']."'");
			$numRows=dbAbstract::returnRowsCount($emailQry);
			if($numRows == 0) {		
				dbAbstract::Insert("insert into mailing_list (email, resturant_id) VALUES ('".$_POST['email']."','". $objRestaurant->id ."')");	
			}
		}  
		
		
		 if($_POST['discount_opt'] == "2" || $_POST['discount_opt'] == "3") {
		
			$email_str = "";
			$email_str = $_POST['mobile']."@".$_POST['carrier'];
			
			$emailQry = dbAbstract::Execute("select * from mailing_list where email='".$email_str ."'");
			$numRows=dbAbstract::returnRowsCount($emailQry);
			
			if($numRows == 0) {
				dbAbstract::Insert("insert into mailing_list (email, resturant_id) VALUES ('".$email_str."','". $objRestaurant->id ."')");	
			}
		}  
		
		 
 		 redirect($SiteUrl .$objRestaurant->url ."/?addtolist=yes&wp_api=load_resturant" );exit;
		
	}	
	
?>
<script language="javascript">	
	$(document).ready(function() {
		$(".close").click(function() {
			$(document).trigger('close.facebox');
		});
	});
 
			
function validate_form() 
{
	valid = true;
	//alert(document.getElementById('radio_bymail').value);
	 var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if (  ( ( document.getElementById('radio_bymail').checked == true) || ( document.getElementById('radio_byboth').checked == true)  ) && ( document.getElementById('email').value == "") )
    {
			alert ( "Please fill in the 'Email' box." );
	        valid = false;
		
	} else if (  ( ( document.getElementById('radio_bymail').checked == true) || ( document.getElementById('radio_byboth').checked == true)  ) && (reg.test(document.getElementById('email').value) == false) )
    {
			alert ( "Please fill in the ' Email' box with Correct Formate." );
	        valid = false;
			
    } else if (  ( ( document.getElementById('radio_bymobile').checked == true) || ( document.getElementById('radio_byboth').checked == true) ) && (document.getElementById('mobile').value == "") )
    {
       
	    alert ( "Please fill in the 'mobile number' box." );
        valid = false;
    
	} else if ( ( ( document.getElementById('radio_bymobile').checked == true) || ( document.getElementById('radio_byboth').checked == true) ) && (document.getElementById('carrier').value == "-1") )
    {
       
	    alert ( "Please select the ' carrier." );
        valid = false;
    
	}  

    return valid;

}

function digitsOnly(obj){
  obj.value=obj.value.replace(/[^\d]/g,'');
}

</script>

<body style="background-color:#FFFFFF">
<center><h2 style="background-color:#EFEFEF; padding:5px 0px 5px 0px; font-size:16px;">Join VIP List1</h2></center>
 <div class="success" id="viprewarmessage" style="display:none;"></div>
 
  <!-- START SMS AND EMAIL MARKETING-->
    <div style="float:left; margin-left:0; height:300px; font-size:12px;"><br />
      Please enter your info below and we will send discount codes right to your phone or email. We will never share your information with any 3rd party and you can opt out any time. <br />
      <br /><br />
      <form method="post" action="?item=mailinglist&wp_api=mailinglist" id="frm_mailing_list" name="frm_sms_marketing" onSubmit=" return validate_form()">
        I want to receive discounts by: Email
        <input type="radio"  name="discount_opt" id="radio_bymail" value="1" onClick="document.getElementById('email_div').style.display = 'block'; document.getElementById('submit_div').style.display = 'block'; document.getElementById('mobile_div').style.display = 'none';" />
        &nbsp;&nbsp;Mobile
        <input type="radio"  name="discount_opt" id="radio_bymobile" value="2" onClick="document.getElementById('email_div').style.display = 'none'; document.getElementById('submit_div').style.display = 'block'; document.getElementById('mobile_div').style.display = 'block';" />
        &nbsp;&nbsp;Both
        <input type="radio"  name="discount_opt" id="radio_byboth" value="3" onClick="document.getElementById('email_div').style.display = 'block'; document.getElementById('submit_div').style.display = 'block'; document.getElementById('mobile_div').style.display = 'block';" />
        <div style="clear:both"></div>
        <div id="email_div" style="float:left; margin-top:10px; display:none "> Enter Your Email Address:&nbsp;&nbsp;&nbsp;
          <input type="text" name="email" id="email" size="32" />
        </div>
        <div style="clear:both"></div>
        <div id="mobile_div" style="float:left; margin-top:10px; display:none"> Enter Your mobile No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="mobile" type="text" id="mobile" size="11" maxlength="10" onKeyUp="digitsOnly(this)" onBlur="digitsOnly(this)"  />
          @
          <select id="carrier" name="carrier">
            <option value="-1">Select Carrier</option>
            <option value="vmobl.com">Virgin Mobile</option>
            <option value="txt.att.net">AT&T</option>
            <option value="message.alltel.com">Alltel</option>
            <option value="myboostmobile.com">Boost Mobile</option>
            <option value="mymetropcs.com">Metro PCS</option>
            <option value="messaging.nextel.com">Nextel</option>
            <option value="messaging.sprintpcs.com">Sprint PCS</option>
            <option value="tmomail.net">T-Mobile</option>
            <option value="vtext.com">Verizon</option>
            <option value="vodafone.net">Vodafone</option>
            <option value="email.uscc.net">US Cellular</option>
          </select>
        </div>
        <div style="clear:both"></div>
        <div  id="submit_div"style="margin:10px 0 0 155px; display:none" >
          <input  type="submit" name="submit" value="Submit" />
        </div>
      </form>
      <br />
</div>
<!--END SMS AND EMAIL MARKETING-->  
<br class="clearfloat" /><!--[if IE 6]><br class="clearfloat" /><![endif]--><!--[if IE 7]><br class="clearfloat" /><![endif]-->
    <div style="float:right"><input id="close" class="close" type="image" src="<?=$SiteUrl?>images/closelabel.gif" /></div>
</body>

