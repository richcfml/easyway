<?php
include("../includes/config.php");
include("../includes/function.php");
if(isset($_POST['submit']))
{
    extract($_POST);
   	     
    header('Location: apilogin.php'.'?email='.$email.'&rest_slugs='.trim($rest_slugs).'&Fname='.trim($firstname).'&Lname='.trim($lastname).'&street1='.trim($street1).'&street2='.trim($street2).'&city='.trim($city).'&state='.trim($state).'&zip='.trim($zip).'&phone='.trim($phone));

}
?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
   <body style="font-family: Arial;">
        <form id ="form1" name ="form1" method ="post">
			<div style="margin-left: 30px;">
				<table style="width: 100%; margin: 0px;" border="0" cellpadding="0" cellspacing="0">
					<tr style="height:50px;">
						<td colspan="2">
						</td>
					</tr>
					
					<tr style="height:30px;">
						<td colspan="2">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="dvAddress">
								<table style="width: 100%; margin: 0px;" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td style="width: 20%;">
											Email:
										</td>
										<td>
											<input type="text" id ="email" name="email"/>
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 20%;">
											Restaurant Slugs:
										</td>
										<td>
											<input type="text" id ="rest_slugs" name="rest_slugs" />
										</td>
									</tr>
									
									
                                                                        <tr>
										<td style="width: 20%;">
											First Name:
										</td>
										<td>
											<input type="text" id ="firstname" name="firstname" />
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 20%;">
											Last Name:
										</td>
										<td>
											<input type="text" id ="lastname" name="lastname" />
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 20%;">
											Street1:
										</td>
										<td>
											<input type="text" id ="street1" name="street1" />
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 20%;">
											Street2:
										</td>
										<td>
											<input type="text" id ="street2" name="street2" />
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 20%;">
											City:
										</td>
										<td>
											<input type="text" id ="city" name="city" />
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 20%;">
											State:
										</td>
										<td>
											<input type="text" id ="state" name="state" />
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 20%;">
											Zip:
										</td>
										<td>
											<input type="text" id ="zip" name="zip" />
										</td>
									</tr>
                                                                        <tr>
										<td style="width: 20%;">
											Phone:
										</td>
										<td>
											<input type="text" id ="phone" name="phone" />
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr style="height:10px;">
						<td colspan="2">
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
				</table>
			</div>
        </form>
    </body>

</html>
