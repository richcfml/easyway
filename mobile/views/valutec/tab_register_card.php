<?

$InputCardNumber = dbAbstract::returnRealEscapedString($_POST['InputCardNumber']);

if (isRegisteredValutecCard($InputCardNumber)) {
    echo json_encode(array("success" => "0", "message" => "Invalid card number"));
} else {

    $ResultData = valutecGetRegistration($InputCardNumber);

    $Authorized = $ResultData['Authorized'];
    $ErrMessage = $ResultData['ErrMessage'];
    $reg_data = $ResultData;

    if ($Authorized == "true") {
        $Balance = valutecAddValue($InputCardNumber, $objRestaurant->rewardPoints * $objRestaurant->numberofPoints);
//	 	$Balance=valutecCardBalance($InputCardNumber);

        $loggedinuser->saveUserValutecCard($InputCardNumber, $Balance['PointBalance'], $Balance['Balance']);
        echo json_encode(array("success" => "1", "message" => "Thank you for registering your card, you now have <u style='font-size:16px;'>" . $Balance['PointBalance'] . "</u> Point(s) and <u style='font-size:16px;'>$" . $Balance['Balance'] . "</u> balance"));
    } else {


        $ResultData = valutecSetRegistration($InputCardNumber, $loggedinuser->cust_your_name . " " . $loggedinuser->LastName, $loggedinuser->cust_odr_address, '', $loggedinuser->cust_ord_city, $loggedinuser->cust_ord_state, $loggedinuser->cust_ord_zip, '', $loggedinuser->cust_email, '', '', '', '', '', '');

        $Authorized = $ResultData['Authorized'];
        $ErrMessage = $ResultData['ErrMessage'];
        $CardNumber = $ResultData['CardNumber'];

        if ($Authorized == "false") {
            echo json_encode(array("success" => "0", "message" => "Invalid card number", "response" => $ResultData));
        } else {

            $ResultData = valutecAddValue($InputCardNumber, $objRestaurant->rewardPoints * $objRestaurant->numberofPoints);
            $Authorized = $ResultData['Authorized'];

            if ($Authorized == "false") {
                echo json_encode(array("success" => "0", "message" => "Invalid card number", "result1" => $ResultData));
            } else {
                $loggedinuser->saveUserValutecCard($InputCardNumber, $Balance['PointBalance'], $Balance['Balance']);
                echo json_encode(array("success" => "1", "message" => "Thank you for registering your card, you now have <u style='font-size:16px;'>" . $ResultData['PointBalance'] . "</u> Point(s)  and <u style='font-size:16px;'>$" . $Balance['Balance'] . "</u> balance"));
            }
        }
    }
}
?>