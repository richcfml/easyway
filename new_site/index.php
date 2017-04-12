<?php
	$site_base = '../';
	$site_root_path = "new_site/";
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
	
 	require($site_root_path . "includes/abandoned_cart_config.php");
 	require($site_root_path . "includes/controller.php");
	
	if(isset($_GET['sso']) && $_GET['sso']!='')
        {
            $mSQL = "select u.*, bhs.session_id as session_id, bhs.session_expiry from bh_sso_user u inner join bh_sso_session bhs on u.id = bhs.sso_user_id WHERE bhs.session_id = '".$_GET['sso']."' and bhs.session_expiry > '".time()."'";	
            Log::write("Sign In - SSO User - IF", "QUERY --".$mSQL, 'sso', 1);	
            $sso_rs = dbAbstract::Execute($mSQL);
            if(dbAbstract::returnRowsCount($sso_rs) > 0)
            {
                $sso_row = dbAbstract::returnObject($sso_rs);	
                $mSQL = "select * from customer_registration where cust_email='".$sso_row->email."' and resturant_id='".$objRestaurant->id."'";
                Log::write("Sign In - SSO User - IF", "QUERY --".$mSQL, 'sso', 1);
                $cust_rs = dbAbstract::Execute($mSQL);
			
                // if customer record exist than login
                if(dbAbstract::returnRowsCount($cust_rs) > 0)
                {
                    $cust_row = dbAbstract::returnObject($cust_rs);
                    if (($cust_row->cust_email != '') && ($cust_row->epassword != ''))
                    {
                        $mECommerceID = dbAbstract::Insert("INSERT INTO bh_ecommerce (SSOUserID, CustomerID) VALUES (".$sso_row->id.", ".$cust_row->id.")", 1, 2);
                        $_SESSION["EcommerceID"] = $mECommerceID;
                        dbAbstract::Update("update general_detail set sso_user_id='".$sso_row->id."' where id_2='".$cust_row->id."'");
?>
                        <form method="post" name="sso_login" action="<?=$SiteUrl.$objRestaurant->url.'/?item=login'?>">
                            <input type="hidden" name="ssoUserId" value="<?=$sso_row->id?>"/>
                            <input type="hidden" name="email" value="<?=$cust_row->cust_email?>"/>
                            <input type="hidden" name="login" value="sso"/>
                        </form>
                        <script language="javascript">
                            document.sso_login.submit();
                        </script>
<?php					
                    }
                    else
                    {
                        echo '<div style="width=100%; text-align:center; color:#F00; height:20px; width:982px; background-color:#F3B5B5; border:1px solid #C37D7D; margin: 0 auto;">Sorry! Invalid E-mail or Password.</div>';
                    }
                }
                else
                {
                    // if customer record not exist than register & login
                    $loggedinuser->cust_email=  $sso_row->email;
                    $mSalt = hash('sha256', mt_rand(10,1000000));    
                    $loggedinuser->salt= $mSalt;
                    $loggedinuser->epassword= hash('sha256', trim($sso_row->password).$mSalt);
                    $loggedinuser->cust_your_name= trim($sso_row->firstName);
                    $loggedinuser->LastName= trim($sso_row->lastName);
                    $loggedinuser->street1= trim($sso_row->address1) ;
                    $loggedinuser->street2= trim($sso_row->address2) ;
                    $loggedinuser->cust_ord_city= trim($sso_row->city) ;
                    $loggedinuser->cust_ord_state= trim($sso_row->state) ;
                    $loggedinuser->cust_ord_zip= trim($sso_row->zip) ;
                    $loggedinuser->cust_phone1= trim($sso_row->phone) ;
			  
                    $loggedinuser->delivery_street1= trim($sso_row->address1) ;
                    $loggedinuser->delivery_street2= trim($sso_row->address2) ;
                    $loggedinuser->delivery_city1= trim($sso_row->city) ;
                    $loggedinuser->delivery_state1= trim($sso_row->state) ;
                    $loggedinuser->deivery1_zip= trim($sso_row->zip) ;

                    $loggedinuser->resturant_id =$objRestaurant->id;
                    $loggedinuser->ssoUserId = $sso_row->id;

                    $result = $loggedinuser->customerRegistration($objRestaurant, $objMail, $sso_row->id);
                    $loggedinuser->ssoUserId = $sso_row->id;
                    $mECommerceID = dbAbstract::Insert("INSERT INTO bh_ecommerce (SSOUserID, CustomerID) VALUES (".$sso_row->id.", ".$loggedinuser->id.")", 2);
                    $_SESSION["EcommerceID"] = $mECommerceID;
                    if($result===true)
                    {
                        redirect($SiteUrl.$objRestaurant->url."/");
                        exit;	
                    }
                }
            }
            else
            {
                echo '<div style="width=100%; text-align:center; color:#F00; height:20px; width:982px; background-color:#F3B5B5; border:1px solid #C37D7D; margin: 0 auto;">Sorry! Invalid session id or session id has been expired.</div>';
            }
	}
	
 	if(isset($_GET['ajax'])) 
	{	
		require($include);	
		exit;
	}
	else
	{
		if(isset($_REQUEST["wp_api"])) 
		{
			if($objRestaurant->status!=0)
			{
				require($site_root_path . "includes/wp-header.php");
			}
		} 
		else if(isset($_REQUEST["ifrm"])) 
		{
			if($objRestaurant->status!=0)
			{
				require($site_root_path . "includes/if-header.php");
			}
		} 
		else 
		{
			require($site_root_path . "includes/header.php");
		}
		
		

?>
	<div id="body">
		<?php
		require($include);
		if(empty($_REQUEST["wp_api"])) 
		{
		?>
 		<div id="footer">
			<script type="text/javascript">
				$(function(){
					$(".menu_disabled").click(function(e) 
					{
						e.preventDefault();
						$.facebox("<div class='alert-error'><span class='alert-bold'>"+ $(this).attr("title") + "</span> is not available at this time.  Would you like to view the menu anyway? <br/> <br/> <a href='?item=menu&menuid="+ $(this).attr("menuid") +"' class='boldlink'>View Menu</a> | <a href='?item=menu' class='boldlink'>Return to Main Menu</a>&nbsp;&nbsp;<br/><br/>  <span class='alert-bold'> Menu Timing: &nbsp; "+ $(this).attr("timings")  +"</span> </div>");	 
					})
				});
			</script>   
        
			<div style="float:left">
                <div class="footer_Logo_Left"><a href="http://easywayordering.com"><img src="/images/Footer_Left_Logo.png" border="0" width="150px" height="60px"></a></div>
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
		<?php
		} 
		?>
		<?php
			$myurl = $_SERVER['REQUEST_URI'];
        		$url = explode('/', $myurl);
        		$is_rest_bh_new_promo = product::isRestPartOfNewBHPromo($url[1]);
        		if(isset($is_rest_bh_new_promo) && $is_rest_bh_new_promo->bh_new_promotion == 1){
		?><div>
			 <p style="font-size: 0.8em; text-align:cente;  padding: 0 0 10px 0; font-weight: lighter;">
				*Limited time only. 50% discount off any sandwich proudly featuring Boar’s Head meats or cheeses.  Limit one discount per order.  No additional discounts or coupons apply.
				<br/> Discount taken from the highest priced item containing Boar’s Head meats or cheeses.  Only valid on orders placed online or through the Boar’s Head Deli Guide App.  
				At participating stores only.
			</p>
			<!-- <p style="font-size: 0.8em; text-align:cente;  padding: 0 0 10px 0; font-weight: lighter;">
                                Limited time only (offer good January 28, 2017 through January 31, 2017). Complimentary Boar's Head<sup>&copy;</sup> Sandwich.<br/> 
                		*Limit one discount per order.  No additional discounts or coupons apply.
                                 Only valid on orders placed online or through the Deli Guide App. Discount taken from the highest priced item. At participating stores only.
                        </p> -->
		</div>
		<?php } ?>
	</div>
</div><!-- MAIN CONTAINER WITH HEADER -->
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
		echo $visit_tracker_html 
		?>
	<div id="footer-links">
		<ul style="list-style:none;margin: 0 30%;">
			<li style="float:left;margin:0px 10px;padding: 0px 10px;border-right:1px solid #d3d4d5;">
				<a href="?<?= isset($_REQUEST["wp_api"]) ? (($objRestaurant->status!=0) ?'wp_api':'') : (isset($_REQUEST["ifrm"]) ? 'ifrm' : 'item') ?>=tos"><?= isset($_REQUEST["wp_api"]) ? (($objRestaurant->status!=0) ?'Terms Of Services':'') : 'Terms Of Services' ?></a>
			</li>
			<li style="float:left;padding: 0px 10px;padding: 0px 10px;border-right:1px solid #d3d4d5;">
				<a href="?<?= isset($_REQUEST["wp_api"]) ? (($objRestaurant->status!=0) ?'wp_api':'') : (isset($_REQUEST["ifrm"]) ? 'ifrm' : 'item') ?>=privacypolicy"><?= isset($_REQUEST["wp_api"]) ? (($objRestaurant->status!=0) ?'Privacy Policy':'') : 'Privacy Policy' ?></a>
			</li>
			<li style="float:left;padding: 0px 10px;">
				<a href="?<?= isset($_REQUEST["wp_api"]) ? (($objRestaurant->status!=0) ?'wp_api':'') : (isset($_REQUEST["ifrm"]) ? 'ifrm' : 'item') ?>=refundpolicy"><?= isset($_REQUEST["wp_api"]) ? (($objRestaurant->status!=0) ?'Refund Policy':'') : 'Refund Policy' ?></a>
			</li>
		</ul>
	</div>
	</body>
	</html>
	<?php   
	} 
	?>
