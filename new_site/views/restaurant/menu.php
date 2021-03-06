<style type="text/css">
    #dhtmltooltip
    {
        position: absolute;
        width: 300px;
        border: 2px solid #E4E4E4;
        padding: 5px;
        background-color: #F4F4F4;
        visibility: hidden;
        z-index: 100;
        font-size:11px;
        color:#585858;
    }

    #dhtmltooltip span 
    {
        font-size:14px;
        font-weight:bold;
        color:#000;
    }	
</style>
<a href="#BodyLeftArea" rel="facebox" id="showPopupLink"></a>
<div id="dhtmltooltip"></div>
<script src="<? echo $js_root; ?>dhtmltip.js" type="text/javascript"></script>
<?php
include "classes/ProductDetails.php";
if (isset($_GET['faverr'])) {
    echo("<script>jQuery.facebox({div: '?item=addtofavorite&id=" . $_GET["id"] . "&ajax=1&err=1'});</script>");
}
$objCategory->menu_id = $menuid;

$arr = product::getProductsByMenuId($menuid);
$menus_details = $arr['details'];
$total_cats = $arr['count'];
$half = round($total_cats / 2);
$mColumn1Count = -1;
$mSQLMenu = "SELECT IFNULL(Column1Count, -1) AS Column1Count FROM menus WHERE id=".$menuid;
if($mSQLMenuRes = dbAbstract::Execute($mSQLMenu)){
    if (dbAbstract::returnRowsCount($mSQLMenuRes)>0)
    {
            $mSQLMenuRow = dbAbstract::returnObject($mSQLMenuRes);
            if ($mSQLMenuRow->Column1Count>=0)
            {
                    $mColumn1Count = $mSQLMenuRow->Column1Count;
            }
    }
}
$mDivider = $half;

if ($mColumn1Count>=0)
{
	$mDivider = $mColumn1Count;
}

$mDivider = $mDivider + 1;

$index = 0;
$loop_index = 0;
$loop_index_check = FALSE;

?>
<?php 
    $attributeCount=0;
    if(empty($attributes_result)){
        $attributes_result=array();
    }else{
        $attributeCount=count($attributes_result);
    }
    
?>
<script>
        var attributeRequired;
      
	function isNumeric(n) 
	{
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}	
    menu_details = <?php echo json_encode($menus_details) ?>;

    function setProductDetailsToPopup(productDetails){

        $('#item_title').html(productDetails.item_title);
        mTmpDesc = productDetails.item_des;
        mTmpDesc = mTmpDesc.split("&#174;").join("<sub>&#174;</sub>");
        mTmpDesc = mTmpDesc.split("&#8482;").join("<sub>&#8482;</sub>");
        mTmpDesc = mTmpDesc.split("&#169;").join("<sub>&#169;</sub>");
        productDetails.item_des = mTmpDesc;
        $('#item_des').html(productDetails.item_des);

        if(productDetails.item_image){
            $('#popupProductImage').show();
            $('#popupProductImage').append('<a href="<?php echo $SiteUrl . "images/item_images/" ?>' + productDetails.item_image+'" rel="prettyPhoto" display="inline" id="item_image_link"><img class="images" src="<?php echo $SiteUrl . "images/item_images/" ?>' + productDetails.item_image+'" width="70" height="70" id="item_image" border="0"></a>');
            $('#item_detail_wrapper').css('float','right');
        } else {
            $('#popupProductImage').hide();
            $('#item_image_link').remove();
            $('#item_detail_wrapper').css('float','left');
        }
        $('.item_title_type').remove();
        if(productDetails.item_type){
            var product_item_type = productDetails.item_type;
            if(product_item_type.indexOf('0') !== -1){
                $('<img src="../c_panel/img/new_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
            if(product_item_type.indexOf('1') !== -1){
                $('<img src="../c_panel/img/vegan_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
            if(product_item_type.indexOf('2') !== -1){
                $('<img src="../c_panel/img/POPULAR_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
            if(product_item_type.indexOf('3') !== -1){
                $('<img src="../c_panel/img/nutfree_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
            if(product_item_type.indexOf('4') !== -1){
                $('<img src="../c_panel/img/glutenfree_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
            if(product_item_type.indexOf('5') !== -1){
                $('<img src="../c_panel/img/LOWFAT_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
            if(product_item_type.indexOf('6') !== -1){
                $('<img src="../c_panel/img/vegetarian_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
            if(product_item_type.indexOf('7') !== -1){
                $('<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
            if(product_item_type.indexOf('8') !== -1){
                $('<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/><img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
            if(product_item_type.indexOf('9') !== -1){
                $('<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/><img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/><img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
            if(product_item_type.indexOf('B') !== -1){
                $('<img src="../c_panel/img/bh_item1.png?v4" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
            }
        }
        $('#retail_price').html('<?=$currency?>' + productDetails.retail_price);
        $('#product_sale_price').val(productDetails.sale_price);
    }
    
    function showPopup(productId,hasAssociates,hasAttribute,cartItemIndex) {
        $('#facebox').css("position","absolute");
        $('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) + "px");
        $('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
        $('#attributes_wrapper').html('');
        $('#association_wrapper').html('');
        $('#item_image_link').remove();

        var productDetails;

        if(cartItemIndex < 0){
            //Means user just want to add items to cart
            productDetails = menu_details.filter(function(x) {
                return x.prd_id == productId;
            });
            productDetails = productDetails['0'];
            //Call setProductDetailsToPopup to set details of product to popup
            setProductDetailsToPopup(productDetails);
        }

        $('#product_id_field').val(productId);
        $('#totalattributes').val(<?php echo $attributeCount; ?>);
        $('#hasAssociates').val(hasAssociates);
        $('#hasAttributes').val(hasAttribute);
        $('#cartItemIndex').val(cartItemIndex);
        
        attributeRequired = new Array();
        var attribute_index = 0;
        var attribute_name = "attr";
        var attribute_parent_name = "attrname";
        var html = '';

        html = html + '<table width="100%" border="0" cellspacing="0" cellpadding="0"><input type="hidden" id="hdnControls" />';
        
        if(hasAssociates == 1 || hasAttribute == 1 || cartItemIndex>=0){

            var mUrl = '';
            var mRandom = Math.floor((Math.random() * 1000000) + 1);
            if(cartItemIndex>=0)
            {
                mUrl = "<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=favindex&showEditPopup=1&ajax=1";
            }
            else
            {
                mUrl = "<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=favindex&showpopup=1&ajax=1";
            }
            
            $.ajax
            ({
                url: mUrl,
                type: 'POST',
                data: {
                    productId : productId,
                    hasAssociates : hasAssociates,
                    hasAttribute : hasAttribute,
                    cartItemIndex :cartItemIndex
                },
                success: function(data)
                {
                    data = JSON.parse(data);
                    attributes_array = data.attr;
                    association_array = data.assoc;
                    var totalattributes = data.totalAttribute;
                    $('#totalAttributes').val(totalattributes);
                    
                    if(data.hasOwnProperty('productDetails'))
                    {
                        //Means Edit Item function called from cart
                        // So, call setProductDetailsToPopup function to set extra details of product to popup
                        productDetails = data.productDetails;
                        setProductDetailsToPopup(productDetails);
                    }
                    
                    if(data.hasOwnProperty('quantity')){
                        var quantity = data.quantity;
                        $('#quantity').val(quantity);
                    }else
                    {
                        $('#quantity').val(1);
                    }
                    if(data.hasOwnProperty('item_for')){
                        var item_for = data.item_for.replace(/&#39;/g, "'");
                        $('#item_for').val(item_for);
                    }else
                    {
                        $('#item_for').val('');
                    }
                    if(data.hasOwnProperty('requestnote')){
                        var requestnote = data.requestnote.replace(/&#39;/g, "'");
                        $('#requestnote').val(requestnote);
                        $('#requestnote').text(requestnote);
                    }else
                    {
                        $('#requestnote').val('');
                        $('#requestnote').text('');
                    }

                    var requiredIndex = 0;
                    $.each(attributes_array, function(i, attribute) 
                    {
                        mAttReq = '';
                        mAttrReqHtml = '';
                        mStrRe = '<tr><td colspan="3" style="width: 100%; font-size: 11px !important; color: red;">&nbsp;<i>';

                        var mAttrName = attribute.attr_name;
                        var mLimit = '';
                        var mLimitPrice = attribute.extra_charge;
                        var mDD = '';

                        if ($.trim(mAttrName)!="")
                        {
                            var mTmp =	mAttrName.split("~");
                            var mLimit =mTmp[2];
                        }
						
                        attribute_index = attribute_index + 1;
                        attribute_name = "attr" + attribute_index + "[]";
                        if (attribute.Type == 1 || attribute.Type == 3) 
                        {
                            attribute_name = "attr" + attribute_index;
                        }
                        attribute_parent_name = "attrname" + attribute_index;
						
                        if (attribute.Required == 1)
                        {
                            attributeRequired[requiredIndex] = attribute.id;
                            requiredIndex++;
                            mStrRe = mStrRe + 'Choose at least one';
                            mAttrReqHtml = '<span id="attrRequired-'+attribute.id+'" class="red" style="display:none;">*</span>';
                        }
						
                        if (($.trim(mLimit)!="") && ($.trim(mLimitPrice)!=""))
                        {
                            if ((mLimit>0) && (mLimitPrice>0))
                            {
                                if (mStrRe=='<tr><td colspan="3" style="width: 100%; font-size: 11px !important; color: red;">&nbsp;<i>')
                                {
                                    mStrRe = mStrRe + '<?=$currency?>'+mLimitPrice+' will be added for each additional selection';
                                }
                                else
                                {
                                    mStrRe = mStrRe + '&nbsp;&nbsp;-&nbsp;&nbsp;(<?=$currency?>'+mLimitPrice+' will be added for each additional selection)';
                                }
                            }
                        }

                        mStrRe = mStrRe + '</i></td></tr>';
                        if (attribute.display_Name!=null)
                        {
                            if (($.trim(attribute.display_Name)!="") && ($.trim(attribute.display_Name)!="Type your message here"))
                            {
                                html = html + '<tr><td><table id=attrRequiredBorder-'+attribute.id+' style="margin-bottom:5px;width: 100%;"><tr><td>&nbsp;</td></tr><tr ><td><strong class="Text_14px">' + attribute.display_Name + '</strong>'+mAttrReqHtml+'<input type="hidden" name="' + attribute_parent_name + '" value="' + attribute.option_name + '" />'+mAttReq+'</td></tr><tr><td>'+mStrRe+'</td></tr>';
                            }
                            else
                            {
                                html = html + '<tr><td><table id=attrRequiredBorder-'+attribute.id+' style="margin-bottom:5px;width: 100%;"><tr><td>&nbsp;</td></tr><tr ><td><strong class="Text_14px">' + attribute.option_name + '</strong>'+mAttrReqHtml+'<input type="hidden" name="' + attribute_parent_name + '" value="' + attribute.option_name + '" />'+mAttReq+'</td></tr><tr><td>'+mStrRe+'</td></tr>';
                            }
                        }
                        else
                        {
                            html = html + '<tr><td><table id=attrRequiredBorder-'+attribute.id+' style="margin-bottom:5px;width: 100%;"><tr><td>&nbsp;</td></tr><tr ><td><strong class="Text_14px">' + attribute.option_name + '</strong>'+mAttrReqHtml+'<input type="hidden" name="' + attribute_parent_name + '" value="' + attribute.option_name + '" />'+mAttReq+'</td></tr><tr><td>'+mStrRe+'</td></tr>';
                        }
                        if (attribute.Type == 1) 
                        {
                            html = html + '  <tr><td><select id="ddlAttr" attributeid="'+attribute.id+'" textboxid="txtDD'+attribute.id+'" type="select" name="' + attribute_name + '" class="inputAttrDD">';
                            html = html + '<option price="" value="" selected>-	Select -</option>';
                        }

                        var attribute_option_index = 0;
                        var attributes = [];
                        var tempfirstatt = {};

                        tempfirstatt = {
                            id: attribute.id,
                            ProductID: attribute.ProductID,
                            option_name: attribute.option_name,
                            Title: attribute.Title,
                            Price: attribute.Price,
                            option_display_preference: attribute.option_display_preference,
                            apply_sub_cat: attribute.apply_sub_cat,
                            Type: attribute.Type,
                            Required: attribute.Required,
                            OderingNO: attribute.OderingNO,
                            rest_price: attribute.rest_price,
                            Default: attribute.Default,
                            add_to_price: attribute.add_to_price
                        }

                        attributes.push(tempfirstatt);

                        $.each(attribute.attributes, function(i, attribute1) {
                            var attributes1 = [];
                            var tempfirstatt1 = {};

                            tempfirstatt1 = {
                                id: attribute1.id,
                                ProductID: attribute1.ProductID,
                                option_name: attribute1.option_name,
                                Title: attribute1.Title,
                                Price: attribute1.Price,
                                option_display_preference: attribute1.option_display_preference,
                                apply_sub_cat: attribute1.apply_sub_cat,
                                Type: attribute1.Type,
                                Required: attribute1.Required,
                                OderingNO: attribute1.OderingNO,
                                rest_price: attribute1.rest_price,
                                Default: attribute1.Default,
                                add_to_price: attribute1.add_to_price
                            }
                            attributes1.push(tempfirstatt1)
                            $.merge(attributes, attributes1);
                        });

                        var col_index = 0;
                        var mCheckCount = 0;
                        var mTxtCheckedLoad = '';
                        var j = 0;
						var mAddFlag = 0;
						var mAddFlag1 = 0;
                        $.each(attributes, function(i, attribute_option) {
							var attrid = '';
                            attribute_option_index += 1;
                            selected = '';
                            attribute_option.Price = $.trim(attribute_option.Price);
                            attribute_option.Price = attribute_option.Price.replace("/[^0-9.]+/", '');

                            if (!isNaN(attribute_option.Price) && attribute_option.Price != 0) {
                                if (attribute_option.Price[0] == '-') {
                                    if (attribute_option.add_to_price == 1 || attribute_option.add_to_price == '') {
                                        attribute_option.displayprice = "<span class='red'> - Subtract <?=$currency?>" + attribute_option.Price.replace("/[^0-9.]+/", '') + "</span>";
                                    } else {
                                        attribute_option.displayprice = "<span class='red'>  <?=$currency?>" + (parseFloat(attribute_option.Price) + parseFloat(productDetails.retail_price))+''.replace("/[^0-9.]+/", '') + "</span>";
                                    }
                                } else {
                                    attribute_option.add_to_price;
                                    if (attribute_option.add_to_price == 1 || attribute_option.add_to_price == '') {
                                        attribute_option.displayprice = "<span class='red'> + Add <?=$currency?>" + attribute_option.Price.replace("/[^0-9.]+/", '') + "</span>";
                                    } else {
                                        attribute_option.displayprice = "<span class='red'>  <?=$currency?>" + (parseFloat(attribute_option.Price) + parseFloat(productDetails.retail_price))+''.replace("/[^0-9.]+/", '') + "</span>";
                                    }
                                }
                            } else {
                                attribute_option.displayprice = '';
                            }

                            if (attribute.Type == 1) {
                                if (attribute_option.Default == 1 && j == 0) {
                                    attrid = attribute_option.id;
                                    j = 1;
                                }
                                html = html + '<option optionid="'+attribute_option.id+'" textboxid="txtDD'+attribute.id+'" price="'+attribute_option.Price+'"  value="' + attribute_option.id + '"';
                                if (attrid != '' || attrid == attribute_option.id) {
                                    html = html + 'selected';
                                    mDD = attribute_option.id+",";
                                    if ($.trim(attribute_option.Price)!="")
                                    {
                                        if (!isNaN(attribute_option.Price))
                                        {
                                            mPreviousPrice = $('#retail_price').html(); 
                                            mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
											mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                                            mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(attribute_option.Price);
                                            mPreviousPrice = mPreviousPrice.toFixed(2);
                                            mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
                                            $('#retail_price').html(mPreviousPrice);
                                        }
                                    }
                                }
                                html = html + '>' + attribute_option.Title + '' + attribute_option.displayprice + '</option>';
                            } else if (attribute.Type == 2 || attribute.Type == 3) {
                                if (col_index % 3 == 0) {
                                    html = html + '<tr>';
                                }
								
                                if (attribute_option.Default == 1)
                                {
									if (attribute.Type == 1 || attribute.Type == 3) 
									{
										if (mAddFlag==0)
										{
											mAddFlag=1;
											mCheckCount = mCheckCount + 1;
											if ($.trim(mLimit)!="")
											{
												if (mCheckCount>mLimit)
												{
													mPreviousPrice = $('#retail_price').html();
													mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
													mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
													if (isNumeric(attribute_option.Price))
													{
														mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(attribute_option.Price) + parseFloat(mLimitPrice);
													}
													else
													{
														mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mLimitPrice);
													}
													mPreviousPrice = mPreviousPrice.toFixed(2);
													mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
													$('#retail_price').html(mPreviousPrice);
													mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
												}
												else
												{
													mPreviousPrice = $('#retail_price').html();
													mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
													mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
													if (isNumeric(attribute_option.Price))
													{
														mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(attribute_option.Price);
													}
													else
													{
														mPreviousPrice = parseFloat(mPreviousPrice);
													}

													mPreviousPrice = mPreviousPrice.toFixed(2);
													mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
													$('#retail_price').html(mPreviousPrice);
													mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
												}
											}
											else
											{
												mPreviousPrice = $('#retail_price').html();
												mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
												mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
												if (isNumeric(attribute_option.Price))
												{
													mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(attribute_option.Price);
												}
												else
												{
													mPreviousPrice = parseFloat(mPreviousPrice);
												}
												
												
												mPreviousPrice = mPreviousPrice.toFixed(2);
												mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
												$('#retail_price').html(mPreviousPrice);
												mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
											}
										}
									}
									else
									{
	                                    mCheckCount = mCheckCount + 1;
	                                    if ($.trim(mLimit)!="")
	                                    {
	                                        if (mCheckCount>mLimit)
	                                        {
	                                            mPreviousPrice = $('#retail_price').html();
	                                            mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
												mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
												if (isNumeric(attribute_option.Price))
												{
		                                            mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(attribute_option.Price) + parseFloat(mLimitPrice);
												}
												else
												{
		                                            mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mLimitPrice);
												}

	                                            mPreviousPrice = mPreviousPrice.toFixed(2);
	                                            mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
	                                            $('#retail_price').html(mPreviousPrice);
	                                            mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
	                                        }
	                                        else
	                                        {
	                                            mPreviousPrice = $('#retail_price').html();
	                                            mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
												mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
												if (isNumeric(attribute_option.Price))
												{
		                                            mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(attribute_option.Price);
												}
												else
												{
		                                            mPreviousPrice = parseFloat(mPreviousPrice);
												}

	                                            mPreviousPrice = mPreviousPrice.toFixed(2);
	                                            mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
	                                            $('#retail_price').html(mPreviousPrice);
	                                            mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
	                                        }
	                                    }
	                                    else
	                                    {
	                                        mPreviousPrice = $('#retail_price').html();
	                                        mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
											mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
											if (isNumeric(attribute_option.Price))
											{
		                                        mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(attribute_option.Price);
											}
											else
											{
		                                        mPreviousPrice = parseFloat(mPreviousPrice);
											}

	                                        mPreviousPrice = mPreviousPrice.toFixed(2);
	                                        mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
	                                        $('#retail_price').html(mPreviousPrice);
	                                        mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
	                                    }
									}
                                }
								
                                html = html + '<td class="attribute"><input limit="'+mLimit+'" limitprice="'+mLimitPrice+'" price="'+attribute_option.Price+'" id="'+attribute_option.id+'" attributeid="'+attribute.id+'" name="' + attribute_name + '" class="' + (attribute.Type == 2 ? 'inputAttr' : 'inputAttrRB') + '" type="' + (attribute.Type == 2 ? 'checkbox' : 'radio') + '" value="' + attribute_option.id + '" ' +  (mAddFlag1==0 ? (attribute_option.Default == 1 ? 'checked' : '') : '') + ' >' + attribute_option.Title + '' + attribute_option.displayprice + '</td>';
								
								if ((mAddFlag1==0) && (attribute_option.Default == 1) && (attribute.Type == 3))
								{
									mAddFlag1 = 1;
								}
								
                                col_index += 1;
                                if (col_index % 3 == 0) {
                                    html = html + '</tr>';
                                }
                            }

                            attribute.Required = 0;
                        });
						
                        if (attribute.Required == 1)
                        {
                            mAttReq = '<input type="hidden" id="txtChecked'+attribute.id+'" value="'+mTxtCheckedLoad+'" /><input type="hidden" id="txtReq'+attribute.id+'" value="1" />';
                        }
                        else
                        {
                            mAttReq = '<input type="hidden" id="txtChecked'+attribute.id+'" value="'+mTxtCheckedLoad+'" /><input type="hidden" id="txtReq'+attribute.id+'" value="0" />';
                        }
						
                        if (attribute.Type == 1) {
                            html = html + "</select><input type='hidden' id='txtDD"+attribute.id+"' value='"+mDD+"'/></td></tr>";
                        }
						
                        html = html + mAttReq+'</table></td></tr>';
                    });
                    html = html + '<tr><td>&nbsp;</td></tr></table>';

                    $('#attributes_wrapper').html(html);
		    html = '';

                    //association details
                    if(association_array != ''){
                        var associationResult = association_array.filter(function(x) {
                            return x.product_id == productId;
                        });

                        var index = 0;
                        if (associationResult.length > 0) {
                            html = html + '<table width="100%" border="0" cellspacing="0" cellpadding="0" id="association_wrapper"><tbody><tr><td><input type="hidden" id="txtAssoc" /><strong>Add to your meal</strong></td></tr>'
                            $.each(associationResult, function(i, item) {
                                var selected = '';
                                if(("Selected" in item))
                                {
                                    selected = 'checked="checked"';
                                    mPreviousPrice = $('#retail_price').html();
                                    mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
									mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                                    mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(item[productId]['0'].retail_price);
                                    mPreviousPrice = mPreviousPrice.toFixed(2);
                                    mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
                                    $('#retail_price').html(mPreviousPrice);
                                }
                                if (index % 3 == 0) {
                                    html = html + '<tr>';
                                }
                                var ind_val = index + 1;
                                html = html + '<td class="attribute"><input class="clsAssoc" id="'+item[productId]['0'].prd_id+'" price="'+item[productId]['0'].retail_price+'" type="checkbox" ' + selected + ' name="associations[]" value="' + ind_val + '" >' + item[productId]['0'].item_title + '&nbsp;<span class="red"><?= $currency ?>' + item[productId]['0'].retail_price + '</span></td>'
                                index = ind_val;
                                if (index % 3 == 0) {
                                    html = html + '</tr>';
                                }

                            });
                            html = html + '</tbody></table>'
                        }
                    }
                    $('#association_wrapper').html(html);
                    $('#showPopupLink').trigger('click');
                    $('#facebox').css("position","absolute");
                    $('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) + "px");
                    $('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");

                },
                error: function(data)
                {
                    alert('Error occurred.');
                }
            });
        } else {
            $('#quantity').val(1);
            $('#item_for').val('');
            $('#requestnote').val('');
            $('#requestnote').text('');
            
            $('#showPopupLink').trigger('click');
            $('#facebox').css("position","absolute");
            $('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) + "px");
            $('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
        }

    }

$(function() 
{
    $('#facebox #frmPrd').live('submit', function(event) 
    {
        event.preventDefault();
            
        mQuantity = $("#quantity").val();
        mItemFor = $("#item_for").val();
        mRequestNote = $("#requestnote").val();
        mTotalAttributes = $("#totalattributes").val();

        var product_id = $('#product_id_field').val();
        var product_title = $('#item_title').val();
        var product_quantity = $('#quantity').val();
        var product_sale_price = $('#product_sale_price').val();
        var price = product_sale_price * product_quantity;
        var index = $('#contents div.flip').length - 1;
        var hasAttributes = $('#hasAttributes').val();
        var hasAssociates = $('#hasAssociates').val();
            
        var isValid = true;

        $.each(attributeRequired, function(index, value) 
        {
            var isAllRequiredSelected = false;
            var attributeElements = $('[attributeid='+value+']');
  
            var filteredAttributeElements = attributeElements.slice(attributeElements.length/2);
            $.each(filteredAttributeElements, function(index, element) 
            {    
                if(element.tagName !== "SELECT")
                {
                    if ($(element).is(':checked')) {
                        isAllRequiredSelected = true;
                    }
                }
                else
                {
                    if ($(element).val() !== "") {
                        isAllRequiredSelected = true;
                    }
                }
            });
            var attributeId = 'attrRequired-'+value;
            var attributeborderId = 'attrRequiredBorder-'+value;
            if(!(isAllRequiredSelected))
            {
                isValid = false;
                $($('[id='+attributeId+']')[1]).show();
                $($('[id='+attributeborderId+']')[1]).css({"display":"block","border":"2px solid #9C0F17","border-collapse":"separate","border-spacing":"2px"});
            }
            else
            {
                $($('[id='+attributeId+']')[1]).hide();
                $($('[id='+attributeborderId+']')[1]).css({"border":"none"});
            }
        });

        if(!(isValid))
        {
            $($('[id=updateMessage]')[1]).show();
            $($('[id=updateMessage1]')[1]).show();
        }
        else
        {
            $($('[id=updateMessage]')[1]).hide();
            $($('[id=updateMessage1]')[1]).hide();
            var mUrl = '';
            var mRandom = Math.floor((Math.random() * 1000000) + 1);
            mUrl = "?item=favindex&addtocart=1&ProductID=" + product_id + "&rndm=" + mRandom + "&ajax=1";
            $.facebox.close();
            $.ajax
            ({
                url: mUrl,
                type: 'POST',
                data: $("#facebox #frmPrd").serialize(),
                success: function()
                {
                    var timeStamp = Math.floor(Date.now() / 1000);
                    $("#cart").load("?item=cart&ajax=1&t="+timeStamp+"&l="+mRandom);
                },
                error: function()
                {
                    alert('Error occurred.');
                }
            });
        }
    });
});
	
	$(document).ready(function()
	{
		$(".clsAssoc").live("change", function() 
		{
			var mPrice = $(this).attr("price");			
			var mID = $(this).attr("id");
			var mChecked = 0;

			if ($(this).is(':checked'))
			{
				mChecked = 1;
			}
			
			if ($.trim(mPrice)!="")
			{
				if (isNumeric(mPrice))
				{
					mPreviousPrice = $('span[id=retail_price]:last').html();
					mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
					mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
					mTxtChecked = $('input[id=txtAssoc]:last').val();
					
					if (mChecked==1)
					{
						mTxtChecked = mTxtChecked+mID+",";
						mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
						$('input[id=txtAssoc]:last').val(mTxtChecked);
					}
					else if (mChecked==0)
					{
						mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPrice);									
						mTxtChecked = mTxtChecked.replace(mID+",", '');
						$('input[id=txtAssoc]:last').val(mTxtChecked);
					}
				}
				mPreviousPrice = mPreviousPrice.toFixed(2);
				$('span[id=retail_price]:last').html("<?=$currency?>"+mPreviousPrice);
			}
		});
		
		$(".inputAttr").live("change", function() 
		{
			var mPrice = $(this).attr("price");
			var mType = $(this).attr("type");
			var mID = $(this).attr("id");
			var mAttributeID = $(this).attr("attributeid");
			var mLimit = $(this).attr("limit");
			var mLimitPrice = $(this).attr("limitprice");
			
			var mChecked = 0;
			if (mType=="checkbox")
			{
				if ($(this).is(':checked'))
				{
					mChecked = 1;
				}
			}

			if ($.trim(mPrice)!="")
			{
				if (isNumeric(mPrice))
				{
					mPreviousPrice = $('span[id=retail_price]:last').html();
					mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
					mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');		
					mTxtChecked = $('input[id=txtChecked'+mAttributeID+']:last').val();
					if (mChecked==1)
					{
						mTxtChecked = mTxtChecked+mID+",";
						var mTmp = mTxtChecked.split(",");
						var mLength = mTmp.length;
						if ($.trim(mLimit)!="")
						{
							if (mLength-1>mLimit)
							{
								mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice) + parseFloat(mLimitPrice);
							}
							else
							{
								mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
							}
						}
						else
						{
							mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
						}
						$('input[id=txtChecked'+mAttributeID+']:last').val(mTxtChecked);
					}
					else if (mChecked==0)
					{
						var mTmp = mTxtChecked.split(",");
						var mLength = mTmp.length;
						if ($.trim(mLimit)!="")
						{
							if (mLength-1<=mLimit)
							{
								mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPrice);
							}
							else
							{
								mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPrice) - parseFloat(mLimitPrice);
							}
						}
						else
						{
							mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPrice);
						}
						mTxtChecked = mTxtChecked.replace(mID+",", '');
						$('input[id=txtChecked'+mAttributeID+']:last').val(mTxtChecked);
					}
					mPreviousPrice = mPreviousPrice.toFixed(2);
					mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
					$('span[id=retail_price]:last').html(mPreviousPrice);
				}
				else
				{
					mPreviousPrice = $('span[id=retail_price]:last').html();
					mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
					mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
					mTxtChecked = $('input[id=txtChecked'+mAttributeID+']:last').val();
					if (mChecked==1)
					{
						mTxtChecked = mTxtChecked+mID+",";
						var mTmp = mTxtChecked.split(",");
						var mLength = mTmp.length;
						if ($.trim(mLimit)!="")
						{
							if (mLength-1>mLimit)
							{
								mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mLimitPrice);
							}
						}
						$('input[id=txtChecked'+mAttributeID+']:last').val(mTxtChecked);
					}
					else if (mChecked==0)
					{
						var mTmp = mTxtChecked.split(",");
						var mLength = mTmp.length;
						if ($.trim(mLimit)!="")
						{
							if (mLength-1>mLimit)
							{
								mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mLimitPrice);
							}
						}
						mTxtChecked = mTxtChecked.replace(mID+",", '');
						$('input[id=txtChecked'+mAttributeID+']:last').val(mTxtChecked);
					}
					mPreviousPrice = mPreviousPrice.toFixed(2);
					mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
					$('span[id=retail_price]:last').html(mPreviousPrice);
				}
			}
			else
			{
				mPreviousPrice = $('span[id=retail_price]:last').html();
				mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
				mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
				mTxtChecked = $('input[id=txtChecked'+mAttributeID+']:last').val();
				if (mChecked==1)
				{
					mTxtChecked = mTxtChecked+mID+",";
					var mTmp = mTxtChecked.split(",");
					var mLength = mTmp.length;
					if ($.trim(mLimit)!="")
					{
						if (mLength-1>mLimit)
						{
							mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mLimitPrice);
						}
					}
					$('input[id=txtChecked'+mAttributeID+']:last').val(mTxtChecked);
				}
				else if (mChecked==0)
				{
					var mTmp = mTxtChecked.split(",");
					var mLength = mTmp.length;
					if ($.trim(mLimit)!="")
					{
						if (mLength-1>mLimit)
						{
							mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mLimitPrice);
						}
					}
					mTxtChecked = mTxtChecked.replace(mID+",", '');
					$('input[id=txtChecked'+mAttributeID+']:last').val(mTxtChecked);
				}
				mPreviousPrice = mPreviousPrice.toFixed(2);
				mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
				$('span[id=retail_price]:last').html(mPreviousPrice);
			}
		});
		
		$(".inputAttrRB").die('change').live("change", function() 
		{
			var mPrice = $(this).attr("price");
			var mID = $(this).attr("id");
			var mName = $(this).attr("name");
			var mAttributeID = $(this).attr("attributeid");
			var mTxtChecked = $('input[id=txtChecked'+mAttributeID+']:last').val();
			
			if ($.trim(mTxtChecked)!="")
			{
				$('input[name="'+mName+'"]').each(function (index) 
				{
					var mLoopPrice = $(this).attr("price");
					var mLoopID = $(this).attr("id");
					
					if ($.trim(mTxtChecked).indexOf(mLoopID+",")>=0)
					{
						mPreviousPrice = $('span[id=retail_price]:last').html();
						mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
						mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
						mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mLoopPrice);

						if ($.trim(mPrice)!="")
						{
							mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
						}
						
						mPreviousPrice = mPreviousPrice.toFixed(2);
						mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
						$('input[id=txtChecked'+mAttributeID+']:last').val(mID+",")
						$('span[id=retail_price]:last').html(mPreviousPrice);
						return false;
					}
				});	
			}
			else
			{
				mPreviousPrice = $('span[id=retail_price]:last').html();
				mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
				mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');

				if ($.trim(mPrice)!="")
				{
					mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
				}
				
				mPreviousPrice = mPreviousPrice.toFixed(2);
				mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
				$('input[id=txtChecked'+mAttributeID+']:last').val(mID+",")
				$('span[id=retail_price]:last').html(mPreviousPrice);
			}
		});
		
		$(".inputAttrDD").live("change", function() 
		{
			var mTextboxId = $(this).attr("textboxid");
			var mID = $(this).attr("id");
			var mPrice = $('option:selected', this).attr('price');
			var mOptionID = $('option:selected', this).attr('optionid');
			
			if ($(this).val()!="")
			{
				if ($.trim($("#"+mTextboxId).val())!="")
				{
					$("#"+mID+" > option").each(function() 
					{
						var mOptionIDLoop = $(this).attr("value");
						var mPriceLoop = $(this).attr("price");
						if ($.trim(mOptionIDLoop)!="")
						{
							if ($.trim($("#"+mTextboxId).val()).indexOf(mOptionIDLoop+",")>=0)
							{
								mPreviousPrice = $('span[id=retail_price]:last').html();
								mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
								mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
								mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPriceLoop);
		
								if ($.trim(mPrice)!="")
								{
									mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
								}
								
								mPreviousPrice = mPreviousPrice.toFixed(2);
								mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
								$("#"+mTextboxId).val($("#"+mTextboxId).val().replace(mOptionIDLoop+",", ""));
								$("#"+mTextboxId).val(mOptionID+",")
								$('span[id=retail_price]:last').html(mPreviousPrice);
							}
						}
					});
				}
				else
				{
					if ($.trim(mPrice)!="")
					{
						$("#"+mTextboxId).val($('option:selected', this).attr('optionid')+",")
						mPreviousPrice = $('span[id=retail_price]:last').html();
						mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
						mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
						mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
						mPreviousPrice = mPreviousPrice.toFixed(2);
						mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
						$('span[id=retail_price]:last').html(mPreviousPrice);
					}
				}
			}
			else
			{
				if ($.trim($("#"+mTextboxId).val())!="")
				{
					$("#"+mID+" > option").each(function() 
					{
						var mOptionIDLoop = $(this).attr("value");
						var mPriceLoop = $(this).attr("price");
						
						if ($.trim(mOptionIDLoop)!="")
						{
							if ($.trim($("#"+mTextboxId).val()).indexOf($.trim(mOptionIDLoop)+",")>=0)
							{
								mPreviousPrice = $('span[id=retail_price]:last').html();
								mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
								mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
								if (isNumeric(mPriceLoop))
								{
									mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPriceLoop);
									mPreviousPrice = mPreviousPrice.toFixed(2);
								}
								mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
								$("#"+mTextboxId).val($("#"+mTextboxId).val().replace(mOptionIDLoop+",", ""));
								$('span[id=retail_price]:last').html(mPreviousPrice);
							}
						}
					});
				}
			}
		});
	});
</script>

<div id="body_left_col">
    <?php
    $firstindex = 0;
    $current_cat1 = 0;
    $current_cat = 0;
    while ($loop_index < 1) 
	{
        foreach ($menus_details as $menulist) 
		{
            	if ($loop_index_check == FALSE) 
				{
	?>

                <div class="left_col_inner_block" <? if ($loop_index == 1) { ?> style="margin-left:10px;" <? } ?>>
    <?php
    				$loop_index_check = TRUE;
           		}

           		if ((($current_cat1 != $menulist->sub_cat_id || $firstindex1 == 0)) || ($menulist->sub_cat_id==""))
				{
					$index++;
                	if ($index == $mDivider)
					{
	?>
                    </div>

                    <div class="left_col_inner_block" style="margin-left:10px;">
<?php
				}
?>
                    <div class="listing_area">           
                        <div class="product" <?php echo($menulist->display); ?>>
                            <?= stripslashes($menulist->cat_name) ?> <br /><span style="font-size:12px; font-weight:normal;"><?= stripslashes($menulist->cat_des) ?></span></div>
    <?php
    			}

				if ($current_cat == $menulist->sub_cat_id || $firstindex == 0) 
				{
					$display = 'block';
					if($menulist->signature_sandwitch_id > 0){
						$ss_obj  = dbAbstract::ExecuteObject("select start_date,end_date from bh_signature_sandwitch where id='".$menulist->signature_sandwitch_id."'");
						if(strtotime($ss_obj->start_date) > strtotime(date("Y-m-d")) || strtotime($ss_obj->end_date) < strtotime(date("Y-m-d"))){
							$display = 'none';
						}
					}
   ?>
                        <div class="products_area" style="height: 40px;  <?php if (($menulist->status!=1) || ($menulist->display==" style='display: none;' ") || ($display=='none')) { echo("display: none;"); }?>">
                            <table style="margin: 0px; width: 100%;" cellpadding="0" cellspacing="0" border="0">
                                <tr style="height: 40px;">
                                    <td style="width: 2%;"></td>
                                    <td align="left" style="width: 88%; font-size:12px;" valign="top">
                                        <div class="product_name">
                                          <a id='showProductPopup' myItemTitle="<?= htmlspecialchars(trim(str_replace("&#39;", "'", str_replace("<br />", "\n", str_replace("<br/>", "\n", str_replace("<br>", "\n", $menulist->item_title)))))) ?>" myItemDescription="<?= $function_obj->_esc_xmlchar(trim(str_replace("&#8482;", "<sub>™</sub>", str_replace("&#169;", "<sub>©</sub>", str_replace("&#39;", "'", str_replace("&#174;", "<sub>®</sub>", str_replace("&#228;", "ä", str_replace("&#232;", "è", str_replace("&#241;", "ñ", $menulist->item_des))))))))) ?>" myItemImage="<?= trim($menulist->item_image) ?>" onMouseout="hideddrivetip()" <? if ($iscurrentMenuAvaible == 0) { ?>  href="javascript:alert('menu is not available at this time');" <? } else { ?> href="#"  onclick="event.preventDefault();showPopup(<?php echo $menulist->prd_id; ?> , <?php echo $menulist->HasAssociates; ?>, <?php echo $menulist->HasAttributes; ?>,-1);"<? } ?> >
											
											<?= stripslashes($menulist->item_title)?>
                                        </a>
                                      </div>
                                    </td>
                                    <td align="right" valign="top" style="width: 10%; font-size:12px;">
                                        <div class="product_price"><?= $menulist->retail_price ?>&nbsp;&nbsp;</div>
                                    </td>
                                </tr>
                            </table>	
                        </div>
	<?php
    				$current_cat = $menulist->sub_cat_id;
				} 
				else 
				{
            		$current_cat = $menulist->sub_cat_id;
                	$firstindex = 1;
            	}
	?>
                    <div style="clear:both"></div>
    <?php
				if ((($current_cat1 != $menulist->sub_cat_id || $firstindex1 == 0)) || ($menulist->sub_cat_id=="")) 
				{
            		$firstindex1 = 1;
					$current_cat1 = $menulist->sub_cat_id;
    ?>
                    </div>

    <?php
            	}
		}
        ++$loop_index;
	}
    ?>
    </div>    
</div>

<div id="body_right_col" style="float:right;">
    <script language="javascript" type="text/javascript">
        function hideShowDeliveryMethod(val)
        {
            if (val == "delivery")
            {
                window.location.href = "?item=detail&method=" + val;
            }
            else if (val == "pickup")
            {
                window.location.href = "?item=detail&method=" + val;
            }
        }

        function PlaceOrder(subtotal, min_order, receiving_method)
        {
            var minitotal = min_order
            var sess_val = "";
            var item_val = "detail";
            if (subtotal >= minitotal || receiving_method == 'pickup')
            {
                if (sess_val != "" || item_val == "logincart")
                {
                    document.form1.action = "?item=confirmorder";
                }
                else
                {
                    document.form1.action = "?item=logincart";
                }

                if (receiving_method != 'disabled' && receiving_method == '')
                {
                    alert('Please select Pickup or Delivery');
                    document.form1.action = "?item=detail";
                }
                else
                {
                    document.form1.submit();
                }
            }
            else if (subtotal < minitotal && receiving_method == 'delivery')
            {
                alert('<?=$currency?>' + minitotal + ' of food required to checkout. Please add more items');
            }
        }

        function UpdateCart()
        {
            document.form1.action = "?mod=resturants&item=updatecart";
            document.form1.submit();
        }

        // Go Back where this page was previousely called.
        function ReturnBack()
        {
            window.location.href = "?mod=resturants&item=product";
        }//function

        jQuery(document).ready(function($)
        {
            $(tipobj).mouseover(function()
            {
                mouseOnPopupDiv = true;
            });

            $(tipobj).mouseout(function()
            {
                mouseOnPopupDiv = false;
            });

            $('.product_name a').mouseover(function(e)
            {
                mouseOnProductName = true;
                var itemTitleIs = $(this).attr('myItemTitle');
                var itemDescIs = $(this).attr('myItemDescription');
                mTmpDesc = itemDescIs;
                mTmpDesc = mTmpDesc.split("&#174;").join("<sub>&#174;</sub>");
                mTmpDesc = mTmpDesc.split("&#8482;").join("<sub>&#8482;</sub>");
                mTmpDesc = mTmpDesc.split("&#169;").join("<sub>&#169;</sub>");
                mTmpDesc = mTmpDesc.split("®;").join("<sub>®</sub>");
                mTmpDesc = mTmpDesc.split("©").join("<sub>©</sub>");
                mTmpDesc = mTmpDesc.split("™").join("<sub>™</sub>");
                itemDescIs = mTmpDesc;
                var imageHTML = '';
                var myNewHTML = $('<div class="popupDiv"><span>' + itemTitleIs + '</span><br /><br />' + itemDescIs + ' ' + imageHTML + '</div>');
                ddrivetip(myNewHTML.html());
                positiontip(e);
                $("a[rel^='prettyPhoto']").prettyPhoto()
            });
        });
    </script>
    <?php
    if ($objRestaurant->announcement != "" && $objRestaurant->announce_status == '1') {
        ?>
        <div style="background-color:#EAEAEC;  margin-bottom:10px; border:1px solid #A4A4A4; color:#F00; font-size:12px;">
            <div style="float:left; padding:5px 5px;"><img src="<?= $SiteUrl ?>images/dialog_warning.png" width="30"  /></div>
            <div style="padding:15px 5px 0px 5px;"><?= $objRestaurant->announcement ?></div>
            <br style="clear:both"  />
        </div>
        <?php
    }
    ?>
    <!-- Loyality Box Code Starts //Gulfam -->
    <div id="dvLB">
        <?php
        if (isset($mSLB)) {
            if ($mSLB == 1) {
                ?>
                <?php
                if ($loggedinuser->valuetec_card_number > 0) {
                    ?>  
                    <div class="heading">YOUR VIP REWARDS <span class="rewardamount">$<?= $loggedinuser->valuetec_reward ?></span></div>
                    <table class="listing1" width="100%" cellpadding="0px" cellspacing="0px" border="0" style="border: 1px solid #e4e4e4; border-bottom: none;">
                        <tr>
                            <td id="rewardwrap">
                                <div class="bar">
                                    <div class="barOuter">
                                        <? $reward_points = number_format((($loggedinuser->valuetec_points / $objRestaurant->rewardLevel) * 100), 0); ?>
                                        <div class="barInner" <? if ($reward_points >= 5) { ?>  style="width:<?= $reward_points; ?>%;max-width:100%;" <? } ?>></div>
                                    </div>    
                                    <div class="points"><?= $loggedinuser->valuetec_points ?> pts</div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                                <div class="nextreward">Next reward: $<?= $objRestaurant->rewardAmount ?> @ <?= $objRestaurant->rewardLevel ?> pts</div>
                            </td>
                        </tr>
                    </table>
                    <br />
                    <?php
                } elseif ($loggedinuser->valuetec_card_number == 0 || empty($loggedinuser->valuetec_card_number)) {
                    ?>
                    <table class="listing1" width="100%" cellpadding="0px" cellspacing="0px" border="0" style="border: 1px solid #e4e4e4; border-bottom: none;">
                        <tr>
                            <td id="rewardwrap" style="width: 250px !important;">
                                <table style="width: 100%; margin: 0px;" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="width: 55%;">
                                            <img src="../images/vip.png" />
                                        </td>
                                        <td style="font-size: 11px; width: 45%;margin-right: 2px !important;" valign="middle" align="left">
                                            <a style="text-decoration:none" rel="facebox" href="?item=valutec&ajax=1">Click here to register your VIP card</a>
                                        </td>
                                    </tr>
                                </table>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </table>
                    <?php
                }
            }
        }
        ?>
    </div>
    <!-- Loyality Box Code Ends //Gulfam -->
    <div id="cart">
        <?php
        require($site_root_path . "views/cart/cart.php");
        ?>
    </div>
</div>
<div style="clear:left"></div>
<div class="clear"></div>


<div id="BodyLeftArea" style="width:750px;display:none;">
    <form action="" method="post" id="frmPrd" name="frmPrd">
        <script type="text/javascript">
                jQuery(document).ready(function($) 
                {
                        $("a[rel^='prettyPhoto']").prettyPhoto();
                });
        </script>
        <div style="width: 65%; height: 80px;">
            <div style="margin:6px 6px 6px 0; width: 75px; float: left;" id="popupProductImage"> 
            </div>
            <div style="float: left; margin-top: 9px;" id="item_detail_wrapper">
                <div style="width:380px; margin-top:5px;"> 
                    <strong style="padding-top:3px;" id="item_title"></strong>
                    <br>
                    <span style="font-size:12px;" id="item_des"></span> 
                </div>
                <div style="clear:both"></div>
                <div style="margin-top: 6px;"> 
                    <strong>Item Price:</strong> <span style="font-size:12px; color:#ff0000;" id="retail_price"></span>
                </div>
            </div>
            <br>
        </div>
    
        <div style="clear: both;"></div>
		
        <div id="updateMessage" style="margin-bottom: 10px; color:#9C0F17; margin-top:10px;display:none;font-size:14px;">Please make a selection to continue.</div>
        
        <div id='attributes_wrapper'></div>
        <div id="association_wrapper"></div>
        <hr width="96%" size="1" class="hr">
        <div>
            <label><strong>Quantity:</strong></label>
			<script type="text/javascript" language="javascript">
				$(document).ready(function () 
				{
					$(".qnty").keydown(function (e) 
					{
						if ($.inArray(e.keyCode, [8, 9, 27, 13]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) 
						{
							return;
						}

						if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) 
						{
							e.preventDefault();
						}
					});
				});
			</script>
            <input name="quantity" type="text" id="quantity" tabindex="1" title="Quantity" maxlength="3" value="1" size="5"  class="qnty">
			<input type="hidden" name="totalattributes" id="totalattributes" value="-1">
        </div>
        <br>
        <div class="attribute">
            <br>
            <label>Who is this item for</label>
            <br>
            <input name="item_for" type="text" id="item_for" tabindex="1" value="" size="25">
        </div>
        <br>
        <div class="attribute">
            <label>List any special requests or notes</label>
            <br>
            <textarea name="requestnote" id="requestnote" tabindex="3" cols="35" rows="4"></textarea>
        </div>
        <div class="attribute">
            <div style="float: left;width:100px"><input type="submit" name="addtocart" id="addtocart" value="Add to Cart" ></div>
            <div id="updateMessage1" style="padding:3px; color:#9C0F17; margin-top:10px;display:none;font-size:14px;">Please make a selection to continue.</div>
        </div>

        <div style="height:5px;">&nbsp;</div>


        <input type="hidden" id="product_id_field" name="product_id_field">
        <input type="hidden" id="product_sale_price" name="product_sale_price">
        <input type="hidden" id="HasAssociates" name="has_associates">
        <input type="hidden" id="HasAttributes" name="has_attributes">
		<input type="hidden" name="totalattributes" id="totalAttributes">
		<input type="hidden" id="cartItemIndex" name="cartItemIndex" value="-1">
    </form>
	<div style=" float:right;right:15px; bottom:10px; position: absolute;">
            <input id="close" type="image" src="<?= $SiteUrl ?>images/closelabel.gif" onclick="$(document).trigger('close.facebox');"/>
        </div>
</div>