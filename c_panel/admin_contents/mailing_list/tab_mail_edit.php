<?php
	$mailid		=	$_REQUEST['mailid'];
	$mailQry	=	dbAbstract::Execute("select * from mailing_list where id = $mailid", 1);
	$mailRs		=	dbAbstract::returnObject($mailQry, 1);
				   
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
				
				dbAbstract::Update("UPDATE mailing_list SET email= '$email' WHERE id=$mailid", 1);
				?>
                 <script language="javascript">
						window.location="./?mod=mailing_list&cid=<?=$mRestaurantIDCP?>";
				</script>
		
		<?	}// end submit ?>
        
<div id="main_heading">EDIT Mail</div>
   <div class="form_outer">
    <form action="" method="post" name="form1">
    <table width="750" border="0" cellpadding="4" cellspacing="0">
      <tr align="left" valign="top">
        <td width="200"><strong>Email:</strong></td>
        <td><input name="email" type="text" value="<?=$mailRs->email?>" size="40" />
        </td>
      </tr>
      <tr align="left" valign="top">
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="Edit Mail" />
        <input name="mailid" type="hidden" value="<?=$mailid?>" />
        </td>
      </tr>
    </table>
    </form>
   </div>
 

