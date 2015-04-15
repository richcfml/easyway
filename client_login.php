<?
require_once("includes/config.php");
$function_obj = new clsFunctions();

@$admin_err = 0;
$chargify_url = "";

if (isset($_POST['submit'])) {
    $username = $_REQUEST['username'];
    $pass = $_REQUEST['pass'];

    $qry_str = "SELECT id, type, status FROM users WHERE username='" . prepareStringForMySQL($username) . "' AND password='" . prepareStringForMySQL($pass) . "'";
    $user = mysql_query($qry_str);
    if (mysql_num_rows($user) > 0) {
        $user = mysql_fetch_assoc($user);
        if ($user["status"] == 1) {
            $qry_str = "SELECT c.site_shared_key,r.chargify_subscription_id,c.hosted_page_url
                FROM chargify_products c
                LEFT JOIN resturants r
                ON r.chargify_subscription_canceled=1 AND r.owner_id='" . $user["id"] . "'
                WHERE r.chargify_product_id=c.settings_id AND c.site_shared_key!=''";

            $resturants = mysql_query($qry_str);
            if (mysql_num_rows($resturants) > 0) {
                // ask user to update payment of the suspended restaurant licenses
                $resturant = mysql_fetch_assoc($resturants);
                $return_url = $resturant["hosted_page_url"];
                $subdomain = substr($return_url, 7, strpos($return_url, '.') - strlen($return_url));
                $message = "update_payment--" . $resturant["chargify_subscription_id"] . "--" . $resturant["site_shared_key"];
                $message = SHA1($message);
                $token = substr($message, 0, 10);
                $chargify_url = "https://$subdomain.chargify.com/update_payment/" . $resturant["chargify_subscription_id"] . "/$token";
                $admin_err = 3;
            } else {
                // login user
                $_SESSION['admin_session_user_name'] = $username;
                $_SESSION['admin_session_pass'] = $pass;
                $_SESSION['admin_type'] = $user["type"];
                $_SESSION['owner_id'] = $user["id"];
                header("location:./c_panel/?mod=resturant");
            }
        } else {
            // user is not active
            $admin_err = 2;
        }
    } else {
        // provided credentials are incomplete
        $admin_err = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>EWO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="signup_css/style.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,700,600,300,800' rel='stylesheet' type='text/css'>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="signup_js/jquery.cookie.js"></script>
        <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script>
            $(document).ready(function ()
            {
                $('.btn').click(function ()
                {
                    if ($(this).attr('data-toggle') == 'hide')
                    {
                        $(this).attr('data-toggle', 'show');
                    } else
                    {
                        $(this).attr('data-toggle', 'hide');
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {

                var checkthis = $.cookie('openDivFY');
                $('.exp').hide();
                //alert(checkthis);
                if (checkthis != null ) {
                    $('#'+checkthis.toLowerCase()).show();
                } else {
                    $('#sendmoney').show();
                }


                $(".explodeshow").click(function () {

                    //$.cookie('openDivFY', $(this).text().trim());

                    var idExplode = $(this).attr("class").split(" ");
                    var idExp = idExplode[1];
                    $('.exp').hide();
                    $("#"+idExp).show("fade", {}, 1000);
                });

                $("li").click(function(){
                    $('li').removeClass('selected');
                    $(this).addClass('selected');
                });
            });

        </script>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
    </head>
    <body style="background:#fff;">
        <div id="pagewrap">
            <style>
                .client_login{
                    background:url(signup_images/client_login_con.png) 0px center no-repeat;
                    width: 100%;
                    background-size:100%;
                    height: 570px;
                    max-width:500px;
                    margin:0 auto;
                }

                .client_login form{
                    width: 90%;
                    max-width: 400px;
                    margin:0 auto 0 auto;
                    padding-top:170px;
                }

                .client_login form input {
                    width: 100%;
                    padding:0;
                    text-indent: 20px;
                    max-width: 380px;
                    height: 70px;
                    margin: 0 auto;
                    background:#e5e7e9;
                    font-size:20px;
                    border: 1px solid #CFCFCF;
                    box-shadow: inset 1px 1px 4px #CDCDCD;
                }
                .client_login form .login {
                    width: 100%;
                    height: 70px;
                    max-width: 380px;
                    margin: 0 auto;
                    border-radius:5px;
                    -moz-border-radius:5px;
                    -webkit-border-radius:5px;
                    font-size:30px;
                    border: 1px solid #238CA5;

                }
            </style>
            <?php include('header_a.php'); ?>
            <section id="" class="ewo-row">
                <div class="ewo-container">
                    <br/><br clear="all"/>
                    <h2 style="color:#25aae1;" class="aligncenter"><strong>Welcome Back</strong></h2>
                    <br clear="all"/>
                    <div class="client_login">
                        <form class="grid100" name="login" method="post" >
                            <h2 style="color:#222222;" class="aligncenter"><strong>Admin Panel</strong><br/> </h2><p align="center"> This is where the magic happens!</p><br clear="all"/> <br clear="all"/>
                            <div class="grid90" style="margin:0 auto;">
                                <input type="text" name="username" id="username" placeholder="Username" required style="border-top-left-radius: 5px;
                                       border-top-right-radius: 5px;"/>
                            </div>
                            <div class="grid90" style="margin:0 auto;">
                                <input type="password" id="pass" name="pass"  placeholder="Password" id  required style="border-bottom-left-radius: 5px;
                                       border-bottom-right-radius: 5px;"/>
                                <br clear="all"/>  <br clear="all"/>
                            </div>
                            <div class="grid90" style="margin:0 auto;">
                                <div class="button-demo" style="width: 100%;">
                                    <button type="submit" class="ladda-button login" data-color="green" id="login" data-style="expand-right" id="submit" name="submit"><strong>Login</strong></button>
                                </div>
                            </div>
                            <br clear="all"/>
                            <br/>
                        </form>
                        <br clear="all"/> <br clear="all"/>
                    </div>
                    <br clear="all"/>
                    <p align="center" style="font-size:16px;"><a href="" ><strong class="orangef" > Forgot your password?</strong> </a><br/> Just give us a call - 1-800-648-6238</p>
                    <br clear="all"/>
                </div>
                <br clear="all"/>
                <br clear="all"/>
                <br clear="all"/>
            </section>
        </div>
        <script src="dist/spin.min.js"></script>
        <script src="dist/ladda.min.js"></script>
    <link rel="stylesheet" href="dist/ladda.min.css">
    <script>
        Ladda.bind( '.button-demo button', { timeout: 2000 } );

        // Bind progress buttons and simulate loading progress
        Ladda.bind( '.progress-demo button', {
            callback: function( instance ) {
                var progress = 0;
                var interval = setInterval( function() {
                    progress = Math.min( progress + Math.random() * 0.1, 1 );
                    instance.setProgress( progress );

                    if( progress === 1 ) {
                        instance.stop();
                        clearInterval( interval );
                    }
                }, 200 );
            }
        } );
    </script>
    <script>

        var _gaq=[['_setAccount','UA-11278966-1'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
    <?php include('footer.php'); ?>
</body>
</html>
