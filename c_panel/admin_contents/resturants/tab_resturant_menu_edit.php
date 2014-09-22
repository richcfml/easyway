<?
		//$catid	=	$_REQUEST['catid'];
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
				   
		if (isset($_REQUEST['submit2']))
				{
					$errMessage1 ="";
					 if ($subcat_name == '') {
								 $errMessage1="Please Enter Menu Name";
					}else{
                                                        Log::write("Update category - restaurant/tab_resturant_menus_edit.php", "QUERY --UPDATE categories SET  cat_name= '".addslashes($subcat_name)."', cat_ordering= $cat_ordering, cat_des= '".addslashes($subcat_des)."' WHERE cat_id= $sub_cat", 'menu', 1 , 'cpanel');
							mysql_query("UPDATE categories SET  cat_name= '".addslashes($subcat_name)."', cat_ordering= $cat_ordering, cat_des= '".addslashes($subcat_des)."' WHERE cat_id= $sub_cat");
		?>
       			<script language="javascript">
					window.location="?subitem=menu&catid=<?=$catid?>";
				</script>
		<?
					}
		
				} // end submit2
?>

<? 
	  $dis1 = "none";
	  if ($sub_cat <= 0) {
	  	$dis1 = "";
	  }
	  ?>
<form name="catnameform" method="post" action="" style="display:<? echo $dis1?>">
        <table width="500" border="0" align="center" cellpadding="4" cellspacing="0">
     
          <?
			$subcat_qry	=	mysql_query("select * from categories where parent_id = $catid");
		?>
			
          <tr>
  <td>Select Menu</td>
  <td><select name="sub_cat" id="sub_cat" >
			<option value="-2" >Choose Menu</option>
              	<? while($subcat_qryRs = mysql_fetch_object($subcat_qry)){?>
<option value="<?=$subcat_qryRs->cat_id?>" <? if($subcat_qryRs->cat_id==$sub_cat){?>selected="selected"<? }?>><?=$subcat_qryRs->cat_name?></option>

	<? }?>
              
            </select></td>
</tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit1" value="Edit This Menu" >            </td>
          </tr>
        </table>
      </form>
      <? 
	  $dis = "none";
	  if ($sub_cat > 0) {
	  	$dis = "";
	  }
	  ?>
	   <form name="supcatform" method="post" action="" style="display:<? echo $dis?>">
<table width="500" border="0" align="center" cellpadding="4" cellspacing="0">
<?=($errMessage1 != '')?"<div class=\"msg_error\">$errMessage1</div>":"";?>    

 <?
		$subcat_qry	=	mysql_query("select * from categories where cat_id = $sub_cat");
		$subcat_Rs	=	mysql_fetch_object($subcat_qry);
?>
<tr>
<td><strong>Menu Name:</strong><br />

  <input name="subcat_name" type="text" id="subcat_name" size="40" value="<?=stripslashes(stripcslashes($subcat_Rs->cat_name))?>">  <a href="delete_subcat.php?cid=<?=$catid?>&scid=<?=$sub_cat?>" onClick="return confirm('Are you sure you would like to delete this Menu?')">DELETE</a></td>
</tr>
<tr>
<td><strong>Menu Ordering #:</strong><br />

  <input name="cat_ordering" type="text" id="cat_ordering" size="40" value="<?=stripslashes(stripcslashes($subcat_Rs->cat_ordering))?>"> </td>
</tr>
<tr align="left" valign="top">
<td><strong>Menu Description:</strong><br>
              <em><font color="#666666">(To insert a new paragraph, enter &lt;P&gt;. 
              To bold text, surround text with &lt;B&gt; and &lt;/B&gt;. To italicize text, surround text with &lt;I&gt; 
              and &lt;/I&gt;.)</font></em><br />
<textarea name="subcat_des" cols="40" rows="6" id="subcat_des"><?=stripslashes(stripcslashes($subcat_Rs->cat_des))?>
              </textarea></td>
</tr>
<tr>
<td><input type="submit" name="submit2" value="Save Changes">
  <input type="hidden" name="sub_cat" id="sub_cat"  value="<?=$sub_cat?>"/></td>
</tr>
</table>
</form>
