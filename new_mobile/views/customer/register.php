<?php
$mZipPostal = "Zip Code:";
$mStateProvince = "State:";

if ($objRestaurant->region == "2") //Canada
{
	$mZipPostal = "Postal Code:";
	$mStateProvince = "Province:";
}
	
$result=-1;
if(isset($_POST['register_user'])) 
{
    $mFBID='';
    if (isset($_POST["txtFBID"]))
    {
            $mFBID=$_POST["txtFBID"];
    }
    extract($_POST);
    $mSalt = hash('sha256', mt_rand(10,1000000));    
    $ePassword = hash('sha256', trim($user_password).$mSalt);
    $loggedinuser->cust_email=  $email;
    $loggedinuser->cust_your_name= trim($first_name) ;
    $loggedinuser->epassword= trim($ePassword) ;
    $loggedinuser->salt= trim($mSalt) ;
    $loggedinuser->LastName= trim($last_name) ;
    $loggedinuser->street1= trim($address1) ;
    $loggedinuser->street2= trim($address2) ;
    $loggedinuser->cust_ord_city= trim($city) ;
    $loggedinuser->cust_ord_state= trim($state) ;
    $loggedinuser->cust_ord_zip= trim($zip) ;
    $loggedinuser->cust_phone1= trim($phone1) ;
    $loggedinuser->delivery_street1= trim($saddress1) ;
    $loggedinuser->delivery_street2= trim($saddress2) ;
    $loggedinuser->delivery_city1= trim($scity) ;
    $loggedinuser->delivery_state1= trim($cstate) ;
    $loggedinuser->deivery1_zip= trim($czip) ;
    $loggedinuser->resturant_id =$objRestaurant->id;
    $loggedinuser->facebook_id = $mFBID;
    
    $result=$loggedinuser->customerRegistration($objRestaurant,$objMail);
    
    if($result===true)
    {
        if(isset($twitter_id)){
        $loggedinuser->updateCustomerTwitterID($loggedinuser->id, $_SESSION['twitter_id']);
        unset($_SESSION['twitter_id']);
        unset($_SESSION['twitter_name']);
        
    }
        
		$itemcount = $cart->totalItems();
		
		if(isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"]) && $itemcount > 0)
		{
			redirect($SiteUrl .$objRestaurant->url ."/?item=checkout&grp_userid=".$_GET["grp_userid"]."&grpid=".$_GET["grpid"]."&uid=".$_GET["uid"]."&grp_keyvalue=".$_GET["grp_keyvalue"]);
		}
		elseif ($itemcount > 0) 
        {
            redirect($SiteUrl . $objRestaurant->url . "/?item=checkout");
            exit;
        }
		else{
			redirect($SiteUrl.$objRestaurant->url ."/?item=account");
			exit;
		}
	}
}
if ($result===false){
	redirect($SiteUrl.$objRestaurant->url ."/?reponse=-1#register");
	exit;
}
if ($result===0){
	redirect($SiteUrl.$objRestaurant->url ."/?reponse=-2#register");
	exit;
} 
?>
      
<script type="text/javascript">
$(function(){
$("#registerationform").validate({
           rules: {
				email: {required: true, email:1 },
				user_password: {required: true,minlength: 5},
				user_password_confirm: {equalTo: "#user_password",required: true,minlength: 5},
				first_name: {required: true,minlength: 3},
				last_name: {required: true,minlength: 3},
				address1: {required: true,minlength: 3},
				city: {required: true,minlength: 2},
				state: {required: true,minlength: 2},
				zip: {required: true,minlength: 3},
				phone1: {required: true,minlength: 3},
           },
           messages: {
                   email: {
							   required: "please enter your email address",
							   email: "please enter a valid email address"
                               },
				   user_password: {
								   required: "please enter your password",
								   minlength: "your password should contain at leat 5 characters"
 								 },
				   
				   user_password_confirm : {
										  equalTo : "password mismatched, please confirm your password",
										  required: "please enter your password",
										  minlength: "your password should contain at leat 5 characters",
					  			 			},
				  first_name: {
					   			required: "please enter your first namess",
							   minlength: "please enter a valid first namess"
						   },
				   last_name: {
					   required: "please enter your last namess",
					   minlength: "please enter a valid last name"
                        
                   },
				   
				  address1: {
					   required: "please enter your street 1",
					   minlength: "please enter a valid street 1"
                        
                   },
				 city: {
					   required: "please enter your city",
					   minlength: "please enter a valid city"
                        
                   },
				 state: {
					   required: "please enter your <?=$mStateProvince?>",
					   minlength: "please enter a valid <?=$mStateProvince?>"
                        
                   },
				zip: {
					   required: "please enter your <?=$mZipPostal?>",
					   minlength: "please enter a valid <?=$mZipPostal?>"
                        
                   },
				 phone1: {
					   required: "please enter your phone1",
					   minlength: "please enter a valid phone1"
                        
                   },
					   
           },
		   errorElement: "div",
       
          errorClass: "alert-error",
});
});
</script>