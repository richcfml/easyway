<script language="javascript" type="text/javascript">
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
</script>
<?php
$errMessage='';
$confirm=false;
$from_email='';
$email_subject='';

if(isset($_REQUEST['mailid']))
{
	$mailid = $_REQUEST['mailid'];
	dbAbstract::Delete("DELETE from mailing_list where id=".$mailid, 1);
}

if(isset($_POST['submit']))
			{
			$email_id = @$_REQUEST['email_id'];
			$from_email = @$_REQUEST['from_email'];
			$email_subject = @$_REQUEST['email_subject'];
			$mail_body = @$_REQUEST['mailbox'];
			//////////////get resturant name////////////
			$restQry = dbAbstract::Execute("select name, url_name from resturants where id=".$mRestaurantIDCP, 1);
			@$restRs = dbAbstract::returnArray($restQry, 1);
			$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";       

			if (count($email_id) == 0) {
				$errMessage="Please select at least 1 email address from the list";
			} else if($from_email == '') {
				$errMessage = "Please enter from email";
			} else if(!eregi($pattern, $from_email)) {
				
				$errMessage = "Please enter valid from email";
			} else if($email_subject == '') {
				$errMessage = "Please enter email subject";
			} else if($mail_body == '') {
				$errMessage = "Please enter email message";
			} else {
			$objMailML = new testmail();
                        $objMailML->from="info@easywayordering.com";
			 // $MAIL_HOST_NAME="smtpout.secureserver.net";
			  for ($i=0;$i<count($email_id);$i++)
				  {
					  @$mailQry	=	dbAbstract::Execute("select email from mailing_list where id= ".$email_id[$i]."", 1);
					  while(@$mailRs	=	dbAbstract::returnObject($mailQry, 1)){						
						  /*$subject = $email_subject;
						  $mail = new PHPMailer();
						  $mail->IsSMTP(); // telling the class to use SMTP
						  $mail->Host = "smtpout.secureserver.net";// SMTP server
						  $mail->SMTPAuth = true;
						  $mail->Username = 'voice@easywayordering.com';
						  $mail->Password = 'welcome';
						  $mail->From = $from_email;
						  $mail->FromName="";
						  $mail->Sender=""; // indicates ReturnPath header
						  $mail->AddAddress($mailRs->email);
						  $mail->Subject = $subject;
						  $mail->Body = $mail_body;
						  $mail->IsHTML(true);
						  $mail->Send();*/
                                                  $objMailML->sendTo($mail_body, $subject, $mailRs->email, true);
						 
					  } 
			  }// end for
			$confirm = true;
?>
<!--<script language="javascript">
	window.location="./?mod=mailing_list";
</script>
--><? } 
}//end else 
?>

<script language="JavaScript">
function SelectAll(){

  var objForm = document.forms[0];
  var el = document.getElementsByName('email_id[]')
  for(i=0;i<el.length;i++){
      if(el[i].checked){
	  el[i].checked=false;
	  }else{
	  el[i].checked=true;
	  }
  }

}
</script>

<?php
@$mailinglistQry	=	dbAbstract::Execute("select * from mailing_list where resturant_id=".$mRestaurantIDCP, 1);
 $counter = 0;
?>
<div id="main_heading">EXISTING MAILING LIST </div>
<? if ($errMessage != "" ) { ?><div style="margin-left:0px; margin-right:0px; width:" class="msg_done"><?=$errMessage?></div><br />
<? }
if($confirm) { ?>
	<div style="margin-left:0px; margin-right:0px; width:" class="msg_done">Mail messages have been sent. <a href="./?mod=mailing_list&cid=<?=$mRestaurantIDCP?>">Click here</a> for Maling list</div>
<? } else {?>

<form  method="post" action="" id="mailing_list" name="mailing_list">
<table width="100%" border="0" cellspacing="1" class="listig_table">
  <tr>
  	<th ></th>
    <th ><strong>#</strong></th>
    <th ><strong>Email</strong></th>
    <th ><strong>Edit Info</strong></th>
  </tr>
  <tr>
    <td colspan="8"><input type="checkbox" name="checkbox" value="checkbox" onclick="SelectAll();" /> Check All / UnCheck All<br /></td>
  </tr>
  <?php while(@$mailinglistRs	=	dbAbstract::returnObject($mailinglistQry, 1)){?>
   <?  if( $counter++ % 2 == 0)  
   			$bgcolor = '#F8F8F8';
	   else $bgcolor = '';
   ?>
  <tr bgcolor="<?=$bgcolor ?>" >
    <td><input type="checkbox" name="email_id[]" value="<?=$mailinglistRs->id?>"  /></td>
    <td><?=$counter?></td>
    <td><?=$mailinglistRs->email?></td>
    <td><a href="./?mod=<?=$mod?>&item=mailedit&mailid=<?=$mailinglistRs->id?>" style="text-decoration:underline;">Edit</a> | <a href="?mod=<?=$mod?>&mailid=<?=$mailinglistRs->id?>" onclick="return confirm('Are you sure you want to delete this email address from mailing list?')" style="text-decoration:underline;">Delete</a></td>
    
  </tr>
  <? }?>
  <tr>
    <td colspan="8"><br /><input type="checkbox" name="checkbox" value="checkbox" onclick="SelectAll();" /> Check All / UnCheck All</td>
  </tr>
</table>
<div style="float:left; margin:20px 0px 10px 0px;">
<strong>From:*</strong><br />
<input type="text" name="from_email" id="from_email" size="40" value="<?=stripslashes($from_email)?>"/><br />

<strong>Subject:*</strong><br />
<input type="text" name="email_subject" id="email_subject" size="40" value="<?=stripslashes($email_subject)?>" /><br />

<strong>Email Message:*</strong><br />
<textarea  style="width:300px; height:80px;" name="mailbox"  id="mailbox" onKeyDown="limitText(this.form.mailbox,this.form.countdown,160);"></textarea><br /><font size="2">(Maximum characters: 160)<br>You have <input readonly type="text" name="countdown" size="3" value="160"> characters left.</font></div>
<div style="clear:both"></div>
<div style=""><input type="submit" name="submit" value="Send Mail" /></div>
</form>
<? $confirm = false;  }?>