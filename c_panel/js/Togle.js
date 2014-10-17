
 
	$(document).ready(function() {

	$('#cmdsearch').click(function(){
		 if($.trim($("#searchtext").val())=='')
				return false;
		  else 
			    return true;
			});
			
			
// place holder was not working with this code
		
// $('[placeholder]').focus(function() {
//	  var input = $(this);
//	   if (input.val() == input.attr('placeholder')) {
//		input.val('');
//		input.removeClass('placeholder');
//	  }
//	}).blur(function() {
//	  var input = $(this);
//	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
//		input.addClass('placeholder');
//		input.val(input.attr('placeholder'));
//	  }
//	}).blur().parents('form').submit(function() {
//	  $(this).find('[placeholder]').each(function() {
//		var input = $(this);
//		if (input.val() == input.attr('placeholder')) {
//		  input.val('');
//		}
//	  })
//	});
	
  $('.dropdownhead').mouseover(function() {
			$(".dropdownhead").attr("style", "cursor:pointer");											 
		});
  	   $('.dropdownhead').click(function() {
		   	//$(".dropdownopt").attr("style", "display:block; cursor:pointer;float:none;");
			if( $(".dropdownopt").css("display")=='none' ){
			//	$(".dropdownopt").show('slide', {direction: 'right'}, 1000);
				 		
				$(".dropdownopt").show("slow", function() {
					$(".dropdownopt").attr("style", "display:block; cursor:pointer;;");
				  });
				
			}else{
			 				 
				$(".dropdownopt").hide("slow", function() {
				  	$(".dropdownopt").attr("style", "display:none;");		
				  });
				
			}
		});

 	$(".dropdownopt .opt_container").hover(
		  function () {
			$(this).addClass("dhover");
		  }, 
		  function () {
			 if($(this).attr("selected")!=1)
				$(this).removeClass("dhover");
		  }
		);
 
 
	   $('.dropdownopt .opt_container').click(function() {
 
			setselected($(this).attr('id'),$(this).children(".opt_date").html(),$(this).children(".opt_wd").html(),$(this).attr('reload'))
			if( $(".dropdownopt").css("display")=='none' ){
				$(".dropdownopt").attr("style", "display:block; cursor:pointer;");
			 
			}else{
				$(".dropdownopt").attr("style", "display:none;");											 
			}
				$(".dhover").attr("selected",0);
				$(".dhover").removeClass("dhover");
			//	$(".dhover").each(function() {$(this).attr("selected",0));
				$(this).attr("selected",1);
				$(this).addClass("dhover");
		});
		
});
 
  
  function toggle_visibility(id) {

     var e = document.getElementById(id);
	if(e){
    	   if(e.style.display == 'block')

       	   e.style.display = 'none';

      else

	 	e.style.display = 'block';
	  }
	



    }

	

  function Hide(id){

    var obj=document.getElementById(id);

    var evt=window.event||arguments.callee.caller.arguments[0];

    var eobj=window.event?evt.srcElement:evt.target;

    if(eobj.nodeType==3) eobj=eobj.parentNode;

    while (eobj.parentNode){

      if (eobj==obj) return;

        eobj=eobj.parentNode;

    }
if(document.getElementById('itemtosearch'))
     document.getElementById('itemtosearch').style.display='none';

  }



document.onclick=function(){ Hide('top_nav_bg'); }

function SelectDropdownValue(showval,adtypetosearch){



  	document.getElementById('search_by_bg').innerHTML = showval;
 	document.getElementById('adtypetosearch').value = adtypetosearch;
 
	document.getElementById('itemtosearch').style.display = "none";

}


function CheckSearchTypes(adtypetosearch){
 
if(adtypetosearch=="Jobs")
 {
	  $("#rdJobs").attr('checked', 'checked');
	   $("#adtypetosearch").val("Jobs");
	 
 }

else {
	  $("#rdTenders").attr('checked', 'checked');
	  $("#adtypetosearch").val("Tenders");
	}


 
}

function refinesearh(title,adtype,paperid,catid,postdate,pageindex) {
 
	 	 	$("#searchcontents").html("<div class='msg_alert' style='width:550px'><div style='float:left'><img src='../images/loading.gif' /></div><div style='float:left;padding-left:20px;padding-top:5px'> Please wait ...</div><br style='clear:both'/> </div>");

 $.post("/index2.php?mod=refinesearch", {title:title,adtypetosearch:adtype,paperid:paperid,catid:catid,postdate:postdate,pageindex:pageindex},function(data) {
		 	$("#searchcontents").html(data);
	 
		 }); 
		
}

function moresearchresults(title,adtype,paperid,catid,postdate,totalpages) {
 
	  var imageurl='../images/loading.gif';
     
  	 $("#ad_list_meessage").html("<div style='margin:0px;'><img src=" + imageurl +" /></div>");
	 var page_index=$("#page_index").val();
	 page_index=parseInt(page_index)+1;
 
		
 $.post("/index2.php?mod=moresearchresults",{title:title,adtypetosearch:adtype,paperid:paperid,catid:catid,postdate:postdate,pageindex:page_index},function(data) {
	 	 $("#ad_list_meessage").html("");
		$("#search_results").append(data);
  	    
		$("#page_index").val(page_index);
	 if(page_index >=totalpages){
			$("#ajax_more").toggle(200);
		}
		
	}); 
	
		
}
 function togglehandler(hadler,list){
 
    $("#" + list).toggle(500);
	$('#' + hadler).toggleClass('itemopened');
	$('#' + hadler).toggleClass('itemclosed');
	
////////////////////////////////////////////////////////

//var counter = 1;
//while(counter <= 10){
//	
//
//	}
	
}
////////////////////////////////////////////////////////////////  
   function toggle(hadler){
 
    $("#" + hadler).toggle(500);

  }
    function dialog(hadler,width,height){
 
    $("#" + hadler).dialog({
			modal: true,
			width: width,
			height:height,
			resizable: false,
			closeOnEscape:false,
			show: "blind",
			hide: "blind"
		 
		});

  }
  
  function setselected(slink){
	 var $allListElements = $('li').removeClass("selected");
	 $("#"+slink).addClass("selected");
	 
	 }


function listcategories(container,subscribe_adtype) {
	
	imageurl='/images/loading.gif';
 document.getElementById(container).innerHTML="<div class='msg_alert'><div style='float:left'><img src=" + imageurl +" /></div><div style='float:left;padding-left:20px;padding-top:0px'> Please wait ...</div><br style='clear:both'/> </div>";
 
 $.post("/index2.php?mod=adcategories", {subscribe_adtype:subscribe_adtype},function(data) {
		 	document.getElementById(container).innerHTML=data;
		 }); 
			
			
}

function subscribeuser(url) {
 
 
    if ($.trim($("#username").val())=="")
  {
	  alert("Please enter your name.");
	   $("#username").addClass("txtmsg_error");
	   return;
	   
  }
  else {
	   $("#username").removeClass("txtmsg_error");
	  }
	  
	  
	   var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
   if ( $.trim($("#email_address").val())=="")
  {
	  alert("Please enter your email address.");
	   $("#email_address").addClass("txtmsg_error");
	   return;
	   
  }
  else if(!emailReg.test( $.trim($("#email_address").val()))) {
	 	  alert("Please enter valid email address.");
            $("#email_address").addClass("txtmsg_error");
            return;
        }
  else {
		
	   $("#email_address").removeClass("txtmsg_error");
	 }
	 
 var $categories = $('input[type=checkbox]');
 
  if($categories.filter(':checked').length==0)
  {
	  alert("Please select atleast one category to subscribe.");
	    return;
  }
  
  var imageurl=url+'/images/loading.gif';
  	 $("#message").html("<div class='msg_alert' style='margin:0px'><div style='float:left'><img src=" + imageurl +" /></div><div style='float:left;padding-left:20px;padding-top:0px'> Please wait ...</div><br style='clear:both'/> </div>");
	
    $("#subscribe_form").toggle();
    $("#message").toggle();
		 
  $.post("/index2.php?mod=savesubscribeusers",$("#subscribeform").serialize(),function(data) {
	 
		 	 $("#message").html(data);
			if(data.search("successfully")>-1){
				  var closedialog = setTimeout (function(){ $("#subscribe_form").toggle();
					    $("#message").toggle();
						$("#subscribenow").dialog("close");}, 3000);
				}
				else {
					   $("#subscribe_form").toggle();
					    $("#message").toggle();
					}
			 
		 }); 
			
			
}


function submitfeedback(url) {
 
 
    if ($.trim($("#feedback_username").val())=="")
  {
	  alert("Please enter your name.");
	   $("#feedback_username").addClass("txtmsg_error");
	   return;
	   
  }
  else {
	   $("#feedback_username").removeClass("txtmsg_error");
	  }
	  
	  
	   var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
   if ( $.trim($("#feedback_email_address").val())=="")
  {
	  alert("Please enter your email address.");
	   $("#feedback_email_address").addClass("txtmsg_error");
	   return;
	   
  }
  else if(!emailReg.test( $.trim($("#feedback_email_address").val()))) {
	 	  alert("Please enter valid email address.");
            $("#feedback_email_address").addClass("txtmsg_error");
            return;
        }
  else {
		
	   $("#feedback_email_address").removeClass("txtmsg_error");
	 }
	 
 
   if ($.trim($("#feedback_message").val())=="")
  {
	  alert("Please enter your feedback.");
	   $("#feedback_message").addClass("txtmsg_error");
	   return;
	   
  }
  
  var imageurl=url+'/images/loading.gif';
  	 $("#feedback_message_alert").html("<div class='msg_alert' style='margin:0px'><div style='float:left'><img src=" + imageurl +" /></div><div style='float:left;padding-left:20px;padding-top:0px'> Please wait ...</div><br style='clear:both'/> </div>");
	
    $("#feedbackform").toggle();
    $("#feedback_message_alert").toggle();
		 
  $.post("/index2.php?mod=savefeedback",$("#feedback_form").serialize(),function(data) {
 
		 	 $("#feedback_message_alert").html(data);
			 
			 
			  var closedialog = setTimeout (function(){
				    $("#feedbackform").toggle();
   					 $("#feedback_message_alert").toggle();
					 $("#feedbackbox").dialog("close");
				  
				  }, 3000);
			 
			 
		 }); 
			
			
				
}


function addCss(handler,cssclass){
 
	 $("'#" +handler +"'").addClass(cssclass);
	}


function Popupdetail(imagepath,title){
	$("#ad_detail").html("<img style=\"max-width:600px;\" title='"+ title +"'   src='"+ imagepath  +"'/>");
	 dialog("ad_detail",'auto',850) ;
		
 }
 
 
 
 
 function submitcomment(url,ad_id) {
 
 
    if ($.trim($("#comments_user_name").val())=="")
  {
	  alert("Please enter your name.");
	   $("#comments_user_name").addClass("txtmsg_error");
	   return;
	   
  }
  else {
	   $("#comments_user_name").removeClass("txtmsg_error");
	  }
	  
	  
	   var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
   if ( $.trim($("#comments_user_email_address").val())=="")
  {
	  alert("Please enter your email address.");
	   $("#comments_user_email_address").addClass("txtmsg_error");
	   return;
	   
  }
  else if(!emailReg.test( $.trim($("#comments_user_email_address").val()))) {
	 	  alert("Please enter valid email address.");
            $("#comments_user_email_address").addClass("txtmsg_error");
            return;
        }
  else {
		
	   $("#comments_user_email_address").removeClass("txtmsg_error");
	 }
	 
 
   if ($.trim($("#comments_message").val())=="")
  {
	  alert("Please enter your message.");
	   $("#comments_message").addClass("txtmsg_error");
	   return;
	   
  }
  
  var imageurl=url+'/images/loading.gif';
  	 $("#user_message_alert").html("<div class='msg_alert' style='margin:0px;'><div style='float:left'><img src=" + imageurl +" /></div><div style='float:left;padding-left:20px;padding-top:0px'> Please wait ...</div><br style='clear:both'/> </div>");
	
     
		 
 $.post("/index2.php?mod=savecomments",{ad_id:ad_id,user_name:$("#comments_user_name").val(),user_email:$("#comments_user_email_address").val(),comment:$("#comments_message").val()},function(data) {
	 $("#user_message_alert").toggle(500);
  			 $("#user_message_alert").html(data);
			 
		var totalcomments=$("#comments_count").val();
	
		totalcomments=parseInt(totalcomments)+1;
		
		$("#comments_count").val(totalcomments);
	
		var classstyle="commenttext";
		
		if(totalcomments % 2 ==0) classstyle="alternatecommenttext";
		
		$("#comment_list").append("<div class='"+ classstyle +"'> <cite><strong>" + $("#comments_user_name").val()  +"</strong> said: </cite><p>"+ $("#comments_message").val() +"</p></div><div class='commentmetadata'>" + dateFormat(new Date(), "dddd, mmmm dS, yyyy") +" </div>");

		$("#comments_user_name").val("");
		$("#comments_user_email_address").val("");
		$("#comments_message").val("");
	
	
		 var hidemessage = setTimeout (function(){ $("#user_message_alert").toggle(500); }, 3000);
						
			 
		 }); 
 	
}



 
 function moreresults(sender,url,paper_id,tabval,size,start_date,end_date,totalpages) {
   var imageurl=url+'images/loading.gif';
    
  	 $("#ad_list_meessage").html("<div style='margin:0px;'><img src=" + imageurl +" /></div>");
	 var page_index=$("#page_index").val();
	 page_index=parseInt(page_index);
     var first_limit=page_index*size;
		
 $.post("/index2.php?mod=moreresults",{tabval:tabval,paper_id:paper_id,first_limit:first_limit,size:size,start_date:start_date,end_date:end_date},function(data) {
	   
	 	 $("#ad_list_meessage").html("");
		 
		 $("#ad_list_container").append(data);
  	     page_index=page_index+1;
		$("#page_index").val(page_index);
	 if(page_index >=totalpages){
			$("#ajax_more").toggle(200);
		}
		
	}); 
 	
}

 function moreeditorials(sender,url, paper_id,writer_id,size,start_date,end_date) {
   var imageurl=url+'images/loading.gif';
    
  	 $("#ad_list_meessage").html("<div style='margin:0px;'><img src=" + imageurl +" /></div>");
	 var page_index=$("#page_index").val();
	 page_index=parseInt(page_index);
     var first_limit=page_index*size;
		
 $.post("/index2.php?mod=moreeditorials",{paper_id:paper_id,writer_id:writer_id,first_limit:first_limit,size:size,start_date:start_date,end_date:end_date},function(data) {
		
		$("#ad_list_meessage").html("");
		$("#ad_list_container").append(data);
		
		page_index=page_index+1;
		$("#page_index").val(page_index);
		if(data.length<=5){
			$("#ajax_more").toggle(200);
	 	}
	}); 
 	
}

function sitedatechanged(seleceddate) {
	if(seleceddate=="-") return ;
	else window.location="?sday="+seleceddate;
	
	}

function refreshhome(seleceddate) {
 var imageurl='/images/loading.gif';
     $("#home_newspapers").html("<div style='margin:0px;'><img src=" + imageurl +" /></div>");
	 
  $.post("/index2.php?mod=refreshhome",{sday:seleceddate},function(data) {
	 	 
		 $("#home_newspapers").html("");
		 $("#home_newspapers").append(data);
  		}); 
	
	}
function dialog_subscribe(){
 
 	dialog('subscribenow',850,600);
	$('#subscribe_adtype').val(1);
	listcategories('listing',1)
	
	}
 
 function setHomepage()
{
 if (document.all)
    {
        document.body.style.behavior='url(#default#homepage)';
  document.body.setHomePage('http://papers.com.pk/');

    }
    else if (window.sidebar)
    {
    if(window.netscape)
    {
         try
   {  
            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
         }  
         catch(e)  
         {  
            alert("this action was aviod by your browserï¼Œif you want to enableï¼Œplease enter about:config in your address line,and change the value of signed.applets.codebase_principal_support to true");  
         }
    } 
    var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components. interfaces.nsIPrefBranch);
    prefs.setCharPref('browser.startup.homepage','http://papers.com.pk/');
 }
}

/**********************get_detail_classifiedcat*********************************/
function get_cat_classifieds(catid,page,city_id,province_id,pageindex) {

  
	$('.msg_error').hide();	 
	$("#msg_alert").html("<div class='msg_alert'> <img src='/images/loading.gif' />  Please wait..</div>");	 
	$.post("/index2.php?mod=classifieddetails",{catid:catid,page:page,city_id:city_id,province_id:province_id,pageindex:pageindex}, function(data) {

			$("#msg_alert").html("");
			$("#classified_list").html(data);
			scroll(0,0);
			//alert(data);
		}); 
	}

/******************************comments_validate********************************************/
function comments_validate() {
 
 
    if ($.trim($("#name").val())=="")
  {
	  alert("Please enter your name.");
	   $("#name").addClass("txtmsg_error");
	   $("#name").focus();
	   return false;
	   
  }
  else {
	   $("#name").removeClass("txtmsg_error");
	  }
	  
	  
	   var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
   if ( $.trim($("#email").val())=="")
  {
	  alert("Please enter your email address.");
	   $("#email").addClass("txtmsg_error");
	   $("#email").focus();
	   return false;
	   
  }
  else if(!emailReg.test( $.trim($("#email").val()))) {
	 	  alert("Please enter valid email address.");
            $("#email").addClass("txtmsg_error");
			$("#email").focus();
            return false;
        }
  else {
		
	   $("#email").removeClass("txtmsg_error");
	 }
	 
 
   if ($.trim($("#comments").val())=="")
  {
	  alert("Please enter your comments.");
	   $("#comments").addClass("txtmsg_error");
	   $("#comments").focus();
	   return false;
	   
  }
  
}

function lookuplocation(inputString) {

	if(inputString.length == 0) {
	//$("input[type=submit]").removeAttr("disabled", "disabled");
	$('#suggestions').hide();
	}else{
	 
		$.post("/index2.php?mod=suggestlocation", {inputString: ""+inputString+""}, function(data){
		if(data.length >0){

		// $("input[type=button]").attr("disabled", "disabled");
			$('#autoSuggestionsList').html(data);
			$('#autoSuggestionsList').attr('selected','selected');
			$('#suggestions').show();	
			
			}
		});
	}
} //end

function lookupcolumns(inputString) {

	if(inputString.length == 0) {
 		$('#suggestions').hide();
	}else if(inputString.length>=3 ){
	 
		$.post("/index2.php?mod=suggestcolumn", {inputString: ""+inputString+""}, function(data){
		if(data.length >0){

		// $("input[type=button]").attr("disabled", "disabled");
			$('#autoSuggestionsList').html(data);
			$('#autoSuggestionsList').attr('selected','selected');
			$('#suggestions').show();	
			
			}
		});
	}
} //end


// if user clicks a suggestion, fill the text box.
function fill(thisValue) {
		//$("input[type=button]").removeAttr("disabled", "disabled");
		$('#search_location').val(thisValue);
		$("#search_location").focus();
		setTimeout("$('#suggestions').hide();", 200);

}

/////////////////////handlespecialchars////////////////////////////////////////////
function handlespecialchars(txt){

		txt = txt.split(' ').join('-'),txt;
		txt = txt.split('/').join('-'),txt;
		txt = txt.split(',').join('-'),txt;
		txt = txt.split(',').join('-'),txt;
		txt = txt.split(',').join('-'),txt;
		txt = txt.split(':').join('-'),txt;
		txt = txt.split('.').join('-'),txt;
		txt = txt.split('"').join('-'),txt;
		txt = txt.split('>').join('-'),txt;
		txt = txt.split('<').join('-'),txt;
		txt = txt.split(' \ ').join('-'),txt;
		txt = txt.split('//').join('-'),txt;
		txt = txt.split('------').join('-'),txt;
		txt = txt.split('-----').join('-'),txt;
		txt = txt.split('----').join('-'),txt;
		txt = txt.split('---').join('-'),txt;
		txt = txt.split('--').join('-'),txt;
		txt = txt.split('@').join('-'),txt; 
	return txt;
	 }
	 
 //////////////////////////////////new work//////////////////////////// 
function lessMore_cities(){
  var more_cities = $('#link').text();

	 if(more_cities == "More cities"){
		$("#link").html("Less cities"); 
	}else if(more_cities == "Less cities"){
		$("#link").html("More cities"); 
	}
}
function setselected(unixdate,textdate,dayname,_reload) {
	$("#dropdownmenu .dropdownhead .opt_container").attr("id",unixdate);
	$("#dropdownmenu .dropdownhead .opt_container .opt_date").html(textdate);
	$("#dropdownmenu .dropdownhead .opt_container .opt_wd").html(dayname);
	if(_reload==0) {sitedatechanged(unixdate)
		}else{refreshhome(unixdate);}
	
	}
///////////////////////hide_cities/////////////////////////////////////////////

/*function hide_cities(){
	$('#city_list').hide();
}*/