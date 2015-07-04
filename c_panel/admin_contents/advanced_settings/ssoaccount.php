<script type="text/javascript">
    $(document).ready(function()
    {
        var reseller_id = $('#reselelr').val();
        getclients(reseller_id,'add_resturant',$('#owner_id').val());
    });
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
function getclients( resellerId, pageName,owner_name ) {		
		var milliseconds = (new Date).getTime();
		var strURL="admin_contents/resturants/tab_find_clients.php?resellerId="+resellerId+"&pageName="+pageName+"&owner_name="+owner_name+"&"+milliseconds;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('client_div').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}	
	}
        
        $(document).ready(function()
        {
            $("#submitButton").click(function(event)
            {
               var type = '<?php echo $_SESSION['admin_type']?>'
               if(type =="admin") 
               {
                   if($("#name").val() == "")
                   {
                       event.preventDefault();
                       $("#msg_done").show().text("Please enter sso acccount name.");
                       return false;
                   }
                   else if($("#reselelr").val() == "-1")
                   {
                       event.preventDefault();
                       $("#msg_done").show().text("Please select reseler.")
                       return false;
                   }
                   else if($("#owner_name").val() == "-1")
                   {
                       event.preventDefault();
                       $("#msg_done").show().text("Please select owner.")
                       return false;
                   }
               }
               else if(type == "reseller") 
               {
                   if($("#name").val() == "")
                   {
                       event.preventDefault();
                       $("#msg_done").show().text("Please enter sso acccount name.");
                       return false;
                   }
                   else if($("#owner_name").val() == "-1")
                   {
                       event.preventDefault();
                       $("#msg_done").show().text("Please select owner.")
                       return false;
                   }
               }
               else if(type == "store owner") 
               {
                   if($("#name").val() == "")
                   {
                       event.preventDefault();
                       $("#msg_done").show().text("Please enter sso acccount name.");
                       return false;
                   }
               }
            });
        });
</script>
<?php
if (isset($_POST['submitButton'])){
    extract($_POST);
    
    $sqlResult = mysql_query("SELECT name FROM bh_sso_accounts WHERE name ='" .prepareStringForMySQL($name). "'");
    if(mysql_num_rows($sqlResult) > 0) {
		 $errMessage = "SSO account name already exists. Please select another.";
    }else{
        if($_SESSION['admin_type']=="admin"){
            $created_by=1;
            $reseller_id = $reselelr;
            $owner_id = $owner_name;
        }elseif($_SESSION['admin_type']=="reseller"){
            $created_by=2;
            $reseller_id = $_SESSION['owner_id'];
            $owner_id = $owner_name;
        }elseif($_SESSION['admin_type']=="store owner"){
            $created_by=3;
            $owner_id = $_SESSION['owner_id'];
        }

        $mAPIKey = substr(str_shuffle(str_repeat("23456789abcdefghijkmnpqrstuvwxyz", 10)), 0, 10);
        $queryInsertSSO = "INSERT INTO bh_sso_accounts
                                    SET name= '".prepareStringForMySQL($name)."'
                                    ,api_key= '".prepareStringForMySQL($mAPIKey)."'
                                    ,reseller_id='".prepareStringForMySQL($reseller_id)."'
                                    ,owner_id='".prepareStringForMySQL($owner_id)."'
                                    ,created_by = '".$created_by."'";
        $ssoid = mysql_query($queryInsertSSO);
        if($ssoid > 0)
        {?>
            
            <script language="javascript">
				window.location="./?mod=apikey";
                </script>
        <?php
        
        }
    }
}
?>
<div id="main_heading">Add SSO Account</div>
<?php if ($errMessage != "" ) { ?>
<div class="msg_done">
  <?=$errMessage?>
</div>
<?php }?>
<div class="msg_done" id="msg_done" style="display:none"></div>
<div class="form_outer">
  <form method="post" action="" enctype="multipart/form-data" id="userRegFrm" name="userRegFrm">
    <table width="570" border="0" cellpadding="4" cellspacing="0" >
      <tr align="left" valign="top">
        <td width="160">SSO Account Name:</td>
        <td width="394"><input name="name" type="text" size="40" value="<?=@$name?>" id="name" />
        </td>
      </tr>
      
      <?php if ( $_SESSION['admin_type'] == 'admin' ) {?>
          <tr align="left" valign="top"> 
            <td width="254">Reseller Name:</td>
            <td>
            <select name="reselelr" id="reselelr" style="width:270px;" onChange="getclients(this.value,'add_resturant')" >
              <option value="-1">Select Reseller</option>
               <?=resellers_drop_down(@$reselelr) ?>
            </select>
             </td>
          </tr>
          <tr  align="left" valign="top"> 
            <td width="254">Owner's Name:</td>
            <td id="client_div">
            <select name="owner_name" id="1" style="width:270px;">
              <option value="-1">Select Restaurant Owner</option>
		<?=client_drop_down(@$owner_name) ?>
            </select>
             </td>
          </tr>
		  <?php }?>
		   <?php if( $_SESSION['admin_type'] == 'reseller' ) {?>
           <tr align="left" valign="top"> 
            <td width="254">Owner's Name:</td>
            <td>
            <select name="owner_name" id="owner_name" style="width:270px;">
              <option value="-1">Select Restaurant Owner</option>
               <?=client_drop_down(@$owner_name) ?>
            </select>
             </td>
          </tr>
          <?php }?>   

      <tr align="left" valign="top">
        <td width="160"></td>
        <td><input name="submitButton" Type="submit"  value="Submit" tabindex="1" id="submitButton" />
        </td>
      </tr>
    </table>
      <input type="hidden" name="owner_id" id ="owner_id" value="<?= @$owner_name ?>">
  </form>
</div>