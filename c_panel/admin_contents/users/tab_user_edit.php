<?php	

if ($_SESSION['admin_type'] != 'admin')
{
    $mAdminID = $_SESSION['owner_id'];
    if ($_SESSION['admin_type'] == 'reseller')
    {
        $mRow = dbAbstract::ExecuteObject("SELECT COUNT(*) As Cnt FROM reseller_client WHERE reseller_id=".$mAdminID." AND client_id=".$_REQUEST['userid'], 1);
        if ($mRow)
        {
            if ($mRow->Cnt<=0)
            {
                redirect($AdminSiteUrl.'?mod=resturant');
            }
        }
        else
        {
            redirect($AdminSiteUrl.'?mod=resturant');    
        }
    }
    else if ($_SESSION['admin_type'] == 'store owner')
    {
        redirect($AdminSiteUrl.'?mod=resturant');
    }
    else if ($_SESSION['admin_type'] == 'bh')
    {
        redirect($AdminSiteUrl.'?mod=resturant');
    }
}
	$country_obj = new Country();
        $errMessage='';
	//////////////////////////////////////////////////////////////////
	$resellerId  = 	$_REQUEST['resellerId'];
	$user_id	=	$_REQUEST['userid'];
	$user_qry	=	dbAbstract::Execute("select * from users where id = $user_id",1);
	$user_qryRs	=	dbAbstract::returnObject($user_qry,1);
	
				   
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
				
				$user_qry1	=	dbAbstract::Execute("select * from users where username = '$user_name' AND id != $user_id",1);
				
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
				} else if(dbAbstract::returnRowsCount($user_qry1,1) > 0) {
					 $errMessage = "User name already exists. Please select another.";
				} else if($country < 0) {
					 $errMessage = "Please select country";
				} else if($state == '') {
					 $errMessage = "Please enter state/province";
				} else if($city == '') {
					 $errMessage = "Please enter city";
				} else if($zip == '') {
					 $errMessage = "Please enter zip/postal";
				} else {
                                        if (trim($password)!="")
                                        {
                                            $mSalt = hash('sha256', mt_rand(10,1000000));    
                                            $ePassword = hash('sha256', $password.$mSalt);
                                            $sql = "UPDATE users SET firstname= '".dbAbstract::returnRealEscapedString(stripslashes($first_name))."', lastname= '".dbAbstract::returnRealEscapedString(stripslashes($last_name))."', email= '".dbAbstract::returnRealEscapedString(stripslashes($email))."',username='".dbAbstract::returnRealEscapedString(stripslashes($user_name))."',password= '$password', epassword='".$ePassword."', salt='".$mSalt."', country= '".dbAbstract::returnRealEscapedString(stripslashes($country))."', state= '".dbAbstract::returnRealEscapedString(stripslashes($state))."', city= '".dbAbstract::returnRealEscapedString(stripslashes($city))."', zip= '$zip',address='".dbAbstract::returnRealEscapedString(stripslashes($address))."' WHERE id=$user_id";
                                        }
                                        else
                                        {
                                            $sql = "UPDATE users SET firstname= '".dbAbstract::returnRealEscapedString(stripslashes($first_name))."', lastname= '".dbAbstract::returnRealEscapedString(stripslashes($last_name))."', email= '".dbAbstract::returnRealEscapedString(stripslashes($email))."',username='".dbAbstract::returnRealEscapedString(stripslashes($user_name))."', country= '".dbAbstract::returnRealEscapedString(stripslashes($country))."', state= '".dbAbstract::returnRealEscapedString(stripslashes($state))."', city= '".dbAbstract::returnRealEscapedString(stripslashes($city))."', zip= '$zip',address='".dbAbstract::returnRealEscapedString(stripslashes($address))."' WHERE id=$user_id";
                                        }
				 dbAbstract::Update($sql, 1);
				 
				 $reseller_client_sql = "UPDATE reseller_client SET reseller_id = '".$reseller."' WHERE  client_id = '".$user_id."' ";
				 if(!empty($user_qryRs->chargify_customer_id))
                                 {
                                    $chargify_customer_id = $chargify->updateCustomer($user_qryRs->chargify_customer_id,$first_name,$last_name,$email,$company_name,$city,$state,$zip,$country,$address,$phone);
                                 }
				 if($result = dbAbstract::Update($reseller_client_sql,1))  { ?>
					<script language="javascript">
						window.location="./?mod=clients&resellerId=<?=$resellerId?>";
					</script> 
				 
                 <? } 
				}
				?>
                
              
		
		<?	}// end submit ?>
   <script language="javascript">     
    function DeleteUserProfile(uid){
	abc= confirm('You Are About To Completely Delete Your Account Which Will Remove All Orders, Against Your Account. Are You Sure? YES/NO');
	alert(abc);
	if(abc == 'true'){
//		window.location="?mod=<?=$mod?>&item=deleteuser&userid="+uid;
	};
	
}
</script>
        
<div id="main_heading">EDIT CLIENT INFO</div>
<? if ($errMessage != "" ) { ?><div class="msg_done"><?=$errMessage?></div>
<? }?>
<div class="form_outer">
    <form action="" method="post" name="form1">
    <table width="750" border="0"  cellpadding="4" cellspacing="0">
      <tr align="left" valign="top">
        <td><strong>First Name:</strong></td>
        <td>
        <input name="first_name" id="first_name" type="text" value="<?=$user_qryRs->firstname?>" size="40" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Last Name:</strong></td>
        <td><input name="last_name" type="text" value="<?=$user_qryRs->lastname?>" size="40" id="last_name" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Email:</strong></td>
        <td width="574"><input name="email" id="email" type="text" size="40"  value="<?=$user_qryRs->email?>"  /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>User Name:</strong></td>
        <td width="574"><input name="user_name" type="text" size="40" value="<?=$user_qryRs->username?>" id="user_name" /></td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Password: </strong></td>
        <td><input name="password" type="password" size="40" id="password" /></td>
      </tr>
      
      <tr align="left" valign="top">
        <td width="160"><strong>Reseller:</strong></td>
		<td width="574">
        	<?php
			$reseller_sql = "SELECT reseller_id from reseller_client WHERE client_id = '".$user_id."'";
			$reseller_qry = dbAbstract::Execute( $reseller_sql,1 );
			$reseller_rs = dbAbstract::returnArray( $reseller_qry,1);			
			?>
            <select name="reseller" id="reseller" style="width:270px;" >
            <option value="-1">Select Reseller</option>
            <?=resellers_drop_down(@$reseller_rs['reseller_id']) ?>
          </select>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Country:</strong></td>
		<td width="574">
        	<select name="country" id="country" style="width:270px;" >
              <option value="-1">Select Country</option>
               <?=$country_obj->getCountyDropDownList($user_qryRs->country) ?>
            </select>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160">address:</td>
        <td><input name="address" type="text" size="40" value="<?=$user_qryRs->address?>" id="address"/>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>State/Province:</strong></td>
        <td width="574"><input name="state" type="text" size="40" value="<?=$user_qryRs->state?>" id="state" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>City:</strong></td>
        <td width="574"><input name="city" type="text" size="40" value="<?=$user_qryRs->city?>" id="city" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Zip/Postal:</strong></td>
        <td width="574"><input name="zip" type="text" size="40" value="<?=$user_qryRs->zip?>" id="zip" /></td>
      </tr>
      <tr align="left" valign="top">
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="Edit user Information" />
        <input name="userid" type="hidden" value="<?=$user_id?>" />
        </td>
      </tr>
    </table>
    </form>
</div>
