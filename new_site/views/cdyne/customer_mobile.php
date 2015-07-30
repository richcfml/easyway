<? if(isset($_POST['phone1'])) {
 if($_POST['phone1']==''){
	 echo "alert-error";
	 }else{
	$loggedinuser->cust_phone1=$_POST['phone1'];
	$loggedinuser->savePhone();
 
	 echo "success";
	//@mysql_close($mysql_conn);	
 }
exit;
}?>
<div id="favorites" style="border:#e4e4e4 1px solid; ">
 <h2 style="background-color:#EFEFEF; padding:5px 10px 5px 10px; font-size:16px;margin:0px">Update your mobile number</h2> 
      <div style="font-size:12px; font-weight:bold;margin-bottom:10px;padding:10px">Please enter your mobile number. 
         </div>  <div class="message" style="text-align:left;display:none;"> </div> <input type="text" name="phone1" id="phone1" maxlength="60" value="<?= $loggedinuser-> cust_phone1?>"  />
           <input type="button" name="btnsave" id="btnsave"  value=" Save " />
           <p></p>
 </div>
  <div style="float:right;right:25px; bottom:20px; position: absolute;">
	<input id="close" type="image" src="<?=$SiteUrl?>images/closelabel.gif"  onclick="$(document).trigger('close.facebox');"/>
</div>
 <script src="../js/mask.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	 $('#phone1').mask("(999) 999-9999? x99999");
	  $('#btnsave').click(function(){
		    $.post("?mod=resturants&item=reordermobile&ajax=1",{phone1:$("#phone1").val()},function(data){
				var msg=$(".message");
				$(msg).attr('class','')
				  $(msg).addClass('message');
				 $(msg).addClass(data);
				 
				if(data=='success'){
					$(msg).html("Phone number updated successfully!");
					 $(msg).show(400,function(){
					  setTimeout(function(){$(document).trigger('close.facebox')},900);		
					 	 });	
				}else {
				 $(msg).html("Please enter your phone number!");
				  $(msg).show();	
				}
				 
				
			  }
		  
		  )
		  
		  });
	  
});
</script>