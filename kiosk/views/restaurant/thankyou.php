<META http-equiv="refresh" content="30; URL=<?=$SiteUrl.$objRestaurant->url."/?kiosk=1" ?>">
<?php 
if (isset($_POST["btnSubmit"]))
{
    $objMail->sendTo($_SESSION["confimration_email"],"Order Confirmation",$_POST["txtEmail"]);
    redirect($SiteUrl.$objRestaurant->url."/?kiosk=1");
}

if (is_numeric($loggedinuser->id))
{
    $clone=$cart->myclone();
}

if( isset($clone))
{
    if($clone->isempty()|| $clone->order_created===0) 
    {
        redirect($SiteUrl .$objRestaurant->url ."/?item=menu&kiosk=1" );
        exit;
    }
}
else 
{
    if($cart->isempty()|| $cart->order_created===0) 
    {
        redirect($SiteUrl .$objRestaurant->url ."/?item=menu&kiosk=1" );
        exit;
    }
?>
<style type="text/css">
    #ContentImages{ width:975px; /*border:#999 solid 1px;*/ /*padding:50px*/;}
    #ImageOrderItsWay{ float:left; padding:30px 70px 30px 30px; border:#999 solid 1px; /*width:449px;*/}
    #ImageAddThis{ float:left; border:#999 solid 1px; /*width:455px;*/ margin-top:-19px; padding-top: 26px; padding-left: 80px; padding-right:75px; }
    #OrderInformationContent{ padding:20px; border:#999 solid 1px; width: 940px;}
    #TableInfo1{ padding-left:20px; padding-top:30px;}
    #Total{ font-size:13px; font-weight:bold; }
</style>
<form action="" method="post">
<div id="thank_you_area" style="padding:5px; float:none;">
    <div id="ContentImages">
        <table cellspacing="0" bordercolor="#999999" style="width: 100%;" border="1">
            <tr>
                <td style="padding:30px 70px 30px 30px;">
                        <img src="../images/YourOrderNowItsWay.png">
                </td>
                <td style="text-align: center; vertical-align: middle; width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding-top: 26px; ">							
                                <input type="button" onclick="location.href='<?=$SiteUrl.$objRestaurant->url."/?kiosk=1";?>'" name="btnStart" id="btnStart" Value="Start New Order" class="delivery_button" style="margin: 0px !important; cursor: hand; cursor: pointer;" />
                            </td>
                        </tr>        
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 20px; padding-left: 70px; text-align: left !important;">
                                <strong>Want to get an Email receipt? Enter you email</strong><br />
                                <input type="text" placeholder="Email Address" id="txtEmail" name="txtEmail" style="width: 250px; height: 30px; font-size: 16px;" />
                                <input id="btnSubmit" name="btnSubmit" value="Send" type="submit" class="pickup_button" style="height: 30px; width: 60px;" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <span style="margin-top: 15px;">
        <br />
        Your order has been received. Should you have any questions, please do not hesitate to contact us.
        <br />
        <br />
    </span>
    <div id="order_information_div" style="float: left; margin-left: 20px;">
        <p style="color:#999; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold;">ORDER INFORMATION</p>
        <div id="order_information">
            <ul style="width:120px;">
                <li>Merchant:</li>
                <li>Order Number:</li>
                <li>Description:</li>
                <li>Invoice Number:</li>
            </ul>
            <ul>
                <li>
                    <?=$objRestaurant->name; ?>
                </li>
                <li>
                    <?=$cart->order_id; ?>
                </li>
                <li>
                    Customer Order
                </li>
                <li>
                    <?=  $_SESSION["invoice_number_thankyou"] ?>
                </li>
            </ul>
            <div style="clear:left"></div>
            <div class="vertical_line" style="margin:0px 10px 10px 0px;">&nbsp;</div>
            <div id="Total">Total:&nbsp;US $
                <?=$cart->grand_total() ?>
            </div>
            <div style="clear:left"></div>
        </div>
    </div>
    <div class="clear"></div>
    <br/>
</div> 
</form>
    <?php
    $cart->createclone();
    $cart->destroysession();
}

if($objRestaurant->yelp_review_request == 1 && !empty($objRestaurant->yelp_restaurant_url))
{
?>
    <div id="yelp_review">
    <a href="<?=$objRestaurant->yelp_restaurant_url?>" target="_blank"><img src="<?=$SiteUrl?>images/Yelp-Review-Button.jpg" width="236px" style="float:left;"></a>
        <div style="font-size:19px;padding-top: 83px;">We want you to love your meal.
        If you do, please leave us a <a href="<?=$objRestaurant->yelp_restaurant_url?>" target="_blank">Yelp review</a>.
        If you are unsatisfied for any reason please <a href="mailto:<?=$objRestaurant->email?>">let us know</a> so we can make it right
        </div>
    </div>
<?php 
} 
?>
<div class="clear"></div>
