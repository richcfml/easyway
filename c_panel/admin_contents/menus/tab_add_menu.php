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
					 if ($menu_name == '') {
						$errMessage1="Please Enter Menu Name";
					 }else if ($menu_ordering == '') {
						 $errMessage1="Please Enter Menu ordering no.";
					}else{
                                                        Log::write("Adding new menu", "QUERY -- INSERT INTO menus SET rest_id= $restid, menu_name= '".addslashes($menu_name)."', menu_ordering= $menu_ordering, menu_desc= '".addslashes($menu_desc)."'", 'menu', 1 , 'cpanel');
							mysql_query("INSERT INTO menus SET rest_id= $restid, menu_name= '".addslashes($menu_name)."', menu_ordering= $menu_ordering, menu_desc= '".addslashes($menu_desc)."'");
							?>
       			<script language="javascript">
					window.location="?mod=menus&catid=<?=$restid?>";
				</script>
		<?
					
												
					}
		
				} // end submit2
?>
<div id="main_heading">Add Menu</div>
  <div class="form_outer">
  <form name="supcatform" method="post" action="">
  <table width="500" border="0" cellpadding="4" cellspacing="0">
   <?=($errMessage1 != '')?"<div class=\"msg_error\">$errMessage1</div>":"";?><tr>
  <td><strong>Menu Name:</strong><br />
  
    <input name="menu_name" type="text" id="menu_name" size="50"></td>
  </tr>
  <tr>
  <td><strong>Sub Menu Ordering #:</strong><br />
    <input name="menu_ordering" type="text" id="menu_ordering" size="50"></td>
  </tr>
  <tr align="left" valign="top">
  <td><strong>Menu Description:</strong><br>
                <em><font color="#666666">(To insert a new paragraph, enter &lt;P&gt;. 
                To bold text, surround text with &lt;B&gt; and &lt;/B&gt;. To italicize text, surround text with &lt;I&gt; 
                and &lt;/I&gt;.)</font></em><br />
  <textarea name="menu_desc" cols="40" rows="6" id="menu_desc"></textarea></td>
  </tr>
  <tr>
  <td><input type="submit" name="submit2" value="Add Menu" />
  <input type="hidden" name="catid" id="catid"  value="<?=$catid?>"/></td>
  </tr>
  </table>
  </form>
</div>
