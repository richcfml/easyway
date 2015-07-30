<?php 
		$prdid        = @$_REQUEST['pid'];
		$catid		  = $_GET['catid'];
		
	if(isset($_REQUEST['submit'])){
		
				$attrArray 		= array();
				$optionname   	= @$_REQUEST['option_name'];
				$optionlayout 	= @$_REQUEST['option_layout'];
				$applysubcat 	= @$_REQUEST['apply_subcat'];
				$required		= @$_REQUEST['required'];
				if( $optionname == "") {
					$errMessage="Pleaser Enter Option Name";
				} else { 
						if(empty($required)){ $required=0; }
						
						$MaxOrder		= dbAbstract::Execute("SELECT MAX(OderingNO) FROM attribute Where ProductID =$prdid", 1);
						$MaxRs			= dbAbstract::returnRow($MaxOrder, 1);
						$Max_Order_No	= $MaxRs[0]+1;
		
						$prdandsubcatQry = dbAbstract::Execute("select p.item_title,c.cat_name,c.cat_id,sub_cat_id from product p,categories c where p.sub_cat_id=c.cat_id and p.prd_id=$prdid", 1);
						$prdandsubcatRes	= dbAbstract::returnArray($prdandsubcatQry, 1);
						$sub_cat_id 		= $prdandsubcatRes['sub_cat_id'];
						
						$option = trim($_POST['option_title']);
						$arr = preg_split("/\r\n/", $option);
						$i = 0;
						while($i<count($arr)){
												$arr1	= explode("=", $arr[$i]);
												$name	= @$arr1[0];
												$rate_ary = explode("|", $arr1[1]);
												$value	= @$rate_ary[0];
												$rest_price	= @$rate_ary[1];
												
												if ($value==NULL){ $value = 0; }
												
							if ($applysubcat==1) 
								{
									$selectprdQry = dbAbstract::Execute("select * from product where sub_cat_id=$sub_cat_id", 1);
									while ($selectprdRes=dbAbstract::returnArray($selectprdQry, 1)) 
										{
											$pid = $selectprdRes['prd_id'];
                                                                            Log::write("Add new attribute - tab_add_attribute.php", "QUERY -- INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type,Required,OderingNO,rest_price) VALUES ('$pid' , '$optionname', '$name', '$value', 0, '$applysubcat', '$optionlayout',$required,$Max_Order_No,'$rest_price')", 'menu', 1 , 'cpanel');
                                                                            dbAbstract::Insert("INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type,Required,OderingNO,rest_price) VALUES ('$pid' , '".dbAbstract::returnRealEscapedString($optionname)."', '".dbAbstract::returnRealEscapedString($name)."', '".dbAbstract::returnRealEscapedString($value)."', 0, '$applysubcat', '$optionlayout',$required,$Max_Order_No,'".dbAbstract::returnRealEscapedString($rest_price)."')", 1); 
                                                                            Log::write("Set product HasAttributes=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $prdid . "", 'menu', 1 , 'cpanel');
                                                                            dbAbstract::Update("UPDATE product set HasAttributes=1 WHERE prd_id = " . $prdid . "", 1);
												
										} // end inner while
								} else {
                                                                        Log::write("Add new attribute - tab_add_attribute.php", "QUERY -- INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type,Required,OderingNO,rest_price) VALUES ('$prdid' , '$optionname', '$name', '$value', 0, '$applysubcat', '$optionlayout',$required,$Max_Order_No,'$rest_price')", 'menu', 1 , 'cpanel');
									dbAbstract::Insert("INSERT INTO attribute (ProductID, option_name, Title, Price, option_display_preference, apply_sub_cat, Type,Required,OderingNO,rest_price) VALUES ('$prdid' , '".dbAbstract::returnRealEscapedString($optionname)."', '".dbAbstract::returnRealEscapedString($name)."', '".dbAbstract::returnRealEscapedString($value)."', 0, '$applysubcat', '$optionlayout',$required,$Max_Order_No,'".dbAbstract::returnRealEscapedString($rest_price)."')", 1); 
                                                                        Log::write("Set product HasAttributes=1 - tab_add_attribute.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $prdid . "", 'menu', 1 , 'cpanel');
                                                                        dbAbstract::Update("UPDATE product set HasAttributes=1 WHERE prd_id = " . $prdid . "", 1);
									
										}	 
							   $i++;
					} // end upper while
		
				$query_GetAtr_id	= dbAbstract::Execute("Select Distinct(option_name),id from attribute  Where ProductID=$prdid and option_name='$optionname'", 1); 
				$Attribue_ID_RS		= dbAbstract::returnRow($query_GetAtr_id, 1);
				$AT_ID				= $Attribue_ID_RS[1];
				if ($applysubcat==1) {
                                                Log::write("Update category - tab_add_attribute.php", "QUERY --UPDATE categories SET Apply_Attribute= 0 WHERE cat_id=$sub_cat_id", 'menu', 1 , 'cpanel');
						 dbAbstract::Update("UPDATE categories SET Apply_Attribute= 1, AttributeId= $AT_ID WHERE cat_id=$sub_cat_id", 1);
				}else{
                                                Log::write("Update category - tab_add_attribute.php", "QUERY --UPDATE categories SET Apply_Attribute= 0 WHERE cat_id=$sub_cat_id", 'menu', 1 , 'cpanel');
						 dbAbstract::Update("UPDATE categories SET Apply_Attribute= 0 WHERE cat_id=$sub_cat_id", 1);
					} 
					?>
						<script language="javascript">
								window.location="?mod=menus&catid=<?=$catid?>";
						</script>				
		 
		 <? 
			}// else
 } // end submit ?>
<div id="main_heading">Add Attribute</div>
<div class="form_outer">
  <form name="form1" method="post" action="">
    <table width="500" border="0"  cellpadding="4" cellspacing="0">
    	   <?=($errMessage != '')?"<div class=\"msg_error\">$errMessage</div>":"";?>
    <tr align="left" valign="top"> 
      <td width="265"><strong>Option Name</strong></td>
      <td width="284"><input name="option_name" type="text" id="option_name" size="20"> 
      </td>
    </tr>
    <tr align="left" valign="top"> 
      <td><strong>Option Layout</strong> (Choose One) </td>
      <td><input name="option_layout" type="radio" value="1" checked>
        Drop Down Menu <br> <input name="option_layout" type="radio" value="2">
        Check Boxes (choose all that apply) <br> <input name="option_layout" type="radio" value="3">
        Radio Buttons (choose one option) </td>
    </tr>
    <tr align="left" valign="top"> 
      <td>Apply This Attribute To All Menu Items In Same Sub-Category As 
        This Item </td>
      <td><input name="apply_subcat" type="checkbox" id="apply_subcat" value="1"></td>
    </tr>
    <tr align="left" valign="top">
      <td>Make This Attribute Required For Ordering.<br></td>
      <td><input name="required" type="checkbox" id="required" value="1" ></td>
    </tr>
    <tr align="left" valign="top"> 
      <td colspan="2">
          <div class="msg_warning">
            Enter Option Values Below <strong>Following These 
            Instructions</strong>:<br>
            - Enter each option on a new line<br>
            - To add an amount, list as &quot;option=1.00&quot; (this adds 
            <?=$currency?>1 to the base price of the item)<br>
            - To subtract an amount, list as &quot;option=-0.50&quot; (this 
            subtracts <?=$currency?>0.50 from the base price)
          </div></td>
    </tr>
    <tr align="left" valign="top"> 
      <td colspan="2"><textarea name="option_title" cols="50" rows="6" id="option_title"></textarea> 
      </td>
    </tr>
    <tr align="left" valign="top"> 
      <td colspan="2">
      </td>
    </tr>
    <tr align="left" valign="top"> 
      <td colspan="2"><div align="center"></div>
        <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input type="submit" name="submit" value="Add Attribute To Menu Item(s)">
          </font> 
          <input type="hidden" name="pid" value="<? echo $prdid?>">
           <input type="hidden" name="catid" value="<? echo $catid?>">
        </div></td>
    </tr>
  </table>
  </form>
</div>
  