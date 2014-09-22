<?	

$country_obj				=	new clscountry();
	$errMessage='';
	$message='';
	$myimage				= new ImageSnapshot; //new instance
	if(isset($_FILES['company_logo']))$myimage->ImageField	= $_FILES['company_logo']; //uploaded file array
	function GetFileExt($fileName){
			$ext = substr($fileName, strrpos($fileName, '.') + 1);
			$ext = strtolower($ext);
			return $ext;
	}
	
	$reseller_id	=	(isset($_REQUEST['reseller_id']) ?$_REQUEST['reseller_id'] : $_SESSION['owner_id']); 
	
	$reseller_qry	=	mysql_query("select * from users where id = $reseller_id");
	$reseller_qryRs	=	mysql_fetch_object($reseller_qry);		   
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
				
				$errMessage="";
				
				$reseller_qry1	=	mysql_query("select * from users where username = '$user_name' AND id != $reseller_id");
				
				if($first_name == '') {
					 $errMessage = "Please enter first name";
				} else if($last_name == '') {
					 $errMessage = "Please enter last name";
				} else if($_SESSION['admin_type'] == 'admin' && $email == '') {
					 $errMessage = "Please enter email address";
				} else if( $_SESSION['admin_type'] == 'admin' && !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
					 $errMessage = "Please enter email address in correct format";
				} else if($phone == '') {
					 $errMessage = "Please enter phone number";
				} else if( $_SESSION['admin_type'] == 'admin' && $user_name == '') {
					 $errMessage = "Please enter user name";
				} else if(mysql_num_rows($reseller_qry1) > 0) {
					 $errMessage = "User name already exists. Please select another.";
				} else if($_SESSION['admin_type'] == 'admin' && $password == '') {
					 $errMessage = "Please enter password";
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
				}else if (!empty($_FILES['company_logo']['name']) && $myimage->ProcessImage() == false) {	
					 $errMessage="Pleaser enter valid logo thumbnail"; 
				} else if($company_logo_link == '') {
					 $errMessage = "Please enter company logo link";
				} else {

					if(!empty($_FILES['company_logo']['name']))
					{
						$path = '../images/logos_thumbnail/';
						$exe = GetFileExt($_FILES['company_logo']['name']);	
						$image_name = "img_".$reseller_id."_reseller_thumbnail.".$exe;
						//die("IMAGE NAME: ".$image_name);
						$uploadfile = $path . $image_name;					
						move_uploaded_file( $_FILES['company_logo']['tmp_name'] , $uploadfile );
					 
							
					}else {
						$image_name = $reseller_qryRs->company_logo;
					}
					
					if(!empty($_FILES['pdf_image_header']['name']))
							{
								$path = '../images/resturant_headers/';
								$exe = $function_obj->GetFileExt($_FILES['pdf_image_header']['name']);	
						 
								$name = "img_".$reseller_id ."_pdf_header.".$exe;
								$uploadfile = $path . $name;
								move_uploaded_file( $_FILES['pdf_image_header']['tmp_name'] , $uploadfile );
								list($width, $height, $type, $attr) = getimagesize("$uploadfile");
									if($height>$width){
										$image = new SimpleImage();
										$image->load($uploadfile);
										$image->resizeToHeight(50);
										$image->save($uploadfile);
									}else {
										$image = new SimpleImage();
										$image->load($uploadfile);
										$image->resizeToWidth(850);
										$image->save($uploadfile);
									}	
							}else{
							 
									$name = $pdf_image_header1;
								}
			
					
					if( $_SESSION['admin_type'] == 'admin' ) {
					
						$sql = "UPDATE users SET firstname= '".mysql_real_escape_string(stripslashes($first_name))."', 
						lastname= '".mysql_real_escape_string(stripslashes($last_name))."', email= '".mysql_real_escape_string(stripslashes($email))."', 
						phone ='$phone', username='".mysql_real_escape_string(stripslashes($user_name))."',password= '$password', 
						country= '".mysql_real_escape_string(stripslashes($country))."', state= '".mysql_real_escape_string(stripslashes($state))."', 
						city= '".mysql_real_escape_string(stripslashes($city))."', zip= '$zip',
						company_name= '".mysql_real_escape_string(stripslashes($company_name))."',
						company_logo= '".mysql_real_escape_string(stripslashes($image_name))."',
						company_logo_link= '".mysql_real_escape_string(stripslashes($company_logo_link))."',
						pdf_image_header= '". $name ."',
						plain_text_header= '".mysql_real_escape_string(stripslashes($plain_text_header))."',
						aDotNet_access= '".$aDotNet_access."',address='".mysql_real_escape_string(stripslashes($address))."' WHERE id=$reseller_id";
					
					} else if( $_SESSION['admin_type'] == 'reseller' ) { 
						
						$sql = "UPDATE users SET firstname= '".mysql_real_escape_string(stripslashes($first_name))."', 
						lastname= '".mysql_real_escape_string(stripslashes($last_name))."', 
						phone ='$phone', 
						country= '".mysql_real_escape_string(stripslashes($country))."', state= '".mysql_real_escape_string(stripslashes($state))."', 
						city= '".mysql_real_escape_string(stripslashes($city))."', zip= '$zip',
						company_name= '".mysql_real_escape_string(stripslashes($company_name))."',
						company_logo= '".mysql_real_escape_string(stripslashes($image_name))."',
								pdf_image_header= '". $name ."',
						plain_text_header= '".mysql_real_escape_string(stripslashes($plain_text_header))."',
						company_logo_link= '".mysql_real_escape_string(stripslashes($company_logo_link))."',address='".mysql_real_escape_string(stripslashes($address))."' WHERE id=$reseller_id";

					}
                                        if(!empty($reseller_qryRs->chargify_customer_id))
                                        {
                                            $chargify_customer_id = $chargify->updateCustomer($reseller_qryRs->chargify_customer_id,$first_name,$last_name,$email,$company_name,$city,$state,$zip,$country,$address,$phone);
                                        }
                                        if($_SESSION['admin_type'] == 'admin' && $result = mysql_query($sql))  { ?>
						<script language="javascript">
							window.location="./?mod=resellers";
						</script> 
                 <? } else if( $_SESSION['admin_type'] == 'reseller' && $result = mysql_query($sql) ) {
				 		 $message="Your profile information has been updated successfully.";
					
					} else {
					  die("ERROR: ".mysql_error()."<br/><br/><br/><br/><br/><br/>"); 
				  	}	 
				}
				?>
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
        
<div id="main_heading">Edit Reseller Info</div>
<? if ($errMessage != "" ) { ?><div class="msg_done"><?=$errMessage?></div>
<? }?>
<div class="Table_Outer_Div">
<div class="form_outer" style="float:left; width:445px;">
    <form action="" method="post" name="form1" enctype="multipart/form-data">
    <table width="100%" border="0"  cellpadding="4" cellspacing="0">
    <? if($message) { ?>
    <tr>
    <td colspan="2">
    <div class="msg_done"><?=$message?></div>
    </td>
    </tr>
    <? } ?>
      <tr align="left" valign="top">
        <td class="Width"><strong>First Name:</strong></td>
        <td>
        <input name="first_name" id="first_name" type="text" value="<?=$reseller_qryRs->firstname?>" size="40" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Last Name:</strong></td>
        <td><input name="last_name" type="text" value="<?=$reseller_qryRs->lastname?>" size="40" id="last_name" /></td>
      </tr>
      <? if($_SESSION['admin_type'] == 'admin') {?>
      <tr align="left" valign="top">
        <td width="160"><strong>Email:</strong></td>
        <td width="400"><input name="email" id="email" type="text" size="40"  value="<?=$reseller_qryRs->email?>"  /></td>
      </tr>
      <? }?>
      <tr align="left" valign="top">
        <td width="160"><strong>Phone:</strong></td>
        <td width="400"><input name="phone" id="phone" type="text" size="40"  value="<?=$reseller_qryRs->phone?>"  /></td>
      </tr>
      <? if($_SESSION['admin_type'] == 'admin') {?>
      <tr align="left" valign="top">
        <td width="160"><strong>User Name:</strong></td>
        <td width="400"><input name="user_name" type="text" size="40" value="<?=$reseller_qryRs->username?>" id="user_name" /></td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Password: </strong></td>
        <td><input name="password" type="password" value="<?=$reseller_qryRs->password?>" size="40" id="password" /></td>
      </tr>
      <? }?>
      <tr align="left" valign="top">
        <td width="160"><strong>Country:</strong></td>
<td width="400"><select name="country" id="country" style="width:270px;" >
                      <option value="-1">Select Country</option>
                       <?=$country_obj->get_country_drop_down($reseller_qryRs->country) ?>
                    </select></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>address:</strong></td>
        <td width="400"><input name="address" type="text" size="40" value="<?=$reseller_qryRs->address?>" id="address" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>State/Province:</strong></td>
        <td width="400"><input name="state" type="text" size="40" value="<?=$reseller_qryRs->state?>" id="state" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>City:</strong></td>
        <td width="400"><input name="city" type="text" size="40" value="<?=$reseller_qryRs->city?>" id="city" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Zip/Postal:</strong></td>
        <td width="400"><input name="zip" type="text" size="40" value="<?=$reseller_qryRs->zip?>" id="zip" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Company Name:</strong></td>
        <td width="400"><input name="company_name" type="text" size="40" value="<?=$reseller_qryRs->company_name?>" id="company_name" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Company Logo:</strong></td>
        <td width="400"><input name="company_logo" type="file" size="28"><br />
        	<img style="width:100px; height:40px; margin-top:5px" src="../images/logos_thumbnail/<?=$reseller_qryRs->company_logo ?>" border="0" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Company Logo Link:</strong></td>
        <td width="400">http://&nbsp;<input name="company_logo_link" type="text" size="33" value="<?=$reseller_qryRs->company_logo_link?>" id="company_name" /><br />
        eg.&nbsp;&nbsp;&nbsp;&nbsp; <span style="color:#4c4c4c; size:7px">yourcompany.com</span>
        </td>
      </tr>
      
      
    <tr align="left" valign="top">
      <td><strong>PDF Image Header:(2 X 10 in)</strong></td>
      <td><input name="pdf_image_header" type="file" id="pdf_image_header" />
     
      <input type="hidden" id="pdf_image_header1" name="pdf_image_header1" value="<?= $reseller_qryRs->pdf_image_header ?>"  />
      </td>
      <? if ($reseller_qryRs->pdf_image_header) { ?>
      <tr><td colspan="2" align="center">  <img src="../images/resturant_headers/<?= $reseller_qryRs->pdf_image_header ?>" width="80px" /></td></tr>
    </tr>
    <? } ?>
    <tr align="left" valign="top">
      <td><strong>Plain Text Header:</strong></td>
      <td><textarea id="plain_text_header" name="plain_text_header"><?= $reseller_qryRs->plain_text_header ?></textarea></td>
    </tr>
    
    
     <? if($_SESSION['admin_type'] == 'admin') {?>
      <tr align="left" valign="top">
        <td width="160"><strong>Authorise.Net Access</strong></td>
	<td width="400">
		<input name="aDotNet_access" type="radio" value="1"  id="aDotNet_access" <? if($reseller_qryRs->aDotNet_access == "1" ){ echo "checked";} ?>>Yes            &nbsp;&nbsp;
            <input name="aDotNet_access" type="radio" value="0"  id="aDotNet_access" <? if($reseller_qryRs->aDotNet_access == "0" ){ echo "checked";} ?>>No
      </tr>
      <? }?>
      <tr align="left" valign="top">
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="Edit user Information" />
       
        </td>
      </tr>
    </table>
  </form>
</div>
<script language="JavaScript">
<!--
    var flag = 0;
function calcHeight(frame_name) {
        if(flag==0 || flag==1 )
        {
	//find the height of the internal page
            var the_height = document.getElementById(frame_name).contentWindow.document.body.scrollHeight;
            //change the height of the iframe
            document.getElementById(frame_name).height = the_height+154;
            flag++;
        }
}
function calcHeight1(frame_name) {
        	//find the height of the internal page
            var the_height = document.getElementById(frame_name).contentWindow.document.body.scrollHeight;
            //change the height of the iframe
            document.getElementById(frame_name).height = the_height;
            
        
}
//-->
</script>
<div class="form_outer1">
  <? if($_SESSION['admin_type'] == 'admin') {?>
		<iframe src="admin_contents/resellers/tab_license_listing.php?reseller_id=<?=$reseller_id?>" style="border:0px" width="100%" scrolling="no" id="iframe1" onload="calcHeight('iframe1')"></iframe>
  <? } else if($_SESSION['admin_type'] == 'reseller') { ?>
		<iframe src="admin_contents/resellers/tab_reseller_licenses_list.php" style="border:0px" width="100%" scrolling="no" id="iframe1" onload="calcHeight('iframe1')"></iframe>
  <? }?>
  <iframe src="admin_contents/resellers/tab_chargify_products_listing.php?reseller_id=<?=$reseller_id?>" style="border:0px" width="100%" scrolling="no" id="iframe2" onload="calcHeight1('iframe2')"></iframe>
</div>
<div style="clear:both"></div>
</div>