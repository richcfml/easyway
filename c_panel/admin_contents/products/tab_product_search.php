<?	
	$catid 		= $_GET['catid'];
	$message1 	= '';
	$message2 	= '';
	
	if (isset($_REQUEST['submit1']))
			{
				$sub_cat			=	$_REQUEST['sub_cat'];
				$search_titleQry	=	mysql_query("select * from product where sub_cat_id = $sub_cat");
				$search_titlerows	= 	mysql_num_rows($search_titleQry);
				
				if($search_titlerows == 0){$message1 = "No Matches Were Found. Please Try Another Selection.";}	
				else { $message1 = "We Found The Following Matches - Please Select One To Edit:";}
			}
			
	if (isset($_REQUEST['submit2']))
			{
				$searched_code		=	$_REQUEST['item_code'];
				$search_codeQry		=	mysql_query("select * from product where item_code = $searched_code");
				$search_coderows	= 	mysql_num_rows($search_codeQry);
				
				if($search_coderows == 0){$message2 = "No Matches Were Found. Please Try Another Selection.";}	
				else { $message2 = "We Found The Following Matches - Please Select One To Edit:";}	
			}

?>
<h1>EDIT/REMOVE MENU ITEMS</h1> 
<br>

      <table width="500" border="0" align="center" cellpadding="4" cellspacing="0">
        <tr> 
          <td><form name="form1" method="post" action="">
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <?
			$subcat_qry	=	mysql_query("select * from categories where parent_id = $catid");
		?>
                <tr>
  <td>Select Menu</td>
  <td><select name="sub_cat" id="sub_cat" >
			<option value="-2" >Choose Menu </option>
              	<? while($subcat_qryRs = mysql_fetch_object($subcat_qry)){?>
<option value="<?=$subcat_qryRs->cat_id?>" <? if($subcat_qryRs->cat_id==$sub_cat){?>selected="selected"<? }?>><?=$subcat_qryRs->cat_name?></option>

	<? }?>
              
            </select></td>
</tr>
                <tr> 
                  <td height="30">&nbsp;</td>
                  <td height="30" align="left" valign="bottom"> <input type="submit" name="submit1" value="Find Item" > 
                   <input name="catid" type="hidden" value="<?=$catid?>" />
                  </td>
                </tr>
                  <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3"><?=$message1?></strong></td>
                </tr>
            
                <tr align="center" class="linktextbig"> 
                  <td colspan="2"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td width="50%" align="left" > 
                          <? 
									  if($search_titlerows > 0){ 
										$i=0;
										while ($i < $search_titlerows/2) {
											$prd_id		= mysql_result($search_titleQry,$i,"prd_id");
											$item_title = mysql_result($search_titleQry,$i,"item_title");
										
							         ?>
                          - <a href="?subitem=editproduct2&amp;pid=<?=$prd_id?>&catid=<?=$catid?>&scid=<?=$sub_cat?>" class="linktextbig"><?=stripslashes(stripcslashes($item_title))?></a> <br> 
                          <? 
								   $i++;
								     } // end while
									 
									 ?>
                        </td>
                        <td width="50%" align="left" valign="top" > 
                          <? 
								      
							 while ($i < $search_titlerows) {
											$prd_id		= mysql_result($search_titleQry,$i,"prd_id");
											$item_title = mysql_result($search_titleQry,$i,"item_title");
							 ?>
                          - <a href="?subitem=editproduct2&amp;pid=<?=$prd_id?>&catid=<?=$catid?>&scid=<?=$sub_cat?>" class="linktextbig"><?=stripslashes(stripcslashes($item_title))?></a> <br> 
                          <? 
							   $i++;
							   
							   }
							    } // enf if
					   ?>
                        </td>
                      </tr>
					  
                    </table></td>
                </tr>
              </table>
            </form></td>
			
        </tr>
        <tr> 
          <td height="20" align="left" ><HR width="100%" noShade SIZE=1></td>
        </tr>
        <tr align="left" valign="top"> 
          <td><form action="" method="post" name="form2" id="form2">
                <table width="100%" border="0" align="center" cellspacing="0">
                  <tr> 
                    <td colspan="2" align="left" class="bodytext"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      
                        <tr> 
                          
                        <td width="30%" class="bodytext">Enter Item ID/Code</td>
                          <td width="70%"><input name="item_code" type="text" id="item_code"></td>
                        </tr>
                     
                        <tr> 
                          <td>&nbsp;</td>
                          <td height="30" valign="bottom"> <input type="submit" name="submit2" value="Find Item"> 
                          <input name="catid" type="hidden" value="<?=$catid?>" />
                           
                          </td>
                        </tr>
                      </table></td>
                  </tr>
                    <tr align="left"> 
                    <td height="30" colspan="2" class="darkgreytext"><strong class="style3"><?=$message2?></strong></td>
                  </tr>
                    
                  <tr> 
                        <td width="50%" align="left" > 
                          <? 
									  if($search_coderows > 0){ 
										$j=0;
										while ($j < $search_coderows/2) {
											$prd_id		= mysql_result($search_codeQry,$j,"prd_id");
											$item_title = mysql_result($search_codeQry,$j,"item_title");
										
							         ?>
                          - <a href="?subitem=editproduct2&amp;pid=<?=$prd_id?>&catid=<?=$catid?>&scid=<?=$sub_cat?>" class="linktextbig"><?=stripslashes(stripcslashes($item_title))?></a> <br> 
                          <? 
								   $j++;
								     } // end while
									 
									 ?>
                        </td>
                        <td width="50%" align="left" valign="top" > 
                          <? 
								      
							 while ($j < $search_coderows) {
											$prd_id		= mysql_result($search_codeQry,$j,"prd_id");
											$item_title = mysql_result($search_codeQry,$j,"item_title");
							 ?>
                          - <a href="?subitem=editproduct2&amp;pid=<?=$prd_id?>&catid=<?=$catid?>&scid=<?=$sub_cat?>" class="linktextbig"><?=stripslashes(stripcslashes($item_title))?></a> <br> 
                          <? 
							   $j++;
							   
							   }
							    } // enf if
					   ?>
                        </td>
                      </tr>
                </table>
              </form></td>
        </tr>
      </table>