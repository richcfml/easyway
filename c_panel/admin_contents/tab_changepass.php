<?php require_once("../includes/config.php");?>
<script src="js/fancybox.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/fancy.css">
<style type="text/css">
    .fancybox-inner{
        height:472px !important;
    }
</style>
<script language="javascript" type="text/javascript">

// Roshan's Ajax dropdown code with php
// This notice must stay intact for legal use
// Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
// If you have any problem contact me at http://roshanbh.com.np
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{
			try{
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}

		return xmlhttp;
    }

        function getCardInfo( ownerId  ) {

		var strURL="admin_contents/resturants/tab_find_cardInfo.php?ownerId="+ownerId;
		var req = getXMLHTTP();

		if (req) {

			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
                                                console.log(document.getElementById('card_div'))
						$('.card_div').html(req.responseText);
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
		//get_licenses_of_reseller( resellerId );
	}


</script>
<?php 
	$old_username_pass_qry = dbAbstract::Execute("select * from users WHERE username = '".$_SESSION['admin_session_user_name']."'",1);
	$old_username_pass_qry_rs = dbAbstract::returnArray($old_username_pass_qry,1);
	$oldpass_db = $old_username_pass_qry_rs['epassword'];
        $oldpass_salt = $old_username_pass_qry_rs['salt'];
        
	$name = $old_username_pass_qry_rs['username'];
	$errMessage="";

        $counter = 0;
	if (isset($_REQUEST['submit'])) 
        {
            $oldpass = $_REQUEST['oldpass'];
            $mOldPassword = hash('sha256', $oldpass.$oldpass_salt);
            $pass = $_REQUEST['pass']; 
            $confirm1 = $_REQUEST['confirm1']; 
        if ($oldpass_db!=$mOldPassword) 
        {
            $errMessage="Your old password is not correct.";
	} 
        else if ($pass!=$confirm1) 
        {
            $errMessage="New and Confirm Password should match ecah other.";
	}
	
        if($errMessage=="")
        {	
            $ePassword = hash('sha256', $pass.$oldpass_salt);
	dbAbstract::Update("UPDATE users set password = '".$pass."', epassword = '".$ePassword."' where username ='".$name."'",1);
	?>
    <script language="javascript">
		window.location="./?mod=resturant";
	</script>
		<?php 
        }
	}
        if(isset($_REQUEST['saveCard']))
        {
           extract($_POST);
           if($payment_profile_id < 0) {
			$errMessage1 = "Please select Card";
		}else{
            Log::write('updatePaymentProfile parameter - tab_changepass.php', 'Posted Array:payment profile id:'.$payment_profile_id, 'MyAccount', 1);
            $payment_data = $chargify->updatePaymentProfile($first_name,$last_name,$billingAddress1,$billingAddress2,$city,$state ,$zip ,$country,$payment_profile_id,$exp_month,$exp_year );
            Log::write('updatePaymentProfile - tab_changepass.php', 'Response Array:'.print_r($payment_data,true), 'MyAccount', 1);

            $sql=dbAbstract::Update("UPDATE chargify_payment_method set billing_address = '".dbAbstract::returnRealEscapedString(stripslashes($payment_data->payment_profile->billing_address))."',billing_address_2 = '".dbAbstract::returnRealEscapedString(stripslashes($payment_data->payment_profile->billing_address_2))."'
                ,billing_city = '".dbAbstract::returnRealEscapedString(stripslashes($payment_data->payment_profile->billing_city))."',billing_country = '".dbAbstract::returnRealEscapedString(stripslashes($payment_data->payment_profile->billing_country))."',billing_state ='".dbAbstract::returnRealEscapedString(stripslashes($payment_data->payment_profile->billing_state))."'
                ,billing_zip = '".dbAbstract::returnRealEscapedString(stripslashes($payment_data->payment_profile->billing_zip))."',first_name = '".dbAbstract::returnRealEscapedString(stripslashes($payment_data->payment_profile->first_name))."',last_name = '".dbAbstract::returnRealEscapedString(stripslashes($payment_data->payment_profile->last_name))."'
                ,expiration_month = '".dbAbstract::returnRealEscapedString(stripslashes($payment_data->payment_profile->expiration_month))."',expiration_year = '".dbAbstract::returnRealEscapedString(stripslashes($payment_data->payment_profile->expiration_year))."'
                 where Payment_profile_id = ".$payment_profile_id."",1);
            Log::write('update chargify_payment table - tab_changepass.php', 'Query:'.$sql, 'MyAccount', 1);
            
        }
        }
        if(isset($_POST['change']))
        {   extract($_POST);
            $chargify_subscription_id = dbAbstract::ExecuteObject("Select chargify_subscription_id from resturants where id = (select  resturant_id from licenses where license_key ='$licenseid' limit 0,1)",1);
            $Objsubcription = $chargify->getSubscriptionByCustomerId($chargify_subscription_id->chargify_subscription_id);
            $oldProduct = $Objsubcription->product;
            Log::write('My Account - tab_changepass.php', 'Calling Create Migration:subscriptionID:'.$chargify_subscription_id->chargify_subscription_id.' Product ID:'.$product_details, 'MyAccount', 1);
            $chargify->createMigration($chargify_subscription_id->chargify_subscription_id,$product_details);
            
            $oldProductSQl = dbAbstract::ExecuteObject("select * from chargify_products where product_id='".$oldProduct->id."' and user_id = (select reseller_id from licenses where license_key ='$licenseid' limit 0,1)",1);
            $newProductSQl = dbAbstract::ExecuteObject("select * from chargify_products where product_id='".$product_details."' and user_id = (select reseller_id from licenses where license_key ='$licenseid' limit 0,1)",1);
            $reseller_chargify_id = dbAbstract::ExecuteObject("Select chargify_subcription_id from users where id = (select reseller_id from licenses where license_key ='$licenseid' limit 0,1)",1);
            
            if($oldProductSQl->premium_account!=$newProductSQl->premium_account)
            {
                
                if($newProductSQl->premium_account ==1)
                {
                    $quantityPremium = $chargify->getallocationQuantity($reseller_chargify_id->chargify_subcription_id,$newProductSQl->premium_account);
                    $quantityStandard = $chargify->getallocationQuantity($reseller_chargify_id->chargify_subcription_id,$oldProductSQl->premium_account);
                    Log::write('My Account - tab_changepass.php', 'Calling multipleAllocations:subscriptionID:'.$reseller_chargify_id->chargify_subcription_id.' qtyPremium:'.$quantityPremium.' qtyStandard:'.$quantityStandard.' premium_account:1', 'MyAccount', 1);
                    $chargify->multipleAllocation($reseller_chargify_id->chargify_subcription_id,$quantityPremium,$quantityStandard,1);
                }
                else
                {
                    $quantityPremium = $chargify->getallocationQuantity($reseller_chargify_id->chargify_subcription_id,$oldProductSQl->premium_account);
                    $quantityStandard = $chargify->getallocationQuantity($reseller_chargify_id->chargify_subcription_id,$newProductSQl->premium_account);
                    Log::write('My Account - tab_changepass.php', 'Calling multipleAllocations:subscriptionID:'.$reseller_chargify_id->chargify_subcription_id.' qtyPremium:'.$quantityPremium.' qtyStandard:'.$quantityStandard.' premium_account:0', 'MyAccount', 1);
                    $chargify->multipleAllocation($reseller_chargify_id->chargify_subcription_id,$quantityPremium,$quantityStandard,0);
                }
            }
            
        }
        if( isset($_POST['activate'] ))
        {
            extract($_POST);
           
           
            

            $license_quantity = dbAbstract::ExecuteObject("SELECT count(*) as total_license FROM `licenses` WHERE reseller_id = (select reseller_id from licenses where license_key ='$license_id' limit 0,1) and status != 'suspended'",1);
            $reseller_chargify_id = dbAbstract::ExecuteObject("Select chargify_subcription_id from users where id = (select reseller_id from licenses where license_key ='$license_id' limit 0,1)",1);

            $resData = dbAbstract::ExecuteObject("Select id,name,region,rest_address,rest_city,rest_state,phone,rest_zip,premium_account from resturants where id = (select  resturant_id from licenses where license_key ='$license_id' limit 0,1) and premium_account = 1",1);

            $chargify_subscription_id = dbAbstract::ExecuteObject("Select chargify_subscription_id,premium_account,name from resturants where id = (select resturant_id from licenses where license_key ='$license_id' limit 0,1)",1);
            
            if(!empty($chargify_subscription_id->chargify_subscription_id))
            {
                
                    $chargify_subscription = $chargify->reactivateSubcription($chargify_subscription_id->chargify_subscription_id,$subcription_method,$credit_card_number,$card_no,$exp_month,$exp_year);
                    Log::write('My Account - tab_changepass.php', 'Calling reactivateSubcription:subscriptionID:'.$chargify_subscription_id->chargify_subscription_id , 'MyAccount', 1);
                    if(!empty($chargify_subscription->subscription))
                    {
                    $uptLicenses = "UPDATE licenses SET  status='activated' WHERE license_key ='$license_id'";
                    dbAbstract::Update($uptLicenses,1);
                    Log::write('Edit Restaurant - tab_changepass.php', 'Updated Licenses Clients:'.$uptLicenses, 'MyAccount', 1);

                    dbAbstract::Update("UPDATE resturants SET  status='1' WHERE id = (select  resturant_id from licenses where license_key ='$license_id'  limit 0,1)",1);
		    dbAbstract::Update("UPDATE analytics SET  status='1' WHERE resturant_id = (select  resturant_id from licenses where license_key ='$license_id' limit 0,1)",1);
                    if($subcription_method =='invoice')
                    {
                    $objMail = new testmail();
                                    $mMessage = '<table style="font:Arial; font-family: Arial; font-size: 14px; width: 80%; border: 0" border="0" cellpadding="0" cellspacing="0">
                                                                    <tr>
                                                                            <td valign="top" style="width: 3%;">
                                                                            </td>
                                                                            <td valign="top" style="width: 94%;">
                                                                                    <table style="font:Arial; font-family: Arial; font-size: 14px; width: 100%; border: 0" border="0" cellpadding="0" cellspacing="0">
                                                                                            <tr style="height: 15px;">
                                                                                                    <td valign="top">
                                                                                                    </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                    <td valign="top">
                                                                                                            <span style="font-size: 14px;">Restaurant subscripton has been activated.</span>
                                                                                                    </td>


											</tr>
											<tr style="height: 10px;">
												<td valign="top">
													<span style="font-size: 14px;">Subscription ID: '.$chargify_subscription_id->chargify_subscription_id.'</span>
												</td>
											</tr>
											<tr style="height: 15px;">
												<td valign="top">
													<span style="font-size: 14px;">Restaurant Name: '.$chargify_subscription_id->name.'</span>
												</td>
											</tr>
											<tr style="height: 30px;">
												<td valign="top">
												</td>
											</tr>
											<tr style="height: 15px;">
												<td valign="top">
													Thank You,
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>';


					//$objMail->sendTo($mMessage, "Signup wizard Started", "menus@easywayordering.com", true);
					//$objMail->sendTo($mMessage, "Subscription is activated", "ashernedian02@gmail.com", true);
                                        $objMail->sendTo($mMessage, "Subscription has been activated", "ops@easywayordering.com", true);
                                        
                }
                $quantity = $chargify->getallocationQuantity($reseller_chargify_id->chargify_subcription_id,$chargify_subscription_id->premium_account);
                $quantity = $quantity+1;
                Log::write('My Account - tab_changepass.php', 'Calling Allocation Quantity:'.$reseller_chargify_id->chargify_subcription_id.'Quantity;'.$quantity.'Account Type:'.$chargify_subscription_id->premium_account.'activate:activate', 'MyAccount', 1);
                $chargify->allocationQuantity($reseller_chargify_id->chargify_subcription_id,$quantity,$chargify_subscription_id->premium_account,'activate');
                $chargify_customer = $chargify_subscription->subscription->customer;

                $credit_card_info = $chargify_subscription->subscription;
                $check_card_data_Qry = dbAbstract::ExecuteObject("Select * from chargify_payment_method where chargify_customer_id = '".$chargify_customer->id."' and card_number='".$credit_card_info->credit_card->masked_card_number."'",1);
                
                if(empty($check_card_data_Qry))
                {
                    $uptchargify_payment_method = "INSERT INTO chargify_payment_method
                            SET user_id= '".addslashes($_SESSION['owner_id'])."'
                                    ,chargify_customer_id= '".addslashes($chargify_customer->id)."'
                                    ,Payment_profile_id='".addslashes($credit_card_info->credit_card->id)."'
                                    ,card_number='".$credit_card_info->credit_card->masked_card_number."'";
                    dbAbstract::Insert($uptchargify_payment_method,1);
                    Log::write('My Account - tab_changepass.php', 'Update Chargify Payment menthod:'.$uptchargify_payment_method , 'MyAccount', 1);
                }

                    if(!empty($resData))
                    {   
                        $getOwnerEmail = dbAbstract::ExecuteObject("Select email from users where id = '".$_SESSION['owner_id']."'",1);
                        $cntry = $resData->region;
                        if($cntry=='0'){$cntry="GB";}else if($cntry=='1'){$cntry="US";} else if($cntry=='2'){$cntry="CA";}else{$cntry="US";}
                        $getSrid= $chargify->createVendestaPremium($resData->name,$cntry,$resData->rest_address,$resData->rest_city,$resData->rest_state,$resData->rest_zip,"false",$resData->phone,$getOwnerEmail->email);
                        dbAbstract::Update("UPDATE resturants SET srid='".$getSrid."' where id = $resData->id",1);

                    }

                }
                else
                {
			
                    $errorMsg = $chargify_subscription->errors;

                    $errMessage =  $errorMsg[0];
                }
                
            }
        }

        if(isset($_REQUEST)){
            
                if(isset($_REQUEST['license_id'])){
			$license_id = $_REQUEST['license_id'];
                        }
		if ($_REQUEST['action'] == 'del') {
			dbAbstract::Delete("DELETE FROM licenses WHERE license_key ='$license_id' and reseller_id=$reseller_id",1);

		} else if ($_REQUEST['action'] == 'suspend'){
                        
			dbAbstract::Update("UPDATE licenses SET  status='suspended' WHERE license_key ='$license_id'",1);
			dbAbstract::Update("UPDATE resturants SET  status='2' WHERE id = (select  resturant_id from licenses where license_key ='$license_id'  limit 0,1)",1);
			dbAbstract::Update("UPDATE analytics SET  status='2' WHERE resturant_id = (select resturant_id from licenses where license_key ='$license_id' limit 0,1)",1);
			
			
			$sridSql = dbAbstract::ExecuteObject("Select id,srid from resturants where id = (select  resturant_id from licenses where license_key ='$license_id' limit 0,1) and premium_account = 1 ",1);
                        
                        $chargify_subscription_id = dbAbstract::ExecuteObject("Select chargify_subscription_id,premium_account from resturants where id = (select  resturant_id from licenses where license_key ='$license_id' limit 0,1)",1);
                        $license_quantity = dbAbstract::ExecuteObject("SELECT count(*) as total_license FROM `licenses` WHERE reseller_id = (select reseller_id from licenses where license_key ='$license_id' limit 0,1) and status != 'suspended'",1);
                        $reseller_chargify_id = dbAbstract::ExecuteObject("Select chargify_subcription_id from users where id = (select reseller_id from licenses where license_key ='$license_id' limit 0,1)",1);
                        
                        $quantity = $chargify->getallocationQuantity($reseller_chargify_id->chargify_subcription_id,$chargify_subscription_id->premium_account);
                        $quantity = $quantity-1;
                        Log::write('My Account - tab_changepass.php', 'Calling Allocation Quantity:'.$reseller_chargify_id->chargify_subcription_id.'Quantity;'.$quantity.'Account Type:'.$chargify_subscription_id->premium_account.'suspend:suspend', 'MyAccount', 1);
                        $chargify->allocationQuantity($reseller_chargify_id->chargify_subcription_id,$quantity,$chargify_subscription_id->premium_account,'suspend');
                        Log::write('My Account - tab_changepass.php', 'cancelSubcriptionByRestowner Subscription ID:'.$chargify_subscription_id->chargify_subscription_id, 'MyAccount', 1);
                        $chargify->cancelSubcriptionByRestowner($chargify_subscription_id->chargify_subscription_id);

                        if(!empty($sridSql->srid))
                        {
                            Log::write('My Account - tab_changepass.php', 'cancelVendesta srid:'.$sridSql->srid, 'MyAccount', 1);
                            $chargify->cancelVendesta($sridSql->srid);
                            dbAbstract::Update("UPDATE resturants set srid = '' where id = ".$sridSql->id."",1);
                        }
                        ?>
                          <script type='text/javascript'>window.location='?mod=changepass'</script>
                        <?php
                        
		} else if ($_REQUEST['action'] == 'activate'){

			dbAbstract::Update("UPDATE licenses SET  status='activated' WHERE secure_key ='$license_id' and reseller_id=$reseller_id",1);
			dbAbstract::Update("UPDATE resturants SET  status='1' WHERE id = (select  resturant_id from licenses where secure_key ='$license_id' and reseller_id=$reseller_id limit 0,1)",1);
			dbAbstract::Update("UPDATE analytics SET  status='1' WHERE resturant_id = (select  resturant_id from licenses where secure_key ='$license_id' and reseller_id=$reseller_id limit 0,1)",1);
                        $subcription_method = 'automatic';
                        
                        if($card_no=='' && $credit_card_number =="-1")
                        {
                            $subcription_method = 'invoice';
                        }

                        $chargify_subscription_id = dbAbstract::ExecuteObject("Select chargify_subscription_id from resturants where id = (select  resturant_id from licenses where secure_key ='$license_id' and reseller_id=$reseller_id limit 0,1)",1);
                        if(!empty($chargify_subscription_id->chargify_subscription_id))
                        {
                            $chargify->reactivateSubcription($chargify_subscription_id->chargify_subscription_id,$subcription_method,$credit_card_number,$card_no,$exp_month,$exp_year);
                        }

		} else if ($_REQUEST['action'] == 'deactivate'){

			dbAbstract::Update("UPDATE licenses SET  status='0' WHERE license_key ='$license_id' and reseller_id=$reseller_id",1);

		}

	}

if($_SESSION['admin_type']!="admin")
{?>
     <div id="LoginContainer" style="width:700px"><br />
         <?php if ($errMessage != "" ) { ?><div class="msg_done" style="margin-bottom: 15px; color: red;"><?=$errMessage?></div>
<?php }?>
<table class="listig_table" width="100%" border="0" align="center" cellspacing="1">

    <tr>
    <tr bgcolor="#729338">
      <th width="113"><strong>License Key</strong></th>
      <th width="113"><strong>License status</strong></th>
      <th width="113"><strong>Resturant</strong></th>
      <th width="113"><strong>Activation Date</strong></th>
      <th width="150"><strong>Action</strong></th>

    </tr>
    <?  if($_SESSION['admin_type']=="store owner")
        {
            $licenseQry = dbAbstract::Execute("select * from licenses where id in(Select license_id from resturants where owner_id = ".$_SESSION['owner_id'].")",1);
        }
        else if($_SESSION['admin_type']=="reseller")
        {   
            $licenseQry = dbAbstract::Execute("select * from licenses where reseller_id  = ".$_SESSION['owner_id']."",1);
        }
    while($licenseRs	=	dbAbstract::returnObject($licenseQry,1)){ ?>
    

   <?  if( $counter++ % 2 == 0)
   			$bgcolor = '#F8F8F8';
	   else $bgcolor = '';
   ?>
    <tr bgcolor="<?=$bgcolor ?>" >

      <td><?=$licenseRs->license_key?></td>
      <td><?=$licenseRs->status?> </td>
      <td>
	  <?
      	if( $licenseRs->resturant_id ) {
			$resurant_sql_str = "SELECT name FROM resturants WHERE id=".$licenseRs->resturant_id;
			$resurant_qry = dbAbstract::Execute( $resurant_sql_str,1 );
			$resurant_rs  = dbAbstract::returnObject( $resurant_qry,1 ) ;
			$resturant_name = $resurant_rs->name;
		} else {
			$resturant_name = "";
		}

		echo $resturant_name;
	  ?>
      </td>
      <td>
	  	<?
		if ($licenseRs->activation_date == 0)
			$activationDate = "";
		else  $activationDate = strftime("%m-%d-%Y", $licenseRs->activation_date);
		echo $activationDate;
		?>
      </td>
      <td class="links_class">


<?if($_SESSION['admin_type'] == 'reseller')
          {?>
		  <?php if($licenseRs->status == 'activated') { ?>
              <a href="?mod=changepass&action=suspend&license_id=<?=$licenseRs->license_key ?>&rest_id=<?=$licenseRs->resturant_id?>" onClick="return confirm('Are you sure you want to change the status of this License')" style="text-decoration:underline;display: block;float: left;width: 50px;">Suspend</a>
          <?php } else if( $licenseRs->status == 'suspended' ) { ?>
           	<span class="openFancyDiv" style="text-decoration:underline;color:#0000ee;cursor:pointer;display: block;float: left;width: 50px;" license_id="<?=$licenseRs->license_key ?>">Activate</span>
		  <? }else if( $licenseRs->status == 'unused' ) { ?>
          &nbsp;&nbsp;<a href="?action=del<?= $_SESSION['admin_type'] == 'admin' ? "&reseller_id=".$reseller_id:""?>&license_id=<?=$licenseRs->license_key  ?>"  onclick="return confirm('Are you sure you want to Delete this License')" style="text-decoration:underline;">Delete</a>
        <? }}?>

          <?if($_SESSION['admin_type'] == 'store owner')
          {?>
<!--            <span class="change_plan" style="text-decoration:underline;color:#0000ee;cursor:pointer;margin-left: 21px;display: block;float: left;width: 85px;" license_id="<?=$licenseRs->license_key ?>">Change Plan</span>-->
            <a href="loadSubcription.php?license_id=<?=$licenseRs->license_key ?>&<?php echo time() ?>" style="text-decoration:underline;color:#0000ee;cursor:pointer;margin-left: 12px;" class="loadSubscripstion">View</a>

         <?}?>
      </td>

    </tr>
    <tr><td colspan="5">
    
   
    
            </td></tr>
    <? }?>
 <tr>
        <td colspan="5">
<form id="form2" name ="form2" method="post" action="">
        
            <input type="hidden" name="license_id" id ="license_id"/>
       <strong>Billing Information</strong>
    <table class="PaymentMethod"  style="width:695px" >
          <tr align="left" valign="top">
            <td>Subcription type</td>
            <td><input name="subcription_method" type="radio" value="automatic"  id="payment_collection_method" checked> automatic           &nbsp;&nbsp;
            <input name="subcription_method" type="radio" value="invoice"  id="payment_collection_method" >Invoice</td>
          </tr>

          <tbody class="card_new_existing">
          <tr align="left" valign="top">
               <td></td>
            <td><input name="credit_card" type="radio" value="0"  id="credit_card" checked>New Credit Card            &nbsp;&nbsp;
            <input name="credit_card" type="radio" value="1"  id="credit_card" >Choose Existing Card</td>
          </tr>
          <tbody class="payment_Method">
           <tr align="left" valign="top">
            <td><strong>Card no:</strong></td>
            <td>
		<input name="card_no" type="text" size="40"  id="card_no" /> </td>
           </tr>



          <tr align="left" valign="top">
            <td><strong>Exp Date:</strong></td>
            <td>
                 <select name="exp_month" id="exp_month" style="width:70px;" >
                    <option value="1">1 -Jan</option>
                    <option value="2">2 -Feb</option>
                    <option value="3">3 -Mar</option>
                    <option value="4">4 -Apr</option>
                    <option value="5">5 -May</option>
                    <option value="6">6 -Jun</option>
                    <option value="7">7 -Jul</option>
                    <option value="8">8 -Aug</option>
                    <option value="9">9 -Sep</option>
                    <option value="10">10 -Oct</option>
                    <option value="11">11 -Nov</option>
                    <option value="12">12 -Dec</option>
                 </select>

                <select name="exp_year" id="exp_year" style="width:70px;" >
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                </select>
            </td>
          </tr>
          </tbody>

           <tbody class="choose_existing">
            <tr align="left" valign="top">
                <td><strong>Credit card no:</strong></td>
                <td  class="card_div">
                    <select name="credit_card_number" id="credit_card_number" style="width:270px;" >
                    <option value="-1">Select Card</option>
                    </select>
                </td>
            </tr>

           </tbody>
           </tbody>
           <tr>
               <td colspan="5">
                     <input type="submit" name="activate" value="Activate" id="activate"/>
                </td>
            </tr>
            </table>
    
</form>

<form id="form3" name ="form2" method="post" action="">

    <input type="hidden" name="licenseid" id ="licenseid"/>
    <strong>Product Plan</strong>
    <table class="product_plan"  style="width:439px" >
          <tr align="left" valign="top">
                <td><strong>Choose Product:</strong></td>
                <td id="product_div">
                    <select name="product_details" id="product_details" style="width:270px;">
                    <option value="-1">Select Product</option>
                    <?php
                    $product_sqlStr =  "SELECT product_id FROM chargify_products WHERE user_id  = (Select reseller_id from reseller_client where client_id = ".$_SESSION['owner_id'].")";
                    $product_qry = dbAbstract::Execute( $product_sqlStr,1 );
                    while($result=dbAbstract::returnArray($product_qry,1)) {
                    $product = $chargify->getProductById($result['product_id']);
	 	    $price = number_format(round($product->price_in_cents/100,2),2);
		    $price = "$".$price;
                    ?>
                    <option value=<?=$result['product_id']?> title="<?=$product->description;?>"><?=$product->name.'     Price:  '.$price;?></option>
                    <? } ?>
                    </select>
                </td>
          </tr>

           <tr>
               <td colspan="5">
                     <input type="submit" name="change" value="Change Product" id="change"/>
                </td>
            </tr>
     </table>

</form>
        </td>
    </tr>
  

        </table>
        </div>
<?}?>
<form id="form1" name="form1" method="post" action="">


  <div id="LoginContainer" style="width:700px"><br />
<br />
<br />
<div id="TopNav">
  <table width="100%" border="0" cellspacing="5" cellpadding="0">
    <? if(@$errMessage!="") {?>
    <tr>
      <td align="right" valign="middle">&nbsp;</td>
      <td align="left" valign="middle" class="ErrorMsg" style="color: red;"><?php echo @$errMessage?></td>
    </tr>
    <? }?>
    <tr>
      <td width="40%" align="right" valign="middle"><strong>Old Password:</strong></td>
      <td align="left" valign="middle"><label>
        <input type="password" name="oldpass" id="oldpass" style="width:200px;" />
        </label></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><strong>New Password:</strong></td>
      <td align="left" valign="middle"><input type="password" name="pass" id="pass" style="width:200px;" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><strong>Confirm Password:</strong></td>
      <td align="left" valign="middle"><input type="password" name="confirm1" id="confirm1" style="width:200px;" /></td>
    </tr>
     <tr>
      <td align="right" valign="middle">&nbsp;</td>
      <td align="left" valign="middle"><label>
        <input type="submit" name="submit" id="submit" value="Change" />
      </label></td>
    </tr>
  </table>
</div></div>


    <div style="text-align: center;font-size: 20px;">Edit Billing Information</div>
<div id="LoginContainer" style="width:700px"><br />
<br />
<br />
<div id="TopNav">
  <table width="100%" border="0" cellspacing="5" cellpadding="0">
    <? if(@$errMessage1!="") {?>
    <tr>
      <td align="right" valign="middle">&nbsp;</td>
      <td align="left" valign="middle" class="ErrorMsg" style="color: red;"><? echo @$errMessage1?></td>
    </tr>
    <? }?>
    <tr>
      <td width="40%" align="right" valign="middle"><strong>Credit Card:</strong></td>
      <td align="left" valign="middle"><label>
        <select name="payment_profile_id" id="payment_profile_id" style="width:270px;" onchange="getCardData(this.value)">
            <?php $mSQL = dbAbstract::Execute("Select Payment_profile_id,card_number from chargify_payment_method where user_id = ".$_SESSION['owner_id']."",1);?>
                    <option value="-1">Select Card</option>
                    <? while($mResult = dbAbstract::returnObject($mSQL,1)){?>
                    <option value="<?=$mResult->Payment_profile_id?>"><?=$mResult->card_number?></option>
                    <?}?>
        </select>
        </label></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><strong>First Name:</strong></td>
      <td align="left" valign="middle"><input type="text" name="first_name" id="first_name" style="width:200px;" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><strong>Last Name:</strong></td>
      <td align="left" valign="middle"><input type="text" name="last_name" id="last_name" style="width:200px;" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><strong>Expiration Date:</strong></td>
      <td align="left" valign="middle">
                  <select name="exp_month" id="exp_month" style="width:70px;" >
                    <option value="1">1 -Jan</option>
                    <option value="2">2 -Feb</option>
                    <option value="3">3 -Mar</option>
                    <option value="4">4 -Apr</option>
                    <option value="5">5 -May</option>
                    <option value="6">6 -Jun</option>
                    <option value="7">7 -Jul</option>
                    <option value="8">8 -Aug</option>
                    <option value="9">9 -Sep</option>
                    <option value="10">10 -Oct</option>
                    <option value="11">11 -Nov</option>
                    <option value="12">12 -Dec</option>
                 </select>
                 <select name="exp_year" id="exp_year" style="width:70px;" >
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                </select>
      </td>
    </tr>
    <tr>
        
      <td align="right" valign="middle"><strong>Billing Address 1:</strong></td>
      <td align="left" valign="middle"><input type="text" name="billingAddress1" id="billingAddress1" style="width:200px;" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><strong>Billing Address 2</strong></td>
      <td align="left" valign="middle"><input type="text" name="billingAddress2" id="billingAddress2" style="width:200px;" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><strong>Billing Country</strong></td>
      <td align="left" valign="middle"><input type="text" name="country" id="country" style="width:200px;" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><strong>Billing State</strong></td>
      <td align="left" valign="middle"><input type="text" name="state" id="state" style="width:200px;" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle"><strong>Billing City</strong></td>
      <td align="left" valign="middle"><input type="text" name="city" id="city" style="width:200px;" /></td>
    </tr>

    <tr>
      <td align="right" valign="middle"><strong>Billing Zip Code</strong></td>
      <td align="left" valign="middle"><input type="text" name="zip" id="zip" style="width:200px;" /></td>
    </tr>
     <tr>
      <td align="right" valign="middle">&nbsp;</td>
      <td align="left" valign="middle"><label>
        <input type="submit" name="saveCard" id="saveCard" value="Save Changes" />
      </label></td>
    </tr>
  </table>
</div></div>
</form>
<script type="text/javascript">
    function getCardData(payment_id)
    {   console.log(1)
        $.ajax
        ({
            type:"post",
            url:"admin_contents/ajax_card_data.php?payment_id="+payment_id,
            success: function(data) {
                if(data!='')
                {
                    var parseJson = jQuery.parseJSON(data);
                    $("#first_name").val(parseJson.first_name);
                    $("#last_name").val(parseJson.last_name);
                    $("#billingAddress1").val(parseJson.billing_address);
                    $("#billingAddress2").val(parseJson.billing_address_2);
                    $("#city").val(parseJson.billing_city);
                    $("#state").val(parseJson.billing_state	);
                    $("#country").val(parseJson.billing_country);
                    $("#zip").val(parseJson.billing_zip);
                    $("#exp_month option[value='"+parseJson.expiration_month+"']").attr("Selected", true);
                    $("#exp_year option[value='"+parseJson.expiration_year+"']").attr("Selected", true);
                    
                }
            }
        });
    }
</script>
<script type="text/javascript">
   $( document ).ready(function() {
      $("#form2").hide();
      $("#form3").hide();
      $('input:radio[name="credit_card"]').filter('[value="0"]').attr('checked', true);
      $(".openFancyDiv").click(function()
      {
          var element = $(this);
          var reseller_id = GetURLParameter('reseller_id');
          getCardInfo(reseller_id);

          $('#license_id').val(element.attr('license_id'));
          var nextrow = element.closest('tr').next();
          if($('#form2', nextrow).length == 0){
              var row = $('<tr>');
              var col = $('<td colspan="5">');
              col.append($('#form2'));
              row.append(col);
              row.insertAfter(element.closest('tr'));
              $("#form2").show();
              element.removeClass('openFancyDiv');
              element.addClass('closeFancyDiv');
              setTimeout(500);
          }else{
              nextrow.show();
          }

      });

      $(".change_plan").click(function()
      {
          var element = $(this);
          var staus = element.parents('.links_class').find('.openFancyDiv').text();
          if(staus != 'Activate')
          {
              var nextrow = element.closest('tr').next();
              if($('#form3', nextrow).length == 0)
              {
                  $('#licenseid').val(element.attr('license_id'));
                  var row = $('<tr>');
                  var col = $('<td colspan="5">');
                  col.append($('#form3'));
                  row.append(col);
                  row.insertAfter(element.closest('tr'));
                  $("#form3").show();
                  element.removeClass('openFancyDiv');
                  element.addClass('closeFancyDiv');
                  setTimeout(500);
              }
          }
          else
          {
              alert('Please activate subscription first');
          }

      });
  });

  $('.choose_existing').hide();

    $('input[type=radio][name=credit_card]').change(function()
    {
        var rdbval = $('input[name=credit_card]:checked').val();
        if(rdbval==1)
        {
            $('.payment_Method').hide();
            $('.card_new_existing').show();
            $('.choose_existing').show();
        }
        else
        {
            $('.payment_Method').show();
            $('.card_new_existing').show();
            $('.choose_existing').hide();
        }
    });

    $('input[type=radio][name=subcription_method]').change(function()
    {
        var rdbvalcard = $('input[name=credit_card]:checked').val();
        var rdbval = $('input[name=subcription_method]:checked').val();
        if(rdbval=="automatic")
        {
            if(rdbvalcard==1)
            {
                $('.payment_Method').hide();
                $('.card_new_existing').show();
                $('.choose_existing').show();
            }
            else
            {
                $('.choose_existing')
                $('.payment_Method').show();
                $('.card_new_existing').show();
            }
        }
        else
        {
            $('.payment_Method').hide();
            $('.choose_existing').hide();
            $('.card_new_existing').hide();

        }
    });

       $('.loadSubscripstion').fancybox({type:'iframe'})


    function GetURLParameter(sParam){
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }
    }
</script>

