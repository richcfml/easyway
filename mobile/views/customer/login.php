<?
if (isset($_POST['btncheckout'])) {
    if ($_POST['btncheckout'] == 'Pickup') {
        $cart->setdelivery_type(cart::Pickup);
    } else {
        $cart->setdelivery_type(cart::Delivery);
    }
}
$user_email = '';
if (isset($_COOKIE["user"])) {
    $user_email = $_COOKIE["user"];
}
$remeber_me = '';
if (isset($_POST['rememberme'])) {
    $remeber_me = $_POST['rememberme'];
}
$result = -1;
if (isset($_POST['login'])) {
    $user_email = $_POST['email'];
    $user = $loggedinuser->login($_POST['email'], $_POST['password'], $objRestaurant->id);

    if (is_null($user)) {
        $result = false;
    } else {

        $loggedinuser->destroysession();
        $loggedinuser = $user;
        require($mobile_root_path . "../new_site/includes/abandoned_cart_config.php");


        if ($objRestaurant->useValutec == 1) {
            if ($loggedinuser->valuetec_card_number > 0) {
                $Balance = CardBalance($loggedinuser->valuetec_card_number);
                $loggedinuser->valuetec_points = $Balance['PointBalance'];
                $loggedinuser->valuetec_reward = $Balance['Balance'];
            }
        } else {
            $loggedinuser->valuetec_card_number = 0;
        }

        $address1 = explode('~', trim($loggedinuser->cust_odr_address, '~'));

        $loggedinuser->street1 = $address1[0];
        $loggedinuser->street2 = '';
        if (count($address1) >= 1)
            if(isset($address1[1])){
                $loggedinuser->street2 = $address1[1];
            }

        $address1 = explode('~', trim($loggedinuser->delivery_address1, '~'));

        $loggedinuser->delivery_street1 = $address1[0];
        $loggedinuser->delivery_street2 = '';
        if (count($address1) >= 1)
            if(isset($address1[1])){
                $loggedinuser->delivery_street2 = $address1[1];
            }

        $loggedinuser->savetosession();



        $result = true;
        //------Added by Asher--------//
        $itemcount = $cart->totalItems();
        if ($itemcount > 0) {
            redirect($SiteUrl . $objRestaurant->url . "/?item=cart");
//            header("location: " . $SiteUrl . $objRestaurant->url . "/?item=cart");
            exit;
        }
        //----------------/////
        redirect($SiteUrl . $objRestaurant->url . "/?item=account");
//        header("location: " . $SiteUrl . $objRestaurant->url . "/?item=account");
        exit;
        /* echo "<script type='text/javascript'>window.location=''</script>"; */
    }
} else if (is_numeric($loggedinuser->id)) {
    //------Added by Asher--------//
    $itemcount = $cart->totalItems();
    if ($itemcount > 0) {
        redirect($SiteUrl . $objRestaurant->url . "/?item=cart");
//                             header("location: ". $SiteUrl .$objRestaurant->url ."/?item=cart" );
        exit;
    }
    //----------------/////
    redirect($SiteUrl . $objRestaurant->url . "/?item=account");
//	header("location: ". $SiteUrl .$objRestaurant->url ."/?item=account" );
    exit;
    /* echo "<script type='text/javascript'>window.location='?item=account'</script>"; */
}
?>
<script type="text/javascript">
    $(function() {
        $(".commentsblock").validate({
            rules: {
                email: {required: true, email: 1},
                password: {required: true, minlength: 3},
            },
            messages: {
                email: {
                    required: "please enter your email address",
                    email: "please enter a valid email address"
                },
                password: {
                    required: "please enter your password",
                    minlength: "your enter a valid password"
                }
            },
            errorElement: "div",
            errorClass: "alert-error",
        });
    });

</script>

<section class='menu_list_wrapper'>
    <h1>Exiting users</h1>
    <form action="" method="post" class="commentsblock">
		<?php 
		if ($result === false) 
		{ 
		?>
		<div class="alert-blue"><span id="spnError" name="spnError">Sorry, Your email or password is not correct</span></div>
		<?php 
		} 
		else
		{
		?>
	    <div class="alert-blue" style="display: none;" id="dvSpn"><span id="spnError" name="spnError"></span></div>
		<?php
		}
		?>

        <p class="margintop">Username:&nbsp;&nbsp;<span>
                <input type="text"  size="30"  maxlength="150" title="Username" tabindex="1" id="email" name="email" value="<?= $user_email ?>">
            </span> </p>
        <p class="margintop">Password:&nbsp;&nbsp; <span>
                <input type="password"  size="30"  maxlength="30" title="Password" tabindex="2" id="password" name="password">
            </span> </p>
        <div class="rightalign">Remember me <input type="checkbox"  name="rememberme"  value="1" <?= ($remeber_me == "1" ? "Checked" : "") ?> /> </div>

        <div class="rightalign">Forgot your password? <a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=forgotpassword">Click Here</a></div>
        <div class="rightalign">New User? <a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=register">Click Here to register</a>
        </div>
        <div class="rightalign">
			<!-- Facebook Login Code Starts Here -->
			<table style="width: 100%; margin: 0px;" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<input type="submit" name="login" value="Login" class="button blue">
			            <input type="button" name="checkout" value="Continue without login" class="button blue" onclick="window.location = '?item=checkout'">
					</td>
				<tr>
				<tr>
					<td>
						<strong>OR</strong>
					</td>	
				</tr>
				<tr>
					<td>
						<div id="fbLogin">
							<div id="fb-root"></div>
							<script>
							window.fbAsyncInit = function() 
							{
								FB.init
								({
									appId      : '569304429756200', //597714500283054
									status     : false, // check login status
									cookie     : true, // enable cookies to allow the server to access the session
									xfbml      : true  // parse XFBML
								});
								
								FB.Event.subscribe('auth.statusChange', function(response) 
								{
									if (response.status === 'connected') 
									{
										checkAssociation();
									} 
									else if (response.status === 'not_authorized') 
									{
										FB.login(function(response) 
										{
											if (response.authResponse) // connected
											{
												checkAssociation();
											} 
											else 
											{
												// cancelled
											}
										}, { scope: 'email' });
									} 
									else 
									{
										FB.login(function(response) 
										{
											if (response.authResponse) // connected
											{
												checkAssociation();
											} 
											else 
											{
												// cancelled
											}
										}, { scope: 'email' });
									}
								});
							};
							
							// Load the SDK asynchronously
							(function(d)
							{
								var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
								if (d.getElementById(id)) {return;}
								js = d.createElement('script'); js.id = id; js.async = true;
								js.src = "//connect.facebook.net/en_US/all.js";
								ref.parentNode.insertBefore(js, ref);
							 }
							 (document));
							
							function checkAssociation() 
							{
								FB.api('/me', function (response) 
								{
									var mEmail = '';
									if (response.email)
									{
										if ((response.email != undefined))
										{
											mEmail = response.email;
										}
									}
									var url = '';
									var mRandom = Math.floor((Math.random()*1000000)+1); 
									url="<?=$SiteUrl?><?=$objRestaurant->url?>/?item=checkfbid&checkfbid=1&fbid="+response.id+"&email="+myURLEncode(mEmail)+"&rndm="+mRandom;

									$.ajax
									({
										url: url,
										cache: false,
										type: "POST",
										success: function(data)
												 {
													mResult = data.substring(data.indexOf('<ewo_result>')+12);
													mResult = mResult.substring(0, mResult.indexOf('</ewo_result>'));

													if (mResult<0) //Error
													{
														document.getElementById("dvSpn").style.display = 'block';
														document.getElementById("spnError").innerHTML = "Error occurred.";
													}
													else if (mResult==0) //No Associated Account
													{													
														document.getElementById("dvSpn").style.display = 'block';
														document.getElementById("spnError").innerHTML = "No associated EWO account found.";
													}
													else if (mResult>0) //EWO Associated Account
													{
														var itemCount = <?=$cart->totalItems()?>;
														if (itemCount<=0)
														{
															window.location ="<?php echo($SiteUrl.$objRestaurant->url); ?>/?item=account";
														}
														else
														{
															window.location ="<?php echo($SiteUrl.$objRestaurant->url); ?>/?item=cart";
														}
													}
												 },
										 error: function (jqXHR, textStatus, errorThrown) 
												{
													document.getElementById("dvSpn").style.display = 'block';
													document.getElementById("spnError").innerHTML = "Error occurred.";
													//alert(jqXHR.status);
													//alert(textStatus);
												}
									});
								});
							}

							function myURLEncode(str) 
							{
								str = (str + '').toString();
							  	return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+').replace('.', '%2E');
							}
							
							</script>
							<div class="fb-login-button" autologoutlink="false" data-show-faces="false" data-width="200" data-max-rows="1" size="medium" scope="email"></div>
						</div>
					</td>
				</tr>
			</table>
			<!-- Facebook Login Code Ends Here -->
        </div>

    </form>
    <div style="height:5px;">&nbsp;</div>
</section>
