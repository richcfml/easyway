<?php 
	if($_GET['mod'])
	{
		$mod = $_GET['mod']; 
	}
	else 
	{
		$mod = 'resturant';
	}
        
        /* Access Management For Admins/Resellers/Store Owners and BH admins Starts Here */
        if ($_SESSION['admin_type'] == 'admin')
        {
            if (($mod=='resellers') && (isset($_GET['item'])) && ($_GET['item']=='profile'))
            {
                redirect($AdminSiteUrl.'?mod=resturant');
            }
        }
        else if ($_SESSION['admin_type'] == 'reseller')
        {
            if ((($mod=='resellers') && (!isset($_GET['item']) || ($_GET['item']!='profile' && $_GET['item']!='edit'))) || ($mod=='feedback'))
            {
                redirect($AdminSiteUrl.'?mod=resturant');
            }
        }
        else if ($_SESSION['admin_type'] == 'store owner')
        {
            if (($mod=='resellers') || ($mod=='feedback') || ($mod=='clients') || (($mod=='resturant') && (isset($_GET['item']) && $_GET['item']=='add')))
            {
                redirect($AdminSiteUrl.'?mod=resturant');
            }
        }
        else if ($_SESSION['admin_type'] == 'bh')
        {
            if (($mod=='resellers') || ($mod=='clients') || (($mod=='resturant') && (isset($_GET['item']) && $_GET['item']=='add')))
            {
                redirect($AdminSiteUrl.'?mod=resturant');
            }
        }
        
        /* Access Management For Admins/Resellers/Store Owners and BH admins Ends Here */

	if($mod == 'clients')	
	{ 
	$admin_include_content = "admin_contents/users/tab_user_main.php";
	}
	
	if($mod == 'resellers')	
	{ 
	$admin_include_content = "admin_contents/resellers/tab_reseller_main.php";
	}
	
	if($mod == 'resturant')		
	{ 
		$admin_include_content = "admin_contents/resturants/tab_resturant_main.php";
	} 
	
	if($mod == 'customer')		
	{ 
		$admin_include_content = "admin_contents/customer/tab_customer_main.php";
	} 
	
	if($mod == 'order')			
	{ 
		$admin_include_content = "admin_contents/orders/tab_order_main.php";
	} 
	
	if($mod == 'attribute')		
	{ 
		$admin_include_content = "admin_contents/attributes/tab_attribute_main.php";
	} 
	
	if($mod == 'changepass')	
	{ 
		$admin_include_content = "admin_contents/tab_changepass.php";
	} 
	
	if($mod == 'apikey')	
	{ 
		$admin_include_content = "admin_contents/advanced_settings/apikey.php";
	} 
	
	if($mod == 'ssoaccount')	
	{ 
		$admin_include_content = "admin_contents/advanced_settings/ssoaccount.php";
	}
        if($mod == 'feedback')	
	{ 
		$admin_include_content = "admin_contents/advanced_settings/feedback.php";
	}
	if($mod == 'news')			
	{ 
		$admin_include_content = "admin_contents/news/tab_news_main.php";
	} 
	
	if($mod == 'coupon')		
	{ 
		$admin_include_content = "admin_contents/coupon/tab_coupon_main.php";
	}
	
	if($mod == 'contents')		
	{ 
		$admin_include_content = "admin_contents/bottom_contents/tab_bottom_main.php";
	}
	
	if($mod == 'menus')			
	{ 
		$admin_include_content = "admin_contents/menus/tab_resturant_menus.php";
	}
	
	if($mod == 'mailing_list')	
	{ 
		$admin_include_content = "admin_contents/mailing_list/tab_mailing_list_main.php";
	}
	
	if($mod == 'analytics')		
	{ 
		$admin_include_content = "admin_contents/analytics/tab_analytics_main.php";
	}
	
        if($mod == 'new_menu')	
	{
		$admin_include_content = "admin_contents/menus/new_menu.php";
	}
	
	if($mod == 'overview' || $mod == 'social' || $mod == 'mentions' || $mod == 'mentionsall' || $mod == 'competition' || $mod == 'visibility' || $mod == 'account' || $mod == 'reviews')
	{
		$admin_include_content = "admin_contents/reputation/tab_reputation_main_new.php";
	}
	
	if($mod == 'iframe_settings')		
	{ 
		$admin_include_content = "admin_contents/iframe/iframe_settings.php";
	}
        
        if($mod == 'wordpress_settings')		
	{ 
		$admin_include_content = "admin_contents/advanced_settings/wpsettings.php";
	}
    
	if($mod == 'reputation')
	{
		$admin_include_content = "admin_contents/reputation/tab_reputation_main.php";
	}
     
	if($_SESSION['admin_type'] == 'admin' || $_SESSION['admin_type'] == 'reseller') 
	{
		if($mod == 'advanced_settings' || $mod == 'coverstation_settings' ||  $mod == 'valutec_loyalty' )	
		{
			$admin_include_content = "admin_contents/advanced_settings/tab_advanced_settings_main.php";
		}
	}
	if($_SESSION['admin_type'] == 'bh') 
	{
		if($mod == 'signaturesandwitch')	
		{
			$admin_include_content = "admin_contents/signature_sandwitch/tab_add_signature_sandwitch.php";
		}
	}
	if($ajax==0) 
	{
?>
		<div id="navigation">
			<?php 
			if($_SESSION['admin_type'] == 'admin') 
			{ 
			?>
				<div class="links <?=$mod=='resellers' ? 'selected' : 'not'?>"><a href="<?=$AdminSiteUrl?>?mod=resellers">Resellers  <?="(".$totalresellers.")" ?></a></div>
				<div class="links <?=$mod=='clients' ? 'selected' : 'not'?>"><a href="<?=$AdminSiteUrl?>?mod=clients">Clients  <?="(".$totalClients.")"?></a></div>
			<?php 
			} 
			else if($_SESSION['admin_type'] == 'reseller') 
			{ 
			?>
				<div class="links <?=$mod=='clients' ? 'selected' : 'not'?>"><a href="<?=$AdminSiteUrl?>?mod=clients">Clients  <?="(".$totalClients.")"?></a></div>
			<?php 
			}
			?> 
			<div class="links <?=$mod=='resturant' ? 'selected' : 'not'?>"><a href="<?=$AdminSiteUrl?>?mod=resturant" >Restaurants <?="(".$totalResturants.")"?></a></div>
			<div class="links <?=$mod=='changepass' ? 'selected' : 'not'?>"><a href="<?=$AdminSiteUrl?>?mod=changepass" >My Account</a></div> 		  
			<?php 
			if($_SESSION['admin_type'] == 'reseller') 
			{ 
			?>
				<div class="links <?=$mod=='resellers' ? 'selected' : 'not'?>"><a href="<?=$AdminSiteUrl?>?mod=resellers&item=profile">My Profile</a></div>
			<?php 
			} 
			?> 
			<div class="links <?=$mod=='apikey' ? 'selected' : 'not'?>"><a href="<?=$AdminSiteUrl?>?mod=apikey" >API Key</a></div> 	
                        <?php 
			if (($_SESSION['admin_type'] == 'admin') || ($_SESSION['admin_type'] == 'bh'))
			{ 
			?>
                            <div class="links <?=$mod=='feedback' ? 'selected' : 'not'?>"><a href="<?=$AdminSiteUrl?>?mod=feedback" >Customer Feedback</a></div> 	
			<?php 
			} 
			?> 
                        <?php 
			if ($_SESSION['admin_type'] == 'bh')
			{ 
			?>
                                <div class="links <?=$mod=='signaturesandwitch' ? 'selected' : 'not'?>"><a href="<?=$AdminSiteUrl?>?mod=signaturesandwitch" >Signature Sandwich</a></div> 	
			<?php 
			} 
			?> 
                        <br style="clear:both" />
		</div>
<?php 
	} 
?>
