(function($){
	$(function() {
		$("#login-id, #reg-id").click(function() {
			var id = $(this).attr("id");
			alert(id);
			if(id == "login-id") {
				$('#login-div').show();
				$('#reg-div').hide();
			} else if(id == "reg-id") {
				$('#login-div').hide();
				$('#reg-div').show();			
			}
			return false;
		});	
		
		$("#fax, #email, #pos").click(function() {
			var id = $(this).attr("id");
			if(id=='fax')
				{
					$('#FaxDivId').show();
					$('#EmailDivId').hide();
					$('#POSDivId').hide();	
				}
				else if(id=='email')
				{
					$('#FaxDivId').hide();
					$('#EmailDivId').show();
					$('#POSDivId').hide();	
				}
				else if(id=='pos')
				{
					$('#FaxDivId').hide();
					$('#EmailDivId').hide();
					$('#POSDivId').show();	
				}
		});	
		
		$("#fax, #email, #pos").click(function() {
			var id = $(this).attr("id");
			if(key=='fax')
				{
					$('#FaxDivId').show();
					$('#EmailDivId').hide();
					$('#POSDivId').hide();	
				}
				else if(key=='email')
				{
					$('#FaxDivId').hide();
					$('#EmailDivId').show();
					$('#POSDivId').hide();	
				}
				else if(key=='pos')
				{
					$('#FaxDivId').hide();
					$('#EmailDivId').hide();
					$('#POSDivId').show();	
				}
		});
		
		$("#del_yes, #del_no").click(function() {
			var id = $(this).attr("id");
			if(id == "del_yes") {
				$('#del_yes_div').show();
				$('#del_no_div').hide();
			} else if(id == "del_no") {
				$('#del_yes_div').hide();
				$('#del_no_div').show();			
			}
		});
		
		$("#del_yes, #del_no").click(function() {
			var id = $(this).attr("id");
			if(id == "del_yes") {
				$('#del_yes_div').show();
				$('#del_no_div').hide();
			} else if(id == "del_no") {
				$('#del_yes_div').hide();
				$('#del_no_div').show();			
			}
		});
		
		
		$("#delRad, #cusDelZon").click(function() {
			var id = $(this).attr("id");
			if(id == "delRad") {
				$('#delRadDiv').show();
				$('#cusDelZonDiv').hide();
			} else if(id == "cusDelZon") {
				$('#delRadDiv').hide();
				$('#cusDelZonDiv').show();			
			}
		});
		
		$("#uploadMenu, #uploadMenu2, #uploadMenu3").click(function() {
			var id = $(this).attr("id");
			if(id == "uploadMenu") {
				$('#uploadMenuDiv').show();
				$('#uploadMenuDiv2').hide();
				$('#uploadMenuDiv3').hide();
			} else if(id == "uploadMenu2") {
				$('#uploadMenuDiv').hide();
				$('#uploadMenuDiv2').show();
				$('#uploadMenuDiv3').hide();			
			}else if(id == "uploadMenu3") {
				$('#uploadMenuDiv').hide();
				$('#uploadMenuDiv2').hide();
				$('#uploadMenuDiv3').show();			
			}
		});
		
		$("#cash_yes, #cash_no").click(function() {
			var id = $(this).attr("id");
			if(id == "cash_yes") {
				$('#cashDivYes').show();
				$('#cashDivNo').hide();
			} else if(id == "cash_no") {
				$('#cashDivYes').hide();
				$('#cashDivNo').hide();			
			}
		});	
		
		
		$("#domain1, #domain2, #domain3").click(function() {
			var id = $(this).attr("id");
			if(id == "domain1") {
				$('#domainDiv1').show();
				$('#domainDiv2').hide();
				$('#domainDiv3').hide();
			} else if(id == "domain2") {
				$('#domainDiv1').hide();
				$('#domainDiv2').show();
				$('#domainDiv3').hide();		
			}else if(id == "domain3") {
				$('#domainDiv1').hide();
				$('#domainDiv2').hide();
				$('#domainDiv3').show();		
			}
		});	
		
		$("#domainName1, #domainName2").click(function() {
			var id = $(this).attr("id");
			if(id == "domainName1") {
				$("#domainDiv2").show();
				$("#domainDiv3").hide();
			} else if(id == "domainName2") {
				$("#domainDiv2").hide();
				$("#domainDiv3").show();			
			}
		});	

		$("#hosting_info1, #hosting_info2, #hosting_info3").click(function() {
			var id = $(this).attr("id");
			if(id == "hosting_info1") {
				$("#hosting_div1").show();
				$("#hosting_div2").hide();
				$("#hosting_div3").hide();
			} else if(id == "hosting_info2") {
				$("#hosting_div1").hide();
				$("#hosting_div2").show();
				$("#hosting_div3").hide();			
			}else if(id == "hosting_info3") {
				$("#hosting_div1").hide();
				$("#hosting_div2").hide();
				$("#hosting_div3").hide();			
			}
		});
		
		var active_tab = 1;
		$("#btn_next").click(function(){
			activate_tab(active_tab, ++active_tab);
		});
		$("#btn_previous").click(function(){
			activate_tab(active_tab, --active_tab);
		});
		$("#btn_skip").click(function(){
			$("#tab_" + active_tab).removeClass("selected visited");
			$("#tab_" + active_tab).next().removeClass("selected visited");
			$("#tab_" + active_tab).addClass("skipped");
			$("#tab_" + active_tab).next().addClass("skipped");
			activate_tab(active_tab, ++active_tab);
		});
		$("div.tile").click(function(){
			var new_tab = $(this).attr("id").substr(-1);
			if(new_tab == active_tab) return;
			activate_tab(active_tab, new_tab);
			active_tab = new_tab;
		});
		
		var is_logged_in = false;
		function activate_tab(active_tab_id, new_tab_id) {
			if(new_tab_id == 6) {
				$("#btn_next").hide();
				$("#btn_skip").hide();
				$("#btn_submit").show();
				$("#btn_previous").show();
			} else if(new_tab_id == 1) {
				$("#btn_previous").hide();
				$("#btn_skip").hide();
				$("#btn_next").show();
				$("#btn_submit").hide();
			} else {
				
				$("#btn_previous").show();
				$("#btn_next").show();
				$("#btn_skip").show();
				$("#btn_submit").hide();
			}
			console.log(active_tab_id + " = " + new_tab_id);
			$("#tab_" + active_tab_id).removeClass("selected");
			$("#tab_" + active_tab_id).addClass("visited");
			$("#tab_" + active_tab_id).next().removeClass("selected");
			$("#tab_" + active_tab_id).next().addClass("visited");
			
			$("#tab_" + new_tab_id).addClass("selected");
			$("#tab_" + new_tab_id).removeClass("visited skipped");
			$("#tab_" + new_tab_id).next().addClass("selected");
			$("#tab_" + new_tab_id).next().removeClass("visited skipped");

			$("#tab" + active_tab_id).hide();
			$("#tab" + new_tab_id).show();
		}
	});
})(jQuery);