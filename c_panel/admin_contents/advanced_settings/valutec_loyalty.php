<? 
 if(isset($_POST['submit'])) {
		
		extract($_POST);
                $Objrestaurant->terminalUserName=$terminalUserName;
		$Objrestaurant->useValutec=$useValutec;
		$Objrestaurant->locationName=$locationName;
		$Objrestaurant->merchantID=$merchantID;
		$Objrestaurant->locationID=$locationID;
		$Objrestaurant->terminalID=$terminalID;
		$Objrestaurant->clientKey=$clientKey;
		$Objrestaurant->isDoubleReward=$isDoubleReward;
		$Objrestaurant->numberofPoints=$numberofPoints;
		$Objrestaurant->rewardAmount=$rewardAmount;					 
		$Objrestaurant->rewardLevel=$rewardLevel;	
		$Objrestaurant->saveLoyalitySetting();
	 }
  include "nav.php" ?>
<div class="form_outer" >
 
   <form name="form1" method="post" action=""  >
    <table width="100%" border="0" cellpadding="4" cellspacing="0" class="zebrastrip">
    
    <tr align="left" valign="middle">
        <td><strong>Loyalty:</strong></td>
        <td>
            <input type="radio" name="useValutec" id="useValutec" value="1" <?= $Objrestaurant->useValutec=="1" ?  'checked="checked"' :'' ?> onclick="changelabels(this)"/> Valutec
          <input type="radio" name="useValutec" id="useValutec" value="2" <?= $Objrestaurant->useValutec=="2" ?  'checked="checked"' :'' ?> onclick="changelabels(this)"/> GO3
          <input type="radio" name="useValutec" id="useValutec" value="0" <?= $Objrestaurant->useValutec=="0" ?  'checked="checked"' :'' ?>/> No
          </td>
      </tr>
      
      
      <tr align="left" valign="middle">
        <td width="120"><strong>Location name:</strong></td>
        <td><input name="locationName" type="text" size="40" id="locationName" value="<?=$Objrestaurant->locationName?>"></td>
      </tr>
      <tr align="left" valign="middle">
        <td id="merchantIDnamelabel">
            <strong><?= $Objrestaurant->useValutec == "1" ? "Merchant id:" : "User Name" ?></strong>
                </td>
      
        <td><input name="merchantID" type="text" size="40" id="merchantID" value="<?=$Objrestaurant->merchantID?>"></td>
      </tr>
      <tr align="left" valign="middle">
        <td><strong>Location id:</strong></td>
        <td><input name="locationID" type="text" size="40" id="locationID" value="<?=$Objrestaurant->locationID?>">
          </td>
      </tr>
      
      <tr align="left" valign="middle">
      <td><strong>Terminal id:</strong></td>
        <td><input name="terminalID" type="text" size="40" id="terminalID" value="<?=$Objrestaurant->terminalID?>">
      </tr>
       <tr id="terminalIDnamelabel" align="left" valign="middle">
      <td width="140"><strong>Terminal User Name:</strong></td>
        <td><input name="terminalUserName" type="text" size="40" id="terminalUserName" value="<?=$Objrestaurant->terminalUserName?>">
      </tr>
       <tr align="left" valign="middle">
           <td id="clientkeynamelabel">
            <strong><?= $Objrestaurant->useValutec == "1" ? "Client key:" : "Password" ?></strong>
                </td>
        <td><input name="clientKey" type="text" size="40" id="clientKey" value="<?=$Objrestaurant->clientKey?>">
          </td>
      </tr>
      
    <tr align="left" valign="middle">
        <td><strong>Use double rewards</strong></td>
        <td><input type="radio" name="isDoubleReward" id="isDoubleReward" value="1"  /> Yes
          
          <input type="radio" name="isDoubleReward" id="isDoubleReward" value="0" checked="checked"/> No
          </td>
      </tr>
      
       <tr align="left" valign="middle">
        <td><strong> Number of points:</strong></td>
        <td><input name="numberofPoints" type="text" size="5" id="numberofPoints"  value="<?=$Objrestaurant->numberofPoints?>">
           added on card registration</td>
      </tr>
         <tr align="left" valign="middle">
        <td><strong>Rewards Levels:</strong></td>
        <td><?=$currency?><input name="rewardAmount" type="text" size="3" id="rewardAmount" value="<?=$Objrestaurant->rewardAmount?>"> earned @ <input name="rewardLevel" type="text" size="3" id="rewardLevel" value="<?=$Objrestaurant->rewardLevel?>"> Points</td>
      </tr>
     
       
     
      <tr align="left" valign="top">
        <td>&nbsp;</td>
        
        <td><input type="submit" name="submit" value=" Save "></td>
      </tr>
    </table>
  </form>
 
</div>
<script type ="text/javascript">
    function changelabels(option)
    {   
        if ($(option).val() == '1' && $(option).is(':checked'))
        {
            $("#merchantIDnamelabel").html("Merchant id:")
            $("#clientkeynamelabel").html("Client key:")
            document.getElementById('terminalIDnamelabel').style.display= "none"
        }
        else if ($(option).val() == '2' && $(option).is(':checked'))
        {
            $("#merchantIDnamelabel").html("User Name:")
            $("#clientkeynamelabel").html("Password:")
             document.getElementById('terminalIDnamelabel').style.display= ""
        }
        document.getElementById('merchantIDnamelabel').style.fontWeight = "bold"
        document.getElementById('clientkeynamelabel').style.fontWeight = "bold"
    }
</script>