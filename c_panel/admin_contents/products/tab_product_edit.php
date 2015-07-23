<? 
		$pid		= $_REQUEST['pid'];
		$catid 		= $_GET['catid'];
		$menu_qry	= mysql_query("select * from product where prd_id = $pid");
		$menu_qryRs	= mysql_fetch_object($menu_qry);
		
		///////////////////$values variable used for arguments in get_menu_drop_down function /////////////////////////
		$values = array($menu_qryRs->cat_id, $menu_qryRs->sub_cat_id);
		
		$myimage = new ImageSnapshot; //new instance
		$myimage->ImageField	 = $_FILES['userfile']; //uploaded file array
		
		function GetFileExt($fileName){
			$ext = substr($fileName, strrpos($fileName, '.') + 1);
			$ext = strtolower($ext);
			return $ext;
		}

		/////////////////////////////////////////////////
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
				  
		if (isset($_REQUEST['submit'])){
		$errMessage ="";
		
		if($menu <= 0){
					$errMessage="Please Select Menu";		   
		}else if($item_title == ''){
					$errMessage="Please Enter Item Title";
		}else if($r_price == ''){
					$errMessage="Please Enter Regular Price";
		}else if (!empty($_FILES['userfile']['name']) && $myimage->ProcessImage() == false) {	
					 $errMessage="Pleaser Enter Valid Image"; 
		}else{
						if(!empty($_FILES['userfile']['name']))
							{	
								$path = '../images/item_images/';
								$exe = GetFileExt($_FILES['userfile']['name']);	
								$name = "img_".$pid."_prd.".$exe;
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
							}else{
								  
									$name = $logo;
								}
						  $r_price = str_replace("$",",",$r_price);
						   $r_price = explode(",",$r_price);
						   $r_price = trim(end($r_price));
						   if (strstr($r_price, ".")==FALSE) {
								$r_price = $r_price.".00";				
						   } else {
								$r_price = $r_price;						
							   if(substr($r_price,0,1) == '.'){
								 $r_price = '0'.$r_price;
							   } 
						   }
						   
						  		
						 //  if($feature_subcat == ''){ $feature_subcat=0; }
						 $feature_subcat=0; 
						   if($img_show_hide == ''){ $img_show_hide = 0;}
						   
							$item_title =  str_replace('"','&rdquo;',$item_title);
							$item_title =  str_replace('"','&rsquo;',$item_title);
							
							$item_des = str_replace('"','&rdquo;',$item_des);
							$item_des = str_replace("'",'&rsquo;',$item_des);							
						   //echo 'in = '.$img_show_hide;
                                                        Log::write("Update Product Status - tab_product_edit.php", "QUERY -- UPDATE product set cat_id = $catid, sub_cat_id = $menu,item_title = '".addslashes(trim($item_title))."', item_des = '".addslashes(trim($item_des))."', Ptitle = '".addslashes($p_title)."', Alt_tag = '".addslashes($alt_tags)."', Meta_des = '".addslashes($meta_des)."', Meta_tag = '".addslashes($meta_key)."', retail_price = '$r_price', item_image = '$name', feature_sub_cat = $feature_subcat,pos_id = '$pos_id' where prd_id = $pid", 'menu', 1 , 'cpanel'); 
						 mysql_query("UPDATE product set cat_id = $catid, sub_cat_id = $menu,item_title = '".addslashes(trim($item_title))."', item_des = '".addslashes(trim($item_des))."', Ptitle = '".addslashes($p_title)."', Alt_tag = '".addslashes($alt_tags)."', Meta_des = '".addslashes($meta_des)."', Meta_tag = '".addslashes($meta_key)."', retail_price = '$r_price', item_image = '$name', feature_sub_cat = $feature_subcat,pos_id = '$pos_id' where prd_id = $pid");
			?>
            
    <script language="javascript">
		window.location="?mod=menus&catid=<?=$catid?>";
	</script>
        
	<?								
			} // end of else
		}// end submit
?>
<script type="text/javascript" src="../js/my_ajax.js"></script>
<script language="javascript">
function populate(){
  var val = document.form1.main.value;
  makeRequest('get_subcat_ajax.php?cat='+val,'DIVSUBCATVALUE');
  }
  </script>
  		<?php 
			$menuQuery = mysql_query("SELECT cat_name FROM categories WHERE  cat_id = '".$scid."' AND parent_id = '".$catid."'");
			$menuRs = mysql_fetch_array($menuQuery);
		?>  
  
  		<strong><?=$menuRs['cat_name']; ?></strong>
<div id="main_heading">Edit Menu Item</div>
<div class="form_outer">
  <form name="form1" method="post" action="" enctype="multipart/form-data">
    <table width="500" border="0" cellpadding="4" cellspacing="0">
    <?=($errMessage != '')?"<div class=\"msg_error\">$errMessage</div>":"";?>
    
      <tr>          
        <td><strong>Menu Name:</strong><br />
         <select name="menu" id="menu" style="width:330px;">
            <option value="-1">===Select Menu===</option>
            <?=get_menu_drop_down($values) ?>
         </select> 
         </td>
        </tr>          
      <tr align="left" valign="top">
        <td colspan="3"><strong>Item Title:</strong><br />
  
        <input name="item_title" type="text" size="40" id="item_title" value="<?=stripslashes(stripcslashes($menu_qryRs->item_title))?>">&nbsp;&nbsp;&nbsp;<a href="admin_contents/products/delete_products.php?pid=<?=$menu_qryRs->prd_id?>&cid=<?=$menu_qryRs->cat_id?>" class="linktextbig" onClick="return confirm('Are you sure you would like to delete this product')">DELETE</a>         </td>
      </tr>
      <tr align="left" valign="top">
          <td colspan="3"><strong>Item Description:</strong><br>
            <em><font color="#666666">(To insert a new paragraph, enter &lt;P&gt;. 
            To bold text, surround text with &lt;B&gt;
            and &lt;/B&gt;. To italicize text, surround text with &lt;I&gt; 
            and &lt;/I&gt;.)</font> </em><br>
            <textarea name="item_des" cols="40" rows="6" id="item_des"><? echo stripslashes(stripcslashes($menu_qryRs->item_des))?>
            </textarea>          </td>
      </tr>
      <tr> 
          <td colspan="5" align="left" valign="top" class="bodytext"><strong>Page Title:</strong><br />
  
            <textarea name="p_title" cols="40" rows="2" id="p_title"><? echo stripslashes(stripcslashes($menu_qryRs->Ptitle))?>
            </textarea></td>
      </tr>
        <tr> 
          <td colspan="5" align="left" valign="top" class="bodytext"><strong>Image Alt Tag:</strong><br />
  
            <textarea name="alt_tags" cols="40" rows="2" id="alt_tags"><? echo stripslashes(stripcslashes($menu_qryRs->Alt_tag))?>
            </textarea></td>
        </tr>
        <tr> 
          <td colspan="5" align="left" valign="top" class="bodytext"><strong>META Description:</strong><br />
  
            <textarea name="meta_des" cols="40" rows="4" id="meta_des"><? echo stripslashes(stripcslashes($menu_qryRs->Meta_des))?>
            </textarea></td>
        </tr>
        <tr> 
          <td colspan="5" align="left" valign="top" class="bodytext"><strong>META Keywords:</strong><br />
  
            <textarea name="meta_key" cols="40" rows="5" id="meta_key"><? echo stripslashes(stripcslashes($menu_qryRs->Meta_tag))?>
            </textarea></td>
        </tr>
      <tr align="left" valign="top">
        <td colspan="3"><strong>Price:</strong>
        <font color="#666666">(ex. <?=$currency?>10.00)</font>      <br>      <input name="r_price" type="text" size="20" id="r_price" value="<?=$menu_qryRs->retail_price?>">          </td>
      </tr>
       <tr align="left" valign="top">
        <td colspan="2"><strong>Item Image:</strong><br />
        <input name="userfile" type="file" id="userfile"></td>
      </tr>
      <tr align="left" valign="top">
        <td colspan="2"><strong>POS ID:</strong><br />
        <input name="pos_id" type="text" id="pos_id" value="<?=($menu_qryRs->pos_id)?>"></td>
      </tr>
      <tr align="left" valign="top">
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
     
      <tr align="left" valign="top">
        <td colspan="3">            <input type="submit" name="submit" value="Save Changes">
          <input type="hidden" name="pid" id="pid" value="<?=$pid?>"/>
        <input type="hidden" name="catid" id="catid" value="<?=$catid?>"/>
        <input type="hidden" name="logo" value="<?=$menu_qryRs->item_image?>">
        </td>
      </tr>
    </table>
  </form>
</div>
