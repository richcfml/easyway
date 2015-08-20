<?php
require_once("includes/config.php");
include_once("c_panel/classes/Chargify_Api.php");

$mResultDiv = " style='display: none;' ";
$mdvTwenty = " style='display: block;' ";
$mRestaurantID = -1;
$mDupError = "";
$mDupShowHide = " style='display: none;' ";

						   
$mGoogleAPIKey = "AIzaSyBOYImEs38uinA8zuHZo-Q9VnKAW3dSrgo";

for($loopCount=0; $loopCount<3; $loopCount++) 
{
	$mRestDet[$loopCount]["DivShowHide"] = " style='display: none;' ";
	$mRestDet[$loopCount]["Image"] = "";
	$mRestDet[$loopCount]["Name"] = "";
	$mRestDet[$loopCount]["Address"] = "";
	$mRestDet[$loopCount]["Phone"] = "";
	$mRestDet[$loopCount]['street_number'] = "";
	$mRestDet[$loopCount]['street_name'] = "";
	$mRestDet[$loopCount]['city'] = "";
	$mRestDet[$loopCount]['state'] = "";
	$mRestDet[$loopCount]['country'] = "";
	$mRestDet[$loopCount]['zip_code'] = "";
}

if (isset($_POST["btnUpload"]))
{
	extract ($_POST);
	$mNewDomain = 0;
	if (isset($rbNewDomain))
	{
		$mNewDomain = 1;
	}
	
	if (trim(strtolower($txtDomainName))=="")
	{
		$mNewDomain = 1;
	}
	
	if (is_numeric($txtRestaurantID))
	{
		if ($txtRestaurantID>0)
		{
			$mRestaurantID = $txtRestaurantID;
			$mSQL = "UPDATE signupdetails SET `TimeZoneID`=".$ddlTimeZone.", `RestaurantName`='".dbAbstract::returnRealEscapedString($txtRestaurantName)."', `Address`='".dbAbstract::returnRealEscapedString($txtStreetAddress)."', `State`='".dbAbstract::returnRealEscapedString($txtState)."', `City`='".dbAbstract::returnRealEscapedString($txtCity)."', `ZipCode`='".dbAbstract::returnRealEscapedString($txtZip)."', `Country`='".$ddlCountry1."', `PhoneNumber`='".dbAbstract::returnRealEscapedString($txtPhone)."', `FaxNumber`='".dbAbstract::returnRealEscapedString($txtFax)."', `FullName`='".dbAbstract::returnRealEscapedString($txtFullName)."', `EmailAddress`='".dbAbstract::returnRealEscapedString($txtEmailAddress)."', `Password`='".dbAbstract::returnRealEscapedString($txtPassword)."', `UserName`='".dbAbstract::returnRealEscapedString($txtUserName)."', `OrderReceive`=".$rbOrders.", `Delivery`=".$rbDelivery.", `Tax`='".dbAbstract::returnRealEscapedString($txtTax)."', `Cash`=".$rbCash.", `CreditCard`=".$rbCreditCard.", `GateWay`=".$rbGateWay.", `DomainName`='".dbAbstract::returnRealEscapedString($txtDomainName)."', `NewDomain`=".$mNewDomain.", `MenuUse`=".$rbMenuUse.", `HostingInformation`=".$rbHosting.", `DeliveryMinimum`='".$txtDeliveryMinimum."', `DeliveryCharges`='".$txtDeliveryCharges."', `DeliveryRadius`='".$txtDeliveryRadius."', `ClientAddress`='".dbAbstract::returnRealEscapedString($txtClientAddress)."', `ClientState`='".dbAbstract::returnRealEscapedString($txtClientState)."', `ClientCity`='".dbAbstract::returnRealEscapedString($txtClientCity)."', `ClientZipCode`='".dbAbstract::returnRealEscapedString($txtClientZip)."', `ClientCountry`='".$ddlCountry2."' WHERE ID=".$txtRestaurantID;
			dbAbstract::Update($mSQL);
		}
		else
		{
			
			$mSQL = "SELECT COUNT(*) AS UserCount FROM `users` WHERE LOWER(TRIM(username))='".trim(strtolower($_GET["username"]))."'";
			$mResult = dbAbstract::Execute($mSQL);
			if (dbAbstract::returnRowsCount($mResult)>0)
			{
				$mRow = dbAbstract::returnObject($mResult);
				if ($mRow->UserCount>0)
				{
					$mDupError = "Username already in use.";
					$mDupShowHide = " style='display: block;' ";
				}
				else
				{
					$mDupShowHide = " style='display: none;' ";
					$mDupError = "";
					$mSQL = "INSERT INTO  signupdetails (`TimeZoneID`, `RestaurantName`, `Address`, `State`, `City`, `ZipCode`, `Country`, `PhoneNumber`, `FaxNumber`, `FullName`, `EmailAddress`, `Password`, `UserName`, `OrderReceive`, `Delivery`, `Tax`, `Cash`, `CreditCard`, `GateWay`, `DomainName`, `NewDomain`, `MenuUse`, `HostingInformation`, `DeliveryMinimum`, `DeliveryCharges`, `DeliveryRadius`, `ClientAddress`, `ClientState`, `ClientCity`, `ClientZipCode`, `ClientCountry`) VALUES ";
					$mSQL .= "(".$ddlTimeZone.", '".dbAbstract::returnRealEscapedString($txtRestaurantName)."', '".dbAbstract::returnRealEscapedString($txtStreetAddress)."', '".dbAbstract::returnRealEscapedString($txtState)."', '".dbAbstract::returnRealEscapedString($txtCity)."', '".dbAbstract::returnRealEscapedString($txtZip)."', '".$ddlCountry1."', '".dbAbstract::returnRealEscapedString($txtPhone)."', '".dbAbstract::returnRealEscapedString($txtFax)."', '".dbAbstract::returnRealEscapedString($txtFullName)."', '".dbAbstract::returnRealEscapedString($txtEmailAddress)."', '".dbAbstract::returnRealEscapedString($txtPassword)."', '".dbAbstract::returnRealEscapedString($txtUserName)."', ".$rbOrders.", ".$rbDelivery.", '".dbAbstract::returnRealEscapedString($txtTax)."', ".$rbCash.", ".$rbCreditCard.", ".$rbGateWay.", '".dbAbstract::returnRealEscapedString($txtDomainName)."', ".$mNewDomain.", ".$rbMenuUse.", ".$rbHosting.", '".$txtDeliveryMinimum."', '".$txtDeliveryCharges."', '".$txtDeliveryRadius."', '".dbAbstract::returnRealEscapedString($txtClientAddress)."', '".dbAbstract::returnRealEscapedString($txtClientState)."', '".dbAbstract::returnRealEscapedString($txtClientCity)."', '".dbAbstract::returnRealEscapedString($txtClientZip)."', '".dbAbstract::returnRealEscapedString($ddlCountry2)."')";
					$mRestaurantID = dbAbstract::Insert($mSQL, 0 ,2);
				}
			}
			else
			{
					$mDupError = "Error occurred.";
					$mDupShowHide = " style='display: block;' ";
			}
			
			

		}
	}
	else
	{
		$mSQL = "SELECT COUNT(*) AS UserCount FROM `users` WHERE LOWER(TRIM(username))='".trim(strtolower($_GET["username"]))."'";
		$mResult = dbAbstract::Execute($mSQL);
		if (dbAbstract::returnRowsCount($mResult)>0)
		{
			$mRow = dbAbstract::returnObject($mResult);
			if ($mRow->UserCount>0)
			{
				$mDupError = "Username already in use.";
				$mDupShowHide = " style='display: block;' ";
			}
			else
			{
				$mDupShowHide = " style='display: none;' ";
				$mDupError = "";
				$mSQL = "INSERT INTO  signupdetails (`TimeZoneID`, `RestaurantName`, `Address`, `State`, `City`, `ZipCode`, `Country`, `PhoneNumber`, `FaxNumber`, `FullName`, `EmailAddress`, `Password`, `UserName`, `OrderReceive`, `Delivery`, `Tax`, `Cash`, `CreditCard`, `GateWay`, `DomainName`, `NewDomain`, `MenuUse`, `HostingInformation`, `DeliveryMinimum`, `DeliveryCharges`, `DeliveryRadius`, `ClientAddress`, `ClientState`, `ClientCity`, `ClientZipCode`, `ClientCountry`) VALUES ";
				$mSQL .= "(".$ddlTimeZone.", '".dbAbstract::returnRealEscapedString($txtRestaurantName)."', '".dbAbstract::returnRealEscapedString($txtStreetAddress)."', '".dbAbstract::returnRealEscapedString($txtState)."', '".dbAbstract::returnRealEscapedString($txtCity)."', '".dbAbstract::returnRealEscapedString($txtZip)."', '".$ddlCountry1."', '".dbAbstract::returnRealEscapedString($txtPhone)."', '".dbAbstract::returnRealEscapedString($txtFax)."', '".dbAbstract::returnRealEscapedString($txtFullName)."', '".dbAbstract::returnRealEscapedString($txtEmailAddress)."', '".dbAbstract::returnRealEscapedString($txtPassword)."', '".dbAbstract::returnRealEscapedString($txtUserName)."', ".$rbOrders.", ".$rbDelivery.", '".dbAbstract::returnRealEscapedString($txtTax)."', ".$rbCash.", ".$rbCreditCard.", ".$rbGateWay.", '".dbAbstract::returnRealEscapedString($txtDomainName)."', ".$mNewDomain.", ".$rbMenuUse.", ".$rbHosting.", '".$txtDeliveryMinimum."', '".$txtDeliveryCharges."', '".$txtDeliveryRadius."', '".dbAbstract::returnRealEscapedString($txtClientAddress)."', '".dbAbstract::returnRealEscapedString($txtClientState)."', '".dbAbstract::returnRealEscapedString($txtClientCity)."', '".dbAbstract::returnRealEscapedString($txtClientZip)."', '".dbAbstract::returnRealEscapedString($ddlCountry2)."')";
				$mRestaurantID = dbAbstract::Insert($mSQL, 0 ,2);
			}
		}
		else
		{
			$mDupError = "Error occurred.";
			$mDupShowHide = " style='display: block;' ";
		}
	}
	
	if ((is_numeric($mRestaurantID)) && ($mRestaurantID>0))
	{
		if($_FILES['uploadme']['name'])
		{
			if(!($_FILES['uploadme']['error']))
			{


				if($_FILES['uploadme']['size'] > (5120000)) //can't be larger than 5 MB
				{
					$mUploadError = "File too large. Please upload a file having size less than 5MB.";	
				}
				else
				{
					$mFileName = $_FILES['uploadme']['name'];
					$mExt = GetFileExt($_FILES['uploadme']['name']);
					if (!file_exists('signupUploads')) 
					{
						mkdir('signupUploads', 0777, true);
					}
					
					$mPath = 'signupUploads/';
					$mRandom = mt_rand(1, mt_getrandmax());
					$mFileName = str_replace(".", "_", str_replace(" ", "_", basename($_FILES['uploadme']['name'],$mExt)))."_".$mRandom.$mExt;
					$mFilePath =  $mPath.$mFileName;
					if (!move_uploaded_file($_FILES['uploadme']['tmp_name'] , $mFilePath))
					{
						$mUploadError = "Error occurred. Try again later.";
					}
					else
					{
						$mSQL = "INSERT INTO  signupuploads (`RestaurantID`, `FileName`, `MenuName`) VALUES ";
						$mSQL .= "(".$mRestaurantID.", '".dbAbstract::returnRealEscapedString($mFileName)."', '".dbAbstract::returnRealEscapedString($txtMenuName)."')";
						dbAbstract::Insert($mSQL);
					}
				}
			}
			else
			{
				$mUploadError = "Error occurred. Try again later.";
			}
		}
	}
	
}
else if (isset($_POST["btnSearch"]))
{
	$mResultDiv = " style='display: block;' ";
	$mRestaurant = $_POST["txtRestaurant"];
	$mCityStateZip = $_POST["txtCSZ"];
	$mCountry = $_POST["ddlCountry"];
	
	
	$mURL = "http://maps.google.com/maps/api/geocode/json?address=".$mCityStateZip."&sensor=false&region=".$mCountry;
	$mURL = str_replace(" ", "%20", $mURL);
	$mResponse = file_get_contents($mURL);
	$mResponse = json_decode_ewo($mResponse, true);
 	$mLat = $mResponse["results"][0]["geometry"]["location"]["lat"];
	$mLong = $mResponse["results"][0]["geometry"]["location"]["lng"];
	
	$mURL = "https://maps.googleapis.com/maps/api/place/search/json?location=".$mLat.",".$mLong."&rankby=distance&types=establishment&name=".$mRestaurant."&sensor=false&key=".$mGoogleAPIKey;
	$mURL = str_replace(" ", "%20", $mURL);
	
	$mResponse = file_get_contents($mURL);
	$mResponse = json_decode_ewo($mResponse, true);

	$mTotalCount = count($mResponse['results']);
	
	if ($mTotalCount>0)
	{
		if ($mTotalCount>2)
		{
			$loopMax = 3;
		}
		else
		{
			$loopMax = $mTotalCount;
		}
		
		
		for($loopCount=0; $loopCount<$loopMax; $loopCount++) 
		{
			$mRestDet[$loopCount]["DivShowHide"] = " style='display: inline;' ";
			$mURL = "https://maps.googleapis.com/maps/api/place/details/json?reference=".$mResponse["results"][$loopCount]["reference"]."&sensor=false&key=".$mGoogleAPIKey;
			
			$mURL = str_replace(" ", "%20", $mURL);
			$mResponseDet = file_get_contents($mURL);
			$mResponseDet = json_decode_ewo($mResponseDet, true);
			
			$mAddress_components = $mResponseDet['result']['address_components'];
			$mAddress = array();
			foreach ($mAddress_components as $components)
			{
				$mAddress[$components['types'][0]] = array ('short_name' => $components['short_name'], 'long_name' =>  $components['long_name']);
			}
			
			$mRestDet[$loopCount]['street_number'] = (($mAddress['street_number']['long_name'] != 'false') && ($mAddress['street_number']['long_name'] != '')) ? $mAddress['street_number']['long_name'] : '';
			$mRestDet[$loopCount]['street_name'] = (($mAddress['route']['long_name'] != 'false') && ($mAddress['route']['long_name'] != '')) ? $mAddress['route']['long_name'] : '';
			
			if (($mRestDet[$loopCount]['street_number']=='') && ($mRestDet[$loopCount]['street_name']==''))
			{
				$mRestDet[$loopCount]['street_number'] = substr($mResponseDet["result"]["formatted_address"], 0, strpos($mResponseDet["result"]["formatted_address"], ","));
			}
			
			$mRestDet[$loopCount]['city'] = (($mAddress['locality']['long_name'] != 'false') && ($mAddress['locality']['long_name'] != '')) ? $mAddress['locality']['long_name'] : '';
			if ($mRestDet[$loopCount]['city']=='')
			{
				$mRestDet[$loopCount]['city'] = (($mAddress['postal_town']['long_name'] != 'false') && ($mAddress['postal_town']['long_name'] != '')) ? $mAddress['postal_town']['long_name'] : '';
			}
			$mRestDet[$loopCount]['state'] = (($mAddress['administrative_area_level_1']['short_name'] != 'false') && ($mAddress['administrative_area_level_1']['short_name'] != '')) ? $mAddress['administrative_area_level_1']['short_name'] : '';
			$mRestDet[$loopCount]['country'] = (($mAddress['country']['short_name'] != 'false') && ($mAddress['country']['short_name'] != '')) ? $mAddress['country']['short_name'] : '';
			$mRestDet[$loopCount]['zip_code'] = (($mAddress['postal_code']['long_name'] != 'false') && ($mAddress['postal_code']['long_name'] != '')) ? $mAddress['postal_code']['long_name'] : '';
			
			if (isset($mResponseDet["result"]["formatted_address"]))
			{
				$mRestDet[$loopCount]["Address"] = $mResponseDet["result"]["formatted_address"];
			}
			
			if (isset($mResponseDet["result"]["formatted_phone_number"]))
			{
				$mRestDet[$loopCount]["Phone"] = $mResponseDet["result"]["formatted_phone_number"];
			}
			
			if (isset($mResponseDet["result"]["name"]))
			{
				$mRestDet[$loopCount]["Name"] = $mResponseDet["result"]["name"];
			}
			
			if (isset($mResponseDet["result"]["photos"]))
			{
				$mPhotoReference = 	$mResponseDet["result"]["photos"][0]["photo_reference"];
				$mURL = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=1200&photoreference=".$mPhotoReference."&sensor=false&key=".$mGoogleAPIKey;
				$mURL = str_replace(" ", "%20", $mURL);
				$mRestDet[$loopCount]["Image"] = $mURL;
			}
			else
			{
				$mRestDet[$loopCount]["Image"] = "images/NIA.jpg";
			}
		}	
	}
	else
	{
		$mdvTwenty = " style='display: none;' ";
	}
}


if (isset($_GET["sdid"]))
{
	if (is_numeric($_GET["sdid"]))
	{
		if ($_GET["sdid"]>0)
		{
			if (isset($_GET["token"]))
			{
				$mResTK = dbAbstract::Execute("SELECT IFNULL(RestaurantID, 0) AS RestaurantID, IFNULL(Token, '') AS Token FROM signupdetails WHERE ID=".$_GET["sdid"]);
				$mRowTK = dbAbstract::returnObject($mResTK);
				$mToken = $mRowTK->Token;
				
				if ($mToken==$_GET["token"])
				{
					if ($mRowTK->RestaurantID>0)
					{
						redirect("signup.php");
					}
					else
					{
						$mRestaurantID = $_GET["sdid"];
					}
					
				}
			}
		}
	}
}
				


if ($mRestaurantID>0)
{
	$mSQL = "SELECT * FROM `signupdetails` WHERE ID=".$mRestaurantID;
	$mResult = dbAbstract::Execute($mSQL);
	if (dbAbstract::returnRowsCount($mResult)>0)
	{
		$mRestaurantDetails = dbAbstract::returnObject($mResult);
	}
}

function GetFileExt($pFileName)
{
	$mExt = substr($pFileName, strrpos($pFileName, '.'));
	$mExt = strtolower($mExt);
	return $mExt;
}

function fillTimeZones()
{
	$mSQL = dbAbstract::Execute("SELECT * FROM times_zones");
	while($mRes = dbAbstract::returnObject($mSQL))
	{
		echo "<option value=".$mRes->id.">".$mRes->time_zone."</option>";
	} 
}

function json_decode_ewo($json)
{
	$comment = false;
	$out = '$x=';
	for ($i=0; $i<strlen($json); $i++)
	{
		if (!$comment)
		{
			if (($json[$i] == '{') || ($json[$i] == '['))
				$out .= ' array(';
			else if (($json[$i] == '}') || ($json[$i] == ']'))
				$out .= ')';
			else if ($json[$i] == ':')
				$out .= '=>';
			else
				$out .= $json[$i];
		}
		else
			$out .= $json[$i];
		if ($json[$i] == '"' && $json[($i-1)]!="\\")
			$comment = !$comment;
	}
	eval($out . ';');
	return $x;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Easyway Ordering | SignUp</title>
<link rel="stylesheet" type="text/css" href="Styles/GlobalStyles.css" />
<link rel="stylesheet" type="text/css" href="Styles/font-awesome.css">
<link rel="stylesheet" type="text/css" href="Styles/menu.css">

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script src="//maps.googleapis.com/maps/api/js?key=<?=$google_api_key?>&sensor=false" type="text/javascript"></script>
<script type="text/javascript" src="Scripts/function.js"></script>
<script src="Scripts/modernizr.custom.js"></script>
<script src="js/mask.js" type="text/javascript"></script>
<script src="js/facebox.js" type="text/javascript"></script>
<link href="css/facebox.css" type="text/css" rel="stylesheet" media="screen">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="Styles/Accordian_style.css" />

<script type="text/javascript" src="Scripts/modernizr.custom.29473.js"></script>
<!--<script src="Scripts/jquery-1.9.0.min.js"></script>-->
<script src="Scripts/Misc.js"></script>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function($) 
	{
		$('#txtPhone').mask('(999) 999-9999');
		$('#txtFax').mask('(999) 999-9999');
		
		$('a[rel*=facebox]').facebox();		
		
		$(document).bind('close.facebox', function() 
		{
			$('#ac-3').prop('checked',true);
			return false;
     	});
	});
</script>

<script>
$(function(){
    $('#clickme').click(function(){
        $('#uploadme').click();
    });
    
    $('#uploadme').change(function(){
        $('#filename').val($(this).val());
    });
});
</script>
<link href="Styles/ProgressButton_style.css" rel="stylesheet" />
</head>

<body class="BodySignUpPage">
<div id="SignUpPagewrap">
	<div id="InnerPagewrap">
    	<div id="LoginDiv">
        	<a href="c_panel/login.php" class="Login">
            	<span class="LoginImg"><img src="images/LoginIcon.PNG" /></span>
            	<span class="LoginSpan">LOGIN</span>
            </a>
        </div>
    	<div id="SocialIconsDiv">
        	<div id="FacebookIcon"><a href="https://www.facebook.com/EasywayOrdering" target="_blank"><img src="images/FbIcon.png" /></a></div>
            <div id="TwitterIcon"><a href="http://www.twitter.com/EasyWayInc" target="_blank"><img src="images/TwitterIcon.png" /></a></div>
            <div id="GooglePlusIcon"><a href="https://plus.google.com/u/1/b/117596371910744353409/117596371910744353409" target="_blank"><img src="images/GooglePlusIcon.png" /></a></div>
            <div id="YoutbueIcon"><a href="http://www.youtube.com/user/EasyWayOrdering/" target="_blank"><img src="images/YoutubeIcon.png" /></a></div>
            <div id="InstagramIcon"><a href="http://instagram.com/easywayordering#" target="_blank"><img src="images/InstagramIcon.png" /></a></div>
            <div id="LinkedInIcon"><a href="http://www.linkedin.com/in/easywayordering/" target="_blank"><img src="images/LinkedInIcon.png" /></a></div>
        </div>
        <div class="clear"></div>
        <!--Responsive Menu Strats-->
        <div id="RespinsiveMenu">
            <div id="wrap">
                <header>
                    <div class="inner relative">
                        <a class="logo" href="<?php echo $SiteUrl;?>home.html"><img src="images/EasywayLogo.png" alt="Easyway Ordering"></a>
                        <a id="menu-toggle" class="button dark" href="#"><i style="color:#000;" class="icon-reorder"></i></a>
                        <nav id="navigation">
                            <ul id="main-menu">
                                <li><a href="Features.html">FEATURES</a></li>
                                <li><a href="Pricing.html">PRICING</a></li>
                                <li><a href="AboutUs.html">ABOUT</a></li>
                                <li><a href="Blog.html">BLOG</a></li>
                                <li><a href="Reseller.html" style="color:#282d30;">RESELLER?</a></li>
                                <li><a href="FreeDemo.html" style="color:#282d30;">FREE DEMO!</a></li>
                            </ul>
                        </nav>
                        <div class="clear"></div>
                    </div>
                </header>	
            </div>  
         </div>  
        <!-- REsponsive Menu Ends-->
        <div class="clear"></div>
        <div id="WhiteDivSignUp">
        	<div id="HomeImgDiv"><img src="images/House.png" /></div>
            <div id="ReadyToSetup">Ready to Set Up EasyWay Ordering?</div>
            <div id="FillInThe">Fill in the information below to get started today!</div>
			<form id="frmSearch" name="frmSearch" method="post" action="signup.php#SearchAgainDiv">
            <div id="SearchFormBigDiv">
            	<div id="RestaurantTextBoxDiv">
                	<input type="text" class="RestaurantTextBox" placeholder="Restaurant Name" id="txtRestaurant" name="txtRestaurant" maxlength="50"/>
                    <p class="TextBoxHeading">Your Restaurant’s Name</p>
                </div>
                <div id="CityZipTextBoxDiv">
                	<input type="text" class="CityZipTextBox" placeholder="City, State or Zip Code" id="txtCSZ" name="txtCSZ" maxlength="20"/>
                    <p class="TextBoxHeading">Location of Your Business</p>
                </div>
                <div id="CountrySelectDiv">
					<select class="CountrySelect CountrySelectImg" id="ddlCountry" name="ddlCountry">
						<option id="US">US</option>
						<option id="Canada">Canada</option>
						<option id="UK">UK</option>
					</select>
				</div>
            </div>
            <div id="SearchRestaurantError" style="display: none;">Please correct the errors highlighted in red.</div>
			<script type="text/javascript" language="javascript">
				$(document).ready(function() 
			   	{
					$("#btnSearch").click(function()
					{
						$("#txtRestaurant").css("border", "");
						$("#txtCSZ").css("border", "");						
						$("#SearchRestaurantError").hide();
						
						if ($.trim($("#txtRestaurant").val())=="")
						{
							$("#SearchRestaurantError").show();
							$("#txtRestaurant").focus();
							$("#txtRestaurant").css("border", "2px solid #FF0000");
							return false;
						}
						else
						{
							$("#SearchRestaurantError").hide();
							$("#txtRestaurant").css("border", "");
						}
						
						if ($.trim($("#txtCSZ").val())=="")
						{
							$("#SearchRestaurantError").show();
							$("#txtCSZ").focus();
							$("#txtCSZ").css("border", "2px solid #FF0000");
							return false;
						}
						else
						{
							$("#SearchRestaurantError").hide();
							$("#txtCSZ").css("border", "");
						}
					});
					

				});
				
				function FillDetails(pName, pPhone, pStreet_number, pStreet_name, pCity, pState, pCountry, pZip_code)
				{
					pName = pName.replace("|||", "'");
					pStreet_number = pStreet_number.replace("|||", "'");
					pStreet_name = pStreet_name.replace("|||", "'");
					$("#txtRestaurantName").val(pName);
					if ($.trim(pStreet_name)!="")
					{
						$("#txtStreetAddress").val(pStreet_number+", "+pStreet_name);
						$("#txtClientAddress").val(pStreet_number+", "+pStreet_name);
					}
					else
					{
						$("#txtStreetAddress").val(pStreet_number);
						$("#txtClientAddress").val(pStreet_number);
					}
					$("#txtState").val(pState);
					$("#txtCity").val(pCity);
					$("#txtZip").val(pZip_code);
					
					$("#txtClientState").val(pState);
					$("#txtClientCity").val(pCity);
					$("#txtClientZip").val(pZip_code);
					
					$("#txtPhone").val(pPhone);

					if (($.trim(pCountry.toLowerCase())=="us") || ($.trim(pCountry.toLowerCase())=="usa") || ($.trim(pCountry.toLowerCase())=="united states") || ($.trim(pCountry.toLowerCase())=="united states of america") || ($.trim(pCountry.toLowerCase())=="america"))
					{
			            $("#ddlTimeZone").children('option:contains("London")').hide();
						$("#ddlTimeZone").children('option:contains("Canada")').hide();
						$("#ddlTimeZone").children('option:contains("US")').show();
						
						$('select[name="ddlCountry1"]').find('option[value="US"]').attr("selected",true);
						$('select[name="ddlCountry2"]').find('option[value="US"]').attr("selected",true);
						
						$('#txtPhone').unmask();
						$('#txtFax').unmask();
						$('#txtPhone').mask('(999) 999-9999');
						$('#txtFax').mask('(999) 999-9999');
						
						$("#txtPhone").attr("placeholder","(555) 555-5555")
						$("#txtFax").attr("placeholder","(555) 555-5555")						
					}
					else if (($.trim(pCountry.toLowerCase())=="uk") || ($.trim(pCountry.toLowerCase())=="gb") || ($.trim(pCountry.toLowerCase())=="united kingdom") || ($.trim(pCountry.toLowerCase())=="england"))
					{
			            $("#ddlTimeZone").children('option:contains("London")').show();
						$("#ddlTimeZone").children('option:contains("Canada")').hide();
						$("#ddlTimeZone").children('option:contains("US")').hide();
						
 						$('select[name="ddlCountry1"]').find('option[value="UK"]').attr("selected",true);
 						$('select[name="ddlCountry2"]').find('option[value="UK"]').attr("selected",true);
						
						$('#txtPhone').unmask();
						$('#txtFax').unmask();
					
						$('#txtPhone').mask('(9999) 999-9999');
						$('#txtFax').mask('(9999) 999-9999');
						
						$("#txtPhone").attr("placeholder","(5555) 555-5555")
						$("#txtFax").attr("placeholder","(5555) 555-5555")	
					}
					else if (($.trim(pCountry.toLowerCase())=="canada") || ($.trim(pCountry.toLowerCase())=="ca"))
					{
			            $("#ddlTimeZone").children('option:contains("London")').hide();
						$("#ddlTimeZone").children('option:contains("Canada")').show();
						$("#ddlTimeZone").children('option:contains("US")').hide();
					
						$('select[name="ddlCountry1"]').find('option[value="Canada"]').attr("selected",true);
						$('select[name="ddlCountry2"]').find('option[value="Canada"]').attr("selected",true);

						$('#txtPhone').unmask();
						$('#txtFax').unmask();
						$('#txtPhone').mask('(999) 999-9999');
						$('#txtFax').mask('(999) 999-9999');
						
						$("#txtPhone").attr("placeholder","(555) 555-5555")
						$("#txtFax").attr("placeholder","(555) 555-5555")
						
						$("#pStateProvince").text("Province");
						$("#pZipPostal").text("Postal Code");
					}
					$("select#ddlTimeZone")[0].selectedIndex = 0;
					$("#ddlTimeZone").val(-1);
			  		//$('#lnkMap').attr('href', 'tab_restaurant_delivery_zones.php?address=' + $("#txtStreetAddress").val().replace(" ", "%20").replace(",", "%2C").replace("'", "%27").replace("(", "%28").replace(")", "%29").replace(".", "%2E").replace("/", "%2F").replace("'", "%23").replace("'", "%22").replace("'", "%26").replace("*", "%2A").replace("+", "%2B") + '&city=' + $("#txtCity").val().replace(" ", "%20").replace(",", "%2C").replace("'", "%27").replace("(", "%28").replace(")", "%29").replace(".", "%2E").replace("/", "%2F").replace("'", "%23").replace("'", "%22").replace("'", "%26").replace("*", "%2A").replace("+", "%2B") + '&state=' + $("#txtState").val().replace(" ", "%20").replace(",", "%2C").replace("'", "%27").replace("(", "%28").replace(")", "%29").replace(".", "%2E").replace("/", "%2F").replace("'", "%23").replace("'", "%22").replace("'", "%26").replace("*", "%2A").replace("+", "%2B"));
					$('#ac-1').prop('checked',true);
				}
				
				function changeTimesZones()
				{
					if ($.trim($("#ddlCountry1").val().toLowerCase())=="us")
					{
						$("#ddlTimeZone").children('option:contains("London")').hide();
						$("#ddlTimeZone").children('option:contains("Canada")').hide();
						$("#ddlTimeZone").children('option:contains("US")').show();
					}
					else if ($.trim($("#ddlCountry1").val().toLowerCase())=="uk")
					{
						$("#ddlTimeZone").children('option:contains("London")').show();
						$("#ddlTimeZone").children('option:contains("Canada")').hide();
						$("#ddlTimeZone").children('option:contains("US")').hide();
					}
					else if ($.trim($("#ddlCountry1").val().toLowerCase())=="canada")
					{
						$("#ddlTimeZone").children('option:contains("London")').hide();
						$("#ddlTimeZone").children('option:contains("Canada")').show();
						$("#ddlTimeZone").children('option:contains("US")').hide();
					}
					$("select#ddlTimeZone")[0].selectedIndex = 0;
				}
			</script>
            <div id="SearchAgainDiv" name="SearchAgainDiv"><input id="btnSearch" name="btnSearch" type="submit" class="SearchAgain" value="Find My Restaurant" /></div>
			<!--This Div is to Hide and Show-->
            <div id="SelectYourBusinessBigDiv" <?=$mResultDiv?>>
            	<div id="SelectYouBusinessHeading">Select Your Business to Get Set Up Faster</div>
                <div id="DontSeeHeading">Don’t See Your Business?<span><a href="#dvMore" onclick="$('#ac-1').prop('checked',true);" style="cursor: hand; cursor: pointer;" class="SpanClickTo"> Click to Continue.</a></span></div>
                <div class="clear"></div>
                <div id="SearchResultsDiv">
                	<div class="ResultsDiv" <?=$mRestDet[0]["DivShowHide"]?>>
                    	
                    	<div class="ResultImg"><img src="<?=$mRestDet[0]["Image"]?>" style="width: 180px; height: 150px;" /></div>
                        <div class="RestaurantName" style="width: 220px !important;">
                        	<?=$mRestDet[0]["Name"]?>
                        </div>
                        <div class="RestaurantAddress" style="width: 160px !important;"><?=$mRestDet[0]["Address"]?><br  /><br  /><?=$mRestDet[0]["Phone"]?></div>
                        <div class="ThisIsMeDiv"><a href="#dvMore" class="ThisIsMe" onclick="FillDetails('<?=str_replace("'", "|||", $mRestDet[0]["Name"])?>', '<?=$mRestDet[0]["Phone"]?>', '<?=str_replace("'", "|||", $mRestDet[0]['street_number'])?>', '<?=str_replace("'", "|||", $mRestDet[0]['street_name'])?>', '<?=$mRestDet[0]['city']?>', '<?=$mRestDet[0]['state']?>', '<?=$mRestDet[0]['country']?>', '<?=$mRestDet[0]['zip_code']?>');">This Is Me!</a></div>
                    	<div class="HrDiv"></div>
						
                    </div>
                    
                    <div class="ResultsDiv" <?=$mRestDet[1]["DivShowHide"]?>>
                    	<div class="ResultImg"><img src="<?=$mRestDet[1]["Image"]?>" style="width: 180px; height: 150px;" /></div>
                        <div class="RestaurantName" style="width: 220px !important;">
                        	<?=$mRestDet[1]["Name"]?>
                        </div>
                        <div class="RestaurantAddress" style="width: 160px !important;"><?=$mRestDet[1]["Address"]?><br  /><br  /><?=$mRestDet[1]["Phone"]?></div>
                        <div class="ThisIsMeDiv"><a href="#dvMore" class="ThisIsMe" onclick="FillDetails('<?=str_replace("'", "|||", $mRestDet[1]["Name"])?>', '<?=$mRestDet[1]["Phone"]?>', '<?=str_replace("'", "|||", $mRestDet[1]['street_number'])?>', '<?=str_replace("'", "|||", $mRestDet[1]['street_name'])?>', '<?=$mRestDet[1]['city']?>', '<?=$mRestDet[1]['state']?>', '<?=$mRestDet[1]['country']?>', '<?=$mRestDet[1]['zip_code']?>');">This Is Me!</a></div>
                        <div class="HrDiv"></div>
                    </div>
                    
                    <div class="ResultsDiv" <?=$mRestDet[2]["DivShowHide"]?>>
                    	<div class="ResultImg"><img src="<?=$mRestDet[2]["Image"]?>" style="width: 180px; height: 150px;" /></div>
                        <div class="RestaurantName" style="width: 220px !important;">
                        	<?=$mRestDet[2]["Name"]?>
                        </div>
                        <div class="RestaurantAddress" style="width: 160px !important;"><?=$mRestDet[2]["Address"]?><br  /><br  /><?=$mRestDet[2]["Phone"]?></div>
                        <div class="ThisIsMeDiv"><a href="#dvMore" class="ThisIsMe" onclick="FillDetails('<?=str_replace("'", "|||", $mRestDet[2]["Name"])?>', '<?=$mRestDet[2]["Phone"]?>', '<?=str_replace("'", "|||", $mRestDet[2]['street_number'])?>', '<?=str_replace("'", "|||", $mRestDet[2]['street_name'])?>', '<?=$mRestDet[2]['city']?>', '<?=$mRestDet[2]['state']?>', '<?=$mRestDet[2]['country']?>', '<?=$mRestDet[2]['zip_code']?>');">This Is Me!</a></div>
                        <div class="HrDiv"></div>
                    </div>
                    
                </div>
                <div class="clear"></div>
                <div class="ProgressBarDiv" <?=$mdvTwenty?>>
                    <div id="progress">
                        <span id="percent">20%</span>
                        <div id="bar"></div>
                    </div>
            	</div>
            </div>
			</form>
            <!--Till Here-->
			<form enctype="multipart/form-data" id="frmMain" name="frmMain" method="post" action="">
			<input type="hidden" id="txtRestaurantID" name="txtRestaurantID" value="<?=$mRestaurantID?>" />
			<input type="hidden" id="txtStep" name="txtStep" value="1" />
			
            <div id="AccordianBigDiv">
            
            	<section class="ac-container">
				<div id="dvMore" name="dvMore">
					<a name="signupstarted" id="signupstarted"></a>
					<input id="ac-1" name="accordion-1" type="radio" />
					<label for="ac-1">Tell Us More About Your Business</label>
					<article class="ac-small">
					  <div class="AccordianHeading">Tell Us a Little About Your Business:</div>
                      <div class="FormRaw">
                      	<div class="AccRestaurantNameTextBoxDiv">
                        	<input type="text" class="AccRestaurantNameTextBox" placeholder="Joe’s Diner"  id="txtRestaurantName" name="txtRestaurantName" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->RestaurantName:'')?>" maxlength="50"/>
                            <p class="TextBoxHeading">Restaurant Name</p>
                        </div>
                        <div class="AccCityZipTextBoxDiv">
                        	<input type="text" class="AccCityZipTextBox" placeholder="70 N 6th St"  id="txtStreetAddress" name="txtStreetAddress" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->Address:'')?>" maxlength="60"/>
                            <p class="TextBoxHeading">Address</p>
                        </div>
                        <div class="AccCountrySelectDiv">
                        	<input type="text" style="width:150px !Important;" class="AccCityZipTextBox" id="txtState" name="txtState" placeholder="NY" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->State:'')?>" maxlength="15"/>
                            <p class="TextBoxHeading" id="pStateProvince">State</p>
                        </div>
                      </div>
                      
                      <div class="FormRaw">
                      	<div class="AccCityZipTextBoxDiv" style="margin-left: 0;">
                        	<input type="text" class="AccCityZipTextBox" placeholder="Brooklyn" id="txtCity" name="txtCity" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->City:'')?>"   maxlength="25"/>
                            <p class="TextBoxHeading">City</p>
                        </div>
                        <div class="AccCityZipTextBoxDiv" style="margin-left:70px;">
                        	<input type="text" class="AccCityZipTextBox" placeholder="11223" style="width:170px;" id="txtZip" name="txtZip" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->ZipCode:'')?>"  maxlength="15"/>
                            <p class="TextBoxHeading" id="pZipPostal">Zip Code</p>
                        </div>
                        <div class="AccCountrySelectDiv">
						<?php
							$mCountry1 = "";
							$mCountry2 = "";
							$mCountry3 = "";
							
							if (isset($mRestaurantDetails))
							{
								if (trim(strtolower($mRestaurantDetails->Country))=="us")
								{
									$mCountry1 = " selected='selected' ";
								}
								else if (trim(strtolower($mRestaurantDetails->Country))=="canada")
								{
									$mCountry2 = " selected='selected' ";
								}
								else if (trim(strtolower($mRestaurantDetails->Country))=="uk")
								{
									$mCountry3 = " selected='selected' ";
								}
								else
								{
									$mCountry1 = " selected='selected' ";
								}
							}
							else
							{
								$mCountry1 = " selected='selected' ";
							}
						?>
							<select class="CountrySelect CountrySelectImg" id="ddlCountry1" name="ddlCountry1" onchange="changeTimesZones();">
								<option id="US" value="US" <?=$mCountry1?>>US</option>
								<option id="Canada" value="Canada" <?=$mCountry2?>>Canada</option>
								<option id="UK" value="UK" <?=$mCountry3?>>UK</option>
							</select>
                            <p class="TextBoxHeading">Country</p>
                        </div>
                      </div>
                      
                      <div class="FormRaw">
                      	<div class="AccCityZipTextBoxDiv" style="margin-left: 0;">
                        	<input type="text" class="AccCityZipTextBox" placeholder="(718) 782-3334" id="txtPhone" name="txtPhone" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->PhoneNumber:'')?>"  maxlength="20"/>
                            <p class="TextBoxHeading">Phone Number</p>
                        </div>
                        <div class="AccCityZipTextBoxDiv" style="margin-left: 70px;">
                        	<input type="text" class="AccCityZipTextBox" placeholder="(555) 555-5555" id="txtFax" name="txtFax" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->FaxNumber:'')?>"   maxlength="20"/>
                            <p class="TextBoxHeading">Fax Number</p>
                        </div>
                        <div class="AccCountrySelectDiv">
                        	<select class="CountrySelect CountrySelectImg" id="ddlTimeZone" name="ddlTimeZone">
								<option value="-1"></option>
								<?=@fillTimeZones();?>
							</select>
								<?php
									if (isset($mRestaurantDetails))
									{
										if (trim(strtolower($mRestaurantDetails->Country))=="us")
										{
											echo("<script language='javascript' type='text/javascript'>
												$('#ddlTimeZone').children('option:contains(\"London\")').hide();
												$('#ddlTimeZone').children('option:contains(\"Canada\")').hide();
												$('#ddlTimeZone').children('option:contains(\"US\")').show();
												</script>");
										}
										else if (trim(strtolower($mRestaurantDetails->Country))=="canada")
										{
											echo("<script language='javascript' type='text/javascript'>
												$('#ddlTimeZone').children('option:contains(\"London\")').hide();
												$('#ddlTimeZone').children('option:contains(\"Canada\")').show();
												$('#ddlTimeZone').children('option:contains(\"US\")').hide();
												</script>");
										}
										else if (trim(strtolower($mRestaurantDetails->Country))=="uk")
										{
											echo("<script language='javascript' type='text/javascript'>
												$('#ddlTimeZone').children('option:contains(\"London\")').show();
												$('#ddlTimeZone').children('option:contains(\"Canada\")').hide();
												$('#ddlTimeZone').children('option:contains(\"US\")').hide();
												</script>");
										}
										else
										{
											echo("<script language='javascript' type='text/javascript'>
												$('#ddlTimeZone').children('option:contains(\"London\")').hide();
												$('#ddlTimeZone').children('option:contains(\"Canada\")').hide();
												$('#ddlTimeZone').children('option:contains(\"US\")').show();
												</script>");
										}
										
										echo("<script language='javascript' type='text/javascript'>
											$('#ddlTimeZone').val('".$mRestaurantDetails->TimeZoneID."');
											</script>");
									}
									else
									{
										echo("<script language='javascript' type='text/javascript'>
											$('#ddlTimeZone').children('option:contains(\"London\")').hide();
											$('#ddlTimeZone').children('option:contains(\"Canada\")').hide();
											$('#ddlTimeZone').children('option:contains(\"US\")').show();
											</script>");
									}
									
								?>
                            <p class="TextBoxHeading">Select Time Zone</p>
                        </div>
                      </div>
                      <div class="clear"></div>
		              <div id="RestaurantInputBasicError" class="SearchRestaurantError" style="display: none;">Please correct the errors highlighted in red.</div>
                      <div class="SubmitBigDiv" style="margin-left: 320px !important;">
                      	<div class="AccSubmitDiv" style="margin-top: -18px;"><input type="button" value="Save" class="Save" style="display:block;" id="btnSave1" name="btnSave1"/></div>
                        <div class="AccSubmitDiv" style="display: none;"><a href="#" class="Next">Back</a></div>
                        <div class="AccSubmitDiv" style="margin:0 30px;"><a href="#" class="Next" id="Second_Tab">Next</a></div>
                      </div>
                      
                      <div class="ProgressBarDiv" style="padding: 50px 0;">
                          <div id="progress">
                              <span id="percent" style="left: 15%;">30%</span>
                              <div id="bar" style="width: 40%;"></div>
                          </div>
            		  </div>
					</article>
				</div>
				<div>
					<input id="ac-2" name="accordion-1" type="radio" />
					<label for="ac-2">Create Your Account</label>
					<article class="ac-medium">
						<div class="AccordianHeading">Create Your Account:</div>
                        <div class="HadingPara">You will use this account to access your restaurant control panel. From there, you can manage all of your business locations with ease.</div>  
                        <div class="FormRaw">
                            <div class="AccRestaurantNameTextBoxDiv">
                                <input type="text" class="AccRestaurantNameTextBox" placeholder="Full Name" id="txtFullName" name="txtFullName" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->FullName:'')?>"  maxlength="50"/>
                                <p class="TextBoxHeading">Your Full Name</p>
                            </div>
                            <div class="AccCityZipTextBoxDiv">
                                <input type="text" class="AccCityZipTextBox" placeholder="email@address.com" id="txtEmailAddress" name="txtEmailAddress" style="width:350px !important;" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->EmailAddress:'')?>"  maxlength="100"/>
                                <p class="TextBoxHeading">Email</p>
                            </div>
                     	</div>
                        
                        <div class="FormRaw">
                            <div class="AccCityZipTextBoxDiv" style="margin-left: 0;">
                                <input type="password" class="AccCityZipTextBox" placeholder="******" id="txtPassword" name="txtPassword" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->Password:'')?>"  maxlength="15"/>
                                <p class="TextBoxHeading">Choose Password</p>
                            </div>
                            <div class="AccCityZipTextBoxDiv" style="margin-left: 70px;">
                                <input type="password" class="AccCityZipTextBox" placeholder="******" id="txtCPassword" name="txtCPassword" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->Password:'')?>"  maxlength="15"/>
                                <p class="TextBoxHeading">Confirm Password</p>
                            </div>
                      </div>
                      
                      <div class="FormRaw">
                            <div class="AccCityZipTextBoxDiv" style="margin-left: 0;">
                                <input type="text" class="AccCityZipTextBox" placeholder="Username" id="txtUserName" name="txtUserName" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->UserName:'')?>"  maxlength="15"/>
                                <p class="TextBoxHeading">Choose Username</p>
                            </div>
                      </div>
                      
                      <div class="clear"></div>
  		              <div id="RestaurantInputAccountError" class="SearchRestaurantError" style="display: none;"></div>
                      <div class="SubmitBigDiv">
                      	<div class="AccSubmitDiv" style="margin-top: -18px;"><input type="button" value="Save" class="Save" style="display:block;" id="btnSave2" name="btnSave2"/></div>
                        <div class="AccSubmitDiv" style="margin:0 30px;"><a href="#" class="Next" id="Second_Back_Tab">Back</a></div>
                        <div class="AccSubmitDiv"><a href="#" class="Next" id="Third_Tab">Next</a></div>
                      </div>
                      
                      <div class="ProgressBarDiv" style="padding: 50px 0;">
                          <div id="progress">
                              <span id="percent" style="left: 20%;">50%</span>
                              <div id="bar" style="width: 50%;"></div>
                          </div>
            		  </div>
					</article>
				</div>
				<div>
					<input id="ac-3" name="accordion-1" type="radio" />
					<label for="ac-3">Add Your Menus and Select Order Preference</label>
					<article class="ac-large">
						<div class="AccordianHeading">How Would You Like To Receive Orders?</div>
                        <div class="HadingPara">Here, you can input order related settings like order destination, delivery method, order minimum cost 
and the area in which restaurant provides delivery.</div>   
						<div class="FormRaw">
                            <div class="AccCityZipTextBoxDiv" style="margin-left: 0;">
							<?php
								$mrbOrders1	= "";
								$mrbOrders2 = "";
								$mrbOrders3 = "";
								
								if (isset($mRestaurantDetails))
								{
									if ($mRestaurantDetails->OrderReceive=="1")
									{
										$mrbOrders1 = " checked='checked' ";
									}
									else if ($mRestaurantDetails->OrderReceive=="2")
									{
										$mrbOrders2 = " checked='checked' ";
									}
									else if ($mRestaurantDetails->OrderReceive=="3")
									{
										$mrbOrders3 = " checked='checked' ";
									}
									else
									{
										$mrbOrders1 = " checked='checked' ";
									}
								}
								else
								{
									$mrbOrders1 = " checked='checked' ";
								}
							?>
                                <input type="radio" class="RadioBox" name="rbOrders" id="rbOrders1" <?=$mrbOrders1?> value="1" /><span class="RadionImgIcon"><img src="images/FaxIcon.png" /></span>
                                <p class="TextBoxHeading">Via Fax</p>
                            </div>
                            <div class="AccCityZipTextBoxDiv" style="margin-left:0px;">
                                <input type="radio" class="RadioBox" name="rbOrders" id="rbOrders2" <?=$mrbOrders2?> value="2"/><span class="RadionImgIcon"><img src="images/EmailIcon.png" /></span>
                                <p class="TextBoxHeading">Via Email</p>
                            </div>
                            <div class="AccCountrySelectDiv">
                                <input type="radio" class="RadioBox" name="rbOrders" id="rbOrders3" <?=$mrbOrders3?> value="3"/><span class="RadionImgIcon"><img src="images/POSIcon.png" /></span>
                                <p class="TextBoxHeading">Via POS</p>
                            </div>
                      </div>
                      
                      <div class="FormRaw">
                      	<div class="AccCityZipTextBoxDiv" style="margin-left: 0;width: 400px;">
                                <input type="text" class="AccCityZipTextBox" placeholder="8.0" name="txtTax" id="txtTax" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->Tax:'')?>"  maxlength="5"/>
                                <p class="TextBoxHeading">Please Enter Any Sales Tax (%) You Wish to Charge</p>
                            </div>
                      </div>
					  <a name="hrefUplaod" id="hrefUplaod"></a>
                      <?php
							$mrbDelivery1 = "";
							$mrbDelivery2 = "";
							
							if (isset($mRestaurantDetails))
							{
								if ($mRestaurantDetails->Delivery=="1")
								{
									$mrbDelivery1 = " checked='checked' ";
								}
								else if ($mRestaurantDetails->Delivery=="2")
								{
									$mrbDelivery2 = " checked='checked' ";
								}
								else
								{
									$mrbDelivery1 = " checked='checked' ";
								}
							}
							else
							{
								$mrbDelivery1 = " checked='checked' ";
							}
						?>
                      <div class="FormRaw">
					  		<script type="text/javascript" language="javascript">
								$(document).ready(function() 
			   					{
									$("#rbDelivery1").click(function()
									{
										if($(this).is(':checked'))
										{
											$("#dvDelivery").show();
										}
									});
								
									$("#rbDelivery2").click(function()
									{
										if($(this).is(':checked'))
										{
											$("#dvDelivery").hide();
											$("#txtDeliveryMinimum").val("");
											$("#txtDeliveryCharges").val("");
											$("#txtDeliveryRadius").val("");
										}
									});
								});
							
							</script>
                            <div class="AccCityZipTextBoxDiv" style="margin-left: 0;">
                                <input type="radio" <?=$mrbDelivery1?> class="RadioBox" style="margin-top: 0;" id="rbDelivery1" name="rbDelivery" value="1" /><span class="RadionImgIcon">Yes</span>
                                <p class="TextBoxHeading">Will You Offer Delivery?</p>
                            </div>
                            <div class="AccCityZipTextBoxDiv" style="margin-left:0px;">
                                <input type="radio" <?=$mrbDelivery2?> class="RadioBox" style="margin-top: 0;" id="rbDelivery2" name="rbDelivery" value="2"/><span class="RadionImgIcon">No</span>
                            </div>
                      </div>
					  
					  <div style="display: inline;" id="dvDelivery">
						  <div class="AccRestaurantNameTextBoxDiv" style="width: 280px !important; margin-left: 15px !important;">
							<input style="width: 150px;" type="text" class="AccCityZipTextBox" placeholder="5" id="txtDeliveryMinimum" name="txtDeliveryMinimum" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->DeliveryMinimum:'')?>"  maxlength="3"/>
							<p class="TextBoxHeading">Delivery Minimum</p>
						  </div>
						  <div class="AccCityZipTextBoxDiv">
							<input style="width: 150px;" type="text" class="AccCityZipTextBox" placeholder="5" id="txtDeliveryCharges" name="txtDeliveryCharges" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->DeliveryCharges:'')?>"  maxlength="2"/>
							<p class="TextBoxHeading">Delivery Charges (if any)</p>
						  </div>
						  <div class="AccCountrySelectDiv">
							<input style="width: 150px;" type="text" class="AccCityZipTextBox" placeholder="5" id="txtDeliveryRadius" name="txtDeliveryRadius" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->DeliveryRadius:'')?>"  maxlength="3"/>
							  <p class="TextBoxHeading">Delivery Radius</p>
							  <script type="text/javascript" language="javascript">
							  	$(document).ready(function() 
			   					{
							  		//$('#lnkMap').attr('href', 'tab_restaurant_delivery_zones.php?address=' + $("#txtStreetAddress").val() + '&city=' + $("#txtCity").val() + '&state=' + $("#txtState").val());
//									$('#lnkMap').attr('href', 'tab_restaurant_delivery_zones.php?address=505%2C%20Grand%20St&state=ny&city=new%20york');
									$("#lnkMap").click(function()
									{
										if (($.trim($("#txtStreetAddress").val())!="") && ($.trim($("#txtCity").val())!="") && ($.trim($("#txtState").val())!=""))
										{
											$.facebox({div: 'tab_restaurant_delivery_zones.php?address=' + $("#txtStreetAddress").val().replace(" ", "%20").replace(",", "%2C").replace("'", "%27").replace("(", "%28").replace(")", "%29").replace(".", "%2E").replace("/", "%2F").replace("'", "%23").replace("'", "%22").replace("'", "%26").replace("*", "%2A").replace("+", "%2B") + '&city=' + $("#txtCity").val().replace(" ", "%20").replace(",", "%2C").replace("'", "%27").replace("(", "%28").replace(")", "%29").replace(".", "%2E").replace("/", "%2F").replace("'", "%23").replace("'", "%22").replace("'", "%26").replace("*", "%2A").replace("+", "%2B") + '&state=' + $("#txtState").val().replace(" ", "%20").replace(",", "%2C").replace("'", "%27").replace("(", "%28").replace(")", "%29").replace(".", "%2E").replace("/", "%2F").replace("'", "%23").replace("'", "%22").replace("'", "%26").replace("*", "%2A").replace("+", "%2B")});  
											$('#facebox').css("position","absolute");
											$('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) + "px");
											$('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
										}
										else
										{
											alert("Please enter Street address, City and State first.");
										}
									});
								});
							  </script>
							  <p><a style="color: #25aae1; cursor: hand; cursor: pointer;" id="lnkMap">Custom Delivery Zone?</a></p> 
						  </div>
					</div>	  	
					  <div style="clear: both;"></div>
                      <div class="AccordianHeading" style="margin-top:50px;">Your Menus</div>
                      <div class="HadingPara">Restaurant menus can be uploaded in this step. You can provide menu name, menu files etc
Don’t have a digital copy? <span style="color:#25aae1;">Print our fax cover sheet</span> and fax your menu to: (877) 211-0213</div>
						<!-- Menus Upload Start -->:
							<script type="text/javascript" language="javascript">
								$(document).ready(function() 
			   					{
									$("#btnUpload").click(function()
									{
										if ($.trim($("#txtMenuName").val())=="")
										{
											$("#RestaurantInputMenuError").text("Please correct the errors highlighted in red.");
											$("#RestaurantInputMenuError").show();
											$("#txtMenuName").focus();
											$("#txtMenuName").css("border", "2px solid #FF0000");
											return false;
										}
										else
										{
											$("#txtMenuName").css("border", "");
											if ($.trim($("#uploadme").val())=="")
											{
												$("#RestaurantInputMenuError").text("Please correct the errors highlighted in red.");
												$("#RestaurantInputMenuError").show();
												$("#filename").css("border", "2px solid #FF0000");
												return false;
											}
											else
											{
												$("#RestaurantInputAccountError").hide();
												$("#RestaurantInputMenuError").hide();
												$("#uploadme").css("border", "");
												$("#frmMain").attr("action", "signup.php?flag=upload#hrefUplaod");
												return true;
											}
										}
									});
								});
							</script>
							<div class="FormRaw">
								<div class="AccCityZipTextBoxDiv" style="margin-left: 0;">
									<input type="text" class="AccCityZipTextBox" placeholder="Dinner Menu" id="txtMenuName" name="txtMenuName"  maxlength="20"/>
									<p class="TextBoxHeading">Name This Menu</p>
								</div>
							</div>
							<div class="FormRaw">
								<div class="AccCityZipTextBoxDiv" style="margin-left: 0; width: 520px !Important;">
									<input type="text" id="filename" class="FileUploadText" placeholder="no file selected"/>
									<input type="file"  id="uploadme" name="uploadme" />
									<input type="button" id="clickme" value="Choose File" class="FileUploadButton" />&nbsp;<input type="submit" id="btnUpload" name="btnUpload" value="Upload" class="FileUploadButton" />
									<div class="clear"></div>
									<p class="TextBoxHeading">Upload Your Menu</p>
								</div>
							</div>
							<div id="UploadedFilesDiv">	
								<input type="hidden" id="txtDeletedFiles" name="txtDeletedFiles" value=""/>
								<script type="text/javascript" language="javascript">
									$(document).ready(function() 
			   						{
										$(".clsRemove").click(function(e)
										{
											mID = $(this).attr("ID");
											mLiID = "#li"+$(this).attr("ID");
											$(mLiID).hide();
											$("#txtDeletedFiles").val($("#txtDeletedFiles").val()+mID+",");
										});
									});
								</script>
								<ul class="FileAdded">Files Added
									<li style="visibility: hidden;"></li>
									<?php 
										if (isset($mRestaurantDetails))
										{
											$mID = $mRestaurantDetails->ID;
											$mSQL = "SELECT * FROM `signupuploads` WHERE RestaurantID=".$mID;
											$mResult = dbAbstract::Execute($mSQL);
											if (dbAbstract::returnRowsCount($mResult)>0)
											{
												while ($mRow = dbAbstract::returnObject($mResult))
												{
									?>
									<li class='UploadedMenu' id='li<?=$mRow->ID?>'>-<span class='UploadedMenuMargin'><?=$mRow->MenuName?></span>&nbsp;&nbsp;<span style='color: #933; cursor: hand; cursor: pointer;' class='clsRemove' ID='<?=$mRow->ID?>'>Remove</span></li>
									<?php
												}
											}
										}
									?>
								</ul>
							</div>
						<!-- Mennus Upload End -->
                      
                      <div class="clear"></div>
   		              <div id="RestaurantInputMenuError" class="SearchRestaurantError" <?=$mDupShowHide?>><?=$mDupError?></div>
                      <div class="SubmitBigDiv">
                      	<div class="AccSubmitDiv" style="margin-top: -18px;"><input type="button" value="Save" class="Save" style="display:block;" id="btnSave3" name="btnSave3"/></div>
                        <div class="AccSubmitDiv" style="margin:0 30px;"><a href="#" class="Next" id="Third_Back_Tab">Back</a></div>
                        <div class="AccSubmitDiv"><a href="#" class="Next" id="Fourth_Tab">Next</a></div>
                      </div>
                      
                      <div class="ProgressBarDiv" style="padding: 50px 0;">
                          <div id="progress">
                              <span id="percent" style="left: 35%;">80%</span>
                              <div id="bar" style="width: 80%;"></div>
                          </div>
            		  </div>
					</article>
				</div>
				<div>
					<input id="ac-4" name="accordion-1" type="radio" />
					<label for="ac-4">Submit Payment Information</label>
					<article class="ac-large">
						<div class="AccordianHeading">Payment Information:</div> 
                        <div class="clear"></div>
                        <div id="PaymentInformationBigDiv">
                        	<div id="PaymentInfoLeftDiv">
							<?php
								$mrbCash1 = "";
								$mrbCash2 = "";
								
								if (isset($mRestaurantDetails))
								{
									if ($mRestaurantDetails->Cash=="1")
									{
										$mrbCash1 = " checked='checked' ";
									}
									else if ($mRestaurantDetails->Cash=="2")
									{
										$mrbCash2 = " checked='checked' ";
									}
									else
									{
										$mrbCash1 = " checked='checked' ";
									}
								}
								else
								{
									$mrbCash1 = " checked='checked' ";
								}
							?>
                            	<div id="WillCustomerDiv">
                                	<span class="SpanWillCustomer">Will customers be able to pay via cash?</span>
                                    <span class="SpanDollarImgIcon"><img src="images/DollarIcon.png" /></span>
                                </div>
                                <div class="RadioDiv">
                                	<table>
                                    	<tr><td><input type="radio" style="display:block" id="rbYesC" value="1" name="rbCash" <?=$mrbCash1?> /></td><td class="PaddingLeft"><input type="radio" style="display:block" <?=$mrbCash2?> id="rbNoC" value="2" name="rbCash"/></td></tr>
                                       	<tr><td class="Yes">Yes</td><td class="Yes PaddingLeft">No</td></tr>
                                    </table>
                                </div>
                            </div>
							<?php
								$mrbCC1 = "";
								$mrbCC2 = "";
								
								if (isset($mRestaurantDetails))
								{
									if ($mRestaurantDetails->CreditCard=="1")
									{
										$mrbCC1 = " checked='checked' ";
									}
									else if ($mRestaurantDetails->CreditCard=="2")
									{
										$mrbCC2 = " checked='checked' ";
									}
									else
									{
										$mrbCC1 = " checked='checked' ";
									}
								}
								else
								{
									$mrbCC1 = " checked='checked' ";
								}
							?>
                            <div id="PaymentInfoRightDiv">
                            	<div id="WillCustomerRightDiv">
                                	<span class="SpanWillCustomer">Will customers be able to pay via credit card?</span>
                                    <span class="SpanDollarImgIcon"><img src="images/VisaIcon.png" /></span>
                                </div>
                                <div class="RadioDiv">
                                	<table>
                                    	<tr><td><input type="radio" style="display:block" id="rbYesCC" value="1" name="rbCreditCard" <?=$mrbCC1?> /></td><td class="PaddingLeft"><input type="radio" style="display:block" id="rbNoCC" value="2" name="rbCreditCard" <?=$mrbCC2?>/></td></tr>
                                       	<tr><td class="Yes">Yes</td><td class="Yes PaddingLeft">No</td></tr>
                                    </table>
                                </div>
								<?php
									$rbGateWay1 = "";
									$rbGateWay2 = "";
									$rbGateWay3 = "";
									
									if (isset($mRestaurantDetails))
									{
										if ($mRestaurantDetails->GateWay=="1")
										{
											$rbGateWay1 = " checked='checked' ";
										}
										else if ($mRestaurantDetails->GateWay=="2")
										{
											$rbGateWay2 = " checked='checked' ";
										}
										else if ($mRestaurantDetails->GateWay=="3")
										{
											$rbGateWay3 = " checked='checked' ";
										}
										else
										{
											$rbGateWay1 = " checked='checked' ";
										}
									}
									else
									{
										$rbGateWay1 = " checked='checked' ";
									}
								?>
                                <div class="RadioBoxQuestionDiv">
                                	<span class="SpanRadioBoxQuestion"><input type="radio" style="display:block" id="rbGateWay1" name="rbGateWay" value="1" <?=$rbGateWay1?> /></span>
                                    <span class="SpanQuestion">I have a payment gateway for online transactions</span>
                                </div>
                                <div class="RadioBoxQuestionDiv">
                                	<span class="SpanRadioBoxQuestion"><input type="radio" style="display:block" id="rbGateWay2" name="rbGateWay" value="2" <?=$rbGateWay2?>/></span>
                                    <span class="SpanQuestion">I would like to set up a new e-commerce merchant account with an EasyWay certified partner.</span>
                                </div>
                                <div class="RadioBoxQuestionDiv">
                                	<span class="SpanRadioBoxQuestion"><input type="radio" style="display:block"  id="rbGateWay3" name="rbGateWay" value="3" <?=$rbGateWay3?>/></span>
                                    <span class="SpanQuestion">I plan to open an e-commerce merchant account on my own.</span>
                                </div>
                            </div>
                        </div> 
                        <div class="clear"></div>
                        <div class="AccordianHeading" style="margin-top:50px;">Your Website:</div>
                      <div class="HadingPara">Here is where you can enter all the details of your current or soon-to-be website!</div>
                       <div class="FormRaw">
                       		<div id="NameText">
                            	<span class="SpanWhatIsYour">What is Your Domain Name?</span>
                                <span class="SpanTextBox"><input type="text" class="CityZipTextBox" id="txtDomainName" name="txtDomainName"  maxlength="50" style="display:block;" placeholder="www.joespizza.com" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->DomainName:'')?>"/></span>
                            </div><div class="clear"></div>
							<?php
								$rbGateWay1 = "";
								$rbGateWay2 = "";
								$rbGateWay3 = "";
								
								if (isset($mRestaurantDetails))
								{
									if ($mRestaurantDetails->NewDomain=="1")
									{
										$rbNewDomain = " checked='checked' ";
									}
								}
								else
								{
									$rbNewDomain = "";
								}
							?>
                            <div id="IWouldLIkeDiv">
                            	<span class="SpanRadioBoxQuestion"><input type="checkbox" style="display:block" id="rbNewDomain" name="rbNewDomain" value="1" <?=$rbNewDomain?> /></span>
                                <span class="SpanQuestion" style="margin-top:0px;"><i>I’d like a new domain name</i></span>
                            </div>
                       </div>
                       <div class="FormRaw">
					   <?php
							$rbMenuUse1 = "";
							$rbMenuUse2 = "";
							$rbMenuUse3 = "";
							
							if (isset($mRestaurantDetails))
							{
								if ($mRestaurantDetails->MenuUse=="1")
								{
									$rbMenuUse1 = " checked='checked' ";
								}
								else if ($mRestaurantDetails->MenuUse=="2")
								{
									$rbMenuUse3 = " checked='checked' ";
								}
								else if ($mRestaurantDetails->MenuUse=="3")
								{
									$rbMenuUse3 = " checked='checked' ";
								}
								else
								{
									$rbMenuUse1 = " checked='checked' ";
								}
							}
							else
							{
								$rbMenuUse1 = " checked='checked' ";
							}
						?>
                       	<ul class="ULPara">How Would You Like to Use Your Menu?
                            <li class="LIPara">
                                <span class="SpanRadioBoxQuestion"><input type="radio" style="display:block" id="rbMenuUse1" name="rbMenuUse" value="1" <?=$rbMenuUse1?>/></span>
                                <span class="SpanQuestion" style="margin-top:0px; float:none;">Add EasyWay Ordering menu to existing website</span>
                            </li>
                            <li class="LIPara">
                                <span class="SpanRadioBoxQuestion"><input type="radio" style="display:block" id="rbMenuUse2" name="rbMenuUse" value="2" <?=$rbMenuUse2?> /></span>
                                <span class="SpanQuestion" style="margin-top:0px; float:none;">Use EasyWay Ordering menu as my website</span>
                            </li>
                            <li class="LIPara">
                                <span class="SpanRadioBoxQuestion"><input type="radio" style="display:block" id="rbMenuUse3" name="rbMenuUse" value="3" <?=$rbMenuUse3?> /></span>
                                <span class="SpanQuestion" style="margin-top:0px; float:none;">I would like a custome website built for me</span>
                            </li>
                        </ul>
                       </div>
                       <div class="FormRaw">
					   	 <?php
							$rbHosting1 = "";
							$rbHosting2 = "";
							$rbHosting3 = "";
							
							if (isset($mRestaurantDetails))
							{
								if ($mRestaurantDetails->HostingInformation=="1")
								{
									$rbHosting1 = " checked='checked' ";
								}
								else if ($mRestaurantDetails->HostingInformation=="2")
								{
									$rbHosting2 = " checked='checked' ";
								}
								else if ($mRestaurantDetails->HostingInformation=="3")
								{
									$rbHosting3 = " checked='checked' ";
								}
								else
								{
									$rbHosting1 = " checked='checked' ";
								}
							}
							else
							{
								$rbHosting1 = " checked='checked' ";
							}
						?>
                       	<ul class="ULPara">Provide Your Hosting Information:
                            <li class="LIPara">
                                <span class="SpanRadioBoxQuestion"><input type="radio" style="display:block" id="rbHosting1" name="rbHosting" value="1" <?=$rbHosting1?> /></span>
                                <span class="SpanQuestion" style="margin-top:0px; float:none;">I would like EasyWay to configure my hosting account and or integrate the ordering page with my website.</span>
                            </li>
                            <li class="LIPara">
                                <span class="SpanRadioBoxQuestion"><input type="radio" style="display:block" id="rbHosting2" name="rbHosting" value="2" <?=$rbHosting2?>/></span>
                                <span class="SpanQuestion" style="margin-top:0px; float:none;">My webmaster will make all necessary changes</span>
                            </li>
                            <li class="LIPara">
                                <span class="SpanRadioBoxQuestion"><input type="radio" style="display:block" id="rbHosting3" name="rbHosting" value="3" <?=$rbHosting3?>/></span>
                                <span class="SpanQuestion" style="margin-top:0px; float:none;">I will make all necessary changes</span>
                            </li>
                        </ul>
                       </div>
                       <div class="clear"></div>
					   <div class="FormRaw">
					   		<p class="ULPara">Provide Your Credit Card Information:</p>
							<div class="AccRestaurantNameTextBoxDiv" style="width: 280px !important; margin-left: 15px !important;">
							<input style="width: 290px;" type="text" class="AccCityZipTextBox" placeholder="4111111111111111111"  maxlength="20" id="txtCreditCardNumber" name="txtCreditCardNumber" />
							<p class="TextBoxHeading">Credit Card Number</p>
						  </div>
						  <div class="AccCityZipTextBoxDiv">
							<select id="ddlExpMonth" name="ddlExpMonth" class="CountrySelect CountrySelectImg">
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>																
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>																
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>																																
							</select>
							<p class="TextBoxHeading">Expiry Month</p>
						  </div>
						  <div class="AccCountrySelectDiv">
							<select id="ddlExpYear" name="ddlExpYear" class="CountrySelect CountrySelectImg">
								<option value="2014">2014</option>
								<option value="2015">2015</option>
								<option value="2016">2016</option>
								<option value="2017">2017</option>																
								<option value="2018">2018</option>
								<option value="2019">2019</option>
								<option value="2020">2020</option>																
							</select>
							<p class="TextBoxHeading">Expiry Year</p>
						  </div>
                       </div>
					   <div class="clear"></div>
					   
					   
					   
					   
					   
					   
			   		<p class="ULPara" style="margin-left: 15px;">Provide Your Billing Address Details:</p>
					   <div class="FormRaw" style="margin-left: -5px !important;">
                        <div class="AccCityZipTextBoxDiv">
                        	<input type="text" class="AccCityZipTextBox" placeholder="70 N 6th St"  id="txtClientAddress" name="txtClientAddress" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->ClientAddress:'')?>" maxlength="60"/>
                            <p class="TextBoxHeading">Address</p>
                        </div>
                        <div class="AccCountrySelectDiv">
                        	<input type="text" style="width:150px !Important;" class="AccCityZipTextBox" id="txtClientState" name="txtClientState" placeholder="NY" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->ClientState:'')?>" maxlength="15"/>
                            <p class="TextBoxHeading" id="pStateProvince">State</p>
                        </div>
						<div class="AccCityZipTextBoxDiv" style="margin-left: 0;">
                        	<input type="text" class="AccCityZipTextBox" placeholder="Brooklyn" id="txtClientCity" name="txtClientCity" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->ClientCity:'')?>"   maxlength="25"/>
                            <p class="TextBoxHeading">City</p>
                        </div>
                      </div>
                      
                      <div class="FormRaw" style="margin-left: -40px !important;">
                      	
                        <div class="AccCityZipTextBoxDiv" style="margin-left:70px;">
                        	<input type="text" class="AccCityZipTextBox" placeholder="11223" style="width:170px;" id="txtClientZip" name="txtClientZip" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->ClientZipCode:'')?>"  maxlength="15"/>
                            <p class="TextBoxHeading" id="pZipPostal">Zip Code</p>
                        </div>
                        <div class="AccCountrySelectDiv">
						<?php
							$mCountryC1 = "";
							$mCountryC2 = "";
							$mCountryC3 = "";
							
							if (isset($mRestaurantDetails))
							{
								if (trim(strtolower($mRestaurantDetails->ClientCountry))=="us")
								{
									$mCountryC1 = " selected='selected' ";
								}
								else if (trim(strtolower($mRestaurantDetails->ClientCountry))=="canada")
								{
									$mCountryC2 = " selected='selected' ";
								}
								else if (trim(strtolower($mRestaurantDetails->ClientCountry))=="uk")
								{
									$mCountryC3 = " selected='selected' ";
								}
								else
								{
									$mCountryC1 = " selected='selected' ";
								}
							}
							else
							{
								$mCountryC1 = " selected='selected' ";
							}
						?>
							<select class="CountrySelect CountrySelectImg" id="ddlCountry2" name="ddlCountry2">
								<option id="US" value="US" <?=$mCountryC1?>>US</option>
								<option id="Canada" value="Canada" <?=$mCountryC2?>>Canada</option>
								<option id="UK" value="UK" <?=$mCountryC3?>>UK</option>
							</select>
                            <p class="TextBoxHeading">Country</p>
                        </div>
                      </div>
					   
					   
					   
					   
					   
					   
					   
					   
					   
					   
					   
					   
					   
					   
					   
					   <div class="FormRaw">
					   	 <?php
							$mProductID = "3305634";
							//$mSQLPr = "SELECT product_id FROM `chargify_products` WHERE user_id = (SELECT user_id FROM chargify_products WHERE product_id='".$mProductID."')";
							//Below we are using Hard coded userid of Claerence. This may change as per instructions of Clarence.
							$mSQLPr = "SELECT product_id FROM `chargify_products` WHERE user_id = 414"; 
							$mResPr = dbAbstract::Execute($mSQLPr);
							if (dbAbstract::returnRowsCount($mResPr)>0)
							{
								$mObjCAPI = new Chargify_Api;
							?>
							<script type="text/javascript" language="javascript">
								$(document).ready(function($) 
								{
									$(".optProduct").click(function()
									{
										mSetup = $(this).attr("initfee");
										mMonthly = $(this).attr("monthfee");
										if (mSetup=="")
										{
											mSetup = "0";
										}
										
										if (mMonthly=="")
										{
											mMonthly = "0";
										}
										
										$("#spnSetup").text("$"+mSetup/100);
										$("#spnMonthly").text("$"+mMonthly/100);
										mTotal = ((mSetup/100) + (mMonthly/100));
										$("#spnTotal").text("$"+mTotal);
									});
								});
							</script>
							<select id="ddlProducts" name="ddlProducts" class="CountrySelect CountrySelectImg" style="margin-left: 17px; width: 580px;">
							<?php
								$mCount = 0;
								$mSetup = "";
								$mMonthly = "";
								$mTotal = "";
								
								while($mRowPr = dbAbstract::returnObject($mResPr))
								{
									$mPrdID = $mRowPr->product_id;
									$mProduct = $mObjCAPI->getProductById($mPrdID);
									if ($mCount == 0)
									{
										$mSetup = $mProduct->initial_charge_in_cents;
										$mMonthly = $mProduct->price_in_cents;
										if ($mSetup=="")
										{
											$mSetup = "0";
										}
										else
										{
											$mSetup = $mSetup/100;
										}
										
										if ($mMonthly=="")
										{
											$mMonthly = "0";
										}
										else
										{
											$mMonthly = $mMonthly/100;
										}
										$mTotal = $mSetup + $mMonthly;
										$mTotal = "$".$mTotal;
										$mSetup = "$".$mSetup;
										$mMonthly = "$".$mMonthly;
										$mCount++;
									}
							?>
								<option class="optProduct" value="<?=$mProduct->id?>" title="<?=$mProduct->description?>" initfee="<?=$mProduct->initial_charge_in_cents?>" monthfee="<?=$mProduct->price_in_cents?>"><?=$mProduct->name?></option>
							<?php		
								}
							}
						   ?>
						   </select>
						   <p class="TextBoxHeading" style="margin-left: 17px;">Chooose your product</p>
					   </div>
					   <div class="clear"></div>
                       <div id="CalculationsDIv">
                       		<div id="CalculationLeft">
                            	<div class="CalculationLeftStyle">Set-up Fee</div>
                                <div class="CalculationLeftStyle">Mothnly Fee</div>
                            </div>
                            <div id="CalculationRight">
                            	<div class="CalculationRightStyle" id="spnSetup"><?=$mSetup?></div>
                                <div class="CalculationRightStyle" id="spnMonthly"><?=$mMonthly?></div>
                                <div class="CalculationRightStyle" style="border-bottom:1px solid #888;">Total Due:<span class="TotalPrice" id="spnTotal"><?=$mTotal?></span></div>
                            </div>
                       </div>
                       <div class="clear"></div>
                       <div id="PleaseReview">Please Review Order Information Before Clicking Submit</div>
                       <div class="SubmitBigDiv" style="margin-left: 320px !important;">
	   		              <div id="RestaurantInputFinalError" class="SearchRestaurantError" style="width: 500px !important; margin-bottom: 20px !important; margin-left: -140px; display: none;">Error occurred.</div>
                          <div class="AccSubmitDiv" style="margin-top: -16px;">
                          	<a id="submitButton" href="#" class="Save progress-button">Save</a>
                          <!--<input id="submitButton" type="submit" value="Save" class="Save progress-button" style="display:block;" />-->
                          </div>
                          <div class="AccSubmitDiv" style="margin:0 30px;"><a href="#" class="Next" id="Fourth_Back_Tab">Back</a></div>
                          <div class="AccSubmitDiv" style="display: none;"><a href="#" class="Next" id="Fifth_Tab">Next</a></div>
                     	</div>
                       <div class="ProgressBarDiv" style="padding: 50px 0;">
                          <div id="progress">
                              <span id="percent" style="left: 40%;">90%</span>
                              <div id="bar" style="width: 90%;"></div>
                          </div>
            		  </div>
					</article>
				</div>
			</section>
            </div>

            <!--This Div is to Hide And Show-->
            <div id="ThankYouContainer" style="display: none;">
            	<div id="ThankYou">Thank You!</div>
                <div id="ThankYouMsg">Your information has been received, and someone will contact you shortly. In the meantime, have you checked out our demo video?</div>
                <div class="clear"></div>
                <div id="BigVideoDiv">
                    <div id="InnerBigVideo">
                        <embed width="850" height="485"src="https://www.youtube.com/watch?v=VqpyGZzv1YE&feature=share&list=UU389YE8eB5cWeQgxge__9ZA&index=2" type="application/x-shockwave-flash"></embed>
                    </div>
            	</div>
                <div id="FinalBackButton"><a href="#" class="Next" id="Fifth_Back_Tab">Back</a></div>
                <div class="ProgressBarDiv" style="padding: 50px 0;">
                          <div id="progress">
                              <span id="percent" style="left: 45%;">100%</span>
                              <div id="bar" style="width: 100%;"></div>
                          </div>
            	</div>
            </div>
			</form>
            <!--Till Here-->
        </div>
        <div id="FooterBigDiv">
        	<div id="FooterInnerDiv">
            	<div id="FooterInnerLeft">
                	<div id="Navigation">
                        <div id="Careers"><a href="#" style="color:#FFF; text-decoration:none;">Careers</a></div>
                        <div id="Press"><a href="#" style="color:#FFF; text-decoration:none;">Press</a></div>
                        <div id="Privacy"><a href="Privacy.html" style="color:#FFF; text-decoration:none;">Privacy</a></div>
                        <div id="Support"><a href="Support.html" style="color:#FFF; text-decoration:none;">Support</a></div>
                    </div>
                    <div id="AddressDiv">
                    	<div id="FirstAddress">50 Broad Street   Suite 1701</div>
                        <div id="SecondAddress">New York, NY 10004 United States of America. </div>
                        <div id="Telephone">T: (800) 648-6238</div>
                        <div id="Fax">F: (800) 356-1510</div>
                        
                    </div>
                    
                </div>
                <div id="FooterInnerRight">
               		<div id="ArrowDiv"><a href="#top"><img src="images/UpArrow.png" /></a></div>
                	<div id="NewsletterDiv">
                    	<div id="TextBoxDiv">
                        	<form action="http://easywayordering.us7.list-manage.com/subscribe/post" method="POST"> 
                            <input type="hidden" name="u" value="b833067e17d155a7b4f915d2e"> 
                            <input type="hidden" name="id" value="4e87551cd3">
							<input type="email" autocapitalize="off" autocorrect="off" name="MERGE0" id="MERGE0" size="25" value="" class="Text" placeholder="Enter email address">
                            
                        </div>
                        <div id="ButtonDiv">
                            <input type="submit" class="Submit" value="SIGN ME UP!" />
                            </form>
                        </div>
                    </div>
                    <div id="JoinOurNewsLetter">JOIN OUR NEWSLETTER</div>
                    <div id="GetTheLattest">Get the latest industry news, plus valuable <br /> business tips and tricks, FOR FREE! - subscribe now</div>
                    
                </div>
            </div>
        </div>
        
        
    </div>
</div>
<script src="Scripts/ProgressButton_script.js"></script>
</body>
</html>

<?php
	if (isset($_GET["flag"]))
	{
		if ($_GET["flag"]=="upload")
		{
			echo("<script type='text/javascript' language='javascript'>$('#ac-3').prop('checked',true);</script>");
		}
		else if ($_GET["flag"]=="start")
		{
			echo("<script type='text/javascript' language='javascript'>$('#ac-1').prop('checked',true);</script>");
		}
	}
//	@mysql_close($mysql_conn);
?>
