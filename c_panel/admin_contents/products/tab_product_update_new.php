<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") 
{
?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.6.2.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.js"></script>
<?php
}
else
{
?>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.js"></script>
<?php
}
?>
<script type="text/javascript" src="js/new-menu.js<?php echo $jsParameter;?>"></script>
<link rel="stylesheet" type="text/css" href="css/new_menu.css<?php echo $jsParameter;?>">
<script src="../js/mask.js" type="text/javascript"></script>
<script src="../js/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/tab.css">
<script src="js/fancybox.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/fancy.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="js/block.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/darktooltip.min.css">
<script src="js/darktooltip.min.js"></script>
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="css/component.css" />
<script src="js/modernizr.custom.js"></script>
<script src="js/classie.js"></script>
<script src="js/jquery.noty.packaged.min.js" type="text/javascript"></script>
<script src="js/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="css/jquery.Jcrop.css">
<link rel="stylesheet" href="css/demos.css">
<script src="js/jquery.Jcrop.js"></script>
<script language="Javascript">
   var jcrop_api;
   
    $(document).ready(function(){
             $('#item_img').Jcrop({ addClass: 'jcrop-centered',aspectRatio: 1, onSelect: updateCoords,maxSize: [ 500, 500 ]
            },function(){
            jcrop_api = this;
          });
        
    });


    initJcrop();
    function initJcrop()
    {
        jcrop_api = $.Jcrop('#item_img');
        console.log(jcrop_api);
    };
     function nothing(e) {
        e.stopPropagation();
        e.preventDefault();
        return false;
    };

    // Returns event handler for animation callback
    function anim_handler(ac) {
        return function(e) {
            api.animateTo(ac);
            return nothing(e);
        };
    };
    function updateCoords(c) {
        $('#x').val(c.x);
        $('#y').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
    };
    function checkCoords() {
        if (parseInt($('#w').val()))
            return true; alert('Select to crop.');
        return false;
    };


</script>
<?php 
if (isset($_GET['prd_id'])) {
    $prd_id = $_GET['prd_id'];

    $prd_data = dbAbstract::ExecuteObject("Select * from product where prd_id = " . $prd_id . "",1);
    $item_name = stripcslashes($prd_data->item_title);
    $price = $prd_data->retail_price;
    $price = currencyToNumber($price);
    $description = $prd_data->item_des;
    $description1 = $prd_data->item_des;
    $imgSource = $prd_data->item_image;
    if (strtolower(substr(php_uname('s'), 0, 3))=="win")
    {
        $description1 = str_replace("<br/>", "\r\n", $description1);
        $description1 = str_replace("<br>", "\r\n", $description1);
        $description1 = str_replace("<br />", "\r\n", $description1);
    }
    else if (strtolower(php_uname('s'))=='linux')
    {
        $description1 = str_replace("<br/>", "\n", $description1);
        $description1 = str_replace("<br>", "\n", $description1);
        $description1 = str_replace("<br />", "\n", $description1);
    }
    else if (strtolower(php_uname('s'))=='unix')
    {
        $description1 = str_replace("<br/>", "\n", $description1);
        $description1 = str_replace("<br>", "\n", $description1);
        $description1 = str_replace("<br />", "\n", $description1);
    }
    else if (strtolower(substr(php_uname('s'), 0, 6))=="darwin")
    {
        $description1 = str_replace("<br/>", "\r", $description1);
        $description1 = str_replace("<br>", "\r", $description1);
        $description1 = str_replace("<br />", "\r", $description1);
    }
    else if (strtolower(substr(php_uname('s'), 0, 3))=="mac")
    {
        $description1 = str_replace("<br/>", "\r", $description1);
        $description1 = str_replace("<br>", "\r", $description1);
        $description1 = str_replace("<br />", "\r", $description1);
    }
    else
    {
        $description1=str_replace ("<br/>", "\n", $description1);
        $description1=str_replace ("<br>", "\n", $description1);
        $description1=str_replace ("<br />", "\n", $description1);
    }

    if ((($_SESSION['admin_type'] == 'admin') || ($_SESSION['admin_type'] == 'bh')) && ($Objrestaurant->bh_restaurant=='1'))
    {
		$description = preg_replace_callback('|@[0-9]+|', 
			function ($matches) {
				$code = str_replace('@','',$matches[0]);
				$result=dbAbstract::ExecuteObject("SELECT * FROM bh_items where ItemCode='$code' order by id desc limit 1");
				$E = '<a contenteditable="false" href="#" style="color: #0066CC;"><i></i>'.$result->ItemName.'</a>';
				return $E;
			}, $description);
    }
    
	$size = getimagesize("../images/item_images/". $imgSource);
	
	if (($size[0]>=450) || ($size[1]>=450))
	{
		$mWidth = round($size[0]/4.5);
		$mHeight = round($size[1]/4.5);
		$mScale = 4.5;
	}
	else if ((($size[0]<450) && ($size[0]>=400)) || (($size[1]<450) && ($size[1]>=400)))
	{
		$mWidth = round($size[0]/4);
		$mHeight = round($size[1]/4);
		$mScale = 4;
	}
	else if ((($size[0]<400) && ($size[0]>=300)) || (($size[1]<400) && ($size[1]>=300)))
	{
		$mWidth = round($size[0]/3.5);
		$mHeight = round($size[1]/3.5);
		$mScale = 3.5;
	}
	else if ((($size[0]<300) && ($size[0]>=250)) || (($size[1]<300) && ($size[1]>=250)))
	{
		$mWidth = round($size[0]/3);
		$mHeight = round($size[1]/3);
		$mScale = 3;
	}
	else if ((($size[0]<250) && ($size[0]>=220)) || (($size[1]<250) && ($size[1]>=220)))
	{
		$mWidth = round($size[0]/2.5);
		$mHeight = round($size[1]/2.5);
		$mScale = 2.5;
	}																							
	else if ((($size[0]<220) && ($size[0]>=190)) || (($size[1]<220) && ($size[1]>=190)))
	{
		$mWidth = round($size[0]/2);
		$mHeight = round($size[1]/2);
		$mScale = 2;
	}
	else if ((($size[0]<190) && ($size[0]>=120)) || (($size[1]<190) && ($size[1]>=105)))
	{
		$mWidth = round($size[0]/1.5);
		$mHeight = round($size[1]/1.5);
		$mScale = 1.5;
	}
	else
	{
		$mWidth = round($size[0]/1);
		$mHeight = round($size[1]/1);
		$mScale = 1;
	}

    $pos_id =$prd_data->pos_id;
    /*if($imgSource != '')
    {
        $size = getimagesize("../images/item_images/". $imgSource);
        
        if($size[0] > 160)
        {   
            $image = new SimpleImage();
            $image->load("../images/item_images/". $imgSource);
            $image->resize(160,100);
            $image->save("../images/item_images/". $imgSource);
        }
    }*/
    $type = $prd_data->item_type;

    $cat_data = dbAbstract::ExecuteObject("Select menu_id,cat_name from categories where cat_id = " . $prd_data->sub_cat_id . "",1);
    $cat_id = $prd_data->sub_cat_id;
    $cat_name = stripcslashes($cat_data->cat_name);
    
    $menu_data = dbAbstract::ExecuteObject("Select id,menu_name from menus where id = " . $cat_data->menu_id . "",1);
    $menuid=$menu_data->id;
    $menu_name = stripcslashes($menu_data->menu_name);
}
?>

<script type="text/javascript">
    $(document).ready(function()
    {
        $("#btnCancelProduct").click(function()
        {
            window.location.href = $("#redirectMenuPage").attr('href');
        });
    });
	
    $(function() {
        $("#update_item_form").validate({
            rules: {
                item_name: {required: true},
                price: {required: true,maxlength: 8}
            },
            messages: {
                item_name: {
                    required: "please enter your email address",
                    email: "please enter a valid email address"
                },
                price: {
                    required: "please enter your password",
                    minlength: "your enter a valid pa ssword"
                }
            },
            errorElement: "br",
            errorClass: "alert-error"
        });

        $(".tab_content_product").hide();
        $(".tab_content_product:first").show();

        $("ul.tabs_product li").click(function() {
            $("ul.tabs_product li").addClass("active_product");
            $(this).removeClass("active_product");
            $(".tab_content_product").hide();
            var activeTab = $(this).attr("rel");
            $("#"+activeTab).fadeIn();
        });

        //var cat_id = $("#restaurantid").val();
        //var prd_id = GetURLParameter('prd_id');

        // Open Fancy Box for add related Item
		var checkflag = 0;
		$("#RelatedItemLi").click(function(){
			$("#typeRelatedItemName option:first").attr('selected','selected');
			$("#typeRelatedItemName").trigger('liszt:updated');
			if(checkflag==0)
			{
				loadAllRelatedItem();
				checkflag=1;
			}
			$("#mainFancyBox").slideDown(500);
			$("#displayMessage").hide();
	
		//$("#popup").show();
		});
		
        var flag = 0;
		$("#ExistingAttributeLi").click(function(){
				loadAttribute();
				flag=1;
				$("#addAttributetxt option:first").attr('selected','selected');
				$("#addAttributetxt").trigger('liszt:updated');
				$(".fancyAddAttribute").fancybox().click();
				$(".background_overlay").show();
				$("#attrDisplayMessage").hide();
		});
	});
</script>

<style>
.alert-error {
	background-color: #f2dede;
	border-color: #eed3d7;
	color: #b94a48;
	text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
input[type=text].alert-error, input[type=select].alert-error, input[type=password].alert-error {
	background-color: #F99;
	border: 1px solid #D92353;
	border-image: initial;
}
.AddAttributeHeader {
	background-color: #25AAE1;
	width: 392px;
	padding: -1px;
	text-align: left;
	font-size: 20px;
	color: white;
	border-radius: 3.5px;
	padding-left: 8px;
}
.BodyContainer {
	height: 1050px;
	background-repeat: no-repeat !important;
}
.jcrop-centered {
	display: inline-block;
}
</style>
<!DOCTYPE html>
<html>
  <body>
	<form method ="post" id="update_item_form"  action="" enctype="multipart/form-data">
  	  <div id ="main_div" class="main_div">
        <div id ="inner_div">
          <input type="hidden" value="<?=$Objrestaurant->id?>" id="restaurantid"/>
          <input type="hidden" value="<?=$_GET['prd_id']?>" id="prd_id"/>
          <input type="hidden" value="<?=$_GET['sub_cat']?>" id="hdn_subcatid"/>
          
          <a href="?mod=new_menu&catid=<?=$Objrestaurant->id?>&menuid=<?=$menuid?>&menu_name=<?=$menu_name?>" style="margin-top: 3px;margin-left: 16px;cursor: pointer;width: 37px;float: left;" id="redirectMenuPage">
            <img src="img/back.png" style="margin-top: 10px;" />
          </a>
          <div  class="items_root">
            <?= $menu_name." - " .$cat_name." - ". $item_name ?>
          </div>
          
          <div class="Submenu_Heading">Edit
            <?= $item_name ?>
          </div>
          
          <div class="add_area_div">
            <table style="width: 85%; margin: 0px;margin-left: 21px;" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td><input type="text" id="item_name" name="item_name" style="margin-left: 13%;margin-top: 30px;width:85%;padding:8px" <?php if(isset($_GET['sandwichId'])){ ?> readonly <?php } ?> value="<?= $item_name ?>" class="textAreaClass" placeholder="Item Name"  maxlength="40"></td>
                <td><input onblur="$('#price').attr('placeholder','Price(ex:<?=$currency?>4.50)');" maxlength="7" type="text" id="price" name="price" style="margin-left: 18%;margin-top: 30px;width: 90%;padding: 8px;"  <? if (strpos($price, '.') !== false) { ?> value="<?= $currency . $price ?>" <? } else { ?> value="<?= $currency . $price . ".00" ?>" <? } ?> class="textAreaClass" placeholder="Price(ex:<?=$currency?>4.50)"></td>
              </tr>
              
              <tr>
                <td>
				<?php
				if ((($_SESSION['admin_type'] == 'admin') || ($_SESSION['admin_type'] == 'bh')) && ($Objrestaurant->bh_restaurant=="1"))
				{
				?>
                  <script type="text/javascript">
				  	$(document).ready(function()
				  	{
						var start=/@/ig; // @ Match
						
						$("#product_description1").blur(function()
						{
							if ($.trim($("#product_description1").text())=="")
							{
								$("#product_description1").text("Description of Item");
								$("#product_description1").css("color", "#917591");
							}
						});
						
						$("#product_description1").focus(function()
						{
							if ($.trim($("#product_description1").text())=="Description of Item")
							{
								$("#product_description1").text("");
								$("#product_description1").css("color", "#000000");
							}
						});
						
						$("#product_description1").live("keydown",function(e)
						{
							// trap the return key being pressed
							/*if (jQuery.browser.safari)
							{
								if (e.keyCode === 13) {
								  // insert 2 br tags (if only one br tag is inserted the cursor won't go to the next line)
								  document.execCommand('insertHTML', false, '<br>');
								  // prevent the default behaviour of return key pressed
								  return false;
								}
							}*/
						});
						
						$("#product_description1").live("keyup",function(e)
						{
							var search = "";
							var content=$(this).text(); //Content Box Data
							if (content=="")
							{
								$("#display").hide();
								$("#product_description").val("");
								return;
							}                                       
							
							search = content.substring(content.indexOf("@"));
							
							if (search.indexOf(" ")>=0)
							{   
								search = search.substring(0, search.indexOf(" "));
							}
							
							if (search.indexOf(",")>=0)
							{   
								search = search.substring(0, search.indexOf(","));
							}
							
							search = $.trim(search);
							
							var go= content.match(start); //Content Matching @

							var dataString = 'searchword='+ search;
							
							if (go)
							{
								if(go.length>0)
								{
									if (e.keyCode==32)
									{
										if ((search!="") && (search!="@"))
										{
											$("#hdnSearch").val(search);
											setTimeout(function()
											{
												$.ajax({
													type: "POST",
													url: "admin_contents/products/ajax.php?sku1=1", // Database name search
													data: dataString,
													cache: false,
													success: function(data)
													{
														if ($.trim(data)!="")
														{
															var E='<a contenteditable="false" href="#" style="color: #0066CC;"><i></i>'+data+'</a>';
															
															$("#product_description1").html($("#product_description1").html().replace($("#hdnSearch").val(), E));
															placeCaretAtEnd(document.getElementById("product_description1"));
															
															tmp_html = $("#product_description1").html();
															$("#product_description2").val(tmp_html.replace("'", "&#39;").replace("®", "&#174;").replace("ä", "&#228;").replace("è", "&#232;").replace("è", "&#232;").replace("ñ", "&#241;").replace('&#38;',"&").replace("™","&#8482;").replace("'","&#39;").replace("©","&#169;").replace("Ã","&#195;"));
															
															mTmpHTML = removeAnchors($("#product_description2").val());
															$("#product_description").val(mTmpHTML);
															$("#bh_item").attr('checked', true);
														}
													}
												});
											}, 100);
										}
									}
								}
								else
								{
									tmp_html = $("#product_description1").html();
									$("#product_description2").val(tmp_html.replace("'", "&#39;").replace("®", "&#174;").replace("ä", "&#228;").replace("è", "&#232;").replace("è", "&#232;").replace("ñ", "&#241;").replace('&#38;',"&").replace("™","&#8482;").replace("'","&#39;").replace("©","&#169;").replace("Ã","&#195;"));
									
									mTmpHTML = removeAnchors($("#product_description2").val());
									$("#product_description").val(mTmpHTML);
								}
							}
							else
							{
								tmp_html = $("#product_description1").html();
									$("#product_description2").val(tmp_html.replace("'", "&#39;").replace("®", "&#174;").replace("ä", "&#228;").replace("è", "&#232;").replace("è", "&#232;").replace("ñ", "&#241;").replace('&#38;',"&").replace("™","&#8482;").replace("'","&#39;").replace("©","&#169;").replace("Ã","&#195;"));
									
									mTmpHTML = removeAnchors($("#product_description2").val());
									$("#product_description").val(mTmpHTML);
							}
							
							
							if ($("#product_description1").html().indexOf("<a ")<0)
							{
								$("#bh_item").attr('checked', false);
							}
							
							return false;
						});
						
						function removeAnchors(pStr)
						{
							mTmpHTML = pStr;
							if (mTmpHTML.indexOf("<a")!=-1)
							{
								mStr = mTmpHTML.substring(mTmpHTML.indexOf("<a"), mTmpHTML.indexOf("</i>") + 4);

								mTmpHTML = mTmpHTML.replace(mStr, "").replace("</a>","");
								if (mTmpHTML.indexOf("</i>")!=-1)
								{
									mTmpHTML = removeAnchors(mTmpHTML);
								}

								//mTmpHTML =  removeSpans(mTmpHTML);
							}
							return mTmpHTML;
						}
						
						function removeSpans(pStr)
						{
							mTmpHTML = pStr;
							if (mTmpHTML.indexOf("<span")!=-1)
							{
								mStr = mTmpHTML.substring(mTmpHTML.indexOf("<span"), mTmpHTML.indexOf("</span>") + 7);

								mTmpHTML = mTmpHTML.replace(mStr, "");

								if (mTmpHTML.indexOf("</span>")!=-1)
								{
									mTmpHTML = removeSpans(mTmpHTML);
								}
							}
							return mTmpHTML;
						}
						
						function placeCaretAtEnd(el) {
							el.focus();
							if (typeof window.getSelection != "undefined"
									&& typeof document.createRange != "undefined") {
								var range = document.createRange();
								range.selectNodeContents(el);
								range.collapse(false);
								var sel = window.getSelection();
								sel.removeAllRanges();
								sel.addRange(range);
							} else if (typeof document.body.createTextRange != "undefined") {
								var textRange = document.body.createTextRange();
								textRange.moveToElementText(el);
								textRange.collapse(false);
								textRange.select();
							}
						}
					});
				  </script>
                  <textarea id="product_description" name="product_description" style="display: none;"><?= trim($description1) ?></textarea>
                  <input type="hidden" id="hdnSearch" />
                  <div id="container">
                    <div id="product_description1" name="product_description1" <?php if(isset($_GET['sandwichId'])){ ?> contenteditable="false" <?php }else{ ?> contenteditable="true" <?php }?> class="textAreaClass" style="overflow: auto; font-size: 15px; font-family: Arial; background-color: white; border: 1px solid #A9A9A9; margin-left: 13%;resize: none;margin-top: 30px;width: 85%;height: 133px;padding: 8px;">
                      <?= trim($description) ?>
                    </div>
                    <div id='display' style="background-color: #FFF8DC; margin-left: 4%; margin-top: 1px; position: absolute; width: 25%; z-index: 2;"> </div>
                  </div>
                <?php
				}else{
				?>
                  <textarea rows="4" cols="50" id="product_description" name="product_description" <?php if(isset($_GET['sandwichId'])){ ?> readonly <?php } ?> class="textAreaClass" style="margin-left: 13%;resize: none;margin-top: 30px;width: 85%;height: 133px;padding: 8px;" placeholder="Description of Item"><?= trim($description1) ?></textarea>
                <?php
				}
                ?>
                <input type="hidden" name="product_description2" id="product_description2">
                </td>
                <td>
                  <div class="photo_div_before" id="show_photo_before" <?=(($imgSource != '')? 'style="display:none;"':'')?>>
                    <div style="text-align: center;font-size: 16px;"  > Photo of item? </div>
                    <div style="height: 75px;width: 28%;"> 
                      <img src ="img/camera.png" id="item_img_camera" style="height: 79%;margin-left: 131%;margin-top: 8px;"/> 
                    </div>
                    
                    <div style="text-align: center;text-align: center;width: 150px;margin-left: 15%;margin-top: 0px"> 
                      (Click to upload or drag and drop file here) 
                    </div>
                  </div>
                  
                  <div class="photo_div" id="show_photo" <?=(($imgSource != '')? 'style="display:block;"':'')?>>
                    <div style="text-align: center;font-size:16px"> Photo of item </div>
                    <div style="text-align:center;"> <img src ="../images/item_images/<?php echo $imgSource; ?>?time=<?php echo time() ?>" id="item_img" style="margin-top: 9px; width: <?=$mWidth?>px; height: <?=$mHeight?>px;"/>
                      <input type="hidden" id="hdnScale" name="hdnScale" value="<?=$mScale?>" />
                    </div>
                  </div>
                  <?php if(!isset($_GET['sandwichId'])){?>
                    <input name="userfile" type="file" id="userfile" size="30" style="margin-left: 4%;margin-top: -160px;opacity: 0;position: absolute;height: 165px;cursor: pointer" title=" ">
                 <?php } ?>
                </td>
              </tr>
              
              <tr>
                <td>
                  <input type="text" name="pos_id" id="pos_id" placeholder="POS ID" class="textAreaClass" style="margin-left: 13%;margin-top: 25px;padding: 5px;" value="<?= $pos_id ?>"/>
                </td>
                <td style="width:290px">
                    <?php if(!isset($_GET['sandwichId'])){ ?>
                  <div id="sizeErrorMsg1" style="color: green;font-size: 13px;margin-left: 52px;">File Size must under 1MB</div>
                  <script type="text/javascript" language="javascript">
					$("#upSocialMeida").fancybox
					({
						afterClose: function() 
						{
							emptyFancyBoxFields();
						}
					});
				  </script>
                  <div style="margin-left: 52px;"> 
                    <a href="#socialmedia" id="upSocialMeida" style="font-weight: bold; text-decoration: none; color: #06C;">
                      <table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td><img src="images/socialmedia.png" alt="Social Media" /></td>
                          <td valign="top" style="vertical-align: top !important;"> Have Pictures on Social Media? </td>
                        </tr>
                      </table>
                    </a> 
                  </div>
                 
                  <span class="deleteimg" <?=(($imgSource == '')? 'style="display:none;"':'')?> id="deleteimg">Delete Photo</span>
                  <input type="hidden" id="x" name="x" />
                  <input type="hidden" id="y" name="y" />
                  <input type="hidden" id="w" name="w" />
                  <input type="hidden" id="h" name="h" />
                  <span id="cropimg" name="cropimg" class="deleteimg" style=" <?=(($imgSource == '')? 'display:none;':'margin-left: 10px;')?>">
                  	Crop Image
                  </span>
                   <?php } ?>
                </td>
              </tr>
              
              <tr>
                <td></td>
                <td>
                  <div id="socialmedia" style="display: none; width: 830px; height: 600px;">
                    <div style="text-align: center !important;" align="center"> 
                      <script type="text/javascript" language="javascript">
						$(document).ready(function()
						{
							$("#imgFlickr").die().live("click", function() 
							{
								if ($("#dvFlickr").css("display")=="none")
								{
									$("#dvFlickr").show();
									$("#dvAlbums").hide();
									$("#dvImages").html('');
								}
								else
								{
									$("#dvFlickr").hide();
								}
							});
						});
					  </script> 
                      <span style="font-weight: bold; font-size: 16px;">Have Pictures on Social Media You'd Like to Use?</span><br />
                      <span style="font-size: 16px;">Connect to Your Social Account! Click Below</span><br />
                      <br />
                      <img src="images/flickr.png" alt="Flickr" id="imgFlickr" title="Flickr" style="cursor: hand; cursor: pointer;" /> &nbsp;&nbsp; 
                      <img src="images/facebook.png" alt="Facebook" title="Facebook" style="cursor: hand; cursor: pointer;"  onclick="$('#dvFlickr').hide();$('#dvAlbums').html('');$('#dvImages').html('');FB.login(null, {scope: 'user_photos'});" /> 
                    </div>
                    <!--------------------------------------------------------------------------------------------------------------> 
                    <br />
                    <br />
                    
                    <div style="display: none; text-align: center;" align="center" id="dvFlickr"> 
                      <script type="text/javascript" language="javascript">
						function radioClickedPhotoFlickr(pPhotoSource){
							$("#hdnPhotoSourceFlickr").val(pPhotoSource);
						}
						
						function goClickPhotoFlickr(){
							mPhotoSource = 	$("#hdnPhotoSourceFlickr").val();
							if ($.trim(mPhotoSource)!="")
							{
								var mRandom = Math.floor((Math.random()*1000000)+1); 
								var mUrl = "admin_contents/products/ajax.php?importimage=1&rndm="+mRandom;
								$.ajax({
									url: mUrl,
									type: 'POST',
									data: {'imageUrl': $('#hdnPhotoSourceFlickr').val()},
									success: function(data)
									{
										if(data!='')
										{
											mTmpArr = data.split("~");
											mImageSrc = mTmpArr[0];
											mWidth = mTmpArr[1];
											mHeight = mTmpArr[2];
											
											$("#show_photo").show();
											$("#show_photo_before").hide();
											$("#item_img").attr("src","../c_panel/tempimages/"+mImageSrc+"?rndm="+mRandom);
											$.fancybox.close();
											$("#deleteimg").show();
											$("#cropimg").show();
											$("#cropimg").css('margin-left','10px');
											$('.form-progress-wrapper').hide();
											$("#show_photo_before").css('opacity','1.5');
											$("#show_photo").css('opacity','1.5');
											$('.jcrop-holder img').css("height","100px");
											$('.jcrop-holder img').css("width","100px");
											$('.jcrop-centered').css("height","100px");
											$('.jcrop-centered').css("width","100px");
											if ($('#item_img').data('Jcrop') != null) 
											{
												$('#item_img').data('Jcrop').destroy();
											}

											if ((mWidth>=450) || (mHeight>=450))
											{
												$('#item_img').css("width",Math.round(mWidth/4.5)+"px");
												$('#item_img').css("height",Math.round(mHeight/4.5)+"px");
												$('#hdnScale').val('4.5');
											}
											else if (((mWidth<450) && (mWidth>=400)) || ((mHeight<450) && (mHeight>=400)))
											{
												$('#item_img').css("width",Math.round(mWidth/4)+"px");
												$('#item_img').css("height",Math.round(mHeight/4)+"px");;
												$('#hdnScale').val('4');
											}
											else if (((mWidth<400) && (mWidth>=300)) || ((mHeight<400) && (mHeight>=300)))
											{
												$('#item_img').css("width",Math.round(mWidth/3.5)+"px");
												$('#item_img').css("height",Math.round(mHeight/3.5)+"px");;
												$('#hdnScale').val('3.5');
											}
											else if (((mWidth<300) && (mWidth>=250)) || ((mHeight<300) && (mHeight>=250)))
											{
												$('#item_img').css("width",Math.round(mWidth/3)+"px");
												$('#item_img').css("height",Math.round(mHeight/3)+"px");
												$('#hdnScale').val('3');
											}
											else if (((mWidth<250) && (mWidth>=220)) || ((mHeight<250) && (mHeight>=220)))
											{
												$('#item_img').css("width",Math.round(mWidth/2.5)+"px");
												$('#item_img').css("height",Math.round(mHeight/2.5)+"px");
												$('#hdnScale').val('2.5');
											}																							
											else if (((mWidth<220) && (mWidth>=190)) || ((mHeight<220) && (mHeight>=190)))
											{
												$('#item_img').css("width",Math.round(mWidth/2)+"px");
												$('#item_img').css("height",Math.round(mHeight/2)+"px");
												$('#hdnScale').val('2');
											}
											else if (((mWidth<190) && (mWidth>=120)) || ((mHeight<190) && (mHeight>=105)))
											{
												$('#item_img').css("width",Math.round(mWidth/1.5)+"px");
												$('#item_img').css("height",Math.round(mHeight/1.5)+"px");
												$('#hdnScale').val('1.5');
											}
											else
											{
												$('#item_img').css("width",Math.round(mWidth)+"px");
												$('#item_img').css("height",Math.round(mHeight)+"px");
												$('#hdnScale').val('1');
											}

											$('.jcrop-holder img').attr("src","../c_panel/tempimages/"+mImageSrc+"?rndm="+mRandom);
											$('#item_img').Jcrop
											({
												addClass: 'jcrop-centered',aspectRatio: 1, onSelect: updateCoords,maxSize: [ 500, 500 ]
											});
										}
									},
									error: function(data)
									{
										alert('Error occurred.');
									}
								});
							}
							else
							{
								alert('No Photo selected.');
							}
						}
						
						function jsonFlickrFeed(o){
							var mCount = 0;
							mHTML = "<br /><br /><table style='margin-top: 25px; width: 100%; font-family: Arial; size: 14px;' border='0' cellpadding='0' cellspacing='0'><tr style='height: 40px;'><td colspan='6'><span style='font-size: 18px; font-weight: bold; color: navy;'>Select Photo</span>&nbsp;<i>(After selection Click Import given at bottom.)</i><input type='hidden' id='hdnPhotoSourceFlickr' /></td><tr style='height: 40px;'>";
							while(o.items[mCount])
							{
								if ((mCount>0) && (mCount%2==0))
								{
									mHTML = mHTML+"</tr><tr style='height: 15px;'><td colspan='6'></td></tr><tr'>";
								}
								mHTML = mHTML+"<td style='width: 1%;'></td><td style='width: 4%;' valign='top'><input type='radio' onclick='radioClickedPhotoFlickr(\""+o.items[mCount].media.m.replace("_m", "")+"\");' name='photolist' id='rbPhotoFlickr"+mCount+"' name='rbPhotoFlickr"+mCount+"' value='"+mCount+"' /></td><td style='width: 45%'><img width='288px' height='168px' src='"+o.items[mCount].media.m+"' alt='"+o.items[mCount].title+"' /></td>";
								//$("#dvImages").append('<img src="' + o.items[mCount].media.m.replace("_m", "")	+ '" alt="' + o.items[mCount].title +'">');
								mCount++;
							}
							
							if (mCount==0)
							{
								mHTML = mHTML+"<td colspan='6'>No Photo found.</td></tr></table>";
							}
							else
							{
								mHTML = mHTML+"</tr><tr style='height: 40px;'><td colspan='6'><input class='btnadd' onclick='goClickPhotoFlickr();' type='button' id='btnGoPhotoFlickr' name='btnGoPhotoFlickr' value='Import' size='60' style='margin-left: 0px !important; width: 90px !important;' /></td></tr></table>";
							}
							$("#dvImages").html(mHTML);
							$("#imgAjaxFlickr").hide();
						}
						
						function FlickrImport(){
							$("#imgAjaxFlickr").show();
                                                        <?php
                                                        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") 
                                                        {
                                                        ?>
							$.getScript("https://api.flickr.com/services/feeds/photos_public.gne?id="+$("#txtFlickrID").val()+"&lang=en-us&format=json");
                                                        <?php
                                                        }
                                                        else
                                                        {
                                                        ?>
                                                        $.getScript("http://api.flickr.com/services/feeds/photos_public.gne?id="+$("#txtFlickrID").val()+"&lang=en-us&format=json");
                                                        <?php
                                                        }
                                                        ?>
						}
					  </script>
                      
                      <span style="font-size: 16px;">Enter your Flickr ID:</span>&nbsp;&nbsp;
                      <input type="text" id="txtFlickrID" style="width: 200px; height: 30px; font-size: 16px;"/>
                      &nbsp;&nbsp;&nbsp;
                      <input class='btnadd' onclick='FlickrImport();' type='button' id='btnGoFlickr' name='btnGoFlickr' value='Show Photos' size='60' style='margin-left: 0px !important; width: 90px !important;' />
                      &nbsp;
                      <img src="images/ajax.gif" alt="Processing" style="display: none;" id="imgAjaxFlickr" /> 
                      <span style="font-size: 14px; float: left !important; margin-top: 15px !important">
                        <strong>Flickr ID: </strong>&nbsp;When you visit your flickr photostream, then browser's URL must be like
                      </span><br />
                      
                      <img src="images/flickrurl.jpg" alt="Flickr" style="float: left !important; margin-top: 5px !important;"/> <br />
                      <span style="font-size: 14px; float: left !important; margin-top: 10px !important">
                        Yellow highlight part will be your Flickr ID.
                        <strong>&nbsp;OR&nbsp;</strong>You can use 
                        <a href="http://idgettr.com/" target="_blank">http://idgettr.com/</a> to know your Flickr ID
                      </span>
                    </div>
                    
                    <div id="fbLogin">
                      <div id="fb-root"></div>
                      <script>
						window.fbAsyncInit = function() 
						{
							FB.init
							({
								appId      : '597714500283054', //569304429756200
								status     : false, // check login status
								cookie     : true, // enable cookies to allow the server to access the session
								xfbml      : true  // parse XFBML
							});
							
							FB.Event.subscribe('auth.statusChange', function(response) 
							{
								if (response.status === 'connected') 
								{
									checkAssociation();
								} 
								else if (response.status === 'not_authorized') 
								{
									FB.login(function(response) 
									{
										if (response.authResponse) // connected
										{
											checkAssociation();
										} 
										else 
										{
											// cancelled
										}
									});
								} 
								else 
								{
									FB.login(function(response) 
									{
										if (response.authResponse) // connected
										{
											checkAssociation();
										} 
										else 
										{
											// cancelled
										}
									});
								}
							});
						};
						
						// Load the SDK asynchronously
						(function(d)
						{
							var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
							if (d.getElementById(id)) {return;}
							js = d.createElement('script'); js.id = id; js.async = true;
							js.src = "//connect.facebook.net/en_US/all.js";
							ref.parentNode.insertBefore(js, ref);
						 }
						 (document));
						
						function checkAssociation() 
						{
							
							FB.api('/me/albums', function(response) 
							{
								mCount = 0;
								mHTML = "<table style='width: 100%; font-family: Arial; size: 14px;' border='0' cellpadding='0' cellspacing='0'><tr style='height: 40px;'><td colspan='3'><span style='font-size: 18px; font-weight: bold; color: navy;'>Select Album</span><input type='hidden' id='hdnAlbumID' /></td><tr style='height: 40px;'>";
								response.data.forEach(function(pRow) 
								{
									if ((mCount>0) && (mCount%3==0))
									{
										mHTML = mHTML+"</tr><tr style='height: 40px;'>";
									}
									mHTML = mHTML+"<td style='width: 33%;'><input type='radio' onclick='radioClickedAlbum("+pRow.id+");' name='albumlist' id='rbAlbum"+mCount+"' name='rbAlbum"+mCount+"' value='"+pRow.id+"' />"+pRow.name+"</td>";
									mCount++;
								});
								if (mCount==0)
								{
									mHTML = mHTML+"<td colspan='3'>No Album found.</td></tr></table>";
								}
								else
								{
									mHTML = mHTML+"</tr><tr style='height: 40px;'><td colspan='3'><input onclick='goClickAlbum();' type='button' id='btnGo' name='btnGo' value='Show Photos' size='40' class='btnadd' style='margin-left: 0px !important; width: 120px !important;' />&nbsp;<img src='images/ajax.gif' alt='Processing' style='display: none;' id='imgAjax' /></td></tr></table>";
								}
								$("#dvAlbums").html(mHTML);
							}, {scope: 'user_photos'});
						}
						
						function radioClickedAlbum(pAlbumID)
						{
							$("#hdnAlbumID").val(pAlbumID);
						}
						
						function goClickAlbum()
						{
							$("#imgAjax").show();
							mAlbumID = 	$("#hdnAlbumID").val();
							if ($.trim(mAlbumID)!="")
							{
								FB.api('/'+mAlbumID+'/photos', function(response) 
								{
									mCount = 0;
									mHTML = "<br /><br /><table style='width: 100%; font-family: Arial; size: 14px;' border='0' cellpadding='0' cellspacing='0'><tr style='height: 40px;'><td colspan='6'><span style='font-size: 18px; font-weight: bold; color: navy;'>Select Photo</span>&nbsp;<i>(After selection Click Import given at bottom.)</i><input type='hidden' id='hdnPhotoID' /><input type='hidden' id='hdnPhotoSource' /></td><tr style='height: 40px;'>";
									response.data.forEach(function(pRow) 
									{
										if ((mCount>0) && (mCount%2==0))
										{
											mHTML = mHTML+"</tr><tr style='height: 15px;'><td colspan='6'></td></tr><tr'>";
										}
										mHTML = mHTML+"<td style='width: 1%;'></td><td style='width: 4%;' valign='top'><input type='radio' onclick='radioClickedPhoto("+pRow.id+", \""+pRow.source+"\");' name='photolist' id='rbPhoto"+mCount+"' name='rbPhoto"+mCount+"' value='"+pRow.id+"' /></td><td style='width: 45%'><img width='288px' height='168px' src='"+pRow.source+"' alt='"+pRow.Name+"' /></td>";
										mCount++;
									});
									if (mCount==0)
									{
										mHTML = mHTML+"<td colspan='6'>No Photo found.</td></tr></table>";
									}
									else
									{
										mHTML = mHTML+"</tr><tr style='height: 40px;'><td colspan='6'><input onclick='goClickPhoto();' type='button' id='btnGoPhoto' name='btnGoPhoto' class='btnadd' value='Import' size='60' style='margin-left: 0px !important; width: 90px !important;'/></td></tr></table>";
									}
									$("#dvImages").html(mHTML);
									$("#imgAjax").hide();
								}, {scope: 'user_photos'});
							}
							else
							{
								alert('No Album selected.');
							}
						}
						
						function radioClickedPhoto(pPhotoID, pPhotoSource)
						{
							$("#hdnPhotoID").val(pPhotoID);
							$("#hdnPhotoSource").val(pPhotoSource);
						}
						
						function goClickPhoto()
						{
							mPhotoID = 	$("#hdnPhotoID").val();
							if ($.trim(mPhotoID)!="")
							{
								var mRandom = Math.floor((Math.random()*1000000)+1); 
								var mUrl = "admin_contents/products/ajax.php?importimage=1&rndm="+mRandom;
								$.ajax({
									url: mUrl,
									type: 'POST',
									data: {'imageUrl': $('#hdnPhotoSource').val()},
									success: function(data)
									{
										if(data!='')
										{
											mTmpArr = data.split("~");
											mImageSrc = mTmpArr[0];
											mWidth = mTmpArr[1];
											mHeight = mTmpArr[2];
											
											$("#show_photo").show();
											$("#show_photo_before").hide();
											$("#item_img").attr("src","../c_panel/tempimages/"+mImageSrc+"?rndm="+mRandom);
											$.fancybox.close();
											$("#deleteimg").show();
											$("#cropimg").show();
											$("#cropimg").css('margin-left','10px');
											$('.form-progress-wrapper').hide();
											$("#show_photo_before").css('opacity','1.5');
											$("#show_photo").css('opacity','1.5');
											$('.jcrop-holder img').css("height","100px");
											$('.jcrop-holder img').css("width","100px");
											$('.jcrop-centered').css("height","100px");
											$('.jcrop-centered').css("width","100px");
											if ($('#item_img').data('Jcrop') != null) 
											{
												$('#item_img').data('Jcrop').destroy();
											}
											
											if ((mWidth>=450) || (mHeight>=450))
											{
												$('#item_img').css("width",Math.round(mWidth/4.5)+"px");
												$('#item_img').css("height",Math.round(mHeight/4.5)+"px");
												$('#hdnScale').val('4.5');
											}
											else if (((mWidth<450) && (mWidth>=400)) || ((mHeight<450) && (mHeight>=400)))
											{
												$('#item_img').css("width",Math.round(mWidth/4)+"px");
												$('#item_img').css("height",Math.round(mHeight/4)+"px");;
												$('#hdnScale').val('4');
											}
											else if (((mWidth<400) && (mWidth>=300)) || ((mHeight<400) && (mHeight>=300)))
											{
												$('#item_img').css("width",Math.round(mWidth/3.5)+"px");
												$('#item_img').css("height",Math.round(mHeight/3.5)+"px");;
												$('#hdnScale').val('3.5');
											}
											else if (((mWidth<300) && (mWidth>=250)) || ((mHeight<300) && (mHeight>=250)))
											{
												$('#item_img').css("width",Math.round(mWidth/3)+"px");
												$('#item_img').css("height",Math.round(mHeight/3)+"px");
												$('#hdnScale').val('3');
											}
											else if (((mWidth<250) && (mWidth>=220)) || ((mHeight<250) && (mHeight>=220)))
											{
												$('#item_img').css("width",Math.round(mWidth/2.5)+"px");
												$('#item_img').css("height",Math.round(mHeight/2.5)+"px");
												$('#hdnScale').val('2.5');
											}																							
											else if (((mWidth<220) && (mWidth>=190)) || ((mHeight<220) && (mHeight>=190)))
											{
												$('#item_img').css("width",Math.round(mWidth/2)+"px");
												$('#item_img').css("height",Math.round(mHeight/2)+"px");
												$('#hdnScale').val('2');
											}
											else if (((mWidth<190) && (mWidth>=120)) || ((mHeight<190) && (mHeight>=105)))
											{
												$('#item_img').css("width",Math.round(mWidth/1.5)+"px");
												$('#item_img').css("height",Math.round(mHeight/1.5)+"px");
												$('#hdnScale').val('1.5');
											}
											else
											{
												$('#item_img').css("width",Math.round(mWidth)+"px");
												$('#item_img').css("height",Math.round(mHeight)+"px");
												$('#hdnScale').val('1');
											}
											
											$('.jcrop-holder img').attr("src","../c_panel/tempimages/"+mImageSrc+"?rndm="+mRandom);
											$('#item_img').Jcrop
											({
												addClass: 'jcrop-centered',aspectRatio: 1, onSelect: updateCoords,maxSize: [ 500, 500 ]
											});
										}
									},
									error: function(data)
									{
										alert('Error occurred.');
									}
								});
							}
							else
							{
								alert('No Photo selected.');
							}
						}
						
						</script> 
                    </div>
                    <div id="dvAlbums"></div>
                    <div id="dvImages"></div>
                    <!--------------------------------------------------------------------------------------------------------------> 
                  </div></td>
              </tr>
              <tr>
                <td><a class="hover_gray" style="margin-top:20px;padding: 2px 6px 2px 5px;border-radius: 50%;color: black;background-color: #b9b9b9;cursor: pointer;float: right;margin-right: 30%;color: white;">?<i>Use these symbols to describe your items even further. Choose from New, Popular, Gluten-Free, Vegan, Spicy (3 chilies being spiciest), Vegetarian, Nut-Free or Low-Fat.</i></a></td>
              </tr>
            </table>
            <table id ="chk_area" style="float: left;margin-left: 66px;width: 250px; margin-top:-20px;">
              <tr>
                <td style="width: 85px;"><input type="checkbox" name ="type[]" class="chk_style" id ="new" value="0" <?if (strpos($type,'0') !== false) {?> checked<?}?>/>
                  <label for="new"></label>
                  <div style="margin-left: 27px;"><img src="img/new_icon.png" data-tooltip="New" id="new1" style="width: 36px;"></div></td>
                <td><input type="checkbox" name ="type[]" class="chk_style" id ="vegan" value="1" <?if (strpos($type,'1') !== false) {?> checked<?}?>/>
                  <label for="vegan"></label>
                  <div style="margin-left: 30px;"><img src="img/vegan_icon.png" data-tooltip="Vegan" id ="vegan1" style="width: 36px;"></div></td>
              </tr>
              <tr>
                <td class="padding_td"><input type="checkbox" name ="type[]" class="chk_style" id ="POPULAR"  value="2" <?if (strpos($type,'2') !== false) {?> checked<?}?>/>
                  <label for="POPULAR"></label>
                  <div style="margin-left: 27px;"><img src="img/POPULAR_icon.png" data-tooltip="Popular" id="Popular1" style="width: 36px;"></div></td>
                <td class="padding_td"><input type="checkbox" name ="type[]" class="chk_style" id ="nut_free" value="3" <?if (strpos($type,'3') !== false) {?> checked<?}?>/>
                  <label for="nut_free"></label>
                  <div style="margin-left: 30px;"><img src="img/nutfree_icon.png" data-tooltip="Nut Free" id="nut_free1" style="width: 36px;"></div></td>
              </tr>
              <tr>
                <td class="padding_td"><input type="checkbox" name ="type[]" class="chk_style" id ="glutenfree"  value="4" <?if (strpos($type,'4') !== false) {?> checked<?}?>/>
                  <label for="glutenfree"></label>
                  <div style="margin-left: 27px;"><img src="img/glutenfree_icon.png" id="glutenfree1" data-tooltip="Gluten Free" id="glutenfree1" style="width: 36px;"></div></td>
                <td class="padding_td"><input type="checkbox" name ="type[]" class="chk_style" id ="LOWFAT" value="5" <?if (strpos($type,'5') !== false) {?> checked<?}?>/>
                  <label for="LOWFAT"></label>
                  <div style="margin-left: 30px;"><img src="img/LOWFAT_icon.png" data-tooltip="Low Fat" id ="LOWFAT1" style="width: 36px;"></div></td>
              </tr>
              <tr>
                <td class="padding_td"><input type="checkbox" name ="type[]" class="chk_style" id ="vegetarian"  value="6" <?if (strpos($type,'6') !== false) {?> checked<?}?>/>
                  <label for="vegetarian"></label>
                  <div style="margin-left: 27px;"> <img src="img/vegetarian_icon.png" data-tooltip="Vegetarian" id ="Vegetarian1" style="width: 36px;"> </div></td>
                <?php
                            if (($_SESSION['admin_type'] == 'admin') || ($_SESSION['admin_type'] == 'bh'))
                            {
                                if ($Objrestaurant->bh_restaurant=="1")
                                {
                        ?>
                <td class="padding_td">
                <input type="checkbox" name ="type[]" class="chk_style" id ="bh_item" value="B" 
                <?=((strpos($type,'B') !== false)? 'checked':'')?>/>
                  <label for="bh_item"></label>
                  <div style="margin-left: 27px;"> <img src="img/bh_item1.png<?php echo $jsParameter;?>" data-tooltip="BH Item" id ="bh_item1" style="width: 36px;"> </div></td>
                <?php
                                }
                            }
                        ?>
              </tr>
              <tr>
                <td class="padding_td"><input type="checkbox" name ="type[]" class="chk_style" id ="spicy" value="7" <?if (strpos($type,'7') !== false) {?> checked<?}?>/>
                  <label for="spicy"></label>
                  <div style="margin-left:30px;"><img src="img/spicy_icon.png" data-tooltip="mild"  class ="spicy1" style="width: 36px;"></div></td>
              </tr>
              <tr>
                <td colspan="2" class="padding_td"><input type="checkbox" class="chk_style" name="type[]" id="spicy1" value="8" <?if (strpos($type,'8') !== false) {?> checked<?}?>/>
                  <label for="spicy1"></label>
                  <div style="margin-top: -12px;margin-left: 27px;"><img src="img/spicy_icon.png" class ="spicy1" data-tooltip="Medium" style="margin-top: -25px;width: 36px;"> <img src="img/spicy_icon.png" data-tooltip="Medium" class ="spicy1" style="margin-left: 5px;width: 36px;"></div></td>
              </tr>
              <tr>
                <td colspan="3" class="padding_td"><input type="checkbox" class="chk_style" name="type[]" id ="spicy2" value="9" <?if (strpos($type,'9') !== false) {?> checked<?}?>/>
                  <label for="spicy2"></label>
                  <div style="margin-top: -12px;margin-left: 27px;"><img src="img/spicy_icon.png" class ="spicy1" data-tooltip="Hot" style="width: 36px;"> <img src="img/spicy_icon.png" data-tooltip="Hot" class ="spicy1" style="margin-left: 5px;width: 36px;"> <img src="img/spicy_icon.png" data-tooltip="Hot" class ="spicy1" style="margin-left: 5px;width: 36px;"></div></td>
              </tr>
            </table>
            <input type="submit" value="Save Changes" name="btnUpdateProduct" class="btnadd" id="btnUpdateProduct" style="width: 16%;margin-top: 89px;">
            <input type="button" name="btnCancelProduct" id="btnCancelProduct" value="Cancel" style="margin-left: 25px;height: 38px;width: 96px;">
          </div>
        </div>
        <div id="right_panel" style="width:25%;margin-right: 5%;margin-top: 20px;">
          <ul class="tabs_product">
            <li  rel="tab1_product" style="line-height: 44px;margin-top: -16px;"> Attributes</li>
            <li class="active_product" rel="tab2_product" style="width: 78px;margin-top: -16px;"><span style="margin-top: 8px;float: left;">Complementary Items</span></li>
          </ul>
          <div class="tab_container_product" style="min-height: 500px;width: 230px;margin-right: -11px;box-shadow: 0 2px 40px 0;height:auto;">
            <div id="tab1_product" class="tab_content_product"> 
              <script type="text/javascript" language="javascript">
                                 $("#btnAddAttribute").fancybox({
                                                                                afterClose: function() {
                                                                                emptyFancyBoxFields();
                                                                                  }
                                                                               });
                            </script>
              <div id="NewAttributeLi" style="cursor:pointer"><a href="#dvAddAttribute" id="btnAddAttribute" style="color: #A2A3A3;text-decoration: none"><span style="float:left"><img src="img/add_icon.png"></span><span style="margin-left: 12px;">Create new Attribute</span></a></div>
              <div id="ExistingAttributeLi" style="margin-top: 12px;cursor:pointer"><span style="float:left"><img src="img/add_icon.png"></span><span style="margin-left: 12px;color: #A2A3A3;">Add Existing Attribute</span></div>
              <div class="ulProductdiv">
                <ul id="attr-list_product">
                  <?php
                        
                        $attrib_qry = dbAbstract::Execute("SELECT id,display_name,option_name,OderingNO FROM attribute WHERE ProductID ='".$_GET['prd_id']."' order by OderingNO,id",1);
                        $optionName="";
                        $attributeStr="";
                        $displayName="";
                        while ($attr_array = dbAbstract::returnObject($attrib_qry,1)) {
                            if($optionName==""){//run only first time
                                $optionName=$attr_array->option_name;
                                $displayName=$attr_array->display_Name;
                            }
                            if($optionName!=$attr_array->option_name){
                                if($attributeStr!=""){
                                    ?>
                  <li id="attr_<?=str_replace(" ", "", $optionName);?>" class="liAtribute">
                      <a href="#dvAddAttribute" class="option_Name" style="margin-top:2px"><?=$optionName;?></a> 
                      <span class="attr_delete" attributeIds="<?php echo substr($attributeStr, 0,-1);?>" option_name ="<?=$optionName?>" display_name="<?=$displayName?>">x</span>
                    <div style="clear:both"></div>
                  </li>
                  <?php
                                    $attributeStr="";
                                }
                            }
                            $attributeStr.=$attr_array->id."-";
                            $optionName=$attr_array->option_name;
                            $displayName=$attr_array->display_Name;
                        }
                        if($optionName!=""){
                            if($attributeStr!=""){
                                ?>
                  <li id="attr_<?=str_replace(" ", "", $optionName);?>" class="liAtribute">
                      <a href="#dvAddAttribute" class="option_Name" style="margin-top:2px"><?=$optionName;?></a> 
                      <span class="attr_delete" attributeIds="<?php echo substr($attributeStr, 0,-1);?>" option_name ="<?=$optionName?>" display_name="<?=$displayName?>">x</span>
                    <div style="clear:both"></div>
                  </li>
                  <?php
                                $attributeStr="";
                            }
                        }
                        ?>
                </ul>
                <script type="text/javascript" language="javascript">
                                         $('.option_Name').fancybox({
                                                            afterClose: function(){
                                                                emptyFancyBoxFields();
                                                            }
                                                           });
                           </script>
                <div class="noAttributesForProduct" <? if(dbAbstract::returnRowsCount($attrib_qry,1)>0){?> style="display:none;" <?}else{?> style="display:block;"<?}?>>
                <span style="display:block;margin-top: 125px;">No Attributes</span> <span>Add Some?</span> </div>
            </div>
            <input type="button" value="Save attribute order" name="btnSortAttribute" id="btnSortAttribute" style="width: 130px;margin-left: 30px;" class="cancel">
            <input type="hidden" value="" id="hdnSortAttributes" name="hdnSortAttributes"/>
          </div>
          <div id="tab2_product" class="tab_content_product">
            <div id="RelatedItemLi" style="cursor:pointer"><span style="float:left;width: 24px;height: 24px;"><img src="img/add_icon.png"></span><span style="color: #A2A3A3;">Add new Complementary Items</span></div>
            <div class="ulProductdiv">
              <ul id="related-list_product">
                <?php
                        $product_assoc_qry = dbAbstract::Execute("SELECT association_id FROM product_association WHERE product_id ='".$prd_id."' order by sortOrder asc",1);
                             while ($assocRs = dbAbstract::returnArray($product_assoc_qry, 1)) {
                                 
                                 $product_query = dbAbstract::Execute("SELECT prd_id,item_title FROM product WHERE prd_id='" . $assocRs['association_id'] . "'",1);
                    
                                    while ($productRS = dbAbstract::returnObject($product_query,1)) {
                                          ?>
                <li id="related_<?=$assocRs['association_id']?>" class="liRelated"><span style="width: 80%;float: left;margin-top:4px">
                  <?=$productRS->item_title;?>
                  </span> <span class="related_delete" prd_id ="<?=$prd_id?>" assoc_id="<?=$assocRs['association_id']?>">x</span>
                  <div style="clear:both"> </div>
                </li>
                <?}}?>
              </ul>
              <div class="noRelatedItems"   <?php if(dbAbstract::returnRowsCount($product_query, 1, true)>0){?>style="display:none;" <?}else{?> style="display:block;"<?}?>> <span style="display:block;margin-top: 125px;">No Complementary Items</span> <span>Add Some?</span> </div>
            </div>
            <input type="button" value="Save order" name="btnSortComplimentry" id="btnSortComplimentry" style="width: 125px;margin-left: 33px;margin-top: 12px;margin-bottom: 5px;" class="cancel">
            <input type="hidden" value="" id="associations_ids" name="sortComplimentryItems"/>
            <div id="mainFancyBox" style="display:none;width: 226px;border: 2px solid #D6E2E0;background: #F9F8F8;margin-left: -20px;margin-top: 6px;">
              <div style="text-align: center;line-height: 25px;width:226px">
                <div style="text-align: center;margin-left: 13px;margin-bottom: 8px;color: #8A8A8A;font-size: 17px;">Add New Complementary Item</div>
                <select class="chzn-select"  name="typeRelatedItemName" id="typeRelatedItemName" style="width:200px;">
                  <option value="">--Please Select--</option>
                </select>
                <br/>
                <input type="checkbox" name="applyToAll" id="applyToAll" value ="1" class="chk_style"/>
                <label for="applyToAll" style="color: #25AAE1; font-size:14px;margin-top: 8px;">Apply to All</label>
                <div id="displayMessage" class="alert-error" style="display: none;width: 150px;margin-left: 26px;margin-top: 15px">Item already Added</div>
                <div style="text-align: center">or</div>
                <div style="text-align: center">
                  <input type="button" value="Browse" id="browseRelatedItems" name="browseRelatedItems" class="cancel" style="margin-top:0px;width:120px;line-height: 26px;;height:28px;background-color: #565656">
                </div>
                <input type="button" value="Add" id="addRelatedIteminProduct" name="addRelatedIteminProduct" class="cancel" style="margin-top:10px;width:120px">
                <input type="button" value="Cancel" id="closeRelatedItemDiv" name="closeRelatedItemDiv" class="cancel" style="margin-top:10px;margin-bottom: 20px;width:120px">
              </div>
            </div>
          </div>
        </div>
        <div id="back-top"><a href="#top"><img src="img/arrowUp.png" style="width: 58px;position: absolute" id="back-img"/></a></div>
      </div>
  	  </div>
  	  
      <a class="fancyTrigger" href="#TheFancybox"></a>
      <div id="TheFancybox">
        <div style="background-image: url('img/fupload.png');width:558px;height: 390px;display: none;" class="uploader_div">
          <div style="text-align: center">
            <input type="submit" value ="Browse Files" name="upd_file_btn" id="upd_file_btn" class="updfile"/>
            <div id="sizeErrorMsg" style="margin-top: 8px;font-size: 13px;">File Size must under 1MB</div>
            <input name="userfile-uploader" type="file" id="userfile-uploader" size="30" style="visibility: hidden" title=" ">
          </div>
        </div>
      </div>
	</form>
  </body>
</html>

<?php include('attr_assoc_popup.php'); ?>
<div id="popup_boxProduct" class="popup_box" style="width:400px;min-height:250px;">
  <div style="background-color: #25AAE1;font-size: 19px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;">
    <div style="margin-left: 112px;">Save Changes?</div>
  </div>
  <div style="margin-top: 40px;margin-left: 85px;">
    <input type="button" id="btnYesProduct" name="btnYesProduct" value="Yes" class="cancel" style="font-size: 20px;">
    <input type="button" id="btnNoProduct" name="btnNoProduct" value="No" class="cancel" style="font-size: 20px;">
  </div>
</div>
