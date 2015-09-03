<link rel="stylesheet" href="css/colorpicker.css" type="text/css" />
<script type="text/javascript" src="js/colorpicker.js"></script>
<?php
include "includes/resturant_header.php";
$mMessage="";
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
$mRbLayout1="";
$mRbLayout2="";
$mChkShow="";
$mShow= " display:none;";
	
$mRestaurantID = $Objrestaurant->id;
$mSettingsCount = $Objrestaurant->CountIframeSettingsByRestaurantID($mRestaurantID);

function GetFileExt($pFileName)
{
	$mExt = substr($pFileName, strrpos($pFileName, '.'));
	$mExt = strtolower($mExt);
	return $mExt;
}
$mOOBIFileName="";
$mCBIFileName="";

$mOOBIFlag = 0;
$mCBIFlag = 0;

if (isset($_POST["btnDelOOBI"])) //Delete Order Online Image Button Clicked
{
	$mOOBICBI = $Objrestaurant->getOOBICBIByRestaurantID($Objrestaurant->id);
	if ($mOOBICBI!=0)
	{
		$mTmpOOBI = $mOOBICBI->OrderOnlineButtonImage;
		if (file_exists(realpath($mTmpOOBI))) 
		{
			unlink(realpath($mTmpOOBI));
		}
	}
	$Objrestaurant->UpdateOOBIByRestaurantID($Objrestaurant->id, "");
}
else if (isset($_POST["btnDelCBI"])) //Delete Cell BG Image Button Clicked
{
	$mOOBICBI = $Objrestaurant->getOOBICBIByRestaurantID($Objrestaurant->id);
	if ($mOOBICBI!=0)
	{
		$mTmpCBI = $mOOBICBI->CellBGImage;
		if (file_exists(realpath($mTmpCBI))) 
		{
			unlink(realpath($mTmpCBI));
		}
	}
	$Objrestaurant->UpdateCBIByRestaurantID($Objrestaurant->id, "");
}
else if (isset($_POST["btnDownloadImage"])) //Download Image button clicked
{
}
else if (isset($_POST["btnSubmit"])) //Submit Button Clicked
{
	if($_FILES['fuOOBI']['name'])
	{
		if(!$_FILES['fuOOBI']['error'])
		{
			$mExt = GetFileExt($_FILES['fuOOBI']['name']);
			if ((strtolower($mExt)!='.jpg') && (strtolower($mExt)!='.jpeg') && (strtolower($mExt)!='.gif') && (strtolower($mExt)!='.png'))
			{
				$mMessage = "Invalid file type for Online Order Button Image, please upload only jpg, gif or png.";
			}
			else
			{
				if (!file_exists('../images/'.$Objrestaurant->url_name)) 
				{
					mkdir('../images/'.$Objrestaurant->url_name, 0777, true);
				}
				
				$mPath = '../images/'.$Objrestaurant->url_name.'/';
				$mRandom = mt_rand(1, mt_getrandmax());
				$mOOBIFileName =  $mPath.str_replace(".", "_", str_replace(" ", "_", basename($_FILES['fuOOBI']['name'],$mExt)))."_".$mRandom.$mExt;
				if (!move_uploaded_file($_FILES['fuOOBI']['tmp_name'] , $mOOBIFileName))
				{
					$mMessage = "Error occurred while uploading Online Order Button Image.";
				}
				else
				{
					$mOOBIFlag = 1;
				}
			}
		}
		else
		{
			$mMessage = "Error occurred while uploading Online Order Button Image.";
		}
	}
	
	if ($mMessage=="")
	{
		if($_FILES['fuCBI']['name'])
		{
			if(!$_FILES['fuCBI']['error'])
			{
				$mExt = GetFileExt($_FILES['fuCBI']['name']);
				if ((strtolower($mExt)!='.jpg') && (strtolower($mExt)!='.jpeg') && (strtolower($mExt)!='.gif') && (strtolower($mExt)!='.png'))
				{
					$mMessage = "Invalid file type for Cell BG Image, please upload only jpg, gif or png.";
				}
				else
				{
					if (!file_exists('../images/'.$Objrestaurant->url_name)) 
					{
						mkdir('../images/'.$Objrestaurant->url_name, 0777, true);
					}
					
					$mPath = '../images/'.$Objrestaurant->url_name.'/';
					$mRandom = mt_rand(1, mt_getrandmax());
					$mCBIFileName =  $mPath.str_replace(".", "_", str_replace(" ", "_", basename($_FILES['fuCBI']['name'],$mExt)))."_".$mRandom.$mExt;
					if (!move_uploaded_file($_FILES['fuCBI']['tmp_name'] , $mCBIFileName))
					{
						$mMessage = "Error occurred while uploading Cell BG Image.";
					}
					else
					{
						$mCBIFlag = 1;
					}
				}
			}
			else
			{
				$mMessage = "Error occurred while uploading Cell BG Image.";
			}
		}
	}
	
	if ($mMessage=="")
	{
		if ($mSettingsCount==0) //No Settings Exist, Do Insert
		{
			if ($Objrestaurant->InsertIframeDetailsByRestaurantID($mRestaurantID, $_POST["txtGFS"], $_POST["txtGTC"], $_POST["txtSTC"], $_POST["txtMBC"], $_POST["txtMLCA"], $_POST["txtMLCI"], $_POST["txtSMHC"], $_POST["txtSMDC"], $_POST["txtITC"], $_POST["txtIPC"], $_POST["txtIDC"], $_POST["txtIPFS"], $_POST["txtYOSC"], $_POST["txtYOSFS"], $_POST["txtCBC"], $_POST["txtCBrC"], $_POST["txtCBT"], $_POST["txtTFS"], $_POST["txtTF"], $_POST["txtMWC"], $_POST["txtCVPB"], $mOOBIFileName, $mCBIFileName, $_POST["rbST"], ((isset($_POST["chkSLB"]))?1:0) ,$_POST["rbLayout"], ((isset($_POST["chkShow"]))?1:0)))
			{
				$mMessage = "Facebook Ordering settings saved successfully.";
			}
			else
			{
				$mMessage = "Error occurred.";
			}
		}
		else if ($mSettingsCount==1) //Settings Exist, Do Update
		{
			$mOOBICBI = $Objrestaurant->getOOBICBIByRestaurantID($Objrestaurant->id);
			if ($mOOBICBI!=0)
			{
				$mTmpOOBI = $mOOBICBI->OrderOnlineButtonImage;
				$mTmpCBI = $mOOBICBI->CellBGImage;
				if ($mOOBIFlag == 1)
				{
					if (file_exists(realpath($mTmpOOBI))) 
					{
						unlink(realpath($mTmpOOBI));
					}
				}
				else
				{
					$mOOBIFileName = $mTmpOOBI;
				}
				
				if ($mCBIFlag == 1)
				{
					if (file_exists(realpath($mTmpCBI))) 
					{
						unlink(realpath($mTmpCBI));
					}
				}
				else
				{
					$mCBIFileName = $mTmpCBI;
				}
			}
			
			if ($Objrestaurant->UpdateIframeDetailsByRestaurantID($mRestaurantID, $_POST["txtGFS"], $_POST["txtGTC"], $_POST["txtSTC"], $_POST["txtMBC"], $_POST["txtMLCA"], $_POST["txtMLCI"], $_POST["txtSMHC"], $_POST["txtSMDC"], $_POST["txtITC"], $_POST["txtIPC"], $_POST["txtIDC"], $_POST["txtIPFS"], $_POST["txtYOSC"], $_POST["txtYOSFS"], $_POST["txtCBC"], $_POST["txtCBrC"], $_POST["txtCBT"], $_POST["txtTFS"], $_POST["txtTF"], $_POST["txtMWC"], $_POST["txtCVPB"], $mOOBIFileName, $mCBIFileName, $_POST["rbST"], ((isset($_POST["chkSLB"]))?1:0) ,$_POST["rbLayout"], ((isset($_POST["chkShow"]))?1:0)))
			{
				$mMessage = "Facebook Ordering settings updated successfully.";
			}
			else
			{
				$mMessage = "Error occurred.";
			}
		}
		else //Error
		{
			$mMessage = "Error occurred.";
		}
	}
}
else if (isset($_POST["btnDefault"])) //Default Button Clicked
{
	if ($mSettingsCount==0) //No Settings Exist, Do Insert
	{
		if ($Objrestaurant->InsertIframeDetailsByRestaurantID($mRestaurantID, $mGFS, $mGTC, $mSTC, $mMBC, $mMLCA, $mMLCI, $mSMHC, $mSMDC, $mITC, $mIPC, $mIDC, $mIPFS, $mYOSC, $mYOSFS, $mCBC, $mCBrC, $mCBT, $mTFS, $mTF, $mMWC, $mCVPB, $mOOBIFileName, $mCBIFileName, $mCBIST, $mSLB, $mLS, $mSPD))
		{
			$mMessage = "Facebook Ordering settings saved successfully.";
		}
		else
		{
			$mMessage = "Error occurred.";
		}
	}
	else if ($mSettingsCount==1) //Settings Exist, Do Update
	{
		$mOOBICBI = $Objrestaurant->getOOBICBIByRestaurantID($Objrestaurant->id);
		if ($mOOBICBI!=0)
		{
			$mTmpOOBI = $mOOBICBI->OrderOnlineButtonImage;
			$mTmpCBI = $mOOBICBI->CellBGImage;
			if (file_exists(realpath($mTmpOOBI))) 
			{
				unlink(realpath($mTmpOOBI));
			}
			
			if (file_exists(realpath($mTmpCBI))) 
			{
				unlink(realpath($mTmpCBI));
			}
		}
		
		if ($Objrestaurant->UpdateIframeDetailsByRestaurantID($mRestaurantID, $mGFS, $mGTC, $mSTC, $mMBC, $mMLCA, $mMLCI, $mSMHC, $mSMDC, $mITC, $mIPC, $mIDC, $mIPFS, $mYOSC, $mYOSFS, $mCBC, $mCBrC, $mCBT, $mTFS, $mTF, $mMWC, $mCVPB, $mOOBIFileName, $mCBIFileName, $mCBIST, $mSLB, $mLS, $mSPD))
		{
			$mMessage = "Facebook Ordering settings updated successfully.";
		}
		else
		{
			$mMessage = "Error occurred.";
		}
	}
	else //Error
	{
		$mMessage = "Error occurred.";
	}
}

$mIframeSettings = $Objrestaurant->getIframeDetailsByRestaurantID($Objrestaurant->id);
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
}

if ($mLS == 0)
{
	$mRbLayout1=" checked='checked' ";
}
else if ($mLS == 1)
{
	$mShow= "display:inline;";
	$mRbLayout2=" checked='checked' ";
	if ($mSPD == 1)
	{
		$mChkShow=" checked='checked' ";
	}
}

if ($mSLB==1)
{
	$mChkSLB=" checked='checked' ";
}
?>
<script language="javascript" type="application/javascript">
$(document).ready( function($) {
	$('#txtGTC').ColorPicker({
		color: $('#txtGTC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtGTC').val('#' + hex);
		}
	});
	
	$('#txtSTC').ColorPicker({
		color: $('#txtSTC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtSTC').val('#' + hex);
		}
	});
		
	$('#txtMBC').ColorPicker({
		color: $('#txtMBC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtMBC').val('#' + hex);
		}
	});
			
	$('#txtMLCA').ColorPicker({
		color: $('#txtMLCA').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtMLCA').val('#' + hex);
		}
	});
				
	$('#txtMLCI').ColorPicker({
		color: $('#txtMLCI').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtMLCI').val('#' + hex);
		}
	});
					
	$('#txtSMHC').ColorPicker({
		color: $('#txtSMHC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtSMHC').val('#' + hex);
		}
	});
						
	$('#txtSMDC').ColorPicker({
		color: $('#txtSMDC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtSMDC').val('#' + hex);
		}
	});
							
	$('#txtITC').ColorPicker({
		color: $('#txtITC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtITC').val('#' + hex);
		}
	});
								
	$('#txtIPC').ColorPicker({
		color: $('#txtIPC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtIPC').val('#' + hex);
		}
	});
									
	$('#txtIDC').ColorPicker({
		color: $('#txtIDC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtIDC').val('#' + hex);
		}
	});
										
	$('#txtYOSC').ColorPicker({
		color: $('#txtYOSC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtYOSC').val('#' + hex);
		}
	});
	
	$('#txtCBC').ColorPicker({
		color: $('#txtCBC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtCBC').val('#' + hex);
		}
	});
		
	$('#txtCBrC').ColorPicker({
		color: $('#txtCBrC').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtCBrC').val('#' + hex);
		}
	});
			
	$('#txtCVPB').ColorPicker({
		color: $('#txtCVPB').val(),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#txtCVPB').val('#' + hex);
		}
	});
});
</script>
<?php include "admin_contents/advanced_settings/nav.php"; ?>
<div id="contents">
	<div class="form_outer" style="text-align: left !important;">
		<form id="frmMain" name="frmmain" method="post" enctype="multipart/form-data">
			<table width="857px" border="0" cellpadding="4" cellspacing="0">
				<tr style="display: none;">
					<td colspan="3">
					</td>
				</tr>
				<tr style="height: 20px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 98%;" colspan="2">
						<span style="color: red;"><?php echo($mMessage); ?></span>
					</td>
				</tr>
				<tr style="display: none;">
					<td colspan="3">
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div style="width: 100%;">
							<table style="width: 100%; margin: 0px; border: 1px solid #CCCCCC;" cellpadding="0" cellspacing="0">
								<tr style="height: 60px;background-color: #FFFFFF !important;">
									<td style="width: 2%;">
									</td>
									<td>
										<span style="font-size: 18px; font-weight: bold; color: #039;">Facebook App Settings</span>
									</td>
								</tr>
								<tr style="background-color: #F6F7F8; height: 60px;">
									<td style="width: 2%;">
									</td>
									<td colspan="3">
										<span style="margin-bottom: 100px !important; font-size: 15px; font-weight: bold;">Page Tab</span>
									</td>
								</tr>
								<tr style="background-color: #FFFFFF !important; height: 10px;">
									<td colspan="4">
									</td>
								</tr>
								<tr style="background-color: #FFFFFF !important;">
									<td style="width: 2%;">
									</td>
									<td style="width: 48%;">
										<span style="margin-bottom: 100px !important; font-size: 15px;">Page Tab Name</span>
									</td>
									<td style="width: 48%;">
										<span style="margin-bottom: 100px !important; font-size: 15px;">Page Tab URL</span>
									</td>
									<td style="width: 2%;">
									</td>
								</tr>
								<tr style="height: 40px; background-color: #FFFFFF !important;">
									<td style="width: 2%;">
									</td>
									<td style="width: 48%;">
										<input type="text" style="width: 95%; height: 25px; border: 1px solid #CCCCCC;" value="<?=$Objrestaurant->name?> Online Ordering" readonly="readonly"/>
									</td>
									<td style="width: 48%;">
										<input type="text" style="width: 95%; height: 25px; border: 1px solid #CCCCCC;" value="<?=$SiteUrl?>/<?=$Objrestaurant->url_name?>/?item=resturants&ifrm=load_resturant" readonly="readonly"/>
									</td>
									<td style="width: 2%;">
									</td>
								</tr>
								<tr style="background-color: #FFFFFF !important; height: 10px;">
									<td colspan="4">
									</td>
								</tr>
								<tr style="background-color: #FFFFFF !important;">
									<td style="width: 2%;">
									</td>
									<td style="width: 48%;">
										<span style="margin-bottom: 100px !important; font-size: 15px;">Secure Page Tab URL</span>
									</td>
									<td style="width: 48%;">
										<span style="margin-bottom: 100px !important; font-size: 15px;">Page Tab Edit URL</span>
									</td>
									<td style="width: 2%;">
									</td>
								</tr>
								<tr style="height: 40px; background-color: #FFFFFF !important;">
									<td style="width: 2%;">
									</td>
									<td style="width: 48%;">
										<input type="text" style="width: 95%; height: 25px; border: 1px solid #CCCCCC;" value="https://<?=str_replace("https://", "", str_replace("http://", "", $SiteUrl))?><?=$Objrestaurant->url_name?>/?item=resturants&ifrm=load_resturant" readonly="readonly"/>
									</td>
									<td style="width: 48%;">
										<input type="text" style="width: 95%; height: 25px; border: 1px solid #CCCCCC;" value="https://<?=str_replace("https://", "", str_replace("http://", "", $AdminSiteUrl))?>" readonly="readonly"/>
									</td>
									<td style="width: 2%;">
									</td>
								</tr>
								<tr style="background-color: #FFFFFF !important; height: 10px;">
									<td colspan="4">
									</td>
								</tr>
								<tr style="height: 40px; background-color: #FFFFFF !important;">
									<td style="width: 2%;">
									</td>
									<td style="width: 96%;" colspan="2">
										<span style="margin-bottom: 100px !important; font-size: 15px;">Page Tab Image</span>
									</td>
									<td style="width: 2%;">
									</td>
								</tr>
								<tr style="height: 40px; background-color: #FFFFFF !important;">
									<td style="width: 2%;">
									</td>
									<td style="width: 96%;" colspan="2">
										<table style="width: 100%;" cellpadding="0" cellspacing="0" border="0">
											<tr style="background-color: #FFFFFF;">
												<td style="width: 15%;">
													<img src="../images/ewo_order_online.png" border="0" />
												</td>
												<td valign="bottom">
													<a href="download.php" target="_blank">Click to download image file</a>
												</td>
											</tr>
										</table>
									</td>
									<td style="width: 2%;">
									</td>
								</tr>
								<tr style="background-color: #FFFFFF !important; height: 10px;">
									<td colspan="4">
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr style="height: 20px; display: none;">
					<td colspan="3">
					</td>
				</tr>
				<tr style="background-color: #FFFFFF !important;">
					<td colspan="3" valign="top">
						<script type="text/javascript" language="javascript">
							$(document).ready(function()
							{
								$("#txtAppID").focus(function()
								{
									if ($.trim($("#txtAppID").val())=="Your AppID")
									{
										$("#txtAppID").val("");
										$("#txtAppID").css("color", "#000000");
									}
								});
								
								$("#txtAppID").focusout(function()
								{
									if ($.trim($("#txtAppID").val())=="")
									{
										$("#txtAppID").val("Your AppID");
										$("#txtAppID").css("color", "#888888");
									}
									else
									{
										if (!($.isNumeric($('#txtAppID').val())))
										{
											$('#spnNumber').show();
										}
										else
										{
											$('#spnNumber').hide();
										}
									}
								});
								
								$('#txtAppID').bind('#txtAppID propertychange', function() 
								{
									if (!($.isNumeric($('#txtAppID').val())))
									{
										$('#spnNumber').show();
									}
									else
									{
										$('#spnNumber').hide();
									}
								});
								
								$('#btnAppID').click(function() 
								{
									if (!($.isNumeric($('#txtAppID').val())))
									{
										$('#spnNumber').show();
									}
									else
									{
										$('#spnNumber').hide();
										window.open("https://www.facebook.com/dialog/pagetab?app_id="+$('#txtAppID').val()+"&display=popup&next=http://www.facebook.com", "_blank");
									}
								});
							});
						</script>
						<table style="background-color: #FFFFFF !important; width: 100%; height: 100px; margin: 0px; border: 1px solid #CCCCCC;" cellpadding="0" cellspacing="0">
							<tr style="height: 25px; background-color: #FFFFFF !important;">
								<td colspan="3">
								</td>
							</tr>
							<tr style="background-color: #FFFFFF !important;">
								<td style="width: 2%;">
								</td>
								<td valign="top">
									<span style="font-size: 18px; font-weight: bold; color: #039;">Widget to Add your App to Facebook page.</span>
								</td>
								<td style="width: 2%;">
								</td>
							</tr>
							<tr style="height: 10px; background-color: #FFFFFF !important;">
								<td colspan="3">
								</td>
							</tr>
							<tr style="background-color: #FFFFFF !important;">
								<td style="width: 2%;">
								</td>
								<td valign="top">
									<span style="font-size: 13px; font-weight: bold; color: #000000">Write/Paste your AppID in the textbox below and click "Add to Page". This will open a facbook popup/tab where you can choose the page where you want to add the App.</span> <br /><br /><span style="font-size: 13px; font-weight: bold; color: #B80000;">NOTE: If you are not logged in to your Facebook account then popup/tab will require you to login. Its AppID not Page ID.</strong>
								</td>
								<td style="width: 2%;">
								</td>
							</tr>
							<tr style="height: 10px; background-color: #FFFFFF !important;">
								<td colspan="3">
								</td>
							</tr>
							<tr style="background-color: #FFFFFF !important;">
								<td style="width: 2%;">
								</td>
								<td valign="top">
									<input type="text" id="txtAppID" size="40" value="Your AppID" style="color: #888888; height: 30px;" />&nbsp;&nbsp;&nbsp;<input type="button" id="btnAppID" value="Add to Page" style="height: 35px;" />
								</td>
								<td style="width: 2%;">
								</td>
							</tr>
							<tr style="background-color: #FFFFFF !important;">
								<td style="width: 2%;">
								</td>
								<td valign="top">
									<span style="color: #FF0000; display: none;" id="spnNumber">Numbers Only</span>
								</td>
								<td style="width: 2%;">
								</td>
							</tr>
							<tr style="height: 25px; background-color: #FFFFFF !important;">
								<td colspan="3">
								</td>
							</tr>
						</table>
					</td>
				</tr>	
				<tr style="height: 20px; display: none;">
					<td colspan="3">
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Layout style:
					</td>
					<td style="width: 68%;">	
						<input type="radio" name="rbLayout" value="0" id="rbLayout1" style="display: none;"/>
						<input type="radio" name="rbLayout" value="1" id="rbLayout2" checked="checked" style="display: none;" />
					   <input type="checkbox" name="chkShow" id="chkShow" <?php echo($mChkShow); ?> />Show item pictures and description
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						General font size:
					</td>
					<td style="width: 68%;">
						<input name="txtGFS" id="txtGFS" value="<?php echo($mGFS); ?>" size="5" maxlength="2">px<br><i>All elments without any font size defined will use this.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						General text color:
					</td>
					<td style="width: 68%;">
						<input name="txtGTC" id="txtGTC" value="<?php echo($mGTC); ?>" size="20" maxlength="7"><br><i>Text without any color defined will use this color as default.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Secondary text color:
					</td>
					<td style="width: 68%;">
						<input name="txtSTC" id="txtSTC" value="<?php echo($mSTC); ?>" size="20" maxlength="7"><br><i>Secondary text color.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Menu BG Color:
					</td>
					<td style="width: 68%;">
						<input name="txtMBC" id="txtMBC" value="<?php echo($mMBC); ?>" size="20" maxlength="7"><br><i>Default is grey, applied to the top menu backgroud.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Menu link color on active:
					</td>
					<td style="width: 68%;">
						<input name="txtMLCA" id="txtMLCA" value="<?php echo($mMLCA); ?>" size="20" maxlength="7"><br><i>Default is red, used for active menu link.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Menu link color on inactive:
					</td>
					<td style="width: 68%;">
						<input name="txtMLCI" id="txtMLCI" value="<?php echo($mMLCI); ?>" size="20" maxlength="7"><br><i>Default is #333333, Used for inactive menu link.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Sub Menu Headings color:
					</td>
					<td style="width: 68%;">
						<input name="txtSMHC" id="txtSMHC" value="<?php echo($mSMHC); ?>" size="20" maxlength="7"><br><i>Default is #585858, Used for sub menu headings color.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Sub Menu Descriptions color:
					</td>
					<td style="width: 68%;">
						<input name="txtSMDC" id="txtSMDC" value="<?php echo($mSMDC); ?>" size="20" maxlength="7"><br><i>Default is #585858, Used for sub menu descriptions color.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Item's title color:
					</td>
					<td style="width: 68%;">
						<input name="txtITC" id="txtITC" value="<?php echo($mITC); ?>" size="20" maxlength="7"><br><i>Default is #000000, color for item's title color.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Item's price color:
					</td>
					<td style="width: 68%;">
						<input name="txtIPC" id="txtIPC" value="<?php echo($mIPC); ?>" size="20" maxlength="7"><br><i>Default is #000000, color for item's price color.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Item's description color:
					</td>
					<td style="width: 68%;">
						<input name="txtIDC" id="txtIDC" value="<?php echo($mIDC); ?>" size="20" maxlength="7"><br><i>Default is #000000, color for item's description color.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Items & Prices font size:
					</td>
					<td style="width: 68%;">
						<input name="txtIPFS" id="txtIPFS" value="<?php echo($mIPFS); ?>" size="5" maxlength="2">px<br><i>Default is 14px, used for "Items & Prices" font size.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						"Your Order Summary" color:
					</td>
					<td style="width: 68%;">
						<input name="txtYOSC" id="txtYOSC" value="<?php echo($mYOSC); ?>" size="20" maxlength="7"><br><i>Default is #5f5f5f, color for "Your Order Summary" color.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						"Your Order Summary" font size:
					</td>
					<td style="width: 68%;">
						<input name="txtYOSFS" id="txtYOSFS" value="<?php echo($mYOSFS); ?>" size="5" maxlength="2">px<br><i>Default is 18px, Font size of "Your Order Summary".</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						"Order Online" button image
					</td>
					<td style="width: 68%;">
						<table style="width: 100%; margin: 0; background-color: #EFEFEF;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="width: 70%;">
									<input type="file" id="fuOOBI" name="fuOOBI" size="5" /><br  />Default is grey, appears behind the menu list and sub menu title.
								</td>
								<td style="width: 30%;" align="right">
									<?php
										if ($mOOBI!="")
										{
									?>
											<table style="width: 100%; margin: 0; background-color: #EFEFEF;" border="0" cellpadding="0" cellspacing="0">
												<tr>
													<td align="left">
														<strong>Current</strong>
													</td>
												</tr>
												<tr>
													<td align="left" style=" background-color: #EFEFEF;">
														<img width="100px" height="50px" src="<?php echo($mOOBI); ?>"/>
													</td>
												</tr>
												<tr>
													<td align="left">
														<input type="submit" id="btnDelOOBI" name="btnDelOOBI" value="Delete" />
													</td>
												</tr>
											</table>
									<?php		
										}
									?>
								</td>
							</tr>	
						</table>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Cell BG color:
					</td>
					<td style="width: 68%;">
						<input name="txtCBC" id="txtCBC" value="<?php echo($mCBC); ?>" size="20" maxlength="7"><br><i>Specify background color or chose "transparent" for the white behind menu items and cart contents.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Cell BG image
					</td>
					<td style="width: 68%;">
						<table style="width: 100%; margin: 0; background-color: #EFEFEF !important;" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="width: 70%;">
									<input type="file" id="fuCBI" name="fuCBI" size="5" /><br  />Specify background image for the white behind menu items and cart contents.
								</td>
								<td style="width: 30%;" align="right">
									<?php
										if ($mCBI!="")
										{
									?>
											<table style="width: 100%; margin: 0; background-color: #EFEFEF !important;" border="0" cellpadding="0" cellspacing="0">
												<tr>
													<td align="left">
														<strong>Current</strong>
													</td>
												</tr>
												<tr>
													<td align="left" style="background-color: #EFEFEF !important;">
														<img width="100px" height="50px" src="<?php echo($mCBI); ?>"/>
													</td>
												</tr>
												<tr>
													<td align="left">
														<input type="submit" id="btnDelCBI" name="btnDelCBI" value="Delete" />
													</td>
												</tr>
											</table>
									<?php		
										}
									?>
								</td>
							</tr>	
						</table>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Cell BG image stretch / tile
					</td>
					<td style="width: 68%;">
						<input type="radio" name="rbST" value="0" <?php if ($mCBIST==0) { echo(' checked="checked" '); } ?>/>Stretch&nbsp;&nbsp;&nbsp;<input type="radio" name="rbST" value="1"  <?php if ($mCBIST==1) { echo(' checked="checked" ');} ?>/>Tile
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Cell border color:
					</td>
					<td style="width: 68%;">
						<input name="txtCBrC" id="txtCBrC" value="<?php echo($mCBrC); ?>" size="20" maxlength="7"><br><i>Default is #f4f4f4, set the border color of the columns.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Cell border thickness:
					</td>
					<td style="width: 68%;">
						<input name="txtCBT" id="txtCBT" value="<?php echo($mCBT); ?>" size="5" maxlength="1">px<br><i>Default is 1, set the border thickness of the columns.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Titles font size:
					</td>
					<td style="width: 68%;">
						<input name="txtTFS" id="txtTFS" value="<?php echo($mTFS); ?>" size="5" maxlength="2">px<br><i>Default is 12px, font size of titles.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Titles font:
					</td>
					<td style="width: 68%;">
						<input name="txtTF" id="txtTF" value="<?php echo($mTF); ?>" size="30" maxlength="50"><br><i>Default is 'Arial,Helvetica,sans-serif', font family or style of titles.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Min Width of the container:
					</td>
					<td style="width: 68%;">
						<input name="txtMWC" id="txtMWC" value="<?php echo($mMWC); ?>" size="5" maxlength="4">px<br><i>Min width of the container.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Show loyalty box above the cart:
					</td>
					<td style="width: 68%;">
						<input type="checkbox" id="chkSLB" name="chkSLB" <?php echo($mChkSLB); ?> />
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%; font-weight: bold;">
						Color for VIP progress bar:
					</td>
					<td style="width: 68%;">
						<input name="txtCVPB" id="txtCVPB" value="<?php echo($mCVPB); ?>" size="20" maxlength="7"><br><i>Set the color fo the VIP progress bar.</i>
					</td>
				</tr>
				<tr style="height: 60px; vertical-align: middle;">
					<td style="width: 2%;">
					</td>
					<td style="width: 30%;">
					</td>
					<td style="width: 68%;">	
						<input type="submit" id="btnSubmit" name="btnSubmit" value="Save" style="width: 80px !important;"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" id="btnDefault" name="btnDefault" value="Default" style="width: 80px !important;"/>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>