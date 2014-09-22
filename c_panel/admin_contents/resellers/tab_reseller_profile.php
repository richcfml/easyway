<?	$reseller_id	=	(isset($_REQUEST['reseller_id']) ?$_REQUEST['reseller_id'] :$_SESSION['owner_id']); 
	$reseller_qry	=	mysql_query("select * from users where id = $reseller_id");
	$reseller_qryRs	=	mysql_fetch_object($reseller_qry);		   
	 ?>
   <script language="javascript">     
	/*function DeleteUserProfile(uid){
		abc= confirm('You Are About To Completely Delete Your Account Which Will Remove All Orders, Against Your Account. Are You Sure? YES/NO');
		alert(abc);
		if(abc == 'true'){
			window.location="?mod=<?=$mod?>&item=deleteuser&userid="+uid;
		};
	}*/
</script>
 
<div id="main_heading">Reseller Profile</div>
<div class="Table_Outer_Div">
<div class="form_outer" style="float:left; width:445px;">
    <table width="100%" border="0"  cellpadding="4" cellspacing="0">
      <tr align="left" valign="top">
        <td class="Width"><strong>First Name:</strong></td>
        <td><?=$reseller_qryRs->firstname?>
            <div style=" float:right; margin-left:30px"><a href="?mod=resellers&item=edit<?= $_SESSION['admin_type'] == 'admin' ? "&reseller_id=".$reseller_id:""?>">Edit Profile</a></div>
        </td>
      </tr>
      <tr align="left" valign="top">
        <td><strong>Last Name:</strong></td>
        <td><?=$reseller_qryRs->lastname?></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Email:</strong></td>
        <td width="400"><?=$reseller_qryRs->email?></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Phone:</strong></td>
        <td width="400"><?=$reseller_qryRs->phone?></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>User Name:</strong></td>
        <td width="400"><?=$reseller_qryRs->username?></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Country:</strong></td>
		<td width="400"><?=$reseller_qryRs->country?></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>State:</strong></td>
        <td width="400"><?=$reseller_qryRs->state?></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>City:</strong></td>
        <td width="400"><?=$reseller_qryRs->city?></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Zip:</strong></td>
        <td width="400"><?=$reseller_qryRs->zip?></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Company Name:</strong></td>
        <td width="400"><?=$reseller_qryRs->company_name?></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Company Logo:</strong></td>
        <td width="400"><img style="width:100px; height:40px; margin-top:5px" src="../images/logos_thumbnail/<?=$reseller_qryRs->company_logo ?>" border="0" /></td>
      </tr>
      <tr align="left" valign="top">
        <td width="160"><strong>Company Logo Link:</strong></td>
        <td width="400"><?=$reseller_qryRs->company_logo_link?></td>
      </tr>
      <? if($_SESSION['admin_type'] == 'admin')  {?>
      <tr align="left" valign="top">
        <td width="160"><strong>Authorise.Net Access:</strong></td>
        <td width="400">
		<? if( $reseller_qryRs->aDotNet_access == 0  )
			 echo "NO";
		    else if( $reseller_qryRs->aDotNet_access == 1 )
			 echo "Yes";
		?>
        </td>
      </tr>
      <? }?>
    </table>
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
	 <iframe src="admin_contents/resellers/tab_license_listing.php<?= $_SESSION['admin_type'] == 'admin' ? "?reseller_id=".$reseller_id:""?>" style="border:0px" width="100%" scrolling="no" id="iframe1" onload="calcHeight('iframe1')"></iframe>
 
</div>
<div style="clear:both"></div>
</div>