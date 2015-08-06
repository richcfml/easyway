<?php require_once("../includes/config.php");?> 
<link href="css/adminMain.css" rel="stylesheet" type="text/css" />
 <style type="text/css">
     body
     {
        background-image: none;
     }
 </style>
<?php

    $license_id = $_GET['license_id'];
    $subcription_id = dbAbstract::ExecuteObject("Select chargify_subscription_id from resturants where id = (select  resturant_id from licenses where license_key ='$license_id' limit 0,1)",1);
    $url = $ChargifyURL."subscriptions/".$subcription_id->chargify_subscription_id.".json";
    
    $username = '2aRl08rsgL3H3WiWl5ar';
    $password = 'x';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $mResult = curl_exec($ch);
    curl_close($ch);
    unset($ch);
    
    $mResult = json_decode($mResult);
    $mResult = (object) $mResult;
    $mResult->subscription = (object) $mResult->subscription;
    $mResult = $mResult->subscription;
    $monthName='';
    
    if(!empty($mResult))
    {
        $mResult->credit_card = (object) $mResult->credit_card;
        $mResult->customer = (object) $mResult->customer;
	$mResult->product = (object) $mResult->product;
        $monthNum  = $mResult->credit_card->expiration_month;
        $monthNum = trim($monthNum);
	

        if (($monthNum=="1") || ($monthNum=="01"))
        {
                $monthName = "January";
        }
        else if (($monthNum=="2") || ($monthNum=="02"))
        {
                $monthName = "February";
        }
        else if (($monthNum=="3") || ($monthNum=="03"))
        {
                $monthName = "March";
        }
        else if (($monthNum=="4") || ($monthNum=="04"))
        {
                $monthName = "April";
        }
        else if (($monthNum=="5") || ($monthNum=="05"))
        {
                $monthName = "May";
        }
        else if (($monthNum=="6") || ($monthNum=="06"))
        {
                $monthName = "June";
        }
        else if (($monthNum=="7") || ($monthNum=="07"))
        {
                $monthName = "July";
        }
        else if (($monthNum=="8") || ($monthNum=="08"))
        {
                $monthName = "August";
        }
        else if (($monthNum=="9") || ($monthNum=="09"))
        {
                $monthName = "September";
        }
        else if ($monthNum=="10")
        {
                $monthName = "October";
        }
        else if ($monthNum=="11")
        {
                $monthName = "November";
        }
        else if ($monthNum=="12")
        {
                $monthName = "December";
        }
        if($mResult->state == 'canceled')
        {
           $mResult->next_assessment_at = "Subscription cancels on";
        }
        $price = number_format(round($mResult->product_price_in_cents/100,2),2);
        $price = "$".$price;
        $html =  "<div style='text-align: center;font-size: 20px;margin-top: 12%;'>Subscription Details</div>
            <div id='LoginContainer' style='width:700px;'>
        <div class='form_outer' style='float:left; width:670px;'>
            <table width='100%' border='0'  cellpadding='4' cellspacing='0'>
              <tr align='left' valign='top'>
                <td class='Width'><strong>First Name:</strong></td>
                <td>".$mResult->customer->first_name."</td>
              </tr>
              <tr align='left' valign='top'>
                <td><strong>Last Name:</strong></td>
                <td>".$mResult->customer->last_name."</td>
              </tr>
              <tr align='left' valign='top'>
                <td width='160'><strong>Email:</strong></td>
                <td width='400'>".$mResult->customer->email."</td>
              </tr>
	      <tr align='left' valign='top'>
                <td width='160'><strong>Product Name:</strong></td>
                <td width='400'>".$mResult->product->name."</td>
              </tr>
              <tr align='left' valign='top'>
                <td width='160'><strong>Product Price:</strong></td>
                <td width='400'>".$price."</td>
              </tr>
              <tr align='left' valign='top'>
                <td width='160'><strong>Card Number:</strong></td>
                <td width='400'>".$mResult->credit_card->masked_card_number."</td>
              </tr>
              <tr align='left' valign='top'>
                <td width='160'><strong>Expiration Date:</strong></td>
                        <td width='400'>".$monthName.' '.$mResult->credit_card->expiration_year."</td>
              </tr>
              <tr align='left' valign='top'>
                <td width='160'><strong>Payment Method:</strong></td>
                <td width='400'>".$mResult->payment_collection_method."</td>
              </tr>
              <tr align='left' valign='top'>
                <td width='160'><strong>Payment Type:</strong></td>
                <td width='400'>".$mResult->payment_type."</td>
              </tr>
              <tr align='left' valign='top'>
                <td width='160'><strong>Next Billing Date:</strong></td>
                <td width='400'>".$mResult->next_assessment_at."</td>
              </tr>
            </table>
        </div>
        <div style='clear:both'></div>
        </div>";

       echo $html;
    }
    else
    {
        return $mResult;
    }

?>
