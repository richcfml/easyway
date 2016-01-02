<!--	Cart -->
<?php
if (isset($_GET['ajax'])) {
    extract($_GET);
    if (isset($index)) {
        $cart->remove_Item($index);
    }
}

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

$cartTotalDisplay = (!empty($cart)) ? 'show' : 'hide';

$grouporderChkout='';
$placeOderDisplay = 'block';

if($_GET['item']!='grouporderthankyou' && $_GET['item']!='thankyou'){
?>
<aside class=cart>
    <?php
	if (isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"])) {
		include $mobile_root_path .'views/cart/grouporder.php';	
	}else{
		include $mobile_root_path .'views/cart/cart.php';
	}
	?>
</aside>
<?php 
}
//echo "<pre>"; print_r($loggedinuser); echo "</pre>"; 
?>

<!--	Main Menu -->
<div class=main-menu>
         <div class=main-menu__container>
            <hgroup class=main-menu__header>
               <h2 class=main-menu__title></h2>
            </hgroup>
            <ul class=main-menu__list>
               <li> <a class=main-menu__item href="?item=menu">Home</a> </li>
               <li> <a class=main-menu__item href="?item=favorites">Favorites</a> </li>
               <li> <a class=main-menu__item href="?item=groupordering">Group Orders</a> </li>
               <li class=main-menu__account><?php 
                    if (isset($loggedinuser->id)){ ?>
                        <a class=main-menu__item href="?item=account">Account</a>
                    <?php }else { ?>
                        <a class=main-menu__item href="?item=account">Login/Register</a> 
                    <?php } ?>
               </li>
            </ul>
            <ul class=main-menu__list>
               <li> <a class="main-menu__item secondary" href="?item=tos">Terms & Conditions</a> </li>
               <li> <a class="main-menu__item secondary" href="?item=privacypolicy">Privacy</a> </li>
               <li> <a class="main-menu__item secondary" href="?item=refundpolicy">Refund Policy</a> </li>
               <?php if (isset($loggedinuser->id)) {?>
            <li> <a class=main-menu__item href="?item=logout">Log Out</a> </li>
            <?php } ?>
            </ul>
            <i class=main-menu__logo></i> 
         </div>
      </div>

<footer class=footer>
    <div class=footer__container>
        <div class=footer__stepper></div>
        <span class=footer__stepper-next></span> </div>
</footer>
 <script src="<?=$mobile_js_path?>application.js"></script> 
<?php
$checkoutUrl = (isset($loggedinuser->id)) ? $SiteUrl . $objRestaurant->url . '/?item=checkout' : 'login';
?>

<div id="ajax_notification" style="line-height:15px"></div>

<script language="javascript">
$(document).ready(function(e) {
	if(window.location.hash=='#login' || window.location.hash=='#register' || window.location.hash=='#forgotpassword'){
		hash = window.location.hash;
		hash = hash.replace("#", "");
		$.ajax({
			type:"POST",
			url: "?item=accountajax&ajax=1&getAuthenticationHtml=1&reponse=<?=$_GET['reponse']?>&hash="+hash,
			data:{},
			success: function(data) {
				$("#ajax_notification").html(data)
			}
		});
	}
});
</script>
<!--	Facebook Login Code Start	-->
<script>
	$.browser = {};
	$.browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
	$.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
	$.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
	$.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());
	
	function gotoHASH() {
		if (location.hash){
			if ( $.browser.webkit == false ) {
				window.location.href = location.hash;
			} else {
				window.location.hash = location.hash;
				
			}
		}
	}
	
    window.fbAsyncInit = function ()
    {
        FB.init
                ({
                    appId: '569304429756200',// '1457406827814198',
                    status: false, // check login status
                    cookie: true, // enable cookies to allow the server to access the session
                    xfbml: true  // parse XFBML
                });
            }
    
        function Login() {
            FB.login(function (response)
            {
                if (response.authResponse) // connected
                {
                    checkAssociation();
                }
                else
                {
                    // cancelled
                }
            }, {scope: 'email'});

            
        }   
    

    // Load the SDK asynchronously
    (function (d)
    {
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement('script');
        js.id = id;
        js.async = true;
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
            var mRandom = Math.floor((Math.random() * 1000000) + 1);
            url = "<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=checkfbid&checkfbid=1&ajax=1&fbid=" + response.id + "&email=" + myURLEncode(mEmail) + "&rndm=" + mRandom;

            $.ajax
                    ({
                        url: url,
                        cache: false,
                        type: "POST",
                        success: function (data)
                        {
                            data = data.replace(/(\r\n|\n|\r)/gm,"");
                            var mResult = data;
                            if (mResult < 0) //Error
                            {
                                document.getElementById("fbLoginError").innerHTML = "Error occurred.<br />Cannot login to Easyway Ordering.";
                            }
                            else if (mResult == 0) //No Associated Account
                            {
                                document.getElementById("txtFBID").value = response.id;
                                document.getElementById("reg_email").value = mEmail;
                                document.getElementById("first_name").value = response.first_name;
                                document.getElementById("last_name").value = response.last_name;
                                window.location.hash = "#register", EasyWay.Notification.open();
                                

                                document.getElementById("fbLoginError").innerHTML = 'Since this is your first time, please complete the rest of the form and click "register account"';

                                $("#user_password").addClass("alert-errorG");
                                $("#user_password_confirm").addClass("alert-errorG");

                                if ($("#reg_email").val() == "")
                                {
                                    $("#reg_email").addClass("alert-errorG");
                                }

                                if ($("#first_name").val() == "")
                                {
                                    $("#first_name").addClass("alert-errorG");
                                }

                                if ($("#last_name").val() == "")
                                {
                                    $("#last_name").addClass("alert-errorG");
                                }

                                if ($("#address1").val() == "")
                                {
                                    $("#address1").addClass("alert-errorG");
                                }

                                if ($("#city").val() == "")
                                {
                                    $("#city").addClass("alert-errorG");
                                }

                                if ($("#state").val() == "")
                                {
                                    $("#state").addClass("alert-errorG");
                                }

                                if ($("#zip").val() == "")
                                {
                                    $("#zip").addClass("alert-errorG");
                                }

                                if ($("#phone1").val() == "")
                                {
                                    $("#phone1").addClass("alert-errorG");
                                }
                                //document.getElementById("btnregister").click();
                            }
                            else if (mResult > 0) //EWO Associated Account
                            {
                                var itemCount = <?= $cart->totalItems() ?>;
                                if (itemCount <= 0)
                                {
                                    window.location = "<?php echo($SiteUrl . $objRestaurant->url); ?>/";
                                }
                                else
                                {
                                    window.location = "<?php echo($SiteUrl . $objRestaurant->url); ?>/?item=checkout";
                                }
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            document.getElementById("fbLoginError").innerHTML = "Error occurred.<br />Cannot login to Easyway Ordering.";
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
<!--	Facebook Login Code End	-->