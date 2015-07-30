<script src="../js/mask.js" type="text/javascript"></script>

<?php

	$qry="select id, cust_your_name as firstname,LastName from customer_registration where password != '' and resturant_id=". $Objrestaurant->id ." ";
	$search=0;
if(isset($_POST['findbylastname'])) {
	$qry .=" and LOWER(LastName) like '". strtolower($_POST['last_name']) ."%'";
	$search=1;
}else if(isset($_POST['findbybusiness'])) {
	
	$qry .=" and LOWER(cust_business_name) like '". strtolower($_POST['cust_business_name']) ."%'";
	$search=1;
 
 }else if(isset($_POST['findbyemail'])) {
	
	$qry .=" and LOWER(cust_email) like '". strtolower($_POST['email']) ."%'";
	$search=1;
 
	
}else if(isset($_POST['findbyphone'])) {
	
	$qry .=" and  (REPLACE(REPLACE(REPLACE( REPLACE(cust_phone1,'(',  ''), ')', ''),'-',''),' ',''))  like '%". $_POST['phonenumber'] ."%' OR (REPLACE(REPLACE(REPLACE( REPLACE(cust_phone2,'(',  ''), ')', ''),'-',''),' ','')) like '%". $_POST['phonenumber'] ."%')";
	$search=1;
}else if(isset($_POST['findbyalpha'])) {
	
	if($_POST['findlastalpha']=="AN")
	
		$qry .=" and LastName BETWEEN '0%' and '9%'";
	 else
		$qry .=" and LOWER(LastName) like '". strtolower($_POST['findlastalpha']) ."%'";
		
	$search=1;
}

$qry .= " Order by LastName";
 
 
 if($search==1)
 {
	 $result =array();
 	 $query=dbAbstract::Execute($qry, 1);
	 while ($result_object=dbAbstract::returnObject($query, 1)) {
			 $result[]=$result_object;
		  }
		 
 }
?>

<div id="main_heading">FIND AN EXISTING REGISTER CUSTOMER</div>
<div class="form_outer">
  <table width="750" border="0" cellpadding="4" cellspacing="0">
    <tr>
      <td class="style3"><strong> FIND A CUSTOMER </strong></td>
    </tr>
    <tr>
      <td><form name="form1" method="post" action="">
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="31%" class="bodytext">Enter Customer's Last Name</td>
              <td width="69%" height="22" align="left"><input type="text" name="last_name" value="<?= isset($_POST['last_name']) ? $_POST['last_name']:"" ?>"></td>
            </tr>
            <tr>
              <td height="30">&nbsp;</td>
              <td height="30" align="left" valign="bottom"><input type="submit" name="findbylastname" value="Find Customer" ></td>
            </tr>
            
                  
                <? if(isset($_POST['findbylastname'])) { 
				
				if(count($result)>0) {?>
                <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3">We Found The Following Matches - Please Select One To Edit:</strong></td>
                </tr>
                <? }else { ?>
                  <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3">No result found against this search queury , please try again!</strong></td>
                </tr>
                <? } if(count($result)>0) {?>
				    <tr align="left"> 
                    <td colspan="2">
				 <div style="width:450px;">
                 <? 
				 $float="left";
				 foreach ($result  as $cust_result)  { ?>
                 
                 <div style="float:<?=$float?>;  width:48%;">
            		<a href="?mod=customer&item=edituser&userid=<?=$cust_result->id?>"> <?=  $cust_result->firstname . " ". $cust_result->LastName  ?></a>
                </div>
                 
           
                 
                 <? 
				 $float= ($float=="left" ? "right" :"left");
				 } ?>
                 <div style="clear:both;"> </div>
                 </div>
                 
                 </td>	 
                 </tr>
                     
            
                 
                
                <? 
				} //RESULTS FOUND
				
				}  //SEARCH BY LAST NAME
				 ?> 
                 
          </table>
        </form></td>
    </tr>
    <tr>
      <td height="20" align="left" ><HR width="100%" noShade SIZE=1></td>
    </tr>
    <tr align="left" valign="top">
      <td><form action="" method="post" name="frmfindbybusiness" id="search">
          <table width="100%" border="0" align="center" cellspacing="0">
            <tr>
              <td colspan="2" align="left" class="bodytext"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="30%" class="bodytext">Enter Business Name </td>
                    <td width="70%"><input name="cust_business_name" type="text" id="cust_business_name" value="<?= isset($_POST['cust_business_name']) ? $_POST['cust_business_name']:"" ?>"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td height="30" valign="bottom"><input type="submit" name="findbybusiness" value="Find Customer"></td>
                  </tr>
                </table></td>
            </tr>
             
            <? if(isset($_POST['findbybusiness'])) { 
				
				if(count($result)>0) {?>
                <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3">We Found The Following Matches - Please Select One To Edit:</strong></td>
                </tr>
                <? }else { ?>
                  <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3">No result found against this search queury , please try again!</strong></td>
                </tr>
                <? } if(count($result)>0) {?>
				    <tr align="left"> 
                    <td colspan="2">
				 <div style="width:450px;">
                 <? 
				 $float="left";
				 foreach ($result  as $cust_result)  { ?>
                 
                 <div style="float:<?=$float?>;  width:48%;">
            		<a href="?mod=customer&item=edituser&userid=<?=$cust_result->id?>"> <?=  $cust_result->firstname . " ". $cust_result->LastName  ?></a>
                </div>
                 
           
                 
                 <? 
				 $float= ($float=="left" ? "right" :"left");
				 } ?>
                 <div style="clear:both;"> </div>
                 </div>
                 
                 </td>	 
                 </tr>
                     
            
                 
                
                <? 
				} //RESULTS FOUND
				
				}  //SEARCH BY LAST NAME
				 ?> 
             
          </table>
        </form></td>
    </tr>
    <tr>
      <td height="20" align="left" ><HR width="100%" noShade SIZE=1></td>
    </tr>
    <tr align="left" valign="top">
      <td><form action="" method="post" name="form_email" id="form_email">
          <table width="100%" border="0" align="center" cellspacing="0">
            <tr>
              <td colspan="2" align="left" class="bodytext"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="30%" class="bodytext">Enter Customer's Email</td>
                    <td width="70%"><input name="email" type="text" id="email" value="<?= isset($_POST['email']) ? $_POST['email']:"" ?>"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td height="30" valign="bottom"><input type="submit" name="findbyemail" value="Find Customer"></td>
                  </tr>
                </table></td>
            </tr>
             
        		  <? if(isset($_POST['findbyemail'])) { 
				
				if(count($result)>0) {?>
                <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3">We Found The Following Matches - Please Select One To Edit:</strong></td>
                </tr>
                <? }else { ?>
                  <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3">No result found against this search queury , please try again!</strong></td>
                </tr>
                <? } if(count($result)>0) {?>
				    <tr align="left"> 
                    <td colspan="2">
				 <div style="width:450px;">
                 <? 
				 $float="left";
				 foreach ($result  as $cust_result)  { ?>
                 
                 <div style="float:<?=$float?>;  width:48%;">
            		<a href="?mod=customer&item=edituser&userid=<?=$cust_result->id?>"> <?=  $cust_result->firstname . " ". $cust_result->LastName  ?></a>
                </div>
                 
           
                 
                 <? 
				 $float= ($float=="left" ? "right" :"left");
				 } ?>
                 <div style="clear:both;"> </div>
                 </div>
                 
                 </td>	 
                 </tr>
                     
            
                 
                
                <? 
				} //RESULTS FOUND
				
				}  //SEARCH BY LAST NAME
				 ?> 
             
          </table>
        </form></td>
    </tr>
    <tr>
      <td height="20" align="left" ><HR width="100%" noShade SIZE=1></td>
    </tr>
    <? /////////////////////////////////////////////////////////////////////////////////////////////?>
    <tr align="left" valign="top">
      <td><form action="" method="post" name="frmphone" id="phone">
          <table width="100%" border="0" align="center" cellspacing="0">
            <tr>
              <td colspan="2" align="left" class="bodytext"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="30%" class="bodytext">Enter Phone Number </td>
                    <td width="70%"><input name="phonenumber" type="text" id="phonenumber" value="<?= isset($_POST['phonenumber']) ? $_POST['phonenumber']:"" ?>"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td height="30" valign="bottom"><input type="submit" name="findbyphone" value="Find Customer"></td>
                  </tr>
                </table></td>
            </tr>
             
            <? if(isset($_POST['findbyphone'])) { 
				
				if(count($result)>0) {?>
                <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3">We Found The Following Matches - Please Select One To Edit:</strong></td>
                </tr>
                <? }else { ?>
                  <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3">No result found against this search queury , please try again!</strong></td>
                </tr>
                <? } if(count($result)>0) {?>
				    <tr align="left"> 
                    <td colspan="2">
				 <div style="width:450px;">
                 <? 
				 $float="left";
				 foreach ($result  as $cust_result)  { ?>
                 
                 <div style="float:<?=$float?>;  width:48%;">
            		<a href="?mod=customer&item=edituser&userid=<?=$cust_result->id?>"> <?=  $cust_result->firstname . " ". $cust_result->LastName  ?></a>
                </div>
                 
           
                 
                 <? 
				 $float= ($float=="left" ? "right" :"left");
				 } ?>
                 <div style="clear:both;"> </div>
                 </div>
                 
                 </td>	 
                 </tr>
                     
            
                 
                
                <? 
				} //RESULTS FOUND
				
				}  //SEARCH BY LAST NAME
				 ?> 
             
          </table>
        </form></td>
    </tr>
    <? /////////////////////////////////////////////////////////////////////////////////////////////?>
    <tr>
      <td height="20" align="left" ><HR width="100%" noShade SIZE=1></td>
    </tr>
    <tr>
      <td><form name="form3" method="post" action="">
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="31%" class="bodytext">Browse Alpha List</td>
              <td width="69%" height="22" align="left"><select name="findlastalpha" id="findlastalpha">
                  <option value="AN"   <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="AN" ? "selected" :""):"" ?>>0-9</option>
                  <option value="A" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="A" ? "selected" :""):"" ?>>A</option>
                  <option value="B"<?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="B" ? "selected" :""):"" ?>>B</option>
                  <option value="C" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="C" ? "selected" :""):"" ?>>C</option>
                  <option value="D" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="D" ? "selected" :""):"" ?>>D</option>
                  <option value="E" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="E" ? "selected" :""):"" ?>>E</option>
                  <option value="F" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="F" ? "selected" :""):"" ?>>F</option>
                  <option value="G" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="G" ? "selected" :""):"" ?>>G</option>
                  <option value="H" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="H" ? "selected" :""):"" ?>>H</option>
                  <option value="I" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="I" ? "selected" :""):"" ?>>I</option>
                  <option value="J" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="J" ? "selected" :""):"" ?>>J</option>
                  <option value="K" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="K" ? "selected" :""):"" ?>>K</option>
                  <option value="L" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="L" ? "selected" :""):"" ?>>L</option>
                  <option value="M" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="M" ? "selected" :""):"" ?>>M</option>
                  <option value="N" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="N" ? "selected" :""):"" ?>>N</option>
                  <option value="O" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="O" ? "selected" :""):"" ?>>O</option>
                  <option value="P" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="P" ? "selected" :""):"" ?>>P</option>
                  <option value="Q" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="Q" ? "selected" :""):"" ?>>Q</option>
                  <option value="R" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="R" ? "selected" :""):"" ?>>R</option>
                  <option value="S" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="S" ? "selected" :""):"" ?>>S</option>
                  <option value="T" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="T" ? "selected" :""):"" ?>>T</option>
                  <option value="U" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="U" ? "selected" :""):"" ?> >U</option>
                  <option value="V" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="V" ? "selected" :""):"" ?>>V</option>
                  <option value="W" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="W" ? "selected" :""):"" ?>>W</option>
                  <option value="X" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="X" ? "selected" :""):"" ?>>X</option>
                  <option value="Y" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="Y" ? "selected" :""):"" ?>>Y</option>
                  <option value="Z" <?= isset($_POST['findlastalpha']) ? ($_POST['findlastalpha']=="Z" ? "selected" :""):"" ?>>Z</option>
                </select></td>
            </tr>
            <tr>
              <td height="30">&nbsp;</td>
              <td height="30" align="left" valign="bottom"><input type="submit" name="findbyalpha" value="Display Customer In Selected List" ></td>
            </tr>
             
           <? if(isset($_POST['findbyalpha'])) { 
				
				if(count($result)>0) {?>
                <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3">We Found The Following Matches - Please Select One To Edit:</strong></td>
                </tr>
                <? }else { ?>
                  <tr align="left"> 
                  <td height="35" colspan="2" class="style3"><strong class="style3">No result found against this search queury , please try again!</strong></td>
                </tr>
                <? } if(count($result)>0) {?>
				    <tr align="left"> 
                    <td colspan="2">
				 <div style="width:450px;">
                 <? 
				 $float="left";
				 foreach ($result  as $cust_result)  { ?>
                 
                 <div style="float:<?=$float?>;  width:48%;">
            		<a href="?mod=customer&item=edituser&userid=<?=$cust_result->id?>"> <?=  $cust_result->firstname . " ". $cust_result->LastName  ?></a>
                </div>
                 
           
                 
                 <? 
				 $float= ($float=="left" ? "right" :"left");
				 } ?>
                 <div style="clear:both;"> </div>
                 </div>
                 
                 </td>	 
                 </tr>
                     
            
                 
                
                <? 
				} //RESULTS FOUND
				
				}  //SEARCH BY LAST NAME
				 ?> 
             
          </table>
        </form></td>
    </tr>
  </table>
</div>
<font size="2" face="Arial, Helvetica, sans-serif">&nbsp; </font> 
<script type="text/javascript">/*<![CDATA[*/// 
   jQuery(function($) {
      //$.mask.definitions['~']='[+-]';
      //$('#date').mask('99/99/9999');
       
	  $('#phonenumber').mask('(999) 999-9999');
	  
      //$('#customer_cell').mask("(999) 999-9999? x99999");
     // $("#tin").mask("99-9999999");
      //$("#ssn").mask("999-99-9999");
    //  $("#product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("You typed the following: "+this.val());}});
     // $("#eyescript").mask("~9.99 ~9.99 999");
   });
// ]]&gt;/*]]>*/</script>