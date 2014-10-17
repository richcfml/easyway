<?php
function mail_attachment($filename,  $mailto, $from_mail,   $subject, $message) {
    $file =$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid 	= md5(uniqid(time()));
    $name 	= basename($file);
    $header = "From: orders <".$from_mail.">\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= $uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= $uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= ".$uid.";
    if (mail($mailto, $subject, "", $header)) {
        echo "mail send ... OK"; // or use booleans here
    } else {
        echo "mail send ... ERROR!";
    }
}


$my_file = "data62.pdf";
$from	= "orders@easywayordering.com";
//$to 	= "qasimqc.721@gmail.com";
$to	= "qasim@qualityclix.com";
//$to		= "qasimqc@hotmail.com";
//$to		= "awaaz_q@yahoo.com";
$my_subject = "This is a mail with attachment.";
$my_message = "Hallo,This is test mail body.";
mail_attachment($my_file,  $to, $from, $my_subject, $my_message);


?>