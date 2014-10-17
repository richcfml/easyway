$(document).ready(function() {
	 
	/* pull menu*/
	var sign = 1;
	
	var slider=	$('section.menu_list_wrapper');
		 
		 
	if($(".slide").length>0)	{ 
		$(slider).cycle('add', $(".slide"));
		 
	}
		
	$('#menu_pull, a.submenu').click(function(e) {
			 
	  if ( $(this).is("a") ) { 
	 e.preventDefault();
		 var obj=$(this)
			  $('#sub_menu_contain').slideToggle('slow', function() { 
			  			loadProduct(obj);
						menu_down();
							$('.current_menulist').removeClass('current_menulist');
								$(obj).addClass('current_menulist');
						 }
			  
			   );
		 
	  }else{
	  
	      $('#sub_menu_contain').slideToggle('slow', menu_down);
	 
	  }
	  
		  	//Change the CSS background position value of arrow

		if(sign == 0) {
			sign = 1;
			$('.pull_arrow').css("background-position","0px 0px");
			$('.pull_txt').html('Pull Up Menu');
		} else {
			sign = 0;
			$('.pull_arrow').css("background-position","0px -22px");
			$('.pull_txt').html('Pull Down Menu');
		}
		
		
		
	});

	/* effect pull menu when window resize*/ 
	$(window).resize(function() {
		menu_down();
	});

//	menu_down();
	
	
	 
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
		 
});


function menu_down() {
	/* get height of container*/
	var containerHeight = parseInt($('.container').css('height').split('px')[0]);
	//console.log(containerHeight);
	  if(containerHeight<34) containerHeight=40;
	// Change the CSS top value of .menu_list_wrapper
	$('.menu_list_wrapper').css({
		top: (containerHeight + 1) + 'px'
	});
	 
	console.log(containerHeight);
	
	/* $('.footer_wrapper').css({
				top: (munu_height + 2) + 'px'
	});*/
	
		  
}
		
