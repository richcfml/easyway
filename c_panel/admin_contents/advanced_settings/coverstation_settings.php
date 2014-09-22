<style>
a.actionlinks {
	text-decoration: none;
	color: #545454;
	font-size: 12px;
	font-weight: bold;
}
a.actionlinks:hover {
	color: #C00;
}
.titleblue {
	text-decoration: none;
	color: #6D82E2;
	font-size: 14px;
	font-weight: bold;
}
.addlink {
	background: no-repeat url(../images/green_plus_sign.gif);
	padding-left:18px;
	height:24px;
	font-size: 12px;
}
.textcontents {
	font-size: 12px;
}
.box {
	-moz-box-shadow: 10px 10px 5px #888;
	-webkit-box-shadow: 10px 10px 5px #888;
	box-shadow: 10px 10px 5px #888;
	width:400px;
	height:350px;
	padding:10px;
	background-color:rgba(0, 0, 0, 0.8);
}
.box .heading {
	color:#FFF;
	font-size:18px;
	letter-spacing:5px;
	text-align:center;
	margin-bottom:25px;
}
.box .heading1 {
	color:#FFF;
	font-size:18px;
	text-align:left;
	float:left;
	width:50px;
}
.box .textarea {
	float:left;
}
.box .textarea input {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	width:300px;
	text-align:left;
}
.box .textarea input {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	width:300px;
}
.box .textarea textarea {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}
</style>
<script type="text/javascript">
$(function () {
	$(".edit").click(function(){
			var cellAndRow = $(this).parents('td,tr');
			var tableid=$(this).parents('table').attr("id");
			
			var cellIndex = cellAndRow[0].cellIndex
			var rowIndex = cellAndRow[1].rowIndex + 1;
			var currenttd=$('table#'+ tableid +' > tbody > tr:nth-child('+rowIndex+')> td:nth-child(2)');
		 	
			$(".box #hiddenid").val($(currenttd).find('#hiddenid').val());
			$(".box #hiddentype").val($(currenttd).find('#hiddentype').val());
			$(".box #title").val($.trim($(currenttd).find('.titleblue').html()));
			$(".box #htmlcode").html($(currenttd).find('#htmlcode').html());
			$(".box").show();
			 $(".box #title").focus();
		 
	 	});	
		
		$(".add").click(function(){
			
			$(".box #hiddenid").val('');
		 	$(".box #hiddentype").val($(this).attr("type"));
			$(".box #title").val('');
			$(".box #htmlcode").html('');
			
		 		
				$(".box").show(); 
		 
	 	});	
			$(".cancelbutton").click(function(){
				$(".box").hide(); 		 
	 	});
		
	});
</script>
<? 

require_once "../classes/trackers.php"; 


$tracker=new tracker();
 
$tracker->RestaurantId=$Objrestaurant->id;

 

if(isset($_POST['submit'])) {
	
	extract($_POST);
	$tracker->id=$hiddenid;
	$tracker->Name=$title;
	$tracker->HtmlCode=$htmlcode;
	$tracker->type=$hiddentype;
	$tracker->save();
 
	
}elseif(isset($_REQUEST['trackerId'])) {
	
	$tracker->id=$_REQUEST['trackerId'];
	$tracker->delete();
 
}

$arrData=$tracker->getAll();
 
?>
 <?  include "nav.php" ?>
<div class="form_outer" >
  <div style="float:left; width:455px">
    <table width="450px"  cellspacing="15" id="visittracker">
      <tr>
        <td colspan="4"><strong>Visit Trackers</strong></td>
      </tr>
      <? 
	foreach($arrData as $plugin) {
		if($plugin->type==1) {
	?>
      <tr>
        <td>&nbsp;</td>
        <td width="75%"><div class="titleblue">
            <?= $plugin->Name; ?>
          </div>
          <textarea style="display:none;" id="htmlcode" name="htmlcode" > <?=  stripslashes($plugin->HtmlCode); ?></textarea>
          <input type="hidden" id="hiddenid" name="id"  value="<?=  $plugin->id; ?> "/>
         <input type="hidden" id="hiddentype" name="hiddentype"  value="<?=  $plugin->type; ?> "/>
          </td>
        <td><a href="javascript:void(0);" class="actionlinks edit">Edit</a> | <a href="?mod=coverstation_settings&trackerId=<?=  $plugin->id; ?>&cid=<?=$mRestaurantIDCP?>" class="actionlinks">Delete</a></td>
      </tr>
      <? } } ?>
      <tr>
        <td colspan="4"><div class="addlink"><a href="javascript:void(0);" class="actionlinks add" type="1">Add New Visitor </a></div></td>
      </tr>
    </table>
    <table width="450px;" cellspacing="15" id="purcahsetracker">
      <tr>
        <td colspan="4"><strong>Purchase Trackers</strong></td>
      </tr>
      <? 
	foreach($arrData as $plugin) {
		if($plugin->type==2) {
	?>
      <tr>
        <td>&nbsp;</td>
        <td width="75%"><div class="titleblue">
            <?= $plugin->Name; ?>
          </div>
          <textarea style="display:none;" id="htmlcode" name="htmlcode" > <?=  stripslashes($plugin->HtmlCode); ?></textarea>
          <input type="hidden" id="hiddenid" name="id"  value="<?=  $plugin->id; ?> "/>
         <input type="hidden" id="hiddentype" name="hiddentype"  value="<?=  $plugin->type; ?> "/>
          </td>
        <td><a href="javascript:void(0);" class="actionlinks edit">Edit</a> | <a href="?mod=coverstation_settings&trackerId=<?=  $plugin->id; ?>&cid=<?=$mRestaurantIDCP?>" class="actionlinks">Delete</a></td>
      </tr>
      <? } } ?>
      <tr>
        <td colspan="4"><div class="addlink"><a href="javascript:void(0);" class="actionlinks add" type="2">Add New Tracker </a></div></td>
      </tr>
    </table>
  </div>
  <div style="float:left; border:2px solid #000; display:none;" class="box">
  <form method="post" action="?mod=coverstation_settings&cid=<?=$mRestaurantIDCP?>"> 
    <div class="heading"> ADD NEW TRACKER</div>
    <div class="heading1">Title</div>
    <div class="textarea">
      <input type="text"  name="title"  id="title"  />
      <input type="hidden"  name="hiddenid"  id="hiddenid"  />
      <input type="hidden" id="hiddentype" name="hiddentype"   />
    </div>
    <div style="clear:both;"> </div>
    <div class="textarea" style="margin-left:8px">
      <textarea   rows="15" cols="45"  id="htmlcode" name="htmlcode"  ></textarea>
    </div>
    <div  class="textarea" style="float:right;">
      <input type="submit" name="submit" value="Save" style="width:80px;">
      </input>
       <input type="button" name="cancel" value="Cancel"  class="cancelbutton" style="width:80px;">
    </div>
    <div style="clear:both;"> </div>
    </form>
  </div>
  <div style="clear:both"></div>
</div>
