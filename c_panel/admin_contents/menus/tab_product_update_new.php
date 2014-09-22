<script type="text/javascript">
    $(function() {

        $("#add_item_form").validate({
            rules: {
                item_name: {required: true },
                price: {required: true}
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
            $("ul.tabs_product li").removeClass("active_product");
            $(this).addClass("active_product");
            $(".tab_content_product").hide();
            var activeTab = $(this).attr("rel");
            $("#"+activeTab).fadeIn();
        });

    });
</script>

<style>
    .alert-error {
        background-color:#f2dede;
        border-color:#eed3d7;
        color:#b94a48;
        text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);
        -webkit-border-radius:4px;
        -moz-border-radius:4px;
        border-radius:4px;

    }

    input[type=text].alert-error,input[type=select].alert-error,input[type=password].alert-error{
        background-color: #F99;
        border: 1px solid #D92353;
        border-image: initial;
    }
</style>

<form method ="post" id="update_item_form"  action="" enctype="multipart/form-data">
    <div class="required">
    </div>
    
    <div id ="main_div" class="main_div">
        <div id ="inner_div">
            <div id="backdiv"style="margin-top: 16px;margin-left: 7px;cursor: pointer"> <img src="img/back.png" /></div>
            <div style="float: right" class="items_root">asasaa</div>
            <div class="Submenu_Heading"></div>
            <div class="add_area_div">
                <table>
                    <tr>
                        <td><input type="hidden" value="" name="hdnProductId" id ="hdnProductId"/>
                            <input type="text" id="item_name" name="item_name" style="margin-left: 34px;margin-top: 30px;width:315px;padding:8px">
                        </td>
                        <td>
                            <input type="text" id="price" name="price" style="margin-left: 10px;margin-top: 30px;width: 237px;padding: 8px;" >
                        </td>
                    </tr>
                    <tr><td>
                            <textarea rows="4" cols="50" id="product_description" name="product_description" style="margin-left: 34px;resize: none;margin-top: 13px;width: 315px;height: 133px;padding: 8px;float: left;" placeholder="description of Item" >
                                
                            </textarea>
                        </td>
                        <td>
                            <div class="photo_div_before" id="show_photo_before" style="display:block;">
                                <div style="text-align: center;"  > Photo of item? </div>
                                <div style="height: 75px;width: 76px;margin-left: 45;">  <img src ="img/camera.png" id="item_img_camera" style="width: 90%;height: 79%;margin-left: 81px;margin-top: 8px;"/> </div>
                                <div style="text-align: center;text-align: center;width: 150px;margin-left: 46px;margin-top: 0px"> (Click to upload or drag and drop file here) </div></div>
                            <div class="photo_div" id="show_photo" style="display:none;">
                                <div style="text-align: center;"> Photo of item </div>
                                <img src ="" id="item_img" style="height: 72%;margin-left: 40px;width: 141px;margin-top: 9px;"/></div>
                            <input name="userfile" type="file" id="userfile" size="30" style="margin-top: -148px;opacity: 0;width: 260px;height: 165px;">
                        </td>
                    </tr>
                </table>
                <table id ="chk_area" style="float: left;margin-left: 34px;margin-top: 25px;width: 250px;">
                    <tr><td style="width: 85px;"><input type="checkbox" name ="type[]" id ="new" value="0" />
                            <div><img src="img/new_icon.png" style="margin-left: 30px;margin-top: -25px;"></div></td>
                        <td><input type="checkbox" name ="type[]" id ="vegan" value="1" />
                            <div><img src="img/vegan_icon.png" style="margin-left: 30px;margin-top: -25px;"></div></td>
                    </tr>
                    <tr><td class="padding_td"><input type="checkbox" name ="type[]" id ="POPULAR" value="2" />
                            <div><img src="img/POPULAR_icon.png" style="margin-left: 27px;margin-top: -25px;"></div></td>
                        <td class="padding_td"><input type="checkbox" name ="type[]" id ="nut_free" value="3" />
                            <div><img src="img/nutfree_icon.png" style="margin-left: 30px;margin-top: -25px;"></div></td>
                    </tr>
                    <tr><td class="padding_td"><input type="checkbox" name ="type[]" id ="glutenfree" value="4" />
                            <div><img src="img/glutenfree_icon.png" style="margin-left: 27px;margin-top: -25px;"></div></td>
                        <td class="padding_td"><input type="checkbox" name ="type[]" id ="LOWFAT" value="5" />
                            <div><img src="img/LOWFAT_icon.png" style="margin-left: 30px;margin-top: -25px;"></div></td>
                    </tr>
                    <tr><td class="padding_td"><input type="checkbox" name ="type[]" id ="vegetarian" value="6" />
                            <div><img src="img/vegetarian_icon.png" style="margin-left: 27px;margin-top: -25px;"></div></td>
                        <td class="padding_td"><input type="checkbox" name ="type[]" id ="spicy" value="7" />
                            <div><img src="img/spicy_icon.png" style="margin-left: 30px;margin-top: -25px;"></div></td>
                    </tr>
                    <tr><td colspan="2" class="padding_td"><input type="checkbox" name="type[]" id ="spicy1" value="8"/>
                            <div style="margin-top: -12px;"><img src="img/spicy_icon.png" style="margin-left: 27px;margin-top: -25px;">
                                <img src="img/spicy_icon.png" style="margin-left: 5px;margin-top: -25px;"></div></td>
                    </tr>
                    <tr><td colspan="3" class="padding_td"><input type="checkbox" name="type[]" id ="spicy2" value="9" />
                            <div style="margin-top: -12px;"><img src="img/spicy_icon.png" style="margin-left: 27px;margin-top: -25px;">
                                <img src="img/spicy_icon.png" style="margin-left: 5px;margin-top: -25px;">
                                <img src="img/spicy_icon.png" style="margin-left: 5px;margin-top: -25px;"></div></td>
                    </tr>
                </table>

                <input type="submit" value="Update" name="btnUpdateProduct" class="btnadd" id="btnUpdateProduct" style="width: 14%;margin-top: 89px;">
                <input type ="button" value="Cancel" name ="cancel_click_product" id="cancel_click_product" class="cancel"/>
            </div>
        </div>
        <div id="right_panel">
            <ul class="tabs_product" style="border-bottom: 2px solid #999999;">
                <li class="active_product" rel="tab1_product"> Attributes</li>
                <li rel="tab2_product"> Related Items</li>
            </ul>

            <div class="tab_container_product" style="height: 500px;">

                <div id="tab1_product" class="tab_content_product">
                    <ul id="attr-list_product">

                    </ul>

                </div>
                <div id="tab2_product" class="tab_content_product">

                    <ul id="related-list_product">

                    </ul>

                </div>
            </div>
        </div>

    </div>
    
     <a class="fancyTrigger" href="#TheFancybox"></a>
    <div id="TheFancybox">
        <div style="background-image: url('img/fupload.png');width:558px;height: 390px;display: none;" class="uploader_div">
            <div style="text-align: center">
                <input type="submit" value ="Browse Files" name="upd_file_btn" id="upd_file_btn" class="updfile"/>
                <input name="userfile-uploader" type="file" id="userfile-uploader" size="30" style="display: none">
            </div>
        </div>
    </div>
</form>