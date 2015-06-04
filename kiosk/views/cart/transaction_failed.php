<META http-equiv="refresh" content="30; URL=<?=$SiteUrl.$objRestaurant->url."/?kiosk=1" ?>">
<?php 
    $cart_total=$cart->grand_total();
    if(!is_numeric($cart_total) || $cart_total<=0) 
    {
        header("location: ". $SiteUrl .$objRestaurant->url ."/?kiosk=1");
        exit;	
    }

    if(!isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "off") 
    {
        header("location: ". $SiteUrl .$objRestaurant->url ."/?kiosk=1");
        exit;
    }

    $response=0;

    if(isset($_GET['response_code']))
    {
        $response=$_GET['response_code']; 
    }
?>
<div id="thank_you_area" style="padding:5px;">
 
<?php
    $error_msg = "Transaction failed. ";
    $error_msg .= "<br /><strong>Error Code: </strong>".$response;
    if ($response==1000)
    {
        $error_msg .= "<br /><strong>Error Message: </strong>Missing or invalid Parameters";
    }
    	
    $cart->destroysession();
    $loggedinuser->destroysession();
?>
	<div id="error_img"><img src="<?=$SiteUrl?>images/dialog_warning.png" width="64" /></div>
	<div id="error_message" style="font-size:12px;">
	<?=$error_msg?>
	<br /><br />
 	<input type="button" onclick="location.href='<?=$SiteUrl.$objRestaurant->url."/?kiosk=1";?>'" name="btnStart" id="btnStart" Value="Start New Order" class="delivery_button" style="width: 120px; margin: 0px !important; cursor: hand; cursor: pointer;" />
	</div>
	<br />
</div>
<br style="clear: both;" />