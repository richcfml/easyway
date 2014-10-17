<?
	/*$mysql_conn = mysql_connect("localhost","root","");
	mysql_select_db("onlineorderingsystem",$mysql_conn);*/
	if($_POST) {
		$textbox_array = $_POST['textbox'];
		for($i = 0 ; $i<count($textbox_array); $i++ ) {
			if($textbox_array[$i] != "") 
				mysql_query("INSERT INTO mailing_list (email, resturant_id) VALUES('$textbox_array[$i]','".$mRestaurantIDCP."')") ;
		}	
	}
?>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script> 
<script type="text/javascript">
 
$(document).ready(function(){
 
    var counter = 2;
 
    $("#addButton").click(function () {
 
	if(counter>10){
            alert("Only 10 textboxes allowed");
            return false;
	}   
 
	var newTextBoxDiv = $(document.createElement('div'))
	     .attr("id", 'TextBoxDiv' + counter);
 
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
  });
</script>
<div id="main_heading">Add Mail</div>
   <div class="form_outer">
    <form method="post"  action="" id="frm" name="frm">
      <div id='TextBoxesGroup'>
          <div id="TextBoxDiv1">
              <strong>Email 1 :</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='textbox' id='textbox1' name="textbox[]" size="30"  >
          </div><br />
      </div>
      <div style="margin-left:84px;">
        <input type='button' value='Add More' id='addButton'>
        <!--<input type='button' value='Remove' id='removeButton'>-->
        <input type='submit' value='Submit' id='submit'>
      </div>
    </form>
</div>
