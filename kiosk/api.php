<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" language="javascript">
    $(document).ready(function()
    {
        $("#btnSuccess").click(function()
        {
            window.location.href = "<?=$_REQUEST["RedirectURL"]?>&Amount=<?=$_REQUEST["Amount"]?>&InvoiceNumber=<?=$_REQUEST["InvoiceNum"]?>&PaymentType=<?=$_REQUEST["PaymentType"]?>&ErrorNum=0000&AuthCode=1&AuthMessage=1&TransNumber=1&DateTime=1&CardNumber=1&CardType=VISA&CardHolder=Gulfam Khan";
        });
        
        $("#btnFailure").click(function()
        {
            window.location.href = "<?=$_REQUEST["RedirectURL"]?>&Amount=<?=$_REQUEST["Amount"]?>&InvoiceNumber=<?=$_REQUEST["InvoiceNum"]?>&PaymentType=<?=$_REQUEST["PaymentType"]?>&ErrorNum=1111&AuthCode=1&AuthMessage=1&TransNumber=1&DateTime=1&CardNumber=1&CardType=VISA&CardHolder=Gulfam Khan";
        });
    });
</script>
    
<table style="width: 100%; font-family: Arial; font-size: 14px;"  cellpadding="0" cellspacing="0">
    <tr style="height: 80px;">
        <td colspan="4">
            
        </td>
    </tr>
    <tr style="background-color: #D6E2E0;">
        <td style="width: 15%;">
            
        </td>
        <td style="width: 25%;">
            <strong>Merchant ID:</strong>
        </td>
        <td style="width: 2%;">
            
        </td>
        <td style="width: 58%;">
            <span><?=$_REQUEST["MerchantID"]?></span>
        </td>
    </tr>
    <tr style="height: 20px;">
        <td colspan="4">
            
        </td>
    </tr>
    <tr style="background-color: #D6E2E0;">
        <td style="width: 15%;">
            
        </td>
        <td style="width: 25%;">
            <strong>Amount:</strong>
        </td>
        <td style="width: 2%;">
            
        </td>
        <td style="width: 58%;">
            <span><?=$_REQUEST["Amount"]?></span>
        </td>
    </tr>
    <tr style="height: 20px;">
        <td colspan="4">
            
        </td>
    </tr>
    <tr style="background-color: #D6E2E0;">
        <td style="width: 15%;">
            
        </td>
        <td style="width: 25%;">
            <strong>Invoice Number:</strong>
        </td>
        <td style="width: 2%;">
            
        </td>
        <td style="width: 58%;">
            <span><?=$_REQUEST["InvoiceNum"]?></span>
        </td>
    </tr>
    <tr style="height: 20px;">
        <td colspan="4">
            
        </td>
    </tr>
    <tr style="background-color: #D6E2E0;">
        <td style="width: 15%;">
            
        </td>
        <td style="width: 25%;">
            <strong>Payment Type:</strong>
        </td>
        <td style="width: 2%;">
            
        </td>
        <td style="width: 58%;">
            <span><?=$_REQUEST["PaymentType"]?></span>
        </td>
    </tr>
    <tr style="height: 20px;">
        <td colspan="4">
            
        </td>
    </tr>
    <tr style="background-color: #D6E2E0;">
        <td style="width: 15%;">
            
        </td>
        <td style="width: 25%;">
            <strong>Currency:</strong>
        </td>
        <td style="width: 2%;">
            
        </td>
        <td style="width: 58%;">
            <span><?=$_REQUEST["CurrencyIso"]?></span>
        </td>
    </tr>
    <tr style="height: 20px;">
        <td colspan="4">
            
        </td>
    </tr>
    <tr style="background-color: #D6E2E0;">
        <td style="width: 15%;">
            
        </td>
        <td style="width: 25%;">
            <strong>Capture Sign:</strong>
        </td>
        <td style="width: 2%;">
            
        </td>
        <td style="width: 58%;">
            <span><?=$_REQUEST["SignCapture"]?></span>
        </td>
    </tr>
    <tr style="height: 20px;">
        <td colspan="4">
            
        </td>
    </tr>
    <tr style="background-color: #D6E2E0;">
        <td style="width: 15%;">
            
        </td>
        <td style="width: 25%;">
            <strong>Redirect URL:</strong>
        </td>
        <td style="width: 2%;">
            
        </td>
        <td style="width: 58%;">
            <span><?=$_REQUEST["RedirectURL"]?></span>
        </td>
    </tr>
    <tr style="height: 40px;">
        <td colspan="4">
            
        </td>
    </tr>
    <tr>
        <td style="width: 15%;">
            
        </td>
        <td style="width: 85%;" colspan="3">
            <input type="button" id="btnSuccess" value="Success" style="width: 100px;"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="btnFailure" value="Failure"  style="width: 100px;"/>
        </td>
    </tr>
</table>
