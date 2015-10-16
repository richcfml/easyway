<?php
	require_once("../classes/Restaurant.php");
	$Objrestaurant_data=new Restaurant();
	$Objrestaurant=new Restaurant();
	
	if ((isset($_GET["cid"])) && (is_numeric($_GET["cid"])))
	{
		$mRestaurantIDCP = $_GET["cid"];
	}
	else if ((isset($_GET["catid"])) && (is_numeric($_GET["catid"])))
	{
		$mRestaurantIDCP = $_GET["catid"];
	}
	else if ((isset($_GET["restid"])) && (is_numeric($_GET["restid"])))
	{
		$mRestaurantIDCP = $_GET["restid"];
	}
	else if ((isset($_GET["category_id"])) && (is_numeric($_GET["category_id"])))
	{
		$mRestaurantIDCP = $_GET["category_id"];
	}
	else if ((isset($_GET["OrderID"])) && (is_numeric($_GET["OrderID"])))
	{
		$mOrderID = $_GET["OrderID"];
		$mResult = dbAbstract::Execute("SELECT cat_id FROM ordertbl WHERE OrderID=".$mOrderID,1);
		if (dbAbstract::returnRowsCount($mResult,1)>0)
		{
			$mRow = dbAbstract::returnObject($mResult,1);
			$mRestaurantIDCP = $mRow->cat_id;
		}
		else
		{
			redirect("./?mod=resturant");
		}
	}
	else if ((isset($_GET["userid"])) && (is_numeric($_GET["userid"])))
	{
		$mUserID = $_GET["userid"];
		$mResult = dbAbstract::Execute("SELECT resturant_id FROM customer_registration WHERE id=".$mUserID,1);
		if (dbAbstract::returnRowsCount($mResult,1)>0)
		{
			$mRow = dbAbstract::returnObject($mResult,1);
			$mRestaurantIDCP = $mRow->resturant_id;
		}
		else
		{
			redirect("./?mod=resturant");
		}
	}
	else if ((isset($_GET["mailid"])) && (is_numeric($_GET["mailid"])))
	{
		$mMailID = $_GET["mailid"];
		$mResult = dbAbstract::Execute("SELECT resturant_id FROM mailing_list WHERE id=".$mMailID,1);
		if (dbAbstract::returnRowsCount($mResult,1)>0)
		{
			$mRow = dbAbstract::returnObject($mResult,1);
			$mRestaurantIDCP = $mRow->resturant_id;
		}
		else
		{
			redirect("./?mod=resturant");
		}
	}
	else if ((isset($_GET["coupon_id"])) && (is_numeric($_GET["coupon_id"])))
	{
		$mCouponID = $_GET["coupon_id"];
		$mResult = dbAbstract::Execute("SELECT resturant_id FROM coupontbl WHERE coupon_id=".$mCouponID,1);
		if (dbAbstract::returnRowsCount($mResult,1)>0)
		{
			$mRow = dbAbstract::returnObject($mResult,1);
			$mRestaurantIDCP = $mRow->resturant_id;
		}
		else
		{
			redirect("./?mod=resturant");
		}
	}	
	else
	{
		redirect("./?mod=resturant");
	}
	
	if ((!is_numeric($mRestaurantIDCP)) || ($mRestaurantIDCP<=0))
	{
		redirect("./?mod=resturant");
	}
	
        if ($_SESSION['admin_type'] == 'bh') 
        {
            $mSQLBH = dbAbstract::ExecuteObject("SELECT bh_restaurant FROM resturants WHERE id=".$mRestaurantIDCP);
            if ($mSQLBH)
            {
                if ($mSQLBH->bh_restaurant<=0)
                {
                    redirect("./?mod=resturant");
                }
            }
            else
            {
                redirect("./?mod=resturant");
            }
        }
        
	$Objrestaurant= $Objrestaurant_data->getDetailByRestaurantID($mRestaurantIDCP);
		 
	if (($Objrestaurant->region == 1)  || ($Objrestaurant->region == 2))
	{
		  $currency = "$";
		  $java_currency = "$";
	}
	else
	{
		$currency = "&#163;"; 
		$java_currency = "\u00A3";
	}

	if ( $_SESSION['admin_type'] == 'store owner' && $Objrestaurant->owner_id !=$_SESSION['owner_id'] )
	{
		echo "<script>window.location='./?mod=resturant'</script>";
	}
	else if( $_SESSION['admin_type'] == 'reseller')  
	{
		$client_ids= $_SESSION['RESSELER_CLIENTS'] ;
		$qry = "SELECT count(*) as total FROM resturants WHERE owner_id IN ( $client_ids ) and id=".$mRestaurantIDCP;
		$result=dbAbstract::ExecuteObject($qry,1);
		if($result->total==0)
		echo "<script>window.location='./?mod=resturant'</script>";
	}
?>
  
<?php 
if($Objrestaurant) 
{ 
    if($Objrestaurant->header_image != '')
    {   
        $imageSrc = "../images/resturant_headers/$Objrestaurant->header_image?".time();
       
    }else{
        $imageSrc = "../images/default_200_by_200.jpg";
    }
?>
	<div style="padding-bottom:10px;text-align:center">
    	<img style="width:1085px; height:90px;" id="restaurant_header" src="<?= $imageSrc; ?>" border="0" />
	</div>
<?php 
} 
?>
 	
<?php 
	@$customerQry		=	dbAbstract::Execute("SELECT count(*) as total from customer_registration where epassword != '' AND resturant_id= ".$mRestaurantIDCP,1);
	@$totalCustomers_rs	=	dbAbstract::returnObject($customerQry,1);
	$totalCustomers=$totalCustomers_rs->total;
	
	@$orderQry			=	dbAbstract::Execute("SELECT count(*) as total from ordertbl where cat_id= ".$mRestaurantIDCP." AND payment_approv = 1",1);
	@$totalOrders_rs 	=  dbAbstract::returnObject($orderQry,1);
	$totalOrders=$totalOrders_rs->total;	
	if(!isset($item))
	{
		$item='';
	}

        $menyqry = dbAbstract::ExecuteArray("SELECT  id, menu_name FROM menus where rest_id = $mRestaurantIDCP AND status = 1 ORDER BY menu_ordering ASC limit 1",1);
        $menu_id = $menyqry['id'];
        $menu_name = $menyqry['menu_name'];
        if(!empty($menu_id))
        {
            $newMenuUrl = "&catid=".$mRestaurantIDCP."&menuid=".$menu_id."&menu_name=".$menu_name;
        }
        else
        {
            $newMenuUrl = "&catid=".$mRestaurantIDCP;
        }
?>
<div id="navigation_links">
	<div id="navigation">
		<div class="links <?=$mod=='resturant' ? 'selected' : ''?>"><a href="?mod=resturant&item=restedit&cid=<?=$mRestaurantIDCP?>" >Restaurant</a></div>
		<div class="links <?=$mod=='order' ? 'selected' : ''?>"><a href="?mod=order&cid=<?=$mRestaurantIDCP?>" class="">Orders<?="(".$totalOrders.")"; ?></a></div>
		<div class="links <?=$mod=='customer' ? 'selected' : ''?>"><a href="?mod=customer&cid=<?=$mRestaurantIDCP?>" class="">Customers<?="(".$totalCustomers.")"; ?></a></div>
		<div class="links <?=$mod=='coupon' ? 'selected' : ''?>"><a href="?mod=coupon&cid=<?=$mRestaurantIDCP?>" class="">Coupons</a></div>
		<div class="links <?=$mod=='new_menu' ? 'selected' : ''?>"><a href="?mod=new_menu<?=$newMenuUrl?>" class="">Menus</a></div>
		<div class="links <?=$mod=='mailing_list' ? 'selected' : ''?>"><a href="?mod=mailing_list&cid=<?=$mRestaurantIDCP?>"class="">Mailing List</a></div>
    	<?php 
			if($_SESSION['admin_type'] == 'admin' || $_SESSION['admin_type'] == 'reseller'  ) 
			{
		?>
				<div class="links <?=$mod=='advanced_settings' ? 'selected' : ''?>"><a href="?mod=advanced_settings&cid=<?=$mRestaurantIDCP?>"class="">Advanced Settings</a></div>
		<?php 
			}
		?>

		<?php 
			if($_SESSION['admin_type'] == "admin" || $_SESSION['admin_type'] == "reseller" || $_SESSION['admin_type'] == "store owner") 
			{ 
		?>
				<div class="links <?=$mod=='analytics' ? 'selected' : ''?>"><a href="?mod=analytics&cid=<?=$mRestaurantIDCP?>"class="">Analytics</a></div>
		<?php
        		$qry = "SELECT * FROM resturants WHERE id=".$mRestaurantIDCP;
                $result=dbAbstract::ExecuteObject($qry,1);
                if($result->premium_account == 1)
				{
		?>
					<div class="links <?=$mod=='reputation' ? 'selected' : ''?>"><a href="?mod=overview&cid=<?=$mRestaurantIDCP?>" class="">Reputation</a></div>
		<?php 
				} 
			}
		?>
		<br style="clear:both" />
	</div>
</div>
