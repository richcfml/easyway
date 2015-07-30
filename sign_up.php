<?php
require_once("includes/config.php");

$mResultDiv = " style='display: none;' ";
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

if (isset($_POST["btnSearch"]))
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

        /*Time Zone Code Starts Here*/ //Asher - 16 August 2014
	$mURL = "https://maps.googleapis.com/maps/api/timezone/json?location=".$mLat.",".$mLong."&timestamp=".time();
	$mURL = str_replace(" ", "%20", $mURL);
	$mResponse = file_get_contents($mURL);
	$mResponse = json_decode($mResponse, true);
	$mTimeZoneSelect = "Select Time Zone";
	if (isset($mResponse['timeZoneName']))
	{
		$mTimeZone = trim(strtolower($mResponse['timeZoneName']));

		if ($mTimeZone!="")
		{
			if (trim(strtolower($mCountry))=="us")
			{
				if (strpos($mTimeZone, "eastern")>=0)
				{
					$mTimeZoneSelect = "US/Eastern";
				}
				else if (strpos($mTimeZone, "hawaii")>=0)
				{
					$mTimeZoneSelect = "US/Hawaii";
				}
				else if (strpos($mTimeZone, "alaska")>=0)
				{
					$mTimeZoneSelect = "US/Alaska";
				}
				else if (strpos($mTimeZone, "pacific")>=0)
				{
					$mTimeZoneSelect = "US/Pacific";
				}
				else if (strpos($mTimeZone, "mountain")>=0)
				{
					$mTimeZoneSelect = "US/Mountain";
				}
				else if (strpos($mTimeZone, "central")>=0)
				{
					$mTimeZoneSelect = "US/Central";
				}
			}
			else if (trim(strtolower($mCountry))=="canada")
			{
				if (strpos($mTimeZone, "pacific")>=0)
				{
					$mTimeZoneSelect = "Canada/Pacific";
				}
				else if (strpos($mTimeZone, "central")>=0)
				{
					$mTimeZoneSelect = "Canada/Central";
				}
				else if (strpos($mTimeZone, "mountain")>=0)
				{
					$mTimeZoneSelect = "Canada/Mountain";
				}
				else if (strpos($mTimeZone, "eastern")>=0)
				{
					$mTimeZoneSelect = "Canada/Eastern";
				}
				else if (strpos($mTimeZone, "atlantic")>=0)
				{
					$mTimeZoneSelect = "Canada/Atlantic";
				}
				else if (strpos($mTimeZone, "newfoundland")>=0)
				{
					$mTimeZoneSelect = "Canada/Newfoundland";
				}
			}
			else if (trim(strtolower($mCountry))=="uk")
			{
				if (strpos($mTimeZone, "british")>=0)
				{
					$mTimeZoneSelect = "Europe/London";
				}
			}
		}
	}
	/*Time Zone Code Ends Here*/ //Asher - 16 August 2014
        
	$mURL = "https://maps.googleapis.com/maps/api/place/search/json?location=".$mLat.",".$mLong."&rankby=distance&types=establishment&name=".$mRestaurant."&sensor=false&key=".$mGoogleAPIKey;
	$mURL = str_replace(" ", "%20", $mURL);

	$mResponse = file_get_contents($mURL);
	$mResponse = json_decode_ewo($mResponse, true);
        //echo "<pre>";print_r($mResponse);exit;
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
                       if (trim($mRestDet[$loopCount]['city'])=='')
                       {
                        $mRestDet[$loopCount]['city'] = (($mAddress['postal_town']['long_name'] != 'false') && ($mAddress['postal_town']['long_name'] != '')) ? $mAddress['postal_town']['long_name'] : '';
                       }

                       if (trim($mRestDet[$loopCount]['city'])=='')
                       {
                        $mRestDet[$loopCount]['city'] = (($mAddress['sublocality']['long_name'] != 'false') && ($mAddress['sublocality']['long_name'] != '')) ? $mAddress['sublocality']['long_name'] : '';
                       }

                       if (trim($mRestDet[$loopCount]['city'])=='')
                       {
                        $mRestDet[$loopCount]['city'] = (($mAddress['sublocality_level_1']['long_name'] != 'false') && ($mAddress['sublocality_level_1']['long_name'] != '')) ? $mAddress['sublocality_level_1']['long_name'] : '';
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
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>EWO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="signup_css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="signup_css/customStyle.css" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,700,600,300,800' rel='stylesheet' type='text/css'>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="signup_js/jquery.cookie.js"></script>
        <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <link href="signup_js/radio/bootstrap-switch.css" rel="stylesheet">
        <script src="js/mask.js" type="text/javascript"></script>
        <!-- jQuery easing plugin -->
        <script src="signup_js/jquery.easing.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="signup_js/function.js?v=1"></script>
        <script src="js/facebox.js" type="text/javascript"></script>
        <link href="css/facebox.css" type="text/css" rel="stylesheet" media="screen"/>
        <script src="//maps.googleapis.com/maps/api/js?key=<?=$google_api_key?>&sensor=false" type="text/javascript"></script>
        <link href="Styles/ProgressButton_style.css" rel="stylesheet" />
        <script src="signup_js/ProgressButton_script.js?v=1"></script>
        

    <script type="text/javascript" language="javascript">
    jQuery(document).ready(function($)
    {
            $("#close").click($.facebox.close);
            $('#txtPhone').mask('(999) 999-9999');
            $('#txtFax').mask('(999) 999-9999');
            $('#masterPhone').mask('9-999-999-9999');
            $('#txtCreditCardNumber').mask('9999-9999-9999-9999');
            
            $('a[rel*=facebox]').facebox();
            var padding = parseInt($('#facebox table').css('padding-left'))
                    + parseInt($('#facebox table').css('padding-right'))
                    + parseInt($('#facebox .tl').width())
                        + parseInt($('#facebox .tr').width());
                var offset = 20;
                $('#facebox table').width( ($(window).width() - padding - offset) + 'px' );

                        
            $(".SpanClickTo").click(function()
            {
                $("#locateBuisness").css("display","none");
                $("#BuisnessInfo").css({"opacity":"1","left":"0","display":"block"});
            });
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

                            var radioValue = $("input[name='rbProducts']:checked").val();
                            if(radioValue == '<?=$ChargifyPremiumProduct?>'){
                                $("#msform").attr("action", "sign_up.php?plan=premium");
                            }else if (radioValue == '<?=$ChargifyStandardProduct?>'){
                                $("#msform").attr("action", "sign_up.php?plan=basic");
                            }
                            return true;
                    }
            });

            $("#txtTax").blur(function()
            {
                var tax = $("#txtTax").val();
                if(tax.indexOf("%") == -1 && tax.length >0)
                {
                    var formatedValue = $("#txtTax").val()+"%";
                    $("#txtTax").val(formatedValue);
                }
                
            });

            $( ".rbNewDomain").click(function()
            {
               var newdiv = document.createElement('p');
               newdiv.setAttribute('id','radio_');
               newdiv.setAttribute('align','left');
               newdiv.innerHTML = "<strong>New domain name?*</strong><input type='text' name='txtNewDomainName[]' placeholder='www.yourwebsite.com'>";
               document.getElementById("domainDiv").appendChild(newdiv);
            });

            $("#txtZip").focusout(function(){
            var zip = $("#txtZip").val();
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': zip}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    geocoder.geocode({'latLng': results[0].geometry.location}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            var loc = getCityState(results);
                            var array = loc.split(',');
                            var city = array[0];
                            var state = array[1];
                            var country = array[2];
                            $("#txtCity").val(city);
                            state = state.replace(' ','');
                            $("#txtState").val(state);
                            country = country.replace(' ','');
                            $("#ddlCountry1").val(country);
                            if(country=="US")
                            {
                                $("#ddlTimeZone").children('option:contains("London")').hide();
                                $("#ddlTimeZone").children('option:contains("Canada")').hide();
                                $("#ddlTimeZone").children('option:contains("US")').show();
                            }
                        }
                    }
                });
              }
         });
        });

        function getCityState(results)
        {
            var a = results[0].address_components;
            //console.log(a)
            var city, state,country;
            for(i = 0; i <  a.length; ++i)
            {
               var t = a[i].types;
               if(compIsType(t, 'administrative_area_level_1'))
                  state = a[i].short_name; //store the state
               else if(compIsType(t, 'locality'))
                  city = a[i].long_name; //store the city
              else if(compIsType(t, 'country'))
                  country = a[i].short_name; //store the city
            }
            return (city + ', ' + state+', ' + country)
        }

    function compIsType(t, s) {
           for(z = 0; z < t.length; ++z)
              if(t[z] == s)
                 return true;
           return false;
        }
    });

    function FillDetails(pName, pPhone, pStreet_number, pStreet_name, pCity, pState, pCountry, pZip_code,pImage)
    {       $("#progressbar li").eq($("fieldset").index(0)).addClass("active");
            pName = pName.replace("|||", "'");
            pStreet_number = pStreet_number.replace("|||", "'");
            pStreet_name = pStreet_name.replace("|||", "'");
            $("#txtRestaurantName").val(pName);
            if ($.trim(pStreet_name)!="")
            {
                    $("#txtStreetAddress").val(pStreet_number+" "+pStreet_name);
                    $("#txtClientAddress").val(pStreet_number+" "+pStreet_name);
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
            $("#optionallogo").val(pImage);
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

                    $("#txtPhone").attr("placeholder","Phone Number")
                    $("#txtFax").attr("placeholder","Fax Number")
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

                    $("#txtPhone").attr("placeholder","Phone Number")
                    $("#txtFax").attr("placeholder","Fax Number")
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

                    $("#txtPhone").attr("placeholder","Phone Number")
                    $("#txtFax").attr("placeholder","Fax Number")

                    $("#pStateProvince").text("Province");
                    $("#pZipPostal").text("Postal Code");
            }
            $("#ddlTimeZone option").each(function()
            {
                    if($.trim($(this).text().toLowerCase()) == $.trim($("#txtTimeZone").val().toLowerCase()))
                    {
                            $(this).attr('selected', 'selected');
                    }
            });
            //$('#lnkMap').attr('href', 'tab_restaurant_delivery_zones.php?address=' + $("#txtStreetAddress").val().replace(" ", "%20").replace(",", "%2C").replace("'", "%27").replace("(", "%28").replace(")", "%29").replace(".", "%2E").replace("/", "%2F").replace("'", "%23").replace("'", "%22").replace("'", "%26").replace("*", "%2A").replace("+", "%2B") + '&city=' + $("#txtCity").val().replace(" ", "%20").replace(",", "%2C").replace("'", "%27").replace("(", "%28").replace(")", "%29").replace(".", "%2E").replace("/", "%2F").replace("'", "%23").replace("'", "%22").replace("'", "%26").replace("*", "%2A").replace("+", "%2B") + '&state=' + $("#txtState").val().replace(" ", "%20").replace(",", "%2C").replace("'", "%27").replace("(", "%28").replace(")", "%29").replace(".", "%2E").replace("/", "%2F").replace("'", "%23").replace("'", "%22").replace("'", "%26").replace("*", "%2A").replace("+", "%2B"));
            $("#locateBuisness").css("display","none");
            $("#BuisnessInfo").css({"opacity":"1","left":"0","display":"block"});
            $("#step1").removeClass("active").css("background-color","#e4eff3");
            
            $(window).scrollTop(125);
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
    function moveWindow()
    {
        $(window).scrollTop(600);
    }
</script>
    </head>

    <body style="background:#fff;" <?php if($mTotalCount>0){?> onload='moveWindow()'<?php } ?>>
        <div id="pagewrap">


            <script type="text/javascript">
              $(document).ready(function()
              {
                  $('#rbDelivery').on('switchChange.bootstrapSwitch', function(event, state) {
                      var deliveryOption = $(this).val();
                      if(deliveryOption == 0)
                      {
                            $(this).val(1);
                            $("#delivery_fields").slideDown();
                      }
                      else
                      {
                            $(this).val(0);
                            $("#delivery_fields").slideUp();
                      }
                   });
                   
                   $( "input[name='rbOrders']" ).on('switchChange.bootstrapSwitch', function(event, state) {
                      var value = $(this).val();
                    
                      if(value == 4)
                      {
                            $("#dvManagerTab").show();
                            $("#spnManageTab").text('150.00');
                      }
                      else 
                      {
                            $("#dvManagerTab").hide();
                            $("#spnManageTab").text('0.00');
                      }
                      var managaerTab = parseInt($("#spnManageTab").text());
                      var setupFee = parseInt($("#spnSetup").text());
                      var monthlyFee = parseInt($("#spnMonthly").text());
                      TotalPrice = managaerTab +setupFee+monthlyFee;
                      $("#spnTotal").text(TotalPrice+'.00');
                       
                   });
                   $( "input[name='orderingPlan']" ).on('switchChange.bootstrapSwitch', function(event, state) {
                      var orderType = $(this).val();
                      if(orderType == 2)
                      {
                            $("#spnSetup").text('845.00');
                      }
                      else
                      {
                            $("#spnSetup").text('0.00');
                      }
                      
                      var managaerTab = parseInt($("#spnManageTab").text());
                      var setupFee = parseInt($("#spnSetup").text());
                      var monthlyFee = parseInt($("#spnMonthly").text());
                      TotalPrice = managaerTab +setupFee+monthlyFee;
                      $("#spnTotal").text(TotalPrice+'.00');

                   });
                   $('#rbCreditCard').on('switchChange.bootstrapSwitch', function(event, state) {
                      var creditCrad = $(this).val();
                      if(creditCrad == 0)
                      {
                            $(this).val(1);
                            $("#creditCardOption").slideDown();
                      }
                      else
                      {
                            $(this).val(0);
                            $("#creditCardOption").slideUp();
                      }
                   });
                   $('#rbCash').on('switchChange.bootstrapSwitch', function(event, state) {
                      var cash = $(this).val();
                      if(cash == 0)
                      {
                            $(this).val(1);
                      }
                      else
                      {
                            $(this).val(0);
                      }
                });
                    $('#rbPremium').on('switchChange.bootstrapSwitch', function(event, state) {
                      $("#spnMonthly").text('129.00');
                      var managaerTab = parseInt($("#spnManageTab").text());
                      var setupFee = parseInt($("#spnSetup").text());
                      var monthlyFee = parseInt($("#spnMonthly").text());
                      TotalPrice = managaerTab +setupFee+monthlyFee;
                      $("#spnTotal").text(TotalPrice+'.00');
                   });
                   $('#rbBasic').on('switchChange.bootstrapSwitch', function(event, state) {
                      $("#spnMonthly").text('69.00');
                      var managaerTab = parseInt($("#spnManageTab").text());
                      var setupFee = parseInt($("#spnSetup").text());
                      var monthlyFee = parseInt($("#spnMonthly").text());
                      TotalPrice = managaerTab +setupFee+monthlyFee;
                      $("#spnTotal").text(TotalPrice+'.00');
                   });

                   $( "input[name='rbMenuUse']" ).on('switchChange.bootstrapSwitch', function(event, state) {
                      $("#paymentsMethodError").hide();
                      $("#paymentsMethodError").text('Please correct the errors highlighted in red.');
                      $("#onlineOrdering").css("border","none");
                      var Type = $(this).val();
                      if(Type == 1)
                      {
                            $("#with_website").show();
                            $("#with_domain").hide();
                            $("#noWebDomain").hide();

                            $("#hostingCompany").val('');
                            $("#accountName").val('');
                            $("#accountUsername").val('');
                            $("#masterName").val('');
                            $("#masterEmail").val('');
                            $("#masterPhone").val('');
                      }
                      else if(Type == 2)
                      {
                            $("#with_domain").show();
                            $("#with_website").hide();
                            $("#noWebDomain").hide();

                            $("#webName").val('');
                            $("#webHost").val('');
                            $("#webUserName").val('');
                            $("#webPassword").val('');
                      }
                      else
                      {

                            $("#with_website").hide();
                            $("#with_domain").hide();
                            $("#noWebDomain").show();

                            $("#hostingCompany").val('');
                            $("#accountName").val('');
                            $("#accountUsername").val('');
                            $("#masterName").val('');
                            $("#masterEmail").val('');
                            $("#masterPhone").val('');
                            $("#webName").val('');
                            $("#webHost").val('');
                            $("#webUserName").val('');
                            $("#webPassword").val('');
                      }
                   });

                   $( "input[name='rbHosting']" ).on('switchChange.bootstrapSwitch', function(event, state) {
                      var Type = $(this).val();
                      if(Type == 1)
                      {
                            $("#webMasterManage").hide();
                            $("#neitherManage").hide();
                            $("#webIdo").show();

                            $("#hostingCompany").val('');
                            $("#accountName").val('');
                            $("#accountUsername").val('');
                            $("#masterName").val('');
                            $("#masterEmail").val('');
                            $("#masterPhone").val('');

                      }
                      else if(Type == 2)
                      {
                            $("#webMasterManage").show();
                            $("#neitherManage").hide();
                            $("#webIdo").hide();

                            $("#hostingCompany").val('');
                            $("#accountName").val('');
                            $("#accountUsername").val('');
                      }
                      else
                      {
                            $("#webMasterManage").hide();
                            $("#neitherManage").show();
                            $("#webIdo").hide();

                            $("#masterName").val('');
                            $("#masterEmail").val('');
                            $("#masterPhone").val('');
                      }

                   });
                   var val =-1;
                    $("#noFTP").on('switchChange.bootstrapSwitch click', function(event, state) {
                        if($(this).val()==val)
                        {
                            $(this).prop('checked', false);
                            val = -1;
                            $("#webName").removeAttr('disabled');
                            $("#webHost").removeAttr('disabled');
                            $("#webUserName").removeAttr('disabled');
                            $("#webPassword").removeAttr('disabled');
                        }
                        else
                        {
                             val = $(this).val();
                             $("#webName").val('').attr('disabled','true');
                             $("#webHost").val('').attr('disabled','true');
                             $("#webUserName").val('').attr('disabled','true');
                             $("#webPassword").val('').attr('disabled','true');
                        }

                   });
                   var val1 =-1;
                    $("#noWebmaster").on('switchChange.bootstrapSwitch click', function(event, state) {
                       if($(this).val()==val1)
                       {
                           $(this).prop('checked', false);
                           val1 = -1;
                           $("#masterName").removeAttr('disabled');
                           $("#masterEmail").removeAttr('disabled');
                           $("#masterPhone").removeAttr('disabled');

                       }
                       else
                       {
                           val1 = $(this).val();
                           $("#masterName").val('').attr('disabled','true');
                           $("#masterEmail").val('').attr('disabled','true');
                           $("#masterPhone").val('').attr('disabled','true');
                           
                       }
                   });

                   var val2 =-1;
                   $("#noHosting").on('switchChange.bootstrapSwitch click', function(event, state) {
                       if($(this).val()==val2)
                       {
                           $(this).prop('checked', false);
                           val2 = -1;
                           $("#hostingCompany").removeAttr('disabled');
                           $("#accountName").removeAttr('disabled');
                           $("#accountUsername").removeAttr('disabled');

                       }
                       else
                       {
                           val2 = $(this).val();
                           $("#hostingCompany").val('').attr('disabled','true');
                           $("#accountName").val('').attr('disabled','true');
                           $("#accountUsername").val('').attr('disabled','true');
                       }
                   });

                   
                   $("#txtDomainName").on("blur",function()
                   {
                      var domain = $("#txtDomainName").val();
                      if (domain.toLowerCase().indexOf("www") == -1)
                      {
                        domain = 'www.' + domain;
                      }
                      if (domain.toLowerCase().indexOf(".com") == -1)
                      {
                        domain = domain + '.com';
                      }
                      $("#txtDomainName").val(domain);
                   });
                   
                   $(document).on("blur","input[name*='txtNewDomainName']",function()
                   {
                         var domain = $(this).val();
                          if (domain.toLowerCase().indexOf("www.") == -1)
                          {
                            domain = 'www.' + domain;
                          }
                          if (domain.toLowerCase().indexOf(".com") == -1)
                          {
                            domain = domain + '.com';
                          }
                          $(this).val(domain);
                   });
              });
            </script>
            <style type="text/css">
                .sign_up{
                    width: 100%;
                    background-size:100%;
                    margin:0 auto;
                }

                select{
                    background:url(signup_images/select_down.png) right center no-repeat;
                    height: 50px;
                    margin: 0  1%;
                    width: 90%;max-width: 330px;
                    -moz-appearance: none;
                }
                .sign_up form input
                {
                    width: 90%;
                    padding:0;
                    max-width: 330px;
                    height: 50px;
                    margin: 0  1%;
                    /*background:#e5e7e9;*/
                    font-size:20px;
                    border: 1px solid #CFCFCF;
                    box-shadow: inset 1px 1px 4px #CDCDCD;
                }


                .sign_up .radio{
                    background: none !important;
                    float:left none !important;
                    height:25px !important;
                    width: 25px !important;
                    line-height:25px none !important;
                    box-shadow: none none !important;
                }
                .sign_up form .login {
                    width: 100%;
                    height: 50px;
                    max-width: 380px;
                    margin: 0 auto;
                    border-radius:5px;
                    -moz-border-radius:5px;
                    -webkit-border-radius:5px;
                    font-size:30px;
                    border: 1px solid #238CA5;

                }
                .sign_up .orange_con,  .sign_up .blue_con,{
                    font-weight: 600;
                    font-size:18px !important;
                }

                /*form styles*/
                #msform {
                    width: 100%;
                    margin: 50px auto;
                    text-align: center;
                    position: relative;
                    height:auto;
                    clear:both;box-shadow: 0 0 2px 0px #999;
                    overflow:hidden;
                    min-height: 300px;
                }
                #msform fieldset {
                    background: white;
                    border: 0 none;
                    padding-top:50px;
                    box-sizing: border-box;
                    width: 100%;
                    /*stacking fieldsets above each other*/
                    position: relative;
                }

                #msform fieldset h3{
                    color:#25aae1;
                }
                /*Hide all except first fieldset*/
                #msform fieldset:not(:first-of-type) {
                    display: none;
                }
                /*inputs*/
                #msform input, #msform textarea {
                    padding: 15px;
                    border: 1px solid #ccc;
                    border-radius: 3px;
                    margin-bottom: 10px;
                    width: 100%;
                    box-sizing: border-box;
                    color: #2C3E50;
                    font-size: 13px;
                }
                /*buttons*/
                #msform .action-button {
                    width: 100px;
                    background: #25aae1;
                    font-weight: bold;
                    color: white;
                    border: 0 none;
                    border-radius: 1px;
                    cursor: pointer;
                    padding: 10px 5px;
                    margin: 10px 5px;
                }

                #msform .action-button.previous {
                    width: 100px;
                    background: #f7941d;
                    font-weight: bold;
                    color: white;
                    border: 0 none;
                    border-radius: 1px;
                    cursor: pointer;
                    padding: 10px 5px;
                    margin: 10px 5px;
                }
                #msform .action-button:hover, #msform .action-button:focus {
                    box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
                }
                /*headings*/
                .fs-title {
                    font-size: 15px;
                    color: #636e75;

                }
                .fs-subtitle {
                    font-weight: normal;
                    font-size: 13px;
                    color: #666;

                }
                /*progressbar*/
                #progressbar {
                    margin:0;
                    width:100%;
                    padding:0;
                    overflow: hidden;
                    /*CSS counters to number the steps*/
                    counter-reset: step;

                }
                #progressbar li {
                    list-style-type: none;
                    color: #25aae1;
                    padding-top: 10px;
                    padding-bottom: 10px;
                    font-size: 16px;
                    width: 20%;
                    float: left;
                    position: relative;
                    /*background: #f0f0f0;*/
                    box-shadow: 0 0 2px 0px #999;
                    border-bottom: 1px solid #f0f0f0;
                }

                /*progressbar connectors*/


                /*marking active/completed steps green*/
                /*The number of the step and the connector before it = green*/
                #progressbar li.active {
                    background: #25aae1;
                    color: white;
                    box-shadow: 0 0 2px 0px #fff;
                }

                #facebox
                {
                    top:380px;
                    left:50px !important;
                }
                #facebox_overlay
                {
                    opacity:0.8 !important;
                }
                #facebox .body-facebox
                {
                   width:auto;
                }


            </style>
            <style>
                #radio_ .bootstrap-switch
                {
                    width: 23px !important;
                    height: 23px !important;
                    direction: none !important;
                    padding: 0 !important;
                    background:url(signup_images/csscheckbox_b25d5084e6ac9885319021918fd8408c.png) 100% no-repeat !important;
                    line-height: 23px  !important;
                    border-radius:23px  !important;

                }
                #radio_ .bootstrap-switch-wrapper
                {
                    margin-left: 0 !important;
                }
                #radio_  .bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-primary,
                #radio_ .bootstrap-switch 		.bootstrap-switch-handle-off.bootstrap-switch-primary {
                    color: #fff;
                    background: none !important;
                    display: none !important;
                }
                #radio_ .bootstrap-switch-on
                {
                    border-color: none !important;
                    outline: 0 !important;
                    -webkit-box-shadow:none !important;
                    box-shadow: none !important;
                }
                #radio_ .bootstrap-switch-id-radio_
                {
                    border-color: none !important;
                    outline: 0 !important;
                    -webkit-box-shadow:none !important;
                    box-shadow: none !important;
                }

                #radio_ .bootstrap-switch.bootstrap-switch-focused {
                    border-color: none !important;
                    outline: 0 !important;
                    -webkit-box-shadow:none !important;
                    box-shadow: none !important;
                }
                #radio_ .bootstrap-switch-animate
                {
                    border-color: none !important;
                    outline: 0 !important;
                    -webkit-box-shadow:none !important;
                    box-shadow: none !important;
                }
                #radio_ .bootstrap-switch-container {
                    margin-left: 0px !important;
                    width: 27px !important;
                    border: none !important;
                    height: 27px !important;
                    margin-top: 1px;
                }
                #radio_ input[type=radio] {
                    margin-top: 1px!important;
                    /* border: 0px; */
                    line-height: 23px !important;
                    margin-bottom: 0px;
                    height: 23px !important;
                    width: 27px !important;
                    border: none;
                    box-shadow: none;
                    /* margin-left: -20px; */
                }


                #radio_   .bootstrap-switch .bootstrap-switch-handle-on,
                #radio_  .bootstrap-switch .bootstrap-switch-handle-off,
                #radio_   .bootstrap-switch .bootstrap-switch-label {
                    -webkit-box-sizing: border-box;
                    -moz-box-sizing: border-box;
                    box-sizing: border-box;
                    cursor: pointer;
                    display: inline-block !important;
                    height:22px;

                    font-size: 14px;
                    line-height: 23px;
                }

            </style>

            <script>
                var form = $("#example-advanced-form").show();
 
                form.steps({
                    headerTag: "h3",
                    bodyTag: "fieldset",
                    transitionEffect: "slideLeft",
                    onStepChanging: function (event, currentIndex, newIndex)
                    {
                        // Allways allow previous action even if the current form is not valid!
                        if (currentIndex > newIndex)
                        {
                            return true;
                        }
                        // Forbid next action on "Warning" step if the user is to young
                        if (newIndex === 3 && Number($("#age-2").val()) < 18)
                        {
                            return false;
                        }
                        // Needed in some cases if the user went back (clean up)
                        if (currentIndex < newIndex)
                        {
                            // To remove error styles
                            form.find(".body:eq(" + newIndex + ") label.error").remove();
                            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                        }
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    },
                    onStepChanged: function (event, currentIndex, priorIndex)
                    {
                        // Used to skip the "Warning" step if the user is old enough.
                        if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
                        {
                            form.steps("next");
                        }
                        // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                        if (currentIndex === 2 && priorIndex === 3)
                        {
                            form.steps("previous");
                        }
                    },
                    onFinishing: function (event, currentIndex)
                    {
                        form.validate().settings.ignore = ":disabled";
                        return form.valid();
                    },
                    onFinished: function (event, currentIndex)
                    {
                        alert("Submitted!");
                    }
                }).validate({
                    errorPlacement: function errorPlacement(error, element) { element.before(error); },
                    rules: {
                        confirm: {
                            equalTo: "#password-2"
                        }
                    }
                });</script>
            <?php include('header_a.php'); ?>
            <div class="blue_con center">
                <div class="ewo-container">
                    <h3><strong>Be a part of the online ordering revolution!</strong><br/>Offer added value to your clients</h3>
                    <br clear="all"/></div>
            </div>
            
            <section id="sign_up" class="ewo-row">
                <div class="ewo-container">
                    <div class="sign_up">
                        <!-- multistep form -->
                        <form id="msform" name="msform" method="post" action="sign_up.php" novalidate enctype="multipart/form-data">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" style="cursor:pointer" id="step1">Locate Your Business</li>
                                <li style="cursor:pointer" id="step2">Describe the Business</li>
                                <li style="cursor:pointer" id="step3">Order Preferences</li>
                                <li style="cursor:pointer" id="step4">Payment & Website Details</li>
                                <li style="cursor:pointer" id="step5">Create Account</li>
                            </ul>
                            <!-- fieldsets -->
                            
                            <fieldset id="locateBuisness">
                                <h3><strong>Where is your restuarant located?</strong></h3>

                                <h4 class="fs-title"><strong>Locate Your Business</strong></h4>
                                <hr/><br/>
                                <p align="center"> <input type="text" id="txtRestaurant" name="txtRestaurant" maxlength="50" placeholder="Restaurant Name*" />
                                    <input type="text" placeholder="City, State or Zip Code*" id="txtCSZ" name="txtCSZ"/>
                                    <select id="ddlCountry" name="ddlCountry">
                                        <option id="US">US</option>
                                        <option id="Canada">Canada</option>
                                        <option id="UK">UK</option>
                                    </select></p> <br clear="all"/> <br clear="all"/>
                                    <div id="SearchRestaurantError" style="display: none;">Please correct the errors highlighted in red.</div>
                                    <input name="btnSearch" id="btnSearch" type="submit" class="orange_con" value="Find My Restaurant" />
                                <hr/>
                                <br clear="all"/>
                                

                                <div id="SelectYourBusinessBigDiv" <?=$mResultDiv?>>
                                    <div id="SelectYouBusinessHeading">Select Your Business to Get Set Up Faster</div>
                                    <div id="DontSeeHeading">Don’t See Your Business?<span><span style="cursor: hand; cursor: pointer;" class="SpanClickTo"> Click to Continue.</span></span></div>
                                    <div class="clear"></div>
                                    <div id="SearchResultsDiv">
                                            <div class="ResultsDiv" <?=$mRestDet[0]["DivShowHide"]?>>

                                            <div class="ResultImg"><img src="<?=$mRestDet[0]["Image"]?>" style="width: 180px; height: 150px;" /></div>
                                            <div class="RestaurantName" style="width: 220px !important;">
                                                    <?=$mRestDet[0]["Name"]?>
                                            </div>
                                            <div class="RestaurantAddress" style="width: 160px !important;"><?=$mRestDet[0]["Address"]?><br  /><br  /><?=$mRestDet[0]["Phone"]?></div>
                                            <div class="ThisIsMeDiv"><a href="#dvMore" class="ThisIsMe" onclick="FillDetails('<?=str_replace("'", "|||", $mRestDet[0]["Name"])?>', '<?=$mRestDet[0]["Phone"]?>', '<?=str_replace("'", "|||", $mRestDet[0]['street_number'])?>', '<?=str_replace("'", "|||", $mRestDet[0]['street_name'])?>', '<?=$mRestDet[0]['city']?>', '<?=$mRestDet[0]['state']?>', '<?=$mRestDet[0]['country']?>', '<?=$mRestDet[0]['zip_code']?>', '<?=$mRestDet[0]["Image"]?>');">This Is Me!</a></div>
                                            <div class="HrDiv"></div>

                                        </div>

                                        <div class="ResultsDiv" <?=$mRestDet[1]["DivShowHide"]?>>
                                            <div class="ResultImg"><img src="<?=$mRestDet[1]["Image"]?>" style="width: 180px; height: 150px;" /></div>
                                            <div class="RestaurantName" style="width: 220px !important;">
                                                    <?=$mRestDet[1]["Name"]?>
                                            </div>
                                            <div class="RestaurantAddress" style="width: 160px !important;"><?=$mRestDet[1]["Address"]?><br  /><br  /><?=$mRestDet[1]["Phone"]?></div>
                                            <div class="ThisIsMeDiv"><a href="#dvMore" class="ThisIsMe" onclick="FillDetails('<?=str_replace("'", "|||", $mRestDet[1]["Name"])?>', '<?=$mRestDet[1]["Phone"]?>', '<?=str_replace("'", "|||", $mRestDet[1]['street_number'])?>', '<?=str_replace("'", "|||", $mRestDet[1]['street_name'])?>', '<?=$mRestDet[1]['city']?>', '<?=$mRestDet[1]['state']?>', '<?=$mRestDet[1]['country']?>', '<?=$mRestDet[1]['zip_code']?>', '<?=$mRestDet[1]["Image"]?>');">This Is Me!</a></div>
                                            <div class="HrDiv"></div>
                                        </div>

                                        <div class="ResultsDiv" <?=$mRestDet[2]["DivShowHide"]?>>
                                            <div class="ResultImg"><img src="<?=$mRestDet[2]["Image"]?>" style="width: 180px; height: 150px;" /></div>
                                            <div class="RestaurantName" style="width: 220px !important;">
                                                    <?=$mRestDet[2]["Name"]?>
                                            </div>
                                            <div class="RestaurantAddress" style="width: 160px !important;"><?=$mRestDet[2]["Address"]?><br  /><br  /><?=$mRestDet[2]["Phone"]?></div>
                                            <div class="ThisIsMeDiv"><a href="#dvMore" class="ThisIsMe" onclick="FillDetails('<?=str_replace("'", "|||", $mRestDet[2]["Name"])?>', '<?=$mRestDet[2]["Phone"]?>', '<?=str_replace("'", "|||", $mRestDet[2]['street_number'])?>', '<?=str_replace("'", "|||", $mRestDet[2]['street_name'])?>', '<?=$mRestDet[2]['city']?>', '<?=$mRestDet[2]['state']?>', '<?=$mRestDet[2]['country']?>', '<?=$mRestDet[2]['zip_code']?>', '<?=$mRestDet[2]["Image"]?>');">This Is Me!</a></div>
                                            <div class="HrDiv"></div>
                                        </div>

                                    </div>
                                    <div class="clear"></div>
                                </div>
                                
                                <br clear="all"/>
                                <input type="button" name="next" class="next action-button" value="Next" id="firstTab"/>
                                <br clear="all"/>
                                
                            </fieldset>

                            <fieldset id="BuisnessInfo">
                                <h3><strong>Tell Us a Little About Your Business</strong></h3>
                                <h4 class="fs-title"><strong>Describe the Business</strong></h4>
                                <hr/>

                                <br/>
                                <input type="hidden" id="txtTimeZone" name="txtTimeZone" value="<?=$mTimeZoneSelect?>" />
                                <input type="text" placeholder="Restaurant Name" id="txtRestaurantName" name="txtRestaurantName" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->RestaurantName:'')?>" maxlength="50"/>
                                <input type="text" placeholder="Address" id="txtStreetAddress" name="txtStreetAddress" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->Address:'')?>" maxlength="60" />
                                <input type="text" placeholder="City" id="txtCity" name="txtCity" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->City:'')?>"   maxlength="25"/>  <br clear="all"/>
                                <input type="text" placeholder="11223" id="txtZip" name="txtZip" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->ZipCode:'')?>"  maxlength="15"/>
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
                                <select id="txtState" name="txtState">
                                    <option value="-1">State</option>
                                    <option  value="AL" >AL</option>
                                    <option  value="AK" >AK</option>
                                    <option  value="AZ" >AZ</option>
                                    <option value="AR">AR</option>
                                    <option  value="CA" >CA</option>
                                    <option  value="CO" >CO</option>
                                    <option  value="CT" >CT</option>
                                    <option  value="DE">DE</option>
                                    <option  value="FL" >FL</option>
                                    <option value="GA">GA</option>
                                    <option  value="HI" >HI</option>
                                    <option  value="ID" >ID</option>
                                    <option  value="IL" >IL</option>
                                    <option value="IN">IN</option>
                                    <option  value="IA" >IA</option>
                                    <option  value="KS" >KS</option>
                                    <option  value="KY" >KY</option>
                                    <option  value="LA">LA</option>
                                    <option  value="ME" >ME</option>
                                    <option  value="MD" >MD</option>
                                    <option  value="MA" >MA</option>
                                    <option  value="MI" >MI</option>
                                    <option  value="MN">MN</option>
                                    <option  value="MS" >MS</option>
                                    <option  value="MO" >MO</option>
                                    <option  value="MT" >MT</option>
                                    <option  value="NE">NE</option>
                                    <option  value="NV" >NV</option>
                                    <option  value="NH">NH</option>
                                    <option  value="NJ" >NJ</option>
                                    <option  value="NM" >NM</option>
                                    <option  value="NY">NY</option>
                                    <option  value="NC">NC</option>
                                    <option  value="ND" >ND</option>
                                    <option  value="OH" >OH</option>
                                    <option  value="OK">OK</option>
                                    <option  value="OR" >OR</option>
                                    <option  value="PA">PA</option>
                                    <option  value="RI" >RI</option>
                                    <option  value="SC">SC</option>
                                    <option  value="SD" >SD</option>
                                    <option  value="TN" >TN</option>
                                    <option  value="TX">TX</option>
                                    <option  value="UT" >UT</option>
                                    <option  value="VT" >VT</option>
                                    <option  value="VA" >VA</option>
                                    <option  value="WA">WA</option>
                                    <option  value="WV" >WV</option>
                                    <option  value="WI" >WI</option>
                                    <option  value="WY">WY</option>
                                    
                                    <?php
                                     if (isset($mRestaurantDetails))
                                    {
                                        echo("<script language='javascript' type='text/javascript'>
                                                    $('#txtState').val('".$mRestaurantDetails->State."');
                                                    </script>");
                                    }
                                    ?>
                                </select>
                                <select id="ddlCountry1" name="ddlCountry1" onchange="changeTimesZones();">
                                    <option id="US" value="US" <?=$mCountry1?>>US</option>
                                    <option id="Canada" value="Canada" <?=$mCountry2?>>Canada</option>
                                    <option id="UK" value="UK" <?=$mCountry3?>>UK</option>
                                </select>
                                <br clear="all"/><br clear="all"/>
                                <input type="text" placeholder="Phone Number" id="txtPhone" name="txtPhone" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->PhoneNumber:'')?>"  maxlength="20"/>
                                <input type="text" placeholder="Fax Number" id="txtFax" name="txtFax" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->FaxNumber:'')?>"   maxlength="20"/>
                                <select id="ddlTimeZone" name="ddlTimeZone">
                                    <option value="-1">Timezone</option>
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
                                <br clear="all"/><br clear="all"/>
                                <h3><strong>Add Your Menu</strong></h3>
                                <p> Please upload a digital copy of your menu.<br/>
                                    Don't have a digital copy? <a href="https://drive.google.com/file/d/0B6jHT4ob5gBqZ0JIMF9Xclp0eFE/view?pli=1" style="color:#25aae1;cursor:pointer;" onclick="window.open(this.href, 'https://drive.google.com/file/d/0B6jHT4ob5gBqZ0JIMF9Xclp0eFE/view?pli=1','left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;"     >Print our fax cover sheet</a> and fax your menu to: (800) 836 - 1510</p>
                                <br clear="all"/>
                                <label class="myLabel orange_con">
                                    <input type="file" name="uploadme" id="uploadme" value="Attach Your Menu" style="display: none;" multiple/> <br clear="all"/>
                                    <span>Attach Your Menu</span>
                                </label>

                                <p id="menu_name"> <strong>Menus Attached:</strong><br/><span id="noMenuAttached" style="display:none">No Menu Attached</span
                                </p>
                                <p id="radio_" style="cursor:pointer;display:none" class="addNewMenu" onclick="performClick('uploadme');">  <span>Add More<i class="fa fa-plus removeMenu" key="0" style="  margin-left: 20px;color: #25aae1;font-size: 18px;cursor:pointer"></i><br></span>
                                        </p>
                                 <div id="RestaurantInputBasicError" class="SearchRestaurantError" style="display: none;">Please correct the errors highlighted in red.</div><hr/>
                                <input type="button" name="previous" class="previous action-button" value="Back" />
                                <input type="button" name="next" class="next action-button" value="Next" id="Second_Tab"/>
                                <input type="hidden" id="txtRestaurantID" name="txtRestaurantID" value="<?=$mRestaurantID?>" />
                                <input name="optionallogo" type="hidden" value="<?=@$optionallogo?>" id="optionallogo" />
                                <br clear="all"/>
                            </fieldset>


                            <fieldset id="orderPrefrence">
                                <h3><strong>Select Your Order Preferences:</strong></h3>
                                <h4 class="fs-title"><strong>Order Preferences</strong></h4>
                                <hr/>
                                <br/>
                                <p align="center">
                                    <span style="color:#565d67;font-size: 25px;line-height: 35px;font-weight: 500;width:500px;">How would you like to receive orders?</span><span>*</span>
                               </p>
                            <br/> <br/>
                                <div class="grid25 floatleft">
                                    <p align="center"><img src="signup_images/fax.png" /><br/>
                                        <strong>Via Fax</strong></p>
                                    <div class="switch">
                                        <input type="radio" name="rbOrders"  data-radio-all-off="true" class="radio_btn" id="rbOrders1" value="1" flag="0">
                                    </div>
                                </div>

                                <div class="grid25 floatleft">
                                    <p align="center"><img src="signup_images/email_.png" /><br/>
                                        <strong>Via Email</strong></p>
                                    <div class="switch2">
                                        <input type="radio" name="rbOrders" data-radio-all-off="true" class="radio_btn" id="rbOrders2" value="2">
                                    </div>
                                </div>

                                <div class="grid25 floatleft">
                                    <p align="center"><img src="signup_images/pos.png" /><br/>
                                        <strong>Via POS</strong></p>
                                    <div class="switch3">
                                        <input type="radio" name="rbOrders" data-radio-all-off="true" class="radio_btn" id="rbOrders3" value="3">
                                    </div>
                                </div>

                                <div class="grid25 floatleft">
                                    <p align="center"><img src="signup_images/tablet.png" /><br/>
                                        <strong>Via Manager Tablet</strong></p>
                                    <div class="switch3">
                                        <input type="radio" name="rbOrders" data-radio-all-off="true" class="radio_btn" id="rbOrders4" value="4">
                                    </div>
                                </div>
                                
                                <br clear="all"/><br clear="all"/>
                                <br clear="all"/> <br clear="all"/>
                                <div class="grid33 floatleft">
                                    <div class="switch4">
                                        <strong>Do you offer pickup? </strong>
                                        <input id="switch-state" type="radio" data-radio-all-off="true" class="radio_btn">
                                    </div>
                                </div>
                                <div class="grid33 floatleft">
                                    <div class="switch5">
                                        <strong>Do you offer delivery? </strong>
                                        <input id="rbDelivery" name="rbDelivery" type="radio" data-radio-all-off="true" class="radio_btn" value="0">
                                    </div>
                                </div>

                                <div class="grid33 floatleft" id="show-me" >
                                    <div class="switch5">
                                        <input type="text" placeholder="Sales Tax %*"  name="txtTax" id="txtTax" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->Tax:'')?>"  maxlength="5"/>
                                    </div>
                                </div>
                                <br clear="all"/> <br clear="all"/>
                                <div id="delivery_fields" style="display:none">
                                    <div class="grid33 floatleft" >
                                        <div class="switch5">
                                            <input type="text" placeholder="Delivery Minimum"  placeholder="5" id="txtDeliveryMinimum" name="txtDeliveryMinimum" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->DeliveryMinimum:'')?>"  maxlength="3"/>
                                        </div>
                                    </div>
                                    <div class="grid33 floatleft" >
                                        <div class="switch5">
                                            <input type="text" placeholder="Delivery Charges"  id="txtDeliveryCharges" name="txtDeliveryCharges" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->DeliveryCharges:'')?>"  maxlength="2"/>
                                        </div>
                                    </div>
                                    <div class="grid33 floatleft">
                                        <div class="switch5">
                                            <input type="text" placeholder="Delivery Radius"  id="txtDeliveryRadius" name="txtDeliveryRadius" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->DeliveryRadius:'')?>"  maxlength="3"/>
                                        </div>
                                        
                                          <p><a href="tab_restaurant_delivery_zones.php?" style="color: #25aae1; cursor: hand; cursor: pointer;" id="lnkMap" rel="facebox">Custom Delivery Zone?</a></p>
                                    </div>
                                </div>
                                <br clear="all"/><br clear="all"/>
                                <div id="orderPreferencesError" class="errorDiv" style="display: none;">Please correct the errors highlighted in red.</div>
                                <input type="button" name="previous" class="previous action-button" value="Back" />
                                <input type="button" name="next" class="next action-button" value="Next" id="Third_Tab"/>
                                <br clear="all"/>
                            </fieldset>

                            <fieldset id="payment">
                                <h3><strong>Select Your Payment Methods:</strong></h3>
                                <h4 class="fs-title"><strong>Payment & Website Details</strong></h4>
                                <hr/>
                                <br/>

                                <p> How would you like customers to pay?*</p>
                                <div class="grid33 floatleft">
                                    <p align="center"><img src="signup_images/dollar.png" /><br/>
                                        <strong>Via Cash</strong></p>
                                    <div class="switch6">
                                        <input type="radio" name="rbCash" data-radio-all-off="true" class="radio_btn" id="rbCash" value="0"/>
                                    </div>

                                </div>

                                <div class="grid33 floatleft">
                                    <p align="center"><img src="signup_images/credit_card.png" /><br/>
                                        <strong>Via Credit Card</strong></p>
                                    <div class="switch7">
                                        <input type="radio" name="rbCreditCard" data-radio-all-off="true" class="radio_btn" id="rbCreditCard" value="0">
                                    </div>
                                    <br clear="all"/>
                                </div>
                                <br clear="all"/>
                                <div style="width:80%;margin:0 auto; padding:2%;display:none;margin-left: 400px;" id="creditCardOption">
                                    <p align="left" id="radio_">
                                    <input type="radio" id="rbGateWay1" name="rbGateWay" value="1" checked /> I have a payment gateway for online transactions<br clear="all"/>
                                    <input type="radio" id="rbGateWay2" name="rbGateWay" value="2"  /> I would like to set up a new e-commerce merchant account with an EasyWay certified partner.<br clear="all"/>
                                    <input type="radio" id="rbGateWay3" name="rbGateWay" value="3" /> I plan to open an e-commerce merchant account on my own.</p>
                                </div>
                                <hr/>
                                <br clear="all"/>
                                <h3>Website Details</h3>
                                <br clear="all"/>

                                <p><strong> How would you like to use your online ordering?*</strong></p>
                                <br clear="all"/>
                                <div style="width:80%;margin:0 auto; padding:2%;" id="onlineOrdering">
                                    <p align="left" id="radio_">
                                    <input type="radio" id="rbMenuUse1" name="rbMenuUse" value="1"/> Add EasyWay Ordering to my existing website<br clear="all"/>
                                    <input type="radio" id="rbMenuUse2" name="rbMenuUse" value="2" /> Use my EasyWay Ordering with my domain name<br clear="all"/>
                                    <input type="radio" id="rbMenuUse3" name="rbMenuUse" value="3" /> I do not have a website or a domain name</p>
                                </div>
                                <br clear="all"/>

                                <div style="border:1px solid #e9e9e9; width:80%;margin:0 auto; padding:2%;" id="with_website">
                                    <br clear="all"/>
                                    <p align="left" id="radio_" style="color:#25aae1">
                                    Great! We can add your online ordering to any existing website.
                                    Please provide your FTP access information, so our team can install the ordering page.<br clear="all"/><br clear="all"/></p>
                                    <p align="left" id="radio_" style="color:gray">
                                    <input type="radio"   name="noFTP" id="noFTP" /> I don't have this information<br clear="all"/><br clear="all"/></p>
                                    <div class="grid50 floatleft"> <p align="left"  id="radio_">
                                        <input type="text" placeholder="Name" id="webName" name="webName"/>
                                    </div>
                                    <div class="grid50 floatleft"> <p align="left"  id="radio_">
                                        <input type="text" placeholder="FTP/Host" id="webHost" name="webHost"/>
                                    </div>
                                    <div class="grid50 floatleft"> <p align="left"  >
                                        <input type="text" placeholder="Username" id="webUserName" name="webUserName" />
                                    </div>
                                    <div class="grid50 floatleft"> <p align="left"  id="radio_">
                                        <input type="password" placeholder="Password" id="webPassword" name="webPassword"/>
                                    </div>
                                    <br clear="all"/>
                                </div>
                               
                                <div style="border:1px solid #e9e9e9; width:80%;margin:0 auto; padding:2%;display:none;" id="with_domain" >
                                    <br clear="all"/>
                                    <div class="grid50 floatleft" id="domainDiv"> <p align="left"  id="radio_"> <strong>What is your domain name?*</strong>
                                        <input type="text" placeholder="www.yourwebsite.com" id="txtDomainName" name="txtDomainName"/>
                                        </p>
                                    </div>
                                    <div class="grid50 floatleft"> <br clear="all"/>
                                        <p align="left"  id="radio_" style="cursor:pointer;  margin-top: 12px;" class="rbNewDomain">  <span style="font-size: 35px;color:#25aae1;  font-weight: bold;float: left; width: 35px">+</span><span style="font-weight:bold;">Add additional choice?</span>
                                        </p>
                                    </div>
                                    <br clear="all"/><br clear="all"/>
                                    <p align="left"  id="radio_"><strong> Who manages your website?*</strong></p>

                                    <div class="grid25 floatleft"> <p align="left"  id="radio_"><input type="radio"   id="rbHosting1" name="rbHosting" value="1" /> I do</p></div>
                                    <div class="grid33 floatleft"><p align="left"  id="radio_"> <input type="radio"   id="rbHosting2" name="rbHosting" value="2" /> My webmaster/designer</p></div>
                                    <div class="grid25 floatleft"> <p align="left"  id="radio_"><input type="radio"   id="rbHosting3" name="rbHosting" value="3" /> Neither</p></div>
                                    <br clear="all"/><br clear="all"/>
                                    <div id="webIdo" class="floatleft" style="display:none">
                                        <p align="left" style="color:#25aae1">Great! We will give you everything you need to install the online ordering yourself. You can continue with your setup, just click Next.</p>
                                    </div>
                                    <div id="webMasterManage" class="floatleft" style="display:none">
                                        <p align="left" style="color:#25aae1">EasyWay will work with your web designer, and add the online ordering. We just need that person's contact information.</p>
                                        <br clear="all"/>
                                        <p align="left" id="radio_" style="color:gray">
                                        <input type="radio"   name="noWebmaster" id="noWebmaster"/> I don't have this information<br clear="all"/><br clear="all"/></p>
                                        <div class="grid50 floatleft"> <p align="left"  id="radio_">
                                            <input type="text" placeholder="Name" id="masterName" name="masterName"/>
                                        </div>
                                        <div class="grid50 floatleft"> <p align="left"  id="radio_">
                                            <input type="text" placeholder="johndoe@gmail.com" id="masterEmail" name="masterEmail"/>
                                        </div>
                                        <div class="grid50 floatleft"> <p align="left"  >
                                            <input type="text" placeholder="1-234-800-8989" id="masterPhone" name="masterPhone" />
                                        </div>
                                    </div>
                                    <div id="neitherManage" class="floatleft" style="display:none">
                                        <p align="left" style="color:#25aae1">EasyWay can configure your hosting account and/or integrate the online ordering page in to your exisiting website. We just need the few details to make that happen!</p>
                                        <br clear="all"/>
                                        <p align="left" id="radio_" style="color:gray">
                                        <input type="radio"   name="noHosting" id="noHosting" /> I don't have this information<br clear="all"/><br clear="all"/></p>
                                        <p align="left"  id="radio_"><strong> Hosting Information</strong></p>
                                        <div class="grid50 floatleft"> <p align="left"  id="radio_">
                                            <input type="text" placeholder="Name" id="hostingCompany" name="hostingCompany"/>
                                        </div>
                                        <div class="grid50 floatleft"> <p align="left"  id="radio_">
                                            <input type="text" placeholder="FTP/Host" id="accountName" name="accountName"/>
                                        </div>
                                        <div class="grid50 floatleft"> <p align="left"  >
                                            <input type="text" placeholder="Username" id="accountUsername" name="accountUsername" />
                                        </div>
                                    </div>
                                    <br clear="all"/>
                                </div>
                                
                                <div style="border:1px solid #e9e9e9; width:80%;margin:0 auto; padding:2%;display:none;" id="noWebDomain">
                                    <br clear="all"/>
                                    <div class="grid33 floatleft"> <p align="left"  id="radio_"> <strong>Please choose one:</strong>
                                        </p></div>
                                    <br/>
                                   <div class="grid33 floatleft"> <p align="center"><img src="signup_images/singlepage.jpg" /><br/>
                                    <strong>Stand Alone</strong><br />(single page online ordering)</p>
                                   <p align="center" id="radio_">
                                    <input type="radio" id="standAlone" name="orderingPlan" value="1"  /></div>

                                    <div class="grid33 floatleft"><p align="center"><img src="signup_images/fullwebsite.jpg" /><br/>
                                    <strong>Full Website</strong><br />(with online ordering included)</p>
                                    <p align="center" id="radio_">
                                    <input type="radio" id="chooseOne" name="orderingPlan" value="2"  /></div>
                                    <br clear="all"/>
                                </div>
                                <br clear="all"/>

                                <div id="paymentsMethodError" class="errorDiv" style="display: none;">Please correct the errors highlighted in red.</div>
                                <hr/>
                                <input type="button" name="previous" class="previous action-button" value="Back" />
                                <input type="button" name="next" class="next action-button" value="Next" id="Fourth_Tab"/>
                                <br clear="all"/>
                            </fieldset>

                            <fieldset id="createAccount">
                                <h3><strong>Complete Your Set Up</strong></h3>
                                <h4 class="fs-title"><strong>Create Account</strong></h4>
                                <br/>
                                <hr/> <br/>

                                <p align="center"> <strong>Create an account below. <br/>This will save all of your information. <br/>
                                        This is also how you access your control panel, and make
                                        changes to your online ordering menus.</strong></p>

                                <br/>
                                <script>
                                    $(document).ready(
                                    function() {
                                        $("#premium").click(function() {
                                            $("#premiuminfo").toggle();
                                            $("#basicinfo").hide();
                                        });
		
                                        $("#basic").click(function() {
                                            $("#basicinfo").toggle();
                                            $("#premiuminfo").hide();
                                        });
		
		
                                    });
                                </script>
                                <div class="grid50 floatleft"> <br clear="all"/>
                                    <div style=" width:85%; margin:0 auto; ">
                                        <div class="grid50 floatleft"><div style="background:#E5E5E5; width:98%; margin:0 auto;  box-shadow: #999 1px 1px 1px ; border-radius: 5px;padding-top:10px;padding-bottom:10px;"">
                                               <div> <p align="left"  id="radio_">
                                                        <input type="radio" data-radio-all-off="true"  name="rbProducts" id="rbPremium" value="<?=$ChargifyPremiumProduct?>"/>
                                                        <strong>Premium $129/mo</strong> <img src="signup_images/select_down.png" id="premium" align="center" width="20px"/>
                                                    </p>
                                                </div>

                                                <div id="premiuminfo"> <hr/>
                                                    <p>
                                                    <li>Orders via fax, POS, email</li>
                                                    <li>Unlimited orders</li>
                                                    <li>Confirmation phone calls</li>
                                                    <li>Mobile-ready site</li>
                                                    <li>Powerful order analytics</li>
                                                    <li>Credit card integration</li>
                                                    <li>E-coupon creation</li>
                                                    <li>Facebook ordering</li>
                                                    <li>Cloud control panel</li>
                                                    <li>Free Manager's Tablet</li>
                                                    <li>Online reputation management</li>
                                                    <li>Text message ordering</li>
                                                    <li>Review boost</li>
                                                    <li>PowerListings™ via Yext</li></p>
                                                </div>
                                            </div><br clear="all"/></div>
                                        <div class="grid50 floatright"><div style="background:#E5E5E5;width:98%; margin:0 auto;  box-shadow: #999 1px 1px 1px ; border-radius: 5px;padding-top:10px;padding-bottom:10px;">
                                                <div> <p align="left"  id="radio_">
                                                        <input type="radio"  name="rbProducts" id="rbBasic" value="<?=$ChargifyStandardProduct?>"/>
                                                        <strong>Basic $69/mo</strong>
                                                        <img src="signup_images/select_down.png" id="basic" align="center" width="20px"/>
                                                    </p></div>

                                                <div id="basicinfo"><hr/>
                                                    <p align="left">
                                                    <li>Orders via fax, POS, email</li>
                                                    <li> Unlimited orders</li>
                                                    <li>Confirmation phone calls</li>
                                                    <li>Mobile-ready site</li>
                                                    <li>Powerful order analytics</li>
                                                    <li> Credit card integration</li>
                                                    <li>E-coupon creation</li>
                                                    <li> Facebook ordering</li>
                                                    <li>Cloud control panel</li>
                                                    <li>Free Manager's Tablet</li></p>
                                                </div>
                                            </div></div>
                                    </div>
                                    <br clear="all"/>
                                    <div style="background:#E5E5E5; width:80%; margin:0 auto; padding: 10px; box-shadow: #999 1px 1px 1px ; border-radius: 5px;">
                                        <div class="grid50 floatleft">
                                            Set-up Fee
                                        </div>
                                        <div class="grid50 floatleft">
                                            $<span id="spnSetup">0.00</span>
                                        </div>

                                        <br clear="all"/>
                                        <div class="grid50 floatleft">
                                            Monthly Fee
                                        </div>
                                        <div class="grid50 floatleft">
                                            $<span id="spnMonthly">0.00</span>
                                        </div>
                                        <div id="dvManagerTab" style="display:none">
                                            <br clear="all"/>
                                            <div class="grid50 floatleft">
                                                Manager Tablet
                                            </div>
                                            <div class="grid50 floatleft">
                                                $<span id="spnManageTab">0.00</span>
                                            </div>
                                        </div>
                                        
                                        <br clear="all"/>
                                        <br clear="all"/>
                                        <div class="grid50 floatleft">
                                            <span class="gray">Total Due:</span>
                                        </div>
                                        <div class="grid50 floatleft">
                                            $<span class="TotalPrice" id="spnTotal">0.00</span>
                                        </div>
                                        <br/>
                                        <hr style="border: #00a651 1px solid; width: 80%;"/>

                                        <br clear="all"/>
                                        <p style="color:#25aae1;"><strong> Please Review Order Information Before Clicking Submit<br/>
                                                By Clicking submit your agree to our <a href="termscondition.php" target="_blank">Terms & Conditions</a></strong></p>
                                        <br clear="all"/>
                                    </div>
                                    <br clear="all"/>
                                </div>

                                <div class="grid50 floatright">
                                    <br clear="all"/>
                                    <p> Your Name*</p>
                                    <input type="text" placeholder="First Name, Last Name"  id="txtFullName" name="txtFullName" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->FullName:'')?>"  maxlength="50"/>
                                    <p> Email*</p>

                                    <input type="text" placeholder="email@address.com"  id="txtEmailAddress" name="txtEmailAddress" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->EmailAddress:'')?>"  maxlength="100"/>
                                   
                                    <p> Choose Username*</p>
                                    <input type="text" placeholder="Username"  id="txtUserName" name="txtUserName" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->UserName:'')?>"  maxlength="15"/>

                                    <p> Choose Password*</p>
                                    <input type="password" placeholder="*****"  id="txtPassword" name="txtPassword" value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->Password:'')?>"  maxlength="15"/>

                                </div>

                                <br clear="all"/><br clear="all"/>
                                <hr/>
                                <br clear="all"/>
                                <p><strong> Billing Information</strong></p>
                                <br clear="all"/>
                                <input type="text" placeholder="Credit Card" maxlength="20" id="txtCreditCardNumber" name="txtCreditCardNumber">
							

                                <select id="ddlExpMonth" name="ddlExpMonth" style="max-width: 152px">
                                    <option value="">Expiry Month</option>
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
                                <select id="ddlExpYear" name="ddlExpYear" style="max-width: 152px">
                                    <option value="">Expiry Year</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
				</select>

                                <br clear="all"/>
                                <input type="text" name="" placeholder="CVV"  required/>
                                <input type="text" name="txtClientAddress" placeholder="Billing Address"  id="txtClientAddress"  value="<?=(isset($mRestaurantDetails)?$mRestaurantDetails->ClientAddress:'')?>" maxlength="60"/>
                                <br clear="all"/>
                                <div id="RestaurantInputFinalError" class="SearchRestaurantError" style=" margin-bottom: 20px !important;  display: none;">Please correct the errors highlighted in red.</div>
                                <hr/>

                                <input type="button" name="previous" class="previous action-button" value="Back" />
                                <a id="submitButton" href="#" class="submit action-button Save progress-button" style="overflow: visible;">Finish</a>
                                <br clear="all"/>
                            </fieldset>
                            <br clear="all"/>
                            <input type="hidden"  id="hzone1"  value="0"/>
                            <input type="hidden"  id="hzone1_delivery_charges" value="0"/>
                            <input type="hidden"  id="hzone1_min_total" value="0"/>
                            <input type="hidden"  id="hzone2" value="0"/>
                            <input type="hidden"  id="hzone2_delivery_charges" value="0"/>
                            <input type="hidden"  id="hzone2_min_total" value="0"/>
                            <input type="hidden"  id="hzone3" value="0"/>
                            <input type="hidden"  id="hzone3_delivery_charges" value="0"/>
                            <input type="hidden"  id="hzone3_min_total" value="0"/>
                            <input type="hidden"  id="hzone1_coordinates" value=""/>
                            <input type="hidden"  id="hzone2_coordinates" value=""/>
                            <input type="hidden"  id="hzone3_coordinates" value=""/>
                        </form>


                        <br clear="all"/>
                       
                        <script type="text/javascript">
                    
                            var _gaq = _gaq || [];
                            _gaq.push(['_setAccount', 'UA-36251023-1']);
                            _gaq.push(['_setDomainName', 'jqueryscript.net']);
                            _gaq.push(['_trackPageview']);
                    
                            (function() {
                                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                            })();
                    
                        </script>
                        <br clear="all"/> <br clear="all"/>
                    </div>
                    <br clear="all"/>
                </div>
                <br clear="all"/>
                <br clear="all"/>
            </section>
        </div>
        <script src="dist/spin.min.js"></script>
        <script src="dist/ladda.min.js"></script>
        <link rel="stylesheet" href="dist/ladda.min.css">

    <script>
        Ladda.bind( '.button-demo button', { timeout: 2000 } );

        // Bind progress buttons and simulate loading progress
        Ladda.bind( '.progress-demo button', {
            callback: function( instance ) {
                var progress = 0;
                var interval = setInterval( function() {
                    progress = Math.min( progress + Math.random() * 0.1, 1 );
                    instance.setProgress( progress );

                    if( progress === 1 ) {
                        instance.stop();
                        clearInterval( interval );
                    }
                }, 200 );
            }
        } );
        // You can control loading explicitly using the JavaScript API
        // as outlined below:

        // var l = Ladda.create( document.querySelector( 'button' ) );
        // l.start();
        // l.stop();
        // l.toggle();
        // l.isLoading();
        // l.setProgress( 0-1 );
			
			
			
			
    </script>
    <script>



        var _gaq=[['_setAccount','UA-11278966-1'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>


    <script src="signup_js/radio/highlight.js"></script>
    <script src="signup_js/radio/bootstrap-switch.js"></script>
    <script src="signup_js/radio/main.js"></script>
    <?php include('footer.php'); ?>
</div>

</body>
</html>
<?php
if (isset($_GET["flag"]))
{
        if ($_GET["flag"]=="upload")
        {
                echo("<script type='text/javascript' language='javascript'>$('#locateBuisness').css('display','none');$('#BuisnessInfo').css({'opacity':'1','left':'0','display':'block'});$('#progressbar li').eq($('fieldset').index(0)).addClass('active');</script>");
        }
}
if ($_GET["plan"]=="basic")
{
        echo("<script type='text/javascript' language='javascript'>
                      $('#rbBasic').attr('checked','true');
                      $('#spnMonthly').text('69.00');
                      var managaerTab = parseInt($('#spnManageTab').text());
                      var setupFee = parseInt($('#spnSetup').text());
                      var monthlyFee = parseInt($('#spnMonthly').text());
                      TotalPrice = managaerTab +setupFee+monthlyFee;
                      $('#spnTotal').text(TotalPrice+'.00');
              </script>");
}
else if ($_GET["plan"]=="premium")
{
        echo("<script type='text/javascript' language='javascript'>
                    $('#rbPremium').attr('checked','true');
                    $('#spnMonthly').text('129.00');
                    var managaerTab = parseInt($('#spnManageTab').text());
                    var setupFee = parseInt($('#spnSetup').text());
                    var monthlyFee = parseInt($('#spnMonthly').text());
                    TotalPrice = managaerTab +setupFee+monthlyFee;
                    $('#spnTotal').text(TotalPrice+'.00');
            </script>");
}
?>
<?php mysqli_close($mysqli);?>