<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PYXPAY</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">  
    <meta name="description" content="">
    <meta name="author" content="">
     <link href="signup_css/style.css" rel="stylesheet">

<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic' rel='stylesheet' type='text/css'><script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

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



<?php include('header.php'); ?>

<section id="pyx-home" class="pyx-row" style="height:auto !important; padding-bottom:25px;">
	


    <div class="pyx-container">
  	
        <h2>PyxPay Card Benefits</h2>
          

	</div>          
     
    </section>



<section id="pyx-3a" class="pyx-row">
	


  <div class="pyx-container">
        
           <div class="grid100">
                    <h3>Access to over 22,000 Pulse Select Surcharge-FREE ATMs.</h3><br clear="all"/>
                   <p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non  mauris vitae erat consequat auctor eu in elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
                    </p>
                    <br clear="all"/>
    
                    <p>Mauris in erat justo. Nullam ac urna eu felis dapibus condimentum sit amet a augue. Sed non neque elit. Sed ut imperdiet nisi. Proin condimentum fermentum nunc. Etiam pharetra, erat sed fermentum feugiat, velit mauris egestas quam, ut aliquam massa nisl quis neque. Suspendisse in orci enim.</p>
                   <br clear="all"/>
    			
    
    
   		 </div>
    
		
 <br clear="all"/>


			
      <div class="grid100">
                    <h3>FREE Membership to Discount Pharmacy, Labs &lt; Imaging Program.</h3>
                    <br clear="all"/>
                   <p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non  mauris vitae erat consequat auctor eu in elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. </p>
                    <br clear="all"/>
    
                    <p>Mauris in erat justo. Nullam ac urna eu felis dapibus condimentum sit amet a augue. Sed non neque elit. Sed ut imperdiet nisi. Proin condimentum fermentum nunc. Etiam pharetra, erat sed fermentum feugiat, velit mauris egestas quam, ut aliquam massa nisl quis neque. Suspendisse in orci enim.</p>
                   <br clear="all"/>
    			
    
    
   		 </div>
		
        <br clear="all"/> 
        
        <div class="grid100">
                    <h3>Purchase Discount Prepaid Cellular Phone PINs. </h3>
                    <br clear="all"/>
          <p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non  mauris vitae erat consequat auctor eu in elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
          </p>
          <p><br clear="all"/>
    </p>
    </div>
                <br clear="all"/>
    <div class="grid100">
                  <h3>Direct Deposit your Paycheck.</h3>
                  <br clear="all"/>
                  <p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non  mauris vitae erat consequat auctor eu in elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. </p>
                  <br clear="all"/>
                </div>
                <p>&nbsp;</p>
                <div class="grid100">
                  <h3>Pay your Bills Online.</h3>
                  <br clear="all"/>
                  <p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non  mauris vitae erat consequat auctor eu in elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. </p>
                  <br clear="all"/>
                </div>
                <p><br clear="all"/> 
                  
                </p>
                <div class="grid100 fullborder">
                  <h2 style=" font-size: 1.8em; text-align:center">What are you waiting for, get your <strong>PyxPay Card </strong>now to enjoy all the best benefits!</h2>
                  
                   <br clear="all"/> 
    			             <p align="center">  <a href="sign_up.php" class="fontm"><input class="pyx-orange-btna" type="submit" value="Register here to get your card!" /></a>
</p>
    
    
    </div>      
                
    </div>  
    
    
    
   
<br clear="all"/>  
</section>


<?php include('footer.php'); ?>


</body>
</html>
