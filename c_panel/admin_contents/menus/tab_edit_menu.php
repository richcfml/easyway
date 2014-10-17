<? 
		if (isset($_REQUEST['menuActivateId'])){
			$id = $_REQUEST['menuActivateId'];
                        Log::write("Update menu status", "QUERY -- "."UPDATE menus SET  status='1' WHERE id=$id", 'menu', 1 , 'cpanel');
			mysql_query("UPDATE menus SET  status='1' WHERE id=$id");
			echo "<script language=\"JavaScript\">window.location.href=\"?mod=menus\";</script>";
			
		} else if (isset($_REQUEST['menuDeactivateId'])){
			$id = $_REQUEST['menuDeactivateId'];
                        Log::write("Update menu status", "QUERY -- UPDATE menus SET  status='0' WHERE id=$id", 'menu', 1 , 'cpanel');
			mysql_query("UPDATE menus SET  status='0' WHERE id=$id");
			echo "<script language=\"JavaScript\">window.location.href=\"?mod=menus\";</script>";
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
				   
		if (isset($_REQUEST['submit2']))
				{
					$errMessage1 ="";
					if ($menu_name == '') {
								 $errMessage1="Please Enter Menu Name";
					}else if ($menu_ordering == '') {
								 $errMessage1="Please Enter Menu Ordering Number";
					}else{
                                                        Log::write("Update menu status", "QUERY -- UPDATE menus SET  menu_name= '".addslashes($menu_name)."',  menu_ordering= $menu_ordering, menu_desc= '".addslashes($menu_desc)."' WHERE id= $menuid", 'menu', 1 , 'cpanel');
							mysql_query("UPDATE menus SET  menu_name= '".addslashes($menu_name)."',  menu_ordering= $menu_ordering, menu_desc= '".addslashes($menu_desc)."' WHERE id= $menuid");
		?>
       			<script language="javascript">
					window.location="?mod=menus&item=menu&cid=<?=$mRestaurantIDCP?>";
				</script>
		<?
					}
		
				} // end submit2
?>
<div id="main_heading">Edit Menu</div>
 <div id="AdminLeftConlum">
  <form name="supcatform" method="post" action="" style="display:<? echo $dis?>">
  <table width="500" border="0"  cellpadding="4" cellspacing="0">
  <?=($errMessage1 != '')?"<div class=\"msg_error\">$errMessage1</div>":"";?>    
  
   <?
          $menuQry	=	mysql_query("select * from menus where id = $menuid");
          $menuRs	=	mysql_fetch_object($menuQry); 
  ?>
  <tr>
  <td><strong>Menu Name:</strong><br />
    <input name="menu_name" type="text" id="menu_name" size="40" value="<?=stripslashes(stripcslashes($menuRs->menu_name))?>">  
  </td>
  </tr>
  <tr>
  <td><strong>Menu Ordering #:</strong><br />
  
    <input name="menu_ordering" type="text" id="menu_ordering" size="40" value="<?=stripslashes(stripcslashes($menuRs->menu_ordering))?>"> </td>
  </tr>
  <tr align="left" valign="top">
  <td><strong> Menu Description:</strong><br>
                <em><font color="#666666">(To insert a new paragraph, enter &lt;P&gt;. 
                To bold text, surround text with &lt;B&gt; and &lt;/B&gt;. To italicize text, surround text with &lt;I&gt; 
                and &lt;/I&gt;.)</font></em><br />
  <textarea name="menu_desc" cols="40" rows="6" id="menu_desc"><?=stripslashes(stripcslashes($menuRs->menu_desc))?>
                </textarea></td>
  </tr>
  <tr>
  <td><input type="submit" name="submit2" value="Save Changes">
    <input type="hidden" name="menuid" id="menuid"  value="<?=$menuid?>"/></td>
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
   <div id="AdminRightConlum" style="padding:5px; min-height:800px"><iframe src="ajax.php?mod=menus&item=hours&menuid=<?=$menuid?>" frameborder="0"  width="100%" scrolling="no" id="iframe1" onload="calcHeight('iframe1')"></iframe></div><br class="clearfloat" />
