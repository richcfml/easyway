<?php
// Add your MD5 Setting.
$cart_total = $cart->grand_total();
if (!is_numeric($cart_total) || $cart_total <= 0) {
    redirect($SiteUrl . $objRestaurant->url . "/");
//    header("location: " . $SiteUrl . $objRestaurant->url . "/");
    exit;
}
if (!isset($_SERVER['HTTPS'])) {
//		 header("location: ". $SiteUrl .$objRestaurant->url ."/" );exit;
}
$mZipPostal = "Zip Code";
$mStateProvince = "State";
	
if ($objRestaurant->region == "2") //Canada
{
	$mZipPostal = "Postal Code";
	$mStateProvince = "Province";
}


if (isset($_POST['btnconfirmorder1'])) {
    $success = 0;
    if ($objRestaurant->payment_gateway == "authoriseDotNet")
        $objRestaurant->payment_gateway = "AuthorizeNet";
    require_once 'classes/gateways/' . $objRestaurant->payment_gateway . '.php';

    if ($success == 1) {
        if ($objRestaurant->tokenization == 1) {
            $secure_data = $_POST['x_card_num'];
            $type = substr($secure_data, 0, 1);
        }
        // Added by Saad 22-Sept-2014 -- if user do not wish to save card info.. i.e tokenization checkbox not selected
        $creditCardType = 0;
        if(!isset($gateway_token))
        {
            $creditCardType = substr($secure_data, 0,1);
        }
        //Modified01082013
        $cc = substr($secure_data, -4, 4);


        $_POST['x_card_num'] = '';
        include "submit_order.php";
    }
} else {
    if (isset($_POST['cart_delivery_charges'])) {
        $objRestaurant->delivery_charges = $_POST['cart_delivery_charges'];
        $cart->rest_delivery_charges = $objRestaurant->delivery_charges;
        $cart_total = $cart->grand_total();
        $cart->save();
    }
    $serving_date = $_POST['serving_date'];
    $serving_time = $_POST['serving_time'];
    $x_first_name = $loggedinuser->cust_your_name;
    $x_last_name = $loggedinuser->LastName;
    $x_address = trim($loggedinuser->street1 . " " . $loggedinuser->street2);
    $x_city = $loggedinuser->cust_ord_city;
    $x_state = $loggedinuser->cust_ord_state;
    $x_zip = $loggedinuser->cust_ord_zip;
    $x_phone = $loggedinuser->cust_phone1;
    $x_email = $loggedinuser->cust_email;
}



/* if(isset($_POST['btnconfirmorder'])){

  require_once 'lib/authorize_api/AuthorizeNet.php';
  define("AUTHORIZENET_API_LOGIN_ID",$objRestaurant->authoriseLoginID);    // Add your API LOGIN ID
  define("AUTHORIZENET_TRANSACTION_KEY",$objRestaurant->transKey); // Add your API transaction key
  define("AUTHORIZENET_SANDBOX",false);       // Set to false to test against production
  define("TEST_REQUEST", "FALSE");           // You may want to set to true if testing against production
  define("AUTHORIZENET_MD5_SETTING","");

  extract($_POST);

  $transaction = new AuthorizeNetAIM;
  $transaction->setSandbox(AUTHORIZENET_SANDBOX);


  $transaction->setFields(
  array(
  'amount' => $cart_total,
  'card_num' => $x_card_num,
  'exp_date' => $x_exp_date,
  'first_name' => $x_first_name,
  'last_name' => $x_last_name,
  'address' => $x_address,
  'city' => $x_city,
  'state' => $x_state,
  'country' => "USA",
  'zip' => $x_zip,
  'email' => $x_email,
  'ship_to_first_name' => $x_first_name,
  'ship_to_last_name' => $x_last_name,
  'ship_to_address' => $x_address,
  'ship_to_city' => $x_city,
  'ship_to_state' => $x_state,
  'ship_to_zip' => $x_zip,
  'ship_to_country' => "USA",
  'invoice_num' => date('YmdHis')
  )
  );


  $_POST['x_exp_date']='';
  $_POST['x_card_code']='';
  $response = $transaction->authorizeAndCapture();
  if ($response->approved) {
  $_POST['payment_method']=1;
  $_POST['invoice_number']=$response->invoice_number;
  if($objRestaurant->tokenization=="1"){
  $secure_data = $_POST['x_card_num'];
  $type=  substr($secure_data, 0);
  $cc=  substr($secure_data, -4, 4);
  }

  $_POST['x_card_num']='';

  include "submit_order.php";
  exit;
  } else {
  $_SESSION['GATEWAY_RESPONSE']=$response->error_message;
  header("location: ". $SiteUrl .$objRestaurant->url ."/?item=failed" );exit;
  }

  } */
?>
<style type="text/css">
    h3{
        margin:10px 0 0 0; 
        border-bottom:1px solid #CCC;
    }
</style>

<section class="menu_list_wrapper">

    <form action="" method="post" id="payment_form" name="payment_form"  >
        <input type="hidden" name="serving_date" value="<?= $serving_date ?>">
        <input type="hidden" name="serving_time" value="<?= $serving_time ?>">

        <h1>Order confirmation</h1>

        <h3>Payment Information <span  style="float:right;margin-right:10px;text-decoration:underline">Total : $<?= $cart_total ?></span></h3>

        <div class="margintop normal">
            <div class="left">&nbsp;</div>
            <div class="rightItem">
                <img width="43" height="26" alt="Visa" title="Visa" src="../images/V.gif">
                <img width="41" height="26" alt="MasterCard" title="MasterCard" src="../images/MC.gif">
                <img width="40" height="26" alt="American Express" title="American Express" src="../images/Amex.gif">
                <img width="40" height="26" alt="Discover" title="Discover" src="../images/Disc.gif">
                <img width="35" height="26" alt="Diners Club" title="Diners Club" src="../images/DC.gif">
            </div>
            <div class="clear"></div>
        </div>


        <?
        $newcard = '';
        $card_type = '';
        $card_num = '';
        $card_ed = '';

        if ($objRestaurant->tokenization == 1 && count($loggedinuser->arrTokens)) {
            ?>
            <h3>Choose card on file</h3>
            <div class="margintop normal">
                <div class="left">Choose card on file</div>
                <div  class="rightItem"><select id="card_token" name="card_token">
                        <?php
                        foreach ($loggedinuser->arrTokens as $token) {
                            $card_type = '';
                            $card_num = '';
                            $card_ed = '';
                            $card_ccv = '';
                            if ($token->data_type == AMEX)
                                $card_type = "American Express";
                            else if ($token->data_type == VISA)
                                $card_type = "VISA";
                            else if ($token->data_type == MASTER)
                                $card_type = "MasterCard";
                            else if ($token->data_type == DISCOVER)
                                $card_type = "Discover";
                            ?>
                            <option value="<?= $token->data_2 ?>"><?= $card_type . " ending in " . $token->data_1 ?></option>
    <? } $newcard = ' hidden';
    $card_num = '0000000000000000';
    $card_ed = '0000'; ?>
                        <option value="0">New Card</option>
                    </select></div>

            </div>
<? } ?>
        <div class="newcard<?= $newcard ?>">

            <div class="normal margintop">
                <div class="left">Card Number:</div>
                <div class="rightItem">
                    <input type="text"  size="30"  maxlength="16"   tabindex="2" id="x_card_num" name="x_card_num"  value="<?= $card_num ?>"  /><br/><span class="red">* (enter number without spaces or dashes)</span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="margintop normal">
                <div class="left">Expiration Date:</div>
                <div class="rightItem">
                    <input type="text"  size="4"  maxlength="4"   tabindex="3" id="x_exp_date" name="x_exp_date"   value="<?= $card_ed ?>"  /><span class="red">* (mmyy)</span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="margintop normal">
                <div class="left">CCV:</div>
                <div class="rightItem">
                    <input type="text"  size="4"  maxlength="4"   tabindex="3" id="x_card_code" name="x_card_code" value="<?= $card_ed ?>"    /><br/><span class="red">* (credit card security code,please check on the backside of your card)</span>
                </div>
                <div class="clear"></div>
            </div>

<? //if (is_numeric($loggedinuser->id) && $objRestaurant->tokenization == 1)
    // Changed by Saad 22-Sept-2014 --> Allow guest user to save credit card for first time during registration.
    if($objRestaurant->tokenization==1)
    { ?>
                <div class="margintop normal">
                    <div  class="left" >
                        <u> I would like to save my credit card token for next purchase</u>
                    </div>
                    <div  class="rightItem" >
                        <input type="checkbox" name="tokenization" id="tokenization" value="1"/> 
                    </div>
                    <div class="clear"></div>
                </div>
<? } ?>

        </div>
        <h3>Billing Information</h3>
        <div class="margintop normal">
            <div class="left">First Name:</div>
            <div class="rightItem">
                <input type="text"  size="30"  maxlength="150" title="Name" tabindex="4" id="x_first_name" name="x_first_name" value="<?= $x_first_name ?>"  />
            </div>
            <div class="clear"></div>
        </div>
        <div class="margintop normal">
            <div class="left">Last Name:</div>
            <div class="rightItem">
                <input type="text"  size="30"  maxlength="150" title="Name" tabindex="5" id="x_last_name" name="x_last_name" value="<?= $x_last_name ?>"  />
            </div>
            <div class="clear"></div>
        </div>

        <div class="margintop normal">
            <div class="left">Address:</div>
            <div class="rightItem">
                <input type="text"  size="30"  maxlength="150" title="Address" tabindex="6" id="x_address" name="x_address"  value="<?= $x_address ?>"/>
            </div>
            <div class="clear"></div>
        </div>
        <div class="margintop normal">
            <div class="left">City:</div>
            <div class="rightItem">
                <input type="text"  size="30"  maxlength="150" title="City" tabindex="7" id="x_city" name="x_city"  value="<?= $x_city ?>" />
            </div>
            <div class="clear"></div>
        </div>
        <div class="margintop normal">
            <div class="left"><?=$mStateProvince?>:</div>
            <div class="rightItem">
                <input type="text"  size="30"  maxlength="150" title="State" tabindex="8" id="x_state" name="x_state"  value="<?= $x_state ?>" />
            </div>
            <div class="clear"></div>
        </div>
        <div class="margintop normal">
            <div class="left"><?=$mZipPostal?>:</div>
            <div class="rightItem">
                <input type="text"  size="30"  maxlength="150" title="Zip" tabindex="9" id="x_zip" name="x_zip"  value="<?= $x_zip ?>"/>
            </div>
            <div class="clear"></div>
        </div>

        <div class="margintop normal">
            <div class="left">Email:</div>
            <div class="rightItem">
                <input type="text"  size="30"  maxlength="150" title="Email Address" tabindex="20" id="x_email" name="x_email"  value="<?= $x_email ?>"/>
            </div>
            <div class="clear"></div>
        </div>

        <div class="margintop normal">
            <div class="left">Phone:</div>
            <div class="rightItem">
                <input type="text"  size="30"  maxlength="150" title="Phone Number" tabindex="11" id="x_phone" name="x_phone"  value="<?= $x_phone ?>"/>
            </div>
            <div class="clear"></div>
        </div>


        <div class="rightalign">
            <input type="hidden" id="btnsubmit" name="btnconfirmorder1" value=" Submit "  class="button blue">
            <input type="submit" id="submitbutton" name="btnconfirmorder" value=" Submit "  class="button blue">
        </div>
        <script type="text/javascript">
            $(function() {
                $("#card_token").change(function() {
                    if ($(this).val() == "0") {
                        $("#x_card_num").val('');
                        $("#x_exp_date").val('');
                        $("#x_card_code").val('');
                        $(".newcard").show('slow');


                    } else {

                        $(".newcard").hide('slow', function() {
                            $("#x_card_num").val('0000000000000000');
                            $("#x_exp_date").val('0000');
                            $("#x_card_code").val('0000');

                        });

                    }
                });

                $("#payment_form").validate({
                    rules: {
                        x_card_num: {required: true, minlength: 16},
                        x_exp_date: {required: true, minlength: 4},
                        x_card_code: {required: true},
                        x_first_name: {required: true},
                        x_last_name: {required: true},
                        x_address: {required: true, minlength: 3},
                        x_city: {required: true, minlength: 3},
                        x_state: {required: true, minlength: 2},
                        x_zip: {required: true, minlength: 3},
                    },
                    messages: {
                        x_card_num: {
                            required: "please enter your credit card number",
                            minlength: "please enter a valid credit card number"
                        },
                        x_exp_date: {
                            required: "please enter your credit card expiry date",
                            minlength: "please enter a valid credit card expiry date"
                        },
                        x_card_code: {
                            required: "please enter your credit card security code",
                            minlength: "please enter a valid  credit card security code"
                        },
                        x_first_name: {
                            required: "please enter your first name",
                        },
                        x_last_name: {
                            required: "please enter your last name",
                        },
                        x_address: {
                            required: "please enter your street address",
                            minlength: "please enter a valid address"
                        },
                        x_city: {
                            required: "please enter your city",
                            minlength: "please enter a valid city"

                        },
                        x_state: {
                            required: "please enter your <?=$mStateProvince?>",
                            minlength: "please enter a valid <?=$mStateProvince?>"

                        },
                        x_zip: {
                            required: "please enter your <?=$mZipPostal?>",
                            minlength: "please enter a valid <?=$mZipPostal?>"

                        }
                    },
                    errorElement: "div",
                    errorClass: "alert-error",
                    submitHandler: function(form) {

                        $("#submitbutton").attr('disabled', 'disabled');
                        form.submit();

                    }
                });
                $("#payment_form").submit(function() {
                    //  $("#submitbutton").attr('disabled', 'disabled'); 
                });

            });
        </script>
    </form>
    <br/>
    <br/>
</section>