<?php 
$InputCardNumber=dbAbstract::returnRealEscapedString($_POST['InputCardNumber']);
$InputExpDate="";
$InputCvv="";
if($objRestaurant->useValutec ==  2) //GO3
{
	$InputExpDate=dbAbstract::returnRealEscapedString($_POST['InputExpDate']);
	$InputCvv=dbAbstract::returnRealEscapedString($_POST['InputCvv']);
	if ($InputExpDate=='')
	{
		$InputExpDate= 122015;
	}

	if($objGO3->go3IsCardAlreadyRegistered($InputCardNumber))
	{
		echo json_encode(array("success"=>"0","message"=>"Invalid card number"));
	}
	else
	{
		$Authorized = $objGO3->go3SetRegistration($InputCardNumber, $InputExpDate, $InputCvv, ($objRestaurant->rewardPoints * $objRestaurant->numberofPoints), $loggedinuser->cust_phone1, $loggedinuser->id);

		if($Authorized=="Success") 
		{
			$objGO3->go3ActivateReward($InputCardNumber, $InputExpDate, $InputCvv, $loggedinuser->id);
			$objGO3->go3AddValue($InputCardNumber,($objRestaurant->rewardPoints * $objRestaurant->numberofPoints));
			$loggedinuser->saveCard($InputCardNumber, $objGO3->go3RewardPoints($InputCardNumber), $objGO3->go3CardBalance($InputCardNumber));
			
			echo json_encode(array("success"=>"1","message"=>"Thank you for registering your card, you now have <u style='font-size:16px;'>". $objGO3->go3RewardPoints($InputCardNumber) ."</u> Point(s) and <u style='font-size:16px;'>$". $objGO3->go3CardBalance($InputCardNumber) ."</u> balance"));
		}
		else 
		{
			echo json_encode(array("success"=>"0","message"=>"$Authorized"));
		}
	}
}
else if($objRestaurant->useValutec ==  1) //ValuTec
{
	if(isCardRegistered($InputCardNumber))
	{
		echo json_encode(array("success"=>"0","message"=>"Invalid card number"));
	}
	else
	{
		$ResultData = GetRegistration($InputCardNumber);
		$Authorized	= $ResultData['Authorized'];
		$ErrMessage	= $ResultData['ErrMessage'];
		$reg_data=$ResultData;
		if($Authorized=="true") 
		{
			$Balance=AddValue($InputCardNumber,$objRestaurant->rewardPoints * $objRestaurant->numberofPoints);
			$loggedinuser->saveCard($InputCardNumber,$Balance['PointBalance'],$Balance['Balance']);
			echo json_encode(array("success"=>"1","message"=>"Thank you for registering your card, you now have <u style='font-size:16px;'>". $Balance['PointBalance'] ."</u> Point(s) and <u style='font-size:16px;'>$". $Balance['Balance'] ."</u> balance"));
		}
		else 
		{
			$ResultData = SetRegistration($InputCardNumber, $loggededuser->cust_your_name ." ".$loggededuser->LastName, $loggededuser->cust_odr_address, '',$loggededuser->cust_ord_city, $loggededuser->cust_ord_state, $loggededuser->cust_ord_zip, '', $loggededuser->cust_email, '', '', '', '', '', '');
			$Authorized			= $ResultData['Authorized'];
			$ErrMessage			= $ResultData['ErrMessage'];
			$CardNumber			= $ResultData['CardNumber'];
			
			if($Authorized=="false") 
			{
				echo json_encode(array("success"=>"0","message"=>"Invalid card number","response"=>$ResultData));
			}
			else
			{
				$ResultData =  AddValue($InputCardNumber,$objRestaurant->rewardPoints * $objRestaurant->numberofPoints);
				$Authorized	= $ResultData['Authorized'];
					 
				if($Authorized=="false") 
				{
					echo json_encode(array("success"=>"0","message"=>"Invalid card number" ,"result1"=>$ResultData));
				}
				else 
				{
					$loggedinuser->saveCard($InputCardNumber,$Balance['PointBalance'],$Balance['Balance']);
					echo json_encode(array("success"=>"1","message"=>"Thank you for registering your card, you now have <u style='font-size:16px;'>". $ResultData['PointBalance'] ."</u> Point(s)  and <u style='font-size:16px;'>$". $Balance['Balance'] ."</u> balance"));				 
				} 
			}
		}
	}
}
?>