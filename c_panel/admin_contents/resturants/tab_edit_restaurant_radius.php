<style>
    input[type=text].alert-error,input[type=select].alert-error,input[type=password].alert-error{
        background-color: #F99;
        border: 1px solid #D92353;
        border-image: initial;
    }

    .alert-error {
        background-color:#f2dede;
        border-color:#eed3d7;
        color:#b94a48;
        text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);
        -webkit-border-radius:4px;
        -moz-border-radius:4px;
        border-radius:4px;
    }
    div.alert-danger, div.alert-error , span.alert-error{
        padding:8px 10px;
        margin: 5px 0 10px 0;
    }
    .hidden{display:none;}

    #outline {margin:20px; border:solid 10px #9FB6CD; -moz-border-radius:20px; width:512px; height:440px;}
    #map_canvas{width:850px; height:540px;float:left;}
    #forehead{text-align:left;font-size:150%;}
    #novel{width:400px; margin:20px;float:right;}
    #AdSense{margin:20px;}
    /*A:hover {color: red;text-decoration: underline overline;}*/
    td{vertical-align:top;}
    .draggable-popup input{background-color:#ccf}
    .draggable-popup{background-color:white; border:1px solid black}
</style>
<!--Start script for dependent list-->
<!--<script src="../js/deliveryzones.js" type="text/javascript"></script>-->
<script src="../js/checkdeliveryzones.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
// Roshan's Ajax dropdown code with php
// This notice must stay intact for legal use
// Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
// If you have any problem contact me at http://roshanbh.com.np
    function getXMLHTTP() { //fuction to return the xml http object
        var xmlhttp = false;
        try {
            xmlhttp = new XMLHttpRequest();
        }
        catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {
                try {
                    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e1) {
                    xmlhttp = false;
                }
            }
        }

        return xmlhttp;
    }

    function getclients(resellerId, pageName) {
	
	var milliseconds = (new Date).getTime();
        var strURL = "admin_contents/resturants/tab_find_clients.php?resellerId=" + resellerId + "&pageName=" + pageName+"&"+milliseconds;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('client_div').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
        get_licenses_of_reseller(resellerId);
    }

    function get_licenses_of_reseller(resellerId) {
	
	var milliseconds = (new Date).getTime();
        var strURL = "admin_contents/resturants/tab_find_licenses.php?resellerId=" + resellerId+"&"+milliseconds;
        var req = getXMLHTTP();

        if (req) {

            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                        document.getElementById('licenses_div').innerHTML = req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }


</script>
<!--End script for dependent list-->
<script language="JavaScript">
    var location1, location2;
    function showdeliveryoption(option) {
        if (option == 1) {
            $("#delivery_radius").show('slow');
            $("#delivery_zone").hide('slow');
        } else {
            $("#delivery_radius").hide('slow');
            $("#delivery_zone").show('slow');
        }
    }

    function showLocation() {
        loc1 = document.getElementById('rest_zip').value;

        if (loc1 == "") {
            alert("Please enter the zip code for resturant.");
            return false;
        }
        geocoder.geocode({'address': loc1}, function(results, status) {
            if (status !== google.maps.GeocoderStatus.OK)
            {
                alert("Sorry,  unable to recognize the zip code");
            } else
            {
                document.getElementById('submit').click();
            }
        });
    }

</script>

<script src="../js/jquery.validate.js" type="text/javascript"></script>
<script src="../js/mask.js" type="text/javascript"></script>
<link href="../css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox.js" type="text/javascript"></script>


<script type="text/javascript">


    jQuery(function($) {

        $('a[rel*=facebox]').facebox();
        $("#frmedit").validate({
            rules: {
                catname: {required: true},
                email: {required: true, email: 1},
                phone: {required: true},
                fax: {required: true},
                rest_address: {required: true},
                rest_city: {required: true},
                rest_state: {required: true},
                rest_zip: {required: true},
                delivery_radius: {required: true},
                order_minimum: {required: true},
                tax_percent: {required: true},
                delivery_charges: {required: true},
                time_zone: {required: true, min: 1},
            },
            messages: {
                catname: {required: "please enter your restaurant name"},
                email: {
                    required: "please enter email address",
                    email: "please enter a valid email address"
                },
                phone: {
                    required: "please enter phone number"
                },
                fax: {
                    required: "please enter fax number"
                },
                rest_address: {required: "please enter restaurant address"},
                rest_city: {required: "please enter restaurant city"},
                rest_state: {required: "please enter restaurant state"},
                rest_zip: {
                    required: "please enter zip code",
                },
                delivery_radius: {
                    required: "please enter delivery radius",
                },
                order_minimum: {
                    required: "please enter minimum order ammount",
                },
                tax_percent: {
                    required: "please enter sales text percentage",
                },
                delivery_charges: {
                    required: "please enter delivery charges ",
                    minlength: "please enter a valid state"

                },
                time_zone: {
                    required: "please select restaurant time zone",
                    min: "please select restaurant time zone",
                },
            },
            errorElement: "div",
            errorClass: "alert-error",
        });
    });



</script>



<?


$errMessage = '';
include "includes/resturant_header.php";
$catid = $mRestaurantIDCP;

//////////////////////////////////////////////////////////////////////////////////////////////////////////

$myimage = new ImageSnapshot; //new instance
$myimage2 = new ImageSnapshot; //new instance

if (isset($_FILES['userfile']))
    $myimage->ImageField = $_FILES['userfile']; //uploaded file array
if (isset($_FILES['userfile2']))
    $myimage2->ImageField = $_FILES['userfile2']; //uploaded file array

function GetFileExt($fileName) {
    $ext = substr($fileName, strrpos($fileName, '.') + 1);
    $ext = strtolower($ext);
    return $ext;
}

if (!empty($_POST)) {
    extract($_POST);
} else if (!empty($HTTP_POST_VARS)) {
    extract($HTTP_POST_VARS);
}

if (!empty($_GET)) {
    extract($_GET);
} else if (!empty($HTTP_GET_VARS)) {
    extract($HTTP_GET_VARS);
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_POST['submit'])) {
	Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Posted Array:'.print_r($_POST,true), 'restaurant', 1);
    $restQry = mysql_query("select name from resturants where name='$catname' AND id!='$catid'");
    $restRs = mysql_num_rows($restQry);
    if ($restRs > 0)
        $rest_exist = 1;

    if ($license_key == -1) {
        $license_key = $rest_license_key;
    }
    $errMessage = "";

    // check if chargify_subscription_id already taken
    $restRs1 = 0;
    if (!empty($chargify_subscription_id)) {
        $restQry1 = mysql_query("SELECT COUNT(*) AS total FROM resturants WHERE chargify_subscription_id='$chargify_subscription_id' AND id!='$catid'");
        $restRs1 = mysql_fetch_object($restQry1);
        $restRs1 = $restRs1->total;
    }

    if ($catname == '') {
        $errMessage = "Please Enter Restaurant Name";
    } else if ($email == '') {
        $errMessage = "Please enter email address";
    } else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
        $errMessage = "Please enter email address in correct format";
    } else if ($phone == '') {
        $errMessage = "Please enter phone number";
    } else if ($fax == '') {
        $errMessage = "Please enter fax number";
    } else if (!empty($_FILES['userfile']['name']) && $myimage->ProcessImage() == false) {
        $errMessage = "Pleaser Enter Valid Logo";
    } else if (!empty($_FILES['userfile2']['name']) && $myimage2->ProcessImage() == false) {
        $errMessage = "Pleaser Enter Valid Logo Thumbnail";
    } else if ($rest_zip == '') {
        $errMessage = "Please enter resturant zip code";
    } else if ($delivery_radius == '') {
        $errMessage = "Please enter delivery radius for resturant";
    } else if ($order_minimum == '') {
        $errMessage = "Please enter minimum order ammount";
    } else if ($tax_percent == '') {
        $errMessage = "Please enter sales tax percentage";
    } else if ($delivery_charges == '') {
        $errMessage = "Please enter delivery charges";
    } else if ($time_zone < 0) {
        $errMessage = "Please select resturant's time zone";
    } else if ($credit == '' && $cash == '' && $_SESSION['admin_type'] == 'admin') {
        $errMessage = "Please select payment method";
    } else if ($restRs1 > 0) {
        $errMessage = "Chargify Subscription ID not available";
    } else {
        if (!empty($credit) & !empty($cash)) {
            $payment_method = "both";
        } else if (!empty($credit)) {
            $payment_method = "credit";
        } else if (!empty($cash)) {
            $payment_method = "cash";
        }
        if (!empty($_FILES['userfile']['name'])) {
            $path = '../images/resturant_logos/';
            $exe = GetFileExt($_FILES['userfile']['name']);
            //$name = $_FILES['userfile']['name'];
            $name = "img_" . $catid . "_cat_logos." . $exe;
            $uploadfile = $path . $name;
            move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
            list($width, $height, $type, $attr) = getimagesize("$uploadfile");
            if ($height > $width) {
                $image = new SimpleImage();
                $image->load($uploadfile);
                $image->resizeToHeight(500);
                $image->save($uploadfile);
            } else {
                $image = new SimpleImage();
                $image->load($uploadfile);
                $image->resizeToWidth(600);
                $image->save($uploadfile);
            }
        } else {
            $name = $logo;
        }
        ////////////////////////////////////////////////////////////

        if (!empty($_FILES['userfile2']['name'])) {
            $path1 = '../images/logos_thumbnail/';
            $exe1 = GetFileExt($_FILES['userfile2']['name']);
            //$name1 = $_FILES['userfile2']['name'];
            $name1 = "img_" . $catid . "_cat_thumbnail." . $exe1;
            $uploadfile1 = $path1 . $name1;
            move_uploaded_file($_FILES['userfile2']['tmp_name'], $uploadfile1);
            list($width, $height, $type, $attr) = getimagesize("$uploadfile1");
            if ($height > $width) {
                $image = new SimpleImage();
                $image->load($uploadfile1);
                $image->resizeToHeight(500);
                $image->save($uploadfile1);
            } else {
                $image = new SimpleImage();
                $image->load($uploadfile1);
                $image->resizeToWidth(600);
                $image->save($uploadfile1);
            }
        } else {
            $name1 = $thumb;
        }
        ////////////////////////////////////////////////////////////

        if (!empty($_FILES['userfile3']['name'])) {
            $path3 = '../images/resturant_headers/';
            $exe3 = GetFileExt($_FILES['userfile3']['name']);
            $name3 = "img_" . $catid . "_cat_header." . $exe3;

            $uploadfile3 = $path3 . $name3;
            move_uploaded_file($_FILES['userfile3']['tmp_name'], $uploadfile3);
        } else {
            $name3 = $_REQUEST['header_images'];
        }
        ///////////////////////////////////////////////////////////

        $homefeatute = 0;
        if ($rest_open_close == '') {
            $rest_open_close = 0;
        }
        $catname = trim($catname, " ");
        $rest_url_name = str_replace(" ", "_", $catname);
        $rest_url_name = strtolower($rest_url_name);

		// Get Chargify Product ID Starts Here // -- Gulfam
		$mChargify_Settings_ID = 0;
		$mResult = mysql_query("SELECT settings_id FROM chargify_products WHERE user_id=".addslashes($reseller));
		if (mysql_num_rows($mResult)>0)
		{
			$mRow = mysql_fetch_object($mResult);
			if (is_numeric($mRow->settings_id))
			{
				$mChargify_Settings_ID = $mRow->settings_id;
			}
		}
		
		// Get Chargify Product ID Ends Here // -- Gulfam
		
        if ($_SESSION['admin_type'] == 'admin') {
			$queryUpdate="UPDATE resturants 
					SET name= '" . prepareStringForMySQL(trim($catname)) . "'
						,email= '" . addslashes($email) . "'
						,fax= '" . addslashes($fax) . "'
						,phone= '" . addslashes($phone) . "'
						,logo= '$name'
						,optionl_logo='$name1'
						,delivery_charges=$delivery_charges
						,order_minimum=$order_minimum
						,tax_percent=$tax_percent
						,owner_id='" . addslashes($owner_name) . "'
						,license_id='" . $license_key . "'
						,header_image= '$name3'
						,time_zone_id = '$time_zone'
						,payment_method= '$payment_method'
						,announcement='" . $rest_announcements . "'
						,announce_status=$announce_status
						,rest_open_close =$rest_open_close
						,delivery_offer=$delivery_offer
						,voice_phone='$voice_phone'
						,phone_notification='$phone_notification_status'
						,rest_address= '$rest_address'
						,rest_city= '$rest_city'
						,rest_state= '$rest_state'
						,rest_zip= '$rest_zip'
						,delivery_radius='$delivery_radius'
						,delivery_option='$delivery_option'
						,facebookLink='$facebookLink'
						,chargify_product_id=".$mChargify_Settings_ID."
						,chargify_subscription_id='$chargify_subscription_id'
						,chargify_subscription_status='$chargify_subscription_status'
						,meta_keywords='" . addslashes(trim($meta_keywords)) . "'
						,meta_description='" . addslashes(trim($meta_description)) . "' 
						,yelp_review_request='" . addslashes(trim($yelp_review_request)) . "'
						,yelp_restaurant_url='" . addslashes(trim($yelp_restaurant_url)) . "'
						,premium_account='" . addslashes(trim($premium_account)) . "'
						,region='" . addslashes(trim($region)) . "'
					where id = $catid";
            mysql_query($queryUpdate);
			Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Update Restaurant:'.$queryUpdate, 'restaurant', 1);
			$queryAnalytics="UPDATE analytics 
					SET name= '" . addslashes(trim($catname)) . "'
                                                ,optionl_logo='$name1'
					where resturant_id = $catid";
            mysql_query($queryAnalytics);
			Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Updated Restaurant Analytics:'.$queryAnalytics, 'restaurant', 1);
            //When resturant has been created then the granted license status will become activated.
			$queryLicences="UPDATE licenses 
					SET status='activated'
						,resturant_id=" . $catid . "
						,activation_date= '" . time() . "' 
					where id =" . $license_key;
            mysql_query($queryLicences);
			Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Updated Restaurant Licences:'.$queryLicences, 'restaurant', 1);
        } else if ($_SESSION['admin_type'] == 'store owner' || $_SESSION['admin_type'] == 'reseller') {
			$queryUpdate="UPDATE resturants 
					SET name= '" . prepareStringForMySQL($catname) . "'
						,email= '" . addslashes($email) . "'
						,fax= '" . addslashes($fax) . "'
						,phone= '" . addslashes($phone) . "'
						,logo= '$name'
						,optionl_logo='$name1'
						,delivery_charges=$delivery_charges
						,order_minimum=$order_minimum
						,tax_percent=$tax_percent
						,header_image= '$name3'
						,time_zone_id = '$time_zone'
						,announcement='" . $rest_announcements . "'
						,announce_status=$announce_status
						,rest_open_close =$rest_open_close
						,delivery_offer=$delivery_offer
						,rest_address= '$rest_address'
						,rest_city= '$rest_city'
						,rest_state= '$rest_state'
						,rest_zip= '$rest_zip'
						,delivery_radius='$delivery_radius'
						,delivery_option='$delivery_option'
						,facebookLink='$facebookLink'
						,meta_keywords='" . addslashes(trim($meta_keywords)) . "'
						,meta_description='" . addslashes(trim($meta_description)) . "'
						,region='" . addslashes(trim($region)) . "'
					where id = $catid";
            mysql_query($queryUpdate);
			Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Updated Restaurant - Reseller:'.$queryUpdate, 'restaurant', 1);
			$queryAnalytics="UPDATE analytics 
					SET name= '" . prepareStringForMySQL($catname) . "'
						,optionl_logo='$name1'
					where resturant_id = $catid";
            mysql_query($queryAnalytics);
			Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Updated Restaurant Analytics - Reseller:'.$queryAnalytics, 'restaurant', 1);
        }
	$queryResellerClient="UPDATE reseller_client SET reseller_client.firstname=(SELECT firstname FROM users where users.id = ".$owner_name."),reseller_client.lastname=(SELECT lastname FROM users where users.id = ".$owner_name."),reseller_client.restaurant_count=(SELECT count(name) FROM resturants where resturants.owner_id = ".$owner_name.") Where reseller_client.client_id=".$owner_name;
	mysql_query($queryResellerClient);
    Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Updated Reseller Clients:'.$queryResellerClient, 'restaurant', 1);
	if(isset($region) && $region == "0"){
            mysql_query("UPDATE resturants SET payment_gateway='authoriseDotNet'
					where id = $catid"
            );
        }
        $addresslink = str_replace(' ', '+', $rest_address . " " . $rest_city . " " . $rest_state);
        $result = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $addresslink . '&sensor=false');
        $array = (json_decode($result, true));
        if (!empty($array['results'][0]['geometry']['location']['lat']))
        {
            $lat = $array['results'][0]['geometry']['location']['lat'];
            $long = $array['results'][0]['geometry']['location']['lng'];

            $check_restid = mysql_fetch_object(mysql_query("select rest_id from rest_langitude_latitude where rest_id = $catid"));
            if(!empty($check_restid))
            {
                 mysql_query("UPDATE rest_langitude_latitude SET rest_id = '".$catid."', rest_latitude='".$lat."', rest_longitude= '".$long."' where rest_id = $catid");
            }
            else
            {
                 mysql_query("Insert into rest_langitude_latitude SET rest_id = '".$catid."', rest_latitude='".$lat."', rest_longitude= '".$long."'");
            }
        }

        if($premium_account!=$_POST['hdnpremium'])
        {

            $getSrid = mysql_fetch_object(mysql_query("select srid,premium_account,owner_id from resturants where id = $catid"));
            //echo "select chargify_subcription_id from users where id = ( select reseller_id from reseller_client where client_id = $getSrid->owner_id)";
            $getResellerID = mysql_fetch_object(mysql_query("select chargify_subcription_id from users where id = ( select reseller_id from reseller_client where client_id = $getSrid->owner_id)"));
            $getOwnerEmail = mysql_fetch_object(mysql_query("Select email from users where id = '".$owner_name."'"));

            $quantityPremium = $chargify->getallocationQuantity($getResellerID->chargify_subcription_id,1);
            $quantityStandard = $chargify->getallocationQuantity($getResellerID->chargify_subcription_id,0);
            //print_r($quantity);
			Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Calling multipleAllocations:subscriptionID:'.$getResellerID->chargify_subcription_id.' qtyPremium:'.$quantityPremium.' qtyStandard:'.$quantityStandard.' premium_account:'.$getSrid->premium_account, 'restaurant', 1);
            $chargify->multipleAllocation($getResellerID->chargify_subcription_id,$quantityPremium,$quantityStandard,$getSrid->premium_account);

            $srid = $getSrid->srid;
            //echo $srid;
            $mURL = "https://reputation-intelligence-api.vendasta.com/api/v2/account/convertSalesAccount/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=".$srid;
			
            if($premium_account == 1)
            {
				Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Calling Vandesta for premium account URL:'.$mURL, 'restaurant', 1);
                $parameters = "email=".$getOwnerEmail->email;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $mURL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);

                $mResult = curl_exec($ch);
                curl_close($ch);
                unset($ch);
                //$mResult= json_decode($mResult);
                
                
            }
            else
            {
                $cntry=$_POST['region'];
                if($cntry=='0'){$cntry="GB";}else if($cntry=='1'){$cntry="US";} else if($cntry=='2'){$cntry="CA";}else{$cntry="US";}
                $demoAccountFlag = "true";
				Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Calling Cancel Vandesta srid:'.$srid, 'restaurant', 1);
                $chargify->cancelVendesta($srid);
                mysql_query("update resturants set srid = '' where id ".$catid."");
				Log::write('Edit Restaurant - tab_edit_restaurant_radius.php', 'Calling Cancel Vandesta premium catname:'.$catname, 'restaurant', 1);
                $srid = $chargify->createVendestaPremium($catname,$cntry,$rest_address,$rest_city,$rest_state,$rest_zip,$demoAccountFlag,$phone,$getOwnerEmail->email);
                
		
		if(!empty($srid))
                {
                    mysql_query("UPDATE resturants SET srid='".$srid."' where id = $catid");
                }
            }
        }
        $_GET['cid'] = $catid;
        unset($_SESSION['restaurant_detail']);
    } //end else 
	$Objrestaurant= $Objrestaurant->getDetail($mRestaurantIDCP);
} //end submit2		
////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>


<div id="main_heading">
    <span>Edit Restaurant</span>
</div>
<? if ($errMessage != "") { ?><div class="alert-error"><?= $errMessage ?></div> <? } ?> 
<div id="AdminLeftConlum">
    <form name="frmedit" id="frmedit"  action="" method="post" enctype="multipart/form-data"   >
        <table width="100%" border="0" cellpadding="4" cellspacing="0">
            <tr align="left" valign="top">
                <td width="76">&nbsp;</td>
                <td width="1052"><strong>Restaurant Name</strong><br />
                    <textarea name="catname" cols="35" id="catname" style="font-size:18px; font-family:Arial;"><?= stripslashes(stripcslashes($Objrestaurant->name)) ?>
                    </textarea></td>
            </tr>
            <?
            if ($_SESSION['admin_type'] == 'admin') {
                $client_id = $Objrestaurant->owner_id;
                $reseller_sql = "SELECT reseller_id FROM reseller_client WHERE client_id = '" . $client_id . "' ";
                $reseller_qry = mysql_query($reseller_sql);
                $reseller_rs = mysql_fetch_array($reseller_qry);
                ?>
                <tr align="left" valign="top"> 
                    <td width="76"></td>
                    <td><strong>Reseller Name:</strong><br />
                        <select name="reseller" id="reseller" style="width:270px;" onChange="getclients(this.value, 'edit_resturant')">
                            <option value="-1">Select Reseller</option>
                            <?= resellers_drop_down($reseller_rs['reseller_id']) ?>
                        </select> </td>
                </tr>
                <tr align="left" valign="top"> 
                    <td width="76"></td>
                    <td ><strong>Restaurant Owner's Name:</strong><br />
                        <div id="client_div">
                            <select name="owner_name" id="owner_name" style="width:270px;">
                                <option value="-1">Select Restaurant Owner</option>
                                <?= client_drop_down($Objrestaurant->owner_id, $reseller_rs[reseller_id]) ?>
                            </select> 
                        </div>
                    </td>
                </tr>
                <tr align="left" valign="top"> 
                    <td width="76"></td>
                    <td ><strong>Licenses:</strong><br />
                        <div id="licenses_div">
                            <select name="license_key" id="license_key" style="width:270px;">
                                <option value="-1">Select License Key</option>
                                <?= licenses_drop_down($Objrestaurant->license_id, $reseller_rs[reseller_id]) ?>
                            </select>
                        </div>
                    </td>
                </tr>
            <? } ?>
            <tr align="left" valign="top"> 
                <td width="76"></td>
                <td ><strong>Resturant Licenses Key:</strong><br />
                    <?
                    $rest_license_key_sql_str = "SELECT license_key FROM licenses WHERE id = $Objrestaurant->license_id";
                    $rest_license_key_qry = mysql_query($rest_license_key_sql_str);
                    $rest_license_key_rs = mysql_fetch_array($rest_license_key_qry);
                    echo $rest_license_key_rs['license_key'];
                    ?>
                    <input type="hidden" name="rest_license_key" id="rest_license_key" value="<?= $Objrestaurant->license_id; ?>"  />

                </td>
            </tr>





            <tr align="left" valign="top"> 
                <td width="76"></td>
                <td><strong>Email:</strong><br />
                    <input name="email" type="text" size="40" value="<?= stripslashes(stripcslashes($Objrestaurant->email)) ?>" id="email" /> </td>
            </tr>
            <!--<tr align="left" valign="top"> 
              <td width="76"></td>
              <td><strong>Enable PDF attachment with email:</strong><br />
              <input name="pdf_attachment" type="checkbox" size="40" value="1" id="pdf_attachment" <? if ($Objrestaurant->pdf_attachment_status == 1) {
                        echo "checked";
                    } ?> /> </td>
            </tr>-->
             <tr align="left" valign="top">
                <td></td>
                <td><strong>Regional Settings:</strong><br />					
					<?php
					$mRegionUSA = "";
					$mRegionUK = "";
					$mRegionCanada = "";
					if ($Objrestaurant->region == "0")
					{
						$mRegionUK = "checked='checked'";
					}
					else if ($Objrestaurant->region == "1")
					{
						$mRegionUSA = "checked='checked'";
					}
					else if ($Objrestaurant->region == "2")
					{
						$mRegionCanada = "checked='checked'";
					}
					else
					{
						$mRegionUSA = "checked='checked'";
					}
					?>
                    <input name="region" type="radio" value="1" onclick="loadTimeZoneUS()" id="region" <?=$mRegionUSA?>>USA&nbsp;&nbsp;
                    <input name="region" type="radio" value="0" onclick="loadTimeZoneUK()" id="region" <?=$mRegionUK?>>UK&nbsp;&nbsp;
                    <input name="region" type="radio" value="2" onclick="loadTimeZoneCanada()" id="region" <?=$mRegionCanada?>>Canada 
                </td>
            </tr>
            <tr align="left" valign="top"> 
                <td width="76"></td>
                <td><strong>Phone:</strong><br />
                    <input name="phone" type="text" size="40" value="<?= stripslashes(stripcslashes($Objrestaurant->phone)) ?>" id="phone" /> </td>
            </tr>
            <tr align="left" valign="top"> 
                <td width="76"></td>
                <td><strong>Fax:</strong><br />
                    <input name="fax" type="text" size="40" value="<?= stripslashes(stripcslashes($Objrestaurant->fax)) ?>" id="fax" /> </td>
            </tr>



 <tr align="left" valign="top">
                <td></td>
                <td><strong>Resturant Address:</strong><br />
                    <input name="rest_address" type="text" size="40" id="rest_address" value="<?= $Objrestaurant->rest_address ?>">            
                </td>
            </tr>
            <tr align="left" valign="top"> 
                <td></td>
                <td><strong>Resturant City:</strong><br />
                    <input name="rest_city" type="text" size="40" id="rest_city" value="<?= $Objrestaurant->rest_city ?>">            
                </td>
            </tr>
            <tr align="left" valign="top"> 
                <td></td>
                <td><strong><span id="spnSP">Resturant State:</span></strong><br />
                    <input name="rest_state" type="text" size="40" id="rest_state" value="<?= $Objrestaurant->rest_state ?>">            
                </td>
            </tr>
            <tr align="left" valign="top"> 
                <td></td>
                <td><strong><span id="spnZP">Resturant Zip Code:</span></strong><br />
                    <input name="rest_zip" type="text" size="40" id="rest_zip" value="<?= $Objrestaurant->rest_zip ?>">            
                </td>
            </tr>
            <tr align="left" valign="top"> 
                <td></td>
                <td><strong>Delivery Options: </strong><br />
                    <input type="radio" name="delivery_option" value="1"  <?= $Objrestaurant->delivery_option == 'radius' ? 'checked' : '' ?>  onclick="showdeliveryoption(1)"/>Delivery Radius
                    <input type="radio" name="delivery_option" value="2"   <?= $Objrestaurant->delivery_option == 'delivery_zones' ? 'checked' : '' ?>  onclick="showdeliveryoption(2)"/>Custom Delivery Zone
                </td>
            </tr>

            <tr align="left" valign="top" id="delivery_radius" <?= $Objrestaurant->delivery_option == 'delivery_zones' ? 'class="hidden"' : '' ?>> 
                <td></td>
                <td><strong>Delivery Radius for Resturant:</strong><br />
                    <input name="delivery_radius" type="text" size="40" id="delivery_radius" value="<?= $Objrestaurant->delivery_radius ?>">&nbsp;(miles)            </td>
            </tr>
            <tr align="left" valign="top" id="delivery_zone" <?= $Objrestaurant->delivery_option == 'radius' ? 'class="hidden"' : '' ?>> 
                <td>&nbsp;</td>
                <td> <a href="ajax.php?mod=resturant&item=delivery_zone&cid=<?=$mRestaurantIDCP?>"  id="lnkdelivery_zones" rel="facebox"><img src="images/zones.png" title="Draw Delivery Zones"/></a>
                </td>
            </tr>

            <tr align="left" valign="top"> 
                <td></td>
                <td><strong>Time Zone:</strong><br />
                    <select name="time_zone" id="time_zone" style="width:270px;">
                        <option value="-1">Select Time Zone</option>
                       <?= get_timezone_drop_down($Objrestaurant->time_zone_id)?>
                    </select>
                </td>

            </tr>



            <tr align="left" valign="top">
                <td>&nbsp;</td>
                <td><strong>Optional Logo</strong><br> <font color="#666666"><!--(system will resize to 
                    500x500)--></font>
                    <input name="userfile" type="file" id="userfile">
                    <input type="hidden" name="logo" value="<?= $Objrestaurant->logo ?>">
                    <input type="hidden" name="thumb" value="<?= $Objrestaurant->optionl_logo ?>"></td>
            <input type="hidden" name="header_images" value="<?= $Objrestaurant->header_image ?>">
            </tr>
              <tr align="left" valign="top">
                <td>&nbsp;</td>
                <td><strong>Optional Logo Thumbnail</strong><br> <font color="#666666"><!--(system will
                    resize to 130x130)--></font>
                    <input name="userfile2" type="file" id="userfile2"></td>
            </tr>
            <tr align="left" valign="top">
                <td>&nbsp;</td>
                <td><strong>Header Image</strong><br> <font color="#666666"><!--(system will
                    resize to 130x130)--></font>
                    <input name="userfile3" type="file" id="userfile3"></td>
            </tr>


            <tr align="left" valign="top">
                <td></td>
                <td><strong>Order Minimum:</strong><br />
                    <input name="order_minimum" type="text" size="40" id="order_minimum" value="<?= $Objrestaurant->order_minimum ?>">            </td>
            </tr>
            <tr align="left" valign="top"> 
                <td></td>
                <td><strong>Sales tax % for Restaurant:</strong><br />
                    <input name="tax_percent" type="text" size="40" id="tax_percent" value="<?= $Objrestaurant->tax_percent ?>">            </td>
            </tr>
            <tr align="left" valign="top"> 
                <td></td>
                <td><strong>Delivery Charges:</strong><br />
                    <input name="delivery_charges" type="text" size="40" id="delivery_charges" value="<?= $Objrestaurant->delivery_charges ?>">            </td>
            </tr> 
            <tr align="left" valign="top"> 
                <td></td>
                <td><strong>Announcements:</strong><br />
                    <input name="rest_announcements" type="text" size="40" id="rest_announcements" value="<?= $Objrestaurant->announcement ?>">            </td>
            </tr>
<? if ($_SESSION['admin_type'] == 'admin') { ?>
                <tr align="left" valign="top"> 
                    <td></td>
                    <td><strong>Payment Mathod:</strong><br />
                        <input name="credit" type="checkbox" value="credit"  id="payment_method" <? if ($Objrestaurant->payment_method == "credit" || $Objrestaurant->payment_method == "both") {
        echo "checked";
    } ?>>Credit Card            &nbsp;&nbsp;
                        <input name="cash" type="checkbox" value="cash"  id="payment_method" <? if ($Objrestaurant->payment_method == "cash" || $Objrestaurant->payment_method == "both") {
        echo "checked";
    } ?>>Cash
                    </td>
                </tr>      	
<? } ?>
            <tr align="left" valign="top"> 
                <td></td>
                <td><strong>Announcement status:</strong><br />
                    <input name="announce_status" type="radio" value="1"  id="announce_status" <? if ($Objrestaurant->announce_status == "1") {
    echo "checked";
} ?>>Activate            &nbsp;&nbsp;
                    <input name="announce_status" type="radio" value="0"  id="announce_status" <? if ($Objrestaurant->announce_status == "0") {
    echo "checked";
} ?>>Deactivate
                </td>
            </tr>  
            <tr align="left" valign="top"> 
                <td></td>
                <td><strong>Allow Delivery Option:</strong><br />
                    <input name="delivery_offer" type="radio" value="1"  id="delivery_offer" <? if ($Objrestaurant->delivery_offer == "1") {
    echo "checked";
} ?>>Yes            &nbsp;&nbsp;
                    <input name="delivery_offer" type="radio" value="0"  id="delivery_offer" <? if ($Objrestaurant->delivery_offer == "0") {
    echo "checked";
} ?>>No
                </td>
            </tr>  
            <tr align="left" valign="top"> 
                <td></td>
                <td><strong>Resturant Status:</strong><br />
                    <input name="rest_open_close" type="radio" value="1"  id="rest_open_close" <? if ($Objrestaurant->rest_open_close == "1") {
    echo "checked";
} ?>>Open            &nbsp;&nbsp;
                    <input name="rest_open_close" type="radio" value="0"  id="rest_open_close" <? if ($Objrestaurant->rest_open_close == "0") {
    echo "checked";
} ?>>Close
                </td>
            </tr>
<? if ($_SESSION['admin_type'] == 'admin') { ?>
                <tr align="left" valign="top" style="display: none;"> 
                    <td width="76"></td>
                    <td><strong>Voice Confirmation Phone:</strong><br />
                        <input name="voice_phone" type="text" size="40" value="<?= stripslashes(stripcslashes($Objrestaurant->voice_phone)) ?>" id="voice_phone" /> </td>
                </tr>
				<tr style="height: 0px;"><td colspan="2"</tr>
                <tr align="left" valign="top"> 
                    <td></td>
                    <td>
                        <strong>Voice Confirmation Status:</strong><br />
                        <input name="phone_notification_status" type="radio" value="1"  id="phone_notification_status" <? if ($Objrestaurant->phone_notification == "1") {
        echo "checked";
    } ?>>On            &nbsp;&nbsp;
                        <input name="phone_notification_status" type="radio" value="0"  id="phone_notification_status" <? if ($Objrestaurant->phone_notification == "0") {
        echo "checked";
    } ?>>Off
                    </td>
                </tr>
                <tr align="left" valign="top">
                    <td></td>
                    <td>
                        <strong>Chargify Subscription Status:</strong><br />
                        <input name="chargify_subscription_status" type="radio" value="1"  id="chargify_subscription_status1" <? if ($Objrestaurant->chargify_subscription_status == "1") {
        echo "checked";
    } ?>>On            &nbsp;&nbsp;
                        <input name="chargify_subscription_status" type="radio" value="0"  id="chargify_subscription_status2" <? if ($Objrestaurant->chargify_subscription_status == "0") {
        echo "checked";
    } ?>>Off

                        <div id="chargify_id_container" style="display: <? if ($Objrestaurant->chargify_subscription_status == "1") {
        echo "block";
    } else {
        echo "none";
    } ?>;">
                            <strong>Chargify Subscription ID:</strong><br />
                        <input name="chargify_subscription_id" type="text" size="40" value="<?= $Objrestaurant->chargify_subscription_id ?>" id="chargify_subscription_id" /> </td>
                    </div>
                <script type="text/javascript">
                    jQuery(function() {
                        jQuery("#chargify_subscription_status1, #chargify_subscription_status2").click(function() {
                            var id = $(this).attr("id");
                            if (id == "chargify_subscription_status1") {
                                $("#chargify_id_container").slideDown();
                            } else {
                                $("#chargify_id_container").slideUp();
                            }
                        });
                    });
                </script>
                </td>
                </tr>
                <tr align="left" valign="top">
                    <td></td>
                    <td colspan="2" style="font-size: 12px;">
                        <strong>Premium Account Status:</strong><br />
                        <input name="premium_account" type="radio" value="1"  id="premium_account1" <? if ($Objrestaurant->premium_account == "1") {
        echo "checked";
    } ?>>On            &nbsp;&nbsp;
                        <input name="premium_account" type="radio" value="0"  id="premium_account2" <? if ($Objrestaurant->premium_account == "0") {
        echo "checked";
    } ?>>Off

                        <div id="yelp_settings" style="display: <? if ($Objrestaurant->premium_account == "1") {
        echo "block";
    } else {
        echo "none";
    } ?>;">
                            <strong>Yelp Review Status:</strong><br />
                            <input name="yelp_review_request" type="radio" value="1"  id="yelp_review_request1" <? if ($Objrestaurant->yelp_review_request == "1") {
        echo "checked";
    } ?>>On            &nbsp;&nbsp;
                            <input name="yelp_review_request" type="radio" value="0"  id="yelp_review_request2" <? if ($Objrestaurant->yelp_review_request == "0") {
        echo "checked";
    } ?>>Off

                            <div id="yelp_restaurant_url_container" style="display: <? if ($Objrestaurant->yelp_review_request == "1") {
        echo "block";
    } else {
        echo "none";
    } ?>;">
                                <strong>Yelp Restaurant URL:</strong><br />
                                <input name="yelp_restaurant_url" type="text" size="40" value="<?= $Objrestaurant->yelp_restaurant_url ?>" id="yelp_restaurant_url" /> </td>
                            </div>
                            <script type="text/javascript">
                                jQuery(function() {
                                    jQuery("#yelp_review_request1, #yelp_review_request2").click(function() {
                                        var id = $(this).attr("id");
                                        if (id == "yelp_review_request1") {
                                            $("#yelp_restaurant_url_container").slideDown();
                                        } else {
                                            $("#yelp_restaurant_url_container").slideUp();
                                        }
                                    });
                                    jQuery("#premium_account1, #premium_account2").click(function() {
                                        var id = $(this).attr("id");
                                        if (id == "premium_account1") {
                                            $("#yelp_settings").slideDown();
                                        } else {
                                            $("#yelp_settings").slideUp();
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </td>
                </tr>
<? } ?>


            <tr align="left" valign="top"> 
                <td>&nbsp;</td>
                <td>Facebook Link<br /><input name="facebookLink" type="text" size="40" id="facebookLink" value="<?= $Objrestaurant->facebookLink ?>" /></td>
            </tr>
            <tr align="left" valign="top">
                <td width="76">&nbsp;</td>
                <td width="1052"><strong>META Keywords</strong><br />
                    <textarea name="meta_keywords" cols="35" id="meta_keywords" style="font-size:18px; font-family:Arial;"><?= stripslashes(stripcslashes($Objrestaurant->meta_keywords)) ?>
                    </textarea></td>
            </tr>
            <tr align="left" valign="top">
                <td width="76">&nbsp;</td>
                <td width="1052"><strong>META Description</strong><br />
                    <textarea name="meta_description" cols="35" id="meta_description" style="font-size:18px; font-family:Arial;"><?= stripslashes(stripcslashes($Objrestaurant->meta_description)) ?>
                    </textarea></td>
            </tr>
            <tr align="left" valign="top">
                <td>&nbsp;</td>
                <td>
                    <input type="submit" name="submit" id="submit" value="submit" style="display:none" />

                    <input type="hidden"  id="hzone1"  value="<?= $Objrestaurant->zone1 ?>"/>
                    <input type="hidden"  id="hzone1_delivery_charges" value="<?= $Objrestaurant->zone1_delivery_charges ?>"/>
                    <input type="hidden"  id="hzone1_min_total" value="<?= $Objrestaurant->zone1_min_total ?>"/>
                    <input type="hidden"  id="hzone2" value="<?= $Objrestaurant->zone2 ?>"/>
                    <input type="hidden"  id="hzone2_delivery_charges" value="<?= $Objrestaurant->zone2_delivery_charges ?>"/>
                    <input type="hidden"  id="hzone2_min_total" value="<?= $Objrestaurant->zone2_min_total ?>"/>
                    <input type="hidden"  id="hzone3" value="<?= $Objrestaurant->zone3 ?>"/>
                    <input type="hidden"  id="hzone3_delivery_charges" value="<?= $Objrestaurant->zone3_delivery_charges ?>"/>
                    <input type="hidden"  id="hzone3_min_total" value="<?= $Objrestaurant->zone3_min_total ?>"/>
                    <input type="hidden"  id="hzone1_coordinates" value="<?= $Objrestaurant->zone1_coordinates ?>"/>
                    <input type="hidden"  id="hzone2_coordinates" value="<?= $Objrestaurant->zone2_coordinates ?>"/>
                    <input type="hidden"  id="hzone3_coordinates" value="<?= $Objrestaurant->zone3_coordinates ?>"/>
		    <input type="hidden"  name ="hdnpremium" id="hdnpremium" value="<?= $Objrestaurant->premium_account ?>"/>


                    <input type="button" name="btnSave" value="Save Changes"  onclick="showLocation();
                            " />
                </td>
            </tr>
        </table>
    </form>
</div>  
<script language="JavaScript">
<!--
    function calcHeight(frame_name)
    {   //find the height of the internal page
        var the_height =
                document.getElementById(frame_name).contentWindow.
                document.body.scrollHeight;

        //change the height of the iframe
        document.getElementById(frame_name).height =
                the_height;
    }
//-->
</script>
<!--Saad Change 12-Sept-2014-->
<div id="AdminRightConlum" style="padding:5px; min-height:560px;background-image:url('images/bg.jpg')">
<!--    <iframe src="admin_contents/resturants/tab_resturant_businesshours.php?catid=<= catid ?>&<=ime()?>" frameborder="0"  width="100%" scrolling="no" id="iframe1" onload="calcHeight('iframe1')"></iframe>-->
</div>
<br class="clearfloat" />



<script type="text/javascript">
    function saveZones() {

        var zone1 = 0;
        var zone1_delivery_charges = $("#zone1_delivery_charges").val();
        var zone1_min_total = $("#zone1_min_total").val();

        var zone2 = 0;
        var zone2_delivery_charges = $("#zone2_delivery_charges").val();
        var zone2_min_total = $("#zone2_min_total").val();

        var zone3 = 0;
        var zone3_delivery_charges = $("#zone3_delivery_charges").val();
        var zone3_min_total = $("#zone3_min_total").val();

        var zone1_coordinates, zone2_coordinates, zone3_coordinates;
        if ($("#zone1").is(':checked')) {
            zone1 = 1;
            if (isNaN(parseFloat(zone1_delivery_charges))) {
                alert("please enter zone 1 delivery charges");
                return false;
            }
            if (isNaN(parseFloat(zone1_min_total))) {
                alert("please enter zone 1 minimum total ");
                return false;
            }

        }

        if ($("#zone2").is(':checked')) {
            zone2 = 1;
            if (isNaN(parseFloat(zone2_delivery_charges))) {
                alert("please enter zone 2 delivery charges");
                return false;
            }
            if (isNaN(parseFloat(zone2_min_total))) {
                alert("please enter zone 2 minimum total");
                return false;
            }

        }


        if ($("#zone3").is(':checked')) {
            zone3 = 1;

            if (isNaN(parseFloat(zone3_delivery_charges))) {
                alert("please enter zone 3 delivery charges");
                return false;
            }
            if (isNaN(parseFloat(zone3_min_total))) {
                alert("please enter zone 3 minimum total");
                return false;
            }

        }
        var zone1_coordinates = Zone1.Coordinates();
        var zone2_coordinates = Zone2.Coordinates();
        var zone3_coordinates = Zone3.Coordinates();
       
        $.post("ajax.php?mod=resturant&item=delivery_zone&savedata=1&cid=<?=$mRestaurantIDCP?>", {zone1: zone1, zone1_delivery_charges: zone1_delivery_charges, zone1_min_total: zone1_min_total, zone2: zone2, zone2_delivery_charges: zone2_delivery_charges, zone2_min_total: zone2_min_total, zone3: zone3, zone3_delivery_charges: zone3_delivery_charges, zone3_min_total: zone3_min_total, zone1_coordinates: zone1_coordinates, zone2_coordinates: zone2_coordinates, zone3_coordinates: zone3_coordinates}, function(data) {
            $("#hzone1").val(zone1);
            $("#hzone1_delivery_charges").val(zone1_delivery_charges);
            $("#hzone1_min_total").val(zone1_min_total);
            $("#hzone1_coordinates").val(zone1_coordinates);

            $("#hzone2").val(zone2);
            $("#hzone2_delivery_charges").val(zone2_delivery_charges);
            $("#hzone2_min_total").val(zone2_min_total);
            $("#hzone2_coordinates").val(zone2_coordinates);

            $("#hzone3").val(zone3);
            $("#hzone3_delivery_charges").val(zone3_delivery_charges);
            $("#hzone3_min_total").val(zone3_min_total);
            $("#hzone3_coordinates").val(zone3_coordinates);
            alert("zones saved");

        }

        );
    }

    $(document).ready(function() {
        geocoder = new google.maps.Geocoder();
        var restaurant_location = $("#rest_address").val() + " " + $("#rest_city").val() + " " + $("#rest_state").val();
        geocoder.geocode({'address': restaurant_location}, function(results, status) {
            if (status != google.maps.GeocoderStatus.OK)
            {
                alert("Sorry, we were unable to recognize the resturant address");
                return false;
            }

            else
            {
                //location1 = {lat: response.Placemark[0].Point.coordinates[1], lon: response.Placemark[0].Point.coordinates[0], address: response.Placemark[0].address};
                //$("#lnkdelivery_zones").attr('href','ajax.php?mod=resturant&item=delivery_zone&latitude='+location1.lat +'&longitude='+location1.lon)
            }
        });

        //Saad Change 12-Sept-2014
        fillBusinessHours("GetBusinessHours",<?= $catid ?>, 0, 0);
    });

    //Saad Change 12-Sept-2014
    function fillBusinessHours(action, restId, dayId, bHId){
        if (typeof action !== 'undefined') {
            var url = "admin_contents/resturants/tab_resturant_businesshours.php?&action="+action+"&catid="+restId+"&dayid="+dayId+"&deleteid="+bHId;
            $.ajax({
                type: "GET",
                url: url,
                cache:false,
                success: function(html){
                    $("#AdminRightConlum").html(html);
                }
            });
        }else{
            $("#AdminRightConlum").html("Action is missing from url, some one definetly messed with it.");
            return;
        }
    }

    function updateBuisnessHours(){
    
        var postData = $('#business_hrs').serialize();
        var formURL = "admin_contents/resturants/tab_resturant_businesshours.php";
        $.ajax(
        {
            url : formURL,
            type: "POST",
            data : postData,
            success:function(html)
            {
                $("#AdminRightConlum").html(html);
            }
        });
        
        return false;
    }


</script>
<script type="text/javascript">
    /* Developed by: Abhinay Rathore [web3o.blogspot.com] */
//Global variables 
    var global = this;
    var map;

    var PolygonMarkers = []; //Array for Map Markers 
    var PolygonPoints = []; //Array for Polygon Node Markers 
    var bounds = new google.maps.LatLngBounds; //Polygon Bounds
    var Polygon; //Polygon overlay object 
    var polygon_resizing = false; //To track Polygon Resizing 

//Polygon Marker/Node icons 
//var redpin = new google.maps.Icon(); //Red Pushpin Icon
    image = "http://maps.google.com/mapfiles/ms/icons/red-pushpin.png";
//redpin.iconSize = new google.maps.Size(32, 32);
//redpin.iconAnchor = new google.maps.Point(10, 32);
//var bluepin = new google.maps.Icon(); //Blue Pushpin Icon
    image = "http://maps.google.com/mapfiles/ms/icons/blue-pushpin.png";
//bluepin.iconSize = new google.maps.Size(32, 32);
//bluepin.iconAnchor = new google.maps.Point(10, 32);

    function initializeMap(latitude, longitude) { //Initialize google.maps.oogle Map
        if (google.maps.BrowserIsCompatible()) {
            map = new google.maps.Map2(document.getElementById("map_canvas")); //New google.maps.Map object
            console.log(latitude);
            console.log(longitude);
            var center = new google.maps.LatLng(latitude, longitude);

            map.setCenter(center, 13);
            var marker = new google.maps.Marker(center, {draggable: false});
            map.addOverlay(marker);
            var ui = new google.maps.MapUIOptions(); //Map UI options
            ui.maptypes = {normal: true, satellite: true, hybrid: true, physical: false}
            ui.zoom = {scrollwheel: true, doubleclick: true};
            ui.controls = {largemapcontrol3d: true, maptypecontrol: true, scalecontrol: true};
            map.setUI(ui); //Set Map UI options 

            //Add Shift+Click event to add Polygon markers 
            google.maps.Event.addListener(map, "click", function(overlay, point, overlaypoint) {
                var p = (overlaypoint) ? overlaypoint : point;
                //Add polygon marker if overlay is not an existing marker and shift key is pressed 
                if (global.shiftKey && !checkPolygonMarkers(overlay)) {
                    addMarker(p);
                }
            });
        }
    }

// Adds a new Polygon boundary marker 
    function addMarker(point) {

        var markerOptions = {icon: bluepin, draggable: true};
        var marker = new google.maps.Marker(point, markerOptions);
        PolygonMarkers.push(marker); //Add marker to PolygonMarkers array 
        map.addOverlay(marker); //Add marker on the map 
        google.maps.Event.addListener(marker, 'dragstart', function() { //Add drag start event
            marker.setImage(redpin.image);
            polygon_resizing = true;
        });
        google.maps.Event.addListener(marker, 'drag', function() {
            drawPolygon();
        }); //Add drag event
        google.maps.Event.addListener(marker, 'dragend', function() {   //Add drag end event
            marker.setImage(bluepin.image);
            polygon_resizing = false;
            drawPolygon();
            fitPolygon();
        });
        google.maps.Event.addListener(marker, 'click', function(point) { //Add Ctrl+Click event to remove marker
            if (global.ctrlKey) {
                removeMarker(point);
            }
        });
        drawPolygon();

//If more then 2 nodes then automatically fit the polygon 
        if (PolygonMarkers.length > 2)
            fitPolygon();
    }

// Removes a Polygon boundary marker 
    function removeMarker(point) {
        if (PolygonMarkers.length == 1) { //Only one marker in the array 
            map.removeOverlay(PolygonMarkers[0]);
            map.removeOverlay(PolygonMarkers[0]);
            PolygonMarkers = [];
            if (Polygon) {
                map.removeOverlay(Polygon)
            }
            ;
        }
        else //More then one marker 
        {
            var RemoveIndex = -1;
            var Remove;
            //Search for clicked Marker in PolygonMarkers Array 
            for (var m = 0; m < PolygonMarkers.length; m++)
            {
                if (PolygonMarkers[m].getPoint().equals(point))
                {
                    RemoveIndex = m;
                    Remove = PolygonMarkers[m]
                    break;
                }
            }
            //Shift Array elemeents to left 
            for (var n = RemoveIndex; n < PolygonMarkers.length - 1; n++)
            {
                PolygonMarkers[n] = PolygonMarkers[n + 1];
            }
            PolygonMarkers.length = PolygonMarkers.length - 1 //Decrease Array length by 1 
            map.removeOverlay(Remove); //Remove Marker 
            drawPolygon(); //Redraw Polygon 
        }
    }

//Draw Polygon from the PolygonMarkers Array 
    function drawPolygon()
    {
        PolygonPoints.length = 0;
        for (var m = 0; m < PolygonMarkers.length; m++)
        {
            PolygonPoints.push(PolygonMarkers[m].getPoint()); //Add Markers to PolygonPoints node array 
        }
//Add first marker in the end to close the Polygon 
        PolygonPoints.push(PolygonMarkers[0].getPoint());
        if (Polygon) {
            map.removeOverlay(Polygon);
        } //Remove existing Polygon from Map 
        var fillColor = (polygon_resizing) ? 'red' : 'blue'; //Set Polygon Fill Color 
        Polygon = new google.maps.Polygon(PolygonPoints, '#FF0000', 2, 1, fillColor, 0.2); //New google.maps.Polygon object
        map.addOverlay(Polygon); //Add Polygon to the Map 
        var rows = [];
        $("cords").value = "";
        var len = PolygonPoints.length || 0;
        for (var i = 0; i < len; i++) {
            rows.push(PolygonPoints[i].y.toFixed(6) + ", " + PolygonPoints[i].x.toFixed(6));
        }
        $("#cords").html(rows.join('\n'));

        if (Polygon.containsLatLng(new google.maps.LatLng(location1.lat, location1.lon))) {
            $("#userVerification").html("Restaurant is inside the polygon region");
            $("#userVerification").addClass("msg_done");
            $("#userVerification").removeClass("msg_error");
        } else {
            $("#userVerification").html("Restaurant is out inside the polygon region");
            $("#userVerification").addClass("msg_error");
            $("#userVerification").removeClass("msg_done");

        }




//TO DO: Function Call triggered after Polygon is drawn 
    }

    google.maps.Polygon.prototype.containsLatLng = function(latLng) {
// Do simple calculation so we don't do more CPU-intensive calcs for obvious misses
        var bounds = this.getBounds();

        if (!bounds.containsLatLng(latLng)) {
            return false;
        }

// Point in polygon algorithm found at http://msdn.microsoft.com/en-us/library/cc451895.aspx
        var numPoints = this.getVertexCount();
        var inPoly = false;
        var i;
        var j = numPoints - 1;

        for (var i = 0; i < numPoints; i++) {
            var vertex1 = this.getVertex(i);
            var vertex2 = this.getVertex(j);

            if (vertex1.lng() < latLng.lng() && vertex2.lng() >= latLng.lng() || vertex2.lng() < latLng.lng() && vertex1.lng() >= latLng.lng()) {
                if (vertex1.lat() + (latLng.lng() - vertex1.lng()) / (vertex2.lng() - vertex1.lng()) * (vertex2.lat() - vertex1.lat()) < latLng.lat()) {
                    inPoly = !inPoly;
                }
            }

            j = i;
        }

        return inPoly;
    };
//Fits the Map to Polygon bounds 
    function fitPolygon() {
        bounds = Polygon.getBounds();
        map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds));
    }
//check is the marker is a polygon boundary marker 
    function checkPolygonMarkers(marker) {
        var flag = false;
        for (var m = 0; m < PolygonMarkers.length; m++) {
            if (marker == PolygonMarkers[m])
            {
                flag = true;
                break;
            }
        }
        return flag;
    }

//////////////////[ Key down event handler ]///////////////////// 
//Event handler class to attach events 
    var EventUtil = {
        addHandler: function(element, type, handler) {
            if (element.addEventListener) {
                element.addEventListener(type, handler, false);
            } else if (element.attachEvent) {
                element.attachEvent("on" + type, handler);
            } else {
                element["on" + type] = handler;
            }
        }
    };

// Attach Key down/up events to document 
    EventUtil.addHandler(document, "keydown", function(event) {
        keyDownHandler(event)
    });
    EventUtil.addHandler(document, "keyup", function(event) {
        keyUpHandler(event)
    });

//Checks for shift and Ctrl key press 
    function keyDownHandler(e)
    {
        if (!e)
            var e = window.event;
        var target = (!e.target) ? e.srcElement : e.target;
        if (e.keyCode == 16 && !global.shiftKey) { //Shift Key 
            global.shiftKey = true;
        }
        if (e.keyCode == 17 && !global.ctrlKey) { //Ctrl Key 
            global.ctrlKey = true;
        }
    }
//Checks for shift and Ctrl key release 
    function keyUpHandler(e) {
        if (!e)
            var e = window.event;
        if (e.keyCode == 16 && global.shiftKey) { //Shift Key 
            global.shiftKey = false;
        }
        if (e.keyCode == 17 && global.ctrlKey) { //Ctrl Key 
            global.ctrlKey = false;
        }
    }
</script>

<script type="text/javascript">


$(document).ready(function() 
{
	var region = <?php echo $Objrestaurant->region ?>;
	if(region==1)
	{

		$('#phone').unmask();
		$('#fax').unmask();
		$('#phone').mask('(999) 999-9999');
		$('#fax').mask('(999) 999-9999');
		$("#time_zone").children('option:contains("US")').show();
		$("#time_zone").children('option:contains("London")').hide();
		$("#time_zone").children('option:contains("Canada")').hide();
		$("#spnSP").text("Resturant State:");
		$("#spnZP").text("Resturant Zip Code:");
	}
	else if(region==0)
	{
		$('#phone').unmask();
		$('#fax').unmask();
	
		$('#phone').mask('(9999) 999-9999');
		$('#fax').mask('(9999) 999-9999');
	
		$("#time_zone").children('option:contains("US")').hide();
		$("#time_zone").children('option:contains("London")').show();
		$("#time_zone").children('option:contains("Canada")').hide();
		$("#spnSP").text("Resturant State:");
		$("#spnZP").text("Resturant Zip Code:");
	}
	else if(region==2)
	{

		$('#phone').unmask();
		$('#fax').unmask();
		$('#phone').mask('(999) 999-9999');
		$('#fax').mask('(999) 999-9999');
		$("#time_zone").children('option:contains("US")').hide();
		$("#time_zone").children('option:contains("London")').hide();
		$("#time_zone").children('option:contains("Canada")').show();
		$("#spnSP").text("Resturant Province:");
		$("#spnZP").text("Resturant Postal Code:");
	}
	else
	{
		$('#phone').unmask();
		$('#fax').unmask();
		$('#phone').mask('(999) 999-9999');
		$('#fax').mask('(999) 999-9999');
		$("#time_zone").children('option:contains("US")').show();
		$("#time_zone").children('option:contains("London")').hide();
		$("#time_zone").children('option:contains("Canada")').hide();
		$("#spnSP").text("Resturant State:");
		$("#spnZP").text("Resturant Zip Code:");
	}
});

function loadTimeZoneUS()
{
	$('#phone').unmask();
	$('#fax').unmask();
	
	$('#phone').mask('(999) 999-9999');
	$('#fax').mask('(999) 999-9999');
	
	$("#time_zone").children('option:contains("London")').hide();
	$("#time_zone").children('option:contains("US")').show();
	$("#time_zone").children('option:contains("Canada")').hide();
	$("#time_zone").val($("#time_zone").children('option:contains("US")').first().val());
	$("#spnSP").text("Resturant State:");
	$("#spnZP").text("Resturant Zip Code:");
}

function loadTimeZoneCanada()
{
	$('#phone').unmask();
    $('#fax').unmask();

	$('#phone').mask('(999) 999-9999');
	$('#fax').mask('(999) 999-9999');

	$("#time_zone").children('option:contains("London")').hide();
	$("#time_zone").children('option:contains("US")').hide();
	$("#time_zone").children('option:contains("Canada")').show();
	$("#time_zone").val($("#time_zone").children('option:contains("Canada")').first().val());
	$("#spnSP").text("Resturant Province:");
	$("#spnZP").text("Resturant Postal Code:");
}

function loadTimeZoneUK()
{
	$('#phone').unmask();
	$('#fax').unmask();

	$('#phone').mask('(9999) 999-9999');
	$('#fax').mask('(9999) 999-9999');

	$("#time_zone").children('option:contains("US")').hide();
	$("#time_zone").children('option:contains("Europe")').show();
	$("#time_zone").children('option:contains("Canada")').hide();
	$("#time_zone").val($("#time_zone").children('option:contains("Europe")').first().val());
	$("#spnSP").text("Resturant State:");
	$("#spnZP").text("Resturant Zip Code:");
}



</script>
