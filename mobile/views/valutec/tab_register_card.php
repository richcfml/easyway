<?

$InputCardNumber = mysql_real_escape_string($_POST['InputCardNumber']);

if (isCardRegistered($InputCardNumber)) {
    echo json_encode(array("success" => "0", "message" => "Invalid card number"));
} else {

    $ResultData = GetRegistration($InputCardNumber);

    $Authorized = $ResultData['Authorized'];
    $ErrMessage = $ResultData['ErrMessage'];
    $reg_data = $ResultData;

    if ($Authorized == "true") {
        $Balance = AddValue($InputCardNumber, $objRestaurant->rewardPoints * $objRestaurant->numberofPoints);
//	 	$Balance=CardBalance($InputCardNumber);

        $loggedinuser->saveCard($InputCardNumber, $Balance['PointBalance'], $Balance['Balance']);
        echo json_encode(array("success" => "1", "message" => "Thank you for registering your card, you now have <u style='font-size:16px;'>" . $Balance['PointBalance'] . "</u> Point(s) and <u style='font-size:16px;'>$" . $Balance['Balance'] . "</u> balance"));
    } else {


        $ResultData = SetRegistration($InputCardNumber, $loggedinuser->cust_your_name . " " . $loggedinuser->LastName, $loggedinuser->cust_odr_address, '', $loggedinuser->cust_ord_city, $loggedinuser->cust_ord_state, $loggedinuser->cust_ord_zip, '', $loggedinuser->cust_email, '', '', '', '', '', '');

        $Authorized = $ResultData['Authorized'];
        $ErrMessage = $ResultData['ErrMessage'];
        $CardNumber = $ResultData['CardNumber'];

        if ($Authorized == "false") {
            echo json_encode(array("success" => "0", "message" => "Invalid card number", "response" => $ResultData));
        } else {

            $ResultData = AddValue($InputCardNumber, $objRestaurant->rewardPoints * $objRestaurant->numberofPoints);
            $Authorized = $ResultData['Authorized'];

            if ($Authorized == "false") {
                echo json_encode(array("success" => "0", "message" => "Invalid card number", "result1" => $ResultData));
            } else {
                $loggedinuser->saveCard($InputCardNumber, $Balance['PointBalance'], $Balance['Balance']);
                echo json_encode(array("success" => "1", "message" => "Thank you for registering your card, you now have <u style='font-size:16px;'>" . $ResultData['PointBalance'] . "</u> Point(s)  and <u style='font-size:16px;'>$" . $Balance['Balance'] . "</u> balance"));
            }
        }
    }
}
?>