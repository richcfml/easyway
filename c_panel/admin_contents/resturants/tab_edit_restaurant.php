 <?php
 function prepareStringForMySQL($string){
    $string=str_replace ( "\r" , "<br/>",$string);
    $string=str_replace ( "\n" , "<br/>",$string);
    $string=str_replace ( "\t" , " ",$string);
    $string=dbAbstract::returnRealEscapedString($string);
    return $string;
}
	if ($_REQUEST['cid']) {
		$catid 	=   $_REQUEST['cid']; 
		$_SESSION['ResturantId'] = $_REQUEST['cid'];
	} else {
		$catid 	=   $_SESSION['ResturantId']; 
	}
	$cat_info_qry	=	dbAbstract::Execute("select * from resturants where id = $catid",1);
	$cat_info_Rs	=	dbAbstract::returnObject($cat_info_qry,1);
	$header_image	=	$cat_info_Rs->header_image;
	$optional_image	=	$cat_info_Rs->optionl_logo;
	$cat_des		=	$cat_info_Rs->des;
	$cat_reviews	=	$cat_info_Rs->reviews;
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
						
		$myimage		= new ImageSnapshot; //new instance
		$myimage2 		= new ImageSnapshot; //new instance

		$myimage->ImageField	 = $_FILES['userfile']; //uploaded file array
		$myimage2->ImageField	 = $_FILES['userfile2']; //uploaded file array
		
		function GetFileExt($fileName){
			$ext = substr($fileName, strrpos($fileName, '.') + 1);
			$ext = strtolower($ext);
			return $ext;
		}
	
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
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		if (isset($_REQUEST['submit'])){
		$restQry=dbAbstract::Execute("select name from resturants where name='$catname' AND id!='$catid'",1);
		$restRs	=	dbAbstract::returnRowsCount($restQry,1);	
		if($restRs > 0) 
			$rest_exist = 1;	
		
		
			$errMessage ="";
			if ($catname == '') {
				$errMessage="Please Enter Restaurant Name";
			} else if($email == '') {
				$errMessage = "Please enter email address";
			} else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
				$errMessage = "Please enter email address in correct format";
			} else if($phone == '') {
				$errMessage = "Please enter phone number";
			} else if($fax == '') {
				$errMessage = "Please enter fax number";
			}else if (!empty($_FILES['userfile']['name']) && $myimage->ProcessImage() == false) {	
				$errMessage="Pleaser Enter Valid Logo"; 
			}else if (!empty($_FILES['userfile2']['name']) && $myimage2->ProcessImage() == false) {	
				$errMessage="Pleaser Enter Valid Logo Thumbnail";  
			} else if($rest_zip == '') {
				$errMessage = "Please enter resturant zip code";
			} else if($delivery_radius == '') {
				$errMessage = "Please enter delivery radius for resturant";
			} else if($order_minimum == '') {
				$errMessage = "Please enter minimum order ammount";
			} else if($tax_percent == '') {
				$errMessage = "Please enter sales tax percentage";
			} else if($delivery_charges == '') {
				$errMessage = "Please enter delivery charges";
			} else if($time_zone < 0) {
				$errMessage = "Please select resturant's time zone";
			} else if($voice_email == '' && $_SESSION['admin_type'] == 'admin') {
				$errMessage = "Please enter voice confirmation email address";
			} else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $voice_email) && $_SESSION['admin_type'] == 'admin') {
				$errMessage = "Please enter voice confirmation email address in correct format";
			} else if($credit == '' && $cash == '' && $_SESSION['admin_type'] == 'admin') {
				$errMessage = "Please select payment method";
			}else{
					if($credit & $cash) {
						  $payment_method = "both";
					  } else if($credit) {
						  $payment_method = "credit";	
					  } else if($cash) {
						  $payment_method = "cash"; 
					  }
					
					
					if(!empty($_FILES['userfile']['name']))
							{
								$path = '../images/resturant_logos/';
								$exe = GetFileExt($_FILES['userfile']['name']);	
								//$name = $_FILES['userfile']['name'];
								$name = "img_".$catid."_cat_logos.".$exe;
								$uploadfile = $path . $name;
								move_uploaded_file( $_FILES['userfile']['tmp_name'] , $uploadfile );
								list($width, $height, $type, $attr) = getimagesize("$uploadfile");
									if($height>$width){
									$image = new SimpleImage();
									$image->load($uploadfile);
									$image->resizeToHeight(500);
									$image->save($uploadfile);
									}else {
									$image = new SimpleImage();
									$image->load($uploadfile);
									$image->resizeToWidth(600);
									$image->save($uploadfile);
									}	
							}else{
									$name = $logo;
								}
						////////////////////////////////////////////////////////////
						
					if(!empty($_FILES['userfile2']['name']))
							{
								$path1 = '../images/logos_thumbnail/';
								$exe1 = GetFileExt($_FILES['userfile2']['name']);	
								//$name1 = $_FILES['userfile2']['name'];
								$name1 = "img_".$catid."_cat_thumbnail.".$exe1;
								$uploadfile1 = $path1 . $name1;					
								move_uploaded_file( $_FILES['userfile2']['tmp_name'] , $uploadfile1 );
								list($width, $height, $type, $attr) = getimagesize("$uploadfile1");
									if($height>$width){
									$image = new SimpleImage();
									$image->load($uploadfile1);
									$image->resizeToHeight(500);
									$image->save($uploadfile1);
									}else {
									$image = new SimpleImage();
									$image->load($uploadfile1);
									$image->resizeToWidth(600);
									$image->save($uploadfile1);
									}
							}else{
									$name1 = $thumb; 
								}
						////////////////////////////////////////////////////////////
						
						if(!empty($_FILES['userfile3']['name']))
							{
								$path3 = '../images/resturant_headers/';
								$exe3 = GetFileExt($_FILES['userfile3']['name']);	
								$name3 = "img_".$catid."_cat_header.".$exe3;
								//die($name3);
								//$name = $_FILES['userfile']['name'];
								$uploadfile3 = $path3 . $name3;
								move_uploaded_file( $_FILES['userfile3']['tmp_name'] , $uploadfile3 );			
									
							}else{
									$name3 = $_REQUEST[header_images];
								}
						///////////////////////////////////////////////////////////
							$homefeatute = 0;		
							if($open_close == ''){ $open_close = 0;}
							$catname = trim($catname," ");
							$rest_url_name = str_replace(" ","_", $catname); 
							$rest_url_name = strtolower($rest_url_name); 
							if($_SESSION['admin_type'] == 'admin') {
									dbAbstract::Update("UPDATE resturants SET name= '".addslashes($catname)."', email= '".addslashes($email)."', fax= '".addslashes($fax)."', phone= '".addslashes($phone)."', logo= '$name', optionl_logo='$name1', delivery_charges=$delivery_charges, order_minimum=$order_minimum,  tax_percent=$tax_percent, business_hrs= '".addslashes($business_hrs)."',header_image= '$name3',time_zone_id = '$time_zone' ,payment_method= '$payment_method' ,announcement='".$rest_announcements."',announce_status =$announce_status,rest_open_close =$rest_open_close,delivery_offer=$delivery_offer,authoriseLoginID= '$authoriseLoginID',transKey= '$transKey',voice_email='$voice_email',phone_notification='$phone_notification_status',rest_address= '$rest_address',rest_city= '$rest_city',rest_state= '$rest_state',rest_zip= '$rest_zip',delivery_radius='$delivery_radius',meta_keywords='$meta_keywords',meta_description='$meta_description' where id = $catid",1);	
									dbAbstract::Update("UPDATE analytics SET name= '".addslashes($catname)."', optionl_logo='$name1' , first_letter = '".strtoupper(substr(addslashes($catname), 0, 1))."' where resturant_id = $catid",1);	
								} else if ( $_SESSION['admin_type'] == 'store owner')   {
									dbAbstract::Update("UPDATE resturants SET name= '".addslashes($catname)."', email= '".addslashes($email)."', fax= '".addslashes($fax)."', phone= '".addslashes($phone)."', logo= '$name', optionl_logo='$name1', delivery_charges=$delivery_charges, order_minimum=$order_minimum,  tax_percent=$tax_percent, business_hrs= '".addslashes($business_hrs)."',header_image= '$name3',time_zone_id = '$time_zone' ,announcement='".$rest_announcements."',announce_status =$announce_status,rest_open_close =$rest_open_close,delivery_offer=$delivery_offer,rest_address= '$rest_address',rest_city= '$rest_city',rest_state= '$rest_state',rest_zip= '$rest_zip',delivery_radius='$delivery_radius',meta_keywords='$meta_keywords',meta_description='$meta_description' where id = $catid",1);
									dbAbstract::Update("UPDATE analytics SET name= '".addslashes($catname)."', optionl_logo='$name1' , first_letter = '".strtoupper(substr(addslashes($catname), 0, 1))."' where resturant_id = $catid",1);
	
								}								
	 ?>		
			<script language="javascript">
				window.location="./?mod=resturant&item=restedit";
			</script> 
		<? } //end else 
	
} //end submit2		
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ?>
 	<?
	if($_REQUEST[cid]) {
		$_SESSION[cid] = $_REQUEST[cid];
		}
	 ?>	
      <? include "includes/resturant_header.php" ?>
      
      
    <div id="main_heading">
		<span>Edit Restaurant</span>
	</div>
     <? if ($errMessage != "" ) { ?><div class="msg_done"><?=$errMessage?></div> <? }?> 
      <div id="AdminLeftConlum">
      <form action="" method="post" enctype="multipart/form-data" name="editcatform" id="editcatform" >
        <table width="100%" border="0" cellpadding="4" cellspacing="0">
          <tr align="left" valign="top">
            <td width="76">&nbsp;</td>
            <td width="1052"><strong>Restaurant Name</strong><br />
            <textarea name="catname" cols="35" id="catname" style="font-size:18px; font-family:Arial;"><?=stripslashes(stripcslashes($cat_info_Rs->name))?>
              </textarea></td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="76"></td>
            <td><strong>Email:</strong><br />
            <input name="email" type="text" size="40" value="<?=stripslashes(stripcslashes($cat_info_Rs->email))?>" id="email" /> </td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="76"></td>
            <td><strong>Phone:</strong><br />
            <input name="phone" type="text" size="40" value="<?=stripslashes(stripcslashes($cat_info_Rs->phone))?>" id="phone" /> </td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="76"></td>
            <td><strong>Fax:</strong><br />
            <input name="fax" type="text" size="40" value="<?=stripslashes(stripcslashes($cat_info_Rs->fax))?>" id="fax" /> </td>
          </tr>
          <tr align="left" valign="top">
            <td>&nbsp;</td>
            <td><strong>Optional Logo</strong><br> <font color="#666666"><!--(system will resize to 
                500x500)--></font>
                <input name="userfile" type="file" id="userfile">
                <input type="hidden" name="logo" value="<?=$cat_info_Rs->logo?>">
                <input type="hidden" name="thumb" value="<?=$cat_info_Rs->optionl_logo?>"></td>
                <input type="hidden" name="header_images" value="<?=$cat_info_Rs->header_image?>">
          </tr>
          <tr align="left" valign="top">
            <td>&nbsp;</td>
            <td><strong>Optional Logo Thumbnail</strong><br> <font color="#666666"><!--(system will 
                resize to 130x130)--></font> 
           <input name="userfile2" type="file" id="userfile2"></td>
          </tr>
           <tr align="left" valign="top">
            <td>&nbsp;</td>
            <td><strong>Header Image</strong><br> <font color="#666666"><!--(system will 
                resize to 130x130)--></font> 
           <input name="userfile3" type="file" id="userfile3"></td>
          </tr>
          <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Resturant Address:</strong><br />
            <input name="rest_address" type="text" size="40" id="rest_address" value="<?=$cat_info_Rs->rest_address?>">            
            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Resturant City:</strong><br />
            <input name="rest_city" type="text" size="40" id="rest_city" value="<?=$cat_info_Rs->rest_city?>">            
            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Resturant State:</strong><br />
            <input name="rest_state" type="text" size="40" id="rest_state" value="<?=$cat_info_Rs->rest_state?>">            
            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Resturant Zip Code:</strong><br />
            <input name="rest_zip" type="text" size="40" id="rest_zip" value="<?=$cat_info_Rs->rest_zip?>">            
            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Delivery Radius for Resturant:</strong><br />
            <input name="delivery_radius" type="text" size="40" id="delivery_radius" value="<?=$cat_info_Rs->delivery_radius?>">&nbsp;(miles)            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Order Minimum:</strong><br />
            <input name="order_minimum" type="text" size="40" id="order_minimum" value="<?=$cat_info_Rs->order_minimum?>">            </td>
          </tr>
           <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Sales tax % for Restaurant:</strong><br />
            <input name="tax_percent" type="text" size="40" id="tax_percent" value="<?=$cat_info_Rs->tax_percent?>">            </td>
          </tr>
           <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Delivery Charges:</strong><br />
            <input name="delivery_charges" type="text" size="40" id="delivery_charges" value="<?=$cat_info_Rs->delivery_charges?>">            </td>
          </tr> 
           <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Announcements:</strong><br />
            <input name="rest_announcements" type="text" size="40" id="rest_announcements" value="<?=$cat_info_Rs->announcement?>">            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td></td>  
            <td><strong>Time Zone:</strong><br />
            <select name="time_zone" id="time_zone" style="width:270px;">
                  <option value="-1">Select Time Zone</option>
             	  <?=get_timezone_drop_down($cat_info_Rs->time_zone_id) ; ?>
              </select>            
            </td>
          </tr>  
          <? if($_SESSION['admin_type'] == 'admin'){ ?>
           <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Payment Mathod:</strong><br />
             <input name="credit" type="checkbox" value="credit"  id="payment_method" <? if($cat_info_Rs->payment_method == "credit" || $cat_info_Rs->payment_method == "both" ){ echo "checked";} ?>>Credit Card            &nbsp;&nbsp;
            <input name="cash" type="checkbox" value="cash"  id="payment_method" <? if($cat_info_Rs->payment_method == "cash" || $cat_info_Rs->payment_method == "both" ){ echo "checked";} ?>>Cash
            </td>
          </tr>      	
          <? } ?>
           <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Announcement status:</strong><br />
            <input name="announce_status" type="radio" value="1"  id="announce_status" <? if($cat_info_Rs->announce_status == "1" ){ echo "checked";} ?>>Activate            &nbsp;&nbsp;
            <input name="announce_status" type="radio" value="0"  id="announce_status" <? if($cat_info_Rs->announce_status == "0" ){ echo "checked";} ?>>Deactivate
            </td>
          </tr>  
           <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Order Delivery Offer:</strong><br />
            <input name="delivery_offer" type="radio" value="1"  id="delivery_offer" <? if($cat_info_Rs->delivery_offer == "1" ){ echo "checked";} ?>>Yes            &nbsp;&nbsp;
            <input name="delivery_offer" type="radio" value="0"  id="delivery_offer" <? if($cat_info_Rs->delivery_offer == "0" ){ echo "checked";} ?>>No
            </td>
          </tr>  
           <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Resturant Status:</strong><br />
            <input name="rest_open_close" type="radio" value="1"  id="rest_open_close" <? if($cat_info_Rs->rest_open_close == "1" ){ echo "checked";} ?>>Open            &nbsp;&nbsp;
            <input name="rest_open_close" type="radio" value="0"  id="rest_open_close" <? if($cat_info_Rs->rest_open_close == "0" ){ echo "checked";} ?>>Close
            </td>
          </tr>  
          <? if($_SESSION['admin_type'] == 'admin'){ ?>
           <tr align="left" valign="top"> 
            <td width="76"></td>
            <td><strong>Voice Confirmation Email Address:</strong><br />
            <input name="voice_email" type="text" size="40" value="<?=stripslashes(stripcslashes($cat_info_Rs->voice_email))?>" id="voice_email" /> </td>
          </tr>
          <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Voice Confirmation Status:</strong><br />
            <input name="phone_notification_status" type="radio" value="1"  id="phone_notification_status" <? if($cat_info_Rs->phone_notification == "1" ){ echo "checked";} ?>>On            &nbsp;&nbsp;
            <input name="phone_notification_status" type="radio" value="0"  id="phone_notification_status" <? if($cat_info_Rs->phone_notification == "0" ){ echo "checked";} ?>>Off
            </td>
          </tr>  
          <tr align="left" valign="top"> 
            <td></td>
            <td><strong>Authorize.net</strong></td>
          </tr>
           <tr align="left" valign="top"> 
            <td>&nbsp;</td>
            <td>Login ID<br /><input name="authoriseLoginID" type="text" size="40" id="authoriseLoginID" value="<?=$cat_info_Rs->authoriseLoginID?>" /></td>
          </tr>
           <tr align="left" valign="top"> 
            <td>&nbsp;</td>
            <td>Transaction Key<br /><input name="transKey" type="text" size="40" id="transKey" value="<?=$cat_info_Rs->transKey?>" /></td>
          </tr>
          <? }?>
          <tr align="left" valign="top">
            <td width="76">&nbsp;</td>
            <td width="1052"><strong>META Keywords</strong><br />
            <textarea name="meta_keywords" cols="35" id="meta_keywords" style="font-size:18px; font-family:Arial;"><?=stripslashes(stripcslashes($cat_info_Rs->meta_keywords))?>
              </textarea></td>
          </tr>
          <tr align="left" valign="top">
            <td width="76">&nbsp;</td>
            <td width="1052"><strong>META Description</strong><br />
            <textarea name="meta_description" cols="35" id="meta_description" style="font-size:18px; font-family:Arial;"><?=stripslashes(stripcslashes($cat_info_Rs->meta_description))?>
              </textarea></td>
          </tr>
          <tr align="left" valign="top">
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="Save Changes" /></td>
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
      <div id="AdminRightConlum" style="padding:5px; min-height:800px"><iframe src="admin_contents/resturants/tab_resturant_businesshours.php?catid=<?=$catid?>" frameborder="0"  width="100%" scrolling="no" id="iframe1" onload="calcHeight('iframe1')"></iframe></div><br class="clearfloat" />
	
