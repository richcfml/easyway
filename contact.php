  <?php
if(isset($_POST['send1']))
{
$EmailTo1 = "xenpacubas@yahoo.com"; /* yung ma sendan ng email -  qiusaiyue@hotmail.com */
$Subject1 = "ewo";
$Name1 = Trim(stripslashes($_POST['name1']));
$Email1 = "do-not-reply@easywayordering.com"; /* kung sinong nag email */
$Emailsender1 =  Trim(stripslashes($_POST['email1']));

$msg1 = Trim(stripslashes($_POST['message1']));

$human1 = $_REQUEST1['url'];

$headers1 = "From: <$Email1>\r\n";
$headers1 .= "Reply-To: <$Email>\r\n";
$headers1 .= "MIME-Version: 1.0\r\n";
$headers1 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$Body1 = "Name:" . $Name1 . "<br/>";
$Body1 .= "Email:" . $Emailsender1 . "<br/>";
$Body1 .= "Comments:" . $msg1 . "<br/>";


$success1 = mail($EmailTo1, $Subject1, $Body1, $headers1); 

 if($success1){
 print "<meta http-equiv=\"refresh\" content=\"0;URL=about.php\">";
 }
}
?>