<?php
require_once "includes/config.php";
if(isset($_GET['op'])){

    
    
    $op = $_GET['op'];
    $message = array();
    if($op == 'authenticate'){

        
        $username = $_POST['user'];
        $password = $_POST['pass'];
        
        $result = dbAbstract::Execute('SELECT * FROM users WHERE username = "'.  dbAbstract::returnRealEscapedString($username).'" AND md5(password) ="'.  dbAbstract::returnRealEscapedString($password).'"');

        $authenticated = dbAbstract::returnRowsCount($result) > 0 ? true : false;

        if($authenticated){
            $message['message'] = 'authenticated';
            $user = dbAbstract::returnObject($result);
            
            $rests = array();
            $restaurants = dbAbstract::Execute('SELECT * FROM resturants WHERE owner_id='.$user->id);
            while($restaurant = dbAbstract::returnObject($restaurants)){
                $rests[$restaurant->url_name] = $restaurant->id;
            }


            $message['rest'] = dbAbstract::returnRealEscapedString(serialize($rests));
            $message['user'] = $user->id;
            
        }else{
            $message['message'] = 'authentication failed';
        }

    }elseif($op == 'confirmOrder'){
        $rest_id = $_POST['rest_id'];
        $order_id = $_POST['order_id'];
        if(is_numeric($rest_id) && is_numeric($order_id)){
            $order = dbAbstract::ExecuteObject('SELECT * FROM ordertbl WHERE OrderID = '.$order_id.' AND cat_id = '.$rest_id);
            if($order){
                dbAbstract::Update('UPDATE ordertbl SET Approve=1 WHERE OrderID = '.$order_id);
                $message['message'] = 'Order Approved';
            }
        }else{
            $message['message'] = 'Invalid Parameters';
        }
        
    }
	// Order Refund Functionality Starts Here -- Gulfam 04 April 2014
	else if ($op == 'refund')
	{
		$mOrderID = $_POST['order_id'];
		$mPassword = $_POST['pass'];
		$mRestaurantID =  $_POST['rest_id'];
		$mResult = dbAbstract::Execute("SELECT refund_password FROM resturants WHERE id=".$mRestaurantID);
		if (dbAbstract::returnRowsCount($mResult>0))
		{
			$mRow = dbAbstract::returnObject($mResult);
			if ($mPassword==md5($mRow->refund_password))
			{
				$mResult = dbAbstract::Execute("SELECT R.id AS ID FROM resturants R INNER JOIN customer_registration CR ON CR.resturant_id=R.id INNER JOIN ordertbl O ON O.UserID=CR.id WHERE O.OrderID=".$mOrderID);
				if (dbAbstract::returnRowsCount($mResult>0))
				{
					$mRow = dbAbstract::returnObject($mResult);
					if ($mRestaurantID==$mRow->ID)
					{
						$message["message"] =  RefundOrder($mOrderID, $mRestaurantID);
					}
					else
					{
						$message["message"] = "Error occurred in restaurant and order validation.";
					}
				}
				else
				{
					$message["message"] = "Error occurred in restaurant validation.";
				}
			}
			else
			{
				$message["message"] = "Password mismatch.";
			}
		}
	}
	
	function RefundOrder($pOrderID, $pRestaurantID)
	{
		$mResult=dbAbstract::Execute("SELECT IFNULL(transaction_id, '') AS transaction_id, IFNULL(payment_approv, 0) AS payment_approv, IFNULL(payment_method, '') AS payment_method FROM `ordertbl` WHERE OrderID=".$pOrderID);
		if (dbAbstract::returnRowsCount($mResult)>0)
		{
			$mRow = dbAbstract::returnObject($mResult);
			if ((trim($mRow->transaction_id)=="") || (trim($mRow->transaction_id)=="0"))
			{
				return "Order can not be refunded.";
			}
			
			if (trim($mRow->payment_approv)!="1")
			{
				return "Payment not approved.";
			}
			
			if (strtolower(trim($mRow->payment_method))!="credit card")
			{
				return "Refund available for Credit Card orders only.";
			}
			
			$mTransactionID = $mRow->transaction_id;
			
			$mResult=dbAbstract::Execute("SELECT C.id AS UserID, C.cust_your_name AS FirstName, C.LastName AS LastName, C.cust_email AS Email, O.Totel AS Total, O.cdata AS CData, O.OrderDate AS OrderDate FROM `ordertbl` O INNER JOIN `customer_registration` C on O.UserID=C.id WHERE O.OrderID=".$pOrderID);
			if (dbAbstract::returnRowsCount($mResult)>0)
			{
				$mRow = dbAbstract::returnObject($mResult);
				
				$gUID = $mRow->UserID; 
				$amount = $mRow->Total;
				$cc = $mRow->CData;
				$mFirstName = $mRow->FirstName;
				$mLastName = $mRow->LastName;
				$mEmail = $mRow->Email;
				$mOrderDate = $mRow->OrderDate;
				$mTotal = $mRow->Total;				
				
				$mResult=dbAbstract::Execute("SELECT IFNULL(payment_gateway, '') AS payment_gateway, url, IFNULL(phone, '') AS phone, IFNULL(fax, '') AS fax, IFNULL(email, '') AS email FROM resturants WHERE id=".$pRestaurantID);
				if (dbAbstract::returnRowsCount($mResult)>0)
				{
					$mRow = dbAbstract::returnObject($mResult);
					if (trim($mRow->payment_gateway)!="")
					{
						$success=0;
						$mURL = $mRow->url;
						$mPhone = $mRow->phone;
						$mFax = $mRow->fax;
						$mRestEmail = $mRow->email;
						if( $mRow->payment_gateway=="authoriseDotNet")  
						{
							$mRow->payment_gateway="AuthorizeNet";
						}
						require_once 'c_panel/admin_contents/gateways/'.$mRow->payment_gateway.'.php';
						
						
						
						
						if($success==1)
						{
							dbAbstract::Update("UPDATE ordertbl set payment_approv=0 where OrderID=".$pOrderID);
							$testmail=new testmail();
							$message="<br/> Dear Customer <br/><br/> Your Order Payment is refunded: <br/><br/> Order ID: ".$pOrderID ."<br/><br/> Order Date: ".$mOrderDate."<br/><br/> Order Total: ".$mTotal." <br/> ";
							$message .=" <a href='http://www.easywayordering.com/".$mURL."/'>http://www.easywayordering.com/".$mURL."/</a>";
							$message .="<br/><br/>Phone: ".$mPhone."";
							$message .="<br/><br/>Fax: ".$mFax."";
							$subject="Easyway Ordering Refund";
							$testmail->sendTo($message,$subject,$mEmail);
					
							$message="<br/> Dear Restaurant Owner <br/><br/> Order Payment is refunded: <br/><br/> Order ID: ".$pOrderID."<br/><br/> Order Date: ".$mOrderDate."<br/><br/> Order Total: ".$mTotal." <br/> ";
							$message .=" <a href='http://www.easywayordering.com/".$mURL."/'>http://www.easywayordering.com/".$mURL."/</a>";
							$message .="<br/><br/>Phone: ".$mPhone."";
							$message .="<br/><br/>Fax: ".$mFax."";
							$testmail->sendTo($message,$subject,$mRestEmail);
							
							return "Order Refunded successfully.";
						}
						else 
						{
							$message="Order Refund failed. Gateway Message: ".$message;
						}
						
						
						
						
						
						
					}
					else
					{
						return "Error in fetching Payment gateway for restaurant.";
					}
				}
				else
				{
					return "Error occurred in fetching Payment gateway for restaurant.";
				}
			}
			else
			{
				return "Error occurred in fetching Order details.";
			}
														   
		}
		else
		{
			return "Order not found.";
		}
	}
	// Order Refund Functionality Ends Here -- Gulfam 04 April 2014
	
    //print_r($message);
    echo json_string($message);
    exit;
}
function json_string($array){
    $first = false;
    echo '{';
    foreach($array as $key => $value){
        echo ($first ? ',' : '').'"'.$key.'":"'.(is_string($value) ? $value : json_string($value)).'"';
        $first=true;
    }
    echo '}';
}
?>
