<?php
if (strtolower(trim($_GET["item"]))!="login")
{
	if(isset($_REQUEST["ifrm"])) 
	{
?>	
	<script type="text/javascript" src="//connect.facebook.net/en_US/all.js"></script> 
	<script type="text/javascript" language="javascript">
	window.fbAsyncInit = function () {
		FB._https = true;
		FB.init({
		  appId  : '569304429756200', // 597714500283054
		  status : true, // check login status
		  cookie : true, // enable cookies to allow the server to access the session
		  xfbml  : true  // parse XFBML
		});
<?php
	if (strtolower(trim($_GET["item"]))!="thankyou")
	{
?>
		FB.Canvas.setAutoGrow();
<?php
	}
	else
	{
?>		
		FB.Canvas.setDoneLoading( function(result) 
		{
			FB.Canvas.setSize({height: 900 });
		});
<?php
	}
?>
	};
	</script>
<?php
	}
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
	<meta charset="utf-8">
	<title><?=$objRestaurant->name;?></title>
   	<meta NAME="DESCRIPTION" CONTENT="<?=trim(stripslashes($objRestaurant->meta_description));?>">
	<meta NAME="KEYWORDS" CONTENT="<?=trim(stripslashes($objRestaurant->meta_keywords));?>">

    <link href="<?php echo $css_path; ?>index_style_new_wp_api.css?t=<?= time() ?>" type="text/css" rel="stylesheet" media="screen">
	<script src="<?php echo $js_root; ?>jquery.min.js"></script>
    <script src="<?php echo $js_root; ?>jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?php echo $js_root; ?>jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo $js_root; ?>jquery.prettyPhoto.js" type="text/javascript"></script>

    <link href="<? echo $css_path;  ?>facebox.css" media="screen" rel="stylesheet" type="text/css"/>
    <link href="<? echo $css_path;  ?>prettyPhoto.css" media="screen" rel="stylesheet" type="text/css"/>
	<script src="<? echo $js_root;  ?>facebox.js" type="text/javascript"></script>

    <script language="javascript">
        jQuery(document).ready(function($) {
            $('a[rel*=facebox]').facebox();
        });

        function showDiv(divname){
            $(divname).show();
        }
        function hideDiv(divname){
            $(divname).hid();
        }
    </script>
<?php
	//Code For Tracking Visits Starts Here// Gulfam 27 March 2014
	$mIPAddress = "Unknown";
	$mSessionID = session_id();
	$mRestaurantID = $objRestaurant->id;
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARTDED_FOR'] != '') 
	{
    	$mIPAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} 
	else 
	{
    	$mIPAddress = $_SERVER['REMOTE_ADDR'];
	}
	
	$mResult = dbAbstract::Execute("SELECT COUNT(*) AS VisitCount FROM facebookvisits WHERE RestaurantID=".$mRestaurantID." AND IPAddress='".$mIPAddress."' AND SessionID='".$mSessionID."'");
	if (dbAbstract::returnRowsCount($mResult)>0)
	{
		$mRow = dbAbstract::returnObject($mResult);
		if (is_numeric($mRow->VisitCount))
		{
			if ($mRow->VisitCount<1) //No Entry for this visit, Record this visit
			{
				$mResult = dbAbstract::Insert("INSERT INTO facebookvisits (RestaurantID, IPAddress, SessionID) VALUES (".$mRestaurantID.", '".$mIPAddress."', '".$mSessionID."')");
			}
		}
	}
	
	//Code For Tracking Visits Ends Here// Gulfam 27 March 2014
	$mGFS = "12";
	$mGTC = "#000000";
	$mSTC = "#000000";
	$mMBC = "#F4F4F4";
	$mMLCA = "#CC0000";
	$mMLCI = "#333333";
	$mSMHC = "#585858";
	$mSMDC = "#585858";
	$mITC = "#000000";
	$mIPC = "#000000";
	$mIDC = "#000000";
	$mIPFS = "14";
	$mYOSC = "#5F5F5F";
	$mYOSFS = "18";
	$mCBC = "#fff";
	$mCBrC = "#e4e4e4";
	$mCBT = "1";
	$mTFS = "12";
	$mTF = "Arial,Helvetica,sans-serif";
	$mMWC = "0";
	$mCVPB = "#00CCFF";
	$mOOBI = "";
	$mCBI="";
	$mCBIST=0;
	$mSLB = 0;
	$mLS = 0;
	$mSPD = 0;
	
	$mIframeSettings = $objRestaurant->getIframeDetailsByRestaurantID($objRestaurant->id);
	if ($mIframeSettings!=0)
	{
		$mGFS = $mIframeSettings->GeneralFontSize;
		$mGTC = $mIframeSettings->GeneralTextColor;
		$mSTC = $mIframeSettings->SecondaryTextColor;
		$mMBC = $mIframeSettings->MenuBGColor;
		$mMLCA = $mIframeSettings->MenuLinkColorOnActive;
		$mMLCI = $mIframeSettings->MenuLinkColorOnInactive;
		$mSMHC = $mIframeSettings->SubMenuHeadingsColor;
		$mSMDC = $mIframeSettings->SubMenuDescriptionsColor;
		$mITC = $mIframeSettings->ItemsTitleColor;
		$mIPC = $mIframeSettings->ItemsPriceColor;
		$mIDC = $mIframeSettings->ItemsDscriptionColor;
		$mIPFS = $mIframeSettings->ItemsPricesFontSize;
		$mYOSC = $mIframeSettings->YourOrderSummaryColor;
		$mYOSFS = $mIframeSettings->YourOrderSummaryFontSize;
		$mCBC = $mIframeSettings->CellBGColor;
		$mCBrC = $mIframeSettings->CellBorderColor;
		$mCBT = $mIframeSettings->CellBorderThickness;
		$mTFS = $mIframeSettings->TitlesFontSize;
		$mTF = $mIframeSettings->TitlesFont;
		$mMWC = $mIframeSettings->MinWidthOfTheContainer;
		$mCVPB = $mIframeSettings->ColorForVIPProgressBar;
		$mOOBI = $mIframeSettings->OrderOnlineButtonImage;
		$mCBI = $mIframeSettings->CellBGImage;
		$mCBIST = $mIframeSettings->CellBGImageStretchTile;
		$mSLB = $mIframeSettings->ShowLoyaltyBox;
		$mLS = $mIframeSettings->LayoutStyle;
		$mSPD = $mIframeSettings->ShowPicturesDescription;
		if ($mLS==0)
		{
			$mSPD = 0;
		}
	}
	$mMinWidth = '';
	if (empty($mMWC) || $mMWC == "0")
	{
		$mMinWidth = "780px";
	}
	else
	{
		$mMinWidth = $mMWC."px";
	}

	if ($mOOBI == "")
	{
		$mOOBI = "background-image: url('../images/online_ordering.gif');";
	}
	else
	{
		$mOOBI = "background-image: url('".$mOOBI."');";
	}
	
	if ($mCBI == "")
	{
		$mCBI = "background-image: none;";
		$mCBIST = "";
	}
	else
	{
		$mCBI = "background-image: url('".$mCBI."');";
		if($mCBIST == 1) 
		{
			$mCBIST = "background-repeat: repeat !important;";
		} 
		else 
		{ 
			$mCBIST = "background-position: center top !important; background-size: 100% 100% !important;";
		}
	}
	
	$mCSSStr = "<style type='text/css'>body, .radio_bttn, #body #items_bg, .second_body_heading, #body #body_heading, .text_12px  {	color: ".$mGTC." !important;	font-size: ".$mGFS." !important;}.generaltext2,.second_body_text ,.second_body_text a,.second_body_text a:hover,.account_detail,.account_detail a,.account_detail a:hover,.vipmessage{	color: ".$mSTC." !important;	}.second_body_heading, #body #body_heading, .forget, .username, #rewardwrap a {	color: ".$mGTC." !important;}#maincontainer {	min-width: ".$mMinWidth." !important;} #display_uername_area a {	color: ".$mMLCI." !important;}#display_uername_area a.selected_red {	color: ".$mMLCA." !important;}#display_uername_area {    background-color: ".$mMBC." !important;}.left_col_inner_block .product {    color: ".$mSMHC." !important;	font-family: ".$mTF." !important;	font-size: ".$mTFS."px !important;	background-color: ".$mMBC." !important;	}";
	$mCSSStr = $mCSSStr.".left_col_inner_block .product_name a, .flip #counting, #contents .subtotal {    color: ".$mITC." !important;}.favTitle {    color: ".$mITC." !important;}.left_col_inner_block .product_price, .flip #dollor, #contents .amount  {    color: ".$mIPC." !important;}.favPrice  {    color: ".$mIPC." !important;}.left_col_inner_block .product_name span {    color: ".$mIDC." !important;}.left_col_inner_block .product span {    color: ".$mSMDC." !important;}.favHead {    color: ".$mSMDC." !important;}#body #body_right_col #your_summery {    color: ".$mYOSC." !important;	font-size: ".$mYOSFS."px !important;}.products_area, .products_area a {	font-size: ".$mIPFS."px !important;}#dhtmltooltip span {	color: ".$mSMHC." !important;}#dhtmltooltip {	color: ".$mSMDC." !important;}#body_right_col #contents {	padding-bottom: 10px !important;}";
	$mCSSStr = $mCSSStr.".left_col_inner_block, #body_right_col {	background-color: ".$mCBC." !important;".$mCBI.$mCBIST."	}";
	$mCSSStr = $mCSSStr.".left_col_inner_block, #body_right_col {	border-width: ".$mCBT."px !important;	border-color: ".$mCBrC." !important;	border-style: solid;}";
	$mCSSStr = $mCSSStr.".online_ordering .button {	background-image: ".$mOOBI." !important;	background-position: center center;	background-repeat: no-repeat;	width: 255px;	height: 37px;	display:block;	margin: 10px auto;}#rewardwrap .bar .barOuter .barInner {    background: none repeat scroll 0 0 ".$mCVPB." !important;}</style>";
?>
<span id="spnCSS"><?php echo($mCSSStr); ?></span>
</head>
<body>

<div id="maincontainer">
<? require($site_root_path . "views/ifrm/if_home_nav.php") ?>

 