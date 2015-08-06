<?php
$errMessage="";
$current_time 	= time();
$country_obj	=	new clscountry();

$myimage		= new ImageSnapshot; //new instance
$myimage->ImageField	 = $_FILES['company_logo']; //uploaded file array
function GetFileExt($fileName){
			$ext = substr($fileName, strrpos($fileName, '.') + 1);
			$ext = strtolower($ext);
			return $ext;
}
	
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
	} else if($phone == '') {
		 $errMessage = "Please enter phone number";
	} else if($user_name == '') {
		 $errMessage = "Please enter user name";
	} else if(dbAbstract::returnRowsCount($sqlResult,1) > 0) {
		 $errMessage = "User name already exists. Please select another.";
	} else if($password == '') {
		 $errMessage = "Please enter password";
	} else if($password != $confirm_password) {
		 $errMessage = "Password and Confirm Password fields should match eatch other";
	} else if($country < 0) {
		 $errMessage = "Please select country";
	} else if($address == '') {
		 $errMessage = "Please enter address";
	}else if($state == '') {
		 $errMessage = "Please enter state/province";
	} else if($city == '') {
		 $errMessage = "Please enter city";
	} else if($zip == '') {
		 $errMessage = "Please enter zip/postal";
	} else if($company_name == '') {
		 $errMessage = "Please enter company name";
	} else if($company_logo_link == '') {
		 $errMessage = "Please enter company_logo_link";
	}else if (!empty($_FILES['company_logo']['name']) && $myimage->ProcessImage() == false) {	
			$errMessage="Pleaser enter valid logo thumbnail"; 
	} else if($number_of_licences < 0) {
		 $errMessage = "Please select number of licenses";
	} else {
            $payment_profile_id ='';
            $product_id = $ChargifyResellerProduct;
            

            $chargify_customer_id = $chargify->createCustomer($first_name,$last_name,$email,$company_name,$city,$state,$zip,$country,$address,$phone);
                        
            $chargify_subscription_id = $chargify->createResellerSubcription($product_id,$chargify_customer_id,$subcription_method,$payment_profile_id,$card_no,$exp_date,$exp_year,$number_of_licences);
           	
            //echo "<pre>";print_r($chargify_subscription_id);exit;
            $credit_card_info = $chargify_subscription_id->subscription;
            
            $check_card_data_Qry = dbAbstract::ExecuteObject("Select * from chargify_payment_method where chargify_customer_id = '".$chargify_customer_id."' and card_number='".$credit_card_info->credit_card->masked_card_number."'",1);
            

            
            if(!empty($chargify_customer_id))
            {
                if(!empty($chargify_subscription_id->subscription))
                {
                    $sql="INSERT INTO users (firstname,lastname,email,phone,username,password,country,state,city,zip,status,type,company_name,company_logo_link,number_of_licenses,chargify_customer_id,chargify_subcription_id,address)
                        values ('".dbAbstract::returnRealEscapedString(stripslashes($first_name))."','".dbAbstract::returnRealEscapedString(stripslashes($last_name))."',
                        '".dbAbstract::returnRealEscapedString(stripslashes($email))."','$phone','".dbAbstract::returnRealEscapedString(stripslashes($user_name))."',
                        '$password','".dbAbstract::returnRealEscapedString(stripslashes($country))."','".dbAbstract::returnRealEscapedString(stripslashes($state))."',
                        '".dbAbstract::returnRealEscapedString(stripslashes($city))."','$zip','1','reseller','".dbAbstract::returnRealEscapedString(stripslashes($company_name))."',
                        '".dbAbstract::returnRealEscapedString(stripslashes($company_logo_link))."','$number_of_licences','".$chargify_customer_id."','".$chargify_subscription_id->subscription->id."','".dbAbstract::returnRealEscapedString(stripslashes($address))."'
                        )";

                        $reseller_id = dbAbstract::Insert($sql, 1, 2);
                        if($reseller_id > 0)  {

                            dbAbstract::Insert(
                                        "INSERT INTO chargify_products
                                        SET user_id= '".addslashes($reseller_id)."'
                                                ,product_id	= '".$ChargifyStandardProduct."'
                                                ,api_access_key='2aRl08rsgL3H3WiWl5ar'
                                                ,site_shared_key='Lh2aYDxDHC5oBUkADFF'
                                                ,return_url= '".$ChargifyURL."self-signup-wizard/index.php'
                                                ,update_return_url='http://easywayordering.com/self-signup-wizard/handle_update_payment.php'
                                                ,hosted_page_url='".$ChargifyURL."h/".$ChargifyStandardProduct."/subscriptions/new '
                                                ,premium_account=0
                                                ,status=1
                                ",1);
                            dbAbstract::Insert(
                                        "INSERT INTO chargify_products
                                        SET user_id= '".addslashes($reseller_id)."'
                                                ,product_id	= '".$ChargifyPremiumProduct."'
                                                ,api_access_key='2aRl08rsgL3H3WiWl5ar'
                                                ,site_shared_key='Lh2aYDxDHC5oBUkADFF'
                                                ,return_url= '".$ChargifyURL."self-signup-wizard/index.php'
                                                ,update_return_url='http://easywayordering.com/self-signup-wizard/handle_update_payment.php'
                                                ,hosted_page_url='".$ChargifyURL."h/".$ChargifyPremiumProduct."/subscriptions/new '
                                                ,premium_account=1
                                                ,status=1
                                ",1);
                            if(empty($check_card_data_Qry))
                            {
                                dbAbstract::Insert(
                                        "INSERT INTO chargify_payment_method
                                        SET user_id= '".addslashes($reseller_id)."'
                                                ,chargify_customer_id= '".addslashes($chargify_customer_id)."'
                                                ,Payment_profile_id='".addslashes($credit_card_info->credit_card->id)."'
                                                ,card_number='".$credit_card_info->credit_card->masked_card_number."'",1
                                );
                            }
			if(!empty($_FILES['company_logo']['name']))
			{
				$path = '../images/logos_thumbnail/';
				$exe = GetFileExt($_FILES['company_logo']['name']);	
				$image_name = "img_".$reseller_id."_reseller_thumbnail.".$exe;
				$uploadfile = $path . $image_name;					
				move_uploaded_file( $_FILES['company_logo']['tmp_name'] , $uploadfile );
				/*list($width, $height, $type, $attr) = getimagesize("$uploadfile");
					if($height>$width){
					$image = new SimpleImage();
					$image->load($uploadfile);
					$image->resizeToHeight(80);
					$image->save($uploadfile);
					}else {
					$image = new SimpleImage();
					$image->load($uploadfile);
					$image->resizeToWidth(100);
					$image->save($uploadfile);
					}*/
		dbAbstract::Update("UPDATE users SET company_logo='$image_name' where id =".$reseller_id,1);						
			}
			
			for( $i= 1; $i<= $number_of_licences ; $i++ ) {
				$license_key = rand(0,99999999);
				$license_qry = "INSERT INTO licenses (  reseller_id, status,dated ) VALUES ( '$reseller_id', 'unused',".time()." )";
				$license_id  = dbAbstract::Insert($license_qry, 1, 2);
				$license_key = $license_id.$license_key;
				$license_update_qry_srt = "UPDATE licenses SET license_key ='".$license_key."' WHERE id = '".$license_id."'";
				dbAbstract::Update($license_update_qry_srt,1);
			}
                        $errMessage="Reseller Added Successfully";
		}	
		?>
<!--------------------------------Start------------------------------------------------->
<script type="text/javascript">
function leave() {
  window.location="./?mod=resellers&item=main";
}
setTimeout("leave()", 2000);
</script>
<!--------------------------------End---------------------------------------------------->

		<? 
                }
                else
                {
                    $errorMsg = $chargify_subscription_id['errors'];
                    $errMessage =  $errorMsg[0];
                }
        } 
	}
            
}
?>

<div id="main_heading">Add Reseller</div>
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
        <td width="160">Phone:</td>
        <td><input name="phone" type="text" size="40" value="<?=@$phone?>" id="phone" />
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
        <td><input name="confirm_password" type="password" size="40" value="<?=@$confirm_password?>" id="confirm_password" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">Country:</td>
        <td><select name="country" id="country" style="width:270px;" >
            <option value="-1">Select Country</option>
            <?=$country_obj->get_country_drop_down(@$country) ?>
          </select></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">Address:</td>
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
        <td width="160">Company Name</td>
        <td><input name="company_name" type="text" size="40" value="<?=@$company_name?>" id="company_name" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">Company Logo</td>
        <td><input name="company_logo" type="file" size="28"><br />
        	height:126, width:31
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">Company Logo Link</td>
        <td>http://&nbsp; <input name="company_logo_link" type="text" size="33" value="<?=@$company_logo_link?>" id="company_name" /><br />
        	eg.&nbsp;&nbsp;&nbsp;&nbsp; <span style="color:#4c4c4c; size:7px">yourcompany.com</span>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">Number of licenses :</td>
        <td><select name="number_of_licences" id="number_of_licences" style="width:270px;" >
            <option value="-1">Select Licenses</option>
			<?
			for($i= 1; $i<= 20 ; $i++) {
			?>
             <option value="<?=$i?>"> <?=$i?> </option>
			<?
				}
			?>
          </select></td>
      </tr>
          <tr align="left" valign="top">
            <td><strong>Billing Information</strong></td>
            <td></td>
          </tr>

          <tr align="left" valign="top">
            <td>Subcription type</td>
            <td><input name="subcription_method" type="radio" value="automatic"  id="payment_collection_method" checked> automatic           &nbsp;&nbsp;
            <input name="subcription_method" type="radio" value="invoice"  id="payment_collection_method" >Invoice</td>
          </tr>
 
          <tbody class="payment_Method">

            <tr>
             <td></td>
            <td>
		 </td>
          </tr>
           <tr align="left" valign="top">
            <td><strong>Card no:</strong></td>
            <td>
		<input name="card_no" type="text" size="40"  id="card_no" /> </td>
           </tr>

           <tr>
             <td><strong>Exp Date:</strong></td>
            <td>
                 <select name="exp_date" id="exp_date" style="width:70px;" >
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
      <tr align="left" valign="top">
        <td width="160"></td>
        <td><input name="submit" Type="submit"  value="Submit" tabindex="1" id="submit" />
        </td>
      </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
   
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
</script>