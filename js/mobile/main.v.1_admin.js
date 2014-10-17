  if (top.location != location) {
    top.location.href = document.location.href ;
  }
  
$(document).ready(function() {
	
	    var counter = 2;
 
    $("#addButton").click(function () {
 
	if(counter>10){
            alert("Only 10 textboxes allowed");
            return false;
	}   
 
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
 
	newTextBoxDiv.after().html('<strong>Email '+ counter + ' : </strong>&nbsp;&nbsp;&nbsp;&nbsp;' +
	      '<input type="text" name="textbox[]" id="textbox' + counter + '" value=""  size="30" ><br /><br />');

	newTextBoxDiv.appendTo("#TextBoxesGroup");
	counter++;
     });
 
     $("#removeButton").click(function () {
	if(counter==1){
          alert("No more textbox to remove");
          return false;
       }   
	counter--;
        $("#TextBoxDiv" + counter).remove();
     });
     $("#getButtonValue").click(function () {
 
	var msg = '';
	for(i=1; i<counter; i++){
   	  msg += "\n Textbox #" + i + " : " + $('#textbox' + i).val();
	}
    	  alert(msg);
     });
 function loadProduct(object) {
			 
				var category_id= $(object).attr('category_id')
				if($("#menu_"+category_id).length>0)
				{
					var current_slide=$("#menu_"+category_id);
					$(slider).cycle('goto', $(current_slide).attr('slide_index'));
					
				}
				else{
					var count = $(slider).children('div.slide').length;
			        $('div.modal-backdrop').show();
					 
					var url= $(object).attr('href') +'&ajax=1&counter='+count;
					$.ajax({
					url: url,
					success: function(data){
						 setupCycle(data,count);
						 $('div.modal-backdrop').fadeOut(500);
					 
					}
				 });  
				
				}
}
	 
	 function setupCycle(data,index) {
		    $(slider).cycle('add', data);
			 $(slider).cycle('goto', index);
	 	 }
		 
		 $(".action").live('click',function (e) {
		if(confirm($(this).attr('data-confirm'))) 
		{ 
 			var actionurl="ajax.php"+ $(this).attr("id") +"&status=" +$(this).attr('data-status');
			var status=$(this).attr('data-status');
			var object=$(this);
			$.get(actionurl,function(data) {
					if(status ==1)
						{
							$(object).attr('data-status',0);
							
						}
						else{
								$(object).attr('data-status',1);
							}
						
						if( $(object).attr('data-childs')==1){
							toggleactiveInactiveStatus($(object));
						}
						else{
							
							 if($(object).attr('data-status')==0){
								 $(object).children(1).children(1).children(1).attr('src','../images/enable.png');
								$(object).children(1).children(1).children(1).attr('title','Enabled');
							 }else{ 
								 $(object).children(1).children(1).children(1).attr('src','../images/disable.png');
								 $(object).children(1).children(1).children(1).attr('title','Disabled');
							 }
							}		
				});
			e.preventDefault();
			$('#listview').listview('refresh');
			}
		});
	 
});
function openpopup(url){
	//alert(url);
		$('#delete').attr('href',url);
		$("#popupDialog").popup("open");
		$("#mylistview").listview("refresh");
	}
function attribute(catid,sub_cat,pid){
	$('#product_id').val(pid);
	$('#category_id').val(catid);
	$('#sub_cat_id').val(catid);
	$("#popupform").popup("open");
}

function toggleactiveInactiveStatus(trigger) {
		 
		$(trigger).parent().find('img[data-icon="1"]').each(function(index){
			 if($(trigger).attr('data-status')==0){
					 $(this).attr('src','../images/enable.png');
					 $(this).attr('title','Enabled');
				 }else{ 
					 $(this).attr('src','../images/disable.png');
					 $(this).attr('title','Disabled');
				 }
			
			
			});
		
		}
		
		var check=0;
function popup(element,coupenid){
	var items = [];
	var url=$(element).attr('id');
    $.getJSON(url, function(data) {
		var ids=data.copenid;
		var checked="";
		$.each(data.item, function(key,val) {
			checked="";
			if(jQuery.inArray(key, ids)!=-1){
				checked="checked";
			}
		items.push('<li> \
					<label for="'+key+'">'+val+' \
					<input type="checkbox" name="itemcheck[]" id="'+ key +'" value="'+ key +'~'+ val +'" '+ checked +'> \
					</label> \
					</li>');
		});
	$('#formlist').html(items.join(''));
	$('#save').attr('onclick','saveSelectedItems('+coupenid+')');
	$("#popupform input[type='checkbox']").checkboxradio({ theme: "c" });
	if(check==0){
	$('#formlist').listview();
		check=1;
	}else{
		$('#formlist').listview('refresh');
	}
	$("#popupform").popup();
	$("#popupform").popup('open');
	});
}
		
function saveSelectedItems(couponItem){
	var count = 0;
	var selectedItems=""
	var selectedItemTitles=""
	var selectedItemIds=""
	var form = document.forms['frmpopup'];
	var element = form.elements['itemcheck[]'];
	for(var i=0; i < element.length; i++){
	if(element[i].checked) {
	  count+= 1;
		var valArr = element[i].value
		var itemArr = valArr.split("~");

		selectedItemTitles += itemArr[1]+","; 
		selectedItemIds += itemArr[0]+","; 
		
	}

	}
	if( couponItem == 1 ) {
		document.getElementById('selitems1').innerHTML = selectedItemTitles;
		document.getElementById('coupon_items1').value = selectedItemIds;
		
	} else if( couponItem == 2 ) {
		document.getElementById('selitems2').innerHTML = selectedItemTitles;
		document.getElementById('coupon_items2').value = selectedItemIds;
	} else if( couponItem == 3 ) {
		document.getElementById('selitems3').innerHTML = selectedItemTitles;
		document.getElementById('coupon_items3').value = selectedItemIds;
	}
	$( "#popupform" ).popup( "close" )
	$('#list').listview('refresh');
}

		
