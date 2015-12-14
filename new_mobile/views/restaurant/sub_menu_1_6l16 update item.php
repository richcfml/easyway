<body class="index">
    <?php require($mobile_root_path . "includes/header.php"); ?>
    <main class=main>
        <div class=main__container>

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
                    ?>
                    <article data-onclick="showPopup(173926, 0, 0, -1, this)" class="menu-item editPrd ">
                                            <header class="menu-item__header" flag="1">
                                                <hgroup class="menu-item__info">
                                                    <h3 id="item_title">Lunch</h3>
                                                    <h4 id="retail_price">$44.00</h4>
                                                    <img src="../c_panel/img/vegan_icon22.png" style="margin-left: 10px;" class="item_title_type"><img src="../c_panel/img/nutfree_icon22.png" style="margin-left: 10px;" class="item_title_type"><img src="../c_panel/img/bh_item1.png?v4" style="margin-left: 10px;" class="item_title_type">                                                </hgroup>
                                            </header>
                                            <form price="44.00" prd_id="173926" hasassociates="0" hasattributes="0" class="menu-item__form">
                                                <!--attributes loop-->
                                                <input type="hidden" id="product_id_field" name="product_id_field" value="173926">
                                                <input type="hidden" id="product_sale_price" name="product_sale_price">
                                                <input type="hidden" id="hasAssociates" name="has_associates" value="0">
                                                <input type="hidden" id="hasAttributes" name="has_attributes" value="0">
                                                <input type="hidden" name="totalattributes" id="totalAttributes">
                                                <input type="hidden" id="cartItemIndex" name="cartItemIndex" value="-1"><fieldset class="js-slide" data-name="Quantity" style="float: left; list-style: none; position: relative; width: 242px; margin-right: 20px;">Sweet Slice® HamSweet Slice® Ham&nbsp;42% Lower Sodium Deluxe Ham&nbsp;Rosemary &amp; Sundried Tomato Ham&nbsp;Sweet Slice® Ham&nbsp;Cracked Pepper Mill® Turkey&nbsp;Ovengold® Turkey&nbsp;Bold Salsalito® Turkey&nbsp;Mesquite Wood Smoked® Turkey&nbsp;All Natural* Roasted Turkey&nbsp;Bold Salsalito® Turkey&nbsp;Mesquite Wood Smoked® Turkey&nbsp;All Natural* Roasted Turkey&nbsp;All Natural* Smoked Turkey&nbsp;All Natural* Uncured Ham&nbsp;All Natural* Smoked Uncured Ham&nbsp;All Natural* Oven Roasted Beef&nbsp;33% Lower Sodium Bologna&nbsp;46% Lower Sodium Turkey&nbsp;46% Lower Sodium Turkey&nbsp;<div class="menu-item__quantity"> <label for="quantity">Quantity: </label> <input type="button" value="-" class="menu-item__quantity-update js-minus"> <input id="quantity" name="quantity" value="1" min="1" class="menu-item__quantity-input"> <input type="button" value="+" class="menu-item__quantity-update js-plus"> </div></fieldset><fieldset class="js-slide" data-name="Special instructions" style="float: left; list-style: none; position: relative; width: 242px; margin-right: 20px;"> <legend>Special Instructions</legend> <textarea name="requestnote" id="requestnote" rows="5" cols="" class="menu-item__special-instructions"></textarea> </fieldset>
                                                
                    
                                            </form>
                     </article>
                        <?php
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
                            <h2 class=menu__header>  <?php echo stripslashes($category->cat_name); ?></h2>
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
                                        ?>

                                        <article data-onclick="showPopup(<?php echo $product->prd_id; ?>, <?php echo $product->HasAssociates; ?>, <?php echo $product->HasAttributes; ?>, -1, this)" class="menu-item <?php echo $product->prd_id; ?>">
                                            <header class='menu-item__header' flag="0">
                                                <hgroup class=menu-item__info>
                                                    <h3 id="item_title"><?php echo stripcslashes($product->item_title); ?></h3>
                                                    <h4 id="retail_price"> $<?php echo $product->retail_price; ?></h4>
                                                    <?php
                                                    if ($product->item_type != '') {

                                                        if (strpos('x'.$product->item_type, '0')  ) {
                                                            echo('<img src="../c_panel/img/new_icon22.png" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '1')  ) {
                                                            echo('<img src="../c_panel/img/vegan_icon22.png" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '2') ) {
                                                            echo('<img src="../c_panel/img/POPULAR_icon22.png" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '3') ) {
                                                            echo('<img src="../c_panel/img/nutfree_icon22.png" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '4') ) {
                                                            echo('<img src="../c_panel/img/glutenfree_icon22.png" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '5') ) {
                                                            echo('<img src="../c_panel/img/LOWFAT_icon22.png" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '6') ) {
                                                            echo('<img src="../c_panel/img/vegetarian_icon22.png" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '7') ) {
                                                            echo('<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '8') ) {
                                                            echo('<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/><img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                        if (strpos('x'.$product->item_type, '9') ) {
                                                            echo('<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/><img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/><img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                        if (strpos('x'.$product->item_type, 'B') ) {
                                                            echo('<img src="../c_panel/img/bh_item1.png?v4" style="margin-left: 10px;" class="item_title_type"/>');
                                                        }
                                                    }
                                                    ?>
                                                </hgroup>
                                            </header>
                                            <form  price="<?php echo $product->retail_price; ?>" prd_id="<?php echo $product->prd_id; ?>" HasAssociates="<?php echo $product->HasAssociates; ?>" HasAttributes="<?php echo $product->HasAttributes; ?>"     class=menu-item__form>
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
    <script>
        /*------------------------------Naveed Start---------------------------------------------------*/
        var attributeRequired;
        /*------------------------------Naveed End-----------------------------------------------------*/
        var siteUrl = '<?php echo $SiteUrl ?>';
        var restauranrUrl= '<?php echo $objRestaurant->url ?>';
        var currency = '<?php echo $currency ?>';
        function isNumeric(n)
        {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }
        menu_details = <?php echo json_encode($menus_details) ?>;

        function setProductDetailsToPopup(productDetails) {

            $('#item_title').html(productDetails.item_title);
            $('#item_des').html(productDetails.item_des);

            if (productDetails.item_image) {
                $('#popupProductImage').show();
                $('#popupProductImage').append('<a href="<?php echo $SiteUrl . "images/item_images/" ?>' + productDetails.item_image + '" rel="prettyPhoto" display="inline" id="item_image_link"><img class="images" src="<?php echo $SiteUrl . "images/item_images/" ?>' + productDetails.item_image + '" width="70" height="70" id="item_image" border="0"></a>');
                $('#item_detail_wrapper').css('float', 'right');
            } else {
                $('#popupProductImage').hide();
                $('#item_image_link').remove();
                $('#item_detail_wrapper').css('float', 'left');
            }
            // $('.item_title_type').remove();
            if (productDetails.item_type) {
                var product_item_type = productDetails.item_type;
                if (product_item_type.indexOf('0') !== -1) {
                    $('<img src="../c_panel/img/new_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
                if (product_item_type.indexOf('1') !== -1) {
                    $('<img src="../c_panel/img/vegan_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
                if (product_item_type.indexOf('2') !== -1) {
                    $('<img src="../c_panel/img/POPULAR_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
                if (product_item_type.indexOf('3') !== -1) {
                    $('<img src="../c_panel/img/nutfree_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
                if (product_item_type.indexOf('4') !== -1) {
                    $('<img src="../c_panel/img/glutenfree_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
                if (product_item_type.indexOf('5') !== -1) {
                    $('<img src="../c_panel/img/LOWFAT_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
                if (product_item_type.indexOf('6') !== -1) {
                    $('<img src="../c_panel/img/vegetarian_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
                if (product_item_type.indexOf('7') !== -1) {
                    $('<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
                if (product_item_type.indexOf('8') !== -1) {
                    $('<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/><img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
                if (product_item_type.indexOf('9') !== -1) {
                    $('<img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/><img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/><img src="../c_panel/img/spicy_icon22.png" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
                if (product_item_type.indexOf('B') !== -1) {
                    $('<img src="../c_panel/img/bh_item1.png?v4" style="margin-left: 10px;" class="item_title_type"/>').insertAfter($('#item_title'));
                }
            }
            $(' .active_form #retail_price').html('<?= $currency ?>' + productDetails.retail_price);
            $('#product_sale_price').val(productDetails.sale_price);
        }

        function showPopup(productId, hasAssociates, hasAttribute, cartItemIndex, elementt) {
            // alert('sds')
//            console.log(elementt)
//            $(elementt).parent().find('#retail_price').html('$'+elementt.attr('price'))
//            if (!$(elementt).hasClass('active_form')) {
//                $(elementt).find('#quantity').val('1')
//                $(elementt).find('input:checkbox').removeAttr('checked');
//                $(elementt).find('input:radio').removeAttr('checked');
//                $(elementt).find('select').val('')
//                var price = $(elementt).attr('price')
//                $(elementt).find('#retail_price').text('$' + price)
//            }
//
//            $('#facebox').css("position", "absolute");
//            $('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) + "px");
//            $('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
//            $('#attributes_wrapper').html('');
//            $('#association_wrapper').html('');
//            $('#item_image_link').remove();
//            if (!elementt) {
//                elementt = $('.' + productId)
//                $(elementt).parent().addClass('open')
//            }
            elementt=$('.editPrd ')[0]
            $('article').removeClass("active_form")
//            $(elementt).addClass('active_form')
//            if ($(elementt).hasClass('done')) {
//                if ($(elementt).hasClass('ddone')) {
//                    return
//                }
//                $(elementt).addClass('ddone')
//
//                $(elementt).find('header').click();
//                return;
//            }
//            $(elementt).addClass('done')
            var productDetails;

            if (cartItemIndex < 0) {
                //Means user just want to add items to cart
                productDetails = menu_details.filter(function (x) {
                    return x.prd_id == productId;
                });
                productDetails = productDetails['0'];
                //Call setProductDetailsToPopup to set details of product to popup
               // setProductDetailsToPopup(productDetails);
            }
            
            $('.active_form  #product_id_field').val(productId);
            $('.active_form #totalattributes').val(<?php echo $attributeCount; ?>);
            $('.active_form #hasAssociates').val(hasAssociates);
            $('.active_form #hasAttributes').val(hasAttribute);
            $('.active_form #cartItemIndex').val(cartItemIndex);

            attributeRequired = new Array();
            var attribute_index = 0;
            var attribute_name = "attr";
            var attribute_parent_name = "attrname";
            var html = '';

            // html = html + '<input type="hidden" id="hdnControls" />';

            if (hasAssociates == 1 || hasAttribute == 1 || cartItemIndex >= 0) {

                var mUrl = '';
                var mRandom = Math.floor((Math.random() * 1000000) + 1);
                if (cartItemIndex >= 0)
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
                                productId: productId,
                                hasAssociates: hasAssociates,
                                hasAttribute: hasAttribute,
                                cartItemIndex: cartItemIndex
                            },
                            success: function (data)
                            {
                                data = JSON.parse(data);
                                attributes_array = data.attr;
                                association_array = data.assoc;
                                var totalattributes = data.totalAttribute;
                                console.log('attrites total');
                                console.log(data.totalAttribute)
                                $('.active_form #totalAttributes').val(totalattributes);

                                if (data.hasOwnProperty('productDetails'))
                                {
                                    //Means Edit Item function called from cart
                                    // So, call setProductDetailsToPopup function to set extra details of product to popup
                                    productDetails = data.productDetails;
                                    setProductDetailsToPopup(productDetails);
                                }

                                if (data.hasOwnProperty('quantity')) {
                                    var quantity = data.quantity;
                                    $('#quantity').val(quantity);
                                } else
                                {
                                    $('#quantity').val(1);
                                }
                                if (data.hasOwnProperty('item_for')) {
                                    var item_for = data.item_for.replace(/&#39;/g, "'");
                                    $('#item_for').val(item_for);
                                } else
                                {
                                    $('#item_for').val('');
                                }
                                if (data.hasOwnProperty('requestnote')) {
                                    var requestnote = data.requestnote.replace(/&#39;/g, "'");
                                    $('#requestnote').val(requestnote);
                                    $('#requestnote').text(requestnote);
                                } else
                                {
                                    $('#requestnote').val('');
                                    $('#requestnote').text('');
                                }
                                //console.log(attributes_array)
                                var requiredIndex = 0;
                                $.each(attributes_array, function (i, attribute)
                                {
    //                                console.log(attribute.attr_name + ':'+ attribute.Required)
                                    mAttReq = '';
                                    mAttrReqHtml = '';
                                    mStrRe = '<div>';

                                    var mAttrName = attribute.attr_name;
                                    var mLimit = '';
                                    var mLimitPrice = attribute.extra_charge;
                                    var mDD = '';

                                    if ($.trim(mAttrName) != "")
                                    {
                                        var mTmp = mAttrName.split("~");
                                        var mLimit = mTmp[2];
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
                                        mAttrReqHtml = '<span id="attrRequired-' + attribute.id + '" class="red" style="display:none;">*</span>';
                                    }

                                    if (($.trim(mLimit) != "") && ($.trim(mLimitPrice) != ""))
                                    {
                                        if ((mLimit > 0) && (mLimitPrice > 0))
                                        {
                                            if (mStrRe == '<td colspan="3" style="width: 100%; font-size: 11px !important; color: red;">&nbsp;<i>')
                                            {
                                                mStrRe = mStrRe + '<?= $currency ?>' + mLimitPrice + ' will be added for each additional selection';
                                            }
                                            else
                                            {
                                                mStrRe = mStrRe + '&nbsp;&nbsp;-&nbsp;&nbsp;(<?= $currency ?>' + mLimitPrice + ' will be added for each additional selection)';
                                            }
                                        }
                                    }

                                    mStrRe = mStrRe + '</div>';
                                    if (attribute.display_Name != null)
                                    {
                                        if (($.trim(attribute.display_Name) != "") && ($.trim(attribute.display_Name) != "Type your message here"))
                                        {
                                            html = html + '<fieldset data-type="' + attribute.Type + '" data-req="' + attribute.Required + '"  class=js-slide data-name="' + attribute.option_name + '"  id=attrRequiredBorder-' + attribute.id + ' style="float: left; list-style: none; position: relative; width: 242px; margin-right: 20px;"><tr ><div><strong class="Text_14px">' + attribute.display_Name + '</strong></div>' + mAttrReqHtml + '<input type="hidden" name="' + attribute_parent_name + '" value="' + attribute.option_name + '" />' + mAttReq + '' + mStrRe + '';
                                        }
                                        else
                                        {
                                            html = html + '<table id=attrRequiredBorder-' + attribute.id + ' style="">&nbsp;<tr ><strong class="Text_14px">' + attribute.option_name + '</strong>' + mAttrReqHtml + '<input type="hidden" name="' + attribute_parent_name + '" value="' + attribute.option_name + '" />' + mAttReq + '' + mStrRe + '';
                                        }
                                    }
                                    else
                                    {
                                        html = html + '<table id=attrRequiredBorder-' + attribute.id + ' style="">&nbsp;<tr ><strong class="Text_14px">' + attribute.option_name + '</strong>' + mAttrReqHtml + '<input type="hidden" name="' + attribute_parent_name + '" value="' + attribute.option_name + '" />' + mAttReq + '' + mStrRe + '';
                                    }
                                    if (attribute.Type == 1)
                                    {
                                        html = html + '  <select id="ddlAttr" attributeid="' + attribute.id + '" textboxid="txtDD' + attribute.id + '" type="select" name="' + attribute_name + '" class="inputAttrDD">';
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
                                            html = html + '<option optionid="' + attribute_option.id + '" textboxid="txtDD' + attribute.id + '" price="' + attribute_option.Price + '"  value="' + attribute_option.id + '"';
                                            if (attrid != '' || attrid == attribute_option.id) {
                                                html = html + 'selected';
                                                mDD = attribute_option.id + ",";
                                                if ($.trim(attribute_option.Price) != "")
                                                {
                                                    if (!isNaN(attribute_option.Price))
                                                    {
                                                        mPreviousPrice = $(' .active_form #retail_price').html();
                                                        mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                                                        mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                                                        mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(attribute_option.Price);
                                                        mPreviousPrice = mPreviousPrice.toFixed(2);
                                                        mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                                        $(' .active_form #retail_price').html(mPreviousPrice);
                                                    }
                                                }
                                            }
                                            html = html + '>' + attribute_option.Title + '' + attribute_option.displayprice + '</option>';
                                        } else if (attribute.Type == 2 || attribute.Type == 3) {
                                            if (col_index % 3 == 0) {
                                                html = html + '';
                                            }

                                            if (attribute_option.Default == 1)
                                            {
                                                if (attribute.Type == 1 || attribute.Type == 3)
                                                {
                                                    if (mAddFlag == 0)
                                                    {
                                                        mAddFlag = 1;
                                                        mCheckCount = mCheckCount + 1;
                                                        if ($.trim(mLimit) != "")
                                                        {
                                                            if (mCheckCount > mLimit)
                                                            {
                                                                mPreviousPrice = $(' .active_form #retail_price').html();
                                                                mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
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
                                                                mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                                                $(' .active_form #retail_price').html(mPreviousPrice);
                                                                mTxtCheckedLoad = mTxtCheckedLoad + attribute_option.id + ",";
                                                            }
                                                            else
                                                            {
                                                                mPreviousPrice = $(' .active_form #retail_price').html();
                                                                mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
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
                                                                mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                                                $(' .active_form #retail_price').html(mPreviousPrice);
                                                                mTxtCheckedLoad = mTxtCheckedLoad + attribute_option.id + ",";
                                                            }
                                                        }
                                                        else
                                                        {
                                                            mPreviousPrice = $(' .active_form #retail_price').html();
                                                            mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
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
                                                            mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                                            $(' .active_form #retail_price').html(mPreviousPrice);
                                                            mTxtCheckedLoad = mTxtCheckedLoad + attribute_option.id + ",";
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    mCheckCount = mCheckCount + 1;
                                                    if ($.trim(mLimit) != "")
                                                    {
                                                        if (mCheckCount > mLimit)
                                                        {
                                                            mPreviousPrice = $(' .active_form #retail_price').html();
                                                            mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
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
                                                            mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                                            $(' .active_form #retail_price').html(mPreviousPrice);
                                                            mTxtCheckedLoad = mTxtCheckedLoad + attribute_option.id + ",";
                                                        }
                                                        else
                                                        {
                                                            mPreviousPrice = $(' .active_form #retail_price').html();
                                                            mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
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
                                                            mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                                            $(' .active_form #retail_price').html(mPreviousPrice);
                                                            mTxtCheckedLoad = mTxtCheckedLoad + attribute_option.id + ",";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        mPreviousPrice = $(' .active_form #retail_price').html();
                                                        mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
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
                                                        mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                                        $(' .active_form #retail_price').html(mPreviousPrice);
                                                        mTxtCheckedLoad = mTxtCheckedLoad + attribute_option.id + ",";
                                                    }
                                                }
                                            }

                                            html = html + '<td class="attribute"><label style="display: block; "><input limit="' + mLimit + '" limitprice="' + mLimitPrice + '" price="' + attribute_option.Price + '" id="' + attribute_option.id + '" attributeid="' + attribute.id + '" name="' + attribute_name + '" class="' + (attribute.Type == 2 ? 'inputAttr' : 'inputAttrRB') + '" type="' + (attribute.Type == 2 ? 'checkbox' : 'radio') + '" value="' + attribute_option.id + '" ' + (mAddFlag1 == 0 ? (attribute_option.Default == 1 ? 'checked' : '') : '') + ' >' + attribute_option.Title + '' + attribute_option.displayprice + '</label>';

                                            if ((mAddFlag1 == 0) && (attribute_option.Default == 1) && (attribute.Type == 3))
                                            {
                                                mAddFlag1 = 1;
                                            }

                                            col_index += 1;
                                            if (col_index % 3 == 0) {
                                                html = html + '';
                                            }
                                        }

                                        attribute.Required = 0;
                                    });

                                    if (attribute.Required == 1)
                                    {
                                        mAttReq = '<input type="hidden" id="txtChecked' + attribute.id + '" value="' + mTxtCheckedLoad + '" /><input type="hidden" id="txtReq' + attribute.id + '" value="1" />';
                                    }
                                    else
                                    {
                                        mAttReq = '<input type="hidden" id="txtChecked' + attribute.id + '" value="' + mTxtCheckedLoad + '" /><input type="hidden" id="txtReq' + attribute.id + '" value="0" />';
                                    }

                                    if (attribute.Type == 1) {
                                        html = html + "</select><input type='hidden' id='txtDD" + attribute.id + "' value='" + mDD + "'/>";
                                    }

                                    html = html + mAttReq + '</fieldset>';
                                });
                                html = html + '&nbsp;';

                                //$('.test div form fieldset').remove()
                                //$(elementt).find('fieldset').remove();
                                $(elementt).find('form').append(html);

    //                            console.log($(elementt).find('.bx-wrapper'))
                                $('#attributes_wrapper').html(html);
                                html = '';


                                //association details
                                if (association_array != '') {
                                    var associationResult = association_array.filter(function (x) {
                                        return x.product_id == productId;
                                    });

                                    var index = 0;
                                    if (associationResult.length > 0) {
                                        html = html + '<fieldset class="js-slide" data-name="Add to your meal"><input type="hidden" id="txtAssoc" /><strong>Add to your meal</strong>';

                                        $.each(associationResult, function (i, item) {
                                            var selected = '';

                                            if (("Selected" in item))
                                            {
                                                selected = 'checked="checked"';
                                                mPreviousPrice = $(' .active_form #retail_price').html();
                                                mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                                                mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                                                mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(item[productId]['0'].retail_price);
                                                mPreviousPrice = mPreviousPrice.toFixed(2);
                                                mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                                $(' .active_form #retail_price').html(mPreviousPrice);
                                            }
                                            if (index % 3 == 0) {
                                                html = html + '';
                                            }
                                            var ind_val = index + 1;
                                            html = html + '<label class="attribute"><input class="clsAssoc" id="' + item[productId]['0'].prd_id + '" price="' + item[productId]['0'].retail_price + '" type="checkbox" ' + selected + ' name="associations[]" value="' + ind_val + '" >' + item[productId]['0'].item_title + '&nbsp;<span class="red"><?= $currency ?>' + item[productId]['0'].retail_price + '</span></label>'
                                            index = ind_val;
                                            if (index % 3 == 0) {
                                                html = html + '';
                                            }

                                        });
                                        html = html + '</fieldset>'
                                    }
                                }
                                html = html + '<fieldset class="js-slide" data-name="Special instructions" style="float: left; list-style: none; position: relative; width: 242px; margin-right: 20px;"> <legend>Special Instructions</legend> <textarea  name="requestnote" id="requestnote" rows="5" cols="" class="menu-item__special-instructions"></textarea> </fieldset>';

                                $('#association_wrapper').html(html);
                                $(elementt).find('form').append(html);
                                
                                $('#showPopupLink').trigger('click');
    //                            console.log(html)

                            },
                            error: function (data)
                            {
                                alert('Error occurred.');
                            }
                        });
            } else {
                html =  '<fieldset class="js-slide" data-name="Special instructions" style="float: left; list-style: none; position: relative; width: 242px; margin-right: 20px;"> <legend>Special Instructions</legend> <textarea  name="requestnote" id="requestnote" rows="5" cols="" class="menu-item__special-instructions"></textarea> </fieldset>';
                $(elementt).find('form').append(html);
                $('#quantity').val(1);
                $('#item_for').val('');
                $('#requestnote').val('');
                $('#requestnote').text('');

                $('#showPopupLink').trigger('click');
                $('#facebox').css("position", "absolute");
                $('#facebox').css("top", Math.max(0, (($(window).height() - $('#facebox').outerHeight()) / 2) + $(window).scrollTop()) + "px");
                $('#facebox').css("left", Math.max(0, (($(window).width() - $('#facebox').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
            }

        }



        $(document).ready(function ()
        {
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
                        mPreviousPrice = $(' .active_form #retail_price').html();
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
                    $(' .active_form #retail_price').html("<?= $currency ?>" + mPreviousPrice);
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
                        mPreviousPrice = $(' .active_form #retail_price').html();
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
                        $(' .active_form #retail_price').html(mPreviousPrice);
                    }
                    else
                    {
                        mPreviousPrice = $(' .active_form #retail_price').html();
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
                        $(' .active_form #retail_price').html(mPreviousPrice);
                    }
                }
                else
                {
                    mPreviousPrice = $(' .active_form #retail_price').html();
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
                    $(' .active_form #retail_price').html(mPreviousPrice);
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
                            mPreviousPrice = $(' .active_form #retail_price').html();
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
                            $(' .active_form #retail_price').html(mPreviousPrice);
                            return false;
                        }
                    });
                }
                else
                {
                    mPreviousPrice = $(' .active_form #retail_price').html();
                    mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                    mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');

                    if ($.trim(mPrice) != "")
                    {
                        mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
                    }

                    mPreviousPrice = mPreviousPrice.toFixed(2);
                    mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                    $('input[id=txtChecked' + mAttributeID + ']:last').val(mID + ",")
                    $(' .active_form #retail_price').html(mPreviousPrice);
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
                                    mPreviousPrice = $(' .active_form #retail_price').html();
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
                                    $(' .active_form #retail_price').html(mPreviousPrice);
                                }
                            }
                        });
                    }
                    else
                    {
                        if ($.trim(mPrice) != "")
                        {
                            $("#" + mTextboxId).val($('option:selected', this).attr('optionid') + ",")
                            mPreviousPrice = $(' .active_form #retail_price').html();
                            mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                            mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                            mPreviousPrice = parseFloat(mPreviousPrice) + parseFloat(mPrice);
                            mPreviousPrice = mPreviousPrice.toFixed(2);
                            mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                            $(' .active_form #retail_price').html(mPreviousPrice);
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
                                    mPreviousPrice = $(' .active_form #retail_price').html();
                                    mPreviousPrice = mPreviousPrice.replace('<?= $currency ?>', '');
                                    mPreviousPrice = mPreviousPrice.replace(/\u00A3/g, '');
                                    if (isNumeric(mPriceLoop))
                                    {
                                        mPreviousPrice = parseFloat(mPreviousPrice) - parseFloat(mPriceLoop);
                                        mPreviousPrice = mPreviousPrice.toFixed(2);
                                    }
                                    mPreviousPrice = '<?= $currency ?>' + mPreviousPrice;
                                    $("#" + mTextboxId).val($("#" + mTextboxId).val().replace(mOptionIDLoop + ",", ""));
                                    $(' .active_form #retail_price').html(mPreviousPrice);
                                }
                            }
                        });
                    }
                }
            });
        });

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

//            $.each(attributeRequired, function (index, value) {
//
//                var isAllRequiredSelected = false;
//                var attributeElements = $('[attributeid=' + value + ']');
//
//                var filteredAttributeElements = attributeElements.slice(attributeElements.length / 2);
//                $.each(filteredAttributeElements, function (index, element) {
//
//                    if (element.tagName !== "SELECT")
//                    {
//                        if ($(element).is(':checked')) {
//                            isAllRequiredSelected = true;
//                        }
//                    }
//                    else
//                    {
//                        if ($(element).val() !== "") {
//                            isAllRequiredSelected = true;
//                        }
//                    }
//                });
//                var attributeId = 'attrRequired-' + value;
//                var attributeborderId = 'attrRequiredBorder-' + value;
//                if (!(isAllRequiredSelected))
//                {
//                    isValid = false;
//                    $($('[id=' + attributeId + ']')[1]).show();
//                    $($('[id=' + attributeborderId + ']')[1]).css({"display": "block", "border": "2px solid #9C0F17", "border-collapse": "separate", "border-spacing": "2px"});
//
//                }
//                else
//                {
//                    $($('[id=' + attributeId + ']')[1]).hide();
//                    $($('[id=' + attributeborderId + ']')[1]).css({"border": "none"});
//                }
//            });

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
                mUrl = "<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=favindex&addtocart=1&ProductID=" + product_id + "&rndm=" + mRandom + "&ajax=1";
                var dd = $('.active_form form').serialize();
                $.ajax
                        ({
                            url: mUrl,
                            type: 'POST',
                            data: $('.active_form form').serialize(),
                            success: function (data)
                            {
                                //alert('sucess')

                                var cart_html = '<div class="flip"><div id="edit_sign"><a href="#" onclick="event.preventDefault();showPopup(' + product_id + ',' + hasAssociates + ',' + hasAttributes + ',-1);" style="color:#8c1515;"><img border="0" src="../images/gray_edit.gif" height="14px"></a></div>'
                                        + '<div id="counting">' + product_title + '</div>'
                                '<div id="minus_sign" class="remove_cart"><a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=cart&amp;index=' + index + '"><img src="../images/minus_sign.gif" width="14" height="14" border="0"></a></div>'
                                '<div id="dollor"><?= $currency ?>' + price + '</div>'
                                '<div style="clear:left"></div>'
                                '</div>';
                                var cart_html = ' <li class=cart-item>'+
                                        '<div id="minus_sign" class="remove_cart"><a href="<?= $SiteUrl ?><?= $objRestaurant->url . "/" ?>?item=cart&amp;index=' + index + '"><img src="../images/minus_sign.gif" width="14" height="14" border="0"></a></div>'+
                                        '<input class=cart-item__quantity value=' + product_quantity + '>'+
                                                    '<div class=cart-item__info><h4 class=cart-item__name>' + product_title + ' </h4><span class=cart-item__price>' + price + '</span> </div>'+
                                                '<i class=cart-item__edit onclick="event.preventDefault();showPopup(' + product_id + ',' + hasAssociates + ',' + hasAttributes + ',-1);" ></i> </li>';
    //                    console.log(cart_html);
    //                    $('.cart').html(cart_html);

                                $(".cart-item__list2").append(cart_html)
                                //return cart_html;
                                //$(cart_html).insertAfter('#contents div.flip:last-child');

                                $(".cart").load("<?= $SiteUrl ?><?= $objRestaurant->url ?>/?item=cart&ajax=1");
                                $('.header__cart').html($('.cart-item').length)
                                
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

    </style>
