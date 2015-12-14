<?php
$mDecryptedID = 0;
if (isset($_GET["id"]))
{
    if (trim($_GET["id"])!="")
    {
        $mObjFun=new clsFunctions();
        $mDecryptedID = $mObjFun->decrypt($_GET["id"], "53cr3t9455w0rd");
        $mRow = dbAbstract::ExecuteObject("SELECT * FROM customer_registration WHERE id=".$mDecryptedID);
        if ($mRow)
        {
            if ($mRow->id<=0)
            {
                redirect($SiteUrl);
            }
        }
        else
        {
            redirect($SiteUrl);
        }    
    }
}

$mStyle = "redtxt";
$mErrorMessage = "";

if (isset($_POST["btnresetpswd"]))
{
    if ($mDecryptedID!=0)
    {
        $mSalt = hash('sha256', mt_rand(10,1000000));    
        $ePassword= hash('sha256', $_POST["txtPassword"].$mSalt);
        if (dbAbstract::Update("UPDATE customer_registration SET salt='".$mSalt."', epassword='".$ePassword."' WHERE id=".$mDecryptedID))
        {
            $mStyle = "greentxt";
            $mErrorMessage = "Password changed successfully.";
        }
        else
        {
            $mStyle = "redtxt";
            $mErrorMessage = "Error occurred.";
        }
    }
}
?>
<script type="text/javascript" language="javascript">
    $(document).ready(function()
    {
        $("#resetpswdfrm").submit(function(){
			$('#error').hide();
			if ($.trim($("#rest_password").val())=="")
            {
                $("#error").text("Required");
                $("#error").show();
				$("#rest_password").css('border-color','#F00');
				return false;
            }
            else if ($.trim($("#rest_password").val()).length<5)
            {
                $("#error").text("Minimum length for password is 5");
                $("#error").show();
				$("#rest_password").css('border-color','#F00');
				return false;
            }
            else if ($.trim($("#rest_cpassword").val())=="")
            {
                $("#error").text("Required");
                $("#error").show();
				$("#rest_cpassword").css('border-color','#F00');
				return false;
            }
            else if ($.trim($("#rest_password").val())!=$.trim($("#rest_cpassword").val()))
            {
                $("#error").text("Password mismatch");
                $("#error").show();
				$("#rest_password").css('border-color','#F00');
				return false;
            }
            else
            {
                return true;
            }
		});
    });
</script>

<!--	Reset Password	-->
<div class="notification show" id="resetpassword">
  <div class=notification__box>
    <header class='notification__box-header center-text'>
      <h3 class=notification__box-title>Reset Your Password</h3>
    </header>
    <div class=notification__box-content>
      <?php
      if(isset($_GET['reponse']) && $_GET['reponse']==-1){
		echo '<p class=center-text>Sorry, Your email or password is not correct</p>';
	  }elseif(isset($_POST["btnresetpswd"]) && $mErrorMessage!=''){
		echo '<p class="center-text '.$mStyle.'">'.$mErrorMessage.'</p>';
	  }
	  else{
	  	echo '<p class="center-text redtxt" id="error"></p>';
	  }
	  
	  ?>
      <form action="" method="post" id="resetpswdfrm">
        <fieldset>
          <label for="password">Password: </label>
          <input type="password" name="password" id="rest_password" />
          
          <label for="password">Confirm Password: </label>
          <input type="password" id="rest_cpassword" />
        </fieldset>
        
        <input type="submit" name="btnresetpswd" value="Reset Password" class="sign__action"/>
      </form>
    </div>
  </div>
</div>