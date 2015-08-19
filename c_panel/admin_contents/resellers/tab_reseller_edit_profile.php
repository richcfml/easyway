<?php
        $country_obj				=	new Country();
	$myimage				= new ImageSnapshot; //new instance
	$myimage->ImageField	= $_FILES['company_logo']; //uploaded file array
	function GetFileExt($fileName){
			$ext = substr($fileName, strrpos($fileName, '.') + 1);
			$ext = strtolower($ext);
			return $ext;
	}
	
	$reseller_id	=	 	(isset($_REQUEST['reseller_id']) ?$_REQUEST['reseller_id'] :$_SESSION['owner_id']); 
	
	$reseller_qry	=	dbAbstract::Execute("select * from users where id = $reseller_id",1);
	$reseller_qryRs	=	dbAbstract::returnObject($reseller_qry,1);		   
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
				
				$reseller_qry1	=	dbAbstract::Execute("select * from users where username = '$user_name' AND id != $reseller_id",1);
				
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
				} else if(dbAbstract::returnRowsCount($reseller_qry1,1) > 0) {
					 $errMessage = "User name already exists. Please select another.";
				} else if($_SESSION['admin_type'] == 'admin' && $password == '') {
					 $errMessage = "Please enter password";
				} else if($country < 0) {
					 $errMessage = "Please select country";
				} else if($state == '') {
					 $errMessage = "Please enter state";
				} else if($city == '') {
					 $errMessage = "Please enter city";
				} else if($zip == '') {
					 $errMessage = "Please enter zip";
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
						/*list($width, $height, $type, $attr) = getimagesize("$uploadfile");
							if($height>$width){
							$image = new SimpleImage();
							$image->load($uploadfile);
							$image->resizeToHeight(100);
							$image->save($uploadfile);
							}else {
							$image = new SimpleImage();
							$image->load($uploadfile);
							$image->resizeToWidth(100);
							$image->save($uploadfile);
							}	*/			
							
					}else {
						$image_name = $reseller_qryRs->company_logo;
					}
			
					
				    if( $_SESSION['admin_type'] == 'reseller' ) { 
						
						$sql = "UPDATE users SET firstname= '".dbAbstract::returnRealEscapedString(stripslashes($first_name))."', 
						lastname= '".dbAbstract::returnRealEscapedString(stripslashes($last_name))."', 
						phone ='$phone', 
						country= '".dbAbstract::returnRealEscapedString(stripslashes($country))."', state= '".dbAbstract::returnRealEscapedString(stripslashes($state))."', 
						city= '".dbAbstract::returnRealEscapedString(stripslashes($city))."', zip= '$zip',
						company_name= '".dbAbstract::returnRealEscapedString(stripslashes($company_name))."',
						company_logo= '".dbAbstract::returnRealEscapedString(stripslashes($image_name))."',
						company_logo_link= '".dbAbstract::returnRealEscapedString(stripslashes($company_logo_link))."' WHERE id=$reseller_id";

					}
					
					  if( $_SESSION['admin_type'] == 'reseller' && $result = dbAbstract::Update($sql,1) ) {
				 		 die("Your profile information has been updated successfully.");
					
					} 
				}
				?>
		<?	}// end submit ?>
    
        
<div id="main_heading">Edit Reseller Info</div>
<? if ($errMessage != "" ) { ?><div class="msg_done"><?=$errMessage?></div>
<? }?>
<div class="Table_Outer_Div">
<div class="form_outer" style="float:left; width:445px;">
    <form action="" method="post" name="form1" enctype="multipart/form-data">
    <table width="100%" border="0"  cellpadding="4" cellspacing="0">
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
                       <?=$country_obj->getCountyDropDownList($reseller_qryRs->country) ?>
                    </select></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>State:</strong></td>
        <td width="400"><input name="state" type="text" size="40" value="<?=$reseller_qryRs->state?>" id="state" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>City:</strong></td>
        <td width="400"><input name="city" type="text" size="40" value="<?=$reseller_qryRs->city?>" id="city" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Zip:</strong></td>
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
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="Edit user Information" />
        
        </td>
      </tr>
    </table>
  </form>
</div>
<script language="JavaScript">
<!--
function calcHeight(frame_name)
{   //find the height of the internal page
  var the_height=
    document.getElementById(frame_name).contentWindow.
      document.body.scrollHeight;
  //change the height of the iframe
  document.getElementById(frame_name).height=
      the_height;
}
//-->
</script>

<div class="form_outer1">
  <? if($_SESSION['admin_type'] == 'admin') {?>
   <iframe src="admin_contents/resellers/tab_license_listing.php?reseller_id=<?=$reseller_id?>" style="border:0px" width="100%" scrolling="no" id="iframe1" onload="calcHeight('iframe1')"></iframe>
  <? } else if($_SESSION['admin_type'] == 'reseller') { ?>
  	 <iframe src="admin_contents/resellers/tab_reseller_licenses_list.php?reseller_id=<?=$reseller_id?>" style="border:0px" width="100%" scrolling="no" id="iframe1" onload="calcHeight('iframe1')"></iframe>
  
  <? }?>
</div>
<div style="clear:both"></div>
</div>