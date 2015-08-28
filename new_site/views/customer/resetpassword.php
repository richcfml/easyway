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

$mStyle = "red";
$mErrorMessage = "";

if (isset($_POST["btnSubmit"]))
{
    if ($mDecryptedID!=0)
    {
        $mSalt = hash('sha256', mt_rand(10,1000000));    
        $ePassword= hash('sha256', $_POST["txtPassword"].$mSalt);
        if (dbAbstract::Update("UPDATE customer_registration SET salt='".$mSalt."', epassword='".$ePassword."' WHERE id=".$mDecryptedID))
        {
            $mStyle = "green";
            $mErrorMessage = "Password changed successfully.";
        }
        else
        {
            $mStyle = "red";
            $mErrorMessage = "Error occurred.";
        }
    }
}
?>
<script type="text/javascript" language="javascript">
    $(document).ready(function()
    {
        $("#btnSubmit").unbind("click").click(function(e)
        {
            $("#spnPassword").hide();
            $("#spnCPassword").hide();
            if ($.trim($("#txtPassword").val())=="")
            {
                $("#spnPassword").text("Required");
                $("#spnPassword").show();
                e.preventDefault();
            }
            else if ($.trim($("#txtPassword").val()).length<5)
            {
                $("#spnPassword").text("Minimum length for password is 5");
                $("#spnPassword").show();
                e.preventDefault();
            }
            else if ($.trim($("#txtCPassword").val())=="")
            {
                $("#spnCPassword").text("Required");
                $("#spnCPassword").show();
                e.preventDefault();
            }
            else if ($.trim($("#txtPassword").val())!=$.trim($("#txtCPassword").val()))
            {
                $("#spnCPassword").text("Password mismatch");
                $("#spnCPassword").show();
                e.preventDefault();
            }
            else
            {
                return true;
            }
        });
    });
</script>
<form id="frmResetPasswor" name="frmResetPasswor" method="post">
    <table style="width: 100%; font-size: 12px;" border="0" cellpadding="0" cellspacing="0">
        <tr style="height: 100px;">
            <td colspan="5">
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">   
            </td>
            <td style="width: 90%;" colspan="4">
                <span style="color:<?=$mStyle?>;"><?php echo($mErrorMessage) ?></span>
            </td>
        </tr>
        <tr style="height: 30px;">
            <td colspan="5">
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">   
            </td>
            <td style="width: 20%; font-weight: bold;">   
                New Password&nbsp;<span style="color: red;">*</span>
            </td>
            <td style="width: 5%;">   
            </td>
            <td style="width: 55%;">   
                <input type="password" id="txtPassword" name="txtPassword" style="width: 200px;" />&nbsp;<span id="spnPassword" style="display: none; color: red;"></span>
            </td>
            <td style="width: 10%;">   
            </td>
        </tr>
        <tr style="height: 20px;">
            <td colspan="5">
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">   
            </td>
            <td style="width: 20%; font-weight: bold;">   
                Confirm New Password&nbsp;<span style="color: red;">*</span>
            </td>
            <td style="width: 5%;">   
            </td>
            <td style="width: 55%;">   
                <input type="password" id="txtCPassword" name="txtCPassword" style="width: 200px;" />&nbsp;<span id="spnCPassword" style="display: none;  color: red;"></span>
            </td>
            <td style="width: 10%;">   
            </td>
        </tr>
        <tr style="height: 20px;">
            <td colspan="5">
            </td>
        </tr>
        <tr>
            <td style="width: 10%;">   
            </td>
            <td style="width: 20%;">   

            </td>
            <td style="width: 5%;">   
            </td>
            <td style="width: 65%; text-align: left;" colspan="2">   
                <input type="submit" value="Submit" id="btnSubmit" name="btnSubmit" style="width: 100px;" />
            </td>
        </tr>
        <tr style="height: 100px;">
            <td colspan="5">
            </td>
        </tr>
    </table>
</form>