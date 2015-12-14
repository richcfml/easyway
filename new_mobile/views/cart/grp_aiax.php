<?php
if(isset($_GET['cancel_grouporder']) && isset($_GET['grp_userid']) && isset($_GET['grpid']) && isset($_GET['ajax']))
{
    dbAbstract::Delete("DELETE FROM grouporder WHERE CustomerID=".$_GET["grp_userid"]." AND GroupID=".$_GET["grpid"]);
}

if(isset($_GET['del_user']) && isset($_GET['id']))
{
    dbAbstract::Delete("DELETE FROM grouporder WHERE grporder_id=".$_GET["id"]);
}

if(isset($_GET['setReceivingMethod']) && isset($_GET['ReceivingMethod']) && isset($_GET['cid']) && isset($_GET['gid']))
{
    if ($_GET['ReceivingMethod']==1)
    {
        $cart->setdelivery_type(cart::Delivery);
        if(isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"]))
        {
            $mSQL = "UPDATE grouporder SET ReceivingMethod='Delivery' WHERE GroupID=".$_GET["gid"]." AND CustomerID=".$_GET["cid"];
            dbAbstract::Update($mSQL);
        }
    }
    else 
    {
        $cart->setdelivery_type(cart::Pickup);
        if(isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"]))
        {
            $mSQL = "UPDATE grouporder SET ReceivingMethod='Pickup' WHERE GroupID=".$_GET["gid"]." AND CustomerID=".$_GET["cid"];
            dbAbstract::Update($mSQL);
        }
    }
}

if(isset($_GET['groupEmail']) && isset($_GET['grp_userid']) && isset($_GET['grpid']) && isset($_GET['ajax']))
{
    $sql_qry=dbAbstract::Execute("SELECT grp_name, grp_useremail FROM grouporder WHERE CustomerID=".$_GET["grp_userid"]." AND GroupID=".$_GET["grpid"]);
    while($grp_userEmail = dbAbstract::returnObject($sql_qry))
    {
        $to         = $grp_userEmail->grp_useremail; 
        $subject    = 'Easywayordering Group Order Message';
        $message    = "<div style='width: 100%; font-family: Arial; font-size: 14px;'>";
        $message    .= $_POST['grpOrderMessage'];
        $message    .= "</div>";
        $from       = $grp_userEmail->grp_name;
        $objMail->sendEmailTo($from, $message, $subject, $to);
    }
}

if(isset($_GET['submitform'])) 
{
	$userData = $_POST['userNameEmail'];
	$userData = str_replace('"','',$userData);
    $userData = str_replace("[","",$userData);
    $userData = str_replace("]","",$userData);
	
	$userInfo = array();
    $userInfo = explode( ',', $userData );
	
	$size = sizeof($userInfo);
    $userName= array();
    $userEmail= array();

    for($i = 0; $i < $size; $i++) {
        $index = strpos($userInfo[$i]," - ");
        $userName[$i] = substr($userInfo[$i],0,$index) ;
        $userEmail[$i] = substr($userInfo[$i],$index) ;
        $userEmail[$i] = str_replace(" - ","", $userEmail[$i]);
    }
	
	/*$member_names = explode('~',$_POST['member_names']);
	$member_emails = explode('~',$_POST['member_emails']);

    $size = sizeof($member_names);
    $userName= array();
    $userEmail= array();

    for($i = 0; $i < $size; $i++) {
        $index = strpos($userInfo[$i]," - ");
        $userName[$i] = $member_names[$i];
        $userEmail[$i] = $member_emails[$i];
    }*/
    
	$mFirstName = $_POST['first_name'];
	$mLastName = $_POST['last_name'];
	$_POST['GrpOrdername'] = $mFirstName.' '.$mLastName;
		
    $mCustomerID = 0;
    if ($loggedinuser->id > 0)
    {
        $mCustomerID = $loggedinuser->id;
    }
    else
    {
        $mSQL = "INSERT INTO customer_registration SET cust_email='".prepareStringForMySQL($_POST["GrpOrderemail"])."', cust_your_name='".prepareStringForMySQL($mFirstName)."', LastName='".prepareStringForMySQL($mLastName)."', cust_phone1='".prepareStringForMySQL($_POST["GrpOrdercontact"])."', resturant_id=".$objRestaurant->id;
        $mInsertID = dbAbstract::Insert($mSQL, 0, 2);
        if ($mInsertID > 0)
        {
            $mCustomerID = $mInsertID;
			$mSQLAddress = "INSERT INTO customer_address (CustomerID, `Default`)";
            $mSQLAddress .= " VALUES (".$mCustomerID.", 1)";
            $mAddressID = dbAbstract::Insert($mSQLAddress,0 ,2);
            
			$loggedinuser->delivery_address_choice=$mAddressID;
            if ($mFirstName==$mLastName)
            {
                $loggedinuser->cust_your_name = $mFirstName;
            }
            else
            {
                $loggedinuser->cust_your_name = $mFirstName." ".$mLastName;
            }
            $loggedinuser->cust_email = $_POST["GrpOrderemail"];
            $loggedinuser->savetosession();
			
            /*$mSQLAddress = "INSERT INTO customer_address (CustomerID, `Default`, StreetAddress, City, State, Zip, ApartmentNo)";
            $mSQLAddress .= " VALUES (".$mCustomerID.", 1, '".prepareStringForMySQL($_POST["delivery_streetG"])."', '".prepareStringForMySQL($_POST["delivery_cityG"])."', '".prepareStringForMySQL($_POST["delivery_stateG"])."', '".prepareStringForMySQL($_POST["delivery_zipG"])."', '".prepareStringForMySQL($_POST["delivery_suiteG"])."')";
            $mAddressID = dbAbstract::Insert($mSQLAddress,0 ,2);
            $loggedinuser->delivery_address_choice=$mAddressID;
            $loggedinuser->street1 = $_POST["delivery_streetG"];
            $loggedinuser->cust_odr_address = $_POST["delivery_streetG"];
            $loggedinuser->cust_ord_city =  $_POST["delivery_cityG"];
            $loggedinuser->cust_ord_state =  $_POST["delivery_stateG"];
            $loggedinuser->cust_ord_zip = $_POST["delivery_zipG"];
            $loggedinuser->cust_apartment_no = $_POST["delivery_suiteG"];
            if ($mFirstName==$mLastName)
            {
                $loggedinuser->cust_your_name = $mFirstName;
            }
            else
            {
                $loggedinuser->cust_your_name = $mFirstName." ".$mLastName;
            }
            $loggedinuser->cust_email = $_POST["GrpOrderemail"];
            $loggedinuser->savetosession();*/
        }
    }
    
    if ($mCustomerID>0)
    {
        $mSQL = "SELECT IFNULL(MAX(GroupID), '0') AS GroupID FROM grouporder WHERE CustomerID=".$mCustomerID;
        $mRow = dbAbstract::ExecuteObject($mSQL);
        $mGroupID = $mRow->GroupID;
        $mGroupID = $mGroupID + 1;
        
        $mReceivingMethod = "";
        if ($_POST["DeliveryPickup"]==1)
        {
            $mReceivingMethod = "Delivery";
        }
        else
        {
            $mReceivingMethod = "Pickup";
        }
        
        $mSQL = "SELECT cust_your_name, LastName FROM customer_registration WHERE id=".$mCustomerID;
        $mRow = dbAbstract::ExecuteObject($mSQL);
        
        //query user type Leader start--------------
        $sql="INSERT INTO grouporder(GroupID, CustomerID, UserID, grp_name, grp_username, grp_useremail, grp_contact, grp_usertype, grp_keyvalue, ReceivingMethod) values (".$mGroupID.", ".$mCustomerID.", 1, '".prepareStringForMySQL($_POST['GrpOrderemail'])."','".prepareStringForMySQL($mFirstName.' '.$mLastName)."','".prepareStringForMySQL($_POST['GrpOrderemail'])."','".prepareStringForMySQL($_POST['GrpOrdercontact'])."','".prepareStringForMySQL("leader")."','".prepareStringForMySQL("group_order")."', '".$mReceivingMethod."')";
        dbAbstract::Insert($sql);
        
		
		
        $to      = $_POST['GrpOrderemail']; 
        $subject = 'Easywayordering Group Order Started';
        $message = "<div style='width: 100%; font-family: Arial; font-size: 14px;'>";
        $message .= "Hello ".$_POST['GrpOrdername'].",<br /><br />";
        $message .= "You just have started a group order.";
        $message .= "<br />Please follow the link below for updates.<br />";
        $message .= "<a target='_blank' href='".$SiteUrl.$objRestaurant->url."/"."?grp_userid=".$mCustomerID."&grpid=".$mGroupID."&uid=1&grp_keyvalue=group_order'>".$SiteUrl.$objRestaurant->url."/"."?grp_userid=".$mCustomerID."&grpid=".$mGroupID."&uid=1&grp_keyvalue=group_order</a>";
        $message .= "<br /><br /><br />Thank You!</div>";
        $from = "info@easywayordering.com";
        $objMail->sendEmailTo($from,$message,$subject,$to);
        
		//echo "<pre>"; print_r(); echo "</pre>";
		//die();
		
        //query user type Leader End--------------
        $mUserID = 2; //1 is already inserted in above query
        $mEmailFromName = "";
        if (trim(strtolower($mRow->cust_your_name))==trim(strtolower($mRow->LastName)))
        {
            $mEmailFromName =  $mRow->cust_your_name;
        }
        else
        {
            $mEmailFromName =  $mRow->cust_your_name." ".$mRow->LastName;
        }
        for($j = 0; $j < $size; $j++) 
        {
            if (trim($userEmail[$j])!="")
            {
                $sql_gporder="INSERT INTO grouporder(GroupID, CustomerID, UserID, grp_name,grp_username,grp_useremail,grp_contact,grp_usertype,grp_keyvalue, ReceivingMethod) values (".$mGroupID.", ".$mCustomerID.", ".$mUserID.", '".prepareStringForMySQL($_POST['GrpOrderemail'])."','".prepareStringForMySQL($userName[$j])."','".prepareStringForMySQL($userEmail[$j])."','".prepareStringForMySQL(" ")."','".prepareStringForMySQL("user")."','".prepareStringForMySQL("group_order")."', '".$mReceivingMethod."')";
                dbAbstract::Insert($sql_gporder);

                $to      = $userEmail[$j]; 
                $subject = 'Easywayordering Group Order Invitation';
                $message = "<div style='width: 100%; font-family: Arial; font-size: 14px;'>";
                $message .= "Hello ".$userName[$j].",<br /><br />";
                $message .= "You are invited in a group order by ".$mEmailFromName.".";
                $message .= "<br />Please follow the link below to proceed.<br />";
                $message .= "<a target='_blank' href='".$SiteUrl.$objRestaurant->url."/"."?grp_userid=".$mCustomerID."&grpid=".$mGroupID."&uid=".$mUserID."&grp_keyvalue=group_order'>".$SiteUrl.$objRestaurant->url."/"."?grp_userid=".$mCustomerID."&grpid=".$mGroupID."&uid=".$mUserID."&grp_keyvalue=group_order</a>";
                $message .= "<br /><br /><br />Thank You!</div>";
                $from = $_POST['GrpOrderemail'];
                $objMail->sendEmailTo($from,$message,$subject,$to); 
                $mUserID = $mUserID + 1;
            }
        }
        
        echo($mGroupID."~".$mCustomerID);
    }
}

?>
