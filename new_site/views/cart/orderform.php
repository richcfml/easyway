<?php
          // Add your MD5 Setting.
if($cart->isempty()) 
{
	//@mysql_close($mysql_conn);
	redirect($SiteUrl .$objRestaurant->url ."/?item=menu" );
	exit;
	//header("location: ". $SiteUrl .$objRestaurant->url ."/" );exit;	
}

if(!isset($_SERVER['HTTPS'])) 
{
	//@mysql_close($mysql_conn);
	//redirect($SiteUrl .$objRestaurant->url ."/?item=menu" );
//exit;
	// header("location: ". $SiteUrl .$objRestaurant->url ."/" );exit;
}

$mZipPostal = "Zip Code";
$mStateProvince = "State";
	
if ($objRestaurant->region == "2") //Canada
{
	$mZipPostal = "Postal Code";
	$mStateProvince = "Province";
}

$cart_total=$cart->grand_total();
if(isset($_POST['btnconfirmorder1']))
{
	$auth_defined = false;
    $txn_id = 0;
	$success=0;
	$CarkTokenOrderTbl = "";
	if( $objRestaurant->payment_gateway=="authoriseDotNet")  
	{
		$objRestaurant->payment_gateway="AuthorizeNet";
	}
        if(!is_numeric($loggedinuser->id) || $loggedinuser->id==0)
	{
                $is_guest = 1;
		$loggedinuser->createNewUser($is_guest);
		Log::write("Loginuser id is z or not numeric","LoggedinUser Data:".print_r($loggedinuser,true),'order', 0 , 'user');
		
	}
		 
 	require_once 'classes/gateways/'.$objRestaurant->payment_gateway.'.php';
	if($success==1) 
	{
		$secure_data = $_POST['x_card_num'];
		if($objRestaurant->tokenization==1)
		{
			$type=  substr($secure_data, 0,1);
		}
                // Added by Saad 22-Sept-2014 -- if user do not wish to save card info.. i.e tokenization checkbox not selected
                $creditCardType = 0;
                if(!isset($gateway_token))
                {
                    $creditCardType = substr($secure_data, 0,1);
                }
		//Modified01082013
		$cc=  substr($secure_data, -4, 4);
			 		
		$_POST['x_card_num']='';
		include "submit_order.php";
	}
}
else
{
	$serving_date=$_POST['serving_date'];
	$serving_time=$_POST['serving_time'];
	$payment_method=$_POST['payment_method'];
        $special_notes=$_POST['special_notes'];
	if(isset($_POST['cart_delivery_charges']))
	{
		$objRestaurant->delivery_charges=$_POST['cart_delivery_charges'];
		$cart->rest_delivery_charges=$objRestaurant->delivery_charges;
		$cart_total=$cart->grand_total();
		$cart->save();
	}
	if(!is_numeric($loggedinuser->id))
	{
		$loggedinuser->cust_your_name=$_POST['customer_name'];
		$loggedinuser->LastName=$_POST['customer_last_name'];
		$loggedinuser->street1=$_POST['customer_address'];
		$loggedinuser->street2='';
		$loggedinuser->cust_ord_city=$_POST['customer_city'];
		$loggedinuser->cust_ord_state=$_POST['customer_state'];
		$loggedinuser->cust_ord_zip=$_POST['customer_zip'];
		$loggedinuser->cust_phone1=$_POST['customer_phone'];
		$loggedinuser->cust_email=$_POST['customer_email'];
		$loggedinuser->delivery_address_choice=1;
		$loggedinuser->saveToSession();
	}
			
	$x_first_name=$loggedinuser->cust_your_name;
	$x_last_name=$loggedinuser->LastName;
	$x_address=trim($loggedinuser->street1 ." ". $loggedinuser->street2);
	$x_city=$loggedinuser->cust_ord_city;
	$x_state=$loggedinuser->cust_ord_state;
	$x_zip=$loggedinuser->cust_ord_zip;
	$x_phone=$loggedinuser->cust_phone1;
	$x_email=$loggedinuser->cust_email;
}
	
?>
<style type="text/css">
h3 
{
	font-size:14px;
	margin:10px 0 0 0;
	border-bottom:1px solid #CCC;
}

h4 
{
	border-bottom:1px solid #CCC;
}

.normal 
{
	margin: 0 0 0 20px;
	font-size: 12px !important;
	font-weight: normal!important;
	float:none;
}

#body_left_col 
{
	font-size: 12px !important;
	font-weight: normal!important;
}

.unit 
{
	margin:10px 0 10px 10px;
}

.unit .col_heading 
{
	width:88px;
}

span.alert-error
{
	margin-left:10px; 
}
</style>

<div id="body_left_col" style="width:980px; border: #e4e4e4 1px solid;">
	<form action="" method="post" id="payment_form" name="payment_form"  >
    	<input type="hidden" name="serving_date" value="<?=$serving_date ?>">
	    <input type="hidden" name="serving_time" value="<?=$serving_time ?>">
	    <input type="hidden" name="payment_method" value="<?=$payment_method ?>">
            <input type="hidden" name="special_notes" value="<?=$special_notes?>">
    	<input type="hidden" name="cart_delivery_charges" value="<?=$cart->rest_delivery_charges ?>">
    	<div class="heading">Order confirmation</div>
		<h3>
			Payment Information <span  style="float:right;margin-right:10px;text-decoration:underline">Total : $<?= number_format($cart_total,2) ?></span>
		</h3>
 
		<div class="unit">
		  <div class="left col_heading">&nbsp;</div>
		  <div  class="left normal" > <img width="43" height="26" alt="Visa" title="Visa" src="../images/V.gif"> <img width="41" height="26" alt="MasterCard" title="MasterCard" src="../images/MC.gif"> <img width="40" height="26" alt="American Express" title="American Express" src="../images/Amex.gif"> <img width="40" height="26" alt="Discover" title="Discover" src="../images/Disc.gif"></div>
		  <div class="clear"></div>
		</div>
		
		<?php 
		$newcard='';
		if(($objRestaurant->tokenization==1)  && (count($loggedinuser->arrTokens)))
		{
		?>
			<h4>Choose card on file</h4>
			<div class="unit">
				<div class="left col_heading" style="width: 130px;">Choose card on file</div>
				<div  class="left normal">
                <select id="card_token" name="card_token">
				<?php 
					foreach($loggedinuser->arrTokens as $token) 
					{
						$card_type='';
						$card_num='';
						$card_ed='';
						if($token->data_type==AMEX)
						{
							$card_type="American Express";
						}
						else if($token->data_type==VISA)
						{
							$card_type="VISA";
						}
						else  if($token->data_type==MASTER)
						{
							$card_type="MasterCard";
						}
						else  if($token->data_type==DISCOVER)
						{
							$card_type="Discover";
						}
				 ?>
						<option value="<?=$token->data_2?>" exp="<?=$token->card_expiry?>"><?= $card_type . " ending in ".$token->data_1 ?></option>
				<?php 
					} 
					$newcard=' hidden'; 
					$card_num='0000000000000000';
					$card_ed='0000' 
				?>
					<option value="0">New Card</option>
				   </select>
                   <a href="#">manage payment methods</a> 
                   <input type="hidden" value="<?=$card_type?>"  id="card_type" name="card_type"/>
                   <input type="hidden" id="x_exp_date1" name="x_exp_date1" />
				</div>
			</div>
            
			<!--<div class="unit">
				<div id="dvExp" name="dvExp">
					<?php 
					if ($objRestaurant->payment_gateway=="gge4")
					{
					?>
						<div class="left col_heading" style="width: 130px;">
							Expiration Date:
						</div>
						<div  class="left normal" >
							<input type="text"  size="4"  maxlength="4"   tabindex="3" id="x_exp_date1" name="x_exp_date1" />
							<span class="red">* (mmyy)</span> 
						</div>
					<?php
					}
					?>
				</div>
			</div>-->
		<?php 
		} 
		?>
		<div class="newcard<?=$newcard?>">
			<div class="unit">
				<div class="left col_heading" style="width: 130px;">Card Number:</div>
				<div  class="left normal" >
					<input type="text"  size="30"  maxlength="16"   tabindex="2" id="x_card_num" name="x_card_num"   value="<?=$card_num ?>"  />
					<span class="red">* (enter number without spaces or dashes)</span> 
				</div>
				<div class="clear"></div>
			</div>
			<div class="unit">
				<div class="left col_heading" style="width: 130px;">Expiration Date:</div>
				<div  class="left normal" >
					<input type="text"  size="4"  maxlength="4"   tabindex="3" id="x_exp_date" name="x_exp_date"   value="<?=$card_ed ?>" />
					<span class="red">* (mmyy)</span> 
				</div>
			<div class="clear"></div>
		</div>
		<?php 
		//if((is_numeric($loggedinuser->id)) && ($objRestaurant->tokenization==1))
                // Changed by Saad 22-Sept-2014 --> Allow guest user to save credit card for first time during registration.
                if($objRestaurant->tokenization==1)
		{
		?>
			<div class="unit">
				<div  class="left" >
					<u> Save this payment method for future orders</u>
				</div>
				<div  class="left normal" >
					<input type="checkbox" name="tokenization" id="tokenization" value="1" checked/> 
				</div>
				<div class="clear"></div>
			</div>
		<?php 
		} 
		?>
	</div><!-- NEW CARD -->
                     <div  class="left" style="margin-left: 10px;">
                        <u> Make this my default payment method</u>
                    </div>
                    <div  class="left normal" >
                        <input type="checkbox" name="default_card" id="default_card" value="1" checked/>
                    </div>
	<h4>Billing Information</h4>
		<div class="unit">
			<div class="left col_heading">First Name:</div>
			<div class="left normal">
				<input type="text"  size="30"  maxlength="150" title="Name" tabindex="4" id="x_first_name" name="x_first_name" value="<?= $x_first_name ?>"  />
			</div>
			<div class="clear"></div>
		</div>
		<div class="unit">
			<div class="left col_heading">Last Name:</div>
			<div class="left normal">
				<input type="text"  size="30"  maxlength="150" title="Name" tabindex="5" id="x_last_name" name="x_last_name" value="<?= $x_last_name ?>"  />
			</div>
			<div class="clear"></div>
		</div>
		<div class="unit">
			<div class="left col_heading">Address:</div>
			<div class="left normal">
				<input type="text"  size="30"  maxlength="150" title="Address" tabindex="6" id="x_address" name="x_address"  value="<?= $x_address ?>"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="unit">
			<div class="left col_heading">City:</div>
			<div class="left normal">
				<input type="text"  size="30"  maxlength="150" title="City" tabindex="7" id="x_city" name="x_city"  value="<?= $x_city ?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="unit">
			<div class="left col_heading"><?=$mStateProvince?>:</div>
			<div class="left normal">
				<input type="text"  size="30"  maxlength="150" title="State" tabindex="8" id="x_state" name="x_state"  value="<?= $x_state ?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="unit">
			<div class="left col_heading"><?=$mZipPostal?>:</div>
			<div class="left normal">
				<input type="text"  size="30"  maxlength="150" title="Zip" tabindex="9" id="x_zip" name="x_zip"  value="<?= $x_zip ?>"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="unit">
			<div class="left col_heading">Email:</div>
			<div class="left normal">
				<input type="text"  size="30"  maxlength="150" title="Email Address" tabindex="10" id="x_email" name="x_email"  value="<?= $x_email ?>"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="unit">
			<div class="left col_heading">Phone Number:</div>
			<div class="left normal">
			<script src="<?=$js_root?>mask.js" type="text/javascript"></script>
			<script type="text/javascript" language="javascript">
				$(document).ready(function() 
				{
					var region = <?php echo $objRestaurant->region ?>;
					if (region==0) //UK
					{
						$('#x_phone').unmask();
						$('#x_phone').mask('(9999) 999-9999');
					}
					else //US, Canada
					{
						$('#x_phone').unmask();
						$('#x_phone').mask('(999) 999-9999');
					}
				});
			</script>
				<input type="text"  size="30"  maxlength="150" title="Phone Number" tabindex="11" id="x_phone" name="x_phone"  value="<?= $x_phone ?>"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="unit">
			<div class="left col_heading">&nbsp;</div>
			<div class="left normal">
				<input type="hidden" id="btnsubmit" name="btnconfirmorder1" value=" Submit "  class="button blue">
				<input type="submit" id="submitbutton" name="btnconfirmorder" value=" Submit "  class="button blue">
				&nbsp;<span style="color: red; display: none;" id="spnStop">Your order is being processed, do not refresh or go back.</span>
				<br/>
			</div>
			<div class="clear"></div>
		</div>
	</form>
</div>
<script type="text/javascript">
$(function()
{
	$("#x_exp_date1").val($('option:selected', $("#card_token")).attr('exp'));
	
	$("#card_token").change(function()
	{
		if($(this).val()=="0")
		{
			$("#x_card_num").val('');
			$("#x_exp_date").val('');
			$(".newcard").show('slow');	
			$("#dvExp").hide('slow');	
		}
		else
		{
			$("#x_exp_date1").val($('option:selected', this).attr('exp'));
			
			$("#dvExp").show('slow');	
	    	$(".newcard").hide('slow',function()
			{
				$("#x_card_num").val('0000000000000000');
				$("#x_exp_date").val('0000');
			});			
   		}
	});
	
	$("#payment_form").validate
	({
		rules: 
		{
			x_card_num: {required: true ,minlength:15 },
			x_exp_date: {required: true,minlength:4   },
			x_card_code: {required: true   },
			x_first_name: {required: true  },
			x_last_name: {required: true  },
					 
			x_address: {required: true,minlength: 3},
			x_city: {required: true,minlength: 3},
			x_state: {required: true,minlength: 2},
			x_zip: {required: true,minlength: 3},
		},
		messages: 
		{
			x_card_num: 
			{
				required: "please enter your credit card number",
				minlength: "please enter a valid credit card number"
			},
			x_exp_date: 
			{
				required: "please enter your credit card expiry date",
				minlength: "please enter a valid credit card expiry date"
			},
			x_card_code: 
			{
				required: "please enter your credit card security code",
				minlength: "please enter a valid  credit card security code"
			},
						
			x_first_name: 
			{
				required: "please enter your first name",
			},
			x_last_name: 
			{
				required: "please enter your last name",
			},
			x_address: 
			{
				required: "please enter your street address",
				minlength: "please enter a valid address"
			},
			x_city: 
			{
				required: "please enter your city",
				minlength: "please enter a valid city"
			},
			x_state: 
			{
				required: "please enter your <?=$mStateProvince?>",
				minlength: "please enter a valid <?=$mStateProvince?>"            
			},
			x_zip: 
			{
				required: "please enter your <?=$mZipPostal?>",
				minlength: "please enter a valid <?=$mZipPostal?>"
			} 				 
		},  
		errorElement: "span",
		errorClass: "alert-error",
		submitHandler: function (form) 
		{
			$("#submitbutton").attr('disabled', 'disabled'); 
			$("#spnStop").show();
			form.submit();		 
		}
	});
		
	$("#payment_form").submit(function() 
	{
			   //$("#submitbutton").hide();
	});
});
</script> 
