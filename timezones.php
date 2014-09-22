<head>
	<title>
		EWO - Time Zones
	</title>
</head>
<bod style="line-height: 2; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
<?php
echo("<strong>US</strong><br />");
date_default_timezone_set('US/Eastern');
echo("<span style='color: maroon;'>Eastern:</span> ".date("l, j F, Y, g:i A")."<br />");
date_default_timezone_set('US/Hawaii');
echo("<span style='color: maroon;'>Hawaii:</span> ".date("l, j F, Y, g:i A")."<br />");
date_default_timezone_set('US/Alaska');
echo("<span style='color: maroon;'>Alaska:</span> ".date("l, j F, Y, g:i A")."<br />");
date_default_timezone_set('US/Pacific');
echo("<span style='color: maroon;'>Pacific:</span> ".date("l, j F, Y, g:i A")."<br />");
date_default_timezone_set('US/Mountain');
echo("<span style='color: maroon;'>Mountain:</span> ".date("l, j F, Y, g:i A")."<br />");
date_default_timezone_set('US/Central');
echo("<span style='color: maroon;'>Central:</span> ".date("l, j F, Y, g:i A")."<br /><br /><br />");

echo("<strong>Canada</strong><br />");
date_default_timezone_set('Canada/Pacific');
echo("<span style='color: maroon;'>Pacific:</span> ".date("l, j F, Y, g:i A")."<br />");
date_default_timezone_set('Canada/Central');
echo("<span style='color: maroon;'>Centeral:</span> ".date("l, j F, Y, g:i A")."<br />");
date_default_timezone_set('Canada/Atlantic');
echo("<span style='color: maroon;'>Atlantic:</span> ".date("l, j F, Y, g:i A")."<br />");
date_default_timezone_set('Canada/Mountain');
echo("<span style='color: maroon;'>Mountain:</span> ".date("l, j F, Y, g:i A")."<br />");
date_default_timezone_set('Canada/Eastern');
echo("<span style='color: maroon;'>Eastern:</span> ".date("l, j F, Y, g:i A")."<br />");
date_default_timezone_set('Canada/Newfoundland');
echo("<span style='color: maroon;'>Newfoundland:</span> ".date("l, j F, Y, g:i A")."<br /><br /><br />");

echo("<strong>Europe</strong><br />");
date_default_timezone_set('Europe/London');
echo("<span style='color: maroon;'>Europe:</span> ".date("l, j F, Y, g:i A")."<br />");
?>
</body>