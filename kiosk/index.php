<?php
$site_base = '../';
$site_root_path = "kiosk/";
$css_path = $site_base."css/";
$js_root=$site_base."js/";
$site_images_path = $site_base."images/";
if(($objRestaurant->region == 1) || ($objRestaurant->region == 2))
{
    $currency = "$";
    $java_currency = "$";
}
else
{
    $currency = "&#163;"; 
    $java_currency = "\u00A3";
}

isset($_GET['item']) ? $mod=$_GET['item'] : $mod='resturants';

$cart=NULL;
if(isset($_SESSION['CART']))
{
    $cart=$_SESSION['CART'];	 
}

if(is_null($cart)) 
{
   $cart=new cart();
}
else
{
    $cart=unserializeData($_SESSION['CART']); 
    if ($objRestaurant->id != $cart->restaurant_id)
    {
        $cart->destroyclone();
        $cart->destroysession();
        $cart=new cart();
    }
    else
    {
        $cart=unserializeData($_SESSION['CART']); 
    }
}
	
$cart->restaurant_id=$objRestaurant->id;
$cart->sales_tax_ratio=$objRestaurant->tax_percent;
$loggedinuser->resturant_id=$objRestaurant->id;

if($objRestaurant->delivery_option=='delivery_zones')
{
    $objRestaurant->delivery_charges=$objRestaurant->zone1_delivery_charges;
    $objRestaurant->order_minimum=$objRestaurant->zone1_min_total;
}

if($cart->isempty())
{
    $cart->rest_delivery_charges=$objRestaurant->delivery_charges;
}

if($mod=="cdyne") 
{
    require($site_root_path . "views/cdyne/index.php");
    die();
}
	
require($site_root_path."includes/abandoned_cart_config.php");
require($site_root_path."includes/controller.php");
	
if(isset($_GET['ajax'])) 
{	
    require($include);	
    exit;
}
else
{
    require($site_root_path . "includes/header.php");
    $sitefolder = "";
?>
    <div id="body">
        <script type="text/javascript">
            $(function()
            {
                $(".menu_disabled").click(function(e) 
                {
                    e.preventDefault();
                    $.facebox("<div class='alert-error'><span class='alert-bold'>"+ $(this).attr("title") + "</span> is not available at this time.  Would you like to view the menu anyway? <br/> <br/> <a href='?item=menu&kiosk=1&menuid="+ $(this).attr("menuid") +"' class='boldlink'>View Menu</a> | <a href='?item=menu&kiosk=1' class='boldlink'>Return to Main Menu</a>&nbsp;&nbsp;<br/><br/>  <span class='alert-bold'> Menu Timing: &nbsp; "+ $(this).attr("timings")  +"</span> </div>");	 
                })
            });
        </script>
<?php
    require($include);
?>
                
        <div id="footer">
            <div style="float:left">
                <div class="footer_Logo_Left">
                    <a href="http://easywayordering.com/?kiosk=1"><img src="/images/Footer_Left_Logo.png" border="0" width="150px" height="60px"></a>
                </div>
                <div class="footer_Text_Left">Powered by Easy Way Ordering</div>
            </div>
		  
<?php 
    $imgPath = $SiteUrl."images/logos_thumbnail/". @$objRestaurant->reseller->company_logo;
    if ($objRestaurant->reseller->company_name!="" && @getimagesize($imgPath))
    { 
?>
            <div style="float:right;">
                <div class="footer_Logo_Left"><a href="http://<?=$objRestaurant->reseller->company_logo_link?>"><img  src=<?=$imgPath?> border="0" width="150px" height="60px"></a></div>
                <div class="footer_Text_Left">Distributed by <?=$objRestaurant->reseller->company_name?></div>
            </div>
<?php 
    } 
?>
            <div style="clear:both"></div>
        </div>
    </div>
    </div><!-- Its <DIV> is in includes/header.php #HeaderDiv-->
    <script type="text/javascript">
      (function(i,s,o,g,r,a,m){
            i['GoogleAnalyticsObject']=r;
            i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)
            },
            i[r].l=1*new Date();
            a=s.createElement(o), m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)	
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-41266560-1', 'easywayordering.com');
      ga('send', 'pageview');

    </script>
<?php  
    $visit_tracker_html="";
    $tracker=new tracker();
    $tracker->RestaurantId=$objRestaurant->id;
    if($mod=='thankyou')
    {	
        $arrData=$tracker->getPurcahseTracker();
    }
    else
    {
        $arrData=$tracker->getVisitTrackers();
    }

    foreach($arrData  as $tracker_code) 
    {
        if($mod=='thankyou')
        {	
            $visit_tracker_html .= stripslashes(str_replace('<!--ORDER_TOTAL-->',$cart->grand_total(),$tracker_code->HtmlCode));
        }
        else 
        {
            $visit_tracker_html .= stripslashes($tracker_code->HtmlCode). "<br/>";
        }
    }
    echo($visit_tracker_html);
?>
    <div id="footer-links">
        <ul style="list-style:none;margin: 0 30%;">
            <li style="float:left;margin:0px 10px;padding: 0px 10px;border-right:1px solid #d3d4d5;">
                <a href="?item=tos&kiosk=1">Terms Of Services</a>
            </li>
            <li style="float:left;padding: 0px 10px;padding: 0px 10px;border-right:1px solid #d3d4d5;">
                <a href="?item=privacypolicy&kiosk=1">Privacy Policy</a>
            </li>
            <li style="float:left;padding: 0px 10px;">
                <a href="?item=refundpolicy&kiosk=1">Refund Policy</a>
            </li>
        </ul>
    </div>
</body><!-- Its <body> is in includes/header.php #HeaderBody -->
</html> <!-- Its <html> is in includes/header.php #HeaderHTML -->
<?php   
} 
?>