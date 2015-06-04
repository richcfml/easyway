<?php
Log::write("Kiosk Parse Response ","URL: "."http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"],'kiosk');
if (isset($_REQUEST["ErrorNum"]))
{
    if ($_REQUEST["ErrorNum"]=="0000")
    {
        $_POST["invoice_number"] = $_REQUEST["InvoiceNumber"];
        $_POST["CardNumber"] = $_REQUEST["CardNumber"];
        $_POST["CardType"] = $_REQUEST["CardType"];
        $_POST["TransNumber"] = $_REQUEST["TransNumber"];
        
        $mName = "";
        if (isset($_REQUEST["CardHolderName"]))
        {
            $mName = $_REQUEST["CardHolderName"];
        }
        else if (isset($_REQUEST["CardHolder"]))
        {
            $mName = $_REQUEST["CardHolder"];
        }
        
        if ($mName!="")
        {
            if (strpos(trim($mName), " ")>=0)
            {
                $mTmp = explode(" ",trim($mName));
                $_POST["customer_name"] = $mTmp[0];
                $_POST["customer_last_name"] = $mTmp[1];
            }
            else
            {
                $_POST["customer_name"] = trim($mName);
                $_POST["customer_last_name"] = trim($mName);
            }
        }
        else
        {
            $_POST["customer_name"] = "";
            $_POST["customer_last_name"] = "";
        }
        
        include("submit_order.php");
    }
    else
    {
        redirect($SiteUrl.$objRestaurant->url."/?item=failed&kiosk=1&response_code=".$_REQUEST["ErrorNum"]);
    }
}
else
{
    redirect($SiteUrl.$objRestaurant->url."/?kiosk=1");
}
?>