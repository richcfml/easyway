<?php
        $errMessage='';
	$mZipPostal = "Zip Code";
	$mStateProvince = "State";

	if ($Objrestaurant->region == "2") //Canada
	{
            $mZipPostal = "Postal Code";
            $mStateProvince = "Province";
	}
	$user_id	=	$_REQUEST['userid'];
	$user_qry	=       dbAbstract::Execute("select * from customer_registration where id = $user_id", 1);
	$user_qryRs	=	dbAbstract::returnObject($user_qry, 1);
				   
        if (isset($_REQUEST['submit']))
        {
            if (! empty ( $_POST )) {
            extract ( $_POST ) ;
            } else if (! empty ( $HTTP_POST_VARS )) {
                extract ( $HTTP_POST_VARS ) ;
            }

            if (! empty ( $_GET )) {
               extract ( $_GET ) ;
           } else if (! empty ( $HTTP_GET_VARS )) {
               extract ( $HTTP_GET_VARS ) ;
           }

            $desiredname='';
//<!----------------------------------Start------------------------------------>            
                            $password		=	$_POST['password'];
                            $firstname		=	$_POST['f_name'];
                            $lastname    		=   $_POST['l_name'];
                            $businessname		=	$_POST['business_name'];
                            $address		= 	$_POST['address'];
                            $addcity		= 	$_POST['city'];
                            $addstate		= 	$_POST['state'];
                            $addzipcode		= 	$_POST['zip'];
                            $phonenumber	 	= 	$_POST['phone_no'];
                            $altphonenumber 	= 	$_POST['alt_phone'];
                            $altdeliveryadd		= 	$_POST['ad_address'];
                            $altaddcity		= 	$_POST['ad_city'];
                            $altaddstate		= 	$_POST['ad_state'];
                            $altaddzipcode		= 	$_POST['ad_zip'];
                            
                            $errMessage='';
            
                            if($password == ''){
					$errMessage="Please Enter Password";
                            }elseif($firstname == ''){
					$errMessage="Please Enter First Name";
                            }elseif($lastname == ''){
                                            $errMessage="Please Enter Last Name";
                            }elseif($businessname == ''){
                                            $errMessage="Please Enter Business Name";
                            }elseif($address == '' ){
                                            $errMessage="Please Enter Address";
                            }elseif($addcity == '' ){
                                            $errMessage="Please Enter City Name";
                            }elseif($addstate == '' ){
                                            $errMessage="Please Enter State Name";
                            }elseif($addzipcode == '' ){
                                            $errMessage="Please Enter Zip Code";	
                            }elseif($phonenumber == '' ){
                                            $errMessage="Please Enter Phone Number";	
                            }elseif($altphonenumber == '' ){
                                            $errMessage="Please Enter Alternate Phone Number";	
                            }elseif($altdeliveryadd == '' ){
                                            $errMessage="Please Enter Alternate Delivery Address";		
                            }elseif($altaddcity == '' ){
                                            $errMessage="Please Enter City Name";	
                            }elseif($altaddstate == '' ){
                                            $errMessage="Please Enter State Name";	
                            }elseif($altaddzipcode == '' ){
                                            $errMessage="Please Enter Zip Code";
                            }else{
                                print_r("Update Data Process");
                            
//<!----------------------------------End--------------------------------------->                            
            //Query changed by Saad in order to not to store email address again.
            dbAbstract::Update("UPDATE customer_registration SET cust_desire_name= '".addslashes($desiredname)."', password= '$password', cust_your_name= '".addslashes($f_name)."',LastName='".addslashes($l_name)."',cust_business_name= '".addslashes($business_name)."', cust_odr_address= '".addslashes($address)."', cust_ord_city= '".addslashes($city)."', cust_ord_state= '".addslashes($state)."', cust_ord_zip= '$zip', cust_phone1= '$phone_no', cust_phone2= '$alt_phone', delivery_address1= '".addslashes($ad_address)."', deivery1_zip= '$ad_zip',delivery_city1= '".addslashes($ad_city)."',delivery_state1= '".addslashes($ad_state)."' WHERE id=$user_id", 1);
            ?>

            <script language="javascript">
		window.location="./?mod=customer&cid=<?=$mRestaurantIDCP?>";
            </script>
<? } ?>            
<?	}// end submit ?>
   <script language="javascript">     
    function DeleteUserProfile(uid){
	abc= confirm('You Are About To Completely Delete Your Account Which Will Remove All Orders, Against Your Account. Are You Sure? YES/NO');
	alert(abc);
	if(abc == 'true'){
		window.location="?mod=<?=$mod?>&item=deleteuser&userid="+uid;
	};
	
}
</script>
        
<div id="main_heading">EDIT REGISTER CUSTOMER INFO</div>
<? if ($errMessage != "" ) { ?><div class="msg_done"><?=$errMessage?></div>
<? }?>
   <div class="form_outer">
    <form action="" method="post" name="form1">
    <table width="750" border="0" cellpadding="4" cellspacing="0">
      <tr align="left" valign="top">
        <td><strong>Email:</strong></td>
        <td><!--<input name="email" type="text" value="<?=$user_qryRs->cust_email?>" size="40" />&nbsp;&nbsp;&nbsp;&nbsp;<strong><a href="" onclick="DeleteUserProfile(<? echo $user_id;?>);" style="text-decoration:underline;">Delete</a></strong>-->
            <!-- disabled added by Saad on 12-Aug-2014 to disallow customer email change -->
        <input name="email" type="text" value="<?=$user_qryRs->cust_email?>" disabled="disabled" size="40" />&nbsp;&nbsp;&nbsp;&nbsp;<strong><a href="?mod=<?=$mod?>&item=deleteuser&userid=<?=$user_id?>" onclick="return confirm('You Are About To Completely Delete Your Account Which Will Remove All Orders, Against Your Account. Are You Sure? YES/NO')" style="text-decoration:underline;">Delete</a></strong>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Password:</strong></td>
        <td><input name="password" type="text" value="<?=$user_qryRs->password?>" size="40" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="200"><strong>First Name:</strong></td>
        <td width="332"><input name="f_name" type="text" value="<?=$user_qryRs->cust_your_name?>" size="40" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="200"><strong>Last Name:</strong></td>
        <td width="332"><input name="l_name" type="text" value="<?=$user_qryRs->LastName?>" size="40" /></td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Business Name: </strong></td>
        <td><input name="business_name" type="text" value="<?=$user_qryRs->cust_business_name?>" size="40" /></td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Address:</strong></td>
        <td><input name="address" type="text" value="<?=$user_qryRs->cust_odr_address?>" size="40" />
          Address<br />
          <input name="city" type="text" value="<?=$user_qryRs->cust_ord_city?>" size="40" />
          City<br />
          <input name="state" type="text" value="<?=$user_qryRs->cust_ord_state?>" size="40" />
          <?=$mStateProvince?><br />
          <input name="zip" type="text" value="<?=$user_qryRs->cust_ord_zip?>" size="40" />
          <?=$mZipPostal?></td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Phone Number: </strong></td>
        <td><input name="phone_no" type="text" value="<?=$user_qryRs->cust_phone1?>" size="20" /></td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Alt Phone Number: </strong></td>
        <td><input name="alt_phone" type="text" value="<?=$user_qryRs->cust_phone2?>" size="20" /></td>
      </tr>
      
      <tr align="left" valign="top">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Alternate Delivery Address 1 </strong></td>
        <td><input name="ad_address" type="text" value="<?=$user_qryRs->delivery_address1?>" size="40" />
          Address<br />
          <input name="ad_city" type="text" value="<?=$user_qryRs->delivery_city1?>" size="40" />
          City<br />
          <input name="ad_state" type="text" value="<?=$user_qryRs->delivery_state1?>" size="40" />
          <?=$mStateProvince?><br />
          <input name="ad_zip" type="text" value="<?=$user_qryRs->deivery1_zip?>" size="40" />
          <?=$mZipPostal?></td>
      </tr>
      <tr align="left" valign="top">
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="Edit Customer Information" />
        <input name="userid" type="hidden" value="<?=$user_id?>" />
        </td>
      </tr>
    </table>
    </form>
   </div>

