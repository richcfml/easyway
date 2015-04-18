<?php
	$prdid				= @$_REQUEST['pid'];
	$name				= $_REQUEST['name'];
	$catid 				= $_GET['catid'];
	
	
	$prdandsubcatQry	= mysql_query("select p.item_title,c.cat_name,c.cat_id,sub_cat_id from product p,categories c where p.sub_cat_id=c.cat_id and p.prd_id=$prdid");
	
	$prdandsubcatRes 	= mysql_fetch_array($prdandsubcatQry);
	//$catid 				= $prdandsubcatRes['cat_id'];
	$sub_cat_id 		= $prdandsubcatRes['sub_cat_id'];
	$editattQry 		= mysql_query("select id,Type from attribute where option_name='".$name."' and ProductID=$prdid");
	$editattRes 		= mysql_fetch_array($editattQry);
	$attribute 			= mysql_query("select * from attribute where option_name='".$name."' and ProductID=$prdid order by id");
    $show 				= '';
	$Checked_requied	= "";
	
	while($attrs = mysql_fetch_array($attribute)) 
			{
				if ($attrs['Price']!=0){	$p = "=".$attrs['Price'];	} else {	$p = "";	}
				if ($attrs['rest_price']!=0 || $attrs['rest_price']!=""){	$rest_p = "|".$attrs['rest_price'];	} else {	$rest_p = "";	}
				
				$show	= $show.$attrs['Title'].$p.$rest_p."\n";
	   			$id		= $attrs['id'];
				if($attrs['Required']==1){	$Checked_requied="checked";	}
		
			} //end while
	
	//////////////////////////////////////	EDIT ATTRIBUTE	/////////////////////////////////////////////
	if(isset($_REQUEST['submit'])){
		
				$attrArray 			= array();
				$optionname   		= @$_REQUEST['option_name'];
				$optionlayout 		= @$_REQUEST['option_layout'];
				$applysubcat 		= @$_REQUEST['apply_subcat'];
				$old_option_name    = @$_REQUEST['name'];
				//$catid 				= $prdandsubcatRes['cid'];
				$sub_cat_id 		= @$_REQUEST['scid'];
				$moveup				= @$_REQUEST['moveup'];
				$movedown			= @$_REQUEST['movedown'];
				$required			= @$_REQUEST['required'];
				if( $optionname == "") {
					$errMessage="Pleaser Enter Option Name";
				} else { 
		
						if(empty($required)){ $required=0; }
						
						$ordingsecondary	= mysql_query("Select distinct(option_name),OderingNO from attribute Where ProductID=$prdid order By OderingNO");
										$i	= 0;
						$StrorderNoSeconday	= "";
			while($OrdSecondaryRs=mysql_fetch_array($ordingsecondary))
					{ 
						if($i==0){	$StrorderNoSeconday=$StrorderNoSeconday.$OrdSecondaryRs[1];	}
							 else{	$StrorderNoSeconday=$StrorderNoSeconday."~".$OrdSecondaryRs[1];	}
					}
			$Second_Order_no	= split("~",$StrorderNoSeconday);
		
			
		//*************************************************
		//	Get Other OrderNo of this Attribute
		//*************************************************	
			$ordingquery	= mysql_query("Select distinct(option_name),OderingNO from attribute Where ProductID=$prdid AND option_name='$optionname'");
			$OrdRs			= mysql_fetch_array($ordingquery); 
			$orderNo		= $OrdRs[1];
			
		//***************************
		//Delete This Attribute
		//****************************
			$old_option_name_query	= mysql_query("Select * FROM attribute WHERE option_name = '$old_option_name' and ProductID=$prdid ");
					$old_title 		= array();
							$o		= 0;
					while($attrs_old = mysql_fetch_array($old_option_name_query)) 
							{
								$old_title[$o]=$attrs_old['Title'];
								$old_ids[$o]=$attrs_old['id'];
								$o++;
							}
		
					$option	= trim($_POST['option_title']);
					$arr 	= split("\r\n", $option);
					$i 		= 0;
					while($i<count($arr))
						{
											
							$arr1	= split("=", $arr[$i]);
							$name	= @$arr1[0];
							$rate_ary = explode("|", $arr1[1]);
							$value	= @$rate_ary[0];
							$rest_price	= @$rate_ary[1];
							
							if ($value==NULL){	$value = 0;	}
							
							if ($applysubcat==1)
								 { 
									$selectprdQry	= mysql_query("select * from product where sub_cat_id=$sub_cat_id"); 
									while ($selectprdRes=mysql_fetch_array($selectprdQry))
										 {
											$pid	= $selectprdRes['prd_id'];
											$Exitst_option_name_query	=mysql_query("Select * FROM attribute WHERE option_name = '$old_option_name' and ProductID=$pid AND id=".$old_ids[$i]."  AND Title='".@$old_title[$i]."'");
				
									if(mysql_num_rows( $Exitst_option_name_query)>0)
										{
                                                                                        Log::write("Update attribute - tab_edit_attribute.php", "QUERY -- UPDATE attribute SET ProductID='$pid',option_name= '$optionname', Title= '$name', Price= '$value', option_display_preference= 0, apply_sub_cat= '$applysubcat', Type= '$optionlayout', Required= $required, rest_price='$rest_price' WHERE ProductID='$pid' AND option_name= '$old_option_name' AND id=".$old_ids[$i]." AND Title='".$old_title[$i]."'", 'menu', 1 , 'cpanel');
											mysql_query("UPDATE attribute SET ProductID='$pid',option_name= '".mysql_real_escape_string($optionname)."', Title= '".mysql_real_escape_string($name)."', Price= '".mysql_real_escape_string($value)."', option_display_preference= 0, apply_sub_cat= '$applysubcat', Type= '$optionlayout', Required= $required, rest_price='".mysql_real_escape_string($rest_price)."' WHERE ProductID='$pid' AND option_name= '$old_option_name' AND id=".$old_ids[$i]." AND Title='".$old_title[$i]."'");
										}else{
                                                                                                Log::write("Add new attribute", "QUERY -- INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type,Required,OderingNO,rest_price) VALUES ('$pid' , '$optionname', '$name', '$value', 0, '$applysubcat', '$optionlayout',$required,$orderNo,'$rest_price')", 'menu', 1 , 'cpanel');
												mysql_query("INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type,Required,OderingNO,rest_price) VALUES ('$pid' , '".mysql_real_escape_string($optionname)."', '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($value)."', 0, '$applysubcat', '$optionlayout',$required,$orderNo,'".mysql_real_escape_string($rest_price)."')");     
                                                                                                Log::write("Set product HasAttributes=1 - tab_edit_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $pid . "", 'menu', 1 , 'cpanel');
                                                                                                mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id = " . $pid . "");
											}	
							} // end while 
							} // end subcat if 
					else {
							 $Exitst_option_name_query=mysql_query("Select * FROM attribute WHERE option_name = '$old_option_name' and ProductID=$prdid AND Title='".@$old_title[$i]."'");
				
							 if(mysql_num_rows( $Exitst_option_name_query)>0)
								{
                                                                        Log::write("Update attribute - tab_edit_attribute.php", "QUERY -- UPDATE attribute SET ProductID='$prdid',option_name= '$optionname', Title= '$name', Price= '$value', option_display_preference= 0, apply_sub_cat= '$applysubcat', Type= '$optionlayout', Required= $required, rest_price='$rest_price' WHERE ProductID='$prdid' AND option_name= '$old_option_name' AND id=".$old_ids[$i]." AND Title='$old_title[$i]'", 'menu', 1 , 'cpanel');
									mysql_query("UPDATE attribute SET ProductID='$prdid',option_name= '".mysql_real_escape_string($optionname)."', Title= '".mysql_real_escape_string($name)."', Price= '".mysql_real_escape_string($value)."', option_display_preference= 0, apply_sub_cat= '$applysubcat', Type= '$optionlayout', Required= $required, rest_price='".mysql_real_escape_string($rest_price)."' WHERE ProductID='$prdid' AND option_name= '$old_option_name' AND id=".$old_ids[$i]." AND Title='$old_title[$i]'");
									
								}else{
                                                                                Log::write("Add new attribute - tab_edit_attribute.php", "QUERY -- INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type,Required,OderingNO,rest_price) VALUES ('$prdid' , '$optionname', '$name', '$value', 0, '$applysubcat', '$optionlayout',$required,$orderNo,'$rest_price')", 'menu', 1 , 'cpanel');
										mysql_query("INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type,Required,OderingNO,rest_price) VALUES ('$prdid' , '".mysql_real_escape_string($optionname)."', '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($value)."', 0, '$applysubcat', '$optionlayout',$required,$orderNo,'".mysql_real_escape_string($rest_price)."')");
                                                                                Log::write("Set product HasAttributes=1 - tab_edit_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $prdid . "", 'menu', 1 , 'cpanel');
                                                                            mysql_query("UPDATE product set HasAttributes=1 WHERE prd_id = " . $prdid . "");
									}
								}	// end subcat else
					$i++;
				} // end while
		
		//*********************************************
		//*********************************************
		if ($applysubcat==1) { 
						$selectprdQry = mysql_query("select * from product where sub_cat_id=$sub_cat_id"); 
						while ($selectprdRes=mysql_fetch_array($selectprdQry)) 
							{
								$pid	= $selectprdRes['prd_id'];
								for($o=$i;$o<count($old_title);$o++)
									{
                                                                            $mQuery = "Delete FROM attribute WHERE option_name = '$old_option_name' and ProductID=$pid AND Title='".@$old_title[$o]."'";
                                                                            mysql_query($mQuery);
                                                                            Log::write("Delete Attribute - tab_edit_attribute.php - LINE 142", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');	
									}	
							}
				
				}else{		
						for($o=$i;$o<count($old_title);$o++)
							{								
								$mQuery = "Delete FROM attribute WHERE option_name = '$old_option_name' and ProductID=$prdid AND Title='".@$old_title[$o]."'";
                                                                mysql_query($mQuery);
                                                                Log::write("Delete Attribute - tab_edit_attribute.php - LINE 151", "QUERY --".$mQuery, 'menu', 1 , 'cpanel');
                                                                $i++;
										}
					}
		
		//*******************************************************
		$query_GetAtr_id	= mysql_query("Select Distinct(option_name),id from attribute  Where ProductID=$prdid and  option_name='$optionname'"); 
		$Attribue_ID_RS		= mysql_fetch_row($query_GetAtr_id);
		$AT_ID				= $Attribue_ID_RS[1];
			if ($applysubcat==1) {
                                                                        Log::write("Update category - tab_edit_attribute.php", "QUERY --UPDATE categories SET Apply_Attribute= 1, AttributeId= $AT_ID WHERE cat_id=$sub_cat_id", 'menu', 1 , 'cpanel');
									mysql_query("UPDATE categories SET Apply_Attribute= 1, AttributeId= $AT_ID WHERE cat_id=$sub_cat_id");
								}else{
                                                                        Log::write("Update category - tab_edit_attribute.php", "QUERY --UPDATE categories SET Apply_Attribute= 0 WHERE cat_id=$sub_cat_id", 'menu', 1 , 'cpanel');
									mysql_query("UPDATE categories SET Apply_Attribute= 0 WHERE cat_id=$sub_cat_id");
								}
		//*******************************************************
		
		//*******************************************************************************************************************************
		//*************************************************
		//	
		//*************************************************
		for($j=0;$j<count($Second_Order_no);$j++){
		
			if($orderNo==$Second_Order_no[$j])
				{
					$Second_update_order_no=$Second_Order_no[$j];
				}	
			}
		
		
		$orderNo=0;
		if($moveup=="1"){
			
			$ordingquery		= mysql_query("Select * from attribute Where ProductID=$prdid AND option_name='$optionname'");
			$OrdRs				= mysql_fetch_array($ordingquery);
			$orderNo			= $OrdRs[9];
			$ChangAlreadyNoQry	= mysql_query("Select * from attribute Where ProductID=$prdid and OderingNO < $orderNo AND option_name !='$optionname' limit 0,1");
			$OrdRsChange		= mysql_fetch_array($ChangAlreadyNoQry);
			$changeorder		= $OrdRsChange[9];			
			$chageorderOptionName	= $OrdRsChange[2];
			
			$ChangAlreadyNoQry2	= mysql_query("Select * from attribute Where ProductID=$prdid and OderingNO < $orderNo AND option_name ='$chageorderOptionName'");
			 while ($OrdRsChange2=mysql_fetch_array($ChangAlreadyNoQry2)) {
                             Log::write("Update attribute - tab_edit_attribute.php", "QUERY -- UPDATE attribute SET OderingNO=".$orderNo." WHERE id=".$OrdRsChange2[0], 'menu', 1 , 'cpanel');
                             mysql_query("UPDATE attribute SET OderingNO=".$orderNo." WHERE id=".$OrdRsChange2[0]);
			}
		
			$ordingquery2=mysql_query("Select * from attribute Where ProductID=$prdid AND option_name='$optionname'");
			  while($OrdRs2=mysql_fetch_array($ordingquery2)){
                                        Log::write("Update attribute - tab_edit_attribute.php", "QUERY -- UPDATE attribute SET OderingNO=".$changeorder." WHERE id=".$OrdRs2[0], 'menu', 1 , 'cpanel');
					mysql_query("UPDATE attribute SET OderingNO=".$changeorder." WHERE id=".$OrdRs2[0]);
			 }
		}
		
		
		if($movedown=="1"){
		$changeAtr=0;
		//**************************************************************************************
		//Change number of already attribute 
		//**************************************************************************************
		
			$ordingquery=mysql_query("Select * from attribute Where ProductID=$prdid AND option_name='$optionname'");
			$OrdRs=mysql_fetch_array($ordingquery);
			$orderNo=$OrdRs[9];
		//	echo"Select * from attribute Where ProductID=$prdid AND option_name='$optionname'"."<br>";
			//echo "Select * from attribute Where ProductID=$prdid and OderingNO >=$orderNo AND option_name !='$optionname'"."<br>";
			$ChangAlreadyNoQry=mysql_query("Select * from attribute Where ProductID=$prdid and OderingNO >$orderNo AND option_name !='$optionname' limit 0,1");
		
			$OrdRsChange=mysql_fetch_array($ChangAlreadyNoQry);
			$changeorder=$OrdRsChange[9];			
			$chageorderOptionName=$OrdRsChange[2];
			
		$ChangAlreadyNoQry2=mysql_query("Select * from attribute Where ProductID=$prdid and OderingNO >$orderNo AND option_name ='$chageorderOptionName'");
			 while ($OrdRsChange2=mysql_fetch_array($ChangAlreadyNoQry2)) {
                             Log::write("Update attribute - tab_edit_attribute.php", "QUERY -- UPDATE attribute SET OderingNO=".$orderNo." WHERE id=".$OrdRsChange2[0], 'menu', 1 , 'cpanel');
			   mysql_query("UPDATE attribute SET OderingNO=".$orderNo." WHERE id=".$OrdRsChange2[0]);
		//	   echo "UPDATE attribute SET OderingNO=".$orderNo." WHERE id=".$OrdRsChange[0]."<br>";
			}
		
			$ordingquery2=mysql_query("Select * from attribute Where ProductID=$prdid AND option_name='$optionname'");
			  while($OrdRs2=mysql_fetch_array($ordingquery2)){
					Log::write("Update attribute - tab_edit_attribute.php", "QUERY -- UPDATE attribute SET OderingNO=".$changeorder." WHERE id=".$OrdRs2[0], 'menu', 1 , 'cpanel');
					mysql_query("UPDATE attribute SET OderingNO=".$changeorder." WHERE id=".$OrdRs2[0]);
				//	echo "UPDATE attribute SET OderingNO=".$changeorder." WHERE id=".$OrdRs[0]."<br>";
			 }
		
		
		}
		?>
				<script language="javascript">
								window.location="?mod=menus&catid=<?=$catid?>";
				</script>				
		 
		 <?
	}
 } // end submit ?>
<font class="style3">
<? 
	$menuQuery = mysql_query("SELECT cat_name FROM categories WHERE 	cat_id = '".$sub_cat_id."' AND parent_id = '".$catid."'");
	$menuRs = mysql_fetch_array($menuQuery);
	
	
//echo $menuQuery['cat_name']. "<br />";
echo "<strong>".stripslashes(stripcslashes($menuRs['cat_name']))." > ".stripslashes(stripcslashes($prdandsubcatRes['item_title']))?>
</br>
<div id="main_heading">Edit Menu</div>
<div class="form_outer">
 <form name="form1" method="post" action="">
   <table width="500" border="0"  cellpadding="4" cellspacing="0">
        <?=($errMessage != '')?"<div class=\"msg_error\">$errMessage</div>":"";?> 
          <tr align="left" valign="top">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="265"><strong>Option Name</strong></td>
            <td width="142"><input name="option_name" type="text" id="option_name" size="20" value="<? echo stripslashes(stripcslashes($name));?>">            </td>
            <td width="142"><a href="admin_contents/attributes/deleteattr.php?id=<? echo $prdid ;?>&name=<? echo $name;?>&cid=<?=$catid?>" onClick="return confirm('Are you sure you would like to delete this attribute?')" class="linktextbig">DELETE</a></td>
          </tr>
          <tr align="left" valign="top"> 
            <td><strong>Option Layout</strong> (Choose One) </td>
            <td><input name="option_layout" type="radio" value="1" <? if ($editattRes['Type']==1) {?> checked="checked" <? }?>>
              Drop Down Menu <br> <input name="option_layout" type="radio" value="2" <? if ($editattRes['Type']==2) {?> checked="checked" <? }?>>
              Check Boxes (choose all that apply) <br> <input name="option_layout" type="radio" value="3" <? if ($editattRes['Type']==3) {?> checked="checked" <? }?>>
              Radio Buttons (choose one option) </td>
          </tr>
          <tr align="left" valign="top"> 
            <td>Apply This Attribute To All Menu Items In Same Sub-Category As 
              This Item </td>
            <td colspan="2"><input name="apply_subcat" type="checkbox" id="apply_subcat" value="1"></td>
          </tr>
          <tr align="left" valign="top"> 
            <td>Make This Attribute Required For Ordering.</td>
            <td colspan="2"><input name="required" type="checkbox" id="required" value="1" <? echo $Checked_requied?>></td>
          </tr>
          <tr align="left" valign="top"> 
            <td>Move Up In Attribute List<br></td>
            <td colspan="2"><input name="moveup" type="checkbox" id="moveup" value="1" ></td>
          </tr>
          <tr align="left" valign="top">
            <td>Move Down In Attribute List</td>
            <td colspan="2"><input name="movedown" type="checkbox" id="movedown" value="1" ></td>
          </tr>
          <tr align="left" valign="top"> 
            <td colspan="2"><p>Enter Option Values Below <strong>Following These 
                Instructions</strong>:<br>
                - Enter each option on a new line<br>
                - To add an amount, list as &quot;option=1.00&quot; (this adds 
                <?=$currency?>1 to the base price of the item)<br>
                - To subtract an amount, list as &quot;option=-0.50&quot; (this 
                subtracts <?=$currency?>0.50 from the base price)</p></td>
          </tr>
          <tr align="left" valign="top"> 
            <td colspan="2"><textarea name="option_title" cols="50" rows="6" id="option_title"><? echo $show?></textarea> 
              <br> <br>
              Below is an example that will add $2 if a side salad is ordered 
              and <?=$currency?>3 if a ceaser salad is ordered:<br> <br>
              Side Salad=2.00<br>
              Ceaser Side Salad=3.00</td>
          </tr>
          <tr align="left" valign="top"> 
            <td colspan="2"><div align="center"></div>
              <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                <input type="submit" name="submit" value="Edit Attribute To Menu Item(s)">
                </font> 
                <input type="hidden" name="pid" value="<? echo $prdid?>">
                <input type="hidden" name="catid" value="<? echo $catid?>">
                <input type="hidden" name="scid" value="<? echo $sub_cat_id?>">
                <input type="hidden" name="name" value="<? echo $name?>">
                <!--<input type="hidden" name="name" value="<? echo $name?>">-->
              </div></td>
          </tr>
        </table>
 </form>
</div>
  