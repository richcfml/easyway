<?php
	$rbOpen = ' checked="checked" ';
	$rbDel = '';
	$result='';
        $rblogin = '';
	if(isset($_POST['submit']))
	{
    	extract($_POST);
		if (isset($_GET['debug']))
		{
			$client_path = "http://staging.easywayordering.com/ewoapi/ewoapi_v1.php?debug=1";
		}
		else
		{
	    	$client_path = "http://staging.easywayordering.com/ewoapi/ewoapi_v1.php";
		}
		$addresslink = str_replace(' ', '+', $street . " " . $city . " " . $state);

		if($rbOpenDel == 'Delivered')
        {
			$rbDel = ' checked="checked" ';
			$rbOpen = '';
                        $rblogin='';
        	$addresslink = str_replace(' ', '+', $street . " " . $city . " " . $state);
			if (isset($_GET['debug']))
			{
         		$result = file_get_contents($client_path.'&apireq=deliver&address='.$addresslink);
			}
			else
			{
         		$result = file_get_contents($client_path.'?apireq=deliver&address='.$addresslink);
			}
        }
        else if($rbOpenDel == 'open')
        {
        	$result = file_get_contents($client_path.'?apireq=open');
			if (isset($_GET['debug']))
			{
         		$result = file_get_contents($client_path.'&apireq=open');
			}
			else
			{
         		$result = file_get_contents($client_path.'?apireq=open');
			}
    	}
        else if($rbOpenDel == 'login')
        {

			$rbDel = '';
			$rbOpen = '';
                        $rblogin=' checked="checked" ';
                        $addresslink = "&email=$Email&rest_slug=$rest_slugs&Fname=$Fname&Lname=$Lname&street1=$street1&street2=$street2&city=$city&state=$state&zip=$zip&phone=$phone";
			
                        if (isset($_GET['debug']))
			{
         		header('Location:ewoapi_v1.php?apireq=login'.$addresslink);
			}
			else
			{
         		header('Location:ewoapi_v1.php?apireq=login'.$addresslink);
			}
    	}
	}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script src="http://www.easywayordering.com/js/jquery.min.js"></script>
        <title>EWO API Test</title>
    </head>
    <body style="font-family: Arial;">
        <form id ="form1" name ="form1" method ="post">
			<div style="margin-left: 30px;">
				<table style="width: 100%; margin: 0px;" border="0" cellpadding="0" cellspacing="0">
					<tr style="height:50px;">
						<td colspan="2">
						</td>
					</tr>
					<tr>
						<td style="width: 100%;" colspan="2">
							<script type="text/javascript" language="javascript">
							$(document).ready(function($) 
							{
								$(".rbOpenDel").click(function()
								{
									if($('#rbOpen').is(':checked'))
									{
										$('#dvAddress').fadeOut();
									}
									else if($('#rbDel').is(':checked'))
									{
                                                                                $('#dvlogin').fadeOut();
										$('#dvAddress').fadeIn();
									}
                                                                        else if($('#rblogin').is(':checked'))
									{
                                                                            $('#dvAddress').fadeOut();
										$('#dvlogin').fadeIn();
									}
								});
							});
							</script>
							<input type ="radio" value ="open" <?php echo($rbOpen); ?> name ="rbOpenDel" id ="rbOpen" class="rbOpenDel"/>Open restaurant
							&nbsp;&nbsp;
							<input type ="radio" value ="Delivered" <?php echo($rbDel); ?> name ="rbOpenDel" id ="rbDel" class="rbOpenDel"/>Delivered given Address
                                                        &nbsp;&nbsp;
							<input type ="radio" value ="login" <?php echo($rblogin); ?> name ="rbOpenDel" id ="rblogin" class="rbOpenDel"/>login/Register
						</td>
					</tr>
					<tr style="height:30px;">
						<td colspan="2">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="dvAddress" style="display: none;">
								<table style="width: 100%; margin: 0px;" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td style="width: 10%;">
											Street:
										</td>
										<td>
											<input type="text" id ="street" name="street" value="34 N Portland Ave"/>
										</td>
									</tr>
									<tr style="height:10px;">
										<td colspan="2">
										</td>
									</tr>
									<tr>
										<td style="width: 10%;">
											City:
										</td>
										<td>
											<input type="text" id ="city" name="city" value ="New York"/>
										</td>
									</tr>
									<tr style="height:10px;">
										<td colspan="2">
										</td>
									</tr>
									<tr>
										<td style="width: 10%;">
											State:
										</td>
										<td>
											<input type="text" id ="state" name="state" value ="NY"/>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr style="height:10px;">
						<td colspan="2">
                                                    <div id="dvlogin" style="display: none;">
								<table style="width: 100%; margin: 0px;" border="0" cellpadding="0" cellspacing="0">
                                                                        <tr>
										<td style="width: 10%;">
											Email:
										</td>
										<td>
											<input type="text" id ="Email" name="Email" value=""/>
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 10%;">
											Restaurant Slugs:
										</td>
										<td>
											<input type="text" id ="rest_slugs" name="rest_slugs" value=""/>
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 10%;">
											First Name:
										</td>
										<td>
											<input type="text" id ="Fname" name="Fname" value=""/>
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 10%;">
											Last Name:
										</td>
										<td>
											<input type="text" id ="Lname" name="Lname" value=""/>
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 10%;">
											Street1:
										</td>
										<td>
											<input type="text" id ="street1" name="street1" value="34 N Portland Ave"/>
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 10%;">
											Street2:
										</td>
										<td>
											<input type="text" id ="street" name="street2" value="207 cypress Ave"/>
										</td>
									</tr>
									<tr style="height:10px;">
										<td colspan="2">
										</td>
									</tr>
									<tr>
										<td style="width: 10%;">
											City:
										</td>
										<td>
											<input type="text" id ="city" name="city" value ="New York"/>
										</td>
									</tr>
									<tr style="height:10px;">
										<td colspan="2">
										</td>
									</tr>
									<tr>
										<td style="width: 10%;">
											State:
										</td>
										<td>
											<input type="text" id ="state" name="state" value ="NY"/>
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 10%;">
											zip:
										</td>
										<td>
											<input type="text" id ="zip" name="zip"/>
										</td>
									</tr>
									<tr style="height:10px;">
										<td colspan="2">
										</td>
									</tr>
									<tr>
										<td style="width: 10%;">
											Phone:
										</td>
										<td>
											<input type="text" id ="phone" name="phone"/>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td style="width: 100%;" colspan="2">
							<input type ="submit" id ="submit" name="submit" value="Submit"/>
						</td>
					</tr>
					<tr style="height:40px;">
						<td colspan="2">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo($result); ?>
						</td>
					</tr>
				</table>
			</div>
        </form>
    </body>
</html>
