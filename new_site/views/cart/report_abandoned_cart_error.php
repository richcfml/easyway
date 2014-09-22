<?
	if(isset($_POST["type"])) {
		if($_POST["type"] == "under_delivery_minimum") {
			$_SESSION["abandoned_cart_error"]["under_delivery_minimum"] = array(
				"zone" => (isset($_POST["zone"]) ? $_POST["zone"] : ""),
				"minTotal" => (isset($_POST["minTotal"]) ? $_POST["minTotal"] : 0)
			);
		} else if($_POST["type"] == "out_of_area") {
			$_SESSION["abandoned_cart_error"]["out_of_area"] = (isset($_POST["msg"]) ? $_POST["msg"] : "");
		}	
	}
?>