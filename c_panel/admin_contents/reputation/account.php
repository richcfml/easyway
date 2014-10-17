<?php include('nav.php'); ?>
<?php $srid = $Objrestaurant->srid; ?>
<?php $country_obj = new clscountry(); ?>
<script>
    $(document).ready(function() {
        //when Alert Tab Save Settings button is press
        $("#alertSubmit").on('click', function(e) {
            if ($('#email').val() == "") {
                $('#email').addClass('required');
                return;
            } else {
                $('#email').removeClass('required');
                var mUrl = '';
                mUrl = "admin_contents/reputation/ajax.php?vendastaApi=1&call=saveAlert&srid=<?= $srid ?>";
                $.ajax({
                    url: mUrl,
                    type: 'POST',
                    data: {
                        email: $('#email').val(),
                        altEmail1: $('#altEmail1').val(),
                        altEmail2: $('#altEmail2').val(),
                        altEmail3: $('#altEmail3').val(),
                        altEmail4: $('#altEmail4').val(),
                        altEmail5: $('#altEmail5').val()
                    },
                    success: function(data)
                    {
                        alert(data);
                    },
                    error: function(data)
                    {
                        alert('Error occurred.');
                    }
                });
            }
        });

        //when Contact info Tab Save Settings button is press
        $("#contactSubmit").on('click', function(e) {
            if ($('#firstname').val() == "") {
                $('#firstname').addClass('required');
                return;
            }
            if ($('#lastname').val() == "") {
                $('#lastname').addClass('required');
                return;
            }

            $('#firstname').removeClass('required');
            $('#lastname').removeClass('required');
            var mUrl = '';
            mUrl = "admin_contents/reputation/ajax.php?vendastaApi=1&call=saveContact&srid=<?= $srid ?>";
            $.ajax({
                url: mUrl,
                type: 'POST',
                data: {
                    firstname: $('#firstname').val(),
                    lastname: $('#lastname').val()
                },
                success: function(data)
                {
                    alert(data);
                },
                error: function(data)
                {
                    alert('Error occurred.');
                }
            });
        });

        //when Information Tab Save Settings button is pressed        
        $("#infoSubmit").on('click', function(e) {
            if ($('#businessName').val() == "") {
                $('#businessName').addClass('required');
                return;
            }
            if ($('#country').val() == "") {
                $('#country').addClass('required');
                return;
            }
            if ($('#address').val() == "") {
                $('#address').addClass('required');
                return;
            }
            if ($('#city').val() == "") {
                $('#city').addClass('required');
                return;
            }
            if ($('#state').val() == "") {
                $('#state').addClass('required');
                return;
            }
            if ($('#zip').val() == "") {
                $('#zip').addClass('required');
                return;
            }

            $('#businessName').removeClass('required');
            $('#country').removeClass('required');
            $('#address').removeClass('required');
            $('#city').removeClass('required');
            $('#state').removeClass('required');
            $('#zip').removeClass('required');

            var mUrl = '';
            mUrl = "admin_contents/reputation/ajax.php?vendastaApi=1&call=saveInformation&srid=<?= $srid ?>";
            $.ajax({
                url: mUrl,
                type: 'POST',
                data: {
                    businessName: $('#businessName').val(),
                    country: $('#country').val(),
                    address: $('#address').val(),
                    city: $('#city').val(),
                    state: $('#state').val(),
                    zip: $('#zip').val(),
                    worknumber1: $('#worknumber1').val(),
                    worknumber2: $('#worknumber2').val(),
                    worknumber3: $('#worknumber3').val(),
                    worknumber4: $('#worknumber4').val(),
                    worknumber5: $('#worknumber5').val(),
                    worknumber6: $('#worknumber6').val(),
                    website: $('#website').val(),
                    service: $('#service').val(),
                    competitor1: $('#competitor1').val(),
                    competitor2: $('#competitor2').val(),
                    competitor3: $('#competitor3').val(),
                    keyperson1: $('#keyperson1').val(),
                    keyperson2: $('#keyperson2').val(),
                    keyperson3: $('#keyperson3').val(),
                    commonBusinessName1: $('#commonBusinessName1').val(),
                    commonBusinessName2: $('#commonBusinessName2').val(),
                    commonBusinessName3: $('#commonBusinessName3').val()
                },
                success: function(data)
                {
                    alert(data);
                },
                error: function(data)
                {
                    alert('Error occurred.');
                }
            });
        });

    });
</script>
<?php
//$url = "https://reputation-intelligence-api.vendasta.com/api/v2/account/get/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=" . $srid;
//$url = "https://reputation-intelligence-api.vendasta.com/api/v2/account/update/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=" . $srid;

$mCompError = "";
$srid = $Objrestaurant->srid;
$url = 'https://reputation-intelligence-api.vendasta.com/api/v2/account/get/?apiUser=ESWY&apiKey=_Azt|hmKHOyiJY59SDj2qsHje.gxVVlcwEbmZuP1&srid=' . $srid;
$mch = curl_init();
curl_setopt($mch, CURLOPT_URL, $url);
curl_setopt($mch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, 0);

$visResult = curl_exec($mch);
curl_close($mch);
unset($mch);
$visResult = json_decode($visResult);
$visResult = (object) $visResult;

foreach($visResult->data as $mResult){

$email = empty($mResult['email']) ? "" : $mResult['email'];

if (!empty($mResult['alternateEmail'])) {
    $alternateEmail1 = empty($mResult['alternateEmail']['4']) ? "" : $mResult['alternateEmail']['4'];
    $alternateEmail2 = empty($mResult['alternateEmail']['1']) ? "" : $mResult['alternateEmail']['1'];
    $alternateEmail3 = empty($mResult['alternateEmail']['0']) ? "" : $mResult['alternateEmail']['0'];
    $alternateEmail4 = empty($mResult['alternateEmail']['3']) ? "" : $mResult['alternateEmail']['3'];
    $alternateEmail5 = empty($mResult['alternateEmail']['2']) ? "" : $mResult['alternateEmail']['2'];
}

$firstname = empty($mResult['firstName']) ? "" : $mResult['firstName'];
$lastname = empty($mResult['lastName']) ? "" : $mResult['lastName'];


$companyName = empty($mResult['companyName']) ? "" : $mResult['companyName'];
$address = empty($mResult['address']) ? "" : $mResult['address'];
$city = empty($mResult['city']) ? "" : $mResult['city'];
$country = empty($mResult['country']) ? "" : $mResult['country'];
$state = empty($mResult['state']) ? "" : $mResult['state'];
$zip = empty($mResult['zip']) ? "" : $mResult['zip'];
$website = empty($mResult['website']) ? "" : $mResult['website'];

//$competitor1 = empty($mResult['competitor']['0']) ? "" : $mResult['competitor']['0'];
//$competitor2 = empty($mResult['competitor']['1']) ? "" : $mResult['competitor']['1'];
//$competitor3 = empty($mResult['competitor']['2']) ? "" : $mResult['competitor']['2'];

$phoneNum1 = empty($mResult['workNumber']['0']) ? "" : $mResult['workNumber']['0'];
$phoneNum2 = empty($mResult['workNumber']['1']) ? "" : $mResult['workNumber']['1'];
$phoneNum3 = empty($mResult['workNumber']['2']) ? "" : $mResult['workNumber']['2'];
$phoneNum4 = empty($mResult['workNumber']['3']) ? "" : $mResult['workNumber']['3'];
$phoneNum5 = empty($mResult['workNumber']['4']) ? "" : $mResult['workNumber']['4'];
$phoneNum6 = empty($mResult['workNumber']['5']) ? "" : $mResult['workNumber']['5'];

$keyPerson1 = empty($mResult['employee']['0']) ? "" : $mResult['employee']['0'];
$keyPerson2 = empty($mResult['employee']['1']) ? "" : $mResult['employee']['1'];
$keyPerson3 = empty($mResult['employee']['2']) ? "" : $mResult['employee']['2'];

$commonBusinessName1 = empty($mResult['commonCompanyName']['0']) ? "" : $mResult['commonCompanyName']['0'];
$commonBusinessName2 = empty($mResult['commonCompanyName']['1']) ? "" : $mResult['commonCompanyName']['1'];
$commonBusinessName3 = empty($mResult['commonCompanyName']['2']) ? "" : $mResult['commonCompanyName']['2'];

$service1 = empty($mResult['service']['0']) ? "" : $mResult['service']['0'] . ",";
$service2 = empty($mResult['service']['1']) ? "" : $mResult['service']['1'] . ",";
$service3 = empty($mResult['service']['2']) ? "" : $mResult['service']['2'] . ",";
$service4 = empty($mResult['service']['3']) ? "" : $mResult['service']['3'] . ",";
$service5 = empty($mResult['service']['4']) ? "" : $mResult['service']['4'] . ",";
$service6 = empty($mResult['service']['5']) ? "" : $mResult['service']['5'] . ",";

$service = $service1 . "" . $service2 . "" . $service3 . "" . $service4 . "" . $service5 . "" . $service6;
$service = trim($service, ",");

}

?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script>
    $(function() {
        $("#tabs").tabs();
    });
</script>
<style>
    .ui-widget-header{
        padding: 0px;
        background: none;
        border-width: 0px;
        border-bottom-width: 1px;
        border-bottom-color: #aaaaaa;
    }
</style>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Information</a></li>
        <li><a href="#tabs-2">Alerts</a></li>
        <li><a href="#tabs-3">Contact Info</a></li>
    </ul>
    <div id="tabs-1">
        <h1 class="mainH1">Enhance Your Monitoring Service With Additional Information</h1>

        <form id="alerts_form" class="ajaxform leftLabel page" method="post" action="" accept-charset="utf-8">

            <p>Please verify the following information</p>
            <div class="tabMainDiv">
                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">Business Name<span class="req">*</span></label>
                    <div class="inputEmailTxt">
                        <input type="text" class="emailInput" name="businessName" id="businessName" value="<?= $companyName ?>">
                    </div>
                </div>

                <div style="clear:both"></div>

                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">Address<span class="req">*</span></label>
                    <div class="inputEmailTxt">
                        <!--<input type="text" class="emailInput" name="country" id="country" value="<?= $country ?>">-->
                        <select name="country" id="country" class="emailInput" style="margin-right:30px;width:230px" >
<?= $country_obj->get_country_drop_down(@$country) ?>
                        </select>
                        <input type="text" class="emailInput" placeholder="Enter street address" name="address" id="address" value="<?= $address ?>">
                        <input type="text" class="emailInput" placeholder="Enter city" name="city" id="city" value="<?= $city ?>">
                        <input type="text" class="emailInput" placeholder="Enter state" name="state" id="state" value="<?= $state ?>">
                        <input type="text" class="emailInput" placeholder="Enter postal/zip code" name="zip" id="zip" value="<?= $zip ?>">
                    </div>
                </div>
                <div style="clear:both"></div>

                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">Phone Number</label>
                    <div class="inputEmailTxt">
                        <input type="text" class="emailInput" name="worknumber1" id="worknumber1" value="<?= $phoneNum1 ?>">
                        <input type="text" class="emailInput" name="worknumber2" id="worknumber2" value="<?= $phoneNum2 ?>">
                        <input type="text" class="emailInput" name="worknumber3" id="worknumber3" value="<?= $phoneNum3 ?>">
                        <input type="text" class="emailInput" name="worknumber4" id="worknumber4" value="<?= $phoneNum4 ?>">
                        <input type="text" class="emailInput" name="worknumber5" id="worknumber5" value="<?= $phoneNum5 ?>">
                        <input type="text" class="emailInput" name="worknumber6" id="worknumber6" value="<?= $phoneNum6 ?>">
                    </div>
                    <div class="help-text">Primary and alternate phone numbers for your business</div>
                </div>
                <div style="clear:both"></div>

                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">Website</label>
                    <div class="inputEmailTxt">
                        <input type="text" class="emailInput" name="website" id="website" value="<?= $website ?>">
                    </div>
                    <div class="help-text">The URL for your business's website</div>
                </div>

                <div style="clear:both"></div>

                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">Service Keywords</label>
                    <div class="inputEmailTxt" style="margin-bottom: 47px;">
                        <input type="text" class="emailInput" name="service" id="service" value="<?= $service ?>">
                    </div>
                    <div class="help-text">Separate each service with a comma. Max 3 services allowed.<br>eg. Teaching, Architecture, Lion Taming</div>
                </div>
                <div style="clear:both"></div>

                <!--<div class="inputEmailDiv">
                    <label class="inputEmailLbl">Competitors</label>
                    <div class="inputEmailTxt">
                        <input type="text" class="emailInput" name="competitor1" id="competitor1" value="<?= $competitor1 ?>">
                        <input type="text" class="emailInput" name="competitor2" id="competitor2" value="<?= $competitor2 ?>">
                        <input type="text" class="emailInput" name="competitor3" id="competitor3" value="<?= $competitor3 ?>">
                    </div>
                    <div class="help-text">Adding competitors will improve the results for Share of Voice</div>
                </div>
                <div style="clear:both"></div>-->

                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">Key Persons</label>
                    <div class="inputEmailTxt">
                        <input type="text" class="emailInput" name="keyperson1" id="keyperson1" value="<?= $keyPerson1 ?>">
                        <input type="text" class="emailInput" name="keyperson2" id="keyperson2" value="<?= $keyPerson2 ?>">
                        <input type="text" class="emailInput" name="keyperson3" id="keyperson3" value="<?= $keyPerson3 ?>">
                    </div>
                    <div class="help-text">Enter up to 3 key people that work at this business</div>
                </div>
                <div style="clear:both"></div>

                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">Common Business Names</label>
                    <div class="inputEmailTxt">
                        <input type="text" class="emailInput" name="commonBusinessName1" id="commonBusinessName1" value="<?= $commonBusinessName1 ?>">
                        <input type="text" class="emailInput" name="commonBusinessName2" id="commonBusinessName2" value="<?= $commonBusinessName2 ?>">
                        <input type="text" class="emailInput" name="commonBusinessName3" id="commonBusinessName3" value="<?= $commonBusinessName3 ?>">
                    </div>
                    <div class="help-text">Less formal names people might refer to your business as. For example "Joe's Shoes" instead of "Joe's Shoe Company Inc."</div>
                </div>
            </div>
            <div style="clear:both"></div>
            <div style="margin-left:14px;">
                <input type="button" value="Save Settings" class="SaveBtn" name="infoSubmit" id="infoSubmit">
            </div>
        </form>
    </div>
    <div id="tabs-2">
        <h1 class="mainH1">Alerts</h1>

        <form id="alerts_form" class="ajaxform leftLabel page" method="post" action="" accept-charset="utf-8">

            <h2 class="mainH2">Send to:</h2>
            <div class="tabMainDiv">
                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">Preferred Email Address <span class="req">*</span></label>
                    <div class="inputEmailTxt">
                        <input type="text" class="emailInput" name="email" id="email" value="<?= $email ?>">
                    </div>
                </div>

                <div style="clear:both"></div>

                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">Additional Email Addresses</label>
                    <div class="inputEmailTxt">
                        <input type="text" class="emailInput" name="altEmail1" id="altEmail1" value="<?= $alternateEmail1 ?>">
                        <input type="text" class="emailInput" name="altEmail2" id="altEmail2" value="<?= $alternateEmail2 ?>">
                        <input type="text" class="emailInput" name="altEmail3" id="altEmail3" value="<?= $alternateEmail3 ?>">
                        <input type="text" class="emailInput" name="altEmail4" id="altEmail4" value="<?= $alternateEmail4 ?>">
                        <input type="text" class="emailInput" name="altEmail5" id="altEmail5" value="<?= $alternateEmail5 ?>">
                    </div>
                </div>
            </div>
            <div style="clear:both"></div>
            <div style="margin-left:14px;">
                <input type="button" value="Save Settings" class="SaveBtn" id="alertSubmit" name="alertSubmit">
            </div>
        </form>
    </div>
    <div id="tabs-3">
        <form method="post" action="" enctype="multipart/form-data" accept-charset="utf-8">

            <!-- Personal Information -->
            <h1 class="mainH1">Contact Info</h1>
            <p class="introduction">
                This information will be used if our support department needs to contact you.
            </p>
            <div class="tabMainDiv">
                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">First Name<span class="req">*</span></label>
                    <div class="inputEmailTxt">
                        <input type="text" class="emailInput" name="firstname" id="firstname" value="<?= $firstname ?>">
                    </div>
                </div>

                <div style="clear:both"></div>

                <div class="inputEmailDiv">
                    <label class="inputEmailLbl">Last Name<span class="req">*</span></label>
                    <div class="inputEmailTxt">
                        <input type="text" class="emailInput" name="lastname" id="lastname" value="<?= $lastname ?>">
                    </div>
                </div>
            </div>
            <div style="clear:both"></div>
            <br><br>
            <div style="margin-left:14px;">
                <input type="button" value="Save Settings" class="SaveBtn" name="contactSubmit" id="contactSubmit">
            </div>


        </form>
    </div>
</div>