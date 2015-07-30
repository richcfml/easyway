<?php
$mDIDNumber = '';
if(isset($_POST['submit']))
{
	$order_destination			 = @$_POST['order_destination'];
 	$email_fromat				 = @$_POST['email_fromat'];
	$payment_gateway 			 = @$_POST['payment_gateway'];
	$tokenization				 = @$_POST['tokenization'];
	$payment_gateway_login_id	 = @$_POST['payment_gateway_login_id'];
	$payment_gateway_trans_Key	 = @$_POST['payment_gateway_trans_Key']; 
	$vendor_id					 = @$_POST['vendor_id']; 
        $mURL					 = @$_POST['txtURL'];
	
	$did_number=$_POST['did_number']; 
 	$refund_password= @$_POST['refund_password']; 
	
	if($did_number=='')
	{
		$did_number='0';
	}
	
	$mDIDNumber = $did_number;
	$mDupFlag = false;
	
	if ((trim($did_number!='')) && (trim($did_number)!='0') && (trim($did_number)!=trim($_POST["did_number_p"])))
	{
		$mDupQuery = "SELECT COUNT(*) AS DidCount FROM resturants WHERE TRIM(did_number)='".trim($did_number)."'";
		$mDupRes = dbAbstract::Execute($mDupQuery, 1);
		if (dbAbstract::returnRowsCount($mDupRes, 1)>0)
		{
			$mDupRow = dbAbstract::returnObject($mDupRes, 1);
			if ($mDupRow->DidCount>0)
			{
				$mMessage = "Did number already exists in system.";
				$mDupFlag = true;
			}
		}
	}
	
	if (!$mDupFlag)
	{
		dbAbstract::Update("UPDATE resturants SET URL='".$mURL."', VendorID='".$vendor_id."', order_destination='".$order_destination."', rest_order_email_fromat='$email_fromat', payment_gateway='$payment_gateway', tokenization= '$tokenization',authoriseLoginID='$payment_gateway_login_id', transKey= '$payment_gateway_trans_Key' ,refund_password='$refund_password',did_number='$did_number' WHERE id= ". $Objrestaurant->id, 1);
		$Objrestaurant->did_number=$did_number;
		$mMessage = "Restaurant updated successfully.";
	}
	else
	{
		dbAbstract::Update("UPDATE resturants SET URL='".$mURL."', VendorID='".$vendor_id."',  order_destination='".$order_destination."', rest_order_email_fromat='$email_fromat', payment_gateway='$payment_gateway', tokenization= '$tokenization',authoriseLoginID='$payment_gateway_login_id', transKey= '$payment_gateway_trans_Key' ,refund_password='$refund_password' WHERE id= ". $Objrestaurant->id, 1);
	}

	$Objrestaurant->order_destination=$order_destination;
	$Objrestaurant->rest_order_email_fromat=$email_fromat;
	$Objrestaurant->payment_gateway=$payment_gateway;
	$Objrestaurant->tokenization=$tokenization;
	$Objrestaurant->authoriseLoginID=$payment_gateway_login_id;
	$Objrestaurant->transKey=$payment_gateway_trans_Key;
	$Objrestaurant->refund_password=$refund_password;
	$Objrestaurant->VendorID=$vendor_id;
        $Objrestaurant->URL=$mURL;
	$Objrestaurant->saveToSession();
}
 
include "nav.php"; ?>
 
<div class="form_outer" >
	<form  method="post" action="" id="mailing_list" name="mailing_list" enctype="multipart/form-data">
  		<table width="650" border="0" cellpadding="4" cellspacing="0">
		    <tr style="background-color: white !important;">
				<td colspan="2" style="background-color: white !important;">
					<span style="color: red;"><?=$mMessage?></span>
				</td>
    		</tr>
     		<tr style="height: 10px;">
				<td colspan="2"></td>
    		</tr>
			<tr align="left" valign="top">
      			<td width="200px">
					Order destination
				</td>
      			<td>
      				<input name="order_destination" type="radio" value="email"     id="order_destination"   <?php if($Objrestaurant->order_destination == 'email' ){ echo "checked";} ?>>Email          &nbsp;&nbsp;
            		<input name="order_destination" type="radio" value="fax" id="order_destination"   <?php if($Objrestaurant->order_destination == 'fax' ){ echo "checked";} ?> >Fax              &nbsp;&nbsp;
            		<input name="order_destination" type="radio" value="POS" id="order_destination"   <?php if($Objrestaurant->order_destination == 'POS' ){ echo "checked";} ?> >POS
      			</td>
    		</tr>
 		   	<tr align="left" valign="top">
		    	<td>
			  		Email format(choose one)
			  	</td>
		      	<td>
      		  		<input name="email_fromat" type="radio" value="plain text"  id="email_fromat" <?php if($Objrestaurant->rest_order_email_fromat == 'plain text' ){ echo "checked";} ?>>Plain Text          &nbsp;&nbsp;
		      		<input name="email_fromat" type="radio" value="pdf"  id="email_fromat" <?php if($Objrestaurant->rest_order_email_fromat == 'pdf' ){ echo "checked";} ?> >PDF Attachment
		      	</td>
    		</tr>
 		    <tr align="left" valign="top">
				<td>
					Payment gateway
				</td>
     			<td>
                    <input name="payment_gateway" type="radio" value="authoriseDotNet"  id="payment_gateway" <?php
                    if ($Objrestaurant->payment_gateway == "authoriseDotNet") {
                        echo "checked";
                    }
                    ?>  onclick="changelabels(this)"/>
                    Authorize Dot Net&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if($Objrestaurant->region != "0") //Changed by Gulfam - QualityClix, If region is not UK(0) then show other payment gateways as well. For UK(0) only Autherize.Net will be shown
					{ 
					?>
                        <input name="payment_gateway" type="radio" value="nmi"  id="payment_gateway" <?php
                        if ($Objrestaurant->payment_gateway == "nmi") 
						{
                            echo "checked";
                        }
                        ?>
                               onclick="changelabels(this)"/>
                        NMI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="payment_gateway" type="radio" value="gge4"  id="payment_gateway" <?php
                        if ($Objrestaurant->payment_gateway == "gge4") 
						{
                            echo "checked";
                        }
                        ?>
                               onclick="changelabels(this)"/>
                        First Data GGe4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="payment_gateway" type="radio" value="suregate"  id="payment_gateway" <?php
                        if ($Objrestaurant->payment_gateway == "suregate") 
						{
                            echo "checked";
                        }
                        ?>
                               onclick="changelabels(this)"/>
                        Suregate
                    <?php 
					}
					?>
                </td>
            </tr>
            <tr align="left" valign="top">
                <td>
                    Enable tokenization
                </td>
                <td>
                    <input name="tokenization" type="radio" value="1"  id="tokenization" <?php
                    if ($Objrestaurant->tokenization == "1") {
                        echo "checked";
                    }
                    ?>>
                    &nbsp;&nbsp Yes           &nbsp;&nbsp;
                    <input name="tokenization" type="radio" value="0"  id="tokenization" <?php
                    if ($Objrestaurant->tokenization == "0") {
                        echo "checked";
                    }
                    ?>>
                    &nbsp;&nbsp No
                </td>
            </tr>
			<?php
				if ($Objrestaurant->payment_gateway == "authoriseDotNet")
				{
					$mUserNameLabel = "Payment gateway login ID";
					$mPasswordLabel = "Payment gateway transaction key";
					$mSureGateDisplay = " style='visibility: hidden;' ";					
				}
				else if ($Objrestaurant->payment_gateway == "nmi")
				{
					$mUserNameLabel = "NMI user name";
					$mPasswordLabel = "NMI password";
					$mSureGateDisplay = " style='visibility: hidden;' ";					
				}
				else if ($Objrestaurant->payment_gateway == "gge4")
				{
					$mUserNameLabel = "GGe4 Gateway ID/Exact ID";
					$mPasswordLabel = "GGe4 Password";
					$mSureGateDisplay = " style='visibility: hidden;' ";					
				}
				else if ($Objrestaurant->payment_gateway == "suregate")
				{
					$mUserNameLabel = "Suregate user name";
					$mPasswordLabel = "Suregate password";
					$mSureGateDisplay = " style='visibility: visible;' ";
				}
				else
				{
					$mUserNameLabel = "Gateway user name";
					$mPasswordLabel = "Gateway password";
					$mSureGateDisplay = " style='visibility: hidden;' ";
				}
			?>
            <tr align="left" valign="top">
                <td id="gatewaynamelabel">
                    <?=$mUserNameLabel?>
                </td>
                <td>
                    <input name="payment_gateway_login_id" type="text" size="40" id="payment_gateway_login_id" value="<?= $Objrestaurant->authoriseLoginID ?>" />
                </td>
            </tr>
            <tr align="left" valign="top">
                <td id="gatewaykeylabel">
                    <?=$mPasswordLabel?>
                </td>
                <td>
                    <input name="payment_gateway_trans_Key" type="text" size="20" id="transKey" value="<?= $Objrestaurant->transKey ?>" />
					&nbsp;&nbsp;
					<span <?=$mSureGateDisplay?> id="trSureGate1">
						Vendor ID
					</span>
					&nbsp;&nbsp;
					<span <?=$mSureGateDisplay?> id="trSureGate2">
						<input name="vendor_id" type="text" size="15" id="vendor_id" value="<?= $Objrestaurant->VendorID ?>" />
					</span>
                </td>
            </tr>
            <tr align="left" valign="top">
                <td> 
                    Refund password
                </td>
                <td>
                    <input name="refund_password" type="password" size="30" maxlength="30" id="refund_password" value="<?= $Objrestaurant->refund_password ?>" /><a href="javascript:void(0);" id="showhidepassword" >Show password</a>
                </td>
            </tr>
            <tr align="left" valign="top">
                <td> 
                    DID Number
                </td>
                <td>
                    <input name="did_number" type="text" size="30" maxlength="30" id="did_number" value="<?php if (trim($mDIDNumber)!="") { echo($mDIDNumber); } else { echo($Objrestaurant->did_number); } ?>" />
					<input name="did_number_p" type="hidden" id="did_number_p" value="<?= $Objrestaurant->did_number ?>" />
                </td>
            </tr>
            <tr align="left" valign="top">
                <td> 
                    URL (Domain)
                </td>
                <td>
                    <input name="txtURL" type="text" size="45" maxlength="200" id="txtURL" value="<?=$Objrestaurant->URL?>" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="submit" value="Save">
                </td>
            </tr>
        </table>
    </form>
</div>
<script >

    $(document).ready(function()
    {
      changelabels($('input[name="payment_gateway"]:checked').length ? $('input[name="payment_gateway"]:checked')[0] : $('input[name="payment_gateway"]:first')[0]);
    });   

    function changelabels(nmi)
    {
        if ($(nmi).val() == 'nmi' && $(nmi).is(':checked'))
        {
            $("#gatewaynamelabel").html("NMI user name");
            $("#gatewaykeylabel").html("NMI password");
			$("#trSureGate").hide();			
			$("#trSureGate1").attr("style", "visibility: hidden;");			
			$("#trSureGate2").attr("style", "visibility: hidden;");			
        }
        else if ($(nmi).val() == 'gge4' && $(nmi).is(':checked'))
        {
            $("#gatewaynamelabel").html("GGe4 Gateway ID/Exact ID");
            $("#gatewaykeylabel").html("GGe4 Password");
			$("#trSureGate1").attr("style", "visibility: hidden;");			
			$("#trSureGate2").attr("style", "visibility: hidden;");			
        }
		else if ($(nmi).val() == 'suregate' && $(nmi).is(':checked'))
        {
            $("#gatewaynamelabel").html("Suregate user name")
            $("#gatewaykeylabel").html("Suregate password")
			$("#trSureGate1").attr("style", "visibility: visible;");			
			$("#trSureGate2").attr("style", "visibility: visible;");			
        }
        else
        {
            $("#gatewaynamelabel").html("Payment gateway login ID")
            $("#gatewaykeylabel").html("Payment gateway transaction key")
			$("#trSureGate1").attr("style", "visibility: hidden;");			
			$("#trSureGate2").attr("style", "visibility: hidden;");			
        }
    }

    $(function()
    {
        $("#showhidepassword").click(function()
        {
            var originalinput = $("#refund_password");
            var newInput = originalinput.clone();
            if ($(originalinput).attr('type') == 'text')
            {
                $(this).html('Show password');
                newInput.attr("type", "password");
            }
            else
            {
                $(this).html('Hide password');
                newInput.attr("type", "text");
            }
            newInput.insertBefore(originalinput);
            originalinput.remove();
        });
    });
</script>