<?	$errMessage='';
$item_title='';
$item_des ='';
		$catid		= $_GET['catid'];
		$sub_cat	= $_REQUEST['scid'];
		
		 
		$myimage	= new ImageSnapshot; //new instance
		if(isset($_FILES['userfile'])) $myimage->ImageField	 = $_FILES['userfile']; //uploaded file array
		
		function GetFileExt($fileName){
			$ext = substr($fileName, strrpos($fileName, '.') + 1);
			$ext = strtolower($ext);
			return $ext;
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
		
		if (isset($_REQUEST['submit']) ){
		

		$errMessage ="";
				   
		 if ($sub_cat < 0) {
					 $errMessage="Please Select Menu/Sub-Category";
		}else if($item_title == ''){
					$errMessage="Please Enter Item Title";
		}else if($r_price == ''){
					$errMessage="Please Enter Regular Price";
		}else if (!empty($_FILES['userfile']['name']) && $myimage->ProcessImage() == false) {	
					 $errMessage="Pleaser Enter Valid Image"; 
		}else{										
						  $r_price = str_replace("$",",",$r_price);
						   $r_price = split(",",$r_price);
						   $r_price = trim(end($r_price));
						   if (strstr($r_price, ".")==FALSE) {
								$r_price = $r_price.".00";				
						   } else {
								$r_price = $r_price;						
							   if(substr($r_price,0,1) == '.'){
								 $r_price = '0'.$r_price;
							   }
						   }
						  				
						    //if($feature_subcat == ''){ $feature_subcat=0; }
							 $feature_subcat=0;
							
							$item_title =  str_replace('"','&rdquo;',$item_title);
							$item_title =  str_replace('"','&rsquo;',$item_title);
							
							$item_des = str_replace('"','&rdquo;',$item_des);
							$item_des = str_replace("'",'&rsquo;',$item_des);
							// echo "INSERT INTO product set cat_id = $catid, sub_cat_id = $sub_cat,item_title = '".addslashes($item_title)."', item_code = $item_id, item_des = '".addslashes($item_des)."', retail_price = '$r_price', sale_price = '0.00', rest_price = '$rest_price', feature_sub_cat = $feature_subcat, img_show_hide = $img_show_hide";
                                                        Log::write("Add new product  - tab_product_add.php", "QUERY -- INSERT INTO product set cat_id = $catid, sub_cat_id = $sub_cat,item_title = '".addslashes($item_title)."', item_des = '".addslashes($item_des)."', retail_price = '$r_price', feature_sub_cat = $feature_subcat", 'menu', 1 , 'cpanel');
						 $lastid = dbAbstract::Insert("INSERT INTO product set cat_id = $catid, sub_cat_id = $sub_cat,item_title = '".addslashes($item_title)."', item_des = '".addslashes($item_des)."', retail_price = '$r_price', feature_sub_cat = $feature_subcat",1,2);
			
			if(!empty($_FILES['userfile']['name'])){
				
					$path = '../images/item_images/';
					$exe = GetFileExt($_FILES['userfile']['name']);	
					$name = "img_".$lastid."_prd.".$exe;
					$uploadfile = $path . $name;
					move_uploaded_file( $_FILES['userfile']['tmp_name'] , $uploadfile );
					list($width, $height, $type, $attr) = getimagesize("$uploadfile");
					if($height>$width){
							$image = new SimpleImage();
							$image->load($uploadfile);
							$image->resizeToHeight(500);
							$image->save($uploadfile);
					}else {
							$image = new SimpleImage();
							$image->load($uploadfile);
							$image->resizeToWidth(500);
							$image->save($uploadfile);
					}
                                        Log::write("Update Product Status - menu_ajax.php", "QUERY -- UPDATE product set item_image = '$name' where prd_id = ".$lastid, 'menu', 1 , 'cpanel'); 
				 dbAbstract::Update("UPDATE product set item_image = '$name' where prd_id = ".$lastid,1);	
				 			
										
			} // End of IF
			if($_REQUEST[itemcheck]){
				foreach ($itemcheck as $arr)
                                    Log::write("Add new product  - tab_product_add.php", "QUERY -- INSERT INTO product_association set product_id = $lastid, association_id = $arr", 'menu', 1 , 'cpanel');
				 dbAbstract::Insert("INSERT INTO product_association set product_id = $lastid, association_id = $arr",1);
                                    Log::write("Set product HasAssociates=1 - tab_product_add.php", "QUERY -- UPDATE product set HasAttributes=1 WHERE prd_id = " . $lastid . "", 'menu', 1, 'cpanel');
                                 dbAbstract::Update("UPDATE product set HasAssociates=1 WHERE prd_id = " . $lastid . "",1);
        }
			
			if($_POST[submit] == 'Save and New')
					echo '<script language="javascript">window.location="?mod=menus&item=addproduct&catid='.$catid.'&scid='.$sub_cat.'";</script>';
			 elseif($_POST[submit] == 'Save')	
			 		echo '<script language="javascript">window.location="?mod=menus&catid='.$catid.'";</script>';
			
			} // end of else
	}// end submit
?>
<div id="main_heading">Add Menu Item</div>
<div class="form_outer">
<form name="form1" method="post" action=""  enctype="multipart/form-data">
  <table width="500" border="0"  cellpadding="4" cellspacing="0">
                <?=($errMessage != '')?"<div class=\"msg_error\">$errMessage</div>":"";?>
    <tr align="left" valign="top">
      <td colspan="2"><strong>Item Title:</strong><br />

      <input name="item_title" type="text" size="40" id="item_title" value="<? echo stripslashes(stripcslashes($item_title))?>">          </td>
    </tr>
    <tr align="left" valign="top">
        <td colspan="2"><strong>Item Description:</strong><br>
          <div class="msg_warning">To insert a new paragraph, enter &lt;P&gt;. 
          To bold text, surround text with &lt;B&gt;
          and &lt;/B&gt;. To italicize text, surround text with &lt;I&gt; 
          and &lt;/I&gt;.</div><br>
          <textarea name="item_des" cols="40" rows="6" id="item_des"><? echo stripslashes(stripcslashes($item_des))?>
          </textarea>          </td>
    </tr>
    <tr align="left" valign="top">
      <td colspan="2"><strong>Price (in <?php echo $Objrestaurant->region == "1" ? '$' : '&#163;';?>):</strong>
      <font color="#666666">(ex. <?=$currency?>10.00)</font> <br>           <input name="r_price" type="text" size="20" id="r_price" value="<?=@$r_price?>">          </td>
    </tr>
    <tr align="left" valign="top">
      <td colspan="2"><strong>Item Image:</strong><br />
      <input name="userfile" type="file" size="30"></td>
    </tr>
    <tr align="left" valign="top">
      <td colspan="2"><strong>Check Items to Associate:</strong><br />
       <?
        $count = 0;
  
        $item_query = dbAbstract::Execute("SELECT * FROM product WHERE cat_id='".$_GET['catid']."' AND prd_id!='".$_GET['pid']."'",1);
         while($itemRs	=	dbAbstract::returnObject($item_query,1)){
            //if product is already associated then chech box will be checed else check box will unchecked.
             $assoc_query = dbAbstract::Execute("SELECT  id FROM product_association WHERE product_id='".$_GET['pid']."' AND association_id ='".$itemRs->prd_id."' ",1);
             $assoc_rows = dbAbstract::returnArray($assoc_query,1);	
             if($count % 2 == 0) echo "<br />";
      ?>
      <input  name="itemcheck[]" id="itemcheck" type="checkbox" <? if($assoc_rows['id']) { ?>  checked="checked" <? }?> value="<?=$itemRs->prd_id ?>"   /><?=stripslashes($itemRs->item_title) ?>
      <?
      $count++;  
      }
      ?>
      
      </td>
    </tr>
   
    <tr align="left" valign="top">
      <td colspan="2"> <input type="submit" name="submit" id="saveandnew" value="Save and New"> &nbsp;&nbsp; <input   type="submit" name="submit" id="save" value="Save">
      <input type="hidden" name="catid" id="catid" value="<?=$catid?>" /></td>
    </tr>
  </table>
 </form>
</div>
