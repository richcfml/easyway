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



</head>

<body>

<div id="pagewrap">

	<?php include('header.php'); ?>
    <div class="orange_con center">
    	<div class="ewo-container"><h1>Your Business, Your Customers, Your Terms</h1><br></div>
    </div>

    <section id="ewo-home" class="ewo-row">
        <div class="ewo-container">
            <div class="grid55 floatleft">
                    <img src="signup_images/mockup_ewo.png" width="90%" />
             </div>
        <div class="grid40 floatleft">
        	<br/>
            <h3 class="blue center"> <strong>Powerful Tools Help You Grow Your Business</strong></h3>
            <br/><br/>
            <p align="center">
                <strong>Edit</strong> menus anytime, from any device <br/>
                <strong>Receive</strong> orders your way, through POS, fax or email  <br/>
                <strong>Get</strong> your money when you want it – within 1-2 days   <br/>
                <strong>Protect</strong> your brand identity through reputation management   <br/>
                <strong>Build</strong> your customer database, and reward loyal fans
            </p>
        </div>

        </div>

        <br clear="all"/>

        </section>

    <div class="blue_con center" style="padding-bottom:40px;">
       <div class="ewo-container">
           <div class="grid50 floatleft">
       			 <h4><strong>Getting started is easy!</strong><br/>Let us show you</h4>
           </div>

            <div class="grid50 floatleft" style="margin-top:20px;">
                <h4><a href="sign_up.php" class="ewo-orange-btn"><strong>Get Started Today!</strong></a></h4>
            </div>
    	<br clear="all"/>
    	</div>
    </div>

<br clear="all"/>

    <section id="ewo-home" class="ewo-row">




        <div class="ewo-container">

        <h4 class="blue center"> <strong>Powerful Tools Let You Maximize Revenue</strong></h4>
        <br clear="all"/><br clear="all"/>
            <div class="grid33 animateme floatleft"
            data-when="enter"
            data-from="0.5"
            data-to="0"
            data-opacity="0"
            data-translatex="-200"
            data-rotatez="90">
            <p align="center"><img src="signup_images/empower.png" width="158"/></p>
             <h4 class="blue center"> <strong>Empower</strong><br/><span class="darkgray"><strong>Yourself</strong></span></h4>
             <br clear="all"/>
             <p>Control your menus, customer data and money.
    Open and close your online ordering when you choose.
    Retain control of your brand, and prevent 3rd party sites
    from profiting off of your hard work! </p>
            </div>
             <div class="grid33 floatleft">
            <p align="center"><img src="signup_images/connect.png" width="158"/></p>
            <h4 class="blue center"> <strong>Connect</strong><br/><span class="darkgray"><strong>With Customers</strong></span></h4>
            <br clear="all"/>
             <p>Reach your customers, study their purchasing habits
    and analyze your ROI, with a host of powerful user data.
    Leverage your social media prescence with our
    Facebook ordering feature. </p>
            </div>
            <div class="grid33 floatleft">
            <p align="center"><img src="signup_images/grow.png" width="158"/></p>
             <h4 class="blue center"> <strong>Grow</strong><br/><span class="darkgray"><strong>Your Business</strong></span></h4>
             <br clear="all"/>
              <p>Turn your website into your biggest business asset
    with our powerful tools. Take control of your digital
    reputation, and increase visibility, to gain sales!
    Increase your profits up to 20% with online ordering. </p>
            </div>
               <br clear="all"/>
        </div>
        <br clear="all"/>
           <br clear="all"/>
             <h4 class="center"><a href="features.php" class="ewo-orange-btn" style="border:none;"><strong>See All Of Our Great Features</strong></a></h4>
        </section>
<div class="blue_con center" style="padding-bottom:40px;">
    <div class="ewo-container">
<h4><strong><img src="signup_images/left_qoute.png" /> &nbsp;&nbsp;&nbsp;If you're not on EasyWay, you're in trouble, as far as restaurants go.</strong> &nbsp;&nbsp;&nbsp;<img src="signup_images/right_qoute.png" /></h4>
<p align="right" style="margin-right: 120px;margin-top:10px;"> <strong>Antonella,</strong> Owner, Forno Siciliano</p>
 </div>
</div>

 <br clear="all"/>
<section id="ewo-home" class="ewo-row">




    <div class="ewo-container">

    <h4 class="blue center"> <strong>From Pizzas To Pastries,
</strong><br/>We Are Proud To Work With 100’s Of Great Restaurants</h4>
    <br clear="all"/><br clear="all"/>

       <div class="" style="margin:0 auto;">


        <div class="grid25 floatleft">
        <p align="center"><a href="http://www.giannipizzajh.com/" target="_blank"><img src="signup_images/gianni.png" width="126"/></a><br/>
        <u><strong><a href="http://www.giannipizzajh.com/" target="_blank">Gianni Pizza</a></strong></u><br/>
        New York, NY</p>

        </div>
         <div class="grid25 floatleft">
        <p align="center"><a href="http://www.astor-bakeshop.com/" target="_blank"><img src="signup_images/astorbakeshop.png" width="126"/></a><br/>
        <u><strong><a href="http://www.astor-bakeshop.com/" target="_blank">Astor Bake Shop</a></strong></u><br/>
        New York, NY</p>

        </div>
       <div class="grid25 floatleft">
        <p align="center"><a href="http://burgerburgernyc.com/" target="_blank"><img src="signup_images/burgerburger.png" width="126"/></a><br/>
          <u><strong><a href="http://burgerburgernyc.com/" target="_blank">Burger Burger</a></strong></u><br/>
        New York, NY</p>
        </div>
        <div class="grid25 floatleft">
        <p align="center"><a href="http://tequilascantina.com/" target="_blank"><img src="signup_images/tequilascantina.png" width="126"/></a><br/>
        <u><strong><a href="http://tequilascantina.com/" target="_blank">Tequilla's Cantina &amp; Grill</a></strong></u><br/>
        Burbank, CA</p>
        </div>
        </div>
           <br clear="all"/>
	</div>

       <br clear="all"/>
    </section>
<?php include('footer.php'); ?>
</div>

</body>
</html>
