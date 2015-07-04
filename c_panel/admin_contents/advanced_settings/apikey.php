<?php
	$mAPIKey = "";
	$mUserID = $_SESSION['owner_id'];
	$mResult = mysql_query("SELECT IFNULL(ewo_api_key, '0') AS ewo_api_key FROM users WHERE id=".$mUserID);
	if (mysql_num_rows($mResult)>0)
	{
		$mRow = mysql_fetch_object($mResult);
		$mAPIKey = $mRow->ewo_api_key;
		if (($mAPIKey=="") || ($mAPIKey=="0"))
		{
			$mAPIKey = getUniqueAPIKey();
			mysql_query("UPDATE users SET ewo_api_key='".$mAPIKey."' WHERE id=".$mUserID);
		}
	}
	else
	{
		header("location:./?mod=resturant");
	}

	function getUniqueAPIKey()
	{
		$mAPIKey = substr(str_shuffle(str_repeat("23456789abcdefghijkmnpqrstuvwxyz", 10)), 0, 10);
		$mResult = mysql_query("SELECT COUNT(*) AS KeyCount FROM users WHERE ewo_api_key='".$mAPIKey."'");
		if (mysql_num_rows($mResult)>0)
		{
			$mRow = mysql_fetch_object($mResult);
			$mKeyCount = $mRow->KeyCount;
			if ($mKeyCount>0)
			{
				getUniqueAPIKey();
			}
			else
			{
				return $mAPIKey;
			}
		}
		else
		{
			header("location:./?mod=resturant");
		}
	}
	
?>
<div style="margin: 50px;">
	<span style="font-family: Arial, Helvetica, sans-serif; color: Maroon; font-weight: bold; font-size: 20px;">
		Your API Key is
	</span>
	<br />
	<span style="font-size: 16px;">
	<?=$mAPIKey?>
	</span>
</div>
<?php

if(isset( $_REQUEST['id'])) {
	$sso_id = $_REQUEST['id'];
	 if ($_REQUEST['action'] == 'activate'){
		mysql_query("UPDATE bh_sso_accounts SET  status='1' WHERE id= $sso_id");
	}
        else if ($_REQUEST['action'] == 'deactivate'){
		mysql_query("UPDATE bh_sso_accounts SET  status='0' WHERE id=$sso_id");
	}
}
        
        if($_SESSION['admin_type']=="admin"){
            $ssoQry	=	mysql_query("select * from bh_sso_accounts");
        }elseif($_SESSION['admin_type']=="reseller"){
            $ssoQry	=	mysql_query("select * from bh_sso_accounts where reseller_id = ".$_SESSION['owner_id']."");
        }elseif($_SESSION['admin_type']=="store owner"){
            $ssoQry	=	mysql_query("select * from bh_sso_accounts where owner_id = ".$_SESSION['owner_id']."");
        }
        
        $ssoRows	=	mysql_num_rows($ssoQry);
?>
    <div id="main_heading">
    <span>SSO Account List</span>
    </div>
      <table class="listig_table" width="100%" border="0" align="center" cellspacing="1">
        <tr bgcolor="#729338">
          <th width="150"><strong>SSO Account Name</strong></th>
          <th width="100"><strong>SSO api Key</strong></th>
          <th width="100"><strong>Create By</strong></th>
          <th width="320"><strong>Action</strong></th>
        </tr>
        <?php while($ssoRs	=	mysql_fetch_object($ssoQry)){ ?>
       <?php  if( $counter++ % 2 == 0)  
                            $bgcolor = '#F8F8F8';
               else $bgcolor = '';
       ?>
        <tr bgcolor="<?=$bgcolor ?>" >
          <td><?=$ssoRs->name?></td>
          <td><?=$ssoRs->api_key?></td>
          <td><?php if($ssoRs->created_by==1){echo "Admin";}else if($ssoRs->created_by==2){echo "Reseller";}else{echo "Restaurant Owner";} ?></td>
          <td>
              <?php if($ssoRs->status == 0) { ?>
                  <a href="./?mod=<?=$mod?>&action=activate&id=<?=$ssoRs->id?>" onclick="return confirm('Are you sure you want to change the status of this SSO Account')" style="text-decoration:underline;">Activate</a> 
              <?php } 
                    if($ssoRs->status == 1) { 
               ?>
              <a href="./?mod=<?=$mod?>&action=deactivate&id=<?=$ssoRs->id?>" onclick="return confirm('Are you sure you want to change the status of this SSO Account')" style="text-decoration:underline;">Deactivate</a>
              <?php } ?>
          </td>
        </tr>
        <?php }?>
        <tr>
          <td colspan="9">&nbsp;</td>
        </tr>
      </table> 