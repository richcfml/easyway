<?
include("admin_contents/FCKeditor_new/fckeditor/fckeditor.php");
	$type	=	$_REQUEST['t'];
	if($type == ''){	$type = 1;	}
	if($type == 1){ $title = 'EDIT FAQ';}
	else if($type == 2){ $title = 'Edit About Us';}
	else if($type == 3){ $title = 'Edit Contact Us';}
	else if($type == 4){ $title = 'Edit Privacy Policy';}
	else if($type == 5){ $title = 'Edit Delivery Info  ';}
	else if($type == 6){ $title = 'Edit Corporate & Catering';}
	else if($type == 7){ $title = 'Edit Customer Care';}
	
	if(isset($_REQUEST['submit2']))
			{
					$contents	=	addslashes($_REQUEST['edit']);
					mysql_query("UPDATE bottom_contents SET content_text = '$contents' where content_text_type = $type");
			}
		
	$contents_qry		=	mysql_query("select * from bottom_contents where content_text_type = $type");
	$contents_infoRs	=	mysql_fetch_object($contents_qry);	
	
?>
<h1><?=$title?></h1>

<form action="" method="post" name="form1">
<table width="600" border="0" align="center">
  <tr>
 
    <td>&nbsp;</td>
  </tr>
  <tr>

    <td>
     <?php
						
					$oFCKeditor =  new FCKeditor('edit') ;

					$oFCKeditor->BasePath	=  "admin_contents/FCKeditor_new/fckeditor/" ; //$sBasePath ;

					

					$oFCKeditor->Value		= stripslashes($contents_infoRs->content_text);

					$oFCKeditor->Height		= '300';
					$oFCKeditor->Width		= '720';
					
					//$oFCKeditor->ToolbarSet		= 'basic';

					//ToolbarSet	= 'basic' ;

					$oFCKeditor->Create()  ;

					?>
    
    
    </td>
  </tr>
   <tr>
   
    <td><input type="submit" name="submit2" value="Save Changes">
    <input name="t" type="hidden" value="<?=$type?>" />
    </td>
  </tr>
</table>
</form>
