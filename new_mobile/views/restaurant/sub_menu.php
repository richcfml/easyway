<body class="index">
    <?php require($mobile_root_path . "includes/header.php"); ?>
    <main class=main>
        <div class=main__container>
        <section class='menu   editItemPopper ' style="display: none">
                            <header class="menu__header"> 
                                <h2 class="menu__header-title">
                                  
                                </h2> 
                                <i class="menu__header-action"></i> 
                            </header>
<!--                            <h2 class=menu__header>  <?php echo stripslashes($category->cat_name); ?></h2>-->
                            <div class="menu__content ">
                                    <article data-onclick="showPopup(170504, 1, 1, -1, this)" class="menu-item 170504">
                                                                                                            
                                            <header class="menu-item__header" flag="0">
                                                <hgroup class="menu-item__info">
                                                    <h3 style="margin-right: 30px;" id="item_title"></h3>
                                                    <h4 id="retail_price" style="display: inline-block; height: 34px"></h4>
                                                   
                                                </hgroup>
                                            </header>
                                            <form price="5.00" prd_id="170504" hasassociates="1" hasattributes="1" class="menu-item__form">
                                                <!--attributes loop-->
                                                <input type="hidden" id="product_id_field" name="product_id_field">
                                                <input type="hidden" id="product_sale_price" name="product_sale_price">
                                                <input type="hidden" id="hasAssociates" name="has_associates">
                                                <input type="hidden" id="hasAttributes" name="has_attributes">
                                                <input type="hidden" name="totalattributes" id="totalAttributes">
                                                <input type="hidden" id="cartItemIndex" name="cartItemIndex" value="-1">
                                                
                    
                                            </form>
                                        </article>
                
                            </div>
            </section>
        
            <?php
            if ($mod == 'resturants') {
                if ($menuid > 0) {
                    ?>
                    <!-- pull down menu-->

                    <?php
                    $arr = product::getProductsByMenuId($menuid);
                    $menus_details = $arr['details'];


                    $objCategory->menu_id = $menuid;
// used in header
                    $categoryid = (isset($_GET['category']) ? $_GET['category'] : "");
                    $categories = $objCategory->getCategoryByMenuId();

                    for ($i = 0; $i < count($categories); $i++) {
                        $class = '';
                        $category = $categories[$i];
                        $categoryid = $category->cat_id;
                        if ($i == 0 && $categoryid == "") {
                            $categoryid = $category->cat_id;
                            $class = ' current_menulist';
                        } elseif ($categoryid == $category->cat_id) {
                            $class = ' current_menulist';
                        }
                        ?>
                        <section class=menu>
                            <header class="menu__header"> 
                                <h2 class="menu__header-title">
                                   <?php echo stripslashes($category->cat_name); ?>
                                </h2> 
                                <i class="menu__header-action"></i> 
                            </header>
<!--                            <h2 class=menu__header>  <?php echo stripslashes($category->cat_name); ?></h2>-->
                            <div class="menu__content ">
                                <?php
                                if ($menuid > 0) {
                                    $product = new Product();
                                    $products = $product->getProductsByCategoryId($categoryid);

                                    foreach ($products as $product) {
                                        //  $product = product::getDetailsByProductId($product->prd_id);
                                        $attribute_index = 0;
                                        $attribute_name = "attr";
                                        $attribute_parent_name = "attrname";
                                        $totalattributes = count($product->distinct_attributes);
                                        $display = 'block';
                                        if($product->signature_sandwitch_id > 0){
                                                $ss_obj  = dbAbstract::ExecuteObject("select start_date,end_date from bh_signature_sandwitch where id='".$product->signature_sandwitch_id."'");
						if(strtotime($ss_obj->start_date) > strtotime(date("Y-m-d")) || strtotime($ss_obj->end_date) < strtotime(date("Y-m-d"))){
							$display = 'none';
						}
					}
                                        ?>
                                        
                                <article style="display: <?= $display?>" data-onclick="showPopup(<?php echo $product->prd_id; ?>, <?php echo $product->HasAssociates; ?>, <?php echo $product->HasAttributes; ?>, -1, this)" class="menu-item <?php echo $product->prd_id; ?>">
                                            <?php if (!empty($product->item_image)) : ?>   
                                                                     <div class=menu-item__image style='background-image: url(<?= $SiteUrl . "images/item_images/" . $product->item_image ?>)'></div>
                                                                <?php endif;?>
                                            
                                            <header <?php if ($iscurrentMenuAvaible == 0) { ?> style="cursor:pointer" onClick="javascript:alert('menu is not available at this time');" <?php } else { ?> class='menu-item__header' <?php } ?> flag="0">
                                                <hgroup class=menu-item__info>
                                                    <h3 style="margin-right: 30px;" id="item_title"><?php echo stripcslashes($product->item_title); ?></h3>
                                                    <h4  id="retail_price" style="display: inline-block; height: 34px"> <?php echo $currency . $product->retail_price; ?></h4>
                                                    
                                                    <?php
                                                    if ($product->item_type != '') {

                                                        if (strpos('x'.$product->item_type, '0')  ) {
                                                            echo('<i class="identifier__new"></i>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '1')  ) {
                                                            echo('<i class="identifier__veggie"></i>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '2') ) {
                                                            echo('<i class="identifier__pop"></i>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '3') ) {
                                                            echo('<i class="identifier__nut-free"></i>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '4') ) {
                                                            echo('<i class="identifier__glutten-free"></i>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '5') ) {
                                                            echo('<i class="identifier__low-fat"></i>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '6') ) {
                                                            echo('<i class="identifier__vegan"></i>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '7') ) {
                                                            echo('<i class="identifier__spicy"></i>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '8') ) {
                                                            echo('<i class="identifier__spicy"></i><i class="identifier__spicy"></i>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '9') ) {
                                                            echo('<i class="identifier__spicy"></i><i class="identifier__spicy"></i><i class="identifier__spicy"></i>');
                                                        }
                                                        if (strpos('x'.$product->item_type, 'B') ) {
                                                            $bhBack="../css/new_mobile/images/bh.svg";
                                                            echo('<i class="identifier__bh"   ></i>');
                                                        }
                                                    }
                                                    ?>
                                                </hgroup>
                                            </header>
                                            <form price="<?php echo $product->retail_price; ?>" prd_id="<?php echo $product->prd_id; ?>" HasAssociates="<?php echo $product->HasAssociates; ?>" cartItemIndex="-1"    HasAttributes="<?php echo $product->HasAttributes; ?>"     class=menu-item__form>
                                                <!--attributes loop-->
                                                <input type="hidden" id="product_id_field" name="product_id_field">
                                                <input type="hidden" id="product_sale_price" name="product_sale_price">
                                                <input type="hidden" id="hasAssociates" name="has_associates">
                                                <input type="hidden" id="hasAttributes" name="has_attributes">
                                                <input type="hidden" name="totalattributes" id="totalAttributes">
                                                <input type="hidden" id="cartItemIndex" name="cartItemIndex" value="-1">
                                                
                    <?php ?>

                                            </form>
                                        </article>

                                        <?php
                                    }
                                }
                                ?>


                            </div>
                        </section>


                    <?php } ?>


                    <?php
                }
            }
            ?>
        </div>
    </main>
	
    <!--	Make Order -->
    <div class=notification id=make-order>
        <div class='notification__box center-text'>
            <header class=notification__box-header> <a class=notification__box-action href="#">X</a>
                <h3 class=notification__box-title>1. Make your order</h3>
            </header>
            <div class=notification__box-content>
                <p> You're responding to a group order request. <br>
                    Choose the items you want to add to your cart. </p>
            </div>
        </div>
    </div>
    
    <div class=notification id=order-sent>
        <div class=notification__box>
            <header class=notification__box-header> <a class=notification__box-action href="#">X</a>
                <h3 class=notification__box-title>Notification Sent!</h3>
            </header>
            <div class=notification__box-content>
                <p style="text-align:center;">Users have been notified. Please make your order.</p>
            </div>
        </div>
    </div>
    <?php
    $item = 'cart';
	$grouporderingUrl='';
	if(isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]) && isset($_GET["grp_keyvalue"])){
	  $item = 'grouporder';
	  $grouporderingUrl = '&grp_userid='.$_GET["grp_userid"].'&grpid='.$_GET["grpid"].'&uid='.$_GET["uid"].'&grp_keyvalue='.$_GET['grp_keyvalue'];
	}
	?>
    
    <script>
        /*------------------------------Naveed Start---------------------------------------------------*/
        var attributeRequired;
        /*------------------------------Naveed End-----------------------------------------------------*/
        var siteUrl = '<?php echo $SiteUrl ?>';
        var restauranrUrl= '<?php echo $objRestaurant->url ?>';
        var currency = '<?php echo $currency ?>';
        var currentClass='';
        
        
        
        function isNumeric(n)
        {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }
        menu_details = <?php echo json_encode($menus_details) ?>;

        function setProductDetailsToPopup(productDetails) {

            $('#item_title').html(productDetails.item_title);
            $('#item_des').html(productDetails.item_des);

            
             
            if (productDetails.item_type) {
                var product_item_type = productDetails.item_type;
                if($('.active_form #cartItemIndex').val()!=-1){ 
                    $('.active_form hgroup i').remove()
                if (product_item_type.indexOf('0') !== -1) {
                    $('.active_form  #retail_price').after('<i class="identifier__new"></i>' )
                }
                if (product_item_type.indexOf('1') !== -1) {
                    $('.active_form #retail_price').after('<i class="identifier__veggie"></i>' )
                }
                if (product_item_type.indexOf('2') !== -1) {
                    $('.active_form  #retail_price').after('<i class="identifier__pop"></i>')
                }
                if (product_item_type.indexOf('3') !== -1) {
                    $('.active_form  #retail_price').after('<i class="identifier__nut-free"></i>')
                }
                if (product_item_type.indexOf('4') !== -1) {
                    $('.active_form  #retail_price').after('<i class="identifier__glutten-free"></i>')
                }
                if (product_item_type.indexOf('5') !== -1) {
                    $('.active_form  #retail_price').after('<i class="identifier__low-fat"></i>')
                }
                if (product_item_type.indexOf('6') !== -1) {
                   $('.active_form  #retail_price').after('<i class="identifier__vegan"></i>')
                }
                if (product_item_type.indexOf('7') !== -1) {
                   $('.active_form  #retail_price').after('<i class="identifier__spicy"></i>' )
                }
                if (product_item_type.indexOf('8') !== -1) {
                   $('.active_form  #retail_price').after('<i class="identifier__spicy"></i><i class="identifier__spicy"></i>' )
                }
                if (product_item_type.indexOf('9') !== -1) {
                    $('.active_form  #retail_price').after('<i class="identifier__spicy"></i><i class="identifier__spicy"></i><i class="identifier__spicy"></i>' )
                }
                if (product_item_type.indexOf('B') !== -1) {
                   $('.active_form  #retail_price').after('<i class="identifier__bh"   ></i>' )
                }
                }
            }
            $('  #edit_item  #retail_price').html('<?= $currency ?>' + productDetails.retail_price);
            $('#product_sale_price').val(productDetails.sale_price);
        }

       function showPopup(productId,hasAssociates,hasAttribute,cartItemIndex) {
        currentClass='#edit_item'
        EasyWay.Cart.close()
        $('#facebox').css("position","absolute");
        $('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) + "px");
        $('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
        $('#edit_item #attributes_wrapper').html('');
        $('#edit_item #association_wrapper').html('');
        $('#item_image_link').remove();
        $('.editItemPopper form').attr('hasassociates',hasAssociates)
        $('.editItemPopper form').attr('hasattributes',hasAttribute)
        $('.editItemPopper form').attr('cartItemIndex',cartItemIndex)
        $('.editItemPopper form').attr('prd_id',productId)
        $('.editItemPopper form').closest('article').addClass('active_form')
        $('.editItemPopper .menu-item__header').attr('flag','0')
        $('.editItemPopper ').css('display', 'block')
        $('.editItemPopper .menu__header').addClass('open')
        $('.editItemPopper .menu__content').addClass('open')
        //$('.editItemPopper .menu__header ').click()
        $('.editItemPopper .menu-item__header').click()
        return 
        
        
        
        
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

        $('#edit_item #product_id_field').val(productId);
        $('#edit_item #totalattributes').val(<?php echo $attributeCount; ?>);
        $('#edit_item #HasAssociates').val(hasAssociates);
        $('#edit_item #HasAttributes').val(hasAttribute);
        $('#edit_item #cartItemIndex').val(cartItemIndex);
        
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
                    $('#edit_item #totalAttributes').val(totalattributes);
                    
                    if(data.hasOwnProperty('productDetails'))
                    {
                        //Means Edit Item function called from cart
                        // So, call setProductDetailsToPopup function to set extra details of product to popup
                        productDetails = data.productDetails;
                        setProductDetailsToPopup(productDetails);
                    }
                    
                    if(data.hasOwnProperty('quantity')){
                        var quantity = data.quantity;
                        $('#edit_item #quantity').val(quantity);
                    }else
                    {
                        $('#edit_item #quantity').val(1);
                    }
                    if(data.hasOwnProperty('item_for')){
                        var item_for = data.item_for.replace(/&#39;/g, "'");
                        $('#edit_item #item_for').val(item_for);
                    }else
                    {
                        $('#edit_item #item_for').val('');
                    }
                    if(data.hasOwnProperty('requestnote')){
                        var requestnote = data.requestnote.replace(/&#39;/g, "'");
                        $('#edit_item #requestnote').val(requestnote);
                        $('#edit_item #requestnote').text(requestnote);
                    }else
                    {
                        $('#edit_item #requestnote').val('');
                        $('#edit_item #requestnote').text('');
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

                                    $.each(attribute.attributes, function (i, attribute1) {
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
                                    $.each(attributes, function (i, attribute_option) {
                                        var attrid = '';
                                        attribute_option_index += 1;
                                        selected = '';
                                        attribute_option.Price = $.trim(attribute_option.Price);
                                        attribute_option.Price = attribute_option.Price.replace("/[^0-9.]+/", '');

                                        if (!isNaN(attribute_option.Price) && attribute_option.Price != 0) {
                                            if (attribute_option.Price[0] == '-') {
                                                if (attribute_option.add_to_price == 1 || attribute_option.add_to_price == '') {
                                                    attribute_option.displayprice = "<span class='red'> - Subtract <?= $currency ?>" + attribute_option.Price.replace("/[^0-9.]+/", '') + "</span>";
                                                } else {
                                                    attribute_option.displayprice = "<span class='red'>  <?= $currency ?>" + (parseFloat(attribute_option.Price) + parseFloat(productDetails.retail_price)) + ''.replace("/[^0-9.]+/", '') + "</span>";
                                                }
                                            } else {
                                                attribute_option.add_to_price;
                                                if (attribute_option.add_to_price == 1 || attribute_option.add_to_price == '') {
                                                    attribute_option.displayprice = "<span class='red'> + Add <?= $currency ?>" + attribute_option.Price.replace("/[^0-9.]+/", '') + "</span>";
                                                } else {
                                                    attribute_option.displayprice = "<span class='red'>  <?= $currency ?>" + (parseFloat(attribute_option.Price) + parseFloat(productDetails.retail_price)) + ''.replace("/[^0-9.]+/", '') + "</span>";
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
                                            mPreviousPrice = $('#edit_item #retail_price').html(); 
                                            mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
											mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                                            mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(attribute_option.Price);
                                            mPreviousPrice = mPreviousPrice.toFixed(2);
                                            mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
                                            $('#edit_item #retail_price').html(mPreviousPrice);
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
													mPreviousPrice = $('#edit_item #retail_price').html();
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
													$('#edit_item #retail_price').html(mPreviousPrice);
													mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
												}
												else
												{
													mPreviousPrice = $('#edit_item #retail_price').html();
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
													$('#edit_item #retail_price').html(mPreviousPrice);
													mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
												}
											}
											else
											{
												mPreviousPrice = $('#edit_item #retail_price').html();
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
												$('#edit_item #retail_price').html(mPreviousPrice);
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
	                                            mPreviousPrice = $('#edit_item #retail_price').html();
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
	                                            $('#edit_item #retail_price').html(mPreviousPrice);
	                                            mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
	                                        }
	                                        else
	                                        {
	                                            mPreviousPrice = $('#edit_item #retail_price').html();
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
	                                            $('#edit_item #retail_price').html(mPreviousPrice);
	                                            mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
	                                        }
	                                    }
	                                    else
	                                    {
	                                        mPreviousPrice = $('#edit_item #retail_price').html();
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
	                                        $('#edit_item #retail_price').html(mPreviousPrice);
	                                        mTxtCheckedLoad = mTxtCheckedLoad+attribute_option.id+",";
	                                    }
									}
                                }
								
                                html = html + '<td style="display: block;" class="attribute"> <input limit="'+mLimit+'" limitprice="'+mLimitPrice+'" price="'+attribute_option.Price+'" id="'+attribute_option.id+'" attributeid="'+attribute.id+'" name="' + attribute_name + '" class="' + (attribute.Type == 2 ? 'inputAttr' : 'inputAttrRB') + '" type="' + (attribute.Type == 2 ? 'checkbox' : 'radio') + '" value="' + attribute_option.id + '" ' +  (mAddFlag1==0 ? (attribute_option.Default == 1 ? 'checked' : '') : '') + ' >' + attribute_option.Title + '' + attribute_option.displayprice + '</td>';
								
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

                    $('#edit_item #attributes_wrapper').html(html);
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
                                    mPreviousPrice = $('#edit_item #retail_price').html();
                                    mPreviousPrice = mPreviousPrice.replace('<?=$currency?>', '');
									mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                                    mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(item[productId]['0'].retail_price);
                                    mPreviousPrice = mPreviousPrice.toFixed(2);
                                    mPreviousPrice = '<?=$currency?>'+mPreviousPrice;
                                    $('#edit_item #retail_price').html(mPreviousPrice);
                                }
                                if (index % 3 == 0) {
                                    html = html + '<tr>';
                                }
                                var ind_val = index + 1;
                                html = html + '<td style="display: block;" class="attribute"><input class="clsAssoc" id="'+item[productId]['0'].prd_id+'" price="'+item[productId]['0'].retail_price+'" type="checkbox" ' + selected + ' name="associations[]" value="' + ind_val + '" >' + item[productId]['0'].item_title + '&nbsp;<span class="red"><?= $currency ?>' + item[productId]['0'].retail_price + '</span></td>'
                                index = ind_val;
                                if (index % 3 == 0) {
                                    html = html + '</tr>';
                                }

                            });
                            html = html + '</tbody></table>'
                        }
                    }
                    $('#edit_item #association_wrapper').html(html);
                    $('#edit_item #showPopupLink').trigger('click');
                    $('#facebox').css("position","absolute");
                    $('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) + "px");
                    $('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
                    $('#edit_item').centerPopup()
                },
                error: function(data)
                {
                    alert('Error occurred.');
                }
            });
        } else {
            $('#edit_item #quantity').val(1);
            $('#edit_item #item_for').val('');
            $('#edit_item #requestnote').val('');
            $('#edit_item #requestnote').text('');
            
            $('#showPopupLink').trigger('click');
            $('#facebox').css("position","absolute");
            $('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) + "px");
            $('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
        }
        window.location.hash = '#edit_item', EasyWay.Notification.open(),window.location.hash = ''
        
    }


        $(document).ready(function ()
        {
            $(document).on( 'click',".footer__stepper-next " ,function(e) {
            if(EasyWay.Footer.isLastStep())
                    if(EasyWay.Menu.activeForm.text()){
                        price=EasyWay.Menu.activeForm.parent().parent().parent().find('#retail_price').text()
                        if(price)
                            $('.footer .footer__stepper .bx-custom-pager').html(price).css('padding','5px')
                        
                    }
        })
        
            $.fn.centerPopup = function ()
        {
            this.height('auto')
            if (this.height()>500)
            {
                this.height('20em')
                this.css("position","absolute");
                
            }
            else
            {
                this.css("position","fixed");
                
            }

            
           
        }
        $(document).on( 'keydown',"#quantity, .qnty " ,function(e) {
          
           if ($.inArray(e.keyCode, [173, 46, 8, 9, 27, 13, 109, 189]) !== -1 ||
                 // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                         // let it happen, don't do anything
                         return true;
            }
            // Ensure that it is a number and stop the keypress
           if ((e.keyCode != 173) && (e.keyCode != 109) && (e.keyCode != 189))
            {
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105))
                {
                    if(e.keyCode==190)
                        return false
                        
                return false
                }
            }
}); 
       $(document).on( 'blur',"#quantity" ,function(e) {
           if($(this).val()<=0){
               $(this).val(1)
           }
           if ($.inArray(e.keyCode, [173, 46, 8, 9, 27, 13, 110, 190, 109, 189]) !== -1 ||
                 // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                         // let it happen, don't do anything
                         return;
            }
            // Ensure that it is a number and stop the keypress
           if ((e.keyCode != 173) && (e.keyCode != 109) && (e.keyCode != 189))
            {
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105))
                {
                        e.preventDefault();
                }
            }
});
            EasyWay.Menu.bindEvents();

            $(document).on('change', '.clsAssoc', function ()
            {
                var mPrice = $(this).attr("price");
                var mID = $(this).attr("id");
                var mChecked = 0;

                if ($(this).is(':checked'))
                {
                    mChecked = 1;
                }

                if ($.trim(mPrice) != "")
                {
                    if (isNumeric(mPrice))
                    {
                        mPreviousPrice = $(currentClass + '  #retail_price').html();
                        mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                        mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                        mTxtChecked = $('input[id=txtAssoc]:last').val();

                        if (mChecked == 1)
                        {
                            mTxtChecked = mTxtChecked + mID + ",";
                            mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
                            $('input[id=txtAssoc]:last').val(mTxtChecked);
                        }
                        else if (mChecked == 0)
                        {
                            mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPrice);
                            mTxtChecked = mTxtChecked.replace(mID + ",", '');
                            $('input[id=txtAssoc]:last').val(mTxtChecked);
                        }
                    }
                    mPreviousPrice = mPreviousPrice.toFixed(2);
                    $(  currentClass +' #retail_price').html("<?= $currency ?>" + mPreviousPrice);
                }
            });


            $(document).on('click', '.inputAttr', function ()
            {
                var mPrice = $(this).attr("price");
                var mType = $(this).attr("type");
                var mID = $(this).attr("id");
                var mAttributeID = $(this).attr("attributeid");
                var mLimit = $(this).attr("limit");
                var mLimitPrice = $(this).attr("limitprice");

                var mChecked = 0;
                if (mType == "checkbox")
                {
                    if ($(this).is(':checked'))
                    {
                        mChecked = 1;
                    }
                }

                if ($.trim(mPrice) != "")
                {
                    if (isNumeric(mPrice))
                    {
                        mPreviousPrice = $(currentClass+'  #retail_price').html();
                        mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                        mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                        mTxtChecked = $('input[id=txtChecked' + mAttributeID + ']:last').val();
                        if (mChecked == 1)
                        {
                            mTxtChecked = mTxtChecked + mID + ",";
                            var mTmp = mTxtChecked.split(",");
                            var mLength = mTmp.length;
                            if ($.trim(mLimit) != "")
                            {
                                if (mLength - 1 > mLimit)
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
                            $('input[id=txtChecked' + mAttributeID + ']:last').val(mTxtChecked);
                        }
                        else if (mChecked == 0)
                        {
                            var mTmp = mTxtChecked.split(",");
                            var mLength = mTmp.length;
                            if ($.trim(mLimit) != "")
                            {
                                if (mLength - 1 <= mLimit)
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
                            mTxtChecked = mTxtChecked.replace(mID + ",", '');
                            $('input[id=txtChecked' + mAttributeID + ']:last').val(mTxtChecked);
                        }
                        mPreviousPrice = mPreviousPrice.toFixed(2);
                        mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                        $(currentClass+'  #retail_price').html(mPreviousPrice);
                    }
                    else
                    {
                        mPreviousPrice = $(currentClass+'  #retail_price').html();
                        mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                        mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                        mTxtChecked = $('input[id=txtChecked' + mAttributeID + ']:last').val();
                        if (mChecked == 1)
                        {
                            mTxtChecked = mTxtChecked + mID + ",";
                            var mTmp = mTxtChecked.split(",");
                            var mLength = mTmp.length;
                            if ($.trim(mLimit) != "")
                            {
                                if (mLength - 1 > mLimit)
                                {
                                    mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mLimitPrice);
                                }
                            }
                            $('input[id=txtChecked' + mAttributeID + ']:last').val(mTxtChecked);
                        }
                        else if (mChecked == 0)
                        {
                            var mTmp = mTxtChecked.split(",");
                            var mLength = mTmp.length;
                            if ($.trim(mLimit) != "")
                            {
                                if (mLength - 1 > mLimit)
                                {
                                    mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mLimitPrice);
                                }
                            }
                            mTxtChecked = mTxtChecked.replace(mID + ",", '');
                            $('input[id=txtChecked' + mAttributeID + ']:last').val(mTxtChecked);
                        }
                        mPreviousPrice = mPreviousPrice.toFixed(2);
                        mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                        $( currentClass +' #retail_price').html(mPreviousPrice);
                    }
                }
                else
                {
                    mPreviousPrice = $(currentClass + ' #retail_price').html();
                    mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                    mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                    mTxtChecked = $('input[id=txtChecked' + mAttributeID + ']:last').val();
                    if (mChecked == 1)
                    {
                        mTxtChecked = mTxtChecked + mID + ",";
                        var mTmp = mTxtChecked.split(",");
                        var mLength = mTmp.length;
                        if ($.trim(mLimit) != "")
                        {
                            if (mLength - 1 > mLimit)
                            {
                                mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mLimitPrice);
                            }
                        }
                        $('input[id=txtChecked' + mAttributeID + ']:last').val(mTxtChecked);
                    }
                    else if (mChecked == 0)
                    {
                        var mTmp = mTxtChecked.split(",");
                        var mLength = mTmp.length;
                        if ($.trim(mLimit) != "")
                        {
                            if (mLength - 1 > mLimit)
                            {
                                mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mLimitPrice);
                            }
                        }
                        mTxtChecked = mTxtChecked.replace(mID + ",", '');
                        $('input[id=txtChecked' + mAttributeID + ']:last').val(mTxtChecked);
                    }
                    mPreviousPrice = mPreviousPrice.toFixed(2);
                    mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                    $(currentClass+ ' #retail_price').html(mPreviousPrice);
                }
            });


            $(document).on('click', '.inputAttrRB', function ()
            {
                var mPrice = $(this).attr("price");
                var mID = $(this).attr("id");
                var mName = $(this).attr("name");
                var mAttributeID = $(this).attr("attributeid");
                var mTxtChecked = $('input[id=txtChecked' + mAttributeID + ']:last').val();

                if ($.trim(mTxtChecked) != "")
                {
                    $('input[name="' + mName + '"]').each(function (index)
                    {
                        var mLoopPrice = $(this).attr("price");
                        var mLoopID = $(this).attr("id");

                        if ($.trim(mTxtChecked).indexOf(mLoopID + ",") >= 0)
                        {
                            mPreviousPrice = $(currentClass+ '  #retail_price').html();
                            mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                            mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                            mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mLoopPrice);

                            if ($.trim(mPrice) != "")
                            {
                                mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
                            }

                            mPreviousPrice = mPreviousPrice.toFixed(2);
                            mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                            $('input[id=txtChecked' + mAttributeID + ']:last').val(mID + ",")
                            $(currentClass + '  #retail_price').html(mPreviousPrice);
                            return false;
                        }
                    });
                }
                else
                {
                    mPreviousPrice = $(currentClass + ' #retail_price').html();
                    mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                    mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');

                    if ($.trim(mPrice) != "")
                    {
                        mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
                    }

                    mPreviousPrice = mPreviousPrice.toFixed(2);
                    mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                    $('input[id=txtChecked' + mAttributeID + ']:last').val(mID + ",")
                    $(currentClass + ' #retail_price').html(mPreviousPrice);
                }
            });

            $(document).on('click', '.inputAttrDD', function ()
            {
                var mTextboxId = $(this).attr("textboxid");
                var mID = $(this).attr("id");
                var mPrice = $('option:selected', this).attr('price');
                var mOptionID = $('option:selected', this).attr('optionid');

                if ($(this).val() != "")
                {
                    if ($.trim($("#" + mTextboxId).val()) != "")
                    {
                        $("#" + mID + " > option").each(function ()
                        {
                            var mOptionIDLoop = $(this).attr("value");
                            var mPriceLoop = $(this).attr("price");
                            if ($.trim(mOptionIDLoop) != "")
                            {
                                if ($.trim($("#" + mTextboxId).val()).indexOf(mOptionIDLoop + ",") >= 0)
                                {
                                    mPreviousPrice = $(currentClass + ' #retail_price').html();
                                    mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                                    mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                                    mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPriceLoop);

                                    if ($.trim(mPrice) != "")
                                    {
                                        mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
                                    }

                                    mPreviousPrice = mPreviousPrice.toFixed(2);
                                    mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                    $("#" + mTextboxId).val($("#" + mTextboxId).val().replace(mOptionIDLoop + ",", ""));
                                    $("#" + mTextboxId).val(mOptionID + ",")
                                    $(currentClass + ' #retail_price').html(mPreviousPrice);
                                }
                            }
                        });
                    }
                    else
                    {
                        if ($.trim(mPrice) != "")
                        {
                            $("#" + mTextboxId).val($('option:selected', this).attr('optionid') + ",")
                            mPreviousPrice = $(currentClass +' #retail_price').html();
                            mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                            mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                            mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
                            mPreviousPrice = mPreviousPrice.toFixed(2);
                            mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                            $(currentClass + ' #retail_price').html(mPreviousPrice);
                        }
                    }
                }
                else
                {
                    if ($.trim($("#" + mTextboxId).val()) != "")
                    {
                        $("#" + mID + " > option").each(function ()
                        {
                            var mOptionIDLoop = $(this).attr("value");
                            var mPriceLoop = $(this).attr("price");

                            if ($.trim(mOptionIDLoop) != "")
                            {
                                if ($.trim($("#" + mTextboxId).val()).indexOf($.trim(mOptionIDLoop) + ",") >= 0)
                                {
                                    mPreviousPrice = $(currentClass + ' #retail_price').html();
                                    mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                                    mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                                    if (isNumeric(mPriceLoop))
                                    {
                                        mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPriceLoop);
                                        mPreviousPrice = mPreviousPrice.toFixed(2);
                                    }
                                    mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                    $("#" + mTextboxId).val($("#" + mTextboxId).val().replace(mOptionIDLoop + ",", ""));
                                    $(currentClass + ' #retail_price').html(mPreviousPrice);
                                }
                            }
                        });
                    }
                }
            });
        });


        $('  #edit_item form').submit( function(event){
        	event.preventDefault();
            console.log(attributeRequired);
            mQuantity = $("#edit_item #quantity").val();
            mItemFor = $("#edit_item #item_for").val();
            mRequestNote = $("#edit_item #requestnote").val();
            mTotalAttributes = $("#edit_item #totalattributes").val();
			
            var product_id = $('#edit_item #product_id_field').val();
            var product_title = $('#edit_item #item_title').val();
            var product_quantity = $('#edit_item #quantity').val();
            var product_sale_price = $('#edit_item #product_sale_price').val();
            var price = product_sale_price * product_quantity;
            var index = $('#edit_item #cartItemIndex').val()
            var hasAttributes = $('#edit_item #HasAttributes').val();
            var hasAssociates = $('#edit_item #HasAssociates').val();
            
			/*------------------------------Naveed Start---------------------------------------------------*/
			var isValid = true;
			
			$.each(attributeRequired, function(index, value) {
			  
			  var isAllRequiredSelected = false;
			  var attributeElements = $('[attributeid='+value+']');
			  
			  var filteredAttributeElements = attributeElements.slice(attributeElements.length/2);
			  $.each(filteredAttributeElements, function(index, element) {    
				
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
					$($('[id='+attributeborderId+']')[0]).css({"display":"block","border":"2px solid #9C0F17","border-collapse":"separate","border-spacing":"2px"});
			
				}
				else
				{
					$($('[id='+attributeId+']')[1]).hide();
					$($('[id='+attributeborderId+']')[0]).css({"border":"none"});
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
			/*------------------------------Naveed End-----------------------------------------------------*/        
					var mUrl = '';
					var mRandom = Math.floor((Math.random() * 1000000) + 1);
					mUrl = "<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=favindex&addtocart=1&ProductID=" + product_id + "&rndm=" + mRandom + "&ajax=1<?=$grouporderingUrl?>";
					
					$.ajax
					({
						url: mUrl,
						type: 'POST',
						data: $('  #edit_item form').serialize(),
						success: function(data)
						{
							
							var cart_html = '<div class="flip"><div id="edit_sign"><a href="#" onclick="event.preventDefault();showPopup('+product_id + ',' + hasAssociates + ',' + hasAttributes +',-1);" style="color:#8c1515;"><img border="0" src="../images/gray_edit.gif" height="14px"></a></div>'
									+ '<div id="counting">' + product_title + '</div>'
							'<div id="minus_sign" class="remove_cart"><a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=<?=$item.$grouporderingUrl?>&amp;index=' + index + '"><img src="../images/minus_sign.gif" width="14" height="14" border="0"></a></div>'
							'<div id="dollor"><?= $currency ?>' + price + '</div>'
							'<div style="clear:left"></div>'
							'</div>';
			
								$(cart_html).insertAfter('#contents div.flip:last-child');
								$(".cart").load("<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=<?=$item.$grouporderingUrl?>&ajax=1", function(){
                                                                    $('.header__cart').html($('.cart-item').length);
                                                                });
								EasyWay.Notification.close();
			
							},
							error: function(data)
							{
								alert('Error occurred.');
							}
						});
			/*------------------------------Naveed Start---------------------------------------------------*/        
    }
        
       })
        function  add_to_cart(elementt) {

            ///event.preventDefault();
            console.log(elementt)
            console.log(attributeRequired);
            mQuantity = $("#quantity").val();
            mItemFor = $("#item_for").val();
            mRequestNote = $("#requestnote").val();
            mTotalAttributes = $("#totalattributes").val();

            var product_id = $('.active_form #product_id_field').val();
            var product_title = $('.active_form  #item_title').text();
            var product_quantity = $('.active_form #quantity').val();
            var product_sale_price = $(' .active_form #retail_price').text().replace('$', '')
            var price = product_sale_price * product_quantity;
            var index = $('#contents div.flip').length - 1;
            var hasAttributes = $('.active_form #hasAttributes').val();
            var hasAssociates = $('.active_form #hasAssociates').val();

            /*------------------------------Naveed Start---------------------------------------------------*/
            var isValid = true;


            if (!(isValid))
            {
                $($('[id=updateMessage]')[1]).show();
                $($('[id=updateMessage1]')[1]).show();
            }
            else
            {
                $($('[id=updateMessage]')[1]).hide();
                $($('[id=updateMessage1]')[1]).hide();
                /*------------------------------Naveed End-----------------------------------------------------*/
                var mUrl = '';
                var mRandom = Math.floor((Math.random() * 1000000) + 1);
                
				<?php
				if (isset($_GET["grp_userid"]) && isset($_GET["grpid"]) && isset($_GET["uid"]))
				{
				?>
				mUrl = "<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=favindex&addtocart=1&ProductID=" + product_id + "&rndm=" + mRandom + "&ajax=1&grp_userid=<?=$_GET["grp_userid"]?>&grpid=<?=$_GET["grpid"]?>&uid=<?=$_GET["uid"]?>";
				<?php
				}
				else
				{
				?>
				mUrl = "<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=favindex&addtocart=1&ProductID=" + product_id + "&rndm=" + mRandom + "&ajax=1";
				<?php
				}
				?>
				
                var dd = $('.active_form form').serialize();
                $.ajax
                        ({
                            url: mUrl,
                            type: 'POST',
                            data: $('.active_form form').serialize(),
                            success: function (data)
                            {

                                    $(".cart").load("<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=<?=$item.$grouporderingUrl?>&ajax=1" , function(){
                                        $('.header__cart').html($('.cart-item').length);
                                    });
                                $('.header__cart').html($('.cart-item').length);
                                $('article').find('hgroup #retail_price').each(function () {
                                    $(this).html(currency + $(this).parent().parent().parent().find('form').attr('price'))
                                    
                                })
                                
                            },
                            error: function (data)
                            {
                                alert('Error occurred.');
                            }
                        });
                        
                        $('.footer__stepper span').html($('.cart__total2').html())
                /*------------------------------Naveed Start---------------------------------------------------*/
            }
            /*------------------------------Naveed End-----------------------------------------------------*/

        }

    </script>
    <style>
       
        .item_title_type{
           display: inline-block;
        }
        .menu-item__form.open {
            max-height: 1000px;
        }

    </style>
