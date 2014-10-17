<?php
	require_once("../includes/config.php");
	
	if(!empty($_REQUEST["subscription_id"]) && !empty($_REQUEST["product_id"])) {
		
		$resturant = mysql_query("SELECT id FROM resturants WHERE chargify_subscription_id='". $_REQUEST["subscription_id"] ."' LIMIT 1");
		if(mysql_num_rows($resturant) > 0) {
			$resturant = mysql_fetch_assoc($resturant);
			$resturant = $resturant["id"];
			// echo "UPDATE resturants
				// SET chargify_subscription_canceled=0,status=1
				// WHERE id=$resturant";
			mysql_query("
				UPDATE resturants
				SET chargify_subscription_canceled=0,status=1
				WHERE id=$resturant
			");
			// echo "
				// UPDATE licenses
				// SET chargify_subscription_canceled=0,status='activated'
				// WHERE resturant_id=$resturant
			// ";
			mysql_query("
				UPDATE licenses
				SET chargify_subscription_canceled=0,status='activated'
				WHERE resturant_id=$resturant
			");
			header("location: /c_panel/login.php?msg=chargify_update_success");
		} else {
			$error = true;
		}
	} else {
		$error = true;
	}
	
	if($error) {
		echo "
			<html>
				<body>
					<h1 style='text-align: center; margin: 50px; background-color: #e0e0e0;'>Provided data is incomplete</h1>
				</body>
			</html>";
	}
?>