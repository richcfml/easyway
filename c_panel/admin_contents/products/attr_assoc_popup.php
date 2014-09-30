<link href="css/chosen.css" rel="stylesheet" type="text/css" />
<script src="js/chosen.js" type="text/javascript"></script>
<link href="css/chosen-org.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/darktooltip.min.css">
<script src="js/darktooltip.min.js"></script>
    <a class="fancyloadRelatedItem" href="#BrowsefancyBox"></a>
    <div id="BrowsefancyBox" style="display:none;width:1150px;min-height:1448px;">
        <div>
            <form action="admin_contents/products/add_assoc_new.php" method="get" name="frm1" >
                <div style="background-color: #25AAE1;font-size: 19px;color: white;text-align: center;padding: 7px;border-radius: 5px;">Check Items To Associate</div>
                <div id ="checkboxBind" style="width: 98%;"></div>
                <input type="hidden" id="product_id" name="product_id" value="<?=$_GET['prd_id']?>"  />
                <input type="hidden" id="category_id" name="category_id" value="<?= $_GET['catid'] ?>"  />
                <input type="hidden" id="sub_cat_id" name="sub_cat_id" value="<?= $_GET['sub_cat'] ?>"  />
            </form>
        </div>
        <br/><br/>
    </div>
    <a class="fancyAddAttribute" href="#TheFancyboxAddExisting"></a>
    <div id="TheFancyboxAddExisting" style="display:none;" >
         
        <div id="existingAttributeFancy" style="width:400px;height: 250px;text-align: center;line-height: 40px;">
            <div style="background-color: #25AAE1;font-size: 19px;color: white;text-align: center;padding-left: 7px;border-radius: 5px;">Add Existing Attribute</div>
            
            <div style="text-align: left;padding: 20px;">
                <select class="chzn-select"  name="addAttributetxt" id="addAttributetxt" style="width:200px;">
            <option value="">--Please Select--</option>

            </select>
                
            <input type="button" value="Browse" id="browseAttributes" name="browseAttributes" class="cancel" style="line-height: 25px;height:27px;width: 96px;">
            <span id="attrDisplayMessage" class="alert-error" style="display: none">Attribute already Added</span>
            </div>
            
            <div style="text-align: left;margin-left: 98px;">
                <input type="submit" value="Add" id="btn_addAttribute" name="btn_addAttribute" class="cancel" style="line-height: 25px;height:27px;width: 85px;margin-left: 20px"></div>
        </div>
        
    </div>
        <a class="fancyloadAttributeItem" href="#BrowseAttributefancyBox"></a>
        <div id="BrowseAttributefancyBox" style="display:none;width:1150px;min-height:400px;height:1448px;" class="handle_fancyBox">
            <div>
                 
                <form action="admin_contents/products/add_attribute_new.php" method="get" name="frm2" >
                    <div style="background-color: #25AAE1;font-size: 19px;color: white;text-align: center;padding: 7px;border-radius: 5px;">Check Attributes to Add</div>
                    <input type="hidden" id="prod_id" name="prod_id" value="<?=$_GET['prd_id']?>"  />
                    <input type="hidden" id="category_id" name="category_id" value="<?= $_GET['catid'] ?>"  />
                    <input type="hidden" id="subcat_id" name="subcat_id" value="<?= $_GET['sub_cat'] ?>"  />
                    <div id ="checkboxBindAttribute" style="margin-top:15px"></div>
                    
                </form>
            </div>
            <br/>
            <br/>
            
        </div>
		
	<div style="display: none; font-family: Arial; border: 1px solid #CCC; width: 600px;" id="dvAddAttribute">
		<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
			<tr style="height: 50px; background-color:#25AAE1 !important; text-align: center; vertical-align: middle;">
				<td>
					<span style="font-size: 23px; color: #FFFFFF;" id="TopHeading">Add New Attribute</span>
				</td>
			</tr>
			<tr>
				<table style="background-color: #ECEDEE; width: 100%; text-align: center;" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="4"><div class="validated_error"style="display:none;color: red;margin-top: 10px;font-size: 18px;">Please fill required fields</div>
						</td>
					</tr>
					<tr style="height: 50px;">
						<td colspan="4">
							<select id ="attr_choose" name="attr_choose" style="height: 36px;">
								<option value="">Please Select</option>
								<option value="1">Choose</option>
								<option value="2">Pick</option>
								<option value="3">Add</option>
								<option value="4">Create Your Own</option>
							</select>
							<span style="color:red; visibility: hidden;" id="spnChooseAttr">*</span>
							<input type="text" id="txtAttName" name="txtAttName" placeholder="Name" style="width:160px; text-indent: 5px; height: 30px;margin-top: 6px;" /><span style="color:red; visibility: hidden;" id="spnNameReq">*</span>
							<select id ="attr_limit" name="attr_limit" style="height: 36px;width: 60px;" placeholder="Limit">
								<option value="">Limit</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
							</select>
                                                        <span style="color:red; visibility: hidden;" id="spnChooseLimit">*</span>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
							<div id="final_attr_name" name="final_attr_name" style="font-size: 14px;color: green;"><span id="span_Choose" style="margin-left:18px"></span><span id="span_limit"></span><span id="span_attr_name"></span></div>
						</td>
					</tr>
                                        <tr>
                                            <td colspan="4">
                                                <div style="color: #25AAE1; font-size:14px;margin-top: 12px;margin-left: 72px;width: 273px;float: left">Charge extra if limit exceeded</div>
                                                <div style="float: left;margin-left: -32px;">
                                                 <input type="radio" name="chkLimitExceed" id="chkLimitExceedNo" value="0"  class="chk_style"><label for="chkLimitExceedNo" style="color: #25AAE1; font-size:14px;margin-top: 8px;">No</label>
                                                 <input type="radio" name="chkLimitExceed" id="chkLimitExceedYes" value="1" class="chk_style"><label for="chkLimitExceedYes" style="color: #25AAE1; font-size:14px;">Yes</label>
                                                 <input type="text" id="txtLimitExceed" name="txtLimitExceed" maxlength="4" placeholder="" style="text-indent: 5px;margin-top: 6px;width: 60px;height: 24px;display:none" />
                                                <span style="color:red; visibility: hidden;" id="spnLimitExceed">*</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <script type="text/javascript">
                                        $(document).ready(function()
                                        {
                                            $("input[name='chkLimitExceed']").change(function ()
                                            {
                                                var value = this.value;
                                                if(value == 1)
                                                {
                                                    $("#txtLimitExceed").show();

                                                }
                                                else
                                                {
                                                    $("#txtLimitExceed").hide();
                                                    $("#spnLimitExceed").css("visibility", "hidden");
                                                }
                                            });
                                        });
                                        </script>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr style="height: 50px;">
						<td colspan="4">
							<input type="text" id="txtAttTitle" name="txtAttTitle" placeholder="Display Title (Example - &quot;Choose Sauce&quot;)" style="width: 320px; text-indent: 5px; height: 30px;" /><span style="color:red; visibility: hidden;" id="spnAttTitleReq">*</span>
						</td>
					</tr>
					<tr style="height: 5px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<span style="color: red; display: none;" id="spnDupAtt">Attribute already exists.</span>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td style="width: 10%;">
						</td>
						<td align="left">
							<input type="checkbox" name="chkAttReq" id="chkAttReq" class="chk_style"/><label for="chkAttReq" style="color: #25AAE1; font-size:14px;">Attribute Required for Ordering</label>
						</td>
						<td style="width: 10%;">
						</td>
						<td align="left">
							<input type="radio" name="chkAttAdd" id="chkAttAdd" value="1" checked="checked" class="chk_style"/><label for="chkAttAdd" style="color: #25AAE1; font-size:14px;margin-top: 8px;">Attribute adds to price</label><br />
							<input type="radio" name="chkAttAdd" id="chkAttTotal" value="2" class="chk_style"/><label for="chkAttTotal" style="color: #25AAE1; font-size:14px;margin-top: 8px;">Attribute displays total price</label>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td align="center" colspan="4" align="center">
							<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="width: 30%;">
									</td>
									<td style="width: 14%;" valign="top">
										<span style="color: #25AAE1; font-size:14px;">Layout:</span>
                                                                                <a class="hover_gray" style="padding: 2px 6px 2px 5px;border-radius: 50%;color: black;background-color: #b9b9b9;cursor: pointer;color: white;">?<i style="width:165px;margin-left:-85px">This is the method through which your customers will select options. Radio Buttons are used for making one choice. Checkboxes for multiple choices.</i></a>
									</td>
									<td>
										<input type="radio" name="rbAtt" id="rbAttDD" value="1" checked="checked" class="chk_style"/><label for="rbAttDD"  style="color: #25AAE1; font-size:14px;margin-top: 8px;">Drop Down Menu</label><br />
										<input type="radio" name="rbAtt" id="rbAttCB" value="2" class="chk_style" /><label for="rbAttCB" style="color: #25AAE1; font-size:14px;margin-top: 8px;">Check Boxes</label><span style="font-size:14px;"></span><br />
										<input type="radio" name="rbAtt" id="rbAttRB" value="3" class="chk_style"/><label for="rbAttRB" style="color: #25AAE1; font-size:14px;margin-top: 8px;">Radio Buttons</label>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="width: 5%;">
									</td>
									<td style="width: 90%;">
										<hr />
									</td>
									<td style="width: 5%;">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td style="width: 10%;">
						</td>
						<td align="left">
							<input type="text" id="txtAttSubTitle" name="txtAttSubTitle" placeholder="Example - &quot;Hot Sauce&quot;" style="width: 250px; text-indent: 5px; height: 30px;" /><span style="color:red; visibility: hidden;" id="spnTitleReq">*</span>
						</td>
						<td style="width: 10%;">
						</td>
						<td align="left">
							&nbsp;<input type="text" id="txtAttPrice" maxlength="7" name="txtAttPrice" placeholder=".75" style="width: 120px; text-indent: 5px; height: 30px;" />
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td style="width: 10%;">
						</td>
						<td colspan="3" align="left">
							<input type="checkbox" name="chkAttDef" class="chk_style" id="chkAttDef"/><label for="chkAttDef" style="color: #25AAE1; font-size:14px;">Default Attribute?</label>
                                                        <a class="hover_gray" style="padding: 2px 6px 2px 5px;border-radius: 50%;color: black;background-color: #b9b9b9;cursor: pointer;color: white;">?<i style="width:165px;margin-left:-85px">Required for check-out. If customer neglects to make a selection, this will be the default selection.</i></a>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td colspan="4">

							<script language="javascript" type="text/javascript">
								function deleteOption(pRowID)
								{
									var pRowID1 = document.getElementById(pRowID);
									$(pRowID1).hide();
									$(pRowID1).hide();
									mText = "|"+pRowID.replace("ewo_qc", "~");
									mText = mText.replace("ewo_qc", "~");
									mText = mText.replace("|tr", "|");
									mText = mText.replace(/\//g, ' ');
									$("#hdnOptions").val($("#hdnOptions").val().replace(mText, ""));
								}
								
								$(document).ready(function()
								{
									$("#attr_choose").change(function()
										{
											if($("#attr_choose").val()!="" && $("#attr_choose").val()!= 4)
											{
												$("#span_Choose").text($( "#attr_choose option:selected" ).text())
                                                                                                $('#txtAttName').width(160);
											}
                                                                                        else if($("#attr_choose").val()== 4)
                                                                                        {
                                                                                                $('#txtAttName').width(200);
                                                                                                $( "#span_Choose" ).text('Type your message here');
                                                                                                $( "#span_limit" ).text('');
                                                                                                $("#span_attr_name").text('');
                                                                                        }
											else
											{
												$( "#span_Choose" ).text('');
                                                                                                $('#txtAttName').width(160);
											}
										});

										$("#txtAttName").focusout(function()
										{
											if($( "#attr_limit" ).text()!="" && $("#attr_choose").val()!= 4)
											{
												$("#span_attr_name").text(' '+$("#txtAttName").val());
											}
											else
											{
												$("#span_attr_name").text('');
											}
										});

										$("#attr_limit").change(function()
										{
											$("#span_limit").text(' '+$( "#attr_limit option:selected" ).text());
											if($("#attr_limit").val()!="" && $("#attr_choose").val()!= 4)
											{   
												$( "#span_limit" ).text(" up to "+$( "#attr_limit option:selected" ).text());
											}
											else
											{
												$( "#span_limit" ).text('')
											}

										});
										
									$("#txtAttPrice,#txtLimitExceed").keydown(function (e) {
										if ($.inArray(e.keyCode, [173, 46, 8, 9, 27, 13, 110, 190, 109, 189]) !== -1 ||
											(e.keyCode == 65 && e.ctrlKey === true) ||
											(e.keyCode >= 35 && e.keyCode <= 39)) {
												 return;
										}
										if ((e.keyCode != 173) && (e.keyCode != 109) && (e.keyCode != 189))
										{
											if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) 
											{
												e.preventDefault();
											}
										}
									});

                                                                        $("#txtAttName,#txtAttTitle,#txtAttSubTitle").keypress(function(e) {
                                                                                   var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
                                                                                   var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (keyCode == 32) || (keyCode == 8 ));
                                                                                   return ret;
                                                                       });

								    $("#btnAddAttr").die('click').live('click', function() 
									{
										$("#spnDup").hide();
										if (($.trim($("#txtAttSubTitle").val())=="") || ($.trim($("#txtAttSubTitle").val())=='Example - "Hot Sause"'))
										{
											$("#spnTitleReq").css("visibility", "visible");
											$("#txtAttSubTitle").focus();
											return;
										}
										else
										{
											$("#spnTitleReq").css("visibility", "hidden");
										}
										
										if ($.trim($("#hdnOptions").val()).toLowerCase().indexOf("|"+$.trim($("#txtAttSubTitle").val()).toLowerCase()+"~")>=0)
										{
											$("#spnDup").show();
											$("#txtAttSubTitle").focus();
											return;
										}
										else
										{
											$("#spnDup").hide();
										}
																																				   
										
										var mPrice = "NA";
										if ($.trim($("#txtAttPrice").val())!="")
										{
											
                                                                                        mPrice = Number($("#txtAttPrice").val()).toFixed(2);
											
										}
										
										var mDefault = "No";
										if ($("#chkAttDef").is(":checked")) 
										{
											mDefault = "Yes";
										}
										
										$("#hdnOptions").val($("#hdnOptions").val()+"|"+$("#txtAttSubTitle").val()+"~"+mPrice+"~"+mDefault);
										$("#tblOptions tr:last").after('<tr id="tr'+$("#txtAttSubTitle").val().replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"><td style="width: 5%;"></td><td style="width: 50%;" align="left">'+$("#txtAttSubTitle").val()+'</td><td style="width: 15%;" align="left">'+mPrice+'</td><td style="width: 15%;" align="left">'+mDefault+'</td><td style="width: 10%;"><img onclick=deleteOption("tr'+$("#txtAttSubTitle").val().replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"); class="attRem" src="img/delete_icon2.png" alt="Delete" style="width: 17px;cursor:pointer" data-tooltip="Delete" name="imgAttDel" id="imgAttDel"/></td><td style="width: 5%;"></td></tr><tr id="2tr'+$("#txtAttSubTitle").val()+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'" style="height: 5px;"><td colspan="6"></td></tr>');
										$("#chkAttDef").attr("checked", false);
										$("#txtAttSubTitle").val("");
										$("#txtAttPrice").val("");
									});
								});
							</script>
							<table style="width: 100%; margin: 0px;" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td style="width: 40%;">
									</td>
									<td style="width: 60%; text-align: left !important;" align="left">
										<input type="button" id="btnAddAttr" name="btnAddAttr" value="Add">&nbsp;<span style="color: red; display: none;" id="spnDup">Attribute already exists.</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td colspan="4">
						   <div style="overflow-y:scroll;height:100px ">
							<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="width: 5%;">
									</td>
									<td style="width: 90%;">
										<input type="hidden" id="hdnOptions" name="hdnOptions" />
										<input type="hidden" id="hdnAttributes" name="hdnAttributes" />
										<input type="hidden" id="updateAttributeSet1" name="updateAttributeSet1" />
										<input type="hidden" id="hdnName" name="hdnName" />
										<table id="tblOptions" style="margin-top: 5px; font-size: 12px; width: 100%; border: 1px solid #CCCCCC; background-color: #FFFFFF;" cellpadding="0" cellspacing="0">
											<tr>
												<td style="width: 5%;">
												</td>
												<td style="width: 50%;" align="left">
													<strong style="color:#25aee1;">Option</strong>
												</td>
												<td style="width: 15%;" align="left">
													<strong style="color:#25aee1;">Price</strong>
												</td>
												<td style="width: 15%;" align="left">
													<strong style="color:#25aee1;">Default</strong>
												</td>
												<td style="width: 10%;">
	
												</td>
												<td style="width: 5%;">
												</td>
											</tr>
											<tr style="height: 5px;">
												<td colspan="6">
												</td>
											</tr>
										</table>
									</td>
									<td style="width: 5%;">
									</td>
								</tr>
							</table>
						   </div>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="width: 5%;"></td>
									<td style="width: 65%;">
										<span style="font-size: 14px;" class="appl_all">
											Would you like to apply this to an entire Submenu?
										</span>
										<input type="checkbox" name="chkAttEntire" id="chkAttEntire" value="1" class="chk_style"/>
										<label for="chkAttEntire" style="color: #25AAE1; font-size:14px;margin-bottom: 15px;"></label>
									</td>
									<td style="width: 12%;" align="right">
										<script type="text/javascript" language="javascript">
										$(document).ready(function()
										{
											$(".btnCancelSM").click(function()
											{
                                                                                            $.fancybox.close();
                                                                                            emptyFancyBoxFields();
											});
		
                                                                                        $('.option_Name').fancybox({
                                                                                            afterClose: function() {
                                                                                                    emptyFancyBoxFields();
                                                                                            }
											});

											$("#btnSave").die('click').live('click', function() 
											{
												$("#spnDupAtt").hide();
												if ($("#attr_choose").val()=="")
												{
													$("#spnChooseAttr").css("visibility", "visible");
													$("#attr_choose").focus();
													return;
												}
												
												if ($("#txtAttName").val()=="")
												{
													$("#spnNameReq").css("visibility", "visible");
													$("#txtAttName").focus();
													return;
												}
												$("#spnNameReq").css("visibility", "hidden");
												
												if ($("#txtAttTitle").val()=="")
												{
													$("#spnAttTitleReq").css("visibility", "visible");
													$("#txtAttTitle").focus();
													return;	
												}
												$("#spnAttTitleReq").css("visibility", "hidden");
												
												var mSubCatID = <?=$_GET["sub_cat"];?>;
												var mName = $("#span_Choose").text()+$("#span_limit").text()+$("#span_attr_name").text();
												var mOptionName = $("#txtAttTitle").val();
												var mAttrFields = $("#attr_choose").val()+'~'+$("#txtAttName").val()+'~'+$("#attr_limit").val();
												var prd_id = $("#prd_id").val();
												var mLayout = 1;
												var ajaxUrl;
												var add_to_price = 1;
                                                                                                var mExtraCharger;
                                                                                                if($('input[name=chkLimitExceed]:radio:checked').val() == 1 )
                                                                                                {
                                                                                                    if($("#attr_limit").val()=='')
                                                                                                    {   
                                                                                                        $("#spnChooseLimit").css("visibility", "visible");
													$("#attr_limit").focus();
                                                                                                        return;
                                                                                                    }
                                                                                                     mExtraCharger = $("#txtLimitExceed").val();
                                                                                                     if(mExtraCharger=='' ||mExtraCharger==0)
                                                                                                     {
                                                                                                        $("#spnLimitExceed").css("visibility", "visible");
													$("#txtLimitExceed").focus();
                                                                                                        return;
                                                                                                     }
                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                    mExtraCharger =0;
                                                                                                }
                                                                                                
												if ($("#rbAttDD").is(":checked"))
												{
													mLayout = 1;
												}
												else if ($("#rbAttCB").is(":checked"))
												{
													mLayout = 2;
												}
												else if ($("#rbAttRB").is(":checked"))
												{
													mLayout = 3;
												}

												if ($("#chkAttAdd").is(":checked"))
												{
													add_to_price = 1;
												}
												else if ($("#chkAttTotal").is(":checked"))
												{
													add_to_price = 2;
												}

												var mApplySubCat = 0;
												if ($("#chkAttEntire").is(":checked"))
												{
													mApplySubCat = 1;
												}
	
												var mRequired = 0;
												if ($("#chkAttReq").is(":checked"))
												{
													mRequired = 1;
												}
												
												mOptionsString = $("#hdnOptions").val();
												var milliseconds = (new Date).getTime();
												if($("#updateAttributeSet1").val()=="")
												{
												   ajaxUrl =  "admin_contents/menus/menu_ajax.php?AddNewAttribute=1&SubCatID="+mSubCatID+"&Name="+mName+"&OptionName="+mOptionName+"&Layout="+mLayout+"&ApplySubCat="+mApplySubCat+"&Required="+mRequired+"&Title_Price_Defalt="+mOptionsString+"&prd_id="+prd_id+"&add_to_price="+add_to_price+"&attrFields="+mAttrFields+"&extraCharger="+mExtraCharger+"&"+milliseconds;
												}
												else if($("#updateAttributeSet1").val()!="")
												{  
												   ajaxUrl =  "admin_contents/menus/menu_ajax.php?UpdateAttribute=1&SubCatID="+mSubCatID+"&Name="+mName+"&OptionName="+mOptionName+"&Layout="+mLayout+"&ApplySubCat="+mApplySubCat+"&Required="+mRequired+"&Title_Price_Defalt="+mOptionsString+"&add_to_price="+add_to_price+"&prd_id="+prd_id+"&oldOptionName="+$("#updateAttributeSet1").val()+"&attrFields="+mAttrFields+"&extraCharger="+mExtraCharger+"&"+milliseconds;
												   
												}
													$.ajax({
														url: ajaxUrl,
														success: function(data) 
														{	
															if (data!='Failure')
															{
																if ($("#hdnAttributes").val().indexOf("~"+$("#txtAttTitle").val())<0)
																{																												  
																	if ($.trim($("#hdnAttributes").val())=="")
																	{
																		$("#hdnAttributes").val(data+"~"+$("#txtAttTitle").val()+"~"+$("#txtAttName").val());
																	}
																	else
																	{
																		$("#hdnAttributes").val($("#hdnAttributes").val()+"|"+data+"~"+$("#txtAttTitle").val()+"~"+$("#txtAttName").val());
																	}
																	
																	$("#dvAttributes").html("");
																	mAttributeList = $("#hdnAttributes").val().split("|");
                                                                                                                                        console.log(mAttributeList);
																	mAttributeList.forEach(function(mAttributeListRow)
																	{
																		if(prd_id!='')
																		{
																			mAttributeData = mAttributeListRow.split("~");
																			mAttributeID = mAttributeData[0]; 
																			mAttributeName = mAttributeData[1];
																			mAttributeN = mAttributeData[2];

																			var img = $('<div><span></div>');
																			img.find('span').attr({
																				'option_name':mAttributeName,
																				'display_name':mName,
                                                                                                                                                                'attributeIds':data
																			});
																			
																			img.find('span').addClass("attr_delete");
																			img.find('span').text('x');
																			var attr_name = "attr_"+(document.getElementById('updateAttributeSet1').value).replace(/ /g,'');
                                                                                                                                                        var atrtObject = document.getElementById(attr_name);
																			$(atrtObject).remove();
																			$("#attr-list_product").append('<li id=\'attr_' + mAttributeName.split(' ').join('') + '\' class="liAtribute"><a href="#dvAddAttribute" class="option_Name" style="margin-top:2px">'+mAttributeName+'</a> '+img.html()+'  <div style="clear:both"></div></li>');
																			$('.noAttributesForProduct').hide();
                                                                                                                                                        $("#btnSortAttribute").show();
																			$('.option_Name').fancybox({
																				  afterClose: function() {
																					  emptyFancyBoxFields();
																				  }
																				});
																			}
																	});
																}
																
																$("#dvNoAtt").hide();
																$("#dvAttributes").show();
																$("#spnDupAtt").hide();
																$("#spnDup").hide();
																$.fancybox.close();
																emptyFancyBoxFields();
															}
															else
															{
																if (data=="duplicate")
																{
																	$("#spnDupAtt").show();
																	$("#txtAttTitle").focus();
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
                                                                                                       
										   $('.option_Name').live("click",function()
										   {
											   
											   var option_Name = $(this).text();
											   var subcatid = $("#hdnCatid").val();
											   $("#updateAttributeSet1").val(option_Name);
											   
											   if(isNaN(subcatid))
											   {
												   subcatid = $("#hdn_subcatid").val();
											   }

											   $.ajax
											   ({
													type:"POST",
													url: "admin_contents/menus/menu_ajax.php?GetAttrbutedataforEdit=1&sub_catid="+subcatid+"&option_name="+option_Name+"&prd_id="+$("#prd_id").val(),
													success: function(data) {

														if(data!='')
														{
															var AttrData = jQuery.parseJSON(data);
															var hdnOptions='';
															$("#tblOptions tr:gt(0)").remove();
															for(var i in AttrData)
															{
																$("#hdnName").val(AttrData[i]['display_Name']);
																$("#txtAttTitle").val(AttrData[i]['option_name'].replace("&#39;", "'"));
																
																$("#TopHeading").html('Edit '+AttrData[i]['option_name'].replace("&#39;", "'"));
																var type = AttrData[i]['Type'];
																var add_to_price = AttrData[i]['add_to_price'];
																
															   if(AttrData[i]['attr_name']!= null)
																{
																	mAttributeArray = AttrData[i]['attr_name'].replace("&#39;", "'").split("~");
																	$("#txtAttName").val(mAttributeArray[1]);
																	$("#attr_choose").val(mAttributeArray[0]);
																	$("#attr_limit").val(mAttributeArray[2]);
																	if(AttrData[i]['extra_charge']!=''&& AttrData[i]['extra_charge']!='0')
																	{
																		$("#txtLimitExceed").val(AttrData[i]['extra_charge']);
																		$("#chkLimitExceedYes").attr('checked', 'checked');
																		$("#txtLimitExceed").show();
																	}
																	else
																	{
																		$("#chkLimitExceedNo").attr('checked', 'checked');
																	}
																}
																else
																{
																	$("#txtAttName").val(AttrData[i]['display_Name']);
																}

																if($("#attr_choose").val() !="" && $("#attr_choose").val()!= 4)
																{
																	$("#span_Choose").text($("#attr_choose option:selected" ).text())
																}
																if($("#attr_limit").val() !="" && $("#attr_choose").val()!= 4)
																{
																	$("#span_limit").text(' up to '+$("#attr_limit option:selected" ).text())
																}
																$("#span_attr_name").text(' '+$("#txtAttName").val());

																if(AttrData[i]['Required']== "1")
																{
																	$("#chkAttReq").prop('checked',true);
																}

																if(AttrData[i]['apply_sub_cat']== "1")
																{
																	$("#chkAttEntire").prop('checked',true);
																}
																
																$('input[type="radio"][name="rbAtt"][value='+type+']:first').prop('checked', true);
																$('input[type="radio"][name="chkAttAdd"][value='+add_to_price+']:first').prop('checked', true);

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

																$("#tblOptions tr:last").after('<tr id="tr'+AttrData[i]['Title'].replace("&#39;", "'").replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"><td style="width: 5%;"></td><td style="width: 50%;" align="left">'+AttrData[i]['Title'].replace("&#39;", "'")+'</td><td style="width: 15%;" align="left">'+mPrice+'</td><td style="width: 15%;" align="left">'+mDefault+'</td><td style="width: 10%;"><img onclick=deleteOption("tr'+AttrData[i]['Title'].replace("&#39;", "'").replace(/ /g,"/")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'"); class="attRem" src="img/delete_icon2.png" alt="Delete" style="width: 17px;cursor:pointer" data-tooltip="Delete" name="imgAttDel" id="imgAttDel"/></td><td style="width: 5%;"></td></tr><tr id="2tr'+AttrData[i]['Title'].replace("&#39;", "'")+"ewo_qc"+mPrice+"ewo_qc"+mDefault+'" style="height: 5px;"><td colspan="6"></td></tr>');
															}
															$("#hdnOptions").val(hdnOptions);
														}
													}
											   });

										   });
                                                                                                       
										});
                                                                                    
										function isNumber(n) 
										{
										  return !isNaN(parseFloat(n)) && isFinite(n);
										}

										function emptyFancyBoxFields()
										{
											$("#hdnOptions").val('');
											$("#chkAttDef").attr("checked", false);
											$("#txtAttSubTitle").val("");
											$("#txtAttPrice").val("");
											$("#rbAttDD").attr("checked", true);
											$("#chkAttAdd").attr("checked", true);
											$("#chkAttTotal").attr("checked", false);
											$("#chkAttReq").attr("checked", false);
											$("#chkAttEntire").attr("checked", false);
											$("#txtAttTitle").val("");
											$("#txtAttName").val("");
											$("#updateAttributeSet1").val('');
											$("#tblOptions tr:gt(0)").remove();
											$("#TopHeading").html("Add New Attribute");
											$("#hdnAttributes").val("");
											$("#username_availability_result").html('');
											$("#hdnName").val('');
											$('.validated_error').css('display','none');
											$("#span_Choose").text('');
											$("#span_limit").text('');
											$("#span_attr_name").text('');
											$("#attr_choose").val("");
											$("#attr_limit").val("");
											$("#txtLimitExceed").val("");
										        $("#hdnAttributes").val("");
                                                                                        $('#txtAttName').attr('placeholder','Name');
										}
										</script>
										<input type="button" id="btnSave" name="btnSave" value="Save">
									</td>
									<td>
										<input type="button" class="btnCancelSM"  value="Cancel">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: 10px;">
						<td colspan="4">
						</td>
					</tr>
				</table>
			</tr>
		</table>
	</div>
        <div id="popup_box" class="popup_box" style="width:400px;min-height:250px;">

        <div style="background-color: #25AAE1;font-size: 19px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;"><div style="margin-left: 112px;">Save Changes?</div></div>
            <div style="margin-top: 40px;margin-left: 85px;">
                <input type="button" id="btnYesSM" name="btnYesSM" value="Yes" class="cancel" style="font-size: 20px;">
                <input type="button" id="btnNoSM" name="btnNoSM" value="No" class="cancel" style="font-size: 20px;">
            </div>
       </div>

        <a class="fancyCopMenu" href="#TheFancyboxCopyMenu"></a>
        <div id="TheFancyboxCopyMenu" style="display:none;width:400px;min-height:250px;" >
            <div style="background-color: #25AAE1;font-size: 19px;color: white;text-align: left;padding-left: 7px;padding: 14px;border-radius: 5px;">
              Copy Menu</div>
            <div style="text-align: center;font-size: 17px;padding: 13px;">Restaurants</div>
                    
             <form action="admin_contents/products/copy_restaurant_menu.php" method="get" name="frmCopy" >
              <div class='check_box_restaurant' style="margin-left: 10px;">
              <?php
                    $item_query = mysql_query("SELECT id,name from resturants where owner_id =(Select owner_id from resturants WHERE id = '".$Objrestaurant->id."') and id != '".$Objrestaurant->id."'");
                        while($itemRs = mysql_fetch_object($item_query)){
                ?>
                    <input type='checkbox' name='restaurantcheck[]' id='restaurantcheck' value=" <?= $itemRs->id ?>" class="chk_style"><label for="restaurantcheck" style="color: #25AAE1; font-size:14px;"><?=stripslashes($itemRs->name) ?></label>

               <? }?>
               </div>
                    <input type="hidden" value ="<?=$menu_id?>" id="hdnMenu_id" name="hdnMenu_id"/>
                    <input type="submit" value="Copy Menu" id="btnCopyRestaurant" name="btnCopyRestaurant" class="cancel" >
             </form>
         </div>