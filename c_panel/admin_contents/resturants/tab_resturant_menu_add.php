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
					 }else if ($cat_ordering == '') {
						 $errMessage1="Please Enter Menu ordering no.";
					}else{
                                                        Log::write("Add new category - resturant/tab_resturant_menu_add.php", "QUERY --INSERT INTO categories SET parent_id= $catid, cat_name= '".addslashes($subcat_name)."', cat_ordering= $cat_ordering, cat_des= '".addslashes($subcat_des)."'", 'menu', 1 , 'cpanel');
							mysql_query("INSERT INTO categories SET parent_id= $catid, cat_name= '".addslashes($subcat_name)."', cat_ordering= $cat_ordering, cat_des= '".addslashes($subcat_des)."'");
							
							?>
       			<script language="javascript">
					window.location="?subitem=menu&catid=<?=$catid?>";
				</script>
		<?
					
												
					}
		
				} // end submit2
?>
<form name="supcatform" method="post" action="">
<table width="500" border="0" align="center" cellpadding="4" cellspacing="0">
 <?=($errMessage1 != '')?"<div class=\"msg_error\">$errMessage1</div>":"";?><tr>
<td><strong>Menu Name:</strong><br />

  <input name="subcat_name" type="text" id="subcat_name" size="50"></td>
</tr>
<tr>
<td><strong>Menu Ordering #:</strong><br />
  <input name="cat_ordering" type="text" id="cat_ordering" size="50"></td>
</tr>
<tr align="left" valign="top">
<td><strong>Menu Description:</strong><br>
              <em><font color="#666666">(To insert a new paragraph, enter &lt;P&gt;. 
              To bold text, surround text with &lt;B&gt; and &lt;/B&gt;. To italicize text, surround text with &lt;I&gt; 
              and &lt;/I&gt;.)</font></em><br />
<textarea name="subcat_des" cols="40" rows="6" id="subcat_des"></textarea></td>
</tr>
<tr>
<td><input type="submit" name="submit2" value="Add Menu" />
<input type="hidden" name="catid" id="catid"  value="<?=$catid?>"/></td>
</tr>
</table>
</form>
