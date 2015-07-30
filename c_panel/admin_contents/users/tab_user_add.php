<?php
$country_obj				=	new clscountry();
$errMessage='';
if($_POST) {
	extract($_POST);
	
	// to avoid duplicate insertion...
	$sqlResult = dbAbstract::Execute("SELECT username FROM users WHERE username ='" .$user_name. "'",1);
	
	$errMessage="";
	if($first_name == '') {
		 $errMessage = "Please enter first name";
	} else if($last_name == '') {
		 $errMessage = "Please enter last name";
	} else if($email == '') {
		 $errMessage = "Please enter email address";
	} else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
		 $errMessage = "Please enter email address in correct format";
	} else if($user_name == '') {
		 $errMessage = "Please enter user name";
	} else if(dbAbstract::returnRowsCount($sqlResult,1) > 0) {
		 $errMessage = "User name already exists. Please select another.";
	} else if($password == '') {
		 $errMessage = "Please enter password";
	} else if($password != $confirm_password) {
		 $errMessage = "Password and Confirm Password fields should match eatch other";
	} else if(  $_SESSION['admin_type'] == 'admin' && $reseller < 0) {
		 $errMessage = "Please select reseller";
	} else if($country < 0) {
		 $errMessage = "Please select country";
	}else if($address == '') {
                 $errMessage = "Please enter address";
        }
	 else if($state == '') {
		 $errMessage = "Please enter state/province";
	} else if($city == '') {
		 $errMessage = "Please enter city";
	} else if($zip == '') {
		 $errMessage = "Please enter zip/postal";
	} else {
		$phone = '';
		$chargify_customer_id = $chargify->createCustomer($first_name,$last_name,$email,$company_name,$city,$state,$zip,$country,$address,$phone);
		
                if(!empty($chargify_customer_id))
                {   
                    $sql="INSERT INTO users(firstname,lastname,email,username,password,country,state,city,zip,status,type,chargify_customer_id,address) values ('".dbAbstract::returnRealEscapedString(stripslashes($first_name))."','".dbAbstract::returnRealEscapedString(stripslashes($last_name))."','".dbAbstract::returnRealEscapedString(stripslashes($email))."','".dbAbstract::returnRealEscapedString(stripslashes($user_name))."','$password','".dbAbstract::returnRealEscapedString(stripslashes($country))."','".dbAbstract::returnRealEscapedString(stripslashes($state))."','".dbAbstract::returnRealEscapedString(stripslashes($city))."','$zip','1','".dbAbstract::returnRealEscapedString(stripslashes("store owner"))."','".$chargify_customer_id."','".dbAbstract::returnRealEscapedString(stripslashes($address))."')";
                    $client_id = dbAbstract::Insert($sql, 1, 2);
                    
                    $resllerID=0;
                    if( $_SESSION['admin_type'] == 'admin' ) {
                            $reseller_client_sql = "INSERT INTO reseller_client (reseller_id,client_id) VALUES ('".$reseller."','".$client_id."')";
                            $resllerID=$reseller;
                    } else  if( $_SESSION['admin_type'] == 'reseller') {
                            $reseller_client_sql = "INSERT INTO reseller_client (reseller_id,client_id) VALUES ('".$_SESSION[owner_id]."','".$client_id."')";
                                                    $resllerID=$_SESSION[owner_id];
                    }
                }
		if($result = dbAbstract::Insert($reseller_client_sql,1))  {
			 ?>
			<script language="javascript">
            window.location="./?mod=clients&resellerId=<?=$resllerID?>";
        </script>
			 
			 <?
		} 
	}	
}

?>

<div id="main_heading">ADD CLIENT</div>
<? if ($errMessage != "" ) { ?>
<div class="msg_done">
  <?=$errMessage?>
</div>
<? }?>
<div class="form_outer">
  <form method="post" action="" enctype="multipart/form-data" id="userRegFrm" name="userRegFrm">
    <table width="570" border="0" cellpadding="4" cellspacing="0" >
      <tr align="left" valign="top">
        <td width="160">First Name:</td>
        <td width="394"><input name="first_name" type="text" size="40" value="<?=@$first_name?>" id="first_name" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">Last Name:</td>
        <td><input name="last_name" type="text" size="40" value="<?=@$last_name?>" id="last_name" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">Email:</td>
        <td><input name="email" type="text" size="40" value="<?=@$email?>" id="email" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">User Name:</td>
        <td><input name="user_name" type="text" size="40" value="<?=@$user_name?>" id="user_name" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">Password:</td>
        <td><input name="password" type="password" size="40" value="<?=@$password?>" id="password" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">Confirm Password:</td>
        <td><input name="confirm_password" type="password" size="40" value="" id="confirm_password" />
        </td>
      </tr>
     <? if( $_SESSION['admin_type'] == 'admin') {?>
      <tr align="left" valign="top">
        <td width="160">Reseller:</td>
        <td><select name="reseller" id="reseller" style="width:270px;" >
            <option value="-1">Select Reseller</option>
            <?=resellers_drop_down(@$reseller) ?>
          </select>
        </td>
      </tr>
     <? } ?>
      <tr align="left" valign="top">
        <td width="160">Country:</td>
        <td><select name="country" id="country" style="width:270px;" >
            <option value="-1">Select Country</option>
            <?=$country_obj->get_country_drop_down(@$country) ?>
          </select>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">address:</td>
        <td><input name="address" type="text" size="40" value="<?=@$address?>" id="address"/>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">State/Province:</td>
        <td><input name="state" type="text" size="40" value="<?=@$state?>" id="state"/>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">City:</td>
        <td><input name="city" type="text" size="40" value="<?=@$city?>" id="city"/>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">Zip/Postal:</td>
        <td><input name="zip" type="text" size="40" value="<?=@$zip?>" id="zip" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"></td>
        <td><input name="submit" Type="submit"  value="Submit" tabindex="1" id="submit" />
        </td>
      </tr>
    </table>
  </form>
</div>
