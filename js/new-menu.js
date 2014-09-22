function DOMReady(){
    //loadAllRelatedItem();
    $(".chzn-select").chosen({
        width: "200px"
    });
    $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
    
    $('.form-progress-wrapper').css('display','none');
    
    $("#product_description").val($.trim($("#product_description").val()));
    $("#description_menu").val($.trim($("#description_menu").val()));
    $("#description").val($.trim($("#description").val()));


    //if product not add hide all dropdown value in product page
    if(window.location.search.indexOf("addproduct") != -1){
        $("select option").hide();
        $("#addAttributetxt").trigger('liszt:updated')
        $("#typeRelatedItemName").trigger('liszt:updated')
    }
    
    $( "#sortable1, #sortable2" ).sortable({
        handle: '#imgMove',
        connectWith: ".connectedSortable",
        stop: function(event, ui){
            parseIDs();
        }
    });

    $( ".clsS" ).sortable({
        handle: '#imgMove',
        connectWith: ".clsS",
        stop: function(event, ui)
        {
            parseIDs();
        }
    });
    $("#tblProduct", ".demo").each(function(){
        $(this).mouseover(function(){
            $("#dvIcon", this).hide();
            $("#dvOptions", this).show();

        });

        $(this).mouseout(function(){
            $("#dvIcon", this).show();
            $("#dvOptions", this).hide();
        });
    });

    $("#add_new_submenu_link").click(function(){
//       $("#add_new_menu").animate({
//            left:'0px',
//            height:'0px',
//            width:'0px',
//            bottom:'0px'
//        },600);

        $("#add_new_submenu").animate({
            left:'100px',
            height:'480px',
            width:'500px',
            bottom:'-20px'
        },600);
        $("#btnUpdate").hide()
        $("#btnAdd").show();
        $("#submenu_name").val('');
        $("#description").val('');
        $("#hdnCatid").val('');
        $("#Submenu_Heading").text('Add New Sub Menu');
        $("#attr_desc").css('background-color','transparent');
        $("#attr_desc").css('height','81px');
        $("#attr_desc").html('Any Attribut? Click on Attributes in your textbox at right, to add. "Click New" to add new attribute.');
    });

    $("#menu_form").unbind("submit").bind('submit', function(){
        event.preventDefault();
       
        if(!$(this).valid()){
            return false;
        }
        var catid = '';
        var menu_id = '';
        var menu_name = '';
        var selected_menu = $(".selected").attr("href");


        var sPageURL = selected_menu.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == "catid")
            {
                catid =  sParameterName[1];
            }
            else if (sParameterName[0] == "menuid")
            {
                menu_id =  sParameterName[1];
            }
            else if (sParameterName[0] == "menu_name")
            {
                menu_name =  sParameterName[1];
            }
        }



        if($("#hdnCatid").val()=='')
        {
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?add=1&catid="+catid+"&menuid="+menu_id+"&menu_name="+menu_name,
                data: $("#menu_form").serialize(),
                success: function(data) {
                    if(data==1)
                    {
                        window.location.href = "?mod=new_menu&catid="+catid+"&menuid="+menu_id+"&menu_name="+menu_name;

                    //$("#add_new_submenu").hide();
                    //$('#main_tbl').load('?mod=new_menu&ajax=1',function(){
                    //    DOMReady();
                    //});
                    }
                }
            });
        }
        else{

            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?update=1",
                data: $("#menu_form").serialize(),
                success: function(data) {
                    if(data==1)
                    {   
                        window.location.href = "?mod=new_menu&catid="+catid+"&menuid="+menu_id+"&menu_name="+menu_name;
                    //                        $("#add_new_submenu").hide();
                    //                        $('#main_tbl').load('?mod=new_menu&ajax=1',function(){
                    //                            DOMReady();
                    //                        });
                    }
                }
            });
        }
    });

    $('.submenu_edit').click(function(){
        

        var id = $(this).attr("id");
        
        $("#attr_desc").css('height','15px');
        $("#attr_desc").css('background-color','#EBEBEB');
        var totalAttribute='';
        $.ajax({
            //type:"post",
            url: "admin_contents/menus/menu_ajax.php?sub_menuid="+id+"&count_attr=1",
            success: function(data) {//console.log(data)
                $("#attr_desc").html('Attributes:'+data);
            //$("#attr_desc").css('height','15px');
            }
        });
        $("#add_new_submenu").animate({
            left:'100px',
            height:'400px',
            width:'500px',
            bottom:'-20px'
        },600);
        $("#add_new_submenu").show();
        $("#submenu_name").removeClass("alert-error");
        $("#description").removeClass("alert-error");
        $("#submenu_name").val($(this).attr("alt"));
        $("#description").val($(this).attr("desc"));
        $("#attribut_details").text('');
        $("#hdnCatidbtn_addAttribute").val($(this).attr("cat_id"));
        $("#btnUpdate").show();
        $("#btnAdd").hide();
        $("#Submenu_Heading").text('Edit '+$(this).attr( "alt" ));
        $("#attr_desc").html(totalAttribute);
        $("#subcat_id").val($("#hdnCatid").val());
        $("#sub_cat_id").val($("#hdnCatid").val());

        //Load right Panel Attributes and related Items
        $("#main_tab1").text('Attributes');
        $("#main_tab2").show();
        $('#attr-list').show();
        $("#NewAttributeLi").show();
        //$("#ExistingAttributeLi").show();
        $('#menuinfo').hide();

        loadAttribute($("#hdnCatid").val());
        var attribute = getattrforRightToolsBox($(this).attr("cat_id"));
        var related = getAssociationforRightToolsBox($(this).attr("cat_id"));


        related.success(function (data) {
            related = jQuery.parseJSON(data);
            
            // Show related Items
            $('#related-list').html('')
            var str1 = '';
            if (related != undefined || related != null ){
                for (i in related) {
                    var img1 = $('<div><img></div>');
                    img1.find('img').attr({
                        'src': '../c_panel/img/delete.png',
                        'assoc_id':i,
                        'subcatid':id
                    });
                    img1.find('img').css('float', 'right');
                    img1.find('img').css('cursor', 'pointer');
                    img1.find('img').addClass("related_delete");
                    img1.find('img').addClass("related_delete");
                    str1 += '<li id=\'related_' + i + '\'>'+related[i]+' '+img1.html()+'  </li>';
                }
                $('#related-list').append(str1);
                if($('#related-list li').length>0)
                {
                   $('.noRelatedItems').hide();
                }
                else
                {
                   $('.noRelatedItems').hide();
                }
            }
        });

    
        attribute.success(function (data) {
            attribute = jQuery.parseJSON(data);
        
            $('#attr-list').html('')
            //var attribute =item_attr[$(this).attr("id")];
            var str = '';
            var img = $('<div><img></div>');
            if (attribute != undefined || attribute != null ){
                for (var i  in attribute) {
                    img.find('img').attr({
                        'src': '../c_panel/img/delete.png',
                        'option_name':attribute[i],
                        'display_name':i
                    });
                    img.find('img').css('float', 'right');
                    img.find('img').css('cursor', 'pointer');
                    img.find('img').addClass("attr_delete");
                    str += '<li id=\'attr_' + attribute[i].split(' ').join('') + '\'><a href="#dvAddAttributeSM" class="option_NameSM" style="cursor:pointer">'+attribute[i]+'</a> '+img.html()+'  </li>';
                    
                }
                
                $('#attr-list').append(str);
                if($('#attr-list li').length>0)
                {
                   $('.noAttributes').hide();
                }
                else
                {
                    $('.noAttributes').show();
                }
            }
        });
    });

    $("#cancel_click").click(function(){
        //var menuname = GetURLParameter('menu_name');
        var menuname = $("#menuname").val();
        $('#attr-list').hide();
        $('#menuinfo').show();
        $("#NewAttributeLi").hide();
        $("#ExistingAttributeLi").hide();
        $("#main_tab1").text(menuname);
        $("#main_tab2").hide();
        $(".tab_content").hide();
        $(".tab_content:first").show();
        $("#main_tab1").addClass("active");
        $("#add_new_submenu").animate({
            left:'0px',
            height:'0px',
            width:'0px',
            bottom:'0px'
        },600);

    });

    //    $('.submenu_item_Add').click(function(){
    //        //$.session("catid", GetURLParameter('catid'));
    //        window.location.href = "?mod=new_menu&item=addproduct_new&scid="+$(this).attr( "alt");
    //    });

    //copy sub menu and its data
    $('.submenu_item_Copy').click(function(){
        var confirmmsg = confirm("Are you sure you want to copy restaurant menu?");
        var id =  $(this).attr( "alt" );

        var catid = '';
        var menu_id = '';
        var menu_name = '';
        var selected_menu = $(".selected").attr("href");


        var sPageURL = selected_menu.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == "catid")
            {
                catid =  sParameterName[1];
            }
            else if (sParameterName[0] == "menuid")
            {
                menu_id =  sParameterName[1];
            }
            else if (sParameterName[0] == "menu_name")
            {
                menu_name =  sParameterName[1];
            }
        }


        if (confirmmsg==true){
            $.ajax({
                //type:"post",
                url: "admin_contents/menus/menu_ajax.php?cat_id="+id+"&copy=1"+"&menuid="+menu_id,
                success: function(data) {
                    if(data>0)
                    {
                        window.location.href = "?mod=new_menu&catid="+catid+"&menuid="+menu_id+"&menu_name="+menu_name;
                    //                        $('#main_tbl').load('?mod=new_menu&ajax=1',function(){
                    //                            DOMReady();
                    //                        });
                    }
                }
            });
        }
    //alert($(this).attr( "alt"));
    });

    $('.submenu_item_Delete').click(function(){
        var confirmmsg = confirm("Are you sure you want to Delete this Sub menu?");
        var id =  $(this).attr( "alt" );

        var catid = '';
        var menu_id = '';
        var menu_name = '';
        var selected_menu = $(".selected").attr("href");


        var sPageURL = selected_menu.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == "catid")
            {
                catid =  sParameterName[1];
            }
            else if (sParameterName[0] == "menuid")
            {
                menu_id =  sParameterName[1];
            }
            else if (sParameterName[0] == "menu_name")
            {
                menu_name =  sParameterName[1];
            }
        }


        if (confirmmsg==true){
            $.ajax({
                //type:"post",
                url: "admin_contents/menus/menu_ajax.php?cat_id="+id+"&delete_submenu=1",
                success: function(data) {
                    if(data>0)
                    {
                        window.location.href = "?mod=new_menu&catid="+catid+"&menuid="+menu_id+"&menu_name="+menu_name;
                    //                        $('#main_tbl').load('?mod=new_menu&ajax=1',function(){
                    //                            DOMReady();
                    //                        });
                    }
                }
            });
        }
    //alert($(this).attr( "alt"));
    });


    $('.deleteItem').click(function(){
        var confirmmsg = confirm("Are you sure you want to delete this product");
        var id =  $(this).attr( "alt" );


        var catid = '';
        var menu_id = '';
        var menu_name = '';
        var selected_menu = $(".selected").attr("href");


        var sPageURL = selected_menu.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == "catid")
            {
                catid =  sParameterName[1];
            }
            else if (sParameterName[0] == "menuid")
            {
                menu_id =  sParameterName[1];
            }
            else if (sParameterName[0] == "menu_name")
            {
                menu_name =  sParameterName[1];
            }
        }


        if (confirmmsg==true){
            $.ajax({
                //type:"post",
                url: "admin_contents/menus/menu_ajax.php?prd_id="+id+"&prd_delete=1",
                success: function(data) {
                    if(data==1)
                    {
                        window.location.href = "?mod=new_menu&catid="+catid+"&menuid="+menu_id+"&menu_name="+menu_name;
                    //$("#main_tbl").load("?mod=new_menu #main_tbl");
                    // $('#main_tbl').load('?mod=new_menu&delete=1&ajax=1',function(){
                    //  DOMReady();
                    //});
                    }
                }
            });
        }
    });

    $('.copyItem').click(function(){
        var confirmmsg = confirm("Are you sure you want to copy this Item?");
        var id =  $(this).attr( "alt" );


        var catid = '';
        var menu_id = '';
        var menu_name = '';
        var selected_menu = $(".selected").attr("href");


        var sPageURL = selected_menu.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == "catid")
            {
                catid =  sParameterName[1];
            }
            else if (sParameterName[0] == "menuid")
            {
                menu_id =  sParameterName[1];
            }
            else if (sParameterName[0] == "menu_name")
            {
                menu_name =  sParameterName[1];
            }
        }
        
        if (confirmmsg==true){
            $.ajax({
                //type:"post",
                url: "admin_contents/menus/menu_ajax.php?prd_id="+id+"&prd_copy=1",
                success: function(data) {
                    if(data>0)
                    {
                        window.location.href = "?mod=new_menu&catid="+catid+"&menuid="+menu_id+"&menu_name="+menu_name;
                    //                        $('#main_tbl').load('?mod=new_menu&delete=1&ajax=1',function(){
                    //                            DOMReady();
                    //                        });
                    }
                }
            });
        }
    });

    $('.editItem').click(function(){
        var scid = $(this).parents('.lItem').find('.lblCat').attr('id');
        var id =  $(this).attr( "alt" );
        
        window.location.href = "?mod=new_menu&item=updateproduct_new&prd_id="+id+"&sub_cat="+scid;
    });

    $('.rdb_status').click(function(){
        var confirmmsg = confirm("Are you sure you would like to change the status of this Product?");
        var el =$(this);
        var id =  el.attr( "alt" );
        var status = el.attr( "status" );

        if (confirmmsg==true){
            $.ajax({
                //type:"post",
                url: "admin_contents/menus/menu_ajax.php?prd_id="+id+"&item_deactivate=1&status="+status+"",
                success: function(data) {
                    if(data=='Activate')
                    {
                        //el.parents('.item_data').find('.price-class').addClass("enable-menu");
                        el.parents('.item_data').find('.disable-menu').removeClass( "disable-menu").addClass("enable-menu");
                        el.parents('.item_data').find('.disable-menu-options').removeClass( "disable-menu-options").addClass("enable-menu-options");
                        el.attr("title","Disable");
                        el.attr("src","../c_panel/img/enable.png");
                        el.attr("status","1");
                    }
                    else if(data=='Deactivate')
                    {
                        //var data = el.closest('.item_data');
                        //el.parents('.item_data').find('.price-class').addClass("disable-menu");
                        el.parents('.item_data').find('.enable-menu').removeClass( "enable-menu").addClass("disable-menu");
                        el.parents('.item_data').find('.enable-menu-options').removeClass( "enable-menu-options").addClass("disable-menu-options");
                        el.attr("title","Enable");
                        el.attr("src","../c_panel/img/disable.png");
                        el.attr("status","0");
                    }
                }
            });
        }
    });


    $("#userfile").change(function(){
        $("#show_photo_before").css('opacity','0.5');
        $("#show_photo").css('opacity','0.5');
        $('.form-progress-wrapper').show();
        
        var data = new FormData();
        jQuery.each($('#userfile')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });

        // event.preventDefault();

        $.ajax({
            type:"post",
            url: "admin_contents/menus/menu_ajax.php?imgupload=1",
            data: data,
            cache: false,
            contentType: false,
            processData: false,

            success: function(data) {
                if(data!='')
                {
                    $("#show_photo").show();
                    $("#show_photo_before").hide();
                    $("#item_img").attr("src","../c_panel/img/"+data);
                    $.fancybox.close();
                    $(".deleteimg").show();
                    $('.form-progress-wrapper').hide();
                    $("#show_photo_before").css('opacity','1.5');
                    $("#show_photo").css('opacity','1.5');

                }
            }
        });

    });

    $("#add_item_form").unbind("submit").bind('submit', function(e){
        e.preventDefault();
        if(!$(this).valid()){
            return false;
        }

        var rest_id = $("#restaurantid").val();
        var scid = GetURLParameter('sub_cat');

        var ext = $("#item_img").attr('src').split("/").pop(-1);
        $.ajax({
            type:"POST",
            url: "admin_contents/menus/menu_ajax.php?add_menu_item=1&ext="+ext+"&sub_cat="+scid+"&restid="+rest_id+"&attributes="+$("#hdnAttributes").val(),
            data: $("#add_item_form").serialize(),
            success: function(data) {

                if(data>0)
                {
                    $("#prd_id").val(data);
                    $("#product_id").val(data);
                    $("#prod_id").val(data);
                    $("select option").css('display','block');
                    
                    $("#addAttributetxt").trigger('liszt:updated');
                    $("#typeRelatedItemName").trigger('liszt:updated');
                    window.alert('Item added Successfully');
                }
            }
        });
    //}
    });


    $("#update_item_form").unbind("submit").bind('submit', function(e){
        e.preventDefault();
        if(!$(this).valid()){
            return false;
        }


        var prd_id = $("#prd_id").val();
        var subcat_id = GetURLParameter('sub_cat');
        //var prd_id = $("#hdnProductId").val();
        var ext = $("#item_img").attr('src').split("/").pop(-1);
        
        $.ajax({
            type:"POST",
            url: "admin_contents/menus/menu_ajax.php?update_menu_item=1&ext="+ext+"&prd_id="+prd_id+"&sub_catid="+subcat_id,
            data: $("#update_item_form").serialize(),
            success: function(data) {

                if(data!='')
                {
                    var d = jQuery.parseJSON(data);
                    var menu_id = d.id;
                    var catid = d.rest_id;
                    var menu_name = d.menu_name
                    window.location.href = "?mod=new_menu&catid="+catid+"&menuid="+menu_id+"&menu_name="+menu_name;
                }
            }
        });
    //}
    });

    $('#userfile').click(function(e){
        e.preventDefault();
        $('.uploader_div').css({
            "display":"block"
        });
        $(".fancyTrigger").fancybox({

            }).click();
       
    //document.getElementById("#popup").style.display = 'block';
    //$(".fancyTrigger").fancybox().click();
    });
    

    $("#upd_file_btn").click(function(){
        $('input[name=userfile-uploader]').click();
        
        $("#userfile-uploader").change(function(){
            
            var data = new FormData();
            jQuery.each($('#userfile-uploader')[0].files, function(i, file) {
                data.append('file-'+i, file);
            });

            $.ajax({
                type:"post",
                url: "admin_contents/menus/menu_ajax.php?imgupload=1",
                data: data,
                cache: false,
                contentType: false,
                processData: false,

                success: function(data) {
                    if(data!='')
                    {
                        //console.log(data)
                        $("#show_photo").show();
                        $("#show_photo_before").hide();
                        $("#item_img").attr("src","../c_panel/img/"+data);
                        $.fancybox.close();
                        $(".deleteimg").show();
                       

                    }
                }
            });
        });
    });

    $('.lblCat').click(function(){
        $("#main_tab1").text('Attributes');
        $("#main_tab2").show();
        $('#attr-list').show();
        $('#menuinfo').hide();
        
        $("#subcat_id").val($("#hdnCatid").val());
        $("#hdnCatid").val($(this).attr('id'));
        $("#sub_cat_id").val($("#hdnCatid").val());

        
        var related = getAssociationforRightToolsBox($("#sub_cat_id").val());

        related.success(function (data) {
            related = jQuery.parseJSON(data);
            // Show related Items
            $('#related-list').html('')
            var str1 = '';
            if (related != undefined || related != null ){
                for (i in related) {
                    var img1 = $('<div><img></div>');
                    img1.find('img').attr({
                        'src': '../c_panel/img/delete.png',
                        'assoc_id':i,
                        'subcatid':$("#sub_cat_id").val()
                    });
                    img1.find('img').css('float', 'right');
                    img1.find('img').css('cursor', 'pointer');
                    img1.find('img').addClass("related_delete");
                    img1.find('img').addClass("related_delete");
                    str1 += '<li id=\'related_' + i + '\'>'+related[i]+' '+img1.html()+'  </li>';
                }
                $('#related-list').append(str1);
                if($('#related-list li').length>0)
                {
                   $('.noRelatedItems').hide();
                }
                else
                {
                   $('.noRelatedItems').hide();
                }
            }
        });



        var attribute = getattrforRightToolsBox($("#sub_cat_id").val());

        attribute.success(function (data) {
            attribute = jQuery.parseJSON(data);

            $('#attr-list').html('')
            //var attribute =item_attr[$(this).attr("id")];
            var str = '';
            var img = $('<div><img></div>');

            if (attribute != undefined || attribute != null ){
                for (var i  in attribute) {
                    img.find('img').attr({
                        'src': '../c_panel/img/delete.png',
                        'option_name':attribute[i],
                        'display_name':i
                    });
                    img.find('img').css('float', 'right');
                    img.find('img').css('cursor', 'pointer');
                    img.find('img').addClass("attr_delete");
                    str += '<li id=\'attr_' + attribute[i] + '\'><a href="#dvAddAttributeSM" class="option_NameSM" style="cursor:pointer">'+attribute[i]+'</a> '+img.html()+'  </li>';
                }
                $('#attr-list').append(str);
                if($('#attr-list li').length>0)
                {
                   $('.noAttributes').hide();
                }
                else
                {
                    $('.noAttributes').show();
                }
            }
        });
          
    });

    //delete attribute
    $('.attr_delete').live("click", function(){
        var option_name = $(this).attr("option_name");
        var display_name = $(this).attr("display_name");
        var prd_id = $("#prd_id").val();
        var subcat_id = $("#hdnCatid").val();

        var confirmmsg = confirm("Are you sure you want to delete attribute?");
        if(!isNaN(prd_id)){
            if (confirmmsg==true){
                $.ajax({
                    type:"POST",
                    url: "admin_contents/menus/menu_ajax.php?remove_attributes=1&option_name="+option_name+"&prd_id="+prd_id+"&display_name="+display_name,
                    success: function(data) {

                        if(data>0)
                        {
                            $('#attr_'+option_name.split(' ').join('')).remove();
                            if($('#attr-list_product li').length==0)
                            {
                                $('.noAttributesForProduct').show();
                            }
                        }
                    }
                });
            }
        }
        else{
            if (confirmmsg==true){
                var count_attr = parseInt($("#attr_desc").html().split(":").pop());

                $.ajax({
                    type:"POST",
                    url: "admin_contents/menus/menu_ajax.php?remove_attributesfromCategory=1&option_name="+option_name+"&sub_catid="+subcat_id+"&display_name="+display_name,
                    success: function(data) {

                        if(data>0)
                        {
                            $('#attr_'+option_name.split(' ').join('')).remove();
                            count_attr = count_attr-1;
                            $("#attr_desc").html('Attributes:'+count_attr);
                            
                            if($('#attr-list li').length==0)
                            {
                                $('.noAttributes').show();
                            }
                        }
                    }
                });
            }
        }
    });


    // Delete related Items
    $('.related_delete').live("click", function(){
   
        var related_id = $(this).attr("assoc_id");
        var prd_id = $(this).attr("prd_id");
        var subcatid = $("#hdnCatid").val();
        //alert(related_id);
        var confirmmsg = confirm("Are you sure you want to delete this related item?");

        if(!isNaN(prd_id)){
            if (confirmmsg==true){
                $.ajax({
                    type:"POST",
                    url: "admin_contents/menus/menu_ajax.php?remove_relatedItems=1&relatedItemid="+related_id+"&prd_id="+prd_id,
                    success: function(data) {

                        if(data>0)
                        {
                            $('#related_'+related_id).remove();
                            if($('#related-list_product li').length==0)
                            {
                                $('.noRelatedItems').show();
                            }
                        }
                    }
                });
            }
        }else{
            if (confirmmsg==true){
                $.ajax({
                    type:"POST",
                    url: "admin_contents/menus/menu_ajax.php?remove_relItemCategory=1&relatedItemid="+related_id+"&subcat_id="+subcatid,
                    success: function(data) {

                        if(data>0)
                        {
                            $('#related_'+related_id).remove();
                            if($('#related-list li').length==0)
                            {
                                $('.noRelatedItems').show();
                            }
                        }
                    }
                });
            }
        }

    });

    // Open Fancy Box for add related Item
    $("#RelatedItemLi").click(function(){

        $("#typeRelatedItemName option:first").attr('selected','selected');
        $("#typeRelatedItemName").trigger('liszt:updated');
        $(".fancyRelatedItem").fancybox().click();
        $("#displayMessage").hide();
        
    //$("#popup").show();
    });


    // Add related Item on Tool Box
    $("#addRelatedIteminProduct").click(function(){
        //var prd_id = GetURLParameter('prd_id');
        var prd_id = $("#prd_id").val();
        var subcatid = $("#hdnCatid").val();
        var ItemName = $("#typeRelatedItemName :selected").text();
        var assoc_id = $("#typeRelatedItemName :selected").val();
        if(assoc_id==''){
            $("#displayMessage").text('Please Select Item');
            $("#displayMessage").show();
            return false;
        }
      
        if(!isNaN(prd_id)){
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?addRelatedItem=1&assoc_id="+assoc_id+"&prd_id="+prd_id,
                success: function(data) {

                    if(data>0)
                    {
                        var img = $('<div><span></div>'); //Equivalent: $(document.createElement('img'))
                        img.find('span').attr({
                            'prd_id':+prd_id,
                            'assoc_id':assoc_id
                        });
                        
                        img.find('span').addClass("related_delete");
                        $("#related-list_product").append('<li id=\'related_' + assoc_id + '\' class="liRelated"><span style="width:80%;float:left">'+ItemName+'</span>'+img.html()+'  <div style="clear:both"></div></li>');
                        $.fancybox.close();
                        $('.noRelatedItems').hide();
                    
                    }
                    else{
                        $("#displayMessage").text('Item already Exist');
                        $('#displayMessage').show();
                    }
                }
            });
        }else
        {
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?addRelatedItemInCategory=1&assoc_id="+assoc_id+"&sub_catid="+subcatid,
                success: function(data) {

                    if(data>0)
                    {
                        $('#related_'+assoc_id).remove();
                        var img = $('<div><img></div>'); //Equivalent: $(document.createElement('img'))
                        img.find('img').attr({
                            'src': '../c_panel/img/delete.png',
                            'assoc_id':assoc_id,
                            'subcatid':+subcatid
                        });
                        img.find('img').css('float', 'right');
                        img.find('img').css('cursor', 'pointer');
                        img.find('img').addClass("related_delete");
                        $("#related-list").append('<li id=\'related_' + assoc_id + '\'>'+ItemName+' '+img.html()+'  </li>');
                        $.fancybox.close();
                        $('.noRelatedItems').hide();
                    

                    }
                }
            });
        }
    });


    // Load all Related Items
    $("#browseRelatedItems").click(function(){
        $(".fancyloadRelatedItem").fancybox().click();
        
        $("#checkboxBind").html('');
        $("#typeRelatedItemName").val('');
        var cat_id = $("#restaurantid").val();
        var subcatid = $("#hdnCatid").val();
        var prd_id = $("#prd_id").val();

        if(isNaN(subcatid))
        {
            subcatid = $("#hdnCatid").val();
        }

        if(prd_id==''){
            $("#checkboxBind").append('Please Add Item First');
            return false;
        }
        if(!isNaN(prd_id)){
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?LoadRelatedItem=1&cat_id="+cat_id+"&prd_id="+prd_id+"&sub_catid="+subcatid,
                success: function(data) {

                    if(data!='')
                    {
                        $("#checkboxBind").append(data);
                        
                    }
                     else
                    {
                        $("#checkboxBind").append('No Item');
                    }
                }
            });
        }else{
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?LoadRelatedItemByCategory=1&cat_id="+cat_id+"&sub_catid="+subcatid,
                success: function(data) {

                    if(data!='')
                    {
                        $("#checkboxBind").append(data);
                    }
                     else
                    {
                        $("#checkboxBind").append('No Item');
                    }
                }
            });
        }
    });


    //load all attribute on popup
    $("#browseAttributes").click(function(){
        $(".fancyloadAttributeItem").fancybox().click();
        $("#checkboxBindAttribute").html('');
        $("#addAttributetxt").val('');
        //var cat_id = $("#restaurantid").val();
        var sub_catid = $("#hdn_subcatid").val();
        
        if(isNaN(sub_catid))
        {
            sub_catid = $("#hdnCatid").val();
        }
        var prd_id = $("#prd_id").val();
        if(prd_id==''){
            $("#checkboxBindAttribute").append('Please Add Item First');
            return false;
        }
            
        if(!isNaN(prd_id)){
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?LoadAttribute=1&sub_cat_id="+sub_catid+"&prd_id="+prd_id,
                success: function(data) {

                    if(data!='')
                    {
                        $("#checkboxBindAttribute").append(data);
                    }
                    else{
                        $("#checkboxBindAttribute").append('No Attribute saved for this category');
                    }
                }
            });
        }else{
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?LoadAttributeByCategory=1&sub_cat_id="+sub_catid,
                success: function(data) {
                
                    if(data!='')
                    {
                        $("#checkboxBindAttribute").append(data);
                    }
                    else{
                        $("#checkboxBindAttribute").append('No Attribute saved for this category');
                    }
                }
            });
        }
    });



    $("#ExistingAttributeLi").click(function()
    {   
        $("#addAttributetxt option:first").attr('selected','selected');
        $("#addAttributetxt").trigger('liszt:updated');
        $(".fancyAddAttribute").fancybox().click();
        $(".background_overlay").show();
    });

    // Add Attribute from Auto Complete textbox
    $("#btn_addAttribute").click(function(){
        //var prd_id = GetURLParameter('prd_id');
      
        var prd_id = $("#prd_id").val();
        var subcatid = $("#hdnCatid").val();
        if(isNaN(subcatid))
        {
            subcatid = $("#hdn_subcatid").val();
        }
          
        var display_Name = $("#addAttributetxt :selected").val();
        var option_Name = $("#addAttributetxt :selected").text();
        if(display_Name == ''){
            $("#attrDisplayMessage").html('Please select Attribute');
            $("#attrDisplayMessage").show();
            return false;
        }
        if(!isNaN(prd_id)){
            //Add attribute in Product from Add and Update Product Page
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?addAttributeinProduct=1&display_Name="+display_Name+"&prd_id="+prd_id+"&sub_catid="+subcatid+"&option_name="+option_Name,
                success: function(data) {

                    if(data>0)
                    {
                        var img = $('<div><span></div>');
                        img.find('span').attr({
                            'option_name':option_Name,
                            'display_name':display_Name
                        });

                        img.find('span').addClass("attr_delete");
                        $("#attr-list_product").append('<li id=\'attr_' + option_Name.split(' ').join('') + '\' class="liAtribute"><a href="#dvAddAttribute" class="option_Name" >'+option_Name+'</a> '+img.html()+'  <div style="clear:both"></div></li>');
                        $.fancybox.close();
                        $('.noAttributesForProduct').hide();
                    }
                    else{
                        $("#attrDisplayMessage").html('Attribute aleady exist')
                        $("#attrDisplayMessage").show();
                        return false;
                    }
                }
            });
        }
        else{
            //Add attribute in Category from Menu Page
            var count_attr = parseInt($("#attr_desc").html().split(":").pop());
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?addAttributeinCategory=1&display_Name="+display_Name+"&sub_catid="+subcatid,
                success: function(data) {

                    if(data!='')
                    {
                        $('#attr_'+option_Name.split(' ').join('')).remove();
                        var img = $('<div><img></div>'); //Equivalent: $(document.createElement('img'))
                        img.find('img').attr({
                            'src': '../c_panel/img/delete.png',
                            'option_name':option_Name,
                            'display_name':display_Name
                        });
                        img.find('img').css('float', 'right');
                        img.find('img').css('cursor', 'pointer');
                        img.find('img').addClass("attr_delete");
                        $("#attr-list").append('<li id=\'attr_' + option_Name.split(' ').join('') + '\'>'+option_Name+' '+img.html()+'  </li>');
                        $.fancybox.close();
                        count_attr = count_attr+1;
                        $("#attr_desc").html('Attributes:'+count_attr);
                        $('.noAttributes').hide();
                    }
                }
            });
        }
    });

    // fill auto complete text box
    //    function loadAllRelatedItem() {
    //        var cat_id = $("#restaurantid").val();
    //        var prd_id = $("#prd_id").val();
    //        var subcatid = $("#hdnCatid").val();
    //
    //        $.ajax({
    //            type:"POST",
    //            url: "admin_contents/menus/menu_ajax.php?GetallRelatedItem=1&prd_id="+prd_id+"&cat_id="+cat_id+"&subcatid="+subcatid,
    //            success: function(data) {
    //
    //                if(data!='')
    //                {
    //                    var availableTags = eval("("+data+")");
    //                   $( "#typeRelatedItemName" ).autocomplete({
    //                    source: function( request, response ) {
    //                    var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
    //                    response( $.grep( availableTags, function( item ){
    //                    return matcher.test( item );
    //                             }) );
    //                          }
    //                    });
    //                }
    //            }
    //        });
    //    }
    
    // load addl Attribure for AutoComplete textbox
    function loadAttribute(sub_catid) {
        $('#addAttributetxt').empty();
        $("#addAttributetxt").append($("<option></option>").val('').html('--Please Select--'));
        //         if(isNaN(sub_catid))
        //          {
        //              echo
        //              sub_catid = $("#hdn_subcatid").val();
        //          }
        //         console.log(sub_catid);
        
        //var cat_id = $("#restaurantid").val();
        //var prd_id = $("#prd_id").val();
        
        $.ajax({
            type:"POST",
            url: "admin_contents/menus/menu_ajax.php?GetallAttributeOptionName=1&sub_catid="+sub_catid,
            success: function(data) {

                if(data!='')
                {

                    var attribute = jQuery.parseJSON(data);
                    for(var i in attribute){
                        $("#addAttributetxt").append($("<option></option>").val(attribute[i]).html(i));
                    }
                    $("#addAttributetxt").trigger('liszt:updated');
                //                    var availableTags = eval("("+data+")");
                //                    $("#addAttributetxt" ).autocomplete({
                //                    source: function( request, response ) {
                //                    var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
                //                    response( $.grep( availableTags, function( item ){
                //                    return matcher.test( item );
                //                             }) );
                //                          }
                //                    });
                }
            }
        });
    }


    // Remove Photo
    $('.deleteimg').click(function(){
        $("#show_photo").hide();
        $("#show_photo_before").show();
        $("#item_img").attr("src","");
        $('.deleteimg').hide();
    });


    function getattrforRightToolsBox(subCatId)
    {
        return $.ajax({
            type:"POST",
            url: "admin_contents/menus/menu_ajax.php?GetAttributeForToolBox=1&sub_catid="+subCatId
        });
    }

    function getAssociationforRightToolsBox(subCatId)
    {
        return $.ajax({
            type:"POST",
            url: "admin_contents/menus/menu_ajax.php?GetAssociationForToolBox=1&sub_catid="+subCatId
        });
    }

    function GetURLParameter(sParam){
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }
    }

    //Menu menu Add div
    $("#add_mainmenu").click(function(){
        $("#menu_shuffle_form").hide();
        $("#menu_form").hide();
//        $("#add_new_submenu").animate({
//            left:'0px',
//            height:'0px',
//            width:'0px',
//            bottom:'0px'
//        },600);


        $("#add_new_menu").animate({
            left:'100px',
            height:'400px',
            width:'500px',
            bottom:'-20px'
        },600);
        $(this).parents('#menu_nav').find('.selected').removeClass("selected").addClass('previous menu_links');
        $(this).removeClass("menu_links").addClass('selected');
        $("#txt_menuname").val('');
        $("#txt_menudescription").val('');
    });

    $("#cancel_click_menu").click(function(){
        
        $('#menu_nav').find('.previous').removeClass("previous menu_links ").addClass('selected');
        $('#add_mainmenu').removeClass("selected").addClass('menu_links');
        $("#add_new_menu").animate({
            left:'0px',
            height:'0px',
            width:'0px',
            bottom:'0px'
        },600);
        $("#menu_shuffle_form").show();
        $("#menu_form").show();
    });

    $("#mainmenu_form").unbind("submit").bind('submit', function(){
        event.preventDefault();
        var restId = $("#restaurantid").val();
        if(!$(this).valid()){
            return false;
        }
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?addMainMenu=1"+"&restId="+restId,
                data: $("#mainmenu_form").serialize(),
                success: function(data) {
                    if(data>0)
                    {
                        window.location.href = "?mod=new_menu&catid="+restId+"&menuid="+data+"&menu_name="+$("#txt_menuname").val();
                    }
                }
            });
        }); 
}

$(document).ready(DOMReady);


function parseIDs()
{
    var mOutPut='';
    $(".demo").find(".connectedSortable").each(function()
    {
        $(this).find(".lItem").each(function()
        {
            mOutPut=mOutPut+"|"+$(this).find("#lblCatID").text()+",";
            $(this).find(".clsS").each(function()
            {
                $(this).find("#lblProductID").each(function()
                {
                    mOutPut=mOutPut+$(this).text()+",";
                });
            });
        });
    });
    $("#lblHidden").val(mOutPut);
}
