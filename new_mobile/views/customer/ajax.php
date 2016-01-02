<?php
include_once("../includes/config.php");
include_once ("../lib/AuthDotNet/vars.php");
include_once ("../lib/AuthDotNet/util.php");
include_once ("../lib/nmi_api/gwapi.php");
global $loggedinuser;

if (isset($_GET['checkfbid'])) //To Check that if a give facebook id is associated with any EWO account
{
	if (isset($_GET['fbid']))
	{
		$mRow = $loggedinuser->selectUserIDByFBIDRestaurantID($_GET['fbid'], $objRestaurant->id);
		if ($mRow==0) //No EWO account associated
		{
			if (isset($_GET['email']))
			{
				if (trim($_GET['email'])!="")
				{
					$mRow = $loggedinuser->selectUserIdByEmailRestaurantId(urldecode($_GET['email']), $objRestaurant->id);
					if ($mRow==0) //No EWO account associated
					{
						echo 0;
					}
					else
					{							
						$mUserID = $mRow->UserID;
						
						$mEmail = $mRow->Email;
						if ($mUserID==0) //Error, because UserID cannot be 0 if there is a row
						{
							echo -1;
						}
						else if ($mUserID>0) //EWO account associated
						{
							$loggedinuser->updateCustomerFacebookID($mUserID, $_GET['fbid']);
							$mUser = $loggedinuser->ssoUserLogin($mEmail, $objRestaurant->id);
							if(is_null($mUser))
							{
								echo -2;
							}
							else
							{
								$loggedinuser->destroyUserSession();
								$loggedinuser = $mUser;
						
								require($mobile_root_path . "includes/abandoned_cart_config.php");
						
								if ($objRestaurant->useValutec == 1)  //ValuTec
								{
									if ($loggedinuser->valuetec_card_number > 0) 
									{
										$Balance = valutecCardBalance($loggedinuser->valuetec_card_number);
										$loggedinuser->valuetec_points = $Balance['PointBalance'];
										$loggedinuser->valuetec_reward = $Balance['Balance'];
									}
								} 
								else if ($objRestaurant->useValutec == 2)  //GO3
								{
									if ($loggedinuser->valuetec_card_number > 0) 
									{
										$loggedinuser->valuetec_points = $objGO3->go3RewardPoints($loggedinuser->valuetec_card_number);
										$loggedinuser->valuetec_reward = $objGO3->go3CardBalance($loggedinuser->valuetec_card_number);
									}
								} 
								else 
								{
									$loggedinuser->valuetec_card_number = 0;
								}
						
								$mAddress1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));
						
								$loggedinuser->street1 = $mAddress1[0];
								$loggedinuser->street2 = '';
								if (count($mAddress1) >= 1)
									$loggedinuser->street2 = $mAddress1[1];
						
								$mAddress1 = explode('~', trim($loggedinuser->delivery_address1, '~'));
						
								$loggedinuser->delivery_street1 = $mAddress1[0];
								$loggedinuser->delivery_street2 = '';
								if (count($mAddress1) >= 1)
									$loggedinuser->delivery_street2 = $mAddress1[1];
						
								$loggedinuser->saveToSession();
								echo $mUserID;
							}
						}
					}
				}
			}
		}
		else
		{
			$mUserID = $mRow->UserID;
			
			$mEmail = $mRow->Email;
			if ($mUserID==0) //Error, because UserID cannot be 0 if there is a row
			{
				echo -1;
			}
			else if ($mUserID>0) //EWO account associated
			{
				$mUser = $loggedinuser->ssoUserLogin($mEmail, $objRestaurant->id);
				if(is_null($mUser))
				{
					echo -2;
				}
				else
				{
					$loggedinuser->destroyUserSession();
					$loggedinuser = $mUser;
			
					require($mobile_root_path . "includes/abandoned_cart_config.php");
			
					if ($objRestaurant->useValutec == 1)  //ValuTec
					{
						if ($loggedinuser->valuetec_card_number > 0) 
						{
							$Balance = valutecCardBalance($loggedinuser->valuetec_card_number);
							$loggedinuser->valuetec_points = $Balance['PointBalance'];
							$loggedinuser->valuetec_reward = $Balance['Balance'];
						}
					} 
					else if ($objRestaurant->useValutec == 2)  //GO3
					{
						if ($loggedinuser->valuetec_card_number > 0) 
						{
							$loggedinuser->valuetec_points = $objGO3->go3RewardPoints($loggedinuser->valuetec_card_number);
							$loggedinuser->valuetec_reward = $objGO3->go3CardBalance($loggedinuser->valuetec_card_number);
						}
					} 
					else 
					{
						$loggedinuser->valuetec_card_number = 0;
					}
			
					$mAddress1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));
			
					$loggedinuser->street1 = $mAddress1[0];
					$loggedinuser->street2 = '';
					if (count($mAddress1) >= 1)
						$loggedinuser->street2 = $mAddress1[1];
			
					$mAddress1 = explode('~', trim($loggedinuser->delivery_address1, '~'));
			
					$loggedinuser->delivery_street1 = $mAddress1[0];
					$loggedinuser->delivery_street2 = '';
					if (count($mAddress1) >= 1)
						$loggedinuser->delivery_street2 = $mAddress1[1];
			
					$loggedinuser->saveToSession();
					echo $mUserID;
				}
			}
		}
	}
}

if (isset($_GET['checktwitter'])) {
    $qq = include $mobile_root_path . 'includes/twitteroauth/OAuth.php';
    $qqq = include $mobile_root_path . 'includes/twitteroauth/twitteroauth.php';
    $consumerKey = 'mDgYyzTy13qEuT2ScvzJzn5yc';
    $consumerSecret = 'kl08xfXOGUgDRf3h7aQhiGwMWC3bwsg6EICrY627TS3lk0p6Sr';
    $OAUTH_CALLBACK = $SiteUrl . $objRestaurant->url . "/?item=checktwitter&ajax=1";
    if (isset($_GET['oauth_verifier'])) {
        // TwitterOAuth instance, with two new parameters we got in twitter_login.php
        $twitteroauth = new TwitterOAuth($consumerKey, $consumerSecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
// Let's request the access token
        $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
// Save it in a session var
        $_SESSION['access_token'] = $access_token;
// Let's get the user's info
        $user_info = $twitteroauth->get('account/verify_credentials');

// Print user's info
        if (isset($user_info->id)) {
            $mRow = $loggedinuser->selectUserIDByTwittwerIDRestaurantID($user_info->id, $objRestaurant->id);
            if ($mRow == 0) { //No EWO account associated
                $_SESSION['twitter_name'] = $user_info->name;
                $_SESSION['twitter_id'] = $user_info->id;
                redirect($SiteUrl . $objRestaurant->url . '/#register');
            } else {

                $mUserID = $mRow->UserID;

                $mEmail = $mRow->Email;

                if ($mUserID == 0) { //Error, because UserID cannot be 0 if there is a row
                } else if ($mUserID > 0) { //EWO account associated
                    $mUser = $loggedinuser->ssoUserLogin($mEmail, $objRestaurant->id);
                    if (is_null($mUser)) {
                        
                    } else {

                        $loggedinuser->destroyUserSession();
                        $loggedinuser = $mUser;

                        require($mobile_root_path . "includes/abandoned_cart_config.php");

                        if ($objRestaurant->useValutec == 1) {  //ValuTec
                            if ($loggedinuser->valuetec_card_number > 0) {
                                $Balance = valutecCardBalance($loggedinuser->valuetec_card_number);
                                $loggedinuser->valuetec_points = $Balance['PointBalance'];
                                $loggedinuser->valuetec_reward = $Balance['Balance'];
                            }
                        } else if ($objRestaurant->useValutec == 2) {  //GO3
                            if ($loggedinuser->valuetec_card_number > 0) {
                                $loggedinuser->valuetec_points = $objGO3->go3RewardPoints($loggedinuser->valuetec_card_number);
                                $loggedinuser->valuetec_reward = $objGO3->go3CardBalance($loggedinuser->valuetec_card_number);
                            }
                        } else {
                            $loggedinuser->valuetec_card_number = 0;
                        }

                        $mAddress1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));

                        $loggedinuser->street1 = $mAddress1[0];
                        $loggedinuser->street2 = '';
                        if (count($mAddress1) >= 1)
                            $loggedinuser->street2 = $mAddress1[1];

                        $mAddress1 = explode('~', trim($loggedinuser->delivery_address1, '~'));

                        $loggedinuser->delivery_street1 = $mAddress1[0];
                        $loggedinuser->delivery_street2 = '';
                        if (count($mAddress1) >= 1)
                            $loggedinuser->delivery_street2 = $mAddress1[1];

                        $loggedinuser->saveToSession();

                        redirect($SiteUrl . $objRestaurant->url . '/');
                    }
                }
            }
        }
    }
}

if(isset($_GET['saveInfo']))
{
    
    extract($_POST);
    $loggedinuser->cust_your_name= trim($info_first_name)=='' ? $loggedinuser->cust_your_name : $info_first_name;
    $loggedinuser->LastName= trim($info_last_name)=='' ? $loggedinuser->LastName : $info_last_name;
    $loggedinuser->cust_email= trim($info_email)=='' ? $loggedinuser->cust_email : $info_email;
    $loggedinuser->savetosession();
    $loggedinuser->updateCustomerRegistration();
    echo true;
}
else if ($_GET["call"]=="setdefault")
{
	$mTokenID = $_POST["token"];
    $mUserID = $_POST["id"];
    $mSQL = "UPDATE general_detail SET data_3=0 WHERE id_2=".$mUserID;
    if (dbAbstract::Update($mSQL))
    {
        $mSQL = "UPDATE general_detail SET data_3=1 WHERE id=".$mTokenID;
        
        if (dbAbstract::Update($mSQL))
        {
            $loggedinuser->getUserCCTokens();
            $loggedinuser->savetosession();
            echo json_encode(array("message"=>"Information updated successfully."));
        }
        else
        {
            echo json_encode(array("message"=>"Error occurred."));
        }
    }
    else
    {
        echo json_encode(array("message"=>"Error occurred."));
    }
}

if($_GET["call"]=="saveCard")
{
    extract($_POST);
   
    $x_card_num=$_POST['card_number'];
    $x_card_name=$_POST['card_name'];
    $x_exp_date=$_POST['expiration_month'].$_POST['expiration_year'];
    
    
    if(!isset($_POST['token']) || $_POST['token']=="undefined")
    {
        $token='0';
    }

    if(trim($x_card_num)=='' || trim($x_exp_date)=='')
    {
        echo json_encode(array("css"=>"alert-error","message"=>"Please enter credit card information"));
        exit;
    }

    $success=0;
    $gateway_token=0;


    if ($objRestaurant->payment_gateway == "authoriseDotNet")
    {
        $objRestaurant->payment_gateway = "AuthorizeNet";
    }
    $error_message='';
    require_once 'classes/gateways/tokenization/'. $objRestaurant->payment_gateway .'.php';

    if(!empty ($error_message))
    {
        echo json_encode(array("css"=>"alert-error","message"=>$error_message));
    }
    else
    {
        if($success==1)
        {
                       
            $insertID = $loggedinuser->saveCCTokenForMobile($x_card_num,$gateway_token,1,$x_exp_date,$x_card_name);
            if($insertID){
                echo json_encode(array("css"=>"success","message"=>"success","card"=>$x_card_num,"id"=>$insertID));
                $loggedinuser->savetosession();
            }else{
                echo json_encode(array("css"=>"alert-error","message"=>"Credit card already added","card"=>$x_card_num));
            }
            
        }
        else
        {
            echo json_encode(array("css"=>"alert-error","message"=>"Credit card not accepted by gateway","card"=>$x_card_num));
            
        }
    }

    exit;
}
if(isset($_GET['changepassword']))
{
	extract($_POST);
	
	if(trim($previous_password)=='' || trim($new_password)=='' || trim($password_confirmation)==''){
		echo -1;
	}elseif(trim($new_password) != trim($password_confirmation)){
		echo -2;
	}else{
		$mSalt = $loggedinuser->salt;    
		$ePassword = hash('sha256', $previous_password.$mSalt);
		if($ePassword == $loggedinuser->epassword){
			$loggedinuser->epassword = hash('sha256', $new_password.$mSalt);
			$loggedinuser->updateCustomerRegistration();
			echo 1;
		}else{
			echo -3;
		}
	}
}

if(isset($_GET['addNewAddress'])){
	extract($_POST);
	if(($updateAddress == 1) || ($updateAddress == 0 && $loggedinuser->cust_ord_city=='' && $loggedinuser->cust_ord_state=='' && $loggedinuser->cust_ord_zip=='')){
	  $loggedinuser->street1= $street;
	  $loggedinuser->cust_ord_city= trim($city)=='' ? $loggedinuser->cust_ord_city : $city;
	  $loggedinuser->cust_ord_state= trim($state)=='' ? $loggedinuser->cust_ord_state : $state;
	  $loggedinuser->cust_ord_zip= trim($zip)=='' ? $loggedinuser->cust_ord_zip : $zip;
	  $loggedinuser->cust_phone1= trim($phone)=='' ? $loggedinuser->cust_phone1 : $phone;
	}
	elseif(($updateAddress == 2) || ($updateAddress == 0 && $loggedinuser->delivery_city1=='' && $loggedinuser->delivery_state1=='' && $loggedinuser->deivery1_zip=='')){
	  $loggedinuser->delivery_street1 = $street;
	  $loggedinuser->delivery_city1= trim($city)=='' ? $loggedinuser->delivery_city1 : $city;
	  $loggedinuser->delivery_state1= trim($state)=='' ? $loggedinuser->delivery_state1 : $state;
	  $loggedinuser->deivery1_zip= trim($zip)=='' ? $loggedinuser->deivery1_zip : $zip;
	  $loggedinuser->cust_phone2= trim($phone)=='' ? $loggedinuser->cust_phone2 : $phone;
	}
	$loggedinuser->savetosession();
	$loggedinuser->updateCustomerRegistration();
	echo true;
}

if(isset($_GET['changeDefaultAddress'])){
	@extract($_POST);

	$tmpStr1 = $loggedinuser->delivery_street1;
	$tmpStr2 = $loggedinuser->delivery_street2;
	$tmpCty = $loggedinuser->delivery_city1;
	$tmpState = $loggedinuser->delivery_state1;
	$tmpZip = $loggedinuser->deivery1_zip;
	
	$loggedinuser->delivery_street1 = $loggedinuser->street1;
	$loggedinuser->delivery_street2 = $loggedinuser->street2;
	$loggedinuser->delivery_city1 = $loggedinuser->cust_ord_city;
	$loggedinuser->delivery_state1 = $loggedinuser->cust_ord_state;
	$loggedinuser->deivery1_zip = $loggedinuser->cust_ord_zip;
	
	$loggedinuser->street1 = $tmpStr1;
	$loggedinuser->street2 = $tmpStr2;
	$loggedinuser->cust_ord_city = trim($tmpCty);
	$loggedinuser->cust_ord_state = trim($tmpState);
	$loggedinuser->cust_ord_zip = trim($tmpZip);
	$loggedinuser->updateCustomerRegistration();
	echo json_encode($loggedinuser);
}

if(isset($_GET['getAuthenticationHtml'])){
    $consumerKey = 'mDgYyzTy13qEuT2ScvzJzn5yc';
$consumerSecret = 'kl08xfXOGUgDRf3h7aQhiGwMWC3bwsg6EICrY627TS3lk0p6Sr';
$OAUTH_CALLBACK = $SiteUrl . $objRestaurant->url . "/?item=checktwitter&checktwitter=1&ajax=1";
$oAuthToken = '';
$oAuthSecret = '';
$twitter_url = '';

$qq = include $mobile_root_path . 'includes/twitteroauth/OAuth.php';
$qqq = include $mobile_root_path . 'includes/twitteroauth/twitteroauth.php';
$connection = new TwitterOAuth($consumerKey, $consumerSecret);
//var_dump($connection);
$request_token = $connection->getRequestToken($OAUTH_CALLBACK);

if ($request_token) {
    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

//    $defaultNamespace = new Zend_Session_Namespace('Default');
    $token = $request_token['oauth_token'];

    $_SESSION['request_token'] = $token;

    $_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];

    switch ($connection->http_code) {
        case 200:
            $twitter_url = $connection->getAuthorizeURL($token);
            //redirect to Twitter .
            //header('Location: ' . $url);
            //echo $url;
            break;
        default:
            echo "Coonection with twitter Failed";
            break;
    }
}
    
?>
<!--	Register -->
<div class="notification" id="register" style="height: 100%; overflow: auto">
    <p class=sign__link> Already a member? <a class=js-sign href="#login" onClick="changeHash('#login')">Log in</a> </p>
    <div class=notification__box>
        <header class='notification__box-header center-text'> <a class=notification__box-action href="#">X</a>
            <h3 class=notification__box-title>Register</h3>
        </header>
        <div class=notification__box-content>
            <p class=center-text>Save favorite orders, delivery info and much more.</p>
            <?php
            if (isset($_GET['reponse']) && $_GET['reponse'] == -1) {
                echo '<p class=center-text>Sorry, registration failed please try again.</p>';
            } elseif (isset($_GET['reponse']) && $_GET['reponse'] == -2) {
                echo '<p class=center-text>This email is already registerd with easywayordering.com/' . $objRestaurant->url . '. Please <a href="' . $SiteUrl . $objRestaurant->url . '/?item=forgotpassword"><u>Click Here</u></a> if you forgot your passowrd.</p>';
            }
            ?>
            <p class=center-text id="fbLoginError"></p>
            <form action="<?= $SiteUrl . $objRestaurant->url ?>/?item=register" method="post" id="registerationform">
                <fieldset>
                    <label class="relative" for="email">
                        Email: 
                        <span class="invalidVal hide"><i class="fa fa-remove"></i></span>
                        <span class="validVal hide"><i class="fa fa-check"></i></span>
                    </label>
                    <input type="email" name="email" id="reg_email" placeholder="e.g. john@doe.com" autocomplete="email" class="email" />

                    <label for="password">Password: </label>
                    <input type="password" name="user_password" id="user_password" />

                    <label for="first_name">First name: </label>
                    <input name="first_name" id="first_name" value="<?= ($_SESSION['twitter_name'] ) ? $_SESSION['twitter_name']: ''; ?>"  placeholder="e.g. Jack" autocomplete="given-name" />

                    <label for="last_name">Last name: </label>
                    <input name="last_name" id="last_name" Placeholder="e.g. Nicholson" autocomplete="family-name" />
                    <input type="hidden" id="txtFBID" name="txtFBID"/>
		<?php
                    
                        if (isset($_SESSION['twitter_id'])) {
                            echo '<input type="hidden" id="twitter_id" name="twitter_id" value="' . $_SESSION['twitter_id'] . '"/> ';
                        }
                    
                    ?>	
                </fieldset>
                <input type="submit" name="register_user" value="Register" class="sign__action"/>
                <div class=social>
                    <a class=facebook href="javascript:void(0)" onclick="Login();">Facebook</a>
                    <a class=twitter href="<?php echo $twitter_url; ?>">Twitters</a> 
                </div>
            </form>
        </div>
    </div>
    <div class='notification__box center-text'> <a href="javascript:void(0)" onclick="window.location = '?item=checkout'">Proceed as Guest!</a> </div>
</div>

<!--	Login -->
<div class="notification" id="login" style="height: 100%; overflow: auto">
    <p class=sign__link> Not a member? <a class=js-sign href="#register" onClick="changeHash('#register')">Register</a> </p>
    <div class=notification__box>
        <header class='notification__box-header center-text'> <a class=notification__box-action href="#">X</a>
            <h3 class=notification__box-title>Log in</h3>
        </header>
        <div class=notification__box-content>
            <?php
            if (isset($_GET['reponse']) && $_GET['reponse'] == -1) {
                echo '<p class=center-text>Sorry, Your email or password is not correct</p>';
            } else {
                echo '<p class=center-text>Welcome back!</p>';
            }
            ?>
            <span class="redtxt" id="spnError"></span>
            <form action="<?= $SiteUrl . $objRestaurant->url ?>/?item=login" method="post" id="loginfrm">
                <fieldset>
                    <label class="relative" for="email">Email: 
                        <span class="invalidVal hide"><i class="fa fa-remove"></i></span>
                        <span class="validVal hide"><i class="fa fa-check"></i></span>
                    </label>
                    <input type="email" name="email" id="login_email" placeholder="e.g. john@doe.com" autocomplete="email" value="<?= $user_email ?>" class="email" />
                    <label for="password">Password: </label>
                    <input type="password" name="password" id="password" />
                    <a class="js-sign right-text" href="#forgotpassword" onClick="changeHash('#forgotpassword')">Forgot password?</a>
                </fieldset>
                <input type="submit" name="login" value="Login" class="sign__action"/>
                <div class=social> 
                    <a class=facebook href="javascript:void(0)" onclick="Login();">Use Facebook</a>
                    <a class=twitter href="<?php echo $twitter_url; ?>">Use Twitter</a> 
                </div>
            </form>
        </div>
    </div>
    <div class='notification__box center-text'> <a href="javascript:void(0)" onclick="window.location = '?item=checkout'">Proceed as Guest!</a> </div>
</div>

<!--	Forgot Password -->
<div class="notification" id="forgotpassword">
    <div class=notification__box>
        <header class='notification__box-header center-text'> <a class=notification__box-action href="#">X</a>
            <h3 class=notification__box-title>Forgot Password</h3>
        </header>
        <div class=notification__box-content>
            <?php
            if (isset($_GET['reponse']) && $_GET['reponse'] == -1) {
                echo '<p class=center-text>Sorry, that email address is currently not registered at easywayordering.com</p>';
            } elseif (isset($_GET['reponse']) && $_GET['reponse'] == 1) {
                echo '<p class=center-text>Your account password has been sent to your email address.&nbsp;&nbsp;<a href="' . $SiteUrl . $objRestaurant->url . '/">Back to Home</a></p>';
            } else {
                echo '<p class=center-text>Welcome back!</p>';
            }
            ?>
            <form action="<?= $SiteUrl . $objRestaurant->url ?>/?item=forgotpassword" method="post" id="forgotpswdfrm">
                <fieldset>
                    <label class="relative" for="email">Email: 
                        <span class="invalidVal hide"><i class="fa fa-remove"></i></span>
                        <span class="validVal hide"><i class="fa fa-check"></i></span>
                    </label>
                    <input type="email" name="email" id="fp_email" placeholder="e.g. john@doe.com" autocomplete="email" value="<?= $user_email ?>" class="email" />

                    <a class="js-sign right-text" href="#login" onClick="changeHash('#login')">Log in</a>
                </fieldset>
                <input type="submit" name="forgotpassword" value="Submit" class="sign__action"/>
            </form>
        </div>
    </div>
</div>
<script language="javascript">
function changeHash(hash){
	window.location.hash = hash, EasyWay.Notification.open()
}
changeHash('#<?=$_GET['hash']?>');
</script>
<?php
}