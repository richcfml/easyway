<?php           
require_once("../includes/config.php");
require_once('../classes/users.php');

                require	"../includes/class.phpmailer.php";
                
                include "../classes/restaurant.php";
                $objMail = new testmail();
                $loggedinuser = new users();
                $objRestaurant = new restaurant();
                $result=-1;

                error_reporting(0);
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
                //echo "select * from customer_registration where cust_email='".$_GET['email']."' and resturant_id= ". $objRestaurant->id ."";exit;
                $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
                $emailaddress = $_GET['email'];
                if (preg_match($pattern, $emailaddress) === 1) {
                    
               
                if(!empty($_GET['email'])& !empty($_GET['rest_slugs'])){
                $user_qry  = mysql_query("select * from customer_registration where cust_email='".$_GET['email']."' and resturant_id= ". $objRestaurant->id ."");
                
                if(mysql_num_rows($user_qry)>1){ return NULL;}
               
                
                if(mysql_num_rows($user_qry)==1){
                $user=mysql_fetch_object($user_qry,"users");
                $loggedinuser->destroysession();
                $loggedinuser = $user;
                $address1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));

                $loggedinuser->street1 = $address1[0];
                $loggedinuser->street2 = '';
                if (count($address1) >= 1)
                $loggedinuser->street2 = $address1[1];
                
                $loggedinuser->savetosession();
                header("location: ". $SiteUrl."/" .$_GET['rest_slugs'] ."/?item=menu" );exit;
                }

                if(mysql_num_rows($user_qry)==0){
                if(!empty( $_GET['Fname'] )& !empty( $_GET['Lname'] )& !empty( $_GET['street1'] )& !empty( $_GET['city'] ) & !empty( $_GET['state'] ) & !empty( $_GET['zip'] ) & !empty( $_GET['phone'] )){
                $result=$loggedinuser->register($objRestaurant,$objMail);
                }
                else
                {
                    echo("Bad Request.");
                }
                }

                if($result===true){
                   $loggedinuser->destroysession();
                   $loggedinuser->savetosession();
                   header("location: ". $SiteUrl."/" .$_GET['rest_slugs'] ."/?item=menu" );exit;
                }
             }
             else
		{
			echo("Bad Request.");
		}
          }
                else
                {
                  echo("Bad Request.");
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
