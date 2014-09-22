<?php           require_once('../classes/users.php');
                include("../includes/function.php");
                require	"../includes/class.phpmailer.php";
                include("../includes/config.php");
                include "../classes/restaurant.php";
                $objMail = new testmail();
                $loggedinuser = new users();
                $objRestaurant = new restaurant();
                $result=-1;

                 if($_SERVER['SERVER_NAME'] == 'localhost') {
                $client_path = "http://".$_SERVER['HTTP_HOST']."/easywayordering";
                }
                else
                {
                    $client_path = "http://".$_SERVER['HTTP_HOST'];
                }

                $loggedinuser->	cust_email=  $_GET['email'];
                $loggedinuser->	password= '12345' ;
		$loggedinuser->	cust_your_name= trim($_GET['Fname']);
		$loggedinuser->	LastName= trim($_GET['Lname']) ;
		$loggedinuser->	street1= trim($_GET['street1']) ;
		$loggedinuser->	street2= trim($_GET['street2']) ;
		$loggedinuser->	cust_ord_city= trim($_GET['city']) ;
		$loggedinuser->	cust_ord_state= trim($_GET['state']) ;
		$loggedinuser->	cust_ord_zip= trim($_GET['zip']) ;
		$loggedinuser->	cust_phone1= trim($_GET['phone']) ;
		
                session_start();
                
                $qry = "Select *,url_name as url from resturants where url_name = '" .$_GET['rest_slugs'] . "'";
                $objRestaurant = mysql_fetch_object(mysql_query($qry));

                $user_qry  = mysql_query("select * from customer_registration where cust_email='".$_GET['email']."' and resturant_id= ". $objRestaurant->id ."");
                if(mysql_num_rows($user_qry)>1){ return NULL;}
                $user=mysql_fetch_object($user_qry,"users");
                
                
               
                if(!empty($user)){

                $loggedinuser->destroysession();
                $loggedinuser = $user;
                $address1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));

                $loggedinuser->street1 = $address1[0];
                $loggedinuser->street2 = '';
                if (count($address1) >= 1)
                $loggedinuser->street2 = $address1[1];
                
                $loggedinuser->savetosession();
                header("location: ". $client_path."/" .$_GET['rest_slugs'] ."/?item=menu" );exit;
                }

                if(empty($user)){
                $result=$loggedinuser->register($objRestaurant,$objMail);
                }

                
                if($result===true){
                   $loggedinuser->destroysession();
                   $loggedinuser->savetosession();
                   header("location: ". $client_path."/" .$_GET['rest_slugs'] ."/?item=menu" );exit;
			}
                

?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        ?>
    </body>
</html>
