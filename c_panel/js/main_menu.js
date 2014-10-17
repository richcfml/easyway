
$(document).ready(function() {
    
    var $radios = $('input:radio[name=chkLimitExceedSM]');
                                            
    if($radios.is(':checked') === false) 
    {
        $radios.filter('[value=0]').prop('checked', true);
    }                                       
    
	window.onload = function () 
	{
		$(".myDiv").hide();    
	};
	
        $(".tab_content").hide();
        $(".tab_content:first").show();

        $("ul.tabs li").click(function() {
            $("ul.tabs li").removeClass("active");
            $(this).addClass("active");
            $(".tab_content").hide();
            var activeTab = $(this).attr("rel");
            $("#"+activeTab).fadeIn();
        });


        $("#menu_form").validate({
            rules: {
                submenu_name: {required: true }

            },
            messages: {
                submenu_name: {
                    required: "please enter your email address",
                    email: "please enter a valid email address"
                },
                description: {
                    required: "please enter your password",
                    minlength: "your enter a valid pa ssword"
                }
            },
            errorElement: "br",
            errorClass: "alert-error"
        });


        $("#mainmenu_form").validate({
            rules: {
                txt_menuname: {required: true}
            },
            messages: {
                txt_menuname: {
                    required: "please enter your menu name",
                    email: "please enter a valid menu name"
                }
            },
            errorElement: "br",
            errorClass: "alert-error"
        });

        $("#menu_shuffle_form").validate({
            rules: {
                menuname: {required: true}

            },
            messages: {
                menuname: {
                    required: "please enter your menu name",
                    email: "please enter a valid menu name"
                }
            },
            errorElement: "br",
            errorClass: "alert-error"
        });

	$("#RelatedItemLi").click(function()
        {
            $("#mainFancyBox").slideDown(500);
        });

        $("#txtAttPriceSM").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
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
    $("#menu_ordering").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
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
    //All Alpha Numeric and space
    $("#txtAttNameSM,#txtAttTitleSM,#txtAttSubTitleSM").keypress(function(e) {
               var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
               var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (keyCode == 32 ) || (keyCode == 8 ));
               return ret;
   });

   

    $("#txtLimitExceedSM").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
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

    //load mneu fancy box
    $('.loadMenuHours').fancybox({'height' : '442px',type:'iframe'})

    // Empty Attribute fancyBox fields
    $("#btnAddAttributeSM").fancybox({
      afterClose: function() {
          emptyFancyBoxFieldsSM();
      }
    });

    //Attribute fancy Box Jquery
    //Show Hide Extra charge textbox
    $("input[name='chkLimitExceedSM']").change(function ()
    {
        var value = this.value;
        if(value == 1)
        {
            $("#txtLimitExceedSM").show();
            $("#attr_limitSM").show();

        }
        else
        {
            $("#txtLimitExceedSM").hide();
            $("#spnLimitExceedSM").css("visibility", "hidden");
            
            $("#attr_limitSM").hide();
            $("#spnChooseLimitSM").css("visibility", "hidden");
        }
    });


    $("#attr_chooseSM").change(function()
    {
		makeStringSM();
		$("#txtAttTitleSM").focus();
                if($("#attr_chooseSM").val()=="4")
                {
//                    $("#txtAttTitleSM").width(320);
                    $('#txtAttTitleSM').attr('placeholder','Type your message here');

                }
                else
                {
//                    $("#txtAttTitleSM").width(255);
                    $('#txtAttTitleSM').attr('placeholder','Display Title (Example - "Choose Sauce")');
                }
    });

    $("#txtAttTitleSM").focusout(function()
    {
        makeStringSM();
    });

    $("#attr_limitSM").change(function()
    {
        makeStringSM();
    });


    $("#btnAddAttrSM").die('click').live('click', function()
    {
			$("#spnDupSM").hide();
            if (($.trim($("#txtAttSubTitleSM").val())=="") || ($.trim($("#txtAttSubTitleSM").val())=='Example - "Hot Sause"'))
            {
                    $("#spnTitleReqSM").css("visibility", "visible");
                    $("#txtAttSubTitleSM").focus();
                    return;
            }
            else
            {
                    $("#spnTitleReqSM").css("visibility", "hidden");
            }
			
			if ($.trim($("#hdnOptionsSM").val()).toLowerCase().indexOf("|"+$.trim($("#txtAttSubTitleSM").val()).toLowerCase()+"~")>=0)
			{
				$("#spnDupSM").show();
				$("#txtAttSubTitleSM").focus();
				return;
			}
			else
			{
				$("#spnDupSM").hide();
			}

            var mPrice = "NA";
            if (($.trim($("#txtAttPriceSM").val())!="") && ($.trim($("#txtAttPriceSM").val()).replace(/-/g, "")!=""))
            {
                    
                mPrice = Number($("#txtAttPriceSM").val()).toFixed(2);
                   
            }

            var mDefault = "No";
            if ($("#chkAttDefSM").is(":checked"))
            {
                    mDefault = "Yes";
            }

            $("#hdnOptionsSM").val($("#hdnOptionsSM").val()+"|"+$("#txtAttSubTitleSM").val()+"~"+mPrice+"~"+mDefault);
            $("#tblOptionsSM tr:last").after('<tr id="tr'+$("#txtAttSubTitleSM").val().replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"><td style="width: 5%;"></td><td style="width: 50%;" align="left">'+$("#txtAttSubTitleSM").val()+'</td><td style="width: 15%;" align="left">'+mPrice+'</td><td style="width: 15%;" align="left">'+mDefault+'</td><td style="width: 10%;"><img onclick=editAttributeOptionSM("tr'+$("#txtAttSubTitleSM").val().replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"); class="attEdit" src="img/pencil.png" alt="Edit" style="width: 17px;cursor:pointer" data-tooltip="Edit" name="imgAttEditSM" id="imgAttEditSM"/>&nbsp;<img onclick=deleteOptionSM("tr'+$("#txtAttSubTitleSM").val().replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"); class="attRem" src="img/delete_icon2.png" alt="Delete" style="width: 17px;cursor:pointer" data-tooltip="Delete" name="imgAttDelSM" id="imgAttDelSM"/></td><td style="width: 5%;"></td></tr><tr id="2tr'+$("#txtAttSubTitleSM").val().replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'" style="height: 5px;"><td colspan="6"></td></tr>');
            $("#chkAttDefSM").attr("checked", false);
            $("#txtAttSubTitleSM").val("");
            $("#txtAttPriceSM").val("");

            
    });

    $('.option_NameSM').fancybox({
          afterClose: function() {
              emptyFancyBoxFieldsSM();
          }
   });

//**************Text Boxes focus***************//
    $( "#submenu_name" ).blur(function() {

     $('#submenu_name').attr('placeholder','Name');
    });

    $( "#submenu_name" ).focus(function() {

     $('#submenu_name').attr('placeholder','');
    });

    $( "#description" ).blur(function() {

     $('#description').attr('placeholder','Description of Category');
    });

    $( "#description" ).focus(function() {

     $('#description').attr('placeholder','');
    });

    $( "#txt_menuname" ).blur(function() {

     $('#txt_menuname').attr('placeholder','Menu Name');
    });

    $( "#txt_menuname" ).focus(function() {

     $('#txt_menuname').attr('placeholder','');
    });

    $( "#txt_menudescription" ).blur(function() {

     $('#txt_menudescription').attr('placeholder','Menu Description');
    });

    $( "#txt_menudescription" ).focus(function() {

     $('#txt_menudescription').attr('placeholder','');
    });

    $( "#menuname" ).blur(function() {

     $('#menuname').attr('placeholder','Menu Name');
    });

    $( "#menuname" ).focus(function() {

     $('#menuname').attr('placeholder','');
    });

    $( "#description_menu" ).blur(function() {

     $('#description_menu').attr('placeholder','Menu Description');
    });

    $( "#description_menu" ).focus(function() {

     $('#description_menu').attr('placeholder','');
    });
	
	function makeStringSM()
	{
		var mStr = "";
		if ($("#attr_chooseSM").val() == 4)
		{
			mStr = $("#txtAttTitleSM").val();
			if ($("#attr_limitSM").val()!="")
			{
				mStr = mStr+" (up to "+$("#attr_limitSM option:selected").text()+")";
			}
                        $('#txtAttTitleSM').attr('placeholder','Type your message here');
		}
		else if ($("#attr_chooseSM").val() != "")
		{
			mStr = $("#attr_chooseSM option:selected").text(); 
			if ($("#attr_limitSM").val()!="")
			{
				mStr = mStr+" up to "+$("#attr_limitSM option:selected").text()+' '+$("#txtAttTitleSM").val();
			}
			else
			{
				mStr = mStr+" "+$("#txtAttTitleSM").val();
			}
                        $('#txtAttTitleSM').attr('placeholder','Display Title (Example - "Choose Sauce")');
		}
		else
		{
			mStr = "";
			if ($("#attr_limitSM").val()!="")
			{
				mStr = mStr+" up to "+$("#attr_limitSM option:selected").text()+' '+$("#txtAttTitleSM").val();
			}
			else
			{
				mStr = mStr+" "+$("#txtAttTitleSM").val();
			}
		}
		
		$("#span_attr_nameSM").text(mStr);
	}

    $("#btnSaveSM").die('click').live('click', function()
    {
			$("#spnDupAttSM").hide();
            if ($("#attr_chooseSM").val()=="")
            {
                    $("#spnChooseAttrSM").css("visibility", "visible");
                    $("#attr_chooseSM").focus();
                    return;
            }

            if ($("#txtAttNameSM").val()=="")
            {
                    $("#spnNameReqSM").css("visibility", "visible");
                    $("#txtAttNameSM").focus();
                    return;
            }

            $("#spnNameReqSM").css("visibility", "hidden");

            if ($("#txtAttTitleSM").val()=="")
            {
                    $("#spnAttTitleReqSM").css("visibility", "visible");
                    $("#txtAttTitleSM").focus();
                    return;
            }
            $("#spnAttTitleReqSM").css("visibility", "hidden");

            //var mSubCatID = $("#hdnSubmenuIDSM").val();
            var mSubCatID= $("#hdnCatid").val();
            if(isNaN(mSubCatID))
           {
               subcatid = $("#hdn_subcatid").val();
           }
            var mName = $("#span_attr_nameSM").text();
            var mOptionName = $("#txtAttTitleSM").val();
            var mAttrFields = $("#attr_chooseSM").val()+'~'+$("#txtAttNameSM").val()+'~'+$("#attr_limitSM").val();
            var add_to_price = 1;
            var ajaxUrl;
            var mExtraCharger;
            if($('input[name=chkLimitExceedSM]:radio:checked').val() == 1 )
            {
                if($("#attr_limitSM").val()=='')
                {
                    $("#spnChooseLimitSM").css("visibility", "visible");
                    $("#attr_limitSM").focus();
                    return;
                }
                 mExtraCharger = $("#txtLimitExceedSM").val();
                 if(mExtraCharger=='' ||mExtraCharger==0)
                 {
                    $("#spnLimitExceedSM").css("visibility", "visible");
                    $("#txtLimitExceedSM").focus();
                    return;
                 }
            }
            else
            {
                mExtraCharger =0;
            }

            var mLayout = 1;

            if ($("#rbAttDDSM").is(":checked"))
            {
                    mLayout = 1;
            }
            else if ($("#rbAttCBSM").is(":checked"))
            {
                    mLayout = 2;
            }
            else if ($("#rbAttRBSM").is(":checked"))
            {
                    mLayout = 3;
            }

            if ($("#chkAttAddSM").is(":checked"))
            {
                    add_to_price = 1;
            }
            else if ($("#chkAttTotalSM").is(":checked"))
            {
                    add_to_price = 2;
            }

            var mApplySubCat = 0;
            if ($("#chkAttEntireSM").is(":checked"))
            {
                    mApplySubCat = 1;
            }

            var mRequired = 0;
            if ($("#chkAttReqSM").is(":checked"))
            {
                    mRequired = 1;
            }

            mOptionsString = $("#hdnOptionsSM").val();
            var milliseconds = (new Date).getTime();
            if($("#hdnAttributeUpdateSet1").val()=="")
            {
               ajaxUrl =  "admin_contents/menus/menu_ajax.php?AddNewAttributeInCategory=1&SubCatID="+mSubCatID+"&Name="+mName+"&OptionName="+mOptionName+"&Layout="+mLayout+"&ApplySubCat="+mApplySubCat+"&Required="+mRequired+"&Title_Price_Defalt="+mOptionsString+"&add_to_price="+add_to_price+"&attrFields="+mAttrFields+"&extraCharger="+mExtraCharger+"&"+milliseconds;
            }
            else if($("#hdnAttributeUpdateSet1").val()!="")
            {
               ajaxUrl =  "admin_contents/menus/menu_ajax.php?UpdateAttributeInCategory=1&SubCatID="+mSubCatID+"&Name="+mName+"&OptionName="+mOptionName+"&Layout="+mLayout+"&ApplySubCat="+mApplySubCat+"&Required="+mRequired+"&Title_Price_Defalt="+mOptionsString+"&oldOptionName="+$("#hdnAttributeUpdateSet1").val()+"&add_to_price="+add_to_price+"&attrFields="+mAttrFields+"&extraCharger="+mExtraCharger+"&"+milliseconds;

            }

            $.ajax({
                    url: ajaxUrl,
                    success: function(data)
                    {
                            if (isNumber(data))
                                    {
                                            if ($("#hdnAttributesSM").val().indexOf("~"+$("#txtAttTitleSM").val())<0)
                                            {
                                                    if ($.trim($("#hdnAttributesSM").val())=="")
                                                    {
                                                            $("#hdnAttributesSM").val(data+"~"+$("#txtAttTitleSM").val()+"~"+$("#txtAttName").val());
                                                    }
                                                    else
                                                    {
                                                            $("#hdnAttributesSM").val($("#hdnAttributesSM").val()+"|"+data+"~"+$("#txtAttTitleSM").val()+"~"+$("#txtAttName").val());
                                                    }

                                                    $("#dvAttributes").html("");
                                                    mAttributeList = $("#hdnAttributesSM").val().split("|");
                                                    console.log(mAttributeList);
                                                    mAttributeList.forEach(function(mAttributeListRow)
                                                    {
                                                            mAttributeData = mAttributeListRow.split("~");
                                                            mAttributeID = mAttributeData[0];
                                                            mAttributeName = mAttributeData[1];
                                                            mAttributeN = mAttributeData[2];

                                                            var img = $('<div><span></div>'); //Equivalent: $(document.createElement('img'))
                                                            img.find('span').attr({
                                                                'option_name':mAttributeName,
                                                                'display_name':mName
                                                            });
                                                            img.find('span').addClass("attr_delete");
                                                            img.find('span').text('x');
                                                            var attr_name = "attr_"+(document.getElementById('hdnAttributeUpdateSet1').value).replace(/ /g,'');
                                                            var atrtObject = document.getElementById(attr_name);
                                                            $(atrtObject).remove();
                                                            $("#attr-list").append('<li id=\'attr_' + mAttributeName.split(' ').join('') + '\' class="liAtribute"><a href="#dvAddAttributeSM" class="option_NameSM" style="margin-top:2px">'+mAttributeName+'</a> '+img.html()+'  <div style="clear:both"></div></li>');

                                                            $("#addAttributetxt").append($("<option></option>").val(mAttributeN).html(mAttributeName));
                                                            $("#addAttributetxt").trigger('liszt:updated');

                                                            if($("#hdnAttributeUpdateSet1").val()=="")
                                                            {
                                                            var count_attr = parseInt($("#attr_desc").html().split(":").pop());
                                                            count_attr = count_attr+1;
                                                            $("#attr_desc").html('Attributes:'+count_attr);
                                                            }

                                                            $('.noAttributes').hide();
                                                            $('.option_NameSM').fancybox({
                                                                  afterClose: function() {
                                                                      emptyFancyBoxFieldsSM();
                                                                  }
                                                                });

                                                    });
                                            }


                                            $("#dvNoAttSM").hide();
											$("#spnDupAttSM").hide();
											$("#spnDupSM").hide();
                                            $.fancybox.close();
                                            emptyFancyBoxFieldsSM();
                                    }
                                    else
                                    {
										if (data=="duplicate")
										{
											$("#spnDupAttSM").show();
											$("#txtAttTitleSM").focus();
										}
                                    }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                            alert(jqXHR.status);
                            alert(textStatus);
                    }
            });
    });

//Get Attribute data for Edit
	$(".option_NameSM").die('click').live('click', function() 
    {


       var option_Name = $(this).text();
       var subcatid = $("#hdnCatid").val();
       $("#hdnAttributeUpdateSet1").val(option_Name);

       if(isNaN(subcatid))
       {
           subcatid = $("#hdn_subcatid").val();
       }

       $.ajax
       ({
            type:"POST",
            url: "admin_contents/menus/menu_ajax.php?GetCategoryAttrbutedataforEdit=1&sub_catid="+subcatid+"&option_name="+option_Name,
            success: function(data) {

                if(data!='')
                {
                    var AttrData = jQuery.parseJSON(data);

                    var hdnOptions='';

                    $("#tblOptionsSM tr:gt(0)").remove();
                    for(var i in AttrData)
                    {
                        $("#hdnNameSM").val(AttrData[i]['display_Name'].replace("&#39;", "'"));
                        $("#txtAttTitleSM").val(AttrData[i]['option_name'].replace("&#39;", "'"));
                        $("#TopHeadingSM").html('Edit '+AttrData[i]['option_name'].replace("&#39;", "'"));
                        var type = AttrData[i]['Type'];
                        var add_to_price = AttrData[i]['add_to_price'];

                        if(AttrData[i]['attr_name']!= null)
                        {
                            mAttributeArray = AttrData[i]['attr_name'].replace("&#39;", "'").split("~");
                            $("#txtAttNameSM").val(mAttributeArray[1]);
                            $("#attr_chooseSM").val(mAttributeArray[0]);
                            if($("#attr_chooseSM").val()=="4")
                            {
//                                $("#txtAttTitleSM").width(320);
                            }
                            else
                            {
//                                $("#txtAttTitleSM").width(255);
                            }
                            $("#attr_limitSM").val(mAttributeArray[2]);
                            if(AttrData[i]['extra_charge']!=''&& AttrData[i]['extra_charge']!='0')
                            {
                                $("#txtLimitExceedSM").val(AttrData[i]['extra_charge']);
                                $("#chkLimitExceedYesSM").attr('checked', 'checked');
                                $("#txtLimitExceedSM").show();
                                $("#attr_limitSM").show();
                            }
                            else
                            {
                                $("#chkLimitExceedNoSM").attr('checked', 'checked');
                                $("#txtLimitExceedSM").hide();
                                $("#attr_limitSM").hide();
                            }
                        }
                        else
                        {
                            $("#txtAttNameSM").val(AttrData[i]['display_Name'].replace("&#39;", "'"));
                        }

                        if(AttrData[i]['Required']== "1")
                        {
                            $("#chkAttReqSM").prop('checked',true);
                        }

                        if(AttrData[i]['apply_sub_cat']== "1")
                        {
                            $("#chkAttEntireSM").prop('checked',true);
                        }

                        $('input[type="radio"][name="rbAttSM"][value='+type+']:first').prop('checked', true);
                        $('input[type="radio"][name="chkAttAddSM"][value='+add_to_price+']:first').prop('checked', true);
                        var mPrice = "NA";

                        if (($.trim(AttrData[i]['Price'])!="") && ($.trim(AttrData[i]['Price'])!=".75"))
                        {
                                mPrice = Number(AttrData[i]['Price']).toFixed(2);
                        }

                        var mDefault = "No";

                        if (AttrData[i]['Default']==1)
                        {
                                mDefault = "Yes";
                        }

                        hdnOptions  = hdnOptions+  "|"+AttrData[i]['Title'].replace("&#39;", "'")+"~"+mPrice+"~"+mDefault;

                        $("#tblOptionsSM tr:last").after('<tr id="tr'+AttrData[i]['Title'].replace("&#39;", "'").replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"><td style="width: 5%;"></td><td style="width: 50%;" align="left">'+AttrData[i]['Title'].replace("&#39;", "'")+'</td><td style="width: 15%;" align="left">'+mPrice+'</td><td style="width: 15%;" align="left">'+mDefault+'</td><td style="width: 10%;"><img onclick=editAttributeOptionSM("tr'+AttrData[i]['Title'].replace("&#39;", "'").replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"); class="attEdit" src="img/pencil.png" alt="Edit" style="width: 17px;cursor:pointer" data-tooltip="Edit" name="imgAttEditSM" id="imgAttEditSM"/>&nbsp;<img onclick=deleteOptionSM("tr'+AttrData[i]['Title'].replace("&#39;", "'").replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"); class="attRem" src="img/delete_icon2.png" alt="Delete" style="width: 17px;cursor:pointer" data-tooltip="Delete" name="imgAttDelSM" id="imgAttDelSM"/></td><td style="width: 5%;"></td></tr><tr id="2tr'+AttrData[i]['Title'].replace("&#39;", "'").replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'" style="height: 5px;"><td colspan="6"></td></tr>');
                    }
                    $("#hdnOptionsSM").val(hdnOptions);
                    makeStringSM();
                }
            }
       });

   });

    $(".btnCancelSM").click(function()
    {
        $.fancybox.close();
        emptyFancyBoxFieldsSM();
    });

    //*****************focus and blur event*********************//
    $( "#txtAttNameSM" ).blur(function() {
         $('#txtAttNameSM').attr('placeholder','Admin Name');
     });

     $( "#txtAttNameSM" ).focus(function() {

         $('#txtAttNameSM').attr('placeholder','');
     });

//     $( "#txtAttTitleSM" ).blur(function() {
//
//         $('#txtAttTitleSM').attr('placeholder','Display Title (Example - "Choose Sauce")');
//     });

     $( "#txtAttTitleSM" ).focus(function() {

         $('#txtAttTitleSM').attr('placeholder','');
     });

     $( "#txtAttSubTitleSM" ).blur(function() {

         $('#txtAttSubTitleSM').attr('placeholder','Example - "Hot Sauce"');
     });

     $( "#txtAttSubTitleSM" ).focus(function() {

         $('#txtAttSubTitleSM').attr('placeholder','');
     });

     $( "#txtAttPriceSM" ).blur(function() {

         $('#txtAttPriceSM').attr('placeholder','.75');
     });

     $( "#txtAttPriceSM" ).focus(function() {

         $('#txtAttPriceSM').attr('placeholder','');
     });
     //*****************End*********************//
});

    //Delete attribute option from fancy box
    function deleteOptionSM(pRowID)
    {
        var pRowID1 = document.getElementById(pRowID);
        $(pRowID1).hide();
        $(pRowID1).hide();
        mText = "|"+pRowID.replace("ewo_qc", "~");
        mText = mText.replace("ewo_qc", "~");
        mText = mText.replace("|tr", "|");
        mText = mText.replace(/\//g, ' ');
        $("#hdnOptionsSM").val($("#hdnOptionsSM").val().replace(mText, ""));

        //Saad change 25-Sept-2014
        var editIdHiddenValue = $('#hdnEditOptionIdSM').val();
        if(editIdHiddenValue == pRowID) {
            cancelEditAttributeOptionSM();
        }
    }

    // Saad - 25-Sept-2014 -- Edit Attribute Option
    function editAttributeOptionSM(optionRowId)
    {
        var tableRow = document.getElementById(optionRowId);
        var rowColumns = $(tableRow).find('td');
        var optionName = $(rowColumns[1]).text();
        var optionPrice =  $(rowColumns[2]).text();
        var isOptionDefaultSelected =  $(rowColumns[3]).text();
        $('#txtAttSubTitleSM').val(optionName);
        if(optionPrice==="NA"){
            $('#txtAttPriceSM').val('');
        }else{
            $('#txtAttPriceSM').val(optionPrice);
        }
        
        if(isOptionDefaultSelected != 'No') {
            $('#chkAttDefSM').attr('checked', true);
        }else{
            $('#chkAttDefSM').attr('checked', false);
        }

        $('#btnUpdateAttrSM').attr('onclick','updateAttributeOptionSM("'+optionRowId+'");');
        $('#hdnEditOptionIdSM').val(optionRowId);
        $('#btnAddAttrSM').hide();
        $('#btnUpdateAttrSM').show();
        $('#btnCancelAttrSM').show();

    }

    // Saad - 25-Sept-2014 -- Cancel Editing Attribute Option
    function cancelEditAttributeOptionSM()
    {
        $("#spnDupSM").hide();
        $('#txtAttSubTitleSM').val('');
        $('#txtAttPriceSM').val('');
        $('#hdnEditOptionIdSM').val('');
        $('#chkAttDefSM').attr('checked', false);

        $('#btnAddAttrSM').show();
        $('#btnUpdateAttrSM').hide();
        $('#btnCancelAttrSM').hide();

    }

    // Saad - 25-Sept-2014 -- Update Attribute Option
    function updateAttributeOptionSM(optionRowId)
    {
        var tableRow = document.getElementById(optionRowId);
        var rowColumns = $(tableRow).find('td');

        var currentOptionName = $(rowColumns[1]).text();
        //var currentOptionPrice =  $(rowColumns[2]).text();
        //var currentOptionDefaultSelected =  $(rowColumns[3]).text();

        var updatedOptionName = $.trim($('#txtAttSubTitleSM').val());
        var updatedOptionPrice =  $('#txtAttPriceSM').val();
        var isOptionDefaultSelected =  $('#chkAttDefSM').is(':checked');

        //var currentValForHiddenField = "|"+currentOptionName+"~"+currentOptionPrice+"~"+currentOptionDefaultSelected;

        $("#spnDupSM").hide();

        // Check if empty field for attribute
        if ((updatedOptionName == "") || (updatedOptionName == 'Example - "Hot Sause"'))
        {
            $("#spnTitleReqSM").css("visibility", "visible");
            $("#txtAttSubTitleSM").focus();
            return;
        }
        else
        {
            $("#spnTitleReqSM").css("visibility", "hidden");
        }

        // Check if user updated option name
        if(currentOptionName.toLowerCase() !== updatedOptionName.toLowerCase())
        {
            // Check updated name of option in hidden field in order to make sure no duplicate name for options
            if ($.trim($("#hdnOptionsSM").val()).toLowerCase().indexOf("|"+updatedOptionName.toLowerCase()+"~")>=0)
            {
                $("#spnDupSM").show();
                $("#txtAttSubTitleSM").focus();
                return;
            }
            else
            {
                $("#spnDupSM").hide();
            }
        }

        var updatedOptionPriceFixedValue = "NA";
		if (($.trim(updatedOptionPrice)!="") && ($.trim(updatedOptionPrice).replace(/-/g, "")!=""))
        {
            updatedOptionPriceFixedValue = Number(updatedOptionPrice).toFixed(2);
        }

        var updatedOptionDefaultValue = "No";
        if (isOptionDefaultSelected)
        {
            updatedOptionDefaultValue = "Yes";
        }

        $(rowColumns[1]).text(updatedOptionName);
        $(rowColumns[2]).text(updatedOptionPriceFixedValue);
        $(rowColumns[3]).text(updatedOptionDefaultValue);

        //var updatedValForHiddenField = "|"+updatedOptionName+"~"+mPrice+"~"+updatedOptionDefaultValue;

        var allOptionsArray = $("#hdnOptionsSM").val().split('|');
        var updatedOptionsArray = new Array();
        updatedOptionsArray[0] = allOptionsArray[0];

        for(i = 1; i < allOptionsArray.length; i++){

            var attributeValues = allOptionsArray[i].split('~');
            if(attributeValues[0] === currentOptionName){
                updatedOptionsArray[i] = updatedOptionName+"~"+updatedOptionPriceFixedValue+"~"+updatedOptionDefaultValue;
            }else{
                updatedOptionsArray[i] = allOptionsArray[i];
            }
        }

        var updatedValue = updatedOptionsArray.join('|');

        $("#hdnOptionsSM").val(updatedValue);
        var updatedId = "tr"+updatedOptionName.replace(/ /g,"/")+"ewo_qc"+updatedOptionPriceFixedValue+"ewo_qc"+updatedOptionDefaultValue;

        $(tableRow).find('#imgAttEditSM').attr('onclick','editAttributeOptionSM("'+updatedId+'");');
        $(tableRow).find('#imgAttDelSM').attr('onclick','deleteOptionSM("'+updatedId+'");');

        $(tableRow).attr('id',updatedId);
        
        var tableRow2 = document.getElementById("2"+optionRowId);
        $(tableRow2).attr('id','2'+updatedId);
        //$("#tblOptions tr:last").after('<tr id="tr'+$("#txtAttSubTitle").val().replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"><td style="width: 5%;"></td><td style="width: 50%;" align="left">'+$("#txtAttSubTitle").val()+'</td><td style="width: 15%;" align="left">'+mPrice+'</td><td style="width: 15%;" align="left">'+mDefault+'</td><td style="width: 10%;"><img onclick=editAttributeOption("tr'+AttrData[i]['Title'].replace("&#39;", "'").replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"); class="attEdit" src="img/pencil.png" alt="Edit" style="width: 17px;cursor:pointer" data-tooltip="Edit" name="imgAttEdit" id="imgAttEdit"/>&nbsp;<img onclick=deleteOption("tr'+$("#txtAttSubTitle").val().replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"); class="attRem" src="img/delete_icon2.png" alt="Delete" style="width: 17px;cursor:pointer" data-tooltip="Delete" name="imgAttDel" id="imgAttDel"/></td><td style="width: 5%;"></td></tr><tr id="2tr'+$("#txtAttSubTitle").val()+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'" style="height: 5px;"><td colspan="6"></td></tr>');

        cancelEditAttributeOptionSM();

    }

    function isNumber(n)
    {
    return !isNaN(parseFloat(n)) && isFinite(n);
    }
    function emptyFancyBoxFieldsSM()
    {
    $("#hdnOptionsSM").val('');
    $("#chkAttDefSM").attr("checked", false);
    $("#txtAttSubTitleSM").val("");
    $("#txtAttPriceSM").val("");
    $("#rbAttDDSM").attr("checked", true);
    $("#chkAttAddSM").attr("checked", true);
    $("#chkAttTotalSM").attr("checked", false);
    $("#chkAttReqSM").attr("checked", false);
    $("#chkAttEntireSM").attr("checked", false);
    $("#txtAttTitleSM").val("");
    $("#txtAttNameSM").val("");
    $("#hdnAttributeUpdateSet1").val('');
    $("#username_availability_resultSM").html('');
    $("#tblOptionsSM tr:gt(0)").remove();
    $("#TopHeadingSM").html("Add New Attribute");
    $("#hdnNameSM").val('');
    $('.validated_error').css('display','none');
    $("#span_ChooseSM").text('');
    $("#span_limitSM").text('');
    $("#span_attr_nameSM").text('');
    $("#attr_chooseSM").val("");
    $("#attr_limitSM").val("");
    $("#attr_limitSM").hide();
    $("#txtLimitExceedSM").val("");
    $("#hdnAttributesSM").val("");
    $("#txtLimitExceedSM").hide();
    $("#chkLimitExceedNoSM").attr('checked', 'checked');
    $('#txtAttTitleSM').attr('placeholder','Display Title (Example - "Choose Sauce")');
    $("#txtAttTitleSM").width(255);

    }

