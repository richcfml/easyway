<?php
if ($mod == 'overview') {
    $admin_subcontent = "admin_contents/reputation/overview.php";
} else if ($mod == 'mentions') {
    $admin_subcontent = "admin_contents/reputation/mentions.php";
} else if ($mod == 'mentionsall') {
    $admin_subcontent = "admin_contents/reputation/mentionsall.php";
} else if ($mod == 'social') {
    $admin_subcontent = "admin_contents/reputation/tab_social_listings.php";
} else if ($mod == 'competition') {
    $admin_subcontent = "admin_contents/reputation/competition.php";
} else if ($mod == 'account') {
    $admin_subcontent = "admin_contents/reputation/account.php";
} else if ($mod == 'visibility') {
    $admin_subcontent = "admin_contents/reputation/visibility.php";
} else if ($mod == 'reviews') {
    $admin_subcontent = "admin_contents/reputation/reviews.php";
}
?>
<!------------------------Start--------------------------->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.js"></script>
<script src="js/fancybox.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/fancy.css">

<style type="text/css">
    .fancybox-overlay {
        background: url(images/fancybox_overlay.png);
    }
</style>
<div id="BodyContainer">

<?php
	include "includes/resturant_header.php";
	
	$mExists = false;
	$mSRID = "";
	if (isset($Objrestaurant->srid))
	{
		if (trim($Objrestaurant->srid)!="")
		{
			$mExists = true;
			$mSRID = $Objrestaurant->srid;
		}
	}
	
	if (!$mExists) //No SRID exists for restaurant
	{
		$demoAccountFlag = 'true';
		$parameters = "address=".$Objrestaurant->rest_address."&city=".$Objrestaurant->rest_city."&companyName=".$Objrestaurant->name."&country=US&state=".$Objrestaurant->rest_state."&zip=".$Objrestaurant->rest_zip;
		if ($Objrestaurant->premium_account==1)
		{
			$demoAccountFlag = 'false';
		}
		
		$parameters = $parameters."&demoAccountFlag=".$demoAccountFlag."&salesPersonEmail=cwilliams@easywayordering.com";
		$mURL2 = "https://reputation-intelligence-api.vendasta.com/api/v2/account/create/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1";
		$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, $mURL2);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch2, CURLOPT_POST, 1);
		curl_setopt($ch2, CURLOPT_POSTFIELDS, $parameters);
	
		$mResult2 = curl_exec($ch2);
		curl_close($ch2);
		unset($ch2);
		$mResult2= json_decode($mResult2);
		$mResult2 = (object) $mResult2;
		
		if (isset($mResult2->data))
		{
			$mResult2->data = (object) $mResult2->data;
			$data = $mResult2->data;
			$mSRID = $data->srid;
			
			if (trim($mSRID)!="")
			{
                            dbAbstract::Update("UPDATE resturants SET srid='".$mSRID."' where id=".$Objrestaurant->id,1);
			}
		}
	}
	
	if ($mSRID=="")
	{
		redirect("./?mod=resturant&item=restedit&cid=".$Objrestaurant->id);
	}
	
	$Objrestaurant->srid = $mSRID;
	
    include $admin_subcontent;
?>

</div>
<!------------------------End--------------------------->