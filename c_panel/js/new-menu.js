function DOMReady(){
    //loadAllRelatedItem();
    $(".chzn-select").chosen({
        width: "200px"
    });
$('.chosen').chosen().change(
    function (evt) {
        var label = $(this.options[this.selectedIndex]).closest('optgroup').prop('label');
        alert(label);
});
setInterval(function(){animateTheBox();},500);

//***********************Copy MEnu *******************
$("#copyDiv").click(function()
{
    $(".fancyCopMenu").fancybox().click();
});
//*****************************************************
 //Bouncing Image ***************


    function animateTheBox() {
    $("#back-img").animate(
                {top: 20}, {
                    duration: 'slow',
                    easing: 'easeOutBack'
                }).animate(
                {top: -20}, {

                    duration: 'slow',
                    easing: 'easeOutBack'
                }).animate(
                {top: 20}, {

                    duration: 'slow',
                    easing: 'easeOutBack'
                },animateTheBox);
    }


    //********************//

//**********************alert plug in*********************///   
$.noty.defaults.template= '<div id="popup_box" class="popup_box" style="width:400px;min-height:250px;display:block"><div style="background-color: #25AAE1;font-size: 19px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;"><div style="margin-left: 112px;">Save Changes?</div></div><div style="margin-top: 40px;margin-left: 85px;"><input type="button" id="btnConfirmYes" name="btnConfirmYes" value="Yes" class="cancel" style="font-size: 20px;"><input type="button" id="btnConfirmNo" name="btnConfirmNo" value="No" class="cancel" style="font-size: 20px;"></div></div>'
$.noty.defaults.template_submenu_delete= '<div id="popup_box" class="popup_box" style="width:400px;min-height:250px;display:block"><div style="background-color: #25AAE1;font-size: 16px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;"><div style="margin-left: 12px;">Are you sure you want to delete this sub menu?</div></div><div style="margin-top: 40px;margin-left: 85px;"><input type="button" id="btnConfirmYes" name="btnConfirmYes" value="Yes" class="cancel" style="font-size: 20px;"><input type="button" id="btnConfirmNo" name="btnConfirmNo" value="No" class="cancel" style="font-size: 20px;"></div></div>'
$.noty.defaults.template_item_delete= '<div id="popup_box" class="popup_box" style="width:400px;min-height:250px;display:block"><div style="background-color: #25AAE1;font-size: 16px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;"><div style="margin-left: 12px;">Are you sure you want to delete this item?</div></div><div style="margin-top: 40px;margin-left: 85px;"><input type="button" id="btnConfirmYes" name="btnConfirmYes" value="Yes" class="cancel" style="font-size: 20px;"><input type="button" id="btnConfirmNo" name="btnConfirmNo" value="No" class="cancel" style="font-size: 20px;"></div></div>'
$.noty.defaults.template_attribute_delete= '<div id="popup_box" class="popup_box" style="width:400px;min-height:250px;display:block"><div style="background-color: #25AAE1;font-size: 16px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;"><div style="margin-left: 12px;">Are you sure you want to delete this attribute?</div></div><div style="margin-top: 40px;margin-left: 85px;"><input type="button" id="btnConfirmYes" name="btnConfirmYes" value="Yes" class="cancel" style="font-size: 20px;"><input type="button" id="btnConfirmNo" name="btnConfirmNo" value="No" class="cancel" style="font-size: 20px;"></div></div>'
$.noty.defaults.template_assoc_delete= '<div id="popup_box" class="popup_box" style="width:400px;min-height:250px;display:block"><div style="background-color: #25AAE1;font-size: 16px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;"><div style="margin-left: 12px;">Are you sure you want to delete this associate?</div></div><div style="margin-top: 40px;margin-left: 85px;"><input type="button" id="btnConfirmYes" name="btnConfirmYes" value="Yes" class="cancel" style="font-size: 20px;"><input type="button" id="btnConfirmNo" name="btnConfirmNo" value="No" class="cancel" style="font-size: 20px;"></div></div>'
$.noty.defaults.template1= '<div id="changes_saved" class="popup_box" style="width:400px;min-height:250px;display:block"><div style="background-color: #25AAE1;font-size: 19px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;"><div style="margin-left: 66px;">Your changes have been saved</div></div><div style="margin-top: 40px;text-align: center;"><input type="button" id="btnOK" name="btnOK" value="Ok" class="cancel" style="font-size: 20px;"></div></div>'
$.noty.defaults.template_menu_delete= '<div id="popup_box" class="popup_box" style="width:400px;min-height:250px;display:block"><div style="background-color: #25AAE1;font-size: 16px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;"><div style="margin-left: 12px;">Are you sure you want to delete this menu?</div></div><div style="margin-top: 40px;margin-left: 85px;"><input type="button" id="btnConfirmYes" name="btnConfirmYes" value="Yes" class="cancel" style="font-size: 20px;"><input type="button" id="btnConfirmNo" name="btnConfirmNo" value="No" class="cancel" style="font-size: 20px;"></div></div>'
//******************************End************************//
    //*******************Left Menu Navigation*****************//

    var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
                        showLeft = document.getElementById( 'showLeft' )

                showLeft.onclick = function() {
                        classie.toggle( this, 'active' );
                        classie.toggle( menuLeft, 'cbp-spmenu-open' );
                        disableOther( 'showLeft' );
                };

                function disableOther( button ) {
                        if( button !== 'showLeft' ) {
                                classie.toggle( showLeft, 'disabled' );
                        }

                }
    function removerHoverfromImages()
    {
        $("#leftimgRest").attr("src","../c_panel/img/restaurant.png");
        $("#leftimgOrder").attr("src","../c_panel/img/orders.png");
        $("#leftimgMenus").attr("src","../c_panel/img/Menus.png");
        $("#leftimgCustomers").attr("src","../c_panel/img/ew_verticalnav_32-03.png");
        $("#leftimgCoupans").attr("src","../c_panel/img/ew_verticalnav_32-05.png");
        $("#leftimgMailing").attr("src","../c_panel/img/mailing.png");
        $("#leftimgAnalytics").attr("src","../c_panel/img/ew_verticalnav_32-07.png");
    }
    $(".leftDivRest").hover(function(){

      $(this).find("#leftimgRest").attr("src","../c_panel/img/restaurant_mouseover.png");
      $(this).find(".leftheadingSpan").css("color","#d97a14");
      $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      },function(){
      $(this).find("#leftimgRest").attr("src","../c_panel/img/restaurant.png");
      $(this).find(".leftheadingSpan").css("color","#FFFFFF");
      $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
    });

    $(".leftDivOrder").hover(function(){

      $(this).find("#leftimgOrder").attr("src","../c_panel/img/orders_mouseover.png");
      $(this).find(".leftheadingSpan").css("color","#d97a14");
      $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      },function(){
      $(this).find("#leftimgOrder").attr("src","../c_panel/img/orders.png");
      $(this).find(".leftheadingSpan").css("color","#FFFFFF");
      $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
      if ($(this).next('div').next(".nestedMenu").is(':visible'))
      {

        $(this).find("#leftimgOrder").attr("src","../c_panel/img/orders_mouseover.png");
        $(".leftMenuArrow").removeClass('fa-caret-left');
        $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      }


    });
    $(".leftDivMenus").hover(function(){

      $(this).find("#leftimgMenus").attr("src","../c_panel/img/menus_mouseover.png");
      $(this).find(".leftheadingSpan").css("color","#d97a14");
      $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      },function(){
      $(this).find("#leftimgMenus").attr("src","../c_panel/img/Menus.png");
      $(this).find(".leftheadingSpan").css("color","#FFFFFF");
      $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
      if ($(this).next('div').next(".nestedMenu").is(':visible'))
      {

        $(this).find("#leftimgMenus").attr("src","../c_panel/img/menus_mouseover.png");
        $(".leftMenuArrow").removeClass('fa-caret-left');
        $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      }
    });
    $(".leftDivCustomers").hover(function(){

      $(this).find("#leftimgCustomers").attr("src","../c_panel/img/ew_verticalnav_32-04.png");
      $(this).find(".leftheadingSpan").css("color","#d97a14");
      $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      },function(){
      $(this).find("#leftimgCustomers").attr("src","../c_panel/img/ew_verticalnav_32-03.png");
      $(this).find(".leftheadingSpan").css("color","#FFFFFF");
      $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
      if ($(this).next('div').next(".nestedMenu").is(':visible'))
      {

        $(this).find("#leftimgCustomers").attr("src","../c_panel/img/ew_verticalnav_32-04.png");
        $(".leftMenuArrow").removeClass('fa-caret-left');
        $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      }
    });
    $(".leftDivCoupans").hover(function(){

      $(this).find("#leftimgCoupans").attr("src","../c_panel/img/ew_verticalnav_32-06.png");
      $(this).find(".leftheadingSpan").css("color","#d97a14");
      $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      },function(){
      $(this).find("#leftimgCoupans").attr("src","../c_panel/img/ew_verticalnav_32-05.png");
      $(this).find(".leftheadingSpan").css("color","#FFFFFF");
      $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
      if ($(this).next('div').next(".nestedMenu").is(':visible'))
      {
        $(this).find("#leftimgCoupans").attr("src","../c_panel/img/ew_verticalnav_32-06.png");
        $(".leftMenuArrow").removeClass('fa-caret-left');
        $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      }
    });

    $(".leftDivMailing").hover(function(){

      $(this).find("#leftimgMailing").attr("src","../c_panel/img/mailing__mouseover.png");
      $(this).find(".leftheadingSpan").css("color","#d97a14");
      $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      },function(){
      $(this).find("#leftimgMailing").attr("src","../c_panel/img/mailing.png");
      $(this).find(".leftheadingSpan").css("color","#FFFFFF");
      $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
      if ($(this).next('div').next(".nestedMenu").is(':visible'))
      {
        $(this).find("#leftimgMailing").attr("src","../c_panel/img/mailing__mouseover.png");
        $(".leftMenuArrow").removeClass('fa-caret-left');
        $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      }
    });
    $(".leftDivAnalytics").hover(function(){

      $(this).find("#leftimgAnalytics").attr("src","../c_panel/img/ew_verticalnav_32-08.png");
      $(this).find(".leftheadingSpan").css("color","#d97a14");
      $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      },function(){
      $(this).find("#leftimgAnalytics").attr("src","../c_panel/img/ew_verticalnav_32-07.png");
      $(this).find(".leftheadingSpan").css("color","#FFFFFF");
      $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
      if ($(this).next('div').next(".nestedMenu").is(':visible'))
      {
        $(this).find("#leftimgAnalytics").attr("src","../c_panel/img/ew_verticalnav_32-08.png");
        $(".leftMenuArrow").removeClass('fa-caret-left');
        $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      }


    });

    $(".leftDivReputation").hover(function(){

      $(this).find("#leftimgReputation").attr("src","../c_panel/img/reputation_rollover.png");
      $(this).find(".leftheadingSpan").css("color","#d97a14");
      $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      },function(){
      $(this).find("#leftimgReputation").attr("src","../c_panel/img/reputation_1.png");
      $(this).find(".leftheadingSpan").css("color","#FFFFFF");
      $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
      if ($(this).next('div').next(".nestedMenu").is(':visible'))
      {
        $(this).find("#leftimgReputation").attr("src","../c_panel/img/reputation_rollover.png");
        $(".leftMenuArrow").removeClass('fa-caret-left');
        $(this).find(".leftMenuArrow").addClass('fa-caret-left');
      }

    });

    $('.leftDivMenus').click(function()
    {
        removerHoverfromImages();
        $(".nestedMenu").slideUp(700);
        if ($(this).next('div').next(".nestedMenu").is(':visible'))
        {
            $(this).find("#leftimgMenus").attr("src","../c_panel/img/Menus.png");
            $(this).find(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
            $(this).next('div').next(".nestedMenu").slideUp(700);
        }
        else
        {
            $(this).find("#leftimgMenus").attr("src","../c_panel/img/menus_mouseover.png");
            $(this).next('div').next(".nestedMenu").slideDown(700);
            $(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftheadingSpan").addClass('leftclicked');

            $(".leftMenuArrow").removeClass('fa-caret-left');
            $(this).find(".leftMenuArrow").addClass('fa-caret-left');
        }

    });
    $('.leftDivOrder').click(function()
    {
        removerHoverfromImages();
        $(".nestedMenu").slideUp(700);
        if ($(this).next('div').next(".nestedMenu").is(':visible'))
        {
            $(this).find("#leftimgOrder").attr("src","../c_panel/img/orders.png");
            $(this).find(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
            $(this).next('div').next(".nestedMenu").slideUp(700);
        }
        else
        {
            $(this).find("#leftimgOrder").attr("src","../c_panel/img/orders_mouseover.png");
            $(this).next('div').next(".nestedMenu").slideDown(700);
            $(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftheadingSpan").addClass('leftclicked');
            $(".leftMenuArrow").removeClass('fa-caret-left');
            $(this).find(".leftMenuArrow").addClass('fa-caret-left');
        }
    });
    $('.leftDivCustomers').click(function()
    {
        removerHoverfromImages();
        $(".nestedMenu").slideUp(700);
        if ($(this).next('div').next(".nestedMenu").is(':visible'))
        {
            $(this).find("#leftimgCustomers").attr("src","../c_panel/img/ew_verticalnav_32-03.png");
            $(this).find(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
            $(this).next('div').next(".nestedMenu").slideUp(700);
        }
        else
        {
            $(this).find("#leftimgCustomers").attr("src","../c_panel/img/ew_verticalnav_32-04.png");
            $(this).next('div').next(".nestedMenu").slideDown(700);
            $(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftheadingSpan").addClass('leftclicked');
        }
    });
    $('.leftDivCoupans').click(function()
    {
        removerHoverfromImages();
        $(".nestedMenu").slideUp(700);
        if ($(this).next('div').next(".nestedMenu").is(':visible'))
        {
            $(this).find("#leftimgCoupans").attr("src","../c_panel/img/ew_verticalnav_32-05.png");
            $(this).find(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
            $(this).next('div').next(".nestedMenu").slideUp(700);
        }
        else
        {
            $(this).find("#leftimgCoupans").attr("src","../c_panel/img/ew_verticalnav_32-06.png");
            $(this).next('div').next(".nestedMenu").slideDown(700);
            $(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftheadingSpan").addClass('leftclicked');
        }
    });
    $('.leftDivMailing').click(function()
    {
        $(".nestedMenu").slideUp(700);
        if ($(this).next('div').next(".nestedMenu").is(':visible'))
        {
            $(this).find("#leftimgMailing").attr("src","../c_panel/img/mailing.png");
            $(this).find(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
            $(this).next('div').next(".nestedMenu").slideUp(700);
        }
        else
        {
            $(this).find("#leftimgMailing").attr("src","../c_panel/img/mailing__mouseover.png");
            $(this).next('div').next(".nestedMenu").slideDown(700);
            $(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftheadingSpan").addClass('leftclicked');
        }
    });
    $('.leftDivAnalytics').click(function()
    {
        removerHoverfromImages();
        $(".nestedMenu").slideUp(700);
        if ($(this).next('div').next(".nestedMenu").is(':visible'))
        {
            $(this).find("#leftimgAnalytics").attr("src","../c_panel/img/ew_verticalnav_32-07.png");
            $(this).find(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
            $(this).next('div').next(".nestedMenu").slideUp(700);
        }
        else
        {
            $(this).find("#leftimgAnalytics").attr("src","../c_panel/img/ew_verticalnav_32-08.png");
            $(this).next('div').next(".nestedMenu").slideDown(700);
            $(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftheadingSpan").addClass('leftclicked');
        }
    });

    $('.leftDivReputation').click(function()
    {
        removerHoverfromImages();
        $(".nestedMenu").slideUp(700);
        if ($(this).next('div').next(".nestedMenu").is(':visible'))
        {
            $(this).find("#leftimgReputation").attr("src","../c_panel/img/reputation_1.png");
            $(this).find(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftMenuArrow").removeClass('fa-caret-left');
            $(this).next('div').next(".nestedMenu").slideUp(700);
        }
        else
        {
            $(this).find("#leftimgReputation").attr("src","../c_panel/img/reputation_rollover.png");
            $(this).next('div').next(".nestedMenu").slideDown(700);
            $(".leftheadingSpan").removeClass('leftclicked');
            $(this).find(".leftheadingSpan").addClass('leftclicked');
        }
    });
    //********************End**********************************//
    //***************Top Menu Navigation*****************
    $(".userNameLink").click(function()
    {
        $("#toggle_menu_nav").css("right","5%");
        $("#toggle_menu_nav").css("position","absolute");
        $("#toggle_menu_nav").css("top","56px");
        $("#toggle_menu_nav").slideToggle(500);
        return false;
    });

    $(".divlirest").hover(function(){

      $(this).find("#imgRest").attr("src","../c_panel/img/restaurants_mouseover.png");
      },function(){
      $(this).find("#imgRest").attr("src","../c_panel/img/restaurantsTop.png");
    });
    $(".divliSetting").hover(function(){

      $(this).find("#imgSetting").attr("src","../c_panel/img/settings_mouseover.png");
      },function(){
      $(this).find("#imgSetting").attr("src","../c_panel/img/settings.png");
    });
    $(".divliHelp").hover(function(){

      $(this).find("#imgHelp").attr("src","../c_panel/img/help_mouseover.png");
      },function(){
      $(this).find("#imgHelp").attr("src","../c_panel/img/help.png");
    });
    $(".divliLogout").hover(function(){

      $(this).find("#imglogout").attr("src","../c_panel/img/logout_mouseover.png");
      },function(){
      $(this).find("#imglogout").attr("src","../c_panel/img/logout.png");
    });

    $('.divStop').click(function()
    {
       $("#toggle_menu_nav").slideUp(500);
    });

    //*************************************************//
    $( "#item_name" ).blur(function() {

         $('#item_name').attr('placeholder','Item Name');
     });

     $( "#item_name" ).focus(function() {

         $('#item_name').attr('placeholder','');
     });

     $( "#price" ).focus(function() {

         $('#price').attr('placeholder','');
     });

     $( "#product_description" ).blur(function() {

         $('#product_description').attr('placeholder','Description of Item');
     });

     $( "#product_description" ).focus(function() {

         $('#product_description').attr('placeholder','');
     });

     $( "#txtAttName" ).blur(function() {
            $('#txtAttName').attr('placeholder','Admin Name');
     });

     $( "#txtAttName" ).focus(function() {

         $('#txtAttName').attr('placeholder','');
     });

//     $( "#txtAttTitle" ).blur(function() {
//
//         $('#txtAttTitle').attr('placeholder','Display Title (Example - "Choose Sauce")');
//     });

     $( "#txtAttTitle" ).focus(function() {

         $('#txtAttTitle').attr('placeholder','');
     });

     $( "#txtAttSubTitle" ).blur(function() {

         $('#txtAttSubTitle').attr('placeholder','Example - "Hot Sauce"');
     });

     $( "#txtAttSubTitle" ).focus(function() {

         $('#txtAttSubTitle').attr('placeholder','');
     });

     $( "#txtAttPrice" ).blur(function() {

         $('#txtAttPrice').attr('placeholder','.75');
     });

     $( "#txtAttPrice" ).focus(function() {

         $('#txtAttPrice').attr('placeholder','');
     });

     $( "#pos_id" ).blur(function() {

         $('#pos_id').attr('placeholder','POS ID');
     });

     $( "#pos_id" ).focus(function() {

         $('#pos_id').attr('placeholder','');
     });


    $('.MoveImg').darkTooltip();
    $('.submenu_edit').darkTooltip();
    $('.submenu_item_Add').darkTooltip();
    $('.submenu_item_Delete').darkTooltip();
    $('.submenu_item_Copy').darkTooltip();

    $('#imgEdit').darkTooltip();
    $('#imgCopy').darkTooltip();
    $('#imgDelete').darkTooltip();
    $('.rdb_status').darkTooltip();
	$('.rdb_statusSM').darkTooltip();
	
    $('#new1').darkTooltip();
    $('#Popular1').darkTooltip();
    $('#nut_free1').darkTooltip();
    $('#vegan1').darkTooltip();
    $('#LOWFAT1').darkTooltip();
    $('#Vegetarian1').darkTooltip();
    $('#bh_item1').darkTooltip();
    $('.spicy1').darkTooltip();
    $('#glutenfree1').darkTooltip();
    $('#imgAttDelSM').darkTooltip();
    $('#imgAttDel').darkTooltip();
    $('.fa').darkTooltip();

    $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
    $("#back-top").hide();

	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 500) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});
    $('.form-progress-wrapper').css('display','none');

    $("#product_description").val($.trim($("#product_description").val()));
    $("#description_menu").val($.trim($("#description_menu").val()));
    $("#description").val($.trim($("#description").val()));


    //if product not add hide all dropdown value in product page

    if(window.location.search.indexOf("addproduct") != -1){
        $("#typeRelatedItemName optgroup").hide();
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
//*********************Sort Attribute******************/////
  $(function() {
    $( "#attr-list_product" ).sortable();
    $( "#attr-list_product" ).disableSelection();
  });
    if($('#attr-list_product li').length==0)
    {
        $("#btnSortAttribute").hide();
    }
  $("#btnSortAttribute").click(function()
  {
      $("#hdnSortAttributes").val('');
      var prd_id = $("#prd_id").val();
      $('#attr-list_product li').each(function ()
      {
          var list = $(this).find('span');
          $("#hdnSortAttributes").val($("#hdnSortAttributes").val()+"|"+list.attr('attributeids'));
      });

      $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?updateAttributeOrder=1&prd_id="+prd_id+"&attrbutes="+$("#hdnSortAttributes").val(),
                success: function(data) {

                    if(data==1)
                    {
                        $.blockUI({ message: null });
                        noty({
                        template:$.noty.defaults.template1,
                        callback: {
                        onShow: function() {
                            $("#btnOk").click(function(){
                                $.noty.closeAll()
                                $.unblockUI();
                            });
                    },
                    afterShow: function() {$(document).ajaxStop($.blockUI({ message: null }));},
                    onClose: function() {$(document).ajaxStop($.unblockUI());},
                    afterClose: function() {}
                }
            });
                    }
            }
      });
 });
 if($('#related-list_product li').length==0)
{
    $("#btnSortComplimentry").hide();
}
 $("#btnSortComplimentry").click(function()
 {
    $("#associations_ids").val('');
    var prd_id = $("#prd_id").val();
    $('#related-list_product li').each(function ()
    {
        var list = $(this).find('.related_delete');
        $("#associations_ids").val($("#associations_ids").val()+"|"+list.attr('assoc_id'));
    });

    $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?updateComplimentryOrder=1&prd_id="+prd_id+"&associations="+$("#associations_ids").val(),
                success: function(data) {

                    if(data==1)
                    {
                        $.blockUI({ message: null });
                        noty({
                        template:$.noty.defaults.template1,
                        callback: {
                        onShow: function() {
                            $("#btnOk").click(function(){
                                $.noty.closeAll()
                                $.unblockUI();
                            });
                    },
                    afterShow: function() {$(document).ajaxStop($.blockUI({ message: null }));},
                    onClose: function() {$(document).ajaxStop($.unblockUI());},
                    afterClose: function() {}
                }
            });
                    }
            }
      });
       
 });
//*******************End*******///***************
//
//
//*********************Sort Attribute******************/////
$(function() {
    $( "#related-list_product" ).sortable();
    $( "#attr-related-list_product" ).disableSelection();
  });
//*******************End*******///***************

    $("#add_new_submenu_link").click(function(){
        $(".fancyadd_submenu").fancybox().click();

        /* hide attribute box for adding sub menu */
            $("#attr_desc").css('display','none');
            $(".hover_gray").css('display','none');
        /* ------------------------------------------------------- */

        $("#btnUpdate").hide()
        $("#btnAdd").show();
        $("#submenu_name").val('');
        $("#description").val('');
        $("#hdnCatid").val('');
        $("#Submenu_Heading").text('Add New Sub Menu');
        $("#attr_desc").css('background-color','transparent');
       
        $(".BodyContainer").css('min-height','1300px');
        $("#attr_desc").css('height','81px');
        $("#attr_desc").html('Any Attribut? Click on Attributes in your textbox at right, to add. "Click New" to add new attribute.');
        $("#submenu_name").removeClass("alert-error");

        $("#main_tab1").addClass("active");
        $("#main_tab2").removeClass("active");
        $("#main_tab1").hide();
        $("#main_tab2").hide();
        $("#main_tab3").show();
        $('.ulCategorydiv').hide();
        $("#NewAttributeLi").hide();
        //$("#ExistingAttributeLi").show();
        $('#menuinfo').show();
    });

    $("#menu_form").unbind("submit").bind('submit', function(event){

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
    var flag1 = 0;
    $('.submenu_edit').click(function(){
        $(".fancyadd_submenu").fancybox().click();

        if(flag1 ==0)
        {
            loadAllRelatedItem();
            flag1 = 1
        }
        var id = $(this).attr("id");
	var newDate = new Date();
        $("#attr_desc").css('height','15px');
        $("#attr_desc").css('background-color','#EBEBEB');
        var totalAttribute='';
        $.ajax({
            //type:"post",
            url: "admin_contents/menus/menu_ajax.php?sub_menuid="+id+"&count_attr=1&time="+newDate.getTime(),
            success: function(data) {
                $("#attr_desc").html('Attributes:'+data);
            //$("#attr_desc").css('height','15px');
            }
        });
        
        /* show attribute box for adding sub menu */
            $("#attr_desc").show();
            $(".hover_gray").show();
        /* ------------------------------------------------------- */
        $("#add_new_submenu").show();
        $("#submenu_name").removeClass("alert-error");
        $("#description").removeClass("alert-error");
        $("#submenu_name").val($(this).attr("alt"));
        $("#description").val($(this).attr("desc"));
        $("#attribut_details").text('');
        $("#hdnCatid").val($(this).attr("cat_id"));
        $("#btnUpdate").show();
        $("#btnAdd").hide();
        $("#btnSubmit").hide();
        $("#Submenu_Heading").text('Edit '+$(this).attr( "alt" ));
        $("#attr_desc").html(totalAttribute);
        $("#subcat_id").val($("#hdnCatid").val());
        $("#sub_cat_id").val($("#hdnCatid").val());
       
        //Load right Panel Attributes and related Items

        $("#main_tab1").addClass("active");
        $("#main_tab2").removeClass("active");
        $("#main_tab1").show();
        $("#main_tab2").show();
        $("#main_tab3").hide();
        $('.ulCategorydiv').show();
        $("#NewAttributeLi").show();
        //$("#ExistingAttributeLi").show();
        $('#menuinfo').hide();

        //loadAttribute($("#hdnCatid").val());
        var attribute = getattrforRightToolsBox($(this).attr("cat_id"));
        var related = getAssociationforRightToolsBox($(this).attr("cat_id"));


        related.success(function (data) {
            related = jQuery.parseJSON(data);

            // Show related Items
            $('#related-list').html('')
            var str1 = '';
            if (related != undefined || related != null ){
                var img = $('<div><span></div>');
                for (i in related) {
                        img.find('span').attr({
                            'assoc_id':i,
                            'subcatid':id
                        });
                        img.find('span').text('x');
                        img.find('span').addClass("related_delete");
                        str1+= '<li id=\'related_' + i + '\' class="liRelated"><span style="width:80%;float:left;margin-top:4px">'+related[i]+'</span>'+img.html()+'  <div style="clear:both"></div></li>';
                }
                $('#related-list').append(str1);
                if($('#related-list li').length>0)
                {
                   $('.noRelatedItems').hide();
                }
                else
                {
                   $('.noRelatedItems').show();
                }
            }
        });


        attribute.success(function (data) {
            attribute = jQuery.parseJSON(data);

            $('#attr-list').html('')
            //var attribute =item_attr[$(this).attr("id")];
            var str = '';
            var img = $('<div><span></div>');
            if (attribute != undefined || attribute != null ){
                for (var i  in attribute) {
                        img.find('span').attr({
                            'option_name':attribute[i],
                            'display_name':i
                        });

                        img.find('span').text('x');
                        img.find('span').addClass("attr_delete");
                        str += '<li id=\'attr_' + attribute[i].split(' ').join('') + '\' class="liAtribute"><a href="#dvAddAttributeSM" class="option_NameSM" style="margin-top:2px">'+attribute[i]+'</a> '+img.html()+'  <div style="clear:both"></div></li>';
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
        $("#add_new_submenu").css('margin-bottom','0');
        $(".BodyContainer").css('min-height','');
        $.fancybox.close();
    });

    //    $('.submenu_item_Add').click(function(){
    //        //$.session("catid", GetURLParameter('catid'));
    //        window.location.href = "?mod=new_menu&item=addproduct_new&scid="+$(this).attr( "alt");
    //    });

    //copy sub menu and its data
    $('.submenu_item_Copy').click(function(){
        var id =  $(this).attr( "alt" );
        var catid = '';
        var menu_id = '';
        var menu_name = '';
        var selected_menu = $(".selected").attr("href");
        var newDate = new Date();

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
        $.blockUI({ message: null });
        noty({
          template:$.noty.defaults.template,
          callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();
                        $.ajax({
                //type:"post",
                            url: "admin_contents/menus/menu_ajax.php?cat_id="+id+"&copy=1"+"&menuid="+menu_id+"&time="+newDate.getTime(),
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
                        });
                        $("#btnConfirmNo").click(function(event){
                        event.stopPropagation();
                        $.noty.closeAll()
                        $.unblockUI();
                        });
                        },

                afterShow: function() {},
                onClose: function() {},
                afterClose: function() {}
            }
        });
    });

    $('.submenu_item_Delete').click(function(){
        var id =  $(this).attr( "alt" );
		
		var mColumn = $(this).attr("column");
		var mMenuID = $(this).attr("mid");
		
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

        $.blockUI({ message: null });
        noty({
          template:$.noty.defaults.template_submenu_delete,
          callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        
                        event.stopPropagation();
                        $.ajax({
                //type:"post",
                        url: "admin_contents/menus/menu_ajax.php?cat_id="+id+"&delete_submenu=1&column="+mColumn+"&mid="+mMenuID,
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
                        });
                        $("#btnConfirmNo").click(function(event){
                        event.stopPropagation();
                        $.noty.closeAll()
                        $.unblockUI();
                        });
                        },

                afterShow: function() {},
                onClose: function() {$.unblockUI();},
                afterClose: function() {}
            }
        });


    //alert($(this).attr( "alt"));
    });


    $('.deleteItem').click(function(){
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

        $.blockUI({ message: null });

        noty({
          template:$.noty.defaults.template_item_delete,
          callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        
                        event.stopPropagation();
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
                        });
                        $("#btnConfirmNo").click(function(event){
                        event.stopPropagation();
                        $.noty.closeAll()
                        $.unblockUI();
                        });
                        },

                afterShow: function() {},
                onClose: function() {$.unblockUI();},
                afterClose: function() {}
            }
        });


    });

    $('.copyItem').click(function(){

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
        $.blockUI({ message: null });
        noty({
          template:$.noty.defaults.template,
          callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();
                        $.ajax({
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
                        });
                        $("#btnConfirmNo").click(function(event){
                        event.stopPropagation();
                        $.noty.closeAll()
                        $.unblockUI();
                        });
                        },

                afterShow: function() {},
                onClose: function() {},
                afterClose: function() {}
            }
        });

    });

    $('.editItem').click(function(){
        var scid = $(this).parents('.lItem').find('.lblCat').attr('id');
        var id =  $(this).attr( "alt" );

        window.location.href = "?mod=new_menu&item=updateproduct_new&prd_id="+id+"&sub_cat="+scid;
    });

    $('.rdb_status').click(function(){
        var milliseconds = (new Date).getTime();
        var id,status,el;
        el =$(this);
        id =  el.attr( "alt" );
        status = el.attr( "status" );
        $.blockUI({ message: null });
        noty({
          template:$.noty.defaults.template,
          callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();
                        $.ajax({
                //type:"post",
                                url: "admin_contents/menus/menu_ajax.php?prd_id="+id+"&item_deactivate=1&status="+status+"&"+milliseconds,
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
                                        el.parents('.item_data').find('.disable-menu-options').removeClass( "disable-menu-options").addClass("enable-menu-options");
                                        el.attr("title","Enable");
                                        el.attr("src","../c_panel/img/disable.png");
                                        el.attr("status","0");
                                    }
                                }
                            });
                        });
                        $("#btnConfirmNo").click(function(event){
                        event.stopPropagation();
                        $.noty.closeAll()
                        $.unblockUI();
                        });
                        },

                afterShow: function() {},
                onClose: function() {},
                afterClose: function() {}
            }
        });

    });
	
	$('.rdb_statusSM').click(function(){
        var milliseconds = (new Date).getTime();
        var id,status,el;
        el =$(this);
        id =  el.attr( "alt" );
        status = el.attr( "status" );
        $.blockUI({ message: null });
        noty({
          template:$.noty.defaults.template,
          callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();
                        $.ajax({
                //type:"post",
                                url: "admin_contents/menus/menu_ajax.php?cat_id="+id+"&submenu_deactivate=1&status="+status+"&"+milliseconds,
                                success: function(data) {
                                    if(data=='Activate')
                                    {
										$("#spn"+id).attr("style", "");
                                        el.attr("title","Disable");
                                        el.attr("src","../c_panel/img/enable_submenu.png");
                                        el.attr("status","1");
										el.attr("data-tooltip","Disabled");
                                    }
                                    else if(data=='Deactivate')
                                    {
										$("#spn"+id).attr("style", "color: #E8E8E8;");
										el.attr("title","Enable");
                                        el.attr("src","../c_panel/img/disable_submenu.png");
                                        el.attr("status","0");
										el.attr("data-tooltip","Enabled");
                                    }
                                }
                            });
                        });
                        $("#btnConfirmNo").click(function(event){
                        event.stopPropagation();
                        $.noty.closeAll()
                        $.unblockUI();
                        });
                        },

                afterShow: function() {},
                onClose: function() {},
                afterClose: function() {}
            }
        });

    });

    $("#userfile").change(function(event){
        $("#show_photo_before").css('opacity','0.5');
        $("#show_photo").css('opacity','0.5');
        $('.form-progress-wrapper').show();

        var data = new FormData();
        jQuery.each($('#userfile')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });

        var imageSize = document.getElementById('userfile').files[0].size;
        if(imageSize > 1048576)
        {

            $("#sizeErrorMsg1").css('color','red');

        }
        else{
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
						mTmpArr = data.split("~");
						mImageSrc = mTmpArr[0];
						mWidth = mTmpArr[1];
						mHeight = mTmpArr[2];
							
                        $("#show_photo").show();
                        $("#show_photo_before").hide();
                        $("#item_img").attr("src","../c_panel/tempimages/"+mImageSrc);
                        $.fancybox.close();
                        $("#deleteimg").show();
                        $("#cropimg").show();
                        $("#cropimg").css('margin-left','10px');
                        $('.form-progress-wrapper').hide();
                        $("#show_photo_before").css('opacity','1.5');
                        $("#show_photo").css('opacity','1.5');
                        if ($('#item_img').data('Jcrop') != null) {
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

                        $('.jcrop-holder img').attr("src","../c_panel/tempimages/"+mImageSrc);
                        $('#item_img').Jcrop({addClass: 'jcrop-centered',aspectRatio: 1, onSelect: updateCoords,maxSize: [ 500, 500 ]

        });

                    }
                }
            });
        }
    });
    
    $("#add_item_form").unbind("submit").bind('submit', function(e){
        e.preventDefault();
 
        var link = $("#redirectMenuPage").attr('href');
        var rest_id = $("#restaurantid").val();
        var scid = GetURLParameter('sub_cat');
        var ext = $("#item_img").attr('src').split("/").pop(-1);
        ext = ext.split("?")[0];
        if($("#prd_id").val()!="" && $("#hdnflag").val()==0)
        {
            var prd_id = $("#prd_id").val();

            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?update_menu_item=1&ext="+ext+"&prd_id="+prd_id+"&sub_catid="+scid,
                data: $("#add_item_form").serialize(),
                success: function(data) {

                    if(data!='')
                    {
                        window.location.href = link
                        return false;
                    }
                }
            });

        }
        else if($("#prd_id").val()!="" && $("#hdnflag").val()==2)
        {
            prd_id = $("#prd_id").val();

            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?update_menu_item=1&ext="+ext+"&prd_id="+prd_id+"&sub_catid="+scid,
                data: $("#add_item_form").serialize(),
                success: function(data) {

                    if(data!='')
                    {
                        console.log(data)
                        location.reload();
                        return false;
                    }
                }
            });

        }
        else if($("#prd_id").val()!="" && $("#hdnflag").val()==1)
        {
            return false;
        }
        if(!$(this).valid()){
            return false;
        }
        if($("#prd_id").val()=="")
        {
            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?add_menu_item=1&ext="+ext+"&sub_cat="+scid+"&restid="+rest_id,
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
                        if($("#hdnflag").val()==1)
                        {
                          
                        }
                        else if($("#hdnflag").val()==2)
                        {
                            location.reload();
                        }
                        else
                        {
                            window.location.href = link
                        }
                    }
                }
            });
       }
    });


    $("#cropimg").click(function()
    {
        if($("#x").val()!="" && $('#item_img').data('Jcrop')!=null)
        {
	    var ext;
            var newDate = new Date();
            ext = $("#item_img").attr('src');
            ext = ext.split("?")[0];

            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?cropimg=1&ext="+ext+"&x="+$("#x").val()+"&y="+$("#y").val()+"&w="+$("#w").val()+"&h="+$("#h").val()+"&scale="+$("#hdnScale").val()+"&time="+newDate.getTime(),
                data: $("#update_item_form").serialize(),
                success: function(data) {
               
                    if(data!='')
                    {
                        var ext = data.split("/").pop(-1);
                        if(data.indexOf('prd') < 0){
                            $("#item_img").attr("src","../c_panel/tempimages/"+ext+"?time="+newDate.getTime());
                            $(".jcrop-holder img").attr("src","../c_panel/tempimages/"+ext+"?time="+newDate.getTime());
                        }
                        else{
                        $("#item_img").attr("src","../images/item_images/"+ext+"?time="+newDate.getTime());
                        $('.jcrop-holder img').attr("src","../images/item_images/"+ext+"?time="+newDate.getTime());
                    }
                        $("#show_photo_before").css('opacity','1.5');
                        $("#show_photo").css('opacity','1.5');

                        $('#item_img').data('Jcrop').destroy();
                        //$('#item_img').css("height","70px");
                        //$('#item_img').css("width","70px");
                        $('#item_img').css("display","inline-block");
                        

                    }
                }
            });
        }
    });

    $("#update_item_form").unbind("submit").bind('submit', function(e){
        e.preventDefault();
        if(!$(this).valid()){
            return false;
        }

            var link = $("#redirectMenuPage").attr('href');
            var prd_id = $("#prd_id").val();
            var subcat_id = GetURLParameter('sub_cat');
            //var prd_id = $("#hdnProductId").val();
            var ext = $("#item_img").attr('src').split("/").pop(-1);
            ext = ext.split("?")[0];

            $("#associations_ids").val('');

            $('#related-list_product li').each(function ()
            {
                var list = $(this).find('.related_delete');
                $("#associations_ids").val($("#associations_ids").val()+"|"+list.attr('assoc_id'));
            });


            $.ajax({
                type:"POST",
                url: "admin_contents/menus/menu_ajax.php?update_menu_item=1&ext="+ext+"&prd_id="+prd_id+"&sub_catid="+subcat_id+"&associations="+$("#associations_ids").val(),
                data: $("#update_item_form").serialize(),
                success: function(data) {

                    if(data!='')
                    {
                        window.location.href = link;
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
        $("#sizeErrorMsg").css('color','green');
        $(".fancyTrigger").fancybox({

            }).click();

    //document.getElementById("#popup").style.display = 'block';
    //$(".fancyTrigger").fancybox().click();
    });

$("#upd_file_btn").die('click').live("click", function(){
        $('input[name=userfile-uploader]').click();
		$("#userfile-uploader").die('change').live("change", function(){
            var data = new FormData();
            jQuery.each($('#userfile-uploader')[0].files, function(i, file) {
                data.append('file-'+i, file);
            });
            var imageSize = document.getElementById('userfile-uploader').files[0].size;
            if(imageSize > 1048576)
            {

                $("#sizeErrorMsg").css('color','red');
            }
            else
            {
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
							mTmpArr = data.split("~");
							mImageSrc = mTmpArr[0];
							mWidth = mTmpArr[1];
							mHeight = mTmpArr[2];
							
                            $("#show_photo").show();
                            $("#show_photo_before").hide();
                            $("#item_img").attr("src","../c_panel/tempimages/"+mImageSrc);
                            $.fancybox.close();
                            $("#deleteimg").show();
                            $("#cropimg").show();
                            $("#cropimg").css('margin-left','10px');
                            $('.form-progress-wrapper').hide();
                            $("#show_photo_before").css('opacity','1.5');
                            $("#show_photo").css('opacity','1.5');

                            if ($('#item_img').data('Jcrop') != null) {
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

                            $('.jcrop-holder img').attr("src","../c_panel/tempimages/"+mImageSrc);
                            $('#item_img').Jcrop({addClass: 'jcrop-centered',aspectRatio: 1, onSelect: updateCoords,maxSize: [ 500, 500 ]});

                            $.fancybox.close();
                            $("#deleteimg").show();


                        }
                    }
                });
            }
			$("#userfile-uploader").val("");
        });
    });

    $('.lblCat').click(function(){
        $("#main_tab1").show();
        $("#main_tab2").show();
        $("#main_tab3").hide();
        $("#main_tab1").addClass("active");
        $("#main_tab2").removeClass("active");
        $("#btnSubmit").hide();

        $('.ulCategorydiv').show();
        $('#menuinfo').hide();
        $("#NewAttributeLi").show();
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
                var img = $('<div><span></div>');
                for (i in related) {
                    img.find('span').attr({
                            'assoc_id':i,
                            'subcatid':$("#sub_cat_id").val()
                        });

                        img.find('span').text('x');
                        img.find('span').addClass("related_delete");
                        str1+= '<li id=\'related_' + i + '\' class="liRelated"><span style="width:80%;float:left;margin-top:4px">'+related[i]+'</span>'+img.html()+'  <div style="clear:both"></div></li>';
                }
                $('#related-list').append(str1);
                if($('#related-list li').length>0)
                {
                   $('.noRelatedItems').hide();
                }
                else
                {
                   $('.noRelatedItems').show();
                }
            }
        });



        var attribute = getattrforRightToolsBox($("#sub_cat_id").val());

        attribute.success(function (data) {
            attribute = jQuery.parseJSON(data);

            $('#attr-list').html('')
            //var attribute =item_attr[$(this).attr("id")];
            var str = '';
            var img = $('<div><span></div>');

            if (attribute != undefined || attribute != null ){
                for (var i  in attribute) {
                   img.find('span').attr({
                            'option_name':attribute[i],
                            'display_name':i
                        });
                        img.find('span').text('x');
                        img.find('span').addClass("attr_delete");
                        str += '<li id=\'attr_' + attribute[i].split(' ').join('') + '\' class="liAtribute"><a href="#dvAddAttributeSM" class="option_NameSM" style="margin-top:2px;">'+attribute[i]+'</a> '+img.html()+'  <div style="clear:both"></div></li>';
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
        var attributeids = $(this).attr("attributeids");
        var prd_id = $("#prd_id").val();
        var subcat_id = $("#hdnCatid").val();


        if(!isNaN(prd_id)){
          $.blockUI({ message: null });
          noty({
          template:$.noty.defaults.template_attribute_delete,
          callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();
                        $.ajax({
                            type:"POST",
// ------------------------------------Start NK--------------------------------------------------------------                             
                            url: "admin_contents/menus/menu_ajax.php?remove_attributes=1&option_name="+option_name+"&prd_id="+prd_id+"&display_name="+display_name+"&subcatid="+$("#hdn_subcatid").val()+"&attributeids="+attributeids,
// ------------------------------------End NK----------------------------------------------------------------                             
                            success: function(data) {

                                if(data>0)
                                {
                                   var attr_name = "attr_"+(option_name).replace(/ /g,'');
                                   var atrtObject = document.getElementById(attr_name);
                                   $(atrtObject).remove();
                                   $("#addAttributetxt").append("<option value='"+display_name+"'>"+option_name+"</option>");
                                    if($('#attr-list_product li').length==0)
                                    {
                                        $('.noAttributesForProduct').show();
                                        $("#btnSortAttribute").hide();
                                    }
                                }
                            }
                        });
                        });
                        $("#btnConfirmNo").click(function(event){
                        event.stopPropagation();
                        $.noty.closeAll()
                        $.unblockUI();
                        });
                        },

                afterShow: function() {},
                onClose: function() {$.unblockUI();},
                afterClose: function() {}
            }
        });

        }
        else{

                var count_attr = parseInt($("#attr_desc").html().split(":").pop());
                $.blockUI({ message: null });
                noty({
                template:$.noty.defaults.template_attribute_delete,
                callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();
                        $.ajax({
                                type:"POST",
                                url: "admin_contents/menus/menu_ajax.php?remove_attributesfromCategory=1&option_name="+option_name+"&sub_catid="+subcat_id+"&display_name="+display_name,
                                success: function(data) {

                                    if(data>0)
                                    {
                                        var attr_name = "attr_"+(option_name).replace(/ /g,'');
                                        var atrtObject = document.getElementById(attr_name);
                                        $(atrtObject).remove();
                                        count_attr = count_attr-1;
                                        $("#attr_desc").html('Attributes:'+count_attr);

                                        if($('#attr-list li').length==0)
                                        {
                                            $('.noAttributes').show();
                                        }
                                    }
                                }
                            });
                        });
                        $("#btnConfirmNo").click(function(event){
                        event.stopPropagation();
                        $.noty.closeAll()
                        $.unblockUI();
                        });
                        },

                afterShow: function() {},
                onClose: function() {$.unblockUI();},
                afterClose: function() {}
            }
        });

       }
    });


    // Delete related Items
    $('.related_delete').live("click", function(){

        var related_id = $(this).attr("assoc_id");
        var prd_id = $(this).attr("prd_id");
        var subcatid = $("#hdnCatid").val();
        //alert(related_id);


        if(!isNaN(prd_id)){
            $.blockUI({ message: null });
            
            noty({
                template:$.noty.defaults.template_assoc_delete,
                callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();
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
                                        $("#btnSortComplimentry").hide();

                                    }
                                }
                            }
                        });

                        });
                        $("#btnConfirmNo").click(function(event){
                        event.stopPropagation();
                        $.noty.closeAll()
                        $.unblockUI();
                        });
                        },

                afterShow: function() {},
                onClose: function() {$.unblockUI();},
                afterClose: function() {}
            }
        });


        }else{
            $.blockUI({ message: null });
            noty({
                template:$.noty.defaults.template_assoc_delete,
                callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();

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

                        });
                        $("#btnConfirmNo").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();

                        });
                        },

                afterShow: function() {},
                onClose: function() {$.unblockUI();},
                afterClose: function() {}
            }
        });

     }
    });

    $("#closeRelatedItemDiv").click(function()
    {
       $("#mainFancyBox").slideUp(500);
    });

    // Add related Item on Tool Box
    $("#addRelatedIteminProduct").click(function(){
        //var prd_id = GetURLParameter('prd_id');
        var applyToAll = 0;
        if($('#applyToAll').is(':checked'))
        {
            applyToAll = 1;
        }
        $("#displayMessage").hide();
        $("#btnSortComplimentry").show();
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
                url: "admin_contents/menus/menu_ajax.php?addRelatedItem=1&assoc_id="+assoc_id+"&prd_id="+prd_id+"&applyToall="+applyToAll+"&scid="+$("#hdn_subcatid").val(),
                success: function(data) {

                    if(data>0)
                    {
                        var img = $('<div><span></div>'); //Equivalent: $(document.createElement('img'))
                        img.find('span').attr({
                            'prd_id':+prd_id,
                            'assoc_id':assoc_id
                        });

                        img.find('span').addClass("related_delete");
                        img.find('span').text('x');
                        $('#related_'+assoc_id).remove();
                        $("#related-list_product").append('<li id=\'related_' + assoc_id + '\' class="liRelated"><span style="width:80%;float:left;margin-top:4px">'+ItemName+'</span>'+img.html()+'  <div style="clear:both"></div></li>');
			$("#typeRelatedItemName").val('');
                        $("#typeRelatedItemName").trigger('liszt:updated');
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
                        var img = $('<div><span></div>');
                        img.find('span').attr({
                            'assoc_id':assoc_id,
                            'subcatid':+subcatid
                        });

                        img.find('span').addClass("related_delete");
                        img.find('span').text('x');
                        $("#related-list").append('<li id=\'related_' + assoc_id + '\' class="liRelated"><span style="width:80%;float:left;margin-top:4px">'+ItemName+'</span>'+img.html()+'  <div style="clear:both"></div></li>');
                        $.fancybox.close();
			$("#typeRelatedItemName").val('');
                        $("#typeRelatedItemName").trigger('liszt:updated');
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
            $("#checkboxBind").append("<div style='text-align:center;text-align: center;background-color: #25aae1;color: white;padding: 10px;border-radius: 8px;width: 400px;margin: 0 auto;border: 5px solid #e5e5e5;font-size: 20px;'><span style=''>Please add item first</span></div>");
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
                        $("#checkboxBind").append("<div style='text-align:center;text-align: center;background-color: #25aae1;color: white;padding: 10px;border-radius: 8px;width: 400px;margin: 0 auto;border: 5px solid #e5e5e5;font-size: 20px;'><span style=''>No Complementary Item Saved</span></div>");
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
                        $("#checkboxBind").append("<div style='text-align:center;text-align: center;background-color: #25aae1;color: white;padding: 10px;border-radius: 8px;width: 400px;margin: 0 auto;border: 5px solid #e5e5e5;font-size: 20px;'><span style=''>No Complementary Item Saved</span></div>");
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
            $("#checkboxBindAttribute").append("<div style='text-align:center;text-align: center;background-color: #25aae1;color: white;padding: 10px;border-radius: 8px;width: 400px;margin: 0 auto;border: 5px solid #e5e5e5;font-size: 20px;'><span style=''>Please add Item first</span></div>");
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
                        $("#checkboxBindAttribute").append("<div style='text-align:center;text-align: center;background-color: #25aae1;color: white;padding: 10px;border-radius: 8px;width: 400px;margin: 0 auto;border: 5px solid #e5e5e5;font-size: 20px;'><span style=''>No Attribute saved for this category</span></div>");
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
                        $("#checkboxBindAttribute").append("<div style='text-align:center;text-align: center;background-color: #25aae1;color: white;padding: 10px;border-radius: 8px;width: 400px;margin: 0 auto;border: 5px solid #e5e5e5;font-size: 20px;'><span style=''>No Attribute saved for this category</span></div>");
                    }
                }
            });
        }
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

                    if(data!='')
                    {
                        var img = $('<div><span></div>');
                        img.find('span').attr({
                            'option_name':option_Name,
                            'display_name':display_Name,
                            'attributeIds':data
                        });

                        img.find('span').text('x');
                        img.find('span').addClass("attr_delete");
                        $("#attr-list_product").append('<li id=\'attr_' + option_Name.split(' ').join('') + '\' class="liAtribute"><a href="#dvAddAttribute" class="option_Name" style="margin-top:2px">'+option_Name+'</a> '+img.html()+'  <div style="clear:both"></div></li>');
                        $.fancybox.close();
                        $('.noAttributesForProduct').hide();
                        $("#btnSortAttribute").show();
                        $("#addAttributetxt option[value='"+display_Name+"']").remove();
                        $("#addAttributetxt").trigger('liszt:updated');
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
                        var attr_name = "attr_"+(option_Name).replace(/ /g,'');
                        var atrtObject = document.getElementById(attr_name);
                        $(atrtObject).remove();
                        
                        var img = $('<div><span></div>');
                        img.find('span').attr({
                            'option_name':option_Name,
                            'display_name':display_Name
                        });

                        img.find('span').addClass("attr_delete");
                        $("#attr-lis").append('<li id=\'attr_' + option_Name.split(' ').join('') + '\' class="liAtribute"><a href="#dvAddAttribute" class="option_Name" >'+option_Name+'</a> '+img.html()+'  <div style="clear:both"></div></li>');
                        $.fancybox.close();
                        count_attr = count_attr+1;
                        $("#attr_desc").html('Attributes:'+count_attr);
                        $('.noAttributes').hide();
                    }
                }
            });
        }
    });

    

    


    // Remove Photo
    $('#deleteimg').click(function(){
        $("#show_photo").hide();
        $("#show_photo_before").show();
        $("#item_img").attr("src","");
        $('#deleteimg').hide();
        $("#cropimg").hide();
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
        $(".fancymainmenu_form").fancybox().click();
        $(this).parents('#menu_nav').find('.selected').removeClass("selected").addClass('previous menu_links');
        $(this).removeClass("menu_links").addClass('selected');
        $("#txt_menuname").val('');
        $("#txt_menudescription").val('');
    });

    $("#cancel_click_menu").click(function(){
        $("#popup_boxMenu").css('display','none');
        $('#menu_nav').find('.previous').removeClass("previous menu_links ").addClass('selected');
        $('#add_mainmenu').removeClass("selected").addClass('menu_links');
        $("#txt_menuname").removeClass("alert-error")
        $.fancybox.close();
        //$("#menu_shuffle_form").show();
        


    });

    $("#mainmenu_form").unbind("submit").bind('submit', function(event){
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

// hover cross hair icon
    $(".MoveImg").hover(function(){
      $(this).css("border-color","#69C6EA");
      $(this).parents('.lItem').css("border","3px solid #25aae1");
      $(this).parents('.lItem').find(".menu_operations").css("border","1px solid #25aae1");
      $(this).parents('.lItem').find(".submenu_edit").css("border","1px solid #25aae1");
      $(this).parents('.lItem').find(".submenu_item_Copy").css("border","1px solid #25aae1");
      $(this).parents('.lItem').find(".submenu_item_Add").css("border","1px solid #25aae1");
      $(this).parents('.lItem').find(".submenu_item_Delete").css("border","1px solid #25aae1");
      },function(){
      $(this).css("border-color","#C4C4C4");
      $(this).parents('.lItem').css("border","none");
      $(this).parents('.lItem').find(".menu_operations").css("border","none");
      $(this).parents('.lItem').find(".submenu_edit").css("border","1px solid #b9b9b9");
      $(this).parents('.lItem').find(".submenu_item_Copy").css("border","1px solid #b9b9b9");
      $(this).parents('.lItem').find(".submenu_item_Add").css("border","1px solid #b9b9b9");
      $(this).parents('.lItem').find(".submenu_item_Delete").css("border","1px solid #b9b9b9");
    });

    $(".imgMove").hover(function(){
      $(this).css("border-color","#69C6EA");
      $(this).parents('.item_data').css("border","2px solid #25aae1");
      $(this).parents('.tblProduct').css("margin-bottom","0px");
      $(this).parents('.tblProduct').find('.dvOptions').css("border","2px solid #25aae1");
      },function(){
      $(this).css("border-color","#C4C4C4");
      $(this).parents('.item_data').css("border","none");
      $(this).parents('.tblProduct').css("margin-bottom","7px");
      $(this).parents('.tblProduct').find('.dvOptions').css("border","none");
    });

    //Menu Collapse and Expand
    $(".fa-plus-square").live("click",function()
    {
       $(this).removeClass('fa-plus-square');
       $(this).addClass('fa-minus');
       $(this).parents("div").parents(".menu_heading_top").next(".toggleSubmenu").slideDown(700);
       $(this).removeAttr("data-tooltip");
       $(this).attr('data-tooltip', 'Collapse Menu');
       $('.fa').darkTooltip();
       //$(this).closest('.toggleSubmenu').slideToggle(500);
    });

    $(".fa-minus").live("click",function()
    {
       $(this).removeClass('fa-minus');
       $(this).addClass('fa-plus-square');
       $(this).parents("div").parents(".menu_heading_top").next(".toggleSubmenu").slideUp(700);
       $(this).removeAttr("data-tooltip");
       $(this).attr('data-tooltip', 'Expand Menu');
       $('.fa').darkTooltip();
    });


    $("#price").keydown(function (e)
    {

        if ($.inArray(e.keyCode, [173, 46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                (e.keyCode == 65 && e.ctrlKey === true) ||
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                         return;
        }
        if (e.keyCode != 173)
        {
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105))
                {
                        e.preventDefault();
                }
        }
    });

    var menu_id = GetURLParameter('menuid');
    if(isNaN(menu_id))
    {
        $("#btnDeleteMenu").hide();
    }
    
    $("#btnDeleteMenu").click(function(event)
    {
        var value = $("#allowDelete").val();
        if(value==0){
        event.preventDefault();
         
         $.blockUI({message: null});
            noty({
                template:$.noty.defaults.template_menu_delete,
                callback: {
                onShow: function() {
                        $("#btnConfirmYes").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();
                        $("#allowDelete").val(1);
                        $("#btnDeleteMenu").trigger("click");
                        });
                        $("#btnConfirmNo").click(function(event){
                        $.noty.closeAll()
                        event.stopPropagation();
                        $.unblockUI();

                        });
                        },

                afterShow: function() {},
                onClose: function() {$.unblockUI();},
                afterClose: function() {}
            }
        });
        }
    });
}

$(document).ready(DOMReady);


function parseIDs()
{
    var mColumn1Count = $("#sortable1 .lItem").length;
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
    $("#lblColumn1").val(mColumn1Count);
}

// fill auto complete text box
        function loadAllRelatedItem() {
            var rest_id = $("#restaurantid").val();
            var prd_id = $('#prd_id').val();
            var item;

            $.ajax({
                 type:"POST",
                url: "admin_contents/menus/menu_ajax.php?loadRelatedDropdown=1&rest_id="+rest_id,
                success: function(data) {

                    if(data!='')
                    {
                        var relatedItem = jQuery.parseJSON(data);
                        for(var i in relatedItem)
                        {
                            //$("#typeRelatedItemName").append('<optgroup label="'+relatedItem[i].cat_name+'"  />');
                            item = relatedItem[i].products;
                            
                            if(typeof(item)!='undefined')
                            {
                                $("#typeRelatedItemName").append($("<option disabled style='color: #3078D5;border: 1px solid gray;'></option>").html(relatedItem[i].cat_name));
                            }
                            for(var j in item)
                            {
                                $("#typeRelatedItemName").append($("<option></option>").val(item[j].prd_id).html(item[j].item_title));
                            }
                        }
                        if(typeof(prd_id)!='undefined')
                        {
                            $("#typeRelatedItemName option[value='"+prd_id+"']").remove();
                        }
                        $("#typeRelatedItemName").trigger('liszt:updated');
                    }
                }
            });
        }

        // load addl Attribure for AutoComplete textbox
    function loadAttribute() {
        var sub_catid = $("#hdn_subcatid").val();
        var prd_id = $("#prd_id").val();

        $('#addAttributetxt').empty();
        $("#addAttributetxt").append($("<option></option>").val('').html('--Please Select--'));

        $.ajax({
            type:"POST",
            url: "admin_contents/menus/menu_ajax.php?GetallAttributeOptionName=1&sub_catid="+sub_catid+"&prd_id="+prd_id,
            success: function(data) {

                if(data!='')
                {

                    var attribute = jQuery.parseJSON(data);
                    for(var i in attribute){
                        $("#addAttributetxt").append($("<option></option>").val(attribute[i]).html(i));
                    }
                    $("#addAttributetxt").trigger('liszt:updated');

                }
            }
        });
    }